{{-- quizzes/categories.blade.php --}}
@extends('layouts.app')

@section('title', 'Developer Quiz Categories')
@section('meta_description', 'Test your knowledge with our developer quizzes. Choose from various categories like web development, programming languages, and algorithms. Instant results and challenges.')

@section('head')
<link rel="canonical" href="{{ route('quizzes.index') }}">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Quizzes", "item": "{{ route('quizzes.index') }}" }
    ]
}
</script>
@endsection

@section('content')

<style>
    /* ══════════════════════════════════════════════
       QUIZ CATEGORIES PAGE
    ══════════════════════════════════════════════ */

    .quiz-hero {
        padding: 4rem 0 2.5rem;
        text-align: center;
    }

    .quiz-hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5vw, 3.8rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.05;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .quiz-hero-title .quiz-accent { color: var(--brand); }

    /* Category card */
    .category-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 2rem 1.5rem;
        text-align: center;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: box-shadow var(--transition), transform var(--transition-spring), border-color var(--transition-fast);
    }

    /* Signature: corner accent glow on hover */
    .category-card::after {
        content: '';
        position: absolute;
        bottom: -40px;
        right: -40px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(22,163,74,.2) 0%, transparent 70%);
        opacity: 0;
        transition: opacity var(--transition), transform var(--transition-spring);
        transform: scale(.5);
    }

    .category-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-5px);
        color: inherit;
        border-color: rgba(22,163,74,.25);
    }

    .category-card:hover::after {
        opacity: 1;
        transform: scale(1.5);
    }

    .category-avatar {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--brand-pale-2);
        margin-bottom: 1rem;
        transition: transform var(--transition-spring);
    }

    .category-icon-wrap {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: var(--brand-pale);
        border: 2px solid rgba(22,163,74,.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
        transition: transform var(--transition-spring), background var(--transition-fast);
        position: relative;
        z-index: 1;
    }

    .category-card:hover .category-avatar,
    .category-card:hover .category-icon-wrap {
        transform: scale(1.1) rotate(-3deg);
        background: var(--brand-pale-2);
    }

    .category-name {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
        margin-bottom: .35rem;
        position: relative;
        z-index: 1;
    }

    .category-enter {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .72rem;
        font-weight: 700;
        color: var(--brand);
        margin-top: .75rem;
        opacity: 0;
        transform: translateY(4px);
        transition: opacity var(--transition-fast), transform var(--transition-fast);
        position: relative;
        z-index: 1;
    }

    .category-card:hover .category-enter {
        opacity: 1;
        transform: translateY(0);
    }

    /* Empty state */
    .empty-quiz {
        text-align: center;
        padding: 5rem 2rem;
        grid-column: 1 / -1;
    }
</style>

<div class="container-fluid px-lg-5 py-2">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="quiz-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width:fit-content;">
            <i class="bi bi-lightning-fill"></i> Quizzes
        </div>
        <h1 class="quiz-hero-title">
            Test your <span class="quiz-accent">knowledge.</span><br>
            Earn bragging rights.
        </h1>
        <p class="small mt-2" style="color:var(--muted); max-width:420px; margin:0 auto; line-height:1.65;">
            Choose a category below to start a timed challenge. Results are instant.
        </p>
    </div>

    {{-- ── Category Grid ────────────────────────────────── --}}
    <div class="section-divider mb-4 reveal">
        <p class="section-divider-label">Choose a category</p>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 pb-5">
        @forelse($categories as $category)
            <div class="col reveal stagger-{{ ($loop->index % 4) + 1 }}">
                <a href="{{ route('quizzes.showCategoryQuizzes', $category->slug) }}" class="category-card">

                    @if($category->image)
                        <img src="{{ Storage::url($category->image) }}"
                             alt="{{ $category->title }}"
                             class="category-avatar">
                    @else
                        <div class="category-icon-wrap">
                            <i class="bi bi-question-circle-fill" style="color:var(--brand);"></i>
                        </div>
                    @endif

                    <h3 class="category-name">{{ $category->title }}</h3>

                    <span class="category-enter">
                        Start test <i class="bi bi-arrow-right" style="font-size:.68rem;"></i>
                    </span>

                </a>
            </div>
        @empty
            <div class="empty-quiz">
                <span style="font-size:3rem; display:block; margin-bottom:1rem;">🧠</span>
                <h3 style="font-family:var(--font-display); font-weight:700; color:var(--ink); margin-bottom:.5rem;">No categories yet</h3>
                <p style="color:var(--muted); max-width:300px; margin:auto; font-size:.875rem;">Quiz categories are on their way. Check back soon!</p>
            </div>
        @endforelse
    </div>

</div>

@endsection