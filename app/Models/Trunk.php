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
    ];
    public function vendors()
    {
        return $this->hasMany(VendorTrunk::class,'trunkid');
    }
}
