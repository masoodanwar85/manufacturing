<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productType', function (Blueprint $table) {
            $table->bigIncrements('productTypeID');
			$table->string('productType',250);
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreignId('createdByUserID')->constrained('users','userID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productType');
    }
}
