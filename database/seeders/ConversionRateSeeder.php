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
            ['from' => 'Naira', 'to' => 'Dollar', 'rate' => '0.0013', 'status'=>'1', 'amount'=>'1'],
            [ 'from' => 'Dollar', 'to' => 'Naira', 'rate' => '780', 'status'=>'1', 'amount'=>'12'],
            [ 'from' => 'NGN', 'to' => 'GHS', 'rate' => '0.0013', 'status'=>'1', 'amount'=>'13'],
            [ 'from' => 'NGN', 'to' => 'RWF', 'rate' => '0.0013', 'status'=>'0', 'amount'=>'15'],
            [ 'from' => 'NGN', 'to' => 'KES', 'rate' => '0.0013', 'status'=>'1', 'amount'=>'11'],
            [ 'from' => 'NGN', 'to' => 'TZS', 'rate' => '0.0013', 'status'=>'0', 'amount'=>'14'],
            [ 'from' => 'NGN', 'to' => 'MWK', 'rate' => '0.0013', 'status'=>'1', 'amount'=>'16'],
            [ 'from' => 'NGN', 'to' => 'UGX', 'rate' => '0.0013', 'status'=>'1', 'amount'=>'17'],
            [ 'from' => 'NGN', 'to' => 'ZAR', 'rate' => '0.0013', 'status'=>'1', 'amount'=>'18'],
            [ 'from' => 'NGN', 'to' => 'XOF', 'rate' => '0.0013', 'status'=>'0', 'amount'=>'19'],
            [ 'from' => 'NGN', 'to' => 'XAF', 'rate' => '0.0013', 'status'=>'0', 'amount'=>'10'],
            [ 'from' => 'NGN', 'to' => 'ZMW', 'rate' => '0.0013', 'status'=>'0', 'amount'=>'21'],
        ];

        ConversionRate::query()->delete();
        foreach($rates as $rate)
        {
            ConversionRate::updateOrCreate($rate);
        }
    }
}
