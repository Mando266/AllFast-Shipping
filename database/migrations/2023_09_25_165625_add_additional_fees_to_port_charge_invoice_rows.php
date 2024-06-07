<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFeesToPortChargeInvoiceRows extends Migration
{
    public function up()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
            $table->decimal('additional_fees', 10, 2)->nullable();
            $table->string('additional_fees_description')->nullable();
        });
    }

    public function down()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
            $table->dropColumn('additional_fees');
            $table->dropColumn('additional_fees_description');
        });
    }
}
