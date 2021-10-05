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
            //delete check items
            if($request->has('delete'))
            {
                //factory
                if($request->has('factory'))
                {
                    $count = $request->input('factorycount');
                    $names = DB::table('廠別')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('factorycheck' . $i))
                        {
                            廠別::where('廠別', $names[$i]->廠別)->delete();
                            /*DB::table('廠別')
                            ->where('廠別', $names[$i])
                            ->softdelete();*/
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

                    $count = $request->input('clientcount');
                    $names = DB::table('客戶別')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('clientcheck' . $i))
                        {
                            客戶別::where('客戶', $names[$i]->客戶)->delete();
                            /*DB::table('客戶別')
                            ->where('客戶', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('machinecount');
                    $names = DB::table('機種')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('machinecheck' . $i))
                        {
                            機種::where('機種', $names[$i]->機種)->delete();
                            /*DB::table('機種')
                            ->where('機種', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('productioncount');
                    $names = DB::table('製程')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('productioncheck' . $i))
                        {
                            製程::where('製程', $names[$i]->製程)->delete();
                            /*DB::table('製程')
                            ->where('製程', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('linecount');
                    $names = DB::table('線別')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('linecheck' . $i))
                        {
                            線別::where('線別', $names[$i]->線別)->delete();
                            /*DB::table('線別')
                            ->where('線別', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('usecount');
                    $names = DB::table('領用部門')->whereNull('deleted_at')->get();('領用部門');

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('usecheck' . $i))
                        {
                            領用部門::where('領用部門', $names[$i]->領用部門)->delete();
                            /*DB::table('領用部門')
                            ->where('領用部門', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('usereasoncount');
                    $names = DB::table('領用原因')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('usereasoncheck' . $i))
                        {
                            領用原因::where('領用原因', $names[$i]->領用原因)->delete();
                            /*DB::table('領用原因')
                            ->where('領用原因', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('inreasoncount');
                    $names = DB::table('入庫原因')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('inreasoncheck' . $i))
                        {
                            入庫原因::where('入庫原因', $names[$i]->入庫原因)->delete();
                            /*DB::table('入庫原因')
                            ->where('入庫原因', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('positioncount');
                    $names = DB::table('儲位')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('positioncheck' . $i))
                        {
                            儲位::where('儲存位置', $names[$i]->儲存位置)->delete();
                            /*DB::table('儲位')
                            ->where('儲存位置', $names[$i])
                            ->delete();*/
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
                    $names = DB::table('發料部門')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('sendcheck' . $i))
                        {
                            發料部門::where('發料部門', $names[$i]->發料部門)->delete();
                            /*DB::table('發料部門')
                            ->where('發料部門', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('ocount');
                    $names = DB::table('O庫')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('ocheck' . $i))
                        {
                            O庫::where('O庫', $names[$i]->O庫)->delete();
                            /*DB::table('O庫')
                            ->where('O庫', $names[$i])
                            ->delete();*/
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
                else if($request->has('back'))
                {
                    $count = $request->input('backcount');
                    $names = DB::table('退回原因')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('backcheck' . $i))
                        {
                            退回原因::where('退回原因', $names[$i]->退回原因)->delete();
                            /*DB::table('退回原因')
                            ->where('退回原因', $names[$i])
                            ->delete();*/
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
                    $count = $request->input('factorycount');
                    $names = DB::table('廠別')->whereNull('deleted_at')->get();

                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('factorycheck' . $i))
                        {
                            DB::table('廠別')
                            ->where('廠別', $names[$i]->廠別)
                            ->update(['廠別' => $request->input('factory' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    if($request->input('factorynew') !== null)
                    {
                        $test = 廠別::onlyTrashed()
                        ->where('廠別', $request->input('factorynew'))
                        ->get();

                        if(!$test->isEmpty())
                        {
                            DB::table('廠別')
                            ->where('廠別', $request->input('factorynew'))
                            ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                        }
                        else
                        {
                            DB::table('廠別')
                            ->insert(['廠別' => $request->input('factorynew'),'created_at' => Carbon::now()]);
                        }

                    }

                    return view('basic.change')->with('choose' , 'factory')
                    ->with(['factorys' => 廠別::cursor()]);

                }
                //client
                else if($request->has('client'))
                {
                    $count = $request->input('clientcount');
                    $names = DB::table('客戶別')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('clientcheck' . $i))
                        {
                            DB::table('客戶別')
                            ->where('客戶', $names[$i]->客戶)
                            ->update(['客戶' => $request->input('client' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    if($request->input('clientnew') !== null)
                    {
                        $test = 客戶別::onlyTrashed()
                        ->where('客戶', $request->input('clientnew'))
                        ->get();

                        if(!$test->isEmpty())
                        {
                            DB::table('客戶別')
                            ->where('客戶', $request->input('clientnew'))
                            ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                        }
                        else
                        {
                            DB::table('客戶別')
                            ->insert(['客戶' => $request->input('clientnew'),'created_at' => Carbon::now()]);
                        }

                    }
                    return view('basic.change')->with('choose' , 'client')
                    ->with(['clients' => 客戶別::cursor()]);
                }
                //machine
                else if($request->has('machine'))
                {
                    $count = $request->input('machinecount');
                    $names = DB::table('機種')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('machinecheck' . $i))
                        {
                            DB::table('機種')
                            ->where('機種', $names[$i]->機種)
                            ->update(['機種' => $request->input('machine' . $i),'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 機種::onlyTrashed()
                        ->where('機種', $request->input('machinenew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('機種')
                        ->where('機種', $request->input('machinenew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('machinenew') !== null)
                    {
                        DB::table('機種')
                        ->insert(['機種' => $request->input('machinenew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'machine')
                    ->with(['machines' => 機種::cursor()]);
                }
                //production
                else if($request->has('production'))
                {
                    $count = $request->input('productioncount');
                    $names = DB::table('製程')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('machinecheck' . $i))
                        {
                            DB::table('製程')
                            ->where('製程', $names[$i]->製程)
                            ->update(['製程' => $request->input('production' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }

                    $test = 製程::onlyTrashed()
                        ->where('製程', $request->input('productionnew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('製程')
                        ->where('製程', $request->input('productionnew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('productionnew') !== null)
                    {
                        DB::table('製程')
                        ->insert(['製程' => $request->input('productionnew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'production')
                    ->with(['productions' => 製程::cursor()]);
                }
                //line
                else if($request->has('line'))
                {
                    $count = $request->input('linecount');
                    $names = DB::table('線別')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('linecheck' . $i))
                        {
                            DB::table('線別')
                            ->where('線別', $names[$i]->線別)
                            ->update(['線別' => $request->input('line' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 線別::onlyTrashed()
                        ->where('線別', $request->input('linenew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('線別')
                        ->where('線別', $request->input('linenew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('linenew') !== null)
                    {
                        DB::table('線別')
                        ->insert(['線別' => $request->input('linenew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'line')
                    ->with(['lines' => 線別::cursor()]);
                }
                //use
                else if($request->has('use'))
                {
                    $count = $request->input('usecount');
                    $names = DB::table('領用部門')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('usecheck' . $i))
                        {
                            DB::table('領用部門')
                            ->where('領用部門', $names[$i]->領用部門)
                            ->update(['領用部門' => $request->input('use' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 領用部門::onlyTrashed()
                        ->where('領用部門', $request->input('usenew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('領用部門')
                        ->where('領用部門', $request->input('usenew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('usenew') !== null)
                    {
                        DB::table('領用部門')
                        ->insert(['領用部門' => $request->input('usenew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'use')
                    ->with(['uses' => 領用部門::cursor()]);
                }
                //usereason
                else if($request->has('usereason'))
                {
                    $count = $request->input('usereasoncount');
                    $names = DB::table('領用原因')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('usereasoncheck' . $i))
                        {
                            DB::table('領用原因')
                            ->where('領用原因', $names[$i]->領用原因)
                            ->update(['領用原因' => $request->input('usereason' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 領用原因::onlyTrashed()
                        ->where('領用原因', $request->input('usereasonnew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('領用原因')
                        ->where('領用原因', $request->input('usereasonnew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('usereasonnew') !== null)
                    {
                        DB::table('領用原因')
                        ->insert(['領用原因' => $request->input('usereasonnew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'usereason')
                    ->with(['usereasons' => 領用原因::cursor()]);
                }
                //inreason
                else if($request->has('inreason'))
                {
                    $count = $request_input('inreasoncount');
                    $names = DB::table('入庫原因')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('inreasoncheck' . $i))
                        {
                            DB::table('入庫原因')
                            ->where('入庫原因', $names[$i]->入庫原因)
                            ->update(['入庫原因' => $request->input('inreason' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 入庫原因::onlyTrashed()
                        ->where('入庫原因', $request->input('inreasonnew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('入庫原因')
                        ->where('入庫原因', $request->input('inreasonnew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('inreasonnew') !== null)
                    {
                        DB::table('入庫原因')
                        ->insert(['入庫原因' => $request->input('inreasonnew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'inreason')
                    ->with(['inreasons' => 入庫原因::cursor()]);
                }
                //position
                else if($request->has('position'))
                {
                    $count = $request->input('positioncount');
                    $names = DB::table('儲位')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('positioncheck' . $i))
                        {
                            DB::table('儲位')
                            ->where('儲存位置', $names[$i]->儲存位置)
                            ->update(['儲存位置' => $request->input('position' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 儲位::onlyTrashed()
                        ->where('儲存位置', $request->input('positionnew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('儲位')
                        ->where('儲存位置', $request->input('positionnew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('positionnew') !== null)
                    {
                        DB::table('儲位')
                        ->insert(['儲存位置' => $request->input('positionnew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'position')
                    ->with(['positions' => 儲位::cursor()]);
                }
                //send
                else if($request->has('send'))
                {
                    $count = $request->input('sendcount');
                    $names = DB::table('發料部門')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('sendcheck' . $i))
                        {
                            DB::table('發料部門')
                            ->where('發料部門', $names[$i]->發料部門)
                            ->update(['發料部門' => $request->input('send' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 發料部門::onlyTrashed()
                        ->where('發料部門', $request->input('sendnew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('發料部門')
                        ->where('發料部門', $request->input('sendnew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    if($request->input('sendnew') !== null)
                    {
                        DB::table('發料部門')
                        ->insert(['發料部門' => $request->input('sendnew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'send')
                    ->with(['sends' => 發料部門::cursor()]);
                }
                //O庫
                else if($request->has('o'))
                {
                    $count = $request->input('ocount');
                    $names = DB::table('O庫')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('ocheck' . $i))
                        {
                            DB::table('O庫')
                            ->where('O庫', $names[$i]->O庫)
                            ->update(['O庫' => $request->input('o' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = O庫::onlyTrashed()
                        ->where('O庫', $request->input('onew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('O庫')
                        ->where('O庫', $request->input('onew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    else
                    {
                        DB::table('O庫')
                        ->insert(['O庫' => $request->input('onew'),'created_at' => Carbon::now()]);
                    }
                    return view('basic.change')->with('choose' , 'o')
                    ->with(['os' => O庫::cursor()]);
                }
                //退回原因
                else if($request->has('back'))
                {
                    $count = $request->input('backcount');
                    $names = DB::table('退回原因')->whereNull('deleted_at')->get();
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        if($request->has('backcheck' . $i))
                        {
                            DB::table('退回原因')
                            ->where('退回原因', $names[$i]->退回原因)
                            ->update(['退回原因' => $request->input('back' . $i) , 'updated_at' => Carbon::now()]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    $test = 退回原因::onlyTrashed()
                        ->where('退回原因', $request->input('backnew'))
                        ->get();

                    if(!$test->isEmpty())
                    {
                        DB::table('退回原因')
                        ->where('退回原因', $request->input('backnew'))
                        ->update(['updated_at' =>  Carbon::now() , 'deleted_at' => null]);
                    }
                    else
                    {
                        DB::table('退回原因')
                        ->insert(['退回原因' => $request->input('backnew'),'created_at' => Carbon::now()]);
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
                        return view('basic.uploadbasic1');
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
                        return view('basic.uploadbasic1');
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
                        return view('basic.uploadbasic1');
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
