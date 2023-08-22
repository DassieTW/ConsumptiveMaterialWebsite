<?php

use App\Models\入庫原因;
use App\Models\客戶別;
use App\Models\發料部門;
use App\Models\製程;
use App\Models\領用原因;
use App\Models\領用部門;
use App\Models\廠別;
use App\Models\人員信息;
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
})->middleware('can:oboundNewMat,App\Models\O庫');

Route::post('/new', [OboundController::class, 'new'])->name('obound.new')->middleware('can:oboundNewMat,App\Models\O庫');

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
Route::get('/inboundsearch', function () {
    return view('obound.inboundsearch')->with(['client' => 客戶別::cursor()])
        ->with(['bound' => O庫::cursor()]);
})->name('obound.inboundsearch')->middleware('can:oboundInSearch,App\Models\O庫');

//O庫-入庫查詢ok
Route::get('/inboundsearchok', function () {
    return view("obound.inboundsearchok");
})->middleware('can:oboundInSearch,App\Models\O庫');

Route::post('/inboundsearchok', [OboundController::class, 'inboundsearchok'])->name('obound.inboundsearchok')->middleware('can:oboundInSearch,App\Models\O庫');

//O庫-入庫查詢刪除
Route::post('/delete', [OboundController::class, 'delete'])->name('obound.delete')->middleware('can:oboundInSearch,App\Models\O庫');

//O庫-庫存上傳頁面
Route::get('/upload', function () {
    return view('obound.upload');
})->name('obound.upload')->middleware('can:oboundStockUpload,App\Models\O庫');

//O庫-新增料件上傳
Route::get('/uploadinventory', function () {
    return view('obound.upload');
})->middleware('can:oboundStockUpload,App\Models\O庫');

Route::post('/uploadinventory', [OboundController::class, 'uploadinventory'])->name('obound.uploadinventory')->middleware('can:oboundStockUpload,App\Models\O庫');

//O庫-上傳資料新增至資料庫
Route::post('/insertuploadinventory', [OboundController::class, 'insertuploadinventory'])->name('obound.insertuploadinventory')->middleware('can:oboundStockUpload,App\Models\O庫');

//O庫-庫存查詢頁面
Route::get('/searchstock', function () {
    return view('obound.searchstock')->with(['client' => 客戶別::cursor()])
        ->with(['bound' => O庫::cursor()]);
})->name('obound.searchstock')->middleware('can:oboundStockSearch,App\Models\O庫');

//O庫-庫存查詢成功
Route::get('/searchstocksubmit', function () {
    return view("obound.searchstockok");
})->middleware('can:oboundStockSearch,App\Models\O庫');

//O庫-領料
Route::get('/pick', function () {
    return view('obound.pick')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])
        ->with(['production' => 製程::cursor()])
        ->with(['line' => 線別::cursor()])
        ->with(['usereason' => 領用原因::cursor()]);
})->name('obound.pick')->middleware('can:oboundPickup,App\Models\O庫');

//Route::post('/pick', [OboundController::class, 'pick'])->name('outbound.pick');

//O庫-退料
Route::get('/back', function () {
    return view('obound.back')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])
        ->with(['production' => 製程::cursor()])
        ->with(['line' => 線別::cursor()])
        ->with(['backreason' => 退回原因::cursor()]);
})->name('obound.back')->middleware('can:oboundReturn,App\Models\O庫');

//Route::post('/back', [OboundController::class, 'back'])->name('outbound.back');

