<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuthController
 * 
 * Handles authentication endpoints:
 * - POST /api/v1/auth/login
 * - POST /api/v1/auth/register
 * - POST /api/v1/auth/refresh
 * - POST /api/v1/auth/logout
 * - GET /api/v1/auth/me
 * 
 * Security:
 * - All endpoints validated via FormRequests
 * - Rate limited at middleware level
 * - Passwords never returned in responses
 * - CSRF protection enabled
 */
class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    /**
     * Login endpoint
     * 
     * POST /api/v1/auth/login
     * 
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Attempt login
        $user = $this->authService->attemptLogin(
            $request->email,
            $request->password
        );

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Invalid email or password.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 401);
        }

        // Generate token
        $token = $this->authService->generateToken($user);

        // Log successful login
        $user->logAction('user.login');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
            ],
            'timestamp' => now()->toIso8601String(),
        ])->cookie(
            name: 'sanctum_api_token',
            value: $token,
            minutes: 1440,  // 24 hours
            path: '/',
            domain: config('app.domain'),
            secure: config('app.env') === 'production',
            httpOnly: true,
            sameSite: 'strict'
        );
    }

    /**
     * Register endpoint
     * 
     * POST /api/v1/auth/register
     * 
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => new UserResource($user),
                    'message' => 'Registration successful. Please log in.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'REGISTRATION_FAILED',
                    'message' => 'Registration failed. Please try again.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 422);
        }
    }

    /**
     * Refresh token endpoint
     * 
     * POST /api/v1/auth/refresh
     * Requires authentication
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'Unauthenticated.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 401);
        }

        // Generate new token
        $token = $this->authService->refreshToken($user);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'expires_in' => 1440,  // minutes
            ],
            'timestamp' => now()->toIso8601String(),
        ])->cookie(
            name: 'sanctum_api_token',
            value: $token,
            minutes: 1440,
            path: '/',
            domain: config('app.domain'),
            secure: config('app.env') === 'production',
            httpOnly: true,
            sameSite: 'strict'
        );
    }

    /**
     * Logout endpoint
     * 
     * POST /api/v1/auth/logout
     * Requires authentication
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'Unauthenticated.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 401);
        }

        // Logout user
        $this->authService->logout($user);

        return response()->json([
            'success' => true,
            'data' => ['message' => 'Logged out successfully.'],
            'timestamp' => now()->toIso8601String(),
        ])->cookie(
            name: 'sanctum_api_token',
            value: '',
            minutes: -1,  // Delete cookie
            path: '/',
            domain: config('app.domain'),
            secure: config('app.env') === 'production',
            httpOnly: true,
            sameSite: 'strict'
        );
    }

    /**
     * Get current authenticated user
     * 
     * GET /api/v1/auth/me
     * Requires authentication
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'Unauthenticated.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
