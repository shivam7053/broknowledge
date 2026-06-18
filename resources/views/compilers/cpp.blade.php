{{-- resources/views/compilers/cpp.blade.php --}}
@extends('layouts.app')
@section('title', 'C++ Online Compiler (GCC 13) — Run C++ in Your Browser')
@section('meta_description', 'Free online C++ compiler using GCC 13. Write and compile C++ code instantly in your browser — no setup needed.')

@section('head')
@include('partials.compiler-styles')

<link rel="canonical" href="{{ route('compiler.cpp') }}">
<meta property="og:type" content="website">
<meta property="og:title" content="C++ Online Compiler (GCC 13) — Run C++ in Your Browser">
<meta property="og:description" content="Free online C++ compiler using GCC 13. Write and compile C++ code instantly — no setup needed.">
<meta property="og:url" content="{{ route('compiler.cpp') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta name="twitter:card" content="summary_large_image">

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Code Compilers", "item": "{{ route('compiler.html') }}" },
        { "@@type": "ListItem", "position": 3, "name": "C++ (GCC 13)", "item": "{{ route('compiler.cpp') }}" }
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
            "name": "Which compiler is used for C++?",
            "acceptedAnswer": { "@@type": "Answer", "text": "GCC 13, running inside an isolated, network-disabled container." }
        },
        {
            "@@type": "Question",
            "name": "Does compile time count toward execution limits?",
            "acceptedAnswer": { "@@type": "Answer", "text": "Yes. The execution window includes both compilation and runtime." }
        }
    ]
}
</script>
@endsection

@php $language = 'cpp'; @endphp

@section('content')
<div class="container-fluid px-lg-5"
     x-data="compilerPage()"
     x-init="pingHealth()">

    {{-- ── Hero (H1 for SEO) ──────────────────────────── --}}
    <header class="cp-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content;">
            <i class="bi bi-terminal"></i> Code Playground
        </div>
        <h1 class="cp-hero-title">Compile <span class="cp-accent">C++</span> — in your browser.</h1>
        <p class="cp-hero-subtitle">
            Write and compile C++ with GCC 13 in a secure, isolated sandbox. No installs, no setup.
        </p>
        <ul class="cp-lang-strip" aria-label="Other languages">
            <li><a href="{{ route('compiler.html') }}" class="cp-lang-pill"><span class="cp-dot dot-html"></span> HTML/CSS/JS</a></li>
            <li><a href="{{ route('compiler.python') }}" class="cp-lang-pill"><span class="cp-dot dot-py"></span> Python 3.11</a></li>
            <li><span class="cp-lang-pill is-current"><span class="cp-dot dot-cpp"></span> C++ (GCC 13)</span></li>
            <li><a href="{{ route('compiler.java') }}" class="cp-lang-pill"><span class="cp-dot dot-java"></span> Java 21</a></li>
            <li><a href="{{ route('compiler.php') }}" class="cp-lang-pill"><span class="cp-dot dot-php"></span> PHP 8.3</a></li>
        </ul>
    </header>

    <div class="cp-wrap">

        @include('partials.compiler-sidebar', ['activeLang' => 'cpp'])

        <div class="cp-panel reveal" x-data="compilerRunner(defaultCode)">
            <div class="cp-topbar">
                <div class="cp-win-dots">
                    <span class="cp-win-dot dot-red"></span>
                    <span class="cp-win-dot dot-yellow"></span>
                    <span class="cp-win-dot dot-green"></span>
                </div>
                <span class="cp-title">
                    <span class="cp-dot dot-cpp"></span>
                    C++ Compiler — GCC 13
                    <span x-show="exitCode !== null" x-cloak>
                        <span class="exit-badge"
                              :class="isError ? 'exit-err' : 'exit-ok'"
                              x-text="isError ? '✕ exit ' + exitCode : '✓ exit 0'"></span>
                    </span>
                </span>
                <button class="cp-run-btn" @click="run" :disabled="isLoading" type="button">
                    <template x-if="!isLoading">
                        <span>&#9654; Compile &amp; Run</span>
                    </template>
                    <template x-if="isLoading">
                        <span>
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Compiling…
                        </span>
                    </template>
                </button>
            </div>
            <div class="cp-body">
                <div class="cp-info-banner">
                    <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                    <div>Code is compiled with <code>g++</code> and executed inside a network-isolated container. Compilation time counts toward the execution limit.</div>
                </div>
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label class="cp-section-label" for="cpp-editor">Editor</label>
                        <textarea id="cpp-editor" class="code-editor p-3"
                                  rows="14"
                                  x-model="code"
                                  spellcheck="false"
                                  autocomplete="off"></textarea>
                    </div>
                    <div>
                        <span class="cp-section-label">Console output</span>
                        <div class="cp-terminal" :class="isError ? 'has-error' : ''"
                             x-show="output || isLoading" x-cloak
                             x-text="isLoading ? '⏳ Compiling and running…' : output"
                             role="status" aria-live="polite"></div>
                        <div class="cp-terminal" x-show="!output && !isLoading">
                            <span class="cp-terminal-placeholder">Press ▶ Run to compile and execute your C++ code…</span>
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
    <section class="cp-seo-copy reveal" aria-labelledby="cpp-about-heading">
        <h2 id="cpp-about-heading">Compile C++ Online with GCC 13</h2>
        <p>
            Test C++ snippets, data structures, or algorithm implementations without setting up a local
            toolchain. Your code is compiled with GCC 13 and run inside a sandboxed, network-disabled
            container, then discarded once execution finishes.
        </p>
        <div class="cp-faq-item">
            <h3>Which compiler is used for C++?</h3>
            <p>GCC 13, running inside an isolated, network-disabled container.</p>
        </div>
        <div class="cp-faq-item">
            <h3>Does compile time count toward execution limits?</h3>
            <p>Yes — the execution window includes both compilation and runtime.</p>
        </div>

        <div class="cp-crosslinks">
            <a href="{{ route('compiler.java') }}" class="cp-lang-pill"><span class="cp-dot dot-java"></span> Try the Java compiler</a>
            <a href="{{ route('compiler.python') }}" class="cp-lang-pill"><span class="cp-dot dot-py"></span> Try the Python compiler</a>
        </div>
    </section>

</div>

<script>
const defaultCode = `#include <iostream>
#include <vector>

int main() {
    std::cout << "Hello from C++!" << std::endl;

    std::vector<int> v = {1, 2, 3, 4, 5};
    int sum = 0;
    for (int n : v) sum += n;
    std::cout << "Sum: " << sum << std::endl;
    return 0;
}`;
</script>
@include('partials.compiler-scripts')
@endsection