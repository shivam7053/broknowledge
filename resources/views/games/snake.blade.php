<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Share+Tech+Mono&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #060A06;
            --panel:    #0B100B;
            --border:   #1A2E1A;
            --dim:      #2A4A2A;
            --green:    #39FF14;
            --green2:   #22CC08;
            --apple:    #FF3B30;
            --gold:     #FFD60A;
            --text:     #C8ECC8;
            --muted:    #4A6E4A;
            --cell:     22px;
            --cols:     25;
            --rows:     22;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Share Tech Mono', monospace;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            letter-spacing: 0.3em;
            color: var(--green);
            text-shadow: 0 0 18px rgba(57,255,20,0.45);
            margin-bottom: 0.9rem;
        }

        /* ── Layout ──────────────────────────────────────────────── */
        .layout {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        /* ── Canvas ──────────────────────────────────────────────── */
        .board-wrap {
            position: relative;
            line-height: 0;
        }

        canvas#game {
            display: block;
            border: 1px solid var(--border);
            background: var(--panel);
        }

        /* ── Side panel ──────────────────────────────────────────── */
        .panel {
            width: 110px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .pbox {
            border: 1px solid var(--border);
            background: var(--panel);
            padding: 0.7rem;
        }

        .plabel {
            font-size: 0.58rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.3rem;
        }

        .pval {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.9rem;
            letter-spacing: 0.04em;
            color: var(--text);
            line-height: 1;
        }

        .pval.hi { color: var(--gold); }

        .key-hint {
            font-size: 0.58rem;
            color: var(--muted);
            line-height: 2;
        }

        /* ── Overlay ─────────────────────────────────────────────── */
        #overlay {
            position: absolute;
            inset: 0;
            background: rgba(6,10,6,0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            backdrop-filter: blur(3px);
        }

        #overlay h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.4rem;
            letter-spacing: 0.2em;
            color: var(--green);
            text-shadow: 0 0 14px rgba(57,255,20,0.5);
        }

        #overlay p {
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            color: var(--muted);
            text-align: center;
            line-height: 1.8;
        }

        #overlay button {
            margin-top: 0.6rem;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.55rem 1.5rem;
            background: transparent;
            border: 1px solid var(--dim);
            color: var(--green);
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        #overlay button:hover {
            border-color: var(--green);
            box-shadow: 0 0 10px rgba(57,255,20,0.3);
        }

        /* ── Speed selector ──────────────────────────────────────── */
        .speed-row {
            display: flex;
            gap: 0.4rem;
            margin-bottom: 0.6rem;
        }

        .spd-btn {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.1em;
            padding: 0.3rem 0.6rem;
            background: transparent;
            border: 1px solid var(--dim);
            color: var(--muted);
            cursor: pointer;
            transition: all 0.15s;
        }

        .spd-btn.active,
        .spd-btn:hover {
            border-color: var(--green);
            color: var(--green);
            box-shadow: 0 0 6px rgba(57,255,20,0.25);
        }

        /* ── Touch controls ──────────────────────────────────────── */
        .touch-pad {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.8rem;
        }

        .touch-row { display: flex; gap: 0.35rem; }

        .tbtn {
            font-size: 1.3rem;
            width: 56px;
            height: 56px;
            background: var(--panel);
            border: 1px solid var(--border);
            color: var(--green);
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: background 0.1s;
        }

        .tbtn:active { background: var(--dim); }

        @media (max-width: 600px) {
            :root { --cell: 16px; --cols: 20; --rows: 18; }
            .panel { width: 80px; }
            .pval  { font-size: 1.4rem; }
            h1     { font-size: 2.2rem; }
            .touch-pad { display: flex; }
        }

        @media (max-width: 400px) {
            :root { --cell: 14px; --cols: 18; --rows: 16; }
        }
    </style>
</head>
<body>

<h1>Snake</h1>

