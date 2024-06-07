<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestXmlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifest_xml', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();   
            $table->string('ref_no','255')->nullable();
            $table->unsignedInteger('voyage_id')->nullable();
            $table->unsignedInteger('port_id')->nullable();
            $table->unsignedInteger('is_load_port')->nullable();
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
        Schema::dropIfExists('manifest_xml');
    }
}
