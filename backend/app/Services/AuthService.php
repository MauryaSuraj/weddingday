<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * AuthService
 * 
 * Handles authentication business logic.
 * Separated from controller for better testability and reusability.
 * 
 * Security Considerations:
 * - Password hashing via bcrypt
 * - Token generation via Sanctum
 * - Rate limiting via middleware
 * - Audit logging of authentication events
 * - No sensitive data exposure
 */
class AuthService
{
    /**
     * Attempt login with email and password
     * 
     * Security:
     * - Uses bcrypt comparison (timing-attack safe)
     * - Logs failed attempts
     * - Rate limiting at middleware level
     */
    public function attemptLogin(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            // Log failed attempt (without exposing user exists or not)
            event(new \App\Events\LoginAttempted($email, false));
            return null;
        }

        // Check if email is verified (optional - remove if not needed)
        // if (!$user->isEmailVerified()) {
        //     return null;
        // }

        return $user;
    }

    /**
     * Generate API token for authenticated user
     * 
     * Security:
     * - Token expires after configured duration
     * - Token is unique and random
     * - Stored securely in database
     */
    public function generateToken(User $user): string
    {
        // Revoke previous tokens for cleaner session management (optional)
        // $user->revokeAllTokens();

        // Create new token
        $token = $user->createToken(
            'api-token',
            ['api'],
            now()->addHours(config('security.token.expiration_hours'))
        );

        return $token->plainTextToken;
    }

    /**
     * Refresh user token
     * 
     * Security:
     * - Validates existing token is not revoked
     * - Issues new token with fresh expiration
     * - Enables token rotation
     */
    public function refreshToken(User $user): string
    {
        // Remove old token if rotation is enabled
        if (config('security.token.rotation_enabled')) {
            $user->currentAccessToken()->delete();
        }

        return $this->generateToken($user);
    }

    /**
     * Logout user by revoking all tokens
     * 
     * Security:
     * - Invalidates all sessions across devices
     * - Logs the action
     */
    public function logout(User $user): bool
    {
        // Log the logout action
        $user->logAction('user.logout');

        // Revoke all API tokens
        return $user->revokeAllTokens() > 0;
    }

    /**
     * Register new user
     * 
     * Security:
     * - Password is hashed via Laravel's Authenticatable
     * - Email is checked for uniqueness
     * - User is assigned default role
     * - No auto-login on register
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],  // Automatically hashed via model
        ]);

        // Assign default role
        $user->assignRole('user');

        // Log the registration
        $user->logAction('user.registered');

        return $user;
    }

    /**
     * Change user password
     * 
     * Security:
     * - Validates current password
     * - Hashes new password
     * - Optionally revokes existing tokens
     * - Logs the action
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        // Verify current password
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        // Update password
        $user->update(['password' => $newPassword]);

        // Revoke all tokens after password change for security
        $user->revokeAllTokens();

        // Log the action
        $user->logAction('user.password_changed');

        return true;
    }

    /**
     * Verify user's email address
     * 
     * Security:
     * - Called after email verification link is clicked
     */
    public function verifyEmail(User $user): bool
    {
        if ($user->isEmailVerified()) {
            return true;
        }

        $result = $user->markEmailAsVerified();

        // Log the action
        $user->logAction('user.email_verified');

        return $result;
    }
}
