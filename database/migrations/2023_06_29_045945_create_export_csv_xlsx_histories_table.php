<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportCsvXlsxHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_csv_xlsx_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default('0');
            $table->integer('client_id')->default('0');
            $table->string('type');
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
        Schema::dropIfExists('export_csv_xlsx_histories');
    }
}
