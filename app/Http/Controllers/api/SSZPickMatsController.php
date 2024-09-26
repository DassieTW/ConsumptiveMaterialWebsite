<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Date;
use DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SSZNumber;
use Exception;

class SSZPickMatsController extends Controller
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
    public function storeDataFromMIS(Request $request)
    {
        \Log::channel('dbquerys')->info('----------------------------MIS---------------------------');
        \Log::channel('dbquerys')->info(json_encode($request->post()));
        \Log::channel('dbquerys')->info('--------------------------MIS END--------------------------');

        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test
        $datetime = Carbon::now();
        try {
            \DB::beginTransaction();
            $records = SSZNumber::updateOrCreate(
                ['id' => $request->input('FlowNumber')],
                [
                    'status' => $request->input('Status'),
                    'received_time' => $datetime,
                ]
            );
            \DB::commit();
        } catch (Exception $e) {
            dd($e); // dump error
        } // try - catch

        return \Response::json(['message' => 'Data Has Been Received', 'Status' => '000'], 200);
    } // storeDataFromMIS

    public function storeDataFromMIS_Test(Request $request)
    {
        \Log::channel('dbquerys')->info('----------------------------MIS---------------------------');
        \Log::channel('dbquerys')->info(json_encode($request->post()));
        \Log::channel('dbquerys')->info('--------------------------MIS END--------------------------');

        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "HQ TEST Consumables management");
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test
        $datetime = Carbon::now();
        try {
            \DB::beginTransaction();
            $records = SSZNumber::updateOrCreate(
                ['id' => $request->input('FlowNumber')],
                [
                    'status' => $request->input('Status'),
                    'received_time' => $datetime,
                ]
            );
            \DB::commit();

            $db = \DB::connection('sqlsrv_ssztest')->table('[SAPBPM].[dbo].[V_SSZ_RelQtyInfo]')
                ->where('FlowNumber', $request->input('FlowNumber'))
                ->get(); 
        } catch (Exception $e) {
            dd($e); // dump error
        } // try - catch

        return \Response::json(['message' => 'Data Has Been Received', 'Status' => '000'], 200);
    } // storeDataFromMIS_Test

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

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
