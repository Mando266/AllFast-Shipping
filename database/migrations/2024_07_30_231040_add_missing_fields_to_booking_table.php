<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking', function (Blueprint $table) {
            // Check and add columns if they do not exist
            if (!Schema::hasColumn('booking', 'bl_kind')) {
                $table->string('bl_kind', 255)->nullable();
            }
            if (!Schema::hasColumn('booking', 'reciver_customer')) {
                $table->string('reciver_customer', 255)->nullable()->after('customer_consignee_id');
            }
            if (!Schema::hasColumn('booking', 'importer_id')) {
                $table->unsignedInteger('importer_id')->nullable()->after('acid');
            }
            if (!Schema::hasColumn('booking', 'exportal_id')) {
                $table->unsignedInteger('exportal_id')->nullable()->after('importer_id');
            }
            if (!Schema::hasColumn('booking', 'payment_kind')) {
                $table->string('payment_kind', 255)->nullable()->after('exportal_id');
            }
            if (!Schema::hasColumn('booking', 'free_time')) {
                $table->unsignedInteger('free_time')->nullable()->after('payment_kind');
            }
            if (!Schema::hasColumn('booking', 'bl_kind')) {
                $table->string('bl_kind', 255)->nullable()->after('free_time');
            }
            if (!Schema::hasColumn('booking', 'commodity_code')) {
                $table->string('commodity_code', 255)->nullable()->after('bl_kind');
            }
            if (!Schema::hasColumn('booking', 'commodity_description')) {
                $table->text('commodity_description')->nullable()->after('commodity_code');
            }
            if (!Schema::hasColumn('booking', 'notes')) {
                $table->text('notes')->nullable()->after('commodity_description');
            }
            if (!Schema::hasColumn('booking', 'booking_confirm')) {
                $table->boolean('booking_confirm')->default(0)->after('notes');
            }
            if (!Schema::hasColumn('booking', 'principal_name')) {
                $table->string('principal_name', 255)->nullable()->after('booking_confirm');
            }
            if (!Schema::hasColumn('booking', 'vessel_name')) {
                $table->string('vessel_name', 255)->nullable()->after('principal_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('booking', 'bl_kind')) {
                $table->dropColumn('bl_kind');
            }
            if (Schema::hasColumn('booking', 'reciver_customer')) {
                $table->dropColumn('reciver_customer');
            }
            if (Schema::hasColumn('booking', 'importer_id')) {
                $table->dropColumn('importer_id');
            }
            if (Schema::hasColumn('booking', 'exportal_id')) {
                $table->dropColumn('exportal_id');
            }
            if (Schema::hasColumn('booking', 'payment_kind')) {
                $table->dropColumn('payment_kind');
            }
            if (Schema::hasColumn('booking', 'free_time')) {
                $table->dropColumn('free_time');
            }
            if (Schema::hasColumn('booking', 'bl_kind')) {
                $table->dropColumn('bl_kind');
            }
            if (Schema::hasColumn('booking', 'commodity_code')) {
                $table->dropColumn('commodity_code');
            }
            if (Schema::hasColumn('booking', 'commodity_description')) {
                $table->dropColumn('commodity_description');
            }
            if (Schema::hasColumn('booking', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('booking', 'booking_confirm')) {
                $table->dropColumn('booking_confirm');
            }
            if (Schema::hasColumn('booking', 'principal_name')) {
                $table->dropColumn('principal_name');
            }
            if (Schema::hasColumn('booking', 'vessel_name')) {
                $table->dropColumn('vessel_name');
            }
        });
    }
}
