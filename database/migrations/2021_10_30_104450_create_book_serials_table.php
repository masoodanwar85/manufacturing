<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookSerials', function (Blueprint $table) {
            $table->bigIncrements('bookSerialID');
            $table->unsignedBigInteger('invoiceBookID');
			$table->integer('serialNumber');
			$table->text('reason');
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('invoiceBookID')->references('invoiceBookID')->on('invoiceBooks');
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
        Schema::dropIfExists('bookSerials');
    }
}
