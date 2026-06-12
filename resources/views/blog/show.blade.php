{{-- blog/show.blade.php --}}
@extends('layouts.app')

@section('title', $post->title)

@section('content')

<style>
    /* ══════════════════════════════════════════════
       BLOG ARTICLE READER
    ══════════════════════════════════════════════ */

    .article-layout {
        display: grid;
        grid-template-columns: 1fr 240px;
        gap: 3rem;
        max-width: 1100px;
        margin: 0 auto;
        padding: 3rem 1.5rem 6rem;
    }

    /* ── Article header ────────────────────────── */
    .article-back {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .78rem;
        font-weight: 600;
        color: var(--muted);
        text-decoration: none;
        margin-bottom: 2rem;
        transition: color var(--transition-fast);
    }
    .article-back:hover { color: var(--brand); }

    .article-cat {
        display: inline-flex;
        align-items: center;
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--brand);
        background: var(--brand-pale);
        border: 1px solid rgba(22,163,74,.18);
        border-radius: 100px;
        padding: .25rem .75rem;
        margin-bottom: 1rem;
    }

    .article-title {
        font-family: var(--font-display);
        font-size: clamp(1.9rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.1;
        color: var(--ink);
        margin-bottom: 1.25rem;
    }

    .article-meta-row {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--card-border);
        margin-bottom: 2.5rem;
    }

    .article-meta-item {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .75rem;
        color: var(--muted);
    }

    .article-meta-item i { font-size: .7rem; opacity: .7; }

    /* ── Rich content ──────────────────────────── */
    .article-content {
        font-size: .975rem;
        line-height: 1.85;
        color: var(--ink-2);
    }

    .article-content h1, .article-content h2,
    .article-content h3, .article-content h4 {
        font-family: var(--font-display);
        font-weight: 700;
        letter-spacing: -.025em;
        color: var(--ink);
        margin-top: 2.5rem;
        margin-bottom: .8rem;
        line-height: 1.2;
    }

    .article-content h2 {
        font-size: 1.55rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid var(--card-border);
    }

    .article-content h3 { font-size: 1.2rem; }
    .article-content h4 { font-size: 1rem; color: var(--ink-2); }

    .article-content p { margin-bottom: 1.35rem; }

    .article-content a {
        color: var(--brand);
        text-decoration: underline;
        text-decoration-thickness: 1.5px;
        text-underline-offset: 3px;
    }
    .article-content a:hover { color: var(--brand-dark); }

    .article-content strong { font-weight: 700; color: var(--ink); }
    .article-content em { font-style: italic; }

    .article-content pre {
        background: #0d1117;
        color: #e6edf3;
        border-radius: var(--radius-sm);
        padding: 1.4rem 1.5rem;
        overflow-x: auto;
        font-family: 'Fira Code', 'Cascadia Code', monospace;
        font-size: .82rem;
        line-height: 1.7;
        border: 1px solid rgba(255,255,255,.07);
        margin: 1.75rem 0;
        position: relative;
    }

    .article-content code:not(pre code) {
        font-family: 'Fira Code', monospace;
        font-size: .8em;
        background: var(--brand-pale);
        color: var(--brand-dark);
        padding: .15em .45em;
        border-radius: 4px;
        border: 1px solid rgba(22,163,74,.15);
    }

    .article-content blockquote {
        border-left: 3px solid var(--brand);
        padding: 1rem 1.5rem;
        margin: 2rem 0;
        background: var(--brand-pale);
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        color: var(--ink-2);
        font-style: italic;
        position: relative;
    }

    .article-content blockquote::before {
        content: '"';
        font-family: Georgia, serif;
        font-size: 3rem;
        color: var(--brand);
        opacity: .2;
        position: absolute;
        top: -.5rem;
        left: .75rem;
        line-height: 1;
    }

    .article-content ul, .article-content ol {
        padding-left: 1.6rem;
        margin-bottom: 1.35rem;
    }

    .article-content li { margin-bottom: .5rem; }

    .article-content ul li::marker { color: var(--brand); }

    .article-content img {
        max-width: 100%;
        border-radius: var(--radius-sm);
        border: 1px solid var(--card-border);
        margin: 1.5rem 0;
        display: block;
    }

    .article-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.75rem 0;
        font-size: .875rem;
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 1px solid var(--card-border);
    }

    .article-content th {
        background: var(--surface-2);
        font-family: var(--font-display);
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--muted);
        padding: .7rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--card-border);
    }

    .article-content td {
        padding: .65rem 1rem;
        color: var(--ink-2);
        border-bottom: 1px solid var(--card-border);
        font-size: .875rem;
    }

    .article-content tr:last-child td { border-bottom: none; }
    .article-content tr:nth-child(even) td { background: var(--surface-2); }

    .article-content hr {
        border: none;
        border-top: 1px solid var(--card-border);
        margin: 2.5rem 0;
    }

    /* ── Article footer ────────────────────────── */
    .article-footer {
        margin-top: 3.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .article-back-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .82rem;
        font-weight: 700;
        color: var(--brand);
        text-decoration: none;
        padding: .6rem 1.1rem;
        border-radius: var(--radius-sm);
        border: 1.5px solid rgba(22,163,74,.3);
        background: var(--brand-pale);
        transition: border-color var(--transition-fast), transform var(--transition-spring);
    }
    .article-back-btn:hover { border-color: var(--brand); color: var(--brand); transform: translateX(-2px); }

    /* Share bar */
    .share-row {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .share-label {
        font-size: .72rem;
        font-weight: 700;
        color: var(--muted-light);
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .share-btn {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        text-decoration: none;
        font-size: .85rem;
        transition: border-color var(--transition-fast), color var(--transition-fast), background var(--transition-fast);
        cursor: pointer;
        background: var(--card-bg);
    }

    .share-btn:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-pale); }

    /* ── Sidebar (TOC + related) ───────────────── */
    .article-sidebar {
        position: sticky;
        top: 85px;
        height: fit-content;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .sidebar-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .sidebar-card-header {
        padding: .8rem 1rem;
        border-bottom: 1px solid var(--card-border);
        background: var(--surface-2);
        font-family: var(--font-display);
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted-light);
    }

    .sidebar-card-body { padding: .85rem; }

    /* Article info card */
    .info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .5rem 0;
        border-bottom: 1px solid var(--card-border);
        font-size: .78rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row-label { color: var(--muted); }
    .info-row-value { font-weight: 600; color: var(--ink); }

    /* Reading progress — signature element for blog show */
    .reading-progress-wrap {
        position: fixed;
        top: 65px;
        left: 0;
        right: 0;
        height: 3px;
        background: transparent;
        z-index: 1025;
    }

    .reading-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--brand), var(--brand-mid));
        transition: width .1s linear;
        width: 0%;
    }

    @media (max-width: 991px) {
        .article-layout { grid-template-columns: 1fr; gap: 2rem; }
        .article-sidebar { position: static; }
    }

    @media (max-width: 767px) {
        .article-layout { padding: 1.5rem 1rem 4rem; }
        .article-title { font-size: 1.75rem; }
    }
