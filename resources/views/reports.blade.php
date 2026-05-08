@extends('layouts.app')

@section('title', 'Reports & Analytics — KiteaCall')

@section('topbar_title', 'Reports & Analytics')
@section('topbar_subtitle', 'Transform evaluation data into quality insights and operational decisions')

@section('content')

<style>
  /* ═══════════════════════════════════════
     REPORTS PAGE STYLES
  ═══════════════════════════════════════ */

  /* ─── SECTION HEADING ─── */
  .section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }
  .section-heading-left { display: flex; flex-direction: column; gap: 2px; }
  .section-heading h2 {
    font-family: 'Syne', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.3px;
  }
  .section-heading p { font-size: 12px; color: var(--text-muted); }

  /* ─── FILTERS CARD ─── */
  .filters-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    padding: 20px 24px;
    margin-bottom: 24px;
    animation: fadeUp 0.4s ease both;
  }

  .filters-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .filters-title {
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .filters-title svg { width: 16px; height: 16px; color: var(--walnut-mid); }

  .filter-reset {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    padding: 5px 12px;
    border-radius: 8px;
    border: 1px solid rgba(139,0,0,0.1);
    background: transparent;
    transition: var(--transition);
  }
  .filter-reset:hover { background: var(--cream-deep); color: var(--walnut-mid); }

  .filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
    margin-bottom: 14px;
  }

  .filter-group { display: flex; flex-direction: column; gap: 5px; }
  .filter-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    color: var(--text-muted);
  }

  .filter-select,
  .filter-input {
    padding: 9px 12px;
    border-radius: 9px;
    border: 1px solid rgba(139,0,0,0.12);
    background: var(--cream);
    color: var(--text-dark);
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    outline: none;
    transition: var(--transition);
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239C7078' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 30px;
    cursor: pointer;
  }
  .filter-select:focus,
  .filter-input:focus {
    border-color: rgba(192,21,42,0.35);
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(192,21,42,0.06);
  }
  .filter-input { background-image: none; padding-right: 12px; }

  .filter-range { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

  .date-tabs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }
  .date-tab {
    padding: 7px 13px;
    border-radius: 8px;
    border: 1px solid rgba(139,0,0,0.1);
    background: var(--cream);
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
  }
  .date-tab:hover { background: var(--cream-deep); color: var(--walnut-mid); }
  .date-tab.active {
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 2px 8px rgba(192,21,42,0.25);
  }

  .filters-actions {
    display: flex;
    gap: 10px;
    padding-top: 14px;
    border-top: 1px solid rgba(139,0,0,0.05);
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    border: none;
    transition: var(--transition);
    box-shadow: 0 2px 10px rgba(192,21,42,0.25);
  }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(192,21,42,0.35); }
  .btn-primary svg { width: 15px; height: 15px; }

  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 18px;
    border-radius: 10px;
    background: var(--cream);
    color: var(--text-mid);
    font-size: 13px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    border: 1px solid rgba(139,0,0,0.12);
    transition: var(--transition);
  }
  .btn-secondary:hover { background: var(--cream-deep); border-color: rgba(139,0,0,0.2); }
  .btn-secondary svg { width: 15px; height: 15px; }

  /* ─── SEARCH BAR ─── */
  .search-bar-wrap {
    margin-bottom: 24px;
    animation: fadeUp 0.4s 0.05s ease both;
  }
  .search-bar {
    position: relative;
    max-width: 480px;
  }
  .search-bar svg {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: var(--text-muted);
    pointer-events: none;
  }
  .search-bar input {
    width: 100%;
    padding: 11px 14px 11px 40px;
    border-radius: 12px;
    border: 1px solid rgba(139,0,0,0.12);
    background: var(--white);
    font-size: 13.5px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text-dark);
    outline: none;
    transition: var(--transition);
    box-shadow: var(--shadow-card);
  }
  .search-bar input::placeholder { color: var(--text-muted); }
  .search-bar input:focus {
    border-color: rgba(192,21,42,0.3);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.07), var(--shadow-card);
  }

  /* ─── KPI GRID ─── */
  .reports-kpi-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
    margin-bottom: 24px;
  }

  .r-kpi {
    background: var(--white);
    border-radius: var(--radius);
    padding: 18px 18px 16px;
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    position: relative;
    overflow: hidden;
    transition: var(--transition);
    cursor: default;
    animation: fadeUp 0.4s ease both;
  }
  .r-kpi:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

  .r-kpi::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--radius) var(--radius) 0 0;
  }
  .r-kpi:nth-child(1)::before { background: linear-gradient(90deg, var(--walnut), var(--walnut-mid)); }
  .r-kpi:nth-child(2)::before { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
  .r-kpi:nth-child(3)::before { background: linear-gradient(90deg, #4a6b42, var(--sage)); }
  .r-kpi:nth-child(4)::before { background: linear-gradient(90deg, var(--walnut-light), #ff6b7a); }
  .r-kpi:nth-child(5)::before { background: linear-gradient(90deg, #e07b00, var(--gold)); }
  .r-kpi:nth-child(6)::before { background: linear-gradient(90deg, var(--walnut-mid), var(--walnut-light)); }

  .r-kpi-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
    font-size: 16px;
  }
  .r-kpi:nth-child(1) .r-kpi-icon { background: rgba(139,0,0,0.08); }
  .r-kpi:nth-child(2) .r-kpi-icon { background: rgba(245,166,35,0.12); }
  .r-kpi:nth-child(3) .r-kpi-icon { background: rgba(122,140,114,0.12); }
  .r-kpi:nth-child(4) .r-kpi-icon { background: rgba(217,56,72,0.08); }
  .r-kpi:nth-child(5) .r-kpi-icon { background: rgba(224,123,0,0.1); }
  .r-kpi:nth-child(6) .r-kpi-icon { background: rgba(192,21,42,0.08); }

  .r-kpi-val {
    font-family: 'Syne', sans-serif;
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.8px;
    line-height: 1;
    margin-bottom: 4px;
  }
  .r-kpi-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    color: var(--text-muted);
  }
  .r-kpi-sub {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .badge-up { color: #4a6b42; font-weight: 700; }
  .badge-dn { color: var(--walnut-light); font-weight: 700; }

  /* ─── CHARTS ROW ─── */
  .charts-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
  }

  .chart-header-tabs {
    display: flex;
    gap: 4px;
  }
  .cht {
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: var(--transition);
    color: var(--text-muted);
    background: transparent;
  }
  .cht:hover { background: var(--cream); color: var(--text-dark); }
  .cht.active {
    background: var(--cream-deep);
    color: var(--walnut-mid);
    border-color: rgba(192,21,42,0.15);
  }

  /* ─── DONUT ─── */
  .donut-wrap {
    padding: 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }

  .donut-svg { position: relative; width: 140px; height: 140px; }
  .donut-svg svg { width: 100%; height: 100%; }
  .donut-center {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .donut-pct {
    font-family: 'Syne', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -1px;
  }
  .donut-sub { font-size: 10px; color: var(--text-muted); font-weight: 600; }

  .donut-legend {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .dl-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12.5px;
  }
  .dl-left { display: flex; align-items: center; gap: 8px; }
  .dl-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
  .dl-name { color: var(--text-dark); font-weight: 500; }
  .dl-val { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--text-dark); }

  /* ─── RANKING TABLE ─── */
  .rank-num {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 15px;
    width: 28px;
    text-align: center;
  }
  .rank-1 { color: var(--gold); }
  .rank-2 { color: #9E9E9E; }
  .rank-3 { color: #C07B4A; }
  .rank-other { color: var(--text-muted); font-size: 12px; }

  /* ─── KO SECTION ─── */
  .ko-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
  }

  .ko-bar-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(139,0,0,0.04);
  }
  .ko-bar-item:last-child { border-bottom: none; }
  .ko-bar-label { font-size: 12.5px; font-weight: 500; color: var(--text-dark); flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .ko-bar-track { width: 100px; height: 7px; background: rgba(139,0,0,0.07); border-radius: 4px; overflow: hidden; flex-shrink: 0; }
  .ko-bar-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, var(--walnut), var(--walnut-light)); transition: width 1s cubic-bezier(0.4,0,0.2,1); }
  .ko-bar-count { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; color: var(--walnut-mid); flex-shrink: 0; width: 28px; text-align: right; }

  .ko-advisor-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(139,0,0,0.04);
  }
  .ko-advisor-item:last-child { border-bottom: none; }

  /* ─── CRITERIA TABLE ─── */
  .criteria-bar-track { width: 80px; height: 5px; background: rgba(139,0,0,0.07); border-radius: 3px; overflow: hidden; display: inline-flex; }
  .criteria-bar-fill { height: 100%; border-radius: 3px; transition: width 1s cubic-bezier(0.4,0,0.2,1); }

  .fail-rate {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
  }
  .fail-low  { background: rgba(122,140,114,0.12); color: #3d6035; }
  .fail-mid  { background: rgba(245,166,35,0.12);  color: #8B5E00; }
  .fail-high { background: rgba(217,56,72,0.09);   color: var(--walnut-light); }

  /* ─── HEATMAP ─── */
  .heatmap-wrap {
    padding: 20px 24px;
    overflow-x: auto;
  }

  .heatmap-grid {
    display: grid;
    grid-template-columns: 60px repeat(7, 1fr);
    gap: 4px;
    min-width: 480px;
  }

  .hm-cell {
    aspect-ratio: 1;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 600;
    transition: transform 0.15s;
    cursor: default;
    max-height: 36px;
  }
  .hm-cell:hover { transform: scale(1.15); z-index: 5; }
  .hm-label { color: var(--text-muted); font-size: 10.5px; font-weight: 600; display: flex; align-items: center; justify-content: flex-end; padding-right: 6px; }

  .hm-0 { background: rgba(139,0,0,0.04); color: transparent; }
  .hm-1 { background: rgba(192,21,42,0.10); color: var(--walnut-mid); }
  .hm-2 { background: rgba(192,21,42,0.20); color: var(--walnut-mid); }
  .hm-3 { background: rgba(192,21,42,0.32); color: #fff; }
  .hm-4 { background: rgba(192,21,42,0.52); color: #fff; }
  .hm-5 { background: var(--walnut-mid); color: #fff; }

  .hm-day-label {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-muted);
    text-align: center;
    padding-bottom: 4px;
    display: flex; align-items: flex-end; justify-content: center;
  }

  /* ─── EXPORT SECTION ─── */
  .export-card {
    background: linear-gradient(135deg, #1A0204 0%, #2D0008 60%, #3D0010 100%);
    border-radius: var(--radius);
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
  }
  .export-card::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(245,166,35,0.15), transparent 70%);
    border-radius: 50%;
  }
  .export-title {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
  }
  .export-desc { font-size: 12.5px; color: rgba(255,255,255,0.5); }

  .export-btns { display: flex; gap: 10px; flex-shrink: 0; }

  .export-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    border: none;
    transition: var(--transition);
  }
  .export-btn svg { width: 15px; height: 15px; }
  .export-excel { background: linear-gradient(135deg, #1d6f42, #207a4a); color: #fff; box-shadow: 0 2px 10px rgba(29,111,66,0.4); }
  .export-excel:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(29,111,66,0.5); }
  .export-csv   { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.15); }
  .export-csv:hover { background: rgba(255,255,255,0.16); }
  .export-pdf   { background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light)); color: #fff; box-shadow: 0 2px 10px rgba(192,21,42,0.35); }
  .export-pdf:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(192,21,42,0.45); }

  /* ─── TOP 5 LEADERBOARD ─── */
  .top5-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 24px;
    border-bottom: 1px solid rgba(139,0,0,0.04);
    transition: background 0.15s;
    cursor: pointer;
  }
  .top5-item:last-child { border-bottom: none; }
  .top5-item:hover { background: var(--cream); }

  .top5-bar-wrap {
    flex: 1;
    height: 6px;
    background: rgba(139,0,0,0.07);
    border-radius: 3px;
    overflow: hidden;
  }
  .top5-bar-fill {
    height: 100%;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--walnut-mid), var(--gold));
    transition: width 1.2s cubic-bezier(0.4,0,0.2,1);
  }

  /* ─── CALL TYPE CARDS ─── */
  .call-type-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    padding: 20px 24px;
  }
  .ct-card {
    border-radius: 12px;
    padding: 18px;
    border: 1px solid rgba(139,0,0,0.08);
    transition: var(--transition);
    cursor: default;
  }
  .ct-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
  .ct-card.incoming { background: linear-gradient(135deg, rgba(122,140,114,0.06), rgba(122,140,114,0.12)); }
  .ct-card.outgoing { background: linear-gradient(135deg, rgba(245,166,35,0.06), rgba(245,166,35,0.12)); }

  .ct-head { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
  .ct-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
  }
  .ct-icon svg { width: 17px; height: 17px; }
  .ct-card.incoming .ct-icon { background: rgba(122,140,114,0.2); color: var(--sage); }
  .ct-card.outgoing .ct-icon { background: rgba(245,166,35,0.2); color: #B87000; }
  .ct-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; color: var(--text-dark); }
  .ct-count { font-size: 11px; color: var(--text-muted); }

  .ct-metric { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-bottom: 1px solid rgba(139,0,0,0.04); }
  .ct-metric:last-child { border-bottom: none; }
  .ct-metric-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
  .ct-metric-val { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: var(--text-dark); }

  /* ─── DETAILED TABLE ─── */
  .detail-table-wrap { overflow-x: auto; }
  .detail-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .detail-table th {
    padding: 10px 16px;
    text-align: left;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--text-muted);
    background: var(--cream);
    border-bottom: 1px solid rgba(139,0,0,0.06);
    white-space: nowrap;
  }
  .detail-table td {
    padding: 11px 16px;
    border-bottom: 1px solid rgba(139,0,0,0.04);
    vertical-align: middle;
    white-space: nowrap;
    color: var(--text-dark);
  }
  .detail-table tbody tr:hover { background: rgba(255,245,245,0.8); }
  .detail-table tbody tr:last-child td { border-bottom: none; }

  .eval-id { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 12px; color: var(--walnut-mid); }
  .has-audio { color: var(--sage); }
  .no-audio  { color: var(--text-muted); opacity: 0.4; }

  .ko-chip {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 8px;
    border-radius: 5px;
    font-size: 10.5px;
    font-weight: 700;
  }
  .ko-yes { background: rgba(139,0,0,0.08); color: var(--walnut); }
  .ko-no  { background: rgba(122,140,114,0.1); color: #3d6035; }

  .tbl-actions { display: flex; gap: 6px; }
  .tbl-btn {
    padding: 4px 10px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid rgba(139,0,0,0.15);
    background: var(--cream);
    color: var(--walnut-mid);
    transition: var(--transition);
    white-space: nowrap;
  }
  .tbl-btn:hover { background: var(--walnut-mid); color: #fff; border-color: var(--walnut-mid); }

  .pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid rgba(139,0,0,0.05);
  }
  .pag-info { font-size: 12px; color: var(--text-muted); }
  .pag-btns { display: flex; gap: 4px; }
  .pag-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid rgba(139,0,0,0.12);
    background: var(--cream);
    color: var(--text-muted);
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition);
  }
  .pag-btn:hover { background: var(--cream-deep); color: var(--walnut-mid); }
  .pag-btn.active { background: var(--walnut-mid); color: #fff; border-color: var(--walnut-mid); }

  /* ─── RESPONSIVE ─── */
  @media (max-width: 1200px) {
    .reports-kpi-grid { grid-template-columns: repeat(3, 1fr); }
    .charts-row { grid-template-columns: 1fr; }
    .ko-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 768px) {
    .reports-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .filters-grid { grid-template-columns: repeat(2, 1fr); }
    .export-card { flex-direction: column; gap: 16px; align-items: flex-start; }
    .call-type-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 480px) {
    .reports-kpi-grid { grid-template-columns: 1fr 1fr; }
    .filters-grid { grid-template-columns: 1fr; }
    .export-btns { flex-wrap: wrap; }
  }
</style>

{{-- ═══════════════════════════════════ --}}
{{-- 0. SEARCH BAR                       --}}
{{-- ═══════════════════════════════════ --}}
<div class="search-bar-wrap">
  <div class="search-bar">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <input type="text" placeholder="Search by conseiller name, evaluation ID or recording reference…">
  </div>
</div>

{{-- ═══════════════════════════════════ --}}
{{-- 1. FILTERS                          --}}
{{-- ═══════════════════════════════════ --}}
<div class="filters-card">
  <div class="filters-top">
    <div class="filters-title">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
      </svg>
      Filters &amp; Parameters
    </div>
    <button class="filter-reset" onclick="resetFilters()">Reset all</button>
  </div>

  {{-- Date quick tabs --}}
  <div style="margin-bottom:14px;">
    <div class="filter-label" style="margin-bottom:7px;">Date Range</div>
    <div class="date-tabs">
      <button class="date-tab" onclick="setDateTab(this, 'today')">Today</button>
      <button class="date-tab active" onclick="setDateTab(this, 'week')">This Week</button>
      <button class="date-tab" onclick="setDateTab(this, 'month')">This Month</button>
      <button class="date-tab" onclick="setDateTab(this, 'custom')">Custom Range</button>
    </div>
  </div>

  {{-- Custom date range (hidden by default) --}}
  <div id="customRange" style="display:none; margin-bottom:14px;">
    <div class="filter-range" style="max-width:340px;">
      <div class="filter-group">
        <label class="filter-label">From</label>
        <input type="date" class="filter-input">
      </div>
      <div class="filter-group">
        <label class="filter-label">To</label>
        <input type="date" class="filter-input">
      </div>
    </div>
  </div>

  <div class="filters-grid">
    <div class="filter-group">
      <label class="filter-label">Conseiller</label>
      <select class="filter-select">
        <option value="">All conseillers</option>
        <option>Amina Benali</option>
        <option>Karim Tahiri</option>
        <option>Sara Elouafi</option>
        <option>Youssef Chraibi</option>
        <option>Nadia Idrissi</option>
      </select>
    </div>
    <div class="filter-group">
      <label class="filter-label">Manager</label>
      <select class="filter-select">
        <option value="">All managers</option>
        <option>Hassan Alami</option>
        <option>Fatima Zohra</option>
      </select>
    </div>
    <div class="filter-group">
      <label class="filter-label">Call Type</label>
      <select class="filter-select">
        <option value="">All types</option>
        <option>Incoming</option>
        <option>Outgoing</option>
      </select>
    </div>
    <div class="filter-group">
      <label class="filter-label">Status</label>
      <select class="filter-select">
        <option value="">All statuses</option>
        <option>Completed</option>
        <option>Signed</option>
        <option>Pending</option>
        <option>Draft</option>
      </select>
    </div>
    <div class="filter-group">
      <label class="filter-label">KO Filter</label>
      <select class="filter-select">
        <option value="">All evaluations</option>
        <option>With KO</option>
        <option>Without KO</option>
      </select>
    </div>
    <div class="filter-group">
      <label class="filter-label">Min Score</label>
      <input type="number" class="filter-input" placeholder="0" min="0" max="100">
    </div>
    <div class="filter-group">
      <label class="filter-label">Max Score</label>
      <input type="number" class="filter-input" placeholder="100" min="0" max="100">
    </div>
  </div>

  <div class="filters-actions">
    <button class="btn-primary">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
      </svg>
      Apply Filters
    </button>
    <button class="btn-secondary">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
      </svg>
      Refresh
    </button>
  </div>
</div>

{{-- ═══════════════════════════════════ --}}
{{-- 2. KPI METRICS                      --}}
{{-- ═══════════════════════════════════ --}}
<div class="section-heading" style="animation: fadeUp 0.4s 0.1s ease both;">
  <div class="section-heading-left">
    <h2>Global Statistics</h2>
    <p>Aggregated KPIs for the selected period</p>
  </div>
</div>

<div class="reports-kpi-grid">
  <div class="r-kpi" style="animation-delay:0.08s">
    <div class="r-kpi-icon">📋</div>
    <div class="r-kpi-val">1,284</div>
    <div class="r-kpi-label">Total Evaluations</div>
    <div class="r-kpi-sub"><span class="badge-up">↑ 12%</span> vs last period</div>
  </div>
  <div class="r-kpi" style="animation-delay:0.12s">
    <div class="r-kpi-icon">⭐</div>
    <div class="r-kpi-val">83.4%</div>
    <div class="r-kpi-label">Avg Global Score</div>
    <div class="r-kpi-sub"><span class="badge-up">↑ 2.1pt</span> vs last period</div>
  </div>
  <div class="r-kpi" style="animation-delay:0.16s">
    <div class="r-kpi-icon">🏆</div>
    <div class="r-kpi-val">98.5%</div>
    <div class="r-kpi-label">Highest Score</div>
    <div class="r-kpi-sub">Amina Benali · May 6</div>
  </div>
  <div class="r-kpi" style="animation-delay:0.20s">
    <div class="r-kpi-icon">⚠️</div>
    <div class="r-kpi-val">42.0%</div>
    <div class="r-kpi-label">Lowest Score</div>
    <div class="r-kpi-sub"><span class="badge-dn">↓ KO flagged</span></div>
  </div>
  <div class="r-kpi" style="animation-delay:0.24s">
    <div class="r-kpi-icon">🚨</div>
    <div class="r-kpi-val">87</div>
    <div class="r-kpi-label">Total KOs Detected</div>
    <div class="r-kpi-sub"><span class="badge-dn">↑ 4</span> vs last period</div>
  </div>
  <div class="r-kpi" style="animation-delay:0.28s">
    <div class="r-kpi-icon">✅</div>
    <div class="r-kpi-val">962</div>
    <div class="r-kpi-label">Signed Evaluations</div>
    <div class="r-kpi-sub">74.9% sign rate · 322 pending</div>
  </div>
</div>

{{-- ═══════════════════════════════════ --}}
{{-- 3. PERFORMANCE EVOLUTION + CALL TYPE --}}
{{-- ═══════════════════════════════════ --}}
<div class="section-heading" style="animation: fadeUp 0.4s 0.15s ease both; margin-bottom: 16px;">
  <div class="section-heading-left">
    <h2>Performance Evolution</h2>
    <p>Average quality score trends over time</p>
  </div>
</div>

<div class="charts-row" style="animation: fadeUp 0.4s 0.18s ease both;">

  {{-- Line chart --}}
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Score Evolution</div>
        <div class="card-subtitle">Daily, weekly & monthly quality trend</div>
      </div>
      <div class="chart-header-tabs">
        <button class="cht" onclick="setChartTab(this)">Daily</button>
        <button class="cht active" onclick="setChartTab(this)">Weekly</button>
        <button class="cht" onclick="setChartTab(this)">Monthly</button>
      </div>
    </div>
    <div class="chart-wrap">
      <div class="chart-legend">
        <div class="legend-item"><div class="legend-dot" style="background:var(--walnut-mid)"></div>Avg Score</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--gold)"></div>Target (85%)</div>
        <div class="legend-item"><div class="legend-dot" style="background:var(--sage)"></div>Best Performer</div>
      </div>
      <div class="chart-svg-wrap" id="chartWrap" style="position:relative;">
        <div class="chart-tooltip" id="chartTip"></div>
        <svg id="chartSvg" viewBox="0 0 600 200" preserveAspectRatio="none">
          <defs>
            <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="rgba(192,21,42,0.15)"/>
              <stop offset="100%" stop-color="rgba(192,21,42,0)"/>
            </linearGradient>
            <linearGradient id="goldGrad" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="rgba(245,166,35,0.1)"/>
              <stop offset="100%" stop-color="rgba(245,166,35,0)"/>
            </linearGradient>
          </defs>

          <!-- Grid lines -->
          <line x1="0" y1="50"  x2="600" y2="50"  stroke="rgba(139,0,0,0.06)" stroke-width="1"/>
          <line x1="0" y1="100" x2="600" y2="100" stroke="rgba(139,0,0,0.06)" stroke-width="1"/>
          <line x1="0" y1="150" x2="600" y2="150" stroke="rgba(139,0,0,0.06)" stroke-width="1"/>
          <text x="0" y="50"  font-size="9" fill="#9C7078" dy="-3">100%</text>
          <text x="0" y="100" font-size="9" fill="#9C7078" dy="-3">75%</text>
          <text x="0" y="150" font-size="9" fill="#9C7078" dy="-3">50%</text>

          <!-- Area fill: avg score -->
          <path class="chart-line"
            d="M20,130 L120,105 L220,90 L320,80 L420,65 L520,40 L580,25"
            fill="url(#areaGrad)" stroke="none" opacity="0.8"/>

          <!-- Avg Score line -->
          <path class="chart-line"
            d="M20,130 L120,105 L220,90 L320,80 L420,65 L520,40 L580,25"
            fill="none" stroke="var(--walnut-mid)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

          <!-- Target line (85%) -->
          <line x1="0" y1="60" x2="600" y2="60" stroke="var(--gold)" stroke-width="1.5" stroke-dasharray="6 4" opacity="0.7" class="chart-line2"/>

          <!-- Best performer line -->
          <path class="chart-line3"
            d="M20,100 L120,80 L220,65 L320,50 L420,35 L520,20 L580,12"
            fill="none" stroke="var(--sage)" stroke-width="1.5" stroke-dasharray="4 3" stroke-linecap="round" stroke-linejoin="round" opacity="0.7"/>

          <!-- Dots: avg score -->
          <circle cx="20"  cy="130" r="4" fill="var(--walnut-mid)" stroke="#fff" stroke-width="2"/>
          <circle cx="120" cy="105" r="4" fill="var(--walnut-mid)" stroke="#fff" stroke-width="2"/>
          <circle cx="220" cy="90"  r="4" fill="var(--walnut-mid)" stroke="#fff" stroke-width="2"/>
          <circle cx="320" cy="80"  r="4" fill="var(--walnut-mid)" stroke="#fff" stroke-width="2"/>
          <circle cx="420" cy="65"  r="4" fill="var(--walnut-mid)" stroke="#fff" stroke-width="2"/>
          <circle cx="520" cy="40"  r="4" fill="var(--walnut-mid)" stroke="#fff" stroke-width="2"/>
          <circle cx="580" cy="25"  r="5" fill="var(--walnut-mid)" stroke="#fff" stroke-width="2.5"/>
        </svg>
      </div>
      <div class="chart-months">
        <span>Nov</span><span>Dec</span><span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span>
      </div>
    </div>
  </div>

  {{-- Call Type Analysis --}}
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Call Type Analysis</div>
        <div class="card-subtitle">Incoming vs outgoing comparison</div>
      </div>
    </div>
    <div class="call-type-grid">
      <div class="ct-card incoming">
        <div class="ct-head">
          <div class="ct-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
          </div>
          <div>
            <div class="ct-title">Incoming</div>
            <div class="ct-count">712 calls</div>
          </div>
        </div>
        <div class="ct-metric">
          <span class="ct-metric-label">Avg Score</span>
          <span class="ct-metric-val">85.2%</span>
        </div>
        <div class="ct-metric">
          <span class="ct-metric-label">KO Rate</span>
          <span class="ct-metric-val" style="color:var(--walnut-light)">5.8%</span>
        </div>
        <div class="ct-metric">
          <span class="ct-metric-label">Evals</span>
          <span class="ct-metric-val">712</span>
        </div>
      </div>

      <div class="ct-card outgoing">
        <div class="ct-head">
          <div class="ct-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
          </div>
          <div>
            <div class="ct-title">Outgoing</div>
            <div class="ct-count">572 calls</div>
          </div>
        </div>
        <div class="ct-metric">
          <span class="ct-metric-label">Avg Score</span>
          <span class="ct-metric-val">81.0%</span>
        </div>
        <div class="ct-metric">
          <span class="ct-metric-label">KO Rate</span>
          <span class="ct-metric-val" style="color:var(--walnut-light)">9.2%</span>
        </div>
        <div class="ct-metric">
          <span class="ct-metric-label">Evals</span>
          <span class="ct-metric-val">572</span>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════ --}}
{{-- 4. RANKING TABLE + TOP 5 LEADERBOARD --}}
{{-- ═══════════════════════════════════ --}}
<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:24px; animation: fadeUp 0.4s 0.2s ease both;">

  {{-- Ranking Table --}}
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Conseiller Performance Ranking</div>
        <div class="card-subtitle">Sorted by average score — identify top performers & coaching needs</div>
      </div>
      <a href="#" class="card-action">Full table</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Conseiller</th>
            <th>Avg Score</th>
            <th>Evaluations</th>
            <th>KO Count</th>
            <th>Best Score</th>
          </tr>
        </thead>
        <tbody>
          @php
            $conseillers = [
              ['rank'=>1,'name'=>'Amina Benali','team'=>'Team Alpha','color'=>'#C0152A','initials'=>'AB','score'=>94.2,'evals'=>87,'ko'=>1,'best'=>98.5],
              ['rank'=>2,'name'=>'Karim Tahiri','team'=>'Team Beta','color'=>'#E07B00','initials'=>'KT','score'=>89.7,'evals'=>74,'ko'=>3,'best'=>96.0],
              ['rank'=>3,'name'=>'Sara Elouafi','team'=>'Team Alpha','color'=>'#4a6b42','initials'=>'SE','score'=>86.3,'evals'=>91,'ko'=>5,'best'=>94.5],
              ['rank'=>4,'name'=>'Youssef Chraibi','team'=>'Team Gamma','color'=>'#6B3040','initials'=>'YC','score'=>82.1,'evals'=>63,'ko'=>8,'best'=>91.0],
              ['rank'=>5,'name'=>'Nadia Idrissi','team'=>'Team Beta','color'=>'#7A5C00','initials'=>'NI','score'=>79.4,'evals'=>55,'ko'=>11,'best'=>88.0],
              ['rank'=>6,'name'=>'Omar Benhida','team'=>'Team Gamma','color'=>'#8B0000','initials'=>'OB','score'=>71.8,'evals'=>48,'ko'=>18,'best'=>85.5],
            ];
          @endphp
          @foreach($conseillers as $c)
          <tr>
            <td>
              <span class="rank-num {{ $c['rank']==1?'rank-1':($c['rank']==2?'rank-2':($c['rank']==3?'rank-3':'rank-other')) }}">
                {{ $c['rank'] <= 3 ? ['🥇','🥈','🥉'][$c['rank']-1] : $c['rank'] }}
              </span>
            </td>
            <td>
              <div class="advisor-cell">
                <div class="advisor-av" style="background:{{ $c['color'] }}">{{ $c['initials'] }}</div>
                <div>
                  <div class="advisor-name">{{ $c['name'] }}</div>
                  <div class="advisor-team">{{ $c['team'] }}</div>
                </div>
              </div>
            </td>
            <td>
              <div class="score-cell">
                <span class="score-val" style="color:{{ $c['score']>=85?'#3d6035':($c['score']>=70?'#8B5E00':'var(--walnut-light)') }}">{{ $c['score'] }}%</span>
                <div class="score-bar"><div class="score-bar-fill" style="width:{{ $c['score'] }}%; background:{{ $c['score']>=85?'var(--sage)':($c['score']>=70?'var(--gold)':'var(--walnut-light)') }}"></div></div>
              </div>
            </td>
            <td>{{ $c['evals'] }}</td>
            <td>
              @if($c['ko'] > 10)
                <span class="ko-chip ko-yes">🚨 {{ $c['ko'] }}</span>
              @elseif($c['ko'] > 0)
                <span class="ko-chip" style="background:rgba(245,166,35,0.1);color:#8B5E00;">⚠️ {{ $c['ko'] }}</span>
              @else
                <span class="ko-chip ko-no">✓ 0</span>
              @endif
            </td>
            <td style="font-family:'Syne',sans-serif; font-weight:700;">{{ $c['best'] }}%</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Top 5 Leaderboard --}}
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">🏆 Top 5 Best Conseillers</div>
        <div class="card-subtitle">Based on average score this period</div>
      </div>
    </div>
    <div style="padding:8px 0;">
      @foreach(array_slice($conseillers, 0, 5) as $i => $c)
      <div class="top5-item">
        <span class="lb-rank {{ $i==0?'top1':($i==1?'top2':($i==2?'top3':'other')) }}">
          {{ $i+1 }}
        </span>
        <div class="lb-av" style="background:{{ $c['color'] }}">{{ $c['initials'] }}</div>
        <div class="lb-info">
          <div class="lb-name">{{ $c['name'] }}</div>
          <div class="top5-bar-wrap">
            <div class="top5-bar-fill" style="width:{{ $c['score'] }}%"></div>
          </div>
        </div>
        <div class="lb-score-wrap">
          <div class="lb-score">{{ $c['score'] }}%</div>
          <div class="lb-trend up">↑ trend</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════ --}}
{{-- 6. KO ANALYSIS                      --}}
{{-- ═══════════════════════════════════ --}}
<div class="section-heading" style="animation: fadeUp 0.4s 0.22s ease both;">
  <div class="section-heading-left">
    <h2>🚨 KO Analysis</h2>
    <p>Most frequent failures & most impacted conseillers</p>
  </div>
