<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EvalCall — Connexion</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --walnut:     #8B0000;
    --walnut-mid: #C0152A;
    --walnut-lt:  #D93848;
    --cream:      #FFF5F5;
    --cream-deep: #140709;
    --gold:       #F5A623;
    --gold-lt:    #F7BC54;
    --sage:       #7A8C72;
    --bg:         #FAFAFA;
    --white:      #FFFFFF;
    --text-dark:  #1A0A0C;
    --text-mid:   #6B3040;
    --text-muted: #9C7078;
    --border:     rgba(192,21,42,0.13);
    --shadow-sm:  0 2px 12px rgba(139,0,0,0.08);
    --shadow-md:  0 8px 40px rgba(139,0,0,0.12);
    --shadow-lg:  0 20px 60px rgba(139,0,0,0.16);
    --radius:     14px;
    --radius-sm:  8px;
    --radius-xl:  22px;
  }

  html, body {
    height: 100%;
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text-dark);
    -webkit-font-smoothing: antialiased;
  }

  /* ── Layout ── */
  .page {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 100vh;
  }

  /* ── LEFT PANEL ── */
  .brand-panel {
    position: relative;
    background: var(--walnut);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 48px 56px;
    overflow: hidden;
    isolation: isolate;
  }

  /* Warm wood-grain texture via layered SVG patterns */
  .brand-panel::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 80% 60% at 20% 80%, rgba(245,166,35,0.15) 0%, transparent 60%),
      radial-gradient(ellipse 60% 80% at 85% 10%, rgba(180,30,50,0.35) 0%, transparent 55%),
      linear-gradient(160deg, #6B0010 0%, #C0152A 55%, #9B0F22 100%);
    z-index: -2;
  }

  /* Subtle grain overlay */
  .brand-panel::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    background-size: 180px 180px;
    z-index: -1;
    opacity: 0.6;
  }

  /* Decorative arc lines */
  .brand-arcs {
    position: absolute;
    right: -80px;
    top: 50%;
    transform: translateY(-50%);
    width: 320px;
    height: 320px;
    z-index: -1;
    opacity: 0.08;
  }

  .brand-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    animation: fadeUp 0.6s ease both;
  }

  .logo-mark {
    width: 40px;
    height: 40px;
    background: var(--gold);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .logo-mark svg {
    width: 22px;
    height: 22px;
    fill: var(--walnut);
  }

  .logo-text {
    font-family: 'DM Serif Display', serif;
    font-size: 22px;
    color: var(--cream);
    letter-spacing: 0.02em;
  }

  .brand-center {
    animation: fadeUp 0.7s 0.1s ease both;
  }

  .brand-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 20px;
  }

  .brand-headline {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(30px, 3.5vw, 42px);
    line-height: 1.18;
    color: var(--cream);
    margin-bottom: 20px;
  }

  .brand-headline em {
    font-style: italic;
    color: var(--gold-lt);
  }

  .brand-sub {
    font-size: 15px;
    line-height: 1.7;
    color: rgba(245,240,232,0.65);
    max-width: 340px;
    font-weight: 300;
  }

  /* Stat cards */
  .brand-stats {
    display: flex;
    gap: 16px;
    margin-top: 40px;
    animation: fadeUp 0.7s 0.15s ease both;
  }

  .stat-card {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(245,240,232,0.1);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    flex: 1;
    backdrop-filter: blur(4px);
  }

  .stat-value {
    font-family: 'DM Serif Display', serif;
    font-size: 26px;
    color: var(--cream);
    line-height: 1;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 11px;
    color: rgba(245,240,232,0.5);
    letter-spacing: 0.06em;
    font-weight: 300;
  }

  .brand-footer {
    font-size: 12px;
    color: rgba(245,240,232,0.3);
    animation: fadeUp 0.7s 0.2s ease both;
  }

  /* ── RIGHT PANEL ── */
  .form-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: var(--bg);
    animation: fadeIn 0.5s ease both;
  }

  .form-card {
    width: 100%;
    max-width: 420px;
  }

  .form-header {
    margin-bottom: 36px;
    animation: fadeUp 0.5s 0.05s ease both;
  }

  .form-welcome {
    font-size: 13px;
    color: var(--text-muted);
    font-weight: 400;
    margin-bottom: 6px;
    letter-spacing: 0.01em;
  }

  .form-title {
    font-family: 'DM Serif Display', serif;
    font-size: 30px;
    color: var(--text-dark);
    line-height: 1.2;
    margin-bottom: 6px;
  }

  .form-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 300;
  }

  /* Divider with text */
  .form-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
    animation: fadeUp 0.5s 0.1s ease both;
  }

  .divider-line {
    flex: 1;
    height: 1px;
    background: var(--border);
  }

  .divider-text {
    font-size: 11px;
    color: var(--text-muted);
    letter-spacing: 0.12em;
    text-transform: uppercase;
  }

  /* Form fields */
  .field-group {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 20px;
    animation: fadeUp 0.5s 0.12s ease both;
  }

  .field {
    position: relative;
  }

  .field-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-mid);
    margin-bottom: 7px;
    letter-spacing: 0.04em;
  }

  .field-input-wrap {
    position: relative;
  }

  .field-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    stroke: var(--text-muted);
    stroke-width: 1.6;
    fill: none;
    transition: stroke 0.2s;
    pointer-events: none;
  }

  .field input {
    width: 100%;
    height: 48px;
    padding: 0 14px 0 42px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-size: 14.5px;
    color: var(--text-dark);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    -webkit-appearance: none;
  }

  .field input::placeholder {
    color: var(--text-muted);
    font-weight: 300;
  }

  .field input:hover {
    border-color: rgba(90,62,43,0.25);
  }

  .field input:focus {
    border-color: var(--walnut-mid);
    box-shadow: 0 0 0 3.5px rgba(90,62,43,0.10);
  }

  .field input:focus + .field-icon,
  .field input:focus ~ .field-icon {
    stroke: var(--walnut-mid);
  }

  .field-input-wrap:focus-within .field-icon {
    stroke: var(--walnut-mid);
  }

  /* Password toggle */
  .pw-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: var(--text-muted);
    transition: color 0.2s;
    display: flex;
    align-items: center;
  }

  .pw-toggle:hover { color: var(--walnut-mid); }
  .pw-toggle svg { width: 17px; height: 17px; stroke: currentColor; stroke-width: 1.6; fill: none; }

  /* Remember + Forgot row */
  .form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    animation: fadeUp 0.5s 0.15s ease both;
  }

  .checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 13px;
    color: var(--text-mid);
    user-select: none;
  }

  .checkbox-label input[type="checkbox"] {
    -webkit-appearance: none;
    appearance: none;
    width: 17px;
    height: 17px;
    border: 1.5px solid var(--border);
    border-radius: 4px;
    background: var(--white);
    cursor: pointer;
    position: relative;
    transition: all 0.2s;
    flex-shrink: 0;
  }

  .checkbox-label input[type="checkbox"]:checked {
    background: var(--walnut-mid);
    border-color: var(--walnut-mid);
  }

  .checkbox-label input[type="checkbox"]:checked::after {
    content: '';
    position: absolute;
    left: 4px;
    top: 1px;
    width: 6px;
    height: 10px;
    border: 2px solid #fff;
    border-top: none;
    border-left: none;
    transform: rotate(43deg);
  }

  .forgot-link {
    font-size: 13px;
    color: var(--walnut-mid);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
  }

  .forgot-link:hover { color: var(--walnut); text-decoration: underline; }

  /* Error message */
  .error-box {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    width: min(100%, 360px);
    align-items: stretch;
    background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
    border: 1.5px solid #B91C1C;
    border-radius: var(--radius);
    padding: 14px 16px 10px;
    font-size: 13px;
    color: #FEE2E2;
    box-shadow: 0 18px 32px rgba(139, 0, 0, 0.24);
    backdrop-filter: blur(10px);
    z-index: 9999;
    overflow: hidden;
    opacity: 0;
    transform: translateY(-20px) translateX(20px) scale(0.97);
    transition: opacity 0.25s ease, transform 0.25s ease;
  }

  .error-box.visible {
    display: flex;
    opacity: 1;
    transform: translateY(0) translateX(0) scale(1);
  }

  .error-box.hide {
    opacity: 0;
    transform: translateY(-16px) translateX(16px) scale(0.98);
  }

  .error-box::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, rgba(255,255,255,0.15) 0%, transparent 55%);
    border-radius: var(--radius);
    pointer-events: none;
  }

  .error-box-content {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    position: relative;
    z-index: 1;
  }

  .error-icon {
    width: 20px;
    height: 20px;
    stroke: #FEE2E2;
    stroke-width: 2;
    fill: none;
    flex-shrink: 0;
    animation: pulse 0.6s ease-in-out infinite;
  }

  .error-text {
    flex: 1;
    font-weight: 500;
    letter-spacing: 0.1px;
    line-height: 1.4;
  }

  .error-close {
    background: none;
    border: none;
    color: #FEE2E2;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: color 0.2s, transform 0.2s;
    flex-shrink: 0;
  }

  .error-close:hover {
    color: #FFFFFF;
    transform: scale(1.05);
  }

  .error-progress {
    position: relative;
    height: 4px;
    width: 100%;
    margin-top: 12px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 999px;
    overflow: hidden;
    z-index: 1;
  }

  .error-progress-inner {
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.35) 100%);
    transform-origin: left;
    transition: transform 5s linear;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-14px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes pulse {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0.7;
    }
  }

  /* Login button */
  .btn-login {
    width: 100%;
    height: 50px;
    background: var(--walnut-mid);
    color: var(--cream);
    border: none;
    border-radius: var(--radius-sm);
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    letter-spacing: 0.02em;
    position: relative;
    overflow: hidden;
    transition: background 0.25s, transform 0.15s, box-shadow 0.25s;
    box-shadow: 0 4px 18px rgba(90,62,43,0.28);
    margin-bottom: 24px;
    animation: fadeUp 0.5s 0.18s ease both;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn-login::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.08) 50%, transparent 100%);
    transform: translateX(-100%);
    transition: transform 0.45s ease;
  }

  .btn-login:hover {
    background: var(--walnut);
    box-shadow: 0 6px 28px rgba(90,62,43,0.38);
    transform: translateY(-1px);
  }

  .btn-login:hover::before { transform: translateX(100%); }

  .btn-login:active {
    transform: translateY(0px) scale(0.99);
    box-shadow: 0 2px 12px rgba(90,62,43,0.22);
  }

  .btn-arrow {
    width: 18px; height: 18px;
    stroke: currentColor; stroke-width: 1.8; fill: none;
    transition: transform 0.2s;
  }

  .btn-login:hover .btn-arrow { transform: translateX(3px); }

  /* SSO row */
  .sso-row {
    text-align: center;
    font-size: 13px;
    color: var(--text-muted);
    animation: fadeUp 0.5s 0.2s ease both;
  }

  .sso-row a {
    color: var(--walnut-mid);
    text-decoration: none;
    font-weight: 500;
    border-bottom: 1px solid rgba(90,62,43,0.25);
    transition: color 0.2s, border-color 0.2s;
  }

  .sso-row a:hover { color: var(--walnut); border-color: var(--walnut); }

  /* Form footer */
  .form-footer {
    margin-top: 36px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
    text-align: center;
    font-size: 12px;
    color: var(--text-muted);
    animation: fadeUp 0.5s 0.22s ease both;
  }

  .form-footer a {
    color: var(--walnut-mid);
    text-decoration: none;
    font-weight: 500;
  }

  .form-footer a:hover { text-decoration: underline; }

  /* ── Animations ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
  }

  @keyframes shake {
    0%,100% { transform: translateX(0); }
    20% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    60% { transform: translateX(-4px); }
    80% { transform: translateX(4px); }
  }

  /* ── Mobile ── */
  @media (max-width: 860px) {
    .page { grid-template-columns: 1fr; }

    .brand-panel {
      padding: 36px 28px;
      min-height: unset;
    }

    .brand-center { margin: 28px 0; }
    .brand-headline { font-size: 28px; }
    .brand-stats { margin-top: 24px; }
    .stat-card { padding: 12px 16px; }
    .stat-value { font-size: 22px; }
    .brand-footer { display: none; }

    .form-panel { padding: 40px 24px; }
    .form-card { max-width: 100%; }
  }

  @media (max-width: 480px) {
    .brand-stats { flex-direction: column; gap: 10px; }
    .stat-card { display: flex; align-items: center; gap: 14px; }
    .stat-value { font-size: 20px; margin-bottom: 0; margin-right: 4px; }
  }
