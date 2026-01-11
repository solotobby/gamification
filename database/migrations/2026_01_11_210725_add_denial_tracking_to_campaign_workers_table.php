<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDenialTrackingToCampaignWorkersTable extends Migration
{
    public function up()
    {
        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->timestamp('denied_at')->nullable()->after('reason');
            $table->boolean('slot_released')->default(false)->after('denied_at');
        });
    }

    public function down()
    {
        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->dropColumn(['denied_at', 'slot_released']);
        });
    }
}
