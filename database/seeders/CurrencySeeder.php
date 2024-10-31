<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            ['code' => 'NGN', 'country' => 'Nigeria', 'is_active' => true],
            ['code' => 'USD', 'country' => 'United States', 'is_active' => true],
            ['code' => 'RWF', 'country' => 'Rwanda', 'is_active' => false],
            ['code' => 'GHS', 'country' => 'Ghana', 'is_active' => true],
            ['code' => 'KES', 'country' => 'Kenya', 'is_active' => true],
            ['code' => 'TZS', 'country' => 'Tanzania', 'is_active' => false],
            ['code' => 'MWK', 'country' => 'Malawi', 'is_active' => false],
            ['code' => 'UGX', 'country' => 'Uganda', 'is_active' => true],
            ['code' => 'ZAR', 'country' => 'South Africa', 'is_active' => true],
            ['code' => 'XOF', 'country' => "Cote d'Ivoire", 'is_active' => false],
            ['code' => 'XAF', 'country' => 'Cameroon', 'is_active' => false],
            ['code' => 'ZMW', 'country' => 'Zambia', 'is_active' => false],
            ['code' => 'XOF', 'country' => 'Senegal', 'is_active' => false],
        ];

        foreach($currencies as $currency){
            Currency::updateOrCreate($currency);
        }
    }
}
