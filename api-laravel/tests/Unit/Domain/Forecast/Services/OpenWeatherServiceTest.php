<?php

namespace Tests\Unit\Domain\Forecast\Services;

use Tests\TestCase;
use App\Domain\Forecast\Services\OpenWeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class OpenWeatherServiceTest extends TestCase
{
    /**
     * @test
     * @covers \App\Domain\Forecast\Services\OpenWeatherService
     * @covers \App\Domain\Forecast\Services\OpenWeatherService::getWeatherConditionsByCity
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
     * @test
     * @covers \App\Domain\Forecast\Services\OpenWeatherService
     * @covers \App\Domain\Forecast\Services\OpenWeatherService::getWeatherConditionsByCity
     *
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
     * @test
     * @covers \App\Domain\Forecast\Services\OpenWeatherService
     * @covers \App\Domain\Forecast\Services\OpenWeatherService::locationExists
     *
     * Tests that the method returns true when the external API returns a 200
     * response.
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
     * @test
     * @covers \App\Domain\Forecast\Services\OpenWeatherService
     * @covers \App\Domain\Forecast\Services\OpenWeatherService::locationExists
     *
     * Tests that the method returns false when the external API returns a 404
     * response.
     */
    public function testLocationExistsFailedResponse()
    {
        Http::fake([
            '*' => Http::response([], 404)
        ]);

        $service = new OpenWeatherService();

        $result = $service->locationExists('Test City', 'Test State');

        $this->assertFalse($result);
    }
}
