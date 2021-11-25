<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseOrderTransaction', function (Blueprint $table) {
			$table->unsignedBigInteger('purchaseOrderID');
            $table->unsignedBigInteger('transactionID');
            $table->unsignedBigInteger('purchaseOrderDetailID')->nullable();
			$table->tinyInteger('isExpense')->default(0);
            $table->primary(['purchaseOrderID','transactionID']);
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('purchaseOrderID')->references('purchaseOrderID')->on('purchaseOrder');
            $table->foreign('transactionID')->references('transactionID')->on('transaction');
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
        Schema::dropIfExists('purchaseOrderTransaction');
    }
}
