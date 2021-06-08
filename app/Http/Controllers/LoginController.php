<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use DB;
use Session;
use Route;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

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
