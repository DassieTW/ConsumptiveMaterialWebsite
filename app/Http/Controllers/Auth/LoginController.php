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
            'username' => $request->username
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
            $department = \Auth::user()->detail_info->部門;

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
        // dd($request); // test

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
            $work_id = \Auth::user()->username;
            $user_name = \Auth::user()->detail_info->姓名;
            $dept_name = \Auth::user()->detail_info->部門;
            $office_mail = \Auth::user()->detail_info->email;
            $m_work_id = \Auth::user()->detail_info->主管工號;

            $user_m = 人員信息::where([
                '工號' => $m_work_id
            ])->first();

            if ($user_m !== null) {
                $m_name = $user_m->姓名;
                $m_dept_name = $user_m->部門;
                $m_office_mail = $user_m->email;
                $m2_work_id = $user_m->主管工號;

                $user_m2 = 人員信息::where([
                    '工號' => $m2_work_id
                ])->first();

                if ($user_m2 !== null) {
                    $m2_name = $user_m2->姓名;
                    $m2_dept_name = $user_m2->部門;
                    $m2_office_mail = $user_m2->email;
                } // if
            } // if

            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            \Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerate();

            $request->session()->flush();

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $DBName);
            \DB::purge(env("DB_CONNECTION"));

            // insert self & manager data
            DB::table('人員信息')->upsert([
                [
                    '工號' => $work_id,
                    '姓名' => $user_name,
                    '部門' => $dept_name,
                    'email' => $office_mail,
                    '主管工號' => $m_work_id
                ],
            ], ['工號'], ['姓名', '部門', 'email', '主管工號']);

            if ($user_m) {
                DB::table('人員信息')->upsert([
                    [
                        '工號' => $m_work_id,
                        '姓名' => $m_name,
                        '部門' => $m_dept_name,
                        'email' => $m_office_mail,
                        '主管工號' => $m2_work_id
                    ],
                ], ['工號'], ['姓名', '部門', 'email', '主管工號']);
            } // if

            // insert manager's manager data
            if ($user_m2) {
                DB::table('人員信息')->upsert([
                    [
                        '工號' => $m2_work_id,
                        '姓名' => $m2_name,
                        '部門' => $m2_dept_name,
                        'email' => $m2_office_mail,
                    ],
                ], ['工號'], ['姓名', '部門', 'email']);
            } // if

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
            Session::put('department', \Auth::user()->detail_info->部門);
            Session::put('locale', $previousUser->preferred_lang);
            \App::setLocale($previousUser->preferred_lang);
            session(['database' => $DBName]);
        } // if
        else if ($request->user()->can('canSwitchToSysDB', Login::class)) {
            $previousUser = \Auth::user();
            $work_id = \Auth::user()->username;
            $user_name = \Auth::user()->detail_info->姓名;
            $dept_name = \Auth::user()->detail_info->部門;
            $office_mail = \Auth::user()->detail_info->email;
            $m_work_id = \Auth::user()->detail_info->主管工號;

            $user_m = 人員信息::where([
                '工號' => $m_work_id
            ])->first();

            if ($user_m !== null) {
                $m_name = $user_m->姓名;
                $m_dept_name = $user_m->部門;
                $m_office_mail = $user_m->email;
                $m2_work_id = $user_m->主管工號;

                $user_m2 = 人員信息::where([
                    '工號' => $m2_work_id
                ])->first();

                if ($user_m2 !== null) {
                    $m2_name = $user_m2->姓名;
                    $m2_dept_name = $user_m2->部門;
                    $m2_office_mail = $user_m2->email;
                } // if
            } // if

            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            \Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerate();

            $request->session()->flush();

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $DBName);
            \DB::purge(env("DB_CONNECTION"));

            // insert self & manager data
            DB::table('人員信息')->upsert([
                [
                    '工號' => $work_id,
                    '姓名' => $user_name,
                    '部門' => $dept_name,
                    'email' => $office_mail,
                    '主管工號' => $m_work_id
                ],
            ], ['工號'], ['姓名', '部門', 'email', '主管工號']);

            if ($user_m) {
                DB::table('人員信息')->upsert([
                    [
                        '工號' => $m_work_id,
                        '姓名' => $m_name,
                        '部門' => $m_dept_name,
                        'email' => $m_office_mail,
                        '主管工號' => $m2_work_id
                    ],
                ], ['工號'], ['姓名', '部門', 'email', '主管工號']);
            } // if

            // insert manager's manager data
            if ($user_m2) {
                DB::table('人員信息')->upsert([
                    [
                        '工號' => $m2_work_id,
                        '姓名' => $m2_name,
                        '部門' => $m2_dept_name,
                        'email' => $m2_office_mail,
                    ],
                ], ['工號'], ['姓名', '部門', 'email']);
            } // if

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
            Session::put('department', \Auth::user()->detail_info->部門);
            Session::put('locale', $previousUser->preferred_lang);
            \App::setLocale($previousUser->preferred_lang);
            session(['database' => $DBName]);
        } // else if

        return redirect()->back();
    } // switchSite

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
                'username' => $job_id,
                'password' => "123456",
                'priority' => 4,
                'avatarChoice' => $profilePic,
                'last_login_time' => $datetime,
                'available_dblist' =>  str_replace(" Consumables management", "", $site)
            ]);

        $work_id = Session::get('work_id');
        $user_name = Session::get('user_name');
        $dept_name = Session::get('dept_name');
        $office_mail = Session::get('office_mail');

        $m_name = Session::get('m_name');
        $m_office_mail = Session::get('m_office_mail');
        $m_dept_name = Session::get('m_dept_name');
        $m_work_id = Session::get('m_work_id');
        $m_priority = Session::get('m_priority');

        $m2_name = Session::get('m2_name');
        $m2_office_mail = Session::get('m2_office_mail');
        $m2_dept_name = Session::get('m2_dept_name');
        $m2_work_id = Session::get('m2_work_id');
        $m2_priority = Session::get('m2_priority');

        // insert self & manager data
        DB::table('人員信息')->upsert([
            [
                '工號' => $work_id,
                '姓名' => $user_name,
                '部門' => $dept_name,
                'email' => $office_mail,
                '主管工號' => $m_work_id
            ],
            [
                '工號' => $m_work_id,
                '姓名' => $m_name,
                '部門' => $m_dept_name,
                'email' => $m_office_mail,
                '主管工號' => $m2_work_id
            ]
        ], ['工號'], ['姓名', '部門', 'email', '主管工號']);

        // insert manager's manager data
        DB::table('人員信息')->upsert([
            [
                '工號' => $m2_work_id,
                '姓名' => $m2_name,
                '部門' => $m2_dept_name,
                'email' => $m2_office_mail,
            ]
        ], ['工號'], ['姓名', '部門', 'email']);

        $user = Login::where([
            'username' => $job_id
        ])->first();

        \Auth::login($user);

        $request->session()->regenerate();
        $usernameAuthed = \Auth::user()->username;
        $prior = \Auth::user()->priority;
        $avatarChoice = \Auth::user()->avatarChoice;
        Session::put('username', $usernameAuthed);
        Session::put('priority', $prior);
        Session::put('avatarChoice', $avatarChoice);
        Session::put('department', \Auth::user()->detail_info->部門);
        $this->authenticated($request, \Auth::user()); // set the login db

        return \Response::json(['message' => 'success insert']/* Status code here default is 200 ok*/);
    } // register

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
} // LoginController
