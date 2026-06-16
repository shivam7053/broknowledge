{{-- resources/views/compilers/html.blade.php --}}
@extends('layouts.app')
@section('title', 'HTML / CSS / JS Live Editor — Online Compiler')
@section('meta_description', 'Free online HTML CSS JavaScript live preview editor. See results instantly in your browser.')

@section('head')
@include('partials.compiler-styles')
@endsection

@section('content')
<div class="container-fluid px-lg-5"
     x-data="compilerPage()"
     x-init="pingHealth()">
    <div class="cp-wrap">

        @include('partials.compiler-sidebar', ['activeLang' => 'html'])

        <div class="cp-panel" x-data="{ code: defaultHtmlCode }">
            <div class="cp-topbar">
                <div class="cp-win-dots">
                    <span class="cp-win-dot dot-red"></span>
                    <span class="cp-win-dot dot-yellow"></span>
                    <span class="cp-win-dot dot-green"></span>
                </div>
                <span class="cp-title">
                    <span class="cp-dot dot-html"></span>
                    HTML / CSS / JS — Live Preview
                </span>
                <span style="font-size:.65rem;font-weight:700;padding:.2rem .65rem;
                             background:rgba(249,115,22,.12);color:#f97316;border-radius:999px;">
                    LIVE
                </span>
            </div>
            <div class="cp-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;min-height:460px;">
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <span class="cp-section-label">Source code</span>
                        <textarea class="code-editor p-3"
                                  x-model="code"
                                  style="flex:1;min-height:420px;"
                                  spellcheck="false"
                                  autocomplete="off"></textarea>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <span class="cp-section-label">Live preview</span>
                        <iframe class="cp-preview-frame" style="flex:1;"
                                :srcdoc="code"
                                sandbox="allow-scripts"
                                title="HTML live preview"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const defaultHtmlCode = `<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: sans-serif; display: flex; flex-direction: column;
           align-items: center; justify-content: center; height: 100vh;
           margin: 0; background: #f8fafb; }
    h1   { color: #16a34a; font-size: 2rem; margin-bottom: .5rem; }
    p    { color: #6b7280; }
    button { background: #16a34a; color: white; border: none;
             padding: .6rem 1.4rem; border-radius: 8px;
             cursor: pointer; font-size: 1rem; }
    button:hover { background: #15803d; }
  </style>
</head>
<body>
  <h1>Hello, World!</h1>
  <p>Edit this code to see live changes.</p>
  <button onclick="alert('Clicked!')">Try me</button>
</body>
</html>`;

function compilerPage() {
    return {
        status: 'checking',
        async pingHealth() {
            try {
                const r = await fetch('{{ route("compiler.health") }}');
                this.status = r.ok ? 'online' : 'offline';
            } catch { this.status = 'offline'; }
        }
    };
}
</script>
@endsection