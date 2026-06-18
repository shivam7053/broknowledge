{{-- resources/views/compilers/html.blade.php --}}
@extends('layouts.app')
@section('title', 'HTML / CSS / JS Live Editor — Online Compiler')
@section('meta_description', 'Free online HTML, CSS, and JavaScript live editor with instant preview. Write code and see results instantly in your browser — no setup, no signup.')

@section('head')
@include('partials.compiler-styles')

<link rel="canonical" href="{{ route('compiler.html') }}">
<meta property="og:type" content="website">
<meta property="og:title" content="HTML / CSS / JS Live Editor — Online Compiler">
<meta property="og:description" content="Free online HTML, CSS, and JavaScript live preview editor. See results instantly in your browser.">
<meta property="og:url" content="{{ route('compiler.html') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta name="twitter:card" content="summary_large_image">

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Code Compilers", "item": "{{ route('compiler.html') }}" },
        { "@@type": "ListItem", "position": 3, "name": "HTML / CSS / JS", "item": "{{ route('compiler.html') }}" }
    ]
}
</script>

<style>
/* ── Compiler page styles ──────────────────────────────────── */
.cp-hero {
    text-align: center;
    padding: 3rem 1rem 2rem;
}
.cp-hero-title {
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -0.02em;
    color: var(--bs-body-color);
    margin-bottom: .75rem;
}
.cp-accent { color: #f97316; }
.cp-hero-subtitle {
    font-size: 1.05rem;
    color: var(--bs-secondary-color, #6b7280);
    max-width: 520px;
    margin: 0 auto 1.5rem;
    line-height: 1.6;
}
.eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #f97316;
    background: rgba(249,115,22,.1);
    padding: .3rem .9rem;
    border-radius: 999px;
}

/* Language strip */
.cp-lang-strip {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .5rem;
    list-style: none;
    padding: 0;
    margin: 0;
}
.cp-lang-pill {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .78rem;
    font-weight: 500;
    padding: .35rem .9rem;
    border-radius: 999px;
    border: 1px solid var(--bs-border-color, #e5e7eb);
    color: var(--bs-body-color);
    text-decoration: none;
    background: var(--bs-body-bg);
    transition: border-color .15s, background .15s;
}
.cp-lang-pill:hover { border-color: #f97316; color: #f97316; background: rgba(249,115,22,.06); }
.cp-lang-pill.is-current { border-color: #f97316; color: #f97316; background: rgba(249,115,22,.08); }
.cp-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.dot-html { background: #f97316; }
.dot-py   { background: #3b82f6; }
.dot-cpp  { background: #8b5cf6; }
.dot-java { background: #ef4444; }
.dot-php  { background: #6366f1; }

/* ── Editor shell ──────────────────────────────────────────── */
.compiler-shell {
    border-radius: 14px;
    border: 1px solid var(--bs-border-color, #e5e7eb);
    overflow: hidden;
    background: var(--bs-body-bg);
    box-shadow: 0 4px 32px rgba(0,0,0,.06);
    display: flex;
    flex-direction: column;
    min-height: 620px;
    margin-bottom: 2rem;
}

/* Top chrome bar */
.cp-topbar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
    background: var(--bs-tertiary-bg, #f9fafb);
    flex-shrink: 0;
}
.cp-win-dots { display: flex; gap: 6px; }
.cp-win-dot { width: 12px; height: 12px; border-radius: 50%; }
.dot-red    { background: #f87171; }
.dot-yellow { background: #fbbf24; }
.dot-green  { background: #34d399; }
.cp-title {
    flex: 1;
    text-align: center;
    font-size: .78rem;
    font-weight: 600;
    color: var(--bs-secondary-color, #6b7280);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
}
.live-badge {
    font-size: .65rem;
    font-weight: 700;
    padding: .2rem .65rem;
    background: rgba(239,68,68,.1);
    color: #ef4444;
    border-radius: 999px;
    letter-spacing: .06em;
}

/* Split layout */
.cp-editor-area {
    display: grid;
    grid-template-columns: 1fr 1fr;
    flex: 1;
    min-height: 0;
}
@media (max-width: 767px) {
    .cp-editor-area { grid-template-columns: 1fr; }
    .cp-editors-col { border-right: none !important; border-bottom: 1px solid var(--bs-border-color, #e5e7eb); }
    .compiler-shell { min-height: 800px; }
}

/* Editors column */
.cp-editors-col {
    display: flex;
    flex-direction: column;
    border-right: 1px solid var(--bs-border-color, #e5e7eb);
    min-height: 0;
}

/* Tabs */
.cp-tab-bar {
    display: flex;
    border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
    background: var(--bs-tertiary-bg, #f9fafb);
    flex-shrink: 0;
}
.cp-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 16px;
    font-size: .75rem;
    font-weight: 600;
    color: var(--bs-secondary-color, #6b7280);
    cursor: pointer;
    border: none;
    border-bottom: 2px solid transparent;
    background: none;
    transition: color .15s;
    user-select: none;
    letter-spacing: .02em;
}
.cp-tab:hover { color: var(--bs-body-color); }
.cp-tab.active { color: var(--bs-body-color); border-bottom-color: var(--bs-body-color); }
.cp-tab-dot { width: 7px; height: 7px; border-radius: 50%; }
.td-html { background: #f97316; }
.td-css  { background: #3b82f6; }
.td-js   { background: #eab308; }

/* Editor panes */
.cp-editor-pane { display: none; flex: 1; flex-direction: column; min-height: 0; }
.cp-editor-pane.active { display: flex; }
.cp-code-area {
    flex: 1;
    width: 100%;
    padding: 14px 16px;
    font-family: 'Fira Code', 'Cascadia Code', 'Consolas', 'Menlo', monospace;
    font-size: 13px;
    line-height: 1.7;
    color: var(--bs-body-color);
    background: var(--bs-body-bg);
    border: none;
    outline: none;
    resize: none;
    tab-size: 2;
    min-height: 300px;
}
.cp-code-area:focus { background: var(--bs-body-bg); }

/* Preview column */
.cp-preview-col {
    display: flex;
    flex-direction: column;
    min-height: 0;
}
.cp-preview-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 14px;
    border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
    background: var(--bs-tertiary-bg, #f9fafb);
    flex-shrink: 0;
}
.cp-preview-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .75rem;
    font-weight: 600;
    color: var(--bs-secondary-color, #6b7280);
    letter-spacing: .03em;
}
.cp-pulse {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #34d399;
    animation: cpPulse 2s infinite;
}
@keyframes cpPulse { 0%,100% { opacity: 1; } 50% { opacity: 0.35; } }

.cp-run-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 8px;
    font-size: .75rem;
    font-weight: 600;
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color, #e5e7eb);
    color: var(--bs-body-color);
    cursor: pointer;
    transition: background .12s, border-color .12s;
    letter-spacing: .02em;
}
.cp-run-btn:hover { background: var(--bs-tertiary-bg, #f9fafb); border-color: #f97316; color: #f97316; }

.cp-preview-frame {
    flex: 1;
    border: none;
    background: #ffffff;
    width: 100%;
    min-height: 300px;
}

/* Status bar */
.cp-status-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 14px;
    border-top: 1px solid var(--bs-border-color, #e5e7eb);
    background: var(--bs-tertiary-bg, #f9fafb);
    font-size: .7rem;
    color: var(--bs-secondary-color, #9ca3af);
    flex-shrink: 0;
}
.cp-status-left { display: flex; gap: 14px; align-items: center; }

/* SEO copy */
.cp-seo-copy { padding: 2rem 0 3rem; max-width: 720px; margin: 0 auto; }
.cp-seo-copy h2 { font-size: 1.4rem; font-weight: 700; margin-bottom: 1rem; }
.cp-faq-item { margin-bottom: 1.25rem; }
.cp-faq-item h3 { font-size: 1rem; font-weight: 600; margin-bottom: .35rem; }
.cp-faq-item p { color: var(--bs-secondary-color, #6b7280); line-height: 1.6; font-size: .95rem; }
.cp-crosslinks { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1.5rem; }

.reveal { opacity: 0; transform: translateY(12px); animation: revealUp .5s ease forwards; }
.reveal:nth-child(2) { animation-delay: .1s; }
.reveal:nth-child(3) { animation-delay: .2s; }
@keyframes revealUp { to { opacity: 1; transform: none; } }
</style>
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-5"
     x-data="compilerPage()"
     x-init="pingHealth()">

    {{-- ── Hero ──────────────────────────────────────────────── --}}
    <header class="cp-hero reveal">
        <div class="eyebrow mb-3 mx-auto">
            <i class="bi bi-terminal"></i> Code Playground
        </div>
        <h1 class="cp-hero-title">
            HTML, CSS &amp; <span class="cp-accent">JS</span> — live preview.
        </h1>
        <p class="cp-hero-subtitle">
            Edit markup, styles, and scripts together and watch the result render instantly.
            No build step, no signup.
        </p>
        <ul class="cp-lang-strip" aria-label="Other languages">
            <li><span class="cp-lang-pill is-current"><span class="cp-dot dot-html"></span> HTML/CSS/JS</span></li>
            <li><a href="{{ route('compiler.python') }}" class="cp-lang-pill"><span class="cp-dot dot-py"></span> Python 3.11</a></li>
            <li><a href="{{ route('compiler.cpp') }}"    class="cp-lang-pill"><span class="cp-dot dot-cpp"></span> C++ (GCC 13)</a></li>
            <li><a href="{{ route('compiler.java') }}"   class="cp-lang-pill"><span class="cp-dot dot-java"></span> Java 21</a></li>
            <li><a href="{{ route('compiler.php') }}"    class="cp-lang-pill"><span class="cp-dot dot-php"></span> PHP 8.3</a></li>
        </ul>
    </header>

    {{-- ── Editor shell ─────────────────────────────────────── --}}
    <div class="compiler-shell reveal">

        {{-- Chrome top bar --}}
        <div class="cp-topbar">
            <div class="cp-win-dots">
                <span class="cp-win-dot dot-red"></span>
                <span class="cp-win-dot dot-yellow"></span>
                <span class="cp-win-dot dot-green"></span>
            </div>
            <span class="cp-title">
                <span class="cp-dot dot-html"></span>
                HTML / CSS / JS Live Editor
            </span>
            <span class="live-badge">● LIVE</span>
        </div>

        {{-- Split pane: editors + preview --}}
        <div class="cp-editor-area" style="flex:1;">

            {{-- Left: tabbed editors --}}
            <div class="cp-editors-col">
                <div class="cp-tab-bar" role="tablist">
                    <button class="cp-tab active" role="tab" aria-selected="true"  data-tab="html" aria-controls="pane-html">
                        <span class="cp-tab-dot td-html"></span> HTML
                    </button>
                    <button class="cp-tab"        role="tab" aria-selected="false" data-tab="css"  aria-controls="pane-css">
                        <span class="cp-tab-dot td-css"></span> CSS
                    </button>
                    <button class="cp-tab"        role="tab" aria-selected="false" data-tab="js"   aria-controls="pane-js">
                        <span class="cp-tab-dot td-js"></span> JS
                    </button>
                </div>

                <div class="cp-editor-pane active" id="pane-html" role="tabpanel">
                    <textarea id="html-code" class="cp-code-area" spellcheck="false"
                              autocomplete="off" autocorrect="off" autocapitalize="off"
                              aria-label="HTML editor"></textarea>
                </div>
                <div class="cp-editor-pane" id="pane-css" role="tabpanel">
                    <textarea id="css-code" class="cp-code-area" spellcheck="false"
                              autocomplete="off" autocorrect="off" autocapitalize="off"
                              aria-label="CSS editor"></textarea>
                </div>
                <div class="cp-editor-pane" id="pane-js" role="tabpanel">
                    <textarea id="js-code" class="cp-code-area" spellcheck="false"
                              autocomplete="off" autocorrect="off" autocapitalize="off"
                              aria-label="JavaScript editor"></textarea>
                </div>
            </div>

            {{-- Right: live preview --}}
            <div class="cp-preview-col">
                <div class="cp-preview-header">
                    <span class="cp-preview-label">
                        <span class="cp-pulse"></span> Preview
                    </span>
                    <button class="cp-run-btn" id="cp-run-btn" type="button">
                        ▶ Run
                    </button>
                </div>
                <iframe id="cp-preview-frame"
                        class="cp-preview-frame"
                        sandbox="allow-scripts"
                        title="HTML live preview"></iframe>
            </div>
        </div>

        {{-- Status bar --}}
        <div class="cp-status-bar">
            <div class="cp-status-left">
                <span id="cp-char-count">0 chars</span>
                <span>Auto-refresh on</span>
            </div>
            <span id="cp-status-msg">Ready</span>
        </div>
    </div>

    {{-- ── SEO copy block ──────────────────────────────────── --}}
    <section class="cp-seo-copy reveal" aria-labelledby="html-about-heading">
        <h2 id="html-about-heading">A Live HTML, CSS &amp; JavaScript Sandbox</h2>
        <p class="mb-4">
            This editor renders your markup, styles, and scripts in a live iframe as you type — no compile
            step, no server round-trip. It's a fast way to prototype a layout, test a CSS snippet, debug a
            small script, or follow along with a tutorial without leaving your browser.
        </p>
        <div class="cp-faq-item">
            <h3>Does this run on a server?</h3>
            <p>No. HTML/CSS/JS renders entirely client-side in a sandboxed iframe — nothing is sent anywhere.</p>
        </div>
        <div class="cp-faq-item">
            <h3>Can I use external libraries?</h3>
            <p>Yes — link to any CDN script or stylesheet inside your HTML and it will load in the preview.</p>
        </div>
        <div class="cp-faq-item">
            <h3>Why isn't my JavaScript working?</h3>
            <p>Check the preview for a red error bar at the bottom — JS errors are caught and displayed there so you can debug without opening DevTools.</p>
        </div>
        <div class="cp-crosslinks">
            <a href="{{ route('compiler.python') }}" class="cp-lang-pill"><span class="cp-dot dot-py"></span> Try the Python compiler</a>
            <a href="{{ route('compiler.cpp') }}"    class="cp-lang-pill"><span class="cp-dot dot-cpp"></span> Try the C++ compiler</a>
        </div>
    </section>

</div>

{{-- ── Scripts ────────────────────────────────────────────── --}}
<script>
(function () {
    /* ── Default starter code ────────────────────────────── */
    var DEFAULT_HTML = [
        '<div class="card">',
        '  <h1>Hello, World! \uD83D\uDC4B</h1>',
        '  <p>Edit the panels on the left to see live changes here.</p>',
        '  <button id="btn">Click me</button>',
        '  <div class="counter" id="counter">0 clicks</div>',
        '</div>'
    ].join('\n');

    var DEFAULT_CSS = [
        '* { box-sizing: border-box; margin: 0; padding: 0; }',
        '',
        'body {',
        '  font-family: system-ui, sans-serif;',
        '  display: flex;',
        '  align-items: center;',
        '  justify-content: center;',
        '  min-height: 100vh;',
        '  background: #f0f4f8;',
        '}',
        '',
        '.card {',
        '  background: white;',
        '  border-radius: 16px;',
        '  padding: 2.5rem;',
        '  text-align: center;',
        '  box-shadow: 0 4px 24px rgba(0,0,0,.08);',
        '  max-width: 360px;',
        '  width: 90%;',
        '}',
        '',
        'h1 { font-size: 1.8rem; color: #1a1a2e; margin-bottom: .75rem; }',
        'p  { color: #6b7280; margin-bottom: 1.5rem; line-height: 1.6; }',
        '',
        'button {',
        '  background: #6366f1; color: white; border: none;',
        '  padding: .65rem 1.6rem; border-radius: 8px;',
        '  font-size: 1rem; cursor: pointer;',
        '  transition: background .2s, transform .1s;',
        '}',
        'button:hover  { background: #4f46e5; }',
        'button:active { transform: scale(0.97); }',
        '',
        '.counter { margin-top: 1rem; font-size: .85rem; color: #9ca3af; }'
    ].join('\n');

    var DEFAULT_JS = [
        'var count = 0;',
        'var btn     = document.getElementById("btn");',
        'var counter = document.getElementById("counter");',
        '',
        'btn.addEventListener("click", function () {',
        '  count++;',
        '  counter.textContent = count + (count === 1 ? " click" : " clicks");',
        '  btn.textContent = count > 4 ? "Keep going! \uD83D\uDE80" : "Click me";',
        '});'
    ].join('\n');

    /* ── DOM refs ─────────────────────────────────────────── */
    var htmlEl    = document.getElementById('html-code');
    var cssEl     = document.getElementById('css-code');
    var jsEl      = document.getElementById('js-code');
    var frame     = document.getElementById('cp-preview-frame');
    var runBtn    = document.getElementById('cp-run-btn');
    var charCount = document.getElementById('cp-char-count');
    var statusMsg = document.getElementById('cp-status-msg');

    /* ── Seed defaults ────────────────────────────────────── */
    htmlEl.value = DEFAULT_HTML;
    cssEl.value  = DEFAULT_CSS;
    jsEl.value   = DEFAULT_JS;

    /* ── Build srcdoc ─────────────────────────────────────── */
    function buildDoc() {
        return '<!DOCTYPE html>\n<html>\n<head>\n<meta charset="utf-8">\n<style>\n'
            + cssEl.value
            + '\n<\/style>\n<\/head>\n<body>\n'
            + htmlEl.value
            + '\n<script>\ntry {\n'
            + jsEl.value
            + '\n} catch(e) {\n'
            + '  document.body.insertAdjacentHTML("beforeend",'
            + '"<div style=\\"position:fixed;bottom:0;left:0;right:0;'
            + 'background:#fef2f2;border-top:2px solid #fca5a5;'
            + 'padding:10px 14px;font-family:monospace;font-size:13px;'
            + 'color:#b91c1c;z-index:9999;\\">'
            + '\u26A0\uFE0F " + e.message + "<\/div>");\n}\n'
            + '<\/script>\n<\/body>\n<\/html>';
    }

    /* ── Render preview ───────────────────────────────────── */
    function runPreview() {
        frame.srcdoc = buildDoc();
        var total = htmlEl.value.length + cssEl.value.length + jsEl.value.length;
        charCount.textContent = total.toLocaleString() + ' chars';
        statusMsg.textContent = 'Updated ' + new Date().toLocaleTimeString([], {
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    }

    /* ── Debounced auto-refresh ───────────────────────────── */
    var debounceTimer;
    function debouncedRun() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(runPreview, 400);
    }

    htmlEl.addEventListener('input', debouncedRun);
    cssEl.addEventListener('input',  debouncedRun);
    jsEl.addEventListener('input',   debouncedRun);
    runBtn.addEventListener('click', runPreview);

    /* ── Tab key → indent ─────────────────────────────────── */
    [htmlEl, cssEl, jsEl].forEach(function (ta) {
        ta.addEventListener('keydown', function (e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                var s = ta.selectionStart, end = ta.selectionEnd;
                ta.value = ta.value.slice(0, s) + '  ' + ta.value.slice(end);
                ta.selectionStart = ta.selectionEnd = s + 2;
                debouncedRun();
            }
        });
    });

    /* ── Tab switching ────────────────────────────────────── */
    document.querySelectorAll('.cp-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.cp-tab').forEach(function (t) {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('.cp-editor-pane').forEach(function (p) {
                p.classList.remove('active');
            });
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
            var pane = document.getElementById('pane-' + tab.dataset.tab);
            if (pane) pane.classList.add('active');
        });
    });

    /* ── Initial render ───────────────────────────────────── */
    runPreview();
}());

/* ── Alpine component for health ping ────────────────────── */
function compilerPage() {
    return {
        status: 'checking',
        async pingHealth() {
            try {
                var r = await fetch('{{ route("compiler.health") }}');
                this.status = r.ok ? 'online' : 'offline';
            } catch (e) {
                this.status = 'offline';
            }
        }
    };
}
</script>
@endsection