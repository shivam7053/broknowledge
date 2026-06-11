{{-- compilers.blade.php --}}
@extends('layouts.app')

@section('title', 'Code Playground')

@section('content')

<script src="https://cdn.jsdelivr.net/pyodide/v0.26.1/full/pyodide.js"></script>

<style>
    /* ══════════════════════════════════════════════
       COMPILERS / CODE PLAYGROUND PAGE
    ══════════════════════════════════════════════ */

    .compilers-hero {
        padding: 3.5rem 0 2rem;
        text-align: center;
    }

    .compilers-title {
        font-family: var(--font-display);
        font-size: clamp(2.2rem, 5vw, 3.4rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.06;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .compilers-title .code-accent { color: #06b6d4; }

    .compilers-subtitle {
        font-size: .95rem;
        color: var(--muted);
        max-width: 420px;
        margin: 0 auto;
        line-height: 1.65;
    }

    /* ── Sidebar nav ───────────────────────────── */
    .compiler-sidebar {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        position: sticky;
        top: 90px;
        z-index: 10;
    }

    .sidebar-section-label {
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted-light);
        padding: 0 .5rem;
        margin-bottom: .6rem;
    }

    /* Signature: terminal-style tab buttons */
    .compiler-tab {
        display: flex;
        align-items: center;
        gap: .65rem;
        width: 100%;
        padding: .65rem .75rem;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        background: none;
        font-size: .82rem;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        text-align: left;
        transition: background var(--transition-fast), color var(--transition-fast), border-color var(--transition-fast);
        text-decoration: none;
    }

    .compiler-tab:hover {
        background: var(--brand-pale);
        color: var(--brand);
        border-color: rgba(22,163,74,.15);
    }

    .compiler-tab.active {
        background: var(--brand-pale);
        color: var(--brand);
        border-color: rgba(22,163,74,.25);
        font-weight: 700;
    }

    .compiler-tab-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .tab-dot-html  { background: #f97316; }
    .tab-dot-py    { background: #3b82f6; }
    .tab-dot-java  { background: #ef4444; }

    /* ── Workspace panel ───────────────────────── */
    .compiler-panel {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .panel-topbar {
        padding: .85rem 1.5rem;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface-2);
    }

    .panel-window-dots {
        display: flex;
        gap: .4rem;
        align-items: center;
    }

    .win-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .win-dot-red    { background: #ef4444; }
    .win-dot-yellow { background: #f59e0b; }
    .win-dot-green  { background: #22c55e; }

    .panel-title {
        font-family: var(--font-display);
        font-size: .82rem;
        font-weight: 700;
        color: var(--muted);
        letter-spacing: .04em;
    }

    .panel-body { padding: 1.5rem; }

    /* Code editor */
    .code-editor {
        font-family: 'Fira Code', 'Cascadia Code', 'Courier New', monospace;
        font-size: .85rem;
        line-height: 1.65;
        background: #0d1117 !important;
        color: #e6edf3 !important;
        border: 1px solid rgba(255,255,255,.08) !important;
        border-radius: var(--radius-sm) !important;
        resize: vertical;
        outline: none !important;
        box-shadow: none !important;
    }

    .code-editor:focus {
        border-color: rgba(6,182,212,.4) !important;
        box-shadow: 0 0 0 3px rgba(6,182,212,.1) !important;
    }

    /* Terminal output */
    .terminal-output {
        font-family: 'Fira Code', 'Cascadia Code', monospace;
        font-size: .82rem;
        line-height: 1.7;
        background: #0d1117;
        color: #22c55e;
        border: 1px solid rgba(255,255,255,.06);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
        min-height: 140px;
        white-space: pre-wrap;
        overflow-y: auto;
    }

    .terminal-placeholder { color: rgba(34,197,94,.4); }

    /* Run button */
    .run-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #06b6d4;
        color: #fff;
        font-size: .82rem;
        font-weight: 700;
        padding: .55rem 1.2rem;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        transition: background var(--transition-fast), transform var(--transition-spring), box-shadow var(--transition-fast);
        box-shadow: 0 3px 10px rgba(6,182,212,.3);
    }

    .run-btn:hover:not(:disabled) {
        background: #0891b2;
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(6,182,212,.4);
    }

    .run-btn:disabled { opacity: .65; cursor: not-allowed; transform: none; }

    /* Preview frame */
    .preview-frame {
        width: 100%;
        height: 100%;
        min-height: 420px;
        border: 1px solid var(--card-border);
        border-radius: var(--radius-sm);
        background: white;
    }

    /* Info banner */
    .info-banner {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .9rem 1.1rem;
        background: rgba(6,182,212,.06);
        border: 1px solid rgba(6,182,212,.2);
        border-radius: var(--radius-sm);
        font-size: .8rem;
        color: #0891b2;
        line-height: 1.5;
    }

    [data-bs-theme="dark"] .info-banner { color: #67e8f9; }
</style>

<div class="container-fluid px-lg-5 py-2"
     x-data="{ activeTool: 'html-compiler' }">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="compilers-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content;">
            <i class="bi bi-terminal"></i> Code Playground
        </div>
        <h1 class="compilers-title">
            Write, run, <span class="code-accent">preview</span><br>— instantly.
        </h1>
        <p class="compilers-subtitle">
            Test snippets in HTML, Python, and Java without leaving your browser.
        </p>
    </div>

    <div class="row g-4">

        {{-- ── Sidebar ──────────────────────────────────── --}}
        <div class="col-lg-3 reveal reveal-left">
            <div class="compiler-sidebar">
                <div class="sidebar-section-label">Languages</div>
                <div class="d-flex flex-column gap-1">
                    <button @click="activeTool = 'html-compiler'"
                            :class="activeTool === 'html-compiler' ? 'active' : ''"
                            class="compiler-tab">
                        <span class="compiler-tab-dot tab-dot-html"></span>
                        HTML / CSS / JS
                    </button>
                    <button @click="activeTool = 'python-compiler'"
                            :class="activeTool === 'python-compiler' ? 'active' : ''"
                            class="compiler-tab">
                        <span class="compiler-tab-dot tab-dot-py"></span>
                        Python 3.x
                    </button>
                    <button @click="activeTool = 'java-compiler'"
                            :class="activeTool === 'java-compiler' ? 'active' : ''"
                            class="compiler-tab">
                        <span class="compiler-tab-dot tab-dot-java"></span>
                        Java (Backend)
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Workspace ────────────────────────────────── --}}
        <div class="col-lg-9">

            {{-- HTML Live Preview --}}
            <div x-show="activeTool === 'html-compiler'"
                 x-data="{
                     htmlCode: '<html>\n<head>\n  <style>\n    body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #f8fafb; }\n    h1 { color: #16a34a; font-size: 2rem; margin-bottom: .5rem; }\n    p { color: #6b7280; }\n    button { background: #16a34a; color: white; border: none; padding: .6rem 1.4rem; border-radius: 8px; cursor: pointer; font-size: 1rem; }\n    button:hover { background: #15803d; }\n  </style>\n</head>\n<body>\n  <h1>Hello, World!</h1>\n  <p>Edit this code to see live changes.</p>\n  <button onclick=\"alert(\'Clicked!\')\">Try me</button>\n</body>\n</html>'
                 }">
                <div class="compiler-panel">
                    <div class="panel-topbar">
                        <div class="panel-window-dots">
                            <span class="win-dot win-dot-red"></span>
                            <span class="win-dot win-dot-yellow"></span>
                            <span class="win-dot win-dot-green"></span>
                        </div>
                        <span class="panel-title">HTML Live Preview</span>
                        <span class="eyebrow" style="font-size:.58rem; padding:.2rem .6rem;">Live</span>
                    </div>
                    <div class="panel-body">
                        <div class="row g-3" style="min-height: 460px;">
                            <div class="col-md-6 d-flex flex-column">
                                <label class="sidebar-section-label mb-2">Source code</label>
                                <textarea class="code-editor flex-grow-1 p-3" x-model="htmlCode" style="min-height: 400px;"></textarea>
                            </div>
                            <div class="col-md-6 d-flex flex-column">
                                <label class="sidebar-section-label mb-2">Live preview</label>
                                <iframe class="preview-frame flex-grow-1" :srcdoc="htmlCode"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Python Runner --}}
            <div x-show="activeTool === 'python-compiler'"
                 x-data="{
                     code: 'import sys\nprint(\'Python\', sys.version.split()[0])\n\n# A quick demo\nnumbers = [1, 2, 3, 4, 5]\nprint(f\'Numbers: {numbers}\')\nprint(f\'Sum: {sum(numbers)}\')\nprint(f\'Average: {sum(numbers)/len(numbers)}\')',
                     output: '',
                     isLoading: false,
                     pyodide: null,
                     async run() {
                         this.isLoading = true;
                         this.output = 'Initializing Python runtime...\n';
                         try {
                             if (!this.pyodide) this.pyodide = await loadPyodide();
                             let out = '';
                             this.pyodide.setStdout({ batched: (s) => { out += s + '\n'; } });
                             await this.pyodide.runPythonAsync(this.code);
                             this.output = out || '(no output)';
                         } catch (err) {
                             this.output = '⚠ Error: ' + err.message;
                         } finally {
                             this.isLoading = false;
                         }
                     }
                 }">
                <div class="compiler-panel">
                    <div class="panel-topbar">
                        <div class="panel-window-dots">
                            <span class="win-dot win-dot-red"></span>
                            <span class="win-dot win-dot-yellow"></span>
                            <span class="win-dot win-dot-green"></span>
                        </div>
                        <span class="panel-title">Python 3 Runner</span>
                        <button class="run-btn" @click="run" :disabled="isLoading">
                            <span x-show="!isLoading"><i class="bi bi-play-fill"></i> Run</span>
                            <span x-show="isLoading" x-cloak>
                                <span class="spinner-border spinner-border-sm me-1"></span> Running…
                            </span>
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="sidebar-section-label mb-2">Editor</label>
                                <textarea class="code-editor w-100 p-3" rows="10" x-model="code"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="sidebar-section-label mb-2">Console output</label>
                                <div class="terminal-output" x-text="output || ''">
                                    <span class="terminal-placeholder" x-show="!output">Press Run to execute your code…</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Java (concept) --}}
            <div x-show="activeTool === 'java-compiler'"
                 x-data="{
                     code: 'public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"Hello from Java!\");\n\n        // Simple loop\n        for (int i = 1; i <= 5; i++) {\n            System.out.println(\"Count: \" + i);\n        }\n    }\n}'
                 }">
                <div class="compiler-panel">
                    <div class="panel-topbar">
                        <div class="panel-window-dots">
                            <span class="win-dot win-dot-red"></span>
                            <span class="win-dot win-dot-yellow"></span>
                            <span class="win-dot win-dot-green"></span>
                        </div>
                        <span class="panel-title">Java Compiler</span>
                        <button class="run-btn" disabled style="background: var(--surface-2); color: var(--muted); box-shadow: none;">
                            <i class="bi bi-plug"></i> API Required
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="info-banner mb-4">
                            <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                            <div>
                                Running Java requires a backend server. Integrate with <strong>Judge0</strong> or the <strong>Piston API</strong> to compile and return output securely. The editor below is ready to wire up.
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="sidebar-section-label mb-2">Editor</label>
                                <textarea class="code-editor w-100 p-3" rows="10" x-model="code"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="sidebar-section-label mb-2">Console output</label>
                                <div class="terminal-output">
                                    <span class="terminal-placeholder">Connect a backend runner to see output here.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection