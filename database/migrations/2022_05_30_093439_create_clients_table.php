<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('lead_owner');
            $table->string('company');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone');
            $table->string('fax');
            $table->string('mobile');
            $table->string('website');
            $table->string('skype_id');
            $table->string('status');
            $table->string('vat_number');
            $table->string('description');
            $table->string('address_line1');
            $table->string('city');
            $table->string('address_line2');
            $table->string('postzip');
            $table->string('address_line3');
            $table->string('country');
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
        Schema::dropIfExists('clients');
    }
}
