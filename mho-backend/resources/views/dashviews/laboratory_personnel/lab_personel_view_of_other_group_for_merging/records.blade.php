@extends('layouts.app')
@section('content')

<div class="wrap">
    {{-- Page Header --}}
    <div class="page-title">Patient Records</div>
    <div class="page-sub">Manage and view patient records</div>

    <div class="container-fluid patient-records-container" style="padding-top: 8px;">
        {{-- Main Card --}}
        <div class="border-0 shadow-none card patient-card">
            <div class="card-body patient-body">

                {{-- Header Section: Title left, Search + Filters + Actions right --}}
                <div class="mb-4 header-row no-print">
                    {{-- Left: mini title --}}
                    <div class="mini-title">New Patients Record</div>

                    {{-- Right: Search + Filters + Print + Export --}}
                    <div class="filters-group">
                        <div class="search-wrapper">
                            <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input
                                type="text"
                                class="search-input"
                                id="searchInput"
                                placeholder="Search anything here"
                                autocomplete="off"
                                oninput="filterTable()"
                            >
                        </div>

                        {{-- Filters dropdown (contains the date filter) --}}
                        <div class="filters-dropdown-wrapper">
                            <button type="button" class="btn-filters" onclick="toggleFiltersDropdown()">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                                </svg>
                                Filters
                            </button>
                            <div class="filters-dropdown" id="filtersDropdown">
                                <label class="filters-label">Filter by date</label>
                                <input type="date" id="dateFilter" class="date-input" onchange="filterTable()">
                            </div>
                        </div>

                        {{-- Print Button --}}
                        <button class="btn-icon-plain" onclick="printTable()" title="Print">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 6 2 18 2 18 9"/>
                                <path d="M6 12H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2"/>
                                <rect x="6" y="14" width="12" height="8"/>
                            </svg>
                        </button>

                        {{-- Export / View All style button --}}
                        <button class="btn-view-all" onclick="openExportModal()">
                            Export
                        </button>
                    </div>
                </div>

                {{-- Results count indicator --}}
                <p class="results-count no-print" id="resultsCount"></p>

                {{-- Table Section --}}
                <div class="table-wrapper" id="printArea">
                    <table class="patient-table" id="dataTable">
                        <thead>
                            <tr class="table-header">
                                <th class="checkbox-col no-print">
                                    <input type="checkbox" class="row-checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                </th>
                                <th onclick="sortTable(1)">Sr# <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(2)">ID Code <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(3)">Patient Name <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(4)">D.O.B <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(5)">Gender <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(6)">Age <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(7)">Created Date <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(8)">Time <span class="sort-arrow">▾</span></th>
                                <th onclick="sortTable(9)">Status <span class="sort-arrow">▾</span></th>
                                <th class="no-print action-header">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTable">
                            @forelse($records as $record)
                                @php
                                    $firstName  = $record->first_name  ?? '';
                                    $lastName   = $record->last_name   ?? '';
                                    $middleName = $record->middle_name ?? '';
                                    $age        = $record->age         ?? '—';
                                    $fullName   = trim($firstName . ' ' . $middleName . ' ' . $lastName) ?: 'Unknown Patient';

                                    $year      = date('Y', strtotime($record->record_date ?? $record->created_at));
                                    $patientId = $record->patient_id
                                        ? $year . '-' . str_pad($record->patient_id, 3, '0', STR_PAD_LEFT)
                                        : '—';

                                    $dateSource = $record->record_date ?? $record->created_at;
                                    $recDate    = date('M d, Y', strtotime($dateSource));
                                    $recDateRaw = date('Y-m-d', strtotime($dateSource));

                                    // FIX: Time column used to always read from $record->created_at
                                    // directly. Carbon::parse(null) does NOT throw — it silently
                                    // falls back to "now", so whenever created_at was null/missing
                                    // the row showed the current time instead of blank/correct data,
                                    // which made the column look wrong/inconsistent with Date.
                                    // Now it uses the same $dateSource as the Date column (with a
                                    // record_date -> created_at fallback) and only formats when a
                                    // real value exists; otherwise it shows an em dash.
                                    $recTime = $dateSource ? \Carbon\Carbon::parse($dateSource)->format('h:i A') : '—';

                                    // D.O.B: check the common places this could live (record itself,
                                    // or a related patient/user record) so real data actually shows up.
                                    $dobRaw = $record->dob
                                        ?? $record->date_of_birth
                                        ?? $record->birthdate
                                        ?? $record->birth_date
                                        ?? optional($record->patient ?? null)->dob
                                        ?? optional($record->patient ?? null)->date_of_birth
                                        ?? optional($record->user ?? null)->dob
                                        ?? null;
                                    $dob = $dobRaw ? date('d/m/Y', strtotime($dobRaw)) : '—';

                                    // Gender: normalize whatever is stored down to a plain M or F.
                                    $genderRaw = $record->gender
                                        ?? $record->sex
                                        ?? optional($record->patient ?? null)->gender
                                        ?? optional($record->patient ?? null)->sex
                                        ?? optional($record->user ?? null)->gender
                                        ?? null;
                                    $genderLetter = strtoupper(substr(trim((string) $genderRaw), 0, 1));
                                    $gender = in_array($genderLetter, ['M', 'F']) ? $genderLetter : '—';

                                    $status     = $record->status ?? 'Pending';
                                    $statusClass = strtolower($status) === 'confirmed' ? 'status-confirmed' : (strtolower($status) === 'pending' ? 'status-pending' : 'status-default');

                                    // Initials for the avatar placeholder
                                    $initials = strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1));
                                @endphp
                                <tr class="record-row"
                                    data-id="{{ $record->id }}"
                                    data-patient-id="{{ $patientId }}"
                                    data-first-name="{{ $firstName }}"
                                    data-last-name="{{ $lastName }}"
                                    data-middle-name="{{ $middleName }}"
                                    data-patient-name="{{ $fullName }}"
                                    data-age="{{ $age }}"
                                    data-date="{{ $recDate }}"
                                    data-date-raw="{{ $recDateRaw }}"
                                    data-time="{{ $recTime }}">

                                    <td class="checkbox-col no-print">
                                        <input type="checkbox" class="row-checkbox">
                                    </td>
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <span class="patient-id-badge">{{ $patientId }}</span>
                                    </td>
                                    <td class="name-cell">
                                        <div class="patient-name-wrap">
                                            <span class="avatar-circle">{{ $initials ?: '—' }}</span>
                                            <span>{{ $fullName }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $dob }}</td>
                                    <td>{{ $gender }}</td>
                                    <td>{{ $age }}</td>
                                    <td>{{ $recDate }}</td>
                                    <td>{{ $recTime }}</td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">{{ $status }}</span>
                                    </td>
                                    <td class="no-print action-cell">
                                        <a href="{{ route('staff1.records.view', $record->id) }}"
                                           class="btn-view-records">
                                            {{-- <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg> --}}
                                            View Patient Records
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="empty-state">
                                        <div class="empty-content">
                                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                                <rect x="9" y="3" width="6" height="4" rx="1"/>
                                                <path d="M9 12h6M9 16h6"/>
                                            </svg>
                                            <p class="empty-title">No records found</p>
                                            <p class="empty-sub">Try a different search term or date.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer: Count + Pagination --}}
                <div class="pagination-section no-print">
                    <p class="pagination-info">
                        Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} records
                    </p>
                    @if ($records->hasPages())
                        <div class="pagination-controls">
                            @if ($records->onFirstPage())
                                <span class="pagination-disabled">‹</span>
                            @else
                                <a href="{{ $records->previousPageUrl() }}" class="pagination-link">‹</a>
                            @endif

                            @foreach ($records->getUrlRange(1, $records->lastPage()) as $page => $url)
                                @if ($page === $records->currentPage())
                                    <span class="pagination-active">{{ $page }}</span>
                                @elseif ($page <= 3 || $page === $records->lastPage())
                                    <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                @elseif ($page === 4 && $records->lastPage() > 4)
                                    <span class="pagination-dots">…</span>
                                @endif
                            @endforeach

                            @if ($records->hasMorePages())
                                <a href="{{ $records->nextPageUrl() }}" class="pagination-link">›</a>
                            @else
                                <span class="pagination-disabled">›</span>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ══════════ EXPORT MODAL ══════════ --}}
