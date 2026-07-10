<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900">Prescriptions</h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Medications</span>
    </div>
    <p class="text-xs text-slate-500 mb-3">
        Recent prescriptions with basic medication details.
    </p>

    <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
        <div class="flex-1">
            <label for="doctor_prescription_search" class="block text-[0.7rem] text-slate-600 mb-1">Search prescriptions</label>
            <input id="doctor_prescription_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Patient name, ID or notes">
        </div>
        <div class="w-full md:w-40">
            <label for="doctor_prescription_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
            <select id="doctor_prescription_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="date_desc">Newest first</option>
                <option value="date_asc">Oldest first</option>
                <option value="patient_asc">Patient A–Z</option>
                <option value="patient_desc">Patient Z–A</option>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-[0.7rem] text-slate-600 mb-1">&nbsp;</label>
            <button type="button" id="docPrescriptionRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                Refresh
            </button>
        </div>
    </div>

<div class="overflow-x-auto overflow-y-auto scrollbar-hidden" style="max-height:300px">
        <table class="min-w-full text-left text-xs text-slate-600">
            <thead class="sticky top-0 bg-white z-10">
                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                    <th class="py-2 pr-4 font-semibold">Prescription ID</th>
                    <th class="py-2 pr-4 font-semibold">Patient</th>
                    <th class="py-2 pr-4 font-semibold">Date</th>
                    <th class="py-2 pr-4 font-semibold">Items</th>
                    <th class="py-2 pr-4 font-semibold">Notes</th>
                    <th class="py-2 pr-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody id="doctorPrescriptionTbody">
                <tr>
                    <td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Loading…</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="doctorPrescriptionPagination" class="mt-3 flex items-center justify-center gap-1"></div>
</div>

<div id="doctorPrescriptionItemsOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40"></div>
<aside id="doctorPrescriptionItemsPanel" class="fixed top-0 right-0 z-[75] h-full w-full max-w-xl translate-x-full border-l border-slate-200 bg-white shadow-2xl transition-transform duration-300 ease-out">
    <div class="flex h-full flex-col">
        <div class="border-b border-slate-200 px-5 py-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Prescription Items</div>
                    <h3 id="doctorPrescriptionItemsTitle" class="mt-1 text-base font-semibold text-slate-900">Prescription details</h3>
                    <p id="doctorPrescriptionItemsSubtitle" class="mt-1 text-xs text-slate-500">Review the prescribed medicines and instructions.</p>
                </div>
                <button id="doctorPrescriptionItemsClose" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                    <span class="text-lg leading-none">&times;</span>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4">
            <div id="doctorPrescriptionItemsMeta" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-[0.78rem] text-slate-600"></div>
            <div id="doctorPrescriptionItemsList" class="mt-4 space-y-3"></div>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── Pagination state ────────────────────────────────────────────
        var prescCurrentPage = 1
        var prescPerPage = 10
        var prescVisibleCount = 5
        var prescLastPage = 1
        var prescTotal = 0
        var prescDoctorId = {{ (int) (($currentUser->user_id ?? 0)) }}
        var prescSearchQuery = ''

        var overlay = document.getElementById('doctorPrescriptionItemsOverlay')
        var panel = document.getElementById('doctorPrescriptionItemsPanel')
        var closeButton = document.getElementById('doctorPrescriptionItemsClose')
        var panelTitle = document.getElementById('doctorPrescriptionItemsTitle')
        var panelSubtitle = document.getElementById('doctorPrescriptionItemsSubtitle')
        var panelMeta = document.getElementById('doctorPrescriptionItemsMeta')
        var panelList = document.getElementById('doctorPrescriptionItemsList')
        var tbody = document.getElementById('doctorPrescriptionTbody')
        var pagEl = document.getElementById('doctorPrescriptionPagination')
        var searchInput = document.getElementById('doctor_prescription_search')
        var sortSelect = document.getElementById('doctor_prescription_sort')

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function setVisible(el, visible) {
            if (!el) return
            el.classList.toggle('hidden', !visible)
        }

        function openPanel() {
            if (overlay) overlay.classList.remove('hidden')
            if (panel) panel.classList.remove('translate-x-full')
        }

        function closePanel() {
            if (overlay) overlay.classList.add('hidden')
            if (panel) panel.classList.add('translate-x-full')
        }

        function renderItemCard(item, index) {
            var details = [
                { label: 'Dosage', value: item && item.dosage ? item.dosage : '-' },
                { label: 'Frequency', value: item && item.frequency ? item.frequency : '-' },
                { label: 'Duration', value: item && item.duration ? item.duration : '-' },
                { label: 'Instructions', value: item && item.instructions ? item.instructions : '-' },
            ]
            return '' +
                '<div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">' +
                    '<div class="flex items-start justify-between gap-3">' +
                        '<div>' +
                            '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Item ' + escapeHtml(index + 1) + '</div>' +
                            '<div class="mt-1 text-sm font-semibold text-slate-900">' + escapeHtml(item && item.medicine ? item.medicine : 'Medicine') + '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="mt-3 grid gap-3 sm:grid-cols-2">' +
                        details.map(function (detail) {
                            return '' +
                                '<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2">' +
                                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400">' + escapeHtml(detail.label) + '</div>' +
                                    '<div class="mt-1 text-[0.8rem] text-slate-700 whitespace-pre-line">' + escapeHtml(detail.value) + '</div>' +
                                '</div>'
                        }).join('') +
                    '</div>' +
                '</div>'
        }

        function showPrescriptionItems(button) {
            if (!button) return
            var prescriptionId = button.getAttribute('data-prescription-id') || ''
            var patientName = button.getAttribute('data-patient-name') || 'Patient'
            var prescribedDate = button.getAttribute('data-prescribed-date') || '-'
            var items = []
            try {
                items = JSON.parse(button.getAttribute('data-items') || '[]')
            } catch (error) {
                items = []
            }
            if (panelTitle) panelTitle.textContent = 'Prescription #' + prescriptionId
            if (panelSubtitle) panelSubtitle.textContent = patientName + ' • ' + prescribedDate
            if (panelMeta) {
                panelMeta.innerHTML = '' +
                    '<div><span class="font-semibold text-slate-800">Patient:</span> ' + escapeHtml(patientName) + '</div>' +
                    '<div class="mt-1"><span class="font-semibold text-slate-800">Date:</span> ' + escapeHtml(prescribedDate) + '</div>' +
                    '<div class="mt-1"><span class="font-semibold text-slate-800">Items:</span> ' + escapeHtml(items.length) + '</div>'
            }
            if (panelList) {
                panelList.innerHTML = items.length
                    ? items.map(renderItemCard).join('')
                    : '<div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-4 text-[0.78rem] text-slate-500">No prescription items recorded for this prescription.</div>'
            }
            openPanel()
        }

        // ── API Load ─────────────────────────────────────────────────────
        function loadPrescriptions(page) {
            if (!tbody) return
            tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400 animate-pulse">Loading…</td></tr>'

            apiFetch("{{ url('/api/prescriptions') }}?per_page=" + prescPerPage + "&page=" + page + "&doctor_id=" + prescDoctorId)
                .then(function (r) { return r.json() })
                .then(function (result) {
                    if (!result || !result.data) {
                        tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No prescriptions found.</td></tr>'
                        prescTotal = 0
                        prescLastPage = 1
                        renderPrescriptionPagination()
                        return
                    }
                    var data = result.data
                    prescCurrentPage = result.current_page || page
                    prescLastPage = result.last_page || 1
                    prescTotal = result.total || 0

                    if (!data.length) {
                        tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No prescriptions found.</td></tr>'
                    } else {
                        var html = ''
                        data.forEach(function (p) {
                            var transaction = p.transaction || {}
                            var appointment = transaction.appointment || {}
                            var patient = appointment.patient || {}
                            var parts = [patient.firstname, patient.middlename, patient.lastname].filter(function (v) { return v && String(v).trim() !== '' })
                            var patientName = parts.length ? parts.join(' ') : (patient.email || 'Patient')
                            var dateKey = p.prescribed_datetime ? p.prescribed_datetime.slice(0, 10) : ''
                            var itemsArr = Array.isArray(p.items) ? p.items : []
                            var itemsCount = itemsArr.length
                            var itemPayload = itemsArr.map(function (item) {
                                var medicine = item.medicine || {}
                                var mn = [medicine.generic_name, medicine.brand_name ? '(' + medicine.brand_name + ')' : ''].filter(Boolean).join(' ').trim()
                                return {
                                    medicine: mn || ('Medicine #' + (item.medicine_id || '')),
                                    dosage: item.dosage,
                                    frequency: item.frequency,
                                    duration: item.duration,
                                    instructions: item.instructions,
                                }
                            })
                            var notes = p.notes || ''
                            var notesDisplay = notes.length > 80 ? notes.slice(0, 80) + '…' : (notes || '')

                            html += '<tr class="border-b border-slate-50 last:border-0">' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">#' + escapeHtml(p.prescription_id) + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(patientName) + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(dateKey) + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (itemsCount > 0 ? itemsCount + ' item' + (itemsCount === 1 ? '' : 's') : '<span class="text-[0.7rem] text-slate-400">No items</span>') + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (notes ? escapeHtml(notesDisplay) : '<span class="text-[0.7rem] text-slate-400">No notes</span>') + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' +
                                '<button type="button" class="doctor-prescription-view-items inline-flex items-center justify-center rounded-xl border border-green-200 bg-green-50 px-3 py-1.5 text-[0.74rem] font-semibold text-green-700 hover:bg-green-100" ' +
                                'data-prescription-id="' + escapeHtml(p.prescription_id) + '" ' +
                                'data-patient-name="' + escapeHtml(patientName) + '" ' +
                                'data-prescribed-date="' + escapeHtml(dateKey || '-') + '" ' +
                                "data-items='" + JSON.stringify(itemPayload).replace(/'/g, '&#39;') + "'>View items</button></td></tr>"
                        })
                        tbody.innerHTML = html
                        // Re-attach view items listeners
                        tbody.querySelectorAll('.doctor-prescription-view-items').forEach(function (btn) {
                            btn.addEventListener('click', function () { showPrescriptionItems(btn) })
                        })
                    }
                    renderPrescriptionPagination()
                })
                .catch(function () {
                    tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Failed to load prescriptions.</td></tr>'
                    renderPrescriptionPagination()
                })
        }

        // ── Pagination UI ────────────────────────────────────────────────
        function renderPrescriptionPagination() {
            if (!pagEl) return
            var totalPages = prescLastPage
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'

            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + prescTotal + ' entries</span>'
            html += '<button type="button" class="' + (prescCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-presc-page="prev"' + (prescCurrentPage === 1 ? ' disabled' : '') + '>&lsaquo; Prev</button>'

            var ws = Math.max(1, prescCurrentPage - Math.floor(prescVisibleCount / 2))
            var we = Math.min(ws + prescVisibleCount - 1, totalPages)
            if (we - ws + 1 < prescVisibleCount) ws = Math.max(1, we - prescVisibleCount + 1)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === prescCurrentPage ? btnActive : btnInactive) + '" data-presc-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) {
                html += '<button type="button" class="' + btnInactive + '" data-presc-page="next-window" title="Next set">&hellip;</button>'
            }
            html += '<button type="button" class="' + (prescCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-presc-page="next"' + (prescCurrentPage === totalPages ? ' disabled' : '') + '>Next &rsaquo;</button>'

            pagEl.innerHTML = html
            pagEl.querySelectorAll('button[data-presc-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-presc-page')
                    if (p === 'prev' && prescCurrentPage > 1) { prescCurrentPage-- }
                    else if (p === 'next' && prescCurrentPage < totalPages) { prescCurrentPage++ }
                    else if (p === 'next-window') { prescCurrentPage = Math.min(we + 1, totalPages) }
                    else if (p !== 'prev' && p !== 'next') { prescCurrentPage = parseInt(p, 10) }
                    else return
                    loadPrescriptions(prescCurrentPage)
                })
            })
        }

        // ── Initial load ─────────────────────────────────────────────────
        loadPrescriptions(1)

        // ── Search / Sort (client-side on current page) ──────────────────
        function applyClientFilter() {
            var query = searchInput ? searchInput.value.toLowerCase().trim() : ''
            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'))
            rows.forEach(function (row) {
                var text = (row.textContent || '').toLowerCase()
                row.style.display = query ? (text.indexOf(query) !== -1 ? '' : 'none') : ''
            })
        }
        function applyClientSort() {
            if (!sortSelect || !tbody) return
            var value = sortSelect.value
            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'))
            var visible = rows.filter(function (r) { return r.style.display !== 'none' })
            visible.sort(function (a, b) {
                var pa = (a.cells[1] ? a.cells[1].textContent || '' : '').toLowerCase()
                var pb = (b.cells[1] ? b.cells[1].textContent || '' : '').toLowerCase()
                var da = (a.cells[2] ? a.cells[2].textContent || '' : '').toLowerCase()
                var db = (b.cells[2] ? b.cells[2].textContent || '' : '').toLowerCase()
                if (value === 'patient_asc' || value === 'patient_desc') {
                    if (pa < pb) return value === 'patient_asc' ? -1 : 1
                    if (pa > pb) return value === 'patient_asc' ? 1 : -1
                    return 0
                }
                return value === 'date_asc' ? (da < db ? -1 : da > db ? 1 : 0) : (da > db ? -1 : da < db ? 1 : 0)
            })
            visible.forEach(function (r) { tbody.appendChild(r) })
        }
        if (searchInput) searchInput.addEventListener('input', function () { applyClientFilter(); applyClientSort() })
        if (sortSelect) sortSelect.addEventListener('change', applyClientSort)

        // ── Panel close ──────────────────────────────────────────────────
        if (closeButton) closeButton.addEventListener('click', closePanel)
        if (overlay) overlay.addEventListener('click', closePanel)
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') closePanel()
        })

        // ── Refresh button ───────────────────────────────────────────────
        if (document.getElementById('docPrescriptionRefreshBtn')) {
            document.getElementById('docPrescriptionRefreshBtn').addEventListener('click', function () {
                prescCurrentPage = 1
                loadPrescriptions(1)
            })
        }
    })
</script>
