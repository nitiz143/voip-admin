<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDownloadProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_download_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('trunks')->nullable();
            $table->string('timezones')->nullable();
            $table->string('format')->nullable();
            $table->string('filetype')->nullable();
            $table->string('effective')->nullable();
            $table->string('customDate')->nullable();
            $table->string('isMerge')->nullable();
            $table->string('sendMail')->nullable();
            $table->string('type')->nullable();
            $table->string('account_owners')->nullable();
            $table->string('client_id')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('vendor_download_processes');
    }
}
