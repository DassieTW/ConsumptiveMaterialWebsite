<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryCheckService
{

    public function fetchInventCheckRecord(Request $request)
    {
        $results = [];
        $serialNum = filter_input(INPUT_POST, 'tablename', FILTER_SANITIZE_STRING); // 單號
        $onlyCode = filter_input(INPUT_POST, 'texBox', FILTER_SANITIZE_STRING); // the scanned in barcode
        $isIsn = filter_input(INPUT_POST, 'isIsn', FILTER_VALIDATE_BOOLEAN); // is this a ISN or Loc.

        DB::beginTransaction();

        try {
            if ($isIsn) {  // if it is an isn
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.單號', '=', $serialNum],
                        ['checking_inventory.料號', 'like', $onlyCode . '%'],
                    ])->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->get();
            } else {     // if it is a loc
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.單號', '=', $serialNum],
                        ['checking_inventory.儲位', 'like', $onlyCode . '%'],
                    ])
                    ->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->get();
            } // if else

            // dd($results); // test

            DB::commit();
            // all good
        } catch (\Exception $e) {
            // DB::rollback(); // select statements dont need to roll back
            // something went wrong
        } // try catch

        return $results;
    } // fetchInventCheckRecord

    public function fetchInventCheckRecordWithDetailedConditions(Request $request)
    {
        $results = [];
        $serialNum = filter_input(INPUT_POST, 'tablename', FILTER_SANITIZE_STRING); // 單號
        $loc = $request->input('loc');
        $isn = $request->input('isn');

        DB::beginTransaction();

        try {

            if ($loc !== "false" && $isn !== "false") {
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.單號', '=', $serialNum],
                        ['checking_inventory.儲位', '=', $loc],
                        ['checking_inventory.料號', '=', $isn],
                    ])
                    ->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->get();
            } // if
            else if ($loc !== "false") {
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.單號', '=', $serialNum],
                        ['checking_inventory.儲位', '=', $loc],
                    ])
                    ->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->get();
            } // else if
            else {
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.單號', '=', $serialNum],
                    ])
                    ->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->get();
            } // else

            // dd($results); // test

            DB::commit();
            // all good
        } catch (\Exception $e) {
            // DB::rollback(); // select statements dont need to roll back
            // something went wrong
        } // try catch

        return $results;
    } // fetchInventCheckRecordWithDetailedConditions

    public function fetchInventCheckRecordWithinTimeRange(Request $request, $fromTime, $toTime)
    {
        $results = [];
        $onlyCode = filter_input(INPUT_POST, 'texBox', FILTER_SANITIZE_STRING); // the scanned in barcode
        $isIsn = filter_input(INPUT_POST, 'isIsn', FILTER_VALIDATE_BOOLEAN); // is this a ISN or Loc.

        DB::beginTransaction();

        try {
            if ($isIsn) {  // if it is an isn
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.created_at', '>=', $fromTime],
                        ['checking_inventory.created_at', '<=', $toTime],
                        ['checking_inventory.料號', 'like', $onlyCode . '%'],
                    ])->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->leftJoin('login', 'checking_inventory.updated_by', '=', 'login.username')
                    ->get();
            } else {     // if it is a loc
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.created_at', '>=', $fromTime],
                        ['checking_inventory.created_at', '<=', $toTime],
                        ['checking_inventory.儲位', 'like', $onlyCode . '%'],
                    ])
                    ->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->leftJoin('login', 'checking_inventory.updated_by', '=', 'login.username')
                    ->get();
            } // if else

            // dd($results); // test

            DB::commit();
            // all good
        } catch (\Exception $e) {
            // DB::rollback(); // select statements dont need to roll back
            // something went wrong
        } // try catch

        return $results;
    } // fetchInventCheckRecordWithinTimeRange

    public function updateInventCheckRecord(Request $request)
    {
        $results = [];
        // isnn: $isnn, locc: $locc, checkk: $checkk, clientt: $client, pageno: $pageno, tname: $tname

        $isn = filter_input(INPUT_POST, 'isnn', FILTER_SANITIZE_STRING);
        $loc = filter_input(INPUT_POST, 'locc', FILTER_SANITIZE_STRING);
        $client = filter_input(INPUT_POST, 'clientt', FILTER_SANITIZE_STRING);
        $checkUpdate = (int) filter_input(INPUT_POST, 'checkk', FILTER_SANITIZE_STRING);
        $serialNum = filter_input(INPUT_POST, 'tname', FILTER_SANITIZE_STRING); // 單號

        DB::beginTransaction();

        try {
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());
            $affected = DB::table('checking_inventory')
                ->where('單號', '=', $serialNum)
                ->where('料號', '=', $isn)
                ->where('儲位', '=', $loc)
                ->where('客戶別', '=', $client)
                ->update(['盤點' => $checkUpdate, 'updated_at' => $datetime, 'updated_by' => \Auth::user()->username]);

            DB::commit();
            return true;
            // all good
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return false;
            // something went wrong
        } // try catch

        return false;
    } // updateInventCheckRecord

    public function createTableService(Request $request)
    {
        DB::beginTransaction();
        $results = DB::table('inventory')
            ->select('料號', '現有庫存', '儲位', '客戶別')
            ->get();

        $datetimeOfSerial = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());
        $pieces = explode(" ", $datetimeOfSerial);
        $serialNo = \Auth::user()->username . '_' .
            $pieces[0] . '_' .
            $pieces[1];

        try {
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            foreach ($results as $result) {
                DB::table('checking_inventory')
                    ->insert([
                        '單號' => $serialNo,
                        '料號' => $result->料號,
                        '現有庫存' => $result->現有庫存,
                        '儲位' => $result->儲位,
                        '客戶別' => $result->客戶別,
                        'created_at' => $datetime,
                        'updated_at' => $datetime,
                    ]);
            } // foreach
            DB::commit();
            return true;
            // all good
        } catch (\Exception $e) {
            dd($serialNo);
            DB::rollback();
            return false;
            // something went wrong
        } // try catch

        return false;
    } // createTableService

    public function fetchCreators(Request $request)
    {
        DB::beginTransaction();

        try {
            $results = DB::table('login')
                ->select('username', '姓名')
                ->get();

            // dd($results); // test

            DB::commit();
            // all good
        } catch (\Exception $e) {
            // DB::rollback(); // select statements dont need to roll back
            // something went wrong
        } // try catch

        return $results;
    } // fetchCreators
} // InventoryCheckService
