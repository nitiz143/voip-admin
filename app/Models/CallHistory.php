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
        'account_id'
    ];
    public function clients() {
        return $this->belongsTo(Client::class,'account_id','id');
    }
} 



