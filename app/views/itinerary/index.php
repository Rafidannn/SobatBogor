<?php
/**
 * app/views/itinerary/index.php
 * Smart Itinerary Planner — SobatBogor (Premium Redesign)
 */
?>

<!-- ══ Extra CDN for this page ══════════════════════════ -->
<!-- GSAP + ScrollTrigger -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<!-- CountUp.js -->
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.8.0/dist/countUp.umd.js"></script>
<!-- noUiSlider (not used here, keeping reserved) -->

<style>
/* ═══════════════════════════════════════════════════════════
   ITINERARY PAGE — Premium 2025 UI
   Brand: Blue #1a6bbf | Green #3a9e3a | Dark #0f172a
   ═══════════════════════════════════════════════════════════ */

/* ── Hero ─────────────────────────────────────────────── */
.itin-hero {
    min-height: 340px;
    background: #0a0f1e;
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.itin-hero-canvas {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    pointer-events: none;
    z-index: 0;
}
/* radial glow blobs */
.hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.35;
    pointer-events: none;
}
.hero-blob-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, #1a6bbf 0%, transparent 70%);
    top: -150px; left: -100px;
    animation: blobFloat 8s ease-in-out infinite alternate;
}
.hero-blob-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, #3a9e3a 0%, transparent 70%);
    bottom: -120px; right: 5%;
    animation: blobFloat 10s ease-in-out infinite alternate-reverse;
}
@keyframes blobFloat {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(30px, 20px) scale(1.08); }
}
.hero-grid-lines {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(26,107,191,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(26,107,191,0.06) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}
.hero-inner {
    position: relative;
    z-index: 1;
    padding: 3.5rem 0 2.5rem;
    width: 100%;
}
.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: rgba(26,107,191,0.15);
    border: 1px solid rgba(96,165,250,0.25);
    border-radius: 30px;
    padding: 0.3rem 1rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #60a5fa;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 1rem;
}
.hero-eyebrow i { font-size: 0.7rem; }
.hero-title {
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    margin-bottom: 0.75rem;
    letter-spacing: -0.5px;
}
.hero-title .grad-text {
    background: linear-gradient(135deg, #60a5fa 0%, #4ade80 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-subtitle {
    color: rgba(255,255,255,0.58);
    font-size: 0.95rem;
    max-width: 520px;
    line-height: 1.65;
    margin: 0;
}
/* Stats chips in hero */
.hero-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1.75rem;
}
.stat-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(8px);
    border-radius: 30px;
    padding: 0.4rem 1rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: rgba(255,255,255,0.8);
}
.stat-chip i { color: #4ade80; font-size: 0.8rem; }
/* Hero bottom gradient line */
.itin-hero::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #1a6bbf 30%, #3a9e3a 70%, transparent);
}

/* ── Breadcrumb ──────────────────────────────────────── */
.hero-breadcrumb .breadcrumb-item a { color: rgba(255,255,255,0.45); font-size: 0.82rem; }
.hero-breadcrumb .breadcrumb-item.active { color: rgba(255,255,255,0.75); font-size: 0.82rem; }
.hero-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.25); }

/* ── Page Layout ─────────────────────────────────────── */
.itin-layout { padding: 2.25rem 0 4.5rem; background: var(--gray-50); }

/* ── Glass Form Panel ────────────────────────────────── */
.form-panel {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(26,107,191,0.12);
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    overflow: hidden;
    transition: box-shadow .3s ease;
}
.form-panel:hover { box-shadow: 0 8px 32px rgba(26,107,191,0.12); }
.form-panel-header {
    padding: 1.35rem 1.5rem;
    background: linear-gradient(135deg, #0f172a 0%, #1a2a4a 50%, #0d2316 100%);
    position: relative;
    overflow: hidden;
}
.form-panel-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 120% at 90% 50%, rgba(26,107,191,0.25) 0%, transparent 65%);
    pointer-events: none;
}
.form-panel-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, #1a6bbf, #3a9e3a);
}
.form-panel-header h5 {
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 0.2rem;
    position: relative;
}
.form-panel-header p {
    color: rgba(255,255,255,0.55);
    font-size: 0.78rem;
    margin: 0;
    position: relative;
}
.form-panel-body { padding: 1.5rem; }
.form-section-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #374151;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    margin-bottom: 0.7rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.form-section-label i {
    color: #1a6bbf;
    width: 16px;
    text-align: center;
}
.form-divider { border: none; border-top: 1px dashed #e2e8f0; margin: 1.25rem 0; }

/* ── Duration Buttons ────────────────────────────────── */
.dur-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 0.55rem; }
.btn-dur {
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    padding: 0.75rem 0.4rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    background: #fff;
    color: #64748b;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .25s cubic-bezier(0.4,0,0.2,1);
    font-family: 'Outfit', sans-serif;
    width: 100%;
}
.btn-dur i { font-size: 1.1rem; transition: transform .3s ease; }
.btn-dur:hover { border-color: #1a6bbf; color: #1a6bbf; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(26,107,191,0.15); }
.btn-dur:hover i { transform: scale(1.2); }
.btn-check:checked + .btn-dur {
    background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 6px 18px rgba(26,107,191,0.35);
    transform: translateY(-2px);
}
.btn-check:checked + .btn-dur i { transform: scale(1.1); }
/* Ripple on duration btn */
.btn-dur::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    background: rgba(26,107,191,0.2);
    transform: scale(0);
    transition: transform .5s, opacity .5s;
    opacity: 0;
    width: 100%; height: 100%;
    top: 0; left: 0;
}
.btn-check:checked + .btn-dur::after { transform: scale(2); opacity: 0; }

