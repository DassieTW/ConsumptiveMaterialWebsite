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

    public function index()
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
    }

    //基礎信息更新或刪除
    public function changeordelete(Request $request)
    {
        if (Session::has('username'))
        {
            $reDive = new responseObj();
            $now = Carbon::now();

            $count = count($request->input('data'));
            $names = $request->input('data');
            $oldnames = $request->input('olddata');
            $datanew = $request->input('datanew');
            $choose;
            $chooseindex;

            //factory
            if($request->input('dataname') == "factory")
            {
                $choose = "App\Models\廠別";
                $chooseindex = "廠別";
                $table = "廠別";
                $reDive->message = "廠別";
                $reDive->database = "FactoryExample";
            }
            //client
            else if($request->input('dataname') == "client")
            {
                $choose = "App\Models\客戶別";
                $chooseindex = '客戶';
                $table = "客戶別";
                $reDive->message = "客戶別";
                $reDive->database = "ClientExample";
            }
            //machine
            else if($request->input('dataname') == "machine")
            {
                $choose = "App\Models\機種";
                $chooseindex = '機種';
                $table = "機種";
                $reDive->message = "機種";
                $reDive->database = "MachineExample";
            }
            //production
            else if($request->input('dataname') == "production")
            {
                $choose = "App\Models\製程";
                $chooseindex = '製程';
                $table = "製程";
                $reDive->message = "製程";
                $reDive->database = "ProductionExample";
            }
            //line
            else if($request->input('dataname') == "line")
            {
                $choose = "App\Models\線別";
                $chooseindex = '線別';
                $table = "線別";
                $reDive->message = "線別";
                $reDive->database = "LineExample";
            }
            //use
            else if($request->input('dataname') == "use")
            {
                $choose = "App\Models\領用部門";
                $chooseindex = '領用部門';
                $table = "領用部門";
                $reDive->message = "領用部門";
                $reDive->database = "UseExample";
            }
            //usereason
            else if($request->input('dataname') == "usereason")
            {
                $choose = "App\Models\領用原因";
                $chooseindex = '領用原因';
                $table = "領用原因";
                $reDive->message = "領用原因";
                $reDive->database = "UseReasonExample";
            }
            //inreason
            else if($request->input('dataname') == "inreason")
            {
                $choose = "App\Models\入庫原因";
                $chooseindex = '入庫原因';
                $table = "入庫原因";
                $reDive->message = "入庫原因";
                $reDive->database = "InReasonExample";
            }
            //position
            else if($request->input('dataname') == "position")
            {
                $choose = "App\Models\儲位";
                $chooseindex = '儲存位置';
                $table = "儲位";
                $reDive->message = "儲位";
                $reDive->database = "PositionExample";
            }
            //send
            else if($request->input('dataname') == "send")
            {
                $choose = "App\Models\發料部門";
                $chooseindex = '發料部門';
                $table = "發料部門";
                $reDive->message = "發料部門";
                $reDive->database = "SendExample";
            }
            //o庫
            else if($request->input('dataname') == "o")
            {
                $choose = "App\Models\O庫";
                $chooseindex = 'O庫';
                $table = "O庫";
                $reDive->message = "O庫";
                $reDive->database = "OboundExample";
            }
            //退回原因
            else if($request->input('dataname') == "back")
            {
                $choose = "App\Models\退回原因";
                $chooseindex = '退回原因';
                $table = "退回原因";
                $reDive->message = "退回原因";
                $reDive->database = "BackReasonExample";
            }
            if($request->input('select') == "刪除")
            {
                for($i = 0 ; $i < $count ; $i++)
                {
                    DB::beginTransaction();
                    try{
                        $choose::where($chooseindex, $names[$i])->delete();
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollback();
                        $reDive->boolean = false;
                        $reDive->passbool = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                }

                $reDive->boolean = true;
                $reDive->passbool = true;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
            //change
            else if($request->input('select') == "更新")
            {
                for($i = 0 ; $i < $count ; $i++)
                {
                    DB::beginTransaction();
                    try{
                        DB::table($table)
                        ->where($chooseindex, $oldnames[$i])
                        ->update([$chooseindex => $names[$i] , 'updated_at' => Carbon::now()]);
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollback();
                        $reDive->boolean = false;
                        $reDive->passbool = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }
                }

                if($datanew !== null)
                {
                    $test = $choose::onlyTrashed()
                    ->where($chooseindex, $datanew)
                    ->get();

                    DB::beginTransaction();
                    try{
                        if(!$test->isEmpty())
                        {
                            DB::table($table)
                            ->where($chooseindex, $datanew)
                            ->update(['updated_at' =>  null , 'deleted_at' => null , 'created_at' => Carbon::now()]);
                        }
                        else
                        {
                            DB::table($table)
                            ->insert([$chooseindex => $datanew ,'created_at' => Carbon::now()]);
                        }
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollback();
                        $reDive->boolean = false;
                        $reDive->passbool = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    }

                }

                $reDive->boolean = true;
                $reDive->passbool = true;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //料件信息更新或刪除
    public function materialchangeordel(Request $request)
    {
        if (Session::has('username'))
        {
            //delete
            if($request->has('delete'))
            {
                $record = 0;
                $count = $request->input('count');
                for($i = 0 ; $i < $count ; $i++)
                {
                    if($request->has('innumber' . $i))
                    {
                        ConsumptiveMaterial::where('料號', $request->input('number' . $i))->delete();
                        /*DB::table('consumptive_material')
                        ->where('料號', $request->input('number' . $i))
                        ->delete();*/
                        $record ++;
                    }
                    else
                    {
                        continue;
                    }
                }
                $mess = trans('basicInfoLang.total').' '.$record.' '.trans('basicInfoLang.record').' '
                .trans('basicInfoLang.isn').' '.trans('basicInfoLang.delete').' '
                .trans('basicInfoLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/basic';
                </script>");
            }
            //change
            else if($request->has('change'))
            {
                $count = $request->input('count');
                $record = 0;
                for($i = 0 ; $i < $count ; $i++)
                {
                    if($request->has('innumber' . $i))
                    {
                        $gradea = $request->input('gradea' . $i);
                        $month = $request->input('month' . $i);
                        $send = $request->input('send' . $i);
                        $belong = $request->input('belong' . $i);
                        $price = $request->input('price' . $i);
                        $money = $request->input('money' . $i);
                        $unit = $request->input('unit' . $i);
                        $mpq = $request->input('mpq' . $i);
                        $moq = $request->input('moq' . $i);
                        $lt = $request->input('lt' . $i);
                        $safe = $request->input('safe' . $i);
                        $number = $request->input('number' . $i);

                        if($gradea === 'Yes') $gradea = '是';
                        if($gradea === 'No') $gradea = '否';
                        if($month === 'Yes') $month = '是';
                        if($month === 'No') $month = '否';
                        if($belong === 'Unit consumption' || $belong === '单耗') $belong = '單耗';
                        if($belong === 'Station') $belong = '站位';
                        if($send === 'Spare parts room' || $send === '备品室') $send = '備品室';
                        else if($send === 'ME Spare parts room' || $send === 'ME备品室') $send = 'ME備品室';
                        else if($send === 'IE Spare parts room' || $send === 'IE备品室') $send = 'IE備品室';
                        else if($send === 'Equip Spare parts room' || $send === '设备备品室') $send = '設備備品室';

                        $row = $i + 1;
                        if($month === '否' && $safe === null || $safe === '')
                        {
                            $mess = trans('basicInfoLang.row').' : '.$row.trans('basicInfoLang.isn').' '.$number.' '
                            .trans('basicInfoLang.notmonthsafe');
                            echo ("<script LANGUAGE='JavaScript'>
                            window.alert('$mess');
                            window.location.href = 'material';
                            </script>");

                        }
                        else
                        {

                            DB::beginTransaction();
                            try {
                                DB::table('consumptive_material')
                                    ->where('料號', $request->input('number' . $i))
                                    ->update(['A級資材' => $gradea , '月請購' => $month , '發料部門' => $send , '耗材歸屬' => $belong ,
                                '單價' => $price , '幣別' => $money , '單位' => $unit , 'MPQ' => $mpq ,
                                'MOQ' => $moq , 'LT' => $lt , '安全庫存' => $safe , 'updated_at' => Carbon::now()]);
                                $record++;
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
                    }
                    else
                    {
                        continue;
                    }
                }

                $mess = trans('basicInfoLang.total').' '.$record.' '.trans('basicInfoLang.record').' '
                .trans('basicInfoLang.isn').' '.trans('basicInfoLang.change').' '
                .trans('basicInfoLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/basic';
                </script>");

            }
            //download
            else if($request->has('download'))
            {

                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
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
                $filename = '料件信息'. $now . '.xlsx';
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');

                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');

            }

            else
            {
                return redirect(route('basic.material'));
            }
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
            return view('basic.searchmaterial');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

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
        if(Session::has('username'))
        {
            if($request->input('number') === null)
            {
                return view('basic.searchmaterialok')->with(['data' => ConsumptiveMaterial::cursor()])
                ->with(['data1' => ConsumptiveMaterial::cursor()]);
            }
            else if($request->input('number') !== null && strlen($request->input('number')) <= 12)
            {
                $input = $request->input('number');

                $datas = DB::table('consumptive_material')
                ->where('料號', 'like', $input.'%')
                ->get();

                return view("basic.searchmaterialok")
                ->with(['data' => $datas])
                ->with(['data1' => $datas]);

            }
            else
            {
                return back()->withErrors([

                    'number' => trans('basicInfoLang.isnlength'),
                ]);
            }
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
            for($i = 0 ; $i < count($numbers) ; $i ++)
            {
                if(strcasecmp($number,$numbers[$i]) === 0 )
                {
                    if(!$delete->isEmpty())
                    {
                        DB::table('consumptive_material')
                        ->where('料號', $number)
                        ->update(['品名' => $name , '規格' => $format , '單價' => $price , '幣別' => $money
                        , '單位' => $unit , 'MPQ' => $mpq , 'MOQ' => $moq ,'LT' => $lt , '月請購' => $month , 'A級資材' => $gradea
                        , '耗材歸屬' => $belong , '發料部門' => $send , '安全庫存' => $safe , 'updated_at' => Carbon::now(),'deleted_at' => null]);


                        $reDive->boolean = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                        return;
                    }
                    else
                    {
                        $reDive->newerror[0] = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                        return;
                        /*return back()->withErrors([
                        'number' => '料號 is repeated , Please enter another 料號',
                        ]);*/
                    }
                }
                else
                {
                    continue;
                }
            }

            if($request->input('number') !== null && $request->input('name') !== null)
            {
                //長度是否為12
                if(strlen($request->input('number')) !== 12)
                {

                    $reDive->newerror[1] = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                    /*return back()->withErrors([
                        'number' => '料號長度不為12 , Please enter again',
                        ]);*/
                }

                //check 非月請購是否有填安全庫存
                if($request->input('month') === '否' && $request->input('safe') === null)
                {
                    $reDive->newerror[2] = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                    /*return back()->withErrors([
                        'safe' => '非月請購之安全庫存為必填項目',
                    ]);*/
                }

                DB::table('consumptive_material')
                ->insert(['料號' => $number , '品名' => $name , '規格' => $format , '單價' => $price , '幣別' => $money
                , '單位' => $unit , 'MPQ' => $mpq , 'MOQ' => $moq ,'LT' => $lt , '月請購' => $month , 'A級資材' => $gradea
                , '耗材歸屬' => $belong , '發料部門' => $send , '安全庫存' => $safe , 'created_at' => Carbon::now()]);

                $reDive->boolean = true;
                $myJSON = json_encode($reDive);
                echo $myJSON;
                return;
                //return view('basic.newok');
            }
            else
            {
                return view('basic.new')->with(['data' => 發料部門::cursor()]);
            }
        }
        else
        {
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

    //新增料件上傳
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
            return view('basic.newupload')->with(['data' => $sheetData])->with(['senddata' => 發料部門::cursor()]);;
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //新增料件上傳頁面
    public function uploadmaterialpage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('basic.newupload1');
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
            $test = 0;
            for($i = 0 ; $i < $count ; $i ++)
            {
                if($request->input('data0a'.$i) !== null && $request->input('data1a'.$i) !== null)
                {
                    $number =  $request->input('data0a'. $i);
                    $name =  $request->input('data1a'. $i);
                    $format =  $request->input('data2a'. $i);
                    $price =  $request->input('data3a'. $i);
                    $money =  $request->input('data4a'. $i);
                    $unit =  $request->input('data5a'. $i);
                    $mpq =  $request->input('data6a'. $i);
                    $moq =  $request->input('data7a'. $i);
                    $lt =  $request->input('data8a'. $i);
                    $month =  $request->input('data9a'. $i);
                    $gradea =  $request->input('data10a'. $i);
                    $belong =  $request->input('data11a'. $i);
                    $send =  $request->input('data12a'. $i);
                    $safe =  $request->input('data13a'. $i);

                    if($gradea === 'Yes') $gradea = '是';
                    if($gradea === 'No') $gradea = '否';
                    if($month === 'Yes') $month = '是';
                    if($month === 'No') $month = '否';
                    if($belong === 'Unit consumption' || $belong === '单耗') $belong = '單耗';
                    if($belong === 'Station') $belong = '站位';
                    if($send === 'Spare parts room' || $send === '备品室') $send = '備品室';
                    else if($send === 'ME Spare parts room' || $send === 'ME备品室') $send = 'ME備品室';
                    else if($send === 'IE Spare parts room' || $send === 'IE备品室') $send = 'IE備品室';
                    else if($send === 'Equip Spare parts room' || $send === '设备备品室') $send = '設備備品室';

                    $numbers = DB::table('consumptive_material')->pluck('料號');
                    //判斷料號是否重複
                    for($j = 0 ; $j < count($numbers) ; $j ++)
                    {
                        if(strcasecmp($number,$numbers[$j]) === 0)
                        {
                            $row = $i + 1;

                            $mess = trans('basicInfoLang.row').' : '.$row.' '.trans('basicInfoLang.isnrepeat');
                            echo ("<script LANGUAGE='JavaScript'>
                            window.alert('$mess');
                            window.location.href = 'upload';
                            </script>");
                            /*return back()->withErrors([
                            'number' => '料號 is repeated , Please enter another 料號',
                            ]);*/
                        }
                        else
                        {
                            continue;
                        }
                    }

                    //長度是否為12
                    if(strlen($request->input('data0a'.$i)) !== 12)
                    {
                        $row = $i + 1;
                        $mess = trans('basicInfoLang.row').' : '.$row.' '.trans('basicInfoLang.isnlength');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        window.location.href = 'upload';
                        </script>");
                        /*return back()->withErrors([
                            'number' => '料號長度不為12 , Please enter again',
                            ]);*/
                    }
                    else
                    {
                        //check 非月請購是否有填安全庫存
                        if($month === '否' && $request->input('data13a'.$i) === null)
                        {
                            $row = $i + 1 ;
                            $mess = trans('basicInfoLang.row').' : '.$row.' '.trans('basicInfoLang.safeerror');
                            echo ("<script LANGUAGE='JavaScript'>
                            window.alert('$mess');
                            window.location.href = 'upload';
                            </script>");
                            /*return back()->withErrors([
                                'safe' => '非月請購之安全庫存為必填項目',
                            ]);*/
                        }
                        else
                        {
                            $test ++;
                        }
                        if($test == $count)
                        {
                            for($i = 0 ; $i < $count ; $i ++)
                            {
                                $number =  $request->input('data0a'. $i);
                                $name =  $request->input('data1a'. $i);
                                $format =  $request->input('data2a'. $i);
                                $price =  $request->input('data3a'. $i);
                                $money =  $request->input('data4a'. $i);
                                $unit =  $request->input('data5a'. $i);
                                $mpq =  $request->input('data6a'. $i);
                                $moq =  $request->input('data7a'. $i);
                                $lt =  $request->input('data8a'. $i);
                                $month =  $request->input('data9a'. $i);
                                $gradea =  $request->input('data10a'. $i);
                                $belong =  $request->input('data11a'. $i);
                                $send =  $request->input('data12a'. $i);
                                $safe =  $request->input('data13a'. $i);

                                DB::beginTransaction();
                                try {
                                    DB::table('consumptive_material')
                                        ->insert(['料號' => $number , '品名' => $name , '規格' => $format ,'單價' => $price , '幣別' => $money , '單位' => $unit
                                        , 'MPQ' => $mpq , 'MOQ' => $moq , 'LT' => $lt , '月請購' => $month , 'A級資材' => $gradea , '耗材歸屬' => $belong , '發料部門' => $send
                                        , '安全庫存' => $safe ,'created_at' => Carbon::now()]);
                                    DB::commit();
                                    $record++;
                                }catch (\Exception $e) {
                                    DB::rollback();

                                    $mess = $e->getMessage();
                                    dd($mess);
                                    echo ("<script LANGUAGE='JavaScript'>
                                    window.alert('$mess');
                                    window.location.href='/basic';
                                    </script>");
                                }
                            }
                        }
                    }
                }
                else
                {
                    continue;
                }

            }
            $mess = trans('basicInfoLang.total').' '.$record.trans('basicInfoLang.record').' '
            .trans('basicInfoLang.newMats').' '.trans('basicInfoLang.success');
            echo("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href = '/basic';
            </script>");

        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //基礎資料上傳
    public function uploadbasic(Request $request)
    {
        if (Session::has('username'))
        {
            $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $choose = $sheetData[0][0];


            if($choose !== "廠別" && $choose !== "客戶別" && $choose !== "機種"  && $choose !== "製程" && $choose !== "線別" && $choose !== "領用部門"
            && $choose !== "領用原因" && $choose !== "入庫原因" && $choose !== "儲位" && $choose !== "發料部門" && $choose !== "O庫" && $choose !== "退回原因")
            {
                $mess = trans('basicInfoLang.uploaderror');
                    echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    </script>");
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
                unset($sheetData[0]);
                return view('basic.uploadbasic')->with(['data' => $sheetData])->with('choose' , $choose);
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //基礎信息批量新增至資料庫
    public function insertuploadbasic(Request $request)
    {
        if (Session::has('username'))
        {
            $count = $request->input('count');
            $choose = $request->input('title0');
            $record = 0;
            for($i = 0 ; $i < $count ; $i ++)
            {
                $data =  $request->input('data0'. $i);
                if($choose === '客戶別')
                {
                    DB::beginTransaction();
                    try {
                        DB::table('客戶別')
                        ->insert(['客戶' => $data , 'created_at' => Carbon::now()]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $i ++ ;
                        $mess = trans('basicInfoLang.row').' : '.$i.' '.trans('basicInfoLang.repeat');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('basic.index');
                    }
                }
                else if($choose === '儲位')
                {
                    DB::beginTransaction();
                    try {
                        DB::table('儲位')
                        ->insert(['儲存位置' => $data , 'created_at' => Carbon::now()]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $i ++ ;
                        $mess = trans('basicInfoLang.row').' : '.$i.' '.trans('basicInfoLang.repeat');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('basic.index');
                    }
                }
                else
                {
                    DB::beginTransaction();
                    try {
                        DB::table($choose)
                        ->insert([$choose => $data , 'created_at' => Carbon::now()]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $i ++ ;
                        $mess = trans('basicInfoLang.row').' : '.$i.' '.trans('basicInfoLang.repeat');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('basic.index');
                    }
                }



            }
            $mess = trans('basicInfoLang.total').' '.$record.trans('basicInfoLang.record').' '
            .$choose.' '.trans('basicInfoLang.new').' '.trans('basicInfoLang.success');
            echo("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href = '/basic';
            </script>");

        }
        else
        {
            return redirect(route('member.login'));
        }
    }
}
