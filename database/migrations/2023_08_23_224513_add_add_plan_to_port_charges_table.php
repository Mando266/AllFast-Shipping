<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddPlanToPortChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('port_charges', function (Blueprint $table) {
            $table->dropColumn('wire_trnshp_20ft');
            $table->dropColumn('wire_trnshp_40ft');
            $table->decimal('power_free', 10, 2)->nullable();
            $table->decimal('add_plan_20ft', 10, 2)->nullable();
            $table->decimal('add_plan_40ft', 10, 2)->nullable();
            $table->integer('company_id')->nullable();

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
            $table->decimal('wire_trnshp_20ft', 10, 2)->nullable();
            $table->decimal('wire_trnshp_40ft', 10, 2)->nullable();
            $table->dropColumn('power_free');
            $table->dropColumn('add_plan_20ft');
            $table->dropColumn('add_plan_40ft');
        });
    }
}
