<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveryDetails', function (Blueprint $table) {
            $table->bigIncrements('deliveryDetailID');
            $table->foreignId('deliveryID')->nullable()->constrained('delivery','deliveryID');
            $table->foreignId('salesOrderID')->nullable()->constrained('salesOrder','salesOrderID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_details');
    }
}
