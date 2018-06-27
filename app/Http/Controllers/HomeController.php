<?php

namespace App\Http\Controllers;

use App\Classes\HomeWrapperClass;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home_wrapper= new HomeWrapperClass();
        $qvms=$home_wrapper->getAllQVMs();
        $servers=$home_wrapper->getAllServers();
        $users=$home_wrapper->getAllUsers();
        $info=array('qvms'=>count($qvms),
            'servers'=>count($servers),
            'users'=>count($users));

        return view('home')->with('info', $info);
    }
}
