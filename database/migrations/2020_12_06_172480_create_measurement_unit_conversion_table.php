<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementUnitConversionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('measurementUnitConversion', function (Blueprint $table) {
            $table->bigIncrements('conversionID');
			$table->unsignedBigInteger('fromUnitID');
            $table->unsignedBigInteger('toUnitID');
            $table->string('multiplyBy');
            $table->foreign('fromUnitID')->references('unitID')->on('measurementUnit');
            $table->foreign('toUnitID')->references('unitID')->on('measurementUnit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurementUnitConversion');
    }
}
