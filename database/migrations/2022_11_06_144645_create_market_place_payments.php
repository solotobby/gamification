<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketPlacePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_place_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('market_place_product_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('amount');
            $table->string('email');
            $table->string('ref');
            $table->string('url')->nullable();
            $table->boolean('is_complete')->default(false);
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
        Schema::dropIfExists('market_place_payments');
    }
}
