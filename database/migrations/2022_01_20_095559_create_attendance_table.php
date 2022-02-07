<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->bigIncrements('attendanceID');
            $table->unsignedBigInteger('leaveTypeID');
            $table->unsignedBigInteger('staffID');
            $table->date('attendanceDate');
            $table->integer('hours')->default(8);
            $table->text('description')->nullable();
            $table->timestamp('dateCreated')->useCurrent();
            $table->unsignedBigInteger('createdByUserID');
            $table->foreign('leaveTypeID')->references('leaveTypeID')->on('leaveType');
            $table->foreign('staffID')->references('staffID')->on('staff');
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
        Schema::dropIfExists('attendance');
    }
}
