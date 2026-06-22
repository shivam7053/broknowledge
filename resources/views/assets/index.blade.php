{{-- assets/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Free SVG Icons, Emojis & Design Assets Library')
@section('meta_description', 'Thousands of free SVG icons and emojis for developers. Filter by category, search by keyword, and export to high-res PNG instantly — no attribution required.')

@section('head')
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Free SVG Icons, Emojis & Assets Library — BroKnowledge">
    <meta property="og:description" content="Browse and download thousands of free SVG design assets. High-quality icons and emojis for your projects.">
    <meta property="og:url" content="{{ route('assets.index') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Free Icons, Emojis & SVG Assets Library">
    <meta name="twitter:description" content="Thousands of free SVG icons and emojis. Search, filter, and download instantly.">

    {{-- If a search query is present, tell crawlers not to index the filtered/paginated view --}}
    @if(request('search') || request()->query('page', 1) > 1)
        <meta name="robots" content="noindex, follow">
    @endif

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Assets Library", "item": "{{ route('assets.index') }}" }
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "Are these assets free for commercial use?",
                "acceptedAnswer": { "@@type": "Answer", "text": "Yes. All icons and emojis in the BroKnowledge library are available for personal and commercial use without attribution." }
            }
        ]
    }
    </script>
@endsection

@section('content')

