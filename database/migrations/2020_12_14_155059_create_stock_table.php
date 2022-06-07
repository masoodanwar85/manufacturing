<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->bigIncrements('stockID');
            $table->unsignedBigInteger('purchaseOrderID')->nullable();
            $table->unsignedBigInteger('productionBOMID')->nullable();
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('purchaseOrderID')->references('purchaseOrderID')->on('purchaseOrder');
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
        Schema::dropIfExists('stock');
    }
}
