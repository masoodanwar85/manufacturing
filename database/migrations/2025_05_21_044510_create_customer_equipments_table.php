<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customerEquipments', function (Blueprint $table) {
            $table->bigIncrements('customerEquipmentID');
            $table->unsignedBigInteger('customerID');
            $table->string('equipmentType');
            $table->string('equipmentSerial')->nullable();
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
        Schema::dropIfExists('customerEquipments');
    }
}
