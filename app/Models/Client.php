<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'lead_owner',
        'company',
        'firstname',
        'lastname',
        'email',
        'phone',
        'fax',
        'mobile',
        'website',
        'lead_source',
        'lead_status',
        'rating',
        'employee',
        'skype_id',
        'timezone',
        'status',
        'country',
        'vat_number',
        'description',
        'address_line1',
        'city',
        'address_line2',
        'postzip',
        'address_line3',
        'account_owner',
        'ownership',
        'account_number',
        'account_website',
        'account_name',
        'Vendor',
        'customer',
        'reseller',
        'account_reseller',
        'customer_unbilled_ammount',
        'vendor_unbilled_ammount',
        'billing_email',
        'account_tag',
        'currency',
        'verification_status',
        'norminal_code',
        'language',
        'customer_authentication_rule',
        'customer_authentication_value',
        'vendor_authentication_rule',
        'vendor_authentication_value',
        'account_balance',
        'customer_unbilled_ammount',
        'vendor_unbilled_ammount',
        'account_exposure',
        'available_credit_limit',
        'credit_limit',
        'balance_threshold',
        'billing_status',
        'customer_call_id',
        'vender_call_id'
     ];
}
