<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BomberBlast — Retro Arcade Bomb Strategy Game | BroKnowledge Games</title>
    <meta name="description" content="Play BomberBlast online! Blast crates, defeat enemies, and collect power-ups in this classic arcade-style strategy game. Free to play, no download required.">
    <link rel="canonical" href="{{ route('games.bomber') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="BomberBlast — Retro Arcade Bomb Strategy Game">
    <meta property="og:description" content="Classic arcade action! Blast your way through levels, collect power-ups, and survive the explosions.">
    <meta property="og:url" content="{{ route('games.bomber') }}">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Game Zone", "item": "{{ route('games') }}" },
            { "@@type": "ListItem", "position": 3, "name": "BomberBlast", "item": "{{ route('games.bomber') }}" }
        ]
    }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Share+Tech+Mono&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:      #0A0A14;
            --panel:   #10101E;
            --border:  #1E1E3A;
            --dim:     #2A2A50;
            --yellow:  #FFD600;
            --orange:  #FF6B00;
            --red:     #FF2D55;
            --cyan:    #00F5D4;
            --purple:  #9B5DE5;
            --green:   #39FF14;
            --text:    #E0E0FF;
            --muted:   #4A4A7A;
            --wall:    #1A1A3A;
            --block:   #2E1A5A;
            --floor:   #0D0D1F;

            --CELL: 40px;
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
            overflow: hidden;
        }

        /* ── Title ──────────────────────────────────────────────── */
        h1 {
            font-family: 'Press Start 2P', monospace;
            font-size: clamp(1rem, 3vw, 1.5rem);
            letter-spacing: 0.1em;
            color: var(--yellow);
            text-shadow: 0 0 20px rgba(255,214,0,0.5), 3px 3px 0 #7a3d00;
            margin-bottom: 0.8rem;
        }

        /* ── Layout ─────────────────────────────────────────────── */
        .layout {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        /* ── Canvas ─────────────────────────────────────────────── */
        .board-wrap { position: relative; line-height: 0; }

        canvas#game {
            display: block;
            border: 2px solid var(--border);
            image-rendering: pixelated;
        }

        /* ── Side panel ─────────────────────────────────────────── */
        .panel {
            width: 120px;
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .pbox {
            border: 1px solid var(--border);
            background: var(--panel);
            padding: 0.65rem;
        }

        .plabel {
            font-family: 'Press Start 2P', monospace;
            font-size: 0.42rem;
            letter-spacing: 0.1em;
            color: var(--muted);
            margin-bottom: 0.4rem;
        }

        .pval {
            font-family: 'Press Start 2P', monospace;
            font-size: 1rem;
            color: var(--text);
            line-height: 1;
        }

        .pval.gold   { color: var(--yellow); text-shadow: 0 0 8px rgba(255,214,0,0.4); }
        .pval.danger { color: var(--red);    text-shadow: 0 0 8px rgba(255,45,85,0.4); }

        .bomb-icons { display: flex; gap: 3px; flex-wrap: wrap; margin-top: 4px; }
        .bomb-icon  { font-size: 1rem; }

        .key-hint {
            font-size: 0.55rem;
            color: var(--muted);
            line-height: 2.2;
        }

        .upgrade-list {
            font-size: 0.52rem;
            color: var(--muted);
            line-height: 2.2;
        }

        /* ── Overlay ─────────────────────────────────────────────── */
        #overlay {
            position: absolute;
            inset: 0;
            background: rgba(10,10,20,0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            backdrop-filter: blur(4px);
        }

        #overlay h2 {
            font-family: 'Press Start 2P', monospace;
            font-size: clamp(0.9rem, 3vw, 1.4rem);
            color: var(--yellow);
            text-shadow: 0 0 16px rgba(255,214,0,0.5), 2px 2px 0 #7a3d00;
            text-align: center;
        }

        #overlay p {
            font-size: 0.6rem;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-align: center;
            line-height: 2;
            font-family: 'Share Tech Mono', monospace;
        }

        #overlay button {
            margin-top: 0.6rem;
            font-family: 'Press Start 2P', monospace;
            font-size: 0.6rem;
            letter-spacing: 0.1em;
            padding: 0.7rem 1.4rem;
            background: var(--yellow);
            color: #000;
            border: none;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
            box-shadow: 3px 3px 0 #7a3d00;
        }
        #overlay button:hover  { opacity: 0.85; }
        #overlay button:active { transform: translate(2px,2px); box-shadow: 1px 1px 0 #7a3d00; }

        /* ── Touch controls ─────────────────────────────────────── */
        .touch-pad {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.8rem;
        }
        .touch-row { display: flex; gap: 0.35rem; }
        .tbtn {
            font-size: 1.2rem;
            width: 52px; height: 52px;
            background: var(--panel);
            border: 1px solid var(--border);
            color: var(--yellow);
            cursor: pointer;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            font-family: 'Share Tech Mono', monospace;
        }
        .tbtn:active { background: var(--dim); }
        .tbtn.bomb-btn { color: var(--orange); border-color: var(--orange); font-size: 1.4rem; }

        @media (max-width: 600px) {
            :root { --CELL: 30px; }
            .panel { display: none; }
            .touch-pad { display: flex; }
            h1 { font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<h1>💣 BomberBlast</h1>

<div class="layout">

    <div class="board-wrap">
        <canvas id="game"></canvas>
        <div id="overlay">
            <h2 id="ovTitle">BomberBlast</h2>
            <p id="ovMsg">
                Place bombs to blast crates<br>
                Collect power-ups to grow stronger<br>
                Kill all enemies to advance<br><br>
                ⚠️ Don't get caught in your own blast!
            </p>
            <button id="ovBtn">Start Game</button>
        </div>
    </div>

    <div class="panel">
        <div class="pbox">
            <div class="plabel">Score</div>
            <div class="pval gold" id="scoreEl">0</div>
        </div>
        <div class="pbox">
            <div class="plabel">Level</div>
            <div class="pval" id="levelEl">1</div>
        </div>
        <div class="pbox">
            <div class="plabel">Lives</div>
            <div class="pval danger" id="livesEl">❤❤❤</div>
        </div>
        <div class="pbox">
            <div class="plabel">Bombs</div>
            <div class="bomb-icons" id="bombsEl"></div>
        </div>
        <div class="pbox upgrade-list">
            <div class="plabel">Power-ups</div>
            🔥 Flame range<br>
            💣 +1 bomb<br>
            👟 Speed up<br>
            💎 +500 pts
        </div>
        <div class="pbox key-hint">
            <div class="plabel">Controls</div>
            ↑↓←→ Move<br>
            Space Bomb<br>
            P Pause
        </div>
    </div>

</div>

<div class="touch-pad">
    <div class="touch-row">
        <div style="width:52px"></div>
        <button class="tbtn" id="tc-up">↑</button>
        <button class="tbtn bomb-btn" id="tc-bomb">💣</button>
    </div>
    <div class="touch-row">
        <button class="tbtn" id="tc-left">←</button>
        <button class="tbtn" id="tc-down">↓</button>
        <button class="tbtn" id="tc-right">→</button>
    </div>
</div>

<script>
// ── Config ───────────────────────────────────────────────────────
const CELL   = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--CELL')) || 40;
const COLS   = 13;
const ROWS   = 11;
const canvas = document.getElementById('game');
canvas.width  = COLS * CELL;
canvas.height = ROWS * CELL;
const ctx    = canvas.getContext('2d');

// Tile types
const T = { FLOOR:0, WALL:1, CRATE:2 };

// Colours
const C = {
    floor:   '#0D0D1F',
    floorAlt:'#0F0F24',
    wall:    '#1A1A3A',
    wallTop: '#2A2A5A',
    crate:   '#3D1A6B',
    crateTop:'#5A2A9A',
    crateEdge:'#7B3DCC',
    player:  '#FFD600',
    playerShadow:'#7a3d00',
    enemy1:  '#FF2D55',
    enemy2:  '#FF6B00',
    bomb:    '#111120',
    bombFuse:'#FF6B00',
    flame:   '#FF6B00',
    flameC:  '#FFD600',
};

// ── State ────────────────────────────────────────────────────────
let map, player, enemies, bombs, flames, particles, powerups;
let score, level, lives, running, paused, animId;
let keys = {};
let lastMove = 0, moveDelay = 140;
let levelClearTimer = 0;

// ── Map generator ────────────────────────────────────────────────
function genMap(lvl) {
    const m = [];
    for (let r = 0; r < ROWS; r++) {
        m[r] = [];
        for (let c = 0; c < COLS; c++) {
            if (r === 0 || r === ROWS-1 || c === 0 || c === COLS-1) {
                m[r][c] = T.WALL;
            } else if (r % 2 === 0 && c % 2 === 0) {
                m[r][c] = T.WALL;
            } else {
                // Leave safe zone around player start (1,1)
                const safe = (r <= 2 && c <= 2) || (r <= 2 && c >= COLS-3) ;
                const density = Math.min(0.35 + lvl * 0.03, 0.55);
                m[r][c] = (!safe && Math.random() < density) ? T.CRATE : T.FLOOR;
            }
        }
    }
    return m;
}

// ── Enemy AI ─────────────────────────────────────────────────────
function makeEnemy(x, y, type) {
    return { x, y, tx: x, ty: y, dir: {dx:1,dy:0}, spd: type===1 ? 180 : 130, last: 0, type, dead: false, deathTimer: 0 };
}

function spawnEnemies(lvl) {
    const count = Math.min(2 + lvl, 6);
    const spots = [];
    for (let r = 1; r < ROWS-1; r++)
        for (let c = 1; c < COLS-1; c++)
            if (map[r][c] === T.FLOOR && !(r<=3 && c<=3)) spots.push({r,c});
    const picked = spots.sort(()=>Math.random()-0.5).slice(0, count);
    return picked.map((s,i) => makeEnemy(s.c * CELL, s.r * CELL, (i%2)+1));
}

// ── Init ─────────────────────────────────────────────────────────
function initGame() {
    score  = 0; level = 1; lives = 3;
    loadLevel();
}

function loadLevel() {
    map      = genMap(level);
    bombs    = [];
    flames   = [];
    particles= [];
    powerups = [];
    player   = {
        x: CELL, y: CELL,
        tx: CELL, ty: CELL,
        maxBombs: 1, bombsLeft: 1, flameRange: 2, spd: 5,
        dead: false, deathTimer: 0, invincible: 0,
        moving: false
    };
    enemies  = spawnEnemies(level);
    levelClearTimer = 0;
    updateUI();
}

// ── Tile helpers ──────────────────────────────────────────────────
function tileAt(px, py) {
    const c = Math.round(px/CELL), r = Math.round(py/CELL);
    if (r<0||r>=ROWS||c<0||c>=COLS) return T.WALL;
    return map[r][c];
}
function setTile(px, py, t) {
    const c = Math.round(px/CELL), r = Math.round(py/CELL);
    if (r>=0&&r<ROWS&&c>=0&&c<COLS) map[r][c] = t;
}
function hasBombAt(c, r) {
    return bombs.some(b => Math.round(b.x/CELL)===c && Math.round(b.y/CELL)===r);
}

// ── Place bomb ────────────────────────────────────────────────────
function placeBomb() {
    if (player.bombsLeft <= 0 || player.dead) return;
    const bc = Math.round(player.x/CELL), br = Math.round(player.y/CELL);
    if (hasBombAt(bc, br)) return;
    player.bombsLeft--;
    bombs.push({
        x: bc*CELL, y: br*CELL,
        timer: 2500,
        range: player.flameRange,
        owner: 'player',
        flash: 0
    });
    updateBombUI();
}

// ── Explode bomb ──────────────────────────────────────────────────
function explodeBomb(bomb) {
    const bc = Math.round(bomb.x/CELL), br = Math.round(bomb.y/CELL);
    const dirs = [{dx:0,dy:0},{dx:1,dy:0},{dx:-1,dy:0},{dx:0,dy:1},{dx:0,dy:-1}];
    const newFlames = [];

    dirs.forEach(({dx, dy}) => {
        const steps = dx===0&&dy===0 ? 1 : bomb.range;
        for (let i = 0; i < steps; i++) {
            const fc = bc + dx*(i + (dx===0&&dy===0?0:1));
            const fr = br + dy*(i + (dx===0&&dy===0?0:1));
            if (fc<0||fc>=COLS||fr<0||fr>=ROWS) break;
            const tile = map[fr][fc];
            if (tile === T.WALL) break;
            newFlames.push({ x: fc*CELL, y: fr*CELL, life: 600, maxLife: 600 });
            spawnParticles(fc*CELL + CELL/2, fr*CELL + CELL/2, '#FF6B00', 8);
            if (tile === T.CRATE) {
                map[fr][fc] = T.FLOOR;
                score += 50;
                // Chance to drop power-up
                if (Math.random() < 0.35) spawnPowerup(fc, fr);
                break;
            }
            // Chain reaction
            const chainBomb = bombs.find(b => Math.round(b.x/CELL)===fc && Math.round(b.y/CELL)===fr);
            if (chainBomb && !chainBomb.exploding) { chainBomb.timer = 1; }
        }
    });

    flames.push(...newFlames);
    if (bomb.owner === 'player') {
        player.bombsLeft = Math.min(player.bombsLeft + 1, player.maxBombs);
        updateBombUI();
    }
    updateUI();
}

// ── Power-ups ─────────────────────────────────────────────────────
function spawnPowerup(c, r) {
    const types = ['flame','bomb','speed','gem'];
    const emojis = { flame:'🔥', bomb:'💣', speed:'👟', gem:'💎' };
    const t = types[Math.floor(Math.random()*types.length)];
    powerups.push({ x: c*CELL, y: r*CELL, type: t, emoji: emojis[t], pulse: 0 });
}

function collectPowerup(pu) {
    switch(pu.type) {
        case 'flame': player.flameRange = Math.min(player.flameRange + 1, 6); break;
        case 'bomb':  player.maxBombs   = Math.min(player.maxBombs + 1, 5);
                      player.bombsLeft  = Math.min(player.bombsLeft+1, player.maxBombs); break;
        case 'speed': moveDelay         = Math.max(moveDelay - 15, 70); break;
        case 'gem':   score += 500; break;
    }
    spawnParticles(pu.x + CELL/2, pu.y + CELL/2, '#FFD600', 16);
    updateUI(); updateBombUI();
}

// ── Player movement (grid-snapped) ───────────────────────────────
function movePlayer(ts) {
    if (player.dead) return;
    if (ts - lastMove < moveDelay) return;

    let dx = 0, dy = 0;
    if      (keys['ArrowLeft']  || keys['tc-left'])  dx = -1;
    else if (keys['ArrowRight'] || keys['tc-right']) dx =  1;
    else if (keys['ArrowUp']    || keys['tc-up'])    dy = -1;
    else if (keys['ArrowDown']  || keys['tc-down'])  dy =  1;

    if (dx === 0 && dy === 0) return;

    const nx = player.x + dx * CELL;
    const ny = player.y + dy * CELL;
    const nc = Math.round(nx/CELL), nr = Math.round(ny/CELL);

    if (nc >= 0 && nc < COLS && nr >= 0 && nr < ROWS
        && map[nr][nc] === T.FLOOR
        && !hasBombAt(nc, nr)) {
        player.x = nx;
        player.y = ny;
        lastMove = ts;
    }
}

// ── Enemy movement (simple random walk) ──────────────────────────
function moveEnemies(ts) {
    enemies.forEach(e => {
        if (e.dead) return;
        if (ts - e.last < e.spd) return;
        e.last = ts;

        const dirs = [{dx:1,dy:0},{dx:-1,dy:0},{dx:0,dy:1},{dx:0,dy:-1}];
        const shuffled = dirs.sort(()=>Math.random()-0.5);
        // Bias toward player
        const pdx = player.x - e.x, pdy = player.y - e.y;
        if (Math.abs(pdx) > Math.abs(pdy)) shuffled.unshift(pdx>0?{dx:1,dy:0}:{dx:-1,dy:0});
        else                               shuffled.unshift(pdy>0?{dx:0,dy:1}:{dx:0,dy:-1});

        for (const d of shuffled) {
            const nx = e.x + d.dx * CELL;
            const ny = e.y + d.dy * CELL;
            const nc = Math.round(nx/CELL), nr = Math.round(ny/CELL);
            if (nc>=0&&nc<COLS&&nr>=0&&nr<ROWS && map[nr][nc]===T.FLOOR && !hasBombAt(nc,nr)) {
                e.x = nx; e.y = ny;
                e.dir = d;
                break;
            }
        }
    });
}

// ── Collision checks ─────────────────────────────────────────────
function checkCollisions(ts) {
    if (player.dead) return;

    // Player ↔ flames
    flames.forEach(f => {
        if (Math.abs(f.x - player.x) < CELL*0.7 && Math.abs(f.y - player.y) < CELL*0.7) {
            if (player.invincible <= 0) hitPlayer();
        }
    });

    // Player ↔ enemies
    enemies.forEach(e => {
        if (e.dead) return;
        if (Math.abs(e.x - player.x) < CELL*0.65 && Math.abs(e.y - player.y) < CELL*0.65) {
            if (player.invincible <= 0) hitPlayer();
        }
    });

    // Enemies ↔ flames
    enemies.forEach(e => {
        if (e.dead) return;
        flames.forEach(f => {
            if (Math.abs(f.x - e.x) < CELL*0.7 && Math.abs(f.y - e.y) < CELL*0.7) {
                e.dead = true;
                score += 200;
                spawnParticles(e.x + CELL/2, e.y + CELL/2, e.type===1?'#FF2D55':'#FF6B00', 18);
                updateUI();
            }
        });
    });

    // Player ↔ power-ups
    powerups = powerups.filter(pu => {
        if (Math.abs(pu.x - player.x) < CELL*0.8 && Math.abs(pu.y - player.y) < CELL*0.8) {
            collectPowerup(pu);
            return false;
        }
        return true;
    });
}

function hitPlayer() {
    lives--;
    player.invincible = 120;
    spawnParticles(player.x + CELL/2, player.y + CELL/2, '#FFD600', 20);
    updateUI();
    if (lives <= 0) {
        player.dead = true;
        setTimeout(() => showOverlay('Game Over', `Score: ${score}`, 'Try Again'), 800);
    }
}

// ── Level clear check ─────────────────────────────────────────────
function checkLevelClear() {
    if (enemies.every(e => e.dead) && levelClearTimer === 0) {
        levelClearTimer = 120; // frames
    }
    if (levelClearTimer > 0) {
        levelClearTimer--;
        if (levelClearTimer === 0) {
            score += level * 500;
            level++;
            updateUI();
            loadLevel();
        }
    }
}

// ── Particles ─────────────────────────────────────────────────────
function spawnParticles(x, y, color, n=10) {
    for (let i = 0; i < n; i++) {
        const a = Math.random() * Math.PI * 2;
        const s = Math.random() * 3.5 + 0.5;
        particles.push({ x, y, vx: Math.cos(a)*s, vy: Math.sin(a)*s,
            r: Math.random()*3+1, life:1, decay: Math.random()*0.04+0.02, color });
    }
}

// ── Update ────────────────────────────────────────────────────────
function update(ts) {
    movePlayer(ts);
    moveEnemies(ts);

    // Update bombs
    bombs.forEach(b => {
        b.timer -= 16.67;
        b.flash = Math.sin(b.timer * 0.015) * 0.5 + 0.5;
        if (b.timer <= 0) {
            b.exploding = true;
            explodeBomb(b);
        }
    });
    bombs = bombs.filter(b => !b.exploding);

    // Update flames
    flames.forEach(f => { f.life -= 16.67; });
    flames = flames.filter(f => f.life > 0);

    // Update particles
    particles.forEach(p => { p.x+=p.vx; p.y+=p.vy; p.vy+=0.08; p.life-=p.decay; });
    particles = particles.filter(p => p.life > 0);

    // Power-up pulse
    powerups.forEach(pu => { pu.pulse = (pu.pulse || 0) + 0.07; });

    if (player.invincible > 0) player.invincible--;

    checkCollisions(ts);
    checkLevelClear();
}

// ── Draw ──────────────────────────────────────────────────────────
function draw(ts) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Tiles
    for (let r = 0; r < ROWS; r++) {
        for (let c = 0; c < COLS; c++) {
            const x = c * CELL, y = r * CELL;
            switch(map[r][c]) {
                case T.FLOOR:
                    ctx.fillStyle = (r+c)%2===0 ? C.floor : C.floorAlt;
                    ctx.fillRect(x, y, CELL, CELL);
                    break;
                case T.WALL:
                    // Dark base
                    ctx.fillStyle = C.wall;
                    ctx.fillRect(x, y, CELL, CELL);
                    // Top highlight
                    ctx.fillStyle = C.wallTop;
                    ctx.fillRect(x, y, CELL, 4);
                    ctx.fillRect(x, y, 4, CELL);
                    // Bottom shadow
                    ctx.fillStyle = '#0A0A1A';
                    ctx.fillRect(x, y+CELL-4, CELL, 4);
                    ctx.fillRect(x+CELL-4, y, 4, CELL);
                    break;
                case T.CRATE:
                    ctx.fillStyle = C.crate;
                    ctx.fillRect(x+1, y+1, CELL-2, CELL-2);
                    // Cross pattern
                    ctx.fillStyle = C.crateEdge;
                    ctx.fillRect(x+CELL/2-1, y+4, 2, CELL-8);
                    ctx.fillRect(x+4, y+CELL/2-1, CELL-8, 2);
                    // Edge highlights
                    ctx.fillStyle = C.crateTop;
                    ctx.fillRect(x+1, y+1, CELL-2, 3);
                    ctx.fillRect(x+1, y+1, 3, CELL-2);
                    ctx.fillStyle = '#1A0A30';
                    ctx.fillRect(x+1, y+CELL-4, CELL-2, 3);
                    break;
            }
        }
    }

    // Power-ups
    powerups.forEach(pu => {
        const px = pu.x + CELL/2, py = pu.y + CELL/2;
        const scale = 1 + Math.sin(pu.pulse) * 0.1;
        ctx.save();
        ctx.translate(px, py);
        ctx.scale(scale, scale);
        ctx.shadowColor = '#FFD600';
        ctx.shadowBlur  = 12;
        ctx.font = `${CELL * 0.6}px serif`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(pu.emoji, 0, 0);
        ctx.shadowBlur = 0;
        ctx.restore();
    });

    // Flames
    flames.forEach(f => {
        const t = f.life / f.maxLife;
        const cx = f.x + CELL/2, cy = f.y + CELL/2;
        const rad = CELL * 0.42 * t;

        // Outer glow
        ctx.globalAlpha = t * 0.4;
        ctx.fillStyle = '#FF6B00';
        ctx.beginPath();
        ctx.arc(cx, cy, rad * 1.4, 0, Math.PI*2);
        ctx.fill();

        // Core
        ctx.globalAlpha = t * 0.85;
        const grad = ctx.createRadialGradient(cx, cy, 0, cx, cy, rad);
        grad.addColorStop(0,   '#FFFF80');
        grad.addColorStop(0.4, '#FF6B00');
        grad.addColorStop(1,   'rgba(255,40,0,0)');
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(cx, cy, rad, 0, Math.PI*2);
        ctx.fill();
        ctx.globalAlpha = 1;
    });

    // Bombs
    bombs.forEach(b => {
        const bx = b.x + CELL/2, by = b.y + CELL/2;
        const r  = CELL * 0.36;

        // Shadow
        ctx.fillStyle = 'rgba(0,0,0,0.5)';
        ctx.beginPath();
        ctx.ellipse(bx+2, by+4, r, r*0.5, 0, 0, Math.PI*2);
        ctx.fill();

        // Body
        ctx.fillStyle = '#111120';
        ctx.strokeStyle = b.flash > 0.5 ? '#FF2D55' : '#333355';
        ctx.lineWidth = 2;
        ctx.shadowColor = b.flash > 0.5 ? '#FF2D55' : 'transparent';
        ctx.shadowBlur  = b.flash > 0.5 ? 12 : 0;
        ctx.beginPath();
        ctx.arc(bx, by, r, 0, Math.PI*2);
        ctx.fill();
        ctx.stroke();
        ctx.shadowBlur = 0;

        // Shine
        ctx.fillStyle = 'rgba(255,255,255,0.15)';
        ctx.beginPath();
        ctx.arc(bx - r*0.3, by - r*0.3, r*0.25, 0, Math.PI*2);
        ctx.fill();

        // Fuse spark
        const fuseLen = CELL * 0.22;
        ctx.strokeStyle = '#888888';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.moveTo(bx, by - r);
        ctx.quadraticCurveTo(bx + fuseLen, by - r - fuseLen, bx + fuseLen*0.8, by - r - fuseLen*1.4);
        ctx.stroke();
        // Spark glow
        ctx.fillStyle = b.flash > 0.5 ? '#FFFF00' : '#FF8C00';
        ctx.shadowColor = ctx.fillStyle; ctx.shadowBlur = 8;
        ctx.beginPath();
        ctx.arc(bx + fuseLen*0.8, by - r - fuseLen*1.4, 3, 0, Math.PI*2);
        ctx.fill();
        ctx.shadowBlur = 0;
    });

    // Enemies
    enemies.forEach(e => {
        if (e.dead) return;
        const ex = e.x + CELL/2, ey = e.y + CELL/2;
        const wobble = Math.sin(Date.now() * 0.008 + e.x) * 2;

        ctx.save();
        ctx.translate(ex, ey + wobble);

        // Body
        const bodyColor = e.type === 1 ? '#FF2D55' : '#FF6B00';
        ctx.fillStyle = bodyColor;
        ctx.shadowColor = bodyColor; ctx.shadowBlur = 10;
        ctx.beginPath();
        ctx.arc(0, 0, CELL * 0.35, 0, Math.PI*2);
        ctx.fill();
        ctx.shadowBlur = 0;

        // Eyes
        const eyeOff = e.dir.dx !== 0 ? e.dir.dx * 5 : 0;
        ctx.fillStyle = '#fff';
        ctx.beginPath(); ctx.arc(-5 + eyeOff, -4, 4, 0, Math.PI*2); ctx.fill();
        ctx.beginPath(); ctx.arc( 5 + eyeOff, -4, 4, 0, Math.PI*2); ctx.fill();
        ctx.fillStyle = '#000';
        ctx.beginPath(); ctx.arc(-4 + eyeOff, -3, 2, 0, Math.PI*2); ctx.fill();
        ctx.beginPath(); ctx.arc( 6 + eyeOff, -3, 2, 0, Math.PI*2); ctx.fill();

        // Angry brow
        ctx.strokeStyle = '#000'; ctx.lineWidth = 1.5;
        ctx.beginPath(); ctx.moveTo(-8,-8); ctx.lineTo(-2,-6); ctx.stroke();
        ctx.beginPath(); ctx.moveTo( 8,-8); ctx.lineTo( 2,-6); ctx.stroke();

        // Spiky top
        ctx.fillStyle = bodyColor;
        for (let i = 0; i < 4; i++) {
            const a = -Math.PI * 0.7 + i * 0.45;
            ctx.beginPath();
            ctx.moveTo(Math.cos(a)*CELL*0.33, Math.sin(a)*CELL*0.33);
            ctx.lineTo(Math.cos(a - 0.15)*CELL*0.5, Math.sin(a - 0.15)*CELL*0.5);
            ctx.lineTo(Math.cos(a + 0.15)*CELL*0.5, Math.sin(a + 0.15)*CELL*0.5);
            ctx.closePath(); ctx.fill();
        }

        ctx.restore();
    });

    // Player
    if (!player.dead) {
        const blink = player.invincible > 0 && Math.floor(player.invincible / 8) % 2 === 0;
        if (!blink) {
            const px = player.x + CELL/2, py = player.y + CELL/2;
            const bob = Math.sin(ts * 0.006) * 1.5;

            ctx.save();
            ctx.translate(px, py + bob);

            // Shadow
            ctx.globalAlpha = 0.4;
            ctx.fillStyle = '#000';
            ctx.beginPath(); ctx.ellipse(0, CELL*0.38, CELL*0.28, CELL*0.12, 0, 0, Math.PI*2); ctx.fill();
            ctx.globalAlpha = 1;

            // Body
            ctx.fillStyle = '#FFD600';
            ctx.shadowColor = '#FFD600'; ctx.shadowBlur = 14;
            ctx.beginPath(); ctx.arc(0, 0, CELL*0.34, 0, Math.PI*2); ctx.fill();
            ctx.shadowBlur = 0;

            // Visor
            ctx.fillStyle = '#000033';
            ctx.beginPath(); ctx.ellipse(0, -3, CELL*0.2, CELL*0.12, 0, 0, Math.PI*2); ctx.fill();

            // Visor shine
            ctx.fillStyle = 'rgba(0,245,255,0.6)';
            ctx.beginPath(); ctx.ellipse(-4, -5, 4, 3, -0.3, 0, Math.PI*2); ctx.fill();

            // Belt
            ctx.fillStyle = '#7a3d00';
            ctx.fillRect(-CELL*0.34, 4, CELL*0.68, 4);
            ctx.fillStyle = '#FFD600';
            ctx.fillRect(-3, 3, 6, 6);

            // Legs
            ctx.fillStyle = '#3A1A6B';
            ctx.beginPath(); ctx.ellipse(-7, CELL*0.38, 5, 7, 0, 0, Math.PI*2); ctx.fill();
            ctx.beginPath(); ctx.ellipse( 7, CELL*0.38, 5, 7, 0, 0, Math.PI*2); ctx.fill();

            ctx.restore();
        }
    }

    // Particles
    particles.forEach(p => {
        ctx.globalAlpha = p.life;
        ctx.fillStyle   = p.color;
        ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI*2); ctx.fill();
    });
    ctx.globalAlpha = 1;

    // Level clear flash
    if (levelClearTimer > 0 && levelClearTimer > 60) {
        ctx.fillStyle = `rgba(57,255,20,${(levelClearTimer-60)/60 * 0.2})`;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = `rgba(57,255,20,${(levelClearTimer-60)/60 * 0.9})`;
        ctx.font = `bold ${CELL*0.7}px 'Press Start 2P', monospace`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.shadowColor = '#39FF14'; ctx.shadowBlur = 20;
        ctx.fillText('LEVEL CLEAR!', canvas.width/2, canvas.height/2);
        ctx.shadowBlur = 0;
    }
}

