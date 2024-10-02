<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustPortChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('port_charges', function (Blueprint $table) {
            $table->dropColumn('storage_20ft');
            $table->dropColumn('storage_40ft_first_5');
            $table->dropColumn('storage_40ft_after_5');
            $table->dropColumn('pti_20ft');
            $table->dropColumn('pti_40ft_failed');
            $table->dropColumn('pti_40ft_pass');
            $table->decimal('storage_free', 10, 2)->nullable();
            $table->decimal('storage_slab1_period', 10, 2)->nullable();
            $table->decimal('storage_slab1_20ft', 10, 2)->nullable();
            $table->decimal('storage_slab1_40ft', 10, 2)->nullable();
            $table->decimal('storage_slab2_period', 10, 2)->nullable();
            $table->decimal('storage_slab2_20ft', 10, 2)->nullable();
            $table->decimal('storage_slab2_40ft', 10, 2)->nullable();
            $table->decimal('pti_failed', 10, 2)->nullable();
            $table->decimal('pti_passed', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('port_charges', function (Blueprint $table) {
            $table->dropColumn('storage_free');
            $table->dropColumn('storage_slab1_period');
            $table->dropColumn('storage_slab1_20ft');
            $table->dropColumn('storage_slab1_40ft');
            $table->dropColumn('storage_slab2_period');
            $table->dropColumn('storage_slab2_20ft');
            $table->dropColumn('storage_slab2_40ft');
            $table->dropColumn('pti_failed');
            $table->dropColumn('pti_passed');
            $table->decimal('pti_20ft', 10, 2)->nullable();
            $table->decimal('pti_40ft_failed', 10, 2)->nullable();
            $table->decimal('pti_40ft_pass', 10, 2)->nullable();
            $table->decimal('storage_20ft', 10, 2)->nullable();
            $table->decimal('storage_40ft_first_5', 10, 2)->nullable();
            $table->decimal('storage_40ft_after_5', 10, 2)->nullable();
        });
    }
}