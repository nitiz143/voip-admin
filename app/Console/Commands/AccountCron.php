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
        $accounts = Client::get();
        $callhistory =array();
        if(!empty($accounts)){
            foreach ($accounts as $key => $account) {
                if(!empty($account->customer_authentication_rule)){       
  
                    //for other
                    if($account->customer_authentication_rule == 6){
                        $data['account_id'] =  $account->id;
                        $callhistory[] = CallHistory::whereNull('account_id')->where('customeraccount',$account->customer_authentication_value)->orwhere('customername',$account->customer_authentication_value)->update($data);
                    }
                    //for customername
                    if($account->customer_authentication_rule == 2){
                        $data['account_id'] =  $account->id;
                        $callhistory[] = CallHistory::whereNull('account_id')->where('customername',$account->customer_authentication_value)->update($data);
                    }
                    //for customeraccount
                    if($account->customer_authentication_rule == 3){
                        $data['account_id'] =  $account->id;
                        $callhistory[] = CallHistory::whereNull('account_id')->where('customeraccount',$account->customer_authentication_value)->update($data);
                    }
                }

                if(!empty($account->vendor_authentication_rule)){       
  
                    //for other
                    if($account->vendor_authentication_rule == 6){
                        $data['vendor_account_id'] =  $account->id;
                        $callhistory[] = CallHistory::whereNull('vendor_account_id')->where('agentaccount',$account->vendor_authentication_value)->orwhere('agentname',$account->vendor_authentication_value)->update($data);
                    }
                    //for customername
                    if($account->vendor_authentication_rule == 2){
                        $data['vendor_account_id'] =  $account->id;
                        $callhistory[] = CallHistory::whereNull('vendor_account_id')->where('agentname',$account->vendor_authentication_value)->update($data);
                    }
                    //for customeraccount
                    if($account->vendor_authentication_rule == 3){
                        $data['vendor_account_id'] =  $account->id;
                        $callhistory[] = CallHistory::whereNull('vendor_account_id')->where('agentaccount',$account->vendor_authentication_value)->update($data);
                    }
                }
            
            }
            
        }
    }
}
