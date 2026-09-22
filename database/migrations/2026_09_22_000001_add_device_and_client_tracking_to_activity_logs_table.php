<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceAndClientTrackingToActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'client_type')) {
                $table->string('client_type', 50)->nullable()->after('user_type')->index();
            }
            if (!Schema::hasColumn('activity_logs', 'device')) {
                $table->string('device', 50)->nullable()->after('client_type')->index();
            }
            if (!Schema::hasColumn('activity_logs', 'platform')) {
                $table->string('platform', 100)->nullable()->after('device');
            }
            if (!Schema::hasColumn('activity_logs', 'browser')) {
                $table->string('browser', 100)->nullable()->after('platform');
            }
            if (!Schema::hasColumn('activity_logs', 'device_model')) {
                $table->string('device_model', 150)->nullable()->after('browser');
            }
            if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('device_model')->index();
            }
            if (!Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('activity_logs', 'action')) {
                $table->string('action', 255)->nullable()->after('user_agent');
            }
            if (!Schema::hasColumn('activity_logs', 'properties')) {
                $table->json('properties')->nullable()->after('action');
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
        Schema::table('activity_logs', function (Blueprint $table) {
            $columns = [
                'client_type',
                'device',
                'platform',
                'browser',
                'device_model',
                'ip_address',
                'user_agent',
                'action',
                'properties',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('activity_logs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
