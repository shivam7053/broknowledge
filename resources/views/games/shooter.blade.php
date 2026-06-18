<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Shooter — Retro Space Arcade Game | BroKnowledge Games</title>
    <meta name="description" content="Defend the galaxy in Star Shooter! Shoot stars, dodge bombs, and collect power-ups in this fast-paced space arcade game. Play for free online.">
    <link rel="canonical" href="{{ route('games.shooter') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Star Shooter — Retro Space Arcade Game">
    <meta property="og:description" content="Space combat action! Shoot stars, avoid bombs, and survive the cosmic onslaught.">
    <meta property="og:url" content="{{ route('games.shooter') }}">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Game Zone", "item": "{{ route('games') }}" },
            { "@@type": "ListItem", "position": 3, "name": "Star Shooter", "item": "{{ route('games.shooter') }}" }
        ]
    }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Share+Tech+Mono&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:     #04040F;
            --panel:  #08081A;
            --border: #14143A;
            --cyan:   #00F5FF;
            --yellow: #FFE600;
            --red:    #FF2D55;
            --green:  #39FF14;
            --purple: #CC44FF;
            --text:   #C8D8FF;
            --muted:  #3A4A7A;
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

        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.8rem;
            letter-spacing: 0.35em;
            color: var(--cyan);
            text-shadow: 0 0 20px rgba(0,245,255,0.5);
            margin-bottom: 0.7rem;
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
            background: var(--bg);
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

        .pval.hi  { color: var(--yellow); }
        .pval.red { color: var(--red); }

        .lives-row { display: flex; gap: 4px; flex-wrap: wrap; margin-top: 4px; }
        .life-icon { font-size: 1rem; }

        .key-hint {
            font-size: 0.58rem;
            color: var(--muted);
            line-height: 2;
        }

        /* ── Legend ──────────────────────────────────────────────── */
        .legend {
            font-size: 0.6rem;
            color: var(--muted);
            line-height: 2.2;
        }
        .legend span { margin-right: 4px; }

        /* ── Overlay ─────────────────────────────────────────────── */
        #overlay {
            position: absolute;
            inset: 0;
            background: rgba(4,4,15,0.88);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            backdrop-filter: blur(3px);
        }

        #overlay h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.6rem;
            letter-spacing: 0.2em;
            color: var(--cyan);
            text-shadow: 0 0 16px rgba(0,245,255,0.5);
        }

        #overlay p {
            font-size: 0.68rem;
            letter-spacing: 0.1em;
            color: var(--muted);
            text-align: center;
            line-height: 1.9;
        }

        #overlay button {
            margin-top: 0.6rem;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.55rem 1.5rem;
            background: transparent;
            border: 1px solid var(--muted);
            color: var(--cyan);
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        #overlay button:hover {
            border-color: var(--cyan);
            box-shadow: 0 0 12px rgba(0,245,255,0.3);
        }

        /* ── Touch controls ──────────────────────────────────────── */
        .touch-pad {
            display: none;
            gap: 0.5rem;
            margin-top: 0.8rem;
            align-items: center;
        }

        .tbtn {
            font-size: 1.2rem;
            padding: 0.6rem 1.1rem;
            background: var(--panel);
            border: 1px solid var(--border);
            color: var(--cyan);
            cursor: pointer;
            border-radius: 4px;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: background 0.1s;
            font-family: 'Share Tech Mono', monospace;
            letter-spacing: 0.05em;
        }

        .tbtn:active { background: var(--border); }
        .tbtn.fire   { color: var(--yellow); border-color: var(--yellow); }

        @media (max-width: 620px) {
            .panel { width: 80px; }
            .pval  { font-size: 1.4rem; }
            h1     { font-size: 2rem; }
            .touch-pad { display: flex; }
        }
    </style>
</head>
<body>

<h1>Star Shooter</h1>

