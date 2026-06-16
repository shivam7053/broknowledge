{{-- resources/views/compilers/java.blade.php --}}
@extends('layouts.app')
@section('title', 'Java 21 Online Compiler (OpenJDK) — Run Java in Your Browser')
@section('meta_description', 'Free online Java 21 compiler using OpenJDK. Write and run Java code instantly in your browser — no setup needed.')

@section('head')
@include('partials.compiler-styles')
@endsection

@php $language = 'java'; @endphp

@section('content')
<div class="container-fluid px-lg-5"
     x-data="compilerPage()"
     x-init="pingHealth()">
    <div class="cp-wrap">

        @include('partials.compiler-sidebar', ['activeLang' => 'java'])

        <div class="cp-panel" x-data="compilerRunner(defaultCode)">
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
                        <span>&#9654; Run</span>
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
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <span class="cp-section-label">Editor</span>
                        <textarea class="code-editor p-3"
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
                    </div>
                </div>
            </div>
        </div>

    </div>
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