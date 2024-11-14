<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\LocationController;
use App\Domain\Forecast\Services\OpenWeatherService;
use App\Domain\Location\Resources\LocationResource;
use App\Domain\Location\Services\LocationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Domain\Location\Models\Location;
use App\Domain\User\Models\User;
use Mockery;

class LocationControllerTest extends TestCase
{
    protected $locationServiceMock;
    protected $openWeatherServiceMock;
    protected $controller;



    protected function setUp(): void
    {
        parent::setUp();

        $this->locationServiceMock = Mockery::mock(LocationService::class);
        $this->openWeatherServiceMock = Mockery::mock(OpenWeatherService::class);

        $this->controller = new LocationController(
            $this->locationServiceMock,
            $this->openWeatherServiceMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that all locations for the user are returned.
     */
    public function testIndex()
    {
        $user = User::factory()->make(['id' => 1]);
        $locations = collect([Location::factory()->make(), Location::factory()->make()]);

        $this->locationServiceMock
            ->shouldReceive('listLocationsByUser')
            ->with($user->id)
            ->andReturn($locations);

        $request = Request::create('/locations', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->index($request);

        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $response);
        $this->assertCount(2, $response->collection);
    }

    /**
     * Test a valid location can be added by the user.
     */
    public function testStore()
    {
        $user = User::factory()->make(['id' => 1]);
        $request = Request::create('/locations', 'POST', ['city' => 'TestCity', 'state' => 'TestState']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $this->locationServiceMock
            ->shouldReceive('countLocationsByUser')
            ->with($user->id)
            ->andReturn(2);

        $this->locationServiceMock
            ->shouldReceive('findLocation')
            ->with('TestCity', 'TestState')
            ->andReturn(null);

        $this->openWeatherServiceMock
            ->shouldReceive('locationExists')
            ->with('TestCity', 'TestState')
            ->andReturn(true);

        $this->locationServiceMock
            ->shouldReceive('createLocation')
            ->with('TestCity', 'TestState')
            ->andReturn($location = Location::factory()->make(['id' => 1]));

        $this->locationServiceMock
            ->shouldReceive('isUserAttached')
            ->with($location, $user->id)
            ->andReturn(false);

        $this->locationServiceMock
            ->shouldReceive('attachUser')
            ->with($location, $user->id);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(LocationResource::class, $response);
        $this->assertEquals(1, $response->id);
    }

    /**
     * Test that adding a location when the user has already saved 3 locations throws an exception.
     */
    public function testStoreExceedingLocationLimit()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('You can only save 3 locations');

        $user = User::factory()->make(['id' => 1]);
        $request = Request::create('/locations', 'POST', ['city' => 'TestCity']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $this->locationServiceMock
            ->shouldReceive('countLocationsByUser')
            ->with($user->id)
            ->andReturn(3);

        $this->controller->store($request);
    }

    /**
     * Test that a valid location can be deleted by the user.
     */
    public function testDestroy()
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;

        $location = Location::factory()->make(['id' => 1]);

        $this->locationServiceMock
            ->shouldReceive('getLocationById')
            ->with($location->id)
            ->andReturn($location);

        $user->shouldReceive('cannot')
            ->with('delete', $location)
            ->andReturn(false);

        $this->locationServiceMock
            ->shouldReceive('detachUser')
            ->with($location, $user->id);

        $request = Request::create('/locations/1', 'DELETE');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $this->controller->destroy($request, $location->id);

        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * Test that attempting to delete a location without proper authorization
     * results in a 403 HttpException.
     */
    public function testDestroyUnauthorized()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('You cannot delete this location');

        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;

        $location = Location::factory()->make(['id' => 1]);

        $this->locationServiceMock
            ->shouldReceive('getLocationById')
            ->with($location->id)
            ->andReturn($location);

        $user->shouldReceive('cannot')
            ->with('delete', $location)
            ->andReturn(true);

        $request = Request::create('/locations/1', 'DELETE');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $this->controller->destroy($request, $location->id);
    }

}