<div class="layout">

    <div class="board-wrap">
        <canvas id="game"></canvas>
        <div id="overlay">
            <h2 id="ovTitle">Star Shooter</h2>
            <p id="ovMsg">
                ★ Shoot falling stars for points<br>
                💣 Dodge the bombs — one hit = game over!<br>
                3 lives to survive
            </p>
            <button id="ovBtn">Launch</button>
        </div>
    </div>

    <div class="panel">
        <div class="pbox">
            <div class="plabel">Score</div>
            <div class="pval" id="scoreEl">0</div>
        </div>
        <div class="pbox">
            <div class="plabel">Best</div>
            <div class="pval hi" id="bestEl">0</div>
        </div>
        <div class="pbox">
            <div class="plabel">Level</div>
            <div class="pval" id="levelEl">1</div>
        </div>
        <div class="pbox">
            <div class="plabel">Lives</div>
            <div class="lives-row" id="livesEl"></div>
        </div>
        <div class="pbox legend">
            <div class="plabel">Objects</div>
            <span>★</span> Star +10<br>
            <span>💣</span> Bomb DEAD<br>
            <span>⚡</span> Rapid fire<br>
            <span>🛡</span> Shield
        </div>
        <div class="pbox key-hint">
            ← → Move<br>
            Space Shoot<br>
            P Pause
        </div>
    </div>

</div>

<div class="touch-pad">
    <button class="tbtn" id="tc-left">◀ Left</button>
    <button class="tbtn fire" id="tc-fire">⚡ Fire</button>
    <button class="tbtn" id="tc-right">Right ▶</button>
</div>

<script>
// ── Canvas setup ─────────────────────────────────────────────────
const canvas = document.getElementById('game');
const isMobile = window.innerWidth <= 620;
const W = isMobile ? Math.min(window.innerWidth - 110, 320) : 420;
const H = isMobile ? 480 : 560;
canvas.width  = W;
canvas.height = H;
const ctx = canvas.getContext('2d');

// ── Constants ─────────────────────────────────────────────────────
const SHIP_W   = 44, SHIP_H = 34;
const BULLET_W = 3,  BULLET_H = 14;
const MAX_LIVES = 3;
const STAR_TYPES = ['★','✦','✧','✶','✵'];

// ── State ─────────────────────────────────────────────────────────
let ship, bullets, objects, particles, bgStars;
let score, best, level, lives;
let running, paused, animId;
let keys = {};
let lastSpawn = 0, spawnInterval = 1100;
let rapidFireUntil = 0, shieldUntil = 0;
let lastShot = 0, shotCooldown = 280;
let frameTs = 0;
let screenFlash = 0;

// ── Background stars ──────────────────────────────────────────────
function makeBgStars() {
    bgStars = Array.from({length: 80}, () => ({
        x: Math.random() * W,
        y: Math.random() * H,
        r: Math.random() * 1.2 + 0.3,
        spd: Math.random() * 0.4 + 0.1,
        a: Math.random() * 0.6 + 0.2
    }));
}

// ── Init ──────────────────────────────────────────────────────────
function initGame() {
    ship = { x: W/2, y: H - 55, w: SHIP_W, h: SHIP_H, spd: 5, shieldHit: 0 };
    bullets   = [];
    objects   = [];
    particles = [];
    score     = 0;
    level     = 1;
    lives     = MAX_LIVES;
    lastSpawn = 0;
    spawnInterval = 1100;
    rapidFireUntil = 0;
    shieldUntil    = 0;
    lastShot    = 0;
    shotCooldown = 280;
    screenFlash  = 0;
    makeBgStars();
    updateUI();
}

// ── Spawn falling objects ─────────────────────────────────────────
function spawnObject(ts) {
    if (ts - lastSpawn < spawnInterval) return;
    lastSpawn = ts;

    const rand = Math.random();
    let type, emoji, color, pts, spd;

    if (rand < 0.65) {
        // Regular star
        type  = 'star';
        emoji = STAR_TYPES[Math.floor(Math.random() * STAR_TYPES.length)];
        color = ['#FFE600','#00F5FF','#CC44FF','#FF8C00','#39FF14'][Math.floor(Math.random()*5)];
        pts   = 10;
        spd   = 1.5 + level * 0.4 + Math.random() * 0.8;
    } else if (rand < 0.85) {
        // Bomb — instant death
        type  = 'bomb';
        emoji = '💣';
        color = '#FF2D55';
        pts   = 0;
        spd   = 1.2 + level * 0.35 + Math.random() * 0.6;
    } else if (rand < 0.93) {
        // Power-up: rapid fire
        type  = 'rapid';
        emoji = '⚡';
        color = '#FFE600';
        pts   = 0;
        spd   = 1.3 + Math.random() * 0.5;
    } else {
        // Power-up: shield
        type  = 'shield';
        emoji = '🛡';
        color = '#00F5FF';
        pts   = 0;
        spd   = 1.3 + Math.random() * 0.5;
    }

    objects.push({
        x:     Math.random() * (W - 32) + 16,
        y:     -24,
        type, emoji, color, pts, spd,
        rot:   0,
        rotSpd: (Math.random() - 0.5) * 0.08,
        size:  22 + Math.random() * 8,
        wobble: Math.random() * Math.PI * 2,
        wobbleSpd: 0.04 + Math.random() * 0.02
    });
}

