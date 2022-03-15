<?php

use App\Models\入庫原因;
use App\Models\客戶別;
use App\Models\發料部門;
use App\Models\製程;
use App\Models\領用原因;
use App\Models\領用部門;
use App\Models\廠別;
use App\Models\線別;
use App\Models\機種;
use App\Models\儲位;
use App\Models\退回原因;
use App\Models\O庫;
use App\Models\ConsumptiveMaterial;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicInformationController;

/*
|--------------------------------------------------------------------------
| Web Routes for basic type
|--------------------------------------------------------------------------
|
*/

Route::get('/', function () {
    return view('basic.index')->with(['factorys' => 廠別::cursor()])
        ->with(['clients' => 客戶別::cursor()])
        ->with(['machines' => 機種::cursor()])
        ->with(['productions' => 製程::cursor()])
        ->with(['lines' => 線別::cursor()])
        ->with(['uses' => 領用部門::cursor()])
        ->with(['usereasons' => 領用原因::cursor()])
        ->with(['inreasons' => 入庫原因::cursor()])
        ->with(['positions' => 儲位::cursor()])
        ->with(['sends' => 發料部門::cursor()])
        ->with(['os' => O庫::cursor()])
        ->with(['backs' => 退回原因::cursor()]);
    //return view('basic.index', ['factorys' => 廠別::cursor(), 'clients' => 客戶別::cursor() ]);

})->name('basic.index')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');




//basic information change or delete


Route::post('/changeordelete', [BasicInformationController::class, 'changeordelete'])->name('basic.changeordelete')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//新增料件
Route::get('/new', function () {
    return view('basic.new')->with(['data' => 發料部門::cursor()]);
})->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/new', [BasicInformationController::class, 'new'])->name('basic.new')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//新增料件提交
Route::post('/materialaddsubmit', [BasicInformationController::class, 'materialaddsubmit'])->name('basic.materialaddsubmit')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//料件信息查詢頁面
Route::get('/material', function () {
    return view('basic.searchmaterial');
})->name('basic.material')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::get('/upload', function () {
    return view('basic.new')->with(['data' => 發料部門::cursor()]);
})->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');


//料件信息查詢
Route::get('/materialsearch', function () {
    $datas = DB::table('consumptive_material')->get();

    return view("basic.searchmaterialok")
        ->with(['data' => $datas])
        ->with(['sends' => 發料部門::cursor()]);
})->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/materialsearch', [BasicInformationController::class, 'searchmaterial'])->name('basic.searchmaterial')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//料件信息刪除或修改
Route::post('/materialchangeordel', [BasicInformationController::class, 'materialchangeordel'])->name('basic.materialchangeordel')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//資料下載
Route::post('/download', [BasicInformationController::class, 'download'])->name('basic.download')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//新增料件上傳
Route::post('/upload', [BasicInformationController::class, 'uploadmaterial'])->name('basic.uploadmaterial')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//上傳資料新增至資料庫
Route::post('/insertuploadmaterial', [BasicInformationController::class, 'insertuploadmaterial'])->name('basic.insertuploadmaterial')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//基礎資料上傳
Route::get('/uploadbasic', function () {
    return view('basic.index')->with(['factorys' => 廠別::cursor()])
        ->with(['clients' => 客戶別::cursor()])
        ->with(['machines' => 機種::cursor()])
        ->with(['productions' => 製程::cursor()])
        ->with(['lines' => 線別::cursor()])
        ->with(['uses' => 領用部門::cursor()])
        ->with(['usereasons' => 領用原因::cursor()])
        ->with(['inreasons' => 入庫原因::cursor()])
        ->with(['positions' => 儲位::cursor()])
        ->with(['sends' => 發料部門::cursor()])
        ->with(['os' => O庫::cursor()])
        ->with(['backs' => 退回原因::cursor()]);
})->name('basic.insertuploadbasic')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

Route::post('/uploadbasic', [BasicInformationController::class, 'uploadbasic'])->name('basic.uploadbasic')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');

//基礎資料上傳新增至資料庫

Route::post('/insertuploadbasic', [BasicInformationController::class, 'insertuploadbasic'])->name('basic.insertuploadbasic')->middleware('can:viewBasicInfo,App\Models\ConsumptiveMaterial');
