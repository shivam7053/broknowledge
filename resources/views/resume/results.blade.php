{{-- resources/views/resume/results.blade.php --}}
@extends('layouts.app')

@section('title', 'Your ATS Resume Score & Analysis')
@section('meta_description', 'Your personalized ATS resume score, keyword match breakdown, and improvement recommendations.')

@section('head')
<meta name="robots" content="noindex, nofollow">
<style>
    /* ══════════════════════════════════════════════
       ATS RESULTS DASHBOARD
    ══════════════════════════════════════════════ */

    .ats-results-hero {
        padding: 2.5rem 0 1.5rem;
    }

    .ats-back-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .8rem;
        font-weight: 600;
        color: var(--muted);
        text-decoration: none;
        margin-bottom: 1.25rem;
        transition: color var(--transition-fast);
    }

    .ats-back-link:hover { color: var(--brand); }

    /* ── Top row: summary + score ──────────────── */
    .ats-summary-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        height: 100%;
    }

    .ats-summary-quote {
        font-size: 1.05rem;
        line-height: 1.65;
        color: var(--ink-2);
        font-style: italic;
        margin: 0;
    }

    .ats-score-card {
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
        box-shadow: 0 10px 30px rgba(22,163,74,.28);
        position: relative;
        overflow: hidden;
    }

    .ats-score-card::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .ats-score-number {
        font-family: var(--font-display);
        font-size: 3.2rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -.03em;
        position: relative;
        z-index: 1;
    }

    .ats-score-label {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: rgba(255,255,255,.85);
        margin-top: .35rem;
        position: relative;
        z-index: 1;
    }

    .ats-score-badge {
        margin-top: 1rem;
        font-size: .72rem;
        font-weight: 700;
        padding: .3rem .85rem;
        border-radius: 100px;
        background: rgba(255,255,255,.18);
        position: relative;
        z-index: 1;
    }

    /* ── Section cards ──────────────────────────── */
    .ats-section-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }

    .ats-section-title {
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.015em;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: .55rem;
    }

    .ats-section-icon {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.05rem;
    }

    /* ── Metric breakdown bars ──────────────────── */
    .ats-metric-row { margin-bottom: 1.1rem; }
    .ats-metric-row:last-child { margin-bottom: 0; }

    .ats-metric-head {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: .4rem;
    }

    .ats-metric-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--muted);
    }

    .ats-metric-value {
        font-family: var(--font-display);
        font-size: .85rem;
        font-weight: 700;
        color: var(--ink);
    }

    .ats-metric-track {
        width: 100%;
        height: 6px;
        background: var(--surface-2);
        border-radius: 100px;
        overflow: hidden;
    }

    .ats-metric-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 1s cubic-bezier(0.4,0,0.2,1);
    }

    /* ── Improvement hints ──────────────────────── */
    .ats-hint-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: .85rem;
    }

    .ats-hint-item {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        font-size: .88rem;
        color: var(--ink-2);
        line-height: 1.55;
    }

    .ats-hint-item .material-symbols-outlined {
        color: #f59e0b;
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-top: .1rem;
    }

    /* ── Keyword tags ────────────────────────────── */
    .ats-keyword-group { margin-bottom: 1.25rem; }
    .ats-keyword-group:last-child { margin-bottom: 0; }

    .ats-keyword-group-label {
        font-size: .78rem;
        color: var(--muted);
        margin-bottom: .65rem;
        font-weight: 600;
    }

    .ats-tag-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .ats-tag {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .78rem;
        font-weight: 600;
        padding: .35rem .8rem;
        border-radius: var(--radius-sm);
    }

    .ats-tag.is-matched {
        background: var(--brand-pale);
        color: var(--brand-dark);
        border: 1px solid rgba(22,163,74,.2);
    }

    .ats-tag.is-missing {
        background: rgba(239,68,68,.06);
        color: #b91c1c;
        border: 1px solid rgba(239,68,68,.18);
    }

    /* ── Sidebar cards ───────────────────────────── */
    .ats-checklist-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .65rem 0;
        border-bottom: 1px solid var(--card-border);
        font-size: .85rem;
    }

    .ats-checklist-row:last-child { border-bottom: none; }

    .ats-checklist-label {
        color: var(--ink-2);
        text-transform: capitalize;
    }

    .ats-check-icon { font-size: 1.1rem; }
    .ats-check-icon.is-yes { color: var(--brand); }
    .ats-check-icon.is-no  { color: #d1d5db; }

    /* Formatting warnings */
    .ats-warning-card {
        background: rgba(239,68,68,.05);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
    }

    .ats-warning-title {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        color: #b91c1c;
        margin-bottom: .85rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .ats-warning-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .ats-warning-list li {
        font-size: .82rem;
        color: #b91c1c;
        line-height: 1.5;
        display: flex;
        gap: .5rem;
    }

    .ats-warning-list li::before {
        content: '•';
        flex-shrink: 0;
    }

    /* Re-run CTA */
    .ats-rerun-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
    }

    .ats-rerun-btn {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        background: #6366f1;
        color: #fff;
        font-size: .82rem;
        font-weight: 700;
        padding: .65rem 1.4rem;
        border-radius: 100px;
        text-decoration: none;
        box-shadow: 0 3px 10px rgba(99,102,241,.3);
        transition: background var(--transition-fast), transform var(--transition-spring);
        margin-top: .85rem;
    }

    .ats-rerun-btn:hover { background: #4f46e5; color: #fff; transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="container-fluid px-lg-5 py-2">

    <header class="ats-results-hero reveal">
        <a href="{{ route('resume.index') }}" class="ats-back-link">
            <span class="material-symbols-outlined" style="font-size: .9rem;">arrow_back</span>
            Run another analysis
        </a>
    </header>

    {{-- ── Summary + Score row ─────────────────────────── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8 reveal stagger-1">
            <div class="ats-summary-card">
                <div class="eyebrow mb-3" style="background: rgba(99,102,241,.1); color: #6366f1; border-color: rgba(99,102,241,.2);">
                    <span class="material-symbols-outlined" style="font-size: .9rem;">auto_awesome</span> Executive Summary
                </div>
                <p class="ats-summary-quote">&ldquo;{{ $data['summary'] }}&rdquo;</p>
            </div>
        </div>
        <div class="col-lg-4 reveal stagger-2">
            <div class="ats-score-card">
                <span class="ats-score-number">{{ $data['ats_score']['total'] }}</span>
                <span class="ats-score-label">ATS Match Score</span>
                <span class="ats-score-badge">{{ $data['ats_score']['label'] }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- ── Left column: detailed feedback ──────────── --}}
        <div class="col-lg-8">

            {{-- Score breakdown --}}
            <div class="ats-section-card reveal stagger-1">
                <h2 class="ats-section-title">
                    <span class="ats-section-icon" style="background: var(--brand-pale); color: var(--brand);">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">bar_chart</span>
                    </span>
                    ATS Match Breakdown
                </h2>

                @php
                    $metrics = [
                        ['label' => 'Keyword Match',          'val' => $data['ats_score']['keyword_score'],        'max' => 30, 'color' => 'var(--brand)'],
                        ['label' => 'Semantic Similarity',    'val' => $data['ats_score']['similarity_score'],     'max' => 25, 'color' => '#06b6d4'],
                        ['label' => 'Quantification',         'val' => $data['ats_score']['quantification_score'], 'max' => 15, 'color' => '#8b5cf6'],
                        ['label' => 'Section Completeness',   'val' => $data['ats_score']['section_score'],        'max' => 15, 'color' => '#6366f1'],
                        ['label' => 'Action Verbs',           'val' => $data['ats_score']['verb_score'],           'max' => 10, 'color' => '#f59e0b'],
                        ['label' => 'Contact Info',           'val' => $data['ats_score']['contact_score'],        'max' => 5,  'color' => '#22c55e'],
                    ];
                @endphp

                <div class="row">
                    @foreach($metrics as $m)
                        <div class="col-md-6">
                            <div class="ats-metric-row">
                                <div class="ats-metric-head">
                                    <span class="ats-metric-label">{{ $m['label'] }}</span>
                                    <span class="ats-metric-value">{{ $m['val'] }} / {{ $m['max'] }}</span>
                                </div>
                                <div class="ats-metric-track">
                                    <div class="ats-metric-fill" style="width: {{ ($m['val'] / $m['max']) * 100 }}%; background: {{ $m['color'] }};"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Improvement hints --}}
            <div class="ats-section-card reveal stagger-2">
                <h2 class="ats-section-title">
                    <span class="ats-section-icon" style="background: rgba(245,158,11,.12); color: #d97706;">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">tips_and_updates</span>
                    </span>
                    Priority Improvements
                </h2>

                <ul class="ats-hint-list">
                    @foreach($data['hints'] as $hint)
                        <li class="ats-hint-item">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span>{{ $hint }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Keyword analysis --}}
            <div class="ats-section-card reveal stagger-3" style="margin-bottom: 0;">
                <h2 class="ats-section-title">
                    <span class="ats-section-icon" style="background: var(--brand-pale); color: var(--brand);">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">key</span>
                    </span>
                    Keyword Analysis
                </h2>

                <div class="ats-keyword-group">
                    <p class="ats-keyword-group-label">Matched ({{ count($data['keyword_analysis']['matched_keywords']) }})</p>
                    <div class="ats-tag-wrap">
                        @forelse($data['keyword_analysis']['matched_keywords'] as $kw)
                            <span class="ats-tag is-matched">
                                <span class="material-symbols-outlined" style="font-size: .85rem;">check</span>
                                {{ $kw }}
                            </span>
                        @empty
                            <span style="font-size: .82rem; color: var(--muted-light);">No matched keywords found.</span>
                        @endforelse
                    </div>
                </div>

                <div class="ats-keyword-group">
                    <p class="ats-keyword-group-label">Missing ({{ count($data['keyword_analysis']['missing_keywords']) }})</p>
                    <div class="ats-tag-wrap">
                        @forelse($data['keyword_analysis']['missing_keywords'] as $kw)
                            <span class="ats-tag is-missing">
                                <span class="material-symbols-outlined" style="font-size: .85rem;">close</span>
                                {{ $kw }}
                            </span>
                        @empty
                            <span style="font-size: .82rem; color: var(--muted-light);">No missing keywords — nice work.</span>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Right column: checklist & warnings ──────── --}}
        <div class="col-lg-4">

            <div class="ats-section-card reveal stagger-1">
                <h2 class="ats-section-title">
                    <span class="ats-section-icon" style="background: var(--surface-2); color: var(--muted);">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">checklist</span>
                    </span>
                    Section Checklist
                </h2>

                @foreach($data['sections_detected'] as $section => $found)
                    <div class="ats-checklist-row">
                        <span class="ats-checklist-label">{{ $section }}</span>
                        <span class="material-symbols-outlined ats-check-icon {{ $found ? 'is-yes' : 'is-no' }}">
                            {{ $found ? 'check_circle' : 'cancel' }}
                        </span>
                    </div>
                @endforeach
            </div>

            @if(count($data['formatting_issues']) > 0)
                <div class="ats-warning-card reveal stagger-2 mb-4">
                    <h3 class="ats-warning-title">
                        <span class="material-symbols-outlined">warning</span>
                        Formatting Warnings
                    </h3>
                    <ul class="ats-warning-list">
                        @foreach($data['formatting_issues'] as $issue)
                            <li>{{ $issue }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="ats-rerun-card reveal stagger-3">
                <p style="font-size: .82rem; color: var(--muted); margin-bottom: 0; line-height: 1.55;">
                    Made changes to your resume? Re-run the analysis to see your updated score.
                </p>
                <a href="{{ route('resume.index') }}" class="ats-rerun-btn">
                    <span class="material-symbols-outlined" style="font-size: 1rem;">refresh</span>
                    Analyze Again
                </a>
            </div>

        </div>
    </div>

</div>
@endsection