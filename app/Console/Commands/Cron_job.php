<?php

namespace App\Console\Commands;
use App\Models\CronJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Cron_job extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Cron:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $data = array();

       echo  Carbon::now();

       $cronjob = CronJob::select('*')->get();
        \Log::info("Cron is working fine!");
    }
}
