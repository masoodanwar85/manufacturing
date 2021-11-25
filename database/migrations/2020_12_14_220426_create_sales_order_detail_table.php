<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesOrderDetail', function (Blueprint $table) {
			$table->bigIncrements('salesOrderDetailID');
			$table->unsignedBigInteger('salesOrderID');
			$table->unsignedBigInteger('stockDetailStatusID');
			$table->foreign('salesOrderID')->references('salesOrderID')->on('salesOrder');
			$table->foreign('stockDetailStatusID')->references('stockDetailStatusID')->on('stockDetailStatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesOrderDetail');
    }
}
