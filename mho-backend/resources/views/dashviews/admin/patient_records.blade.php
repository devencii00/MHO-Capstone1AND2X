<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900">Patient Records</h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Clinical</span>
    </div>
    <p class="text-xs text-slate-500 mb-4">
        Review patient medical backgrounds and visit history.
    </p>

    <div id="adminPrPatientsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

    <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
        <div class="flex-1">
            <label for="admin_pr_patients_search" class="block text-[0.7rem] text-slate-600 mb-1">Search patient name</label>
            <input id="admin_pr_patients_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by name (starts with)">
        </div>
        <div class="w-full md:w-44">
            <label for="admin_pr_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
            <select id="admin_pr_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="name_asc">Name A-Z</option>
                <option value="created_desc">Newest first</option>
                <option value="created_asc">Oldest first</option>
            </select>
        </div>
    </div>

    <div class="mb-4">
        <div class="text-[0.7rem] text-slate-600 mb-1">Age filter</div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-green-600 text-white text-[0.72rem] font-semibold" data-age-filter="all">
                All
                <span id="adminPrAgeCountAll" class="ml-1 inline-flex items-center rounded-full bg-white/15 px-2 py-0.5 text-[0.68rem] font-semibold">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="1_5">
                1–5
                <span id="adminPrAgeCount1_5" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="6_12">
                6–12
                <span id="adminPrAgeCount6_12" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="13_18">
                13–18
                <span id="adminPrAgeCount13_18" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="19_30">
                19–30
                <span id="adminPrAgeCount19_30" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="31_up">
                31+
                <span id="adminPrAgeCount31Up" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto overflow-y-auto scrollbar-hidden h-[310px]">
        <table class="min-w-full text-left text-xs text-slate-600">
            <thead>
                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                    <th class="py-2 pr-4 font-semibold"></th>
                    <th class="py-2 pr-4 font-semibold">Patient</th>
                    <th class="py-2 pr-4 font-semibold">Address</th>
                    <th class="py-2 pr-4 font-semibold">Age</th>
                    <th class="py-2 pr-4 font-semibold">Sex</th>
                    <th class="py-2 pr-4 font-semibold">Type</th>
                    <th class="py-2 pr-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody id="admin_pr_patients_table_body">
                <tr>
                    <td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">
                        Loading patients…
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="adminPrPagination" class="flex items-center justify-center gap-3 pt-3 pb-1 flex-wrap"></div>
</div>

<div id="adminPrSlideoverOverlay" class="fixed inset-0 z-50 bg-black/30 opacity-0 pointer-events-none transition-opacity"></div>

<!-- Vitals side panel (slides from left inside the main slideover) -->
<div id="adminPrVitalsSidePanel" class="fixed top-0 left-0 z-[51] h-full w-full max-w-[300px] bg-white border-r border-slate-200 shadow-2xl -translate-x-full transition-transform">
    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 shrink-0">
            <div class="text-[0.78rem] font-semibold text-slate-900">Vitals</div>
            <button type="button" id="adminPrVitalsSideClose" class="text-slate-400 hover:text-slate-600">
                <x-lucide-x class="w-[18px] h-[18px]" />
            </button>
        </div>
        <div id="adminPrVitalsSideBody" class="flex-1 overflow-y-auto p-3 space-y-2">
            <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading vitals…</div>
        </div>
    </div>
</div>

