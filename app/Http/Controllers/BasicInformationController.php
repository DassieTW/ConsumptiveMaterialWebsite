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
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
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
                            ->update([$chooseindex => $names[$i]]);
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }



                if ($datanew !== null) {
                    /*$test = $choose::onlyTrashed()
                        ->where($chooseindex, $datanew)
                        ->get();*/

                    DB::beginTransaction();
                    try {

                        DB::table($table)
                            ->insert([$chooseindex => $datanew]);

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
                $Alldata = json_decode($request->input('AllData'));

                for ($i = 0; $i < $request->input('count'); $i++) {
                    ConsumptiveMaterial::where('料號', $Alldata[0][$i])->delete();
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
                $Alldata = json_decode($request->input('AllData'));
                try {
                    for ($i = 0; $i < $count; $i++) {
                        $number = $Alldata[0][$i];
                        $gradea = $Alldata[1][$i];
                        $month = $Alldata[2][$i];
                        $send = $Alldata[3][$i];
                        $belong = $Alldata[4][$i];
                        $price  = $Alldata[5][$i];
                        $money = $Alldata[6][$i];
                        $unit = $Alldata[7][$i];
                        $mpq = $Alldata[8][$i];
                        $moq = $Alldata[9][$i];
                        $lt = $Alldata[10][$i];
                        $safe = $Alldata[11][$i];

                        $check = $request->input('check');

                        // if ($gradea === 'Yes') $gradea = '是';
                        // if ($gradea === 'No') $gradea = '否';
                        // if ($month === 'Yes') $month = '是';
                        // if ($month === 'No') $month = '否';
                        // if ($belong === 'Unit consumption' || $belong === '单耗') $belong = '單耗';
                        // if ($belong === 'Station') $belong = '站位';

                        DB::table('consumptive_material')
                            ->where('料號', $number)
                            ->update([
                                'A級資材' => $gradea, '月請購' => $month, '發料部門' => $send, '耗材歸屬' => $belong,
                                '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq,
                                'MOQ' => $moq, 'LT' => $lt, '安全庫存' => $safe, /*'updated_at' => Carbon::now()*/
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
                $Alldata = DB::table('consumptive_material')->get();
                $downloadcount = count($Alldata);
                $arr = ['料號', '品名', '規格', 'A級資材', '月請購', '發料部門', '耗材歸屬', '單價', '幣別', '單位', 'MPQ', 'MOQ', 'LT', '安全庫存'];

                // $stringValueBinder = new StringValueBinder();
                // $stringValueBinder->setNullConversion(false)->setFormulaConversion(false);
                // \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder($stringValueBinder); // make it so it doesnt covert 儲位 to weird number format


                //填寫表頭
                for ($i = 0; $i < 14; $i++) {
                    $worksheet->setCellValueByColumnAndRow($i + 1, 1, $arr[$i]);
                }

                //填寫內容
                for ($i = 0; $i < 14; $i++) {
                    $string  = $arr[$i];
                    for ($j = 0; $j < $downloadcount; $j++) {
                        $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $Alldata[$j]->$string);
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
            $input = $request->input('input');
            $send = $request->input('send');
            dd($send);
            if ($request->input('radio') === "1") {
                if($send !== null)
                {
                    $datas = DB::table('consumptive_material')
                    ->where('發料部門', '=', $send)
                    ->where('料號', 'like', $input . '%')
                    ->get();
                    // return view("basic.searchmaterialok")
                    //     ->with(['data' => $datas])
                    //     ->with(['sends' => 發料部門::cursor()]);
                    return \Response::json(['datas' => $datas, 'sends' => 發料部門::cursor()], 200/* Status code here default is 200 ok*/);

                }
                else
                {

                    $datas = DB::table('consumptive_material')
                        ->where('料號', 'like', $input . '%')
                        ->get();
                    // return view("basic.searchmaterialok")
                    //     ->with(['data' => $datas])
                    //     ->with(['sends' => 發料部門::cursor()]);
                    return \Response::json(['datas' => $datas, 'sends' => 發料部門::cursor()], 200/* Status code here default is 200 ok*/);
                }
            } else {

                if($send !== null)
                {
                    $datas = DB::table('consumptive_material')
                    ->where('發料部門', '=', $send)
                    ->whereIn('料號', $input)
                    ->get();
                    // return view("basic.searchmaterialok")
                    //     ->with(['data' => $datas])
                    //     ->with(['sends' => 發料部門::cursor()]);
                    return \Response::json(['datas' => $datas, 'sends' => 發料部門::cursor()], 200/* Status code here default is 200 ok*/);

                }
                else
                {
                    $datas = DB::table('consumptive_material')
                        ->whereIn('料號', $input)
                        ->get();
                    // return view("basic.searchmaterialok")
                    //     ->with(['data' => $datas])
                    //     ->with(['sends' => 發料部門::cursor()]);
                    return \Response::json(['datas' => $datas, 'sends' => 發料部門::cursor()], 200/* Status code here default is 200 ok*/);
                }
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
            /*$delete = ConsumptiveMaterial::onlyTrashed()
                ->where('料號', $number)->get();*/
            //判斷料號是否重複
            for ($i = 0; $i < count($numbers); $i++) {
                if (strcasecmp($number, $numbers[$i]) === 0) {

                    return \Response::json(['message' => 'isn repeat'], 420/* Status code here default is 200 ok*/);
                } else {
                    continue;
                }
            }

            if ($request->input('number') !== null && $request->input('name') !== null) {
                //長度是否為12
                if (strlen($request->input('number')) !== 12) {

                    return \Response::json(['message' => 'isn not 12'], 421/* Status code here default is 200 ok*/);
                }

                //check 非月請購是否有填安全庫存
                if ($request->input('month') === '否' && $request->input('safe') === null) {
                    return \Response::json(['message' => 'no write safe'], 422/* Status code here default is 200 ok*/);
                    /*return back()->withErrors([
                        'safe' => '非月請購之安全庫存為必填項目',
                    ]);*/
                }

                return \Response::json([
                    'number' => $number, 'name' => $name, 'format' => $format, 'price' => $price,
                    'money' => $money, 'unit' => $unit, 'mpq' => $mpq, 'moq' => $moq, 'lt' => $lt, 'month' => $month,
                    'gradea' => $gradea, 'belong' => $belong, 'send' => $send, 'safe' => $safe
                ]/* Status code here default is 200 ok*/);
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

    // //資料下載
    // public function download(Request $request)
    // {
    //     if (Session::has('username')) {

    //         $spreadsheet = new Spreadsheet();
    //         $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
    //         $worksheet = $spreadsheet->getActiveSheet();
    //         $time = $request->input('time');
    //         $count = $request->input('count');
    //         //填寫表頭
    //         for ($i = 0; $i < $time; $i++) {
    //             $worksheet->setCellValueByColumnAndRow($i + 1, 1, $request->input('title' . $i));
    //         }

    //         //填寫內容
    //         for ($i = 0; $i < $time; $i++) {
    //             for ($j = 0; $j < $count; $j++) {
    //                 $worksheet->setCellValueByColumnAndRow($i + 1, $j + 2, $request->input('data' . $i . $j));
    //             }
    //         }


    //         // 下載
    //         $now = Carbon::now()->format('YmdHis');
    //         $title = $request->input('title');
    //         $filename = $title . $now . '.xlsx';
    //         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //         header('Content-Disposition: attachment;filename="' . $filename . '"');
    //         header('Cache-Control: max-age=0');

    //         $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    //         $writer->save('php://output');
    //     } else {
    //         return redirect(route('member.login'));
    //     }
    // }

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


    //上傳料件新增至資料庫
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
                    $price =  $Alldata[3][$i];
                    $money =   $Alldata[4][$i];
                    $unit =   $Alldata[5][$i];
                    $mpq =   $Alldata[6][$i];
                    $moq =   $Alldata[7][$i];
                    $lt =   $Alldata[8][$i];
                    $month =   $Alldata[9][$i];
                    $gradea =   $Alldata[10][$i];
                    $belong =   $Alldata[11][$i];
                    $send =   $Alldata[12][$i];
                    $safe =   $Alldata[13][$i];

                    $test = DB::table('consumptive_material')->where('料號', $number)->value('品名');
                    /*$delete = ConsumptiveMaterial::onlyTrashed()
                        ->where('料號', $number)->get();*/

                    if ($gradea === 'Yes') $gradea = '是';
                    if ($gradea === 'No') $gradea = '否';

                    if ($month === 'Yes') $month = '是';
                    if ($month === 'No') $month = '否';

                    // if ($belong === 'Unit Cons.' || $belong === '单耗') $belong = '單耗';
                    // if ($belong === 'Station') $belong = '站位';

                    if ($safe === '') $safe = null;

                    if ($month === "否" && $safe === null) {
                        $row = $i + 1;
                        return \Response::json(['message' => $row], 422/* Status code here default is 200 ok*/);
                    }

                    // $in = iconv('UTF-8//IGNORE', 'GB2312//IGNORE', $name);

                    // $in = iconv('GB2312//IGNORE', 'BIG5//IGNORE', $in);

                    // $out = iconv('BIG5//IGNORE', 'UTF-8//IGNORE', $in);

                    // $in1 = iconv('UTF-8//IGNORE', 'GB2312//IGNORE', $format);

                    // $in1 = iconv('GB2312//IGNORE', 'BIG5//IGNORE', $in1);

                    // $out1 = iconv('BIG5//IGNORE', 'UTF-8//IGNORE', $in1);

                    if ($test === null) {
                        DB::table('consumptive_material')
                            ->insert([
                                '料號' => $number, '品名' => $name, '規格' => $format, '單價' => $price, '幣別' => $money, '單位' => $unit, 'MPQ' => $mpq, 'MOQ' => $moq, 'LT' => $lt, '月請購' => $month, 'A級資材' => $gradea, '耗材歸屬' => $belong, '發料部門' => $send, '安全庫存' => $safe
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
            $dataarray =  $request->input('data');
            $count = count($dataarray);
            $choose = $request->input('title');
            $row = $request->input('row');
            $record = 0;
            $check = array();
            $test  = [];


            if ($choose == '客戶別') {
                $chooseindex = '客戶';
                // $table = "App\Models\客戶別";
            } else if ($choose == '儲位') {
                $chooseindex = '儲存位置';
                // $table = "App\Models\儲位";
            } else if ($choose == '製程') {
                $chooseindex = '制程';
                // $table = "App\Models\製程";
            } else {
                $chooseindex = $choose;
                // $table = "App\Models" . "\\" . $choose;
            }

            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $data = $dataarray[$i];
                    $testa = DB::table($choose)->where($chooseindex, $data)->value($chooseindex);

                    //判斷data是否重複
                    if ($testa === null) {
                        $test[$i] = 1;
                    } else {
                        $test[$i] = 2;
                    }
                    if ($test[$i] == 1) {
                        DB::table($choose)
                            ->insert([$chooseindex => $data]);
                        $record++;
                        array_push($check, $row[$i]);
                    } else {
                        continue;
                    }
                }
                DB::commit();
                return \Response::json(['record' => $record, 'choose' => $choose, 'check' => $check]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }
}
