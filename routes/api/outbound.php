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
Route::post('/pickrecord', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $pickrecordline = json_decode($request->input('pickrecordline'));
    $pickrecordisn = json_decode($request->input('pickrecordisn'));
    $pickrecordbegin = date(json_decode($request->input('pickrecordbegin')));
    $pickrecordend = strtotime(json_decode($request->input('pickrecordend')));

    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $pickrecordend));

    // dd($pickrecordbegin); // test
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $datas = DB::table('consumptive_material')
        ->join('outbound', function ($join) {
            $join->on('outbound.料號', '=', 'consumptive_material.料號')
                ->whereNotNull('領料人員');
        })->where('consumptive_material.料號', 'like', $pickrecordisn . '%')
        ->where('outbound.線別', 'like', $pickrecordline . '%')
        ->whereBetween('出庫時間', [$pickrecordbegin, $end])
        ->get();

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/backrecord', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $backrecordline = json_decode($request->input('backrecordline'));
    $backrecordisn = json_decode($request->input('backrecordisn'));
    $backrecordbegin = date(json_decode($request->input('backrecordbegin')));
    $backrecordend = strtotime(json_decode($request->input('backrecordend')));

    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $backrecordend));

    // dd($send);
    $datas = [];

    $datas = DB::table('consumptive_material')
        ->join('出庫退料', function ($join) {
            $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                ->whereNotNull('收料人員');
        })->where('consumptive_material.料號', 'like', $backrecordisn . '%')
        ->where('出庫退料.線別', 'like', $backrecordline . '%')
        ->whereBetween('入庫時間', [$backrecordbegin, $end])
        ->get();

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/pickreason', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test
    // dd($send);
    $datas = [];

    $datas = DB::table('領用原因')
        ->get();

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/lines', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test
    // dd($send);
    $datas = [];

    $datas = DB::table('線別')
        ->get();

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