// ── UI updates ────────────────────────────────────────────────────
function updateUI() {
    document.getElementById('scoreEl').textContent = score;
    document.getElementById('levelEl').textContent = level;
    document.getElementById('livesEl').textContent = '❤'.repeat(Math.max(lives,0)) + '🖤'.repeat(Math.max(3-lives,0));
    updateBombUI();
}
function updateBombUI() {
    const el = document.getElementById('bombsEl');
    el.innerHTML = '';
    for (let i = 0; i < player.maxBombs; i++) {
        const s = document.createElement('span');
        s.className = 'bomb-icon';
        s.textContent = i < player.bombsLeft ? '💣' : '🕳';
        el.appendChild(s);
    }
}

// ── Loop ──────────────────────────────────────────────────────────
function loop(ts) {
    if (!running || paused) return;
    update(ts);
    draw(ts);
    animId = requestAnimationFrame(loop);
}

function startGame() {
    if (animId) cancelAnimationFrame(animId);
    moveDelay = 140;
    initGame();
    hideOverlay();
    running = true; paused = false;
    animId = requestAnimationFrame(loop);
}

function togglePause() {
    if (!running) return;
    paused = !paused;
    if (paused) showOverlay('Paused', 'Press P to resume', 'Resume');
    else { hideOverlay(); animId = requestAnimationFrame(loop); }
}

