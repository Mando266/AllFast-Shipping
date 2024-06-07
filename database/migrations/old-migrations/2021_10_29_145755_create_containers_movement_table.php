<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainersmovementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('containers_movement', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->BigInteger('stock_type_id')->nullable();
            $table->BigInteger('container_status_id')->nullable();
            $table->BigInteger('company_id')->nullable();
            $table->boolean('is_on_hire')->nullable();
            $table->boolean('is_off_hire')->nullable();
            $table->boolean('is_snts')->nullable();
            $table->boolean('is_rcvs')->nullable();
            $table->boolean('is_load_full')->nullable();
            $table->boolean('is_dchf')->nullable();
            $table->boolean('is_sntc')->nullable();
            $table->boolean('is_rcvc')->nullable();
            $table->boolean('is_load_empty')->nullable();
            $table->boolean('is_dche')->nullable();
            $table->boolean('is_sntr')->nullable();
            $table->boolean('is_rcve')->nullable();
            $table->boolean('is_lodt')->nullable();
            $table->boolean('is_dcht')->nullable();
            $table->boolean('is_trff')->nullable();
            $table->boolean('is_trfe')->nullable();
            $table->boolean('is_rcvf')->nullable();
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
        Schema::dropIfExists('containers_movement');
    }
}
