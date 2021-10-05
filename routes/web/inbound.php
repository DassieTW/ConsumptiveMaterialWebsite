<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboundController;

/*
|--------------------------------------------------------------------------
| Web Routes for inbound type
|--------------------------------------------------------------------------
|
*/

//入庫
Route::get('/', [InboundController::class, 'index'])->name('inbound.index')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-查詢頁面
Route::get('/search', [InboundController::class, 'search'])->name('inbound.search')->middleware('can:viewInbound,App\Models\Inbound');

//Route::post('/search', [InboundController::class, 'search'])->name('inbound.search');

//入庫-查詢ok
Route::get('/inquire', [InboundController::class, 'inquire'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/inquire', [InboundController::class, 'inquire'])->name('inbound.inquire')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增
Route::get('/add', [InboundController::class, 'add'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/add', [InboundController::class, 'add'])->name('inbound.add')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加
Route::post('/addnew', [InboundController::class, 'addnew'])->name('inbound.addnew')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加(By客戶別)
Route::get('/addclient', [InboundController::class, 'addclient'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/addclient', [InboundController::class, 'addclient'])->name('inbound.addclient')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-新增添加頁面
Route::get('/addnewok', [InboundController::class, 'addnewok'])->middleware('can:viewInbound,App\Models\Inbound');

//入庫-提交新增
Route::post('/addnewsubmit', [InboundController::class, 'addnewsubmit'])->name('inbound.addnewsubmit')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-提交新增成功
Route::get('/addnewsubmitok', [InboundController::class, 'addnewsubmitok'])->name('inbound.addnewsubmitok')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存查詢頁面
Route::get('/searchstock', [InboundController::class, 'searchstock'])->name('inbound.searchstock')->middleware('can:viewInbound,App\Models\Inbound');

//Route::post('/searchstock', [InboundController::class, 'searchstock'])->name('inbound.searchstock');

//入庫-庫存查詢成功
Route::get('/searchstocksubmit', [InboundController::class, 'searchstocksubmit'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/searchstocksubmit', [InboundController::class, 'searchstocksubmit'])->name('inbound.searchstocksubmit')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-查詢刪除
Route::post('/delete', [InboundController::class, 'delete'])->name('inbound.delete')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥頁面
Route::get('/positionchange', [InboundController::class, 'positionchange'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/positionchange', [InboundController::class, 'positionchange'])->name('inbound.positionchange')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥
Route::get('/change', [InboundController::class, 'change'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/change', [InboundController::class, 'change'])->name('inbound.change')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-儲位調撥提交
Route::post('/changesubmit', [InboundController::class, 'changesubmit'])->name('inbound.changesubmit')->middleware('can:viewInbound,App\Models\Inbound');

//入庫-庫存上傳頁面
Route::get('/upload', [InboundController::class, 'upload'])->name('inbound.upload')->middleware('can:viewInbound,App\Models\Inbound');

//Route::post('/upload', [InboundController::class, 'upload'])->name('inbound.upload');

//入庫-新增提交(By客戶別)
Route::post('/addclientsubmit', [InboundController::class, 'addclientsubmit'])->name('inbound.addclientsubmit')->middleware('can:viewInbound,App\Models\Inbound');

//資料下載
Route::post('/download', [InboundController::class, 'download'])->name('inbound.download')->middleware('can:viewInbound,App\Models\Inbound');


//新增料件上傳
Route::get('/uploadinventory', [InboundController::class, 'uploadinventorypage'])->middleware('can:viewInbound,App\Models\Inbound');

Route::post('/uploadinventory', [InboundController::class, 'uploadinventory'])->name('inbound.uploadinventory')->middleware('can:viewInbound,App\Models\Inbound');
//上傳資料新增至資料庫
Route::post('/insertuploadinventory', [InboundController::class, 'insertuploadinventory'])->name('inbound.insertuploadinventory')->middleware('can:viewInbound,App\Models\Inbound');
