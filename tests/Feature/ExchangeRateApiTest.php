<?php

namespace Tests\Feature;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExchangeRateApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test to create exchange rate.
     */
    public function test_create_exchange_rate(): void
    {
        Currency::factory()->create(['code' => 'USD', 'name' => 'US Dollar']);
        Currency::factory()->create(['code' => 'PHP', 'name' => 'Philippine Peso']);

        $payload = [
            'from_currency' => 'USD',
            'to_currency' => 'PHP',
            'rate' => 55.50,
        ];

        $response = $this->postJson('/api/v1/rates/store', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'from_currency' => 'USD',
                'to_currency' => 'PHP',
                'rate' => 55.5,
                'source' => 'Manual',
            ]);
    }
}
