{{-- resources/views/partials/compiler-styles.blade.php --}}
<style>
.cp-wrap {
    display: grid;
    grid-template-columns: 210px 1fr;
    gap: 1.5rem;
    align-items: start;
    padding: 2rem 0 4rem;
}
@media (max-width: 768px) {
    .cp-wrap { grid-template-columns: 1fr; }
    .cp-sidebar { position: static !important; }
}

/* Sidebar */
.cp-sidebar {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    box-shadow: var(--card-shadow);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    position: sticky;
    top: 90px;
}
.cp-sidebar-label {
    font-size: .6rem; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--muted-light);
    padding: 0 .5rem; margin-bottom: .75rem; display: block;
}
.cp-nav-link {
    display: flex; align-items: center; gap: .6rem;
    padding: .6rem .75rem;
    border-radius: var(--radius-sm);
    border: 1px solid transparent;
    font-size: .82rem; font-weight: 600;
    color: var(--muted); text-decoration: none;
    transition: background 120ms, color 120ms, border-color 120ms;
    margin-bottom: 2px;
}
.cp-nav-link:hover {
    background: var(--brand-pale); color: var(--brand);
    border-color: rgba(22,163,74,.12);
}
.cp-nav-link.active {
    background: var(--brand-pale); color: var(--brand);
    border-color: rgba(22,163,74,.25); font-weight: 700;
}
.cp-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dot-html { background: #f97316; }
.dot-py   { background: #3b82f6; }
.dot-cpp  { background: #6366f1; }
.dot-java { background: #ef4444; }
.dot-php  { background: #777bb4; }

.cp-status {
    margin-top: 1rem; padding-top: 1rem;
    border-top: 1px solid var(--card-border);
    display: flex; align-items: center; gap: .5rem;
    font-size: .68rem; color: var(--muted-light); padding-left: .5rem;
}
.status-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.status-dot.online   { background: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.15); }
.status-dot.offline  { background: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
.status-dot.checking { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,.15);
                        animation: sp 1.4s ease-in-out infinite; }
@keyframes sp { 0%,100%{opacity:1}50%{opacity:.35} }

/* Panel */
.cp-panel {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    box-shadow: var(--card-shadow);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.cp-topbar {
    display: flex; align-items: center;
    justify-content: space-between; gap: .75rem;
    padding: .85rem 1.5rem;
    background: var(--surface-2);
    border-bottom: 1px solid var(--card-border);
}
.cp-win-dots { display: flex; gap: .4rem; align-items: center; }
.cp-win-dot  { width: 10px; height: 10px; border-radius: 50%; }
.dot-red    { background: #ef4444; }
.dot-yellow { background: #f59e0b; }
.dot-green  { background: #22c55e; }
.cp-title {
    font-family: var(--font-display); font-size: .82rem;
    font-weight: 700; color: var(--muted);
    letter-spacing: .04em;
    display: inline-flex; align-items: center; gap: .5rem; flex: 1;
}
.exit-badge {
    font-size: .62rem; font-weight: 700;
    padding: .15rem .5rem; border-radius: 999px;
}
.exit-ok  { background: rgba(34,197,94,.15); color: #22c55e; }
.exit-err { background: rgba(248,113,113,.15); color: #f87171; }
.cp-run-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #06b6d4; color: #fff;
    font-size: .82rem; font-weight: 700;
    padding: .5rem 1.1rem;
    border-radius: var(--radius-sm); border: none;
    cursor: pointer; white-space: nowrap;
    transition: background 120ms, transform 150ms, box-shadow 120ms;
    box-shadow: 0 3px 10px rgba(6,182,212,.28);
}
.cp-run-btn:hover:not(:disabled) {
    background: #0891b2; transform: translateY(-1px);
    box-shadow: 0 5px 16px rgba(6,182,212,.38);
}
.cp-run-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.cp-body { padding: 1.5rem; }
.code-editor {
    font-family: 'Fira Code','Cascadia Code','Courier New',monospace;
    font-size: .84rem; line-height: 1.65;
    background: #0d1117 !important; color: #e6edf3 !important;
    border: 1px solid rgba(255,255,255,.08) !important;
    border-radius: var(--radius-sm) !important;
    resize: vertical; outline: none !important;
    box-shadow: none !important; tab-size: 4; width: 100%;
}
.code-editor:focus {
    border-color: rgba(6,182,212,.4) !important;
    box-shadow: 0 0 0 3px rgba(6,182,212,.09) !important;
}
.cp-terminal {
    font-family: 'Fira Code','Cascadia Code',monospace;
    font-size: .82rem; line-height: 1.7;
    background: #0d1117; color: #22c55e;
    border: 1px solid rgba(255,255,255,.06);
    border-radius: var(--radius-sm);
    padding: 1rem 1.25rem;
    min-height: 140px; max-height: 320px;
    white-space: pre-wrap; word-break: break-word; overflow-y: auto;
}
.cp-terminal.has-error { color: #f87171; }
.cp-terminal-placeholder { color: rgba(34,197,94,.38); font-style: italic; }
.cp-preview-frame {
    width: 100%; min-height: 420px;
    border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); background: #fff;
}
.cp-section-label {
    font-size: .6rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--muted-light); margin-bottom: .5rem; display: block;
}
</style>