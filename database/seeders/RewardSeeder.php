<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rewards = [
            ['id' => '1', 'name' => 'CASH', 'amount' => '1000'],
            ['id' => '2', 'name' => 'AIRTIME', 'amount' => '200'],
            ['id' => '3', 'name' => 'DATA', 'amount' => '200']
        ];

        foreach($rewards as $reward)
        {
             Reward::updateOrCreate($reward);
        }
    }
}
