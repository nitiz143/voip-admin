<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use Illuminate\Support\Str;


class CallHistory extends Model
{
    protected $fillable = [
        'callere164',
        'calleraccesse164',
        'calleee164',
        'calleeaccesse164',
        'callerip',
        'callercodec',
        'callergatewayid',
        'callerproductid',
        'callertogatewaye164',
        'callertype',
        'calleeip',
        'calleecodec',
        'calleegatewayid',
        'calleeproductid',
        'calleetogatewaye164',
        'calleetype',
        'billingmode',
        'calllevel',
        'agentfeetime',
        'starttime',
        'stoptime',
        'callerpdd',
        'calleepdd',
        'holdtime',
        'callerareacode',
        'feetime',
        'fee',
        'tax',
        'suitefee',
        'suitefeetime',
        'incomefee',
        'incometax',
        'customeraccount',
        'customername',
        'calleeareacode',
        'agentfee',
        'agenttax',
        'agentsuitefee',
        'agentsuitefeetime',
        'agentaccount',
        'agentname',
        'flowno',
        'softswitchname',
        'softswitchcallid',
        'callercallid',
        'calleecallid',
        'rtpforward',
        'enddirection',
        'endreason',
        'billingtype',
        'cdrlevel',
        'agentcdr_id',
        'transactionid',
        'account_id',
        'callerrtpip',
        'calleertpip',
        'calleroriginalcallid',
        'calleroriginalinfo',
        'sipreasonheader',
        'recordstarttime',
        'flownofirst',
        'vendor_account_id',
    ];
    public function clients() {
        return $this->belongsTo(Client::class,'account_id','id');
    }
    public function Attempts($request){
      $query = self::select('*');
        if((!empty($request->StartDate) && !empty($request->EndDate))){
            $start =  strtotime($request->StartDate);
            $start = $start*1000;
            $end = strtotime($request->EndDate);
            $end = $end*1000;
            $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
        }
        if($request->billingtype == 'one') {            
            $query->where( 'feetime', ">", "0"); 
        }
        if($request->Report != 'Account-Manage') {
            if(!empty( $AccountID )) {
                $query->where('call_histories.account_id', $AccountID);
            }
        }elseif($request->Report == 'Customer-Negative-Report'){
            $query->where('call_histories.fee','<=', 0);
        }
        return $query->count();    

    }

    public function completed($request){

        $query = self::select('feetime');
        
        if (!empty($request->StartDate) && !empty($request->EndDate)) {
            $start = strtotime($request->StartDate);
            $start = $start * 1000;
            $end = strtotime($request->EndDate);
            $end = $end * 1000;
            
            $query->where([
                ['starttime', '>=', $start],
                ['stoptime', '<=', $end]
            ]);              
        }
        
        $query->where('feetime', '!=', 0); 
        
        if ($request->Report != 'Account-Manage') {
            if (!empty($AccountID)) {
                $query->where('call_histories.account_id', $AccountID);
            }
        } elseif ($request->Report == 'Customer-Negative-Report') {
            $query->where('call_histories.fee', '<=', 0);
        }
    
        $completed_count = $query->whereNotNull('feetime')->count();
        
        return !empty($completed_count) ? $completed_count : "";  
    }
    

public function ASR($request){

    $query = self::query();
    $total_count = $query->count();
    if (!empty($request->StartDate) && !empty($request->EndDate)) {
        $start = strtotime($request->StartDate) * 1000;
        $end = strtotime($request->EndDate) * 1000;
        
        $query->where([
            ['starttime', '>=', $start],
            ['stoptime', '<=', $end]
        ]);              
    }
    $query->where('feetime', '!=', 0); 

    
    if ($request->Report != 'Account-Manage') {
        if (!empty($AccountID)) {
            $query->where('call_histories.account_id', $AccountID);
        }
    } elseif ($request->Report == 'Customer-Negative-Report') {
        $query->where('call_histories.fee', '<=', 0);
    }
    
    $completed_count = $query->whereNotNull('feetime')->count();
    
    $asr = $completed_count / $total_count * 100;

    return number_format($asr, 5) . '%';
}



    public function ACD($request){

        $query = self::select('feetime');
        
        if (!empty($request->StartDate) && !empty($request->EndDate)) {
            $start = strtotime($request->StartDate);
            $start = $start * 1000;
            $end = strtotime($request->EndDate);
            $end = $end * 1000;
            
            $query->where([
                ['starttime', '>=', $start],
                ['stoptime', '<=', $end]
            ]);              
        }
          
        $query->where('feetime', '!=', 0); 
        
        if ($request->Report != 'Account-Manage') {
            if (!empty($AccountID)) {
                $query->where('call_histories.account_id', $AccountID);
            }
        } elseif ($request->Report == 'Customer-Negative-Report') {
            $query->where('call_histories.fee', '<=', 0);
        }
    
        $completed_count = $query->whereNotNull('feetime')->get()->pluck('feetime')->toArray();
    
        $sec = 0;
        if (!empty($completed_count)) {
            $sec = array_sum($completed_count) / count($completed_count);
        }
    
        return !empty($sec) ? \Str::limit($sec, 5) : "0";
    }
    


