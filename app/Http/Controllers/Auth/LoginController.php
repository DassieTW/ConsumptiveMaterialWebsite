<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\人員信息;
use DB;
use Lang;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use DateTime;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use League\CommonMark\Cursor;
use Sentry\Util\JSON;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        attemptLogin as baseAttemptLogin;
    }

    protected $redirectTo = RouteServiceProvider::HOME;

    protected function attemptLogin(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // dd($request->site) ; // test
        // return \Auth::attempt($credentials);

        // login without hashed password
        $user = Login::where([
            'username' => $request->username,
            'password' => $request->password,
        ])->first();

        if ($user) {
            \Auth::login($user);
            return true;
        } // if
        else {
            return false;
        } // else
    } // attemptLogin

    protected function authenticated(Request $request, $user)
    {
        session(['database' => $request->site]);
        // dd(session('database')); // test
        // $request->session()->get('database'); // test
    } // authenticated

    //login
    public function login(Request $request)
    {
        // exit(0); // test
        $request->validate(
            [
                'username' => ['required'],
                'password' => ['required'],
                'site' => ['required'],
            ],
        );

        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->site);
        \DB::purge(env("DB_CONNECTION"));

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();

            $usernameAuthed = \Auth::user()->username;
            $prior = \Auth::user()->priority;
            $avatarChoice = \Auth::user()->avatarChoice;
            $department = \Auth::user()->部門;

            Session::put('username', $usernameAuthed);
            Session::put('priority', $prior);
            Session::put('avatarChoice', $avatarChoice);
            Session::put('department', $department);

            $this->authenticated($request, \Auth::user()); // set the login db
            if (\Auth::user()->preferred_lang == null) {
                Session::put('locale', 'en');
                \App::setLocale('en');
            } else {
                Session::put('locale', \Auth::user()->preferred_lang);
                \App::setLocale(\Auth::user()->preferred_lang);
            } // else

            DB::beginTransaction();

            try {
                $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());
                $affected = DB::table('login')
                    ->where('username', '=', \Auth::user()->username)
                    ->update(['last_login_time' => $datetime]);

                DB::commit();
                return \Response::json(['message' => 'Log in successful !']); // Status code
                // all good
            } catch (\Exception $e) {
                dd($e);
                DB::rollback();
                return \Response::json(['message' => $e], 420); // Status code here
                // something went wrong
            } // try catch

            return \Response::json(['message' => \DB::connection()->getDatabaseName()], 420); // Status code here
        } // if
        else { // login failed
            return \Response::json(['message' => \DB::connection()->getDatabaseName()], 420); // Status code here
        } // else
    } // login

    protected function attemptRecentlyLoginDBForMultiSiteUsers(Request $request)
    {
        $databaseArray = config('database_list.databases');
        $latestLoginTime = new DateTime("1996-12-10 16:52:36.000");
        $mostrecentDB = null;

        foreach ($databaseArray as $site) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
            \DB::purge(env("DB_CONNECTION"));
            $user = Login::where([
                'username' => $request->work_id,
            ])->first();

            if ($user !== null) {
                if ($user->available_dblist == null || $user->available_dblist == "" || count(explode("_", $user->available_dblist)) <= 1) {
                    $tempDateTime = new DateTime($user->last_login_time);
                    if ($tempDateTime > $latestLoginTime) {
                        $mostrecentDB = $site;
                        $latestLoginTime = $tempDateTime;
                    } // if
                } // if
                else {
                    if (in_array(str_replace(" Consumables management", "", $site), explode("_", $user->available_dblist))) {
                        // if available_dblist contains current site
                        $tempDateTime = new DateTime($user->last_login_time);
                        if ($tempDateTime > $latestLoginTime) {
                            $mostrecentDB = $site;
                            $latestLoginTime = $tempDateTime;
                        } // if
                    } // if
                } // else
            } // if

        } // foreach

        return $mostrecentDB;
    } // attemptRecentlyLoginDBForMultiSiteUsers

    //OA Account Login
    public function OALogin(Request $request)
    {
        $databaseArray = config('database_list.databases');
        // dd($databaseArray); //test

        try {
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            $recentSite = $this->attemptRecentlyLoginDBForMultiSiteUsers($request);
            if ($recentSite == null) {
                // if the OA account is new to us
                Session::put('work_id', $request->work_id);
                Session::put('user_name', $request->user_name);
                Session::put('dept_name', $request->dept_name);
                Session::put('office_mail', $request->office_mail);

                Session::put('m_name', $request->m_name);
                Session::put('m_office_mail', $request->m_office_mail);
                Session::put('m_dept_name', $request->m_dept_name);
                Session::put('m_work_id', $request->m_work_id);
                Session::put('m_priority', $request->m_priority);

                Session::put('m2_name', $request->m2_name);
                Session::put('m2_office_mail', $request->m2_office_mail);
                Session::put('m2_dept_name', $request->m2_dept_name);
                Session::put('m2_work_id', $request->m2_work_id);
                Session::put('m2_priority', $request->m2_priority);
                return redirect()->route('member.New_OA_Login');
            } // if

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $recentSite);
            \DB::purge(env("DB_CONNECTION"));
            $encrypt_site = \Crypt::encrypt($recentSite);

            DB::beginTransaction();

            $affected = DB::table('login')
                ->where('username', '=', $request->work_id)
                ->update([
                    'last_login_time' => $datetime
                ]);

            DB::table('人員信息')->upsert([
                [
                    '工號' => $request->work_id,
                    '姓名' => $request->user_name,
                    '部門' => $request->dept_name,
                    'email' => $request->office_mail,
                    '主管工號' => $request->m_work_id
                ],
                [
                    '工號' => $request->m_work_id,
                    '姓名' => $request->m_name,
                    '部門' => $request->m_dept_name,
                    'email' => $request->m_office_mail,
                    '主管工號' => $request->m2_work_id
                ]
            ], ['工號'], ['姓名', '部門', 'email', '主管工號']);

            DB::table('人員信息')->upsert([
                [
                    '工號' => $request->m2_work_id,
                    '姓名' => $request->m2_name,
                    '部門' => $request->m2_dept_name,
                    'email' => $request->m2_office_mail,
                ]
            ], ['工號'], ['姓名', '部門', 'email']);


            DB::commit();

            $encrypt_id = \Crypt::encrypt($request->work_id);
            return redirect('/?S=' . $encrypt_id . '&D=' . $encrypt_site);
            // all good
        } catch (\Exception $e) {
            dd($e); // test
            DB::rollback();
            return \Response::json(['message' => $e], 420); // Status code here
            // something went wrong
        } // try catch

    } // OAlogin

    // switch the user ( with $user->available_dblist contains the target site ) to another site
    // the switch site url href="https://XXXXXXX/switchSite/HQ_TEST_Consumables_management"
    // and the actual DB Name in Server: HQ TEST Consumables management ( Notice the "_" are now all spaces )
    public function switchSite(Request $request, $site_name)
    {
        $ifDBListContainsTargetSite = false;
        $DBName = str_replace('_', ' ', $site_name);
        foreach (explode("_", \Auth::user()->available_dblist) as $shortSite) {
            if ($DBName === ($shortSite . " Consumables management")) {
                $ifDBListContainsTargetSite = true;
                break;
            } // if
        } // foreach

        if ($request->user()->can('canSwitchSites', Login::class) && $ifDBListContainsTargetSite) {
            $previousUser = \Auth::user();
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            \Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerate();

            $request->session()->flush();

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $DBName);
            \DB::purge(env("DB_CONNECTION"));

            $user = Login::updateOrCreate(
                ['username' => $previousUser->username],
                [
                    'password' => $previousUser->password,
                    'priority' => $previousUser->priority,
                    'avatarChoice' => $previousUser->avatarChoice,
                    'last_login_time' => $datetime,
                    'update_priority_time' => $datetime,
                    'available_dblist' => $previousUser->available_dblist,
                    'preferred_lang' => $previousUser->preferred_lang,
                ]
            );

            \Auth::login($user);
            $request->session()->regenerate();
            $usernameAuthed = \Auth::user()->username;
            $prior = \Auth::user()->priority;
            $avatarChoice = \Auth::user()->avatarChoice;
            Session::put('username', $usernameAuthed);
            Session::put('priority', $prior);
            Session::put('avatarChoice', $avatarChoice);
            Session::put('department', \Auth::user()->部門);
            Session::put('locale', $previousUser->preferred_lang);
            \App::setLocale($previousUser->preferred_lang);
            session(['database' => $DBName]);
        } // if

        return redirect()->back();
    } // switchSite

    // 權限0的User可透過此功能修改可登入DB清單
    public function update_available_dblist(Request $request)
    {
        $username = $request->input('username');
        $dbListStr = $request->input('list');
        $databaseArray = config('database_list.databases');
        $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());
        $error = '';

        foreach ($databaseArray as $site) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
            \DB::purge(env("DB_CONNECTION"));

            DB::beginTransaction();

            try {
                $affected = DB::table('login')
                    ->where('username', '=', $username)
                    ->update(['available_dblist' => $dbListStr, 'update_priority_time' => $datetime]);

                DB::commit();
            } catch (\Exception $e) {
                // dd($e); // test
                return \Response::json(['message' => $e], 420); // Status code here

                DB::rollback();
                // something went wrong
            } // try catch
        } // foreach

        return \Response::json(['message' => "Updated !"], 200); // Status code here

    } // update_available_dblist

    //register newly logged in OA account
    public function register(Request $request)
    {
        $site = $request->input('site');
        $job_id = $request->input('job_id');
        $email = $request->input('email');
        $name = $request->input('p_name');
        $department = $request->input('dep');
        $profilePic = intval($request->input('profilePic'));
        $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
        \DB::purge(env("DB_CONNECTION"));

        DB::table('login')
            ->insert([
                'username' => $job_id, 'password' => "123456", 'priority' => 4,
                'avatarChoice' => $profilePic,
                'last_login_time' => $datetime, 'available_dblist' =>  str_replace(" Consumables management", "", $site)
            ]);

        // insert self & manager data
        DB::table('人員信息')->upsert([
            [
                '工號' => $request->work_id,
                '姓名' => $request->user_name,
                '部門' => $request->dept_name,
                'email' => $request->office_mail,
                '主管工號' => $request->m_work_id
            ],
            [
                '工號' => $request->m_work_id,
                '姓名' => $request->m_name,
                '部門' => $request->m_dept_name,
                'email' => $request->m_office_mail,
                '主管工號' => $request->m2_work_id
            ]
        ], ['工號'], ['姓名', '部門', 'email', '主管工號']);

        // insert manager's manager data
        DB::table('人員信息')->upsert([
            [
                '工號' => $request->m2_work_id,
                '姓名' => $request->m2_name,
                '部門' => $request->m2_dept_name,
                'email' => $request->m2_office_mail,
            ]
        ], ['工號'], ['姓名', '部門', 'email']);

        $user = Login::where([
            'username' => $job_id,
        ])->first();
        \Auth::login($user);

        $request->session()->regenerate();
        $usernameAuthed = \Auth::user()->username;
        $prior = \Auth::user()->priority;
        $avatarChoice = \Auth::user()->avatarChoice;
        Session::put('username', $usernameAuthed);
        Session::put('priority', $prior);
        Session::put('avatarChoice', $avatarChoice);
        Session::put('department', \Auth::user()->部門);
        $this->authenticated($request, \Auth::user()); // set the login db

        return \Response::json(['message' => 'success insert']/* Status code here default is 200 ok*/);
    } // register

    //change password
    public function change(Request $request)
    {
        $rules1 = [
            'password' => ['required'],
            'newpassword' => ['required'],
            'surepassword' => ['required'],
        ];

        $rules2 = [
            'newMail' => [],
        ];

        if (Session::has('username') && $request->has('password')) {
            $this->validate($request, $rules1);
            if ($request->input('newpassword') === $request->input('surepassword')) {
                $username = Session::get('username');
                $password = DB::table('login')->where('username', $username)->value('password');
                // if (Hash::check($request->input('password'), $password)) {
                if ($request->input('password') === $password) {
                    DB::table('login')
                        ->where('username', $username)
                        // ->update(['password' => Hash::make($request->input('newpassword')), 'updated_at' => Carbon::now()]);
                        ->update(['password' => $request->input('newpassword')]);
                    $request->session()->flush();

                    return \Response::json([]/* Status code here default is 200 ok*/);
                } else {
                    return \Response::json(['message' => 'passwords are not the same'], 420/* Status code here default is 200 ok*/);
                } // else
            } else {
                return \Response::json(['message' => 'old password is wrong'], 421/* Status code here default is 200 ok*/);
            } // else
        } // if
        else if (Session::has('username') && $request->has('newMail')) {
            $this->validate($request, $rules2);
            if ($request->input('oldMail') === $request->input('newMail')) {
                // dont need to update Email
                return \Response::json(['message' => 'Email is the same as old one'], 200/* Status code here default is 200 ok*/);
            } // if
            else {
                if ($request->input('newMail') === null || $request->input('newMail') === "") { // delete the old email
                    DB::table('人員信息')
                        ->where('工號', \Auth::user()->username)
                        // ->update(['password' => Hash::make($request->input('newpassword')), 'updated_at' => Carbon::now()]);
                        ->update(['email' => NULL]);
                } // if
                else {
                    DB::table('人員信息')
                        ->where('工號', \Auth::user()->username)
                        // ->update(['password' => Hash::make($request->input('newpassword')), 'updated_at' => Carbon::now()]);
                        ->update(['email' => $request->input('newMail')]);
                } // else

                return \Response::json(['message' => 'Update successful'], 200/* Status code here default is 200 ok*/);
            } // else
            return \Response::json(['message' => 'weird error'], 421/* Status code here default is 200 ok*/);
        } // else if
        else {
            return redirect(route('member.login'));
        } // else
    } // change password


    //用戶信息更新
    public function usernamechange(Request $request)
    {
        if (Session::has('username')) {

            $username = $request->input('username');
            $priority = $request->input('priority');
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            DB::table('login')
                ->where('username', $username)
                ->update(['priority' => $priority, 'update_priority_time' => $datetime]);

            return \Response::json(['message' => 'success']/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        } // if else
    } // usernamechange()

    //用戶信息刪除
    public function usernameDel(Request $request)
    {
        //將此工號從每個DB都刪除 下次此人登入時應該要從選擇廠別重新開始
        $username = $request->input('username');
        $databaseArray = config('database_list.databases');
        try {
            $currentDB = \Config::get('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            // dd($currentDB); // test
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                \DB::table('login')
                    ->where('username', '=', $username)
                    ->delete();
            } // foreach

            // reset back to login db, just in case
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $currentDB);
            \DB::purge(env("DB_CONNECTION"));
            return \Response::json(['message' => 'success']/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e, "\n";
            dd($e); // test
            return \Response::json(['message' => json_encode($e)], 420);
        } // try catch
    } // usernameDel()

    //用戶信息查詢
    public function searchusername(Request $request)
    {
        $input = $request->input('username');

        $datas = DB::table('login')
            ->where('username', 'like', $input . '%')
            ->where('priority', '<>', 1)
            ->get();

        return view("member.searchusernameok")
            ->with(['data' => $datas]);
    }

    //logout
    public function logout(Request $request)
    {
        \Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->regenerate();

        $request->session()->flush();

        return redirect(url('/member/login'));
    } // logout

    //人員信息刪除,新增
    public function numberchangeordel(Request $request)
    {
        $select = $request->input('select');
        $count = $request->input('count');
        $number = $request->input('number');
        $numbers = DB::table('人員信息')->pluck('工號');
        $newname = $request->input('newname');
        $newnumber = $request->input('newnumber');
        $newdep = $request->input('newdep');

        //delete
        if ($select === "刪除") {
            for ($i = 0; $i < $count; $i++) {
                DB::beginTransaction();
                try {
                    DB::table('人員信息')
                        ->where('工號', $number[$i])
                        ->delete();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
            }
            return \Response::json(['message' => $count, 'status' => 202]/* Status code here default is 200 ok*/);
        }
        //new
        else {

            DB::beginTransaction();
            //job number repeat
            for ($i = 0; $i < count($numbers); $i++) {
                if (strcasecmp($newnumber, $numbers[$i]) === 0) {
                    return \Response::json(['message' => Lang::get('loginPageLang.jobrepeat')], 421/* Status code here default is 200 ok*/);
                } else {
                    continue;
                }
            }

            DB::table('人員信息')->insert(['工號' => $newnumber, '姓名' => $newname, '部門' => $newdep]);
            DB::commit();
            return \Response::json(['message' => $count, 'status' => 201]/* Status code here default is 200 ok*/);
        } // if else
    } // numberchangeordel
}
