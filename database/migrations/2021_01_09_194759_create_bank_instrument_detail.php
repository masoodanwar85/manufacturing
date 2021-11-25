<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankInstrumentDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankInstrumentDetail', function (Blueprint $table) {
            $table->bigIncrements('bankInstrumentDetailID');
			$table->unsignedBigInteger('transactionDetailID');
            $table->unsignedBigInteger('bankInstrumentTypeID');
            $table->unsignedBigInteger('bankID');
            $table->unsignedBigInteger('bankAccountID')->nullable();
			$table->string('instrumentNumber', 145);
			$table->date('instrumentDate');
			$table->unsignedBigInteger('instrumentAmount')->default(0);
			$table->text('description')->nullable();
			$table->foreign('transactionDetailID')->references('transactionDetailID')->on('transactionDetail');
			$table->foreign('bankInstrumentTypeID')->references('bankInstrumentTypeID')->on('bankInstrumentType');
            $table->foreign('bankID')->references('bankID')->on('bank');
            $table->foreign('bankAccountID')->references('bankAccountID')->on('bankAccount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bankInstrumentDetail');
    }
}