<div class="layout">

    <div class="board-wrap">
        <canvas id="game"></canvas>
        <div id="overlay">
            <h2 id="ovTitle">Snake</h2>
            <p id="ovMsg">Eat. Grow. Don't crash.</p>
            <div class="speed-row">
                <button class="spd-btn" data-spd="180">Slow</button>
                <button class="spd-btn active" data-spd="110">Normal</button>
                <button class="spd-btn" data-spd="60">Fast</button>
            </div>
            <button id="ovBtn">Start</button>
        </div>
    </div>

    <div class="panel">
        <div class="pbox">
            <div class="plabel">Score</div>
            <div class="pval" id="score">0</div>
        </div>
        <div class="pbox">
            <div class="plabel">Best</div>
            <div class="pval hi" id="best">0</div>
        </div>
        <div class="pbox">
            <div class="plabel">Length</div>
            <div class="pval" id="length">1</div>
        </div>
        <div class="pbox key-hint">
            ↑↓←→ Move<br>
            WASD  Move<br>
            P     Pause
        </div>
    </div>

</div>

<div class="touch-pad">
    <div class="touch-row">
        <button class="tbtn" id="tc-up">↑</button>
    </div>
    <div class="touch-row">
        <button class="tbtn" id="tc-left">←</button>
        <button class="tbtn" id="tc-down">↓</button>
        <button class="tbtn" id="tc-right">→</button>
    </div>
</div>

<script>
// ── Config ───────────────────────────────────────────────────────
const cs   = document.documentElement;
const CELL = parseInt(getComputedStyle(cs).getPropertyValue('--cell'))  || 22;
const COLS = parseInt(getComputedStyle(cs).getPropertyValue('--cols'))  || 25;
const ROWS = parseInt(getComputedStyle(cs).getPropertyValue('--rows'))  || 22;

const canvas = document.getElementById('game');
canvas.width  = COLS * CELL;
canvas.height = ROWS * CELL;
const ctx = canvas.getContext('2d');

// Colours
const C = {
    bg:       '#0B100B',
    grid:     '#0F160F',
    snakeH:   '#39FF14',
    snakeB:   '#22CC08',
    snakeDim: '#166610',
    apple:    '#FF3B30',
    appleGlow:'rgba(255,59,48,0.35)',
    gold:     '#FFD60A',
    text:     '#C8ECC8',
    muted:    '#4A6E4A',
};

// ── State ─────────────────────────────────────────────────────────
let snake, dir, nextDir, apple, score, best, running, paused, loopId;
let interval = 110;  // ms per tick
let tick = 0;

function initGame() {
    const startX = Math.floor(COLS / 2);
    const startY = Math.floor(ROWS / 2);
    snake   = [{x: startX, y: startY}, {x: startX-1, y: startY}, {x: startX-2, y: startY}];
    dir     = {x: 1, y: 0};
    nextDir = {x: 1, y: 0};
    score   = 0;
    tick    = 0;
    best    = loadBest();
    placeApple();
    updateUI();
}

// ── Apple ─────────────────────────────────────────────────────────
function placeApple() {
    let pos;
    do {
        pos = { x: Math.floor(Math.random() * COLS),
                y: Math.floor(Math.random() * ROWS) };
    } while (snake.some(s => s.x === pos.x && s.y === pos.y));
    apple = pos;
}

// ── Game step ─────────────────────────────────────────────────────
function step() {
    dir = nextDir;
    const head = { x: snake[0].x + dir.x, y: snake[0].y + dir.y };

    // Wall collision
    if (head.x < 0 || head.x >= COLS || head.y < 0 || head.y >= ROWS) {
        return endGame();
    }
    // Self collision
    if (snake.some(s => s.x === head.x && s.y === head.y)) {
        return endGame();
    }

    snake.unshift(head);
    tick++;

    if (head.x === apple.x && head.y === apple.y) {
        score += 10;
        updateUI();
        placeApple();
    } else {
        snake.pop();
    }
}

