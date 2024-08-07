<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default('0');
            $table->integer('client_id')->default('0');
            $table->string('report_type');
            $table->string('file_name')->nullable();
            $table->longText('file')->nullable();
            $table->enum('status', array('complete','pending'));
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
        Schema::dropIfExists('export_histories');
    }
}
