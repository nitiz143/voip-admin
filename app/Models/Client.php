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
        'status',
        'vat_number',
        'description',
        'address_line1',
        'city',
        'address_line2',
        'postzip',
        'address_line3',
        'country',
     ];
}
