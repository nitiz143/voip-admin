<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Log;
use File;
use App\Models\ExportHistory;
use App\Models\CallHistory;
use App\Models\Client;
use App\Models\Country;
use Illuminate\Support\Facades\Storage;

class InvoiceGenrateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data,$authUser,$exporthistory_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request,$authUser,$exporthistory_id)
    {
        $this->data = $request->all();
        $this->authUser = $authUser;
        $this->exporthistory_id = $exporthistory_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $type = $this->data['type'];
        $AccountID = $this->data['AccountID'];
        $StartDate = $this->data['StartDate'] .' '. $this->data['StartTime'];
        $EndDate = $this->data['EndDate'] .' '. $this->data['EndTime'];
    
        if($type == "Vendor"){
            $query = CallHistory::query('*');
            if((!empty( $StartDate ) && !empty( $EndDate ))){
                $start =  strtotime($StartDate);
                $start = $start*1000;
                $end = strtotime($EndDate);
                $end = $end*1000;
                $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
            }

            if(!empty( $AccountID )) {
                $query->where('call_histories.account_id', $AccountID);
            }
           
            $invoices = $query->get();
            $count_duration=[];
            $calls = $invoices->count();
            $total_cost = "";
            if(!empty($invoices)){
                $total_cost = $invoices->sum('agentfee');
                    foreach ($invoices as $key => $invoice) {
                        $count_duration[] =   $invoice->feetime;
                    }
            }
            $invoices = $invoices->groupBy('callerareacode');
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Vendor";
            $data = ExportHistory::find($this->exporthistory_id);
            $pdf = PDF::loadView('invoicepdf', compact('invoices','total_cost','user','account','data','count_duration','calls'))->setPaper('a4');
        }  
        if($type == "Customer"){
            $query = CallHistory::query('*');
            if((!empty( $StartDate ) && !empty( $EndDate ))){
                $start =  strtotime($StartDate);
                $start = $start*1000;
                $end = strtotime($EndDate);
                $end = $end*1000;
                $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
            }

            if(!empty( $AccountID )) {
                $query->where('call_histories.account_id', $AccountID);
            }
          
            $invoices = $query->get();
            $count_duration=[];
            $total_cost = "";
            $calls = $invoices->count();
            if(!empty( $invoices)){
                $total_cost = $invoices->sum('fee');
                foreach ($invoices as $key => $invoice) {
                    $count_duration[] =   $invoice->feetime;
                }
            }

            $invoices = $invoices->groupBy('callerareacode');
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Customer";
            $data = ExportHistory::find($this->exporthistory_id);

            $pdf = PDF::loadView('invoicepdf', compact('invoices','total_cost','user','account','data','count_duration','StartDate','EndDate','calls',))->setPaper('a4');
        }

        if(!empty($pdf)){      
            $exporthistory_arr = ExportHistory::find($this->exporthistory_id);
            Storage::put('voip/pdf/'.$exporthistory_arr->file_name,$pdf->output(),'public');
            $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
            $exporthistory_arr['status'] = 'complete';
            $exporthistory_arr->save();
        } 
    }
}
