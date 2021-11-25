<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankInstrumentStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankInstrumentStage', function (Blueprint $table) {
            $table->bigIncrements('bankInstrumentStageID');
			$table->unsignedBigInteger('bankInstrumentDetailID');
            $table->unsignedBigInteger('bankInstrumentStatusID');
			$table->timestamp('dateCreated')->useCurrent();
            $table->foreign('bankInstrumentDetailID')->references('bankInstrumentDetailID')->on('bankInstrumentDetail');
			$table->foreign('bankInstrumentStatusID')->references('bankInstrumentStatusID')->on('bankInstrumentStatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bankInstrumentStage');
    }
}
