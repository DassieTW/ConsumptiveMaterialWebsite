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
Route::get('/', [OutboundController::class, 'index'])->name('outbound.index')->middleware('can:viewOutbound,App\Models\Outbound');

//出庫-領料
Route::get('/pick', [OutboundController::class, 'pick'])->name('outbound.pick')->middleware('can:outboundPickup,App\Models\Outbound');

//Route::post('/pick', [OutboundController::class, 'pick'])->name('outbound.pick');

//出庫-退料
Route::get('/back', [OutboundController::class, 'back'])->name('outbound.back')->middleware('can:outboundReturn,App\Models\Outbound');

//Route::post('/back', [OutboundController::class, 'back'])->name('outbound.back');

//出庫-領料單頁面
Route::get('/picklist', [OutboundController::class, 'picklistpage'])->name('outbound.picklistpage')->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

//Route::post('/picklist', [OutboundController::class, 'picklistpage'])->name('outbound.picklistpage');

//出庫-領料單
Route::get('/picklistsub', [OutboundController::class, 'picklist'])->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

Route::post('/picklistsub', [OutboundController::class, 'picklist'])->name('outbound.picklist')->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

//出庫-退料單頁面
Route::get('/backlist', [OutboundController::class, 'backlistpage'])->name('outbound.backlistpage')->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

//Route::post('/backlist', [OutboundController::class, 'backlistpage'])->name('outbound.backlistpage');

//出庫-領料單
Route::get('/backlistsub', [OutboundController::class, 'backlist'])->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

Route::post('/backlistsub', [OutboundController::class, 'backlist'])->name('outbound.backlist')->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

//出庫-領料紀錄表
Route::get('/pickrecord', [OutboundController::class, 'pickrecord'])->name('outbound.pickrecord')->middleware('can:outboundPickupRecord,App\Models\Outbound');

//Route::post('/pickrecord', [OutboundController::class, 'pickrecord'])->name('outbound.pickrecord');

//出庫-領料紀錄表查詢
Route::get('/pickrecordsearch', [OutboundController::class, 'pickrecordsearch'])->middleware('can:outboundPickupRecord,App\Models\Outbound');

Route::post('/pickrecordsearch', [OutboundController::class, 'pickrecordsearch'])->name('outbound.pickrecordsearch')->middleware('can:outboundPickupRecord,App\Models\Outbound');

//出庫-退料紀錄表
Route::get('/backrecord', [OutboundController::class, 'backrecord'])->name('outbound.backrecord')->middleware('can:outboundReturnRecord,App\Models\Outbound');

//Route::post('/backrecord', [OutboundController::class, 'backrecord'])->name('outbound.backrecord');

//出庫-退料紀錄表查詢
Route::get('/backrecordsearch', [OutboundController::class, 'backrecordsearch'])->middleware('can:outboundReturnRecord,App\Models\Outbound');

Route::post('/backrecordsearch', [OutboundController::class, 'backrecordsearch'])->name('outbound.backrecordsearch')->middleware('can:outboundReturnRecord,App\Models\Outbound');

//領料添加
Route::post('/pickadd', [OutboundController::class, 'pickadd'])->name('outbound.pickadd')->middleware('can:outboundPickup,App\Models\Outbound');

//領料添加頁面
Route::get('/pickaddok', [OutboundController::class, 'pickaddok'])->middleware('can:outboundPickup,App\Models\Outbound');

//退料添加
Route::post('/backadd', [OutboundController::class, 'backadd'])->name('outbound.backadd')->middleware('can:outboundReturn,App\Models\Outbound');

//退料添加頁面
Route::get('/backaddok', [OutboundController::class, 'backaddok'])->middleware('can:outboundReturn,App\Models\Outbound');

//提交領料
Route::post('/pickaddsubmit', [OutboundController::class, 'pickaddsubmit'])->name('outbound.pickaddsubmit')->middleware('can:outboundPickup,App\Models\Outbound');

//提交退料
Route::post('/backaddsubmit', [OutboundController::class, 'backaddsubmit'])->name('outbound.backaddsubmit')->middleware('can:outboundReturn,App\Models\Outbound');

//提交領料單
Route::post('/picklistsubmit', [OutboundController::class, 'picklistsubmit'])->name('outbound.picklistsubmit')->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

//提交退料單
Route::post('/backlistsubmit', [OutboundController::class, 'backlistsubmit'])->name('outbound.backlistsubmit')->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

//資料下載
Route::post('/download', [OutboundController::class, 'download'])->name('outbound.download')->middleware('can:viewOutbound,App\Models\Outbound');
