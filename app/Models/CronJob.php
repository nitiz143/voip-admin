<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_title',
        'cron_type',
        'gateway',
        'Alert',
        'success_email',
        'error_email',
        'download_limit',
        'threshold',
        'job_time',
        'job_intervel',
        'job_day',
        'start_time',
        'status',
    ];
}