<div id="exportModal" class="modal-overlay" style="display: none;" onclick="closeExportModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Export Records</h3>
            <button class="modal-close" onclick="closeExportModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-desc">Choose your preferred export format.</p>
            <div class="export-grid">
                <button onclick="exportData('csv')" class="export-option">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                        <polyline points="13 2 13 9 20 9"/>
                    </svg>
                    <span>CSV</span>
                </button>
                <button onclick="exportData('excel')" class="export-option">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                        <polyline points="13 2 13 9 20 9"/>
                    </svg>
                    <span>Excel</span>
                </button>
                <button onclick="exportData('pdf')" class="export-option">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                        <polyline points="13 2 13 9 20 9"/>
                    </svg>
                    <span>PDF</span>
                </button>
                <button onclick="exportData('word')" class="export-option">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                        <polyline points="13 2 13 9 20 9"/>
                    </svg>
                    <span>Word</span>
                </button>
            </div>
            <button onclick="closeExportModal()" class="btn-cancel">Cancel</button>
        </div>
    </div>
</div>

{{-- ══════════ NO RECORDS MODAL ══════════ --}}
<div id="noRecordsModal" class="modal-overlay" style="display: none;" onclick="closeNoRecordsModal(event)">
    <div class="modal-content modal-small" onclick="event.stopPropagation()">
        <div class="modal-body text-center">
            <div class="no-records-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <h3 class="modal-title">No Records to Export</h3>
            <p class="modal-desc">There are no records available to export.</p>
            <button onclick="closeNoRecordsModal()" class="btn-primary">Got it</button>
        </div>
    </div>
