<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900"></h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Monitoring</span>
    </div>
    <p class="text-xs text-slate-500 mb-4">
        
    </p>

    <div id="adminAppointmentsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

    <div class="flex justify-end mb-2">
        <button type="button" id="adminApptRefreshBtn" class="inline-flex items-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
            <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
            Refresh
        </button>
    </div>

    <div class="mb-3 grid grid-cols-1 md:grid-cols-6 gap-2 md:items-end">
        <div>
            <label for="admin_appt_search" class="block text-[0.7rem] text-slate-600 mb-1">Search</label>
            <input id="admin_appt_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Patient/doctor name">
        </div>
        <div>
            <label for="admin_appt_date" class="block text-[0.7rem] text-slate-600 mb-1">Date</label>
            <input id="admin_appt_date" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
        </div>
        <div>
            <label for="admin_appt_doctor" class="block text-[0.7rem] text-slate-600 mb-1">Doctor</label>
            <select id="admin_appt_doctor" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All doctors</option>
            </select>
        </div>
        <div>
            <label for="admin_appt_type" class="block text-[0.7rem] text-slate-600 mb-1">Type</label>
            <select id="admin_appt_type" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All types</option>
                <option value="walk_in">Walk In</option>
                <option value="scheduled">Scheduled</option>
            </select>
        </div>
        <div>
            <label for="admin_appt_status" class="block text-[0.7rem] text-slate-600 mb-1">Status</label>
            <select id="admin_appt_status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="no_show">No-show</option>
            </select>
        </div>
        <div>
            <label for="admin_appt_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
            <select id="admin_appt_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="newest" selected>Newest first</option>
                <option value="oldest">Oldest first</option>
            </select>
        </div>
    </div>

   <div class="overflow-auto scrollbar-hidden h-[575px]">
        <table class="min-w-full text-left text-xs text-slate-600">
            <thead>
                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                    <th class="py-2 pr-4 font-semibold">Datetime</th>
                    <th class="py-2 pr-4 font-semibold">Patient</th>
                    <th class="py-2 pr-4 font-semibold">Doctor</th>
                    <th class="py-2 pr-4 font-semibold">Type</th>
                    <th class="py-2 pr-4 font-semibold">Status</th>
                    <th class="py-2 pr-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody id="admin_appt_table_body">
                <tr>
                    <td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">
                        Loading appointments…
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="adminApptPagination" class="flex items-center justify-center gap-1 mt-3 flex-wrap"></div>
</div>

<!-- Appointment History Modal -->
<div id="adminApptHistoryOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl h-[90vh] max-h-none rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex overflow-hidden">
        <!-- History list (left) -->
        <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Appointment History</div>
                    <div id="adminApptHistorySubtitle" class="text-[0.72rem] text-slate-500">Loading…</div>
                </div>
                <button type="button" id="adminApptHistoryClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div class="px-4 py-2 border-b border-slate-100 shrink-0 grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-[0.6rem] text-slate-500 mb-0.5">Date</label>
                    <input id="adminApptHistoryDate" type="date" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>
                <div>
                    <label class="block text-[0.6rem] text-slate-500 mb-0.5">Status</label>
                    <select id="adminApptHistoryStatus" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
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
                    <select id="adminApptHistoryType" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="">All</option>
                        <option value="walk_in">Walk In</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>
            </div>
            <div id="adminApptHistoryBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>
            </div>
        </div>
        <!-- Detail panel (right) -->
        <div id="adminApptDetailPanel" class="w-1/2 flex flex-col min-h-0 bg-slate-50/50">
            <div class="px-4 py-3 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div class="text-sm font-semibold text-slate-900">Appointment Details</div>
            </div>
            <div id="adminApptDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>
            </div>
            <div class="px-4 py-3 border-t border-slate-200 shrink-0 bg-white flex items-center gap-3">
                <div class="flex-1">
                    <label class="block text-[0.6rem] text-slate-500 mb-0.5">Change Status</label>
                    <select id="adminApptDetailStatusSelect" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" disabled>
                        <option value="">Select status</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="no_show">No-show</option>
                    </select>
                </div>
                <button id="adminApptDetailUpdateBtn" type="button" class="shrink-0 self-end px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed" disabled>Update Status</button>
            </div>
        </div>
    </div>
