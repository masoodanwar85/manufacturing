<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockDetailStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockDetailStatus', function (Blueprint $table) {
			$table->bigIncrements('stockDetailStatusID');
			$table->unsignedBigInteger('stockDetailID');
            $table->foreignId('productionID')->nullable()->constrained('production','productionID');
			$table->unsignedInteger('statusID');
			$table->unsignedBigInteger('batchID');
            $table->unsignedBigInteger('godownID');
            $table->string('bookSerial')->nullable();
			$table->bigInteger('quantity');
            $table->bigInteger('discount')->default(0);
			$table->integer('quantityUnits');
			$table->decimal('salePrice',18,4)->nullable();
            $table->date('transferDate');
            $table->unsignedBigInteger('productionBOMItemID')->nullable();
			$table->unsignedBigInteger('createdByUserID');
			$table->timestamp('dateCreated')->useCurrent();
            $table->foreign('stockDetailID')->references('stockDetailID')->on('stockDetail');
			$table->foreign('statusID')->references('statusID')->on('stockStatus');
			$table->foreign('batchID')->references('batchID')->on('batch');
            $table->foreign('productionBOMItemID')->references('productionBOMItemID')->on('productionBOMItem');
            $table->foreign('godownID')->references('godownID')->on('godown');
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
        Schema::dropIfExists('stockDetailStatus');
    }
}
