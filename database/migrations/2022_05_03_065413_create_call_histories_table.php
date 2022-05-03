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
            $table->string('caller_id');
            $table->string('callere164');
            $table->string('calleraccesse164');
            $table->string('calleee164');
            $table->string('calleeaccesse164');
            $table->string('callerip');
            $table->string('callergatewayh323id');
            $table->string('callerproductid');
            $table->string('callertogatewaye164');
            $table->string('calleeip');
            $table->string('calleegatewayh323id');
            $table->string('calleeproductid');
            $table->string('calleetogatewaye164');
            $table->string('billingmode');
            $table->string('calllevel');
            $table->string('agentfeetime');
            $table->string('starttime');
            $table->string('stoptime');
            $table->string('pdd');
            $table->string('holdtime');
            $table->string('feeprefix');
            $table->string('feetime');
            $table->string('fee');
            $table->string('suitefee');
            $table->string('suitefeetime');
            $table->string('incomefee');
            $table->string('customername');
            $table->string('agentfeeprefix');
            $table->string('agentfee');
            $table->string('agentsuitefee');
            $table->string('agentsuitefeetime');
            $table->string('agentaccount');
            $table->string('agentname');
            $table->string('flowno');
            $table->string('softswitchdn');
            $table->string('enddirection');
            $table->string('endreason');
            $table->string('calleebilling');
            $table->string('cdrlevel');
            $table->string('subcdr_id');
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
