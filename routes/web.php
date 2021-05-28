<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InspiringController;
use App\Http\Controllers;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello-world', function () { // first test ( with 'php artisan test' in terminal)
    return view('hello_world');
});

Route::get('/about_us', function () { // second test (layout with blade)
    return view('about_us');
});

Route::get('/template_test', function () {
    return view('templateChart');
})->name('charts');

Route::get('/dashboard', function () {
    return view('testTemplate');
})->name('dashboard');

Route::get('/barcode_gen', function () {
    return view('barcode_gen_page');
})->name('barcode_gen');

Route::get('/inspire', [InspiringController::class, 'inspire']); // third test ( connect controllers )

Route::get('/test', function(){ // cursor() : a LazyCollection implement by yield( php original ).
    return Models\Post::cursor()->filter(function ($post) {
        return $post->id > 450;
    });
});

Route::get('/test2', function(){
    // \Log::info('觸發 Test2'); // test
    // App::setLocale('en');
    // return trans('auth.throttle', ['seconds' => 5]);
    // return trans('auth.failed');
    // $author = Models\User::find(2);
    // $posts = $author->posts;
    // return $posts;
    $mats = Models\ConsumptiveMaterial::first();
    return $mats;
});

Route::resource('posts', Controllers\PostController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

