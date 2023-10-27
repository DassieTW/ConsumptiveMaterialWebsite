<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    public function showNonMonthly(Request $request) // get 非月請購
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $datas = [];

        $datas = \DB::table('consumptive_material')
            ->join('非月請購', function ($join) {
                $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                    ->whereNull('SXB單號');
            })->get();


        return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showNonMonthly

    public function showTransit(Request $request) // get 在途量
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $transitisn = json_decode($request->input('transitisn'));
        $transitsend = json_decode($request->input('transitsend'));

        //dd($transitsend);
        // dd($send);
        $datas = [];

        $test = \DB::table('在途量')->select('料號', \DB::raw('SUM(請購數量) as 請購數量'))
            ->groupBy('料號');

        $datas = \DB::table('consumptive_material')
            ->joinSub($test, '在途量', function ($join) {
                $join->on('在途量.料號', '=', 'consumptive_material.料號');
            })->where('consumptive_material.料號', 'like', $transitisn . '%')
            ->where('consumptive_material.發料部門', 'like', $transitsend . '%')
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

    //load re-check consume
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

    //send consume mail
    public static function sendconsumemail($email, $sessemail, $username, $database)
    {
        $dename = \DB::table('login')->where('username', $username)->value('姓名');
        $data = array('email' => urlencode($sessemail), 'username' => urlencode($username), 'database' => urlencode($database), 'name' => urlencode($dename));

        \Mail::send('mail/consumecheck', $data, function ($message) use ($email) {

            $message->to($email, 'Tutorials Point')->subject('請確認單耗資料');
            $message->bcc('vincent6_yeh@pegatroncorp.com');
            // $message->attach(public_path() . '/download/LineExample.xlsx');
            $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
        });
    }

    //提交料號單耗
    public function update_UnitConsumption(Request $request)
    {
        $count = $request->input('count');
        $row = $request->input('row');
        $record = 0;
        $check = array();
        $email = $request->input('email');
        $sessemail = $email;
        $username = \Auth::user()->username;
        $database = $request->session()->get('database');
        $database = $database;

        try {
            $res_arr_values = array();
            for ($i = 0; $i < $count; $i++) {
                $number = $request->input('number')[$i];
                $number90 = $request->input('number90')[$i];
                $consume = $request->input('consume')[$i];

                $temp = array(
                    "料號" => $number,
                    '料號90' => $number90,
                    '單耗' => $consume,
                    '畫押信箱' => $email,
                    '狀態' => "待畫押",
                    '送單時間' => Carbon::now(),
                    '送單人' => \Auth::user()->username
                );

                $res_arr_values[] = $temp;
                array_push($check, $row[$i]);
            } //for

            \DB::beginTransaction();

            $record = \DB::table('月請購_單耗')->upsert(
                $res_arr_values,
                ['料號', '料號90'],
                ['單耗', '畫押信箱', '狀態', '送單時間', '送單人']
            );

            \DB::commit();

            if ($record > 0) {
                self::sendconsumemail($email, $sessemail, $username, $database);
            } // if

            return \Response::json(['record' => $record, 'check' => $check]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch

    } // consumenewsubmit

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
