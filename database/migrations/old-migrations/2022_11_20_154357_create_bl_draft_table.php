<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bl_draft', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('company_id')->nullable();  
            $table->string('ref_no','255')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('customer_consignee_details','1000')->nullable();
            $table->string('customer_shipper_details','1000')->nullable();
            $table->string('customer_notifiy_details','1000')->nullable();
            $table->string('descripions','1000')->nullable();
            $table->unsignedInteger('customer_consignee_id')->nullable(); 
            $table->unsignedInteger('customer_notifiy_id')->nullable(); 
            $table->unsignedInteger('place_of_acceptence_id')->nullable();  
            $table->unsignedInteger('place_of_delivery_id')->nullable();     
            $table->unsignedInteger('load_port_id')->nullable();            
            $table->unsignedInteger('discharge_port_id')->nullable();       
            $table->unsignedInteger('equipment_type_id')->nullable(); 
            $table->unsignedInteger('voyage_id')->nullable();       
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
        Schema::dropIfExists('bl_draft');
    }
}
