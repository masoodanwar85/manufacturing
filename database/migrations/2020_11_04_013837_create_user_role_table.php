<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userRole', function (Blueprint $table) {
            $table->bigInteger('userID')->unsigned();
            $table->bigInteger('roleID')->unsigned();
            $table->primary(['roleID','userID']);
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('userID')->references('userID')->on('users');
            $table->foreign('roleID')->references('roleID')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userRole');
    }
}
