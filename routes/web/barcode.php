<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeDisplayController;
use App\Http\Controllers\AjaxController;


/*
|--------------------------------------------------------------------------
| Web Routes for barcode pages
|--------------------------------------------------------------------------
|
*/

// Root domain route
Route::get('/', function () {
    return view('barcode.barcode_gen_page');
})->name('barcode.index');

Route::post('/barcode_isn', [BarcodeDisplayController::class, 'postBack'])->name('isn_barcode_gen');
Route::post('/barcode_loc', [BarcodeDisplayController::class, 'postBack_loc'])->name('loc_barcode_gen');
Route::post('/seenDelete', [AjaxController::class, 'delTempImg'])->name('seenDelete');
Route::post('/batch_upload', [BarcodeDisplayController::class, 'batchUpload']);
Route::post('/batch_upload_decompose', [BarcodeDisplayController::class, 'decompose']);
