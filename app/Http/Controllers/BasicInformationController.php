<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\入庫原因;
use App\Models\客戶別;
use App\Models\發料部門;
use App\Models\製程;
use App\Models\領用原因;
use App\Models\領用部門;
use App\Models\廠別;
use App\Models\線別;
use App\Models\機種;
use App\Models\儲位;
use App\Models\退回原因;
use App\Models\O庫;
use App\Models\ConsumptiveMaterial;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use Session;
use Route;
use Carbon\Carbon;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class BasicInformationController extends Controller
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
        if (Session::has('username'))
        {
            return view('basic.index')->with(['factorys' => 廠別::cursor()])
            ->with(['clients' => 客戶別::cursor()])
            ->with(['machines' => 機種::cursor()])
            ->with(['productions' => 製程::cursor()])
            ->with(['lines' => 線別::cursor()])
            ->with(['uses' => 領用部門::cursor()])
            ->with(['usereasons' => 領用原因::cursor()])
            ->with(['inreasons' => 入庫原因::cursor()])
            ->with(['positions' => 儲位::cursor()])
            ->with(['sends' => 發料部門::cursor()])
            ->with(['os' => O庫::cursor()])
            ->with(['backs' => 退回原因::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    //基礎信息更新或刪除
    public function changeordelete(Request $request)
    {
        if (Session::has('username')) {


            if (is_array($request->input('data'))) {
                $count = count($request->input('data'));
            } else {
                $count = '0';
            }

            $names = $request->input('data');
            $oldnames = $request->input('olddata');
            $datanew = $request->input('datanew');
            $choose = "";
            $chooseindex = "";
            $errnew = "";

            //factory
            if ($request->input('dataname') == "factory") {
                $choose = "App\Models\廠別";
                $chooseindex = "廠別";
                $table = "廠別";
                $errnew = "factorynew";
            }
            //client
            else if ($request->input('dataname') == "client") {
                $choose = "App\Models\客戶別";
                $chooseindex = '客戶';
                $table = "客戶別";
                $errnew = "clientnew";
            }
            //machine
            else if ($request->input('dataname') == "machine") {
                $choose = "App\Models\機種";
                $chooseindex = '機種';
                $table = "機種";
                $errnew = "machinenew";
            }
            //production
            else if ($request->input('dataname') == "production") {
                $choose = "App\Models\製程";
                $chooseindex = '制程';
                $table = "製程";
                $errnew = "productionnew";
            }
            //line
            else if ($request->input('dataname') == "line") {
                $choose = "App\Models\線別";
                $chooseindex = '線別';
                $table = "線別";
                $errnew = "linenew";
            }
            //use
            else if ($request->input('dataname') == "use") {
                $choose = "App\Models\領用部門";
                $chooseindex = '領用部門';
                $table = "領用部門";
                $errnew = "usenew";
            }
            //usereason
            else if ($request->input('dataname') == "usereason") {
                $choose = "App\Models\領用原因";
                $chooseindex = '領用原因';
                $table = "領用原因";
                $errnew = "usereasonnew";
            }
            //inreason
            else if ($request->input('dataname') == "inreason") {
                $choose = "App\Models\入庫原因";
                $chooseindex = '入庫原因';
                $table = "入庫原因";
                $errnew = "inreasonnew";
            }
            //position
            else if ($request->input('dataname') == "position") {
                $choose = "App\Models\儲位";
                $chooseindex = '儲存位置';
                $table = "儲位";
                $errnew = "positionnew";
            }
            //send
            else if ($request->input('dataname') == "send") {
                $choose = "App\Models\發料部門";
                $chooseindex = '發料部門';
                $table = "發料部門";
                $errnew = "sendnew";
            }
            //o庫
            else if ($request->input('dataname') == "o") {
                $choose = "App\Models\O庫";
                $chooseindex = 'O庫';
                $table = "O庫";
                $errnew = "onew";
            }
            //退回原因
            else if ($request->input('dataname') == "back") {
                $choose = "App\Models\退回原因";
                $chooseindex = '退回原因';
                $table = "退回原因";
                $errnew = "backnew";
            }
            if ($request->input('select') == "刪除") {
                DB::beginTransaction();
                try {
                    for ($i = 0; $i < $count; $i++) {
                        $choose::where($chooseindex, $names[$i])->delete();
                    } //for
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
                }

                return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
            }
            //change
            else if ($request->input('select') == "更新") {
                DB::beginTransaction();
                try {
                    for ($i = 0; $i < $count; $i++) {

                        DB::table($table)
                            ->where($chooseindex, $oldnames[$i])
                            ->update([$chooseindex => $names[$i], 'updated_at' => Carbon::now()]);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }



                if ($datanew !== null) {
                    $test = $choose::onlyTrashed()
                        ->where($chooseindex, $datanew)
                        ->get();

                    DB::beginTransaction();
                    try {
                        if (!$test->isEmpty()) {
                            DB::table($table)
                                ->where($chooseindex, $datanew)
                                ->update(['updated_at' =>  null, 'deleted_at' => null, 'created_at' => Carbon::now()]);
                        } else {
                            DB::table($table)
                                ->insert([$chooseindex => $datanew, 'created_at' => Carbon::now()]);
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $errnew], 422/* Status code here default is 200 ok*/);
                    }
                }
                return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
            } //if-else
        } else {
            return redirect(route('member.login'));
        }
    }

    //料件信息更新或刪除
    public function materialchangeordel(Request $request)
    {
        if (Session::has('username')) {
            //delete
            if ($request->input('select') == "刪除") {
                for ($i = 0; $i < $request->input('count'); $i++) {
                    ConsumptiveMaterial::where('料號', $request->input('number')[$i])->delete();
                    /*DB::table('consumptive_material')
                    ->where('料號', $request->input('number' )[$i])
                    ->delete();*/
                }
                return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
            }
            //change
            else if ($request->input('select') == "更新") {
                $count = $request->input('count');
                DB::beginTransaction();
                try {
                    for ($i = 0; $i < $count; $i++) {
                        $gradea = $request->input('gradea')[$i];
                        $month = $request->input('month')[$i];
                        $send = $request->input('send')[$i];
                        $belong = $request->input('belong')[$i];
                        $price = $request->input('price')[$i];
                        $money = $request->input('money')[$i];
                        $unit = $request->input('unit')[$i];
                        $mpq = $request->input('mpq')[$i];
                        $moq = $request->input('moq')[$i];
                        $lt = $request->input('lt')[$i];
                        $safe = $request->input('safe')[$i];
                        $number = $request->input('number')[$i];
                        $check = $request->input('check');

                        if ($gradea === 'Yes') $gradea = '是';
                        if ($gradea === 'No') $gradea = '否';
                        if ($month === 'Yes') $month = '是';
                        if ($month === 'No') $month = '否';
                        if ($belong === 'Unit consumption' || $belong === '单耗') $belong = '單耗';
                        if ($belong === 'Station') $belong = '站位';

                        DB::table('consumptive_material')
                            ->where('料號', $number)
                            ->update([
                                'A級資材' => $gradea, '月請購' => $month, '發料部門' => $send, '耗材歸屬' => $belong,
                                '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq,
                                'MOQ' => $moq, 'LT' => $lt, '安全庫存' => $safe, 'updated_at' => Carbon::now()
                            ]);
                    } //for
                    DB::commit();
                    return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
                } catch (\Exception $e) {
                    $row = $check[$i];
                    DB::rollback();
                    $mess = $e->getMessage();
                    return \Response::json([
                        'message' => $mess,
                        'row' => $row
                    ], 409/* Status code here default is 200 ok*/);
                }
            }
            //download
            else if ($request->input('select') == "下載") {

                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
                $worksheet = $spreadsheet->getActiveSheet();
                $time = $request->input('time');
                //填寫表頭
                for ($i = 0; $i < 14; $i++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title')[$i]);
                }

                //填寫內容
                for ($i = 0; $i < 14; $i++) {
                    for ($j = 0; $j < $time; $j++) {
                        $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data' . $i)[$j]);
                    }
                }


                // 下載
                $now = Carbon::now()->format('YmdHis');
                //rawurlencode('呆滯庫存查詢');
                $filename = rawurlencode('料件查詢') . $now . '.xlsx';
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
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
                return redirect(route('basic.material'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    /*//料件信息查詢頁面
    public function material(Request $request)
    {
        if(Session::has('username'))
        {
            return view('basic.searchmaterial');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    /*
    //儲位條碼查詢頁面
    public function position(Request $request)
    {
        if(Session::has('username'))
        {
            return view('basic.searchposition');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //料號條碼查詢頁面
    public function number(Request $request)
    {
        if(Session::has('username'))
        {
            return view('basic.searchnumber');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //儲位條碼查詢
    public function searchposition(Request $request)
    {
        if(Session::has('username'))
        {
            if($request->input('position') === null)
            {
                return view('basic.searchpositionok')->with(['data' => 儲位::cursor()]);
            }
            else if($request->input('position') !== null)
            {
                $input = $request->input('position');

                $datas = DB::table('儲位')
                ->where('儲存位置', 'like', $input.'%')
                ->get();

                return view("basic.searchpositionok")
                ->with(['data' => $datas]);

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //料號條碼查詢
    public function searchnumber(Request $request)
    {
        if(Session::has('username'))
        {
            if($request->input('number') === null)
            {
                return view('basic.searchnumberok')->with(['data' => ConsumptiveMaterial::cursor()]);
            }
            else if($request->input('number') !== null && strlen($request->input('number')) <= 12)
            {
                $input = $request->input('number');

                $datas = DB::table('consumptive_material')
                ->where('料號', 'like', $input.'%')
                ->get();

                return view("basic.searchnumberok")
                ->with(['data' => $datas]);

            }
            else
            {
                return back()->withErrors([
                    'number' => '料號長度大於12 , Please enter again',
                ]);
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }
    */

    //料件信息查詢修改
    public function searchmaterial(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('number') === null) {
                return view('basic.searchmaterialok')->with(['data' => ConsumptiveMaterial::cursor()])
                    ->with(['data1' => ConsumptiveMaterial::cursor()])->with(['sends' => 發料部門::cursor()]);
            } else if ($request->input('number') !== null && strlen($request->input('number')) <= 12) {
                $input = $request->input('number');

                $datas = DB::table('consumptive_material')
                    ->where('料號', 'like', $input . '%')
                    ->get();

                return view("basic.searchmaterialok")
                    ->with(['data' => $datas])
                    ->with(['data1' => $datas])
                    ->with(['sends' => 發料部門::cursor()]);
            } else {
                return back()->withErrors([

                    'number' => trans('basicInfoLang.isnlength'),
                ]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }


    //新增料件
    public function new(Request $request)
    {

        if (Session::has('username')) {
            $number = $request->input('number');
            $name = $request->input('name');
            $format = $request->input('format');
            $price = $request->input('price');
            $unit = $request->input('unit');
            $money = $request->input('money');
            $mpq = $request->input('mpq');
            $moq = $request->input('moq');
            $lt = $request->input('lt');
            $gradea = $request->input('gradea');
            $belong = $request->input('belong');
            $month = $request->input('month');
            $send = $request->input('send');
            $safe = $request->input('safe');
            $numbers = DB::table('consumptive_material')->pluck('料號');

            $delete = ConsumptiveMaterial::onlyTrashed()
                ->where('料號', $number)->get();
            //判斷料號是否重複
            for ($i = 0; $i < count($numbers); $i++) {
                if (strcasecmp($number, $numbers[$i]) === 0) {
                    if (!$delete->isEmpty()) {
                        DB::table('consumptive_material')
                            ->where('料號', $number)
                            ->update([
                                '品名' => $name, '規格' => $format, '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq, 'MOQ' => $moq, 'LT' => $lt, '月請購' => $month, 'A級資材' => $gradea, '耗材歸屬' => $belong, '發料部門' => $send, '安全庫存' => $safe, 'updated_at' => Carbon::now(), 'deleted_at' => null
                            ]);


                        return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
                    } else {
                        return \Response::json(['message' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
                        /*return back()->withErrors([
                        'number' => '料號 is repeated , Please enter another 料號',
                        ]);*/
                    }
                } else {
                    continue;
                }
            }

            if ($request->input('number') !== null && $request->input('name') !== null) {
                //長度是否為12
                if (strlen($request->input('number')) !== 12) {

                    return \Response::json(['message' => 'No Results Found!'], 421/* Status code here default is 200 ok*/);

                    /*return back()->withErrors([
                        'number' => '料號長度不為12 , Please enter again',
                        ]);*/
                }

                //check 非月請購是否有填安全庫存
                if ($request->input('month') === '否' && $request->input('safe') === null) {
                    return \Response::json(['message' => 'No Results Found!'], 422/* Status code here default is 200 ok*/);
                    /*return back()->withErrors([
                        'safe' => '非月請購之安全庫存為必填項目',
                    ]);*/
                }

                DB::table('consumptive_material')
                    ->insert([
                        '料號' => $number, '品名' => $name, '規格' => $format, '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq, 'MOQ' => $moq, 'LT' => $lt, '月請購' => $month, 'A級資材' => $gradea, '耗材歸屬' => $belong, '發料部門' => $send, '安全庫存' => $safe, 'created_at' => Carbon::now()
                    ]);

                return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
                //return view('basic.newok');
            } else {
                return view('basic.new')->with(['data' => 發料部門::cursor()]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }


    /*
    //新增料件成功
    public function newok()
    {
        if (Session::has('username'))
        {
            if(Session::has('newmaterialok'))
            {
                return view("basic.newok");
                Session::forget('newmaterialok');
            }
            else
            {
                return redirect(route('basic.new'));
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }*/

    //資料下載
    public function download(Request $request)
    {
        if (Session::has('username')) {

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
            $worksheet = $spreadsheet->getActiveSheet();
            $time = $request->input('time');
            $count = $request->input('count');
            //填寫表頭
            for ($i = 0; $i < $time; $i++) {
                $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title' . $i));
            }

            //填寫內容
            for ($i = 0; $i < $time; $i++) {
                for ($j = 0; $j < $count; $j++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data' . $i . $j));
                }
            }


            // 下載
            $now = Carbon::now()->format('YmdHis');
            $title = $request->input('title');
            $filename = $title . $now . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        } else {
            return redirect(route('member.login'));
        }
    }

    //新增料件上傳
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
            return view('basic.newupload')->with(['data' => $sheetData])->with(['senddata' => 發料部門::cursor()]);;
        } else {
            return redirect(route('member.login'));
        }
    }


    //上傳資料新增至資料庫
    public function insertuploadmaterial(Request $request)
    {
        if (Session::has('username')) {

            $count = $request->input('count');
            $record = 0;
            $row = 0;
            $test = 0;
            $test1 = array();
            $bool = true;
            for ($i = 0; $i < $count; $i++) {
                $test1[$i] = 0;
                $number =  $request->input('number')[$i];
                $numbers = DB::table('consumptive_material')->pluck('料號');
                $delete = ConsumptiveMaterial::onlyTrashed()
                    ->where('料號', $number)->get();
                //判斷料號是否重複
                for ($j = 0; $j < count($numbers); $j++) {
                    if (strcasecmp($number, $numbers[$j]) === 0) {
                        if (!$delete->isEmpty()) {
                            $test1[$i] = 1;
                        } else {
                            //料號重複
                            $bool = false;
                            $row = $i + 1;
                            return \Response::json(['message' => $row], 420/* Status code here default is 200 ok*/);
                        }
                    } else {
                        continue;
                    }

                }
                $test++;

            }

            $number = ($request->input('test'));
            //$data = json_decode($number[0]);
            // $name =  $request->input('data');
            // $format =  $request->input('data');
            // $price =  $request->input('data');
            // $money =  $request->input('data');
            // $unit =  $request->input('data');
            // $mpq =  $request->input('data');
            // $moq =  $request->input('data');
            // $lt =  $request->input('data');
            // $month =  $request->input('data');
            // $gradea =  $request->input('data');
            // $belong =  $request->input('data');
            // $send =  $request->input('data');
            // $safe =  $request->input('data');

            // return \Response::json(['A級資材' => $gradea, '月請購' => $month, '發料部門' => $send, '耗材歸屬' => $belong,
            // '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq,
            // 'MOQ' => $moq, 'LT' => $lt, '安全庫存' => $number] , 422/* Status code here default is 200 ok*/);

            return \Response::json(['安全庫存' => $number] , 422);

            if ($test == $count && $bool == true) {
                DB::beginTransaction();
                try {
                    for ($i = 0; $i < $count; $i++) {
                        $number =  $request->input('number')[$i];
                        $name =  $request->input('name')[$i];
                        $format =  $request->input('format')[$i];
                        $price =  $request->input('price')[$i];
                        $money =  $request->input('money')[$i];
                        $unit =  $request->input('unit')[$i];
                        $mpq =  $request->input('mpq')[$i];
                        $moq =  $request->input('moq')[$i];
                        $lt =  $request->input('lt')[$i];
                        $month =  $request->input('month')[$i];
                        $gradea =  $request->input('gradea')[$i];
                        $belong =  $request->input('belong')[$i];
                        $send =  $request->input('send')[$i];
                        $safe =  $request->input('safe')[$i];

                        if ($gradea === 'Yes') $gradea = '是';
                        if ($gradea === 'No') $gradea = '否';

                        if ($month === 'Yes') $month = '是';
                        if ($month === 'No') $month = '否';

                        if ($belong === 'Unit consumption' || $belong === '单耗') $belong = '單耗';
                        if ($belong === 'Station') $belong = '站位';


                        if ($test1[$i] != 0) {
                            DB::table('consumptive_material')
                                ->where('料號', $number)
                                ->update([
                                    '品名' => $name, '規格' => $format, '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq, 'MOQ' => $moq, 'LT' => $lt, '月請購' => $month, 'A級資材' => $gradea, '耗材歸屬' => $belong, '發料部門' => $send, '安全庫存' => $safe, 'updated_at' => Carbon::now(), 'deleted_at' => null
                                ]);
                        } else {
                            DB::table('consumptive_material')
                                ->insert([
                                    '料號' => $number, '品名' => $name, '規格' => $format, '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq, 'MOQ' => $moq, 'LT' => $lt, '月請購' => $month, 'A級資材' => $gradea, '耗材歸屬' => $belong, '發料部門' => $send, '安全庫存' => $safe, 'created_at' => Carbon::now()
                                ]);
                        }
                        $record++;
                    } //for
                    DB::commit();
                    return \Response::json(['message' => $record]/* Status code here default is 200 ok*/);

                } catch (\Exception $e) {
                    DB::rollback();
                    $mess = $e->getMessage();
                    return \Response::json(['message' => $mess], 423/* Status code here default is 200 ok*/);
                }

            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //基礎資料上傳
    public function uploadbasic(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $choose = $sheetData[0][0];


            if (
                $choose !== "廠別" && $choose !== "客戶別" && $choose !== "機種"  && $choose !== "製程" && $choose !== "線別" && $choose !== "領用部門"
                && $choose !== "領用原因" && $choose !== "入庫原因" && $choose !== "儲位" && $choose !== "發料部門" && $choose !== "O庫" && $choose !== "退回原因"
            ) {
                $mess = trans('basicInfoLang.uploaderror');
                echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    window.location.href = '/basic';
                    </script>");
            } else {
                unset($sheetData[0]);
                return view('basic.uploadbasic')->with(['data' => $sheetData])->with('choose', $choose);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //基礎信息批量新增至資料庫
    public function insertuploadbasic(Request $request)
    {
        if (Session::has('username')) {
            $count = $request->input('count');
            $choose = $request->input('title');
            $dataarray =  $request->input('data');
            $table = "";
            $record = 0;
            $test = 0;
            $test1 = array();
            $row = 0;
            $bool = true;

            if ($choose == '客戶別') {
                $chooseindex = '客戶';
                $table = "App\Models\客戶別";
            } else if ($choose == '儲位') {
                $chooseindex = '儲存位置';
                $table = "App\Models\儲位";
            } else if ($choose == '製程') {
                $chooseindex = '制程';
                $table = "App\Models\製程";
            } else {
                $chooseindex = $choose;
                $table = "App\Models" . "\\" . $choose;
            }


            for ($i = 0; $i < $count; $i++) {
                $test1[$i] = 0;

                $data = $dataarray[$i];
                $datas = DB::table($choose)->pluck($chooseindex);

                $delete = $table::onlyTrashed()
                    ->where($chooseindex, $data)->get();

                //判斷data是否重複
                for ($j = 0; $j < count($datas); $j++) {
                    if (strcasecmp($data, $datas[$j]) === 0) {
                        if (!$delete->isEmpty()) {
                            $test1[$i] = 1;
                        } else {
                            $bool = false;
                            $row = $i + 1;
                            //data repeat
                            return \Response::json(['message' => $row], 420/* Status code here default is 200 ok*/);
                        }
                    } else {

                        continue;
                    }
                }
                $test++;
            }

            if ($test == $count && $bool == true) {
                DB::beginTransaction();
                try {
                    for ($i = 0; $i < $count; $i++) {
                        $data = $dataarray[$i];
                        if ($test1[$i] != 0) {
                            DB::table($choose)
                                ->where($chooseindex, $data)
                                ->update([$chooseindex => $data, 'updated_at' => Carbon::now(), 'deleted_at' => null]);
                        } else {
                            DB::table($choose)
                                ->insert([$chooseindex => $data, 'created_at' => Carbon::now()]);
                        }
                        $record++;
                    } // for
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $i = $i + 1;
                    return \Response::json(['message' => $i], 420/* Status code here default is 200 ok*/);
                }
            }
            return \Response::json(['message' => $record, 'choose' => $choose]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }
}
