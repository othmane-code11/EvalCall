@extends('layouts.app')

@section('title', 'Create Evaluation — EvalCall')
@section('topbar_title', 'Create Evaluation')
@section('topbar_subtitle', 'Wednesday, 6 May 2026 · Evaluations')

@section('content')
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
    --shadow-hover: 0 8px 32px rgba(139,0,0,0.14), 0 2px 8px rgba(0,0,0,0.06);
    --transition:   all 0.25s cubic-bezier(0.4,0,0.2,1);
  }
  /* ── Signature pad ── */
.signature-outer {
  border: 2px dashed rgba(139,0,0,0.18);
  border-radius: 12px;
  background: linear-gradient(135deg, #fff9f9, var(--white));
  overflow: hidden;
  transition: var(--transition);
}
.signature-outer:hover { border-color: var(--walnut-mid); }
.signature-outer.signing {
  border-color: var(--walnut-mid);
  border-style: solid;
  box-shadow: 0 0 0 3px rgba(192,21,42,0.07);
}
.sig-topbar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 14px;
  border-bottom: 1px solid rgba(139,0,0,0.06);
  background: var(--cream);
}
.sig-hint {
  font-size: 11.5px; color: var(--text-muted);
  display: flex; align-items: center; gap: 6px;
}
.sig-actions { display: flex; gap: 8px; }
.sig-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 12px; border-radius: 7px;
  font-size: 11.5px; font-weight: 600; cursor: pointer;
  transition: var(--transition);
  border: 1.5px solid rgba(139,0,0,0.15);
  background: var(--white); color: var(--text-mid);
  font-family: 'DM Sans', sans-serif;
}
.sig-btn svg { width: 12px; height: 12px; }
.sig-btn:hover {
  background: var(--cream-deep);
  border-color: var(--walnut-mid);
  color: var(--walnut-mid);
}
.sig-btn.sig-btn-primary {
  background: linear-gradient(135deg, var(--walnut), var(--walnut-mid));
  color: #fff; border-color: transparent;
  box-shadow: 0 2px 8px rgba(139,0,0,0.25);
}
.sig-btn.sig-btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

