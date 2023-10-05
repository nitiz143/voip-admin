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
        // $oldRecords = CallHistory::where('id',$this->olddata->id)->get();
        
        // $id = $this->data['id'];
        // $account = Client::where('id',$id)->first();
        $callhistory =array();
                if(!empty($this->user->customer_authentication_rule)){       
  
                    //for other
                    if(!empty($olddata)){
                        $data['account_id'] =  $this->user->id;
                        $callhistory = CallHistory::where('account_id',$this->olddata->id)->update($data);
                    }
                    else {
                        $data['account_id'] =  $this->user->id;
                        $callhistory = CallHistory::where('customeraccount',$this->user->customer_authentication_value)->update($data);

                    }
                    // //for customername
                    // if($this->user->customer_authentication_rule == 2){
                    //     $data['account_id'] =  $this->user->id;
                    //     $callhistory[] = CallHistory::where('customername',$this->user->customer_authentication_value)->update($data);
                    // }
                    // //for customeraccount
                    // if($this->user->customer_authentication_rule == 3){
                    //     $data['account_id'] =  $this->user->id;
                    //     $callhistory[] = CallHistory::where('customeraccount',$this->user->customer_authentication_value)->update($data);
                    // }
                }

                if(!empty($this->user->vendor_authentication_rule)){    
                    if(!empty($olddata)){ 
                    $data['vendor_account_id'] =  $this->user->id;
                    $callhistory = CallHistory::where('vendor_account_id',$this->olddata->id)->update($data);  
                }
                else {
                    $data['vendor_account_id'] =  $this->user->id;
                    $callhistory = CallHistory::where('agentname',$this->user->vendor_authentication_value)->update($data);
 
                }
                                    //     //for other
                //     if($this->user->vendor_authentication_rule == 6){
                //         $data['vendor_account_id'] = $this->user->id;
                //         $callhistory[] = CallHistory::where('agentaccount',$this->user->vendor_authentication_value)->orwhere('agentname',$account->vendor_authentication_value)->update($data);
                //     }
                //     //for customername
                //     if($this->$user->vendor_authentication_rule == 2){
                //         $data['vendor_account_id'] =  $this->$user->id;
                //         $callhistory[] = CallHistory::where('agentname',$this->user->vendor_authentication_value)->update($data);
                //     }
                //     //for customeraccount
                //     if($this->$user->vendor_authentication_rule == 3){
                //         $data['vendor_account_id'] =  $this->$user->id;
                //         $callhistory[] = CallHistory::where('agentaccount',$this->$user->vendor_authentication_value)->update($data);
                //     }
                }
            
            
            

    }
}
