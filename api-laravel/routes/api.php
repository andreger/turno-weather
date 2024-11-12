<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('locations', LocationController::class)->only(['index', 'store', 'destroy']);
});
