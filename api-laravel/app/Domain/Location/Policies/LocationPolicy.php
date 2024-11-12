<?php

namespace App\Domain\Location\Policies;

use App\Domain\User\Models\User;
use App\Domain\Location\Models\Location;

class LocationPolicy
{
    /**
     * Determine whether the user can delete the specified location.
     *
     * @param User $user
     * @param Location $location
     * @return bool
     */
    public function delete(User $user, Location $location): bool
    {
        return $user->locations()->where('location_id', $location->id)->exists();
    }
}
