<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class SecurityLog extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Append-Only Enforcement
    |--------------------------------------------------------------------------
    | This model must never be updated or deleted. It is an audit trail.
    | Any attempt to update or delete throws immediately so bugs are caught
    | at development time, not silently in production.
    */

    // No updated_at column on this table
    public const UPDATED_AT = null;

    protected $fillable = [
        'event',
        'level',
        'user_id',
        'user_email',
        'ip_address',
        'user_agent',
        'path',
        'method',
        'context',
    ];

    protected $casts = [
        'context'    => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Prevent any update to this model.
     * Security audit logs are immutable.
     */
    public static function boot(): void
    {
        parent::boot();

        static::updating(function () {
            throw new LogicException('SecurityLog records are immutable and cannot be updated.');
        });

        static::deleting(function () {
            throw new LogicException('SecurityLog records cannot be deleted. Use the archive process.');
        });
    }
}