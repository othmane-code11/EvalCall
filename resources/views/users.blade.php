@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('topbar_title', 'Gestion des utilisateurs')
@section('topbar_subtitle', 'Créer, modifier et gérer les comptes')

@section('content')

<style>
  /* ═══════════════════════════════════════
     PAGE HEADER
  ═══════════════════════════════════════ */
  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    animation: fadeUp 0.4s ease both;
  }
  .page-header-left h2 {
    font-family: 'Syne', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.5px;
    margin-bottom: 4px;
  }
  .page-header-left p { font-size: 13px; color: var(--text-muted); }

  .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 16px;
    border-radius: 10px;
    border: 1px solid rgba(139,0,0,0.14);
    background: var(--white);
    color: var(--text-mid);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: var(--shadow-card);
  }
  .btn-back:hover { background: var(--cream); border-color: rgba(139,0,0,0.25); transform: translateY(-1px); }
  .btn-back svg { width: 16px; height: 16px; }

  /* ═══════════════════════════════════════
     FORM LAYOUT
  ═══════════════════════════════════════ */
  .form-grid {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 20px;
    align-items: start;
  }

  .form-body { padding: 24px 24px 24px; }
  .form-header-gap { margin-bottom: 24px; }

  .field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 18px;
  }

  .field {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 16px;
  }

  .field-label {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .field-label .required { color: var(--walnut-mid); font-size: 14px; line-height: 1; }

  .field-input {
    width: 100%;
    padding: 11px 14px;
    border-radius: 10px;
    border: 1.5px solid rgba(139,0,0,0.12);
    background: var(--cream);
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--text-dark);
    transition: var(--transition);
    outline: none;
    appearance: none;
    -webkit-appearance: none;
  }
  .field-input::placeholder { color: var(--text-muted); }
  .field-input:focus {
    border-color: var(--walnut-mid);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.08);
  }
  .field-input:hover:not(:focus) { border-color: rgba(139,0,0,0.22); background: var(--white); }
  .field-input.is-invalid { border-color: var(--walnut-light); background: #fff8f8; }

  .field-hint { font-size: 11.5px; color: var(--text-muted); }
  .invalid-feedback { font-size: 11.5px; color: var(--walnut-light); margin-top: 2px; }

  .field-input-wrap { position: relative; }
  .field-input-wrap .field-input { padding-left: 40px; }
  .field-input-wrap .input-icon {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    width: 16px; height: 16px;
    color: var(--text-muted);
    pointer-events: none;
    transition: color 0.2s;
  }
  .field-input-wrap:focus-within .input-icon { color: var(--walnut-mid); }

  .pass-toggle {
    position: absolute;
    right: 13px; top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: var(--text-muted);
    width: 18px; height: 18px;
    transition: color 0.2s;
  }
  .pass-toggle:hover { color: var(--walnut-mid); }

  .form-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(139,0,0,0.08), transparent);
    margin: 4px 0 20px;
  }

  .section-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
  }
  .section-label::after { content: ''; flex: 1; height: 1px; background: rgba(139,0,0,0.07); }

  /* Role cards */
  .role-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }

  .role-card { position: relative; cursor: pointer; }
  .role-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

  .role-card-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 14px 10px;
    border-radius: 12px;
    border: 2px solid rgba(139,0,0,0.1);
    background: var(--cream);
    transition: var(--transition);
    text-align: center;
    user-select: none;
  }
  .role-card:hover .role-card-inner { border-color: rgba(192,21,42,0.3); background: var(--white); }
  .role-card input:checked ~ .role-card-inner {
    border-color: var(--walnut-mid);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.08), var(--shadow-card);
  }
  .role-card input:checked ~ .role-card-inner .role-icon-wrap {
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    color: #fff;
    box-shadow: 0 4px 12px rgba(192,21,42,0.3);
  }
  .role-card input:checked ~ .role-card-inner .role-name { color: var(--walnut-mid); }

  .role-icon-wrap {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: rgba(139,0,0,0.07);
    color: var(--text-mid);
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition);
  }
  .role-icon-wrap svg { width: 20px; height: 20px; }

  .role-name { font-size: 12px; font-weight: 700; color: var(--text-dark); transition: color 0.2s; letter-spacing: 0.2px; }
  .role-desc { font-size: 10px; color: var(--text-muted); line-height: 1.4; }

  .role-check {
    position: absolute; top: 8px; right: 8px;
    width: 16px; height: 16px;
    border-radius: 50%;
    background: var(--walnut-mid);
    display: flex; align-items: center; justify-content: center;
    opacity: 0;
    transform: scale(0.5);
    transition: var(--transition);
  }
  .role-check svg { width: 9px; height: 9px; stroke: #fff; }
  .role-card input:checked ~ .role-card-inner .role-check { opacity: 1; transform: scale(1); }

  /* Avatar preview */
  .avatar-preview-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 10px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.07);
    margin-bottom: 24px;
  }
  .avatar-preview {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800; font-size: 17px; color: #fff;
    box-shadow: 0 3px 12px rgba(192,21,42,0.25);
    transition: var(--transition);
  }
  .avatar-preview-name { font-size: 14px; font-weight: 700; color: var(--text-dark); }
  .avatar-preview-role { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

  /* Form actions */
  .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 24px;
    border-top: 1px solid rgba(139,0,0,0.06);
    background: var(--cream);
  }

  .btn-cancel {
    padding: 10px 20px;
    border-radius: 10px;
    border: 1.5px solid rgba(139,0,0,0.14);
    background: var(--white);
    color: var(--text-mid);
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px; font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: var(--transition);
  }
  .btn-cancel:hover { background: var(--cream-deep); border-color: rgba(139,0,0,0.25); }

  .btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, var(--walnut-mid) 0%, var(--walnut) 100%);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px; font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 16px rgba(192,21,42,0.3);
  }
  .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(192,21,42,0.4); }
  .btn-submit svg { width: 16px; height: 16px; }

  /* Sidebar cards */
  .role-guide { padding: 0 24px 20px; display: flex; flex-direction: column; gap: 8px; }
  .role-guide-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.05);
  }
  .rgi-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
  .rgi-label { font-size: 12px; font-weight: 600; color: var(--text-dark); }
  .rgi-desc  { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

  .info-list { padding: 20px 24px; display: flex; flex-direction: column; gap: 16px; }
  .info-item { display: flex; align-items: flex-start; gap: 12px; }
  .info-item-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px; }
  .info-item-text strong { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
  .info-item-text span  { font-size: 11.5px; color: var(--text-muted); line-height: 1.4; }

  /* ═══════════════════════════════════════
     USERS TABLE SECTION
  ═══════════════════════════════════════ */
  .users-section {
    margin-top: 32px;
    animation: fadeUp 0.5s 0.35s ease both;
  }

  .users-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
  }

  .users-section-title {
    font-family: 'Syne', sans-serif;
    font-size: 20px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.4px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .users-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    color: #fff;
    font-family: 'Syne', sans-serif;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 20px;
    box-shadow: 0 3px 10px rgba(192,21,42,0.25);
  }

  .users-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .users-search {
    position: relative;
  }
  .users-search input {
    padding: 8px 14px 8px 36px;
    border-radius: 9px;
    border: 1.5px solid rgba(139,0,0,0.12);
    background: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    color: var(--text-dark);
    outline: none;
    width: 220px;
    transition: var(--transition);
  }
  .users-search input:focus {
    border-color: var(--walnut-mid);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.08);
    width: 260px;
  }
  .users-search svg {
    position: absolute;
    left: 11px; top: 50%;
    transform: translateY(-50%);
    width: 15px; height: 15px;
    color: var(--text-muted);
    pointer-events: none;
  }

  .filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 13px;
    border-radius: 9px;
    border: 1.5px solid rgba(139,0,0,0.12);
    background: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-mid);
    cursor: pointer;
    transition: var(--transition);
    appearance: none;
    outline: none;
  }
  .filter-pill:focus, .filter-pill:hover {
    border-color: var(--walnut-mid);
    background: var(--cream);
  }

  /* ─── Table card ─── */
  .users-table-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    overflow: hidden;
  }

  .users-table-wrap { overflow-x: auto; }

  .users-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
    min-width: 700px;
  }

  .users-table thead tr { background: var(--cream); }

  .users-table th {
    padding: 11px 18px;
    text-align: left;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.9px;
    text-transform: uppercase;
    color: var(--text-muted);
    white-space: nowrap;
    border-bottom: 1px solid rgba(139,0,0,0.06);
    cursor: pointer;
    user-select: none;
    transition: color 0.15s;
  }
  .users-table th:hover { color: var(--walnut-mid); }
  .users-table th .sort-icon { display: inline; margin-left: 4px; opacity: 0.4; font-size: 9px; }
  .users-table th.sorted .sort-icon { opacity: 1; color: var(--walnut-mid); }

  .users-table td {
    padding: 13px 18px;
    color: var(--text-dark);
    border-bottom: 1px solid rgba(139,0,0,0.04);
    vertical-align: middle;
    white-space: nowrap;
  }
  .users-table tbody tr { transition: background 0.15s; }
  .users-table tbody tr:hover { background: rgba(255,245,245,0.7); }
  .users-table tbody tr:last-child td { border-bottom: none; }

  /* User cell */
  .user-cell {
    display: flex;
    align-items: center;
    gap: 11px;
  }

  .user-av {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 13px;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
  }

  .user-name { font-weight: 700; font-size: 13.5px; color: var(--text-dark); }
  .user-email { font-size: 11.5px; color: var(--text-muted); margin-top: 1px; }

  /* Role badge */
  .role-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.3px;
  }
  .role-badge-pill::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
  }

  .role-conseiller {
    background: rgba(122,140,114,0.12);
    color: #2e5c28;
    border: 1px solid rgba(122,140,114,0.2);
  }
  .role-conseiller::before { background: var(--sage); }

  .role-manager {
    background: rgba(245,166,35,0.1);
    color: #7a4e00;
    border: 1px solid rgba(245,166,35,0.25);
  }
  .role-manager::before { background: var(--gold); }

  .role-admin {
    background: rgba(192,21,42,0.09);
    color: var(--walnut);
    border: 1px solid rgba(192,21,42,0.18);
  }
  .role-admin::before { background: var(--walnut-mid); }

  /* Status badge */
  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
  }
  .status-active { background: rgba(122,140,114,0.1); color: #2e5c28; }
  .status-inactive { background: rgba(107,48,64,0.08); color: var(--text-mid); }

  /* Action buttons */
  .table-actions { display: flex; gap: 6px; align-items: center; }

  .btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 13px;
    border-radius: 8px;
    border: 1.5px solid rgba(245,166,35,0.35);
    background: rgba(245,166,35,0.08);
    color: #8B6200;
    font-family: 'DM Sans', sans-serif;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    white-space: nowrap;
  }
  .btn-edit svg { width: 13px; height: 13px; flex-shrink: 0; }
  .btn-edit:hover {
    background: var(--gold);
    border-color: var(--gold);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(245,166,35,0.35);
  }

  .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 13px;
    border-radius: 8px;
    border: 1.5px solid rgba(192,21,42,0.2);
    background: rgba(192,21,42,0.06);
    color: var(--walnut-mid);
    font-family: 'DM Sans', sans-serif;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
  }
  .btn-delete svg { width: 13px; height: 13px; flex-shrink: 0; }
  .btn-delete:hover {
    background: var(--walnut-mid);
    border-color: var(--walnut-mid);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(192,21,42,0.3);
  }

  /* Table footer */
  .users-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 20px;
    border-top: 1px solid rgba(139,0,0,0.05);
    background: var(--cream);
    flex-wrap: wrap;
    gap: 10px;
  }

  .table-footer-info { font-size: 12px; color: var(--text-muted); }

  .page-btns { display: flex; gap: 4px; }
  .page-btn {
    min-width: 30px; height: 30px;
    border-radius: 7px;
    border: 1px solid rgba(139,0,0,0.12);
    background: var(--white);
    color: var(--text-dark);
    font-family: 'DM Sans', sans-serif;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex; align-items: center; justify-content: center;
    padding: 0 8px;
  }
  .page-btn:hover { background: var(--cream-deep); }
  .page-btn.active { background: var(--walnut-mid); color: #fff; border-color: var(--walnut-mid); }
  .page-btn:disabled { opacity: 0.4; pointer-events: none; }

  /* ═══════════════════════════════════════
     DELETE CONFIRM MODAL
  ═══════════════════════════════════════ */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(10,2,4,0.5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
  }
  .modal-overlay.open { opacity: 1; pointer-events: all; }

  .modal-box {
    background: var(--white);
    border-radius: 18px;
    box-shadow: 0 24px 80px rgba(139,0,0,0.18), 0 4px 20px rgba(0,0,0,0.08);
    padding: 32px;
    max-width: 420px;
    width: 100%;
    transform: translateY(20px) scale(0.96);
    transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
    border: 1px solid rgba(139,0,0,0.07);
  }
  .modal-overlay.open .modal-box { transform: translateY(0) scale(1); }

  .modal-icon {
    width: 56px; height: 56px;
    border-radius: 16px;
    background: rgba(192,21,42,0.08);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
  }
  .modal-icon svg { width: 28px; height: 28px; color: var(--walnut-mid); }

  .modal-title {
    font-family: 'Syne', sans-serif;
    font-size: 20px;
    font-weight: 800;
    color: var(--text-dark);
    text-align: center;
    margin-bottom: 8px;
  }
  .modal-body {
    font-size: 13.5px;
    color: var(--text-muted);
    text-align: center;
    line-height: 1.6;
    margin-bottom: 24px;
  }
  .modal-body strong { color: var(--text-dark); font-weight: 700; }

  .modal-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
  }
  .modal-cancel {
    flex: 1;
    padding: 11px 20px;
    border-radius: 10px;
    border: 1.5px solid rgba(139,0,0,0.14);
    background: var(--white);
    color: var(--text-mid);
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px; font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
  }
  .modal-cancel:hover { background: var(--cream-deep); }

  .modal-confirm {
    flex: 1;
    padding: 11px 20px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut));
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px; font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 16px rgba(192,21,42,0.3);
    display: flex; align-items: center; justify-content: center; gap: 7px;
  }
  .modal-confirm:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(192,21,42,0.4); }
  .modal-confirm svg { width: 15px; height: 15px; }

  /* ═══════════════════════════════════════
     EDIT MODAL
  ═══════════════════════════════════════ */
  .edit-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(10,2,4,0.5);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
  }
  .edit-modal-overlay.open { opacity: 1; pointer-events: all; }

  .edit-modal-box {
    background: var(--white);
    border-radius: 18px;
    box-shadow: 0 24px 80px rgba(139,0,0,0.18), 0 4px 20px rgba(0,0,0,0.08);
    max-width: 560px;
    width: 100%;
    transform: translateY(20px) scale(0.96);
    transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
    border: 1px solid rgba(139,0,0,0.07);
    overflow: hidden;
    max-height: 90vh;
    overflow-y: auto;
  }
  .edit-modal-overlay.open .edit-modal-box { transform: translateY(0) scale(1); }

  .edit-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 28px 18px;
    border-bottom: 1px solid rgba(139,0,0,0.06);
    position: sticky;
    top: 0;
    background: var(--white);
    z-index: 2;
  }

  .edit-modal-title {
    font-family: 'Syne', sans-serif;
    font-size: 17px;
    font-weight: 800;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .edit-modal-title-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--walnut-mid), var(--walnut-light));
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    flex-shrink: 0;
  }
  .edit-modal-title-icon svg { width: 17px; height: 17px; }

  .modal-close-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid rgba(139,0,0,0.12);
    background: var(--cream);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    color: var(--text-muted);
    font-size: 18px;
    line-height: 1;
    flex-shrink: 0;
  }
  .modal-close-btn:hover { background: var(--cream-deep); color: var(--walnut-mid); }

  .edit-modal-body { padding: 24px 28px; }

  .edit-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 28px;
    border-top: 1px solid rgba(139,0,0,0.06);
    background: var(--cream);
    position: sticky;
    bottom: 0;
  }

  /* Edit modal role cards (compact) */
  .edit-role-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }

  /* ─── Responsive ─── */
  @media (max-width: 1100px) { .form-grid { grid-template-columns: 1fr; } }
  @media (max-width: 768px) {
    .users-section-header { flex-direction: column; align-items: flex-start; }
    .users-search input { width: 180px; }
    .users-search input:focus { width: 200px; }
  }
  @media (max-width: 640px) {
    .field-row { grid-template-columns: 1fr; }
    .role-cards { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; gap: 14px; align-items: flex-start; }
    .users-toolbar { width: 100%; }
    .users-search { flex: 1; }
    .users-search input { width: 100%; }
    .users-search input:focus { width: 100%; }
    .btn-edit span, .btn-delete span { display: none; }
    .btn-edit, .btn-delete { padding: 7px; }
    .edit-role-cards { grid-template-columns: 1fr; }
    .modal-actions { flex-direction: column; }
  }
