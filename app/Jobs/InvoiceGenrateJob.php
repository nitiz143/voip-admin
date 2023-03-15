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
        $AccountID = $this->data['AccountID'];
        $StartDate = $this->data['StartDate'];
        $EndDate = $this->data['EndDate'];
        $zerovaluecost = $this->data['zerovaluecost'];
        $user = ''; 
        $query = CallHistory::query('*');
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
        if((!empty( $StartDate ) && !empty( $EndDate ))){
            $start =  strtotime($StartDate);
            $start = $start*1000;
            $end = strtotime($EndDate);
            $end = $end*1000;
            $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
        }

        $invoices = $query->get();
       
        foreach($invoices as $invoice){
            $cost = collect($invoice->fee)->sum();
        }

   
        $pdf = PDF::loadView('invoicepdf', compact('invoices','cost'))->setPaper('a4');
        if(!empty($pdf)){      
            $exporthistory_arr = ExportHistory::find($this->exporthistory_id);
            $destinationPath = public_path('invoice');
            if (!file_exists($destinationPath)) {
                File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
            }
            $pdf_data = File::put('public/invoice/'.$exporthistory_arr->file_name, $pdf->output());
            $exporthistory_arr['file'] =  $pdf_data;
            $exporthistory_arr['status'] = 'complete';
            $exporthistory_arr->save();
        } 
    }
}