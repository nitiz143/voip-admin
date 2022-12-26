<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Codes extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'destination',
        'codes',
        'rate($)',
        'status',
        'effective_date',
        'level',
    ];

    public function BlockByCodes()
    {
        return $this->hasMany(BlockByCode::class ,'CodeID' , 'id');
    }
}
