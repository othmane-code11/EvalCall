<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings — KiteaCall Performance Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
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
     SIDEBAR (identical to layout)
  ═══════════════════════════════════════ */
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
  .sidebar::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
    pointer-events: none;
    opacity: 0.6;
  }
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
  }
  .logo-text {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 20px;
    color: #fff;
    letter-spacing: -0.3px;
  }
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
  }
  .nav-list { list-style: none; padding: 0 12px; flex: 1; }
  .nav-item { margin-bottom: 2px; position: relative; }
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
  .nav-icon { width: 20px; height: 20px; opacity: 0.7; flex-shrink: 0; }
  .nav-badge {
    margin-left: auto;
    background: var(--walnut-mid);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
  }
  .sidebar-footer { padding: 16px 12px 24px; }
  .sidebar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 12px;
    border-radius: 10px;
    background: rgba(255,255,255,0.05);
    cursor: pointer;
  }
  .sidebar-avatar {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: #fff;
  }
  .sidebar-user-info { flex: 1; }
  .sidebar-user-name { font-size: 13px; font-weight: 600; color: #fff; }
  .sidebar-user-role { font-size: 11px; color: rgba(255,255,255,0.4); }

  /* TOPBAR */
  .topbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-w);
    right: 0;
    height: var(--topbar-h);
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(20px);
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
  }
  .hamburger span { display: block; width: 20px; height: 2px; background: var(--walnut); transition: var(--transition); }
  .topbar-title h1 { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; color: var(--text-dark); }
  .topbar-title p { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
  .topbar-actions { display: flex; align-items: center; gap: 8px; }
  .topbar-btn {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.08);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
  }
  .notif-dot {
    position: absolute;
    top: 7px; right: 7px;
    width: 7px; height: 7px;
    background: var(--gold);
    border-radius: 50%;
  }
  .topbar-divider { width: 1px; height: 28px; background: rgba(139,0,0,0.1); margin: 0 4px; }
  .topbar-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 10px 6px 6px;
    border-radius: 12px;
    cursor: pointer;
  }
  .topbar-avatar {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    color: #fff;
  }
  .role-badge {
    font-size: 10px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--cream-deep), #ffd6da);
    color: var(--walnut-mid);
  }
  .main {
    margin-left: var(--sidebar-w);
    padding-top: var(--topbar-h);
    min-height: 100vh;
    transition: margin-left 0.35s cubic-bezier(0.4,0,0.2,1);
  }
  .content { padding: 32px; max-width: 1400px; }

  /* ═══════════════════════════════════════
     SETTINGS SPECIFIC STYLES
  ═══════════════════════════════════════ */
  .settings-container {
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
  }
  .settings-sidebar-nav {
    width: 260px;
    flex-shrink: 0;
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    padding: 12px 0;
    height: fit-content;
    position: sticky;
    top: calc(var(--topbar-h) + 20px);
  }
  .settings-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-mid);
    cursor: pointer;
    transition: var(--transition);
    border-left: 3px solid transparent;
  }
  .settings-nav-item svg {
    width: 18px;
    height: 18px;
    opacity: 0.7;
  }
  .settings-nav-item.active {
    background: var(--cream);
    border-left-color: var(--walnut-mid);
    color: var(--walnut);
    font-weight: 600;
  }
  .settings-nav-item.active svg { opacity: 1; color: var(--walnut); }
  .settings-nav-item:hover:not(.active) {
    background: var(--cream-deep);
    color: var(--walnut);
  }
  .settings-panel {
    flex: 1;
    min-width: 0;
  }
  .settings-section {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    margin-bottom: 28px;
    overflow: hidden;
  }
  .section-header {
    padding: 22px 28px 12px 28px;
    border-bottom: 1px solid rgba(139,0,0,0.05);
  }
  .section-header h2 {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
  }
  .section-header p {
    font-size: 13px;
    color: var(--text-muted);
    margin-top: 4px;
  }
  .settings-form {
    padding: 20px 28px 28px;
  }
  .form-row {
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 16px;
  }
  .form-group {
    flex: 1;
    min-width: 200px;
  }
  .form-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-muted);
    margin-bottom: 8px;
  }
  input, select, textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1.5px solid rgba(139,0,0,0.12);
    background: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    transition: var(--transition);
    outline: none;
  }
  input:focus, select:focus, textarea:focus {
    border-color: var(--walnut-mid);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.1);
  }
  .toggle-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid rgba(139,0,0,0.05);
  }
  .toggle-label {
    font-weight: 500;
    font-size: 14px;
  }
  .toggle-desc {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 2px;
  }
  .toggle-switch {
    position: relative;
    width: 48px;
    height: 24px;
    background: #ddd;
    border-radius: 30px;
    cursor: pointer;
    transition: background 0.25s;
  }
  .toggle-switch.active {
    background: var(--walnut-mid);
  }
  .toggle-switch::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    top: 2px;
    left: 3px;
    transition: transform 0.25s;
  }
  .toggle-switch.active::after {
    transform: translateX(22px);
  }
  .color-swatch {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
  }
  .color-option {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    cursor: pointer;
    border: 3px solid transparent;
    transition: var(--transition);
  }
  .color-option.selected {
    border-color: var(--text-dark);
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .btn-primary {
    background: var(--walnut-mid);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
  }
  .btn-primary:hover {
    background: var(--walnut);
    transform: translateY(-2px);
  }
  .btn-secondary {
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.2);
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    margin-left: 12px;
  }
  .danger-zone {
    background: #fff8f8;
    border-left: 4px solid var(--walnut);
  }
  .danger-btn {
    background: transparent;
    border: 1px solid var(--walnut);
    color: var(--walnut);
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
  }
  .danger-btn:hover {
    background: var(--walnut);
    color: white;
  }
  .toast-message {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #1e2a1e;
    color: white;
    padding: 12px 24px;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 500;
    z-index: 200;
    opacity: 0;
    transition: opacity 0.2s;
    pointer-events: none;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
  }
  .toast-message.show {
    opacity: 1;
  }
  hr {
    margin: 20px 0;
    border: none;
    height: 1px;
    background: rgba(139,0,0,0.08);
  }

  /* Responsive */
  @media (max-width: 960px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .topbar { left: 0; }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
    .settings-sidebar-nav { width: 100%; position: static; margin-bottom: 20px; display: flex; overflow-x: auto; }
    .settings-nav-item { white-space: nowrap; }
    .settings-container { flex-direction: column; }
  }
  @media (max-width: 600px) {
    .content { padding: 20px 16px; }
    .form-row { flex-direction: column; }
  }
  .sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(10,2,4,0.55);
    z-index: 99;
  }
  .sidebar-overlay.open { display: block; }
