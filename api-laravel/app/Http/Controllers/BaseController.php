<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    public function sendResponse($data, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data
        ], $statusCode);
    }

    public function sendNoContentResponse(): Response
    {
        return response()->noContent();
    }

    public function sendError(string $error, int $statusCode): JsonResponse
    {
        $response = [
            'error' => $error,
        ];

        return response()->json($response, $statusCode);
    }
}
