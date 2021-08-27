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
                        ['checking_inventory.料號', '=', $onlyCode],
                    ])->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->get();

            } else {     // if it is a loc
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.單號', '=', $serialNum],
                        ['checking_inventory.儲位', '=', $onlyCode],
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

    public function updateInventCheckRecord(Request $request)
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
                        ['checking_inventory.料號', '=', $onlyCode],
                    ])->join('consumptive_material', function ($join) {
                        $join->on('checking_inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->get();

            } else {     // if it is a loc
                $results = DB::table('checking_inventory')
                    ->where([
                        ['checking_inventory.單號', '=', $serialNum],
                        ['checking_inventory.儲位', '=', $onlyCode],
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

        return false;
    } // updateInventCheckRecord
} // InventoryCheckService