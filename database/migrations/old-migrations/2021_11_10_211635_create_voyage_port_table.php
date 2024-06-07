<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoyagePortTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voyage_port', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('voyage_id');
            $table->string('port_from_name','255')->nullable();
            $table->string('terminal_name','255')->nullable();
            $table->unsignedInteger('road_no')->nullable();
            $table->date('eta')->nullable();
            $table->date('etd')->nullable();
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
        Schema::dropIfExists('voyage_port');
    }
}
