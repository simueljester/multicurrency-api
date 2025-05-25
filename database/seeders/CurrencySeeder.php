<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://openexchangerates.org/api/currencies.json');

        $currencies = $response->json();

        foreach ($currencies as $code => $name) {
            Currency::updateOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
        }
    }
}
