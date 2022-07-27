<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $fillable = [
        'caller_id', 'callere164','calleraccesse164','calleee164','calleeaccesse164','callerip','callerproductid','callertogatewaye164','calleeip','calleegatewayh323id','calleeproductid','calleetogatewaye164','billingmode','calllevel','agentfeetime','starttime','stoptime','pdd','holdtime','feeprefix','feetime','fee','suitefee','suitefeetime','incomefee','customername','agentfeeprefix','agentfee','agentsuitefee','agentsuitefeetime','agentaccount','agentname','flowno','softswitchdn','enddirection','endreason','calleebilling','cdrlevel','subcdr_id'
    ];
}



																									