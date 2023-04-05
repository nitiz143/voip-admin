<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('destination');
            $table->string('destination_code');
            $table->date('effective_date');
            $table->string('rate')->comment('US($)');
            $table->string('billing_increment');
            $table->date('deletion_date')->nullable();
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
        Schema::dropIfExists('rate_uploads');
    }
}
