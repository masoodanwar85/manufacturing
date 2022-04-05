<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionBOMItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productionBOMItem', function (Blueprint $table) {
			$table->bigIncrements('productionBOMItemID');
			$table->unsignedBigInteger('productionBOMID');
			$table->unsignedBigInteger('productID');
			$table->integer('quantity')->default(1);
			$table->decimal('consumed',18,4)->default(1);
			$table->timestamp('dateCreated')->useCurrent();
			$table->unsignedBigInteger('createdByUserID');
			$table->foreign('productionBOMID')->references('productionBOMID')->on('productionBOM');
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
        Schema::dropIfExists('productionBOMItem');
    }
}
