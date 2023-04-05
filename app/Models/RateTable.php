<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateTable extends Model
{
    use HasFactory;
    protected $fillable = [
        'codeDeckId','trunkId','currency','name','RoundChargedAmount'
    ];
}
