<?php

namespace App\Console\Commands;

use App\Enums\ExchangeRateSourceEnum;
use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch latest exchange rates from Open Exchange Rates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appId = config('services.openexchangerates.app_id');
        $response = Http::get('https://openexchangerates.org/api/latest.json', [
            'app_id' => $appId,
            'base' => 'USD',
        ]);

        if (! $response->successful()) {
            $this->error('Failed to fetch exchange rates.');

            return;
        }

        $data = $response->json();
        $timestamp = now();
        $source = ExchangeRateSourceEnum::OpenExchangeRates;

        foreach ($data['rates'] as $toCurrency => $rate) {
            ExchangeRate::updateOrCreate(
                [
                    'from_currency' => 'USD',
                    'to_currency' => $toCurrency,
                    'fetched_at' => $timestamp,
                ],
                [
                    'rate' => $rate,
                    'source' => $source->value,
                ]
            );
        }

        $this->info('Exchange rates updated successfully.');
    }
}