// ── Shoot ──────────────────────────────────────────────────────────
function shoot(ts) {
    const cd = (ts < rapidFireUntil) ? 100 : shotCooldown;
    if (ts - lastShot < cd) return;
    lastShot = ts;
    bullets.push({ x: ship.x, y: ship.y - SHIP_H/2, spd: 10 });
    if (ts < rapidFireUntil) {
        // Spread shot
        bullets.push({ x: ship.x - 10, y: ship.y - SHIP_H/2 + 6, spd: 10, dx: -1.5 });
        bullets.push({ x: ship.x + 10, y: ship.y - SHIP_H/2 + 6, spd: 10, dx:  1.5 });
    }
}

// ── Particles ─────────────────────────────────────────────────────
function spawnParticles(x, y, color, count=12) {
    for (let i = 0; i < count; i++) {
        const angle = Math.random() * Math.PI * 2;
        const spd   = Math.random() * 3 + 1;
        particles.push({
            x, y,
            vx: Math.cos(angle) * spd,
            vy: Math.sin(angle) * spd,
            r:  Math.random() * 3 + 1,
            life: 1.0,
            decay: Math.random() * 0.04 + 0.025,
            color
        });
    }
}

// ── Hit test (circle) ─────────────────────────────────────────────
function hit(a, b, ar, br) {
    const dx = a.x - b.x, dy = a.y - b.y;
    return dx*dx + dy*dy < (ar+br)*(ar+br);
}

