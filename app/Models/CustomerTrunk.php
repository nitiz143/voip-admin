<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTrunk extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'customer_id',
        'prefix',
        'codedeck',
        'rate_table_id',
        'trunkid',
        'prefix_cdr',
        'includePrefix',
        'routine_plan_status',
        'status',
    ];

    public function trunk()
    {
        return $this->belongsTo(Trunk::class);
    }
}
