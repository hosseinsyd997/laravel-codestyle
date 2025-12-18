<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CountryFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currency = Currency::inRandomOrder()->first() ?? Currency::factory()->create();

        return [
            'name' => $this->faker->country(),
            'code' => strtoupper($this->faker->unique()->lexify('??')),
            'currency_id' => $currency->id,
        ];
    }
}