    public function Raw_Dur($request){

        $query = self::select('feetime');
        
        if (!empty($request->StartDate) && !empty($request->EndDate)) {
            $start = strtotime($request->StartDate);
            $start = $start * 1000;
            $end = strtotime($request->EndDate);
            $end = $end * 1000;
            
            $query->where([
                ['starttime', '>=', $start],
                ['stoptime', '<=', $end]
            ]);              
        }
          
        $query->where('feetime', '!=', 0); 
        
        if ($request->Report != 'Account-Manage') {
            if (!empty($AccountID)) {
                $query->where('call_histories.account_id', $AccountID);
            }
        } elseif ($request->Report == 'Customer-Negative-Report') {
            $query->where('call_histories.fee', '<=', 0);
        }
    
        $durationInSeconds = $query->whereNotNull('feetime')->sum('feetime');
        
        $durationFormatted = sprintf("%02.2d:%02.2d", floor($durationInSeconds / 60), $durationInSeconds % 60);
        
        return $durationFormatted;
    }
    


    public function Rnd_Dur($request){

        $query = self::select('feetime');
        
        if (!empty($request->StartDate) && !empty($request->EndDate)) {
            $start = strtotime($request->StartDate);
            $start = $start * 1000;
            $end = strtotime($request->EndDate);
            $end = $end * 1000;
            
            $query->where([
                ['starttime', '>=', $start],
                ['stoptime', '<=', $end]
            ]);              
        }
          
        $query->where('feetime', '!=', 0); 
        
        if ($request->Report != 'Account-Manage') {
            if (!empty($AccountID)) {
                $query->where('call_histories.account_id', $AccountID);
            }
        } elseif ($request->Report == 'Customer-Negative-Report') {
            $query->where('call_histories.fee', '<=', 0);
        }
    
        $durationInSeconds = $query->whereNotNull('feetime')->sum('feetime');
    
        $durationFormatted = sprintf("%02.2d:%02.2d", floor($durationInSeconds / 60), $durationInSeconds % 60);
        
        return $durationFormatted;
    }
    


    public function Revenue($request){

        $query = self::select('feetime');
        if((!empty($request->StartDate) && !empty($request->EndDate))){
            $start =  strtotime($request->StartDate);
            $start = $start*1000;
            $end = strtotime($request->EndDate);
            $end = $end*1000;
            $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
        }
    
            $query->where( 'feetime', "!=", "0"); 
        
        if($request->Report != 'Account-Manage') {
            if(!empty( $AccountID )) {
                $query->where('call_histories.account_id', $AccountID);
            }
        }elseif($request->Report == 'Customer-Negative-Report'){
            $query->where('call_histories.fee','<=', 0);
        }
                
        return  '$'.sprintf('%0.2f', $query->sum('fee'));    


    }

    public function Rev_Min($request){

        $query = self::select('feetime');
        if((!empty($request->StartDate) && !empty($request->EndDate))){
            $start =  strtotime($request->StartDate);
            $start = $start*1000;
            $end = strtotime($request->EndDate);
            $end = $end*1000;
            $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
        }
    
            $query->where( 'feetime', "!=", "0"); 
        
        if($request->Report != 'Account-Manage') {
            if(!empty( $AccountID )) {
                $query->where('call_histories.account_id', $AccountID);
            }
        }elseif($request->Report == 'Customer-Negative-Report'){
            $query->where('call_histories.fee','<=', 0);
        }
           
        $customer_fee="";
        $totalSum = $query->sum('feetime');

        if($query->sum('fee') > 0 && $totalSum > 0){
            $timepersec = $query->sum('fee')/$totalSum;
            $persec =  round($timepersec, 7);
            $customer_fee= $persec*60;
        }
        return  !empty($customer_fee) ? '$'.sprintf('%0.2f',$customer_fee) : "$ 0.00";  


    }



    public function Cost($request){

        $query = self::select('feetime');
        if((!empty($request->StartDate) && !empty($request->EndDate))){
            $start =  strtotime($request->StartDate);
            $start = $start*1000;
            $end = strtotime($request->EndDate);
            $end = $end*1000;
            $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
        }
    
            $query->where( 'feetime', "!=", "0"); 
        
        if($request->Report != 'Account-Manage') {
            if(!empty( $AccountID )) {
                $query->where('call_histories.account_id', $AccountID);
            }
        }elseif($request->Report == 'Customer-Negative-Report'){
            $query->where('call_histories.fee','<=', 0);
        }
           
        return  '$'.sprintf('%0.2f',$query->sum('agentfee')); 


    }



    public function Cost_Min($request){

        $query = self::select('feetime');
    
        if(!empty($request->StartDate) && !empty($request->EndDate)){
            $start = strtotime($request->StartDate) * 1000;
            $end = strtotime($request->EndDate) * 1000;
    
            $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);
        }
    
        $query->where('feetime', '!=', 0); 
    
        if($request->Report != 'Account-Manage') {
            if(!empty($request->AccountID)) {
                $query->where('call_histories.account_id', $request->AccountID);
            }
        } elseif($request->Report == 'Customer-Negative-Report'){
            $query->where('call_histories.fee', '<=', 0);
        }
           
