<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'account_id',
        'billing_class',
        'billing_type',
        'billing_timezone',
        'billing_startdate',
        'billing_cycle',
        'billing_cycle_startday',
        'auto_pay',
        'auto_pay_method',
        'send_invoice_via_email',
        'last_invoice_date',
        'next_invoice_date',
        'last_charge_date',
        'next_charge_date',
        'outbound_discount_plan',
        'inbound_discount_plan',
    ];
    public function clients()
    {
        return $this->belongsTo(Client::class);
    }

}
