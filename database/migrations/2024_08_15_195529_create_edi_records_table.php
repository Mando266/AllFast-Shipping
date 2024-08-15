<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdiRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edi_records', function (Blueprint $table) {
            $table->id();
            $table->string('container_no');
            $table->string('voyage_number');
            $table->string('imo_number');
            $table->string('ship_name');
            $table->string('country_code')->nullable();
            $table->string('gross_weight')->nullable();
            $table->string('movement_type');
            $table->string('iso_number');
            $table->string('booking_number')->nullable();
            $table->text('goods_description')->nullable();
            $table->timestamp('arrival_date')->nullable();
            $table->timestamp('departure_date')->nullable();
            $table->string('activity_location')->nullable();
            $table->string('pol')->nullable();
            $table->string('pod')->nullable();
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
        Schema::dropIfExists('edi_records');
    }
}
