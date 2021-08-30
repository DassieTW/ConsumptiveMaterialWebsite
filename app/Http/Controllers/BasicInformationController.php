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
<<<<<<< HEAD
use DB;
use Session;
use Route;
use Carbon;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;


=======
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
>>>>>>> 0827tony
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
<<<<<<< HEAD
            ->with(['sends' => 發料部門::cursor()]);
=======
            ->with(['sends' => 發料部門::cursor()])
            ->with(['os' => O庫::cursor()])
            ->with(['backs' => 退回原因::cursor()]);
>>>>>>> 0827tony
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

<<<<<<< HEAD
    //change or delete update
=======
    //基礎信息更新或刪除
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
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
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('factorynew') !== null)
                    {
                        DB::table('廠別')
                        ->insert(['廠別' => $request->input('factorynew')]);
                    }

>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('clientnew') !== null)
                    {
                        DB::table('客戶別')
                        ->insert(['客戶' => $request->input('clientnew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('machinenew') !== null)
                    {
                        DB::table('機種')
                        ->insert(['機種' => $request->input('machinenew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('productionnew') !== null)
                    {
                        DB::table('製程')
                        ->insert(['製程' => $request->input('productionnew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('linenew') !== null)
                    {
                        DB::table('線別')
                        ->insert(['線別' => $request->input('linenew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('usenew') !== null)
                    {
                        DB::table('領用部門')
                        ->insert(['領用部門' => $request->input('usenew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('usereasonnew') !== null)
                    {
                        DB::table('領用原因')
                        ->insert(['領用原因' => $request->input('usereasonnew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('inreasonnew') !== null)
                    {
                        DB::table('入庫原因')
                        ->insert(['入庫原因' => $request->input('inreasonnew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
=======
                    if($request->input('positionnew') !== null)
                    {
                        DB::table('儲位')
                        ->insert(['儲存位置' => $request->input('positionnew')]);
                    }
>>>>>>> 0827tony
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
<<<<<<< HEAD
                    return view('basic.change')->with('choose' , 'send')
                    ->with(['sends' => 發料部門::cursor()]);
                }
            }
=======
                    if($request->input('sendnew') !== null)
                    {
                        DB::table('發料部門')
                        ->insert(['發料部門' => $request->input('sendnew')]);
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
                    if($request->input('onew') !== null)
                    {
                        DB::table('O庫')
                        ->insert(['O庫' => $request->input('onew')]);
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
                    if($request->input('backnew') !== null)
                    {
                        DB::table('退回原因')
                        ->insert(['退回原因' => $request->input('backnew')]);
                    }
                    return view('basic.change')->with('choose' , 'back')
                    ->with(['backs' => 退回原因::cursor()]);
                }


            }

>>>>>>> 0827tony
            else
            {
                return redirect(route('basic.index'));
            }
        }
        else
        {
<<<<<<< HEAD
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

=======
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
                        DB::table('consumptive_material')
                        ->where('料號', $request->input('number' . $i))
                        ->delete();
                        $record ++;
                    }
                    else
                    {
                        continue;
                    }
                }
                $mess = trans('basicInfoLang.total').$record.trans('basicInfoLang.record')
                .trans('basicInfoLang.isn').trans('basicInfoLang.delete')
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
                    $number = $request->input('number' . $i);
                    $row = $i + 1;
                    if($month === '否' && $safe === null || $safe === '')
                    {
                        $mess = trans('basicInfoLang.row').$row.trans('basicInfoLang.isn').$number
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
                                'MOQ' => $moq , 'LT' => $lt , '安全庫存' => $safe]);
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

                $mess = trans('basicInfoLang.update').trans('basicInfoLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    window.location.href = '/basic';
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
>>>>>>> 0827tony
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
<<<<<<< HEAD
    }

    //search material position
=======

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
>>>>>>> 0827tony
    public function searchposition(Request $request)
    {
        if(Session::has('username'))
        {
<<<<<<< HEAD
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

=======
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
>>>>>>> 0827tony
            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

<<<<<<< HEAD
    //new material inf
    public function new(Request $request)
    {
=======

    //新增料件
    public function new(Request $request)
    {
        $reDive = new responseObj();
>>>>>>> 0827tony
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
<<<<<<< HEAD
            $gp = $request->input('gp');
=======
>>>>>>> 0827tony
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
<<<<<<< HEAD
                    return back()->withErrors([
                    'number' => '料號 is repeated , Please enter another 料號',
                    ]);
=======
                    $reDive->newerror[0] = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                    /*return back()->withErrors([
                    'number' => '料號 is repeated , Please enter another 料號',
                    ]);*/
>>>>>>> 0827tony
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
<<<<<<< HEAD
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
=======

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

                DB::table('consumptive_material')
                ->insert(['料號' => $number , '品名' => $name , '規格' => $format , '單價' => $price , '幣別' => $money
                , '單位' => $unit , 'MPQ' => $mpq , 'MOQ' => $moq ,'LT' => $lt , '月請購' => $month , 'A級資材' => $gradea
                , '耗材歸屬' => $belong , '發料部門' => $send , '安全庫存' => $safe]);

                $reDive->boolean = true;
                $myJSON = json_encode($reDive);
                Session::put('newmaterialok' , $number);
                echo $myJSON;
                return;
                //return view('basic.newok');
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
            return view('basic.newupload')->with(['data' => $sheetData]);
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
                    $numbers = DB::table('consumptive_material')->pluck('料號');
                    //判斷料號是否重複
                    for($j = 0 ; $j < count($numbers) ; $j ++)
                    {
                        if($number == $numbers[$j])
                        {
                            $mess = trans('basicInfoLang.isnrepeat');
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

                        $mess = trans('basicInfoLang.isnlength');
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
                        if($request->input('data9a'.$i) === '否' && $request->input('data13a'.$i) === null)
                        {
                            $mess = trans('basicInfoLang.notmonthsafe');
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
                            DB::beginTransaction();
                            try {
                                DB::table('consumptive_material')
                                    ->insert(['料號' => $number , '品名' => $name , '規格' => $format ,'單價' => $price , '幣別' => $money , '單位' => $unit
                                    , 'MPQ' => $mpq , 'MOQ' => $moq , 'LT' => $lt , '月請購' => $month , 'A級資材' => $gradea , '耗材歸屬' => $belong , '發料部門' => $send
                                    , '安全庫存' => $safe ]);
                                DB::commit();
                                $record++;
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
            $mess = trans('basicInfoLang.total').$record.trans('basicInfoLang.record')
            .trans('basicInfoLang.isn').trans('basicInfoLang.new')
            .trans('basicInfoLang.success');
            echo("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href = '/basic';
            </script>");

>>>>>>> 0827tony
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

<<<<<<< HEAD
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

=======
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

            unset($sheetData[0]);
            return view('basic.uploadbasic')->with(['data' => $sheetData])->with('choose' , $choose);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //基礎信息上傳頁面
    public function uploadbasicpage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('basic.uploadbasic1');
        }
>>>>>>> 0827tony
        else
        {
            return redirect(route('member.login'));
        }
<<<<<<< HEAD

=======
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
                        ->insert(['客戶' => $data]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $mess = trans('basicInfoLang.repeat');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('basic.uploadbasic1');
                    }
                }
                else if($choose === '儲位')
                {
                    DB::beginTransaction();
                    try {
                        DB::table('儲位')
                        ->insert(['儲存位置' => $data]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $mess = trans('basicInfoLang.repeat');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('basic.uploadbasic1');
                    }
                }
                else
                {
                    DB::beginTransaction();
                    try {
                        DB::table($choose)
                        ->insert([$choose => $data]);
                        DB::commit();
                        $record++;
                    }catch (\Exception $e) {
                        DB::rollback();
                        $mess = trans('basicInfoLang.repeat');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        </script>");
                        return view('basic.uploadbasic1');
                    }
                }



            }
            $mess = trans('basicInfoLang.total').$record.trans('basicInfoLang.record')
            .$choose.trans('basicInfoLang.new').trans('basicInfoLang.success');
            echo("<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href = '/basic';
            </script>");

        }
        else
        {
            return redirect(route('member.login'));
        }
>>>>>>> 0827tony
    }
}
