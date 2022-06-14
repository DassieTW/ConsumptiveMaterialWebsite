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

    $inboundclient = json_decode($request->input('inboundclient'));
    $inboundisn = json_decode($request->input('inboundisn'));
    $inboundlist = json_decode($request->input('inboundlist'));
    $inboundcheck = json_decode($request->input('inboundcheck'));
    $inboundbegin = date(json_decode($request->input('inboundbegin')));
    $inboundend = strtotime(json_decode($request->input('inboundend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $inboundend));

    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    if ($inboundisn !== null) {
        $datas = DB::table('inbound')
            ->where('料號', 'like', $inboundisn . '%')
            ->get();
    } else {
        $datas = DB::table('inbound')
            ->get();
    }
    if ($inboundcheck) {
        if ($inboundclient !== null && $inboundlist !== null) {
            $datas = $datas->where('客戶別', $inboundclient)->whereBetween('入庫時間', [$inboundbegin, $end])->where('入庫單號', $inboundlist);
        } else if ($inboundclient !== null && $inboundlist === null) {
            $datas = $datas->where('客戶別', $inboundclient)->whereBetween('入庫時間', [$inboundbegin, $end]);
        } else if ($inboundclient === null && $inboundlist !== null) {
            $datas = $datas->whereBetween('入庫時間', [$inboundbegin, $end])->where('入庫單號', $inboundlist);
        } else {
            $datas = $datas->whereBetween('入庫時間', [$inboundbegin, $end]);
        }
    } else {
        if ($inboundclient !== null && $inboundlist !== null) {
            $datas = $datas->where('客戶別', $inboundclient)->where('入庫單號', $inboundlist);
        } else if ($inboundclient !== null && $inboundlist === null) {
            $datas = $datas->where('客戶別', $inboundclient);
        } else if ($inboundclient === null && $inboundlist !== null) {
            $datas = $datas->where('入庫單號', $inboundlist);
        } else {
            $datas = $datas;
        }
    } // if else

    // $senders = DB::table("發料部門")->pluck("發料部門");

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});
