<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('charges_matrices', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->boolean('empty');
//            $table->boolean('full');
//            $table->boolean('import');
//            $table->boolean('export');
//            $table->boolean('ts');
//            $table->string('payer');
//            $table->string('currency');
//            $table->integer('storage_free')->default(0);
//            $table->string('storage_from');
//            $table->string('storage_to');
//            $table->integer('power_free')->default(0);
//            $table->string('power_from');
//            $table->string('power_to');
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
        Schema::dropIfExists('charges_matrices');
    }
}
