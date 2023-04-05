<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateUpload extends Model
{
    use HasFactory;
    protected $fillable = [
        'destination','destination_code','effective_date','rate','billing_increment','deletion_date'
    ];
}
