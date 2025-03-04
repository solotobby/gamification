<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('professional_category_id');
            $table->unsignedBigInteger('professional_sub_category_id');
            $table->string('title');
            $table->string('slug');
            $table->longText('description');
            $table->string('views')->default('0');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('professional_category_id')->references('id')->on('professionals_categories')->cascadeOnDelete();
            $table->foreign('professional_sub_category_id')->references('id')->on('professionals_sub_categories')->cascadeOnDelete();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professional_jobs');
    }
}
