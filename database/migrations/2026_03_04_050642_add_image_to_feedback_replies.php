<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback_replies', function (Blueprint $table) {
            $table->boolean('is_image')->default(false)->after('message');
            $table->string('image_url')->nullable()->after('is_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_replies', function (Blueprint $table) {
            $table->dropColumn(['is_image', 'image_url']);
        });
    }
};
