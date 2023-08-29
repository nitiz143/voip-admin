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
        Commands\DownloadCsvImportCron::class,
        Commands\AccountCron::class,
        Commands\InvoiceCreateCron::class,
        Commands\VendorCron::class,

    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {



        $schedule->command('csvImport:cron')->everyNMinutes(15)->withoutOverlapping();
        $schedule->command('download:cron')->everyNMinutes(18)->withoutOverlapping();
        $schedule->command('account:cron')->everyNMinutes(20)->withoutOverlapping();
        $schedule->command('vendor:cron')->everyNMinutes(20)->withoutOverlapping();
        $schedule->command('invoice_create:cron')->daily();
      //  $schedule->command('Billing:cron')->everyFiveMinutes();
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
