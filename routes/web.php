<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\DashboardController;

// Welcome route
Route::get('/', function () {
    return view('welcome');
});


// 🔹 Super-Admin Route Group
Route::middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminDashboardController::class, 'index'])
        ->name('super-admin.dashboard');
});


// 🔹 Admin Route Group
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});


// 🔹 Agent Route Group
Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])
        ->name('agent.dashboard');
});


// 🔹 Default User Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});