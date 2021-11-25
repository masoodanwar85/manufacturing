<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
			$table->bigIncrements('settingID');
			$table->unsignedBigInteger('settingTypeID')->nullable();
            $table->unsignedBigInteger('fieldTypeID');
			$table->string('settingName');
			$table->string('settingCode');
			$table->string('label');
			$table->string('tab');
			$table->string('group');
			$table->integer('sortOrder');
			$table->text('defaultValue')->nullable();
			$table->timestamp('dateCreated')->useCurrent();
			$table->foreign('settingTypeID')->references('settingTypeID')->on('settingType');
			$table->foreign('fieldTypeID')->references('fieldTypeID')->on('fieldType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
