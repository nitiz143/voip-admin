<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_details', function (Blueprint $table) {
            $table->string('client_id');
            $table->string('account_owner')->nullable();
            $table->string('ownership')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('account_number')->nullable(); 
            $table->string('account_website')->nullable();
            $table->string('account_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('Vendor')->nullable();
            $table->string('fax')->nullable();
            $table->string('customer')->nullable();
            $table->string('employee')->nullable();
            $table->string('website')->nullable();
            $table->string('reseller')->nullable();
            $table->string('account_reseller')->nullable();
            $table->string('email')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('status')->nullable();
            $table->string('account_tag')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('currency')->nullable();
            $table->string('verification_status')->nullable();
            $table->string('norminal_code')->nullable();
            $table->string('language')->nullable();
        
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
        Schema::dropIfExists('account_details');
    }
}
