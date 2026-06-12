{{-- blog/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Blog')

@section('content')

<style>
    /* ══════════════════════════════════════════════
       BLOG INDEX PAGE
    ══════════════════════════════════════════════ */

    .blog-hero {
        padding: 4rem 0 2.5rem;
    }

    .blog-hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5vw, 3.8rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.05;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .blog-hero-title .accent { color: var(--brand); }

    /* ── Featured post (first one) ─────────────── */
    .featured-post {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow-md);
        border-radius: var(--radius-lg);
        display: grid;
        grid-template-columns: 1fr 1fr;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        min-height: 260px;
        transition: box-shadow var(--transition), transform var(--transition-spring);
    }

    .featured-post:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-3px);
        color: inherit;
    }

    .featured-post-visual {
        background: linear-gradient(135deg, #0f1117 0%, #1a2035 60%, #16a34a 200%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .featured-post-visual::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 30% 50%, rgba(22,163,74,.18) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 80% 80%, rgba(99,102,241,.1) 0%, transparent 50%);
    }

    .featured-post-visual-icon {
        font-size: 3.5rem;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 0 20px rgba(22,163,74,.4));
    }

    .featured-post-body {
        padding: 2.25rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .post-cat-badge {
        display: inline-flex;
        align-items: center;
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--brand);
        background: var(--brand-pale);
        border: 1px solid rgba(22,163,74,.18);
        border-radius: 100px;
        padding: .22rem .7rem;
        width: fit-content;
        margin-bottom: .75rem;
    }

    .post-date {
        font-size: .72rem;
        color: var(--muted-light);
    }

    .featured-post-title {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -.02em;
        line-height: 1.2;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .featured-post-excerpt {
        font-size: .85rem;
        color: var(--muted);
        line-height: 1.65;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .read-more-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .8rem;
        font-weight: 700;
        color: var(--brand);
        transition: gap .2s;
    }

    .featured-post:hover .read-more-link { gap: .7rem; }

    /* ── Post grid cards ───────────────────────── */
    .post-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        position: relative;
        transition: box-shadow var(--transition), transform var(--transition-spring);
    }

    /* Signature: color-coded left accent bar per category */
    .post-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--brand), var(--brand-mid));
        opacity: 0;
        transition: opacity var(--transition-fast);
    }

    .post-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-4px) rotate(.2deg);
        color: inherit;
    }

    .post-card:hover::before { opacity: 1; }

    .post-card-body {
        padding: 1.4rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .post-card-title {
        font-family: var(--font-display);
        font-size: .95rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.015em;
        line-height: 1.35;
        margin-bottom: .55rem;
        transition: color var(--transition-fast);
    }

    .post-card:hover .post-card-title { color: var(--brand); }

    .post-card-excerpt {
        font-size: .78rem;
        color: var(--muted);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .post-card-footer {
        padding: .8rem 1.4rem;
        border-top: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .post-arrow {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: 1.5px solid rgba(22,163,74,.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .68rem;
        color: var(--brand);
        transition: background var(--transition-fast), border-color var(--transition-fast), color var(--transition-fast), transform var(--transition-spring);
    }

    .post-card:hover .post-arrow {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
        transform: rotate(-45deg);
    }

    /* Empty state */
    .blog-empty {
        text-align: center;
        padding: 5rem 2rem;
    }

    @media (max-width: 767px) {
        .featured-post { grid-template-columns: 1fr; }
        .featured-post-visual { min-height: 160px; }
        .featured-post-body { padding: 1.5rem; }
    }
</style>

<div class="container-fluid px-lg-5 py-2">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="blog-hero reveal">
        <div class="eyebrow mb-3" style="width:fit-content;">
            <i class="bi bi-newspaper"></i> Blog
        </div>
        <h1 class="blog-hero-title">
            Ideas, insights,<br><span class="accent">and deep dives.</span>
        </h1>
        <p style="color:var(--muted); font-size:1rem; max-width:440px; line-height:1.7; margin:0;">
            Technical articles, tutorials, and developer perspectives — published regularly.
        </p>
    </div>

    @if($posts->count() > 0)

        @php $featured = $posts->first(); $rest = $posts->skip(1); @endphp

        {{-- ── Featured post ────────────────────────────── --}}
        <div class="mb-5 reveal">
            <div class="section-divider mb-3">
                <p class="section-divider-label">Latest</p>
            </div>

            <a href="{{ route('blog.show', $featured->slug) }}" class="featured-post d-block">
                <div class="featured-post-visual">
                    <span class="featured-post-visual-icon">✍️</span>
                </div>
                <div class="featured-post-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="post-cat-badge">{{ $featured->category->title }}</span>
                        <span class="post-date">{{ $featured->created_at->format('M d, Y') }}</span>
                    </div>
                    <h2 class="featured-post-title">{{ $featured->title }}</h2>
                    <p class="featured-post-excerpt">
                        {!! Str::limit(strip_tags(htmlspecialchars_decode($featured->html_content)), 180) !!}
                    </p>
                    <span class="read-more-link">
                        Read article <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>

        {{-- ── Grid ──────────────────────────────────────── --}}
        @if($rest->count() > 0)
            <div class="reveal">
                <div class="section-divider mb-4">
                    <p class="section-divider-label">All Articles</p>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 pb-5">
                    @foreach($rest as $post)
                        <div class="col reveal stagger-{{ ($loop->index % 3) + 1 }}">
                            <a href="{{ route('blog.show', $post->slug) }}" class="post-card">
                                <div class="post-card-body">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="post-cat-badge">{{ $post->category->title }}</span>
                                        <span class="post-date">{{ $post->created_at->format('M d') }}</span>
                                    </div>
                                    <h2 class="post-card-title">{{ $post->title }}</h2>
                                    <p class="post-card-excerpt">
                                        {!! Str::limit(strip_tags(htmlspecialchars_decode($post->html_content)), 130) !!}
                                    </p>
                                </div>
                                <div class="post-card-footer">
                                    <span class="read-more-link" style="font-size:.75rem;">Read</span>
                                    <div class="post-arrow"><i class="bi bi-arrow-right"></i></div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        <div class="blog-empty reveal">
            <span style="font-size:3rem; display:block; margin-bottom:1rem;">✍️</span>
            <h3 style="font-family:var(--font-display); font-weight:700; color:var(--ink); margin-bottom:.5rem;">No articles yet</h3>
            <p style="color:var(--muted); max-width:300px; margin:auto; font-size:.875rem;">
                Articles are on their way. Check back soon for new content.
            </p>
        </div>
    @endif

</div>

@endsection