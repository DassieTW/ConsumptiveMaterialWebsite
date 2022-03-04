<?php

use App\Models\人員信息;
use App\Models\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Models\Inventory;
// use DB;
// use Session;

// use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes for member type
|--------------------------------------------------------------------------
|
*/

Route::get('/', function () {
    return view('member.infor');
})->name('member.index');

// Matches The "/member/login" URL
Route::get('/login', function () {
    // used middleware for lacale now
    $user = \Auth::user();
    $response = \Gate::inspect('canLogin', $user); // call to LoginPolicy
    if ($response->allowed() || $user === null) {
        // The action is authorized...
        return view('member.login');  // continue log in process
    } else {
        return back(); // users that already logged in will be return back.
    } // if else

})->withoutMiddleware('auth');

Route::post('/login', [Auth\LoginController::class, 'login'])->name('member.login')->withoutMiddleware('auth');

//change password, Matches The "/member/change" URL
Route::get('/change', function () {
    $email = \DB::table('login')->select('email')
        ->where('username', '=', \Auth::user()->username)->get();
    return view('member.change', ['oldMail' => $email]);
});

Route::post('/change', [Auth\LoginController::class, 'change'])->name('member.change');

//register login people
Route::get('/register', function () {
    return view('member.register');
})->middleware('can:create,App\Models\Login');

Route::post('/register', [Auth\LoginController::class, 'register'])->name('member.register')->middleware('can:create,App\Models\Login');

//new people information
Route::get('/new', function () {
    return view('member.new');
})->name('member.new_get')->middleware('can:newPeopleInfo,App\Models\Login');

Route::post('/new', [Auth\LoginController::class, 'new'])->name('member.new')->middleware('can:newPeopleInfo,App\Models\Login');

//update people info
Route::post('/update', [Auth\LoginController::class, 'update'])->name('member.update')->middleware('can:newPeopleInfo,App\Models\Login');

//logout
Route::get('/logout', [Auth\LoginController::class, 'logout'])->withoutMiddleware('auth');

Route::post('/logout', [Auth\LoginController::class, 'logout'])->name('member.logout')->withoutMiddleware('auth');


//人員信息查詢頁面
Route::get('/number', function () {
    return view('member.searchnumber');
})->middleware('can:searchAndUpdatePeople,App\Models\Login');

Route::post('/number', [Auth\LoginController::class, 'number'])->name('member.number')->middleware('can:searchAndUpdatePeople,App\Models\Login');

//人員信息查詢
Route::get('/numbersearch', function () {
    return view('member.searchnumberok')->with(['data' => 人員信息::cursor()]);
})->middleware('can:searchAndUpdatePeople,App\Models\Login');

Route::post('/numbersearch', [Auth\LoginController::class, 'searchnumber'])->name('member.searchnumber')->middleware('can:searchAndUpdatePeople,App\Models\Login');

//人員信息刪除或修改
Route::post('/numberchangeordel', [Auth\LoginController::class, 'numberchangeordel'])->name('member.numberchangeordel')->middleware('can:searchAndUpdatePeople,App\Models\Login');


//用戶信息查詢頁面
Route::get('/username', function () {
    return view('member.searchusername');
})->middleware('can:searchAndUpdateUser,App\Models\Login');

Route::post('/username', [Auth\LoginController::class, 'username'])->name('member.username')->middleware('can:searchAndUpdateUser,App\Models\Login');

//用戶信息查詢
Route::get('/usernamesearch', function () {
    return view('member.searchusernameok')->with(['data' => Login::cursor()]);
})->middleware('can:searchAndUpdateUser,App\Models\Login');

Route::post('/usernamesearch', [Auth\LoginController::class, 'searchusername'])->name('member.searchusername')->middleware('can:searchAndUpdateUser,App\Models\Login');

//用戶信息刪除或修改
Route::post('/usernamechangeordel', [Auth\LoginController::class, 'usernamechangeordel'])->name('member.usernamechangeordel')->middleware('can:searchAndUpdateUser,App\Models\Login');

//新增人員信息上傳
Route::get('/uploadpeople', function () {
    return view('member.new');
});
Route::post('/uploadpeople', [Auth\LoginController::class, 'uploadpeople'])->name('member.uploadpeople');

//人員信息上傳資料新增至資料庫
Route::post('/insertuploadpeople', [Auth\LoginController::class, 'insertuploadpeople'])->name('member.insertuploadpeople');
