<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BasicInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDispatcher(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $data = [];
        $data = \DB::table('發料部門')->get();
        return \Response::json(['data' => $data, "dbName" => $database]/* Status code here default is 200 ok*/);
    } // showDispatcher

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePN(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $pn = json_decode($request->input('pn'));
        $name = json_decode($request->input('name'));
        $spec = json_decode($request->input('spec'));
        $price = json_decode($request->input('price'));
        $unit = json_decode($request->input('unit'));
        $currency = json_decode($request->input('currency'));
        $mpq = json_decode($request->input('mpq'));
        $moq = json_decode($request->input('moq'));
        $lt = json_decode($request->input('lt'));
        $gradea = json_decode($request->input('gradea'));
        $monthly = json_decode($request->input('monthly'));
        $dispatcher = json_decode($request->input('dispatcher'));
        $safestock = json_decode($request->input('safe'));
        $record = 0;
        // dd($safestock); // test
        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($pn); $i++) {
                $temp = array(
                    "料號" => $pn[$i],
                    '品名' => $name[$i],
                    '規格' => $spec[$i],
                    '單價' => floatval($price[$i]),
                    '幣別' => $currency[$i],
                    '單位' => $unit[$i],
                    'MPQ' => intval($mpq[$i]),
                    'MOQ' => intval($moq[$i]),
                    'LT' => floatval($lt[$i]),
                    '月請購' => $monthly[$i],
                    'A級資材' => $gradea[$i],
                    '發料部門' => $dispatcher[$i],
                );

                if ($safestock[$i] === "null") {
                    $temp['安全庫存'] = null;
                } else {
                    $temp['安全庫存'] = intval($safestock[$i]);
                } // if else

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
    } // updatePN

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPN(Request $request)
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
    } // destroyPN
}
