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
        $test = DB::table('inventory')
            ->select('料號', '儲位', '最後更新時間', DB::raw('SUM(現有庫存) as 現有庫存'))
            ->groupBy('料號', '儲位', '最後更新時間');

        $datas = DB::table('consumptive_material')
            ->joinSub($test, 'inventory', function ($join) {
                $join->on('inventory.料號', '=', 'consumptive_material.料號');
            })->where('現有庫存', '>', 0)
            ->where('consumptive_material.料號', 'like', $request->input('number') . '%')
            ->where('inventory.儲位', 'like', $request->input('position') . '%')
            ->where('consumptive_material.發料部門', 'like', $request->input('send') . '%')->get();


        return view('inbound.change')->with(['data' => $datas]);
    }

    //入庫-儲位調撥提交
    public function changesubmit(Request $request)
    {
        $number = $request->input('number');
        $oldposition = $request->input('oldposition');
        $amount = $request->input('amount');
        $newposition = $request->input('newposition');
        //$stock = $request->input('stock');
        $now = Carbon::now();
        $test = DB::table('inventory')
            ->where('料號', $number)
            ->where('儲位', $newposition)
            ->value('現有庫存');

        DB::beginTransaction();
        try {

            if ($test !== null) {
                DB::table('inventory')
                    ->where('料號', $number)
                    ->where('儲位', $newposition)
                    ->update(['現有庫存' => $test + $amount, '最後更新時間' => $now]);
            } else {
                DB::table('inventory')
                    ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $newposition, '最後更新時間' => $now]);
            }

            $stock = DB::table('inventory')
                ->where('料號', $number)
                ->where('儲位', $oldposition)
                ->value('現有庫存');
            DB::table('inventory')
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
    }

    //入庫-新增
    public function addnew(Request $request)
    {
        $inreason = $request->input('inreason');
        $number = $request->input('number');

        //不等於12
        if ($number !== ' ' && strlen($number) !== 12 || $number === null) {
            return \Response::json(['message' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
        } else {
            $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
            $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
            $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
            $amount = DB::table('在途量')->where('料號', $number)->sum('請購數量');
            $stock = DB::table('inventory')->where('料號', $number)->sum('現有庫存');
            $positions = DB::table('inventory')->where('料號', $number)->where('現有庫存', '>', 0)->pluck('儲位');

            $showstock  = '';
            $nowstock = DB::table('inventory')->where('料號', $number)->where('現有庫存', '>', 0)->pluck('現有庫存')->toArray();
            $nowloc = DB::table('inventory')->where('料號', $number)->where('現有庫存', '>', 0)->pluck('儲位')->toArray();
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

                    // if ($month === '否') {
                    //     $safe = DB::table('consumptive_material')->where('料號', $number)->value('安全庫存');
                    // } else {
                    //     if ($belong === '單耗') {
                    //         $machine = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)->value('機種');
                    //         $production = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)->value('製程');
                    //         $consume = DB::table('月請購_單耗')->where('料號', $number)->where('客戶別', $client)->value('單耗');
                    //         $nextmps = DB::table('MPS')->where('機種', $machine)->where('客戶別', $client)->where('製程', $production)->value('下月MPS');
                    //         $nextday = DB::table('MPS')->where('機種', $machine)->where('客戶別', $client)->where('製程', $production)->value('下月生產天數');
                    //         if ($nextday == 0) {
                    //             $safe = 0;
                    //         } else {
                    //             $safe = $lt * $consume * $nextmps / $nextday;
                    //         }
                    //     } else {
                    //         $machine = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('機種');
                    //         $production = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('製程');
                    //         $nextstand = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月站位人數');
                    //         $nextline = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月開線數');
                    //         $nextclass = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月開班數');
                    //         $nextuse = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月每人每日需求量');
                    //         $nextchange = DB::table('月請購_站位')->where('料號', $number)->where('客戶別', $client)->value('下月每日更換頻率');
                    //         $mpq = DB::table('consumptive_material')->where('料號', $number)->value('MPQ');
                    //         if ($mpq == 0) {
                    //             $safe = 0;
                    //         } else {
                    //             $safe = $lt * $nextstand * $nextline * $nextclass * $nextuse * $nextchange / $mpq;
                    //         }
                    //     }

                    $amount = round($amount);

                    return \Response::json([
                        'type' => 'add',
                        'number' => $number,  'inreason' => $inreason,
                        'transit' => $amount, 'stock' => $stock, 'name' => $name,
                        'format' => $format, 'unit' => $unit, 'positions' => $positions, 'showstock' => $showstock,
                    ]/* Status code here default is 200 ok*/);
                }
            }
        }

        // } else {
        //     Session::put('addclient', $request->input('client'));
        //     Session::put('client', $request->input('client'));
        //     Session::put('inreason', $request->input('inreason'));
        //     return \Response::json(['type' => 'client']/* Status code here default is 200 ok*/);
        // }
    }

    //入庫-新增提交
    public function addnewsubmit(Request $request)
    {
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
                $number = $Alldata[0][$i];
                $position = $Alldata[1][$i];
                $amount = $Alldata[2][$i];
                $inreason = $Alldata[3][$i];

                $now = Carbon::now();
                $inpeople = $request->input('inpeople');
                $stock = DB::table('inventory')->where('料號', $number)->where('儲位', $position)->value('現有庫存');
                $buy = DB::table('在途量')->where('料號', $number)->sum('請購數量');

                if ($amount > $buy && $inreason !== '調撥' && $inreason !== '退庫') {
                    $row = $i + 1;
                    return \Response::json(['row' => $row, 'number' => $number], 420/* Status code here default is 200 ok*/);
                } else {
                    if ($stock !== null) {
                        DB::table('inventory')
                            ->where('料號', $number)
                            ->where('儲位', $position)
                            ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $now]);
                    } //if stock
                    else {
                        DB::table('inventory')
                            ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $position, '最後更新時間' => $now]);
                    }
                    DB::table('inbound')
                        ->insert([
                            '入庫單號' => $opentime, '料號' => $number, '入庫數量' => $amount, '儲位' => $position,
                            '入庫人員' => $inpeople, '入庫原因' => $inreason, '入庫時間' => $now
                        ]);

                    if ($inreason !== '調撥' && $inreason !== '退庫') {
                        DB::table('在途量')
                            ->where('料號', $number)
                            ->update(['請購數量' => $buy - $amount]);
                    } //else
                } // for
            }
            DB::commit();
            return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        }
    } // addnewsubmit
}
