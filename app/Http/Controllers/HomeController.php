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
       $switches = $home_wrapper->getAllSwitches();
        for($i=0;$i<count($switches);$i++){
           if($switches[$i]['name']=='root-switch'){
             $root_switch_id = $switches[$i]['switchID'];
           }
           else{
             $slave_switch_id = $switches[$i]['switchID'];
            }
        }
        $rootswitch_logs = $home_wrapper->getLogsBySwitch($root_switch_id);
        $slaveswitch_logs = $home_wrapper->getLogsBySwitch($slave_switch_id);
        $info=array('qvms'=>count($qvms),
            'servers'=>count($servers),
            'users'=>count($users),
            'rootswitch_logs'=>$rootswitch_logs,
            'slaveswitch_logs'=>$slaveswitch_logs);


        return view('home')->with('info', $info);
    }
}
