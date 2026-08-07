<div class="space-y-6">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">Patient Records</h1>
            <p class="text-sm text-slate-500">Manage and view patient records across the facility.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="labRecordsPrintBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-printer class="w-3.5 h-3.5" />
                Print
            </button>
            <button type="button" id="labRecordsRefreshBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-orange-500 text-white text-[0.72rem] font-semibold hover:bg-orange-600 transition-colors">
                <x-lucide-rotate-cw class="w-3.5 h-3.5" />
                Refresh
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        {{-- Toolbar --}}
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="relative w-full sm:w-72">
                <x-lucide-search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input type="text" id="labRecordsSearch"
                       class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 py-2 text-[0.78rem] text-slate-800 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all"
                       placeholder="Search patient name, ID..." autocomplete="off">
            </div>
            <div class="flex items-center gap-2">
                <input type="date" id="labRecordsDateFilter"
                       class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <button type="button" id="labRecordsClearFilters" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-500 text-[0.72rem] font-semibold hover:bg-slate-50 transition-colors">
                    <x-lucide-x class="w-3.5 h-3.5" />
                    Clear
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto overflow-y-auto scrollbar-hidden min-h-[400px]">
            <table class="w-full text-left text-[0.75rem] text-slate-600 whitespace-nowrap">
                <thead class="text-slate-500 border-b border-slate-300 bg-slate-50/60">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Sr#</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">ID Code</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Patient Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">D.O.B</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Gender</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Age</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Date</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Time</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300" id="labRecordsTableBody">
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2.5">
                                <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-300">
                                    <x-lucide-folder-open class="w-5 h-5" />
                                </div>
                                <p class="text-sm font-medium text-slate-400">No patient records found</p>
                                <p class="text-[0.7rem] text-slate-400">Records will appear here once the backend is connected.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-[0.7rem] text-slate-400" id="labRecordsCount">Showing 0 of 0 records</p>
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

    var searchEl = document.getElementById('labRecordsSearch')
    var dateEl = document.getElementById('labRecordsDateFilter')
    var clearBtn = document.getElementById('labRecordsClearFilters')
    var refreshBtn = document.getElementById('labRecordsRefreshBtn')
    var printBtn = document.getElementById('labRecordsPrintBtn')
    var body = document.getElementById('labRecordsTableBody')
    var countEl = document.getElementById('labRecordsCount')

    var allRows = []

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;')
    }

    function renderRows(rows) {
        if (!body) return
        if (!rows.length) {
            body.innerHTML =
                '<tr><td colspan="10" class="px-4 py-12 text-center">' +
                '<div class="flex flex-col items-center gap-2">' +
                '<div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-300"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></div>' +
                '<p class="text-sm font-medium text-slate-400">No matching records</p>' +
                '<p class="text-slate-400 text-[0.7rem]">Try a different search term or date.</p>' +
                '</div></td></tr>'
            if (countEl) countEl.textContent = 'Showing 0 of 0 records'
            return
        }

        var html = ''
        rows.forEach(function (r, i) {
            var status = String(r.status || 'pending').toLowerCase()
            var statusClass = status === 'confirmed'
                ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                : (status === 'pending' ? 'bg-orange-50 text-orange-700 border-orange-100' : 'bg-slate-50 text-slate-600 border-slate-200')
            html += '<tr class="hover:bg-slate-50/60 transition-colors">' +
                '<td class="px-4 py-3">' + String(i + 1).padStart(2, '0') + '</td>' +
                '<td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[0.68rem] font-semibold">' + escapeHtml(r.patient_id || '—') + '</span></td>' +
                '<td class="px-4 py-3"><div class="flex items-center gap-2.5">' +
                '<span class="w-7 h-7 rounded-full bg-green-100 text-green-700 text-[0.62rem] font-bold flex items-center justify-center shrink-0">' + escapeHtml(r.initials || '—') + '</span>' +
                '<span class="font-medium text-slate-800">' + escapeHtml(r.full_name || r.patient_name || 'Unknown Patient') + '</span></div></td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.dob || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.gender || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.age != null ? r.age : '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.date || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.time || '—') + '</td>' +
                '<td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-full border text-[0.65rem] font-semibold ' + statusClass + '">' + escapeHtml(status.charAt(0).toUpperCase() + status.slice(1)) + '</span></td>' +
                '<td class="px-4 py-3 text-right"><a href="' + escapeHtml(r.view_url || '/dashboard/laboratory_personnel?role=laboratory_personnel&section=records-view&view=1') + '" data-spa-nav="1" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-green-200 bg-green-50 text-green-700 text-[0.65rem] font-semibold hover:bg-green-100 transition-colors">View</a></td>' +
                '</tr>'
        })
        body.innerHTML = html
        if (countEl) countEl.textContent = 'Showing ' + rows.length + ' of ' + allRows.length + ' records'
    }

    function applyFilters() {
        var q = (searchEl ? searchEl.value : '').trim().toLowerCase()
        var d = dateEl ? dateEl.value : ''
        var filtered = allRows.filter(function (r) {
            if (q) {
                var haystack = [r.full_name, r.patient_name, r.patient_id, r.first_name, r.last_name].join(' ').toLowerCase()
                if (haystack.indexOf(q) === -1) return false
            }
            if (d && r.date_raw && r.date_raw !== d) return false
            return true
        })
        renderRows(filtered)
    }

    function loadData() {
        if (typeof window.fetchDashboardData !== 'function') return
        window.fetchDashboardData('records')
            .then(function (payload) {
                var rows = (payload && payload.data && Array.isArray(payload.data.records)) ? payload.data.records : []
                allRows = rows
                applyFilters()
            })
            .catch(function () {
                allRows = []
                renderRows([])
            })
    }

    if (searchEl) searchEl.addEventListener('input', applyFilters)
    if (dateEl) dateEl.addEventListener('change', applyFilters)
    if (clearBtn) clearBtn.addEventListener('click', function () {
        if (searchEl) searchEl.value = ''
        if (dateEl) dateEl.value = ''
        applyFilters()
    })
    if (refreshBtn) refreshBtn.addEventListener('click', loadData)
    if (printBtn) printBtn.addEventListener('click', function () { window.print() })

    loadData()
})();
</script>
