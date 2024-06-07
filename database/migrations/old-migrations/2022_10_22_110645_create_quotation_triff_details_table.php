<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTriffDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_triff_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('quotation_triff_id')->nullable();
            $table->string('charge_type','255')->nullable();
            $table->string('unit','255')->nullable();
            $table->string('selling_price','255')->nullable();
            $table->string('cost','255')->nullable();
            $table->string('agency_revene','255')->nullable();
            $table->string('liner','255')->nullable();
            $table->string('payer','255')->nullable();
            $table->boolean('add_to_quotation')->nullable();
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
        Schema::dropIfExists('quotation_triff_details');
    }
}
