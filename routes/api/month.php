<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\在途量;

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

// get the info
Route::post('transit', 'api\MonthlyPRController@showTransit');

Route::post('/sxb', 'api\MonthlyPRController@showSXB');

Route::post('/notmonth', 'api\MonthlyPRController@showNonMonthly');

Route::post('/rejectedUC', 'api\MonthlyPRController@showRejectedUnitConsumption');
Route::post('/checkersMail', 'api\MonthlyPRController@showCheckersEmail');

// send Unit Consumption to DB
Route::post('/send_UC_to_DB', 'api\MonthlyPRController@update_UnitConsumption');

Route::post('/mps', 'api\MonthlyPRController@showMPS');
Route::post('/delete_mps', 'api\MonthlyPRController@destroyMPS');
Route::post('/submit_monthlypr', 'api\MonthlyPRController@storeMonthlyPR');


