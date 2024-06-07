<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPortChargeInvoiceVoyagesTable extends Migration
{
    public function up()
    {
        Schema::table('port_charge_invoice_voyages', function (Blueprint $table) {
            $table->json('empty_costs')->nullable();
            $table->json('full_costs')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('port_charge_invoice_voyages', function (Blueprint $table) {
            $table->dropColumn('empty_costs');
            $table->dropColumn('full_costs');
        });
    }
}
