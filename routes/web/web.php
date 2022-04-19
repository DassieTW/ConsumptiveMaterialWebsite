<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\OwarehouseController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\MailController;
use App\Services\MailService; // for testing only

use Illuminate\Http\Request;
use App\Models;
use Illuminate\Database\Eloquent\Model;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// if using Vue-Router to handle all the front end routes and redirects AKA "SPA, Single Page Application",
// Laravel should only have API routes, and only this one single web routes.
// Vue (front-end) gets all the data from API (Laravel, back-end)
// For authentication, use Laravel Sanctum ( laravel authentication system for SPAs )
//------------------------------------------------------------------------------------------------------------
// Route::get('/{any}', function () {
//     return view('layouts.app');
// })->where("any", ".*");
// --------------- the about code gets any url of our website and intended to pass it to Vue Router ----------

Route::get('/', function () {
    return view('welcome');
})->name('welcome')->withoutMiddleware('auth');

Route::get('/testwebsql', function () {
    return view('websql');
})->withoutMiddleware('auth');

Route::get('/template_test', function () {
    return view('templateChart');
})->name('charts');

Route::get('/dashboard', function () {
    return view('testTemplate');
})->name('dashboard');

Route::get('/vuetest', function () {
    // dd( \Auth::user()->username ); // test
    // return view("layouts.app");
    $databases = config('database_list.databases');

    MailService::day(); // test
})->withoutMiddleware('auth');

Route::get('/phpinfo', function () {
    phpinfo();
})->withoutMiddleware('auth');

Route::get('/import_excel', [ImportExcelController::class, 'index']);

Route::get('/import_excel/import', [ImportExcelController::class, 'index']);
Route::post('/import_excel/import', [ImportExcelController::class, 'import']);


//Route::middleware('priority')->get('member/call', [PriorityController::class, 'call'])->name('member.call');
//Route::middleware('priority')->post('member/call', [PriorityController::class, 'call'])->name('member.call');

// Auth::routes();

// language changing routes
Route::get('/lang/{type}', function (Request $request, $type) {
    $session = $request->getSession();
    $session->put('locale', $type);
    \App::setLocale($type);
    return Redirect::back();
})->withoutMiddleware('auth');

Route::get('/home', function () {
    return view("home");
})->name('home');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::post('/getCurrentDB', function () {
    return DB::connection()->getDatabaseName();
});

