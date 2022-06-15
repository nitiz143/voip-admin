<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('rate_sheet');
            $table->string('company_name');
            $table->string('vat');
            $table->string('default_trunk_prefix');
            $table->string('last_trunk_prefix');
            $table->string('currency');
            $table->string('timezone');
            $table->string('default_dashboard');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address_line1');
            $table->string('city');
            $table->string('address_line2');
            $table->string('postzip');
            $table->string('address_line3');
            $table->string('country');
            $table->string('invoice_status');
            $table->string('decimal_place');
            $table->string('header_row');
            $table->string('footer_row');
            $table->string('position_left');
            $table->string('position_right');
            $table->string('smtp_server');
            $table->string('email_from');
            $table->string('smtp_user');
            $table->string('password');
            $table->string('port');
            $table->integer('cdr');
            $table->integer('acc_verification');
            $table->integer('email_invoice');
            $table->integer('certificate');
            $table->integer('ssl');
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
        Schema::dropIfExists('companies');
    }
}
