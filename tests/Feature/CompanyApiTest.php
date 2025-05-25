<?php

namespace Tests\Feature;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test for creating a company.
     */
    public function test_create_company(): void
    {
        // insert fake currencies data
        $currencies = [
            ['code' => 'PHP', 'name' => 'Philippine Peso'],
            ['code' => 'USD', 'name' => 'US Dollar'],
            ['code' => 'AUD', 'name' => 'Australian Dollar'],
        ];

        foreach ($currencies as $currency) {
            Currency::factory()->create($currency);
        }

        $payload = [
            'name' => 'Jollibee',
            'base_currency' => 'PHP',
        ];

        $response = $this->postJson('/api/v1/companies/store', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Jollibee',
                'base_currency' => 'PHP',
            ]);
    }
}