</style>

{{-- Reading progress bar (signature element) --}}
<div class="reading-progress-wrap">
    <div class="reading-progress-bar" id="readingProgress"></div>
</div>

<div class="container-fluid px-lg-5">
    <div class="article-layout">

        {{-- ── Article ──────────────────────────────────── --}}
        <article>
            <a href="{{ route('blog.index') }}" class="article-back">
                <i class="bi bi-arrow-left"></i> All articles
            </a>

            <span class="article-cat">{{ $post->category->title }}</span>

            <h1 class="article-title">{{ $post->title }}</h1>

            <div class="article-meta-row">
                <span class="article-meta-item">
                    <i class="bi bi-calendar3"></i>
                    {{ $post->created_at->format('F j, Y') }}
                </span>
                <span class="article-meta-item">
                    <i class="bi bi-clock"></i>
                    {{ max(1, (int) ceil(str_word_count(strip_tags($post->html_content)) / 200)) }} min read
                </span>
                <span class="article-meta-item">
                    <i class="bi bi-folder2"></i>
                    {{ $post->category->title }}
                </span>
            </div>

            <div class="article-content">
                {!! htmlspecialchars_decode($post->html_content) !!}
            </div>

            <footer class="article-footer">
                <a href="{{ route('blog.index') }}" class="article-back-btn">
                    <i class="bi bi-arrow-left"></i> Back to Blog
                </a>

                <div class="share-row">
                    <span class="share-label">Share</span>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}"
                       target="_blank" class="share-btn" title="Share on X">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                       target="_blank" class="share-btn" title="Share on LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <button class="share-btn" title="Copy link"
                            onclick="navigator.clipboard.writeText(window.location.href).then(()=>this.innerHTML='<i class=\'bi bi-check2\'></i>')">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                </div>
            </footer>
        </article>

        {{-- ── Sidebar ──────────────────────────────────── --}}
        <aside class="article-sidebar">

            {{-- Article info --}}
            <div class="sidebar-card">
                <div class="sidebar-card-header">About this article</div>
                <div class="sidebar-card-body">
                    <div class="info-row">
                        <span class="info-row-label">Category</span>
                        <span class="info-row-value">{{ $post->category->title }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Published</span>
                        <span class="info-row-value">{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Read time</span>
                        <span class="info-row-value">{{ max(1, (int) ceil(str_word_count(strip_tags($post->html_content)) / 200)) }} min</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Words</span>
                        <span class="info-row-value">{{ number_format(str_word_count(strip_tags($post->html_content))) }}</span>
                    </div>
                </div>
            </div>

            {{-- Reading progress card --}}
            <div class="sidebar-card" x-data="{ progress: 0 }"
                 @scroll.window="
                     const el = document.documentElement;
                     const scrolled = el.scrollTop;
                     const total = el.scrollHeight - el.clientHeight;
                     progress = total > 0 ? Math.round((scrolled / total) * 100) : 0;
                 ">
                <div class="sidebar-card-header">Reading progress</div>
                <div class="sidebar-card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:.72rem; color:var(--muted);">Progress</span>
                        <span style="font-family:var(--font-display); font-weight:800; font-size:.9rem; color:var(--brand);"
                              x-text="progress + '%'"></span>
                    </div>
                    <div style="height:6px; background:var(--surface-2); border-radius:100px; overflow:hidden;">
                        <div style="height:100%; background:linear-gradient(90deg, var(--brand), var(--brand-mid)); border-radius:100px; transition: width .1s linear;"
                             :style="'width:' + progress + '%'"></div>
                    </div>
                </div>
            </div>

            {{-- Back CTA --}}
            <div class="sidebar-card">
                <div class="sidebar-card-body" style="padding:1rem;">
                    <p style="font-size:.78rem; color:var(--muted); margin-bottom:.85rem; line-height:1.55;">
                        Enjoyed this article? Browse more from our blog.
                    </p>
                    <a href="{{ route('blog.index') }}"
                       style="display:flex; align-items:center; gap:.4rem; background:var(--brand); color:#fff; font-size:.78rem; font-weight:700; padding:.6rem 1rem; border-radius:var(--radius-sm); text-decoration:none; justify-content:center; box-shadow:0 3px 10px rgba(22,163,74,.3); transition:background var(--transition-fast);">
                        <i class="bi bi-arrow-left"></i> All Articles
                    </a>
                </div>
            </div>

        </aside>

    </div>
</div>

{{-- Reading progress bar script --}}
<script>
    const bar = document.getElementById('readingProgress');
    window.addEventListener('scroll', () => {
        const el = document.documentElement;
        const pct = el.scrollHeight - el.clientHeight > 0
            ? (el.scrollTop / (el.scrollHeight - el.clientHeight)) * 100
            : 0;
        bar.style.width = pct + '%';
    }, { passive: true });
</script>

@endsection