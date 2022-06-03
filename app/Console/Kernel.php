<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\CronJob;
use Log;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\Cron_job::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('Cron:job')
        ->everyMinute();

        $tasks = CronJob::all();

        foreach ($tasks as $task) {

            $frequency = $task->job_time;

            $schedule->call(function() use($task) {
                /*  Run your task here */
                Log::info($task->job_title.' '.\Carbon\Carbon::now());
            })->monitorName($task->job_title)->$frequency();
        }
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
