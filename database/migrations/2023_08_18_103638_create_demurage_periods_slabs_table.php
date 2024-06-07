<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemuragePeriodsSlabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demurage_periods_slabs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('demurage_id')->nullable();
            $table->unsignedInteger('container_type_id')->nullable();
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
        Schema::dropIfExists('demurage_periods_slabs');
    }
}
