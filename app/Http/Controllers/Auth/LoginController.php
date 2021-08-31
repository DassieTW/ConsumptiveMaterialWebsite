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
use App\Http\Controllers\responseObj;
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

    use AuthenticatesUsers;

    //login
    public function login(Request $request)
    {
        // exit(0); // test
        $input = $request->all();

        $credentials = $request->validate(
            [
                'username' => ['required'],
                'password' => ['required'],
            ],
        );

        // exit(0); // test

        if (\Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $reDive = new responseObj();
            $usernameAuthed = \Auth::user()->username;
            $prior = \Auth::user()->priority;
            $avatarChoice = \Auth::user()->avatarChoice;
            Session::put('username', $usernameAuthed);
            Session::put('priority', $prior);
            Session::put('avatarChoice', $avatarChoice);
            return \Response::json(['message' => 'Log in successful !']); // Status code here
        } // if
        else { // login failed
            return \Response::json(['message' => 'Invalid Username and/or Password.'], 420); // Status code here
        } // else
    } // login

    //search by job number
    public function search(Request $request)
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
            } else {
                $reDive->boolean = false;
                $myJSON = json_encode($reDive);
                echo $myJSON;
                /*return back()->withErrors([
                        'number' => 'Job number is not exist , Please enter another job number',
                        ]);*/
            }
        } else {
            return view('member.search');
        }
    }


    //register login people
    public function register(Request $request)
    {
        $reDive = new responseObj();
        if ($request->input('username') !== null && $request->input('password2') !== null) {
            if ($request->input('password') === $request->input('password2')) {
                $username = $request->input('username');
                $password = Hash::make($request->input('password'));
                $priority = $request->input('priority');
                $name = $request->input('name');
                $department = $request->input('department');
                $names = DB::table('login')->pluck('username');
                for ($i = 0; $i < count($names); $i++) {
                    if ($username == $names[$i]) {

                        return back()->withErrors([
                            'username' => trans('loginPageLang.usernamerepeat'),
                        ]);
                    } else {
                        continue;
                    }
                }

                DB::table('login')
                ->insert(['username' => $username , 'password' => $password , 'priority' => $priority ,
                '姓名' => $name , '部門' => $department , 'created_at' => Carbon::now()]);

                $request->session()->flush();

                $mess = trans('loginPageLang.new').trans('loginPageLang.success').trans('loginPageLang.againlogin');
                echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    window.location.href='login';
                    </script>");
            } else {
                /*$reDive->boolean = true;
                $reDive->passbool = false;
                $myJSON = json_encode($reDive);
                echo $myJSON;*/
                return back()->withErrors([
                        'password' => '',
                        'password2' => trans('loginPageLang.errorpassword2'),
                    ]);
            }
        } else {
            return view('member.register');
        }
    }

    //change password
    public function change(Request $request)
    {
        $reDive = new responseObj();
        if (Session::has('username')) {
            if ($request->input('password') !== null && $request->input('newpassword') !== null) {
                if ($request->input('newpassword') === $request->input('surepassword')) {
                    $username = Session::get('username');
                    $password = DB::table('login')->where('username', $username)->value('password');
                    if (Hash::check($request->input('password'), $password)) {
                        DB::table('login')
                            ->where('username', $username)
                            ->update(['password' => Hash::make($request->input('newpassword'))]);

                        $request->session()->flush();

                        Session::put('change', $username);
                        $reDive->boolean = true;
                        $reDive->passbool = true;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                    } else {
                        $reDive->boolean = true;
                        $reDive->passbool = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                        return;
                    }
                } else {
                    $reDive->boolean = false;
                    $reDive->passbool = false;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                }
            } else {
                return view('member.change');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //new people information
    public function new(Request $request)
    {
        Session::forget('new');
        if (Session::has('username')) {
            if ($request->input('number') !== null && $request->input('name') !== null) {
                $number = $request->input('number');
                $name = $request->input('name');
                $department = $request->input('department');
                $numbers = DB::table('人員信息')->pluck('工號');
                $reDive = new responseObj();
                if(strlen($number) !== 9)
                {
                    $reDive->boolean = false;
                    $reDive->passbool = true;
                    $myJSON = json_encode($reDive);
                    echo $myJSON;
                    return;
                }
                for ($i = 0; $i < count($numbers); $i++) {
                    if ($number == $numbers[$i]) {
                        $reDive->boolean = true;
                        $reDive->passbool = false;
                        $myJSON = json_encode($reDive);
                        echo $myJSON;
                        return;
                        /*return back()->withErrors([
                            'number' => 'Job number is repeated , Please enter another job number',
                            ]);*/
                    } else {
                        continue;
                    }
                }

                DB::table('人員信息')
                ->insert(['工號' => $number , '姓名' => $name , '部門' => $department]);

                Session::put('new', $number);
                $reDive->boolean = true;
                $reDive->passbool = true;
                $myJSON = json_encode($reDive);
                echo $myJSON;
            } else {
                return view('member.new');
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //update people information
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
    }

    //人員信息更新或刪除
    public function numberchangeordel(Request $request)
    {
        if (Session::has('username')) {
            //delete
            if ($request->has('delete')) {
                $record = 0;
                $count = $request->input('count');
                for ($i = 0; $i < $count; $i++) {
                    if ($request->has('innumber' . $i)) {
                        DB::table('人員信息')
                            ->where('工號', $request->input('number' . $i))
                            ->delete();
                            $record ++;
                    } else {
                        continue;
                    }
                }

                $mess = trans('loginPageLang.total').$record.trans('loginPageLang.record')
                .trans('loginPageLang.pinf').trans('loginPageLang.delete')
                .trans('loginPageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/member';
                </script>");
            }
            //change
            else if ($request->has('change')) {
                $count = $request->input('count');
                $time = 0;
                for ($i = 0; $i < $count; $i++) {
                    $name = $request->input('name' . $i);
                    $department = $request->input('department' . $i);
                    DB::table('人員信息')
                        ->where('工號', $request->input('number' . $i))
                        ->update(['姓名' => $name, '部門' => $department]);
                        $time ++;
                }
                $mess = trans('loginPageLang.total').$record.trans('loginPageLang.record')
                .trans('loginPageLang.pinf').trans('loginPageLang.change')
                .trans('loginPageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/member';
                </script>");
            } else {
                return redirect(route('member.number'));
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


    //用戶信息更新或刪除
    public function usernamechangeordel(Request $request)
    {
        if (Session::has('username')) {
            //delete
            if ($request->has('delete')) {
                $record = 0;
                $count = $request->input('count');
                for ($i = 0; $i < $count; $i++) {
                    if ($request->has('innumber' . $i)) {
                        DB::table('login')
                            ->where('username', $request->input('username' . $i))
                            ->delete();
                            $record ++;
                    } else {
                        continue;
                    }
                }
                $mess = trans('loginPageLang.total').$record.trans('loginPageLang.record')
                .trans('loginPageLang.user').trans('loginPageLang.delete')
                .trans('loginPageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/member';
                </script>");

            }
            //change
            /*else if($request->has('change'))
                {
                    $count = $request->input('count');
                    for($i = 0 ; $i < $count ; $i++)
                    {
                        $name = $request->input('name' . $i);
                        $department = $request->input('department' . $i);
                        DB::table('人員信息')
                            ->where('工號', $request->input('number' . $i))
                            ->update(['姓名' => $name , '部門' => $department]);
                    }
                    return view('member.changeok');
                }*/ else {
                return redirect(route('member.username'));
            }
        } else {
            return redirect(route('member.login'));
        }
    }

    //用戶信息查詢
    public function searchusername(Request $request)
    {
        if (Session::has('username')) {
            if ($request->input('username') === null) {
                return view('member.searchusernameok')->with(['data' => Login::cursor()]);
            } else if ($request->input('username') !== null) {
                $input = $request->input('username');

                $datas = DB::table('login')
                    ->where('username', 'like', $input . '%')
                    ->get();

                return view("member.searchusernameok")
                    ->with(['data' => $datas]);
            }
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
        if (Session::has('username'))
        {
            $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
            ]);
            $path = $request->file('select_file')->getRealPath();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            unset($sheetData[0]);
            return view('member.uploadpeople')->with(['data' => $sheetData]);
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //人員信息上傳頁面
    function uploadpeoplepage(Request $request)
    {
        if (Session::has('username'))
        {
            return view('member.uploadpeople1');
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

        //人員信息上傳資料新增至資料庫
        public function insertuploadpeople(Request $request)
        {
            if (Session::has('username'))
            {
                $count = $request->input('count');
                $numbers = DB::table('人員信息')->pluck('工號');
                $record = 0;
                for($i = 0 ; $i < $count ; $i ++)
                {
                    $number =  $request->input('data0'. $i);
                    $name =  $request->input('data1'. $i);
                    $department = $request->input('data2'. $i);
                    if(strlen($number) !== 9)
                    {
                        $mess = trans('loginPageLang.joblength');
                        echo ("<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        window.location.href='uploadpeople';
                        </script>");
                        //return view('member.uploadpeople1');
                    }
                    else
                    {

                        //判斷工號是否重複
                        for($i = 0 ; $i < count($numbers) ; $i ++)
                        {
                            if($number == $numbers[$i])
                            {
                                $mess = trans('loginPageLang.repeat');
                                echo ("<script LANGUAGE='JavaScript'>
                                window.alert('$mess');
                                window.location.href='uploadpeople';
                                </script>");
                                return;
                                /*return back()->withErrors([
                                'number' => '料號 is repeated , Please enter another 料號',
                                ]);*/
                            }
                            else
                            {
                                continue;
                            }
                        }
                        DB::beginTransaction();
                        try {
                            DB::table('人員信息')
                                ->insert(['工號' => $number , '姓名' => $name , '部門' => $department]);
                            DB::commit();
                            $record++;
                        }catch (\Exception $e) {
                            DB::rollback();
                            $mess = trans('loginPageLang.repeat');
                            echo ("<script LANGUAGE='JavaScript'>
                            window.alert('$mess'');
                            window.location.href='uploadpeople';
                            </script>");
                            //return view('member.uploadpeople1');
                        }
                    }

                }
                $mess = trans('loginPageLang.total').$record.trans('loginPageLang.record')
                .trans('loginPageLang.pinf').trans('loginPageLang.upload1')
                .trans('loginPageLang.success');
                echo ("<script LANGUAGE='JavaScript'>
                window.alert('$mess');
                window.location.href='/member';
                </script>");
            }

            else
            {
                return redirect(route('member.login'));
            }

        }







} // end of controller
