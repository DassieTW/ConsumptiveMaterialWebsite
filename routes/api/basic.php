<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicInformationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|                 !!!!! NOTICE !!!!!
|         !!! API CANNOT ACCESS SESSION !!!
|
|
*/

// get the material info
Route::post('/mats', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName();
    //dd($request->all());
    $datas = DB::table('consumptive_material')->get();
    $senders = DB::table("發料部門")->pluck("發料部門");

    return \Response::json(['datas' => $datas, 'senders' => $senders, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
