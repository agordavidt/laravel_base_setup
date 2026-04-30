<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Frontrow – Welcome</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

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
        * { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        body { background: #0b0f1a; }
        .hero-bg {
            background:
                radial-gradient(ellipse 70% 50% at 60% 40%, rgba(201,168,76,0.07) 0%, transparent 70%),
                radial-gradient(ellipse 40% 60% at 10% 80%, rgba(201,168,76,0.04) 0%, transparent 60%),
                #0b0f1a;
        }
        .btn-gold {
            background: linear-gradient(135deg, #c9a84c, #a0832e);
            transition: opacity .2s, transform .15s;
        }
        .btn-gold:hover { opacity:.9; transform: translateY(-2px); }
        .btn-outline {
            border: 1px solid rgba(201,168,76,.35);
            color: #c9a84c;
            transition: background .2s;
        }
        .btn-outline:hover { background: rgba(201,168,76,.08); }
        .feature-card {
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.07);
            transition: border-color .2s, transform .2s;
        }
        .feature-card:hover { border-color: rgba(201,168,76,.25); transform: translateY(-3px); }
        .divider-gold { width: 48px; height: 2px; background: linear-gradient(90deg, #c9a84c, transparent); }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .fade-up   { animation: fadeUp .6s .05s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-2 { animation: fadeUp .6s .18s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-3 { animation: fadeUp .6s .32s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-4 { animation: fadeUp .6s .46s cubic-bezier(.22,1,.36,1) both; }
    </style>
</head>
<body class="hero-bg min-h-screen">

    {{-- ===== NAVBAR ===== --}}
   <header class="px-8 py-5 flex items-center justify-between border-b border-white/5">
        <span class="font-display text-gold text-2xl tracking-widest uppercase">Frontrow</span>

        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-white/40 text-sm hover:text-white/70 transition border border-white/10 px-5 py-1.5 rounded-sm hover:border-gold/40 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white/40 text-sm hover:text-white/70 transition">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-gold text-navy text-sm font-medium px-5 py-2 rounded-full">
                            Get Started
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    {{-- ===== HERO ===== --}}
    <section class="max-w-5xl mx-auto px-6 pt-28 pb-24 text-center">
        <div class="fade-up inline-block text-xs tracking-[.2em] text-gold/70 uppercase border border-gold/20 px-4 py-1.5 rounded-full mb-8">
            Now in Public Beta
        </div>
        <h1 class="fade-up-2 font-display text-5xl md:text-7xl text-white leading-tight mb-6">
            Your seat at the<br>
            <em class="text-gold not-italic">front row.</em>
        </h1>
        <p class="fade-up-3 text-white/40 text-lg md:text-xl max-w-xl mx-auto mb-12 leading-relaxed">
            Frontrow is a simple, elegant authentication starter built with Laravel Fortify and Tailwind CSS — the perfect base for your next project.
        </p>
        <div class="fade-up-4 flex items-center justify-center gap-4 flex-wrap">
            <a href="/register" class="btn-gold text-navy font-semibold px-8 py-3 rounded-full text-base">
                Create Account
            </a>
            <a href="/login" class="btn-outline px-8 py-3 rounded-full text-base font-medium">
                Sign In
            </a>
        </div>
    </section>

    {{-- ===== DIVIDER ===== --}}
    <div class="max-w-5xl mx-auto px-6">
        <div class="border-t border-white/5"></div>
    </div>

    {{-- ===== FEATURES ===== --}}
    <section id="features" class="max-w-5xl mx-auto px-6 py-24">
        <div class="text-center mb-16">
            <div class="divider-gold mx-auto mb-6"></div>
            <h2 class="font-display text-3xl md:text-4xl text-white mb-3">Everything you need</h2>
            <p class="text-white/35 text-base">A complete auth flow out of the box.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="feature-card rounded-2xl p-7">
                <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <h3 class="text-white font-medium mb-2">Secure Auth</h3>
                <p class="text-white/35 text-sm leading-relaxed">Login, registration, and password reset powered by Laravel Fortify with CSRF protection built in.</p>
            </div>

            <div class="feature-card rounded-2xl p-7">
                <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/>
                    </svg>
                </div>
                <h3 class="text-white font-medium mb-2">Two-Factor Auth</h3>
                <p class="text-white/35 text-sm leading-relaxed">Optional two-factor authentication via TOTP for users who need an extra layer of account protection.</p>
            </div>

            <div class="feature-card rounded-2xl p-7">
                <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M22 16.92V19a2 2 0 01-2.18 2A19.8 19.8 0 013 4.18 2 2 0 015 2h2.09a2 2 0 012 1.72 12.8 12.8 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.8 12.8 0 002.81.7A2 2 0 0122 16.92z"/>
                    </svg>
                </div>
                <h3 class="text-white font-medium mb-2">Email Verification</h3>
                <p class="text-white/35 text-sm leading-relaxed">Verify new users via email before granting access. Works seamlessly with Laravel's notification system.</p>
            </div>

        </div>
    </section>

    {{-- ===== CTA ===== --}}
    <section class="max-w-5xl mx-auto px-6 pb-24">
        <div class="rounded-3xl border border-gold/15 bg-gold/5 p-12 text-center">
            <h2 class="font-display text-3xl text-white mb-3">Ready to get started?</h2>
            <p class="text-white/35 mb-8">Sign up for free and take your seat at the front row.</p>
            <a href="/register" class="btn-gold text-navy font-semibold px-10 py-3 rounded-full text-base inline-block">
                Create Free Account
            </a>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="border-t border-white/5 py-8 text-center text-white/20 text-xs tracking-wide">
        &copy; {{ date('Y') }} Frontrow. Built with Laravel Fortify &amp; Tailwind CSS.
    </footer>

</body>
</html>