</div>

<div class="ko-grid" style="animation: fadeUp 0.4s 0.24s ease both;">

  {{-- Most frequent KO criteria --}}
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Most Frequent KO Criteria</div>
        <div class="card-subtitle">Number of KO occurrences per criterion</div>
      </div>
    </div>
    <div style="padding:16px 24px;">
      @php
        $kos = [
          ['label'=>'Identity Verification Missing', 'count'=>24, 'pct'=>100],
          ['label'=>'Compliance Script Not Followed', 'count'=>19, 'pct'=>79],
          ['label'=>'Data Privacy Not Mentioned', 'count'=>16, 'pct'=>67],
          ['label'=>'Call Ended Without Resolution', 'count'=>12, 'pct'=>50],
          ['label'=>'Incorrect Product Info', 'count'=>9, 'pct'=>38],
          ['label'=>'Customer Not Identified', 'count'=>7, 'pct'=>29],
        ];
      @endphp
      @foreach($kos as $ko)
      <div class="ko-bar-item">
        <span class="ko-bar-label">{{ $ko['label'] }}</span>
        <div class="ko-bar-track"><div class="ko-bar-fill" style="width:{{ $ko['pct'] }}%"></div></div>
        <span class="ko-bar-count">{{ $ko['count'] }}</span>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Conseillers most impacted --}}
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Conseillers Most Impacted</div>
        <div class="card-subtitle">Highest KO rate — coaching priority</div>
      </div>
    </div>
    <div style="padding:16px 24px;">
      @php
        $impact = [
          ['name'=>'Omar Benhida',    'initials'=>'OB','color'=>'#8B0000','ko'=>18,'rate'=>37.5],
          ['name'=>'Nadia Idrissi',   'initials'=>'NI','color'=>'#7A5C00','ko'=>11,'rate'=>20.0],
          ['name'=>'Youssef Chraibi', 'initials'=>'YC','color'=>'#6B3040','ko'=>8, 'rate'=>12.7],
          ['name'=>'Sara Elouafi',    'initials'=>'SE','color'=>'#4a6b42','ko'=>5, 'rate'=>5.5],
          ['name'=>'Karim Tahiri',    'initials'=>'KT','color'=>'#E07B00','ko'=>3, 'rate'=>4.1],
        ];
      @endphp
      @foreach($impact as $imp)
      <div class="ko-advisor-item">
        <div class="advisor-av" style="background:{{ $imp['color'] }}; width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:#fff; flex-shrink:0;">{{ $imp['initials'] }}</div>
        <div style="flex:1; min-width:0;">
          <div style="font-weight:600; font-size:13px; color:var(--text-dark);">{{ $imp['name'] }}</div>
          <div style="font-size:11px; color:var(--text-muted);">{{ $imp['ko'] }} KOs · {{ $imp['rate'] }}% rate</div>
        </div>
        <div>
          <span class="ko-chip {{ $imp['rate']>20?'ko-yes':($imp['rate']>8?'':'ko-no') }}" style="{{ $imp['rate']>20?'':'background:rgba(245,166,35,0.1);color:#8B5E00;' }}">
            {{ $imp['rate'] }}%
          </span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════ --}}
{{-- 8. CRITERIA PERFORMANCE ANALYSIS   --}}
{{-- ═══════════════════════════════════ --}}
<div class="section-heading" style="animation: fadeUp 0.4s 0.26s ease both;">
  <div class="section-heading-left">
    <h2>Criteria Performance Analysis</h2>
    <p>Average score and failure rate per evaluation criterion</p>
  </div>