// ── Draw ──────────────────────────────────────────────────────────
function draw() {
    // Background
    ctx.fillStyle = C.bg;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Subtle grid
    ctx.strokeStyle = C.grid;
    ctx.lineWidth = 0.5;
    for (let c = 0; c <= COLS; c++) {
        ctx.beginPath(); ctx.moveTo(c*CELL,0); ctx.lineTo(c*CELL,canvas.height); ctx.stroke();
    }
    for (let r = 0; r <= ROWS; r++) {
        ctx.beginPath(); ctx.moveTo(0,r*CELL); ctx.lineTo(canvas.width,r*CELL); ctx.stroke();
    }

    // Apple glow
    ctx.shadowColor  = C.appleGlow;
    ctx.shadowBlur   = 14;
    ctx.fillStyle    = C.apple;
    const ax = apple.x * CELL + CELL/2;
    const ay = apple.y * CELL + CELL/2;
    ctx.beginPath();
    ctx.arc(ax, ay, CELL/2 - 2, 0, Math.PI*2);
    ctx.fill();
    // Apple stem
    ctx.shadowBlur = 0;
    ctx.strokeStyle = '#8B3A3A';
    ctx.lineWidth = 1.5;
    ctx.beginPath();
    ctx.moveTo(ax, ay - CELL/2 + 3);
    ctx.lineTo(ax + 3, ay - CELL/2 - 1);
    ctx.stroke();

    // Snake
    ctx.shadowBlur = 0;
    snake.forEach((seg, i) => {
        const t = i / snake.length;
        // Gradient from bright head to dim tail
        if (i === 0) {
            ctx.fillStyle = C.snakeH;
            ctx.shadowColor = 'rgba(57,255,20,0.5)';
            ctx.shadowBlur  = 8;
        } else {
            // Interpolate green → dim
            const r = Math.round(lerp(0x22, 0x16, Math.min(t*1.4,1)));
            const g = Math.round(lerp(0xCC, 0x66, Math.min(t*1.4,1)));
            const b = Math.round(lerp(0x08, 0x10, Math.min(t*1.4,1)));
            ctx.fillStyle  = `rgb(${r},${g},${b})`;
            ctx.shadowBlur = 0;
        }

        const pad = i === 0 ? 1 : 2;
        const radius = i === 0 ? 5 : 3;
        roundRect(ctx,
            seg.x * CELL + pad,
            seg.y * CELL + pad,
            CELL - pad*2, CELL - pad*2,
            radius);
        ctx.fill();

        // Eyes on head
        if (i === 0) {
            ctx.shadowBlur = 0;
            ctx.fillStyle = '#000';
            const [e1, e2] = eyePositions(seg, dir);
            ctx.beginPath(); ctx.arc(e1.x, e1.y, 2, 0, Math.PI*2); ctx.fill();
            ctx.beginPath(); ctx.arc(e2.x, e2.y, 2, 0, Math.PI*2); ctx.fill();
            ctx.fillStyle = '#fff';
            ctx.beginPath(); ctx.arc(e1.x-0.5, e1.y-0.5, 0.8, 0, Math.PI*2); ctx.fill();
            ctx.beginPath(); ctx.arc(e2.x-0.5, e2.y-0.5, 0.8, 0, Math.PI*2); ctx.fill();
        }
    });
    ctx.shadowBlur = 0;
}

function lerp(a, b, t) { return a + (b - a) * t; }

function roundRect(c, x, y, w, h, r) {
    c.beginPath();
    c.moveTo(x+r, y);
    c.lineTo(x+w-r, y); c.arcTo(x+w,y, x+w,y+r, r);
    c.lineTo(x+w, y+h-r); c.arcTo(x+w,y+h, x+w-r,y+h, r);
    c.lineTo(x+r, y+h); c.arcTo(x,y+h, x,y+h-r, r);
    c.lineTo(x, y+r); c.arcTo(x,y, x+r,y, r);
    c.closePath();
}

function eyePositions(seg, d) {
    const cx = seg.x * CELL + CELL/2;
    const cy = seg.y * CELL + CELL/2;
    const off = CELL * 0.22;
    if (d.x === 1)  return [{x:cx+4,y:cy-off},{x:cx+4,y:cy+off}];
    if (d.x === -1) return [{x:cx-4,y:cy-off},{x:cx-4,y:cy+off}];
    if (d.y === -1) return [{x:cx-off,y:cy-4},{x:cx+off,y:cy-4}];
                    return [{x:cx-off,y:cy+4},{x:cx+off,y:cy+4}];
}

