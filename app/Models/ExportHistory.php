<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportHistory extends Model
{
    use HasFactory;
	protected $fillable = ['clinic_id','user_id','report_type','file_name','file','status'];
}
