<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckingInventoryController;
use Carbon\Carbon;
use App\Models\Checking_inventory;

/*
|--------------------------------------------------------------------------
| Web Routes for Checking Inventory Type
|--------------------------------------------------------------------------
|
*/

// Matches The "/checking" URL
Route::get('/', function () {
    return view('checkInventory.checkingInvent');
})->name('checking.index');

// get the record search page
Route::get('/check_record', function () {
    return view('checkInventory.checkRecord');
})->name('checking.check_record');
