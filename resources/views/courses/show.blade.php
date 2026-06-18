{{-- courses/show.blade.php --}}
@extends('layouts.app')

@section('title', ($currentTopic ? $currentTopic->title . ' — ' : '') . $course->title)
@section('meta_description', $currentTopic ? 'Learn ' . $course->title . ': ' . $currentTopic->title . '. Master developer concepts with our free interactive lessons.' : 'Master ' . $course->title . ' with our structured developer courses.')

@section('head')
@if($currentTopic)
<link rel="canonical" href="{{ route('courses.show', ['course' => $course->slug, 'topic' => $currentTopic->slug]) }}">
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $currentTopic->title }} — {{ $course->title }}">
<meta property="og:description" content="Read our lesson on {{ $currentTopic->title }} and advance your skills in {{ $course->title }}.">
@else
<link rel="canonical" href="{{ route('courses.show', $course->slug) }}">
<meta property="og:type" content="website">
@endif

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Courses", "item": "{{ route('courses.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "{{ $course->title }}", "item": "{{ route('courses.show', $course->slug) }}" }
        @if($currentTopic)
        ,{ "@@type": "ListItem", "position": 4, "name": "{{ $currentTopic->title }}", "item": "{{ route('courses.show', ['course' => $course->slug, 'topic' => $currentTopic->slug]) }}" }
        @endif
    ]
}
</script>

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Course",
    "name": "{{ $course->title }}",
    "description": "Master {{ $course->title }} through structured, developer-focused lessons and topics.",
    "publisher": {
        "@@type": "Organization",
        "name": "BroKnowledge"
    }
}
</script>
@endsection

@section('content')

