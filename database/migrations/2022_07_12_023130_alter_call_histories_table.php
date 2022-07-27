<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_histories', function (Blueprint $table) {
            $table->string('caller_id')->nullable()->change();
            $table->string('callere164')->nullable()->change();
            $table->string('calleraccesse164')->nullable()->change();
            $table->string('calleee164')->nullable()->change();
            $table->string('calleeaccesse164')->nullable()->change();
            $table->string('callerip')->nullable()->change();
            $table->string('callerproductid')->nullable()->change();
            $table->string('callertogatewaye164')->nullable()->change();
            $table->string('calleeip')->nullable()->change();
            $table->string('calleegatewayh323id')->nullable()->change();
            $table->string('calleeproductid')->nullable()->change();
            $table->string('calleetogatewaye164')->nullable()->change();
            $table->string('billingmode')->nullable()->change();
            $table->string('calllevel')->nullable()->change();
            $table->string('agentfeetime')->nullable()->change();
            $table->string('starttime')->nullable()->change();
            $table->string('stoptime')->nullable()->change();
            $table->string('pdd')->nullable()->change();
            $table->string('holdtime')->nullable()->change();
            $table->string('feeprefix')->nullable()->change();
            $table->string('feetime')->nullable()->change();
            $table->string('fee')->nullable()->change();
            $table->string('suitefee')->nullable()->change();
            $table->string('suitefeetime')->nullable()->change();
            $table->string('incomefee')->nullable()->change();
            $table->string('customername')->nullable()->change();
            $table->string('agentfeeprefix')->nullable()->change();
            $table->string('agentfee')->nullable()->change();
            $table->string('agentsuitefee')->nullable()->change();
            $table->string('agentsuitefeetime')->nullable()->change();
            $table->string('agentaccount')->nullable()->change();
            $table->string('agentname')->nullable()->change();
            $table->string('flowno')->nullable()->change();
            $table->string('softswitchdn')->nullable()->change();
            $table->string('enddirection')->nullable()->change();
            $table->string('endreason')->nullable()->change();
            $table->string('calleebilling')->nullable()->change();
            $table->string('cdrlevel')->nullable()->change();
            $table->string('subcdr_id')->nullable()->change();

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
            $table->string('caller_id')->nullable(false)->change();
            $table->string('callere164')->nullable(false)->change();
            $table->string('calleraccesse164')->nullable(false)->change();
            $table->string('calleee164')->nullable(false)->change();
            $table->string('calleeaccesse164')->nullable(false)->change();
            $table->string('callerip')->nullable(false)->change();
            $table->string('callerproductid')->nullable(false)->change();
            $table->string('callertogatewaye164')->nullable(false)->change();
            $table->string('calleeip')->nullable()->change(false);
            $table->string('calleegatewayh323id')->nullable(false)->change();
            $table->string('calleeproductid')->nullable(false)->change();
            $table->string('calleetogatewaye164')->nullable(false)->change();
            $table->string('billingmode')->nullable(false)->change();
            $table->string('calllevel')->nullable(false)->change();
            $table->string('agentfeetime')->nullable(false)->change();
            $table->string('starttime')->nullable(false)->change();
            $table->string('stoptime')->nullable(false)->change();
            $table->string('pdd')->nullable(false)->change();
            $table->string('holdtime')->nullable(false)->change();
            $table->string('feeprefix')->nullable(false)->change();
            $table->string('feetime')->nullable(false)->change();
            $table->string('fee')->nullable(false)->change();
            $table->string('suitefee')->nullable(false)->change();
            $table->string('suitefeetime')->nullable(false)->change();
            $table->string('incomefee')->nullable(false)->change();
            $table->string('customername')->nullable(false)->change();
            $table->string('agentfeeprefix')->nullable(false)->change();
            $table->string('agentfee')->nullable(false)->change();
            $table->string('agentsuitefee')->nullable(false)->change();
            $table->string('agentsuitefeetime')->nullable(false)->change();
            $table->string('agentaccount')->nullable(false)->change();
            $table->string('agentname')->nullable(false)->change();
            $table->string('flowno')->nullable(false)->change();
            $table->string('softswitchdn')->nullable(false)->change();
            $table->string('enddirection')->nullable(false)->change();
            $table->string('endreason')->nullable(false)->change();
            $table->string('calleebilling')->nullable(false)->change();
            $table->string('cdrlevel')->nullable(false)->change();
            $table->string('subcdr_id')->nullable(false)->change();
        });
    }
}
