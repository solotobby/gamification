<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsdverifieds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usdverifieds', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false);
            $table->string('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usdverifieds', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'amount']);
        });
    }
}
