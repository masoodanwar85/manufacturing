<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockDetail', function (Blueprint $table) {
			$table->bigIncrements('stockDetailID');
			$table->unsignedBigInteger('stockID');
			$table->unsignedBigInteger('purchaseOrderDetailID')->nullable();
			$table->unsignedBigInteger('productID');
			$table->unsignedBigInteger('godownID');
			$table->bigInteger('quantity');
			$table->bigInteger('quantityUnits');
			$table->decimal('purchasePrice',18,4);
			$table->timestamp('dateCreated')->useCurrent();
            $table->foreign('stockID')->references('stockID')->on('stock');
			$table->foreign('productID')->references('productID')->on('product');
			$table->foreign('godownID')->references('godownID')->on('godown');
			$table->foreign('purchaseOrderDetailID')->references('purchaseOrderDetailID')->on('purchaseOrderDetail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stockDetail');
    }
}
