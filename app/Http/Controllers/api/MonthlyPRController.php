<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Sentry\Util\JSON;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\style\Borders;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\月請購_單耗;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Mail;
use function Swoole\Coroutine\Http\get;

class MonthlyPRController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function checkIfUnitConsumptionExist(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test
        $number = json_decode($request->input('number'));
        $number90 = json_decode($request->input('number90'));
        $data_length = count($number90);
        $mergedResult = "";
        // chunk the parameter array first so it doesnt exceed the MSSQL parameters hard limit
        $whole_load_of_number = array_chunk($number, 100, false);
        $whole_load_of_number90 = array_chunk($number90, 100, false);
        try {
            for ($i = 0; $i < count($whole_load_of_number); $i++) {
                // loop thru each chunk
                $query = \DB::table('月請購_單耗');
                for ($j = 0; $j < count($whole_load_of_number[$i]); $j++) {
                    $single_isn =  $whole_load_of_number[$i][$j];
                    $single_isn90 = $whole_load_of_number90[$i][$j];
                    $query->orWhere(
                        function ($semiquery) use ($single_isn, $single_isn90) {
                            $semiquery->where('料號', '=', $single_isn)
                                ->where('料號90', '=', $single_isn90);
                        } // function
                    );
                } // for

                $result = $query->get(['料號', '料號90', '單耗', '狀態']);
                if ($mergedResult === "") {
                    $mergedResult = $result;
                } // if
                else {
                    $mergedResult = $result->merge($mergedResult);
                } // else
            } //for

            // dd($mergedResult); // test
            return \Response::json(['data' => $mergedResult->all()], 200 /* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            dd($e);
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } // try - catch
    } // checkIfUnitConsumptionExist

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
        $number = json_decode($request->input('number'));
        $number90 = json_decode($request->input('number90'));
        $data_length = count($number90);

        // chunk the parameter array first so it doesnt exceed the MSSQL parameters hard limit
        $whole_load_of_number = array_chunk($number, 100, false);
        $whole_load_of_number90 = array_chunk($number90, 100, false);
        $mergedResult = "";

        try {
            for ($i = 0; $i < count($whole_load_of_number); $i++) {
                // loop thru each chunk
                $query = \DB::table('MPS');
                for ($j = 0; $j < count($whole_load_of_number[$i]); $j++) {
                    $single_isn =  $whole_load_of_number[$i][$j];
                    $single_isn90 = $whole_load_of_number90[$i][$j];
                    $query->orWhere(
                        function ($semiquery) use ($single_isn, $single_isn90) {
                            $semiquery->where('料號', '=', $single_isn)
                                ->where('料號90', '=', $single_isn90)
                                ->whereNotNull('SXB單號');
                        } // function
                    );
                } // for

                $result = $query->get();
                if ($mergedResult === "") {
                    $mergedResult = $result;
                } // if
                else {
                    $mergedResult = $result->merge($mergedResult);
                } // else
            } // for

            $PR_already_sent = $mergedResult;

            if (count($PR_already_sent) > 0) {
                return \Response::json(['PR_ALREADY' => json_encode($PR_already_sent)], 420 /* Status code here default is 200 ok*/);
            } // if

            $res_arr_values = array();
            for ($i = 0; $i < $data_length; $i++) {
                $temp = array(
                    "料號" => json_decode($request->input('number'))[$i],
                    "料號90" => json_decode($request->input('number90'))[$i],
                    "下月MPS" => floatval(json_decode($request->input('nextmps'))[$i]),
                    "下月生產天數" => floatval(json_decode($request->input('nextday'))[$i]),
                    "本月MPS" => floatval(json_decode($request->input('nowmps'))[$i]),
                    "本月生產天數" => floatval(json_decode($request->input('nowday'))[$i]),
                    "填寫時間" => $now
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 100, true);
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
            dd($e);
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

            $PR_already_sent = \DB::table('非月請購')
                ->whereIn('料號', json_decode($request->input('number')))
                ->whereNotNull('SXB單號')
                ->get();

            if (count($PR_already_sent) > 0) {
                return \Response::json(['PR_ALREADY' => json_encode($PR_already_sent)], 420 /* Status code here default is 200 ok*/);
            } // if

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

    public function storeBuylist(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $request_user = $request->input('User');
        $PN = json_decode($request->input('PN'));
        $pName = json_decode($request->input('pName'));
        $Spec = json_decode($request->input('Spec'));
        $Unit_price = json_decode($request->input('Unit_price'));
        $nowNeed = json_decode($request->input('nowNeed'));
        $nextNeed = json_decode($request->input('nextNeed'));
        $Stock = json_decode($request->input('Stock'));
        $in_Transit = json_decode($request->input('in_Transit'));
        $ReqAmount = json_decode($request->input('ReqAmount'));
        $total_price1 = json_decode($request->input('total_price1'));
        $currency_name = json_decode($request->input('currency_name'));
        $total_price2 = json_decode($request->input('total_price2'));
        $MOQ = json_decode($request->input('MOQ'));
        $nonMPS_PN_Array = json_decode($request->input('nonMPS_PN_Array'));
        $MPS_90PN_Array = json_decode($request->input('MPS_90PN_Array'));
        $MPS_PN_Array = json_decode($request->input('MPS_PN_Array'));

        $count = count(json_decode($request->input('PN')));
        $now = Carbon::now();
        $SXB_serial_number = date("YmdHis");
        $record = 0;
        $mergedResult = "";

        // chunk the parameter array first so it doesnt exceed the MSSQL parameters hard limit
        $whole_load_of_number = array_chunk($MPS_PN_Array, 100, false);
        $whole_load_of_number90 = array_chunk($MPS_90PN_Array, 100, false);

        try {
            \DB::beginTransaction();
            // ---------------------------------------------------------------------
            // update MPS table
            for ($i = 0; $i < count($whole_load_of_number); $i++) {
                // loop thru each chunk
                $query = \DB::table('MPS');
                for ($j = 0; $j < count($whole_load_of_number[$i]); $j++) {
                    $single_isn =  $whole_load_of_number[$i][$j];
                    $single_isn90 = $whole_load_of_number90[$i][$j];
                    $query->orWhere(
                        function ($semiquery) use ($single_isn, $single_isn90) {
                            $semiquery->where('料號', '=', $single_isn)
                                ->where('料號90', '=', $single_isn90);
                        } // function
                    );
                } // for

                $result_MPS = $query->update(['SXB單號' => $SXB_serial_number]);
            } //for
            // ---------------------------------------------------------------------
            // update 非月請購 table
            $result2 = \DB::table('非月請購')
                ->whereIn('料號', $nonMPS_PN_Array)
                ->update(['SXB單號' => $SXB_serial_number]);
            // ---------------------------------------------------------------------
            // insert 請購單 table
            $res_arr_values = array();
            for ($i = 0; $i < $count; $i++) {
                if (floatval(str_replace(',', '', $ReqAmount[$i])) > 0.0) {
                    $temp = array(
                        "SRM單號" => "未簽核",
                        "SXB單號" => $SXB_serial_number,
                        "開單人員" => $request_user['username'],
                        "料號" => $PN[$i],
                        "品名" => $pName[$i],
                        "規格" => $Spec[$i],
                        "單價" => floatval(str_replace(',', '', $Unit_price[$i])),
                        "幣別" => $currency_name[$i],
                        "現有庫存" => floatval(str_replace(',', '', $Stock[$i])),
                        "在途數量" => floatval(str_replace(',', '', $in_Transit[$i])),
                        "當月需求" => floatval(str_replace(',', '', $nowNeed[$i])),
                        "下月需求" => floatval(str_replace(',', '', $nextNeed[$i])),
                        "本次請購數量" => floatval(str_replace(',', '', $ReqAmount[$i])),
                        "請購金額" => floatval(str_replace(',', '', $total_price1[$i])),
                        "匯率" => floatval(str_replace(',', '', $total_price2[$i])),
                        "MOQ" => intval(str_replace(',', '', $MOQ[$i])),
                        "請購時間" => $now,
                    );

                    $res_arr_values[] = $temp;
                } // if
            } // for

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 50, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('請購單')->insert(
                    $whole_load[$i]
                );

                $record = $record + $temp_record;
            } // for
            // ---------------------------------------------------------------------
            \DB::commit();
            return \Response::json(['record' => $record] /* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } // try catch
    } // storeBuylist

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

    public function showInTransit(Request $request) // get 在途量
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $transitisn = json_decode($request->input('transitisn'));

        //dd($transitsend);
        // dd($send);
        $datas = [];

        $test = \DB::table('在途量');

        $datas = \DB::table('consumptive_material')
            ->joinSub($test, '在途量', function ($join) {
                $join->on('在途量.料號', '=', 'consumptive_material.料號');
            })
            // ->where('請購數量', '>', 0)
            ->get();

        //dd($datas);
        return \Response::json(['data' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showInTransit

    public function showSXB(Request $request) // get SXB
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $sxbisn = json_decode($request->input('sxbisn'));
        $sxbsend = json_decode($request->input('sxbsend'));
        $sxbbegin = date(json_decode($request->input('sxbbegin')));
        $sxbend = strtotime(json_decode($request->input('sxbend')));
        // dump($request->input('sxbend')); // test
        // return;
        $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $sxbend));

        $datas = [];
        $datas = \DB::table('consumptive_material')
            ->join('請購單', function ($join) {
                $join->on('請購單.料號', '=', 'consumptive_material.料號')
                    ->whereNotNull('SXB單號');
            })->where('consumptive_material.料號', 'like', $sxbisn . '%')
            ->where('consumptive_material.發料部門', 'like', $sxbsend . '%')
            ->whereBetween('請購時間', [$sxbbegin, $end])
            ->get();

        return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
    } // showSXB

    //料號單耗(查詢)
    public function showAllUnitConsumption(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // tests

        $data = \DB::table('consumptive_material')
            ->join('月請購_單耗', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->get();

        return \Response::json(['data' => $data, "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showAllUnitConsumption

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

        $people = \DB::table('login')
            ->join('人員信息', function ($join) {
                $join->on('人員信息.工號', '=', 'login.username');
            })
            ->where('priority', "=", 1)
            ->whereNotNull('email')
            ->get();
        return \Response::json(['data' => $people, "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showCheckersEmail

    public function showMPS(Request $request) // get 月請購
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $results = \DB::table('MPS')
            ->whereNull('SXB單號')
            ->get();

        return \Response::json(['data' => $results, "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showMPS

    public function showCurrency(Request $request) // get all exchange rate
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        // get all exchange rate
        $results = \File::get(public_path('exchange_rate.json'));

        return \Response::json(['data' => json_encode($results), "dbName" => $dbName]/* Status code here default is 200 ok*/);
    } // showCurrency

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
        // ---------------------- test ----------------------
        // ->select('月請購_單耗.*', 'MPS.*', 'consumptive_material.*')
        // ->where('月請購_單耗.狀態', '=', "已完成")
        // ->get();
        // dd($temp); // test
        // ---------------------- test ----------------------

        $datas = $temp
            ->leftJoinSub($currentStockByPN, 'sum_stock', '月請購_單耗.料號', '=', 'sum_stock.料號')
            ->leftJoinSub($inTransit, 'shipping', '月請購_單耗.料號', '=', 'shipping.料號')
            ->select('月請購_單耗.*', 'MPS.*', 'consumptive_material.*', 'sum_stock.total_stock', 'shipping.in_transit')
            ->where('月請購_單耗.狀態', '=', "已完成")
            ->whereNull('MPS.SXB單號')
            ->get();

        // dd($datas); // test
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
        $dename = \DB::table('login')
            ->join('人員信息', function ($join) {
                $join->on('人員信息.工號', '=', 'login.username');
            })
            ->where('username', $username)
            ->value('姓名');
        $data = array('app_url' => urlencode(env('APP_URL')), 'email' => urlencode($email), 'username' => urlencode($username), 'database' => urlencode($database), 'name' => urlencode($dename));

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

    //更新在途量
    public function update_InTransit(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $isn = json_decode($request->input('isn'));
        $qty = json_decode($request->input('qty'));
        $descr = json_decode($request->input('descr'));
        $username = $request->input('username');
        $record = 0;

        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($isn); $i++) {
                $temp = array(
                    "料號" => $isn[$i],
                    '請購數量' => floatval($qty[$i]),
                    '說明' => strval($descr[$i]),
                    '最後更新時間' => Carbon::now(),
                    '修改人員' => $username
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 200, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('在途量')->upsert(
                    $whole_load[$i],
                    ['料號'],
                    ['請購數量', '說明', '最後更新時間', '修改人員']
                );

                $record = $record + $temp_record;
            } // for

            // delete the record if the qty is 0
            $delete_record = \DB::table('在途量')
                ->where('請購數量', '<=', 0)
                ->delete();

            \DB::commit();

            return \Response::json(['record' => $record]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch
    } // update_InTransit

    //寄出請購單
    public static function sendPRMail(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $spreadsheet = new Spreadsheet();
        // $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Sun-ExtA');
        $worksheet = $spreadsheet->getActiveSheet();

        $request_user = $request->input('User');
        $PN = json_decode($request->input('PN'));
        $pName = json_decode($request->input('pName'));
        $Spec = json_decode($request->input('Spec'));
        $Unit_price = json_decode($request->input('Unit_price'));
        $nowNeed = json_decode($request->input('nowNeed'));
        $nextNeed = json_decode($request->input('nextNeed'));
        $Stock = json_decode($request->input('Stock'));
        $in_Transit = json_decode($request->input('in_Transit'));
        $ReqAmount = json_decode($request->input('ReqAmount'));
        $total_price1 = json_decode($request->input('total_price1'));
        $currency_name = json_decode($request->input('currency_name'));
        $total_price2 = json_decode($request->input('total_price2'));
        $MOQ = json_decode($request->input('MOQ'));

        $i = 3;
        //填寫表頭
        $worksheet->setCellValue("A2", "項次");
        $worksheet->setCellValue("B2", "耗材料號");
        $worksheet->setCellValue("C2", "品名");
        $worksheet->setCellValue("D2", "規格");
        $worksheet->setCellValue("E2", "單價");
        $worksheet->setCellValue("F2", "當月需求");
        $worksheet->setCellValue("G2", "下月需求");
        $worksheet->setCellValue("H2", "庫存");
        $worksheet->setCellValue("I2", "廠商未交貨");
        $worksheet->setCellValue("J2", "實際請購數量");
        $worksheet->setCellValue("K2", "總價");
        $worksheet->setCellValue("L2", "總價(USD)");
        $worksheet->setCellValue("M2", "MOQ");
        $worksheet->mergeCells("A1:M1"); // for the SUM row
        $title = explode(" Consumables management", $dbName)[0] . "廠 ";
        $year = date('Y/', strtotime("last day of 1 month"));
        $month = date('m', strtotime("last day of 1 month"));
        $lastday = date('t', strtotime("last day of 1 month"));
        $title  = $title . $month . "月 耗材購買明細";
        $worksheet->setCellValue("A1", $title);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A1")->getFont()->setSize(16);
        $worksheet->getStyle("A1")->getFont()->setBold(true);
        $total_USD = 0;

        //填寫內容
        for ($j = 0; $j < count($PN); $j++) {
            if ($ReqAmount[$j] !== "0" || $ReqAmount[$j] != 0) {
                $worksheet->setCellValue("A" . $i, $i - 2); // A
                $worksheet->setCellValue("B" . $i, $PN[$j]); // B
                $worksheet->setCellValue("C" . $i, str_replace("\n", "", $pName[$j])); // C
                $worksheet->setCellValue("D" . $i, str_replace("\n", "", $Spec[$j])); // D
                $worksheet->setCellValue("E" . $i, strval($Unit_price[$j]) . " " . strtoupper($currency_name[$j])); // E
                $worksheet->setCellValue("F" . $i, strval($nowNeed[$j])); // F
                $worksheet->setCellValue("G" . $i, strval($nextNeed[$j])); // G
                $worksheet->setCellValue("H" . $i, strval($Stock[$j])); // H
                $worksheet->setCellValue("I" . $i, strval($in_Transit[$j])); // I
                $worksheet->setCellValue("J" . $i, strval($ReqAmount[$j])); // J
                $worksheet->setCellValue("K" . $i, strval($total_price1[$j]) . " " . strtoupper($currency_name[$j])); // K
                $worksheet->setCellValue("L" . $i, strval($total_price2[$j])); // L
                $worksheet->setCellValue("M" . $i, strval($MOQ[$j])); // M

                $total_USD = $total_USD + floatval(str_replace(",", "", strval($total_price2[$j])));

                $i++;
            } else {
                continue;
            } // if else
        } // for

        $spreadsheet->getActiveSheet()->getStyle('A2:M2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('c4d79b');
        $worksheet->getStyle('A2' . ':' . 'M' . $i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // $worksheet->setCellValue(("K" . $i), ("=SUM(K3:K" . ($i - 1) . ")"));
        $worksheet->setCellValue(("L" . $i), strval(number_format($total_USD, 2)));

        $worksheet->setCellValue(("A" . $i + 2), "核准:");
        $worksheet->setCellValue(("D" . $i + 2), "審核:");
        $worksheet->getStyle("D" . $i + 2)->getAlignment()->setHorizontal('center');
        $worksheet->setCellValue(("K" . $i + 2), "製表: " . $request_user['detail_info']['姓名'] . " " . date('Y-m-d'));

        $worksheet->getStyle('A2:M2')->getAlignment()->setHorizontal('center');
        $worksheet->getStyle('A3:D' . ($i + 1))->getAlignment()->setHorizontal('center');
        $worksheet->getStyle('E3:M' . ($i + 1))->getAlignment()->setHorizontal('right');

        foreach ($worksheet->getColumnIterator() as $column) {
            $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        } // foreach

        // Set margins if needed
        $worksheet->getPageMargins()->setTop(0.3);
        $worksheet->getPageMargins()->setRight(0.3);
        $worksheet->getPageMargins()->setLeft(0.3);
        $worksheet->getPageMargins()->setBottom(0.3);

        $now = Carbon::now()->format('Ymd');
        $filename = $title . \Lang::get("monthlyPRpageLang.page_name") . "_" . $now . '.pdf';

        // Save as PDF
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
        $writer->save(public_path() . "/excel/" . $filename);

        // WaterMark
        // First, get the correct document size.
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app'),
            'orientation' => 'P' //直向
        ]);
        $pagecount = $mpdf->SetSourceFile(public_path() . "/excel/" . $filename);
        $tplId = $mpdf->ImportPage(1);
        $size = $mpdf->getTemplateSize($tplId);

        // Open a new instance with specified width and height, read the file again
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path('app'),
            'format' => [$size['width'], $size['height']] //PDF size
        ]);

        $mpdf->SetSourceFile(public_path() . "/excel/" . $filename);

        // Set Path to Font File
        $font_path = public_path() . "/fonts/MicrosoftJhengHeiBold.ttf";
        $text_to_write = ($request_user['detail_info']['姓名'] . " " . explode(" Consumables management", $dbName)[0] . "廠");

        // Create Image From Existing File
        $png_image = imagecreatefrompng(public_path() . "/admin/img/PEGA_Logo.png");
        imagesavealpha($png_image, true);
        imagealphablending($png_image, false);

        // Get image dimensions
        $width = imagesx($png_image);
        $height = imagesy($png_image);

        // Get center coordinates of image
        $centerX = $width / 2;
        $centerY = $height / 2;

        // Get size of text
        list($left, $bottom, $right,,, $top) = imageftbbox(120, 45, $font_path, $text_to_write);

        // Determine offset of text
        $left_offset = ($right - $left) / 2;
        $top_offset = ($bottom - $top) / 2;

        // Generate coordinates
        $x = $centerX - $left_offset + 220;
        $y = $centerY + $top_offset + 180;

        // Allocate A Color For The Text
        $black = imagecolorallocate($png_image, 0, 0, 0);

        // Print Text On Image
        imagettftext($png_image, 120, 45, $x, $y, $black, $font_path, $text_to_write);

        // Save Image
        imagepng($png_image, public_path() . "/excel/" . $request_user['username'] . "_PEGA_Logo.png");

        // create new image instance
        $manager = new ImageManager(new Driver());
        $image = $manager->read(public_path() . "/excel/" . $request_user['username'] . "_PEGA_Logo.png");

        // move the image to center it a bit since we added a text
        $image->crop(3535, 3535, 60, 60, position: 'bottom-right');
        $image->save(public_path() . "/excel/" . $request_user['username'] . "_PEGA_Logo.png");

        // Write into the instance and output it to the same file
        for ($i = 1; $i <= $pagecount; $i++) {
            $tplId = $mpdf->ImportPage($i);
            $mpdf->addPage();
            $mpdf->UseTemplate($tplId);

            // Set watermark image
            $mpdf->SetWatermarkImage(new \Mpdf\WatermarkImage(
                public_path() . "/excel/" . $request_user['username'] . "_PEGA_Logo.png", //image
                \Mpdf\WatermarkImage::SIZE_DEFAULT,  //original size of image
                \Mpdf\WatermarkImage::POSITION_CENTER_PAGE, //Centred on the whole page area
                0.1,  //Alpha of the watermark(values 0-1)
                true  //images behind page contents
            ));

            $mpdf->showWatermarkImage = true;
        } // for

        // Output the modified PDF directly to the original file
        $mpdf->Output(public_path() . "/excel/" . $filename, \Mpdf\Output\Destination::FILE);

        // You may want to delete the temporary file created by mPDF
        $mpdf->cleanup();
        // Clear Memory
        imagedestroy($png_image);
        \File::delete(public_path() . "/excel/" . $request_user['username'] . "_PEGA_Logo.png");
        $data = array(
            'PN' => $PN,
            'pName' => $pName,
            'Spec' => $Spec,
            'Unit_price' => $Unit_price,
            'nowNeed' => $nowNeed,
            'nextNeed' => $nextNeed,
            'Stock' => $Stock,
            'in_Transit' => $in_Transit,
            'ReqAmount' => $ReqAmount,
            'total_price1' => $total_price1,
            'currency_name' => $currency_name,
            'total_price2' => $total_price2,
            'MOQ' => $MOQ
        );

        // dd(str_replace("\n", "", $Spec[0])); // test

        Mail::send(
            'mail/pr_review',
            $data,
            function ($message) use ($filename, $request_user) {
                $message->to($request_user['detail_info']["email"])->subject('Consumables Management PR Review');
                $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                $message->attach(public_path() . '/excel/' . $filename);
                $message->from('CM_No-Reply@pegatroncorp.com', 'Consumables_Management_No-Reply');
            }
        );
        \File::delete(public_path() . '/excel/' . $filename);
    } // sendconsumemail

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUC(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $number = json_decode($request->input('number'));
        $number90 = json_decode($request->input('number90'));
        \DB::beginTransaction();
        for ($i = 0; $i < count($number); $i++) {
            try {
                月請購_單耗::where('料號', $number[$i])
                    ->where('料號90', $number90[$i])
                    ->delete();
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            } // try catch
        } // for
        return \Response::json(['record' => count($number)]/* Status code here default is 200 ok*/);
    } // destroyUC

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
            dd($e);
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

    public function rejectSXB(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test

        $sxb = json_decode($request->input('sxb'));
        try {
            \DB::beginTransaction();

            \DB::table('非月請購')
                ->where('SXB單號', $sxb)
                ->delete();

            \DB::table('MPS')
                ->where('SXB單號', $sxb)
                ->delete();

            \DB::table('請購單')
                ->where('SXB單號', $sxb)
                ->update(['SRM單號' => '已退單']); // 未簽核/已退單/已完成
            \DB::commit();
            return \Response::json(['message' => 'success']/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } // catch
    } // rejectSXB

    public function approveSXB(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $database = \DB::connection()->getDatabaseName(); // test
        $record = 0;
        $sxb = json_decode($request->input('sxb'));
        $isn = json_decode($request->input('isn'));
        $amount = json_decode($request->input('amount'));

        try {
            $res_arr_values = array();
            for ($i = 0; $i < count($isn); $i++) {
                $temp = array(
                    "料號" => $isn[$i],
                    '請購數量' => $amount[$i],
                );

                $res_arr_values[] = $temp;
            } //for

            \DB::beginTransaction();
            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($res_arr_values, 200, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('在途量')->upsert(
                    $whole_load[$i],
                    ['料號'],
                    ['請購數量']
                );

                $record = $record + $temp_record;
            } // for

            \DB::table('非月請購')
                ->where('SXB單號', $sxb)
                ->delete();

            \DB::table('MPS')
                ->where('SXB單號', $sxb)
                ->delete();

            \DB::table('請購單')
                ->where('SXB單號', $sxb)
                ->update(['SRM單號' => '已完成']); // 未簽核/已退單/已完成
            \DB::commit();
            return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            \DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } // catch
    } // approveSXB
} // MonthlyPRController