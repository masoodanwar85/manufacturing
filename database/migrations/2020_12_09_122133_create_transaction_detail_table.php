<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactionDetail', function (Blueprint $table) {
			$table->bigIncrements('transactionDetailID');
			$table->unsignedBigInteger('transactionID');
			$table->unsignedBigInteger('headID');
			$table->unsignedBigInteger('subHeadID');
			$table->tinyInteger('isDebit');
			$table->decimal('amount',18,4);
			$table->text('description')->nullable();
            $table->foreign('transactionID')->references('transactionID')->on('transaction')->onDelete('cascade');
			$table->foreign('headID')->references('headID')->on('accountHead');
			$table->foreign('subHeadID')->references('headID')->on('accountHead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactionDetail');
    }
}
