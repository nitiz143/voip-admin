<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'rate_sheet',
        'company_name',
        'vat',
        'default_trunk_prefix',
        'last_trunk_prefix',
        'currency',
        'timezone',
        'default_dashboard',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address_line1',
        'city',
        'address_line2',
        'postzip',
        'address_line3',
        'country',
        'invoice_status',
        'decimal_place',
        'header_row',
        'footer_row',
        'position_left',
        'position_right',
        'smtp_server',
        'email_from',
        'smtp_user',
        'password',
        'port',
        'cdr',
        'acc_verification',
        'email_invoice',
        'certificate',
        'ssl',
    ];
}
