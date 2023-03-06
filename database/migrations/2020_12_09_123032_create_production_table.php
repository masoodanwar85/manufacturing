<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production', function (Blueprint $table) {
			$table->bigIncrements('productionID');
            $table->string('serial')->nullable();
			$table->text('description')->nullable();
			$table->tinyInteger('isCompleted')->default(0);
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
        Schema::dropIfExists('productionBOM');
    }
}
