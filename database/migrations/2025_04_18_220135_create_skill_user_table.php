<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_asset_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('skill_asset_id')->references('id')->on('skill_assets')->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skill_user');
    }
}
