<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Frontrow – @yield('title', 'Dashboard')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    {{-- App styles --}}
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.css') }}">
</head>
<body>

<div class="fr-overlay" id="frOverlay"></div>

<div class="fr-wrapper">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="fr-sidebar" id="frSidebar">

        <a href="/" class="sidebar-logo">
            <div class="logo-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            </div>
            <span class="logo-text">Frontrow</span>
        </a>

        <nav class="sidebar-nav">
            @include('partials.sidebar.' . (auth()->user()->getRoleNames()->first() ?? 'user'))
        </nav>

        <div class="sidebar-footer">
            {{-- <div class="sidebar-user">
                <div class="user-avatar-initials">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="user-meta">
                    <div class="u-name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="u-role">
                        {{ str_replace('-', ' ', auth()->user()->getRoleNames()->first() ?? 'user') }}
                    </div>
                </div>
            </div> --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout" data-label="Logout">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- ═══ BODY ═══ --}}
    <div class="fr-body">

        {{-- Topbar --}}
        <header class="fr-topbar">
            <button class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <span class="topbar-title">@yield('title', 'Dashboard')</span>

            <div class="topbar-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search…" id="globalSearch">
            </div>

            <div class="topbar-actions ms-auto">
                <a href="#" class="topbar-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notif-dot"></span>
                </a>
                <a href="" class="topbar-btn" title="Settings">
                    <i class="fas fa-gear"></i>
                </a>
                <div class="dropdown">
                    <div class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="topbar-user-avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="topbar-user-info">
                            <div class="topbar-user-name">
                                {{ Str::words(auth()->user()->name ?? 'User', 1, '') }}
                            </div>
                            <div class="topbar-user-role">
                                {{ str_replace('-', ' ', auth()->user()->getRoleNames()->first() ?? 'user') }}
                            </div>
                        </div>
                        <i class="fas fa-chevron-down ms-1" style="font-size:.65rem;color:var(--text-muted);"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end" style="font-size:.85rem;min-width:180px;">
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="fas fa-user me-2 text-muted"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-arrow-right-from-bracket me-2"></i>Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if (session('success') || session('error') || session('info'))
        <div class="fr-main" style="padding-bottom:0;padding-top:.75rem;">
            @if (session('success'))
                <div class="flash flash-success mb-2">
                    <i class="fas fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="flash flash-error mb-2">
                    <i class="fas fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif
            @if (session('info'))
                <div class="flash flash-info mb-2">
                    <i class="fas fa-circle-info"></i> {{ session('info') }}
                </div>
            @endif
        </div>
        @endif

        {{-- Page content --}}
        <main class="fr-main" style="padding-top:.75rem;">
            @yield('content')
        </main>

    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- App JS --}}
<script src="{{ asset('backend/assets/js/app.js') }}"></script>
{{-- Chart.js (only loaded when a page needs it via data attribute) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

</body>
</html>