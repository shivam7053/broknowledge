{{-- home.blade.php --}}
@extends('layouts.app')

@section('title', 'Your Developer Workspace & Learning Hub')
@section('meta_description', 'BroKnowledge is a comprehensive platform offering coding courses, private browser-based office tools, online compilers for Python/Java/HTML, and brain-sharpening games.')

@section('content')

<style>
    /* ══════════════════════════════════════════════
       HOME PAGE STYLES
    ══════════════════════════════════════════════ */

    /* ── Hero ──────────────────────────────────── */
    .hero-section {
        padding: 5rem 0 4rem;
        position: relative;
        overflow: hidden;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        background: var(--brand-pale);
        color: var(--brand);
        border: 1px solid rgba(22,163,74,.2);
        border-radius: 100px;
        padding: .38rem .9rem;
        margin-bottom: 1.5rem;
    }

    .hero-pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--brand);
        animation: hero-blink 1.8s ease-in-out infinite;
    }

    @keyframes hero-blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: .3; }
    }

    .hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.8rem, 6vw, 4.8rem);
        font-weight: 800;
        line-height: 1.04;
        letter-spacing: -.04em;
        color: var(--ink);
        margin-bottom: 1.5rem;
    }

    .hero-title .line-accent {
        color: var(--brand);
        display: block;
        position: relative;
    }

    /* Kinetic word swap — signature element for Home */
    .swap-word {
        display: inline-block;
        position: relative;
        overflow: hidden;
        vertical-align: bottom;
        height: 1.8em; /* Increased height to prevent clipping during animation */
        min-width: 10ch;
    }

    .swap-word span {
        position: absolute;
        top: 0;
        left: 0;
        animation: swap-words 9s infinite;
        opacity: 0;
        transform: translateY(60%);
        transition: none;
        white-space: nowrap;
        color: var(--brand);
    }

    .swap-word span:nth-child(1) { animation-delay: 0s; }
    .swap-word span:nth-child(2) { animation-delay: 3s; }
    .swap-word span:nth-child(3) { animation-delay: 6s; }

    @keyframes swap-words {
        0%   { opacity: 0; transform: translateY(60%); }
        5%   { opacity: 1; transform: translateY(0); }
        28%  { opacity: 1; transform: translateY(0); }
        33%  { opacity: 0; transform: translateY(-60%); }
        100% { opacity: 0; transform: translateY(-60%); }
    }

    .hero-subtitle {
        font-size: 1.05rem;
        color: var(--muted);
        max-width: 520px;
        line-height: 1.7;
        margin-bottom: 2.25rem;
    }

    .hero-cta-primary {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: var(--brand);
        color: #fff;
        font-size: .875rem;
        font-weight: 700;
        padding: .8rem 1.6rem;
        border-radius: var(--radius-sm);
        text-decoration: none;
        border: none;
        transition: background var(--transition-fast), transform var(--transition-spring), box-shadow var(--transition-fast);
        box-shadow: 0 4px 14px rgba(22,163,74,.35);
    }

    .hero-cta-primary:hover {
        background: var(--brand-dark);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(22,163,74,.4);
    }

    .hero-cta-secondary {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        color: var(--ink);
        font-size: .875rem;
        font-weight: 600;
        padding: .8rem 1.6rem;
        border-radius: var(--radius-sm);
        text-decoration: none;
        border: 1.5px solid var(--card-border);
        background: var(--card-bg);
        transition: border-color var(--transition-fast), transform var(--transition-spring);
    }

    .hero-cta-secondary:hover {
        color: var(--ink);
        border-color: rgba(22,163,74,.35);
        background: var(--brand-pale);
        transform: translateY(-2px);
    }

    /* Hero stats row */
    .hero-stat {
        text-align: center;
        padding: 1.25rem 1.5rem;
        border-radius: var(--radius);
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
    }

    .hero-stat-num {
        font-family: var(--font-display);
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--brand);
        line-height: 1;
        margin-bottom: .2rem;
        letter-spacing: -.03em;
    }

    .hero-stat-label {
        font-size: .75rem;
        color: var(--muted);
        font-weight: 500;
    }

    /* ── Section headers ───────────────────────── */
    .section-head-title {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: clamp(1.5rem, 3vw, 2.1rem);
        letter-spacing: -.025em;
        color: var(--ink);
        margin-bottom: .3rem;
    }

    /* ── Course cards ──────────────────────────── */
    .home-course-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius);
        padding: 1.4rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
        transition: box-shadow var(--transition), transform var(--transition-spring);
    }

    .home-course-card::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--brand), var(--brand-mid));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .35s cubic-bezier(0.4,0,0.2,1);
    }

    .home-course-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-4px);
    }

    .home-course-card:hover::after { transform: scaleX(1); }

    .home-course-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-sm);
        background: var(--brand-pale);
        border: 1px solid rgba(22,163,74,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        flex-shrink: 0;
    }

    .home-course-title {
        font-family: var(--font-display);
        font-size: .95rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
        margin-bottom: .5rem;
    }

    .home-course-desc {
        font-size: .8rem;
        color: var(--muted);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
        margin-bottom: .9rem;
    }

    .home-course-link {
        font-size: .78rem;
        font-weight: 700;
        color: var(--brand);
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        transition: gap .2s;
    }
    .home-course-card:hover .home-course-link { gap: .55rem; }

    /* ── Blog cards ────────────────────────────── */
    .blog-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius);
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: box-shadow var(--transition), transform var(--transition-spring);
    }

    .blog-card:hover {
        box-shadow: var(--card-shadow-md);
        transform: translateY(-3px);
    }

    .blog-card-body { padding: 1.4rem; flex: 1; display: flex; flex-direction: column; }

    .blog-cat-badge {
        display: inline-block;
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--brand);
        background: var(--brand-pale);
        border: 1px solid rgba(22,163,74,.18);
        border-radius: 100px;
        padding: .22rem .65rem;
    }

    .blog-date {
        font-size: .72rem;
        color: var(--muted-light);
    }

    .blog-title {
        font-family: var(--font-display);
        font-size: .9rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
        line-height: 1.35;
        text-decoration: none;
        margin-bottom: .6rem;
        display: block;
    }

    .blog-title:hover { color: var(--brand); }

    .blog-excerpt {
        font-size: .78rem;
        color: var(--muted);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .blog-card-footer {
        padding: .85rem 1.4rem;
        border-top: 1px solid var(--card-border);
    }

    .read-link {
        font-size: .75rem;
        font-weight: 700;
        color: var(--brand);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        transition: gap .2s;
    }
    .blog-card:hover .read-link { gap: .55rem; }

    /* ── Features/tools strip ──────────────────── */
    .feature-strip {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow);
        padding: 2.5rem;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .feature-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .feature-item h5 {
        font-family: var(--font-display);
        font-size: .9rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
        margin-bottom: .25rem;
    }

    .feature-item p {
        font-size: .78rem;
        color: var(--muted);
        line-height: 1.55;
        margin: 0;
    }
</style>

<div class="py-2">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <section class="hero-section">
        <div class="container-fluid px-lg-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 reveal">
                    <div class="hero-pill">
                        <span class="hero-pill-dot"></span>
                        Open for learning
                    </div>

                    <h1 class="hero-title">
                        The place to<br>
                        <span class="line-accent">
                            <span class="swap-word">
                                <span>build skills.</span>
                                <span>write code.</span>
                                <span>level up.</span>
                            </span>
                        </span>
                    </h1>

                    <p class="hero-subtitle">
                        Courses, live compilers, and productivity tools — everything a developer needs, in one clean workspace.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="#courses" class="hero-cta-primary">
                            Explore Courses <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ route('blog.index') }}" class="hero-cta-secondary">
                            <i class="bi bi-newspaper"></i> Read Blog
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 reveal reveal-right">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="hero-stat">
                                <div class="hero-stat-num">{{ $courses->count() }}+</div>
                                <div class="hero-stat-label">Courses available</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hero-stat">
                                <div class="hero-stat-num">5+</div>
                                <div class="hero-stat-label">Mini games</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hero-stat">
                                <div class="hero-stat-num">10+</div>
                                <div class="hero-stat-label">Office tools</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="hero-stat">
                                <div class="hero-stat-num">100%</div>
                                <div class="hero-stat-label">Free to use</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Courses Section ─────────────────────────────── --}}
    <section id="courses" class="py-5">
        <div class="container-fluid px-lg-5">
            <div class="d-flex justify-content-between align-items-end mb-4 reveal">
                <div>
                    <div class="eyebrow mb-2"><i class="bi bi-mortarboard-fill"></i> Learning</div>
                    <h2 class="section-head-title">Featured Courses</h2>
                    <p class="small mb-0" style="color: var(--muted);">Structured paths to sharpen your technical skills.</p>
                </div>
                <a href="{{ route('courses.index') }}" class="read-link" style="color: var(--brand); text-decoration: none; font-size: .82rem; font-weight: 700; white-space: nowrap;">
                    All courses <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
                @foreach($courses->take(4) as $course)
                    <div class="col reveal stagger-{{ ($loop->index % 4) + 1 }}">
                        <a href="{{ route('courses.show', $course->slug) }}" class="home-course-card">
                            <div class="home-course-icon">
                                <svg width="20" height="20" fill="none" stroke="var(--brand)" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="home-course-title">{{ $course->title }}</div>
                            <p class="home-course-desc">Master fundamentals and advanced concepts through this comprehensive learning track.</p>
                            <span class="home-course-link">Start Learning <i class="bi bi-arrow-right" style="font-size:.75rem;"></i></span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Blog Section ────────────────────────────────── --}}
    <section class="py-5">
        <div class="container-fluid px-lg-5">
            <div class="d-flex justify-content-between align-items-end mb-4 reveal">
                <div>
                    <div class="eyebrow mb-2"><i class="bi bi-newspaper"></i> Articles</div>
                    <h2 class="section-head-title">Latest from the Blog</h2>
                    <p class="small mb-0" style="color: var(--muted);">Deep dives, tutorials, and developer insights.</p>
                </div>
                <a href="{{ route('blog.index') }}" class="read-link" style="color: var(--brand); text-decoration: none; font-size: .82rem; font-weight: 700; white-space: nowrap;">
                    All articles <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                @foreach($posts->take(4) as $post)
                    <div class="col reveal stagger-{{ ($loop->index % 4) + 1 }}">
                        <article class="blog-card">
                            <div class="blog-card-body">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="blog-cat-badge">{{ $post->category->title }}</span>
                                    <span class="blog-date">{{ $post->created_at->format('M d') }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="blog-title">{{ $post->title }}</a>
                                <p class="blog-excerpt">{!! strip_tags(htmlspecialchars_decode($post->html_content)) !!}</p>
                            </div>
                            <div class="blog-card-footer">
                                <a href="{{ route('blog.show', $post->slug) }}" class="read-link">
                                    Read <i class="bi bi-arrow-right" style="font-size:.72rem;"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Features strip ──────────────────────────────── --}}
    <section class="py-5">
        <div class="container-fluid px-lg-5">
            <div class="feature-strip reveal">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-item">
                            <div class="feature-icon-wrap" style="background: var(--brand-pale);">🎮</div>
                            <div>
                                <h5>Game Zone</h5>
                                <p>Snake, Tetris, Memory cards and more. Take a break and keep your mind sharp.</p>
                                <a href="{{ route('games') }}" class="read-link" style="color: var(--brand); font-size:.75rem; font-weight: 700; text-decoration: none; display:inline-flex; align-items:center; gap:.3rem;">
                                    Play now <i class="bi bi-arrow-right" style="font-size:.7rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <div class="feature-icon-wrap" style="background: rgba(99,102,241,.1);">⚙️</div>
                            <div>
                                <h5>Office Tools</h5>
                                <p>PDF merger, image compressor, Markdown editor — all client-side, all private.</p>
                            <a href="{{ route('tools.index') }}" class="read-link" style="color: var(--brand); font-size:.75rem; font-weight: 700; text-decoration: none; display:inline-flex; align-items:center; gap:.3rem;">
                                    Open tools <i class="bi bi-arrow-right" style="font-size:.7rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-item">
                            <div class="feature-icon-wrap" style="background: rgba(6,182,212,.1);">💻</div>
                            <div>
                                <h5>Code Playground</h5>
                                <p>Run Python, preview HTML/CSS/JS, and experiment with Java — right in the browser.</p>
                                <a href="{{ route('compilers') }}" class="read-link" style="color: var(--brand); font-size:.75rem; font-weight: 700; text-decoration: none; display:inline-flex; align-items:center; gap:.3rem;">
                                    Start coding <i class="bi bi-arrow-right" style="font-size:.7rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection