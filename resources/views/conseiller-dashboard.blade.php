{{-- 
  resources/views/conseiller/dashboard.blade.php
  Static Conseiller dashboard — extends the KiteaCall layout.
  Adjust the @extends path to match where you saved the layout file.
--}}
@extends('layouts.app')

@section('title', 'My Dashboard — KiteaCall')
@section('topbar_title', 'My Performance')
@section('topbar_subtitle', 'Welcome back,')
@section('content')

{{-- ═══════════════════════════════════════════════════════════
     PAGE-SPECIFIC STYLES
══════════════════════════════════════════════════════════════ --}}
<style>
  /* ─── Welcome / pending signature banner ─── */
  .welcome-banner {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 22px 26px;
    margin-bottom: 24px;
    border-radius: var(--radius);
    background: linear-gradient(135deg, #1A0204 0%, #2D0008 50%, #3D0010 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(139,0,0,0.18);
  }
  .welcome-banner::before {
    content: '';
    position: absolute;
    top: -40%; right: -10%;
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(245,166,35,0.18) 0%, transparent 70%);
    pointer-events: none;
  }
  .welcome-banner::after {
    content: '';
    position: absolute;
    bottom: -50%; left: -5%;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(192,21,42,0.25) 0%, transparent 70%);
    pointer-events: none;
  }
  .wb-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 6px 20px rgba(245,166,35,0.4);
    z-index: 1;
  }
  .wb-icon svg { width: 26px; height: 26px; color: #fff; }
  .wb-content { flex: 1; z-index: 1; min-width: 0; }
  .wb-title {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 18px;
    margin-bottom: 4px;
    letter-spacing: -0.3px;
  }
  .wb-msg {
    font-size: 13.5px;
    color: rgba(255,255,255,0.7);
    line-height: 1.5;
  }
  .wb-msg strong { color: var(--gold-light); font-weight: 600; }
  .wb-cta {
    padding: 11px 20px;
    background: var(--gold);
    color: #1A0204;
    border: none;
    border-radius: 10px;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.3px;
    cursor: pointer;
    transition: var(--transition);
    z-index: 1;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(245,166,35,0.4);
  }
  .wb-cta:hover {
    background: var(--gold-light);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245,166,35,0.55);
  }

  /* ─── Secondary stats row (KO / best / lowest) ─── */
  .secondary-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 28px;
  }
  .stat-mini {
    background: var(--white);
    border: 1px solid rgba(139,0,0,0.06);
    border-radius: var(--radius);
    padding: 16px 18px;
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition);
  }
  .stat-mini:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }

  .stat-mini-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 17px;
  }
  .stat-mini.best  .stat-mini-icon { background: rgba(122,140,114,0.15); color: #4a6b42; }
  .stat-mini.low   .stat-mini-icon { background: rgba(192,21,42,0.1);    color: var(--walnut-mid); }
  .stat-mini.ko    .stat-mini-icon { background: rgba(245,166,35,0.15);  color: #C07A00; }

  .stat-mini-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    color: var(--text-muted);
  }
  .stat-mini-value {
    font-family: 'Syne', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.5px;
    line-height: 1.1;
    margin-top: 3px;
  }
  .stat-mini-value small { font-size: 13px; color: var(--text-muted); font-weight: 500; }

  /* ─── Filter bar ─── */
  .filters-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    margin-bottom: 20px;
    background: var(--white);
    border: 1px solid rgba(139,0,0,0.06);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
  }
  .filter-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.7px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-right: 4px;
  }
  .filter-input, .filter-select {
    appearance: none;
    -webkit-appearance: none;
    border: 1px solid rgba(139,0,0,0.1);
    background: var(--cream);
    color: var(--text-dark);
    font-family: inherit;
    font-size: 13px;
    font-weight: 500;
    padding: 8px 12px;
    padding-right: 30px;
    border-radius: 9px;
    cursor: pointer;
    transition: var(--transition);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238B0000' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 11px center;
  }
  .filter-input { padding-right: 12px; background-image: none; cursor: text; }
  .filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: var(--walnut-mid);
    background: var(--cream-deep);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.1);
  }
  .filter-clear {
    margin-left: auto;
    padding: 8px 14px;
    border: 1px solid transparent;
    background: transparent;
    color: var(--walnut-mid);
    font-weight: 600;
    font-size: 12.5px;
    border-radius: 9px;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    display: inline-block;
  }
  .filter-clear:hover { background: var(--cream-deep); border-color: rgba(192,21,42,0.2); }

  /* ─── Score range slider ─── */
  .range-wrap { display: flex; align-items: center; gap: 8px; }
  .range-label-min, .range-label-max {
    font-size: 11.5px;
    font-weight: 700;
    color: var(--walnut-mid);
    min-width: 24px;
    text-align: center;
  }
  input[type="range"] {
    -webkit-appearance: none;
    appearance: none;
    width: 90px;
    height: 5px;
    background: rgba(139,0,0,0.12);
    border-radius: 3px;
    outline: none;
  }
  input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 14px; height: 14px;
    border-radius: 50%;
    background: var(--walnut-mid);
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(192,21,42,0.4);
    transition: transform 0.15s;
  }
  input[type="range"]::-webkit-slider-thumb:hover { transform: scale(1.2); }
  input[type="range"]::-moz-range-thumb {
    width: 14px; height: 14px;
    border-radius: 50%;
    background: var(--walnut-mid);
    cursor: pointer;
    border: 2px solid #fff;
  }

  /* ─── Call-type pill ─── */
  .call-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
  }
  .call-in  { background: rgba(122,140,114,0.1); color: #4a6b42; }
  .call-out { background: rgba(192,21,42,0.08); color: var(--walnut-mid); }

  /* ─── KO chip ─── */
  .ko-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 7px;
    border-radius: 5px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.3px;
  }
  .ko-yes { background: rgba(139,0,0,0.1); color: var(--walnut); border: 1px solid rgba(139,0,0,0.2); }
  .ko-no  { background: rgba(122,140,114,0.1); color: #4a6b42; border: 1px solid rgba(122,140,114,0.18); }

  /* ─── Icon action buttons (table) ─── */
  .icon-actions { display: inline-flex; gap: 4px; }
  .icon-btn {
    width: 28px; height: 28px;
    border-radius: 7px;
    border: 1px solid rgba(139,0,0,0.1);
    background: var(--cream);
    color: var(--text-mid);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
  }
  .icon-btn svg { width: 13px; height: 13px; }
  .icon-btn:hover {
    background: var(--walnut-mid);
    color: #fff;
    border-color: var(--walnut-mid);
    transform: translateY(-1px);
  }
  .icon-btn.sign-btn {
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 2px 6px rgba(245,166,35,0.35);
  }
  .icon-btn.sign-btn:hover {
    background: linear-gradient(135deg, var(--gold-light), var(--gold));
    transform: translateY(-1px) scale(1.05);
  }

  /* ─── Bottom grid: notifications + export ─── */
  .bottom-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 20px;
    margin-bottom: 32px;
  }

  .notif-list { padding: 8px 0; max-height: 360px; overflow-y: auto; }
  .notif-list::-webkit-scrollbar { width: 6px; }
  .notif-list::-webkit-scrollbar-thumb { background: rgba(139,0,0,0.15); border-radius: 3px; }

  .notif-item {
    display: flex;
    gap: 12px;
    padding: 14px 24px;
    border-left: 3px solid transparent;
    transition: background 0.15s;
    cursor: pointer;
    position: relative;
  }
  .notif-item:hover { background: var(--cream); }
  .notif-item + .notif-item { border-top: 1px solid rgba(139,0,0,0.04); }
  .notif-item.unread { border-left-color: var(--gold); background: rgba(255,245,232,0.4); }
  .notif-item.unread::after {
    content: '';
    position: absolute;
    top: 18px; right: 24px;
    width: 7px; height: 7px;
    background: var(--gold);
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(245,166,35,0.2);
  }

  .notif-icon {
    width: 32px; height: 32px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    background: rgba(139,0,0,0.06);
    color: var(--walnut);
  }
  .notif-icon svg { width: 15px; height: 15px; }
  .notif-icon.gold { background: rgba(245,166,35,0.12); color: #C07A00; }
  .notif-icon.sage { background: rgba(122,140,114,0.12); color: #4a6b42; }

  .notif-body { flex: 1; min-width: 0; padding-right: 16px; }
  .notif-title { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
  .notif-msg   { font-size: 12px; color: var(--text-mid); line-height: 1.4; }
  .notif-time  { font-size: 10.5px; color: var(--text-muted); margin-top: 4px; }

  /* ─── Export panel ─── */
  .export-panel { padding: 16px 24px; display: flex; flex-direction: column; gap: 10px; }
  .export-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 11px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.06);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    color: inherit;
    font-family: inherit;
    width: 100%;
    text-align: left;
  }
  .export-btn:hover {
    background: var(--cream-deep);
    transform: translateX(4px);
    border-color: rgba(192,21,42,0.15);
  }
  .export-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .export-icon svg { width: 17px; height: 17px; }
  .export-btn.pdf  .export-icon { background: rgba(192,21,42,0.1);  color: var(--walnut-mid); }
  .export-btn.csv  .export-icon { background: rgba(122,140,114,0.12); color: #4a6b42; }
  .export-btn.history .export-icon { background: rgba(245,166,35,0.12); color: #C07A00; }

  .export-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13.5px; color: var(--text-dark); }
  .export-sub   { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

  /* ═══════════════════════════════════════════════════════════
     EVALUATION DETAIL MODAL
  ═══════════════════════════════════════════════════════════ */
  .modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(10,2,4,0.6);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 200;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
  }
  .modal-backdrop.open { opacity: 1; pointer-events: auto; }

  .modal {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -45%) scale(0.96);
    width: min(900px, 94vw);
    max-height: 92vh;
    background: var(--white);
    border-radius: 18px;
    box-shadow: 0 24px 80px rgba(10,2,4,0.4);
    z-index: 201;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s, transform 0.35s cubic-bezier(0.4,0,0.2,1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .modal.open {
    opacity: 1;
    pointer-events: auto;
    transform: translate(-50%, -50%) scale(1);
  }

  .modal-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(139,0,0,0.07);
    background: linear-gradient(135deg, #FFFFFF 0%, var(--cream) 100%);
    flex-shrink: 0;
  }
  .modal-id-badge {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 12px;
    color: var(--walnut);
    background: var(--cream-deep);
    padding: 5px 10px;
    border-radius: 6px;
    border: 1px solid rgba(192,21,42,0.15);
    letter-spacing: 0.3px;
  }
  .modal-title-wrap { flex: 1; min-width: 0; }
  .modal-title {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.3px;
  }
  .modal-sub { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

  .modal-close {
    width: 34px; height: 34px;
    border-radius: 9px;
    border: 1px solid rgba(139,0,0,0.08);
    background: var(--white);
    color: var(--text-mid);
    cursor: pointer;
    transition: var(--transition);
    display: flex; align-items: center; justify-content: center;
  }
  .modal-close svg { width: 16px; height: 16px; }
  .modal-close:hover {
    background: var(--walnut-mid);
    color: #fff;
    border-color: var(--walnut-mid);
    transform: rotate(90deg);
  }

  .modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
  }
  .modal-body::-webkit-scrollbar { width: 8px; }
  .modal-body::-webkit-scrollbar-thumb { background: rgba(139,0,0,0.18); border-radius: 4px; }

  /* ─── Modal: tabs ─── */
  .modal-tabs {
    display: flex;
    gap: 6px;
    padding: 4px;
    background: var(--cream);
    border-radius: 12px;
    margin-bottom: 22px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  .modal-tabs::-webkit-scrollbar { display: none; }

  .modal-tab {
    flex: 1;
    min-width: max-content;
    padding: 9px 14px;
    border: none;
    background: transparent;
    border-radius: 8px;
    font-family: inherit;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-mid);
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }
  .modal-tab:hover { color: var(--walnut-mid); }
  .modal-tab.active {
    background: var(--white);
    color: var(--walnut);
    box-shadow: 0 2px 8px rgba(139,0,0,0.08);
  }
  .modal-tab .tab-count {
    background: rgba(139,0,0,0.1);
    color: var(--walnut-mid);
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 10px;
  }
  .modal-tab.active .tab-count { background: var(--walnut-mid); color: #fff; }

  .tab-panel { display: none; animation: fadeIn 0.25s ease; }
  .tab-panel.active { display: block; }

  /* ─── General Info grid ─── */
  .info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 20px;
  }
  .info-tile {
    padding: 12px 14px;
    background: var(--cream);
    border-radius: 10px;
    border: 1px solid rgba(139,0,0,0.05);
  }
  .info-tile-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 4px;
  }
  .info-tile-value {
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
  }

  /* ─── Audio player ─── */
  .audio-player {
    padding: 18px 20px;
    background: linear-gradient(135deg, #1A0204 0%, #2D0008 100%);
    color: #fff;
    border-radius: 14px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
  }
  .audio-player::before {
    content: '';
    position: absolute;
    top: -50%; right: -10%;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(245,166,35,0.15) 0%, transparent 70%);
    pointer-events: none;
  }

  .audio-top {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 14px;
    position: relative;
    z-index: 1;
  }

  .audio-play-btn {
    width: 46px; height: 46px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border: none;
    color: #1A0204;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: var(--transition);
    box-shadow: 0 4px 16px rgba(245,166,35,0.4);
  }
  .audio-play-btn:hover { transform: scale(1.08); box-shadow: 0 6px 22px rgba(245,166,35,0.55); }
  .audio-play-btn svg { width: 20px; height: 20px; }

  .audio-info { flex: 1; min-width: 0; }
  .audio-title {
    font-family: 'Syne', sans-serif;
    font-weight: 600;
    font-size: 14px;
    color: #fff;
  }
  .audio-meta { font-size: 11.5px; color: rgba(255,255,255,0.5); margin-top: 2px; }

  .audio-speed {
    padding: 5px 10px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    border-radius: 7px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
  }
  .audio-speed:hover { background: rgba(255,255,255,0.15); }

  /* waveform */
  .audio-wave {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 2px;
    height: 40px;
    margin-bottom: 8px;
    cursor: pointer;
  }
  .audio-wave-bar {
    flex: 1;
    background: rgba(255,255,255,0.2);
    border-radius: 1.5px;
    transition: background 0.2s;
  }
  .audio-wave-bar.played { background: linear-gradient(to top, var(--gold), var(--gold-light)); }

  .audio-times {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: rgba(255,255,255,0.5);
    font-variant-numeric: tabular-nums;
    position: relative;
    z-index: 1;
  }

  /* ─── Eval grid (criteria) ─── */
  .crit-section {
    margin-bottom: 18px;
  }
  .crit-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: var(--cream-deep);
    border-radius: 10px 10px 0 0;
    border: 1px solid rgba(192,21,42,0.1);
    border-bottom: none;
  }
  .crit-section-title {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: var(--walnut);
    letter-spacing: -0.2px;
  }
  .crit-section-score {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 14px;
    color: var(--walnut);
  }
  .crit-list {
    border: 1px solid rgba(139,0,0,0.07);
    border-radius: 0 0 10px 10px;
    overflow: hidden;
  }

  .crit-row {
    padding: 12px 14px;
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 12px;
    align-items: center;
    background: var(--white);
    border-bottom: 1px solid rgba(139,0,0,0.04);
  }
  .crit-row:last-child { border-bottom: none; }
  .crit-row.has-comment { background: rgba(255,245,232,0.4); }

  .crit-name { font-size: 13px; font-weight: 500; color: var(--text-dark); }
  .crit-comment {
    grid-column: 1 / -1;
    margin-top: 8px;
    padding: 10px 12px;
    background: rgba(245,166,35,0.08);
    border-left: 3px solid var(--gold);
    border-radius: 0 8px 8px 0;
    font-size: 12.5px;
    color: var(--text-mid);
    line-height: 1.45;
    font-style: italic;
  }
  .crit-comment strong { color: #8B5E00; font-style: normal; font-weight: 700; }

  .crit-score-pill {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 12px;
    padding: 3px 9px;
    border-radius: 6px;
  }
  .crit-score-pill.full { background: rgba(122,140,114,0.12); color: #3d6035; }
  .crit-score-pill.partial { background: rgba(245,166,35,0.12); color: #8B5E00; }
  .crit-score-pill.fail { background: rgba(139,0,0,0.1); color: var(--walnut); }

  .crit-ko-flag {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.5px;
    background: var(--walnut);
    color: #fff;
    padding: 2px 7px;
    border-radius: 5px;
  }

  /* total score panel */
  .total-score-panel {
    margin-top: 20px;
    padding: 20px 22px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--cream) 0%, var(--cream-deep) 100%);
    border: 1px solid rgba(192,21,42,0.12);
    display: flex;
    align-items: center;
    gap: 20px;
  }
  .ts-circle {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: conic-gradient(var(--walnut-mid) 0deg, var(--walnut-light) calc(var(--score, 86) * 3.6deg), rgba(139,0,0,0.1) calc(var(--score, 86) * 3.6deg));
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
  }
  .ts-circle::after {
    content: '';
    position: absolute;
    inset: 6px;
    background: var(--white);
    border-radius: 50%;
  }
  .ts-circle-text {
    position: relative;
    z-index: 1;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 22px;
    color: var(--walnut);
    letter-spacing: -1px;
  }
  .ts-circle-text small { font-size: 11px; color: var(--text-muted); font-weight: 500; }

  .ts-info { flex: 1; }
  .ts-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 4px;
  }
  .ts-status-text {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 16px;
    color: var(--text-dark);
    margin-bottom: 4px;
  }
  .ts-message { font-size: 12.5px; color: var(--text-mid); line-height: 1.5; }

  /* ─── Manager feedback blocks ─── */
  .feedback-block {
    padding: 16px 18px;
    border-radius: 12px;
    margin-bottom: 14px;
    border: 1px solid;
    position: relative;
  }
  .feedback-block-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
  }
  .feedback-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .feedback-icon svg { width: 14px; height: 14px; }
  .feedback-title {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: -0.2px;
  }

  .feedback-block.strengths {
    background: rgba(122,140,114,0.06);
    border-color: rgba(122,140,114,0.2);
  }
  .feedback-block.strengths .feedback-icon { background: rgba(122,140,114,0.15); color: #4a6b42; }
  .feedback-block.strengths .feedback-title { color: #3d6035; }

  .feedback-block.improvements {
    background: rgba(245,166,35,0.06);
    border-color: rgba(245,166,35,0.2);
  }
  .feedback-block.improvements .feedback-icon { background: rgba(245,166,35,0.15); color: #C07A00; }
  .feedback-block.improvements .feedback-title { color: #8B5E00; }

  .feedback-block.recommendations {
    background: rgba(192,21,42,0.05);
    border-color: rgba(192,21,42,0.15);
  }
  .feedback-block.recommendations .feedback-icon { background: rgba(192,21,42,0.1); color: var(--walnut-mid); }
  .feedback-block.recommendations .feedback-title { color: var(--walnut); }

  .feedback-list { list-style: none; padding: 0; margin: 0; }
  .feedback-list li {
    font-size: 13px;
    color: var(--text-dark);
    line-height: 1.55;
    padding: 4px 0 4px 18px;
    position: relative;
  }
  .feedback-list li::before {
    content: '';
    position: absolute;
    left: 0; top: 12px;
    width: 6px; height: 6px;
    background: currentColor;
    border-radius: 50%;
    opacity: 0.5;
  }

  /* ─── Comment textarea ─── */
  .comment-wrap { margin-bottom: 18px; }
  .comment-label {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13.5px;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .comment-label svg { width: 16px; height: 16px; color: var(--walnut-mid); }

  .comment-area {
    width: 100%;
    min-height: 110px;
    padding: 14px 16px;
    border: 1px solid rgba(139,0,0,0.12);
    background: var(--cream);
    border-radius: 12px;
    font-family: inherit;
    font-size: 13.5px;
    color: var(--text-dark);
    line-height: 1.55;
    resize: vertical;
    transition: var(--transition);
  }
  .comment-area:focus {
    outline: none;
    border-color: var(--walnut-mid);
    background: var(--white);
    box-shadow: 0 0 0 4px rgba(192,21,42,0.08);
  }
  .comment-area::placeholder { color: var(--text-muted); }

  .comment-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 6px;
    font-size: 11px;
    color: var(--text-muted);
  }
  .comment-counter { font-variant-numeric: tabular-nums; }

  /* quick acknowledge chips */
  .quick-replies {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
  }
  .quick-reply {
    padding: 6px 12px;
    background: var(--white);
    border: 1px solid rgba(139,0,0,0.1);
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 500;
    color: var(--text-mid);
    cursor: pointer;
    transition: var(--transition);
    font-family: inherit;
  }
  .quick-reply:hover {
    background: var(--cream-deep);
    color: var(--walnut);
    border-color: rgba(192,21,42,0.25);
    transform: translateY(-1px);
  }

  /* ─── Signature pad ─── */
  .sig-section {
    padding: 18px 20px;
    background: var(--cream);
    border-radius: 14px;
    border: 1px solid rgba(139,0,0,0.08);
    margin-bottom: 20px;
  }
  .sig-method-toggle {
    display: flex;
    gap: 4px;
    padding: 3px;
    background: var(--white);
    border-radius: 9px;
    margin-bottom: 14px;
    width: fit-content;
  }
  .sig-method-btn {
    padding: 7px 14px;
    border: none;
    background: transparent;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
  }
  .sig-method-btn.active {
    background: var(--walnut-mid);
    color: #fff;
  }

  .sig-pad-wrap {
    background: var(--white);
    border: 2px dashed rgba(139,0,0,0.18);
    border-radius: 12px;
    position: relative;
    overflow: hidden;
    aspect-ratio: 3 / 1;
    min-height: 130px;
  }
  .sig-pad-wrap.has-signature { border-style: solid; border-color: rgba(192,21,42,0.3); }

  .sig-canvas {
    width: 100%;
    height: 100%;
    cursor: crosshair;
    touch-action: none;
    display: block;
  }

  .sig-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    color: var(--text-muted);
    transition: opacity 0.2s;
  }
  .sig-placeholder svg { width: 26px; height: 26px; opacity: 0.3; margin-bottom: 6px; }
  .sig-placeholder span { font-size: 12px; font-weight: 500; }

  .sig-pad-wrap.has-signature .sig-placeholder { opacity: 0; }

  .sig-line {
    position: absolute;
    bottom: 22px;
    left: 16px; right: 16px;
    height: 1px;
    background: rgba(139,0,0,0.15);
    pointer-events: none;
  }
  .sig-x {
    position: absolute;
    bottom: 14px;
    left: 16px;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    color: var(--text-muted);
    font-size: 14px;
    pointer-events: none;
  }

  .sig-actions {
    display: flex;
    gap: 8px;
    margin-top: 10px;
  }
  .sig-clear {
    padding: 8px 14px;
    background: var(--white);
    border: 1px solid rgba(139,0,0,0.12);
    color: var(--text-mid);
    border-radius: 8px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
  }
  .sig-clear:hover { background: var(--cream-deep); color: var(--walnut); }

  /* checkbox confirmation */
  .sig-checkbox-wrap {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: var(--white);
    border-radius: 11px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: var(--transition);
  }
  .sig-checkbox-wrap.checked {
    border-color: var(--walnut-mid);
    background: var(--cream-deep);
  }

  .sig-checkbox {
    width: 22px; height: 22px;
    border: 2px solid rgba(139,0,0,0.25);
    border-radius: 6px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1px;
    transition: var(--transition);
    background: var(--white);
  }
  .sig-checkbox-wrap.checked .sig-checkbox {
    background: var(--walnut-mid);
    border-color: var(--walnut-mid);
  }
  .sig-checkbox svg { width: 14px; height: 14px; color: #fff; opacity: 0; transition: opacity 0.15s; }
  .sig-checkbox-wrap.checked .sig-checkbox svg { opacity: 1; }

  .sig-checkbox-label {
    font-size: 13px;
    color: var(--text-dark);
    line-height: 1.5;
  }
  .sig-checkbox-label strong { font-weight: 700; }

  /* ─── Modal footer ─── */
  .modal-footer {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 24px;
    border-top: 1px solid rgba(139,0,0,0.07);
    background: var(--cream);
    flex-shrink: 0;
    flex-wrap: wrap;
  }
  .footer-btn {
    padding: 10px 18px;
    border-radius: 10px;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    transition: var(--transition);
    border: 1px solid transparent;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    letter-spacing: 0.2px;
  }
  .footer-btn svg { width: 14px; height: 14px; }

  .footer-btn.ghost {
    background: transparent;
    color: var(--text-mid);
    border-color: rgba(139,0,0,0.15);
  }
  .footer-btn.ghost:hover { background: var(--white); color: var(--walnut); border-color: rgba(192,21,42,0.3); }

  .footer-btn.secondary {
    background: var(--white);
    color: var(--walnut);
    border-color: rgba(192,21,42,0.2);
  }
  .footer-btn.secondary:hover { background: var(--cream-deep); }

  .footer-btn.primary {
    background: linear-gradient(135deg, var(--walnut), var(--walnut-mid));
    color: #fff;
    box-shadow: 0 4px 14px rgba(192,21,42,0.3);
    margin-left: auto;
  }
  .footer-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(192,21,42,0.4);
  }
  .footer-btn.primary:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
  }

  /* ─── Responsive ─── */
  @media (max-width: 1100px) {
    .bottom-grid { grid-template-columns: 1fr; }
    .secondary-stats { grid-template-columns: 1fr; }
  }
  @media (max-width: 768px) {
    .welcome-banner { flex-direction: column; align-items: flex-start; padding: 18px 20px; }
    .wb-cta { width: 100%; padding: 12px; }
    .info-grid { grid-template-columns: 1fr; }
    .total-score-panel { flex-direction: column; text-align: center; }
    .modal { width: 96vw; max-height: 94vh; border-radius: 14px; }
    .modal-header { padding: 16px 18px; }
    .modal-body { padding: 18px; }
    .modal-footer { padding: 14px 18px; }
    .footer-btn.primary { margin-left: 0; width: 100%; justify-content: center; }
    .footer-btn { flex: 1; min-width: 0; justify-content: center; }
  }
  @media (max-width: 600px) {
    .filters-bar { padding: 12px; }
    .filter-clear { width: 100%; text-align: center; }
    .crit-row { grid-template-columns: 1fr; gap: 6px; }
    .crit-row .crit-score-pill, .crit-row .crit-ko-flag { justify-self: start; }
  }

  /* ─── Page-load stagger animations ─── */
  .welcome-banner { animation: fadeUp 0.5s 0.05s ease both; }
  .secondary-stats { animation: fadeUp 0.5s 0.18s ease both; }
  .filters-bar { animation: fadeUp 0.5s 0.32s ease both; }
  .bottom-grid > .card:first-child { animation: fadeUp 0.5s 0.40s ease both; }
  .bottom-grid > .card:last-child  { animation: fadeUp 0.5s 0.45s ease both; }
</style>

{{-- ═══════════════════════════════════════════════════════════
     PENDING SIGNATURE BANNER (only when there's something to sign)
═══════════════════════════════════════════════════════════ --}}
@php $pendingSigCount = $pendingSigCount ?? 2; @endphp

@if($pendingSigCount > 0)
<div class="welcome-banner">
  <div class="wb-icon">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>
  </div>
  <div class="wb-content">
    <div class="wb-title">You have <strong style="color:var(--gold-light);">{{ $pendingSigCount }} evaluation{{ $pendingSigCount > 1 ? 's' : '' }}</strong> awaiting your signature</div>
    <div class="wb-msg">Review the manager's feedback and sign to acknowledge. Latest pending: <strong>EV-1247 — Incoming call review · 6 May 2026</strong></div>
  </div>
  <button class="wb-cta" onclick="openModal()">Review &amp; Sign →</button>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════
     1. PERSONAL KPI SUMMARY
═══════════════════════════════════════════════════════════ --}}
<div class="kpi-grid">
  {{-- Total Evaluations --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Total Evaluations</span>
      <div class="kpi-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value">{{ $totalEvaluations ?? 24 }}</div>
    <div class="kpi-meta">
      <span class="kpi-delta up">▲ 4</span>
      <span>this month</span>
    </div>
  </div>

  {{-- Average Score --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">My Average Score</span>
      <div class="kpi-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value">{{ number_format($averageScore ?? 86.4, 1) }}<span style="font-size:18px;color:var(--text-muted);font-weight:500;">/100</span></div>
    <div class="kpi-meta">
      <span class="kpi-delta up">▲ 3.2 pts</span>
      <span>vs. last month</span>
    </div>
  </div>

  {{-- Signed --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Signed</span>
      <div class="kpi-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value">{{ $signedCount ?? 19 }}</div>
    <div class="kpi-meta">
      <span class="kpi-delta up">{{ $totalEvaluations > 0 ? round((($signedCount ?? 19) / $totalEvaluations) * 100) : 0 }}%</span>
      <span>completion rate</span>
    </div>
  </div>

  {{-- Pending Signatures --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Awaiting Signature</span>
      <div class="kpi-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value">{{ $pendingSigCount }}</div>
    <div class="kpi-meta">
      <span class="kpi-delta down">▲ Action needed</span>
      <span>review now</span>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     OPTIONAL SECONDARY STATS (KO / best / lowest)
═══════════════════════════════════════════════════════════ --}}
<div class="secondary-stats">
  <div class="stat-mini best">
    <div class="stat-mini-icon">🏅</div>
    <div>
      <div class="stat-mini-label">Best Score</div>
      <div class="stat-mini-value">{{ $bestScore ?? 96 }}<small>/100</small></div>
    </div>
  </div>
  <div class="stat-mini low">
    <div class="stat-mini-icon">📉</div>
    <div>
      <div class="stat-mini-label">Lowest Score</div>
      <div class="stat-mini-value">{{ $lowestScore ?? 71 }}<small>/100</small></div>
    </div>
  </div>
  <div class="stat-mini ko">
    <div class="stat-mini-icon">⚠️</div>
    <div>
      <div class="stat-mini-label">KO Occurrences</div>
      <div class="stat-mini-value">{{ $myKoCount ?? 1 }}<small>this year</small></div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     2. PERSONAL PERFORMANCE OVERVIEW (chart)
═══════════════════════════════════════════════════════════ --}}
<div class="card" style="margin-bottom:24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">My Score Evolution</h3>
      <p class="card-subtitle">Track your progression over time</p>
    </div>
    <div style="display:flex;gap:6px;padding:3px;background:var(--cream);border-radius:9px;">
      <button class="period-btn" data-period="daily">Daily</button>
      <button class="period-btn" data-period="weekly">Weekly</button>
      <button class="period-btn active" data-period="monthly">Monthly</button>
    </div>
  </div>

  <div class="chart-wrap">
    <div class="chart-legend">
      <div class="legend-item">
        <span class="legend-dot" style="background:linear-gradient(135deg,var(--walnut-mid),var(--walnut-light));"></span>
        My score
      </div>
      <div class="legend-item">
        <span class="legend-dot" style="background:rgba(122,140,114,0.7);"></span>
        Team average
      </div>
      <div class="legend-item">
        <span class="legend-dot" style="background:var(--gold);"></span>
        Best month
      </div>
    </div>

    <div class="chart-svg-wrap" id="myChartWrap">
      <svg id="myChart" viewBox="0 0 600 200" preserveAspectRatio="none">
        <defs>
          <linearGradient id="myFillGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%"   stop-color="#C0152A" stop-opacity="0.2"/>
            <stop offset="100%" stop-color="#C0152A" stop-opacity="0"/>
          </linearGradient>
          <linearGradient id="myLineGrad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%"   stop-color="#8B0000"/>
            <stop offset="100%" stop-color="#D93848"/>
          </linearGradient>
        </defs>

        {{-- grid lines --}}
        <line x1="0" y1="40"  x2="600" y2="40"  stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>
        <line x1="0" y1="80"  x2="600" y2="80"  stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>
        <line x1="0" y1="120" x2="600" y2="120" stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>
        <line x1="0" y1="160" x2="600" y2="160" stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>

        {{-- team avg line --}}
        <polyline class="chart-line2"
                  points="0,80 100,75 200,72 300,68 400,65 500,60 600,55"
                  fill="none"
                  stroke="rgba(122,140,114,0.6)"
                  stroke-width="2"
                  stroke-dasharray="4 4"
                  stroke-linecap="round"/>

        {{-- my score area --}}
        <path d="M 0 100 L 100 90 L 200 70 L 300 65 L 400 50 L 500 38 L 600 28 L 600 200 L 0 200 Z"
              fill="url(#myFillGrad)"/>

        {{-- my score line --}}
        <polyline class="chart-line"
                  points="0,100 100,90 200,70 300,65 400,50 500,38 600,28"
                  fill="none"
                  stroke="url(#myLineGrad)"
                  stroke-width="2.8"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>

        {{-- data points --}}
        <circle class="my-dot" cx="0"   cy="100" r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Dec · 75/100"/>
        <circle class="my-dot" cx="100" cy="90"  r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Jan · 80/100"/>
        <circle class="my-dot" cx="200" cy="70"  r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Feb · 85/100"/>
        <circle class="my-dot" cx="300" cy="65"  r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Mar · 87/100"/>
        <circle class="my-dot" cx="400" cy="50"  r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Apr · 90/100"/>
        <circle class="my-dot" cx="500" cy="38"  r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Apr · 93/100"/>
        <circle class="my-dot" cx="600" cy="28"  r="5.5" fill="#F5A623" stroke="#fff" stroke-width="3" data-label="May · 96/100 — best!"/>
      </svg>
      <div class="chart-tooltip" id="myChartTip"></div>
    </div>

    <div class="chart-months">
      <span>Dec</span><span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     4. FILTERS
═══════════════════════════════════════════════════════════ --}}
<form method="GET" action="#" class="filters-bar" id="filtersForm" onsubmit="return false;">
  <span class="filter-label">Filter by</span>

  <input type="date" name="date_from" class="filter-input" placeholder="From">
  <input type="date" name="date_to" class="filter-input" placeholder="To">

  <select name="call_type" class="filter-select">
    <option value="">All call types</option>
    <option value="incoming">Incoming</option>
    <option value="outgoing">Outgoing</option>
  </select>

  <select name="status" class="filter-select">
    <option value="">All statuses</option>
    <option value="draft">Draft</option>
    <option value="completed">Completed</option>
    <option value="signed">Signed</option>
  </select>

  <div class="range-wrap">
    <span class="range-label-min" id="rangeMin">0</span>
    <input type="range" id="scoreRange" min="0" max="100" value="50" step="5">
    <span class="range-label-max" id="rangeMax">100</span>
  </div>

  <a href="#" class="filter-clear" onclick="event.preventDefault(); document.getElementById('filtersForm').reset(); document.getElementById('rangeMin').textContent='0';">Clear</a>
</form>

{{-- ═══════════════════════════════════════════════════════════
     3. MY EVALUATIONS TABLE
═══════════════════════════════════════════════════════════ --}}
<div class="card" style="margin-bottom:24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">My Evaluations</h3>
      <p class="card-subtitle">All your call reviews · most recent first</p>
    </div>
    <a href="#" class="card-action">Export all →</a>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Date</th>
          <th>Call Type</th>
          <th>Score</th>
          <th>KO</th>
          <th>Status</th>
          <th>Manager</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @php
          $myEvals = $recentEvaluations ?? [];
        @endphp

        @foreach($myEvals as $i => $ev)
          @php
            $scoreColor = $ev['score'] >= 85 ? '#4a6b42' : ($ev['score'] >= 70 ? '#C07A00' : '#8B0000');
            $barFill    = $ev['score'] >= 85 ? 'linear-gradient(90deg,#7A8C72,#9CB394)'
                         : ($ev['score'] >= 70 ? 'linear-gradient(90deg,#F5A623,#F7BC54)'
                         : 'linear-gradient(90deg,#8B0000,#C0152A)');
            $isPending = $ev['status'] === 'completed';
          @endphp
          <tr>
            <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--walnut);font-size:12.5px;">{{ $ev['id'] }}</td>
            <td class="date-cell">{{ $ev['date'] }}</td>
            <td>
              @if($ev['type'] === 'incoming')
                <span class="call-pill call-in">↓ Incoming</span>
              @else
                <span class="call-pill call-out">↑ Outgoing</span>
              @endif
            </td>
            <td>
              <div class="score-cell">
                <span class="score-val" style="color: {{ $scoreColor }};">{{ $ev['score'] }}</span>
                <div class="score-bar">
                  <div class="score-bar-fill" style="width: {{ $ev['score'] }}%; background: {{ $barFill }};"></div>
                </div>
              </div>
            </td>
            <td>
              @if($ev['ko'])
                <span class="ko-chip ko-yes">⚠ KO</span>
              @else
                <span class="ko-chip ko-no">✓ OK</span>
              @endif
            </td>
            <td>
              @switch($ev['status'])
                @case('signed')    <span class="status-badge status-completed">Signed</span> @break
                @case('completed') <span class="status-badge status-pending">To sign</span> @break
                @case('draft')     <span class="status-badge status-draft">Draft</span> @break
              @endswitch
            </td>
            <td>
              <div class="advisor-cell">
                <div class="advisor-av" style="background: linear-gradient(135deg,#6B3040,#9C7078); width:26px;height:26px;font-size:10px;">{{ $ev['mgr_init'] }}</div>
                <div class="advisor-name" style="font-size:12.5px;">{{ $ev['manager'] }}</div>
              </div>
            </td>
            <td style="text-align:right;">
              <div class="icon-actions">
                <button class="icon-btn" title="View details" onclick="openModal()">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
                <button class="icon-btn" title="Listen to audio">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </button>
                <button class="icon-btn" title="Comment" onclick="openModal('comment')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </button>
                @if($isPending)
                  <button class="icon-btn sign-btn" title="Sign now" onclick="openModal('sign')">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                @endif
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     8 + 9. NOTIFICATIONS  +  EXPORT PANEL
═══════════════════════════════════════════════════════════ --}}
<div class="bottom-grid">

  {{-- Notifications --}}
  <div class="card">
    <div class="card-header">
      <div>
        <h3 class="card-title">Notifications</h3>
        <p class="card-subtitle">Latest activity on your evaluations</p>
      </div>
      <span class="card-action" style="cursor:default;">3 new</span>
    </div>
    <div class="notif-list">
      <div class="notif-item unread">
        <div class="notif-icon gold">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <div class="notif-body">
          <div class="notif-title">Evaluation awaiting your signature</div>
          <div class="notif-msg">EV-1247 from Karim Mansouri is ready for review and acknowledgment.</div>
          <div class="notif-time">Today, 10:42</div>
        </div>
      </div>

      <div class="notif-item unread">
        <div class="notif-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div class="notif-body">
          <div class="notif-title">Manager added a comment</div>
          <div class="notif-msg">Karim Mansouri left feedback on EV-1238: "Excellent objection handling on this call."</div>
          <div class="notif-time">Yesterday, 14:08</div>
        </div>
      </div>

      <div class="notif-item unread">
        <div class="notif-icon sage">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div class="notif-body">
          <div class="notif-title">New evaluation received</div>
          <div class="notif-msg">EV-1247 — incoming call from 6 May has been reviewed and is now available.</div>
          <div class="notif-time">2 days ago</div>
        </div>
      </div>

      <div class="notif-item">
        <div class="notif-icon sage">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="notif-body">
          <div class="notif-title">Signature confirmed</div>
          <div class="notif-msg">You signed EV-1229 on 3 May. Status updated to "Signed".</div>
          <div class="notif-time">3 days ago</div>
        </div>
      </div>

      <div class="notif-item">
        <div class="notif-icon sage">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div class="notif-body">
          <div class="notif-title">New personal best!</div>
          <div class="notif-msg">You scored 96/100 on EV-1247 — your highest score yet. Keep it up.</div>
          <div class="notif-time">3 days ago</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Export panel --}}
  <div class="card">
    <div class="card-header">
      <div>
        <h3 class="card-title">Export &amp; Download</h3>
        <p class="card-subtitle">Get a copy of your records</p>
      </div>
    </div>
    <div class="export-panel">
      <button class="export-btn pdf">
        <div class="export-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
          <div class="export-title">Latest Evaluation PDF</div>
          <div class="export-sub">EV-1247 · 6 May 2026 · 92/100</div>
        </div>
      </button>

      <button class="export-btn csv">
        <div class="export-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
        </div>
        <div>
          <div class="export-title">Personal History (CSV)</div>
          <div class="export-sub">All {{ $totalEvals ?? 24 }} evaluations · spreadsheet format</div>
        </div>
      </button>

      <button class="export-btn history">
        <div class="export-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4m0 0l-4-4m4 4l-4 4m6 4V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2z"/></svg>
        </div>
        <div>
          <div class="export-title">Full Performance Report</div>
          <div class="export-sub">PDF · charts &amp; trends · 12 pages</div>
        </div>
      </button>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     5 + 6 + 7. EVALUATION DETAIL MODAL
            (general info, audio, grid, feedback, comment, signature)
