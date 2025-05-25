<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->currencyCode),
            'name' => $this->faker->randomElement([
                'Philippine Peso',
                'US Dollar',
                'Australian Dollar',
                'Euro',
            ]),
        ];
    }
}
