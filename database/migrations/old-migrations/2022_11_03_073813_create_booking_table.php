<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('quotation_id')->nullable();            //done
            $table->unsignedInteger('company_id')->nullable();              //done
            $table->string('ref_no','255')->nullable();                     
            $table->unsignedInteger('customer_id')->nullable();             //done
            $table->string('shipper_ref_no','255')->nullable();             //done
            $table->string('forwarder_ref_no','255')->nullable();           //done
            $table->unsignedInteger('voyage_id')->nullable();               //done
            $table->unsignedInteger('voyage_id_second')->nullable();        //done
            $table->unsignedInteger('place_of_acceptence_id')->nullable();  //done
            $table->unsignedInteger('place_of_delivery_id')->nullable();    //done 
            $table->unsignedInteger('load_port_id')->nullable();            //done
            $table->unsignedInteger('discharge_port_id')->nullable();       //done
            $table->unsignedInteger('terminal_id')->nullable();             //done
            $table->unsignedInteger('agent_id')->nullable();                //done
            $table->unsignedInteger('equipment_type_id')->nullable();       //done
            $table->date('discharge_etd')->nullable();                      //done
            $table->date('load_port_cutoff')->nullable();                   //done
            $table->string('oog_dimensions','255')->nullable();             //done
            $table->string('load_port_dayes','255')->nullable();            //done
            $table->string('bl_release','255')->nullable();                 //done
            $table->string('tariff_service','255')->nullable();             //done
            $table->unsignedInteger('booked_by')->nullable();               //done
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
        Schema::dropIfExists('booking');
    }
}
