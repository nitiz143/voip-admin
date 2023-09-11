<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

class ExportHistory extends Model
{
    use HasFactory;
	protected $fillable = ['client_id','user_id','report_type','file_name','file','status','Invoice_no','send_at'];

    public function clients() {
        return $this->belongsTo(Client::class,'client_id','id');
    }
}
