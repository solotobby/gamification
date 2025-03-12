<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricesToSkillassetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skill_assets', function (Blueprint $table) {
            $table->string('min_price')->after('availability')->default(0.0);
            $table->string('max_price')->after('availability')->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skill_assets', function (Blueprint $table) {
            $table->dropColumn(['min_price', 'max_price']);
        });
    }
}
