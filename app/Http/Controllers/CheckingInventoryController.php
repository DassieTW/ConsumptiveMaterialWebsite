<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checking_inventory;
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
class CheckingInventoryController extends Controller
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
            //delete check items
            if($request->has('delete'))
            {
                //factory
                if($request->has('factory'))
                {
                    $count = DB::table('廠別')->count();
                    $names = DB::table('廠別')->pluck('廠別');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('factorycheck' . $i))
                        {
                            DB::table('廠別')
                            ->where('廠別', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'factory')
                    ->with(['factorys' => 廠別::cursor()]);
                }
                //client
                else if($request->has('client'))
                {
                    $count = DB::table('客戶別')->count();
                    $names = DB::table('客戶別')->pluck('客戶');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('clientcheck' . $i))
                        {
                            DB::table('客戶別')
                            ->where('客戶', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'client')
                    ->with(['clients' => 客戶別::cursor()]);
                }
                //machine
                else if($request->has('machine'))
                {
                    $count = DB::table('機種')->count();
                    $names = DB::table('機種')->pluck('機種');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('machinecheck' . $i))
                        {
                            DB::table('機種')
                            ->where('機種', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'machine')
                    ->with(['machines' => 機種::cursor()]);
                }
                //production
                else if($request->has('production'))
                {
                    $count = DB::table('製程')->count();
                    $names = DB::table('製程')->pluck('製程');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('productioncheck' . $i))
                        {
                            DB::table('製程')
                            ->where('製程', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'production')
                    ->with(['productions' => 製程::cursor()]);
                }
                //line
                else if($request->has('line'))
                {
                    $count = DB::table('線別')->count();
                    $names = DB::table('線別')->pluck('線別');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('linecheck' . $i))
                        {
                            DB::table('線別')
                            ->where('線別', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'line')
                    ->with(['lines' => 線別::cursor()]);
                }
                //use
                else if($request->has('use'))
                {
                    $count = DB::table('領用部門')->count();
                    $names = DB::table('領用部門')->pluck('領用部門');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('usecheck' . $i))
                        {
                            DB::table('領用部門')
                            ->where('領用部門', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'use')
                    ->with(['uses' => 領用部門::cursor()]);
                }
                //usereason
                else if($request->has('usereason'))
                {
                    $count = DB::table('領用原因')->count();
                    $names = DB::table('領用原因')->pluck('領用原因');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('factorycheck' . $i))
                        {
                            DB::table('領用原因')
                            ->where('領用原因', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'usereason')
                    ->with(['usereasons' => 領用原因::cursor()]);
                }
                //inreason
                else if($request->has('inreason'))
                {
                    $count = DB::table('入庫原因')->count();
                    $names = DB::table('入庫原因')->pluck('入庫原因');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('inreasoncheck' . $i))
                        {
                            DB::table('入庫原因')
                            ->where('入庫原因', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'inreason');
                }
                //position
                else if($request->has('position'))
                {
                    $count = DB::table('儲位')->count();
                    $names = DB::table('儲位')->pluck('儲存位置');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('positioncheck' . $i))
                        {
                            DB::table('儲位')
                            ->where('儲存位置', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'position')
                    ->with(['positions' => 儲位::cursor()]);
                }
                //send
                else if($request->has('send'))
                {
                    $count = DB::table('發料部門')->count();
                    $names = DB::table('發料部門')->pluck('發料部門');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('sendcheck' . $i))
                        {
                            DB::table('發料部門')
                            ->where('發料部門', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'send')
                    ->with(['sends' => 發料部門::cursor()]);
                }
                //o庫
                else if($request->has('o'))
                {
                    $count = DB::table('O庫')->count();
                    $names = DB::table('O庫')->pluck('O庫');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('ocheck' . $i))
                        {
                            DB::table('O庫')
                            ->where('O庫', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'o')
                    ->with(['os' => O庫::cursor()]);
                }
                //退回原因
                else if($request->has('o'))
                {
                    $count = DB::table('退回原因')->count();
                    $names = DB::table('退回原因')->pluck('退回原因');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('backcheck' . $i))
                        {
                            DB::table('退回原因')
                            ->where('退回原因', $names[$i])
                            ->delete();
                        }
                        else
                        {
                            continue;
                        }
                    }
                    return view('basic.change')->with('choose' , 'back')
                    ->with(['backs' => 退回原因::cursor()]);
                }
            }
            //change
            else if($request->has('change'))
            {
                //factory
                if($request->has('factory'))
                {
                    $count = DB::table('廠別')->count();
                    $names = DB::table('廠別')->pluck('廠別');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('廠別')
                        ->where('廠別', $names[$i])
                        ->update(['廠別' => $request->input('factory' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'factory')
                    ->with(['factorys' => 廠別::cursor()]);
                }
                //client
                else if($request->has('client'))
                {
                    $count = DB::table('客戶別')->count();
                    $names = DB::table('客戶別')->pluck('客戶');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('客戶別')
                        ->where('客戶', $names[$i])
                        ->update(['客戶' => $request->input('client' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'client')
                    ->with(['clients' => 客戶別::cursor()]);
                }
                //machine
                else if($request->has('machine'))
                {
                    $count = DB::table('機種')->count();
                    $names = DB::table('機種')->pluck('機種');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('機種')
                        ->where('機種', $names[$i])
                        ->update(['機種' => $request->input('machine' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'machine')
                    ->with(['machines' => 機種::cursor()]);
                }
                //production
                else if($request->has('production'))
                {
                    $count = DB::table('製程')->count();
                    $names = DB::table('製程')->pluck('製程');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('製程')
                        ->where('製程', $names[$i])
                        ->update(['製程' => $request->input('production' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'production')
                    ->with(['productions' => 製程::cursor()]);
                }
                //line
                else if($request->has('line'))
                {
                    $count = DB::table('線別')->count();
                    $names = DB::table('線別')->pluck('線別');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('線別')
                        ->where('線別', $names[$i])
                        ->update(['線別' => $request->input('line' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'line')
                    ->with(['lines' => 線別::cursor()]);
                }
                //use
                else if($request->has('use'))
                {
                    $count = DB::table('領用部門')->count();
                    $names = DB::table('領用部門')->pluck('領用部門');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('領用部門')
                        ->where('領用部門', $names[$i])
                        ->update(['領用部門' => $request->input('use' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'use')
                    ->with(['uses' => 領用部門::cursor()]);
                }
                //usereason
                else if($request->has('usereason'))
                {
                    $count = DB::table('領用原因')->count();
                    $names = DB::table('領用原因')->pluck('領用原因');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('領用原因')
                        ->where('領用原因', $names[$i])
                        ->update(['領用原因' => $request->input('usereason' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'usereason')
                    ->with(['usereasons' => 領用原因::cursor()]);
                }
                //inreason
                else if($request->has('inreason'))
                {
                    $count = DB::table('入庫原因')->count();
                    $names = DB::table('入庫原因')->pluck('入庫原因');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('入庫原因')
                        ->where('入庫原因', $names[$i])
                        ->update(['入庫原因' => $request->input('inreason' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'inreason');
                }
                //position
                else if($request->has('position'))
                {
                    $count = DB::table('儲位')->count();
                    $names = DB::table('儲位')->pluck('儲存位置');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('儲位')
                        ->where('儲存位置', $names[$i])
                        ->update(['儲存位置' => $request->input('position' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'position')
                    ->with(['positions' => 儲位::cursor()]);
                }
                //send
                else if($request->has('send'))
                {
                    $count = DB::table('發料部門')->count();
                    $names = DB::table('發料部門')->pluck('發料部門');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('發料部門')
                        ->where('發料部門', $names[$i])
                        ->update(['發料部門' => $request->input('send' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'send')
                    ->with(['sends' => 發料部門::cursor()]);
                }
                //O庫
                else if($request->has('o'))
                {
                    $count = DB::table('O庫')->count();
                    $names = DB::table('O庫')->pluck('O庫');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('O庫')
                        ->where('O庫', $names[$i])
                        ->update(['O庫' => $request->input('o' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'o')
                    ->with(['os' => O庫::cursor()]);
                }
                //退回原因
                else if($request->has('back'))
                {
                    $count = DB::table('退回原因')->count();
                    $names = DB::table('退回原因')->pluck('退回原因');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        DB::table('退回原因')
                        ->where('退回原因', $names[$i])
                        ->update(['退回原因' => $request->input('back' . $i)]);
                    }
                    return view('basic.change')->with('choose' , 'back')
                    ->with(['backs' => 退回原因::cursor()]);
                }
            }
            else
            {
                return redirect(route('basic.index'));
            }
        }
        else
        {
            return redirect(route('basic.index'));
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

                $count = $request->input('count');
                for($i = 0 ; $i < $count ; $i++)
                {
                    if($request->has('innumber' . $i))
                    {
                        DB::table('consumptive_material')
                        ->where('料號', $request->input('number' . $i))
                        ->delete();
                    }
                    else
                    {
                        continue;
                    }
                }
                return view('basic.deleteok');
            }
            //change
            else if($request->has('change'))
            {
                $count = $request->input('count');
                for($i = 0 ; $i < $count ; $i++)
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
                    DB::table('consumptive_material')
                        ->where('料號', $request->input('number' . $i))
                        ->update(['A級資材' => $gradea , '月請購' => $month , '發料部門' => $send , '耗材歸屬' => $belong ,
                        '單價' => $price , '幣別' => $money , '單位' => $unit , 'MPQ' => $mpq ,
                        'MOQ' => $moq , 'LT' => $lt , '安全庫存' => $safe]);
                }
                return view('basic.changeok');
            }
            //download
            else if($request->has('download'))
            {

                $spreadsheet = new Spreadsheet();
                foreach (range('A','T') as $col) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                }
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
                    'number' => '料號長度大於12 , Please enter again',
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
            //判斷料號是否重複
            for($i = 0 ; $i < count($numbers) ; $i ++)
            {
                if($number == $numbers[$i])
                {
                    $reDive->newerror[0] = true;
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
                if($request->input('month') === '否' && $request->input('safe') === "zero")
                {
                    $reDive->newerror[2] = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                    /*return back()->withErrors([
                        'safe' => '非月請購之安全庫存為必填項目',
                    ]);*/
                }
                //是月請購有安全庫存
                if($request->input('month') === '是' && $request->input('safe') !== "zero")
                {
                    DB::insert('insert into consumptive_material (料號, 品名 , 規格 , 單價 , 幣別 , 單位, MPQ , MOQ , LT , 月請購 ,
                    A級資材 , 耗材歸屬 , 發料部門 , 安全庫存)
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$number, $name , $format , $price , $money , $unit, $mpq , $moq , $lt , $month ,$gradea , $belong , $send , $safe]);
                    $reDive->boolean = true;
                    $myJSON = json_encode($reDive);
                    Session::put('newmaterialok' , $number);
                    echo $myJSON;
                    return;
                    //return view('basic.newok');
                }
                //是月請購無安全庫存
                if($request->input('month') === '是' && $request->input('safe') === "zero")
                {
                    DB::insert('insert into consumptive_material (料號, 品名 , 規格 , 單價 , 幣別 , 單位, MPQ , MOQ , LT , 月請購 ,
                    A級資材, 耗材歸屬 , 發料部門)
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$number, $name , $format , $price , $money , $unit, $mpq , $moq , $lt , $month ,$gradea , $belong , $send]);
                    $reDive->boolean = true;
                    $myJSON = json_encode($reDive);
                    Session::put('newmaterialok' , $number);
                    echo $myJSON;
                    return;
                    //return view('basic.newok');
                }
                //非月請購有安全庫存
                if($request->input('month') === '否' && $request->input('safe') !== "zero")
                {
                    DB::insert('insert into consumptive_material (料號, 品名 , 規格 , 單價 , 幣別 , 單位, MPQ , MOQ , LT , 月請購 ,
                    A級資材 , 耗材歸屬 , 發料部門 , 安全庫存)
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$number, $name , $format , $price , $money , $unit, $mpq , $moq , $lt , $month ,$gradea , $belong , $send ,$safe]);
                    $reDive->boolean = true;
                    $myJSON = json_encode($reDive);
                    Session::put('newmaterialok' , $number);
                    echo $myJSON;
                    return;
                    //return view('basic.newok');
                }
            }
            else
            {
                return view('basic.new');
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }


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
    }

    public function download(Request $request)
    {
        if (Session::has('username'))
        {

            $spreadsheet = new Spreadsheet();
            foreach (range('A','T') as $col) {
                $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            }
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


} // end of CheckingInventoryController class
