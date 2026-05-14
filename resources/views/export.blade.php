@extends('layouts.app')

@section('title', 'Export Data - KiteaCall')
@section('topbar_title', 'Export Data')
@section('topbar_subtitle', 'Download evaluations in CSV, Excel, or PDF format')

@section('content')
<style>
  .export-wrap {
    max-width: 980px;
    margin: 0 auto;
  }
  .export-card {
    padding: 28px;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 2px 22px rgba(0,0,0,0.06);
    border: 1px solid rgba(139,0,0,0.08);
  }
  .export-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-top: 28px;
  }
  .export-box {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 24px;
    border-radius: 18px;
    border: 1px solid rgba(139,0,0,0.08);
    background: #faf6f6;
    text-align: left;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .export-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 30px rgba(0,0,0,0.08);
  }
  .export-box h3 {
    margin: 0;
    font-size: 18px;
    color: #1A0A0C;
  }
  .export-box p {
    margin: 0;
    color: #7a4f56;
    font-size: 14px;
    line-height: 1.6;
  }
  .export-box a {
    margin-top: auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 18px;
    border-radius: 999px;
    font-weight: 700;
    text-decoration: none;
    color: #fff;
  }
  .export-csv { background: linear-gradient(135deg, #7A8C72, #9CB394); }
  .export-excel { background: linear-gradient(135deg, #1d6f42, #207a4a); }
  .export-pdf { background: linear-gradient(135deg, #8B0000, #C0152A); }
  .export-note {
    margin-top: 20px;
    font-size: 13px;
    color: #6b4f56;
  }
  @media (max-width: 840px) {
    .export-grid { grid-template-columns: 1fr; }
  }
</style>

<div class="export-wrap">
  <div class="export-card">
    <div class="card-header">
      <div>
        <h3 class="card-title">Export Evaluations</h3>
        <p class="card-subtitle">Choose a download format for all evaluation records.</p>
      </div>
    </div>

    <div class="export-grid">
      <div class="export-box">
        <h3>Comma-separated values</h3>
        <p>Download a clean CSV file with all evaluations and conseiller details.</p>
        <a href="{{ route('export', ['format' => 'csv']) }}" class="export-csv">Download CSV</a>
      </div>

      <div class="export-box">
        <h3>Excel-compatible export</h3>
        <p>Open the exported spreadsheet directly in Excel or Google Sheets.</p>
        <a href="{{ route('export', ['format' => 'excel']) }}" class="export-excel">Download Excel</a>
      </div>

      <div class="export-box">
        <h3>Printable PDF summary</h3>
        <p>Download a simple PDF-ready export of the latest evaluation rows.</p>
        <a href="{{ route('export', ['format' => 'pdf']) }}" class="export-pdf">Download PDF</a>
      </div>
    </div>

    <div class="export-note">
      This export includes all evaluations stored in the system. The PDF version is generated server-side as a compact summary; use CSV or Excel for full spreadsheet work.
    </div>
  </div>
</div>
@endsection
