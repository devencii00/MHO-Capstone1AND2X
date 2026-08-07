@extends('layouts.app')

@section('content')

<div class="wrap">
    <!-- Page Title -->
    <div class="page-title">Ultrasound Records</div>
    <div class="page-sub">List of all ultrasound patient results.</div>

    <div class="container-fluid ultrasound-records-container" style="padding-top: 8px;">
        <!-- Main Card -->
        <div class="border-0 shadow-none card ultrasound-card">
            <div class="card-body ultrasound-body">

                <!-- Header Section with Search and Filters -->
                <div class="mb-4 header-row no-print">
                    <!-- Left: Search only -->
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input
                            type="text"
                            class="search-input"
                            id="searchInput"
                            placeholder="Search Patient ID, Name..."
                            autocomplete="off"
                        >
                    </div>

                    <!-- Right: Filters + Print + Reset -->
                    <div class="filters-group">
                        <!-- Status Filter -->
                        <select class="filter-select" id="statusFilter">
                            <option value="All">All Status</option>
                            <option value="awaiting_result">Awaiting Result</option>
                            <option value="completed">Completed</option>
                        </select>

                        <!-- Print Button -->
                        <button class="btn-print" id="printBtn" title="Print Table Only" onclick="printTable()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"/>
                                <path d="M6 12H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2"/>
                                <rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            Print
                        </button>

                        <!-- Reset Button -->
                        <button class="btn-reset" id="resetBtn" title="Reset Filters">
                            <i class="fas fa-undo-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-wrapper" id="printArea">
                    <table class="ultrasound-table" id="ultrasoundTable">
                        <thead>
                            <tr class="table-header">
                                <th>PATIENT ID</th>
                                <th>LAST NAME</th>
                                <th>FIRST NAME</th>
                                <th>MIDDLE NAME</th>
                                <th>SERVICES</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @php
                                // Group records by patient_id to avoid duplicates
                                $groupedRecords = $records->groupBy('patient_id');
                                
                                // Prepare data for JavaScript modal
                                $modalData = [];
                                foreach ($groupedRecords as $pId => $pRecords) {
                                    $modalData[$pId] = [];
                                    foreach ($pRecords as $rec) {
                                        $modalData[$pId][] = [
                                            'id' => $rec->id,
                                            'service_name' => $rec->service_name,
                                            'status' => $rec->status,
                                            'view_url' => route('staff1.results.ultrasound.view', $rec->id)
                                        ];
                                    }
                                }
                            @endphp

                            @forelse($groupedRecords as $patientId => $patientRecords)
                            @php
                                $firstRecord = $patientRecords->first();
                                $patient = $firstRecord->patient;
                                
                                // Get unique service names
                                $services = $patientRecords->pluck('service_name')->filter()->unique()->values();
                                
                                // Display only 1 service name
                                $displayService = $services->first() ?: 'N/A';
                                
                                // Determine overall status
                                $hasPending = $patientRecords->contains(function($record) {
                                    return $record->status !== 'completed';
                                });
                                $overallStatus = $hasPending ? 'awaiting_result' : 'completed';
                                
                                // Patient ID display
                                $displayPatientId = isset($patient->patient_id)
                                    ? $patient->patient_id
                                    : (isset($patient->id)
                                        ? date('Y') . '-' . str_pad($patient->id, 3, '0', STR_PAD_LEFT)
                                        : 'N/A');
                            @endphp
                            <tr data-status="{{ $overallStatus }}" data-patient-id="{{ $patientId }}">
                                <td>
                                    <span class="patient-id-link">{{ $displayPatientId }}</span>
                                </td>
                                <td>{{ $patient->last_name ?? 'N/A' }}</td>
                                <td>{{ $patient->first_name ?? 'N/A' }}</td>
                                <td>{{ $patient->middle_name ?? '' }}</td>
                                <td>{{ $displayService }}</td>
                                <td style="text-align: center;">
                                    <span class="status-badge status-{{ str_replace('_', '-', strtolower($overallStatus)) }}">
                                        {{ $overallStatus === 'awaiting_result' ? 'Awaiting Result' : 'Completed' }}
                                    </span>
                                </td>
                                <td style="text-align: center;" class="action-cell">
                                    <button onclick="openManageModal('{{ $patientId }}')" 
                                            class="btn-view-services no-print">
                                        
                                        View Services
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr id="emptyRow">
                                <td colspan="7" style="text-align: center; padding: 24px; color: #8C8C8C;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" style="color: #9ca3af;">
                                            <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                            <path d="M14 2V8H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                            <path d="M8 13H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M8 17H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                        <span>No ultrasound records found.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Showing count -->
                <div class="flex-wrap mt-4 d-flex align-items-center justify-content-between pagination-section no-print">
                    <p class="pagination-info" id="showingCount">
                        Showing all {{ $groupedRecords->count() }} records
                    </p>
                    <div id="noResultsMsg" style="display:none; color:#8C8C8C; font-size:13px;">
                        No matching records found.
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Manage Results Modal -->
<div id="manageModal" class="modal-overlay" style="display: none;" onclick="closeManageModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Patient Services</h3>
            <button class="modal-close" onclick="closeManageModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Services will be loaded here -->
        </div>
    </div>
