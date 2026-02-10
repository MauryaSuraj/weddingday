<?php

namespace App\Models\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HasRoles
 * 
 * Implements role-based access control (RBAC) for users.
 * Allows assignment and verification of roles and permissions.
 * 
 * Security: Server-side authorization via policies and traits
 */
trait HasRoles
{
    /**
     * User has many roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * Assign a role to user
     */
    public function assignRole(string $role): self
    {
        $role = Role::where('name', $role)->firstOrFail();
        
        if (!$this->hasRole($role)) {
            $this->roles()->attach($role);
        }

        return $this;
    }

    /**
     * Remove a role from user
     */
    public function removeRole(string $role): self
    {
        $role = Role::where('name', $role)->firstOrFail();
        $this->roles()->detach($role);

        return $this;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(Role|string $role): bool
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
            if (!$role) {
                return false;
            }
        }

        return $this->roles->contains('id', $role->id);
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles(...$roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all permissions through roles
     */
    public function permissions()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->contains('name', $permission);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
