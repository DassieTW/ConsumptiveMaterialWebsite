<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\在途量;

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

    $transitisn = json_decode($request->input('transitisn'));
    $transitsend = json_decode($request->input('transitsend'));

    //dd($transitsend);
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $test = DB::table('在途量')->select('料號', DB::raw('SUM(請購數量) as 請購數量'))
        ->groupBy('料號');

    $datas = DB::table('consumptive_material')
        ->joinSub($test, '在途量', function ($join) {
            $join->on('在途量.料號', '=', 'consumptive_material.料號');
        })->where('請購數量', '>', 0)->get();

    //dd($datas);
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/sxb', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $sxbisn = json_decode($request->input('sxbisn'));
    $sxbsend = json_decode($request->input('sxbsend'));
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
        ->where('consumptive_material.發料部門', 'like', $sxbsend . '%')
        ->get();


    if ($sxbcheck) {
        $datas = $datas->whereBetween('請購時間', [$sxbbegin, $end])->values();
    }

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/notmonth', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    //dd($transitsend);
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    $datas = DB::table('consumptive_material')
        ->join('非月請購', function ($join) {
            $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                ->whereNull('SXB單號');
        })->get();


    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
