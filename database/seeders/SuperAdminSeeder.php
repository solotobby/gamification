<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create or get super admin user
        $user = User::firstOrCreate(
            ['email' => 'hello@freebyz.com'],
            [
                'name' => 'System Super Admin',
                'password' => Hash::make('Admin@123'),
                'email_verified_at' => now(),
                'referral_code' => Str::random(7),
                'is_blacklisted' => false,
                'role' => 'super_admin',
                'is_verified' => true,
                'verified_at' => now(),
            ]
        );

        // If you’re using a separate pivot or role relationship
        if (method_exists($user, 'roles')) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        $this->command->info('✅ Super Admin role and user created successfully.');
    }
}
