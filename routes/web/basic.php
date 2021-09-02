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
Route::get('/', [BasicInformationController::class, 'index'])->name('basic.index')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//basic information change or delete

Route::get('/inf', [BasicInformationController::class, 'changeordelete'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/inf', [BasicInformationController::class, 'changeordelete'])->name('basic.changeordelete')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//新增料件
Route::get('/new', [BasicInformationController::class, 'new'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/new', [BasicInformationController::class, 'new'])->name('basic.new')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//新增料件成功
Route::get('/newok', [BasicInformationController::class, 'newok'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');


//儲位條碼查詢頁面
Route::get('/position', [BasicInformationController::class, 'position'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/position', [BasicInformationController::class, 'position'])->name('basic.position')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//料號條碼查詢頁面
Route::get('/number', [BasicInformationController::class, 'number'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/number', [BasicInformationController::class, 'number'])->name('basic.number')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//料件信息查詢頁面
Route::get('/material', [BasicInformationController::class, 'material'])->name('basic.material')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//Route::post('/material', [BasicInformationController::class, 'material'])->name('basic.material');

//料件信息查詢
Route::get('/materialsearch', [BasicInformationController::class, 'searchmaterial'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/materialsearch', [BasicInformationController::class, 'searchmaterial'])->name('basic.searchmaterial')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//料件信息刪除或修改
Route::post('/materialchangeordel', [BasicInformationController::class, 'materialchangeordel'])->name('basic.materialchangeordel')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//資料下載
Route::post('/download', [BasicInformationController::class, 'download'])->name('basic.download')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');


//新增料件上傳
Route::get('/upload', [BasicInformationController::class, 'uploadmaterialpage'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/upload', [BasicInformationController::class, 'uploadmaterial'])->name('basic.uploadmaterial')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//上傳資料新增至資料庫
Route::post('/uploadmaterial', [BasicInformationController::class, 'insertuploadmaterial'])->name('basic.insertuploadmaterial')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//基礎資料上傳
Route::get('/uploadbasic', [BasicInformationController::class, 'uploadbasicpage'])->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/uploadbasic', [BasicInformationController::class, 'uploadbasic'])->name('basic.uploadbasic')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//基礎資料上傳新增至資料庫
Route::post('/insertuploadbasic', [BasicInformationController::class, 'insertuploadbasic'])->name('basic.insertuploadbasic')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');
