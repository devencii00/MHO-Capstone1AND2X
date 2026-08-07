@extends('layouts.app')

@section('title', 'Medical Results')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body, .wrap { font-family: 'Inter', sans-serif; }
  .wrap {
    background: #f4f5f7;
    min-height: 100vh;
    padding: 28px 20px 40px;
    font-family: -apple-system, 'Segoe UI', sans-serif;
  }
  .page-title { font-size: 22px; font-weight: 800; color: #111827; }
  .page-sub   { font-size: 13px; color: #6b7280; margin-top: 2px; margin-bottom: 24px; }

  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
  }
  .stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 25px 22px;
    height: 145px;
    display: flex; align-items: center; gap: 16px;
    border: 1px solid #e5e7eb;
  }
  .stat-icon {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .stat-label { font-size: 12px; font-weight: 700; color: #6b7280; margin-bottom: 2px; letter-spacing: .03em; }
  .stat-num   { font-size: 25px; font-weight: 700; line-height: 1; color: #111827; margin-bottom: 3px; }
  .stat-desc  { font-size: 12px; color: #6b7280; margin-bottom: 8px; }

  .toolbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 8px; gap: 12px;
  }
  .search-wrap { position: relative; flex: 1; max-width: 300px; }
  .search-wrap svg {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    width: 16px; height: 16px; stroke: #9ca3af; fill: none; stroke-width: 2;
  }
  .search-input {
    width: 100%; padding: 9px 12px 9px 36px;
    border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 14px; background: #fff; color: #111827; outline: none;
  }
  .search-input::placeholder { color: #9ca3af; }
  .search-input:focus { border-color: #22c55e; }

  .filter-group { display: flex; align-items: center; gap: 8px; }
  .dropdown-wrap { position: relative; }
  .dropdown-btn {
    display: flex; align-items: center; gap: 6px;
    padding: 9px 12px; border-radius: 8px; border: 1.5px solid #e5e7eb;
    font-size: 13px; font-weight: 600; cursor: pointer; background: #fff;
    color: #374151; font-family: inherit;
    min-width: 130px; justify-content: space-between;
  }
  .dropdown-btn:hover { border-color: #22c55e; }
  .dropdown-btn svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 2.5; }
  .dropdown-menu {
    position: absolute; top: calc(100% + 4px); right: 0; z-index: 50;
    background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.1); min-width: 160px; padding: 6px;
    display: none;
  }
  .dropdown-menu.open { display: block; }
  .dropdown-item {
    padding: 9px 14px; border-radius: 7px; font-size: 13px; font-weight: 600;
    cursor: pointer; color: #374151; transition: background .12s;
  }
  .dropdown-item:hover { background: #f3f4f6; }
  .dropdown-item.active { background: #dcfce7; color: #16a34a; }

  .table-wrap {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    overflow: hidden;
  }
  .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  table { width: 100%; border-collapse: collapse; min-width: 800px; }
  thead tr { border-bottom: 1.5px solid #e5e7eb; }
  thead th {
    padding: 12px 18px;
    text-align: left; font-size: 11px; font-weight: 700;
    color: #6b7280; letter-spacing: .08em; text-transform: uppercase;
    white-space: nowrap;
  }
  tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .12s; }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #f9fafb; }
  tbody td { padding: 15px 18px; font-size: 14px; color: #111827; white-space: nowrap; }
  .pid { font-size: 14px; font-weight: 600; color: #111827; }

  .badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 600; white-space: nowrap;
  }
  .badge svg { width: 13px; height: 13px; flex-shrink: 0; }
  .badge.awaiting   { background: #fff7ed; color: #ea580c; }
  .badge.awaiting svg { stroke: #ea580c; fill: none; stroke-width: 2; }
  .badge.processing { background: #eff6ff; color: #2563eb; }
  .badge.processing svg { stroke: #2563eb; fill: none; stroke-width: 2; }

  .btn-input {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 8px;
    border: 1.5px solid #16a34a; background: #dcfce7; color: #16a34a;
    font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit;
    transition: all .15s; white-space: nowrap;
  }
  .btn-input:hover { background: #16a34a; color: #fff; }
  .btn-input:hover svg { stroke: #fff; }
  .btn-input svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }

  .no-action { font-size: 13px; color: #9ca3af; }

  .pagination-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px;
    border-top: 1px solid #e5e7eb;
    font-size: 13px; color: #6b7280;
    flex-wrap: wrap; gap: 10px;
  }
  .page-btns { display: flex; gap: 6px; flex-wrap: wrap; }
  .page-btn {
    width: 32px; height: 32px; border-radius: 7px; border: 1.5px solid #e5e7eb;
    background: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 600; color: #6b7280; font-family: inherit;
    transition: border-color .12s;
  }
  .page-btn:hover { border-color: #22c55e; color: #22c55e; }
  .page-btn.active { background: #22c55e; color: white; border-color: #22c55e; }
  .page-btn:disabled { opacity: 0.4; cursor: not-allowed; }

  /* ── Modals ── */
  .modal-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 1000;
    background: rgba(0,0,0,0.5);
    align-items: center; justify-content: center;
    padding: 20px;
  }
  .modal-overlay.open { display: flex; }
  .modal-overlay.active { display: flex; }

  .modal-box {
    background: #fff;
    border-radius: 16px;
    width: 100%; max-width: 850px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    animation: modalIn .2s ease;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  .modal-box::-webkit-scrollbar { display: none; }
  @keyframes modalIn {
    from { opacity: 0; transform: translateY(12px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }

  .modal-header {
    background: #166534;
    color: #f8fafc;
    padding: 16px 24px;
    display: flex; align-items: center; justify-content: space-between;
    border-radius: 16px 16px 0 0;
    position: sticky; top: 0; z-index: 10;
  }
  .modal-header-title { font-size: 17px; font-weight: 700; color: #f8fafc; }
  .modal-back-btn-header {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 8px;
    border: 1px solid #22c55e; background: transparent;
    font-size: 13px; font-weight: 600; color: #bbf7d0;
    cursor: pointer; font-family: inherit; transition: all .15s;
    white-space: nowrap;
  }
  .modal-back-btn-header:hover { background: #14532d; color: #fff; border-color: #4ade80; }
  .modal-back-btn-header svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2.5; }

  .modal-body { padding: 0 24px 24px; }

  .m-section-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #6b7280;
    padding: 16px 0 10px;
    border-bottom: 1.5px solid #f3f4f6;
    margin-bottom: 14px;
  }
  .m-section-title svg { width: 18px; height: 18px; stroke: #22c55e; fill: none; stroke-width: 2; }

  .profile-grid {
    display: flex; flex-wrap: wrap; gap: 8px 24px;
    margin-bottom: 4px; align-items: center;
  }
  .profile-item {
    font-size: 14px; color: #111827;
    display: inline-flex; align-items: center; gap: 4px;
  }
  .profile-item .lbl { font-size: 12px; font-weight: 600; color: #6b7280; }
  .profile-separator { color: #d1d5db; margin: 0 4px; font-size: 14px; }

  .findings-textarea {
    width: 100%; padding: 14px 16px; font-size: 15px;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    background: #f9fafb; color: #111827;
    font-family: inherit; outline: none; resize: vertical; min-height: 120px;
    line-height: 1.7; transition: border-color .15s, background .15s;
  }
  .findings-textarea:focus { border-color: #22c55e; background: #fff; }
  .findings-textarea.error { border-color: #dc2626; background: #fef2f2; }
  .error-msg { font-size: 12px; color: #dc2626; margin-top: 4px; display: none; }

  .impression-textarea {
    width: 100%; padding: 14px 16px; font-size: 15px;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    background: #f9fafb; color: #111827;
    font-family: inherit; outline: none; resize: vertical; min-height: 100px;
    line-height: 1.7; transition: border-color .15s, background .15s;
  }
  .impression-textarea:focus { border-color: #22c55e; background: #fff; }
  .impression-textarea.error { border-color: #dc2626; background: #fef2f2; }

  .upload-row-wrap { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-top: 4px; }
  .upload-sub {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #6b7280;
  }
  .upload-sub svg { width: 16px; height: 16px; stroke: #3b82f6; fill: none; stroke-width: 2; }
  .choose-file-btn {
    padding: 8px 16px; border-radius: 8px;
    border: 1.5px solid #d1d5db; background: #fff;
    font-size: 14px; font-weight: 600; color: #374151;
    cursor: pointer; font-family: inherit; white-space: nowrap; transition: all .15s;
  }
  .choose-file-btn:hover { border-color: #3b82f6; color: #3b82f6; }
  .file-hint { font-size: 13px; color: #9ca3af; }
  .file-list { margin-top: 8px; display: flex; flex-direction: column; gap: 6px; }
  .file-list-item {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; color: #374151;
    padding: 6px 12px; background: #f9fafb; border-radius: 8px;
    word-break: break-all;
  }
  .file-list-item svg { width: 16px; height: 16px; stroke: #dc2626; fill: none; stroke-width: 2; cursor: pointer; flex-shrink: 0; }

  .modal-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 24px;
    border-top: 1.5px solid #f3f4f6;
    gap: 14px;
    position: sticky; bottom: 0;
    background: #fff; border-radius: 0 0 16px 16px;
  }
  .btn-discard {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 22px; border-radius: 10px;
    border: 1.5px solid #fca5a5; background: #fff; color: #dc2626;
    font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all .15s;
  }
  .btn-discard:hover { background: #fef2f2; border-color: #dc2626; }
  .btn-discard svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
  .btn-save {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 26px; border-radius: 10px;
    border: none; background: #16a34a; color: #fff;
    font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; transition: background .15s;
  }
  .btn-save:hover { background: #15803d; }
  .btn-save:disabled { background: #86efac; cursor: not-allowed; }
  .btn-save svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }

  .confirm-modal-box {
    background: #fff; border-radius: 20px; padding: 32px 28px 28px;
    max-width: 420px; width: 90%; text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    transform: translateY(20px) scale(0.95); transition: transform 0.3s ease;
  }
  .modal-overlay.active .confirm-modal-box { transform: translateY(0) scale(1); }
  .confirm-modal-icon {
    width: 64px; height: 64px; margin: 0 auto 20px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
  }
  .confirm-modal-icon.success { background: #f0fdf4; border: 3px solid #bbf7d0; }
  .confirm-modal-icon.warning { background: #fffbeb; border: 3px solid #fde68a; }
  .confirm-modal-icon.error   { background: #fef2f2; border: 3px solid #fecaca; }
  .confirm-modal-icon.info    { background: #eff6ff; border: 3px solid #bfdbfe; }
  .confirm-modal-icon svg { width: 32px; height: 32px; fill: none; stroke-width: 2.5; }
  .confirm-modal-icon.success svg { stroke: #22c55e; }
  .confirm-modal-icon.warning svg { stroke: #f59e0b; }
  .confirm-modal-icon.error svg   { stroke: #dc2626; }
  .confirm-modal-icon.info svg    { stroke: #3b82f6; }
  .confirm-modal-box h3 { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
  .confirm-modal-box p { font-size: 15px; color: #64748b; line-height: 1.6; margin-bottom: 24px; }
  .confirm-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
  .btn-confirm {
    padding: 12px 24px; border-radius: 10px; font-size: 15px; font-weight: 600;
    font-family: inherit; cursor: pointer; border: none; transition: all 0.15s;
  }
  .btn-confirm-primary   { background: #16a34a; color: #fff; }
  .btn-confirm-primary:hover { background: #15803d; }
  .btn-confirm-danger    { background: #dc2626; color: #fff; }
  .btn-confirm-danger:hover { background: #b91c1c; }
  .btn-confirm-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
  .btn-confirm-secondary:hover { background: #e2e8f0; }

  .toast {
    position: fixed; bottom: 28px; right: 28px; z-index: 2000;
    display: flex; align-items: center; gap: 10px;
    padding: 14px 20px; border-radius: 10px;
    font-size: 14px; font-weight: 600; font-family: inherit;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
    opacity: 0; transform: translateY(10px);
    transition: all .25s ease; pointer-events: none;
  }
  .toast.show { opacity: 1; transform: translateY(0); }
  .toast.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
  .toast.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
  .toast svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2.5; }

  .spinner {
    width: 18px; height: 18px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff;
    animation: spin .6s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── RESPONSIVE ── */
  @media (max-width: 1200px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 768px) {
    .wrap { padding: 16px 12px 30px; }
    .page-title { font-size: 20px; }
    .page-sub { font-size: 12px; margin-bottom: 16px; }
    .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .stat-card { padding: 16px 14px; height: auto; min-height: 100px; gap: 12px; }
    .stat-icon { width: 40px; height: 40px; }
    .stat-icon img { width: 30px !important; height: 30px !important; }
    .stat-num { font-size: 22px; }
    .stat-label { font-size: 11px; }
    .stat-desc { font-size: 11px; }
    .toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
    .search-wrap { max-width: 100%; }
    .table-wrap { border-radius: 10px; }
    .table-responsive { border-radius: 10px; -webkit-overflow-scrolling: touch; }
    table { min-width: 650px; }
    thead th { padding: 10px 12px; font-size: 10px; }
    tbody td { padding: 12px; font-size: 13px; }
    .btn-input { padding: 6px 10px; font-size: 11px; }
    .pagination-row { flex-direction: column; align-items: center; text-align: center; padding: 12px; font-size: 12px; }
    .page-btn { width: 28px; height: 28px; font-size: 12px; }
    .modal-overlay { padding: 10px; }
    .modal-box { max-width: 95vw; border-radius: 12px; max-height: 90vh; }
    .modal-header { padding: 12px 16px; border-radius: 12px 12px 0 0; flex-wrap: wrap; gap: 8px; }
    .modal-header-title { font-size: 15px; }
    .modal-back-btn-header { padding: 6px 10px; font-size: 12px; }
    .modal-body { padding: 0 16px 20px; }
    .profile-grid { flex-direction: column; gap: 4px; align-items: flex-start; }
    .profile-separator { display: none; }
    .profile-item { font-size: 13px; white-space: normal; flex-wrap: wrap; }
    .m-section-title { font-size: 11px; padding: 12px 0 8px; }
    .m-section-title svg { width: 16px; height: 16px; }
    .findings-textarea, .impression-textarea { font-size: 14px; min-height: 100px; padding: 12px; }
    .modal-footer { flex-direction: column; padding: 12px 16px; gap: 8px; }
    .btn-discard, .btn-save { width: 100%; justify-content: center; padding: 12px; }
    .upload-row-wrap { flex-direction: column; align-items: flex-start; }
    .confirm-modal-box { padding: 24px 20px 20px; }
    .confirm-modal-box h3 { font-size: 18px; }
    .confirm-modal-box p { font-size: 14px; }
    .confirm-actions { flex-direction: column; gap: 8px; }
    .btn-confirm { width: 100%; }
    .toast { bottom: 16px; right: 16px; left: 16px; font-size: 13px; padding: 12px 16px; }
  }

  @media (max-width: 480px) {
    .stat-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
    .stat-card { padding: 12px 10px; gap: 8px; }
    .stat-icon { width: 36px; height: 36px; }
    .stat-icon img { width: 24px !important; height: 24px !important; }
    .stat-num { font-size: 20px; }
    .page-title { font-size: 18px; }
    .badge { padding: 4px 8px; font-size: 11px; }
  }
</style>
@endpush

@section('content')

<div class="wrap">
  <div class="page-title">Medical Results</div>
  <div class="page-sub">List of all patient results.</div>

  {{-- Stat Cards --}}
  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-icon">
        <img src="{{ asset('images/microscope.png') }}" alt="Laboratory" style="width:40px;height:40px;">
      </div>
      <div>
        <div class="stat-label">Laboratory</div>
        <div class="stat-num" id="countLab">{{ $labCount ?? 0 }}</div>
        <div class="stat-desc">Lab Results</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <img src="{{ asset('images/x-ray.png') }}" alt="X-Ray" style="width:40px;height:40px;">
      </div>
      <div>
        <div class="stat-label">X-Ray</div>
        <div class="stat-num" id="countXray">{{ $xrayCount ?? 0 }}</div>
        <div class="stat-desc">X-Ray Results</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <img src="{{ asset('images/ultrasound (1).png') }}" alt="Ultrasound" style="width:40px;height:40px;">
      </div>
      <div>
        <div class="stat-label">Ultrasound</div>
        <div class="stat-num" id="countUltrasound">{{ $ultrasoundCount ?? 0 }}</div>
        <div class="stat-desc">Ultrasound Results</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <img src="{{ asset('images/all.png') }}" alt="All" style="width:40px;height:40px;">
      </div>
      <div>
        <div class="stat-label">Total Results</div>
        <div class="stat-num" id="countTotal">{{ $totalCount ?? 0 }}</div>
        <div class="stat-desc">All Medical Results</div>
      </div>
    </div>
  </div>

  {{-- QUEUE TABLE --}}
  <div class="toolbar">
    <div class="search-wrap">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      <input type="text" class="search-input" id="searchInput" placeholder="Search patient..." oninput="filterTable()">
    </div>
    <div class="filter-group"></div>
  </div>

  <div class="table-wrap">
    <div class="table-responsive">
      <table id="patientTable">
        <thead>
          <tr>
            <th>Patient ID</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Service</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @forelse($results as $result)
          <tr data-status="{{ $result->status }}"
              data-service="{{ $result->service_name }}"
              data-result-id="{{ $result->id }}"
              data-patient='@json($result->patient)'
              data-service-name="{{ $result->service_name }}"
              data-date="{{ $result->date_conducted ?? $result->created_at }}">
            <td class="pid">2026-{{ str_pad($result->patient_id, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $result->patient->last_name  ?? '—' }}</td>
            <td>{{ $result->patient->first_name ?? '—' }}</td>
            <td>{{ $result->patient->middle_name ?? '—' }}</td>
            <td>{{ $result->service_name }}</td>
            <td>
              @if($result->status === 'awaiting_result')
                <span class="badge awaiting">
                  <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Awaiting Result
                </span>
              @elseif($result->status === 'in_progress')
                <span class="badge processing">
                  <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
                  Processing
                </span>
              @else
                <span class="badge awaiting">{{ $result->status }}</span>
              @endif
            </td>
            <td>
              @if($result->status !== 'completed')
                <button class="btn-input" onclick="openInputModal(this)">
                  <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  Input Medical Result
                </button>
              @else
                <span class="no-action">—</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #9ca3af;">
                        <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <path d="M8 13H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M8 17H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="18" cy="18" r="3" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M20.5 20.5L22.5 22.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span>No medical result.</span>
                </div>
            </td>
        </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($results, 'firstItem'))
    <div class="pagination-row">
      <span>Showing {{ $results->firstItem() }} to {{ $results->lastItem() }} of {{ $results->total() }} records</span>
      <div class="page-btns">
        <button class="page-btn" {{ $results->onFirstPage() ? 'disabled' : '' }}
          onclick="window.location='{{ $results->previousPageUrl() }}'">‹</button>
        @for($i = 1; $i <= $results->lastPage(); $i++)
          <button class="page-btn {{ $results->currentPage() == $i ? 'active' : '' }}"
            onclick="window.location='{{ $results->url($i) }}'">{{ $i }}</button>
        @endfor
        <button class="page-btn" {{ !$results->hasMorePages() ? 'disabled' : '' }}
          onclick="window.location='{{ $results->nextPageUrl() }}'">›</button>
      </div>
    </div>
    @else
    <div class="pagination-row">
      <span>Showing {{ $results->count() }} records</span>
    </div>
    @endif
  </div>
</div>

{{-- ENCODING RESULT MODAL --}}
<div class="modal-overlay" id="encodingModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-header-title">Encoding Result</div>
      <button class="modal-back-btn-header" onclick="discardModalEntry()">Back</button>
    </div>
    <div class="modal-body">
      <div class="m-section-title">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Patient Information
      </div>
      <div class="profile-grid">
        <span class="profile-item"><span class="lbl">Name:</span> <span id="mPatientName">—</span></span>
        <span class="profile-separator">|</span>
        <span class="profile-item"><span class="lbl">Age:</span> <span id="mAge">—</span></span>
        <span class="profile-separator">|</span>
        <span class="profile-item"><span class="lbl">Gender:</span> <span id="mGender">—</span></span>
        <span class="profile-separator">|</span>
        <span class="profile-item"><span class="lbl">Service:</span> <span id="mServiceName">—</span></span>
        <span class="profile-item"><span class="lbl">Date:</span> <span id="mDateConducted">—</span></span>
      </div>
      <div class="m-section-title" style="margin-top:8px;">
        <svg viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><polyline points="9 11 11 13 15 9"/><line x1="9" y1="17" x2="15" y2="17"/></svg>
        Findings
      </div>
      <textarea class="findings-textarea" id="mFindings" placeholder="Enter findings here… (required)"></textarea>
      <div class="error-msg" id="findingsError">Findings is required.</div>
      <div class="m-section-title" style="margin-top:16px;">
        <svg viewBox="0 0 24 24"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0018 8 6 6 0 006 8c0 1.23.5 2.42 1.5 3.5.76.76 1.23 1.52 1.41 2.5h6.18z"/></svg>
        Impression
      </div>
      <textarea class="impression-textarea" id="mImpression" placeholder="Enter impression here… (required)"></textarea>
      <div class="error-msg" id="impressionError">Impression is required.</div>
      <div style="margin-top:16px;">
        <div class="upload-sub" style="margin-bottom:8px;">
          <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Upload Result Files
        </div>
        <div class="upload-row-wrap">
          <button class="choose-file-btn" id="chooseFileBtn" onclick="document.getElementById('mFileUpload').click()">Choose File</button>
          <span class="file-hint" id="mFileHint">No files chosen</span>
          <input type="file" id="mFileUpload" accept=".pdf,.jpg,.jpeg,.png,.gif" style="display:none;" onchange="handleFileSelect(this)" multiple>
        </div>
        <div class="file-list" id="fileList"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-discard" onclick="discardModalEntry()">
        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
        Discard
      </button>
      <button class="btn-save" id="btnSave" onclick="submitForm()">
        <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        Submit Result
      </button>
    </div>
  </div>
</div>

{{-- CONFIRMATION MODAL --}}
<div class="modal-overlay" id="confirmModal">
  <div class="confirm-modal-box">
    <div class="confirm-modal-icon info" id="confirmIcon">
      <svg viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
    </div>
    <h3 id="confirmTitle">Confirm Action</h3>
    <p id="confirmMessage">Are you sure you want to proceed?</p>
    <div class="confirm-actions" id="confirmActions"></div>
  </div>
</div>

{{-- Toast --}}
<div class="toast" id="toast">
  <svg id="toastIcon" viewBox="0 0 24 24"></svg>
  <span id="toastMsg"></span>
</div>

<script>
let currentResultId = null;
let selectedFiles = [];
const MAX_FILES = 2;

function classifyService(name) {
  const s = (name || '').toLowerCase();
  if (s.includes('lab') || s.includes('cbc') || s.includes('blood') || s.includes('urine') || s.includes('fecal') || s.includes('lipid') || s.includes('cholesterol') || s.includes('creatinine') || s.includes('hba1c') || s.includes('sgot') || s.includes('sgpt') || s.includes('uric') || s.includes('hepa') || s.includes('vdrl') || s.includes('dengue') || s.includes('pregnancy')) return 'laboratory';
  if (s.includes('ultrasound') || s.includes('abdomen') || s.includes('pelvic') || s.includes('transvaginal') || s.includes('transrectal') || s.includes('biophysical') || s.includes('breast') || s.includes('thyroid') || s.includes('scrotal')) return 'ultrasound';
  return 'xray';
}

function handleFileSelect(input) {
  const newFiles = Array.from(input.files);
  if (selectedFiles.length + newFiles.length > MAX_FILES) {
    showToast(`Maximum ${MAX_FILES} files allowed.`, 'error');
    input.value = ''; return;
  }
  newFiles.forEach(file => selectedFiles.push({ file }));
  input.value = '';
  renderFileList();
}
function removeFile(index) { selectedFiles.splice(index, 1); renderFileList(); }
function renderFileList() {
  const container = document.getElementById('fileList');
  const hint = document.getElementById('mFileHint');
  const btn  = document.getElementById('chooseFileBtn');
  if (selectedFiles.length === 0) {
    hint.textContent = 'No files chosen'; container.innerHTML = ''; btn.style.display = ''; return;
  }
  hint.textContent = `${selectedFiles.length} of ${MAX_FILES} files selected`;
  btn.style.display = selectedFiles.length >= MAX_FILES ? 'none' : '';
  container.innerHTML = selectedFiles.map((f, i) =>
    `<div class="file-list-item"><span>${f.file.name} (${formatFileSize(f.file.size)})</span>
     <svg viewBox="0 0 24 24" onclick="removeFile(${i})"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></div>`
  ).join('');
}
function formatFileSize(b) {
  if (!b) return '0 Bytes';
  const k = 1024, s = ['Bytes','KB','MB'], i = Math.floor(Math.log(b)/Math.log(k));
  return parseFloat((b/Math.pow(k,i)).toFixed(1)) + ' ' + s[i];
}
function clearAllFiles() {
  selectedFiles = [];
  document.getElementById('mFileUpload').value = '';
  document.getElementById('mFileHint').textContent = 'No files chosen';
  document.getElementById('fileList').innerHTML = '';
  document.getElementById('chooseFileBtn').style.display = '';
}
function clearErrors() {
  ['mFindings','mImpression'].forEach(id => document.getElementById(id).classList.remove('error'));
  ['findingsError','impressionError'].forEach(id => document.getElementById(id).style.display = 'none');
}

function openInputModal(button) {
  const row = button.closest('tr');
  if (!row) return;
  currentResultId = row.dataset.resultId || null;
  const p = JSON.parse(row.dataset.patient || '{}');
  const serviceName   = row.dataset.serviceName || '';
  const dateConducted = row.dataset.date || '';

  document.getElementById('mPatientName').textContent =
    `${p.last_name || row.cells[1]?.textContent?.trim() || '—'}, ${p.first_name || row.cells[2]?.textContent?.trim() || '—'} ${p.middle_name || ''}`.trim();
  document.getElementById('mServiceName').textContent = serviceName;
  document.getElementById('mAge').textContent    = p.age    || '—';
  document.getElementById('mGender').textContent = p.gender || '—';

  let dateStr = '—';
  if (dateConducted) {
    const d = new Date(dateConducted);
    if (!isNaN(d)) dateStr = d.toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'numeric', minute:'2-digit' });
  }
  document.getElementById('mDateConducted').textContent = dateStr;
  document.getElementById('mFindings').value = '';
  document.getElementById('mImpression').value = '';
  clearErrors(); clearAllFiles();
  document.getElementById('encodingModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeModal() {
  clearAllFiles(); clearErrors();
  document.getElementById('encodingModal').classList.remove('open');
  document.body.style.overflow = '';
  currentResultId = null;
}
document.getElementById('encodingModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });

function validateForm() {
  let ok = true;
  const findings   = document.getElementById('mFindings').value.trim();
  const impression = document.getElementById('mImpression').value.trim();
  clearErrors();
  if (!findings) {
    document.getElementById('mFindings').classList.add('error');
    document.getElementById('findingsError').style.display = 'block';
    document.getElementById('mFindings').focus(); ok = false;
  }
  if (!impression) {
    document.getElementById('mImpression').classList.add('error');
    document.getElementById('impressionError').style.display = 'block';
    if (ok) document.getElementById('mImpression').focus(); ok = false;
  }
  if (!ok) showToast('Please fill in all required fields.', 'error');
  return ok;
}
function discardModalEntry() {
  showConfirmModal({
    title:'Discard Entry?', message:'All unsaved changes will be lost.',
    icon:'warning', confirmText:'Yes, Discard', confirmClass:'btn-confirm-danger',
    onConfirm: () => { closeConfirmModal(); closeModal(); },
    onCancel:  () => closeConfirmModal()
  });
}
function submitForm() {
  if (!validateForm()) return;
  showConfirmModal({
    title:'Review Before Submitting',
    message:'Once submitted, the result will be transmitted to mobile.',
    icon:'info', confirmText:' Submit Result', confirmClass:'btn-confirm-primary',
    onConfirm: () => { closeConfirmModal(); saveResult(); },
    onCancel:  () => closeConfirmModal()
  });
}

async function saveResult() {
  if (!currentResultId) { showToast('No patient selected.', 'error'); return; }
  const btnSave = document.getElementById('btnSave');
  btnSave.disabled = true;
  btnSave.innerHTML = `<div class="spinner"></div> Saving...`;
  try {
    const findings    = document.getElementById('mFindings').value.trim();
    const impression  = document.getElementById('mImpression').value.trim();
    const serviceName = document.getElementById('mServiceName').textContent.trim();
    const patientName = document.getElementById('mPatientName').textContent.trim();
    const age         = document.getElementById('mAge').textContent.trim();
    const gender      = document.getElementById('mGender').textContent.trim();
    const row         = document.querySelector(`tr[data-result-id="${currentResultId}"]`);
    const pd          = row ? JSON.parse(row.dataset.patient || '{}') : {};
    const findingsJson = JSON.stringify({ findings_text: findings, impression, patient_name: patientName, age, gender });
    const serviceType  = classifyService(serviceName);
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('patient_id', pd.id || '');
    formData.append('service_type', serviceType);
    formData.append('findings', findingsJson);
    formData.append('doctor_remarks', impression);
    formData.append('service_name', serviceName);
    formData.append('status', 'completed');
    selectedFiles.forEach(f => {
      if (f.file.type === 'application/pdf') formData.append('pdf_file', f.file);
      else formData.append('files[]', f.file);
    });
    const res  = await fetch(`/staff1/api/result/${currentResultId}`, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: formData
    });
    const json = await res.json();
    if (json.success) {
      showConfirmModal({
        title:'Success!', message:'Result saved & transmitted to mobile!',
        icon:'success', confirmText:'OK', confirmClass:'btn-confirm-primary',
        onConfirm: () => { closeConfirmModal(); closeModal(); window.location.reload(); },
        onCancel: null
      });
    } else {
      showToast(json.message || 'Failed to save result.', 'error');
    }
  } catch(err) {
    showToast('Error: ' + err.message, 'error');
  } finally {
    btnSave.disabled = false;
    btnSave.innerHTML = `<svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:#fff;fill:none;stroke-width:2;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Submit Result`;
  }
}

function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#tableBody tr');
  rows.forEach(row => {
    if (row.cells.length === 1) return;
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(q) ? '' : 'none';
  });
}

function showConfirmModal({ title, message, icon, confirmText, confirmClass, onConfirm, onCancel }) {
  document.getElementById('confirmTitle').textContent   = title;
  document.getElementById('confirmMessage').textContent = message;
  const iconDiv = document.getElementById('confirmIcon');
  iconDiv.className = 'confirm-modal-icon ' + (icon || 'info');
  const svg = iconDiv.querySelector('svg');
  if (icon === 'success') svg.innerHTML = '<polyline points="20 6 9 17 4 12"/>';
  else if (icon === 'warning') svg.innerHTML = '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>';
  else if (icon === 'error') svg.innerHTML = '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
  else svg.innerHTML = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';
  const actionsDiv = document.getElementById('confirmActions');
  actionsDiv.innerHTML = '';
  if (onCancel) {
    const btn = document.createElement('button');
    btn.className = 'btn-confirm btn-confirm-secondary';
    btn.textContent = 'Cancel';
    btn.onclick = onCancel;
    actionsDiv.appendChild(btn);
  }
  const confirmBtn = document.createElement('button');
  confirmBtn.className = 'btn-confirm ' + (confirmClass || 'btn-confirm-primary');
  confirmBtn.textContent = confirmText || 'Confirm';
  confirmBtn.onclick = onConfirm;
  actionsDiv.appendChild(confirmBtn);
  document.getElementById('confirmModal').classList.add('active');
}
function closeConfirmModal() { document.getElementById('confirmModal').classList.remove('active'); }
document.getElementById('confirmModal').addEventListener('click', function(e) { if (e.target === this) closeConfirmModal(); });

let toastTimer;
function showToast(msg, type = 'success') {
  const toast = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  toast.className = `toast ${type}`;
  document.getElementById('toastIcon').innerHTML = type === 'success'
    ? '<polyline points="20 6 9 17 4 12"/>'
    : '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
  requestAnimationFrame(() => toast.classList.add('show'));
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove('show'), 3500);
}

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('mFindings').addEventListener('input', function() {
    if (this.value.trim()) { this.classList.remove('error'); document.getElementById('findingsError').style.display = 'none'; }
  });
  document.getElementById('mImpression').addEventListener('input', function() {
    if (this.value.trim()) { this.classList.remove('error'); document.getElementById('impressionError').style.display = 'none'; }
  });
});
</script>
@endsection