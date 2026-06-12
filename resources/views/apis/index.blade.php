{{-- apis/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Free Public APIs')
@section('meta_description', 'A curated directory of free public APIs for developers. Explore documentation and endpoints for your next project.')

@section('content')

<style>
    /* ══════════════════════════════════════════════
       API DIRECTORY PAGE
    ══════════════════════════════════════════════ */

    .api-hero {
        padding: 4rem 0 2.5rem;
        text-align: center;
    }

    .api-hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5vw, 3.8rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.05;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .api-hero-title .accent { color: var(--brand); }

    /* ── Search + filter bar ───────────────────── */
    .api-toolbar {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        padding: .75rem 1rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .api-search-wrap {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex: 1;
        min-width: 200px;
        background: var(--surface-2);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-sm);
        padding: .5rem .85rem;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    }

    .api-search-wrap:focus-within {
        border-color: rgba(22,163,74,.4);
        box-shadow: 0 0 0 3px rgba(22,163,74,.08);
    }

    .api-search-input {
        border: none;
        background: transparent;
        outline: none;
        font-size: .82rem;
        color: var(--ink);
        width: 100%;
    }

    .api-search-input::placeholder { color: var(--muted-light); }

    .api-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .04em;
        padding: .45rem .85rem;
        border-radius: 100px;
        border: 1.5px solid var(--card-border);
        background: var(--surface-2);
        color: var(--muted);
        cursor: pointer;
        transition: border-color var(--transition-fast), color var(--transition-fast), background var(--transition-fast);
        white-space: nowrap;
    }

    .api-filter-btn:hover,
    .api-filter-btn.active {
        border-color: rgba(22,163,74,.3);
        color: var(--brand);
        background: var(--brand-pale);
    }

    .api-count-badge {
        font-size: .62rem;
        font-weight: 700;
        background: var(--brand);
        color: #fff;
        border-radius: 100px;
        padding: .08rem .45rem;
        min-width: 16px;
        text-align: center;
    }

    /* ── API cards ─────────────────────────────── */
    .api-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        transition: box-shadow var(--transition), transform var(--transition-spring), border-color var(--transition-fast);
    }

    /* Signature: top-right corner glow on hover */
    .api-card::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(22,163,74,.18) 0%, transparent 70%);
        opacity: 0;
        transition: opacity var(--transition), transform var(--transition-spring);
        transform: scale(.4);
        pointer-events: none;
    }

    .api-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        border-color: rgba(22,163,74,.2);
    }

    .api-card:hover::after {
        opacity: 1;
        transform: scale(1.6);
    }

    /* Category badge */
    .api-category-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .6rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        padding: .22rem .65rem;
        border-radius: 100px;
        background: var(--brand-pale);
        color: var(--brand);
        border: 1px solid rgba(22,163,74,.15);
    }

    /* Auth pill */
    .api-auth-pill {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .6rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--muted-light);
        background: var(--surface-2);
        border: 1px solid var(--card-border);
        border-radius: 100px;
        padding: .22rem .6rem;
    }

    /* API name */
    .api-name {
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.015em;
        margin: .9rem 0 .4rem;
    }

    /* Description */
    .api-desc {
        font-size: .8rem;
        color: var(--muted);
        line-height: 1.65;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    /* Action row */
    .api-actions {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: .5rem;
        margin-top: auto;
    }

    .api-docs-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: .6rem .9rem;
        border-radius: var(--radius-sm);
        font-size: .78rem;
        font-weight: 700;
        background: var(--surface-2);
        color: var(--ink);
        border: 1.5px solid var(--card-border);
        cursor: pointer;
        transition: background var(--transition-fast), color var(--transition-fast), border-color var(--transition-fast), transform var(--transition-spring);
    }

    .api-card:hover .api-docs-btn {
        background: var(--brand);
        color: #fff;
        border-color: var(--brand);
    }

    .api-copy-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        font-size: .85rem;
        background: var(--surface-2);
        color: var(--muted);
        border: 1.5px solid var(--card-border);
        cursor: pointer;
        transition: background var(--transition-fast), color var(--transition-fast), border-color var(--transition-fast);
        flex-shrink: 0;
        position: relative;
    }

    .api-copy-btn:hover {
        background: rgba(6,182,212,.1);
        color: #0891b2;
        border-color: rgba(6,182,212,.3);
    }

    .api-copy-btn.copied {
        background: var(--brand-pale);
        color: var(--brand);
        border-color: rgba(22,163,74,.3);
    }

    /* ── Documentation modal ───────────────────── */
    .docs-overlay {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(0,0,0,.6);
        backdrop-filter: blur(10px);
        z-index: 9000;
    }

    .docs-modal {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        box-shadow: 0 24px 64px rgba(0,0,0,.25);
        max-width: 720px;
        width: 100%;
        max-height: 88vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .docs-modal-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--card-border);
        background: var(--surface-2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-shrink: 0;
    }

    .docs-modal-title {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.015em;
    }

    .docs-modal-close {
        width: 30px;
        height: 30px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--muted);
        font-size: .8rem;
        transition: background var(--transition-fast), color var(--transition-fast), border-color var(--transition-fast);
        flex-shrink: 0;
    }

    .docs-modal-close:hover {
        background: rgba(239,68,68,.08);
        color: #ef4444;
        border-color: rgba(239,68,68,.25);
    }

    .docs-modal-body {
        overflow-y: auto;
        padding: 1.75rem;
        flex: 1;
        scrollbar-width: thin;
        scrollbar-color: rgba(0,0,0,.1) transparent;
    }

    /* Doc content styles */
    .docs-content {
        font-size: .875rem;
        color: var(--ink-2);
        line-height: 1.75;
    }

    .docs-content h1, .docs-content h2, .docs-content h3 {
        font-family: var(--font-display);
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.02em;
        margin-top: 1.75rem;
        margin-bottom: .6rem;
    }

    .docs-content h2 { font-size: 1.2rem; border-bottom: 1px solid var(--card-border); padding-bottom: .4rem; }
    .docs-content h3 { font-size: 1rem; }

    .docs-content p { margin-bottom: 1rem; }

    .docs-content code:not(pre code) {
        font-family: 'Fira Code', monospace;
        font-size: .8em;
        background: var(--brand-pale);
        color: var(--brand-dark);
        padding: .15em .45em;
        border-radius: 4px;
        border: 1px solid rgba(22,163,74,.15);
    }

    .docs-content pre {
        background: #0d1117;
        color: #e6edf3;
        border-radius: var(--radius-sm);
        padding: 1.1rem 1.25rem;
        overflow-x: auto;
        font-family: 'Fira Code', monospace;
        font-size: .78rem;
        line-height: 1.7;
        border: 1px solid rgba(255,255,255,.06);
        margin: 1rem 0;
    }

    .docs-content a { color: var(--brand); text-decoration: underline; text-underline-offset: 2px; }

    .docs-content ul, .docs-content ol { padding-left: 1.4rem; margin-bottom: 1rem; }
    .docs-content li { margin-bottom: .35rem; }

    .docs-content table {
        width: 100%;
        border-collapse: collapse;
        font-size: .8rem;
        margin: 1rem 0;
        border: 1px solid var(--card-border);
        border-radius: var(--radius-sm);
        overflow: hidden;
    }

    .docs-content th {
        background: var(--surface-2);
        padding: .55rem .85rem;
        text-align: left;
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--muted);
        border-bottom: 1px solid var(--card-border);
    }

    .docs-content td {
        padding: .55rem .85rem;
        border-bottom: 1px solid var(--card-border);
        color: var(--ink-2);
    }

    .docs-content tr:last-child td { border-bottom: none; }

    /* No results */
    .api-no-results {
        text-align: center;
        padding: 4rem 2rem;
        grid-column: 1 / -1;
    }