</style>

{{-- Page header --}}
<div class="page-header">
  <div class="page-header-left">
    <h2>Nouveau compte utilisateur</h2>
    <p>Remplissez les informations ci-dessous pour créer un nouvel utilisateur.</p>
  </div>
  <a href="/dashboard" class="btn-back">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Retour au tableau de bord
  </a>
</div>

@if(session('success'))
  <div style="margin-bottom:20px;padding:12px 18px;background:rgba(122,140,114,0.12);color:#2e5c28;border:1px solid rgba(122,140,114,0.25);border-radius:10px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
  </div>
@endif

{{-- ══ CREATE FORM GRID ══ --}}
<div class="form-grid">

  {{-- Main form card --}}
  <div class="card" style="animation: fadeUp 0.45s 0.1s ease both;">
    <div class="card-header">
      <div>
        <div class="card-title">Informations utilisateur</div>
        <div class="card-subtitle">Détails du compte et rôle d'accès</div>
      </div>
    </div>

    <form id="createUserForm" action="/create-user" method="POST">
      @csrf

      <div class="form-body">

        {{-- Avatar preview --}}
        <div class="avatar-preview-wrap">
          <div class="avatar-preview" id="avatarPreview">?</div>
          <div>
            <div class="avatar-preview-name" id="previewName">Nouvel utilisateur</div>
            <div class="avatar-preview-role" id="previewRole">Rôle non sélectionné</div>
          </div>
        </div>

        {{-- Name row --}}
        <div class="field-row">
          <div class="field">
            <label class="field-label" for="first_name">
              Prénom <span class="required">*</span>
            </label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <input class="field-input @error('first_name') is-invalid @enderror"
                type="text" id="first_name" name="first_name"
                value="{{ old('first_name') }}" placeholder="ex. Karim"
                autocomplete="given-name" required>
            </div>
            @error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label class="field-label" for="last_name">
              Nom <span class="required">*</span>
            </label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <input class="field-input @error('last_name') is-invalid @enderror"
                type="text" id="last_name" name="last_name"
                value="{{ old('last_name') }}" placeholder="ex. Mansouri"
                autocomplete="family-name" required>
            </div>
            @error('last_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>
        </div>

        {{-- Email --}}
        <div class="field">
          <label class="field-label" for="email">
            Adresse e-mail <span class="required">*</span>
          </label>
          <div class="field-input-wrap">
            <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <input class="field-input @error('email') is-invalid @enderror"
              type="email" id="email" name="email"
              value="{{ old('email') }}" placeholder="utilisateur@kiteacall.com"
              autocomplete="email" required>
          </div>
          <span class="field-hint">Ceci sera utilisé comme identifiant de connexion.</span>
          @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-divider"></div>
        <div class="section-label">Sécurité du compte</div>

        {{-- Password row --}}
        <div class="field-row">
          <div class="field">
            <label class="field-label" for="password">
              Mot de passe <span class="required">*</span>
            </label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              <input class="field-input @error('password') is-invalid @enderror"
                type="password" id="password" name="password"
                placeholder="8 caractères min."
                autocomplete="new-password" required style="padding-right:44px">
              <svg class="pass-toggle" onclick="togglePass('password', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </div>
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>

          <div class="field">
            <label class="field-label" for="password_confirmation">
              Confirmer le mot de passe <span class="required">*</span>
            </label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
              <input class="field-input"
                type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Répéter le mot de passe"
                autocomplete="new-password" required style="padding-right:44px">
              <svg class="pass-toggle" onclick="togglePass('password_confirmation', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="form-divider"></div>
        <div class="section-label">Rôle et permissions</div>

        @error('role')<span class="invalid-feedback" style="display:block;margin-bottom:10px">{{ $message }}</span>@enderror

        <div class="role-cards">
          <label class="role-card">
            <input type="radio" name="role" value="conseiller" {{ old('role','conseiller')==='conseiller'?'checked':'' }}>
            <div class="role-card-inner">
              <div class="role-check"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
              <div class="role-icon-wrap"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
              <div class="role-name">Conseiller</div>
              <div class="role-desc">Voir ses évaluations, signer les formulaires</div>
            </div>
          </label>

          <label class="role-card">
            <input type="radio" name="role" value="manager" {{ old('role')==='manager'?'checked':'' }}>
            <div class="role-card-inner">
              <div class="role-check"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
              <div class="role-icon-wrap"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
              <div class="role-name">Manager</div>
              <div class="role-desc">Gérer les évaluations de l'équipe et les rapports</div>
            </div>
          </label>

          <label class="role-card">
            <input type="radio" name="role" value="admin" {{ old('role')==='admin'?'checked':'' }}>
            <div class="role-card-inner">
              <div class="role-check"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
              <div class="role-icon-wrap"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
              <div class="role-name">Admin</div>
              <div class="role-desc">Accès complet au système et configuration</div>
            </div>
          </label>
        </div>
      </div>

      <div class="form-actions">
        <a href="/users" class="btn-cancel">Annuler</a>
        <button type="submit" class="btn-submit">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
          </svg>
          Créer l'utilisateur
        </button>
      </div>
    </form>
  </div>

  {{-- Sidebar info card --}}
  <div class="card" style="animation: fadeUp 0.45s 0.2s ease both;">
    <div class="card-header">
      <div>
        <div class="card-title">Guide des rôles</div>
        <div class="card-subtitle">Permissions par rôle</div>
      </div>
    </div>
    <div class="role-guide">
      <div class="role-guide-item">
        <div class="rgi-dot" style="background:var(--sage)"></div>
        <div><div class="rgi-label">Conseiller</div><div class="rgi-desc">Voir & signer ses propres évaluations</div></div>
      </div>
      <div class="role-guide-item">
        <div class="rgi-dot" style="background:var(--gold)"></div>
        <div><div class="rgi-label">Manager</div><div class="rgi-desc">Créer des évals, gérer l'équipe & rapports</div></div>
      </div>
      <div class="role-guide-item">
        <div class="rgi-dot" style="background:var(--walnut-mid)"></div>
        <div><div class="rgi-label">Admin</div><div class="rgi-desc">Accès complet, gestion users & config</div></div>
      </div>
    </div>

    <div class="card-header" style="border-top:1px solid rgba(139,0,0,0.05)">
      <div><div class="card-title">Conseils</div><div class="card-subtitle">Bonnes pratiques</div></div>
    </div>
    <div class="info-list">
      <div class="info-item">
        <div class="info-item-icon" style="background:rgba(245,166,35,0.1)">💡</div>
        <div class="info-item-text">
          <strong>Mot de passe fort</strong>
          <span>Utilisez au moins 8 caractères avec majuscule, chiffre et symbole.</span>
        </div>
      </div>
      <div class="info-item">
        <div class="info-item-icon" style="background:rgba(122,140,114,0.1)">✉️</div>
        <div class="info-item-text">
          <strong>Email = Identifiant</strong>
          <span>L'utilisateur se connectera avec cette adresse e-mail.</span>
        </div>
      </div>
      <div class="info-item">
        <div class="info-item-icon" style="background:rgba(192,21,42,0.08)">🔐</div>
        <div class="info-item-text">
          <strong>Moindre privilège</strong>
          <span>Attribuez le rôle minimum nécessaire aux responsabilités de l'utilisateur.</span>
        </div>
      </div>
    </div>
  </div>

</div>{{-- /form-grid --}}


{{-- ══════════════════════════════════════
     USERS LIST SECTION
══════════════════════════════════════ --}}
<div class="users-section">

  <div class="users-section-header">
    <div class="users-section-title">
      Utilisateurs existants
      <span class="users-count-badge" id="usersCount">6</span>
    </div>
    <div class="users-toolbar">
      <div class="users-search">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="usersSearch" placeholder="Rechercher un utilisateur…" oninput="filterUsers()">
      </div>
      <select class="filter-pill" id="roleFilter" onchange="filterUsers()">
        <option value="">Tous les rôles</option>
        <option value="conseiller">Conseiller</option>
        <option value="manager">Manager</option>
        <option value="admin">Admin</option>
      </select>
    </div>
  </div>

  <div class="users-table-card">
    <div class="users-table-wrap">
      <table class="users-table" id="usersTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)" class="sorted">Utilisateur <span class="sort-icon">↕</span></th>
            <th onclick="sortTable(1)">Rôle <span class="sort-icon">↕</span></th>
            <th onclick="sortTable(2)">Statut <span class="sort-icon">↕</span></th>
            <th onclick="sortTable(3)">Créé le <span class="sort-icon">↕</span></th>
            <th onclick="sortTable(4)">Évaluations <span class="sort-icon">↕</span></th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="usersBody">

          <tr data-name="Youssef El Amrani" data-role="conseiller" data-status="actif">
            <td>
              <div class="user-cell">
                <div class="user-av" style="background:linear-gradient(135deg,#C0152A,#F5A623)">YA</div>
                <div>
                  <div class="user-name">Youssef El Amrani</div>
                  <div class="user-email">y.elamrani@kiteacall.com</div>
                </div>
              </div>
            </td>
            <td><span class="role-badge-pill role-conseiller">Conseiller</span></td>
            <td><span class="status-pill status-active">● Actif</span></td>
            <td style="color:var(--text-muted);font-size:12.5px">12 Jan 2026</td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;">42</td>
            <td>
              <div class="table-actions" style="justify-content:flex-end">
                <button class="btn-edit" onclick="openEditModal({id:1,first:'Youssef',last:'El Amrani',email:'y.elamrani@kiteacall.com',role:'conseiller'})">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  <span>Modifier</span>
                </button>
                <button class="btn-delete" onclick="openDeleteModal(1,'Youssef El Amrani')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  <span>Supprimer</span>
                </button>
              </div>
            </td>
          </tr>

          <tr data-name="Fatima Zahra Idrissi" data-role="conseiller" data-status="actif">
            <td>
              <div class="user-cell">
                <div class="user-av" style="background:linear-gradient(135deg,#6B3040,#C0152A)">FI</div>
                <div>
                  <div class="user-name">Fatima Zahra Idrissi</div>
                  <div class="user-email">fz.idrissi@kiteacall.com</div>
                </div>
              </div>
            </td>
            <td><span class="role-badge-pill role-conseiller">Conseiller</span></td>
            <td><span class="status-pill status-active">● Actif</span></td>
            <td style="color:var(--text-muted);font-size:12.5px">18 Jan 2026</td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;">38</td>
            <td>
              <div class="table-actions" style="justify-content:flex-end">
                <button class="btn-edit" onclick="openEditModal({id:2,first:'Fatima Zahra',last:'Idrissi',email:'fz.idrissi@kiteacall.com',role:'conseiller'})">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  <span>Modifier</span>
                </button>
                <button class="btn-delete" onclick="openDeleteModal(2,'Fatima Zahra Idrissi')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  <span>Supprimer</span>
                </button>
              </div>
            </td>
          </tr>

          <tr data-name="Karim Mansouri" data-role="manager" data-status="actif">
            <td>
              <div class="user-cell">
                <div class="user-av" style="background:linear-gradient(135deg,#D4900A,#F5A623)">KM</div>
                <div>
                  <div class="user-name">Karim Mansouri</div>
                  <div class="user-email">k.mansouri@kiteacall.com</div>
                </div>
              </div>
            </td>
            <td><span class="role-badge-pill role-manager">Manager</span></td>
            <td><span class="status-pill status-active">● Actif</span></td>
            <td style="color:var(--text-muted);font-size:12.5px">03 Nov 2025</td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;">—</td>
            <td>
              <div class="table-actions" style="justify-content:flex-end">
                <button class="btn-edit" onclick="openEditModal({id:3,first:'Karim',last:'Mansouri',email:'k.mansouri@kiteacall.com',role:'manager'})">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  <span>Modifier</span>
                </button>
                <button class="btn-delete" onclick="openDeleteModal(3,'Karim Mansouri')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  <span>Supprimer</span>
                </button>
              </div>
            </td>
          </tr>

          <tr data-name="Mehdi Benali" data-role="conseiller" data-status="actif">
            <td>
              <div class="user-cell">
                <div class="user-av" style="background:linear-gradient(135deg,#7A8C72,#5a6e53)">MB</div>
                <div>
                  <div class="user-name">Mehdi Benali</div>
                  <div class="user-email">m.benali@kiteacall.com</div>
                </div>
              </div>
            </td>
            <td><span class="role-badge-pill role-conseiller">Conseiller</span></td>
            <td><span class="status-pill status-active">● Actif</span></td>
            <td style="color:var(--text-muted);font-size:12.5px">25 Feb 2026</td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;">31</td>
            <td>
              <div class="table-actions" style="justify-content:flex-end">
                <button class="btn-edit" onclick="openEditModal({id:4,first:'Mehdi',last:'Benali',email:'m.benali@kiteacall.com',role:'conseiller'})">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  <span>Modifier</span>
                </button>
                <button class="btn-delete" onclick="openDeleteModal(4,'Mehdi Benali')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  <span>Supprimer</span>
                </button>
              </div>
            </td>
          </tr>

          <tr data-name="Nadia Chraibi" data-role="conseiller" data-status="inactif">
            <td>
              <div class="user-cell">
                <div class="user-av" style="background:linear-gradient(135deg,#9C7078,#6B3040)">NC</div>
                <div>
                  <div class="user-name">Nadia Chraibi</div>
                  <div class="user-email">n.chraibi@kiteacall.com</div>
                </div>
              </div>
            </td>
            <td><span class="role-badge-pill role-conseiller">Conseiller</span></td>
            <td><span class="status-pill status-inactive">○ Inactif</span></td>
            <td style="color:var(--text-muted);font-size:12.5px">10 Mar 2026</td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;">27</td>
            <td>
              <div class="table-actions" style="justify-content:flex-end">
                <button class="btn-edit" onclick="openEditModal({id:5,first:'Nadia',last:'Chraibi',email:'n.chraibi@kiteacall.com',role:'conseiller'})">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  <span>Modifier</span>
                </button>
                <button class="btn-delete" onclick="openDeleteModal(5,'Nadia Chraibi')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  <span>Supprimer</span>
                </button>
              </div>
            </td>
          </tr>

          <tr data-name="Sarah Alaoui" data-role="admin" data-status="actif">
            <td>
              <div class="user-cell">
                <div class="user-av" style="background:linear-gradient(135deg,#8B0000,#C0152A)">SA</div>
                <div>
                  <div class="user-name">Sarah Alaoui</div>
                  <div class="user-email">s.alaoui@kiteacall.com</div>
                </div>
              </div>
            </td>
            <td><span class="role-badge-pill role-admin">Admin</span></td>
            <td><span class="status-pill status-active">● Actif</span></td>
            <td style="color:var(--text-muted);font-size:12.5px">01 Oct 2025</td>
            <td style="font-family:'Syne',sans-serif;font-weight:700;">—</td>
            <td>
              <div class="table-actions" style="justify-content:flex-end">
                <button class="btn-edit" onclick="openEditModal({id:6,first:'Sarah',last:'Alaoui',email:'s.alaoui@kiteacall.com',role:'admin'})">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  <span>Modifier</span>
                </button>
                <button class="btn-delete" onclick="openDeleteModal(6,'Sarah Alaoui')">
                  <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  <span>Supprimer</span>
                </button>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

    <div class="users-table-footer">
      <span class="table-footer-info" id="usersFooterInfo">Affichage de 6 utilisateurs</span>
      <div class="page-btns">
        <button class="page-btn" disabled>‹</button>
        <button class="page-btn active">1</button>
        <button class="page-btn" disabled>›</button>
      </div>
    </div>
  </div>
</div>{{-- /users-section --}}


{{-- ══ DELETE CONFIRM MODAL ══ --}}
<div class="modal-overlay" id="deleteModal" onclick="if(event.target===this)closeDeleteModal()">
  <div class="modal-box">
    <div class="modal-icon">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
      </svg>
    </div>
    <div class="modal-title">Supprimer l'utilisateur ?</div>
    <div class="modal-body">
      Vous êtes sur le point de supprimer <strong id="deleteUserName">—</strong>.<br>
      Cette action est irréversible et supprimera toutes les données associées.
    </div>
    <div class="modal-actions">
      <button class="modal-cancel" onclick="closeDeleteModal()">Annuler</button>
      <form id="deleteForm" method="POST" style="flex:1;display:flex;">
        @csrf
        @method('DELETE')
        <button type="submit" class="modal-confirm" style="width:100%">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
          Oui, supprimer
        </button>
      </form>
    </div>
  </div>
</div>

{{-- ══ EDIT USER MODAL ══ --}}
<div class="edit-modal-overlay" id="editModal" onclick="if(event.target===this)closeEditModal()">
  <div class="edit-modal-box">

    <div class="edit-modal-header">
      <div class="edit-modal-title">
        <div class="edit-modal-title-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
        </div>
        Modifier l'utilisateur
      </div>
      <button class="modal-close-btn" onclick="closeEditModal()">×</button>
    </div>

    <div class="edit-modal-body">

      {{-- Avatar preview --}}
      <div class="avatar-preview-wrap" style="margin-bottom:20px">
        <div class="avatar-preview" id="editAvatarPreview">?</div>
        <div>
          <div class="avatar-preview-name" id="editPreviewName">—</div>
          <div class="avatar-preview-role" id="editPreviewRole">—</div>
        </div>
      </div>

      <form id="editUserForm" method="POST">
        @csrf
        @method('PUT')

        <div class="field-row" style="margin-bottom:16px">
          <div class="field" style="margin-bottom:0">
            <label class="field-label" for="edit_first_name">Prénom <span class="required">*</span></label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <input class="field-input" type="text" id="edit_first_name" name="first_name" required placeholder="Prénom">
            </div>
          </div>
          <div class="field" style="margin-bottom:0">
            <label class="field-label" for="edit_last_name">Nom <span class="required">*</span></label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <input class="field-input" type="text" id="edit_last_name" name="last_name" required placeholder="Nom de famille">
            </div>
          </div>
        </div>

        <div class="field">
          <label class="field-label" for="edit_email">Adresse e-mail <span class="required">*</span></label>
          <div class="field-input-wrap">
            <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <input class="field-input" type="email" id="edit_email" name="email" required placeholder="email@kiteacall.com">
          </div>
        </div>

        <div class="form-divider" style="margin-bottom:16px"></div>
        <div class="section-label" style="margin-bottom:14px">Nouveau mot de passe <span style="font-size:10px;font-weight:500;text-transform:none;letter-spacing:0;color:var(--text-muted)">(laisser vide pour ne pas changer)</span></div>

        <div class="field-row" style="margin-bottom:16px">
          <div class="field" style="margin-bottom:0">
            <label class="field-label" for="edit_password">Mot de passe</label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              <input class="field-input" type="password" id="edit_password" name="password" placeholder="Nouveau mot de passe" style="padding-right:44px">
              <svg class="pass-toggle" onclick="togglePass('edit_password',this)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
          </div>
          <div class="field" style="margin-bottom:0">
            <label class="field-label" for="edit_password_confirmation">Confirmer</label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <input class="field-input" type="password" id="edit_password_confirmation" name="password_confirmation" placeholder="Répéter" style="padding-right:44px">
              <svg class="pass-toggle" onclick="togglePass('edit_password_confirmation',this)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
          </div>
        </div>

        <div class="form-divider" style="margin-bottom:16px"></div>
        <div class="section-label" style="margin-bottom:14px">Rôle et permissions</div>

        <div class="edit-role-cards">
          <label class="role-card">
            <input type="radio" name="role" id="edit_role_conseiller" value="conseiller">
            <div class="role-card-inner">
              <div class="role-check"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
              <div class="role-icon-wrap"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
              <div class="role-name">Conseiller</div>
            </div>
          </label>
          <label class="role-card">
            <input type="radio" name="role" id="edit_role_manager" value="manager">
            <div class="role-card-inner">
              <div class="role-check"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
              <div class="role-icon-wrap"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
              <div class="role-name">Manager</div>
            </div>
          </label>
          <label class="role-card">
            <input type="radio" name="role" id="edit_role_admin" value="admin">
            <div class="role-card-inner">
              <div class="role-check"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
              <div class="role-icon-wrap"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
              <div class="role-name">Admin</div>
            </div>
          </label>
        </div>

      </form>
    </div>

    <div class="edit-modal-footer">
      <button class="btn-cancel" onclick="closeEditModal()">Annuler</button>
      <button type="button" class="btn-submit" onclick="document.getElementById('editUserForm').submit()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Enregistrer les modifications
      </button>
    </div>
  </div>
</div>

@push('scripts')
<script>
  /* ── Create form: Avatar live preview ── */
  const firstNameEl = document.getElementById('first_name');
  const lastNameEl  = document.getElementById('last_name');
  const avatarEl    = document.getElementById('avatarPreview');
  const previewName = document.getElementById('previewName');
  const previewRole = document.getElementById('previewRole');

  function updatePreview() {
    const fn = firstNameEl.value.trim();
    const ln = lastNameEl.value.trim();
    avatarEl.textContent = ((fn[0]||'')+(ln[0]||'')).toUpperCase() || '?';
    previewName.textContent = (fn||ln) ? [fn,ln].filter(Boolean).join(' ') : 'Nouvel utilisateur';
  }
  firstNameEl.addEventListener('input', updatePreview);
  lastNameEl.addEventListener('input', updatePreview);

  document.querySelectorAll('#createUserForm input[name="role"]').forEach(r => {
    r.addEventListener('change', function() {
      const labels = { conseiller:'Conseiller', manager:'Manager', admin:'Admin' };
      previewRole.textContent = labels[this.value] ?? 'Rôle non sélectionné';
    });
  });

  updatePreview();
  const checkedRole = document.querySelector('#createUserForm input[name="role"]:checked');
  if (checkedRole) {
    const labels = { conseiller:'Conseiller', manager:'Manager', admin:'Admin' };
    previewRole.textContent = labels[checkedRole.value] ?? 'Rôle non sélectionné';
  }

  /* ── Password show/hide ── */
  function togglePass(inputId, icon) {
    const inp = document.getElementById(inputId);
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    icon.style.color = show ? 'var(--walnut-mid)' : '';
  }

  /* ── DELETE MODAL ── */
  let deleteUserId = null;

  function openDeleteModal(id, name) {
    deleteUserId = id;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteForm').action = '/users/' + id;
    document.getElementById('deleteModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
    document.body.style.overflow = '';
  }

  /* ── EDIT MODAL ── */
  function openEditModal(user) {
    // Set form action
    document.getElementById('editUserForm').action = '/users/' + user.id;

    // Populate fields
    document.getElementById('edit_first_name').value = user.first;
    document.getElementById('edit_last_name').value  = user.last;
    document.getElementById('edit_email').value      = user.email;
    document.getElementById('edit_password').value   = '';
    document.getElementById('edit_password_confirmation').value = '';

    // Set role radio
    const roleInput = document.getElementById('edit_role_' + user.role);
    if (roleInput) roleInput.checked = true;

    // Update avatar preview
    const initials = ((user.first[0]||'') + (user.last[0]||'')).toUpperCase();
    document.getElementById('editAvatarPreview').textContent = initials || '?';
    document.getElementById('editPreviewName').textContent = user.first + ' ' + user.last;
    const labels = { conseiller:'Conseiller', manager:'Manager', admin:'Admin' };
    document.getElementById('editPreviewRole').textContent = labels[user.role] || '—';

    document.getElementById('editModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeEditModal() {
    document.getElementById('editModal').classList.remove('open');
    document.body.style.overflow = '';
  }

  // Edit modal live preview
  document.getElementById('edit_first_name').addEventListener('input', updateEditPreview);
  document.getElementById('edit_last_name').addEventListener('input', updateEditPreview);
  document.querySelectorAll('#editUserForm input[name="role"]').forEach(r => {
    r.addEventListener('change', function() {
      const labels = { conseiller:'Conseiller', manager:'Manager', admin:'Admin' };
      document.getElementById('editPreviewRole').textContent = labels[this.value] || '—';
    });
  });

  function updateEditPreview() {
    const fn = document.getElementById('edit_first_name').value.trim();
    const ln = document.getElementById('edit_last_name').value.trim();
    document.getElementById('editAvatarPreview').textContent = ((fn[0]||'')+(ln[0]||'')).toUpperCase() || '?';
    document.getElementById('editPreviewName').textContent = [fn,ln].filter(Boolean).join(' ') || '—';
  }

  /* ── Close modals on Escape ── */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeDeleteModal(); closeEditModal(); }
  });

  /* ── Users table: live search + role filter ── */
  function filterUsers() {
    const q    = document.getElementById('usersSearch').value.toLowerCase();
    const role = document.getElementById('roleFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#usersBody tr');
    let visible = 0;

    rows.forEach(row => {
      const name     = (row.dataset.name || '').toLowerCase();
      const rowRole  = (row.dataset.role || '').toLowerCase();
      const matchQ    = !q    || name.includes(q);
      const matchRole = !role || rowRole === role;
      const show = matchQ && matchRole;
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    document.getElementById('usersFooterInfo').textContent =
      visible === 1 ? '1 utilisateur trouvé' : visible + ' utilisateurs trouvés';
    document.getElementById('usersCount').textContent = visible;
  }

  /* ── Sort table columns ── */
  let sortDir = {};
  function sortTable(colIndex) {
    const tbody = document.getElementById('usersBody');
    const rows  = Array.from(tbody.querySelectorAll('tr'));
    sortDir[colIndex] = !sortDir[colIndex];
    const dir = sortDir[colIndex] ? 1 : -1;

    rows.sort((a, b) => {
      const aCell = a.cells[colIndex]?.textContent.trim() || '';
      const bCell = b.cells[colIndex]?.textContent.trim() || '';
      return aCell.localeCompare(bCell, 'fr') * dir;
    });

    rows.forEach(r => tbody.appendChild(r));

    document.querySelectorAll('.users-table th').forEach((th, i) => {
      th.classList.toggle('sorted', i === colIndex);
      const icon = th.querySelector('.sort-icon');
      if (icon) icon.textContent = i === colIndex ? (sortDir[colIndex] ? '↑' : '↓') : '↕';
    });
  }
</script>
@endpush

@endsection