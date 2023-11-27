<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Sentry\Util\JSON;

use function Swoole\Coroutine\Http\get;

class MonthlyPRController extends Controller
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
    public function storeMonthlyPR(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test
        $now = Carbon::now();
        $record = 0;
        $data_length = count(json_decode($request->input('number')));
        try {
            $res_arr_values = array();
            for ($i = 0; $i < $data_length; $i++) {
                $temp = array(
                    "料號" => json_decode($request->input('number'))[$i],
                    "料號90" => json_decode($request->input('number90'))[$i],
                    "下月MPS" => json_decode($request->input('nextmps'))[$i],
                    "下月生產天數" => json_decode($request->input('nextday'))[$i],
                    "本月MPS" => json_decode($request->input('nowmps'))[$i],
                    "本月生產天數" => json_decode($request->input('nowday'))[$i],
                    "填寫時間" => $now
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 200, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('MPS')->upsert(
                    $whole_load[$i],
                    ['料號', '料號90'],
                    ['下月MPS', '下月生產天數', '本月MPS', '本月生產天數', '填寫時間']
                );

                $record = $record + $temp_record;
            } // for

            \DB::commit();
            return \Response::json(['record' => $record] /* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } // try - catch
    } // storeMonthlyPR

    public function storeNonMonthlyPR(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $count = count(json_decode($request->input('number')));
        $now = Carbon::now();
        $record = 0;
        try {
            $res_arr_values = array();
            for ($i = 0; $i < $count; $i++) {
                $temp = array(
                    "料號" => json_decode($request->input('number'))[$i],
                    "當月需求" => json_decode($request->input('thisdemand'))[$i],
                    "下月需求" => json_decode($request->input('nextdemand'))[$i],
                    "請購數量" => json_decode($request->input('amount'))[$i],
                    "說明" => json_decode($request->input('desc'))[$i],
                    "上傳時間" => $now,
                );

                $res_arr_values[] = $temp;
            } // for

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 200, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('非月請購')->upsert(
                    $whole_load[$i],
                    ['料號'],
                    ['當月需求', '下月需求', '請購數量', '上傳時間', '說明']
                );

                $record = $record + $temp_record;
            } // for

            \DB::commit();
            return \Response::json(['record' => $record] /* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } // try catch
    } // storeNonMonthlyPR

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNonMonthly(Request $request) // get 非月請購
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $datas = [];

        $currentStockByPN = \DB::table('inventory')
            ->select('料號', \DB::raw('SUM(現有庫存) as total_stock'))
            ->groupBy('料號');

        $inTransit = \DB::table('在途量')
            ->select('料號', \DB::raw('SUM(請購數量) as in_transit'))
            ->groupBy('料號');

        $temp = \DB::table('consumptive_material')
            ->join('非月請購', function ($join) {
                $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                    ->whereNull('SXB單號');
            });

        $datas = $temp
            ->leftJoinSub($currentStockByPN, 'sum_stock', '非月請購.料號', '=', 'sum_stock.料號')
            ->leftJoinSub($inTransit, 'shipping', '非月請購.料號', '=', 'shipping.料號')
            ->select('非月請購.*', 'consumptive_material.*', 'sum_stock.total_stock', 'shipping.in_transit')
            ->get();


        return \Response::json(['data' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showNonMonthly

    public function showTransit(Request $request) // get 在途量
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $transitisn = json_decode($request->input('transitisn'));
        // $transitsend = json_decode($request->input('transitsend'));

        //dd($transitsend);
        // dd($send);
        $datas = [];

        $test = \DB::table('在途量')->select('料號', \DB::raw('SUM(請購數量) as 請購數量'))
            ->groupBy('料號');

        $datas = \DB::table('consumptive_material')
            ->joinSub($test, '在途量', function ($join) {
                $join->on('在途量.料號', '=', 'consumptive_material.料號');
            })->where('consumptive_material.料號', 'like', $transitisn . '%')
            ->where('請購數量', '>', 0)->get();

        //dd($datas);
        return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showTransit

    public function showSXB(Request $request) // get SXB
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $sxbisn = json_decode($request->input('sxbisn'));
        $sxbsend = json_decode($request->input('sxbsend'));
        $sxbcheck = json_decode($request->input('sxbcheck'));
        $sxbbegin = date(json_decode($request->input('sxbbegin')));
        $sxbend = strtotime(json_decode($request->input('sxbend')));
        $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $sxbend));

        //dd($transitsend);
        // dd($send);
        $datas = [];
        $datas1 = [];
        // dd(json_decode($request->input('LookInTargets'))); // test
        $datas = \DB::table('consumptive_material')
            ->join('請購單', function ($join) {
                $join->on('請購單.料號', '=', 'consumptive_material.料號')
                    ->whereNotNull('SXB單號');
            })->where('consumptive_material.料號', 'like', $sxbisn . '%')
            ->where('consumptive_material.發料部門', 'like', $sxbsend . '%')
            ->get();


        if ($sxbcheck) {
            $datas = $datas->whereBetween('請購時間', [$sxbbegin, $end])->values();
        }

        return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showSXB

    //load rejected unit consumption
    public function showRejectedUnitConsumption(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $datas = \DB::table('consumptive_material')
            ->join('月請購_單耗', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->where('狀態', '待重畫')->get();

        return \Response::json(['datas' => $datas, "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showRejectedUnitConsumption

    public function showCheckersEmail(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $people = \DB::table('login')->where('priority', "=", 1)->whereNotNull('email')->get();
        return \Response::json(['data' => $people, "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showCheckersEmail

    public function showMPS(Request $request) // get 月請購
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $results = \DB::table('MPS')
            ->get();

        return \Response::json(['data' => $results, "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showMPS

    public function showBuylist(Request $request) // get 月請購 請購單
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $currentStockByPN = \DB::table('inventory')
            ->select('料號', \DB::raw('SUM(現有庫存) as total_stock'))
            ->groupBy('料號');

        $inTransit = \DB::table('在途量')
            ->select('料號', \DB::raw('SUM(請購數量) as in_transit'))
            ->groupBy('料號');

        $temp = \DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.料號', '=', '月請購_單耗.料號')
                    ->on('MPS.料號90', '=', '月請購_單耗.料號90');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
            });

        $datas = $temp
            ->leftJoinSub($currentStockByPN, 'sum_stock', '月請購_單耗.料號', '=', 'sum_stock.料號')
            ->leftJoinSub($inTransit, 'shipping', '月請購_單耗.料號', '=', 'shipping.料號')
            ->select('月請購_單耗.*', 'MPS.*', 'consumptive_material.*', 'sum_stock.total_stock', 'shipping.in_transit')
            ->where('月請購_單耗.狀態', '=', "已完成")
            ->get();

        return \Response::json(['data' => $datas, "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showBuylist

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //send consume mail
    public static function sendconsumemail($email, $username, $database)
    {
        $dename = \DB::table('login')->where('username', $username)->value('姓名');
        $data = array('email' => urlencode($email), 'username' => urlencode($username), 'database' => urlencode($database), 'name' => urlencode($dename));

        \Mail::send('mail/consumecheck', $data, function ($message) use ($email) {
            $message->to($email, 'Default Test')->subject('請確認單耗資料');
            $message->bcc('vincent6_yeh@pegatroncorp.com');
            // $message->attach(public_path() . '/download/LineExample.xlsx');
            $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management No-Reply');
        });
    } // sendconsumemail

    //提交料號單耗
    public function update_UnitConsumption(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $number = json_decode($request->input('number'));
        $number90 = json_decode($request->input('number90'));
        $consume = json_decode($request->input('consume'));
        $record = 0;
        $email = $request->input('email');
        $username = $request->input('username');

        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($number); $i++) {
                $temp = array(
                    "料號" => $number[$i],
                    '料號90' => $number90[$i],
                    '單耗' => floatval($consume[$i]),
                    '畫押信箱' => $email,
                    '狀態' => "待畫押",
                    '送單時間' => Carbon::now(),
                    '送單人' => $username
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 200, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('月請購_單耗')->upsert(
                    $whole_load[$i],
                    ['料號', '料號90'],
                    ['單耗', '畫押信箱', '狀態', '送單時間', '送單人']
                );

                $record = $record + $temp_record;
            } // for

            \DB::commit();

            if ($record > 0) {
                self::sendconsumemail($email, $username, $database);
            } // if

            return \Response::json(['record' => $record]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch

    } // update_UnitConsumption

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyMPS(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $number = json_decode($request->input('isn'));
        $number90 = json_decode($request->input('isn90'));
        $query = \DB::table('MPS');
        for ($i = 0; $i < count($number90); $i++) {
            $single_isn =  $number[$i];
            $single_isn90 = $number90[$i];
            $query->orWhere(
                function ($semiquery) use ($single_isn, $single_isn90) {
                    $semiquery->where('料號', '=', $single_isn)
                        ->where('料號90', '=', $single_isn90);
                }
            );
        } //for

        \DB::beginTransaction();
        try {
            $result = $query->delete();
            \DB::commit();
            return \Response::json(['deleted_rows_count' => $result]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    } // destroyMPS

    public function destroyNonMPS(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $count = count(json_decode($request->input('number')));
        $number = json_decode($request->input('number'));
        \DB::beginTransaction();
        try {
            \DB::table('非月請購')
                ->whereIn('料號', $number)
                ->delete();
            \DB::commit();
            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    } // destroyNonMPS
}
