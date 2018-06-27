<?php
/**
 * Created by PhpStorm.
 * User: ramya
 * Date: 6/24/2018
 * Time: 5:48 PM
 */

namespace App\Http\Controllers\DataCenter;
use App\Classes\HomeWrapperClass;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;

class ServersUsedPerMonthController extends Controller
{
    public function getBarChart()
    {

        $lava = new \Khill\Lavacharts\Lavacharts;
        $servers = $lava->DataTable();
        $home_wrapper= new HomeWrapperClass();
        $data=$home_wrapper->getAllServers();
        $servers->addStringColumn('serverIP')
            ->addDateColumn('serverCereatedOn');
        $chart = $lava->BarChart('servers', $servers);
        return view('servers', [
            'lava'      => $lava
        ]);
    }

}