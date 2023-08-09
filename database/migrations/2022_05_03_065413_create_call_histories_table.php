<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_histories', function (Blueprint $table) {
            $table->id();
            $table->string('callere164')->nullable();
            $table->string('calleraccesse164')->nullable();
            $table->string('calleee164')->nullable();
            $table->string('calleeaccesse164')->nullable();
            $table->string('callerip')->nullable();
            $table->string('callercodec')->nullable();
            $table->string('callergatewayid')->nullable();
            $table->string('callerproductid')->nullable();
            $table->string('callertogatewaye164')->nullable();
            $table->string('callertype')->nullable();
            $table->string('calleeip')->nullable();
            $table->string('calleecodec')->nullable();
            $table->string('calleegatewayid')->nullable();
            $table->string('calleeproductid')->nullable();
            $table->string('calleetogatewaye164')->nullable();
            $table->string('calleetype')->nullable();
            $table->string('billingmode')->nullable();
            $table->string('calllevel')->nullable();
            $table->string('agentfeetime')->nullable();
            $table->string('starttime')->nullable();
            $table->string('stoptime')->nullable();
            $table->string('callerpdd')->nullable();
            $table->string('calleepdd')->nullable();
            $table->string('holdtime')->nullable();
            $table->string('callerareacode')->nullable();
            $table->string('feetime')->nullable();
            $table->string('fee')->nullable();
            $table->string('tax')->nullable();
            $table->string('suitefee')->nullable();
            $table->string('suitefeetime')->nullable();
            $table->string('incomefee')->nullable();
            $table->string('incometax')->nullable();
            $table->string('customeraccount')->nullable();
            $table->string('customername')->nullable();
            $table->string('calleeareacode')->nullable();
            $table->string('agentfee')->nullable();
            $table->string('agenttax')->nullable();
            $table->string('agentsuitefee')->nullable();
            $table->string('agentsuitefeetime')->nullable();
            $table->string('agentaccount')->nullable();
            $table->string('agentname')->nullable();
            $table->string('flowno')->nullable();
            $table->string('softswitchname')->nullable();
            $table->string('softswitchcallid')->nullable();
            $table->string('callercallid')->nullable();
            $table->string('calleecallid')->nullable();
            $table->string('rtpforward')->nullable();
            $table->string('enddirection')->nullable();
            $table->string('endreason')->nullable();
            $table->string('billingtype')->nullable();
            $table->string('cdrlevel')->nullable();
            $table->string('agentcdr_id')->nullable();
            $table->string('transactionid')->nullable();
            $table->string('caller_id')->nullable();
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
        Schema::dropIfExists('call_histories');
    }
}
