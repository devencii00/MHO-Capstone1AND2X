<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900">Reports & analytics</h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Summary</span>
    </div>
    <p class="text-xs text-slate-500 mb-3">
        Key metrics and revenue summary for the clinic.
    </p>

    @php
        $metrics = $adminMetrics ?? [];
        $reports = $adminReports ?? [];
        $recentTransactions = $adminRecentTransactions ?? collect();
    @endphp

    <div class="mb-4 flex items-center justify-end">
        <div class="text-[0.72rem] text-slate-500">
            Updated based on live system records.
        </div>
    </div>

    <div class="grid gap-3 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 admin-analytics-card" data-group="patients">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[0.78rem] text-slate-500">Total patients</span>
                <x-lucide-users class="w-[17px] h-[17px] text-green-600" />
            </div>
            <div class="font-serif font-bold text-xl text-slate-900">
                {{ number_format((int) ($metrics['patientCount'] ?? 0)) }}
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 admin-analytics-card" data-group="staff">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[0.78rem] text-slate-500">Active doctors</span>
                <x-lucide-stethoscope class="w-[17px] h-[17px] text-green-600" />
            </div>
            <div class="font-serif font-bold text-xl text-slate-900">
                {{ number_format((int) ($metrics['doctorCount'] ?? 0)) }}
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 admin-analytics-card" data-group="compliance">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[0.78rem] text-slate-500">Pending verifications</span>
                <x-lucide-badge-check class="w-[17px] h-[17px] text-amber-500" />
            </div>
            <div class="font-serif font-bold text-xl text-slate-900">
                {{ number_format((int) ($metrics['pendingVerificationsCount'] ?? 0)) }}
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 admin-analytics-card" data-group="compliance">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[0.78rem] text-slate-500">Total audit entries</span>
                <x-lucide-folder class="w-[17px] h-[17px] text-slate-600" />
            </div>
            <div class="font-serif font-bold text-xl text-slate-900">
                {{ number_format((int) ($metrics['recentLogsCount'] ?? 0)) }}
            </div>
        </div>
    </div>

    <div class="border border-slate-100 rounded-2xl p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-semibold text-slate-900">Revenue Reports</h3>
            <span class="text-[0.68rem] text-slate-400 uppercase tracking-widest">Summary</span>
        </div>
        <div class="flex flex-wrap items-stretch gap-3">
            <div class="flex-1 min-w-[180px] flex items-center justify-between rounded-2xl bg-white border border-slate-100 px-4 py-3">
                <div>
                    <p class="text-[0.7rem] text-slate-500 mb-0.5">Today</p>
                    <p class="font-serif font-bold text-lg text-slate-900">
                        ₱{{ number_format((float) ($metrics['revenueToday'] ?? 0), 2) }}
                    </p>
                </div>
                <x-lucide-calendar class="w-[22px] h-[22px] text-green-600 shrink-0 ml-2" />
            </div>
            <div class="flex-1 min-w-[180px] flex items-center justify-between rounded-2xl bg-white border border-slate-100 px-4 py-3">
                <div>
                    <p class="text-[0.7rem] text-slate-500 mb-0.5">This month</p>
                    <p class="font-serif font-bold text-lg text-slate-900">
                        ₱{{ number_format((float) ($metrics['revenueThisMonth'] ?? 0), 2) }}
                    </p>
                </div>
                <x-lucide-chart-column class="w-[22px] h-[22px] text-emerald-600 shrink-0 ml-2" />
            </div>
        </div>
    </div>

    <div class="border border-slate-100 rounded-2xl p-4 mt-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-semibold text-slate-900">Today's Transactions</h3>
            <div class="flex items-center gap-2">
                <span id="adminTxnTodayCount" class="text-[0.68rem] text-slate-400 uppercase tracking-widest">— entries</span>
                <button type="button" id="adminGenReportBtn" class="px-3 py-1.5 rounded-lg bg-green-600 text-white text-[0.7rem] font-semibold hover:bg-green-700">Generate Report</button>
            </div>
        </div>
        <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="admin_txn_today_search" class="block text-[0.7rem] text-slate-600 mb-1">Search by patient / doctor</label>
                <input id="admin_txn_today_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Type patient or doctor name…">
            </div>
            <div class="w-full sm:w-48">
                <label for="admin_txn_today_service" class="block text-[0.7rem] text-slate-600 mb-1">Filter by service</label>
                <select id="admin_txn_today_service" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">All services</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto scrollbar-hidden">
            <table class="min-w-full text-left text-xs text-slate-600">
                <thead>
                    <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                        <th class="py-2 pr-4 font-semibold">Date / Time</th>
                        <th class="py-2 pr-4 font-semibold">Patient</th>
                        <th class="py-2 pr-4 font-semibold">Doctor</th>
                        <th class="py-2 pr-4 font-semibold">Service</th>
                        <th class="py-2 pr-4 font-semibold">Amount</th>
                        <th class="py-2 pr-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody id="adminTxnTodayBody">
                    <tr>
                        <td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">
                            Loading today's transactions…
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="adminTxnTodayPagination" class="flex items-center justify-center gap-1 mt-3 flex-wrap"></div>
    </div>

    <div id="adminReportModal" class="hidden fixed inset-0 z-[90] bg-slate-950/45 backdrop-blur-sm p-4 sm:p-6">
        <div class="min-h-full flex items-center justify-center">
            <div id="adminReportModalCard" class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.22)] transition-all duration-200">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                    <div>
                        <h3 id="adminReportModalTitle" class="text-sm font-semibold text-slate-900">Generate transaction report</h3>
                        <p id="adminReportModalSubtitle" class="mt-1 text-[0.78rem] text-slate-500">Choose a single date or a custom date range, then generate a report preview inside this window.</p>
                    </div>
                    <button type="button" id="adminReportModalCloseBtn" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700">
                        <x-lucide-x class="w-4 h-4" />
                    </button>
                </div>

                <div id="adminReportModalForm" class="px-5 py-4 space-y-4">
                    <div id="adminReportFormFields">
                        <label for="adminReportType" class="block text-[0.72rem] text-slate-600 mb-1">Report type</label>
                        <select id="adminReportType" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="date">Single date</option>
                            <option value="range">Date range</option>
                        </select>
                    </div>

                    <div id="adminReportSingleDateWrap">
                        <label for="adminReportDate" class="block text-[0.72rem] text-slate-600 mb-1">Date</label>
                        <input id="adminReportDate" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>

                    <div id="adminReportRangeWrap" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="adminReportStartDate" class="block text-[0.72rem] text-slate-600 mb-1">Starting date</label>
                            <input id="adminReportStartDate" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="adminReportEndDate" class="block text-[0.72rem] text-slate-600 mb-1">End date</label>
                            <input id="adminReportEndDate" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                    </div>

                    <div id="adminReportFeedback" class="hidden rounded-2xl border px-3 py-2 text-[0.78rem]"></div>
                </div>

                <div id="adminReportPreviewWrap" class="hidden px-5 py-4">
                    <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50">
                        <iframe id="adminReportPreviewFrame" title="Transaction report preview" class="block w-full h-[68vh] bg-white"></iframe>
                    </div>
                </div>

                <div id="adminReportInitialActions" class="flex items-center justify-end gap-2 border-t border-slate-100 px-5 py-4">
                    <button type="button" id="adminReportCancelBtn" class="px-3 py-2 rounded-xl border border-slate-200 text-[0.78rem] font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                    <button type="button" id="adminReportSubmitBtn" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Generate Report</button>
                </div>

                <div id="adminReportPreviewActions" class="hidden items-center justify-between gap-2 border-t border-slate-100 px-5 py-4">
                    <button type="button" id="adminReportResetBtn" class="px-3 py-2 rounded-xl border border-slate-200 text-[0.78rem] font-semibold text-slate-600 hover:bg-slate-50">Generate Another Report</button>
                    <div class="flex items-center gap-2">
                        <button type="button" id="adminReportPreviewCloseBtn" class="px-3 py-2 rounded-xl border border-slate-200 text-[0.78rem] font-semibold text-slate-600 hover:bg-slate-50">Close</button>
                        <button type="button" id="adminReportPrintBtn" class="px-3 py-2 rounded-xl bg-green-700 text-white text-[0.78rem] font-semibold hover:bg-green-800">Download / Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var txnBody = document.getElementById('adminTxnTodayBody')
        var txnPagination = document.getElementById('adminTxnTodayPagination')
        var txnCount = document.getElementById('adminTxnTodayCount')
        var txnSearch = document.getElementById('admin_txn_today_search')
        var txnServiceFilter = document.getElementById('admin_txn_today_service')
        var genReportBtn = document.getElementById('adminGenReportBtn')
        var reportModal = document.getElementById('adminReportModal')
        var reportModalCard = document.getElementById('adminReportModalCard')
        var reportModalCloseBtn = document.getElementById('adminReportModalCloseBtn')
        var reportModalTitle = document.getElementById('adminReportModalTitle')
        var reportModalSubtitle = document.getElementById('adminReportModalSubtitle')
        var reportModalForm = document.getElementById('adminReportModalForm')
        var reportPreviewWrap = document.getElementById('adminReportPreviewWrap')
        var reportPreviewFrame = document.getElementById('adminReportPreviewFrame')
        var reportInitialActions = document.getElementById('adminReportInitialActions')
        var reportPreviewActions = document.getElementById('adminReportPreviewActions')
        var reportCancelBtn = document.getElementById('adminReportCancelBtn')
        var reportSubmitBtn = document.getElementById('adminReportSubmitBtn')
        var reportResetBtn = document.getElementById('adminReportResetBtn')
        var reportPreviewCloseBtn = document.getElementById('adminReportPreviewCloseBtn')
        var reportPrintBtn = document.getElementById('adminReportPrintBtn')
        var reportType = document.getElementById('adminReportType')
        var reportDate = document.getElementById('adminReportDate')
        var reportStartDate = document.getElementById('adminReportStartDate')
        var reportEndDate = document.getElementById('adminReportEndDate')
        var reportSingleDateWrap = document.getElementById('adminReportSingleDateWrap')
        var reportRangeWrap = document.getElementById('adminReportRangeWrap')
        var reportFeedback = document.getElementById('adminReportFeedback')

        var allTransactions = []
        var filteredTransactions = []
        var txPerPage = 10
        var txCurrentPage = 1
        var txVisibleCount = 6
        var reportPreviewLoaded = false

        function pad2(v) { return String(v).padStart(2, '0') }
        function trim(s) { return String(s || '').trim() }

        function todayIsoDate() {
            var d = new Date()
            return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate())
        }

        function getApiToken() {
            try {
                return window.localStorage ? window.localStorage.getItem('api_token') : null
            } catch (e) {
                return null
            }
        }

        function fetchWithAuth(url, options) {
            var token = getApiToken()
            var opts = options || {}
            var headers = Object.assign({}, opts.headers || {})
            headers['X-Requested-With'] = 'XMLHttpRequest'
            if (token) headers['Authorization'] = 'Bearer ' + token
            return fetch(url, Object.assign({}, opts, { headers: headers }))
        }

        function setReportFeedback(message, tone) {
            if (!reportFeedback) return
            if (!message) {
                reportFeedback.className = 'hidden rounded-2xl border px-3 py-2 text-[0.78rem]'
                reportFeedback.textContent = ''
                return
            }

            var cls = 'rounded-2xl border px-3 py-2 text-[0.78rem] '
            if (tone === 'error') cls += 'border-rose-200 bg-rose-50 text-rose-700'
            else if (tone === 'success') cls += 'border-emerald-200 bg-emerald-50 text-emerald-700'
            else cls += 'border-slate-200 bg-slate-50 text-slate-600'

            reportFeedback.className = cls
            reportFeedback.textContent = message
        }

        function syncReportInputs() {
            var isRange = reportType && reportType.value === 'range'
            if (reportSingleDateWrap) reportSingleDateWrap.classList.toggle('hidden', isRange)
            if (reportRangeWrap) reportRangeWrap.classList.toggle('hidden', !isRange)
            setReportFeedback('', '')
        }

        function setReportModalMode(mode) {
            var previewMode = mode === 'preview'

            if (reportModalCard) {
                reportModalCard.classList.toggle('max-w-lg', !previewMode)
                reportModalCard.classList.toggle('max-w-7xl', previewMode)
            }
            if (reportModalForm) reportModalForm.classList.toggle('hidden', previewMode)
            if (reportPreviewWrap) reportPreviewWrap.classList.toggle('hidden', !previewMode)
            if (reportInitialActions) {
                reportInitialActions.classList.toggle('hidden', previewMode)
                reportInitialActions.classList.toggle('flex', !previewMode)
            }
            if (reportPreviewActions) {
                reportPreviewActions.classList.toggle('hidden', !previewMode)
                reportPreviewActions.classList.toggle('flex', previewMode)
            }
            if (reportModalTitle) reportModalTitle.textContent = previewMode ? 'Transaction report preview' : 'Generate transaction report'
            if (reportModalSubtitle) {
                reportModalSubtitle.textContent = previewMode
                    ? 'Review the generated report here, then print it or save it as PDF when ready.'
                    : 'Choose a single date or a custom date range, then generate a report preview inside this window.'
            }
        }

        function resetReportModal(clearDates) {
            reportPreviewLoaded = false
            if (reportPreviewFrame) reportPreviewFrame.srcdoc = ''
            setReportModalMode('form')
            setReportFeedback('', '')
            if (clearDates) {
                var defaultDate = todayIsoDate()
                if (reportType) reportType.value = 'date'
                if (reportDate) reportDate.value = defaultDate
                if (reportStartDate) reportStartDate.value = defaultDate
                if (reportEndDate) reportEndDate.value = defaultDate
            }
            syncReportInputs()
        }

        function openReportModal() {
            if (!reportModal) return
            reportModal.classList.remove('hidden')
            resetReportModal(false)
        }

        function closeReportModal() {
            if (!reportModal) return
            reportModal.classList.add('hidden')
            resetReportModal(false)
        }

        function buildReportQuery() {
            var mode = reportType ? reportType.value : 'date'
            if (mode === 'range') {
                var start = trim(reportStartDate ? reportStartDate.value : '')
                var end = trim(reportEndDate ? reportEndDate.value : '')
                if (!start || !end) {
                    throw new Error('Starting date and end date are required.')
                }
                return '?start_date=' + encodeURIComponent(start) + '&end_date=' + encodeURIComponent(end)
            }

            var singleDate = trim(reportDate ? reportDate.value : '')
            if (!singleDate) {
                throw new Error('Date is required.')
            }
            return '?start_date=' + encodeURIComponent(singleDate) + '&end_date=' + encodeURIComponent(singleDate)
        }

        function formatDate(iso) {
            if (!iso) return '—'
            var d = new Date(iso)
            if (isNaN(d.getTime())) return iso
            return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate()) + ' ' + pad2(d.getHours()) + ':' + pad2(d.getMinutes())
        }

        function patientName(tx) {
            var p = tx && tx.appointment && tx.appointment.patient ? tx.appointment.patient : null
            if (!p) return '—'
            return trim((p.firstname || '') + ' ' + (p.lastname || ''))
        }

        function doctorName(tx) {
            var d = tx && tx.appointment && tx.appointment.doctor ? tx.appointment.doctor : null
            if (!d) return '—'
            return trim((d.firstname || '') + ' ' + (d.lastname || ''))
        }

        function serviceNames(tx) {
            var svcs = tx && tx.appointment && Array.isArray(tx.appointment.services) ? tx.appointment.services : []
            var names = svcs.map(function (s) { return String(s.service_name || '').trim() }).filter(function (v) { return v !== '' })
            return names.length ? names.join(', ') : '—'
        }

        function statusHtml(status) {
            var s = trim(String(status || '')).toLowerCase()
            var label = s || 'unknown'
            var cls = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-[0.68rem] font-semibold '
            if (s === 'paid') cls += 'bg-emerald-50 text-emerald-700 border border-emerald-100'
            else if (s === 'pending') cls += 'bg-amber-50 text-amber-700 border border-amber-100'
            else if (s === 'failed') cls += 'bg-rose-50 text-rose-700 border border-rose-100'
            else cls += 'bg-slate-50 text-slate-600 border border-slate-100'
            return '<span class="' + cls + '">' + label + '</span>'
        }

        function renderTodaysTransactions() {
            if (!txnBody) return
            var list = filteredTransactions
            if (!list.length) {
                txnBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">No transactions recorded today.</td></tr>'
                if (txnPagination) txnPagination.innerHTML = ''
                if (txnCount) txnCount.textContent = '0 entries'
                return
            }
            var totalPages = Math.ceil(list.length / txPerPage)
            if (txCurrentPage > totalPages) txCurrentPage = totalPages
            var start = (txCurrentPage - 1) * txPerPage
            var end = Math.min(start + txPerPage, list.length)
            var page = list.slice(start, end)

            if (txnCount) txnCount.textContent = list.length + ' entries'

            var html = ''
            page.forEach(function (tx) {
                html += '<tr class="border-b border-slate-50 last:border-0">'
                html += '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + formatDate(tx.transaction_datetime) + '</td>'
                html += '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + patientName(tx) + '</td>'
                html += '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + doctorName(tx) + '</td>'
                html += '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + serviceNames(tx) + '</td>'
                html += '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">₱' + Number(tx.amount || 0).toFixed(2) + '</td>'
                html += '<td class="py-2 pr-4 text-[0.78rem]">' + statusHtml(tx.payment_status) + '</td>'
                html += '</tr>'
            })
            txnBody.innerHTML = html
            renderTxnPagination()
        }

        function renderTxnPagination() {
            if (!txnPagination) return
            var total = filteredTransactions.length
            if (total === 0) {
                txnPagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                return
            }
            var totalPages = Math.ceil(total / txPerPage)
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
            html += '<button type="button" class="' + (txCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-txpage="prev"' + (txCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var windowStart = txCurrentPage
            var windowEnd = Math.min(windowStart + txVisibleCount - 1, totalPages)
            for (var i = windowStart; i <= windowEnd; i++) {
                html += '<button type="button" class="' + (i === txCurrentPage ? btnActive : btnInactive) + '" data-txpage="' + i + '">' + i + '</button>'
            }
            if (windowEnd < totalPages) {
                html += '<button type="button" class="' + btnInactive + '" data-txpage="next-window" title="Next set">…</button>'
            }
            html += '<button type="button" class="' + (txCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-txpage="next"' + (txCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            txnPagination.innerHTML = html
            txnPagination.querySelectorAll('button[data-txpage]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var p = btn.getAttribute('data-txpage')
                    if (p === 'prev' && txCurrentPage > 1) { txCurrentPage--; renderTodaysTransactions() }
                    else if (p === 'next' && txCurrentPage < totalPages) { txCurrentPage++; renderTodaysTransactions() }
                    else if (p === 'next-window') {
                        txCurrentPage = Math.min(windowEnd + 1, totalPages)
                        renderTodaysTransactions()
                    }
                    else if (p !== 'prev' && p !== 'next') { txCurrentPage = parseInt(p, 10); renderTodaysTransactions() }
                })
            })
        }

        function filterTransactions() {
            var q = trim(txnSearch ? txnSearch.value : '').toLowerCase()
            var svc = txnServiceFilter ? txnServiceFilter.value : ''
            filteredTransactions = allTransactions.filter(function (tx) {
                if (q) {
                    var pt = patientName(tx).toLowerCase()
                    var dr = doctorName(tx).toLowerCase()
                    if (pt.indexOf(q) === -1 && dr.indexOf(q) === -1) return false
                }
                if (svc) {
                    var svcs = tx && tx.appointment && Array.isArray(tx.appointment.services) ? tx.appointment.services : []
                    var hasSvc = svcs.some(function (s) { return String(s.service_id) === svc })
                    if (!hasSvc) return false
                }
                return true
            })
            txCurrentPage = 1
            renderTodaysTransactions()
        }

        function loadTodaysTransactions() {
            var ds = todayIsoDate()
            fetchWithAuth('/api/transactions?per_page=500&start_date=' + ds + '&end_date=' + ds + '&order=latest', {
                headers: { 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json() })
            .then(function (result) {
                var data = result && result.data ? result.data : []
                allTransactions = Array.isArray(data) ? data : []
                filterTransactions()
            })
            .catch(function () {
                if (txnBody) txnBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Failed to load transactions.</td></tr>'
            })
        }

        function loadServices() {
            fetchWithAuth('/api/services', {
                headers: { 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json() })
            .then(function (result) {
                var data = result && result.data ? result.data : []
                var services = Array.isArray(data) ? data : []
                if (!txnServiceFilter) return
                txnServiceFilter.innerHTML = '<option value="">All services</option>'
                services.forEach(function (s) {
                    var opt = document.createElement('option')
                    opt.value = s.service_id
                    opt.textContent = s.service_name || 'Unnamed'
                    txnServiceFilter.appendChild(opt)
                })
            })
            .catch(function () {})
        }

        function openPrintableReport() {
            try {
                var query = buildReportQuery()
                if (!reportSubmitBtn) return

                reportSubmitBtn.disabled = true
                reportSubmitBtn.textContent = 'Preparing report...'
                setReportFeedback('Generating report preview...', 'info')

                fetchWithAuth('/api/transactions/report/print' + query + '&embed=1', {
                    headers: { 'Accept': 'text/html' }
                })
                .then(function (response) {
                    return response.text().then(function (html) {
                        return { ok: response.ok, status: response.status, html: html }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        if (result.status === 403) throw new Error('You are not allowed to generate this report.')
                        throw new Error('Failed to generate transaction report.')
                    }

                    if (!reportPreviewFrame) throw new Error('Report preview is unavailable.')

                    reportPreviewLoaded = false
                    reportPreviewFrame.srcdoc = result.html
                    setReportModalMode('preview')
                    setReportFeedback('', '')
                })
                .catch(function (error) {
                    setReportFeedback(error && error.message ? error.message : 'Failed to generate transaction report.', 'error')
                })
                .finally(function () {
                    if (reportSubmitBtn) {
                        reportSubmitBtn.disabled = false
                        reportSubmitBtn.textContent = 'Generate Report'
                    }
                })
            } catch (error) {
                setReportFeedback(error && error.message ? error.message : 'Please review the report dates.', 'error')
                if (reportSubmitBtn) {
                    reportSubmitBtn.disabled = false
                    reportSubmitBtn.textContent = 'Generate Report'
                }
            }
        }

        function printPreviewReport() {
            if (!reportPreviewFrame || !reportPreviewFrame.contentWindow || !reportPreviewLoaded) {
                return
            }

            // Temporarily set the page title so the browser suggests the right PDF filename
            var origTitle = document.title
            var mode = reportType ? reportType.value : 'date'
            var dateLabel
            if (mode === 'range') {
                var start = reportStartDate ? reportStartDate.value : ''
                var end = reportEndDate ? reportEndDate.value : ''
                dateLabel = start === end ? start : start + ' - ' + end
            } else {
                dateLabel = reportDate ? reportDate.value : ''
            }
            document.title = 'OPOL MHO - Report ' + (dateLabel || '')

            reportPreviewFrame.contentWindow.focus()
            reportPreviewFrame.contentWindow.print()

            // Restore original title after print dialog closes
            setTimeout(function () {
                document.title = origTitle
            }, 100)
        }

        var defaultDate = todayIsoDate()
        if (reportDate) reportDate.value = defaultDate
        if (reportStartDate) reportStartDate.value = defaultDate
        if (reportEndDate) reportEndDate.value = defaultDate
        if (reportPreviewFrame) {
            reportPreviewFrame.addEventListener('load', function () {
                reportPreviewLoaded = true
            })
        }

        if (txnSearch) txnSearch.addEventListener('input', filterTransactions)
        if (txnServiceFilter) txnServiceFilter.addEventListener('change', filterTransactions)
        if (reportType) reportType.addEventListener('change', syncReportInputs)

        if (genReportBtn) {
            genReportBtn.addEventListener('click', function () {
                openReportModal()
            })
        }

        if (reportModalCloseBtn) reportModalCloseBtn.addEventListener('click', closeReportModal)
        if (reportCancelBtn) reportCancelBtn.addEventListener('click', closeReportModal)
        if (reportSubmitBtn) reportSubmitBtn.addEventListener('click', openPrintableReport)
        if (reportResetBtn) reportResetBtn.addEventListener('click', function () { resetReportModal(true) })
        if (reportPreviewCloseBtn) reportPreviewCloseBtn.addEventListener('click', closeReportModal)
        if (reportPrintBtn) reportPrintBtn.addEventListener('click', printPreviewReport)
        if (reportModal) {
            reportModal.addEventListener('click', function (event) {
                if (event.target === reportModal) closeReportModal()
            })
        }
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && reportModal && !reportModal.classList.contains('hidden')) {
                closeReportModal()
            }
        })

        loadServices()
        loadTodaysTransactions()
    })
</script>
