<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTariffTypeIdToDemurrageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('demurrage', function (Blueprint $table) {
//            $table->bigInteger('tariff_type_id')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demurrage', function (Blueprint $table) {
            $table->dropColumn('tariff_type_id');
        });
    }
}
