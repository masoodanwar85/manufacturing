<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rolePrivilege', function (Blueprint $table) {
            $table->bigInteger('roleID')->unsigned();
            $table->bigInteger('privilegeID')->unsigned();
			$table->primary(['roleID','privilegeID']);
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('roleID')->references('roleID')->on('roles');
            $table->foreign('privilegeID')->references('privilegeID')->on('privilege');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rolePrivilege');
    }
}
