<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-2">
        <h2 class="text-sm font-semibold text-slate-900"></h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Patients</span>
    </div>
    <p class="text-xs text-slate-500 mb-4">
       

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Pending</div>
            <div id="admin_verif_stat_pending" class="mt-1 text-xl font-semibold text-slate-900">-</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Approved</div>
            <div id="admin_verif_stat_approved" class="mt-1 text-xl font-semibold text-slate-900">-</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Rejected</div>
            <div id="admin_verif_stat_rejected" class="mt-1 text-xl font-semibold text-slate-900">-</div>
        </div>
    </div>

    <div id="adminVerifError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

    <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
        <div class="flex-1">
            <label for="admin_verif_search" class="block text-[0.7rem] text-slate-600 mb-1">Search</label>
            <input id="admin_verif_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Patient name, email, or verification ID">
        </div>
        <div class="w-full md:w-44">
            <label for="admin_verif_status_filter" class="block text-[0.7rem] text-slate-600 mb-1">Status</label>
            <select id="admin_verif_status_filter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="w-full md:w-44">
            <label for="admin_verif_type_filter" class="block text-[0.7rem] text-slate-600 mb-1">Type</label>
            <select id="admin_verif_type_filter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All</option>
                <option value="none">None</option>
                <option value="senior">Senior</option>
                <option value="pwd">PWD</option>
                <option value="pregnant">Pregnant</option>
            </select>
        </div>
        <div class="w-full md:w-28 pt-1">
            <button type="button" id="adminVerifRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                Refresh
            </button>
        </div>
        <div class="w-full md:w-44">
            <label for="admin_verif_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
            <select id="admin_verif_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="date_desc">Newest first</option>
                <option value="date_asc">Oldest first</option>
            </select>
        </div>
    </div>

<div class="overflow-x-auto overflow-y-auto scrollbar-hidden mb-4 h-[460px]">
        <table class="min-w-full w-full table-fixed text-left text-xs text-slate-600">
            <thead>
                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                    <th class="w-[28%] py-2 pr-4 font-semibold">Patient</th>
                    <th class="w-[12%] py-2 pr-4 font-semibold">Type</th>
                    <th class="w-[14%] py-2 pr-4 font-semibold">Status</th>
                    <th class="w-[12%] py-2 pr-4 font-semibold">Uploaded</th>
                    <th class="w-[18%] py-2 pr-4 font-semibold">Verified/Rejected by</th>
                    <th class="w-[16%] py-2 pr-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody id="admin_verif_table_body">
                <tr>
                    <td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">
                        Loading verifications…
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="adminVerifPagination" class="flex items-center justify-center gap-3 pt-2 pb-1"></div>

</div>

<div id="adminVerifDocPanelOverlay" class="hidden fixed inset-0 z-[80] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl h-[90vh] max-h-none rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex overflow-hidden">
        <!-- History list (left) -->
        <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Verification History</div>
                    <div id="adminVerifDocPanelSubtitle" class="text-[0.72rem] text-slate-500">Loading...</div>
                </div>
                <button type="button" id="adminVerifDocPanelClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div id="adminVerifHistoryBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading verification history...</div>
            </div>
        </div>
        <!-- Detail panel (right) -->
        <div class="w-1/2 flex flex-col min-h-0 bg-slate-50/50">
            <div class="px-4 py-3 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div class="text-sm font-semibold text-slate-900">Patient Details</div>
            </div>
            <div class="shrink-0 px-4 py-2 border-b border-slate-200 bg-white flex items-center gap-2">
                <button type="button" id="adminVerifApproveBtn" class="flex-1 px-3 py-2 rounded-xl border border-emerald-200 bg-emerald-50 text-[0.78rem] font-semibold text-emerald-700 text-center hover:bg-emerald-100 disabled:opacity-50 disabled:cursor-not-allowed">Approve</button>
                <button type="button" id="adminVerifRejectBtn" class="flex-1 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50 text-[0.78rem] font-semibold text-rose-700 text-center hover:bg-rose-100 disabled:opacity-50 disabled:cursor-not-allowed">Reject</button>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <div id="adminVerifPatientCard" class="rounded-xl border border-slate-200 bg-white p-3 mb-4">
                    <div class="text-[0.78rem] text-slate-500">Select a verification record.</div>
                </div>
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Latest Uploaded Document</div>
                <div id="adminVerifDocMainWrap" class="rounded-xl border border-slate-200 bg-white overflow-hidden h-[16rem] flex items-center justify-center text-[0.78rem] text-slate-500 transition cursor-default">
                    Select a verification record.
                </div>
                <div class="mt-3 flex items-center justify-between gap-3">
                    <div id="adminVerifDocHint" class="text-[0.72rem] text-slate-500">Image previews can be opened fullscreen for closer inspection.</div>
                    <button type="button" id="adminVerifOverrideBtn" class="px-2.5 py-1 rounded-lg border border-amber-200 bg-amber-50 text-[0.72rem] font-semibold text-amber-700 text-center hover:bg-amber-100 shrink-0">Override status</button>
                </div>
            </div>
        </div>
    </div>

    <div id="adminVerifImageViewer" class="hidden fixed inset-0 z-[95] bg-slate-950/90 p-4 md:p-6">
        <div class="flex h-full flex-col">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Document viewer</div>
                    <div id="adminVerifImageViewerTitle" class="truncate text-sm font-semibold text-white">Verification document</div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="adminVerifZoomOut" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/15 bg-white/10 text-white hover:bg-white/20">
                        <x-lucide-minus class="w-[16px] h-[16px]" />
                    </button>
                    <button type="button" id="adminVerifZoomReset" class="rounded-xl border border-white/15 bg-white/10 px-3 py-2 text-[0.78rem] font-semibold text-white hover:bg-white/20">
                        100%
                    </button>
                    <button type="button" id="adminVerifZoomIn" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/15 bg-white/10 text-white hover:bg-white/20">
                        <x-lucide-plus class="w-[16px] h-[16px]" />
                    </button>
                    <button type="button" id="adminVerifImageViewerClose" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/15 bg-white/10 text-white hover:bg-white/20">
                        <x-lucide-x class="w-[16px] h-[16px]" />
                    </button>
                </div>
            </div>
            <div id="adminVerifImageViewerStage" class="relative flex-1 overflow-hidden rounded-2xl border border-white/10 bg-slate-900/80">
                <img id="adminVerifImageViewerImg" src="" alt="Verification document" class="absolute left-1/2 top-1/2 max-h-none max-w-none select-none" style="transform: translate(-50%, -50%) translate(0px, 0px) scale(1); transform-origin: center center;" draggable="false">
            </div>
        </div>
    </div>