═══════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="modalBackdrop" onclick="closeModal()"></div>

<div class="modal" id="evalModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

  <div class="modal-header">
    <span class="modal-id-badge">EV-1247</span>
    <div class="modal-title-wrap">
      <div class="modal-title" id="modalTitle">Incoming call review</div>
      <div class="modal-sub">6 May 2026, 09:42 · Reviewed by Karim Mansouri</div>
    </div>
    <button class="modal-close" onclick="closeModal()" aria-label="Close">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <div class="modal-body">

    {{-- Tabs --}}
    <div class="modal-tabs" role="tablist">
      <button class="modal-tab active" data-tab="overview" role="tab">Overview</button>
      <button class="modal-tab" data-tab="grid" role="tab">Grid <span class="tab-count">8</span></button>
      <button class="modal-tab" data-tab="feedback" role="tab">Feedback</button>
      <button class="modal-tab" data-tab="comment" role="tab">My Comment</button>
      <button class="modal-tab" data-tab="sign" role="tab">Sign</button>
    </div>

    {{-- ── OVERVIEW TAB ── --}}
    <div class="tab-panel active" data-panel="overview">
      <div class="info-grid">
        <div class="info-tile">
          <div class="info-tile-label">Reference</div>
          <div class="info-tile-value">EV-1247</div>
        </div>
        <div class="info-tile">
          <div class="info-tile-label">Date</div>
          <div class="info-tile-value">6 May 2026, 09:42</div>
        </div>
        <div class="info-tile">
          <div class="info-tile-label">Manager</div>
          <div class="info-tile-value">Karim Mansouri</div>
        </div>
        <div class="info-tile">
          <div class="info-tile-label">Call Type</div>
          <div class="info-tile-value">📞 Incoming · 4 min 32 s</div>
        </div>
      </div>

      {{-- Audio player --}}
      <div class="audio-player">
        <div class="audio-top">
          <button class="audio-play-btn" id="audioPlayBtn" aria-label="Play">
            <svg id="playIcon" fill="currentColor" viewBox="0 0 24 24">
              <path d="M8 5v14l11-7z"/>
            </svg>
            <svg id="pauseIcon" style="display:none;" fill="currentColor" viewBox="0 0 24 24">
              <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>
          </button>
          <div class="audio-info">
            <div class="audio-title">Call recording — Customer #C-8842</div>
            <div class="audio-meta">Inbound · client retention queue</div>
          </div>
          <button class="audio-speed" id="audioSpeed">1.0×</button>
        </div>

        <div class="audio-wave" id="audioWave">
          {{-- 50 bars generated by JS --}}
        </div>

        <div class="audio-times">
          <span id="audioCurrent">0:00</span>
          <span id="audioDuration">4:32</span>
        </div>
      </div>

      {{-- Total score panel --}}
      <div class="total-score-panel" style="--score: 92;">
        <div class="ts-circle">
          <div class="ts-circle-text">92<small>/100</small></div>
        </div>
        <div class="ts-info">
          <div class="ts-label">Final score</div>
          <div class="ts-status-text">Excellent performance 🎯</div>
          <div class="ts-message">You handled this incoming call with strong empathy and resolved the customer's concern in under 5 minutes. One minor area to refine — see Manager Feedback.</div>
        </div>
      </div>
    </div>

    {{-- ── GRID TAB ── --}}
    <div class="tab-panel" data-panel="grid">

      <div class="crit-section">
        <div class="crit-section-header">
          <span class="crit-section-title">📞 Call Opening</span>
          <span class="crit-section-score">18 / 20</span>
        </div>
        <div class="crit-list">
          <div class="crit-row">
            <span class="crit-name">Greeting &amp; identification</span>
            <span class="crit-score-pill full">5/5</span>
            <span></span>
          </div>
          <div class="crit-row">
            <span class="crit-name">Tone of voice &amp; energy</span>
            <span class="crit-score-pill full">5/5</span>
            <span></span>
          </div>
          <div class="crit-row has-comment">
            <span class="crit-name">Confirming customer identity</span>
            <span class="crit-score-pill partial">4/5</span>
            <span></span>
            <div class="crit-comment"><strong>Manager note:</strong> You verified the name but could also confirm the contact number on file before sharing details.</div>
          </div>
          <div class="crit-row">
            <span class="crit-name">Reason for call inquiry</span>
            <span class="crit-score-pill full">4/5</span>
            <span></span>
          </div>
        </div>
      </div>

      <div class="crit-section">
        <div class="crit-section-header">
          <span class="crit-section-title">💬 Customer Handling</span>
          <span class="crit-section-score">36 / 40</span>
        </div>
        <div class="crit-list">
          <div class="crit-row">
            <span class="crit-name">Active listening</span>
            <span class="crit-score-pill full">10/10</span>
            <span></span>
          </div>
          <div class="crit-row">
            <span class="crit-name">Empathy &amp; reassurance</span>
            <span class="crit-score-pill full">10/10</span>
            <span></span>
          </div>
          <div class="crit-row has-comment">
            <span class="crit-name">Objection handling</span>
            <span class="crit-score-pill partial">8/10</span>
            <span></span>
            <div class="crit-comment"><strong>Manager note:</strong> Solid recovery on the pricing objection. Next time try the "feel-felt-found" framework for an even stronger close.</div>
          </div>
          <div class="crit-row">
            <span class="crit-name">Solution proposal</span>
            <span class="crit-score-pill full">8/10</span>
            <span></span>
          </div>
        </div>
      </div>

      <div class="crit-section">
        <div class="crit-section-header">
          <span class="crit-section-title">📋 Compliance &amp; KO Items</span>
          <span class="crit-section-score">20 / 20</span>
        </div>
        <div class="crit-list">
          <div class="crit-row">
            <span class="crit-name">Data protection disclosure</span>
            <span class="crit-score-pill full">5/5</span>
            <span class="crit-ko-flag">KO</span>
          </div>
          <div class="crit-row">
            <span class="crit-name">Recording consent</span>
            <span class="crit-score-pill full">5/5</span>
            <span class="crit-ko-flag">KO</span>
          </div>
          <div class="crit-row">
            <span class="crit-name">No misleading information</span>
            <span class="crit-score-pill full">5/5</span>
            <span class="crit-ko-flag">KO</span>
          </div>
          <div class="crit-row">
            <span class="crit-name">Proper escalation procedure</span>
            <span class="crit-score-pill full">5/5</span>
            <span></span>
          </div>
        </div>
      </div>

      <div class="crit-section">
        <div class="crit-section-header">
          <span class="crit-section-title">🎯 Closing</span>
          <span class="crit-section-score">18 / 20</span>
        </div>
        <div class="crit-list">
          <div class="crit-row">
            <span class="crit-name">Summary of next steps</span>
            <span class="crit-score-pill full">7/8</span>
            <span></span>
          </div>
          <div class="crit-row">
            <span class="crit-name">Polite closing &amp; thank you</span>
            <span class="crit-score-pill full">6/6</span>
            <span></span>
          </div>
          <div class="crit-row has-comment">
            <span class="crit-name">Cross-sell opportunity</span>
            <span class="crit-score-pill partial">5/6</span>
            <span></span>
            <div class="crit-comment"><strong>Manager note:</strong> You spotted the upgrade opportunity but hesitated. Trust your instincts — your read was correct.</div>
          </div>
        </div>
      </div>

      <div class="total-score-panel" style="--score: 92; margin-top: 12px;">
        <div class="ts-circle">
          <div class="ts-circle-text">92<small>/100</small></div>
        </div>
        <div class="ts-info">
          <div class="ts-label">Total score</div>
          <div class="ts-status-text">Status: Awaiting your signature</div>
          <div class="ts-message">All compliance KO items passed. Final score above your rolling 30-day average of 86.4.</div>
        </div>
      </div>
    </div>

    {{-- ── FEEDBACK TAB ── --}}
    <div class="tab-panel" data-panel="feedback">
      <div class="feedback-block strengths">
        <div class="feedback-block-header">
          <div class="feedback-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
          </div>
          <div class="feedback-title">What you did well</div>
        </div>
        <ul class="feedback-list">
          <li>Outstanding empathy when the customer expressed frustration about the billing issue.</li>
          <li>Confident product knowledge — you answered the technical question without putting the call on hold.</li>
          <li>Perfect compliance: all four KO items handled cleanly and at the right moment in the call flow.</li>
          <li>Excellent rapport-building tone throughout, the customer mentioned this in the post-call survey.</li>
        </ul>
      </div>

      <div class="feedback-block improvements">
        <div class="feedback-block-header">
          <div class="feedback-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <div class="feedback-title">Areas to improve</div>
        </div>
        <ul class="feedback-list">
          <li>Identity verification — confirm at least two data points (name + phone or DOB) before discussing account specifics.</li>
          <li>Hesitation on the upgrade pitch around minute 3:20. Your read was correct, commit to it next time.</li>
          <li>Try the "feel-felt-found" framework for stronger objection responses on pricing concerns.</li>
        </ul>
      </div>

      <div class="feedback-block recommendations">
        <div class="feedback-block-header">
          <div class="feedback-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          </div>
          <div class="feedback-title">My recommendations</div>
        </div>
        <ul class="feedback-list">
          <li>Watch the recorded "Sales Recovery Masterclass" in the training portal (≈ 25 min).</li>
          <li>Pair with Fatima Z. Bennani on her next outbound shift to observe her closing technique.</li>
          <li>Let's review one outbound call together in our 1-on-1 next Tuesday to refine pitch confidence.</li>
        </ul>
      </div>
    </div>

    {{-- ── COMMENT TAB ── --}}
    <div class="tab-panel" data-panel="comment">
      <div class="comment-wrap">
        <label class="comment-label" for="conseillerComment">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
          Your reply to this evaluation
        </label>
        <textarea
          id="conseillerComment"
          class="comment-area"
          placeholder="Share your thoughts on the feedback, ask questions, or acknowledge points to work on..."
          maxlength="1000"></textarea>
        <div class="comment-meta">
          <span>This will be visible to your manager.</span>
          <span class="comment-counter"><span id="commentCount">0</span> / 1000</span>
        </div>

        <div class="quick-replies">
          <button class="quick-reply" data-text="Thank you for the feedback. I understand the points raised and will work on them.">💬 Acknowledge feedback</button>
          <button class="quick-reply" data-text="I agree with the assessment. I'll focus on identity verification on my next calls.">✓ I agree, will improve</button>
          <button class="quick-reply" data-text="Could we discuss the objection handling point in our next 1-on-1? I'd like to practice the framework.">🗓 Request 1-on-1</button>
          <button class="quick-reply" data-text="Thanks! Glad the empathy came through clearly.">🙏 Thank you</button>
        </div>
      </div>
    </div>

    {{-- ── SIGN TAB ── --}}
    <div class="tab-panel" data-panel="sign">
      <div class="sig-section">
        <div class="sig-method-toggle">
          <button class="sig-method-btn active" data-method="draw">✍️ Draw signature</button>
          <button class="sig-method-btn" data-method="check">☑ Confirm by checkbox</button>
        </div>

        {{-- Draw signature pad --}}
        <div id="sigDrawPanel">
          <div class="sig-pad-wrap" id="sigPadWrap">
            <canvas class="sig-canvas" id="sigCanvas"></canvas>
            <div class="sig-placeholder">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              <span>Sign here using your mouse, finger, or stylus</span>
            </div>
            <div class="sig-line"></div>
            <div class="sig-x">×</div>
          </div>
          <div class="sig-actions">
            <button class="sig-clear" id="sigClearBtn">Clear signature</button>
            <span style="font-size:11px;color:var(--text-muted);align-self:center;margin-left:auto;">By signing you confirm you've reviewed this evaluation</span>
          </div>
        </div>

        {{-- Checkbox alternative --}}
        <div id="sigCheckPanel" style="display:none;">
          <div class="sig-checkbox-wrap" id="sigCheckWrap" onclick="toggleSigCheck()">
            <div class="sig-checkbox">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div class="sig-checkbox-label">
              <strong>I, Imane Cherkaoui,</strong> confirm that I have reviewed evaluation <strong>EV-1247</strong>, read the manager's feedback in full, and acknowledge the points raised. I understand this signature is electronic and binding.
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="modal-footer">
    <button class="footer-btn ghost" onclick="closeModal()">Close</button>
    <button class="footer-btn secondary">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
      Download PDF
    </button>
    <button class="footer-btn primary" id="signSubmitBtn" disabled>
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
      Sign &amp; Submit
    </button>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     PAGE-SPECIFIC SCRIPTS
