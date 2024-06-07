<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtbndToPortChargeInvoiceRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
            $table->decimal('otbnd', 10, 2)->default(0);
            $table->string('otbnd_currency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
            $table->dropColumn('otbnd');
            $table->dropColumn('otbnd_currency');
        });
    }
}