</div>

<div id="adminVerifActionOverlay" class="hidden fixed inset-0 z-[90] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center text-green-700">
                <x-lucide-shield-check class="w-[18px] h-[18px]" />
            </div>
            <div class="flex-1 min-w-0">
                <div id="adminVerifActionTitle" class="text-sm font-semibold text-slate-900">Confirm Action</div>
                <div id="adminVerifActionMessage" class="text-[0.78rem] text-slate-600 mt-0.5"></div>
            </div>
        </div>
        <div id="adminVerifActionDetails" class="mt-3 text-[0.78rem] text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2"></div>
        <div id="adminVerifActionDocWrap" class="mt-3 rounded-lg border border-slate-200 bg-slate-50 overflow-hidden h-32 flex items-center justify-center text-[0.74rem] text-slate-500">
            No document preview.
        </div>
        <div id="adminVerifRejectReasonWrap" class="hidden mt-3">
            <label for="adminVerifRejectReason" class="block text-[0.7rem] text-slate-600 mb-1">Reject reason</label>
            <select id="adminVerifRejectReason" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="Blurry document">Blurry Document</option>
                <option value="Invalid Document">Invalid Document</option>
                <option value="Expired Document">Expired Document</option>
                <option value="Mismatch Information">Mismatch Information</option>
                <option value="Cropped Document">Cropped Document</option>
            </select>
        </div>
        <div id="adminVerifOverrideStatusWrap" class="hidden mt-3">
            <label for="adminVerifOverrideStatus" class="block text-[0.7rem] text-slate-600 mb-1">New status</label>
            <select id="adminVerifOverrideStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="mt-4 flex items-center justify-end gap-2">
            <button id="adminVerifActionCancel" type="button" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
            <button id="adminVerifActionConfirm" type="button" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Confirm</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var errorBox = document.getElementById('adminVerifError')
        var statPending = document.getElementById('admin_verif_stat_pending')
        var statApproved = document.getElementById('admin_verif_stat_approved')
        var statRejected = document.getElementById('admin_verif_stat_rejected')

        var searchInput = document.getElementById('admin_verif_search')
        var statusFilter = document.getElementById('admin_verif_status_filter')
        var typeFilter = document.getElementById('admin_verif_type_filter')
        var sortSelect = document.getElementById('admin_verif_sort')
        var tableBody = document.getElementById('admin_verif_table_body')

        var docPanelOverlay = document.getElementById('adminVerifDocPanelOverlay')
        var docPanelClose = document.getElementById('adminVerifDocPanelClose')
        var docPanelSubtitle = document.getElementById('adminVerifDocPanelSubtitle')
        var patientCard = document.getElementById('adminVerifPatientCard')
        var docMainWrap = document.getElementById('adminVerifDocMainWrap')
        var docHint = document.getElementById('adminVerifDocHint')
        var historyBody = document.getElementById('adminVerifHistoryBody')

        var actionOverlay = document.getElementById('adminVerifActionOverlay')
        var actionTitle = document.getElementById('adminVerifActionTitle')
        var actionMessage = document.getElementById('adminVerifActionMessage')
        var actionDetails = document.getElementById('adminVerifActionDetails')
        var actionDocWrap = document.getElementById('adminVerifActionDocWrap')
        var actionCancel = document.getElementById('adminVerifActionCancel')
        var actionConfirm = document.getElementById('adminVerifActionConfirm')
        var rejectReasonWrap = document.getElementById('adminVerifRejectReasonWrap')
        var rejectReasonSelect = document.getElementById('adminVerifRejectReason')

        var currentPage = 1
        var lastPayload = null

        var panelApproveBtn = document.getElementById('adminVerifApproveBtn')
        var panelRejectBtn = document.getElementById('adminVerifRejectBtn')
        var currentPanelVerif = null
        var currentHistoryRows = []
        var actionResolver = null

        function updatePanelActionButtons(status) {
            var isFinal = status === 'approved' || status === 'rejected'
            if (panelApproveBtn) { panelApproveBtn.disabled = isFinal }
            if (panelRejectBtn) { panelRejectBtn.disabled = isFinal }
        }
        var actionDelayTimer = null
        var actionCountdownTimer = null
        var actionConfirmDefaultHtml = actionConfirm ? actionConfirm.innerHTML : ''
        var overrideStatusWrap = document.getElementById('adminVerifOverrideStatusWrap')
        var overrideStatusSelect = document.getElementById('adminVerifOverrideStatus')
        var overrideBtn = document.getElementById('adminVerifOverrideBtn')
        var imageViewer = document.getElementById('adminVerifImageViewer')
        var imageViewerTitle = document.getElementById('adminVerifImageViewerTitle')
        var imageViewerStage = document.getElementById('adminVerifImageViewerStage')
        var imageViewerImg = document.getElementById('adminVerifImageViewerImg')
        var imageViewerClose = document.getElementById('adminVerifImageViewerClose')
        var zoomInButton = document.getElementById('adminVerifZoomIn')
        var zoomOutButton = document.getElementById('adminVerifZoomOut')
        var zoomResetButton = document.getElementById('adminVerifZoomReset')
        var imageViewerState = {
            scale: 1,
            offsetX: 0,
            offsetY: 0,
            dragging: false,
            startX: 0,
            startY: 0
        }

        function showError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function statusBadge(status) {
            var key = String(status || '').toLowerCase()
            var map = {
                pending: 'bg-amber-50 text-amber-700 border-amber-100',
                approved: 'bg-emerald-50 text-emerald-700 border-emerald-100',
                rejected: 'bg-rose-50 text-rose-700 border-rose-100'
            }
            var cls = map[key] || 'bg-slate-50 text-slate-600 border-slate-100'
            var label = key ? (key.charAt(0).toUpperCase() + key.slice(1)) : 'Unknown'
            return '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.68rem] font-medium border ' + cls + '">' + label + '</span>'
        }

        function escapeHtml(text) {
            return String(text || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function getPatientLabel(v) {
            var p = v && v.patient ? v.patient : null
            if (!p) return 'Unknown'
            var name = ((p.firstname || '') + ' ' + (p.middlename || '') + ' ' + (p.lastname || '')).trim().replace(/\s+/g, ' ')
            if (name) return name
            if (p.email) return p.email
            return 'Patient #' + p.user_id
        }

        function getVerifierLabel(v) {
            var u = v && v.verifier ? v.verifier : null
            if (!u) return '-'
            var name = ((u.firstname || '') + ' ' + (u.lastname || '')).trim()
            if (name) return name
            if (u.email) return u.email
            return 'User #' + u.user_id
        }

        function normalizeDateValue(value) {
            if (!value) return null
            var text = String(value).trim()
            if (!text) return null
            var match = text.match(/^(\d{4})-(\d{2})-(\d{2})/)
            if (match) {
                var parsed = new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]))
                return isNaN(parsed.getTime()) ? null : parsed
            }
            var date = new Date(text)
            return isNaN(date.getTime()) ? null : date
        }

        function padNumber(value) {
            return String(value).padStart(2, '0')
        }

        function formatBirthdateMain(value) {
            var date = normalizeDateValue(value)
            if (!date) return '-'
            return padNumber(date.getMonth() + 1) + '-' + padNumber(date.getDate()) + '-' + date.getFullYear()
        }

        function formatBirthdateDayFirst(value) {
            var date = normalizeDateValue(value)
            if (!date) return '-'
            return padNumber(date.getDate()) + '-' + padNumber(date.getMonth() + 1) + '-' + date.getFullYear()
        }

        function calculateAge(value) {
            var date = normalizeDateValue(value)
            if (!date) return null
            var today = new Date()
            var age = today.getFullYear() - date.getFullYear()
            var monthDiff = today.getMonth() - date.getMonth()
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < date.getDate())) {
                age = age - 1
            }
            return age < 0 ? null : age
        }

        function formatBirthdateSubtitle(value) {
            var date = normalizeDateValue(value)
            if (!date) return 'No birthdate submitted.'
            var formatted = date.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            })
            var age = calculateAge(value)
            return age === null ? formatted : formatted + ' - Age ' + age
        }

        function patientFieldValue(value, fallback) {
            var text = String(value || '').trim()
            return text || (fallback || '-')
        }

        function renderPatientSummary(verification) {
            if (!patientCard) return
            var patient = verification && verification.patient ? verification.patient : null
            if (!patient) {
                patientCard.innerHTML = '<div class="text-[0.78rem] text-slate-500">Patient details are unavailable for this verification.</div>'
                return
            }

            patientCard.innerHTML =
                '<div class="space-y-2 text-[0.8rem] text-slate-700">' +
                    '<div><span class="font-semibold text-slate-900">Name:</span> ' + escapeHtml(getPatientLabel(verification)) + '</div>' +
                    '<div><span class="font-semibold text-slate-900">Date of Birth:</span> ' + escapeHtml(formatBirthdateMain(patient.birthdate)) + '</div>' +
                    '<div class="text-[0.72rem] text-slate-500">' + escapeHtml(formatBirthdateSubtitle(patient.birthdate)) + '</div>' +
                    '<div><span class="font-semibold text-slate-900">Sex:</span> ' + escapeHtml(patientFieldValue(patient.sex)) + '</div>' +
                    '<div><span class="font-semibold text-slate-900">Address:</span> <span class="break-words">' + escapeHtml(patientFieldValue(patient.address, 'No address submitted.')) + '</span></div>' +
                '</div>'
        }

        function loadStats() {
            apiFetch("{{ url('/api/patient-verifications-stats') }}", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    })
                })
                .then(function (result) {
                    if (!result.ok) return
                    if (statPending) statPending.textContent = String(result.data.pending ?? '0')
                    if (statApproved) statApproved.textContent = String(result.data.approved ?? '0')
                    if (statRejected) statRejected.textContent = String(result.data.rejected ?? '0')
                })
                .catch(function () {})
        }

        function buildQuery(page) {
            var params = []
            params.push('per_page=10')
            params.push('page=' + encodeURIComponent(page || 1))

            var status = statusFilter ? statusFilter.value : ''
            if (status) params.push('status=' + encodeURIComponent(status))

            var type = typeFilter ? typeFilter.value : ''
            if (type) params.push('type=' + encodeURIComponent(type))

            var query = searchInput ? searchInput.value.trim() : ''
            if (query) params.push('search=' + encodeURIComponent(query))

            var sort = sortSelect ? sortSelect.value : 'date_desc'
            params.push('sort=' + encodeURIComponent(sort))

            return params.join('&')
        }

        function loadVerifications(page) {
            currentPage = page || 1
            showError('')
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Loading verifications…</td></tr>'
            }
            var pag = document.getElementById('adminVerifPagination')
            if (pag) pag.innerHTML = ''

            apiFetch("{{ url('/api/patient-verifications') }}?" + buildQuery(currentPage), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        showError('Failed to load verifications.')
                        if (tableBody) tableBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No data.</td></tr>'
                        var pag = document.getElementById('adminVerifPagination')
                        if (pag) pag.innerHTML = ''
                        return
                    }
                    lastPayload = result.data
                    renderVerifications()
                })
                .catch(function () {
                    showError('Network error while loading verifications.')
                    if (tableBody) tableBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No data.</td></tr>'
                    var pag = document.getElementById('adminVerifPagination')
                    if (pag) pag.innerHTML = ''
                })
        }

        function renderVerifications() {
            if (!tableBody) return
            var payload = lastPayload || {}
            var items = Array.isArray(payload.data) ? payload.data : []

            // Sort by datetime descending, keep only 1 per patient (most recent)
            items.sort(function (a, b) {
                var da = String(a && a.created_at ? a.created_at : '')
                var db = String(b && b.created_at ? b.created_at : '')
                if (da < db) return 1
                if (da > db) return -1
                return 0
            })
            var seenPatient = {}
            items = items.filter(function (v) {
                var pid = v && (v.patient_id || (v.patient && v.patient.user_id))
                if (!pid) return true
                if (seenPatient[pid]) return false
                seenPatient[pid] = true
                return true
            })

            if (!items.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No verifications found.</td></tr>'
                var pag = document.getElementById('adminVerifPagination')
                if (pag) pag.innerHTML = ''
                return
            }

            var html = ''
            items.forEach(function (v) {
                var id = v.verification_id
                var patientLabel = escapeHtml(getPatientLabel(v))
                var type = escapeHtml(v.type ? String(v.type).toUpperCase() : '-')
                var status = v.status || ''
                var uploaded = v.created_at ? escapeHtml(String(v.created_at).slice(0, 10)) : '-'
                var verifier = escapeHtml(getVerifierLabel(v))
                var hasDoc = !!v.document_path

                html += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + patientLabel + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + type + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' + statusBadge(status) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + uploaded + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + verifier + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' +
                        '<div class="flex items-center gap-2 flex-nowrap min-w-[19rem]">' +
                            (hasDoc
                                ? '<button type="button" class="w-28 shrink-0 px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.72rem] font-semibold text-slate-700 text-center hover:bg-slate-50 admin-verif-doc" data-id="' + id + '">View document</button>'
                                : '<button type="button" disabled class="w-28 shrink-0 px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-100 text-[0.72rem] font-semibold text-slate-400 text-center cursor-not-allowed">No document</button>') +
                        '</div>' +
                    '</td>' +
                '</tr>'
            })

            tableBody.innerHTML = html
            bindRowActions()
            renderServerPagination(payload)
        }

        // ── Server-side pagination ──
        function renderServerPagination(payload) {
            var pagination = document.getElementById('adminVerifPagination')
            if (!pagination) return
            var total = payload.total || 0
            var lastPage = payload.last_page || 1
            var current = payload.current_page || 1

            if (total === 0) {
                pagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                return
            }

            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border ';
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer';
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default';
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600';
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
            html += '<button type="button" class="' + (current === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (current === 1 ? ' disabled' : '') + '>‹ Prev</button>'

            var windowStart = current
            var windowEnd = Math.min(windowStart + 5, lastPage)
            for (var i = windowStart; i <= windowEnd; i++) {
                html += '<button type="button" class="' + (i === current ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
            }
            if (windowEnd < lastPage) {
                html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>'
            }
            html += '<button type="button" class="' + (current === lastPage ? btnDisabled : btnInactive) + '" data-page="next"' + (current === lastPage ? ' disabled' : '') + '>Next ›</button>'

            pagination.innerHTML = html

            pagination.querySelectorAll('button[data-page]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var p = btn.getAttribute('data-page')
                    var goTo
                    if (p === 'prev' && current > 1) goTo = current - 1
                    else if (p === 'next' && current < lastPage) goTo = current + 1
                    else if (p === 'next-window') goTo = Math.min(windowEnd + 1, lastPage)
                    else if (p !== 'prev' && p !== 'next') goTo = parseInt(p, 10)
                    if (goTo) loadVerifications(goTo)
                })
            })
        }

        function statusText(value) {
            var key = String(value || '').toLowerCase()
            if (!key) return 'Unknown'
            return key.charAt(0).toUpperCase() + key.slice(1)
        }

        function documentUrl(verificationId) {
            return "{{ url('/api/patient-verifications') }}/" + encodeURIComponent(String(verificationId)) + "/document"
        }

        function directDocumentUrl(verification) {
            if (!verification) return ''
            if (verification.document_url) return String(verification.document_url)
            if (verification.verification_id) return documentUrl(verification.verification_id)
            return ''
        }

        function revokePreviewUrl(target) {
            if (!target) return
            var previousUrl = target.getAttribute('data-object-url')
            if (previousUrl && window.URL && typeof window.URL.revokeObjectURL === 'function') {
                window.URL.revokeObjectURL(previousUrl)
            }
            target.removeAttribute('data-object-url')
        }

        function documentExtension(path) {
            var value = String(path || '')
            var lastDot = value.lastIndexOf('.')
            if (lastDot === -1) return ''
            return value.slice(lastDot + 1).toLowerCase()
        }

        function isImageDocument(contentType, extension) {
            if (String(contentType || '').indexOf('image/') === 0) return true
            return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].indexOf(String(extension || '').toLowerCase()) !== -1
        }

        function isPdfDocument(contentType, extension) {
            return String(contentType || '').indexOf('pdf') !== -1 || String(extension || '').toLowerCase() === 'pdf'
        }

        function guessedContentType(extension) {
            var ext = String(extension || '').toLowerCase()
            if (['jpg', 'jpeg'].indexOf(ext) !== -1) return 'image/jpeg'
            if (ext === 'png') return 'image/png'
            if (ext === 'gif') return 'image/gif'
            if (ext === 'webp') return 'image/webp'
            if (ext === 'bmp') return 'image/bmp'
            if (ext === 'pdf') return 'application/pdf'
            return ''
        }

        function setPreviewHtml(target, html) {
            if (!target) return
            revokePreviewUrl(target)
            target.removeAttribute('data-preview-image-url')
            target.removeAttribute('data-preview-title')
            target.classList.remove('cursor-zoom-in', 'hover:border-green-300')
            target.classList.add('cursor-default')
            target.innerHTML = html
        }

        function fetchDocumentAsset(verification) {
            if (!verification || !verification.document_path) {
                return Promise.reject(new Error('No document available.'))
            }

            var extension = documentExtension(verification.document_path)
            var fallbackUrl = directDocumentUrl(verification)
            var fallbackAsset = fallbackUrl
                ? {
                    url: fallbackUrl,
                    contentType: guessedContentType(extension),
                    extension: extension
                }
                : null

            return apiFetch(documentUrl(verification.verification_id), { method: 'GET' })
                .then(function (response) {
                    if (!response.ok) {
                        if (fallbackAsset) return fallbackAsset
                        throw new Error('Failed to load document.')
                    }
                    var contentType = response.headers.get('Content-Type') || ''
                    return response.blob().then(function (blob) {
                        if (!window.URL || typeof window.URL.createObjectURL !== 'function' || !blob || !blob.size) {
                            if (fallbackAsset) return fallbackAsset
                            throw new Error('Preview is not supported in this browser.')
                        }
                        return {
                            url: window.URL.createObjectURL(blob),
                            contentType: contentType || blob.type || '',
                            extension: extension
                        }
                    })
                })
                .catch(function () {
                    if (fallbackAsset) return fallbackAsset
                    throw new Error('Failed to load document.')
                })
        }

        function setZoomablePreview(target, asset, title, enabled) {
            if (!target) return
            target.removeAttribute('data-preview-image-url')
            target.removeAttribute('data-preview-title')
            target.classList.remove('cursor-zoom-in', 'hover:border-green-300')
            target.classList.add('cursor-default')

            if (!enabled || !asset || !asset.url) {
                if (target === docMainWrap) {
                    if (docHint) docHint.textContent = 'Image previews can be opened fullscreen for closer inspection.'
                }
                return
            }

            target.setAttribute('data-preview-image-url', asset.url)
            target.setAttribute('data-preview-title', title || 'Verification document')
            target.classList.remove('cursor-default')
            target.classList.add('cursor-zoom-in', 'hover:border-green-300')
            if (target === docMainWrap) {
                if (docHint) docHint.textContent = 'Click the image preview to inspect it fullscreen and zoom in.'
            }
        }

        function renderDocumentAsset(target, asset, title, options) {
            if (!target || !asset) return
            options = options || {}
            revokePreviewUrl(target)
            if (String(asset.url || '').indexOf('blob:') === 0) {
                target.setAttribute('data-object-url', asset.url)
            } else {
                target.removeAttribute('data-object-url')
            }

            if (isImageDocument(asset.contentType, asset.extension)) {
                setZoomablePreview(target, asset, title, !!options.zoomable)
                target.innerHTML =
                    '<div class="relative h-full w-full bg-white">' +
                        '<img src="' + escapeHtml(asset.url) + '" alt="' + escapeHtml(title || 'Verification document') + '" class="h-full w-full object-contain bg-white">' +
                        (options.zoomable
                            ? '<div class="pointer-events-none absolute bottom-3 right-3 rounded-full bg-slate-900/75 px-3 py-1 text-[0.68rem] font-semibold text-white shadow-lg">Click to inspect</div>'
                            : '') +
                    '</div>'
                return
            }

            setZoomablePreview(target, null, '', false)
            if (isPdfDocument(asset.contentType, asset.extension)) {
                target.innerHTML = '<iframe src="' + escapeHtml(asset.url) + '" class="w-full h-full bg-white" title="' + escapeHtml(title || 'Verification document preview') + '"></iframe>'
                return
            }

            target.innerHTML =
                '<div class="px-4 text-center">' +
                    '<div class="text-[0.78rem] text-slate-500 mb-3">Preview is not available for this file type.</div>' +
                    '<a href="' + escapeHtml(asset.url) + '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.74rem] font-semibold text-slate-700 hover:bg-slate-50">Open document</a>' +
                '</div>'
        }

        function loadDocumentPreview(target, verification, emptyMessage, loadingMessage, title, options) {
            if (!target) return Promise.resolve()
            options = options || {}
            if (!verification || !verification.document_path) {
                setPreviewHtml(target, '<div class="text-[0.78rem] text-slate-500">' + escapeHtml(emptyMessage || 'No document uploaded.') + '</div>')
                return Promise.resolve()
            }

            setPreviewHtml(
                target,
                '<div class="inline-flex items-center gap-2 text-[0.78rem] text-slate-500">' +
                    '<span class="w-4 h-4 border-2 border-slate-300 border-t-green-600 rounded-full animate-spin"></span>' +
                    '<span>' + escapeHtml(loadingMessage || 'Loading document...') + '</span>' +
                '</div>'
            )

            return fetchDocumentAsset(verification)
                .then(function (asset) {
                    renderDocumentAsset(target, asset, title, options)
                })
                .catch(function () {
                    setPreviewHtml(target, '<div class="px-4 text-center text-[0.78rem] text-slate-500">Unable to load the document preview.</div>')
                })
        }

        function resetImageViewerTransform() {
            imageViewerState.scale = 1
            imageViewerState.offsetX = 0
            imageViewerState.offsetY = 0
            if (zoomResetButton) zoomResetButton.textContent = '100%'
            if (imageViewerImg) {
                imageViewerImg.style.transform =
                    'translate(-50%, -50%) translate(' + imageViewerState.offsetX + 'px, ' + imageViewerState.offsetY + 'px) scale(' + imageViewerState.scale + ')'
            }
        }

        function setImageViewerScale(nextScale) {
            var clamped = Math.max(1, Math.min(6, nextScale))
            imageViewerState.scale = clamped
            if (clamped === 1) {
                imageViewerState.offsetX = 0
                imageViewerState.offsetY = 0
            }
            if (zoomResetButton) zoomResetButton.textContent = Math.round(clamped * 100) + '%'
            if (imageViewerImg) {
                imageViewerImg.style.transform =
                    'translate(-50%, -50%) translate(' + imageViewerState.offsetX + 'px, ' + imageViewerState.offsetY + 'px) scale(' + clamped + ')'
            }
        }

        function closeImageViewer() {
            imageViewerState.dragging = false
            if (imageViewer) imageViewer.classList.add('hidden')
            if (imageViewerImg) {
                imageViewerImg.removeAttribute('src')
                imageViewerImg.style.transform = 'translate(-50%, -50%) translate(0px, 0px) scale(1)'
            }
            if (imageViewerTitle) imageViewerTitle.textContent = 'Verification document'
            resetImageViewerTransform()
        }

        function openImageViewer(url, title) {
            if (!imageViewer || !imageViewerImg || !url) return
            if (imageViewerTitle) imageViewerTitle.textContent = title || 'Verification document'
            imageViewer.classList.remove('hidden')
            imageViewerImg.setAttribute('src', url)
            resetImageViewerTransform()
        }

        function openDocPanel() {
            if (!docPanelOverlay) return
            docPanelOverlay.classList.remove('hidden')
            docPanelOverlay.classList.add('flex')
        }

        function closeDocPanel() {
            if (!docPanelOverlay) return
            closeImageViewer()
            docPanelOverlay.classList.add('hidden')
            docPanelOverlay.classList.remove('flex')
            if (patientCard) {
                patientCard.innerHTML = '<div class="text-[0.78rem] text-slate-500">Select a verification record.</div>'
            }
            if (docMainWrap) {
                docMainWrap.innerHTML = 'Select a verification record.'
            }
            if (historyBody) {
                historyBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No history loaded.</div>'
            }
            if (docPanelSubtitle) docPanelSubtitle.textContent = 'Loading...'
            currentHistoryRows = []
            if (docHint) docHint.textContent = 'Image previews can be opened fullscreen for closer inspection.'
        }

        function setMainDocumentPreview(v) {
            return loadDocumentPreview(
                docMainWrap,
                v,
                'No document uploaded for this verification.',
                'Loading document...',
                'Verification document preview',
                { zoomable: true }
            )
        }

        function renderHistory(rows) {
            if (!historyBody) return
            var items = Array.isArray(rows) ? rows : []
            currentHistoryRows = items
            if (!items.length) {
                historyBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No verification history found.</div>'
                return
            }
            historyBody.innerHTML = items.map(function (entry) {
                var uploaded = entry && entry.created_at ? String(entry.created_at).slice(0, 10) : '-'
                var stText = statusText(entry.status)
                var stColor = ''
                var stBg = ''
                if (String(entry.status || '').toLowerCase() === 'approved') {
                    stColor = 'text-emerald-700'
                    stBg = 'bg-emerald-50 border-emerald-100'
                } else if (String(entry.status || '').toLowerCase() === 'rejected') {
                    stColor = 'text-rose-700'
                    stBg = 'bg-rose-50 border-rose-100'
                } else {
                    stColor = 'text-amber-700'
                    stBg = 'bg-amber-50 border-amber-100'
                }
                var remarks = entry && entry.remarks ? String(entry.remarks) : ''
                var id = entry && entry.verification_id ? String(entry.verification_id) : ''
                return '<div class="rounded-xl border border-slate-200 bg-white p-3 hover:border-green-200 transition-colors cursor-pointer admin-verif-history-card" data-id="' + escapeHtml(id) + '">' +
                    '<div class="flex items-center justify-between mb-1">' +
                        '<span class="text-[0.78rem] font-semibold text-slate-800">' + escapeHtml(entry.type || '-') + '</span>' +
                        '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.68rem] font-medium border ' + stBg + ' ' + stColor + '">' + escapeHtml(stText) + '</span>' +
                    '</div>' +
                    '<div class="text-[0.72rem] text-slate-500 mb-1">' + (remarks ? escapeHtml(remarks) : '<span class="text-slate-400">No remarks</span>') + '</div>' +
                    '<div class="text-[0.68rem] text-slate-400">Uploaded: ' + escapeHtml(uploaded) + '</div>' +
                '</div>'
            }).join('')
            // Bind click handlers for history cards
            historyBody.querySelectorAll('.admin-verif-history-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    var id = this.getAttribute('data-id')
                    var found = currentHistoryRows.find(function (r) {
                        return String(r.verification_id) === String(id) || String(r.id) === String(id)
                    })
                    if (found) {
                        renderPatientSummary(found)
                        setMainDocumentPreview(found)
                        currentPanelVerif = found
                        updatePanelActionButtons(String(found.status || '').toLowerCase())
                        if (docPanelSubtitle) docPanelSubtitle.textContent = getPatientLabel(found) + ' | ' + statusText(found.status) + ' | ' + (found.type || '-')
                    }
                })
            })
        }

        function openDocumentPanelFor(verification) {
            if (!verification) return
            currentPanelVerif = verification
            if (docPanelSubtitle) docPanelSubtitle.textContent = getPatientLabel(verification) + ' | ' + statusText(verification.status) + ' | ' + (verification.type || '-')
            renderPatientSummary(verification)
            updatePanelActionButtons(String(verification.status || '').toLowerCase())
            closeImageViewer()
            setMainDocumentPreview(verification)
            openDocPanel()

            if (historyBody) {
                historyBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading verification history...</div>'
            }
            var patientId = verification && verification.patient_id ? verification.patient_id : 0
            if (!patientId) {
                renderHistory([])
                return
            }

            apiFetch("{{ url('/api/patient-verifications') }}?per_page=15&patient_id=" + encodeURIComponent(String(patientId)), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        renderHistory([])
                        return
                    }
                    var rows = Array.isArray(result.data.data) ? result.data.data : []
                    rows.sort(function (a, b) {
                        var da = String(a && a.created_at ? a.created_at : '')
                        var db = String(b && b.created_at ? b.created_at : '')
                        if (da < db) return 1
                        if (da > db) return -1
                        return 0
                    })
                    renderHistory(rows)
                })
                .catch(function () {
                    renderHistory([])
                })
        }

        function closeActionModal(result) {
            if (actionOverlay) {
                actionOverlay.classList.add('hidden')
                actionOverlay.classList.remove('flex')
            }
            if (actionDelayTimer) {
                clearTimeout(actionDelayTimer)
                actionDelayTimer = null
            }
            if (actionCountdownTimer) {
                clearInterval(actionCountdownTimer)
                actionCountdownTimer = null
            }
            if (actionConfirm) {
                actionConfirm.disabled = false
                actionConfirm.innerHTML = actionConfirmDefaultHtml || 'Confirm'
            }
            if (rejectReasonWrap) rejectReasonWrap.classList.add('hidden')
            if (overrideStatusWrap) overrideStatusWrap.classList.add('hidden')
            setPreviewHtml(actionDocWrap, '<div class="text-[0.74rem] text-slate-500">No document preview.</div>')
            var resolver = actionResolver
            actionResolver = null
            if (typeof resolver === 'function') resolver(result || null)
        }

        function openActionModal(status, verification) {
            return new Promise(function (resolve) {
                if (!actionOverlay || !actionTitle || !actionMessage || !actionDetails || !actionConfirm || !actionCancel) {
                    resolve(null)
                    return
                }
                var isReject = status === 'rejected'
                var patient = verification && verification.patient ? verification.patient : null
                actionDetails.innerHTML = '<ul class="space-y-1">' +
                    // '<li><strong class="font-semibold text-slate-800">Verification ID:</strong> #' + escapeHtml(verification.verification_id) + '</li>' +
                    '<li><strong class="font-semibold text-slate-800">Patient:</strong> ' + escapeHtml(getPatientLabel(verification)) + '</li>' +
                    '<li><strong class="font-semibold text-slate-800">DOB:</strong> ' + escapeHtml(formatBirthdateDayFirst(patient ? patient.birthdate : null)) + '</li>' +
                    '<li><strong class="font-semibold text-slate-800">Sex:</strong> ' + escapeHtml(patientFieldValue(patient ? patient.sex : '', '-')) + '</li>' +
                    '<li><strong class="font-semibold text-slate-800">Address:</strong> ' + escapeHtml(patientFieldValue(patient ? patient.address : '', 'No address submitted.')) + '</li>' +
                    '<li><strong class="font-semibold text-slate-800">Type:</strong> ' + escapeHtml(verification.type || '-') + '</li>' +
                    '<li><strong class="font-semibold text-slate-800">Current Status:</strong> ' + escapeHtml(statusText(verification.status)) + '</li>' +
                '</ul>'

                void loadDocumentPreview(
                    actionDocWrap,
                    verification,
                    'No document preview.',
                    'Loading preview...',
                    'Verification action preview',
                    { zoomable: false }
                )

                if (isReject) {
                    actionTitle.textContent = 'Reject Verification'
                    actionMessage.textContent = "Do you want to reject this patient's verification request?"
                    if (rejectReasonWrap) rejectReasonWrap.classList.remove('hidden')
                    if (overrideStatusWrap) overrideStatusWrap.classList.add('hidden')
                } else if (status === 'override') {
                    actionTitle.textContent = 'Override Verification Status'
                    actionMessage.textContent = 'Force-set the verification status for this patient.'
                    if (rejectReasonWrap) rejectReasonWrap.classList.add('hidden')
                    if (overrideStatusWrap) overrideStatusWrap.classList.remove('hidden')
                } else {
                    actionTitle.textContent = 'Approve Verification'
                    actionMessage.textContent = 'Do you want to approve this patient verification request?'
                    if (rejectReasonWrap) rejectReasonWrap.classList.add('hidden')
                    if (overrideStatusWrap) overrideStatusWrap.classList.add('hidden')
                }

                actionResolver = resolve
                actionOverlay.classList.remove('hidden')
                actionOverlay.classList.add('flex')

                actionConfirm.disabled = true
                var countdown = 3
                actionConfirm.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>Confirm (' + countdown + 's)</span></span>'
                actionCountdownTimer = setInterval(function () {
                    countdown = countdown - 1
                    if (countdown < 1) {
                        clearInterval(actionCountdownTimer)
                        actionCountdownTimer = null
                        return
                    }
                    actionConfirm.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>Confirm (' + countdown + 's)</span></span>'
                }, 1000)
                actionDelayTimer = setTimeout(function () {
                    if (actionCountdownTimer) {
                        clearInterval(actionCountdownTimer)
                        actionCountdownTimer = null
                    }
                    actionConfirm.disabled = false
                    actionConfirm.innerHTML = actionConfirmDefaultHtml || 'Confirm'
                    actionDelayTimer = null
                }, 3000)
            })
        }

        function updateVerificationStatus(id, status, remarks) {
            return apiFetch("{{ url('/api/patient-verifications') }}/" + id, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: status, remarks: remarks || '' })
            }).then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data }
                }).catch(function () {
                    return { ok: response.ok, data: null }
                })
            })
        }

        function bindRowActions() {
            var docButtons = document.querySelectorAll('.admin-verif-doc')
            docButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var id = this.getAttribute('data-id')
                    if (!id) return
                    var payload = lastPayload || {}
                    var list = Array.isArray(payload.data) ? payload.data : []
                    var selected = list.find(function (item) { return String(item.verification_id) === String(id) }) || null
                    if (!selected) {
                        showError('Unable to find the selected verification.')
                        return
                    }
                    openDocumentPanelFor(selected)
                })
            })
        }

        function bindHistoryActions() {
            // History card click handlers are now bound in renderHistory()
        }

        if (docPanelClose) docPanelClose.addEventListener('click', closeDocPanel)
        if (docMainWrap) {
            docMainWrap.addEventListener('click', function () {
                var imageUrl = docMainWrap.getAttribute('data-preview-image-url')
                if (!imageUrl) return
                openImageViewer(imageUrl, docMainWrap.getAttribute('data-preview-title') || 'Verification document')
            })
        }
        if (docPanelOverlay) {
            docPanelOverlay.addEventListener('click', function (e) {
                if (e.target === docPanelOverlay) {
                    if (imageViewer && !imageViewer.classList.contains('hidden')) {
                        closeImageViewer()
                        return
                    }
                    closeDocPanel()
                }
            })
        }
        if (imageViewerClose) imageViewerClose.addEventListener('click', closeImageViewer)
        if (imageViewer) {
            imageViewer.addEventListener('click', function (e) {
                if (e.target === imageViewer) closeImageViewer()
            })
        }
        if (zoomInButton) zoomInButton.addEventListener('click', function () { setImageViewerScale(imageViewerState.scale + 0.25) })
        if (zoomOutButton) zoomOutButton.addEventListener('click', function () { setImageViewerScale(imageViewerState.scale - 0.25) })
        if (zoomResetButton) zoomResetButton.addEventListener('click', resetImageViewerTransform)
        if (imageViewerStage) {
            imageViewerStage.addEventListener('wheel', function (e) {
                e.preventDefault()
                setImageViewerScale(imageViewerState.scale + (e.deltaY < 0 ? 0.2 : -0.2))
            }, { passive: false })
            imageViewerStage.addEventListener('mousedown', function (e) {
                if (imageViewerState.scale <= 1) return
                imageViewerState.dragging = true
                imageViewerState.startX = e.clientX - imageViewerState.offsetX
                imageViewerState.startY = e.clientY - imageViewerState.offsetY
            })
        }
        document.addEventListener('mousemove', function (e) {
            if (!imageViewerState.dragging) return
            imageViewerState.offsetX = e.clientX - imageViewerState.startX
            imageViewerState.offsetY = e.clientY - imageViewerState.startY
            setImageViewerScale(imageViewerState.scale)
        })
        document.addEventListener('mouseup', function () {
            imageViewerState.dragging = false
        })

        // ── Panel approve/reject button handlers ──
        if (panelApproveBtn) {
            panelApproveBtn.addEventListener('click', function () {
                if (!currentPanelVerif) return
                var verifId = currentPanelVerif.verification_id || currentPanelVerif.id
                if (!verifId) return
                openActionModal('approved', currentPanelVerif).then(function (result) {
                    if (!result) return
                    var remarks = result.remarks || ''
                    updateVerificationStatus(String(verifId), 'approved', remarks).then(function (updateResult) {
                        if (!updateResult || !updateResult.ok) {
                            showError('Failed to update verification status.')
                            return
                        }
                        showError('')
                        if (typeof showToast === 'function') showToast('Verification approved.', 'success')
                        loadStats()
                        loadVerifications(currentPage)
                        currentHistoryRows = []
                        if (docPanelOverlay && !docPanelOverlay.classList.contains('hidden')) {
                            openDocumentPanelFor(updateResult.data || currentPanelVerif)
                        }
                    }).catch(function () {
                        showError('Network error while updating verification status.')
                    })
                }).catch(function () {})
            })
        }
        if (panelRejectBtn) {
            panelRejectBtn.addEventListener('click', function () {
                if (!currentPanelVerif) return
                var verifId = currentPanelVerif.verification_id || currentPanelVerif.id
                if (!verifId) return
                openActionModal('rejected', currentPanelVerif).then(function (result) {
                    if (!result) return
                    var remarks = result.remarks || ''
                    updateVerificationStatus(String(verifId), 'rejected', remarks).then(function (updateResult) {
                        if (!updateResult || !updateResult.ok) {
                            showError('Failed to update verification status.')
                            return
                        }
                        showError('')
                        if (typeof showToast === 'function') showToast('Verification rejected.', 'success')
                        loadStats()
                        loadVerifications(currentPage)
                        currentHistoryRows = []
                        if (docPanelOverlay && !docPanelOverlay.classList.contains('hidden')) {
                            openDocumentPanelFor(updateResult.data || currentPanelVerif)
                        }
                    }).catch(function () {
                        showError('Network error while updating verification status.')
                    })
                }).catch(function () {})
            })
        }
        if (overrideBtn) {
            overrideBtn.addEventListener('click', function () {
                if (!currentPanelVerif) return
                var verifId = currentPanelVerif.verification_id || currentPanelVerif.id
                if (!verifId) return
                openActionModal('override', currentPanelVerif).then(function (result) {
                    if (!result || !result.overrideStatus) return
                    var newStatus = result.overrideStatus
                    updateVerificationStatus(String(verifId), newStatus, '').then(function (updateResult) {
                        if (!updateResult || !updateResult.ok) {
                            showError('Failed to update verification status.')
                            return
                        }
                        showError('')
                        if (typeof showToast === 'function') showToast('Verification status overridden to ' + newStatus + '.', 'success')
                        loadStats()
                        loadVerifications(currentPage)
                        currentHistoryRows = []
                        if (docPanelOverlay && !docPanelOverlay.classList.contains('hidden')) {
                            openDocumentPanelFor(updateResult.data || currentPanelVerif)
                        }
                    }).catch(function () {
                        showError('Network error while updating verification status.')
                    })
                }).catch(function () {})
            })
        }
        if (actionCancel) actionCancel.addEventListener('click', function () { closeActionModal(null) })
        if (actionConfirm) {
            actionConfirm.addEventListener('click', function () {
                var overrideStatus = overrideStatusSelect && !overrideStatusWrap.classList.contains('hidden') ? overrideStatusSelect.value : null
                closeActionModal({ confirmed: true, overrideStatus: overrideStatus })
            })
        }
        if (actionOverlay) {
            actionOverlay.addEventListener('click', function (e) {
                if (e.target === actionOverlay) closeActionModal(null)
            })
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageViewer()
                closeDocPanel()
                closeActionModal(null)
            }
        })

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                loadVerifications(1)
            })
        }
        if (statusFilter) {
            statusFilter.addEventListener('change', function () {
                loadVerifications(1)
                loadStats()
            })
        }
        if (typeFilter) {
            typeFilter.addEventListener('change', function () {
                loadVerifications(1)
                loadStats()
            })
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                loadVerifications(1)
            })
        }

        var verifRefreshBtn = document.getElementById('adminVerifRefreshBtn')
        if (verifRefreshBtn) {
            verifRefreshBtn.addEventListener('click', function () { loadVerifications(currentPage) })
        }

        loadStats()
        loadVerifications(1)
    })
</script>
