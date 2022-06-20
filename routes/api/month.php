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


    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    if ($transitisn !== null) {
        $datas = DB::table('consumptive_material')
            ->join('在途量', function ($join) {
                $join->on('在途量.料號', '=', 'consumptive_material.料號')
                    ->where('在途量.請購數量', '>', 0);
            })->where('consumptive_material.料號', 'like', $transitisn . '%')->get();
    } else {
        $datas = DB::table('consumptive_material')
            ->join('在途量', function ($join) {
                $join->on('在途量.料號', '=', 'consumptive_material.料號')
                    ->where('在途量.請購數量', '>', 0);
            })->get();
    }

    //select all
    if ($transitclient !== null && $transitsend !== null) {
        $datas = $datas->where('客戶別', $transitclient)->where('發料部門', $transitsend)->values();
    }
    // select client
    else if ($transitclient !== null && $transitsend !== null) {
        $datas = $datas->where('客戶別', $transitclient)->values();
    }
    //select send 
    else if ($transitclient !== null && $transitsend === null) {
        $datas = $datas->where('發料部門', $transitsend)->values();
    } else {
        $datas = $datas->values();
    }


    // $senders = DB::table("發料部門")->pluck("發料部門");
    //dd($datas);
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
    // dd(json_decode($request->input('LookInTargets'))); // test
    if ($backrecordisn !== null) {
        $datas = DB::table('consumptive_material')
            ->join('出庫退料', function ($join) {
                $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                    ->whereNotNull('收料人員');
            })->where('consumptive_material.料號', 'like', $backrecordisn . '%')->get();
    } else {
        $datas = DB::table('consumptive_material')
            ->join('出庫退料', function ($join) {
                $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                    ->whereNotNull('收料人員');
            })->get();
    }
    if ($backrecordcheck) {
        //select all
        if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend !== null) {
            $datas = $datas->where('客戶別', $backrecordclient)->whereBetween('入庫時間', [$backrecordbegin, $end])->where('製程', $backrecordproduction)->where('發料部門', $backrecordsend)->values();
        }
        // select client and production 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->where('客戶別', $backrecordclient)->whereBetween('入庫時間', [$backrecordbegin, $end])->where('製程', $backrecordproduction)->values();
        }
        //select client and send 
        else if ($backrecordclient !== null && $backrecordproduction === null && $backrecordsend !== null) {
            $datas = $datas->where('客戶別', $backrecordclient)->whereBetween('入庫時間', [$backrecordbegin, $end])->where('發料部門', $backrecordsend)->values();
        }
        // select production and send 
        else if ($backrecordclient === null && $backrecordproduction !== null && $backrecordsend !== null) {
            $datas = $datas->whereBetween('入庫時間', [$backrecordbegin, $end])->where('製程', $backrecordproduction)->where('發料部門', $backrecordsend)->values();
        }
        // select send 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->whereBetween('入庫時間', [$backrecordbegin, $end])->where('發料部門', $backrecordsend)->values();
        }
        //select production 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->whereBetween('入庫時間', [$backrecordbegin, $end])->where('製程', $backrecordproduction)->values();
        }
        //select client 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->where('客戶別', $backrecordclient)->whereBetween('入庫時間', [$backrecordbegin, $end])->values();
        } else {
            $datas = $datas->whereBetween('入庫時間', [$backrecordbegin, $end])->values();
        }
    } else {
        //select all
        if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend !== null) {
            $datas = $datas->where('客戶別', $backrecordclient)->where('製程', $backrecordproduction)->where('發料部門', $backrecordsend)->values();
        }
        // select client and production 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->where('客戶別', $backrecordclient)->where('製程', $backrecordproduction)->values();
        }
        //select client and send 
        else if ($backrecordclient !== null && $backrecordproduction === null && $backrecordsend !== null) {
            $datas = $datas->where('客戶別', $backrecordclient)->where('發料部門', $backrecordsend)->values();
        }
        // select production and send 
        else if ($backrecordclient === null && $backrecordproduction !== null && $backrecordsend !== null) {
            $datas = $datas->where('製程', $backrecordproduction)->where('發料部門', $backrecordsend)->values();
        }
        // select send 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->where('發料部門', $backrecordsend)->values();
        }
        //select production 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->where('製程', $backrecordproduction)->values();
        }
        //select client 
        else if ($backrecordclient !== null && $backrecordproduction !== null && $backrecordsend === null) {
            $datas = $datas->where('客戶別', $backrecordclient)->values();
        } else {
            $datas = $datas->values();
        }
    } // if else

    // $senders = DB::table("發料部門")->pluck("發料部門");
    //dd($datas);
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