#signatureCanvas {
  display: block; width: 100%; height: 180px;
  cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Cpath fill='%238B0000' d='M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z'/%3E%3C/svg%3E") 0 24, crosshair;
  background: #fff;
  touch-action: none;
}
.sig-status {
  padding: 8px 14px;
  border-top: 1px solid rgba(139,0,0,0.05);
  display: flex; align-items: center; justify-content: space-between;
  background: #fafafa;
}
.sig-status-label { font-size: 11px; color: var(--text-muted); display: flex; align-items: center; }
.sig-status-dot {
  width: 7px; height: 7px; border-radius: 50%;
  background: rgba(139,0,0,0.15); margin-right: 7px; flex-shrink: 0;
  transition: var(--transition);
}
.sig-status-dot.active { background: var(--sage); box-shadow: 0 0 0 3px rgba(122,140,114,0.2); }
.sig-strokes { font-size: 11px; color: var(--text-muted); }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text-dark);
    min-height: 100vh;
    overflow-x: hidden;
  }

  .content { padding: 32px; max-width: 1100px; }

  .breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    font-size: 12.5px;
    color: var(--text-muted);
    animation: fadeUp 0.4s ease both;
  }
  .breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
  .breadcrumb a:hover { color: var(--walnut-mid); }
  .breadcrumb-sep { opacity: 0.4; font-size: 10px; }
  .breadcrumb-current { color: var(--walnut-mid); font-weight: 600; }

  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 28px;
    animation: fadeUp 0.4s 0.05s ease both;
  }
  .page-header-left h2 {
    font-family: 'Syne', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.6px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .page-header-left h2 .header-icon {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, var(--walnut), var(--walnut-mid));
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(139,0,0,0.3);
  }
  .page-header-left h2 .header-icon svg { width: 18px; height: 18px; color: #fff; }
  .page-header-left p { font-size: 13px; color: var(--text-muted); margin-top: 6px; margin-left: 48px; }

  .eval-layout {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 24px;
    align-items: start;
  }

  .form-stack { display: flex; flex-direction: column; gap: 20px; }

  .eval-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    overflow: hidden;
    animation: fadeUp 0.5s ease both;
  }
  .eval-card:nth-child(1) { animation-delay: 0.1s; }
  .eval-card:nth-child(2) { animation-delay: 0.15s; }
  .eval-card:nth-child(3) { animation-delay: 0.2s; }
  .eval-card:nth-child(4) { animation-delay: 0.25s; }

  .card-header-strip {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 24px;
    border-bottom: 1px solid rgba(139,0,0,0.05);
    background: linear-gradient(to right, var(--cream), var(--white));
  }
  .card-header-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .card-header-icon svg { width: 17px; height: 17px; }
  .card-header-text h3 {
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.2px;
  }
  .card-header-text p { font-size: 11.5px; color: var(--text-muted); margin-top: 1px; }

  .card-body { padding: 24px; }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
  }
  .form-row:last-child { margin-bottom: 0; }
  .form-row.three { grid-template-columns: 1fr 1fr 1fr; }
  .form-row.full { grid-template-columns: 1fr; }

  .form-group { display: flex; flex-direction: column; gap: 6px; }

  .form-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-mid);
    letter-spacing: 0.2px;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .form-label .required {
    color: var(--walnut-mid);
    font-size: 14px;
    line-height: 1;
  } 
  .form-label .optional {
    font-weight: 400;
    color: var(--text-muted);
    font-size: 11px;
  }
  
  .form-control {
    width: 100%;
    padding: 10px 14px;
    border-radius: var(--radius-sm);
    border: 1.5px solid rgba(139,0,0,0.12);
    background: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    color: var(--text-dark);
    transition: var(--transition);
    outline: none;
    -webkit-appearance: none;
    appearance: none;
  }
  .form-control:focus {
    border-color: var(--walnut-mid);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.08);
    background: var(--white);
  }
  .form-control:hover:not(:focus) { border-color: rgba(139,0,0,0.22); }
  .form-control::placeholder { color: var(--text-muted); opacity: 0.7; }
           
  .select-wrap { position: relative; }
  .select-wrap select { padding-right: 36px; cursor: pointer; }
  .select-wrap::after {
    content: '';
    position: absolute;
    right: 13px; top: 50%;
    transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
    transition: transform 0.2s;
  }
  .select-wrap:focus-within::after { border-top-color: var(--walnut-mid); }

  .upload-zone {
    border: 2px dashed rgba(139,0,0,0.18);
    border-radius: 12px;
    padding: 40px 24px;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    background: linear-gradient(135deg, #fff9f9, var(--white));
  }
  .upload-zone:hover, .upload-zone.drag-over {
    border-color: var(--walnut-mid);
    background: var(--cream);
    box-shadow: 0 4px 20px rgba(139,0,0,0.08);
  }
  .upload-zone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
  }
  .upload-icon {
    width: 56px; height: 56px;
    background: linear-gradient(135deg, rgba(192,21,42,0.08), rgba(245,166,35,0.06));
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
    transition: var(--transition);
  }
  .upload-zone:hover .upload-icon {
    background: linear-gradient(135deg, rgba(192,21,42,0.14), rgba(245,166,35,0.1));
    transform: translateY(-2px);
  }
  .upload-icon svg { width: 26px; height: 26px; color: var(--walnut-mid); }
  .upload-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: var(--text-dark); margin-bottom: 6px; }
  .upload-sub { font-size: 12.5px; color: var(--text-muted); line-height: 1.5; }
  .upload-sub strong { color: var(--walnut-mid); font-weight: 600; }
  .upload-formats {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 16px;
    flex-wrap: wrap;
  }
  .format-tag {
    padding: 3px 10px;
    border-radius: 20px;
    background: var(--cream-deep);
    border: 1px solid rgba(139,0,0,0.1);
    font-size: 11px;
    font-weight: 600;
    color: var(--text-mid);
    letter-spacing: 0.4px;
  }

  .file-preview {
    display: none;
    margin-top: 16px;
    padding: 12px 16px;
    border-radius: 10px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.08);
    align-items: center;
    gap: 12px;
  }
  .file-preview.show { display: flex; }
  .file-preview-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--walnut), var(--walnut-mid));
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .file-preview-icon svg { width: 16px; height: 16px; color: #fff; }
  .file-preview-info { flex: 1; min-width: 0; }
  .file-preview-name { font-size: 13px; font-weight: 600; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .file-preview-size { font-size: 11px; color: var(--text-muted); }
  .file-remove {
    width: 28px; height: 28px;
    border-radius: 7px;
    border: 1px solid rgba(139,0,0,0.12);
    background: var(--white);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: var(--text-muted);
    transition: var(--transition);
    flex-shrink: 0;
  }
  .file-remove:hover { background: var(--walnut-mid); color: #fff; border-color: var(--walnut-mid); }
  .file-remove svg { width: 13px; height: 13px; }

  .eval-section { margin-bottom: 28px; }
  .eval-section:last-child { margin-bottom: 0; }

  .eval-section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--cream), #fff8f8);
    border: 1px solid rgba(139,0,0,0.07);
    margin-bottom: 12px;
  }
  .eval-section-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
  .eval-section-title {
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-dark);
    letter-spacing: -0.1px;
    flex: 1;
  }
  .eval-section-weight {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    background: var(--white);
    border: 1px solid rgba(139,0,0,0.08);
    padding: 2px 8px;
    border-radius: 20px;
  }

  .criteria-table {
    width: 100%;
    border-collapse: collapse;
  }
  .criteria-table thead tr { background: transparent; }
  .criteria-table th {
    padding: 6px 12px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--text-muted);
    border-bottom: 1px solid rgba(139,0,0,0.06);
    text-align: left;
  }
  .criteria-table th:last-child { text-align: center; }
  .criteria-table th.score-col { text-align: center; width: 120px; }
  .criteria-table th.ko-col { text-align: center; width: 70px; }

  .criteria-table td {
    padding: 10px 12px;
    border-bottom: 1px solid rgba(139,0,0,0.04);
    vertical-align: middle;
  }
  .criteria-table tbody tr:last-child td { border-bottom: none; }
  .criteria-table tbody tr { transition: background 0.15s; }
  .criteria-table tbody tr:hover { background: rgba(255,245,245,0.7); }

  .criterion-label {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dark);
  }
  .criterion-desc {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 1px;
  }

  .score-select-wrap { position: relative; display: flex; justify-content: center; }
  .score-select {
    width: 90px;
    padding: 7px 28px 7px 12px;
    border-radius: 8px;
    border: 1.5px solid rgba(139,0,0,0.12);
    background: var(--white);
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: var(--text-dark);
    cursor: pointer;
    outline: none;
    -webkit-appearance: none;
    appearance: none;
    transition: var(--transition);
    text-align: center;
  }
  .score-select:focus {
    border-color: var(--walnut-mid);
    box-shadow: 0 0 0 3px rgba(192,21,42,0.08);
  }
  .score-select-wrap::after {
    content: '';
    position: absolute;
    right: 10px; top: 50%;
    transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid var(--text-muted);
    pointer-events: none;
  }

  .ko-wrap {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .ko-checkbox {
    position: relative;
    width: 32px; height: 32px;
    cursor: pointer;
  }
  .ko-checkbox input { display: none; }
  .ko-box {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1.5px solid rgba(139,0,0,0.15);
    background: var(--white);
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition);
    font-size: 11px;
    font-weight: 800;
    color: transparent;
    font-family: 'Syne', sans-serif;
  }
  .ko-checkbox input:checked + .ko-box {
    background: var(--walnut);
    border-color: var(--walnut);
    color: #fff;
    box-shadow: 0 2px 8px rgba(139,0,0,0.3);
  }
  .ko-box:hover { border-color: var(--walnut-mid); background: var(--cream); }

  textarea.form-control {
    resize: vertical;
    min-height: 110px;
    line-height: 1.6;
  }
  .char-count { font-size: 11px; color: var(--text-muted); text-align: right; margin-top: 4px; }

  .score-sidebar { position: sticky; top: calc(var(--topbar-h) + 24px); }

  .score-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    overflow: hidden;
    animation: fadeUp 0.5s 0.3s ease both;
  }

  .score-card-header {
    background: linear-gradient(135deg, var(--walnut) 0%, var(--walnut-mid) 100%);
    padding: 20px;
    position: relative;
    overflow: hidden;
  }
  .score-card-header::before {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 80px; height: 80px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
  }
  .score-card-header::after {
    content: '';
    position: absolute;
    bottom: -30px; left: -10px;
    width: 100px; height: 100px;
    background: rgba(245,166,35,0.1);
    border-radius: 50%;
  }
  .score-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.6);
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
  }
  .score-display {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 52px;
    color: #fff;
    letter-spacing: -2px;
    line-height: 1;
    position: relative;
    z-index: 1;
  }
  .score-display span {
    font-size: 20px;
    font-weight: 600;
    color: rgba(255,255,255,0.5);
    letter-spacing: 0;
  }
  .score-gold-bar {
    margin-top: 14px;
    height: 4px;
    border-radius: 2px;
    background: rgba(255,255,255,0.15);
    overflow: hidden;
    position: relative;
    z-index: 1;
  }
  .score-gold-bar-fill {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    border-radius: 2px;
    transition: width 0.8s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 0 8px rgba(245,166,35,0.5);
  }

  .score-breakdown { padding: 16px; display: flex; flex-direction: column; gap: 10px; }

  .breakdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 9px;
    background: var(--cream);
    border: 1px solid rgba(139,0,0,0.05);
    transition: var(--transition);
    cursor: default;
  }
  .breakdown-item:hover { background: var(--cream-deep); }
  .breakdown-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
  .breakdown-info { flex: 1; min-width: 0; }
  .breakdown-name { font-size: 11.5px; font-weight: 600; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .breakdown-weight { font-size: 10px; color: var(--text-muted); }
  .breakdown-score {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 15px;
    color: var(--text-dark);
    flex-shrink: 0;
  }
  .breakdown-score.na { color: var(--text-muted); font-size: 12px; font-weight: 500; font-family: 'DM Sans', sans-serif; }

  .score-ko-alert {
    margin: 0 16px 16px;
    padding: 12px 14px;
    border-radius: 9px;
    background: rgba(139,0,0,0.05);
    border: 1.5px solid rgba(139,0,0,0.2);
    display: flex;
    align-items: flex-start;
    gap: 10px;
    display: none;
  }
  .score-ko-alert.show {
    display: flex;
    background: linear-gradient(135deg, rgba(139,0,0,0.08), rgba(192,21,42,0.06));
    border-color: #C0152A;
    box-shadow: 0 0 0 3px rgba(192,21,42,0.05);
  }
  .ko-alert-icon { color: var(--walnut); flex-shrink: 0; margin-top: 1px; }
  .ko-alert-icon svg { width: 16px; height: 16px; }
  .ko-alert-text { font-size: 12px; color: #8B0000; font-weight: 700; line-height: 1.4; }

  .actions-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(139,0,0,0.05);
    padding: 20px;
    animation: fadeUp 0.5s 0.35s ease both;
  }

  .actions-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .actions-left { display: flex; align-items: center; gap: 8px; }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    border: none;
    text-decoration: none;
  }
  .btn svg { width: 15px; height: 15px; }

  .btn-draft {
    background: var(--white);
    color: var(--text-mid);
    border: 1.5px solid rgba(139,0,0,0.15);
  }
  .btn-draft:hover {
    background: var(--cream);
    border-color: rgba(139,0,0,0.25);
    color: var(--text-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(139,0,0,0.08);
  }

  .btn-cancel {
    background: transparent;
    color: var(--text-muted);
    border: 1.5px solid transparent;
  }
  .btn-cancel:hover { background: var(--cream); color: var(--text-mid); border-color: rgba(139,0,0,0.08); }

  .btn-submit {
    background: linear-gradient(135deg, var(--walnut) 0%, var(--walnut-mid) 100%);
    color: #fff;
    box-shadow: 0 4px 16px rgba(139,0,0,0.3);
  }
  .btn-submit:hover {
    background: linear-gradient(135deg, #700000, var(--walnut));
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(139,0,0,0.4);
  }
  .btn-submit:active { transform: translateY(0); }

  .form-progress {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    animation: fadeUp 0.4s 0.08s ease both;
  }
  .progress-step {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .progress-step-bar {
    height: 4px;
    border-radius: 2px;
    background: rgba(139,0,0,0.08);
    overflow: hidden;
  }
  .progress-step-bar-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 1s ease;
  }
  .progress-step.done .progress-step-bar-fill { background: var(--sage); width: 100%; }
  .progress-step.active .progress-step-bar-fill {
    background: linear-gradient(90deg, var(--walnut-mid), var(--gold));
    width: 60%;
  }
  .progress-step.todo .progress-step-bar-fill { width: 0%; }
  .progress-step-label { font-size: 10.5px; font-weight: 600; color: var(--text-muted); letter-spacing: 0.3px; }
  .progress-step.done .progress-step-label { color: var(--sage); }
  .progress-step.active .progress-step-label { color: var(--walnut-mid); }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes pulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(245,166,35,0.4); }
    50% { box-shadow: 0 0 0 6px rgba(245,166,35,0); }
  }

  @media (max-width: 1100px) {
    .eval-layout { grid-template-columns: 1fr; }
    .score-sidebar { position: static; }
  }
  @media (max-width: 960px) {
    :root { --sidebar-w: 260px; }
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .sidebar-overlay.open { display: block; }
    .topbar { left: 0; }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
  }
  @media (max-width: 700px) {
    .content { padding: 20px 16px; }
    .topbar { padding: 0 16px; }
    .topbar-profile-info { display: none; }
    .form-row { grid-template-columns: 1fr; }
    .form-row.three { grid-template-columns: 1fr; }
    .criteria-table { font-size: 12px; }
    .actions-inner { flex-direction: column; align-items: stretch; }
    .actions-left { justify-content: center; }
    .btn-submit { width: 100%; justify-content: center; }
    .form-progress { display: none; }
  }
