<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

/**
 * Trait UserHasUUIDs
 * 
 * Automatically generate UUIDs for user model instead of auto-increment IDs.
 * This improves security by making IDs non-predictable.
 * 
 * Security: UUIDs prevent ID enumeration attacks
 */
trait UserHasUUIDs
{
    /**
     * Boot the trait
     */
    protected static function bootUserHasUUIDs()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type
     */
    public function getKeyType()
    {
        return 'string';
    }
}
