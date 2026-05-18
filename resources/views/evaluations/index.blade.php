@extends('layouts.app')

@section('title', 'Evaluations — EvalCall')
@section('topbar_title', 'All Evaluations')
@section('topbar_subtitle', 'Showing all evaluations with pagination')

@section('content')
<style>
  .pagination {
    display: inline-flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
    align-items: center;
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .pagination li {
    margin: 0;
  }
  .pagination li a,
  .pagination li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 30px;
    padding: 0 10px;
    border-radius: 999px;
    border: 1px solid rgba(139, 0, 0, 0.15);
    background: #fff;
    color: var(--walnut);
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    text-decoration: none;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    transition: transform 0.15s ease, background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
  }
  .pagination li a:hover {
    background: rgba(245, 166, 35, 0.12);
    color: var(--walnut-mid);
    transform: translateY(-1px);
  }
  .pagination li.active span,
  .pagination li.active a {
    background: var(--walnut-mid);
    color: #fff;
    border-color: var(--walnut-mid);
    box-shadow: none;
  }
  .pagination li.disabled span,
  .pagination li.disabled a {
    opacity: 0.4;
    cursor: not-allowed;
    background: #f7f2f2;
  }
  .pagination li:first-child a,
  .pagination li:last-child a {
    min-width: 28px;
    padding: 0 8px;
    font-size: 11px;
  }
  .pagination li a svg,
  .pagination li span svg {
    width: 12px;
    height: 12px;
  }
</style>
<div class="content">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;gap:16px;flex-wrap:wrap;">
    <div>
      <h2 style="margin:0;font-family:Syne, sans-serif;">Evaluations</h2>
      <p style="margin:6px 0 0;font-size:13px;color:var(--text-muted);">Showing {{ $evaluations->firstItem() ?? 0 }} to {{ $evaluations->lastItem() ?? 0 }} of {{ $evaluations->total() }} evaluations.</p>
    </div>
    <a href="{{ url('/evaluations/create') }}" class="sig-btn sig-btn-primary">Create evaluation</a>
  </div>

  <div class="card eval-card" style="padding:0;overflow:hidden;">
    <div class="table-wrap" style="overflow-x:auto;">
      <table style="width:100%;border-collapse:collapse;min-width:900px;">
        <thead>
          <tr style="background:rgba(139,0,0,0.04);">
            <th style="padding:14px 16px;text-align:left;">ID</th>
            <th style="padding:14px 16px;text-align:left;">Conseiller</th>
            <th style="padding:14px 16px;text-align:left;">Call Type</th>
            <th style="padding:14px 16px;text-align:left;">Date</th>
            <th style="padding:14px 16px;text-align:left;">Score</th>
            <th style="padding:14px 16px;text-align:left;">KO</th>
            <th style="padding:14px 16px;text-align:left;">Status</th>
            <th style="padding:14px 16px;text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($evaluations as $ev)
          @php
            $scoreColor = $ev['score'] >= 85 ? '#4a6b42' : ($ev['score'] >= 70 ? '#C07A00' : '#8B0000');
            $barFill    = $ev['score'] >= 85 ? 'linear-gradient(90deg,#7A8C72,#9CB394)'
                         : ($ev['score'] >= 70 ? 'linear-gradient(90deg,#F5A623,#F7BC54)'
                         : 'linear-gradient(90deg,#8B0000,#C0152A)');
          @endphp
            <tr style="border-top:1px solid rgba(0,0,0,0.05);">
              <td style="padding:14px 16px;font-weight:700;color:var(--walnut);">{{ $ev['id'] }}</td>
              <td style="padding:14px 16px;">
                <div style="display:flex;align-items:center;gap:10px;">
                  <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:{{ $ev['avatar'] }};color:#fff;font-weight:700;">{{ $ev['initials'] }}</div>
                  <div>
                    <div style="font-weight:600;color:var(--text-dark);">{{ $ev['name'] }}</div>
                  </div>
                </div>
              </td>
              <td style="padding:14px 16px;">
                @if($ev['type'] === 'incoming')
                  <span class="call-pill call-in">↓ Incoming</span>
                @else
                  <span class="call-pill call-out">↑ Outgoing</span>
                @endif
              </td>
              <td style="padding:14px 16px;">{{ $ev['date'] }}</td>
              <td>
              <div class="score-cell">
                <span class="score-val" style="color: {{ $scoreColor }};">{{ $ev['score'] }}</span>
                <div class="score-bar">
                  <div class="score-bar-fill" style="width: {{ $ev['score'] }}%; background: {{ $barFill }};"></div>
                </div>
              </div>
            </td>
              <td style="padding:14px 16px;">@if($ev['ko']) <span class="ko-chip ko-yes">⚠ KO</span> @else <span class="ko-chip ko-no">✓ OK</span> @endif</td>
              <td style="padding:14px 16px;">{{ ucfirst($ev['status']) }}</td>
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
          @empty
            <tr>
              <td colspan="8" style="padding:24px;text-align:center;color:var(--text-muted);">No evaluations found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="padding:18px 20px;display:flex;justify-content:center;border-top:1px solid rgba(0,0,0,0.06);background:var(--white);">
      {{ $evaluations->withQueryString()->links('pagination::simple-bootstrap-4') }}
    </div>
    
  </div>
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
</div>
@endsection
