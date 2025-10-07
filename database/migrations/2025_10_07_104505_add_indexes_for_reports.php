<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // USERS table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('is_verified');
            $table->index('created_at');
        });

        // CAMPAIGNS table indexes
        Schema::table('campaigns', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });

        // CAMPAIGN_WORKERS table indexes
        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });

        // LOGIN_POINTS table indexes
        if (Schema::hasTable('login_points')) {
            Schema::table('login_points', function (Blueprint $table) {
                $table->index('is_redeemed');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_verified']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        if (Schema::hasTable('login_points')) {
            Schema::table('login_points', function (Blueprint $table) {
                $table->dropIndex(['is_redeemed']);
                $table->dropIndex(['created_at']);
            });
        }
    }
};
