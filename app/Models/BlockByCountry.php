<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockByCountry extends Model
{
    use HasFactory;
    protected $guarded = [''];   
    public function countries()
    {
        return $this->belongsTo(Country::class);
    }
}