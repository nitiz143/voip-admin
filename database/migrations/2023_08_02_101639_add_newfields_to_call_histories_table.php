<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewfieldsToCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_histories', function (Blueprint $table) {
            $table->string('callerrtpip')->nullable();
            $table->string('calleertpip')->nullable();
            $table->string('calleroriginalcallid')->nullable();
            $table->string('calleroriginalinfo')->nullable();
            $table->string('sipreasonheader')->nullable();
            $table->string('recordstarttime')->nullable();
            $table->string('flownofirst')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_histories', function (Blueprint $table) {
            //
        });
    }
}
