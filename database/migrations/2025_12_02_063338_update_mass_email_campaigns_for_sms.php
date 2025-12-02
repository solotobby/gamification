<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMassEmailCampaignsForSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mass_email_campaigns', function (Blueprint $table) {
            $table->string('channel')->default('email')->after('id');
            $table->text('sms_message')->nullable()->after('message');
            $table->integer('sms_recipients')->default(0)->after('total_recipients');
            $table->integer('sms_delivered')->default(0)->after('sms_recipients');
            $table->integer('sms_failed')->default(0)->after('sms_delivered');

            // Make subject nullable for SMS-only campaigns
            $table->string('subject')->nullable()->change();
            $table->text('message')->nullable()->change();
        });

        Schema::table('mass_email_logs', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('channel')->default('email')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mass_email_campaigns', function (Blueprint $table) {
            $table->dropColumn(['channel', 'sms_message', 'sms_recipients', 'sms_delivered', 'sms_failed']);
            $table->string('subject')->nullable(false)->change();
            $table->text('message')->nullable(false)->change();
        });

        Schema::table('mass_email_logs', function (Blueprint $table) {
            $table->dropColumn(['phone', 'channel']);
        });
    }
}
