<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExchangeRateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'from_currency' => 'USD',
            'to_currency' => 'PHP',
            'rate' => $this->faker->randomFloat(6, 40, 60),
            'fetched_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'source' => \App\Enums\ExchangeRateSourceEnum::Manual->value,
        ];
    }
}
