<div class="space-y-6">
    @php
        $metrics = $adminMetrics ?? [];
        $sectionKey = $section ?? 'overview';
        if ($sectionKey === 'medical-background-viewer') {
            $sectionKey = 'patient-records';
        }

        $sectionTitles = [
            'user-management' => 'User Management',
            'doctor-management' => 'Staff Management',
            'services-management' => 'Services Management',
            'medicines-management' => 'Medicines',
            'appointments' => 'Appointments',
            'patient-records' => 'Patient Records',
            'verification-oversight' => 'Verification Oversight',
            'reports' => 'Reports',
            'chatbot-management' => 'Chatbot Management',
            'logs' => 'Logs',
            'settings' => 'Settings',
        ];

        $sectionSubtitles = [
            'user-management' => 'Create users, edit accounts, suspend or activate, search, and view dependents.',
            'doctor-management' => 'Manage staff profiles - doctors and receptionists. Edit profiles, schedules, and license information.',
            'services-management' => 'Add, edit, delete, and update pricing for clinic services.',
            'medicines-management' => 'Manage medicine reference data and active status.',
            'appointments' => 'Global appointment monitoring across doctors and dates.',
            'patient-records' => 'Review patient medical backgrounds and visit history.',
            'verification-oversight' => 'Review and override patient verification requests with document viewing and audit logs.',
            'reports' => 'View transactions, revenue trends, appointments, and no-show analytics.',
            'chatbot-management' => 'Manage chatbot questions, options, and conversation flow.',
            'logs' => 'View system logs and filter by user or action.',
            'settings' => 'Configure clinic info, queue behavior, payment methods, and account settings.',
        ];
    @endphp

    @if ($sectionKey === 'overview')
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">Admin Dashboard</h1>
            <p class="text-sm text-slate-500">High-level overview of patients, doctors, today’s appointments, and revenue.</p>
        </div>

        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[0.78rem] text-slate-500">Total patients</span>
                    <x-lucide-users class="w-[17px] h-[17px] text-green-600" />
                </div>
                <div class="font-serif font-bold text-xl text-slate-900">
                    {{ number_format((int) ($metrics['patientCount'] ?? 0)) }}
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[0.78rem] text-slate-500">Total doctors</span>
                    <x-lucide-stethoscope class="w-[17px] h-[17px] text-green-600" />
                </div>
                <div class="font-serif font-bold text-xl text-slate-900">
                    {{ number_format((int) ($metrics['doctorCount'] ?? 0)) }}
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[0.78rem] text-slate-500">Today’s appointments</span>
                    <x-lucide-calendar-check class="w-[17px] h-[17px] text-green-600" />
                </div>
                <div class="font-serif font-bold text-xl text-slate-900">
                    {{ number_format((int) ($metrics['appointmentsToday'] ?? 0)) }}
                </div>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[0.78rem] text-slate-500">Today’s revenue</span>
                    <x-lucide-coins class="w-[17px] h-[17px] text-green-600" />
                </div>
                <div class="font-serif font-bold text-xl text-slate-900">
                    ₱{{ number_format((float) ($metrics['revenueToday'] ?? 0), 2) }}
                </div>
            </div>
        </div>

        @php
            $charts = $adminCharts ?? [];
            $appointmentsChart = $charts['appointmentsPerDay'] ?? ['labels' => [], 'values' => []];
            $revenueChart = $charts['revenuePerMonth'] ?? ['labels' => [], 'values' => []];
        @endphp

        <div class="mt-6 grid gap-4 grid-cols-1 lg:grid-cols-2">
            <div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Charts</h2>
                        <p class="text-xs text-slate-500">Appointments per day (last 14 days)</p>
                    </div>
                    <x-lucide-chart-line class="w-[18px] h-[18px] text-green-600" />
                </div>
                <div id="adminAppointmentsPerDayChart" class="w-full h-[170px]"></div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Charts</h2>
                        <p class="text-xs text-slate-500">Revenue per month (last 12 months)</p>
                    </div>
                    <x-lucide-chart-column class="w-[18px] h-[18px] text-emerald-600" />
                </div>
                <div id="adminRevenuePerMonthChart" class="w-full h-[170px]"></div>
            </div>
        </div>

        <script type="application/json" id="adminAppointmentsPerDayChartData">@json($appointmentsChart)</script>
        <script type="application/json" id="adminRevenuePerMonthChartData">@json($revenueChart)</script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function safeParseJson(id) {
                    var el = document.getElementById(id)
                    if (!el) return null
                    try {
                        return JSON.parse(el.textContent || '{}')
                    } catch (e) {
                        return null
                    }
                }

                function renderLineChart(container, labels, values, color) {
                    if (!container) return
                    var w = 420
                    var h = 160
                    var padX = 36
                    var padY = 18

                    var max = 0
                    values.forEach(function (v) {
                        var num = typeof v === 'number' ? v : parseFloat(v || '0')
                        if (num > max) max = num
                    })
                    if (max <= 0) max = 1

                    var innerW = w - padX * 2
                    var innerH = h - padY * 2
                    var step = values.length > 1 ? innerW / (values.length - 1) : innerW

                    var points = values.map(function (v, idx) {
                        var num = typeof v === 'number' ? v : parseFloat(v || '0')
                        var x = padX + idx * step
                        var y = padY + (innerH - (num / max) * innerH)
                        return x.toFixed(2) + ',' + y.toFixed(2)
                    }).join(' ')

                    var svg =
                        '<svg viewBox="0 0 ' + w + ' ' + h + '" class="w-full h-full">' +
                            '<rect x="0" y="0" width="' + w + '" height="' + h + '" fill="white" />' +
                            '<line x1="' + padX + '" y1="' + (h - padY) + '" x2="' + (w - padX) + '" y2="' + (h - padY) + '" stroke="#e2e8f0" stroke-width="1" />' +
                            '<line x1="' + padX + '" y1="' + padY + '" x2="' + padX + '" y2="' + (h - padY) + '" stroke="#e2e8f0" stroke-width="1" />' +
                            '<polyline points="' + points + '" fill="none" stroke="' + color + '" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />' +
                        '</svg>'

                    container.innerHTML = svg
                }

                function renderBarChart(container, labels, values, color) {
                    if (!container) return
                    var w = 420
                    var h = 160
                    var padX = 36
                    var padY = 18

                    var max = 0
                    values.forEach(function (v) {
                        var num = typeof v === 'number' ? v : parseFloat(v || '0')
                        if (num > max) max = num
                    })
                    if (max <= 0) max = 1

                    var innerW = w - padX * 2
                    var innerH = h - padY * 2
                    var count = values.length || 1
                    var barGap = 4
                    var barW = Math.max(2, (innerW - barGap * (count - 1)) / count)

                    var bars = values.map(function (v, idx) {
                        var num = typeof v === 'number' ? v : parseFloat(v || '0')
                        var barH = (num / max) * innerH
                        var x = padX + idx * (barW + barGap)
                        var y = padY + (innerH - barH)
                        return '<rect x="' + x.toFixed(2) + '" y="' + y.toFixed(2) + '" width="' + barW.toFixed(2) + '" height="' + barH.toFixed(2) + '" rx="3" fill="' + color + '" opacity="0.85" />'
                    }).join('')

                    var svg =
                        '<svg viewBox="0 0 ' + w + ' ' + h + '" class="w-full h-full">' +
                            '<rect x="0" y="0" width="' + w + '" height="' + h + '" fill="white" />' +
                            '<line x1="' + padX + '" y1="' + (h - padY) + '" x2="' + (w - padX) + '" y2="' + (h - padY) + '" stroke="#e2e8f0" stroke-width="1" />' +
                            '<line x1="' + padX + '" y1="' + padY + '" x2="' + padX + '" y2="' + (h - padY) + '" stroke="#e2e8f0" stroke-width="1" />' +
                            bars +
                        '</svg>'

                    container.innerHTML = svg
                }

                var apptData = safeParseJson('adminAppointmentsPerDayChartData') || { labels: [], values: [] }
                var revData = safeParseJson('adminRevenuePerMonthChartData') || { labels: [], values: [] }

                renderLineChart(document.getElementById('adminAppointmentsPerDayChart'), apptData.labels || [], apptData.values || [], '#0cce3d')
                renderBarChart(document.getElementById('adminRevenuePerMonthChart'), revData.labels || [], revData.values || [], '#059669')
            })
        </script>

        <div class="mt-6 bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-slate-900">Recent activities</h2>
                <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Logs</span>
            </div>
            <p class="text-xs text-slate-500 mb-3">
                Latest system actions from the audit log.
            </p>
            <div class="overflow-x-auto">
                <table id="adminRecentActivitiesTable" class="min-w-full text-left text-xs text-slate-600">
                    <thead>
                        <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                            <th class="py-2 pr-4 font-semibold">When</th>
                            <th class="py-2 pr-4 font-semibold">User</th>
                            <th class="py-2 pr-4 font-semibold">Action</th>
                            <th class="py-2 pr-4 font-semibold">Record</th>
                        </tr>
                    </thead>
                    <tbody id="adminRecentActivitiesBody">
                        @forelse (($adminRecentAuditLogs ?? []) as $log)
                            <tr class="border-b border-slate-50 last:border-0 admin-activity-row">
                                <td class="py-2 pr-4 text-[0.78rem] text-slate-500">
                                    {{ optional($log->created_at)->format('Y-m-d H:i') ?? '-' }}
                                </td>
                                <td class="py-2 pr-4 text-[0.78rem] text-slate-700">
                                    @if ($log->user)
                                        {{ $log->user->email }}
                                    @else
                                        <span class="text-slate-400">System</span>
                                    @endif
                                </td>
                                <td class="py-2 pr-4 text-[0.78rem] text-slate-500">
                                    {{ $log->action ?? 'Action' }}
                                </td>
                                <td class="py-2 pr-4 text-[0.78rem] text-slate-500">
                                    {{ $log->table_name }} #{{ $log->record_id }}
                                </td>
                            </tr>
                        @empty
                            <tr id="adminRecentActivitiesEmpty">
                                <td colspan="4" class="py-4 text-center text-[0.78rem] text-slate-400">
                                    No recent activities recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="adminRecentActivitiesPagination" class="flex items-center justify-center gap-3 pt-3 pb-1"></div>
            </div>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var rows = document.querySelectorAll('#adminRecentActivitiesBody .admin-activity-row');
    var empty = document.getElementById('adminRecentActivitiesEmpty');
    var pagination = document.getElementById('adminRecentActivitiesPagination');

    if (!rows.length || !pagination) return;

    var perPage = 15;
    var total = rows.length;
    var totalPages = Math.ceil(total / perPage);
    var currentPage = 1;
    var visibleCount = 6;

    function showPage(page) {
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        var start = (page - 1) * perPage;
        var end = Math.min(start + perPage, total);
        rows.forEach(function (row, i) {
            row.style.display = (i >= start && i < end) ? '' : 'none';
        });
        renderPagination();
    }

    function renderPagination() {
        var html = '';
        var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border ';
        var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer';
        var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default';
        var btnActive = btnBase + 'bg-green-600 text-white border-green-600';

       
        html += '<button type="button" class="' + (currentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (currentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>';

     
        var windowStart = currentPage;
        var windowEnd = Math.min(windowStart + visibleCount - 1, totalPages);

    
        for (var i = windowStart; i <= windowEnd; i++) {
            html += '<button type="button" class="' + (i === currentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>';
        }

     
        if (windowEnd < totalPages) {
            var nextWindowStart = windowEnd + 1;
            html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>';
        }

   
        html += '<button type="button" class="' + (currentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (currentPage === totalPages ? ' disabled' : '') + '>Next ›</button>';

        pagination.innerHTML = html;

        pagination.querySelectorAll('button[data-page]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var p = btn.getAttribute('data-page');
                if (p === 'prev' && currentPage > 1) showPage(currentPage - 1);
                else if (p === 'next' && currentPage < totalPages) showPage(currentPage + 1);
                else if (p === 'next-window') {
                    var nextStart = Math.min(windowEnd + 1, totalPages);
                    showPage(nextStart);
                }
                else if (p !== 'prev' && p !== 'next') showPage(parseInt(p, 10));
            });
        });
    }

    showPage(1);
});
</script>
        </div>

    @else
        @php
            $title = $sectionTitles[$sectionKey] ?? 'Admin';
            $subtitle = $sectionSubtitles[$sectionKey] ?? 'Administrative workspace';
        @endphp

        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">{{ $title }}</h1>
            <p class="text-sm text-slate-500">{{ $subtitle }}</p>
        </div>

        @if ($sectionKey === 'user-management')
            @include('dashviews.admin.manage_user')
        @elseif ($sectionKey === 'doctor-management')
            @include('dashviews.admin.staff_management')
        @elseif ($sectionKey === 'services-management')
            @include('dashviews.admin.services_management')
        @elseif ($sectionKey === 'medicines-management')
            @include('dashviews.admin.medicines_management')
        @elseif ($sectionKey === 'appointments')
            @include('dashviews.admin.appointments_view')
        @elseif ($sectionKey === 'patient-records')
            @include('dashviews.admin.patient_records')
        @elseif ($sectionKey === 'verification-oversight')
            @include('dashviews.admin.verification_approvals')
        @elseif ($sectionKey === 'reports')
            @include('dashviews.admin.reports_analytics')
        @elseif ($sectionKey === 'chatbot-management')
            @include('dashviews.admin.chatbot_management')
        @elseif ($sectionKey === 'logs')
            @include('dashviews.admin.audit_logs')
        @elseif ($sectionKey === 'settings')
            @include('dashviews.admin.system_settings')
        @endif
    @endif
</div>