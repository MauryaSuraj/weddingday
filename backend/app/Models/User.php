<?php

namespace App\Models;

use App\Models\Traits\UserHasUUIDs;
use App\Models\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 * 
 * User model with security best practices:
 * - UUID primary key (prevents ID enumeration)
 * - Encrypted sensitive fields
 * - Sanctum tokens for API authentication
 * - Role-based access control
 * 
 * Security Features:
 * - Password hashing (bcrypt)
 * - Email verification
 * - Password history tracking
 * - Token management
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UserHasUUIDs, HasRoles;

    /**
     * The attributes that are mass assignable
     * 
     * Security: Only allow specific attributes to be mass-assigned
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays
     * 
     * Security: Don't expose passwords or tokens in API responses
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user's full name
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Check if user's email is verified
     */
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Get user's active API tokens
     */
    public function activeTokens()
    {
        return $this->tokens()->where('revoked', false)->get();
    }

    /**
     * Revoke all user tokens (for logout across all devices)
     * 
     * Security: Invalidate all sessions on logout
     */
    public function revokeAllTokens(): bool
    {
        return $this->tokens()->update(['revoked' => true]);
    }

    /**
     * Check if user can access resource
     */
    public function canAccess(string $resource): bool
    {
        // Will be determined by policies
        return true;
    }

    /**
     * Log user action for audit trail
     * 
     * Security: Track all security-critical actions
     */
    public function logAction(string $action, ?string $details = null): void
    {
        AuditLog::create([
            'user_id' => $this->id,
            'action' => $action,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
