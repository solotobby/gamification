<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClicksToMassEmailCampaignsAndLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mass_email_campaigns', function (Blueprint $table) {
            $table->unsignedInteger('clicks')->default(0)->after('opened');
        });

        Schema::table('mass_email_logs', function (Blueprint $table) {
            $table->timestamp('clicked_at')->nullable()->after('opened_at');
        });
    }

    public function down()
    {
        Schema::table('mass_email_campaigns', function (Blueprint $table) {
            $table->dropColumn('clicks');
        });

        Schema::table('mass_email_logs', function (Blueprint $table) {
            $table->dropColumn('clicked_at');
        });
    }
}