</div>

{{-- Consultation Receipt Modal --}}
<div id="adminConsultReceiptModal" class="hidden fixed inset-0 z-[70] bg-slate-900/60 flex items-center justify-center p-4">
    <div class="w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(15,23,42,0.35)] flex flex-col overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div>
                <div class="text-sm font-semibold text-slate-900">Consultation Summary</div>
                <div id="adminConsultReceiptSubtitle" class="text-[0.72rem] text-slate-500">Printable consultation summary.</div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="adminConsultReceiptPrintBtn" class="inline-flex items-center gap-1.5 rounded-xl bg-green-700 px-3 py-2 text-[0.78rem] font-semibold text-white hover:bg-green-800">
                    <x-lucide-printer class="w-4 h-4" />
                    Print / PDF
                </button>
                <button type="button" id="adminConsultReceiptCloseBtn" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Close
                </button>
            </div>
        </div>
        <div class="flex-1 min-h-0 bg-slate-50">
            <iframe id="adminConsultReceiptIframe" src="" class="w-full h-full border-0" style="min-height: 70vh;"></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var errorBox = document.getElementById('adminAppointmentsError')
        var dateInput = document.getElementById('admin_appt_date')
        var doctorSelect = document.getElementById('admin_appt_doctor')
        var typeSelect = document.getElementById('admin_appt_type')
        var statusSelect = document.getElementById('admin_appt_status')
        var sortSelect = document.getElementById('admin_appt_sort')
        var searchInput = document.getElementById('admin_appt_search')
        var tableBody = document.getElementById('admin_appt_table_body')

        var appointments = []
        var apptCurrentPage = 1
        var apptMeta = { current_page: 1, last_page: 1, total: 0 }

        var apptVisibleCount = 6;
        function renderApptPagination() {
            var pagination = document.getElementById('adminApptPagination')
            if (!pagination) return
            var total = apptMeta.total
            var totalPages = apptMeta.last_page
            if (total === 0) {
                pagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                return
            }
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border ';
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer';
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default';
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600';
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
            html += '<button type="button" class="' + (apptCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (apptCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var windowStart = apptCurrentPage;
            var windowEnd = Math.min(windowStart + apptVisibleCount - 1, totalPages);
            for (var i = windowStart; i <= windowEnd; i++) {
                html += '<button type="button" class="' + (i === apptCurrentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
            }
            if (windowEnd < totalPages) {
                html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>'
            }
            html += '<button type="button" class="' + (apptCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (apptCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            pagination.innerHTML = html
            pagination.querySelectorAll('button[data-page]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var p = btn.getAttribute('data-page')
                    if (p === 'prev' && apptCurrentPage > 1) { loadAppointments(apptCurrentPage - 1) }
                    else if (p === 'next' && apptCurrentPage < totalPages) { loadAppointments(apptCurrentPage + 1) }
                    else if (p === 'next-window') {
                        var nextStart = Math.min(windowEnd + 1, totalPages);
                        loadAppointments(nextStart);
                    }
                    else if (p !== 'prev' && p !== 'next') { loadAppointments(parseInt(p, 10)) }
                })
            })
        }

        var doctors = []

        function showError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function escapeHtml(text) {
            return String(text || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function personLabel(u, fallback) {
            if (!u) return fallback || '-'
            var name = ((u.firstname || '') + ' ' + (u.lastname || '')).trim()
            if (name) return name
            if (u.email) return u.email
            return fallback || ('User #' + u.user_id)
        }

        function statusBadge(status) {
            var key = String(status || '').toLowerCase()
            var map = {
                pending: 'bg-amber-50 text-amber-700 border-amber-100',
                confirmed: 'bg-orange-50 text-orange-700 border-orange-100',
                consulted: 'bg-purple-50 text-purple-700 border-purple-100',
                completed: 'bg-green-50 text-green-700 border-green-100',
                cancelled: 'bg-slate-50 text-slate-600 border-slate-100',
                no_show: 'bg-rose-50 text-rose-700 border-rose-100'
            }
            var cls = map[key] || 'bg-slate-50 text-slate-600 border-slate-100'
            var label = key ? key.replace('_', ' ') : 'Unknown'
            label = label.charAt(0).toUpperCase() + label.slice(1)
            return '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.68rem] font-medium border ' + cls + '">' + escapeHtml(label) + '</span>'
        }

        function apptTypeLabel(type) {
            var map = { walk_in: 'Walk In', scheduled: 'Scheduled' }
            return map[String(type || '').toLowerCase()] || type || '-'
        }

        // ── History modal state ──
        var historyOverlay = document.getElementById('adminApptHistoryOverlay')
        var historyClose = document.getElementById('adminApptHistoryClose')
        var historySubtitle = document.getElementById('adminApptHistorySubtitle')
        var historyBody = document.getElementById('adminApptHistoryBody')
        var historyDate = document.getElementById('adminApptHistoryDate')
        var historyStatus = document.getElementById('adminApptHistoryStatus')
        var historyType = document.getElementById('adminApptHistoryType')
        var detailBody = document.getElementById('adminApptDetailBody')
        var detailStatusSelect = document.getElementById('adminApptDetailStatusSelect')
        var detailUpdateBtn = document.getElementById('adminApptDetailUpdateBtn')

        // Consultation Receipt Modal
        var adminConsultReceiptModal = document.getElementById('adminConsultReceiptModal')
        var adminConsultReceiptIframe = document.getElementById('adminConsultReceiptIframe')
        var adminConsultReceiptPrintBtn = document.getElementById('adminConsultReceiptPrintBtn')
        var adminConsultReceiptCloseBtn = document.getElementById('adminConsultReceiptCloseBtn')
        var historyPatientId = null
        var historyAppointments = []

        function openHistoryModal(patientId, patientName) {
            historyPatientId = patientId
            if (historySubtitle) historySubtitle.textContent = patientName || 'Patient #' + patientId
            if (historyBody) historyBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>'
            if (detailBody) detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>'
            if (historyDate) historyDate.value = ''
            if (historyStatus) historyStatus.value = ''
            if (historyType) historyType.value = ''
            if (detailStatusSelect) { detailStatusSelect.value = ''; detailStatusSelect.disabled = true }
            if (detailUpdateBtn) detailUpdateBtn.disabled = true
            window.__adminCurrentApptId = null
            if (historyOverlay) {
                historyOverlay.classList.remove('hidden')
                historyOverlay.classList.add('flex')
            }
            loadPatientHistory(patientId)
        }

        function closeHistoryModal() {
            if (historyOverlay) {
                historyOverlay.classList.add('hidden')
                historyOverlay.classList.remove('flex')
            }
            historyPatientId = null
            historyAppointments = []
            if (detailStatusSelect) { detailStatusSelect.value = ''; detailStatusSelect.disabled = true }
            if (detailUpdateBtn) detailUpdateBtn.disabled = true
            window.__adminCurrentApptId = null
        }

        function loadPatientHistory(patientId) {
            if (!patientId) return
            apiFetch("{{ url('/api/appointments') }}?per_page=15&patient_id=" + patientId, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () { return { ok: false, data: null } })
                })
                .then(function (result) {
                    if (!result.ok) {
                        if (historyBody) historyBody.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Failed to load history.</div>'
                        return
                    }
                    historyAppointments = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    if (historySubtitle) historySubtitle.textContent = (historyAppointments[0] ? personLabel(historyAppointments[0].patient) : 'Patient #' + patientId) + ' - ' + historyAppointments.length + ' appointment(s)'
                    renderHistory()
                })
                .catch(function () {
                    if (historyBody) historyBody.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Network error loading history.</div>'
                })
        }

        function renderHistory() {
            if (!historyBody) return
            var filtered = historyAppointments.slice()

            var selDate = historyDate ? historyDate.value : ''
            var selStatus = historyStatus ? historyStatus.value : ''
            var selType = historyType ? historyType.value : ''

            if (selDate) filtered = filtered.filter(function (a) { return (a.appointment_datetime || '').slice(0, 10) === selDate })
            if (selStatus) filtered = filtered.filter(function (a) { return String(a.status || '') === selStatus })
            if (selType) filtered = filtered.filter(function (a) { return String(a.appointment_type || '') === selType })

            filtered.sort(function (a, b) {
                return ((b.appointment_datetime || '') > (a.appointment_datetime || '')) ? 1 : -1
            })

            if (!filtered.length) {
                historyBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No matching appointments found.</div>'
                return
            }

            var html = ''
            filtered.forEach(function (a) {
                var dt = a.appointment_datetime ? String(a.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
                var doctor = personLabel(a.doctor, '-')
                html += '<div class="rounded-xl border border-slate-200 bg-white p-3 hover:border-green-200 transition-colors cursor-pointer admin-history-row" data-appointment-id="' + a.appointment_id + '">' +
                    '<div class="flex items-center justify-between mb-1">' +
                        '<span class="text-[0.78rem] font-semibold text-slate-800">' + escapeHtml(dt) + '</span>' +
                        statusBadge(a.status) +
                    '</div>' +
                    '<div class="text-[0.72rem] text-slate-500 mb-2">' + escapeHtml(doctor) + ' · ' + escapeHtml(apptTypeLabel(a.appointment_type)) + '</div>' +
                    '<button type="button" class="text-[0.7rem] font-semibold text-green-700 hover:text-green-800 admin-history-details" data-appointment-id="' + a.appointment_id + '">View Details →</button>' +
                '</div>'
            })
            historyBody.innerHTML = html

            historyBody.querySelectorAll('.admin-history-details').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation()
                    var apptId = this.getAttribute('data-appointment-id')
                    loadAppointmentDetail(apptId)
                })
            })
            historyBody.querySelectorAll('.admin-history-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    var apptId = this.getAttribute('data-appointment-id')
                    loadAppointmentDetail(apptId)
                })
            })
        }

        function loadAppointmentDetail(appointmentId) {
            if (!detailBody) return
            detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading details…</div>'
            apiFetch("{{ url('/api/appointments') }}/" + appointmentId, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () { return { ok: false, data: null } })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Failed to load details.</div>'
                        return
                    }
                    renderAppointmentDetail(result.data)
                })
                .catch(function () {
                    detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Network error loading details.</div>'
                })
        }

        function renderPrescriptions(appt) {
            var tx = appt.transaction || null
            if (!tx) return '<span class="text-slate-400">-</span>'
            var prescriptions = Array.isArray(tx.prescriptions) ? tx.prescriptions : []
            if (!prescriptions.length) return '<span class="text-slate-400">No prescriptions.</span>'
            var html = ''
            prescriptions.forEach(function (p) {
                html += '<div class="mb-3 last:mb-0">' +
                    '<div class="text-[0.72rem] text-slate-500 mb-1">' + (p.notes ? escapeHtml(p.notes) : '') + '</div>'
                var items = Array.isArray(p.items) ? p.items : []
                if (items.length) {
                    html += '<div class="space-y-1">'
                    items.forEach(function (item) {
                        var med = item.medicine || {}
                        var medName = med.medicine_name || med.name || 'Medicine #' + (med.medicine_id || '')
                        var dosage = item.dosage || med.dosage || ''
                        var quantity = item.quantity || ''
                        var instructions = item.instructions || ''
                        html += '<div class="flex items-center justify-between text-[0.78rem] bg-slate-50 rounded-md px-2 py-1">' +
                            '<span class="text-slate-800">' + escapeHtml(medName) + '</span>' +
                            '<span class="text-slate-500 text-[0.7rem]">' + escapeHtml(dosage ? dosage : '') + (dosage && quantity ? ' · ' : '') + escapeHtml(quantity ? 'x' + quantity : '') + '</span>' +
                        '</div>'
                        if (instructions) {
                            html += '<div class="text-[0.72rem] text-slate-400 pl-2">' + escapeHtml(instructions) + '</div>'
                        }
                    })
                    html += '</div>'
                }
                html += '</div>'
            })
            return html
        }

        function renderAppointmentDetail(appt) {
            if (!detailBody) return
            if (!appt) {
                detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>'
                if (detailStatusSelect) { detailStatusSelect.value = ''; detailStatusSelect.disabled = true }
                if (detailUpdateBtn) detailUpdateBtn.disabled = true
                return
            }
            var patient = appt.patient || {}
            var patientName = personLabel(patient, 'N/A')
            var dt = appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
            var tx = appt.transaction || null
            var services = Array.isArray(appt.services) ? appt.services : []
            var serviceRows = services.length ? services.map(function (s) {
                var sn = String(s && s.service_name ? s.service_name : '').trim()
                var sd = String(s && s.description ? s.description : '').trim()
                var sp = s && s.price != null ? '\u20B1' + String(s.price) : ''
                var text = sn
                if (sd) text += ' - ' + sd
                if (sp) text += ' : ' + sp
                return '<div class="text-[0.7rem] text-slate-800">' + escapeHtml(text) + '</div>'
            }).join('') : '<div class="text-[0.72rem] text-slate-400">-</div>'
            var amount = tx ? (tx.amount || 0) : 0
            var discountAmount = tx ? (tx.discount_amount || 0) : 0
            var discountType = tx ? (tx.discount_type || 'none') : 'none'
            var net = parseFloat(amount) - parseFloat(discountAmount)
            var diagnosis = tx ? (tx.diagnosis || '-') : '-'
            var treatment = tx ? (tx.treatment_notes || '-') : '-'

            var html = '<div class="space-y-3">' +
                (String(appt.status || '').toLowerCase() === 'completed' && tx ?
                    '<div class="text-right">' +
                        '<button type="button" class="text-[0.72rem] font-semibold text-green-700 hover:text-green-800 underline underline-offset-2" onclick="window.openAdminConsultReceipt(\'' + String(tx.transaction_id || tx.id || '').replace(/'/g, "\\'") + '\')">Generate Consultation Summary</button>' +
                    '</div>' :
                '') +
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Appointment</div>' +
                    '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                        '<div class="text-slate-500">Patient</div>' +
                        '<div class="text-slate-800 font-medium">' + escapeHtml(patientName) + '</div>' +
                        '<div class="text-slate-500">Date & Time</div>' +
                        '<div class="text-slate-800 font-medium">' + escapeHtml(dt) + '</div>' +
                        '<div class="text-slate-500">Doctor</div>' +
                        '<div class="text-slate-800 font-medium">' + escapeHtml(personLabel(appt.doctor, '-')) + '</div>' +
                        '<div class="text-slate-500">Type</div>' +
                        '<div class="text-slate-800 font-medium">' + escapeHtml(apptTypeLabel(appt.appointment_type)) + '</div>' +
                        '<div class="text-slate-500">Status</div>' +
                        '<div>' + statusBadge(appt.status) + '</div>' +
                        '<div class="text-slate-500">Reason</div>' +
                        '<div class="text-slate-800">' + (appt.reason_for_visit ? escapeHtml(appt.reason_for_visit) : '<span class="text-slate-400">-</span>') + '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Services & Payment</div>' +
                    '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                        '<div class="text-slate-500">Services</div>' +
                        '<div class="text-slate-800">' + serviceRows + '</div>' +
                        '<div class="text-slate-500">Gross Amount</div>' +
                        '<div class="text-slate-800 font-medium">₱' + escapeHtml(Number(amount).toFixed(2)) + '</div>' +
                        '<div class="text-slate-500">Discount (' + escapeHtml(discountType !== 'none' ? discountType.toUpperCase() : 'None') + ')</div>' +
                        '<div class="text-slate-800">−₱' + escapeHtml(Number(discountAmount).toFixed(2)) + '</div>' +
                        '<div class="text-slate-500 font-semibold">Net</div>' +
                        '<div class="text-slate-800 font-bold text-green-700">₱' + escapeHtml(net.toFixed(2)) + '</div>' +
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
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions</div>' +
                    '<div class="text-[0.78rem]">' +
                        renderPrescriptions(appt) +
                    '</div>' +
                '</div>' +
            '</div>'
            detailBody.innerHTML = html

            // Enable status select
            var currentStatus = String(appt && appt.status ? appt.status : '').toLowerCase()
            if (detailStatusSelect) {
                detailStatusSelect.value = currentStatus
                detailStatusSelect.disabled = false
            }
            if (detailUpdateBtn) {
                detailUpdateBtn.disabled = false
            }
            // Store current appointment ID for status update
            window.__adminCurrentApptId = appt.appointment_id || appt.id || null
        }

        // ── Consultation Receipt Modal ──
        window.openAdminConsultReceipt = function (txId) {
            if (!adminConsultReceiptModal || !adminConsultReceiptIframe || !txId) return
            adminConsultReceiptIframe.src = '{{ url('/print/consultations') }}/' + encodeURIComponent(String(txId))
            adminConsultReceiptModal.classList.remove('hidden')
        }

        function closeAdminConsultReceipt() {
            if (!adminConsultReceiptModal) return
            adminConsultReceiptModal.classList.add('hidden')
            if (adminConsultReceiptIframe) adminConsultReceiptIframe.src = ''
        }

        // ── Consultation Receipt Modal events ──
        if (adminConsultReceiptCloseBtn) {
            adminConsultReceiptCloseBtn.addEventListener('click', closeAdminConsultReceipt)
        }
        if (adminConsultReceiptPrintBtn && adminConsultReceiptIframe) {
            adminConsultReceiptPrintBtn.addEventListener('click', function () {
                var iframeWin = adminConsultReceiptIframe.contentWindow
                if (iframeWin) {
                    iframeWin.focus()
                    iframeWin.print()
                }
            })
        }
        if (adminConsultReceiptModal) {
            adminConsultReceiptModal.addEventListener('click', function (e) {
                if (e.target === adminConsultReceiptModal) closeAdminConsultReceipt()
            })
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && adminConsultReceiptModal && !adminConsultReceiptModal.classList.contains('hidden')) {
                closeAdminConsultReceipt()
            }
        })

        // ── Status update handler ──
        if (detailUpdateBtn) {
            detailUpdateBtn.addEventListener('click', function () {
                var apptId = window.__adminCurrentApptId
                if (!apptId) return
                var newStatus = detailStatusSelect ? detailStatusSelect.value : ''
                if (!newStatus) {
                    if (typeof showToast === 'function') showToast('Please select a status.', 'error')
                    return
                }
                detailUpdateBtn.disabled = true
                detailUpdateBtn.textContent = 'Updating...'
                apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(apptId) + "/status", {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ status: newStatus })
                })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data }
                        }).catch(function () { return { ok: false, data: null } })
                    })
                    .then(function (r) {
                        if (!r.ok) {
                            if (typeof showToast === 'function') showToast(r.data && r.data.message ? r.data.message : 'Failed to update status.', 'error')
                            return
                        }
                        if (typeof showToast === 'function') showToast('Status updated successfully.', 'success')
                        loadAppointments()
                        loadAppointmentDetail(apptId)
                    })
                    .catch(function () {
                        if (typeof showToast === 'function') showToast('Network error.', 'error')
                    })
                    .finally(function () {
                        detailUpdateBtn.disabled = false
                        detailUpdateBtn.textContent = 'Update Status'
                    })
            })
        }

        // ── Modal event bindings ──
        if (historyClose) historyClose.addEventListener('click', closeHistoryModal)
        if (historyOverlay) {
            historyOverlay.addEventListener('click', function (e) {
                if (e.target === historyOverlay) closeHistoryModal()
            })
        }
        if (historyDate) historyDate.addEventListener('change', renderHistory)
        if (historyStatus) historyStatus.addEventListener('change', renderHistory)
        if (historyType) historyType.addEventListener('change', renderHistory)

        function loadDoctors() {
            apiFetch("{{ url('/api/doctors') }}?per_page=15", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    })
                })
                .then(function (result) {
                    if (!result.ok) return
                    doctors = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    renderDoctorOptions()
                })
                .catch(function () {})
        }

        function renderDoctorOptions() {
            if (!doctorSelect) return
            var selected = doctorSelect.value
            var html = '<option value="">All doctors</option>'
            doctors.forEach(function (d) {
                html += '<option value="' + d.user_id + '">' + escapeHtml(personLabel(d, 'Doctor #' + d.user_id)) + '</option>'
            })
            doctorSelect.innerHTML = html
            doctorSelect.value = selected
        }

        function loadAppointments(page) {
            if (!tableBody) return
            tableBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Loading appointments…</td></tr>'
            showError('')
            page = page || 1
            apptCurrentPage = page

            var pool = []
            var seenPids = {}
            var serverPage = 1
            var maxServerPages = 100
            var baseUrl = "{{ url('/api/appointments') }}?per_page=10&order=latest"

            function fetchNext() {
                if (serverPage > maxServerPages) { finish(pool, page); return }

                apiFetch(baseUrl + '&page=' + serverPage, { method: 'GET' })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data }
                        }).catch(function () { return { ok: false, data: null } })
                    })
                    .then(function (result) {
                        if (!result.ok) { finish(pool, page); return }

                        var items = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                        items.forEach(function (a) {
                            var pid = a.patient_id || (a.patient && a.patient.user_id) || ''
                            if (pid) seenPids[pid] = true
                            pool.push(a)
                        })

                        var uniqueCount = Object.keys(seenPids).length
                        var lastServerPage = result.data.last_page || serverPage

                        if (uniqueCount >= page * 10 || items.length === 0 || serverPage >= lastServerPage) {
                            finish(pool, page)
                        } else {
                            serverPage++
                            fetchNext()
                        }
                    })
                    .catch(function () { finish(pool, page) })
            }

            fetchNext()

            function finish(pool, page) {
                appointments = pool
                var uniqueCount = Object.keys(seenPids).length
                var totalUniquePages = Math.ceil(uniqueCount / 10) || 1
                apptMeta = {
                    current_page: Math.min(page, totalUniquePages),
                    last_page: totalUniquePages,
                    total: uniqueCount
                }
                renderAppointments()
            }
        }

        function renderAppointments() {
            if (!tableBody) return

            var selectedDate = dateInput ? dateInput.value : ''
            var selectedDoctor = doctorSelect ? doctorSelect.value : ''
            var selectedType = typeSelect ? typeSelect.value : ''
            var selectedStatus = statusSelect ? statusSelect.value : ''
            var selectedSort = sortSelect ? sortSelect.value : 'newest'
            var query = searchInput ? searchInput.value.toLowerCase().trim() : ''

            // Filter
            var filtered = appointments.slice()
            if (selectedDate) {
                filtered = filtered.filter(function (a) {
                    return (a.appointment_datetime || '').slice(0, 10) === selectedDate
                })
            }
            if (selectedDoctor) {
                filtered = filtered.filter(function (a) {
                    return String(a.doctor_id || (a.doctor && a.doctor.user_id) || '') === String(selectedDoctor)
                })
            }
            if (selectedType) {
                filtered = filtered.filter(function (a) {
                    return String(a.appointment_type || '') === selectedType
                })
            }
            if (selectedStatus) {
                filtered = filtered.filter(function (a) {
                    return String(a.status || '') === selectedStatus
                })
            }
            if (query) {
                filtered = filtered.filter(function (a) {
                    var p = personLabel(a.patient, '').toLowerCase()
                    var d = personLabel(a.doctor, '').toLowerCase()
                    return p.indexOf(query) !== -1 || d.indexOf(query) !== -1
                })
            }

            // Apply sort order (descending by default, ascending if 'oldest')
            var sorted = filtered.slice()
            sorted.sort(function (a, b) {
                var da = a.appointment_datetime || ''
                var db = b.appointment_datetime || ''
                if (selectedSort === 'oldest') {
                    if (da < db) return -1; if (da > db) return 1; return 0
                }
                if (da < db) return 1; if (da > db) return -1; return 0
            })

            // Keep only 1 per patient (most recent) — only when sorting newest-first
            if (selectedSort !== 'oldest') {
                var seenPatient = {}
                sorted = sorted.filter(function (a) {
                    var pid = a.patient_id || (a.patient && a.patient.user_id) || ''
                    if (!pid) return true
                    if (seenPatient[pid]) return false
                    seenPatient[pid] = true
                    return true
                })
            }

            // Slice to current visual page (10 per page)
            var pageStart = (apptCurrentPage - 1) * 10
            sorted = sorted.slice(pageStart, pageStart + 10)

            if (!sorted.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No appointments found.</td></tr>'
                renderApptPagination()
                return
            }

            var html = ''
            sorted.forEach(function (a) {
                var dt = a.appointment_datetime ? String(a.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
                var patient = personLabel(a.patient, 'Patient #' + (a.patient_id || ''))
                var patientId = a.patient_id || (a.patient && a.patient.user_id) || ''
                var doctor = personLabel(a.doctor, 'Doctor #' + (a.doctor_id || ''))
                html += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(dt) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(patient) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(doctor) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(apptTypeLabel(a.appointment_type)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' + statusBadge(a.status) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' +
                        '<button type="button" class="admin-see-history inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.78rem] font-semibold hover:bg-slate-50" data-patient-id="' + escapeHtml(patientId) + '" data-patient-name="' + escapeHtml(patient) + '">' +
                            'See Appointment History' +
                        '</button>' +
                    '</td>' +
                '</tr>'
            })
            tableBody.innerHTML = html

            tableBody.querySelectorAll('.admin-see-history').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var pid = this.getAttribute('data-patient-id')
                    var pname = this.getAttribute('data-patient-name')
                    openHistoryModal(pid, pname)
                })
            })
            renderApptPagination()
        }

        function reloadAppts() {
            apptCurrentPage = 1
            loadAppointments(1)
        }

        if (dateInput) dateInput.addEventListener('change', reloadAppts)
        if (doctorSelect) doctorSelect.addEventListener('change', reloadAppts)
        if (typeSelect) typeSelect.addEventListener('change', reloadAppts)
        if (statusSelect) statusSelect.addEventListener('change', reloadAppts)
        if (sortSelect) sortSelect.addEventListener('change', reloadAppts)
        if (searchInput) searchInput.addEventListener('input', reloadAppts)

        var apptRefreshBtn = document.getElementById('adminApptRefreshBtn')
        if (apptRefreshBtn) {
            apptRefreshBtn.addEventListener('click', function () { loadAppointments(apptCurrentPage) })
        }

        loadDoctors()
        loadAppointments(1)
    })
</script>
