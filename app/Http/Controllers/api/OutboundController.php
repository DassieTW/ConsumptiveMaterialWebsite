<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OutboundController extends Controller
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
    // 領料單添加
    public function storeNewPicklist(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $user = $request->input('username');
        $now = Carbon::now();
        $opentime = Carbon::now()->format('YmdHis');
        $Alldata = json_decode($request->input('AllData'));

        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($Alldata[0]); $i++) {
                $remark = $Alldata[7][$i];
                if ($remark === null || $remark === "") $remark = "";
                $temp = array(
                    "料號" => $Alldata[0][$i],
                    "線別" => $Alldata[1][$i],
                    "領用原因" => $Alldata[2][$i],
                    "品名" => $Alldata[3][$i],
                    "規格" => $Alldata[4][$i],
                    "單位" => $Alldata[5][$i],
                    "預領數量" => $Alldata[6][$i],
                    "實際領用數量" => $Alldata[6][$i],
                    "備註" => $remark,
                    "領料單號" => $opentime,
                    "開單時間" => $now,
                    "開單人員" => $user,
                );
                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();
            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 100, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                \DB::table('outbound')
                    ->insert($whole_load[$i]);
            } // for
            \DB::commit();
            return \Response::json(['serialNo' => $opentime]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } // catch
    } // storeNewPicklist

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
    } // show

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //刪除 入庫紀錄 並還原庫存
    public function destroy(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test
        $now = Carbon::now();
    } // destroy
} // OutboundController
