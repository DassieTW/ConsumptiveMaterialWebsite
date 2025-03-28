<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Date;
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
        // dd($dbName); // test
        $yearTag = $request->input('Year');
        try {
            $result_buylist = DB::table('請購單')
                ->leftjoin('consumptive_material', function ($join) {
                    $join->on('consumptive_material.料號', '=', '請購單.料號');
                })
                ->select(
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.單位',
                    '請購單.MOQ',
                    '請購單.SRM單號',
                    '請購單.當月需求',
                    '請購單.下月需求',
                    '請購單.匯率',
                    '請購單.單價',
                    '請購單.在途數量',
                    '請購單.幣別',
                    '請購單.本次請購數量',
                    '請購單.現有庫存',
                    '請購單.規格',
                    '請購單.請購時間',
                    '請購單.請購金額',
                )
                ->where('SRM單號', '=', '已完成')
                ->whereYear('請購時間', $yearTag)
                ->get();

            $result_outbound = DB::table('outbound')
                ->leftjoin('consumptive_material', function ($join) {
                    $join->on('consumptive_material.料號', '=', 'outbound.料號');
                })
                ->select(
                    'consumptive_material.料號',
                    'consumptive_material.品名',
                    'consumptive_material.單價',
                    'consumptive_material.幣別',
                    'consumptive_material.單位',
                    'outbound.領用原因',
                    'outbound.實際領用數量',
                    'outbound.領料單號',
                    'outbound.領料人員',
                    'outbound.出庫時間',
                    'outbound.備註',
                )
                ->whereYear('outbound.出庫時間', $yearTag)
                ->get();

            // dd($result_outbound); // test
            return \Response::json(['buylist' => $result_buylist, 'outbound' => $result_outbound], 200 /* Status code here default is 200 ok*/);
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
        
    } // sendAlertMail

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
} // AlertController