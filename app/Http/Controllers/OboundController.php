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

    /*public function index()
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
    }*/

    //新增料件
    public function new(Request $request)
    {
        if (Session::has('username')) {
            $number = $request->input('number');
            $name = $request->input('name');
            $format = $request->input('format');

            $numbers = DB::table('O庫_material')->pluck('料號');
            //判斷料號是否重複
            for ($i = 0; $i < count($numbers); $i++) {
                if (strcasecmp($number, $numbers[$i]) === 0) {

                    return \Response::json(['message' => 'isn repeat'], 420/* Status code here default is 200 ok*/);
                } else {
                    continue;
                }
            }
            return \Response::json([
                'number' => $number, 'name' => $name, 'format' => $format
            ]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫料件上傳
    public function uploadmaterial(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('obound.uploadmaterial')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //上傳料件新增至資料庫(料號)
    public function insertuploadmaterial(Request $request)
    {
        if (Session::has('username')) {

            $row = $request->input('row');
            $count = count($row);
            $record = 0;
            $check = array();
            $Alldata = json_decode($request->input('AllData'));

            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $number =  $Alldata[0][$i];
                    $name =   $Alldata[1][$i];
                    $format =   $Alldata[2][$i];

                    $test = DB::table('O庫_material')->where('料號', $number)->value('品名');

                    if ($test === null) {
                        DB::table('O庫_material')
                            ->insert([
                                '料號' => $number, '品名' => $name, '規格' => $format
                            ]);
                        $record++;
                        array_push($check, $row[$i]);
                    } else {
                        continue;
                    }
                } //for
                DB::commit();
                return \Response::json(['record' => $record, 'check' => $check]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getMessage();
                return \Response::json(['message' => $mess], 423/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }


    //料件信息查詢
    public function searchmaterial(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('number') === null) {
                return view('obound.searchmaterialok')->with(['data' => O庫Material::cursor()]);
            } else if ($request->input('number') !== null) {
                $input = $request->input('number');

                $datas = DB::table('O庫_material')
                    ->where('料號', 'like', $input . '%')
                    ->get();

                return view("obound.searchmaterialok")
                    ->with(['data' => $datas]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫
    public function inbound(Request $request)
    {
        if (Session::has('username')) {

            return view('obound.inbound')->with(['client' => 客戶別::cursor()])
                ->with(['inreason' => 入庫原因::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫新增
    public function inboundnew(Request $request)
    {
        if (Session::has('username')) {

            if ($request->input('client') !== null && $request->input('inreason') !== null) {
                $client = $request->input('client');
                $inreason = $request->input('inreason');
                $number = $request->input('number');
                $name = DB::table('O庫_material')->where('料號', $number)->value('品名');
                $format = DB::table('O庫_material')->where('料號', $number)->value('規格');

                if ($name === null || $format === null) {
                    return \Response::json(['message' => 'No Results Found!'], 421/* Status code here default is 200 ok*/);
                } else {
                    return \Response::json([
                        'number' => $number, 'client' => $client, 'inreason' => $inreason,
                        'name' => $name, 'format' => $format,
                    ]/* Status code here default is 200 ok*/);
                }
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-入庫新增提交
    public function inboundnewsubmit(Request $request)
    {
        if (Session::has('username')) {
            $Alldata = json_decode($request->input('AllData'));
            $count = count($Alldata[0]);
            $j = '0001';
            $max = DB::table('O庫inbound')->max('時間');
            $maxtime = date_create(date('Y-m-d', strtotime($max)));
            $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int)($interval);
            if ($interval > 0) {
                $opentime = Carbon::now()->format('Ymd') . $j;
            } else {
                $num = DB::table('O庫inbound')->max('入庫單號');
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
                    $bound = $Alldata[2][$i];
                    $amount = $Alldata[3][$i];
                    $inreason = $Alldata[4][$i];
                    $name = $Alldata[5][$i];
                    $format = $Alldata[6][$i];
                    $mark = $Alldata[7][$i];

                    $now = Carbon::now();
                    $inpeople = $request->input('inpeople');
                    $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');


                    if ($stock !== null) {
                        DB::table('O庫inbound')
                            ->insert([
                                '入庫單號' => $opentime, '料號' => $number, '品名' => $name, '規格' => $format, '數量' => $amount, '庫別' => $bound,
                                '入庫人員' => $inpeople, '客戶別' => $client, '入庫原因' => $inreason, '時間' => $now, '備註' => $mark
                            ]);

                        DB::table('O庫inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('庫別', $bound)
                            ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $now, '品名' => $name, '規格' => $format]);
                    } //if stock
                    else {
                        DB::table('O庫inbound')
                            ->insert([
                                '入庫單號' => $opentime, '料號' => $number, '品名' => $name, '規格' => $format, '數量' => $amount, '庫別' => $bound,
                                '入庫人員' => $inpeople, '客戶別' => $client, '入庫原因' => $inreason, '時間' => $now, '備註' => $mark
                            ]);

                        DB::table('O庫inventory')
                            ->insert([
                                '料號' => $number, '現有庫存' => $amount, '庫別' => $bound, '客戶別' => $client,
                                '最後更新時間' => $now, '品名' => $name, '規格' => $format
                            ]);
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

    //O庫-入庫查詢ok
    public function inboundsearchok(Request $request)
    {
        if (Session::has('username')) {

            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            if ($request->input('number') !== null) {
                $datas = DB::table('O庫inbound')
                    ->where('料號', 'like', $request->input('number') . '%')
                    ->get();
            }

            //all empty
            if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null && !($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()]);
            }
            //select client
            else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null && !($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()->where('客戶別', $request->input('client'))]);
            }
            //input bound
            else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null && !($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()->where('庫別', $request->input('bound'))]);
            }
            //input material number
            else if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null && !($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => $datas]);
            }
            //select date
            else if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null && ($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()->whereBetween('時間', [$begin, $end])]);
            }
            //select client and bound
            else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null && !($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                    ->where('客戶別', $request->input('client'))->where('庫別', $request->input('bound'))]);
            }
            //select client and number
            else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null && !($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => $datas->where('客戶別', $request->input('client'))]);
            }
            //select client and time
            else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null && ($request->has('date'))) {

                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                    ->whereBetween('時間', [$begin, $end])->where('客戶別', $request->input('client'))]);
            }
            //select bound and number
            else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null && !($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => $datas->where('庫別', $request->input('bound'))]);
            }
            //select bound and time
            else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null && ($request->has('date'))) {

                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                    ->whereBetween('入庫時間', [$begin, $end])->where('庫別', $request->input('bound'))]);
            }
            //select number and time
            else if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null && ($request->has('date'))) {
                return view('obound.inboundsearchok')->with(['data' => $datas->whereBetween('時間', [$begin, $end])]);
            }
            //select client and bound and number
            else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null && !($request->has('date'))) {

                return view('obound.inboundsearchok')->with(['data' => $datas->where('庫別', $request->input('bound'))->where('客戶別', $request->input('client'))]);
            }
            //select client and bound and time
            else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null && ($request->has('date'))) {

                return view('obound.inboundsearchok')->with(['data' => O庫Inbound::cursor()
                    ->whereBetween('時間', [$begin, $end])->where('庫別', $request->input('bound'))
                    ->where('客戶別', $request->input('client'))]);
            }
            //select client and number and time
            else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null && ($request->has('date'))) {

                return view('obound.inboundsearchok')->with(['data' => $datas->where('客戶別', $request->input('client'))->whereBetween('時間', [$begin, $end])]);
            }
            //select bound and number and time
            else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null && ($request->has('date'))) {

                return view('obound.inboundsearchok')->with(['data' => $datas->whereBetween('時間', [$begin, $end])->where('庫別', $request->input('bound'))]);
            }
            //select all
            else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null && ($request->has('date'))) {

                return view('obound.inboundsearchok')->with(['data' => $datas->whereBetween('時間', [$begin, $end])
                    ->where('庫別', $request->input('bound'))->where('客戶別', $request->input('client'))]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-刪除入庫
    public function delete(Request $request)
    {
        if (Session::has('username')) {
            $now = Carbon::now();
            $time = $request->input('time');
            $name = $request->input('name');
            $format = $request->input('format');
            $list = $request->input('list');
            $number = $request->input('number');
            $amount = $request->input('amount');
            $bound = $request->input('bound');
            $inpeople = $request->input('inpeople');
            $client = $request->input('client');
            $inreason = $request->input('inreason');
            $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');
            DB::beginTransaction();
            try {
                if ($stock < $amount) {
                    if ($stock === null) $stock = 0;

                    return \Response::json(['stock' => $stock, 'amount' => $amount], 420/* Status code here default is 200 ok*/);
                }

                DB::table('O庫inventory')
                    ->where('客戶別', $client)
                    ->where('料號', $number)
                    ->where('庫別', $bound)
                    ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $now]);

                DB::table('O庫inbound')
                    ->where('入庫單號', $list)
                    ->where('客戶別', $client)
                    ->where('料號', $number)
                    ->where('數量', $amount)
                    ->where('入庫人員', $inpeople)
                    ->where('入庫原因', $inreason)
                    ->where('庫別', $bound)
                    ->where('品名', $name)
                    ->where('規格', $format)
                    ->where('時間', $time)
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

    /*//O庫-庫存上傳頁面
    public function upload(Request $request)
    {
        if (Session::has('username')) {
            return view('obound.upload');
        } else {
            return redirect(route('member.login'));
        }
    }*/

    //O庫-庫存上傳
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
            return view('obound.uploadinventory')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }



    //O庫-上傳資料新增至資料庫(庫存)
    public function insertuploadinventory(Request $request)
    {
        if (Session::has('username')) {
            $bounds = DB::table('O庫')->pluck('O庫')->toArray();
            $count = $request->input('count');
            $now = Carbon::now();
            $Alldata = json_decode($request->input('AllData'));
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {

                    if (!(in_array($Alldata[5][$i], $bounds))) {
                        DB::table('O庫')
                            ->insert(['O庫' => $Alldata[5][$i]]);
                    }

                    $stock = DB::table('O庫inventory')->where('客戶別', $Alldata[0][$i])->where('料號', $Alldata[1][$i])->where('庫別', $Alldata[5][$i])->value('現有庫存');

                    if ($stock === null) {
                        DB::table('O庫inventory')
                            ->insert([
                                '料號' => $Alldata[1][$i], '現有庫存' => $Alldata[4][$i], '庫別' => $Alldata[5][$i],
                                '客戶別' => $Alldata[0][$i], '最後更新時間' => $now, '品名' => $Alldata[2][$i], '規格' => $Alldata[3][$i]
                            ]);
                    } else {
                        DB::table('O庫inventory')
                            ->where('客戶別', $Alldata[0][$i])
                            ->where('料號', $Alldata[1][$i])
                            ->where('庫別', $Alldata[5][$i])
                            ->update(['現有庫存' => $stock + $Alldata[4][$i], '最後更新時間' => $now, '品名' => $Alldata[2][$i], '規格' => $Alldata[3][$i]]);
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

    /*//O庫-庫存查詢頁面
    public function searchstock(Request $request)
    {
        if (Session::has('username')) {
            return view('obound.searchstock')->with(['client' => 客戶別::cursor()])
                ->with(['bound' => O庫::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }*/

    //O庫-庫存查詢
    public function searchstocksubmit(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $bound = $request->input('bound');
            $number = $request->input('number');

            //不良品inventory
            if ($request->has('nogood')) {
                if ($request->input('number') !== null) {
                    $datas = DB::table('O庫不良品inventory')
                        ->where('料號', 'like', $request->input('number') . '%')
                        ->where('現有庫存', '>', 0)
                        ->get();
                }
                //all empty
                if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()->where('現有庫存', '>', 0)]);
                }
                //select client
                else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()->where('現有庫存', '>', 0)
                        ->where('客戶別', $client)]);
                }
                //select bound
                else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()->where('現有庫存', '>', 0)
                        ->where('庫別', $bound)]);
                }

                //input material number
                else if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null) {
                    return view('obound.searchstockok')->with(['data' => $datas]);
                }
                //select client and bound
                else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫不良品Inventory::cursor()->where('現有庫存', '>', 0)
                        ->where('客戶別', $client)->where('庫別', $bound)]);
                }
                //select client and number
                else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null) {
                    return view('obound.searchstockok')->with(['data' => $datas->where('客戶別', $client)]);
                }
                //select bound and number
                else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null) {
                    return view('obound.searchstockok')->with(['data' => $datas->where('庫別', $bound)]);
                }
                //select all
                else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null) {

                    return view('obound.searchstockok')->with(['data' => $datas->where('客戶別', $client)->where('庫別', $bound)]);
                }
            }
            //inventory
            else {
                if ($request->input('number') !== null) {
                    $datas = DB::table('O庫inventory')
                        ->where('料號', 'like', $request->input('number') . '%')
                        ->where('現有庫存', '>', 0)
                        ->get();
                }
                //all empty
                if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()->where('現有庫存', '>', 0)]);
                }
                //select client
                else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()->where('客戶別', $client)->where('現有庫存', '>', 0)]);
                }
                //select bound
                else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()->where('庫別', $bound)->where('現有庫存', '>', 0)]);
                }

                //input material number
                else if ($request->input('client') === null && $request->input('bound') === null && $request->input('number') !== null) {
                    return view('obound.searchstockok')->with(['data' => $datas]);
                }
                //select client and bound
                else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') === null) {
                    return view('obound.searchstockok')->with(['data' => O庫Inventory::cursor()->where('客戶別', $client)
                        ->where('庫別', $bound)->where('現有庫存', '>', 0)]);
                }
                //select client and number
                else if ($request->input('client') !== null && $request->input('bound') === null && $request->input('number') !== null) {
                    return view('obound.searchstockok')->with(['data' => $datas->where('料號', $number)]);
                }
                //select bound and number
                else if ($request->input('client') === null && $request->input('bound') !== null && $request->input('number') !== null) {
                    return view('obound.searchstockok')->with(['data' => $datas->where('庫別', $bound)]);
                }
                //select all
                else if ($request->input('client') !== null && $request->input('bound') !== null && $request->input('number') !== null) {

                    return view('obound.searchstockok')->with(['data' => $datas->where('客戶別', $client)->where('庫別', $bound)]);
                }
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料單
    public function picklist(Request $request)
    {
        if (Session::has('username')) {
            //刪除領料單
            if ($request->has('delete')) {
                $list =  $request->input('list');
                DB::table('O庫outbound')
                    ->where('領料單號', $request->input('list'))
                    ->delete();

                $mess = trans('oboundpageLang.delete') . ' : ' . trans('oboundpageLang.picklistnum')
                    . $list . trans('oboundpageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='picklist';
                </script>");
            } else {
                $list =  $request->input('list');
                $datas =  DB::table('O庫outbound')
                    ->join('O庫_material', 'O庫outbound.料號', '=', 'O庫_material.料號')
                    ->wherenull('O庫outbound.發料人員')
                    ->select('O庫outbound.*')
                    ->get();
                return view('obound.picklist')->with(['number' => $datas->where('領料單號', $list)])
                    ->with(['data' => $datas->where('領料單號', $list)])
                    ->with(['people' => 人員信息::cursor()])
                    ->with(['people1' => 人員信息::cursor()])
                    ->with(['check' => 人員信息::cursor()]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料單
    public function backlist(Request $request)
    {
        if (Session::has('username')) {
            //刪除退料單
            if ($request->has('delete')) {
                $list =  $request->input('list');
                DB::table('O庫出庫退料')
                    ->where('退料單號', $request->input('list'))
                    ->delete();

                $mess = trans('oboundpageLang.delete') . ' : ' . trans('oboundpageLang.backlistnum')
                    . $list . trans('oboundpageLang.success');

                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='backlist';
                </script>");
            } else {
                $list =  $request->input('list');
                $datas =  DB::table('O庫出庫退料')
                    ->join('O庫_material', 'O庫出庫退料.料號', '=', 'O庫_material.料號')
                    ->wherenull('O庫出庫退料.收料人員')
                    ->select('O庫出庫退料.*')
                    ->get();
                return view('obound.backlist')->with(['number' => $datas->where('退料單號', $list)])
                    ->with(['data' => $datas->where('退料單號', $list)])
                    ->with(['people' => 人員信息::cursor()])
                    ->with(['people1' => 人員信息::cursor()])
                    ->with(['bounds' => O庫::cursor()])
                    ->with(['check' => 人員信息::cursor()]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交領料單
    public function picklistsubmit(Request $request)
    {

        if (Session::has('username')) {
            $Alldata = json_decode($request->input('AllData'));
            $count = count($Alldata[0]);
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $client = $Alldata[0][$i];
                    $machine = $Alldata[1][$i];
                    $production = $Alldata[2][$i];
                    $usereason = $Alldata[3][$i];
                    $line = $Alldata[4][$i];
                    $number = $Alldata[5][$i];
                    $name = $Alldata[6][$i];
                    $format = $Alldata[7][$i];
                    $advance = $Alldata[8][$i];
                    $amount = $Alldata[9][$i];
                    $remark = $Alldata[10][$i];
                    $reason = $Alldata[11][$i];
                    $list = $Alldata[12][$i];
                    $opentime = $Alldata[13][$i];
                    $bound = $Alldata[14][$i];
                    $sendpeople = $request->input('sendpeople');
                    $pickpeople = $request->input('pickpeople');

                    $now = Carbon::now();
                    $sendname = DB::table('人員信息')->where('工號', $sendpeople)->value('姓名');
                    $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');
                    $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');
                    //庫存小於實際領用數量,無法出庫
                    if ($amount > $stock) {
                        DB::rollBack();
                        return \Response::json(['bound' => $bound, 'nowstock' => $stock, 'row' => $i], 421/* Status code here default is 200 ok*/);
                    } else {
                        $test = DB::table('O庫outbound')->where('領料單號', $list)->where('客戶別', $client)->where('料號', $number)
                            ->where('預領數量', $advance)->whereNull('發料人員')->get();

                        if ($remark === null) $remark = '';

                        if (($test->count()) > 0) {

                            DB::table('O庫outbound')
                                ->where('領料單號', $list)
                                ->where('客戶別', $client)
                                ->where('料號', $number)
                                ->where('預領數量', $advance)
                                ->whereNUll('發料人員')
                                ->update([
                                    '實際領用數量' => $amount, '實領差異原因' => $reason, '庫別' => $bound,
                                    '領料人員' => $pickname, '領料人員工號' => $pickpeople, '發料人員' => $sendname, '發料人員工號' => $sendpeople,
                                    '出庫時間' => $now
                                ]);
                        } else {
                            DB::table('O庫outbound')
                                ->insert([
                                    '客戶別' => $client, '機種' => $machine, '製程' => $production, '領用原因' => $usereason, '線別' => $line,
                                    '料號' => $number, '品名' => $name, '規格' => $format, '預領數量' => $advance,
                                    '實際領用數量' => $amount, '實領差異原因' => $reason, '備註' => $remark, '領料單號' => $list,
                                    '開單時間' => $opentime,  '庫別' => $bound, '領料人員' => $pickname, '領料人員工號' => $pickpeople,
                                    '發料人員' => $sendname, '發料人員工號' => $sendpeople, '出庫時間' => $now
                                ]);
                        }

                        DB::table('O庫inventory')
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('庫別', $bound)
                            ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $now]);
                    } // else
                } //for
                DB::commit();
                return \Response::json(['record' => $count, 'list' => $list]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 422/* Status code here default is 200 ok*/);
            } //try - catch
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交退料單
    public function backlistsubmit(Request $request)
    {
        if (Session::has('username')) {
            $Alldata = json_decode( $request->input('AllData') );
            $count = count($Alldata[0]);
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $client = $Alldata[0][$i];
                    $machine = $Alldata[1][$i];
                    $production = $Alldata[2][$i];
                    $backreason = $Alldata[3][$i];
                    $line = $Alldata[4][$i];
                    $number = $Alldata[5][$i];
                    $name = $Alldata[6][$i];
                    $format = $Alldata[7][$i];
                    $advance = $Alldata[8][$i];
                    $amount = $Alldata[9][$i];
                    $remark = $Alldata[10][$i];
                    $reason = $Alldata[11][$i];
                    $list = $Alldata[12][$i];
                    $opentime = $Alldata[13][$i];
                    $bound = $Alldata[14][$i];
                    $status = $Alldata[15][$i];
                    $backpeople = $request->input('backpeople');
                    $pickpeople = $request->input('pickpeople');
                    $now = Carbon::now();
                    $backname = DB::table('人員信息')->where('工號', $backpeople)->value('姓名');
                    $pickname = DB::table('人員信息')->where('工號', $pickpeople)->value('姓名');

                    if ($status === '良品') {
                        $inventoryname = 'O庫inventory';
                    } else {
                        $inventoryname = 'O庫不良品inventory';
                    }

                    $stock = DB::table($inventoryname)->where('客戶別', $client)->where('料號', $number)->where('庫別', $bound)->value('現有庫存');

                    $test = DB::table('O庫出庫退料')->where('退料單號', $list)->where('客戶別', $client)->where('料號', $number)
                        ->where('預退數量', $advance)->whereNull('收料人員')->get();

                    if ($remark === null) $remark = '';

                    if (($test->count()) > 0) {

                        DB::table('O庫出庫退料')
                            ->where('退料單號', $list)
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('預退數量', $advance)
                            ->whereNUll('收料人員')
                            ->update([
                                '實際退回數量' => $amount, '實退差異原因' => $reason, '庫別' => $bound,
                                '收料人員' => $pickname, '收料人員工號' => $pickpeople, '退料人員' => $backname, '退料人員工號' => $backpeople,
                                '入庫時間' => $now, '功能狀況' => $status
                            ]);
                    } else {
                        DB::table('O庫出庫退料')
                            ->insert([
                                '客戶別' => $client, '機種' => $machine, '製程' => $production, '退回原因' => $backreason, '線別' => $line,
                                '料號' => $number, '品名' => $name, '規格' => $format, '預退數量' => $advance,
                                '實際退回數量' => $amount, '實退差異原因' => $reason, '備註' => $remark, '退料單號' => $list,
                                '開單時間' => $opentime,  '庫別' => $bound, '收料人員' => $pickname, '收料人員工號' => $pickpeople,
                                '退料人員' => $backname, '退料人員工號' => $backpeople, '入庫時間' => $now, '功能狀況' => $status,
                            ]);
                    }

                    if ($stock === null) {
                        DB::table($inventoryname)
                            ->insert(['料號' => $number, '現有庫存' => $amount, '庫別' => $bound, '客戶別' => $client, '最後更新時間' => $now , '品名' => $name , '規格' => $format]);
                    } else {
                        DB::table($inventoryname)
                            ->where('客戶別', $client)
                            ->where('料號', $number)
                            ->where('庫別', $bound)
                            ->update(['現有庫存' => $stock + $amount, '最後更新時間' => $now]);
                    }
                } //for
                DB::commit();
                return \Response::json(['record' => $count, 'list' => $list]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            } //try - catch
        } else {
            return redirect(route('member.login'));
        }
    }

    /*//O庫-領料記錄表
    public function pickrecord(Request $request)
    {
        if (Session::has('username')) {
            return view('obound.pickrecord')->with(['client' => 客戶別::cursor()])
                ->with(['production' => 製程::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料記錄表
    public function backrecord(Request $request)
    {
        if (Session::has('username')) {
            return view('obound.backrecord')->with(['client' => 客戶別::cursor()])
                ->with(['production' => 製程::cursor()]);
        } else {
            return redirect(route('member.login'));
        }
    }*/

    //O庫-領料記錄表查詢
    public function pickrecordsearch(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $number =  $request->input('number');
            $production = $request->input('production');
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            //all empty
            if ($client === null && $production === null && $number === null && !($request->has('date'))) {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')]);
            }
            //select client
            else if ($client !== null && $production === null && $number === null && !($request->has('date'))) {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->where('客戶別', $client)]);
            }
            //select production
            else if ($client === null && $production !== null && $number === null && !($request->has('date'))) {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->where('製程', $production)]);
            }
            //input material number
            else if ($client === null && $production === null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();

                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')]);
            }
            //select date
            else if ($client === null && $production === null && $number === null  && ($request->has('date'))) {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間', [$begin, $end])]);
            }
            //select client and production
            else if ($client !== null && $production !== null && $number === null  && !($request->has('date'))) {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->where('客戶別', $client)->where('製程', $production)]);
            }
            //select client and number
            else if ($client !== null && $production === null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();

                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號', $number)->where('客戶別', $client)]);
            }
            //select client and time
            else if ($client !== null && $production === null && $number === null  && ($request->has('date'))) {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間', [$begin, $end])->where('客戶別', $client)]);
            }
            //select production and number
            else if ($client === null && $production !== null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號', $number)->where('製程', $production)]);
            }
            //select production and time
            else if ($client === null && $production !== null && $number === null  && ($request->has('date'))) {

                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間', [$begin, $end])->where('製程', $production)]);
            }
            //select number and time
            else if ($client === null && $production === null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->whereBetween('出庫時間', [$begin, $end])->where('料號', $number)]);
            }
            //select client and production and number
            else if ($client !== null && $production !== null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號', $number)->where('製程', $production)
                    ->where('客戶別', $client)]);
            }
            //select client and production and time
            else if ($client !== null && $production !== null && $number === null  && ($request->has('date'))) {
                return view('obound.pickrecordsearchok')->with(['data' => O庫Outbound::cursor()->whereNotNull('發料人員')->whereBetween('出庫時間', [$begin, $end])->where('製程', $production)
                    ->where('客戶別', $client)]);
            }
            //select client and number and time
            else if ($client !== null && $production === null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->whereBetween('出庫時間', [$begin, $end])->where('料號', $number)
                    ->where('客戶別', $client)]);
            }
            //select production and number and time
            else if ($client === null && $production !== null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->whereBetween('出庫時間', [$begin, $end])->where('料號', $number)
                    ->where('製程', $production)]);
            }


            //select all
            else if ($client !== null && $production !== null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫outbound')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.pickrecordsearchok')->with(['data' => $datas->whereNotNull('發料人員')->where('料號', $number)->where('製程', $production)
                    ->where('客戶別', $client)->whereBetween('出庫時間', [$begin, $end])]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料記錄表查詢
    public function backrecordsearch(Request $request)
    {
        if (Session::has('username')) {
            $client = $request->input('client');
            $number =  $request->input('number');
            $production = $request->input('production');
            $begin = date($request->input('begin'));
            $endDate = strtotime($request->input('end'));
            $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $endDate));
            //all empty
            if ($client === null && $production === null && $number === null && !($request->has('date'))) {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')]);
            }
            //select client
            else if ($client !== null && $production === null && $number === null && !($request->has('date'))) {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->where('客戶別', $client)]);
            }
            //select production
            else if ($client === null && $production !== null && $number === null && !($request->has('date'))) {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->where('製程', $production)]);
            }
            //input material number
            else if ($client === null && $production === null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();

                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')]);
            }
            //select date
            else if ($client === null && $production === null && $number === null  && ($request->has('date'))) {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間', [$begin, $end])]);
            }
            //select client and production
            else if ($client !== null && $production !== null && $number === null  && !($request->has('date'))) {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->where('客戶別', $client)->where('製程', $production)]);
            }
            //select client and number
            else if ($client !== null && $production === null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();

                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號', $number)->where('客戶別', $client)]);
            }
            //select client and time
            else if ($client !== null && $production === null && $number === null  && ($request->has('date'))) {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間', [$begin, $end])->where('客戶別', $client)]);
            }
            //select production and number
            else if ($client === null && $production !== null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號', $number)->where('製程', $production)]);
            }
            //select production and time
            else if ($client === null && $production !== null && $number === null  && ($request->has('date'))) {

                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間', [$begin, $end])->where('製程', $production)]);
            }
            //select number and time
            else if ($client === null && $production === null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->whereBetween('入庫時間', [$begin, $end])->where('料號', $number)]);
            }
            //select client and production and number
            else if ($client !== null && $production !== null && $number !== null  && !($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號', $number)->where('製程', $production)
                    ->where('客戶別', $client)]);
            }
            //select client and production and time
            else if ($client !== null && $production !== null && $number === null  && ($request->has('date'))) {
                return view('obound.backrecordsearchok')->with(['data' => O庫出庫退料::cursor()->whereNotNull('收料人員')->whereBetween('入庫時間', [$begin, $end])->where('製程', $production)
                    ->where('客戶別', $client)]);
            }
            //select client and number and time
            else if ($client !== null && $production === null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->whereBetween('入庫時間', [$begin, $end])->where('料號', $number)
                    ->where('客戶別', $client)]);
            }
            //select production and number and time
            else if ($client === null && $production !== null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->whereBetween('入庫時間', [$begin, $end])->where('料號', $number)
                    ->where('製程', $production)]);
            }


            //select all
            else if ($client !== null && $production !== null && $number !== null  && ($request->has('date'))) {
                $input = $request->input('number');
                $datas = DB::table('O庫出庫退料')
                    ->where('料號', 'like', $input . '%')
                    ->get();
                return view('obound.backrecordsearchok')->with(['data' => $datas->whereNotNull('收料人員')->where('料號', $number)->where('製程', $production)
                    ->where('客戶別', $client)->whereBetween('入庫時間', [$begin, $end])]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-領料添加
    public function pickadd(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null) {


                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $line = $request->input('line');
                $usereason = $request->input('usereason');
                $number = $request->input('number');
                $name = DB::table('O庫_material')->where('料號', $number)->value('品名');
                $format = DB::table('O庫_material')->where('料號', $number)->value('規格');

                $stock = DB::table('O庫inventory')->where('客戶別', $client)->where('料號', $number)->sum('現有庫存');

                $showstock  = '';
                $nowstock = DB::table('O庫inventory')->where('料號', $number)->where('客戶別', $client)->where('現有庫存', '>', 0)->pluck('現有庫存')->toArray();
                $nowloc = DB::table('O庫inventory')->where('料號', $number)->where('客戶別', $client)->where('現有庫存', '>', 0)->pluck('庫別')->toArray();
                $test = array_combine($nowloc, $nowstock);

                foreach ($test as $k => $a) {
                    $showstock = $showstock . __('oboundpageLang.bound') . ' : ' . $k . ' ' . __('oboundpageLang.nowstock') . ' : ' . $a . "\n";
                }

                if ($name !== null && $format !== null) {
                    if ($stock > 0) {
                        return \Response::json([
                            'number' => $number, 'client' => $client, 'machine' => $machine,
                            'production' => $production, 'line' => $line, 'usereason' => $usereason, 'name' => $name,
                            'format' => $format, 'showstock' => $showstock,
                        ]/* Status code here default is 200 ok*/);
                    }
                    //沒有庫存
                    else {
                        return \Response::json(['message' => 'no stock'], 420/* Status code here default is 200 ok*/);
                    }
                }
                //沒有料號
                else {
                    return \Response::json(['message' => 'no isn'], 421/* Status code here default is 200 ok*/);
                }
            } else {
                return view('obound.pick');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-退料添加
    public function backadd(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('client') !== null && $request->input('number') !== null) {

                $client = $request->input('client');
                $machine = $request->input('machine');
                $production = $request->input('production');
                $line = $request->input('line');
                $backreason = $request->input('backreason');
                $number = $request->input('number');
                $name = DB::table('O庫_material')->where('料號', $number)->value('品名');
                $format = DB::table('O庫_material')->where('料號', $number)->value('規格');

                if ($name !== null && $format !== null) {

                    return \Response::json([
                        'number' => $number, 'client' => $client, 'machine' => $machine,
                        'production' => $production, 'line' => $line, 'backreason' => $backreason, 'name' => $name,
                        'format' => $format,
                    ]/* Status code here default is 200 ok*/);
                }
                //料號不存在
                else {
                    return \Response::json(['message' => 'no isn'], 420/* Status code here default is 200 ok*/);
                }
            } else {
                return view('outbound.back');
            }
        } else {
            return redirect(route('member.login'));
        }
    }


    //O庫-提交領料添加
    public function pickaddsubmit(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $j = '0001';
            $max = DB::table('O庫outbound')->max('開單時間');
            $maxtime = date_create(date('Y-m-d', strtotime($max)));
            $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int)($interval);
            if ($interval > 0) {
                $opentime = Carbon::now()->format('Ymd') . $j;
            } else {
                $num = DB::table('O庫outbound')->max('領料單號');
                $num = intval($num);
                $num++;
                $num = strval($num);
                $opentime = $num;
            }

            $Alldata = json_decode($request->input('AllData'));

            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $client = $Alldata[0][$i];
                    $machine = $Alldata[1][$i];
                    $production = $Alldata[2][$i];
                    $line = $Alldata[3][$i];
                    $usereason = $Alldata[4][$i];
                    $number = $Alldata[5][$i];
                    $name = $Alldata[6][$i];
                    $format = $Alldata[7][$i];
                    $amount = $Alldata[8][$i];
                    $remark = $Alldata[9][$i];
                    if ($remark === null) $remark = '';
                    DB::table('O庫outbound')
                        ->insert([
                            '客戶別' => $client, '機種' => $machine, '製程' => $production, '領用原因' => $usereason, '線別' => $line,
                            '料號' => $number, '品名' => $name, '規格' => $format, '預領數量' => $amount,
                            '實際領用數量' => $amount, '備註' => $remark, '領料單號' => $opentime, '開單時間' => Carbon::now()
                        ]);
                } //for
                DB::commit();
                return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            } // catch

        } else {
            return redirect(route('member.login'));
        }
    }

    //O庫-提交退料添加
    public function backaddsubmit(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $j = '0001';
            $max = DB::table('O庫出庫退料')->max('開單時間');
            $maxtime = date_create(date('Y-m-d', strtotime($max)));
            $nowtime = date_create(date('Y-m-d', strtotime(Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int)($interval);
            if ($interval > 0) {
                $opentime = Carbon::now()->format('Ymd') . $j;
            } else {
                $num = DB::table('O庫出庫退料')->max('退料單號');
                $num = intval($num);
                $num++;
                $num = strval($num);
                $opentime = $num;
            }
            $Alldata = json_decode( $request->input('AllData') );

            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $client = $Alldata[0][$i];
                    $machine = $Alldata[1][$i];
                    $production = $Alldata[2][$i];
                    $line = $Alldata[3][$i];
                    $backreason = $Alldata[4][$i];
                    $number = $Alldata[5][$i];
                    $name = $Alldata[6][$i];
                    $format = $Alldata[7][$i];
                    $amount = $Alldata[8][$i];
                    $remark = $Alldata[9][$i];
                    if ($remark === null) $remark = '';
                    DB::table('O庫出庫退料')
                        ->insert([
                            '客戶別' => $client, '機種' => $machine, '製程' => $production, '退回原因' => $backreason, '線別' => $line,
                            '料號' => $number, '品名' => $name, '規格' => $format, '預退數量' => $amount,
                            '實際退回數量' => $amount, '備註' => $remark, '退料單號' => $opentime, '開單時間' => Carbon::now()
                        ]);
                } // for
                DB::commit();
                return \Response::json(['message' => $opentime, 'record' => $count]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            } //catch

        } else {
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
        if (Session::has('username')) {

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $worksheet = $spreadsheet->getActiveSheet();
            $titlecount = $request->input('titlecount');
            $count = $request->input('count');
            $Alldata = json_decode($request->input('AllData'));

            //填寫表頭
            for ($i = 0; $i < $titlecount; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title')[$i]);
            }

            //填寫內容
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
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        } else {
            return redirect(route('member.login'));
        }
    }
}
