<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('plan_code');
            $table->string('subscription_code');
            $table->string('amount');
            $table->string('commission');
            $table->string('affiliate_commission')->nullable();
            $table->unsignedBigInteger('affiliate_referral_id')->nullable();
            $table->string('payment_plan')->nullable();
            $table->string('numberOfSubscribers')->nullable();
            $table->string('nextPayment')->nullable();
            $table->string('product')->nullable();
            $table->string('partner');
            $table->boolean('settlement_status')->default(false);
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
        Schema::dropIfExists('partner_subscriptions');
    }
}
