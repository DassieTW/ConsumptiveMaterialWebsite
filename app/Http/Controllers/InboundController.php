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
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
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

    public function index()
    {
        //
        if(Session::has('username'))
        {
            return view('inbound.index');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-查詢頁面
    public function search(Request $request)
    {
        if(Session::has('username'))
        {
            return view('inbound.search')->with(['client' => 客戶別::cursor()]);

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-庫存查詢頁面
    public function searchstock(Request $request)
    {
        if(Session::has('username'))
        {
            return view('inbound.searchstock')->with(['client' => 客戶別::cursor()])
            ->with(['position' => 儲位::cursor()]);

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-儲位調撥頁面
    public function positionchange(Request $request)
    {
        if(Session::has('username'))
        {
            return view('inbound.positionchange')->with(['client' => 客戶別::cursor()])
            ->with(['position' => 儲位::cursor()]);

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-庫存上傳頁面
    public function upload(Request $request)
    {
        if(Session::has('username'))
        {
            return view('inbound.upload');

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-儲位調撥
    public function change(Request $request)
    {
        if(Session::has('username'))
        {
            //all empty
            if($request->input('client') === null && $request->input('position') === null && $request->input('number') === null )
            {
                return view('inbound.change')->with(['data' => Inventory::cursor()->where('現有庫存' , '>' , 0)]);
            }
            //select client
            else if($request->input('client') !== null && $request->input('position') === null && $request->input('number') === null )
            {
                return view('inbound.change')->with(['data' => Inventory::cursor()->where('客戶別' , $request->input('client'))->where('現有庫存' , '>' , 0)]);
            }
            //select position
            else if($request->input('client') === null && $request->input('position') !== null && $request->input('number') === null )
            {
                return view('inbound.change')->with(['data' => Inventory::cursor()->where('儲位' , $request->input('position'))->where('現有庫存' , '>' , 0)]);
            }
            //input material number
            else if($request->input('client') === null && $request->input('position') === null && $request->input('number') !== null )
            {
                if(strlen($request->input('number')) !== 12)
                {
                    return back()->withErrors([
                        'number' => '料號長度不為12 , Please enter again',
                    ]);
                }
                else
                {
                    return view('inbound.change')->with(['data' => Inventory::cursor()->where('料號' , $request->input('number'))->where('現有庫存' , '>' , 0)]);
                }
            }
            //select client and position
            else if($request->input('client') !== null && $request->input('position') !== null && $request->input('number') === null)
            {
                return view('inbound.change')->with(['data' => Inventory::cursor()
                ->where('客戶別' , $request->input('client'))->where('儲位' , $request->input('position'))->where('現有庫存' , '>' , 0)]);

            }
            //select client and number
            else if($request->input('client') !== null && $request->input('position') === null && $request->input('number') !== null )
            {
                if(strlen($request->input('number')) !== 12)
                {
                    return back()->withErrors([
                        'number' => '料號長度不為12 , Please enter again',
                    ]);
                }
                else
                {
                    return view('inbound.change')->with(['data' => Inventory::cursor()
                    ->where('料號' , $request->input('number'))->where('客戶別' , $request->input('client'))->where('現有庫存' , '>' , 0)]);
                }
            }
            //select position and number
            else if($request->input('client') === null && $request->input('position') !== null && $request->input('number') !== null )
            {
                if(strlen($request->input('number')) !== 12)
                {
                    return back()->withErrors([
                        'number' => '料號長度不為12 , Please enter again',
                    ]);
                }
                else
                {
                    return view('inbound.change')->with(['data' => Inventory::cursor()
                    ->where('料號' , $request->input('number'))->where('儲位' , $request->input('position'))->where('現有庫存' , '>' , 0)]);
                }
            }
            //select all
            else if($request->input('client') !== null && $request->input('position') !== null && $request->input('number') !== null)
            {
                if(strlen($request->input('number')) !== 12)
                {
                    return back()->withErrors([
                        'number' => '料號長度不為12 , Please enter again',
                    ]);
                }
                else
                {
                    return view('inbound.change')->with(['data' => Inventory::cursor()
                    ->where('客戶別' , $request->input('client'))->where('儲位' , $request->input('position'))
                    ->where('料號' , $request->input('number'))->where('現有庫存' , '>' , 0)]);
                }
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-儲位調撥提交
    public function changesubmit(Request $request)
    {
        if(Session::has('username'))
        {
            $count = $request->input('time');
            for($i = 0 ; $i < $count ; $i++)
            {
                if($request->has('submit' . $i))
                {
                    $number = $request->input('number' . $i);
                    $stock = $request->input('stock' . $i);
                    $oldposition = $request->input('oldposition' . $i);
                    $client = $request->input('client' . $i);
                    $amount = $request->input('amount' . $i);
                    $newposition = $request->input('newposition' . $i);
                    $now = Carbon::now();
                    $test = DB::table('inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('儲位', $newposition)
                            ->value('現有庫存');

                    if($amount > $stock)
                    {
                        //echo "<script>alert('調撥數量不可大於現有庫存，無法提交此筆儲位調撥');</script>";
                        /*return back()->withErrors([
                            'stock' => '調撥數量不可大於現有庫存，無法提交此筆儲位調撥',
                        ]);*/
                        echo ("<script LANGUAGE='JavaScript'>
                                window.alert('調撥數量不可大於現有庫存，無法提交此筆儲位調撥');
                                window.location.href='positionchange';
                                </script>");
                        //return redirect()->action('InboundController@positionchange', ['stock' => '調撥數量不可大於現有庫存，無法提交此筆儲位調撥']);
                        //return route('inbound.positionchange');
                    }

                    else
                    {
                        $sure = ("<script LANGUAGE='JavaScript'>
                                var c = window.confirm('即將從 : ' + '$oldposition' + ' 調撥 :' + ' $number : ' + '$amount' + ' 到 ' + '$newposition' + ' \\n客戶別 : ' + '$client');
                                if(!c){window.history.go(-1);}

                                </script>");
                        echo $sure;
                        DB::beginTransaction();
                        try{

                        if($test === null)
                        {
                            DB::table('inventory')
                            ->insert(['料號' => $number , '現有庫存' => $amount , '儲位' => $newposition ,'客戶別' => $client , '最後更新時間' => $now]);

                        }
                        else
                        {
                            DB::table('inventory')
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('儲位', $newposition)
                                ->update(['現有庫存' => $test + $amount , '最後更新時間' => $now]);
                        }

                        DB::table('inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('儲位', $oldposition)
                            ->update(['現有庫存' => $stock - $amount , '最後更新時間' => $now]);

                            DB::commit();
                        }catch(\Exception $e){
                            DB::rollback();
                            $mess = $e->getMessage();
                                echo ("<script LANGUAGE='JavaScript'>
                                window.alert('$mess');
                                window.location.href='/inbound';
                                </script>");
                        }

                    }

                }

                else
                {
                    continue;
                }
            }

            echo ("<script LANGUAGE='JavaScript'>
            window.alert('調撥成功');
            window.location.href='/inbound';
            </script>");
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-查詢
    public function inquire(Request $request)
    {
        if(Session::has('username'))
        {
            if($request->input('number') !== null)
            {
                $datas = DB::table('inbound')
                ->where('料號', 'like', $request->input('number').'%')
                ->get();
            }
            //all empty
            if($request->input('client') === null && $request->input('innumber') === null && $request->input('number') === null && !($request->has('date')))
            {
                return view('inbound.searchok')->with(['data' => Inbound::cursor()]);
            }
            //select client
            else if($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') === null && !($request->has('date')))
            {
                return view('inbound.searchok')->with(['data' => Inbound::cursor()->where('客戶別' , $request->input('client'))]);
            }
            //input innumber
            else if($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') === null && !($request->has('date')))
            {
                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                return view('inbound.searchok')->with(['data' => Inbound::cursor()->where('入庫單號' , $request->input('innumber'))]);

                }
            }
            //input material number
            else if($request->input('client') === null && $request->input('innumber') === null && $request->input('number') !== null && !($request->has('date')))
            {
                return view('inbound.searchok')->with(['data' => $datas]);
            }
            //select date
            else if($request->input('client') === null && $request->input('innumber') === null && $request->input('number') === null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $end = date($request->input('end'));
                return view('inbound.searchok')->with(['data' => Inbound::cursor()->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select client and innumber
            else if($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') === null && !($request->has('date')))
            {
                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                    return view('inbound.searchok')->with(['data' => Inbound::cursor()
                    ->where('客戶別' , $request->input('client'))->where('入庫單號' , $request->input('innumber'))]);
                }
            }
            //select client and number
            else if($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') !== null && !($request->has('date')))
            {

                return view('inbound.searchok')->with(['data' => $datas->where('客戶別' , $request->input('client'))]);
            }
            //select client and time
            else if($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') === null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $end = date($request->input('end'));
                return view('inbound.searchok')->with(['data' => Inbound::cursor()
                ->whereBetween('入庫時間' , [$begin, $end])->where('客戶別' , $request->input('client'))]);
            }
            //select innumber and number
            else if($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') !== null && !($request->has('date')))
            {

                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                    return view('inbound.searchok')->with(['data' => $datas->where('入庫單號' , $request->input('innumber'))]);
                }

            }
            //select innumber and time
            else if($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') === null && ($request->has('date')))
            {
                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                    $begin = date($request->input('begin'));
                    $end = date($request->input('end'));
                    return view('inbound.searchok')->with(['data' => Inbound::cursor()
                    ->whereBetween('入庫時間' , [$begin, $end])->where('入庫單號' , $request->input('innumber'))]);
                }
            }
            //select number and time
            else if($request->input('client') === null && $request->input('innumber') === null && $request->input('number') !== null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $end = date($request->input('end'));
                return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select client and innumber and number
            else if($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') !== null && !($request->has('date')))
            {

                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                    return view('inbound.searchok')->with(['data' => $datas->where('入庫單號' , $request->input('innumber'))
                    ->where('客戶別' , $request->input('client'))]);
                }

            }
            //select client and innumber and time
            else if($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') === null && ($request->has('date')))
            {
                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                    $begin = date($request->input('begin'));
                    $end = date($request->input('end'));
                    return view('inbound.searchok')->with(['data' => Inbound::cursor()
                    ->whereBetween('入庫時間' , [$begin, $end])->where('' , $request->input('innumber'))
                    ->where('客戶別' , $request->input('client'))]);
                }
            }
            //select client and number and time
            else if($request->input('client') !== null && $request->input('innumber') === null && $request->input('number') !== null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $end = date($request->input('end'));
                return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])
                ->where('客戶別' , $request->input('client'))]);
            }
            //select innumber and number and time
            else if($request->input('client') === null && $request->input('innumber') !== null && $request->input('number') !== null && ($request->has('date')))
            {
                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                    $begin = date($request->input('begin'));
                    $end = date($request->input('end'));
                    return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])
                    ->where('入庫單號' , $request->input('innumber'))]);
                }
            }
            //select all
            else if($request->input('client') !== null && $request->input('innumber') !== null && $request->input('number') !== null && ($request->has('date')))
            {
                if(strlen($request->input('innumber')) !== 12)
                {
                    return back()->withErrors([
                        'innumber' => '入庫單號格式錯誤，長度須為12 , Please enter again',
                    ]);
                }
                else
                {
                    $begin = date($request->input('begin'));
                    $end = date($request->input('end'));
                    return view('inbound.searchok')->with(['data' => $datas->whereBetween('入庫時間' , [$begin, $end])
                    ->where('入庫單號' , $request->input('innumber'))->where('客戶別' , $request->input('client'))]);
                }
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-庫存查詢
    public function searchstocksubmit(Request $request)
    {
        if(Session::has('username'))
        {
            $send = $request->input('send');
            $client = $request->input('client');
            $position = $request->input('position');
            $number = $request->input('number');
            //不良品inventory
            if($request->has('nogood'))
            {
                if($number !== null)
                {
                    $datas = DB::table('consumptive_material')
                    ->join('不良品inventory', function($join)
                    {
                        $join->on('不良品inventory.料號', '=', 'consumptive_material.料號');

                    })->where('consumptive_material.料號','like', $number.'%')->get();
                }
                else
                {
                    $datas = DB::table('consumptive_material')
                    ->join('不良品inventory', function($join)
                    {
                        $join->on('不良品inventory.料號', '=', 'consumptive_material.料號');

                    })->get();
                }
            }
            //inventory
            else
            {
                if($number !== null)
                {
                    $datas = DB::table('consumptive_material')
                    ->join('inventory', function($join)
                    {
                        $join->on('inventory.料號', '=', 'consumptive_material.料號');

                    })->where('consumptive_material.料號','like', $number.'%')->get();
                }
                else
                {
                    $datas = DB::table('consumptive_material')
                    ->join('inventory', function($join)
                    {
                        $join->on('inventory.料號', '=', 'consumptive_material.料號');

                    })->get();
                }
            }
            //庫存使用月數
            if($request->has('month'))
            {
                $test = DB::table('inventory')
                ->select('料號', '客戶別' , DB::raw('SUM(現有庫存) as 現有庫存'))
                ->groupBy('料號' , '客戶別');

                if($number !== null)
                {
                    $datas = DB::table('consumptive_material')
                    ->joinSub($test, 'inventory', function ($join) {
                        $join->on('inventory.料號', '=', 'consumptive_material.料號');
                    })->where('consumptive_material.料號','like', $number.'%')->get();
                }
                else
                {
                    $datas = DB::table('consumptive_material')
                    ->joinSub($test, 'inventory', function ($join) {
                        $join->on('inventory.料號', '=', 'consumptive_material.料號');
                    })->get();
                }

                //all empty
                if($request->input('client') === null && $request->input('number') === null && $request->input('send') === null )
                {
                    return view('inbound.searchstockok1')->with(['data' => $datas]);
                }
                //select client
                else if($request->input('client') !== null && $request->input('number') === null && $request->input('send') === null)
                {
                    return view('inbound.searchstockok1')->with(['data' => $datas->where('客戶別' , $client)]);
                }
                //input material number
                else if($request->input('client') === null && $request->input('number') !== null && $request->input('send') === null)
                {

                    return view('inbound.searchstockok1')->with(['data' => $datas]);

                }
                //select send
                else if($request->input('client') === null && $request->input('number') === null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok1')->with(['data' => $datas->where('發料部門' , $send)]);
                }
                //select client and number
                else if($request->input('client') !== null && $request->input('number') !== null && $request->input('send') === null)
                {

                    return view('inbound.searchstockok1')->with(['data' => $datas->where('客戶別' , $client)]);

                }
                //select client and send
                else if($request->input('client') !== null && $request->input('number') === null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok1')->with(['data' => $datas
                    ->where('發料部門' , $send)->where('客戶別' , $client)]);
                }
                //select send and number
                else if($request->input('client') === null && $request->input('number') !== null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok1')->with(['data' => $datas->where('發料部門' , $send)]);
                }
                //select all
                else if($request->input('client') !== null && $request->input('number') !== null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok1')->with(['data' => $datas->where('發料部門' , $send)->where('客戶別' , $client)]);
                }
            }
            else
            {

                if($request->input('client') === null && $request->input('position') === null && $request->input('number') === null && $request->input('send') === null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas]);
                }
                //select client
                else if($request->input('client') !== null && $request->input('position') === null && $request->input('number') === null && $request->input('send') === null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)]);
                }
                //select position
                else if($request->input('client') === null && $request->input('position') !== null && $request->input('number') === null && $request->input('send') === null )
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('儲位' , $position)]);
                }

                //input material number
                else if($request->input('client') === null && $request->input('position') === null && $request->input('number') !== null && $request->input('send') === null)
                {

                    return view('inbound.searchstockok')->with(['data' => $datas]);

                }
                //select send
                else if($request->input('client') === null && $request->input('position') === null && $request->input('number') === null && $request->input('send') !== null)
                {

                    return view('inbound.searchstockok')->with(['data' => $datas->where('發料部門' , $send)]);

                }
                //select client and position
                else if($request->input('client') !== null && $request->input('position') !== null && $request->input('number') === null && $request->input('send') === null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('儲位' , $position)]);

                }
                //select client and number
                else if($request->input('client') !== null && $request->input('position') === null && $request->input('number') !== null  && $request->input('send') === null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)]);
                }
                //select position and number
                else if($request->input('client') === null && $request->input('position') !== null && $request->input('number') !== null && $request->input('send') === null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('儲位' , $position)]);
                }
                //select client and send
                else if($request->input('client') !== null && $request->input('position') === null && $request->input('number') === null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('發料部門' , $send)]);
                }
                //select position and send
                else if($request->input('client') === null && $request->input('position') !== null && $request->input('number') === null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('發料部門' , $send)->where('儲位' , $position)]);
                }
                //select number and send
                else if($request->input('client') === null && $request->input('position') === null && $request->input('number') !== null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('發料部門' , $send)]);
                }
                //select client and position and send
                else if($request->input('client') !== null && $request->input('position') !== null && $request->input('number') === null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('儲位' , $position)->where('發料部門' , $send)]);
                }
                //select number and position and send
                else if($request->input('client') === null && $request->input('position') !== null && $request->input('number') !== null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('儲位' , $position)->where('發料部門' , $send)]);
                }
                //select client and position and number
                else if($request->input('client') !== null && $request->input('position') !== null && $request->input('number') !== null && $request->input('send') === null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('儲位' , $position)]);
                }
                //select client and number and send
                else if($request->input('client') !== null && $request->input('position') === null && $request->input('number') !== null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('發料部門' , $send)]);
                }
                //select all
                else if($request->input('client') !== null && $request->input('position') !== null && $request->input('number') !== null && $request->input('send') !== null)
                {
                    return view('inbound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('儲位' , $position)->where('發料部門' , $send)]);
                }
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }


    //入庫-新增
    public function add(Request $request)
    {
        if(Session::has('username'))
        {

            return view('inbound.add')->with(['client' => 客戶別::cursor()])
            ->with(['inreason' => 入庫原因::cursor()]);

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-新增
    public function addnew(Request $request)
    {
        Session::forget('number');
        Session::forget('client');
        Session::forget('name');
        Session::forget('format');
        Session::forget('unit');
        Session::forget('amount');
        Session::forget('stock');
        Session::forget('safe');
        Session::forget('inreason');
        Session::forget('positions');
        Session::forget('inboundadd');
        $reDive = new responseObj();
        if(Session::has('username'))
        {
            if($request->input('submit') === '0')
            {
                if($request->input('client') !== null && $request->input('inreason') !== null)
                {
                    $client = $request->input('client');
                    $inreason = $request->input('inreason');
                    $number = $request->input('number');

                    if($number !== 'zero' && strlen($number) !== 12)
                    {
                        $reDive->boolean = true;
                        $reDive->passbool = false;
                        $reDive->passstock = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                    else
                    {
                        $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
                        $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
                        $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
                        $amount = DB::table('在途量')->where('料號', $number)->where('客戶', $client)->sum('請購數量');
                        $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->sum('現有庫存');
                        $positions = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('現有庫存','>',0)->pluck('儲位');
                        $belong = DB::table('consumptive_material')->where('料號', $number)->value('耗材歸屬');
                        $lt = DB::table('consumptive_material')->where('料號', $number)->value('LT');
                        //dd($name);
                        //return;
                        if($name === null || $format === null)
                        {
                            $reDive->boolean = true;
                            $reDive->passbool = true;
                            $reDive->passstock = false;
                            $myJSON = json_encode($reDive);
                            echo $myJSON;
                        }
                        else
                        {
                            if($amount === 0 && $inreason !== '調撥' &&  $inreason !== '退庫')
                            {
                                $reDive->boolean = true;
                                $reDive->passbool = false;
                                $reDive->passstock = true;
                                $myJSON = json_encode($reDive);
                                echo $myJSON;
                            }
                            else
                            {
                                $reDive->boolean = true;
                                $reDive->passbool = true;
                                $reDive->passstock = true;
                                $myJSON = json_encode($reDive);
                                echo $myJSON;
                            }
                            if($belong === '單耗')
                            {
                                $machine = DB::table('月請購_單耗')->where('料號',$number)->where('客戶別', $client)->value('機種');
                                $production = DB::table('月請購_單耗')->where('料號',$number)->where('客戶別', $client)->value('製程');
                                $consume = DB::table('月請購_單耗')->where('料號',$number)->where('客戶別', $client)->value('單耗');
                                $nextmps = DB::table('MPS')->where('機種',$machine)->where('客戶別', $client)->where('製程',$production)->value('下月MPS');
                                $nextday = DB::table('MPS')->where('機種',$machine)->where('客戶別', $client)->where('製程',$production)->value('下月生產天數');
                                if($nextday == 0)
                                {
                                    $safe = 0;
                                }
                                else
                                {
                                    $safe = $lt * $consume * $nextmps / $nextday;
                                }
                            }
                            else
                            {
                                $machine = DB::table('月請購_站位')->where('料號',$number)->where('客戶別', $client)->value('機種');
                                $production = DB::table('月請購_站位')->where('料號',$number)->where('客戶別', $client)->value('製程');
                                $nextstand = DB::table('月請購_站位')->where('料號',$number)->where('客戶別', $client)->value('下月站位人數');
                                $nextline = DB::table('月請購_站位')->where('料號',$number)->where('客戶別', $client)->value('下月開線數');
                                $nextclass = DB::table('月請購_站位')->where('料號',$number)->where('客戶別', $client)->value('下月開班數');
                                $nextuse = DB::table('月請購_站位')->where('料號',$number)->where('客戶別', $client)->value('下月每人每日需求量');
                                $nextchange = DB::table('月請購_站位')->where('料號',$number)->where('客戶別', $client)->value('下月每日更換頻率');
                                $mpq = DB::table('consumptive_material')->where('料號',$number)->value('MPQ');
                                if($mpq == 0)
                                {
                                    $safe = 0;
                                }
                                else
                                {
                                    $safe = $lt * $nextstand * $nextline * $nextclass * $nextuse * $nextchange / $mpq;
                                }
                            }
                            Session::put('client' , $client);
                            Session::put('inreason' , $inreason);
                            Session::put('number' , $number);
                            Session::put('name' , $name);
                            Session::put('format' , $format);
                            Session::put('unit' , $unit);
                            Session::put('amount' , $amount);
                            Session::put('stock' , $stock);
                            Session::put('safe' , $safe);
                            Session::put('positions' , $positions);
                            Session::put('inboundadd' , $number);
                        }
                    }
                }
                else
                {
                    return view('inbound.add');
                }

            }
            else
            {
                Session::put('addclient' , $request->input('client'));
                Session::put('client' , $request->input('client'));
                Session::put('inreason' , $request->input('inreason'));
                $reDive->boolean = false;
                $reDive->passbool = true;
                $reDive->passstock = false;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }


    //入庫-新增頁面
    public function addnewok(Request $request)
    {
        if (Session::has('username'))
        {
            if(Session::has('inboundadd'))
            {
                Session::forget('inboundadd');
                return view("inbound.addnew");
            }
            else
            {
                return redirect(route('inbound.add'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-新增提交
    public function addnewsubmit(Request $request)
    {
        $reDive = new  responseObj();
        if (Session::has('username'))
        {
            if($request->input('inamount') !== null)
            {
                $number = $request->input('number');
                $name = $request->input('name');
                $format = $request->input('format');
                $unit = $request->input('unit');
                $client = $request->input('client');
                $amount = $request->input('amount');
                $inamount = $request->input('inamount');
                $inreason = $request->input('inreason');
                $oldposition = $request->input('oldposition');
                $newposition = $request->input('newposition');
                $inpeople = $request->input('inpeople');
                $time = Carbon::now();
                $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $newposition)->value('現有庫存');
                $buy = DB::table('在途量')->where('客戶', $client)->where('料號', $number)->value('請購數量');
                //入庫>在途量
                if($inamount > $amount && $inreason !== '調撥' && $inreason !== '退庫')
                {
                    $reDive->boolean = true;
                    $reDive->passbool = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
                else
                {
                    if($stock !== null)
                    {
                        $i = '0001';
                        $max = DB::table('inbound')->max('入庫時間');
                        $maxtime = date_create(date('Y-m-d',strtotime($max)));
                        $nowtime = date_create(date('Y-m-d',strtotime(Carbon::now())));
                        $interval = date_diff($maxtime ,$nowtime);
                        $interval = $interval->format('%R%a');
                        $interval = (int)($interval);
                        if($interval > 0)
                        {
                            $opentime = Carbon::now()->format('Ymd').$i;
                        }
                        else
                        {
                            $num = DB::table('inbound')->max('入庫單號');
                            $num = intval($num);
                            $num ++;
                            $num = strval($num);
                            $opentime = $num;
                        }
                        DB::beginTransaction();
                        try {

                            DB::table('inbound')
                            ->insert(['入庫單號' => $opentime , '料號' => $number , '入庫數量' => $inamount ,'儲位' => $newposition ,
                            '入庫人員' => $inpeople ,'客戶別' => $client , '入庫原因' => $inreason , '入庫時間' => Carbon::now()]);

                            DB::table('inventory')
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('儲位', $newposition)
                                ->update(['現有庫存' => $stock + $inamount , '最後更新時間' => $time]);
                            if($inreason !== '調撥' && $inreason !== '退庫')
                            {
                                DB::table('在途量')
                                ->where('客戶', $client)
                                ->where('料號', $number)
                                ->update(['請購數量' => $buy - $inamount]);
                            }
                            DB::commit();
                        }catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                        }
                        Session::put('addnewsubmitok' , $number);
                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $reDive->message = $opentime;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                    else
                    {
                        $i = '0001';
                        $max = DB::table('inbound')->max('入庫時間');
                        $maxtime = date_create(date('Y-m-d',strtotime($max)));
                        $nowtime = date_create(date('Y-m-d',strtotime(Carbon::now())));
                        $interval = date_diff($maxtime ,$nowtime);
                        $interval = $interval->format('%R%a');
                        $interval = (int)($interval);
                        if($interval > 0)
                        {
                            $opentime = Carbon::now()->format('Ymd').$i;
                        }
                        else
                        {
                            $num = DB::table('inbound')->max('入庫單號');
                            $num = intval($num);
                            $num ++;
                            $num = strval($num);
                            $opentime = $num;
                        }
                        DB::beginTransaction();
                        try {
                            DB::table('inbound')
                            ->insert(['入庫單號' => $opentime , '料號' => $number , '入庫數量' => $inamount ,'儲位' => $newposition ,
                            '入庫人員' => $inpeople ,'客戶別' => $client , '入庫原因' => $inreason , '入庫時間' => Carbon::now()]);

                            DB::table('inventory')
                            ->insert(['料號' => $number , '現有庫存' => $inamount , '儲位' => $newposition ,'客戶別' => $client , '最後更新時間' => Carbon::now()]);
                            if($inreason !== '調撥' && $inreason !== '退庫')
                            {
                                DB::table('在途量')
                                ->where('客戶', $client)
                                ->where('料號', $number)
                                ->update(['請購數量' => $buy - $inamount]);
                            }
                            DB::commit();
                        }catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                        }
                        Session::put('addnewsubmitok' , $number);
                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $reDive->message = $opentime;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                }
            }
            else
            {
                return redirect(route('inbound.add'));
            }
        }

        else
        {
            return redirect(route('member.login'));
        }

    }


    //入庫-新增提交成功
    public function addnewsubmitok()
    {
        if (Session::has('username'))
        {
            if(Session::has('addnewsubmitok'))
            {
                Session::forget('addnewsubmitok');
                return view("inbound.addnewsubmitok");

            }
            else
            {
                return redirect(route('inbound.add'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-新增(By客戶別)頁面
    public function addclient(Request $request)
    {
        if (Session::has('username'))
        {
            if(Session::has('addclient'))
            {
                Session::forget('addclient');
                $client = Session::get('client');
                $number = DB::table('在途量')->where('客戶', Session::get('client'))->where('請購數量', '>', 0)->value('料號');
                return view("inbound.addclient")->with(['data' => 在途量::cursor()->where('請購數量', '>', 0)->where('客戶' , $client)])
                ->with(['inreason' => Session::get('inreason')])
                ->with(['positions' => 儲位::cursor()])
                ->with(['peopleinf' => 人員信息::cursor()]);
            }
            else
            {
                return redirect(route('inbound.add'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //入庫-新增(By客戶別)提交
    public function addclientsubmit(Request $request)
    {
        if (Session::has('username'))
        {
            $count = $request->input('count');
            $record = 0;
            //入庫單號
            $i = '0001';
            $max = DB::table('inbound')->max('入庫時間');
            $maxtime = date_create(date('Y-m-d',strtotime($max)));
            $nowtime = date_create(date('Y-m-d',strtotime(Carbon::now())));
            $interval = date_diff($maxtime ,$nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int)($interval);
            if($interval > 0)
            {
                $opentime = Carbon::now()->format('Ymd').$i;
            }
            else
            {
                $num = DB::table('inbound')->max('入庫單號');
                $num = intval($num);
                $num ++;
                $num = strval($num);
                $opentime = $num;
            }
            for($i = 0 ; $i < $count ; $i++)
            {
                $number = $request->input('number' . $i);
                if($number !== null)
                {
                    $name = $request->input('name'. $i);
                    $format = $request->input('format'. $i);
                    $unit = $request->input('unit'. $i);
                    $client = $request->input('client'. $i);
                    $amount = $request->input('amount'. $i);
                    $inreason = Session::get('inreason');
                    //$oldposition = $request->input('oldposition'. $i);
                    $newposition = $request->input('newposition'. $i);
                    $inpeo = $request->input('inpeople');
                    $inpeoples = explode(" ", $inpeo);
                    $inpeople = $inpeoples[0];

                    $time = Carbon::now();
                    $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $newposition)->value('現有庫存');
                    $buy = DB::table('在途量')->where('客戶', $client)->where('料號', $number)->value('請購數量');
                    //入庫>在途量
                    if($amount > $buy && $inreason !== '調撥' && $inreason !== '退庫')
                    {
                        $i++;
                        echo ("<script LANGUAGE='JavaScript'>
                                window.alert('入庫數量不可大於在途量，In Row :' + '$i' + ' 客戶別: ' + '$client' + ' 料號: ' + '$number');
                                window.location.href='add';
                                </script>");
                    }
                    else
                    {
                        if($stock !== null)
                        {

                            DB::beginTransaction();
                            try {

                                DB::table('inbound')
                                ->insert(['入庫單號' => $opentime , '料號' => $number , '入庫數量' => $amount ,'儲位' => $newposition ,
                                '入庫人員' => $inpeople ,'客戶別' => $client , '入庫原因' => $inreason , '入庫時間' => Carbon::now()]);

                                DB::table('inventory')
                                    ->where('客戶別', $client)
                                    ->where('料號', $number)
                                    ->where('儲位', $newposition)
                                    ->update(['現有庫存' => $stock + $amount , '最後更新時間' => $time]);
                                if($inreason !== '調撥' && $inreason !== '退庫')
                                {
                                    DB::table('在途量')
                                    ->where('客戶', $client)
                                    ->where('料號', $number)
                                    ->update(['請購數量' => $buy - $amount]);
                                }

                                DB::commit();
                                $record ++;
                            }catch (\Exception $e) {
                                DB::rollback();
                                $mess = $e->getMessage();
                                echo ("<script LANGUAGE='JavaScript'>
                                window.alert('$mess');
                                window.location.href='/inbound';
                                </script>");

                            }
                        }
                        else
                        {

                            DB::beginTransaction();
                            try {
                                DB::table('inbound')
                                ->insert(['入庫單號' => $opentime , '料號' => $number , '入庫數量' => $amount ,'儲位' => $newposition ,
                                '入庫人員' => $inpeople ,'客戶別' => $client , '入庫原因' => $inreason , '入庫時間' => Carbon::now()]);

                                DB::table('inventory')
                                ->insert(['料號' => $number , '現有庫存' => $amount , '儲位' => $newposition ,'客戶別' => $client , '最後更新時間' => Carbon::now()]);

                                if($inreason !== '調撥' && $inreason !== '退庫')
                                {
                                    DB::table('在途量')
                                    ->where('客戶', $client)
                                    ->where('料號', $number)
                                    ->update(['請購數量' => $buy - $amount]);
                                }
                                DB::commit();
                                $record ++;
                            }catch (\Exception $e) {
                                DB::rollback();
                                $mess = $e->getMessage();
                                echo ("<script LANGUAGE='JavaScript'>
                                window.alert('$mess');
                                window.location.href='/inbound';
                                </script>");

                            }
                        }
                    }
                }
                else
                {
                    continue;
                }
            }
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('共 ' + '$record' + ' 筆更新完成，入庫單號: ' + '$opentime');
            window.location.href='/inbound';
            </script>");
        }

        else
        {
            return redirect(route('member.login'));
        }

    }

    //刪除
    public function delete(Request $request)
    {
        if (Session::has('username'))
        {
            if($request->has('delete'))
            {
                $count = $request->input('count');
                for($i = 0 ; $i < $count ; $i++)
                {
                    if($request->has('innumber' . $i))
                    {
                        $time = Carbon::now();
                        $list = $request->input('data0' . $i);
                        $number = $request->input('data1' . $i);
                        $amount = $request->input('data2' . $i);
                        $position = $request->input('data3' . $i);
                        $inpeople = $request->input('data4' . $i);
                        $client = $request->input('data5' . $i);
                        $inreason = $request->input('data6' . $i);
                        $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $position)->value('現有庫存');
                        $buy = DB::table('在途量')->where('客戶', $client)->where('料號', $number)->value('請購數量');
                        DB::beginTransaction();
                        try {
                            if($stock < $amount)
                            {
                                if($stock === null) $stock = 0;
                                echo ("<script LANGUAGE='JavaScript'>
                                window.alert('目前庫存小於入庫數量，無法刪除此筆入庫。目前庫存: ' + '$stock' + '入庫數量: ' + '$amount');
                                window.location.href='search';
                                </script>");
                                return;
                            }
                            if($inreason !== '調撥' && $inreason !=='退庫')
                            {
                                DB::table('在途量')
                                ->where('客戶', $client)
                                ->where('料號', $number)
                                ->update(['請購數量' => $buy + $amount]);
                            }
                            DB::table('inventory')
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('儲位', $position)
                                ->update(['現有庫存' => $stock - $amount , '最後更新時間' => $time]);

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
                        }catch (\Exception $e) {
                            DB::rollback();
                            $mess = $e->getMessage();
                                echo ("<script LANGUAGE='JavaScript'>
                                window.alert('$mess');
                                window.location.href='/inbound';
                                </script>");
                        }
                    }
                    else
                    {
                        continue;
                    }
                }
                echo("<script LANGUAGE='JavaScript'>
                window.alert('刪除成功: 入庫單號 : ' + '$list' + ' 客戶別: ' +  '$client' + ' 料號: ' + '$number');
                window.location.href='/inbound';
                </script>");
            }
            else if($request->has('download'))
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
                $filename = '入庫查詢'. $now . '.xlsx';
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');

            }

            else
            {
                return redirect(route('inbound.search'));
            }
        }
        else
        {
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



    //庫存上傳
    public function uploadinventory(Request $request)
    {
        if (Session::has('username'))
        {
            $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('inbound.uploadinventory')->with(['data' => $sheetData]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //庫存上傳頁面
    function uploadinventorypage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('inbound.uploadinventory1');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //上傳資料新增至資料庫
    public function insertuploadinventory(Request $request)
    {
        if (Session::has('username'))
        {
            $count = $request->input('count');
            $time = 0;
            $now = Carbon::now();
            $in = false;
            for($i = 0 ; $i < $count ; $i ++)
            {
                $client =  $request->input('data0'. $i);
                $number =  $request->input('data1'. $i);
                $amount =  $request->input('data2'. $i);
                $position =  $request->input('data3'. $i);
                $stock = DB::table('inventory')->where('客戶別', $client)->where('料號', $number)->where('儲位', $position)->value('現有庫存');

                if($stock === null)
                {
                    DB::beginTransaction();
                    try {
                        DB::table('inventory')
                            ->insert(['料號' => $number , '現有庫存' => $amount , '儲位' => $position ,'客戶別' => $client , '最後更新時間' => $now]);
                        DB::commit();
                        $time++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $mess = $e->getmessage();
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('inbound.uploadinventory1');
                    }
                }
                else
                {
                    DB::beginTransaction();
                    try {
                        DB::table('inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('儲位', $position)
                            ->update(['現有庫存' => $stock + $amount , '最後更新時間' => $now]);
                        DB::commit();
                        $time++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $mess = $e->getmessage();
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('inbound.uploadinventory1');
                    }
                }
            }
            echo("<script LANGUAGE='JavaScript'>
                window.alert('共 : ' + '$time' + ' 筆庫存上傳成功');
                window.location.href='/inbound';
                </script>");

        }

        else
        {
            return redirect(route('member.login'));
        }

    }
}
