<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OutboundController;

/*
|--------------------------------------------------------------------------
| Web Routes for outbound type
|--------------------------------------------------------------------------
|
*/

//出庫
Route::get('/', [OutboundController::class, 'index'])->name('outbound.index');

//出庫-領料
Route::get('/pick', [OutboundController::class, 'pick'])->name('outbound.pick');

//Route::post('/pick', [OutboundController::class, 'pick'])->name('outbound.pick');

//出庫-退料
Route::get('/back', [OutboundController::class, 'back'])->name('outbound.back');

//Route::post('/back', [OutboundController::class, 'back'])->name('outbound.back');

//出庫-領料單頁面
Route::get('/picklist', [OutboundController::class, 'picklistpage'])->name('outbound.picklistpage');

//Route::post('/picklist', [OutboundController::class, 'picklistpage'])->name('outbound.picklistpage');

//出庫-領料單
Route::get('/picklistsub', [OutboundController::class, 'picklist']);

Route::post('/picklistsub', [OutboundController::class, 'picklist'])->name('outbound.picklist');

//出庫-退料單頁面
Route::get('/backlist', [OutboundController::class, 'backlistpage'])->name('outbound.backlistpage');

//Route::post('/backlist', [OutboundController::class, 'backlistpage'])->name('outbound.backlistpage');

//出庫-領料單
Route::get('/backlistsub', [OutboundController::class, 'backlist']);

Route::post('/backlistsub', [OutboundController::class, 'backlist'])->name('outbound.backlist');

//出庫-領料紀錄表
Route::get('/pickrecord', [OutboundController::class, 'pickrecord'])->name('outbound.pickrecord');

//Route::post('/pickrecord', [OutboundController::class, 'pickrecord'])->name('outbound.pickrecord');

//出庫-領料紀錄表查詢
Route::get('/pickrecordsearch', [OutboundController::class, 'pickrecordsearch']);

Route::post('/pickrecordsearch', [OutboundController::class, 'pickrecordsearch'])->name('outbound.pickrecordsearch');

//出庫-退料紀錄表
Route::get('/backrecord', [OutboundController::class, 'backrecord'])->name('outbound.backrecord');

//Route::post('/backrecord', [OutboundController::class, 'backrecord'])->name('outbound.backrecord');

//出庫-退料紀錄表查詢
Route::get('/backrecordsearch', [OutboundController::class, 'backrecordsearch']);

Route::post('/backrecordsearch', [OutboundController::class, 'backrecordsearch'])->name('outbound.backrecordsearch');

//領料添加
Route::post('/pickadd', [OutboundController::class, 'pickadd'])->name('outbound.pickadd');

//領料添加頁面
Route::get('/pickaddok', [OutboundController::class, 'pickaddok']);

//退料添加
Route::post('/backadd', [OutboundController::class, 'backadd'])->name('outbound.backadd');

//退料添加頁面
Route::get('/backaddok', [OutboundController::class, 'backaddok']);

//提交領料
Route::post('/pickaddsubmit', [OutboundController::class, 'pickaddsubmit'])->name('outbound.pickaddsubmit');

//提交退料
Route::post('/backaddsubmit', [OutboundController::class, 'backaddsubmit'])->name('outbound.backaddsubmit');

//提交領料單
Route::post('/picklistsubmit', [OutboundController::class, 'picklistsubmit'])->name('outbound.picklistsubmit');

//提交退料單
Route::post('/backlistsubmit', [OutboundController::class, 'backlistsubmit'])->name('outbound.backlistsubmit');

//資料下載
Route::post('/download', [OutboundController::class, 'download'])->name('outbound.download');
