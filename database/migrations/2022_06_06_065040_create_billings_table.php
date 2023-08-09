<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('billing_class')->nullable();
            $table->string('billing_type')->nullable();
            $table->string('billing_timezone')->nullable();
            $table->string('billing_startdate')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->string('billing_cycle_startday')->nullable();
            $table->string('auto_pay')->nullable();
            $table->string('auto_pay_method')->nullable();
            $table->string('send_invoice_via_email')->nullable();
            $table->string('last_invoice_date')->nullable();
            $table->string('next_invoice_date')->nullable();
            $table->string('last_charge_date')->nullable();
            $table->string('next_charge_date')->nullable();
            $table->string('outbound_discount_plan')->nullable();
            $table->string('inbound_discount_plan')->nullable();
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
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropColumn('billing_class')->nullable();
            $table->dropColumn('billing_type')->nullable();
            $table->dropColumn('billing_timezone')->nullable(); 
            $table->dropColumn('billing_startdate')->nullable();
            $table->dropColumn('billing_cycle')->nullable();
            $table->dropColumn('billing_cycle_startday')->nullable();
            $table->dropColumn('auto_pay')->nullable();
            $table->dropColumn('auto_pay_method')->nullable();
            $table->dropColumn('send_invoice_via_email')->nullable();
            $table->dropColumn('last_invoice_date')->nullable();
            $table->dropColumn('next_invoice_date')->nullable();
            $table->dropColumn('last_charge_date')->nullable();
            $table->dropColumn('next_charge_date')->nullable();
            $table->dropColumn('outbound_discount_plan')->nullable();
            $table->dropColumn('inbound_discount_plan')->nullable();

        });
    }
}
