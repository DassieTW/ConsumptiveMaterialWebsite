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
    return view('month.importnotmonth');
})->name('month.importnotmonth')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//匯入月請購資料頁面
Route::get('/importmonth', function () {
    return view('month.importmonth');
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

Route::get('/buylistmake', function () {
    return view('month.buylist')->with(['client' => 客戶別::cursor(), 'send' => 發料部門::cursor()]);
})->name('month.buylist')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/buylistmake', [MonthController::class, 'buylistmake'])->name('month.buylistmake')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/buylistsubmit', [MonthController::class, 'buylistsubmit'])->name('month.buylistsubmit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');


// 更新單價
Route::get('/UpdateUnitPrice', function () {
    return view('month.UpdateUnitPrice');
})->name('month.UpdateUnitPrice')->middleware('can:updateUnitPrice,App\Models\月請購_單耗');

// 送簽請購單 匯率畫面
Route::get('/SendPRReview', function () {
    return view('month.SendPRReview');
})->name('month.SendPRReview');

//在途量(查詢)頁面
Route::get('/transit', function () {
    return view('month.transit')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('month.transit')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//在途量(查詢)
Route::get('/transitsearch', function () {
    return view("month.transitsearchok");
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SXB單(查詢)頁面
Route::get('/sxb', function () {
    return view('month.sxb')->with(['client' => 客戶別::cursor()])->with(['send' => 發料部門::cursor()]);
})->name('month.sxb')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//SXB單(查詢)
Route::get('/sxbsearch', function () {
    return view("month.sxbsearchok");
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//料號單耗(新增)頁面
Route::get('/consumeadd', function () {
    return view('month.consumeadd');
})->name('month.consumeadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(新增)頁面
Route::get('/standadd', function () {
    $people = DB::table('login')
        ->join('人員信息', function ($join) {
            $join->on('人員信息.工號', '=', 'login.username');
        })
        ->where('priority', "=", 1)
        ->whereNotNull('email')
        ->get();
    return view('month.standadd')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()])->with([
            'people' => $people
        ]);
})->name('month.standadd')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//站位人力(新增)
Route::post('/standnew', [MonthController::class, 'standnew'])->name('month.standnew')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

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

//新增站位上傳
Route::get('/uploadstand', function () {
    $people = DB::table('login')
        ->join('人員信息', function ($join) {
            $join->on('人員信息.工號', '=', 'login.username');
        })
        ->where('priority', "=", 1)
        ->whereNotNull('email')
        ->get();
    return view('month.standadd')->with(['client' => 客戶別::cursor()])
        ->with(['machine' => 機種::cursor()])->with(['production' => 製程::cursor()])->with(['people' => $people]);
})->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

Route::post('/uploadstand', [MonthController::class, 'uploadstand'])->name('month.uploadstand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//單耗畫押page
Route::get('/testconsume', function () {
    if (strcmp(env('APP_ENV'), 'production') === 0 && request()->query('SSOfailed', 'false') === 'false') {
        // redirect to MIS SSO page
        $userKey = urlencode(base64_encode(env('SSO_Key')));
        $sysType = urlencode(base64_encode(env('SSO_sysType')));
        $SSO_URL = env('SSO_URL');
        $ReDirToUrl = env('APP_URL') . "/month/testconsume?r=" . request()->r . "&u=" . request()->u . "&d=" . request()->d . "&l=" . request()->getSession()->get('locale');
        $ReDirToUrl = urlencode($ReDirToUrl);
        $FailTo = env('APP_URL') . "/month/testconsume?r=" . request()->r . "&u=" . request()->u . "&d=" . request()->d . "&l=" . request()->getSession()->get('locale') . "&SSOfailed=true";
        $FailTo = urlencode($FailTo);
        return redirect($SSO_URL . '?ReDirTo=' . $ReDirToUrl . '&FailTo=' . $FailTo . '&sysType=' . $sysType . '&userKey=' . $userKey);
    } // if
    else {
        if (request()->filled('r')) {
            $email = Crypt::decryptString(request()->r);
            $username = Crypt::decryptString(request()->u);
            $database = Crypt::decryptString(request()->d);
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));
            $name = DB::table('login')
                ->join('人員信息', function ($join) {
                    $join->on('人員信息.工號', '=', 'login.username');
                })
                ->where('username', $username)->value('姓名');
            return view('month.testconsume')->with(['data' => 月請購_單耗::cursor()->where('狀態', "待畫押")->where("畫押信箱", $email)])
                ->with(['email' => $email])->with(['username' => $name])->with(['database' => $database]);
        } else {
            return abort(404);
        } // if else
    } // else
})->withoutMiddleware('auth');

// POST Route for SSO Redir
Route::post('/testconsume', [MonthController::class, 'testconsumeOALogin'])->withoutMiddleware('auth');

//test單耗畫押提交
Route::post('/testconsume_submit', [MonthController::class, 'testconsume'])->name('month.testconsume_submit')->withoutMiddleware('auth');

//站位畫押page
Route::get('/teststand', function () {
    if (strcmp(env('APP_ENV'), 'production') === 0 && request()->query('SSOfailed', 'false') == 'false') {
        // redirect to MIS SSO page
        $userKey = urlencode(base64_encode(env('SSO_Key')));
        $sysType = urlencode(base64_encode(env('SSO_sysType')));
        $SSO_URL = env('SSO_URL');
        $ReDirToUrl = env('APP_URL') . "/month/teststand?r=" . request()->r . "&u=" . request()->u . "&d=" . request()->d . "&l=" . request()->getSession()->get('locale');
        $ReDirToUrl = urlencode($ReDirToUrl);
        $FailTo = env('APP_URL') . "/month/teststand?r=" . request()->r . "&u=" . request()->u . "&d=" . request()->d . "&l=" . request()->getSession()->get('locale') . "&SSOfailed=true";
        $FailTo = urlencode($FailTo);
        return redirect($SSO_URL . '?ReDirTo=' . $ReDirToUrl . '&FailTo=' . $FailTo . '&sysType=' . $sysType . '&userKey=' . $userKey);
    } // if
    else {
        if (request()->filled('r')) {
            $email = Crypt::decryptString(request()->r);
            $username = Crypt::decryptString(request()->u);
            $database = Crypt::decryptString(request()->d);
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));
            $name = DB::table('login')
                ->join('人員信息', function ($join) {
                    $join->on('人員信息.工號', '=', 'login.username');
                })
                ->where('username', $username)->value('姓名');
            return view('month.teststand')->with(['data' => 月請購_站位::cursor()->where('狀態', "待畫押")->where("畫押信箱", $email)])
                ->with(['email' => $email])->with(['username' => $name])->with(['database' => $database]);
        } else {
            return abort(404);
        } // if else
    } // else
})->withoutMiddleware('auth');

// POST Route for SSO Redir
Route::post('/teststand', [MonthController::class, 'teststandOALogin'])->withoutMiddleware('auth');

//test站位畫押提交
Route::post('/teststand_submit', [MonthController::class, 'teststand'])->name('month.teststand_submit')->withoutMiddleware('auth');

//站位人力下載
Route::post('/standdownload', [MonthController::class, 'standdownload'])->name('month.standdownload');

//單耗下載
Route::post('/consumedownload', [MonthController::class, 'consumedownload'])->name('month.consumedownload');

//非月請購下載
Route::post('/download', [MonthController::class, 'download'])->name('month.download');

//請購單下載
Route::post('/buylistdownload', [MonthController::class, 'buylistdownload'])->name('month.buylistdownload');

//load 待重畫單耗
Route::post('/loadconsume', [MonthController::class, 'loadconsume'])->name('month.loadconsume')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');

//load 待重畫站位
Route::post('/loadstand', [MonthController::class, 'loadstand'])->name('month.loadstand')->middleware('can:viewMonthlyPR,App\Models\月請購_單耗');
