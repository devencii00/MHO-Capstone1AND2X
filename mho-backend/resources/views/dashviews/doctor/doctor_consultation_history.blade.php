<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900"></h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Combined records</span>
    </div>
    <p class="text-xs text-slate-500 mb-3">
       
    </p>

    {{-- Top row: Toggle + Today/Refresh --}}
    <div class="mb-2 flex items-center justify-between gap-2">
        <button type="button" id="consultHistMyToggle" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 px-3 py-1.5 text-[0.7rem] font-semibold transition-colors hover:bg-slate-50 hover:border-slate-300">
            <svg id="consultHistToggleIcon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span id="consultHistToggleText">My consults</span>
        </button>
        <div class="flex items-center gap-2">
            <button id="consultHistTodayOnlyBtn" type="button" class="shrink-0 inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.75rem] font-semibold text-slate-700">
                Show today only
            </button>
            <button type="button" id="consultHistRefreshBtn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2v6h-6"/><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M3 22v-6h6"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/></svg>
                Refresh
            </button>
        </div>
    </div>
    {{-- Bottom row: Search + Sort --}}
    <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
        <div class="flex-1">
            <label for="consultHistSearch" class="block text-[0.7rem] text-slate-600 mb-1">Search patient</label>
            <input id="consultHistSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Patient name or email">
        </div>
        <div class="w-full md:w-36">
            <label for="consultHistSort" class="block text-[0.7rem] text-slate-600 mb-1">Sort by last visit</label>
            <select id="consultHistSort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="desc">Newest first</option>
                <option value="asc">Oldest first</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto overflow-y-auto scrollbar-hidden" style="height:540px">
            <table class="text-xs" style="min-width:600px;width:100%;table-layout:auto;">
                <thead class="bg-slate-50 text-slate-600 sticky top-0">
                    <tr>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Visit date</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Patient</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Doctor</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Reason</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Diagnosis</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Notes</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Prescriptions</th>
                        <th class="text-right px-3 py-2 font-semibold whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="consultHistTbody" class="divide-y divide-slate-100 bg-white">
                    <tr>
                        <td colspan="8" class="py-6 text-center text-[0.78rem] text-slate-400">Loading&hellip;</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="consultHistMeta" class="px-3 py-2 text-[0.72rem] text-slate-500 bg-white border-t border-slate-100 flex items-center justify-between"></div>
        <div id="consultHistPagination" class="px-3 py-2 bg-white border-t border-slate-50 flex items-center justify-center gap-1"></div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- See Details & History Modal (2-panel) --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="consultHistDetailOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl h-[90vh] max-h-none rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex overflow-hidden">

        {{-- Left Panel: History list --}}
        <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
            <div id="consultHistDetailListSection">
                {{-- Header --}}
                <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Patient History</div>
                        <div id="consultHistDetailSubtitle" class="text-[0.72rem] text-slate-500">Loading&hellip;</div>
                    </div>
                    <button type="button" id="consultHistDetailClose" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                {{-- Filters --}}
                <div class="px-4 py-2 border-b border-slate-100 shrink-0 grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Date</label>
                        <input id="consultHistDetailDate" type="date" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Filter</label>
                        <select id="consultHistDetailFilter" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="all">All</option>
                            <option value="mine">My consults</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">&nbsp;</label>
                        <button type="button" id="consultHistDetailResetFilter" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-600 hover:bg-slate-50">Reset</button>
                    </div>
                </div>
                {{-- History items list --}}
                <div id="consultHistDetailList" class="flex-1 overflow-y-auto p-3 space-y-2" style="max-height:calc(90vh - 140px)">
                    <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history&hellip;</div>
                </div>
            </div>
        </div>

        {{-- Right Panel: Details --}}
        <div id="consultHistDetailPanel" class="w-1/2 flex flex-col min-h-0 bg-slate-50/50">
            <div class="px-4 py-3 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div class="flex items-center gap-2">
                    <div class="text-sm font-semibold text-slate-900">Consultation Details</div>
                    <span id="consultHistEditStatus" class="hidden text-[0.6rem] uppercase tracking-widest text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">Editing</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="consultHistSaveBtn" class="hidden rounded-lg border border-green-300 bg-green-600 px-3 py-1.5 text-[0.72rem] font-semibold text-white hover:bg-green-700">Save changes</button>
                    <button type="button" id="consultHistEditDetailsBtn" class="text-[0.7rem] font-semibold text-green-700 hover:text-green-800 underline underline-offset-2 hidden">Edit details</button>
                </div>
            </div>
            <div id="consultHistDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a visit to view details.</div>
            </div>
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- Edit Consultation Mini Modal --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="consultHistEditOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/70 items-center justify-center px-4 py-6">
    <div class="w-full max-w-2xl max-h-[90vh] rounded-3xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(15,23,42,0.35)] overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3 flex-shrink-0">
            <div>
                <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Edit Consultation</div>
                <div id="consultHistEditTitle" class="text-sm font-semibold text-slate-900">Modify clinical record</div>
                <div id="consultHistEditSubtitle" class="text-xs text-slate-500 mt-1">Update diagnosis, treatment notes, or prescription items.</div>
            </div>
            <button type="button" id="consultHistEditClose" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Close</button>
        </div>
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
            <div>
                <label for="consultHistEditDiagnosis" class="block text-[0.7rem] text-slate-600 mb-1">Diagnosis</label>
                <textarea id="consultHistEditDiagnosis" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none min-h-[70px]" placeholder="Enter diagnosis"></textarea>
            </div>
            <div>
                <label for="consultHistEditTreatment" class="block text-[0.7rem] text-slate-600 mb-1">Treatment notes</label>
                <textarea id="consultHistEditTreatment" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none min-h-[90px]" placeholder="Enter treatment notes"></textarea>
            </div>
            <div class="border-t border-slate-100 pt-3">
                <div class="flex items-center justify-between gap-3 mb-2">
                    <div>
                        <h4 class="text-xs font-semibold text-slate-900">Prescription items</h4>
                        <p class="text-[0.72rem] text-slate-500">Add or modify medicines.</p>
                    </div>
                    <button type="button" id="consultHistEditAddItem" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">+ Add medicine</button>
                </div>
                <div id="consultHistEditItemsList" class="space-y-2">
                    <div class="text-[0.78rem] text-slate-400 text-center py-4">No prescription items. Click "Add medicine" to start.</div>
                </div>
            </div>
            <div id="consultHistEditFeedback" class="hidden rounded-lg border px-3 py-2 text-[0.75rem]"></div>
        </div>
        <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-slate-100 flex-shrink-0">
            <button type="button" id="consultHistEditCancel" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[0.78rem] font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
            <button type="button" id="consultHistEditSave" class="rounded-xl border border-green-300 bg-green-600 px-5 py-2 text-[0.78rem] font-semibold text-white hover:bg-green-700 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400">Save changes</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- Mini Medicine Selector Modal --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="consultHistMedModal" class="hidden fixed inset-0 z-[70] bg-slate-900/70">
    <div class="absolute inset-0 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-3xl h-[80vh] rounded-2xl bg-white border border-slate-200 shadow-[0_20px_50px_rgba(15,23,42,0.35)] overflow-hidden grid grid-cols-1 md:grid-cols-2">
            {{-- Left Panel: Medicine List --}}
            <div class="flex flex-col border-r border-slate-100 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-100">
                    <div class="text-[0.78rem] font-semibold text-slate-900">Select Medicine</div>
                    <button type="button" id="consultHistMedClose" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50">Close</button>
                </div>
                <div class="px-4 py-2.5 border-b border-slate-50">
                    <div class="relative">
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="consultHistMedSearch" class="w-full rounded-lg border border-slate-200 bg-white pl-8 pr-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search medicine..." autocomplete="off">
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto px-4 py-2.5">
                    <div id="consultHistMedListBody" class="space-y-1">
                        <div class="text-[0.78rem] text-slate-400 text-center py-6">Loading medicines...</div>
                    </div>
                    <div class="pt-2 text-center">
                        <button type="button" id="consultHistMedLoadMore" class="text-[0.72rem] font-semibold text-green-700 underline underline-offset-2 hover:text-green-800 disabled:text-slate-300 disabled:no-underline disabled:cursor-not-allowed">See more</button>
                    </div>
                </div>
            </div>
            {{-- Right Panel: Medicine Details + Selected --}}
            <div class="flex flex-col overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-100">
                    <div class="text-[0.78rem] font-semibold text-slate-900">Medicine Details</div>
                </div>
                <div id="consultHistMedDetailBody" class="px-4 py-2.5 border-b border-slate-50">
                    <div class="text-[0.72rem] text-slate-500">Select a medicine from the list to view details.</div>
                </div>
                <div class="px-4 py-2 border-b border-slate-100">
                    <div class="text-[0.78rem] font-semibold text-slate-900">Selected</div>
                </div>
                <div class="flex-1 overflow-y-auto px-4 py-2.5">
                    <div id="consultHistMedSelectedBody" class="space-y-1.5">
                        <div class="text-[0.78rem] text-slate-400 text-center py-6">No medicines selected.</div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2 px-4 py-2.5 border-t border-slate-100">
                    <button type="button" id="consultHistMedClearBtn" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] font-semibold text-slate-600 hover:bg-slate-50">Clear</button>
                    <button type="button" id="consultHistMedConfirmBtn" class="rounded-lg border border-green-300 bg-green-600 px-4 py-1.5 text-[0.72rem] font-semibold text-white hover:bg-green-700">Confirm Selection</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    try { // debug: confirm script runs
        if (typeof window.apiFetch !== 'function') {
            document.getElementById('consultHistTbody').innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-red-500">Error: apiFetch is not available.</td></tr>';
            return;
        }
    } catch(e) {
        document.getElementById('consultHistTbody').innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-red-500">Script init error: ' + e.message + '</td></tr>';
        return;
    }
    var histCurrentPage = 1;
    var histPerPage = 10;
    var histVisibleCount = 5;
    var histLastPage = 1;
    var histTotal = 0;
    var histDoctorId = {{ (int) (($currentUser->user_id ?? 0)) }};
    var histMyConsultsOnly = false;
    var histTodayOnly = false;
    var histIsEditing = false;
    var histMedCurrentVisit = null;
    var histMedSelected = [];
    var histMedPage = 1;
    var histMedSearchTimer = null;
    var histTransactionId = '';
    var histPatientVisits = [];
    var histPatientId = '';

    var searchInput = document.getElementById('consultHistSearch');
    var sortSelect = document.getElementById('consultHistSort');
    var myToggle = document.getElementById('consultHistMyToggle');
    var tbody = document.getElementById('consultHistTbody');
    var pagEl = document.getElementById('consultHistPagination');

    var detailOverlay = document.getElementById('consultHistDetailOverlay');
    var detailClose = document.getElementById('consultHistDetailClose');
    var detailSubtitle = document.getElementById('consultHistDetailSubtitle');
    var detailFilter = document.getElementById('consultHistDetailFilter');
    var detailList = document.getElementById('consultHistDetailList');
    var detailBody = document.getElementById('consultHistDetailBody');

    var editOverlay = document.getElementById('consultHistEditOverlay');
    var editClose = document.getElementById('consultHistEditClose');
    var editCancel = document.getElementById('consultHistEditCancel');
    var editTitle = document.getElementById('consultHistEditTitle');
    var editSubtitle = document.getElementById('consultHistEditSubtitle');
    var editDiagnosis = document.getElementById('consultHistEditDiagnosis');
    var editTreatment = document.getElementById('consultHistEditTreatment');
    var editItemsList = document.getElementById('consultHistEditItemsList');
    var editAddItem = document.getElementById('consultHistEditAddItem');
    var editSave = document.getElementById('consultHistEditSave');
    var editFeedback = document.getElementById('consultHistEditFeedback');

    function esc(val) {
        return String(val == null ? '' : val)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function personName(person, fallback) {
        if (!person) return fallback || '';
        var parts = [person.firstname, person.middlename, person.lastname].filter(function(v) { return v && String(v).trim() !== ''; });
        return parts.length ? parts.join(' ') : (person.email || fallback || 'Unknown');
    }

    function fmtDate(iso) { return iso ? String(iso).replace('T', ' ').slice(0, 10) : '-'; }
    function fmtDT(iso) { return iso ? String(iso).replace('T', ' ').slice(0, 16) : '-'; }

    function loadData(page) {
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-slate-400 animate-pulse">Loading&hellip;</td></tr>';

        var params = '?per_page=' + histPerPage + '&page=' + page +
            '&sort=' + encodeURIComponent(sortSelect ? sortSelect.value : 'desc');

        if (histMyConsultsOnly) {
            params += '&doctor_id=' + histDoctorId;
        }
        if (histTodayOnly) {
            params += '&today_only=1';
        }

        var search = searchInput ? searchInput.value.trim() : '';
        if (search) params += '&search=' + encodeURIComponent(search);

        apiFetch("{{ url('/api/consultation-history') }}" + params, { method: 'GET' })
            .then(function(r) { return r.json(); })
            .then(function(result) {
                if (!result || !result.data) {
                    tbody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-slate-400">No records found.</td></tr>';
                    histTotal = 0; histLastPage = 1; renderPagination();
                    return;
                }
                var data = result.data;
                histCurrentPage = result.current_page || page;
                histLastPage = result.last_page || 1;
                histTotal = result.total || 0;

                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-slate-400">No records found.</td></tr>';
                } else {
                    var html = '';
                    data.forEach(function(tx) {
                        var appt = tx.appointment || {};
                        var patient = appt.patient || {};
                        var doctor = appt.doctor || {};
                        var pName = personName(patient, patient.email || 'Patient');
                        var pEmail = patient.email || '';
                        var dName = personName(doctor, doctor.email || 'Doctor');
                        var vDate = fmtDate(tx.visit_datetime || tx.transaction_datetime);
                        var reason = appt.reason_for_visit || '-';
                        var diag = tx.diagnosis || '';
                        var treat = tx.treatment_notes || '';

                        var prescs = Array.isArray(tx.prescriptions) ? tx.prescriptions : [];
                        var items = [];
                        prescs.forEach(function(p) {
                            if (Array.isArray(p.items)) p.items.forEach(function(i) {
                                var mn = i.medicine
                                    ? [i.medicine.generic_name, i.medicine.brand_name ? '(' + i.medicine.brand_name + ')' : ''].filter(Boolean).join(' ').trim()
                                    : (i.medicine_name || 'Medicine');
                                items.push(mn);
                            });
                        });
                        var prescDisp = items.length === 0 ? '<span class="text-[0.7rem] text-slate-400">-</span>' :
                            items.length === 1 ? esc(items[0]) :
                            esc(items[0]) + ' <span class="text-green-600 font-medium">...' + (items.length - 1) + ' more</span>';

                        var pid = patient.user_id != null ? patient.user_id : '';

                        html += '<tr class="divide-slate-100">' +
                            '<td class="px-3 py-2 text-[0.78rem] text-slate-500 whitespace-nowrap">' + esc(vDate) + '</td>' +
                            '<td class="px-3 py-2 text-[0.78rem] text-slate-700"><div class="font-medium">' + esc(pName) + '</div>' + (pEmail ? '<div class="text-[0.65rem] text-slate-400">' + esc(pEmail) + '</div>' : '') + '</td>' +
                            '<td class="px-3 py-2 text-[0.78rem] text-slate-500 whitespace-nowrap">' + esc(dName) + '</td>' +
                            '<td class="px-3 py-2 text-[0.78rem] text-slate-500 max-w-[7rem] truncate" title="' + esc(reason) + '">' + esc(reason.length > 40 ? reason.slice(0, 40) + '&hellip;' : reason) + '</td>' +
                            '<td class="px-3 py-2 text-[0.78rem] text-slate-500 max-w-[6rem] truncate">' + (diag ? esc(diag.length > 40 ? diag.slice(0, 40) + '&hellip;' : diag) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="px-3 py-2 text-[0.78rem] text-slate-500 max-w-[6rem] truncate">' + (treat ? esc(treat.length > 40 ? treat.slice(0, 40) + '&hellip;' : treat) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="px-3 py-2 text-[0.78rem] text-slate-500 max-w-[8rem] truncate">' + prescDisp + '</td>' +
                            '<td class="px-3 py-2 text-right whitespace-nowrap">' +
                            '<button type="button" class="consult-hist-view-btn inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1 text-[0.72rem] font-medium text-slate-700 hover:bg-slate-50"' +
                            ' data-tx-id="' + esc(tx.transaction_id) + '" data-pid="' + esc(pid) + '" data-pname="' + esc(pName) + '">' +
                            'See details &amp; history</button></td></tr>';
                    });
                    // Client-side today filter
                    if (histTodayOnly) {
                        var todayStr = new Date().toISOString().slice(0, 10);
                        data = data.filter(function(v) {
                            var vd = (v.visit_datetime || v.transaction_datetime || '').slice(0, 10);
                            return vd === todayStr;
                        });
                        histTotal = data.length;
                        histLastPage = Math.ceil(histTotal / histPerPage) || 1;
                        var startIdx = (histCurrentPage - 1) * histPerPage;
                        data = data.slice(startIdx, startIdx + histPerPage);
                    }

                    tbody.innerHTML = html;
                    tbody.querySelectorAll('.consult-hist-view-btn').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            openDetailModal(btn.getAttribute('data-tx-id'), btn.getAttribute('data-pid'), btn.getAttribute('data-pname'));
                        });
                    });
                }
                renderPagination();
            })
            .catch(function() {
                tbody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-slate-400">Failed to load records.</td></tr>';
                renderPagination();
            });
    }

    function renderPagination() {
        if (!pagEl) return;
        var tp = histLastPage;
        var b = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border ';
        var bi = b + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer';
        var bd = b + 'border-slate-200 text-slate-300 cursor-default';
        var ba = b + 'bg-green-600 text-white border-green-600';

        var metaEl = document.getElementById('consultHistMeta');
        if (metaEl) metaEl.innerHTML = '<span>' + histTotal + ' entries</span>';

        var html = '<button type="button" class="' + (histCurrentPage === 1 ? bd : bi) + '" data-p="prev"' + (histCurrentPage === 1 ? ' disabled' : '') + '>&lsaquo; Prev</button>';

        var ws = Math.max(1, histCurrentPage - Math.floor(histVisibleCount / 2));
        var we = Math.min(ws + histVisibleCount - 1, tp);
        if (we - ws + 1 < histVisibleCount) ws = Math.max(1, we - histVisibleCount + 1);
        for (var i = ws; i <= we; i++) {
            html += '<button type="button" class="' + (i === histCurrentPage ? ba : bi) + '" data-p="' + i + '">' + i + '</button>';
        }
        if (we < tp) html += '<button type="button" class="' + bi + '" data-p="ww">&hellip;</button>';
        html += '<button type="button" class="' + (histCurrentPage === tp ? bd : bi) + '" data-p="next"' + (histCurrentPage === tp ? ' disabled' : '') + '>Next &rsaquo;</button>';

        pagEl.innerHTML = html;
        pagEl.querySelectorAll('button[data-p]').forEach(function(el) {
            el.addEventListener('click', function() {
                var p = el.getAttribute('data-p');
                if (p === 'prev' && histCurrentPage > 1) { histCurrentPage--; }
                else if (p === 'next' && histCurrentPage < tp) { histCurrentPage++; }
                else if (p === 'ww') { histCurrentPage = Math.min(we + 1, tp); }
                else if (p !== 'prev' && p !== 'next') { histCurrentPage = parseInt(p, 10) || 1; }
                else return;
                loadData(histCurrentPage);
            });
        });
    }

    // ── Detail Modal ─────────────────────────────────────────────────
    function openDetailModal(txId, pId, pName) {
        histTransactionId = txId;
        histPatientId = pId;
        detailSubtitle.textContent = pName || 'Loading&hellip;';
        detailList.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history&hellip;</div>';
        detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a visit to view details.</div>';

        // Reset modal filters
        var detailDate = document.getElementById('consultHistDetailDate');
        if (detailDate) detailDate.value = '';
        if (detailFilter) detailFilter.value = 'all';

        detailOverlay.classList.remove('hidden');
        detailOverlay.classList.add('flex');

        if (pId) {
            var url = "{{ url('/api/visits') }}?per_page=50&patient_id=" + encodeURIComponent(pId);
            apiFetch(url, { method: 'GET' })
                .then(function(r) { return r.json(); })
                .then(function(result) {
                    var visits = [];
                    if (result && result.data) visits = Array.isArray(result.data) ? result.data : [];
                    visits.sort(function(a, b) {
                        var da = a.visit_datetime || a.transaction_datetime || '';
                        var db = b.visit_datetime || b.transaction_datetime || '';
                        return da < db ? 1 : (da > db ? -1 : 0);
                    });
                    histPatientVisits = visits;
                    detailSubtitle.textContent = (pName || 'Patient') + ' (' + visits.length + ' records)';
                    renderVisitHistoryList(visits, '', 'all');
                })
                .catch(function() {
                    detailList.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load history.</div>';
                });
        }
    }

    function closeDetailModal() {
        detailOverlay.classList.add('hidden');
        detailOverlay.classList.remove('flex');
        histTransactionId = '';
        histPatientId = '';
        histPatientVisits = [];
        histIsEditing = false;
        var editBtn = document.getElementById('consultHistEditDetailsBtn');
        var saveBtn = document.getElementById('consultHistSaveBtn');
        var editStatus = document.getElementById('consultHistEditStatus');
        if (editBtn) { editBtn.classList.add('hidden'); editBtn.textContent = 'Edit details'; }
        if (saveBtn) saveBtn.classList.add('hidden');
        if (editStatus) editStatus.classList.add('hidden');
    }

    function renderVisitHistoryList(visits, activeTxId, filterVal) {
        if (!detailList) return;

        var filtered = visits;
        if (filterVal === 'mine') {
            filtered = visits.filter(function(v) {
                var doc = v.appointment && v.appointment.doctor;
                return doc && String(doc.user_id) === String(histDoctorId);
            });
        }

        if (!filtered.length) {
            detailList.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">' +
                (filterVal === 'mine' ? 'No consultations handled by you.' : 'No visit records found.') +
                '</div>';
            return;
        }

        detailList.innerHTML = filtered.map(function(v) {
            var appt = v.appointment || {};
            var patient = appt.patient || {};
            var doctor = appt.doctor || {};
            var pName = personName(patient, patient.email || 'Patient');
            var dName = personName(doctor, 'Unknown');
            var vDate = fmtDate(v.visit_datetime || v.transaction_datetime);
            var diag = v.diagnosis || 'No diagnosis';
            var txId = v.transaction_id;
            var isActive = String(txId) === String(activeTxId);

            return '<button type="button" class="consult-hist-item w-full text-left rounded-xl border ' +
                (isActive ? 'border-green-400 bg-green-50' : 'border-slate-200 bg-white hover:border-green-300') +
                ' p-3 hover:shadow-sm transition-all cursor-pointer" data-tx-id="' + esc(txId) + '">' +
                '<div class="flex items-center justify-between mb-1">' +
                    '<span class="text-[0.78rem] font-semibold text-slate-800 truncate">' + esc(fmtDT(v.visit_datetime || v.transaction_datetime)) + '</span>' +
                    '<span class="inline-flex items-center gap-1.5 shrink-0">' +
                        '<span class="inline-flex items-center px-2 py-0.5 rounded text-[0.6rem] font-medium border border-green-200 bg-green-50 text-green-700">Completed</span>' +
                    '</span>' +
                '</div>' +
                '<div class="text-[0.72rem] text-purple-600 font-medium mb-1">Consulted by: ' + esc(dName) + '</div>' +
                '<div class="text-[0.68rem] text-slate-500 mb-1 truncate"><span class="font-medium">Diagnosis:</span> ' + esc(diag.length > 80 ? diag.slice(0, 80) + '&hellip;' : diag) + '</div>' +
                '<span class="text-[0.7rem] font-semibold text-green-700">View Details &rarr;</span>' +
                '</button>';
        }).join('');

        detailList.querySelectorAll('.consult-hist-item').forEach(function(item) {
            item.addEventListener('click', function() {
                var txId = item.getAttribute('data-tx-id');
                detailList.querySelectorAll('.consult-hist-item').forEach(function(el) {
                    el.classList.remove('border-green-400', 'bg-green-50');
                    el.classList.add('border-slate-200', 'bg-white');
                });
                item.classList.remove('border-slate-200', 'bg-white');
                item.classList.add('border-green-400', 'bg-green-50');
                loadVisitDetail(txId);
            });
        });
    }

    function loadVisitDetail(txId) {
        if (!detailBody || !txId) return;
        histIsEditing = false;
        detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading details&hellip;</div>';

        var editBtn = document.getElementById('consultHistEditDetailsBtn');
        var saveBtn = document.getElementById('consultHistSaveBtn');
        var editStatus = document.getElementById('consultHistEditStatus');
        if (editBtn) {
            editBtn.classList.add('hidden');
            editBtn.textContent = 'Edit details';
            editBtn.setAttribute('data-tx-id', txId);
        }
        if (saveBtn) saveBtn.classList.add('hidden');
        if (editStatus) editStatus.classList.add('hidden');

        apiFetch("{{ url('/api/visits') }}/" + encodeURIComponent(txId), { method: 'GET' })
            .then(function(r) { return r.json(); })
            .then(function(visit) {
                if (!visit || !visit.transaction_id) {
                    detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load details.</div>';
                    return;
                }
                renderDetailContent(visit, false);
                if (editBtn) editBtn.classList.remove('hidden');
            })
            .catch(function() {
                detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load details.</div>';
            });
    }

    function renderDetailContent(visit, isEditing) {
        if (!detailBody) return;
        isEditing = !!isEditing;
        var appt = visit.appointment || {};
        var patient = appt.patient || {};
        var doctor = appt.doctor || {};
        var pName = personName(patient, patient.email || 'Patient');
        var dName = personName(doctor, 'Doctor');
        var vDate = fmtDT(visit.visit_datetime || visit.transaction_datetime);
        var reason = appt.reason_for_visit || '-';
        var diagnosis = visit.diagnosis || '';
        var treatment = visit.treatment_notes || '';
        var services = Array.isArray(appt.services) ? appt.services : [];

        var svcHtml = services.length
            ? services.map(function(s) { return '<div class="text-[0.72rem] text-slate-700">' + esc(s.service_name || 'Service') + (s.description ? ' - ' + esc(s.description) : '') + (s.price != null ? ' (\u20B1' + s.price + ')' : '') + '</div>'; }).join('')
            : '<div class="text-[0.72rem] text-slate-400">-</div>';

        var prescs = Array.isArray(visit.prescriptions) ? visit.prescriptions : [];
        var prescHtml = '';

        if (!isEditing) {
            // Read-only prescriptions
            if (prescs.length) {
                prescs.forEach(function(rx) {
                    var items = Array.isArray(rx.items) ? rx.items : [];
                    prescHtml += '<div class="rounded-xl border border-slate-200 bg-white p-3 mb-2">' +
                        '<div class="flex items-center justify-between text-[0.7rem] text-slate-500 mb-1.5">' +
                            '<span class="font-medium text-slate-700">' + esc(fmtDT(rx.prescribed_datetime)) + '</span>' +
                            '<span>' + esc(personName(rx.doctor, 'Doctor')) + '</span>' +
                        '</div>' +
                        (rx.notes ? '<div class="text-[0.72rem] text-slate-600 mb-1.5">' + esc(rx.notes) + '</div>' : '') +
                        (items.length ? items.map(function(i) {
                            var mn = i.medicine
                                ? [i.medicine.generic_name, i.medicine.brand_name ? '(' + i.medicine.brand_name + ')' : ''].filter(Boolean).join(' ').trim()
                                : (i.medicine_name || 'Medicine');
                            return '<div class="flex items-center gap-2 text-[0.72rem] text-slate-700 border-b border-slate-50 py-1 last:border-0">' +
                                '<span class="font-medium w-28 truncate">' + esc(mn) + '</span>' +
                                (i.dosage ? '<span class="text-slate-500">' + esc(i.dosage) + '</span>' : '') +
                                (i.frequency ? '<span class="text-slate-500">' + esc(i.frequency) + '</span>' : '') +
                                (i.duration ? '<span class="text-slate-500">' + esc(i.duration) + '</span>' : '') +
                            '</div>';
                        }).join('') : '<div class="text-[0.72rem] text-slate-400">No items recorded.</div>') +
                    '</div>';
                });
            } else {
                prescHtml = '<div class="text-[0.78rem] text-slate-400">No prescriptions found.</div>';
            }
        } else {
            // Edit mode prescriptions
            prescHtml = '<div id="consultHistInlineItems" class="space-y-1.5">';
            var hasItems = false;
            if (prescs.length) {
                prescs.forEach(function(rx) {
                    var items = Array.isArray(rx.items) ? rx.items : [];
                    items.forEach(function(i) {
                        hasItems = true;
                        var mn = i.medicine
                            ? [i.medicine.generic_name, i.medicine.brand_name ? '(' + i.medicine.brand_name + ')' : ''].filter(Boolean).join(' ').trim()
                            : (i.medicine_name || 'Medicine');
                        prescHtml += '<div class="flex items-center gap-2 text-[0.72rem] border border-slate-200 rounded-lg bg-white px-3 py-2">' +
                            '<span class="font-medium flex-1 text-slate-700">' + esc(mn) + '</span>' +
                            (i.dosage ? '<span class="text-slate-500">' + esc(i.dosage) + '</span>' : '') +
                            (i.frequency ? '<span class="text-slate-500">' + esc(i.frequency) + '</span>' : '') +
                            '<button type="button" class="consult-hist-inline-remove text-rose-500 hover:text-rose-700 font-semibold" data-item-id="' + (i.prescription_item_id || '') + '">Remove</button>' +
                        '</div>';
                    });
                });
            }
            if (!hasItems) {
                prescHtml += '<div class="text-[0.78rem] text-slate-400 text-center py-3">No prescription items yet.</div>';
            }
            prescHtml += '</div>' +
                '<button type="button" id="consultHistInlineAddMed" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50 mt-2">+ Add medicine</button>';
        }

        detailBody.innerHTML =
            '<div class="space-y-3">' +
                // Visit Information
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Visit Information</div>' +
                    '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                        '<div class="text-slate-500">Patient</div><div class="text-slate-800 font-medium">' + esc(pName) + '</div>' +
                        '<div class="text-slate-500">Visit date</div><div class="text-slate-800 font-medium">' + esc(vDate) + '</div>' +
                        '<div class="text-slate-500">Doctor</div><div class="text-slate-800 font-medium">' + esc(dName) + '</div>' +
                    '</div>' +
                    '<div class="mt-2 flex items-center gap-2">' +
                        '<span class="inline-flex items-center px-2 py-0.5 rounded text-[0.6rem] font-medium border border-green-200 bg-green-50 text-green-700">Completed</span>' +
                    '</div>' +
                '</div>' +
                // Services
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Services</div>' +
                    svcHtml +
                '</div>' +
                // Reason for Visit
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Reason for Visit</div>' +
                    '<div class="text-[0.78rem] text-slate-700">' + esc(reason) + '</div>' +
                '</div>' +
                // Diagnosis (separate)
                (isEditing
                    ? '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Diagnosis</div>' +
                        '<textarea id="consultHistInlineDiag" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none min-h-[60px]" placeholder="Enter diagnosis">' + esc(diagnosis) + '</textarea>' +
                    '</div>'
                    : '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Diagnosis</div>' +
                        '<div class="text-[0.78rem] text-slate-700 whitespace-pre-line">' + esc(diagnosis || '-') + '</div>' +
                    '</div>'
                ) +
                // Treatment Notes (separate)
                (isEditing
                    ? '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Treatment Notes</div>' +
                        '<textarea id="consultHistInlineTreat" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none min-h-[80px]" placeholder="Enter treatment notes">' + esc(treatment) + '</textarea>' +
                    '</div>'
                    : '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Treatment Notes</div>' +
                        '<div class="text-[0.78rem] text-slate-700 whitespace-pre-line">' + esc(treatment || '-') + '</div>' +
                    '</div>'
                ) +
                // Prescriptions
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions</div>' +
                    prescHtml +
                '</div>' +
            '</div>';

        // Wire up edit mode handlers
        if (isEditing) {
            var addMed = document.getElementById('consultHistInlineAddMed');
            if (addMed) addMed.addEventListener('click', function() {
                histMedCurrentVisit = visit;
                openHistMedicineSelector();
            });
            var removeBtns = detailBody.querySelectorAll('.consult-hist-inline-remove');
            removeBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var itemId = this.getAttribute('data-item-id');
                    if (itemId) {
                        apiFetch("{{ url('/api/prescription-items') }}/" + encodeURIComponent(itemId), { method: 'DELETE' })
                            .then(function() {
                                // Re-render edit view after removal
                                var curTxId = editBtn ? editBtn.getAttribute('data-tx-id') : '';
                                if (curTxId) loadVisitDetail(curTxId);
                            })
                            .catch(function() {});
                    }
                });
            });
        }
    }

    // ── Edit Modal ───────────────────────────────────────────────────
    var editOriginalItems = [];

    function openEditModal(txId) {
        if (!txId) return;
        apiFetch("{{ url('/api/visits') }}/" + encodeURIComponent(txId), { method: 'GET' })
            .then(function(r) { return r.json(); })
            .then(function(visit) {
                if (!visit || !visit.transaction_id) return;
                populateEditForm(visit);
                editOverlay.classList.remove('hidden');
                editOverlay.classList.add('flex');
            })
            .catch(function() {});
    }

    function populateEditForm(visit) {
        editTitle.textContent = 'Edit Consultation #' + visit.transaction_id;
        var patient = visit.appointment && visit.appointment.patient || {};
        editSubtitle.textContent = personName(patient, patient.email || 'Patient');
        editDiagnosis.value = visit.diagnosis || '';
        editTreatment.value = visit.treatment_notes || '';
        editSave.setAttribute('data-tx-id', visit.transaction_id);

        var prescs = Array.isArray(visit.prescriptions) ? visit.prescriptions : [];
        editOriginalItems = [];
        prescs.forEach(function(rx) {
            if (Array.isArray(rx.items)) rx.items.forEach(function(item) {
                editOriginalItems.push({ itemId: String(item.item_id), prescriptionId: String(rx.prescription_id) });
            });
        });
        renderEditItems(prescs);
    }

    function renderEditItems(prescs) {
        if (!editItemsList) return;
        var allItems = [];
        prescs.forEach(function(rx) {
            if (Array.isArray(rx.items)) rx.items.forEach(function(item) {
                allItems.push({
                    prescription_id: rx.prescription_id,
                    item_id: item.item_id,
                    medicine_name: item.medicine
                        ? [item.medicine.generic_name, item.medicine.brand_name ? '(' + item.medicine.brand_name + ')' : ''].filter(Boolean).join(' ').trim()
                        : (item.medicine_name || 'Medicine'),
                    dosage: item.dosage || '',
                    frequency: item.frequency || '',
                    duration: item.duration || '',
                    instructions: item.instructions || '',
                });
            });
        });

        if (!allItems.length) {
            editItemsList.innerHTML = '<div class="text-[0.78rem] text-slate-400 text-center py-4">No prescription items. Click "Add medicine" to start.</div>';
            return;
        }

        editItemsList.innerHTML = allItems.map(function(item, idx) {
            return '<div class="rounded-xl border border-slate-200 bg-white p-3 edit-item-row" data-item-id="' + esc(item.item_id || '') + '" data-prescription-id="' + esc(item.prescription_id || '') + '">' +
                '<div class="flex items-start justify-between gap-2 mb-2">' +
                    '<div class="flex-1 min-w-0"><div class="text-[0.72rem] font-semibold text-slate-800 truncate">' + esc(item.medicine_name) + '</div></div>' +
                    '<button type="button" class="edit-item-remove rounded-lg border border-rose-200 bg-rose-50 px-2 py-1 text-[0.65rem] font-medium text-rose-700 hover:bg-rose-100">Remove</button>' +
                '</div>' +
                '<div class="grid grid-cols-3 gap-2">' +
                    '<div><label class="block text-[0.6rem] text-slate-500 mb-0.5">Dosage</label><input type="text" class="edit-item-dosage w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" value="' + esc(item.dosage) + '"></div>' +
                    '<div><label class="block text-[0.6rem] text-slate-500 mb-0.5">Frequency</label><input type="text" class="edit-item-frequency w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" value="' + esc(item.frequency) + '"></div>' +
                    '<div><label class="block text-[0.6rem] text-slate-500 mb-0.5">Duration</label><input type="text" class="edit-item-duration w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" value="' + esc(item.duration) + '"></div>' +
                '</div>' +
                '<div class="mt-2"><label class="block text-[0.6rem] text-slate-500 mb-0.5">Instructions</label><input type="text" class="edit-item-instructions w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" value="' + esc(item.instructions) + '"></div>' +
            '</div>';
        }).join('');

        editItemsList.querySelectorAll('.edit-item-remove').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var row = btn.closest('.edit-item-row');
                if (row) row.remove();
                if (!editItemsList.querySelector('.edit-item-row')) {
                    editItemsList.innerHTML = '<div class="text-[0.78rem] text-slate-400 text-center py-4">No prescription items. Click "Add medicine" to start.</div>';
                }
            });
        });
    }

    function saveEdit() {
        var txId = editSave.getAttribute('data-tx-id');
        if (!txId) return;
        var diagnosis = editDiagnosis ? editDiagnosis.value.trim() : '';
        var treatment = editTreatment ? editTreatment.value.trim() : '';
        editSave.disabled = true;
        editSave.textContent = 'Saving...';

        var rows = editItemsList ? Array.prototype.slice.call(editItemsList.querySelectorAll('.edit-item-row')) : [];
        var currentItems = rows.map(function(row) {
            return {
                itemId: row.getAttribute('data-item-id') || '',
                prescriptionId: row.getAttribute('data-prescription-id') || '',
                name: (row.querySelector('.edit-item-name') || {}).value || '',
                dosage: (row.querySelector('.edit-item-dosage') || {}).value || '',
                frequency: (row.querySelector('.edit-item-frequency') || {}).value || '',
                duration: (row.querySelector('.edit-item-duration') || {}).value || '',
                instructions: (row.querySelector('.edit-item-instructions') || {}).value || '',
            };
        });

        apiFetch("{{ url('/api/transactions') }}/" + encodeURIComponent(txId), {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ diagnosis: diagnosis || null, treatment_notes: treatment || null })
        })
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (!result || result.error) throw new Error('Failed to update.');
            var deleted = editOriginalItems.filter(function(o) {
                return o.itemId && String(o.itemId).indexOf('new_') !== 0 &&
                    !currentItems.some(function(c) { return String(c.itemId) === String(o.itemId); });
            });
            var newItems = currentItems.filter(function(c) { return String(c.itemId).indexOf('new_') === 0; });
            var promises = [];
            deleted.forEach(function(item) {
                promises.push(apiFetch("{{ url('/api/prescription-items') }}/" + encodeURIComponent(item.itemId), { method: 'DELETE' }).then(function(r) { return r.json(); }).catch(function() { return null; }));
            });
            var prescId = currentItems.length > 0 ? currentItems[0].prescriptionId : '';
            newItems.forEach(function(item) {
                if (!item.name) return;
                promises.push(apiFetch("{{ url('/api/prescription-items') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        prescription_id: prescId || null,
                        medicine_name: item.name,
                        dosage: item.dosage || null,
                        frequency: item.frequency || null,
                        duration: item.duration || null,
                        instructions: item.instructions || null,
                    })
                }).then(function(r) { return r.json(); }).catch(function() { return null; }));
            });
            return Promise.all(promises).then(function() {
                showEditFeedback('Consultation updated successfully.', 'success');
                editSave.disabled = false;
                editSave.textContent = 'Save changes';
                closeEditModal();
                if (detailBody && detailBody.querySelector('.space-y-3')) loadVisitDetail(txId);
                loadData(histCurrentPage);
            });
        })
        .catch(function(err) {
            showEditFeedback(err && err.message ? err.message : 'Failed to update.', 'error');
            editSave.disabled = false;
            editSave.textContent = 'Save changes';
        });
    }

    function showEditFeedback(msg, type) {
        if (!editFeedback) return;
        editFeedback.classList.remove('hidden', 'border-green-200', 'bg-green-50', 'text-green-700', 'border-red-200', 'bg-red-50', 'text-red-700');
        editFeedback.classList.add(type === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
        editFeedback.textContent = msg;
        setTimeout(function() { editFeedback.classList.add('hidden'); }, 4000);
    }

    function closeEditModal() {
        editOverlay.classList.add('hidden');
        editOverlay.classList.remove('flex');
        if (editFeedback) editFeedback.classList.add('hidden');
    }

    // ── Mini Medicine Selector ───────────────────────────────────────
    var histMedModal = document.getElementById('consultHistMedModal');
    var histMedListBody = document.getElementById('consultHistMedListBody');
    var histMedSelectedBody = document.getElementById('consultHistMedSelectedBody');
    var histMedSearch = document.getElementById('consultHistMedSearch');
    var histMedLoadMore = document.getElementById('consultHistMedLoadMore');
    var histMedDetailBody = document.getElementById('consultHistMedDetailBody');

    function openHistMedicineSelector() {
        if (!histMedModal) return;
        histMedSelected = [];
        histMedPage = 1;
        histMedicinesById = {};
        if (histMedSearch) histMedSearch.value = '';
        if (histMedDetailBody) histMedDetailBody.innerHTML = '<div class="text-[0.72rem] text-slate-500">Select a medicine from the list to view details.</div>';
        renderHistMedSelected();
        histMedModal.classList.remove('hidden');
        histMedModal.classList.add('flex');
        loadHistMedicines(true);
        setTimeout(function() { if (histMedSearch) histMedSearch.focus(); }, 150);
    }

    function closeHistMedicineSelector() {
        if (histMedModal) { histMedModal.classList.add('hidden'); histMedModal.classList.remove('flex'); }
        if (histMedSearchTimer) clearTimeout(histMedSearchTimer);
    }

    var histMedicinesById = {};

    function loadHistMedicines(reset) {
        if (!histMedListBody) return;
        if (reset) { histMedPage = 1; histMedListBody.innerHTML = '<div class="text-[0.78rem] text-slate-400 text-center py-6">Loading medicines...</div>'; }
        var query = histMedSearch ? histMedSearch.value.trim() : '';
        var url = "{{ url('/api/medicines') }}?page=" + histMedPage + "&per_page=10" + (query ? '&search=' + encodeURIComponent(query) : '');
        if (histMedLoadMore) { histMedLoadMore.disabled = true; histMedLoadMore.textContent = 'Loading...'; }
        apiFetch(url, { method: 'GET' })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var medicines = data.data || data.medicines || [];
                if (reset) {
                    histMedListBody.innerHTML = '';
                }
                if (!medicines.length && reset) {
                    histMedListBody.innerHTML = '<div class="text-[0.78rem] text-slate-400 text-center py-6">No medicines found.</div>';
                    if (histMedLoadMore) { histMedLoadMore.disabled = true; histMedLoadMore.classList.add('hidden'); }
                    return;
                }
                medicines.forEach(function(m) {
                    histMedicinesById[m.medicine_id] = m;
                    var id = m.medicine_id;
                    var name = m.brand_name || m.generic_name || 'Unknown';
                    var generic = m.generic_name ? '<span class="text-[0.65rem] text-slate-400">' + esc(m.generic_name) + '</span>' : '';
                    var isSelected = histMedSelected.some(function(s) { return String(s.medicine_id) === String(id); });
                    var selClasses = isSelected ? 'border-green-400 bg-green-50/60' : 'border-slate-100 hover:border-slate-300 hover:bg-slate-50';
                    var el = document.createElement('button');
                    el.type = 'button';
                    el.className = 'med-item w-full text-left rounded-lg border px-2.5 py-2 transition-all duration-100 ' + selClasses;
                    el.setAttribute('data-med-id', id);
                    el.innerHTML = '<div class="text-[0.75rem] font-medium text-slate-800">' + esc(name) + '</div>' +
                        '<div class="mt-0.5 text-[0.65rem] text-slate-500">' + (isSelected ? '<span class="text-green-700 font-medium">Selected</span> ' : '') + generic + '</div>';
                    el.addEventListener('click', function() {
                        var mid = this.getAttribute('data-med-id');
                        renderHistMedicineDetail(histMedicinesById[mid]);
                        var idx = histMedSelected.findIndex(function(s) { return String(s.medicine_id) === String(mid); });
                        if (idx > -1) {
                            histMedSelected.splice(idx, 1);
                        } else {
                            histMedSelected.push(histMedicinesById[mid]);
                        }
                        loadHistMedicines(true);
                        renderHistMedSelected();
                    });
                    histMedListBody.appendChild(el);
                });
                var hasMore = data.next_page_url ? true : false;
                if (histMedLoadMore) {
                    histMedLoadMore.disabled = !hasMore;
                    histMedLoadMore.textContent = 'See more';
                    histMedLoadMore.onclick = function() {
                        if (!histMedLoadMore.disabled) { histMedPage++; loadHistMedicines(false); }
                    };
                }
            })
            .catch(function() {
                if (reset) histMedListBody.innerHTML = '<div class="text-[0.78rem] text-slate-400 text-center py-6">Failed to load medicines.</div>';
            });
    }

    function renderHistMedicineDetail(med) {
        if (!histMedDetailBody || !med) {
            if (histMedDetailBody) histMedDetailBody.innerHTML = '<div class="text-[0.72rem] text-slate-500">Select a medicine from the list to view details.</div>';
            return;
        }
        var name = med.brand_name || med.generic_name || 'Unknown';
        var generic = med.generic_name || '-';
        var dosageForm = med.dosage_form || '-';
        var strength = med.strength || '-';
        var indications = med.indications || '-';
        var contra = med.contraindications || '-';
        histMedDetailBody.innerHTML =
            '<div class="rounded-xl border border-slate-100 bg-white p-3 space-y-2">' +
                '<div class="text-[0.78rem] font-semibold text-slate-800">' + esc(name) + '</div>' +
                '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.7rem]">' +
                    '<div><span class="text-slate-400">Generic:</span><br><span class="text-slate-700">' + esc(generic) + '</span></div>' +
                    '<div><span class="text-slate-400">Dosage Form:</span><br><span class="text-slate-700">' + esc(dosageForm) + '</span></div>' +
                    '<div><span class="text-slate-400">Strength:</span><br><span class="text-slate-700">' + esc(strength) + '</span></div>' +
                '</div>' +
                '<div class="text-[0.7rem]"><span class="text-slate-400">Indications:</span><br><span class="text-slate-600">' + esc(indications) + '</span></div>' +
                '<div class="text-[0.7rem]"><span class="text-slate-400">Contraindications:</span><br><span class="text-slate-600">' + esc(contra) + '</span></div>' +
            '</div>';
    }

    function renderHistMedSelected() {
        if (!histMedSelectedBody) return;
        if (!histMedSelected.length) {
            histMedSelectedBody.innerHTML = '<div class="text-[0.78rem] text-slate-400 text-center py-6">No medicines selected.</div>';
            return;
        }
        var html = '';
        histMedSelected.forEach(function(m) {
            var drugName = m.brand_name || m.generic_name || 'Unknown';
            var genericName = m.generic_name && m.generic_name !== drugName ? m.generic_name : '';
            html += '<div class="flex items-center justify-between rounded-xl border border-green-200 bg-green-50/40 px-3 py-2.5">' +
                '<button type="button" class="med-selected-item min-w-0 flex-1 text-left" data-med-id="' + m.medicine_id + '">' +
                    '<div class="text-[0.75rem] font-medium text-slate-700 truncate hover:text-green-700">' + esc(drugName) + '</div>' +
                    (genericName ? '<div class="text-[0.68rem] text-slate-400">' + esc(genericName) + '</div>' : '<div class="text-[0.68rem] text-slate-400">Click to view details</div>') +
                '</button>' +
                '<button type="button" class="med-remove text-[0.65rem] font-semibold text-red-500 hover:text-red-700 underline shrink-0 ml-2" data-med-id="' + m.medicine_id + '">Remove</button>' +
            '</div>';
        });
        histMedSelectedBody.innerHTML = html;
        histMedSelectedBody.querySelectorAll('.med-remove').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var mid = this.getAttribute('data-med-id');
                histMedSelected = histMedSelected.filter(function(s) { return String(s.medicine_id) !== String(mid); });
                var activeMed = histMedDetailBody && histMedDetailBody.querySelector('[data-med-id]');
                if (activeMed && activeMed.getAttribute('data-med-id') === mid) {
                    renderHistMedicineDetail();
                }
                renderHistMedSelected();
                loadHistMedicines(true);
            });
        });
        histMedSelectedBody.querySelectorAll('.med-selected-item').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var mid = this.getAttribute('data-med-id');
                if (histMedicinesById[mid]) renderHistMedicineDetail(histMedicinesById[mid]);
            });
        });
    }

    // ── Event Bindings ───────────────────────────────────────────────
    var searchTimer = null;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() { histCurrentPage = 1; loadData(1); }, 400);
        });
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', function() { histCurrentPage = 1; loadData(1); });
    }
    if (myToggle) {
        var toggleIcon = document.getElementById('consultHistToggleIcon');
        var toggleText = document.getElementById('consultHistToggleText');
        myToggle.addEventListener('click', function() {
            histMyConsultsOnly = !histMyConsultsOnly;
            if (histMyConsultsOnly) {
                myToggle.classList.add('bg-green-600', 'text-white', 'border-green-600');
                myToggle.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300');
                if (toggleText) toggleText.textContent = 'All consults';
                if (toggleIcon) toggleIcon.innerHTML = '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>';
            } else {
                myToggle.classList.remove('bg-green-600', 'text-white', 'border-green-600');
                myToggle.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300');
                if (toggleText) toggleText.textContent = 'My consults';
                if (toggleIcon) toggleIcon.innerHTML = '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>';
            }
            histCurrentPage = 1;
            loadData(1);
        });
    }
    var todayOnlyBtn = document.getElementById('consultHistTodayOnlyBtn');
    if (todayOnlyBtn) {
        todayOnlyBtn.addEventListener('click', function() {
            histTodayOnly = !histTodayOnly;
            if (histTodayOnly) {
                todayOnlyBtn.textContent = 'Showing today only';
                todayOnlyBtn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50');
                todayOnlyBtn.classList.add('bg-green-600', 'text-white', 'border-green-600');
            } else {
                todayOnlyBtn.textContent = 'Show today only';
                todayOnlyBtn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50');
                todayOnlyBtn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
            }
            histCurrentPage = 1;
            loadData(1);
        });
    }
    var refreshBtn = document.getElementById('consultHistRefreshBtn');
    if (refreshBtn) refreshBtn.addEventListener('click', function() { histCurrentPage = 1; loadData(1); });

    var editDetailsBtn = document.getElementById('consultHistEditDetailsBtn');
    var saveBtn = document.getElementById('consultHistSaveBtn');
    var editStatus = document.getElementById('consultHistEditStatus');
    if (editDetailsBtn) {
        editDetailsBtn.addEventListener('click', function() {
            var txId = this.getAttribute('data-tx-id');
            if (!txId) return;
            histIsEditing = !histIsEditing;
            if (histIsEditing) {
                this.textContent = 'Cancel';
                if (saveBtn) saveBtn.classList.remove('hidden');
                if (editStatus) editStatus.classList.remove('hidden');
                apiFetch("{{ url('/api/visits') }}/" + encodeURIComponent(txId), { method: 'GET' })
                    .then(function(r) { return r.json(); })
                    .then(function(visit) {
                        if (visit && visit.transaction_id) renderDetailContent(visit, true);
                    });
            } else {
                this.textContent = 'Edit details';
                if (saveBtn) saveBtn.classList.add('hidden');
                if (editStatus) editStatus.classList.add('hidden');
                apiFetch("{{ url('/api/visits') }}/" + encodeURIComponent(txId), { method: 'GET' })
                    .then(function(r) { return r.json(); })
                    .then(function(visit) {
                        if (visit && visit.transaction_id) renderDetailContent(visit, false);
                    });
            }
        });
    }
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            var txId = editDetailsBtn ? editDetailsBtn.getAttribute('data-tx-id') : '';
            if (!txId) return;
            var diagEl = document.getElementById('consultHistInlineDiag');
            var treatEl = document.getElementById('consultHistInlineTreat');
            var diagnosis = diagEl ? diagEl.value : '';
            var treatment = treatEl ? treatEl.value : '';
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';
            apiFetch("{{ url('/api/transactions') }}/" + encodeURIComponent(txId), {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ diagnosis: diagnosis, treatment_notes: treatment })
            })
            .then(function(r) { return r.json(); })
            .then(function(result) {
                saveBtn.textContent = 'Saved!';
                setTimeout(function() {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save changes';
                    // Exit edit mode and refresh
                    histIsEditing = false;
                    if (editDetailsBtn) editDetailsBtn.textContent = 'Edit details';
                    saveBtn.classList.add('hidden');
                    if (editStatus) editStatus.classList.add('hidden');
                    apiFetch("{{ url('/api/visits') }}/" + encodeURIComponent(txId), { method: 'GET' })
                        .then(function(r) { return r.json(); })
                        .then(function(visit) {
                            if (visit && visit.transaction_id) renderDetailContent(visit, false);
                        });
                }, 800);
            })
            .catch(function() {
                saveBtn.textContent = 'Error!';
                saveBtn.disabled = false;
                setTimeout(function() { saveBtn.textContent = 'Save changes'; }, 2000);
            });
        });
    }
    if (detailClose) detailClose.addEventListener('click', closeDetailModal);
    if (detailOverlay) detailOverlay.addEventListener('click', function(e) { if (e.target === detailOverlay) closeDetailModal(); });
    if (detailFilter) {
        detailFilter.addEventListener('change', function() {
            renderVisitHistoryList(histPatientVisits, histTransactionId, detailFilter.value);
        });
    }
    var detailDate = document.getElementById('consultHistDetailDate');
    if (detailDate) {
        detailDate.addEventListener('change', function() {
            var val = this.value;
            if (!val) { renderVisitHistoryList(histPatientVisits, histTransactionId, detailFilter ? detailFilter.value : 'all'); return; }
            var filtered = histPatientVisits.filter(function(v) {
                var d = (v.visit_datetime || v.transaction_datetime || '').slice(0, 10);
                return d === val;
            });
            renderVisitHistoryList(filtered, histTransactionId, detailFilter ? detailFilter.value : 'all');
        });
    }
    var detailReset = document.getElementById('consultHistDetailResetFilter');
    if (detailReset) {
        detailReset.addEventListener('click', function() {
            if (detailDate) detailDate.value = '';
            if (detailFilter) detailFilter.value = 'all';
            renderVisitHistoryList(histPatientVisits, histTransactionId, 'all');
        });
    }
    if (editClose) editClose.addEventListener('click', closeEditModal);
    if (editCancel) editCancel.addEventListener('click', closeEditModal);
    if (editOverlay) editOverlay.addEventListener('click', function(e) { if (e.target === editOverlay) closeEditModal(); });

    if (editAddItem) {
        editAddItem.addEventListener('click', function() {
            if (editItemsList && editItemsList.querySelector('.text-center.py-4')) editItemsList.innerHTML = '';
            var row = document.createElement('div');
            row.className = 'rounded-xl border border-slate-200 bg-white p-3 edit-item-row';
            row.setAttribute('data-item-id', 'new_' + Date.now());
            row.setAttribute('data-prescription-id', '');
            row.innerHTML =
                '<div class="flex items-start justify-between gap-2 mb-2">' +
                    '<div class="flex-1 min-w-0"><label class="block text-[0.6rem] text-slate-500 mb-0.5">Medicine name</label><input type="text" class="edit-item-name w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Enter medicine name"></div>' +
                    '<button type="button" class="edit-item-remove rounded-lg border border-rose-200 bg-rose-50 px-2 py-1 text-[0.65rem] font-medium text-rose-700 hover:bg-rose-100">Remove</button>' +
                '</div>' +
                '<div class="grid grid-cols-3 gap-2">' +
                    '<div><label class="block text-[0.6rem] text-slate-500 mb-0.5">Dosage</label><input type="text" class="edit-item-dosage w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"></div>' +
                    '<div><label class="block text-[0.6rem] text-slate-500 mb-0.5">Frequency</label><input type="text" class="edit-item-frequency w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"></div>' +
                    '<div><label class="block text-[0.6rem] text-slate-500 mb-0.5">Duration</label><input type="text" class="edit-item-duration w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"></div>' +
                '</div>' +
                '<div class="mt-2"><label class="block text-[0.6rem] text-slate-500 mb-0.5">Instructions</label><input type="text" class="edit-item-instructions w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"></div>';
            editItemsList.appendChild(row);
            row.querySelector('.edit-item-remove').addEventListener('click', function() {
                row.remove();
                if (!editItemsList.querySelector('.edit-item-row')) {
                    editItemsList.innerHTML = '<div class="text-[0.78rem] text-slate-400 text-center py-4">No prescription items. Click "Add medicine" to start.</div>';
                }
            });
        });
    }
    if (editSave) editSave.addEventListener('click', saveEdit);

    // ── Medicine Selector Event Bindings ─────────────────────────────
    var histMedClose = document.getElementById('consultHistMedClose');
    if (histMedClose) histMedClose.addEventListener('click', closeHistMedicineSelector);
    var histMedClear = document.getElementById('consultHistMedClearBtn');
    if (histMedClear) {
        histMedClear.addEventListener('click', function() {
            histMedSelected = [];
            renderHistMedSelected();
            loadHistMedicines(true);
        });
    }
    var histMedConfirm = document.getElementById('consultHistMedConfirmBtn');
    if (histMedConfirm) {
        histMedConfirm.addEventListener('click', function() {
            var txId = editDetailsBtn ? editDetailsBtn.getAttribute('data-tx-id') : '';
            if (!txId || !histMedSelected.length) return;
            var promises = histMedSelected.map(function(m) {
                return apiFetch("{{ url('/api/prescription-items') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        medicine_id: m.medicine_id,
                        dosage: '',
                        frequency: '',
                        duration: '',
                        instructions: ''
                    })
                });
            });
            Promise.all(promises)
                .then(function() {
                    histMedSelected = [];
                    closeHistMedicineSelector();
                    // Reload detail in edit mode
                    if (txId) {
                        apiFetch("{{ url('/api/visits') }}/" + encodeURIComponent(txId), { method: 'GET' })
                            .then(function(r) { return r.json(); })
                            .then(function(visit) {
                                if (visit && visit.transaction_id) renderDetailContent(visit, true);
                            });
                    }
                })
                .catch(function() {});
        });
    }
    if (histMedSearch) {
        histMedSearch.addEventListener('input', function() {
            if (histMedSearchTimer) clearTimeout(histMedSearchTimer);
            histMedSearchTimer = setTimeout(function() { loadHistMedicines(true); }, 250);
        });
    }
    if (histMedModal) {
        histMedModal.addEventListener('click', function(e) {
            if (e.target === histMedModal) closeHistMedicineSelector();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (histMedModal && !histMedModal.classList.contains('hidden')) closeHistMedicineSelector();
            else if (editOverlay && !editOverlay.classList.contains('hidden')) closeEditModal();
            else if (detailOverlay && !detailOverlay.classList.contains('hidden')) closeDetailModal();
        }
    });

    try {
        loadData(1);
    } catch(e) {
        var tb = document.getElementById('consultHistTbody');
        if (tb) tb.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-red-500">Runtime error: ' + e.message + '</td></tr>';
    }
})();
</script>
