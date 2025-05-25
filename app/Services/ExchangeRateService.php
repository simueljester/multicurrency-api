<?php

namespace App\Services;

use App\Http\Repositories\ExchangeRateRepository;
use Illuminate\Support\Carbon;

class ExchangeRateService
{
    protected $exchangeRateRepository;

    /**
     * Inject dependencies through constructor
     */
    public function __construct()
    {
        $this->exchangeRateRepository = app(ExchangeRateRepository::class);
    }

    /**
     * Get the latest exchange rate between two currencies.
     *
     * @return array ['rate' => float, 'timestamp' => Carbon]
     *
     * @throws \Exception
     */
    public function getLatestRate(string $from, string $to): array
    {

        $exchange = $this->exchangeRateRepository->query()->where('from_currency', strtoupper($from))
            ->where('to_currency', strtoupper($to))
            ->orderBy('fetched_at', 'desc')
            ->first();

        if (! $exchange) {
            throw new \Exception("No exchange rate found from $from to $to.");
        }

        return [
            'id' => $exchange->id,
            'rate' => $exchange->rate,
            'from_currency' => $exchange->from_currency,
            'timestamp' => $exchange->fetched_at,
        ];
    }

    /**
     * Convert amount to base currency.
     */
    public function convertToBaseCurrency(
        float $amount,
        string $companyCurrency,
        string $systemBaseCurrency,
        string $fromCurrency,
        float $rate
    ): float {
        if ($companyCurrency === $systemBaseCurrency) {
            return $amount;
        }

        return $companyCurrency === $fromCurrency
            ? $amount * $rate
            : $amount / $rate;
    }
}
