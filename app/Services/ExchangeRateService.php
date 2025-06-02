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
     * Convert a given amount to the system's base currency using the provided exchange rate.
     *
     * @param float  $amount            The monetary amount to convert.
     * @param string $companyCurrency   The currency used by the company.
     * @param string $systemBaseCurrency The base currency used by the system.
     * @param string $fromCurrency      The currency from which conversion is done.
     * @param float  $rate              The exchange rate used for conversion.
     *
     * @return float The amount converted to the base currency.
     */
    public function convertToBaseCurrency(
        float $amount,
        string $companyCurrency,
        string $systemBaseCurrency,
        string $fromCurrency,
        float $rate
    ): float {
        // If the company's currency is already the same as the system's base currency,
        // there's no need to convert, so return the original amount.
        if ($companyCurrency === $systemBaseCurrency) {
            return $amount;
        }

        // - If the company's currency matches the currency we're converting from,
        //   multiply the amount by the rate to get the base currency value.
        // - Otherwise, divide the amount by the rate.
        return $companyCurrency === $fromCurrency
            ? $amount * $rate
            : $amount / $rate;
    }
}
