<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function BlockByCountries()
    {
        return $this->hasMany(BlockByCountry::class ,'CountryID' , 'id');
    }
}
