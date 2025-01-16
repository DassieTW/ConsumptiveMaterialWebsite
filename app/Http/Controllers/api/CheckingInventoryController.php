<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckingInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    } // index

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test
        $user = $request->input('username');
        $stock_array = json_decode($request->input('stock_array'));
        $pn_array = json_decode($request->input('pn_array'));
        $loc_array = json_decode($request->input('loc_array'));
        $serialNum = Carbon::now()->format('YmdHis');
        $created_at = Carbon::now();
        $record = 0;

        try {
            $priority = intval(\DB::table('login')->where('username', $user)->value('priority'));
            // dd($priority); // test
            $res_arr_values = array();
            $res_arr_values2 = array();
            if ($priority < 2) {
                for ($i = 0; $i < count($pn_array); $i++) {
                    $isn = $pn_array[$i];
                    $amount = $stock_array[$i];
                    $loc = $loc_array[$i];

                    $temp = array(
                        "料號" => $isn,
                        '現有庫存' => $amount,
                        '儲位' => $loc,
                        "單號" => $serialNum,
                        'updated_by' => $user,
                        'created_at' => $created_at,
                        'approved_by' => $user, // self approved since priority < 2
                        'approved_at' => $created_at, // self approved since priority < 2
                    );

                    $temp2 = array(
                        "料號" => $isn,
                        '現有庫存' => $amount,
                        '儲位' => $loc,
                        '最後更新時間' => $created_at,
                    );

                    $res_arr_values[] = $temp;
                    $res_arr_values2[] = $temp2;
                } //for
            } // if
            else {
                for ($i = 0; $i < count($pn_array); $i++) {
                    $isn = $pn_array[$i];
                    $amount = $stock_array[$i];
                    $loc = $loc_array[$i];

                    $temp = array(
                        "料號" => $isn,
                        '現有庫存' => $amount,
                        '儲位' => $loc,
                        "單號" => $serialNum,
                        'updated_by' => $user,
                        'created_at' => $created_at,
                    );

                    $res_arr_values[] = $temp;
                } //for
            } // else

            \DB::beginTransaction();
            if ($priority < 2) {
                // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
                $whole_load = array_chunk($res_arr_values, 200, true);
                for ($i = 0; $i < count($whole_load); $i++) {
                    $temp_record = \DB::table('checking_inventory')->upsert(
                        $whole_load[$i],
                        ['料號', '儲位', '單號'],
                        ['現有庫存', 'updated_by', 'created_at', 'approved_by', 'approved_at'],
                    );

                    $record = $record + $temp_record;
                } // for

                $whole_load2 = array_chunk($res_arr_values2, 200, true);
                for ($i = 0; $i < count($whole_load2); $i++) {
                    $temp_record = \DB::table('inventory')->upsert(
                        $whole_load2[$i],
                        ['料號', '儲位'],
                        ['現有庫存', '最後更新時間'],
                    );
                } // for
            } // if
            else {
                // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
                $whole_load = array_chunk($res_arr_values, 200, true);
                for ($i = 0; $i < count($whole_load); $i++) {
                    $temp_record = \DB::table('checking_inventory')->upsert(
                        $whole_load[$i],
                        ['料號', '儲位', '單號'],
                        ['現有庫存', 'updated_by', 'created_at'],
                    );

                    $record = $record + $temp_record;
                } // for
            } // else

            \DB::commit();
            return \Response::json(['newStock' => $record, 'DB' => $database]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage(), 'DB' => $database], 421/* Status code here default is 200 ok*/);
        } //try - catch
    } // store

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCheckingRecords(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test
        $timeRange = json_decode($request->input('timeRange'));

        if ($timeRange === "month") {
            $allResult = \DB::table('checking_inventory')
                ->where('created_at', '>=', Carbon::now()->subMonth(1))
                ->leftJoin('consumptive_material', 'checking_inventory.料號', '=', 'consumptive_material.料號')
                ->leftJoin('人員信息', 'checking_inventory.updated_by', '=', '人員信息.工號')
                ->orderBy('created_at', 'desc')
                ->get();
        } // if
        else if ($timeRange === "quarter") {
            $allResult = \DB::table('checking_inventory')
                ->where('created_at', '>=', Carbon::now()->subMonths(3))
                ->leftJoin('consumptive_material', 'checking_inventory.料號', '=', 'consumptive_material.料號')
                ->leftJoin('人員信息', 'checking_inventory.updated_by', '=', '人員信息.工號')
                ->orderBy('created_at', 'desc')
                ->get();
        } // if
        else { // year
            $allResult = \DB::table('checking_inventory')
                ->where('created_at', '>=', Carbon::now()->subYear(1))
                ->leftJoin('consumptive_material', 'checking_inventory.料號', '=', 'consumptive_material.料號')
                ->leftJoin('人員信息', 'checking_inventory.updated_by', '=', '人員信息.工號')
                ->orderBy('created_at', 'desc')
                ->get();
        } // else

        return \Response::json(['data' => $allResult, "dbName" => $database]/* Status code here default is 200 ok*/);
    } // getCheckingRecords

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveCheckingRecords(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test
        $user = $request->input('username');
        $serialNum = $request->input('serial_num');
        $stock_array = json_decode($request->input('stock'));
        $pn_array = json_decode($request->input('isn'));
        $loc_array = json_decode($request->input('loc'));
        $approve_at = Carbon::now();
        $record = 0;

        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($pn_array); $i++) {
                $isn = $pn_array[$i];
                $stock = $stock_array[$i];
                $loc = $loc_array[$i];
                $temp = array(
                    "料號" => $isn,
                    '現有庫存' => $stock,
                    '儲位' => $loc,
                    '最後更新時間' => $approve_at,
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();

            \DB::table('checking_inventory')
                ->where('單號', $serialNum)
                ->update([
                    'approved_by' => $user,
                    'approved_at' => $approve_at,
                ]);

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 200, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('inventory')->upsert(
                    $whole_load[$i],
                    ['料號', '儲位'],
                    ['現有庫存', '最後更新時間'],
                );
            } // for

            \DB::commit();
            return \Response::json(['count' => $record, 'DB' => $database]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage(), 'DB' => $database], 421/* Status code here default is 200 ok*/);
        } //try - catch
    } // approveCheckingRecords

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyRecord(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $number = $request->input('serial_num');
        \DB::beginTransaction();
        try {
            \DB::table('checking_inventory')
                ->where('單號', $number)
                ->delete();
            \DB::commit();
            return \Response::json(['message' => "success"]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    } // destroyRecord
} // end of CheckingInventoryController class