═══════════════════════════════════════════════════════════ --}}
<script>
/* ───────────────────────────────
   1.  Period buttons (chart filter)
─────────────────────────────── */
document.querySelectorAll('.period-btn').forEach(btn => {
  btn.style.cssText = 'padding:6px 12px;border:none;background:transparent;border-radius:7px;font-family:inherit;font-size:12px;font-weight:600;color:var(--text-muted);cursor:pointer;transition:var(--transition);';
  btn.addEventListener('click', () => {
    document.querySelectorAll('.period-btn').forEach(b => {
      b.classList.remove('active');
      b.style.background = 'transparent';
      b.style.color = 'var(--text-muted)';
    });
    btn.classList.add('active');
    btn.style.background = 'var(--white)';
    btn.style.color = 'var(--walnut)';
    btn.style.boxShadow = '0 2px 6px rgba(139,0,0,0.08)';
  });
  if (btn.classList.contains('active')) {
    btn.style.background = 'var(--white)';
    btn.style.color = 'var(--walnut)';
    btn.style.boxShadow = '0 2px 6px rgba(139,0,0,0.08)';
  }
});

/* ───────────────────────────────
   2.  My-chart tooltip
─────────────────────────────── */
(function() {
  const wrap = document.getElementById('myChartWrap');
  const tip  = document.getElementById('myChartTip');
  const dots = document.querySelectorAll('#myChart .my-dot');
  const svg  = document.getElementById('myChart');

  dots.forEach(dot => {
    const baseR = dot.getAttribute('r');
    dot.style.cursor = 'pointer';
    dot.style.transition = 'r 0.2s';

    dot.addEventListener('mouseenter', function() {
      const wrapRect = wrap.getBoundingClientRect();
      const svgRect  = svg.getBoundingClientRect();
      const cx = parseFloat(dot.getAttribute('cx'));
      const cy = parseFloat(dot.getAttribute('cy'));
      const x = (cx / 600) * svgRect.width;
      const y = (cy / 200) * svgRect.height;

      tip.textContent = dot.getAttribute('data-label');
      tip.style.left = Math.max(0, x - 70) + 'px';
      tip.style.top  = (y - 38) + 'px';
      tip.classList.add('show');

      dot.setAttribute('r', parseFloat(baseR) + 2);
    });

    dot.addEventListener('mouseleave', function() {
      tip.classList.remove('show');
      dot.setAttribute('r', baseR);
    });
  });
})();

