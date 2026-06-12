<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :data-bs-theme="darkMode ? 'dark' : 'light'">
<head>
    <script>
        // Prevent theme flash before Alpine initializes
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BroKnowledge — @yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Typography: Syne (display) + Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ═══════════════════════════════════════════════════
           BROKNOWLEDGE — GLOBAL DESIGN SYSTEM
           ═══════════════════════════════════════════════════ */

        /* ── Design Tokens ─────────────────────────────────── */
        :root {
            --brand:          #16a34a;
            --brand-mid:      #22c55e;
            --brand-dark:     #15803d;
            --brand-pale:     #f0faf4;
            --brand-pale-2:   #dcfce7;

            --ink:            #0f1117;
            --ink-2:          #374151;
            --muted:          #6b7280;
            --muted-light:    #9ca3af;

            --surface:        #f8fafb;
            --surface-2:      #f1f5f9;
            --card-bg:        #ffffff;
            --card-border:    rgba(0, 0, 0, 0.07);
            --card-shadow:    0 1px 3px rgba(0,0,0,.06), 0 0 0 1px var(--card-border);
            --card-shadow-md: 0 4px 16px rgba(0,0,0,.08), 0 0 0 1px var(--card-border);
            --card-shadow-hover: 0 12px 32px rgba(22,163,74,.14), 0 0 0 1.5px rgba(22,163,74,.22);

            --nav-bg:         rgba(255,255,255,0.88);
            --nav-border:     rgba(0,0,0,0.08);

            --radius-sm:      8px;
            --radius:         14px;
            --radius-lg:      20px;
            --radius-xl:      28px;

            --transition-fast: 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            --transition:      0.28s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-spring: 0.36s cubic-bezier(0.34, 1.56, 0.64, 1);

            --font-display: 'Syne', system-ui, sans-serif;
            --font-body:    'Inter', system-ui, sans-serif;
        }

        [data-bs-theme="dark"] {
            --brand-pale:     rgba(22,163,74,.1);
            --brand-pale-2:   rgba(22,163,74,.18);
            --ink:            #f1f5f9;
            --ink-2:          #cbd5e1;
            --muted:          #94a3b8;
            --muted-light:    #64748b;
            --surface:        #0d1117;
            --surface-2:      #161b27;
            --card-bg:        #1a2035;
            --card-border:    rgba(255,255,255,.07);
            --card-shadow:    0 1px 3px rgba(0,0,0,.3), 0 0 0 1px var(--card-border);
            --card-shadow-md: 0 4px 16px rgba(0,0,0,.35), 0 0 0 1px var(--card-border);
            --card-shadow-hover: 0 12px 32px rgba(22,163,74,.2), 0 0 0 1.5px rgba(22,163,74,.3);
            --nav-bg:         rgba(13,17,23,0.88);
            --nav-border:     rgba(255,255,255,.07);
        }

        /* ── Base ──────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background-color: var(--surface);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
            transition: background-color .3s, color .3s;
        }

        /* ── Reveal System ─────────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .7s cubic-bezier(0.4,0,0.2,1),
                        transform .7s cubic-bezier(0.4,0,0.2,1);
            will-change: transform, opacity;
        }
        .reveal.visible { opacity: 1; transform: none; }
        .reveal-left  { transform: translateX(-24px); }
        .reveal-right { transform: translateX(24px); }
        .reveal-left.visible, .reveal-right.visible { transform: none; }
        .stagger-1 { transition-delay: .08s; }
        .stagger-2 { transition-delay: .16s; }
        .stagger-3 { transition-delay: .24s; }

        /* ── Global Hidden Alpine ──────────────────────────── */
        [x-cloak] { display: none !important; }

        /* ── Scrollbar ─────────────────────────────────────── */
        ::-webkit-scrollbar { width: 7px; height: 7px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,.14); border-radius: 4px; }
        [data-bs-theme="dark"] ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); }

        /* ── Float animation ───────────────────────────────── */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-12px); }
        }
        .animate-float { animation: float 7s ease-in-out infinite; }

        /* ══════════════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════════════ */
        .bk-nav {
            background: var(--nav-bg);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border-bottom: 1px solid var(--nav-border);
            padding: .875rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: background .3s;
        }

        .bk-nav .navbar-brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -.03em;
            color: var(--brand) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .nav-logo, .footer-logo {
            height: 50px;
            width: auto;
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--brand);
            display: inline-block;
            animation: brand-pulse 3s ease-in-out infinite;
        }

        @keyframes brand-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .6; transform: scale(.85); }
        }

        /* Nav links */
        .bk-nav-link {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .82rem;
            font-weight: 600;
            color: var(--muted) !important;
            text-decoration: none;
            padding: .4rem .75rem;
            border-radius: var(--radius-sm);
            transition: color var(--transition-fast), background var(--transition-fast);
            letter-spacing: .01em;
            position: relative;
        }

        .bk-nav-link .material-symbols-outlined {
            font-size: 1.1rem;
        }

        .bk-nav-link:hover {
            color: var(--ink) !important;
            background: var(--brand-pale);
        }

        .bk-nav-link.active {
            color: var(--brand) !important;
            background: var(--brand-pale);
        }

        /* Accent links (tools, compilers) */
        .bk-nav-link.accent {
            color: var(--brand) !important;
        }

        /* Theme toggle */
        .theme-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--card-border);
            background: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background var(--transition-fast), transform var(--transition-fast);
            box-shadow: var(--card-shadow);
        }
        .theme-btn:hover { transform: scale(1.08); background: var(--brand-pale); }

        /* Game mode toggle */
        .game-toggle-wrap {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem .75rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--card-border);
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            font-size: .75rem;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            transition: border-color var(--transition-fast);
        }
        .game-toggle-wrap:hover { border-color: rgba(22,163,74,.3); }

        .game-toggle-wrap .form-check-input:checked { background-color: var(--brand); border-color: var(--brand); }

        /* ══════════════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════════════ */
        .bk-footer {
            margin-top: 5rem;
            border-top: 1px solid var(--card-border);
            background: var(--card-bg);
            padding: 4rem 0 2rem;
        }

        .footer-brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -.03em;
            color: var(--brand) !important;
            text-decoration: none;
        }

        .footer-social {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--card-border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            text-decoration: none;
            font-size: .9rem;
            transition: border-color var(--transition-fast), color var(--transition-fast), background var(--transition-fast);
        }
        .footer-social:hover {
            border-color: var(--brand);
            color: var(--brand);
            background: var(--brand-pale);
        }

        .footer-link {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            font-size: .82rem;
            color: var(--muted);
            text-decoration: none;
            transition: color var(--transition-fast);
        }

        .footer-link:hover { color: var(--brand); }

        .footer-link .material-symbols-outlined {
            font-size: 1rem;
        }

        .footer-heading {
            font-family: var(--font-display);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ink);
            margin-bottom: 1rem;
        }

        /* ══════════════════════════════════════════════════
           SHARED GLASS CARD
        ══════════════════════════════════════════════════ */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            box-shadow: var(--card-shadow);
            border-radius: var(--radius-lg);
            transition: box-shadow var(--transition), transform var(--transition);
        }

        /* ══════════════════════════════════════════════════
           SHARED EYEBROW / LABEL UTILS
        ══════════════════════════════════════════════════ */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: var(--brand);
            background: var(--brand-pale);
            border: 1px solid rgba(22,163,74,.18);
            border-radius: 100px;
            padding: .32rem .8rem;
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .section-divider-label {
            font-family: var(--font-display);
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
            white-space: nowrap;
            margin: 0;
        }
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--card-border);
        }

        /* ══════════════════════════════════════════════════
           CUBE BACKGROUND (refined)
        ══════════════════════════════════════════════════ */
        .cube-wrapper {
            position: fixed;
            top: 50%;
            left: 50%;
            width: var(--cube-size);
            height: var(--cube-size);
            perspective: 1000px;
            z-index: 0;
            pointer-events: none;
        }
        .cube {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transform: rotateX(calc(var(--rotateX) + var(--baseRotate))) rotateY(calc(var(--rotateY) + var(--baseRotate)));
            transition: transform 0.12s linear;
        }
        .face {
            position: absolute;
            width: var(--cube-size);
            height: var(--cube-size);
            background: rgba(22,163,74,.07);
            border: 1px solid rgba(22,163,74,.12);
        }
        [data-bs-theme="dark"] .face {
            background: rgba(22,163,74,.05);
            border: 1px solid rgba(22,163,74,.15);
        }
        .front  { transform: rotateY(0deg)    translateZ(calc(var(--cube-size) / 2)); }
        .back   { transform: rotateX(180deg)  translateZ(calc(var(--cube-size) / 2)); }
        .right  { transform: rotateY(90deg)   translateZ(calc(var(--cube-size) / 2)); }
        .left   { transform: rotateY(-90deg)  translateZ(calc(var(--cube-size) / 2)); }
        .top    { transform: rotateX(90deg)   translateZ(calc(var(--cube-size) / 2)); }
        .bottom { transform: rotateX(-90deg)  translateZ(calc(var(--cube-size) / 2)); }

        /* Ensure main content sits above cube */
        main { position: relative; z-index: 1; }

        /* ── Preloader ─────────────────────────────────────── */
        #loader-wrapper {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .4s ease-out, visibility .4s;
        }

        .loader-content {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            z-index: 2;
        }

        .loader-circle {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 3px solid var(--card-border);
            border-top: 3px solid var(--brand);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        body.loaded #loader-wrapper {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>

<body class="loading">
    <!-- Preloader -->
    <div id="loader-wrapper">
        <div class="loader-content">
            <div class="loader-circle"></div>
            <img src="{{ asset('logo/logo.png') }}" alt="Loading..." class="loader-logo">
        </div>
    </div>

    <!-- Scroll reveal initializer -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
        });

        window.addEventListener('load', () => {
            document.body.classList.remove('loading');
            document.body.classList.add('loaded');
        });
    </script>

    <!-- Ambient cube bg -->
    <div class="cube-wrapper"
         x-data="{ rotationX: 0, rotationY: 0, baseRotate: 0, scrollScale: 1 }"
         x-init="setInterval(() => { baseRotate += 0.15 }, 30)"
         @mousemove.window="
             rotationY = ((($event.clientX - window.innerWidth/2) / (window.innerWidth/2)) * 28);
             rotationX = -(($event.clientY - window.innerHeight/2) / (window.innerHeight/2)) * 28;
         "
         @scroll.window="scrollScale = Math.max(0.05, 1 - (window.scrollY / 3500))"
         :style="{
             '--rotateX': rotationX + 'deg',
             '--rotateY': rotationY + 'deg',
             '--cube-size': '480px',
             '--baseRotate': baseRotate + 'deg',
             'transform': `translate(-50%, -50%) scale(${scrollScale})`,
             'opacity': 0.12 + scrollScale * 0.18
         }">
        <div class="cube">
            <div class="face front"></div>
            <div class="face back"></div>
            <div class="face right"></div>
            <div class="face left"></div>
            <div class="face top"></div>
            <div class="face bottom"></div>
        </div>
    </div>

    <!-- ══ NAVBAR ═════════════════════════════════════════ -->
    <nav class="bk-nav">
        <div class="container-fluid px-lg-5 d-flex align-items-center justify-content-between gap-3">

            <a href="/" class="navbar-brand">
                <img :src="darkMode ? '{{ asset('logo/logo-dark.png') }}' : '{{ asset('logo/logo-light.png') }}'" alt="BroKnowledge Logo" class="nav-logo">
            </a>

            <div class="d-flex align-items-center gap-2">
                <!-- Controls -->
                <div class="d-flex align-items-center gap-2 me-2">
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                            class="theme-btn" :title="darkMode ? 'Light mode' : 'Dark mode'">
                        <span x-show="!darkMode" class="material-symbols-outlined">dark_mode</span>
                        <span x-show="darkMode" x-cloak class="material-symbols-outlined">light_mode</span>
                    </button>

                    <label class="game-toggle-wrap mb-0" for="gameModeToggle">
                        <input class="form-check-input mt-0" type="checkbox" role="switch" id="gameModeToggle"
                               {{ request()->routeIs('games') ? 'checked' : '' }}
                               onchange="window.location.href = this.checked ? '{{ route('games') }}' : '{{ route('home') }}'">
                        <span class="d-none d-sm-inline">Game Mode</span>
                        <span class="d-sm-none">🎮</span>
                    </label>
                </div>

                @if (!request()->routeIs('games'))
                    <!-- Nav links -->
                    <div class="d-flex align-items-center gap-1">
                        <a href="{{ route('courses.index') }}"
                           class="bk-nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">school</span> Courses
                        </a>
                        <a href="{{ route('blog.index') }}"
                           class="bk-nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">article</span> Blog
                        </a>
                        <a href="{{ route('tools.index') }}"
                           class="bk-nav-link accent {{ request()->routeIs('tools.*') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">build</span> Tools
                        </a>
                        <a href="{{ route('quizzes.index') }}"
                           class="bk-nav-link accent {{ request()->routeIs('quizzes.*') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">quiz</span> Quizzes
                        </a>
                        <a href="{{ route('apis.index') }}"
                           class="bk-nav-link accent {{ request()->routeIs('apis.*') ? 'active' : '' }}">
                            <span class="material-symbols-outlined">api</span> Free APIs
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- ══ PAGE CONTENT ═══════════════════════════════════ -->
    <main>@yield('content')</main>

    <!-- ══ FOOTER ════════════════════════════════════════ -->
    <footer class="bk-footer reveal">
        <div class="container-fluid px-lg-5">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a href="/" class="footer-brand d-inline-flex align-items-center gap-2 mb-3">
                        <img :src="darkMode ? '{{ asset('logo/logo-dark.png') }}' : '{{ asset('logo/logo-light.png') }}'" alt="BroKnowledge Footer Logo" class="footer-logo">
                    </a>
                    <p class="small mb-4" style="color: var(--muted); line-height: 1.7; max-width: 280px;">
                        A workspace for developers and learners — courses, tools, compilers, and games, all in one place.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="footer-social"><i class="bi bi-github"></i></a>
                        <a href="#" class="footer-social"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="footer-social"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2 offset-lg-2">
                    <div class="footer-heading">Explore</div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('courses.index') }}" class="footer-link">
                            <span class="material-symbols-outlined">school</span> Courses
                        </a>
                        <a href="{{ route('blog.index') }}" class="footer-link">
                            <span class="material-symbols-outlined">article</span> Blog
                        </a>
                        <a href="{{ route('games') }}" class="footer-link">
                            <span class="material-symbols-outlined">sports_esports</span> Game Zone
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <div class="footer-heading">Workspace</div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('tools.index') }}" class="footer-link">
                            <span class="material-symbols-outlined">build</span> Office Tools
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="footer-link">
                            <span class="material-symbols-outlined">quiz</span> Quizzes
                        </a>
                        <a href="{{ route('apis.index') }}" class="footer-link">
                            <span class="material-symbols-outlined">api</span> Free APIs
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <div class="footer-heading">Legal</div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('privacy') }}" class="footer-link">
                            <span class="material-symbols-outlined">shield_lock</span> Privacy Policy
                        </a>
                        <a href="{{ route('terms') }}" class="footer-link">
                            <span class="material-symbols-outlined">gavel</span> Terms of Use
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3"
                 style="border-top: 1px solid var(--card-border);">
                <p class="small mb-0" style="color: var(--muted-light);">&copy; {{ date('Y') }} BroKnowledge. All rights reserved.</p>
                <p class="small mb-0" style="color: var(--muted-light);">Built with ❤️ for the community.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>