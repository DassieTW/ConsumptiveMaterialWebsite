<?php

use App\Models\發料部門;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallController;

/*
|--------------------------------------------------------------------------
| Web Routes for call type
|--------------------------------------------------------------------------
|
*/

/*//index
Route::get('/', [CallController::class, 'index'])->name('call.index')->middleware('can:viewAlarm,App\Models\Inventory');
*/

//安全庫存警報頁面
Route::get('/safe', function () {
    return view('call.safepage')->with(['sends' => 發料部門::cursor()]);

})->name('call.safe')->middleware('can:viewAlarm,App\Models\Inventory');

//安全庫存警報
Route::get('/safesubmit', [CallController::class, 'safesubmit'])->middleware('can:viewAlarm,App\Models\Inventory');

Route::post('/safesubmit', [CallController::class, 'safesubmit'])->name('call.safesubmit')->middleware('can:viewAlarm,App\Models\Inventory');

//呆滯天數警報頁面
Route::get('/day', function () {
    return view('call.daypage')->with(['sends' => 發料部門::cursor()]);
})->name('call.day')->middleware('can:viewAlarm,App\Models\Inventory');

//呆滯天數警報
Route::get('/daysubmit', [CallController::class, 'daysubmit'])->middleware('can:viewAlarm,App\Models\Inventory');

Route::post('/daysubmit', [CallController::class, 'daysubmit'])->name('call.daysubmit')->middleware('can:viewAlarm,App\Models\Inventory');
