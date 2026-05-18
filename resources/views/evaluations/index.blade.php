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

  <form method="GET" action="{{ url('/evaluations') }}" class="filters-bar" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:20px;align-items:end;">
    <div>
      <label style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">From</label>
      <input type="date" name="date_from" value="{{ request('date_from') }}" style="width:100%;padding:10px;border-radius:10px;border:1px solid rgba(0,0,0,0.08);" />
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">To</label>
      <input type="date" name="date_to" value="{{ request('date_to') }}" style="width:100%;padding:10px;border-radius:10px;border:1px solid rgba(0,0,0,0.08);" />
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">Conseiller</label>
      <select name="conseiller_id" style="width:100%;padding:10px;border-radius:10px;border:1px solid rgba(0,0,0,0.08);">
        <option value="">All conseillers</option>
        @foreach($conseillers as $conseiller)
          <option value="{{ $conseiller->id }}" {{ request('conseiller_id') == $conseiller->id ? 'selected' : '' }}>{{ $conseiller->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">Call type</label>
      <select name="call_type" style="width:100%;padding:10px;border-radius:10px;border:1px solid rgba(0,0,0,0.08);">
        <option value="">All call types</option>
        <option value="incoming" {{ request('call_type') == 'incoming' ? 'selected' : '' }}>Incoming</option>
        <option value="outgoing" {{ request('call_type') == 'outgoing' ? 'selected' : '' }}>Outgoing</option>
      </select>
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">Status</label>
      <select name="status" style="width:100%;padding:10px;border-radius:10px;border:1px solid rgba(0,0,0,0.08);">
        <option value="">All statuses</option>
        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }}>Signed</option>
      </select>
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--text-muted);margin-bottom:6px;">KO</label>
      <select name="ko" style="width:100%;padding:10px;border-radius:10px;border:1px solid rgba(0,0,0,0.08);">
        <option value="">KO: any</option>
        <option value="with" {{ request('ko') == 'with' ? 'selected' : '' }}>With KO</option>
        <option value="without" {{ request('ko') == 'without' ? 'selected' : '' }}>Without KO</option>
      </select>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
      <button type="submit" class="sig-btn sig-btn-primary" style="width:100%;padding:10px 16px;">Apply</button>
      <a href="{{ url('/evaluations') }}" class="sig-btn" style="width:100%;padding:10px 16px;background:#f3f3f3;color:#333;border-radius:10px;text-align:center;">Clear</a>
    </div>
  </form>

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
              <td>
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
