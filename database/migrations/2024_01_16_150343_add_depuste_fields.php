<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepusteFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->boolean('is_dispute')->default(false);
            $table->boolean('is_dispute_resolved')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_workers', function (Blueprint $table) {
            $table->dropColumn(['is_dispute', 'is_dispute_resolved']);
        });
    }
}
