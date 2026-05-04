@extends('layouts.app')
@section('title', 'Agent Dashboard')

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
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(201,168,76,.1);color:var(--gold);">
                <i class="fas fa-ticket"></i>
            </div>
            <div class="stat-label">My Tickets</div>
            <div class="stat-value">—</div>
            <div class="stat-change neutral"><i class="fas fa-clock"></i> Coming soon</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,.1);color:var(--success);">
                <i class="fas fa-circle-check"></i>
            </div>
            <div class="stat-label">Resolved</div>
            <div class="stat-value">—</div>
            <div class="stat-change neutral"><i class="fas fa-clock"></i> Coming soon</div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-head"><h3>Welcome, {{ auth()->user()->name }}</h3></div>
    <div class="card-body-pad">
        <p class="text-muted mb-0" style="font-size:.9rem;">You are logged in as <strong>Agent</strong>.</p>
    </div>
</div>

@endsection