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

// get the info
Route::post('/pickrecord', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $pickrecordclient = json_decode($request->input('pickrecordclient'));
    $pickrecordproduction = json_decode($request->input('pickrecordproduction'));
    $pickrecordsend = json_decode($request->input('pickrecordsend'));
    $pickrecordisn = json_decode($request->input('pickrecordisn'));
    $pickrecordcheck = json_decode($request->input('pickrecordcheck'));
    $pickrecordbegin = date(json_decode($request->input('pickrecordbegin')));
    $pickrecordend = strtotime(json_decode($request->input('pickrecordend')));

    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $pickrecordend));

    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $datas = DB::table('consumptive_material')
        ->join('outbound', function ($join) {
            $join->on('outbound.料號', '=', 'consumptive_material.料號')
                ->whereNotNull('領料人員');
        })->where('consumptive_material.料號', 'like', $pickrecordisn . '%')
        ->where('outbound.客戶別', 'like', $pickrecordclient . '%')
        ->where('outbound.製程', 'like', $pickrecordproduction . '%')
        ->where('consumptive_material.發料部門', 'like', $pickrecordsend . '%')->get();

    if ($pickrecordcheck) {
        $datas = $datas->whereBetween('出庫時間', [$pickrecordbegin, $end])->values();
    }
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/backrecord', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $backrecordclient = json_decode($request->input('backrecordclient'));
    $backrecordproduction = json_decode($request->input('backrecordproduction'));
    $backrecordsend = json_decode($request->input('backrecordsend'));
    $backrecordisn = json_decode($request->input('backrecordisn'));
    $backrecordcheck = json_decode($request->input('backrecordcheck'));
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
        ->where('出庫退料.客戶別', 'like', $backrecordclient . '%')
        ->where('出庫退料.製程', 'like', $backrecordproduction . '%')
        ->where('consumptive_material.發料部門', 'like', $backrecordsend . '%')->get();

    if ($backrecordcheck) {
        $datas = $datas->whereBetween('入庫時間', [$backrecordbegin, $end])->values();
    }

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
