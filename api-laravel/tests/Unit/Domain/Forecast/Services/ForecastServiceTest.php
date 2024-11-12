<?php

use App\Domain\Forecast\Models\Forecast;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use App\Domain\Forecast\Services\ForecastService;
use App\Domain\Forecast\Repositories\ForecastRepository;
use App\Domain\Location\Models\Location;
use App\Domain\Forecast\Services\OpenWeatherService;

class ForecastServiceTest extends TestCase
{
    protected $forecastRepositoryMock;
    protected $openWeatherServiceMock;
    protected $forecastService;

    protected function setUp(): void
    {
        $this->forecastRepositoryMock = $this->createMock(ForecastRepository::class);
        $this->openWeatherServiceMock = $this->createMock(OpenWeatherService::class);

        $this->forecastService = new ForecastService(
            $this->forecastRepositoryMock,
            $this->openWeatherServiceMock
        );
    }

    /**
     * Tests that the updateLocationForecasts method successfully retrieves weather conditions
     * for a given location and creates the corresponding forecasts.
     *
     * Mocks the OpenWeatherService to return predefined weather conditions and ensures that
     * the ForecastRepository's createForecast method is called the expected number of times.
     * Verifies that the number of forecasts returned matches the number of weather conditions
     * and that the created forecast objects are as expected.
     */
    public function testUpdateLocationForecasts()
    {
        $location = new Location();
        $location->id = 1;
        $location->city = 'Test City';
        $location->state = 'Test State';

        $forecast = new Forecast();

        $weatherConditions = new Collection([
            [
                'temperature' => 20,
                'condition_at' => '2024-01-01 00:00:00',
                'description' => 'Sunny',
                'icon' => 'sunny'
            ]
        ]);

        $this->openWeatherServiceMock
            ->expects($this->once())
            ->method('getWeatherConditionsByCity')
            ->with($location->city, $location->state)
            ->willReturn($weatherConditions);

        $this->forecastRepositoryMock
            ->expects($this->exactly($weatherConditions->count()))
            ->method('createForecast')
            ->willReturn($forecast);

        $result = $this->forecastService->updateLocationForecasts($location);

        $this->assertCount(1, $result);
        $this->assertEquals($forecast, $result[0]);
    }
}