</style>

@if(session('success'))
  <div class="alert success" style="margin-bottom:20px;padding:16px;border-radius:14px;background:rgba(122,140,114,0.14);color:#3f5a3d;">
    {{ session('success') }}
  </div>
@endif

@if($errors->any())
  <div class="alert errors" style="margin-bottom:20px;padding:16px;border-radius:14px;background:rgba(255,221,221,0.35);color:#7a1414;">
    <strong>There are some issues with your submission:</strong>
    <ul style="margin-top:8px; padding-left:18px;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('evaluations.store') }}" enctype="multipart/form-data">
  @csrf

  <div class="breadcrumb">
  <a href="#">Dashboard</a>
  <span class="breadcrumb-sep">›</span>
  <a href="#">Evaluations</a>
  <span class="breadcrumb-sep">›</span>
  <span class="breadcrumb-current">Create New</span>
</div>

<div class="page-header">
  <div class="page-header-left">
    <h2>
      <div class="header-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      </div>
      Create Evaluation
    </h2>
    <p>Evaluate a call and track advisor performance</p>
  </div>
</div>

<div class="form-progress">
  <div class="progress-step done">
    <div class="progress-step-bar"><div class="progress-step-bar-fill"></div></div>
    <div class="progress-step-label">General Info</div>
  </div>
  <div class="progress-step active">
    <div class="progress-step-bar"><div class="progress-step-bar-fill"></div></div>
    <div class="progress-step-label">Audio Upload</div>
  </div>
  <div class="progress-step todo">
    <div class="progress-step-bar"><div class="progress-step-bar-fill"></div></div>
    <div class="progress-step-label">Evaluation Grid</div>
  </div>
  <div class="progress-step todo">
    <div class="progress-step-bar"><div class="progress-step-bar-fill"></div></div>
    <div class="progress-step-label">Comments</div>
  </div>