/* ───────────────────────────────
   3.  Score range slider
─────────────────────────────── */
const range = document.getElementById('scoreRange');
const rMin  = document.getElementById('rangeMin');
if (range) {
  range.addEventListener('input', e => { rMin.textContent = e.target.value; });
}

/* ───────────────────────────────
   4.  Modal open / close
─────────────────────────────── */
const modal = document.getElementById('evalModal');
const backdrop = document.getElementById('modalBackdrop');

function openModal(focusTab) {
  modal.classList.add('open');
  backdrop.classList.add('open');
  document.body.style.overflow = 'hidden';
  if (focusTab) {
    setTimeout(() => activateTab(focusTab), 50);
  } else {
    activateTab('overview');
  }
}
function closeModal() {
  modal.classList.remove('open');
  backdrop.classList.remove('open');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
  if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
});

/* ───────────────────────────────
   5.  Tab switching
─────────────────────────────── */
function activateTab(name) {
  document.querySelectorAll('.modal-tab').forEach(t => {
    t.classList.toggle('active', t.dataset.tab === name);
  });
  document.querySelectorAll('.tab-panel').forEach(p => {
    p.classList.toggle('active', p.dataset.panel === name);
  });
  // when entering "sign" tab, prep the canvas
  if (name === 'sign') setTimeout(initSigCanvas, 120);
}
document.querySelectorAll('.modal-tab').forEach(tab => {
  tab.addEventListener('click', () => activateTab(tab.dataset.tab));
});

