<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 mb-1">Medical Results</h1>
        <p class="text-sm text-slate-500">List of all patient results across departments.</p>
    </div>

    {{-- ── Stat cards ── --}}
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">Laboratory</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                    <x-lucide-flask-conical class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labResStatLab"><span class="skeleton h-6 w-10"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">Lab results</p>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">X-Ray</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600">
                    <x-lucide-scan-line class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labResStatXray"><span class="skeleton h-6 w-10"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">X-Ray results</p>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">Ultrasound</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                    <x-lucide-activity class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labResStatUs"><span class="skeleton h-6 w-10"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">Ultrasound results</p>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">Total results</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <x-lucide-clipboard-list class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labResStatTotal"><span class="skeleton h-6 w-10"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">All medical results</p>
        </div>
    </div>

    {{-- ── Results table ── --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="relative w-full sm:w-72">
                <x-lucide-search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input type="text" id="labResSearch"
                       class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 py-2 text-[0.78rem] text-slate-800 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all"
                       placeholder="Search patient..." autocomplete="off">
            </div>
            <div class="flex items-center gap-2">
                <select id="labResStatusFilter" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">All statuses</option>
                    <option value="awaiting_result">Awaiting Result</option>
                    <option value="completed">Completed</option>
                </select>
                <select id="labResDeptFilter" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">All departments</option>
                    <option value="laboratory">Laboratory</option>
                    <option value="xray">X-Ray</option>
                    <option value="ultrasound">Ultrasound</option>
                </select>
                <button type="button" id="labResClearBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-500 text-[0.72rem] font-semibold hover:bg-slate-50 transition-colors">
                    <x-lucide-x class="w-3.5 h-3.5" />
                    Clear
                </button>
            </div>
        </div>

        <div class="overflow-x-auto overflow-y-auto scrollbar-hidden min-h-[400px]">
            <table class="w-full text-left text-[0.75rem] text-slate-600 whitespace-nowrap">
                <thead class="text-slate-500 border-b border-slate-300 bg-slate-50/60">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Patient ID</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Last Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">First Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Middle Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Service</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Department</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300" id="labResTableBody">
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2.5">
                                <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-300">
                                    <x-lucide-clipboard-list class="w-5 h-5" />
                                </div>
                                <p class="text-sm font-medium text-slate-400">No medical results</p>
                                <p class="text-[0.7rem] text-slate-400">Results will appear here once the backend is connected.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-[0.7rem] text-slate-400" id="labResCount">Showing 0 of 0 records</p>
            <div class="flex items-center gap-1">
                <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 bg-white text-slate-400 text-[0.75rem] hover:border-green-400 hover:text-green-600 disabled:opacity-40" disabled>‹</button>
                <span class="w-7 h-7 rounded-lg bg-green-600 text-white text-[0.75rem] flex items-center justify-center font-semibold">1</span>
                <button type="button" class="w-7 h-7 rounded-lg border border-slate-200 bg-white text-slate-400 text-[0.75rem] hover:border-green-400 hover:text-green-600 disabled:opacity-40" disabled>›</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    if (typeof window.apiFetch !== 'function') return

    var searchEl = document.getElementById('labResSearch')
    var statusEl = document.getElementById('labResStatusFilter')
    var deptEl = document.getElementById('labResDeptFilter')
    var clearBtn = document.getElementById('labResClearBtn')
    var body = document.getElementById('labResTableBody')
    var countEl = document.getElementById('labResCount')

    var allRows = []

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;')
    }

    function categoryFor(name) {
        var s = String(name || '').toLowerCase()
        if (s.indexOf('ultrasound') !== -1 || s.indexOf('utz') !== -1 || s.indexOf('abdomen') !== -1 || s.indexOf('transvaginal') !== -1 || s.indexOf('doppler') !== -1 || s.indexOf('thyroid') !== -1 || s.indexOf('breast') !== -1 || s.indexOf('renal') !== -1 || s.indexOf('pelvic') !== -1) return 'ultrasound'
        if (s.indexOf('lab') !== -1 || s.indexOf('urinalysis') !== -1 || s.indexOf('fecalysis') !== -1 || s.indexOf('cbc') !== -1 || s.indexOf('blood') !== -1 || s.indexOf('urine') !== -1 || s.indexOf('stool') !== -1 || s.indexOf('glucose') !== -1 || s.indexOf('lipid') !== -1 || s.indexOf('chemistry') !== -1 || s.indexOf('hematology') !== -1 || s.indexOf('cholesterol') !== -1 || s.indexOf('sugar') !== -1) return 'laboratory'
        return 'xray'
    }

    function statusBadge(status) {
        var s = String(status || 'awaiting_result').toLowerCase()
        if (s === 'completed') {
            return '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[0.65rem] font-semibold"><svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Completed</span>'
        }
        if (s === 'in_progress') {
            return '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-[0.65rem] font-semibold">Processing</span>'
        }
        return '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-100 text-[0.65rem] font-semibold"><svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Awaiting Result</span>'
    }

    function renderRows(rows) {
        if (!body) return
        if (!rows.length) {
            body.innerHTML = '<tr><td colspan="8" class="px-4 py-12 text-center">' +
                '<div class="flex flex-col items-center gap-2">' +
                '<div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-300"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h5"/><circle cx="18" cy="18" r="3"/><path d="M20.5 20.5L22.5 22.5"/></svg></div>' +
                '<p class="text-sm font-medium text-slate-400">No matching results</p>' +
                '<p class="text-slate-400 text-[0.7rem]">Try a different search term or filter.</p>' +
                '</div></td></tr>'
            if (countEl) countEl.textContent = 'Showing 0 of 0 records'
            return
        }

        var html = ''
        rows.forEach(function (r, i) {
            var dept = r.department || categoryFor(r.service_name)
            var encodeUrl = '/dashboard/laboratory_personnel?role=laboratory_personnel&section=results&view=' + encodeURIComponent(r.result_id || r.id || i) + '&type=' + encodeURIComponent(dept)
            var action = (r.status || 'awaiting_result') === 'completed'
                ? '<span class="text-slate-300 text-[0.7rem]">—</span>'
                : '<a href="' + encodeUrl + '" data-spa-nav="1" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-green-600 text-white text-[0.65rem] font-semibold hover:bg-green-700 transition-colors"><svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Input Result</a>'
            html += '<tr class="hover:bg-slate-50/60 transition-colors">' +
                '<td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[0.68rem] font-semibold">' + escapeHtml(r.patient_id || '—') + '</span></td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.last_name || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.first_name || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.middle_name || '—') + '</td>' +
                '<td class="px-4 py-3 font-medium text-slate-800">' + escapeHtml(r.service_name || '—') + '</td>' +
                '<td class="px-4 py-3"><span class="text-[0.68rem] capitalize">' + escapeHtml(dept) + '</span></td>' +
                '<td class="px-4 py-3">' + statusBadge(r.status) + '</td>' +
                '<td class="px-4 py-3 text-right">' + action + '</td>' +
                '</tr>'
        })
        body.innerHTML = html
        if (countEl) countEl.textContent = 'Showing ' + rows.length + ' of ' + allRows.length + ' records'
    }

    function applyFilters() {
        var q = (searchEl ? searchEl.value : '').trim().toLowerCase()
        var st = statusEl ? statusEl.value : ''
        var dp = deptEl ? deptEl.value : ''
        var filtered = allRows.filter(function (r) {
            if (q) {
                var haystack = [r.first_name, r.last_name, r.middle_name, r.patient_id, r.service_name].join(' ').toLowerCase()
                if (haystack.indexOf(q) === -1) return false
            }
            if (st && String(r.status || '').toLowerCase() !== st) return false
            if (dp) {
                var dept = r.department || categoryFor(r.service_name)
                if (dept !== dp) return false
            }
            return true
        })
        renderRows(filtered)
    }

    function loadData() {
        if (typeof window.fetchDashboardData !== 'function') return
        window.fetchDashboardData('results')
            .then(function (payload) {
                var rows = (payload && payload.data && Array.isArray(payload.data.results)) ? payload.data.results : []
                allRows = rows
                applyFilters()
                var d = (payload && payload.data) ? payload.data : {}
                var set = function (id, v) { var el = document.getElementById(id); if (el) el.textContent = String(v == null ? '0' : v) }
                set('labResStatLab', d.lab_count)
                set('labResStatXray', d.xray_count)
                set('labResStatUs', d.ultrasound_count)
                set('labResStatTotal', d.total_count)
            })
            .catch(function () {
                allRows = []
                renderRows([])
            })
    }

    if (searchEl) searchEl.addEventListener('input', applyFilters)
    if (statusEl) statusEl.addEventListener('change', applyFilters)
    if (deptEl) deptEl.addEventListener('change', applyFilters)
    if (clearBtn) clearBtn.addEventListener('click', function () {
        if (searchEl) searchEl.value = ''
        if (statusEl) statusEl.value = ''
        if (deptEl) deptEl.value = ''
        applyFilters()
    })

    loadData()
})();
</script>
