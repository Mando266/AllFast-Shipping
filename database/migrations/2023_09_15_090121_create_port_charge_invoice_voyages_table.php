<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortChargeInvoiceVoyagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_charge_invoice_voyages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('port_charge_invoice_id');
            $table->unsignedBigInteger('voyages_id');
            $table->timestamps();
        });
        Schema::table('port_charge_invoices', function (Blueprint $table) {
            $table->dropColumn(['vessel_id', 'voyage_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('port_charge_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('vessel_id')->nullable();
            $table->unsignedBigInteger('voyage_id')->nullable();
        });

        Schema::dropIfExists('port_charge_invoice_voyages');
    }
}