</style>
</head>
<body>

<div class="page">

  <!-- ───── LEFT: Brand Panel ───── -->
  <div class="brand-panel" role="banner">

    <!-- Decorative arcs -->
    <svg class="brand-arcs" viewBox="0 0 320 320" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <circle cx="160" cy="160" r="155" stroke="white" stroke-width="0.8"/>
      <circle cx="160" cy="160" r="118" stroke="white" stroke-width="0.8"/>
      <circle cx="160" cy="160" r="80" stroke="white" stroke-width="0.8"/>
      <circle cx="160" cy="160" r="42" stroke="white" stroke-width="0.8"/>
      <line x1="5" y1="160" x2="315" y2="160" stroke="white" stroke-width="0.5"/>
      <line x1="160" y1="5" x2="160" y2="315" stroke="white" stroke-width="0.5"/>
    </svg>

    <!-- Logo -->
    <div class="brand-logo">
     
      <span class="logo-text">KiteaCall</span>
    </div>

    <!-- Center content -->
    <div class="brand-center">
      
      <h1 class="brand-headline">
        Transformer la qualité des appels en<br>
        <em>excellence de performance</em>
      </h1>
      <p class="brand-sub">
        Une suite d'évaluation professionnelle conçue pour les managers et conseillers qui exigent clarté, cohérence et résultats mesurables.
      </p>

      <div class="brand-stats">
        
        
        
      </div>
    </div>

    <p class="brand-footer">© 2025 Kitea · Système d'évaluation des centres d'appels</p>
  </div>

  <main class="form-panel">
    <div class="form-card">

      <div class="form-header">
        <p class="form-welcome">Bon retour parmi nous</p>
        <h2 class="form-title">Connectez-vous à votre espace</h2>
        <p class="form-subtitle">Saisissez vos identifiants pour accéder au tableau de bord</p>
      </div>

      <div class="form-divider">
        <div class="divider-line"></div>
        <span class="divider-text">Connexion sécurisée</span>
        <div class="divider-line"></div>
      </div>

      <form id="loginForm"  action="/login" method="post" >
             @csrf 
        <!-- Error message -->
        <div class="error-box" id="errorBox" role="alert" aria-live="polite">
          <svg class="error-icon" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="9"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <circle cx="12" cy="16" r="0.5" fill="#C04040" stroke="none"/>
          </svg>
          <span class="error-text" id="errorMsg">Adresse e-mail ou mot de passe incorrect. Veuillez réessayer.</span>
          <button type="button" class="error-close" id="errorClose" aria-label="Fermer l'alerte">✕</button>
        </div>

        <div class="field-group">
          <!-- Email -->
          <div class="field">
            <label class="field-label" for="email">Adresse e-mail professionnelle</label>
            <div class="field-input-wrap">
              <input
                type="email"
                id="email"
                name="email"
                placeholder="prenom.nom@entreprise.com"
                autocomplete="email"
                required
              >
              <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2"/>
                <polyline points="3,5 12,13 21,5"/>
              </svg>
            </div>
          </div>

          <!-- Password -->
          <div class="field">
            <label class="field-label" for="password">Mot de passe</label>
            <div class="field-input-wrap">
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Entrez votre mot de passe"
                autocomplete="current-password"
                required
              >
              <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
                <rect x="5" y="11" width="14" height="10" rx="2"/>
                <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
              </svg>
              <button type="button" class="pw-toggle" id="pwToggle" aria-label="Afficher le mot de passe">
                <svg id="eyeOpen" viewBox="0 0 24 24">
                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg id="eyeClosed" viewBox="0 0 24 24" style="display:none">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94"/>
                  <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Options row -->
        <div class="form-options">
          <label class="checkbox-label">
            <input type="checkbox" id="remember" name="remember">
            Se souvenir de moi
          </label>
          <a href="#" class="forgot-link">Mot de passe oublié ?</a>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-login" id="loginBtn">
          <span>Se connecter</span>
          <svg class="btn-arrow" viewBox="0 0 24 24" aria-hidden="true">
            <line x1="5" y1="12" x2="19" y2="12"/>
            <polyline points="12,5 19,12 12,19"/>
          </svg>
        </button>

      </form>

      <footer class="form-footer">
        Besoin d'un accès ? <a href="#">Contactez votre administrateur</a>
        &nbsp;·&nbsp;
        <a href="#">Politique de confidentialité</a>
      </footer>

    </div>
  </main>

