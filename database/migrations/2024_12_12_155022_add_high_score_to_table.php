<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHighScoreToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spin_scores', function (Blueprint $table) {
            $table->boolean('is_high_prize')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spin_scores', function (Blueprint $table) {
            $table->dropColumn(['is_high_prize']);
        });
    }
}
