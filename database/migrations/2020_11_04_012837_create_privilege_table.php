<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilege', function (Blueprint $table) {
            $table->bigIncrements('privilegeID');
            $table->bigInteger('moduleID')->unsigned();
			$table->bigInteger('accessLevelID')->unsigned();
            $table->string('privilegeCode');
            $table->string('privilegeName');
            $table->foreign('moduleID')->references('moduleID')->on('modules');
			$table->foreign('accessLevelID')->references('accessLevelID')->on('accessLevel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilege');
    }
}