</div>

<div class="eval-layout">
  <div class="form-stack">
      
    <div class="eval-card">
      <div class="card-header-strip">
        <div class="card-header-icon" style="background:rgba(139,0,0,0.08);color:var(--walnut);">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div class="card-header-text">
          <h3>General Information</h3>
          <p>Basic details about the call and advisor</p>
        </div>
    </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Advisor (Conseiller) <span class="required">*</span></label>
            <div class="select-wrap">
              <select name="conseiller_id" class="form-control" required>
                <option value="" disabled {{ old('conseiller_id') ? '' : 'selected' }}>Select an advisor…</option>
                @forelse($conseillers ?? [] as $conseiller)
                  <option value="{{ $conseiller->id }}" {{ old('conseiller_id') == $conseiller->id ? 'selected' : '' }}>
                    {{ $conseiller->name }}
                  </option>
                @empty
                  <option value="" disabled>No advisors available</option>
                @endforelse
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Type of Call <span class="required">*</span></label>
            <div class="select-wrap">
              <select name="type" class="form-control" required>
                <option value="" disabled selected>Select type…</option>
                <option value="entrant" {{ old('type') == 'entrant' ? 'selected' : '' }}>📥 Entrant (Inbound)</option>
                <option value="sortant" {{ old('type') == 'sortant' ? 'selected' : '' }}>📤 Sortant (Outbound)</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Date & Time <span class="required">*</span></label>
            <input type="datetime-local" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d\TH:i')) }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Reference <span class="optional">(optional)</span></label>
            <input type="text" name="reference" class="form-control" placeholder="e.g. CALL-2026-04821" value="{{ old('reference') }}">
          </div>
        </div>
        <div class="form-row full">
          <div class="form-group">
            <label class="form-label">Campaign / Team <span class="optional">(optional)</span></label>
            <div class="select-wrap">
              <select class="form-control">
                <option value="" disabled selected>Select campaign…</option>
                <option>Team A – Retention</option>
                <option>Team B – Acquisition</option>
                <option>Team C – Support</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="eval-card">
      <div class="card-header-strip">
        <div class="card-header-icon" style="background:rgba(245,166,35,0.1);color:#C07A00;">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
        </div>
        <div class="card-header-text">
          <h3>Audio Recording</h3>
          <p>Upload the call recording for evaluation</p>
        </div>
      </div>
      <div class="card-body">
        <div class="upload-zone" id="uploadZone">
          <input type="file" name="audio" accept="audio/*,.mp3,.wav,.ogg,.m4a,.aac,.wma,.flac" id="audioInput" onchange="handleFile(this)">
          <div class="upload-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
          </div>
          <div class="upload-title">Drop your audio file here</div>
          <div class="upload-sub">or <strong>click to browse</strong> from your computer</div>
          <div class="upload-formats">
            <span class="format-tag">MP3</span>
            <span class="format-tag">WAV</span>
            <span class="format-tag">M4A</span>
            <span class="format-tag">OGG</span>
            <span class="format-tag">AAC</span>
            <span class="format-tag">FLAC</span>
          </div>
        </div>

        <div class="file-preview" id="filePreview">
          <div class="file-preview-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
          </div>
          <div class="file-preview-info">
            <div class="file-preview-name" id="fileName">audio-call.mp3</div>
            <div class="file-preview-size" id="fileSize">—</div>
          </div>
          <button class="file-remove" onclick="removeFile()" title="Remove file">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      </div>
    </div>

    <div class="eval-card">
      <div class="card-header-strip">
        <div class="card-header-icon" style="background:rgba(217,56,72,0.09);color:var(--walnut-light);">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
        <div class="card-header-text">
          <h3>Evaluation Grid</h3>
          <p>Rate each criterion from 0 to 5 — check KO for critical failures</p>
        </div>
      </div>
      <div class="card-body">

        <div style="margin-bottom:20px;padding:14px;border-radius:10px;background:linear-gradient(135deg,rgba(122,140,114,0.08),rgba(245,166,35,0.04));border:1px solid rgba(122,140,114,0.12);">
          <div style="display:flex;gap:10px;align-items:flex-start;">
            <svg style="width:18px;height:18px;color:#7A8C72;flex-shrink:0;margin-top:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--text-dark);margin-bottom:4px;">🚫 KO Criteria (Knockout Criteria)</div>
              <div style="font-size:11.5px;color:var(--text-mid);line-height:1.5;">Check "KO" for any criterion that is critically non-compliant or a regulatory violation. If any KO is marked, the evaluation result will be automatically marked as <strong>FAILED</strong> regardless of the total score.</div>
            </div>
          </div>
        </div>

        <div class="eval-section">
          <div class="eval-section-header">
            <div class="eval-section-dot" style="background:#C0152A;"></div>
            <div class="eval-section-title">Communication Quality</div>
            <div class="eval-section-weight">30 pts</div>
          </div>
          <table class="criteria-table">
            <thead>
              <tr>
                <th>Criterion</th>
                <th class="score-col">Score (0-5)</th>
                <th class="ko-col">KO</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="criterion-label">Greeting & Introduction</div>
                  <div class="criterion-desc">Proper opening, company identification, name stated</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Tone & Clarity</div>
                  <div class="criterion-desc">Voice tone, articulation, speaking pace</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Active Listening</div>
                  <div class="criterion-desc">Acknowledgment, reformulation, no interruption</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Professional Closing</div>
                  <div class="criterion-desc">Summary, next steps communicated, proper farewell</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="eval-section">
          <div class="eval-section-header">
            <div class="eval-section-dot" style="background:#F5A623;"></div>
            <div class="eval-section-title">Technical Mastery</div>
            <div class="eval-section-weight">25 pts</div>
          </div>
          <table class="criteria-table">
            <thead>
              <tr>
                <th>Criterion</th>
                <th class="score-col">Score (0–5)</th>
                <th class="ko-col">KO</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="criterion-label">Product Knowledge</div>
                  <div class="criterion-desc">Accuracy of information provided to client</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Problem Resolution</div>
                  <div class="criterion-desc">Effectiveness in resolving customer issue</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Script Compliance</div>
                  <div class="criterion-desc">Adherence to approved call script and procedures</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="eval-section">
          <div class="eval-section-header">
            <div class="eval-section-dot" style="background:#7A8C72;"></div>
            <div class="eval-section-title">Client Experience</div>
            <div class="eval-section-weight">25 pts</div>
          </div>
          <table class="criteria-table">
            <thead>
              <tr>
                <th>Criterion</th>
                <th class="score-col">Score (0–5)</th>
                <th class="ko-col">KO</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="criterion-label">Empathy & Patience</div>
                  <div class="criterion-desc">Understanding client emotions, no signs of frustration</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Wait Time Handling</div>
                  <div class="criterion-desc">Proper hold management, updates communicated</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Customer Satisfaction Probe</div>
                  <div class="criterion-desc">Checked if client needs were fully addressed</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="eval-section">
          <div class="eval-section-header">
            <div class="eval-section-dot" style="background:#6B3040;"></div>
            <div class="eval-section-title">Compliance & Regulations</div>
            <div class="eval-section-weight">20 pts</div>
          </div>
          <table class="criteria-table">
            <thead>
              <tr>
                <th>Criterion</th>
                <th class="score-col">Score (0–5)</th>
                <th class="ko-col">KO</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="criterion-label">Data Privacy (GDPR)</div>
                  <div class="criterion-desc">No unauthorized data disclosure, consent mentioned</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Authentication Protocol</div>
                  <div class="criterion-desc">Identity verification completed per procedure</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
              <tr>
                <td>
                  <div class="criterion-label">Call Recording Notice</div>
                  <div class="criterion-desc">Client informed that call is being recorded</div>
                </td>
                <td><div class="score-select-wrap"><select class="score-select" onchange="updateScore()"><option value="">—</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></div></td>
                <td><div class="ko-wrap"><label class="ko-checkbox"><input type="checkbox" onchange="updateKO(this)"><div class="ko-box">KO</div></label></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="eval-card">
      <div class="card-header-strip">
        <div class="card-header-icon" style="background:rgba(122,140,114,0.12);color:var(--sage);">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <div class="card-header-text">
          <h3>Manager Feedback</h3>
          <p>Add qualitative comments to support the evaluation score</p>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label">
            Evaluation Notes <span class="optional">(optional but recommended)</span>
          </label>
          <textarea class="form-control" id="commentBox" placeholder="Write your evaluation notes here — highlight strengths, areas for improvement, and any specific observations from the call…" rows="5" onkeyup="countChars()"></textarea>
          <div class="char-count"><span id="charCount">0</span> / 1000 characters</div>
        </div>
      </div>
    </div>
    {{-- ─── Signature Card ─────────────────────────────────────────── --}}
