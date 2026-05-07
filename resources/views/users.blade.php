@extends('layouts.app')

@section('title', 'Créer un utilisateur')

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
    padding: 0 24px;
  }

  .form-body {
    padding: 24px 24px 24px;
  }
 
  .form-header-gap {
    margin-bottom: 24px;
  }
 
  /* ─── Field groups ─── */
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
 
  /* Laravel validation error */
  .invalid-feedback {
    font-size: 11.5px;
    color: var(--walnut-light);
    margin-top: 2px;
  }
 
  /* Input with icon */
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
 
  /* Password toggle */
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
 
  /* Divider */
  .form-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(139,0,0,0.08), transparent);
    margin: 4px 0;
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
  }
  .section-label::after { content: ''; flex: 1; height: 1px; background: rgba(139,0,0,0.07); }
 
  /* ─── Role cards ─── */
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
 
  /* ─── Avatar preview ─── */
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
 
  /* ─── Form actions ─── */
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
 
  /* ─── Sidebar card extras ─── */
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
 
  /* ─── Responsive ─── */
  @media (max-width: 1100px) { .form-grid { grid-template-columns: 1fr; } }
  @media (max-width: 640px) {
    .field-row { grid-template-columns: 1fr; }
    .role-cards { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; gap: 14px; align-items: flex-start; }
  }
</style>
 
{{-- Page header --}}
<div class="page-header" style="animation: fadeUp 0.4s ease both;">
  <div class="page-header-left">
    <h2>Nouveau compte utilisateur</h2>
    <p>Remplissez les informations ci-dessous pour créer un nouvel utilisateur.</p>
  </div>
  <a href="#" class="btn-back">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Retour aux utilisateurs
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success" style="margin-bottom: 20px; padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
    {{ session('success') }}
  </div>
@endif

