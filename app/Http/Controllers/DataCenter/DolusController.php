<?php
/**
 * Created by PhpStorm.
 * User: ramya
 * Date: 4/23/2018
 * Time: 7:32 PM
 */

namespace App\Http\Controllers\DataCenter;
use App\Classes\HomeWrapperClass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DolusController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');
    }
public function getServerDetails(Request $request)
{
    $home_wrapper= new HomeWrapperClass();
    $servers=$home_wrapper->getAllServers();

    $info=array('servers'=>$servers
        );


    return view('pages/servers')->with('info', $info);
}
    public function getUserDetails(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $users=$home_wrapper->getAllUsers();

        $info=array('users'=>$users
        );


        return view('pages/users')->with('info', $info);
    }
    public function getQVMDetails(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $qvms=$home_wrapper->getAllQVMs();

        $info=array('qvms'=>$qvms
        );

        return view('pages/qvms')->with('info', $info);
    }
    public function getUserMigrationDetails(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $usermigrations=$home_wrapper->getAllUserMigrations();

        $info=array('usermigrations'=>$usermigrations
        );

        return view('pages/usermigrations')->with('info', $info);
    }
    public function getAllAttckHistory(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $attackHistory=$home_wrapper->getAllAttacks();

        $info=array('attackHistory'=>$attackHistory
        );

        return view('pages/attackhistory')->with('info', $info);
    }
    public function getBacklistedIPs(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $blacklistIps=$home_wrapper->getAllBlacklistIPs();

        $info=array('blacklistIps'=>$blacklistIps
        );

        return view('pages/blacklistips')->with('info', $info);
    }
    public function getSwitchAndDevices(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $switchdevices=$home_wrapper->getAllSwtichDevices();
        $switches=$home_wrapper->getAllSwitches();
        $devices=$home_wrapper->getAllDevices();

        $info=array('switches'=>$switches,
            'devices'=>$devices,
            'switchdevices'=>$switchdevices

        );

        return view('pages/switchdevices')->with('info', $info);
    }
    public function getPolicies(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $policies=$home_wrapper->getAllPolicies();
        $devices=$home_wrapper->getAllDevices();

        $info=array('devices'=>$devices,'policies'=>$policies
        );

        return view('pages/policies')->with('info', $info);
    }
    public function getSuspiciousness(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();

        $devices=$home_wrapper->getAllDevices();

        $info = array(
            'devices'=>$devices);

        return view('pages/suspicious')->with('info', $info);
    }
    public function pythonScripts(Request $request)
    {
        dd("cont");
    }
}