<style>
    /* ══════════════════════════════════════════════
       COURSE READER PAGE
    ══════════════════════════════════════════════ */

    .reader-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        min-height: calc(100vh - 65px);
        border-top: 1px solid var(--card-border);
    }

    /* ── Sidebar ───────────────────────────────── */
    .reader-sidebar {
        background: var(--card-bg);
        border-right: 1px solid var(--card-border);
        position: sticky;
        top: 65px;
        height: calc(100vh - 65px);
        overflow-y: auto;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        gap: .25rem;
        scrollbar-width: thin;
        scrollbar-color: rgba(0,0,0,.1) transparent;
    }

    .reader-sidebar::-webkit-scrollbar { width: 4px; }
    .reader-sidebar::-webkit-scrollbar-thumb { background: rgba(0,0,0,.1); border-radius: 4px; }
    [data-bs-theme="dark"] .reader-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); }

    .sidebar-course-name {
        font-family: var(--font-display);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted-light);
        padding: 0 .5rem;
        margin-bottom: .75rem;
    }

    .topic-link {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .58rem .75rem;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-size: .82rem;
        font-weight: 500;
        color: var(--muted);
        transition: background var(--transition-fast), color var(--transition-fast);
        border: 1px solid transparent;
        line-height: 1.4;
    }

    .topic-link:hover {
        background: var(--brand-pale);
        color: var(--brand);
    }

    .topic-link.active {
        background: var(--brand-pale);
        color: var(--brand);
        font-weight: 700;
        border-color: rgba(22,163,74,.2);
    }

    .topic-num {
        font-size: .65rem;
        font-weight: 700;
        color: var(--muted-light);
        min-width: 18px;
        font-family: var(--font-display);
        flex-shrink: 0;
    }

    .topic-link.active .topic-num { color: var(--brand); }

    /* ── Reader area ───────────────────────────── */
    .reader-main {
        background: var(--surface);
        overflow-y: auto;
    }

    .reader-content-wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: 3.5rem 2rem 6rem;
    }

    /* Breadcrumb */
    .reader-breadcrumb {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .72rem;
        color: var(--muted-light);
        margin-bottom: 2rem;
    }

    .reader-breadcrumb a {
        color: var(--muted-light);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    .reader-breadcrumb a:hover { color: var(--brand); }
    .reader-breadcrumb .sep { opacity: .4; }

    /* Topic header */
    .topic-header {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--card-border);
    }

    .topic-title {
        font-family: var(--font-display);
        font-size: clamp(1.65rem, 3.5vw, 2.4rem);
        font-weight: 800;
        letter-spacing: -.035em;
        line-height: 1.12;
        color: var(--ink);
        margin-bottom: .5rem;
    }

    .topic-meta {
        font-size: .78rem;
        color: var(--muted-light);
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    /* Rich content styling */
    .course-content {
        font-size: .95rem;
        line-height: 1.8;
        color: var(--ink-2);
    }

    .course-content h1, .course-content h2, .course-content h3,
    .course-content h4, .course-content h5 {
        font-family: var(--font-display);
        font-weight: 700;
        letter-spacing: -.02em;
        color: var(--ink);
        margin-top: 2.25rem;
        margin-bottom: .75rem;
        line-height: 1.2;
    }

    .course-content h2 { font-size: 1.45rem; }
    .course-content h3 { font-size: 1.2rem; }

    .course-content p { margin-bottom: 1.2rem; }

    .course-content a { color: var(--brand); text-decoration: underline; text-decoration-thickness: 1.5px; text-underline-offset: 2px; }
    .course-content a:hover { color: var(--brand-dark); }

    .course-content pre {
        background: #0d1117;
        color: #e6edf3;
        border-radius: var(--radius-sm);
        padding: 1.25rem 1.5rem;
        overflow-x: auto;
        font-family: 'Fira Code', monospace;
        font-size: .82rem;
        line-height: 1.7;
        border: 1px solid rgba(255,255,255,.06);
        margin: 1.5rem 0;
    }

    .course-content code:not(pre code) {
        font-family: 'Fira Code', monospace;
        font-size: .82em;
        background: var(--brand-pale);
        color: var(--brand-dark);
        padding: .15em .45em;
        border-radius: 4px;
        border: 1px solid rgba(22,163,74,.15);
    }

    .course-content blockquote {
        border-left: 3px solid var(--brand);
        padding: .75rem 1.25rem;
        margin: 1.5rem 0;
        background: var(--brand-pale);
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        color: var(--ink-2);
        font-style: italic;
    }

    .course-content ul, .course-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.2rem;
    }

    .course-content li { margin-bottom: .4rem; }

    .course-content img {
        max-width: 100%;
        border-radius: var(--radius-sm);
        border: 1px solid var(--card-border);
        margin: 1rem 0;
    }

    .course-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: .875rem;
    }

    .course-content th {
        background: var(--surface-2);
        font-weight: 700;
        text-align: left;
        padding: .6rem .9rem;
        border-bottom: 2px solid var(--card-border);
        font-family: var(--font-display);
        font-size: .75rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--muted);
    }

    .course-content td {
        padding: .6rem .9rem;
        border-bottom: 1px solid var(--card-border);
        color: var(--ink-2);
    }

    /* Nav footer */
    .topic-nav-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid var(--card-border);
    }

    .topic-nav-btn {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 1.25rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        text-decoration: none;
        color: var(--muted);
        font-size: .8rem;
        font-weight: 600;
        transition: border-color var(--transition-fast), color var(--transition-fast), background var(--transition-fast), transform var(--transition-spring);
        max-width: 48%;
        box-shadow: var(--card-shadow);
    }

    .topic-nav-btn:hover {
        border-color: rgba(22,163,74,.3);
        color: var(--brand);
        background: var(--brand-pale);
        transform: translateY(-2px);
    }

    .topic-nav-label {
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted-light);
        display: block;
    }

    .topic-nav-title {
        font-family: var(--font-display);
        font-size: .85rem;
        font-weight: 700;
        color: var(--ink);
        display: block;
        margin-top: .1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Welcome state */
    .reader-welcome {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: 60vh;
        padding: 3rem;
    }

    .welcome-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        display: block;
    }

    .welcome-title {
        font-family: var(--font-display);
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -.025em;
        margin-bottom: .6rem;
    }

    /* Mobile sidebar toggle */
    .mobile-sidebar-toggle {
        display: none;
        align-items: center;
        gap: .5rem;
        padding: .65rem 1rem;
        background: var(--card-bg);
        border-bottom: 1px solid var(--card-border);
        font-size: .82rem;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        border-top: none;
        border-left: none;
        border-right: none;
        width: 100%;
        text-align: left;
    }

    @media (max-width: 767px) {
        .reader-layout {
            grid-template-columns: 1fr;
        }
        .reader-sidebar {
            position: fixed;
            top: 65px;
            left: -280px;
            width: 260px;
            z-index: 1020;
            transition: left var(--transition);
            box-shadow: 4px 0 20px rgba(0,0,0,.15);
        }
        .reader-sidebar.open { left: 0; }
        .reader-main { grid-column: 1; }
        .mobile-sidebar-toggle { display: flex; }
        .reader-content-wrap { padding: 1.5rem 1rem 4rem; }
    }
</style>

