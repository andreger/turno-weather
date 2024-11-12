<?php

namespace App\Domain\Location\Services;

use App\Domain\Forecast\Services\ForecastService;
use App\Domain\Location\Models\Location;
use App\Domain\Location\Repositories\LocationRepository;
use Illuminate\Support\Collection;

class LocationService {

    protected LocationRepository $locationRepository;
    protected ForecastService $forecastService;

    /**
     * Constructs an instance of the LocationService.
     *
     * @param LocationRepository $locationRepository The repository for handling location data.
     * @param ForecastService $forecastService The service for retrieving weather forecasts from OpenWeather.
     */
    public function __construct(
        LocationRepository $locationRepository,
        ForecastService $forecastService,
    ) {
        $this->locationRepository = $locationRepository;
        $this->forecastService = $forecastService;
    }

    /**
     * Retrieves all locations associated with a specific user.
     *
     * @param int $userId The ID of the user whose locations are to be listed.
     * @return Collection A collection of locations associated with the given user.
     */
    public function listLocationsByUser(int $userId): Collection
    {
        return $this->locationRepository->listLocationsByUser($userId);
    }

    /**
     * Retrieves the count of locations associated with a specific user.
     *
     * @param int $userId The ID of the user whose locations are to be counted.
     * @return int The number of locations associated with the given user.
     */
    public function countLocationsByUser(int $userId): int
    {
        return $this->locationRepository->countLocationsByUser($userId);
    }

    /**
     * Creates a new location and updates its forecasts.
     *
     * @param string $city The name of the city.
     * @param string|null $state The name of the state (if applicable).
     * @return Location The newly created location with updated forecasts.
     */
    public function createLocation(string $city, ?string $state = null): Location
    {
        $location = $this->locationRepository->createLocation($city, $state);
        $location->forecasts = $this->forecastService->updateLocationForecasts($location);

        return $location;
    }

    /**
     * Retrieves a location by its ID.
     *
     * @param int $locationId The ID of the location to be retrieved.
     * @return Location|null The location with the given ID, or null if no such location exists.
     */
    public function getLocationById(int $locationId): ?Location
    {
        return $this->locationRepository->getLocationById($locationId);
    }

    /**
     * Finds a location by city and optionally by state.
     *
     * @param string $city The name of the city.
     * @param string|null $state The name of the state (if applicable).
     * @return Location|null The location if found, or null if not found.
     */
    public function findLocation(string $city, ?string $state = null): ?Location
    {
        return $this->locationRepository->findLocation($city, $state);
    }

    /**
     * Attaches a user to a location.
     *
     * @param Location $location The location to attach the user to.
     * @param int $userId The ID of the user to attach.
     * @return void
     */
    public function attachUser(Location $location, int $userId)
    {
        $this->locationRepository->attachUser($location, $userId);
    }

    /**
     * Detaches a user from a location.
     *
     * @param Location $location The location from which to detach the user.
     * @param int $userId The ID of the user to detach.
     * @return void
     */
    public function detachUser(Location $location, int $userId)
    {
        $this->locationRepository->detachUser($location, $userId);
    }

    /**
     * Checks if a user is attached to a specific location.
     *
     * @param Location $location The location to check.
     * @param int $userId The ID of the user to check.
     * @return bool True if the user is attached to the location, otherwise false.
     */
    public function isUserAttached(Location $location, int $userId): bool
    {
        return $this->locationRepository->isUserAttached($location, $userId);
    }
}
