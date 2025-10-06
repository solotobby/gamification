<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'type', 'created_at'], 'idx_user_status_type_created');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['is_verified', 'verified_at'], 'idx_verified_verifiedat');
        });
    }

    public function down()
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_user_status_type_created');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_verified_verifiedat');
        });
    }
};
