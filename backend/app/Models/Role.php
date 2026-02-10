<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Role
 * 
 * Role for role-based access control (RBAC).
 * Each user can have multiple roles (admin, manager, user, etc.)
 * 
 * Security: Server-side authorization via roles and permissions
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Role has many users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Role has many permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    /**
     * Grant a permission to this role
     */
    public function grantPermission(string $permission): self
    {
        $permission = Permission::where('name', $permission)->firstOrFail();
        
        if (!$this->hasPermission($permission)) {
            $this->permissions()->attach($permission);
        }

        return $this;
    }

    /**
     * Revoke a permission from this role
     */
    public function revokePermission(string $permission): self
    {
        $permission = Permission::where('name', $permission)->firstOrFail();
        $this->permissions()->detach($permission);

        return $this;
    }

    /**
     * Check if role has a permission
     */
    public function hasPermission(Permission|string $permission): bool
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
            if (!$permission) {
                return false;
            }
        }

        return $this->permissions->contains('id', $permission->id);
    }
}
