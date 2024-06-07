<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVesselIdToPortChargeInvoiceVoyagesTable extends Migration
{

    public function up()
    {
        Schema::table('port_charge_invoice_voyages', function (Blueprint $table) {
            $table->unsignedBigInteger('vessel_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('port_charge_invoice_voyages', function (Blueprint $table) {
            $table->dropColumn('vessel_id');
        });
    }
}
