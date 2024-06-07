<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortChargeInvoiceRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('port_charge_invoice_rows');

        Schema::create('port_charge_invoice_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('port_charge_invoice_id');
            $table->unsignedBigInteger('port_charge_id');
            $table->string('service')->nullable();
            $table->string('bl_no');
            $table->string('container_no');
            $table->boolean('is_transhipment')->nullable();
            $table->string('shipment_type')->nullable();
            $table->string('quotation_type')->nullable();
            $table->decimal('thc', 10, 2)->default(0);
            $table->decimal('storage', 10, 2)->default(0);
            $table->decimal('power', 10, 2)->default(0);
            $table->decimal('shifting', 10, 2)->default(0);
            $table->decimal('disinf', 10, 2)->default(0);
            $table->decimal('hand_fes_em', 10, 2)->default(0);
            $table->decimal('gat_lift_off_inbnd_em_ft40', 10, 2)->default(0);
            $table->decimal('gat_lift_on_inbnd_em_ft40', 10, 2)->default(0);
            $table->decimal('pti', 10, 2)->default(0);
            $table->decimal('add_plan', 10, 2)->default(0);
            $table->string('pti_type')->nullable();
            $table->string('storage_days')->nullable();
            $table->string('power_days')->nullable();
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
        Schema::dropIfExists('port_charge_invoice_rows');
    }
}
