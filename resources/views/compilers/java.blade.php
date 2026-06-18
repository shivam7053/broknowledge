{{-- resources/views/compilers/java.blade.php --}}
@extends('layouts.app')
@section('title', 'Java 21 Online Compiler (OpenJDK) — Run Java in Your Browser')
@section('meta_description', 'Free online Java 21 compiler using OpenJDK. Write and run Java code instantly in your browser — no setup needed.')

@section('head')
@include('partials.compiler-styles')

<link rel="canonical" href="{{ route('compiler.java') }}">
<meta property="og:type" content="website">
<meta property="og:title" content="Java 21 Online Compiler (OpenJDK) — Run Java in Your Browser">
<meta property="og:description" content="Free online Java 21 compiler using OpenJDK. Write and run Java code instantly — no setup needed.">
<meta property="og:url" content="{{ route('compiler.java') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta name="twitter:card" content="summary_large_image">

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Code Compilers", "item": "{{ route('compiler.html') }}" },
        { "@@type": "ListItem", "position": 3, "name": "Java 21", "item": "{{ route('compiler.java') }}" }
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
            "name": "What does my Java class need to be named?",
            "acceptedAnswer": { "@@type": "Answer", "text": "Your public class must be named Main — it is compiled with javac and run via java -cp /code Main." }
        },
        {
            "@@type": "Question",
            "name": "Which JDK version is used?",
            "acceptedAnswer": { "@@type": "Answer", "text": "OpenJDK 21, running inside an isolated, network-disabled container." }
        }
    ]
}
</script>
@endsection

@php $language = 'java'; @endphp

@section('content')
<div class="container-fluid px-lg-5"
     x-data="compilerPage()"
     x-init="pingHealth()">

    {{-- ── Hero (H1 for SEO) ──────────────────────────── --}}
    <header class="cp-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content;">
            <i class="bi bi-terminal"></i> Code Playground
        </div>
        <h1 class="cp-hero-title">Run <span class="cp-accent">Java 21</span> — instantly.</h1>
        <p class="cp-hero-subtitle">
            Compile and run Java with OpenJDK 21 in a secure, isolated sandbox. No installs, no setup.
        </p>
        <ul class="cp-lang-strip" aria-label="Other languages">
            <li><a href="{{ route('compiler.html') }}" class="cp-lang-pill"><span class="cp-dot dot-html"></span> HTML/CSS/JS</a></li>
            <li><a href="{{ route('compiler.python') }}" class="cp-lang-pill"><span class="cp-dot dot-py"></span> Python 3.11</a></li>
            <li><a href="{{ route('compiler.cpp') }}" class="cp-lang-pill"><span class="cp-dot dot-cpp"></span> C++ (GCC 13)</a></li>
            <li><span class="cp-lang-pill is-current"><span class="cp-dot dot-java"></span> Java 21</span></li>
            <li><a href="{{ route('compiler.php') }}" class="cp-lang-pill"><span class="cp-dot dot-php"></span> PHP 8.3</a></li>
        </ul>
    </header>

    <div class="cp-wrap">

        @include('partials.compiler-sidebar', ['activeLang' => 'java'])

        <div class="cp-panel reveal" x-data="compilerRunner(defaultCode)">
            <div class="cp-topbar">
                <div class="cp-win-dots">
                    <span class="cp-win-dot dot-red"></span>
                    <span class="cp-win-dot dot-yellow"></span>
                    <span class="cp-win-dot dot-green"></span>
                </div>
                <span class="cp-title">
                    <span class="cp-dot dot-java"></span>
                    Java 21 — OpenJDK
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
                    <div>Your public class must be named <code>Main</code> — it's compiled with <strong>javac</strong> and run via <code>java -cp /code Main</code>.</div>
                </div>
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <label class="cp-section-label" for="java-editor">Editor</label>
                        <textarea id="java-editor" class="code-editor p-3"
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
                            <span class="cp-terminal-placeholder">Press ▶ Run to compile and execute your Java code…</span>
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
    <section class="cp-seo-copy reveal" aria-labelledby="java-about-heading">
        <h2 id="java-about-heading">Run Java Online with OpenJDK 21</h2>
        <p>
            Compile and execute Java directly in your browser — no JDK installation, no IDE setup. Each
            submission is compiled with <code>javac</code> and run inside an isolated, network-disabled
            container, making it a quick way to test class behavior, practice syntax, or work through
            algorithm problems.
        </p>
        <div class="cp-faq-item">
            <h3>What does my Java class need to be named?</h3>
            <p>Your public class must be named <code>Main</code> — it's compiled with javac and run via <code>java -cp /code Main</code>.</p>
        </div>
        <div class="cp-faq-item">
            <h3>Which JDK version is used?</h3>
            <p>OpenJDK 21, running inside an isolated, network-disabled container.</p>
        </div>

        <div class="cp-crosslinks">
            <a href="{{ route('compiler.cpp') }}" class="cp-lang-pill"><span class="cp-dot dot-cpp"></span> Try the C++ compiler</a>
            <a href="{{ route('compiler.php') }}" class="cp-lang-pill"><span class="cp-dot dot-php"></span> Try the PHP runner</a>
        </div>
    </section>

</div>

<script>
const defaultCode = `public class Main {
    public static void main(String[] args) {
        System.out.println("Hello from Java 21!");
        for (int i = 1; i <= 5; i++) {
            System.out.println("Count: " + i);
        }
    }
}`;
</script>
@include('partials.compiler-scripts')
@endsection