</div>

<div class="card" style="margin-bottom:24px; animation: fadeUp 0.4s 0.28s ease both;">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Criterion</th>
          <th>Average Score</th>
          <th>Performance</th>
          <th>Failure Rate</th>
          <th>KO Criterion</th>
          <th>Evaluations</th>
        </tr>
      </thead>
      <tbody>
        @php
          $criteria = [
            ['name'=>'Opening Greeting',      'score'=>92, 'fail'=>3,  'ko'=>false,'evals'=>1284],
            ['name'=>'Identity Verification', 'score'=>61, 'fail'=>28, 'ko'=>true, 'evals'=>1284],
            ['name'=>'Needs Discovery',       'score'=>78, 'fail'=>14, 'ko'=>false,'evals'=>1284],
            ['name'=>'Solution Proposal',     'score'=>80, 'fail'=>11, 'ko'=>false,'evals'=>1284],
            ['name'=>'Compliance Script',     'score'=>55, 'fail'=>33, 'ko'=>true, 'evals'=>1284],
            ['name'=>'Data Privacy Notice',   'score'=>64, 'fail'=>24, 'ko'=>true, 'evals'=>1284],
            ['name'=>'Empathy & Tone',        'score'=>88, 'fail'=>6,  'ko'=>false,'evals'=>1284],
            ['name'=>'Call Closing',          'score'=>83, 'fail'=>9,  'ko'=>false,'evals'=>1284],
            ['name'=>'Follow-up Offered',     'score'=>70, 'fail'=>18, 'ko'=>false,'evals'=>1284],
          ];
        @endphp
        @foreach($criteria as $cr)
        <tr>
          <td><strong>{{ $cr['name'] }}</strong></td>
          <td>
            <span style="font-family:'Syne',sans-serif; font-weight:700; font-size:15px; color:{{ $cr['score']>=80?'#3d6035':($cr['score']>=65?'#8B5E00':'var(--walnut-light)') }}">
              {{ $cr['score'] }}%
            </span>
          </td>
          <td>
            <div class="criteria-bar-track">
              <div class="criteria-bar-fill" style="width:{{ $cr['score'] }}%; background:{{ $cr['score']>=80?'var(--sage)':($cr['score']>=65?'var(--gold)':'var(--walnut-light)') }}"></div>
            </div>
          </td>
          <td>
            <span class="fail-rate {{ $cr['fail']<=10?'fail-low':($cr['fail']<=20?'fail-mid':'fail-high') }}">
              {{ $cr['fail'] }}%
            </span>
          </td>
          <td>
            @if($cr['ko'])
              <span class="ko-chip ko-yes">KO</span>
            @else
              <span style="color:var(--text-muted); font-size:12px;">—</span>
            @endif
          </td>
          <td class="date-cell">{{ number_format($cr['evals']) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- ═══════════════════════════════════ --}}
{{-- HEATMAP / ACTIVITY VIEW            --}}
{{-- ═══════════════════════════════════ --}}
<div class="section-heading" style="animation: fadeUp 0.4s 0.29s ease both;">
  <div class="section-heading-left">
    <h2>Activity Heatmap</h2>
    <p>Busiest evaluation periods by day &amp; hour</p>
  </div>
