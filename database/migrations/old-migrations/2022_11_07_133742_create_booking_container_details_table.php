<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingContainerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_container_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('booking_id');
            $table->string('seal_no','255')->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->string('container_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_container_details');
    }
}