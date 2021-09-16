<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BUController;

/*
|--------------------------------------------------------------------------
| Web Routes for bu type
|--------------------------------------------------------------------------
|
*/

//index
Route::get('/', [BUController::class, 'index'])->name('bu.index');

//搜尋呆滯庫存
Route::get('/sluggish', [BUController::class, 'sluggish'])->name('bu.sluggish');

//新增調撥單
Route::post('/transsluggish', [BUController::class, 'transsluggish'])->name('bu.transsluggish');

//調撥單查詢頁面
Route::get('/searchlist', [BUController::class, 'searchlist'])->name('bu.searchlist');

//調撥單查詢
Route::get('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');

Route::post('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');

//調撥單查詢-刪除
Route::post('/delete', [BUController::class, 'delete'])->name('bu.delete');

//調撥_撥出單頁面
Route::get('/outlist', [BUController::class, 'outlistpage'])->name('bu.outlistpage');

//調撥_撥出單
Route::post('/outlistsub', [BUController::class, 'outlist'])->name('bu.outlist');

//調撥_撥出單提交
Route::post('/outlistsubmit', [BUController::class, 'outlistsubmit'])->name('bu.outlistsubmit');

//調撥_接收單頁面
Route::get('/picklist', [BUController::class, 'picklistpage'])->name('bu.picklistpage');

//調撥_接收單
Route::post('/picklistsub', [BUController::class, 'picklist'])->name('bu.picklist');

//調撥_接收單提交
Route::post('/picklistsubmit', [BUController::class, 'picklistsubmit'])->name('bu.picklistsubmit');
