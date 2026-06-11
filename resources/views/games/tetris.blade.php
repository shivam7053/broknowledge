<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetris</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Bebas+Neue&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #08090F;
            --panel:     #0E1018;
            --border:    #1E2133;
            --dim:       #2E3355;
            --text:      #C8CFEE;
            --muted:     #555E88;

            /* Piece colours — saturated neons with matching glow */
            --c-I: #00F5FF;
            --c-O: #FFE600;
            --c-T: #CC44FF;
            --c-S: #39FF14;
            --c-Z: #FF2D55;
            --c-J: #FF8C00;
            --c-L: #0080FF;

            --cell: 30px;   /* board cell size */
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
            gap: 0;
        }

        /* ── Title ───────────────────────────────────────────────── */
        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            letter-spacing: 0.25em;
            color: var(--text);
            margin-bottom: 1rem;
            text-align: center;
        }

        /* ── Layout: sidebar + board + sidebar ───────────────────── */
        .layout {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        /* ── Board ────────────────────────────────────────────────── */
        .board-wrap {
            position: relative;
        }

        canvas#board {
            display: block;
            border: 1px solid var(--border);
            background: var(--panel);
            image-rendering: pixelated;
        }

        /* Grid overlay drawn on canvas — pure canvas approach */

        /* ── Side panels ─────────────────────────────────────────── */
        .panel {
            width: 110px;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .panel-box {
            border: 1px solid var(--border);
            background: var(--panel);
            padding: 0.75rem;
        }

        .panel-label {
            font-size: 0.6rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.4rem;
        }

        .panel-value {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 0.05em;
            color: var(--text);
            line-height: 1;
        }

        canvas#next {
            display: block;
            background: transparent;
            margin-top: 0.3rem;
        }

        /* ── Overlay (start / pause / game-over) ──────────────────── */
        #overlay {
            position: absolute;
            inset: 0;
            background: rgba(8,9,15,0.82);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            backdrop-filter: blur(2px);
        }

        #overlay h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            letter-spacing: 0.2em;
            color: var(--text);
        }

        #overlay p {
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            color: var(--muted);
            text-align: center;
        }

        #overlay button {
            margin-top: 0.5rem;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.55rem 1.4rem;
            background: transparent;
            border: 1px solid var(--dim);
            color: var(--text);
            cursor: pointer;
            transition: border-color 0.15s, color 0.15s;
        }

        #overlay button:hover {
            border-color: var(--c-I);
            color: var(--c-I);
        }

        /* ── Controls hint ───────────────────────────────────────── */
        .hint {
            font-size: 0.6rem;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-align: center;
            margin-top: 0.75rem;
        }

        /* ── Mobile touch buttons ────────────────────────────────── */
        .touch-controls {
            display: none;
            gap: 0.4rem;
            margin-top: 0.75rem;
            flex-direction: column;
            align-items: center;
        }

        .touch-row {
            display: flex;
            gap: 0.4rem;
        }

        .touch-btn {
            font-family: 'Share Tech Mono', monospace;
            font-size: 1.1rem;
            width: 52px;
            height: 52px;
            background: var(--panel);
            border: 1px solid var(--border);
            color: var(--text);
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: background 0.1s;
        }

        .touch-btn:active { background: var(--dim); }

        @media (max-width: 560px) {
            :root { --cell: 24px; }
            .panel { width: 82px; }
            .panel-value { font-size: 1.4rem; }
            h1 { font-size: 2.2rem; }
            .touch-controls { display: flex; }
        }

        @media (max-width: 380px) {
            :root { --cell: 20px; }
            .layout { gap: 0.5rem; }
            .panel { width: 68px; }
        }
    </style>
</head>
<body>

<h1>Tetris</h1>

<div class="layout">

    <!-- Left panel -->
    <div class="panel">
        <div class="panel-box">
            <div class="panel-label">Score</div>
            <div class="panel-value" id="score">0</div>
        </div>
        <div class="panel-box">
            <div class="panel-label">Level</div>
            <div class="panel-value" id="level">1</div>
        </div>
        <div class="panel-box">
            <div class="panel-label">Lines</div>
            <div class="panel-value" id="lines">0</div>
        </div>
        <div class="panel-box">
            <div class="panel-label">Best</div>
            <div class="panel-value" id="best">0</div>
        </div>
    </div>

    <!-- Board -->
    <div class="board-wrap">
        <canvas id="board"></canvas>
        <div id="overlay">
            <h2 id="overlayTitle">Tetris</h2>
            <p id="overlayMsg">Stack blocks.<br>Clear lines. Survive.</p>
            <button id="overlayBtn">Start</button>
        </div>
    </div>

    <!-- Right panel -->
    <div class="panel">
        <div class="panel-box">
            <div class="panel-label">Next</div>
            <canvas id="next" width="80" height="80"></canvas>
        </div>
        <div class="panel-box">
            <div class="panel-label">Hold</div>
            <canvas id="hold" width="80" height="80"></canvas>
        </div>
        <div class="panel-box" style="font-size:0.6rem; color:var(--muted); line-height:1.8;">
            <div class="panel-label">Keys</div>
            ← → Move<br>
            ↑ Rotate<br>
            ↓ Soft drop<br>
            Space Hard drop<br>
            C Hold<br>
            P Pause
        </div>
    </div>