/* ───────────────────────────────
   6.  Audio waveform (visual demo)
─────────────────────────────── */
const wave = document.getElementById('audioWave');
const BAR_COUNT = 60;
const heights = [];
for (let i = 0; i < BAR_COUNT; i++) {
  // pseudo-random-but-stable wave shape
  const h = 25 + Math.sin(i * 0.45) * 18 + Math.cos(i * 0.9) * 12 + Math.random() * 14;
  heights.push(Math.max(8, Math.min(95, h)));
  const bar = document.createElement('div');
  bar.className = 'audio-wave-bar';
  bar.style.height = heights[i] + '%';
  wave.appendChild(bar);
}

// playback simulation
let isPlaying = false;
let currentBar = 0;
let playInterval = null;
const TOTAL_DURATION = 272; // 4:32 in seconds
let elapsed = 0;
let speed = 1;

const playBtn   = document.getElementById('audioPlayBtn');
const playIcon  = document.getElementById('playIcon');
const pauseIcon = document.getElementById('pauseIcon');
const speedBtn  = document.getElementById('audioSpeed');
const curEl     = document.getElementById('audioCurrent');

function fmtTime(s) {
  const m = Math.floor(s / 60);
  const r = Math.floor(s % 60);
  return m + ':' + (r < 10 ? '0' + r : r);
}