// ── Update ────────────────────────────────────────────────────────
function update(ts) {
    const dt = 1; // normalised per-frame

    // Move background stars
    bgStars.forEach(s => { s.y += s.spd; if (s.y > H) s.y = 0; });

    // Move ship
    if (keys['ArrowLeft']  || keys['tc-left'])  ship.x = Math.max(SHIP_W/2,      ship.x - ship.spd);
    if (keys['ArrowRight'] || keys['tc-right']) ship.x = Math.min(W - SHIP_W/2,  ship.x + ship.spd);

    // Auto-fire if key held
    if (keys['Space'] || keys['tc-fire']) shoot(ts);

    // Move bullets
    bullets = bullets.filter(b => b.y > -10);
    bullets.forEach(b => {
        b.y -= b.spd;
        if (b.dx) b.x += b.dx;
    });

    // Move objects
    spawnObject(ts);
    objects.forEach(o => {
        o.y += o.spd;
        o.rot += o.rotSpd;
        o.wobble += o.wobbleSpd;
        o.x += Math.sin(o.wobble) * (o.type === 'bomb' ? 0.8 : 0.3);
    });

    // Bullet ↔ object collision
    bullets.forEach(b => {
        objects.forEach(o => {
            if (o.dead) return;
            if (hit(b, o, 4, o.size/2)) {
                b.dead = true;
                o.dead = true;
                if (o.type === 'star') {
                    score += o.pts;
                    spawnParticles(o.x, o.y, o.color, 14);
                } else if (o.type === 'rapid') {
                    rapidFireUntil = ts + 5000;
                    spawnParticles(o.x, o.y, '#FFE600', 16);
                } else if (o.type === 'shield') {
                    shieldUntil = ts + 7000;
                    spawnParticles(o.x, o.y, '#00F5FF', 16);
                } else if (o.type === 'bomb') {
                    score += 25; // bonus for shooting a bomb
                    spawnParticles(o.x, o.y, '#FF2D55', 22);
                    screenFlash = 8;
                }
                updateUI();
            }
        });
    });

    bullets  = bullets.filter(b => !b.dead);
    objects  = objects.filter(o => !o.dead && o.y < H + 30);

    // Object ↔ ship collision
    objects.forEach(o => {
        if (o.dead) return;
        if (hit(o, ship, o.size/2 - 4, SHIP_W/2 - 8)) {
            o.dead = true;
            if (o.type === 'bomb') {
                // Instant game over unless shielded
                if (ts < shieldUntil) {
                    shieldUntil = 0; // shield consumed
                    ship.shieldHit = 30;
                    spawnParticles(ship.x, ship.y, '#00F5FF', 20);
                } else {
                    spawnParticles(ship.x, ship.y, '#FF2D55', 30);
                    screenFlash = 20;
                    endGame();
                    return;
                }
            } else if (o.type === 'star') {
                // Star hits ship → lose a life
                if (ts < shieldUntil) {
                    shieldUntil = 0;
                    ship.shieldHit = 30;
                } else {
                    lives--;
                    spawnParticles(ship.x, ship.y, '#FF8C00', 16);
                    screenFlash = 12;
                    if (lives <= 0) { endGame(); return; }
                }
                updateUI();
            } else if (o.type === 'rapid') {
                rapidFireUntil = ts + 5000;
                spawnParticles(o.x, o.y, '#FFE600', 14);
            } else if (o.type === 'shield') {
                shieldUntil = ts + 7000;
                spawnParticles(o.x, o.y, '#00F5FF', 14);
            }
        }
    });

    objects = objects.filter(o => !o.dead);

    // Particles
    particles.forEach(p => {
        p.x += p.vx; p.y += p.vy;
        p.vy += 0.06;
        p.life -= p.decay;
    });
    particles = particles.filter(p => p.life > 0);

    if (ship.shieldHit > 0) ship.shieldHit--;

    // Level up
    const newLevel = Math.floor(score / 200) + 1;
    if (newLevel > level) {
        level = newLevel;
        spawnInterval = Math.max(350, 1100 - (level-1)*80);
        updateUI();
    }
}

// ── Draw ──────────────────────────────────────────────────────────
function draw(ts) {
    // Clear
    ctx.fillStyle = '#04040F';
    ctx.fillRect(0, 0, W, H);

    // Screen flash
    if (screenFlash > 0) {
        ctx.fillStyle = `rgba(255,45,85,${screenFlash/20 * 0.35})`;
        ctx.fillRect(0, 0, W, H);
        screenFlash--;
    }

    // Background stars
    bgStars.forEach(s => {
        ctx.globalAlpha = s.a;
        ctx.fillStyle = '#ffffff';
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI*2);
        ctx.fill();
    });
    ctx.globalAlpha = 1;

    // Particles
    particles.forEach(p => {
        ctx.globalAlpha = p.life * 0.9;
        ctx.fillStyle = p.color;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
        ctx.fill();
    });
    ctx.globalAlpha = 1;

    // Bullets
    bullets.forEach(b => {
        ctx.shadowColor = '#00F5FF';
        ctx.shadowBlur  = 8;
        ctx.fillStyle   = '#00F5FF';
        const bx = b.x - BULLET_W/2;
        ctx.beginPath();
        ctx.roundRect(bx, b.y, BULLET_W, BULLET_H, 2);
        ctx.fill();
        // Inner bright core
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.roundRect(bx + 0.5, b.y + 2, BULLET_W - 1, BULLET_H - 4, 1);
        ctx.fill();
        ctx.shadowBlur = 0;
    });

    // Falling objects
    objects.forEach(o => {
        ctx.save();
        ctx.translate(o.x, o.y);
        ctx.rotate(o.rot);
        ctx.font = `${o.size}px serif`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        if (o.type !== 'bomb' && o.type !== 'rapid' && o.type !== 'shield') {
            ctx.shadowColor = o.color;
            ctx.shadowBlur  = 12;
        } else if (o.type === 'bomb') {
            ctx.shadowColor = '#FF2D55';
            ctx.shadowBlur  = 16;
        }

        ctx.fillText(o.emoji, 0, 0);
        ctx.shadowBlur = 0;
        ctx.restore();
    });

    // Ship
    drawShip(ts);

    // Power-up HUD bars
    drawHUD(ts);
}