<div class="reader-layout" x-data="{ sidebarOpen: false }">

    {{-- Mobile toggle --}}
    <button class="mobile-sidebar-toggle" @click="sidebarOpen = !sidebarOpen">
        <i class="bi" :class="sidebarOpen ? 'bi-x-lg' : 'bi-list'"></i>
        <span x-text="sidebarOpen ? 'Close topics' : 'Browse topics'"></span>
        <span class="ms-auto" style="font-size:.68rem; color:var(--muted-light);">{{ $course->title }}</span>
    </button>

    {{-- ── Sidebar ──────────────────────────────────────── --}}
    <aside class="reader-sidebar" :class="sidebarOpen ? 'open' : ''" @click.outside="sidebarOpen = false">
        <div class="sidebar-course-name">{{ $course->title }}</div>

        @foreach($topics as $topic)
            <a href="{{ route('courses.show', ['course' => $course->slug, 'topic' => $topic->slug]) }}"
               class="topic-link {{ $currentTopic && $currentTopic->id === $topic->id ? 'active' : '' }}">
                <span class="topic-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <span>{{ $topic->title }}</span>
            </a>
        @endforeach
    </aside>

    {{-- ── Reader ───────────────────────────────────────── --}}
    <main class="reader-main">
        @if($currentTopic)

            {{-- Compute prev/next --}}
            @php
                $topicList  = $topics->values();
                $currentKey = $topicList->search(fn($t) => $t->id === $currentTopic->id);
                $prevTopic  = $currentKey > 0 ? $topicList[$currentKey - 1] : null;
                $nextTopic  = $currentKey < $topicList->count() - 1 ? $topicList[$currentKey + 1] : null;
            @endphp

            <div class="reader-content-wrap">
                {{-- Breadcrumb --}}
                <div class="reader-breadcrumb">
                    <a href="{{ route('courses.index') }}">Courses</a>
                    <span class="sep">/</span>
                    <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                    <span class="sep">/</span>
                    <span style="color:var(--ink);">{{ $currentTopic->title }}</span>
                </div>

                {{-- Header --}}
                <header class="topic-header">
                    <h1 class="topic-title">{{ $currentTopic->title }}</h1>
                    <div class="topic-meta">
                        <span class="eyebrow" style="font-size:.58rem; padding:.2rem .55rem;">
                            {{ str_pad($currentKey + 1, 2, '0', STR_PAD_LEFT) }} / {{ $topicList->count() }}
                        </span>
                        <span>Part of {{ $course->title }}</span>
                    </div>
                </header>

                {{-- Content --}}
                <div class="course-content">
                    {!! htmlspecialchars_decode($currentTopic->html_content) !!}
                </div>

                {{-- Prev / Next navigation --}}
                <div class="topic-nav-footer">
                    @if($prevTopic)
                        <a href="{{ route('courses.show', ['course' => $course->slug, 'topic' => $prevTopic->slug]) }}"
                           class="topic-nav-btn">
                            <i class="bi bi-arrow-left flex-shrink-0"></i>
                            <div class="overflow-hidden">
                                <span class="topic-nav-label">Previous</span>
                                <span class="topic-nav-title">{{ $prevTopic->title }}</span>
                            </div>
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if($nextTopic)
                        <a href="{{ route('courses.show', ['course' => $course->slug, 'topic' => $nextTopic->slug]) }}"
                           class="topic-nav-btn" style="text-align:right; flex-direction: row-reverse;">
                            <i class="bi bi-arrow-right flex-shrink-0"></i>
                            <div class="overflow-hidden">
                                <span class="topic-nav-label">Next</span>
                                <span class="topic-nav-title">{{ $nextTopic->title }}</span>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

        @else

            {{-- Welcome / empty state --}}
            <div class="reader-welcome">
                <span class="welcome-icon">👋</span>
                <h2 class="welcome-title">Welcome to {{ $course->title }}</h2>
                <p style="color:var(--muted); max-width:360px; line-height:1.7; font-size:.9rem; margin-bottom:1.5rem;">
                    Select a topic from the sidebar to start reading. Your progress is saved automatically.
                </p>
                @if($topics->count() > 0)
                    <a href="{{ route('courses.show', ['course' => $course->slug, 'topic' => $topics->first()->slug]) }}"
                       style="display:inline-flex; align-items:center; gap:.4rem; background:var(--brand); color:#fff; font-size:.82rem; font-weight:700; padding:.65rem 1.4rem; border-radius:var(--radius-sm); text-decoration:none; box-shadow:0 3px 10px rgba(22,163,74,.3); transition:background var(--transition-fast);">
                        <i class="bi bi-play-fill"></i> Start First Topic
                    </a>
                @endif
            </div>

        @endif
    </main>
</div>

@endsection