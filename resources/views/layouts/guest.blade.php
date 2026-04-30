<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Frontrow') }} – @yield('title', 'Welcome')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:  { DEFAULT: '#0b0f1a', 800: '#111827', 700: '#1a2235' },
                        gold:  { DEFAULT: '#c9a84c', light: '#e4c870', dark: '#a0832e' },
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
        .card-glow { box-shadow: 0 0 0 1px rgba(201,168,76,0.15), 0 20px 60px rgba(0,0,0,0.6); }
        .input-field {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            color: #f1f5f9;
            transition: border-color .2s, box-shadow .2s;
        }
        .input-field:focus {
            outline: none;
            border-color: #c9a84c;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
        }
        .input-field::placeholder { color: rgba(255,255,255,0.25); }
        .btn-gold {
            background: linear-gradient(135deg, #c9a84c, #a0832e);
            transition: opacity .2s, transform .1s;
        }
        .btn-gold:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-gold:active { transform: translateY(0); }
        .noise {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
        label { color: rgba(255,255,255,0.55); font-size: .8rem; letter-spacing: .05em; text-transform: uppercase; }
        .link-gold { color: #c9a84c; }
        .link-gold:hover { color: #e4c870; text-decoration: underline; }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(18px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp .55s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-2 { animation: fadeUp .55s .12s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-3 { animation: fadeUp .55s .22s cubic-bezier(.22,1,.36,1) both; }
    </style>
</head>
<body class="min-h-screen flex flex-col noise">    

    {{-- Main content --}}
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center py-5 text-white/20 text-xs tracking-wide border-t border-white/5">
        &copy; {{ date('Y') }} Frontrow. All rights reserved.
    </footer>

</body>
</html>