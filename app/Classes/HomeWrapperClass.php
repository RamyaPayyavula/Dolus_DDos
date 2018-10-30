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
    public function insertIntoUserMigrations($userMigrationsUID,$userIP,$originalServerIP,$migratedServerIP){
        $db = 'test';
        $table = "usermigration";
        $migrationStartTime=date("Y-m-d h:i:s");
        $migrationStopTime = date('Y-m-d H:i:s', strtotime("+30 minutes"));

        DB::table($table)->insert(
            ['userMigrationUID' => $userMigrationsUID, 'userIP' => $userIP,'originalServerIP' => $originalServerIP,'migratedServerIP'=> $migratedServerIP, 'migrationStartTime' => $migrationStartTime,'migrationStopTime' => $migrationStopTime]
        );

    }
    public function getAllUserMigrations(){
        #$db = 'test';
        $table = "usermigration";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function insertIntoBlackLists($ipAddress,$macAddress){
        $db = 'test';
        $table = "blacklist";
        $blacklistedOn=date("Y-m-d h:i:s");
        DB::table($table)->insert(
            ['ipAddress' => $ipAddress, 'macAddress' => $macAddress,'blacklistedOn' => $blacklistedOn]
        );

    }
    public function getAllBlacklistIPs(){
        #$db = 'test';
        $table = "blacklist";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getABlacklistIPs($ipAddress){
        #$db = 'test';
        $table = "blacklist";

        $data = DB::connection('mysql')->table($table)->where('ipAddress', $ipAddress)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function deleteABlacklistedIP($ipAddress){
        $table = "blacklist";
        DB::table($table)->where('ipAddress', '=',$ipAddress)->delete();

    }
    public function getAllWhiteListIPs()
    {
        #$db = 'test';
        $table = "whitelist";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;
    }
    public function getAWhiteListIPs($ipAddress){
        #$db = 'test';
        $table = "whitelist";

        $data = DB::connection('mysql')->table($table)->where('ipv4', $ipAddress)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function insertIntoWhiteListIPs($ipv4)
    {
        $db = 'test';
        $table = "whitelist";
        DB::table($table)->insert(
            ['ipv4' => $ipv4]
        );

    }


    public function insertIntoAttacks($attacker_id,$sourceIP,$destinationIP){
        $db = 'test';
        $table = "attackhistory";
        $attackStopTime = date('Y-m-d H:i:s', strtotime("+30 minutes"));
        $attackStartTime=date("Y-m-d h:i:s");
        DB::table($table)->insert(
            ['attacker_id' => $attacker_id, 'source_IP' => $sourceIP,'destination_IP' => $destinationIP, 'attackStartTime' => $attackStartTime,'attackStopTime' => $attackStopTime]
        );

    }
    public function getAllAttacks(){
        #$db = 'test';
        $table = "attackhistory";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getNoofAttackers(){
        $table = "attackhistory";

        $data = DB::connection('mysql')->table($table)->select('source_IP')->distinct()->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;
    }
    public function updateQVM($qvmIP,$no_of_attacks){
        $table = 'qvm';
        DB::table($table)
            ->where('qvmIP', $qvmIP)
            ->update(['numberOfAttackers' => $no_of_attacks]);
    }
    public function getAllPacketLogs(){
        #$db = 'test';
        $table = "packet_logs";

        $data = DB::connection('mysql')->table($table)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAllPacketLogsByDevice($eth_src){
        #$db = 'test';
        $table = "packet_logs";

        $data = DB::connection('mysql')->table($table)->where('eth_src', $eth_src)->get();
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
    public function getASwitch($switch_id){
        #$db = 'test';
        $table = "switches";

        $data = DB::connection('mysql')->table($table)->where('switchID', $switch_id)->get();
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
    public function getADevices($device_id){
        #$db = 'test';
        $table = "devices";

        $data = DB::connection('mysql')->table($table)->where('deviceID', $device_id)->get();
        $_arr = $this->convertStdclassArrayToArray($data);

        return $_arr;

    }
    public function getAServerByIPAddress($ipv4){
        #$db = 'test';
        $table = "servers";

        $data = DB::connection('mysql')->table($table)->where('serverIP', $ipv4)->get();
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
    public function getASwitchByDevices($device_id){
        #$db = 'test';
        $table = "switch_devices";

        $data = DB::connection('mysql')->table($table)->where('deviceID', $device_id)->get();
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
        $table = "policies";

        DB::table($table)->insert(
            ['policyID' => $policyID, 'deviceID' => $deviceID,'policy' => $policy, 'loaded' => $loaded,'ipv6' => $ipv6,'mac'=>$mac]
        );

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
