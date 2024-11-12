<?php

namespace App\Domain\Location\Repositories;

use App\Domain\Location\Models\Location;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;


class LocationRepository
{
    /**
     * Gets a query that returns all locations associated with a given user.
     *
     * @param int $userId The ID of the user.
     * @return Builder The query.
     */
    private function getLocationsByUserQuery(int $userId): Builder
    {
        return Location::whereHas("users", function (Builder $query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }
    /**
     * Retrieves all locations associated with a specific user.
     *
     * @param int $userId The ID of the user whose locations are to be listed.
     * @return Collection A collection of locations associated with the given user.
     */
    public function listLocationsByUser(int $userId): Collection
    {
        return $this->getLocationsByUserQuery($userId)->get();
    }

    /**
     * Retrieves the number of locations associated with a specific user.
     *
     * @param int $userId The ID of the user whose locations are to be counted.
     * @return int The number of locations associated with the given user.
     */
    public function countLocationsByUser(int $userId): int
    {
        return $this->getLocationsByUserQuery($userId)->count();
    }

    /**
     * Creates a new location in the database.
     *
     * @param string $city The name of the city.
     * @param string|null $state The name of the state (if applicable).
     * @return Location The newly created location.
     */
    public function createLocation(string $city, ?string $state = null): Location
    {
        return Location::create([
            'city' => $city,
            'state' => $state
        ]);
    }

    /**
     * Retrieves a location by its ID.
     *
     * @param int $locationId The ID of the location to be retrieved.
     * @return Location|null The location with the given ID, or null if no such location exists.
     */
    public function getLocationById(int $locationId): ?Location
    {
        return Location::find($locationId);
    }

    /**
     * Finds a location by city and optionally by state.
     *
     * @param string $city The name of the city to search for.
     * @param string|null $state The name of the state to search for, if applicable.
     * @return Location|null The location that matches the given city and state, or null if no match is found.
     */

    public function findLocation(string $city, ?string $state = null): ?Location
    {
        $query = Location::where('city', $city);

        if ($state) {
            $query->where('state', $state);
        }

        return $query->first();
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
        $location->users()->attach($userId);
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
        $location->users()->detach($userId);
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
        return $location->users()->where('user_id', $userId)->exists();
    }
}
