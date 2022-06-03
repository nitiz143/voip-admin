<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('account_owner')->nullable();
            $table->string('ownership')->nullable();
            $table->string('account_number')->nullable(); 
            $table->string('account_name')->nullable();
            $table->string('Vendor')->nullable();
            $table->string('customer')->nullable();
            $table->string('reseller')->nullable();
            $table->string('account_reseller')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('account_tag')->nullable();
            $table->string('employee')->nullable();
            $table->string('currency')->nullable();
            $table->string('verification_status')->nullable();
            $table->string('norminal_code')->nullable();
            $table->string('language')->nullable();
            $table->string('customer_authentication_rule')->nullable();
            $table->string('customer_authentication_value')->nullable();
            $table->string('vendor_authentication_rule')->nullable();
            $table->string('vendor_authentication_value')->nullable();
            $table->string('account_balance')->nullable();
            $table->string('customer_unbilled_ammount')->nullable();
            $table->string('vendor_unbilled_ammount')->nullable();
            $table->string('account_exposure')->nullable();
            $table->string('available_credit_limit')->nullable();
            $table->string('credit_limit')->nullable();
            $table->string('balance_threshold')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('account_owner')->nullable();
            $table->dropColumn('ownership')->nullable();
            $table->dropColumn('account_number')->nullable(); 
            $table->dropColumn('account_name')->nullable();
            $table->dropColumn('Vendor')->nullable();
            $table->dropColumn('customer')->nullable();
            $table->dropColumn('reseller')->nullable();
            $table->dropColumn('account_reseller')->nullable();
            $table->dropColumn('billing_email')->nullable();
            $table->dropColumn('employee')->nullable();
            $table->dropColumn('account_tag')->nullable();
            $table->dropColumn('currency')->nullable();
            $table->dropColumn('verification_status')->nullable();
            $table->dropColumn('norminal_code')->nullable();
            $table->dropColumn('language')->nullable();
            $table->dropColumn('customer_authentication_rule')->nullable();
            $table->dropColumn('customer_authentication_value')->nullable();
            $table->dropColumn('vendor_authentication_rule')->nullable();
            $table->dropColumn('vendor_authentication_value')->nullable();
            $table->dropColumn('account_balance')->nullable();
            $table->dropColumn('customer_unbilled_ammount')->nullable();
            $table->dropColumn('vendor_unbilled_ammount')->nullable();
            $table->dropColumn('account_exposure')->nullable();
            $table->dropColumn('available_credit_limit')->nullable();
            $table->dropColumn('credit_limit')->nullable();
            $table->dropColumn('balance_threshold')->nullable();
        });
    }
}