// ── Overlay ───────────────────────────────────────────────────────
function showOverlay(t, m, b) {
    document.getElementById('ovTitle').textContent  = t;
    document.getElementById('ovMsg').innerHTML      = m;
    document.getElementById('ovBtn').textContent    = b;
    document.getElementById('overlay').style.display = 'flex';
}
function hideOverlay() { document.getElementById('overlay').style.display = 'none'; }

document.getElementById('ovBtn').addEventListener('click', () => {
    paused ? togglePause() : startGame();
});

// ── Keys ──────────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    keys[e.code] = true;
    if (e.code === 'Space') { e.preventDefault(); placeBomb(); }
    if (e.code === 'KeyP')  togglePause();
    if (['ArrowUp','ArrowDown','ArrowLeft','ArrowRight'].includes(e.code)) e.preventDefault();
});
document.addEventListener('keyup', e => { keys[e.code] = false; });

// Touch
['tc-up','tc-down','tc-left','tc-right'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('touchstart', e => { e.preventDefault(); keys[id] = true; }, {passive:false});
    el.addEventListener('touchend',   e => { e.preventDefault(); keys[id] = false; });
});
const tcBomb = document.getElementById('tc-bomb');
if (tcBomb) tcBomb.addEventListener('touchstart', e => { e.preventDefault(); placeBomb(); }, {passive:false});

// ── Init ──────────────────────────────────────────────────────────
// Draw static preview
ctx.fillStyle = '#0A0A14'; ctx.fillRect(0,0,canvas.width,canvas.height);
</script>
</body>
</html>