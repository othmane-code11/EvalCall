@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div style="padding: 32px 40px; max-width: 1200px;">
  <!-- Dashboard Header -->
  <div style="margin-bottom: 32px;">
    <h1 style="font-size: 28px; font-weight: 700; color: #1A0A0C; margin: 0;">Tableau de bord</h1>
    <p style="color: #9C7078; font-size: 14px; margin: 4px 0 0 0;">Welcome back! Here's what's happening with your team today.</p>
  </div>

  <!-- KPI CARDS -->
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px;">
    <!-- Total Evaluations -->
    <div style="background: white; padding: 20px; border-radius: 14px; border: 1px solid rgba(139,0,0,0.07); box-shadow: 0 2px 16px rgba(139,0,0,0.07);">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
        <span style="color: #9C7078; font-size: 12px; font-weight: 500;">Total Evaluations</span>
        <div style="width: 40px; height: 40px; background: rgba(192,21,42,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #C0152A;">
          📋
        </div>
      </div>
      <div style="font-size: 28px; font-weight: 700; color: #1A0A0C; margin-bottom: 8px;">1,284</div>
      <div style="font-size: 12px; color: #9C7078;">
        <span style="color: #228B22;">▲ 12.4%</span> vs last month
      </div>
    </div>

    <!-- Average Score -->
    <div style="background: white; padding: 20px; border-radius: 14px; border: 1px solid rgba(139,0,0,0.07); box-shadow: 0 2px 16px rgba(139,0,0,0.07);">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
        <span style="color: #9C7078; font-size: 12px; font-weight: 500;">Average Score</span>
        <div style="width: 40px; height: 40px; background: rgba(245,166,35,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #F5A623;">
          ⭐
        </div>
      </div>
      <div style="font-size: 28px; font-weight: 700; color: #1A0A0C; margin-bottom: 8px;">87.3<span style="font-size: 14px; color: #9C7078;">%</span></div>
      <div style="font-size: 12px; color: #9C7078;">
        <span style="color: #228B22;">▲ 3.1%</span> vs last month
      </div>
    </div>

    <!-- Pending Reviews -->
    <div style="background: white; padding: 20px; border-radius: 14px; border: 1px solid rgba(139,0,0,0.07); box-shadow: 0 2px 16px rgba(139,0,0,0.07);">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
        <span style="color: #9C7078; font-size: 12px; font-weight: 500;">Pending Reviews</span>
        <div style="width: 40px; height: 40px; background: rgba(192,21,42,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #C0152A;">
          ⏱️
        </div>
      </div>
      <div style="font-size: 28px; font-weight: 700; color: #1A0A0C; margin-bottom: 8px;">48</div>
      <div style="font-size: 12px; color: #9C7078;">
        <span style="color: #C0152A;">▼ 5 urgent</span> need action
      </div>
    </div>

    <!-- Active Users -->
    <div style="background: white; padding: 20px; border-radius: 14px; border: 1px solid rgba(139,0,0,0.07); box-shadow: 0 2px 16px rgba(139,0,0,0.07);">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
        <span style="color: #9C7078; font-size: 12px; font-weight: 500;">Active Users</span>
        <div style="width: 40px; height: 40px; background: rgba(122,140,114,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #7A8C72;">
          👥
        </div>
      </div>
      <div style="font-size: 28px; font-weight: 700; color: #1A0A0C; margin-bottom: 8px;">136</div>
      <div style="font-size: 12px; color: #9C7078;">
        <span style="color: #228B22;">▲ 8 new</span> this week
      </div>
    </div>
  </div>

  <!-- Welcome Message -->
  <div style="background: linear-gradient(135deg, #C0152A 0%, #8B0000 100%); padding: 32px; border-radius: 14px; color: white; text-align: center;">
    <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 8px 0;">Welcome to EvalCall</h2>
    <p style="margin: 0; opacity: 0.9;">Manage your team evaluations efficiently. Navigate through the sidebar to explore features and manage users.</p>
  </div>

</div>
@endsection
