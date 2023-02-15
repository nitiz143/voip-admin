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

        $tasks = CronJob::all();

        $schedule->command('download:cron')->everyFiveMinutes();
        $schedule->command('csvImport:cron')->everyTenMinutes();
        $schedule->command('account:cron')->everyFifteenMinutes();
        
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
