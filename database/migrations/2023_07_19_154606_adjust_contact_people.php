<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustContactPeople extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('contact_people');
//        Schema::create('contact_people', function (Blueprint $table) {
//            $table->id();
//            $table->morphs('contactable');
//            $table->string('role')->nullable();
//            $table->string('title')->nullable();
//            $table->string('phone')->nullable();
//            $table->string('email')->nullable();
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
        //
    }
}
