<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\CallHistory;
use App\Models\ExportCsvXlsxHistory;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;
use OpenSpout\Writer\XLSX\Options;
use File;

class DownloadCsvXlsx implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data,$authUser,$exporthistory_id,$exporthistory_type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request,$authUser,$exporthistory_id,$exporthistory_type)
    {
        $this->data = $request->all();
        $this->authUser = $authUser;
        $this->exporthistory_id = $exporthistory_id;
        $this->exporthistory_type = $exporthistory_type;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $AccountID = $this->data['AccountID'];
        $StartDate = $this->data['StartDate'] ;
        $EndDate = $this->data['EndDate'] ;
        $Report =  $this->data['report'];

        $start =  strtotime($StartDate);
        $start = $start*1000;
        $end = strtotime($EndDate);
        $end = $end*1000;  

        if($this->exporthistory_type == "Xlsx_file"){
            $query = CallHistory::query('*');
            if( $Report == 'Customer/Vendor-Report'){
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
                $downloads = $invoices->groupBy('customeraccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count = array();
                        $fee_count = array();
                        $agentfee_count =array();
                        $customer_fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                        foreach ($value as $invoice){
                            //customer
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->agentfee > 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->fee > 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }
                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                        $data['Rev/Min'] = !empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00";
                        $data['Cost'] =  '$'.sprintf('%0.2f', $value->sum('agentfee'));
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['VendAccountCode'] = "";
                    $data['Vendor'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] = "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Customer-Hourly') {
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
                $downloads = $invoices->groupBy('customeraccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $fee_count = array();
                        $agentfee_count =array();
                        $fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }


                       
                       
                       
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;

                            if($invoice->agentfee > 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->fee > 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }
                        }
                        
                        $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                        $margin_per_min = (int)$fee - (int)$agent_fee;
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                        $data['Rev/Min'] = !empty($fee) ? '$'.sprintf('%0.2f', $fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Vendor-Hourly') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID);
                }
                $invoices = $query->get();
                $downloads = $invoices->groupBy('agentaccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $fee_count = array();
                        $agentfee_count =array();

                        $customer_fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->agentfee > 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->fee > 0) {
                                $fee_count[] = $invoice->fee;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        
                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;

                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                            $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                        }
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Cost'] =  '$'.sprintf('%0.2f', $value->sum('agentfee')) ;
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['VendAccountCode'] =  "";
                    $data['Vendor'] =  "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Account-Manage') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('customeraccount');
                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $fee_count = array();
                        $agentfee_count =array();
                        
                        $customer_fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                    
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            $fee_count[] = $invoice->fee;
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            $agentfee_count[] = $invoice->agentfee;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                        $data['Rev/Min'] = !empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00";
                        $data['Cost'] =  '$'.sprintf('%0.2f', $value->sum('agentfee'));
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                        $data['Mar/Min'] ="";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['VendAccountCode'] = "";
                    $data['Vendor'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] = "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Customer-Margin-Report') {
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
                $downloads = $invoices->groupBy('customeraccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $fee_count = array();
                        $agentfee_count =array();
                        $fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }

                       
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;

                            if($invoice->agentfee > 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->fee > 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }
                        }
                        
                        $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                        $margin_per_min = (int)$fee - (int)$agent_fee;
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                        $data['Rev/Min'] = !empty($fee) ? '$'.sprintf('%0.2f', $fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Vendor-Margin-Report') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID);
                }
                $invoices = $query->get();
                $downloads = $invoices->groupBy('agentaccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $fee_count = array();
                        $agentfee_count =array();
                        $customer_fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->agentfee > 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->fee > 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        
                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;

                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                            $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Cost'] =  '$'.sprintf('%0.2f', $value->sum('agentfee')) ;
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['VendAccountCode'] =  "";
                    $data['Vendor'] =  "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Customer-Negative-Report') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.account_id', $AccountID)->where('fee', '<=', 0);
                }
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('customeraccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                    
                        $fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $fee= $persec*60;
                        }


                       
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }
                        }
                        
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                        $data['Rev/Min'] = !empty($fee) ? '$'.sprintf('%0.2f', $fee) : "$ 0.00";
                        // $data['Margin'] ="";
                        // $data['Mar/Min'] ="";
                        // $data['Mar%'] ="";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    // $data['Margin'] = "";
                    // $data['Mar/Min'] = "";
                    // $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Vendor-Negative-Report') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID)->where('agentfee', '<=', 0);
                }
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('agentaccount');
                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                       


                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                    
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                            $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Cost'] =  '$'.sprintf('%0.2f', $value->sum('agentfee')) ;
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";
                        // $data['Margin'] ="";
                        // $data['Mar/Min'] ="";
                        // $data['Mar%'] ="";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['VendAccountCode'] =  "";
                    $data['Vendor'] =  "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    // $data['Margin'] = "";
                    // $data['Mar/Min'] = "";
                    // $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            else{
                if($Report == 'Vendor-Summary'){
                    if((!empty( $StartDate ) && !empty( $EndDate ))){
                        $start =  strtotime($StartDate);
                        $start = $start*1000;
                        $end = strtotime($EndDate);
                        $end = $end*1000;
                        $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                    }
    
                    if(!empty( $AccountID )) {
                        $query->where('call_histories.vendor_account_id', $AccountID);
                    }
                
                    $invoices = $query->get();
                    $downloads = $invoices->groupBy('agentaccount');
    
                    $list =array();
                    if(!$downloads->isEmpty()){
                        foreach ($downloads as $key => $value) {
                            $Agent_Duration_count = array();
                            $completed_agent_count =array();
    
                            $fee_count = array();
                            $agentfee_count =array();
    
                            $customer_fee ="";
                            $totalSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->feetime);
                            })->sum('feetime');
    
                            if($value->sum('fee') > 0 && $totalSum > 0){
                                $timepersec = $value->sum('fee')/$totalSum;
                                $persec =  round($timepersec, 7);
                                $customer_fee= $persec*60;
                            }
    
                            $agent_fee ="";
                            $totalagentSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->agentfeetime);
                            })->sum('agentfeetime');
                            if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                                $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                                $persec2 =  round($timepersec2, 7);
                                $agent_fee= $persec2*60;
                            }
                        
                            foreach ($value as $invoice){
                                $Duration_count[] = $invoice->feetime;
                                if($invoice->agentfee > 0) {
                                    $agentfee_count[] = $invoice->agentfee;
                                }
                                if($invoice->fee > 0) {
                                    $fee_count[] = $invoice->fee;
                                }
                                if($invoice->feetime != 0 && $invoice->feetime != null) {
                                    $completed_count[] = $invoice->feetime;
                                }
    
                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                    $completed_agent_count[] = $invoice->agentfeetime;
                                }
                            }
                            
                            $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                            
                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                            $sec = "";
                            if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                                $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                            }
                            $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                            $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                            $data['Attempts'] =  $value->count() ;
                            $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                            $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                            $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                            $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                            $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                            $data['Cost'] =  '$'.sprintf('%0.2f', $value->sum('agentfee'));
                            $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";
                            $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                            $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                            $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                            $data['CustProductGroup'] = "";
                            $data['VendProductGroup'] = "";
                            $list[]= $data;
                        }
                    }
                    else{
                        $data =array();
                        $data['VendAccountCode'] =  "";
                        $data['Vendor'] =  "";
                        $data['Attempts'] =  "" ;
                        $data['Completed'] =   "";
                        $data['ASR'] =  "";
                        $data['ACD'] =  "";
                        $data['Raw Dur'] = "";
                        $data['Rnd Dur'] =  "";
                        $data['Cost'] = "";
                        $data['Cost/Min'] =  "";
                        $data['Margin'] = "";
                        $data['Mar/Min'] = "";
                        $data['Mar%'] = "";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                    $destinationPath = public_path('storage/excel_files/');
                    if (!file_exists($destinationPath)) {
                        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                    }
                    $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                    $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                    $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                    $exporthistory_arr['status'] = 'complete';
                    $exporthistory_arr->save();
                }
                if($Report == 'Customer-Summary'){
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
                    $downloads = $invoices->groupBy('customeraccount');

                    $list =array();
                    if(!$downloads->isEmpty()){
                        foreach ($downloads as $key => $value) {
                            $Duration_count = array();
                            $completed_count = array();
                            $Agent_Duration_count = array();
                            $completed_agent_count =array();
                            $fee_count = array();
                            $agentfee_count =array();
                            $fee ="";

                            $totalSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->feetime);
                            })->sum('feetime');

                            if($value->sum('fee') > 0 && $totalSum > 0){
                                $timepersec = $value->sum('fee')/$totalSum;
                                $persec =  round($timepersec, 7);
                                $fee= $persec*60;
                            }

                            $agent_fee ="";

                            $totalagentSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->agentfeetime);
                            })->sum('agentfeetime');
                            if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                                $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                                $persec2 =  round($timepersec2, 7);
                                $agent_fee= $persec2*60;
                            }

                    
                        
                            foreach ($value as $invoice){
                                $Duration_count[] = $invoice->feetime;
                                if($invoice->agentfee > 0) {
                                    $agentfee_count[] = $invoice->agentfee;
                                }
                                if($invoice->fee > 0) {
                                    $fee_count[] = $invoice->fee;
                                }
                                if($invoice->feetime != 0 && $invoice->feetime != null) {
                                    $completed_count[] = $invoice->feetime;
                                }
    
                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                    $completed_agent_count[] = $invoice->agentfeetime;
                                }
                            }

                            $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                            $margin_per_min = (int)$fee - (int)$agent_fee;

                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                            $sec = "";
                            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                $sec =  array_sum($completed_count) /  count($completed_count);
                            }
                            $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                            $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                            $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                            $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                            $data['Attempts'] =  $value->count() ;
                            $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                            $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                            $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                            $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                            $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                            $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                            $data['Rev/Min'] = !empty($fee) ? '$'.sprintf('%0.2f', $fee) : "$ 0.00";
                            $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                            $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                            $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                            $data['CustProductGroup'] = "";
                            $data['VendProductGroup'] = "";
                            $list[]= $data;
                        }
                    }
                    else{
                        $data = array();
                        $data['CustAccountCode'] = "";
                        $data['Customer'] = "";
                        $data['CustDestination'] = "";
                        $data['Attempts'] =  "" ;
                        $data['Completed'] =   "";
                        $data['ASR'] =  "";
                        $data['ACD'] =  "";
                        $data['Raw Dur'] = "";
                        $data['Rnd Dur'] =  "";
                        $data['Revenue'] = "";
                        $data['Rev/Min'] ="";
                        $data['Margin'] = "";
                        $data['Mar/Min'] = "";
                        $data['Mar%'] = "";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                    $destinationPath = public_path('storage/excel_files/');
                    if (!file_exists($destinationPath)) {
                        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                    }
                    $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                    $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                    $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                    $exporthistory_arr['status'] = 'complete';
                    $exporthistory_arr->save();
                }
            }
        }
        if($this->exporthistory_type == "Csv_file"){
            $query = CallHistory::query('*');
            if( $Report == 'Customer/Vendor-Report'){
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
                $downloads = $invoices->groupBy('customeraccount');
                $list =array();
             
                $customer_fee ="";
                $agent_fee ="";
                if(!$downloads->isEmpty()){
                    foreach ( $downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $fee_count = array();
                        $agentfee_count =array();
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            $fee_count[] = $invoice->fee;
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            $agentfee_count[] = $invoice->agentfee;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        
                        }

                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }


                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;

                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] =   \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] =   !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                        $data['Rev/Min'] = !empty($customer_fee) ? '$'.sprintf('%0.2f',$customer_fee) : "$ 0.00";
                        $data['Cost'] =  '$'.sprintf('%0.2f',$value->sum('agentfee'));
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f',$agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f',$margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data; 
                    }
                }else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['VendAccountCode'] =  "";
                    $data['Vendor'] =  "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Customer-Hourly') {
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
                $downloads = $invoices->groupBy('customeraccount');
                $list =array();
                $agent_fee="";
                $customer_fee="";
                if(!$downloads->isEmpty()){
                    foreach ( $downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $fee_count = array();
                        $agentfee_count =array();
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->fee  >= 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->agentfee >= 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }
                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }

                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }


                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }

                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee-(int)$agent_fee;
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] =   \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] =   !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f',$value->sum('fee'));
                        $data['Rev/Min'] = !empty($customer_fee) ? '$'.sprintf('%0.2f',$customer_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f',$margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data; 
                    }
                }else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Vendor-Hourly') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID);
                }
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('agentaccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $customer_fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                    
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;

                            if($invoice->fee  >= 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->agentfee >= 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                            $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                        }

                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Cost'] =  '$'.sprintf('%0.2f',$value->sum('agentfee'));
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f',$agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f',$margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['VendAccountCode'] =  "";
                    $data['Vendor'] =  "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Account-Manage') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

               
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('customeraccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $fee_count = array();
                        $agentfee_count =array();
                        $customer_fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }

                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            $fee_count[] = $invoice->fee;
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            $agentfee_count[] = $invoice->agentfee;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f',$value->sum('fee'));
                        $data['Rev/Min'] = !empty($customer_fee) ? '$'.sprintf('%0.2f',$customer_fee) : "$ 0.00";
                        $data['Cost'] =  '$'.sprintf('%0.2f',$value->sum('agentfee'));
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f',$agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f',$margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['VendAccountCode'] = "";
                    $data['Vendor'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] = "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Customer-Margin-Report') {
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
                $downloads = $invoices->groupBy('customeraccount');
                $list =array();
                $agent_fee="";
                $customer_fee="";
                if(!$downloads->isEmpty()){
                    foreach ( $downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $fee_count = array();
                        $agentfee_count =array();
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->fee  >= 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->agentfee >= 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }
                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }

                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                        $margin_per_min = (int)$customer_fee-(int)$agent_fee;
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] =   \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] =   !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f',$value->sum('fee'));
                        $data['Rev/Min'] = !empty($customer_fee) ? '$'.sprintf('%0.2f',$customer_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f',$margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data; 
                    }
                }else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Vendor-Margin-Report') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID);
                }
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('agentaccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                        $customer_fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $customer_fee= $persec*60;
                        }

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;

                            if($invoice->fee  >= 0) {
                                $fee_count[] = $invoice->fee;
                            }
                            if($invoice->agentfee >= 0) {
                                $agentfee_count[] = $invoice->agentfee;
                            }
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                            $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                        }
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Cost'] =  '$'.sprintf('%0.2f',$value->sum('agentfee'));
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f',$agent_fee) : "$ 0.00";
                        $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f',$margin) : "$ 0.00";
                        $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                        $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['VendAccountCode'] =  "";
                    $data['Vendor'] =  "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    $data['Margin'] = "";
                    $data['Mar/Min'] = "";
                    $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Customer-Negative-Report') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.account_id', $AccountID)->where('fee', '<', 0);
                }
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('customeraccount');

                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Duration_count = array();
                        $completed_count = array();
                    
                        $fee ="";
                        $totalSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->feetime);
                        })->sum('feetime');

                        if($value->sum('fee') > 0 && $totalSum > 0){
                            $timepersec = $value->sum('fee')/$totalSum;
                            $persec =  round($timepersec, 7);
                            $fee= $persec*60;
                        }

                       
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }
                        }
                        
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                            $sec =  array_sum($completed_count) /  count($completed_count);
                        }
                        $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                        $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                        $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                        $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_count) ? count($completed_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Revenue'] = '$'.sprintf('%0.2f', $value->sum('fee'));
                        $data['Rev/Min'] = !empty($fee) ? '$'.sprintf('%0.2f', $fee) : "$ 0.00";
                        // $data['Margin'] ="";
                        // $data['Mar/Min'] ="";
                        // $data['Mar%'] ="";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['CustAccountCode'] = "";
                    $data['Customer'] = "";
                    $data['CustDestination'] = "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Revenue'] = "";
                    $data['Rev/Min'] ="";
                    // $data['Margin'] = "";
                    // $data['Mar/Min'] = "";
                    // $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            elseif ($Report == 'Vendor-Negative-Report') {
                if((!empty( $StartDate ) && !empty( $EndDate ))){
                    $start =  strtotime($StartDate);
                    $start = $start*1000;
                    $end = strtotime($EndDate);
                    $end = $end*1000;
                    $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                }

                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID)->where('agentfee', '<', 0);
                }
            
                $invoices = $query->get();
                $downloads = $invoices->groupBy('agentaccount');
                $list =array();
                if(!$downloads->isEmpty()){
                    foreach ($downloads as $key => $value) {
                        $Agent_Duration_count = array();
                        $completed_agent_count =array();
                       

                        $agent_fee ="";
                        $totalagentSum = $value->filter(function ($item) {
                            // Check if the 'feetime' property is numeric
                            return is_numeric($item->agentfeetime);
                        })->sum('agentfeetime');
                        if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                            $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                            $persec2 =  round($timepersec2, 7);
                            $agent_fee= $persec2*60;
                        }
                    
                        foreach ($value as $invoice){
                            $Duration_count[] = $invoice->feetime;
                            if($invoice->feetime != 0 && $invoice->feetime != null) {
                                $completed_count[] = $invoice->feetime;
                            }

                            $Agent_Duration_count[] = $invoice->agentfeetime;
                            if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                $completed_agent_count[] = $invoice->agentfeetime;
                            }
                        }
                        
                        
                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                        $sec = "";
                        if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                            $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                        }
                        $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                        $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                        $data['Attempts'] =  $value->count() ;
                        $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                        $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                        $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                        $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                        $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                        $data['Cost'] =  '$'.sprintf('%0.2f', $value->sum('agentfee')) ;
                        $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";
                        // $data['Margin'] ="";
                        // $data['Mar/Min'] ="";
                        // $data['Mar%'] ="";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                }
                else{
                    $data =array();
                    $data['VendAccountCode'] =  "";
                    $data['Vendor'] =  "";
                    $data['Attempts'] =  "" ;
                    $data['Completed'] =   "";
                    $data['ASR'] =  "";
                    $data['ACD'] =  "";
                    $data['Raw Dur'] = "";
                    $data['Rnd Dur'] =  "";
                    $data['Cost'] = "";
                    $data['Cost/Min'] =  "";
                    // $data['Margin'] = "";
                    // $data['Mar/Min'] = "";
                    // $data['Mar%'] = "";
                    $data['CustProductGroup'] = "";
                    $data['VendProductGroup'] = "";
                    $list[]= $data;
                }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
            }
            else{
                if($Report == 'Vendor-Summary'){
                    if((!empty( $StartDate ) && !empty( $EndDate ))){
                        $start =  strtotime($StartDate);
                        $start = $start*1000;
                        $end = strtotime($EndDate);
                        $end = $end*1000;
                        $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
                    }
    
                    if(!empty( $AccountID )) {
                        $query->where('call_histories.vendor_account_id', $AccountID);
                    }
                
                    $invoices = $query->get();
                    $downloads = $invoices->groupBy('agentaccount');
    
                    $list =array();
                    if(!$downloads->isEmpty()){
                        foreach ($downloads as $key => $value) {
                            $Duration_count = array();
                            $completed_count = array();
                            $Agent_Duration_count = array();
                            $completed_agent_count =array();
                            $customer_fee ="";
                            $totalSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->feetime);
                            })->sum('feetime');
    
                            if($value->sum('fee') > 0 && $totalSum > 0){
                                $timepersec = $value->sum('fee')/$totalSum;
                                $persec =  round($timepersec, 7);
                                $customer_fee= $persec*60;
                            }
    
                            $agent_fee ="";
                            $totalagentSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->agentfeetime);
                            })->sum('agentfeetime');
                            if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                                $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                                $persec2 =  round($timepersec2, 7);
                                $agent_fee= $persec2*60;
                            }
                        
                            foreach ($value as $invoice){
                                $Duration_count[] = $invoice->feetime;
                                if($invoice->agentfee > 0) {
                                    $agentfee_count[] = $invoice->agentfee;
                                }
                                if($invoice->fee > 0) {
                                    $fee_count[] = $invoice->fee;
                                }
                                if($invoice->feetime != 0 && $invoice->feetime != null) {
                                    $completed_count[] = $invoice->feetime;
                                }
    
                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                    $completed_agent_count[] = $invoice->agentfeetime;
                                }
                            }
                            
                            $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                            $margin_per_min = (int)$customer_fee -(int)$agent_fee;

                            
                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                            $sec = "";
                            if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                                $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                            }
                            $data['VendAccountCode'] = !empty($value[0]['agentaccount']) ? $value[0]['agentaccount'] :"";
                            $data['Vendor'] =   !empty($value[0]['agentname']) ? $value[0]['agentname'] :"";
                            $data['Attempts'] =  $value->count() ;
                            $data['Completed'] =   !empty($completed_agent_count) ? count($completed_agent_count) : "";
                            $data['ASR'] = \Str::limit((count($completed_agent_count)/$value->count() * 100),5).'%';
                            $data['ACD'] = !empty($sec) ? \Str::limit($sec,5) :"0";
                            $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                            $data['Rnd Dur'] =   !empty($Duration) ? $Duration :"";
                            $data['Cost'] =  '$'.sprintf('%0.2f',$value->sum('agentfee'));
                            $data['Cost/Min'] = !empty($agent_fee) ? '$'.sprintf('%0.2f',$agent_fee) : "$ 0.00";
                            $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                            $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                            $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                            $data['CustProductGroup'] = "";
                            $data['VendProductGroup'] = "";
                            $list[]= $data;
                        }
                    }
                    else{
                        $data =array();
                        $data['VendAccountCode'] =  "";
                        $data['Vendor'] =  "";
                        $data['Attempts'] =  "" ;
                        $data['Completed'] =   "";
                        $data['ASR'] =  "";
                        $data['ACD'] =  "";
                        $data['Raw Dur'] = "";
                        $data['Rnd Dur'] =  "";
                        $data['Cost'] = "";
                        $data['Cost/Min'] =  "";
                        $data['Margin'] = "";
                        $data['Mar/Min'] = "";
                        $data['Mar%'] = "";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                    $destinationPath = public_path('storage/csv_files/');
                    if (!file_exists($destinationPath)) {
                        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                    }
                    $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                    $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                    $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                    $exporthistory_arr['status'] = 'complete';
                    $exporthistory_arr->save();
                }
                if($Report == 'Customer-Summary'){
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
                    $downloads = $invoices->groupBy('customeraccount');
                    $list =array();
                    $customer_fee ="";
                    $agent_fee = "";
                    if(!$downloads->isEmpty()){
                        foreach ( $downloads as $key => $value) {

                            $totalSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->feetime);
                            })->sum('feetime');
    
                            if($value->sum('fee') > 0 && $totalSum > 0){
                                $timepersec = $value->sum('fee')/$totalSum;
                                $persec =  round($timepersec, 7);
                                $customer_fee= $persec*60;
                            }
    
                            $totalagentSum = $value->filter(function ($item) {
                                // Check if the 'feetime' property is numeric
                                return is_numeric($item->agentfeetime);
                            })->sum('agentfeetime');
                            if($value->sum('agentfee') > 0 && $totalagentSum > 0){
                                $timepersec2 = $value->sum('agentfee')/$totalagentSum;
                                $persec2 =  round($timepersec2, 7);
                                $agent_fee= $persec2*60;
                            }
                            $Duration_count = array();
                            $completed_count = array();
                            $Agent_Duration_count = array();
                            $completed_agent_count =array();
                            $agentfee_count=array();
                            $fee_count=array();
                        
                            foreach ($value as $invoice){
                                $Duration_count[] = $invoice->feetime;
                                if($invoice->agentfee > 0) {
                                    $agentfee_count[] = $invoice->agentfee;
                                }
                                if($invoice->fee > 0) {
                                    $fee_count[] = $invoice->fee;
                                }
                                if($invoice->feetime != 0 && $invoice->feetime != null) {
                                    $completed_count[] = $invoice->feetime;
                                }
    
                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                if($invoice->agentfeetime  != 0 && $invoice->agentfeetime != null) {
                                    $completed_agent_count[] = $invoice->agentfeetime;
                                }
                            }
                            
                            $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;

                           
                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                            $sec = "";
                            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                $sec =  array_sum($completed_count) /  count($completed_count);
                            }
                            $country = Country::where('phonecode',$value[0]['callerareacode'])->first();
                            $data['CustAccountCode'] = !empty($value[0]['customeraccount']) ? $value[0]['customeraccount'] :"";
                            $data['Customer'] = !empty($value[0]['customername']) ? $value[0]['customername']:"";
                            $data['CustDestination'] = !empty($country->name) ? $country->name:"";
                            $data['Attempts'] =  $value->count() ;
                            $data['Completed'] = !empty($completed_count) ? count($completed_count) : "";
                            $data['ASR'] = \Str::limit((count($completed_count)/$value->count() * 100),5).'%';
                            $data['ACD'] =   !empty($sec) ? \Str::limit($sec,5) :"0";
                            $data['Raw Dur'] = !empty($Duration) ?$Duration :"";
                            $data['Rnd Dur'] = !empty($Duration) ? $Duration :"";
                            $data['Revenue'] = '$'.sprintf('%0.2f',$value->sum('fee'));
                            $data['Rev/Min'] = !empty($customer_fee) ? '$'.sprintf('%0.2f',$customer_fee) : "$ 0.00";
                            $data['Margin'] = !empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";
                            $data['Mar/Min'] =!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
                            $data['Mar%'] =  \Str::limit(($margin/$value->count() * 100),5).'%';
                            $data['CustProductGroup'] = "";
                            $data['VendProductGroup'] = "";
                            $list[]= $data; 
                        }
                    }else{
                        $data =array();
                        $data['CustAccountCode'] = "";
                        $data['Customer'] = "";
                        $data['CustDestination'] = "";
                        $data['Attempts'] =  "" ;
                        $data['Completed'] =   "";
                        $data['ASR'] =  "";
                        $data['ACD'] =  "";
                        $data['Raw Dur'] = "";
                        $data['Rnd Dur'] =  "";
                        $data['Revenue'] = "";
                        $data['Rev/Min'] ="";
                        $data['Margin'] = "";
                        $data['Mar/Min'] = "";
                        $data['Mar%'] = "";
                        $data['CustProductGroup'] = "";
                        $data['VendProductGroup'] = "";
                        $list[]= $data;
                    }
                    $destinationPath = public_path('storage/csv_files/');
                    if (!file_exists($destinationPath)) {
                        File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                    }
                    $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                    $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                    $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                    $exporthistory_arr['status'] = 'complete';
                    $exporthistory_arr->save();
                }
            }
        }
    }
}
