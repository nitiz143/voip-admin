<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDownloadProcess extends Model
{
    use HasFactory;
    // protected $table = "";
    protected $fillable = [
        'name',
        'trunks',
        'timezones',
        'format',
        'filetype',
        'effective',
        'customDate',
        'isMerge',
        'sendMail',
        'type',
        'account_owners',
        'created_by',
        'client_id',
    ];

}
