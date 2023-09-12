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
        $StartDate = $this->data['StartDate'];
        $EndDate = $this->data['EndDate'] ;
        $Report =  $this->data['report'];

        if($type == "Vendor"){   
            $query = CallHistory::query('*');
            if((!empty( $StartDate ) && !empty( $EndDate ))){
                $start =  strtotime($StartDate);
                $start = $start*1000;
                $end = strtotime($EndDate);
                $end = $end*1000;
                $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
            }
            if($Report != 'Account-Manage') {
                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID);
                }
            }
            if($Report == 'Vendor-Negative-Report') {
                $query->where('call_histories.agentfee', '<=', 0);
            }
           
            $invoices = $query->get();
            $count_duration=[];
            $count_vendor_duration=[];
            $total_vendor_cost ="";
            $total_cost = "";
            $calls = $invoices->count();
            if(!empty($invoices)){
                $total_cost = $invoices->sum('fee');
                $total_vendor_cost = $invoices->sum('agentfee');
                foreach ($invoices as $key => $invoice) {
                    $count_duration[] =   $invoice->feetime;
                    $count_vendor_duration[] =  $invoice->agentfeetime;
                }
            }
            $invoices = $invoices->groupBy('callerareacode');
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Vendor";
            $data = ExportHistory::find($this->exporthistory_id);
            $pdf = PDF::loadView('invoicepdf', compact('invoices','Report','total_cost','user','account','data','count_duration','calls','StartDate','EndDate','total_vendor_cost','count_vendor_duration'))->setPaper('a4','landscape');
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
            if($Report != 'Account-Manage') {
                if(!empty( $AccountID )) {
                    $query->where('call_histories.account_id', $AccountID);
                }
            }
            if($Report == 'Customer-Negative-Report'){
                $query->where('call_histories.fee','<=', 0);
            }
            $invoices = $query->get();
            $count_duration=[];
            $count_vendor_duration=[];
            $total_cost = "";
            $total_vendor_cost ="";
            $calls = $invoices->count();
            if(!empty( $invoices)){
                $total_cost = $invoices->sum('fee');
                $total_vendor_cost = $invoices->sum('agentfee');
                foreach ($invoices as $key => $invoice) {
                    $count_duration[] =   $invoice->feetime;
                    $count_vendor_duration[] =  $invoice->agentfeetime;
                }
            }
            $invoices = $invoices->groupBy('callerareacode');
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Customer";
            $data = ExportHistory::find($this->exporthistory_id);
            $pdf = PDF::loadView('invoicepdf', compact('invoices','Report','total_cost','user','account','data','count_duration','StartDate','EndDate','calls','total_vendor_cost','count_vendor_duration'))->setPaper('a4','landscape');
        }

        if(!empty($pdf)){      
            $exporthistory_arr = ExportHistory::find($this->exporthistory_id);
            Storage::put('voip/pdf/'.$exporthistory_arr->file_name,$pdf->output(),'public');
            $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
            $exporthistory_arr['status'] = 'complete';
            $exporthistory_arr['started_at'] = $StartDate;
            $exporthistory_arr['ended_at'] = $EndDate;
            $exporthistory_arr->save();
        } 
    }
}
