<?php

namespace Database\Seeders;

use App\Models\ConversionRate;
use Illuminate\Database\Seeder;

class ConversionRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rates = [
            ['id' => '1', 'from' => 'Naira', 'to' => 'Dollar', 'rate' => '0.0013', 'status'=>'1'],
            ['id' => '2', 'from' => 'Dollar', 'to' => 'Naira', 'rate' => '780', 'status'=>'1']
        ];

        foreach($rates as $rate)
        {
            ConversionRate::updateOrCreate($rate);
        }
    }
}