<div class="eval-card">
  <div class="card-header-strip">
    <div class="card-header-icon" style="background:rgba(107,48,64,0.1);color:var(--text-mid);">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
      </svg>
    </div>
    <div class="card-header-text">
      <h3>Evaluator Signature</h3>
      <p>Draw your signature to confirm and validate this evaluation</p>
    </div>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label class="form-label">
        Signature <span class="optional">(draw with mouse or finger)</span>
      </label>

      <div class="signature-outer" id="sigOuter">
        <div class="sig-topbar">
          <div class="sig-hint">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;color:var(--walnut-mid);flex-shrink:0;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Sign in the area below — use your mouse or touch
          </div>
          <div class="sig-actions">
            <button type="button" class="sig-btn" id="clearBtn" onclick="clearSig()">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Clear
            </button>
            <button type="button" class="sig-btn sig-btn-primary" onclick="saveSig()">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Confirm
            </button>
          </div>
        </div>

        <canvas id="signatureCanvas"></canvas>

        <div class="sig-status">
          <div class="sig-status-label">
            <div class="sig-status-dot" id="sigDot"></div>
            <span id="sigStatusText">Waiting for signature…</span>
          </div>
          <div class="sig-strokes" id="sigStrokes"></div>
        </div>
      </div>

      <input type="hidden" name="signature" id="signatureData">
      <input type="hidden" name="score" id="scoreData">
      <input type="hidden" name="has_ko" id="hasKoData" value="0">
    </div>
  </div>
