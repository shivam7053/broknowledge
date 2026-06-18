{{-- quizzes/take.blade.php --}}
@extends('layouts.app')

@section('title', 'Take Quiz - Examination Hall')
@section('title', 'Take Quiz: ' . $quiz->title . ' - BroKnowledge')
@section('meta_description', 'Engage in a timed quiz challenge for ' . $quiz->title . '. Answer multiple-choice questions and test your knowledge in a focused examination environment.')

@section('head')
<link rel="canonical" href="{{ route('quizzes.take', $quiz->slug) }}">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Quizzes", "item": "{{ route('quizzes.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "{{ $quiz->quizCategory->title }}", "item": "{{ route('quizzes.showCategoryQuizzes', $quiz->quizCategory->slug) }}" },
        { "@@type": "ListItem", "position": 4, "name": "{{ $quiz->title }}", "item": "{{ route('quizzes.take', $quiz->slug) }}" }
    ]
}
</script>
@endsection

@section('content')

@php $csrfToken = csrf_token(); @endphp

<style>
    /* ══════════════════════════════════════════════
       EXAM HALL PAGE
      Full-screen, no scroll, no distractions.
    ══════════════════════════════════════════════ */

    .exam-wrap {
        height: calc(100vh - 65px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: var(--surface);
    }

    /* ── Top bar ───────────────────────────────── */
    .exam-topbar {
        background: var(--card-bg);
        border-bottom: 1px solid var(--card-border);
        padding: .7rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-shrink: 0;
        box-shadow: var(--card-shadow);
        z-index: 10;
    }

    .exam-candidate {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .candidate-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid var(--brand-pale-2);
        object-fit: cover;
        flex-shrink: 0;
    }

    .candidate-name {
        font-family: var(--font-display);
        font-size: .82rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
    }

    .exam-title-badge {
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--brand);
        background: var(--brand-pale);
        border: 1px solid rgba(22,163,74,.2);
        border-radius: 100px;
        padding: .18rem .6rem;
    }

    .exam-center-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .exam-name-chip {
        font-family: var(--font-display);
        font-size: .9rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.01em;
        max-width: 260px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Timer */
    .exam-timer {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .timer-label {
        font-size: .58rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted-light);
        margin-bottom: .1rem;
    }

    .timer-value {
        font-family: var(--font-display);
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -.02em;
        color: var(--ink);
        line-height: 1;
        transition: color .3s;
    }

    .timer-value.danger { color: #ef4444; animation: timer-pulse 1s ease-in-out infinite; }

    @keyframes timer-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .6; }
    }

    /* ── Body split ────────────────────────────── */
    .exam-body {
        display: grid;
        grid-template-columns: 1fr 280px;
        flex: 1;
        overflow: hidden;
    }

    /* ── Question area ─────────────────────────── */
    .question-area {
        overflow-y: auto;
        padding: 2rem 2.5rem;
        background: var(--surface);
        scrollbar-width: thin;
        scrollbar-color: rgba(0,0,0,.1) transparent;
    }

    .question-area::-webkit-scrollbar { width: 4px; }
    .question-area::-webkit-scrollbar-thumb { background: rgba(0,0,0,.1); border-radius: 4px; }

    .question-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--card-border);
    }

    .question-number {
        font-family: var(--font-display);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted);
    }

    .question-number strong {
        color: var(--brand);
        font-size: 1.1rem;
        font-weight: 800;
        vertical-align: middle;
    }

    .section-badge {
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #0891b2;
        background: rgba(6,182,212,.1);
        border: 1px solid rgba(6,182,212,.2);
        border-radius: 100px;
        padding: .22rem .7rem;
    }

    .question-text {
        font-size: 1rem;
        font-weight: 500;
        color: var(--ink);
        line-height: 1.75;
        margin-bottom: 2rem;
    }

    /* Option labels */
    .option-label {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        padding: .9rem 1.1rem;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--card-border);
        background: var(--card-bg);
        cursor: pointer;
        transition: border-color var(--transition-fast), background var(--transition-fast), box-shadow var(--transition-fast);
        user-select: none;
        margin-bottom: .65rem;
    }

    .option-label:hover {
        border-color: rgba(22,163,74,.3);
        background: var(--brand-pale);
    }

    .option-label.selected {
        border-color: var(--brand);
        background: var(--brand-pale);
        box-shadow: 0 0 0 3px rgba(22,163,74,.1);
    }

    .option-label input[type="radio"] { display: none; }

    .option-key {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1.5px solid var(--card-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 700;
        color: var(--muted);
        flex-shrink: 0;
        font-family: var(--font-display);
        transition: border-color var(--transition-fast), background var(--transition-fast), color var(--transition-fast);
    }

    .option-label.selected .option-key {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .option-text {
        font-size: .88rem;
        color: var(--ink-2);
        line-height: 1.55;
        padding-top: .15rem;
    }

    /* Action row */
    .question-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1.5rem;
        border-top: 1px solid var(--card-border);
        margin-top: 1rem;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .exam-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .78rem;
        font-weight: 700;
        padding: .55rem 1.1rem;
        border-radius: var(--radius-sm);
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: background var(--transition-fast), transform var(--transition-spring), border-color var(--transition-fast);
    }

    .exam-btn:hover:not(:disabled) { transform: translateY(-1px); }
    .exam-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    .exam-btn-primary  { background: var(--brand); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.3); }
    .exam-btn-primary:hover:not(:disabled) { background: var(--brand-dark); }

    .exam-btn-dark     { background: var(--ink); color: #fff; }
    .exam-btn-dark:hover:not(:disabled) { background: var(--ink-2); }

    .exam-btn-outline  { background: transparent; border-color: var(--card-border); color: var(--muted); }
    .exam-btn-outline:hover:not(:disabled) { border-color: rgba(107,114,128,.4); color: var(--ink); background: var(--surface-2); transform: none; }

    .exam-btn-purple   { background: #7c3aed; color: #fff; }
    .exam-btn-purple:hover:not(:disabled) { background: #6d28d9; }

    /* ── Sidebar palette ───────────────────────── */
    .exam-sidebar {
        background: var(--card-bg);
        border-left: 1px solid var(--card-border);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        scrollbar-width: thin;
    }

    .palette-header {
        padding: 1rem 1rem .75rem;
        border-bottom: 1px solid var(--card-border);
        font-family: var(--font-display);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted-light);
        flex-shrink: 0;
    }

    .palette-grid {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        padding: .85rem;
        flex: 1;
    }

    /* Signature: question palette buttons with status shapes */
    .palette-btn {
        width: 38px;
        height: 38px;
        font-size: .72rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 1.5px solid var(--card-border);
        border-radius: var(--radius-sm);
        background: var(--surface-2);
        color: var(--muted);
        font-family: var(--font-display);
        transition: all var(--transition-fast);
        position: relative;
    }

    .palette-btn:hover { border-color: var(--brand); color: var(--brand); }

    .palette-btn.is-current {
        border-color: var(--brand);
        background: var(--brand-pale);
        color: var(--brand);
        font-weight: 700;
    }

    /* Answered — full green */
    .palette-btn.is-answered {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
        border-radius: 8px;
    }

    /* Not answered (visited) — red */
    .palette-btn.is-not-answered {
        background: #ef4444;
        border-color: #ef4444;
        color: #fff;
        border-radius: 0 0 12px 12px;
    }

    /* Marked for review — purple */
    .palette-btn.is-review {
        background: #7c3aed;
        border-color: #7c3aed;
        color: #fff;
        border-radius: 50%;
    }

    /* Legend */
    .palette-legend {
        padding: .75rem;
        border-top: 1px solid var(--card-border);
        flex-shrink: 0;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .72rem;
        color: var(--muted);
        margin-bottom: .4rem;
    }

    .legend-dot {
        width: 18px;
        height: 18px;
        font-size: .6rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 4px;
    }

    /* Submit button */
    .submit-wrap {
        padding: .75rem;
        border-top: 1px solid var(--card-border);
        flex-shrink: 0;
    }

    .submit-btn {
        width: 100%;
        padding: .8rem;
        background: var(--brand);
        color: #fff;
        font-family: var(--font-display);
        font-size: .85rem;
        font-weight: 700;
        letter-spacing: .04em;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        box-shadow: 0 3px 10px rgba(22,163,74,.35);
        transition: background var(--transition-fast), transform var(--transition-spring);
    }

    .submit-btn:hover:not(:disabled) {
        background: var(--brand-dark);
        transform: translateY(-1px);
    }

    .submit-btn:disabled { opacity: .65; cursor: not-allowed; transform: none; }

    /* ── Result modal ─────────────────────────── */
    .result-overlay {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,.6);
        backdrop-filter: blur(10px);
        z-index: 9000;
        padding: 1rem;
    }

    .result-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        box-shadow: 0 24px 64px rgba(0,0,0,.25);
        padding: 2.5rem 2rem;
        max-width: 440px;
        width: 100%;
        text-align: center;
    }

    .result-icon-wrap {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: var(--brand-pale);
        border: 2px solid rgba(22,163,74,.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
    }

    .result-title {
        font-family: var(--font-display);
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -.03em;
        margin-bottom: .35rem;
    }

    .result-scores {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--card-border);
        margin: 1.5rem 0;
    }

    .result-score-cell {
        padding: 1rem;
        background: var(--surface-2);
    }

    .result-score-cell + .result-score-cell {
        border-left: 1px solid var(--card-border);
    }

    .result-score-label {
        font-size: .62rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted-light);
        margin-bottom: .35rem;
    }

    .result-score-value {
        font-family: var(--font-display);
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -.04em;
        line-height: 1;
    }

    .result-score-value.green { color: var(--brand); }

    .accuracy-bar {
        height: 10px;
        background: var(--surface-2);
        border-radius: 100px;
        overflow: hidden;
        margin-bottom: .35rem;
    }

    .accuracy-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--brand), var(--brand-mid));
        border-radius: 100px;
        transition: width 1s cubic-bezier(0.4,0,0.2,1);
    }

    .result-home-btn {
        display: block;
        width: 100%;
        padding: .9rem;
        background: var(--brand);
        color: #fff;
        font-family: var(--font-display);
        font-size: .9rem;
        font-weight: 700;
        border-radius: var(--radius-sm);
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(22,163,74,.35);
        transition: background var(--transition-fast);
        margin-top: 1.5rem;
    }

    .result-home-btn:hover { background: var(--brand-dark); color: #fff; }

    @media (max-width: 767px) {
        .exam-body { grid-template-columns: 1fr; }
        .exam-sidebar { display: none; }
        .question-area { padding: 1.25rem; }
    }
</style>

<div class="exam-wrap"
     x-data="{
         currentIdx: 0,
         timer: {{ $quiz->time_limit * 60 }},
         questions: {{ json_encode($questions) }},
         userAnswers: {},
         markedForReview: [],
         isSubmitting: false,
         showResult: false,
         results: { score: 0, total: 0 },
         init() {
             const interval = setInterval(() => {
                 if (this.timer > 0) {
                     this.timer--;
                 } else {
                     clearInterval(interval);
                     this.submitExam();
                 }
             }, 1000);
         },
         formatTime() {
             const min = Math.floor(this.timer / 60);
             const sec = this.timer % 60;
             return `${min}:${sec < 10 ? '0' : ''}${sec}`;
         },
         get current() { return this.questions[this.currentIdx]; },
         getStatus(q, index) {
             if (this.markedForReview.includes(index)) return 'is-review';
             if (this.userAnswers[q.id]) return 'is-answered';
             if (index < this.currentIdx) return 'is-not-answered';
             return '';
         },
         async submitExam() {
             if (!confirm('Submit the exam? You cannot change your answers after submission.')) return;
             this.isSubmitting = true;
             try {
                 const response = await fetch('{{ route('quizzes.submit', $quiz->slug) }}', {
                     method: 'POST',
                     headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ $csrfToken }}' },
                     body: JSON.stringify({ userAnswers: this.userAnswers }),
                 });
                 const data = await response.json();
                 if (response.ok) {
                     this.results = { score: data.score, total: data.total_questions };
                     this.showResult = true;
                 } else {
                     alert('Submission error: ' + (data.message || 'Please try again.'));
                 }
             } catch (err) {
                 alert('Network error. Please try again.');
             } finally {
                 this.isSubmitting = false;
             }
         }
     }">

    {{-- ── Top bar ──────────────────────────────────────── --}}
    <div class="exam-topbar">
        <div class="exam-candidate">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Guest') }}&background=dcfce7&color=16a34a&bold=true"
                 class="candidate-avatar"
                 alt="candidate">
            <div>
                <div class="candidate-name">{{ Auth::user()->name ?? 'Guest' }}</div>
                <span class="exam-title-badge">Candidate</span>
            </div>
        </div>

        <div class="exam-center-info">
            <div class="exam-name-chip">{{ $quiz->title }}</div>
            <div class="eyebrow" style="font-size:.6rem;">
                <span x-text="currentIdx + 1"></span> / {{ count($questions) }}
            </div>
        </div>

        <div class="exam-timer">
            <div class="timer-label">Time Left</div>
            <div class="timer-value" :class="timer < 300 ? 'danger' : ''" x-text="formatTime()"></div>
        </div>
    </div>

    {{-- ── Body ─────────────────────────────────────────── --}}
    <div class="exam-body">

        {{-- Question area --}}
        <div class="question-area">
            <div class="question-header">
                <div class="question-number">
                    Question <strong x-text="currentIdx + 1"></strong> of {{ count($questions) }}
                </div>
                <span class="section-badge">General Knowledge</span>
            </div>

            <div class="question-text" x-html="current.question_text"></div>

            <div>
                <template x-for="option in current.options" :key="option.key">
                    <label class="option-label"
                           :class="userAnswers[current.id] === option.key ? 'selected' : ''">
                        <input type="radio"
                               :name="'q'+current.id"
                               :value="option.key"
                               x-model="userAnswers[current.id]">
                        <span class="option-key" x-text="option.key"></span>
                        <span class="option-text" x-text="option.value"></span>
                    </label>
                </template>
            </div>

            <div class="question-actions">
                <div class="d-flex gap-2 flex-wrap">
                    <button class="exam-btn exam-btn-purple"
                            @click="if(!markedForReview.includes(currentIdx)) markedForReview.push(currentIdx); if(currentIdx < questions.length - 1) currentIdx++"
                            :disabled="markedForReview.includes(currentIdx)">
                        <i class="bi bi-bookmark-fill"></i>
                        Mark & Next
                    </button>
                    <button class="exam-btn exam-btn-outline"
                            @click="userAnswers[current.id] = null">
                        <i class="bi bi-x-circle"></i> Clear
                    </button>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="exam-btn exam-btn-dark"
                            @click="currentIdx--"
                            :disabled="currentIdx === 0">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <button class="exam-btn exam-btn-primary"
                            @click="currentIdx++"
                            :disabled="currentIdx === questions.length - 1">
                        Save & Next <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Palette sidebar --}}
        <div class="exam-sidebar">
            <div class="palette-header">Question Palette</div>

            <div class="palette-grid">
                <template x-for="(q, index) in questions" :key="index">
                    <button class="palette-btn"
                            @click="currentIdx = index"
                            :class="[
                                currentIdx === index ? 'is-current' : '',
                                getStatus(q, index)
                            ]"
                            x-text="index + 1">
                    </button>
                </template>
            </div>

            <div class="palette-legend">
                <div class="legend-item">
                    <span class="legend-dot" style="background:var(--brand); border-radius:4px; color:#fff;">✓</span>
                    Answered
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#ef4444; border-radius:0 0 8px 8px; color:#fff;">—</span>
                    Not answered
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:var(--surface-2); border:1.5px solid var(--card-border); border-radius:4px;"></span>
                    Not visited
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#7c3aed; border-radius:50%; color:#fff;">?</span>
                    Marked for review
                </div>
            </div>

            <div class="submit-wrap">
                <button class="submit-btn" @click="submitExam()" :disabled="isSubmitting">
                    <span class="spinner-border spinner-border-sm" x-show="isSubmitting"></span>
                    <i class="bi bi-check2-circle" x-show="!isSubmitting"></i>
                    <span x-text="isSubmitting ? 'Submitting…' : 'Submit Exam'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Result modal ─────────────────────────────────── --}}
    <template x-if="showResult">
        <div class="result-overlay"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="result-card"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="result-icon-wrap">🎉</div>

                <h2 class="result-title">Exam Complete!</h2>
                <p style="color:var(--muted); font-size:.875rem; margin-bottom:.5rem;">
                    You've completed all {{ count($questions) }} questions.
                </p>

                <div class="result-scores">
                    <div class="result-score-cell">
                        <div class="result-score-label">Your Score</div>
                        <div class="result-score-value green" x-text="results.score"></div>
                    </div>
                    <div class="result-score-cell">
                        <div class="result-score-label">Total</div>
                        <div class="result-score-value" x-text="results.total"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:.75rem; font-weight:600; color:var(--muted);">Accuracy</span>
                        <span style="font-family:var(--font-display); font-weight:800; color:var(--brand); font-size:.9rem;"
                              x-text="Math.round((results.score / results.total) * 100) + '%'"></span>
                    </div>
                    <div class="accuracy-bar">
                        <div class="accuracy-fill"
                             :style="'width:' + Math.round((results.score / results.total) * 100) + '%'"></div>
                    </div>
                </div>

                <a href="{{ route('quizzes.index') }}" class="result-home-btn">
                    Finish & Go Home
                </a>
            </div>
        </div>
    </template>

</div>

@endsection