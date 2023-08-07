<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['name' => 'local', 'status' => 1, 'value' => 'Paystack'],
            ['name' => 'sendmonny', 'status' => 0, 'value' => 'Paystacky']
        ];

        foreach($settings as $setting){
            Settings::updateOrCreate($setting);
        }
    }
}
