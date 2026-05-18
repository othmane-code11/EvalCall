<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'EvalCall — Performance Dashboard')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
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

  /* ─── Compact icon-button group for table actions ─── */
  .icon-actions {
    display: inline-flex;
    gap: 6px;
  }
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
  :root {
    --walnut:       #8B0000;
    --walnut-mid:   #C0152A;
    --walnut-light: #D93848;
    --bg:           #FAFAFA;
    --white:        #FFFFFF;
    --cream:        #FFF5F5;
    --cream-deep:   #FFE8EA;
    --gold:         #F5A623;
    --gold-light:   #F7BC54;
    --sage:         #7A8C72;
    --text-dark:    #1A0A0C;
    --text-mid:     #6B3040;
    --text-muted:   #9C7078;
    --sidebar-w:    260px;
    --topbar-h:     68px;
    --radius:       14px;
    --radius-sm:    8px;
    --shadow-card:  0 2px 16px rgba(139,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-hover: 0 8px 32px rgba(123, 74, 74, 0.14), 0 2px 8px rgba(0,0,0,0.06);
    --transition:   all 0.25s cubic-bezier(0.4,0,0.2,1);
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text-dark);
    min-height: 100vh;
    overflow-x: hidden;
  }

  /* ═══════════════════════════════════════
     SIDEBAR
  ═══════════════════════════════════════ */
  .logout-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  margin-top: 6px;
  border-radius: 10px;
  color: rgba(255,255,255,0.4);
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
  transition: var(--transition);
  cursor: pointer;
}

.logout-btn:hover {
  background: rgba(192,21,42,0.25);
  color: rgba(255,255,255,0.85);
}

.logout-btn svg {
  opacity: 0.6;
  flex-shrink: 0;
  transition: opacity 0.2s;
}