</div>

<script>
  // Password visibility toggle
  const pwInput  = document.getElementById('password');
  const pwToggle = document.getElementById('pwToggle');
  const eyeOpen  = document.getElementById('eyeOpen');
  const eyeClosed = document.getElementById('eyeClosed');

  pwToggle.addEventListener('click', () => {
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    eyeOpen.style.display  = show ? 'none' : '';
    eyeClosed.style.display = show ? '' : 'none';
    pwToggle.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
  });

  // Email validation function
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Password validation function
  function isValidPassword(password) {
    return password && password.trim().length >= 3;
  }

  // Form submission with AJAX
  const form     = document.getElementById('loginForm');
  const errorBox = document.getElementById('errorBox');
  const errorMsg = document.getElementById('errorMsg');
  const errorClose = document.getElementById('errorClose');

  // Close button functionality
  errorClose.addEventListener('click', (e) => {
    e.preventDefault();
    errorBox.classList.remove('visible');
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    errorBox.classList.remove('visible');

    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const btn      = document.getElementById('loginBtn');

    // Client-side validation
    if (!email) {
      errorMsg.textContent = 'Veuillez saisir votre adresse e-mail.';
      errorBox.classList.add('visible');
      return;
    }

    if (!isValidEmail(email)) {
      errorMsg.textContent = 'Veuillez entrer une adresse e-mail valide.';
      errorBox.classList.add('visible');
      return;
    }

    if (!password) {
      errorMsg.textContent = 'Veuillez saisir votre mot de passe.';
      errorBox.classList.add('visible');
      return;
    }

    if (!isValidPassword(password)) {
      errorMsg.textContent = 'Le mot de passe doit contenir au moins 3 caractères.';
      errorBox.classList.add('visible');
      return;
    }

    // Show loading state
    btn.style.opacity = '0.7';
    btn.style.pointerEvents = 'none';
    btn.querySelector('span').textContent = 'Connexion en cours…';

    // Get CSRF token
    const csrfToken = document.querySelector('input[name="_token"]').value;

    // Send AJAX request
    fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: email,
        password: password
      })
    })
    .then(response => {
      if (response.ok) {
        return response.json();
      } else if (response.status === 401) {
        return response.json().then(data => {
          throw new Error('Identifiants invalides. Veuillez vérifier votre e-mail et votre mot de passe.');
        });
      } else if (response.status === 422) {
        return response.json().then(data => {
          throw new Error('' + Object.values(data.errors)[0][0]);
        });
      } else {
        throw new Error('⚠️ Une erreur est survenue. Veuillez réessayer.');
      }
    })
    .then(data => {
      // Success - redirect to dashboard
      if (data.success) {
        window.location.href = '/dashboard';
      }
    })
    .catch(error => {
      // Reset button state
      btn.style.opacity = '';
      btn.style.pointerEvents = '';
      btn.querySelector('span').textContent = 'Se connecter';
      
      // Show error
      errorMsg.textContent = error.message;
      errorBox.classList.add('visible');
      
      // Clear password field
      document.getElementById('password').value = '';
      document.getElementById('password').focus();
    });
  });

  // Hide error on input change
  ['email','password'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
      errorBox.classList.remove('visible');
    });
  });
</script>

</body>
</html>