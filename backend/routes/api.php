<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are versioned under /api/v1
| Public routes do not require authentication
| Protected routes require valid Sanctum token
|
| Security:
| - All routes are HTTPS-only in production
| - CORS restricted to frontend domain
| - Rate limiting applied per route
| - CSRF protection enabled
|
*/

Route::prefix('api/v1')->group(function () {

    // ==================== PUBLIC AUTHENTICATION ROUTES ====================
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1');  // 5 attempts per minute

        Route::post('register', [AuthController::class, 'register'])
            ->middleware('throttle:3,1');  // 3 attempts per minute

        Route::post('refresh', [AuthController::class, 'refresh'])
            ->middleware(['throttle:10,1']);  // 10 per minute

        Route::post('logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum');
    });

    // ==================== PROTECTED ROUTES ====================
    Route::middleware('auth:sanctum')->group(function () {

        // Auth Routes
        Route::prefix('auth')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
        });

        // User Routes
        Route::apiResource('users', UserController::class)
            ->middleware('throttle:60,1');  // 60 per minute

    });

    // ==================== HEALTH CHECK ====================
    Route::get('health', fn () => response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]));

});
