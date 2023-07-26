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

// 入庫查詢
Route::post('/inboundsearch', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $oboundclient = json_decode($request->input('oboundclient'));
    $oboundisn = json_decode($request->input('oboundisn'));
    $oboundbound = json_decode($request->input('oboundbound'));
    $oboundcheck = json_decode($request->input('oboundcheck'));
    $oboundbegin = date(json_decode($request->input('oboundbegin')));
    $oboundend = strtotime(json_decode($request->input('oboundend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $oboundend));

    //dd($inboundclient);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $datas = DB::table('O庫inbound')
        ->where('料號', 'like', $oboundisn . '%')
        ->where('客戶別', 'like', $oboundclient . '%')
        ->where('庫別', 'like', $oboundbound . '%')
        ->get();
    //$datas = $datas->where('客戶別', "Fendi");
    //dd($datas);
    if ($oboundcheck) {
        $datas = $datas->whereBetween('時間', [$oboundbegin, $end])->values();
    }
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

// 料件查詢
Route::post('/isnsearch', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $oboundisn = json_decode($request->input('oboundisn'));

    $datas = [];
    $datas = DB::table('O庫_material')
        ->where('料號', 'like', $oboundisn . '%')
        ->get();
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

// 庫存查詢
Route::post('/searchstock', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $oboundisn = json_decode($request->input('oboundstockisn'));
    $oboundnogood = json_decode($request->input('oboundnogood'));

    $datas = [];
    if ($oboundnogood) {
        $datas = DB::table('O庫不良品inventory')
            ->where('料號', 'like', $oboundisn . '%')
            ->get();
    } else {
        $datas = DB::table('O庫inventory')
            ->where('料號', 'like', $oboundisn . '%')
            ->get();
    }
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

// 領料記錄表查詢
Route::post('/picklistsearch', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $oboundclient = json_decode($request->input('oboundpickrecordclient'));
    $oboundisn = json_decode($request->input('oboundpickrecordisn'));
    $oboundprocess = json_decode($request->input('oboundpickrecordproduction'));
    $oboundcheck = json_decode($request->input('oboundpickrecordcheck'));
    $oboundbegin = date(json_decode($request->input('oboundpickrecordbegin')));
    $oboundend = strtotime(json_decode($request->input('oboundpickrecordend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $oboundend));

    //dd($inboundclient);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $datas = DB::table('O庫outbound')
        ->where('料號', 'like', $oboundisn . '%')
        ->where('客戶別', 'like', $oboundclient . '%')
        ->where('製程', 'like', $oboundprocess . '%')
        ->whereNotNull('發料人員')
        ->get();
    //$datas = $datas->where('客戶別', "Fendi");
    //dd($datas);
    if ($oboundcheck) {
        $datas = $datas->whereBetween('出庫時間', [$oboundbegin, $end])->values();
    }
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

// 退料記錄表查詢
Route::post('/backlistsearch', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $oboundclient = json_decode($request->input('oboundbackrecordclient'));
    $oboundisn = json_decode($request->input('oboundbackrecordisn'));
    $oboundprocess = json_decode($request->input('oboundbackrecordproduction'));
    $oboundcheck = json_decode($request->input('oboundbackrecordcheck'));
    $oboundbegin = date(json_decode($request->input('oboundbackrecordbegin')));
    $oboundend = strtotime(json_decode($request->input('oboundbackrecordend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $oboundend));

    // dd($oboundclient);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $datas = DB::table('O庫出庫退料')
        ->where('料號', 'like', $oboundisn . '%')
        ->where('客戶別', 'like', $oboundclient . '%')
        ->where('製程', 'like', $oboundprocess . '%')
        ->whereNotNull('收料人員')
        ->get();
    //$datas = $datas->where('客戶別', "Fendi");
    //dd($datas);
    if ($oboundcheck) {
        $datas = $datas->whereBetween('入庫時間', [$oboundbegin, $end])->values();
    }
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