</div>

<div class="card" style="margin-bottom:24px; animation: fadeUp 0.4s 0.30s ease both;">
  <div class="card-header">
    <div>
      <div class="card-title">Evaluation Intensity</div>
      <div class="card-subtitle">Darker = more evaluations · hover for details</div>
    </div>
    <div style="display:flex; align-items:center; gap:8px; font-size:11px; color:var(--text-muted);">
      <span>Low</span>
      <div style="display:flex; gap:3px;">
        <div style="width:16px;height:16px;border-radius:4px;background:rgba(139,0,0,0.04)"></div>
        <div style="width:16px;height:16px;border-radius:4px;background:rgba(192,21,42,0.10)"></div>
        <div style="width:16px;height:16px;border-radius:4px;background:rgba(192,21,42,0.22)"></div>
        <div style="width:16px;height:16px;border-radius:4px;background:rgba(192,21,42,0.42)"></div>
        <div style="width:16px;height:16px;border-radius:4px;background:var(--walnut-mid)"></div>
      </div>
      <span>High</span>
    </div>
  </div>
  <div class="heatmap-wrap">
    @php
      $hours = ['8AM','9AM','10AM','11AM','12PM','2PM','3PM','4PM','5PM'];
      $days  = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
      $data  = [
        [1,2,3,4,3,1,0],
        [2,4,5,5,4,2,0],
        [3,5,5,5,5,2,0],
        [2,4,4,5,4,1,0],
        [1,3,4,4,3,1,0],
        [0,1,2,3,2,0,0],
        [0,1,1,2,1,0,0],
        [1,2,3,4,3,1,0],
        [0,1,2,2,1,0,0],
      ];
    @endphp
    <div class="heatmap-grid">
      {{-- Header row --}}
      <div></div>
      @foreach($days as $d)
        <div class="hm-day-label">{{ $d }}</div>
      @endforeach

      {{-- Data rows --}}
      @foreach($hours as $hi => $h)
        <div class="hm-label">{{ $h }}</div>
        @foreach($days as $di => $d)
          @php $v = $data[$hi][$di]; @endphp
          <div class="hm-cell hm-{{ $v }}" title="{{ $d }} {{ $h }}: {{ [0=>0,1=>4,2=>9,3=>16,4=>24,5=>32][$v] ?? 0 }} evals">
            {{ $v > 0 ? [1=>4,2=>9,3=>16,4=>24,5=>32][$v] ?? '' : '' }}
          </div>
        @endforeach
      @endforeach
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════ --}}
{{-- 7. DETAILED EVALUATIONS TABLE       --}}
{{-- ═══════════════════════════════════ --}}
<div class="section-heading" style="animation: fadeUp 0.4s 0.32s ease both;">
  <div class="section-heading-left">
    <h2>Detailed Evaluations Report</h2>
    <p>Full table — all evaluations with actions</p>
  </div>
