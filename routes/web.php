<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController      as AdminDashboardController;
use App\Http\Controllers\Agent\DashboardController      as AgentDashboardController;
use App\Http\Controllers\DashboardController;

// ── Public ─────────────────────────────────────────────────────────────────
Route::get('/', fn () => view('welcome'))->name('home');



// ── Super-Admin ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:super-admin', \App\Http\Middleware\PreventBackHistory::class])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])
            ->name('dashboard');
    });



// ── Admin ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin', \App\Http\Middleware\PreventBackHistory::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
    });



// ── Agent ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:agent', \App\Http\Middleware\PreventBackHistory::class])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])
            ->name('dashboard');
    });



// ── Default User ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', \App\Http\Middleware\PreventBackHistory::class])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
    });