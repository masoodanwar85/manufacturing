<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route', function (Blueprint $table) {
            $table->bigIncrements('routeID');
            $table->foreignId('areaID')->constrained('area','areaID');
            $table->string('route');
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
        Schema::dropIfExists('route');
    }
};
