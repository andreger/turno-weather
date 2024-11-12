<?php

namespace App\Domain\Forecast\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class OpenWeatherService
{

    protected string $baseUrl;
    protected string $apiKey;
    protected string $iconUrl;

    /**
     * Constructs an instance of the OpenWeatherService.
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUrl = env('OPEN_WEATHER_API_URL');
        $this->apiKey = env('OPEN_WEATHER_API_KEY');
        $this->iconUrl = env('OPEN_WEATHER_ICON_URL');
    }

    /**
     * Gets the weather conditions by city.
     *
     * @param string $city
     * @param string|null $state
     * @param int|null $limit
     * @return Collection
     */
    public function getWeatherConditionsByCity(string $city, ?string $state = null, ?int $limit = 10): Collection
    {
        $endpoint = "{$this->baseUrl}/data/2.5/forecast?q={$city},{$state}&cnt={$limit}&units=metric&appid={$this->apiKey}";

        $response = Http::get($endpoint);

        $weatherConditions = [];

        if ($response->successful()) {

            $responseObj = $response->object();

            if ($fetchedConditions = $responseObj->list) {
                foreach ($fetchedConditions as $fetchedCondition) {
                    $weatherConditions[] = [
                        'temperature' => $fetchedCondition->main->temp,
                        'condition_at' => $fetchedCondition->dt_txt,
                        'description' => $fetchedCondition->weather
                            ? $fetchedCondition->weather[0]->description
                            : null,
                        'icon' => $fetchedCondition->weather
                            ? $this->iconUrl . "/" . $fetchedCondition->weather[0]->icon . ".png"
                            : null,
                    ];
                }
            }
        }

        return new Collection($weatherConditions);
    }

    /**
     * Checks if a location exists.
     *
     * @param string $city
     * @param string|null $state
     * @return bool
     */
    public function locationExists(string $city, ?string $state = null): bool
    {
        $endpoint = "{$this->baseUrl}/geo/1.0/direct?q={$city},{$state}&limit=1&appid={$this->apiKey}";

        $response = Http::get($endpoint);

        if ($response->successful()) {
            $responseObj = $response->object();

            return count($responseObj) > 0;
        }

        return false;
    }
}
