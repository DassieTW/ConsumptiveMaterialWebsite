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

class MonthController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /*public function index()
    {
        //
        if(Session::has('username'))
        {
            return view('month.index');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//匯入非月請購頁面
    public function importnotmonth(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.importnotmonth')->with(['client' => 客戶別::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//SRM單數量頁面
    public function srm(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.srm')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//匯入月請購頁面
    public function importmonth(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.importmonth')->with(['client' => 客戶別::cursor()])
            ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//料號單耗(新增)頁面
    public function consumeadd(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.consumeadd')->with(['client' => 客戶別::cursor()])
            ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//站位人力(新增)頁面
    public function standadd(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.standadd')->with(['client' => 客戶別::cursor()])
            ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//料號單耗(查詢與修改)頁面
    public function consume(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.consume')->with(['client' => 客戶別::cursor()])
            ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()])->with(['send' => 發料部門::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//站位人力(查詢與修改)頁面
    public function stand(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.stand')->with(['client' => 客戶別::cursor()])
            ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()])->with(['send' => 發料部門::cursor()]);;
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//請購單頁面
    public function buylist(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.buylist')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);;
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

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
                    })->wherenull('月請購_單耗.deleted_at')
                    ->where('consumptive_material.料號', 'like', $number . '%')
                    ->where('狀態', '已完成')->get();
            } else {
                $datas = DB::table('consumptive_material')
                    ->join('月請購_單耗', function ($join) {
                        $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
                    })->wherenull('月請購_單耗.deleted_at')
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
                    })->wherenull('月請購_站位.deleted_at')
                    ->where('consumptive_material.料號', 'like', $number . '%')
                    ->where('狀態', '已完成')->get();
            } else {
                $datas = DB::table('consumptive_material')
                    ->join('月請購_站位', function ($join) {
                        $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
                    })->wherenull('月請購_站位.deleted_at')
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
            $now = Carbon::now();
            $count = $request->input('count');
            $client = $request->input('client');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $number = $request->input('number');
            $amount = $request->input('amount');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $database = $request->session()->get('database');
            $database = Hash::make($database);
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
                return \Response::json(['message' => $count, 'database' => $database]/* Status code here default is 200 ok*/);
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
                                '畫押信箱' => $email, 'updated_at' => $now, '單耗' => $amount[$i]
                            ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                }
                return \Response::json(['message' => $count, 'database' => $database, 'status' => 201], /* Status code here default is 200 ok*/);
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
            $now = Carbon::now();
            $count = $request->input('count');
            $client = $request->input('data5');
            $machine = $request->input('data6');
            $production = $request->input('data7');
            $number = $request->input('data0');
            $nowpeople = $request->input('data8');
            $nowline = $request->input('data9');
            $nowclass = $request->input('data10');
            $nowdayneed = $request->input('data11');
            $nowchange = $request->input('data12');
            $nextpeople = $request->input('data14');
            $nextline = $request->input('data15');
            $nextclass = $request->input('data16');
            $nextdayneed = $request->input('data17');
            $nextchange = $request->input('data18');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $database = $request->session()->get('database');
            $database = Hash::make($database);
            //delete
            if ($select == "刪除") {
                for ($i = 0; $i < $count; $i++) {
                    DB::beginTransaction();
                    try {
                        月請購_站位::where('客戶別', $client[$i])
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
                return \Response::json(['message' => $count, 'database' => $database]/* Status code here default is 200 ok*/);
            }
            //change
            else if ($select == "更新") {
                for ($i = 0; $i < $count; $i++) {
                    DB::beginTransaction();
                    try {
                        月請購_站位::where('客戶別', $client[$i])
                            ->where('機種', $machine[$i])
                            ->where('製程', $production[$i])
                            ->where('料號', $number[$i])
                            ->update([
                                '狀態' => "待畫押", '畫押工號' => $jobnumber,
                                '畫押信箱' => $email, 'updated_at' => $now, '當月站位人數' => $nowpeople[$i], '當月開線數' => $nowline[$i],
                                '當月開班數' => $nowclass[$i], '當月每人每日需求量' => $nowdayneed[$i], '下月站位人數' => $nextpeople[$i],
                                '下月開線數' => $nextline[$i], '下月開班數' => $nextclass[$i], '下月每人每日需求量' => $nextdayneed[$i],
                                '當月每日更換頻率' => $nowchange[$i], '下月每日更換頻率' => $nextchange[$i]
                            ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                }
                return \Response::json(['message' => $count, 'database' => $database, 'status' => 201], /* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //料號單耗(新增)
    public function consumenew(Request $request)
    {
        Session::forget('number');
        Session::forget('client');
        Session::forget('machine');
        Session::forget('production');
        Session::forget('name');
        Session::forget('format');
        Session::forget('unit');
        Session::forget('lt');
        Session::forget('nowmps');
        Session::forget('nowday');
        Session::forget('nextmps');
        Session::forget('nextday');

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

                    if ($name !== null && $format !== null) {
                        Session::put('number', $number);
                        Session::put('client', $client);
                        Session::put('machine', $machine);
                        Session::put('production', $production);
                        Session::put('name', $name);
                        Session::put('format', $format);
                        Session::put('unit', $unit);
                        Session::put('lt', $lt);
                        Session::put('nowmps', $nowmps);
                        Session::put('nowday', $nowday);
                        Session::put('nextmps', $nextmps);
                        Session::put('nextday', $nextday);
                        Session::put('consume', $number);
                        return \Response::json([]/* Status code here default is 200 ok*/);
                    }
                    //沒有料號
                    else {
                        return \Response::json([], 420/* Status code here default is 200 ok*/);
                    }
                }
                //料號長度不為12
                else {
                    return \Response::json([], 421/* Status code here default is 200 ok*/);
                }
            } else {
                return view('month.consumeadd');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //站位人力(新增)
    public function standnew(Request $request)
    {
        Session::forget('number');
        Session::forget('client');
        Session::forget('machine');
        Session::forget('production');
        Session::forget('name');
        Session::forget('unit');
        Session::forget('mpq');
        Session::forget('lt');

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

                    if ($name !== null && $mpq !== null) {
                        Session::put('number', $number);
                        Session::put('client', $client);
                        Session::put('machine', $machine);
                        Session::put('production', $production);
                        Session::put('name', $name);
                        Session::put('unit', $unit);
                        Session::put('mpq', $mpq);
                        Session::put('lt', $lt);
                        Session::put('stand', $number);
                        return \Response::json([]/* Status code here default is 200 ok*/);
                    }
                    //沒有料號
                    else {
                        return \Response::json([], 420/* Status code here default is 200 ok*/);
                    }
                }
                //料號長度不為12
                else {
                    return \Response::json([], 421/* Status code here default is 200 ok*/);
                }
            } else {
                return view('month.standadd');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    /*//料號單耗(新增)添加頁面
    public function consumenewok(Request $request)
    {
        if (Session::has('username'))
        {
            if(Session::has('consume'))
            {
                Session::forget('consume');
                return view("month.consumenew");
            }
            else
            {
                return redirect(route('month.consumeadd'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//站位人力(新增)添加頁面
    public function standnewok(Request $request)
    {
        if (Session::has('username'))
        {
            if(Session::has('stand'))
            {
                Session::forget('stand');
                return view("month.standnew");
            }
            else
            {
                return redirect(route('month.standadd'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    //提交料號單耗
    public function consumenewsubmit(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('amount') !== null) {
                $number = $request->input('number');
                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $amount = $request->input('amount');
                $jobnumber = $request->input('jobnumber');
                $email = $request->input('email');
                $database = $request->session()->get('database');
                $database = Hash::make($database);
                $test = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)
                    ->where('機種', $machine)->where('製程', $production)->value('單耗');

                $delete = 月請購_單耗::onlyTrashed()
                    ->where('料號', $number)->where('客戶別', $client)
                    ->where('機種', $machine)->where('製程', $production)
                    ->get();

                if (!$delete->isEmpty()) {
                    DB::table('月請購_單耗')
                        ->where('料號', $number)->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)
                        ->update([
                            'created_at' =>  Carbon::now(), 'deleted_at' => null, 'updated_at' => null, '單耗' => $amount, '畫押工號' => $jobnumber, '畫押信箱' => $email, '狀態' => "待畫押"
                        ]);
                    return \Response::json(['database' => $database]/* Status code here default is 200 ok*/);
                } else {
                    if ($test === null) {
                        DB::beginTransaction();
                        try {
                            DB::table('月請購_單耗')
                                ->insert([
                                    '料號' => $number, '客戶別' => $client, '機種' => $machine, '製程' => $production, '單耗' => $amount, 'created_at' => Carbon::now(), '畫押工號' => $jobnumber, '畫押信箱' => $email, '狀態' => "待畫押"
                                ]);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
                        }
                        return \Response::json(['database' => $database]/* Status code here default is 200 ok*/);
                    } else {
                        return \Response::json(['message' => 'repeat'], 420/* Status code here default is 200 ok*/);
                    }
                }
            } else {
                return redirect(route('month.consumeadd'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //提交站位人力
    public function standnewsubmit(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('nowpeople') !== null) {
                $number = $request->input('number');
                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $nowpeople = $request->input('nowpeople');
                $nowline = $request->input('nowline');
                $nowclass = $request->input('nowclass');
                $nowuse = $request->input('nowuse');
                $nowchange = $request->input('nowchange');
                $nextpeople = $request->input('nextpeople');
                $nextline = $request->input('nextline');
                $nextclass = $request->input('nextclass');
                $nextuse = $request->input('nextuse');
                $nextchange = $request->input('nextchange');
                $email = $request->input('email');
                $jobnumber = $request->input('jobnumber');
                $database = $request->session()->get('database');
                $database = Hash::make($database);

                $test = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)
                    ->where('機種', $machine)->where('製程', $production)->value('當月站位人數');

                $delete = 月請購_站位::onlyTrashed()
                    ->where('料號', $number)->where('客戶別', $client)
                    ->where('機種', $machine)->where('製程', $production)
                    ->get();

                if (!$delete->isEmpty()) {
                    DB::table('月請購_站位')
                        ->where('料號', $number)->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)
                        ->update([
                            'created_at' =>  Carbon::now(), 'deleted_at' => null, 'updated_at' => null, '當月站位人數' => $nowpeople, '當月開線數' => $nowline, '當月開班數' => $nowclass, '當月每人每日需求量' => $nowuse, '當月每日更換頻率' => $nowchange, '下月站位人數' => $nextpeople, '下月開線數' => $nextline, '下月開班數' => $nextclass, '下月每人每日需求量' => $nextuse, '下月每日更換頻率' => $nextchange, '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                        ]);
                    return \Response::json(['database' => $database]/* Status code here default is 200 ok*/);
                } else {

                    if ($test === null) {
                        DB::beginTransaction();
                        try {
                            DB::table('月請購_站位')
                                ->insert([
                                    '料號' => $number, '客戶別' => $client, '機種' => $machine, '製程' => $production, '當月站位人數' => $nowpeople, '當月開線數' => $nowline, '當月開班數' => $nowclass, '當月每人每日需求量' => $nowuse, '當月每日更換頻率' => $nowchange, '下月站位人數' => $nextpeople, '下月開線數' => $nextline, '下月開班數' => $nextclass, '下月每人每日需求量' => $nextuse, '下月每日更換頻率' => $nextchange, 'created_at' => Carbon::now(), '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                                ]);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
                        }
                        return \Response::json(['database' => $database]/* Status code here default is 200 ok*/);
                    } else {
                        return \Response::json(['message' => 'repeat'], 420/* Status code here default is 200 ok*/);
                    }
                }
            } else {
                return redirect(route('month.standadd'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //非月請購查詢 or 添加
    public function notmonthsearchoradd(Request $request)
    {
        if (Session::has('username')) {
            //search
            if ($request->has('search')) {


                if ($request->input('client') === null && $request->input('number') === null) {
                    $datas = DB::table('consumptive_material')
                        ->join('非月請購', function ($join) {
                            $join->on('非月請購.料號', '=', 'consumptive_material.料號');
                        })->select('非月請購.*', 'consumptive_material.品名')->get();
                    return view('month.notmonthsearchok')->with(['data' => $datas]);
                } else if ($request->input('client') !== null && $request->input('number') === null) {
                    $datas = DB::table('consumptive_material')
                        ->join('非月請購', function ($join) {
                            $join->on('非月請購.料號', '=', 'consumptive_material.料號');
                        })->select('非月請購.*', 'consumptive_material.品名')->get();
                    return view('month.notmonthsearchok')->with(['data' => $datas->where('客戶別', $request->input('client'))]);
                } else if ($request->input('client') === null && $request->input('number') !== null) {
                    $datas = DB::table('consumptive_material')
                        ->join('非月請購', function ($join) {
                            $join->on('非月請購.料號', '=', 'consumptive_material.料號');
                        })->select('非月請購.*', 'consumptive_material.品名')
                        ->where('非月請購.料號', 'like', $request->input('number') . '%')->get();
                    return view('month.notmonthsearchok')->with(['data' => $datas]);
                } else if ($request->input('client') !== null && $request->input('number') !== null) {
                    $datas = DB::table('consumptive_material')
                        ->join('非月請購', function ($join) {
                            $join->on('非月請購.料號', '=', 'consumptive_material.料號');
                        })->select('非月請購.*', 'consumptive_material.品名')
                        ->where('非月請購.料號', 'like', $request->input('number') . '%')->get();

                    return view('month.notmonthsearchok')->with(['data' => $datas->where('客戶別', $request->input('client'))]);
                }
            }

            //add
            else if ($request->has('add')) {
                if ($request->input('client') === null && $request->input('number') === null) {
                    return back()->withErrors([
                        'client' => trans('validation.required'),
                        'number' => trans('validation.required'),
                    ]);
                } else if ($request->input('client') !== null && $request->input('number') === null) {
                    return back()->withErrors([
                        'number' => trans('validation.required'),
                    ]);
                } else if ($request->input('client') === null && $request->input('number') !== null) {
                    return back()->withErrors([
                        'client' => trans('validation.required'),
                    ]);
                } else if ($request->input('client') !== null && $request->input('number') !== null) {
                    if (strlen($request->input('number')) !== 12) {
                        return back()->withErrors([
                            'number' => trans('validation.regex'),
                        ]);
                    } else {
                        $number = $request->input('number');
                        $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
                        $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
                        $month = DB::table('consumptive_material')->where('料號', $number)->value('月請購');
                        if ($name !== NULL && $unit !== NULL) {

                            return view('month.notmonthadd')
                                ->with('client', $request->input('client'))
                                ->with('number', $number)
                                ->with('name', $name)
                                ->with('unit', $unit)
                                ->with('month', $month);
                        } else {
                            return back()->withErrors([
                                'number' => trans('monthlyPRpageLang.noisn'),
                            ]);
                        }
                    }
                }
            } else {
                return redirect(route('month.importnotmonth'));
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
            $names = DB::table('製程')->pluck('製程');
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
            $record = 0;
            for ($i = 0; $i < $count; $i++) {
                DB::beginTransaction();
                try {
                    DB::table('MPS')
                        ->where('客戶別', $clients[$i])
                        ->where('機種', $machines[$i])
                        ->where('製程', $productions[$i])
                        ->delete();
                    $record++;
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
                DB::rollback();
            }
            if ($record == $count) {
                $record = 0;
                for ($i = 0; $i < $count; $i++) {
                    DB::beginTransaction();
                    try {
                        DB::table('MPS')
                            ->where('客戶別', $clients[$i])
                            ->where('製程', $productions[$i])
                            ->delete();
                        $record++;
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                }
                return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //匯入月請購資料
    public function monthadd(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $machine = $request->input('machine');
            $count = $request->input('count');
            $time = Carbon::now();
            $record = 0;
            for ($i = 0; $i < $count; $i++) {
                $test = DB::table('MPS')->where('客戶別', $request->input('client'))->where('機種', $request->input('machine'))
                    ->where('製程', $request->input('production' . $i))->value('下月MPS');
                if ($test === null) {
                    DB::table('MPS')
                        ->insert([
                            '客戶別' => $client, '機種' => $machine, '製程' => $request->input('production' . $i), '下月MPS' => $request->input('nextmps' . $i), '下月生產天數' => $request->input('nextday' . $i), '本月MPS' => $request->input('nowmps' . $i), '本月生產天數' => $request->input('nowday' . $i), '填寫時間' => $time
                        ]);


                    $record++;
                } else {
                    DB::table('MPS')
                        ->where('客戶別', $request->input('client'))
                        ->where('機種', $request->input('machine'))
                        ->where('製程', $request->input('production' . $i))
                        ->update([
                            '下月MPS' => $request->input('nextmps' . $i), '下月生產天數' => $request->input('nextday' . $i),
                            '本月MPS' => $request->input('nowmps' . $i), '本月生產天數' => $request->input('nowday' . $i), '填寫時間' => $time
                        ]);
                }
            }

            $mess = trans('monthlyPRpageLang.total') . $record . trans('monthlyPRpageLang.record')
                . trans('templateWords.monthly') . trans('monthlyPRpageLang.new')
                . trans('monthlyPRpageLang.success');
            echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/month';
                </script>");
        } else {
            return redirect(route('member.login'));
        }
    }

    //匯入非月請購資料
    public function notmonthadd(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('amount') !== null) {
                $client = $request->input('client');
                $number = $request->input('number');
                $amount = $request->input('amount');
                $month = $request->input('month');
                $sxb = $request->input('sxb');
                $time = Carbon::now();
                $say = $request->input('say');
                $reason = $request->input('reason');
                $test = DB::table('在途量')->where('客戶', $client)->where('料號', $number)->value('請購數量');

                if ($month === "是") {
                    //no reason
                    if ($say === null || $reason === null) {
                        return \Response::json(['message' => 'noreason'], 420/* Status code here default is 200 ok*/);
                    } else {
                        DB::beginTransaction();
                        try {

                            $say = $reason . ' ' . $say;
                            if ($test === null) {
                                DB::table('在途量')
                                    ->insert(['客戶' => $client, '料號' => $number, '請購數量' => $amount]);
                            } else {
                                DB::table('在途量')
                                    ->where('客戶', $client)->where('料號', $number)
                                    ->update(['請購數量' => $test + $amount]);
                            }

                            DB::table('非月請購')
                                ->insert([
                                    '客戶別' => $client, '料號' => $number, '請購數量' => $amount, '上傳時間' => $time, '說明' => $say, 'SXB單號' => $sxb
                                ]);
                            DB::commit();

                            return \Response::json([]/* Status code here default is 200 ok*/);
                        } catch (\Exception $e) {
                            DB::rollback();
                            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
                        }
                    }
                } else {
                    DB::beginTransaction();
                    try {

                        $say = $reason . ' ' . $say;
                        if ($test === null) {
                            DB::table('在途量')
                                ->insert(['客戶' => $client, '料號' => $number, '請購數量' => $amount]);
                        } else {
                            DB::table('在途量')
                                ->where('客戶', $client)->where('料號', $number)
                                ->update(['請購數量' => $test + $amount]);
                        }

                        DB::table('非月請購')
                            ->insert([
                                '客戶別' => $client, '料號' => $number, '請購數量' => $amount, '上傳時間' => $time, '說明' => $say, 'SXB單號' => $sxb
                            ]);
                        DB::commit();

                        return \Response::json([]/* Status code here default is 200 ok*/);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
                    }
                }
            } else {
                return redirect(route('month.importnotmonth'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    /*//在途量(查詢)頁面
    public function transit(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.transit')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//SXB單(查詢)頁面
    public function sxb(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.sxb')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

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
            if ($client == "所有客戶" || $client == '所有客户' || $client == 'ALL Client') {

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
            } else {
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
        }
    }

    //請購單-提交
    public function buylistsubmit(Request $request)
    {
        if (Session::has('username')) {
            //submit

            $now = Carbon::now();
            $count = $request->input('count');
            $record = 0;
            $srm = $request->input('srm');
            $client = $request->input('client');
            $number = $request->input('number');
            $name = $request->input('name');
            $moq = $request->input('moq');
            $nextneed = $request->input('nextneed');
            $nowneed = $request->input('nowneed');
            $safe = $request->input('safe');
            $price = $request->input('price');
            $money = $request->input('money');
            $rate = $request->input('rate');
            $amount = $request->input('amount');
            $stock = $request->input('stock');
            $buyamount = $request->input('buyamount');
            $realneed = $request->input('realneed');
            $buymoney = $request->input('buymoney');
            $buyper = $request->input('buyper');
            $needmoney = $request->input('needmoney');
            $needper = $request->input('needper');
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    DB::table('請購單')
                        ->insert([
                            'SRM單號' => $srm[$i], '客戶' => $client[$i], '料號' => $number[$i], '品名' => $name[$i], 'MOQ' => $moq[$i], '下月需求' => $nextneed[$i], '當月需求' => $nowneed[$i], '安全庫存' => $safe[$i], '單價' => $price[$i], '幣別' => $money[$i], '匯率' => $rate[$i], '在途數量' => $amount[$i], '現有庫存' => $stock[$i], '本次請購數量' => $buyamount[$i], '實際需求' => $realneed[$i], '請購金額' => $buymoney[$i], '請購占比' => $buyper[$i], '需求金額' => $needmoney[$i], '需求占比' => $needper[$i], '請購時間' => $now
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

    //單耗上傳
    public function uploadconsume(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimes:xls,xlsx'
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


    //單耗上傳資料新增至資料庫
    public function insertuploadconsume(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $client =  $request->input('client');
            $number =  $request->input('number');
            $amount =  $request->input('amount');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $database = $request->session()->get('database');
            $database = Hash::make($database);
            $bool = true;
            $record =  0;
            for ($i = 0; $i < $count; $i++) {
                $test = DB::table('月請購_單耗')->where('料號', $number[$i])->where('客戶別', $client[$i])
                    ->where('機種', $machine[$i])->where('製程', $production)->value('單耗');

                $delete = DB::table('月請購_單耗')->where('料號', $number[$i])->where('客戶別', $client[$i])
                    ->where('機種', $machine[$i])->where('製程', $production)->value('deleted_at');

                //data no delete
                if ($delete === null) {
                    //new data
                    if ($test === null) {
                        DB::beginTransaction();
                        try {
                            DB::table('月請購_單耗')
                                ->insert([
                                    '料號' => $number[$i], '客戶別' => $client[$i], '機種' => $machine[$i], '製程' => $production[$i], '單耗' => $amount[$i], 'created_at' => Carbon::now(), '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                                ]);
                        } catch (\Exception $e) {
                            DB::rollback();
                            $bool = false;
                            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                        }
                        DB::rollback();
                        $record++;
                    }
                    //data repeat
                    else {
                        $bool = false;
                        $i = $i + 1;
                        return \Response::json(['message' => $i], 420/* Status code here default is 200 ok*/);
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        DB::table('月請購_單耗')
                            ->where('料號', $number[$i])->where('客戶別', $client[$i])
                            ->where('機種', $machine[$i])->where('製程', $production[$i])
                            ->update([
                                'created_at' =>  Carbon::now(), 'deleted_at' => null, 'updated_at' => null, '單耗' => $amount[$i], '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                            ]);
                        $record++;
                    } catch (\Exception $e) {
                        DB::rollback();
                        $bool = false;
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                    DB::rollback();
                }
            }

            if ($record == $count && $bool == true) {
                $record = 0;
                for ($i = 0; $i < $count; $i++) {
                    $test = DB::table('月請購_單耗')->where('料號', $number[$i])->where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])->where('製程', $production)->value('單耗');

                    $delete = DB::table('月請購_單耗')->where('料號', $number[$i])->where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])->where('製程', $production)->value('deleted_at');

                    //data no delete
                    if ($delete === null) {
                        //new data
                        if ($test === null) {
                            DB::beginTransaction();
                            try {
                                DB::table('月請購_單耗')
                                    ->insert([
                                        '料號' => $number[$i], '客戶別' => $client[$i], '機種' => $machine[$i], '製程' => $production[$i], '單耗' => $amount[$i], 'created_at' => Carbon::now(), '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                                    ]);
                                $record++;
                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();
                                $bool = false;
                                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                            }
                        }
                        //data repeat
                        else {
                            $bool = false;
                            $i = $i + 1;
                            return \Response::json(['message' => $i], 420/* Status code here default is 200 ok*/);
                        }
                    } else {
                        DB::beginTransaction();
                        try {
                            DB::table('月請購_單耗')
                                ->where('料號', $number[$i])->where('客戶別', $client[$i])
                                ->where('機種', $machine[$i])->where('製程', $production[$i])
                                ->update([
                                    'created_at' =>  Carbon::now(), 'deleted_at' => null, 'updated_at' => null, '單耗' => $amount[$i], '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email, '紀錄' => null
                                ]);
                            $record++;
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $bool = false;
                            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                        }
                    }
                }

                return \Response::json(['message' => $record, 'database' => $database]/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //站位上傳
    public function uploadstand(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimes:xls,xlsx'
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


    //站位上傳資料新增至資料庫
    public function insertuploadstand(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $client =  $request->input('client');
            $number =  $request->input('number');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $nowpeople = $request->input('nowpeople');
            $nowline = $request->input('nowline');
            $nowclass = $request->input('nowclass');
            $nowuse = $request->input('nowuse');
            $nowchange = $request->input('nowchange');
            $nextpeople = $request->input('nextpeople');
            $nextline = $request->input('nextline');
            $nextclass = $request->input('nextclass');
            $nextuse = $request->input('nextuse');
            $nextchange = $request->input('nextchange');
            $database = $request->session()->get('database');
            $database = Hash::make($database);
            $bool = true;
            $record = 0;
            for ($i = 0; $i < $count; $i++) {
                $test = DB::table('月請購_站位')->where('料號', $number[$i])->where('客戶別', $client[$i])
                    ->where('機種', $machine[$i])->where('製程', $production)->value('當月站位人數');

                $delete = DB::table('月請購_站位')->where('料號', $number[$i])->where('客戶別', $client[$i])
                    ->where('機種', $machine[$i])->where('製程', $production)->value('deleted_at');

                //data no delete
                if ($delete === null) {
                    //new data
                    if ($test === null) {
                        DB::beginTransaction();
                        try {
                            DB::table('月請購_站位')
                                ->insert([
                                    '料號' => $number[$i], '客戶別' => $client[$i], '機種' => $machine[$i], '製程' => $production[$i], '當月站位人數' => $nowpeople[$i], '當月開線數' => $nowline[$i], '當月開班數' => $nowclass[$i], '當月每人每日需求量' => $nowuse[$i], '當月每日更換頻率' => $nowchange[$i], '下月站位人數' => $nextpeople[$i], '下月開線數' => $nextline[$i], '下月開班數' => $nextclass[$i], '下月每人每日需求量' => $nextuse[$i], '下月每日更換頻率' => $nextchange[$i], 'created_at' => Carbon::now(), '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                                ]);
                        } catch (\Exception $e) {
                            DB::rollback();
                            $bool = false;
                            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                        }
                        DB::rollback();
                        $record++;
                    }
                    //data repeat
                    else {
                        $bool = false;
                        $i = $i + 1;
                        return \Response::json(['message' => $i], 420/* Status code here default is 200 ok*/);
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        DB::table('月請購_站位')
                            ->where('料號', $number[$i])->where('客戶別', $client[$i])
                            ->where('機種', $machine[$i])->where('製程', $production[$i])
                            ->update([
                                'created_at' =>  Carbon::now(), 'deleted_at' => null, 'updated_at' => null, '當月站位人數' => $nowpeople[$i], '當月開線數' => $nowline[$i], '當月開班數' => $nowclass[$i], '當月每人每日需求量' => $nowuse[$i], '當月每日更換頻率' => $nowchange[$i], '下月站位人數' => $nextpeople[$i], '下月開線數' => $nextline[$i], '下月開班數' => $nextclass[$i], '下月每人每日需求量' => $nextuse[$i], '下月每日更換頻率' => $nextchange[$i], '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                            ]);
                        $record++;
                    } catch (\Exception $e) {
                        DB::rollback();
                        $bool = false;
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                    DB::rollback();
                }
            }

            if ($record == $count && $bool == true) {
                $record = 0;
                for ($i = 0; $i < $count; $i++) {
                    $test = DB::table('月請購_站位')->where('料號', $number[$i])->where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])->where('製程', $production)->value('當月站位人數');

                    $delete = DB::table('月請購_站位')->where('料號', $number[$i])->where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])->where('製程', $production)->value('deleted_at');

                    //data no delete
                    if ($delete === null) {
                        //new data
                        if ($test === null) {
                            DB::beginTransaction();
                            try {
                                DB::table('月請購_站位')
                                    ->insert([
                                        '料號' => $number[$i], '客戶別' => $client[$i], '機種' => $machine[$i], '製程' => $production[$i], '當月站位人數' => $nowpeople[$i], '當月開線數' => $nowline[$i], '當月開班數' => $nowclass[$i], '當月每人每日需求量' => $nowuse[$i], '當月每日更換頻率' => $nowchange[$i], '下月站位人數' => $nextpeople[$i], '下月開線數' => $nextline[$i], '下月開班數' => $nextclass[$i], '下月每人每日需求量' => $nextuse[$i], '下月每日更換頻率' => $nextchange[$i], 'created_at' => Carbon::now(), '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email
                                    ]);
                                $record++;
                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();
                                $bool = false;
                                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                            }
                        }
                        //data repeat
                        else {
                            $bool = false;
                            $i = $i + 1;
                            return \Response::json(['message' => $i], 420/* Status code here default is 200 ok*/);
                        }
                    } else {
                        DB::beginTransaction();
                        try {
                            DB::table('月請購_站位')
                                ->where('料號', $number[$i])->where('客戶別', $client[$i])
                                ->where('機種', $machine[$i])->where('製程', $production[$i])
                                ->update([
                                    'created_at' =>  Carbon::now(), 'deleted_at' => null, 'updated_at' => null, '當月站位人數' => $nowpeople[$i], '當月開線數' => $nowline[$i], '當月開班數' => $nowclass[$i], '當月每人每日需求量' => $nowuse[$i], '當月每日更換頻率' => $nowchange[$i], '下月站位人數' => $nextpeople[$i], '下月開線數' => $nextline[$i], '下月開班數' => $nextclass[$i], '下月每人每日需求量' => $nextuse[$i], '下月每日更換頻率' => $nextchange[$i], '狀態' => "待畫押", '畫押工號' => $jobnumber, '畫押信箱' => $email, '紀錄' => null
                                ]);
                            $record++;
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $bool = false;
                            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                        }
                    }
                }

                return \Response::json(['message' => $record, 'database' => $database]/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //非月請購上傳
    public function uploadnotmonth(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimes:xls,xlsx'
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

    //非月請購上傳資料新增至資料庫
    public function insertuploadnotmonth(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $now = Carbon::now();
            $record = 0;
            $client =  $request->input('client');
            $number =  $request->input('number');
            $amount =  $request->input('amount');
            $month = $request->input('month');
            $say = $request->input('say');
            $sxb = $request->input('sxb');
            for ($i = 0; $i < $count; $i++) {
                $test = DB::table('在途量')->where('客戶', $client[$i])->where('料號', $number[$i])->value('請購數量');
                if ($month[$i] === '是') {
                    DB::beginTransaction();
                    try {
                        if ($test === null) {
                            DB::table('在途量')
                                ->insert(['客戶' => $client[$i], '料號' => $number[$i], '請購數量' => $amount[$i]]);
                        } else {
                            DB::table('在途量')
                                ->where('客戶', $client[$i])->where('料號', $number[$i])
                                ->update(['請購數量' => $test + $amount[$i]]);
                        }

                        DB::table('非月請購')
                            ->insert([
                                '料號' => $number[$i], '客戶別' => $client[$i], '請購數量' => $amount[$i], '上傳時間' => $now, '說明' => $say[$i],
                                'SXB單號' => $sxb[$i]
                            ]);
                        DB::commit();
                        $record++;
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        if ($test === null) {
                            DB::table('在途量')
                                ->insert(['客戶' => $client[$i], '料號' => $number[$i], '請購數量' => $amount[$i]]);
                        } else {
                            DB::table('在途量')
                                ->where('客戶', $client[$i])->where('料號', $number[$i])
                                ->update(['請購數量' => $test + $amount[$i]]);
                        }

                        DB::table('非月請購')
                            ->insert([
                                '料號' => $number[$i], '客戶別' => $client[$i], '請購數量' => $amount[$i], '上傳時間' => $now, '說明' => $say[$i],
                                'SXB單號' => $sxb[$i]
                            ]);
                        DB::commit();
                        $record++;
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                }
            }

            return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }


    //月請購上傳
    public function uploadmonth(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimes:xls,xlsx'
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

    //月請購上傳資料新增至資料庫
    public function insertuploadmonth(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $now = Carbon::now();
            $record = 0;
            $client =  $request->input('client');
            $machine =  $request->input('machine');
            $production = $request->input('production');
            $nowmps = $request->input('nowmps');
            $nowday = $request->input('nowday');
            $nextmps = $request->input('nextmps');
            $nextday = $request->input('nextday');
            for ($i = 0; $i < $count; $i++) {
                $test = DB::table('MPS')->where('客戶別', $client[$i])->where('機種', $machine[$i])->where('製程', $production[$i])->value('本月MPS');
                if ($test === null) {
                    DB::beginTransaction();
                    try {

                        DB::table('MPS')
                            ->insert([
                                '機種' => $machine[$i], '客戶別' => $client[$i], '製程' => $production[$i], '本月MPS' => $nowmps[$i], '本月生產天數' => $nowday[$i], '下月MPS' => $nextmps[$i], '下月生產天數' => $nextday[$i], '填寫時間' => $now
                            ]);
                        DB::commit();
                        $record++;
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                } else {
                    DB::beginTransaction();
                    try {

                        DB::table('MPS')
                            ->where('客戶別', $client[$i])->where('機種', $machine[$i])->where('製程', $production[$i])
                            ->update(['本月MPS' => $nowmps[$i], '本月生產天數' => $nowday[$i], '下月MPS' => $nextmps[$i], '下月生產天數' => $nextday[$i], '填寫時間' => $now]);
                        DB::commit();
                        $record++;
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                }
            }

            return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }


    /*//test單耗畫押
    public function testconsume(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.testconsume')->with(['data' => 月請購_單耗::cursor()->where('狀態',"待畫押")]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //test站位畫押
    public function testsand(Request $request)
    {
        if(Session::has('username'))
        {
            return view('month.teststand')->with(['data' => 月請購_站位::cursor()->where('狀態',"待畫押")]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    //test單耗畫押提交
    public function testsubmit(Request $request)
    {
        if (Session::has('username')) {
            $now = Carbon::now();
            $count = $request->input('count');
            $client = $request->input('client');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $number = $request->input('number');
            $amount = $request->input('amount');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $check = $request->input('check');

            for ($i = 0; $i < $count; $i++) {
                DB::beginTransaction();
                try {
                    $update = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('updated_at');
                    $record = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('紀錄');
                    if ($check[$i] == 1) {

                        if ($record == "畫押完成") {
                            $record = ' 工號: ' . $jobnumber . ' 時間: ' . $now . ' 修改此筆單耗 ' . ';';
                        } else {
                            $record = $record . ' 工號: ' . $jobnumber . ' 時間: ' . $now . ' 修改此筆單耗 ' . ';';
                        }
                    } else {
                        $record = '畫押完成';
                    }
                    月請購_單耗::where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])
                        ->where('製程', $production[$i])
                        ->where('料號', $number[$i])
                        ->update([
                            '狀態' => "已完成", '畫押工號' => $jobnumber,
                            '畫押信箱' => $email, '畫押時間' => $now, '單耗' => $amount[$i], 'updated_at' => $update, '紀錄' => $record
                        ]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
            }

            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }

    //test站位畫押提交
    public function teststandsubmit(Request $request)
    {
        if (Session::has('username')) {
            $now = Carbon::now();
            $count = $request->input('count');
            $client = $request->input('client');
            $machine = $request->input('machine');
            $production = $request->input('production');
            $number = $request->input('number');
            $nowpeople = $request->input('nowpeople');
            $nowline = $request->input('nowline');
            $nowclass = $request->input('nowclass');
            $nowuse = $request->input('nowuse');
            $nowchange = $request->input('nowchange');
            $nextpeople = $request->input('nextpeople');
            $nextline = $request->input('nextline');
            $nextclass = $request->input('nextclass');
            $nextuse = $request->input('nextuse');
            $nextchange = $request->input('nextchange');
            $jobnumber = $request->input('jobnumber');
            $email = $request->input('email');
            $check = $request->input('check');

            for ($i = 0; $i < $count; $i++) {
                DB::beginTransaction();
                try {
                    $update = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('updated_at');
                    $record = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)
                        ->where('機種', $machine)->where('製程', $production)->value('紀錄');
                    if ($check[$i] == 1) {

                        if ($record == "畫押完成") {
                            $record = ' 工號: ' . $jobnumber . ' 時間: ' . $now . ' 修改此筆站位 ' . ';';
                        } else {
                            $record = $record . ' 工號: ' . $jobnumber . ' 時間: ' . $now . ' 修改此筆站位 ' . ';';
                        }
                    } else {
                        $record = '畫押完成';
                    }
                    月請購_站位::where('客戶別', $client[$i])
                        ->where('機種', $machine[$i])
                        ->where('製程', $production[$i])
                        ->where('料號', $number[$i])
                        ->update([
                            '狀態' => "已完成", '畫押工號' => $jobnumber,
                            '畫押信箱' => $email, '畫押時間' => $now, '當月站位人數' => $nowpeople[$i], '當月開線數' => $nowline[$i], '當月開班數' => $nowclass[$i], '當月每人每日需求量' => $nowuse[$i], '當月每日更換頻率' => $nowchange[$i], '下月站位人數' => $nextpeople[$i], '下月開線數' => $nextline[$i], '下月開班數' => $nextclass[$i], '下月每人每日需求量' => $nextuse[$i], '下月每日更換頻率' => $nextchange[$i], 'updated_at' => $update, '紀錄' => $record
                        ]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
            }

            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
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

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $worksheet = $spreadsheet->getActiveSheet();
            $title = $request->input('title');
            $count = $request->input('count');
            //填寫表頭
            for ($i = 0; $i < $title; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title' . $i));
            }

            //填寫內容
            for ($i = 0; $i < $title; $i++) {
                for ($j = 0; $j < $count; $j++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data' . $i . $j));
                }
            }


            // 下載
            $now = Carbon::now()->format('YmdHis');
            $titlename = $request->input('titlename');
            $filename = $titlename . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        } else {
            return redirect(route('member.login'));
        }
    }

    //請購單下載
    public function buylistdownload(Request $request)
    {
        if (Session::has('username')) {
            $reDive = new responseObj();
            $spreadsheet = new Spreadsheet();
            //$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $worksheet = $spreadsheet->getActiveSheet();

            $title = $request->input('title');
            $titlecount = $request->input('titlecount');
            $count = $request->input('count');
            $select = $request->input('select');
            if ($select == '匯出' || $select == '汇出' || $select == 'Export') {
                //填寫表頭
                for ($i = 0; $i < $titlecount; $i++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, 1, $title[$i]);
                }

                //填寫內容
                for ($i = 0; $i < $titlecount; $i++) {
                    for ($j = 0; $j < $count; $j++) {
                        $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data' . $i)[$j]);
                    }
                }


                // 下載
                $now = Carbon::now()->format('YmdHis');
                //rawurlencode('呆滯庫存查詢');
                $filename = rawurlencode('請購單') . $now . '.xlsx';
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
            } else {
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
                    if ($request->input('data' . '13')[$j] > 0) {
                        $worksheet->setCellValueByColumnAndRow(2, $i, $request->input('data' . '2')[$j]);
                        $worksheet->setCellValueByColumnAndRow(13, $i, $request->input('data' . '13')[$j]);
                        $worksheet->setCellValueByColumnAndRow(14, $i, $request->input('data' . '14')[$j]);
                    } else {
                        continue;
                    }
                    $i++;
                }
                // 下載
                $now = Carbon::now()->format('YmdHis');
                //rawurlencode('呆滯庫存查詢');
                $filename = rawurlencode('請購單上傳') . $now . '.xlsx';
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
            }

            return response()->stream($callback, 200, $headers);
        } else {
            return redirect(route('member.login'));
        } // if else
    }
}
