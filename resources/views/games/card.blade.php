<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Card Flip — Brain Training Puzzle Game | BroKnowledge Games</title>
    <meta name="description" content="Test your memory with the Card Flip game. Match pairs of emojis as fast as you can. Multiple difficulty levels available. Train your brain for free.">
    <link rel="canonical" href="{{ route('games.card') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Memory Card Flip — Brain Training Game">
    <meta property="og:description" content="Challenge your recall! Match all pairs in the fewest moves possible.">
    <meta property="og:url" content="{{ route('games.card') }}">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Game Zone", "item": "{{ route('games') }}" },
            { "@@type": "ListItem", "position": 3, "name": "Memory", "item": "{{ route('games.card') }}" }
        ]
    }
    </script>

    <style>
        /* ── Design tokens ──────────────────────────────────────────
           Palette:  Deep ink #0F0E17  |  Off-white #FFFCF2
                     Coral  #FF6B6B    |  Teal  #2EC4B6
                     Gold   #F4C430    |  Slate #A7A9BE
           Type:     Display — "Playfair Display" (serif, card symbols)
                     Body    — "DM Sans" (clean, readable)
           Signature: Cards flip with a physical 3-D perspective tilt,
                      and matched pairs "sink" with a tactile scale-down.
        ─────────────────────────────────────────────────────────── */

        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:    #0F0E17;
            --cream:  #FFFCF2;
            --coral:  #FF6B6B;
            --teal:   #2EC4B6;
            --gold:   #F4C430;
            --slate:  #A7A9BE;
            --card-back: #1A1A2E;
            --card-border: #2a2a4a;
        }

        body {
            background: var(--ink);
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
            overflow-x: hidden;
        }

        /* ── Header ─────────────────────────────────────────────── */
        header {
            text-align: center;
            margin-bottom: 2rem;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.2rem);
            letter-spacing: -0.02em;
            color: var(--cream);
            line-height: 1;
        }

        h1 span {
            color: var(--coral);
        }

        .subtitle {
            margin-top: 0.4rem;
            font-size: 0.875rem;
            color: var(--slate);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* ── Score bar ──────────────────────────────────────────── */
        .scorebar {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.75rem;
            align-items: center;
        }

        .stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .stat-label {
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--slate);
        }

        .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            line-height: 1;
            color: var(--cream);
            transition: color 0.3s;
        }

        .stat-value.flash { color: var(--gold); }

        /* ── Grid ───────────────────────────────────────────────── */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
            max-width: 480px;
            width: 100%;
            perspective: 1000px;
        }

        /* ── Card ───────────────────────────────────────────────── */
        .card {
            aspect-ratio: 3/4;
            cursor: pointer;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 10px;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .card:hover:not(.flipped):not(.matched) .card-back {
            background: #252545;
            border-color: var(--teal);
        }

        .card.flipped,
        .card.matched {
            transform: rotateY(180deg);
        }

        .card.matched {
            transform: rotateY(180deg) scale(0.92);
            pointer-events: none;
            transition: transform 0.3s ease;
        }

        .card.shake {
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%,100% { transform: rotateY(180deg) translateX(0); }
            25%      { transform: rotateY(180deg) translateX(-5px); }
            75%      { transform: rotateY(180deg) translateX(5px); }
        }

        .card-face {
            position: absolute;
            inset: 0;
            border-radius: 10px;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Back of card */
        .card-back {
            background: var(--card-back);
            border: 1.5px solid var(--card-border);
            transition: background 0.2s, border-color 0.2s;
        }

        .card-back::before {
            content: '';
            width: 60%;
            height: 60%;
            border: 1.5px solid var(--card-border);
            border-radius: 6px;
        }

        /* Front of card */
        .card-front {
            background: #1E1E38;
            border: 1.5px solid #3a3a6a;
            transform: rotateY(180deg);
            font-size: clamp(1.6rem, 5vw, 2.4rem);
            flex-direction: column;
            gap: 4px;
        }

        .card.matched .card-front {
            border-color: var(--teal);
            background: #162530;
        }

        /* ── Controls ───────────────────────────────────────────── */
        .controls {
            margin-top: 1.75rem;
            display: flex;
            gap: 1rem;
        }

        button {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.65rem 1.4rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
        }

        button:active { transform: scale(0.97); }

        .btn-primary {
            background: var(--coral);
            color: #fff;
        }

        .btn-secondary {
            background: transparent;
            color: var(--slate);
            border: 1.5px solid var(--card-border);
        }

        button:hover { opacity: 0.85; }

        /* ── Win overlay ─────────────────────────────────────────── */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,14,23,0.88);
            align-items: center;
            justify-content: center;
            z-index: 100;
            backdrop-filter: blur(4px);
        }

        .overlay.show { display: flex; }

        .win-box {
            text-align: center;
            padding: 2.5rem 3rem;
            background: #1A1A2E;
            border: 1.5px solid var(--teal);
            border-radius: 16px;
            max-width: 340px;
            animation: popIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
        }

        @keyframes popIn {
            from { transform: scale(0.7); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .win-box h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            color: var(--gold);
            margin-bottom: 0.4rem;
        }

        .win-box p {
            color: var(--slate);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        /* ── Difficulty select ───────────────────────────────────── */
        .diff-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .diff-btn {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.4rem 0.9rem;
            border-radius: 5px;
            border: 1.5px solid var(--card-border);
            background: transparent;
            color: var(--slate);
            cursor: pointer;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }

        .diff-btn.active,
        .diff-btn:hover {
            background: var(--teal);
            color: var(--ink);
            border-color: var(--teal);
            opacity: 1;
        }

        @media (max-width: 400px) {
            .grid { gap: 0.5rem; }
            .scorebar { gap: 1.2rem; }
        }
    </style>
</head>

<body>

<header>
    <h1>Mem<span>o</span>ry</h1>
    <p class="subtitle">Card Flip Game</p>
</header>

<div class="scorebar">
    <div class="stat">
        <span class="stat-label">Moves</span>
        <span class="stat-value" id="moves">0</span>
    </div>
    <div class="stat">
        <span class="stat-label">Pairs</span>
        <span class="stat-value" id="pairs">0</span>
    </div>
    <div class="stat">
        <span class="stat-label">Best</span>
        <span class="stat-value" id="best">—</span>
    </div>
</div>

<div class="diff-row">
    <button class="diff-btn active" data-cols="4" data-pairs="8">Easy</button>
    <button class="diff-btn" data-cols="4" data-pairs="12">Medium</button>
    <button class="diff-btn" data-cols="5" data-pairs="15">Hard</button>
</div>

<div class="grid" id="grid"></div>

<div class="controls">
    <button class="btn-primary" id="newGame">New Game</button>
</div>

<!-- Win overlay -->
<div class="overlay" id="overlay">
    <div class="win-box">
        <h2>Well done!</h2>
        <p id="winMsg">You matched all pairs.</p>
        <button class="btn-primary" id="playAgain">Play Again</button>
    </div>
</div>

<script>
const EMOJI_POOL = [
    '🦊','🐬','🦋','🌸','🎸','🍄','🪐','🐉',
    '🦚','🌊','🎯','🔮','🪄','🦁','🍀','🌙',
    '🐙','🎪','🏔️','🦜','🌺','🎭','🦩','🐧',
    '🌈','🍁','🦋','🎨'
];

let state = {
    cards: [],
    flipped: [],
    matched: 0,
    moves: 0,
    pairs: 8,
    cols: 4,
    locked: false,
    best: {}
};

/* ── Build deck ─────────────────────────────────────────── */
function buildDeck(pairs) {
    const symbols = EMOJI_POOL.slice(0, pairs);
    return [...symbols, ...symbols]
        .sort(() => Math.random() - 0.5)
        .map((sym, i) => ({ id: i, sym, flipped: false, matched: false }));
}

/* ── Render ──────────────────────────────────────────────── */
function render() {
    const grid = document.getElementById('grid');
    grid.style.gridTemplateColumns = `repeat(${state.cols}, 1fr)`;
    grid.innerHTML = '';

    const totalCards = state.pairs * 2;
    // Adjust max-width based on cols
    grid.style.maxWidth = state.cols === 5 ? '540px' : '480px';

    state.cards.forEach((card, idx) => {
        const el = document.createElement('div');
        el.className = 'card' +
            (card.flipped ? ' flipped' : '') +
            (card.matched ? ' matched' : '');
        el.innerHTML = `
            <div class="card-face card-back"></div>
            <div class="card-face card-front">${card.sym}</div>
        `;
        el.addEventListener('click', () => flipCard(idx));
        grid.appendChild(el);
    });

    document.getElementById('moves').textContent = state.moves;
    document.getElementById('pairs').textContent = state.matched;
    const key = state.pairs;
    document.getElementById('best').textContent =
        state.best[key] != null ? state.best[key] : '—';
}

/* ── Flip logic ──────────────────────────────────────────── */
function flipCard(idx) {
    const card = state.cards[idx];
    if (state.locked || card.flipped || card.matched) return;

    card.flipped = true;
    state.flipped.push(idx);
    render();

    if (state.flipped.length === 2) {
        state.moves++;
        state.locked = true;

        const [a, b] = state.flipped;
        if (state.cards[a].sym === state.cards[b].sym) {
            state.cards[a].matched = true;
            state.cards[b].matched = true;
            state.matched++;
            state.flipped = [];
            state.locked = false;
            render();
            flashStat('pairs');
            flashStat('moves');
            if (state.matched === state.pairs) setTimeout(win, 400);
        } else {
            flashStat('moves');
            setTimeout(() => {
                // Shake effect
                const cards = document.querySelectorAll('.card');
                if (cards[a]) cards[a].classList.add('shake');
                if (cards[b]) cards[b].classList.add('shake');
                setTimeout(() => {
                    state.cards[a].flipped = false;
                    state.cards[b].flipped = false;
                    state.flipped = [];
                    state.locked = false;
                    render();
                }, 420);
            }, 700);
        }
    }
}

function flashStat(id) {
    const el = document.getElementById(id);
    el.classList.remove('flash');
    void el.offsetWidth;
    el.classList.add('flash');
    setTimeout(() => el.classList.remove('flash'), 500);
}

/* ── Win ──────────────────────────────────────────────────── */
function win() {
    const key = state.pairs;
    if (state.best[key] == null || state.moves < state.best[key]) {
        state.best[key] = state.moves;
        try { localStorage.setItem('memoBest', JSON.stringify(state.best)); } catch(e){}
    }
    document.getElementById('winMsg').textContent =
        `${state.pairs} pairs in ${state.moves} moves. Best: ${state.best[key]}`;
    document.getElementById('overlay').classList.add('show');
    render();
}

/* ── New game ─────────────────────────────────────────────── */
function newGame() {
    document.getElementById('overlay').classList.remove('show');
    state.cards   = buildDeck(state.pairs);
    state.flipped = [];
    state.matched = 0;
    state.moves   = 0;
    state.locked  = false;
    render();
}

/* ── Difficulty buttons ──────────────────────────────────── */
document.querySelectorAll('.diff-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.diff-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        state.pairs = parseInt(btn.dataset.pairs);
        state.cols  = parseInt(btn.dataset.cols);
        newGame();
    });
});

document.getElementById('newGame').addEventListener('click', newGame);
document.getElementById('playAgain').addEventListener('click', newGame);

/* ── Load best scores ─────────────────────────────────────── */
try {
    const saved = localStorage.getItem('memoBest');
    if (saved) state.best = JSON.parse(saved);
} catch(e) {}

/* ── Init ─────────────────────────────────────────────────── */
newGame();
</script>

</body>
</html>