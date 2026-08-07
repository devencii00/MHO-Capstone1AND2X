<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 mb-1">Laboratory workspace</h1>
        <p class="text-sm text-slate-500">Manage laboratory, ultrasound, and x-ray records and results at a glance.</p>
    </div>

    {{-- ── Stat cards ── --}}
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">Total records</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600">
                    <x-lucide-file-text class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labMetricTotal"><span class="skeleton h-6 w-12"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">Across all departments</p>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">Awaiting results</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600">
                    <x-lucide-clock class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labMetricPending"><span class="skeleton h-6 w-12"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">Pending encoding</p>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">Completed</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <x-lucide-check class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labMetricCompleted"><span class="skeleton h-6 w-12"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">Released results</p>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[0.78rem] text-slate-500">Departments</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                    <x-lucide-microscope class="w-4 h-4" />
                </div>
            </div>
            <div class="font-serif font-bold text-2xl text-slate-900" id="labMetricDepartments"><span class="skeleton h-6 w-12"></span></div>
            <p class="text-[0.68rem] text-slate-400 mt-1">Lab, ultrasound &amp; x-ray</p>
        </div>
    </div>

    {{-- ── Pending results + Quick actions ── --}}
    <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden flex flex-col h-[28rem]">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-orange-50/60 to-white flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600">
                            <x-lucide-clipboard-list class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Pending Results</h2>
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">Results waiting to be encoded</p>
                        </div>
                    </div>
                    <a href="{{ url('/dashboard/laboratory?role=laboratory&section=results') }}"
                       data-spa-nav="1"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-600 hover:border-green-400 hover:text-green-600 transition-colors">
                        View all
                        <x-lucide-arrow-right class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50">
                <table class="w-full text-left text-[0.75rem] text-slate-600 whitespace-nowrap">
                    <thead class="text-slate-500 border-b border-slate-300">
                        <tr>
                            <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Patient</th>
                            <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Service</th>
                            <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Department</th>
                            <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Date</th>
                            <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Status</th>
                            <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300" id="labPendingResultsBody">
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2.5">
                                    <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-300">
                                        <x-lucide-clipboard-list class="w-5 h-5" />
                                    </div>
                                    <p class="text-sm font-medium text-slate-400">No pending results at the moment.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50/60 to-white flex-shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                            <x-lucide-zap class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Quick Actions</h2>
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">Common lab tasks</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 space-y-2.5">
                    <a href="{{ url('/dashboard/laboratory?role=laboratory&section=records') }}" data-spa-nav="1"
                       class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-green-300 hover:bg-green-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600"><x-lucide-folder-open class="w-4 h-4" /></div>
                        <div>
                            <div class="text-[0.8rem] font-semibold text-slate-800">Patient Records</div>
                            <div class="text-[0.68rem] text-slate-400">Browse patient files</div>
                        </div>
                    </a>
                    <a href="{{ url('/dashboard/laboratory?role=laboratory&section=results') }}" data-spa-nav="1"
                       class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-green-300 hover:bg-green-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600"><x-lucide-clipboard-list class="w-4 h-4" /></div>
                        <div>
                            <div class="text-[0.8rem] font-semibold text-slate-800">Encode Results</div>
                            <div class="text-[0.68rem] text-slate-400">Input pending medical results</div>
                        </div>
                    </a>
                    <a href="{{ url('/dashboard/laboratory?role=laboratory&section=laboratory-records') }}" data-spa-nav="1"
                       class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-green-300 hover:bg-green-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600"><x-lucide-flask-conical class="w-4 h-4" /></div>
                        <div>
                            <div class="text-[0.8rem] font-semibold text-slate-800">Laboratory</div>
                            <div class="text-[0.68rem] text-slate-400">Fecalysis, urinalysis, CBC &amp; more</div>
                        </div>
                    </a>
                    <a href="{{ url('/dashboard/laboratory?role=laboratory&section=ultrasound-records') }}" data-spa-nav="1"
                       class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-green-300 hover:bg-green-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600"><x-lucide-activity class="w-4 h-4" /></div>
                        <div>
                            <div class="text-[0.8rem] font-semibold text-slate-800">Ultrasound</div>
                            <div class="text-[0.68rem] text-slate-400">Imaging records &amp; results</div>
                        </div>
                    </a>
                    <a href="{{ url('/dashboard/laboratory?role=laboratory&section=xray-records') }}" data-spa-nav="1"
                       class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-green-300 hover:bg-green-50/40 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600"><x-lucide-scan-line class="w-4 h-4" /></div>
                        <div>
                            <div class="text-[0.8rem] font-semibold text-slate-800">X-Ray</div>
                            <div class="text-[0.68rem] text-slate-400">Radiology records &amp; results</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Analytics (front-end preview) ── --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50/60 to-white flex-shrink-0">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                        <x-lucide-chart-no-axes-combined class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Analytics Overview</h2>
                        <p class="text-[0.7rem] text-slate-500 mt-0.5">Records encoded over the last 7 days</p>
                    </div>
                </div>
                <span class="text-[0.65rem] text-slate-400 uppercase tracking-wider bg-slate-50 px-2 py-1 rounded-full border border-slate-100">7-day trend</span>
            </div>
        </div>
        <div class="p-5">
            <div class="flex items-end gap-2 h-40" id="labWeeklyBars">
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <div class="w-full max-w-[34px] rounded-t-lg bg-slate-100" style="height:20%"></div>
                    <span class="text-[0.6rem] text-slate-400">—</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <div class="w-full max-w-[34px] rounded-t-lg bg-slate-100" style="height:20%"></div>
                    <span class="text-[0.6rem] text-slate-400">—</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <div class="w-full max-w-[34px] rounded-t-lg bg-slate-100" style="height:20%"></div>
                    <span class="text-[0.6rem] text-slate-400">—</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <div class="w-full max-w-[34px] rounded-t-lg bg-slate-100" style="height:20%"></div>
                    <span class="text-[0.6rem] text-slate-400">—</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <div class="w-full max-w-[34px] rounded-t-lg bg-slate-100" style="height:20%"></div>
                    <span class="text-[0.6rem] text-slate-400">—</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <div class="w-full max-w-[34px] rounded-t-lg bg-slate-100" style="height:20%"></div>
                    <span class="text-[0.6rem] text-slate-400">—</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">
                    <div class="w-full max-w-[34px] rounded-t-lg bg-slate-100" style="height:20%"></div>
                    <span class="text-[0.6rem] text-slate-400">—</span>
                </div>
            </div>
            <p class="text-center text-[0.7rem] text-slate-400 mt-3">Connect the laboratory backend to populate live analytics.</p>
        </div>
    </div>
