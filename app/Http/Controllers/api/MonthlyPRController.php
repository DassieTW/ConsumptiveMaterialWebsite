<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Sentry\Util\JSON;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\style\Borders;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mail;

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
        // ---------------------- test ----------------------
        // ->select('月請購_單耗.*', 'MPS.*', 'consumptive_material.*')
        // ->where('月請購_單耗.狀態', '=', "已完成")
        // ->get();
        // dd($temp); // test

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

    //寄出請購單
    public static function sendPRMail(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        // $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
        // $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Microsoft JhengHei');
        $worksheet = $spreadsheet->getActiveSheet();

        $Alldata = json_decode($request->input('AllData'));
        $request_user = $request->input('User');
        $rate = json_decode($request->input('Rate'));
        // dd($request_user["email"]); // user
        // $stringValueBinder = new StringValueBinder();
        // $stringValueBinder->setNullConversion(false)->setFormulaConversion(false);
        // \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder($stringValueBinder); // make it so it doesnt covert 儲位 to weird number format

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
        $worksheet->setCellValue("K2", "總價RMB");
        $worksheet->setCellValue("L2", "台幣(匯率4.326)");
        $worksheet->setCellValue("M2", "MOQ");
        $worksheet->mergeCells("A1:M1"); // for the SUM row
        $database = $request->session()->get('database');
        $title = explode(" Consumables management", $database)[0] . "廠";
        $year = date('Y/', strtotime("last day of 1 month"));
        $month = date('m/', strtotime("last day of 1 month"));
        $lastday = date('t', strtotime("last day of 1 month"));
        $title  = $title . $year . $month . "01" . "-" . $month . $lastday . "號耗材購買明細";
        $worksheet->setCellValue("A1", $title);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle("A1")->getFont()->setSize(16);
        $worksheet->getStyle("A1")->getFont()->setBold(true);
        //填寫內容
        for ($j = 0; $j < $count; $j++) {
            if ($Alldata[11][$j] > 0) {
                $worksheet->setCellValueByColumnAndRow(1, $i, $i - 2);
                $worksheet->setCellValueByColumnAndRow(2, $i, $Alldata[2][$j]);
                $worksheet->setCellValueByColumnAndRow(3, $i, $Alldata[3][$j]);
                $worksheet->setCellValueByColumnAndRow(4, $i, $Alldata[4][$j]);
                $worksheet->setCellValueByColumnAndRow(5, $i, $Alldata[5][$j]);
                if ($Alldata[7][$j] === "/") {
                    $worksheet->getStyle("F" . $i)->getBorders()->setDiagonalDirection(Borders::DIAGONAL_BOTH)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                    $worksheet->getStyle("G" . $i)->getBorders()->setDiagonalDirection(Borders::DIAGONAL_BOTH)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                } else {
                    $worksheet->setCellValueByColumnAndRow(6, $i, $Alldata[7][$j]);
                    $worksheet->setCellValueByColumnAndRow(7, $i, $Alldata[8][$j]);
                }
                $worksheet->setCellValueByColumnAndRow(8, $i, $Alldata[9][$j]);
                $worksheet->setCellValueByColumnAndRow(9, $i, $Alldata[10][$j]);
                $worksheet->setCellValueByColumnAndRow(10, $i, $Alldata[11][$j]);
                $worksheet->setCellValueByColumnAndRow(11, $i, $Alldata[12][$j]);
                $worksheet->setCellValue(("L" . $i), ("=K" . $i) . "*" . $rate);
                $worksheet->setCellValueByColumnAndRow(13, $i, $Alldata[14][$j]);

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

        $worksheet->setCellValue(("K" . $i), ("=SUM(K3" . ":" . "K" . ($i - 1) . ")"));
        $worksheet->setCellValue(("L" . $i), ("=K" . $i) . "*" . "4.326");

        $worksheet->setCellValue(("B" . $i + 2), "核准:");
        $worksheet->setCellValue(("E" . $i + 2), "審核:");
        $worksheet->setCellValue(("K" . $i + 2), "製表:");

        // 下載
        $now = Carbon::now()->format('Ymd');
        $filename = $database . 'Safe Stock' . $now . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(public_path() . "/excel/" . $filename);

        Mail::send('mail/pr_review', [], function ($message) use ($now, $database, $request_user) {
            $message->to($request_user["email"])->subject('PR Review');
            $message->bcc('Vincent6_Yeh@pegatroncorp.com');
            $message->attach(public_path() . '/excel/' . $database . \Lang::get("monthlyPRpageLang.page_name") . $now . '.pdf');
            $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
        });

        \File::delete(public_path() . '/excel/' . $database . 'Safe Stock' . $now . '.xlsx');
    } // sendconsumemail

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
