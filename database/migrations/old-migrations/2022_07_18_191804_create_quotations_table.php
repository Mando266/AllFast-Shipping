<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->string('ref_no','255')->nullable();
            $table->date('validity_from')->nullable();
            $table->date('validity_to')->nullable();
            $table->boolean('rate_sh')->nullable();
            $table->boolean('rate_cn')->nullable();
            $table->boolean('rate_nt')->nullable();
            $table->boolean('rate_fwd')->nullable();
            $table->unsignedInteger('agent_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('place_of_acceptence_id')->nullable();
            $table->unsignedInteger('place_of_delivery_id')->nullable();
            $table->unsignedInteger('load_port_id')->nullable();
            $table->unsignedInteger('discharge_port_id')->nullable();
            $table->unsignedInteger('equipment_type_id')->nullable();
            $table->unsignedInteger('export_detention')->nullable();
            $table->unsignedInteger('import_detention')->nullable();
            $table->boolean('status')->nullable();
            $table->string('oog_dimensions','255')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
