<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store currency codes and names';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching currency list...');

        $response = Http::get('https://openexchangerates.org/api/currencies.json');

        if (! $response->ok()) {
            $this->error('Failed to fetch currencies.');

            return 1;
        }

        $currencies = $response->json();

        foreach ($currencies as $code => $name) {
            Currency::updateOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
        }

        $this->info('Currency list successfully updated.');

        return 0;
    }
}
