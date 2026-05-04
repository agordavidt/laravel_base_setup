@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb"><li class="breadcrumb-item">Home</li><li class="breadcrumb-item active">Dashboard</li></ol>
        </nav>
        <h1>Dashboard</h1>
    </div>
</div>

<div class="content-card">
    <div class="card-head"><h3>Welcome back, {{ auth()->user()->name }} 👋</h3></div>
    <div class="card-body-pad">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div style="background:var(--bg);border-radius:.65rem;padding:1.2rem;">
                    <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:.4rem;">Account Status</div>
                    <span class="status-badge badge-success">Active & Verified</span>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div style="background:var(--bg);border-radius:.65rem;padding:1.2rem;">
                    <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);margin-bottom:.4rem;">Member Since</div>
                    <div style="font-weight:600;">{{ auth()->user()->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection