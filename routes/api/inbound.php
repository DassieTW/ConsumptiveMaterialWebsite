<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicInformationController;
use Maatwebsite\Excel\Concerns\ToArray;

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
Route::post('/search', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $inboundclient = json_decode($request->input('inboundclient'));
    $inboundisn = json_decode($request->input('inboundisn'));
    $inboundlist = json_decode($request->input('inboundlist'));
    $inboundcheck = json_decode($request->input('inboundcheck'));
    $inboundbegin = date(json_decode($request->input('inboundbegin')));
    $inboundend = strtotime(json_decode($request->input('inboundend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $inboundend));

    //dd($inboundclient);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $datas = DB::table('inbound')
        ->where('料號', 'like', $inboundisn . '%')
        ->where('客戶別', 'like', $inboundclient . '%')
        ->where('入庫單號', 'like', $inboundlist . '%')
        ->get();
    //$datas = $datas->where('客戶別', "Fendi");
    //dd($datas);
    if ($inboundcheck) {
        $datas = $datas->whereBetween('入庫時間', [$inboundbegin, $end])->values();
    }
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

// 入庫 庫存查詢送出
Route::post('/searchstocksubmit', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    // $dbName = DB::connection()->getDatabaseName(); // test

    $input = json_decode($request->input('LookInTargets'));
    $send = json_decode($request->input('LookInSend'));
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    if (json_decode($request->input('LookInType')) === "1") {
        if($send !== null){
            $datas = DB::table('consumptive_material')
                ->where('料號', 'like', $input . '%')
                ->where('發料部門', '=', $send)
                ->get();
        }
        else{
            $datas = DB::table('consumptive_material')
                ->where('料號', 'like', $input . '%')
                ->get();
        }
    } else {
        if($send !== null){
            $datas = DB::table('consumptive_material')
                ->where('發料部門', '=', $send)
                ->whereIn('料號', $input)
                ->get();
        }
        else
        {
            $datas = DB::table('consumptive_material')
                ->whereIn('料號', $input)
                ->get();
        }
    } // if else

    $senders = DB::table("發料部門")->pluck("發料部門");

    return \Response::json(['datas' => $datas, 'senders' => $senders], 200/* Status code here default is 200 ok*/);
});