</div>

<div class="card" style="margin-bottom:24px; animation: fadeUp 0.4s 0.34s ease both;">
  <div class="detail-table-wrap">
    <table class="detail-table">
      <thead>
        <tr>
          <th>Eval ID</th>
          <th>Conseiller</th>
          <th>Manager</th>
          <th>Date</th>
          <th>Type</th>
          <th>Score</th>
          <th>KO</th>
          <th>Status</th>
          <th>Audio</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @php
          $evals = [
            ['id'=>'EVL-1084','conseiller'=>'Amina Benali','initials'=>'AB','color'=>'#C0152A','manager'=>'H. Alami','date'=>'May 7, 2026','type'=>'Incoming','score'=>94.2,'ko'=>false,'status'=>'Completed','audio'=>true],
            ['id'=>'EVL-1083','conseiller'=>'Karim Tahiri','initials'=>'KT','color'=>'#E07B00','manager'=>'F. Zohra','date'=>'May 7, 2026','type'=>'Outgoing','score'=>81.5,'ko'=>false,'status'=>'Signed','audio'=>true],
            ['id'=>'EVL-1082','conseiller'=>'Omar Benhida','initials'=>'OB','color'=>'#8B0000','manager'=>'H. Alami','date'=>'May 6, 2026','type'=>'Outgoing','score'=>52.0,'ko'=>true,'status'=>'Pending','audio'=>false],
            ['id'=>'EVL-1081','conseiller'=>'Sara Elouafi','initials'=>'SE','color'=>'#4a6b42','manager'=>'F. Zohra','date'=>'May 6, 2026','type'=>'Incoming','score'=>88.3,'ko'=>false,'status'=>'Completed','audio'=>true],
            ['id'=>'EVL-1080','conseiller'=>'Nadia Idrissi','initials'=>'NI','color'=>'#7A5C00','manager'=>'H. Alami','date'=>'May 5, 2026','type'=>'Incoming','score'=>67.0,'ko'=>true,'status'=>'Pending','audio'=>true],
            ['id'=>'EVL-1079','conseiller'=>'Youssef Chraibi','initials'=>'YC','color'=>'#6B3040','manager'=>'F. Zohra','date'=>'May 5, 2026','type'=>'Outgoing','score'=>83.9,'ko'=>false,'status'=>'Signed','audio'=>false],
            ['id'=>'EVL-1078','conseiller'=>'Amina Benali','initials'=>'AB','color'=>'#C0152A','manager'=>'H. Alami','date'=>'May 4, 2026','type'=>'Incoming','score'=>97.1,'ko'=>false,'status'=>'Signed','audio'=>true],
          ];
        @endphp
        @foreach($evals as $ev)
        <tr>
          <td><span class="eval-id">{{ $ev['id'] }}</span></td>
          <td>
            <div class="advisor-cell">
              <div class="advisor-av" style="background:{{ $ev['color'] }}; width:28px; height:28px; border-radius:7px; font-size:10px;">{{ $ev['initials'] }}</div>
              <span style="font-weight:600; font-size:13px;">{{ $ev['conseiller'] }}</span>
            </div>
          </td>
          <td class="date-cell">{{ $ev['manager'] }}</td>
          <td class="date-cell">{{ $ev['date'] }}</td>
          <td>
            <span style="font-size:12px; font-weight:600; padding:3px 8px; border-radius:5px; background:{{ $ev['type']=='Incoming'?'rgba(122,140,114,0.1)':'rgba(245,166,35,0.1)' }}; color:{{ $ev['type']=='Incoming'?'#3d6035':'#8B5E00' }}">
              {{ $ev['type'] }}
            </span>
          </td>
          <td>
            <span class="score-val" style="font-family:'Syne',sans-serif; font-weight:800; font-size:14px; color:{{ $ev['score']>=85?'#3d6035':($ev['score']>=70?'#8B5E00':'var(--walnut-light)') }}">
              {{ $ev['score'] }}%
            </span>
          </td>
          <td>
            <span class="ko-chip {{ $ev['ko']?'ko-yes':'ko-no' }}">{{ $ev['ko']?'KO':'✓' }}</span>
          </td>
          <td>
            @php
              $sc = match($ev['status']) {
                'Completed' => 'status-completed',
                'Signed'    => 'status-completed',
                'Pending'   => 'status-pending',
                default     => 'status-draft',
              };
            @endphp
            <span class="status-badge {{ $sc }}">{{ $ev['status'] }}</span>
          </td>
          <td style="text-align:center;">
            @if($ev['audio'])
              <svg class="has-audio" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" title="Audio available">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m-3.536-9.536a5 5 0 000 7.072"/>
              </svg>
            @else
              <svg class="no-audio" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" title="No audio">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
              </svg>
            @endif
          </td>
          <td>
            <div class="tbl-actions">
              <button class="tbl-btn">View</button>
              <button class="tbl-btn">PDF</button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <div class="pag-info">Showing 1–7 of 1,284 evaluations</div>
    <div class="pag-btns">
      <button class="pag-btn">‹</button>
      <button class="pag-btn active">1</button>
      <button class="pag-btn">2</button>
      <button class="pag-btn">3</button>
      <span style="padding:0 4px; color:var(--text-muted); font-size:13px;">…</span>
      <button class="pag-btn">184</button>
      <button class="pag-btn">›</button>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════ --}}
{{-- 9. EXPORT SECTION                   --}}
{{-- ═══════════════════════════════════ --}}
<div class="export-card" style="animation: fadeUp 0.4s 0.36s ease both;">
  <div>
    <div class="export-title">📥 Export Reports</div>
    <div class="export-desc">Download filtered data in your preferred format — all active filters applied</div>
  </div>
  <div class="export-btns">
    <button class="export-btn export-excel">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      Excel
    </button>
    <button class="export-btn export-csv">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
      </svg>
      CSV
    </button>
    <button class="export-btn export-pdf">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
      </svg>
      PDF Report
    </button>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // ── Date tab toggle ──
  function setDateTab(el, val) {
    document.querySelectorAll('.date-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    const customRange = document.getElementById('customRange');
    customRange.style.display = val === 'custom' ? 'block' : 'none';
  }

  // ── Chart period tab ──
  function setChartTab(el) {
    document.querySelectorAll('.cht').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
  }

  // ── Reset filters ──
  function resetFilters() {
    document.querySelectorAll('.filter-select').forEach(s => s.selectedIndex = 0);
    document.querySelectorAll('.filter-input').forEach(i => i.value = '');
    document.querySelectorAll('.date-tab').forEach((t, idx) => {
      t.classList.toggle('active', idx === 1);
    });
    document.getElementById('customRange').style.display = 'none';
  }

  // ── Chart tooltip ──
  const tip = document.getElementById('chartTip');
  const cWrap = document.getElementById('chartWrap');

  const pts = [
    { cx: 20,  cy: 130, label: 'Nov · Avg: 68%' },
    { cx: 120, cy: 105, label: 'Dec · Avg: 74%' },
    { cx: 220, cy: 90,  label: 'Jan · Avg: 78%' },
    { cx: 320, cy: 80,  label: 'Feb · Avg: 80%' },
    { cx: 420, cy: 65,  label: 'Mar · Avg: 84%' },
    { cx: 520, cy: 40,  label: 'Apr · Avg: 90%' },
    { cx: 580, cy: 25,  label: 'May · Avg: 94%' },
  ];

  document.querySelectorAll('#chartSvg circle').forEach((c, i) => {
    if (!pts[i]) return;
    c.style.cursor = 'pointer';
    c.addEventListener('mouseenter', function(e) {
      const rect  = cWrap.getBoundingClientRect();
      const svgR  = document.getElementById('chartSvg').getBoundingClientRect();
      const x = svgR.left - rect.left + (pts[i].cx / 600) * svgR.width;
      const y = svgR.top  - rect.top  + (pts[i].cy / 200) * svgR.height;
      tip.textContent = pts[i].label;
      tip.style.left  = (x + 12) + 'px';
      tip.style.top   = (y - 18) + 'px';
      tip.classList.add('show');
    });
    c.addEventListener('mouseleave', () => tip.classList.remove('show'));
  });

  // ── Animate fills on load ──
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.querySelectorAll('.bar-fill, .score-bar-fill, .top5-bar-fill, .ko-bar-fill, .criteria-bar-fill').forEach(b => {
        const w = b.style.width;
        b.style.width = '0';
        setTimeout(() => { b.style.width = w; }, 150);
      });
    }, 500);
  });
</script>
@endpush