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
use App\Models\發料部門;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\人員信息;
use App\Models\儲位;
use App\Models\Inventory;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class OutboundController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view('outbound.index');
    }

    //領料
    public function pick(Request $request)
    {
        if (Session::has('username')) {

            return view('outbound.pick')->with(['client' => 客戶別::cursor()])
                ->with(['machine' => 機種::cursor()])
                ->with(['production' => 製程::cursor()])
                ->with(['line' => 線別::cursor()])
                ->with(['usereason' => 領用原因::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //退料
    public function back(Request $request)
    {
        if (Session::has('username')) {

            return view('outbound.back')->with(['client' => 客戶別::cursor()])
                ->with(['machine' => 機種::cursor()])
                ->with(['production' => 製程::cursor()])
                ->with(['line' => 線別::cursor()])
                ->with(['backreason' => 退回原因::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //領料單頁面
    public function picklistpage(Request $request)
    {
        if (Session::has('username')) {
            $datas =  DB::table('outbound')
                ->join('consumptive_material', function ($join) {
                    $join->on('outbound.料號', '=', 'consumptive_material.料號')
                    ->where('outbound.發料人員', '=', null);

                })->select('outbound.*','consumptive_material.發料部門')
                 ->get();

            return view('outbound.picklistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //退料單頁面
    public function backlistpage(Request $request)
    {
        if (Session::has('username')) {
            $datas =  DB::table('出庫退料')
                ->join('consumptive_material', function ($join) {
                    $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                    ->where('出庫退料.收料人員', '=', null);

                })->select('出庫退料.*','consumptive_material.發料部門')
                 ->get();

            return view('outbound.backlistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //領料單
    public function picklist(Request $request)
    {
        if (Session::has('username'))
        {
            //刪除領料單
            if($request->has('delete'))
            {
                $list =  $request->input('list');
                DB::table('outbound')
                ->where('領料單號', $request->input('list'))
                ->delete();

                $mess = trans('outboundpageLang.delete').' : '.trans('outboundpageLang.picklistnum')
                .$list.trans('outboundpageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess' );
                window.location.href='/outbound';
                        </script>");

            }
            else
            {
                $list = $request->input('list');
                if($request->input('send') === null)
                {

                    $datas =  DB::table('outbound')
                        ->join('consumptive_material', function ($join) {
                            $join->on('outbound.料號', '=', 'consumptive_material.料號')
                            ->where('outbound.發料人員', '=', null);

                        })->select('outbound.*','consumptive_material.發料部門')
                        ->get();
                }
                else
                {
                    $datas =  DB::table('outbound')
                    ->join('consumptive_material', function ($join) {
                        $join->on('outbound.料號', '=', 'consumptive_material.料號')
                        ->where('outbound.發料人員', '=', null);

                    })
                    ->where('consumptive_material.發料部門', '=', $request->input('send'))
                    ->select('outbound.*','consumptive_material.發料部門')
                    ->get();
                }

                if($request->input('list') === null)
                {
                    return view('outbound.picklist')->with(['number' => $datas])
                        ->with(['data' => $datas])
                        ->with(['people' => 人員信息::cursor()])
                        ->with(['people1' => 人員信息::cursor()]);
                }
                else
                {
                    return view('outbound.picklist')->with(['number' => $datas->where('領料單號',$list)])
                        ->with(['data' => $datas->where('領料單號',$list)])
                        ->with(['people' => 人員信息::cursor()])
                        ->with(['people1' => 人員信息::cursor()]);
                }
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //退料單
    public function backlist(Request $request)
    {
        if (Session::has('username'))
        {
            //刪除退料單
            if($request->has('delete'))
            {
                $list =  $request->input('list');
                DB::table('出庫退料')
                ->where('退料單號', $request->input('list'))
                ->delete();
                $mess = trans('outboundpageLang.delete').' : '.trans('outboundpageLang.backlistnum')
                .$list.trans('outboundpageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess' );
                window.location.href='/outbound';
                        </script>");
            }
            else
            {
                $list = $request->input('list');
                if($request->input('send') === null)
                {

                    $datas =  DB::table('出庫退料')
                    ->join('consumptive_material', function ($join) {
                        $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                        ->where('出庫退料.收料人員', '=', null);

                    })->select('出庫退料.*','consumptive_material.發料部門')
                    ->get();

                }
                else
                {
                    $datas =  DB::table('出庫退料')
                    ->join('consumptive_material', function ($join) {
                        $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                        ->where('出庫退料.收料人員', '=', null);

                    })->select('出庫退料.*','consumptive_material.發料部門')
                    ->get();
                }
                if($request->input('list') === null)
                {
                    return view('outbound.backlist')->with(['number' => $datas])
                        ->with(['data' => $datas])
                        ->with(['people' => 人員信息::cursor()])
                        ->with(['people1' => 人員信息::cursor()])
                        ->with(['position' => 儲位::cursor()]);
                }
                else
                {
                    return view('outbound.backlist')->with(['number' => $datas->where('退料單號',$list)])
                        ->with(['data' => $datas->where('退料單號',$list)])
                        ->with(['people' => 人員信息::cursor()])
                        ->with(['people1' => 人員信息::cursor()])
                        ->with(['position' => 儲位::cursor()]);
                }
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //領料記錄表
    public function pickrecord(Request $request)
    {
        if (Session::has('username')) {
            return view('outbound.pickrecord')->with(['client' => 客戶別::cursor()])
                ->with(['production' => 製程::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //退料記錄表
    public function backrecord(Request $request)
    {
        if (Session::has('username')) {
            return view('outbound.backrecord')->with(['client' => 客戶別::cursor()])
                ->with(['production' => 製程::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //領料記錄表查詢
    public function pickrecordsearch(Request $request)
    {
        if(Session::has('username'))
        {
            $client = $request->input('client');
            $number =  $request->input('number');
            $send = $request->input('send');
            $production = $request->input('production');
            $begin = date($request->input('begin'));
            $end = date($request->input('end'));
            if($number !== null)
            {
                $datas = DB::table('consumptive_material')
                    ->join('outbound', function($join)
                    {
                        $join->on('outbound.料號', '=', 'consumptive_material.料號')
                        ->whereNotNull('領料人員');
                    })->where('consumptive_material.料號','like', $number.'%')->get();

            }
            else
            {
                $datas = DB::table('consumptive_material')
                    ->join('outbound', function($join)
                    {
                        $join->on('outbound.料號', '=', 'consumptive_material.料號')
                        ->whereNotNull('領料人員');
                    })->get();
            }

            //all empty
            if($client === null && $production === null && $number === null  && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas]);
            }
            //select client
            else if($client !== null && $production === null && $number === null && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)]);
            }
            //select production
            else if($client === null && $production !== null && $number === null && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('製程' , $production)]);
            }
            //input material number
            else if($client === null && $production === null && $number !== null && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas]);
            }
            //select send
            else if($client === null && $production === null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)]);
            }
            //select date
            else if($client === null && $production === null && $number === null && $send === null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])]);
            }
            //select client and production
            else if($client !== null && $production !== null && $number === null && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)->where('製程' , $production)]);
            }
            //select client and number
            else if($client !== null && $production === null && $number !== null && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)]);
            }
            //select client and time
            else if($client !== null && $production === null && $number === null && $send === null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])->where('客戶別' , $client)]);
            }
            //select production and number
            else if($client === null && $production !== null && $number !== null && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('製程' , $production)]);
            }
            //select production and time
            else if($client === null && $production !== null && $number === null && $send === null && ($request->has('date')))
            {

                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])->where('製程' , $production)]);
            }
            //select number and time
            else if($client === null && $production === null && $number !== null && $send === null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])]);

            }
            //select client and send
            else if($client !== null && $production === null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)->where('發料部門' , $send)]);
            }
            //select production and send
            else if($client === null && $production !== null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('製程' , $production)->where('發料部門' , $send)]);
            }
            //select number and send
            else if($client === null && $production === null && $number !== null && $send !== null && !($request->has('date')))
            {

                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)]);

            }
            //select time and send
            else if($client === null && $production === null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])->where('發料部門' , $send)]);
            }
            //select client and production and number
            else if($client !== null && $production !== null && $number !== null && $send === null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and production and time
            else if($client !== null && $production !== null && $number === null && $send === null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and number and time
            else if($client !== null && $production === null && $number !== null && $send === null && ($request->has('date')))
            {

                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])
                ->where('客戶別' , $client)]);

            }
            //select production and number and time
            else if($client === null && $production !== null && $number !== null && $send === null && ($request->has('date')))
            {

                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])
                ->where('製程' , $production)]);
            }

            //select client and production and send
            else if($client !== null && $production !== null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and number and send
            else if($client !== null && $production === null && $number !== null && $send !== null && !($request->has('date')))
            {

                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)
                ->where('客戶別' , $client)]);

            }
            //select client and time and send
            else if($client !== null && $production === null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])->where('客戶別' , $client)
                    ->where('發料部門' , $send)]);
            }
            //select number and production and send
            else if($client === null && $production !== null && $number !== null && $send !== null && !($request->has('date')))
            {

                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)]);

            }
            //select production and time and send
            else if($client === null && $production !== null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])->where('製程' , $production)
                    ->where('發料部門' , $send)]);
            }
            //select number and time and send
            else if($client === null && $production === null && $number !== null && $send !== null && ($request->has('date')))
            {

                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])
                ->where('發料部門' , $send)]);

            }
            //select client and production and number and time
            else if($client !== null && $production !== null && $number !== null && $send === null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('製程' , $production)
                ->where('客戶別' , $client)->whereBetween('出庫時間' , [$begin, $end])]);
            }
            //select client and send and number and time
            else if($client !== null && $production === null && $number !== null && $send !== null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)
                ->where('客戶別' , $client)->whereBetween('出庫時間' , [$begin, $end])]);
            }
            //select client and production and number and send
            else if($client !== null && $production !== null && $number !== null && $send !== null && !($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('製程' , $production)
                ->where('客戶別' , $client)->where('發料部門' , $send)]);
            }
            //select client and production and send and time
            else if($client !== null && $production !== null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)
                ->where('客戶別' , $client)->whereBetween('出庫時間' , [$begin, $end])]);

            }
            //select number and production and send and time
            else if($client === null && $production !== null && $number !== null && $send !== null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)
                ->whereBetween('出庫時間' , [$begin, $end])]);
            }
            //select all
            else if($client !== null && $production !== null && $number !== null && $send !== null && ($request->has('date')))
            {
                return view('outbound.pickrecordsearchok')->with(['data' => $datas->whereBetween('出庫時間' , [$begin, $end])
                ->where('製程' , $production)->where('客戶別' , $client)->where('發料部門',$send)]);
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //退料記錄表查詢
    public function backrecordsearch(Request $request)
    {
        if(Session::has('username'))
        {
            $client = $request->input('client');
            $number =  $request->input('number');
            $send = $request->input('send');
            $production = $request->input('production');
            $begin = date($request->input('begin'));
            $end = date($request->input('end'));
            if($number !== null)
            {
                $datas = DB::table('consumptive_material')
                    ->join('出庫退料', function($join)
                    {
                        $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                        ->whereNotNull('收料人員');
                    })->where('consumptive_material.料號','like', $number.'%')->get();
            }
            else
            {
                $datas = DB::table('consumptive_material')
                    ->join('出庫退料', function($join)
                    {
                        $join->on('出庫退料.料號', '=', 'consumptive_material.料號')
                        ->whereNotNull('收料人員');
                    })->get();
            }
            //all empty
            if($client === null && $production === null && $number === null  && $send === null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas]);
            }
            //select client
            else if($client !== null && $production === null && $number === null && $send === null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)]);
            }
            //select production
            else if($client === null && $production !== null && $number === null && $send === null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('製程' , $production)]);
            }
            //input material number
            else if($client === null && $production === null && $number !== null && $send === null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas]);
            }
            //select send
            else if($client === null && $production === null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)]);
            }
            //select date
            else if($client === null && $production === null && $number === null && $send === null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select client and production
            else if($client !== null && $production !== null && $number === null && $send === null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)->where('製程' , $production)]);
            }
            //select client and number
            else if($client !== null && $production === null && $number !== null && $send === null && !($request->has('date')))
            {

                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)]);
            }
            //select client and time
            else if($client !== null && $production === null && $number === null && $send === null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])->where('客戶別' , $client)]);
            }
            //select production and number
            else if($client === null && $production !== null && $number !== null && $send === null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('製程' , $production)]);
            }
            //select production and time
            else if($client === null && $production !== null && $number === null && $send === null && ($request->has('date')))
            {

                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])->where('製程' , $production)]);
            }
            //select number and time
            else if($client === null && $production === null && $number !== null && $send === null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select client and send
            else if($client !== null && $production === null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('客戶別' , $client)->where('發料部門' , $send)]);
            }
            //select production and send
            else if($client === null && $production !== null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('製程' , $production)->where('發料部門' , $send)]);
            }
            //select number and send
            else if($client === null && $production === null && $number !== null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)]);
            }
            //select time and send
            else if($client === null && $production === null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])->where('發料部門' , $send)]);
            }
            //select client and production and number
            else if($client !== null && $production !== null && $number !== null && $send === null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and production and time
            else if($client !== null && $production !== null && $number === null && $send === null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and number and time
            else if($client !== null && $production === null && $number !== null && $send === null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])
                ->where('客戶別' , $client)]);
            }
            //select production and number and time
            else if($client === null && $production !== null && $number !== null && $send === null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])
                ->where('製程' , $production)]);
            }
            //select client and production and send
            else if($client !== null && $production !== null && $number === null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and number and send
            else if($client !== null && $production === null && $number !== null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)
                ->where('客戶別' , $client)]);
            }
            //select client and time and send
            else if($client !== null && $production === null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])->where('客戶別' , $client)
                    ->where('發料部門' , $send)]);
            }
            //select number and production and send
            else if($client === null && $production !== null && $number !== null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)]);
            }
            //select production and time and send
            else if($client === null && $production !== null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])->where('製程' , $production)
                    ->where('發料部門' , $send)]);
            }
            //select number and time and send
            else if($client === null && $production === null && $number !== null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])
                ->where('發料部門' , $send)]);
            }
            //select client and production and number and time
            else if($client !== null && $production !== null && $number !== null && $send === null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('製程' , $production)
                ->where('客戶別' , $client)->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select client and send and number and time
            else if($client !== null && $production === null && $number !== null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)
                ->where('客戶別' , $client)->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select client and production and number and send
            else if($client !== null && $production !== null && $number !== null && $send !== null && !($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('製程' , $production)
                ->where('客戶別' , $client)->where('發料部門' , $send)]);
            }
            //select client and production and send and time
            else if($client !== null && $production !== null && $number === null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)
                ->where('客戶別' , $client)->whereBetween('入庫時間' , [$begin, $end])]);

            }
            //select number and production and send and time
            else if($client === null && $production !== null && $number !== null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->where('發料部門' , $send)->where('製程' , $production)
                ->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select all
            else if($client !== null && $production !== null && $number !== null && $send !== null && ($request->has('date')))
            {
                return view('outbound.backrecordsearchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])
                ->where('製程' , $production)->where('客戶別' , $client)->where('發料部門',$send)]);
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //領料添加
    public function pickadd(Request $request)
    {
        Session::forget('client');
        Session::forget('machine');
        Session::forget('production');
        Session::forget('line');
        Session::forget('usereason');
        Session::forget('name');
        Session::forget('format');
        Session::forget('unit');
        Session::forget('send');
        Session::forget('number');
        $reDive = new responseObj();
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null) {

                if (strlen($request->input('number')) === 12) {
                    $client = $request->input('client');
                    $machine = $request->input('machine');
                    $production = $request->input('production');
                    $line = $request->input('line');
                    $usereason = $request->input('usereason');
                    $number = $request->input('number');
                    $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
                    $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
                    $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
                    $send = DB::table('consumptive_material')->where('料號', $number)->value('發料部門');

                    $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->value('現有庫存');

                    if ($name !== null && $format !== null) {
                        Session::put('number', $number);
                        Session::put('client', $client);
                        Session::put('machine', $machine);
                        Session::put('production', $production);
                        Session::put('line', $line);
                        Session::put('usereason', $usereason);
                        Session::put('name', $name);
                        Session::put('format', $format);
                        Session::put('unit', $unit);
                        Session::put('send', $send);
                        Session::put('pick', $client);
                        if ($stock > 0) {
                            $reDive->boolean = true;
                            $reDive->passbool = true;
                            $reDive->passstock = true;
                            $myJSON = json_encode($reDive);
                            echo $myJSON;
                        }
                        //沒有庫存
                        else {
                            $reDive->boolean = true;
                            $reDive->passbool = true;
                            $reDive->passstock = false;
                            $myJSON = json_encode($reDive);
                            echo $myJSON;
                        }
                    } else {
                        $reDive->boolean = true;
                        $reDive->passbool = false;
                        $reDive->passstock = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                } else {
                    $reDive->boolean = false;
                    $reDive->passbool = true;
                    $reDive->passstock = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                }
            } else {
                return view('outbound.pick');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //退料添加
    public function backadd(Request $request)
    {
        Session::forget('client');
        Session::forget('machine');
        Session::forget('production');
        Session::forget('line');
        Session::forget('backreason');
        Session::forget('name');
        Session::forget('format');
        Session::forget('unit');
        Session::forget('send');
        SessION::forget('number');
        $reDive = new responseObj();
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null) {

                if (strlen($request->input('number')) === 12) {
                    $client = $request->input('client');
                    $machine = $request->input('machine');
                    $production = $request->input('production');
                    $line = $request->input('line');
                    $backreason = $request->input('backreason');
                    $number = $request->input('number');
                    $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
                    $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
                    $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
                    $send = DB::table('consumptive_material')->where('料號', $number)->value('發料部門');

                    if ($name !== null && $format !== null) {
                        Session::put('number', $number);
                        Session::put('client', $client);
                        Session::put('machine', $machine);
                        Session::put('production', $production);
                        Session::put('line', $line);
                        Session::put('backreason', $backreason);
                        Session::put('name', $name);
                        Session::put('format', $format);
                        Session::put('unit', $unit);
                        Session::put('send', $send);
                        Session::put('back', $client);
                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    } else {
                        $reDive->boolean = true;
                        $reDive->passbool = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                } else {
                    $reDive->boolean = false;
                    $reDive->passbool = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                }
            } else {
                return view('outbound.back');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //提交領料添加
    public function pickaddsubmit(Request $request)
    {
        $reDive = new  responseObj();
        if (Session::has('username')) {
            if ($request->input('amount') !== null) {
                $number = $request->input('number');
                $name = $request->input('name');
                $format = $request->input('format');
                $unit = $request->input('unit');
                $send = $request->input('send');
                $amount = $request->input('amount');
                $remark = $request->input('remark');
                if($remark === null) $remark = '';
                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $line = $request->input('line');
                $usereason = $request->input('usereason');
                $i = '0001';
                $max = DB::table('outbound')->max('開單時間');
                $maxtime = date_create(date('Y-m-d', strtotime($max)));
                $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
                $interval = date_diff($maxtime, $nowtime);
                $interval = $interval->format('%R%a');
                $interval = (int)($interval);
                if ($interval > 0) {
                    $opentime = Carbon::now()->format('Ymd') . $i;
                } else {
                    $num = DB::table('outbound')->max('領料單號');
                    $num = intval($num);
                    $num++;
                    $num = strval($num);
                    $opentime = $num;
                }
                DB::beginTransaction();
                try {
                    DB::table('outbound')
                            ->insert(['客戶別' => $client , '機種' => $machine , '製程' => $production ,'領用原因' => $usereason , '線別' => $line ,
                            '料號' => $number , '品名' => $name , '規格' => $format ,'單位' => $unit , '預領數量' => $amount ,
                            '實際領用數量' => $amount , '備註' => $remark , '領料單號' => $opentime ,'開單時間' => Carbon::now()]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $reDive->boolean = false;
                    $mess = $e->getMessage();
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        window.location.href='/outbound';
                        </script>");
                }

                $reDive->boolean = true;
                $reDive->message = $opentime;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            } else {
                return redirect(route('outbound.pick'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //提交退料添加
    public function backaddsubmit(Request $request)
    {
        $reDive = new responseObj();
        if (Session::has('username')) {
            if ($request->input('amount') !== null) {
                $number = $request->input('number');
                $name = $request->input('name');
                $format = $request->input('format');
                $unit = $request->input('unit');
                $send = $request->input('send');
                $amount = $request->input('amount');
                $remark = $request->input('remark');
                if($remark === null) $remark = '';
                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $line = $request->input('line');
                $backreason = $request->input('backreason');
                $i = '0001';
                $max = DB::table('出庫退料')->max('開單時間');
                $maxtime = date_create(date('Y-m-d', strtotime($max)));
                $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
                $interval = date_diff($maxtime, $nowtime);
                $interval = $interval->format('%R%a');
                $interval = (int)($interval);
                if ($interval > 0) {
                    $opentime = Carbon::now()->format('Ymd') . $i;
                } else {
                    $num = DB::table('出庫退料')->max('退料單號');
                    $num = intval($num);
                    $num++;
                    $num = strval($num);
                    $opentime = $num;
                }
                if ($remark === 'zero') $remark = '';
                DB::beginTransaction();
                try {
                    DB::table('出庫退料')
                            ->insert(['客戶別' => $client , '機種' => $machine , '製程' => $production ,'退回原因' => $backreason , '線別' => $line ,
                            '料號' => $number , '品名' => $name , '規格' => $format ,'單位' => $unit , '預退數量' => $amount ,
                            '實際退回數量' => $amount , '備註' => $remark , '退料單號' => $opentime ,'開單時間' => Carbon::now()]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $reDive->boolean = false;
                    $mess = $e->getMessage();
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        window.location.href='/outbound';
                        </script>");
                }

                $reDive->boolean = true;
                $reDive->message = $opentime;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            } else {
                return redirect(route('outbound.back'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //提交領料單
    public function picklistsubmit(Request $request)
    {

        $reDive = new responseObj();
        if (Session::has('username'))
        {
            if ($request->input('amount') !== null && $request->input('position') !== null)
            {
                $list = $request->input('list');
                $amount = $request->input('amount');
                $advance = $request->input('advance');
                $number = $request->input('number');
                $client = $request->input('client');
                $reason = $request->input('reason');
                $sendpeople = $request->input('sendpeople');
                $pickpeople = $request->input('pickpeople');
                $position = $request->input('position');
                $time = Carbon::now();
                $sendname = DB::table('人員信息')->where('工號', $sendpeople)->value('姓名');
                $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');
                $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $position)->value('現有庫存');
                //沒填寫實領差異原因
                if ($amount !== $advance && $reason === null)
                {
                    $reDive->boolean = true;
                    $reDive->passbool = false;
                    $reDive->passstock = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
                else
                {
                    //庫存小於實際領用數量,無法出庫
                    if ($amount > $stock)
                    {
                        $reDive->boolean = false;
                        $reDive->passbool = true;
                        $reDive->passstock = true;
                        $reDive->position = $position;
                        $reDive->nowstock = $stock;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                    else
                    {
                        DB::beginTransaction();
                        try {
                            DB::table('outbound')
                                ->where('領料單號', $list)
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->update([
                                    '實際領用數量' => $amount, '實領差異原因' => $reason, '儲位' => $position,
                                    '領料人員' => $pickname, '領料人員工號' => $pickpeople, '發料人員' => $sendname, '發料人員工號' => $sendpeople,
                                    '出庫時間' => $time
                                ]);

                            DB::table('inventory')
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('儲位', $position)
                                ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $time]);

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                            $reDive->passstock = false;
                            $myJSON = json_encode($reDive);
                            echo $myJSON;
                        }

                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $reDive->passstock = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                }
            }
            else
            {
                return view('outbound.picklist');
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //提交退料單
    public function backlistsubmit(Request $request)
    {

        $reDive = new responseObj();
        if (Session::has('username')) {
            if ($request->input('amount') !== null && $request->input('position') !== null) {
                $list = $request->input('list');
                $amount = $request->input('amount');
                $advance = $request->input('advance');
                $number = $request->input('number');
                $client = $request->input('client');
                $reason = $request->input('reason');
                $backpeople = $request->input('backpeople');
                $pickpeople = $request->input('pickpeople');
                $position = $request->input('position');
                $status = $request->input('status');
                $time = Carbon::now();
                $backname = DB::table('人員信息')->where('工號', $backpeople)->value('姓名');
                $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');
                if ($amount !== $advance && $reason === null)
                {
                    $reDive->boolean = true;
                    $reDive->passbool = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
                else
                {
                    if($status === '良品')
                    {
                        $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $position)->value('現有庫存');
                    }
                    else
                    {
                        $stock = DB::table('不良品inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $position)->value('現有庫存');
                    }

                    if ($stock === null)
                    {
                        DB::beginTransaction();
                        try {
                            DB::table('出庫退料')
                                ->where('退料單號', $list)
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->update([
                                    '實際退回數量' => $amount, '實退差異原因' => $reason, '儲位' => $position,
                                    '收料人員' => $pickname, '收料人員工號' => $pickpeople, '退料人員' => $backname, '退料人員工號' => $backpeople,
                                    '入庫時間' => $time, '功能狀況' => $status
                                ]);

                                if($status === '良品')
                                {
                                    DB::table('inventory')
                                    ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $position, '客戶別' => $client, '最後更新時間' => $time]);
                                }
                                else
                                {
                                    DB::table('不良品inventory')
                                    ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $position, '客戶別' => $client, '最後更新時間' => $time]);
                                }
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                        }

                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                    else
                    {
                        DB::beginTransaction();
                        try {
                            DB::table('出庫退料')
                                ->where('退料單號', $list)
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->update([
                                    '實際退回數量' => $amount, '實退差異原因' => $reason, '儲位' => $position,
                                    '收料人員' => $pickname, '收料人員工號' => $pickpeople, '退料人員' => $backname, '退料人員工號' => $backpeople,
                                    '入庫時間' => $time, '功能狀況' => $status
                                ]);
                            if($status === '良品')
                            {
                                DB::table('inventory')
                                    ->where('客戶別', $client)
                                    ->where('料號', $number)
                                    ->where('儲位', $position)
                                    ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $time]);
                            }
                            else
                            {
                                DB::table('不良品inventory')
                                    ->where('客戶別', $client)
                                    ->where('料號', $number)
                                    ->where('儲位', $position)
                                    ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $time]);
                            }

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                        }

                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                }
            }
            else
            {
                return view('outbound.backlist');
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //領料添加頁面
    public function pickaddok()
    {
        if (Session::has('username')) {
            if (Session::has('pick')) {
                Session::forget('pick');
                return view("outbound.pickadd");
            } else {
                return redirect(route('outbound.pick'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //退料添加頁面
    public function backaddok()
    {
        if (Session::has('username')) {
            if (Session::has('back')) {
                Session::forget('back');
                return view("outbound.backadd");
            } else {
                return redirect(route('outbound.back'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //download
    public function download(Request $request)
    {
        if (Session::has('username'))
        {

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $worksheet = $spreadsheet->getActiveSheet();
            $time = $request->input('time');
            $count = $request->input('count');
            //填寫表頭
            for($i = 0 ; $i < $time ; $i ++)
            {
                $worksheet->setCellValueByColumnAndRow($i+1 , 1 , $request->input('title'.$i));
            }

            //填寫內容
            for($i = 0 ; $i < $time ; $i ++)
            {
                for($j = 0 ; $j < $count ; $j++)
                {
                    $worksheet->setCellValueByColumnAndRow($i+1 , $j+2 , $request->input('data'.$i.$j));
                }
            }


            // 下載
            $now = Carbon::now()->format('YmdHis');
            $title = $request->input('title');
            $filename = $title . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }
}
