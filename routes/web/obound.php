<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OboundController;

/*
|--------------------------------------------------------------------------
| Web Routes for obound type
|--------------------------------------------------------------------------
|
*/

//O庫
Route::get('/', [OboundController::class, 'index'])->name('obound.index');

//新增料件

Route::get('/new', [OboundController::class, 'new']);

Route::post('/new', [OboundController::class, 'new'])->name('obound.new');

//新增料件成功
Route::get('/newok', [OboundController::class, 'newok']);

//基礎資料上傳
Route::get('/uploadmaterial', [OboundController::class, 'uploadmaterialpage']);

Route::post('/uploadmaterial', [OboundController::class, 'uploadmaterial'])->name('obound.uploadmaterial');

//基礎資料上傳新增至資料庫
Route::post('/insertuploadmaterial', [OboundController::class, 'insertuploadmaterial'])->name('obound.insertuploadmaterial');


//料件信息查詢頁面
Route::get('/material', [OboundController::class, 'material'])->name('obound.material');


//料件信息查詢
Route::get('/materialsearch', [OboundController::class, 'searchmaterial']);

Route::post('/materialsearch', [OboundController::class, 'searchmaterial'])->name('obound.searchmaterial');

//O庫-入庫頁面
Route::get('/inbound', [OboundController::class, 'inbound'])->name('obound.inbound');


//O庫-入庫新增
Route::post('/inboundnew', [OboundController::class, 'inboundnew'])->name('obound.inboundnew');


//O庫-入庫新增添加頁面
Route::get('/inboundnewok', [OboundController::class, 'inboundnewok']);

//O庫-入庫新增提交
Route::post('/inboundnewsubmit', [OboundController::class, 'inboundnewsubmit'])->name('obound.inboundnewsubmit');

//O庫-入庫提交新增成功
Route::get('/inboundnewsubmitok', [OboundController::class, 'inboundnewsubmitok'])->name('obound.inboundnewsubmitok');

//O庫-入庫查詢頁面
Route::get('/inboundsearch', [OboundController::class, 'inboundsearch'])->name('obound.inboundsearch');

//O庫-入庫查詢ok
Route::get('/inboundsearchok', [OboundController::class, 'inboundsearchok']);

Route::post('/inboundsearchok', [OboundController::class, 'inboundsearchok'])->name('obound.inboundsearchok');

//O庫-入庫查詢刪除
Route::post('/delete', [OboundController::class, 'delete'])->name('obound.delete');

//O庫-庫存上傳頁面
Route::get('/upload', [OboundController::class, 'upload'])->name('obound.upload');

//O庫-新增料件上傳
Route::get('/uploadinventory', [OboundController::class, 'uploadinventorypage']);

Route::post('/uploadinventory', [OboundController::class, 'uploadinventory'])->name('obound.uploadinventory');

//O庫-上傳資料新增至資料庫
Route::post('/insertuploadinventory', [OboundController::class, 'insertuploadinventory'])->name('obound.insertuploadinventory');

//O庫-庫存查詢頁面
Route::get('/searchstock', [OboundController::class, 'searchstock'])->name('obound.searchstock');

//O庫-庫存查詢成功
Route::get('/searchstocksubmit', [OboundController::class, 'searchstocksubmit']);

Route::post('/searchstocksubmit', [OboundController::class, 'searchstocksubmit'])->name('obound.searchstocksubmit');



//O庫-領料
Route::get('/pick', [OboundController::class, 'pick'])->name('obound.pick');

//O庫-退料
Route::get('/back', [OboundController::class, 'back'])->name('obound.back');

//O庫-領料單頁面
Route::get('/picklist', [OboundController::class, 'picklistpage'])->name('obound.picklistpage');

//O庫-退料單頁面
Route::get('/backlist', [OboundController::class, 'backlistpage'])->name('obound.backlistpage');

//O庫-領料單
Route::get('/picklistsub', [OboundController::class, 'picklist']);

Route::post('/picklistsub', [OboundController::class, 'picklist'])->name('obound.picklist');

//O庫-退料單
Route::get('/backlistsub', [OboundController::class, 'backlist']);

Route::post('/backlistsub', [OboundController::class, 'backlist'])->name('obound.backlist');

//O庫-領料記錄表頁面
Route::get('/pickrecord', [OboundController::class, 'pickrecord'])->name('obound.pickrecord');

//O庫-退料紀錄表頁面
Route::get('/backrecord', [OboundController::class, 'backrecord'])->name('obound.backrecord');

//O庫-領料紀錄表查詢
Route::get('/pickrecordsearch', [OboundController::class, 'pickrecordsearch']);

Route::post('/pickrecordsearch', [OboundController::class, 'pickrecordsearch'])->name('obound.pickrecordsearch');

//O庫-退料紀錄表查詢
Route::get('/backrecordsearch', [OboundController::class, 'backrecordsearch']);

Route::post('/backrecordsearch', [OboundController::class, 'backrecordsearch'])->name('obound.backrecordsearch');

//O庫-領料添加
Route::post('/pickadd', [OboundController::class, 'pickadd'])->name('obound.pickadd');

//O庫-領料添加頁面
Route::get('/pickaddok', [OboundController::class, 'pickaddok']);

//O庫-退料添加
Route::post('/backadd', [OboundController::class, 'backadd'])->name('obound.backadd');

//O庫-退料添加頁面
Route::get('/backaddok', [OboundController::class, 'backaddok']);

//O庫-提交領料
Route::post('/pickaddsubmit', [OboundController::class, 'pickaddsubmit'])->name('obound.pickaddsubmit');

//O庫-提交退料
Route::post('/backaddsubmit', [OboundController::class, 'backaddsubmit'])->name('obound.backaddsubmit');

//O庫-提交領料單
Route::post('/picklistsubmit', [OboundController::class, 'picklistsubmit'])->name('obound.picklistsubmit');

//O庫-提交退料單
Route::post('/backlistsubmit', [OboundController::class, 'backlistsubmit'])->name('obound.backlistsubmit');

//O庫-領料添加成功頁面
Route::get('/pickaddsubmitok', [OboundController::class, 'pickaddsubmitok']);

//O庫-退料添加成功頁面
Route::get('/backaddsubmitok', [OboundController::class, 'backaddsubmitok']);

//O庫-領料單添加成功頁面
Route::get('/picklistsubmitok', [OboundController::class, 'picklistsubmitok']);

//O庫-退料單添加成功頁面
Route::get('/backlistsubmitok', [OboundController::class, 'backlistsubmitok']);

//O庫-資料下載
Route::post('/download', [OboundController::class, 'download'])->name('obound.download');