.logout-btn:hover svg {
  opacity: 1;
}
  
  
  .sidebar {
    position: fixed;
    top: 0; left: 0; bottom: 0;
    width: var(--sidebar-w);
    background: linear-gradient(160deg, #1A0204 0%, #2D0008 40%, #3D0010 70%, #280008 100%);
    display: flex;
    flex-direction: column;
    z-index: 100;
    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
    overflow: hidden;
  }

  /* subtle noise texture overlay */
  .sidebar::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
    pointer-events: none;
    opacity: 0.6;
  }

  /* vertical accent line */
  .sidebar::after {
    content: '';
    position: absolute;
    top: 0; right: 0; bottom: 0;
    width: 1px;
    background: linear-gradient(to bottom, transparent, rgba(245,166,35,0.3) 30%, rgba(245,166,35,0.15) 70%, transparent);
  }

  .sidebar-logo {
    padding: 28px 24px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
  }

  .logo-icon {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--gold));
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 16px;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 16px rgba(245,166,35,0.3);
    position: relative;
    overflow: hidden;
  }
  .logo-icon::after {
    content: '';
    position: absolute;
    top: -10px; left: -10px;
    width: 28px; height: 28px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
  }

  .logo-text {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 20px;
    color: #fff;
    letter-spacing: -0.3px;
  }
  .logo-text span { 
    color:red; }

  .sidebar-divider {
    height: 1px;
    margin: 4px 20px 16px;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.08), transparent);
  }

  .nav-section-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
    padding: 0 24px 8px;
    position: relative;
  }

  .nav-list { list-style: none; padding: 0 12px; flex: 1; }

  .nav-item {
    margin-bottom: 2px;
    position: relative;
  }

  .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 10px;
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
    cursor: pointer;
  }

  .nav-link:hover {
    background: rgba(255,255,255,0.06);
    color: rgba(255,255,255,0.9);
  }

  .nav-link.active {
    background: linear-gradient(135deg, rgba(192,21,42,0.35) 0%, rgba(245,166,35,0.12) 100%);
    color: #fff;
    box-shadow: 0 0 0 1px rgba(245,166,35,0.2) inset;
  }

  .nav-link.active::before {
    content: '';
    position: absolute;
    left: -12px; top: 50%;
    transform: translateY(-50%);
    width: 3px; height: 22px;
    background: linear-gradient(to bottom, var(--gold), var(--gold-light));
    border-radius: 0 3px 3px 0;
    box-shadow: 0 0 12px rgba(245,166,35,0.6);
  }

  .nav-icon {
    width: 20px; height: 20px;
    opacity: 0.7;
    flex-shrink: 0;
    transition: opacity 0.2s;
  }
  .nav-link.active .nav-icon,
  .nav-link:hover .nav-icon { opacity: 1; }

  .nav-badge {
    margin-left: auto;
    background: var(--walnut-mid);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
  }

  .sidebar-footer {
    padding: 16px 12px 24px;
    position: relative;
  }

  .sidebar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 12px;
    border-radius: 10px;
    background: rgba(255,255,255,0.05);
    cursor: pointer;
    transition: var(--transition);
  }
  .sidebar-user:hover { background: rgba(255,255,255,0.09); }

  .sidebar-avatar {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: #fff;
    flex-shrink: 0;
  }

  .sidebar-user-info { flex: 1; min-width: 0; }
  .sidebar-user-name {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .sidebar-user-role {
    font-size: 11px;
    color: rgba(255,255,255,0.4);
  }

  /* ═══════════════════════════════════════
     TOPBAR
  ═══════════════════════════════════════ */
  .topbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-w);
    right: 0;
    height: var(--topbar-h);
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(139,0,0,0.07);
    display: flex;
    align-items: center;
    padding: 0 32px;
    gap: 16px;
    z-index: 90;
    transition: left 0.35s cubic-bezier(0.4,0,0.2,1);
  }

  .hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    transition: background 0.2s;
  }
  .hamburger:hover { background: var(--cream-deep); }
  .hamburger span {
    display: block;
    width: 20px; height: 2px;
    background: var(--walnut);
    border-radius: 2px;
    transition: var(--transition);
  }

  .topbar-title {
    flex: 1;
  }
  .topbar-title h1 {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.3px;
  }
  .topbar-title p {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 1px;
  }

  .topbar-actions {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .topbar-btn {
    position: relative;
    width: 38px; height: 38px;
    border-radius: 10px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.08);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    color: var(--text-mid);
  }
  .topbar-btn:hover {
    background: var(--cream-deep);
    border-color: rgba(139,0,0,0.15);
    transform: translateY(-1px);
  }
  .topbar-btn svg { width: 18px; height: 18px; }

  .notif-dot {
    position: absolute;
    top: 7px; right: 7px;
    width: 7px; height: 7px;
    background: var(--gold);
    border-radius: 50%;
    border: 1.5px solid var(--white);
    box-shadow: 0 0 6px rgba(245,166,35,0.6);
  }

  .topbar-divider { width: 1px; height: 28px; background: rgba(139,0,0,0.1); margin: 0 4px; }

  .topbar-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 10px 6px 6px;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--transition);
    border: 1px solid transparent;
  }
  .topbar-profile:hover {
    background: var(--cream);
    border-color: rgba(139,0,0,0.1);
  }

  .topbar-avatar {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: #fff;
    box-shadow: 0 2px 8px rgba(192,21,42,0.3);
  }

  .topbar-profile-info { display: flex; flex-direction: column; }
  .topbar-profile-name { font-size: 13px; font-weight: 600; color: var(--text-dark); }

  .role-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    padding: 2px 7px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--cream-deep), #ffd6da);
    color: var(--walnut-mid);
    border: 1px solid rgba(192,21,42,0.15);
  }

  /* ═══════════════════════════════════════
     MAIN CONTENT
  ═══════════════════════════════════════ */
  .main {
    margin-left: var(--sidebar-w);
    padding-top: var(--topbar-h);
    min-height: 100vh;
    transition: margin-left 0.35s cubic-bezier(0.4,0,0.2,1);
  }

  .content {
    padding: 32px;
    max-width: 1400px;
  }

  /* ═══════════════════════════════════════
     KPI CARDS
  ═══════════════════════════════════════ */
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
  }

  .kpi-card {
    background: var(--white);
    border-radius: var(--radius);
    padding: 22px 24px;
    box-shadow: var(--shadow-card);
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: var(--transition);
    border: 1px solid rgba(139,0,0,0.05);
  }

  .kpi-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--radius) var(--radius) 0 0;
  }

  .kpi-card:nth-child(1)::before { background: linear-gradient(90deg, var(--walnut), var(--walnut-mid)); }
  .kpi-card:nth-child(2)::before { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
  .kpi-card:nth-child(3)::before { background: linear-gradient(90deg, var(--walnut-light), var(--gold)); }
  .kpi-card:nth-child(4)::before { background: linear-gradient(90deg, var(--sage), #9cb394); }

  .kpi-card::after {
    content: '';
    position: absolute;
    bottom: -30px; right: -20px;
    width: 90px; height: 90px;
    border-radius: 50%;
    opacity: 0.04;
    transition: var(--transition);
  }
  .kpi-card:nth-child(1)::after { background: var(--walnut); }
  .kpi-card:nth-child(2)::after { background: var(--gold); }
  .kpi-card:nth-child(3)::after { background: var(--walnut-light); }
  .kpi-card:nth-child(4)::after { background: var(--sage); }

  .kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
  }
  .kpi-card:hover::after { opacity: 0.07; width: 110px; height: 110px; }

  .kpi-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .kpi-label {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: var(--text-muted);
  }

  .kpi-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .kpi-icon svg { width: 18px; height: 18px; }

  .kpi-card:nth-child(1) .kpi-icon { background: rgba(139,0,0,0.08); color: var(--walnut); }
  .kpi-card:nth-child(2) .kpi-icon { background: rgba(245,166,35,0.12); color: #C07A00; }
  .kpi-card:nth-child(3) .kpi-icon { background: rgba(217,56,72,0.09); color: var(--walnut-light); }
  .kpi-card:nth-child(4) .kpi-icon { background: rgba(122,140,114,0.12); color: var(--sage); }

  .kpi-value {
    font-family: 'Syne', sans-serif;
    font-size: 34px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -1px;
    line-height: 1;
    margin-bottom: 8px;
  }

  .kpi-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
  }

  .kpi-delta {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
  }
  .kpi-delta.up { background: rgba(122,140,114,0.12); color: #4a6b42; }
  .kpi-delta.down { background: rgba(217,56,72,0.09); color: var(--walnut-light); }

  /* ═══════════════════════════════════════
     GRID: CHART + TABLE
  ═══════════════════════════════════════ */
  .analytics-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 20px;
    margin-bottom: 28px;
  }

  .card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    overflow: hidden;
  }

  .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px 16px;
    border-bottom: 1px solid rgba(139,0,0,0.05);
  }

  .card-title {
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.2px;
  }
  .card-subtitle {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 2px;
  }

  .card-action {
    font-size: 12px;
    font-weight: 600;
    color: var(--walnut-mid);
    cursor: pointer;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid rgba(192,21,42,0.15);
    background: var(--cream);
    transition: var(--transition);
    text-decoration: none;
  }
  .card-action:hover {
    background: var(--cream-deep);
    border-color: rgba(192,21,42,0.3);
  }

  /* ─── CHART ─── */
  .chart-wrap { padding: 20px 24px; }

  .chart-legend {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-muted);
  }
  .legend-dot {
    width: 10px; height: 10px;
    border-radius: 3px;
  }

  .chart-svg-wrap {
    position: relative;
    width: 100%;
    height: 220px;
  }

  .chart-svg-wrap svg {
    width: 100%;
    height: 100%;
    overflow: visible;
  }

  /* tooltip */
  .chart-tooltip {
    position: absolute;
    background: var(--text-dark);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.15s;
    white-space: nowrap;
    z-index: 10;
  }
  .chart-tooltip.show { opacity: 1; }

  /* month labels */
  .chart-months {
    display: flex;
    justify-content: space-between;
    padding: 8px 0 0;
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 500;
  }

  /* ─── MINI STATS CARD ─── */
  .mini-stats { padding: 20px 24px; display: flex; flex-direction: column; gap: 12px; }

  .mini-stat {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 10px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.05);
    transition: var(--transition);
    cursor: pointer;
  }
  .mini-stat:hover {
    background: var(--cream-deep);
    transform: translateX(4px);
  }

  .mini-stat-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 17px;
  }

  .mini-stat-info { flex: 1; }
  .mini-stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
  .mini-stat-value { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 700; color: var(--text-dark); letter-spacing: -0.5px; }

  .mini-stat-bar {
    width: 48px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
  }

  .bar-track {
    width: 48px; height: 6px;
    background: rgba(139,0,0,0.08);
    border-radius: 3px;
    overflow: hidden;
  }
  .bar-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 1s cubic-bezier(0.4,0,0.2,1);
  }
  .bar-pct { font-size: 10px; font-weight: 700; color: var(--text-muted); }

  /* ─── TABLE ─── */
  .table-wrap { overflow-x: auto; }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
  }

  thead tr {
    background: var(--cream);
  }

  th {
    padding: 11px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--text-muted);
    white-space: nowrap;
    border-bottom: 1px solid rgba(139,0,0,0.06);
  }
  th:first-child { border-radius: var(--radius-sm) 0 0 0; }
  th:last-child  { border-radius: 0 var(--radius-sm) 0 0; }

  td {
    padding: 13px 20px;
    color: var(--text-dark);
    border-bottom: 1px solid rgba(139,0,0,0.04);
    vertical-align: middle;
    white-space: nowrap;
  }

  tbody tr {
    transition: background 0.15s;
  }
  tbody tr:hover { background: rgba(255,245,245,0.8); }
  tbody tr:last-child td { border-bottom: none; }

  .advisor-cell {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .advisor-av {
    width: 32px; height: 32px;
    border-radius: 8px;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 12px;
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .advisor-name { font-weight: 600; font-size: 13.5px; }
  .advisor-team { font-size: 11px; color: var(--text-muted); }

  .score-cell {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .score-val {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 15px;
  }

  .score-bar {
    width: 56px; height: 5px;
    background: rgba(139,0,0,0.08);
    border-radius: 3px;
    overflow: hidden;
  }
  .score-bar-fill {
    height: 100%;
    border-radius: 3px;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.3px;
    white-space: nowrap;
  }
  .status-badge::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
  }

  .status-completed {
    background: rgba(122,140,114,0.12);
    color: #3d6035;
    border: 1px solid rgba(122,140,114,0.2);
  }
  .status-completed::before { background: var(--sage); }

  .status-pending {
    background: rgba(245,166,35,0.1);
    color: #8B5E00;
    border: 1px solid rgba(245,166,35,0.2);
  }
  .status-pending::before { background: var(--gold); box-shadow: 0 0 4px rgba(245,166,35,0.5); }

  .status-critical {
    background: rgba(139,0,0,0.08);
    color: var(--walnut);
    border: 1px solid rgba(139,0,0,0.15);
  }
  .status-critical::before { background: var(--walnut); }

  .status-draft {
    background: rgba(107,48,64,0.07);
    color: var(--text-mid);
    border: 1px solid rgba(107,48,64,0.12);
  }
  .status-draft::before { background: var(--text-muted); }

  .date-cell { color: var(--text-muted); font-size: 12.5px; }

  .action-btn {
    padding: 5px 12px;
    border-radius: 7px;
    border: 1px solid rgba(139,0,0,0.15);
    background: var(--cream);
    color: var(--walnut-mid);
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
  }
  .action-btn:hover {
    background: var(--walnut-mid);
    color: #fff;
    border-color: var(--walnut-mid);
    transform: translateY(-1px);
  }

  /* ═══════════════════════════════════════
     BOTTOM SECTION: ACTIVITY + LEADERBOARD
  ═══════════════════════════════════════ */
  .bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 32px;
  }

  /* Activity Feed */
  .activity-list { padding: 8px 0; }

  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 24px;
    transition: background 0.15s;
    cursor: pointer;
  }
  .activity-item:hover { background: var(--cream); }
  .activity-item + .activity-item { border-top: 1px solid rgba(139,0,0,0.04); }

  .activity-dot-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    flex-shrink: 0;
    padding-top: 4px;
  }

  .activity-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    border: 2px solid;
    flex-shrink: 0;
  }

  .activity-body { flex: 1; min-width: 0; }
  .activity-text { font-size: 13px; color: var(--text-dark); line-height: 1.4; }
  .activity-text strong { font-weight: 600; }
  .activity-time { font-size: 11px; color: var(--text-muted); margin-top: 3px; }

  /* Leaderboard */
  .leaderboard-list { padding: 16px 24px; display: flex; flex-direction: column; gap: 10px; }

  .lb-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    transition: var(--transition);
    cursor: pointer;
  }
  .lb-item:hover { background: var(--cream); }

  .lb-rank {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 16px;
    width: 24px;
    text-align: center;
    flex-shrink: 0;
  }
  .lb-rank.top1 { color: var(--gold); }
  .lb-rank.top2 { color: #9E9E9E; }
  .lb-rank.top3 { color: #C07B4A; }
  .lb-rank.other { color: var(--text-muted); font-size: 13px; }

  .lb-av {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: #fff;
    flex-shrink: 0;
  }

  .lb-info { flex: 1; min-width: 0; }
  .lb-name { font-weight: 600; font-size: 13.5px; color: var(--text-dark); }
  .lb-evals { font-size: 11px; color: var(--text-muted); }

  .lb-score-wrap { text-align: right; }
  .lb-score {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 18px;
    color: var(--text-dark);
    letter-spacing: -0.5px;
  }
  .lb-trend { font-size: 10px; font-weight: 700; }
  .lb-trend.up { color: #4a6b42; }
  .lb-trend.down { color: var(--walnut-light); }

  /* ═══════════════════════════════════════
     SIDEBAR OVERLAY (mobile)
  ═══════════════════════════════════════ */
  .sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(10,2,4,0.55);
    z-index: 99;
    backdrop-filter: blur(2px);
  }

  /* ═══════════════════════════════════════
     RESPONSIVE
  ═══════════════════════════════════════ */
  @media (max-width: 1200px) {
    .analytics-grid { grid-template-columns: 1fr; }
  }

  @media (max-width: 960px) {
    :root { --sidebar-w: 260px; }
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .sidebar-overlay.open { display: block; }
    .topbar { left: 0; }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 600px) {
    .content { padding: 20px 16px; }
    .kpi-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .bottom-grid { grid-template-columns: 1fr; }
    .topbar { padding: 0 16px; }
    .topbar-profile-info { display: none; }
    .kpi-value { font-size: 26px; }
    .analytics-grid { gap: 16px; }
  }

  @media (max-width: 420px) {
    .kpi-grid { grid-template-columns: 1fr; }
  }

  /* ═══════════════════════════════════════
     ANIMATIONS
  ═══════════════════════════════════════ */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
  }
  @keyframes countUp {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .kpi-card   { animation: fadeUp 0.5s ease both; }
  .kpi-card:nth-child(1) { animation-delay: 0.05s; }
  .kpi-card:nth-child(2) { animation-delay: 0.10s; }
  .kpi-card:nth-child(3) { animation-delay: 0.15s; }
  .kpi-card:nth-child(4) { animation-delay: 0.20s; }

  .analytics-grid .card:first-child { animation: fadeUp 0.5s 0.25s ease both; }
  .analytics-grid .card:last-child  { animation: fadeUp 0.5s 0.30s ease both; }
  .card { animation: fadeUp 0.5s 0.35s ease both; }

  /* chart line draw animation */
  .chart-line { stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: drawLine 1.4s 0.6s cubic-bezier(0.4,0,0.2,1) forwards; }
  .chart-line2{ stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: drawLine 1.4s 0.8s cubic-bezier(0.4,0,0.2,1) forwards; }
  .chart-line3{ stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: drawLine 1.4s 1.0s cubic-bezier(0.4,0,0.2,1) forwards; }
  @keyframes drawLine {
    to { stroke-dashoffset: 0; }
  }

  /* pulse for gold dot */
  @keyframes pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(245,166,35,0.4); }
    50% { box-shadow: 0 0 0 6px rgba(245,166,35,0); }
  }
  .notif-dot { animation: pulse 2s infinite; }
