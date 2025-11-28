<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlaggedStatusToCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('flagged_at')->nullable()->after('is_completed');
            $table->text('flagged_reason')->nullable()->after('flagged_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['flagged_at', 'flagged_reason']);
        });
    }
}
