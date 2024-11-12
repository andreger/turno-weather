<?php

namespace Database\Factories;

use App\Domain\Forecast\Models\Forecast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Forecast\Models\Forecast>
 */
class ForecastFactory extends Factory
{
    protected $model = Forecast::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'temperature' => fake()->randomFloat(2, 0, 40),
            'description' => fake()->sentence(),
            'icon' => fake()->lexify('icon-???'),
            'condition_at' => fake()->dateTime(),
            'location_id' => fake()->randomDigitNotNull(),
        ];
    }
}
