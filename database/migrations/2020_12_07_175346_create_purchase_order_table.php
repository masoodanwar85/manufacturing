<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseOrder', function (Blueprint $table) {
			$table->bigIncrements('purchaseOrderID');
            $table->unsignedBigInteger('parentID')->nullable();
			$table->unsignedBigInteger('supplierID')->nullable();
			$table->unsignedBigInteger('customerID')->nullable();
			$table->unsignedBigInteger('batchID');
            $table->unsignedBigInteger('lastGodownID')->nullable();
			$table->tinyInteger('isLocked')->default(0);
			$table->date('purchaseOrderDate');
			$table->text('description')->nullable();
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('parentID')->references('purchaseOrderID')->on('purchaseOrder');
            $table->foreign('supplierID')->references('supplierID')->on('supplier');
			$table->foreign('customerID')->references('customerID')->on('customer');
			$table->foreign('batchID')->references('batchID')->on('batch');
            $table->foreign('lastGodownID')->references('godownID')->on('godown');
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
        Schema::dropIfExists('purchaseOrder');
    }
}
