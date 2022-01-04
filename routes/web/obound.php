<?php
use App\Models\人員信息;
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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OboundController;
use App\Models\O庫;
use App\Models\O庫Material;
use App\Models\O庫Inbound;
use App\Models\O庫Inventory;
use App\Models\O庫不良品Inventory;
use App\Models\O庫Outbound;
use App\Models\O庫出庫退料;

/*
|--------------------------------------------------------------------------
| Web Routes for obound type
|--------------------------------------------------------------------------
|
*/

//O庫
Route::get('/', function () {
    return view('obound.index');
})->name('obound.index')->middleware('can:viewObound,App\Models\O庫');

//新增料件

Route::get('/new', function () {
    return view('obound.new');
})->name('obound.new')->middleware('can:oboundNewMat,App\Models\O庫');

//新增料件批量上傳
Route::get('/uploadmaterial', function () {
    return view('obound.new');
})->middleware('can:oboundNewMat,App\Models\O庫');

Route::post('/uploadmaterial', [OboundController::class, 'uploadmaterial'])->name('obound.uploadmaterial')->middleware('can:oboundNewMat,App\Models\O庫');

//基礎資料上傳新增至資料庫
Route::post('/insertuploadmaterial', [OboundController::class, 'insertuploadmaterial'])->name('obound.insertuploadmaterial')->middleware('can:oboundNewMat,App\Models\O庫');

//料件信息查詢頁面
Route::get('/material', function () {
    return view('obound.searchmaterial');
})->name('obound.material')->middleware('can:oboundMatSearch,App\Models\O庫');


//料件信息查詢
Route::get('/materialsearch', function () {
    return view('obound.searchmaterialok')->with(['data' => O庫Material::cursor()]);
})->middleware('can:oboundMatSearch,App\Models\O庫');

Route::post('/materialsearch', [OboundController::class, 'searchmaterial'])->name('obound.searchmaterial')->middleware('can:oboundMatSearch,App\Models\O庫');


//O庫-入庫頁面
Route::get('/inbound', function () {
    return view('obound.inbound')->with(['client' => 客戶別::cursor()])
        ->with(['inreason' => 入庫原因::cursor()])
        ->with(['bounds' => O庫::cursor()])
        ->with(['peoples' => 人員信息::cursor()])
        ->with(['checks' => 人員信息::cursor()]);
})->name('obound.inbound')->middleware('can:oboundIn,App\Models\O庫');

//O庫-入庫新增
Route::post('/inboundnew', [OboundController::class, 'inboundnew'])->name('obound.inboundnew')->middleware('can:oboundIn,App\Models\O庫');

//O庫-入庫新增提交
Route::post('/inboundnewsubmit', [OboundController::class, 'inboundnewsubmit'])->name('obound.inboundnewsubmit')->middleware('can:oboundIn,App\Models\O庫');

//O庫-入庫查詢頁面
Route::get('/inboundsearch', function(){
    return view('obound.inboundsearch')->with(['client' => 客戶別::cursor()])
    ->with(['bound' => O庫::cursor()]);
})->name('obound.inboundsearch')->middleware('can:oboundInSearch,App\Models\O庫');

//O庫-入庫查詢ok
Route::get('/inboundsearchok', [OboundController::class, 'inboundsearchok'])->middleware('can:oboundInSearch,App\Models\O庫');

Route::post('/inboundsearchok', [OboundController::class, 'inboundsearchok'])->name('obound.inboundsearchok')->middleware('can:oboundInSearch,App\Models\O庫');

//O庫-入庫查詢刪除
Route::post('/delete', [OboundController::class, 'delete'])->name('obound.delete')->middleware('can:oboundInSearch,App\Models\O庫');

//O庫-庫存上傳頁面
Route::get('/upload', function(){
    return view('obound.upload');
})->name('obound.upload')->middleware('can:oboundStockUpload,App\Models\O庫');

//O庫-新增料件上傳
Route::get('/uploadinventory', function(){
    return view('obound.upload');
})->middleware('can:oboundStockUpload,App\Models\O庫');

Route::post('/uploadinventory', [OboundController::class, 'uploadinventory'])->name('obound.uploadinventory')->middleware('can:oboundStockUpload,App\Models\O庫');

//O庫-上傳資料新增至資料庫
Route::post('/insertuploadinventory', [OboundController::class, 'insertuploadinventory'])->name('obound.insertuploadinventory')->middleware('can:oboundStockUpload,App\Models\O庫');

//O庫-庫存查詢頁面
Route::get('/searchstock', function(){
    return view('obound.searchstock')->with(['client' => 客戶別::cursor()])
                ->with(['bound' => O庫::cursor()]);
})->name('obound.searchstock')->middleware('can:oboundStockSearch,App\Models\O庫');

//O庫-庫存查詢成功
Route::get('/searchstocksubmit', [OboundController::class, 'searchstocksubmit'])->middleware('can:oboundStockSearch,App\Models\O庫');

Route::post('/searchstocksubmit', [OboundController::class, 'searchstocksubmit'])->name('obound.searchstocksubmit')->middleware('can:oboundStockSearch,App\Models\O庫');



