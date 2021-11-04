<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboundController;
use App\Models\Login;
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
use App\Models\人員信息;
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
Route::get('/', [InboundController::class, 'index'])->name('inbound.index')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-查詢頁面
Route::get('/search',  function () {
    return view('inbound.search')->with(['client' => 客戶別::cursor()]);
})->name('inbound.search')->middleware('can:viewInbound,App\Models\Inbound');

//Route::post('/search', [InboundController::class, 'search'])->name('inbound.search');

//入庫-查詢ok
Route::get('/inquire', [InboundController::class, 'inquire'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/inquire', [InboundController::class, 'inquire'])->name('inbound.inquire')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增
Route::get('/add', function () {
    return view('inbound.add')->with(['client' => 客戶別::cursor()])
    ->with(['inreason' => 入庫原因::cursor()]);
})->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/add', [InboundController::class, 'add'])->name('inbound.add')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加
Route::post('/addnew', [InboundController::class, 'addnew'])->name('inbound.addnew')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加(By客戶別)
Route::get('/addclient', function () {
    if (Session::has('addclient')) {
        Session::forget('addclient');
        $client = Session::get('client');
        $number = DB::table('在途量')->where('客戶', Session::get('client'))->where('請購數量', '>', 0)->value('料號');
        return view("inbound.addclient")->with(['data' => 在途量::cursor()->where('請購數量', '>', 0)->where('客戶', $client)])
            ->with(['inreason' => Session::get('inreason')])
            ->with(['positions' => 儲位::cursor()])
            ->with(['peopleinf' => 人員信息::cursor()]);
    } else {
        return redirect(route('inbound.add'));
    }
})->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/addclient', [InboundController::class, 'addclient'])->name('inbound.addclient')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加頁面
Route::get('/addnewok', function () {
    if (Session::has('inboundadd')) {
        Session::forget('inboundadd');
        return view("inbound.addnew");
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
Route::get('/searchstocksubmit', [InboundController::class, 'searchstocksubmit'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/searchstocksubmit', [InboundController::class, 'searchstocksubmit'])->name('inbound.searchstocksubmit')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-查詢刪除
Route::post('/delete', [InboundController::class, 'delete'])->name('inbound.delete')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥頁面
Route::get('/positionchange',function () {
    return view('inbound.positionchange')->with(['client' => 客戶別::cursor()])
        ->with(['position' => 儲位::cursor()]);
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

Route::post('/uploadinventory', [InboundController::class, 'uploadinventory'])->name('inbound.uploadinventory')->middleware('can:viewInbound,App\Models\Inbound');
//上傳資料新增至資料庫
Route::post('/insertuploadinventory', [InboundController::class, 'insertuploadinventory'])->name('inbound.insertuploadinventory')->middleware('can:viewInbound,App\Models\Inbound');
