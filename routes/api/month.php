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