</div>

<style>
    /* ============================================
       PAGE WRAPPER - GRAY BACKGROUND
    ============================================ */
    .wrap {
        background: #f3f4f6;
        min-height: 100vh;
        padding: 8px 0 40px;
        font-family: -apple-system, 'Segoe UI', sans-serif;
    }

    .page-title {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
    }

    .page-sub {
        font-size: 13px;
        color: #6b7280;
        margin-top: 2px;
        margin-bottom: 4px;
    }

    /* ============================================
       CONTAINER & CARD
    ============================================ */
    .patient-records-container {
        padding: 0;
    }

    .patient-card {
        border-radius: 14px;
        background-color: #ffffff;
        border: 1px solid #eef0f2;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px 0 rgba(0, 0, 0, 0.04);
    }

    .patient-body {
        padding: 22px 24px;
        background: #ffffff;
    }

    /* ============================================
       HEADER ROW - TITLE + SEARCH + FILTERS
    ============================================ */
    .header-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .mini-title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
    }

    .filters-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    /* Search */
    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        color: #9ca3af;
        pointer-events: none;
        z-index: 1;
    }

    .search-input {
        width: 220px;
        height: 38px;
        padding: 0 12px 0 34px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-family: inherit;
        background-color: #f9fafb;
        color: #1f2937;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: #22c55e;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
    }

    .search-input::placeholder {
        color: #9ca3af;
    }

    /* Filters button + dropdown */
    .filters-dropdown-wrapper {
        position: relative;
    }

    .btn-filters {
        height: 38px;
        padding: 0 16px;
        background-color: #ffffff;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: inherit;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .btn-filters:hover {
        border-color: #9ca3af;
        background-color: #f9fafb;
    }

    .filters-dropdown {
        display: none;
        position: absolute;
        top: 44px;
        right: 0;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 12px;
        z-index: 20;
        min-width: 200px;
    }

    .filters-dropdown.show {
        display: block;
    }

    .filters-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #9ca3af;
        margin-bottom: 6px;
    }

    .date-input {
        width: 100%;
        height: 36px;
        padding: 0 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        background-color: #ffffff;
        color: #1f2937;
        cursor: pointer;
    }

    .date-input:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
    }

    /* Plain icon button (print) */
    .btn-icon-plain {
        height: 38px;
        width: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon-plain:hover {
        color: #1f2937;
        border-color: #9ca3af;
        background-color: #f9fafb;
    }

    /* Green pill button - matches "View All" in the reference */
    .btn-view-all {
        height: 38px;
        padding: 0 20px;
        background-color: #22c55e;
        color: #ffffff;
        border: 1px solid #22c55e;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        white-space: nowrap;
    }

    .btn-view-all:hover {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    /* ============================================
       RESULTS COUNT
    ============================================ */
    .results-count {
        padding: 0 4px;
        margin-bottom: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
    }

    /* ============================================
       TABLE
    ============================================ */
    .table-wrapper {
        margin: 12px 0 20px;
        border-radius: 10px;
        border: 1px solid #eef0f2;
        overflow-x: auto;
        background: #ffffff;
    }

    .patient-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
    }

    .table-header {
        background-color: #f9fafb;
        border-bottom: 1px solid #eef0f2;
    }

    .table-header th {
        padding: 12px 14px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
    }

    .table-header th.checkbox-col,
    .table-header th.action-header {
        cursor: default;
    }

    .sort-arrow {
        font-size: 9px;
        color: #b0b6c0;
        margin-left: 2px;
    }

    .checkbox-col {
        width: 40px;
        text-align: center !important;
    }

    .row-checkbox {
        width: 16px;
        height: 16px;
        accent-color: #22c55e;
        cursor: pointer;
    }

    .action-header {
        text-align: center !important;
    }

    .patient-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.2s;
    }

    .patient-table tbody tr:hover {
        background-color: #f9fafb;
    }

    .patient-table tbody td {
        padding: 12px 14px;
        font-size: 13px;
        color: #374151;
        background: #ffffff;
        white-space: nowrap;
    }

    .patient-id-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        font-family: monospace;
        color: #374151;
        background-color: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
    }

    .name-cell {
        font-weight: 600;
        color: #111827;
    }

    .patient-name-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 50%;
        background-color: #eef2f6;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 20px;
        white-space: nowrap;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #b45309;
    }

    .status-confirmed {
        background-color: #dcfce7;
        color: #15803d;
    }

    .status-default {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .action-cell {
        text-align: center;
        vertical-align: middle;
        width: 160px;
        min-width: 160px;
    }

    /* ============================================
       VIEW RECORDS BUTTON - GREEN (unchanged)
    ============================================ */
    .btn-view-records {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 16px;
        background-color: #ffffff;
        color: #22c55e;
        border: 1.5px solid #22c55e;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        white-space: nowrap;
        font-family: inherit;
        width: 100%;
        max-width: 130px;
        margin: 0 auto;
    }

    .btn-view-records:hover {
        background-color: #22c55e;
        color: #ffffff;
        border-color: #22c55e;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
    }

    .btn-view-records:active {
        transform: translateY(0);
    }

    /* ============================================
       EMPTY STATE
    ============================================ */
    .empty-state {
        padding: 48px 32px;
        text-align: center;
        color: #8C8C8C;
        white-space: normal;
    }

    .empty-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .empty-title {
        font-size: 16px;
        font-weight: 600;
        color: #6b7280;
        margin: 0;
    }

    .empty-sub {
        font-size: 14px;
        color: #9ca3af;
        margin: 0;
    }

    /* ============================================
       PAGINATION
    ============================================ */
    .pagination-section {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
        gap: 12px;
    }

    .pagination-info {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pagination-link,
    .pagination-active,
    .pagination-disabled,
    .pagination-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        font-size: 14px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-link {
        color: #4b5563;
        border: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .pagination-link:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .pagination-active {
        color: #ffffff;
        background: #22c55e;
        border: 1px solid #22c55e;
        font-weight: 700;
    }

    .pagination-disabled {
        color: #d1d5db;
        border: 1px solid #f3f4f6;
        background: #f9fafb;
        cursor: not-allowed;
    }

    .pagination-dots {
        color: #6b7280;
        cursor: default;
    }

    /* ============================================
       MODAL
    ============================================ */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: #ffffff;
        border-radius: 12px;
        width: 90%;
        max-width: 420px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.3s ease;
    }

    .modal-small {
        max-width: 360px;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
        background: #ffffff;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #f3f4f6;
        color: #1f2937;
    }

    .modal-body {
        padding: 24px;
        background: #ffffff;
    }

    .modal-desc {
        font-size: 14px;
        color: #6b7280;
        margin: 0 0 20px 0;
    }

    .export-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .export-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .export-option:hover {
        border-color: #22c55e;
        background: #f0fdf4;
    }

    .export-option span {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
    }

    .btn-cancel,
    .btn-primary {
        width: 100%;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .btn-cancel {
        background: #ffffff;
        color: #6b7280;
    }

    .btn-cancel:hover {
        background: #f9fafb;
    }

    .btn-primary {
        background: #22c55e;
        color: #ffffff;
        border-color: #22c55e;
    }

    .btn-primary:hover {
        background: #16a34a;
        border-color: #16a34a;
    }

    .text-center {
        text-align: center;
    }

    .no-records-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        background: #f3f4f6;
        border-radius: 50%;
    }

    /* ============================================
       PRINT
    ============================================ */
    @media print {
        body * {
            visibility: hidden;
        }
        #printArea,
        #printArea * {
            visibility: visible;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 10px;
        }
        .no-print {
            display: none !important;
        }
        tr {
            break-inside: avoid;
        }
        .patient-table th:last-child,
        .patient-table td:last-child {
            display: none !important;
        }
        .patient-card {
            border: none !important;
            box-shadow: none !important;
        }
        .patient-body {
            padding: 0 !important;
        }
        .table-wrapper {
            border: 1px solid #000 !important;
        }
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 900px) {
        .header-row {
            flex-direction: column;
            align-items: stretch;
        }
        .filters-group {
            flex-wrap: wrap;
        }
        .search-input {
            width: 100%;
        }
        .modal-content {
            width: 95%;
        }
        .action-cell {
            width: auto;
            min-width: auto;
        }
        .btn-view-records {
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 20px;
        }
        .page-sub {
            font-size: 12px;
        }
        .patient-body {
            padding: 16px;
        }
        .btn-icon-plain,
        .btn-filters,
        .btn-view-all {
            font-size: 12px;
            height: 36px;
        }
        .search-input {
            height: 36px;
            font-size: 12px;
        }
        .patient-table tbody td,
        .table-header th {
            padding: 8px 10px;
            font-size: 12px;
        }
        .pagination-section {
            flex-direction: column;
            gap: 12px;
        }
        .btn-view-records {
            padding: 4px 12px;
            font-size: 11px;
        }
        .export-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .modal-content {
            width: 100%;
            margin: 16px;
        }
    }
</style>

<script>
    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const dateValue = document.getElementById('dateFilter').value;

        let visibleCount = 0;
        document.querySelectorAll('.record-row').forEach(row => {
            const matchesSearch = row.textContent.toLowerCase().includes(query);
            const matchesDate = !dateValue || row.dataset.dateRaw === dateValue;
            const show = matchesSearch && matchesDate;
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        const countEl = document.getElementById('resultsCount');
        countEl.textContent = (query || dateValue)
            ? `Showing ${visibleCount} matching record${visibleCount === 1 ? '' : 's'}`
            : '';
    }

    function printTable() {
        window.print();
    }

    function toggleFiltersDropdown() {
        document.getElementById('filtersDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function (e) {
        const wrapper = document.querySelector('.filters-dropdown-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('filtersDropdown').classList.remove('show');
        }
    });

    function toggleSelectAll(checkbox) {
        document.querySelectorAll('#recordsTable .row-checkbox').forEach(cb => {
            cb.checked = checkbox.checked;
        });
    }

    function sortTable(colIndex) {
        const tbody = document.getElementById('recordsTable');
        const rows = Array.from(tbody.querySelectorAll('.record-row'));
        if (!rows.length) return;

        const asc = tbody.dataset.sortCol == colIndex ? tbody.dataset.sortDir !== 'asc' : true;
        tbody.dataset.sortCol = colIndex;
        tbody.dataset.sortDir = asc ? 'asc' : 'desc';

        rows.sort((a, b) => {
            const aText = a.children[colIndex].textContent.trim().toLowerCase();
            const bText = b.children[colIndex].textContent.trim().toLowerCase();
            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);
            let cmp;
            if (!isNaN(aNum) && !isNaN(bNum)) {
                cmp = aNum - bNum;
            } else {
                cmp = aText.localeCompare(bText);
            }
            return asc ? cmp : -cmp;
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    function openExportModal() {
        document.getElementById('exportModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeExportModal(event) {
        if (event && event.target !== document.getElementById('exportModal')) return;
        document.getElementById('exportModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function openNoRecordsModal() {
        document.getElementById('noRecordsModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeNoRecordsModal(event) {
        if (event && event.target !== document.getElementById('noRecordsModal')) return;
        document.getElementById('noRecordsModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function getVisibleRowsData() {
        const rows = [];
        document.querySelectorAll('.record-row').forEach(row => {
            if (row.style.display !== 'none') {
                rows.push({
                    patientId: row.dataset.patientId || '—',
                    lastName: row.dataset.lastName || '—',
                    firstName: row.dataset.firstName || '—',
                    middleName: row.dataset.middleName || '—',
                    age: row.dataset.age || '—',
                    date: row.dataset.date || '—',
                    time: row.dataset.time || '—',
                });
            }
        });
        return rows;
    }

    function exportData(format) {
        const rows = getVisibleRowsData();
        if (!rows.length) {
            closeExportModal();
            openNoRecordsModal();
            return;
        }
        if (format === 'csv') exportCSV(rows);
        if (format === 'excel') exportExcel(rows);
        if (format === 'pdf') exportPDF(rows);
        if (format === 'word') exportWord(rows);
        closeExportModal();
    }

    function exportCSV(rows) {
        const headers = ['Patient ID', 'Last Name', 'First Name', 'Middle Name', 'Age', 'Date', 'Time'];
        const csv = [headers.join(','), ...rows.map(r =>
            [`"${r.patientId}"`, `"${r.lastName}"`, `"${r.firstName}"`, `"${r.middleName}"`, r.age, r.date, r.time].join(',')
        )].join('\n');
        downloadFile('patient_records.csv', csv, 'text/csv');
    }

    function exportExcel(rows) {
        let html = '<html><head><meta charset="UTF-8"></head><body><table border="1">' +
            '<tr style="background:#22c55e;color:white;font-weight:bold;">' +
            '<th>Patient ID</th><th>Last Name</th><th>First Name</th><th>Middle Name</th><th>Age</th><th>Date</th><th>Time</th></tr>';
        rows.forEach(r => html +=
            `<tr><td>${r.patientId}</td><td>${r.lastName}</td><td>${r.firstName}</td><td>${r.middleName}</td><td>${r.age}</td><td>${r.date}</td><td>${r.time}</td></tr>`
            );
        html += '</table></body></html>';
        downloadFile('patient_records.xls', html, 'application/vnd.ms-excel');
    }

    function exportPDF(rows) {
        let html = `<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>body{font-family:Arial,sans-serif;padding:24px;font-size:12px;}h2{color:#22c55e;margin-bottom:16px;}
        table{width:100%;border-collapse:collapse;}th{background:#22c55e;color:white;padding:10px 12px;text-align:left;}
        td{padding:9px 12px;border-bottom:1px solid #eee;}</style></head><body>
        <h2>Patient Records</h2><table><thead><tr>
        <th>Patient ID</th><th>Last Name</th><th>First Name</th><th>Middle Name</th><th>Age</th><th>Date</th><th>Time</th>
        </tr></thead><tbody>`;
        rows.forEach(r => html +=
            `<tr><td>${r.patientId}</td><td>${r.lastName}</td><td>${r.firstName}</td><td>${r.middleName}</td><td>${r.age}</td><td>${r.date}</td><td>${r.time}</td></tr>`
            );
        html += '</tbody></table></body></html>';
        const win = window.open('', '_blank');
        win.document.write(html);
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
            win.close();
        }, 500);
    }

    function exportWord(rows) {
        let html = '<html><head><meta charset="UTF-8"><style>body{font-family:Arial,sans-serif;}' +
            'table{width:100%;border-collapse:collapse;}th{background:#22c55e;color:white;padding:8px 10px;}' +
            'td{padding:7px 10px;border:1px solid #ddd;}</style></head><body>' +
            '<table><thead><tr><th>Patient ID</th><th>Last Name</th><th>First Name</th><th>Middle Name</th><th>Age</th><th>Date</th><th>Time</th></tr></thead><tbody>';
        rows.forEach(r => html +=
            `<tr><td>${r.patientId}</td><td>${r.lastName}</td><td>${r.firstName}</td><td>${r.middleName}</td><td>${r.age}</td><td>${r.date}</td><td>${r.time}</td></tr>`
            );
        html += '</tbody></table></body></html>';
        downloadFile('patient_records.doc', html, 'application/msword');
    }

    function downloadFile(filename, content, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('exportModal').style.display = 'none';
            document.getElementById('noRecordsModal').style.display = 'none';
            document.body.style.overflow = '';
        }
    });
</script>
@endsection