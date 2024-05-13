<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|                                !!!!! NOTICE !!!!!
|                        !!! API CANNOT ACCESS SESSION !!!
|
|
*/

// get Users Data
Route::post('/getUsers', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $users = DB::table('login')
        ->join('人員信息', function ($join) {
            $join->on('人員信息.工號', '=', 'login.username');
        })
        ->get();

    return \Response::json(['datas' => $users, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

// get Staffs Data
Route::post('/getStaffs', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $staffs = DB::table('人員信息')->get();

    return \Response::json(['datas' => $staffs, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

//用戶信息修改
Route::post('/usernamechange', 'api\LoginController@usernamechange');

//用戶刪除
Route::post('/username_del', 'api\LoginController@username_del');

// IT等級使用者更改可登入DB清單
// Route::post('/update_available_dblist', [Auth\LoginController::class, 'update_available_dblist'])->middleware('can:canAddSitesToUser,App\Models\Login');
Route::post('/update_available_dblist', 'api\LoginController@update_available_dblist');

