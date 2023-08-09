<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['id','crm_id','user_id','comment'];
   
    function user() {
        return $this->belongsTo(User::class,'user_id');
    }

}
