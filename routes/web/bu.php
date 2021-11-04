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
use App\Http\Controllers\BUController;

/*
|--------------------------------------------------------------------------
| Web Routes for bu type
|--------------------------------------------------------------------------
|
*/

//index
Route::get('/',function () {
    return view('bu.index');
})->name('bu.index');

//搜尋呆滯庫存
Route::get('/sluggish', [BUController::class, 'sluggish'])->name('bu.sluggish');

//廠區庫存調撥
Route::post('/sluggishmaterial', [BUController::class, 'sluggishmaterial'])->name('bu.sluggishmaterial');

//新增調撥單
Route::post('/transsluggish', [BUController::class, 'transsluggish'])->name('bu.transsluggish');

//調撥單查詢頁面
Route::get('/searchlist', function () {
return view('bu.searchlist')->with(['factory' => 廠別::cursor()])->with(['factory1' => 廠別::cursor()]);
})->name('bu.searchlist');

//調撥單查詢
Route::get('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');

Route::post('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');

//調撥單查詢-刪除
Route::post('/delete', [BUController::class, 'delete'])->name('bu.delete');

//調撥_撥出單頁面
Route::get('/outlist', [BUController::class, 'outlistpage'])->name('bu.outlistpage');

//調撥_撥出單
Route::post('/outlistsub', [BUController::class, 'outlist'])->name('bu.outlist');

//調撥_撥出單提交
Route::post('/outlistsubmit', [BUController::class, 'outlistsubmit'])->name('bu.outlistsubmit');

//調撥_接收單頁面
Route::get('/picklist', [BUController::class, 'picklistpage'])->name('bu.picklistpage');

//調撥_接收單
Route::post('/picklistsub', [BUController::class, 'picklist'])->name('bu.picklist');

//調撥_接收單提交
Route::post('/picklistsubmit', [BUController::class, 'picklistsubmit'])->name('bu.picklistsubmit');


//調撥明細查詢頁面
Route::get('/searchdetail', [BUController::class, 'searchdetail'])->name('bu.searchdetail');

//調撥單查詢
Route::get('/searchdetailsub', [BUController::class, 'searchdetailsub'])->name('bu.searchdetailsub');

Route::post('/searchdetailsub', [BUController::class, 'searchdetailsub'])->name('bu.searchdetailsub');

//呆滯庫存下載
Route::post('/download', [BUController::class, 'download'])->name('bu.download');

//調撥單查詢下載
Route::post('/downloadlist', [BUController::class, 'downloadlist'])->name('bu.downloadlist');

//廠區庫存調撥頁面
Route::get('/material', [BUController::class, 'material'])->name('bu.material');

/*//廠區庫存調撥
Route::get('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');

Route::post('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');*/
