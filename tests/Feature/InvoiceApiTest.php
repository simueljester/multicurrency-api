<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ExchangeRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class InvoiceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_invoice()
    {
        // Set base currency
        Config::set('app.base_currency', 'USD');

        // Create a company with a different base currency
        $company = Company::factory()->create([
            'base_currency' => 'PHP',
        ]);

        // Simulate an exchange rate
        $exchangeRate = ExchangeRate::factory()->create([
            'from_currency' => 'USD',
            'to_currency' => 'PHP',
            'rate' => 55.5,
        ]);

        $payload = [
            'company_id' => $company->id,
            'title' => 'Consulting Fee',
            'amount' => 1000,
        ];

        $response = $this->postJson('/api/v1/invoices/store', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Consulting Fee',
                'amount' => 1000,
                'currency_code' => 'PHP',
                'base_currency' => 'USD',
            ]);

        $this->assertDatabaseHas('invoices', [
            'company_id' => $company->id,
            'title' => 'Consulting Fee',
            'amount_in_base_currency' => 18.018018018018,
        ]);
    }
}
