<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckingInventoryController;
use Carbon\Carbon;
use App\Models\Checking_inventory;

/*
|--------------------------------------------------------------------------
| Web Routes for checking Inventory Type
|--------------------------------------------------------------------------
|
*/

// Matches The "/checking" URL
Route::get('/', function () {
    $month = Carbon::now()->subMonths(3);
    $serialNums = \DB::table('checking_inventory')->select('單號', 'created_at')->latest()->where('created_at', '>=', $month)->distinct()->cursor();
    $serialNumsCount = Checking_inventory::where('created_at', '>=', $month)->distinct('單號')->count();
    return view('checkInventory.checkingInvent', ['serialCount' => $serialNumsCount, 'serialNums' => $serialNums ]);
})->name('checking.index');

Route::post('/checkInentdbSearch', [CheckingInventoryController::class, 'dbSearch'])->name('checkInentdbSearch');
Route::post('/updateChecking', [CheckingInventoryController::class, 'updateChecking'])->name('updateChecking');

// Matches The "/checking/xxx" URL