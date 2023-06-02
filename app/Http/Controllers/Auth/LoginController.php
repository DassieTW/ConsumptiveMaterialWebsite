<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\人員信息;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use League\CommonMark\Cursor;

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
            Session::put('username', $usernameAuthed);
            Session::put('priority', $prior);
            Session::put('avatarChoice', $avatarChoice);
            $this->authenticated($request, \Auth::user()); // set the login db

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

    protected function attemptSSOLogin(Request $request)
    {
        $credentials = [
            'username' => $request->work_id,
        ];

        // dd($request->site) ; // test
        // return \Auth::attempt($credentials);

        // login without hashed password
        $user = Login::where([
            'username' => $request->work_id,
        ])->first();

        if ($user) {
            \Auth::login($user);
            return true;
        } // if
        else {
            return false;
        } // else
    } // attemptSSOLogin

    //OA Account Login
    public function OALogin(Request $request)
    {
        $databaseArray = \Config::get('database_list.databases');
        dd($request); //test
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->site);
        \DB::purge(env("DB_CONNECTION"));

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();

            $usernameAuthed = \Auth::user()->username;
            $prior = \Auth::user()->priority;
            $avatarChoice = \Auth::user()->avatarChoice;
            Session::put('username', $usernameAuthed);
            Session::put('priority', $prior);
            Session::put('avatarChoice', $avatarChoice);
            $this->authenticated($request, \Auth::user()); // set the login db

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

    //search by job number
    /*public function search(Request $request)
    {
        Session::forget('number');
        Session::forget('name');
        Session::forget('department');
        if ($request->input('number') !== null) {
            $number = $request->input('number');
            $name = DB::table('人員信息')->where('工號', $number)->value('姓名');
            $department = DB::table('人員信息')->where('工號', $number)->value('部門');
            $reDive = new responseObj();
            if ($name !== NULL && $department !== NULL) {
                Session::put('number', $number);
                Session::put('name', $name);
                Session::put('department', $department);
                $reDive->boolean = true;
                $myJSON = json_encode($reDive);
                echo $myJSON;
                /*return view('member.searchok')
                    ->with('number' , $number)
                    ->with('name', $name)
                    ->with('department', $department);*/
    //         } else {
    //             $reDive->boolean = false;
    //             $myJSON = json_encode($reDive);
    //             echo $myJSON;
    //             /*return back()->withErrors([
    //                     'number' => 'Job number is not exist , Please enter another job number',
    //                     ]);*/
    //         }
    //     } else {
    //         return view('member.search');
    //     }
    // }


    //register login people
    public function register(Request $request)
    {
        $username = $request->input('username');
        //$password = Hash::make($request->input('password'));
        $password = $request->input('password');
        $priority = $request->input('priority');
        $email = $request->input('email');
        $name = $request->input('name');
        $department = $request->input('department');
        $profilePic = intval($request->input('profilePic'));
        $names = DB::table('login')->pluck('username');
        for ($i = 0; $i < count($names); $i++) {
            if (strcasecmp($username, $names[$i]) === 0) {

                return \Response::json(['message' => 'username repeat'], 420/* Status code here default is 200 ok*/);
            } else {
                continue;
            }
        } // for

        DB::table('login')
            ->insert([
                'username' => $username, 'password' => $password, 'priority' => $priority,
                '姓名' => $name, '部門' => $department, 'avatarChoice' => $profilePic, /*, 'created_at' => Carbon::now(),*/
                'email' => $email
            ]);

        $request->session()->flush();

        return \Response::json(['message' => 'success insert']/* Status code here default is 200 ok*/);
    }

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
                    DB::table('login')
                        ->where('username', \Auth::user()->username)
                        // ->update(['password' => Hash::make($request->input('newpassword')), 'updated_at' => Carbon::now()]);
                        ->update(['email' => NULL]);
                } // if
                else {
                    DB::table('login')
                        ->where('username', \Auth::user()->username)
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


    //new people information
    public function new(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('number') !== null && $request->input('name') !== null) {
                $number = $request->input('number');
                $name = $request->input('name');
                $department = $request->input('department');
                $numbers = DB::table('人員信息')->pluck('工號');
                //job length not 9
                if (strlen($number) !== 9) {
                    return \Response::json(['message' => 'job number isn\'t 9'], 420/* Status code here default is 200 ok*/);
                }
                //job number repeat
                for ($i = 0; $i < count($numbers); $i++) {
                    if (strcasecmp($number, $numbers[$i]) === 0) {
                        return \Response::json(['message' => 'job number is repeat'], 421/* Status code here default is 200 ok*/);
                    } else {
                        continue;
                    }
                }

                DB::table('人員信息')
                    ->insert(['工號' => $number, '姓名' => $name, '部門' => $department]);

                return \Response::json(['message' => 'success']/* Status code here default is 200 ok*/);
            } else {
                return view('member.new');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    /*//update people information
    public function update(Request $request)
    {
        if (Session::has('username')) {
            if (Session::has('number')) {
                $reDive = new responseObj();
                $number = $request->input('number');
                $name = $request->input('name');
                $department = $request->input('department');
                DB::table('人員信息')
                    ->where('工號', Session::get('number'))
                    ->update(['工號' => $number, '姓名' => $name, '部門' => $department]);
                Session::forget('number');
                Session::forget('name');
                Session::forget('department');
                $reDive->boolean = true;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            } else {
                return redirect(route('member.search'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }*/

    //人員信息更新或刪除
    public function numberchangeordel(Request $request)
    {
        if (Session::has('username')) {

            $select = $request->input('select');
            $now = Carbon::now();
            $count = $request->input('count');
            $name = $request->input('name');
            $number = $request->input('number');
            $department = $request->input('department');
            //delete
            if ($select == "刪除") {
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

                return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
            }
            //change
            if ($select == "更新") {
                for ($i = 0; $i < $count; $i++) {
                    DB::beginTransaction();
                    try {
                        人員信息::where('工號', $number[$i])
                            ->update([
                                '姓名' => $name[$i], '部門' => $department[$i],
                                /*'updated_at' => Carbon::now()*/
                            ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                    }
                }
                return \Response::json(['message' => $count, 'status' => 201]/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //人員信息查詢
    public function searchnumber(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('number') === null) {
                return view('member.searchnumberok')->with(['data' => 人員信息::cursor()]);
            } else if ($request->input('number') !== null && strlen($request->input('number')) <= 9) {
                $input = $request->input('number');

                $datas = DB::table('人員信息')
                    ->where('工號', 'like', $input . '%')
                    ->get();

                return view("member.searchnumberok")
                    ->with(['data' => $datas]);
            } else {
                return back()->withErrors([
                    'number' => trans('validation.regex'),
                ]);
            }
        } else {
            return redirect(route('member.login'));
        }
    }


    //用戶信息更新
    public function usernamechangeordel(Request $request)
    {
        if (Session::has('username')) {

            $count = $request->input('count');
            $username = $request->input('username');
            $priority = $request->input('priority');
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

            for ($i = 0; $i < $count; $i++) {

                DB::table('login')
                    ->where('username', $username[$i])
                    ->update(['priority' => $priority[$i], 'update_priority_time' => $datetime]);
            }
            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } else {
            return redirect(route('member.login'));
        }
    }

    //用戶信息查詢
    public function searchusername(Request $request)
    {
        if (Session::has('username')) {
            // if ($request->input('username') === null) {
            //     return view('member.searchusernameok')->with(['data' => Login::cursor()]);
            // } else if ($request->input('username') !== null) {
            $input = $request->input('username');

            $datas = DB::table('login')
                ->where('username', 'like', $input . '%')
                ->where('priority', '<>', 1)
                ->get();

            return view("member.searchusernameok")
                ->with(['data' => $datas]);
            // }
        } else {
            return redirect(route('member.login'));
        }
    }

    //logout
    public function logout(Request $request)
    {
        \Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flush();

        return redirect(url('/'));
    } // logout


    //人員信息上傳
    public function uploadpeople(Request $request)
    {
        if (Session::has('username')) {
            $this->validate($request, [
                'select_file'  => 'required|mimes:xls,xlsx'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('member.uploadpeople')->with(['data' => $sheetData]);
        } else {
            return redirect(route('member.login'));
        }
    }

    //人員信息上傳資料新增至資料庫
    public function insertuploadpeople(Request $request)
    {
        if (Session::has('username')) {

            $number = $request->input('number');
            $name = $request->input('name');
            $department =  $request->input('department');
            $record =  0;
            $count = count($number);
            $row = $request->input('row');
            $check = array();

            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $test = DB::table('人員信息')->where('工號', $number[$i])->value('姓名');

                    //判斷data是否重複
                    if ($test === null) {
                        DB::table('人員信息')
                            ->insert(['工號' => $number[$i], '姓名' => $name[$i], '部門' => $department[$i]/*, 'created_at' => Carbon::now()*/]);
                        $record++;
                        array_push($check, $row[$i]);
                    } else {
                        continue;
                    }
                } //for
                DB::commit();
                return \Response::json(['record' => $record, 'check' => $check]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            }
        } else {
            return redirect(route('member.login'));
        }
    }
}