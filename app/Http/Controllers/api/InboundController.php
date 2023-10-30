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
    public function showStocks(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $inputArray_isn = json_decode($request->isnArray);
        $inputArray_loc = json_decode($request->locArray);

        $query = \DB::table('inventory');
        for ($i = 0; $i < count($inputArray_isn); $i++) {
            $query->orWhere([
                ['料號', '=', $inputArray_isn[$i]],
                ['儲位', '=', $inputArray_loc[$i]]
            ]);
        } // for
        $allResult = $query->get();

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
        try {
            $res_arr_values = array();
            for ($i = 1; $i < count($Alldata); $i++) {
                $isn = $Alldata[$i][0];
                $amount = $Alldata[$i][1];
                $loc = $Alldata[$i][2];

                $temp = array(
                    "料號" => $isn,
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

    //刪除 入庫資訊
    public function destroy(Request $request)
    {
        $now = Carbon::now();
        $list = json_decode($request->input('list'));
        $isn = json_decode($request->input('isn'));
        $amount = json_decode($request->input('amount'));
        $position = json_decode($request->input('position'));
        $inpeople = json_decode($request->input('inpeople'));
        $inreason = json_decode($request->input('inreason'));

        for ($i = 0; $i < count($list); $i++) {
            $stock = \DB::table('inventory')->where('料號', $isn)->where('儲位', $position)->sum('現有庫存');
            $buy = \DB::table('在途量')->where('料號', $isn)->sum('請購數量');
            \DB::beginTransaction();
            try {
                if ($stock < $amount) {
                    if ($stock === null) $stock = 0;

                    return \Response::json(['stock' => $stock, 'amount' => $amount], 420/* Status code here default is 200 ok*/);
                } // if

                if ($inreason !== '調撥' && $inreason !== '退庫') {
                    \DB::table('在途量')
                        ->where('料號', $isn)
                        ->update(['請購數量' => $buy + $amount]);
                } // if

                \DB::table('inventory')
                    ->where('料號', $isn)
                    ->where('儲位', $position)
                    ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $now]);

                \DB::table('inbound')
                    ->where('入庫單號', $list)
                    ->where('料號', $isn)
                    ->where('入庫數量', $amount)
                    ->where('入庫人員', $inpeople)
                    ->where('入庫原因', $inreason)
                    ->where('儲位', $position)
                    ->delete();
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            } // try catch

            return \Response::json(['list' => $list, 'isn' => $isn]/* Status code here default is 200 ok*/);
        } // for each record to delete
    } // destroy
}