</div>

<script>
(function () {
    if (typeof window.apiFetch !== 'function' || typeof window.fetchDashboardData !== 'function') return

    var settled = false

    function setText(id, value) {
        var el = document.getElementById(id)
        if (el) el.textContent = String(value == null ? '0' : value)
    }

    function renderWeeklyBars(days) {
        var wrap = document.getElementById('labWeeklyBars')
        if (!wrap || !Array.isArray(days) || !days.length) return
        var max = Math.max.apply(null, days.map(function (n) { return Number(n) || 0 })) || 1
        var dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        var html = ''
        days.slice(0, 7).forEach(function (n, i) {
            var v = Number(n) || 0
            var h = Math.max(4, Math.round((v / max) * 100))
            html += '<div class="flex-1 flex flex-col items-center justify-end gap-1.5 h-full">' +
                '<span class="text-[0.6rem] text-slate-500 font-semibold">' + v + '</span>' +
                '<div class="w-full max-w-[34px] rounded-t-lg bg-gradient-to-t from-green-600 to-green-400" style="height:' + h + '%"></div>' +
                '<span class="text-[0.6rem] text-slate-400">' + (dayLabels[i] || '') + '</span>' +
                '</div>'
        })
        wrap.innerHTML = html
    }

    window.fetchDashboardData('overview')
        .then(function (payload) {
            settled = true
            var data = payload && payload.data ? payload.data : {}
            setText('labMetricTotal', data.total_records)
            setText('labMetricPending', data.pending_results)
            setText('labMetricCompleted', data.completed_results)
            setText('labMetricDepartments', data.departments)
            renderWeeklyBars(data.weekly_records)

            var rows = Array.isArray(data.pending_results_list) ? data.pending_results_list : []
            var body = document.getElementById('labPendingResultsBody')
            if (!body) return
            if (!rows.length) return
            var html = ''
            rows.forEach(function (r) {
                html += '<tr>' +
                    '<td class="px-4 py-2.5 font-medium text-slate-800">' + escapeHtml(r.patient_name || 'Unknown') + '</td>' +
                    '<td class="px-4 py-2.5">' + escapeHtml(r.service_name || '—') + '</td>' +
                    '<td class="px-4 py-2.5">' + escapeHtml(r.department || '—') + '</td>' +
                    '<td class="px-4 py-2.5">' + escapeHtml(r.date || '—') + '</td>' +
                    '<td class="px-4 py-2.5"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-100 text-[0.65rem] font-semibold">Awaiting result</span></td>' +
                    '<td class="px-4 py-2.5"><a href="' + escapeHtml(r.encode_url || '#') + '" data-spa-nav="1" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-600 text-white text-[0.65rem] font-semibold hover:bg-green-700">Encode</a></td>' +
                    '</tr>'
            })
            body.innerHTML = html
        })
        .catch(function () {
            settled = true
            setText('labMetricTotal', 0)
            setText('labMetricPending', 0)
            setText('labMetricCompleted', 0)
            setText('labMetricDepartments', 3)
        })

    setTimeout(function () {
        if (!settled) {
            setText('labMetricTotal', 0)
            setText('labMetricPending', 0)
            setText('labMetricCompleted', 0)
            setText('labMetricDepartments', 3)
        }
    }, 8000)
})();
</script>
