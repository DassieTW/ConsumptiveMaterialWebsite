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

class CallController extends Controller
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
            return view('call.index');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*//安全庫存警報頁面
    public function safe()
    {
        //
        if(Session::has('username'))
        {
            return view('call.safepage');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/


    //安全庫存警報
    public function safesubmit(Request $request)
    {
        //
        if (Session::has('username')) {
            $send = $request->input('send');
            if ($send === null) {
                $datas = DB::table('月請購_單耗')
                    ->join('MPS', function ($join) {
                        $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                            ->on('MPS.機種', '=', '月請購_單耗.機種')
                            ->on('MPS.製程', '=', '月請購_單耗.製程');
                    })
                    ->join('consumptive_material', function ($join) {
                        $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                    })
                    ->join('inventory', function ($join) {
                        $join->on('consumptive_material.料號', '=', 'inventory.料號');
                    })
                    ->select(
                        '月請購_單耗.單耗',
                        '月請購_單耗.客戶別',
                        'inventory.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.MPQ',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'MPS.下月MPS',
                        'MPS.下月生產天數',

                        DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                    )
                    ->groupBy(
                        'inventory.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.MPQ',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        '月請購_單耗.單耗',
                        '月請購_單耗.客戶別',
                        'MPS.下月MPS',
                        'MPS.下月生產天數',
                    )
                    ->where('月請購_單耗.狀態', '=', "已完成")
                    ->get();

                foreach ($datas as $data) {

                    if ($data->月請購 === '否') {
                        $safe = $data->安全庫存;
                    } else {
                        $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
                    }

                    $data->安全庫存 = round($safe);
                }

                $datas1 = DB::table('月請購_站位')
                ->join('MPS', function($join)
                {
                    $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                    ->on('MPS.機種', '=', '月請購_站位.機種')
                    ->on('MPS.製程', '=', '月請購_站位.製程');
                })
                ->join('consumptive_material', function($join)
                {
                    $join->on('consumptive_material.料號', '=', '月請購_站位.料號');

                })->where('月請購_站位.狀態','=',"已完成")
                ->get();


                foreach ($datas1 as $data) {

                    if ($data->月請購 === '否') {
                        $safe = $data->安全庫存;
                    } else {
                        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
                    }

                    $data->安全庫存 = $safe;
                }


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
                    ->where('月請購_單耗.狀態', '=', "已完成")
                    ->get();

                foreach ($datas as $data) {

                    if ($data->月請購 === '否') {
                        $safe = $data->安全庫存;
                    } else {
                        $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
                    }

                    $data->安全庫存 = $safe;
                }

                $datas1 = DB::table('月請購_站位')
                    ->join('MPS', function ($join) {
                        $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                            ->on('MPS.機種', '=', '月請購_站位.機種')
                            ->on('MPS.製程', '=', '月請購_站位.製程');
                    })
                    ->join('consumptive_material', function ($join) {
                        $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                    })->where('consumptive_material.發料部門', $send)
                    ->where('月請購_站位.狀態', '=', "已完成")
                    ->get()->unique('料號');

                foreach ($datas1 as $data) {

                    if ($data->月請購 === '否') {
                        $safe = $data->安全庫存;
                    } else {
                        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
                    }

                    $data->安全庫存 = round($safe);
                }
            }
            return view('call.safe')->with(['data' => $datas])->with(['data1' => $datas1]);
        } else {
            return redirect(route('member.login'));
        }
    }

    /*//呆滯天數警報頁面
    public function day()
    {
        //
        if(Session::has('username'))
        {
            return view('call.daypage');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    //呆滯天數警報
    public function daysubmit(Request $request)
    {
        //
        if (Session::has('username')) {
            $send = $request->input('send');
            if ($send === null) {
                $datas = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                    ->select(
                        'inventory.客戶別',
                        'inventory.料號',
                        DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                    )
                    ->groupBy('inventory.客戶別', 'inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格',)
                    ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')   // online setting
                    ->get();
            } else {
                $datas = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                    ->select(
                        '客戶別',
                        'inventory.料號',
                        DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                    )
                    ->groupBy('inventory.客戶別', 'inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格',)
                    ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')   // online setting
                    ->where('consumptive_material.發料部門', $send)
                    ->get();
            }
            // dd($datas);
            return view('call.day')->with(['data' => $datas]);
        } else {
            return redirect(route('member.login'));
        }
    }
}
