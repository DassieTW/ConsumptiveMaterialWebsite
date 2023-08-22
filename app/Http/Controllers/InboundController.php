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
use App\Models\發料部門;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\儲位;
use App\Models\在途量;
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
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InboundController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //入庫-儲位調撥
    public function change(Request $request)
    {
        if (Session::has('username')) {

            $datas = DB::table('consumptive_material')
                ->join('inventory', 'consumptive_material.料號', '=', 'inventory.料號')
                ->where('inventory.料號', 'like', $request->input('number') . '%')
                ->where('inventory.客戶別', 'like', $request->input('client') . '%')
                ->where('inventory.儲位', 'like', $request->input('position') . '%')
                ->where('consumptive_material.發料部門', 'like', $request->input('send') . '%')
                ->where('現有庫存', '>', 0)
                ->select('inventory.*', 'consumptive_material.發料部門')
                ->get();

            return view('inbound.change')->with(['data' => $datas]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //入庫-儲位調撥提交
    public function changesubmit(Request $request)
    {
        if (Session::has('username')) {

            $number = $request->input('number');
            $oldposition = $request->input('oldposition');
            $client = $request->input('client');
            $amount = $request->input('amount');
            $newposition = $request->input('newposition');
            //$stock = $request->input('stock');
            $now = Carbon::now();
            $test = DB::table('inventory')
                ->where('客戶別', $client)
                ->where('料號', $number)
                ->where('儲位', $newposition)
                ->value('現有庫存');

            DB::beginTransaction();
            try {

                if ($test !== null) {
                    DB::table('inventory')
                        ->where('客戶別', $client)
                        ->where('料號', $number)
                        ->where('儲位', $newposition)
                        ->update(['現有庫存' => $test + $amount, '最後更新時間' => $now]);
                } else {
                    DB::table('inventory')
                        ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $newposition, '客戶別' => $client, '最後更新時間' => $now]);
                }

                $stock = DB::table('inventory')
                    ->where('客戶別', $client)
                    ->where('料號', $number)
                    ->where('儲位', $oldposition)
                    ->value('現有庫存');
                DB::table('inventory')
                    ->where('客戶別', $client)
                    ->where('料號', $number)
                    ->where('儲位', $oldposition)
                    ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $now]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getMessage();
                return \Response::json(['message' => $mess], 420/* Status code here default is 200 ok*/);
            }

            return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }

    // //入庫-查詢
    // public function inquire(Request $request)
    // {
    //     if (Session::has('username')) {
    //         $begin = date($request->input('begin'));
    //         $endDate = strtotime($request->input('end'));
    //         $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
    //         if ($request->input('number') !== null) {
    //             $datas = DB::table('inbound')
    //                 ->where('料號', 'like', $request->input('number') . '%')
    //                 ->get();
    //         }
    //         //all empty
    //         if ($request->input('client') === null && $request->input('innumber') === null && $request->input('number') === null && !($request->has('date'))) {
    //             return view('inbound.searchok')->with(['data' => Inbound::cursor()]);
    //         }
    //         //select client
    //         else if ($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') === null && !($request->has('date'))) {
    //             return view('inbound.searchok')->with(['data' => Inbound::cursor()->where('客戶別', $request->input('client'))]);
    //         }
    //         //input innumber
    //         else if ($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') === null && !($request->has('date'))) {
    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {
    //                 return view('inbound.searchok')->with(['data' => Inbound::cursor()->where('入庫單號', $request->input('innumber'))]);
    //             }
    //         }
    //         //input material number
    //         else if ($request->input('client') === null && $request->input('innumber') === null && $request->input('number') !== null && !($request->has('date'))) {
    //             return view('inbound.searchok')->with(['data' => $datas]);
    //         }
    //         //select date
    //         else if ($request->input('client') === null && $request->input('innumber') === null && $request->input('number') === null && ($request->has('date'))) {

    //             return view('inbound.searchok')->with(['data' => Inbound::cursor()->whereBetween('入庫時間', [$begin, $end])]);
    //         }
    //         //select client and innumber
    //         else if ($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') === null && !($request->has('date'))) {
    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {
    //                 return view('inbound.searchok')->with(['data' => Inbound::cursor()
    //                     ->where('客戶別', $request->input('client'))->where('入庫單號', $request->input('innumber'))]);
    //             }
    //         }
    //         //select client and number
    //         else if ($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') !== null && !($request->has('date'))) {

    //             return view('inbound.searchok')->with(['data' => $datas->where('客戶別', $request->input('client'))]);
    //         }
    //         //select client and time
    //         else if ($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') === null && ($request->has('date'))) {

    //             return view('inbound.searchok')->with(['data' => Inbound::cursor()
    //                 ->whereBetween('入庫時間', [$begin, $end])->where('客戶別', $request->input('client'))]);
    //         }
    //         //select innumber and number
    //         else if ($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') !== null && !($request->has('date'))) {

    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {
    //                 return view('inbound.searchok')->with(['data' => $datas->where('入庫單號', $request->input('innumber'))]);
    //             }
    //         }
    //         //select innumber and time
    //         else if ($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') === null && ($request->has('date'))) {
    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {

    //                 return view('inbound.searchok')->with(['data' => Inbound::cursor()
    //                     ->whereBetween('入庫時間', [$begin, $end])->where('入庫單號', $request->input('innumber'))]);
    //             }
    //         }
    //         //select number and time
    //         else if ($request->input('client') === null && $request->input('innumber') === null && $request->input('number') !== null && ($request->has('date'))) {

    //             return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間', [$begin, $end])]);
    //         }
    //         //select client and innumber and number
    //         else if ($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') !== null && !($request->has('date'))) {

    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {
    //                 return view('inbound.searchok')->with(['data' => $datas->where('入庫單號', $request->input('innumber'))
    //                     ->where('客戶別', $request->input('client'))]);
    //             }
    //         }
    //         //select client and innumber and time
    //         else if ($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') === null && ($request->has('date'))) {
    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {

    //                 return view('inbound.searchok')->with(['data' => Inbound::cursor()
    //                     ->whereBetween('入庫時間', [$begin, $end])->where('', $request->input('innumber'))
    //                     ->where('客戶別', $request->input('client'))]);
    //             }
    //         }
    //         //select client and number and time
    //         else if ($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') !== null && ($request->has('date'))) {

    //             return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間', [$begin, $end])
    //                 ->where('客戶別', $request->input('client'))]);
    //         }
    //         //select innumber and number and time
    //         else if ($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') !== null && ($request->has('date'))) {
    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {

    //                 return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間', [$begin, $end])
    //                     ->where('入庫單號', $request->input('innumber'))]);
    //             }
    //         }
    //         //select all
    //         else if ($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') !== null && ($request->has('date'))) {
    //             if (strlen($request->input('innumber')) !== 12) {
    //                 return back()->withErrors([
    //                     'innumber' => trans('validation.regex'),
    //                 ]);
    //             } else {

    //                 return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間', [$begin, $end])
    //                     ->where('入庫單號', $request->input('innumber'))->where('客戶別', $request->input('client'))]);
    //             }
    //         }
    //     } else {
    //         return redirect(route('member.login'));
    //     }
    // } // inquire

    //入庫-庫存查詢
    // public function searchstocksubmit(Request $request)
    // {
    //     if (Session::has('username')) {
    //         $send = $request->input('send');
    //         $client = $request->input('client');
    //         $position = $request->input('position');
    //         $number = $request->input('number');
    //         //不良品inventory
    //         if ($request->has('nogood')) {

    //             $datas = DB::table('consumptive_material')
    //                 ->join('不良品inventory', function ($join) {
    //                     $join->on('不良品inventory.料號', '=', 'consumptive_material.料號');
    //                 })->where('consumptive_material.料號', 'like', $number . '%')
    //                 ->where('不良品inventory.客戶別', 'like', $client . '%')
    //                 ->where('不良品inventory.儲位', 'like', $position . '%')
    //                 ->where('consumptive_material.發料部門', 'like', $send . '%')
    //                 ->where('現有庫存', '>', 0)->get();

    //             $datas1 = DB::table('月請購_單耗')
    //                 ->join('MPS', function ($join) {
    //                     $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
    //                         ->on('MPS.機種', '=', '月請購_單耗.機種')
    //                         ->on('MPS.製程', '=', '月請購_單耗.製程');
    //                 })
    //                 ->join('consumptive_material', function ($join) {
    //                     $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
    //                 })
    //                 ->select('MPS.下月MPS', 'MPS.下月生產天數', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存)'))
    //                 ->where('月請購_單耗.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "單耗")->where('consumptive_material.月請購', '=', "是");

    //             $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存'))
    //                 ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


    //             $datas2 = DB::table('月請購_站位')
    //                 ->join('MPS', function ($join) {
    //                     $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
    //                         ->on('MPS.機種', '=', '月請購_站位.機種')
    //                         ->on('MPS.製程', '=', '月請購_站位.製程');
    //                 })
    //                 ->join('consumptive_material', function ($join) {
    //                     $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
    //                 })
    //                 ->select('MPS.*', '月請購_站位.*', DB::raw('(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
    //                 * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
    //                 ->where('月請購_站位.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "站位");

    //             $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
    //             * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
    //                 ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();


    //             for ($i = 0; $i < count($datas); $i++) {

    //                 $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
    //                 $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
    //                 $interval = date_diff($maxtime, $nowtime);
    //                 $interval = $interval->format('%R%a');
    //                 $interval = (int) $interval;
    //                 $datas[$i]->呆滯天數 = $interval;
    //                 if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
    //                 for ($j = 0; $j < count($datas1); $j++) {
    //                     if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
    //                         $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
    //                     }
    //                 }
    //                 for ($k = 0; $k < count($datas2); $k++) {
    //                     if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
    //                         $datas[$i]->安全庫存 = round($datas2[$k]->安全庫存, 3);
    //                     }
    //                 }
    //             }

    //             dd($datas);
    //         }
    //         //normal inventory
    //         else {

    //             $datas = DB::table('consumptive_material')
    //                 ->join('inventory', function ($join) {
    //                     $join->on('inventory.料號', '=', 'consumptive_material.料號');
    //                 })->where('consumptive_material.料號', 'like', $number . '%')
    //                 ->where('inventory.客戶別', 'like', $client . '%')
    //                 ->where('inventory.儲位', 'like', $position . '%')
    //                 ->where('consumptive_material.發料部門', 'like', $send . '%')
    //                 ->where('現有庫存', '>', 0)
    //                 ->get();


    //             $datas1 = DB::table('月請購_單耗')
    //                 ->join('MPS', function ($join) {
    //                     $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
    //                         ->on('MPS.機種', '=', '月請購_單耗.機種')
    //                         ->on('MPS.製程', '=', '月請購_單耗.製程');
    //                 })
    //                 ->join('consumptive_material', function ($join) {
    //                     $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
    //                 })
    //                 ->select('MPS.下月MPS', 'MPS.下月生產天數', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存)'))
    //                 ->where('月請購_單耗.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "單耗")->where('consumptive_material.月請購', '=', "是");

    //             $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存'))
    //                 ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


    //             $datas2 = DB::table('月請購_站位')
    //                 ->join('MPS', function ($join) {
    //                     $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
    //                         ->on('MPS.機種', '=', '月請購_站位.機種')
    //                         ->on('MPS.製程', '=', '月請購_站位.製程');
    //                 })
    //                 ->join('consumptive_material', function ($join) {
    //                     $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
    //                 })
    //                 ->select('MPS.*', '月請購_站位.*', DB::raw('(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
    //                 * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
    //                 ->where('月請購_站位.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "站位");

    //             $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
    //             * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
    //                 ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();


    //             for ($i = 0; $i < count($datas); $i++) {

    //                 $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
    //                 $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
    //                 $interval = date_diff($maxtime, $nowtime);
    //                 $interval = $interval->format('%R%a');
    //                 $interval = (int) $interval;
    //                 $datas[$i]->呆滯天數 = $interval;
    //                 if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
    //                 for ($j = 0; $j < count($datas1); $j++) {
    //                     if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
    //                         $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
    //                     }
    //                 }
    //                 for ($k = 0; $k < count($datas2); $k++) {
    //                     if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
    //                         $datas[$i]->安全庫存 = round($datas2[$k]->安全庫存, 3);
    //                     }
    //                 }
    //             }

    //             dd($datas);
    //         }
    //         //庫存使用月數
    //         if ($request->has('month')) {
    //             $test = DB::table('inventory')
    //                 ->select('料號', '客戶別', DB::raw('SUM(現有庫存) as 現有庫存'))
    //                 ->groupBy('料號', '客戶別');

    //             $datas = DB::table('consumptive_material')
    //                 ->joinSub($test, 'inventory', function ($join) {
    //                     $join->on('inventory.料號', '=', 'consumptive_material.料號');
    //                 })->where('consumptive_material.料號', 'like', $number . '%')
    //                 ->where('inventory.客戶別', 'like', $client . '%')
    //                 ->where('consumptive_material.發料部門', 'like', $send . '%')
    //                 ->where('現有庫存', '>', 0)->get();

    //             $datas1 = DB::table('月請購_單耗')
    //                 ->join('MPS', function ($join) {
    //                     $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
    //                         ->on('MPS.機種', '=', '月請購_單耗.機種')
    //                         ->on('MPS.製程', '=', '月請購_單耗.製程');
    //                 })
    //                 ->join('consumptive_material', function ($join) {
    //                     $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
    //                 })
    //                 ->select('MPS.本月MPS', '月請購_單耗.*', DB::raw('(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量)'))
    //                 ->where('月請購_單耗.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "單耗");

    //             $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量'))
    //                 ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


    //             $datas2 = DB::table('月請購_站位')
    //                 ->join('MPS', function ($join) {
    //                     $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
    //                         ->on('MPS.機種', '=', '月請購_站位.機種')
    //                         ->on('MPS.製程', '=', '月請購_站位.製程');
    //                 })
    //                 ->join('consumptive_material', function ($join) {
    //                     $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
    //                 })
    //                 ->select('MPS.*', '月請購_站位.*', DB::raw('(MPS.本月生產天數 * 月請購_站位.當月站位人數 * 月請購_站位.當月開線數
    //                 * 月請購_站位.當月開班數 * 月請購_站位.當月每人每日需求量 * 月請購_站位.當月每日更換頻率 / consumptive_material.MPQ) as 月使用量'))
    //                 ->where('月請購_站位.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "站位");

    //             $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(MPS.本月生產天數 * 月請購_站位.當月站位人數 * 月請購_站位.當月開線數
    //             * 月請購_站位.當月開班數 * 月請購_站位.當月每人每日需求量 * 月請購_站位.當月每日更換頻率 / consumptive_material.MPQ) as 月使用量'))
    //                 ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();

    //             for ($i = 0; $i < count($datas); $i++) {
    //                 for ($j = 0; $j < count($datas1); $j++) {
    //                     if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
    //                         $datas[$i]->月使用量 = $datas1[$j]->月使用量;
    //                         $datas[$i]->庫存使用月數 = round($datas[$i]->現有庫存 / $datas1[$j]->月使用量, 5);
    //                     }
    //                 }
    //                 for ($k = 0; $k < count($datas2); $k++) {
    //                     if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
    //                         $datas[$i]->月使用量 = $datas2[$k]->月使用量;
    //                         $datas[$i]->庫存使用月數 = round($datas[$i]->現有庫存 / $datas1[$j]->月使用量, 5);
    //                     }
    //                 }
    //             }

    //             //補月使用量
    //             for ($i = 0; $i < count($datas); $i++) {
    //                 if (!(property_exists($datas[$i], "月使用量"))) {
    //                     $datas[$i]->月使用量 = 0;
    //                     $datas[$i]->庫存使用月數 = 0;
    //                 }
    //             }

    //             dd(($datas1));
    //             return view('inbound.searchstockok1')->with(['data' => $datas]);
    //         } else {

    //             return view('inbound.searchstockok')->with(['data' => $datas]);
    //         }
    //     } else {
    //         return redirect(route('member.login'));
    //     } // else
    // }

    //入庫-新增
    public function addnew(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('submit') === '0') {
                if ($request->input('client') !== null && $request->input('inreason') !== null) {
                    $client = $request->input('client');
                    $inreason = $request->input('inreason');
                    $number = $request->input('number');

                    //不等於12
                    if ($number !== ' ' && $number !== null && strlen($number) !== 12) {
                        return \Response::json(['message' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
                    } else {
                        $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
                        $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
                        $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
                        $amount = DB::table('在途量')->where('料號', $number)->where('客戶', $client)->sum('請購數量');
                        $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->sum('現有庫存');
                        $positions = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->pluck('儲位');
                        $belong = DB::table('consumptive_material')->where('料號', $number)->value('耗材歸屬');
                        $lt = DB::table('consumptive_material')->where('料號', $number)->value('LT');
                        $month = DB::table('consumptive_material')->where('料號', $number)->value('月請購');

                        $showstock  = '';
                        $nowstock = DB::table('inventory')->where('料號', $number)->where('客戶別', $client)->where('現有庫存', '>', 0)->pluck('現有庫存')->toArray();
                        $nowloc = DB::table('inventory')->where('料號', $number)->where('客戶別', $client)->where('現有庫存', '>', 0)->pluck('儲位')->toArray();
                        $test = array_combine($nowloc, $nowstock);
                        foreach ($test as $k => $a) {
                            $showstock = $showstock . __('outboundpageLang.loc') . ' : ' . $k . ' ' . __('outboundpageLang.nowstock') . ' : ' . $a . "\n";
                        }

                        //無料號
                        if ($name === null || $format === null) {
                            return \Response::json(['message' => 'No Results Found!'], 421/* Status code here default is 200 ok*/);
                        } else {
                            //在途量為0
                            if ((int)$amount === 0 && $inreason !== '調撥' &&  $inreason !== '退庫') {
                                return \Response::json(['message' => 'No Results Found!'], 422/* Status code here default is 200 ok*/);
                            } else {

                                if ($month === '否') {
                                    $safe = DB::table('consumptive_material')->where('料號', $number)->value('安全庫存');
                                } else {
                                    if ($belong === '單耗') {
                                        $machine = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)->value('機種');
                                        $production = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)->value('製程');
                                        $consume = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)->value('單耗');
                                        $nextmps = DB::table('MPS')->where('機種', $machine)->where('客戶別', $client)->where('製程', $production)->value('下月MPS');
                                        $nextday = DB::table('MPS')->where('機種', $machine)->where('客戶別', $client)->where('製程', $production)->value('下月生產天數');
                                        if ($nextday == 0) {
                                            $safe = 0;
                                        } else {
                                            $safe = $lt * $consume * $nextmps / $nextday;
                                        }
                                    } else {
                                        $machine = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('機種');
                                        $production = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('製程');
                                        $nextstand = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月站位人數');
                                        $nextline = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月開線數');
                                        $nextclass = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月開班數');
                                        $nextuse = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月每人每日需求量');
                                        $nextchange = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月每日更換頻率');
                                        $mpq = DB::table('consumptive_material')->where('料號', $number)->value('MPQ');
                                        if ($mpq == 0) {
                                            $safe = 0;
                                        } else {
                                            $safe = $lt * $nextstand * $nextline * $nextclass * $nextuse * $nextchange / $mpq;
                                        }
                                    }
                                }
                                $amount = round($amount);

                                return \Response::json([
                                    'type' => 'add',
                                    'number' => $number, 'client' => $client, 'inreason' => $inreason,
                                    'transit' => $amount, 'stock' => $stock, 'safe' => $safe, 'name' => $name,
                                    'format' => $format, 'unit' => $unit, 'positions' => $positions, 'showstock' => $showstock,
                                ]/* Status code here default is 200 ok*/);
                            }
                        }
                    }
                } else {
                    return view('inbound.add');
                }
            } else {
                Session::put('addclient', $request->input('client'));
                Session::put('client', $request->input('client'));
                Session::put('inreason', $request->input('inreason'));
                return \Response::json(['type' => 'client']/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //入庫-新增提交
    public function addnewsubmit(Request $request)
    {
        if (Session::has('username')) {
            $Alldata = json_decode($request->input('AllData'));
            $count = count($Alldata[0]);
            $j = '0001';
            $max = DB::table('inbound')->max('入庫時間');
            $maxtime = date_create(date('Y-m-d', strtotime($max)));
            $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int)($interval);
            if ($interval > 0) {
                $opentime = Carbon::now()->format('Ymd') . $j;
            } else {
                $num = DB::table('inbound')->max('入庫單號');
                $num = intval($num);
                $num++;
                $num = strval($num);
                $opentime = $num;
            }
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $client = $Alldata[0][$i];
                    $number = $Alldata[1][$i];
                    $position = $Alldata[2][$i];
                    $amount = $Alldata[3][$i];
                    $inreason = $Alldata[4][$i];

                    $now = Carbon::now();
                    $inpeople = $request->input('inpeople');
                    $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $position)->value('現有庫存');
                    $buy = DB::table('在途量')->where('客戶', $client)->where('料號', $number)->value('請購數量');

                    if ($amount > $buy && $inreason !== '調撥' && $inreason !== '退庫') {
                        $row = $i + 1;
                        return \Response::json(['row' => $row, 'client' => $client, 'number' => $number], 420/* Status code here default is 200 ok*/);
                    }
                    if ($stock !== null) {
                        DB::table('inbound')
                            ->insert([
                                '入庫單號' => $opentime, '料號' => $number, '入庫數量' => $amount, '儲位' => $position,
                                '入庫人員' => $inpeople, '客戶別' => $client, '入庫原因' => $inreason, '入庫時間' => $now
                            ]);

                        DB::table('inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('儲位', $position)
                            ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $now]);
                        if ($inreason !== '調撥' && $inreason !== '退庫') {
                            DB::table('在途量')
                                ->where('客戶', $client)
                                ->where('料號', $number)
                                ->update(['請購數量' => $buy - $amount]);
                        } //if reason
                    } //if stock
                    else {
                        DB::table('inbound')
                            ->insert([
                                '入庫單號' => $opentime, '料號' => $number, '入庫數量' => $amount, '儲位' => $position,
                                '入庫人員' => $inpeople, '客戶別' => $client, '入庫原因' => $inreason, '入庫時間' => $now
                            ]);

                        DB::table('inventory')
                            ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $position, '客戶別' => $client, '最後更新時間' => $now]);
                        if ($inreason !== '調撥' && $inreason !== '退庫') {
                            DB::table('在途量')
                                ->where('客戶', $client)
                                ->where('料號', $number)
                                ->update(['請購數量' => $buy - $amount]);
                        }
                    } //else
                } // for
                DB::commit();
                return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //刪除
    public function delete(Request $request)
    {
        if (Session::has('username')) {
            $now = Carbon::now();
            $list = $request->input('list');
            $number = $request->input('number');
            $amount = $request->input('amount');
            $position = $request->input('position');
            $inpeople = $request->input('inpeople');
            $client = $request->input('client');
            $inreason = $request->input('inreason');
            $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $position)->value('現有庫存');
            $buy = DB::table('在途量')->where('客戶', $client)->where('料號', $number)->value('請購數量');
            DB::beginTransaction();
            try {
                if ($stock < $amount) {
                    if ($stock === null) $stock = 0;

                    return \Response::json(['stock' => $stock, 'amount' => $amount], 420/* Status code here default is 200 ok*/);
                }
                if ($inreason !== '調撥' && $inreason !== '退庫') {
                    DB::table('在途量')
                        ->where('客戶', $client)
                        ->where('料號', $number)
                        ->update(['請購數量' => $buy + $amount]);
                }
                DB::table('inventory')
                    ->where('客戶別', $client)
                    ->where('料號', $number)
                    ->where('儲位', $position)
                    ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $now]);

                DB::table('inbound')
                    ->where('入庫單號', $list)
                    ->where('客戶別', $client)
                    ->where('料號', $number)
                    ->where('入庫數量', $amount)
                    ->where('入庫人員', $inpeople)
                    ->where('入庫原因', $inreason)
                    ->where('儲位', $position)
                    ->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            }
            return \Response::json(['list' => $list, 'client' => $client, 'number' => $number]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }

    //download
    public function download(Request $request)
    {
        if (Session::has('username')) {

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
            $worksheet = $spreadsheet->getActiveSheet();
            $titlecount = $request->input('titlecount');
            $nogood = $request->input('nogood');

            $titlename = $request->input('titlename');
            //庫存
            if ($titlename === "庫存") {
                // 不良品
                if ($nogood === "2") {
                    $titlename = "不良品庫存";
                    $datas = DB::table('consumptive_material')
                        ->join('不良品inventory', function ($join) {
                            $join->on('不良品inventory.料號', '=', 'consumptive_material.料號');
                        })
                        ->where('現有庫存', '>', 0)->get();

                    $datas1 = DB::table('月請購_單耗')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                                ->on('MPS.機種', '=', '月請購_單耗.機種')
                                ->on('MPS.製程', '=', '月請購_單耗.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
                        })
                        ->select('MPS.下月MPS', 'MPS.下月生產天數', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存)'))
                        ->where('月請購_單耗.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "單耗")->where('consumptive_material.月請購', '=', "是");

                    $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存'))
                        ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


                    $datas2 = DB::table('月請購_站位')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                                ->on('MPS.機種', '=', '月請購_站位.機種')
                                ->on('MPS.製程', '=', '月請購_站位.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
                        })
                        ->select('MPS.*', '月請購_站位.*', DB::raw('(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                        * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
                        ->where('月請購_站位.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "站位");

                    $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                        * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
                        ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();


                    for ($i = 0; $i < count($datas); $i++) {

                        $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
                        $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
                        $interval = date_diff($maxtime, $nowtime);
                        $interval = $interval->format('%R%a');
                        $interval = (int) $interval;
                        $datas[$i]->呆滯天數 = $interval;
                        if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
                        $datas[$i]->單價 = round($datas[$i]->單價, 5);
                        for ($j = 0; $j < count($datas1); $j++) {
                            if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
                                $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
                            }
                        }
                        for ($k = 0; $k < count($datas2); $k++) {
                            if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
                                $datas[$i]->安全庫存 = round($datas2[$k]->安全庫存, 3);
                            }
                        }
                    }
                }
                //良品
                else {
                    $datas = DB::table('consumptive_material')
                        ->join('inventory', function ($join) {
                            $join->on('inventory.料號', '=', 'consumptive_material.料號');
                        })
                        ->where('現有庫存', '>', 0)
                        ->get();

                    $datas1 = DB::table('月請購_單耗')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                                ->on('MPS.機種', '=', '月請購_單耗.機種')
                                ->on('MPS.製程', '=', '月請購_單耗.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
                        })
                        ->select('MPS.下月MPS', 'MPS.下月生產天數', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存)'))
                        ->where('月請購_單耗.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "單耗")->where('consumptive_material.月請購', '=', "是");

                    $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存'))
                        ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


                    $datas2 = DB::table('月請購_站位')
                        ->join('MPS', function ($join) {
                            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                                ->on('MPS.機種', '=', '月請購_站位.機種')
                                ->on('MPS.製程', '=', '月請購_站位.製程');
                        })
                        ->join('consumptive_material', function ($join) {
                            $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
                        })
                        ->select('MPS.*', '月請購_站位.*', DB::raw('(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                        * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
                        ->where('月請購_站位.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "站位");

                    $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                        * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
                        ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();


                    for ($i = 0; $i < count($datas); $i++) {

                        $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
                        $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
                        $interval = date_diff($maxtime, $nowtime);
                        $interval = $interval->format('%R%a');
                        $interval = (int) $interval;
                        $datas[$i]->呆滯天數 = $interval;
                        if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
                        $datas[$i]->單價 = round($datas[$i]->單價, 5);
                        for ($j = 0; $j < count($datas1); $j++) {
                            if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
                                $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
                            }
                        }
                        for ($k = 0; $k < count($datas2); $k++) {
                            if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
                                $datas[$i]->安全庫存 = round($datas2[$k]->安全庫存, 3);
                            }
                        }
                    }
                }
            }
            //入庫查詢
            else if ($titlename === "入庫查詢") {
                $datas = DB::table('inbound')->get();
            }
            //庫存使用月數
            else {
                $test = DB::table('inventory')
                    ->select('料號', '客戶別', DB::raw('SUM(現有庫存) as 現有庫存'))
                    ->groupBy('料號', '客戶別');

                $datas = DB::table('consumptive_material')
                    ->joinSub($test, 'inventory', function ($join) {
                        $join->on('inventory.料號', '=', 'consumptive_material.料號');
                    })
                    ->where('現有庫存', '>', 0)->get();

                $datas1 = DB::table('月請購_單耗')
                    ->join('MPS', function ($join) {
                        $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                            ->on('MPS.機種', '=', '月請購_單耗.機種')
                            ->on('MPS.製程', '=', '月請購_單耗.製程');
                    })
                    ->join('consumptive_material', function ($join) {
                        $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
                    })
                    ->select('MPS.本月MPS', '月請購_單耗.*', DB::raw('(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量)'))
                    ->where('月請購_單耗.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "單耗");

                $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量'))
                    ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


                $datas2 = DB::table('月請購_站位')
                    ->join('MPS', function ($join) {
                        $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                            ->on('MPS.機種', '=', '月請購_站位.機種')
                            ->on('MPS.製程', '=', '月請購_站位.製程');
                    })
                    ->join('consumptive_material', function ($join) {
                        $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
                    })
                    ->select('MPS.*', '月請購_站位.*', DB::raw('(MPS.本月生產天數 * 月請購_站位.當月站位人數 * 月請購_站位.當月開線數
                * 月請購_站位.當月開班數 * 月請購_站位.當月每人每日需求量 * 月請購_站位.當月每日更換頻率 / consumptive_material.MPQ) as 月使用量'))
                    ->where('月請購_站位.狀態', '=', "已完成")->where('consumptive_material.耗材歸屬', '=', "站位");

                $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(MPS.本月生產天數 * 月請購_站位.當月站位人數 * 月請購_站位.當月開線數
                * 月請購_站位.當月開班數 * 月請購_站位.當月每人每日需求量 * 月請購_站位.當月每日更換頻率 / consumptive_material.MPQ) as 月使用量'))
                    ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();

                for ($i = 0; $i < count($datas); $i++) {
                    for ($j = 0; $j < count($datas1); $j++) {
                        if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
                            $datas[$i]->月使用量 = $datas1[$j]->月使用量;
                            $datas[$i]->庫存使用月數 = round($datas[$i]->現有庫存 / $datas1[$j]->月使用量, 5);
                        }
                    }
                    for ($k = 0; $k < count($datas2); $k++) {
                        if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
                            $datas[$i]->月使用量 = $datas2[$k]->月使用量;
                            $datas[$i]->庫存使用月數 = round($datas[$i]->現有庫存 / $datas1[$j]->月使用量, 5);
                        }
                    }
                }

                //補月使用量
                for ($i = 0; $i < count($datas); $i++) {
                    $datas[$i]->單價 = round($datas[$i]->單價, 5);
                    if (!(property_exists($datas[$i], "月使用量"))) {
                        $datas[$i]->月使用量 = 0;
                        $datas[$i]->庫存使用月數 = 0;
                    }
                }
            }

            $count = count($datas);

            //填寫表頭
            for ($i = 0; $i < $titlecount; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title')[$i]);
            }

            //填寫內容
            for ($i = 0; $i < $titlecount; $i++) {
                $string = $request->input('titlecol')[$i];
                for ($j = 0; $j < $count; $j++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $datas[$j]->$string);
                }
            }


            // 下載
            // $now = Carbon::now()->format('YmdHis');
            // $titlename = $request->input('titlename');
            // $filename = rawurlencode($titlename) . $now . '.xlsx';
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename="' . $filename . '"; filename*=utf-8\'\'' . $filename . ';');
            // header('Cache-Control: max-age=0');

            // $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Content-Disposition: attachment;filename="' . $filename . '"', 'Cache-Control: max-age=0'];
            // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            // $writer->save('php://output');

            // $callback = function () use ($writer) {
            //     $file = fopen('php://output', 'r');
            //     fclose($file);
            // };

            $now = Carbon::now()->format('YmdHis');
            $filename = rawurlencode($titlename) . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');

            // return response()->stream($callback, 200, $headers);
        } else {
            return redirect(route('member.login'));
        }
    }

    //庫存上傳
    public function uploadinventory(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('inbound.uploadinventory')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //上傳資料新增至資料庫
    public function insertuploadinventory(Request $request)
    {
        if (Session::has('username')) {
            $positions = DB::table('儲位')->pluck('儲存位置')->toArray();
            $count = $request->input('count');
            $now = Carbon::now();
            $Alldata = json_decode($request->input('AllData'));
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {

                    if (!(in_array($Alldata[3][$i], $positions))) {
                        DB::table('儲位')
                            ->insert(['儲存位置' => $Alldata[5][$i]]);
                    }

                    $stock = DB::table('inventory')->where('客戶別', $Alldata[0][$i])->where('料號', $Alldata[1][$i])->where('儲位', $Alldata[3][$i])->value('現有庫存');

                    if ($stock === null) {
                        DB::table('inventory')
                            ->insert(['料號' => $Alldata[1][$i], '現有庫存' => $Alldata[2][$i], '儲位' => $Alldata[3][$i], '客戶別' => $Alldata[0][$i], '最後更新時間' => $now]);
                    } else {
                        DB::table('inventory')
                            ->where('客戶別', $Alldata[0][$i])
                            ->where('料號', $Alldata[1][$i])
                            ->where('儲位', $Alldata[3][$i])
                            ->update(['現有庫存' => $stock + $Alldata[2][$i], '最後更新時間' => $now]);
                    }
                } // for
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getmessage();
                return \Response::json(['message' => $mess], 420/* Status code here default is 200 ok*/);
            }
            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }
}