</div>

    <div class="actions-card">
      <div class="actions-inner">
        <div class="actions-left">
          <button type="button" class="btn btn-cancel" onclick="window.history.back()">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Cancel
          </button>
          <button type="submit" name="status" value="draft" class="btn btn-draft">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Save as Draft
          </button>
        </div>
        <button type="submit" name="status" value="completed" class="btn btn-submit">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Submit Evaluation
        </button>
      </div>
    </div>
  </div>

  <div class="score-sidebar">
    <div class="score-card">
      <div class="score-card-header">
        <div class="score-label">Total Score</div>
        <div class="score-display" id="totalScore">—<span> / 100</span></div>
        <div class="score-gold-bar">
          <div class="score-gold-bar-fill" id="scoreBar"></div>
        </div>
      </div>

      <div class="score-ko-alert" id="koAlert">
        <div class="ko-alert-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="ko-alert-text" id="koAlertText">1 KO criterion flagged — evaluation may be invalidated</div>
      </div>

      <div class="score-breakdown">
        <div style="font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;">Section Breakdown</div>

        <div class="breakdown-item">
          <div class="breakdown-dot" style="background:#C0152A;"></div>
          <div class="breakdown-info">
            <div class="breakdown-name">Communication</div>
            <div class="breakdown-weight">Max 30 pts</div>
          </div>
          <div class="breakdown-score na" id="score-s1">—</div>
        </div>

        <div class="breakdown-item">
          <div class="breakdown-dot" style="background:#F5A623;"></div>
          <div class="breakdown-info">
            <div class="breakdown-name">Technical</div>
            <div class="breakdown-weight">Max 25 pts</div>
          </div>
          <div class="breakdown-score na" id="score-s2">—</div>
        </div>

        <div class="breakdown-item">
          <div class="breakdown-dot" style="background:#7A8C72;"></div>
          <div class="breakdown-info">
            <div class="breakdown-name">Client Experience</div>
            <div class="breakdown-weight">Max 25 pts</div>
          </div>
          <div class="breakdown-score na" id="score-s3">—</div>
        </div>

        <div class="breakdown-item">
          <div class="breakdown-dot" style="background:#6B3040;"></div>
          <div class="breakdown-info">
            <div class="breakdown-name">Compliance</div>
            <div class="breakdown-weight">Max 20 pts</div>
          </div>
          <div class="breakdown-score na" id="score-s4">—</div>
        </div>
      </div>

      <div style="padding:0 16px 16px;">
        <div style="font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px;">Completion</div>
        <div style="height:6px;border-radius:3px;background:rgba(139,0,0,0.08);overflow:hidden;">
          <div id="completionBar" style="height:100%;width:0%;background:linear-gradient(90deg,var(--sage),#9cb394);border-radius:3px;transition:width 0.6s ease;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:5px;">
          <div style="font-size:11px;color:var(--text-muted);" id="completionText">0 of 13 criteria rated</div>
          <div style="font-size:11px;font-weight:700;color:var(--sage);" id="completionPct">0%</div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
