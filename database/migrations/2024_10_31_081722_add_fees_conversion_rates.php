<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeesConversionRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversion_rates', function (Blueprint $table) {
            $table->string('upgrade_fee')->after('status')->nullable();
            $table->string('allow_upload')->after('status')->nullable();
            $table->string('priotize')->after('status')->nullable();
            $table->string('referral_commission')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversion_rates', function (Blueprint $table) {
            $table->dropColumn(['upgrade_fee', 'allow_upload', 'priotize', 'referral_commission']);
        });
    }
}
