<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaveType', function (Blueprint $table) {
            $table->bigIncrements('leaveTypeID');
			$table->string('leaveType');
            $table->tinyInteger('isPaid')->default(1);
			$table->unsignedBigInteger('createdByUserID');
			$table->timestamp('dateCreated')->useCurrent();
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
        Schema::dropIfExists('leaveType');
    }
}