//O庫-領料
Route::get('/pick', [OboundController::class, 'pick'])->name('obound.pick')->middleware('can:oboundPickup,App\Models\O庫');

//O庫-退料
Route::get('/back', [OboundController::class, 'back'])->name('obound.back')->middleware('can:oboundReturn,App\Models\O庫');

//O庫-領料單頁面
Route::get('/picklist', [OboundController::class, 'picklistpage'])->name('obound.picklistpage')->middleware('can:oboundPickupSerialNum,App\Models\O庫');

//O庫-退料單頁面
Route::get('/backlist', [OboundController::class, 'backlistpage'])->name('obound.backlistpage')->middleware('can:oboundReturnSerialNum,App\Models\O庫');

//O庫-領料單
Route::get('/picklistsub', [OboundController::class, 'picklist'])->middleware('can:oboundPickupSerialNum,App\Models\O庫');

Route::post('/picklistsub', [OboundController::class, 'picklist'])->name('obound.picklist')->middleware('can:oboundPickupSerialNum,App\Models\O庫');

//O庫-退料單
Route::get('/backlistsub', [OboundController::class, 'backlist'])->middleware('can:oboundReturnSerialNum,App\Models\O庫');

Route::post('/backlistsub', [OboundController::class, 'backlist'])->name('obound.backlist')->middleware('can:oboundReturnSerialNum,App\Models\O庫');

//O庫-領料記錄表頁面
Route::get('/pickrecord', [OboundController::class, 'pickrecord'])->name('obound.pickrecord')->middleware('can:oboundPickupRecord,App\Models\O庫');

//O庫-退料紀錄表頁面
Route::get('/backrecord', [OboundController::class, 'backrecord'])->name('obound.backrecord')->middleware('can:oboundReturnRecord,App\Models\O庫');

//O庫-領料紀錄表查詢
Route::get('/pickrecordsearch', [OboundController::class, 'pickrecordsearch'])->middleware('can:oboundPickupRecord,App\Models\O庫');

Route::post('/pickrecordsearch', [OboundController::class, 'pickrecordsearch'])->name('obound.pickrecordsearch')->middleware('can:oboundPickupRecord,App\Models\O庫');

//O庫-退料紀錄表查詢
Route::get('/backrecordsearch', [OboundController::class, 'backrecordsearch'])->middleware('can:oboundReturnRecord,App\Models\O庫');

Route::post('/backrecordsearch', [OboundController::class, 'backrecordsearch'])->name('obound.backrecordsearch')->middleware('can:oboundReturnRecord,App\Models\O庫');

//O庫-領料添加
Route::post('/pickadd', [OboundController::class, 'pickadd'])->name('obound.pickadd')->middleware('can:oboundPickup,App\Models\O庫');

//O庫-領料添加頁面
Route::get('/pickaddok', [OboundController::class, 'pickaddok'])->middleware('can:oboundPickup,App\Models\O庫');

//O庫-退料添加
Route::post('/backadd', [OboundController::class, 'backadd'])->name('obound.backadd')->middleware('can:oboundReturn,App\Models\O庫');

//O庫-退料添加頁面
Route::get('/backaddok', [OboundController::class, 'backaddok'])->middleware('can:oboundReturn,App\Models\O庫');

//O庫-提交領料
Route::post('/pickaddsubmit', [OboundController::class, 'pickaddsubmit'])->name('obound.pickaddsubmit')->middleware('can:oboundPickup,App\Models\O庫');

//O庫-提交退料
Route::post('/backaddsubmit', [OboundController::class, 'backaddsubmit'])->name('obound.backaddsubmit')->middleware('can:oboundReturn,App\Models\O庫');

//O庫-提交領料單
Route::post('/picklistsubmit', [OboundController::class, 'picklistsubmit'])->name('obound.picklistsubmit')->middleware('can:oboundPickupSerialNum,App\Models\O庫');

//O庫-提交退料單
Route::post('/backlistsubmit', [OboundController::class, 'backlistsubmit'])->name('obound.backlistsubmit')->middleware('can:oboundReturnSerialNum,App\Models\O庫');

//O庫-領料添加成功頁面
Route::get('/pickaddsubmitok', [OboundController::class, 'pickaddsubmitok'])->middleware('can:oboundPickup,App\Models\O庫');

//O庫-退料添加成功頁面
Route::get('/backaddsubmitok', [OboundController::class, 'backaddsubmitok'])->middleware('can:oboundReturn,App\Models\O庫');

//O庫-領料單添加成功頁面
Route::get('/picklistsubmitok', [OboundController::class, 'picklistsubmitok'])->middleware('can:oboundPickupSerialNum,App\Models\O庫');

//O庫-退料單添加成功頁面
Route::get('/backlistsubmitok', [OboundController::class, 'backlistsubmitok'])->middleware('can:oboundReturnSerialNum,App\Models\O庫');

//O庫-資料下載
Route::post('/download', [OboundController::class, 'download'])->name('obound.download')->middleware('can:viewObound,App\Models\O庫');
