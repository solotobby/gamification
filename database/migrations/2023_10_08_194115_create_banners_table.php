<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('banner_id');
            $table->string('banner_url');
            $table->string('external_link');
            $table->string('age_bracket');
            $table->string('ad_placement_point');
            $table->string('adplacement_position');
            $table->string('duration');
            $table->string('country');
            $table->decimal('amount', 10, 2);
            $table->boolean('status')->default(false);
            $table->bigInteger('impression');
            $table->bigInteger('clicks');
            $table->string('currency')->default('NGN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