@endsection

@push('scripts')
<script>
  function handleFile(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    document.getElementById('fileName').textContent = file.name;
    const size = file.size < 1024*1024
      ? (file.size/1024).toFixed(1) + ' KB'
      : (file.size/(1024*1024)).toFixed(1) + ' MB';
    document.getElementById('fileSize').textContent = size;
    document.getElementById('filePreview').classList.add('show');
  }

  function removeFile() {
    document.getElementById('audioInput').value = '';
    document.getElementById('filePreview').classList.remove('show');
  }

  const zone = document.getElementById('uploadZone');
  if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
      e.preventDefault();
      zone.classList.remove('drag-over');
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        document.getElementById('audioInput').files = files;
        handleFile(document.getElementById('audioInput'));
      }
    });
  }

  const sectionConfig = [
    { id: 'score-s1', rows: [0,1,2,3], max: 30 },
    { id: 'score-s2', rows: [4,5,6], max: 25 },
    { id: 'score-s3', rows: [7,8,9], max: 25 },
    { id: 'score-s4', rows: [10,11,12], max: 20 },
  ];

  function updateScore() {
    const selects = document.querySelectorAll('.score-select');
    const allSelects = Array.from(selects);
    let totalPts = 0;
    let ratedCount = 0;

    sectionConfig.forEach(sec => {
      let secPts = 0;
      let secRated = 0;
      sec.rows.forEach(i => {
        const sel = allSelects[i];
        const maxPerCrit = sec.max / sec.rows.length;
        if (sel && sel.value !== '') {
          secPts += (parseInt(sel.value) / 5) * maxPerCrit;
          secRated++;
          ratedCount++;
        }
      });
      const el = document.getElementById(sec.id);
      if (secRated > 0) {
        el.textContent = secPts.toFixed(1);
        el.classList.remove('na');
        totalPts += secPts;
      } else {
        el.textContent = '—';
        el.classList.add('na');
      }
    });

    const scoreEl = document.getElementById('totalScore');
    const barEl = document.getElementById('scoreBar');
    if (ratedCount > 0) {
      const total = totalPts.toFixed(1);
      scoreEl.innerHTML = total + '<span> / 100</span>';
      barEl.style.width = Math.min((totalPts / 100) * 100, 100) + '%';
      document.getElementById('scoreData').value = total;
    } else {
      scoreEl.innerHTML = '—<span> / 100</span>';
      barEl.style.width = '0%';
      document.getElementById('scoreData').value = '';
    }

    const totalCriteria = allSelects.length;
    const pct = Math.round((ratedCount / totalCriteria) * 100);
    document.getElementById('completionBar').style.width = pct + '%';
    document.getElementById('completionText').textContent = ratedCount + ' of ' + totalCriteria + ' criteria rated';
    document.getElementById('completionPct').textContent = pct + '%';
  }

  let koCount = 0;
  function updateKO() {
    koCount = document.querySelectorAll('.ko-checkbox input:checked').length;
    const alertEl = document.getElementById('koAlert');
    const textEl = document.getElementById('koAlertText');
    const scoreEl = document.getElementById('totalScore');
    const hasKoInput = document.getElementById('hasKoData');
    const submitBtn = document.querySelector('button[value="completed"]');
    
    if (koCount > 0) {
      alertEl.classList.add('show');
      textEl.textContent = koCount + ' KO criterion' + (koCount > 1 ? 'a' : '') + ' flagged — evaluation result FAILED';
      scoreEl.innerHTML = '<span style="color:#C0152A;font-weight:800;">KO</span><span style="font-size:14px;color:var(--text-muted);"> / FAILED</span>';
      document.getElementById('scoreBar').style.width = '100%';
      document.getElementById('scoreBar').style.background = 'linear-gradient(90deg, #8B0000, #C0152A)';
      hasKoInput.value = '1';
      document.getElementById('scoreData').value = '0';
      submitBtn.disabled = false;
      submitBtn.style.opacity = '1';
      submitBtn.style.cursor = 'pointer';
    } else {
      alertEl.classList.remove('show');
      hasKoInput.value = '0';
      updateScore();
      submitBtn.disabled = false;
      submitBtn.style.opacity = '1';
      submitBtn.style.cursor = 'pointer';
    }
  }

  function countChars() {
    const box = document.getElementById('commentBox');
    const count = box.value.length;
    document.getElementById('charCount').textContent = count;
    if (count > 1000) box.value = box.value.substring(0, 1000);
  }

