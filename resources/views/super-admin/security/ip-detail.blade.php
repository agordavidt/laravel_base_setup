@extends('layouts.app')
@section('title', 'Investigate IP: ' . $summary['ip'])

@push('styles')
<style>
    .ip-hero {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-3) 100%);
        border-radius: .9rem;
        padding: 1.75rem 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .ip-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(201,168,76,.06);
        border-radius: 50%;
    }
    .ip-hero code {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--gold);
        background: rgba(201,168,76,.1);
        padding: .3rem .85rem;
        border-radius: .4rem;
        letter-spacing: .02em;
    }
    .ip-hero .copy-btn {
        color: rgba(255,255,255,.4);
        font-size: .95rem;
        background: none;
        border: none;
        cursor: pointer;
        padding: .25rem .45rem;
        border-radius: .3rem;
        transition: color .15s;
    }
    .ip-hero .copy-btn:hover { color: var(--gold); }
    .ip-hero .copy-btn.copied { color: #10b981; }

    .ip-stat {
        text-align: center;
        padding: .5rem 1.5rem;
        border-left: 1px solid rgba(255,255,255,.08);
    }
    .ip-stat:first-child { border-left: none; }
    .ip-stat .is-value { font-size: 1.5rem; font-weight: 700; color: #fff; }
    .ip-stat .is-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.4); margin-top: .15rem; }
    .ip-stat .is-value.danger  { color: #ef4444; }
    .ip-stat .is-value.warning { color: #f59e0b; }
    .ip-stat .is-value.success { color: #10b981; }

    .timeline-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: .3rem;
    }
    .event-type-tag {
        font-size: .7rem;
        font-weight: 600;
        padding: .18rem .55rem;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')

{{-- Back Link --}}
<div class="mb-3">
    <a href="{{ route('super-admin.security.index') }}"
       style="font-size:.85rem;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;">
        <i class="fas fa-arrow-left"></i> Back to Security Monitoring
    </a>
</div>

{{-- IP Hero Banner --}}
<div class="ip-hero">
    <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
        <div>
            <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:.4rem;">
                Investigating IP Address
            </div>
            <div class="d-flex align-items-center gap-2">
                <code>{{ $summary['ip'] }}</code>
                <button class="copy-btn" id="ipCopyBtn" data-copy="{{ $summary['ip'] }}" title="Copy IP">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap" style="margin-top:.5rem;">
        <div class="ip-stat" style="padding-left:0;">
            <div class="is-value {{ $summary['total_events'] > 20 ? 'danger' : ($summary['total_events'] > 5 ? 'warning' : 'success') }}">
                {{ $summary['total_events'] }}
            </div>
            <div class="is-label">Total Events</div>
        </div>
        <div class="ip-stat">
            <div class="is-value {{ $summary['alert_count'] > 0 ? 'danger' : 'success' }}">
                {{ $summary['alert_count'] }}
            </div>
            <div class="is-label">Alerts Triggered</div>
        </div>
        <div class="ip-stat">
            <div class="is-value" style="font-size:1rem;padding-top:.25rem;">
                {{ $summary['first_seen'] ? $summary['first_seen']->format('d M Y') : '—' }}
            </div>
            <div class="is-label">First Seen</div>
        </div>
        <div class="ip-stat">
            <div class="is-value" style="font-size:1rem;padding-top:.25rem;">
                {{ $summary['last_seen'] ? $summary['last_seen']->diffForHumans() : '—' }}
            </div>
            <div class="is-label">Last Seen</div>
        </div>
    </div>
</div>

{{-- Alert History for this IP --}}
@if ($summary['alerts']->isNotEmpty())
<div class="content-card mb-3">
    <div class="card-head">
        <h3>Alert History for this IP</h3>
        <span class="status-badge badge-danger" style="font-size:.72rem;">
            {{ $summary['alerts']->count() }} alert(s)
        </span>
    </div>
    <div class="table-responsive">
        <table class="fr-table">
            <thead>
                <tr>
                    <th>Alert Type</th>
                    <th>Events</th>
                    <th>Window</th>
                    <th>Triggered</th>
                    <th>Status</th>
                    <th>Acknowledged By</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary['alerts'] as $alert)
                <tr>
                    <td style="font-weight:600;font-size:.85rem;">{{ $alert->humanLabel() }}</td>
                    <td>{{ $alert->event_count }}</td>
                    <td style="color:var(--text-muted);font-size:.82rem;">{{ $alert->window_minutes }} min</td>
                    <td style="font-size:.8rem;color:var(--text-muted);">
                        {{ $alert->triggered_at->format('d M Y H:i') }}
                    </td>
                    <td>
                        @if ($alert->isAcknowledged())
                            <span class="status-badge badge-success">Reviewed</span>
                        @else
                            <span class="status-badge badge-danger">Pending</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:var(--text-muted);">
                        {{ $alert->acknowledgedBy?->name ?? '—' }}
                        @if ($alert->acknowledged_at)
                            <br><span style="font-size:.72rem;">{{ $alert->acknowledged_at->diffForHumans() }}</span>
                        @endif
                    </td>
                    <td>
                        @if (!$alert->isAcknowledged())
                            <form method="POST"
                                  action="{{ route('super-admin.security.alerts.acknowledge', $alert) }}">
                                @csrf
                                <button type="submit" class="btn-gold"
                                        style="font-size:.75rem;padding:.3rem .75rem;">
                                    <i class="fas fa-check me-1"></i>Acknowledge
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Event Timeline --}}
<div class="content-card">
    <div class="card-head">
        <h3>Event Timeline (Last 50 Events)</h3>
        <span style="font-size:.75rem;color:var(--text-muted);">Most recent first</span>
    </div>

    @if ($summary['events']->isEmpty())
        <div style="padding:2.5rem;text-align:center;color:var(--text-muted);font-size:.88rem;">
            No detailed event history found in the database for this IP.
        </div>
    @else
    <div style="padding:1rem 1.4rem;">
        @foreach ($summary['events'] as $event)
            @php
                $levelColor = match($event->level) {
                    'critical' => '#ef4444',
                    'warning'  => '#f59e0b',
                    default    => '#3b82f6',
                };
                $tagStyle = match($event->level) {
                    'critical' => 'background:#fee2e2;color:#dc2626;',
                    'warning'  => 'background:#fef3c7;color:#d97706;',
                    default    => 'background:#dbeafe;color:#2563eb;',
                };
            @endphp
            <div style="display:flex;gap:1rem;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border);">
                {{-- Timeline connector --}}
                <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                    <div class="timeline-dot" style="background:{{ $levelColor }};box-shadow:0 0 0 3px {{ $levelColor }}22;"></div>
                    <div style="width:1px;flex:1;background:var(--border);margin-top:.35rem;min-height:24px;"></div>
                </div>

                {{-- Content --}}
                <div style="flex:1;min-width:0;padding-bottom:.25rem;">
                    <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;margin-bottom:.3rem;">
                        <span class="event-type-tag" style="{{ $tagStyle }}">
                            {{ $event->event }}
                        </span>
                        <span style="font-size:.75rem;color:var(--text-muted);">
                            {{ $event->created_at->format('d M Y  H:i:s') }}
                        </span>
                    </div>

                    <div style="font-size:.8rem;color:var(--text-secondary);">
                        @if ($event->user_email)
                            <i class="fas fa-user me-1" style="color:var(--text-muted);"></i>
                            {{ $event->user_email }}
                        @else
                            <i class="fas fa-user-secret me-1" style="color:var(--text-muted);"></i>
                            Guest / Unauthenticated
                        @endif
                        @if ($event->path)
                            &nbsp;&middot;&nbsp;
                            <i class="fas fa-link me-1" style="color:var(--text-muted);"></i>
                            <code style="font-size:.77rem;background:var(--bg);padding:.1rem .35rem;border-radius:.25rem;">
                                {{ $event->method }} {{ $event->path }}
                            </code>
                        @endif
                    </div>

                    @if ($event->context)
                        <button class="context-toggle mt-1" onclick="toggleContext(this)">
                            Context <i class="fas fa-chevron-down" style="font-size:.65rem;"></i>
                        </button>
                        <div class="context-json" style="display:none;background:#0f172a;color:#e2e8f0;border-radius:.45rem;padding:.75rem 1rem;font-size:.77rem;font-family:'Courier New',monospace;margin-top:.4rem;overflow-x:auto;white-space:pre-wrap;word-break:break-all;max-height:180px;overflow-y:auto;">{{ json_encode($event->context, JSON_PRETTY_PRINT) }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function toggleContext(btn) {
    const json = btn.nextElementSibling;
    if (!json) return;
    const open = json.style.display === 'block';
    json.style.display = open ? 'none' : 'block';
    btn.innerHTML = open
        ? 'Context <i class="fas fa-chevron-down" style="font-size:.65rem;"></i>'
        : 'Context <i class="fas fa-chevron-up" style="font-size:.65rem;"></i>';
}

document.getElementById('ipCopyBtn')?.addEventListener('click', function () {
    navigator.clipboard.writeText(this.dataset.copy).then(() => {
        this.classList.add('copied');
        this.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            this.classList.remove('copied');
            this.innerHTML = '<i class="fas fa-copy"></i>';
        }, 2000);
    });
});
</script>
@endpush
