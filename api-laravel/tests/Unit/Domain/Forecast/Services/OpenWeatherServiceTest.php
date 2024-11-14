<?php

namespace Tests\Unit\Domain\Forecast\Services;

use Tests\TestCase;
use App\Domain\Forecast\Services\OpenWeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class OpenWeatherServiceTest extends TestCase
{
    /**
     * Tests that the method returns a collection when the external API
     */
    public function testGetWeatherConditionsByCitySuccessfulResponse()
    {
        $mockResponseData = [
            'list' => [
                [
                    'main' => ['temp' => 25],
                    'dt_txt' => '2024-11-10 12:00:00',
                    'weather' => [['description' => 'clear sky', 'icon' => '01d']]
                ],
                [
                    'main' => ['temp' => 18],
                    'dt_txt' => '2024-11-10 18:00:00',
                    'weather' => [['description' => 'cloudy', 'icon' => '02n']]
                ],
            ]
        ];

        Http::fake([
            '*' => Http::response($mockResponseData, 200)
        ]);

        $service = new OpenWeatherService();

        $result = $service->getWeatherConditionsByCity('Test City', 'Test State', 10);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertEquals(25, $result[0]['temperature']);
        $this->assertEquals('2024-11-10 12:00:00', $result[0]['condition_at']);
        $this->assertEquals('clear sky', $result[0]['description']);
        $this->assertStringContainsString('/01d.png', $result[0]['icon']);
    }

    /**
     * Tests that the method returns an empty collection when the external API
     * returns a 500 error.
     */
    public function testGetWeatherConditionsByCityFailedResponse()
    {

        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $service = new OpenWeatherService();

        $result = $service->getWeatherConditionsByCity('Test City', 'Test State', 10);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEmpty($result);
    }

    /**
     * Tests that the method returns true when the external API returns a 200
     * response and a not empty array.
     */
    public function testLocationExistsSuccessfulResponse()
    {
        $mockResponseData = [
            ['name' => 'Test City']
        ];

        Http::fake([
            '*' => Http::response($mockResponseData, 200)
        ]);

        $service = new OpenWeatherService();

        $result = $service->locationExists('Test City', 'Test State');

        $this->assertTrue($result);
    }

    /**
     * Tests that the method returns false when the external API returns a 500
     * response.
     */
    public function testLocationExistsFailedResponse()
    {
        Http::fake([
            '*' => Http::response([], 500)
        ]);

        $service = new OpenWeatherService();

        $result = $service->locationExists('Test City', 'Test State');

        $this->assertFalse($result);
    }

    /**
     * Tests that the method returns false when the external API returns a 200
     * response and an empty array.
     */
    public function testLocationDoesNotExistsResponse()
    {
        Http::fake([
            '*' => Http::response([], 200)
        ]);

        $service = new OpenWeatherService();

        $result = $service->locationExists('Test City', 'Test State');

        $this->assertFalse($result);
    }
}
