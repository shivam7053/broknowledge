{{-- games.blade.php --}}
@extends('layouts.app')

@section('title', 'Game Zone')
@section('meta_description', 'Take a break in the BroKnowledge Game Zone. Play classic arcade games like Snake, Tetris, and Memory Flip directly in your browser.')

@section('content')

<style>
    /* ══════════════════════════════════════════════
       GAMES PAGE
    ══════════════════════════════════════════════ */

    .games-hero {
        padding: 4rem 0 2.5rem;
        text-align: center;
    }

    .games-title {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5vw, 3.8rem);
        font-weight: 800;
        letter-spacing: -.04em;
        line-height: 1.05;
        color: var(--ink);
        margin-bottom: .75rem;
    }

    .games-title .games-accent { color: #f59e0b; }

    .games-subtitle {
        font-size: 1rem;
        color: var(--muted);
        max-width: 440px;
        margin: 0 auto;
        line-height: 1.65;
    }

    /* ── Game Cards ────────────────────────────── */
    .game-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        border-radius: var(--radius-lg);
        padding: 2rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: box-shadow var(--transition), transform var(--transition-spring), border-color var(--transition-fast);
    }

    .game-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(245,158,11,.05) 0%, transparent 60%);
        opacity: 0;
        transition: opacity var(--transition);
        pointer-events: none;
    }

    .game-card:hover {
        box-shadow: 0 12px 32px rgba(245,158,11,.14), 0 0 0 1.5px rgba(245,158,11,.25);
        transform: translateY(-5px) scale(1.01);
        border-color: rgba(245,158,11,.3);
    }

    .game-card:hover::before { opacity: 1; }

    /* Signature: icon "bounces" on hover via CSS transform */
    .game-icon-wrap {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(245,158,11,.12), rgba(239,68,68,.08));
        border: 1px solid rgba(245,158,11,.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.25rem;
        transition: transform var(--transition-spring);
        position: relative;
        z-index: 1;
    }

    .game-card:hover .game-icon-wrap {
        transform: scale(1.15) rotate(-5deg);
    }

    .game-name {
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -.015em;
        margin-bottom: .5rem;
    }

    .game-desc {
        font-size: .8rem;
        color: var(--muted);
        line-height: 1.6;
        flex: 1;
        margin-bottom: 1.25rem;
    }

    .game-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: #fff;
        font-size: .8rem;
        font-weight: 700;
        padding: .6rem 1.4rem;
        border-radius: 100px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity var(--transition-fast), transform var(--transition-spring), box-shadow var(--transition-fast);
        box-shadow: 0 4px 12px rgba(245,158,11,.3);
    }

    .game-btn:hover {
        color: #fff;
        opacity: .92;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(245,158,11,.4);
    }

    .game-btn.disabled, .game-btn:disabled {
        background: var(--surface-2);
        color: var(--muted);
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    .game-coming-soon {
        position: absolute;
        top: .9rem;
        right: .9rem;
        font-size: .58rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--muted);
        background: var(--surface-2);
        border: 1px solid var(--card-border);
        border-radius: 100px;
        padding: .22rem .6rem;
    }

    /* ── Coming soon banner ───────────────────── */
    .more-coming {
        background: var(--card-bg);
        border: 1px dashed rgba(22,163,74,.25);
        border-radius: var(--radius);
        padding: 1.25rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .75rem;
        color: var(--muted);
        font-size: .82rem;
        font-weight: 500;
    }
</style>

<div class="container-fluid px-lg-5 py-2">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="games-hero reveal">
        <div class="eyebrow mb-3 mx-auto" style="width: fit-content;">
            <i class="bi bi-controller"></i> Game Zone
        </div>
        <h1 class="games-title">
            Take a break.<br>
            <span class="games-accent">Challenge your mind.</span>
        </h1>
        <p class="games-subtitle">Mini-games to keep your brain sharp between coding sessions.</p>
    </div>

    {{-- ── Games Grid ───────────────────────────────────── --}}
    <div class="section-divider mb-4 reveal">
        <p class="section-divider-label">Arcade Games</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">

        <div class="col reveal stagger-1">
            <div class="game-card">
                <div class="game-icon-wrap">🐍</div>
                <h3 class="game-name">Retro Snake</h3>
                <p class="game-desc">The classic arcade game. Eat the apples, grow as long as possible, don't bite yourself.</p>
                <a href="{{ route('games.snake') }}" class="game-btn">
                    <i class="bi bi-play-fill"></i> Play Now
                </a>
            </div>
        </div>

        <div class="col reveal stagger-2">
            <div class="game-card">
                <div class="game-icon-wrap">🧱</div>
                <h3 class="game-name">Tetris</h3>
                <p class="game-desc">Arrange falling blocks to clear lines and rack up points. How long can you last?</p>
                <a href="{{ route('games.tetris') }}" class="game-btn">
                    <i class="bi bi-play-fill"></i> Play Now
                </a>
            </div>
        </div>

        <div class="col reveal stagger-3">
            <div class="game-card">
                <div class="game-icon-wrap">🃏</div>
                <h3 class="game-name">Memory Flip</h3>
                <p class="game-desc">Test your memory by matching pairs of hidden cards before the clock beats you.</p>
                <a href="{{ route('games.card') }}" class="game-btn">
                    <i class="bi bi-play-fill"></i> Play Now
                </a>
            </div>
        </div>

        <div class="col reveal stagger-1">
            <div class="game-card">
                <div class="game-icon-wrap">🚀</div>
                <h3 class="game-name">Star Shooter</h3>
                <p class="game-desc">Pilot your ship, shoot falling stars, and dodge bombs in this fast-paced arcade classic.</p>
                <a href="{{ route('games.shooter') }}" class="game-btn">
                    <i class="bi bi-play-fill"></i> Play Now
                </a>
            </div>
        </div>

        <div class="col reveal stagger-2">
            <div class="game-card">
                <div class="game-icon-wrap">💣</div>
                <h3 class="game-name">BomberBlast</h3>
                <p class="game-desc">Strategically place bombs to clear paths, destroy enemies, and find power-ups.</p>
                <a href="{{ route('games.bomber') }}" class="game-btn">
                    <i class="bi bi-play-fill"></i> Play Now
                </a>
            </div>
        </div>

        {{-- Removed "2048 Puzzle" and "Tic Tac Toe" --}}
    </div>

    {{-- ── More coming ──────────────────────────────────── --}}
    <div class="more-coming reveal mb-5">
        <i class="bi bi-stars" style="color: var(--brand);"></i>
        More games are being added every week. Stay tuned!
    </div>

</div>

@endsection