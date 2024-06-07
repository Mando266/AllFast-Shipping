<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalNotifyToBlDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bl_draft', function (Blueprint $table) {
            $table->unsignedInteger('additional_notify_id')->nullable(); 
            $table->string('additional_notify_details','1000')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bl_draft', function (Blueprint $table) {
            $table->dropColumn('additional_notify_id');
            $table->dropColumn('additional_notify_details');
        });
    }
}
