<?php

namespace Tests\Unit\Domain\Location\Repositories;

use Tests\TestCase;
use App\Domain\User\Models\User;
use App\Domain\Location\Models\Location;
use App\Domain\Location\Repositories\LocationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

class LocationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listLocationsByUser method.
     */
    public function testListLocationsByUser()
    {
        $user = User::factory()->create();
        $locations = Location::factory()->count(3)->create();
        foreach ($locations as $location) {
            $location->users()->attach($user->id);
        }

        $repository = new LocationRepository();
        $result = $repository->listLocationsByUser($user->id);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        $this->assertEquals($locations->pluck('id')->sort()->values(), $result->pluck('id')->sort()->values());
    }

    /**
     * Test countLocationsByUser method.
     */
    public function testCountLocationsByUser()
    {
        $user = User::factory()->create();
        $locations = Location::factory()->count(2)->create();
        foreach ($locations as $location) {
            $location->users()->attach($user->id);
        }

        $repository = new LocationRepository();
        $count = $repository->countLocationsByUser($user->id);

        $this->assertEquals(2, $count);
    }

    /**
     * Test createLocation method.
     */
    public function testCreateLocation()
    {
        $repository = new LocationRepository();
        $location = $repository->createLocation('CityName', 'StateName');

        $this->assertInstanceOf(Location::class, $location);
        $this->assertDatabaseHas('locations', [
            'city' => 'CityName',
            'state' => 'StateName',
        ]);
    }

    /**
     * Test getLocationById method.
     */
    public function testGetLocationById()
    {
        $location = Location::factory()->create();

        $repository = new LocationRepository();
        $result = $repository->getLocationById($location->id);

        $this->assertInstanceOf(Location::class, $result);
        $this->assertEquals($location->id, $result->id);
    }

    /**
     * Test findLocation method.
     */
    public function testFindLocation()
    {
        $location = Location::factory()->create(['city' => 'TestCity', 'state' => 'TestState']);

        $repository = new LocationRepository();
        $result = $repository->findLocation('TestCity', 'TestState');

        $this->assertInstanceOf(Location::class, $result);
        $this->assertEquals($location->id, $result->id);
    }

    /**
     * Test attachUser method.
     */
    public function testAttachUserToLocation()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();

        $repository = new LocationRepository();
        $repository->attachUser($location, $user->id);

        $this->assertTrue($location->users()->where('user_id', $user->id)->exists());
    }

    /**
     * Test detachUser method.
     */
    public function testDetachUserFromLocation()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();
        $location->users()->attach($user->id);

        $repository = new LocationRepository();
        $repository->detachUser($location, $user->id);

        $this->assertFalse($location->users()->where('user_id', $user->id)->exists());
    }

    /**
     * Test isUserAttached method.
     */
    public function testIsUserAttachedToLocation()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create();
        $location->users()->attach($user->id);

        $repository = new LocationRepository();
        $isAttached = $repository->isUserAttached($location, $user->id);

        $this->assertTrue($isAttached);
    }
}
