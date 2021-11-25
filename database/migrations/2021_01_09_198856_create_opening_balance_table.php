<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpeningBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('openingBalance', function (Blueprint $table) {
            $table->bigIncrements('openingBalanceID');
			$table->unsignedBigInteger('batchID');
            $table->unsignedBigInteger('transactionID');
			$table->timestamp('dateCreated')->useCurrent();
			$table->unsignedBigInteger('createdByUserID');
            $table->foreign('batchID')->references('batchID')->on('batch');
			$table->foreign('transactionID')->references('transactionID')->on('transaction');
			$table->foreign('createdByUserID')->references('userID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('openingBalance');
    }
}