</style>
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <span class="logo-text">KiteaCall</span>
  </div>
  <div class="sidebar-divider"></div>
  <div class="nav-section-label">Main Menu</div>
  <ul class="nav-list">
    <li class="nav-item"><a class="nav-link" href="/dashboard"><svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="#"><svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>Evaluations<span class="nav-badge">14</span></a></li>
    <li class="nav-item"><a class="nav-link" href="/users"><svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>Users</a></li>
    <li class="nav-item"><a class="nav-link" href="#"><svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>Reports</a></li>
    <li class="nav-item"><a class="nav-link active" href="#"><svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>Settings</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar">KM</div>
      <div class="sidebar-user-info"><div class="sidebar-user-name">Karim Mansouri</div><div class="sidebar-user-role">Manager · Team A</div></div>
    </div>
  </div>
</aside>

<header class="topbar">
  <div class="hamburger" onclick="toggleSidebar()"><span></span><span></span><span></span></div>
  <div class="topbar-title"><h1>Settings</h1><p>Customize your workspace & preferences · Wednesday, 6 May 2026</p></div>
  <div class="topbar-actions">
    <button class="topbar-btn"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
    <button class="topbar-btn"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg><span class="notif-dot"></span></button>
    <div class="topbar-divider"></div>
    <div class="topbar-profile"><div class="topbar-avatar">KM</div><div class="topbar-profile-info"><span class="topbar-profile-name">Karim M.</span><span class="role-badge">Manager</span></div></div>
  </div>
