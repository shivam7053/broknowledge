{{--
    resources/views/partials/compiler-panel.blade.php
    ──────────────────────────────────────────────────
    Reusable panel for Python / C++ / Java / PHP runners.

    Variables expected (passed via @include):
        $title  – panel header string
        $rows   – textarea row count (default: 12)
--}}
<div class="compiler-panel">
    <div class="panel-topbar">
        <div class="panel-window-dots">
            <span class="win-dot win-dot-red"></span>
            <span class="win-dot win-dot-yellow"></span>
            <span class="win-dot win-dot-green"></span>
        </div>

        <span class="panel-title">
            {{ $title }}
            {{-- Show exit-code badge after a run --}}
            <span x-show="exitCode !== null" x-cloak>
                <span class="exit-badge"
                      :class="isError ? 'exit-err' : 'exit-ok'"
                      x-text="isError ? '✕ exit ' + exitCode : '✓ exit 0'"></span>
            </span>
        </span>

        <button class="run-btn" @click="run" :disabled="isLoading" type="button">
            <template x-if="!isLoading">
                <span><i class="bi bi-play-fill"></i> Run</span>
            </template>
            <template x-if="isLoading">
                <span>
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                    Running…
                </span>
            </template>
        </button>
    </div>

    <div class="panel-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="sidebar-section-label mb-2" for="editor-{{ Str::slug($title) }}">Editor</label>
                <textarea id="editor-{{ Str::slug($title) }}"
                          class="code-editor w-100 p-3"
                          rows="{{ $rows ?? 12 }}"
                          x-model="code"
                          spellcheck="false"
                          autocomplete="off"
                          autocorrect="off"
                          autocapitalize="off"></textarea>
            </div>

            <div class="col-12">
                <span class="sidebar-section-label mb-2 d-block">Console output</span>
                <div class="terminal-output"
                     :class="isError ? 'has-error' : ''"
                     x-show="output"
                     x-text="output"
                     x-cloak
                     role="status"
                     aria-live="polite"></div>
                <div class="terminal-output" x-show="!output && !isLoading">
                    <span class="terminal-placeholder" x-text="placeholder"></span>
                </div>
                <div class="terminal-output" x-show="isLoading" x-cloak>
                    <span class="terminal-placeholder">⏳ Executing inside Docker container…</span>
                </div>
            </div>
        </div>
    </div>
</div>