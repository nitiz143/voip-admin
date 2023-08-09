<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPrefixCdrToVendorTrunks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_trunks', function (Blueprint $table) {
            $table->integer('prefix_cdr')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_trunks', function (Blueprint $table) {
            $table->dropColumn('prefix_cdr')->nullable(false)->change();
        });
    }
}