</style>
</head>
<body>

<!-- SIDEBAR OVERLAY (mobile) -->
<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
   
    <span class="logo-text">KiteaCall</span>
  </div>

  <div class="sidebar-divider"></div>
  <div class="nav-section-label">Main Menu</div>

  <ul class="nav-list">
    <li class="nav-item">
      @if(in_array(auth()->user()->role, ['admin', 'manager']))
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
      @else
        <a class="nav-link {{ request()->routeIs('conseiller.dashboard') ? 'active' : '' }}" href="{{ route('conseiller.dashboard') }}">
      @endif
        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
      </a>
    </li>
    @if(in_array(auth()->user()->role, ['admin', 'manager']))
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('evaluations') ? 'active' : '' }}" href="{{ route('evaluations') }}">
        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        Evaluations
        <span class="nav-badge">{{ $evaluationCount ?? 0 }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}" href="{{ route('users') }}">
        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Users
      </a>
    </li>
     <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}" href="/reports">
        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Reports
      </a>
    </li>
    @endif
    <li class="nav-item" style="margin-top:8px">
      <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">
        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Settings
      </a>
    </li>
   
  </ul>
  <div class="sidebar-footer">
  <div class="sidebar-user">
    <div class="sidebar-avatar">{{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}</div>
    <div class="sidebar-user-info">
      <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
      <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
    </div>
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       title="Logout"
       style="display:flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:7px; color:rgba(255,255,255,0.3); text-decoration:none; transition:var(--transition); flex-shrink:0;"
       onmouseenter="this.style.background='rgba(192,21,42,0.3)'; this.style.color='rgba(255,255,255,0.85)';"
       onmouseleave="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.3)';">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
      </svg>
    </a>
  </div>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
  </form>
</div>

</aside>

<!-- TOPBAR -->
<header class="topbar" id="topbar">
  <div class="hamburger" onclick="toggleSidebar()" id="hamburger">
    <span></span><span></span><span></span>
  </div>
  <div class="topbar-title">
    <h1>@yield('topbar_title', 'Performance Dashboard')</h1>
    <p>@yield('topbar_subtitle', 'Wednesday, 6 May 2026 · Week 19')</p>
  </div>
  <div class="topbar-actions">
    <button class="topbar-btn" title="Search">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
    </button>
    <button class="topbar-btn" title="Notifications">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      <span class="notif-dot"></span>
    </button>
    <div class="topbar-divider"></div>
    <div class="topbar-profile">
      <div class="topbar-avatar">{{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}</div>
      <div class="topbar-profile-info">
        <span class="topbar-profile-name">{{ auth()->user()->name }}</span>
        <span class="role-badge">{{ auth()->user()->role }}</span>
      </div>
    </div>
  </div>
</header>

<!-- MAIN -->
<main class="main" id="main">
<div class="content">
    @yield('content')
</div>
</main>

<script>
  // ── Mobile sidebar toggle ──
  function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.toggle('open');
    o.classList.toggle('open');
  }

  // ── Nav active state is handled server-side by Laravel route checking ──

  // ── Animated counter ──
  function animateCount(el, target, suffix = '', decimals = 0) {
    let start = 0;
    const duration = 1200;
    const step = 16;
    const increment = target / (duration / step);
    const timer = setInterval(() => {
      start = Math.min(start + increment, target);
      el.textContent = decimals
        ? start.toFixed(decimals) + suffix
        : Math.floor(start).toLocaleString() + suffix;
      if (start >= target) clearInterval(timer);
    }, step);
  }

  // Trigger on load
  window.addEventListener('load', () => {
    // bar fills animate via CSS — add 'loaded' class after short delay
    setTimeout(() => {
      document.querySelectorAll('.bar-fill, .score-bar-fill').forEach(b => {
        const w = b.style.width;
        b.style.width = '0';
        setTimeout(() => { b.style.width = w; }, 100);
      });
    }, 400);
  });

  // ── Chart interactive dots ──
  const tooltip = document.getElementById('chartTip');
  const chartWrap = document.getElementById('chartWrap');

  const dotData = [
    { cx: 0,   cy: 60,  label: 'Dec · Alpha: 76%' },
    { cx: 120, cy: 40,  label: 'Feb · Alpha: 82%' },
    { cx: 200, cy: 30,  label: 'Mar · Alpha: 86%' },
    { cx: 320, cy: 35,  label: 'Apr · Alpha: 84%' },
    { cx: 440, cy: 20,  label: 'Apr · Alpha: 90%' },
    { cx: 600, cy: 10,  label: 'May · Alpha: 95%' },
  ];

  document.querySelectorAll('#chartSvg circle').forEach((circle, i) => {
    if (!dotData[i]) return;
    circle.style.cursor = 'pointer';
    circle.addEventListener('mouseenter', function(e) {
      const rect = chartWrap.getBoundingClientRect();
      const svgRect = document.getElementById('chartSvg').getBoundingClientRect();
      const x = svgRect.left - rect.left + (dotData[i].cx / 600) * svgRect.width;
      const y = svgRect.top  - rect.top  + (dotData[i].cy / 200) * svgRect.height;
      tooltip.textContent = dotData[i].label;
      tooltip.style.left = (x + 10) + 'px';
      tooltip.style.top  = (y - 16) + 'px';
      tooltip.classList.add('show');
    });
    circle.addEventListener('mouseleave', () => tooltip.classList.remove('show'));
  });
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
@stack('scripts')
</body>
</html>