<div id="adminPrSlideoverPanel" class="fixed top-0 right-0 z-50 h-full w-full max-w-[560px] bg-white border-l border-slate-200 shadow-2xl translate-x-full transition-transform">
    <div class="h-full flex flex-col">
        <div class="flex items-start justify-between gap-3 p-5 border-b border-slate-100">
            <div class="flex items-center gap-4 min-w-0">
                <div id="adminPrPanelProfilePic" class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 flex-shrink-0 overflow-hidden">
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <x-lucide-user class="w-8 h-8" />
                    </div>
                </div>
                <div class="min-w-0">
                    <div id="adminPrPanelPatientName" class="text-sm font-semibold text-slate-900 truncate">Patient</div>
                    <div id="adminPrPanelPatientContact" class="text-xs text-slate-500 truncate"></div>
                    <div id="adminPrPanelPatientMeta" class="text-xs text-slate-400 truncate"></div>
                </div>
            </div>
            <button type="button" id="adminPrPanelClose" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                <x-lucide-x class="w-[18px] h-[18px]" />
            </button>
        </div>

        <div class="p-5 border-b border-slate-100 grid grid-cols-3 gap-3">
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification status</div>
                <div id="adminPrPanelVerificationStatus" class="text-[0.8rem] font-semibold text-slate-700 mt-1">—</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient type</div>
                <div id="adminPrPanelPatientType" class="text-[0.8rem] font-semibold text-slate-700 mt-1">—</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification ID</div>
                <div id="adminPrPanelVerificationId" class="text-[0.8rem] font-semibold text-slate-700 mt-1">—</div>
            </div>
        </div>

        <div class="px-5 pt-4">
            <div class="flex items-center gap-2">
                <button type="button" id="adminPrPanelTabBackground" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-green-600 text-white">Medical background</button>
                <button type="button" id="adminPrPanelTabVisits" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Visit history</button>
                <button type="button" id="adminPrPanelTabVitals" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Vitals history</button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto scrollbar-hidden">
            <div id="adminPrPanelPanelBackground" class="p-5">
                <div id="adminPrPanelMedBgError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                <div class="overflow-x-auto scrollbar-hidden">
                    <table class="min-w-full text-left text-xs text-slate-600">
                        <thead>
                            <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                                <th class="py-2 pr-4 font-semibold">Category</th>
                                <th class="py-2 pr-4 font-semibold">Name</th>
                                <th class="py-2 pr-4 font-semibold">Diagnosis Date</th>
                                <th class="py-2 pr-4 font-semibold">Procedure Date</th>
                                <th class="py-2 pr-4 font-semibold">Notes</th>
                            </tr>
                        </thead>
                        <tbody id="adminPrPanelMedBgTableBody">
                            <tr>
                                <td colspan="5" class="py-4 text-center text-[0.78rem] text-slate-400">
                                    Select a patient to view entries.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="adminPrPanelPanelVisits" class="p-5 hidden">
                <div id="adminPrPanelVisitsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                <div class="overflow-x-auto scrollbar-hidden">
                    <table class="min-w-full text-left text-xs text-slate-600">
                        <thead>
                            <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                                <th class="py-2 pr-4 font-semibold">Doctor</th>
                                <th class="py-2 pr-4 font-semibold">Visit date</th>
                                <th class="py-2 pr-4 font-semibold">Fees</th>
                                <th class="py-2 pr-4 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminPrPanelVisitsTableBody">
                            <tr>
                                <td colspan="4" class="py-4 text-center text-[0.78rem] text-slate-400">
                                    Select a patient to view visits.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="adminPrPanelPanelVitals" class="p-5 hidden">
                <div id="adminPrPanelVitalsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                <div class="overflow-x-auto scrollbar-hidden">
                    <table class="min-w-full text-left text-xs text-slate-600">
                        <thead>
                            <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                                <th class="py-2 pr-4 font-semibold">Recorded</th>
                                <th class="py-2 pr-4 font-semibold">Height (cm)</th>
                                <th class="py-2 pr-4 font-semibold">Weight (kg)</th>
                                <th class="py-2 pr-4 font-semibold">BP</th>
                                <th class="py-2 pr-4 font-semibold">Temp</th>
                                <th class="py-2 pr-4 font-semibold">Pulse</th>
                                <th class="py-2 pr-4 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminPrPanelVitalsTableBody">
                            <tr>
                                <td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">
                                    Select a patient to view vitals.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var apiBaseUrl = "{{ request()->getBasePath() }}/api"
        var defaultProfilePicHtml = '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
        var patientsError = document.getElementById('adminPrPatientsError')
        var patientsSearch = document.getElementById('admin_pr_patients_search')
        var sortSelect = document.getElementById('admin_pr_sort')
        var patientsTableBody = document.getElementById('admin_pr_patients_table_body')
        var pagination = document.getElementById('adminPrPagination')
        var patientsRows = []
        var perPage = 10
        var currentPage = 1

        function getPatientTableRows() {
            return document.querySelectorAll('#admin_pr_patients_table_body .admin-pr-patient-row')
        }

        function showPage(page) {
            var rows = getPatientTableRows()
            var total = rows.length
            if (!total || !pagination) return

            var totalPages = Math.ceil(total / perPage)
            if (page < 1 || page > totalPages) return
            currentPage = page
            var start = (page - 1) * perPage
            var end = Math.min(start + perPage, total)
            rows.forEach(function (row, i) {
                row.style.display = (i >= start && i < end) ? '' : 'none'
            })
            renderPagination()
        }

        function renderPagination() {
            if (!pagination) return
            var rows = getPatientTableRows()
            var total = rows.length
            if (total === 0) {
                pagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                return
            }
            var totalPages = Math.ceil(total / perPage)
            if (totalPages <= 1) {
                pagination.innerHTML = '<span class="text-[0.7rem] text-slate-400">' + total + ' entries</span>'
                return
            }
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
            html += '<button type="button" class="px-2 py-1 text-[0.72rem] font-semibold rounded-md border border-slate-200 ' +
                (currentPage === 1 ? 'text-slate-300 cursor-default' : 'text-slate-600 hover:bg-slate-50 cursor-pointer') +
                '" data-page="prev"' + (currentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            for (var i = 1; i <= totalPages; i++) {
                html += '<button type="button" class="px-2 py-1 text-[0.72rem] font-semibold rounded-md border ' +
                    (i === currentPage ? 'bg-green-600 text-white border-green-600' : 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer') +
                    '" data-page="' + i + '">' + i + '</button>'
            }
            html += '<button type="button" class="px-2 py-1 text-[0.72rem] font-semibold rounded-md border border-slate-200 ' +
                (currentPage === totalPages ? 'text-slate-300 cursor-default' : 'text-slate-600 hover:bg-slate-50 cursor-pointer') +
                '" data-page="next"' + (currentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            pagination.innerHTML = html

            pagination.querySelectorAll('button[data-page]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var p = btn.getAttribute('data-page')
                    if (p === 'prev' && currentPage > 1) showPage(currentPage - 1)
                    else if (p === 'next' && currentPage < totalPages) showPage(currentPage + 1)
                    else if (p !== 'prev' && p !== 'next') showPage(parseInt(p, 10))
                })
            })
        }

        var activeAgeFilter = 'all'
        var ageFilterButtons = Array.prototype.slice.call(document.querySelectorAll('.admin-pr-age-filter'))
        var ageCountAll = document.getElementById('adminPrAgeCountAll')
        var ageCount1_5 = document.getElementById('adminPrAgeCount1_5')
        var ageCount6_12 = document.getElementById('adminPrAgeCount6_12')
        var ageCount13_18 = document.getElementById('adminPrAgeCount13_18')
        var ageCount19_30 = document.getElementById('adminPrAgeCount19_30')
        var ageCount31Up = document.getElementById('adminPrAgeCount31Up')

        var overlay = document.getElementById('adminPrSlideoverOverlay')
        var panel = document.getElementById('adminPrSlideoverPanel')
        var panelClose = document.getElementById('adminPrPanelClose')
        var panelPatientName = document.getElementById('adminPrPanelPatientName')
        var panelPatientContact = document.getElementById('adminPrPanelPatientContact')
        var panelPatientMeta = document.getElementById('adminPrPanelPatientMeta')
        var panelProfilePic = document.getElementById('adminPrPanelProfilePic')
        var panelVerificationStatus = document.getElementById('adminPrPanelVerificationStatus')
        var panelPatientType = document.getElementById('adminPrPanelPatientType')
        var panelVerificationId = document.getElementById('adminPrPanelVerificationId')

        var panelTabBackground = document.getElementById('adminPrPanelTabBackground')
        var panelTabVisits = document.getElementById('adminPrPanelTabVisits')
        var panelTabVitals = document.getElementById('adminPrPanelTabVitals')
        var panelBackground = document.getElementById('adminPrPanelPanelBackground')
        var panelVisits = document.getElementById('adminPrPanelPanelVisits')
        var panelVitals = document.getElementById('adminPrPanelPanelVitals')

        var panelMedBgError = document.getElementById('adminPrPanelMedBgError')
        var panelMedBgTableBody = document.getElementById('adminPrPanelMedBgTableBody')

        var panelVisitsError = document.getElementById('adminPrPanelVisitsError')
        var panelVisitsTableBody = document.getElementById('adminPrPanelVisitsTableBody')

        var panelVitalsError = document.getElementById('adminPrPanelVitalsError')
        var panelVitalsTableBody = document.getElementById('adminPrPanelVitalsTableBody')

        var currentPatientId = null
        var expandedVitalId = null

        var vitalsSidePanel = document.getElementById('adminPrVitalsSidePanel')
        var vitalsSideClose = document.getElementById('adminPrVitalsSideClose')
        var vitalsSideBody = document.getElementById('adminPrVitalsSideBody')

        function closeVitalsSidePanel() {
            if (vitalsSidePanel) {
                vitalsSidePanel.classList.add('-translate-x-full')
                vitalsSidePanel.classList.remove('translate-x-0')
            }
        }

        function openVitalsSidePanel() {
            if (vitalsSidePanel) {
                vitalsSidePanel.classList.remove('-translate-x-full')
                vitalsSidePanel.classList.add('translate-x-0')
            }
        }

        function loadVitalsForAppointment(appointmentId) {
            if (!vitalsSideBody || !currentPatientId) return
            vitalsSideBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading vitals…</div>'
            openVitalsSidePanel()
            apiFetch(apiBaseUrl + "/vitals?per_page=5&patient_id=" + encodeURIComponent(currentPatientId) + "&appointment_id=" + encodeURIComponent(appointmentId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () { return { ok: false, data: null } })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        vitalsSideBody.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Failed to load vitals.</div>'
                        return
                    }
                    var vitals = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    if (!vitals.length) {
                        vitalsSideBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No vitals recorded for this visit.</div>'
                        return
                    }
                    var html = ''
                    vitals.forEach(function (v) {
                        var recorded = v && v.recorded_at ? formatRecordedAt(v.recorded_at) : '—'
                        var height = v && v.height_cm != null ? formatNumeric(v.height_cm, 1) : '—'
                        var weight = v && v.weight_kg != null ? formatNumeric(v.weight_kg, 1) : '—'
                        var bp = v && v.blood_pressure ? String(v.blood_pressure) : '—'
                        var temp = v && v.temperature != null ? formatNumeric(v.temperature, 1) : '—'
                        var pulse = v && v.pulse_rate != null ? String(v.pulse_rate) : '—'
                        var bmi = '—'
                        if (v && v.height_cm && v.weight_kg && parseFloat(v.height_cm) > 0) {
                            var hm = parseFloat(v.height_cm) / 100
                            var wk = parseFloat(v.weight_kg)
                            var bmiVal = wk / (hm * hm)
                            if (isFinite(bmiVal)) bmi = bmiVal.toFixed(1)
                        }
                        html += '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                            '<div class="text-[0.72rem] text-slate-500 mb-2">' + escapeHtml(recorded) + '</div>' +
                            '<div class="text-[0.72rem] space-y-1.5">' +
                                '<div class="flex items-center justify-between"><span class="text-slate-500">Height</span><span class="text-slate-800 font-medium">' + escapeHtml(height) + ' cm</span></div>' +
                                '<div class="flex items-center justify-between"><span class="text-slate-500">Weight</span><span class="text-slate-800 font-medium">' + escapeHtml(weight) + ' kg</span></div>' +
                                '<div class="flex items-center justify-between"><span class="text-slate-500">BMI</span><span class="text-slate-800 font-medium">' + escapeHtml(bmi) + '</span></div>' +
                                '<div class="flex items-center justify-between"><span class="text-slate-500">BP</span><span class="text-slate-800 font-medium">' + escapeHtml(bp) + '</span></div>' +
                                '<div class="flex items-center justify-between"><span class="text-slate-500">Temp</span><span class="text-slate-800 font-medium">' + escapeHtml(temp) + ' °C</span></div>' +
                                '<div class="flex items-center justify-between"><span class="text-slate-500">Pulse</span><span class="text-slate-800 font-medium">' + escapeHtml(pulse) + ' bpm</span></div>' +
                            '</div>' +
                        '</div>'
                    })
                    vitalsSideBody.innerHTML = html
                })
                .catch(function () {
                    vitalsSideBody.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Network error loading vitals.</div>'
                })
        }

        function escapeHtml(text) {
            return String(text || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function showInlineBox(el, message) {
            if (!el) return
            el.textContent = message || ''
            el.classList.toggle('hidden', !message)
        }

        function categoryLabel(key) {
            var k = String(key || '')
            if (k === 'allergy_food') return 'Food'
            if (k === 'allergy_drug') return 'Drug'
            if (k === 'condition') return 'Condition'
            return k || '—'
        }

        function fullName(p, fallback) {
            if (!p) return fallback || '—'
            var parts = []
            if (p.firstname) parts.push(String(p.firstname))
            if (p.middlename) parts.push(String(p.middlename))
            if (p.lastname) parts.push(String(p.lastname))
            var name = parts.join(' ').trim()
            if (name) return name
            if (p.email) return String(p.email)
            return fallback || ('#' + (p.user_id || ''))
        }

        function nameOnly(p) {
            if (!p) return ''
            var parts = []
            if (p.firstname) parts.push(String(p.firstname))
            if (p.middlename) parts.push(String(p.middlename))
            if (p.lastname) parts.push(String(p.lastname))
            return parts.join(' ').trim()
        }

        function ageFromBirthdate(birthdate) {
            if (!birthdate) return null
            var d = new Date(String(birthdate))
            if (isNaN(d.getTime())) return null
            var today = new Date()
            var age = today.getFullYear() - d.getFullYear()
            var m = today.getMonth() - d.getMonth()
            if (m < 0 || (m === 0 && today.getDate() < d.getDate())) {
                age--
            }
            if (age < 0) return null
            return age
        }

        function matchesAgeFilter(age, filterKey) {
            if (filterKey === 'all') return true
            if (age == null) return false
            if (filterKey === '1_5') return age >= 1 && age <= 5
            if (filterKey === '6_12') return age >= 6 && age <= 12
            if (filterKey === '13_18') return age >= 13 && age <= 18
            if (filterKey === '19_30') return age >= 19 && age <= 30
            if (filterKey === '31_up') return age >= 31
            return true
        }

        function setAgeFilterActiveStyles() {
            ageFilterButtons.forEach(function (btn) {
                var key = btn.getAttribute('data-age-filter') || ''
                var isActive = key === activeAgeFilter
                btn.classList.remove(
                    'bg-green-600',
                    'text-white',
                    'bg-green-600',
                    'bg-white',
                    'text-slate-700',
                    'border-slate-200',
                    'hover:bg-slate-50'
                )
                if (isActive) {
                    btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
                } else {
                    btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                }
            })
        }

        function setText(el, text) {
            if (!el) return
            el.textContent = text == null ? '' : String(text)
        }

        function openPanel() {
            if (overlay) {
                overlay.classList.remove('opacity-0', 'pointer-events-none')
                overlay.classList.add('opacity-100', 'pointer-events-auto')
            }
            if (panel) {
                panel.classList.remove('translate-x-full')
                panel.classList.add('translate-x-0')
            }
        }

        function closePanel() {
            currentPatientId = null
            if (overlay) {
                overlay.classList.add('opacity-0', 'pointer-events-none')
                overlay.classList.remove('opacity-100', 'pointer-events-auto')
            }
            if (panel) {
                panel.classList.add('translate-x-full')
                panel.classList.remove('translate-x-0')
            }
        }

        function setTabButtonActive(btn, isActive) {
            if (!btn) return
            btn.classList.remove(
                'bg-green-600',
                'text-white',
                'border-green-600',
                'bg-white',
                'text-slate-700',
                'border-slate-200',
                'hover:bg-slate-50'
            )
            if (isActive) {
                btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
            } else {
                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
            }
        }

        function setPanelTab(key) {
            var isBackground = key === 'background'
            var isVisits = key === 'visits'
            var isVitals = key === 'vitals'
            if (panelBackground) panelBackground.classList.toggle('hidden', !isBackground)
            if (panelVisits) panelVisits.classList.toggle('hidden', !isVisits)
            if (panelVitals) panelVitals.classList.toggle('hidden', !isVitals)
            setTabButtonActive(panelTabBackground, isBackground)
            setTabButtonActive(panelTabVisits, isVisits)
            setTabButtonActive(panelTabVitals, isVitals)
        }

        function loadPatients() {
            if (!patientsTableBody) return
            patientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading patients…</td></tr>'
            showInlineBox(patientsError, '')

            apiFetch(apiBaseUrl + "/patients?per_page=100", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: false, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        patientsRows = []
                        showInlineBox(patientsError, 'Failed to load patients.')
                        renderPatients()
                        return
                    }
                    var rows = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    patientsRows = rows
                    renderPatients()
                })
                .catch(function () {
                    patientsRows = []
                    showInlineBox(patientsError, 'Failed to load patients.')
                    renderPatients()
                })
        }

        function renderPatients() {
            if (!patientsTableBody) return
            var query = patientsSearch ? String(patientsSearch.value || '').toLowerCase().trim() : ''
            var sortValue = sortSelect ? String(sortSelect.value || 'name_asc') : 'name_asc'
            var base = (patientsRows || []).slice()

            if (query) {
                base = base.filter(function (p) {
                    var name = nameOnly(p).toLowerCase()
                    return name !== '' && name.indexOf(query) === 0
                })
            }

            var counts = {
                all: 0,
                '1_5': 0,
                '6_12': 0,
                '13_18': 0,
                '19_30': 0,
                '31_up': 0
            }

            base.forEach(function (p) {
                var age = ageFromBirthdate(p && p.birthdate ? String(p.birthdate) : null)
                counts.all++
                if (matchesAgeFilter(age, '1_5')) counts['1_5']++
                if (matchesAgeFilter(age, '6_12')) counts['6_12']++
                if (matchesAgeFilter(age, '13_18')) counts['13_18']++
                if (matchesAgeFilter(age, '19_30')) counts['19_30']++
                if (matchesAgeFilter(age, '31_up')) counts['31_up']++
            })

            setText(ageCountAll, counts.all)
            setText(ageCount1_5, counts['1_5'])
            setText(ageCount6_12, counts['6_12'])
            setText(ageCount13_18, counts['13_18'])
            setText(ageCount19_30, counts['19_30'])
            setText(ageCount31Up, counts['31_up'])

            var filtered = base.filter(function (p) {
                var age = ageFromBirthdate(p && p.birthdate ? String(p.birthdate) : null)
                return matchesAgeFilter(age, activeAgeFilter)
            })

            filtered.sort(function (a, b) {
                if (sortValue === 'created_asc' || sortValue === 'created_desc') {
                    var ta = a && a.created_at ? Date.parse(String(a.created_at)) : 0
                    var tb = b && b.created_at ? Date.parse(String(b.created_at)) : 0
                    if (isNaN(ta)) ta = 0
                    if (isNaN(tb)) tb = 0
                    if (ta < tb) return sortValue === 'created_asc' ? -1 : 1
                    if (ta > tb) return sortValue === 'created_asc' ? 1 : -1
                    return 0
                }

                var na = nameOnly(a).toLowerCase()
                var nb = nameOnly(b).toLowerCase()
                if (na < nb) return -1
                if (na > nb) return 1
                var ia = a && a.user_id != null ? parseInt(a.user_id, 10) : 0
                var ib = b && b.user_id != null ? parseInt(b.user_id, 10) : 0
                if (ia < ib) return -1
                if (ia > ib) return 1
                return 0
            })

            if (!filtered.length) {
                patientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">No patients found.</td></tr>'
                renderPagination()
                return
            }

            var html = ''
            filtered.forEach(function (p) {
                var pid = p && p.user_id != null ? String(p.user_id) : ''
                var name = fullName(p, 'Patient')
                var address = p && p.address ? String(p.address) : ''
                var age = ageFromBirthdate(p && p.birthdate ? String(p.birthdate) : null)
                var sex = p && p.sex ? String(p.sex) : ''
                var verificationType = p && p.verification_type ? String(p.verification_type) : ''
                var profileImg = p && p.prof_path_url ? String(p.prof_path_url) : ''
                html += '<tr class="admin-pr-patient-row border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4">' +
                        (profileImg
                            ? '<img src="' + profileImg.replace(/"/g,'&quot;') + '" alt="" class="w-10 h-10 rounded-lg object-cover border border-slate-200">'
                            : '<div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
                        ) +
                    '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(name) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (address ? escapeHtml(address) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (age != null ? escapeHtml(age) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (sex ? escapeHtml(sex.charAt(0).toUpperCase() + sex.slice(1)) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (verificationType ? escapeHtml(verificationType.charAt(0).toUpperCase() + verificationType.slice(1)) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4">' +
                        '<button type="button" class="admin-pr-open-panel inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.78rem] font-semibold hover:bg-slate-50" data-patient-id="' + escapeHtml(pid) + '">' +
                            'View Details and History' +
                        '</button>' +
                    '</td>' +
                '</tr>'
            })
            patientsTableBody.innerHTML = html

            var totalPages = Math.ceil(filtered.length / perPage)
            if (currentPage > totalPages) currentPage = totalPages
            showPage(currentPage || 1)
        }

        function findPatientById(patientId) {
            var pid = String(patientId || '')
            for (var i = 0; i < (patientsRows || []).length; i++) {
                var p = patientsRows[i]
                if (p && String(p.user_id) === pid) {
                    return p
                }
            }
            return null
        }

        function renderPanelMedicalBackground(entries) {
            if (!panelMedBgTableBody) return
            if (!entries || !entries.length) {
                panelMedBgTableBody.innerHTML = '<tr><td colspan="5" class="py-4 text-center text-[0.78rem] text-slate-400">No medical background entries found.</td></tr>'
                return
            }
            var html = ''
            entries.forEach(function (r) {
                var diagnosisDate = r && r.diagnosis_date ? String(r.diagnosis_date) : ''
                var diagnosisTime = r && r.diagnosis_time ? String(r.diagnosis_time) : ''
                if (diagnosisDate || diagnosisTime) {
                    diagnosisDate = (diagnosisDate || '') + (diagnosisTime ? ' ' + diagnosisTime.slice(0, 5) : '')
                }
                var procedureDate = r && r.procedure_date ? String(r.procedure_date) : ''
                html += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(categoryLabel(r.category)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(r && r.name ? String(r.name) : '—') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosisDate ? escapeHtml(diagnosisDate) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (procedureDate ? escapeHtml(procedureDate) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (r && r.notes ? escapeHtml(String(r.notes)) : '<span class="text-slate-400">—</span>') + '</td>' +
                '</tr>'
            })
            panelMedBgTableBody.innerHTML = html
        }

        function renderPanelVisits(rows) {
            if (!panelVisitsTableBody) return
            if (!rows || !rows.length) {
                panelVisitsTableBody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-[0.78rem] text-slate-400">No visits found.</td></tr>'
                return
            }
            var html = ''
            rows.forEach(function (v) {
                var appt = v && v.appointment ? v.appointment : null
                var apptId = appt && appt.appointment_id != null ? String(appt.appointment_id) : ''
                var doctor = appt && appt.doctor ? appt.doctor : null
                var dateRaw = v && (v.visit_datetime || v.transaction_datetime) ? String(v.visit_datetime || v.transaction_datetime) : ''
                var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '—'
                var fees = v && v.amount != null ? ('₱' + parseFloat(v.amount || 0).toFixed(2)) : '—'

                html += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(fullName(doctor, 'Doctor')) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(dateText) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(fees) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' +
                        '<button type="button" class="admin-pr-view-vitals inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.72rem] font-semibold hover:bg-slate-50" data-appointment-id="' + escapeHtml(apptId) + '">View Vitals</button>' +
                    '</td>' +
                '</tr>'
            })
            panelVisitsTableBody.innerHTML = html

            panelVisitsTableBody.querySelectorAll('.admin-pr-view-vitals').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var apptId = this.getAttribute('data-appointment-id')
                    if (!apptId || !currentPatientId) return
                    loadVitalsForAppointment(apptId)
                })
            })
        }

        function doctorLabelFromVitals(v) {
            if (!v) return '—'
            var parts = []
            if (v.doctor_firstname) parts.push(String(v.doctor_firstname))
            if (v.doctor_middlename) parts.push(String(v.doctor_middlename))
            if (v.doctor_lastname) parts.push(String(v.doctor_lastname))
            var name = parts.join(' ').trim()
            if (name) return name
            if (v.doctor_id != null) return 'Doctor #' + String(v.doctor_id)
            return '—'
        }

        function formatRecordedAt(value) {
            var raw = value ? String(value) : ''
            if (!raw) return '—'
            return raw.replace('T', ' ').slice(0, 16)
        }

        function formatNumeric(value, decimals) {
            if (value == null || value === '') return '—'
            var num = typeof value === 'number' ? value : parseFloat(value)
            if (isNaN(num)) return '—'
            var d = decimals == null ? 1 : decimals
            return num.toFixed(d)
        }

        function bmiCategoryText(bmi) {
            if (bmi < 18.5) return 'Underweight (Below 18.5)'
            if (bmi < 25) return 'Healthy Weight (18.5 – 24.9)'
            if (bmi < 30) return 'Overweight (25.0 – 29.9)'
            if (bmi < 35) return 'Class 1 Obesity (30.0 – 34.9)'
            if (bmi < 40) return 'Class 2 Obesity (35.0 – 39.9)'
            return 'Class 3 Obesity (Severe) (40.0 or higher)'
        }

        function setBmiForVital(vitalId) {
            if (!panelVitalsTableBody) return
            var id = String(vitalId || '')
            if (!id) return
            var row = panelVitalsTableBody.querySelector('tr[data-vital-id="' + id.replace(/"/g, '') + '"]')
            var out = panelVitalsTableBody.querySelector('[data-vital-bmi-result="' + id.replace(/"/g, '') + '"]')
            if (!row || !out) return

            var heightCm = parseFloat(row.getAttribute('data-height-cm') || '')
            var weightKg = parseFloat(row.getAttribute('data-weight-kg') || '')
            if (!heightCm || isNaN(heightCm) || !weightKg || isNaN(weightKg) || heightCm <= 0 || weightKg <= 0) {
                out.textContent = 'BMI: —  BMI Category: —'
                return
            }

            var h = heightCm / 100
            var bmi = weightKg / (h * h)
            if (!isFinite(bmi) || isNaN(bmi)) {
                out.textContent = 'BMI: —  BMI Category: —'
                return
            }

            var bmiText = bmi.toFixed(1)
            out.textContent = 'BMI: ' + bmiText + '  BMI Category: ' + bmiCategoryText(bmi)
        }

        function toggleVitalDetails(vitalId) {
            if (!panelVitalsTableBody) return
            var id = String(vitalId || '')
            if (!id) return

            var currentDetail = panelVitalsTableBody.querySelector('tr[data-vital-detail-row="' + id.replace(/"/g, '') + '"]')
            if (!currentDetail) return

            if (expandedVitalId && expandedVitalId !== id) {
                var prev = panelVitalsTableBody.querySelector('tr[data-vital-detail-row="' + String(expandedVitalId).replace(/"/g, '') + '"]')
                if (prev) prev.classList.add('hidden')
            }

            var isHidden = currentDetail.classList.contains('hidden')
            if (isHidden) {
                currentDetail.classList.remove('hidden')
                expandedVitalId = id
            } else {
                currentDetail.classList.add('hidden')
                expandedVitalId = null
            }
        }

        function renderPanelVitals(rows) {
            if (!panelVitalsTableBody) return
            expandedVitalId = null
            if (!rows || !rows.length) {
                panelVitalsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">No vitals found.</td></tr>'
                return
            }

            var html = ''
            rows.forEach(function (v) {
                var id = v && v.vital_id != null ? String(v.vital_id) : ''
                var recorded = formatRecordedAt(v && v.recorded_at ? v.recorded_at : (v && v.appointment_datetime ? v.appointment_datetime : ''))
                var height = v && v.height_cm != null ? formatNumeric(v.height_cm, 1) : '—'
                var weight = v && v.weight_kg != null ? formatNumeric(v.weight_kg, 1) : '—'
                var bp = v && v.blood_pressure ? String(v.blood_pressure) : '—'
                var temp = v && v.temperature != null ? formatNumeric(v.temperature, 1) : '—'
                var pulse = v && v.pulse_rate != null ? String(v.pulse_rate) : '—'
                var doctor = doctorLabelFromVitals(v)
                var appt = v && v.appointment_id != null ? ('#' + String(v.appointment_id)) : '—'
                var apptWhen = v && v.appointment_datetime ? formatRecordedAt(v.appointment_datetime) : '—'

                html += '<tr class="border-b border-slate-50 last:border-0 cursor-pointer hover:bg-slate-50" data-vital-id="' + escapeHtml(id) + '" data-height-cm="' + escapeHtml(v && v.height_cm != null ? String(v.height_cm) : '') + '" data-weight-kg="' + escapeHtml(v && v.weight_kg != null ? String(v.weight_kg) : '') + '">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(recorded) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(height) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(weight) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(bp) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(temp) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(pulse) + '</td>' +
                    '<td class="py-2 pr-4">' +
                        '<button type="button" class="admin-vital-bmi inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.75rem] font-semibold hover:bg-slate-50" data-vital-id="' + escapeHtml(id) + '">Get BMI</button>' +
                    '</td>' +
                '</tr>' +
                '<tr class="hidden" data-vital-detail-row="' + escapeHtml(id) + '">' +
                    '<td colspan="7" class="pb-3">' +
                        '<div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-[0.78rem] text-slate-700">' +
                            '<div class="grid grid-cols-2 gap-2">' +
                                '<div><span class="text-slate-500">Appointment:</span> ' + escapeHtml(appt) + '</div>' +
                                '<div><span class="text-slate-500">Appointment date:</span> ' + escapeHtml(apptWhen) + '</div>' +
                                '<div><span class="text-slate-500">Doctor:</span> ' + escapeHtml(doctor) + '</div>' +
                                '<div><span class="text-slate-500">Recorded at:</span> ' + escapeHtml(recorded) + '</div>' +
                                '<div><span class="text-slate-500">Height:</span> ' + escapeHtml(height) + ' cm</div>' +
                                '<div><span class="text-slate-500">Weight:</span> ' + escapeHtml(weight) + ' kg</div>' +
                                '<div><span class="text-slate-500">Blood pressure:</span> ' + escapeHtml(bp) + '</div>' +
                                '<div><span class="text-slate-500">Temperature:</span> ' + escapeHtml(temp) + '</div>' +
                                '<div><span class="text-slate-500">Pulse rate:</span> ' + escapeHtml(pulse) + '</div>' +
                            '</div>' +
                            '<div class="mt-2 text-[0.78rem] text-slate-800 font-semibold" data-vital-bmi-result="' + escapeHtml(id) + '">BMI: —  BMI Category: —</div>' +
                        '</div>' +
                    '</td>' +
                '</tr>'
            })

            panelVitalsTableBody.innerHTML = html
        }

        function loadPatientPanelData(patientId) {
            currentPatientId = String(patientId || '')
            showInlineBox(panelMedBgError, '')
            showInlineBox(panelVisitsError, '')
            showInlineBox(panelVitalsError, '')
            expandedVitalId = null

            if (panelVerificationStatus) panelVerificationStatus.textContent = '—'
            if (panelPatientType) panelPatientType.textContent = '—'
            if (panelVerificationId) panelVerificationId.textContent = '—'
            if (panelPatientContact) panelPatientContact.textContent = ''
            if (panelProfilePic) {
                panelProfilePic.innerHTML = defaultProfilePicHtml
            }

            if (panelMedBgTableBody) {
                panelMedBgTableBody.innerHTML = '<tr><td colspan="5" class="py-4 text-center text-[0.78rem] text-slate-400">Loading entries…</td></tr>'
            }
            if (panelVisitsTableBody) {
                panelVisitsTableBody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-[0.78rem] text-slate-400">Loading visits…</td></tr>'
            }
            if (panelVitalsTableBody) {
                panelVitalsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading vitals…</td></tr>'
            }

            var medBgReq = apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=100&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var visitsReq = apiFetch(apiBaseUrl + "/visits?per_page=100&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var vitalsReq = apiFetch(apiBaseUrl + "/vitals?per_page=100&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var verificationReq = apiFetch(apiBaseUrl + "/patient-verifications?per_page=1&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            Promise.all([medBgReq, visitsReq, vitalsReq, verificationReq])
                .then(function (results) {
                    if (String(patientId || '') !== currentPatientId) {
                        return
                    }

                    var medBgRes = results[0]
                    if (!medBgRes || !medBgRes.ok || !medBgRes.data) {
                        showInlineBox(panelMedBgError, 'Failed to load medical background entries.')
                        renderPanelMedicalBackground([])
                    } else {
                        var medBgRows = Array.isArray(medBgRes.data.data) ? medBgRes.data.data : (Array.isArray(medBgRes.data) ? medBgRes.data : [])
                        renderPanelMedicalBackground(medBgRows)
                    }

                    var visitsRes = results[1]
                    if (!visitsRes || !visitsRes.ok || !visitsRes.data) {
                        showInlineBox(panelVisitsError, 'Failed to load visits.')
                        renderPanelVisits([])
                    } else {
                        var visitRows = Array.isArray(visitsRes.data.data) ? visitsRes.data.data : (Array.isArray(visitsRes.data) ? visitsRes.data : [])
                        renderPanelVisits(visitRows)
                    }

                    var vitalsRes = results[2]
                    if (!vitalsRes || !vitalsRes.ok || !vitalsRes.data) {
                        showInlineBox(panelVitalsError, 'Failed to load vitals.')
                        renderPanelVitals([])
                    } else {
                        var vitalRows = Array.isArray(vitalsRes.data.data) ? vitalsRes.data.data : (Array.isArray(vitalsRes.data) ? vitalsRes.data : [])
                        renderPanelVitals(vitalRows)
                    }

                    var verRes = results[3]
                    if (!verRes || !verRes.ok || !verRes.data) {
                        if (panelVerificationStatus) panelVerificationStatus.textContent = '—'
                        if (panelPatientType) panelPatientType.textContent = '—'
                        if (panelVerificationId) panelVerificationId.textContent = '—'
                    } else {
                        var verRows = Array.isArray(verRes.data.data) ? verRes.data.data : (Array.isArray(verRes.data) ? verRes.data : [])
                        var latest = verRows && verRows.length ? verRows[0] : null
                        if (panelVerificationStatus) {
                            panelVerificationStatus.textContent = latest && latest.status ? String(latest.status) : 'Not submitted'
                        }
                        if (panelPatientType) {
                            panelPatientType.textContent = latest && latest.type ? String(latest.type) : '—'
                        }
                        if (panelVerificationId) {
                            var docUrl = latest && latest.document_url ? String(latest.document_url) : ''
                            if (docUrl) {
                                panelVerificationId.innerHTML = '<a href="' + docUrl.replace(/"/g,'&quot;') + '" target="_blank" class="text-green-700 underline hover:text-green-800">View ID</a>'
                            } else {
                                panelVerificationId.textContent = '—'
                            }
                        }
                    }
                })
                .catch(function () {
                    if (String(patientId || '') !== currentPatientId) {
                        return
                    }
                    showInlineBox(panelMedBgError, 'Network error while loading medical background entries.')
                    showInlineBox(panelVisitsError, 'Network error while loading visits.')
                    showInlineBox(panelVitalsError, 'Network error while loading vitals.')
                    renderPanelMedicalBackground([])
                    renderPanelVisits([])
                    renderPanelVitals([])
                })
        }

        function searchAndRender() {
            currentPage = 1
            renderPatients()
        }

        if (patientsSearch) patientsSearch.addEventListener('input', searchAndRender)
        if (sortSelect) sortSelect.addEventListener('change', searchAndRender)

        if (ageFilterButtons && ageFilterButtons.length) {
            ageFilterButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var next = this.getAttribute('data-age-filter') || 'all'
                    activeAgeFilter = next
                    setAgeFilterActiveStyles()
                    currentPage = 1
                    renderPatients()
                })
            })
        }

        if (patientsTableBody) {
            patientsTableBody.addEventListener('click', function (e) {
                var target = e && e.target ? e.target : null
                var btn = target && target.closest ? target.closest('.admin-pr-open-panel') : null
                if (!btn) return

                var patientId = btn.getAttribute('data-patient-id')
                if (!patientId) return

                var patient = findPatientById(patientId)
                var name = fullName(patient, 'Patient')
                var address = patient && patient.address ? String(patient.address) : ''
                var age = ageFromBirthdate(patient && patient.birthdate ? String(patient.birthdate) : null)
                var contact = patient && patient.contact_number ? String(patient.contact_number) : ''
                var profileImg = patient && patient.prof_path_url ? String(patient.prof_path_url) : ''

                if (panelPatientName) panelPatientName.textContent = name
                if (panelPatientContact) panelPatientContact.textContent = contact ? 'Contact: ' + contact : ''
                var metaParts = []
                if (address) metaParts.push(address)
                if (age != null) metaParts.push('Age ' + String(age))
                if (panelPatientMeta) panelPatientMeta.textContent = metaParts.join(' • ')

                if (panelProfilePic) {
                    if (profileImg) {
                        panelProfilePic.innerHTML = '<img src="' + profileImg.replace(/"/g,'&quot;') + '" alt="" class="w-full h-full object-cover">'
                    } else {
                        panelProfilePic.innerHTML = defaultProfilePicHtml
                    }
                }

                setPanelTab('background')
                openPanel()
                loadPatientPanelData(patientId)
            })
        }

        closePanel()

        if (vitalsSideClose) vitalsSideClose.addEventListener('click', closeVitalsSidePanel)

        if (panelClose) panelClose.addEventListener('click', closePanel)
        if (overlay) overlay.addEventListener('click', function () {
            closeVitalsSidePanel()
            closePanel()
        })

        if (panelTabBackground) panelTabBackground.addEventListener('click', function () { setPanelTab('background') })
        if (panelTabVisits) panelTabVisits.addEventListener('click', function () { setPanelTab('visits') })
        if (panelTabVitals) panelTabVitals.addEventListener('click', function () { setPanelTab('vitals') })

        if (panelVitalsTableBody) {
            panelVitalsTableBody.addEventListener('click', function (e) {
                var target = e && e.target ? e.target : null
                if (!target) return

                var bmiBtn = target.closest ? target.closest('.admin-vital-bmi') : null
                if (bmiBtn) {
                    e.preventDefault()
                    e.stopPropagation()
                    var vid = bmiBtn.getAttribute('data-vital-id')
                    if (!vid) return
                    toggleVitalDetails(vid)
                    setBmiForVital(vid)
                    return
                }

                var row = target.closest ? target.closest('tr[data-vital-id]') : null
                if (!row) return
                var vitalId = row.getAttribute('data-vital-id')
                if (!vitalId) return
                toggleVitalDetails(vitalId)
            })
        }

        setAgeFilterActiveStyles()
        loadPatients()
    })
</script>