{{-- resources/views/compilers/php-lang.blade.php --}}
@extends('layouts.app')
@section('title', 'PHP 8.3 Online Compiler — Run PHP in Your Browser')
@section('meta_description', 'Free online PHP 8.3 compiler and runner. Write and execute PHP code instantly in your browser — no setup needed.')

@section('head')
@include('partials.compiler-styles')

<link rel="canonical" href="{{ route('compiler.php') }}">
<meta property="og:type" content="website">
<meta property="og:title" content="PHP 8.3 Online Compiler — Run PHP in Your Browser">
<meta property="og:description" content="Free online PHP 8.3 compiler and runner. Write and execute PHP code instantly — no setup needed.">
<meta property="og:url" content="{{ route('compiler.php') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta name="twitter:card" content="summary_large_image">

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Code Compilers", "item": "{{ route('compiler.html') }}" },
        { "@@type": "ListItem", "position": 3, "name": "PHP 8.3", "item": "{{ route('compiler.php') }}" }
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
            "name": "Which PHP version runs this code?",
            "acceptedAnswer": { "@@type": "Answer", "text": "PHP 8.3 CLI, running inside an isolated, network-disabled container." }
        },
        {
            "@@type": "Question",
            "name": "Can I use Composer packages?",
            "acceptedAnswer": { "@@type": "Answer", "text": "No. The sandbox runs plain PHP scripts without network access or a Composer environment." }
        }
    ]
}
</script>
@endsection

@php 
    $language = 'php'; 
    $defaultPhpCode = <<<'PHP'
<?php

echo "Hello from PHP 8.3!\n";

$numbers = [1, 2, 3, 4, 5];
echo "Sum: " . array_sum($numbers) . "\n";
print_r($numbers);
PHP;
@endphp

@section('content')
<div class="container-fluid px-lg-5"
     x-data="compilerPage()"
     x-init="pingHealth()">

    {{-- ── Hero (H1 for SEO) ──────────────────────────── --}}
    <header class="cp-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content;">
            <i class="bi bi-terminal"></i> Code Playground
        </div>
        <h1 class="cp-hero-title">Run <span class="cp-accent">PHP 8.3</span> — instantly.</h1>
        <p class="cp-hero-subtitle">
            Write and execute PHP 8.3 scripts in a secure, isolated sandbox. No installs, no setup.
        </p>
        <ul class="cp-lang-strip" aria-label="Other languages">
            <li><a href="{{ route('compiler.html') }}" class="cp-lang-pill"><span class="cp-dot dot-html"></span> HTML/CSS/JS</a></li>
            <li><a href="{{ route('compiler.python') }}" class="cp-lang-pill"><span class="cp-dot dot-py"></span> Python 3.11</a></li>
            <li><a href="{{ route('compiler.cpp') }}" class="cp-lang-pill"><span class="cp-dot dot-cpp"></span> C++ (GCC 13)</a></li>
            <li><a href="{{ route('compiler.java') }}" class="cp-lang-pill"><span class="cp-dot dot-java"></span> Java 21</a></li>
            <li><span class="cp-lang-pill is-current"><span class="cp-dot dot-php"></span> PHP 8.3</span></li>
        </ul>
    </header>

    <div class="cp-wrap">

        @include('partials.compiler-sidebar', ['activeLang' => 'php'])

        <div class="cp-panel reveal" x-data="compilerRunner(defaultCode)">
            <div class="cp-topbar">
                <div class="cp-win-dots">
                    <span class="cp-win-dot dot-red"></span>
                    <span class="cp-win-dot dot-yellow"></span>
                    <span class="cp-win-dot dot-green"></span>
                </div>
                <span class="cp-title">
                    <span class="cp-dot dot-php"></span>
                    PHP 8.3 Runner
                    <span x-show="exitCode !== null" x-cloak>
                        <span class="exit-badge"
                              :class="isError ? 'exit-err' : 'exit-ok'"
                              x-text="isError ? '✕ exit ' + exitCode : '✓ exit 0'"></span>
                    </span>
                </span>
                <button class="cp-run-btn" @click="run" :disabled="isLoading" type="button">
                    <template x-if="!isLoading">
                        <span>&#9654; Run</span>
                    </template>
                    <template x-if="isLoading">
                        <span>
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Running…
                        </span>
                    </template>
                </button>
            </div>
            <div class="cp-body">
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label class="cp-section-label" for="php-editor">Editor</label>
                        <textarea id="php-editor" class="code-editor p-3"
                                  rows="14"
                                  x-model="code"
                                  spellcheck="false"
                                  autocomplete="off"></textarea>
                    </div>
                    <div>
                        <span class="cp-section-label">Console output</span>
                        <div class="cp-terminal" :class="isError ? 'has-error' : ''"
                             x-show="output || isLoading" x-cloak
                             x-text="isLoading ? '⏳ Executing…' : output"
                             role="status" aria-live="polite"></div>
                        <div class="cp-terminal" x-show="!output && !isLoading">
                            <span class="cp-terminal-placeholder">Press ▶ Run to execute your PHP code…</span>
                        </div>
                        <div class="cp-exec-meta" x-show="elapsedMs !== null">
                            <span class="cp-exec-meta-item">
                                <i class="bi bi-stopwatch"></i>
                                <span x-text="elapsedMs + ' ms'"></span>
                            </span>
                            <span class="cp-exec-meta-item" x-show="isError" style="color:#ef4444;">
                                <i class="bi bi-exclamation-triangle-fill"></i> Exited with error
                            </span>
                            <span class="cp-exec-meta-item" x-show="!isError" style="color:var(--brand);">
                                <i class="bi bi-check-circle-fill"></i> Success
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── SEO copy block ────────────────────────────── --}}
    <section class="cp-seo-copy reveal" aria-labelledby="php-about-heading">
        <h2 id="php-about-heading">A Free PHP 8.3 Runner in Your Browser</h2>
        <p>
            Test PHP 8.3 scripts, debug logic, or practice syntax without setting up a local server. Code runs
            via the PHP 8.3 CLI inside an isolated, network-disabled container, so it's safe to experiment
            with arrays, string functions, and small standalone scripts.
        </p>
        <div class="cp-faq-item">
            <h3>Which PHP version runs this code?</h3>
            <p>PHP 8.3 CLI, running inside an isolated, network-disabled container.</p>
        </div>
        <div class="cp-faq-item">
            <h3>Can I use Composer packages?</h3>
            <p>No — the sandbox runs plain PHP scripts without network access or a Composer environment.</p>
        </div>

        <div class="cp-crosslinks">
            <a href="{{ route('compiler.python') }}" class="cp-lang-pill"><span class="cp-dot dot-py"></span> Try the Python compiler</a>
            <a href="{{ route('compiler.html') }}" class="cp-lang-pill"><span class="cp-dot dot-html"></span> Try the HTML editor</a>
        </div>
    </section>

</div>

<script>
const defaultCode = {!! json_encode($defaultPhpCode) !!};
</script>
@include('partials.compiler-scripts')
@endsection