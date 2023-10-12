<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafeLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safe_locks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount_locked', 10, 2);
            $table->integer('interest_rate');
            $table->decimal('interest_accrued', 10, 2);
            $table->decimal('total_payment', 10, 2);
            $table->string('duration');
            $table->string('start_date');
            $table->string('maturity_date');
            $table->string('status')->default('safeLocked');
            $table->boolean('is_paid')->default(false);
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
        Schema::dropIfExists('safe_locks');
    }
}
