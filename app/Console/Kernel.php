<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\CronJob;
use Log;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        //Commands\Cron_job::class,
        Commands\CsvImportCron::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       // $schedule->command('Cron:job')->everyMinute();

        $tasks = CronJob::all();

        // foreach ($tasks as $task) {
        //     $frequency = $task->job_time;
        //     $minute = 0;
        //     $hour = 0;
        //     $day = 0;
        //     $month = 0;
        //     if($frequency == 'everyMinute'){
        //         $minute = $task->job_intervel;
        //     }elseif ($frequency == 'hourly') {
        //         $hour = $task->job_intervel;
        //     }elseif ($frequency == 'daily') {
        //         $day = $task->job_intervel;
        //     }elseif ($frequency == 'monthly') {
        //         $month = $task->job_intervel;
        //     }
          //  $weeks = json_decode($task->job_day);
            $schedule->command('download:cron')->cron('0 */7 * * *');
            $schedule->command('csvImport:cron')->cron('0 */8 * * *');
        //     if($task->cron_type == 'Download VOS SFTP File'){
        //         // $schedule->command('csvImport:cron')->$frequency();
        //         // $schedule->command('download:cron')->$frequency();
        //         if(!empty($weeks)){
        //             $week_day = (Carbon::now()->format('D'));
        //             if(in_array($week_day,$weeks)){

        //             }

        //         }
        //     }elseif($task->cron_type == 'Active job Cron Email'){
        //         if(!empty($weeks)){
        //             $week_day = (Carbon::now()->format('D'));
        //             if(in_array($week_day,$weeks)){

        //             }

        //         }
        //     }

        //     //else{
        //     //     $schedule->call(function() use($task) {
        //     //         /*  Run your task here */
        //     //         Log::info($task->job_title.' '.\Carbon\Carbon::now());
        //     //     })->monitorName($task->job_title)->$frequency();
        //     // }
       // }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
