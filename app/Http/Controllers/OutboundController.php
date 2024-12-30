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
                    'number' => $number,
                    'line' => $line,
                    'usereason' => $usereason,
                    'name' => $name,
                    'format' => $format,
                    'unit' => $unit,
                    'send' => $send,
                    'showstock' => $showstock,
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
                'number' => $number,
                'line' => $line,
                'backreason' => $backreason,
                'name' => $name,
                'format' => $format,
                'unit' => $unit,
                'send' => $send,
            ]/* Status code here default is 200 ok*/);
        } // if
        //料號不存在
        else {
            return \Response::json(['message' => 'no isn'], 420/* Status code here default is 200 ok*/);
        } // else
    } // backadd

    //提交領料添加
    public function pickaddsubmit(Request $request)
    {
        $count = $request->input('count');
        $j = '0001';
        $max = DB::table('outbound')->max('開單時間');
        $maxtime = date_create(date('Y-m-d', strtotime($max)));
        $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
        $interval = date_diff($maxtime, $nowtime);
        $user = $request->user();
        $now = Carbon::now();
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
        } // else

        $Alldata = json_decode($request->input('AllData'));

        try {
            $res_arr_values = array();
            for ($i = 0; $i < $count; $i++) {
                $remark = $Alldata[7][$i];
                if ($remark === null) $remark = '';
                $temp = array(
                    "領用原因" => $Alldata[1][$i],
                    "線別" => $Alldata[0][$i],
                    "料號" => $Alldata[2][$i],
                    "品名" => $Alldata[3][$i],
                    "規格" => $Alldata[4][$i],
                    "單位" => $Alldata[5][$i],
                    "預領數量" => $Alldata[6][$i],
                    "實際領用數量" => $Alldata[6][$i],
                    "備註" => $remark,
                    "領料單號" => $opentime,
                    "開單時間" => $now,
                    "開單人員" => $user['username'],
                );
                $res_arr_values[] = $temp;
            } //for

            DB::beginTransaction();
            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 100, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                DB::table('outbound')
                    ->insert($whole_load[$i]);
            } // for
            DB::commit();
            return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } // catch
    } // pickaddsubmit

    //提交退料添加
    public function backaddsubmit(Request $request)
    {
        $count = $request->input('count');
        $j = '0001';
        $max = DB::table('出庫退料')->max('開單時間');
        $maxtime = date_create(date('Y-m-d', strtotime($max)));
        $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
        $user = $request->user();
        $now = Carbon::now();
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
        } // else
        $Alldata = json_decode($request->input('AllData'));

        try {
            $res_arr_values = array();
            for ($i = 0; $i < $count; $i++) {
                $remark = $Alldata[7][$i];
                if ($remark === null) $remark = '';
                $temp = array(
                    "退回原因" => $Alldata[1][$i],
                    "線別" => $Alldata[0][$i],
                    "料號" => $Alldata[2][$i],
                    "品名" => $Alldata[3][$i],
                    "規格" => $Alldata[4][$i],
                    "單位" => $Alldata[5][$i],
                    "預退數量" => $Alldata[6][$i],
                    "實際退回數量" => $Alldata[6][$i],
                    "備註" => $remark,
                    "退料單號" => $opentime,
                    "開單時間" => $now,
                    "開單人員" => $user['username'],
                );
                $res_arr_values[] = $temp;
            } // for

            DB::beginTransaction();
            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 100, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                DB::table('出庫退料')
                    ->insert($whole_load[$i]);
            } // for
            DB::commit();
            return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    } // backaddsubmit

    //提交領料單
    public function picklistsubmit(Request $request)
    {
        $Alldata = json_decode($request->input('AllData'));
        $now = Carbon::now();
        $applicant = DB::table('outbound')->where('領料單號', $Alldata[10][0])->value('開單人員');
        $record = 0;
        // sum the 實際領用數量 with the same 料號, 儲位, 線別, 領用原因 from Alldata
        $groupedData = [];
        foreach ($Alldata[2] as $index => $value) {
            $key = $Alldata[2][$index] . '-' . $Alldata[12][$index] . '-' . $Alldata[1][$index] . '-' . $Alldata[0][$index];
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    "領用原因" => $Alldata[0][$index],
                    "線別" => $Alldata[1][$index],
                    "料號" => $Alldata[2][$index],
                    "品名" => $Alldata[3][$index],
                    "規格" => $Alldata[4][$index],
                    "單位" => $Alldata[5][$index],
                    "預領數量" => $Alldata[6][$index],
                    "實際領用數量" => 0,
                    "備註" => $Alldata[8][$index],
                    "實領差異原因" => $Alldata[9][$index],
                    "領料單號" => $Alldata[10][$index],
                    "開單時間" => $Alldata[11][$index],
                    "儲位" => $Alldata[12][$index],
                    "領料人員" => $request->input('pickpeople'),
                    "發料人員" => $request->input('sendpeople'),
                    "開單人員" => $applicant,
                    "出庫時間" => $now,
                ];
            } // if
            $groupedData[$key]['實際領用數量'] += intval($Alldata[7][$index]);
        } // foreach

        $groupedData = array_values($groupedData);

        try {
            $res_arr_values = array();
            $res_arr_values2 = array();
            for ($i = 0; $i < count($groupedData); $i++) {
                $amount = $groupedData[$i]['實際領用數量'];
                $temp = $groupedData[$i];
                $stock = DB::table('inventory')->where('料號', $groupedData[$i]['料號'])->where('儲位', $groupedData[$i]['儲位'])->value('現有庫存');

                $temp2 = array(
                    "料號" => $groupedData[$i]['料號'],
                    "儲位" => $groupedData[$i]['儲位'],
                    "現有庫存" => intval($stock) - $amount,
                    "最後更新時間" => $now,
                );

                // if the $res_arr_values2 is not empty and has the same value as the current 料號, 儲位, then deduct the amount to the $res_arr_values2
                $pn_exists_inventory = false;
                if (count($res_arr_values2) > 0) {
                    foreach ($res_arr_values2 as $key => $value) {
                        if ($value['料號'] === $groupedData[$i]['料號'] && $value['儲位'] === $groupedData[$i]['儲位']) {
                            $temp2 = $value;
                            $temp2['現有庫存'] = intval($temp2['現有庫存']) - $amount;
                            $res_arr_values2[$key] = $temp2;
                            $pn_exists_inventory = true;
                            break;
                        } // if
                    } // foreach
                } // if

                $res_arr_values[] = $temp;
                if (!$pn_exists_inventory) $res_arr_values2[] = $temp2;
            } //for

            //庫存小於實際領用數量,無法出庫
            foreach ($res_arr_values2 as $key => $value) {
                if ($value['現有庫存'] < 0) {
                    return \Response::json(['position' => $value['儲位'], 'nowstock' => $stock], 421/* Status code here default is 200 ok*/);
                } // if
            } // foreach

            DB::beginTransaction();
            // delete the record first
            DB::table('outbound')->where('領料單號', $Alldata[10][0])->delete();
            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 100, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('outbound')->upsert(
                    $whole_load[$i],
                    ['領料單號', '領用原因', '線別', '料號', '預領數量', '開單時間', '儲位'],
                    ['品名', '規格', '單位', '實際領用數量', '實領差異原因', '備註', '領料人員', '發料人員', '出庫時間', '開單人員']
                );

                $record = $record + $temp_record;
            } // for

            $whole_load2 = array_chunk($res_arr_values2, 100, true);
            for ($i = 0; $i < count($whole_load2); $i++) {
                $temp_record = \DB::table('inventory')->upsert(
                    $whole_load2[$i],
                    ['料號', '儲位'],
                    ['現有庫存', '最後更新時間']
                );
            } // for
            DB::commit();
            return \Response::json(['record' => $record, 'list' => $Alldata[10][$i]]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 422/* Status code here default is 200 ok*/);
        } //try - catch
    } // picklistsubmit

    //提交退料單
    public function backlistsubmit(Request $request)
    {
        $Alldata = json_decode($request->input('AllData'));
        $applicant = DB::table('出庫退料')->where('退料單號', $Alldata[10][0])->value('開單人員');
        $now = Carbon::now();
        // sum the 實際退回數量 with the same 料號, 儲位, 線別, 退回原因, 功能狀況 from Alldata
        $groupedData = [];
        foreach ($Alldata[2] as $index => $value) {
            $key = $Alldata[2][$index] . '-' . $Alldata[12][$index] . '-' . $Alldata[1][$index] . '-' . $Alldata[0][$index] . '-' . $Alldata[13][$index];
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    "退回原因" => $Alldata[0][$index],
                    "線別" => $Alldata[1][$index],
                    "料號" => $Alldata[2][$index],
                    "品名" => $Alldata[3][$index],
                    "規格" => $Alldata[4][$index],
                    "單位" => $Alldata[5][$index],
                    "預退數量" => $Alldata[6][$index],
                    "實際退回數量" => 0,
                    "備註" => $Alldata[8][$index],
                    "實退差異原因" => $Alldata[9][$index],
                    "退料單號" => $Alldata[10][$index],
                    "開單時間" => $Alldata[11][$index],
                    "儲位" => $Alldata[12][$index],
                    "收料人員" => $request->input('pickpeople'),
                    "退料人員" => $request->input('backpeople'),
                    "開單人員" => $applicant,
                    "入庫時間" => $now,
                    "功能狀況" => $Alldata[13][$index],
                ];
            } // if
            $groupedData[$key]['實際退回數量'] += intval($Alldata[7][$index]);
        } // foreach

        $groupedData = array_values($groupedData);

        $record = 0;
        try {
            $res_arr_values = array(); // 退料
            $res_arr_values2 = array(); // 良品
            $res_arr_values3 = array(); // 不良品
            for ($i = 0; $i < count($groupedData); $i++) {
                $temp = $groupedData[$i];
                $res_arr_values[] = $temp;

                if ($groupedData[$i]['功能狀況'] === '良品') {
                    $stock = DB::table('inventory')->where('料號', $groupedData[$i]['料號'])->where('儲位', $groupedData[$i]['儲位'])->value('現有庫存');
                    // 良品Array
                    $temp2 = array(
                        "料號" => $groupedData[$i]['料號'],
                        "儲位" => $groupedData[$i]['儲位'],
                        "現有庫存" => intval($stock) + intval($groupedData[$i]['實際退回數量']),
                        "最後更新時間" => $now,
                    );

                    // if the $res_arr_values2 is not empty and has the same value as the current 料號, 儲位, then deduct the amount to the $res_arr_values2
                    $pn_exists_inventory = false;
                    if (count($res_arr_values2) > 0) {
                        foreach ($res_arr_values2 as $key => $value) {
                            if ($value['料號'] === $groupedData[$i]['料號'] && $value['儲位'] === $groupedData[$i]['儲位']) {
                                $temp2 = $value;
                                $temp2['現有庫存'] = intval($temp2['現有庫存']) + intval($groupedData[$i]['實際退回數量']);
                                $res_arr_values2[$key] = $temp2;
                                $pn_exists_inventory = true;
                                break;
                            } // if
                        } // foreach
                    } // if

                    if (!$pn_exists_inventory) $res_arr_values2[] = $temp2;
                } else {
                    $stock = DB::table('不良品inventory')->where('料號', $groupedData[$i]['料號'])->where('儲位', $groupedData[$i]['儲位'])->value('現有庫存');
                    // 不良品Array
                    $temp3 = array(
                        "料號" => $groupedData[$i]['料號'],
                        "儲位" => $groupedData[$i]['儲位'],
                        "現有庫存" => intval($stock) + intval($groupedData[$i]['實際退回數量']),
                        "最後更新時間" => $now,
                    );

                    // if the $res_arr_values3 is not empty and has the same value as the current 料號, 儲位, then deduct the amount to the $res_arr_values3
                    $pn_exists_bad_inventory = false;
                    if (count($res_arr_values3) > 0) {
                        foreach ($res_arr_values3 as $key => $value) {
                            if ($value['料號'] === $groupedData[$i]['料號'] && $value['儲位'] === $groupedData[$i]['儲位']) {
                                $temp3 = $value;
                                $temp3['現有庫存'] = intval($temp3['現有庫存']) + intval($groupedData[$i]['實際退回數量']);
                                $res_arr_values3[$key] = $temp3;
                                $pn_exists_bad_inventory = true;
                                break;
                            } // if
                        } // foreach
                    } // if

                    if (!$pn_exists_bad_inventory) $res_arr_values3[] = $temp3;
                } // else
            } // for

            DB::beginTransaction();
            // delete the record first
            DB::table('出庫退料')->where('退料單號', $Alldata[10][0])->delete();
            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 100, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('出庫退料')->upsert(
                    $whole_load[$i],
                    ['退料單號', '退回原因', '線別', '料號', '儲位', '功能狀況'],
                    ['品名', '規格', '單位', '實際退回數量', '實退差異原因', '備註', '開單時間', '收料人員', '退料人員', '入庫時間']
                );
                $record = $record + $temp_record;
            } // for

            if (count($res_arr_values2) > 0) {
                $whole_load2 = array_chunk($res_arr_values2, 100, true);
                for ($i = 0; $i < count($whole_load2); $i++) {
                    $temp_record = \DB::table('inventory')->upsert(
                        $whole_load2[$i],
                        ['料號', '儲位'],
                        ['現有庫存', '最後更新時間']
                    );
                } // for
            } // if

            if (count($res_arr_values3) > 0) {
                $whole_load3 = array_chunk($res_arr_values3, 100, true);
                for ($i = 0; $i < count($whole_load3); $i++) {
                    $temp_record = \DB::table('不良品inventory')->upsert(
                        $whole_load3[$i],
                        ['料號', '儲位'],
                        ['現有庫存', '最後更新時間']
                    );
                } // for
            } // if
            DB::commit();
            return \Response::json(['record' => $record, 'list' => $Alldata[10][$i]]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch
    }
}
