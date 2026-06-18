{{-- courses/index.blade.php --}}
@extends('layouts.app')

@section('title', 'All Courses')
@section('meta_description', 'Browse our catalog of structured developer courses. Master web development, software architecture, and coding fundamentals with our step-by-step guides.')

@section('head')
<link rel="canonical" href="{{ route('courses.index') }}">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Courses", "item": "{{ route('courses.index') }}" }
    ]
}
</script>
@endsection

@section('content')

<style>
    /* ══════════════════════════════════════════════
       COURSES INDEX PAGE
    ══════════════════════════════════════════════ */

    .catalog-header {
        padding: 4.5rem 0 3rem;
        position: relative;
    }

    .catalog-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 65% 55% at 55% 0%, rgba(34,197,94,.1) 0%, transparent 65%);
        pointer-events: none;
    }

    .catalog-title {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: clamp(2.5rem, 5.5vw, 4rem);
        line-height: 1.04;
        letter-spacing: -.04em;
        color: var(--ink);
        margin-bottom: 1rem;
    }

    .catalog-title .accent { color: var(--brand); }

    .catalog-subtitle {
        font-size: 1rem;
        color: var(--muted);
        line-height: 1.7;
        max-width: 480px;
        margin: 0 auto 1.5rem;
    }

    .count-badge {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 100px;
        padding: .5rem 1.1rem;
        font-size: .8rem;
        font-weight: 600;
        color: var(--muted);
        box-shadow: var(--card-shadow);
    }

    .count-badge strong {
        color: var(--brand);
        font-family: var(--font-display);
        font-size: 1rem;
    }

    /* Featured course */
    .featured-course {
        display: grid;
        grid-template-columns: .9fr 1.1fr;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow-md);
        background: var(--card-bg);
        text-decoration: none;
        color: inherit;
        min-height: 280px;
        transition: box-shadow var(--transition), transform var(--transition-spring);
    }

    .featured-course:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-3px);
        color: inherit;
    }

    .featured-visual {
        background: linear-gradient(135deg, #15803d 0%, #16a34a 50%, #22c55e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 2.5rem;
    }

    .featured-visual::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 80% 20%, rgba(255,255,255,.12) 0%, transparent 50%),
                          radial-gradient(circle at 20% 80%, rgba(0,0,0,.1) 0%, transparent 50%);
    }

    .featured-visual-icon {
        width: 90px;
        height: 90px;
        border-radius: 24px;
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,.25);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
    }

    .featured-start-badge {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        z-index: 1;
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #fff;
        background: rgba(255,255,255,.2);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 100px;
        padding: .28rem .7rem;
    }

    .featured-body {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .featured-label {
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--brand);
        margin-bottom: .6rem;
    }

    .featured-title {
        font-family: var(--font-display);
        font-size: 1.65rem;
        font-weight: 700;
        line-height: 1.15;
        color: var(--ink);
        margin-bottom: .8rem;
        letter-spacing: -.02em;
    }

    .featured-desc {
        font-size: .875rem;
        color: var(--muted);
        line-height: 1.65;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .go-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .82rem;
        font-weight: 700;
        color: var(--brand);
        transition: gap .2s;
    }

    .featured-course:hover .go-link { gap: .7rem; }

    /* Course grid cards */
    .course-grid-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: box-shadow var(--transition), transform var(--transition-spring);
    }

    .course-grid-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--brand), var(--brand-mid));
        transition: right .38s cubic-bezier(0.4,0,0.2,1);
    }

    .course-grid-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-4px) rotate(.25deg);
        color: inherit;
    }

    .course-grid-card:hover::before { right: 0; }

    .course-grid-body {
        padding: 1.4rem 1.4rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .course-grid-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-sm);
        background: var(--brand-pale);
        border: 1px solid rgba(22,163,74,.14);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: .9rem;
    }

    .course-num {
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .13em;
        text-transform: uppercase;
        color: var(--brand);
        font-family: var(--font-display);
        margin-bottom: .3rem;
    }

    .course-grid-title {
        font-family: var(--font-display);
        font-size: .97rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.015em;
        line-height: 1.3;
        margin-bottom: .55rem;
    }

    .course-grid-desc {
        font-size: .78rem;
        color: var(--muted);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .course-grid-footer {
        padding: .85rem 1.4rem;
        border-top: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .start-arrow {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1.5px solid rgba(22,163,74,.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        color: var(--brand);
        transition: background var(--transition-fast), border-color var(--transition-fast), color var(--transition-fast);
    }

    .course-grid-card:hover .start-arrow {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .empty-courses { text-align: center; padding: 6rem 2rem; }

    @media (max-width: 767px) {
        .featured-course { grid-template-columns: 1fr; }
        .featured-visual { min-height: 170px; }
        .featured-body { padding: 1.75rem; }
        .featured-title { font-size: 1.3rem; }
    }
</style>

<div class="container-fluid px-lg-5 py-2">

    <header class="catalog-header text-center reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content;">
            <i class="bi bi-mortarboard-fill"></i> Course Catalog
        </div>
        <h1 class="catalog-title">
            Everything you need<br>to <span class="accent">master your craft.</span>
        </h1>
        <p class="catalog-subtitle">
            Structured learning paths built for developers — from foundations to advanced architecture.
        </p>
        <div class="count-badge mx-auto">
            <strong>{{ $courses->count() }}</strong>
            {{ Str::plural('course', $courses->count()) }} available
            <span style="opacity:.35;">·</span>
            <span style="color: var(--brand); font-weight: 700; font-size: .75rem;">Always growing</span>
        </div>
    </header>

    @if($courses->count() > 0)
        @php $featured = $courses->first(); $rest = $courses->skip(1); @endphp

        <div class="mb-5 reveal">
            <div class="section-divider mb-3">
                <p class="section-divider-label">Featured</p>
            </div>
            <a href="{{ route('courses.show', $featured->slug) }}" class="featured-course">
                <div class="featured-visual">
                    <span class="featured-start-badge">⭐ Start Here</span>
                    <div class="featured-visual-icon">
                        <svg width="44" height="44" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <div class="featured-body">
                    <div class="featured-label">Recommended — Start here</div>
                    <h2 class="featured-title">{{ $featured->title }}</h2>
                    <p class="featured-desc">{{ $featured->description ?? 'A comprehensive learning track covering core concepts, practical patterns, and real-world application. Perfect for developers ready to go deeper.' }}</p>
                    <span class="go-link">Begin course <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
        </div>

        @if($rest->count() > 0)
        <div class="reveal">
            <div class="section-divider">
                <p class="section-divider-label">All Courses</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($rest as $course)
                <div class="col reveal stagger-{{ ($loop->index % 3) + 1 }}">
                    <a href="{{ route('courses.show', $course->slug) }}" class="course-grid-card">
                        <div class="course-grid-body">
                            <div class="course-grid-icon">
                                <svg width="20" height="20" fill="none" stroke="var(--brand)" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="course-num">Course {{ $loop->iteration + 1 }}</div>
                            <h3 class="course-grid-title">{{ $course->title }}</h3>
                            <p class="course-grid-desc">{{ $course->description ?? 'Master the fundamentals and advanced concepts through hands-on projects and structured modules.' }}</p>
                        </div>
                        <div class="course-grid-footer">
                            <span class="go-link" style="font-size:.78rem;">Start learning</span>
                            <div class="start-arrow"><i class="bi bi-arrow-right"></i></div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    @else
        <div class="empty-courses reveal">
            <span style="font-size:3rem; display:block; margin-bottom:1rem;">📚</span>
            <h3 style="font-family:var(--font-display); font-weight:700; color:var(--ink); margin-bottom:.5rem;">No courses yet</h3>
            <p style="color:var(--muted); max-width:320px; margin:auto; font-size:.875rem;">We're building something great. New courses drop regularly — check back soon.</p>
        </div>
    @endif

</div>

@endsection