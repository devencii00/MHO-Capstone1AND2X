<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900">History</h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Past records</span>
    </div>
    <p class="text-xs text-slate-500 mb-3">
        View past patient visits and clinical records for follow-up and review.
    </p>

    <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
        <div class="flex-1">
            <label for="doctor_visit_search" class="block text-[0.7rem] text-slate-600 mb-1">Search visits</label>
            <input id="doctor_visit_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Patient name, ID or diagnosis">
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-[0.7rem] text-slate-600 mb-1">Quick filter</label>
            <button id="doctor_visit_today_toggle" type="button" class="w-full md:w-auto inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Today
            </button>
        </div>
        <div class="w-full md:w-40">
            <label for="doctor_visit_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
            <select id="doctor_visit_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="date_desc">Newest first</option>
                <option value="date_asc">Oldest first</option>
                <option value="patient_asc">Patient A–Z</option>
                <option value="patient_desc">Patient Z–A</option>
            </select>
        </div>
        <div class="w-full md:w-auto">
            <label class="block text-[0.7rem] text-slate-600 mb-1">&nbsp;</label>
            <button type="button" id="docVisitRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                Refresh
            </button>
        </div>
    </div>

   <div class="overflow-x-auto overflow-y-auto scrollbar-hidden" style="max-height:300px">
        <table class="min-w-full text-left text-xs text-slate-600">
            <thead class="sticky top-0 bg-white z-10">
                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                    <th class="py-2 pr-4 font-semibold">Visit ID</th>
                    <th class="py-2 pr-4 font-semibold">Patient</th>
                    <th class="py-2 pr-4 font-semibold">Visit date</th>
                    <th class="py-2 pr-4 font-semibold">Reason</th>
                    <th class="py-2 pr-4 font-semibold">Diagnosis</th>
                    <th class="py-2 pr-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="doctorVisitTbody">
                <tr>
                    <td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Loading…</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="doctorVisitPagination" class="mt-3 flex items-center justify-center gap-1"></div>
</div>

