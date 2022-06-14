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
    if ($pickrecordisn !== null) {
        $datas = DB::table('consumptive_material')
            ->join('outbound', function ($join) {
                $join->on('outbound.料號', '=', 'consumptive_material.料號')
                    ->whereNotNull('領料人員');
            })->where('consumptive_material.料號', 'like', $pickrecordisn . '%')->get();
    } else {
        $datas = DB::table('consumptive_material')
            ->join('outbound', function ($join) {
                $join->on('outbound.料號', '=', 'consumptive_material.料號')
                    ->whereNotNull('領料人員');
            })->get();
    }
    if ($pickrecordcheck) {
        //select all
        if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend !== null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->whereBetween('出庫時間', [$pickrecordbegin, $end])->where('製程', $pickrecordproduction)->where('發料部門', $pickrecordsend)->values();
        }
        // select client and production 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->whereBetween('出庫時間', [$pickrecordbegin, $end])->where('製程', $pickrecordproduction)->values();
        }
        //select client and send 
        else if ($pickrecordclient !== null && $pickrecordproduction === null && $pickrecordsend !== null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->whereBetween('出庫時間', [$pickrecordbegin, $end])->where('發料部門', $pickrecordsend)->values();
        }
        // select production and send 
        else if ($pickrecordclient === null && $pickrecordproduction !== null && $pickrecordsend !== null) {
            $datas = $datas->whereBetween('出庫時間', [$pickrecordbegin, $end])->where('製程', $pickrecordproduction)->where('發料部門', $pickrecordsend)->values();
        }
        // select send 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->whereBetween('出庫時間', [$pickrecordbegin, $end])->where('發料部門', $pickrecordsend)->values();
        }
        //select production 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->whereBetween('出庫時間', [$pickrecordbegin, $end])->where('製程', $pickrecordproduction)->values();
        }
        //select client 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->whereBetween('出庫時間', [$pickrecordbegin, $end])->values();
        } else {
            $datas = $datas->whereBetween('出庫時間', [$pickrecordbegin, $end])->values();
        }
    } else {
        //select all
        if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend !== null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->where('製程', $pickrecordproduction)->where('發料部門', $pickrecordsend)->values();
        }
        // select client and production 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->where('製程', $pickrecordproduction)->values();
        }
        //select client and send 
        else if ($pickrecordclient !== null && $pickrecordproduction === null && $pickrecordsend !== null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->where('發料部門', $pickrecordsend)->values();
        }
        // select production and send 
        else if ($pickrecordclient === null && $pickrecordproduction !== null && $pickrecordsend !== null) {
            $datas = $datas->where('製程', $pickrecordproduction)->where('發料部門', $pickrecordsend)->values();
        }
        // select send 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->where('發料部門', $pickrecordsend)->values();
        }
        //select production 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->where('製程', $pickrecordproduction)->values();
        }
        //select client 
        else if ($pickrecordclient !== null && $pickrecordproduction !== null && $pickrecordsend === null) {
            $datas = $datas->where('客戶別', $pickrecordclient)->values();
        } else {
            $datas = $datas->values();
        }
    } // if else

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
