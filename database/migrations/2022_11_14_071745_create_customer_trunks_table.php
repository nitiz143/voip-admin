<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTrunksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_trunks', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('prefix');
            $table->integer('includePrefix')->nullable();
            $table->integer('prefix_cdr')->nullable();
            $table->integer('routine_plan_status')->nullable();
            $table->integer('codedeck');
            $table->integer('rate_table_id');
            $table->integer('trunkid');
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
        Schema::dropIfExists('customer_trunks');
    }
}
