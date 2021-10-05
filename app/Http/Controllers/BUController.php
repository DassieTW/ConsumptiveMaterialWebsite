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
use App\Models\儲位;
use App\Models\廠別;
use App\Models\在途量;
use App\Models\調撥單;
use App\Models\Inventory;
use App\Models\不良品Inventory;
use App\Models\Inbound;
use App\Models\ConsumptiveMaterial;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class BUController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        if (Session::has('username')) {
            return view('bu.index');
        } else {
            return redirect(route('member.login'));
        }
    }

    //搜尋呆滯庫存
    public function sluggish()
    {
        //
        if (Session::has('username')) {
            $database = ['default', 'testing', 'bb1', 'bb4', 'm1'];

            foreach ($database as $key => $value) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $value);
                \DB::purge(env("DB_CONNECTION"));
                $datas[$key] = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                    ->select(
                        'inventory.料號',
                        '品名',
                        '規格',
                        '單位',
                        DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                        DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                    )
                    ->groupBy('inventory.料號')
                    ->get();
            }


            //dd($datas[2]);
            return view('bu.sluggish')->with(['test' => $datas]);
            /*return view('bu.sluggish')->with(['data0' => $datas[0]])->with(['data1' => $datas[1]])->with(['data2' => $datas[2]])
            ->with(['data3' => $datas[3]])->with(['data4' => $datas[4]]);*/
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥單新增and提交
    public function transsluggish(Request $request)
    {
        if (Session::has('username')) {
            /*$count = $request->input('check'.'4'.'0');
            dd($count);*/
            $reDive = new  responseObj();
            $database = $request->input('factory');

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));
            $oldstock = $request->input('oldstock');
            $number = $request->input('number');
            $name = $request->input('name');
            $format = $request->input('format');
            $unit = $request->input('unit');
            $amount = $request->input('amount');
            $receive = $request->input('receive');
            $nowstock = DB::table('inventory')->where('料號', $number)->sum('現有庫存');
            if ($oldstock == $nowstock) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
                \DB::purge(env("DB_CONNECTION"));
                $z = '0001';
                $max = DB::table('調撥單')->max('開單時間');
                $maxtime = date_create(date('Y-m-d', strtotime($max)));
                $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
                $interval = date_diff($maxtime, $nowtime);
                $interval = $interval->format('%R%a');
                $interval = (int)($interval);
                if ($interval > 0) {
                    $opentime = 'DB-' . Carbon::now()->format('Ymd') . $z;
                } else {
                    $num = DB::table('調撥單')->max('調撥單號');
                    $test = str_replace("DB-", "", $num);
                    $num = intval($test);
                    $num++;
                    $num = strval($num);
                    $opentime = 'DB-' . $num;
                }
                DB::beginTransaction();
                try {
                    DB::table('調撥單')
                        ->insert([
                            '料號' => $number, '品名' => $name, '規格' => $format, '單位' => $unit, '庫存' => $oldstock,
                            '調撥數量' => $amount, '撥出廠區' => $database, '接收廠區' => $receive, '調撥單號' => $opentime, '開單時間' => Carbon::now(), '狀態' => '待撥出'
                        ]);

                    DB::commit();
                    $reDive->boolean = true;
                    $reDive->passbool = true;
                    $reDive->message = $opentime;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                } catch (\Exception $e) {
                    DB::rollback();
                    $reDive->boolean = false;
                    $reDive->passbool = false;
                    $mess = $e->getMessage();
                    $reDive->message = $mess;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
            } else {
                $reDive->boolean = true;
                $reDive->passbool = false;
                $reDive->message = $oldstock;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥單查詢頁面
    public function searchlist(Request $request)
    {
        if (Session::has('username')) {
            return view('bu.searchlist')->with(['factory' => 廠別::cursor()])->with(['factory1' => 廠別::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥單查詢
    public function searchlistsub(Request $request)
    {
        if (Session::has('username')) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            if ($request->input('number') !== null) {
                $datas = DB::table('調撥單')
                    ->where('料號', 'like', $request->input('number') . '%')
                    ->get();
            }
            //all empty
            if ($request->input('outfactory') === null && $request->input('infactory') === null && $request->input('number') === null && !($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()]);
            }
            //select outfactory
            else if ($request->input('outfactory') !== null && $request->input('infactory') === null && $request->input('number') === null && !($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()->where('撥出廠區', $request->input('outfactory'))]);
            }
            //input infactory
            else if ($request->input('outfactory') === null && $request->input('infactory') !== null && $request->input('number') === null && !($request->has('date'))) {
                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {
                    return view('bu.searchlistok')->with(['data' => 調撥單::cursor()->where('接收廠區', $request->input('infactory'))]);
                }
            }
            //input material number
            else if ($request->input('outfactory') === null && $request->input('infactory') === null && $request->input('number') !== null && !($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => $datas]);
            }
            //select date
            else if ($request->input('outfactory') === null && $request->input('infactory') === null && $request->input('number') === null && ($request->has('date'))) {

                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()->whereBetween('開單時間', [$begin, $end])]);
            }
            //select outfactory and infactory
            else if ($request->input('outfactory') !== null && $request->input('infactory') !== null && $request->input('number') === null && !($request->has('date'))) {
                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {
                    return view('bu.searchlistok')->with(['data' => 調撥單::cursor()
                        ->where('撥出廠區', $request->input('outfactory'))->where('接收廠區', $request->input('infactory'))]);
                }
            }
            //select outfactory and number
            else if ($request->input('outfactory') !== null && $request->input('infactory') === null && $request->input('number') !== null && !($request->has('date'))) {

                return view('bu.searchlistok')->with(['data' => $datas->where('撥出廠區', $request->input('outfactory'))]);
            }
            //select outfactory and time
            else if ($request->input('outfactory') !== null && $request->input('infactory') === null && $request->input('number') === null && ($request->has('date'))) {

                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()
                    ->whereBetween('開單時間', [$begin, $end])->where('撥出廠區', $request->input('outfactory'))]);
            }
            //select infactory and number
            else if ($request->input('outfactory') === null && $request->input('infactory') !== null && $request->input('number') !== null && !($request->has('date'))) {

                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {
                    return view('bu.searchlistok')->with(['data' => $datas->where('接收廠區', $request->input('infactory'))]);
                }
            }
            //select infactory and time
            else if ($request->input('outfactory') === null && $request->input('infactory') !== null && $request->input('number') === null && ($request->has('date'))) {
                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {

                    return view('bu.searchlistok')->with(['data' => 調撥單::cursor()
                        ->whereBetween('開單時間', [$begin, $end])->where('接收廠區', $request->input('infactory'))]);
                }
            }
            //select number and time
            else if ($request->input('outfactory') === null && $request->input('infactory') === null && $request->input('number') !== null && ($request->has('date'))) {

                return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])]);
            }
            //select outfactory and infactory and number
            else if ($request->input('outfactory') !== null && $request->input('infactory') !== null && $request->input('number') !== null && !($request->has('date'))) {

                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {
                    return view('bu.searchlistok')->with(['data' => $datas->where('接收廠區', $request->input('infactory'))
                        ->where('撥出廠區', $request->input('outfactory'))]);
                }
            }
            //select outfactory and infactory and time
            else if ($request->input('outfactory') !== null && $request->input('infactory') !== null && $request->input('number') === null && ($request->has('date'))) {
                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {

                    return view('bu.searchlistok')->with(['data' => 調撥單::cursor()
                        ->whereBetween('開單時間', [$begin, $end])->where('', $request->input('infactory'))
                        ->where('撥出廠區', $request->input('outfactory'))]);
                }
            }
            //select outfactory and number and time
            else if ($request->input('outfactory') !== null && $request->input('infactory') === null && $request->input('number') !== null && ($request->has('date'))) {

                return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])
                    ->where('撥出廠區', $request->input('outfactory'))]);
            }
            //select infactory and number and time
            else if ($request->input('outfactory') === null && $request->input('infactory') !== null && $request->input('number') !== null && ($request->has('date'))) {
                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {

                    return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])
                        ->where('接收廠區', $request->input('infactory'))]);
                }
            }
            //select all
            else if ($request->input('outfactory') !== null && $request->input('infactory') !== null && $request->input('number') !== null && ($request->has('date'))) {
                if (strlen($request->input('infactory')) !== 12) {
                    return back()->withErrors([
                        'infactory' => trans('validation.regex'),
                    ]);
                } else {

                    return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])
                        ->where('接收廠區', $request->input('infactory'))->where('撥出廠區', $request->input('outfactory'))]);
                }
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥單刪除
    public function delete(Request $request)
    {
        if (Session::has('username')) {
            $reDive = new  responseObj();

            $list = $request->input('list');

            DB::beginTransaction();
            try {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
                \DB::purge(env("DB_CONNECTION"));
                DB::table('調撥單')
                    ->where('調撥單號', $list)
                    ->delete();
                DB::table('撥出明細')
                    ->where('調撥單號', $list)
                    ->delete();
                DB::commit();
                $reDive->boolean = true;
                $reDive->message = $list;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getMessage();
                echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    window.location.href='/bu';
                    </script>");
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥_撥出單頁面
    public function outlistpage(Request $request)
    {
        if (Session::has('username')) {

            $database = $request->session()->get('database');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));
            return view('bu.outlistpage')->with(['data' => 調撥單::cursor()->where('撥出廠區', $database)->wherenull('調撥人')]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥_撥出單
    public function outlist(Request $request)
    {
        if (Session::has('username')) {
            $list = $request->input('list');

            $database = $request->session()->get('database');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));
            return view('bu.outlist')->with(['data' => 調撥單::cursor()->where('撥出廠區', $database)->wherenull('調撥人')->where('調撥單號', $list)]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥-撥出單提交
    public function outlistsubmit(Request $request)
    {
        if (Session::has('username')) {
            $reDive = new responseObj();

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));
            $list = $request->input('list');
            $name = $request->input('name');
            $number = $request->input('number');
            $format = $request->input('format');
            $preamount = $request->input('preamount');
            $nowstocks = $request->input('nowstocks');
            $realamounts = $request->input('realamounts');
            $positions = $request->input('positions');
            $outfactory = $request->input('outfactory');
            $receivefac = $request->input('receivefac');
            $count = $request->input('count');
            $clients = $request->input('clients');
            $outpeople = $request->input('outpeople');
            $now = Carbon::now();

            $total = 0;
            DB::beginTransaction();
            try {

                for ($i = 0; $i < $count; $i++) {
                    DB::table('撥出明細')
                        ->insert([
                            '調撥單號' => $list, '客戶別' => $clients[$i], '撥出廠區' => $outfactory, '接收廠區' => $receivefac, '料號' => $number, '品名' => $name, '規格' => $format, '現有庫存' => $nowstocks[$i], '預計撥出數量' => $preamount, '實際撥出數量' => $realamounts[$i], '儲位' => $positions[$i], '調撥人' => $outpeople, '撥出時間' => $now
                        ]);
                    $total = $total + $realamounts[$i];
                }

                if ($total > 0) {
                    DB::table('調撥單')
                        ->where('調撥單號', $list)
                        ->whereNull('調撥人')
                        ->update(['調撥人' => $outpeople, '撥出數量' => $total, '狀態' => '待接收', '出庫時間' => $now]);
                } else {
                    DB::table('調撥單')
                        ->where('調撥單號', $list)
                        ->whereNull('調撥人')
                        ->update(['調撥人' => $outpeople, '撥出數量' => $total, '狀態' => '已完成', '出庫時間' => $now]);
                }

                DB::commit();
                $reDive->boolean = true;
                $reDive->message = $list;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getMessage();
                $reDive->boolean = true;
                $reDive->message = $mess;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥_接收單頁面
    public function picklistpage(Request $request)
    {
        if (Session::has('username')) {

            $database = $request->session()->get('database');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));
            return view('bu.picklistpage')->with(['data' => 調撥單::cursor()->where('接收廠區', $database)->where('狀態', '待接收')]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥_接收單
    public function picklist(Request $request)
    {
        if (Session::has('username')) {
            $list = $request->input('list');

            $database = $request->session()->get('database');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));
            return view('bu.picklist')->with(['data' => 調撥單::cursor()->where('接收廠區', $database)->where('狀態', '待接收')->where('調撥單號', $list)]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥-接收單提交
    public function picklistsubmit(Request $request)
    {
        if (Session::has('username')) {
            $reDive = new responseObj();

            $now = Carbon::now();
            $number = $request->input('number');
            $list = $request->input('list');
            $client = $request->input('client');
            $position = $request->input('position');
            $realpick = $request->input('realpick');
            $realout = $request->input('realout');
            $pickpeople = $request->input('pickpeople');
            $name = $request->input('name');
            $format = $request->input('format');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));

            $outclients = DB::table('撥出明細')->where('料號', $number)->where('調撥單號', $list)->pluck('客戶別')->toarray();
            $outlocs = DB::table('撥出明細')->where('料號', $number)->where('調撥單號', $list)->pluck('儲位')->toarray();
            $outstock = DB::table('撥出明細')->where('料號', $number)->where('調撥單號', $list)->pluck('現有庫存')->toarray();
            $outrealout = DB::table('撥出明細')->where('料號', $number)->where('調撥單號', $list)->pluck('實際撥出數量')->toarray();
            $count = count($outclients);
            $outfactory = $request->input('outfactory');

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $outfactory);
            \DB::purge(env("DB_CONNECTION"));

            $inventorysure = false;
            //檢查撥出廠區之庫存是否有異動
            for ($i = 0; $i < $count; $i++) {
                $nowoutstock[$i] = DB::table('inventory')->where('料號', $number)->where('客戶別', $outclients[$i])->where('儲位', $outlocs[$i])->value('現有庫存');
                if ($outstock[$i] != $nowoutstock[$i]) {
                    $reDive->boolean = true;
                    $reDive->passbool = false;
                    $inventorysure = false;
                } else {
                    $inventorysure = true;
                }
            }

            /*$reDive->boolean = true;
            $reDive->passbool = false;
            $reDive->message = $inventorysure;
            $myJSON = json_encode($reDive);
            echo $myJSON;*/

            DB::beginTransaction();
            try {
                //庫存無異動，實際撥出
                if ($inventorysure === true) {
                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $outfactory);
                    \DB::purge(env("DB_CONNECTION"));
                    for ($i = 0; $i < $count; $i++) {

                        DB::table('inventory')
                            ->where('料號', $number)
                            ->where('客戶別', $outclients[$i])
                            ->where('儲位', $outlocs[$i])
                            ->update(['現有庫存' => $nowoutstock[$i] - $outrealout[$i], '最後更新時間' => $now]);
                    }

                    $receivefac = $request->input('receivefac');

                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $receivefac);
                    \DB::purge(env("DB_CONNECTION"));

                    $pickstock = DB::table('inventory')->where('料號', $number)->where('客戶別', $client)->where('儲位', $position)->value('現有庫存');

                    if ($pickstock !== null) {
                        DB::table('inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('儲位', $position)
                            ->update(['現有庫存' => $pickstock + $realpick, '最後更新時間' => $now]);
                    } else {
                        DB::table('inventory')
                            ->insert(['料號' => $number, '現有庫存' => $realpick, '儲位' => $position, '客戶別' => $client, '最後更新時間' => $now]);
                    }

                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
                    \DB::purge(env("DB_CONNECTION"));

                    DB::table('接收明細')
                        ->insert([
                            '調撥單號' => $list, '客戶別' => $client, '撥出廠區' => $outfactory, '接收廠區' => $receivefac, '料號' => $number, '品名' => $name, '規格' => $format, '實際接收數量' => $realpick, '實際撥出數量' => $realout, '儲位' => $position, '接收人' => $pickpeople, '接收時間' => $now
                        ]);

                    DB::table('調撥單')
                        ->where('調撥單號', $list)
                        ->whereNull('接收人')
                        ->update(['接收人' => $pickpeople, '接收數量' => $realpick, '狀態' => '已完成', '入庫時間' => $now]);

                    DB::commit();
                    $reDive->boolean = true;
                    $reDive->passbool = true;
                    $reDive->message = $list;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                } else {
                    $reDive->boolean = true;
                    $reDive->passbool = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getMessage();
                $reDive->boolean = false;
                $reDive->passbool = false;
                $reDive->message = $mess;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
        } else {
            return redirect(route('member.login'));
        }
    }




    //調撥明細查詢頁面
    public function searchdetail(Request $request)
    {
        if (Session::has('username')) {
            return view('bu.searchdetail');
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥明細查詢
    public function searchdetailsub(Request $request)
    {
        if (Session::has('username')) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            \DB::purge(env("DB_CONNECTION"));
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            $database = $request->session()->get('database');
            $choose = 'out';

            if ($request->has('outdetail')) {
                if ($request->input('number') !== null) {
                    if ($request->has('date')) {
                        $datas = DB::table('撥出明細')
                            ->where('料號', 'like', $request->input('number') . '%')
                            ->where('撥出廠區', '=', $database)
                            ->whereBetween('撥出時間', [$begin, $end])
                            ->get();
                    } else {
                        $datas = DB::table('撥出明細')
                            ->where('料號', 'like', $request->input('number') . '%')
                            ->where('撥出廠區', '=', $database)
                            ->get();
                    }
                } else {
                    if ($request->has('date')) {
                        $datas = DB::table('撥出明細')
                            ->where('撥出廠區', '=', $database)
                            ->whereBetween('撥出時間', [$begin, $end])
                            ->get();
                    } else {
                        $datas = DB::table('撥出明細')
                            ->where('撥出廠區', '=', $database)
                            ->get();
                    }
                }
            } else {
                if ($request->input('number') !== null) {
                    if ($request->has('date')) {
                        $datas = DB::table('接收明細')
                            ->where('料號', 'like', $request->input('number') . '%')
                            ->where('接收廠區', '=', $database)
                            ->whereBetween('接收時間', [$begin, $end])
                            ->get();
                    } else {
                        $datas = DB::table('接收明細')
                            ->where('料號', 'like', $request->input('number') . '%')
                            ->where('接收廠區', '=', $database)
                            ->get();
                    }
                } else {
                    if ($request->has('date')) {
                        $datas = DB::table('接收明細')
                            ->where('接收廠區', '=', $database)
                            ->whereBetween('接收時間', [$begin, $end])
                            ->get();
                    } else {
                        $datas = DB::table('接收明細')
                            ->where('接收廠區', '=', $database)
                            ->get();
                    }
                }
                $choose = 'receive';
                $datas = DB::table('撥出明細')
                    ->where('撥出廠區', '=', $database)
                    ->get();
            }

            $choose = 'out';
            return view('bu.searchdetailok')->with(['data' => $datas])->with('choose', $choose);
        } else {
            return redirect(route('member.login'));
        }
    }


    //呆滯庫存查詢下載
    public function download(Request $request)
    {
        if (Session::has('username')) {
            $reDive = new responseObj();
            $spreadsheet = new Spreadsheet();
            //$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            foreach (range('A', 'Z') as $char) {
                $spreadsheet->getActiveSheet()->getColumnDimension($char)->setWidth(20);
            }
            $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(60);
            $worksheet = $spreadsheet->getActiveSheet();

            $title = $request->input('title');
            $data0 = $request->input('data0');
            $test = "";
            //填寫表頭
            for ($i = 0; $i < 10; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $title[$i]);
            }

            for ($i = 0 ; $i < 8; $i++)
            {
                for($j = 0 ; $j < count($data0) ;$j ++)
                {

                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data'.$i)[$j]);

                }

            }

            for ($i = 0 ; $i < 5; $i++)
            {
                if(isset($request->input('data8')[$i]) != 0)
                {
                    for ($j = 0 ; $j < count($request->input('data8')[$i]); $j++)
                    {
                        $test = $test . $request->input('data8')[$i][$j] . "\n";
                    }
                    $worksheet->setCellValueByColumnAndRow(9, $i + 2, $test);
                    $test = "";
                }
            }

            for($j = 0 ; $j < count($data0) ;$j ++)
            {

                $worksheet->setCellValueByColumnAndRow(10, $j + 2, $request->input('data9')[$j]);

            }




            // 下載

            $now = Carbon::now()->format('YmdHis');
            //rawurlencode('呆滯庫存查詢');
            $filename = rawurlencode('呆滯庫存查詢') . $now . '.xlsx';
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

    //調撥單查詢下載
    public function downloadlist(Request $request)
    {
        if (Session::has('username')) {
            $reDive = new responseObj();
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

            $worksheet = $spreadsheet->getActiveSheet();

            $title = $request->input('title');
            $data0 = $request->input('data0');

            //填寫表頭
            for ($i = 0; $i < 17; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $title[$i]);
            }

            for ($i = 0 ; $i < 17; $i++)
            {
                for($j = 0 ; $j < count($data0) ;$j ++)
                {

                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data'.$i)[$j]);

                }
            }




            // 下載

            $now = Carbon::now()->format('YmdHis');

            $filename = rawurlencode('調撥單查詢') . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\''.$filename.';');
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

    //廠區庫存調撥頁面
    public function material(Request $request)
    {
        if (Session::has('username')) {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
        \DB::purge(env("DB_CONNECTION"));
            return view('bu.material')->with(['factory' => 廠別::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }


    public function sluggishmaterial(Request $request)
    {
        //
        if (Session::has('username')) {
            $number = $request->input('number');
            $table = $request->input('table');

            if(strlen($number) !== 12)
            {
                return back()->withErrors([

                    'number' => trans('bupagelang.isnlength'),
                ]);
            }
            else
            {
                $database = ['default', 'testing', 'bb1', 'bb4', 'm1'];

                foreach ($database as $key => $value) {
                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $value);
                    \DB::purge(env("DB_CONNECTION"));
                    $datas[$key] = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                        ->select(
                            'inventory.料號',
                            '品名',
                            '規格',
                            '單位',
                            DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                            DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                        )
                        ->groupBy('inventory.料號')
                        ->where('inventory.料號',$number)
                        ->get();
                }

                return view('bu.sluggish1')->with(['test' => $datas])
                ->with(['table' => $table]);


            }
        } else {
            return redirect(route('member.login'));
        }
    }
}
