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

class AuthenticationTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user,$data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->data = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oldRecord = CallHistory::find($this->user);
        
        if ($oldRecord) {
           
            $newRecordData = $this->data['id'];
            $account = Client::where('id',$newRecordData)->first();
    
            $oldRecord->update($newRecordData);
    
            \Log::info('Record updated successfully: ' . $this->user);
        } else {
            \Log::error('Record not found: ' . $this->user);
        }


        // $id = $this->data['id'];
        // $account = Client::where('id',$id)->first();
        $callhistory =array();
        if(!empty($account)){
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
// }
