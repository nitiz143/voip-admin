<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportCsvXlsxHistory extends Model
{
    use HasFactory;
    protected $fillable = ['client_id','user_id','type','file_name','file','status','report_type'];
    
    public function clients() {
        return $this->belongsTo(Client::class,'client_id','id');
    }
}
