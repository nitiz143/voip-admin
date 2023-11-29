<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;
use App\Models\CallHistory;
use Log;

class UpdateAccountCallHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user,$olddata;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$olddata)
    {
        $this->user = $user;
        $this->olddata = $olddata;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $callhistory =array();
                if(!empty($this->user->customer_authentication_rule)){       
  
                    //for other
                    if(!empty($olddata)){
                        $data['account_id'] =  $this->user->id;
                        $callhistory = CallHistory::where('account_id',$this->olddata->id)->update($data);
                    }
                    
                  
                }

                if(!empty($this->user->vendor_authentication_rule)){    
                    if(!empty($olddata)){ 
                        $data['vendor_account_id'] =  $this->user->id;
                        $callhistory = CallHistory::where('vendor_account_id',$this->olddata->id)->update($data);  
                    }
               
                          
                }
            

    }
}
