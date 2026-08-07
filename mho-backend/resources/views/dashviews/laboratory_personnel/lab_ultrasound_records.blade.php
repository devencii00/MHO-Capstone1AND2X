<div class="space-y-6">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">Ultrasound Records</h1>
            <p class="text-sm text-slate-500">List of all ultrasound patient results.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="labUsRecPrintBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-printer class="w-3.5 h-3.5" />
                Print
            </button>
            <button type="button" id="labUsRecResetBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-500 text-[0.72rem] font-semibold hover:bg-slate-50 transition-colors">
                <x-lucide-rotate-cw class="w-3.5 h-3.5" />
                Reset
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <x-lucide-search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input type="text" id="labUsRecSearch"
                       class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 py-2 text-[0.78rem] text-slate-800 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all"
                       placeholder="Search Patient ID, Name..." autocomplete="off">
            </div>
            <select id="labUsRecStatusFilter" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All Status</option>
                <option value="awaiting_result">Awaiting Result</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-[0.75rem] text-slate-600 whitespace-nowrap">
                <thead class="text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Patient ID</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Patient Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Service</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Exam Date</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="labUsRecTableBody">
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                    <x-lucide-activity class="w-7 h-7" />
                                </div>
                                <p class="text-slate-400 text-[0.8rem] font-medium">No ultrasound records found</p>
                                <p class="text-slate-400 text-[0.7rem]">Records will appear here once the backend is connected.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-[0.7rem] text-slate-400" id="labUsRecCount">Showing 0 records</p>
        </div>
    </div>
</div>

<script>
(function () {
    if (typeof window.apiFetch !== 'function') return

    var searchEl = document.getElementById('labUsRecSearch')
    var statusEl = document.getElementById('labUsRecStatusFilter')
    var resetBtn = document.getElementById('labUsRecResetBtn')
    var printBtn = document.getElementById('labUsRecPrintBtn')
    var body = document.getElementById('labUsRecTableBody')
    var countEl = document.getElementById('labUsRecCount')

    var allRows = []

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;')
    }

    function badge(st) {
        st = String(st || 'awaiting_result').toLowerCase()
        return st === 'completed'
            ? '<span class="inline-flex px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[0.65rem] font-semibold">Completed</span>'
            : '<span class="inline-flex px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-100 text-[0.65rem] font-semibold">Awaiting Result</span>'
    }

    function renderRows(rows) {
        if (!body) return
        if (!rows.length) {
            body.innerHTML = '<tr><td colspan="6" class="px-4 py-12 text-center">' +
                '<div class="flex flex-col items-center gap-2">' +
                '<div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>' +
                '<p class="text-slate-400 text-[0.8rem] font-medium">No matching records</p></div></td></tr>'
            if (countEl) countEl.textContent = 'Showing 0 records'
            return
        }

        var html = ''
        rows.forEach(function (r) {
            var viewUrl = '/dashboard/laboratory_personnel?role=laboratory_personnel&section=results&view=' + encodeURIComponent(r.id || '') + '&type=ultrasound'
            html += '<tr class="hover:bg-slate-50/60 transition-colors" data-status="' + escapeHtml(r.status || '') + '">' +
                '<td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[0.68rem] font-semibold">' + escapeHtml(r.patient_id || '—') + '</span></td>' +
                '<td class="px-4 py-3 font-medium text-slate-800">' + escapeHtml(r.patient_name || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.service_name || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(r.exam_date || '—') + '</td>' +
                '<td class="px-4 py-3">' + badge(r.status) + '</td>' +
                '<td class="px-4 py-3 text-right"><a href="' + viewUrl + '" data-spa-nav="1" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-green-200 bg-green-50 text-green-700 text-[0.65rem] font-semibold hover:bg-green-100 transition-colors">View</a></td>' +
                '</tr>'
        })
        body.innerHTML = html
        if (countEl) countEl.textContent = 'Showing ' + rows.length + ' records'
    }

    function applyFilters() {
        var q = (searchEl ? searchEl.value : '').trim().toLowerCase()
        var st = statusEl ? statusEl.value : ''
        var filtered = allRows.filter(function (r) {
            if (q) {
                var haystack = [r.patient_id, r.patient_name, r.service_name].join(' ').toLowerCase()
                if (haystack.indexOf(q) === -1) return false
            }
            if (st && String(r.status || '').toLowerCase() !== st) return false
            return true
        })
        renderRows(filtered)
    }

    function loadData() {
        if (typeof window.fetchDashboardData !== 'function') return
        window.fetchDashboardData('ultrasound-records')
            .then(function (payload) {
                allRows = (payload && payload.data && Array.isArray(payload.data.records)) ? payload.data.records : []
                applyFilters()
            })
            .catch(function () {
                allRows = []
                renderRows([])
            })
    }

    if (searchEl) searchEl.addEventListener('input', applyFilters)
    if (statusEl) statusEl.addEventListener('change', applyFilters)
    if (resetBtn) resetBtn.addEventListener('click', function () {
        if (searchEl) searchEl.value = ''
        if (statusEl) statusEl.value = ''
        applyFilters()
        loadData()
    })
    if (printBtn) printBtn.addEventListener('click', function () { window.print() })

    loadData()
})();
</script>
