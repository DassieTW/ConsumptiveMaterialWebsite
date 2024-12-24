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
    return view('inbound.add');
})->name("inbound.add")->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢頁面
Route::get('/searchstock', function () {
    return view('inbound.searchstock')->with(['client' => 客戶別::cursor()])
        ->with(['position' => 儲位::cursor()])
        ->with(['senddep' => 發料部門::cursor()]);
})->name('inbound.searchstock')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢結果
Route::get('/searchstocksubmit', function () {
    Session::put("month", false);
    Session::put("nogood", false);
    return view("inbound.searchstockok");
})->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢結果(月使用量)
Route::get('/searchstocksubmit1', function () {
    Session::put("month", true);
    Session::put("nogood", false);
    return view("inbound.searchstockok");
})->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢結果(不良品)
Route::get('/searchstocksubmit2', function () {
    Session::put("month", false);
    Session::put("nogood", true);
    return view("inbound.searchstockok");
})->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥畫面
Route::get('/change', function() {
    return view('inbound.change');
})->name('inbound.change')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥紀錄畫面
Route::get('/change_record', function() {
    return view('inbound.changeRecord');
})->name('inbound.change_record')->middleware('can:viewInbound,App\Models\Inbound');