</div>

<style>
    /* Page header */
    .wrap {
        background: transparent;
        min-height: 100vh;
        padding: 8px 0 40px;
        font-family: -apple-system, 'Segoe UI', sans-serif;
    }
    .page-title { font-size: 22px; font-weight: 800; color: #111827; }
    .page-sub   { font-size: 13px; color: #6b7280; margin-top: 2px; margin-bottom: 4px; }

    :root {
        --primary-blue: #1890FF;
        --awaiting-orange: #FFA500;
        --processing-blue: #1890FF;
        --completed-green: #52C41A;
        --border-light: #D9D9D9;
        --bg-light: #F5F5F5;
        --text-dark: #262626;
        --text-muted: #8C8C8C;
        --white: #FFFFFF;
        --green-500: #22c55e;
        --green-50: #f0fdf4;
        --shadow-light: 0 1px 2px 0 rgba(0, 0, 0, 0.03), 0 1px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px 0 rgba(0, 0, 0, 0.02);
    }

    .ultrasound-records-container { padding: 0; }
    .ultrasound-card { border-radius: 2px; background-color: var(--white); border: 1px solid #F0F0F0; box-shadow: var(--shadow-light); }
    .ultrasound-body { padding: 24px; }
    .header-row { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; }
    .search-wrapper { position: relative; display: flex; align-items: center; flex: 1; max-width: 380px; min-width: 240px; }
    .search-icon { position: absolute; left: 12px; color: var(--text-muted); font-size: 14px; pointer-events: none; z-index: 1; }
    .search-input { width: 100%; height: 36px; padding: 0 12px 0 34px; border: 1px solid var(--border-light); border-radius: 4px; font-size: 13px; font-family: inherit; background-color: var(--white); color: var(--text-dark); transition: border-color 0.2s, box-shadow 0.2s; }
    .search-input:focus { outline: none; border-color: var(--green-500); box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.15); }
    .search-input::placeholder { color: var(--text-muted); }
    .filters-group { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
    .filter-select { height: 36px; padding: 0 12px; border: 1px solid var(--border-light); border-radius: 4px; font-size: 13px; background-color: var(--white); color: var(--text-dark); cursor: pointer; transition: border-color 0.2s; min-width: 155px; font-family: inherit; }
    .filter-select:hover { border-color: var(--green-500); }
    .filter-select:focus { outline: none; border-color: var(--green-500); box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.15); }
    .btn-reset { height: 36px; width: 36px; padding: 0; background-color: var(--white); color: var(--text-muted); border: 1px solid var(--border-light); border-radius: 4px; font-size: 14px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .btn-reset:hover { color: var(--green-500); border-color: var(--green-500); background-color: var(--green-50); }
    .btn-print { height: 36px; padding: 0 14px; background-color: var(--white); color: var(--text-muted); border: 1px solid var(--border-light); border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; font-family: inherit; white-space: nowrap; }
    .btn-print:hover { color: var(--text-dark); border-color: var(--text-muted); background-color: var(--bg-light); }
    .table-wrapper { margin: 20px 0; border-radius: 4px; border: 1px solid #F0F0F0; overflow-x: auto; }
    .ultrasound-table { width: 100%; border-collapse: collapse; background-color: var(--white); }
    .table-header { background-color: var(--bg-light); border-bottom: 1px solid #F0F0F0; }
    .table-header th { padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 500; color: var(--text-dark); white-space: nowrap; }
    .table-header th:nth-child(6), .table-header th:nth-child(7) { text-align: center; }
    .ultrasound-table tbody tr { border-bottom: 1px solid #F0F0F0; transition: background-color 0.2s; }
    .ultrasound-table tbody tr:hover { background-color: #FAFAFA; }
    .ultrasound-table tbody td { padding: 12px 16px; font-size: 13px; color: var(--text-dark); }
    .patient-id-link { color: var(--primary-blue); text-decoration: none; font-weight: 500; }
    .patient-id-link:hover { text-decoration: underline; }
    .status-badge { display: flex; align-items: center; justify-content: center; margin: 0 auto; width: fit-content; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 500; white-space: nowrap; }
    .status-awaiting-result { background-color: #FFF7E6; color: #FA8C16; }
    .status-in-progress { background-color: #E6F7FF; color: #1890FF; }
    .status-completed { background-color: #F6FFED; color: #52C41A; }

    /* View Services button */
    .btn-view-services { display: inline-flex; align-items: center; justify-content: center; gap: 6px; margin: 0 auto; width: fit-content; padding: 6px 16px; background-color: var(--white); color: #1890FF; border: 1px solid #1890FF; border-radius: 4px; font-size: 12px; font-weight: 500; cursor: pointer; transition: all 0.2s; text-decoration: none; white-space: nowrap; font-family: inherit; }
    .btn-view-services:hover { background-color: #1890FF; color: var(--white); border-color: #1890FF; }

    .pagination-section { padding-top: 16px; border-top: 1px solid #F0F0F0; }
    .pagination-info { margin: 0; font-size: 13px; color: var(--text-muted); }

    /* Modal Styles */
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
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.3s ease;
    }
    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(-20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #F0F0F0;
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
        color: #8C8C8C;
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
        background: #F5F5F5;
        color: #262626;
    }
    .modal-body {
        padding: 24px;
    }
    .modal-service-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        border: 1px solid #F0F0F0;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    .modal-service-item:hover {
        border-color: #22c55e;
        background: #f0fdf4;
    }
    .modal-service-name {
        font-size: 14px;
        font-weight: 600;
        color: #262626;
        text-transform: uppercase;
    }
    .modal-service-status {
        font-size: 12px;
        font-weight: 500;
    }
    .modal-btn-input {
        padding: 8px 20px;
        background: #22c55e;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    .modal-btn-input:hover {
        background: #16a34a;
    }

    @media print {
        body * { visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 10px; }
        .no-print, .btn-view-services, .btn-print, .btn-reset, .header-row, .pagination-section, .sidebar, .navbar, aside, nav, header, .page-title, .page-sub, .modal-overlay { display: none !important; }
        .ultrasound-table th:last-child, .ultrasound-table td:last-child { display: none !important; }
        .ultrasound-card { border: none !important; box-shadow: none !important; }
        .ultrasound-body { padding: 0 !important; }
        .table-wrapper { border: 1px solid #000 !important; }
    }

    @media (max-width: 900px) {
        .header-row { flex-direction: column; align-items: stretch; }
        .search-wrapper { max-width: 100%; }
        .filters-group { flex-wrap: wrap; }
        .filter-select { min-width: 130px; flex: 1; }
        .modal-content { width: 95%; }
    }
    @media (max-width: 768px) {
        .page-title { font-size: 20px; }
        .page-sub { font-size: 12px; }
        .ultrasound-body { padding: 16px; }
        .filter-select, .btn-reset, .btn-print { font-size: 12px; height: 34px; }
        .search-input { height: 34px; font-size: 12px; }
        .ultrasound-table tbody td, .table-header th { padding: 8px 10px; font-size: 12px; }
        .pagination-section { flex-direction: column; gap: 12px; }
        .btn-view-services { padding: 4px 12px; font-size: 11px; }
        .modal-content { width: 100%; margin: 16px; }
    }
</style>

<script>
    // Records data prepared from PHP
    const allRecordsData = {!! json_encode($modalData) !!};

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetBtn = document.getElementById('resetBtn');
        const tableBody = document.getElementById('tableBody');
        const showingCount = document.getElementById('showingCount');
        const noResultsMsg = document.getElementById('noResultsMsg');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const statusValue = statusFilter.value;

            const rows = tableBody.querySelectorAll('tr');
            let visibleCount = 0;

            rows.forEach(function(row) {
                if (row.id === 'emptyRow') return;

                const text = row.innerText.toLowerCase();
                const rowStatus = row.getAttribute('data-status') || '';

                let show = true;

                if (searchTerm && !text.includes(searchTerm)) show = false;
                if (statusValue !== 'All' && rowStatus !== statusValue) show = false;

                if (show) { row.style.display = ''; visibleCount++; }
                else { row.style.display = 'none'; }
            });

            const totalRows = tableBody.querySelectorAll('tr:not(#emptyRow)').length;
            if (visibleCount === 0) {
                showingCount.style.display = 'none';
                noResultsMsg.style.display = 'block';
            } else {
                showingCount.style.display = '';
                noResultsMsg.style.display = 'none';
                showingCount.textContent = 'Showing ' + visibleCount + ' of ' + totalRows + ' records';
            }
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);

        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = 'All';
            filterTable();
        });

        function refreshTableData() {
            fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(res) { return res.text(); })
            .then(function(html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.getElementById('tableBody');
                if (newTableBody) {
                    tableBody.innerHTML = newTableBody.innerHTML;
                    filterTable();
                }
            })
            .catch(function(err) { console.error('Live refresh failed:', err); });
        }

        setInterval(refreshTableData, 5000);
    });

    function openManageModal(patientId) {
        const records = allRecordsData[patientId];
        if (!records) return;

        let modalHtml = '';
        records.forEach(function(record) {
            let statusBadge = '';
            let actionButton = '';

            if (record.status === 'completed') {
                statusBadge = '<span style="color:#52C41A;font-weight:500;">✓ Completed</span>';
                actionButton = '<span style="color:#52C41A;font-size:13px;font-weight:500;">Completed</span>';
            } else if (record.status === 'in_progress') {
                statusBadge = '<span style="color:#1890FF;font-weight:500;">◉ In Progress</span>';
                actionButton = '<a href="' + record.view_url + '" class="modal-btn-input">' +
                    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                    '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>' +
                    '<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>' +
                    '</svg> Continue Input' +
                  '</a>';
            } else {
                statusBadge = '<span style="color:#FA8C16;font-weight:500;">○ Awaiting Result</span>';
                actionButton = '<a href="' + record.view_url + '" class="modal-btn-input">' +
                    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                    '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>' +
                    '<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>' +
                    '</svg> Input Result' +
                  '</a>';
            }

            modalHtml += '' +
                '<div class="modal-service-item">' +
                    '<div>' +
                        '<div class="modal-service-name">' + (record.service_name || 'N/A') + '</div>' +
                        '<div class="modal-service-status" style="margin-top:4px;">' + statusBadge + '</div>' +
                    '</div>' +
                    '<div>' + actionButton + '</div>' +
                '</div>';
        });

        document.getElementById('modalBody').innerHTML = modalHtml;
        document.getElementById('manageModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeManageModal(event) {
        if (event && event.target !== document.getElementById('manageModal')) return;
        document.getElementById('manageModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('manageModal').style.display = 'none';
            document.body.style.overflow = '';
        }
    });

    function printTable() { window.print(); }
</script>
@endsection