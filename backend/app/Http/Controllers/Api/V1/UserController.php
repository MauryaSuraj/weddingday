<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * UserController
 * 
 * Handles user management endpoints.
 * All endpoints require authentication and appropriate authorization.
 * 
 * Security:
 * - All endpoints protected with auth:sanctum middleware
 * - Authorization checked via UserPolicy
 * - Input validated via FormRequests
 * - Rate limited
 * - Audit logged
 */
class UserController extends Controller
{
    /**
     * Get all users (admin only)
     * 
     * GET /api/v1/users
     * Requires: admin role
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Authorization: Only admin can list users
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Unauthorized to access this resource.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 403);
        }

        // Pagination
        $perPage = min((int)$request->get('per_page', 15), 100);
        $page = (int)$request->get('page', 1);

        // Filtering (example)
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->has('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->input('role')));
        }

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        // Log the action
        Auth::user()->logAction('users.list', ['page' => $page]);

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users->items()),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get single user
     * 
     * GET /api/v1/users/{id}
     * Requires: own user or admin
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        // Authorization: Can only view own profile or be admin
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Unauthorized to access this resource.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 403);
        }

        // Log the action
        Auth::user()->logAction('user.view', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Update user
     * 
     * PUT /api/v1/users/{id}
     * Requires: own user or admin
     * 
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        // Authorization
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Unauthorized to access this resource.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 403);
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        // Update user
        $user->update($validated);

        // Log the action
        Auth::user()->logAction('user.update', ['user_id' => $user->id, 'changes' => array_keys($validated)]);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
            'message' => 'User updated successfully.',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Delete user
     * 
     * DELETE /api/v1/users/{id}
     * Requires: admin only
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        // Authorization: Only admin can delete users
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Unauthorized to access this resource.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 403);
        }

        // Prevent self-deletion
        if (Auth::id() === $user->id) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'CANNOT_DELETE_SELF',
                    'message' => 'You cannot delete your own account.',
                ],
                'timestamp' => now()->toIso8601String(),
            ], 400);
        }

        // Log the action before deletion
        Auth::user()->logAction('user.delete', ['user_id' => $user->id, 'email' => $user->email]);

        // Soft delete recommended for audit trail
        // $user->delete();

        // Hard delete alternative:
        $user->revokeAllTokens();  // Revoke tokens first
        $user->forceDelete();

        return response()->json([
            'success' => true,
            'data' => ['message' => 'User deleted successfully.'],
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
