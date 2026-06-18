{{-- resources/views/compilers/python.blade.php --}}
@extends('layouts.app')
@section('title', 'Python 3.11 Online Compiler — Run Python in Your Browser')
@section('meta_description', 'Free online Python 3.11 compiler and runner. Write and execute Python code instantly in your browser — no setup needed.')

@section('head')
@include('partials.compiler-styles')

<link rel="canonical" href="{{ route('compiler.python') }}">
<meta property="og:type" content="website">
<meta property="og:title" content="Python 3.11 Online Compiler — Run Python in Your Browser">
<meta property="og:description" content="Free online Python 3.11 compiler and runner. Write and execute Python code instantly — no setup needed.">
<meta property="og:url" content="{{ route('compiler.python') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta name="twitter:card" content="summary_large_image">

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Code Compilers", "item": "{{ route('compiler.html') }}" },
        { "@@type": "ListItem", "position": 3, "name": "Python 3.11", "item": "{{ route('compiler.python') }}" }
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
            "name": "What Python version does this compiler use?",
            "acceptedAnswer": { "@@type": "Answer", "text": "Python 3.11, executed inside an isolated, network-disabled container." }
        },
        {
            "@@type": "Question",
            "name": "Is my code saved or shared with anyone?",
            "acceptedAnswer": { "@@type": "Answer", "text": "No. Code runs in a temporary container for each execution and is discarded immediately afterward." }
        }
    ]
}
</script>
@endsection

@php $language = 'python'; @endphp

@section('content')
<div class="container-fluid px-lg-5"
     x-data="compilerPage()"
     x-init="pingHealth()">

    {{-- ── Hero (H1 for SEO) ──────────────────────────── --}}
    <header class="cp-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content;">
            <i class="bi bi-terminal"></i> Code Playground
        </div>
        <h1 class="cp-hero-title">Run <span class="cp-accent">Python 3.11</span> — instantly.</h1>
        <p class="cp-hero-subtitle">
            Write Python and execute it in a secure, isolated sandbox. No installs, no signup, no setup.
        </p>
        <ul class="cp-lang-strip" aria-label="Other languages">
            <li><a href="{{ route('compiler.html') }}" class="cp-lang-pill"><span class="cp-dot dot-html"></span> HTML/CSS/JS</a></li>
            <li><span class="cp-lang-pill is-current"><span class="cp-dot dot-py"></span> Python 3.11</span></li>
            <li><a href="{{ route('compiler.cpp') }}" class="cp-lang-pill"><span class="cp-dot dot-cpp"></span> C++ (GCC 13)</a></li>
            <li><a href="{{ route('compiler.java') }}" class="cp-lang-pill"><span class="cp-dot dot-java"></span> Java 21</a></li>
            <li><a href="{{ route('compiler.php') }}" class="cp-lang-pill"><span class="cp-dot dot-php"></span> PHP 8.3</a></li>
        </ul>
    </header>

    <div class="cp-wrap">

        @include('partials.compiler-sidebar', ['activeLang' => 'python'])

        <div class="cp-panel reveal" x-data="compilerRunner(defaultCode)">
            <div class="cp-topbar">
                <div class="cp-win-dots">
                    <span class="cp-win-dot dot-red"></span>
                    <span class="cp-win-dot dot-yellow"></span>
                    <span class="cp-win-dot dot-green"></span>
                </div>
                <span class="cp-title">
                    <span class="cp-dot dot-py"></span>
                    Python 3.11 Runner
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
                        <label class="cp-section-label" for="py-editor">Editor</label>
                        <textarea id="py-editor" class="code-editor p-3"
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
                            <span class="cp-terminal-placeholder">Press ▶ Run to execute your Python code…</span>
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
    <section class="cp-seo-copy reveal" aria-labelledby="py-about-heading">
        <h2 id="py-about-heading">A Free Python 3.11 Sandbox in Your Browser</h2>
        <p>
            Run Python scripts instantly without installing an interpreter or setting up a virtual
            environment. Code executes inside an isolated, network-disabled container, so it's safe to
            experiment, test algorithms, or work through interview-style problems.
        </p>
        <div class="cp-faq-item">
            <h3>What Python version does this compiler use?</h3>
            <p>Python 3.11, executed inside an isolated, network-disabled container.</p>
        </div>
        <div class="cp-faq-item">
            <h3>Is my code saved or shared with anyone?</h3>
            <p>No. Code runs in a temporary container for each execution and is discarded immediately afterward.</p>
        </div>

        <div class="cp-crosslinks">
            <a href="{{ route('compiler.cpp') }}" class="cp-lang-pill"><span class="cp-dot dot-cpp"></span> Try the C++ compiler</a>
            <a href="{{ route('compiler.java') }}" class="cp-lang-pill"><span class="cp-dot dot-java"></span> Try the Java compiler</a>
        </div>
    </section>

</div>

<script>
const defaultCode = `import sys
print('Python', sys.version.split()[0])

numbers = [1, 2, 3, 4, 5]
print(f'Numbers: {numbers}')
print(f'Sum:     {sum(numbers)}')
print(f'Average: {sum(numbers)/len(numbers)}')`;
</script>
@include('partials.compiler-scripts')
@endsection