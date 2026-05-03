@extends('layouts.app')
@section('title', 'Super Admin Dashboard')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h1>Dashboard</h1>
    </div>
    <div class="d-flex gap-2">
        <button class="btn-ghost"><i class="fas fa-download me-1"></i> Export</button>
        <button class="btn-gold"><i class="fas fa-plus me-1"></i> Add User</button>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(201,168,76,.1); color:var(--gold);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> 12.4% this month</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(59,130,246,.1); color:#3b82f6;">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-label">Admins</div>
            <div class="stat-value">{{ $stats['admins'] }}</div>
            <div class="stat-change neutral"><i class="fas fa-minus"></i> No change</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,.1); color:var(--success);">
                <i class="fas fa-headset"></i>
            </div>
            <div class="stat-label">Agents</div>
            <div class="stat-value">{{ $stats['agents'] }}</div>
            <div class="stat-change up"><i class="fas fa-arrow-up"></i> 2 new this week</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,.1); color:var(--danger);">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div class="stat-label">Security Alerts</div>
            <div class="stat-value">{{ \App\Models\SecurityAlert::whereNull('acknowledged_at')->count() }}</div>
            <div class="stat-change {{ \App\Models\SecurityAlert::whereNull('acknowledged_at')->count() > 0 ? 'down' : 'up' }}">
                <i class="fas fa-{{ \App\Models\SecurityAlert::whereNull('acknowledged_at')->count() > 0 ? 'exclamation-triangle' : 'check' }}"></i>
                {{ \App\Models\SecurityAlert::whereNull('acknowledged_at')->count() > 0 ? 'Needs attention' : 'All clear' }}
            </div>
        </div>
    </div>
</div>

{{-- Recent Users + Quick Stats --}}
<div class="row g-3">

    {{-- Recent Users Table --}}
    <div class="col-12 col-lg-8">
        <div class="content-card">
            <div class="card-head">
                <h3>Recent Users</h3>
                <a href="#" class="btn-ghost" style="font-size:.8rem; padding:.35rem .9rem;">View All</a>
            </div>
            <div class="table-responsive">
                <table class="fr-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\User::with('roles')->latest()->limit(8)->get() as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:32px;height:32px;border-radius:50%;background:var(--gold);color:var(--navy);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:.85rem;">{{ $user->name }}</div>
                                        <div style="font-size:.75rem;color:var(--text-muted);">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php $role = $user->getRoleNames()->first() ?? 'user'; @endphp
                                <span class="status-badge {{ match($role) {
                                    'super-admin' => 'badge-danger',
                                    'admin'       => 'badge-info',
                                    'agent'       => 'badge-gold',
                                    default       => 'badge-muted'
                                } }}">{{ str_replace('-', ' ', $role) }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->email_verified_at ? 'badge-success' : 'badge-warning' }}">
                                    {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Role Distribution + Security Summary --}}
    <div class="col-12 col-lg-4 d-flex flex-column gap-3">

        {{-- Role Distribution --}}
        <div class="content-card">
            <div class="card-head"><h3>Role Distribution</h3></div>
            <div class="card-body-pad">
                @php
                    $roles = [
                        ['label' => 'Super Admin', 'count' => \App\Models\User::role('super-admin')->count(), 'color' => '#ef4444'],
                        ['label' => 'Admin',       'count' => $stats['admins'],                               'color' => '#3b82f6'],
                        ['label' => 'Agent',       'count' => $stats['agents'],                               'color' => 'var(--gold)'],
                        ['label' => 'User',        'count' => $stats['users'],                                'color' => '#10b981'],
                    ];
                    $total = max($stats['total_users'], 1);
                @endphp
                @foreach ($roles as $r)
                    @php $pct = round(($r['count'] / $total) * 100, 1); @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:.82rem;">
                            <span style="font-weight:500;">{{ $r['label'] }}</span>
                            <span style="color:var(--text-muted);">{{ $r['count'] }} ({{ $pct }}%)</span>
                        </div>
                        <div style="height:6px;background:var(--bg);border-radius:3px;overflow:hidden;">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $r['color'] }};border-radius:3px;transition:width .6s;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Security Quick Status --}}
        <div class="content-card">
            <div class="card-head">
                <h3>Security</h3>
                <a href="{{ route('super-admin.security.index') }}" style="font-size:.78rem;color:var(--gold);text-decoration:none;">View all →</a>
            </div>
            <div class="card-body-pad">
                @php
                    $failedToday  = \App\Models\SecurityLog::where('event','auth.login_failed')->where('created_at','>=',now()->subDay())->count();
                    $activeAlerts = \App\Models\SecurityAlert::whereNull('acknowledged_at')->count();
                @endphp
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span style="font-size:.85rem;">Failed Logins (24h)</span>
                    <span class="status-badge {{ $failedToday > 10 ? 'badge-danger' : ($failedToday > 0 ? 'badge-warning' : 'badge-success') }}">{{ $failedToday }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span style="font-size:.85rem;">Unreviewed Alerts</span>
                    <span class="status-badge {{ $activeAlerts > 0 ? 'badge-danger' : 'badge-success' }}">{{ $activeAlerts > 0 ? $activeAlerts . ' pending' : 'All clear' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
