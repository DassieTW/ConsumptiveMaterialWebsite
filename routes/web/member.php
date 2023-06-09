<?php

use App\Models\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Models\Inventory;

// use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes for member type
|--------------------------------------------------------------------------
|
*/

Route::get('/', function () {
    return view('member.infor');
})->name('member.index');

// Matches The "/member/sso" URL
Route::post('/sso', [Auth\LoginController::class, 'OALogin'])->withoutMiddleware('auth');

// Matches the "/member/New_OA_Login" URL
Route::get('/New_OA_Login', function () {
    if (str_contains(url()->previous(), "/member/sso")) {
        $database_list = config('database_list.databases');
        $database_names = array();
        foreach ($database_list as $value) {
            $temp = str_replace(" Consumables management", "", $value);
            array_push($database_names, $temp);
        } // for each

        unset($value); // unset the var created in the foreach loop

        // continue log in process
        return view('member.new_oa_login')->with([
            'database_list' => $database_list,
            'database_names' => $database_names
        ]);
    } // if
    else {
        return redirect(route('welcome'));
    } // else
})->name('member.New_OA_Login')->withoutMiddleware('auth');

// Matches The "/member/login" URL
Route::get('/login', function () {
    // used middleware for lacale now
    $user = \Auth::user();
    $response = \Gate::inspect('canLogin', $user); // call to LoginPolicy

    if ($response->allowed() || $user === null) {
        // The action is authorized...
        $database_list = config('database_list.databases');
        $database_names = array();
        foreach ($database_list as $value) {
            $temp = str_replace(" Consumables management", "", $value);
            array_push($database_names, $temp);
        } // for each

        unset($value); // unset the var created in the foreach loop

        // continue log in process
        return view('member.login')->with(['database_list' => $database_list, 'database_names' => $database_names]);
    } else {
        return back(); // users that already logged in will be return back.
    } // if else

})->withoutMiddleware('auth');

Route::post('/login', [Auth\LoginController::class, 'login'])->name('member.login')->withoutMiddleware('auth');

//change password, Matches The "/member/change" URL
Route::get('/change', function () {
    $email = \DB::table('login')->select('email')
        ->where('username', '=', \Auth::user()->username)->get();
    return view('member.change', ['oldMail' => $email]);
});

Route::post('/change', [Auth\LoginController::class, 'change'])->name('member.change');

Route::post('/register', [Auth\LoginController::class, 'register'])->name('member.register')->withoutMiddleware('auth');

//new people information
Route::get('/new', function () {
    return view('member.new');
})->name('member.new_get')->middleware('can:newPeopleInfo,App\Models\Login');

Route::post('/new', [Auth\LoginController::class, 'new'])->name('member.new')->middleware('can:newPeopleInfo,App\Models\Login');

//update people info
Route::post('/update', [Auth\LoginController::class, 'update'])->name('member.update')->middleware('can:newPeopleInfo,App\Models\Login');

//logout
Route::get('/logout', [Auth\LoginController::class, 'logout'])->withoutMiddleware('auth');

Route::post('/logout', [Auth\LoginController::class, 'logout'])->name('member.logout')->withoutMiddleware('auth');

//用戶信息查詢頁面
Route::get('/username', function () {
    return view('member.searchusernameok')->with(['data' => Login::cursor()->where('priority', '<>', 1)]);
})->name('member.username')->middleware('can:searchAndUpdateUser,App\Models\Login');

Route::post('/usernamesearch', [Auth\LoginController::class, 'searchusername'])->name('member.searchusername')->middleware('can:searchAndUpdateUser,App\Models\Login');

//用戶信息刪除或修改
Route::post('/usernamechangeordel', [Auth\LoginController::class, 'usernamechangeordel'])->name('member.usernamechangeordel')->middleware('can:searchAndUpdateUser,App\Models\Login');