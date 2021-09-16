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
    $month = Carbon::now()->subMonths(3);
    $serialNums = \DB::table('checking_inventory')->select('id', '單號', 'created_at')->latest('created_at')->groupBy('單號')->having('created_at', '>=', $month)->cursor();
    $serialNumsCount = Checking_inventory::where('created_at', '>=', $month)->distinct('單號')->count();
    return view('checkInventory.checkingInvent', ['serialCount' => $serialNumsCount, 'serialNums' => $serialNums ]);
})->name('checking.index');

// fetch the wanted table (by serial no.) from db
Route::post('/checkInentdbSearch', [CheckingInventoryController::class, 'dbSearch'])->name('checkInentdbSearch');

// user fill in and update the checking
Route::post('/updateChecking', [CheckingInventoryController::class, 'updateChecking'])->name('updateChecking');

// create new table page
Route::get('/create_new_table', function () {
    $month = Carbon::now()->subMonths(3);
    $serialNums = \DB::table('checking_inventory')->select('id', '單號', 'created_at')->latest('created_at')->groupBy('單號')->having('created_at', '>=', $month)->cursor();
    $serialNumsCount = Checking_inventory::where('created_at', '>=', $month)->distinct('單號')->count();
    return view('checkInventory.newTable', ['serialCount' => $serialNumsCount, 'serialNums' => $serialNums ]);
})->name('checking.create_new_table');

// create new table
Route::post('/create_new_table', [CheckingInventoryController::class, 'createTable'])->name('create_new_table');

// get the table creators from login table
Route::post('/get_creators', [CheckingInventoryController::class, 'getCreators']);

// set the continue checking table to session
Route::post('/set_wanted_table', [CheckingInventoryController::class, 'setContinue']);