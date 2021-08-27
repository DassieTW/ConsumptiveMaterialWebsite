<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicInformationController;

/*
|--------------------------------------------------------------------------
| Web Routes for basic type
|--------------------------------------------------------------------------
|
*/

// Root domain route
Route::get('/', [BasicInformationController::class, 'index'])->name('basic.index');

//basic information change or delete

Route::get('/inf', [BasicInformationController::class, 'changeordelete']);

Route::post('/inf', [BasicInformationController::class, 'changeordelete'])->name('basic.changeordelete');

//新增料件
Route::get('/new', [BasicInformationController::class, 'new']);

Route::post('/new', [BasicInformationController::class, 'new'])->name('basic.new');

//新增料件成功
Route::get('/newok', [BasicInformationController::class, 'newok']);


//儲位條碼查詢頁面
Route::get('/position', [BasicInformationController::class, 'position']);

Route::post('/position', [BasicInformationController::class, 'position'])->name('basic.position');

//料號條碼查詢頁面
Route::get('/number', [BasicInformationController::class, 'number']);

Route::post('/number', [BasicInformationController::class, 'number'])->name('basic.number');

//料件信息查詢頁面
Route::get('/material', [BasicInformationController::class, 'material'])->name('basic.material');

//Route::post('/material', [BasicInformationController::class, 'material'])->name('basic.material');

/*
//儲位條碼查詢
Route::get('/positionsearch', [BasicInformationController::class, 'searchposition']);

Route::post('/positionsearch', [BasicInformationController::class, 'searchposition'])->name('basic.searchposition');

//料號條碼查詢
Route::get('/numbersearch', [BasicInformationController::class, 'searchnumber']);

Route::post('/numbersearch', [BasicInformationController::class, 'searchnumber'])->name('basic.searchnumber');
*/

//料件信息查詢
Route::get('/materialsearch', [BasicInformationController::class, 'searchmaterial']);

Route::post('/materialsearch', [BasicInformationController::class, 'searchmaterial'])->name('basic.searchmaterial');

//料件信息刪除或修改
Route::post('/materialchangeordel', [BasicInformationController::class, 'materialchangeordel'])->name('basic.materialchangeordel');

//資料下載
Route::post('/download', [BasicInformationController::class, 'download'])->name('basic.download');


//新增料件上傳
Route::get('/upload', [BasicInformationController::class, 'uploadmaterialpage']);

Route::post('/upload', [BasicInformationController::class, 'uploadmaterial'])->name('basic.uploadmaterial');

//上傳資料新增至資料庫
Route::post('/uploadmaterial', [BasicInformationController::class, 'insertuploadmaterial'])->name('basic.insertuploadmaterial');

//基礎資料上傳
Route::get('/uploadbasic', [BasicInformationController::class, 'uploadbasicpage']);

Route::post('/uploadbasic', [BasicInformationController::class, 'uploadbasic'])->name('basic.uploadbasic');

//基礎資料上傳新增至資料庫
Route::post('/insertuploadbasic', [BasicInformationController::class, 'insertuploadbasic'])->name('basic.insertuploadbasic');
