<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Alert Recipients
    |--------------------------------------------------------------------------
    | The email address that receives security alerts. Set this in your .env.
    | Never hard-code a real email here.
    */
    'alert_email' => env('SECURITY_ALERT_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Suspicious Activity Thresholds (OWASP defaults)
    |--------------------------------------------------------------------------
    */
    'thresholds' => [

        // 5 failed logins per minute per IP → alert
        'failed_logins' => [
            'count'   => 5,
            'minutes' => 1,
        ],

        // 3 lockouts within 60 minutes per IP → alert
        'lockouts' => [
            'count'   => 3,
            'minutes' => 60,
        ],

        // 10 access control violations within 10 minutes per IP → alert
        'access_violations' => [
            'count'   => 10,
            'minutes' => 10,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | High-Value Events (persisted to database)
    |--------------------------------------------------------------------------
    | All events go to the log file. Only these go to the database.
    | Keep this list small to avoid overwhelming the database.
    */
    'db_events' => [
        'auth.login_failed',
        'auth.lockout',
        'auth.password_reset_requested',
        'auth.password_reset_success',
        'auth.registered',
        'access.denied',
        'access.control_violation',
        'error.unhandled_exception',
        'security.alert_triggered',
    ],

    /*
    |--------------------------------------------------------------------------
    | Archive Policy
    |--------------------------------------------------------------------------
    | Records older than this many days are moved to security_logs_archive
    | by the ArchiveSecurityLogs command, keeping the main table lean.
    */
    'archive_after_days' => 90,

];