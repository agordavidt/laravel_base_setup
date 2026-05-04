<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Frontrow') }} – Software & Technology Services</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

<style>
    :root {
        --navy:    #0b0f1a;
        --navy-2:  #111827;
        --navy-3:  #1a2235;
        --gold:    #c9a84c;
        --gold-lt: #e4c870;
        --text:    #f1f5f9;
        --muted:   rgba(241,245,249,0.45);
        --border:  rgba(255,255,255,0.07);
    }

    *, *::before, *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--navy);
        color: var(--text);
    }

    /* ─── LOGO ─── */
    .logo {
        display: flex;
        align-items: center;
        gap: .55rem;
        text-decoration: none;
    }

    .logo-icon {
        width: 32px;
        height: 32px;
        background: var(--gold);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-icon svg {
        width: 18px;
        height: 18px;
        color: var(--navy);
    }

    .logo-name {
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--text);
    }

    /* ─── NAVBAR ─── */
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2.5rem;
        background: rgba(11,15,26,0.85);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border);
    }

    .nav-links {
        display: flex;
        gap: 1.8rem;
    }

    .nav-links a {
        color: rgba(241,245,249,.5);
        font-size: .9rem;
        text-decoration: none;
        transition: color .2s;
    }

    .nav-links a:hover {
        color: var(--text);
    }

    .nav-actions {
        display: flex;
        gap: .6rem;
    }

    /* ─── BUTTONS ─── */
    .btn-ghost {
        padding: .45rem 1.1rem;
        font-size: .85rem;
        color: rgba(241,245,249,.6);
        border-radius: .35rem;
        text-decoration: none;
        transition: color .2s, border .2s;
        border: 1px solid transparent;
    }

    .btn-ghost:hover {
        color: var(--gold);
        border-color: rgba(201,168,76,.4);
    }

    .btn-primary {
        padding: .5rem 1.2rem;
        font-size: .85rem;
        font-weight: 500;
        color: var(--navy);
        background: var(--gold);
        border-radius: .35rem;
        text-decoration: none;
        transition: all .2s;
    }

    .btn-primary:hover {
        background: var(--gold-lt);
        transform: translateY(-1px);
    }

    .btn-solid {
        padding: .75rem 1.8rem;
        background: var(--gold);
        color: var(--navy);
        font-weight: 600;
        font-size: .9rem;
        border-radius: .4rem;
        text-decoration: none;
        border: 2px solid var(--gold);
        transition: all .2s;
    }

    .btn-solid:hover {
        background: var(--gold-lt);
        transform: translateY(-2px);
    }

    .btn-outline {
        padding: .75rem 1.8rem;
        background: transparent;
        color: var(--text);
        font-size: .9rem;
        border-radius: .4rem;
        text-decoration: none;
        border: 2px solid rgba(255,255,255,.15);
        transition: all .2s;
    }

    .btn-outline:hover {
        border-color: rgba(201,168,76,.5);
        color: var(--gold);
    }

</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="/" class="logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
        </div>
        <span class="logo-name">Frontrow</span>
    </a>

    <div class="nav-links">
        <a href="#">Docs</a>
        <a href="#">Features</a>
    </div>

    @if (Route::has('login'))
        <div class="nav-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Register</a>
                @endif
            @endauth
        </div>
    @endif
</nav>


<!-- SIMPLE CENTER VIEW -->
<section style="
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
">

    <div style="text-align:center; max-width: 520px;">

        <h1 style="
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        ">
            Welcome, David
        </h1>

        <p style="
            color: var(--muted);
            font-size: .95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        ">
            This is your base project setup with authentication,
            role management, and admin controls ready.
            Start building your next application from here.
        </p>

        <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
            <a href="{{ url('/dashboard') }}" class="btn-solid">
                Go to Dashboard
            </a>

            <a href="#" class="btn-outline">
                View Documentation
            </a>
        </div>

    </div>

</section>

</body>
</html>