function updateWave() {
  const progress = elapsed / TOTAL_DURATION;
  const playedBars = Math.floor(progress * BAR_COUNT);
  document.querySelectorAll('.audio-wave-bar').forEach((b, i) => {
    b.classList.toggle('played', i < playedBars);
  });
  curEl.textContent = fmtTime(elapsed);
}

playBtn.addEventListener('click', () => {
  isPlaying = !isPlaying;
  playIcon.style.display  = isPlaying ? 'none' : 'block';
  pauseIcon.style.display = isPlaying ? 'block' : 'none';
  if (isPlaying) {
    playInterval = setInterval(() => {
      elapsed += 0.5 * speed;
      if (elapsed >= TOTAL_DURATION) {
        elapsed = TOTAL_DURATION;
        clearInterval(playInterval);
        isPlaying = false;
        playIcon.style.display = 'block';
        pauseIcon.style.display = 'none';
      }
      updateWave();
    }, 500);
  } else {
    clearInterval(playInterval);
  }
});

speedBtn.addEventListener('click', () => {
  const speeds = [1, 1.25, 1.5, 2, 0.75];
  const labels = ['1.0×', '1.25×', '1.5×', '2.0×', '0.75×'];
  const i = (speeds.indexOf(speed) + 1) % speeds.length;
  speed = speeds[i];
  speedBtn.textContent = labels[i];
});

