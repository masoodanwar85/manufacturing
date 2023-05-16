<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->bigIncrements('deliveryID');
            $table->date('deliveryDate')->nullable();
            $table->foreignId('routeID')->nullable()->constrained('route','routeID');
            $table->foreignId('godownID')->nullable()->constrained('godown','godownID');
            $table->foreignId('transportID')->nullable()->constrained('transport','transportID');
            $table->unsignedBigInteger('createdByUserID');
            $table->unsignedBigInteger('updatedByUserID');
            $table->timestamp('dateCreated')->useCurrent();
            $table->timestamp('dateUpdated')->useCurrent();
            $table->foreign('createdByUserID')->references('userID')->on('users');
            $table->foreign('updatedByUserID')->references('userID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
