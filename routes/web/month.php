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
Route::get('/', [MonthController::class, 'index'])->name('month.index');

//匯入非月請購資料頁面
Route::get('/importnotmonth', [MonthController::class, 'importnotmonth'])->name('month.importnotmonth');

//匯入月請購資料頁面
Route::get('/importmonth', [MonthController::class, 'importmonth'])->name('month.importmonth');

//SRM單數量頁面
Route::get('/srm', [MonthController::class, 'srm'])->name('month.srm');

//SRM單(查詢)
Route::get('/srmsearch', [MonthController::class, 'srmsearch']);

Route::post('/srmsearch', [MonthController::class, 'srmsearch'])->name('month.srmsearch');

//SRM單(提交)
Route::post('/srmsubmit', [MonthController::class, 'srmsubmit'])->name('month.srmsubmit');

//非月請購查詢
Route::get('/notmonthinf', [MonthController::class, 'notmonthsearchoradd']);

Route::post('/notmonthinf', [MonthController::class, 'notmonthsearchoradd'])->name('month.notmonthsearchoradd');

//月請購查詢
Route::get('/monthinf', [MonthController::class, 'monthsearchoradd']);

Route::post('/monthinf', [MonthController::class, 'monthsearchoradd'])->name('month.monthsearchoradd');

//月請購刪除
Route::post('/monthdelete', [MonthController::class, 'monthdelete'])->name('month.monthdelete');

//月請購添加
Route::post('/monthadd', [MonthController::class, 'monthadd'])->name('month.monthadd');

//非月請購添加
Route::get('/notmonthadd', [MonthController::class, 'notmonthadd']);

Route::post('/notmonthadd', [MonthController::class, 'notmonthadd'])->name('month.notmonthadd');

//非月請購添加成功
Route::get('/notmonthaddok', [MonthController::class, 'notmonthaddok']);

//月請購添加成功
Route::get('/monthaddok', [MonthController::class, 'monthaddok']);

//請購單頁面
Route::get('/buylist', [MonthController::class, 'buylist'])->name('month.buylist');

//Route::get('/buylistmake', [MonthController::class, 'buylistmake']);

Route::post('/buylistmake', [MonthController::class, 'buylistmake'])->name('month.buylistmake');

Route::post('/buylistsubmit', [MonthController::class, 'buylistsubmit'])->name('month.buylistsubmit');

//在途量(查詢)頁面
Route::get('/transit', [MonthController::class, 'transit'])->name('month.transit');

//在途量(查詢)
Route::get('/transitsearch', [MonthController::class, 'transitsearch']);

Route::post('/transitsearch', [MonthController::class, 'transitsearch'])->name('month.transitsearch');

//SXB單(查詢)頁面
Route::get('/sxb', [MonthController::class, 'sxb'])->name('month.sxb');

//SXB單(查詢)
Route::get('/sxbsearch', [MonthController::class, 'sxbsearch']);

Route::post('/sxbsearch', [MonthController::class, 'sxbsearch'])->name('month.sxbsearch');

//料號單耗(新增)頁面
Route::get('/consumeadd', [MonthController::class, 'consumeadd'])->name('month.consumeadd');

//站位人力(新增)頁面
Route::get('/standadd', [MonthController::class, 'standadd'])->name('month.standadd');


//料號單耗(新增)
Route::post('/consumenew', [MonthController::class, 'consumenew'])->name('month.consumenew');

//站位人力(新增)
Route::post('/standnew', [MonthController::class, 'standnew'])->name('month.standnew');

//料號單耗(新增)添加頁面
Route::get('/consumenewok', [MonthController::class, 'consumenewok']);

//站位人力(新增)添加頁面
Route::get('/standnewok', [MonthController::class, 'standnewok']);


//提交料號單耗
Route::post('/consumenewsubmit', [MonthController::class, 'consumenewsubmit'])->name('month.consumenewsubmitok');

//領料料號單耗成功頁面
Route::get('/consumenewsubmitok', [MonthController::class, 'consumenewsubmitok']);

//提交站位人力
Route::post('/standnewsubmit', [MonthController::class, 'standnewsubmit'])->name('month.standnewsubmit');

//領料站位人力成功頁面
Route::get('/standnewsubmitok', [MonthController::class, 'standnewsubmitok']);


//料號單耗(查詢與修改)頁面
Route::get('/consume', [MonthController::class, 'consume'])->name('month.consume');

//站位人力(查詢與修改)頁面
Route::get('/stand', [MonthController::class, 'stand'])->name('month.stand');

//料號單耗(查詢)ok
Route::get('/consumesearch', [MonthController::class, 'consumesearch']);

Route::post('/consumesearch', [MonthController::class, 'consumesearch'])->name('month.consumesearch');

//站位人力(查詢)ok
Route::get('/standsearch', [MonthController::class, 'standsearch']);

Route::post('/standsearch', [MonthController::class, 'standsearch'])->name('month.standsearch');

//料號單耗(刪除或修改)
Route::post('/consumechangeordelete', [MonthController::class, 'consumechangeordelete'])->name('month.consumechangeordelete');

//站位人力(刪除或修改)
Route::post('/standchangeordelete', [MonthController::class, 'standchangeordelete'])->name('month.standchangeordelete');

//新增單耗上傳
Route::get('/uploadconsume', [MonthController::class, 'uploadconsumepage']);

Route::post('/uploadconsume', [MonthController::class, 'uploadconsume'])->name('month.uploadconsume');

//單耗上傳資料新增至資料庫
Route::post('/insertuploadconsume', [MonthController::class, 'insertuploadconsume'])->name('month.insertuploadconsume');

//新增站位上傳
Route::get('/uploadstand', [MonthController::class, 'uploadstandpage']);

Route::post('/uploadstand', [MonthController::class, 'uploadstand'])->name('month.uploadstand');

//站位上傳資料新增至資料庫
Route::post('/insertuploadstand', [MonthController::class, 'insertuploadstand'])->name('month.insertuploadstand');


//非月請購上傳
Route::get('/uploadnotmonth', [MonthController::class, 'uploadnotmonthpage']);

Route::post('/uploadnotmonth', [MonthController::class, 'uploadnotmonth'])->name('month.uploadnotmonth');

//非月請購上傳資料新增至資料庫
Route::post('/insertuploadnotmonth', [MonthController::class, 'insertuploadnotmonth'])->name('month.insertuploadnotmonth');

//月請購上傳
Route::get('/uploadmonth', [MonthController::class, 'uploadmonthpage']);

Route::post('/uploadmonth', [MonthController::class, 'uploadmonth'])->name('month.uploadmonth');

//月請購上傳資料新增至資料庫
Route::post('/insertuploadmonth', [MonthController::class, 'insertuploadmonth'])->name('month.insertuploadmonth');
