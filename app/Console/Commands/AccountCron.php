<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\CallHistory;

class AccountCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $accounts = Client::query('')->get();
        $callhistory =array();
        foreach ($accounts as $key => $account) {
            //for other
            if($account->customer_authentication_rule == 6){
                $callhistory[] = CallHistory::where('customeraccount',$account->customer_authentication_value)->orwhere('customername',$account->customer_authentication_value)->get();
            }
            //for customername
            if($account->customer_authentication_rule == 2){
                $callhistory[] = CallHistory::where('customername',$account->customer_authentication_value)->get();
            }
            //for customeraccount
            if($account->customer_authentication_rule == 3){
                $callhistory[] = CallHistory::where('customeraccount',$account->customer_authentication_value)->get();
            }
          
        }
        dd( $callhistory);
    }
}