function drawShip(ts) {
    const sx = ship.x, sy = ship.y;
    const shielded = ts < shieldUntil;

    ctx.save();
    ctx.translate(sx, sy);

    // Shield bubble
    if (shielded) {
        const pulse = 0.85 + Math.sin(ts * 0.006) * 0.15;
        ctx.globalAlpha = pulse * 0.4;
        ctx.strokeStyle = '#00F5FF';
        ctx.lineWidth   = 2.5;
        ctx.shadowColor = '#00F5FF';
        ctx.shadowBlur  = 18;
        ctx.beginPath();
        ctx.arc(0, 0, SHIP_W/2 + 10, 0, Math.PI*2);
        ctx.stroke();
        ctx.globalAlpha = 1;
        ctx.shadowBlur  = 0;
    }

    if (ship.shieldHit > 0) {
        ctx.globalAlpha = 0.4 + Math.random() * 0.4;
    }

    // Engine glow
    ctx.shadowColor = '#FF8C00';
    ctx.shadowBlur  = 14;
    ctx.fillStyle   = '#FF4500';
    // Left thruster flame
    ctx.beginPath();
    ctx.moveTo(-10, SHIP_H/2 - 2);
    ctx.lineTo(-6,  SHIP_H/2 + 10 + Math.random()*5);
    ctx.lineTo(-2,  SHIP_H/2 - 2);
    ctx.closePath();
    ctx.fill();
    // Right thruster flame
    ctx.beginPath();
    ctx.moveTo(2,  SHIP_H/2 - 2);
    ctx.lineTo(6,  SHIP_H/2 + 10 + Math.random()*5);
    ctx.lineTo(10, SHIP_H/2 - 2);
    ctx.closePath();
    ctx.fill();

    ctx.shadowBlur = 0;

    // Body — fuselage
    ctx.shadowColor = '#00F5FF';
    ctx.shadowBlur  = 10;
    const grad = ctx.createLinearGradient(-SHIP_W/2, -SHIP_H/2, SHIP_W/2, SHIP_H/2);
    grad.addColorStop(0, '#1A3AFF');
    grad.addColorStop(0.5, '#00A8FF');
    grad.addColorStop(1, '#0050CC');
    ctx.fillStyle = grad;

    ctx.beginPath();
    ctx.moveTo(0, -SHIP_H/2);         // nose tip
    ctx.lineTo(SHIP_W/2, SHIP_H/2);   // right base
    ctx.lineTo(SHIP_W/3, SHIP_H/4);   // right indent
    ctx.lineTo(0, SHIP_H/3);          // centre indent
    ctx.lineTo(-SHIP_W/3, SHIP_H/4);  // left indent
    ctx.lineTo(-SHIP_W/2, SHIP_H/2);  // left base
    ctx.closePath();
    ctx.fill();

    // Cockpit
    ctx.shadowBlur = 6;
    ctx.fillStyle  = '#00F5FF';
    ctx.globalAlpha = 0.8;
    ctx.beginPath();
    ctx.ellipse(0, -SHIP_H/6, 5, 8, 0, 0, Math.PI*2);
    ctx.fill();

    ctx.globalAlpha = 1;
    ctx.shadowBlur  = 0;
    ctx.restore();
}

