<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustSuppliersTable extends Migration
{
    public function up()
    {
//        Schema::dropIfExists('suppliers');
//        Schema::create('suppliers', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->string('city')->nullable();
//            $table->string('address_line_1');
//            $table->string('phone');
//            $table->string('email');
//            $table->string('tax_card')->nullable();
//            $table->bigInteger('currency_id')->nullable();
//            $table->bigInteger('secondary_currency_id')->nullable();
//            $table->text('note')->nullable();
//            $table->string('is_container_depot')->nullable();
//            $table->boolean('is_container_services_provider')->nullable();
//            $table->boolean('is_container_seller')->nullable();
//            $table->boolean('is_container_trucker')->nullable();
//            $table->boolean('is_container_lessor')->nullable();
//            $table->boolean('is_container_haulage')->nullable();
//            $table->boolean('is_container_terminal')->nullable();
//            $table->unsignedInteger('country_id')->nullable();
//            $table->BigInteger('company_id')->nullable();
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
    }
}
