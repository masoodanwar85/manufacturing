<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBOMItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productBOMItem', function (Blueprint $table) {
			$table->bigIncrements('productBOMItemID');
			$table->unsignedBigInteger('productBOMID');
			$table->unsignedBigInteger('productID');
			$table->integer('quantity')->default(1);
			$table->tinyInteger('isConsumeable')->default(1);
			$table->timestamp('dateCreated')->useCurrent();
			$table->unsignedBigInteger('createdByUserID');
			$table->foreign('productBOMID')->references('productBOMID')->on('productBOM');
			$table->foreign('productID')->references('productID')->on('product');
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
        Schema::dropIfExists('productBOMItem');
    }
}
