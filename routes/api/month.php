<?php

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
// get the info
Route::post('/sxb', 'api\MonthlyPRController@showSXB');

Route::post('/notmonth', 'api\MonthlyPRController@showNonMonthly');

Route::post('/rejectedUC', 'api\MonthlyPRController@showRejectedUnitConsumption');
Route::post('/checkersMail', 'api\MonthlyPRController@showCheckersEmail');

// send Unit Consumption to DB
Route::post('/send_UC_to_DB', 'api\MonthlyPRController@update_UnitConsumption');

Route::post('/validateUC', 'api\MonthlyPRController@checkIfUnitConsumptionExist');
Route::post('/mps', 'api\MonthlyPRController@showMPS');
Route::post('/delete_mps', 'api\MonthlyPRController@destroyMPS');
Route::post('/submit_monthlypr', 'api\MonthlyPRController@storeMonthlyPR');
Route::post('/delete_nonmps', 'api\MonthlyPRController@destroyNonMPS');
Route::post('/submit_nonmonthlypr', 'api\MonthlyPRController@storeNonMonthlyPR');
Route::post('/generate_buylist', 'api\MonthlyPRController@showBuylist');
Route::post('/sendPRMail', 'api\MonthlyPRController@sendPRMail');
Route::post('/getCurrency', 'api\MonthlyPRController@showCurrency');
Route::post('/submit_buylist', 'api\MonthlyPRController@storeBuylist');
Route::post('/approveSXB', 'api\MonthlyPRController@approveSXB');
Route::post('/rejectSXB', 'api\MonthlyPRController@rejectSXB');
Route::post('/getTransit', 'api\MonthlyPRController@showInTransit');
Route::post('/updateTransit', 'api\MonthlyPRController@update_InTransit');