        $agent_fee = 0;
    
        $totalAgentSum = $query->sum('agentfee');
    
        if($totalAgentSum > 0 && $query->count() > 0){
            $timepersec2 = $totalAgentSum / $query->count(); // Adjusted calculation here
            $persec2 = round($timepersec2, 7);
            $agent_fee = $persec2 * 60;
        }
    
        return '$' . number_format($agent_fee, 2);
    }
    
    
    




    public function Margin($request){

        $query = self::select('feetime', 'fee', 'agentfeetime', 'agentfee');
        
        if (!empty($request->StartDate) && !empty($request->EndDate)) {
            $start = strtotime($request->StartDate);
            $start = $start * 1000;
            $end = strtotime($request->EndDate);
            $end = $end * 1000;
            
            $query->where([
                ['starttime', '>=', $start],
                ['stoptime', '<=', $end]
            ]);              
        }
          
        $query->where('feetime', '!=', 0); 
        
        if ($request->Report != 'Account-Manage') {
            if (!empty($AccountID)) {
                $query->where('call_histories.account_id', $AccountID);
            }
        } elseif ($request->Report == 'Customer-Negative-Report') {
            $query->where('call_histories.fee', '<=', 0);
        }
    
        $invoices = $query->get();
    
        $fee_count = $agentfee_count = $Duration_count = $Agent_Duration_count = [];
    
        foreach ($invoices as $invoice) {
            $Duration_count[] = $invoice->feetime;
            $fee_count[] = $invoice->fee;
    
            if ($invoice->feetime != 0 && $invoice->feetime != null) {
                $completed_count[] = $invoice->feetime;
            }
    
            $Agent_Duration_count[] = $invoice->agentfeetime;
            $agentfee_count[] = $invoice->agentfee;
    
            if ($invoice->agentfeetime != 0 && $invoice->agentfeetime != null) {
                $completed_agent_count[] = $invoice->agentfeetime;
            }
        }
    
        $margin = array_sum($fee_count) - array_sum($agentfee_count);
        return !empty($margin) ? '$' . sprintf('%0.2f', $margin) : "$ 0.00";
    }
    


    public function Mar_Min($request){

        $query = self::select('feetime');
        
        if (!empty($request->StartDate) && !empty($request->EndDate)) {
            $start = strtotime($request->StartDate);
            $start = $start * 1000;
            $end = strtotime($request->EndDate);
            $end = $end * 1000;
            
            $query->where([
                ['starttime', '>=', $start],
                ['stoptime', '<=', $end]
            ]);              
        }
        
        $query->where('feetime', '!=', 0); 
        
        if ($request->Report != 'Account-Manage') {
            if (!empty($AccountID)) {
                $query->where('call_histories.account_id', $AccountID);
            }
        } elseif ($request->Report == 'Customer-Negative-Report') {
            $query->where('call_histories.fee', '<=', 0);
        }
    
        $customer_fee = 0;
        $agent_fee = 0;
    
        $totalSum = $query->sum('feetime');
    
        if ($query->sum('fee') > 0 && $totalSum > 0) {
            $timepersec = $query->sum('fee') / $totalSum;
            $persec = round($timepersec, 7);
            $customer_fee = $persec * 60;
        }
    
        $totalagentSum = $query->sum('agentfeetime');
        if ($query->sum('agentfee') > 0 && $totalagentSum > 0) {
            $timepersec2 = $query->sum('agentfee') / $totalagentSum;
            $persec2 = round($timepersec2, 7);
            $agent_fee = $persec2 * 60;
        }
    
        $margin_per_min = (int)$customer_fee - (int)$agent_fee;
        return !empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";    
    }
    


    public function Mar($request){

        $query = self::select('feetime', 'fee', 'agentfeetime', 'agentfee');
        
        if (!empty($request->StartDate) && !empty($request->EndDate)) {
            $start = strtotime($request->StartDate);
            $start = $start * 1000;
            $end = strtotime($request->EndDate);
            $end = $end * 1000;
            
            $query->where([
                ['starttime', '>=', $start],
                ['stoptime', '<=', $end]
            ]);              
        }
          
        // $query->where('feetime', '!=', 0); 
        
        if ($request->Report != 'Account-Manage') {
            if (!empty($AccountID)) {
                $query->where('call_histories.account_id', $AccountID);
            }
        } elseif ($request->Report == 'Customer-Negative-Report') {
            $query->where('call_histories.fee', '<=', 0);
        }
    
        $invoices = $query->get();
    
        $fee_count = $agentfee_count = [];
    
        foreach ($invoices as $invoice) {
            $fee_count[] = $invoice->fee;
            $agentfee_count[] = $invoice->agentfee;
        }
        
        $totalFee = array_sum($fee_count);
        $totalAgentFee = array_sum($agentfee_count);
    
        $margin = $totalFee - $totalAgentFee;
    
        $marginPercentage = (count($invoices) > 0) ? ($margin / count($invoices) * 100) : 0;
    
        return number_format($marginPercentage, 5) . '%';
    }
    
    

    



} 




