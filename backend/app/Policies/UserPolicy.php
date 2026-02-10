<?php

namespace App\Policies;

use App\Models\User;

/**
 * UserPolicy
 * 
 * Authorization policy for User-related actions.
 * Laravel automatically applies these policies via authorization.
 * 
 * Usage:
 * - Gate::authorize('view', $user);
 * - $this->authorize('view', $user);
 * 
 * Security:
 * - Server-side authorization
 * - Fine-grained access control
 * - Prevents unauthorized access
 */
class UserPolicy
{
    /**
     * Determine whether the user can view any users
     * Only admins can list users
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view a specific user
     * Can view own profile or be admin
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create users
     * Only admins can create users
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update a user
     * Can update own profile or be admin
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete a user
     * Only admins can delete users
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can permanently delete a user
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can manage roles for a user
     * Only admins can assign roles
     */
    public function assignRoles(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
