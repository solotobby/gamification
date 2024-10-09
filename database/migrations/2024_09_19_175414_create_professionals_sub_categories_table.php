<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalsSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('professional_category_id');
            // $table->foreign('professional_category_id')->references('id')->on('professionals_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('unique_id');
            $table->string('status')->default('active');
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
        Schema::dropIfExists('professionals_sub_categories');
    }
}
