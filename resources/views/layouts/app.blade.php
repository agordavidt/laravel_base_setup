<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Frontrow') }} – @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { DEFAULT: '#0b0f1a', 800: '#111827', 700: '#1a2235' },
                        gold: { DEFAULT: '#c9a84c', light: '#e4c870', dark: '#a0832e' },
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body:    ['"DM Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #0b0f1a; }
        .sidebar-link { display:flex; align-items:center; gap:.75rem; padding:.6rem 1rem; border-radius:.5rem; color:rgba(255,255,255,.45); font-size:.9rem; transition: background .15s, color .15s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(201,168,76,.08); color: #c9a84c; }
        .btn-gold { background: linear-gradient(135deg,#c9a84c,#a0832e); transition: opacity .2s; }
        .btn-gold:hover { opacity:.9; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
    </style>
</head>
<body class="min-h-screen flex" style="background:#0b0f1a;">

    {{-- Sidebar --}}
    <aside class="w-64 shrink-0 border-r border-white/5 flex flex-col px-4 py-6 gap-1">
        <a href="/" class="font-display text-gold text-lg tracking-widest uppercase mb-8 px-3 block">Frontrow</a>

        <a href="/dashboard" class="sidebar-link active">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Dashboard
        </a>
        <a href="#" class="sidebar-link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Profile
        </a>
        <a href="#" class="sidebar-link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm0 0v10l4 2"/></svg>
            Activity
        </a>
        <a href="#" class="sidebar-link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            Settings
        </a>

        <div class="mt-auto pt-6 border-t border-white/5">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-400/60 hover:text-red-400 hover:bg-red-500/5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="flex-1 flex flex-col">
        {{-- Topbar --}}
        <header class="h-16 border-b border-white/5 px-8 flex items-center justify-between">
            <h1 class="text-white/70 text-sm font-medium tracking-wide uppercase">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center text-gold text-sm font-semibold">
                    {{ auth()->user()->name[0] ?? 'U' }}
                </div>
                <span class="text-white/50 text-sm">{{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-8 fade-up">
            @yield('content')
        </main>
    </div>

</body>
</html>