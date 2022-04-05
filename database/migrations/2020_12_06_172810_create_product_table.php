<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
			$table->bigIncrements('productID');
            $table->unsignedBigInteger('categoryID');
            $table->unsignedBigInteger('minimumUnitID');
			$table->unsignedBigInteger('maximumUnitID');
            $table->string('productName');
            $table->integer('unitsInProduct')->unsigned()->default(1);
			$table->tinyInteger('isUnitsInProductFixed')->default(1);
            $table->string('image')->nullable();
            $table->integer('thresholdUnit')->unsigned()->default(0);
			$table->tinyInteger('isSoldPackOrLoose')->default(1);
			$table->tinyInteger('isBOM')->default(0);
			$table->decimal('unitPurchasePrice',18,4);
            $table->decimal('unitSalePrice',18,4);
            $table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('minimumUnitID')->references('unitID')->on('measurementUnit');
			$table->foreign('maximumUnitID')->references('unitID')->on('measurementUnit');
            $table->foreign('categoryID')->references('categoryID')->on('category');
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
        Schema::dropIfExists('product');
    }
}
