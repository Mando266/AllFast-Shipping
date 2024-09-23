<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->unsigned()->nullable()->constrained('containers')->nullOnDelete();
            $table->foreignId('booking_id')->unsigned()->nullable()->constrained('booking')->nullOnDelete();
            $table->foreignId('invoice_id')->unsigned()->constrained('invoice')->cascadeOnDelete();
            $table->string('container_no','255')->nullable();
            $table->string('bl_no','255')->nullable();
            $table->string('container_type','255')->nullable();
            $table->unsignedInteger('container_type_id');
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('from_code','255')->nullable();
            $table->string('to_code','255')->nullable();
            $table->decimal('total', 8, 4, true)->default(0);
            $table->unsignedInteger('daysCount')->define(0);
            $table->unsignedInteger('freeTime')->define(0);
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
        Schema::dropIfExists('invoice_booking');
    }
}