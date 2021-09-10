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

Route::get('/isn_search', function () {
    return view('barcode.barcode_gen_isn_from_db');
});

Route::post('/search_isn', [BarcodeDisplayController::class, 'searchISN']);
Route::post('/search_loc', [BarcodeDisplayController::class, 'searchLoc']);

Route::get('/loc_search', function () {
    return view('barcode.barcode_gen_loc_from_db');
});

Route::post('/barcode_isn', [BarcodeDisplayController::class, 'postBack'])->name('isn_barcode_gen');
Route::post('/barcode_loc', [BarcodeDisplayController::class, 'postBack_loc'])->name('loc_barcode_gen');

// 清除暫存圖片
Route::post('/seenDelete', [BarcodeDisplayController::class, 'delTempImg'])->name('seenDelete');

// 上傳excel檔 批量上傳條碼資料
Route::post('/batch_upload', [BarcodeDisplayController::class, 'batchUpload']);

// 用php office解析excel檔
Route::post('/batch_upload_decompose', [BarcodeDisplayController::class, 'decompose']);

// post並列印出所有條碼
Route::post('/printBarcode', [BarcodeDisplayController::class, 'printBarcode']);
Route::get('/printingPage', function () {
    return view('barcode.barcode_printing_page');
});

// 清除所有暫存條碼（列印後）
Route::post('/cleanupAllBarcodes', [BarcodeDisplayController::class, 'cleanupAllBarcodes']);