<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ActivityLog table indexes
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['created_at', 'activity_type'], 'idx_activity_created_type');
            $table->index('activity_type', 'idx_activity_type');
        });

        // Statistics table indexes
        Schema::table('statistics', function (Blueprint $table) {
            $table->index(['created_at', 'type'], 'idx_stats_created_type');
            $table->index(['date', 'type'], 'idx_stats_date_type');
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('created_at', 'idx_users_created');
            $table->index(['created_at', 'is_verified'], 'idx_users_created_verified');
            $table->index(['created_at', 'source'], 'idx_users_created_source');
            $table->index(['is_verified', 'created_at', 'source'], 'idx_users_verified_created_source');
            $table->index('source', 'idx_users_source');
            $table->index('country', 'idx_users_country');
            $table->index('age_range', 'idx_users_age_range');
        });

        // PaymentTransaction table indexes
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'tx_type', 'type'], 'idx_payment_user_tx_type');
            $table->index(['user_id', 'created_at'], 'idx_payment_user_created');
            $table->index(['tx_type', 'type', 'created_at'], 'idx_payment_tx_type_created');
            $table->index('type', 'idx_payment_type');
        });

        // Wallets table indexes
        Schema::table('wallets', function (Blueprint $table) {
            $table->index('base_currency', 'idx_wallets_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_activity_created_type');
            $table->dropIndex('idx_activity_type');
        });

        Schema::table('statistics', function (Blueprint $table) {
            $table->dropIndex('idx_stats_created_type');
            $table->dropIndex('idx_stats_date_type');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_created');
            $table->dropIndex('idx_users_created_verified');
            $table->dropIndex('idx_users_created_source');
            $table->dropIndex('idx_users_verified_created_source');
            $table->dropIndex('idx_users_source');
            $table->dropIndex('idx_users_country');
            $table->dropIndex('idx_users_age_range');
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_payment_user_tx_type');
            $table->dropIndex('idx_payment_user_created');
            $table->dropIndex('idx_payment_tx_type_created');
            $table->dropIndex('idx_payment_type');
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->dropIndex('idx_wallets_currency');
        });
    }
};
