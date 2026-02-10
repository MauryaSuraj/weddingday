<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuditLog
 * 
 * Audit log for security-critical actions.
 * Tracks who did what, when, and from where.
 * 
 * Security: Compliance and forensic analysis
 */
class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'action',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'details' => 'json',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user associated with this audit log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
