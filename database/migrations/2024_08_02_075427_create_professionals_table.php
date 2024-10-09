<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->string('_link');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('professional_category_id');
            $table->unsignedBigInteger('professional_sub_category_id');
            $table->unsignedBigInteger('professional_domain_id');
            $table->string('full_name');
            $table->string('employment_status');
            $table->string('title');
            $table->longText('work_experience');
            $table->string('communication_mode');
            $table->bigInteger('avg_rating')->default(0);
            $table->text('website_link')->nullable();
            $table->text('fb_link')->nullable();
            $table->text('tiktok_link')->nullable();
            $table->text('x_link')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->text('instagram_link')->nullable();
            $table->text('geo')->nullable();
            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            // $table->foreign('professional_category_id')->references('id')->on('professionals_categories')->cascadeOnDelete();
            // $table->foreign('professional_sub_category_id')->references('id')->on('professionals_sub_categories')->cascadeOnDelete();
            // $table->foreign('professional_domain_id')->references('id')->on('professional_domains')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professionals');
    }
}
