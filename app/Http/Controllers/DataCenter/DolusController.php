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

    $info=array('servers'=>$servers);


    return view('pages/servers')->with('info', $info);
}
    public function getUserDetails(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $users=$home_wrapper->getAllUsers();

        $info=array('users'=>$users);


        return view('pages/users')->with('info', $info);
    }
    public function getQVMDetails(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $qvms=$home_wrapper->getAllQVMs();
        $devices=$home_wrapper->getAllDevices();
        $blacklistIps=$home_wrapper->getAllBlacklistIPs();
        $info=array('qvms'=>$qvms,'blacklistIps'=>$blacklistIps,'devices'=>$devices);

        return view('pages/qvms')->with('info', $info);
    }
    public function getUserMigrationDetails(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $usermigrations=$home_wrapper->getAllUserMigrations();

        $info=array('usermigrations'=>$usermigrations);

        return view('pages/usermigrations')->with('info', $info);
    }
    public function getAllAttackHistory(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $attackHistory=$home_wrapper->getAllAttacks();

        $info=array('attackHistory'=>$attackHistory);

        return view('pages/attackhistory')->with('info', $info);
    }
    public function getBacklistedIPs(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $blacklistIps=$home_wrapper->getAllBlacklistIPs();

        $info=array('blacklistIps'=>$blacklistIps);

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

        $info=array('devices'=>$devices,'policies'=>$policies);

        return view('pages/policies')->with('info', $info);
    }
    public function getSuspiciousness(Request $request)
    {
        $home_wrapper= new HomeWrapperClass();
        $devices=$home_wrapper->getAllDevices();
        $suspicious_Scores = $home_wrapper->getAllSuspiciousnessScores();
        if(count($suspicious_Scores)>0){
             for($i=0;$i<count($suspicious_Scores);$i++){
                 $device_id= $suspicious_Scores[$i]['device_id'];
                 $score = $suspicious_Scores[$i]['score'];
                 $device_details = $home_wrapper->getADevices($device_id);
                 $qvm_details = $home_wrapper->getAllQVMs();
                 $no_of_qvms = count($qvm_details);
                 if($score>=1){
                     $all_attacks = $home_wrapper->getAllAttacks();
                     $attacker_id= 'attack'.strval(count($all_attacks)+1);
                     $sourceIP= $device_details[0]['ipv4'];
                     $packet_details = $home_wrapper->getAllPacketLogsByDevice($sourceIP);
                     $no_of_logs=count($packet_details);
                     $destinationIP = $packet_details[$no_of_logs-1]['ip_dst'];
                     //$home_wrapper->insertIntoAttacks($attacker_id,$sourceIP,$destinationIP);
                     $all_migrations = $home_wrapper->getAllUserMigrations();
                     $userMigrationsUID ='migration'.strval(count($all_migrations));
                     $migratedServerIP = $qvm_details[$no_of_qvms-1]['qvmIP'];
                     //$home_wrapper->insertIntoUserMigrations($userMigrationsUID,$sourceIP,$destinationIP,$migratedServerIP);
                     $all_blacklist = $home_wrapper->getAllBlacklistIPs();
                     $blacklist=true;
                     for($j=0; $j< count($all_blacklist);$j++){
                         if($sourceIP == $all_blacklist[$j]['ipAddress']){
                             $blacklist=false;
                         }
                     }
                     $whilte_listed_users = $home_wrapper->getAllWhiteListIPs();
                     if(count($whilte_listed_users)>0)
                     {
                         for($j=0; $j<count($whilte_listed_users);$j++){
                             if($sourceIP==$whilte_listed_users[$j]['ipv4']){
                                 $blacklist=false;
                             }
                         }
                     }
                     $macaddress = $device_details[0]['mac'];
                     if($blacklist){
                         $home_wrapper->insertIntoBlackLists($sourceIP,$macaddress);
                     }
                         $home_wrapper->insertIntoAttacks($attacker_id,$sourceIP,$destinationIP);
                         $home_wrapper->insertIntoUserMigrations($userMigrationsUID,$sourceIP,$destinationIP,$migratedServerIP);
                         $no_of_attacks = $home_wrapper->getNoofAttackers();
                         $home_wrapper->updateQVM($migratedServerIP,count($no_of_attacks));
                         $switch_devices_switch1 = $home_wrapper->getASwitchByDevices($device_id);
                         $destinationServer = $home_wrapper->getAServerByIPAddress($destinationIP);
                         $switch_devices_switch2 = $home_wrapper->getASwitchByDevices($destinationServer[0]['serverUID']);
                         if(count($switch_devices_switch1)>0 ) {
                             if(count($switch_devices_switch2)>0) {
                                 $sender = $sourceIP;
                                 $receiver = $destinationIP;
                                 $switch1 = $switch_devices_switch1[0]['switchID'];
                                 $redirectPort = $switch_devices_switch1[0]['port'];
                                 $qvm_port = 5;
                                 $switch2 = $switch_devices_switch2[0]['switchID'];
                                 //port 1 is to set to qvm
                                 $no_of_policies = count($home_wrapper->getAllPolicies());
                                 $policyID = 'Policy'.strval($no_of_policies+1);
                                 $deviceID = $device_id;
                                 $loaded = 1;
                                 $home_wrapper->setPolicies($policyID,$deviceID,$sourceIP,$destinationIP,$switch1,$switch2,$redirectPort,$qvm_port,$loaded);

                             }

                         }


                     
                 }
             }
        }

        $info = array('devices'=>$devices);

        return view('pages/suspicious')->with('info', $info);
    }

}
