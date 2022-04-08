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
use App\Models\調撥單;
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
Route::get('/', function () {
    return view('bu.index');
})->name('bu.index');

//搜尋呆滯庫存
Route::get('/sluggish', [BUController::class, 'sluggish'])->name('bu.sluggish');

//廠區庫存調撥
Route::get('/sluggishmaterial', function () {
    $database_list = config('database_list.databases');
    $database_names = array();
    foreach ($database_list as $value) {
        $temp = str_replace(" Consumables management", "", $value);
        array_push($database_names, $temp);
    } // for each

    unset($value); // unset the var created in the foreach loop

    if (Session::has('username')) {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
        \DB::purge(env("DB_CONNECTION"));
        return view('bu.material')->with(['database_list' => $database_list, 'database_names' => $database_names]);
    } else {
        return redirect(route('member.login'));
    } // if else
});

Route::post('/sluggishmaterial', [BUController::class, 'sluggishmaterial'])->name('bu.sluggishmaterial');

//新增調撥單
Route::post('/transsluggish', [BUController::class, 'transsluggish'])->name('bu.transsluggish');

//調撥單查詢頁面
Route::get('/searchlist', function () {
    if (Session::has('username')) {
        $database_list = config('database_list.databases');
        $database_names = array();
        foreach( $database_list as $value ) {
            $temp = str_replace(" Consumables management", "", $value);
            array_push( $database_names, $temp );
        } // for each

        unset($value); // unset the var created in the foreach loop

        return view('bu.searchlist')->with(['database_list' => $database_list, 'database_names' => $database_names]);
    } else {
        return redirect(route('member.login'));
    } // if else
})->name('bu.searchlist');

//調撥單查詢
Route::get('/searchlistsub', [BUController::class, 'searchlistsub']);

Route::post('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');

//調撥單查詢-刪除
Route::post('/delete', [BUController::class, 'delete'])->name('bu.delete');

//調撥_撥出單頁面
Route::get('/outlist', function () {
    if (Session::has('username')) {
        $database = Session::get('database');
        $database_list = config('database_list.databases');
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
        \DB::purge(env("DB_CONNECTION"));
        return view('bu.outlistpage')->with(['data' => 調撥單::cursor()->where('撥出廠區', $database)->wherenull('調撥人')]);
    } else {
        return redirect(route('member.login'));
    }
})->name('bu.outlistpage');

//調撥_撥出單
Route::get('/outlistsub', function () {
    if (Session::has('username')) {
        $database = Session::get('database');
        $database_list = config('database_list.databases');
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
        \DB::purge(env("DB_CONNECTION"));
        return view('bu.outlistpage')->with(['data' => 調撥單::cursor()->where('撥出廠區', $database)->wherenull('調撥人')]);
    } else {
        return redirect(route('member.login'));
    }
})->name('bu.outlist');

Route::post('/outlistsub', [BUController::class, 'outlist'])->name('bu.outlist');

//調撥_撥出單提交
Route::post('/outlistsubmit', [BUController::class, 'outlistsubmit'])->name('bu.outlistsubmit');

//調撥_接收單頁面
Route::get('/picklist', function () {
    if (Session::has('username')) {
        $database = Session::get('database');
        $database_list = config('database_list.databases');
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
        \DB::purge(env("DB_CONNECTION"));
        return view('bu.picklistpage')->with(['data' => 調撥單::cursor()->where('接收廠區', $database)->where('狀態', '待接收')]);
    } else {
        return redirect(route('member.login'));
    }
})->name('bu.picklistpage');

//調撥_接收單
Route::get('/picklistsub', function () {
    if (Session::has('username')) {
        $database = Session::get('database');
        $database_list = config('database_list.databases');
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
        \DB::purge(env("DB_CONNECTION"));
        return view('bu.picklistpage')->with(['data' => 調撥單::cursor()->where('接收廠區', $database)->where('狀態', '待接收')]);
    } else {
        return redirect(route('member.login'));
    }
})->name('bu.picklist');

Route::post('/picklistsub', [BUController::class, 'picklist'])->name('bu.picklist');

//調撥_接收單提交
Route::post('/picklistsubmit', [BUController::class, 'picklistsubmit'])->name('bu.picklistsubmit');


//調撥明細查詢頁面
Route::get('/searchdetail', function () {
    if (Session::has('username')) {
        return view('bu.searchdetail');
    } else {
        return redirect(route('member.login'));
    }
})->name('bu.searchdetail');

//調撥明細查詢
Route::get('/searchdetailsub', function () {
    if (Session::has('username')) {
        return view('bu.searchdetail');
    } else {
        return redirect(route('member.login'));
    }
});

Route::post('/searchdetailsub', [BUController::class, 'searchdetailsub'])->name('bu.searchdetailsub');

//呆滯庫存下載
Route::post('/download', [BUController::class, 'download'])->name('bu.download');

//調撥單查詢下載
Route::post('/downloadlist', [BUController::class, 'downloadlist'])->name('bu.downloadlist');

//廠區庫存調撥頁面
Route::get('/material', function () {
    $database_list = config('database_list.databases');
    $database_names = array();
    foreach ($database_list as $value) {
        $temp = str_replace(" Consumables management", "", $value);
        array_push($database_names, $temp);
    } // for each

    unset($value); // unset the var created in the foreach loop

    if (Session::has('username')) {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database_list[0]);
        \DB::purge(env("DB_CONNECTION"));
        return view('bu.material')->with(['database_list' => $database_list, 'database_names' => $database_names]);
    } else {
        return redirect(route('member.login'));
    } // if else
})->name('bu.material');

/*//廠區庫存調撥
Route::get('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');

Route::post('/searchlistsub', [BUController::class, 'searchlistsub'])->name('bu.searchlistsub');*/
