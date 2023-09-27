<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\客戶別;
use App\Models\機種;
use App\Models\製程;
use App\Models\線別;
use App\Models\領用原因;
use App\Models\退回原因;
use App\Models\入庫原因;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\發料部門;
use App\Models\儲位;
use App\Models\在途量;
use App\Models\Inventory;
use App\Models\不良品Inventory;
use App\Models\Inbound;
use App\Models\ConsumptiveMaterial;
use App\Models\請購單;
use App\Models\月請購_單耗;
use App\Models\月請購_站位;
use App\Models\非月請購;
use App\Models\MPS;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\style\Borders;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mail;
use Mockery\Undefined;
use PhpParser\Node\Expr\Cast\Array_;

class MonthController extends Controller
{
    //料號單耗(查詢)
    public function consumesearch(Request $request)
    {
        $number = $request->input('number');
        $number90 = $request->input('number90');
        $send = $request->input('send');
        $datas = DB::table('consumptive_material')
            ->join('月請購_單耗', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            }) //->wherenull('月請購_單耗.deleted_at')
            ->where('consumptive_material.料號', 'like', $number . '%')
            ->where('consumptive_material.發料部門', 'like', $send . '%')
            ->where('月請購_單耗.料號90', 'like', $number90 . '%')
            ->get();
        $people = DB::table('login')->where('priority', "=", 1)->whereNotNull('email')->get();
        return view('month.consumesearchok')->with(['data' => $datas, 'people' => $people]);
    }

    //站位人力(查詢)
    public function standsearch(Request $request)
    {
        $client = $request->input('client');
        $machine = $request->input('machine');
        $production = $request->input('production');
        $number = $request->input('number');
        $send = $request->input('send');

        $datas = DB::table('consumptive_material')
            ->join('月請購_站位', function ($join) {
                $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
            }) //->wherenull('月請購_站位.deleted_at')
            ->where('consumptive_material.料號', 'like', $number . '%')
            ->where('consumptive_material.發料部門', 'like', $send . '%')
            ->where('月請購_站位.客戶別', 'like', $client . '%')
            ->where('月請購_站位.機種', 'like', $machine . '%')
            ->where('月請購_站位.製程', 'like', $production . '%')
            ->get();
        $people = DB::table('login')->where('priority', "=", 1)->whereNotNull('email')->get();

        return view('month.standsearchok')->with(['data' => $datas, 'people' => $people]);
    }

    //料號單耗(刪除或修改)
    public function consumechangeordelete(Request $request)
    {
        $select = $request->input('select');
        $count = $request->input('count');
        $number = $request->input('number');
        $number90 = $request->input('number90');
        $amount = $request->input('amount');
        $email = $request->input('email');
        $sessemail = $email;
        $name = \Auth::user()->username;
        $database = $request->session()->get('database');
        $database = $database;

        //delete
        if ($select === "刪除") {
            for ($i = 0; $i < $count; $i++) {
                DB::beginTransaction();
                try {
                    月請購_單耗::where('料號', $number[$i])
                        ->where('料號90', $number90[$i])
                        ->delete();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
            }
            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        }
        //change
        if ($select === "更新") {
            for ($i = 0; $i < $count; $i++) {
                DB::beginTransaction();
                try {
                    月請購_單耗::where('料號', $number[$i])
                        ->where('料號90', $number90[$i])
                        ->update([
                            '狀態' => "待畫押", //'畫押工號' => $jobnumber,
                            '畫押信箱' => $email, '單耗' => $amount[$i], '送單時間' => Carbon::now(), '送單人' => \Auth::user()->username,
                        ]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
            }
            self::sendconsumemail($email, $sessemail, $name, $database);

            return \Response::json(['message' => $count, 'status' => 201], /* Status code here default is 200 ok*/);
        }
    }

    //站位人力(刪除或修改)
    public function standchangeordelete(Request $request)
    {
        $select = $request->input('select');
        $Alldata = json_decode($request->input('AllData'));

        $count = $request->input('count');
        $client =  $Alldata[5];
        $machine = $Alldata[6];
        $production = $Alldata[7];
        $number = $Alldata[0];
        $nowpeople = $Alldata[8];
        $nowline = $Alldata[9];
        $nowclass = $Alldata[10];
        $nowdayneed = $Alldata[11];
        $nowchange = $Alldata[12];
        $nextpeople = $Alldata[14];
        $nextline = $Alldata[15];
        $nextclass = $Alldata[16];
        $nextdayneed = $Alldata[17];
        $nextchange = $Alldata[18];
        // $jobnumber = $request->input('jobnumber');
        $email = $request->input('email');
        $sessemail = $email;
        $name = \Auth::user()->username;
        $database = $request->session()->get('database');
        $database = $database;


        //delete
        if ($select == "刪除") {
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {

                    月請購_站位::where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])
                        ->where('製程', $production[$i])
                        ->where('料號', $number[$i])
                        ->delete();
                } //for
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            }
            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        }
        //change
        else if ($select == "更新") {
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {

                    月請購_站位::where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])
                        ->where('製程', $production[$i])
                        ->where('料號', $number[$i])
                        ->update([
                            '狀態' => "待畫押", //'畫押工號' => $jobnumber,
                            '畫押信箱' => $email, '當月站位人數' => $nowpeople[$i], '當月開線數' => $nowline[$i],
                            '當月開班數' => $nowclass[$i], '當月每人每日需求量' => $nowdayneed[$i], '下月站位人數' => $nextpeople[$i],
                            '下月開線數' => $nextline[$i], '下月開班數' => $nextclass[$i], '下月每人每日需求量' => $nextdayneed[$i],
                            '當月每日更換頻率' => $nowchange[$i], '下月每日更換頻率' => $nextchange[$i], '送單時間' => Carbon::now(), '送單人' => \Auth::user()->username,
                        ]);
                } //for
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            }
            self::sendstandmail($email, $sessemail, $name, $database);

            return \Response::json(['message' => $count, 'status' => 201], /* Status code here default is 200 ok*/);
        }
    }

    //料號單耗(新增)
    public function consumenew(Request $request)
    {

        $number = $request->input('number');
        $number90 = $request->input('number90');

        $name = DB::table('consumptive_material')
            ->where('料號', $number)
            // ->where('耗材歸屬', '單耗')
            ->where('月請購', '是')
            ->value('品名');

        $format = DB::table('consumptive_material')->where('料號', $number)
            // ->where('耗材歸屬', '單耗')
            ->where('月請購', '是')
            ->value('規格');

        $unit = DB::table('consumptive_material')->where('料號', $number)
            // ->where('耗材歸屬', '單耗')
            ->where('月請購', '是')
            ->value('單位');

        $lt = DB::table('consumptive_material')->where('料號', $number)
            // ->where('耗材歸屬', '單耗')
            ->where('月請購', '是')
            ->value('LT');

        $lt = round($lt, 3);
        if ($name !== null && $format !== null) {
            return \Response::json([
                'number' => $number,
                'name' => $name, 'format' => $format, 'unit' => $unit, 'lt' => $lt, 'number90' => $number90/*'nowmps' => $nowmps, 'nowday' => $nowday,
                            'nextmps' => $nextmps, 'nextday' => $nextday,*/
            ]/* Status code here default is 200 ok*/);
        } // if
        //沒有料號
        else {
            return \Response::json(['message' => 'no isn'], 420/* Status code here default is 200 ok*/);
        } // else
    }

    //站位人力(新增)
    // public function standnew(Request $request)
    // {
    //     if ($request->input('client') !== null && $request->input('number') !== null) {

    //         if (strlen($request->input('number')) === 12) {
    //             $client = $request->input('client');
    //             $machine = $request->input('machine');
    //             $production = $request->input('production');
    //             $number = $request->input('number');

    //             $name = DB::table('consumptive_material')->where('料號', $number)
    //                 ->where('耗材歸屬', '站位')->where('月請購', '是')->value('品名');

    //             $mpq = DB::table('consumptive_material')->where('料號', $number)
    //                 ->where('耗材歸屬', '站位')->where('月請購', '是')->value('MPQ');

    //             $unit = DB::table('consumptive_material')->where('料號', $number)
    //                 ->where('耗材歸屬', '站位')->where('月請購', '是')->value('單位');

    //             $lt = DB::table('consumptive_material')->where('料號', $number)
    //                 ->where('耗材歸屬', '站位')->where('月請購', '是')->value('LT');

    //             $lt = round($lt, 3);
    //             if ($name !== null && $mpq !== null) {

    //                 return \Response::json([
    //                     'client' => $client, 'machine' => $machine, 'production' => $production, 'number' => $number,
    //                     'name' => $name, 'unit' => $unit, 'lt' => $lt, 'mpq' => $mpq,
    //                 ]/* Status code here default is 200 ok*/);
    //             }
    //             //沒有料號
    //             else {
    //                 return \Response::json(['message' => 'no isn'], 420/* Status code here default is 200 ok*/);
    //             }
    //         }
    //         //料號長度不為12
    //         else {
    //             return \Response::json(['message' => 'isn not 12'], 421/* Status code here default is 200 ok*/);
    //         }
    //     }
    // }


    //提交料號單耗
    public function consumenewsubmit(Request $request)
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

            DB::beginTransaction();

            $record = DB::table('月請購_單耗')->upsert(
                $res_arr_values,
                ['料號', '料號90'],
                ['單耗', '畫押信箱', '狀態', '送單時間', '送單人']
            );

            DB::commit();

            if ($record > 0) {
                self::sendconsumemail($email, $sessemail, $username, $database);
            } // if

            return \Response::json(['record' => $record, 'check' => $check]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch

    } // consumenewsubmit

    //提交站位人力
    public function standnewsubmit(Request $request)
    {
        $count = $request->input('count');
        $row = $request->input('row');
        $record = 0;
        $check = array();
        $database = $request->session()->get('database');
        // $jobnumber = $request->input('jobnumber');
        $email = $request->input('email');
        $sessemail = $email;
        $name = \Auth::user()->username;
        $database = $request->session()->get('database');
        $database = $database;

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $number = $request->input('number')[$i];
                $client = $request->input('client')[$i];
                $machine = $request->input('machine')[$i];
                $production = $request->input('production')[$i];
                $nowpeople = $request->input('nowpeople')[$i];
                $nowline = $request->input('nowline')[$i];
                $nowclass = $request->input('nowclass')[$i];
                $nowuse = $request->input('nowuse')[$i];
                $nowchange = $request->input('nowchange')[$i];
                $nextpeople = $request->input('nextpeople')[$i];
                $nextline = $request->input('nextline')[$i];
                $nextclass = $request->input('nextclass')[$i];
                $nextuse = $request->input('nextuse')[$i];
                $nextchange = $request->input('nextchange')[$i];

                $test = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)
                    ->where('機種', $machine)->where('製程', $production)->value('狀態');


                if ($test === null) {
                    DB::table('月請購_站位')
                        ->insert([
                            '料號' => $number, '客戶別' => $client, '機種' => $machine, '製程' => $production, '當月站位人數' => $nowpeople,
                            '當月開線數' => $nowline, '當月開班數' => $nowclass, '當月每人每日需求量' => $nowuse, '當月每日更換頻率' => $nowchange,
                            '下月站位人數' => $nextpeople, '下月開線數' => $nextline, '下月開班數' => $nextclass, '下月每人每日需求量' => $nextuse,
                            '下月每日更換頻率' => $nextchange, '狀態' => "待畫押"/*'畫押工號' => $jobnumber*/, '畫押信箱' => $email, '送單時間' => Carbon::now(), '送單人' => \Auth::user()->username,
                        ]);
                    $record++;
                    array_push($check, $row[$i]);
                } else if ($test === '待重畫') {
                    月請購_站位::where('客戶別', $client)
                        ->where('機種', $machine)
                        ->where('製程', $production)
                        ->where('料號', $number)
                        ->update([
                            '狀態' => "待畫押", /*'畫押工號' => $jobnumber,*/
                            '畫押信箱' => $email, '當月站位人數' => $nowpeople, '當月開線數' => $nowline,
                            '當月開班數' => $nowclass, '當月每人每日需求量' => $nowuse, '下月站位人數' => $nextpeople,
                            '下月開線數' => $nextline, '下月開班數' => $nextclass, '下月每人每日需求量' => $nextuse,
                            '當月每日更換頻率' => $nowchange, '下月每日更換頻率' => $nextchange, '送單時間' => Carbon::now(), '送單人' => \Auth::user()->username,
                        ]);
                    $record++;
                    array_push($check, $row[$i]);
                } else {
                    continue;
                }
            } //for
            DB::commit();
            self::sendstandmail($email, $sessemail, $name, $database);
            return \Response::json(['record' => $record, 'database' => $database, 'check' => $check]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } //try - catch
    }

    //非月請購添加
    public function notmonthadd(Request $request)
    {
        $number = $request->input('number');
        $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
        if ($name !== NULL) {

            return \Response::json([
                'number' => $number,
                'name' => $name, 'amount' => $request->input('amount')
            ]/* Status code here default is 200 ok*/);
        } else {
            return \Response::json([], 421/* Status code here default is 200 ok*/);
        } // if else
    }

    //非月請購刪除
    public function notmonthdelete(Request $request)
    {
        $count = $request->input('count');
        $number = $request->input('number');
        $record = 0;
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                if (isset($number[$i])) {
                    DB::table('非月請購')
                        ->where('料號', $number[$i])
                        ->delete();
                    $record++;
                }
            } //for
            DB::commit();
            return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    }

    //非月請購提交
    public function notmonthsubmit(Request $request)
    {
        $count = $request->input('count');
        $row = $request->input('row');
        $now = Carbon::now();
        $check = array();
        try {
            $res_arr_values = array();
            for ($i = 0; $i < $count; $i++) {
                $temp = array(
                    "料號" => $request->input('number')[$i],
                    "請購數量" => $request->input('amount')[$i],
                    "上傳時間" => $now,
                    "說明" => $request->input('desc')[$i],
                );

                $res_arr_values[] = $temp;
                array_push($check, $row[$i]);
            } //for

            DB::beginTransaction();

            DB::table('非月請購')->upsert(
                $res_arr_values,
                ['料號'],
                ['請購數量', '上傳時間', '說明']
            );

            DB::commit();
            return \Response::json(['record' => $count, 'check' => $row] /* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        }
    }

    //月請購查詢 or 添加
    public function monthsearchoradd(Request $request)
    {
        //search
        if ($request->has('search')) {

            $datas = DB::table('MPS')
                ->where('料號', 'like', $request->input('number') . '%')
                ->where('料號90', 'like', $request->input('number90') . '%')
                ->get();
            // if else
            return view('month.monthsearchok')->with(['data' => $datas]);
        } // if
        //add
        else if ($request->has('add')) {
            $name = DB::table('consumptive_material')->where('料號', $request->input('number'))
                // ->where('耗材歸屬', '單耗')
                ->where('月請購', '是')
                ->value('品名');
            if ($request->input('number') === null) {
                return back()->withErrors([
                    'number' => trans('validation.required'),
                ]);
            }
            if (strlen($request->input('number')) !== 12) {
                return back()->withErrors([
                    'numberlength' => trans('validation.required'),
                ]);
            }
            if ($name === null) {
                return back()->withErrors([
                    'numberno' => trans('validation.required'),
                ]);
            }
            if ($request->input('number90') === null) {
                return back()->withErrors([
                    'number90' => trans('validation.required'),
                ]);
            }
            if (strlen($request->input('number90')) !== 12) {
                return back()->withErrors([
                    'number90length' => trans('validation.required'),
                ]);
            }
            $nowmps = $request->input('nowmps');
            $nowday = $request->input('nowday');
            $nextmps = $request->input('nextmps');
            $nextday = $request->input('nextday');
            return view('month.monthadd')
                ->with('number90', $request->input('number90'))
                ->with('number', $request->input('number'))
                ->with('nowmps', $nowmps)
                ->with('nowday', $nowday)
                ->with('nextmps', $nextmps)
                ->with('nextday', $nextday);
        } else {
            return redirect(route('month.importmonth'));
        } // if else
    } // monthsearchoradd

    //月請購刪除
    public function monthdelete(Request $request)
    {
        $count = $request->input('count');
        $number = $request->input('number');
        $number90 = $request->input('number90');
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {

                DB::table('MPS')
                    ->where('料號', $number[$i])
                    ->where('料號90', $number90[$i])
                    ->delete();
            } //for
            DB::commit();
            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //catch
    }

    //月請購提交
    public function monthsubmit(Request $request)
    {
        $count = $request->input('count');
        $now = Carbon::now();

        try {
            $res_arr_values = array();
            for ($i = 0; $i < $count; $i++) {
                $temp = array(
                    // "客戶別" => $request->input('client')[$i],
                    // "機種" => $request->input('machine')[$i],
                    // "製程" => $request->input('production')[$i],
                    "料號90" => $request->input('number90')[$i],
                    "下月MPS" => $request->input('nextmps')[$i],
                    "下月生產天數" => $request->input('nextday')[$i],
                    "本月MPS" => $request->input('nowmps')[$i],
                    "本月生產天數" => $request->input('nowday')[$i],
                    "填寫時間" => $now
                );

                $res_arr_values[] = $temp;
            } //for

            DB::beginTransaction();

            DB::table('MPS')->upsert(
                $res_arr_values,
                ['料號', '料號90'],
                ['下月MPS', '下月生產天數', '本月MPS', '本月生產天數', '填寫時間']
            );

            DB::commit();
            return \Response::json(['record' => $count] /* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        } // try - catch
    } // monthsubmit

    //SRM單(查詢)
    public function srmsearch(Request $request)
    {

        $client = $request->input('client');
        $number =  $request->input('number');
        $srm = $request->input('srm');
        $send = $request->input('send');

        $datas = DB::table('consumptive_material')
            ->join('請購單', function ($join) {
                $join->on('請購單.料號', '=', 'consumptive_material.料號')
                    ->whereNull('SXB單號');
            })->where('consumptive_material.料號', 'like', $number . '%')
            ->where('consumptive_material.發料部門', 'like', $send . '%')
            ->where('請購單.客戶', 'like', $client . '%')
            ->where('請購單.SRM單號', 'like', $srm . '%')
            ->get();

        return view('month.srmsearchok')->with(['data' => $datas]);
    } // srmsearch

    //SRM單提交
    public function srmsubmit(Request $request)
    {
        $count = $request->input('count');
        $client = $request->input("client");
        $number = $request->input("number");
        $amount = $request->input("sxbamount");
        $srmnumber = $request->input("srmnumber");
        $sxbnumber = $request->input("sxbnumber");
        $now = Carbon::now();
        $record = 0;
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $test = DB::table('在途量')->where('料號', $number[$i])->where('客戶', $client[$i])->value('請購數量');
                if ($test === null) {
                    DB::table('在途量')
                        ->insert(['客戶' => $client[$i], '料號' => $number[$i], '請購數量' => $amount[$i]]);
                } else {
                    DB::table('在途量')
                        ->where('客戶', $client[$i])
                        ->where('料號', $number[$i])
                        ->update(['請購數量' => $test + $amount[$i]]);
                }
                DB::table('請購單')
                    ->where('客戶', $client[$i])
                    ->where('料號', $number[$i])
                    ->where('SRM單號', $srmnumber[$i])
                    ->update([
                        'SXB單號' => $sxbnumber[$i], '本次請購數量' => $amount[$i], '請購時間' => $now
                    ]);
                $record++;
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } // try catch
        return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
    } // srmsubmit

    //請購單-產生
    public function buylistmake(Request $request)
    {
        $money = $request->input('money');
        $send = $request->input('send');
        $array = array();
        $array1 = array();
        $usd = $request->input('usd');
        $jpy = $request->input('jpy');
        $twd = $request->input('twd');
        $rmb = $request->input('rmb');
        $vnd = $request->input('vnd');
        $idr = $request->input('idr');

        if ($money === null) {
            return back()->withErrors([
                'money' => trans('validation.required'),
            ]);
        } // if

        $datas = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.料號', '=', '月請購_單耗.料號')
                    ->on('MPS.料號90', '=', '月請購_單耗.料號90');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
            })->where('consumptive_material.發料部門', 'like', $send . '%')
            ->where('月請購_單耗.狀態', '=', "已完成")->get();

        foreach ($datas as $data) {
            $test = $data->幣別;
            if ($test !== $money) {
                if ($test === "USD" && $usd === null) {
                    return back()->withErrors([
                        'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "USD" && $usd !== null) {
                    array_push($array, $usd);
                } else if ($test === "RMB" && $rmb === null) {
                    return back()->withErrors([
                        'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "RMB" && $rmb !== null) {
                    array_push($array, $rmb);
                } else if ($test === "JPY" && $jpy === null) {
                    return back()->withErrors([
                        'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "JPY" && $jpy !== null) {
                    array_push($array, $jpy);
                } else if ($test === "TWD" && $twd === null) {
                    return back()->withErrors([
                        'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "TWD" && $twd !== null) {
                    array_push($array, $twd);
                } else if ($test === "VND" && $vnd === null) {
                    return back()->withErrors([
                        'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "VND" && $vnd !== null) {
                    array_push($array, $vnd);
                } else if ($test === "IDR" && $idr === null) {
                    return back()->withErrors([
                        'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "IDR" && $idr !== null) {
                    array_push($array, $idr);
                }
            } else {
                array_push($array, 1);
                continue;
            }
        } // for each
        $datas2 = DB::table('非月請購')
            ->join('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', '非月請購.料號');
            })->where('consumptive_material.發料部門', 'like', $send . '%')
            ->whereNull('非月請購.SXB單號')->get();
        foreach ($datas2 as $data) {
            $test = $data->幣別;
            if ($test !== $money) {
                if ($test === "USD" && $usd === null) {
                    return back()->withErrors([
                        'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "USD" && $usd !== null) {
                    array_push($array1, $usd);
                } else if ($test === "RMB" && $rmb === null) {
                    return back()->withErrors([
                        'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "RMB" && $rmb !== null) {
                    array_push($array1, $rmb);
                } else if ($test === "JPY" && $jpy === null) {
                    return back()->withErrors([
                        'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "JPY" && $jpy !== null) {
                    array_push($array1, $jpy);
                } else if ($test === "TWD" && $twd === null) {
                    return back()->withErrors([
                        'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "TWD" && $twd !== null) {
                    array_push($array1, $twd);
                } else if ($test === "VND" && $vnd === null) {
                    return back()->withErrors([
                        'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "VND" && $vnd !== null) {
                    array_push($array1, $vnd);
                } else if ($test === "IDR" && $idr === null) {
                    return back()->withErrors([
                        'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                    ]);
                } else if ($test === "IDR" && $idr !== null) {
                    array_push($array1, $idr);
                } // if else if
            } else {
                array_push($array1, 1);
                continue;
            } // if else
        } // for each

        return view('month.buylistmakeok')->with(['data1' => $datas])->with(['data2' => $datas2])
            ->with(['rate1' => $array])->with(['rate2' => $array1]);
    }

    //請購單-提交
    public function buylistsubmit(Request $request)
    {
        $now = Carbon::now();
        $count = $request->input('count');
        $Alldata = json_decode($request->input('AllData'));
        $record = 0;
        $srm = $Alldata[0];
        $client = $Alldata[1];
        $number = $Alldata[2];
        $name = $Alldata[3];
        $format = $Alldata[4];
        $price = $Alldata[5];
        $money = $Alldata[6];
        $nowneed = $Alldata[7];
        $nextneed = $Alldata[8];
        $stock = $Alldata[9];
        $amount = $Alldata[10];
        $buyamount = $Alldata[11];
        $buymoney = $Alldata[12];
        $rate = $Alldata[13];
        $moq = $Alldata[14];
        $check = $request->input('check');

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                if ($check[$i] !== '0') {
                    DB::table('請購單')
                        ->insert([
                            'SRM單號' => $srm[$i], '客戶' => $client[$i], '料號' => $number[$i], '品名' => $name[$i], 'MOQ' => $moq[$i], '下月需求' => $nextneed[$i], '當月需求' => $nowneed[$i], '規格' => $format[$i], '單價' => $price[$i], '幣別' => $money[$i], '匯率' => $rate[$i], '在途數量' => $amount[$i], '現有庫存' => $stock[$i], '本次請購數量' => $buyamount[$i], '請購金額' => $buymoney[$i], '請購時間' => $now
                        ]);
                    $record++;
                }
            }
            DB::commit();
            return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        }
    }

    //單耗上傳
    public function uploadconsume(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
        ]);
        $path = $request->file('select_file')->getRealPath();

        $testAgainstFormats = [
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS,
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX,
        ];

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path, 1, $testAgainstFormats);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $people = DB::table('login')->where('priority', "=", 1)->whereNotNull('email')->get();
        unset($sheetData[0]);
        return view('month.uploadconsume')->with(['data' => $sheetData])->with(['people' => $people]);
    }

    //站位上傳
    public function uploadstand(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
        ]);
        $path = $request->file('select_file')->getRealPath();

        $testAgainstFormats = [
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS,
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX,
        ];

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path, 1, $testAgainstFormats);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $people = DB::table('login')->where('priority', "=", 1)->whereNotNull('email')->get();
        unset($sheetData[0]);
        return view('month.uploadstand')->with(['data' => $sheetData])->with(['people' => $people]);
    }

    //非月請購上傳
    public function uploadnotmonth(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
        ]);
        $path = $request->file('select_file')->getRealPath();

        $testAgainstFormats = [
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS,
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX,
        ];

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path, 1, $testAgainstFormats);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        unset($sheetData[0]);
        return view('month.uploadnotmonth')->with(['data' => $sheetData]);
    }

    //月請購上傳
    public function uploadmonth(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
        ]);
        $path = $request->file('select_file')->getRealPath();

        $testAgainstFormats = [
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS,
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX,
        ];

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path, 1, $testAgainstFormats);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        unset($sheetData[0]);
        return view('month.uploadmonth')->with(['data' => $sheetData]);
    } // uploadmonth

    // Get OA Account Info from 畫押人員/單位工程師
    public function testconsumeOALogin(Request $request)
    {
        if (request()->filled('r') && request()->filled('u') && request()->filled('d')) {
            $email = request()->query('r');
            $username = request()->query('u');
            $database = request()->query('d');
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());
            $lang = request()->query('l');
            request()->getSession()->put('locale', $lang);
            \App::setLocale($lang);

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));

            // update the info from SSO POST
            $affected = DB::table('login')
                ->where('username', '=', $request->work_id)
                ->update([
                    '姓名' => $request->user_name,
                    '部門' => $request->dept_name,
                    'email' => $request->office_mail,
                    'last_login_time' => $datetime
                ]);

            DB::table('月請購_單耗')
                ->where('狀態', '=', "待畫押")
                ->where("畫押信箱", '=', $email)
                ->update([
                    '畫押信箱' => $request->office_mail
                ]);

            if ($affected < 1) { // if the 畫押人員 is not in DB yet
                DB::table('login')
                    ->insert([
                        'username' => $request->work_id, 'password' => "123456", 'priority' => 69,
                        '姓名' => $request->user_name, '部門' => $request->dept_name, 'avatarChoice' => 3,
                        'email' => $request->office_mail, 'last_login_time' => $datetime
                    ]);
            } // if
            $name = DB::table('login')->where('username', $username)->value('姓名');

            return view('month.testconsume')->with(['data' => \App\Models\月請購_單耗::cursor()->where('狀態', "待畫押")->where("畫押信箱", $request->office_mail)])
                ->with(['email' => $request->office_mail])->with(['username' => $name])->with(['database' => $database]);
        } else {
            return abort(404);
        } // if else
    } // testconsumeOALogin

    //單耗畫押提交
    public function testconsume(Request $request)
    {

        $now = Carbon::now();
        $count = $request->input('count');
        $sender = $request->input('sender');
        $Alldata = json_decode($request->input('AllData'));
        $database = $request->input('database');
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
        \DB::purge(env("DB_CONNECTION"));

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $number = $Alldata[0][$i];
                $number90 = $Alldata[5][$i];
                $check = $Alldata[3][$i];

                if ($check) {
                    月請購_單耗::where('料號', $number)
                        ->where('料號90', $number90)
                        ->update([
                            '狀態' => "已完成", '畫押時間' => $now,
                        ]);
                } else {
                    月請購_單耗::where('料號', $number)
                        ->where('料號90', $number90)
                        ->update([
                            '狀態' => "待重畫", '畫押時間' => $now,
                        ]);
                } // if else
            } //for
            DB::commit();
            self::sendcheckconsume($Alldata, $count, $sender);

            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //try-catch

    }

    // Get OA Account Info from 畫押人員/單位工程師
    public function teststandOALogin(Request $request)
    {
        if (request()->filled('r') && request()->filled('u') && request()->filled('d')) {
            $email = request()->query('r');
            $username = request()->query('u');
            $database = request()->query('d');
            $lang = request()->query('l');
            request()->getSession()->put('locale', $lang);
            \App::setLocale($lang);

            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));

            // update the info from SSO POST
            $affected = DB::table('login')
                ->where('username', '=', $request->work_id)
                ->update([
                    '姓名' => $request->user_name,
                    '部門' => $request->dept_name,
                    'email' => $request->office_mail,
                    'last_login_time' => $datetime
                ]);

            DB::table('月請購_站位')
                ->where('狀態', '=', "待畫押")
                ->where("畫押信箱", '=', $email)
                ->update([
                    '畫押信箱' => $request->office_mail
                ]);

            if ($affected < 1) { // if the 畫押人員 is not in DB yet
                DB::table('login')
                    ->insert([
                        'username' => $request->work_id, 'password' => "123456", 'priority' => 69,
                        '姓名' => $request->user_name, '部門' => $request->dept_name, 'avatarChoice' => 3,
                        'email' => $request->office_mail, 'last_login_time' => $datetime
                    ]);
            } // if
            $name = DB::table('login')->where('username', $username)->value('姓名');

            return view('month.teststand')->with(['data' => \App\Models\月請購_站位::cursor()->where('狀態', "待畫押")->where("畫押信箱", $request->office_mail)])
                ->with(['email' => $request->office_mail])->with(['username' => $name])->with(['database' => $database]);
        } else {
            return abort(404);
        } // if else
    } // teststandOALogin

    //站位畫押提交
    public function teststand(Request $request)
    {
        $now = Carbon::now();
        $count = $request->input('count');
        $sender = $request->input('sender');
        $Alldata = json_decode($request->input('AllData'));
        $database = $request->input('database');
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
        \DB::purge(env("DB_CONNECTION"));

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {

                $client = $Alldata[2][$i];
                $machine = $Alldata[3][$i];
                $production = $Alldata[4][$i];
                $number = $Alldata[0][$i];

                $check = $Alldata[15][$i];
                if ($check) {
                    月請購_站位::where('客戶別', $client)
                        ->where('機種', $machine)
                        ->where('製程', $production)
                        ->where('料號', $number)
                        ->update([
                            '狀態' => "已完成", '畫押時間' => $now,
                        ]);
                } else {
                    月請購_站位::where('客戶別', $client)
                        ->where('機種', $machine)
                        ->where('製程', $production)
                        ->where('料號', $number)
                        ->update([
                            '狀態' => "待重畫", '畫押時間' => $now,
                        ]);
                }
            } //for
            DB::commit();
            self::sendcheckstand($Alldata, $count, $sender);

            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //try-catch
    }

    //站位人力查詢下載
    public function standdownload(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

        $worksheet = $spreadsheet->getActiveSheet();

        $title = $request->input('title');
        $count = $request->input('count');
        $Alldata = json_decode($request->input('AllData'));
        // $stringValueBinder = new StringValueBinder();
        // $stringValueBinder->setNullConversion(false)->setFormulaConversion(false);
        // \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder($stringValueBinder); // make it so it doesnt covert 儲位 to weird number format


        //填寫表頭
        for ($i = 0; $i < 23; $i++) {
            $worksheet->setCellValueByColumnAndRow($i + 1, 1, $title[$i]);
        }

        // 下載
        for ($i = 0; $i < 23; $i++) {
            for ($j = 0; $j < $count; $j++) {

                $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$i][$j]);
            }
        }

        $now = Carbon::now()->format('YmdHis');

        $filename = rawurlencode($request->input('titlename')) . $now . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
        header('Cache-Control: max-age=0');

        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Content-Disposition: attachment;filename="' . $filename . '"', 'Cache-Control: max-age=0'];
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $callback = function () use ($writer) {
            $file = fopen('php://output', 'r');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    //單耗查詢下載
    public function consumedownload(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

        $worksheet = $spreadsheet->getActiveSheet();

        $title = $request->input('title');
        $count = $request->input('count');
        $titlecount = $request->input('titlecount');
        $Alldata = json_decode($request->input('AllData'));
        // $stringValueBinder = new StringValueBinder();
        // $stringValueBinder->setNullConversion(false)->setFormulaConversion(false);
        // \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder($stringValueBinder); // make it so it doesnt covert 儲位 to weird number format


        //填寫表頭
        for ($i = 0; $i < $titlecount; $i++) {
            $worksheet->setCellValueByColumnAndRow($i + 1, 1, $title[$i]);
        }

        // 下載
        for ($i = 0; $i < $titlecount; $i++) {
            for ($j = 0; $j < $count; $j++) {

                $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$i][$j]);
            }
        }

        $now = Carbon::now()->format('YmdHis');

        $filename = rawurlencode($request->input('titlename')) . $now . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
        header('Cache-Control: max-age=0');

        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Content-Disposition: attachment;filename="' . $filename . '"', 'Cache-Control: max-age=0'];
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $callback = function () use ($writer) {
            $file = fopen('php://output', 'r');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    //download data
    public function download(Request $request)
    {
        // Set value binder so that "100%" won't be inserted as string
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $worksheet = $spreadsheet->getActiveSheet();
        $titlecount = $request->input('titlecount');
        $Alldata = DB::table('非月請購')
            ->join('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', '非月請購.料號');
            })->whereNull('非月請購.SXB單號')->get();
        $count = count($Alldata);

        //填寫表頭
        for ($i = 0; $i < $titlecount; $i++) {
            $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title')[$i]);
        } // for

        //填寫內容
        for ($i = 0; $i < $titlecount; $i++) {
            $string = $request->input('titlecol')[$i];
            for ($j = 0; $j < $count; $j++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$j]->$string);
            }
        }

        // dd($endOfRow . "," . $endOfCol); // test

        // $worksheet->setCellValue("A" . ($endOfRow + 3), "合計：");
        // $worksheet->mergeCells("A" . ($endOfRow + 3) . ":" . $alphabet[$endOfCol - 4] . ($endOfRow + 3)); // for the SUM row
        // $worksheet->setCellValue($alphabet[$endOfCol] . ($endOfRow + 3), "=SUM(" . $alphabet[$endOfCol] . "2:" . $alphabet[$endOfCol] . ($endOfRow + 2) . ")");
        // $worksheet->setCellValue($alphabet[$endOfCol - 1] . ($endOfRow + 3), "=SUM(" . $alphabet[$endOfCol - 1] . "2:" . $alphabet[$endOfCol - 1] . ($endOfRow + 2) . ")");
        // $worksheet->getStyle($alphabet[$endOfCol] . "2:" . $alphabet[$endOfCol] . ($endOfRow + 3))->getNumberFormat()->setFormatCode('0%');
        // $worksheet->setCellValue($alphabet[$endOfCol - 2] . ($endOfRow + 3), "=SUM(" . $alphabet[$endOfCol - 2] . "2:" . $alphabet[$endOfCol - 2] . ($endOfRow + 2) . ")");
        // $worksheet->setCellValue($alphabet[$endOfCol - 3] . ($endOfRow + 3), "=SUM(" . $alphabet[$endOfCol - 3] . "2:" . $alphabet[$endOfCol - 3] . ($endOfRow + 2) . ")");
        // $worksheet->getStyle($alphabet[$endOfCol - 2] . "2:" . $alphabet[$endOfCol - 2] . ($endOfRow + 3))->getNumberFormat()->setFormatCode('0%');

        // 下載
        $now = Carbon::now()->format('YmdHis');
        $titlename = $request->input('titlename');
        $filename = rawurlencode($titlename) . $now . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    } // export excel function

    //請購單下載
    public function buylistdownload(Request $request)
    {

            $spreadsheet = new Spreadsheet();
            // $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            // $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Microsoft JhengHei');
            $worksheet = $spreadsheet->getActiveSheet();

        $count = $request->input('count');
        $Alldata = json_decode($request->input('AllData'));
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
        $worksheet->setCellValue("A1", "MOQ");
        $worksheet->mergeCells("A1:M1"); // for the SUM row
        $database = $request->session()->get('database');
        $title = explode(" Consumables management", $database)[0] . "廠";
        $year = date('Y/', strtotime("last day of 1 month"));
        $month = date('m/', strtotime("last day of 1 month"));
        $lastday = date('t', strtotime("last day of 1 month"));
        $title  = $title . $year . $month . "01" . "~" . $month . $lastday . "號耗材購買明細";
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
                    $worksheet->setCellValue(("L" . $i), ("=K" . $i) . "*" . "4.326");
                    $worksheet->setCellValueByColumnAndRow(13, $i, $Alldata[14][$j]);

                $i++;
            } else {
                continue;
            }
        }
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
            $now = Carbon::now()->format('YmdHis');
            $titlename = $request->input('titlename');
            // $filename = rawurlencode($titlename) . $now . '.xlsx';
            $filename = rawurlencode($titlename) . $now . '.pdf';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
            header('Cache-Control: max-age=0');

            // $writer->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Content-Disposition: attachment;filename="' . $filename . '"', 'Cache-Control: max-age=0'];
            // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
            $writer->save('php://output');
            $callback = function () use ($writer) {
                $file = fopen('php://output', 'r');
                fclose($file);
            };


        return response()->stream($callback, 200, $headers);
    }

    //send consume mail
    public static function sendconsumemail($email, $sessemail, $username, $database)
    {
        $dename = DB::table('login')->where('username', $username)->value('姓名');
        $data = array('email' => urlencode($sessemail), 'username' => urlencode($username), 'database' => urlencode($database), 'name' => urlencode($dename));

        Mail::send('mail/consumecheck', $data, function ($message) use ($email) {

            $message->to($email, 'Tutorials Point')->subject('請確認單耗資料');
            $message->bcc('vincent6_yeh@pegatroncorp.com');
            $message->bcc('tony_tseng@pegatroncorp.com');
            // $message->attach(public_path() . '/download/LineExample.xlsx');
            $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
        });
    }

    //send stand mail
    public static function sendstandmail($email, $sessemail, $name, $database)
    {
        $dename = DB::table('login')->where('username', $name)->value('姓名');
        $data = array('email' => urlencode($sessemail), 'username' => urlencode($name), 'database' => urlencode($database), 'name' => urlencode($dename));

        Mail::send('mail/standcheck', $data,  function ($message) use ($email) {
            $message->to($email, 'Tutorials Point')->subject('請確認站位資料');
            $message->bcc('Vincent6_Yeh@pegatroncorp.com');
            $message->bcc('Tony_Tseng@pegatroncorp.com');
            $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
        });
    }

    //send check consume mail
    public static function sendcheckconsume($alldata, $count, $sender)
    {
        $data = array('datas' => $alldata, 'count' => $count);

        Mail::send('mail/markconsume', $data, function ($message) use ($sender) {
            // $email = 't22923200@gmail.com';
            $email = DB::table('login')->where('姓名', $sender)->value('email');
            if ($email !== null) {
                // dd($email);
                $message->to($email, 'Tutorials Point')->subject('RE:請確認單耗資料');
                $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                $message->bcc('Tony_Tseng@pegatroncorp.com');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            } else {
                $message->to('Tony_Tseng@pegatroncorp.com')->subject('無信箱');
                $message->to('Vincent6_Yeh@pegatroncorp.com')->subject('無信箱');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            }
        });
    }


    //send check stand mail
    public static function sendcheckstand($alldata, $count, $sender)
    {
        $data = array('datas' => $alldata, 'count' => $count);

        Mail::send('mail/markstand', $data, function ($message) use ($sender) {
            // $email = 't22923200@gmail.com';
            $email = DB::table('login')->where('姓名', $sender)->value('email');
            if ($email !== null) {

                $message->to($email, 'Tutorials Point')->subject('RE:請確認站位資料');
                $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                $message->bcc('Tony_Tseng@pegatroncorp.com');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            } else {
                $message->to('Tony_Tseng@pegatroncorp.com')->subject('無信箱');
                $message->to('Vincent6_Yeh@pegatroncorp.com')->subject('無信箱');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            }
        });
    }


    //load re-check consume
    public function loadconsume(Request $request)
    {
        $datas = DB::table('consumptive_material')
            ->join('月請購_單耗', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->where('狀態', '待重畫')->get();
        return \Response::json(['datas' => $datas]/* Status code here default is 200 ok*/);
    }



    //load re-check stand
    public function loadstand(Request $request)
    {
        $datas = DB::table('consumptive_material')
            ->join('月請購_站位', function ($join) {
                $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
            })
            ->where('狀態', '待重畫')->get();
        return \Response::json(['datas' => $datas]/* Status code here default is 200 ok*/);
    }
}
