{{-- 
  resources/views/manager/dashboard.blade.php
  Extends the KiteaCall layout. Adjust the @extends path to match where you 
  saved the layout file (e.g. layouts.app, layouts.manager, etc.).
--}}
@extends('layouts.app')

@section('title', 'Manager Dashboard — KiteaCall')
@section('topbar_title', 'Performance Dashboard')
@section('topbar_subtitle', 'Wednesday, 6 May 2026 · Week 19 · Team A')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     PAGE-SPECIFIC STYLES (additions on top of the layout)
═══════════════════════════════════════════════════════════ --}}
<style>
  /* ─── Quick Actions ─── */
  .quick-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
  }
  .qa-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    background: var(--white);
    border: 1px solid rgba(139,0,0,0.06);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
  }
  .qa-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent 60%, rgba(245,166,35,0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s;
    pointer-events: none;
  }
  .qa-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); border-color: rgba(192,21,42,0.15); }
  .qa-card:hover::after { opacity: 1; }

  .qa-icon {
    width: 42px; height: 42px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .qa-icon svg { width: 19px; height: 19px; }
  .qa-card.primary .qa-icon { background: linear-gradient(135deg, var(--walnut), var(--walnut-mid)); color: #fff; box-shadow: 0 4px 14px rgba(192,21,42,0.28); }
  .qa-card.gold    .qa-icon { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #fff; box-shadow: 0 4px 14px rgba(245,166,35,0.3); }
  .qa-card.sage    .qa-icon { background: rgba(122,140,114,0.15); color: var(--sage); }
  .qa-card.dark    .qa-icon { background: rgba(26,10,12,0.08); color: var(--text-dark); }

  .qa-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; color: var(--text-dark); letter-spacing: -0.2px; }
  .qa-sub   { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

  .qa-arrow { margin-left: auto; opacity: 0.3; transition: var(--transition); color: var(--text-muted); }
  .qa-card:hover .qa-arrow { opacity: 1; transform: translateX(3px); color: var(--walnut-mid); }

  /* ─── Filters Bar ─── */
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
  }
  .filter-clear:hover { background: var(--cream-deep); border-color: rgba(192,21,42,0.2); }

  /* ─── Conseiller perf list ─── */
  .conseiller-list { padding: 12px 0; max-height: 380px; overflow-y: auto; }
  .conseiller-list::-webkit-scrollbar { width: 6px; }
  .conseiller-list::-webkit-scrollbar-track { background: transparent; }
  .conseiller-list::-webkit-scrollbar-thumb { background: rgba(139,0,0,0.15); border-radius: 3px; }

  .cons-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 24px;
    transition: background 0.15s;
    cursor: pointer;
  }
  .cons-row:hover { background: var(--cream); }

  .cons-av {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 12px;
    color: #fff;
    flex-shrink: 0;
  }

  .cons-info { flex: 1; min-width: 0; }
  .cons-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .cons-meta {
    display: flex; gap: 8px; align-items: center;
    margin-top: 4px;
  }
  .cons-bar {
    flex: 1;
    height: 5px;
    background: rgba(139,0,0,0.06);
    border-radius: 3px;
    overflow: hidden;
  }
  .cons-bar-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 1.2s cubic-bezier(0.4,0,0.2,1);
  }
  .cons-evals { font-size: 10.5px; color: var(--text-muted); white-space: nowrap; }

  .cons-score {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 15px;
    letter-spacing: -0.3px;
    min-width: 38px;
    text-align: right;
  }
  .cons-score.high { color: #3d6035; }
  .cons-score.mid  { color: #C07A00; }
  .cons-score.low  { color: var(--walnut); }

  /* ─── Alerts ─── */
  .alert-list { padding: 8px 0; max-height: 420px; overflow-y: auto; }
  .alert-list::-webkit-scrollbar { width: 6px; }
  .alert-list::-webkit-scrollbar-thumb { background: rgba(139,0,0,0.15); border-radius: 3px; }

  .alert-item {
    display: flex;
    gap: 12px;
    padding: 14px 24px;
    border-left: 3px solid transparent;
    transition: background 0.15s;
    cursor: pointer;
  }
  .alert-item:hover { background: var(--cream); }
  .alert-item + .alert-item { border-top: 1px solid rgba(139,0,0,0.04); }
  .alert-item.urgent  { border-left-color: var(--walnut); }
  .alert-item.warning { border-left-color: var(--gold); }
  .alert-item.info    { border-left-color: var(--sage); }

  .alert-icon {
    width: 32px; height: 32px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .alert-icon svg { width: 15px; height: 15px; }
  .alert-item.urgent  .alert-icon { background: rgba(139,0,0,0.08); color: var(--walnut); }
  .alert-item.warning .alert-icon { background: rgba(245,166,35,0.12); color: #C07A00; }
  .alert-item.info    .alert-icon { background: rgba(122,140,114,0.12); color: var(--sage); }

  .alert-body { flex: 1; min-width: 0; }
  .alert-title { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
  .alert-msg   { font-size: 12px; color: var(--text-mid); line-height: 1.4; }
  .alert-time  { font-size: 10.5px; color: var(--text-muted); margin-top: 4px; }

  /* ─── Key Insights ─── */
  .insights-grid {
    padding: 18px 24px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }
  .insight-tile {
    padding: 16px;
    border-radius: 11px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.06);
    position: relative;
    overflow: hidden;
    transition: var(--transition);
  }
  .insight-tile:hover { transform: translateY(-2px); box-shadow: var(--shadow-card); }

  .insight-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 8px;
  }
  .insight-value {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.4px;
    line-height: 1.2;
    margin-bottom: 2px;
  }
  .insight-sub {
    font-size: 11.5px;
    color: var(--text-mid);
    font-weight: 500;
  }
  .insight-tile.accent-gold   { background: linear-gradient(135deg, var(--cream-deep), #fff5e8); }
  .insight-tile.accent-sage   { background: linear-gradient(135deg, #f0f5ee, var(--cream)); }

  /* ─── KO flag chip ─── */
  .ko-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
  }
  .ko-yes { background: linear-gradient(135deg, rgba(139,0,0,0.12), rgba(192,21,42,0.08)); color: #8B0000; border: 1.5px solid rgba(139,0,0,0.25); box-shadow: 0 0 0 2px rgba(139,0,0,0.04); }
  .ko-no  { background: rgba(122,140,114,0.1); color: #4a6b42; border: 1px solid rgba(122,140,114,0.18); }

  /* ─── Compact icon-button group for table actions ─── */
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

  /* call-type pill */
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

  /* responsive tweaks */
  @media (max-width: 1100px) {
    .quick-actions { grid-template-columns: repeat(2, 1fr); }
    .insights-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 600px) {
    .quick-actions { grid-template-columns: 1fr; }
    .filters-bar { padding: 12px; }
    .filter-clear { width: 100%; text-align: center; }
  }

  /* page-load stagger */
  .quick-actions, .filters-bar { animation: fadeUp 0.5s 0.22s ease both; }
  .bottom-grid > .card:first-child { animation: fadeUp 0.5s 0.40s ease both; }
  .bottom-grid > .card:last-child  { animation: fadeUp 0.5s 0.45s ease both; }

  /* ─── Audio Modal ─── */
  .modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
  }
  .modal-content {
    background: var(--white);
    padding: 24px;
    border-radius: var(--radius);
    box-shadow: var(--shadow-hover);
    max-width: 500px;
    width: 90%;
    position: relative;
  }
  .close {
    position: absolute;
    top: 12px;
    right: 16px;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-muted);
  }
  .close:hover { color: var(--walnut); }
  #audioPlayer {
    width: 100%;
    margin-top: 16px;
  }
</style>

{{-- ═══════════════════════════════════════════════════════════
     1. GLOBAL KPI CARDS
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
    <div class="kpi-value">{{ number_format($totalEvaluations ?? 1247) }}</div>
    <div class="kpi-meta">
      <span class="kpi-delta up">▲ 8.4%</span>
      <span>vs. last month</span>
    </div>
  </div>

  {{-- This Month --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">This Month</span>
      <div class="kpi-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value">{{ number_format($evaluationsThisMonth ?? 86) }}</div>
    <div class="kpi-meta">
      <span class="kpi-delta up">▲ 12.1%</span>
      <span>{{ $evaluationsThisWeek ?? 21 }} this week</span>
    </div>
  </div>

  {{-- Average Score --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Avg. Global Score</span>
      <div class="kpi-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value">{{ number_format($averageScore ?? 82.4, 1) }}<span style="font-size:18px;color:var(--text-muted);font-weight:500;">/100</span></div>
    <div class="kpi-meta">
      <span class="kpi-delta up">▲ 2.6 pts</span>
      <span>vs. last month</span>
    </div>
  </div>

  {{-- Conseillers Evaluated --}}
  <div class="kpi-card">
    <div class="kpi-header">
      <span class="kpi-label">Conseillers Evaluated</span>
      <div class="kpi-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
      </div>
    </div>
    <div class="kpi-value">{{ number_format($totalConseillers ?? 34) }}</div>
    <div class="kpi-meta">
      <span class="kpi-delta down">▼ 2</span>
      <span>{{ $totalTeam ?? 38 }} active in team</span>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     5. QUICK ACTIONS
═══════════════════════════════════════════════════════════ --}}
<div class="quick-actions">
  <a href="{{ url('/evaluations/create') }}" class="qa-card primary">
    <div class="qa-icon">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
      </svg>
    </div>
    <div>
      <div class="qa-title">New Evaluation</div>
      <div class="qa-sub">Start a fresh call review</div>
    </div>
    <svg class="qa-arrow" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
  </a>

  <a href="{{ url('/evaluations') }}" class="qa-card sage">
    <div class="qa-icon">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
      </svg>
    </div>
    <div>
      <div class="qa-title">View All</div>
      <div class="qa-sub">{{ $totalEvaluations ?? 1247 }} evaluations</div>
    </div>
    <svg class="qa-arrow" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
  </a>

  <a href="{{ route('export') }}" class="qa-card gold">
    <div class="qa-icon">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
      </svg>
    </div>
    <div>
      <div class="qa-title">Export Data</div>
      <div class="qa-sub">Excel · CSV · PDF</div>
    </div>
    <svg class="qa-arrow" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
  </a>

  <a href="{{ url('/reports') }}" class="qa-card dark">
    <div class="qa-icon">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
      </svg>
    </div>
    <div>
      <div class="qa-title">Reports Dashboard</div>
      <div class="qa-sub">Deep analytics & trends</div>
    </div>
    <svg class="qa-arrow" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
  </a>
</div>

{{-- ═══════════════════════════════════════════════════════════
     2. PERFORMANCE OVERVIEW: chart + conseiller list
═══════════════════════════════════════════════════════════ --}}
<div class="analytics-grid">

  {{-- Score Trend Chart --}}
  <div class="card">
    <div class="card-header">
      <div>
        <h3 class="card-title">Average Score Trend</h3>
        <p class="card-subtitle">Last 6 months · global team performance</p>
      </div>
      <select class="filter-select" style="font-size:12px;padding:6px 28px 6px 10px;" id="trendRange">
        <option value="6m" selected>6 months</option>
        <option value="3m">3 months</option>
        <option value="1m">30 days</option>
        <option value="1w">7 days</option>
      </select>
    </div>

    <div class="chart-wrap">
      <div class="chart-legend">
        <div class="legend-item">
          <span class="legend-dot" style="background:linear-gradient(135deg,var(--walnut-mid),var(--walnut-light));"></span>
          Global avg.
        </div>
        <div class="legend-item">
          <span class="legend-dot" style="background:var(--gold);"></span>
          Top performer
        </div>
        <div class="legend-item">
          <span class="legend-dot" style="background:rgba(122,140,114,0.7);"></span>
          KO threshold
        </div>
      </div>

      <div class="chart-svg-wrap" id="trendChartWrap">
        <svg id="trendChart" viewBox="0 0 600 200" preserveAspectRatio="none">
          {{-- horizontal grid lines --}}
          <line x1="0" y1="40"  x2="600" y2="40"  stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>
          <line x1="0" y1="80"  x2="600" y2="80"  stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>
          <line x1="0" y1="120" x2="600" y2="120" stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>
          <line x1="0" y1="160" x2="600" y2="160" stroke="rgba(139,0,0,0.06)" stroke-dasharray="3 4"/>

          {{-- KO threshold line (70%) --}}
          <line x1="0" y1="120" x2="600" y2="120" stroke="rgba(122,140,114,0.5)" stroke-width="1.5" stroke-dasharray="6 4"/>

          {{-- Top performer band (gradient fill) --}}
          <defs>
            <linearGradient id="globalFill" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%"   stop-color="#C0152A" stop-opacity="0.18"/>
              <stop offset="100%" stop-color="#C0152A" stop-opacity="0"/>
            </linearGradient>
          </defs>

          {{-- Area under global avg --}}
          <path d="M 0 90 L 100 75 L 200 65 L 300 55 L 400 50 L 500 38 L 600 30 L 600 200 L 0 200 Z"
                fill="url(#globalFill)"/>

          {{-- Top performer line (gold) --}}
          <polyline class="chart-line2"
                    points="0,55 100,42 200,38 300,30 400,28 500,18 600,15"
                    fill="none"
                    stroke="#F5A623"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>

          {{-- Global average line (walnut) --}}
          <polyline class="chart-line"
                    points="0,90 100,75 200,65 300,55 400,50 500,38 600,30"
                    fill="none"
                    stroke="url(#lineGrad)"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"/>

          <defs>
            <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
              <stop offset="0%"   stop-color="#8B0000"/>
              <stop offset="100%" stop-color="#D93848"/>
            </linearGradient>
          </defs>

          {{-- data points (global avg) --}}
          <circle class="trend-dot" cx="0"   cy="90" r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Dec · 76%"/>
          <circle class="trend-dot" cx="100" cy="75" r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Jan · 80%"/>
          <circle class="trend-dot" cx="200" cy="65" r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Feb · 82%"/>
          <circle class="trend-dot" cx="300" cy="55" r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Mar · 84%"/>
          <circle class="trend-dot" cx="400" cy="50" r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Apr · 85%"/>
          <circle class="trend-dot" cx="500" cy="38" r="4" fill="#fff" stroke="#C0152A" stroke-width="2.5" data-label="Apr · 88%"/>
          <circle class="trend-dot" cx="600" cy="30" r="5" fill="#F5A623" stroke="#fff" stroke-width="2.5" data-label="May · 91%"/>
        </svg>
        <div class="chart-tooltip" id="trendTip"></div>
      </div>

      <div class="chart-months">
        <span>Dec</span><span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span>
      </div>
    </div>
  </div>

  {{-- Performance by Conseiller --}}
  <div class="card">
    <div class="card-header">
      <div>
        <h3 class="card-title">Performance by Conseiller</h3>
        <p class="card-subtitle">Avg. score · this period</p>
      </div>
      <a href="{{ url('/conseillers') }}" class="card-action">All</a>
    </div>

    <div class="conseiller-list">
      @php
        $conseillers = $conseillers ?? collect();
      @endphp


      

      @foreach($conseillers as $c)
        @php
          $scoreClass = $c['score'] >= 85 ? 'high' : ($c['score'] >= 75 ? 'mid' : 'low');
          $barColor   = $c['score'] >= 85 ? 'linear-gradient(90deg,#7A8C72,#9CB394)'
                       : ($c['score'] >= 75 ? 'linear-gradient(90deg,#F5A623,#F7BC54)'
                       : 'linear-gradient(90deg,#8B0000,#C0152A)');
        @endphp
        <div class="cons-row">
          <div class="cons-av" style="background: {{ $c['color'] }};">{{ $c['initials'] }}</div>
          <div class="cons-info">
            <div class="cons-name">{{ $c['name'] }}</div>
            <div class="cons-meta">
              <div class="cons-bar">
                <div class="cons-bar-fill" style="width: {{ $c['score'] }}%; background: {{ $barColor }};"></div>
              </div>
              <span class="cons-evals">{{ $c['evals'] }} evals</span>
            </div>
          </div>
          <div class="cons-score {{ $scoreClass }}">{{ $c['score'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     4. FILTERS BAR
═══════════════════════════════════════════════════════════ --}}
<form method="GET" action="{{ url('/dashboard') }}" class="filters-bar" id="filtersForm">
  <span class="filter-label">Filter by</span>

  <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}" placeholder="From">
  <input type="date" name="date_to"   class="filter-input" value="{{ request('date_to') }}"   placeholder="To">

  <select name="conseiller" class="filter-select">
    <option value="">All conseillers</option>
    @foreach(($conseillers ?? []) as $c)
      <option value="{{ $c['initials'] }}" {{ request('conseiller') == $c['initials'] ? 'selected' : '' }}>
        {{ $c['name'] }}
      </option>
    @endforeach
  </select>

  <select name="call_type" class="filter-select">
    <option value="">All call types</option>
    <option value="incoming" {{ request('call_type') == 'incoming' ? 'selected' : '' }}>Incoming</option>
    <option value="outgoing" {{ request('call_type') == 'outgoing' ? 'selected' : '' }}>Outgoing</option>
  </select>

  <select name="status" class="filter-select">
    <option value="">All statuses</option>
    <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
    <option value="signed"    {{ request('status') == 'signed'    ? 'selected' : '' }}>Signed</option>
  </select>

  <select name="ko" class="filter-select">
    <option value="">KO: any</option>
    <option value="with"    {{ request('ko') == 'with'    ? 'selected' : '' }}>With KO</option>
    <option value="without" {{ request('ko') == 'without' ? 'selected' : '' }}>Without KO</option>
  </select>

  <a href="{{ url('/dashboard') }}" class="filter-clear">Clear filters</a>
</form>

{{-- ═══════════════════════════════════════════════════════════
     3. LATEST EVALUATIONS TABLE
═══════════════════════════════════════════════════════════ --}}
<div class="card" style="margin-bottom:24px;">
  <div class="card-header">
    <div>
      <h3 class="card-title">Latest Evaluations</h3>
      <p class="card-subtitle">Most recent {{ count($evaluations ?? []) ?: 8 }} entries</p>
    </div>
    <a href="{{ url('/evaluations') }}" class="card-action">View all →</a>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Conseiller</th>
          <th>Call Type</th>
          <th>Date</th>
          <th>Score</th>
          <th>KO</th>
          <th>Status</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @php
          $evaluations = $evaluations ?? collect();
        @endphp

        @foreach($evaluations as $ev)
          @php
            $scoreColor = $ev['score'] >= 85 ? '#4a6b42' : ($ev['score'] >= 70 ? '#C07A00' : '#8B0000');
            $barFill    = $ev['score'] >= 85 ? 'linear-gradient(90deg,#7A8C72,#9CB394)'
                         : ($ev['score'] >= 70 ? 'linear-gradient(90deg,#F5A623,#F7BC54)'
                         : 'linear-gradient(90deg,#8B0000,#C0152A)');
          @endphp
          <tr>
            <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--walnut);font-size:12.5px;">
              {{ $ev['id'] }}
            </td>
            <td>
              <div class="advisor-cell">
                <div class="advisor-av" style="background: {{ $ev['avatar'] }};">{{ $ev['initials'] }}</div>
                <div>
                  <div class="advisor-name">{{ $ev['name'] }}</div>
                  <div class="advisor-team">Team A</div>
                </div>
              </div>
            </td>
            <td>
              @if($ev['type'] === 'incoming')
                <span class="call-pill call-in">↓ Incoming</span>
              @else
                <span class="call-pill call-out">↑ Outgoing</span>
              @endif
            </td>
            <td class="date-cell">{{ $ev['date'] }}</td>
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
                @case('completed') <span class="status-badge status-pending">Completed</span> @break
                @case('draft')     <span class="status-badge status-draft">Draft</span> @break
              @endswitch
            </td>
            <td style="text-align:right;">
              <div class="icon-actions">
                <button class="icon-btn" title="View signature" onclick='showSignature({{ json_encode($ev['signature']) }}, {{ json_encode($ev['name']) }})'>
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>

                <button class="icon-btn" title="Audio" onclick="playAudio('{{ asset('storage/' . $ev['audio']) }}')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </button>
                <button class="icon-btn" title="Download report">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     6 + 7. ALERTS  +  KEY INSIGHTS
═══════════════════════════════════════════════════════════ --}}
<div class="bottom-grid">

  {{-- Alerts / Notifications --}}
  <div class="card">
    <div class="card-header">
      <div>
        <h3 class="card-title">Alerts & Notifications</h3>
        <p class="card-subtitle">Items needing your attention</p>
      </div>
      <span class="card-action" style="cursor:default;">{{ count($alerts ?? []) ?: 5 }} new</span>
    </div>

    <div class="alert-list">
      @php
        $alerts = $alerts ?? collect();
      @endphp

      @foreach($alerts as $alert)
        <div class="alert-item {{ $alert['type'] }}">
          <div class="alert-icon">
            @switch($alert['type'])
              @case('urgent')
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @break
              @case('warning')
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @break
              @case('info')
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @break
            @endswitch
          </div>
          <div class="alert-body">
            <div class="alert-title">{{ $alert['title'] }}</div>
            <div class="alert-msg">{{ $alert['msg'] }}</div>
            <div class="alert-time">{{ $alert['time'] }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Key Insights --}}
  <div class="card">
    <div class="card-header">
      <div>
        <h3 class="card-title">Key Insights</h3>
        <p class="card-subtitle">Snapshot · {{ now()->format('F Y') }}</p>
      </div>
      <a href="{{ url('/reports') }}" class="card-action">Full report</a>
    </div>

    <div class="insights-grid">
      <div class="insight-tile accent-sage">
        <div class="insight-label">🏆 Top performer</div>
        <div class="insight-value">{{ $topPerformer['name'] ?? 'Fatima Z. Bennani' }}</div>
        <div class="insight-sub">Avg. {{ $topPerformer['score'] ?? 94 }}/100 · {{ $topPerformer['evals'] ?? 18 }} evals</div>
      </div>

      <div class="insight-tile">
        <div class="insight-label">📉 Needs coaching</div>
        <div class="insight-value">{{ $lowPerformer['name'] ?? 'Youssef Idrissi' }}</div>
        <div class="insight-sub">Avg. {{ $lowPerformer['score'] ?? 68 }}/100 · {{ $lowPerformer['evals'] ?? 8 }} evals</div>
      </div>

      <div class="insight-tile">
        <div class="insight-label">⚠ KO this month</div>
        <div class="insight-value">{{ $koCount ?? 7 }} <span style="font-size:13px;color:var(--text-muted);font-weight:500;">cases</span></div>
        <div class="insight-sub">{{ $koPercent ?? '8.1' }}% of all evaluations</div>
      </div>

      <div class="insight-tile accent-gold">
        <div class="insight-label">📞 Most evaluated</div>
        <div class="insight-value">Incoming calls</div>
        <div class="insight-sub">{{ $incomingPercent ?? 64 }}% · {{ $outgoingPercent ?? 36 }}% outgoing</div>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     PAGE-SPECIFIC SCRIPTS
═══════════════════════════════════════════════════════════ --}}
<script>
  // Trend chart tooltip
  (function() {
    const wrap = document.getElementById('trendChartWrap');
    const tip  = document.getElementById('trendTip');
    const dots = document.querySelectorAll('#trendChart .trend-dot');
    const svg  = document.getElementById('trendChart');

    dots.forEach(dot => {
      dot.style.cursor = 'pointer';
      dot.style.transition = 'r 0.2s, fill 0.2s';

      dot.addEventListener('mouseenter', function() {
        const wrapRect = wrap.getBoundingClientRect();
        const svgRect  = svg.getBoundingClientRect();
        const cx = parseFloat(dot.getAttribute('cx'));
        const cy = parseFloat(dot.getAttribute('cy'));
        const x = (cx / 600) * svgRect.width;
        const y = (cy / 200) * svgRect.height;

        tip.textContent = dot.getAttribute('data-label');
        tip.style.left = (x - 50) + 'px';
        tip.style.top  = (y - 36) + 'px';
        tip.classList.add('show');

        dot.setAttribute('r', '6');
      });

      dot.addEventListener('mouseleave', function() {
        tip.classList.remove('show');
        dot.setAttribute('r', dot === dots[dots.length - 1] ? '5' : '4');
      });
    });
  })();

  // Auto-submit filters on change (optional — comment out if you prefer manual submit)
  document.querySelectorAll('#filtersForm select, #filtersForm input[type="date"]').forEach(el => {
    el.addEventListener('change', () => {
      // document.getElementById('filtersForm').submit();
    });
  });

  // Animate score bars on load
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.querySelectorAll('.cons-bar-fill, .score-bar-fill').forEach(b => {
        const w = b.style.width;
        b.style.width = '0';
        requestAnimationFrame(() => { b.style.width = w; });
      });
    }, 150);
  });

  // Audio modal functions
  function playAudio(src) {
    console.log('Playing audio:', src);
    document.getElementById('audioPlayer').src = src;
    document.getElementById('audioModal').style.display = 'block';
  }
  function closeAudioModal() {
    document.getElementById('audioModal').style.display = 'none';
    document.getElementById('audioPlayer').pause();
  }

  function showSignature(signature, name) {
    const modal = document.getElementById('signatureModal');
    const img = document.getElementById('signatureImage');
    const title = document.getElementById('signatureTitle');
    const placeholder = document.getElementById('signaturePlaceholder');

    title.textContent = name + ' — Signature';
    if (!signature) {
      img.style.display = 'none';
      placeholder.style.display = 'block';
      placeholder.textContent = 'No signature available for this evaluation.';
    } else {
      img.src = signature;
      img.style.display = 'block';
      placeholder.style.display = 'none';
    }

    modal.style.display = 'flex';
  }

  function closeSignatureModal() {
    const modal = document.getElementById('signatureModal');
    modal.style.display = 'none';
  }

  // Close modal on outside click
  window.onclick = function(event) {
    const audioModal = document.getElementById('audioModal');
    const signatureModal = document.getElementById('signatureModal');
    if (event.target == audioModal) {
      closeAudioModal();
    }
    if (event.target == signatureModal) {
      closeSignatureModal();
    }
  }
</script>

{{-- Audio Modal --}}
<div id="audioModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeAudioModal()">&times;</span>
    <h3 style="margin:0 0 16px 0; color: var(--text-dark);">Listen to Audio</h3>
    <audio id="audioPlayer" controls></audio>
  </div>
</div>

{{-- Signature Modal --}}
<div id="signatureModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeSignatureModal()">&times;</span>
    <h3 id="signatureTitle" style="margin:0 0 16px 0; color: var(--text-dark);"></h3>
    <div id="signaturePlaceholder" style="font-size:14px;color:var(--text-muted);">No signature available.</div>
    <img id="signatureImage" src="" alt="Evaluator signature" style="width:100%;border:1px solid rgba(139,0,0,0.1);border-radius:10px;display:none;" />
  </div>
</div>

@endsection