function drawHUD(ts) {
    const pad = 6;
    let y = H - 10;

    // Rapid fire bar
    if (ts < rapidFireUntil) {
        const pct = (rapidFireUntil - ts) / 5000;
        ctx.fillStyle = 'rgba(255,230,0,0.15)';
        ctx.fillRect(pad, y - 5, W - pad*2, 5);
        ctx.fillStyle = '#FFE600';
        ctx.shadowColor = '#FFE600'; ctx.shadowBlur = 6;
        ctx.fillRect(pad, y - 5, (W - pad*2) * pct, 5);
        ctx.shadowBlur = 0;
        ctx.fillStyle = '#FFE600';
        ctx.font = '9px Share Tech Mono';
        ctx.fillText('⚡ RAPID FIRE', pad + 2, y - 8);
        y -= 14;
    }

    // Shield bar
    if (ts < shieldUntil) {
        const pct = (shieldUntil - ts) / 7000;
        ctx.fillStyle = 'rgba(0,245,255,0.12)';
        ctx.fillRect(pad, y - 5, W - pad*2, 5);
        ctx.fillStyle = '#00F5FF';
        ctx.shadowColor = '#00F5FF'; ctx.shadowBlur = 6;
        ctx.fillRect(pad, y - 5, (W - pad*2) * pct, 5);
        ctx.shadowBlur = 0;
        ctx.fillStyle = '#00F5FF';
        ctx.font = '9px Share Tech Mono';
        ctx.fillText('🛡 SHIELD', pad + 2, y - 8);
    }
}

// ── UI ────────────────────────────────────────────────────────────
function updateUI() {
    document.getElementById('scoreEl').textContent = score;
    document.getElementById('levelEl').textContent = level;
    document.getElementById('bestEl').textContent  = Math.max(score, loadBest());
    const el = document.getElementById('livesEl');
    el.innerHTML = '';
    for (let i = 0; i < MAX_LIVES; i++) {
        const s = document.createElement('span');
        s.className = 'life-icon';
        s.textContent = i < lives ? '🚀' : '💀';
        el.appendChild(s);
    }
}

// ── Game loop ─────────────────────────────────────────────────────
function loop(ts) {
    if (!running || paused) return;
    update(ts);
    draw(ts);
    animId = requestAnimationFrame(loop);
}

function startGame() {
    if (animId) cancelAnimationFrame(animId);
    initGame();
    hideOverlay();
    running = true;
    paused  = false;
    animId  = requestAnimationFrame(loop);
}

function endGame() {
    running = false;
    cancelAnimationFrame(animId);
    const b = Math.max(score, loadBest());
    saveBest(b);
    document.getElementById('bestEl').textContent = b;
    setTimeout(() => {
        showOverlay('Game Over', `Score: ${score}<br>Best: ${b}`, 'Play Again');
    }, 600);
}

function togglePause() {
    if (!running) return;
    paused = !paused;
    if (paused) {
        showOverlay('Paused', 'Press P to resume', 'Resume');
    } else {
        hideOverlay();
        animId = requestAnimationFrame(loop);
    }
}

// ── Overlay ───────────────────────────────────────────────────────
function showOverlay(title, msg, btn) {
    document.getElementById('ovTitle').textContent  = title;
    document.getElementById('ovMsg').innerHTML      = msg;
    document.getElementById('ovBtn').textContent    = btn;
    document.getElementById('overlay').style.display = 'flex';
}
function hideOverlay() {
    document.getElementById('overlay').style.display = 'none';
}

document.getElementById('ovBtn').addEventListener('click', () => {
    if (paused) togglePause();
    else startGame();
});

// ── Keyboard ──────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    keys[e.code] = true;
    if (e.code === 'Space') e.preventDefault();
    if (e.code === 'KeyP')  togglePause();
});
document.addEventListener('keyup', e => { keys[e.code] = false; });

// ── Touch ─────────────────────────────────────────────────────────
['tc-left','tc-right','tc-fire'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('touchstart', e => { e.preventDefault(); keys[id] = true; }, {passive:false});
    el.addEventListener('touchend',   e => { e.preventDefault(); keys[id] = false; });
});

// ── Persistence ───────────────────────────────────────────────────
function loadBest() { try { return parseInt(localStorage.getItem('shooterBest'))||0; } catch{return 0;} }
function saveBest(v){ try { localStorage.setItem('shooterBest', v); } catch{} }

// ── Init ──────────────────────────────────────────────────────────
document.getElementById('bestEl').textContent = loadBest();
// Draw static starfield on load
makeBgStars();
ctx.fillStyle = '#04040F'; ctx.fillRect(0,0,W,H);
bgStars.forEach(s => {
    ctx.globalAlpha = s.a;
    ctx.fillStyle = '#fff';
    ctx.beginPath(); ctx.arc(s.x, s.y, s.r, 0, Math.PI*2); ctx.fill();
});
ctx.globalAlpha = 1;
</script>

</body>
</html>