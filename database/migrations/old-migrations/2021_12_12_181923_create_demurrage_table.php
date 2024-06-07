<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemurrageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demurrage', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('container_type_id');
            $table->unsignedInteger('port_id');
            $table->date('validity_from')->nullable();
            $table->date('validity_to')->nullable();
            $table->string('currency','255')->nullable();
            $table->unsignedInteger('bound_id')->nullable();
            $table->string('tariff_id','255')->nullable();;
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
        Schema::dropIfExists('demurrage');
    }
}
