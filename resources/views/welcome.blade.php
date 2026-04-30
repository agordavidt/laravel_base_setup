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
            --navy-4:  #232d42;
            --gold:    #c9a84c;
            --gold-lt: #e4c870;
            --gold-dk: #a0832e;
            --text:    #f1f5f9;
            --muted:   rgba(241,245,249,0.45);
            --border:  rgba(255,255,255,0.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ─── TYPOGRAPHY ─── */
        .font-display { font-family: 'Playfair Display', serif; }

        h1, h2, h3 { font-family: 'Playfair Display', serif; }

        /* ─── LOGO ─── */
        .logo {
            display: flex;
            align-items: center;
            gap: .55rem;
            text-decoration: none;
        }
        .logo-icon {
            width: 32px; height: 32px;
            background: var(--gold);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-icon svg { width: 18px; height: 18px; color: var(--navy); }
        .logo-name {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text);
            letter-spacing: .01em;
        }

        /* ─── NAVBAR ─── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.1rem 3rem;
            background: rgba(11,15,26,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a {
            color: rgba(241,245,249,.5);
            font-size: .9rem;
            text-decoration: none;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--text); }
        .nav-actions { display: flex; align-items: center; gap: .75rem; }
        .btn-ghost {
            padding: .45rem 1.2rem;
            font-size: .85rem;
            color: rgba(241,245,249,.55);
            border: 1px solid transparent;
            border-radius: .35rem;
            text-decoration: none;
            transition: border-color .2s, color .2s;
        }
        .btn-ghost:hover { border-color: rgba(201,168,76,.4); color: var(--gold); }
        .btn-primary {
            padding: .5rem 1.3rem;
            font-size: .85rem;
            font-weight: 500;
            color: var(--navy);
            background: var(--gold);
            border: none;
            border-radius: .35rem;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }
        .btn-primary:hover { background: var(--gold-lt); transform: translateY(-1px); }

        /* ─── HERO ─── */
        .hero {
            min-height: 100vh;
            padding: 8rem 3rem 5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 4rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 60% 70% at 70% 40%, rgba(201,168,76,.06) 0%, transparent 65%),
                radial-gradient(ellipse 40% 50% at 5% 90%, rgba(201,168,76,.04) 0%, transparent 60%);
            pointer-events: none;
        }
        .hero-label {
            display: inline-flex; align-items: center; gap: .5rem;
            font-size: .75rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.5rem;
        }
        .hero-label span {
            width: 24px; height: 1px;
            background: var(--gold);
            display: inline-block;
        }
        .hero h1 {
            font-size: clamp(2.4rem, 4.5vw, 3.6rem);
            font-weight: 700;
            line-height: 1.1;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        .hero h1 em {
            font-style: italic;
            color: var(--gold);
        }
        .hero p {
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.75;
            max-width: 420px;
            margin-bottom: 2.5rem;
        }
        .hero-buttons { display: flex; gap: 1rem; }
        .btn-solid {
            padding: .8rem 2rem;
            background: var(--gold);
            color: var(--navy);
            font-weight: 600;
            font-size: .9rem;
            border-radius: .4rem;
            text-decoration: none;
            border: 2px solid var(--gold);
            transition: background .2s, transform .15s;
        }
        .btn-solid:hover { background: var(--gold-lt); transform: translateY(-2px); }
        .btn-outline {
            padding: .8rem 2rem;
            background: transparent;
            color: var(--text);
            font-weight: 500;
            font-size: .9rem;
            border-radius: .4rem;
            text-decoration: none;
            border: 2px solid rgba(255,255,255,.15);
            transition: border-color .2s;
        }
        .btn-outline:hover { border-color: rgba(201,168,76,.5); color: var(--gold); }

        /* Hero image — circular clip like the design */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .hero-img-wrap {
            width: 420px; height: 420px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(201,168,76,.2);
            position: relative;
        }
        .hero-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .hero-badge {
            position: absolute;
            bottom: 2.5rem; left: -1.5rem;
            background: var(--navy-3);
            border: 1px solid var(--border);
            border-radius: .75rem;
            padding: .9rem 1.4rem;
            display: flex; flex-direction: column; gap: .15rem;
        }
        .hero-badge .num {
            font-size: 1.6rem; font-weight: 700;
            color: var(--gold);
            font-family: 'Playfair Display', serif;
        }
        .hero-badge .lbl {
            font-size: .72rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        /* ─── CLIENTS STRIP ─── */
        .clients {
            padding: 2.5rem 3rem;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 3.5rem;
            overflow: hidden;
        }
        .clients-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: rgba(255,255,255,.25);
            white-space: nowrap;
            flex-shrink: 0;
        }
        .clients-logos {
            display: flex; align-items: center; gap: 3rem;
            flex-wrap: wrap;
        }
        .client-logo {
            font-size: .9rem;
            font-weight: 600;
            color: rgba(255,255,255,.2);
            letter-spacing: .05em;
            text-transform: uppercase;
            transition: color .2s;
        }
        .client-logo:hover { color: rgba(255,255,255,.45); }

        /* ─── SECTION COMMONS ─── */
        section { padding: 6rem 3rem; }
        .section-tag {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: var(--gold);
            margin-bottom: .75rem;
        }
        .section-title {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: .75rem;
        }
        .section-sub {
            color: var(--muted);
            font-size: .95rem;
            line-height: 1.7;
            max-width: 500px;
        }

        /* ─── SERVICES ─── */
        .services-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 4rem;
        }
        .services-left { max-width: 360px; }
        .services-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .service-card {
            background: var(--navy-3);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.8rem 1.5rem;
            transition: border-color .2s, transform .2s;
            cursor: default;
        }
        .service-card:hover {
            border-color: rgba(201,168,76,.3);
            transform: translateY(-3px);
        }
        .service-icon {
            width: 42px; height: 42px;
            background: rgba(201,168,76,.1);
            border-radius: .6rem;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.1rem;
        }
        .service-icon svg { width: 22px; height: 22px; color: var(--gold); }
        .service-card h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: .5rem;
        }
        .service-card p {
            font-size: .82rem;
            color: var(--muted);
            line-height: 1.6;
        }
        /* Image strip left bottom */
        .services-img {
            width: 100%;
            height: 220px;
            border-radius: 1rem;
            overflow: hidden;
            margin-top: 1.5rem;
            position: relative;
        }
        .services-img img { width: 100%; height: 100%; object-fit: cover; }
        /* Arc clip like the design */
        .services-img-arc {
            clip-path: ellipse(90% 100% at 50% 100%);
        }

        /* ─── CASE STUDIES ─── */
        .case-studies { background: var(--navy-2); }
        .case-header {
            display: flex; align-items: flex-end; justify-content: space-between;
            margin-bottom: 2.5rem;
        }
        .case-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }
        .case-card {
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
            aspect-ratio: 4/3;
            cursor: pointer;
        }
        .case-card img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
        .case-card:hover img { transform: scale(1.05); }
        .case-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(11,15,26,.85) 0%, transparent 55%);
            display: flex; flex-direction: column; justify-content: flex-end;
            padding: 1.2rem;
        }
        .case-tag {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--gold);
            margin-bottom: .3rem;
        }
        .case-title {
            font-family: 'DM Sans', sans-serif;
            font-size: .92rem;
            font-weight: 600;
            color: #fff;
        }
        .case-arrow {
            width: 28px; height: 28px;
            background: var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-top: .6rem;
            flex-shrink: 0;
        }
        .case-arrow svg { width: 12px; height: 12px; color: var(--navy); }
        .case-cta {
            grid-column: span 1;
            background: var(--gold);
            border-radius: 1rem;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            font-weight: 600;
            font-size: .95rem;
            color: var(--navy);
            cursor: pointer;
            transition: background .2s;
            text-align: center;
            text-decoration: none;
        }
        .case-cta:hover { background: var(--gold-lt); }

        /* ─── ABOUT / PARTNER ─── */
        .about { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
        .about-visual { position: relative; }
        .about-img-main {
            width: 85%;
            aspect-ratio: 1;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(201,168,76,.2);
        }
        .about-img-main img { width: 100%; height: 100%; object-fit: cover; }
        .about-stat {
            position: absolute;
            background: var(--navy-3);
            border: 1px solid var(--border);
            border-radius: .75rem;
            padding: .9rem 1.3rem;
        }
        .about-stat .num {
            font-size: 1.5rem; font-weight: 700;
            color: var(--gold);
            font-family: 'Playfair Display', serif;
        }
        .about-stat .lbl {
            font-size: .7rem; color: var(--muted);
            text-transform: uppercase; letter-spacing: .08em;
        }
        .about-stat-1 { bottom: 2rem; right: 0; }
        .about-stat-2 { bottom: 6rem; right: -1rem; }
        .about-stat-3 { top: 2rem; right: 1rem; }
        .about-text .link-gold {
            display: inline-flex; align-items: center; gap: .4rem;
            color: var(--gold);
            font-size: .9rem;
            font-weight: 500;
            margin-top: 2rem;
            text-decoration: none;
            border-bottom: 1px solid rgba(201,168,76,.3);
            padding-bottom: .2rem;
            transition: border-color .2s;
        }
        .about-text .link-gold:hover { border-color: var(--gold); }

        /* ─── BLOG / KNOWLEDGE ─── */
        .blog { background: var(--navy-2); }
        .blog-header {
            display: flex; align-items: flex-end; justify-content: space-between;
            margin-bottom: 2.5rem;
        }
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
        }
        .blog-card {
            background: var(--navy-3);
            border: 1px solid var(--border);
            border-radius: 1rem;
            overflow: hidden;
            transition: border-color .2s, transform .2s;
            cursor: pointer;
        }
        .blog-card:hover { border-color: rgba(201,168,76,.25); transform: translateY(-3px); }
        .blog-card-img {
            width: 100%; height: 160px;
            overflow: hidden;
        }
        .blog-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
        .blog-card:hover .blog-card-img img { transform: scale(1.05); }
        .blog-card-body { padding: 1.3rem; }
        .blog-card-tag {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--gold);
            margin-bottom: .5rem;
        }
        .blog-card h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: .92rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.4;
            margin-bottom: .5rem;
        }
        .blog-card-arrow {
            display: inline-flex; align-items: center; gap: .3rem;
            font-size: .78rem;
            color: var(--gold);
            margin-top: .5rem;
        }
        .blog-card-arrow svg { width: 12px; height: 12px; }
        /* Last card as CTA */
        .blog-card-cta {
            background: var(--gold);
            border: none;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600;
            font-size: .92rem;
            color: var(--navy);
        }
        .blog-card-cta:hover { background: var(--gold-lt); transform: translateY(-3px); }

        /* ─── CONTACT ─── */
        .contact-section {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 5rem;
            padding: 6rem 3rem;
            border-top: 1px solid var(--border);
        }
        .contact-info h2 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }
        .contact-info p {
            color: var(--muted);
            font-size: .9rem;
            margin-bottom: .5rem;
        }
        .contact-info a { color: var(--muted); text-decoration: none; }
        .contact-info a:hover { color: var(--gold); }
        .contact-social {
            display: flex; gap: 1rem;
            margin-top: 1.5rem;
        }
        .social-link {
            width: 36px; height: 36px;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--muted);
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            transition: border-color .2s, color .2s;
        }
        .social-link:hover { border-color: var(--gold); color: var(--gold); }
        .contact-form h2 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .form-field {
            display: flex; flex-direction: column;
            margin-bottom: 1rem;
        }
        .form-field input,
        .form-field textarea {
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(255,255,255,.12);
            padding: .7rem 0;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            outline: none;
            transition: border-color .2s;
        }
        .form-field input::placeholder,
        .form-field textarea::placeholder { color: rgba(255,255,255,.25); }
        .form-field input:focus,
        .form-field textarea:focus { border-color: var(--gold); }
        .form-field textarea { resize: none; height: 80px; }
        .form-footer {
            display: flex; align-items: center; justify-content: space-between;
            margin-top: .5rem;
        }
        .upload-btn {
            font-size: .8rem; color: var(--muted);
            display: flex; align-items: center; gap: .4rem;
            cursor: pointer;
        }
        .upload-btn svg { width: 14px; height: 14px; }

        /* ─── FOOTER ─── */
        footer {
            background: var(--navy-2);
            border-top: 1px solid var(--border);
            padding: 2rem 3rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        footer p { font-size: .8rem; color: rgba(255,255,255,.2); }
        .footer-links { display: flex; gap: 1.5rem; }
        .footer-links a {
            font-size: .8rem;
            color: rgba(255,255,255,.2);
            text-decoration: none;
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--gold); }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up   { animation: fadeUp .65s .05s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-2 { animation: fadeUp .65s .18s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-3 { animation: fadeUp .65s .3s  cubic-bezier(.22,1,.36,1) both; }
        .fade-up-4 { animation: fadeUp .65s .42s cubic-bezier(.22,1,.36,1) both; }
        .fade-up-5 { animation: fadeUp .65s .54s cubic-bezier(.22,1,.36,1) both; }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════ -->
<nav class="navbar">
    <!-- Logo: icon + name, just like image -->
    <a href="/" class="logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
        </div>
        <span class="logo-name">Frontrow</span>
    </a>

    <div class="nav-links">
        <a href="#services">Services</a>
        <a href="#case-studies">Case studies</a>
        <a href="#about">Company</a>
        <a href="#blog">Blog</a>
    </div>

    @if (Route::has('login'))
        <div class="nav-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Get started</a>
                @endif
            @endauth
        </div>
    @endif
</nav>


<!-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ -->
<section class="hero">
    <div class="hero-content fade-up">
        <div class="hero-label">
            <span></span>
            Tech Innovation Partner
        </div>
        <h1>
            Software Development<br>
            <em>For Business Growth</em>
        </h1>
        <p>
            We provide custom technology solutions and support helping enterprises
            bring about change and disrupt industries while maintaining business sustainability.
        </p>
        <div class="hero-buttons fade-up-2">
            <a href="#contact" class="btn-solid">Let's talk</a>
            <a href="#services" class="btn-outline">View more</a>
        </div>
    </div>

    <div class="hero-visual fade-up-3">
        <div class="hero-img-wrap">
            {{-- Replace src with your actual image. Recommended: 840×840px --}}
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=840&h=840&fit=crop&crop=center"
                 alt="Frontrow team at work"
                 width="420" height="420">
        </div>

        <div class="hero-badge">
            <span class="num">8+</span>
            <span class="lbl">Years of Excellence</span>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     CLIENT LOGOS
═══════════════════════════════════════════ -->
<div class="clients">
    <span class="clients-label">Trusted by</span>
    <div class="clients-logos">
        <span class="client-logo">Spotify</span>
        <span class="client-logo">Shopify</span>
        <span class="client-logo">HotJar</span>
        <span class="client-logo">UserTesting</span>
        <span class="client-logo">Twilio</span>
        <span class="client-logo">monday.com</span>
    </div>
</div>


<!-- ═══════════════════════════════════════════
     SERVICES
═══════════════════════════════════════════ -->
<section id="services" style="padding: 6rem 3rem;">
    <div class="services-grid">

        <!-- Left: intro + image arc -->
        <div class="services-left">
            <p class="section-tag">What we do</p>
            <h2 class="section-title">Software development expertise</h2>
            <p class="section-sub">
                Our team of dedicated talents has been delivering projects across
                the globe since 2016, gaining expertise in the most demanded
                aspects of software development.
            </p>
            <div class="services-img services-img-arc" style="margin-top:2rem;">
                {{-- Replace: 720×440px office/team photo --}}
                <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=720&h=440&fit=crop"
                     alt="Frontrow office" width="720" height="440">
            </div>
        </div>

        <!-- Right: service cards 2×2 -->
        <div class="services-cards">

            <div class="service-card">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
                <h3>Web Development</h3>
                <p>Creation of modern applications to help our customers expand business operations globally.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="5" y="2" width="14" height="20" rx="2"/>
                        <circle cx="12" cy="17" r="1" fill="currentColor"/>
                    </svg>
                </div>
                <h3>Mobile Development</h3>
                <p>Ensuring smooth operation of your mobile application on iOS and Android devices.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h3>UI/UX Design</h3>
                <p>Bridging the gap between technologies and users with easy-to-navigate solutions.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 20V10M12 20V4M6 20v-6"/>
                    </svg>
                </div>
                <h3>Data & Analytics</h3>
                <p>Transform raw data into actionable insights that drive smarter business decisions.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                    </svg>
                </div>
                <h3>Cloud / DevOps</h3>
                <p>Boosting delivery capacities and time to market while slashing infrastructure costs.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3>AI & Consultancy</h3>
                <p>Strategic IT guidance and AI integration to future-proof your organisation.</p>
            </div>

        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     CASE STUDIES
═══════════════════════════════════════════ -->
<section id="case-studies" class="case-studies">
    <div class="case-header">
        <div>
            <p class="section-tag">Our work</p>
            <h2 class="section-title">Case studies</h2>
            <p class="section-sub" style="margin-top:.5rem;">
                Our case studies describe design and development solutions we've implemented for our clients.
            </p>
        </div>
    </div>

    <div class="case-grid">

        <div class="case-card">
            {{-- Replace: 600×450px project screenshot --}}
            <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?w=600&h=450&fit=crop"
                 alt="Machine control app" width="600" height="450">
            <div class="case-overlay">
                <span class="case-tag">IoT / Mobile</span>
                <span class="case-title">Machine Control App</span>
                <div class="case-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        <div class="case-card">
            {{-- Replace: 600×450px project screenshot --}}
            <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=600&h=450&fit=crop"
                 alt="Furniture store" width="600" height="450">
            <div class="case-overlay">
                <span class="case-tag">E-Commerce</span>
                <span class="case-title">Furniture Store Platform</span>
                <div class="case-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        <div class="case-card">
            {{-- Replace: 600×450px project screenshot --}}
            <img src="https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=600&h=450&fit=crop"
                 alt="Finance app" width="600" height="450">
            <div class="case-overlay">
                <span class="case-tag">Fintech</span>
                <span class="case-title">LOCUS Finance App</span>
                <div class="case-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        <div class="case-card" style="grid-column: span 2;">
            {{-- Replace: 1200×450px dashboard screenshot --}}
            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&h=450&fit=crop"
                 alt="Analytics Dashboard" width="1200" height="450">
            <div class="case-overlay">
                <span class="case-tag">Web Design / Data</span>
                <span class="case-title">Analytics Dashboard Suite</span>
                <div class="case-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        <a href="#" class="case-cta">
            Explore more projects &nbsp;›
        </a>

    </div>
</section>


<!-- ═══════════════════════════════════════════
     ABOUT / PARTNER
═══════════════════════════════════════════ -->
<section id="about" style="padding: 6rem 3rem;">
    <div class="about">

        <div class="about-visual">
            <div class="about-img-main">
                {{-- Replace: 640×640px team/office circular image --}}
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=640&h=640&fit=crop"
                     alt="Frontrow team" width="640" height="640">
            </div>
            <!-- Stats badges -->
            <div class="about-stat about-stat-3">
                <div class="num">2k+</div>
                <div class="lbl">Brands served</div>
            </div>
            <div class="about-stat about-stat-1">
                <div class="num">1k+</div>
                <div class="lbl">Happy clients</div>
            </div>
            <div class="about-stat about-stat-2">
                <div class="num">7k+</div>
                <div class="lbl">Projects complete</div>
            </div>
        </div>

        <div class="about-text">
            <p class="section-tag">Who we are</p>
            <h2 class="section-title">Your dedicated technology partner</h2>
            <p class="section-sub" style="margin-top:1rem;">
                Since 2016, our dedicated team has been providing software development services
                across the globe and multiple industries. Our development expertise covers all major
                platforms including web, mobile, desktop, and cloud. Being an enterprise-oriented,
                full-stack company allows us to efficiently tackle and minimize response times.
            </p>
            <a href="#contact" class="link-gold">
                Tell us about your project
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

    </div>
</section>


<!-- ═══════════════════════════════════════════
     BLOG / SHARING KNOWLEDGE
═══════════════════════════════════════════ -->
<section id="blog" class="blog">
    <div class="blog-header">
        <div>
            <p class="section-tag">Insights</p>
            <h2 class="section-title">Sharing knowledge</h2>
            <p class="section-sub" style="margin-top:.5rem;">
                On our blog, we write about technology trends and provide valuable insights on how to digitalise businesses.
            </p>
        </div>
    </div>

    <div class="blog-grid">

        <div class="blog-card">
            <div class="blog-card-img">
                {{-- Replace: 600×320px blog post image --}}
                <img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=600&h=320&fit=crop"
                     alt="AI in e-learning" width="600" height="320">
            </div>
            <div class="blog-card-body">
                <p class="blog-card-tag">AI / Machine Learning</p>
                <h3>Application of ML and AI in e-Learning</h3>
                <div class="blog-card-arrow">
                    Read more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        <div class="blog-card">
            <div class="blog-card-img">
                {{-- Replace: 600×320px blog post image --}}
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&h=320&fit=crop"
                     alt="eHealth solutions" width="600" height="320">
            </div>
            <div class="blog-card-body">
                <p class="blog-card-tag">HealthTech</p>
                <h3>Top 5 eHealth Solutions for Patient Engagement</h3>
                <div class="blog-card-arrow">
                    Read more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        <div class="blog-card">
            <div class="blog-card-img">
                {{-- Replace: 600×320px blog post image --}}
                <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?w=600&h=320&fit=crop"
                     alt="UI/UX Design" width="600" height="320">
            </div>
            <div class="blog-card-body">
                <p class="blog-card-tag">Design</p>
                <h3>UI/UX Design for a Mobile Application</h3>
                <div class="blog-card-arrow">
                    Read more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        <a href="#" class="blog-card blog-card-cta" style="text-decoration:none; min-height:120px;">
            Read more stories &nbsp;›
        </a>

    </div>
</section>


<!-- ═══════════════════════════════════════════
     CONTACT
═══════════════════════════════════════════ -->
<div id="contact" class="contact-section">

    <div class="contact-info">
        <h2>Contacts</h2>
        <p><a href="mailto:hello@frontrow.com">hello@frontrow.com</a></p>
        <p><a href="tel:+2341234567890">+234 123 456 7890</a></p>
        <p style="margin-top:1rem; font-size:.85rem; color:rgba(255,255,255,.2); text-transform:uppercase; letter-spacing:.1em;">Follow</p>
        <div class="contact-social">
            <a href="#" class="social-link">M</a>
            <a href="#" class="social-link">Be</a>
            <a href="#" class="social-link">in</a>
            <a href="#" class="social-link">f</a>
        </div>
    </div>

    <div class="contact-form">
        <h2>Let's talk about your project</h2>
        <form method="POST" action="#">
            @csrf
            <div class="form-row">
                <div class="form-field">
                    <input type="text" name="name" placeholder="Your Full Name" required>
                </div>
                <div class="form-field">
                    <input type="email" name="email" placeholder="Your Email Address" required>
                </div>
            </div>
            <div class="form-field">
                <textarea name="message" placeholder="Project details"></textarea>
            </div>
            <div class="form-footer">
                <label class="upload-btn" for="file-upload">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                    </svg>
                    Upload file
                    <input id="file-upload" type="file" name="file" style="display:none;">
                </label>
                <button type="submit" class="btn-primary" style="padding:.7rem 2rem; font-size:.9rem;">
                    Send Message
                </button>
            </div>
        </form>
    </div>

</div>


<!-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ -->
<footer>
    <div class="logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
        </div>
        <span class="logo-name">Frontrow</span>
    </div>
    <p>&copy; {{ date('Y') }} Frontrow. All rights reserved.</p>
    <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#contact">Contact</a>
    </div>
</footer>

</body>
</html>