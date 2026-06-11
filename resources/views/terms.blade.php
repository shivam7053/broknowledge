@extends('layouts.app')

@section('title', 'Terms of Use')

@section('content')
<style>
    .legal-container { max-width: 850px; margin: 0 auto; padding: 4rem 1.5rem; }
    .legal-header { margin-bottom: 4rem; text-align: center; }
    .legal-title { font-family: var(--font-display); font-weight: 800; font-size: clamp(2.5rem, 5vw, 3.5rem); color: var(--ink); margin-top: 1rem; }
    .legal-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg); padding: 3rem; box-shadow: var(--card-shadow); }
    .legal-section { margin-bottom: 2.5rem; }
    .legal-section h2 { font-family: var(--font-display); font-weight: 700; font-size: 1.4rem; color: var(--brand); margin-bottom: 1rem; display: flex; align-items: center; gap: .75rem; }
    .legal-section p, .legal-section li { color: var(--muted); line-height: 1.8; font-size: 0.95rem; }
</style>

<div class="container-fluid">
    <div class="legal-container">
        <header class="legal-header reveal">
            <div class="eyebrow mx-auto" style="width: fit-content;"><i class="bi bi-file-earmark-text-fill"></i> Agreement</div>
            <h1 class="legal-title">Terms of Use</h1>
            <p class="text-muted mt-3">Effective Date: {{ date('F d, Y') }}</p>
        </header>

        <div class="legal-card reveal stagger-1">
            <div class="legal-section">
                <h2><i class="bi bi-check-circle"></i> Acceptance of Terms</h2>
                <p>By accessing and using BroKnowledge, you agree to comply with and be bound by these Terms of Use. If you do not agree, please refrain from using our services.</p>
            </div>

            <div class="legal-section">
                <h2><i class="bi bi-code-square"></i> Use of Services</h2>
                <p>Our platform provides educational content, coding tools, and games. You agree to use these services only for lawful purposes. Any attempt to disrupt the service, reverse-engineer our tools, or use our compilers for malicious activities is strictly prohibited.</p>
            </div>

            <div class="legal-section">
                <h2><i class="bi bi-patch-check"></i> Intellectual Property</h2>
                <p>All content, including course materials, website design, and proprietary code snippets, are the property of BroKnowledge. You are granted a limited license to use these materials for personal, non-commercial educational purposes.</p>
            </div>

            <div class="legal-section">
                <h2><i class="bi bi-exclamation-triangle"></i> Disclaimer of Warranties</h2>
                <p>Our tools and compilers are provided "as is" without any warranties. While we strive for 100% accuracy, BroKnowledge is not responsible for any data loss or errors resulting from the use of our client-side office tools or code execution environments.</p>
            </div>

            <div class="legal-section">
                <h2><i class="bi bi-arrow-repeat"></i> Modifications</h2>
                <p>We reserve the right to update these terms at any time. Your continued use of the platform after changes are posted constitutes your acceptance of the new terms.</p>
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