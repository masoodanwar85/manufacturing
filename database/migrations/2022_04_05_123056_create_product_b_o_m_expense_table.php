<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBOMExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productBOMExpense', function (Blueprint $table) {
			$table->bigIncrements('productBOMExpenseID');
			$table->unsignedBigInteger('productBOMID');
			$table->unsignedBigInteger('expenseHeadID');
			$table->integer('amount');
			$table->timestamp('dateCreated')->useCurrent();
			$table->unsignedBigInteger('createdByUserID');
			$table->foreign('productBOMID')->references('productBOMID')->on('productBOM');
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
        Schema::dropIfExists('productBOMExpense');
    }
}
