<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTrunk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'vendor_id',
        'prefix',
        'codedeck',
        'trunkid',
        'prefix_cdr',
        'status',
    ];

    public function trunk()
    {
        return $this->belongsTo(Trunk::class);
    }
}
