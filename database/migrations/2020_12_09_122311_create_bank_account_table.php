<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankAccount', function (Blueprint $table) {
			$table->bigIncrements('bankAccountID');
			$table->unsignedBigInteger('bankID');
			$table->unsignedBigInteger('headID');
			$table->string('accountTitle',250);
			$table->string('accountNumber',100);
			$table->integer('branchCode');
			$table->string('branchName');
			$table->string('branchLocation');
			$table->text('description')->nullable();
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('bankID')->references('bankID')->on('bank');
			$table->foreign('headID')->references('headID')->on('accountHead');
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
        Schema::dropIfExists('bankAccount');
    }
}
