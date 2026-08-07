<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ url('/dashboard/laboratory?role=laboratory&section=records') }}"
               data-spa-nav="1"
               class="w-8 h-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 mb-1">Patient Records</h1>
                <p class="text-sm text-slate-500">Viewing all services for this patient.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-[0.72rem] text-slate-400">
            <div class="w-8 h-8 rounded-full bg-green-100 border border-green-200 flex items-center justify-center text-green-700 font-bold text-[0.62rem]">MHO</div>
            <span>Primary Care Facility</span>
        </div>
    </div>

    {{-- ── Patient info card ── --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50/60 to-white flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <x-lucide-user class="w-4 h-4" />
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Patient Information</h2>
                    <p class="text-[0.7rem] text-slate-500 mt-0.5">Basic details for this record</p>
                </div>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                <div>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Patient ID</p>
                    <p class="text-sm font-bold text-slate-800" id="labRvPatientId">—</p>
                </div>
                <div>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Full Name</p>
                    <p class="text-sm font-bold text-slate-800" id="labRvPatientName">—</p>
                </div>
                <div>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Age</p>
                    <p class="text-sm font-bold text-slate-800" id="labRvPatientAge">—</p>
                </div>
                <div>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Gender</p>
                    <p class="text-sm font-bold text-slate-800 capitalize" id="labRvPatientGender">—</p>
                </div>
                <div>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Date Registered</p>
                    <p class="text-sm font-bold text-slate-800" id="labRvPatientDate">—</p>
                    <p class="text-xs text-slate-400" id="labRvPatientTime"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Services & results ── --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Patient Services &amp; Results</h2>
                <p class="text-[0.7rem] text-slate-500 mt-0.5" id="labRvServiceCount">0 services on record</p>
            </div>
            <select id="labRvCategoryFilter" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All Services</option>
                <option value="xray">X-Ray</option>
                <option value="ultrasound">Ultrasound</option>
                <option value="laboratory">Laboratory</option>
            </select>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="labRvServiceGrid">
                <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 py-10 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                            <x-lucide-clipboard-list class="w-7 h-7" />
                        </div>
                        <p class="text-slate-400 text-[0.8rem] font-medium">No services on record</p>
                        <p class="text-slate-400 text-[0.7rem]">Services and results will appear here once the backend is connected.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Service detail modal ── --}}
<div id="labRvDetailModal" class="hidden fixed inset-0 z-[80] bg-black/70 items-center justify-center p-4">
    <div class="w-full max-w-2xl h-[85vh] rounded-2xl bg-white border border-slate-200 shadow-[0_20px_80px_rgba(15,23,42,0.35)] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 shrink-0">
            <div>
                <h2 class="text-sm font-semibold text-slate-900" id="labRvModalTitle">Service Details</h2>
                <p class="text-xs text-slate-500 mt-0.5" id="labRvModalSub">Result details for this service</p>
            </div>
            <button type="button" id="labRvModalClose" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                <x-lucide-x class="w-4 h-4" />
            </button>
        </div>
        <div class="flex-1 min-h-0 overflow-y-auto p-5" id="labRvModalBody">
            <p class="text-[0.8rem] text-slate-400 text-center py-8">No result details available.</p>
        </div>
    </div>
</div>

