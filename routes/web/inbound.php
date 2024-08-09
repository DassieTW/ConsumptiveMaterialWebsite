<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboundController;
use App\Models\人員信息;
use App\Models\客戶別;
use App\Models\入庫原因;
use App\Models\發料部門;
use App\Models\儲位;

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

//入庫-庫存查詢結果
Route::get('/searchstocksubmit', function () {
    Session::put("month", false);
    return view("inbound.searchstockok");
})->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢結果(月使用量)
Route::get('/searchstocksubmit1', function () {
    Session::put("month", true);
    return view("inbound.searchstockok");
})->middleware('can:viewInbound,App\Models\Inbound');

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
