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

    public function index()
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
    }

    //安全庫存警報頁面
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
    }

    //安全庫存警報
    public function safesubmit(Request $request)
    {
        //
        if(Session::has('username'))
        {
            $send = $request->input('send');
            if($send === null)
            {
                $datas = DB::table('月請購_單耗')
                ->join('MPS', function($join)
                {
                    $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                    ->on('MPS.機種', '=', '月請購_單耗.機種')
                    ->on('MPS.製程', '=', '月請購_單耗.製程');
                })
                ->join('consumptive_material', function($join)
                {
                    $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');

                })
                ->get();
                foreach($datas as $data)
                {
                    $client = $data->客戶別;
                    $number = $data->料號;
                    $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->sum('現有庫存');

                    if($data->月請購 === '否')
                    {
                        $safe = $data->安全庫存;
                    }
                    else
                    {
                        $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
                    }

                    $data->安全庫存 = $safe;
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

                })->get();

                foreach($datas1 as $data)
                {
                    $client = $data->客戶別;
                    $number = $data->料號;
                    $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->sum('現有庫存');

                    if($data->月請購 === '否')
                    {
                        $safe = $data->安全庫存;
                    }
                    else
                    {
                        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
                    }

                    $data->安全庫存 = $safe;
                }

            }
            else
            {
                $datas = DB::table('月請購_單耗')
                ->join('MPS', function($join)
                {
                    $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                    ->on('MPS.機種', '=', '月請購_單耗.機種')
                    ->on('MPS.製程', '=', '月請購_單耗.製程');
                })
                ->join('consumptive_material', function($join)
                {
                    $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');

                })->where('consumptive_material.發料部門',$send)
                ->get();

                foreach($datas as $data)
                {
                    $client = $data->客戶別;
                    $number = $data->料號;
                    $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->sum('現有庫存');

                    if($data->月請購 === '否')
                    {
                        $safe = $data->安全庫存;
                    }
                    else
                    {
                        $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
                    }

                    $data->安全庫存 = $safe;
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

                })->where('consumptive_material.發料部門',$send)
                ->get();

                foreach($datas1 as $data)
                {
                    $client = $data->客戶別;
                    $number = $data->料號;
                    $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->sum('現有庫存');

                    if($data->月請購 === '否')
                    {
                        $safe = $data->安全庫存;
                    }
                    else
                    {
                        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
                    }

                    $data->安全庫存 = $safe;
                }
            }
            return view('call.safe')->with(['data' => $datas])->with(['data1' => $datas1]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //呆滯天數警報頁面
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
    }

    //呆滯天數警報
    public function daysubmit(Request $request)
    {
        //
        if(Session::has('username'))
        {
            $send = $request->input('send');
            if($send === null)
            {
                $datas = Inventory::join('consumptive_material', 'consumptive_material.料號',"=", 'inventory.料號')
                    ->select('客戶別','inventory.料號','品名','規格',DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'))
                    ->groupBy('inventory.客戶別','inventory.料號')
                    ->get();
                /*$datas = DB::table('inventory')
                ->join('consumptive_material', function($join)
                {
                    $join->on('consumptive_material.料號', '=', 'inventory.料號');

                })->where()
                ->get();*/
                //dd($datas);
            }
            else
            {
                $datas = Inventory::join('consumptive_material', 'consumptive_material.料號',"=", 'inventory.料號')
                    ->select('客戶別','inventory.料號','發料部門','品名','規格',DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'))
                    ->groupBy('inventory.客戶別','inventory.料號')
                    ->where('consumptive_material.發料部門',$send)
                    ->get();


            }
            //dd($datas);
            return view('call.day')->with(['data' => $datas]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }


}
