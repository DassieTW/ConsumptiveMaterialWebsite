<?php

use App\Models\Login;
use App\Models\人員信息;
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
    if (
        str_contains(url()->previous(), "/member/sso") ||
        str_contains(url()->previous(), "ws.ecomp.pegatroncorp.com") ||
        str_contains(url()->previous(), "lang") ||
        str_contains(url()->previous(), "New_OA_Login")
    ) {
        $database_list = config('database_list.databases');
        $database_names = array();
        foreach ($database_list as $value) {
            $temp = str_replace(" Consumables management", "", $value);
            array_push($database_names, $temp);
        } // for each

        unset($value); // unset the var created in the foreach loop

        // continue login process
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
    if (strcmp(env('APP_ENV'), 'production') === 0) {
        return redirect(route('welcome'));
    } // if
    else {
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
    } // else
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
    if (\Auth::user()->priority == 0) {
        return view('member.searchusernameok')->with(['data' => Login::cursor(), 'db_list' => config('database_list.databases')]);
    } else {
        return view('member.searchusernameok')->with(['data' => Login::cursor()->where('priority', '>', 0)]);
    } // if else
})->name('member.username')->middleware('can:searchAndUpdateUser,App\Models\Login');

//用戶信息修改
Route::post('/usernamechange', [Auth\LoginController::class, 'usernamechange'])->name('member.usernamechange')->middleware('can:searchAndUpdateUser,App\Models\Login');
//用戶信息刪除
Route::post('/username_del', [Auth\LoginController::class, 'usernameDel'])->name('member.usernamedel')->middleware('can:canAddSitesToUser,App\Models\Login');


//人員信息查詢頁面
Route::get('/numbersearch', function () {
    return view('member.searchnumberok')->with(['data' => 人員信息::cursor()]);
})->name('member.numbersearch')->middleware('can:searchAndUpdatePeople,App\Models\Login');

//人員信息刪除或修改
Route::post('/numberchangeordel', [Auth\LoginController::class, 'numberchangeordel'])->name('member.numberchangeordel')->middleware('can:searchAndUpdatePeople,App\Models\Login');

// IT等級使用者更改可登入DB清單
Route::post('/update_available_dblist', [Auth\LoginController::class, 'update_available_dblist'])->middleware('can:canAddSitesToUser,App\Models\Login');
