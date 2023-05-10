<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CallController;
use App\Http\Controllers\OwarehouseController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\HomeController;
use App\Services\MailService; // for testing only

use Illuminate\Http\Request;
use App\Models;
use Illuminate\Database\Eloquent\Model;
use MeiliSearch\Client;

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

Route::get('/ntlm', function () {
    return view('hello_world');
})->name('hello_world')->withoutMiddleware('auth');

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

    //MailService::day(); // test
})->withoutMiddleware('auth');

Route::get('/phpinfo', function () {
    phpinfo();
})->withoutMiddleware('auth');

Route::get('/import_excel', [ImportExcelController::class, 'index']);

Route::get('/import_excel/import', [ImportExcelController::class, 'index']);
Route::post('/import_excel/import', [ImportExcelController::class, 'import']);

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

Route::get('/editNews', function () {
    $user = \Auth::user();
    $response = \Gate::inspect('canPostToOtherSite', $user); // call to EditNewsPolicy
    $database_list = config('database_list.databases');
    $database_names = array();
    $selfDB_name = array("Consumables management", str_replace(" Consumables management", "", DB::connection()->getDatabaseName()));
    $selfDB_list = array("Consumables management", DB::connection()->getDatabaseName());
    foreach ($database_list as $value) {
        $temp = str_replace(" Consumables management", "", $value);
        array_push($database_names, $temp);
    } // for each

    unset($value); // unset the var created in the foreach loop

    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
    \DB::purge(env("DB_CONNECTION"));

    $cat_list = [];
    $cat_list = DB::table('bulletins')
        ->select('category')
        ->distinct()
        ->get();

    // get the connection back to original
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $selfDB_list[1]);
    \DB::purge(env("DB_CONNECTION"));

    if ($response->allowed()) {
        // The action is authorized...
        array_push($database_list, "All");
        array_push($database_names, __('templateWords.all'));
        return view('editNewsBoard')->with(['cat_list' => $cat_list, 'database_list' => $database_list, 'database_names' => $database_names]);
    } else {
        return view('editNewsBoard')->with(['cat_list' => $cat_list, 'database_list' => $selfDB_list, 'database_names' => $selfDB_name]);
    } // if else

})->name('editNews');

Route::get('/help', function () {
    return view("help");
})->name('help');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::post('/getCurrentDB', function () {
    return DB::connection()->getDatabaseName();
});

Route::get('/storage/barcodeImg/{filename}', function ($filename) {
    // due to multiple project nginx settings, the php artisan storage:link won't work
    // so we get the storage path ourselves
    $path = storage_path('app/public/barcodeImg/' . $filename);
    $file = File::get($path);
    $type = File::mimeType($path);

    if (!File::exists($path)) {
        abort(404);
    } // if

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::post('/navbar_quick_search', [HomeController::class, 'insiteSearch']);

Route::get('/meiliSearchCleanUp', function () { // use this route when needed
    $testClient = new Client(env('MEILISEARCH_HOST'));
    // dd($testClient->getAllIndexes());
    $testClient->deleteAllIndexes(); // u might want to clean up meilisearch db first
    dd($testClient->getTasks());
});
