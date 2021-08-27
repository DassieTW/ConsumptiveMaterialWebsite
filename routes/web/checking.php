<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckingInventoryController;

/*
|--------------------------------------------------------------------------
| Web Routes for member type
|--------------------------------------------------------------------------
|
*/

// Matches The "/checking" URL
Route::get('/', function () {
    return view('checkInventory.checkingInvent');
})->name('checking.index');

// Matches The "/checking/login" URL