</style>

<div class="container-fluid px-lg-5 py-2"
     x-data="{
         showModal: false,
         modalTitle: '',
         modalContent: '',
         modalCategory: '',
         modalAuth: '',
         modalUrl: '',
         searchQuery: '',
         activeFilter: 'all',
         copiedId: null,

         openDocs(title, content, category, auth, url) {
             this.modalTitle    = title;
             this.modalContent  = content;
             this.modalCategory = category;
             this.modalAuth     = auth;
             this.modalUrl      = url;
             this.showModal     = true;
             document.body.style.overflow = 'hidden';
         },

         closeModal() {
             this.showModal = false;
             document.body.style.overflow = '';
         },

         copyUrl(url, id) {
             navigator.clipboard.writeText(url).then(() => {
                 this.copiedId = id;
                 setTimeout(() => { this.copiedId = null; }, 2000);
             });
         }
     }"
     @keydown.escape.window="closeModal()">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="api-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width:fit-content; background:rgba(6,182,212,.1); color:#0891b2; border-color:rgba(6,182,212,.2);">
            <i class="bi bi-cloud-arrow-down-fill"></i> API Directory
        </div>
        <h1 class="api-hero-title">
            Connect to <span class="accent">Free APIs.</span>
        </h1>
        <p class="small mt-2" style="color:var(--muted); max-width:440px; margin:0 auto; line-height:1.7;">
            A hand-picked collection of public APIs to power your next project. Browse docs and copy endpoints instantly.
        </p>
    </div>

    {{-- ── Search + filter toolbar ─────────────────────── --}}
    @if($apis->count() > 0)
    <div class="mb-4 reveal">
        <div class="api-toolbar">
            <div class="api-search-wrap">
                <i class="bi bi-search" style="color:var(--muted-light); font-size:.8rem; flex-shrink:0;"></i>
                <input type="text"
                       class="api-search-input"
                       placeholder="Search APIs by name or category…"
                       x-model="searchQuery">
                <button x-show="searchQuery"
                        @click="searchQuery = ''"
                        style="background:none; border:none; color:var(--muted-light); cursor:pointer; font-size:.75rem; padding:0; flex-shrink:0;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            {{-- Unique category filters --}}
            <div class="d-flex gap-2 flex-wrap">
                <button class="api-filter-btn"
                        :class="activeFilter === 'all' ? 'active' : ''"
                        @click="activeFilter = 'all'">
                    All
                    <span class="api-count-badge">{{ $apis->count() }}</span>
                </button>

                @foreach($apis->pluck('category')->unique()->sort() as $cat)
                    <button class="api-filter-btn d-none d-md-inline-flex"
                            :class="activeFilter === '{{ $cat }}' ? 'active' : ''"
                            @click="activeFilter = '{{ $cat }}'">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ── Divider ──────────────────────────────────────── --}}
    <div class="section-divider mb-4 reveal">
        <p class="section-divider-label">Available Endpoints</p>
    </div>

    {{-- ── Cards grid ───────────────────────────────────── --}}
    <div class="row g-4 pb-5">
        @forelse($apis as $api)
            <div class="col-md-6 col-lg-4 col-xl-3 reveal stagger-{{ ($loop->index % 4) + 1 }}"
                 x-show="
                     (activeFilter === 'all' || activeFilter === '{{ $api->category }}') &&
                     (searchQuery === '' ||
                      '{{ strtolower($api->title) }}'.includes(searchQuery.toLowerCase()) ||
                      '{{ strtolower($api->category) }}'.includes(searchQuery.toLowerCase()) ||
                      '{{ strtolower($api->description) }}'.includes(searchQuery.toLowerCase()))
                 "
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <div class="api-card">
                    {{-- Top meta row --}}
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                        <span class="api-category-badge">
                            <i class="bi bi-tag" style="font-size:.55rem;"></i>
                            {{ $api->category }}
                        </span>
                        <div class="api-auth-pill">
                            <i class="bi {{ ($api->auth_type ?? 'No Auth') === 'No Auth' ? 'bi-unlock' : 'bi-shield-lock' }}"
                               style="font-size:.6rem;"></i>
                            {{ $api->auth_type ?? 'No Auth' }}
                        </div>
                    </div>

                    <h3 class="api-name">{{ $api->title }}</h3>
                    <p class="api-desc">{{ $api->description }}</p>

                    {{-- Actions --}}
                    <div class="api-actions">
                        <button type="button"
                                class="api-docs-btn"
                                @click="openDocs(
                                    '{{ addslashes($api->title) }}',
                                    '{{ addslashes($api->documentation_html) }}',
                                    '{{ addslashes($api->category) }}',
                                    '{{ addslashes($api->auth_type ?? 'No Auth') }}',
                                    '{{ addslashes($api->url ?? '') }}'
                                )">
                            <i class="bi bi-file-earmark-text"></i>
                            View Docs
                        </button>

                        @if(!empty($api->url))
                            <button type="button"
                                    class="api-copy-btn"
                                    :class="copiedId === {{ $api->id }} ? 'copied' : ''"
                                    @click="copyUrl('{{ $api->url }}', {{ $api->id }})"
                                    title="Copy endpoint URL">
                                <i class="bi" :class="copiedId === {{ $api->id }} ? 'bi-check2' : 'bi-clipboard'"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="api-no-results reveal">
                    <span style="font-size:3rem; display:block; margin-bottom:1rem;">🔌</span>
                    <h3 style="font-family:var(--font-display); font-weight:700; color:var(--ink); margin-bottom:.5rem;">No APIs listed yet</h3>
                    <p style="color:var(--muted); font-size:.875rem; max-width:280px; margin:auto;">
                        We're curating the best free tools. Check back soon!
                    </p>
                </div>
            </div>
        @endforelse

        {{-- Empty search state --}}
        @if($apis->count() > 0)
            <div class="col-12"
                 x-show="$el.parentElement.querySelectorAll('[x-show]:not([style*=\'display: none\'])').length <= 1"
                 style="display:none;">
                <div class="api-no-results">
                    <span style="font-size:3rem; display:block; margin-bottom:1rem;">🔍</span>
                    <h3 style="font-family:var(--font-display); font-weight:700; color:var(--ink); margin-bottom:.5rem;">No results found</h3>
                    <p style="color:var(--muted); font-size:.875rem;">
                        Try a different search term or reset the filter.
                    </p>
                    <button @click="searchQuery=''; activeFilter='all';"
                            style="margin-top:1rem; display:inline-flex; align-items:center; gap:.4rem; background:var(--brand); color:#fff; font-size:.78rem; font-weight:700; padding:.55rem 1.1rem; border-radius:var(--radius-sm); border:none; cursor:pointer;">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset filters
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- ── Documentation Modal ─────────────────────────── --}}
    <div x-show="showModal"
         x-cloak
         class="docs-overlay"
         @click.self="closeModal()"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="docs-modal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal header --}}
            <div class="docs-modal-header">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="eyebrow flex-shrink-0" style="font-size:.58rem; padding:.2rem .6rem;" x-text="modalCategory"></div>
                    <h3 class="docs-modal-title text-truncate" x-text="modalTitle"></h3>
                </div>

                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    {{-- Auth badge --}}
                    <span class="api-auth-pill" x-text="modalAuth"></span>

                    {{-- Copy URL button in modal --}}
                    <template x-if="modalUrl">
                        <button type="button"
                                class="api-copy-btn"
                                @click="navigator.clipboard.writeText(modalUrl).then(() => { this.innerHTML='<i class=\'bi bi-check2\'></i>'; setTimeout(() => { this.innerHTML='<i class=\'bi bi-clipboard\'></i>'; }, 2000); })"
                                title="Copy endpoint URL"
                                style="width:30px; height:30px;">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </template>

                    <button type="button" class="docs-modal-close" @click="closeModal()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Modal body --}}
            <div class="docs-modal-body">
                <div class="docs-content" x-html="modalContent"></div>
            </div>

        </div>
    </div>

</div>

@endsection