<div class="form-grid">
 
  {{-- ── Main form card ── --}}
  <div class="card" style="animation: fadeUp 0.45s 0.1s ease both;">
    <div class="card-header">
      <div>
        <div class="card-title">Informations utilisateur</div>
        <div class="card-subtitle">Détails du compte et rôle d'accès</div>
      </div>
      <div class="card-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </div>
    </div>
 
    <form  id="createUserForm" action="/create-user" method="POST" >
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
              First Name <span class="required">*</span>
            </label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <input
                class="field-input @error('first_name') is-invalid @enderror"
                type="text"
                id="first_name"
                name="first_name"
                value="{{ old('first_name') }}"
                placeholder="e.g. Karim"
                autocomplete="given-name"
                required
              >
            </div>
            @error('first_name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
 
          <div class="field">
            <label class="field-label" for="last_name">
              Last Name <span class="required">*</span>
            </label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <input
                class="field-input @error('last_name') is-invalid @enderror"
                type="text"
                id="last_name"
                name="last_name"
                value="{{ old('last_name') }}"
                placeholder="e.g. Mansouri"
                autocomplete="family-name"
                required
              >
            </div>
            @error('last_name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
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
            <input
              class="field-input @error('email') is-invalid @enderror"
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              placeholder="user@evalcall.com"
              autocomplete="email"
              required
            >
          </div>
          <span class="field-hint">Ceci sera utilisé comme identifiant de connexion.</span>
          @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
          @enderror
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
              <input
                class="field-input @error('password') is-invalid @enderror"
                type="password"
                id="password"
                name="password"
                placeholder="8 caractères min."
                autocomplete="new-password"
                required
                style="padding-right:44px"
              >
              <svg class="pass-toggle" onclick="togglePass('password', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </div>
            @error('password')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
 
          <div class="field">
            <label class="field-label" for="password_confirmation">
              Confirmer le mot de passe <span class="required">*</span>
            </label>
            <div class="field-input-wrap">
              <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
              <input
                class="field-input"
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Répéter le mot de passe"
                autocomplete="new-password"
                required
                style="padding-right:44px"
              >
              <svg class="pass-toggle" onclick="togglePass('password_confirmation', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </div>
          </div>
        </div>
 
        <div class="form-divider"></div>
        <div class="section-label">Rôle et permissions</div>
 
        {{-- Role cards --}}
        @error('role')
          <span class="invalid-feedback" style="display:block">{{ $message }}</span>
        @enderror
 
        <div class="role-cards">
 
          <label class="role-card">
            <input type="radio" name="role" value="conseiller"
              {{ old('role', 'conseiller') === 'conseiller' ? 'checked' : '' }}>
            <div class="role-card-inner">
              <div class="role-check">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div class="role-icon-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div class="role-name">Conseiller</div>
              <div class="role-desc">Voir ses évaluations, signer les formulaires</div>
            </div>
          </label>
 
          <label class="role-card">
            <input type="radio" name="role" value="manager"
              {{ old('role') === 'manager' ? 'checked' : '' }}>
            <div class="role-card-inner">
              <div class="role-check">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div class="role-icon-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="role-name">Manager</div>
              <div class="role-desc">Gérer les évaluations de l'équipe et les rapports</div>
            </div>
          </label>
 
          <label class="role-card">
            <input type="radio" name="role" value="admin"
              {{ old('role') === 'admin' ? 'checked' : '' }}>
            <div class="role-card-inner">
              <div class="role-check">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <div class="role-icon-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <div class="role-name">Admin</div>
              <div class="role-desc">Accès complet au système et configuration</div>
            </div>
          </label>
 
        </div>
      </div>{{-- /form-body --}}
 
      {{-- Actions --}}
      <div class="form-actions">
        <a href="#" class="btn-cancel">Annuler</a>
        <button type="submit" class="btn-submit">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
          </svg>
          Créer l'utilisateur
        </button>
      </div>
 
    </form>
  </div>{{-- /card --}}
 
  {{-- ── Sidebar info card ── --}}
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
        <div>
          <div class="rgi-label">Conseiller</div>
          <div class="rgi-desc">View & sign own evaluations</div>
        </div>
      </div>
      <div class="role-guide-item">
        <div class="rgi-dot" style="background:var(--gold)"></div>
        <div>
          <div class="rgi-label">Manager</div>
          <div class="rgi-desc">Create evals, manage team & reports</div>
        </div>
      </div>
      <div class="role-guide-item">
        <div class="rgi-dot" style="background:var(--walnut-mid)"></div>
        <div>
          <div class="rgi-label">Admin</div>
          <div class="rgi-desc">Full access, user management & config</div>
        </div>
      </div>
    </div>
 
    <div class="card-header" style="border-top:1px solid rgba(139,0,0,0.05)">
      <div>
        <div class="card-title">Conseils</div>
        <div class="card-subtitle">Bonnes pratiques</div>
      </div>
    </div>
    <div class="info-list">
      <div class="info-item">
        <div class="info-item-icon" style="background:rgba(245,166,35,0.1)">💡</div>
        <div class="info-item-text">
          <strong>Strong Password</strong>
          <span>Use at least 8 characters with uppercase, number, and symbol.</span>
        </div>
      </div>
      <div class="info-item">
        <div class="info-item-icon" style="background:rgba(122,140,114,0.1)">✉️</div>
        <div class="info-item-text">
          <strong>Email = Login</strong>
          <span>The user will log in using this email address.</span>
        </div>
      </div>
      <div class="info-item">
        <div class="info-item-icon" style="background:rgba(192,21,42,0.08)">🔐</div>
        <div class="info-item-text">
          <strong>Least Privilege</strong>
          <span>Assign the minimum role needed for the user's responsibilities.</span>
        </div>
      </div>
    </div>
  </div>
 
</div>{{-- /form-grid --}}
 
@push('scripts')
<script>
  /* ── Avatar live preview ── */
  const firstNameEl = document.getElementById('first_name');
  const lastNameEl  = document.getElementById('last_name');
  const avatarEl    = document.getElementById('avatarPreview');
  const previewName = document.getElementById('previewName');
  const previewRole = document.getElementById('previewRole');
 
  function updatePreview() {
    const fn = firstNameEl.value.trim();
    const ln = lastNameEl.value.trim();
    avatarEl.textContent = ((fn[0] || '') + (ln[0] || '')).toUpperCase() || '?';
    previewName.textContent = (fn || ln) ? [fn, ln].filter(Boolean).join(' ') : 'Nouvel utilisateur';
  }
  firstNameEl.addEventListener('input', updatePreview);
  lastNameEl.addEventListener('input',  updatePreview);
 
  document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', function () {
      const labels = { conseiller: 'Conseiller', manager: 'Manager', admin: 'Admin' };
      previewRole.textContent = labels[this.value] ?? 'Rôle non sélectionné';
    });
  });
 
  /* ── Password show/hide ── */
  function togglePass(inputId, icon) {
    const inp  = document.getElementById(inputId);
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    icon.style.color = show ? 'var(--walnut-mid)' : '';
  }
 
  /* ── Restore avatar/role on validation fail (old values) ── */
  updatePreview();
  const checkedRole = document.querySelector('input[name="role"]:checked');
  if (checkedRole) {
    const labels = { conseiller: 'Conseiller', manager: 'Manager', admin: 'Admin' };
    previewRole.textContent = labels[checkedRole.value] ?? 'Rôle non sélectionné';
  }
</script>
@endpush

@endsection