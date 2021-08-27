<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallController;

/*
|--------------------------------------------------------------------------
| Web Routes for call type
|--------------------------------------------------------------------------
|
*/

//index
Route::get('/', [CallController::class, 'index'])->name('call.index');

//安全庫存警報頁面
Route::get('/safe', [CallController::class, 'safe'])->name('call.safe');

//安全庫存警報
Route::get('/safesubmit', [CallController::class, 'safesubmit']);

Route::post('/safesubmit', [CallController::class, 'safesubmit'])->name('call.safesubmit');

//呆滯天數警報頁面
Route::get('/day', [CallController::class, 'day'])->name('call.day');

//呆滯天數警報
Route::get('/daysubmit', [CallController::class, 'daysubmit']);

Route::post('/daysubmit', [CallController::class, 'daysubmit'])->name('call.daysubmit');
