<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\人員信息;
use App\Models\客戶別;
use App\Models\機種;
use App\Models\製程;
use App\Models\線別;
use App\Models\領用原因;
use App\Models\退回原因;
use App\Models\發料部門;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\儲位;
use App\Models\Inventory;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OutboundController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //領料單
    public function picklist(Request $request)
    {
        $list = $request->input('list');
        if ($list === null) {
            return $request->validate([
                'list' => 'required',
            ]);
        } else {
            if ($request->input('send') === null) {

                $datas =  DB::table('outbound')
                    ->join('consumptive_material', 'outbound.料號', '=', 'consumptive_material.料號')
                    ->wherenull('outbound.發料人員')
                    ->select('outbound.*', 'consumptive_material.發料部門')
                    ->get();
            } else {

                $datas =  DB::table('outbound')
                    ->join('consumptive_material', 'outbound.料號', '=', 'consumptive_material.料號')
                    ->where('consumptive_material.發料部門', '=', $request->input('send'))
                    ->wherenull('outbound.發料人員')
                    ->select('outbound.*', 'consumptive_material.發料部門')
                    ->get();
            }


            return view('outbound.picklist')->with(['number' => $datas->where('領料單號', $list)])
                ->with(['data' => $datas->where('領料單號', $list)])
                ->with(['people' => 人員信息::cursor()])
                ->with(['people1' => 人員信息::cursor()])
                ->with(['check' => 人員信息::cursor()]);
        }
    }

    // //退料單
    public function backlist(Request $request)
    {
        $list =  $request->input('list');
        if ($list === null) {
            return $request->validate([
                'list' => 'required',
            ]);
        } else {
            if ($request->input('send') === null) {

                $datas =  DB::table('出庫退料')
                    ->join('consumptive_material', '出庫退料.料號', '=', 'consumptive_material.料號')
                    ->wherenull('出庫退料.收料人員')
                    ->select('出庫退料.*', 'consumptive_material.發料部門')
                    ->get();
            } else {
                $datas =  DB::table('出庫退料')
                    ->join('consumptive_material', '出庫退料.料號', '=', 'consumptive_material.料號')
                    ->where('consumptive_material.發料部門', '=', $request->input('send'))
                    ->wherenull('出庫退料.收料人員')
                    ->select('出庫退料.*', 'consumptive_material.發料部門')
                    ->get();
            }

            return view('outbound.backlist')->with(['number' => $datas->where('退料單號', $list)])
                ->with(['data' => $datas->where('退料單號', $list)])
                ->with(['people' => 人員信息::cursor()])
                ->with(['people1' => 人員信息::cursor()])
                ->with(['locs' => 儲位::cursor()])
                ->with(['check' => 人員信息::cursor()]);
        }
    }

    //領料添加
    public function pickadd(Request $request)
    {
        $line = $request->input('line');
        $usereason = $request->input('usereason');
        $number = $request->input('number');
        $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
        $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
        $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
        $send = DB::table('consumptive_material')->where('料號', $number)->value('發料部門');

        $stock = DB::table('inventory')->where('料號', $number)->sum('現有庫存');


        $showstock  = '';
        $nowstock = DB::table('inventory')->where('料號', $number)->where('現有庫存', '>', 0)->pluck('現有庫存')->toArray();
        $nowloc = DB::table('inventory')->where('料號', $number)->where('現有庫存', '>', 0)->pluck('儲位')->toArray();
        $test = array_combine($nowloc, $nowstock);
        foreach ($test as $k => $a) {
            $showstock = $showstock . __('outboundpageLang.loc') . ' : ' . $k . ' ' . __('outboundpageLang.nowstock') . ' : ' . $a . "\r\n";
        }

        if ($name !== null && $format !== null) {
            if ($stock > 0) {
                return \Response::json([
                    'number' => $number, 'line' => $line, 'usereason' => $usereason, 'name' => $name,
                    'format' => $format, 'unit' => $unit, 'send' => $send, 'showstock' => $showstock,
                ]/* Status code here default is 200 ok*/);
            }
            //沒有庫存
            else {
                return \Response::json(['message' => 'no stock'], 420/* Status code here default is 200 ok*/);
            }
        }
        //沒有料號
        else {
            return \Response::json(['message' => 'no isn'], 421/* Status code here default is 200 ok*/);
        }
    }

    //退料添加
    public function backadd(Request $request)
    {
        $line = $request->input('line');
        $backreason = $request->input('backreason');
        $number = $request->input('number');
        $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
        $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
        $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
        $send = DB::table('consumptive_material')->where('料號', $number)->value('發料部門');

        if ($name !== null && $format !== null) {

            return \Response::json([
                'number' => $number, 'line' => $line, 'backreason' => $backreason, 'name' => $name,
                'format' => $format, 'unit' => $unit, 'send' => $send,
            ]/* Status code here default is 200 ok*/);
        }
        //料號不存在
        else {
            return \Response::json(['message' => 'no isn'], 420/* Status code here default is 200 ok*/);
        }
    }

    //提交領料添加
    public function pickaddsubmit(Request $request)
    {
        $count = $request->input('count');
        $j = '0001';
        $max = DB::table('outbound')->max('開單時間');
        $maxtime = date_create(date('Y-m-d', strtotime($max)));
        $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
        $interval = date_diff($maxtime, $nowtime);
        $interval = $interval->format('%R%a');
        $interval = (int)($interval);
        if ($interval > 0) {
            $opentime = Carbon::now()->format('Ymd') . $j;
        } else {
            $num = DB::table('outbound')->max('領料單號');
            $num = intval($num);
            $num++;
            $num = strval($num);
            $opentime = $num;
        }

        $Alldata = json_decode($request->input('AllData'));

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $line = $Alldata[0][$i];
                $usereason = $Alldata[1][$i];
                $number = $Alldata[2][$i];
                $name = $Alldata[3][$i];
                $format = $Alldata[4][$i];
                $unit = $Alldata[5][$i];
                $amount = $Alldata[6][$i];
                $remark = $Alldata[7][$i];
                if ($remark === null) $remark = '';
                DB::table('outbound')
                    ->insert([
                        '領用原因' => $usereason, '線別' => $line,
                        '料號' => $number, '品名' => $name, '規格' => $format, '單位' => $unit, '預領數量' => $amount,
                        '實際領用數量' => $amount, '備註' => $remark, '領料單號' => $opentime, '開單時間' => Carbon::now()
                    ]);
            } //for
            DB::commit();
            return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } // catch
    }

    //提交退料添加
    public function backaddsubmit(Request $request)
    {
        $count = $request->input('count');
        $j = '0001';
        $max = DB::table('出庫退料')->max('開單時間');
        $maxtime = date_create(date('Y-m-d', strtotime($max)));
        $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
        $interval = date_diff($maxtime, $nowtime);
        $interval = $interval->format('%R%a');
        $interval = (int)($interval);
        if ($interval > 0) {
            $opentime = Carbon::now()->format('Ymd') . $j;
        } else {
            $num = DB::table('出庫退料')->max('退料單號');
            $num = intval($num);
            $num++;
            $num = strval($num);
            $opentime = $num;
        }
        $Alldata = json_decode($request->input('AllData'));

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $line = $Alldata[0][$i];
                $backreason = $Alldata[1][$i];
                $number = $Alldata[2][$i];
                $name = $Alldata[3][$i];
                $format = $Alldata[4][$i];
                $unit = $Alldata[5][$i];
                $amount = $Alldata[6][$i];
                $remark = $Alldata[7][$i];
                if ($remark === null) $remark = '';
                DB::table('出庫退料')
                    ->insert([
                        '退回原因' => $backreason, '線別' => $line,
                        '料號' => $number, '品名' => $name, '規格' => $format, '單位' => $unit, '預退數量' => $amount,
                        '實際退回數量' => $amount, '備註' => $remark, '退料單號' => $opentime, '開單時間' => Carbon::now()
                    ]);
            } // for
            DB::commit();
            return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    }

    //提交領料單
    public function picklistsubmit(Request $request)
    {
        $Alldata = json_decode($request->input('AllData'));
        $count = count($Alldata[0]);
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $usereason = $Alldata[0][$i];
                $line = $Alldata[1][$i];
                $number = $Alldata[2][$i];
                $name = $Alldata[3][$i];
                $format = $Alldata[4][$i];
                $unit = $Alldata[5][$i];
                $advance = $Alldata[6][$i];
                $amount = $Alldata[7][$i];
                $remark = $Alldata[8][$i];
                $reason = $Alldata[9][$i];
                $list = $Alldata[10][$i];
                $opentime = $Alldata[11][$i];
                $position = $Alldata[12][$i];
                $sendpeople = $request->input('sendpeople');
                $pickpeople = $request->input('pickpeople');

                $now = Carbon::now();
                $sendname = DB::table('人員信息')->where('工號', $sendpeople)->value('姓名');
                $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');
                $stock = DB::table('inventory')->where('料號', $number)->where('儲位', $position)->value('現有庫存');
                //庫存小於實際領用數量,無法出庫
                if ($amount > $stock) {
                    DB::rollBack();
                    return \Response::json(['position' => $position, 'nowstock' => $stock, 'row' => $i], 421/* Status code here default is 200 ok*/);
                } else {
                    $test = DB::table('outbound')->where('領料單號', $list)->where('料號', $number)
                        ->where('預領數量', $advance)->whereNull('發料人員')->get();

                    if ($remark === null) $remark = '';

                    if (($test->count()) > 0) {

                        DB::table('outbound')
                            ->where('領料單號', $list)
                            ->where('料號', $number)
                            ->where('預領數量', $advance)
                            ->whereNUll('發料人員')
                            ->update([
                                '實際領用數量' => $amount, '實領差異原因' => $reason, '儲位' => $position,
                                '領料人員' => $pickname, '領料人員工號' => $pickpeople, '發料人員' => $sendname, '發料人員工號' => $sendpeople,
                                '出庫時間' => $now
                            ]);
                    } else {
                        DB::table('outbound')
                            ->insert([
                                '領用原因' => $usereason, '線別' => $line,
                                '料號' => $number, '品名' => $name, '規格' => $format, '單位' => $unit, '預領數量' => $advance,
                                '實際領用數量' => $amount, '實領差異原因' => $reason, '備註' => $remark, '領料單號' => $list,
                                '開單時間' => $opentime,  '儲位' => $position, '領料人員' => $pickname, '領料人員工號' => $pickpeople,
                                '發料人員' => $sendname, '發料人員工號' => $sendpeople, '出庫時間' => $now
                            ]);
                    }

                    DB::table('inventory')
                        ->where('料號', $number)
                        ->where('儲位', $position)
                        ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $now]);
                } // else
            } //for
            DB::commit();
            return \Response::json(['record' => $count, 'list' => $list]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 422/* Status code here default is 200 ok*/);
        } //try - catch
    }

    //提交退料單
    public function backlistsubmit(Request $request)
    {
        $Alldata = json_decode($request->input('AllData'));
        $count = count($Alldata[0]);
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $backreason = $Alldata[0][$i];
                $line = $Alldata[1][$i];
                $number = $Alldata[2][$i];
                $name = $Alldata[3][$i];
                $format = $Alldata[4][$i];
                $unit = $Alldata[5][$i];
                $advance = $Alldata[6][$i];
                $amount = $Alldata[7][$i];
                $remark = $Alldata[8][$i];
                $reason = $Alldata[9][$i];
                $list = $Alldata[10][$i];
                $opentime = $Alldata[11][$i];
                $position = $Alldata[12][$i];
                $status = $Alldata[13][$i];
                $backpeople = $request->input('backpeople');
                $pickpeople = $request->input('pickpeople');
                $now = Carbon::now();
                $backname = DB::table('人員信息')->where('工號', $backpeople)->value('姓名');
                $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');

                if ($status === '良品') {
                    $inventoryname = 'inventory';
                } else {
                    $inventoryname = '不良品inventory';
                }

                $stock = DB::table($inventoryname)->where('料號', $number)->where('儲位', $position)->value('現有庫存');

                $test = DB::table('出庫退料')->where('退料單號', $list)->where('料號', $number)
                    ->where('預退數量', $advance)->whereNull('收料人員')->get();

                if ($remark === null) $remark = '';

                if (($test->count()) > 0) {

                    DB::table('出庫退料')
                        ->where('退料單號', $list)
                        ->where('料號', $number)
                        ->where('預退數量', $advance)
                        ->whereNUll('收料人員')
                        ->update([
                            '實際退回數量' => $amount, '實退差異原因' => $reason, '儲位' => $position,
                            '收料人員' => $pickname, '收料人員工號' => $pickpeople, '退料人員' => $backname, '退料人員工號' => $backpeople,
                            '入庫時間' => $now, '功能狀況' => $status
                        ]);
                } else {
                    DB::table('出庫退料')
                        ->insert([
                            '退回原因' => $backreason, '線別' => $line,
                            '料號' => $number, '品名' => $name, '規格' => $format, '單位' => $unit, '預退數量' => $advance,
                            '實際退回數量' => $amount, '實退差異原因' => $reason, '備註' => $remark, '退料單號' => $list,
                            '開單時間' => $opentime,  '儲位' => $position, '收料人員' => $pickname, '收料人員工號' => $pickpeople,
                            '退料人員' => $backname, '退料人員工號' => $backpeople, '入庫時間' => $now, '功能狀況' => $status,
                        ]);
                }

                if ($stock === null) {
                    DB::table($inventoryname)
                        ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $position, '最後更新時間' => $now]);
                } else {
                    DB::table($inventoryname)
                        ->where('料號', $number)
                        ->where('儲位', $position)
                        ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $now]);
                }
            } //for
            DB::commit();
            return \Response::json(['record' => $count, 'list' => $list]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch
    }

    //download
    public function download(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $worksheet = $spreadsheet->getActiveSheet();
        $titlecount = $request->input('titlecount');
        $titlename = $request->input('titlename');
        if ($titlename === "領料記錄表") {
            $Alldata = DB::table('consumptive_material')
                ->join('outbound', function ($join) {
                    $join->on('outbound.料號', '=', 'consumptive_material.料號')
                        ->whereNotNull('領料人員');
                })->get();
        } else {
            $Alldata = DB::table('consumptive_material')
                ->join('出庫退料', function ($join) {
                    $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                        ->whereNotNull('收料人員');
                })->get();
        }
        $count = count($Alldata);


        //填寫表頭
        for ($i = 0; $i < $titlecount; $i++) {
            $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title')[$i]);
        }

        //填寫內容
        for ($i = 0; $i < $titlecount; $i++) {
            $string = $request->input('titlecol')[$i];
            for ($j = 0; $j < $count; $j++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$j]->$string);
            }
        }


        // 下載
        $now = Carbon::now()->format('YmdHis');
        $filename = rawurlencode($titlename) . $now . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
