<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseOrderDetail', function (Blueprint $table) {
			$table->bigIncrements('purchaseOrderDetailID');
			$table->unsignedBigInteger('purchaseOrderID');
			$table->unsignedBigInteger('productID');
			$table->bigInteger('quantity');
			$table->bigInteger('quantityUnits');
			$table->integer('damaged')->default(0);
			$table->decimal('exchangeRate', 8, 2);
			$table->unsignedBigInteger('perUnitPrice');
			$table->foreign('purchaseOrderID')->references('purchaseOrderID')->on('purchaseOrder');
			$table->foreign('productID')->references('productID')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchaseOrderDetail');
    }
}
