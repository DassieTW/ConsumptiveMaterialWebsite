<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use DB;
use Session;
use Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\LoginController;

class PriorityController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function call()
    {
        //
        $priority = Session::get('priority');
        return view('priority.call')->with('pr',$priority);
    }
    public function data()
    {
        //
        $priority = Session::get('priority');
        return view('priority.data')->with('pr',$priority);
    }
    public function shop()
    {
        //
        $priority = Session::get('priority');
        return view('priority.shop')->with('pr',$priority);
    }
    public function inwarehouse()
    {
        //
        $priority = Session::get('priority');
        return view('priority.inwarehouse')->with('pr',$priority);
    }
    public function outwarehouse()
    {
        //
        $priority = Session::get('priority');
        return view('priority.outwarehouse')->with('pr',$priority);;
    }
}
