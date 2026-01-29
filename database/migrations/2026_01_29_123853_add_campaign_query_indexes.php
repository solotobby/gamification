<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->index(
                ['user_id', 'campaign_id', 'status'],
                'cw_user_campaign_status_idx'
            );
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->index(
                ['status', 'is_completed'],
                'campaigns_status_completed_idx'
            );

            $table->index(
                ['campaign_type'],
                'campaigns_type_idx'
            );

            $table->index(
                ['job_id', 'approved', 'created_at'],
                'campaigns_priority_sort_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->dropIndex('cw_user_campaign_status_idx');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex('campaigns_status_completed_idx');
            $table->dropIndex('campaigns_type_idx');
            $table->dropIndex('campaigns_priority_sort_idx');
        });
    }
};
