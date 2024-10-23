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
        if (count($inputArray_loc) === 0) {
            for ($i = 0; $i < count($inputArray_isn); $i++) {
                $query->orWhere('inventory.料號', '=', $inputArray_isn[$i]);
            } // for
        } // if
        else {
            for ($i = 0; $i < count($inputArray_isn); $i++) {
                $query->orWhere([
                    ['inventory.料號', '=', $inputArray_isn[$i]],
                    ['inventory.儲位', '=', $inputArray_loc[$i]]
                ]);
            } // for
        } // else

        $allResult = $query
            ->leftjoin('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', 'inventory.料號');
            })->get();

        return \Response::json(['data' => $allResult, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showStocks

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) // 庫存新增 上傳
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test
        $record = 0;
        $record2 = 0;
        $record3 = 0;
        $user = $request->input('User');
        $newStock = json_decode($request->input('newStock'));
        $inboundRecords = json_decode($request->input('inboundCount'));
        $newInTransit = json_decode($request->input('newInTransit'));
        $serialNum = json_decode($request->input('serialNum'));
        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($newStock); $i++) {
                $isn = $newStock[$i][0];
                $amount = $newStock[$i][1];
                $loc = $newStock[$i][2];

                $temp = array(
                    "料號" => $isn,
                    '現有庫存' => $amount,
                    '儲位' => $loc,
                    '最後更新時間' => Carbon::now()
                );

                $res_arr_values[] = $temp;
            } //for

            $res_arr_values2 = array();
            for ($i = 0; $i < count($inboundRecords); $i++) {
                $isn = $inboundRecords[$i][0];
                $amount = $inboundRecords[$i][1];
                $loc = $inboundRecords[$i][2];
                $reason = $inboundRecords[$i][3];

                $temp = array(
                    '入庫單號' => $serialNum,
                    "料號" => $isn,
                    '入庫數量' => $amount,
                    '儲位' => $loc,
                    '入庫人員' => $user,
                    '入庫原因' => $reason,
                    '入庫時間' => Carbon::now()
                );

                $res_arr_values2[] = $temp;
            } //for

            $res_arr_values3 = array();
            for ($i = 0; $i < count($newInTransit); $i++) {
                $isn = $newInTransit[$i][0];
                $amount = $newInTransit[$i][1];

                $temp = array(
                    "客戶" => null,
                    "料號" => $isn,
                    '請購數量' => $amount,
                    '說明' => '入庫/Inbound',
                    '修改人員' => $user,
                    '最後更新時間' => Carbon::now()
                );

                $res_arr_values3[] = $temp;
            } //for

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 200, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('inventory')->upsert(
                    $whole_load[$i],
                    ['料號', '儲位'],
                    ['料號', '儲位', '現有庫存', '最後更新時間']
                );

                $record = $record + $temp_record;
            } // for

            $whole_load2 = array_chunk($res_arr_values2, 200, true);
            // dump($whole_load2[0]); // test
            for ($i = 0; $i < count($whole_load2); $i++) {
                $temp_record = \DB::table('inbound')
                    ->insert($whole_load2[$i]);

                $record2 = $record2 + $temp_record;
            } // for

            $whole_load3 = array_chunk($res_arr_values3, 200, true);
            for ($i = 0; $i < count($whole_load3); $i++) {
                $temp_record = \DB::table('在途量')->upsert(
                    $whole_load3[$i],
                    ['料號'],
                    ['料號', '客戶', '請購數量', '說明', '修改人員', '最後更新時間']
                );

                $record3 = $record3 + $temp_record;
            } // for

            \DB::commit();

            return \Response::json(['newStock' => $record, 'inbound' => $record2, 'inTransit' => $record3, 'DB' => $dbName]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage(), 'DB' => $dbName], 421/* Status code here default is 200 ok*/);
        } //try - catch
    } // update

    //入庫-儲位調撥
    public function locTransfer(Request $request)
    {
        $user = $request->input('User');
        $number = json_decode($request->input('number'));
        $oldposition = json_decode($request->input('oldposition'));
        $amount = json_decode($request->input('amount'));
        $newposition = json_decode($request->input('newposition'));

        $insufficient_pn = [];
        $now = Carbon::now();

        try {
            \DB::beginTransaction();
            // loop thru number array
            for ($i = 0; $i < count($number); $i++) {
                $oldLocStock = \DB::table('inventory')->where('料號', $number[$i])->where('儲位', $oldposition[$i])->sum('現有庫存');
                $newLocStock = \DB::table('inventory')->where('料號', $number[$i])->where('儲位', $newposition[$i])->sum('現有庫存');
                if ($newLocStock === null) $newLocStock = 0;
                
                if ($oldLocStock < $amount[$i]) {
                    array_push($insufficient_pn, $number[$i]);
                } // if
                else {
                    \DB::table('inventory')
                        ->upsert(
                            [
                                ['料號' => $number[$i], '現有庫存' => $oldLocStock - $amount[$i], '儲位' => $oldposition[$i], '最後更新時間' => $now],
                                ['料號' => $number[$i], '現有庫存' => $newLocStock + $amount[$i], '儲位' => $newposition[$i], '最後更新時間' => $now]
                            ],
                            ['料號', '儲位'],
                            ['現有庫存', '最後更新時間']
                        );

                    \DB::table('LocTransferRecord')
                        ->insert([
                            '料號' => $number[$i],
                            '調動數量' => $amount[$i],
                            '操作人' => $user['username'],
                            '調出儲位' => $oldposition[$i],
                            '原調出儲位庫存' => $oldLocStock,
                            '接收儲位' => $newposition[$i],
                            '原接收儲位庫存' => $newLocStock,
                            '操作時間' => $now
                        ]);
                } // else
            } // for

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $mess = $e->getMessage();
            return \Response::json(['message' => $mess], 420/* Status code here default is 200 ok*/);
        } // try catch

        if (count($insufficient_pn) > 0) {
            return \Response::json(['insufficient_pn' => $insufficient_pn], 422);
        } // if
        else {
            return \Response::json(['message' => 'all success']/* Status code here default is 200 ok*/);
        } // else
    } // locTransfer

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
        $list = json_decode($request->input('list'));
        $isn = json_decode($request->input('isn'));
        $amount = json_decode($request->input('amount'));
        $position = json_decode($request->input('position'));
        $inpeople = json_decode($request->input('inpeople'));
        $inreason = json_decode($request->input('inreason'));
        $inboundtime = json_decode($request->input('inboundtime'));

        $error_list = [];
        $success_list = [];
        for ($i = 0; $i < count($list); $i++) {
            $stock = \DB::table('inventory')->where('料號', $isn[$i])->where('儲位', $position[$i])->sum('現有庫存');
            $buy = \DB::table('在途量')->where('料號', $isn[$i])->sum('請購數量');
            if ($stock < $amount[$i]) {
                if ($stock === null) $stock = 0;
                array_push($error_list, [$list[$i], $isn[$i], $inpeople[$i], $amount[$i], $inboundtime[$i]]);
                continue;
            } // if

            \DB::beginTransaction();
            try {
                if ($inreason[$i] !== '調撥' && $inreason[$i] !== '退庫') {
                    \DB::table('在途量')
                        ->where('料號', $isn)
                        ->update(['請購數量' => $buy + $amount[$i]]);
                } // if

                \DB::table('inventory')
                    ->where('料號', $isn)
                    ->where('儲位', $position[$i])
                    ->update(['現有庫存' => $stock - $amount[$i], '最後更新時間' => $now]);

                \DB::table('inbound')
                    ->where('入庫單號', $list[$i])
                    ->where('料號', $isn[$i])
                    ->where('入庫數量', $amount[$i])
                    ->where('入庫人員', $inpeople[$i])
                    ->where('入庫原因', $inreason[$i])
                    ->where('儲位', $position[$i])
                    ->delete();

                \DB::commit();

                array_push($success_list, [$list[$i], $isn[$i], $inpeople[$i], $inboundtime[$i]]);
            } catch (\Exception $e) {
                \DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            } // try catch
        } // for each record to delete

        if (count($error_list) > 0) {
            return \Response::json(['error_list' => $error_list, 'success_list' => $success_list], 420/* Status code here default is 200 ok*/);
        } // if
        else {
            return \Response::json(['success_list' => $success_list]/* Status code here default is 200 ok*/);
        } // else
    } // destroy
} // InboundController
