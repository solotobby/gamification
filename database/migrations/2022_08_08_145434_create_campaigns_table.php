<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('post_title');
            $table->string('post_link');
            $table->string('campaign_type');
            $table->string('campaign_subcategory');
            $table->string('number_of_staff');
            $table->string('campaign_amount');
            $table->longText('description');
            $table->longText('proof');
            $table->string('total_amount')->default('0.00');
            $table->boolean('is_paid')->default(false);
            $table->string('approved')->default('Pending');
            $table->string('status')->default('Offline');
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
        Schema::dropIfExists('campaigns');
    }
}
