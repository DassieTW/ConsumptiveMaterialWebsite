<?php

use App\Models\發料部門;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallController;
//use DB;
use App\Models\Inventory;
/*
|--------------------------------------------------------------------------
| Web Routes for call type
|--------------------------------------------------------------------------
|
*/

/*//index
Route::get('/', [CallController::class, 'index'])->name('call.index')->middleware('can:viewAlarm,App\Models\Inventory');
*/

//安全庫存警報頁面
Route::get('/safe', function () {

    $inventorys = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號 '))->groupBy('客戶別', '料號');
    $datas = DB::table('月請購_單耗')
        ->join('MPS', function ($join) {
            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                ->on('MPS.機種', '=', '月請購_單耗.機種')
                ->on('MPS.製程', '=', '月請購_單耗.製程');
        })
        ->join('consumptive_material', function ($join) {
            $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
        })
        ->leftJoinSub($inventorys, 'suminventory', function ($join) {
            $join->on('月請購_單耗.客戶別', '=', 'suminventory.客戶別');
            $join->on('月請購_單耗.料號', '=', 'suminventory.料號');
        })

        ->select(
            '月請購_單耗.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.安全庫存',
            '月請購_單耗.單耗',
            'MPS.下月MPS',
            'MPS.下月生產天數',
            'inventory現有庫存',
        )->groupBy(
            '月請購_單耗.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.安全庫存',
            '月請購_單耗.單耗',
            'MPS.下月MPS',
            'MPS.下月生產天數',
            'inventory現有庫存',
        )
        ->where('consumptive_material.月請購', '=', "是")
        ->where('月請購_單耗.狀態', '=', "已完成")
        ->get()->toArray();



    foreach ($datas as $data) {
        $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
        $data->安全庫存 = round($safe);
    } // for each

    $count = count($datas);
    for ($a = 0; $a < $count; $a++) {
        for ($i = $a; $i + 1 < $count; $i++) {

            if ($datas[$a]->客戶別 === $datas[$i + 1]->客戶別 && $datas[$a]->料號 === $datas[$i + 1]->料號) {


                $datas[$a]->安全庫存 += $datas[$i + 1]->安全庫存;
                unset($datas[$i + 1]);
                $datas = array_values($datas);
            } // if
        } // for
    } // for

    foreach ($datas as $key => $value) {


        if ($value->inventory現有庫存 > $value->安全庫存) {
            unset($datas[$key]);
        }
    }
    $datas = array_values($datas);

    $inventorys1 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號'))->groupBy('客戶別', '料號');
    $datas1 = DB::table('月請購_站位')
        ->join('MPS', function ($join) {
            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                ->on('MPS.機種', '=', '月請購_站位.機種')
                ->on('MPS.製程', '=', '月請購_站位.製程');
        })
        ->join('consumptive_material', function ($join) {
            $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
        })
        ->leftJoinSub($inventorys1, 'suminventory', function ($join) {
            $join->on('月請購_站位.客戶別', '=', 'suminventory.客戶別');
            $join->on('月請購_站位.料號', '=', 'suminventory.料號');
        })

        ->select(
            '月請購_站位.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.MPQ',
            'consumptive_material.安全庫存',
            '月請購_站位.下月站位人數',
            '月請購_站位.下月開線數',
            '月請購_站位.下月開班數',
            '月請購_站位.下月每人每日需求量',
            '月請購_站位.下月每日更換頻率',
            'inventory現有庫存',
        )->groupBy(
            '月請購_站位.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.MPQ',
            'consumptive_material.安全庫存',
            '月請購_站位.下月站位人數',
            '月請購_站位.下月開線數',
            '月請購_站位.下月開班數',
            '月請購_站位.下月每人每日需求量',
            '月請購_站位.下月每日更換頻率',
            'inventory現有庫存',
        )
        ->where('consumptive_material.月請購', '=', "是")
        ->where('月請購_站位.狀態', '=', "已完成")
        ->get()->toArray();


    foreach ($datas1 as $data) {
        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
        $data->安全庫存 = round($safe);
    }

    $count1 = count($datas1);
    for ($a = 0; $a < $count1; $a++) {
        for ($i = $a; $i + 1 < $count1; $i++) {

            if ($datas1[$a]->客戶別 === $datas1[$i + 1]->客戶別 && $datas1[$a]->料號 === $datas1[$i + 1]->料號) {


                $datas1[$a]->安全庫存 += $datas1[$i + 1]->安全庫存;
                unset($datas1[$i + 1]);
                $datas1 = array_values($datas1);
            } // if
        } // for
    } // for

    foreach ($datas1 as $key => $value) {


        if ($value->inventory現有庫存 > $value->安全庫存) {
            unset($datas1[$key]);
        }
    }
    $datas1 = array_values($datas1);

    $inventorys2 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 ,客戶別  ,料號'))->groupBy('客戶別', '料號');
    $datas2 = DB::table('consumptive_material')
        ->leftJoinSub($inventorys2, 'suminventory', function ($join) {
            $join->on('consumptive_material.料號', '=', 'suminventory.料號');
        })
        ->select(
            '客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.安全庫存',
            'consumptive_material.月請購',
            'inventory現有庫存',
        )->groupBy(
            '客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.安全庫存',
            'consumptive_material.月請購',
            'inventory現有庫存',

        )
        ->where('consumptive_material.月請購', '=', "否")
        ->get()->unique('料號')->toArray();

    foreach ($datas2 as $data) {
        $safe = $data->安全庫存;
        $data->安全庫存 = round($safe);
    } // for each

    foreach ($datas2 as $key => $value) {

        if ($value->inventory現有庫存 >= $value->安全庫存) {
            unset($datas2[$key]);
        }
    }
    $datas2 = array_values($datas2);

    $num = count($datas) + count($datas1) + count($datas2);
    return view('call.safepage')->with(['sends' => 發料部門::cursor()])->with(['num' => $num]);
})->name('call.safe')->middleware('can:viewAlarm,App\Models\Inventory');

//安全庫存警報
Route::get('/safesubmit', [CallController::class, 'safesubmit'])->middleware('can:viewAlarm,App\Models\Inventory');

Route::post('/safesubmit', [CallController::class, 'safesubmit'])->name('call.safesubmit')->middleware('can:viewAlarm,App\Models\Inventory');

//呆滯天數警報頁面
Route::get('/day', function () {
    $datas = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
        ->select(
            'inventory.客戶別',
            'inventory.料號',
            DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
            DB::raw('sum(inventory.現有庫存) as inventory現有庫存'),
            'consumptive_material.品名',
            'consumptive_material.規格',
        )
        ->groupBy('inventory.客戶別', 'inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格',)
        ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>30')
        ->havingRaw('sum(inventory.現有庫存) > ?', [0])
        ->get();

    $num = count($datas);
    return view('call.daypage')->with(['sends' => 發料部門::cursor()])->with(['num' => $num]);

})->name('call.day')->middleware('can:viewAlarm,App\Models\Inventory');
//呆滯天數警報
Route::get('/daysubmit', [CallController::class, 'daysubmit'])->middleware('can:viewAlarm,App\Models\Inventory');

Route::post('/daysubmit', [CallController::class, 'daysubmit'])->name('call.daysubmit')->middleware('can:viewAlarm,App\Models\Inventory');