<style>
    /* ══════════════════════════════════════════════
       ASSETS LIBRARY PAGE
    ══════════════════════════════════════════════ */

    .assets-hero {
        padding: 3.5rem 0 2rem;
        text-align: center;
    }

    .assets-title {
        font-family: var(--font-display);
        font-size: clamp(2.2rem, 5vw, 3.4rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.06;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .assets-title .accent { color: #ec4899; }

    .assets-subtitle {
        font-size: .95rem;
        color: var(--muted);
        max-width: 460px;
        margin: 0 auto;
        line-height: 1.65;
    }

    /* ── Category pill nav ─────────────────────── */
    .category-pill-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: .5rem;
        margin: 2rem 0;
    }

    .category-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .8rem;
        font-weight: 600;
        padding: .5rem 1.1rem;
        border-radius: 100px;
        background: var(--card-bg);
        border: 1.5px solid var(--card-border);
        color: var(--muted);
        text-decoration: none;
        transition: border-color var(--transition-fast), color var(--transition-fast), background var(--transition-fast), transform var(--transition-spring);
        box-shadow: var(--card-shadow);
    }

    .category-pill:hover {
        border-color: rgba(236,72,153,.3);
        color: #ec4899;
        background: rgba(236,72,153,.06);
        transform: translateY(-1px);
    }

    .category-pill.active {
        background: #ec4899;
        border-color: #ec4899;
        color: #fff;
        box-shadow: 0 3px 10px rgba(236,72,153,.3);
    }

    /* ── Search bar ─────────────────────────────── */
    .assets-search-form {
        max-width: 540px;
        margin: 0 auto 3rem;
    }

    .assets-search-wrap {
        background: var(--card-bg);
        border: 1.5px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: 100px;
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .35rem .35rem .35rem 1.25rem;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    }

    .assets-search-wrap:focus-within {
        border-color: rgba(236,72,153,.4);
        box-shadow: 0 0 0 4px rgba(236,72,153,.08);
    }

    .assets-search-input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: .9rem;
        color: var(--ink);
        padding: .5rem 0;
    }

    .assets-search-input::placeholder { color: var(--muted-light); }

    .assets-search-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #ec4899;
        color: #fff;
        border: none;
        cursor: pointer;
        flex-shrink: 0;
        transition: background var(--transition-fast), transform var(--transition-spring);
        font-size: 1rem;
    }

    .assets-search-btn:hover { background: #db2777; transform: scale(1.05); }

    /* Active search chip */
    .active-search-chip {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-size: .8rem;
        color: var(--muted);
        margin-top: .85rem;
        justify-content: center;
        width: 100%;
    }

    .active-search-chip .chip-value {
        font-weight: 700;
        color: var(--ink);
        background: var(--surface-2);
        border: 1px solid var(--card-border);
        border-radius: 100px;
        padding: .2rem .7rem;
    }

    .active-search-chip a {
        color: var(--muted-light);
        text-decoration: none;
        font-size: .78rem;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
    }
    .active-search-chip a:hover { color: #ef4444; }

    /* ── Asset card ─────────────────────────────── */
    .asset-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius);
        padding: 1.25rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        position: relative;
        transition: box-shadow var(--transition), transform var(--transition-spring), border-color var(--transition-fast);
    }

    .asset-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        border-color: rgba(236,72,153,.2);
    }

    .asset-visual {
        width: 64px;
        height: 64px;
        margin-bottom: .85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ink-2);
        transition: transform var(--transition-spring);
    }

    .asset-card:hover .asset-visual { transform: scale(1.1) rotate(-3deg); }

    .asset-visual svg { width: 100%; height: 100%; fill: currentColor; }
    [data-bs-theme="dark"] .asset-visual svg { filter: brightness(1.2); }

    .asset-title {
        font-size: .78rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: .85rem;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Download dropdown */
    .asset-dl-wrap { width: 100%; position: relative; }

    .asset-dl-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        font-size: .75rem;
        font-weight: 700;
        padding: .5rem .75rem;
        border-radius: 100px;
        border: 1.5px solid rgba(236,72,153,.3);
        background: transparent;
        color: #ec4899;
        cursor: pointer;
        transition: background var(--transition-fast), color var(--transition-fast);
    }

    .asset-dl-btn:hover, .asset-dl-btn.open {
        background: #ec4899;
        color: #fff;
        border-color: #ec4899;
    }

    .asset-dl-menu {
        position: absolute;
        top: calc(100% + .4rem);
        left: 50%;
        transform: translateX(-50%);
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: 0 12px 28px rgba(0,0,0,.15);
        border-radius: var(--radius-sm);
        min-width: 140px;
        z-index: 30;
        overflow: hidden;
        padding: .35rem;
    }

    .asset-dl-option {
        display: flex;
        align-items: center;
        gap: .5rem;
        width: 100%;
        padding: .5rem .7rem;
        font-size: .8rem;
        font-weight: 600;
        color: var(--ink-2);
        background: none;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        text-align: left;
        text-decoration: none;
        transition: background var(--transition-fast), color var(--transition-fast);
    }

    .asset-dl-option:hover { background: var(--brand-pale); color: var(--brand); }

    /* When a dropdown is open, keep its card above neighboring rows */
    .asset-card-wrap.dropdown-open { z-index: 50; position: relative; }

    /* ── Empty state ────────────────────────────── */
    .assets-empty {
        text-align: center;
        padding: 4rem 2rem;
    }

    /* ── Pagination ─────────────────────────────── */
    .assets-pagination {
        margin-top: 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .75rem;
    }

    /* Override Bootstrap pagination to match brand */
    .assets-pagination nav { width: 100%; }

    .assets-pagination .pagination {
        justify-content: center;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 0;
    }

    .assets-pagination .page-item .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-width: 40px;
        height: 40px;
        padding: 0 14px;
        border-radius: 100px !important;
        border: 1.5px solid var(--card-border);
        background: var(--card-bg);
        color: var(--ink-2);
        font-size: .8rem;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        transition: background var(--transition-fast), color var(--transition-fast), border-color var(--transition-fast), box-shadow var(--transition-fast);
        box-shadow: none;
    }

    .assets-pagination .page-item .page-link:hover {
        background: rgba(236,72,153,.08);
        color: #ec4899;
        border-color: rgba(236,72,153,.35);
    }

    .assets-pagination .page-item.active .page-link {
        background: #ec4899;
        border-color: #ec4899;
        color: #fff;
        box-shadow: 0 3px 10px rgba(236,72,153,.35);
    }

    .assets-pagination .page-item.active .page-link:hover {
        background: #db2777;
        border-color: #db2777;
    }

    .assets-pagination .page-item.disabled .page-link {
        opacity: .38;
        pointer-events: none;
        cursor: default;
        background: var(--card-bg);
        color: var(--muted);
        border-color: var(--card-border);
    }

    /* Page count helper text */
    .pagination-meta {
        font-size: .78rem;
        color: var(--muted);
    }

    .pagination-meta strong {
        color: var(--ink);
        font-weight: 700;
    }

    /* ── SEO copy ───────────────────────────────── */
    .assets-seo-copy {
        max-width: 760px;
        margin: 4rem auto 1rem;
        padding-top: 2.5rem;
        border-top: 1px solid var(--card-border);
    }

    .assets-seo-copy h2 {
        font-family: var(--font-display);
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -.02em;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .assets-seo-copy p {
        font-size: .88rem;
        color: var(--muted);
        line-height: 1.75;
        margin-bottom: 1rem;
    }
</style>

<div class="container-fluid px-lg-5 py-2" x-data="{ openDropdown: null }" @click.away="openDropdown = null">

    {{-- ── Hero (H1 for SEO) ────────────────────────────── --}}
    <header class="assets-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width:fit-content; background:rgba(236,72,153,.1); color:#ec4899; border-color:rgba(236,72,153,.2);">
            <i class="bi bi-stars"></i> Assets Library
        </div>
        <h1 class="assets-title">Free icons, <span class="accent">emojis & SVGs.</span></h1>
        <p class="assets-subtitle">
            Search and filter thousands of free design assets, then export them as SVG or high-resolution PNG — instantly, no attribution required.
        </p>
    </header>

    {{-- ── Category nav ─────────────────────────────────── --}}
    <nav class="category-pill-row reveal" aria-label="Asset categories">
        <a href="{{ route('assets.index') }}"
           class="category-pill {{ !request('category') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> All
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('assets.index', array_filter(['category' => $cat->slug, 'search' => request('search')])) }}"
               class="category-pill {{ request('category') == $cat->slug ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </nav>

    {{-- ── Search bar ───────────────────────────────────── --}}
    <form action="{{ route('assets.index') }}" method="GET" class="assets-search-form reveal" role="search">
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <div class="assets-search-wrap">
            <i class="bi bi-search" style="color:var(--muted-light);"></i>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="assets-search-input"
                   placeholder="Search emojis, icons, or keywords…"
                   aria-label="Search assets">
            <button type="submit" class="assets-search-btn" aria-label="Search">
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>

        @if(request('search'))
            <div class="active-search-chip">
                Showing results for <span class="chip-value">"{{ request('search') }}"</span>
                <a href="{{ route('assets.index', array_filter(['category' => request('category')])) }}">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            </div>
        @endif
    </form>

    {{-- ── Assets Grid ──────────────────────────────────── --}}
    <div class="row g-4">
        @forelse($assets as $asset)
            <div class="col-6 col-md-4 col-lg-2 reveal asset-card-wrap"
                 :class="openDropdown === {{ $asset->id }} ? 'dropdown-open' : ''">
                <div class="asset-card">
                    <div id="asset-svg-{{ $asset->id }}" class="asset-visual" aria-hidden="true">
                        {!! $asset->svg_content !!}
                    </div>
                    <h2 class="asset-title" style="font-size:.78rem;">{{ $asset->title }}</h2>

                    <div class="asset-dl-wrap">
                        <button type="button"
                                class="asset-dl-btn"
                                :class="openDropdown === {{ $asset->id }} ? 'open' : ''"
                                @click="openDropdown = (openDropdown === {{ $asset->id }} ? null : {{ $asset->id }})">
                            <i class="bi bi-download"></i> Download
                        </button>

                        <div class="asset-dl-menu" x-show="openDropdown === {{ $asset->id }}" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <a class="asset-dl-option" href="{{ route('assets.download', [$asset, 'svg']) }}">
                                <i class="bi bi-filetype-svg"></i> SVG
                            </a>
                            <button type="button" class="asset-dl-option"
                                    onclick="downloadAsPng('asset-svg-{{ $asset->id }}', '{{ $asset->title }}')">
                                <i class="bi bi-filetype-png"></i> PNG
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="assets-empty reveal">
                    <span style="font-size:3rem; display:block; margin-bottom:1rem;">🔍</span>
                    <h3 style="font-family:var(--font-display); font-weight:700; color:var(--ink); margin-bottom:.5rem;">No assets found</h3>
                    <p style="color:var(--muted); font-size:.875rem; max-width:300px; margin:auto;">
                        Try a different search term or browse all categories instead.
                    </p>
                    <a href="{{ route('assets.index') }}"
                       style="margin-top:1.25rem; display:inline-flex; align-items:center; gap:.4rem; background:#ec4899; color:#fff; font-size:.8rem; font-weight:700; padding:.6rem 1.3rem; border-radius:100px; text-decoration:none;">
                        <i class="bi bi-arrow-counterclockwise"></i> View all assets
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- ── Pagination ───────────────────────────────────── --}}
    @if($assets->hasPages())
        <div class="assets-pagination">
            {{-- Bootstrap-compatible pagination links --}}
            {{ $assets->links('pagination::bootstrap-5') }}

            {{-- Result count meta --}}
            <p class="pagination-meta">
                Showing
                <strong>{{ $assets->firstItem() }}–{{ $assets->lastItem() }}</strong>
                of
                <strong>{{ $assets->total() }}</strong>
                assets
            </p>
        </div>
    @endif

    {{-- ── SEO copy block ───────────────────────────────── --}}
    <section class="assets-seo-copy reveal" aria-labelledby="assets-about-heading">
        <h2 id="assets-about-heading">A Growing Library of Free Icons and Emojis</h2>
        <p>
            Every asset in this library is available as a scalable SVG or a crisp, high-resolution PNG export —
            generated client-side at the moment you download, so files are always sharp regardless of size.
            Browse by category or search by keyword to quickly find the icon or emoji you need for your next
            project, presentation, or design mockup.
        </p>
        <p>
            New assets are added regularly. If you can't find what you're looking for, try a broader search
            term or check back soon as the collection continues to grow.
        </p>
    </section>

