<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accountHead', function (Blueprint $table) {
			$table->bigIncrements('headID');
			$table->unsignedBigInteger('parentHeadID')->nullable();
            $table->unsignedBigInteger('rootHeadID')->nullable();
			$table->string('headName');
			$table->tinyInteger('isSystemGenerated');
			$table->tinyInteger('isEditable');
			$table->tinyInteger('isShowForPurchaseOrderExpense')->default(0);
			$table->tinyInteger('isShowForPayment')->default(0);
			$table->tinyInteger('isShowForReceipt')->default(0);
			$table->tinyInteger('isShowForOpeningBalance')->default(0);
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('parentHeadID')->references('headID')->on('accountHead');
            $table->foreign('rootHeadID')->references('headID')->on('accountHead');
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
        Schema::dropIfExists('accountHead');
    }
}
