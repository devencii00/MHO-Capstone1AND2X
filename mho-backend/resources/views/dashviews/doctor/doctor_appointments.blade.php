<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900">My Appointments</h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Time &amp; days</span>
    </div>
    <p class="text-xs text-slate-500 mb-3">
        View and manage your patient appointments across days and times.
    </p>

    <div id="doctorManageDateHeader" class="hidden text-center text-sm font-semibold text-slate-700 mb-3"></div>

    <div class="flex items-center justify-between mb-3 gap-3">
        <div class="flex items-center gap-2">
            <button id="doctorManageCalendarToggle" type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-green-600 text-white border-green-600 px-3 py-1.5 text-[0.7rem] font-semibold transition-colors">
                <x-lucide-calendar class="w-[14px] h-[14px]" />
                <span id="doctorManageCalendarToggleText">Table view</span>
            </button>
            <button id="doctorManageClearFilterBtn" type="button" style="display:none" class="shrink-0 inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-[0.7rem] font-semibold text-red-700 hover:bg-red-100 transition-colors">
                <x-lucide-x class="w-[14px] h-[14px]" />
                Clear Filter
            </button>
        </div>
        <div class="flex items-center gap-2">
            <button id="doctorManageTodayOnlyBtn" type="button" class="shrink-0 inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.75rem] font-semibold text-slate-700">
                Show today only
            </button>
            <button type="button" id="doctorManageRefreshBtn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                Refresh
            </button>
        </div>
    </div>



    <div class="grid gap-3 grid-cols-1 md:grid-cols-6 items-start mb-4">
        <div class="md:col-span-2 min-w-0">
            <label for="doctorManageApptSearch" class="block text-[0.7rem] text-slate-600 mb-1">Search</label>
            <input id="doctorManageApptSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by patient name or email">
        </div>
        <div class="min-w-0">
            <label for="doctorManageServiceSearch" class="block text-[0.7rem] text-slate-600 mb-1">Service</label>
            <div class="relative">
                <input id="doctorManageServiceSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="All services" autocomplete="off">
                <input id="doctorManageServiceId" type="hidden">
                <div id="doctorManageServiceResults" class="hidden absolute left-0 right-0 top-full mt-1 w-full rounded-lg border border-slate-200 bg-white shadow-sm max-h-64 overflow-y-auto overscroll-contain z-50"></div>
            </div>
        </div>
        <div class="min-w-0">
            <label for="doctorManageSort" class="block text-[0.7rem] text-slate-600 mb-1">Sort by date</label>
            <select id="doctorManageSort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="latest">Latest first</option>
                <option value="oldest">Oldest first</option>
            </select>
        </div>
        <div class="min-w-0">
            <label for="doctorManageStatus" class="block text-[0.7rem] text-slate-600 mb-1">Status</label>
            <select id="doctorManageStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="no_show">No-show</option>
            </select>
        </div>
        <div class="min-w-0">
            <label for="doctorManageType" class="block text-[0.7rem] text-slate-600 mb-1">Type</label>
            <select id="doctorManageType" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All types</option>
                <option value="scheduled">Scheduled</option>
                <option value="walk_in">Walk In</option>
            </select>
        </div>
    </div>

    <div id="doctorManageTableArea" class="hidden">
        <div class="w-full" style="display:grid;">
            <div class="rounded-2xl border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto overflow-y-auto scrollbar-hidden mb-4 h-[470px]">
                    <table class="text-xs" style="min-width:600px;width:100%;table-layout:auto;">
                        <thead class="bg-slate-50 text-slate-600 sticky top-0">
                            <tr>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Date</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Time</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Patient</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Service</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Status</th>
                                <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Type</th>
                                <th class="text-right px-3 py-2 font-semibold whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="doctorManageAppointmentTableBody" class="divide-y divide-slate-100 bg-white"></tbody>
                    </table>
                </div>
                <div id="doctorManageAppointmentMeta" class="px-3 py-2 text-[0.72rem] text-slate-500 bg-white border-t border-slate-100 flex items-center justify-between">
                    Loading appointments…
                </div>
                <div id="doctorManagePagination" class="px-3 py-2 bg-white border-t border-slate-50 flex items-center justify-center gap-1"></div>
            </div>
        </div>
        <pre id="doctorManageAppointmentResult" class="hidden mt-3 text-[0.68rem] text-slate-600 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 overflow-x-auto"></pre>
    </div>

    <div id="doctorManageCalendarArea">
        <div class="rounded-2xl border border-slate-200 overflow-hidden bg-white">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <button id="doctorManageCalDatePrev" type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">&lsaquo;</button>
                    <div id="doctorManageCalMonthLabel" class="text-base font-bold text-slate-800"></div>
                    <button id="doctorManageCalDateNext" type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">&rsaquo;</button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-7 gap-1.5 text-[0.75rem] text-slate-400 mb-3 text-center font-medium">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>
                <div id="doctorManageCalDateGrid" class="grid grid-cols-7 grid-rows-[repeat(6,1fr)] gap-1.5 h-[520px]"></div>
            </div>
        </div>
    </div>
</div>

