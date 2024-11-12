<?php

namespace Tests\Unit\Domain\Forecast\Repositories;

use Tests\TestCase;
use App\Domain\Forecast\Models\Forecast;
use App\Domain\Forecast\Repositories\ForecastRepository;
use App\Domain\Location\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForecastRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * Creates a new forecast
     *
     * @covers \App\Domain\Forecast\Repositories\ForecastRepository::createForecast
     */
    public function testCreateForecast()
    {
        $location = Location::factory()->create();

        $weatherCondition = [
            'temperature' => 25.5,
            'condition_at' => '2024-11-10 12:00:00',
            'description' => 'Sunny',
            'icon' => '01d',
        ];

        $repository = new ForecastRepository();
        $forecast = $repository->createForecast($location->id, $weatherCondition);

        $this->assertInstanceOf(Forecast::class, $forecast);

        $this->assertEquals($location->id, $forecast->location_id);
        $this->assertEquals($weatherCondition['temperature'], $forecast->temperature);
        $this->assertEquals($weatherCondition['condition_at'], $forecast->condition_at);
        $this->assertEquals($weatherCondition['description'], $forecast->description);
        $this->assertEquals($weatherCondition['icon'], $forecast->icon);

        $this->assertDatabaseHas('forecasts', [
            'location_id' => $location->id,
            'temperature' => $weatherCondition['temperature'],
            'condition_at' => $weatherCondition['condition_at'],
            'description' => $weatherCondition['description'],
            'icon' => $weatherCondition['icon'],
        ]);
    }
}
