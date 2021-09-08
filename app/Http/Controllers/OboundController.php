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
use App\Models\O庫;
use App\Models\O庫Material;
use App\Models\O庫Inbound;
use App\Models\O庫Inventory;
use App\Models\O庫不良品Inventory;
use App\Models\O庫Outbound;
use App\Models\O庫出庫退料;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OboundController extends Controller
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
            return view('obound.index');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //新增料件
    public function new(Request $request)
    {
        $reDive = new responseObj();
        if(Session::has('username'))
        {
            $number = $request->input('number');
            $name = $request->input('name');
            $format = $request->input('format');

            $numbers = DB::table('O庫_material')->pluck('料號');
            //判斷料號是否重複
            for($i = 0 ; $i < count($numbers) ; $i ++)
            {
                if($number == $numbers[$i])
                {
                    $reDive->boolean = false;
                    $reDive->passbool = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                    /*return back()->withErrors([
                    'number' => '料號 is repeated , Please enter another 料號',
                    ]);*/
                }
                else
                {
                    continue;
                }
            }
            if($request->input('number') !== null && $request->input('name') !== null)
            {
                DB::beginTransaction();
                try {
                DB::table('O庫_material')
                ->insert(['料號' => $number, '品名' => $name, '規格' => $format]);
                DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $reDive->boolean = false;
                    $reDive->passbool = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
                $reDive->boolean = true;
                $reDive->passbool = true;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
            else
            {
                return view('obound.new');
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫料件上傳
    public function uploadmaterial(Request $request)
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
            return view('obound.uploadmaterial')->with(['data' => $sheetData]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //庫存上傳頁面
    function uploadmaterialpage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.uploadmaterial1');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //上傳資料新增至資料庫
    public function insertuploadmaterial(Request $request)
    {
        if (Session::has('username'))
        {
            $count = $request->input('count');
            $record = 0;
            $row = 0;
            for($i = 0 ; $i < $count ; $i ++)
            {
                $number =  $request->input('data0'. $i);
                $name =  $request->input('data1'. $i);
                $format =  $request->input('data2'. $i);

                $numbers = DB::table('O庫_material')->pluck('料號');
                $row = $i + 1;
                //判斷料號是否重複
                for($i = 0 ; $i < count($numbers) ; $i ++)
                {
                    if(strcasecmp($number,$numbers[$i]) === 0)
                    {
                        $mess = trans('oboundpageLang.row').' : '.$row.' '.trans('oboundpageLang.isnrepeat');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        window.location.href='uploadmaterial';
                        </script>");
                        return;
                        /*return back()->withErrors([
                        'number' => '料號 is repeated , Please enter another 料號',
                        ]);*/
                    }
                    else
                    {
                        continue;
                    }
                }

                DB::beginTransaction();
                try {
                    DB::table('O庫_material')
                    ->insert(['料號' => $number, '品名' => $name, '規格' => $format]);
                    DB::commit();
                    $record++;
                }catch (\Exception $e) {
                    DB::rollback();
                    $mess = $e->getmessage();
                    echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                    </script>");
                    return view('obound.uploadmaterial1');
                }

            }

            $mess = trans('oboundpageLang.total').' '.$record.trans('oboundpageLang.record').' '
                .trans('oboundpageLang.isn').' '.trans('oboundpageLang.upload1').' '
                .trans('oboundpageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/obound';
                </script>");
        }

        else
        {
            return redirect(route('member.login'));
        }

    }


    //料件信息查詢頁面
    public function material(Request $request)
    {
        if(Session::has('username'))
        {
            return view('obound.searchmaterial');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //料件信息查詢
    public function searchmaterial(Request $request)
    {
        if(Session::has('username'))
        {
            if($request->input('number') === null)
            {
                return view('obound.searchmaterialok')->with(['data' => O庫Material::cursor()]);
            }
            else if($request->input('number') !== null)
            {
                $input = $request->input('number');

                $datas = DB::table('O庫_material')
                ->where('料號', 'like', $input.'%')
                ->get();

                return view("obound.searchmaterialok")
                ->with(['data' => $datas]);

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫
    public function inbound(Request $request)
    {
        if(Session::has('username'))
        {

            return view('obound.inbound')->with(['client' => 客戶別::cursor()])
            ->with(['inreason' => 入庫原因::cursor()]);

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫新增
    public function inboundnew(Request $request)
    {

        Session::forget('client');
        Session::forget('inreason');
        Session::forget('number');
        Session::forget('name');
        Session::forget('format');
        Session::forget('oboundinbound');
        $reDive = new responseObj();
        if(Session::has('username'))
        {

            if($request->input('client') !== null && $request->input('inreason') !== null)
            {
                $client = $request->input('client');
                $inreason = $request->input('inreason');
                $number = $request->input('number');
                $name = DB::table('O庫_material')->where('料號', $number)->value('品名');
                $format = DB::table('O庫_material')->where('料號', $number)->value('規格');

                if($name === null || $format === null)
                {
                    $reDive->boolean = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
                else
                {
                    Session::put('client' , $client);
                    Session::put('inreason' , $inreason);
                    Session::put('number' , $number);
                    Session::put('name' , $name);
                    Session::put('format' , $format);
                    Session::put('oboundinbound' , $format);
                    $reDive->boolean = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                }
            }
            else
            {
                return view('obound.inbound');
            }



        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫新增頁面
    public function inboundnewok(Request $request)
    {
        if (Session::has('username'))
        {
            if(Session::has('oboundinbound'))
            {
                Session::forget('oboundinbound');
                return view("obound.inboundnew");
            }
            else
            {
                return redirect(route('obound.inbound'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫新增提交
    public function inboundnewsubmit(Request $request)
    {
        $reDive = new  responseObj();
        if (Session::has('username'))
        {
            if($request->input('amount') !== null)
            {
                $client = $request->input('client');
                $number = $request->input('number');
                $name = $request->input('name');
                $format = $request->input('format');
                $amount = $request->input('amount');
                $inreason = $request->input('inreason');
                $inpeople = $request->input('inpeople');
                $bound = $request->input('bound');
                $remark = $request->input('remark');
                $time = Carbon::now();
                $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');

                //入庫數小於等於零
                if($amount <= 0)
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
                        $max = DB::table('O庫inbound')->max('時間');
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
                            $num = DB::table('O庫inbound')->max('入庫單號');
                            $num = intval($num);
                            $num ++;
                            $num = strval($num);
                            $opentime = $num;
                        }
                        DB::beginTransaction();
                        try {
                            DB::table('O庫inbound')
                            ->insert(['入庫單號' => $opentime, '料號' => $number, '品名' => $name, '規格' => $format, '客戶別' => $client
                            , '庫別' => $bound, '數量' => $amount, '入庫人員' => $inpeople, '時間' => $time, '備註' => $remark, '入庫原因' => $inreason]);

                            DB::table('O庫inventory')
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('庫別', $bound)
                                ->update(['現有庫存' => $stock + $amount , '最後更新時間' => $time]);
                            DB::commit();
                        }catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                        }
                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $reDive->message = $opentime;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                    else
                    {
                        $i = '0001';
                        $max = DB::table('O庫inbound')->max('時間');
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
                            $num = DB::table('O庫inbound')->max('入庫單號');
                            $num = intval($num);
                            $num ++;
                            $num = strval($num);
                            $opentime = $num;
                        }
                        DB::beginTransaction();
                        try {
                            DB::table('O庫inbound')
                            ->insert(['入庫單號' => $opentime, '料號' => $number, '品名' => $name, '規格' => $format, '客戶別' => $client
                            , '庫別' => $bound, '數量' => $amount, '入庫人員' => $inpeople, '時間' => $time, '備註' => $remark, '入庫原因' => $inreason]);

                            DB::table('O庫inventory')
                            ->insert(['料號' => $number, '現有庫存' => $amount, '客戶別' => $client, '庫別' => $bound, '最後更新時間' => $time
                            , '品名' => $name, '規格' => $format]);
                            DB::commit();
                        }catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                        }
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
                return redirect(route('obound.inbound'));
            }
        }

        else
        {
            return redirect(route('member.login'));
        }

    }

    /*//入庫-新增提交成功
    public function inboundnewsubmitok()
    {
        if (Session::has('username'))
        {
            if(Session::has('inboundnewsubmitok'))
            {
                Session::forget('inboundnewsubmitok');
                return view("obound.inboundnewsubmitok");

            }
            else
            {
                return redirect(route('obound.inbound'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    //O庫-入庫查詢頁面
    public function inboundsearch(Request $request)
    {
        if(Session::has('username'))
        {
            return view('obound.inboundsearch')->with(['client' => 客戶別::cursor()])
            ->with(['bound' => O庫::cursor()]);

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫查詢ok
    public function inboundsearchok(Request $request)
    {
        if(Session::has('username'))
        {
            //all empty
            if($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null && !($request->has('date')))
            {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()]);
            }
            //select client
            else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null && !($request->has('date')))
            {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()->where('客戶別' , $request->input('client'))]);
            }
            //input bound
            else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null && !($request->has('date')))
            {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()->where('庫別' , $request->input('bound'))]);
            }
            //input material number
            else if($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->get();
                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
            //select date
            else if($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()->whereBetween('時間' , [$begin, $end])]);
            }
            //select client and bound
            else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null && !($request->has('date')))
            {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                ->where('客戶別' , $request->input('client'))->where('庫別' , $request->input('bound'))]);
            }
            //select client and number
            else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->where('客戶別' , $request->input('client'))
                    ->get();

                return view('obound.inboundsearchok')->with(['data' => $datas]);

            }
            //select client and time
            else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                ->whereBetween('時間' , [$begin, $end])->where('客戶別' , $request->input('client'))]);
            }
            //select bound and number
            else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->where('庫別' , $request->input('bound'))
                    ->get();

                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
            //select bound and time
            else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                ->whereBetween('入庫時間' , [$begin, $end])->where('庫別' , $request->input('bound'))]);
            }
            //select number and time
            else if($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->whereBetween('時間' , [$begin, $end])
                    ->get();

                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
            //select client and bound and number
            else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->where('客戶別' , $request->input('client'))
                    ->where('庫別' , $request->input('bound'))
                    ->get();

                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
            //select client and bound and time
            else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null && ($request->has('date')))
            {

                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                ->whereBetween('時間' , [$begin, $end])->where('庫別' , $request->input('bound'))
                ->where('客戶別' , $request->input('client'))]);
            }
            //select client and number and time
            else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->where('客戶別' , $request->input('client'))
                    ->whereBetween('時間' , [$begin, $end])
                    ->get();

                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
            //select bound and number and time
            else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->where('庫別' , $request->input('bound'))
                    ->whereBetween('時間' , [$begin, $end])
                    ->get();

                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
            //select all
            else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null && ($request->has('date')))
            {
                $begin = date($request->input('begin'));
                $endDate = strtotime($request->input('end'));
                $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
                $input = $request->input('number');
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $input.'%')
                    ->where('庫別' , $request->input('bound'))
                    ->where('客戶別' , $request->input('client'))
                    ->whereBetween('時間' , [$begin, $end])
                    ->get();

                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-刪除入庫
    public function delete(Request $request)
    {
        if (Session::has('username'))
        {

            $count = $request->input('count');
            $sure = false;
            for($i = 0 ; $i < $count ; $i++)
            {
                if($request->has('innumber' . $i))
                {
                    $time = Carbon::now();
                    $client = $request->input('client' . $i);
                    $number = $request->input('material' . $i);
                    $bound = $request->input('bound' . $i);
                    $amount = $request->input('amount' . $i);
                    $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');

                    if($stock < $amount)
                    {
                        $mess = trans('oboundpageLang.lessstock').' '.trans('oboundpageLang.nowstock').' : '.$stock.' '
                        .trans('oboundpageLang.inboundnum').' : '.$amount;
                        echo ("<script LANGUAGE='JavaScript'>
                            window.alert('$mess');
                            window.location.href='inboundsearch';
                            </script>");
                            return;
                    }
                    else
                    {
                        DB::beginTransaction();
                        try {
                            DB::table('O庫inventory')
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('庫別', $bound)
                                ->update(['現有庫存' => $stock - $amount , '最後更新時間' => $time]);

                                DB::table('O庫inbound')
                                ->where('入庫單號', $request->input('number' . $i))
                                ->delete();
                                $list = $request->input('number' . $i);
                                DB::commit();
                                $sure = true;
                        }catch (\Exception $e) {
                            DB::rollback();
                        }
                    }

                }
                else
                {
                    continue;
                }
            }
            if($sure)
            {
                $mess = trans('oboundpageLang.delete').trans('oboundpageLang.inlist'). ' : '.
                $list.trans('oboundpageLang.success');
                    echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    window.location.href='inboundsearch';
                    </script>");
            }
            else
            {
                $mess = trans('oboundpageLang.nocheck');
                echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    window.location.href='inboundsearch';
                    </script>");
            }

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-庫存上傳頁面
    public function upload(Request $request)
    {
        if(Session::has('username'))
        {
            return view('obound.upload');

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-庫存上傳
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
            return view('obound.uploadinventory')->with(['data' => $sheetData]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-庫存上傳頁面
    function uploadinventorypage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.uploadinventory1');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-上傳資料新增至資料庫
    public function insertuploadinventory(Request $request)
    {
        if (Session::has('username'))
        {
            $count = $request->input('count');
            $record = 0;
            $now = Carbon::now();
            $j = false;
            for($i = 0 ; $i < $count ; $i ++)
            {
                $client =  $request->input('data0'. $i);
                $number =  $request->input('data1'. $i);
                $amount =  $request->input('data2'. $i);
                $bound =  $request->input('data3'. $i);
                $name = $request->input('data4'. $i);
                $format = $request->input('data5'. $i);
                $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');
                $bounds = DB::table('O庫')->pluck('O庫')->toArray();
                if(in_array($bound,$bounds)) $j = true;
                if($stock === null)
                {
                    DB::beginTransaction();
                    try {
                        if($j === false)
                        {
                            DB::table('O庫')
                            ->insert(['O庫' => $bound]);
                        }
                        DB::table('O庫inventory')
                            ->insert(['料號' => $number , '現有庫存' => $amount , '庫別' => $bound ,'客戶別' => $client
                            , '最後更新時間' => $now , '品名' => $name , '規格' => $format]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $mess = $e->getmessage();
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('obound.uploadinventory1');
                    }
                }
                else
                {
                    DB::beginTransaction();
                    try {
                        DB::table('O庫inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('庫別', $bound)
                            ->update(['現有庫存' => $stock + $amount , '最後更新時間' => $now]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $mess = $e->getmessage();
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('obound.uploadinventory1');
                    }
                }
            }
            $mess = trans('oboundpageLang.total').$record.trans('oboundpageLang.record')
                .trans('templateWords.obound').trans('oboundpageLang.stockupload')
                .trans('oboundpageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/obound';
                </script>");

        }

        else
        {
            return redirect(route('member.login'));
        }

    }

    //O庫-庫存查詢頁面
    public function searchstock(Request $request)
    {
        if(Session::has('username'))
        {
            return view('obound.searchstock')->with(['client' => 客戶別::cursor()])
            ->with(['bound' => O庫::cursor()]);

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-庫存查詢
    public function searchstocksubmit(Request $request)
    {
        if(Session::has('username'))
        {
            $client = $request->input('client');
            $bound = $request->input('bound');
            $number = $request->input('number');

            //不良品inventory
            if($request->has('nogood'))
            {
                if ($request->input('number') !== null) {
                    $datas = DB::table('O庫不良品inventory')
                        ->where('料號', 'like', $request->input('number') . '%')
                        ->get();
                }
                //all empty
                if($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null)
                {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()]);
                }
                //select client
                else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null)
                {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()->where('客戶別' , $client)]);
                }
                //select bound
                else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null)
                {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()->where('庫別' , $bound)]);
                }

                //input material number
                else if($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null)
                {
                    return view('obound.searchstockok')->with(['data' => $datas]);
                }
                //select client and bound
                else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null )
                {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()->where('客戶別' , $client)->where('庫別' , $bound)]);

                }
                //select client and number
                else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null )
                {
                    return view('obound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)]);
                }
                //select bound and number
                else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null )
                {
                    return view('obound.searchstockok')->with(['data' => $datas->where('庫別' , $bound)]);
                }
                //select all
                else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null)
                {

                    return view('obound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('庫別' , $bound)]);

                }
            }
            //inventory
            else
            {
                if ($request->input('number') !== null) {
                    $datas = DB::table('O庫inventory')
                        ->where('料號', 'like', $request->input('number') . '%')
                        ->get();
                }
                //all empty
                if($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null)
                {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()]);
                }
                //select client
                else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null)
                {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()->where('客戶別' , $client)]);
                }
                //select bound
                else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null)
                {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()->where('庫別' , $bound)]);
                }

                //input material number
                else if($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null)
                {
                    return view('obound.searchstockok')->with(['data' => $datas]);
                }
                //select client and bound
                else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null )
                {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()->where('客戶別' , $client)->where('庫別' , $bound)]);

                }
                //select client and number
                else if($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null )
                {
                    return view('obound.searchstockok')->with(['data' => $datas->where('料號' , $number)]);
                }
                //select bound and number
                else if($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null )
                {
                    return view('obound.searchstockok')->with(['data' => $datas->where('庫別' , $bound)]);
                }
                //select all
                else if($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null)
                {

                    return view('obound.searchstockok')->with(['data' => $datas->where('客戶別' , $client)->where('庫別' , $bound)]);

                }
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料
    public function pick(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.pick')->with(['client' => 客戶別::cursor()])
                ->with(['machine' => 機種::cursor()])
                ->with(['production' => 製程::cursor()])
                ->with(['line' => 線別::cursor()])
                ->with(['usereason' => 領用原因::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料
    public function back(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.back')->with(['client' => 客戶別::cursor()])
                ->with(['machine' => 機種::cursor()])
                ->with(['production' => 製程::cursor()])
                ->with(['line' => 線別::cursor()])
                ->with(['backreason' => 退回原因::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料單頁面
    public function picklistpage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.picklistpage')->with(['data' => O庫Outbound::cursor()->whereNull('發料人員')]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料單頁面
    public function backlistpage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.backlistpage')->with(['data' => O庫出庫退料::cursor()->whereNull('收料人員')]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料單
    public function picklist(Request $request)
    {
        if (Session::has('username'))
        {
            //刪除領料單
            if($request->has('delete'))
            {
                $list =  $request->input('list');
                DB::table('O庫outbound')
                ->where('領料單號', $request->input('list'))
                ->delete();


                $mess = trans('oboundpageLang.delete').trans('oboundpageLang.picklistnum'). ' : '.
                $list.trans('oboundpageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/obound';
                </script>");

            }
            else
            {
                $list =  $request->input('list');
                if($list === null)
                {
                    return view('obound.picklist')->with(['number' => O庫outbound::cursor()->whereNull('發料人員')])
                        ->with(['data' => O庫outbound::cursor()->whereNull('發料人員')])
                        ->with(['people' => 人員信息::cursor()])
                        ->with(['people1' => 人員信息::cursor()]);
                }
                else
                {
                    return view('obound.picklist')->with(['number' => O庫outbound::cursor()->whereNull('發料人員')->where('領料單號',$list)])
                        ->with(['data' => O庫outbound::cursor()->whereNull('發料人員')->where('領料單號',$list)])
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

    //O庫-退料單
    public function backlist(Request $request)
    {
        if (Session::has('username'))
        {
            //刪除退料單
            if($request->has('delete'))
            {
                $list =  $request->input('list');
                DB::table('O庫出庫退料')
                ->where('退料單號', $request->input('list'))
                ->delete();

                $mess = trans('oboundpageLang.delete').trans('oboundpageLang.backlistnum'). ' : '.
                $list.trans('oboundpageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/obound';
                </script>");

            }
            else
            {
                $list =  $request->input('list');
                if($list === null)
                {
                    return view('obound.backlist')->with(['number' => O庫出庫退料::cursor()->whereNull('收料人員')])
                        ->with(['data' => O庫出庫退料::cursor()->whereNull('收料人員')])
                        ->with(['people' => 人員信息::cursor()])
                        ->with(['people1' => 人員信息::cursor()])
                        ->with(['bound' => O庫::cursor()]);
                }
                else
                {
                    return view('obound.backlist')->with(['number' => O庫出庫退料::cursor()->whereNull('收料人員')->where('領料單號',$list)])
                        ->with(['data' => O庫出庫退料::cursor()->whereNull('收料人員')->where('退料單號',$list)])
                        ->with(['people' => 人員信息::cursor()])
                        ->with(['people1' => 人員信息::cursor()])
                        ->with(['bound' => O庫::cursor()]);
                }
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交領料單
    public function picklistsubmit(Request $request)
    {

        $reDive = new responseObj();
        if (Session::has('username'))
        {
            if ($request->input('amount') !== null && $request->input('bound') !== null)
            {
                $list = $request->input('list');
                $amount = $request->input('amount');
                $advance = $request->input('advance');
                $number = $request->input('number');
                $client = $request->input('client');
                $reason = $request->input('reason');
                $sendpeople = $request->input('sendpeople');
                $pickpeople = $request->input('pickpeople');
                $bound = $request->input('bound');
                $time = Carbon::now();
                $sendname = DB::table('人員信息')->where('工號', $sendpeople)->value('姓名');
                $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');
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
                    $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');
                    //庫存小於實際領用數量,無法出庫
                    if ($amount > $stock)
                    {
                        $reDive->boolean = false;
                        $reDive->passbool = true;
                        $reDive->passstock = true;
                        $reDive->position = $bound;
                        $reDive->nowstock = $stock;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                    else
                    {
                        DB::beginTransaction();
                        try {
                            DB::table('O庫outbound')
                                ->where('領料單號', $list)
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->update([
                                    '實際領用數量' => $amount, '實領差異原因' => $reason, '庫別' => $bound,
                                    '領料人員' => $pickname, '領料人員工號' => $pickpeople, '發料人員' => $sendname, '發料人員工號' => $sendpeople,
                                    '出庫時間' => $time
                                ]);

                            DB::table('O庫inventory')
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('庫別', $bound)
                                ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $time]);

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                            $reDive->passstock = false;
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
                return view('obound.picklist');
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交退料單
    public function backlistsubmit(Request $request)
    {

        $reDive = new responseObj();
        if (Session::has('username'))
        {
            if ($request->input('amount') !== null && $request->input('bound') !== null)
            {
                $list = $request->input('list');
                $amount = $request->input('amount');
                $advance = $request->input('advance');
                $number = $request->input('number');
                $client = $request->input('client');
                $reason = $request->input('reason');
                $backpeople = $request->input('backpeople');
                $pickpeople = $request->input('pickpeople');
                $bound = $request->input('bound');
                $status = $request->input('status');
                $time = Carbon::now();
                $backname = DB::table('人員信息')->where('工號', $backpeople)->value('姓名');
                $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');
                $name = DB::table('O庫_material')->where('料號', $number)->value('品名');
                $format = DB::table('O庫_material')->where('料號', $number)->value('規格');
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
                        $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');
                    }
                    else
                    {
                        $stock = DB::table('O庫不良品inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');
                    }
                    if ($stock === null)
                    {
                        DB::beginTransaction();
                        try {
                            DB::table('O庫出庫退料')
                                ->where('退料單號', $list)
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->update([
                                    '實際退回數量' => $amount, '實退差異原因' => $reason, '庫別' => $bound,
                                    '收料人員' => $pickname, '收料人員工號' => $pickpeople, '退料人員' => $backname, '退料人員工號' => $backpeople,
                                    '入庫時間' => $time, '功能狀況' => $status
                                ]);
                            if($status === '良品')
                            {
                                DB::table('O庫inventory')
                                ->insert(['料號' => $number, '現有庫存' => $amount, '庫別' => $bound, '客戶別' => $client
                                , '最後更新時間' => $time , '品名' => $name , '規格' => $format]);
                            }
                            else
                            {
                                DB::table('O庫不良品inventory')
                                ->insert(['料號' => $number, '現有庫存' => $amount, '庫別' => $bound, '客戶別' => $client
                                , '最後更新時間' => $time , '品名' => $name , '規格' => $format]);
                            }
                            DB::commit();
                        } catch (\Exception $e) {

                            $reDive->boolean = false;
                            $reDive->passbool = false;
                            $reDive->message = $e->getmessage();
                            $myJSON = json_encode($reDive);
                            echo $myJSON;
                        }

                        Session::put('backlistsubmitok', $list);
                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    } else {
                        DB::beginTransaction();
                        try {
                            DB::table('O庫出庫退料')
                                ->where('退料單號', $list)
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->update([
                                    '實際退回數量' => $amount, '實退差異原因' => $reason, '庫別' => $bound,
                                    '收料人員' => $pickname, '收料人員工號' => $pickpeople, '退料人員' => $backname, '退料人員工號' => $backpeople,
                                    '入庫時間' => $time, '功能狀況' => $status
                                ]);
                                if($status === '良品')
                                {
                                    DB::table('O庫inventory')
                                        ->where('客戶別', $client)
                                        ->where('料號', $number)
                                        ->where('庫別', $bound)
                                        ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $time]);
                                }
                                else
                                {
                                    DB::table('O庫不良品inventory')
                                        ->where('客戶別', $client)
                                        ->where('料號', $number)
                                        ->where('庫別', $bound)
                                        ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $time]);
                                }

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $reDive->message = $e->getmessage();
                            $reDive->boolean = false;
                            $reDive->passbool = false;
                            $myJSON = json_encode($reDive);
                        echo $myJSON;
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
                return view('obound.backlist');
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料記錄表
    public function pickrecord(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.pickrecord')->with(['client' => 客戶別::cursor()])
                ->with(['production' => 製程::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料記錄表
    public function backrecord(Request $request)
    {
        if (Session::has('username'))
        {
            return view('obound.backrecord')->with(['client' => 客戶別::cursor()])
                ->with(['production' => 製程::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料記錄表查詢
    public function pickrecordsearch(Request $request)
    {
        if(Session::has('username'))
        {
            $client = $request->input('client');
            $number =  $request->input('number');
            $production = $request->input('production');
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            //all empty
            if($client === null && $production === null && $number === null && !($request->has('date')))
            {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')]);
            }
            //select client
            else if($client !== null && $production === null && $number === null && !($request->has('date')))
            {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->where('客戶別' , $client)]);
            }
            //select production
            else if($client === null && $production !== null && $number === null && !($request->has('date')))
            {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->where('製程' , $production)]);
            }
            //input material number
            else if($client === null && $production === null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();

                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')]);

            }
            //select date
            else if($client === null && $production === null && $number === null  && ($request->has('date')))
            {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間' , [$begin, $end])]);
            }
            //select client and production
            else if($client !== null && $production !== null && $number === null  && !($request->has('date')))
            {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->where('客戶別' , $client)->where('製程' , $production)]);
            }
            //select client and number
            else if($client !== null && $production === null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();

                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號' , $number)->where('客戶別' , $client)]);

            }
            //select client and time
            else if($client !== null && $production === null && $number === null  && ($request->has('date')))
            {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間' , [$begin, $end])->where('客戶別' , $client)]);
            }
            //select production and number
            else if($client === null && $production !== null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號' , $number)->where('製程' , $production)]);
            }
            //select production and time
            else if($client === null && $production !== null && $number === null  && ($request->has('date')))
            {

                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間' , [$begin, $end])->where('製程' , $production)]);
            }
            //select number and time
            else if($client === null && $production === null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->whereBetween('出庫時間' , [$begin, $end])->where('料號' , $number)]);
            }
            //select client and production and number
            else if($client !== null && $production !== null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號' , $number)->where('製程' , $production)
                ->where('客戶別' , $client)]);

            }
            //select client and production and time
            else if($client !== null && $production !== null && $number === null  && ($request->has('date')))
            {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間' , [$begin, $end])->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and number and time
            else if($client !== null && $production === null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->whereBetween('出庫時間' , [$begin, $end])->where('料號' , $number)
                ->where('客戶別' , $client)]);
            }
            //select production and number and time
            else if($client === null && $production !== null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->whereBetween('出庫時間' , [$begin, $end])->where('料號' , $number)
                ->where('製程' , $production)]);

            }


            //select all
            else if($client !== null && $production !== null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號' , $number)->where('製程' , $production)
                    ->where('客戶別' , $client)->whereBetween('出庫時間' , [$begin, $end])]);

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料記錄表查詢
    public function backrecordsearch(Request $request)
    {
        if(Session::has('username'))
        {
            $client = $request->input('client');
            $number =  $request->input('number');
            $production = $request->input('production');
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            //all empty
            if($client === null && $production === null && $number === null && !($request->has('date')))
            {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')]);
            }
            //select client
            else if($client !== null && $production === null && $number === null && !($request->has('date')))
            {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->where('客戶別' , $client)]);
            }
            //select production
            else if($client === null && $production !== null && $number === null && !($request->has('date')))
            {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->where('製程' , $production)]);
            }
            //input material number
            else if($client === null && $production === null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();

                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')]);

            }
            //select date
            else if($client === null && $production === null && $number === null  && ($request->has('date')))
            {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間' , [$begin, $end])]);
            }
            //select client and production
            else if($client !== null && $production !== null && $number === null  && !($request->has('date')))
            {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->where('客戶別' , $client)->where('製程' , $production)]);
            }
            //select client and number
            else if($client !== null && $production === null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();

                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號' , $number)->where('客戶別' , $client)]);

            }
            //select client and time
            else if($client !== null && $production === null && $number === null  && ($request->has('date')))
            {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間' , [$begin, $end])->where('客戶別' , $client)]);
            }
            //select production and number
            else if($client === null && $production !== null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號' , $number)->where('製程' , $production)]);
            }
            //select production and time
            else if($client === null && $production !== null && $number === null  && ($request->has('date')))
            {

                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間' , [$begin, $end])->where('製程' , $production)]);
            }
            //select number and time
            else if($client === null && $production === null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->whereBetween('入庫時間' , [$begin, $end])->where('料號' , $number)]);
            }
            //select client and production and number
            else if($client !== null && $production !== null && $number !== null  && !($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號' , $number)->where('製程' , $production)
                ->where('客戶別' , $client)]);

            }
            //select client and production and time
            else if($client !== null && $production !== null && $number === null  && ($request->has('date')))
            {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間' , [$begin, $end])->where('製程' , $production)
                ->where('客戶別' , $client)]);
            }
            //select client and number and time
            else if($client !== null && $production === null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->whereBetween('入庫時間' , [$begin, $end])->where('料號' , $number)
                ->where('客戶別' , $client)]);
            }
            //select production and number and time
            else if($client === null && $production !== null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->whereBetween('入庫時間' , [$begin, $end])->where('料號' , $number)
                ->where('製程' , $production)]);

            }


            //select all
            else if($client !== null && $production !== null && $number !== null  && ($request->has('date')))
            {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                ->where('料號', 'like', $input.'%')
                ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號' , $number)->where('製程' , $production)
                    ->where('客戶別' , $client)->whereBetween('入庫時間' , [$begin, $end])]);

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料添加
    public function pickadd(Request $request)
    {
        Session::forget('client');
        Session::forget('machine');
        Session::forget('production');
        Session::forget('line');
        Session::forget('usereason');
        Session::forget('name');
        Session::forget('format');
        Session::forget('number');
        $reDive = new responseObj();
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null)
            {

                    $client = $request->input('client');
                    $machine = $request->input('machine');
                    $production = $request->input('production');
                    $line = $request->input('line');
                    $usereason = $request->input('usereason');
                    $number = $request->input('number');
                    $name = DB::table('O庫_material')->where('料號', $number)->value('品名');
                    $format = DB::table('O庫_material')->where('料號', $number)->value('規格');
                    $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->value('現有庫存');

                    if ($name !== null && $format !== null) {
                        Session::put('number', $number);
                        Session::put('client', $client);
                        Session::put('machine', $machine);
                        Session::put('production', $production);
                        Session::put('line', $line);
                        Session::put('usereason', $usereason);
                        Session::put('name', $name);
                        Session::put('format', $format);
                        Session::put('pick', $client);
                        if ($stock > 0)
                        {
                            $reDive->boolean = true;
                            $reDive->passbool = true;
                            $reDive->passstock = true;
                            $myJSON = json_encode($reDive);
                            echo $myJSON;
                        }
                        //沒有庫存
                        else
                        {
                            $reDive->boolean = true;
                            $reDive->passbool = true;
                            $reDive->passstock = false;
                            $myJSON = json_encode($reDive);
                            echo $myJSON;
                        }
                    }
                    //沒有料號
                    else
                    {
                        $reDive->boolean = true;
                        $reDive->passbool = false;
                        $reDive->passstock = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
            }
            else
            {
                return view('obound.pick');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料添加
    public function backadd(Request $request)
    {
        Session::forget('client');
        Session::forget('machine');
        Session::forget('production');
        Session::forget('line');
        Session::forget('backreason');
        Session::forget('name');
        Session::forget('format');
        Session::forget('number');
        $reDive = new responseObj();
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null)
            {

                    $client = $request->input('client');
                    $machine = $request->input('machine');
                    $production = $request->input('production');
                    $line = $request->input('line');
                    $backreason = $request->input('backreason');
                    $number = $request->input('number');
                    $name = DB::table('O庫_material')->where('料號', $number)->value('品名');
                    $format = DB::table('O庫_material')->where('料號', $number)->value('規格');

                    if ($name !== null && $format !== null) {
                        Session::put('number', $number);
                        Session::put('client', $client);
                        Session::put('machine', $machine);
                        Session::put('production', $production);
                        Session::put('line', $line);
                        Session::put('backreason', $backreason);
                        Session::put('name', $name);
                        Session::put('format', $format);
                        Session::put('back', $client);
                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;

                    }
                    //沒有料號
                    else
                    {
                        $reDive->boolean = true;
                        $reDive->passbool = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
            }
            else
            {
                return view('obound.back');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料添加頁面
    public function pickaddok()
    {
        if (Session::has('username')) {
            if (Session::has('pick'))
            {
                Session::forget('pick');
                return view("obound.pickadd");
            }
            else
            {
                return redirect(route('obound.pick'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料添加頁面
    public function backaddok()
    {
        if (Session::has('username')) {
            if (Session::has('back'))
            {
                Session::forget('back');
                return view("obound.backadd");
            }
            else
            {
                return redirect(route('obound.back'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交領料添加
    public function pickaddsubmit(Request $request)
    {
        $reDive = new  responseObj();
        if (Session::has('username'))
        {
            if ($request->input('amount') !== null)
            {
                $number = $request->input('number');
                $name = $request->input('name');
                $format = $request->input('format');
                $amount = $request->input('amount');
                $remark = $request->input('remark');
                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $line = $request->input('line');
                $usereason = $request->input('usereason');
                $i = '0001';
                $max = DB::table('O庫outbound')->max('開單時間');
                $maxtime = date_create(date('Y-m-d', strtotime($max)));
                $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
                $interval = date_diff($maxtime, $nowtime);
                $interval = $interval->format('%R%a');
                $interval = (int)($interval);
                if ($interval > 0)
                {
                    $opentime = Carbon::now()->format('Ymd') . $i;
                }
                else
                {
                    $num = DB::table('O庫outbound')->max('領料單號');
                    $num = intval($num);
                    $num++;
                    $num = strval($num);
                    $opentime = $num;
                }
                if ($remark === null || $remark === ' ') $remark = '';
                DB::beginTransaction();
                try {
                    DB::table('O庫outbound')
                    ->insert(['料號' => $number, '品名' => $name, '規格' => $format, '客戶別' => $client, '機種' => $machine
                    , '製程' => $production, '領用原因' => $usereason, '線別' => $line, '預領數量' => $amount, '實際領用數量' => $amount, '備註' => $remark
                    , '領料單號' => $opentime, '開單時間' => Carbon::now()]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $reDive->boolean = false;
                }

                $reDive->boolean = true;
                $reDive->message = $opentime;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
            else
            {
                return redirect(route('obound.pick'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交退料添加
    public function backaddsubmit(Request $request)
    {
        $reDive = new  responseObj();
        if (Session::has('username'))
        {
            if ($request->input('amount') !== null)
            {
                $number = $request->input('number');
                $name = $request->input('name');
                $format = $request->input('format');
                $amount = $request->input('amount');
                $remark = $request->input('remark');
                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $line = $request->input('line');
                $backreason = $request->input('backreason');
                $i = '0001';
                $max = DB::table('O庫出庫退料')->max('開單時間');
                $maxtime = date_create(date('Y-m-d', strtotime($max)));
                $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
                $interval = date_diff($maxtime, $nowtime);
                $interval = $interval->format('%R%a');
                $interval = (int)($interval);
                if ($interval > 0)
                {
                    $opentime = Carbon::now()->format('Ymd') . $i;
                }
                else
                {
                    $num = DB::table('O庫出庫退料')->max('退料單號');
                    $num = intval($num);
                    $num++;
                    $num = strval($num);
                    $opentime = $num;
                }
                if ($remark === '' || $remark === null) $remark = '';
                DB::beginTransaction();
                try {
                    DB::table('O庫出庫退料')
                    ->insert(['料號' => $number, '品名' => $name, '規格' => $format, '客戶別' => $client, '機種' => $machine
                    , '製程' => $production, '退回原因' => $backreason, '線別' => $line, '預退數量' => $amount, '實際退回數量' => $amount, '備註' => $remark
                    , '退料單號' => $opentime, '開單時間' => Carbon::now()]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $reDive->boolean = false;
                }

                $reDive->boolean = true;
                $reDive->message = $opentime;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
            else
            {
                return redirect(route('obound.back'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    /*//O庫-提交領料添加成功
    public function pickaddsubmitok()
    {
        if (Session::has('username'))
        {
            if (Session::has('pickaddsubmitok'))
            {
                Session::forget('pickaddsubmitok');
                return view("obound.pickaddsubmitok");
            }
            else
            {
                return redirect(route('obound.pick'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交退料添加成功
    public function backaddsubmitok()
    {
        if (Session::has('username'))
        {
            if (Session::has('backaddsubmitok'))
            {
                Session::forget('backaddsubmitok');
                return view("obound.backaddsubmitok");
            }
            else
            {
                return redirect(route('obound.back'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交領料單成功
    public function picklistsubmitok()
    {
        if (Session::has('username')) {
            if (Session::has('picklistsubmitok'))
            {
                Session::forget('picklistsubmitok');
                return view("obound.picklistsubmitok");
            }
            else
            {
                return redirect(route('obound.picklist'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交退料單成功
    public function backlistsubmitok()
    {
        if (Session::has('username')) {
            if (Session::has('backlistsubmitok'))
            {
                Session::forget('backlistsubmitok');
                return view("obound.backlistsubmitok");
            }
            else
            {
                return redirect(route('obound.backlist'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

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
