@extends('layouts.app')
@section('title', 'Security Monitoring')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('super-admin.dashboard') }}" style="color:var(--text-muted);text-decoration:none;">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">Security Monitoring</li>
            </ol>
        </nav>
        <h1>Security Monitoring</h1>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span style="font-size:.78rem;color:var(--text-muted);">
            <i class="fas fa-rotate me-1"></i> {{ now()->format('H:i:s') }}
        </span>
        @if ($activeAlerts->whereNull('acknowledged_at')->count() > 0)
            <form method="POST" action="{{ route('super-admin.security.alerts.bulk-acknowledge') }}">
                @csrf
                <button type="submit" class="btn-ghost" style="font-size:.82rem;">
                    <i class="fas fa-check-double me-1"></i>
                    Acknowledge All ({{ $activeAlerts->whereNull('acknowledged_at')->count() }})
                </button>
            </form>
        @endif
    </div>
</div>

{{-- ── Threat Overview Cards ──────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3">
        <div class="threat-card">            
            <div class="tc-icon" style="background:rgba(239,68,68,.1);">
                <i class="fas fa-right-to-bracket" style="color:#ef4444;"></i>
            </div>
            <div class="tc-label">Failed Logins (24h)</div>
            <div class="tc-value" style="color:#ef4444;">{{ $overview['failed_logins']['value'] }}</div>
            <div class="tc-delta {{ $overview['failed_logins']['direction'] === 'up' ? 'up' : ($overview['failed_logins']['direction'] === 'down' ? 'down' : 'same') }}">
                @if ($overview['failed_logins']['direction'] === 'up')
                    <i class="fas fa-arrow-up"></i> {{ abs($overview['failed_logins']['delta']) }} more than yesterday
                @elseif ($overview['failed_logins']['direction'] === 'down')
                    <i class="fas fa-arrow-down"></i> {{ abs($overview['failed_logins']['delta']) }} fewer than yesterday
                @else
                    <i class="fas fa-minus"></i> Same as yesterday
                @endif
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="threat-card">            
            <div class="tc-icon" style="background:rgba(34,197,94,.1);">
                <i class="fas fa-bell" style="color:var(--primary);"></i>
            </div>
            <div class="tc-label">Unreviewed Alerts</div>
            <div class="tc-value" style="color:var(--primary);">{{ $overview['active_alerts']['value'] }}</div>
            <div class="tc-delta {{ $overview['active_alerts']['value'] > 0 ? 'up' : 'down' }}">
                @if ($overview['active_alerts']['value'] > 0)
                    <i class="fas fa-exclamation-triangle"></i> Requires attention
                @else
                    <i class="fas fa-check-circle"></i> All clear
                @endif
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="threat-card">         
            <div class="tc-icon" style="background:rgba(245,158,11,.1);">
                <i class="fas fa-ban" style="color:#f59e0b;"></i>
            </div>
            <div class="tc-label">Access Violations (24h)</div>
            <div class="tc-value" style="color:#f59e0b;">{{ $overview['access_violations']['value'] }}</div>
            <div class="tc-delta {{ $overview['access_violations']['direction'] === 'up' ? 'up' : ($overview['access_violations']['direction'] === 'down' ? 'down' : 'same') }}">
                @if ($overview['access_violations']['direction'] === 'up')
                    <i class="fas fa-arrow-up"></i> {{ abs($overview['access_violations']['delta']) }} more than yesterday
                @elseif ($overview['access_violations']['direction'] === 'down')
                    <i class="fas fa-arrow-down"></i> {{ abs($overview['access_violations']['delta']) }} fewer than yesterday
                @else
                    <i class="fas fa-minus"></i> Same as yesterday
                @endif
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="threat-card">            
            <div class="tc-icon" style="background:rgba(139,92,246,.1);">
                <i class="fas fa-triangle-exclamation" style="color:#8b5cf6;"></i>
            </div>
            <div class="tc-label">Unhandled Exceptions (24h)</div>
            <div class="tc-value" style="color:#8b5cf6;">{{ $overview['unhandled_exceptions']['value'] }}</div>
            <div class="tc-delta {{ $overview['unhandled_exceptions']['direction'] === 'up' ? 'up' : ($overview['unhandled_exceptions']['direction'] === 'down' ? 'down' : 'same') }}">
                @if ($overview['unhandled_exceptions']['direction'] === 'up')
                    <i class="fas fa-arrow-up"></i> {{ abs($overview['unhandled_exceptions']['delta']) }} more than yesterday
                @elseif ($overview['unhandled_exceptions']['direction'] === 'down')
                    <i class="fas fa-arrow-down"></i> {{ abs($overview['unhandled_exceptions']['delta']) }} fewer than yesterday
                @else
                    <i class="fas fa-minus"></i> Same as yesterday
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ── Chart + Active Alerts ──────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- 7-Day Auth Health Chart --}}
    <div class="col-12 col-lg-7">
        <div class="content-card h-100">
            <div class="card-head">
                <h3>Authentication Health — 7 Days</h3>
                <div class="d-flex gap-3" style="font-size:.75rem;">
                    <span style="display:flex;align-items:center;gap:.35rem;">
                        <span style="width:14px;height:3px;background:#10b981;border-radius:2px;display:inline-block;"></span>Successful
                    </span>
                    <span style="display:flex;align-items:center;gap:.35rem;">
                        <span style="width:14px;height:3px;background:#ef4444;border-radius:2px;display:inline-block;"></span>Failed
                    </span>
                    <span style="display:flex;align-items:center;gap:.35rem;">
                        <span style="width:14px;height:3px;background:#f59e0b;border-radius:2px;display:inline-block;"></span>Violations
                    </span>
                </div>
            </div>
            {{-- Chart data passed via data attribute — no inline JS needed --}}
            <div class="chart-wrap">
                <canvas id="authHealthChart"
                        data-chart-json="{{ json_encode($authChart) }}">
                </canvas>
            </div>
        </div>
    </div>

    {{-- Active Alerts --}}
    <div class="col-12 col-lg-5">
        <div class="content-card h-100" style="display:flex;flex-direction:column;">
            <div class="card-head">
                <h3>
                    Active Alerts
                    @if ($activeAlerts->count() > 0)
                        <span class="status-badge badge-danger ms-2" style="font-size:.68rem;">
                            {{ $activeAlerts->count() }} pending
                        </span>
                    @endif
                </h3>
            </div>
            <div style="flex:1;overflow-y:auto;max-height:280px;">
                @forelse ($activeAlerts as $alert)
                    <div class="alert-row">
                        <div class="alert-dot {{ $alert->isAcknowledged() ? 'acked' : 'unack' }}"></div>
                        <div class="alert-meta">
                            <div class="a-type">{{ $alert->humanLabel() }}</div>
                            <div class="a-detail">
                                IP:&nbsp;
                                <a href="{{ route('super-admin.security.ip', $alert->ip_address) }}"
                                   style="color:var(--primary);text-decoration:none;font-family:monospace;font-size:.8rem;">
                                    {{ $alert->ip_address }}
                                </a>
                                &middot; {{ $alert->event_count }} events / {{ $alert->window_minutes }}min
                            </div>
                            <div class="a-time">{{ $alert->triggered_at->diffForHumans() }}</div>
                        </div>
                        @if (!$alert->isAcknowledged())
                            <form method="POST"
                                  action="{{ route('super-admin.security.alerts.acknowledge', $alert) }}">
                                @csrf
                                <button type="submit"
                                        class="btn-ghost"
                                        style="font-size:.72rem;padding:.25rem .6rem;"
                                        title="Mark reviewed">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @else
                            <span class="status-badge badge-muted" style="font-size:.68rem;">Reviewed</span>
                        @endif
                    </div>
                @empty
                    <div style="padding:2.5rem;text-align:center;color:var(--text-muted);">
                        <i class="fas fa-shield-check" style="font-size:1.8rem;margin-bottom:.75rem;display:block;color:var(--success);"></i>
                        <div style="font-size:.88rem;font-weight:600;">No active alerts</div>
                        <div style="font-size:.78rem;margin-top:.2rem;">All thresholds within normal range</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ── Top IPs + Account Activity ────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-lg-7">
        <div class="content-card">
            <div class="card-head">
                <h3>Top Offending IPs — Last 24 Hours</h3>
                <span style="font-size:.75rem;color:var(--text-muted);">Click IP to investigate</span>
            </div>
            @if ($topIps->isEmpty())
                <div style="padding:2rem;text-align:center;color:var(--text-muted);font-size:.88rem;">
                    No suspicious activity in the last 24 hours.
                </div>
            @else
            <div class="table-responsive">
                <table class="fr-table">
                    <thead>
                        <tr>
                            <th style="width:28px;">#</th>
                            <th>IP Address</th>
                            <th>Events</th>
                            <th class="hide-mobile">Failed</th>
                            <th class="hide-mobile">Violations</th>
                            <th>Last Seen</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxEvents = $topIps->max('total_events') ?: 1; @endphp
                        @foreach ($topIps as $i => $ip)
                        <tr>
                            <td style="color:var(--text-muted);font-size:.8rem;font-weight:700;">{{ $i + 1 }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:.4rem;">
                                    <code style="font-size:.8rem;background:var(--bg);padding:.15rem .45rem;border-radius:.3rem;">{{ $ip->ip_address }}</code>
                                    <button class="copy-btn" data-copy="{{ $ip->ip_address }}" title="Copy">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <div class="ip-bar-wrap">
                                    <div class="ip-bar" style="width:{{ round(($ip->total_events / $maxEvents) * 100) }}%;"></div>
                                </div>
                            </td>
                            <td style="font-weight:700;">{{ $ip->total_events }}</td>
                            <td class="hide-mobile">
                                @if ($ip->failed_logins > 0)
                                    <span class="status-badge badge-danger">{{ $ip->failed_logins }}</span>
                                @else
                                    <span style="color:var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td class="hide-mobile">
                                @if ($ip->access_violations > 0)
                                    <span class="status-badge badge-warning">{{ $ip->access_violations }}</span>
                                @else
                                    <span style="color:var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td style="font-size:.78rem;color:var(--text-muted);">
                                {{ \Carbon\Carbon::parse($ip->last_seen)->diffForHumans() }}
                            </td>
                            <td>
                                <a href="{{ route('super-admin.security.ip', $ip->ip_address) }}"
                                   class="btn-ghost"
                                   style="font-size:.72rem;padding:.25rem .65rem;">
                                    Investigate
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="content-card h-100">
            <div class="card-head"><h3>Recent Account Activity</h3></div>
            <div style="max-height:320px;overflow-y:auto;">
                @forelse ($accountActivity as $event)
                    @php
                        [$icon, $color] = match($event->event) {
                            'auth.registered'               => ['fas fa-user-plus',  '#10b981'],
                            'auth.password_reset_success'   => ['fas fa-key',        '#3b82f6'],
                            'auth.password_reset_requested' => ['fas fa-envelope',   '#f59e0b'],
                            'auth.lockout'                  => ['fas fa-lock',       '#ef4444'],
                            default                         => ['fas fa-circle-info','#94a3b8'],
                        };
                        $label = match($event->event) {
                            'auth.registered'               => 'New Registration',
                            'auth.password_reset_success'   => 'Password Reset',
                            'auth.password_reset_requested' => 'Reset Requested',
                            'auth.lockout'                  => 'Account Locked Out',
                            default                         => $event->event,
                        };
                    @endphp
                    <div style="display:flex;align-items:flex-start;gap:.85rem;padding:.85rem 1.4rem;border-bottom:1px solid var(--border);">
                        <div style="width:32px;height:32px;border-radius:50%;background:{{ $color }}1a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="{{ $icon }}" style="font-size:.8rem;color:{{ $color }};"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.85rem;font-weight:600;">{{ $label }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">
                                {{ $event->user_email ?? 'Guest' }} &middot; {{ $event->ip_address }}
                            </div>
                        </div>
                        <div style="font-size:.72rem;color:var(--text-muted);white-space:nowrap;">
                            {{ $event->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div style="padding:2rem;text-align:center;color:var(--text-muted);font-size:.88rem;">
                        No recent account activity.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ── Security Event Feed ────────────────────────────────────────────── --}}
<div class="content-card">
    <div class="card-head">
        <h3>Security Event Feed</h3>
        <span style="font-size:.75rem;color:var(--text-muted);">
            {{ $recentEvents->total() }} total events
        </span>
    </div>

    {{-- Filter bar: selects auto-submit, IP field filters live in-page --}}
    <form method="GET" action="{{ route('super-admin.security.index') }}" id="filterForm">
        <div class="filter-bar">
            <i class="fas fa-filter" style="color:var(--text-muted);"></i>

            {{-- Event type — auto-submits on change --}}
            <select name="event" class="filter-select" data-auto-submit>
                <option value="">All Events</option>
                @foreach ($eventTypes as $type)
                    <option value="{{ $type }}" {{ ($filters['event'] ?? '') === $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>

            {{-- Level — auto-submits on change --}}
            <select name="level" class="filter-select" data-auto-submit>
                <option value="">All Levels</option>
                <option value="info"     {{ ($filters['level'] ?? '') === 'info'     ? 'selected' : '' }}>Info</option>
                <option value="warning"  {{ ($filters['level'] ?? '') === 'warning'  ? 'selected' : '' }}>Warning</option>
                <option value="critical" {{ ($filters['level'] ?? '') === 'critical' ? 'selected' : '' }}>Critical</option>
            </select>

            {{-- IP — live client-side filter, also submits for server pagination --}}
            <input type="text"
                   name="ip"
                   class="filter-input"
                   placeholder="Filter by IP…"
                   value="{{ $filters['ip'] ?? '' }}"
                   data-filter-table="#eventTable"
                   data-filter-cols="2">

            {{-- Date range --}}
            <input type="date"
                   name="date_from"
                   class="filter-input"
                   value="{{ $filters['date_from'] ?? '' }}"
                   data-auto-submit
                   style="width:140px;">
            <input type="date"
                   name="date_to"
                   class="filter-input"
                   value="{{ $filters['date_to'] ?? '' }}"
                   data-auto-submit
                   style="width:140px;">

            @if (array_filter($filters))
                <a href="{{ route('super-admin.security.index') }}"
                   class="btn-ghost"
                   style="padding:.38rem .9rem;font-size:.82rem;">
                    <i class="fas fa-xmark me-1"></i>Clear
                </a>
            @endif
        </div>
    </form>

    {{-- Event table --}}
    <div class="table-responsive">
        <table class="fr-table" id="eventTable">
            <thead>
                <tr>
                    <th style="width:14px;"></th>
                    <th>Event</th>
                    <th>IP Address</th>
                    <th>User</th>
                    <th class="hide-mobile">Path</th>
                    <th>Time</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentEvents as $event)
                    @php
                        $tagStyle = match($event->level) {
                            'critical' => 'background:#fee2e2;color:#dc2626;',
                            'warning'  => 'background:#fef3c7;color:#d97706;',
                            default    => 'background:#dbeafe;color:#2563eb;',
                        };
                    @endphp
                    <tr>
                        <td><div class="event-level-dot level-{{ $event->level }}"></div></td>
                        <td>
                            <span class="event-type-tag" style="{{ $tagStyle }}">{{ $event->event }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.3rem;">
                                <a href="{{ route('super-admin.security.ip', $event->ip_address) }}"
                                   style="font-family:monospace;font-size:.8rem;color:var(--primary);text-decoration:none;">
                                    {{ $event->ip_address }}
                                </a>
                                <button class="copy-btn" data-copy="{{ $event->ip_address }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td style="font-size:.82rem;">
                            {{ $event->user_email ?? 'Guest' }}
                        </td>
                        <td class="hide-mobile" style="font-size:.78rem;color:var(--text-muted);max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $event->path ?? '—' }}
                        </td>
                        <td style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;">
                            {{ $event->created_at->format('d M H:i:s') }}
                        </td>
                        <td>
                            @if ($event->context)
                                {{-- data-target links button to specific context div --}}
                                <button class="context-toggle"
                                        data-target="#ctx-{{ $event->id }}">
                                    Context <i class="fas fa-chevron-down"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    {{-- Context row (hidden by CSS default) --}}
                    @if ($event->context)
                    <tr class="context-row">
                        <td colspan="7" style="padding:.25rem 1rem .9rem 2.5rem;background:#f8fafc;">
                            <div class="context-json" id="ctx-{{ $event->id }}">{{ json_encode($event->context, JSON_PRETTY_PRINT) }}</div>
                        </td>
                    </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted);">
                            <i class="fas fa-shield-check" style="font-size:1.5rem;display:block;margin-bottom:.75rem;color:var(--success);"></i>
                            No events match your filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="fr-pagination">
        <span>
            Showing {{ $recentEvents->firstItem() ?? 0 }}–{{ $recentEvents->lastItem() ?? 0 }}
            of {{ $recentEvents->total() }} events
        </span>
        <div class="pagination-btns">
            @if ($recentEvents->onFirstPage())
                <button class="pager-btn" disabled><i class="fas fa-chevron-left"></i></button>
            @else
                <a href="{{ $recentEvents->previousPageUrl() }}" class="pager-btn">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            @foreach ($recentEvents->getUrlRange(max(1, $recentEvents->currentPage()-2), min($recentEvents->lastPage(), $recentEvents->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}" class="pager-btn {{ $page == $recentEvents->currentPage() ? 'active' : '' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if ($recentEvents->hasMorePages())
                <a href="{{ $recentEvents->nextPageUrl() }}" class="pager-btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <button class="pager-btn" disabled><i class="fas fa-chevron-right"></i></button>
            @endif
        </div>
    </div>

</div>

@endsection