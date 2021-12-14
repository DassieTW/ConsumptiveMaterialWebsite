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
use App\Http\Controllers\OutboundController;
use App\Models\Outbound;
use Illuminate\Support\Facades\Cookie;

/*
|--------------------------------------------------------------------------
| Web Routes for outbound type
|--------------------------------------------------------------------------
|
*/



//出庫
Route::get('/', function () {
    return view('outbound.index');
})->name('outbound.index')->middleware('can:viewOutbound,App\Models\Outbound');


//出庫-領料
Route::get('/pick', function () {
    return view('outbound.pick')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])
        ->with(['production' => 製程::cursor()])
        ->with(['line' => 線別::cursor()])
        ->with(['usereason' => 領用原因::cursor()]);
})->name('outbound.pick')->middleware('can:outboundPickup,App\Models\Outbound');

//Route::post('/pick', [OutboundController::class, 'pick'])->name('outbound.pick');

//出庫-退料
Route::get('/back', function () {
    return view('outbound.back')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])
        ->with(['production' => 製程::cursor()])
        ->with(['line' => 線別::cursor()])
        ->with(['backreason' => 退回原因::cursor()]);
})->name('outbound.back')->middleware('can:outboundReturn,App\Models\Outbound');

//Route::post('/back', [OutboundController::class, 'back'])->name('outbound.back');

//出庫-領料單頁面
Route::get('/picklist', function () {
    $datas =  DB::table('outbound')
        ->join('consumptive_material', 'outbound.料號', '=', 'consumptive_material.料號')
        ->wherenull('outbound.發料人員')
        ->select('outbound.*')
        ->get()->unique('領料單號');

    // dd($datas);
    return view('outbound.picklistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()]);
})->name('outbound.picklistpage')->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

//Route::post('/picklist', [OutboundController::class, 'picklistpage'])->name('outbound.picklistpage');

//出庫-領料單
Route::get('/picklistsub', function () {
    $datas =  DB::table('outbound')
        ->join('consumptive_material', 'outbound.料號', '=', 'consumptive_material.料號')
        ->wherenull('outbound.發料人員')
        ->select('outbound.*')
        ->get()->unique('領料單號');

    // dd($datas);
    return view('outbound.picklistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()]);
})->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

Route::post('/picklistsub', [OutboundController::class, 'picklist'])->name('outbound.picklist')->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

//出庫-退料單頁面
Route::get('/backlist', function () {
    $datas =  DB::table('出庫退料')
        ->join('consumptive_material', '出庫退料.料號', '=', 'consumptive_material.料號')
        ->wherenull('出庫退料.收料人員')
        ->select('出庫退料.*')
        ->get()->unique('退料單號');

    return view('outbound.backlistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()]);
})->name('outbound.backlistpage')->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

//Route::post('/backlist', [OutboundController::class, 'backlistpage'])->name('outbound.backlistpage');

//出庫-退料單
Route::get('/backlistsub', function(){
    $datas =  DB::table('出庫退料')
        ->join('consumptive_material', '出庫退料.料號', '=', 'consumptive_material.料號')
        ->wherenull('出庫退料.收料人員')
        ->select('出庫退料.*')
        ->get()->unique('退料單號');

    return view('outbound.backlistpage')->with(['data' => $datas])->with(['data1' => 發料部門::cursor()]);
})->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

Route::post('/backlistsub', [OutboundController::class, 'backlist'])->name('outbound.backlist')->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

//出庫-領料紀錄表
Route::get('/pickrecord', function () {
    return view('outbound.pickrecord')->with(['client' => 客戶別::cursor()])
        ->with(['production' => 製程::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('outbound.pickrecord')->middleware('can:outboundPickupRecord,App\Models\Outbound');

//Route::post('/pickrecord', [OutboundController::class, 'pickrecord'])->name('outbound.pickrecord');

//出庫-領料紀錄表查詢
Route::get('/pickrecordsearch', [OutboundController::class, 'pickrecordsearch'])->middleware('can:outboundPickupRecord,App\Models\Outbound');

Route::post('/pickrecordsearch', [OutboundController::class, 'pickrecordsearch'])->name('outbound.pickrecordsearch')->middleware('can:outboundPickupRecord,App\Models\Outbound');

//出庫-退料紀錄表
Route::get('/backrecord', function () {
    return view('outbound.backrecord')->with(['client' => 客戶別::cursor()])
        ->with(['production' => 製程::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('outbound.backrecord')->middleware('can:outboundReturnRecord,App\Models\Outbound');

//Route::post('/backrecord', [OutboundController::class, 'backrecord'])->name('outbound.backrecord');

//出庫-退料紀錄表查詢
Route::get('/backrecordsearch', [OutboundController::class, 'backrecordsearch'])->middleware('can:outboundReturnRecord,App\Models\Outbound');

Route::post('/backrecordsearch', [OutboundController::class, 'backrecordsearch'])->name('outbound.backrecordsearch')->middleware('can:outboundReturnRecord,App\Models\Outbound');

//領料添加
Route::post('/pickadd', [OutboundController::class, 'pickadd'])->name('outbound.pickadd')->middleware('can:outboundPickup,App\Models\Outbound');

//退料添加
Route::post('/backadd', [OutboundController::class, 'backadd'])->name('outbound.backadd')->middleware('can:outboundReturn,App\Models\Outbound');

//提交領料
Route::post('/pickaddsubmit', [OutboundController::class, 'pickaddsubmit'])->name('outbound.pickaddsubmit')->middleware('can:outboundPickup,App\Models\Outbound');

//提交退料
Route::post('/backaddsubmit', [OutboundController::class, 'backaddsubmit'])->name('outbound.backaddsubmit')->middleware('can:outboundReturn,App\Models\Outbound');

//提交領料單
Route::post('/picklistsubmit', [OutboundController::class, 'picklistsubmit'])->name('outbound.picklistsubmit')->middleware('can:outboundPickupSerialNum,App\Models\Outbound');

//提交退料單
Route::post('/backlistsubmit', [OutboundController::class, 'backlistsubmit'])->name('outbound.backlistsubmit')->middleware('can:outboundReturnSerialNum,App\Models\Outbound');

//資料下載
Route::post('/download', [OutboundController::class, 'download'])->name('outbound.download')->middleware('can:viewOutbound,App\Models\Outbound');
