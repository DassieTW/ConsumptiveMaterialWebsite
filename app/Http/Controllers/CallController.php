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
                $inventorys = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號'))->groupBy('客戶別', '料號');
                $datas = DB::table('月請購_單耗')
                    ->join('MPS', function ($join) {
                        $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                            ->on('MPS.機種', '=', '月請購_單耗.機種')
                            ->on('MPS.製程', '=', '月請購_單耗.製程');
                    })
                    ->join('consumptive_material', function ($join) {
                        $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
                    })
                    ->joinSub($inventorys, 'suminventory', function ($join) {
                        $join->on('月請購_單耗.客戶別', '=', 'suminventory.客戶別');
                        $join->on('月請購_單耗.料號', '=', 'suminventory.料號');
                    })

                    ->select(
                        '月請購_單耗.客戶別',
                        'consumptive_material.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.安全庫存',
                        '月請購_單耗.單耗',
                        'MPS.下月MPS',
                        'MPS.下月生產天數',
                        'inventory現有庫存',
                    )->groupBy(
                        '月請購_單耗.客戶別',
                        'consumptive_material.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.安全庫存',
                        '月請購_單耗.單耗',
                        'MPS.下月MPS',
                        'MPS.下月生產天數',
                        'inventory現有庫存',
                    )
                    // ->where('月請購_單耗.狀態', '=', "已完成")
                    ->get();


                foreach ($datas as $data) {

                    if ($data->月請購 === '否') {
                        $safe = $data->安全庫存;
                    } else {
                        $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
                    }

                    $data->安全庫存 = round($safe);
                } // for each



                for ($a = 0; $a < count($datas); $a++) {
                    for ($i = $a; $i + 1 < count($datas); $i++) {
                        if ($datas[$a]->客戶別 === $datas[$i + 1]->客戶別 && $datas[$a]->料號 === $datas[$i + 1]->料號) {


                            $datas[$a]->安全庫存 += $datas[$i + 1]->安全庫存;

                            unset($datas[$i + 1]);
                        } // if
                    } // for
                } // for

                foreach ($datas as $key => $value) {


                    if ($value->inventory現有庫存 > $value->安全庫存) {
                        unset($datas[$key]);
                    }
                }

                $inventorys1 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號'))->groupBy('客戶別', '料號');
                $datas1 = DB::table('月請購_站位')
                    ->join('MPS', function ($join) {
                        $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                            ->on('MPS.機種', '=', '月請購_站位.機種')
                            ->on('MPS.製程', '=', '月請購_站位.製程');
                    })
                    ->join('consumptive_material', function ($join) {
                        $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                    })
                    ->joinSub($inventorys1, 'suminventory', function ($join) {
                        $join->on('月請購_站位.客戶別', '=', 'suminventory.客戶別');
                        $join->on('月請購_站位.料號', '=', 'suminventory.料號');
                    })

                    ->select(
                        '月請購_站位.客戶別',
                        'consumptive_material.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.MPQ',
                        'consumptive_material.安全庫存',
                        '月請購_站位.下月站位人數',
                        '月請購_站位.下月開線數',
                        '月請購_站位.下月開班數',
                        '月請購_站位.下月每人每日需求量',
                        '月請購_站位.下月每日更換頻率',
                        'inventory現有庫存',
                    )->groupBy(
                        '月請購_站位.客戶別',
                        'consumptive_material.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.MPQ',
                        'consumptive_material.安全庫存',
                        '月請購_站位.下月站位人數',
                        '月請購_站位.下月開線數',
                        '月請購_站位.下月開班數',
                        '月請購_站位.下月每人每日需求量',
                        '月請購_站位.下月每日更換頻率',
                        'inventory現有庫存',
                    )
                    // ->where('月請購_單耗.狀態', '=', "已完成")
                    ->get();


                foreach ($datas1 as $data) {

                    if ($data->月請購 === '否') {
                        $safe = $data->安全庫存;
                    } else {

                        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
                    }

                    $data->安全庫存 = round($safe);
                }



                for ($a = 0; $a < count($datas1); $a++) {
                    for ($i = $a; $i + 1 < count($datas1); $i++) {
                        if ($datas1[$a]->客戶別 === $datas1[$i + 1]->客戶別 && $datas1[$a]->料號 === $datas1[$i + 1]->料號) {


                            $datas1[$a]->安全庫存 += $datas1[$i + 1]->安全庫存;

                            unset($datas1[$i + 1]);
                        } // if
                    } // for
                } // for

                foreach ($datas1 as $key => $value) {


                    if ($value->inventory現有庫存 > $value->安全庫存) {
                        unset($datas1[$key]);
                    }
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
                    })
                    ->join('inventory', function ($join) {
                        $join->on('inventory.客戶別', '=', '月請購_單耗.客戶別')
                            ->on('inventory.料號', '=', '月請購_單耗.料號');
                    })
                    ->select(
                        'inventory.客戶別',
                        'inventory.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.安全庫存',
                        '月請購_單耗.單耗',
                        'MPS.下月MPS',
                        'MPS.下月生產天數',
                        DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                    )->groupBy(
                        'inventory.客戶別',
                        'inventory.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.安全庫存',
                        '月請購_單耗.單耗',
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

                $sum = 0;
                foreach ($datas as $key => $value) {
                    if (isset($value->安全庫存)) {
                        $sum += $value->安全庫存;
                    }
                }
                foreach ($datas as $key => $value) {

                    $value->安全庫存 = round($sum);
                    if ($value->inventory現有庫存 > $sum) {
                        unset($datas[$key]);
                    }
                }
                $datas = $datas->unique('料號')->unique('客戶別');
                // dd($datas)->unique('料號');

                $datas1 = DB::table('月請購_站位')
                    ->join('MPS', function ($join) {
                        $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                            ->on('MPS.機種', '=', '月請購_站位.機種')
                            ->on('MPS.製程', '=', '月請購_站位.製程');
                    })
                    ->join('consumptive_material', function ($join) {
                        $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
                    })->where('consumptive_material.發料部門', $send)
                    ->join('inventory', function ($join) {
                        $join->on('inventory.客戶別', '=', '月請購_站位.客戶別')
                            ->on('inventory.料號', '=', '月請購_站位.料號');
                    })
                    ->select(
                        'inventory.客戶別',
                        'inventory.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.MPQ',
                        'consumptive_material.安全庫存',
                        '月請購_站位.下月站位人數',
                        '月請購_站位.下月開線數',
                        '月請購_站位.下月開班數',
                        '月請購_站位.下月每人每日需求量',
                        '月請購_站位.下月每日更換頻率',
                        DB::raw('sum(inventory.現有庫存) as inventory現有庫存')
                    )->groupBy(
                        'inventory.客戶別',
                        'inventory.料號',
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                        'consumptive_material.LT',
                        'consumptive_material.月請購',
                        'consumptive_material.MPQ',
                        'consumptive_material.安全庫存',
                        '月請購_站位.下月站位人數',
                        '月請購_站位.下月開線數',
                        '月請購_站位.下月開班數',
                        '月請購_站位.下月每人每日需求量',
                        '月請購_站位.下月每日更換頻率',
                    )
                    ->where('月請購_站位.狀態', '=', "已完成")
                    ->get();

                foreach ($datas1 as $data) {

                    if ($data->月請購 === '否') {
                        $safe = $data->安全庫存;
                    } else {
                        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
                    }

                    $data->安全庫存 = round($safe);
                }

                $sum = 0;
                foreach ($datas1 as $key => $value) {
                    if (isset($value->安全庫存)) {
                        $sum += $value->安全庫存;
                    }
                }
                foreach ($datas1 as $key => $value) {

                    $value->安全庫存 = round($sum);
                    if ($value->inventory現有庫存 > $sum) {
                        unset($datas1[$key]);
                    }
                }
                $datas1 = $datas1->unique('料號')->unique('客戶別');
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
                        DB::raw('sum(inventory.現有庫存) as inventory現有庫存'),
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                    )
                    ->groupBy('inventory.客戶別', 'inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格',)
                    ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')
                    ->havingRaw('sum(inventory.現有庫存) > ?', [0])
                    ->get();
            } else {
                $datas = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
                    ->select(
                        '客戶別',
                        'inventory.料號',
                        DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                        DB::raw('sum(inventory.現有庫存) as inventory現有庫存'),
                        'consumptive_material.品名',
                        'consumptive_material.規格',
                    )
                    ->groupBy('inventory.客戶別', 'inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格',)
                    ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')   // online setting
                    ->havingRaw('sum(inventory.現有庫存) > ?', [0])
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
