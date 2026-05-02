<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SecurityMonitoringController;
use App\Http\Controllers\SuperAdmin\AcknowledgeAlertController;
use App\Http\Controllers\Admin\DashboardController      as AdminDashboardController;
use App\Http\Controllers\Agent\DashboardController      as AgentDashboardController;
use App\Http\Controllers\DashboardController;

// ── Public ──────────────────────────────────────────────────────────────────
Route::get('/', fn () => view('welcome'))->name('home');



// ── Authenticated ──────────────────────────────────────────────────────────── 
// // TrafficController runs once here and covers every authenticated route: 
// // - Self-heals wrong-role dashboard navigation 
// // - Sets cache-control headers on every response (replaces PreventBackHistory) 
// // - Scales: new roles only need a line in TrafficController::$roleMap


Route::middleware(['auth', \App\Http\Middleware\TrafficController::class])
    ->group(function () {

        // ── Super-Admin  ──
        Route::middleware('role:super-admin')
            ->prefix('super-admin')
            ->name('super-admin.')
            ->group(function () {
                Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])
                    ->name('dashboard');

                Route::get('/security', [SecurityMonitoringController::class, 'index'])
                    ->name('security.index');

                Route::get('/security/ip/{ip}', [SecurityMonitoringController::class, 'investigateIp'])
                    ->name('security.ip')
                    ->where('ip', '[0-9a-fA-F.:]+');

                Route::post('/security/alerts/{alert}/acknowledge', [AcknowledgeAlertController::class, 'store'])
                    ->name('security.alerts.acknowledge');

                Route::post('/security/alerts/bulk-acknowledge', [AcknowledgeAlertController::class, 'bulkAcknowledge'])
                    ->name('security.alerts.bulk-acknowledge');
            });

        // ── Admin  ──
        Route::middleware('role:admin')
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                    ->name('dashboard');
            });

        // ── Agent  ──
        Route::middleware(['role:agent', 'verified'])
            ->prefix('agent')
            ->name('agent.')
            ->group(function () {
                Route::get('/dashboard', [AgentDashboardController::class, 'index'])
                    ->name('dashboard');
            });

        // ── Default User  ──
        Route::middleware('verified')
            ->group(function () {
                Route::get('/dashboard', [DashboardController::class, 'index'])
                    ->name('dashboard');
            });
    });