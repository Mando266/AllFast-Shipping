<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlDraftDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bl_draft_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('bl_id');
            $table->string('container_id')->nullable();
            $table->string('seal_no','255')->nullable();
            $table->unsignedInteger('packs')->nullable();
            $table->string('description','300')->nullable();
            $table->string('gross_weight','255')->nullable();
            $table->string('measurement','255')->nullable();
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
        Schema::dropIfExists('bl_draft_details');
    }
}
