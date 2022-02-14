<?php

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\客戶別;
use App\Models\機種;
use App\Models\製程;
use App\Models\線別;
use App\Models\領用原因;
use App\Models\退回原因;
use App\Models\入庫原因;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\人員信息;
use App\Models\發料部門;
use App\Models\儲位;
use App\Models\在途量;
use App\Models\Inventory;
use App\Models\不良品Inventory;
use App\Models\Inbound;
use App\Models\ConsumptiveMaterial;
use App\Models\請購單;
use App\Models\月請購_單耗;
use App\Models\月請購_站位;
use App\Models\非月請購;
use App\Models\MPS;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonthController;

/*
|--------------------------------------------------------------------------
| Web Routes for month type
|--------------------------------------------------------------------------
|
*/



//月請購
Route::get('/', function () {
    return view('month.index');
})->name('month.index')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//匯入非月請購資料頁面
Route::get('/importnotmonth', function () {
    return view('month.importnotmonth')->with(['client' => 客戶別::cursor()]);
})->name('month.importnotmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//匯入月請購資料頁面
Route::get('/importmonth', function () {
    return view('month.importmonth')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
})->name('month.importmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SRM單數量頁面
Route::get('/srm', function () {
    return view('month.srm')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('month.srm')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SRM單(查詢)
Route::get('/srmsearch', [MonthController::class, 'srmsearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/srmsearch', [MonthController::class, 'srmsearch'])->name('month.srmsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SRM單(提交)
Route::post('/srmsubmit', [MonthController::class, 'srmsubmit'])->name('month.srmsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購查詢
Route::post('/notmonthsearch', function (Request $request) {
    return \Response::json(['client' => $request->input('client'), 'number' => $request->input('number')]/* Status code here default is 200 ok*/);
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/notmonthsearchok', function (Request $request) {

    if ($request->input('varr1') === null && $request->input('varr2') === null) {
        $datas = DB::table('consumptive_material')
            ->join('非月請購', function ($join) {
                $join->on('非月請購.料號', '=', 'consumptive_material.料號');
            })->select('非月請購.*', 'consumptive_material.品名')->get();
        return view('month.notmonthsearchok')->with(['data' => $datas]);
    } else if ($request->input('varr1') !== null && $request->input('var2') === null) {
        $datas = DB::table('consumptive_material')
            ->join('非月請購', function ($join) {
                $join->on('非月請購.料號', '=', 'consumptive_material.料號');
            })->select('非月請購.*', 'consumptive_material.品名')->get();
        return view('month.notmonthsearchok')->with(['data' => $datas->where('客戶別', $request->input('client'))]);
    } else if ($request->input('varr1') === null && $request->input('varr2') !== null) {
        $datas = DB::table('consumptive_material')
            ->join('非月請購', function ($join) {
                $join->on('非月請購.料號', '=', 'consumptive_material.料號');
            })->select('非月請購.*', 'consumptive_material.品名')
            ->where('非月請購.料號', 'like', $request->input('varr2') . '%')->get();
        return view('month.notmonthsearchok')->with(['data' => $datas]);
    } else if ($request->input('varr1') !== null && $request->input('varr2') !== null) {
        $datas = DB::table('consumptive_material')
            ->join('非月請購', function ($join) {
                $join->on('非月請購.料號', '=', 'consumptive_material.料號');
            })->select('非月請購.*', 'consumptive_material.品名')
            ->where('非月請購.料號', 'like', $request->input('varr2') . '%')->get();
        return view('month.notmonthsearchok')->with(['data' => $datas->where('客戶別', $request->input('client'))]);
    }
})->name('month.notmonthsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購新增
Route::post('/notmonthadd', [MonthController::class, 'notmonthadd'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::get('/notmonthaddok', function () {
    return view('month.importnotmonth')->with(['client' => 客戶別::cursor()]);
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/notmonthaddok', function (Request $request) {
    return view('month.notmonthadd')
        ->with('client', $request->input('var1'))
        ->with('number', $request->input('var2'))
        ->with('name', $request->input('var3'))
        ->with('unit', $request->input('var4'))
        ->with('month', $request->input('var5'))
        ->with(['showclient' => 客戶別::cursor()]);
})->name('month.notmonthadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購提交
Route::post('/notmonthsubmit', [MonthController::class, 'notmonthsubmit'])->name('month.notmonthsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購查詢
Route::get('/monthinf', [MonthController::class, 'monthsearchoradd'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/monthinf', [MonthController::class, 'monthsearchoradd'])->name('month.monthsearchoradd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購刪除
Route::post('/monthdelete', [MonthController::class, 'monthdelete'])->name('month.monthdelete')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購添加
Route::post('/monthadd', [MonthController::class, 'monthadd'])->name('month.monthadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購提交
Route::post('/monthsubmit', [MonthController::class, 'monthsubmit'])->name('month.monthsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::get('/buylistmake', function () {
    return view('month.buylist')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('month.buylist')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/buylistmake', [MonthController::class, 'buylistmake'])->name('month.buylistmake')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/buylistsubmit', [MonthController::class, 'buylistsubmit'])->name('month.buylistsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//在途量(查詢)頁面
Route::get('/transit', function () {
    return view('month.transit')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('month.transit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//在途量(查詢)
Route::get('/transitsearch', [MonthController::class, 'transitsearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/transitsearch', [MonthController::class, 'transitsearch'])->name('month.transitsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SXB單(查詢)頁面
Route::get('/sxb', function () {
    return view('month.sxb')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('month.sxb')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SXB單(查詢)
Route::get('/sxbsearch', [MonthController::class, 'sxbsearch'])->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/sxbsearch', [MonthController::class, 'sxbsearch'])->name('month.sxbsearch')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//料號單耗(新增)頁面
Route::get('/consumeadd', function () {
    return view('month.consumeadd')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
})->name('month.consumeadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(新增)頁面
Route::get('/standadd', function () {
    return view('month.standadd')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
})->name('month.standadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');


//料號單耗(新增)
Route::post('/consumenew', [MonthController::class, 'consumenew'])->name('month.consumenew')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(新增)
Route::post('/standnew', [MonthController::class, 'standnew'])->name('month.standnew')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//提交料號單耗
Route::post('/consumenewsubmit', [MonthController::class, 'consumenewsubmit'])->name('month.consumenewsubmitok')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//提交站位人力
Route::post('/standnewsubmit', [MonthController::class, 'standnewsubmit'])->name('month.standnewsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//料號單耗(查詢與修改)頁面
Route::get('/consume', function () {
    return view('month.consume')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('month.consume')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(查詢與修改)頁面
Route::get('/stand', function () {
    return view('month.stand')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()])->with(['send' => 發料部門::cursor()]);;
})->name('month.stand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

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
Route::get('/uploadconsume', function () {
    return view('month.consumeadd')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadconsume', [MonthController::class, 'uploadconsume'])->name('month.uploadconsume')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//新增站位上傳
Route::get('/uploadstand', function () {
    return view('month.standadd')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadstand', [MonthController::class, 'uploadstand'])->name('month.uploadstand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//非月請購上傳
Route::get('/uploadnotmonth', function () {
    return view('month.importnotmonth')->with(['client' => 客戶別::cursor()]);
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadnotmonth', [MonthController::class, 'uploadnotmonth'])->name('month.uploadnotmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//月請購上傳
Route::get('/uploadmonth', function () {
    return view('month.importmonth')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()]);
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadmonth', [MonthController::class, 'uploadmonth'])->name('month.uploadmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//test單耗畫押
Route::get('/testconsume', function () {
    if (request()->filled('r')) {

        $email = Crypt::decrypt(request()->r);
        $username = Crypt::decrypt(request()->u);
        $database = Crypt::decrypt(request()->d);
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
        \DB::purge(env("DB_CONNECTION"));
        $name = DB::table('login')->where('username', $username)->value('姓名');

        return view('month.testconsume')->with(['data' => 月請購_單耗::cursor()->where('狀態', "待畫押")->where("畫押信箱", $email)->where("送單人", $username)])
            ->with(['email' => $email])->with(['username' => $name])->with(['database' => $database]);
    } else {
        return redirect()->route('month.consumeadd');
    }
})->name('month.testconsume')->withoutMiddleware('auth');

//test單耗畫押提交
Route::post('/testconsume', [MonthController::class, 'testconsume'])->name('month.testconsume')->withoutMiddleware('auth');

//test站位畫押
Route::get('/teststand', function () {
    if (request()->filled('r')) {
        $email = Crypt::decrypt(request()->r);
        $username = Crypt::decrypt(request()->u);
        $database = Crypt::decrypt(request()->d);

        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
        \DB::purge(env("DB_CONNECTION"));
        $name = DB::table('login')->where('username', $username)->value('姓名');

        return view('month.teststand')->with(['data' => 月請購_站位::cursor()->where('狀態', "待畫押")->where("畫押信箱", $email)->where("送單人", $username)])
        ->with(['email' => $email])->with(['username' => $name])->with(['database' => $database]);
    } else {
        return redirect()->route('month.standadd');
    }
})->name('month.teststand')->withoutMiddleware('auth');

//test站位畫押提交
Route::post('/teststand', [MonthController::class, 'teststand'])->name('month.teststand')->withoutMiddleware('auth');

//站位人力下載
Route::post('/standdownload', [MonthController::class, 'standdownload'])->name('month.standdownload');

//非月請購下載
Route::post('/download', [MonthController::class, 'download'])->name('month.download');

//請購單下載
Route::post('/buylistdownload', [MonthController::class, 'buylistdownload'])->name('month.buylistdownload');

//load 待重畫單耗
Route::post('/loadconsume', [MonthController::class, 'loadconsume'])->name('month.loadconsume')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//load 待重畫站位
Route::post('/loadstand', [MonthController::class, 'loadstand'])->name('month.loadstand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');
