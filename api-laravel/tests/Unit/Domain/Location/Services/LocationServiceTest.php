<?php

namespace Tests\Unit\Domain\Location\Services;

use App\Domain\Forecast\Models\Forecast;
use App\Domain\Location\Models\Location;
use App\Domain\Location\Repositories\LocationRepository;
use App\Domain\Location\Services\LocationService;
use App\Domain\Forecast\Services\ForecastService;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    protected $locationRepositoryMock;
    protected $forecastServiceMock;
    protected $locationService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mocks for dependencies
        $this->locationRepositoryMock = $this->createMock(LocationRepository::class);
        $this->forecastServiceMock = $this->createMock(ForecastService::class);

        // Instantiate the service with mocked dependencies
        $this->locationService = new LocationService(
            $this->locationRepositoryMock,
            $this->forecastServiceMock,
        );
    }

    /**
     * Tests that the listLocationsByUser method returns the expected collection of locations
     * for a given user ID by mocking the LocationRepository to return a predefined collection.
     */
    public function testListLocationsByUser()
    {
        $userId = 1;
        $expectedLocations = new Collection([new Location()]);

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('listLocationsByUser')
            ->with($userId)
            ->willReturn($expectedLocations);

        $result = $this->locationService->listLocationsByUser($userId);

        $this->assertEquals($expectedLocations, $result);
    }

    /**
     * Tests that the countLocationsByUser method returns the expected count of locations
     * for a given user ID by mocking the LocationRepository to return a predefined count.
     */
    public function testCountLocationsByUser()
    {
        $userId = 1;
        $expectedCount = 5;

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('countLocationsByUser')
            ->with($userId)
            ->willReturn($expectedCount);

        $result = $this->locationService->countLocationsByUser($userId);

        $this->assertEquals($expectedCount, $result);
    }

    /**
     * Tests that the createLocation method returns the expected location
     * for a given city and optionally state by mocking the LocationRepository
     * to return a predefined location and the ForecastService to return a predefined
     * array of forecasts.
     */
    public function testCreateLocation()
    {
        $city = 'New York';
        $state = 'NY';
        $location = new Location();
        $forecasts = [new Forecast(), new Forecast()];
        $location->forecasts = $forecasts;

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('createLocation')
            ->with($city, $state)
            ->willReturn($location);


        $this->forecastServiceMock
            ->expects($this->once())
            ->method('updateLocationForecasts')
            ->with($location)
            ->willReturn($forecasts);

        $result = $this->locationService->createLocation($city, $state);

        $this->assertSame($location, $result);
        $this->assertEquals($location->forecasts, $result->forecasts);
    }

    /**
     * Tests that the getLocationById method returns the expected location
     * for a given location ID by mocking the LocationRepository to return
     * a predefined location.
     */
    public function testGetLocationById()
    {
        $locationId = 1;
        $expectedLocation = new Location();

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('getLocationById')
            ->with($locationId)
            ->willReturn($expectedLocation);

        $result = $this->locationService->getLocationById($locationId);

        $this->assertEquals($expectedLocation, $result);
    }

    /**
     * Tests that the findLocation method returns the expected location
     * for a given city and state by mocking the LocationRepository
     * to return a predefined location.
     */
    public function testFindLocation()
    {
        $city = 'Los Angeles';
        $state = 'California';
        $expectedLocation = new Location();

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('findLocation')
            ->with($city, $state)
            ->willReturn($expectedLocation);

        $result = $this->locationService->findLocation($city, $state);

        $this->assertEquals($expectedLocation, $result);
    }

    /**
     * Tests that the attachUser method delegates to the LocationRepository
     * with the correct location and user ID.
     */
    public function testAttachUser()
    {
        $location = new Location();
        $userId = 1;

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('attachUser')
            ->with($location, $userId);

        $this->locationService->attachUser($location, $userId);

        // Assert: No exception means the test passed
        $this->assertTrue(true);
    }

    /**
     * Tests that the detachUser method delegates to the LocationRepository
     * with the correct location and user ID.
     */
    public function testDetachUser()
    {
        $location = new Location();
        $userId = 1;

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('detachUser')
            ->with($location, $userId);

        $this->locationService->detachUser($location, $userId);

        // Assert: No exception means the test passed
        $this->assertTrue(true);
    }

    /**
     * Tests that the isUserAttached method returns the expected result
     * for a given location and user ID by mocking the LocationRepository
     * to return a predefined boolean value.
     */
    public function testIsUserAttached()
    {
        $location = new Location();
        $userId = 1;
        $expectedResult = true;

        $this->locationRepositoryMock
            ->expects($this->once())
            ->method('isUserAttached')
            ->with($location, $userId)
            ->willReturn($expectedResult);

        $result = $this->locationService->isUserAttached($location, $userId);

        $this->assertEquals($expectedResult, $result);
    }
}
