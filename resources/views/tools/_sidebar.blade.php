{{-- resources/views/tools/_sidebar.blade.php --}}
{{-- Usage: @include('tools._sidebar', ['active' => 'pdf']) --}}

<style>
/* ── Shared Tools Styles (include once via layouts or here) ── */
:root {
    --tool-transition: .15s ease;
}
.tools-nav-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    box-shadow: var(--card-shadow);
    border-radius: var(--radius-lg);
    padding: 1rem;
    position: sticky;
    top: 90px;
}
.suite-link {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .7rem .9rem;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-size: .82rem;
    font-weight: 600;
    color: var(--muted);
    transition: background var(--tool-transition), color var(--tool-transition);
    border: 1px solid transparent;
}
.suite-link:hover { background: var(--surface-2); color: var(--ink); }
.suite-link .suite-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
}
.suite-link.pdf-suite    .suite-icon { background: rgba(99,102,241,.12); color: #6366f1; }
.suite-link.doc-suite    .suite-icon { background: rgba(22,163,74,.12);  color: #16a34a; }
.suite-link.data-suite   .suite-icon { background: rgba(245,158,11,.12); color: #d97706; }
.suite-link.image-suite  .suite-icon { background: rgba(239,68,68,.12);  color: #dc2626; }
.suite-link.dev-suite    .suite-icon { background: rgba(6,182,212,.12);  color: #0891b2; }

.suite-link.active-pdf   { background: rgba(99,102,241,.1);  color: #6366f1; border-color: rgba(99,102,241,.2); }
.suite-link.active-doc   { background: rgba(22,163,74,.1);   color: #16a34a; border-color: rgba(22,163,74,.2); }
.suite-link.active-data  { background: rgba(245,158,11,.1);  color: #d97706; border-color: rgba(245,158,11,.2); }
.suite-link.active-image { background: rgba(239,68,68,.1);   color: #dc2626; border-color: rgba(239,68,68,.2); }
.suite-link.active-dev   { background: rgba(6,182,212,.1);   color: #0891b2; border-color: rgba(6,182,212,.2); }

.suite-count {
    margin-left: auto;
    font-size: .65rem;
    font-weight: 700;
    padding: .15rem .45rem;
    border-radius: 100px;
    background: var(--surface-2);
    color: var(--muted);
}
.nav-divider {
    height: 1px;
    background: var(--card-border);
    margin: .6rem 0;
}
</style>

<div class="tools-nav-card">
    <p class="mb-2" style="font-size:.65rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); padding:.25rem .5rem;">Tool Suites</p>

    <a href="{{ route('tools.pdf') }}"
       class="suite-link pdf-suite {{ $active === 'pdf' ? 'active-pdf' : '' }}">
        <span class="suite-icon"><i class="bi bi-file-earmark-pdf-fill"></i></span>
        <span>PDF Tools</span>
        <span class="suite-count">8</span>
    </a>

    <a href="{{ route('tools.document') }}"
       class="suite-link doc-suite {{ $active === 'document' ? 'active-doc' : '' }}">
        <span class="suite-icon"><i class="bi bi-file-earmark-word-fill"></i></span>
        <span>Document Tools</span>
        <span class="suite-count">11</span>
    </a>

    <a href="{{ route('tools.data') }}"
       class="suite-link data-suite {{ $active === 'data' ? 'active-data' : '' }}">
        <span class="suite-icon"><i class="bi bi-table"></i></span>
        <span>Data Tools</span>
        <span class="suite-count">7</span>
    </a>

    <a href="{{ route('tools.image') }}"
       class="suite-link image-suite {{ $active === 'image' ? 'active-image' : '' }}">
        <span class="suite-icon"><i class="bi bi-image-fill"></i></span>
        <span>Image Tools</span>
        <span class="suite-count">7</span>
    </a>



    <div class="nav-divider"></div>

    <a href="{{ route('tools.index') }}" class="suite-link" style="font-size:.75rem;">
        <i class="bi bi-grid-3x3-gap" style="color:var(--muted);"></i>
        All Tools Hub
    </a>
</div>