<script>
(function () {
    if (typeof window.apiFetch !== 'function') return

    var grid = document.getElementById('labRvServiceGrid')
    var filterEl = document.getElementById('labRvCategoryFilter')
    var countEl = document.getElementById('labRvServiceCount')
    var modal = document.getElementById('labRvDetailModal')
    var modalClose = document.getElementById('labRvModalClose')
    var modalBody = document.getElementById('labRvModalBody')
    var modalTitle = document.getElementById('labRvModalTitle')

    var allServices = []

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;')
    }

    var CATEGORY_META = {
        laboratory: { icon: 'flask', color: 'text-purple-600', bg: 'bg-purple-50', border: 'border-purple-100', badge: 'bg-purple-100 text-purple-700' },
        ultrasound: { icon: 'activity', color: 'text-blue-600', bg: 'bg-blue-50', border: 'border-blue-100', badge: 'bg-blue-100 text-blue-700' },
        xray: { icon: 'scan', color: 'text-green-600', bg: 'bg-green-50', border: 'border-green-100', badge: 'bg-green-100 text-green-700' },
    }

    function iconHtml(name) {
        if (name === 'flask') return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg>'
        if (name === 'activity') return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>'
        return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/></svg>'
    }

    function categoryFor(name) {
        var s = String(name || '').toLowerCase()
        if (s.indexOf('ultrasound') !== -1 || s.indexOf('utz') !== -1 || s.indexOf('abdomen') !== -1 || s.indexOf('transvaginal') !== -1 || s.indexOf('doppler') !== -1 || s.indexOf('thyroid') !== -1 || s.indexOf('breast') !== -1 || s.indexOf('renal') !== -1 || s.indexOf('pelvic') !== -1) return 'ultrasound'
        if (s.indexOf('lab') !== -1 || s.indexOf('urinalysis') !== -1 || s.indexOf('fecalysis') !== -1 || s.indexOf('cbc') !== -1 || s.indexOf('blood') !== -1 || s.indexOf('urine') !== -1 || s.indexOf('stool') !== -1 || s.indexOf('glucose') !== -1 || s.indexOf('lipid') !== -1 || s.indexOf('chemistry') !== -1 || s.indexOf('hematology') !== -1 || s.indexOf('cholesterol') !== -1 || s.indexOf('sugar') !== -1) return 'laboratory'
        return 'xray'
    }

    function openModal(service) {
        if (!modal || !service) return
        if (modalTitle) modalTitle.textContent = escapeHtml(service.name || 'Service Details')
        var subEl = document.getElementById('labRvModalSub')
        if (subEl) subEl.textContent = 'Status: ' + escapeHtml(service.status || '—')
        if (modalBody) {
            var findings = service.findings || {}
            var keys = Object.keys(findings)
            if (!keys.length) {
                modalBody.innerHTML = '<p class="text-[0.8rem] text-slate-400 text-center py-8">No result details available for this service.</p>'
            } else {
                var html = '<div class="space-y-2.5">'
                keys.forEach(function (k) {
                    html += '<div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">' +
                        '<span class="text-[0.75rem] font-medium text-slate-500 capitalize">' + escapeHtml(k.replace(/_/g, ' ')) + '</span>' +
                        '<span class="text-[0.75rem] font-semibold text-slate-800">' + escapeHtml(findings[k]) + '</span></div>'
                })
                html += '</div>'
                modalBody.innerHTML = html
            }
        }
        modal.classList.remove('hidden')
        modal.classList.add('flex')
    }

    function renderServices() {
        if (!grid) return
        var filter = filterEl ? filterEl.value : ''
        var items = filter ? allServices.filter(function (s) { return s.category === filter }) : allServices
        if (countEl) countEl.textContent = items.length + ' service' + (items.length === 1 ? '' : 's') + ' on record'

        if (!items.length) {
            grid.innerHTML = '<div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 py-10 text-center">' +
                '<div class="flex flex-col items-center gap-2">' +
                '<div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h5"/></svg></div>' +
                '<p class="text-slate-400 text-[0.8rem] font-medium">No services found</p></div></div>'
            return
        }

        var html = ''
        items.forEach(function (s, i) {
            var meta = CATEGORY_META[s.category] || CATEGORY_META.xray
            var status = String(s.status || 'awaiting_result').toLowerCase()
            var statusLabel = status === 'completed' ? 'Completed' : 'Awaiting Result'
            var statusClass = status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-orange-50 text-orange-700 border-orange-100'
            html += '<div class="border border-slate-200 rounded-2xl p-4 transition-all duration-200 hover:shadow-md hover:border-green-300 bg-white relative cursor-pointer" data-category="' + s.category + '">' +
                '<div class="absolute top-3 right-3"><span class="inline-flex px-2 py-0.5 rounded-full text-[0.62rem] font-semibold border ' + statusClass + '">' + statusLabel + '</span></div>' +
                '<div class="w-10 h-10 rounded-xl ' + meta.bg + ' border ' + meta.border + ' flex items-center justify-center ' + meta.color + ' mb-3">' + iconHtml(meta.icon) + '</div>' +
                '<div class="text-[0.82rem] font-semibold text-slate-800 pr-16">' + escapeHtml(s.name || 'Unknown Service') + '</div>' +
                '<div class="text-[0.68rem] text-slate-400 mt-1">' + escapeHtml(s.encoded_by || 'MHO Primary Care Facility') + '</div>' +
                '<div class="flex items-center justify-between mt-3">' +
                '<span class="text-[0.62rem] text-slate-400">' + escapeHtml(s.date_time || '—') + '</span>' +
                '<button type="button" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-green-200 bg-green-50 text-green-700 text-[0.65rem] font-semibold hover:bg-green-100 transition-colors" data-service-detail="' + i + '">View details</button>' +
                '</div></div>'
        })
        grid.innerHTML = html
        grid.querySelectorAll('[data-service-detail]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openModal(items[Number(this.getAttribute('data-service-detail'))])
            })
        })
    }

    if (filterEl) filterEl.addEventListener('change', renderServices)
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

    if (typeof window.fetchDashboardData === 'function') {
        window.fetchDashboardData('records-view', { view: window.location.search.match(/[?&]view=([^&]+)/) ? decodeURIComponent(window.location.search.match(/[?&]view=([^&]+)/)[1]) : '' })
            .then(function (payload) {
                var data = (payload && payload.data) ? payload.data : {}
                var set = function (id, val) { var el = document.getElementById(id); if (el) el.textContent = String(val == null ? '—' : val) }
                set('labRvPatientId', data.patient_id)
                set('labRvPatientName', data.full_name)
                set('labRvPatientAge', data.age)
                set('labRvPatientGender', data.gender)
                set('labRvPatientDate', data.date)
                set('labRvPatientTime', data.time)
                allServices = Array.isArray(data.services) ? data.services.map(function (s) {
                    return { name: s.name || s.services || 'Unknown', status: s.status, findings: s.findings || {}, encoded_by: s.encoded_by, date_time: s.date_time, category: categoryFor(s.name || s.services) }
                }) : []
                renderServices()
            })
            .catch(function () { renderServices() })
    }
})();
</script>
