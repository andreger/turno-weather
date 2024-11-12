<?php

namespace App\Domain\Forecast\Repositories;

use App\Domain\Forecast\Models\Forecast;

class ForecastRepository
{

    /**
     * Creates a new forecast
     *
     * @param int $locationId The ID of the related location
     * @param array $weatherCondition An array with the following keys:
     *  - temperature: float
     *  - condition_at: string (ISO 8601 date)
     *  - description: string
     *  - icon: string (icon name)
     *
     * @return Forecast
     */
    public function createForecast(int $locationId, array $weatherCondition): Forecast
    {
        $forecast = Forecast::create([
            'location_id' => $locationId,
            'temperature' => $weatherCondition['temperature'],
            'condition_at' => $weatherCondition['condition_at'],
            'description' => $weatherCondition['description'],
            'icon' => $weatherCondition['icon'],
        ]);

        return $forecast;
    }
}
