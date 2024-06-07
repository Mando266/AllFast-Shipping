<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('container_type_id');
            $table->string('code');
            $table->string('description')->nullable();
            $table->BigInteger('tar_weight')->nullable();
            $table->BigInteger('max_payload')->nullable();
            $table->BigInteger('container_ownership_id')->nullable();
            $table->BigInteger('production_year')->nullable();
            $table->datetime('last_movement')->nullable();
            $table->text('certificat')->nullable();
            $table->BigInteger('company_id')->nullable();
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
        Schema::dropIfExists('containers');
    }
}
