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
use DB;
use Session;
use Route;
use Carbon;

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
            ->with(['sends' => 發料部門::cursor()]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //change or delete update
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
                    return view('basic.change')->with('choose' , 'inreason')
                    ->with(['inreasons' => 入庫原因::cursor()]);
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
                    return view('basic.change')->with('choose' , 'inreason')
                    ->with(['inreasons' => 入庫原因::cursor()]);
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

    //search material number
    public function searchmaterial(Request $request)
    {
        if(Session::has('username'))
        {
            if($request->input('number') !== null)
            {

                $number = $request->input('number');
                $name = DB::table('consumptive_material')->where('料號', $number)->value('品名');
                $format = DB::table('consumptive_material')->where('料號', $number)->value('規格');
                $gp = DB::table('consumptive_material')->where('料號', $number)->value('GP料件');
                $gradea = DB::table('consumptive_material')->where('料號', $number)->value('A級資材');
                $month = DB::table('consumptive_material')->where('料號', $number)->value('月請購');
                $send = DB::table('consumptive_material')->where('料號', $number)->value('發料部門');
                $belong = DB::table('consumptive_material')->where('料號', $number)->value('耗材歸屬');
                $price = DB::table('consumptive_material')->where('料號', $number)->value('單價');
                $money = DB::table('consumptive_material')->where('料號', $number)->value('幣別');
                $unit = DB::table('consumptive_material')->where('料號', $number)->value('單位');
                $mpq = DB::table('consumptive_material')->where('料號', $number)->value('MPQ');
                $moq = DB::table('consumptive_material')->where('料號', $number)->value('MOQ');
                $lt = DB::table('consumptive_material')->where('料號', $number)->value('LT');
                $safe = DB::table('consumptive_material')->where('料號', $number)->value('安全庫存');
                if($name !== NULL && $format !==NULL)
                {
                    return view('basic.modify')
                    ->with('number' , $number)
                    ->with('name', $name)
                    ->with('format', $format)
                    ->with('gp' , $gp)
                    ->with('gradea', $gradea)
                    ->with('month', $month)
                    ->with('send' , $send)
                    ->with('belong', $belong)
                    ->with('price', $price)
                    ->with('money' , $money)
                    ->with('unit', $unit)
                    ->with('mpq', $mpq)
                    ->with('moq' , $moq)
                    ->with('lt', $lt)
                    ->with('safe', $safe);
                }
                else
                {
                    return back()->withErrors([
                        'number' => 'Material number is not exist , Please enter another material number',
                        ]);
                }
            }
            else
            {
                return view('basic.searchmaterial');

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //search material position
    public function searchposition(Request $request)
    {
        if(Session::has('username'))
        {
            if($request->input('position') !== null)
            {
                $input = $request->input('position');
                $position = DB::table('inventory')->where('儲位', $input)->value('儲位');
                $material = DB::table('inventory')->where('儲位', $input)->value('料號');
                $stock = DB::table('inventory')->where('儲位', $input)->value('現有庫存');
                if($position !== NULL)
                {
                    return view('basic.searchok')
                    ->with('position' , $position)
                    ->with('material' , $material)
                    ->with('stock' , $stock);
                }
                else
                {
                    return back()->withErrors([
                        'position' => 'Position number is not exist , Please enter another position number',
                        ]);

                }
            }
            else
            {
                return view('basic.searchposition');

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //new material inf
    public function new(Request $request)
    {
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
            $gp = $request->input('gp');
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
                    return back()->withErrors([
                    'number' => '料號 is repeated , Please enter another 料號',
                    ]);
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
                    return back()->withErrors([
                        'number' => '料號長度不為12 , Please enter again',
                        ]);
                }
                //check 是否有選幣別
                if($request->input('money') === "選擇幣別")
                {
                    return back()->withErrors([
                        'money' => '請選擇幣別',
                        ]);
                }
                //check 是否有選A級資材
                if($request->input('gradea') === "是/否")
                {
                    return back()->withErrors([
                        'gradea' => '請選擇是否是A級資材',
                        ]);
                }
                //check 是否有選GP料件
                if($request->input('gp') === "是/否")
                {
                    return back()->withErrors([
                        'gp' => '請選擇是否是GP料件',
                        ]);
                }
                //check 是否有選耗材歸屬
                if($request->input('belong') === "選擇耗材歸屬")
                {
                    return back()->withErrors([
                        'belong' => '請選擇耗材歸屬',
                        ]);
                }
                //check 是否有選發料部門
                if($request->input('send') === "選擇發料部門")
                {
                    return back()->withErrors([
                        'send' => '請選擇發料部門',
                        ]);
                }
                //check 非月請購是否有填安全庫存
                if($request->input('month') === '否' && $request->input('safe') === null)
                {
                    return back()->withErrors([
                        'safe' => '非月請購之安全庫存為必填項目',
                    ]);
                }
                //是月請購有安全庫存
                else if($request->input('month') === '是' && $request->input('safe') !== null)
                {
                    DB::insert('insert into consumptive_material (料號, 品名 , 規格 , 單價 , 幣別 , 單位, MPQ , MOQ , LT , 月請購 ,
                    A級資材, GP料件 , 耗材歸屬 , 發料部門 , 安全庫存)
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$number, $name , $format , $price , $money , $unit, $mpq , $moq , $lt , $month ,$gradea, $gp , $belong , $send , $safe]);
                    return view('basic.newok');
                }
                //是月請購無安全庫存
                else if($request->input('month') === '是' && $request->input('safe') === null)
                {
                    DB::insert('insert into consumptive_material (料號, 品名 , 規格 , 單價 , 幣別 , 單位, MPQ , MOQ , LT , 月請購 ,
                    A級資材, GP料件 , 耗材歸屬 , 發料部門)
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$number, $name , $format , $price , $money , $unit, $mpq , $moq , $lt , $month ,$gradea, $gp , $belong , $send]);
                    return view('basic.newok');
                }
                //非月請購有安全庫存
                else
                {
                    DB::insert('insert into consumptive_material (料號, 品名 , 規格 , 單價 , 幣別 , 單位, MPQ , MOQ , LT , 月請購 ,
                    A級資材, GP料件 , 耗材歸屬 , 發料部門 , 安全庫存)
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$number, $name , $format , $price , $money , $unit, $mpq , $moq , $lt , $month ,$gradea, $gp , $belong , $send ,$safe]);
                    return view('basic.newok');
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

    //modify material inf
    public function modify(Request $request)
    {
        if (Session::has('username'))
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
            $gp = $request->input('gp');
            $belong = $request->input('belong');
            $month = $request->input('month');
            $send = $request->input('send');
            $safe = $request->input('safe');
            if($month === '否' && $safe === null)
            {
                return back()->withErrors([
                    'safe' => '非月請購之安全庫存為必填項目',
                ]);
            }
            DB::table('consumptive_material')
            ->where('料號', $number)
            ->update(['料號' => $number , '品名' => $name , '規格' => $format , '單價' => $price ,
            '幣別' => $money , '單位' => $unit , 'MPQ' => $mpq , 'MOQ' => $moq ,
            'LT' => $lt , 'A級資材' => $gradea , 'GP料件' => $gp , '耗材歸屬' => $belong ,
            '月請購' => $month , '發料部門' => $send , '安全庫存' => $safe]);
            return view('basic.modifyok');


        }

        else
        {
            return redirect(route('member.login'));
        }

    }
}
