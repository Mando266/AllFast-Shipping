
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('container_id');
            $table->unsignedInteger('container_type_id');
            $table->unsignedInteger('movement_id')->nullable();
            $table->date('movement_date')->nullable();
            $table->string('port_location_id','255')->nullable();
            $table->string('pol_id','255')->nullable();
            $table->string('pod_id','255')->nullable();
            $table->string('vessel_id','255')->nullable();
            $table->string('voyage_id','255')->nullable();
            $table->string('terminal_id','255')->nullable();
            $table->string('booking_no','255')->nullable();
            $table->string('bl_no','255')->nullable();
            $table->string('remarkes')->nullable();
            $table->string('transshipment_port_id','255')->nullable();
            $table->string('booking_agent_id','255')->nullable();
            $table->unsignedInteger('free_time')->nullable();
            $table->string('container_status','255')->nullable();
            $table->string('import_agent','255')->nullable();
            $table->unsignedInteger('free_time_origin')->nullable();
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
        Schema::dropIfExists('movements');
    }
}