// ── Loop ──────────────────────────────────────────────────────────
let lastStep = 0;
function loop(ts) {
    if (!running || paused) return;
    if (ts - lastStep >= interval) {
        step();
        lastStep = ts;
    }
    draw();
    loopId = requestAnimationFrame(loop);
}

function startGame() {
    if (loopId) cancelAnimationFrame(loopId);
    initGame();
    hideOverlay();
    running = true;
    paused  = false;
    lastStep = 0;
    loopId = requestAnimationFrame(loop);
}

function endGame() {
    running = false;
    cancelAnimationFrame(loopId);
    const b = Math.max(score, best);
    saveBest(b);
    document.getElementById('best').textContent = b;
    showOverlay('Game Over', `Score: ${score}<br>Best: ${b}`, 'Try Again');
}

function togglePause() {
    if (!running) return;
    paused = !paused;
    if (paused) {
        showOverlay('Paused', 'Press P to resume', 'Resume');
    } else {
        hideOverlay();
        lastStep = 0;
        loopId = requestAnimationFrame(loop);
    }
}

// ── UI ────────────────────────────────────────────────────────────
function updateUI() {
    document.getElementById('score').textContent  = score;
    document.getElementById('length').textContent = snake.length;
    document.getElementById('best').textContent   = Math.max(score, loadBest());
}

function showOverlay(title, msg, btn) {
    document.getElementById('ovTitle').textContent  = title;
    document.getElementById('ovMsg').innerHTML      = msg;
    document.getElementById('ovBtn').textContent    = btn;
    document.getElementById('overlay').style.display = 'flex';
}

function hideOverlay() {
    document.getElementById('overlay').style.display = 'none';
}

// ── Persistence ───────────────────────────────────────────────────
function loadBest() { try { return parseInt(localStorage.getItem('snakeBest'))||0; } catch{return 0;} }
function saveBest(v){ try { localStorage.setItem('snakeBest', v); } catch{} }

// ── Input ─────────────────────────────────────────────────────────
const DIRS = {
    ArrowUp:    {x:0,y:-1}, ArrowDown:  {x:0,y:1},
    ArrowLeft:  {x:-1,y:0}, ArrowRight: {x:1,y:0},
    KeyW: {x:0,y:-1}, KeyS: {x:0,y:1},
    KeyA: {x:-1,y:0}, KeyD: {x:1,y:0},
};

document.addEventListener('keydown', e => {
    const d = DIRS[e.code];
    if (d) {
        e.preventDefault();
        // Prevent reversing
        if (d.x !== -dir.x || d.y !== -dir.y) nextDir = d;
        return;
    }
    if (e.code === 'KeyP') togglePause();
});

// Touch controls
[['tc-up',{x:0,y:-1}],['tc-down',{x:0,y:1}],
 ['tc-left',{x:-1,y:0}],['tc-right',{x:1,y:0}]].forEach(([id,d])=>{
    const el = document.getElementById(id);
    if (el) el.addEventListener('touchstart', e=>{
        e.preventDefault();
        if (running && !paused && (d.x !== -dir.x || d.y !== -dir.y)) nextDir = d;
    },{passive:false});
});

// ── Speed buttons ─────────────────────────────────────────────────
document.querySelectorAll('.spd-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.spd-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        interval = parseInt(btn.dataset.spd);
    });
});

// ── Overlay button ────────────────────────────────────────────────
document.getElementById('ovBtn').addEventListener('click', () => {
    if (paused) togglePause();
    else startGame();
});

// ── Init display ──────────────────────────────────────────────────
document.getElementById('best').textContent = loadBest();

// Draw static board on load
ctx.fillStyle = C.bg; ctx.fillRect(0,0,canvas.width,canvas.height);
</script>

</body>
</html>