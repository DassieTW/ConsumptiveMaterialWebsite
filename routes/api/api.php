<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/news', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
    \DB::purge(env("DB_CONNECTION"));

    $datas = [];
    $datas = DB::table('bulletins')
                ->get();

    return \Response::json(['datas' => $datas], 200/* Status code here default is 200 ok*/);
});