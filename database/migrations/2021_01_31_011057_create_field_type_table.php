<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fieldType', function (Blueprint $table) {
			$table->bigIncrements('fieldTypeID');
			$table->string('fieldTypeCode');
            $table->string('fieldType');
			$table->integer('sortOrder')->default(0);
			$table->timestamp('dateCreated')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fieldType');
    }
}
