<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexingToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add column
            $table->date('verified_at')->after('is_verified')->nullable();

            // Add indexes
            $table->index(['role', 'is_verified', 'verified_at'], 'users_role_verified_index');
            $table->index(['role', 'email_verified_at'], 'users_role_email_verified_index');
            $table->index(['role', 'created_at'], 'users_role_created_index');
            $table->index(['role', 'country'], 'users_role_country_index');
            $table->index('email', 'users_email_index');
            $table->index('phone', 'users_phone_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_verified_index');
            $table->dropIndex('users_role_email_verified_index');
            $table->dropIndex('users_role_created_index');
            $table->dropIndex('users_role_country_index');
            $table->dropIndex('users_email_index');
            $table->dropIndex('users_phone_index');

            // Drop column
            $table->dropColumn('verified_at');
        });
    }
}
