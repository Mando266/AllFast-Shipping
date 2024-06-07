<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_price', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->string('ref_rate','255')->nullable();
            $table->unsignedInteger('pol_id')->nullable();
            $table->unsignedInteger('pod_id')->nullable();
            $table->unsignedInteger('equipment_type_id')->nullable();
            $table->date('validity_from')->nullable();
            $table->date('validity_to')->nullable();
            $table->string('term_of_shipment','255')->nullable();
            $table->unsignedInteger('slot_rate_mty')->nullable();
            $table->unsignedInteger('slot_rate_leden')->nullable();
            $table->unsignedInteger('baf_for_mty')->nullable();
            $table->unsignedInteger('baf_for_leden')->nullable();
            $table->unsignedInteger('ewri_mty')->nullable();
            $table->unsignedInteger('ewri_leden')->nullable();
            $table->unsignedInteger('imco_sc')->nullable();
            $table->unsignedInteger('oog_char')->nullable();
            $table->unsignedInteger('flexi_sc')->nullable();
            $table->string('remarkes','255')->nullable();
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
        Schema::dropIfExists('supplier_price');
    }
}
