<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionBOMExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productionBOMExpense', function (Blueprint $table) {
			$table->bigIncrements('productionBOMExpenseID');
			$table->unsignedBigInteger('productionBOMID');
			$table->unsignedBigInteger('expenseHeadID');
			$table->integer('amount');
			$table->timestamp('dateCreated')->useCurrent();
			$table->unsignedBigInteger('createdByUserID');
			$table->foreign('productionBOMID')->references('productionBOMID')->on('productionBOM');
			$table->foreign('expenseHeadID')->references('headID')->on('accountHead');
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
        Schema::dropIfExists('productionBOMExpense');
    }
}
