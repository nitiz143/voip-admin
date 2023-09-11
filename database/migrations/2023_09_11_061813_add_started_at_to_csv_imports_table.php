<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartedAtToCsvImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csv_imports', function (Blueprint $table) {
            $table->integer('started_at')->nullable();
            $table->integer('ended_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('csv_imports', function (Blueprint $table) {
            $table->dropColumn('started_at');
            $table->dropColumn('ended_at');


        });
    }
}
