@php
    $metrics = $receptionMetrics ?? [];
    $sectionKey = $section ?? 'overview';

    $newRegistrationsToday = (int) ($metrics['newRegistrationsToday'] ?? 0);
    $appointmentsToday = (int) ($metrics['appointmentsToday'] ?? 0);
    $walkInsToday = (int) ($metrics['walkInsToday'] ?? 0);
    $pendingQueueRequests = (int) ($metrics['pendingQueueRequests'] ?? 0);
    $waitingInQueue = (int) ($metrics['waitingCount'] ?? 0);
    $currentQueueCount = (int) ($metrics['currentQueueCount'] ?? 0);
    $transactionsToday = (float) ($metrics['transactionsToday'] ?? 0);
    $activeCallNextDoctors = collect($receptionDoctorSlots ?? [])
        ->map(function ($slot) {
            $doctor = optional($slot)->doctor;
            return (object) [
                'doctor_id' => (int) ($slot->doctor_id ?? 0),
                'doctor_name' => (string) (
                    optional(optional($doctor)->personalInformation)->full_name
                    ?? $slot->doctor_name
                    ?? 'Doctor'
                ),
                'doctor_specialization' => (string) (
                    optional($doctor)->specialization
                    ?? $slot->doctor_specialization
                    ?? ''
                ),
                'slot_start' => $slot->start_time ?? null,
                'slot_end' => $slot->end_time ?? null,
            ];
        })
        ->filter(function ($slot) {
            return $slot->doctor_id > 0;
        })
        ->values();
@endphp

<div class="space-y-6">
    @if ($sectionKey === 'overview')
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">Receptionist workspace</h1>
            <p class="text-sm text-slate-500">Handle registrations, appointments, and the live queue at the front desk.</p>
        </div>

        <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
            <div class="bg-white border border-slate-200 rounded-[18px] p-5 lg:col-span-2 shadow-[0_2px_10px_rgba(15,23,42,0.04)] flex flex-col h-[600px] overflow-hidden">
                <div class="flex items-center justify-between mb-3 shrink-0">
                    <h2 class="text-sm font-semibold text-slate-900">Today at a glance</h2>
                    <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Front desk</span>
                </div>
                <div class="grid gap-3 grid-cols-1 sm:grid-cols-3 text-sm text-slate-600 shrink-0">
                    <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[0.78rem] text-slate-500">New registrations</span>
                            <x-lucide-user-plus class="w-[17px] h-[17px] text-green-600" />
                        </div>
                        <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($newRegistrationsToday) }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[0.78rem] text-slate-500">Appointments booked</span>
                            <x-lucide-calendar-check class="w-[17px] h-[17px] text-green-600" />
                        </div>
                        <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($appointmentsToday) }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[0.78rem] text-slate-500">Waiting in queue</span>
                            <x-lucide-clock class="w-[17px] h-[17px] text-green-600" />
                        </div>
                        <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($waitingInQueue) }}</div>
                    </div>
                    <div class="sm:col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[0.78rem] text-slate-500">Walk-ins</span>
                                <x-lucide-door-open class="w-[17px] h-[17px] text-green-600" />
                            </div>
                            <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($walkInsToday) }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[0.78rem] text-slate-500">Current queue count</span>
                                <x-lucide-users class="w-[17px] h-[17px] text-green-600" />
                            </div>
                            <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($currentQueueCount) }}</div>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)] sm:col-span-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[0.78rem] text-slate-500">Today's transactions (paid)</span>
                            <x-lucide-coins class="w-[17px] h-[17px] text-green-600" />
                        </div>
                        <div class="font-serif font-bold text-xl text-slate-900">₱{{ number_format($transactionsToday, 2) }}</div>
                    </div>
                </div>

            
                <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
    <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50/60 to-white flex-shrink-0">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                <x-lucide-receipt class="w-4 h-4" />
            </div>
            <div>
                <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Today's Transactions</h2>
                <p class="text-[0.7rem] text-slate-500 mt-0.5">Completed payments today</p>
            </div>
        </div>
    </div>
    <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50">
        <table class="w-full text-left text-[0.75rem] text-slate-600 whitespace-nowrap">
            <thead class="text-slate-500 border-b border-slate-100">
                <tr>
                    <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Date</th>
                    <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Reference</th>
                    <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Patient</th>
                    <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Type</th>
                    <th class="px-4 py-2.5 font-semibold text-[0.68rem] uppercase tracking-widest">Net</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100" id="receptionTodaysTransactionsTableBody">
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                        No transactions recorded today.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="receptionTransactionsPagination" class="flex items-center justify-center gap-1 px-4 py-2.5 border-t border-slate-100 shrink-0 flex-wrap"></div>
</div>

            </div>

            <!-- Queue & Schedule Panel -->