</div>

<div class="touch-controls">
    <div class="touch-row">
        <button class="touch-btn" id="tc-hold">C</button>
        <button class="touch-btn" id="tc-up">↑</button>
        <button class="touch-btn" id="tc-hard">⇩</button>
    </div>
    <div class="touch-row">
        <button class="touch-btn" id="tc-left">←</button>
        <button class="touch-btn" id="tc-down">↓</button>
        <button class="touch-btn" id="tc-right">→</button>
    </div>
</div>

<script>
// ── Constants ───────────────────────────────────────────────────
const COLS = 10, ROWS = 20;
const CELL = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--cell')) || 30;

const boardCanvas = document.getElementById('board');
boardCanvas.width  = COLS * CELL;
boardCanvas.height = ROWS * CELL;
const ctx  = boardCanvas.getContext('2d');
const nCtx = document.getElementById('next').getContext('2d');
const hCtx = document.getElementById('hold').getContext('2d');

// Piece definitions [shape matrix, color-var]
const PIECES = [
    { shape: [[1,1,1,1]],                         color: '#00F5FF' }, // I
    { shape: [[1,1],[1,1]],                        color: '#FFE600' }, // O
    { shape: [[0,1,0],[1,1,1]],                    color: '#CC44FF' }, // T
    { shape: [[0,1,1],[1,1,0]],                    color: '#39FF14' }, // S
    { shape: [[1,1,0],[0,1,1]],                    color: '#FF2D55' }, // Z
    { shape: [[1,0,0],[1,1,1]],                    color: '#0080FF' }, // J
    { shape: [[0,0,1],[1,1,1]],                    color: '#FF8C00' }, // L
];

const SCORE_TABLE = [0, 100, 300, 500, 800]; // lines cleared → score multiplier

// ── State ────────────────────────────────────────────────────────
let board, current, next, held, canHold;
let score, level, lines, best;
let gameRunning, paused, animId, lastTime, dropCounter, dropInterval;

function initState() {
    board       = Array.from({length: ROWS}, () => Array(COLS).fill(null));
    held        = null;
    canHold     = true;
    score       = 0;
    level       = 1;
    lines       = 0;
    dropCounter = 0;
    dropInterval= 800;
    paused      = false;
    gameRunning = true;
    best        = loadBest();
    next        = randomPiece();
    spawnPiece();
}

function randomPiece() {
    const p = PIECES[Math.floor(Math.random() * PIECES.length)];
    return {
        shape: p.shape.map(r => [...r]),
        color: p.color,
        x: Math.floor(COLS / 2) - Math.floor(p.shape[0].length / 2),
        y: 0
    };
}

function spawnPiece() {
    current = next;
    next    = randomPiece();
    canHold = true;
    if (collides(current, current.x, current.y)) {
        gameOver();
    }
}

// ── Collision ────────────────────────────────────────────────────
function collides(piece, ox, oy) {
    for (let r = 0; r < piece.shape.length; r++) {
        for (let c = 0; c < piece.shape[r].length; c++) {
            if (!piece.shape[r][c]) continue;
            const nx = ox + c, ny = oy + r;
            if (nx < 0 || nx >= COLS || ny >= ROWS) return true;
            if (ny >= 0 && board[ny][nx]) return true;
        }
    }
    return false;
}

// ── Rotation (SRS-lite) ──────────────────────────────────────────
function rotate(shape) {
    const rows = shape.length, cols = shape[0].length;
    const result = Array.from({length: cols}, () => Array(rows).fill(0));
    for (let r = 0; r < rows; r++)
        for (let c = 0; c < cols; c++)
            result[c][rows - 1 - r] = shape[r][c];
    return result;
}

function tryRotate() {
    const rotated = rotate(current.shape);
    const kicks = [0, -1, 1, -2, 2];
    for (const kick of kicks) {
        if (!collides({shape: rotated}, current.x + kick, current.y)) {
            current.shape = rotated;
            current.x += kick;
            return;
        }
    }
}

