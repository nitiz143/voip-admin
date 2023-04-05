<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToTrunksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trunks', function (Blueprint $table) {
            $table->integer('area_prefix')->nullable();
            $table->integer('rate_prefix')->nullable();
            $table->integer('prefix')->nullable();
            $table->integer('status')->default(1);;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trunks', function (Blueprint $table) {
            $table->dropColumn('area_prefix');
            $table->dropColumn('rate_prefix');
            $table->dropColumn('prefix');
            $table->dropColumn('status');
        });
    }
}