// click on waveform to scrub
wave.addEventListener('click', e => {
  const rect = wave.getBoundingClientRect();
  const ratio = (e.clientX - rect.left) / rect.width;
  elapsed = ratio * TOTAL_DURATION;
  updateWave();
});

/* ───────────────────────────────
   7.  Comment counter + quick replies
─────────────────────────────── */
const commentArea  = document.getElementById('conseillerComment');
const commentCount = document.getElementById('commentCount');
if (commentArea) {
  commentArea.addEventListener('input', () => {
    commentCount.textContent = commentArea.value.length;
  });
}
document.querySelectorAll('.quick-reply').forEach(btn => {
  btn.addEventListener('click', () => {
    const txt = btn.dataset.text || '';
    if (commentArea.value && !commentArea.value.endsWith(' ')) {
      commentArea.value += ' ';
    }
    commentArea.value += txt;
    commentCount.textContent = commentArea.value.length;
    commentArea.focus();
  });
});

/* ───────────────────────────────
   8.  Signature method toggle
─────────────────────────────── */
let sigMethod = 'draw';
let sigDone = false;

document.querySelectorAll('.sig-method-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.sig-method-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    sigMethod = btn.dataset.method;
    document.getElementById('sigDrawPanel').style.display  = sigMethod === 'draw'  ? 'block' : 'none';
    document.getElementById('sigCheckPanel').style.display = sigMethod === 'check' ? 'block' : 'none';
    if (sigMethod === 'draw') setTimeout(initSigCanvas, 50);
    updateSubmitState();
  });
});