</header>

<main class="main">
<div class="content">
  <div class="settings-container">
    <!-- left nav -->
    <div class="settings-sidebar-nav">
      <div class="settings-nav-item active" data-tab="profile"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Profile</div>
      <div class="settings-nav-item" data-tab="notifications"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>Notifications</div>
      <div class="settings-nav-item" data-tab="appearance"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>Appearance</div>
      <div class="settings-nav-item" data-tab="security"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>Security</div>
      <div class="settings-nav-item" data-tab="team"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>Team Preferences</div>
    </div>
    <!-- right panels -->
    <div class="settings-panel" id="settingsPanel">
      <!-- Profile Section -->
      <div class="settings-section tab-pane active" id="profileTab">
        <div class="section-header"><h2>Profile information</h2><p>Update your personal details and avatar preferences</p></div>
        <div class="settings-form">
          <div class="form-row"><div class="form-group"><label>Full name</label><input type="text" id="fullName" value="Karim Mansouri" placeholder="Full name"></div><div class="form-group"><label>Email address</label><input type="email" id="email" value="karim.mansouri@kiteacall.com"></div></div>
          <div class="form-row"><div class="form-group"><label>Role / Title</label><input type="text" id="role" value="Senior Performance Manager"></div><div class="form-group"><label>Department</label><input type="text" id="dept" value="Quality & Evaluation"></div></div>
          <div class="form-row"><div class="form-group"><label>Bio</label><textarea rows="3" placeholder="Short bio...">Leading evaluation strategies and team performance insights.</textarea></div></div>
          <button class="btn-primary" id="saveProfileBtn">Save changes</button>
        </div>
      </div>

      <!-- Notifications -->
      <div class="settings-section tab-pane" id="notificationsTab" style="display:none">
        <div class="section-header"><h2>Notification preferences</h2><p>Manage how you receive alerts and updates</p></div>
        <div class="settings-form">
          <div class="toggle-group"><div><div class="toggle-label">Email summaries</div><div class="toggle-desc">Weekly performance digest</div></div><div class="toggle-switch" data-toggle="emailDigest"></div></div>
          <div class="toggle-group"><div><div class="toggle-label">Evaluation reminders</div><div class="toggle-desc">Get notified when evaluations are pending</div></div><div class="toggle-switch active" data-toggle="evalReminders"></div></div>
          <div class="toggle-group"><div><div class="toggle-label">Mentions & comments</div><div class="toggle-desc">Real-time alerts for team feedback</div></div><div class="toggle-switch active" data-toggle="mentions"></div></div>
          <button class="btn-primary" id="saveNotifBtn">Save preferences</button>
        </div>
      </div>

      <!-- Appearance -->
      <div class="settings-section tab-pane" id="appearanceTab" style="display:none">
        <div class="section-header"><h2>Theme & appearance</h2><p>Customize your dashboard look and feel</p></div>
        <div class="settings-form">
          <div class="form-group"><label>Color scheme (accent)</label><div class="color-swatch"><div class="color-option" style="background:#8B0000" data-color="walnut"></div><div class="color-option" style="background:#C0152A" data-color="walnut-mid"></div><div class="color-option selected" style="background:#F5A623" data-color="gold"></div><div class="color-option" style="background:#7A8C72" data-color="sage"></div></div></div>
          <div class="toggle-group"><div><div class="toggle-label">Compact density</div><div class="toggle-desc">Reduce spacing between elements</div></div><div class="toggle-switch" data-toggle="compactDensity"></div></div>
          <button class="btn-primary" id="saveAppearanceBtn">Apply appearance</button>
        </div>
      </div>

      <!-- Security -->
      <div class="settings-section tab-pane" id="securityTab" style="display:none">
        <div class="section-header"><h2>Security & sessions</h2><p>Update password and active sessions</p></div>
        <div class="settings-form">
          <div class="form-group"><label>Current password</label><input type="password" placeholder="••••••"></div>
          <div class="form-row"><div class="form-group"><label>New password</label><input type="password" placeholder="New password"></div><div class="form-group"><label>Confirm password</label><input type="password" placeholder="Confirm"></div></div>
          <button class="btn-primary">Update password</button>
          <hr>
          <div class="danger-zone" style="padding:16px 0"><div class="toggle-label">Two-factor authentication</div><div class="toggle-desc">Add an extra layer of security</div><button class="btn-secondary" style="margin-top:12px">Enable 2FA</button></div>
        </div>
      </div>

      <!-- Team Preferences -->
      <div class="settings-section tab-pane" id="teamTab" style="display:none">
        <div class="section-header"><h2>Team & collaboration</h2><p>Set default evaluation templates and team visibility</p></div>
        <div class="settings-form">
          <div class="form-group"><label>Default evaluation template</label><select><option>Quality Framework Q2</option><option>Sales Scorecard</option><option>Customer Experience</option></select></div>
          <div class="toggle-group"><div><div class="toggle-label">Show leaderboard to all members</div><div class="toggle-desc">Public visibility of performance rankings</div></div><div class="toggle-switch active" data-toggle="leaderboardVisibility"></div></div>
          <button class="btn-primary" id="saveTeamBtn">Save team settings</button>
        </div>
      </div>
    </div>
  </div>