// ── Signature pad ──
(function () {
  const canvas = document.getElementById('signatureCanvas');
  const ctx = canvas.getContext('2d');
  const outer = document.getElementById('sigOuter');
  const dot = document.getElementById('sigDot');
  const statusText = document.getElementById('sigStatusText');
  const strokesEl = document.getElementById('sigStrokes');
  let drawing = false, hasSig = false, strokes = 0;

  function resize() {
    const dpr = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
    ctx.strokeStyle = '#8B0000';
    ctx.lineWidth = 2.2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
  }

  function getPos(e) {
    const r = canvas.getBoundingClientRect();
    if (e.touches) return { x: e.touches[0].clientX - r.left, y: e.touches[0].clientY - r.top };
    return { x: e.clientX - r.left, y: e.clientY - r.top };
  }

  function updateStatus() {
    if (hasSig) {
      dot.classList.add('active');
      statusText.textContent = 'Signature captured';
      strokesEl.textContent = strokes + ' stroke' + (strokes !== 1 ? 's' : '');
    }
  }
  canvas.addEventListener('mousedown', e => {
    drawing = true; strokes++;
    const p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y);
    outer.classList.add('signing');
  });


  canvas.addEventListener('mousemove', e => {
    if (!drawing) return;
    const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke();
    hasSig = true; updateStatus();
  });

  canvas.addEventListener('mouseup', () => { drawing = false; });
  canvas.addEventListener('mouseleave', () => { drawing = false; });

  canvas.addEventListener('touchstart', e => {
    e.preventDefault(); drawing = true; strokes++;
    const p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y);
    outer.classList.add('signing');

  }, { passive: false });

  canvas.addEventListener('touchmove', e => {
    e.preventDefault();
    if (!drawing) return;
    const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke();
    hasSig = true; updateStatus();
  }, { passive: false });
  canvas.addEventListener('touchend', () => { drawing = false; });

  window.clearSig = function () {
    const r = canvas.getBoundingClientRect();
    ctx.clearRect(0, 0, r.width, r.height);
    hasSig = false; strokes = 0;
    dot.classList.remove('active');
    statusText.textContent = 'Waiting for signature…';
    strokesEl.textContent = '';
    outer.classList.remove('signing');
    document.getElementById('signatureData').value = '';
  };

  window.saveSig = function () {
    if (!hasSig) { alert('Please draw your signature first.'); return; }
    document.getElementById('signatureData').value = canvas.toDataURL('image/png');
    statusText.textContent = '✓ Signature confirmed and saved';
    strokesEl.textContent = '';
  };
  
                         
  resize();         
  window.addEventListener('resize', resize);
})();
</script>

@endpush