function toggleSigCheck() {
  const w = document.getElementById('sigCheckWrap');
  w.classList.toggle('checked');
  updateSubmitState();
}

function updateSubmitState() {
  const submitBtn = document.getElementById('signSubmitBtn');
  let valid = false;
  if (sigMethod === 'draw') valid = sigDone;
  else valid = document.getElementById('sigCheckWrap').classList.contains('checked');
  submitBtn.disabled = !valid;
}

/* ───────────────────────────────
   9.  Signature canvas (mouse + touch)
─────────────────────────────── */
let sigCanvas, sigCtx, sigDrawing = false, sigInited = false;

function initSigCanvas() {
  if (sigInited) return;
  sigCanvas = document.getElementById('sigCanvas');
  if (!sigCanvas) return;

  // Set canvas size based on actual rendered size, supporting retina
  const wrap = document.getElementById('sigPadWrap');
  const rect = wrap.getBoundingClientRect();
  const dpr = window.devicePixelRatio || 1;
  sigCanvas.width = rect.width * dpr;
  sigCanvas.height = rect.height * dpr;
  sigCanvas.style.width = rect.width + 'px';
  sigCanvas.style.height = rect.height + 'px';

  sigCtx = sigCanvas.getContext('2d');
  sigCtx.scale(dpr, dpr);
  sigCtx.lineCap = 'round';
  sigCtx.lineJoin = 'round';
  sigCtx.lineWidth = 2.2;
  sigCtx.strokeStyle = '#1A0A0C';

  // mouse events
  sigCanvas.addEventListener('mousedown', sigStart);
  sigCanvas.addEventListener('mousemove', sigDraw);
  sigCanvas.addEventListener('mouseup', sigEnd);
  sigCanvas.addEventListener('mouseleave', sigEnd);

  // touch events
  sigCanvas.addEventListener('touchstart', sigStartTouch, { passive: false });
  sigCanvas.addEventListener('touchmove',  sigDrawTouch,  { passive: false });
  sigCanvas.addEventListener('touchend',   sigEnd);

  sigInited = true;
}

function getCoords(e) {
  const rect = sigCanvas.getBoundingClientRect();
  return {
    x: e.clientX - rect.left,
    y: e.clientY - rect.top
  };
}
function getTouchCoords(e) {
  const rect = sigCanvas.getBoundingClientRect();
  const t = e.touches[0];
  return { x: t.clientX - rect.left, y: t.clientY - rect.top };
}

function sigStart(e) {
  sigDrawing = true;
  const c = getCoords(e);
  sigCtx.beginPath();
  sigCtx.moveTo(c.x, c.y);
}
function sigStartTouch(e) {
  e.preventDefault();
  sigDrawing = true;
  const c = getTouchCoords(e);
  sigCtx.beginPath();
  sigCtx.moveTo(c.x, c.y);
}
function sigDraw(e) {
  if (!sigDrawing) return;
  const c = getCoords(e);
  sigCtx.lineTo(c.x, c.y);
  sigCtx.stroke();
  if (!sigDone) {
    sigDone = true;
    document.getElementById('sigPadWrap').classList.add('has-signature');
    updateSubmitState();
  }
}
function sigDrawTouch(e) {
  if (!sigDrawing) return;
  e.preventDefault();
  const c = getTouchCoords(e);
  sigCtx.lineTo(c.x, c.y);
  sigCtx.stroke();
  if (!sigDone) {
    sigDone = true;
    document.getElementById('sigPadWrap').classList.add('has-signature');
    updateSubmitState();
  }
}
function sigEnd() {
  sigDrawing = false;
}

document.getElementById('sigClearBtn').addEventListener('click', () => {
  if (!sigCtx) return;
  sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
  sigDone = false;
  document.getElementById('sigPadWrap').classList.remove('has-signature');
  updateSubmitState();
});

// re-size canvas on window resize
window.addEventListener('resize', () => {
  if (sigInited && document.querySelector('.tab-panel[data-panel="sign"]').classList.contains('active')) {
    sigInited = false;
    initSigCanvas();
  }
});

/* ───────────────────────────────
   10. Submit (demo only)
─────────────────────────────── */
document.getElementById('signSubmitBtn').addEventListener('click', () => {
  alert('✅ Evaluation EV-1247 signed successfully!\n\nThank you, Imane. Your signature has been recorded and the manager will be notified.');
  closeModal();
});

/* ───────────────────────────────
   11. Animate progress bars on load
─────────────────────────────── */
window.addEventListener('load', () => {
  setTimeout(() => {
    document.querySelectorAll('.score-bar-fill, .bar-fill').forEach(b => {
      const w = b.style.width;
      b.style.width = '0';
      requestAnimationFrame(() => { b.style.width = w; });
    });
  }, 150);
});
</script>

@endsection