<div id="doctorVisitInfoOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40"></div>
<aside id="doctorVisitInfoPanel" class="fixed top-0 right-0 z-[75] h-full w-full max-w-2xl translate-x-full border-l border-slate-200 bg-white shadow-2xl transition-transform duration-300 ease-out">
    <div class="flex h-full flex-col">
        <div class="border-b border-slate-200 px-5 py-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Patient Information</div>
                    <h3 id="doctorVisitPanelTitle" class="mt-1 text-base font-semibold text-slate-900">Patient details</h3>
                    <p id="doctorVisitPanelSubtitle" class="mt-1 text-xs text-slate-500">Select a visit to review the patient profile and history.</p>
                </div>
                <button id="doctorVisitPanelClose" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
        </div>

        <div id="doctorVisitPanelLoading" class="hidden mx-5 mt-4 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.78rem] text-slate-600">
            Loading patient information...
        </div>
        <div class="flex-1 overflow-y-auto px-5 py-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient</div>
                    <div id="doctorVisitPatientName" class="mt-1 text-sm font-semibold text-slate-900">-</div>
                    <div id="doctorVisitPatientMeta" class="mt-1 text-[0.75rem] text-slate-500">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Contacts</div>
                    <div id="doctorVisitPatientContacts" class="mt-1 text-[0.75rem] text-slate-600">-</div>
                    <div id="doctorVisitPatientAddress" class="mt-1 text-[0.75rem] text-slate-500">-</div>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-slate-200 bg-white px-4 py-3">
                <div class="text-[0.72rem] font-semibold text-slate-800">Medical background</div>
                <div id="doctorVisitMedicalBackground" class="mt-3 flex flex-wrap gap-2"></div>
            </div>

            <div class="mt-4 rounded-2xl border border-slate-200 bg-white">
                <div class="flex items-center gap-2 border-b border-slate-200 px-4 py-3">
                    <button type="button" id="doctorVisitTabVisits" class="inline-flex items-center rounded-lg border border-green-600 bg-green-600 px-3 py-1.5 text-[0.74rem] font-semibold text-white">
                        Visits history
                    </button>
                    <button type="button" id="doctorVisitTabPrescriptions" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.74rem] font-semibold text-slate-700 hover:bg-slate-50">
                        Prescription history
                    </button>
                </div>
                <div id="doctorVisitTabPanelVisits" class="max-h-[26rem] overflow-y-auto px-4 py-3"></div>
                <div id="doctorVisitTabPanelPrescriptions" class="hidden max-h-[26rem] overflow-y-auto px-4 py-3"></div>
            </div>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── Pagination state ────────────────────────────────────────────
        var visitCurrentPage = 1
        var visitPerPage = 10
        var visitVisibleCount = 5
        var visitLastPage = 1
        var visitTotal = 0
        var visitDoctorId = {{ (int) (($currentUser->user_id ?? 0)) }}

        var searchInput = document.getElementById('doctor_visit_search')
        var sortSelect = document.getElementById('doctor_visit_sort')
        var todayToggle = document.getElementById('doctor_visit_today_toggle')
        var overlay = document.getElementById('doctorVisitInfoOverlay')
        var panel = document.getElementById('doctorVisitInfoPanel')
        var closeButton = document.getElementById('doctorVisitPanelClose')
        var loadingBox = document.getElementById('doctorVisitPanelLoading')
        var panelTitle = document.getElementById('doctorVisitPanelTitle')
        var panelSubtitle = document.getElementById('doctorVisitPanelSubtitle')
        var patientNameEl = document.getElementById('doctorVisitPatientName')
        var patientMetaEl = document.getElementById('doctorVisitPatientMeta')
        var patientContactsEl = document.getElementById('doctorVisitPatientContacts')
        var patientAddressEl = document.getElementById('doctorVisitPatientAddress')
        var backgroundEl = document.getElementById('doctorVisitMedicalBackground')
        var visitsTab = document.getElementById('doctorVisitTabVisits')
        var prescriptionsTab = document.getElementById('doctorVisitTabPrescriptions')
        var visitsPanel = document.getElementById('doctorVisitTabPanelVisits')
        var prescriptionsPanel = document.getElementById('doctorVisitTabPanelPrescriptions')
        var tbody = document.getElementById('doctorVisitTbody')
        var pagEl = document.getElementById('doctorVisitPagination')
        var todayOnly = false
        var activeTab = 'visits'

        function setVisible(el, visible) {
            if (!el) return
            el.classList.toggle('hidden', !visible)
        }

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function api(url) {
            if (typeof apiFetch !== 'function') {
                return Promise.reject(new Error('API client is not available.'))
            }
            return apiFetch(url, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        throw new Error(result.data && result.data.message ? result.data.message : 'Request failed.')
                    }
                    return result.data
                })
        }

        function paginatedRows(payload) {
            if (!payload) return []
            if (Array.isArray(payload.data)) return payload.data
            if (Array.isArray(payload)) return payload
            return []
        }

        function renderBadge(label, tone) {
            var base = 'inline-flex items-center rounded-full border px-2 py-0.5 text-[0.68rem] font-medium '
            if (tone === 'danger') return '<span class="' + base + 'border-red-200 bg-red-50 text-red-700">' + escapeHtml(label) + '</span>'
            if (tone === 'warn') return '<span class="' + base + 'border-amber-200 bg-amber-50 text-amber-800">' + escapeHtml(label) + '</span>'
            return '<span class="' + base + 'border-slate-200 bg-slate-50 text-slate-700">' + escapeHtml(label) + '</span>'
        }

        function setTab(tabName) {
            activeTab = tabName === 'prescriptions' ? 'prescriptions' : 'visits'
            if (visitsTab) {
                visitsTab.classList.toggle('bg-green-600', activeTab === 'visits')
                visitsTab.classList.toggle('text-white', activeTab === 'visits')
                visitsTab.classList.toggle('border-green-600', activeTab === 'visits')
                visitsTab.classList.toggle('bg-white', activeTab !== 'visits')
                visitsTab.classList.toggle('text-slate-700', activeTab !== 'visits')
                visitsTab.classList.toggle('border-slate-200', activeTab !== 'visits')
            }
            if (prescriptionsTab) {
                prescriptionsTab.classList.toggle('bg-green-600', activeTab === 'prescriptions')
                prescriptionsTab.classList.toggle('text-white', activeTab === 'prescriptions')
                prescriptionsTab.classList.toggle('border-green-600', activeTab === 'prescriptions')
                prescriptionsTab.classList.toggle('bg-white', activeTab !== 'prescriptions')
                prescriptionsTab.classList.toggle('text-slate-700', activeTab !== 'prescriptions')
                prescriptionsTab.classList.toggle('border-slate-200', activeTab !== 'prescriptions')
            }
            setVisible(visitsPanel, activeTab === 'visits')
            setVisible(prescriptionsPanel, activeTab === 'prescriptions')
        }

        function openPanel() {
            if (overlay) overlay.classList.remove('hidden')
            if (panel) panel.classList.remove('translate-x-full')
        }

        function closePanel() {
            if (overlay) overlay.classList.add('hidden')
            if (panel) panel.classList.add('translate-x-full')
        }

        function renderMedicalBackground(rows) {
            if (!backgroundEl) return
            if (!rows.length) {
                backgroundEl.innerHTML = '<span class="text-[0.74rem] text-slate-400">No medical background recorded.</span>'
                return
            }
            backgroundEl.innerHTML = rows.map(function (item) {
                var category = String(item.category || '')
                var tone = category === 'allergy_drug' ? 'danger' : (category === 'allergy_food' ? 'warn' : 'default')
                return renderBadge(item.name || '-', tone)
            }).join(' ')
        }

        function renderVisitsHistory(rows) {
            if (!visitsPanel) return
            if (!rows.length) {
                visitsPanel.innerHTML = '<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 text-[0.78rem] text-slate-500">No visit history found.</div>'
                return
            }
            visitsPanel.innerHTML = rows.map(function (visit) {
                var when = String(visit.visit_datetime || visit.transaction_datetime || '').replace('T', ' ').slice(0, 16) || '-'
                var reason = visit.appointment && visit.appointment.reason_for_visit ? visit.appointment.reason_for_visit : 'No reason recorded'
                var diagnosis = visit.diagnosis || 'No diagnosis recorded'
                var appointmentType = visit.appointment && visit.appointment.appointment_type
                    ? String(visit.appointment.appointment_type).replace(/_/g, '-')
                    : '-'
                return '' +
                    '<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 mb-3">' +
                        '<div class="flex items-start justify-between gap-3">' +
                            '<div>' +
                                '<div class="text-[0.72rem] font-semibold text-slate-800">' + escapeHtml(when) + '</div>' +
                                '<div class="mt-1 text-[0.72rem] text-slate-500">Type: ' + escapeHtml(appointmentType) + '</div>' +
                            '</div>' +
                            '<div class="text-[0.72rem] text-slate-400">#' + escapeHtml(visit.transaction_id || '') + '</div>' +
                        '</div>' +
                        '<div class="mt-3 text-[0.76rem] text-slate-700"><span class="font-semibold">Reason:</span> ' + escapeHtml(reason) + '</div>' +
                        '<div class="mt-1 text-[0.76rem] text-slate-700"><span class="font-semibold">Diagnosis:</span> ' + escapeHtml(diagnosis) + '</div>' +
                    '</div>'
            }).join('')
        }

        function renderPrescriptionHistory(visits) {
            if (!prescriptionsPanel) return
            var entries = []
            visits.forEach(function (visit) {
                var prescriptions = Array.isArray(visit.prescriptions) ? visit.prescriptions : []
                prescriptions.forEach(function (prescription) {
                    entries.push({
                        prescribed_datetime: prescription.prescribed_datetime || visit.visit_datetime || visit.transaction_datetime || '',
                        doctor: prescription.doctor,
                        notes: prescription.notes,
                        items: Array.isArray(prescription.items) ? prescription.items : [],
                    })
                })
            })
            if (!entries.length) {
                prescriptionsPanel.innerHTML = '<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 text-[0.78rem] text-slate-500">No prescription history found.</div>'
                return
            }
            prescriptionsPanel.innerHTML = entries.map(function (entry) {
                var when = String(entry.prescribed_datetime || '').replace('T', ' ').slice(0, 16) || '-'
                var doctorName = entry.doctor
                    ? [entry.doctor.firstname, entry.doctor.middlename, entry.doctor.lastname].filter(Boolean).join(' ').trim()
                    : 'Doctor'
                var itemsHtml = entry.items.length
                    ? '<ul class="mt-2 space-y-1 text-[0.74rem] text-slate-700">' + entry.items.map(function (item) {
                        var medicineName = item.medicine
                            ? [item.medicine.generic_name, item.medicine.brand_name ? '(' + item.medicine.brand_name + ')' : ''].filter(Boolean).join(' ').trim()
                            : (item.medicine_name || 'Medicine')
                        var line = medicineName
                        if (item.dosage) line += ' • ' + item.dosage
                        if (item.frequency) line += ' • ' + item.frequency
                        if (item.duration) line += ' • ' + item.duration
                        return '<li>' + escapeHtml(line) + '</li>'
                    }).join('') + '</ul>'
                    : '<div class="mt-2 text-[0.74rem] text-slate-400">No items recorded.</div>'
                return '' +
                    '<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 mb-3">' +
                        '<div class="flex items-start justify-between gap-3">' +
                            '<div>' +
                                '<div class="text-[0.72rem] font-semibold text-slate-800">' + escapeHtml(when) + '</div>' +
                                '<div class="mt-1 text-[0.72rem] text-slate-500">Prescribed by ' + escapeHtml(doctorName || 'Doctor') + '</div>' +
                            '</div>' +
                        '</div>' +
                        (entry.notes ? '<div class="mt-2 text-[0.74rem] text-slate-700"><span class="font-semibold">Notes:</span> ' + escapeHtml(entry.notes) + '</div>' : '') +
                        itemsHtml +
                    '</div>'
            }).join('')
        }

        function renderPatientSummary(visit, backgrounds, visits) {
            var patient = visit && visit.appointment ? visit.appointment.patient : null
            var patientName = patient
                ? [patient.firstname, patient.middlename, patient.lastname].filter(Boolean).join(' ').trim()
                : 'Patient'
            var birthdate = patient && patient.birthdate ? new Date(patient.birthdate) : null
            var age = ''
            if (birthdate && !isNaN(birthdate.getTime())) {
                var now = new Date()
                age = now.getFullYear() - birthdate.getFullYear()
                var monthDiff = now.getMonth() - birthdate.getMonth()
                if (monthDiff < 0 || (monthDiff === 0 && now.getDate() < birthdate.getDate())) age--
                age = age >= 0 ? String(age) + ' yrs' : ''
            }
            if (panelTitle) panelTitle.textContent = patientName || 'Patient details'
            if (panelSubtitle) panelSubtitle.textContent = 'Review patient information, medical background, and prescription records.'
            if (patientNameEl) patientNameEl.textContent = patientName || 'Patient'
            if (patientMetaEl) {
                var parts = []
                if (patient && patient.sex) parts.push(patient.sex)
                if (age) parts.push(age)
                parts.push(patient && patient.is_dependent ? 'Dependent' : 'Regular')
                if (visit && visit.appointment && visit.appointment.appointment_type) {
                    parts.push(String(visit.appointment.appointment_type).replace(/_/g, '-'))
                }
                patientMetaEl.textContent = parts.join(' • ') || '-'
            }
            if (patientContactsEl) {
                patientContactsEl.textContent = [patient && patient.contact_number ? patient.contact_number : '', patient && patient.email ? patient.email : '']
                    .filter(Boolean)
                    .join(' • ') || '-'
            }
            if (patientAddressEl) {
                patientAddressEl.textContent = patient && patient.address ? patient.address : '-'
            }
            renderMedicalBackground(backgrounds)
            renderVisitsHistory(visits)
            renderPrescriptionHistory(visits)
        }

        function loadVisitInformation(visitId, patientId) {
            setVisible(loadingBox, true)
            openPanel()
            Promise.all([
                api("{{ url('/api/visits') }}/" + encodeURIComponent(visitId)),
                api("{{ url('/api/medical-backgrounds') }}?patient_id=" + encodeURIComponent(patientId) + '&per_page=15'),
                api("{{ url('/api/visits') }}?patient_id=" + encodeURIComponent(patientId) + '&per_page=15'),
            ])
                .then(function (results) {
                    var visit = results[0]
                    var backgrounds = paginatedRows(results[1])
                    var visits = paginatedRows(results[2])
                    renderPatientSummary(visit, backgrounds, visits)
                    setTab(activeTab)
                })
                .catch(function (error) {
                    if (typeof showToast === 'function') showToast(error && error.message ? error.message : 'Failed to load patient information.', 'error')
                })
                .finally(function () {
                    setVisible(loadingBox, false)
                })
        }

        function localDateIso() {
            var now = new Date()
            var y = now.getFullYear()
            var m = String(now.getMonth() + 1).padStart(2, '0')
            var d = String(now.getDate()).padStart(2, '0')
            return y + '-' + m + '-' + d
        }

        function applyTodayToggleUi() {
            if (!todayToggle) return
            todayToggle.classList.toggle('bg-green-600', todayOnly)
            todayToggle.classList.toggle('text-white', todayOnly)
            todayToggle.classList.toggle('border-green-600', todayOnly)
            todayToggle.classList.toggle('hover:bg-green-700', todayOnly)
            todayToggle.classList.toggle('bg-white', !todayOnly)
            todayToggle.classList.toggle('text-slate-700', !todayOnly)
            todayToggle.classList.toggle('border-slate-200', !todayOnly)
            todayToggle.classList.toggle('hover:bg-slate-50', !todayOnly)
        }

        // ── API Load ─────────────────────────────────────────────────────
        function loadVisits(page) {
            if (!tbody) return
            tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400 animate-pulse">Loading…</td></tr>'

            apiFetch("{{ url('/api/visits') }}?per_page=" + visitPerPage + "&page=" + page + "&doctor_id=" + visitDoctorId)
                .then(function (r) { return r.json() })
                .then(function (result) {
                    if (!result || !result.data) {
                        tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No visits found.</td></tr>'
                        visitTotal = 0
                        visitLastPage = 1
                        renderVisitPagination()
                        return
                    }
                    var data = result.data
                    visitCurrentPage = result.current_page || page
                    visitLastPage = result.last_page || 1
                    visitTotal = result.total || 0

                    if (!data.length) {
                        tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No visits found.</td></tr>'
                    } else {
                        var html = ''
                        data.forEach(function (v) {
                            var appointment = v.appointment || {}
                            var patient = appointment.patient || {}
                            var parts = [patient.firstname, patient.middlename, patient.lastname].filter(function (x) { return x && String(x).trim() !== '' })
                            var patientName = parts.length ? parts.join(' ') : (patient.email || 'Patient')
                            var patientId = patient.patient_id || patient.user_id || 0
                            var dateKey = (v.visit_datetime || v.transaction_datetime || '').slice(0, 10)
                            var reason = appointment.reason_for_visit || '-'
                            var reasonDisplay = reason.length > 50 ? reason.slice(0, 50) + '…' : reason
                            var diagnosis = v.diagnosis || ''
                            var diagnosisDisplay = diagnosis.length > 80 ? diagnosis.slice(0, 80) + '…' : (diagnosis || '')

                            html += '<tr class="border-b border-slate-50 last:border-0 doctor-visit-row" ' +
                                'data-visit-id="' + escapeHtml(v.transaction_id) + '" ' +
                                'data-patient-id="' + escapeHtml(patientId) + '">' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">#' + escapeHtml(v.transaction_id) + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(patientName) + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(dateKey) + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(reasonDisplay) + '</td>' +
                                '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosis ? escapeHtml(diagnosisDisplay) : '<span class="text-[0.7rem] text-slate-400">No diagnosis recorded</span>') + '</td>' +
                                '<td class="py-2 pr-4 text-right">' +
                                (patientId > 0
                                    ? '<button type="button" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1 text-[0.72rem] font-medium text-slate-700 hover:bg-slate-50 doctor-visit-view">' +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M9 18l6-6-6-6"/></svg>' +
                                    ' View information</button>'
                                    : '<span class="text-[0.7rem] text-slate-400">-</span>') +
                                '</td></tr>'
                        })
                        tbody.innerHTML = html
                        // Re-attach view listeners
                        tbody.querySelectorAll('.doctor-visit-view').forEach(function (btn) {
                            btn.addEventListener('click', function () {
                                var row = btn.closest('.doctor-visit-row')
                                if (!row) return
                                var vid = row.getAttribute('data-visit-id')
                                var pid = row.getAttribute('data-patient-id')
                                if (!vid || !pid) return
                                loadVisitInformation(vid, pid)
                            })
                        })
                    }
                    applyClientFilter()
                    renderVisitPagination()
                })
                .catch(function () {
                    tbody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Failed to load visits.</td></tr>'
                    renderVisitPagination()
                })
        }

        // ── Pagination UI ────────────────────────────────────────────────
        function renderVisitPagination() {
            if (!pagEl) return
            var totalPages = visitLastPage
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'

            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + visitTotal + ' entries</span>'
            html += '<button type="button" class="' + (visitCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-visit-page="prev"' + (visitCurrentPage === 1 ? ' disabled' : '') + '>&lsaquo; Prev</button>'

            var ws = Math.max(1, visitCurrentPage - Math.floor(visitVisibleCount / 2))
            var we = Math.min(ws + visitVisibleCount - 1, totalPages)
            if (we - ws + 1 < visitVisibleCount) ws = Math.max(1, we - visitVisibleCount + 1)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === visitCurrentPage ? btnActive : btnInactive) + '" data-visit-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) {
                html += '<button type="button" class="' + btnInactive + '" data-visit-page="next-window" title="Next set">&hellip;</button>'
            }
            html += '<button type="button" class="' + (visitCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-visit-page="next"' + (visitCurrentPage === totalPages ? ' disabled' : '') + '>Next &rsaquo;</button>'

            pagEl.innerHTML = html
            pagEl.querySelectorAll('button[data-visit-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-visit-page')
                    if (p === 'prev' && visitCurrentPage > 1) { visitCurrentPage-- }
                    else if (p === 'next' && visitCurrentPage < totalPages) { visitCurrentPage++ }
                    else if (p === 'next-window') { visitCurrentPage = Math.min(we + 1, totalPages) }
                    else if (p !== 'prev' && p !== 'next') { visitCurrentPage = parseInt(p, 10) }
                    else return
                    loadVisits(visitCurrentPage)
                })
            })
        }

        // ── Client-side filter / sort on current page ────────────────────
        function applyClientFilter() {
            var query = searchInput ? searchInput.value.toLowerCase().trim() : ''
            var todayKey = localDateIso()
            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'))
            rows.forEach(function (row) {
                var text = (row.textContent || '').toLowerCase()
                var date = row.getAttribute('data-date') || ''
                var matches = query ? text.indexOf(query) !== -1 : true
                if (matches && todayOnly) matches = date === todayKey
                row.style.display = matches ? '' : 'none'
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

        // ── Init ─────────────────────────────────────────────────────────
        loadVisits(1)

        if (searchInput) searchInput.addEventListener('input', function () { applyClientFilter(); applyClientSort() })
        if (sortSelect) sortSelect.addEventListener('change', applyClientSort)
        if (todayToggle) {
            todayToggle.addEventListener('click', function () {
                todayOnly = !todayOnly
                applyTodayToggleUi()
                applyClientFilter()
            })
        }
        if (visitsTab) visitsTab.addEventListener('click', function () { setTab('visits') })
        if (prescriptionsTab) prescriptionsTab.addEventListener('click', function () { setTab('prescriptions') })
        if (closeButton) closeButton.addEventListener('click', closePanel)
        if (overlay) overlay.addEventListener('click', closePanel)

        setTab('visits')
        applyTodayToggleUi()

        // ── Refresh button ───────────────────────────────────────────────
        if (document.getElementById('docVisitRefreshBtn')) {
            document.getElementById('docVisitRefreshBtn').addEventListener('click', function () {
                visitCurrentPage = 1
                loadVisits(1)
            })
        }
    })
</script>
