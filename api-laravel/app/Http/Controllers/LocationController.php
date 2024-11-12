<?php

namespace App\Http\Controllers;

use App\Domain\Forecast\Services\OpenWeatherService;
use App\Domain\Location\Resources\LocationResource;
use App\Domain\Location\Services\LocationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LocationController extends BaseController
{
    private LocationService $locationService;
    private OpenWeatherService $openWeatherService;

    public function __construct(
        LocationService $locationService,
        OpenWeatherService $openWeatherService
    ) {
        $this->locationService = $locationService;
        $this->openWeatherService = $openWeatherService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $locations = $this->locationService->listLocationsByUser($user->id);
        return LocationResource::collection($locations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required',
        ]);

        $user = $request->user();
        $city = $request->input('city');
        $state = $request->input('state');

        $userLocationsCount = $this->locationService->countLocationsByUser($user->id);

        if ($userLocationsCount >= 3) {
            throw new HttpException(422,'You can only save 3 locations');
        }

        $location = $this->locationService->findLocation($city, $state);

        if (! $location) {
            if (! $this->openWeatherService->locationExists($city, $state)) {
                throw new HttpException(404, 'Location not found');
            }

            $location = $this->locationService->createLocation( $city, $state);
        }

        if ($this->locationService->isUserAttached($location, $user->id)) {
            throw new HttpException(422, 'Location already exists');
        }

        $this->locationService->attachUser($location, $user->id);

        return new LocationResource($location);
    }

    public function destroy(Request $request, string $locationId)
    {
        $user = $request->user();
        $location = $this->locationService->getLocationById($locationId);

        if ($user->cannot('delete', $location)) {
            throw new HttpException(403, 'You cannot delete this location');
        }

        $this->locationService->detachUser($location, $user->id);

        return $this->sendNoContentResponse();
    }

}
