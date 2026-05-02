<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class SecurityAlert extends Model
{
    /*
    |--------------------------------------------------------------------------
    | No updated_at — acknowledged_at tracks state, nothing else changes.
    |--------------------------------------------------------------------------
    */
    public const UPDATED_AT = null;

    protected $fillable = [
        'alert_type',
        'ip_address',
        'event_count',
        'window_minutes',
        'context',
        'triggered_at',
    ];

    protected $casts = [
        'context'          => 'array',
        'triggered_at'     => 'datetime',
        'acknowledged_at'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Immutability — the only permitted update is setting acknowledged_at.
    | Any other field change throws immediately so bugs surface at dev time.
    |--------------------------------------------------------------------------
    */
    protected static function boot(): void
    {
        parent::boot();

        static::updating(function (SecurityAlert $alert) {
            $dirty = array_keys($alert->getDirty());
            $permitted = ['acknowledged_at', 'acknowledged_by'];

            $illegal = array_diff($dirty, $permitted);

            if (!empty($illegal)) {
                throw new LogicException(
                    'SecurityAlert records are immutable except for acknowledgement. ' .
                    'Attempted to modify: ' . implode(', ', $illegal)
                );
            }
        });

        static::deleting(function () {
            throw new LogicException(
                'SecurityAlert records cannot be deleted. They are a permanent audit trail.'
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeUnacknowledged($query)
    {
        return $query->whereNull('acknowledged_at');
    }

    public function scopeAcknowledged($query)
    {
        return $query->whereNotNull('acknowledged_at');
    }

    public function scopeForIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isAcknowledged(): bool
    {
        return $this->acknowledged_at !== null;
    }

    public function humanLabel(): string
    {
        return match ($this->alert_type) {
            'failed_login_threshold'     => 'Repeated Failed Login Attempts',
            'lockout_threshold'          => 'Repeated Account Lockouts',
            'access_violation_threshold' => 'Repeated Access Control Violations',
            default                      => ucwords(str_replace('_', ' ', $this->alert_type)),
        };
    }
}