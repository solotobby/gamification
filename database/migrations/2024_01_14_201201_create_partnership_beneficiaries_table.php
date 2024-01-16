<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnershipBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partnership_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partnership_subscriptions_id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('dateOfBirth');
            $table->string('gender');
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
        Schema::dropIfExists('partnership_beneficiaries');
    }
}
