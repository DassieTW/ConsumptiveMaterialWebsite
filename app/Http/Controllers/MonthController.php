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
use App\Models\人員信息;
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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mail;

class MonthController extends Controller
{
    //料號單耗(查詢)
    public function consumesearch(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $number = $request->input('number');
            $send = $request->input('send');
            if ($number !== null) {
                $datas = DB::table('consumptive_material')
                    ->join('月請購_單耗', function ($join) {
                        $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
                    }) //->wherenull('月請購_單耗.deleted_at')
                    ->where('consumptive_material.料號', 'like', $number . '%')
                    ->where('狀態', '已完成')->get();
            } else {
                $datas = DB::table('consumptive_material')
                    ->join('月請購_單耗', function ($join) {
                        $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
                    }) //->wherenull('月請購_單耗.deleted_at')
                    ->where('狀態', '已完成')->get();
            }
            //all empty
            if ($client === null && $machine === null && $production === null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas]);
            }
            //select client
            else if ($client !== null && $machine === null && $production === null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas->where('客戶別', $client)]);
            }
            //select machine
            else if ($client === null && $machine !== null && $production === null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas->where('機種', $machine)]);
            }
            //select production
            else if ($client === null && $machine === null && $production !== null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas->where('製程', $production)]);
            }
            //input material number
            else if ($client === null && $machine === null && $production === null && $number !== null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas]);
            }
            //select send
            else if ($client === null && $machine === null && $production === null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas->where('發料部門', $send)]);
            }
            //select client and machine
            else if ($client !== null && $machine !== null && $production === null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('客戶別', $client)->where('機種', $machine)]);
            }
            //select client and production
            else if ($client !== null && $machine === null && $production !== null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('客戶別', $client)->where('製程', $production)]);
            }
            //select client and number
            else if ($client !== null && $machine === null && $production === null && $number !== null && $send === null) {

                return view('month.consumesearchok')->with(['data' => $datas->where('客戶別', $client)]);
            }
            //select client and send
            else if ($client !== null && $machine === null && $production === null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('客戶別', $client)->where('發料部門', $send)]);
            }
            //select machine and production
            else if ($client === null && $machine !== null && $production !== null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)]);
            }
            //select machine and number
            else if ($client === null && $machine !== null && $production === null && $number !== null && $send === null) {

                return view('month.consumesearchok')->with(['data' => $datas->where('機種', $number)]);
            }
            //select machine and send
            else if ($client === null && $machine !== null && $production === null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('發料部門', $send)]);
            }
            //select production and number
            else if ($client === null && $machine === null && $production !== null && $number !== null && $send === null) {

                return view('month.consumesearchok')->with(['data' => $datas->where('製程', $production)]);
            }
            //select production and send
            else if ($client === null && $machine === null && $production !== null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)]);
            }
            //select number and send
            else if ($client === null && $machine === null && $production === null && $number !== null && $send !== null) {

                return view('month.consumesearchok')->with(['data' => $datas->where('發料部門', $send)]);
            }
            //select client and machine and production
            else if ($client !== null && $machine !== null && $production !== null && $number === null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)
                    ->where('客戶別', $client)]);
            }
            //select client and machine and number
            else if ($client !== null && $machine !== null && $production === null && $number !== null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas->where('客戶別', $client)
                    ->where('機種', $machine)]);
            }
            //select client and machine and send
            else if ($client !== null && $machine !== null && $production === null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('發料部門', $send)
                    ->where('客戶別', $client)]);
            }
            //select client and production and number
            else if ($client !== null && $machine === null && $production !== null && $number !== null && $send === null) {
                return view('month.consumesearchok')->with(['data' => $datas->where('客戶別', $client)
                    ->where('製程', $production)]);
            }
            //select client and production and send
            else if ($client !== null && $machine === null && $production !== null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)
                    ->where('客戶別', $client)]);
            }
            //select client and number and send
            else if ($client !== null && $machine === null && $production === null && $number !== null && $send !== null) {

                return view('month.consumesearchok')->with(['data' => $datas->where('發料部門', $send)
                    ->where('客戶別', $client)]);
            }
            //select machine and production and number
            else if ($client === null && $machine !== null && $production !== null && $number !== null && $send === null) {

                return view('month.consumesearchok')->with(['data' => $datas->where('機種', $machine)
                    ->where('製程', $production)]);
            }
            //select machine and production and send
            else if ($client === null && $machine !== null && $production !== null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('機種', $machine)->where('製程', $production)]);
            }
            //select machine and number and send
            else if ($client === null && $machine !== null && $production === null && $number !== null && $send !== null) {

                return view('month.consumesearchok')->with(['data' => $datas->where('機種', $machine)
                    ->where('發料部門', $send)]);
            }
            //select production and number and send
            else if ($client === null && $machine === null && $production !== null && $number !== null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)]);
            }
            //select client and machine and production and number
            else if ($client !== null && $machine !== null && $production !== null && $number !== null && $send === null) {

                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select client and machine and production and send
            else if ($client !== null && $machine !== null && $production !== null && $number === null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select client and machine and number and send
            else if ($client !== null && $machine !== null && $production === null && $number !== null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select client and production and number and send
            else if ($client !== null && $machine === null && $production !== null && $number !== null && $send !== null) {
                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select machine and production and number and send
            else if ($client === null && $machine !== null && $production !== null && $number !== null && $send !== null) {

                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)->where('機種', $machine)]);
            }
            //select all
            else if ($client !== null && $machine !== null && $production !== null && $number !== null && $send !== null) {

                return view('month.consumesearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)
                    ->where('客戶別', $client)->where('發料部門', $send)]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //站位人力(查詢)
    public function standsearch(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $number = $request->input('number');
            $send = $request->input('send');
            if ($number !== null) {
                $datas = DB::table('consumptive_material')
                    ->join('月請購_站位', function ($join) {
                        $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
                    }) //->wherenull('月請購_站位.deleted_at')
                    ->where('consumptive_material.料號', 'like', $number . '%')
                    ->where('狀態', '已完成')->get();
            } else {
                $datas = DB::table('consumptive_material')
                    ->join('月請購_站位', function ($join) {
                        $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
                    }) //->wherenull('月請購_站位.deleted_at')
                    ->where('狀態', '已完成')->get();
            }

            //all empty
            if ($client === null && $machine === null && $production === null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas]);
            }
            //select client
            else if ($client !== null && $machine === null && $production === null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('客戶別', $client)]);
            }
            //select machine
            else if ($client === null && $machine !== null && $production === null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('機種', $machine)]);
            }
            //select production
            else if ($client === null && $machine === null && $production !== null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('製程', $production)]);
            }
            //input material number
            else if ($client === null && $machine === null && $production === null && $number !== null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas]);
            }
            //select send
            else if ($client === null && $machine === null && $production === null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas->where('發料部門', $send)]);
            }
            //select client and machine
            else if ($client !== null && $machine !== null && $production === null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('客戶別', $client)->where('機種', $machine)]);
            }
            //select client and production
            else if ($client !== null && $machine === null && $production !== null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('客戶別', $client)->where('製程', $production)]);
            }
            //select client and number
            else if ($client !== null && $machine === null && $production === null && $number !== null && $send === null) {

                return view('month.standsearchok')->with(['data' => $datas->where('客戶別', $client)]);
            }
            //select client and send
            else if ($client !== null && $machine === null && $production === null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas->where('客戶別', $client)->where('發料部門', $send)]);
            }
            //select machine and production
            else if ($client === null && $machine !== null && $production !== null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('機種', $machine)->where('製程', $production)]);
            }
            //select machine and number
            else if ($client === null && $machine !== null && $production === null && $number !== null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('機種', $number)]);
            }
            //select machine and send
            else if ($client === null && $machine !== null && $production === null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas->where('機種', $machine)->where('發料部門', $send)]);
            }
            //select production and number
            else if ($client === null && $machine === null && $production !== null && $number !== null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('製程', $production)]);
            }
            //select production and send
            else if ($client === null && $machine === null && $production !== null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas->where('製程', $production)->where('發料部門', $send)]);
            }
            //select number and send
            else if ($client === null && $machine === null && $production === null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas->where('發料部門', $send)]);
            }
            //select client and machine and production
            else if ($client !== null && $machine !== null && $production !== null && $number === null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)
                    ->where('客戶別', $client)]);
            }
            //select client and machine and number
            else if ($client !== null && $machine !== null && $production === null && $number !== null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('客戶別', $client)
                    ->where('機種', $machine)]);
            }
            //select client and machine and send
            else if ($client !== null && $machine !== null && $production === null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('發料部門', $send)
                    ->where('客戶別', $client)]);
            }
            //select client and production and number
            else if ($client !== null && $machine === null && $production !== null && $number !== null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas->where('客戶別', $client)
                    ->where('製程', $production)]);
            }
            //select client and production and send
            else if ($client !== null && $machine === null && $production !== null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)
                    ->where('客戶別', $client)]);
            }
            //select client and number and send
            else if ($client !== null && $machine === null && $production === null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select machine and production and number
            else if ($client === null && $machine !== null && $production !== null && $number !== null && $send === null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)]);
            }
            //select machine and production and send
            else if ($client === null && $machine !== null && $production !== null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('機種', $machine)->where('製程', $production)]);
            }
            //select machine and number and send
            else if ($client === null && $machine !== null && $production === null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('發料部門', $send)]);
            }
            //select production and number and send
            else if ($client === null && $machine === null && $production !== null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)]);
            }
            //select client and machine and production and number
            else if ($client !== null && $machine !== null && $production !== null && $number !== null && $send === null) {

                return view('month.standsearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('機種', $machine)->where('客戶別', $client)]);
            }
            //select client and machine and production and send
            else if ($client !== null && $machine !== null && $production !== null && $number === null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select client and machine and number and send
            else if ($client !== null && $machine !== null && $production === null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select client and production and number and send
            else if ($client !== null && $machine === null && $production !== null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)->where('客戶別', $client)]);
            }
            //select machine and production and number and send
            else if ($client === null && $machine !== null && $production !== null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('製程', $production)->where('發料部門', $send)->where('機種', $machine)]);
            }
            //select all
            else if ($client !== null && $machine !== null && $production !== null && $number !== null && $send !== null) {
                return view('month.standsearchok')->with(['data' => $datas
                    ->where('機種', $machine)->where('製程', $production)
                    ->where('客戶別', $client)->where('發料部門', $send)]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //料號單耗(刪除或修改)
    public function consumechangeordelete(Request $request)
    {
        if (Session::has('username')) {
            $select = $request->input('select');
            $count = $request->input('count');
            $client = $request->input('client');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $number = $request->input('number');
            $amount = $request->input('amount');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $sessemail = \Crypt::encrypt($email);
            $name = \Crypt::encrypt(\Auth::user()->username);
            $database = $request->session()->get('database');
            $database = \Crypt::encrypt($database);

            //delete
            if ($select == "刪除") {
                for ($i = 0; $i < $count; $i++) {
                    DB::beginTransaction();
                    try {
                        月請購_單耗::where('客戶別', $client[$i])
                            ->where('機種', $machine[$i])
                            ->where('製程', $production[$i])
                            ->where('料號', $number[$i])
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
            if ($select == "更新") {
                for ($i = 0; $i < $count; $i++) {
                    DB::beginTransaction();
                    try {
                        月請購_單耗::where('客戶別', $client[$i])
                            ->where('機種', $machine[$i])
                            ->where('製程', $production[$i])
                            ->where('料號', $number[$i])
                            ->update([
                                '狀態' => "待畫押", '畫押工號' => $jobnumber,
                                '畫押信箱' => $email, '單耗' => $amount[$i], '送單時間' => Carbon::now(), '送單人' => \Auth::user()->姓名,
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
        } else {
            return redirect(route('member.login'));
        }
    }

    //站位人力(刪除或修改)
    public function standchangeordelete(Request $request)
    {
        if (Session::has('username')) {
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
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $sessemail = \Crypt::encrypt($email);
            $name = \Crypt::encrypt(\Auth::user()->username);
            $database = $request->session()->get('database');
            $database = \Crypt::encrypt($database);


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
                                '狀態' => "待畫押", '畫押工號' => $jobnumber,
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
        } else {
            return redirect(route('member.login'));
        }
    }

    //料號單耗(新增)
    public function consumenew(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null) {

                if (strlen($request->input('number')) === 12) {
                    $client = $request->input('client');
                    $machine = $request->input('machine');
                    $production = $request->input('production');
                    $number = $request->input('number');

                    $name = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '單耗')->where('月請購', '是')->value('品名');

                    $format = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '單耗')->where('月請購', '是')->value('規格');

                    $unit = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '單耗')->where('月請購', '是')->value('單位');

                    $lt = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '單耗')->where('月請購', '是')->value('LT');

                    $nowmps = DB::table('MPS')->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('本月MPS');

                    $nowday = DB::table('MPS')->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('本月生產天數');

                    $nextmps = DB::table('MPS')->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('下月MPS');

                    $nextday = DB::table('MPS')->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('下月生產天數');

                    $lt = round($lt, 3);
                    if ($name !== null && $format !== null) {
                        return \Response::json([
                            'client' => $client, 'machine' => $machine, 'production' => $production, 'number' => $number,
                            'name' => $name, 'format' => $format, 'unit' => $unit, 'lt' => $lt, 'nowmps' => $nowmps, 'nowday' => $nowday,
                            'nextmps' => $nextmps, 'nextday' => $nextday,
                        ]/* Status code here default is 200 ok*/);
                    }
                    //沒有料號
                    else {
                        return \Response::json(['message' => 'no isn'], 420/* Status code here default is 200 ok*/);
                    }
                }
                //料號長度不為12
                else {
                    return \Response::json(['message' => 'isn not 12'], 421/* Status code here default is 200 ok*/);
                }
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //站位人力(新增)
    public function standnew(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null) {

                if (strlen($request->input('number')) === 12) {
                    $client = $request->input('client');
                    $machine = $request->input('machine');
                    $production = $request->input('production');
                    $number = $request->input('number');

                    $name = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '站位')->where('月請購', '是')->value('品名');

                    $mpq = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '站位')->where('月請購', '是')->value('MPQ');

                    $unit = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '站位')->where('月請購', '是')->value('單位');

                    $lt = DB::table('consumptive_material')->where('料號', $number)
                        ->where('耗材歸屬', '站位')->where('月請購', '是')->value('LT');

                    $lt = round($lt, 3);
                    if ($name !== null && $mpq !== null) {

                        return \Response::json([
                            'client' => $client, 'machine' => $machine, 'production' => $production, 'number' => $number,
                            'name' => $name, 'unit' => $unit, 'lt' => $lt, 'mpq' => $mpq,
                        ]/* Status code here default is 200 ok*/);
                    }
                    //沒有料號
                    else {
                        return \Response::json(['message' => 'no isn'], 420/* Status code here default is 200 ok*/);
                    }
                }
                //料號長度不為12
                else {
                    return \Response::json(['message' => 'isn not 12'], 421/* Status code here default is 200 ok*/);
                }
            }
        } else {
            return redirect(route('member.login'));
        }
    }


    //提交料號單耗
    public function consumenewsubmit(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $row = $request->input('row');
            $record = 0;
            $check = array();
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $sessemail = \Crypt::encrypt($email);
            $username = \Crypt::encrypt(\Auth::user()->username);
            $database = $request->session()->get('database');
            $database = \Crypt::encrypt($database);
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $number = $request->input('number')[$i];
                    $client = $request->input('client')[$i];
                    $machine = $request->input('machine')[$i];
                    $production = $request->input('production')[$i];
                    $consume = $request->input('consume')[$i];

                    $test = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('狀態');

                    if ($test === null) {
                        DB::table('月請購_單耗')
                            ->insert([
                                '料號' => $number, '客戶別' => $client, '機種' => $machine, '製程' => $production,
                                '單耗' => $consume, '畫押工號' => $jobnumber,
                                '畫押信箱' => $email, '狀態' => "待畫押", '送單時間' => Carbon::now(), '送單人' => \Auth::user()->username,
                            ]);
                        $record++;
                        array_push($check, $row[$i]);
                    } else if ($test === '待重畫') {
                        月請購_單耗::where('客戶別', $client)
                            ->where('機種', $machine)
                            ->where('製程', $production)
                            ->where('料號', $number)
                            ->update([
                                '狀態' => "待畫押", '畫押工號' => $jobnumber,
                                '畫押信箱' => $email, '單耗' => $consume, '送單時間' => Carbon::now(), '送單人' => \Auth::user()->username,
                            ]);
                        $record++;
                        array_push($check, $row[$i]);
                    } else {
                        continue;
                    } //continue-else

                } //for
                DB::commit();
                self::sendconsumemail($email, $sessemail, $username, $database);

                return \Response::json(['record' => $record, 'check' => $check]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            } //try - catch
        } else {
            return redirect(route('member.login'));
        }
    }

    //提交站位人力
    public function standnewsubmit(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $row = $request->input('row');
            $record = 0;
            $check = array();
            $database = $request->session()->get('database');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $sessemail = \Crypt::encrypt($email);
            $name = \Crypt::encrypt(\Auth::user()->username);
            $database = $request->session()->get('database');
            $database = \Crypt::encrypt($database);

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
                                '下月每日更換頻率' => $nextchange, '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email, '送單時間' => Carbon::now(), '送單人' => \Auth::user()->username,
                            ]);
                        $record++;
                        array_push($check, $row[$i]);
                    } else if ($test === '待重畫') {
                        月請購_站位::where('客戶別', $client)
                            ->where('機種', $machine)
                            ->where('製程', $production)
                            ->where('料號', $number)
                            ->update([
                                '狀態' => "待畫押", '畫押工號' => $jobnumber,
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
        } else {
            return redirect(route('member.login'));
        }
    }

    //非月請購添加
    public function notmonthadd(Request $request)
    {
        if (Session::has('username')) {
            $number = $request->input('number');
            $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
            $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
            $month = DB::table('consumptive_material')->where('料號', $number)->value('月請購');
            if ($name !== NULL && $unit !== NULL) {

                return \Response::json([
                    'client' => $request->input('client'), 'number' => $number,
                    'name' => $name, 'unit' => $unit, 'month' => $month
                ]/* Status code here default is 200 ok*/);
            } else {
                return \Response::json([], 421/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //非月請購提交
    public function notmonthsubmit(Request $request)
    {
        if (Session::has('username')) {
            $count = count($request->input('client'));
            $now = Carbon::now();
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $client = $request->input('client')[$i];
                    $number = $request->input('number')[$i];
                    $amount = $request->input('amount')[$i];
                    $sxb = $request->input('sxb')[$i];
                    $say = $request->input('say')[$i];
                    $reason = $request->input('reason')[$i];
                    $test = DB::table('在途量')->where('客戶', $client)->where('料號', $number)->value('請購數量');
                    $say = $reason . ' ' . $say;
                    if ($test === null) {
                        DB::table('在途量')
                            ->insert(['客戶' => $client, '料號' => $number, '請購數量' => $amount/*, 'created_at' => $now*/]);
                    } else {
                        DB::table('在途量')
                            ->where('客戶', $client)->where('料號', $number)
                            ->update(['請購數量' => $test + $amount, /*'updated_at' => $now*/]);
                    }

                    DB::table('非月請購')
                        ->insert([
                            '客戶別' => $client, '料號' => $number, '請購數量' => $amount, '上傳時間' => $now, '說明' => $say, 'SXB單號' => $sxb
                        ]);
                } //for
                DB::commit();
                return \Response::json(['record' => $count] /* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //月請購查詢 or 添加
    public function monthsearchoradd(Request $request)
    {
        if (Session::has('username')) {
            //search
            $check = array();
            $count = $request->input('count');
            $names = DB::table('製程')->pluck('制程');
            for ($i = 0; $i < $count; $i++) {
                if ($request->has('innumber' . $i)) {
                    array_push($check, $names[$i]);
                } else {
                    continue;
                }
            }
            if ($request->has('search')) {
                if ($request->input('client') === null && $request->input('machine') === null) {
                    if (empty($check)) {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()]);
                    } else {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()->wherein('製程', $check)]);
                    }
                } else if ($request->input('client') !== null && $request->input('machine') === null) {
                    if (empty($check)) {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()->where('客戶別', $request->input('client'))]);
                    } else {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()->wherein('製程', $check)->where('客戶別', $request->input('client'))]);
                    }
                } else if ($request->input('client') === null && $request->input('machine') !== null) {
                    if (empty($check)) {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()->where('機種', $request->input('machine'))]);
                    } else {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()->wherein('製程', $check)->where('機種', $request->input('machine'))]);
                    }
                } else if ($request->input('client') !== null && $request->input('machine') !== null) {
                    if (empty($check)) {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()->where('機種', $request->input('machine'))->where('客戶別', $request->input('client'))]);
                    } else {
                        return view('month.monthsearchok')->with(['data' => MPS::cursor()->wherein('製程', $check)->where('機種', $request->input('machine'))->where('客戶別', $request->input('client'))]);
                    }
                }
            }
            //add
            else if ($request->has('add')) {
                if ($request->input('client') === null && $request->input('machine') === null) {
                    return back()->withErrors([
                        'client' => trans('validation.required'),
                        'machine' => trans('validation.required'),
                    ]);
                } else if ($request->input('client') === null && $request->input('machine') !== null) {
                    return back()->withErrors([
                        'client' => trans('validation.required'),
                    ]);
                } else if ($request->input('client') !== null && $request->input('machine') === null) {
                    return back()->withErrors([
                        'machine' => trans('validation.required'),
                    ]);
                } else if ($request->input('client') !== null && $request->input('machine') !== null) {
                    if (empty($check)) {
                        return back()->withErrors([
                            'production' => trans('validation.required'),
                        ]);
                    } else {
                        $nowmps = $request->input('nowmps');
                        $nowday = $request->input('nowday');
                        $nextmps = $request->input('nextmps');
                        $nextday = $request->input('nextday');
                        return view('month.monthadd')
                            ->with('client', $request->input('client'))
                            ->with('machine', $request->input('machine'))
                            ->with('production', $check)
                            ->with('nowmps', $nowmps)
                            ->with('nowday', $nowday)
                            ->with('nextmps', $nextmps)
                            ->with('nextday', $nextday);
                    }
                }
            } else {
                return redirect(route('month.importmonth'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //月請購刪除
    public function monthdelete(Request $request)
    {
        if (Session::has('username')) {

            $count = $request->input('count');
            $clients = $request->input('client');
            $machines = $request->input('machine');
            $productions = $request->input('production');
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {

                    DB::table('MPS')
                        ->where('客戶別', $clients[$i])
                        ->where('機種', $machines[$i])
                        ->where('製程', $productions[$i])
                        ->delete();
                } //for
                DB::commit();
                return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            } //catch
        } else {
            return redirect(route('member.login'));
        }
    }

    //月請購提交
    public function monthsubmit(Request $request)
    {

        if (Session::has('username')) {
            $count = $request->input('count');
            $now = Carbon::now();
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $client = $request->input('client')[$i];
                    $machine = $request->input('machine')[$i];
                    $production = $request->input('production')[$i];
                    $nextmps = $request->input('nextmps')[$i];
                    $nextday = $request->input('nextday')[$i];
                    $nowmps = $request->input('nowmps')[$i];
                    $nowday = $request->input('nowday')[$i];
                    $test = DB::table('MPS')->where('客戶別', $client)->where('機種', $machine)
                        ->where('製程', $production)->value('下月MPS');
                    if ($test === null) {
                        DB::table('MPS')
                            ->insert([
                                '客戶別' => $client, '機種' => $machine, '製程' => $production, '下月MPS' => $nextmps, '下月生產天數' => $nextday,
                                '本月MPS' => $nowmps, '本月生產天數' => $nowday, '填寫時間' => $now
                            ]);
                    } else {
                        DB::table('MPS')
                            ->where('客戶別', $client)
                            ->where('機種', $machine)
                            ->where('製程', $production)
                            ->update([
                                '下月MPS' => $nextmps, '下月生產天數' => $nextday,
                                '本月MPS' => $nowmps, '本月生產天數' => $nowday, '填寫時間' => $now
                            ]);
                    }
                } //for
                DB::commit();
                return \Response::json(['record' => $count] /* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //在途量(查詢)
    public function transitsearch(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $number =  $request->input('number');
            $send = $request->input('send');
            if ($number !== null) {
                $datas = DB::table('consumptive_material')
                    ->join('在途量', function ($join) {
                        $join->on('在途量.料號', '=', 'consumptive_material.料號')
                            ->where('在途量.請購數量', '>', 0);
                    })->where('consumptive_material.料號', 'like', $number . '%')->get();
            } else {
                $datas = DB::table('consumptive_material')
                    ->join('在途量', function ($join) {
                        $join->on('在途量.料號', '=', 'consumptive_material.料號')
                            ->where('在途量.請購數量', '>', 0);
                    })->get();
            }

            //select empty
            if ($request->input('client') === null && $request->input('number') === null && $request->input('send') === null) {
                return view('month.transitsearchok')->with(['data' => $datas]);
            }
            //select client
            else if ($request->input('client') !== null && $request->input('number') === null && $request->input('send') === null) {
                return view('month.transitsearchok')->with(['data' => $datas->where('客戶', $client)]);
            }
            //input number
            else if ($request->input('client') === null && $request->input('number') !== null && $request->input('send') === null) {
                return view('month.transitsearchok')->with(['data' => $datas]);
            }
            //select send
            else if ($request->input('client') === null && $request->input('number') === null && $request->input('send') !== null) {
                return view('month.transitsearchok')->with(['data' => $datas->where('發料部門', $send)]);
            }
            //select client and number
            else if ($request->input('client') !== null && $request->input('number') !== null && $request->input('send') === null) {

                return view('month.transitsearchok')->with(['data' => $datas->where('客戶', $client)]);
            }
            //select client and send
            else if ($request->input('client') !== null && $request->input('number') === null && $request->input('send') !== null) {
                return view('month.transitsearchok')->with(['data' => $datas->where('客戶', $client)->where('發料部門', $send)]);
            }
            //select number and send
            else if ($request->input('client') === null && $request->input('number') !== null && $request->input('send') !== null) {
                return view('month.transitsearchok')->with(['data' => $datas->where('發料部門', $send)]);
            }
            //select all
            else if ($request->input('client') !== null && $request->input('number') !== null && $request->input('send') !== null) {

                return view('month.transitsearchok')->with(['data' => $datas->where('發料部門', $send)->where('客戶', $client)]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //SXB單(查詢)
    public function sxbsearch(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $number =  $request->input('number');
            $send = $request->input('send');
            $sxb = $request->input('sxb');
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            if ($number !== null) {
                $datas = DB::table('consumptive_material')
                    ->join('請購單', function ($join) {
                        $join->on('請購單.料號', '=', 'consumptive_material.料號')
                            ->whereNotNull('SXB單號');
                    })->where('consumptive_material.料號', 'like', $number . '%')->get();
                $datas1 = DB::table('consumptive_material')
                    ->join('非月請購', function ($join) {
                        $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                            ->whereNotNull('SXB單號');
                    })->where('consumptive_material.料號', 'like', $number . '%')->get();
            } else {
                $datas = DB::table('consumptive_material')
                    ->join('請購單', function ($join) {
                        $join->on('請購單.料號', '=', 'consumptive_material.料號')
                            ->whereNotNull('SXB單號');
                    })->get();
                $datas1 = DB::table('consumptive_material')
                    ->join('非月請購', function ($join) {
                        $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                            ->whereNotNull('SXB單號');
                    })->get();
            }

            //all empty
            if ($client === null && $sxb === null && $number === null  && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas])->with(['data1' => $datas1]);
            }
            //select client
            else if ($client !== null && $sxb === null && $number === null && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('客戶', $client)])->with(['data1' => $datas1->where('客戶別', $client)]);
            }
            //select sxb
            else if ($client === null && $sxb !== null && $number === null && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('SXB單號', $sxb)])->with(['data1' => $datas1->where('SXB單號', $sxb)]);
            }
            //input material number
            else if ($client === null && $sxb === null && $number !== null && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas])->with(['data1' => $datas1]);
            }
            //select send
            else if ($client === null && $sxb === null && $number === null && $send !== null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)])->with(['data1' => $datas1->where('發料部門', $send)]);
            }
            //select date
            else if ($client === null && $sxb === null && $number === null && $send === null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])]);
            }
            //select client and sxb
            else if ($client !== null && $sxb !== null && $number === null && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('客戶', $client)->where('SXB單號', $sxb)])
                    ->with(['data1' => $datas1->where('客戶別', $client)->where('SXB單號', $sxb)]);
            }
            //select client and number
            else if ($client !== null && $sxb === null && $number !== null && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('客戶', $client)])
                    ->with(['data1' => $datas1->where('客戶別', $client)]);
            }
            //select client and time
            else if ($client !== null && $sxb === null && $number === null && $send === null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])->where('客戶', $client)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])->where('客戶別', $client)]);
            }
            //select sxb and number
            else if ($client === null && $sxb !== null && $number !== null && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('SXB單號', $sxb)])
                    ->with(['data1' => $datas1->where('SXB單號', $sxb)]);
            }
            //select sxb and time
            else if ($client === null && $sxb !== null && $number === null && $send === null && ($request->has('date'))) {

                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])->where('SXB單號', $sxb)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])->where('SXB單號', $sxb)]);
            }
            //select number and time
            else if ($client === null && $sxb === null && $number !== null && $send === null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])]);
            }
            //select client and send
            else if ($client !== null && $sxb === null && $number === null && $send !== null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('客戶', $client)->where('發料部門', $send)])
                    ->with(['data1' => $datas1->where('客戶別', $client)->where('發料部門', $send)]);
            }
            //select sxb and send
            else if ($client === null && $sxb !== null && $number === null && $send !== null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('SXB單號', $sxb)->where('發料部門', $send)])
                    ->with(['data1' => $datas1->where('SXB單號', $sxb)->where('發料部門', $send)]);
            }
            //select number and send
            else if ($client === null && $sxb === null && $number !== null && $send !== null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)])
                    ->with(['data1' => $datas1->where('發料部門', $send)]);
            }
            //select time and send
            else if ($client === null && $sxb === null && $number === null && $send !== null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])->where('發料部門', $send)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])->where('發料部門', $send)]);
            }
            //select client and sxb and number
            else if ($client !== null && $sxb !== null && $number !== null && $send === null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('SXB單號', $sxb)
                    ->where('客戶', $client)])
                    ->with(['data1' => $datas1->where('SXB單號', $sxb)
                        ->where('客戶別', $client)]);
            }
            //select client and sxb and time
            else if ($client !== null && $sxb !== null && $number === null && $send === null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])->where('SXB單號', $sxb)
                    ->where('客戶', $client)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])->where('SXB單號', $sxb)
                        ->where('客戶別', $client)]);
            }
            //select client and number and time
            else if ($client !== null && $sxb === null && $number !== null && $send === null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])
                    ->where('客戶', $client)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])
                        ->where('客戶別', $client)]);
            }
            //select sxb and number and time
            else if ($client === null && $sxb !== null && $number !== null && $send === null && ($request->has('date'))) {

                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])
                    ->where('SXB單號', $sxb)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])
                        ->where('SXB單號', $sxb)]);
            }
            //select client and sxb and send
            else if ($client !== null && $sxb !== null && $number === null && $send !== null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)->where('SXB單號', $sxb)
                    ->where('客戶', $client)])
                    ->with(['data1' => $datas1->where('發料部門', $send)->where('SXB單號', $sxb)
                        ->where('客戶別', $client)]);
            }
            //select client and number and send
            else if ($client !== null && $sxb === null && $number !== null && $send !== null && !($request->has('date'))) {

                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)
                    ->where('客戶', $client)])
                    ->with(['data1' => $datas1->where('發料部門', $send)
                        ->where('客戶別', $client)]);
            }
            //select client and time and send
            else if ($client !== null && $sxb === null && $number === null && $send !== null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])->where('客戶', $client)
                    ->where('發料部門', $send)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])->where('客戶別', $client)
                        ->where('發料部門', $send)]);
            }
            //select number and sxb and send
            else if ($client === null && $sxb !== null && $number !== null && $send !== null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)->where('SXB單號', $sxb)])
                    ->with(['data1' => $datas1->where('發料部門', $send)->where('SXB單號', $sxb)]);
            }
            //select sxb and time and send
            else if ($client === null && $sxb !== null && $number === null && $send !== null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])->where('SXB單號', $sxb)
                    ->where('發料部門', $send)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])->where('SXB單號', $sxb)
                        ->where('發料部門', $send)]);
            }
            //select number and time and send
            else if ($client === null && $sxb === null && $number !== null && $send !== null && ($request->has('date'))) {

                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])
                    ->where('發料部門', $send)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])
                        ->where('發料部門', $send)]);
            }
            //select client and sxb and number and time
            else if ($client !== null && $sxb !== null && $number !== null && $send === null && ($request->has('date'))) {

                return view('month.sxbsearchok')->with(['data' => $datas->where('SXB單號', $sxb)
                    ->where('客戶', $client)->whereBetween('請購時間', [$begin, $end])])
                    ->with(['data1' => $datas1->where('SXB單號', $sxb)
                        ->where('客戶別', $client)->whereBetween('上傳時間', [$begin, $end])]);
            }
            //select client and send and number and time
            else if ($client !== null && $sxb === null && $number !== null && $send !== null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)
                    ->where('客戶', $client)->whereBetween('請購時間', [$begin, $end])])
                    ->with(['data1' => $datas1->where('發料部門', $send)
                        ->where('客戶別', $client)->whereBetween('上傳時間', [$begin, $end])]);
            }
            //select client and sxb and number and send
            else if ($client !== null && $sxb !== null && $number !== null && $send !== null && !($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('SXB單號', $sxb)
                    ->where('客戶', $client)->where('發料部門', $send)])
                    ->with(['data1' => $datas1->where('SXB單號', $sxb)
                        ->where('客戶別', $client)->where('發料部門', $send)]);
            }
            //select client and sxb and send and time
            else if ($client !== null && $sxb !== null && $number === null && $send !== null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)->where('SXB單號', $sxb)
                    ->where('客戶', $client)->whereBetween('請購時間', [$begin, $end])])
                    ->with(['data1' => $datas1->where('發料部門', $send)->where('SXB單號', $sxb)
                        ->where('客戶別', $client)->whereBetween('上傳時間', [$begin, $end])]);
            }
            //select number and sxb and send and time
            else if ($client === null && $sxb !== null && $number !== null && $send !== null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->where('發料部門', $send)->where('SXB單號', $sxb)
                    ->whereBetween('請購時間', [$begin, $end])])
                    ->with(['data1' => $datas1->where('發料部門', $send)->where('SXB單號', $sxb)
                        ->whereBetween('上傳時間', [$begin, $end])]);
            }
            //select all
            else if ($client !== null && $sxb !== null && $number !== null && $send !== null && ($request->has('date'))) {
                return view('month.sxbsearchok')->with(['data' => $datas->whereBetween('請購時間', [$begin, $end])
                    ->where('SXB單號', $sxb)->where('客戶', $client)->where('發料部門', $send)])
                    ->with(['data1' => $datas1->whereBetween('上傳時間', [$begin, $end])
                        ->where('SXB單號', $sxb)->where('客戶別', $client)->where('發料部門', $send)]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //SRM單(查詢)
    public function srmsearch(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $number =  $request->input('number');
            $srm = $request->input('srm');
            $send = $request->input('send');
            if ($number !== null) {
                $datas = DB::table('consumptive_material')
                    ->join('請購單', function ($join) {
                        $join->on('請購單.料號', '=', 'consumptive_material.料號')
                            ->whereNull('SXB單號');
                    })->where('consumptive_material.料號', 'like', $number . '%')->get();
            } else {
                $datas = DB::table('consumptive_material')
                    ->join('請購單', function ($join) {
                        $join->on('請購單.料號', '=', 'consumptive_material.料號')
                            ->whereNull('SXB單號');
                    })->get();
            }
            //all empty
            if ($client === null && $number === null && $srm === null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas]);
            }
            //select client
            else if ($client !== null && $number === null && $srm === null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas->where('客戶', $client)]);
            }
            //select number
            else if ($client === null && $number !== null && $srm === null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas]);
            }
            //select srm
            else if ($client === null && $number === null && $srm !== null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas->where('SRM單號', $srm)]);
            }
            //select send
            else if ($client === null && $number === null && $srm === null && $send !== null) {
                return view('month.srmsearchok')->with(['data' => $datas->where('發料部門', $send)]);
            }
            //select client and number
            else if ($client !== null && $number !== null && $srm === null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('客戶', $client)]);
            }
            //select client and srm
            else if ($client !== null && $number === null && $srm !== null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('SRM單號', $srm)->where('客戶', $client)]);
            }
            //select client and send
            else if ($client !== null && $number === null && $srm === null && $send !== null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('客戶', $client)]);
            }
            //select number and srm
            else if ($client === null && $number !== null && $srm !== null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('SRM單號', $srm)]);
            }
            //select number and send
            else if ($client === null && $number !== null && $srm === null && $send !== null) {

                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)]);
            }
            //select srm and send
            else if ($client === null && $number === null && $srm !== null && $send !== null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('SRM單號', $srm)]);
            }
            //select srm and send and client
            else if ($client !== null && $number === null && $srm !== null && $send !== null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('SRM單號', $srm)->where('客戶', $client)]);
            }
            //select srm and send and number
            else if ($client === null && $number !== null && $srm !== null && $send !== null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('SRM單號', $srm)]);
            }
            //select client and send and number
            else if ($client !== null && $number !== null && $srm === null && $send !== null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('發料部門', $send)->where('客戶', $client)]);
            }
            //select srm and client and number
            else if ($client !== null && $number !== null && $srm !== null && $send === null) {
                return view('month.srmsearchok')->with(['data' => $datas->where('SRM單號', $srm)->where('客戶', $client)]);
            }
            //select all
            else if ($client !== null && $number !== null && $srm !== null && $send !== null) {
                return view('month.srmsearchok')->with(['data' => $datas
                    ->where('SRM單號', $srm)->where('客戶', $client)->where('發料部門', $send)]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //SRM單提交
    public function srmsubmit(Request $request)
    {
        if (Session::has('username')) {
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
            }
            return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }

    //請購單-產生
    public function buylistmake(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
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

            if ($client == "ALL_CLIENT") {
                Session::put("clientChoice", "All Clients"); // for Excel Header
                if ($send === null) {
                    $datas = DB::table('月請購_單耗')
                        ->join('MPS', function ($join) {

                            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                                ->on('MPS.機種', '=', '月請購_單耗.機種')
                                ->on('MPS.製程', '=', '月請購_單耗.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                        })->where('月請購_單耗.狀態', '=', "已完成")->get();
                } else {
                    $datas = DB::table('月請購_單耗')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                                ->on('MPS.機種', '=', '月請購_單耗.機種')
                                ->on('MPS.製程', '=', '月請購_單耗.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                        })->where('consumptive_material.發料部門', $send)
                        ->where('月請購_單耗.狀態', '=', "已完成")->get();
                } // if else 

                foreach ($datas as $data) {
                    $test = $data->幣別;
                    if ($test !== $money) {
                        if ($test == "USD" && $usd == null) {
                            return back()->withErrors([
                                'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "USD" && $usd != null) {
                            array_push($array, $usd);
                        } else if ($test == "RMB" && $rmb == null) {
                            return back()->withErrors([
                                'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "RMB" && $rmb != null) {
                            array_push($array, $rmb);
                        } else if ($test == "JPY" && $jpy == null) {
                            return back()->withErrors([
                                'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "JPY" && $jpy != null) {
                            array_push($array, $jpy);
                        } else if ($test == "TWD" && $twd == null) {
                            return back()->withErrors([
                                'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "TWD" && $twd != null) {
                            array_push($array, $twd);
                        } else if ($test == "VND" && $vnd == null) {
                            return back()->withErrors([
                                'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "VND" && $vnd != null) {
                            array_push($array, $vnd);
                        } else if ($test == "IDR" && $idr == null) {
                            return back()->withErrors([
                                'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "IDR" && $idr != null) {
                            array_push($array, $idr);
                        }
                    } else {
                        array_push($array, 1);
                        continue;
                    }
                } // for each

                if ($send === null) {
                    $datas2 = DB::table('月請購_站位')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                                ->on('MPS.機種', '=', '月請購_站位.機種')
                                ->on('MPS.製程', '=', '月請購_站位.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                        })->get();
                } else {
                    $datas2 = DB::table('月請購_站位')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                                ->on('MPS.機種', '=', '月請購_站位.機種')
                                ->on('MPS.製程', '=', '月請購_站位.製程');
                        })
                        ->join('consumptive_material', function ($join) use ($send) {
                            $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                        })->where('consumptive_material.發料部門', $send)
                        ->get();
                } // if else

                foreach ($datas2 as $data) {
                    $test = $data->幣別;
                    if ($test !== $money) {
                        if ($test == "USD" && $usd == null) {
                            return back()->withErrors([
                                'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "USD" && $usd != null) {
                            array_push($array1, $usd);
                        } else if ($test == "RMB" && $rmb == null) {
                            return back()->withErrors([
                                'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "RMB" && $rmb != null) {
                            array_push($array1, $rmb);
                        } else if ($test == "JPY" && $jpy == null) {
                            return back()->withErrors([
                                'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "JPY" && $jpy != null) {
                            array_push($array1, $jpy);
                        } else if ($test == "TWD" && $twd == null) {
                            return back()->withErrors([
                                'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "TWD" && $twd != null) {
                            array_push($array1, $twd);
                        } else if ($test == "VND" && $vnd == null) {
                            return back()->withErrors([
                                'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "VND" && $vnd != null) {
                            array_push($array1, $vnd);
                        } else if ($test == "IDR" && $idr == null) {
                            return back()->withErrors([
                                'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "IDR" && $idr != null) {
                            array_push($array1, $idr);
                        } // if else if
                    } else {
                        array_push($array1, 1);
                        continue;
                    } // if else
                } // for each

                return view('month.buylistmakeok')->with(['data1' => $datas])->with(['data2' => $datas2])
                    ->with(['dow1' => $datas])->with(['dow2' => $datas2])
                    ->with(['rate1' => $array])->with(['rate2' => $array1])
                    ->with(['drate1' => $array])->with(['drate2' => $array1]);
            } else {
                Session::put("clientChoice", "All Clients"); // for Excel Header
                if ($send === null) {
                    $datas = DB::table('月請購_單耗')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                                ->on('MPS.機種', '=', '月請購_單耗.機種')
                                ->on('MPS.製程', '=', '月請購_單耗.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                        })->where('月請購_單耗.客戶別', $client)
                        ->where('月請購_單耗.狀態', '=', "已完成")
                        ->get();
                } else {
                    $datas = DB::table('月請購_單耗')
                        ->join('MPS', function ($join) use ($send) {
                            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                                ->on('MPS.機種', '=', '月請購_單耗.機種')
                                ->on('MPS.製程', '=', '月請購_單耗.製程');
                        })
                        ->join('consumptive_material', function ($join) use ($send) {
                            $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                        })->where('月請購_單耗.客戶別', $client)
                        ->where('consumptive_material.發料部門', $send)
                        ->where('月請購_單耗.狀態', '=', "已完成")
                        ->get();
                }

                foreach ($datas as $data) {
                    $test = $data->幣別;
                    if ($test !== $money) {
                        if ($test == "USD" && $usd == null) {
                            return back()->withErrors([
                                'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "USD" && $usd != null) {
                            array_push($array, $usd);
                        } else if ($test == "RMB" && $rmb == null) {
                            return back()->withErrors([
                                'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "RMB" && $rmb != null) {
                            array_push($array, $rmb);
                        } else if ($test == "JPY" && $jpy == null) {
                            return back()->withErrors([
                                'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "JPY" && $jpy != null) {
                            array_push($array, $jpy);
                        } else if ($test == "TWD" && $twd == null) {
                            return back()->withErrors([
                                'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "TWD" && $twd != null) {
                            array_push($array, $twd);
                        } else if ($test == "VND" && $vnd == null) {
                            return back()->withErrors([
                                'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "VND" && $vnd != null) {
                            array_push($array, $vnd);
                        } else if ($test == "IDR" && $idr == null) {
                            return back()->withErrors([
                                'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "IDR" && $idr != null) {
                            array_push($array, $idr);
                        }
                    } else {
                        array_push($array, 1);
                        continue;
                    }
                }
                if ($send === null) {
                    $datas2 = DB::table('月請購_站位')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                                ->on('MPS.機種', '=', '月請購_站位.機種')
                                ->on('MPS.製程', '=', '月請購_站位.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                        })->where('月請購_站位.客戶別', $client)
                        ->get();
                } else {
                    $datas2 = DB::table('月請購_站位')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                                ->on('MPS.機種', '=', '月請購_站位.機種')
                                ->on('MPS.製程', '=', '月請購_站位.製程');
                        })
                        ->join('consumptive_material', function ($join) use ($send) {
                            $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                        })->where('月請購_站位.客戶別', $client)
                        ->where('consumptive_material.發料部門', $send)
                        ->get();
                }
                foreach ($datas2 as $data) {
                    $test = $data->幣別;
                    if ($test !== $money) {
                        if ($test == "USD" && $usd == null) {
                            return back()->withErrors([
                                'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "USD" && $usd != null) {
                            array_push($array1, $usd);
                        } else if ($test == "RMB" && $rmb == null) {
                            return back()->withErrors([
                                'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "RMB" && $rmb != null) {
                            array_push($array1, $rmb);
                        } else if ($test == "JPY" && $jpy == null) {
                            return back()->withErrors([
                                'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "JPY" && $jpy != null) {
                            array_push($array1, $jpy);
                        } else if ($test == "TWD" && $twd == null) {
                            return back()->withErrors([
                                'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "TWD" && $twd != null) {
                            array_push($array1, $twd);
                        } else if ($test == "VND" && $vnd == null) {
                            return back()->withErrors([
                                'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "VND" && $vnd != null) {
                            array_push($array1, $vnd);
                        } else if ($test == "IDR" && $idr == null) {
                            return back()->withErrors([
                                'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
                            ]);
                        } else if ($test == "IDR" && $idr != null) {
                            array_push($array1, $idr);
                        }
                    } else {
                        array_push($array1, 1);
                        continue;
                    }
                }

                return view('month.buylistmakeok')->with(['data1' => $datas])->with(['data2' => $datas2])
                    ->with(['dow1' => $datas])->with(['dow2' => $datas2])
                    ->with(['rate1' => $array])->with(['rate2' => $array1])
                    ->with(['drate1' => $array])->with(['drate2' => $array1]);
            }
        } else {
            return redirect(route('member.login'));
        } // else
    }

    //請購單-提交
    public function buylistsubmit(Request $request)
    {
        if (Session::has('username')) {
            //submit

            $now = Carbon::now();
            $check = $request->input('check');
            $count = $request->input('count');
            $Alldata = json_decode($request->input('AllData'));
            $record = 0;
            $srm = $Alldata[0];
            $client = $Alldata[1];
            $number = $Alldata[2];
            $name = $Alldata[3];
            $moq = $Alldata[4];
            $nextneed = $Alldata[5];
            $nowneed = $Alldata[6];
            $safe = $Alldata[7];
            $price = $Alldata[8];
            $money = $Alldata[9];
            $rate = $Alldata[10];
            $amount = $Alldata[11];
            $stock = $Alldata[12];
            $buyamount = $Alldata[13];
            $realneed = $Alldata[14];
            $buymoney = $Alldata[15];
            $buyper = $Alldata[16];
            $needmoney = $Alldata[17];
            $needper = $Alldata[18];
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    if ($check[$i] == 1) {
                        DB::table('請購單')
                            ->insert([
                                'SRM單號' => $srm[$i], '客戶' => $client[$i], '料號' => $number[$i], '品名' => $name[$i], 'MOQ' => $moq[$i], '下月需求' => $nextneed[$i], '當月需求' => $nowneed[$i], '安全庫存' => $safe[$i], '單價' => $price[$i], '幣別' => $money[$i], '匯率' => $rate[$i], '在途數量' => $amount[$i], '現有庫存' => $stock[$i], '本次請購數量' => $buyamount[$i], '實際需求' => $realneed[$i], '請購金額' => $buymoney[$i], '請購占比' => $buyper[$i], '需求金額' => $needmoney[$i], '需求占比' => $needper[$i], '請購時間' => $now
                            ]);
                        $record++;
                    } else {
                        continue;
                    }
                }
                DB::commit();
                return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //單耗上傳
    public function uploadconsume(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('month.uploadconsume')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //站位上傳
    public function uploadstand(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('month.uploadstand')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //非月請購上傳
    public function uploadnotmonth(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('month.uploadnotmonth')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //月請購上傳
    public function uploadmonth(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('month.uploadmonth')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //test單耗畫押提交
    public function testconsume(Request $request)
    {

        $now = Carbon::now();
        $count = $request->input('count');
        $sender = $request->input('sender');
        $Alldata = json_decode($request->input('AllData'));

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $client = $Alldata[2][$i];
                $machine = $Alldata[3][$i];
                $production = $Alldata[4][$i];
                $number = $Alldata[0][$i];
                $check = $Alldata[6][$i];

                if ($check) {
                    月請購_單耗::where('客戶別', $client)
                        ->where('機種', $machine)
                        ->where('製程', $production)
                        ->where('料號', $number)
                        ->update([
                            '狀態' => "已完成", '畫押時間' => $now,

                        ]);
                } else {
                    月請購_單耗::where('客戶別', $client)
                        ->where('機種', $machine)
                        ->where('製程', $production)
                        ->where('料號', $number)
                        ->update([
                            '狀態' => "待重畫", '畫押時間' => $now,

                        ]);
                }
            } //for
            DB::commit();
            self::sendcheckconsume($Alldata, $count, $sender);

            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //try-catch

    }

    //test站位畫押提交
    public function teststand(Request $request)
    {
        if (Session::has('username')) {
            $now = Carbon::now();
            $count = $request->input('count');
            $sender = $request->input('sender');
            $Alldata = json_decode($request->input('AllData'));

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
        } else {
            return redirect(route('member.login'));
        }
    }

    //站位人力查詢下載
    public function standdownload(Request $request)
    {
        if (Session::has('username')) {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

            $worksheet = $spreadsheet->getActiveSheet();

            $title = $request->input('title');
            $count = $request->input('count');


            //填寫表頭
            for ($i = 0; $i < 22; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $title[$i]);
            }

            // 下載
            for ($i = 0; $i < 22; $i++) {
                for ($j = 0; $j < $count; $j++) {

                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data' . $i)[$j]);
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
        } else {
            return redirect(route('member.login'));
        } // if else
    }

    //download data
    public function download(Request $request)
    {
        if (Session::has('username')) {

            // Set value binder so that "100%" won't be inserted as string
            \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $worksheet = $spreadsheet->getActiveSheet();
            $titlecount = $request->input('titlecount');
            $count = $request->input('count');
            $Alldata = json_decode($request->input('AllData'));
            // dd($Alldata); // test
            $worksheet->getPageSetup()->setHorizontalCentered(true);
            // 填寫header & footer of excel when printing
            $worksheet->getHeaderFooter()->setOddHeader("&C&B" . Session::get("clientChoice") . "  " . Carbon::now()->format('m') . "月耗材匯總");
            $worksheet->getHeaderFooter()->setOddFooter("&L&B核准：_______________________&C&B審核：_______________________&R&B申請人：_______________________");
            
            //填寫表頭
            for ($i = 0; $i < $titlecount; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title')[$i]);
            } // for 

            //填寫內容
            $endOfRow = 0 ;
            $endOfCol = 0 ;
            $alphabet = range('A', 'Z'); // A ~ Z
            for ($i = 0; $i < $titlecount; $i++) {
                for ($j = 0; $j < $count; $j++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$i][$j]);
                    $endOfRow = $j ;
                } // for

                $endOfCol = $i ;
            } // for

            // dd($endOfRow . "," . $endOfCol); // test
            
            $worksheet->setCellValue("A" . ($endOfRow+3), "合計：");
            $worksheet->mergeCells("A" . ($endOfRow+3) . ":" . $alphabet[$endOfCol-2] . ($endOfRow+3)); // for the SUM row
            $worksheet->setCellValue( $alphabet[$endOfCol] . ($endOfRow+3), "=SUM(" . $alphabet[$endOfCol] ."2:" . $alphabet[$endOfCol] . ($endOfRow+2) . ")");
            $worksheet->setCellValue( $alphabet[$endOfCol-1] . ($endOfRow+3), "=SUM(" . $alphabet[$endOfCol-1] ."2:" . $alphabet[$endOfCol-1] . ($endOfRow+2) . ")");
            $worksheet->getStyle( $alphabet[$endOfCol] . "2:" . $alphabet[$endOfCol] . ($endOfRow+3))->getNumberFormat()->setFormatCode('0%');
            // 下載
            $now = Carbon::now()->format('YmdHis');
            $titlename = $request->input('titlename');
            $filename = rawurlencode($titlename) . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        } else {
            return redirect(route('member.login'));
        } // else
    }

    //請購單下載
    public function buylistdownload(Request $request)
    {
        if (Session::has('username')) {

            $spreadsheet = new Spreadsheet();
            //$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $worksheet = $spreadsheet->getActiveSheet();

            $count = $request->input('count');
            $Alldata = json_decode($request->input('AllData'));

            $i = 2;

            //填寫表頭
            $worksheet->setCellValueByColumnAndRow(1, 1, 'Item');
            $worksheet->setCellValueByColumnAndRow(2, 1, 'Part Number');
            $worksheet->setCellValueByColumnAndRow(3, 1, 'Part Desc');
            $worksheet->setCellValueByColumnAndRow(4, 1, 'Material Group');
            $worksheet->setCellValueByColumnAndRow(5, 1, '指定廠牌 / 品牌否');
            $worksheet->setCellValueByColumnAndRow(6, 1, '指定廠牌_說明');
            $worksheet->setCellValueByColumnAndRow(7, 1, '廠牌 / 品牌');
            $worksheet->setCellValueByColumnAndRow(8, 1, '客戶指定否');
            $worksheet->setCellValueByColumnAndRow(9, 1, '用途');
            $worksheet->setCellValueByColumnAndRow(10, 1, '是否有驗收要求');
            $worksheet->setCellValueByColumnAndRow(11, 1, '驗收標準');
            $worksheet->setCellValueByColumnAndRow(12, 1, '精度等特殊要求');
            $worksheet->setCellValueByColumnAndRow(13, 1, 'Quantity');
            $worksheet->setCellValueByColumnAndRow(14, 1, '季度預估用量');
            $worksheet->setCellValueByColumnAndRow(15, 1, 'Unit');
            $worksheet->setCellValueByColumnAndRow(16, 1, '需求日期');
            $worksheet->setCellValueByColumnAndRow(17, 1, 'Applicant');
            $worksheet->setCellValueByColumnAndRow(18, 1, '保管人');
            $worksheet->setCellValueByColumnAndRow(19, 1, 'Plant');
            $worksheet->setCellValueByColumnAndRow(20, 1, '廠別');
            $worksheet->setCellValueByColumnAndRow(21, 1, '廠別_Other');
            $worksheet->setCellValueByColumnAndRow(22, 1, '購買類型');
            $worksheet->setCellValueByColumnAndRow(23, 1, '購買類型_Other');
            $worksheet->setCellValueByColumnAndRow(24, 1, 'Project');
            $worksheet->setCellValueByColumnAndRow(25, 1, 'Customer');
            $worksheet->setCellValueByColumnAndRow(26, 1, '製程別');
            $worksheet->setCellValueByColumnAndRow(27, 1, '製程別_Other');
            $worksheet->setCellValueByColumnAndRow(28, 1, '系統別');
            $worksheet->setCellValueByColumnAndRow(29, 1, 'PR TYPE');
            $worksheet->setCellValueByColumnAndRow(30, 1, 'S.L');
            $worksheet->setCellValueByColumnAndRow(31, 1, 'Account Assignment');
            $worksheet->setCellValueByColumnAndRow(32, 1, 'Cost Center');
            $worksheet->setCellValueByColumnAndRow(33, 1, 'AUC No.');
            $worksheet->setCellValueByColumnAndRow(34, 1, 'AUC子號');
            $worksheet->setCellValueByColumnAndRow(35, 1, 'Int.PO');
            $worksheet->setCellValueByColumnAndRow(36, 1, 'Purch.Org.');
            $worksheet->setCellValueByColumnAndRow(37, 1, 'Address');
            $worksheet->setCellValueByColumnAndRow(38, 1, 'Street / House number');
            $worksheet->setCellValueByColumnAndRow(39, 1, 'Remark');
            $worksheet->setCellValueByColumnAndRow(40, 1, '提前備料否');
            //填寫內容
            for ($j = 0; $j < $count; $j++) {
                if ($Alldata[13][$j] > 0) {
                    $worksheet->setCellValueByColumnAndRow(2, $i, $Alldata[2][$j]);
                    $worksheet->setCellValueByColumnAndRow(13, $i, $Alldata[13][$j]);
                    $worksheet->setCellValueByColumnAndRow(14, $i, $Alldata[14][$j]);
                } else {
                    continue;
                }
                $i++;
            }
            // 下載
            $now = Carbon::now()->format('YmdHis');
            $titlename = $request->input('titlename');
            $filename = rawurlencode($titlename) . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
            header('Cache-Control: max-age=0');

            $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Content-Disposition: attachment;filename="' . $filename . '"', 'Cache-Control: max-age=0'];
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            $callback = function () use ($writer) {
                $file = fopen('php://output', 'r');
                fclose($file);
            };


            return response()->stream($callback, 200, $headers);
        } else {
            return redirect(route('member.login'));
        } // if else
    }

    //test send consume mail
    public static function sendconsumemail($email, $sessemail, $username, $database)
    {
        $dename = DB::table('login')->where('username', \Crypt::decrypt($username))->value('姓名');
        $data = array('email' => $sessemail, 'username' => $username, 'database' => $database, 'name' => $dename);

        Mail::send('mail/consumecheck', $data, function ($message) use ($email) {

            $message->to($email, 'Tutorials Point')->subject('Check Consume data');
            $message->bcc('Vincent6_Yeh@pegatroncorp.com');
            $message->bcc('Tony_Tseng@pegatroncorp.com');
            // $message->attach(public_path() . '/download/LineExample.xlsx');
            $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
        });
    }

    //test send stand mail
    public static function sendstandmail($email, $sessemail, $name, $database)
    {
        $dename = DB::table('login')->where('username', \Crypt::decrypt($name))->value('姓名');
        $data = array('email' => $sessemail, 'username' => $name, 'database' => $database, 'name' => $dename);

        Mail::send('mail/standcheck', $data,  function ($message) use ($email) {
            $message->to($email, 'Tutorials Point')->subject('Check Stand data');
            $message->bcc('Vincent6_Yeh@pegatroncorp.com');
            $message->bcc('Tony_Tseng@pegatroncorp.com');
            $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
        });
    }

    //test send check consume mail
    public static function sendcheckconsume($alldata, $count, $sender)
    {
        $data = array('datas' => $alldata, 'count' => $count);

        Mail::send('mail/markconsume', $data, function ($message) use ($sender) {
            // $email = 't22923200@gmail.com';
            $email = DB::table('login')->where('姓名', $sender)->value('email');
            if ($email !== null) {
                // dd($email);
                $message->to($email, 'Tutorials Point')->subject('RE:Check Consume data');
                $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                $message->bcc('Tony_Tseng@pegatroncorp.com');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            }
        });
    }


    //test send check consume mail
    public static function sendcheckstand($alldata, $count, $sender)
    {
        $data = array('datas' => $alldata, 'count' => $count);

        Mail::send('mail/markstand', $data, function ($message) use ($sender) {
            // $email = 't22923200@gmail.com';
            $email = DB::table('login')->where('姓名', $sender)->value('email');
            if ($email !== null) {

                $message->to($email, 'Tutorials Point')->subject('RE:Check Stand data');
                $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                $message->bcc('Tony_Tseng@pegatroncorp.com');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            }
        });
    }


    //load re-check consume
    public function loadconsume(Request $request)
    {
        if (Session::has('username')) {
            $datas = DB::table('consumptive_material')
                ->join('月請購_單耗', function ($join) {
                    $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
                })
                ->where('狀態', '待重畫')->get();
            return \Response::json(['datas' => $datas]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }



    //load re-check stand
    public function loadstand(Request $request)
    {
        if (Session::has('username')) {
            $datas = DB::table('consumptive_material')
                ->join('月請購_站位', function ($join) {
                    $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
                })
                ->where('狀態', '待重畫')->get();
            return \Response::json(['datas' => $datas]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }
}
