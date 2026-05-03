@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item">Home</li><li class="breadcrumb-item active">Dashboard</li></ol>
        </nav>
        <h1>Dashboard</h1>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(201,168,76,.1);color:var(--gold);">
                <i class="fas fa-headset"></i>
            </div>
            <div class="stat-label">Agents</div>
            <div class="stat-value">{{ $stats['agents'] }}</div>
            <div class="stat-change neutral"><i class="fas fa-minus"></i> No change</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(59,130,246,.1);color:#3b82f6;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Users</div>
            <div class="stat-value">{{ $stats['users'] }}</div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> Active accounts</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,.1);color:var(--success);">
                <i class="fas fa-ticket"></i>
            </div>
            <div class="stat-label">Open Tickets</div>
            <div class="stat-value">—</div>
            <div class="stat-change neutral"><i class="fas fa-clock"></i> Coming soon</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,.1);color:var(--danger);">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-label">Reports</div>
            <div class="stat-value">—</div>
            <div class="stat-change neutral"><i class="fas fa-clock"></i> Coming soon</div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-head">
        <h3>Welcome, {{ auth()->user()->name }}</h3>
    </div>
    <div class="card-body-pad">
        <p class="text-muted mb-0" style="font-size:.9rem;">
            You are logged in as <strong>Admin</strong>. Use the navigation to manage agents and users.
        </p>
    </div>
</div>

@endsection
