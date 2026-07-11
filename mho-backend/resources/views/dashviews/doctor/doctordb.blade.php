@php
    $metrics = $doctorMetrics ?? [];
    $appointmentsToday = (int) ($metrics['appointmentsToday'] ?? 0);
    $queueToday = (int) ($metrics['queueToday'] ?? 0);
    $completedToday = (int) ($metrics['completedToday'] ?? 0);
    $pendingPrescriptionsToday = (int) ($metrics['pendingPrescriptionsToday'] ?? 0);
    $unreadNotificationsCount = (int) ($metrics['unreadNotificationsCount'] ?? 0);
    $recentAppointments = $doctorRecentAppointments ?? [];
    $recentVisits = $doctorRecentVisits ?? [];
    $recentQueue = $doctorRecentQueue ?? [];
    $todayAppointments = collect($doctorTodayAppointments ?? [])->sortByDesc(function ($a) {
        return optional($a->appointment_datetime)->format('Y-m-d H:i:s') ?? '';
    })->values();
    $todayQueue = $doctorTodayQueue ?? [];
    $activeQueue = collect($todayQueue)->reject(function ($q) {
        return strtolower((string) ($q->status ?? '')) === 'on_hold';
    })->sortBy(function ($queue) {
        $status = strtolower((string) ($queue->status ?? ''));
        $rank = match ($status) {
            'serving' => 1,
            'waiting', 'skipped' => 3,
            'consulted' => 4,
            'done' => 5,
            default => 6,
        };
        $priority = (int) ($queue->priority_level ?? 5);
        $number = (int) ($queue->queue_number ?? 999999);
        return str_pad((string) $rank, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $priority, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
    })->values();
    $onHoldQueue = collect($todayQueue)->filter(function ($q) {
        return strtolower((string) ($q->status ?? '')) === 'on_hold';
    })->sortBy(function ($queue) {
        $status = strtolower((string) ($queue->status ?? ''));
        $rank = match ($status) {
            'on_hold' => 2,
            default => 3,
        };
        $priority = (int) ($queue->priority_level ?? 5);
        $number = (int) ($queue->queue_number ?? 999999);
        return str_pad((string) $rank, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $priority, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
    })->values();
    $recentNotifications = $doctorRecentNotifications ?? collect();
    $todayIso = now()->toDateString();
    $currentUserUuidQuery = request()->query('user_uuid') ?: request()->query('user_id');
    $todayUpcomingAppointments = collect($recentAppointments)
        ->filter(function ($appointment) use ($todayIso) {
            $date = optional($appointment->appointment_datetime)->format('Y-m-d');
            return $date && $date >= $todayIso && strtolower((string) ($appointment->appointment_type ?? '')) === 'scheduled';
        })
        ->values();

    $formatUserName = function ($user) {
        if (! $user) {
            return '';
        }
        $parts = array_filter([
            $user->firstname ?? null,
            $user->middlename ?? null,
            $user->lastname ?? null,
        ], function ($v) {
            return (string) $v !== '';
        });
        $name = trim(implode(' ', $parts));
        if ($name !== '') {
            return $name;
        }

        $email = trim((string) ($user->email ?? ''));
        return $email !== '' ? $email : 'Patient';
    };

    $sectionKey = $section ?? 'overview';

    $effectiveSectionKey = $sectionKey;

    if ($effectiveSectionKey === 'my-schedule') {
        $effectiveSectionKey = 'appointments';
    } elseif ($effectiveSectionKey === 'history') {
        $effectiveSectionKey = 'visits';
    }

    $sectionTitles = [
        'my-patients' => 'My patients',
        'appointments' => 'My Appointments',
        'queue' => 'Queue',
        'visits' => 'History',
        'history' => 'History',
        'prescriptions' => 'Prescription',
        'my-activity' => 'My activity',
        'patient-records' => 'Patient Records',
        'consultation' => 'Consultation',
        'settings-doctor' => 'Settings',
    ];

    $sectionSubtitles = [
        'my-patients' => 'Patients you are actively seeing or have seen recently.',
        'appointments' => 'Review upcoming and recent appointments.',
        'queue' => 'See today’s queue and recent queue entries.',
        'visits' => 'View past patient visits and records.',
        'history' => 'View past patient visits and records.',
        'prescriptions' => 'Review prescriptions you have issued.',
        'my-activity' => 'High-level view of your recent clinical activity.',
        'patient-records' => 'View and manage patient records.',
        'consultation' => 'Consult with a selected patient and record notes.',
        'settings-doctor' => 'Update your profile, password, and signature.',
    ];
@endphp

<div class="space-y-6">
    @if ($sectionKey === 'overview')
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">Doctor Dashboard</h1>
            <p class="text-sm text-slate-500">Today’s appointments and queue list for your clinic day.</p>
        </div>

    <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
            <div class="bg-white border border-slate-200 rounded-[18px] p-5 lg:col-span-2 shadow-[0_2px_10px_rgba(15,23,42,0.04)] flex flex-col">
                <div class="flex items-center justify-between mb-3 flex-shrink-0">
                    <h2 class="text-sm font-semibold text-slate-900">Today&apos;s schedule</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Consultations</span>
                        <button type="button" id="docScheduleRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                            <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                            Refresh
                        </button>
                    </div>
                </div>
                <div id="doctorMetricsContainer" class="grid gap-3 grid-cols-1 sm:grid-cols-3 text-sm text-slate-600 flex-shrink-0">
                    <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[0.78rem] text-slate-500">Today&rsquo;s Patients</span>
                            <x-lucide-calendar-check class="w-[17px] h-[17px] text-green-600" />
                        </div>
                        <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($appointmentsToday) }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[0.78rem] text-slate-500">In queue</span>
                            <x-lucide-users class="w-[17px] h-[17px] text-green-600" />
                        </div>
                        <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($queueToday) }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[0.78rem] text-slate-500">Completed today</span>
                            <x-lucide-check-circle class="w-[17px] h-[17px] text-green-600" />
                        </div>
                        <div class="font-serif font-bold text-xl text-slate-900">{{ number_format($completedToday) }}</div>
                    </div>
                </div>

                <div class="mt-5 border border-slate-100 rounded-xl bg-white overflow-hidden flex-shrink-0">
                    <div class="h-[12rem] overflow-y-auto overflow-x-hidden scrollbar-hidden">
                    <table class="min-w-full text-left text-xs text-slate-600">
                        <thead class="sticky top-0 z-10 bg-white">
                            <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                                <th class="py-2 px-3 font-semibold">Time</th>
                                <th class="py-2 px-3 font-semibold">Patient</th>
                                <th class="py-2 px-3 font-semibold">Type</th>
                                <th class="py-2 px-3 font-semibold">Status</th>
                                <th class="py-2 px-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="doctorScheduleTbody">
                            @forelse ($todayAppointments as $appointment)
                                @php
                                    $patientName = $formatUserName($appointment->patient);
                                    $time = optional($appointment->appointment_datetime)->format('H:i') ?? '-';
                                    $typeLabel = $appointment->appointment_type ? ucfirst(str_replace('_', '-', $appointment->appointment_type)) : '-';
                                    $isWalkIn = strtolower((string) ($appointment->appointment_type ?? '')) === 'walk_in';
                                    if ($isWalkIn && $appointment->queue) {
                                        $statusLabel = ucfirst(str_replace('_', ' ', $appointment->queue->status));
                                        $statusKey = strtolower((string) ($appointment->queue->status ?? ''));
                                    } else {
                                        $statusLabel = $appointment->status ? ucfirst(str_replace('_', ' ', $appointment->status)) : '-';
                                        $statusKey = strtolower((string) ($appointment->status ?? ''));
                                    }
                                    $apptStatusColors = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'confirmed' => 'border-orange-200 bg-orange-50 text-orange-700',
                                        'completed' => 'border-green-200 bg-green-50 text-green-700',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                        'no_show' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'consulted' => 'border-purple-200 bg-purple-50 text-purple-700',
                                        'waiting' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'serving' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'skipped' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'on_hold' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    ];
                                    $apptStatusColor = $apptStatusColors[$statusKey] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                    $showScheduleActions = $statusKey !== 'completed';
                                    $consultationParams = [
                                        'role' => 'doctor',
                                        'section' => 'consultation',
                                        'appointment_id' => $appointment->appointment_id,
                                    ];
                                    if ($currentUserUuidQuery) {
                                        $consultationParams['user_uuid'] = $currentUserUuidQuery;
                                    }
                                @endphp
                                <tr class="border-b border-slate-100 last:border-0">
                                    <td class="py-2 px-3 text-[0.78rem] text-slate-500">{{ $time }}</td>
                                    <td class="py-2 px-3 text-[0.78rem] text-slate-700">{{ $patientName }}</td>
                                    <td class="py-2 px-3 text-[0.78rem] text-slate-500">{{ $typeLabel }}</td>
                                    <td class="py-2 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-medium border {{ $apptStatusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3">
                                        @if ($showScheduleActions)
                                            <div class="flex flex-wrap gap-1.5">
                                                <button type="button"
                                                    class="doc-details-btn inline-flex items-center justify-center gap-1 rounded-lg border border-slate-200 bg-white px-2 py-1 text-[0.7rem] font-medium text-slate-700 hover:bg-slate-50"
                                                    data-appointment='@json($appointment)'>
                                                    <x-lucide-info class="w-3.5 h-3.5" />
                                                    Details
                                                </button>
                                                <a href="{{ route('dashboard', $consultationParams) }}"
                                                    data-spa-nav="1"
                                                    class="inline-flex items-center justify-center gap-1 rounded-lg border border-green-200 bg-green-50 px-2 py-1 text-[0.7rem] font-semibold text-green-700 hover:bg-green-100">
                                                    <x-lucide-play class="w-3.5 h-3.5" />
                                                    Start
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-[0.72rem] text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-[0.78rem] text-slate-400">
                                        No appointments scheduled for today.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>

                <div class="mt-5 flex-1 flex flex-col min-h-0">
                    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden flex-1 flex flex-col">
                        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50/60 to-white flex-shrink-0">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                                        <x-lucide-calendar-clock class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Upcoming Appointments</h2>
                                        <p class="text-[0.7rem] text-slate-500 mt-0.5">Scheduled visits</p>
                                    </div>
                                </div>
                                <span id="doctorUpcomingCounter" class="text-[0.65rem] text-slate-400 uppercase tracking-wider bg-slate-50 px-2 py-1 rounded-full border border-slate-100"></span>
                            </div>
                        </div>
                        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50 p-4">
                            <div id="doctorUpcomingAppointmentsAll">
                                <p class="text-[0.72rem] text-slate-400 animate-pulse">Loading…</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 px-4 py-3 border-t border-slate-100 text-center">
                            <button id="doctorUpcomingSeeMore" type="button" class="text-[0.7rem] font-medium text-green-600 hover:text-green-700 disabled:text-slate-300 disabled:cursor-default cursor-pointer" disabled>See more</button>
                        </div>
                    </div>
                </div>
            </div>

           
           
           
            <div class="space-y-4">
    {{-- ══════ Queue List card ══════ --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden h-[22rem] flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-orange-50/60 to-white flex-shrink-0">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600">
                        <x-lucide-list class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Queue List</h2>
                        <p class="text-[0.7rem] text-slate-500 mt-0.5">Today's Patients</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if (count($activeQueue))
                    <div class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-green-50 border border-green-100 text-green-700 text-[0.6rem] font-semibold whitespace-nowrap">
                        {{ count($activeQueue) }} {{ Str::plural('patient', count($activeQueue)) }}
                    </div>
                    @endif
                    <button id="doctorOverviewCallNextButton" type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-3 py-2 text-[0.72rem] font-semibold text-white hover:bg-green-700 disabled:opacity-60 disabled:hover:bg-green-600 min-w-[112px] relative">
                        <span id="doctorOverviewCallNextSpinner" class="hidden absolute w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                        <span id="doctorOverviewCallNextContent" class="inline-flex items-center gap-2">
                            <x-lucide-megaphone class="w-3.5 h-3.5" />
                            Call next
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50">
            @if (count($activeQueue))
                <div id="doctorActiveQueueContainer" class="divide-y divide-slate-100">
                    @foreach ($activeQueue as $queue)
                        @php
                            $patientName = $formatUserName(optional(optional($queue->appointment)->patient));
                            $dateKey = optional($queue->queue_datetime)->format('Y-m-d') ?? '-';
                            $timeKey = optional($queue->queue_datetime)->format('H:i') ?? '-';
                            $statusLabel = $queue->status ? ucfirst(str_replace('_', ' ', $queue->status)) : '-';
                            $statusColors = [
                                'waiting' => 'border-orange-200 bg-orange-50 text-orange-700',
                                'serving' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'consulted' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-100',
                                'no_show' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'skipped' => 'bg-orange-50 text-orange-700 border-orange-100',
                                'on_hold' => 'bg-purple-50 text-purple-700 border-purple-100',
                            ];
                            $statusColor = $statusColors[strtolower($queue->status)] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                        @endphp
                        <div class="px-5 py-3.5 hover:bg-slate-50/50 transition-all duration-150">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 text-[0.8rem] font-semibold text-slate-800">
                                            <x-lucide-hash class="w-3.5 h-3.5 text-slate-400" />
                                            {{ $queue->queue_code }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-medium border {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <x-lucide-user class="w-3 h-3 text-slate-400" />
                                        <span class="text-[0.75rem] text-slate-600 truncate">{{ $patientName }}</span>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="flex items-center gap-1.5 text-[0.7rem] text-slate-500">
                                        <x-lucide-calendar class="w-3 h-3 text-slate-400" />
                                        <span>{{ $dateKey }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-1 text-[0.7rem] text-slate-500">
                                        <x-lucide-clock class="w-3 h-3 text-slate-400" />
                                        <span>{{ $timeKey }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div id="doctorActiveQueueContainer" class="flex flex-col items-center justify-center h-full px-4 text-center">
                    <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-2">
                        <x-lucide-calendar-x class="w-5 h-5 text-slate-300" />
                    </div>
                    <p class="text-[0.78rem] font-medium text-slate-500">No active queue entries</p>
                    <p class="text-[0.68rem] text-slate-400 mt-0.5">Queue is empty</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════ On Hold card (same styling as Queue List) ══════ --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden h-[22rem] flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-purple-50/60 to-white flex-shrink-0">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                        <x-lucide-pause class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 tracking-tight">On Hold</h2>
                        <p class="text-[0.7rem] text-slate-500 mt-0.5">Patients currently on hold</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if (count($onHoldQueue))
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-purple-50 border border-purple-100 text-purple-700 text-[0.7rem] font-semibold">
                        {{ count($onHoldQueue) }} {{ Str::plural('patient', count($onHoldQueue)) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50">
            <div id="doctorOnHoldContainer">
                @if (count($onHoldQueue))
                    <div class="divide-y divide-slate-100">
                        @foreach ($onHoldQueue as $queue)
                            @php
                                $patientName = $formatUserName(optional(optional($queue->appointment)->patient));
                                $timeKey = optional($queue->queue_datetime)->format('H:i') ?? '-';
                            @endphp
                            <div class="px-5 py-3.5 hover:bg-slate-50/50 transition-all duration-150">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="inline-flex items-center gap-1.5 text-[0.75rem] font-semibold text-slate-700">
                                            <x-lucide-hash class="w-3 h-3 text-slate-400" />
                                            {{ $queue->queue_code }}
                                        </span>
                                        <span class="text-[0.72rem] text-slate-600 truncate">{{ $patientName }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="text-[0.65rem] text-slate-400">{{ $timeKey }}</span>
                                        <button type="button"
                                            class="call-on-hold-btn inline-flex items-center justify-center gap-1 rounded-lg border border-green-200 bg-green-50 px-2.5 py-1 text-[0.65rem] font-semibold text-green-700 hover:bg-green-100"
                                            data-queue-id="{{ $queue->queue_id }}"
                                            data-doctor-id="{{ (int) ($currentUser->user_id ?? 0) }}">
                                            <x-lucide-megaphone class="w-3 h-3" />
                                            Call
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div id="doctorOnHoldContainer" class="flex flex-col items-center justify-center h-full px-4 text-center">
                        <div class="w-10 h-10 rounded-full bg-purple-50 border border-purple-100 flex items-center justify-center mb-2">
                            <x-lucide-pause class="w-5 h-5 text-purple-300" />
                        </div>
                        <p class="text-[0.78rem] font-medium text-slate-500">No patients on hold</p>
                        <p class="text-[0.68rem] text-slate-400 mt-0.5">On hold queue is empty</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- Details Modal --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="appointmentDetailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm" style="display:none;">
    <div class="relative w-full max-w-3xl mx-4 max-h-[85vh] overflow-y-auto rounded-2xl bg-white shadow-2xl">
        <!-- Close button -->
        <button type="button" id="appointmentDetailsModalClose" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 z-10">
            <x-lucide-x class="w-4 h-4" />
        </button>
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200">
            {{-- Left panel: Patient profile --}}
            <div class="p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600">
                        <x-lucide-user class="w-5 h-5" />
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900" id="modalPatientName">-</div>
                    </div>
                </div>
                <div class="space-y-2 text-[0.75rem]">
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Contact</span>
                        <span class="text-slate-800 font-medium" id="modalPatientContact">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Sex</span>
                        <span class="text-slate-800 font-medium" id="modalPatientSex">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Birthdate</span>
                        <span class="text-slate-800 font-medium" id="modalPatientBirthdate">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Age</span>
                        <span class="text-slate-800 font-medium" id="modalPatientAge">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Address</span>
                        <span class="text-slate-800 font-medium text-right max-w-[200px]" id="modalPatientAddress">-</span>
                    </div>
                </div>
            </div>
            {{-- Right panel: Appointment / Queue details --}}
            <div class="p-5">
                <div class="text-sm font-semibold text-slate-900 mb-3">Appointment Details</div>
                <div class="space-y-2.5 text-[0.75rem]">
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Type</span>
                        <span class="text-slate-800 font-medium" id="modalApptType">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Status</span>
                        <span id="modalApptStatus">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Date</span>
                        <span class="text-slate-800 font-medium" id="modalApptDate">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Time</span>
                        <span class="text-slate-800 font-medium" id="modalApptTime">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Queue Code</span>
                        <span class="text-slate-800 font-medium" id="modalQueueCode">-</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500">Queue Status</span>
                        <span class="text-slate-800 font-medium" id="modalQueueStatus">-</span>
                    </div>
                    <div class="py-1.5 border-b border-slate-50">
                        <div class="text-slate-500 mb-1">Services</div>
                        <div class="text-slate-800 font-medium" id="modalApptServices">-</div>
                    </div>
                    <div class="py-1.5">
                        <div class="text-slate-500 mb-1">Reason for Visit</div>
                        <div class="text-slate-700 text-[0.7rem] leading-relaxed" id="modalApptReason">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function escapeHtml(value) {
                    return String(value == null ? '' : value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;')
                }

                // ── Upcoming Appointments Pagination ─────────────────────────
                var upcomingCurrentPage = 1
                var upcomingPerPage = 10
                var upcomingLastPage = 1
                var upcomingTotal = 0
                var doctorIdForApi = {{ (int) ($currentUser->user_id ?? 0) }}

                function loadUpcomingAppointments(page) {
                    var container = document.getElementById('doctorUpcomingAppointmentsAll')
                    if (!container) return
                    container.innerHTML = '<p class="text-[0.72rem] text-slate-400 animate-pulse">Loading…</p>'

                    apiFetch("{{ url('/api/appointments') }}?per_page=" + upcomingPerPage + "&page=" + page + "&doctor_id=" + doctorIdForApi + "&appointment_type=scheduled&upcoming_only=1")
                        .then(function (r) { return r.json() })
                        .then(function (result) {
                            if (!result || !result.data) {
                                container.innerHTML = '<p class="text-[0.72rem] text-slate-400">No scheduled appointments found.</p>'
                                updateUpcomingSeeMore()
                                return
                            }
                            var data = result.data
                            upcomingCurrentPage = result.current_page || page
                            upcomingLastPage = result.last_page || 1
                            upcomingTotal = result.total || 0

                            if (!data.length) {
                                container.innerHTML = '<p class="text-[0.72rem] text-slate-400">No scheduled appointments found.</p>'
                            } else {
                                var statusColors = {
                                    pending: 'bg-amber-50 text-amber-700 border-amber-200',
                                    confirmed: 'bg-blue-50 text-blue-700 border-blue-200',
                                    completed: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    cancelled: 'bg-red-50 text-red-700 border-red-200',
                                    no_show: 'bg-slate-100 text-slate-600 border-slate-200',
                                    consulted: 'bg-green-50 text-green-700 border-green-100'
                                }
                                var html = '<table class="min-w-full text-left text-[0.7rem] text-slate-600">' +
                                    '<thead>' +
                                    '<tr class="border-b border-slate-200 text-[0.6rem] uppercase tracking-widest text-slate-400">' +
                                    '<th class="py-1.5 pr-3 font-semibold">Date</th>' +
                                    '<th class="py-1.5 pr-3 font-semibold">Time</th>' +
                                    '<th class="py-1.5 pr-3 font-semibold">Patient</th>' +
                                    '<th class="py-1.5 pr-3 font-semibold">Status</th>' +
                                    '<th class="py-1.5 font-semibold">Actions</th>' +
                                    '</tr></thead><tbody>'
                                data.forEach(function (a) {
                                    var patient = a.patient || {}
                                    var parts = [patient.firstname, patient.middlename, patient.lastname].filter(function (v) { return v && String(v).trim() !== '' })
                                    var patientName = parts.length ? parts.join(' ') : (patient.email || 'Patient')
                                    var dateStr = a.appointment_datetime ? new Date(a.appointment_datetime).toLocaleDateString() : '-'
                                    var timeStr = a.appointment_datetime ? new Date(a.appointment_datetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                                    var statusKey = (a.status || '').toLowerCase()
                                    var statusLabel = a.status ? String(a.status).replace(/_/g, ' ') : '-'
                                    var sc = statusColors[statusKey] || 'bg-slate-50 text-slate-600 border-slate-100'
                                    var apptJson = JSON.stringify(a).replace(/'/g, '&#39;')
                                    html += '<tr class="border-b border-slate-100 last:border-0">' +
                                        '<td class="py-1.5 pr-3 text-slate-500">' + escapeHtml(dateStr) + '</td>' +
                                        '<td class="py-1.5 pr-3 text-slate-500">' + escapeHtml(timeStr) + '</td>' +
                                        '<td class="py-1.5 pr-3 text-slate-700 font-medium">' + escapeHtml(patientName) + '</td>' +
                                        '<td class="py-1.5 pr-3"><span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[0.6rem] font-medium border ' + sc + '">' + escapeHtml(statusLabel) + '</span></td>' +
                                        '<td class="py-1.5">' +
                                        '<button type="button" class="doc-details-btn inline-flex items-center justify-center gap-1 rounded-lg border border-slate-200 bg-white px-2 py-1 text-[0.65rem] font-medium text-slate-600 hover:bg-slate-50" data-appointment=\'' + apptJson + '\'>' +
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>' +
                                        ' Details</button></td></tr>'
                                })
                                html += '</tbody></table>'
                                container.innerHTML = html
                            }
                            updateUpcomingSeeMore()
                            var counter = document.getElementById('doctorUpcomingCounter')
                            if (counter) counter.textContent = upcomingTotal + ' entries'
                        })
                        .catch(function () {
                            container.innerHTML = '<p class="text-[0.72rem] text-slate-400">Failed to load appointments.</p>'
                            updateUpcomingSeeMore()
                        })
                }

                function updateUpcomingSeeMore() {
                    var btn = document.getElementById('doctorUpcomingSeeMore')
                    if (!btn) return
                    var disabled = upcomingCurrentPage >= upcomingLastPage || upcomingTotal === 0
                    btn.disabled = disabled
                }

                // Initial load
                loadUpcomingAppointments(1)
                document.getElementById('doctorUpcomingSeeMore').addEventListener('click', function () {
                    if (this.disabled) return
                    loadUpcomingAppointments(upcomingCurrentPage + 1)
                })

                // ── Queue Call Next ──────────────────────────────────────────
                var queueCallNextButton = document.getElementById('doctorOverviewCallNextButton')
                var queueCallNextSpinner = document.getElementById('doctorOverviewCallNextSpinner')
                var queueCallNextContent = document.getElementById('doctorOverviewCallNextContent')

                function setQueueCallNextSubmitting(isSubmitting) {
                    if (queueCallNextButton) queueCallNextButton.disabled = !!isSubmitting
                    if (queueCallNextSpinner) queueCallNextSpinner.classList.toggle('hidden', !isSubmitting)
                    if (queueCallNextContent) queueCallNextContent.classList.toggle('opacity-0', !!isSubmitting)
                }

                if (queueCallNextButton && typeof apiFetch === 'function') {
                    queueCallNextButton.addEventListener('click', function () {
                        if (queueCallNextButton.disabled) return
                        setQueueCallNextSubmitting(true)

                        apiFetch("{{ url('/api/queues/call-next') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ doctor_id: {{ (int) ($currentUser->user_id ?? 0) }} })
                        })
                            .then(function (response) {
                                return response.json().then(function (data) {
                                    return { ok: response.ok, data: data }
                                }).catch(function () {
                                    return { ok: response.ok, data: null }
                                })
                            })
                            .then(function (result) {
                                if (!result.ok) {
                                    if (typeof showToast === 'function') showToast(result.data && result.data.message ? result.data.message : 'Failed to call next patient.', 'error')
                                    setQueueCallNextSubmitting(false)
                                    return
                                }
                                if (typeof showToast === 'function') showToast('Next patient called successfully.', 'success')
                                setQueueCallNextSubmitting(false)
                            })
                            .catch(function () {
                                if (typeof showToast === 'function') showToast('Network error while calling next patient.', 'error')
                                setQueueCallNextSubmitting(false)
                            })
                    })
                }

                // ── On Hold Call button (event delegation, card refresh only) ─
                document.addEventListener('click', function (e) {
                    var btn = e.target.closest('.call-on-hold-btn')
                    if (!btn) return
                    if (btn.disabled) return
                    var queueId = btn.getAttribute('data-queue-id')
                    if (!queueId) return
                    btn.disabled = true

                    apiFetch("{{ url('/api/queues') }}/" + queueId, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ status: 'serving' })
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                return { ok: response.ok, data: data }
                            }).catch(function () {
                                return { ok: response.ok, data: null }
                            })
                        })
                        .then(function (result) {
                            if (!result.ok) {
                                if (typeof showToast === 'function') showToast(result.data && result.data.message ? result.data.message : 'Failed to call patient.', 'error')
                                btn.disabled = false
                                return
                            }
                            if (typeof showToast === 'function') showToast('Patient called successfully.', 'success')
                            btn.disabled = false
                        })
                        .catch(function () {
                            if (typeof showToast === 'function') showToast('Network error while calling patient.', 'error')
                            btn.disabled = false
                        })
                })

                // ── Details Modal (event delegation) ───────────────────────
                var modal = document.getElementById('appointmentDetailsModal')
                var closeBtn = document.getElementById('appointmentDetailsModalClose')

                function showModal() {
                    modal.classList.remove('hidden')
                    modal.style.display = 'flex'
                }

                function hideModal() {
                    modal.classList.add('hidden')
                    modal.style.display = 'none'
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', hideModal)
                }

                if (modal) {
                    modal.addEventListener('click', function (e) {
                        if (e.target === modal) hideModal()
                    })
                }

                function formatPatientName(patient) {
                    if (!patient) return '-'
                    var parts = [patient.firstname || '', patient.middlename || '', patient.lastname || ''].filter(function (v) { return v !== '' })
                    return parts.length ? parts.join(' ') : (patient.email || 'Patient')
                }

                function computeAge(birthdate) {
                    if (!birthdate) return '-'
                    var birth = new Date(birthdate)
                    var today = new Date()
                    var age = today.getFullYear() - birth.getFullYear()
                    var m = today.getMonth() - birth.getMonth()
                    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--
                    return age
                }

                function formatServices(services) {
                    if (!services || !services.length) return 'None'
                    return services.map(function (s) { return s.name || s.service_name || s.service_id }).join(', ')
                }

                // Event delegation: any click on .doc-details-btn (works even after refresh)
                document.addEventListener('click', function (e) {
                    var btn = e.target.closest('.doc-details-btn')
                    if (!btn) return

                    var raw = btn.getAttribute('data-appointment')
                    if (!raw) return
                    try {
                        var a = JSON.parse(raw)
                    } catch (err) {
                        return
                    }

                    var patient = a.patient || {}
                    var queue = a.queue || {}

                    // Left panel – patient profile
                    document.getElementById('modalPatientName').textContent = formatPatientName(patient)
                    document.getElementById('modalPatientContact').textContent = patient.contact_no || patient.contact || '-'
                    document.getElementById('modalPatientSex').textContent = patient.sex || '-'
                    document.getElementById('modalPatientBirthdate').textContent = patient.birthdate ? new Date(patient.birthdate).toLocaleDateString() : '-'
                    document.getElementById('modalPatientAge').textContent = computeAge(patient.birthdate)
                    document.getElementById('modalPatientAddress').textContent = patient.address || '-'

                    // Right panel – appointment / queue details
                    var apptDate = a.appointment_datetime ? new Date(a.appointment_datetime).toLocaleDateString() : '-'
                    var apptTime = a.appointment_datetime ? new Date(a.appointment_datetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-'
                    var typeLabel = a.appointment_type ? a.appointment_type.replace(/_/g, ' ') : '-'
                    var statusLabel = a.status ? a.status.replace(/_/g, ' ') : '-'
                    var statusKey = a.status ? a.status.toLowerCase() : ''
                    var modalStatusColors = {
                        pending: 'bg-amber-50 text-amber-700 border-amber-200',
                        confirmed: 'border-orange-200 bg-orange-50 text-orange-700',
                        completed: 'border-green-200 bg-green-50 text-green-700',
                        cancelled: 'bg-red-50 text-red-700 border-red-200',
                        no_show: 'bg-slate-100 text-slate-600 border-slate-200',
                        consulted: 'border-purple-200 bg-purple-50 text-purple-700',
                        waiting: 'bg-amber-50 text-amber-700 border-amber-100',
                        serving: 'bg-blue-50 text-blue-700 border-blue-100',
                        done: 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        skipped: 'bg-orange-50 text-orange-700 border-orange-100',
                        on_hold: 'bg-purple-50 text-purple-700 border-purple-100'
                    }
                    var statusColor = modalStatusColors[statusKey] || 'bg-slate-50 text-slate-600 border-slate-100'

                    document.getElementById('modalApptType').textContent = typeLabel
                    var statusBadgeEl = document.createElement('span')
                    statusBadgeEl.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-medium border ' + statusColor
                    statusBadgeEl.textContent = statusLabel
                    var modalStatusContainer = document.getElementById('modalApptStatus')
                    modalStatusContainer.innerHTML = ''
                    modalStatusContainer.appendChild(statusBadgeEl)
                    document.getElementById('modalApptDate').textContent = apptDate
                    document.getElementById('modalApptTime').textContent = apptTime
                    document.getElementById('modalQueueCode').textContent = queue.queue_code || 'N/A'
                    document.getElementById('modalQueueStatus').textContent = queue.status ? queue.status.replace(/_/g, ' ') : 'N/A'
                    document.getElementById('modalApptServices').textContent = formatServices(a.services)
                    document.getElementById('modalApptReason').textContent = a.reason_for_visit || 'No reason specified'

                    showModal()
                })

                // ── Refresh All Cards (no page reload) ──────────────────────
                function refreshAllCards() {
                    var refreshBtn = document.getElementById('docScheduleRefreshBtn')
                    if (refreshBtn) refreshBtn.disabled = true

                    // Show loading state on ALL sections
                    var tbody = document.getElementById('doctorScheduleTbody')
                    if (tbody) tbody.innerHTML = '<tr><td colspan="999" class="py-4 text-center text-[0.78rem] text-slate-400 animate-pulse">Loading…</td></tr>'

                    var metricsEl = document.getElementById('doctorMetricsContainer')
                    if (metricsEl) metricsEl.innerHTML = '<div class="col-span-3 py-6 text-center text-[0.78rem] text-slate-400 animate-pulse">Loading…</div>'

                    var activeQEl = document.getElementById('doctorActiveQueueContainer')
                    if (activeQEl) activeQEl.innerHTML = '<div class="py-10 text-center text-[0.78rem] text-slate-400 animate-pulse">Loading…</div>'

                    var onHoldEl = document.getElementById('doctorOnHoldContainer')
                    if (onHoldEl) onHoldEl.innerHTML = '<div class="py-10 text-center text-[0.78rem] text-slate-400 animate-pulse">Loading…</div>'

                    // Upcoming appointments: reload from API
                    loadUpcomingAppointments(1)

                    var url = window.location.href.split('#')[0]
                    url += (url.indexOf('?') > -1 ? '&' : '?') + '_t=' + Date.now()

                    fetch(url)
                        .then(function (r) { return r.text() })
                        .then(function (html) {
                            var parser = new DOMParser()
                            var doc = parser.parseFromString(html, 'text/html')

                            // 1. Today's Schedule table
                            var freshTbody = doc.getElementById('doctorScheduleTbody')
                            var curTbody = document.getElementById('doctorScheduleTbody')
                            if (freshTbody && curTbody) curTbody.innerHTML = freshTbody.innerHTML

                            // 2. Metrics cards (Today's Patients, In queue, Completed today)
                            var freshMetrics = doc.getElementById('doctorMetricsContainer')
                            var curMetrics = document.getElementById('doctorMetricsContainer')
                            if (freshMetrics && curMetrics) curMetrics.innerHTML = freshMetrics.innerHTML

                            // 3. Active Queue list
                            var freshActiveQ = doc.getElementById('doctorActiveQueueContainer')
                            var curActiveQ = document.getElementById('doctorActiveQueueContainer')
                            if (freshActiveQ && curActiveQ) curActiveQ.innerHTML = freshActiveQ.innerHTML

                            // 4. On Hold list (removed upcoming — loaded via API above)
                            var freshOnHold = doc.getElementById('doctorOnHoldContainer')
                            var curOnHold = document.getElementById('doctorOnHoldContainer')
                            if (freshOnHold && curOnHold) curOnHold.innerHTML = freshOnHold.innerHTML

                            if (refreshBtn) refreshBtn.disabled = false
                        })
                        .catch(function () {
                            var curTbody = document.getElementById('doctorScheduleTbody')
                            if (curTbody) curTbody.innerHTML = '<tr><td colspan="999" class="py-4 text-center text-[0.78rem] text-red-500">Refresh failed.</td></tr>'
                            if (refreshBtn) refreshBtn.disabled = false
                        })
                }

                var refreshBtn = document.getElementById('docScheduleRefreshBtn')
                if (refreshBtn) refreshBtn.addEventListener('click', refreshAllCards)

                // ── Silent Refresh (no loading states, used by Reverb) ─────
                function silentRefreshCards() {
                    loadUpcomingAppointments(1)
                    var url = window.location.href.split('#')[0]
                    url += (url.indexOf('?') > -1 ? '&' : '?') + '_t=' + Date.now()
                    fetch(url)
                        .then(function (r) { return r.text() })
                        .then(function (html) {
                            var parser = new DOMParser()
                            var doc = parser.parseFromString(html, 'text/html')
                            var freshTbody = doc.getElementById('doctorScheduleTbody')
                            var curTbody = document.getElementById('doctorScheduleTbody')
                            if (freshTbody && curTbody) curTbody.innerHTML = freshTbody.innerHTML
                            var freshMetrics = doc.getElementById('doctorMetricsContainer')
                            var curMetrics = document.getElementById('doctorMetricsContainer')
                            if (freshMetrics && curMetrics) curMetrics.innerHTML = freshMetrics.innerHTML
                            var freshActiveQ = doc.getElementById('doctorActiveQueueContainer')
                            var curActiveQ = document.getElementById('doctorActiveQueueContainer')
                            if (freshActiveQ && curActiveQ) curActiveQ.innerHTML = freshActiveQ.innerHTML
                            var freshOnHold = doc.getElementById('doctorOnHoldContainer')
                            var curOnHold = document.getElementById('doctorOnHoldContainer')
                            if (freshOnHold && curOnHold) curOnHold.innerHTML = freshOnHold.innerHTML
                        })
                }

                // ── Realtime queue updates via Reverb ──
                if (typeof window.Echo !== 'undefined' && window.Echo && !window.__doctorDashboardQueueInited) {
                    try {
                        window.__doctorDashboardQueueInited = true
                        window.Echo.private('queue.all')
                            .listen('.queue.updated', function () {
                                silentRefreshCards()
                            })
                        console.log('[DoctorDashboard] Echo listener attached to queue.all')
                    } catch (e) {
                        console.error('[DoctorDashboard] Echo subscribe failed:', e)
                    }
                }
            })
        </script>
    @else
        @php
            $title = $sectionTitles[$effectiveSectionKey] ?? 'Doctor workspace';
            $subtitle = $sectionSubtitles[$effectiveSectionKey] ?? 'Clinical workspace';
        @endphp

        <div>
            <h1 class="text-2xl font-semibold text-slate-900 mb-1">{{ $title }}</h1>
            <p class="text-sm text-slate-500">{{ $subtitle }}</p>
        </div>

        @if ($effectiveSectionKey === 'my-patients')
            @include('dashviews.doctor.doctor_my_patients')
        @elseif ($effectiveSectionKey === 'appointments')
            @include('dashviews.doctor.doctor_appointments')
        @elseif ($effectiveSectionKey === 'visits')
            @include('dashviews.doctor.doctor_visits')
        @elseif ($effectiveSectionKey === 'prescriptions')
            @include('dashviews.doctor.doctor_prescriptions')
        @elseif ($effectiveSectionKey === 'my-activity')
            @include('dashviews.doctor.doctor_my_activity')
        @elseif ($effectiveSectionKey === 'consultation')
            @include('dashviews.doctor.doctor_consultation')
        @elseif ($effectiveSectionKey === 'patient-records')
            @include('dashviews.doctor.doctor_patient_records')
        @elseif ($effectiveSectionKey === 'settings-doctor')
            @include('dashviews.doctor.doctor_settings')
        @endif
    @endif
</div>
