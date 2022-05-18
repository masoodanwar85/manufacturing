<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionBOMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productionBOM', function (Blueprint $table) {
			$table->bigIncrements('productionBOMID');
			$table->unsignedBigInteger('productID');
			$table->integer('quantity')->default(1);
            $table->integer('productionStageID')->default(0);
			$table->unsignedBigInteger('createdByUserID');
			$table->timestamp('dateCreated')->useCurrent();
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
        Schema::dropIfExists('productionBOM');
    }
}
