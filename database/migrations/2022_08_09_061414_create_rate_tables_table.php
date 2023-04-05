<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('codeDeckId');
            $table->integer('trunkId');
            $table->string('currency');
            $table->string('name');
            $table->string('RoundChargedAmount');
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
        Schema::dropIfExists('rate_tables');
    }
}
