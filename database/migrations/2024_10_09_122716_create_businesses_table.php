<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('business_name')->unique();
            $table->string('business_phone')->unique();
            $table->longText('description');
            $table->string('x_link')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('tiktok_link')->nullable();
            $table->string('pinterest_link')->nullable();
            $table->string('business_link');
            $table->string('visits')->default('0');
            $table->string('status')->default('ACTIVE');
            $table->boolean('is_live')->default(false);
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
        Schema::dropIfExists('businesses');
    }
}
