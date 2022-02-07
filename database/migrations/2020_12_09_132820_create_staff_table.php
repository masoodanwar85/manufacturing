<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
			$table->bigIncrements('staffID');
			$table->unsignedBigInteger('staffTypeID');
			$table->unsignedBigInteger('headID');
			$table->string('staffName');
			$table->date('dateJoined');
			$table->unsignedBigInteger('createdByUserID');
            $table->integer('paymentFrequencyID')->default(1);
			$table->decimal('paymentAmount',18,4)->default(0);
            $table->integer('workHoursPerDay')->default(8);
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('staffTypeID')->references('staffTypeID')->on('staffType');
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
        Schema::dropIfExists('staff');
    }
}
