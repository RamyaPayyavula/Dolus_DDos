<?php
/**
 * Created by PhpStorm.
 * User: ramya
 * Date: 5/5/2018
 * Time: 11:52 AM
 */

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeWrapperClass
{

    public function getAllQVMs(){
        #$db = 'test';
        $table = "qvm";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }

    public function getAllServers(){
        #$db = 'test';
        $table = "servers";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllUsers(){
        #$db = 'test';
        $table = "users";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllUserMigrations(){
        #$db = 'test';
        $table = "usermigration";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllBlacklistIPs(){
        #$db = 'test';
        $table = "blacklist";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllAttacks(){
        #$db = 'test';
        $table = "attackhistory";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllSwitches(){
        #$db = 'test';
        $table = "switches";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllDevices(){
        #$db = 'test';
        $table = "devices";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllSwtichDevices(){
        #$db = 'test';
        $table = "switch_devices";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllPolicies(){
        #$db = 'test';
        $table = "policies";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function setPolicies($policyID,$deviceID,$policy,$loaded,$ipv6, $mac){
        $db = 'test';
        $table = "policies";
        $sql = "INSERT INTO $table(`policyID `,`deviceID`,`policy`,`loaded`,`ipv6`,`mac`) VALUES ('$policyID','$deviceID','$policy','$loaded','$ipv6','$mac')";
        DB::connection($db)->select($sql);

    }
    public function deletePolicies($deviceID){
        $db = 'test';
        $table = "policies";

       $sql = "DELETE FROM $table WHERE deviceID=$deviceID";
        DB::connection($db)->select($sql);


    }
    public function getAllSuspiciousnessScores(){

        $table = "suspiciousness_scores";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }

    // get SS by user/device
    public function getSuspiciousnessScoreByDevice($device_id){

        $table = "suspiciousness_scores";

        $data = DB::connection('mysql')->table($table)->where('device_id', $device_id)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }

    // get SS by time
    public function getSuspiciousnessScoreByTime(){

        $table = "suspiciousness_scores_by_time";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }


    public function randomNumber(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public function convertStdclassArrayToArray($stdclass_array){

        if(empty($stdclass_array))
            return $stdclass_array;

        $_arr = array();
        foreach ($stdclass_array as $stdclass_item){
            $_arr[] = get_object_vars($stdclass_item);
        }
        return $_arr;
    }




}