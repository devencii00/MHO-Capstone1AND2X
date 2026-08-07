<div class="space-y-6">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">Laboratory Records</h1>
            <p class="text-sm text-slate-500">List of all laboratory patient results.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="labLabRecPrintBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-printer class="w-3.5 h-3.5" />
                Print
            </button>
            <button type="button" id="labLabRecResetBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-500 text-[0.72rem] font-semibold hover:bg-slate-50 transition-colors">
                <x-lucide-rotate-cw class="w-3.5 h-3.5" />
                Reset
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <x-lucide-search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                <input type="text" id="labLabRecSearch"
                       class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 py-2 text-[0.78rem] text-slate-800 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all"
                       placeholder="Search Patient ID, Name..." autocomplete="off">
            </div>
            <select id="labLabRecStatusFilter" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
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
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Last Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">First Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Middle Name</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Services</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3 font-semibold text-[0.68rem] uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="labLabRecTableBody">
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                    <x-lucide-flask-conical class="w-7 h-7" />
                                </div>
                                <p class="text-slate-400 text-[0.8rem] font-medium">No laboratory records found</p>
                                <p class="text-slate-400 text-[0.7rem]">Records will appear here once the backend is connected.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-[0.7rem] text-slate-400" id="labLabRecCount">Showing 0 records</p>
        </div>
    </div>
</div>

{{-- ── View Services modal ── --}}
<div id="labLabRecModal" class="hidden fixed inset-0 z-[80] bg-black/70 items-center justify-center p-4">
    <div class="w-full max-w-2xl h-[85vh] rounded-2xl bg-white border border-slate-200 shadow-[0_20px_80px_rgba(15,23,42,0.35)] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 shrink-0">
            <div>
                <h2 class="text-sm font-semibold text-slate-900" id="labLabRecModalTitle">Patient Services</h2>
                <p class="text-xs text-slate-500 mt-0.5" id="labLabRecModalSub">Laboratory services for this patient</p>
            </div>
            <button type="button" id="labLabRecModalClose" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                <x-lucide-x class="w-4 h-4" />
            </button>
        </div>
        <div class="flex-1 min-h-0 overflow-y-auto p-5" id="labLabRecModalBody">
            <p class="text-[0.8rem] text-slate-400 text-center py-8">No services available.</p>
        </div>
    </div>
</div>

