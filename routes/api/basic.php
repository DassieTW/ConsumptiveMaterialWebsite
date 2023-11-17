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

// get the material info
Route::post('/mats', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $input = json_decode($request->input('LookInTargets'));
    $send = json_decode($request->input('LookInSend'));
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    if (json_decode($request->input('LookInType')) === "1") {
        if ($send !== null) {
            $datas = DB::table('consumptive_material')
                ->where('料號', 'like', $input . '%')
                ->where('發料部門', '=', $send)
                ->get();
        } else {
            $datas = DB::table('consumptive_material')
                ->where('料號', 'like', $input . '%')
                ->get();
        } // if else
    } else {
        if ($send !== null) {
            $datas = DB::table('consumptive_material')
                ->where('發料部門', '=', $send)
                ->whereIn('料號', $input)
                ->get();
        } else {
            $datas = DB::table('consumptive_material')
                ->whereIn('料號', $input)
                ->get();
        } // if else
    } // if else

    $senders = DB::table("發料部門")->pluck("發料部門");

    return \Response::json(['datas' => $datas, 'senders' => $senders, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/delete_pn', 'api\BasicInfoController@destroyPN');
