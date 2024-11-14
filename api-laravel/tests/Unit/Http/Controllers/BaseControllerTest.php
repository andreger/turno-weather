<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseControllerTest extends TestCase
{
    public function testSendResponse()
    {
        $controller = new BaseController();
        $data = ['message' => 'Test response'];
        $statusCode = 200;

        $response = $controller->sendResponse($data, $statusCode);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals(json_encode(['data' => $data]), $response->getContent());
    }

    public function testSendNoContentResponse()
    {
        $controller = new BaseController();

        $response = $controller->sendNoContentResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testSendError()
    {
        $controller = new BaseController();
        $errorMessage = 'An error occurred';
        $statusCode = 400;

        $response = $controller->sendError($errorMessage, $statusCode);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => $errorMessage]), $response->getContent());
    }
}
