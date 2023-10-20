<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InboundController extends Controller
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
    public function show(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $inputArray = json_decode($request->isnArray);
        $combinedValueStr = "";
        foreach ($inputArray as $singleIsn) {
            if ($combinedValueStr == "") {
                $combinedValueStr = $combinedValueStr . "('" . $singleIsn . "')";
            } else {
                $combinedValueStr = $combinedValueStr . ", ('" . $singleIsn . "')";
            } // else
        } // foreach

        $existIsn = ("Select input_array.料號 From (values " . $combinedValueStr . ") input_array(料號)");

        $allResult = \DB::table('consumptive_material')
            ->rightJoinSub($existIsn, 'input_array', function ($join) {
                $join->on('consumptive_material.料號', '=', 'input_array.料號');
            })->get();

        return \Response::json(['data' => $allResult, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // show

    public function showLocs(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $allResult = \DB::table('儲位')->select("儲存位置")->get();

        return \Response::json(['data' => $allResult, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // show

    public function showStocks(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $allResult = \DB::table('inventory')->select(["料號", "現有庫存", "儲位"])->get();

        return \Response::json(['data' => $allResult, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showStocks

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $Alldata = json_decode($request->input('isnArray'));
        // dd($Alldata); //test
        try {
            $res_arr_values = array();
            for ($i = 1; $i < count($Alldata); $i++) {
                $number = $Alldata[$i][0];
                $amount = $Alldata[$i][1];
                $loc = $Alldata[$i][2];

                $temp = array(
                    "料號" => $number,
                    '現有庫存' => $amount,
                    '儲位' => $loc,
                    '最後更新時間' => Carbon::now()
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();

            $record = \DB::table('inventory')->upsert(
                $res_arr_values,
                ['料號', '儲位'],
                ['料號', '儲位', '現有庫存', '最後更新時間']
            );

            \DB::commit();

            return \Response::json(['record' => $record, 'DB' => $dbName]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage(), 'DB' => $dbName], 421/* Status code here default is 200 ok*/);
        } //try - catch
    } // update

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
