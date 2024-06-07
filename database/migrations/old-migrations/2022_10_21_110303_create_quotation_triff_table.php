<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTriffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_triff', function (Blueprint $table) {
            $table->id();
            $table->string('trif_no','255')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('equipment_type_id')->nullable();
            $table->unsignedInteger('port_id')->nullable();
            $table->unsignedInteger('terminal_id')->nullable();
            $table->date('validity_from')->nullable();
            $table->date('validity_to')->nullable();
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
        Schema::dropIfExists('quotation_triff');
    }
}
