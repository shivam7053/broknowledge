{{-- resources/views/resume/analyze-form.blade.php --}}
@extends('layouts.app')

@section('title', 'ATS Resume Optimizer — Free Resume & Job Match Checker')
@section('meta_description', 'Upload your resume and a job description to get an instant ATS compatibility score, keyword analysis, and improvement tips — free, no signup.')

@section('head')
<link rel="canonical" href="{{ route('resume.index') }}">
<meta property="og:type" content="website">
<meta property="og:title" content="ATS Resume Optimizer — Free Resume & Job Match Checker">
<meta property="og:description" content="Upload your resume and a job description to get an instant ATS compatibility score and improvement tips.">
<meta property="og:url" content="{{ route('resume.index') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta name="twitter:card" content="summary_large_image">

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "ATS Resume Optimizer", "item": "{{ route('resume.index') }}" }
    ]
}
</script>

<style>
    /* ══════════════════════════════════════════════
       ATS RESUME OPTIMIZER — UPLOAD FORM
    ══════════════════════════════════════════════ */

    .ats-hero {
        padding: 3.5rem 0 2rem;
        text-align: center;
    }

    .ats-hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.1rem, 4.5vw, 3.1rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.08;
        color: var(--ink);
        margin-bottom: .65rem;
    }

    .ats-hero-title .ats-accent { color: #6366f1; }

    .ats-hero-subtitle {
        font-size: .95rem;
        color: var(--muted);
        max-width: 480px;
        margin: 0 auto;
        line-height: 1.65;
    }

    .ats-form-wrap {
        max-width: 680px;
        margin: 0 auto;
    }

    .ats-error-banner {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .9rem 1.1rem;
        background: rgba(239,68,68,.06);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: var(--radius-sm);
        font-size: .85rem;
        color: #dc2626;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    .ats-form-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow-md);
        border-radius: var(--radius-lg);
        padding: 2rem;
    }

    .ats-field-label {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted-light);
        margin-bottom: .65rem;
        display: block;
    }

    /* Dropzone */
    .ats-dropzone {
        border: 2px dashed rgba(99,102,241,.28);
        border-radius: var(--radius);
        background: rgba(99,102,241,.03);
        padding: 2rem 1.5rem;
        text-align: center;
        transition: border-color var(--transition-fast), background var(--transition-fast);
        cursor: pointer;
        position: relative;
    }

    .ats-dropzone:hover,
    .ats-dropzone.is-dragover {
        border-color: #6366f1;
        background: rgba(99,102,241,.06);
    }

    .ats-dropzone.has-file {
        border-style: solid;
        border-color: rgba(22,163,74,.35);
        background: var(--brand-pale);
    }

    .ats-dropzone-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto .9rem;
        border-radius: 50%;
        background: rgba(99,102,241,.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6366f1;
        font-size: 1.4rem;
        transition: background var(--transition-fast), color var(--transition-fast);
    }

    .ats-dropzone.has-file .ats-dropzone-icon {
        background: var(--brand-pale-2);
        color: var(--brand);
    }

    .ats-dropzone-title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: .25rem;
    }

    .ats-dropzone-sub {
        font-size: .75rem;
        color: var(--muted-light);
    }

    .ats-file-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .ats-file-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .78rem;
        font-weight: 600;
        color: var(--brand);
        background: var(--card-bg);
        border: 1px solid rgba(22,163,74,.25);
        border-radius: 100px;
        padding: .35rem .85rem;
        margin-top: .85rem;
    }

    .ats-field-error {
        font-size: .78rem;
        color: #dc2626;
        margin-top: .5rem;
    }

    .ats-job-textarea {
        width: 100%;
        background: var(--surface-2);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-sm);
        color: var(--ink);
        padding: .9rem 1rem;
        font-size: .88rem;
        line-height: 1.65;
        resize: vertical;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        font-family: var(--font-body);
    }

    .ats-job-textarea:focus {
        outline: none;
        border-color: rgba(99,102,241,.4);
        box-shadow: 0 0 0 3px rgba(99,102,241,.08);
    }

    .ats-job-hint {
        font-size: .72rem;
        color: var(--muted-light);
        margin-top: .5rem;
    }

    .ats-job-counter {
        font-size: .72rem;
        color: var(--muted-light);
        text-align: right;
        margin-top: .4rem;
    }

    .ats-job-counter.is-ok { color: var(--brand); }

    .ats-submit-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .9rem 1.5rem;
        border-radius: var(--radius-sm);
        border: none;
        background: #6366f1;
        color: #fff;
        font-family: var(--font-display);
        font-size: .92rem;
        font-weight: 700;
        letter-spacing: .01em;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(99,102,241,.32);
        transition: background var(--transition-fast), transform var(--transition-spring), box-shadow var(--transition-fast);
    }

    .ats-submit-btn:hover:not(:disabled) {
        background: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(99,102,241,.4);
    }

    .ats-submit-btn:disabled {
        opacity: .7;
        cursor: not-allowed;
        transform: none;
    }

    .ats-submit-btn .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 2px;
    }

    /* Trust strip below the form */
    .ats-trust-strip {
        display: flex;
        justify-content: center;
        gap: 1.75rem;
        margin-top: 1.75rem;
        flex-wrap: wrap;
    }

    .ats-trust-item {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .76rem;
        color: var(--muted);
        font-weight: 600;
    }

    .ats-trust-item .material-symbols-outlined {
        font-size: 1.05rem;
        color: var(--brand);
    }

    /* SEO copy */
    .ats-seo-copy {
        max-width: 680px;
        margin: 4rem auto 1rem;
        padding-top: 2.5rem;
        border-top: 1px solid var(--card-border);
    }

    .ats-seo-copy h2 {
        font-family: var(--font-display);
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -.02em;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .ats-seo-copy p {
        font-size: .88rem;
        color: var(--muted);
        line-height: 1.75;
        margin-bottom: 1rem;
    }

    .ats-faq-item { margin-bottom: 1.25rem; }
    .ats-faq-item h3 {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: .35rem;
    }
    .ats-faq-item p { margin-bottom: 0; }
</style>
@endsection

@section('content')
<div class="container-fluid px-lg-5 py-2">

    {{-- ── Hero (H1 for SEO) ────────────────────────────── --}}
    <header class="ats-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content; background: rgba(99,102,241,.1); color: #6366f1; border-color: rgba(99,102,241,.2);">
            <span class="material-symbols-outlined" style="font-size: 1rem;">description</span> Smart Analysis
        </div>
        <h1 class="ats-hero-title">ATS Resume <span class="ats-accent">Optimizer.</span></h1>
        <p class="ats-hero-subtitle">
            Upload your resume and a job description to get an instant compatibility score, keyword gaps, and concrete improvement tips.
        </p>
    </header>

    <div class="ats-form-wrap reveal">

        @if($errors->has('api'))
            <div class="ats-error-banner" role="alert">
                <span class="material-symbols-outlined flex-shrink-0">error</span>
                <div>{{ $errors->first('api') }}</div>
            </div>
        @endif

        <form id="analysisForm"
              action="{{ route('resume.analyze') }}"
              method="POST"
              enctype="multipart/form-data"
              class="ats-form-card"
              x-data="{
                  fileName: null,
                  isDragging: false,
                  jobText: @js(old('job_description', '')),
                  get charCount() { return this.jobText.length; },
                  get isJobLongEnough() { return this.charCount >= 50; },
                  handleFile(file) { this.fileName = file ? file.name : null; }
              }">
            @csrf

            {{-- ── Resume upload ──────────────────────────── --}}
            <div class="mb-4">
                <label class="ats-field-label">Resume <span style="color:var(--muted-light); font-weight:600;">(PDF, DOCX, or TXT)</span></label>

                <div class="ats-dropzone"
                     :class="{ 'is-dragover': isDragging, 'has-file': fileName }"
                     @dragover.prevent="isDragging = true"
                     @dragleave.prevent="isDragging = false"
                     @drop.prevent="
                        isDragging = false;
                        $refs.resumeInput.files = $event.dataTransfer.files;
                        handleFile($event.dataTransfer.files[0]);
                     ">
                    <div class="ats-dropzone-icon">
                        <span class="material-symbols-outlined" x-text="fileName ? 'task' : 'upload_file'"></span>
                    </div>
                    <p class="ats-dropzone-title" x-text="fileName ? 'File ready to analyze' : 'Drag and drop your resume here'"></p>
                    <p class="ats-dropzone-sub" x-show="!fileName">or click to browse — max size 5MB</p>
                    <span class="ats-file-chip" x-show="fileName">
                        <span class="material-symbols-outlined" style="font-size: .9rem;">description</span>
                        <span x-text="fileName"></span>
                    </span>

                    <input id="resume"
                           name="resume"
                           type="file"
                           required
                           x-ref="resumeInput"
                           class="ats-file-input"
                           accept=".pdf,.doc,.docx,.txt"
                           @change="handleFile($event.target.files[0])">
                </div>

                @error('resume')
                    <p class="ats-field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Job description ────────────────────────── --}}
            <div class="mb-4">
                <label for="job_description" class="ats-field-label">Job Description</label>
                <textarea id="job_description"
                          name="job_description"
                          rows="8"
                          class="ats-job-textarea"
                          x-model="jobText"
                          placeholder="Paste the full job requirements here…"
                          required>{{ old('job_description') }}</textarea>

                <div class="d-flex justify-content-between align-items-start">
                    <p class="ats-job-hint mb-0">Minimum 50 characters for accurate analysis.</p>
                    <p class="ats-job-counter mb-0" :class="isJobLongEnough ? 'is-ok' : ''" x-text="charCount + ' chars'"></p>
                </div>

                @error('job_description')
                    <p class="ats-field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ── Submit ──────────────────────────────────── --}}
            <button type="submit" id="submitBtn" class="ats-submit-btn">
                <span id="btnText" class="d-inline-flex align-items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 1.05rem;">bolt</span>
                    Run Analysis
                </span>
            </button>
        </form>

        {{-- ── Trust strip ────────────────────────────────── --}}
        <div class="ats-trust-strip">
            <span class="ats-trust-item">
                <span class="material-symbols-outlined">lock</span> Private &amp; secure
            </span>
            <span class="ats-trust-item">
                <span class="material-symbols-outlined">bolt</span> Results in seconds
            </span>
            <span class="ats-trust-item">
                <span class="material-symbols-outlined">payments</span> 100% free
            </span>
        </div>
    </div>

    {{-- ── SEO copy block ───────────────────────────────── --}}
    <section class="ats-seo-copy reveal" aria-labelledby="ats-about-heading">
        <h2 id="ats-about-heading">How the ATS Resume Optimizer Works</h2>
        <p>
            Many companies screen resumes with Applicant Tracking Systems (ATS) before a human ever sees
            them. This tool compares your resume against a specific job description and scores it across
            keyword overlap, semantic similarity, quantified achievements, section completeness, and
            formatting — the same signals ATS software typically evaluates.
        </p>
        <div class="ats-faq-item">
            <h3>Is my resume stored after analysis?</h3>
            <p>No. Your file is processed for the analysis and then discarded — it isn't saved or shared.</p>
        </div>
        <div class="ats-faq-item">
            <h3>What file formats are supported?</h3>
            <p>PDF, DOCX, and plain TXT resumes up to 5MB.</p>
        </div>
        <div class="ats-faq-item">
            <h3>Do I need an account to use this?</h3>
            <p>No signup is required — upload your resume and job description to get results immediately.</p>
        </div>
    </section>

</div>

<script>
    document.getElementById('analysisForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');

        // Timeout ensures the form begins submitting before the button disables.
        setTimeout(() => {
            btn.disabled = true;
            text.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Analysing…
            `;
        }, 0);
    });
</script>
@endsection