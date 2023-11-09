<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommonlyUsedController extends Controller
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
    public function showISN(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $inputArray = json_decode($request->input('isnArray'));
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
    } // showISN

    public function showLocs(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $allResult = \DB::table('儲位')->select("儲存位置")->get();

        return \Response::json(['data' => $allResult, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // show

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

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
