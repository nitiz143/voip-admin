<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trunk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'area_prefix',
        'rate_prefix',
        'prefix',
        'status',
    ];
    public function vendors()
    {
        return $this->hasMany(VendorTrunk::class,'trunkid');
    }
    public function customers()
    {
        return $this->hasMany(CustomerTrunk::class,'trunkid');
    }
}