<div class="bg-white border border-slate-100 rounded-2xl shadow-xl flex flex-col h-[600px] overflow-hidden">
    <!-- Header with gradient accent -->
    <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50 shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="">
                    <!-- <x-lucide-calendar-clock class="w-4 h-4" /> -->
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Queue & Schedule</h2>
                    <p class="text-[0.7rem] text-slate-500 mt-0.5">Today's patient flow</p>
                </div>
            </div>
            <span class="text-[0.65rem] text-slate-400 uppercase tracking-wider bg-slate-50 px-2 py-1 rounded-full border border-slate-100">Live Preview</span>
        </div>
    </div>

    <!-- Main content container -->
    <div class="flex-1 min-h-0 p-4 bg-white flex flex-col overflow-hidden">
        <!-- Queue Section -->
        <div class="flex-1 min-h-0 flex flex-col overflow-hidden">
            <div class="flex items-start justify-between gap-3 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center text-green-600">
                        <x-lucide-users class="w-3.5 h-3.5" />
                    </div>
                    <div>
                        <div class="text-[0.72rem] font-semibold text-slate-800">Active Queue</div>
                        <div id="receptionNextQueueMeta" class="text-[0.65rem] text-slate-400 mt-0.5">Waiting patients</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <select id="receptionNextQueueDoctorSelect" class="w-[110px] rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="">Auto (any active doctor)</option>
                        @foreach ($activeCallNextDoctors as $doctorState)
                            @php
                                $doctorNameRaw = trim((string) ($doctorState->doctor_name ?? ''));
                                $doctorNameClean = preg_replace('/^\s*dr\.?\s*/i', '', $doctorNameRaw);
                                $doctorNameDisplay = $doctorNameClean !== '' ? $doctorNameClean : 'Doctor';
                                $doctorSpecialization = trim((string) ($doctorState->doctor_specialization ?? ''));
                            @endphp
                            <option value="{{ $doctorState->doctor_id }}">
                                Dr. {{ $doctorNameDisplay }}
                                @if ($doctorSpecialization !== '')
                                    - {{ $doctorSpecialization }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <button type="button" id="receptionNextQueueNextBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-green-600 text-white text-[0.65rem] font-semibold hover:bg-green-700 transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-60 disabled:pointer-events-none whitespace-nowrap">
                        <span id="receptionNextQueueNextSpinner" class="hidden w-3 h-3 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                        <x-lucide-megaphone class="w-3.5 h-3.5" />
                        <span id="receptionNextQueueNextLabel">Call next</span>
                    </button>
                </div>
            </div>

            <div id="receptionNextQueueInlineMessage" class="hidden mt-3 rounded-lg border px-3 py-2 text-[0.7rem]"></div>

            <div class="mt-2 flex-1 min-h-0 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50 pt-1">
                <ul id="receptionNextQueue" class="space-y-1">
                    <li class="text-center text-[0.7rem] text-slate-400 py-4">No patients in queue</li>
                </ul>
            </div>
        </div>

        <div class="my-3 border-t border-slate-200"></div>

        <!-- Appointments Section -->
        <div class="flex-1 min-h-0 flex flex-col overflow-hidden">
            <div class="flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center text-green-600">
                        <x-lucide-calendar-check class="w-3.5 h-3.5" />
                    </div>
                    <div>
                        <div class="text-[0.72rem] font-semibold text-slate-800">Upcoming Appointments</div>
                        <div id="receptionNextAppointmentsMeta" class="text-[0.65rem] text-slate-400 mt-0.5">Scheduled visits</div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <x-lucide-clock class="w-3 h-3 text-slate-300" />
                    <span class="text-[0.6rem] text-slate-400">Today</span>
                </div>
            </div>

            <div class="mt-2 flex-1 min-h-0 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50 pt-1">
                <ul id="receptionNextAppointments" class="space-y-1.5">
                    <li class="text-center text-[0.7rem] text-slate-400 py-4">No appointments scheduled</li>
                </ul>
            </div>
        </div>
    </div>
</div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var nextApptsList = document.getElementById('receptionNextAppointments')
                var nextApptsMeta = document.getElementById('receptionNextAppointmentsMeta')
                var nextQueueList = document.getElementById('receptionNextQueue')
                var nextQueueMeta = document.getElementById('receptionNextQueueMeta')
                var nextQueueBtn = document.getElementById('receptionNextQueueNextBtn')
                var nextQueueSpinner = document.getElementById('receptionNextQueueNextSpinner')
                var nextQueueLabel = document.getElementById('receptionNextQueueNextLabel')
                var nextQueueInlineMessage = document.getElementById('receptionNextQueueInlineMessage')
                var nextQueueDoctorSelect = document.getElementById('receptionNextQueueDoctorSelect')
                if (typeof apiFetch !== 'function') return

                function escapeHtml(input) {
                    var s = String(input == null ? '' : input)
                    return s
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;')
                }

                function isoDate(d) {
                    var yr = d.getFullYear()
                    var mo = String(d.getMonth() + 1).padStart(2, '0')
                    var da = String(d.getDate()).padStart(2, '0')
                    return yr + '-' + mo + '-' + da
                }

                function parseApiDate(value) {
                    if (!value) return null
                    var raw = String(value)
                    var dt = new Date(raw)
                    if (!isNaN(dt.getTime())) return dt
                    var cleaned = raw.replace(' ', 'T')
                    dt = new Date(cleaned)
                    if (!isNaN(dt.getTime())) return dt
                    return null
                }

                function nameForUser(user) {
                    if (!user) return ''
                    var parts = [user.firstname, user.middlename, user.lastname].filter(function (v) { return String(v || '').trim() !== '' })
                    var name = parts.join(' ').trim()
                    if (!name) name = String(user.email || '').trim()
                    if (!name) name = 'Patient'
                    return name
                }

                function formatTime(dt) {
                    try {
                        return dt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    } catch (_) {
                        return ''
                    }
                }

                function showNextQueueMessage(message, tone) {
                    if (!nextQueueInlineMessage) return
                    var kind = String(tone || 'info').toLowerCase()
                    nextQueueInlineMessage.textContent = message || ''
                    nextQueueInlineMessage.className = 'mt-2 rounded-lg border px-2.5 py-2 text-[0.72rem]'
                    if (!message) {
                        nextQueueInlineMessage.classList.add('hidden')
                        return
                    }
                    if (kind === 'error') {
                        nextQueueInlineMessage.classList.add('border-red-200', 'bg-red-50', 'text-red-700')
                    } else if (kind === 'success') {
                        nextQueueInlineMessage.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-700')
                    } else {
                        nextQueueInlineMessage.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-700')
                    }
                }

                function load() {
                    if (nextApptsList) nextApptsList.innerHTML = '<li class="text-[0.78rem] text-slate-400">Loading…</li>'
                    if (nextQueueList) nextQueueList.innerHTML = '<li class="text-[0.78rem] text-slate-400">Loading…</li>'
                    if (nextApptsMeta) nextApptsMeta.textContent = ''
                    if (nextQueueMeta) nextQueueMeta.textContent = ''
                    if (nextQueueBtn) nextQueueBtn.disabled = true

                    var now = new Date()
                    var today = isoDate(now)

                    var queueSnapshotUrl = "{{ route('queue.display.data') }}" + '?date=' + encodeURIComponent(today)
                    var apptsUrl = "{{ url('/api/appointments') }}" + '?start_date=' + encodeURIComponent(today) + '&end_date=' + encodeURIComponent(today) + '&status=confirmed&per_page=10'

                    Promise.all([
                        apiFetch(queueSnapshotUrl, { method: 'GET' }).then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) }).catch(function () { return { ok: false, data: null } }),
                        apiFetch(apptsUrl, { method: 'GET' }).then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) }).catch(function () { return { ok: false, data: null } })
                    ])
                        .then(function (results) {
                            var queuePayload = null
                            if (results[0] && results[0].ok && results[0].data) {
                                queuePayload = results[0].data
                            }

                            var appts = []
                            if (results[1] && results[1].ok && results[1].data) {
                                appts = Array.isArray(results[1].data.data) ? results[1].data.data : []
                            }

                            if (nextApptsList) {
                                var upcoming = appts.slice().map(function (a) {
                                    var dt3 = parseApiDate(a && a.appointment_datetime ? a.appointment_datetime : null)
                                    return { row: a, dt: dt3 }
                                }).filter(function (x) {
                                    return x.dt && x.dt.getTime() >= now.getTime()
                                }).sort(function (a, b) {
                                    return a.dt.getTime() - b.dt.getTime()
                                }).slice(0, 5)

                                if (!upcoming.length) {
                                    nextApptsList.innerHTML = '<li class="text-[0.72rem] text-slate-400">No upcoming appointments.</li>'
                                } else {
                                    nextApptsList.innerHTML = upcoming.map(function (x) {
                                        var patient = x.row && x.row.patient ? nameForUser(x.row.patient) : 'Patient'
                                        var doctor = x.row && x.row.doctor ? nameForUser(x.row.doctor) : 'Doctor'
                                        var t = formatTime(x.dt)
                                        return '<li class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-white border border-slate-100 shadow-sm">' +
                                            '<div class="flex items-center gap-2.5 min-w-0">' +
                                                '<span class="text-[0.72rem] font-semibold text-green-700 flex-shrink-0">' + escapeHtml(t) + '</span>' +
                                                '<span class="text-[0.72rem] text-slate-700 font-medium truncate">' + escapeHtml(patient) + '</span>' +
                                            '</div>' +
                                            '<span class="text-[0.65rem] text-slate-400 flex-shrink-0">' + escapeHtml(doctor) + '</span>' +
                                        '</li>'
                                    }).join('')
                                }
                            }

                            if (nextQueueList) {
                                function queueLabel(q) {
                                    if (q && q.queue_code) return String(q.queue_code)
                                    var n = q && q.queue_number != null ? String(q.queue_number) : ''
                                    while (n.length && n.length < 3) n = '0' + n
                                    return n || '---'
                                }

                                function waitLabel(minutes) {
                                    if (minutes == null) return ''
                                    var n = parseInt(minutes, 10)
                                    if (isNaN(n) || n < 1) return ''
                                    return 'Est. ' + n + 'min - ' + (n + 5) + 'min'
                                }

                                var serving = queuePayload && Array.isArray(queuePayload.now_serving) ? queuePayload.now_serving : []
                                var next = queuePayload && Array.isArray(queuePayload.next) ? queuePayload.next.slice(0, 5) : []
                                var waitingCount = queuePayload && queuePayload.counts && queuePayload.counts.waiting != null
                                    ? parseInt(String(queuePayload.counts.waiting), 10)
                                    : next.length
                                if (isNaN(waitingCount) || waitingCount < 0) waitingCount = next.length

                                var nowServingLabels = serving.slice(0, 4).map(function (q) {
                                    var patientName = q && q.patient && q.patient.name ? String(q.patient.name) : 'Patient'
                                    var doctorName = q && q.doctor && q.doctor.name ? String(q.doctor.name) : ''
                                    return queueLabel(q) + ' ' + patientName + (doctorName ? (' - ' + doctorName) : '')
                                })

                                var html = ''
                                html += `
<li class="space-y-3 pt-1">
    <div>
        <div class="text-[0.65rem] font-semibold text-slate-500 uppercase tracking-wider mb-2.5">Now serving</div>
        ${nowServingLabels.length 
            ? nowServingLabels.map(label => `
        <div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-lg bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-100 mb-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
            <span class="text-[0.72rem] text-emerald-800 font-medium">${escapeHtml(label)}</span>
        </div>`).join('')
            : '<div class="text-[0.72rem] text-slate-400 py-1.5">-</div>'
        }
    </div>
</li>`;

if (!next.length) {
    html += '<li class="text-[0.72rem] text-slate-400 pt-2">No one waiting.</li>';
} else {
    html += `
<li class="space-y-2 pt-3">
    <div class="text-[0.65rem] font-semibold text-slate-500 uppercase tracking-wider mb-2">Next in line</div>
    <div class="space-y-1.5">
        ${next.map(function (q) {
            var nm = q && q.patient && q.patient.name ? String(q.patient.name) : 'Patient';
            var qn = queueLabel(q);
            var doctorName = q && q.doctor && q.doctor.name ? String(q.doctor.name) : 'Doctor';
            var estLabel = waitLabel(q && q.estimated_wait_minutes != null ? q.estimated_wait_minutes : null);
            return `
        <div class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg bg-white border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2 min-w-0">
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.6rem] font-bold border border-indigo-100 text-indigo-700 bg-indigo-50 flex-shrink-0">
                    #${escapeHtml(qn)}
                </span>
                <span class="text-[0.72rem] text-slate-700 font-medium truncate">${escapeHtml(nm)}</span>
                <span class="text-[0.65rem] text-slate-400 hidden sm:inline">— ${escapeHtml(doctorName)}</span>
            </div>
            ${estLabel ? `<span class="text-[0.6rem] text-slate-400 flex-shrink-0">${escapeHtml(estLabel)}</span>` : ''}
        </div>`;
        }).join('')}
    </div>
</li>`;
}


                                nextQueueList.innerHTML = html
                                if (nextQueueBtn) nextQueueBtn.disabled = waitingCount < 1
                                if (nextQueueBtn) nextQueueBtn.setAttribute('data-can-call', waitingCount > 0 ? '1' : '0')
                                if (nextQueueLabel) nextQueueLabel.textContent = 'Call next'
                            }

                            var stamp = 'Updated ' + now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                            if (nextApptsMeta) nextApptsMeta.textContent = stamp
                            if (nextQueueMeta) nextQueueMeta.textContent = stamp
                        })
                        .catch(function () {
                            if (nextApptsList) nextApptsList.innerHTML = '<li class="text-[0.78rem] text-slate-500">Unable to load.</li>'
                            if (nextQueueList) nextQueueList.innerHTML = '<li class="text-[0.78rem] text-slate-500">Unable to load.</li>'
                            if (nextQueueBtn) nextQueueBtn.disabled = true
                            showNextQueueMessage('Unable to refresh queue snapshot right now.', 'error')
                        })
                }

                function setNextQueueSubmitting(isSubmitting) {
                    if (nextQueueSpinner) nextQueueSpinner.classList.toggle('hidden', !isSubmitting)
                    if (nextQueueBtn) {
                        if (isSubmitting) {
                            nextQueueBtn.disabled = true
                        } else {
                            var canCall = String(nextQueueBtn.getAttribute('data-can-call') || '') === '1'
                            nextQueueBtn.disabled = !canCall
                        }
                    }
                }

                function readApiResult(response) {
                    return response.json().then(function (d) {
                        return { ok: response.ok, data: d }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                }

                function callNextOnce(doctorId) {
                    var payload = {}
                    if (doctorId) payload.doctor_id = doctorId
                    return apiFetch("{{ url('/api/queues/call-next') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    })
                        .then(readApiResult)
                        .then(function (result) {
                            if (!result.ok) {
                                var code = result && result.data && result.data.code ? String(result.data.code) : ''
                                var graceful = code === 'SERVING_SLOTS_FULL' || code === 'NO_AVAILABLE_SLOTS' || code === 'NO_ELIGIBLE_WAITING' || code === 'NO_ACTIVE_DOCTORS'
                                if (graceful) return { called: false, tone: 'error', message: 'No available slots to call next right now.' }
                                var message = (result && result.data && result.data.message) ? String(result.data.message) : 'Unable to call next right now.'
                                throw new Error(message)
                            }
                            return { called: true, message: 'Next patient is now serving.' }
                        })
                }

                if (nextQueueBtn) {
                    nextQueueBtn.addEventListener('click', function () {
                        var canCall = String(nextQueueBtn.getAttribute('data-can-call') || '') === '1'
                        if (!canCall) return

                        var selectedDoctorId = nextQueueDoctorSelect ? parseInt(String(nextQueueDoctorSelect.value || '').trim(), 10) : 0
                        setNextQueueSubmitting(true)

                        callNextOnce(selectedDoctorId)
                            .then(function (state) {
                                var message = state && state.message ? state.message : 'Call next finished.'
                                var tone = state && state.tone ? String(state.tone) : (state && state.called ? 'success' : 'info')
                                showNextQueueMessage(message, tone)
                                load()
                            })
                            .catch(function (err) {
                                var message = (err && err.message) ? err.message : 'Network error while calling next.'
                                showNextQueueMessage(message, 'error')
                            })
                            .finally(function () {
                                setNextQueueSubmitting(false)
                            })
                    })
                }

                // ── Today's Transactions with Server-side Pagination ──
                var txTableBody = document.getElementById('receptionTodaysTransactionsTableBody')
                var transactions = []
                var txPerPage = 10
                var txCurrentPage = 1
                var txVisibleCount = 6
                var txLastPage = 1
                var txTotal = 0

                function renderTxPagination() {
                    var pagination = document.getElementById('receptionTransactionsPagination')
                    if (!pagination) return
                    if (txTotal === 0) {
                        pagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                        return
                    }
                    var totalPages = txLastPage
                    var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
                    var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
                    var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
                    var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
                    var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + txTotal + ' entries</span>'
                    html += '<button type="button" class="' + (txCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (txCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
                    var windowStart = Math.max(1, txCurrentPage - Math.floor(txVisibleCount / 2))
                    var windowEnd = Math.min(windowStart + txVisibleCount - 1, totalPages)
                    if (windowEnd - windowStart + 1 < txVisibleCount) windowStart = Math.max(1, windowEnd - txVisibleCount + 1)
                    for (var i = windowStart; i <= windowEnd; i++) {
                        html += '<button type="button" class="' + (i === txCurrentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
                    }
                    if (windowEnd < totalPages) {
                        html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>'
                    }
                    html += '<button type="button" class="' + (txCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (txCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
                    pagination.innerHTML = html
                    pagination.querySelectorAll('button[data-page]').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            var p = btn.getAttribute('data-page')
                            if (p === 'prev' && txCurrentPage > 1) { txCurrentPage--; loadTransactions() }
                            else if (p === 'next' && txCurrentPage < totalPages) { txCurrentPage++; loadTransactions() }
                            else if (p === 'next-window') {
                                var nextStart = Math.min(windowEnd + 1, totalPages)
                                txCurrentPage = nextStart
                                loadTransactions()
                            }
                            else if (p !== 'prev' && p !== 'next') { txCurrentPage = parseInt(p, 10); loadTransactions() }
                        })
                    })
                }

                function renderTransactions() {
                    if (!txTableBody) return
                    if (!transactions.length) {
                        txTableBody.innerHTML = '<tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">No transactions recorded today.</td></tr>'
                        renderTxPagination()
                        return
                    }

                    var html = ''
                    transactions.forEach(function (tx) {
                        var dateStr = txDatePart(tx)
                        var ref = tx.transaction_reference || tx.reference_number || tx.invoice_number || '-'
                        var patient = txPatientName(tx)
                        var appt = tx && tx.appointment ? tx.appointment : null
                        var rawType = appt && appt.appointment_type ? String(appt.appointment_type).toLowerCase().trim() : ''
                        var typeLabel = (rawType === 'walk_in' || rawType === 'walkin') ? 'Walk-in' : 'Scheduled'
                        var gross = tx.amount || 0
                        var disc = tx.discount_amount || 0
                        var net = parseFloat(gross) - parseFloat(disc)
                        html += '<tr class="cursor-pointer hover:bg-green-50/50" onclick="window.location.href=\'{{ route('dashboard', ['role' => 'receptionist', 'section' => 'record-payment']) }}\'">' +
                            '<td class="px-4 py-2">' + escapeHtml(dateStr) + '</td>' +
                            '<td class="px-4 py-2">' + escapeHtml(ref) + '</td>' +
                            '<td class="px-4 py-2">' + escapeHtml(patient) + '</td>' +
                            '<td class="px-4 py-2"><span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.68rem] font-medium border ' + (typeLabel === 'Walk-in' ? 'bg-sky-50 text-sky-700 border-sky-100' : 'bg-purple-50 text-purple-700 border-purple-100') + '">' + escapeHtml(typeLabel) + '</span></td>' +
                            '<td class="px-4 py-2 text-right font-medium text-slate-700">₱' + escapeHtml(net.toFixed(2)) + '</td>' +
                        '</tr>'
                    })
                    txTableBody.innerHTML = html
                    renderTxPagination()
                }

                function txDatePart(tx) {
                    var raw = tx && tx.transaction_datetime ? String(tx.transaction_datetime) : ''
                    if (!raw) raw = tx && tx.created_at ? String(tx.created_at) : ''
                    return raw ? raw.replace('T', ' ').slice(0, 16) : '-'
                }

                function txPatientName(tx) {
                    var appt = tx && tx.appointment ? tx.appointment : null
                    if (!appt) return 'Patient'
                    var patient = appt.patient
                    if (!patient) return 'Patient'
                    var parts = [patient.firstname, patient.middlename, patient.lastname].filter(function (v) { return String(v || '').trim() !== '' })
                    var name = parts.join(' ').trim()
                    return name || (patient.email ? String(patient.email) : 'Patient')
                }

                function txServiceSummary(tx) {
                    var appt = tx && tx.appointment ? tx.appointment : null
                    var services = appt && Array.isArray(appt.services) ? appt.services : []
                    var names = services.map(function (s) { return String((s && s.service_name) ? s.service_name : '').trim() }).filter(function (v) { return v !== '' })
                    if (!names.length) return '-'
                    return names.join(', ')
                }

                function loadTransactions(page) {
                    if (typeof apiFetch !== 'function') return
                    page = page || txCurrentPage
                    var now = new Date()
                    var yyyy = now.getFullYear()
                    var mm = String(now.getMonth() + 1).padStart(2, '0')
                    var dd = String(now.getDate()).padStart(2, '0')
                    var today = yyyy + '-' + mm + '-' + dd
                    var url = "{{ url('/api/transactions') }}" + '?per_page=10&page=' + page + '&start_date=' + encodeURIComponent(today) + '&end_date=' + encodeURIComponent(today)

                    apiFetch(url, { method: 'GET' })
                        .then(function (response) {
                            return response.json().then(function (data) { return { ok: response.ok, data: data } }).catch(function () { return { ok: false, data: null } })
                        })
                        .then(function (result) {
                            if (!result.ok || !result.data) {
                                if (txTableBody) txTableBody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Unable to load transactions.</td></tr>'
                                return
                            }
                            transactions = Array.isArray(result.data.data) ? result.data.data.slice() : (Array.isArray(result.data) ? result.data.slice() : [])
                            txCurrentPage = result.data.current_page || page
                            txLastPage = result.data.last_page || 1
                            txTotal = result.data.total || transactions.length
                            renderTransactions()
                        })
                        .catch(function () {
                            if (txTableBody) txTableBody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Unable to load transactions.</td></tr>'
                        })
                }

                loadTransactions(1)

                load()

                if (typeof window.Echo !== 'undefined' && window.Echo) {
                    try {
                        window.Echo.private('queue.all')
                            .listen('.queue.updated', function () {
                                load()
                            })
                    } catch (_) {}
                }
            })
        </script>
    @else
     

        @if ($sectionKey === 'queue-management')
            @include('dashviews.receptionist.reception_queue_management')
        @elseif ($sectionKey === 'register-patient')
            @include('dashviews.receptionist.reception_register_patient')
        @elseif ($sectionKey === 'book-appointment')
            @include('dashviews.receptionist.reception_book_appointment')
        @elseif ($sectionKey === 'walk-ins')
            @include('dashviews.receptionist.reception_walk_ins')
        @elseif ($sectionKey === 'record-payment')
            @include('dashviews.receptionist.reception_record_payment')
        @elseif ($sectionKey === 'verification-oversight')
            @include('dashviews.receptionist.reception_verification_approvals')
        @elseif ($sectionKey === 'settings-reception')
            @include('dashviews.receptionist.reception_settings')
        @endif
    @endif
</div>
