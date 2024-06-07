<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVesselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->string('code','255');
            $table->string('name');
            $table->string('call_sign','255')->nullable();
            $table->string('imo_number','255')->nullable();
            $table->BigInteger('production_year')->nullable();
            $table->BigInteger('total_teu')->nullable();
            $table->unsignedInteger('operator_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('vessel_type_id')->nullable();
            $table->BigInteger('mmsi')->nullable();
            $table->BigInteger('gw_no')->nullable();
            $table->BigInteger('dwt_no')->nullable();
            $table->BigInteger('company_id')->nullable();
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
        Schema::dropIfExists('vessels');
    }
}
