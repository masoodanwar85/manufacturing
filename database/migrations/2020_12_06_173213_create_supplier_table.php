<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
			$table->bigIncrements('supplierID');
			$table->unsignedBigInteger('headID');
            $table->string('supplierName')->unique();
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
        Schema::dropIfExists('supplier');
    }
}