// ── Movement ─────────────────────────────────────────────────────
function moveLeft()  { if (!collides(current, current.x - 1, current.y)) current.x--; }
function moveRight() { if (!collides(current, current.x + 1, current.y)) current.x++; }

function softDrop() {
    if (!collides(current, current.x, current.y + 1)) {
        current.y++;
        dropCounter = 0;
        score++;
        updateUI();
    } else {
        lock();
    }
}

function hardDrop() {
    let drop = 0;
    while (!collides(current, current.x, current.y + 1)) { current.y++; drop++; }
    score += drop * 2;
    lock();
}

function holdPiece() {
    if (!canHold) return;
    canHold = false;
    if (held) {
        const tmp = held;
        held = { shape: current.shape.map(r=>[...r]), color: current.color,
                 x: Math.floor(COLS/2) - Math.floor(current.shape[0].length/2), y: 0 };
        current = { ...tmp,
                    x: Math.floor(COLS/2) - Math.floor(tmp.shape[0].length/2), y: 0 };
    } else {
        held = { shape: current.shape.map(r=>[...r]), color: current.color,
                 x: 0, y: 0 };
        spawnPiece();
    }
}

// ── Lock & clear ─────────────────────────────────────────────────
function lock() {
    for (let r = 0; r < current.shape.length; r++)
        for (let c = 0; c < current.shape[r].length; c++) {
            if (!current.shape[r][c]) continue;
            const ny = current.y + r;
            if (ny < 0) { gameOver(); return; }
            board[ny][current.x + c] = current.color;
        }
    clearLines();
    spawnPiece();
}

function clearLines() {
    let cleared = 0;
    for (let r = ROWS - 1; r >= 0; ) {
        if (board[r].every(c => c !== null)) {
            board.splice(r, 1);
            board.unshift(Array(COLS).fill(null));
            cleared++;
        } else {
            r--;
        }
    }
    if (cleared) {
        lines  += cleared;
        score  += SCORE_TABLE[cleared] * level;
        level   = Math.floor(lines / 10) + 1;
        dropInterval = Math.max(80, 800 - (level - 1) * 70);
        updateUI();
    }
}

// ── Ghost piece ───────────────────────────────────────────────────
function ghostY() {
    let gy = current.y;
    while (!collides(current, current.x, gy + 1)) gy++;
    return gy;
}

// ── Draw ──────────────────────────────────────────────────────────
const BG      = '#0E1018';
const GRID_C  = '#141624';
const GHOST_A = 0.18;

function drawCell(context, x, y, color, alpha=1, size=CELL) {
    context.globalAlpha = alpha;
    context.fillStyle = color;
    context.fillRect(x * size + 1, y * size + 1, size - 2, size - 2);
    // Inner highlight
    context.fillStyle = 'rgba(255,255,255,0.12)';
    context.fillRect(x * size + 1, y * size + 1, size - 2, 3);
    context.globalAlpha = 1;
}

function drawBoard() {
    // Background
    ctx.fillStyle = BG;
    ctx.fillRect(0, 0, boardCanvas.width, boardCanvas.height);

    // Grid lines
    ctx.strokeStyle = GRID_C;
    ctx.lineWidth = 0.5;
    for (let c = 0; c <= COLS; c++) {
        ctx.beginPath();
        ctx.moveTo(c * CELL, 0);
        ctx.lineTo(c * CELL, ROWS * CELL);
        ctx.stroke();
    }
    for (let r = 0; r <= ROWS; r++) {
        ctx.beginPath();
        ctx.moveTo(0, r * CELL);
        ctx.lineTo(COLS * CELL, r * CELL);
        ctx.stroke();
    }

    // Locked cells
    for (let r = 0; r < ROWS; r++)
        for (let c = 0; c < COLS; c++)
            if (board[r][c]) drawCell(ctx, c, r, board[r][c]);

    // Ghost
    if (current) {
        const gy = ghostY();
        for (let r = 0; r < current.shape.length; r++)
            for (let c = 0; c < current.shape[r].length; c++)
                if (current.shape[r][c])
                    drawCell(ctx, current.x + c, gy + r, current.color, GHOST_A);
    }

    // Active piece
    if (current) {
        for (let r = 0; r < current.shape.length; r++)
            for (let c = 0; c < current.shape[r].length; c++)
                if (current.shape[r][c])
                    drawCell(ctx, current.x + c, current.y + r, current.color);
    }
}

