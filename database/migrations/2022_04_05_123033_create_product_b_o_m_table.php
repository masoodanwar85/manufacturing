<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBOMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productBOM', function (Blueprint $table) {
			$table->bigIncrements('productBOMID');
			$table->unsignedBigInteger('productID');
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
        Schema::dropIfExists('productBOM');
    }
}
