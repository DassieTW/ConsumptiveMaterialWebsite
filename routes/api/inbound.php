<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicInformationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|                                !!!!! NOTICE !!!!!
|                        !!! API CANNOT ACCESS SESSION !!!
|
|
*/

// get the info 入庫查詢
Route::post('/search', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $inboundisn = json_decode($request->input('inboundisn'));
    $inboundlist = json_decode($request->input('inboundlist'));
    $inboundcheck = json_decode($request->input('inboundcheck'));
    $inboundbegin = date(json_decode($request->input('inboundbegin')));
    $inboundend = strtotime(json_decode($request->input('inboundend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $inboundend));

    //dd($inboundclient);
    //$datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $test = DB::table('inbound')
        ->where('料號', 'like', $inboundisn . '%')
        ->where('入庫單號', 'like', $inboundlist . '%');

    $datas = DB::table('consumptive_material')
        ->rightJoinSub($test, 'inbound', function ($join) {
            $join->on('consumptive_material.料號', '=', 'inbound.料號');
        })->get();

    if ($inboundcheck) {
        $datas = $datas->whereBetween('入庫時間', [$inboundbegin, $end])->values();
    } // if
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

//入庫-查詢 刪除
Route::post('/delete', 'api\InboundController@destroy');

// 庫存查詢
Route::post('/searchstock', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $inboundclient = json_decode($request->input('inboundclient'));
    $inboundisn = json_decode($request->input('inboundisn'));
    $inboundloc = json_decode($request->input('inboundloc'));
    $inboundsend = json_decode($request->input('inboundsend'));
    $inboundmonth = json_decode($request->input('inboundmonth'));
    $inboundnogood = json_decode($request->input('inboundnogood'));

    //不良品inventory
    if ($inboundnogood) {
        $test = DB::table('不良品inventory')
            ->select('料號', '儲位', '最後更新時間', DB::raw('SUM(現有庫存) as 現有庫存'))
            ->groupBy('料號', '儲位', '最後更新時間');

        $datas = DB::table('consumptive_material')
            ->joinSub($test, '不良品inventory', function ($join) {
                $join->on('不良品inventory.料號', '=', 'consumptive_material.料號');
            })->where('現有庫存', '>', 0)
            ->where('consumptive_material.料號', 'like', $inboundisn . '%')
            ->where('不良品inventory.儲位', 'like', $inboundloc . '%')
            ->where('consumptive_material.發料部門', 'like', $inboundsend . '%')->get();

        $datas1 = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.料號90', '=', '月請購_單耗.料號90')
                    ->on('MPS.料號', '=', '月請購_單耗.料號');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.下月MPS', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * 5 / 26) as 安全庫存)'))
            ->where('月請購_單耗.狀態', '=', "已完成")
            // ->where('consumptive_material.耗材歸屬', '=', "單耗")
            ->where('consumptive_material.月請購', '=', "是");

        $datas1 = $datas1->select('月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * 5 / 26) as 安全庫存'))
            ->groupBy('月請購_單耗.料號')->get();


        //count 呆滯天數 and 安全庫存
        for ($i = 0; $i < count($datas); $i++) {
            $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
            $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int) $interval;
            $datas[$i]->呆滯天數 = $interval;
            if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
            $datas[$i]->單價 = round($datas[$i]->單價, 5);
            for ($j = 0; $j < count($datas1); $j++) {
                if ($datas[$i]->料號 === $datas1[$j]->料號) {
                    $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
                } // if
            } // for
        } // for
    } // if
    //normal inventory
    else {
        $test = DB::table('inventory')
            ->select('料號', '儲位', '最後更新時間', DB::raw('SUM(現有庫存) as 現有庫存'))
            ->groupBy('料號', '儲位', '最後更新時間');

        $datas = DB::table('consumptive_material')
            ->joinSub($test, 'inventory', function ($join) {
                $join->on('inventory.料號', '=', 'consumptive_material.料號');
            })->where('現有庫存', '>', 0)
            ->where('consumptive_material.料號', 'like', $inboundisn . '%')
            ->where('inventory.儲位', 'like', $inboundloc . '%')
            ->where('consumptive_material.發料部門', 'like', $inboundsend . '%')->get();


        $datas1 = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.料號90', '=', '月請購_單耗.料號90')
                    ->on('MPS.料號', '=', '月請購_單耗.料號');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.下月MPS', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * 5 / 26) as 安全庫存)'))
            ->where('月請購_單耗.狀態', '=', "已完成")
            ->where('consumptive_material.月請購', '=', "是");

        $datas1 = $datas1->select('月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * 5 / 26) as 安全庫存'))
            ->groupBy('月請購_單耗.料號')->get();

        //count 呆滯天數 and 安全庫存
        for ($i = 0; $i < count($datas); $i++) {
            $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
            $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int) $interval;
            $datas[$i]->呆滯天數 = $interval;
            if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
            $datas[$i]->單價 = round($datas[$i]->單價, 5);
            for ($j = 0; $j < count($datas1); $j++) {
                if ($datas[$i]->料號 === $datas1[$j]->料號) {
                    $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
                } // if
            } // for
        } // for
    } // else
    //庫存使用月數
    if ($inboundmonth) {
        $test = DB::table('inventory')
            ->select('料號', DB::raw('SUM(現有庫存) as 現有庫存'))
            ->groupBy('料號');

        $datas = DB::table('consumptive_material')
            ->joinSub($test, 'inventory', function ($join) {
                $join->on('inventory.料號', '=', 'consumptive_material.料號');
            })->where('consumptive_material.料號', 'like', $inboundisn . '%')
            ->where('consumptive_material.發料部門', 'like', $inboundsend . '%')
            ->where('現有庫存', '>', 0)->get();

        $datas1 = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.料號90', '=', '月請購_單耗.料號90')
                    ->on('MPS.料號', '=', '月請購_單耗.料號');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.本月MPS', '月請購_單耗.*', DB::raw('(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量)'))
            ->where('月請購_單耗.狀態', '=', "已完成");
        // ->where('consumptive_material.耗材歸屬', '=', "單耗");

        $datas1 = $datas1->select('月請購_單耗.料號', DB::raw('SUM(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量'))
            ->groupBy('月請購_單耗.料號')->get();


        for ($i = 0; $i < count($datas); $i++) {
            for ($j = 0; $j < count($datas1); $j++) {
                if ($datas[$i]->料號 === $datas1[$j]->料號) {
                    $datas[$i]->月使用量 = round($datas1[$j]->月使用量, 5);
                    $datas[$i]->庫存使用月數 = round($datas[$i]->現有庫存 / $datas1[$j]->月使用量, 5);
                } // if
            } // for
        } // for

        //補月使用量
        for ($i = 0; $i < count($datas); $i++) {
            $datas[$i]->單價 = round($datas[$i]->單價, 5);
            if (!(property_exists($datas[$i], "月使用量"))) {
                $datas[$i]->月使用量 = 0;
                $datas[$i]->庫存使用月數 = 0;
            } // if
        } // for
    } // if

    return \Response::json(['datas' => $datas, "dbName" => $dbName, "month" => $inboundmonth], 200/* Status code here default is 200 ok*/);
});

Route::post('/getExistingStock', 'api\InboundController@showStocks');

//入庫-儲位調撥提交
Route::post('/locTransfer', 'api\InboundController@locTransfer');

//入庫-儲位調撥紀錄
Route::post('/locTransferRecord', 'api\InboundController@showLocTransferRecord');

//入庫-SSZ領料單
Route::post('/ssz', 'api\InboundController@showSSZFlowNumber');
Route::post('/ssz_info', 'api\InboundController@showSSZInfo');

//入庫-SSZ領料單-入儲位
Route::post('/claimSSZ', 'api\InboundController@ssz_to_loc');