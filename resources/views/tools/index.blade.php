{{-- resources/views/tools/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Free Online Office & Productivity Tools')
@section('meta_description', 'A complete suite of browser-based tools for developers and office work. Merge PDFs, edit images, format JSON, and convert documents safely and privately.')

@section('head')
<link rel="canonical" href="{{ route('tools.index') }}">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Office Tools", "item": "{{ route('tools.index') }}" }
    ]
}
</script>
@endsection

@section('content')
<style>
.tools-hero { padding: 3.5rem 0 2.5rem; text-align: center; }
.tools-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 5vw, 3.4rem);
    font-weight: 800; letter-spacing: -.04em; line-height: 1.06;
    color: var(--ink); margin-bottom: .75rem;
}
.tools-title span { color: #6366f1; }
.suite-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    box-shadow: var(--card-shadow);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    text-decoration: none;
    display: block;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}
.suite-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,.08); }
.suite-card-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin-bottom: 1rem;
}
.suite-card h3 { font-family: var(--font-display); font-size: 1rem; font-weight: 700; color: var(--ink); margin-bottom: .35rem; }
.suite-card p  { font-size: .78rem; color: var(--muted); line-height: 1.6; margin: 0; }
.tool-pill {
    display: inline-block;
    font-size: .65rem; font-weight: 600;
    padding: .2rem .6rem; border-radius: 100px;
    margin: .2rem .1rem 0; letter-spacing: .02em;
}
</style>

<div class="container-fluid px-lg-5 py-2">
    <div class="tools-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width:fit-content; background:rgba(99,102,241,.1); color:#6366f1; border-color:rgba(99,102,241,.2);">
            <i class="bi bi-tools"></i> Office Workspace
        </div>
        <h1 class="tools-title">Private tools,<br><span>right in your browser.</span></h1>
        <p class="small mt-2" style="color:var(--muted); max-width:440px; margin:0 auto; line-height:1.7;">
            39 tools across 5 suites. Everything runs client-side — your files never leave your device.
        </p>
    </div>

    <div class="row g-4 pb-5">
        {{-- PDF --}}
        <div class="col-md-6 col-xl-4 reveal">
            <a href="{{ route('tools.pdf') }}" class="suite-card" style="border-top: 3px solid #6366f1;">
                <div class="suite-card-icon" style="background:rgba(99,102,241,.12); color:#6366f1;">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                </div>
                <h3>PDF Suite <span style="font-size:.7rem; color:#6366f1; font-weight:600; margin-left:.3rem;">8 tools</span></h3>
                <p>Merge, split, compress, rotate, watermark PDFs. Convert images to PDF and extract text.</p>
                <div class="mt-3">
                    @foreach(['Merge','Split','Compress','Rotate','Watermark','Images→PDF','PDF→Images','Page Extract'] as $t)
                        <span class="tool-pill" style="background:rgba(99,102,241,.1); color:#6366f1;">{{ $t }}</span>
                    @endforeach
                </div>
            </a>
        </div>

        {{-- Document --}}
        <div class="col-md-6 col-xl-4 reveal">
            <a href="{{ route('tools.document') }}" class="suite-card" style="border-top: 3px solid #16a34a;">
                <div class="suite-card-icon" style="background:rgba(22,163,74,.12); color:#16a34a;">
                    <i class="bi bi-file-earmark-word-fill"></i>
                </div>
                <h3>Document Suite <span style="font-size:.7rem; color:#16a34a; font-weight:600; margin-left:.3rem;">11 tools</span></h3>
                <p>Word counter, Markdown editor, text diff, case converter, Lorem Ipsum, find & replace, and more.</p>
                <div class="mt-3">
                    @foreach(['Word Count','Markdown','Text Diff','Case Convert','Lorem Ipsum','Find & Replace','Text Sort','Duplicate Remover'] as $t)
                        <span class="tool-pill" style="background:rgba(22,163,74,.1); color:#16a34a;">{{ $t }}</span>
                    @endforeach
                </div>
            </a>
        </div>

        {{-- Data --}}
        <div class="col-md-6 col-xl-4 reveal">
            <a href="{{ route('tools.data') }}" class="suite-card" style="border-top: 3px solid #d97706;">
                <div class="suite-card-icon" style="background:rgba(245,158,11,.12); color:#d97706;">
                    <i class="bi bi-table"></i>
                </div>
                <h3>Data Suite <span style="font-size:.7rem; color:#d97706; font-weight:600; margin-left:.3rem;">7 tools</span></h3>
                <p>View CSVs, convert JSON↔CSV, format JSON, diff JSON, generate fake data, and parse SQL.</p>
                <div class="mt-3">
                    @foreach(['CSV Viewer','JSON→CSV','CSV→JSON','JSON Formatter','JSON Diff','Fake Data','SQL Formatter'] as $t)
                        <span class="tool-pill" style="background:rgba(245,158,11,.1); color:#d97706;">{{ $t }}</span>
                    @endforeach
                </div>
            </a>
        </div>

        {{-- Image --}}
        <div class="col-md-6 col-xl-4 reveal">
            <a href="{{ route('tools.image') }}" class="suite-card" style="border-top: 3px solid #dc2626;">
                <div class="suite-card-icon" style="background:rgba(239,68,68,.12); color:#dc2626;">
                    <i class="bi bi-image-fill"></i>
                </div>
                <h3>Image Suite <span style="font-size:.7rem; color:#dc2626; font-weight:600; margin-left:.3rem;">7 tools</span></h3>
                <p>Compress, resize, crop, convert format, add watermark, grayscale, and read EXIF metadata.</p>
                <div class="mt-3">
                    @foreach(['Compress','Resize','Crop','Convert','Watermark','Grayscale','EXIF Info'] as $t)
                        <span class="tool-pill" style="background:rgba(239,68,68,.1); color:#dc2626;">{{ $t }}</span>
                    @endforeach
                </div>
            </a>
        </div>

    </div>
</div>
@endsection