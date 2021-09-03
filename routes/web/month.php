<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonthController;

/*
|--------------------------------------------------------------------------
| Web Routes for month type
|--------------------------------------------------------------------------
|
*/


//月請購
Route::get('/', [MonthController::class, 'index'])->name('month.index')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//匯入非月請購資料頁面
Route::get('/importnotmonth', [MonthController::class, 'importnotmonth'])->name('month.importnotmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//匯入月請購資料頁面
Route::get('/importmonth', [MonthController::class, 'importmonth'])->name('month.importmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SRM單數量頁面
Route::get('/srm', [MonthController::class, 'srm'])->name('month.srm')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SRM單(查詢)
Route::get('/srmsearch', [MonthController::class, 'srmsearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/srmsearch', [MonthController::class, 'srmsearch'])->name('month.srmsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SRM單(提交)
Route::post('/srmsubmit', [MonthController::class, 'srmsubmit'])->name('month.srmsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購查詢
Route::get('/notmonthinf', [MonthController::class, 'notmonthsearchoradd'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/notmonthinf', [MonthController::class, 'notmonthsearchoradd'])->name('month.notmonthsearchoradd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購查詢
Route::get('/monthinf', [MonthController::class, 'monthsearchoradd'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/monthinf', [MonthController::class, 'monthsearchoradd'])->name('month.monthsearchoradd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購刪除
Route::post('/monthdelete', [MonthController::class, 'monthdelete'])->name('month.monthdelete')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購添加
Route::post('/monthadd', [MonthController::class, 'monthadd'])->name('month.monthadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購添加
Route::get('/notmonthadd', [MonthController::class, 'notmonthadd'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/notmonthadd', [MonthController::class, 'notmonthadd'])->name('month.notmonthadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購添加成功
Route::get('/notmonthaddok', [MonthController::class, 'notmonthaddok'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購添加成功
Route::get('/monthaddok', [MonthController::class, 'monthaddok'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//請購單頁面
Route::get('/buylist', [MonthController::class, 'buylist'])->name('month.buylist')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//Route::get('/buylistmake', [MonthController::class, 'buylistmake']);

Route::post('/buylistmake', [MonthController::class, 'buylistmake'])->name('month.buylistmake')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/buylistsubmit', [MonthController::class, 'buylistsubmit'])->name('month.buylistsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//在途量(查詢)頁面
Route::get('/transit', [MonthController::class, 'transit'])->name('month.transit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//在途量(查詢)
Route::get('/transitsearch', [MonthController::class, 'transitsearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/transitsearch', [MonthController::class, 'transitsearch'])->name('month.transitsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SXB單(查詢)頁面
Route::get('/sxb', [MonthController::class, 'sxb'])->name('month.sxb')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SXB單(查詢)
Route::get('/sxbsearch', [MonthController::class, 'sxbsearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/sxbsearch', [MonthController::class, 'sxbsearch'])->name('month.sxbsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//料號單耗(新增)頁面
Route::get('/consumeadd', [MonthController::class, 'consumeadd'])->name('month.consumeadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(新增)頁面
Route::get('/standadd', [MonthController::class, 'standadd'])->name('month.standadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');


//料號單耗(新增)
Route::post('/consumenew', [MonthController::class, 'consumenew'])->name('month.consumenew')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(新增)
Route::post('/standnew', [MonthController::class, 'standnew'])->name('month.standnew')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//料號單耗(新增)添加頁面
Route::get('/consumenewok', [MonthController::class, 'consumenewok'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(新增)添加頁面
Route::get('/standnewok', [MonthController::class, 'standnewok'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');


//提交料號單耗
Route::post('/consumenewsubmit', [MonthController::class, 'consumenewsubmit'])->name('month.consumenewsubmitok')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//領料料號單耗成功頁面
Route::get('/consumenewsubmitok', [MonthController::class, 'consumenewsubmitok'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//提交站位人力
Route::post('/standnewsubmit', [MonthController::class, 'standnewsubmit'])->name('month.standnewsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//領料站位人力成功頁面
Route::get('/standnewsubmitok', [MonthController::class, 'standnewsubmitok'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');


//料號單耗(查詢與修改)頁面
Route::get('/consume', [MonthController::class, 'consume'])->name('month.consume')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(查詢與修改)頁面
Route::get('/stand', [MonthController::class, 'stand'])->name('month.stand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//料號單耗(查詢)ok
Route::get('/consumesearch', [MonthController::class, 'consumesearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/consumesearch', [MonthController::class, 'consumesearch'])->name('month.consumesearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(查詢)ok
Route::get('/standsearch', [MonthController::class, 'standsearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/standsearch', [MonthController::class, 'standsearch'])->name('month.standsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//料號單耗(刪除或修改)
Route::post('/consumechangeordelete', [MonthController::class, 'consumechangeordelete'])->name('month.consumechangeordelete')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(刪除或修改)
Route::post('/standchangeordelete', [MonthController::class, 'standchangeordelete'])->name('month.standchangeordelete')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//新增單耗上傳
Route::get('/uploadconsume', [MonthController::class, 'uploadconsumepage'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadconsume', [MonthController::class, 'uploadconsume'])->name('month.uploadconsume')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//單耗上傳資料新增至資料庫
Route::post('/insertuploadconsume', [MonthController::class, 'insertuploadconsume'])->name('month.insertuploadconsume')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//新增站位上傳
Route::get('/uploadstand', [MonthController::class, 'uploadstandpage'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadstand', [MonthController::class, 'uploadstand'])->name('month.uploadstand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位上傳資料新增至資料庫
Route::post('/insertuploadstand', [MonthController::class, 'insertuploadstand'])->name('month.insertuploadstand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');


//非月請購上傳
Route::get('/uploadnotmonth', [MonthController::class, 'uploadnotmonthpage'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadnotmonth', [MonthController::class, 'uploadnotmonth'])->name('month.uploadnotmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購上傳資料新增至資料庫
Route::post('/insertuploadnotmonth', [MonthController::class, 'insertuploadnotmonth'])->name('month.insertuploadnotmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購上傳
Route::get('/uploadmonth', [MonthController::class, 'uploadmonthpage'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadmonth', [MonthController::class, 'uploadmonth'])->name('month.uploadmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購上傳資料新增至資料庫
Route::post('/insertuploadmonth', [MonthController::class, 'insertuploadmonth'])->name('month.insertuploadmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');
