<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPointBalanceToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->string('point')->default('0.0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn(['point']);
        });
    }
}
