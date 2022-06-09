<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesOrder', function (Blueprint $table) {
			$table->bigIncrements('salesOrderID');
			$table->unsignedBigInteger('customerID');
            $table->foreignId('salesAgentID')->nullable()->constrained('staff','staffID');
			$table->string('invoiceNumber');
            $table->string('bookSerial')->nullable();
			$table->date('orderDate');
			$table->unsignedBigInteger('discount')->default(0);
			$table->unsignedBigInteger('shippingCharges')->default(0);
            $table->date('paymentDueDate')->nullable();
			$table->text('description')->nullable();
			$table->unsignedBigInteger('createdByUserID');
            $table->timestamp('dateCreated')->useCurrent();
			$table->foreign('customerID')->references('customerID')->on('customer');
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
        Schema::dropIfExists('salesOrder');
    }
}
