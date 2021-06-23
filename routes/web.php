<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InspiringController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\BarcodeDisplayController;
use App\Http\Controllers\BasicInformationController;
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

Route::get('/phpinfo', function(){
    phpinfo();
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

Route::post('/barcode_gen', [BarcodeDisplayController::class, 'postBack']);

Route::get('member', [LoginController::class, 'index'])->name('member.index');

Route::get('basic', [BasicInformationController::class, 'index'])->name('basic.index');


//basic information change or delete
Route::get('basic/inf', [BasicInformationController::class, 'changeordelete'])->name('basic.changeordelete');

Route::post('basic/inf', [BasicInformationController::class, 'changeordelete'])->name('basic.changeordelete');

//new material inff
Route::get('basic/new', [BasicInformationController::class, 'new'])->name('basic.new');

Route::post('basic/new', [BasicInformationController::class, 'new'])->name('basic.new');

//modify material inf
Route::get('basic/modify', [BasicInformationController::class, 'modify'])->name('basic.modify');

Route::post('basic/modify', [BasicInformationController::class, 'modify'])->name('basic.modify');

//search material number
Route::get('basic/search/material', [BasicInformationController::class, 'searchmaterial'])->name('basic.searchmaterial');

Route::post('basic/search/material', [BasicInformationController::class, 'searchmaterial'])->name('basic.searchmaterial');

//search position
Route::get('basic/search/position', [BasicInformationController::class, 'searchposition'])->name('basic.searchposition');

Route::post('basic/search/position', [BasicInformationController::class, 'searchposition'])->name('basic.searchposition');

//login
Route::get('member/login', [LoginController::class, 'login'])->name('member.login');

Route::post('member/login', [LoginController::class, 'login'])->name('member.login');

//change password
Route::get('member/change', [LoginController::class, 'change'])->name('member.change');

Route::post('member/change', [LoginController::class, 'change'])->name('member.change');

//register login people
Route::get('member/register', [LoginController::class, 'register'])->name('member.register');

Route::post('member/register', [LoginController::class, 'register'])->name('member.register');

//new people information
Route::get('member/new', [LoginController::class, 'new'])->name('member.new');

Route::post('member/new', [LoginController::class, 'new'])->name('member.new');

//search people
Route::get('member/search', [LoginController::class, 'search'])->name('member.search');

Route::post('member/search', [LoginController::class, 'search'])->name('member.search');

//change by job number
Route::get('member/changenumber', [LoginController::class, 'changenumber'])->name('member.changenumber');

Route::post('member/changenumber', [LoginController::class, 'changenumber'])->name('member.changenumber');

//logout
Route::get('member/logout', [LoginController::class, 'logout'])->name('member.logout');

Route::post('member/logout', [LoginController::class, 'logout'])->name('member.logout');


Route::middleware('priority')->get('member/call', [PriorityController::class, 'call'])->name('member.call');

Route::middleware('priority')->post('member/call', [PriorityController::class, 'call'])->name('member.call');

Route::middleware('priority')->get('member/data', [PriorityController::class, 'data'])->name('member.data');

Route::middleware('priority')->post('member/data', [PriorityController::class, 'data'])->name('member.data');

Route::middleware('priority')->get('member/shop', [PriorityController::class, 'shop'])->name('member.shop');

Route::middleware('priority')->post('member/shop', [PriorityController::class, 'shop'])->name('member.shop');

Route::middleware('priority')->get('member/inwarehouse', [PriorityController::class, 'inwarehouse'])->name('member.inwarehouse');

Route::middleware('priority')->post('member/inwarehouse', [PriorityController::class, 'inwarehouse'])->name('member.inwarehouse');

Route::middleware('priority')->get('member/outwarehouse', [PriorityController::class, 'outwarehouse'])->name('member.outwarehouse');

Route::middleware('priority')->post('member/outwarehouse', [PriorityController::class, 'outwarehouse'])->name('member.outwarehouse');

Route::resource('posts', Controllers\PostController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

