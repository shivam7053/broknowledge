@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('meta_description', 'Understand how BroKnowledge protects your data. Our privacy policy details client-side processing, data collection, and commitment to your privacy.')

@section('content')
<style>
    .legal-container { max-width: 850px; margin: 0 auto; padding: 4rem 1.5rem; }
    .legal-header { margin-bottom: 4rem; text-align: center; }
    .legal-title { font-family: var(--font-display); font-weight: 800; font-size: clamp(2.5rem, 5vw, 3.5rem); color: var(--ink); margin-top: 1rem; }
    .legal-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg); padding: 3rem; box-shadow: var(--card-shadow); }
    .legal-section { margin-bottom: 2.5rem; }
    .legal-section h2 { font-family: var(--font-display); font-weight: 700; font-size: 1.4rem; color: var(--brand); margin-bottom: 1rem; display: flex; align-items: center; gap: .75rem; }
    .legal-section p, .legal-section li { color: var(--muted); line-height: 1.8; font-size: 0.95rem; }
    .legal-section ul { padding-left: 1.25rem; }
</style>

<div class="container-fluid">
    <div class="legal-container">
        <header class="legal-header reveal">
            <div class="eyebrow mx-auto" style="width: fit-content;"><i class="bi bi-shield-lock-fill"></i> Compliance</div>
            <h1 class="legal-title">Privacy Policy</h1>
            <p class="text-muted mt-3">Last Updated: {{ date('F d, Y') }}</p>
        </header>

        <div class="legal-card reveal stagger-1">
            <div class="legal-section">
                <h2><i class="bi bi-info-circle"></i> Introduction</h2>
                <p>Welcome to BroKnowledge. We value your privacy and are committed to protecting your personal data. This policy explains how we handle information when you use our courses, tools, and games.</p>
            </div>

            <div class="legal-section">
                <h2><i class="bi bi-cpu"></i> Client-Side Processing</h2>
                <p>Most of our interactive tools (PDF Merger, Image Compressor, Compilers) run entirely within your browser. <strong>Your files are never uploaded to our servers.</strong> This "Privacy by Design" approach ensures your sensitive data remains on your local machine.</p>
            </div>

            <div class="legal-section">
                <h2><i class="bi bi-database"></i> Information We Collect</h2>
                <p>We collect minimal data to provide a better experience:</p>
                <ul>
                    <li><strong>Usage Data:</strong> Anonymous statistics regarding page views and tool usage.</li>
                    <li><strong>Preferences:</strong> Local storage settings such as Dark Mode or game high scores.</li>
                    <li><strong>Cookies:</strong> Essential cookies required for site functionality and security.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2><i class="bi bi-envelope-at"></i> Contact Us</h2>
                <p>If you have any questions regarding this Privacy Policy or our practices, please contact us at support@broknowledge.com.</p>
            </div>
        </div>

        <div class="text-center mt-5 reveal stagger-2">
            <a href="{{ route('home') }}" class="btn-tool btn-tool-outline" style="text-decoration: none;">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</div>
@endsection