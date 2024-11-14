<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\AuthController;
use App\Domain\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Mockery;
use App\Domain\User\Models\User;

class AuthControllerTest extends TestCase
{
    protected $userServiceMock;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userServiceMock = Mockery::mock(UserService::class);
        $this->controller = new AuthController($this->userServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testLoginSuccess()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $user = User::factory()->make(['email' => 'test@example.com']);
        $mockToken = 'mocked-token';

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'password'])
            ->andReturn(true);

        $this->userServiceMock
            ->shouldReceive('getUserByEmail')
            ->once()
            ->with('test@example.com')
            ->andReturn($user);

        $this->userServiceMock
            ->shouldReceive('createAuthToken')
            ->once()
            ->with($user)
            ->andReturn($mockToken);

        $response = $this->controller->login($request);

        $responseData = $response->getData(true)['data'];

        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals($mockToken, $responseData['token']);
        $this->assertArrayHasKey('user', $responseData);
    }


    public function testLoginInvalidCredentials()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Invalid credentials');

        $request = Request::create('/login', 'POST', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'wrong-password'])
            ->andReturn(false);

        $this->controller->login($request);
    }

    /**
     * Test that a user can be logged out, removing their tokens.
     */
    public function testLogout()
    {
        $user = User::factory()->make();
        $request = Request::create('/logout', 'POST');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $this->userServiceMock
            ->shouldReceive('deleteTokens')
            ->once()
            ->with($user);

        $response = $this->controller->logout($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
