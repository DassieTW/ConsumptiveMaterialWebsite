<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicInformationController;

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

Route::post('/delete', 'api\CheckingInventoryController@destroyRecord');
Route::post('/upload', 'api\CheckingInventoryController@store');
Route::post('/getCheckingRecords', 'api\CheckingInventoryController@getCheckingRecords');
Route::post('/approve', 'api\CheckingInventoryController@approveCheckingRecords');
Route::post('/reject', 'api\CheckingInventoryController@destroyRecord');


