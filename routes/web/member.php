<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
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
Route::get('/change', [Auth\LoginController::class, 'change']);

Route::post('/change', [Auth\LoginController::class, 'change'])->name('member.change');

//register login people
Route::get('/register', [Auth\LoginController::class, 'register']);

Route::post('/register', [Auth\LoginController::class, 'register'])->name('member.register');

//new people information
Route::get('/new', [Auth\LoginController::class, 'new'])->name('member.new_get');

Route::post('/new', [Auth\LoginController::class, 'new'])->name('member.new');

//update people info
Route::post('/update', [Auth\LoginController::class, 'update'])->name('member.update');

//logout
Route::get('/logout', [Auth\LoginController::class, 'logout'])->withoutMiddleware('auth');

Route::post('/logout', [Auth\LoginController::class, 'logout'])->name('member.logout')->withoutMiddleware('auth');


//update people inf success
Route::get('/updateok', [Auth\LoginController::class, 'updateok']);

//人員信息查詢頁面
Route::get('/number', function () {
    return view('member.searchnumber');
});

Route::post('/number', [Auth\LoginController::class, 'number'])->name('member.number');

//人員信息查詢
Route::get('/numbersearch', [Auth\LoginController::class, 'searchnumber']);

Route::post('/numbersearch', [Auth\LoginController::class, 'searchnumber'])->name('member.searchnumber');

//人員信息刪除或修改
Route::post('/numberchangeordel', [Auth\LoginController::class, 'numberchangeordel'])->name('member.numberchangeordel');


//人員信息查詢頁面
Route::get('/username', function () {
    return view('member.searchusername');
});

Route::post('/username', [Auth\LoginController::class, 'username'])->name('member.username');

//人員信息查詢
Route::get('/usernamesearch', [Auth\LoginController::class, 'searchusername']);

Route::post('/usernamesearch', [Auth\LoginController::class, 'searchusername'])->name('member.searchusername');

//人員信息刪除或修改
Route::post('/usernamechangeordel', [Auth\LoginController::class, 'usernamechangeordel'])->name('member.usernamechangeordel');

//新增人員信息上傳
Route::get('/uploadpeople', [Auth\LoginController::class, 'uploadpeoplepage']);

Route::post('/uploadpeople', [Auth\LoginController::class, 'uploadpeople'])->name('member.uploadpeople');

//人員信息上傳資料新增至資料庫
Route::post('/insertuploadpeople', [Auth\LoginController::class, 'insertuploadpeople'])->name('member.insertuploadpeople');
