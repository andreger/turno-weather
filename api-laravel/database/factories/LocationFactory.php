<?php

namespace Database\Factories;

use App\Domain\Location\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Location\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city' => fake()->city(),
            'state' => fake()->state(),
        ];
    }
}
