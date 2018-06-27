<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Classes\HomeWrapperClass;
use function PHPSTORM_META\type;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //$schedule->exec(dd("Happy New Year!"))->everyMinute();
//        $home_wrapper= new HomeWrapperClass();
//
//        $devices=$home_wrapper->getAllSuspiciousnessScores();
//        $trace_ids=array();
//        if(count($devices) > 0) {
//            for ($i = 0; $i < count($devices); $i++) {
//                $trace_ids[] = $devices[0]["traceID"];
//            }
//        }
        $schedule->exec('python main.py')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
