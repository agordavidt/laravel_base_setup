<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



// Archive old security logs on the first day of every month at 02:00
// Running at 2am minimises overlap with peak traffic
Schedule::command('security:archive-logs')
    ->monthlyOn(1, '02:00')
    ->withoutOverlapping()   // never run two instances simultaneously
    ->runInBackground()      // does not block other scheduled jobs
    ->emailOutputOnFailure(env('SECURITY_ALERT_EMAIL')); // notify if it fails
