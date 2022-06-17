<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
			$table->bigIncrements('customerID');
			$table->unsignedBigInteger('headID');
            $table->string('customerName');
			$table->string('shopName')->nullable();
			$table->string('phone')->nullable();
			$table->string('address')->nullable();
            $table->text('description')->nullable();
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
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
        Schema::dropIfExists('customer');
    }
}
