<?php

namespace App\Domain\Forecast\Services;

use App\Domain\Forecast\Repositories\ForecastRepository;
use App\Domain\Location\Models\Location;

class ForecastService
{

    protected ForecastRepository $forecastRepository;
    protected OpenWeatherService $openWeatherService;

    /**
     * ForecastService constructor.
     *
     * @param ForecastRepository $forecastRepository The repository for handling forecast data.
     * @param OpenWeatherService $openWeatherService The service for retrieving weather conditions from OpenWeather.
     */
    public function __construct(ForecastRepository $forecastRepository, OpenWeatherService $openWeatherService)
    {
        $this->forecastRepository = $forecastRepository;
        $this->openWeatherService = $openWeatherService;
    }

    /**
     * Updates the forecasts for the given location.
     *
     * @param Location $location The location whose forecasts should be updated.
     *
     * @return array The newly created forecasts.
     */
    public function updateLocationForecasts(Location $location): array
    {

        $weatherConditions = $this->openWeatherService->getWeatherConditionsByCity($location->city, $location->state);

        $forecasts = [];
        foreach ($weatherConditions as $weatherCondition) {
            $forecasts[] = $this->forecastRepository->createForecast($location->id, $weatherCondition);
        }

        return $forecasts;
    }


}
