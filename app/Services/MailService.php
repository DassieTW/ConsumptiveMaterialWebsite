<?php

namespace App\Services;

use Illuminate\Http\Request;
use Session;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use DB;
use Mail;
use File;
use App\Models\Inventory;
use App\Models\whereInMultiCol;
use Sentry\Util\JSON;

/**
 * Class MailService
 */
class MailService
{
    /**
     * @return string
     */
    public function download() // 安全庫存 寄警報信
    {
        $databases = config('database_list.databases');
        array_shift($databases); // remove the 'Consumables management' db from array
        $AllISNClientsPairs = array("isn" => array());

        \Log::channel('dbquerys')->info('---------------------------Mail Service Alarm--------------------------');
        foreach ($databases as $database) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));
            \Log::channel('dbquerys')->info('---------------------------DB :' . $database . '--------------------------');
            $inventorys = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 料號'))->groupBy('料號');
            $datas = \DB::table('月請購_單耗')
                ->join('MPS', function ($join) {
                    $join->on('MPS.料號90', '=', '月請購_單耗.料號90')
                        ->on('MPS.料號', '=', '月請購_單耗.料號');
                })
                ->join('consumptive_material', function ($join) {
                    $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                })
                ->leftjoin('safestock報警備註', function ($join) {
                    $join->on('safestock報警備註.料號', '=', '月請購_單耗.料號');
                })
                ->leftJoinSub($inventorys, 'suminventory', function ($join) {
                    $join->on('月請購_單耗.料號', '=', 'suminventory.料號');
                })
                ->select(
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.LT',
                    'consumptive_material.月請購',
                    'consumptive_material.安全庫存',
                    // 'consumptive_material.耗材歸屬',
                    '月請購_單耗.單耗',
                    'MPS.下月MPS',
                    'MPS.下月生產天數',
                    'inventory現有庫存',
                    'safestock報警備註.備註',
                )->groupBy(
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.LT',
                    'consumptive_material.月請購',
                    'consumptive_material.安全庫存',
                    // 'consumptive_material.耗材歸屬',
                    '月請購_單耗.單耗',
                    'MPS.下月MPS',
                    'MPS.下月生產天數',
                    'inventory現有庫存',
                    'safestock報警備註.備註',
                )
                ->where('consumptive_material.月請購', '=', "是")
                // ->where('consumptive_material.耗材歸屬', '=', "單耗")
                ->where('月請購_單耗.狀態', '=', "已完成")
                ->get()->toArray();



            foreach ($datas as $data) {
                $safe =  5 * $data->單耗 * $data->下月MPS / 26;
                $data->安全庫存 = round($safe);
            } // for each

            $count = count($datas);
            for ($a = 0; $a < $count; $a++) {
                for ($i = $a; $i + 1 < $count; $i++) {
                    if ((isset($datas[$a])) && (isset($datas[$i + 1]))) {
                        if ($datas[$a]->料號 === $datas[$i + 1]->料號) {
                            $datas[$a]->安全庫存 += $datas[$i + 1]->安全庫存;
                            unset($datas[$i + 1]);
                            $datas = array_values($datas);
                        } // if
                    } // if
                } // for
            } // for

            foreach ($datas as $key => $value) {
                if ($value->inventory現有庫存 > $value->安全庫存) {
                    unset($datas[$key]);
                }
            }
            $datas = array_values($datas);

            $inventorys2 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 ,料號'))->groupBy('料號');

            $datas2 = DB::table('consumptive_material')
                ->leftJoinSub($inventorys2, 'suminventory', function ($join) {
                    $join->on('consumptive_material.料號', '=', 'suminventory.料號');
                })
                ->leftjoin('safestock報警備註', function ($join) {
                    $join->on('safestock報警備註.料號', '=', 'consumptive_material.料號');
                })
                ->select(
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.安全庫存',
                    'consumptive_material.月請購',
                    'inventory現有庫存',
                    'safestock報警備註.備註',
                )->groupBy(
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.安全庫存',
                    'consumptive_material.月請購',
                    'inventory現有庫存',
                    'safestock報警備註.備註',
                )
                ->where('consumptive_material.月請購', '=', "否")
                ->get()->unique('料號')->toArray();

            foreach ($datas2 as $data) {
                $safe = $data->安全庫存;
                $data->安全庫存 = round($safe);
            } // for each

            foreach ($datas2 as $key => $value) {
                if ($value->inventory現有庫存 >= $value->安全庫存) {
                    unset($datas2[$key]);
                }
            }
            $datas2 = array_values($datas2);
            foreach ($datas2 as $data) {
                $allstock = DB::table('inventory')->where('inventory.料號', '=', $data->料號)->sum('inventory.現有庫存');
                $data->inventory現有庫存 = round($allstock, 0);
            }

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
            $worksheet = $spreadsheet->getActiveSheet();
            $notmonth = \Lang::get('callpageLang.notmonth');
            $ismonth = \Lang::get('callpageLang.ismonth');
            $count2 = count($datas);

            //填寫表頭
            $worksheet->setCellValueByColumnAndRow(1, 1, \Lang::get('callpageLang.month'));
            $worksheet->setCellValueByColumnAndRow(2, 1, \Lang::get('callpageLang.isn'));
            $worksheet->setCellValueByColumnAndRow(3, 1, \Lang::get('callpageLang.pName'));
            $worksheet->setCellValueByColumnAndRow(4, 1, \Lang::get('callpageLang.format'));
            $worksheet->setCellValueByColumnAndRow(5, 1, \Lang::get('callpageLang.stock'));
            $worksheet->setCellValueByColumnAndRow(6, 1, \Lang::get('callpageLang.safe'));
            $worksheet->setCellValueByColumnAndRow(7, 1, \Lang::get('callpageLang.mark'));

            //填寫內容
            for ($j = 0; $j < count($datas); $j++) {
                $worksheet->setCellValueByColumnAndRow(1, $j + 2, $ismonth);
                $worksheet->setCellValueByColumnAndRow(2, $j + 2, $datas[$j]->料號);
                $worksheet->setCellValueByColumnAndRow(3, $j + 2, $datas[$j]->品名);
                $worksheet->setCellValueByColumnAndRow(4, $j + 2, $datas[$j]->規格);
                $worksheet->setCellValueByColumnAndRow(5, $j + 2, $datas[$j]->inventory現有庫存);
                $worksheet->setCellValueByColumnAndRow(6, $j + 2, $datas[$j]->安全庫存);
                $worksheet->setCellValueByColumnAndRow(7, $j + 2, $datas[$j]->備註);

                array_push($AllISNClientsPairs["isn"], $datas[$j]->料號);
            } // for
            //填寫內容

            //填寫內容
            for ($i = $count2, $j = 0; $j < count($datas2); $i++, $j++) {
                $worksheet->setCellValueByColumnAndRow(1, $i + 2, $notmonth);
                $worksheet->setCellValueByColumnAndRow(2, $i + 2, $datas2[$j]->料號);
                $worksheet->setCellValueByColumnAndRow(3, $i + 2, $datas2[$j]->品名);
                $worksheet->setCellValueByColumnAndRow(4, $i + 2, $datas2[$j]->規格);
                $worksheet->setCellValueByColumnAndRow(5, $i + 2, $datas2[$j]->inventory現有庫存);
                $worksheet->setCellValueByColumnAndRow(6, $i + 2, $datas2[$j]->安全庫存);
                $worksheet->setCellValueByColumnAndRow(7, $i + 2, $datas2[$j]->備註);

                array_push($AllISNClientsPairs["isn"], $datas2[$j]->料號);
                array_push($AllISNClientsPairs["client"], "非月請購");
            } // for

            // 下載
            $now = Carbon::now()->format('Ymd');
            $filename = $database . 'Safe Stock' . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save(public_path() . "/excel/" . $filename);

            $num = count($datas) + count($datas2);

            if ($num > 0) {
                Mail::send('mail/safestock', [], function ($message) use ($now, $database) {
                    $emails = DB::table('login')->select('email')->whereNotNull('email')->where('priority', '<', 4)->get()->toArray();

                    foreach ($emails as $email) {

                        if (!empty($email->email) && filter_var($email->email, FILTER_VALIDATE_EMAIL)) {
                            // valid emailaddress
                            //dd($email);
                            $message->to($email->email)->subject('Safe Stock');
                        }
                    }
                    $message->bcc('Vincent6_Yeh@pegatroncorp.com');

                    $message->attach(public_path() . '/excel/' . $database . 'Safe Stock' . $now . '.xlsx');
                    $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
                });
            }

            File::delete(public_path() . '/excel/' . $database . 'Safe Stock' . $now . '.xlsx');

            // ------ delete unused comment ----------
            $value_pairs = array();

            for ($i = 0; $i < count($AllISNClientsPairs['isn']); $i++) {
                array_push($value_pairs, $AllISNClientsPairs['isn'][$i]);
            } // for

            $all_remarks = DB::table('safestock報警備註')->select('料號')->get();
            $all_remarks_pairs = array();

            foreach ($all_remarks as $remark) {
                array_push($all_remarks_pairs, [$remark->料號, $remark->客戶別]);
            } // foreach

            // dd($all_remarks_pairs, $value_pairs); // test

            // Compare all values by a json_encode
            $diff = array_diff(array_map('json_encode', $all_remarks_pairs), array_map('json_encode', $value_pairs));

            // Json decode the result
            $diff = array_map('json_decode', $diff);
            // dd($all_remarks_pairs, $value_pairs, $diff); // test

            $rowsAffected = DB::table('safestock報警備註')
                ->where(function ($query) use ($diff) {
                    whereInMultiCol::scopeWhereInMultiple($query, ['料號'], $diff);
                })
                ->delete();

            \Log::channel('dbquerys')->info('--------------------------deleted ' . $rowsAffected . ' rows in ' . $database . ' 安全庫存備註Table--------------------------');
        } // for each
    } // 安全庫存 寄警報信

    public function day() // 呆滯天數 寄警報信
    {
        $databases = config('database_list.databases');
        array_shift($databases); // remove the 'Consumables management' db from array
        $now = strtotime(Carbon::now()->format('Ymd'));
        $AllISNClientsPairsDay = array("isn" => array(), "client" => array());

        foreach ($databases as $database) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));
            \Log::channel('dbquerys')->info('---------------------------DB :' . $database . '--------------------------');

            $datas = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                ->leftjoin('sluggish報警備註', function ($join) {
                    $join->on('sluggish報警備註.料號', '=', 'inventory.料號');
                })
                ->select(
                    'inventory.料號',
                    DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                    DB::raw('sum(inventory.現有庫存) as inventory現有庫存'),
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'sluggish報警備註.備註',
                )
                ->groupBy('inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格', 'sluggish報警備註.備註')
                ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間), getdate())>90')
                ->havingRaw('sum(inventory.現有庫存) > ?', [0])
                ->get();

            foreach ($datas as $data) {
                $maxtime = date_create(date('Y-m-d', strtotime($data->inventory最後更新時間)));
                $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
                $interval = date_diff($maxtime, $nowtime);
                $interval = $interval->format('%R%a');
                $stayday = (int)($interval);

                $data->inventory最後更新時間 = $stayday;
            } // for each


            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
            $worksheet = $spreadsheet->getActiveSheet();

            //填寫表頭
            $worksheet->setCellValueByColumnAndRow(1, 1, \Lang::get('callpageLang.isn'));
            $worksheet->setCellValueByColumnAndRow(2, 1, \Lang::get('callpageLang.pName'));
            $worksheet->setCellValueByColumnAndRow(3, 1, \Lang::get('callpageLang.format'));
            $worksheet->setCellValueByColumnAndRow(4, 1, \Lang::get('callpageLang.stock'));
            $worksheet->setCellValueByColumnAndRow(5, 1, \Lang::get('callpageLang.days'));
            $worksheet->setCellValueByColumnAndRow(6, 1, \Lang::get('callpageLang.mark'));

            //填寫內容
            for ($j = 0; $j < count($datas); $j++) {
                $worksheet->setCellValueByColumnAndRow(1, $j + 2, $datas[$j]->料號);
                $worksheet->setCellValueByColumnAndRow(2, $j + 2, $datas[$j]->品名);
                $worksheet->setCellValueByColumnAndRow(3, $j + 2, $datas[$j]->規格);
                $worksheet->setCellValueByColumnAndRow(4, $j + 2, $datas[$j]->inventory現有庫存);
                $worksheet->setCellValueByColumnAndRow(5, $j + 2, $datas[$j]->inventory最後更新時間);
                $worksheet->setCellValueByColumnAndRow(6, $j + 2, $datas[$j]->備註);

                array_push($AllISNClientsPairsDay["isn"], $datas[$j]->料號);
            } // for

            $now = Carbon::now()->format('Ymd');
            $filename = $database . 'Passive Day' . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save(public_path() . "/excel/" . $filename);


            $num = count($datas);

            if ($num > 0) {
                Mail::send('mail/sluggishstock', [], function ($message) use ($now, $database) {
                    $emails = DB::table('login')->select('email')->whereNotNull('email')->where('priority', '<', 4)->get()->toArray();

                    foreach ($emails as $email) {

                        if (!empty($email->email) && filter_var($email->email, FILTER_VALIDATE_EMAIL)) {
                            // valid emailaddress
                            $message->to($email->email)->subject('Passive Day');
                        }
                    }

                    $message->bcc('Vincent6_Yeh@pegatroncorp.com');

                    $message->attach(public_path() . '/excel/' . $database . 'Passive Day' . $now . '.xlsx');
                    $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
                });
            } // if

            File::delete(public_path() . '/excel/' . $database . 'Passive Day' . $now . '.xlsx');

            // ------ delete unused comment ----------

            $value_pairs_day = array();

            for ($i = 0; $i < count($AllISNClientsPairsDay['isn']); $i++) {
                array_push($value_pairs_day, $AllISNClientsPairsDay['isn'][$i]);
            } // for

            $all_remarks_day = DB::table('sluggish報警備註')->select('料號')->get();
            $all_remarks_pairs_day = array();

            foreach ($all_remarks_day as $remark) {
                array_push($all_remarks_pairs_day, [$remark->料號, $remark->客戶別]);
            } // foreach

            // Compare all values by a json_encode
            $diff_day = array_diff(array_map('json_encode', $all_remarks_pairs_day), array_map('json_encode', $value_pairs_day));

            // Json decode the result
            $diff_day = array_map('json_decode', $diff_day);
            // dd($all_remarks_pairs, $value_pairs, $diff); // test

            $rowsAffected = DB::table('sluggish報警備註')
                ->where(function ($query) use ($diff_day) {
                    whereInMultiCol::scopeWhereInMultiple($query, ['料號'], $diff_day);
                })
                ->delete();

            \Log::channel('dbquerys')->info('--------------------------deleted ' . $rowsAffected . ' rows in ' . $database . ' 呆滯庫存備註Table--------------------------');
        } // foreach
    } // 呆滯庫存 寄警報信
} // end of class
