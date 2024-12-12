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
        //
    } // store

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $data = [];
        $data = \DB::table('發料部門')->get();
        return \Response::json(['data' => $data, "dbName" => $database]/* Status code here default is 200 ok*/);
    } // show

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRecord(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $pn = json_decode($request->input('pn'));
        $name = json_decode($request->input('name'));
        
        $record = 0;
        // dd($safestock); // test
        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($pn); $i++) {
                $temp = array(
                    "料號" => $pn[$i],
                    '品名' => $name[$i],
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 100, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('consumptive_material')->upsert(
                    $whole_load[$i],
                    ['料號'],
                    ['品名', '規格', '單價', '幣別', '單位', 'MPQ', 'MOQ', 'LT', '月請購', 'A級資材', '發料部門', '安全庫存']
                );

                $record = $record + $temp_record;
            } // for

            \DB::commit();

            return \Response::json(['record' => $record]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch
    } // updateRecord

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

        $count = count(json_decode($request->input('pn')));
        $number = json_decode($request->input('pn'));
        \DB::beginTransaction();
        try {
            \DB::table('consumptive_material')
                ->whereIn('料號', $number)
                ->delete();
            \DB::commit();
            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    } // destroyRecord
} // end of CheckingInventoryController class
