<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'protocol',
        'host',
        'port',
        'username',
        'password',
        'csv_path',
        'version',
   
    ];
}