<script>
(function () {
    if (typeof window.apiFetch !== 'function') return

    var searchEl = document.getElementById('labLabRecSearch')
    var statusEl = document.getElementById('labLabRecStatusFilter')
    var resetBtn = document.getElementById('labLabRecResetBtn')
    var printBtn = document.getElementById('labLabRecPrintBtn')
    var body = document.getElementById('labLabRecTableBody')
    var countEl = document.getElementById('labLabRecCount')
    var modal = document.getElementById('labLabRecModal')
    var modalClose = document.getElementById('labLabRecModalClose')
    var modalBody = document.getElementById('labLabRecModalBody')
    var modalTitle = document.getElementById('labLabRecModalTitle')
    var modalSub = document.getElementById('labLabRecModalSub')

    var allRows = []

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;')
    }

    function openServices(patient) {
        if (!modal || !patient) return
        if (modalTitle) modalTitle.textContent = escapeHtml(patient.patient_name || 'Patient Services')
        if (modalSub) modalSub.textContent = 'Patient ID: ' + escapeHtml(patient.patient_id || '—')
        if (modalBody) {
            var services = Array.isArray(patient.services) ? patient.services : []
            if (!services.length) {
                modalBody.innerHTML = '<p class="text-[0.8rem] text-slate-400 text-center py-8">No laboratory services for this patient.</p>'
            } else {
                var html = '<div class="space-y-2.5">'
                services.forEach(function (svc) {
                    var st = String(svc.status || 'awaiting_result').toLowerCase()
                    var badge = st === 'completed'
                        ? '<span class="inline-flex px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[0.62rem] font-semibold">Completed</span>'
                        : '<span class="inline-flex px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-100 text-[0.62rem] font-semibold">Awaiting Result</span>'
                    var encodeUrl = '/dashboard/laboratory_personnel?role=laboratory_personnel&section=results&view=' + encodeURIComponent(svc.id || '') + '&type=laboratory'
                    html += '<div class="flex items-center justify-between gap-3 p-3.5 rounded-xl border border-slate-200 bg-white">' +
                        '<div class="min-w-0"><div class="text-[0.8rem] font-semibold text-slate-800 truncate">' + escapeHtml(svc.service_name || 'Unknown Service') + '</div>' +
                        '<div class="text-[0.65rem] text-slate-400 mt-0.5">' + (st === 'completed' ? 'Result released' : 'Result pending') + '</div></div>' +
                        '<div class="flex items-center gap-2 shrink-0">' + badge +
                        (st === 'completed'
                            ? '<a href="' + encodeUrl + '" data-spa-nav="1" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-green-200 bg-green-50 text-green-700 text-[0.65rem] font-semibold hover:bg-green-100 transition-colors">View</a>'
                            : '<a href="' + encodeUrl + '" data-spa-nav="1" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-green-600 text-white text-[0.65rem] font-semibold hover:bg-green-700 transition-colors">Encode Result</a>') +
                        '</div></div>'
                })
                html += '</div>'
                modalBody.innerHTML = html
            }
        }
        modal.classList.remove('hidden')
        modal.classList.add('flex')
    }

    function renderRows(rows) {
        if (!body) return
        if (!rows.length) {
            body.innerHTML = '<tr><td colspan="7" class="px-4 py-12 text-center">' +
                '<div class="flex flex-col items-center gap-2">' +
                '<div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg></div>' +
                '<p class="text-slate-400 text-[0.8rem] font-medium">No matching records</p></div></td></tr>'
            if (countEl) countEl.textContent = 'Showing 0 records'
            return
        }

        var html = ''
        rows.forEach(function (p, i) {
            var st = String(p.overall_status || 'awaiting_result').toLowerCase()
            var badge = st === 'completed'
                ? '<span class="inline-flex px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[0.65rem] font-semibold">Completed</span>'
                : '<span class="inline-flex px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-100 text-[0.65rem] font-semibold">Awaiting Result</span>'
            html += '<tr class="hover:bg-slate-50/60 transition-colors" data-status="' + st + '">' +
                '<td class="px-4 py-3"><span class="inline-flex px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[0.68rem] font-semibold">' + escapeHtml(p.patient_id || '—') + '</span></td>' +
                '<td class="px-4 py-3">' + escapeHtml(p.last_name || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(p.first_name || '—') + '</td>' +
                '<td class="px-4 py-3">' + escapeHtml(p.middle_name || '') + '</td>' +
                '<td class="px-4 py-3"><div class="flex items-center gap-1.5">' +
                '<span class="font-medium text-slate-800">' + escapeHtml(p.display_service || 'N/A') + '</span>' +
                (p.service_count > 1 ? '<button type="button" class="text-green-600 text-[0.65rem] font-semibold hover:underline" data-open-services="' + i + '">+' + (p.service_count - 1) + ' more</button>' : '') +
                '</div></td>' +
                '<td class="px-4 py-3">' + badge + '</td>' +
                '<td class="px-4 py-3 text-right"><button type="button" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-green-200 bg-green-50 text-green-700 text-[0.65rem] font-semibold hover:bg-green-100 transition-colors" data-open-services="' + i + '">View Services</button></td>' +
                '</tr>'
        })
        body.innerHTML = html
        if (countEl) countEl.textContent = 'Showing ' + rows.length + ' records'

        body.querySelectorAll('[data-open-services]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openServices(rows[Number(this.getAttribute('data-open-services'))])
            })
        })
    }

    function applyFilters() {
        var q = (searchEl ? searchEl.value : '').trim().toLowerCase()
        var st = statusEl ? statusEl.value : ''
        var filtered = allRows.filter(function (p) {
            if (q) {
                var haystack = [p.patient_id, p.first_name, p.last_name, p.middle_name, p.display_service].join(' ').toLowerCase()
                if (haystack.indexOf(q) === -1) return false
            }
            if (st && String(p.overall_status || '').toLowerCase() !== st) return false
            return true
        })
        renderRows(filtered)
    }

    function loadData() {
        if (typeof window.fetchDashboardData !== 'function') return
        window.fetchDashboardData('laboratory-records')
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
    if (modalClose) modalClose.addEventListener('click', function () {
        modal.classList.add('hidden')
        modal.classList.remove('flex')
    })
    if (modal) modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.add('hidden')
            modal.classList.remove('flex')
        }
    })

    loadData()
})();
</script>
