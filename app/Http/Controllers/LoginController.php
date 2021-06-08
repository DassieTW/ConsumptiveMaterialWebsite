<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use DB;
use Session;
use Route;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\LoginController;


class LoginController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //


        return view('member.index');
    }
    //login
    public function login(Request $request)
    {

        if (Session::has('username'))
        {
            return view('member.infor' , ['logins' => Login::cursor()]);
        }
        else if($request->input('username') !== null && $request->input('password') !== null)
        {
            $username = array();
            $password = array();
            $priorarr = array();
            $names = DB::table('login')->pluck('username');
            $pass = DB::table('login')->pluck('password');
            $priority = DB::table('login')->pluck('priority');
            $name1 = $request->input('username');
            $password1 = $request->input('password');
            foreach($names as $name){
                array_push($username,$name);
            }
            foreach($pass as $pa){
                array_push($password,$pa);
            }
            foreach($priority as $pr){
                array_push($priorarr,$pr);
            }
            for($i = 0 ; $i < count($username) ; $i ++)
            {

                if($name1 == $username[$i] && Hash::check($password1,$password[$i])){
                    Session::put('username', $name1);
                    Session::put('priority', $priorarr[$i]);
                    return view('member.infor' , ['logins' => Login::cursor()]);
                }
                else{
                    continue;
                }
            }

            return back()->withErrors([
                'username' => ' ',
                'password' => 'Username or Password wrong!',
            ]);
        }
        else
        {
            return view('member.login');
        }
    }
    //register login people
    public function register(Request $request)
    {
        if($request->input('username') !== null && $request->input('password') !== null)
        {
            if($request->input('password') === $request->input('surepassword'))
            {
                $username = $request->input('username');
                $password = Hash::make($request->input('password'));
                $priority = $request->input('priority');
                $name = $request->input('name');
                $department = $request->input('department');
                $names = DB::table('login')->pluck('username');
                for($i = 0 ; $i < count($names) ; $i ++)
                {
                    if($username == $names[$i])
                    {
                        return back()->withErrors([
                        'username' => 'Username is repeated , Please enter another username',
                        ]);
                    }
                    else
                    {
                        continue;
                    }
                }
                DB::insert('insert into login (username, password , priority , 姓名 , 部門) values (?,?,?,?,?)',
                [$username, $password , $priority , $name , $department]);

                $request->session()->flush();
                return view('member.registerok');

            }
            else
            {
                return back()->withErrors([
                    'password' => '',
                    'surepassword' => 'Password confirmation does not match!',
                ]);
            }
        }
        else
        {
            return view('member.register');

        }
    }
    //change password
    public function change(Request $request)
    {
        if (Session::has('username'))
        {
            if($request->input('password') !== null && $request->input('newpassword') !== null)
            {
                if($request->input('newpassword') === $request->input('surepassword'))
                {
                    $username = Session::get('username');
                    $password = DB::table('login')->where('username', $username)->value('password');
                    if(Hash::check($request->input('password'),$password))
                    {
                        DB::table('login')
                        ->where('username', $username)
                        ->update(['password' => Hash::make($request->input('newpassword'))]);
                        $request->session()->flush();
                        return view('member.changeok');
                    }
                    else
                    {
                        return back()->withErrors([
                            'password' => 'Old Password is wrong! Please enter again',
                            ]);
                    }
                }
                else
                {
                    return back()->withErrors([
                        'newpassword' => ' ',
                        'surepassword' => 'Password confirmation does not match!',
                    ]);
                }
            }
            else
            {
                return view('member.change');
            }
        }

        else
        {
            return redirect(route('member.login'));
        }

    }

    //new people inf
    public function new(Request $request)
    {
        if (Session::has('username'))
        {
            if($request->input('number') !== null && $request->input('name') !== null)
            {
                    $number = $request->input('number');
                    $name = $request->input('name');
                    $department = $request->input('department');
                    $numbers = DB::table('人員信息')->pluck('工號');
                    for($i = 0 ; $i < count($numbers) ; $i ++)
                    {
                        if($number == $numbers[$i])
                        {
                            return back()->withErrors([
                            'number' => 'Job number is repeated , Please enter another job number',
                            ]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    DB::insert('insert into 人員信息 (工號, 姓名 , 部門) values (?,?,?)',
                    [$number, $name , $department]);

                    return view('member.newok');

            }
            else
            {
                return view('member.new');

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }
    //search
    public function search(Request $request)
    {
        if(Session::has('username'))
        {
            if($request->input('number') !== null)
            {

                $number = $request->input('number');
                $name = DB::table('人員信息')->where('工號', $number)->value('姓名');
                $department = DB::table('人員信息')->where('工號', $number)->value('部門');
                if($name !== NULL && $department !==NULL)
                {
                    Session::put('number', $number);
                    return view('member.searchok')
                    ->with('number' , $number)
                    ->with('name', $name)
                    ->with('department', $department);
                }
                else
                {
                    return back()->withErrors([
                        'number' => 'Job number is not exist , Please enter another job number',
                        ]);

                }
            }
            else
            {
                return view('member.search');

            }
        }
        else
        {
            return redirect(route('member.login'));
        }
    }

    //change change by job number
    public function changenumber(Request $request)
    {
        if (Session::has('username'))
        {
            if($request->input('name') !== NULL && $request->input('department') !== NULL)
            {
                $number = Session::get('number');
                $name = $request->input('name');
                $department = $request->input('department');
                DB::table('人員信息')
                ->where('工號', $number)
                ->update(['姓名' => $name , '部門' => $department]);
                $request->session()->forget('number');
                return view('member.changenumberok');
            }
            else if($request->input('name') !== NULL && $request->input('department') === NULL)
            {
                $number = Session::get('number');
                $name = $request->input('name');
                DB::table('人員信息')
                ->where('工號', $number)
                ->update(['姓名' => $name]);
                $request->session()->forget('number');
                return view('member.changenumberok');
            }
            else if($request->input('name') === NULL && $request->input('department') !== NULL)
            {
                $number = Session::get('number');
                $department = $request->input('department');
                DB::table('人員信息')
                ->where('工號', $number)
                ->update(['部門' => $department]);
                $request->session()->forget('number');
                return view('member.changenumberok');
            }
            else
            {
                return view('member.changenumber');

            }
        }

        else
        {
            return redirect(route('member.login'));
        }

    }


    //logout
    public function logout(Request $request)
    {
        if (Session::has('username'))
        {

           $request->session()->flush();
           return view('member.logout');

        }
        else
        {
            return redirect(route('member.login'));
        }
    }



}