//O庫-領料單頁面
Route::get('/picklist', function () {
    $datas =  DB::table('O庫outbound')
        ->join('O庫_material', 'O庫outbound.料號', '=', 'O庫_material.料號')
        ->wherenull('O庫outbound.發料人員')
        ->select('O庫outbound.*')
        ->get()->unique('領料單號');

    //dd($datas);
    $num = count($datas);
    return view('obound.picklistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()])->with(['num' => $num]);
})->name('obound.picklistpage')->middleware('can:oboundPickupSerialNum,App\Models\O庫');

//Route::post('/picklist', [OboundController::class, 'picklistpage'])->name('outbound.picklistpage');

//O庫-領料單
Route::get('/picklistsub', function () {
    $datas =  DB::table('O庫outbound')
        ->join('O庫_material', 'O庫outbound.料號', '=', 'O庫_material.料號')
        ->wherenull('O庫outbound.發料人員')
        ->select('O庫outbound.*')
        ->get()->unique('領料單號');

    // dd($datas);
    $num = count($datas);
    return view('obound.picklistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()])->with(['num' => $num]);
})->middleware('can:oboundPickupSerialNum,App\Models\O庫');

Route::post('/picklistsub', [OboundController::class, 'picklist'])->name('obound.picklist')->middleware('can:oboundPickupSerialNum,App\Models\O庫');

//O庫-退料單頁面
Route::get('/backlist', function () {
    $datas =  DB::table('O庫出庫退料')
        ->join('O庫_material', 'O庫出庫退料.料號', '=', 'O庫_material.料號')
        ->wherenull('O庫出庫退料.收料人員')
        ->select('O庫出庫退料.*')
        ->get()->unique('退料單號');
    $num = count($datas);
    return view('obound.backlistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()])->with(['num' => $num]);
})->name('obound.backlistpage')->middleware('can:oboundReturnSerialNum,App\Models\O庫');

//Route::post('/backlist', [OboundController::class, 'backlistpage'])->name('outbound.backlistpage');

//O庫-退料單
Route::get('/backlistsub', function () {
    $datas =  DB::table('O庫出庫退料')
        ->join('O庫_material', 'O庫出庫退料.料號', '=', 'O庫_material.料號')
        ->wherenull('O庫出庫退料.收料人員')
        ->select('O庫出庫退料.*')
        ->get()->unique('退料單號');
    $num = count($datas);
    return view('obound.backlistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()])->with(['num' => $num]);
})->middleware('can:oboundReturnSerialNum,App\Models\O庫');

Route::post('/backlistsub', [OboundController::class, 'backlist'])->name('obound.backlist')->middleware('can:oboundReturnSerialNum,App\Models\O庫');

//O庫-領料紀錄表
Route::get('/pickrecord', function () {
    return view('obound.pickrecord')->with(['client' => 客戶別::cursor()])
        ->with(['production' => 製程::cursor()]);
})->name('obound.pickrecord')->middleware('can:oboundPickupRecord,App\Models\O庫');

//Route::post('/pickrecord', [OboundController::class, 'pickrecord'])->name('outbound.pickrecord');

//O庫-領料紀錄表查詢
Route::get('/pickrecordsearch', function () {
    return view("obound.pickrecordsearchok");
})->middleware('can:oboundPickupRecord,App\Models\O庫');

//O庫-退料紀錄表
Route::get('/backrecord', function () {
    return view('obound.backrecord')->with(['client' => 客戶別::cursor()])
        ->with(['production' => 製程::cursor()]);
})->name('obound.backrecord')->middleware('can:oboundReturnRecord,App\Models\O庫');

//Route::post('/backrecord', [OboundController::class, 'backrecord'])->name('outbound.backrecord');

//O庫-退料紀錄表查詢
Route::get('/backrecordsearch', function () {
    return view("obound.backrecordsearchok");
})->middleware('can:oboundReturnRecord,App\Models\O庫');

//領料添加
Route::post('/pickadd', [OboundController::class, 'pickadd'])->name('obound.pickadd')->middleware('can:oboundPickup,App\Models\O庫');

//退料添加
Route::post('/backadd', [OboundController::class, 'backadd'])->name('obound.backadd')->middleware('can:oboundReturn,App\Models\O庫');

//提交領料
Route::post('/pickaddsubmit', [OboundController::class, 'pickaddsubmit'])->name('obound.pickaddsubmit')->middleware('can:oboundPickup,App\Models\O庫');

//提交退料
Route::post('/backaddsubmit', [OboundController::class, 'backaddsubmit'])->name('obound.backaddsubmit')->middleware('can:oboundReturn,App\Models\O庫');

//提交領料單
Route::post('/picklistsubmit', [OboundController::class, 'picklistsubmit'])->name('obound.picklistsubmit')->middleware('can:oboundPickupSerialNum,App\Models\O庫');

//提交退料單
Route::post('/backlistsubmit', [OboundController::class, 'backlistsubmit'])->name('obound.backlistsubmit')->middleware('can:oboundReturnSerialNum,App\Models\O庫');

//資料下載
Route::post('/download', [OboundController::class, 'download'])->name('obound.download')->middleware('can:viewObound,App\Models\O庫');
