<?php

namespace App\Services;

use Illuminate\Http\Request;
use Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use DB;
use Mail;
use File;
use App\Models\Inventory;

/**
 * Class MailService
 */
class MailService
{
    /**
     * @return string
     */
    public function download()
    {

        $databases = [
            'M2_TEST_1112', '巴淡SMT1214', 'BB1_1214 Consumables management',
            '巴淡-LOT11 Consumables management', '巴淡-LOT2 Consumables management', '巴淡-PTSN Consumables management'
        ];

        foreach ($databases as $database) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));

            $inventorys = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號'))->groupBy('客戶別', '料號');
            $datas = DB::table('月請購_單耗')
                ->join('MPS', function ($join) {
                    $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                        ->on('MPS.機種', '=', '月請購_單耗.機種')
                        ->on('MPS.製程', '=', '月請購_單耗.製程');
                })
                ->join('consumptive_material', function ($join) {
                    $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                })
                ->leftJoinSub($inventorys, 'suminventory', function ($join) {
                    $join->on('月請購_單耗.客戶別', '=', 'suminventory.客戶別');
                    $join->on('月請購_單耗.料號', '=', 'suminventory.料號');
                })

                ->select(
                    '月請購_單耗.客戶別',
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.LT',
                    'consumptive_material.月請購',
                    'consumptive_material.安全庫存',
                    '月請購_單耗.單耗',
                    'MPS.下月MPS',
                    'MPS.下月生產天數',
                    'inventory現有庫存',
                )->groupBy(
                    '月請購_單耗.客戶別',
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.LT',
                    'consumptive_material.月請購',
                    'consumptive_material.安全庫存',
                    '月請購_單耗.單耗',
                    'MPS.下月MPS',
                    'MPS.下月生產天數',
                    'inventory現有庫存',
                )
                ->where('consumptive_material.月請購', '=', "是")
                ->where('月請購_單耗.狀態', '=', "已完成")
                ->get()->toArray();



            foreach ($datas as $data) {
                $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
                $data->安全庫存 = round($safe);
            } // for each

            $count = count($datas);
            for ($a = 0; $a < $count; $a++) {
                for ($i = $a; $i + 1 < $count; $i++) {

                    if ($datas[$a]->客戶別 === $datas[$i + 1]->客戶別 && $datas[$a]->料號 === $datas[$i + 1]->料號) {


                        $datas[$a]->安全庫存 += $datas[$i + 1]->安全庫存;
                        unset($datas[$i + 1]);
                        $datas = array_values($datas);
                    } // if
                } // for
            } // for

            foreach ($datas as $key => $value) {


                if ($value->inventory現有庫存 > $value->安全庫存) {
                    unset($datas[$key]);
                }
            }
            $datas = array_values($datas);

            $inventorys1 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號'))->groupBy('客戶別', '料號');
            $datas1 = DB::table('月請購_站位')
                ->join('MPS', function ($join) {
                    $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                        ->on('MPS.機種', '=', '月請購_站位.機種')
                        ->on('MPS.製程', '=', '月請購_站位.製程');
                })
                ->join('consumptive_material', function ($join) {
                    $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                })
                ->leftJoinSub($inventorys1, 'suminventory', function ($join) {
                    $join->on('月請購_站位.客戶別', '=', 'suminventory.客戶別');
                    $join->on('月請購_站位.料號', '=', 'suminventory.料號');
                })

                ->select(
                    '月請購_站位.客戶別',
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.LT',
                    'consumptive_material.月請購',
                    'consumptive_material.MPQ',
                    'consumptive_material.安全庫存',
                    '月請購_站位.下月站位人數',
                    '月請購_站位.下月開線數',
                    '月請購_站位.下月開班數',
                    '月請購_站位.下月每人每日需求量',
                    '月請購_站位.下月每日更換頻率',
                    'inventory現有庫存',
                )->groupBy(
                    '月請購_站位.客戶別',
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.LT',
                    'consumptive_material.月請購',
                    'consumptive_material.MPQ',
                    'consumptive_material.安全庫存',
                    '月請購_站位.下月站位人數',
                    '月請購_站位.下月開線數',
                    '月請購_站位.下月開班數',
                    '月請購_站位.下月每人每日需求量',
                    '月請購_站位.下月每日更換頻率',
                    'inventory現有庫存',
                )
                ->where('consumptive_material.月請購', '=', "是")
                ->where('月請購_站位.狀態', '=', "已完成")
                ->get()->toArray();


            foreach ($datas1 as $data) {
                $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
                $data->安全庫存 = round($safe);
            }

            $count1 = count($datas1);
            for ($a = 0; $a < $count1; $a++) {
                for ($i = $a; $i + 1 < $count1; $i++) {

                    if ($datas1[$a]->客戶別 === $datas1[$i + 1]->客戶別 && $datas1[$a]->料號 === $datas1[$i + 1]->料號) {


                        $datas1[$a]->安全庫存 += $datas1[$i + 1]->安全庫存;
                        unset($datas1[$i + 1]);
                        $datas1 = array_values($datas1);
                    } // if
                } // for
            } // for

            foreach ($datas1 as $key => $value) {


                if ($value->inventory現有庫存 > $value->安全庫存) {
                    unset($datas1[$key]);
                }
            }
            $datas1 = array_values($datas1);

            $inventorys2 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 ,客戶別  ,料號'))->groupBy('客戶別', '料號');

            $datas2 = DB::table('consumptive_material')
                ->leftJoinSub($inventorys2, 'suminventory', function ($join) {
                    $join->on('consumptive_material.料號', '=', 'suminventory.料號');
                })
                ->select(
                    '客戶別',
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.安全庫存',
                    'consumptive_material.月請購',
                    'inventory現有庫存',
                )->groupBy(
                    '客戶別',
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                    'consumptive_material.安全庫存',
                    'consumptive_material.月請購',
                    'inventory現有庫存',

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
            $count2 = count($datas) + count($datas1);

            //填寫表頭
            $worksheet->setCellValueByColumnAndRow(1, 1, \Lang::get('callpageLang.client'));
            $worksheet->setCellValueByColumnAndRow(2, 1, \Lang::get('callpageLang.isn'));
            $worksheet->setCellValueByColumnAndRow(3, 1, \Lang::get('callpageLang.pName'));
            $worksheet->setCellValueByColumnAndRow(4, 1, \Lang::get('callpageLang.format'));
            $worksheet->setCellValueByColumnAndRow(5, 1, \Lang::get('callpageLang.stock'));
            $worksheet->setCellValueByColumnAndRow(6, 1, \Lang::get('callpageLang.safe'));

            //填寫內容
            for ($j = 0; $j < count($datas); $j++) {
                $worksheet->setCellValueByColumnAndRow(1, $j + 2, $datas[$j]->客戶別);
                $worksheet->setCellValueByColumnAndRow(2, $j + 2, $datas[$j]->料號);
                $worksheet->setCellValueByColumnAndRow(3, $j + 2, $datas[$j]->品名);
                $worksheet->setCellValueByColumnAndRow(4, $j + 2, $datas[$j]->規格);
                $worksheet->setCellValueByColumnAndRow(5, $j + 2, $datas[$j]->inventory現有庫存);
                $worksheet->setCellValueByColumnAndRow(6, $j + 2, $datas[$j]->安全庫存);
            }
            //填寫內容
            for ($i = count($datas), $j = 0; $j < count($datas1); $i++, $j++) {
                $worksheet->setCellValueByColumnAndRow(1, $i + 2, $datas1[$j]->客戶別);
                $worksheet->setCellValueByColumnAndRow(2, $i + 2, $datas1[$j]->料號);
                $worksheet->setCellValueByColumnAndRow(3, $i + 2, $datas1[$j]->品名);
                $worksheet->setCellValueByColumnAndRow(4, $i + 2, $datas1[$j]->規格);
                $worksheet->setCellValueByColumnAndRow(5, $i + 2, $datas1[$j]->inventory現有庫存);
                $worksheet->setCellValueByColumnAndRow(6, $i + 2, $datas1[$j]->安全庫存);
            }
            //填寫內容
            for ($i = $count2, $j = 0; $j < count($datas2); $i++, $j++) {
                $worksheet->setCellValueByColumnAndRow(1, $i + 2, $notmonth);
                $worksheet->setCellValueByColumnAndRow(2, $i + 2, $datas2[$j]->料號);
                $worksheet->setCellValueByColumnAndRow(3, $i + 2, $datas2[$j]->品名);
                $worksheet->setCellValueByColumnAndRow(4, $i + 2, $datas2[$j]->規格);
                $worksheet->setCellValueByColumnAndRow(5, $i + 2, $datas2[$j]->inventory現有庫存);
                $worksheet->setCellValueByColumnAndRow(6, $i + 2, $datas2[$j]->安全庫存);
            }
            // 下載
            $now = Carbon::now()->format('Ymd');
            $filename = $database . 'Safe Stock' . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save(public_path() . "/excel/" . $filename);


            Mail::send('mail/safestock', [], function ($message) use ($now, $database) {
                // $email = 't22923200@gmail.com';
                $emails = DB::table('login')->select('email')->whereNotNull('email')->where('priority', '<', 4)->get()->toArray();

                foreach ($emails as $email) {

                    $message->to($email->email)->subject('Safe Stock');
                }

                // $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                // $message->bcc('Tony_Tseng@pegatroncorp.com');
                // $message->bcc('t22923200@gmail.com');

                $message->attach(public_path() . '/excel/' . $database . 'Safe Stock' . $now . '.xlsx');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            });

            File::delete(public_path() . '/excel/' . $database . 'Safe Stock' . $now . '.xlsx');
        }
    }

    public function day()
    {

        $databases = [
            'M2_TEST_1112', '巴淡SMT1214', 'BB1_1214 Consumables management',
            '巴淡-LOT11 Consumables management', '巴淡-LOT2 Consumables management', '巴淡-PTSN Consumables management'
        ];
        $now = strtotime(Carbon::now()->format('Ymd'));
        foreach ($databases as $database) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));
            $datas = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                ->select(
                    'inventory.客戶別',
                    'inventory.料號',
                    DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                    DB::raw('sum(inventory.現有庫存) as inventory現有庫存'),
                    'consumptive_material.品名',
                    'consumptive_material.規格',
                )
                ->groupBy('inventory.客戶別', 'inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格',)
                ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間), getdate())>30')
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
            $worksheet->setCellValueByColumnAndRow(1, 1, \Lang::get('callpageLang.client'));
            $worksheet->setCellValueByColumnAndRow(2, 1, \Lang::get('callpageLang.isn'));
            $worksheet->setCellValueByColumnAndRow(3, 1, \Lang::get('callpageLang.pName'));
            $worksheet->setCellValueByColumnAndRow(4, 1, \Lang::get('callpageLang.format'));
            $worksheet->setCellValueByColumnAndRow(5, 1, \Lang::get('callpageLang.stock'));
            $worksheet->setCellValueByColumnAndRow(6, 1, \Lang::get('callpageLang.days'));

            //填寫內容
            for ($j = 0; $j < count($datas); $j++) {
                $worksheet->setCellValueByColumnAndRow(1, $j + 2, $datas[$j]->客戶別);
                $worksheet->setCellValueByColumnAndRow(2, $j + 2, $datas[$j]->料號);
                $worksheet->setCellValueByColumnAndRow(3, $j + 2, $datas[$j]->品名);
                $worksheet->setCellValueByColumnAndRow(4, $j + 2, $datas[$j]->規格);
                $worksheet->setCellValueByColumnAndRow(5, $j + 2, $datas[$j]->inventory現有庫存);
                $worksheet->setCellValueByColumnAndRow(6, $j + 2, $datas[$j]->inventory最後更新時間);
            }

            $now = Carbon::now()->format('Ymd');
            $filename = $database . 'Passive Day' . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save(public_path() . "/excel/" . $filename);


            Mail::send('mail/safestock', [], function ($message) use ($now, $database) {
                // $email = 't22923200@gmail.com';
                $emails = DB::table('login')->select('email')->whereNotNull('email')->where('priority', '<', 4)->get()->toArray();

                foreach ($emails as $email) {

                    $message->to($email->email)->subject('Passive Day');
                }

                // $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                // $message->bcc('Tony_Tseng@pegatroncorp.com');
                // $message->bcc('t22923200@gmail.com');

                $message->attach(public_path() . '/excel/' . $database . 'Passive Day' . $now . '.xlsx');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            });

            File::delete(public_path() . '/excel/' . $database . 'Passive Day' . $now . '.xlsx');
        }
    }
}
