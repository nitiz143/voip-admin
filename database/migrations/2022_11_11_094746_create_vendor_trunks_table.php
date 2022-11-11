<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTrunksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_trunks', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->string('prefix');
            $table->integer('codedeck');
            $table->integer('trunkid');
            $table->tinyInteger('prefix_cdr');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('vendor_trunks');
    }
}