<!-- Doctor Patient History Modal -->
<div id="doctorManageHistoryOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl h-[90vh] max-h-none rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex overflow-hidden">
        <!-- History list (left) -->
        <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
            <div id="doctorManageHistListSection">
                <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Patient History</div>
                        <div id="doctorManageHistSubtitle" class="text-[0.72rem] text-slate-500">Loading&hellip;</div>
                    </div>
                    <button type="button" id="doctorManageHistClose" class="text-slate-400 hover:text-slate-600">
                        <x-lucide-x class="w-[20px] h-[20px]" />
                    </button>
                </div>
                <div class="px-4 py-2 border-b border-slate-100 shrink-0 grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Date</label>
                        <input id="doctorManageHistDate" type="date" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Status</label>
                        <select id="doctorManageHistStatus" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="no_show">No-show</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Type</label>
                        <select id="doctorManageHistType" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="">All</option>
                            <option value="walk_in">Walk In</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>
                </div>
                <div id="doctorManageHistBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                    <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history&hellip;</div>
                </div>
            </div>
            <!-- NO Change Appointment panel -->
        </div>
        <!-- Detail panel (right) with status actions -->
        <div id="doctorManageHistDetailPanel" class="w-1/2 flex flex-col min-h-0 bg-slate-50/50">
            <div class="px-4 py-3 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div class="text-sm font-semibold text-slate-900">Appointment Details</div>
            </div>
            <div id="doctorManageHistDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── Helper functions ──

        function escapeHtml(str) {
            var s = String(str == null ? '' : str)
            return s
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function readResponse(response) {
            return response.text().then(function (text) {
                var data = null
                try {
                    data = text ? JSON.parse(text) : null
                } catch (e) {
                    data = null
                }
                return { ok: response.ok, status: response.status, data: data, raw: text }
            })
        }

        function apiFetch(path, options) {
            // Use the global window.apiFetch (includes Bearer token)
            if (typeof window.apiFetch === 'function') {
                return window.apiFetch(path, options || {})
            }
            if (!options) options = {}
            if (!options.headers) options.headers = {}
            options.headers['X-Requested-With'] = 'XMLHttpRequest'
            options.headers['Accept'] = 'application/json'
            var token = document.querySelector('meta[name="csrf-token"]')
            if (token) options.headers['X-CSRF-TOKEN'] = token.getAttribute('content')
            // Try to get Bearer token from localStorage like the global version
            var apiToken = null
            try { apiToken = window.localStorage ? window.localStorage.getItem('api_token') : null } catch (e) { apiToken = null }
            if (apiToken) options.headers['Authorization'] = 'Bearer ' + apiToken
            return fetch(path, options)
        }

        function normalizeText(value) {
            return String(value || '').trim().toLowerCase()
        }

        function formatLocalDateIso(dateObj) {
            var d = dateObj instanceof Date ? dateObj : new Date()
            var y = d.getFullYear()
            var m = String(d.getMonth() + 1).padStart(2, '0')
            var day = String(d.getDate()).padStart(2, '0')
            return String(y) + '-' + m + '-' + day
        }

        function formatTime12h(hhmmss) {
            var t = String(hhmmss || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return t
            var parts = t.split(':')
            var h24 = parseInt(parts[0], 10)
            var m = parts[1]
            var ap = h24 >= 12 ? 'PM' : 'AM'
            var h12 = h24 % 12
            if (h12 === 0) h12 = 12
            return h12 + ':' + m + ' ' + ap
        }

        function safeIsoParts(iso) {
            var raw = String(iso || '').replace('T', ' ')
            if (raw.length >= 16) raw = raw.slice(0, 16)
            var datePart = raw.slice(0, 10)
            var timePart = raw.slice(11, 16)
            return { date: datePart, time: timePart }
        }

        function personName(person, fallback) {
            var parts = person ? [person.firstname, person.middlename, person.lastname] : []
            var name = parts.filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            return name || fallback || '-'
        }

        function serviceSummary(appt) {
            var services = appt && Array.isArray(appt.services) ? appt.services : []
            var names = services
                .map(function (s) { return String((s && s.service_name) ? s.service_name : '').trim() })
                .filter(function (v) { return v !== '' })
            if (!names.length) return '-'
            return names.join(', ')
        }

        function manageStatusLabel(appt) {
            var status = appt && appt.status ? String(appt.status) : ''
            if (!status) return ''
            if (status === 'confirmed') {
                if (appt && appt.check_in_time) return 'checked-in'
            }
            return status.replace(/_/g, ' ')
        }

        function manageRowHtml(appt) {
            if (!appt) return ''
            var id = appt.id || appt.appointment_id || ''
            var patient = appt.patient || {}
            var patientFallback = patient && patient.email ? String(patient.email) : ''
            var patientName = personName(patient, patientFallback)
            var when = safeIsoParts(appt && appt.appointment_datetime ? String(appt.appointment_datetime) : '')
            if (!when) when = { date: '-', time: '-' }
            var serviceText = serviceSummary(appt)
            var statusLabel = manageStatusLabel(appt)

            var statusKey = String(appt && appt.status ? appt.status : '').toLowerCase()
            var isCheckedIn = statusKey === 'confirmed' && (appt && appt.check_in_time)
            var statusClass = ''
            if (statusKey === 'completed') {
                statusClass = 'border-green-200 bg-green-50 text-green-700'
            } else if (isCheckedIn || statusKey === 'confirmed') {
                statusClass = 'border-orange-200 bg-orange-50 text-orange-700'
            } else if (statusKey === 'consulted') {
                statusClass = 'border-purple-200 bg-purple-50 text-purple-700'
            } else if (statusKey === 'cancelled') {
                statusClass = 'border-rose-200 bg-rose-50 text-rose-700'
            } else if (statusKey === 'no_show') {
                statusClass = 'border-slate-200 bg-slate-100 text-slate-600'
            } else if (statusKey === 'pending') {
                statusClass = 'border-amber-200 bg-amber-50 text-amber-700'
            } else {
                statusClass = 'border-slate-200 bg-slate-100 text-slate-600'
            }
            var statusDisplay = statusLabel
                ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + statusClass + '">' + escapeHtml(statusLabel) + '</span>'
                : '-'

            var apptTypeRaw = String(appt && appt.appointment_type ? appt.appointment_type : '').toLowerCase()
            var isWalkIn = apptTypeRaw === 'walk_in' || apptTypeRaw === 'walk-in' || apptTypeRaw === 'walk in'
            var apptTypeLabel = isWalkIn ? 'Walk In' : (apptTypeRaw === 'scheduled' ? 'Scheduled' : (apptTypeRaw || '-'))
            var typeClass = isWalkIn ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-purple-200 bg-purple-50 text-purple-700'
            var typeDisplay = apptTypeRaw ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + typeClass + '">' + escapeHtml(apptTypeLabel) + '</span>' : '-'

            return (
                '<tr data-appointment-id="' + escapeHtml(id) + '">' +
                    '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(when.date || '-') + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(when.time ? formatTime12h(when.time) : '-') + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 min-w-[12rem] whitespace-nowrap">' + escapeHtml(patientName) + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 min-w-[14rem] whitespace-nowrap">' + escapeHtml(serviceText) + '</td>' +
                    '<td class="px-3 py-2 whitespace-nowrap">' + statusDisplay + '</td>' +
                    '<td class="px-3 py-2 whitespace-nowrap">' + typeDisplay + '</td>' +
                    '<td class="px-3 py-2 text-right whitespace-nowrap">' +
                        '<button type="button" class="manage-see-history-btn inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300" data-patient-id="' + escapeHtml(patient && patient.user_id != null ? patient.user_id : '') + '" data-patient-name="' + escapeHtml(patientName) + '">See Details</button>' +
                    '</td>' +
                '</tr>'
            )
        }

        function renderManageAppointments(list) {
            if (!manageTableBody) return
            var rows = Array.isArray(list) ? list : []
            if (!rows.length) {
                manageTableBody.innerHTML = '<tr><td colspan="7" class="px-3 py-6 text-center text-[0.78rem] text-slate-500">No appointments found.</td></tr>'
                var pag = document.getElementById('doctorManagePagination')
                if (pag) pag.innerHTML = ''
                return
            }
            manageTableBody.innerHTML = rows.map(manageRowHtml).join('')
            renderManagePagination()
        }

        function renderManagePagination() {
            var pag = document.getElementById('doctorManagePagination')
            if (!pag) return
            if (manageTotal === 0) { pag.innerHTML = ''; return }
            var totalPages = manageLastPage
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + manageTotal + ' entries</span>'
            html += '<button type="button" class="' + (manageCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-manage-page="prev"' + (manageCurrentPage === 1 ? ' disabled' : '') + '>&lsaquo; Prev</button>'
            var ws = Math.max(1, manageCurrentPage - Math.floor(manageVisibleCount / 2))
            var we = Math.min(ws + manageVisibleCount - 1, totalPages)
            if (we - ws + 1 < manageVisibleCount) ws = Math.max(1, we - manageVisibleCount + 1)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === manageCurrentPage ? btnActive : btnInactive) + '" data-manage-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-manage-page="next-window" title="Next set">&hellip;</button>' }
            html += '<button type="button" class="' + (manageCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-manage-page="next"' + (manageCurrentPage === totalPages ? ' disabled' : '') + '>Next &rsaquo;</button>'
            pag.innerHTML = html
            pag.querySelectorAll('button[data-manage-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-manage-page')
                    if (p === 'prev' && manageCurrentPage > 1) { manageCurrentPage-- }
                    else if (p === 'next' && manageCurrentPage < totalPages) { manageCurrentPage++ }
                    else if (p === 'next-window') { manageCurrentPage = Math.min(we + 1, totalPages) }
                    else if (p !== 'prev' && p !== 'next') { manageCurrentPage = parseInt(p, 10) }
                    else return
                    var fn = typeof loadManageAppointments === 'function' ? loadManageAppointments : null
                    if (fn) fn()
                })
            })
        }

        function renderManageCalendar() {
            if (!manageCalDateGrid || !manageCalMonthLabel) return

            var year = manageCalMonth.getFullYear()
            var month = manageCalMonth.getMonth()
            var first = new Date(year, month, 1)
            var firstDow = first.getDay()
            var daysIn = new Date(year, month + 1, 0).getDate()

            manageCalMonthLabel.textContent = first.toLocaleDateString(undefined, { month: 'long', year: 'numeric' })

            var today = new Date()
            today.setHours(0, 0, 0, 0)

            var selectedIso = ''
            var cells = []
            for (var i = 0; i < firstDow; i++) cells.push('')
            for (var day = 1; day <= daysIn; day++) {
                var d = new Date(year, month, day)
                var iso = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                var isPast = d.getTime() < today.getTime()
                var selected = selectedIso && selectedIso === iso
                var base = 'relative w-full rounded-lg text-[0.75rem] font-semibold border transition-colors flex items-center justify-center'
                var cls = base + ' ' + (isPast
                    ? 'bg-slate-100 text-slate-400 border-slate-200 hover:bg-slate-200'
                    : (selected ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'))
                var count = manageMonthAppointments[iso] || 0
                var showBadge = count > 0
                var badgeCls = isPast ? 'bg-red-300' : 'bg-red-500'
                var badge = showBadge ? '<span class="absolute -top-1 -right-1 min-w-[14px] h-[14px] ' + badgeCls + ' text-white text-[0.5rem] leading-[14px] font-bold rounded-full px-0.5 text-center">' + count + '</span>' : ''
                cells.push('<button type="button" class="' + cls + '" data-date="' + iso + '">' + day + badge + '</button>')
            }
            var total = Math.ceil(cells.length / 7) * 7
            while (cells.length < total) cells.push('')
            manageCalDateGrid.innerHTML = cells.map(function (html) {
                return html ? html : '<div></div>'
            }).join('')
        }

        function loadManageMonthAppointments() {
            if (typeof apiFetch !== 'function') return
            var y = manageCalMonth.getFullYear()
            var m = manageCalMonth.getMonth()
            var startDate = y + '-' + String(m + 1).padStart(2, '0') + '-01'
            var lastDay = new Date(y, m + 1, 0).getDate()
            var endDate = y + '-' + String(m + 1).padStart(2, '0') + '-' + String(lastDay).padStart(2, '0')
            apiFetch("{{ url('/api/appointments') }}?start_date=" + encodeURIComponent(startDate) + "&end_date=" + encodeURIComponent(endDate) + "&per_page=200&doctor_id=" + encodeURIComponent(doctorId), { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var map = {}
                    raw.forEach(function (a) {
                        if (!a || !a.appointment_datetime) return
                        if (String(a.status || '').toLowerCase() === 'cancelled') return
                        var d = new Date(a.appointment_datetime)
                        if (!isNaN(d.getTime())) {
                            var datePart = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                            map[datePart] = (map[datePart] || 0) + 1
                        }
                    })
                    manageMonthAppointments = map
                    renderManageCalendar()
                })
                .catch(function () {
                    manageMonthAppointments = {}
                    renderManageCalendar()
                })
        }

        function loadManageAppointments(page) {
            if (typeof apiFetch !== 'function') return
            page = page || manageCurrentPage
            showManageError('')
            showManageSuccess('')
            showManageResult(null)
            setManageSubmitting(true)
            manageTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading appointments&hellip;</td></tr>'

            var url = "{{ url('/api/appointments') }}" + '?per_page=10&page=' + page
            var order = manageSortSelect && manageSortSelect.value ? String(manageSortSelect.value) : 'latest'
            url += '&order=' + encodeURIComponent(order === 'oldest' ? 'oldest' : 'latest')

            var now = new Date()
            var startIso = ''
            var endIso = ''
            if (manageFilterDate) {
                startIso = manageFilterDate
                endIso = manageFilterDate
            } else if (manageShowTodayOnly) {
                var todayIso = formatLocalDateIso(now)
                startIso = todayIso
                endIso = todayIso
            } else {
                var start = new Date(now.getFullYear(), now.getMonth(), 1)
                var end = new Date(now.getFullYear(), now.getMonth() + 1, 0)
                startIso = formatLocalDateIso(start)
                endIso = formatLocalDateIso(end)
            }
            url += '&start_date=' + encodeURIComponent(startIso)
            url += '&end_date=' + encodeURIComponent(endIso)

            // Always filter by the current doctor
            url += '&doctor_id=' + encodeURIComponent(doctorId)

            var search = manageSearchInput ? normalizeText(manageSearchInput.value) : ''
            if (search) url += '&search=' + encodeURIComponent(search)

            var serviceId = manageServiceId && manageServiceId.value ? parseInt(manageServiceId.value, 10) : 0
            if (serviceId) url += '&service_id=' + encodeURIComponent(serviceId)

            var statusFilter = manageStatusSelect && manageStatusSelect.value ? String(manageStatusSelect.value) : ''
            if (statusFilter) url += '&status=' + encodeURIComponent(statusFilter)

            var typeFilter = manageTypeSelect && manageTypeSelect.value ? String(manageTypeSelect.value) : ''
            if (typeFilter) url += '&appointment_type=' + encodeURIComponent(typeFilter)

            apiFetch(url, { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) {
                        var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to load appointments.'
                        showManageError(msg)
                        renderManageAppointments([])
                        return
                    }
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var rows = Array.isArray(raw) ? raw.slice() : []

                    manageCurrentPage = result.data.current_page || page
                    manageLastPage = result.data.last_page || 1
                    manageTotal = result.data.total || rows.length

                    renderManageAppointments(rows)

                    if (manageMeta) {
                        if (manageShowTodayOnly) {
                            manageMeta.textContent = 'Showing page ' + manageCurrentPage + ' of ' + manageLastPage + ' (' + manageTotal + ' appointments for ' + startIso + ').'
                        } else {
                            var monthLabel = startIso.slice(0, 7)
                            manageMeta.textContent = 'Showing page ' + manageCurrentPage + ' of ' + manageLastPage + ' (' + manageTotal + ' appointments for ' + monthLabel + ').'
                        }
                    }
                })
                .catch(function () {
                    showManageError('Network error while loading appointments.')
                    renderManageAppointments([])
                })
                .finally(function () {
                    setManageSubmitting(false)
                })
        }

        function loadManageServices() {
            if (manageServicesLoaded || manageServicesLoading || typeof apiFetch !== 'function') return
            manageServicesLoading = true
            apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) return
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    manageServices = raw || []
                    manageServicesLoaded = true
                })
                .catch(function () {})
                .finally(function () {
                    manageServicesLoading = false
                })
        }

        function renderManageServiceResults() {
            if (!manageServiceResults || !manageServiceSearch) return
            var q = String(manageServiceSearch.value || '').trim()
            var list = Array.isArray(manageServices) ? manageServices : []
            var filtered = list.filter(function (s) {
                var name = s && s.service_name ? String(s.service_name) : ''
                return wordPrefixMatch(name, q)
            })
            filtered = filtered.slice(0, 25)
            if (!filtered.length) {
                manageServiceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No services found.</div>'
            } else {
                manageServiceResults.innerHTML = filtered.map(function (s) {
                    var id = s.service_id != null ? s.service_id : ''
                    var name = s.service_name != null ? s.service_name : ('Service #' + id)
                    return '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 text-[0.78rem] text-slate-700" data-service-id="' + escapeHtml(id) + '">' + escapeHtml(name) + '</button>'
                }).join('')
            }
            manageServiceResults.classList.remove('hidden')
        }

        function setManageServiceSelection(service) {
            if (manageServiceId) manageServiceId.value = service && service.service_id != null ? String(service.service_id) : ''
            if (manageServiceSearch) {
                manageServiceSearch.value = service && service.service_name ? String(service.service_name) : ''
                if (!service) manageServiceSearch.placeholder = 'All services'
            }
            if (manageServiceResults) manageServiceResults.classList.add('hidden')
        }

        function setManageSubmitting(isSubmitting) {
            var disabled = !!isSubmitting
            if (manageSortSelect) manageSortSelect.disabled = disabled
            if (manageStatusSelect) manageStatusSelect.disabled = disabled
            if (manageTypeSelect) manageTypeSelect.disabled = disabled
            if (manageRefreshBtn) manageRefreshBtn.disabled = disabled
            if (manageTodayOnlyBtn) manageTodayOnlyBtn.disabled = disabled
        }

        function showManageError(message) {
            if (message && typeof showToast === 'function') {
                showToast(message, 'error')
            }
        }

        function showManageSuccess(message) {
            if (message && typeof showToast === 'function') {
                showToast(message, 'success')
            }
        }

        function showManageResult(data) {
            if (!manageResult) return
            if (!data) {
                manageResult.classList.add('hidden')
                manageResult.textContent = ''
                return
            }
            try {
                manageResult.textContent = JSON.stringify(data, null, 2)
            } catch (e) {
                manageResult.textContent = String(data)
            }
            manageResult.classList.remove('hidden')
        }

        function updateManageTodayButton() {
            if (!manageTodayOnlyBtn) return
            if (manageShowTodayOnly) {
                manageTodayOnlyBtn.textContent = 'Showing today only'
                manageTodayOnlyBtn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                manageTodayOnlyBtn.classList.add('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-green-700', 'hover:border-green-700')
            } else {
                manageTodayOnlyBtn.textContent = 'Show today only'
                manageTodayOnlyBtn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                manageTodayOnlyBtn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-green-700', 'hover:border-green-700')
            }
        }

        function wordPrefixMatch(value, query) {
            var v = normalizeText(value || '')
            var q = normalizeText(query || '')
            if (!q) return true
            if (!v) return false
            if (v.indexOf(q) === 0) return true
            return v.split(/\s+/).some(function (part) { return part.indexOf(q) === 0 })
        }

        function openManageHistoryModal(patientId, patientName) {
            manageHistoryPatientId = patientId
            if (manageHistSubtitle) manageHistSubtitle.textContent = patientName || 'Loading&hellip;'
            if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history&hellip;</div>'
            if (manageHistDetailBody) manageHistDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>'
            if (manageHistDate) manageHistDate.value = ''
            if (manageHistStatus) manageHistStatus.value = ''
            if (manageHistType) manageHistType.value = 'scheduled'
            if (manageHistOverlay) {
                manageHistOverlay.classList.remove('hidden')
                manageHistOverlay.classList.add('flex')
            }
            loadManagePatientHistory(patientId)
        }

        function closeManageHistoryModal() {
            if (manageHistOverlay) {
                manageHistOverlay.classList.add('hidden')
                manageHistOverlay.classList.remove('flex')
            }
            manageHistoryPatientId = ''
            manageHistoryAppointments = []
        }

        function loadManagePatientHistory(patientId) {
            if (!patientId || typeof apiFetch !== 'function') return
            if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history&hellip;</div>'
            apiFetch("{{ url('/api/appointments') }}?per_page=15&patient_id=" + encodeURIComponent(patientId), { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result || !result.ok || !result.data) {
                        if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load history.</div>'
                        return
                    }
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    manageHistoryAppointments = Array.isArray(raw) ? raw.slice() : []
                    if (manageHistSubtitle) {
                        manageHistSubtitle.textContent = (manageHistSubtitle.textContent || '') + ' (' + String(manageHistoryAppointments.length) + ' records)'
                    }
                    renderManagePatientHistory()
                })
                .catch(function () {
                    if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Network error.</div>'
                })
        }

        function renderManagePatientHistory() {
            if (!manageHistBody) return
            var list = Array.isArray(manageHistoryAppointments) ? manageHistoryAppointments.slice() : []

            var filterDate = manageHistDate ? String(manageHistDate.value).trim() : ''
            var filterStatus = manageHistStatus ? manageHistStatus.value : ''
            var filterType = manageHistType ? manageHistType.value : ''

            if (filterDate) {
                list = list.filter(function (a) {
                    var d = a && a.appointment_datetime ? String(a.appointment_datetime).slice(0, 10) : ''
                    return d === filterDate
                })
            }
            if (filterStatus) {
                list = list.filter(function (a) {
                    return String(a && a.status ? a.status : '').toLowerCase() === filterStatus
                })
            }
            if (filterType) {
                list = list.filter(function (a) {
                    return String(a && a.appointment_type ? a.appointment_type : '').toLowerCase() === filterType
                })
            }

            list.sort(function (a, b) {
                var da = a && a.appointment_datetime ? String(a.appointment_datetime) : ''
                var db = b && b.appointment_datetime ? String(b.appointment_datetime) : ''
                if (da < db) return 1; if (da > db) return -1; return 0
            })

            if (list.length === 0) {
                manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No records found.</div>'
                return
            }

            manageHistBody.innerHTML = list.map(function (a) {
                var aid = a.id || a.appointment_id || ''
                var w = safeIsoParts(a && a.appointment_datetime ? String(a.appointment_datetime) : '')
                var d = w ? (w.date || '') : ''
                var t = w ? (w.time ? formatTime12h(w.time) : '') : ''
                var doctor = a.doctor ? personName(a.doctor, '-') : '-'
                var stLabel = manageStatusLabel(a)
                var stKey = String(a && a.status ? a.status : '').toLowerCase()
                var isCI = stKey === 'confirmed' && (a && a.check_in_time)
                var sc = ''
                if (stKey === 'completed') sc = 'border-green-200 bg-green-50 text-green-700'
                else if (isCI || stKey === 'confirmed') sc = 'border-orange-200 bg-orange-50 text-orange-700'
                else if (stKey === 'consulted') sc = 'border-purple-200 bg-purple-50 text-purple-700'
                else if (stKey === 'cancelled') sc = 'border-rose-200 bg-rose-50 text-rose-700'
                else if (stKey === 'no_show') sc = 'border-slate-200 bg-slate-100 text-slate-600'
                else if (stKey === 'pending') sc = 'border-amber-200 bg-amber-50 text-amber-700'
                else sc = 'border-slate-200 bg-slate-100 text-slate-600'
                var typeRaw = String(a && a.appointment_type ? a.appointment_type : '').toLowerCase()
                var typeLabel = (typeRaw === 'walk_in' || typeRaw === 'walk-in' || typeRaw === 'walk in') ? 'Walk In' : (typeRaw === 'scheduled' ? 'Scheduled' : typeRaw)

                var services = Array.isArray(a.services) ? a.services : []
                var serviceParts = services.map(function (s) {
                    var name = String(s && s.service_name ? s.service_name : '').trim()
                    var desc = String(s && s.description ? s.description : '').trim()
                    var fee = s && s.price != null ? '\u20B1' + String(s.price) : ''
                    var p = [name]
                    if (desc) p.push(desc)
                    if (fee) p.push(fee)
                    return p.join(' - ')
                }).filter(Boolean)
                var serviceInfo = ''
                if (serviceParts.length === 1) {
                    serviceInfo = serviceParts[0]
                } else if (serviceParts.length > 1) {
                    serviceInfo = serviceParts[0] + ' <span class="text-green-600 font-medium">...' + (serviceParts.length - 1) + ' more &rsaquo;</span>'
                } else {
                    serviceInfo = 'No services'
                }

                return (
                    '<button type="button" class="manage-hist-item-btn w-full text-left rounded-xl border border-slate-200 bg-white p-3 hover:border-green-300 hover:shadow-sm transition-all cursor-pointer active-appt-item" data-appointment-id="' + escapeHtml(aid) + '">' +
                        '<div class="flex items-center justify-between mb-1">' +
                            '<span class="text-[0.78rem] font-semibold text-slate-800 truncate">' + escapeHtml(d) + ' ' + escapeHtml(t) + '</span>' +
                            '<span class="inline-flex items-center gap-1.5 shrink-0">' +
                                '<span class="inline-flex items-center px-2 py-0.5 rounded text-[0.6rem] font-medium border ' + (String(typeLabel).toLowerCase().trim() === 'walk in' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200') + '">' + escapeHtml(typeLabel) + '</span>' +
                                '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.62rem] border ' + sc + '">' + escapeHtml(stLabel || '-') + '</span>' +
                            '</span>' +
                        '</div>' +
                        '<div class="text-[0.72rem] text-slate-500 mb-1">' + escapeHtml(doctor) + '</div>' +
                        '<div class="text-[0.68rem] text-slate-500 mb-1 truncate">' + serviceInfo + '</div>' +
                        '<span class="text-[0.7rem] font-semibold text-green-700">View Details &rarr;</span>' +
                    '</button>'
                )
            }).join('')
        }

        function renderManageApptDetail(appt) {
            if (!appt || !manageHistDetailBody) {
                if (manageHistDetailBody) manageHistDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No appointment selected.</div>'
                return
            }

            var patient = appt.patient || {}
            var doctor = appt.doctor || {}
            var patientName = personName(patient, 'N/A')
            var doctorName = personName(doctor, 'N/A')
            var stLabel = manageStatusLabel(appt)
            var apptId = appt.id || appt.appointment_id || ''

            var apptTypeRaw = String(appt && appt.appointment_type ? appt.appointment_type : '').toLowerCase()
            var apptTypeLabel = (apptTypeRaw === 'walk_in' || apptTypeRaw === 'walk-in' || apptTypeRaw === 'walk in') ? 'Walk In' : (apptTypeRaw === 'scheduled' ? 'Scheduled' : apptTypeRaw)
            var currentStatus = String(appt && appt.status ? appt.status : '').toLowerCase()

            var stKey = currentStatus
            var isCI = stKey === 'confirmed' && (appt && appt.check_in_time)
            var sc = ''
            if (stKey === 'completed') sc = 'border-green-200 bg-green-50 text-green-700'
            else if (isCI || stKey === 'confirmed') sc = 'border-orange-200 bg-orange-50 text-orange-700'
            else if (stKey === 'consulted') sc = 'border-purple-200 bg-purple-50 text-purple-700'
            else if (stKey === 'cancelled') sc = 'border-rose-200 bg-rose-50 text-rose-700'
            else if (stKey === 'no_show') sc = 'border-slate-200 bg-slate-100 text-slate-600'
            else if (stKey === 'pending') sc = 'border-amber-200 bg-amber-50 text-amber-700'
            else sc = 'border-slate-200 bg-slate-100 text-slate-600'

            var dt = appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
            var tx = appt.transaction || null
            var services = Array.isArray(appt.services) ? appt.services : []
            var serviceNames = services.length ? services.map(function (s) { return s.service_name || s.name || '' }).filter(Boolean).join(', ') : '-'
            var amount = tx ? (tx.amount || 0) : 0
            var discountAmount = tx ? (tx.discount_amount || 0) : 0
            var discountType = tx ? (tx.discount_type || 'none') : 'none'
            var net = parseFloat(amount) - parseFloat(discountAmount)
            var reason = appt.reason_for_visit ? escapeHtml(appt.reason_for_visit) : '<span class="text-slate-400">-</span>'
            var diagnosis = tx ? (tx.diagnosis || '-') : '-'
            var treatment = tx ? (tx.treatment_notes || '-') : '-'
            var txId = tx ? (tx.transaction_id || tx.id || '') : ''

            manageHistDetailBody.innerHTML =
                '<div class="space-y-3">' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Appointment</div>' +
                        '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                            '<div class="text-slate-500">Patient</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(patientName) + '</div>' +
                            '<div class="text-slate-500">Date & Time</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(dt) + '</div>' +
                            '<div class="text-slate-500">Doctor</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(doctorName) + '</div>' +
                            '<div class="text-slate-500">Type</div>' +
                            '<div><span class="inline-flex items-center px-2 py-0.5 rounded text-[0.6rem] font-medium border ' + (String(apptTypeLabel).toLowerCase().trim() === 'walk in' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200') + '">' + escapeHtml(apptTypeLabel) + '</span></div>' +
                            '<div class="text-slate-500">Status</div>' +
                            '<div><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + sc + '">' + escapeHtml(stLabel || '-') + '</span></div>' +
                            '<div class="text-slate-500">Reason</div>' +
                            '<div class="text-slate-800">' + reason + '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Services & Payment</div>' +
                        '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                            '<div class="text-slate-500">Services</div>' +
                            '<div class="text-slate-800">' + escapeHtml(serviceNames) + '</div>' +
                            '<div class="text-slate-500">Gross Amount</div>' +
                            '<div class="text-slate-800 font-medium">\u20B1' + escapeHtml(Number(amount).toFixed(2)) + '</div>' +
                            '<div class="text-slate-500">Discount (' + escapeHtml(discountType !== 'none' ? discountType.toUpperCase() : 'None') + ')</div>' +
                            '<div class="text-slate-800">-\u20B1' + escapeHtml(Number(discountAmount).toFixed(2)) + '</div>' +
                            '<div class="text-slate-500 font-semibold">Net</div>' +
                            '<div class="text-slate-800 font-bold text-green-700">\u20B1' + escapeHtml(net.toFixed(2)) + '</div>' +
                            '<div class="text-slate-500">Payment Mode</div>' +
                            '<div class="text-slate-800">' + (tx ? escapeHtml(tx.payment_mode || '-') : '-') + '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Diagnosis & Treatment</div>' +
                        '<div class="text-[0.78rem] space-y-2">' +
                            '<div><span class="text-slate-500">Diagnosis:</span><br><span class="text-slate-800">' + escapeHtml(diagnosis) + '</span></div>' +
                            '<div><span class="text-slate-500">Treatment Notes:</span><br><span class="text-slate-800">' + escapeHtml(treatment) + '</span></div>' +
                        '</div>' +
                    '</div>' +
                    '<div id="doctorManageHistPrescriptionsWrap" class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                        '<div class="text-[0.78rem] text-slate-400">Loading prescriptions&hellip;</div>' +
                    '</div>' +
                '</div>'

            if (txId && typeof apiFetch === 'function') {
                apiFetch("{{ url('/api/prescriptions') }}?per_page=20&transaction_id=" + encodeURIComponent(txId), { method: 'GET' })
                    .then(function (response) { return readResponse(response) })
                    .then(function (result) {
                        var wrap = document.getElementById('doctorManageHistPrescriptionsWrap')
                        if (!wrap) return
                        var items = (result && result.ok && result.data) ? (Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])) : []
                        if (!items.length) {
                            wrap.innerHTML =
                                '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                                '<div class="text-[0.78rem] text-slate-500">No prescriptions found.</div>'
                            return
                        }
                        var rxHtml = ''
                        items.forEach(function (rx) {
                            var rxDr = rx.doctor ? personName(rx.doctor, '-') : '-'
                            var rxDate = rx.prescribed_datetime ? String(rx.prescribed_datetime).replace('T', ' ').slice(0, 10) : '-'
                            var rxNotes = rx.notes ? escapeHtml(rx.notes) : ''
                            rxHtml += '<div class="border-b border-slate-100 pb-2 mb-2 last:border-0 last:pb-0 last:mb-0">' +
                                '<div class="flex items-center justify-between text-[0.72rem] text-slate-500 mb-1">' +
                                    '<span class="font-medium text-slate-700">' + escapeHtml(rxDate) + '</span>' +
                                    '<span>' + escapeHtml(rxDr) + '</span>' +
                                '</div>' +
                                (rxNotes ? '<div class="text-[0.72rem] text-slate-600 mb-1">' + rxNotes + '</div>' : '') +
                                '<div class="overflow-x-auto">' +
                                    '<table class="w-full text-[0.72rem] text-slate-700">' +
                                        '<thead>' +
                                            '<tr class="text-[0.6rem] uppercase tracking-wider text-slate-400 border-b border-slate-100">' +
                                                '<th class="py-1 pr-2 text-left">Medicine</th>' +
                                                '<th class="py-1 pr-2 text-left">Dosage</th>' +
                                                '<th class="py-1 pr-2 text-left">Frequency</th>' +
                                                '<th class="py-1 pr-0 text-left">Duration</th>' +
                                            '</tr>' +
                                        '</thead>' +
                                        '<tbody>'
                            var rxItems = Array.isArray(rx.items) ? rx.items : []
                            if (rxItems.length) {
                                rxItems.forEach(function (it) {
                                    rxHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                                        '<td class="py-1 pr-2 font-medium">' + escapeHtml(it.medicine_name || '-') + '</td>' +
                                        '<td class="py-1 pr-2">' + escapeHtml(it.dosage || '-') + '</td>' +
                                        '<td class="py-1 pr-2">' + escapeHtml(it.frequency || '-') + '</td>' +
                                        '<td class="py-1 pr-0">' + escapeHtml(it.duration || '-') + '</td>' +
                                    '</tr>'
                                })
                            } else {
                                rxHtml += '<tr><td colspan="4" class="py-1 text-slate-400">No items</td></tr>'
                            }
                            rxHtml += '</tbody></table></div></div>'
                        })
                        wrap.innerHTML =
                            '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                            rxHtml
                    })
                    .catch(function () {
                        var wrap = document.getElementById('doctorManageHistPrescriptionsWrap')
                        if (wrap) {
                            wrap.innerHTML =
                                '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                                '<div class="text-[0.78rem] text-slate-500">Failed to load prescriptions.</div>'
                        }
                    })
            } else {
                var wrap = document.getElementById('doctorManageHistPrescriptionsWrap')
                if (wrap) {
                    wrap.innerHTML =
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                        '<div class="text-[0.78rem] text-slate-500">No info.</div>'
                }
            }
        }

        // ── Variable declarations ──
        var doctorId = {{ $currentUser?->user_id ?? 'null' }};

        var manageResult = document.getElementById('doctorManageAppointmentResult')
        var manageSearchInput = document.getElementById('doctorManageApptSearch')
        var manageServiceSearch = document.getElementById('doctorManageServiceSearch')
        var manageServiceId = document.getElementById('doctorManageServiceId')
        var manageServiceResults = document.getElementById('doctorManageServiceResults')
        var manageSortSelect = document.getElementById('doctorManageSort')
        var manageStatusSelect = document.getElementById('doctorManageStatus')
        var manageTypeSelect = document.getElementById('doctorManageType')
        var manageTableBody = document.getElementById('doctorManageAppointmentTableBody')
        var manageMeta = document.getElementById('doctorManageAppointmentMeta')
        var manageRefreshBtn = document.getElementById('doctorManageRefreshBtn')
        var manageTodayOnlyBtn = document.getElementById('doctorManageTodayOnlyBtn')
        var manageShowTodayOnly = false
        var manageSearchTimer = null
        var manageServices = []
        var manageServicesLoaded = false
        var manageServicesLoading = false
        var manageCurrentPage = 1
        var managePerPage = 10
        var manageVisibleCount = 5
        var manageLastPage = 1
        var manageTotal = 0
        var manageFilterDate = ''
        var manageDateHeader = document.getElementById('doctorManageDateHeader')
        var manageClearFilterBtn = document.getElementById('doctorManageClearFilterBtn')

        var manageHistOverlay = document.getElementById('doctorManageHistoryOverlay')
        var manageHistClose = document.getElementById('doctorManageHistClose')
        var manageHistSubtitle = document.getElementById('doctorManageHistSubtitle')
        var manageHistBody = document.getElementById('doctorManageHistBody')
        var manageHistDetailBody = document.getElementById('doctorManageHistDetailBody')
        var manageHistDate = document.getElementById('doctorManageHistDate')
        var manageHistStatus = document.getElementById('doctorManageHistStatus')
        var manageHistType = document.getElementById('doctorManageHistType')
        var manageHistoryPatientId = ''
        var manageHistoryAppointments = []

        var manageCalMonth = new Date()
        manageCalMonth.setDate(1)
        var manageMonthAppointments = {}
        var manageCalMonthLabel = document.getElementById('doctorManageCalMonthLabel')
        var manageCalDateGrid = document.getElementById('doctorManageCalDateGrid')
        var manageCalDatePrev = document.getElementById('doctorManageCalDatePrev')
        var manageCalDateNext = document.getElementById('doctorManageCalDateNext')
        var manageTableArea = document.getElementById('doctorManageTableArea')
        var manageCalendarArea = document.getElementById('doctorManageCalendarArea')
        var manageCalendarToggle = document.getElementById('doctorManageCalendarToggle')
        var manageCalendarToggleText = document.getElementById('doctorManageCalendarToggleText')
        var manageShowCalendar = true

        // ── Event listeners ──

        // Search input (debounced 250ms)
        if (manageSearchInput) {
            manageSearchInput.addEventListener('input', function () {
                if (manageSearchTimer) clearTimeout(manageSearchTimer)
                manageSearchTimer = setTimeout(function () {
                    manageCurrentPage = 1
                    loadManageAppointments()
                }, 250)
            })
        }

        // Service search focus/input
        if (manageServiceSearch) {
            manageServiceSearch.addEventListener('focus', function () {
                loadManageServices()
                renderManageServiceResults()
            })
            manageServiceSearch.addEventListener('input', function () {
                if (manageServiceId && manageServiceId.value) {
                    var picked = manageServices.find(function (s) { return String(s.service_id) === String(manageServiceId.value) }) || null
                    var pickedName = picked && picked.service_name ? String(picked.service_name) : ''
                    if (normalizeText(manageServiceSearch.value) !== normalizeText(pickedName)) {
                        setManageServiceSelection(null)
                        manageCurrentPage = 1
                        loadManageAppointments()
                    }
                }
                loadManageServices()
                renderManageServiceResults()
            })
        }

        // Service results click (select service)
        if (manageServiceResults) {
            manageServiceResults.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-service-id]') : null
                if (!btn) return
                var id = btn.getAttribute('data-service-id')
                var picked = manageServices.find(function (s) { return String(s.service_id) === String(id) }) || null
                setManageServiceSelection(picked)
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        // Sort select change
        if (manageSortSelect) {
            manageSortSelect.addEventListener('change', function () {
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        // Status select change
        if (manageStatusSelect) {
            manageStatusSelect.addEventListener('change', function () {
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        // Type select change
        if (manageTypeSelect) {
            manageTypeSelect.addEventListener('change', function () {
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        // Refresh button click
        if (manageRefreshBtn) {
            manageRefreshBtn.addEventListener('click', function () {
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        // Today only button click
        if (manageTodayOnlyBtn) {
            manageTodayOnlyBtn.addEventListener('click', function () {
                manageShowTodayOnly = !manageShowTodayOnly
                updateManageTodayButton()
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        // Calendar toggle click
        if (manageCalendarToggle && manageTableArea && manageCalendarArea) {
            manageCalendarToggle.addEventListener('click', function () {
                manageShowCalendar = !manageShowCalendar
                if (manageShowCalendar) {
                    manageTableArea.classList.add('hidden')
                    manageCalendarArea.classList.remove('hidden')
                    manageCalendarToggle.classList.add('bg-green-600', 'text-white', 'border-green-600')
                    manageCalendarToggle.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300')
                    if (manageCalendarToggleText) manageCalendarToggleText.textContent = 'Table view'
                    if (manageDateHeader) manageDateHeader.style.display = 'none'
                    if (manageClearFilterBtn) manageClearFilterBtn.style.display = 'none'
                    manageCalMonth = new Date()
                    manageCalMonth.setDate(1)
                    loadManageMonthAppointments()
                } else {
                    manageCalendarArea.classList.add('hidden')
                    manageTableArea.classList.remove('hidden')
                    manageCalendarToggle.classList.remove('bg-green-600', 'text-white', 'border-green-600')
                    manageCalendarToggle.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300')
                    if (manageCalendarToggleText) manageCalendarToggleText.textContent = 'Calendar view'
                    if (manageFilterDate && manageDateHeader && manageClearFilterBtn) {
                        manageDateHeader.style.display = ''
                        manageClearFilterBtn.style.display = ''
                    }
                }
            })
        }

        // Calendar prev/next month
        if (manageCalDatePrev) {
            manageCalDatePrev.addEventListener('click', function () {
                manageCalMonth.setMonth(manageCalMonth.getMonth() - 1)
                loadManageMonthAppointments()
            })
        }
        if (manageCalDateNext) {
            manageCalDateNext.addEventListener('click', function () {
                manageCalMonth.setMonth(manageCalMonth.getMonth() + 1)
                loadManageMonthAppointments()
            })
        }

        // Calendar date grid click (select date to filter)
        if (manageCalDateGrid) {
            manageCalDateGrid.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-date]') : null
                if (!btn || btn.disabled) return
                var dateIso = btn.getAttribute('data-date') || ''
                if (!dateIso) return
                manageFilterDate = dateIso
                manageCurrentPage = 1
                if (manageDateHeader) {
                    var d = new Date(dateIso + 'T00:00:00')
                    var formatted = d.toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' })
                    manageDateHeader.textContent = 'Showing ' + formatted + ' Appointments'
                    manageDateHeader.style.display = ''
                }
                if (manageClearFilterBtn) manageClearFilterBtn.style.display = ''
                if (manageCalendarToggle && manageTableArea && manageCalendarArea) {
                    manageShowCalendar = false
                    manageCalendarArea.classList.add('hidden')
                    manageTableArea.classList.remove('hidden')
                    manageCalendarToggle.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-slate-50', 'hover:border-slate-300')
                    manageCalendarToggle.classList.add('bg-white', 'text-slate-700', 'border-slate-200')
                    if (manageCalendarToggleText) manageCalendarToggleText.textContent = 'Calendar view'
                }
                loadManageAppointments()
            })
        }

        // Clear filter button click
        if (manageClearFilterBtn) {
            manageClearFilterBtn.addEventListener('click', function () {
                manageFilterDate = ''
                if (manageDateHeader) {
                    manageDateHeader.textContent = ''
                    manageDateHeader.style.display = 'none'
                }
                manageClearFilterBtn.style.display = 'none'
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        // See Details button click (delegated on table body)
        if (manageTableBody) {
            manageTableBody.addEventListener('click', function (e) {
                var btn = e.target.closest('.manage-see-history-btn')
                if (btn) {
                    var pid = btn.getAttribute('data-patient-id')
                    var pname = btn.getAttribute('data-patient-name')
                    if (pid) openManageHistoryModal(pid, pname)
                }
            })
        }

        // History item click (delegated on history body)
        if (manageHistOverlay) {
            manageHistOverlay.addEventListener('click', function (e) {
                if (e.target === manageHistOverlay) closeManageHistoryModal()
            })
        }
        if (manageHistClose) {
            manageHistClose.addEventListener('click', closeManageHistoryModal)
        }

        if (manageHistDate) manageHistDate.addEventListener('change', renderManagePatientHistory)
        if (manageHistStatus) manageHistStatus.addEventListener('change', renderManagePatientHistory)
        if (manageHistType) manageHistType.addEventListener('change', renderManagePatientHistory)

        if (manageHistBody) {
            manageHistBody.addEventListener('click', function (e) {
                var item = e.target.closest('.manage-hist-item-btn')
                if (item) {
                    var aid = item.getAttribute('data-appointment-id')
                    var appt = manageHistoryAppointments.find(function (a) {
                        return String(a.id || a.appointment_id || '') === aid
                    })
                    if (appt) {
                        var allItems = manageHistBody.querySelectorAll('.manage-hist-item-btn')
                        allItems.forEach(function (el) { el.classList.remove('border-green-400', 'bg-green-50') })
                        item.classList.add('border-green-400', 'bg-green-50')
                        renderManageApptDetail(appt)
                    }
                }
            })
        }

        // Click outside service results to close
        document.addEventListener('click', function (e) {
            if (!manageServiceResults || !manageServiceSearch) return
            if (manageServiceSearch.contains(e.target) || manageServiceResults.contains(e.target)) return
            manageServiceResults.classList.add('hidden')
        })

        // ── Initial Load ──
        updateManageTodayButton()
        loadManageAppointments()
        loadManageMonthAppointments()

        // Calendar starts ON by default
        manageShowCalendar = true
        manageTableArea.classList.add('hidden')
        manageCalendarArea.classList.remove('hidden')
        if (manageCalendarToggle) {
            manageCalendarToggle.classList.add('bg-green-600', 'text-white', 'border-green-600')
            manageCalendarToggle.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300')
        }
        if (manageCalendarToggleText) manageCalendarToggleText.textContent = 'Table view'

        // ── Echo Listener ──
        if (typeof window.Echo !== 'undefined' && window.Echo && doctorId) {
            window.Echo.private('appointments.' + doctorId)
                .listen('.appointment.updated', function (e) {
                    loadManageAppointments()
                    loadManageMonthAppointments()
                });
        }
    })
</script>