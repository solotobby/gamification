<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('professional_sub_categories_id');
            $table->string('name');
            $table->timestamps();

            // $table->foreign('professional_sub_categories_id')->references('id')->on('professionals_sub_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professional_domains');
    }
}
