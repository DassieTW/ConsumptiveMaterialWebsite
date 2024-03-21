<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Exception;
use Sentry\Util\JSON;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\style\Borders;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mail;

use function Swoole\Coroutine\Http\get;

class AlertController extends Controller
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

    public function showYearlyDiff(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $yearTag = $request->input('Year');
        try {
            $result_buylist = DB::table('請購單')
                ->where('SRM單號', '=', '已完成')
                ->whereYear('請購時間', $yearTag)
                ->get();

            $result_inbound = DB::table('inbound')
                ->whereYear('入庫時間', $yearTag)
                ->get();

            // dd($result_inbound); // test
            return \Response::json(['buylist' => $result_buylist, 'inbound' => $result_inbound], 200 /* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            dd($e);
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } // try - catch
    } // showYearlyDiff

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //寄差異警報信
    public static function sendAlertMail(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test

        $spreadsheet = new Spreadsheet();
        // $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Microsoft JhengHei');
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
        // dd($request_user); // test
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
                $worksheet->setCellValueByColumnAndRow(1, $i, $i - 2); // A
                $worksheet->setCellValueByColumnAndRow(2, $i, $PN[$j]); // B
                $worksheet->setCellValueByColumnAndRow(3, $i, $pName[$j]); // C
                $worksheet->setCellValueByColumnAndRow(4, $i, $Spec[$j]); // D
                $worksheet->setCellValueByColumnAndRow(5, $i, strval($Unit_price[$j]) . " " . strtoupper($currency_name[$j])); // E
                $worksheet->setCellValueByColumnAndRow(6, $i, strval($nowNeed[$j])); // F
                $worksheet->setCellValueByColumnAndRow(7, $i, strval($nextNeed[$j])); // G
                $worksheet->setCellValueByColumnAndRow(8, $i, strval($Stock[$j])); // H
                $worksheet->setCellValueByColumnAndRow(9, $i, strval($in_Transit[$j])); // I
                $worksheet->setCellValueByColumnAndRow(10, $i, strval($ReqAmount[$j])); // J
                $worksheet->setCellValueByColumnAndRow(11, $i, strval($total_price1[$j]) . " " . strtoupper($currency_name[$j])); // K
                $worksheet->setCellValueByColumnAndRow(12, $i, strval($total_price2[$j])); // L
                $worksheet->setCellValueByColumnAndRow(13, $i, strval($MOQ[$j])); // M

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
        $worksheet->setCellValue(("K" . $i + 2), "製表: " . $request_user['姓名'] . " " . date('Y-m-d'));

        $worksheet->getStyle('A2:M2')->getAlignment()->setHorizontal('center');
        $worksheet->getStyle('A3:A' . ($i + 1))->getAlignment()->setHorizontal('center');
        $worksheet->getStyle('E3:M' . ($i + 1))->getAlignment()->setHorizontal('right');

        foreach ($worksheet->getColumnIterator() as $column) {
            $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        } // foreach

        $now = Carbon::now()->format('Ymd');
        $filename = $title . \Lang::get("monthlyPRpageLang.page_name") . "_" . $now . '.pdf';

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
        $writer->save(public_path() . "/excel/" . $filename);

        // Data for 內文 View
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
        Mail::send(
            'mail/diff_review',
            $data,
            function ($message) use ($filename, $request_user) {
                $message->to($request_user["email"])->subject('Consumables Management PR Review');
                $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                $message->attach(public_path() . '/excel/' . $filename);
                $message->from('CM_No-Reply@pegatroncorp.com', 'Consumables_Management_No-Reply');
            }
        );

        \File::delete(public_path() . '/excel/' . $filename);
    } // sendAlertMail

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
} // AlertController
