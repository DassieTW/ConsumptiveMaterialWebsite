<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboundController;
use App\Models\Login;
use App\Models\人員信息;
use App\Models\客戶別;
use App\Models\機種;
use App\Models\製程;
use App\Models\線別;
use App\Models\領用原因;
use App\Models\退回原因;
use App\Models\入庫原因;
use App\Models\發料部門;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\儲位;
use App\Models\在途量;
use App\Models\Inventory;
use App\Models\不良品Inventory;
use App\Models\Inbound;
use App\Models\ConsumptiveMaterial;

/*
|--------------------------------------------------------------------------
| Web Routes for inbound type
|--------------------------------------------------------------------------
|
*/



//入庫
Route::get('/', function () {
    return view('inbound.index');
})->name('inbound.index')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-查詢頁面
Route::get('/search',  function () {
    return view('inbound.search')->with(['client' => 客戶別::cursor()]);
})->name('inbound.search')->middleware('can:viewInbound,App\Models\Inbound');

//Route::post('/search', [InboundController::class, 'search'])->name('inbound.search');

//入庫-查詢ok
Route::get('/inquire', function () {
    return view("inbound.searchok");
})->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增
Route::get('/add', function () {
    return view('inbound.add')
        ->with(['clients' => 客戶別::cursor()])
        ->with(['inreasons' => 入庫原因::cursor()])
        ->with(['positions' => 儲位::cursor()])
        ->with(['peoples' => 人員信息::cursor()])
        ->with(['checks' => 人員信息::cursor()]);
})->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/add', [InboundController::class, 'add'])->name('inbound.add')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加
Route::post('/addnew', [InboundController::class, 'addnew'])->name('inbound.addnew')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加(By客戶別)
Route::get('/addclient', function () {
    if (Session::has('addclient')) {
        Session::forget('addclient');
        $client = Session::get('client');
        $inreason = Session::get('inreason');
        $datas = DB::table("在途量")->where('請購數量', '>', 0)->where('客戶', $client)->get();
        return view("inbound.addclient")->with(['data' => $datas])
            ->with(['inreason' => $inreason])
            ->with(['positions' => 儲位::cursor()])
            ->with(['peoples' => 人員信息::cursor()])
            ->with(['checks' => 人員信息::cursor()]);
    } else {
        return redirect(route('inbound.add'));
    }
})->middleware('can:viewInbound,App\Models\Inbound');

//入庫-提交新增
Route::post('/addnewsubmit', [InboundController::class, 'addnewsubmit'])->name('inbound.addnewsubmit')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢頁面
Route::get('/searchstock', function () {
    return view('inbound.searchstock')->with(['client' => 客戶別::cursor()])
        ->with(['position' => 儲位::cursor()])
        ->with(['senddep' => 發料部門::cursor()]);
})->name('inbound.searchstock')->middleware('can:viewInbound,App\Models\Inbound');

//Route::post('/searchstock', [InboundController::class, 'searchstock'])->name('inbound.searchstock');

//入庫-庫存查詢成功
Route::get('/searchstocksubmit', function () {
    Session::put("month", false);
    return view("inbound.searchstockok");
})->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢成功(月使用量)
Route::get('/searchstocksubmit1', function () {
    Session::put("month", true);
    return view("inbound.searchstockok");
})->middleware('can:viewInbound,App\Models\Inbound');


//入庫-查詢刪除
Route::post('/delete', [InboundController::class, 'delete'])->name('inbound.delete')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥頁面
Route::get('/positionchange', function () {
    return view('inbound.positionchange')->with(['client' => 客戶別::cursor()])
        ->with(['position' => 儲位::cursor()])->with(['send' => 發料部門::cursor()]);
})->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/positionchange', [InboundController::class, 'positionchange'])->name('inbound.positionchange')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥
Route::get('/change', [InboundController::class, 'change'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/change', [InboundController::class, 'change'])->name('inbound.change')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥提交
Route::post('/changesubmit', [InboundController::class, 'changesubmit'])->name('inbound.changesubmit')->middleware('can:viewInbound,App\Models\Inbound');



//Route::post('/upload', [InboundController::class, 'upload'])->name('inbound.upload');

//入庫-新增提交(By客戶別)
Route::post('/addclientsubmit', [InboundController::class, 'addclientsubmit'])->name('inbound.addclientsubmit')->middleware('can:viewInbound,App\Models\Inbound');

//資料下載
Route::post('/download', [InboundController::class, 'download'])->name('inbound.download')->middleware('can:viewInbound,App\Models\Inbound');


//新增料件上傳
Route::get('/upload', function () {
    return view('inbound.upload');
})->name('inbound.upload')->middleware('can:viewInbound,App\Models\Inbound');

Route::get('/uploadinventory', function () {
    return view('inbound.upload');
})->middleware('can:viewInbound,App\Models\Inbound');

//test
Route::get('/testinbound', function () {
    $datas1 = DB::table('月請購_單耗')
        ->join('MPS', function ($join) {
            $join->on('MPS.料號90', '=', '月請購_單耗.料號90')
                ->on('MPS.料號', '=', '月請購_單耗.料號');
        })
        ->join('consumptive_material', function ($join) {
            $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
        })
        ->select('MPS.下月MPS', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * 5 / 26) as 安全庫存)'))
        ->where('月請購_單耗.狀態', '=', "已完成")
        // ->where('consumptive_material.耗材歸屬', '=', "單耗")
        ->where('consumptive_material.月請購', '=', "是");

    $datas1 = $datas1->select('月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * 5 / 26) as 安全庫存'))
        ->groupBy('月請購_單耗.料號')->get();
    dd($datas1);
})->middleware('can:viewInbound,App\Models\Inbound');
