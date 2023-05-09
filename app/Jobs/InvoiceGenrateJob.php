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
        $zerovaluecost = $this->data['zerovaluecost'];
    
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
            if(!empty( $zerovaluecost )) {
                if($zerovaluecost == 1){
                    $query->where('agentfee', 0);
                }
                if($zerovaluecost == 2){
                    $query->where('agentfee','!=', 0);
                }
                if($zerovaluecost == 0){
                    $query;
                }
            }
           
            $invoices = $query->get();
            $count_duration=[];
            $total_cost = "";
            if(!empty($invoices)){
                $total_cost = $invoices->sum('agentfee');
                    foreach ($invoices as $key => $invoice) {
                        // $date = new \DateTime();
                        // $value = $invoice->starttime;
                        // $startTime =  $date->setTimestamp($value/1000);

                        // $date1 = new \DateTime();
                        // $value1 = $invoice->stoptime;
                        // $stopTime =  $date1->setTimestamp($value1/1000);
                        $count_duration[] =   $invoice->feetime;
                    }
            }
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Vendor";
            $data = ExportHistory::find($this->exporthistory_id);
            $pdf = PDF::loadView('invoicepdf', compact('invoices','total_cost','user','account','data','count_duration'))->setPaper('a4');
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
            if(!empty( $zerovaluecost )) {
                if($zerovaluecost == 1){
                    $query->where('fee', 0);
                }
                if($zerovaluecost == 2){
                    $query->where('fee','!=', 0);
                }
                if($zerovaluecost == 0){
                    $query;
                }
            }
           
            $invoices = $query->get();
            $count_duration=[];
            $total_cost = "";
            if(!empty( $invoices)){
                $total_cost = $invoices->sum('fee');
                    foreach ($invoices as $key => $invoice) {
                        $count_duration[] =   $invoice->feetime;
                    }
            }

          
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Customer";
            $data = ExportHistory::find($this->exporthistory_id);


            
        //    echo view('invoicepdf', compact('invoices','total_cost','user','account','data','count_duration','StartDate','EndDate'));
        //    die;
            $pdf = PDF::loadView('invoicepdf', compact('invoices','total_cost','user','account','data','count_duration','StartDate','EndDate'))->setPaper('a4');
        }
   

   
       
        if(!empty($pdf)){      
            $exporthistory_arr = ExportHistory::find($this->exporthistory_id);
            Storage::disk('digitalocean')->put('voip/pdf/'.$exporthistory_arr->file_name,$pdf->output(),'public');
            $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
            $exporthistory_arr['status'] = 'complete';
            $exporthistory_arr->save();
        } 
    }
}
