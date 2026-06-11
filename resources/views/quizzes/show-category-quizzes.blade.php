{{-- quizzes/show-category-quizzes.blade.php --}}
@extends('layouts.app')

@section('title', $quizCategory->title . ' Tests')

@section('content')

<style>
    /* ══════════════════════════════════════════════
       QUIZ LISTING PAGE
    ══════════════════════════════════════════════ */

    .quiz-list-hero {
        padding: 3.5rem 0 2.5rem;
    }

    .quiz-list-hero-inner {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow-md);
        border-radius: var(--radius-xl);
        padding: 3rem 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .quiz-list-hero-inner::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 240px; height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(22,163,74,.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .quiz-list-hero-inner::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(22,163,74,.07) 0%, transparent 70%);
        pointer-events: none;
    }

    .quiz-list-title {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 800;
        letter-spacing: -.035em;
        line-height: 1.1;
        color: var(--ink);
        margin-bottom: .6rem;
    }

    .quiz-list-title .accent { color: var(--brand); }

    /* Quiz cards */
    .quiz-item-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
        transition: box-shadow var(--transition), transform var(--transition-spring), border-color var(--transition-fast);
    }

    .quiz-item-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--brand), var(--brand-mid));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .35s cubic-bezier(0.4,0,0.2,1);
    }

    .quiz-item-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-4px);
        color: inherit;
        border-color: rgba(22,163,74,.2);
    }

    .quiz-item-card:hover::before { transform: scaleX(1); }

    .quiz-item-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: var(--brand-pale);
        border: 2px solid rgba(22,163,74,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 1rem;
        transition: transform var(--transition-spring);
    }

    .quiz-item-card:hover .quiz-item-icon {
        transform: scale(1.1);
    }

    .quiz-item-title {
        font-family: var(--font-display);
        font-size: .97rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
        margin-bottom: .5rem;
    }

    .quiz-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .75rem;
        margin-bottom: 1.25rem;
    }

    .quiz-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: .22rem .65rem;
        border-radius: 100px;
        background: var(--surface-2);
        border: 1px solid var(--card-border);
        color: var(--muted);
    }

    .start-quiz-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: var(--brand);
        color: #fff;
        font-size: .78rem;
        font-weight: 700;
        padding: .55rem 1.3rem;
        border-radius: 100px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(22,163,74,.3);
        transition: background var(--transition-fast), transform var(--transition-spring);
        margin-top: auto;
    }

    .start-quiz-btn:hover { background: var(--brand-dark); color: #fff; transform: translateY(-1px); }

    /* Back link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .8rem;
        font-weight: 600;
        color: var(--muted);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    .back-link:hover { color: var(--brand); }
</style>

<div class="container-fluid px-lg-5 py-2">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="quiz-list-hero reveal">
        <div class="quiz-list-hero-inner">
            <a href="{{ route('quizzes.index') }}" class="back-link mb-3 d-inline-flex">
                <i class="bi bi-arrow-left"></i> All categories
            </a>
            <div class="eyebrow mb-2" style="width:fit-content;">
                <i class="bi bi-lightning-fill"></i> {{ $quizCategory->title }}
            </div>
            <h1 class="quiz-list-title">
                {{ $quizCategory->title }}<br>
                <span class="accent">Tests & Challenges</span>
            </h1>
            <p style="color:var(--muted); font-size:.9rem; max-width:460px; line-height:1.65; margin-bottom:0;">
                Select a test below to begin your timed challenge. Each attempt is scored instantly.
            </p>
        </div>
    </div>

    {{-- ── Quiz Grid ────────────────────────────────────── --}}
    <div class="section-divider mb-4 reveal">
        <p class="section-divider-label">{{ $quizzes->count() }} {{ Str::plural('test', $quizzes->count()) }} available</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 pb-5">
        @forelse($quizzes as $quiz)
            <div class="col reveal stagger-{{ ($loop->index % 3) + 1 }}">
                <div class="quiz-item-card">
                    <div class="quiz-item-icon">⚡</div>
                    <h3 class="quiz-item-title">{{ $quiz->title }}</h3>
                    <div class="quiz-meta">
                        <span class="quiz-meta-pill">
                            <i class="bi bi-list-ol" style="font-size:.7rem;"></i>
                            {{ $quiz->questions->count() }} Qs
                        </span>
                        <span class="quiz-meta-pill">
                            <i class="bi bi-clock" style="font-size:.7rem;"></i>
                            {{ $quiz->time_limit }} min
                        </span>
                    </div>
                    <a href="{{ route('quizzes.take', $quiz->slug) }}" class="start-quiz-btn">
                        <i class="bi bi-play-fill"></i> Start Test
                    </a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <span style="font-size:2.5rem; display:block; margin-bottom:1rem;">📋</span>
                <h3 style="font-family:var(--font-display); font-weight:700; color:var(--ink); margin-bottom:.5rem; font-size:1.2rem;">No tests here yet</h3>
                <p style="color:var(--muted); font-size:.875rem; margin-bottom:1.5rem;">Tests for this category are being prepared. Check back soon!</p>
                <a href="{{ route('quizzes.index') }}" class="back-link" style="font-size:.875rem;">
                    <i class="bi bi-arrow-left"></i> Back to all categories
                </a>
            </div>
        @endforelse
    </div>

</div>

@endsection