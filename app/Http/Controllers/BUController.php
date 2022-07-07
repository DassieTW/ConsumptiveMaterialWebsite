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
use App\Models\請購單;
use App\Models\非月請購;
use App\Models\不良品Inventory;
use App\Models\Inbound;
use App\Models\ConsumptiveMaterial;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
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


    /*public function index()
    {
        //
        if (Session::has('username')) {
            return view('bu.index');
        } else {
            return redirect(route('member.login'));
        }
    }*/

    //搜尋呆滯庫存
    public function sluggish()
    {
        //
        if (Session::has('username')) {

            $database = config('database_list.databases');

            foreach ($database as $key => $value) {

                if ($value !== 'Consumables management') {
                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $value);
                    \DB::purge(env("DB_CONNECTION"));

                    /* $datas[$key] = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                    ->select(
                        'inventory.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.單位',
                        DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                        DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                    )
                    ->groupBy('inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格', 'consumptive_material.單位')
                    ->havingRaw('sum(inventory.現有庫存) > ?', [0])
                    ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')   // online setting
                    ->get();*/

                    $datas[$key] = DB::table('inventory')->join('consumptive_material', 'consumptive_material.料號', '=', 'inventory.料號')
                        ->select(
                            'inventory.料號',
                            'consumptive_material.品名',
                            'consumptive_material.規格',
                            'consumptive_material.單位',
                            DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                            DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                        )
                        ->groupBy('inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格', 'consumptive_material.單位')
                        ->havingRaw('sum(inventory.現有庫存) > ?', [0])
                        ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')   // online setting
                        ->get();
                } // if
            } // for each


            //dd($datas);
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
        $database_list = config('database_list.databases');
        if (Session::has('username')) {
            /*$count = $request->input('check'.'4'.'0');
            dd($count);*/
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
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
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
                    return \Response::json(['message' => $opentime]/* Status code here default is 200 ok*/);
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
            } else {
                return \Response::json(['message' => $oldstock], 421/* Status code here default is 200 ok*/);
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
        $database_list = config('database_list.databases');
        if (Session::has('username')) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
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
            //select infactory
            else if ($request->input('outfactory') === null && $request->input('infactory') !== null && $request->input('number') === null && !($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()->where('接收廠區', $request->input('infactory'))]);
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
                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()
                    ->where('撥出廠區', $request->input('outfactory'))->where('接收廠區', $request->input('infactory'))]);
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
                return view('bu.searchlistok')->with(['data' => $datas->where('接收廠區', $request->input('infactory'))]);
            }
            //select infactory and time
            else if ($request->input('outfactory') === null && $request->input('infactory') !== null && $request->input('number') === null && ($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()
                    ->whereBetween('開單時間', [$begin, $end])->where('接收廠區', $request->input('infactory'))]);
            }
            //select number and time
            else if ($request->input('outfactory') === null && $request->input('infactory') === null && $request->input('number') !== null && ($request->has('date'))) {

                return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])]);
            }
            //select outfactory and infactory and number
            else if ($request->input('outfactory') !== null && $request->input('infactory') !== null && $request->input('number') !== null && !($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => $datas->where('接收廠區', $request->input('infactory'))
                    ->where('撥出廠區', $request->input('outfactory'))]);
            }
            //select outfactory and infactory and time
            else if ($request->input('outfactory') !== null && $request->input('infactory') !== null && $request->input('number') === null && ($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => 調撥單::cursor()
                    ->whereBetween('開單時間', [$begin, $end])->where('', $request->input('infactory'))
                    ->where('撥出廠區', $request->input('outfactory'))]);
            }
            //select outfactory and number and time
            else if ($request->input('outfactory') !== null && $request->input('infactory') === null && $request->input('number') !== null && ($request->has('date'))) {

                return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])
                    ->where('撥出廠區', $request->input('outfactory'))]);
            }
            //select infactory and number and time
            else if ($request->input('outfactory') === null && $request->input('infactory') !== null && $request->input('number') !== null && ($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])
                    ->where('接收廠區', $request->input('infactory'))]);
            }
            //select all
            else if ($request->input('outfactory') !== null && $request->input('infactory') !== null && $request->input('number') !== null && ($request->has('date'))) {
                return view('bu.searchlistok')->with(['data' => $datas->whereBetween('開單時間', [$begin, $end])
                    ->where('接收廠區', $request->input('infactory'))->where('撥出廠區', $request->input('outfactory'))]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥單刪除
    public function delete(Request $request)
    {
        $database_list = config('database_list.databases');
        if (Session::has('username')) {

            $list = $request->input('list');

            DB::beginTransaction();
            try {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
                \DB::purge(env("DB_CONNECTION"));
                DB::table('調撥單')
                    ->where('調撥單號', $list)
                    ->delete();
                DB::table('撥出明細')
                    ->where('調撥單號', $list)
                    ->delete();
                DB::commit();
                return \Response::json(['message' => $list]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage(), 420]/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥_撥出單
    public function outlist(Request $request)
    {
        $database_list = config('database_list.databases');
        if (Session::has('username')) {
            $list = $request->input('list');

            $database = $request->session()->get('database');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
            \DB::purge(env("DB_CONNECTION"));
            return view('bu.outlist')
                ->with(['data' => 調撥單::cursor()->where('撥出廠區', $database)->wherenull('調撥人')->where('調撥單號', $list)]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥-撥出單提交
    public function outlistsubmit(Request $request)
    {
        $database_list = config('database_list.databases');
        if (Session::has('username')) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
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
                return \Response::json(['message' => $list]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥_接收單
    public function picklist(Request $request)
    {
        $database_list = config('database_list.databases');
        if (Session::has('username')) {
            $list = $request->input('list');

            $database = $request->session()->get('database');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
            \DB::purge(env("DB_CONNECTION"));
            return view('bu.picklist')->with(['data' => 調撥單::cursor()->where('接收廠區', $database)->where('狀態', '待接收')->where('調撥單號', $list)]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //調撥-接收單提交
    public function picklistsubmit(Request $request)
    {
        $database_list = config('database_list.databases');
        if (Session::has('username')) {

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
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
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
                    return \Response::json(['message' => 'inventory error'], 421/* Status code here default is 200 ok*/);
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

                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
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
                    return \Response::json(['message' => $list]/* Status code here default is 200 ok*/);
                } else {
                    return \Response::json(['message' => 'inventory error'], 421/* Status code here default is 200 ok*/);
                }
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }




    /*//調撥明細查詢頁面
    public function searchdetail(Request $request)
    {
        if (Session::has('username')) {
            return view('bu.searchdetail');
        } else {
            return redirect(route('member.login'));
        }
    }*/

    //調撥明細查詢
    public function searchdetailsub(Request $request)
    {
        $database_list = config('database_list.databases');
        if (Session::has('username')) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
            \DB::purge(env("DB_CONNECTION"));
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            $database = $request->session()->get('database');
            if ($request->has('outdetail')) {
                $choose = 'out';
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
                $choose = 'receive';
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
            }
            return view('bu.searchdetailok')->with(['data' => $datas])->with('choose', $choose);
        } else {
            return redirect(route('member.login'));
        }
    }


    //呆滯庫存查詢下載
    public function download(Request $request)
    {
        if (Session::has('username')) {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

            $worksheet = $spreadsheet->getActiveSheet();


            $title = $request->input('title');
            $Alldata = json_decode($request->input('AllData'));
            $count = $request->input('count');
            $test = "";
            $stringValueBinder = new StringValueBinder();
            $stringValueBinder->setNullConversion(false)->setFormulaConversion(false);
            \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder($stringValueBinder); // make it so it doesnt covert 儲位 to weird number format


            try {
                //填寫表頭
                for ($i = 0; $i < 10; $i++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, 1, $title[$i]);
                }

                for ($i = 0; $i < 8; $i++) {
                    for ($j = 0; $j < $count; $j++) {

                        $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$i][$j]);
                    }
                }

                for ($i = 0; $i < $count; $i++) {
                    if (isset($Alldata[8][$i]) != 0) {
                        for ($j = 0; $j < count($Alldata[8][$i]); $j++) {
                            $test = $test . $Alldata[8][$i][$j];
                        }
                        $worksheet->setCellValueByColumnAndRow(9, $i + 2, $test);
                        $test = "";
                    }
                }

                for ($j = 0; $j < $count; $j++) {

                    $worksheet->setCellValueByColumnAndRow(10, $j + 2, $Alldata[9][$j]);
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
            } catch (\Exception $e) {
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            } // try catch

            return response()->stream($callback, 200, $headers);
        } else {
            return redirect(route('member.login'));
        } // if else
    } // download

    //調撥單查詢下載
    public function downloadlist(Request $request)
    {
        if (Session::has('username')) {

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);

            $worksheet = $spreadsheet->getActiveSheet();

            $titlecount = $request->input('titlecount');
            $count = $request->input('count');
            $Alldata = json_decode($request->input('AllData'));

            // $stringValueBinder = new StringValueBinder();
            // $stringValueBinder->setNullConversion(false)->setFormulaConversion(false);
            // \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder($stringValueBinder); // make it so it doesnt covert 儲位 to weird number format



            //填寫表頭
            for ($i = 0; $i < $titlecount; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title')[$i]);
            }

            for ($i = 0; $i < $titlecount; $i++) {
                for ($j = 0; $j < $count; $j++) {

                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$i][$j]);
                }
            }

            // 下載
            $now = Carbon::now()->format('YmdHis');
            $titlename = $request->input('titlename');
            $filename = rawurlencode($titlename) . $now . '.xlsx';
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

    public function sluggishmaterial(Request $request)
    {
        //
        if (Session::has('username')) {
            $number = $request->input('number');
            $table = $request->input('table');

            if (strlen($number) !== 12) {
                return back()->withErrors([

                    'number' => trans('bupagelang.isnlength'),
                ]);
            } else {
                $database = config('database_list.databases');

                foreach ($database as $key => $value) {
                    if ($value !== 'Consumables management') {
                        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $value);
                        \DB::purge(env("DB_CONNECTION"));
                        $datas[$key] = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                            ->select(
                                'inventory.料號',
                                'consumptive_material.品名',
                                'consumptive_material.規格',
                                'consumptive_material.單位',
                                DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                                DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                            )
                            ->groupBy('inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格', 'consumptive_material.單位')
                            ->havingRaw('sum(inventory.現有庫存) > ?', [0])
                            ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')
                            ->where('inventory.料號', '=', $number)
                            ->get();
                    } // if
                } // foreach

                return view('bu.sluggish1')->with(['test' => $datas])
                    ->with(['table' => $table]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }
}
