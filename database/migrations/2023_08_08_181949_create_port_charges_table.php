<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('port_charges');
//        Schema::create('port_charges', function (Blueprint $table) {
//            $table->id();
//            $table->bigInteger('charge_matrix_id');
//            $table->decimal('thc_20ft', 10, 2)->nullable();
//            $table->decimal('thc_40ft', 10, 2)->nullable();
//            $table->decimal('storage_20ft', 10, 2)->nullable();
//            $table->decimal('storage_40ft_first_5', 10, 2)->nullable();
//            $table->decimal('storage_40ft_after_5', 10, 2)->nullable();
//            $table->decimal('power_20ft', 10, 2)->nullable();
//            $table->decimal('power_40ft', 10, 2)->nullable();
//            $table->decimal('shifting_20ft', 10, 2)->nullable();
//            $table->decimal('shifting_40ft', 10, 2)->nullable();
//            $table->decimal('disinf_20ft', 10, 2)->nullable();
//            $table->decimal('disinf_40ft', 10, 2)->nullable();
//            $table->decimal('hand_fes_em_20ft', 10, 2)->nullable();
//            $table->decimal('hand_fes_em_40ft', 10, 2)->nullable();
//            $table->decimal('gat_lift_off_inbnd_em_ft40_20ft', 10, 2)->nullable();
//            $table->decimal('gat_lift_off_inbnd_em_ft40_40ft', 10, 2)->nullable();
//            $table->decimal('gat_lift_on_inbnd_em_ft40_20ft', 10, 2)->nullable();
//            $table->decimal('gat_lift_on_inbnd_em_ft40_40ft', 10, 2)->nullable();
//            $table->decimal('pti_20ft', 10, 2)->nullable();
//            $table->decimal('pti_40ft_failed', 10, 2)->nullable();
//            $table->decimal('pti_40ft_pass', 10, 2)->nullable();
//            $table->decimal('wire_trnshp_20ft', 10, 2)->nullable();
//            $table->decimal('wire_trnshp_40ft', 10, 2)->nullable();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('port_charges');
    }
}