</div>
</main>
<div id="toastMsg" class="toast-message">✨ Settings saved</div>

<script>
  function toggleSidebar() { document.getElementById('sidebar').classList.toggle('open'); document.getElementById('overlay').classList.toggle('open'); }
  // Tab switching
  const navItems = document.querySelectorAll('.settings-nav-item');
  const panes = document.querySelectorAll('.tab-pane');
  navItems.forEach(item => {
    item.addEventListener('click', () => {
      const tabId = item.getAttribute('data-tab');
      navItems.forEach(nav => nav.classList.remove('active'));
      item.classList.add('active');
      panes.forEach(pane => pane.style.display = 'none');
      if (tabId === 'profile') document.getElementById('profileTab').style.display = 'block';
      if (tabId === 'notifications') document.getElementById('notificationsTab').style.display = 'block';
      if (tabId === 'appearance') document.getElementById('appearanceTab').style.display = 'block';
      if (tabId === 'security') document.getElementById('securityTab').style.display = 'block';
      if (tabId === 'team') document.getElementById('teamTab').style.display = 'block';
      if (window.innerWidth <= 960) toggleSidebar();
    });
  });

  // Initialize toggles
  document.querySelectorAll('.toggle-switch').forEach(toggle => {
    toggle.addEventListener('click', function(e) { this.classList.toggle('active'); });
  });

  // Color picker for appearance
  const colorOptions = document.querySelectorAll('.color-option');
  colorOptions.forEach(opt => {
    opt.addEventListener('click', () => {
      colorOptions.forEach(c => c.classList.remove('selected'));
      opt.classList.add('selected');
    });
  });

  function showToast(msg) {
    const toast = document.getElementById('toastMsg');
    toast.textContent = msg || '✅ Settings updated successfully';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2300);
  }

  document.getElementById('saveProfileBtn')?.addEventListener('click', () => { showToast('Profile information saved.'); });
  document.getElementById('saveNotifBtn')?.addEventListener('click', () => { showToast('Notification preferences updated.'); });
  document.getElementById('saveAppearanceBtn')?.addEventListener('click', () => { showToast('Appearance settings applied.'); });
  document.getElementById('saveTeamBtn')?.addEventListener('click', () => { showToast('Team preferences saved.'); });

  // Additional demo: update active class for sidebar links (just for aesthetics)
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
      if(this.getAttribute('href') !== '#' && this.getAttribute('href') !== '/users' && this.getAttribute('href') !== '/dashboard') e.preventDefault();
      document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
      this.classList.add('active');
      if (window.innerWidth <= 960) toggleSidebar();
    });
  });
  // manually sync settings active
  document.querySelector('.nav-link.active')?.classList.add('active');
</script>
</body>
</html>