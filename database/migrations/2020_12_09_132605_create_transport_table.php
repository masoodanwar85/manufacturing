<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport', function (Blueprint $table) {
			$table->bigIncrements('transportID');
			$table->unsignedBigInteger('headID');
			$table->string('name',250);
			$table->string('owner',100);
			$table->string('vehicleNumber');
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
        Schema::dropIfExists('transport');
    }
}
