<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtbndToPortCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('port_charges', function (Blueprint $table) {
            $table->decimal('otbnd_20ft', 10, 2)->nullable();
            $table->decimal('otbnd_40ft', 10, 2)->nullable();
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
            $table->dropColumn('otbnd_20ft');
            $table->dropColumn('otbnd_40ft');
        });
    }
}
