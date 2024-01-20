<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfilesInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            // 'country', 'country_code', 'currency', 'currency_code'
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['country', 'country_code', 'currency', 'currency_code']);
        });
    }
}
