@extends('layouts.app')

@section('title', 'Settings - KiteaCall Performance Dashboard')
@section('topbar_title', 'Settings')
@section('topbar_subtitle', 'Customize your workspace & preferences - Wednesday, 6 May 2026')

@section('content')
<style>
  .settings-container {
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
  }
  .settings-sidebar-nav {
    width: 260px;
    flex-shrink: 0;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(139,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    border: 1px solid rgba(139,0,0,0.05);
    padding: 12px 0;
    height: fit-content;
    position: sticky;
    top: calc(68px + 20px);
  }
  .settings-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 500;
    color: #6B3040;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
    border-left: 3px solid transparent;
  }
  .settings-nav-item svg {
    width: 18px;
    height: 18px;
    opacity: 0.7;
  }
  .settings-nav-item.active {
    background: #FFF5F5;
    border-left-color: #C0152A;
    color: #C0152A;
    font-weight: 600;
  }
  .settings-nav-item.active svg { opacity: 1; color: #C0152A; }
  .settings-nav-item:hover:not(.active) {
    background: #FFE8EA;
    color: #C0152A;
  }
  .settings-panel {
    flex: 1;
    min-width: 0;
  }
  .settings-section {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(139,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
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
    color: #1A0A0C;
  }
  .section-header p {
    font-size: 13px;
    color: #9C7078;
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
    color: #9C7078;
    margin-bottom: 8px;
  }
  input, select, textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1.5px solid rgba(139,0,0,0.12);
    background: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
    outline: none;
  }
  input:focus, select:focus, textarea:focus {
    border-color: #C0152A;
    box-shadow: 0 0 0 3px rgba(192,21,42,0.1);
  }
  .toggle-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid rgba(139,0,0,0.05);
  }
  .toggle-label { font-weight: 500; font-size: 14px; }
  .toggle-desc { font-size: 12px; color: #9C7078; margin-top: 2px; }
  .toggle-switch {
    position: relative;
    width: 48px;
    height: 24px;
    background: #ddd;
    border-radius: 30px;
    cursor: pointer;
    transition: background 0.25s;
  }
  .toggle-switch.active { background: #C0152A; }
  .toggle-switch::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: #fff;
    border-radius: 50%;
    top: 2px;
    left: 3px;
    transition: transform 0.25s;
  }
  .toggle-switch.active::after { transform: translateX(22px); }
  .color-swatch { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
  .color-option {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
  }
  .color-option.selected {
    border-color: #1A0A0C;
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .btn-primary {
    background: #C0152A;
    color: #fff;
    border: none;
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
  }
  .btn-primary:hover { background: #8B0000; transform: translateY(-2px); }
  .btn-secondary {
    background: #FFF5F5;
    border: 1px solid rgba(139,0,0,0.2);
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    margin-left: 12px;
  }
  .danger-zone {
    background: #fff8f8;
    border-left: 4px solid #8B0000;
  }
  .danger-btn {
    background: transparent;
    border: 1px solid #8B0000;
    color: #8B0000;
    padding: 10px 24px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
  }
  .danger-btn:hover { background: #8B0000; color: #fff; }
  .toast-message {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #1e2a1e;
    color: #fff;
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
  .toast-message.show { opacity: 1; }
  hr {
    margin: 20px 0;
    border: none;
    height: 1px;
    background: rgba(139,0,0,0.08);
  }
  @media (max-width: 960px) {
    .settings-container { flex-direction: column; }
    .settings-sidebar-nav { width: 100%; position: static; margin-bottom: 20px; display: flex; overflow-x: auto; }
    .settings-nav-item { white-space: nowrap; }
  }
  @media (max-width: 600px) {
    .form-row { flex-direction: column; }
    .settings-sidebar-nav { padding: 8px 0; }
  }
</style>

<div class="settings-container">
  <div class="settings-sidebar-nav">
    <div class="settings-nav-item active" data-tab="profile"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Profile</div>
    <div class="settings-nav-item" data-tab="notifications"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>Notifications</div>
    <div class="settings-nav-item" data-tab="appearance"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>Appearance</div>
    <div class="settings-nav-item" data-tab="security"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>Security</div>
    <div class="settings-nav-item" data-tab="team"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>Team Preferences</div>
  </div>

  <div class="settings-panel" id="settingsPanel">
    <div class="settings-section tab-pane active" id="profileTab">
      <div class="section-header"><h2>Profile information</h2><p>Update your personal details. Email is unique and cannot be changed here.</p></div>
      <div class="settings-form">
        @if(session('success'))
          <div style="margin-bottom:16px;padding:14px 18px;border-radius:12px;background:#E7F7EE;color:#0B6B34;">{{ session('success') }}</div>
        @endif
        @if($errors->has('name'))
          <div style="margin-bottom:16px;padding:14px 18px;border-radius:12px;background:#FDECEA;color:#9C2E2E;">{{ $errors->first('name') }}</div>
        @endif
        <form action="{{ route('settings.update') }}" method="POST">
          @csrf
          <div class="form-row"><div class="form-group"><label>Full name</label><input type="text" name="name" id="fullName" value="{{ old('name', $user->name) }}" placeholder="Full name"></div><div class="form-group"><label>Email address</label><input type="email" id="email" value="{{ $user->email }}" disabled></div></div>
          <div class="form-row"><div class="form-group"><label>Role / Title</label><input type="text" id="role" value="{{ ucfirst($user->role) }}" disabled></div><div class="form-group"><label>Department</label><input type="text" id="dept" value="Quality & Evaluation"></div></div>
          <button type="submit" class="btn-primary">Save changes</button>
        </form>
      </div>
    </div>

    <div class="settings-section tab-pane" id="notificationsTab" style="display:none">
      <div class="section-header"><h2>Notification preferences</h2><p>Manage how you receive alerts and updates</p></div>
      <div class="settings-form">
        <div class="toggle-group"><div><div class="toggle-label">Email summaries</div><div class="toggle-desc">Weekly performance digest</div></div><div class="toggle-switch" data-toggle="emailDigest"></div></div>
        <div class="toggle-group"><div><div class="toggle-label">Evaluation reminders</div><div class="toggle-desc">Get notified when evaluations are pending</div></div><div class="toggle-switch active" data-toggle="evalReminders"></div></div>
        <div class="toggle-group"><div><div class="toggle-label">Mentions & comments</div><div class="toggle-desc">Real-time alerts for team feedback</div></div><div class="toggle-switch active" data-toggle="mentions"></div></div>
        <button class="btn-primary" id="saveNotifBtn">Save preferences</button>
      </div>
    </div>

    <div class="settings-section tab-pane" id="appearanceTab" style="display:none">
      <div class="section-header"><h2>Theme & appearance</h2><p>Customize your dashboard look and feel</p></div>
      <div class="settings-form">
        <div class="form-group"><label>Color scheme (accent)</label><div class="color-swatch"><div class="color-option" style="background:#8B0000" data-color="walnut"></div><div class="color-option" style="background:#C0152A" data-color="walnut-mid"></div><div class="color-option selected" style="background:#F5A623" data-color="gold"></div><div class="color-option" style="background:#7A8C72" data-color="sage"></div></div></div>
        <div class="toggle-group"><div><div class="toggle-label">Compact density</div><div class="toggle-desc">Reduce spacing between elements</div></div><div class="toggle-switch" data-toggle="compactDensity"></div></div>
        <button class="btn-primary" id="saveAppearanceBtn">Apply appearance</button>
      </div>
    </div>

    <div class="settings-section tab-pane" id="securityTab" style="display:none">
      <div class="section-header"><h2>Security & sessions</h2><p>Update your password and protect your account.</p></div>
      <div class="settings-form">
        @if(session('success_password'))
          <div style="margin-bottom:16px;padding:14px 18px;border-radius:12px;background:#E7F7EE;color:#0B6B34;">{{ session('success_password') }}</div>
        @endif
        @if($errors->has('current_password'))
          <div style="margin-bottom:16px;padding:14px 18px;border-radius:12px;background:#FDECEA;color:#9C2E2E;">{{ $errors->first('current_password') }}</div>
        @endif
        @if($errors->has('new_password'))
          <div style="margin-bottom:16px;padding:14px 18px;border-radius:12px;background:#FDECEA;color:#9C2E2E;">{{ $errors->first('new_password') }}</div>
        @endif
        <form action="{{ route('settings.password.update') }}" method="POST">
          @csrf
          <div class="form-group"><label>Current password</label><input type="password" name="current_password" id="currentPassword" placeholder="Enter current password"></div>
          <div class="form-row"><div class="form-group"><label>New password</label><input type="password" name="new_password" id="newPassword" placeholder="New password"></div><div class="form-group"><label>Confirm password</label><input type="password" name="new_password_confirmation" id="confirmPassword" placeholder="Confirm new password"></div></div>
          <div id="passwordHelp" style="font-size:13px;color:#9C7078;margin-bottom:16px;">Enter your current password and choose a new one to enable update.</div>
          <button type="submit" class="btn-primary" id="updatePasswordBtn" disabled>Update password</button>
        </form>
        <hr>
        <div class="danger-zone" style="padding:16px 0"><div class="toggle-label">Two-factor authentication</div><div class="toggle-desc">Add an extra layer of security</div><button class="btn-secondary" style="margin-top:12px">Enable 2FA</button></div>
      </div>
    </div>

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

<div id="toastMsg" class="toast-message">✨ Settings saved</div>
@endsection

@push('scripts')
<script>
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

  document.querySelectorAll('.toggle-switch').forEach(toggle => {
    toggle.addEventListener('click', function() { this.classList.toggle('active'); });
  });

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

  const currentPassword = document.getElementById('currentPassword');
  const newPassword = document.getElementById('newPassword');
  const confirmPassword = document.getElementById('confirmPassword');
  const updatePasswordBtn = document.getElementById('updatePasswordBtn');
  const passwordHelp = document.getElementById('passwordHelp');

  function validatePasswordSection() {
    const current = currentPassword?.value.trim();
    const next = newPassword?.value.trim();
    const confirm = confirmPassword?.value.trim();
    const ready = current && next && confirm && next === confirm;

    if (updatePasswordBtn) {
      updatePasswordBtn.disabled = !ready;
    }

    if (!current) {
      passwordHelp.textContent = 'Enter your current password to begin.';
    } else if (!next || !confirm) {
      passwordHelp.textContent = 'Choose a new password and confirm it.';
    } else if (next !== confirm) {
      passwordHelp.textContent = 'Passwords do not match.';
    } else {
      passwordHelp.textContent = 'Passwords match. Click update to save your new password.';
    }
  }

  [currentPassword, newPassword, confirmPassword].forEach(input => {
    input?.addEventListener('input', validatePasswordSection);
  });

  updatePasswordBtn?.addEventListener('click', () => {
    if (!updatePasswordBtn.disabled) {
      // allow natural form submission to handle password change
    }
  });

  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
      if (this.getAttribute('href') === '#') {
        e.preventDefault();
      }
      document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
      this.classList.add('active');
      if (window.innerWidth <= 960) toggleSidebar();
    });
  });

  document.querySelector('.nav-link.active')?.classList.add('active');
</script>
@endpush
