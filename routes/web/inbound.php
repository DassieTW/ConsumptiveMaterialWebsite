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
Route::get('/', [InboundController::class, 'index'])->name('inbound.index');

//入庫-查詢頁面
Route::get('/search', [InboundController::class, 'search'])->name('inbound.search');

//Route::post('/search', [InboundController::class, 'search'])->name('inbound.search');

//入庫-查詢ok
Route::get('/inquire', [InboundController::class, 'inquire']);

Route::post('/inquire', [InboundController::class, 'inquire'])->name('inbound.inquire');

//入庫-新增
Route::get('/add', [InboundController::class, 'add']);

Route::post('/add', [InboundController::class, 'add'])->name('inbound.add');

//入庫-新增添加
Route::post('/addnew', [InboundController::class, 'addnew'])->name('inbound.addnew');

//入庫-新增添加(By客戶別)
Route::get('/addclient', [InboundController::class, 'addclient']);

Route::post('/addclient', [InboundController::class, 'addclient'])->name('inbound.addclient');

//入庫-新增添加頁面
Route::get('/addnewok', [InboundController::class, 'addnewok']);

//入庫-提交新增
Route::post('/addnewsubmit', [InboundController::class, 'addnewsubmit'])->name('inbound.addnewsubmit');

//入庫-提交新增成功
Route::get('/addnewsubmitok', [InboundController::class, 'addnewsubmitok'])->name('inbound.addnewsubmitok');

//入庫-庫存查詢頁面
Route::get('/searchstock', [InboundController::class, 'searchstock'])->name('inbound.searchstock');

//Route::post('/searchstock', [InboundController::class, 'searchstock'])->name('inbound.searchstock');

//入庫-庫存查詢成功
Route::get('/searchstocksubmit', [InboundController::class, 'searchstocksubmit']);

Route::post('/searchstocksubmit', [InboundController::class, 'searchstocksubmit'])->name('inbound.searchstocksubmit');

//入庫-查詢刪除
Route::post('/delete', [InboundController::class, 'delete'])->name('inbound.delete');

//入庫-儲位調撥頁面
Route::get('/positionchange', [InboundController::class, 'positionchange']);

Route::post('/positionchange', [InboundController::class, 'positionchange'])->name('inbound.positionchange');

//入庫-儲位調撥
Route::get('/change', [InboundController::class, 'change']);

Route::post('/change', [InboundController::class, 'change'])->name('inbound.change');

//入庫-儲位調撥提交
Route::post('/changesubmit', [InboundController::class, 'changesubmit'])->name('inbound.changesubmit');

//入庫-庫存上傳頁面
Route::get('/upload', [InboundController::class, 'upload'])->name('inbound.upload');

//Route::post('/upload', [InboundController::class, 'upload'])->name('inbound.upload');

//入庫-新增提交(By客戶別)
Route::post('/addclientsubmit', [InboundController::class, 'addclientsubmit'])->name('inbound.addclientsubmit');

//資料下載
Route::post('/download', [InboundController::class, 'download'])->name('inbound.download');


//新增料件上傳
Route::get('/uploadinventory', [InboundController::class, 'uploadinventorypage']);

Route::post('/uploadinventory', [InboundController::class, 'uploadinventory'])->name('inbound.uploadinventory');
//上傳資料新增至資料庫
Route::post('/insertuploadinventory', [InboundController::class, 'insertuploadinventory'])->name('inbound.insertuploadinventory');
