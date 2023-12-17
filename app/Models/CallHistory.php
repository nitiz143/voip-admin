<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;


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

    
    public static function completed($query, $request){
        $completed_count = array();
        if($request->Report == "Customer-Summary"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
        }
        if($request->Report == "Customer-Hourly"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
        }
        if($request->Report == "Customer-Margin-Report"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
        }
        if($request->Report == "Customer-Negative-Report"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
        }
        return $completed_count;
    }

    public static function asrValue($query, $request){
        $completed_count = array();
        if($request->Report == "Customer-Summary"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return round(count($completed_count)/$query->count() * 100); 
            }
        }
        if($request->Report == "Customer-Hourly"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return round(count($completed_count)/$query->count() * 100); 
            }
        }
        if($request->Report == "Customer-Margin-Report"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return round(count($completed_count)/$query->count() * 100); 
            }
        }

        if($request->Report == "Customer-Negative-Report"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return round(count($completed_count)/$query->count() * 100); 
            }
        }

    }

    public static function acdSec($query, $request){
        $completed_count = array();
        if($request->Report == "Customer-Summary"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return array_sum($completed_count) /  count($completed_count);
            }
        }
        if($request->Report == "Customer-Hourly"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return array_sum($completed_count) /  count($completed_count);
            }
        }
        if($request->Report == "Customer-Margin-Report"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return array_sum($completed_count) /  count($completed_count);
            }
        }
        if($request->Report == "Customer-Negative-Report"){
            if($query->feetime > 0 && $query->feetime != null) {
                $completed_count[] = $query->feetime;
            }
            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                return array_sum($completed_count) /  count($completed_count);
            }
        }
    }
    public static function rawDur($query, $request){
        $Duration_count = array();
        if($request->Report == "Customer-Summary"){
            $Duration_count[] = $query->feetime;
        }
        if($request->Report == "Customer-Hourly"){
            $Duration_count[] = $query->feetime;
        }
        if($request->Report == "Customer-Margin-Report"){
            $Duration_count[] = $query->feetime;
        }
        if($request->Report == "Customer-Negative-Report"){
            $Duration_count[] = $query->feetime;
        }
        return sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
    }

    public static function revenue($query, $request){
        if($request->Report == 'Customer-Summary'){
            return '$'.sprintf('%0.2f', $query->sum('fee'));
        }
        if($request->Report == 'Customer-Hourly'){
            return '$'.sprintf('%0.2f', $query->sum('fee'));
        }
        if($request->Report == "Customer-Margin-Report"){
            return '$'.sprintf('%0.2f', $query->sum('fee'));
        }
        if($request->Report == "Customer-Negative-Report"){
            return '$'.sprintf('%0.2f', $query->sum('fee'));
        }

    }

    public static function revenueMin($query, $request){
        $customer_fee ="";
        if($request->Report == "Customer-Summary"){
            $totalSum = $query->sum('feetime');
            if($query->sum('fee') > 0 && $totalSum > 0){
                $timepersec = $query->sum('fee')/$totalSum;
                $persec =  round($timepersec, 7);
                $customer_fee= $persec*60;
            }
        }
        if($request->Report == "Customer-Hourly"){
            $totalSum = $query->sum('feetime');
            if($query->sum('fee') > 0 && $totalSum > 0){
                $timepersec = $query->sum('fee')/$totalSum;
                $persec =  round($timepersec, 7);
                $customer_fee= $persec*60;
            }
        }
        if($request->Report == "Customer-Margin-Report"){
            $totalSum = $query->sum('feetime');
            if($query->sum('fee') > 0 && $totalSum > 0){
                $timepersec = $query->sum('fee')/$totalSum;
                $persec =  round($timepersec, 7);
                $customer_fee= $persec*60;
            }
        }
        if($request->Report == "Customer-Negative-Report"){
            $totalSum = $query->sum('feetime');
            if($query->sum('fee') > 0 && $totalSum > 0){
                $timepersec = $query->sum('fee')/$totalSum;
                $persec =  round($timepersec, 7);
                $customer_fee= $persec*60;
            }
        }
        return !empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00";
    }

    public static function margin($query, $request){
        if($request->Report == "Customer-Summary"){
            return '$'.sprintf('%0.2f', $query->sum('agentfee'));
        }
        if($request->Report == "Customer-Hourly"){
            return '$'.sprintf('%0.2f', $query->sum('agentfee'));
        }
        if($request->Report == "Customer-Margin-Report"){
            return '$'.sprintf('%0.2f', $query->sum('agentfee'));
        }

    }

    public static function marginMin($query, $request){
        $customer_fee ="";
        $agent_fee ="";
        $totalSum = $query->sum('feetime');
        $totalagentSum = $query->sum('agentfeetime');
        if($request->Report == "Customer-Summary"){
            if($query->sum('fee') != 0 && $totalSum != 0){
                $timepersec2 = $query->sum('fee')/$totalSum;
                $persec2 =  round($timepersec2, 7);
                $customer_fee= $persec2*60;
            }
    
            if($query->sum('agentfee') > 0 && $totalagentSum > 0){
                $timepersec2 = $query->sum('agentfee')/$totalagentSum ;
                $persec2 =  round($timepersec2, 7);
                $agent_fee= $persec2*60;
            }
        }
        if($request->Report == "Customer-Hourly"){
            if($query->sum('fee') != 0 && $totalSum != 0){
                $timepersec2 = $query->sum('fee')/$totalSum;
                $persec2 =  round($timepersec2, 7);
                $customer_fee= $persec2*60;
            }
    
            if($query->sum('agentfee') > 0 && $totalagentSum > 0){
                $timepersec2 = $query->sum('agentfee')/$totalagentSum ;
                $persec2 =  round($timepersec2, 7);
                $agent_fee= $persec2*60;
            }
        }
        if($request->Report == "Customer-Margin-Report"){
            if($query->sum('fee') != 0 && $totalSum != 0){
                $timepersec2 = $query->sum('fee')/$totalSum;
                $persec2 =  round($timepersec2, 7);
                $customer_fee= $persec2*60;
            }
    
            if($query->sum('agentfee') > 0 && $totalagentSum > 0){
                $timepersec2 = $query->sum('agentfee')/$totalagentSum ;
                $persec2 =  round($timepersec2, 7);
                $agent_fee= $persec2*60;
            }
        }
        $margin_per_min = (int)$customer_fee - (int)$agent_fee;
        return !empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";
    }

    public static function marginPercent($query, $request){
        $fee_count = array();
        $agentfee_count = array();
        if($request->Report == "Customer-Hourly"){
            if($query->agentfee > 0) {
                $agentfee_count[] = $query->agentfee;
            }
            if($query->fee > 0) {
                $fee_count[] = $query->fee;
            }
        }
        if($request->Report == "Customer-Summary"){
            if($query->agentfee > 0) {
                $agentfee_count[] = $query->agentfee;
            }
            if($query->fee > 0) {
                $fee_count[] = $query->fee;
            }
        }
        if($request->Report == "Customer-Margin-Report"){
            if($query->agentfee > 0) {
                $agentfee_count[] = $query->agentfee;
            }
            if($query->fee > 0) {
                $fee_count[] = $query->fee;
            }
        }

        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
        return round($margin/$query->count() * 100);
    }
    
} 



