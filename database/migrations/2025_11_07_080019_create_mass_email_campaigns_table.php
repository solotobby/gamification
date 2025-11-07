<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMassEmailCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mass_email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('message');
            $table->string('audience_type');
            $table->integer('days_filter')->nullable();
            $table->string('country_filter')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('delivered')->default(0);
            $table->integer('opened')->default(0);
            $table->integer('bounced')->default(0);
            $table->integer('failed')->default(0);
            $table->foreignId('sent_by')->constrained('users');
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
        Schema::dropIfExists('mass_email_campaigns');
    }
}
