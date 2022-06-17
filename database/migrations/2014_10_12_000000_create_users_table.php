<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('userID');
            $table->unsignedBigInteger('userTypeID');
			$table->unsignedBigInteger('clientID');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->timestamp('lastLogin')->nullable();
            $table->integer('statusID')->unsigned()->default(1);
            $table->ipAddress('lastLoginIP')->nullable();
            $table->timestamp('emailVerifiedAt')->nullable();
            $table->string('password');
            $table->string('rememberToken')->nullable();
            $table->timestamp('dateCreated')->useCurrent();
            $table->foreign('userTypeID')->references('userTypeID')->on('userType');
            $table->foreign('clientID')->references('clientID')->on('client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