/* ── Budget Options ──────────────────────────────────── */
.budget-option {
    cursor: pointer;
    padding: 0.75rem 1rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.85rem;
    transition: all .22s ease;
    margin-bottom: 0.55rem;
    background: #fff;
    position: relative;
    overflow: hidden;
}
.budget-option:last-child { margin-bottom: 0; }
.budget-option::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #1a6bbf, #3a9e3a);
    border-radius: 3px 0 0 3px;
    transform: scaleY(0);
    transition: transform .22s ease;
}
.budget-option:has(input:checked)::before { transform: scaleY(1); }
.budget-option:has(input:checked) {
    border-color: #1a6bbf;
    background: linear-gradient(135deg, rgba(26,107,191,0.04), rgba(58,158,58,0.03));
}
.budget-option:hover { border-color: #3a9e3a; box-shadow: 0 2px 10px rgba(58,158,58,0.1); }
.budget-radio { accent-color: #1a6bbf; width: 14px; height: 14px; flex-shrink: 0; }
.budget-info { flex: 1; margin-left: 0.6rem; }
.budget-name { font-weight: 700; color: #0f172a; font-size: 0.87rem; line-height: 1.2; }
.budget-desc { color: #94a3b8; font-size: 0.73rem; margin-top: 0.1rem; }
.budget-badge {
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 700;
    border: 1px solid;
    letter-spacing: 0.3px;
}
.bb-green  { background: rgba(58,158,58,.1); color: #3a9e3a; border-color: rgba(58,158,58,.2); }
.bb-blue   { background: rgba(26,107,191,.1); color: #1a6bbf; border-color: rgba(26,107,191,.2); }
.bb-amber  { background: rgba(180,83,9,.1); color: #b45309; border-color: rgba(180,83,9,.2); }

/* ── Category Pills ──────────────────────────────────── */
.cat-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.38rem 0.9rem;
    border-radius: 30px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
    user-select: none;
}
.cat-pill:hover {
    border-color: #1a6bbf;
    color: #1a6bbf;
    background: rgba(26,107,191,0.05);
    transform: translateY(-1px);
}
.btn-check:checked + .cat-pill {
    border-color: transparent;
    background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
    color: #fff;
    box-shadow: 0 4px 12px rgba(26,107,191,0.3);
    transform: translateY(-1px);
}

/* ── Generate Button ─────────────────────────────────── */
.btn-generate {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.85rem 1.2rem;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #1a6bbf 0%, #3a9e3a 100%);
    background-size: 200% 200%;
    color: #fff;
    font-family: 'Outfit', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .3s ease;
    letter-spacing: 0.2px;
    position: relative;
    overflow: hidden;
}
.btn-generate::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0));
    opacity: 0;
    transition: opacity .3s;
}
.btn-generate:hover::before { opacity: 1; }
.btn-generate:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(26,107,191,0.4), 0 4px 12px rgba(58,158,58,0.2);
}
.btn-generate:active { transform: translateY(0); }
.btn-generate .btn-icon { transition: transform .3s ease; }
.btn-generate:hover .btn-icon { transform: translateX(3px); }

/* ── Sticky sidebar ──────────────────────────────────── */
@media (min-width: 992px) {
    .form-sidebar-sticky { position: sticky; top: 80px; }
}

/* ══════════════════════════════════════════════════════
   EMPTY / INTRO STATE
   ══════════════════════════════════════════════════════ */
.intro-card {
    background: #fff;
    border-radius: 24px;
    border: 1px solid rgba(26,107,191,0.1);
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    overflow: hidden;
}
.intro-visual {
    background: linear-gradient(135deg, #0a0f1e 0%, #0d1f38 50%, #0a180f 100%);
    padding: 3rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.intro-visual::before {
    content: '';
    position: absolute; inset: 0;
    background-image: radial-gradient(rgba(26,107,191,0.1) 1px, transparent 1px);
    background-size: 28px 28px;
    pointer-events: none;
}
.intro-orbit {
    position: relative;
    width: 130px; height: 130px;
    margin: 0 auto 1.5rem;
}
.orbit-ring {
    position: absolute; inset: 0;
    border-radius: 50%;
    border: 1.5px dashed;
    animation: orbitSpin 12s linear infinite;
}
.orbit-ring-1 { border-color: rgba(26,107,191,0.3); }
.orbit-ring-2 { inset: 15px; border-color: rgba(58,158,58,0.25); animation-direction: reverse; animation-duration: 8s; }
.orbit-center {
    position: absolute; inset: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(26,107,191,0.2), rgba(58,158,58,0.2));
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(96,165,250,0.3);
}
.orbit-center i {
    font-size: 1.6rem;
    background: linear-gradient(135deg, #60a5fa, #4ade80);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.orbit-dot {
    position: absolute;
    width: 10px; height: 10px;
    border-radius: 50%;
    top: 0; left: 50%;
    transform-origin: 0 65px;
    margin-left: -5px;
}
.orbit-dot-1 { background: #60a5fa; animation: orbitSpin 12s linear infinite; }
.orbit-dot-2 { background: #4ade80; top: 15px; left: calc(50% - 5px); transform-origin: 0 50px; animation: orbitSpin 8s linear infinite reverse; }
@keyframes orbitSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.intro-title {
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    position: relative;
}
.intro-desc {
    color: rgba(255,255,255,0.55);
    font-size: 0.88rem;
    max-width: 400px;
    margin: 0 auto;
    line-height: 1.65;
    position: relative;
}
.intro-features {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 0;
    border-top: 1px solid #f1f5f9;
}
.intro-feat {
    padding: 1.5rem 1.25rem;
    text-align: center;
    border-right: 1px solid #f1f5f9;
    transition: background .2s;
}
.intro-feat:last-child { border-right: none; }
.intro-feat:hover { background: #fafcff; }
.feat-icon-wrap {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    margin: 0 auto 0.75rem;
}
.fw-blue  { background: rgba(26,107,191,0.1); color: #1a6bbf; }
.fw-green { background: rgba(58,158,58,0.1);  color: #3a9e3a; }
.fw-teal  { background: rgba(13,148,148,0.1); color: #0d9488; }
.feat-title { font-size: 0.87rem; font-weight: 700; color: #0f172a; margin-bottom: 0.3rem; }
.feat-desc  { font-size: 0.76rem; color: #94a3b8; line-height: 1.5; }

@media (max-width: 576px) {
    .intro-features { grid-template-columns: 1fr; }
    .intro-feat { border-right: none; border-bottom: 1px solid #f1f5f9; }
}

/* ══════════════════════════════════════════════════════
   RESULTS
   ══════════════════════════════════════════════════════ */
.action-bar {
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 0.75rem;
    margin-bottom: 1.5rem;
}
.action-title {
    font-size: 1.2rem;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0 0 0.2rem;
}
.action-title .ti {
    width: 32px; height: 32px;
    border-radius: 10px;
    background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: 0.8rem;
    flex-shrink: 0;
}
.action-sub { font-size: 0.8rem; color: #64748b; }
.action-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.btn-act {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.45rem 1.1rem;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all .22s ease;
    text-decoration: none;
    cursor: pointer;
    border: none;
    font-family: 'Outfit', sans-serif;
}
.btn-act-ghost {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    color: #475569;
}
.btn-act-ghost:hover { border-color: #1a6bbf; color: #1a6bbf; transform: translateY(-1px); }
.btn-act-wa { background: #16a34a; color: #fff; border: 1.5px solid transparent; }
.btn-act-wa:hover { background: #15803d; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
.btn-act-map {
    background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
    color: #fff;
    border: 1.5px solid transparent;
}
.btn-act-map:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26,107,191,0.35); color: #fff; }

/* ── Budget Summary ──────────────────────────────────── */
.budget-card {
    border-radius: 20px;
    background: #0a0f1e;
    border: 1px solid rgba(26,107,191,0.2);
    overflow: hidden;
    margin-bottom: 1.75rem;
    position: relative;
}
.budget-card::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 70% 80% at 80% 50%, rgba(26,107,191,0.15) 0%, transparent 65%),
        radial-gradient(ellipse 40% 60% at 10% 80%, rgba(58,158,58,0.1) 0%, transparent 60%);
    pointer-events: none;
}
.budget-card-inner { padding: 1.75rem; position: relative; z-index: 1; }
.bc-label {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: rgba(26,107,191,0.18);
    border: 1px solid rgba(96,165,250,0.25);
    border-radius: 30px;
    padding: 0.25rem 0.85rem;
    font-size: 0.72rem;
    font-weight: 700;
    color: #93c5fd;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 0.65rem;
}
.bc-amount {
    font-size: clamp(1.9rem, 4vw, 2.5rem);
    font-weight: 800;
    background: linear-gradient(135deg, #60a5fa 0%, #4ade80 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.15;
    margin-bottom: 0.4rem;
}
.bc-note { color: rgba(255,255,255,0.4); font-size: 0.77rem; }
.bc-divider { border: none; border-top: 1px solid rgba(255,255,255,0.08); margin: 1.2rem 0; }
.bc-row {
    display: flex; justify-content: space-between;
    font-size: 0.82rem;
    margin-bottom: 0.55rem;
    color: rgba(255,255,255,0.5);
    align-items: center;
}
.bc-row:last-child { margin-bottom: 0; }
.bc-row .val { color: #fff; font-weight: 600; }
.bc-row .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-blue  { background: #60a5fa; }
.dot-green { background: #4ade80; }
.dot-amber { background: #fbbf24; }

/* ── Day Cards ───────────────────────────────────────── */
.day-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e9eef6;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 1.25rem;
    transition: all .3s cubic-bezier(0.4,0,0.2,1);
}
.day-card:last-child { margin-bottom: 0; }
.day-card:hover {
    box-shadow: 0 12px 36px rgba(26,107,191,0.12);
    transform: translateY(-3px);
    border-color: rgba(26,107,191,0.2);
}
.day-header {
    background: linear-gradient(135deg, #0d1529 0%, #0f2135 60%, #0a1e10 100%);
    padding: 1rem 1.35rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}
.day-header::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #1a6bbf, #3a9e3a);
}
.day-header::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(90deg, transparent, rgba(26,107,191,0.06));
    pointer-events: none;
}
.day-number {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    position: relative;
}
.day-num-badge {
    width: 34px; height: 34px;
    border-radius: 10px;
    background: rgba(26,107,191,0.2);
    border: 1px solid rgba(96,165,250,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem;
    font-weight: 800;
    color: #60a5fa;
    flex-shrink: 0;
}
.day-num-label {
    font-size: 0.92rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}
.day-num-sub { font-size: 0.72rem; color: rgba(255,255,255,0.4); letter-spacing: 0.3px; }
.day-pill {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 30px;
    padding: 0.2rem 0.75rem;
    font-size: 0.68rem;
    font-weight: 700;
    color: rgba(255,255,255,0.6);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    position: relative;
}

/* ── Timeline ────────────────────────────────────────── */
.day-body { padding: 0.5rem 0; }
.timeline-slot {
    display: flex;
    gap: 1rem;
    padding: 1.1rem 1.35rem;
    transition: background .2s ease;
    align-items: flex-start;
    position: relative;
}
.timeline-slot:not(:last-child) {
    border-bottom: 1px dashed #edf0f7;
}
.timeline-slot:hover { background: #fafcff; }

/* Time pill */
.t-pill {
    flex-shrink: 0;
    min-width: 108px;
    display: flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.38rem 0.7rem;
    border-radius: 30px;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    margin-top: 4px;
}
.tp-pagi    { background: rgba(234,179,8,0.1);   color: #92400e; border: 1px solid rgba(234,179,8,0.2); }
.tp-siang   { background: rgba(239,68,68,0.08);  color: #b91c1c; border: 1px solid rgba(239,68,68,0.18); }
.tp-sore    { background: rgba(26,107,191,0.1);  color: #1e40af; border: 1px solid rgba(26,107,191,0.2); }
.tp-malam   { background: rgba(58,158,58,0.1);   color: #166534; border: 1px solid rgba(58,158,58,0.2); }

/* Slot content with left accent */
.slot-body {
    flex: 1;
    padding-left: 1rem;
    border-left: 3px solid;
}
.slot-body.sb-pagi  { border-color: #f59e0b; }
.slot-body.sb-siang { border-color: #ef4444; }
.slot-body.sb-sore  { border-color: #1a6bbf; }
.slot-body.sb-malam { border-color: #3a9e3a; }

.slot-label {
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 0.35rem;
}
.slot-name {
    font-size: 0.97rem;
    font-weight: 700;
    color: #0f172a;
    text-decoration: none;
    display: block;
    margin-bottom: 0.3rem;
    line-height: 1.3;
    transition: color .2s;
}
.slot-name:hover { color: #1a6bbf; }
.slot-addr {
    display: flex;
    align-items: flex-start;
    gap: 0.35rem;
    font-size: 0.78rem;
    color: #94a3b8;
    margin-bottom: 0.55rem;
    line-height: 1.4;
}
.slot-addr i { color: #ef4444; font-size: 0.72rem; margin-top: 2px; flex-shrink: 0; }
.slot-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; align-items: center; }

/* Tag variations */
.s-tag {
    display: inline-flex; align-items: center; gap: 0.25rem;
    padding: 0.22rem 0.65rem;
    border-radius: 30px;
    font-size: 0.72rem;
    font-weight: 600;
    border: 1px solid;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.st-price  { background: rgba(26,107,191,0.07); color: #1a6bbf; border-color: rgba(26,107,191,0.18); }
.st-food   { background: rgba(239,68,68,0.07);  color: #b91c1c; border-color: rgba(239,68,68,0.18); }
.st-rating { background: rgba(234,179,8,0.08);  color: #92400e; border-color: rgba(234,179,8,0.2); }
.st-dir    { background: rgba(58,158,58,0.07);  color: #166534; border-color: rgba(58,158,58,0.2); }
.st-hotel  { background: rgba(58,158,58,0.07);  color: #166534; border-color: rgba(58,158,58,0.2); }
.st-dir:hover { background: #3a9e3a; color: #fff; border-color: transparent; transform: translateY(-1px); }

/* ── No destination placeholder ─────────────────────── */
.slot-empty-title { color: #334155; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.3rem; }
.slot-empty-desc  { color: #94a3b8; font-size: 0.8rem; line-height: 1.5; }

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 576px) {
    .budget-card-inner { padding: 1.25rem; }
    .bc-amount { font-size: 1.75rem; }
    .timeline-slot { padding: 0.9rem 1rem; gap: 0.75rem; }
    .t-pill { min-width: 88px; font-size: 0.62rem; }
    .action-bar { flex-direction: column; align-items: flex-start; }
}

/* ── Print ───────────────────────────────────────────── */
@media print {
    body, .itin-layout { background: #fff !important; }
    .navbar, footer, .print-hide, .itin-hero { display: none !important; }
    .day-card { break-inside: avoid; box-shadow: none !important; border: 1px solid #ddd !important; transform: none !important; }
    .budget-card { background: #0a0f1e !important; -webkit-print-color-adjust: exact; }
}

/* ── Results section: CSS fade-in (tidak pakai GSAP agar tidak stuck) ── */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
}
.budget-card {
    animation: fadeInUp 0.55s ease both;
    animation-delay: 0.05s;
}
.day-card {
    animation: fadeInUp 0.55s ease both;
}
.day-card:nth-child(1) { animation-delay: 0.1s; }
.day-card:nth-child(2) { animation-delay: 0.22s; }
.day-card:nth-child(3) { animation-delay: 0.34s; }
.action-bar { animation: fadeInUp 0.45s ease both; animation-delay: 0.02s; }

/* ── GSAP ready (initial hidden states handled via JS) ── */
.gsap-hidden { opacity: 0; }
</style>

<!-- ══ HERO SECTION ══════════════════════════════════════ -->
<section class="itin-hero">
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>
    <div class="hero-grid-lines"></div>
    <div class="hero-inner">
        <div class="container">
            <nav aria-label="breadcrumb" class="hero-breadcrumb mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Beranda</a></li>
                    <li class="breadcrumb-item active">Itinerary Planner</li>
                </ol>
            </nav>
            <div class="hero-eyebrow" id="hero-eyebrow">
                <i class="fas fa-magic"></i>
                PERENCANA OTOMATIS
            </div>
            <h1 class="hero-title" id="hero-title">
                Smart Itinerary<br>
                <span class="grad-text">Planner Bogor</span>
            </h1>
            <p class="hero-subtitle" id="hero-subtitle">
                Rencanakan liburan impianmu di Bogor secara otomatis — pilih durasi, budget, dan preferensi tempat, kami yang atur sisanya.
            </p>
            <div class="hero-stats" id="hero-stats">
                <span class="stat-chip"><i class="fas fa-map-marked-alt"></i> 20+ Destinasi Wisata</span>
                <span class="stat-chip"><i class="fas fa-utensils"></i> 5 Kuliner Terbaik</span>
                <a href="<?= BASE_URL ?>/itinerary/builder" class="stat-chip text-white" style="background:linear-gradient(135deg,#1a6bbf,#3a9e3a);border:none;text-decoration:none;font-weight:700;">
                    <i class="fas fa-tools text-white"></i> Buat Itinerary Manual (Drag &amp; Drop)
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ══ MAIN LAYOUT ═══════════════════════════════════════ -->
<div class="itin-layout">
    <div class="container">

        <!-- Banner Promosi Manual Builder -->
        <div class="mb-4 p-3.5" style="background:linear-gradient(135deg, #0d1529 0%, #1a2a4a 100%);border-radius:18px;border:1px solid rgba(96,165,250,0.3);box-shadow:0 4px 20px rgba(0,0,0,0.08);" data-aos="fade-up">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg, #1a6bbf, #3a9e3a);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;flex-shrink:0;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <h5 style="color:#fff;font-weight:800;font-size:1.05rem;margin:0 0 0.2rem;">
                            Ingin Susun Rencana Perjalanan Sendiri?
                        </h5>
                        <p style="color:rgba(255,255,255,0.65);font-size:0.85rem;margin:0;">
                            Coba fitur <strong>Itinerary Builder Manual</strong> dengan sistem Drag &amp; Drop interaktif dan visualisasi rute di peta!
                        </p>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/itinerary/builder" class="btn text-white font-weight-bold px-4 py-2" style="background:linear-gradient(135deg, #1a6bbf, #3a9e3a);border-radius:12px;font-weight:700;font-size:0.9rem;white-space:nowrap;">
                    <i class="fas fa-magic me-1.5"></i> Buka Itinerary Builder
                </a>
            </div>
        </div>
        <div class="row g-4 align-items-start">

            <!-- ═══ LEFT: FORM PANEL ════════════════════ -->
            <div class="col-lg-4 form-sidebar-sticky" id="formSidebar">
                <div class="form-panel">
                    <div class="form-panel-header">
                        <h5>
                            <i class="fas fa-sliders-h me-2"></i>
                            Atur Rencana Liburanmu
                        </h5>
                        <p>Sesuaikan preferensi perjalanan di bawah ini</p>
                    </div>
                    <div class="form-panel-body">
                        <form action="<?= BASE_URL ?>/itinerary" method="GET" id="itineraryForm">
                            <input type="hidden" name="generate" value="1">

                            <!-- 1. Durasi -->
                            <div class="mb-4">
                                <div class="form-section-label">
                                    <i class="fas fa-calendar-day"></i>
                                    1. Durasi Liburan
                                </div>
                                <div class="dur-grid">
                                    <div>
                                        <input type="radio" class="btn-check" name="duration" id="dur1" value="1" <?= $duration == 1 ? 'checked' : '' ?>>
                                        <label class="btn-dur" for="dur1">
                                            <i class="fas fa-sun"></i>1 Hari
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" class="btn-check" name="duration" id="dur2" value="2" <?= $duration == 2 ? 'checked' : '' ?>>
                                        <label class="btn-dur" for="dur2">
                                            <i class="fas fa-moon"></i>2H 1M
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" class="btn-check" name="duration" id="dur3" value="3" <?= $duration == 3 ? 'checked' : '' ?>>
                                        <label class="btn-dur" for="dur3">
                                            <i class="fas fa-star"></i>3H 2M
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="form-divider">

                            <!-- 2. Budget -->
                            <div class="mb-4">
                                <div class="form-section-label">
                                    <i class="fas fa-wallet"></i>
                                    2. Estimasi Budget
                                </div>
                                <label class="budget-option">
                                    <input type="radio" class="budget-radio" name="budget" value="ekonomis" <?= $budget === 'ekonomis' ? 'checked' : '' ?>>
                                    <div class="budget-info">
                                        <div class="budget-name">Ekonomis</div>
                                        <div class="budget-desc">Hemat &amp; Terjangkau</div>
                                    </div>
                                    <span class="budget-badge bb-green">Hemat</span>
                                </label>
                                <label class="budget-option">
                                    <input type="radio" class="budget-radio" name="budget" value="standar" <?= $budget === 'standar' ? 'checked' : '' ?>>
                                    <div class="budget-info">
                                        <div class="budget-name">
                                            Standar
                                            <span style="color:#1a6bbf;font-weight:600;font-size:0.73rem;"> ★ Rekomendasi</span>
                                        </div>
                                        <div class="budget-desc">Seimbang &amp; Nyaman</div>
                                    </div>
                                    <span class="budget-badge bb-blue">Standar</span>
                                </label>
                                <label class="budget-option">
                                    <input type="radio" class="budget-radio" name="budget" value="mewah" <?= $budget === 'mewah' ? 'checked' : '' ?>>
                                    <div class="budget-info">
                                        <div class="budget-name">Mewah / Premium</div>
                                        <div class="budget-desc">Fasilitas &amp; Hotel Terbaik</div>
                                    </div>
                                    <span class="budget-badge bb-amber">Premium</span>
                                </label>
                            </div>

                            <hr class="form-divider">

                            <!-- 3. Kategori -->
                            <div class="mb-4">
                                <div class="form-section-label">
                                    <i class="fas fa-map-signs"></i>
                                    3. Preferensi Tempat
                                    <span style="font-weight:500;color:#94a3b8;font-size:0.72rem;text-transform:none;letter-spacing:0;">(Opsional)</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($categories as $cat): ?>
                                    <?php $isChecked = in_array($cat['slug'], $selectedCats); ?>
                                    <input type="checkbox" class="btn-check" name="categories[]"
                                           id="cat-<?= $cat['id'] ?>"
                                           value="<?= htmlspecialchars($cat['slug']) ?>"
                                           <?= $isChecked ? 'checked' : '' ?>>
                                    <label class="cat-pill" for="cat-<?= $cat['id'] ?>">
                                        <i class="fas fa-<?= htmlspecialchars($cat['icon'] ?? 'map-pin') ?>"></i>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Generate Button -->
                            <button type="submit" id="btnGenerate" class="btn-generate">
                                <i class="fas fa-route btn-icon"></i>
                                Buat Itinerary Otomatis
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ═══ RIGHT: OUTPUT ═══════════════════════ -->
            <div class="col-lg-8">

                <?php if (!$hasGenerated || empty($itinerary)): ?>
                <!-- ── Intro State ─────────────────────── -->
                <div class="intro-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="intro-visual">
                        <div class="intro-orbit">
                            <div class="orbit-ring orbit-ring-1"></div>
                            <div class="orbit-ring orbit-ring-2"></div>
                            <div class="orbit-dot orbit-dot-1"></div>
                            <div class="orbit-dot orbit-dot-2"></div>
                            <div class="orbit-center">
                                <i class="fas fa-route"></i>
                            </div>
                        </div>
                        <h3 class="intro-title">Siap Liburan ke Bogor?</h3>
                        <p class="intro-desc">
                            Isi preferensi di panel kiri, lalu klik
                            <strong style="color:#4ade80;">"Buat Itinerary Otomatis"</strong>
                            untuk mendapatkan rencana perjalanan lengkap beserta estimasi biaya.
                        </p>
                    </div>
                    <div class="intro-features">
                        <div class="intro-feat">
                            <div class="feat-icon-wrap fw-blue">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="feat-title">Cepat &amp; Otomatis</div>
                            <div class="feat-desc">Susunan rute dan jam operasional diatur efisien secara instan.</div>
                        </div>
                        <div class="intro-feat">
                            <div class="feat-icon-wrap fw-green">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="feat-title">Estimasi Budget</div>
                            <div class="feat-desc">Tiket, hotel, dan makan dihitung langsung sesuai pilihanmu.</div>
                        </div>
                        <div class="intro-feat">
                            <div class="feat-icon-wrap fw-teal">
                                <i class="fas fa-share-alt"></i>
                            </div>
                            <div class="feat-title">Mudah Dibagi</div>
                            <div class="feat-desc">Cetak atau bagikan ke keluarga via WhatsApp dalam sekali klik.</div>
                        </div>
                    </div>
                </div>

                <?php else: ?>
                <!-- ── Generated Results ───────────────── -->
                <div id="printableItinerary">

                    <!-- Action Bar -->
                    <div class="action-bar print-hide" data-aos="fade-up">
                        <div>
                            <div class="action-title">
                                <div class="ti"><i class="fas fa-map-marked-alt"></i></div>
                                Rencana Perjalananmu
                            </div>
                            <div class="action-sub">
                                <i class="fas fa-calendar-check me-1" style="color:#3a9e3a;"></i>
                                <?= $itinerary['duration'] ?> Hari &nbsp;·&nbsp;
                                Budget <?= ucfirst($itinerary['budget_tier']) ?>
                            </div>
                        </div>
                        <div class="action-btns">
                            <button onclick="window.print()" class="btn-act btn-act-ghost">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                            <?php
                            $waText = "Halo! Ini Rencana Liburan ke Bogor saya (" . $itinerary['duration'] . " Hari): " . BASE_URL . "/itinerary?" . http_build_query($_GET);
                            $waUrl  = "https://api.whatsapp.com/send?text=" . urlencode($waText);
                            ?>
                            <a href="<?= $waUrl ?>" target="_blank" class="btn-act btn-act-wa">
                                <i class="fab fa-whatsapp"></i> Bagikan
                            </a>
                            <a href="<?= BASE_URL ?>/peta" class="btn-act btn-act-map">
                                <i class="fas fa-map-marked-alt"></i> Peta
                            </a>
                        </div>
                    </div>

                    <!-- Budget Summary Card -->
                    <div class="budget-card" data-aos="fade-up" data-aos-delay="80">
                        <div class="budget-card-inner">
                            <div class="row align-items-center g-0">
                                <div class="col-md-6">
                                    <div class="bc-label">
                                        <i class="fas fa-receipt"></i>
                                        Estimasi Total / Orang
                                    </div>
                                    <div class="bc-amount" id="countupTotal"
                                         data-val="<?= $itinerary['grand_total'] ?>">
                                        Rp <?= number_format($itinerary['grand_total'], 0, ',', '.') ?>
                                    </div>
                                    <p class="bc-note">Mencakup tiket wisata, penginapan, dan estimasi makan harian.</p>
                                </div>
                                <div class="col-md-6 ps-md-4 mt-3 mt-md-0">
                                    <div class="bc-divider d-md-none"></div>
                                    <div style="border-left:1px solid rgba(255,255,255,0.08);padding-left:1.5rem;" class="d-none d-md-block">
                                        <div class="bc-row">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="dot dot-blue"></span>Tiket Masuk Wisata
                                            </span>
                                            <span class="val">Rp <?= number_format($itinerary['total_ticket'], 0, ',', '.') ?></span>
                                        </div>
                                        <?php if ($itinerary['total_hotel'] > 0): ?>
                                        <div class="bc-row">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="dot dot-green"></span>Penginapan / Hotel
                                            </span>
                                            <span class="val">Rp <?= number_format($itinerary['total_hotel'], 0, ',', '.') ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="bc-row">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="dot dot-amber"></span>Makan &amp; Jajan
                                            </span>
                                            <span class="val">Rp <?= number_format($itinerary['total_meal'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                    <!-- Mobile no-border version -->
                                    <div class="d-md-none">
                                        <div class="bc-row">
                                            <span class="d-flex align-items-center gap-2"><span class="dot dot-blue"></span>Tiket Masuk</span>
                                            <span class="val">Rp <?= number_format($itinerary['total_ticket'], 0, ',', '.') ?></span>
                                        </div>
                                        <?php if ($itinerary['total_hotel'] > 0): ?>
                                        <div class="bc-row">
                                            <span class="d-flex align-items-center gap-2"><span class="dot dot-green"></span>Penginapan</span>
                                            <span class="val">Rp <?= number_format($itinerary['total_hotel'], 0, ',', '.') ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="bc-row">
                                            <span class="d-flex align-items-center gap-2"><span class="dot dot-amber"></span>Makan &amp; Jajan</span>
                                            <span class="val">Rp <?= number_format($itinerary['total_meal'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- decorative bar -->
                        <div style="height:3px;background:linear-gradient(90deg,#1a6bbf,#3a9e3a);"></div>
                    </div>

                    <!-- ── Day Cards ──────────────────── -->
                    <?php foreach ($itinerary['days'] as $di => $day): ?>
                    <div class="day-card"
                         data-aos="fade-up"
                         data-aos-delay="<?= ($di * 80) + 120 ?>">

                        <!-- Day Header -->
                        <div class="day-header">
                            <div class="day-number">
                                <div class="day-num-badge"><?= $day['day_number'] ?></div>
                                <div>
                                    <div class="day-num-label">
                                        <i class="fas fa-calendar-day me-1" style="color:#60a5fa;font-size:.85rem;"></i>
                                        HARI KE-<?= $day['day_number'] ?>
                                    </div>
                                    <div class="day-num-sub">Agenda Lengkap Perjalanan</div>
                                </div>
                            </div>
                            <span class="day-pill">
                                <i class="fas fa-calendar-check me-1"></i> Hari <?= $day['day_number'] ?>
                            </span>
                        </div>

                        <!-- Timeline Body -->
                        <div class="day-body">

                            <!-- PAGI -->
                            <?php if ($day['pagi']): ?>
                            <div class="timeline-slot">
                                <span class="t-pill tp-pagi">
                                    <i class="fas fa-sun"></i>
                                    08:00–11:30
                                </span>
                                <div class="slot-body sb-pagi">
                                    <div class="slot-label"><i class="fas fa-sun me-1.5" style="color:#f59e0b;"></i> WISATA PAGI</div>
                                    <a href="<?= BASE_URL ?>/destinations/<?= $day['pagi']['slug'] ?>"
                                       class="slot-name" target="_blank">
                                        <?= htmlspecialchars($day['pagi']['name']) ?>
                                    </a>
                                    <div class="slot-addr">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= htmlspecialchars($day['pagi']['address'] ?? 'Bogor, Jawa Barat') ?>
                                    </div>
                                    <div class="slot-tags">
                                        <span class="s-tag st-price">
                                            <i class="fas fa-ticket-alt" style="font-size:.65rem;"></i>
                                            <?= formatRupiah($day['pagi']['ticket_price_weekday'] ?? $day['pagi']['ticket_price']) ?>
                                        </span>
                                        <span class="s-tag st-rating">
                                            <i class="fas fa-star" style="font-size:.65rem;"></i>
                                            <?= number_format((float)$day['pagi']['avg_rating'], 1) ?>
                                        </span>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?= $day['pagi']['latitude'] ?>,<?= $day['pagi']['longitude'] ?>"
                                           target="_blank" class="s-tag st-dir">
                                            <i class="fas fa-directions" style="font-size:.65rem;"></i>
                                            Petunjuk Arah
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- MAKAN SIANG -->
                            <div class="timeline-slot">
                                <span class="t-pill tp-siang">
                                    <i class="fas fa-utensils"></i>
                                    12:00–13:30
                                </span>
                                <div class="slot-body sb-siang">
                                    <div class="slot-label"><i class="fas fa-utensils me-1.5" style="color:#ef4444;"></i> ISTIRAHAT &amp; MAKAN SIANG</div>
                                    <?php if ($day['kuliner']): ?>
                                    <a href="<?= BASE_URL ?>/destinations/<?= $day['kuliner']['slug'] ?>"
                                       class="slot-name" target="_blank">
                                        <?= htmlspecialchars($day['kuliner']['name']) ?>
                                    </a>
                                    <div class="slot-addr">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= htmlspecialchars($day['kuliner']['address'] ?? 'Pusat Kuliner Bogor') ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="slot-empty-title">Wisata Kuliner Khas Bogor</div>
                                    <div class="slot-empty-desc">Nikmati Soto Mie Bogor, Asinan Bogor, atau Restoran lokal terdekat di sekitar lokasi wisata pagi.</div>
                                    <?php endif; ?>
                                    <div class="slot-tags mt-1">
                                        <span class="s-tag st-food">
                                            <i class="fas fa-utensils" style="font-size:.65rem;"></i>
                                            Est. Rp 35.000 – 60.000 / porsi
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- SORE -->
                            <?php if ($day['sore']): ?>
                            <div class="timeline-slot">
                                <span class="t-pill tp-sore">
                                    <i class="fas fa-cloud-sun"></i>
                                    14:00–17:30
                                </span>
                                <div class="slot-body sb-sore">
                                    <div class="slot-label"><i class="fas fa-cloud-sun me-1.5" style="color:#1a6bbf;"></i> WISATA SORE</div>
                                    <a href="<?= BASE_URL ?>/destinations/<?= $day['sore']['slug'] ?>"
                                       class="slot-name" target="_blank">
                                        <?= htmlspecialchars($day['sore']['name']) ?>
                                    </a>
                                    <div class="slot-addr">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= htmlspecialchars($day['sore']['address'] ?? 'Bogor, Jawa Barat') ?>
                                    </div>
                                    <div class="slot-tags">
                                        <span class="s-tag st-price">
                                            <i class="fas fa-ticket-alt" style="font-size:.65rem;"></i>
                                            <?= formatRupiah($day['sore']['ticket_price_weekday'] ?? $day['sore']['ticket_price']) ?>
                                        </span>
                                        <span class="s-tag st-rating">
                                            <i class="fas fa-star" style="font-size:.65rem;"></i>
                                            <?= number_format((float)$day['sore']['avg_rating'], 1) ?>
                                        </span>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?= $day['sore']['latitude'] ?>,<?= $day['sore']['longitude'] ?>"
                                           target="_blank" class="s-tag st-dir">
                                            <i class="fas fa-directions" style="font-size:.65rem;"></i>
                                            Petunjuk Arah
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- HOTEL / MALAM -->
                            <?php if ($day['hotel']): ?>
                            <div class="timeline-slot">
                                <span class="t-pill tp-malam">
                                    <i class="fas fa-bed"></i>
                                    Malam
                                </span>
                                <div class="slot-body sb-malam">
                                    <div class="slot-label"><i class="fas fa-moon me-1.5" style="color:#3a9e3a;"></i> PENGINAPAN / HOTEL</div>
                                    <a href="<?= BASE_URL ?>/hotels/<?= $day['hotel']['id'] ?>"
                                       class="slot-name" target="_blank">
                                        <?= htmlspecialchars($day['hotel']['name']) ?>
                                    </a>
                                    <div class="slot-addr">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Dekat <?= htmlspecialchars($day['hotel']['destination_name']) ?>
                                    </div>
                                    <div class="slot-tags">
                                        <span class="s-tag st-hotel">
                                            <i class="fas fa-bed" style="font-size:.65rem;"></i>
                                            Mulai Rp <?= number_format($day['hotel']['price_start'], 0, ',', '.') ?> / malam
                                        </span>
                                        <span class="s-tag st-rating">
                                            <?php for ($s = 0; $s < (int)$day['hotel']['star_rating']; $s++): ?>
                                            <i class="fas fa-star" style="font-size:.6rem;"></i>
                                            <?php endfor; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php endforeach; ?>

                </div><!-- /#printableItinerary -->
                <?php endif; ?>

            </div><!-- /.col-lg-8 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>

<!-- ══════════════════════════════════════════════════════
     SCRIPTS — GSAP + CountUp Animations
     ══════════════════════════════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── GSAP Hero Animation ──────────────────────────── */
    if (typeof gsap !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        // Hero elements stagger in
        const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });
        heroTl
            .from('#hero-eyebrow',  { y: 20, opacity: 0, duration: 0.5 })
            .from('#hero-title',    { y: 30, opacity: 0, duration: 0.65 }, '-=0.25')
            .from('#hero-subtitle', { y: 20, opacity: 0, duration: 0.55 }, '-=0.3')
            .from('#hero-stats .stat-chip', {
                y: 15, opacity: 0, duration: 0.45,
                stagger: 0.1
            }, '-=0.2');

        // Form sidebar slide in from left
        gsap.from('#formSidebar', {
            x: -30, opacity: 0, duration: 0.7, ease: 'power3.out',
            scrollTrigger: {
                trigger: '#formSidebar',
                start: 'top 85%',
                once: true
            }
        });

        /* GSAP hanya untuk hero — hasil itinerary pakai CSS animation */
    }

    /* ── CountUp for budget total ─────────────────────── */
    const countEl = document.getElementById('countupTotal');
    if (countEl && typeof CountUp !== 'undefined') {
        const rawVal = parseInt(countEl.getAttribute('data-val') || '0', 10);

        // Observe when element is visible
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const cu = new CountUp.CountUp(countEl, rawVal, {
                        duration: 1.8,
                        separator: '.',
                        decimal: ',',
                        prefix: 'Rp '
                    });
                    if (!cu.error) cu.start();
                    observer.unobserve(countEl);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(countEl);
    }

    /* ── Duration button micro-interaction ─────────────── */
    document.querySelectorAll('.btn-check').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (typeof gsap !== 'undefined') {
                const label = document.querySelector('label[for="' + this.id + '"]');
                if (label) {
                    gsap.fromTo(label,
                        { scale: 0.92 },
                        { scale: 1, duration: 0.35, ease: 'back.out(2)' }
                    );
                }
            }
        });
    });

    /* ── Generate button loading state ─────────────────── */
    var form = document.getElementById('itineraryForm');
    var btn  = document.getElementById('btnGenerate');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyusun Rencana...';
            btn.disabled = true;
            btn.style.opacity = '0.85';
        });
    }

    /* ── AOS reinit (safety) ──────────────────────────── */
    if (typeof AOS !== 'undefined') {
        AOS.refresh();
    }

});
</script>
