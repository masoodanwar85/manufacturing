<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoiceBooks', function (Blueprint $table) {
            $table->bigIncrements('invoiceBookID');
            $table->enum('bookType', ['RB', 'BB', 'CB', 'TB', 'MB', 'SR']);
            $table->integer('bookNumber');
            $table->integer('startPage');
            $table->integer('endPage');
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
        Schema::dropIfExists('invoiceBooks');
    }
}
