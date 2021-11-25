<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesOrderTransaction', function (Blueprint $table) {
			$table->bigIncrements('salesOrderTransactionID');
			$table->unsignedBigInteger('salesOrderID');
			$table->unsignedBigInteger('transactionID');
			$table->unsignedBigInteger('createdByUserID');
			$table->timestamp('dateCreated')->useCurrent();
			$table->foreign('salesOrderID')->references('salesOrderID')->on('salesOrder');
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
        Schema::dropIfExists('salesOrderTransaction');
    }
}
