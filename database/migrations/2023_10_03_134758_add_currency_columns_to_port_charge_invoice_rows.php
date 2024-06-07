<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyColumnsToPortChargeInvoiceRows extends Migration
{
    public function up()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
            $table->string('thc_currency')->nullable();
            $table->string('storage_currency')->nullable();
            $table->string('power_currency')->nullable();
            $table->string('shifting_currency')->nullable();
            $table->string('disinf_currency')->nullable();
            $table->string('hand_fes_em_currency')->nullable();
            $table->string('gat_lift_off_inbnd_em_ft40_currency')->nullable();
            $table->string('gat_lift_on_inbnd_em_ft40_currency')->nullable();
            $table->string('pti_currency')->nullable();
            $table->string('add_plan_currency')->nullable();
            $table->string('additional_fees_currency')->nullable();
        });
        Schema::table('port_charge_invoices', function (Blueprint $table) {
            $table->string('all_egp')->nullable();
        });
    }

    public function down()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
            $table->dropColumn([
                'thc_currency',
                'storage_currency',
                'power_currency',
                'shifting_currency',
                'disinf_currency',
                'hand_fes_em_currency',
                'gat_lift_off_inbnd_em_ft40_currency',
                'gat_lift_on_inbnd_em_ft40_currency',
                'pti_currency',
                'add_plan_currency',
                'additional_fees_currency',
            ]);
        });
        Schema::table('port_charge_invoices', function (Blueprint $table) {
            $table->dropColumn('all_egp');
        });
    }
}
