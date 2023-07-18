<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWithrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withrawals', function (Blueprint $table) {
            $table->string('paypal_email')->nullable();
            $table->boolean('is_usd')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withrawals', function (Blueprint $table) {
            $table->dropColumn(['paypal_email', 'is_usd']);
        });
    }
}
