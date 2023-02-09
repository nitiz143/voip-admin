<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPhoneToCrms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crms', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->string('fax')->nullable()->change();
            $table->string('mobile')->nullable()->change();
            $table->string('website')->nullable()->change();
            $table->string('lead_source')->nullable()->change();
            $table->string('lead_status')->nullable()->change();
            $table->string('rating')->nullable()->change();
            $table->string('employee')->nullable()->change();
            $table->string('skype_id')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->string('vat_number')->nullable()->change();
            $table->string('description')->nullable()->change();
            $table->string('address_line1')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('address_line2')->nullable()->change();
            $table->string('postzip')->nullable()->change();
            $table->string('address_line3')->nullable()->change();
            $table->string('country')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crms', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
            $table->string('fax')->nullable(false)->change();
            $table->string('mobile')->nullable(false)->change();
            $table->string('website')->nullable(false)->change();
            $table->string('lead_source')->nullable(false)->change();
            $table->string('lead_status')->nullable(false)->change();
            $table->string('rating')->nullable(false)->change();
            $table->string('employee')->nullable(false)->change();
            $table->string('skype_id')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
            $table->string('vat_number')->nullable(false)->change();
            $table->string('description')->nullable(false)->change();
            $table->string('address_line1')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('address_line2')->nullable(false)->change();
            $table->string('postzip')->nullable(false)->change();
            $table->string('address_line3')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();
        });
    }
}
