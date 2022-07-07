<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// get the info
Route::post('/transit', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $transitclient = json_decode($request->input('transitclient'));
    $transitisn = json_decode($request->input('transitisn'));
    $transitsend = json_decode($request->input('transitsend'));

    //dd($transitsend);
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    $datas = DB::table('consumptive_material')
        ->join('在途量', function ($join) {
            $join->on('在途量.料號', '=', 'consumptive_material.料號')
                ->where('在途量.請購數量', '>', 0);
        })->where('consumptive_material.料號', 'like', $transitisn . '%')
        ->where('在途量.客戶', 'like', $transitclient . '%')
        ->where('consumptive_material.發料部門', 'like', $transitsend . '%')->get();
    //dd($datas);
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/sxb', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $sxbclient = json_decode($request->input('sxbclient'));
    $sxbisn = json_decode($request->input('sxbisn'));
    $sxbsend = json_decode($request->input('sxbsend'));
    $sxbsxb = json_decode($request->input('sxbsxb'));
    $sxbcheck = json_decode($request->input('sxbcheck'));
    $sxbbegin = date(json_decode($request->input('sxbbegin')));
    $sxbend = strtotime(json_decode($request->input('sxbend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $sxbend));

    //dd($transitsend);
    // dd($send);
    $datas = [];
    $datas1 = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    $datas = DB::table('consumptive_material')
        ->join('請購單', function ($join) {
            $join->on('請購單.料號', '=', 'consumptive_material.料號')
                ->whereNotNull('SXB單號');
        })->where('consumptive_material.料號', 'like', $sxbisn . '%')
        ->where('請購單.客戶', 'like', $sxbclient . '%')
        ->where('consumptive_material.發料部門', 'like', $sxbsend . '%')
        ->where('請購單.SXB單號', 'like', $sxbsxb . '%')->get();

    $datas1 = DB::table('consumptive_material')
        ->join('非月請購', function ($join) {
            $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                ->whereNotNull('SXB單號');
        })->where('consumptive_material.料號', 'like', $sxbisn . '%')
        ->where('非月請購.客戶別', 'like', $sxbclient . '%')
        ->where('consumptive_material.發料部門', 'like', $sxbsend . '%')
        ->where('非月請購.SXB單號', 'like', $sxbsxb . '%')->get();

    if ($sxbcheck) {
        $datas = $datas->whereBetween('請購時間', [$sxbbegin, $end])->values();
        $datas1 = $datas->whereBetween('上傳時間', [$sxbbegin, $end])->values();
    }

    return \Response::json(['datas' => $datas, 'datas1' => $datas1, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/notmonth', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $notmonthclient = json_decode($request->input('notmonthclient'));
    $notmonthisn = json_decode($request->input('notmonthisn'));
    //dd($transitsend);
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    $datas = DB::table('consumptive_material')
        ->join('非月請購', function ($join) {
            $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                ->whereNotNull('SXB單號');
        })->where('consumptive_material.料號', 'like', $notmonthisn . '%')
        ->where('非月請購.客戶別', 'like', $notmonthclient . '%')->get();


    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
