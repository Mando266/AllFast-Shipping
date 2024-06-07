<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QuotationsLoad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations_load', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('quotation_id');
            $table->string('charge_type','255')->nullable();
            $table->string('mode','255')->nullable();
            $table->string('currency','255')->nullable();
            $table->string('unit','255')->nullable();
            $table->string('selling_price','255')->nullable();
            $table->string('payer','255')->nullable();
            $table->string('equipment_type_id','255')->nullable();
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
        Schema::dropIfExists('quotations_load');
    }
}