</div>

<script>
function downloadAsPng(containerId, fileName) {
    const container = document.getElementById(containerId);
    const svg = container.querySelector('svg');

    const serializer = new XMLSerializer();
    let source = serializer.serializeToString(svg);

    if (!source.match(/^<svg[^>]+xmlns="http:\/\/www\.w3\.org\/2000\/svg"/)) {
        source = source.replace(/^<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
    }
    if (!source.match(/^<svg[^>]+xmlns:xlink="http:\/\/www\.w3\.org\/1999\/xlink"/)) {
        source = source.replace(/^<svg/, '<svg xmlns:xlink="http://www.w3.org/1999/xlink"');
    }

    const svgBlob = new Blob([source], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(svgBlob);

    const canvas = document.createElement('canvas');
    const img = new Image();
    const targetSize = 1024;
    canvas.width = targetSize;
    canvas.height = targetSize;
    const ctx = canvas.getContext('2d');

    img.onload = function () {
        ctx.drawImage(img, 0, 0, targetSize, targetSize);
        const pngUrl = canvas.toDataURL('image/png');
        const downloadLink = document.createElement('a');
        downloadLink.href = pngUrl;
        downloadLink.download = `${fileName}.png`;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        URL.revokeObjectURL(url);
    };

    img.onerror = function () {
        alert('Failed to process the image for PNG download.');
    };

    img.src = url;
}
</script>

@endsection