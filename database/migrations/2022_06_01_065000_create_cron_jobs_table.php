<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('cron_type');
            $table->string('gateway')->nullable();
            $table->string('Alert')->nullable();
            $table->string('success_email');
            $table->string('error_email');
            $table->string('download_limit')->nullable();
            $table->string('threshold')->nullable();
            $table->string('job_time');
            $table->string('job_intervel');
            $table->string('job_day');
            $table->string('start_time');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron_jobs');
    }
}