function drawMini(context, piece, canvasSize=80) {
    context.clearRect(0, 0, canvasSize, canvasSize);
    if (!piece) return;
    const s  = piece.shape;
    const cs = Math.floor(canvasSize / Math.max(s.length, s[0].length, 4));
    const ox = Math.floor((canvasSize - s[0].length * cs) / 2);
    const oy = Math.floor((canvasSize - s.length    * cs) / 2);
    for (let r = 0; r < s.length; r++)
        for (let c = 0; c < s[r].length; c++)
            if (s[r][c]) {
                context.fillStyle = piece.color;
                context.fillRect(ox + c * cs + 1, oy + r * cs + 1, cs - 2, cs - 2);
                context.fillStyle = 'rgba(255,255,255,0.12)';
                context.fillRect(ox + c * cs + 1, oy + r * cs + 1, cs - 2, 3);
            }
}

function updateUI() {
    document.getElementById('score').textContent = score;
    document.getElementById('level').textContent = level;
    document.getElementById('lines').textContent = lines;
    document.getElementById('best').textContent  = Math.max(score, best);
}

// ── Game loop ─────────────────────────────────────────────────────
function loop(timestamp) {
    if (!gameRunning || paused) return;
    const delta = timestamp - (lastTime || timestamp);
    lastTime = timestamp;
    dropCounter += delta;
    if (dropCounter >= dropInterval) {
        dropCounter = 0;
        if (!collides(current, current.x, current.y + 1)) current.y++;
        else lock();
    }
    drawBoard();
    drawMini(nCtx, next);
    drawMini(hCtx, held);
    animId = requestAnimationFrame(loop);
}

function startGame() {
    if (animId) cancelAnimationFrame(animId);
    initState();
    updateUI();
    hideOverlay();
    lastTime = null;
    animId = requestAnimationFrame(loop);
}

function gameOver() {
    gameRunning = false;
    cancelAnimationFrame(animId);
    const b = Math.max(score, best);
    saveBest(b);
    document.getElementById('best').textContent = b;
    showOverlay('Game Over', `Score: ${score} — Best: ${b}`, 'Try Again');
}

function togglePause() {
    if (!gameRunning) return;
    paused = !paused;
    if (paused) {
        showOverlay('Paused', 'Press P to resume', 'Resume');
    } else {
        hideOverlay();
        lastTime = null;
        animId = requestAnimationFrame(loop);
    }
}

// ── Overlay helpers ───────────────────────────────────────────────
function showOverlay(title, msg, btnText) {
    document.getElementById('overlayTitle').textContent = title;
    document.getElementById('overlayMsg').innerHTML     = msg;
    document.getElementById('overlayBtn').textContent  = btnText;
    document.getElementById('overlay').style.display   = 'flex';
}

function hideOverlay() {
    document.getElementById('overlay').style.display = 'none';
}

// ── Persistence ───────────────────────────────────────────────────
function loadBest() {
    try { return parseInt(localStorage.getItem('tetrisBest')) || 0; } catch { return 0; }
}
function saveBest(v) {
    try { localStorage.setItem('tetrisBest', v); } catch {}
}

// ── Keyboard ──────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (!gameRunning && e.code !== 'Enter') return;
    switch (e.code) {
        case 'ArrowLeft':  e.preventDefault(); moveLeft();   break;
        case 'ArrowRight': e.preventDefault(); moveRight();  break;
        case 'ArrowDown':  e.preventDefault(); softDrop();   break;
        case 'ArrowUp':    e.preventDefault(); tryRotate();  break;
        case 'Space':      e.preventDefault(); hardDrop();   break;
        case 'KeyC':       holdPiece();  break;
        case 'KeyP':       togglePause(); break;
    }
});

// ── Overlay button ────────────────────────────────────────────────
document.getElementById('overlayBtn').addEventListener('click', () => {
    if (paused) { togglePause(); }
    else        { startGame(); }
});

// ── Touch controls ────────────────────────────────────────────────
const tcMap = {
    'tc-left':  () => { if (gameRunning && !paused) moveLeft(); },
    'tc-right': () => { if (gameRunning && !paused) moveRight(); },
    'tc-up':    () => { if (gameRunning && !paused) tryRotate(); },
    'tc-down':  () => { if (gameRunning && !paused) softDrop(); },
    'tc-hard':  () => { if (gameRunning && !paused) hardDrop(); },
    'tc-hold':  () => { if (gameRunning && !paused) holdPiece(); },
};
Object.entries(tcMap).forEach(([id, fn]) => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('touchstart', e => { e.preventDefault(); fn(); }, {passive:false});
});

// ── Init best display ─────────────────────────────────────────────
document.getElementById('best').textContent = loadBest();
</script>

</body>
</html>