<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'client_id',
        'CodeID',
        'Trunk',
        'preference'
    ];

    public function codes()
    {
        return $this->belongsTo(Codes::class);
    }
}
