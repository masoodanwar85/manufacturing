<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
			$table->bigIncrements('transactionID');
			$table->unsignedBigInteger('transactionTypeID');
			$table->unsignedBigInteger('batchID');
            $table->tinyInteger('isPaymentReceipt')->default(0);
            $table->date('transactionDate');
            $table->string('transactionTypeNumber')->nullable();
			$table->unsignedDecimal('exchangeRate', 8, 2);
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('transactionTypeID')->references('transactionTypeID')->on('transactionType');
			$table->foreign('batchID')->references('batchID')->on('batch');
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
        Schema::dropIfExists('transaction');
    }
}
