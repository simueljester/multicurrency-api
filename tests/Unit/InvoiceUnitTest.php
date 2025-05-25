<?php

namespace Tests\Unit;

use App\Services\ExchangeRateService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceUnitTest extends TestCase
{
    use WithFaker;

    /**
     * A basic unit test for currency services.
     */
    public function test_get_latest_rate(): void
    {
        $exchangeRateService = $this->createMock(ExchangeRateService::class);

        // Create a Collection of fake exchange rates
        $exchangeRates = collect([
            [
                'from_currency' => 'USD',
                'to_currency' => 'PHP',
                'rate' => 56.5,
                'fetched_at' => '2025-05-25 05:21:47',
                'source' => 'Manual',
            ],
            [
                'from_currency' => 'USD',
                'to_currency' => 'AUD',
                'rate' => 1.45,
                'fetched_at' => '2025-05-25 05:22:00',
                'source' => 'Manual',
            ],
            [
                'from_currency' => 'USD',
                'to_currency' => 'INR',
                'rate' => 82.3,
                'fetched_at' => '2025-05-25 05:22:15',
                'source' => 'Manual',
            ],
        ]);

        // Map keys for willReturnMap to use for mocking getLatestRate()
        // Format: [from_currency, to_currency, data]
        $map = $exchangeRates->map(function ($rate) {
            return [$rate['from_currency'], $rate['to_currency'], $rate];
        })->toArray();

        // Mock getLatestRate method to return corresponding item based on from/to
        $exchangeRateService->method('getLatestRate')->willReturnMap($map);

        // Test USD to PHP
        $resultPHP = $exchangeRateService->getLatestRate('USD', 'PHP');
        $expectedPHP = $exchangeRates->firstWhere('to_currency', 'PHP');
        $this->assertEquals($expectedPHP, $resultPHP);

        // Test USD to AUD
        $resultAUD = $exchangeRateService->getLatestRate('USD', 'AUD');
        $expectedAUD = $exchangeRates->firstWhere('to_currency', 'AUD');
        $this->assertEquals($expectedAUD, $resultAUD);

        // Test USD to INR
        $resultINR = $exchangeRateService->getLatestRate('USD', 'INR');
        $expectedINR = $exchangeRates->firstWhere('to_currency', 'INR');
        $this->assertEquals($expectedINR, $resultINR);
    }

    public function test_convert_to_base_currency(): void
    {
        $exchangeRateService = new ExchangeRateService;

        $amount = 1000;

        // Scenario 1: company currency equals base currency → no conversion needed
        $companyCurrency = 'USD';
        $systemBaseCurrency = 'USD';
        $fromCurrency = 'USD';

        $converted = $exchangeRateService->convertToBaseCurrency(
            $amount,
            $companyCurrency,
            $systemBaseCurrency,
            $fromCurrency,
            $rate = 0
        );

        $this->assertEquals(1000.0, $converted, 'No conversion if currencies are same');

        // Scenario 2: company currency is PHP, system base is USD, conversion applies
        $companyCurrency = 'PHP';
        $systemBaseCurrency = 'USD';
        $fromCurrency = 'USD';
        $rate = 55.3;

        $expected = $amount / $rate;

        $converted = $exchangeRateService->convertToBaseCurrency(
            $amount,
            $companyCurrency,
            $systemBaseCurrency,
            $fromCurrency,
            $rate
        );

        $this->assertEquals($expected, $converted, 'Conversion applies when currencies differ');
    }
}
