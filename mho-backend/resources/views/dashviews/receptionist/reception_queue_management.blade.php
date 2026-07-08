@php
    $queueItems = collect($receptionQueue ?? []);
    $tableQueueItems = $queueItems
        ->filter(function ($row) {
            return strtolower((string) ($row->status ?? '')) !== 'no_show';
        })
        ->sortBy(function ($row) {
            $status = strtolower((string) ($row->status ?? ''));
            $statusRank = match ($status) {
                'waiting', 'skipped' => 0,
                'serving' => 1,
                'on_hold' => 2,
                default => 3,
            };
            $priority = (int) ($row->priority_level ?? 5);
            $number = (int) ($row->queue_number ?? 999999);
            return str_pad((string) $statusRank, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $priority, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
        })
        ->values();
    $doctorSlots = collect($receptionDoctorSlots ?? [])
        ->filter(function ($slot) {
            return $slot && $slot->doctor;
        })
        ->values();
    $servingItems = $queueItems->where('status', 'serving')->values()->take(4)->values();
    $boardItems = $queueItems
        ->filter(function ($row) {
            return in_array((string) ($row->status ?? ''), ['waiting', 'skipped', 'on_hold'], true);
        })
        ->sortBy(function ($row) {
            $status = strtolower((string) ($row->status ?? ''));
            // Skipped entries keep the same rank as waiting so queue_number position is respected
            $statusRank = match ($status) {
                'waiting', 'skipped' => 0,
                'on_hold' => 1,
                default => 2,
            };
            $priority = (int) ($row->priority_level ?? 5);
            $number = (int) ($row->queue_number ?? 999999);
            return str_pad((string) $statusRank, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $priority, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
        })
        ->values();
    $nextItems = $boardItems->take(5);
    $doctorPanelItems = $doctorSlots->map(function ($slot) use ($queueItems) {
        $doctorId = (int) ($slot->doctor_id ?? 0);
        $doctorName = optional($slot->doctor)->personalInformation->full_name ?? 'Doctor';
        $doctorSpecialization = (string) (optional($slot->doctor)->specialization ?? '');
        $queueForDoctor = $queueItems
            ->filter(function ($row) use ($doctorId) {
                return (int) (optional($row->appointment)->doctor_id ?? 0) === $doctorId;
            })
            ->values();
        $serving = $queueForDoctor
            ->first(function ($row) {
                return (string) ($row->status ?? '') === 'serving';
            });
        $nextWaiting = $queueForDoctor
            ->filter(function ($row) {
                return (string) ($row->status ?? '') === 'waiting';
            })
            ->sortBy(function ($row) {
                $priority = (int) ($row->priority_level ?? 5);
                $number = (int) ($row->queue_number ?? 999999);
                return str_pad((string) $priority, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
            })
            ->first();

        return (object) [
            'doctor_id' => $doctorId,
            'doctor_name' => $doctorName,
            'doctor_specialization' => $doctorSpecialization,
            'slot_start' => $slot->start_time ?? null,
            'slot_end' => $slot->end_time ?? null,
            'room_number' => $slot->room_number ?? null,
            'serving' => $serving,
            'next_waiting' => $nextWaiting,
        ];
    })->values();
@endphp

<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Queue management</h2>
            <p class="text-xs text-slate-500">Add patients to the queue and monitor today&apos;s flow.</p>
        </div>
        <div class="flex flex-wrap items-end gap-2">
            <div>
                <label for="receptionCallNextDoctorId" class="block text-[0.68rem] text-slate-500 mb-1">Call next for</label>
                <select id="receptionCallNextDoctorId" class="w-[260px] rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">Auto (any active doctor)</option>
                    @foreach ($doctorPanelItems as $doctorState)
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
            </div>
            <button id="receptionCallNextButton" type="button" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-green-700 text-white text-[0.8rem] font-semibold hover:bg-green-600 transition-colors disabled:opacity-70 disabled:hover:bg-green-800 min-w-[122px] relative">
                <span id="receptionCallNextSpinner" class="hidden absolute w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                <span id="receptionCallNextContent" class="inline-flex items-center gap-2">
                    <x-lucide-megaphone class="w-[18px] h-[18px]" />
                    Call next
                </span>
            </button>
            <a href="{{ route('queue.display', ['date' => now()->toDateString()]) }}" target="_blank" id="receptionPublicQueueLinkButton" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-slate-800 text-[0.8rem] font-semibold hover:bg-slate-50 transition-colors border border-slate-200">
                <x-lucide-link class="w-[18px] h-[18px]" />
                Public link
            </a>
            
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-900">Today&apos;s queue</h3>
            <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Front desk</span>
        </div>

        <form id="receptionAddQueueForm" class="mb-4 grid gap-2 grid-cols-1 md:grid-cols-2 items-end">
            <div>
                <!-- <label for="reception_add_queue_appointment_id" class="block text-[0.7rem] text-slate-600 mb-1">Appointment</label> -->
                <div class="relative">
                    <!-- <input id="reception_queue_appointment_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Click to select walk-in appointment">
                    <input id="reception_add_queue_appointment_id" type="hidden" required>
                    <div id="receptionQueueAppointmentResults" class="hidden absolute left-0 right-0 top-full mt-1 w-full rounded-lg border border-slate-200 bg-white shadow-sm max-h-64 overflow-y-auto overscroll-contain z-50"></div> -->
                </div>
                <div id="receptionQueueAppointmentPreview" class="hidden mt-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.78rem] text-slate-700 break-words"></div>
            </div>
            <div class="flex items-end">
                <!-- <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors">
                    Add to queue
                </button> -->
            </div>
        </form>

        <div id="receptionQueueError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
        <div id="receptionQueueSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

        <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50/70 p-3">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-[0.72rem] font-semibold uppercase tracking-wider text-slate-600">Doctor serving monitor</h4>
                <span class="text-[0.68rem] text-slate-400">Based on today&apos;s active schedule</span>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                @forelse ($doctorPanelItems as $doctorState)
                    @php
                        $servingQueue = $doctorState->serving;
                        $nextQueue = $doctorState->next_waiting;
                        $servingPatient = optional(optional($servingQueue)->appointment?->patient)->personalInformation->full_name ?? null;
                        $nextPatient = optional(optional($nextQueue)->appointment?->patient)->personalInformation->full_name ?? null;
                        $servingServices = collect(optional(optional($servingQueue)->appointment)->services ?? [])->pluck('service_name')->filter()->values();
                        $nextServices = collect(optional(optional($nextQueue)->appointment)->services ?? [])->pluck('service_name')->filter()->values();
                    @endphp
                    <div class="rounded-lg border border-slate-200 bg-white px-3 py-2.5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="text-[0.75rem] font-semibold text-slate-800">Doctor: {{ $doctorState->doctor_name }}</div>
                                <div class="text-[0.68rem] text-slate-400">
                                    @if ($doctorState->slot_start && $doctorState->slot_end)
                                        {{ substr((string) $doctorState->slot_start, 0, 5) }}-{{ substr((string) $doctorState->slot_end, 0, 5) }}
                                    @else
                                        Schedule today
                                    @endif
                                    @if ($doctorState->room_number)
                                        - Room {{ (int) $doctorState->room_number }}
                                    @endif
                                </div>
                            </div>
                            @if ($servingQueue)
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold border border-emerald-200 bg-emerald-50 text-emerald-700">Serving</span>
                            @else
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold border border-amber-200 bg-amber-50 text-amber-700">No serving patient</span>
                            @endif
                        </div>
                        <div class="mt-2 text-[0.72rem] text-slate-600">
                            @if ($servingQueue)
                                <span class="font-medium text-slate-700">Serving:</span>
                                {{ $servingPatient ?: 'Patient' }}
                                @if ($servingServices->count())
                                    - {{ $servingServices->join(', ') }}
                                @endif
                            @else
                                <span class="text-slate-500">Serving: none</span>
                            @endif
                        </div>
                        <div class="mt-1 text-[0.72rem] text-slate-500">
                            @if ($nextQueue)
                                <span class="font-medium text-slate-700">Next:</span>
                                {{ $nextPatient ?: 'Patient' }}
                                @if ($nextServices->count())
                                    - {{ $nextServices->join(', ') }}
                                @endif
                            @else
                                <span>No waiting patient for this doctor.</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-lg border border-slate-200 bg-white px-3 py-3 text-[0.75rem] text-slate-500">
                        No active doctor schedule found for this time.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
            <div class="flex-1">
                <label for="reception_queue_search" class="block text-[0.7rem] text-slate-600 mb-1">Search queue</label>
                <input id="reception_queue_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Queue number, patient or doctor">
            </div>
            <div class="w-full md:w-40">
                <label for="reception_queue_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
                <select id="reception_queue_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="priority">Priority</option>
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
            <div class="w-full md:w-28 pt-1">
                <button type="button" id="recQueueRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                    <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                    Refresh
                </button>
            </div>
        </div>

      <div class="overflow-x-auto scrollbar-hidden mb-4 h-[470px]">
            <table class="min-w-full text-left text-xs text-slate-600">
                <thead>
                    <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                        <th class="py-2 pr-4 font-semibold">Queue #</th>
                        <th class="py-2 pr-4 font-semibold">Patient</th>
                        <th class="py-2 pr-4 font-semibold">Doctor</th>
                        <th class="py-2 pr-4 font-semibold">Services</th>
                        <th class="py-2 pr-4 font-semibold">Priority</th>
                        <th class="py-2 pr-4 font-semibold">Date</th>
                        <th class="py-2 pr-4 font-semibold">Status</th>
                        <th class="py-2 pr-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="receptionQueueTableBody">
                    @forelse ($tableQueueItems as $qi => $queue)
                        @php
                            $patientName = optional(optional($queue->appointment)->patient)->personalInformation->full_name ?? '';
                            $doctorName = optional(optional($queue->appointment)->doctor)->personalInformation->full_name ?? '';
                            $statusName = (string) ($queue->status ?? '');
                            $statusDropdownColor = match(strtolower($statusName)) {
                                'serving' => 'text-green-700 border-green-300 bg-green-50',
                                'on_hold' => 'text-purple-700 border-purple-300 bg-purple-50',
                                default => 'text-slate-700 border-slate-200 bg-white',
                            };
                            $dateKey = $queue->queue_datetime ? $queue->queue_datetime->format('Y-m-d H:i') : '';
                            $queueId = $queue->queue_id ?? null;
                            $priority = (int) ($queue->priority_level ?? 5);
                            $priorityLabel = match ($priority) {
                                1 => 'Emergency',
                                2 => 'Priority',
                                default => 'Regular',
                            };
                            $services = collect(optional(optional($queue)->appointment)->services ?? [])
                                ->filter(function ($s) { return $s && $s->service_name; })
                                ->values()
                                ->map(function ($s) {
                                    return [
                                        'name' => (string) ($s->service_name ?? ''),
                                        'description' => (string) ($s->description ?? ''),
                                        'service_id' => $s->service_id ?? null,
                                    ];
                                });
                            $serviceCount = $services->count();
                            $servicePrimary = $serviceCount ? $services->first() : null;
                            $servicePrimaryLabel = $servicePrimary
                                ? ($servicePrimary['name'] . ($servicePrimary['description'] ? ' - ' . $servicePrimary['description'] : ''))
                                : null;
                            $statusNameLower = strtolower($statusName);
                             $statusDropdownColor = match($statusNameLower) {
                                 'serving' => 'text-green-700 border-green-300 bg-green-50',
                                 'on_hold' => 'text-purple-700 border-purple-300 bg-purple-50',
                                 default => 'text-slate-700 border-slate-200 bg-white',
                             };
                             $statusBadgeColor = match($statusNameLower) {
                                 'waiting' => 'bg-amber-50 text-amber-700 border-amber-100',
                                 'serving' => 'bg-green-50 text-green-700 border-green-100',
                                 'consulted' => 'bg-blue-50 text-blue-700 border-blue-100',
                                 'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                 'cancelled' => 'bg-red-50 text-red-700 border-red-100',
                                 'no_show' => 'bg-slate-100 text-slate-600 border-slate-200',
                                 'skipped' => 'bg-orange-50 text-orange-700 border-orange-100',
                                 'on_hold' => 'bg-purple-50 text-purple-700 border-purple-100',
                                 default => 'bg-slate-50 text-slate-700 border-slate-100',
                             };
                        @endphp
                        <tr class="reception-queue-row"
                            data-qi="{{ $qi }}"
                            data-queue-number="{{ $queue->queue_number }}"
                            data-queue-code="{{ $queue->queue_code }}"
                            data-patient="{{ strtolower($patientName) }}"
                            data-doctor="{{ strtolower($doctorName) }}"
                            data-date="{{ $dateKey }}"
                            data-status="{{ strtolower($statusName) }}"
                            data-priority="{{ $priority }}"
                            @if ($queueId)
                                data-queue-id="{{ $queueId }}"
                            @endif
                            @if (optional($queue->appointment)->appointment_id)
                                data-appointment-id="{{ optional($queue->appointment)->appointment_id }}"
                            @endif
                            @if (optional(optional($queue->appointment)->doctor)->user_id)
                                data-doctor-id="{{ optional(optional($queue->appointment)->doctor)->user_id }}"
                            @endif>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">{{ $queue->queue_code ?? $queue->queue_number }}</td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-700">
                                @if ($patientName)
                                    {{ $patientName }}
                                @else
                                    <span class="text-slate-400">Patient</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">
                                @php $canChangeDoctor = in_array(strtolower($statusName), ['waiting', 'serving', 'skipped'], true); @endphp
                                @if ($doctorName)
                                    @if ($canChangeDoctor)
                                        <button type="button" class="rec-queue-change-doctor text-left underline decoration-dotted underline-offset-2 hover:text-green-700 hover:decoration-green-400">{{ $doctorName }}</button>
                                    @else
                                        <span class="text-slate-700">{{ $doctorName }}</span>
                                    @endif
                                @else
                                    @if ($canChangeDoctor)
                                        <button type="button" class="rec-queue-change-doctor text-[0.7rem] text-slate-400 underline decoration-dotted underline-offset-2 hover:text-green-700 hover:decoration-green-400">Assign doctor</button>
                                    @else
                                        <span class="text-[0.7rem] text-slate-400">Assign doctor</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500 max-w-[180px]">
                                @if ($serviceCount > 0)
                                    <div class="inline-flex items-center gap-1">
                                        <span class="truncate">{{ $servicePrimaryLabel }}</span>
                                        @if ($serviceCount > 1)
                                            <button type="button"
                                                class="inline-flex items-center rounded-lg border border-slate-200 px-2 py-0.5 text-[0.65rem] text-slate-600 hover:bg-slate-50 reception-service-overlay-trigger"
                                                data-services='@json($services->values()->all())'
                                                data-patient="{{ $patientName ?: 'Patient' }}"
                                                data-queue-label="{{ $queue->queue_code ?? $queue->queue_number }}">
                                                +{{ $serviceCount - 1 }}more
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[0.7rem] text-slate-400">No service</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">{{ $priority }} - {{ $priorityLabel }}</td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">
                                {{ $dateKey }}
                            </td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">
                                @if ($statusName)
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.68rem] font-medium border {{ $statusBadgeColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $statusName)) }}
                                    </span>
                                @else
                                    <span class="text-[0.7rem] text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-[0.78rem] text-right text-slate-500">
                                @if ($queueId ?? null)
                                    @if (in_array(strtolower($statusName), ['done', 'cancelled', 'no_show', 'consulted'], true))
                                        <span class="inline-flex items-center gap-1.5 text-[0.7rem] text-slate-400">
                                            @if (strtolower($statusName) === 'consulted')
                                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-2 py-1 text-[0.68rem] font-medium text-green-700">
                                                    <x-lucide-lock class="w-3 h-3" />
                                                    Waiting for payment
                                                </span>
                                            @else
                                                <x-lucide-lock class="w-3 h-3" />
                                                <span class="text-slate-400">{{ ucfirst(str_replace('_', ' ', $statusName)) }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <div class="inline-flex items-center gap-1.5">
                                            <div class="relative reception-status-dropdown-container">
                                                <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-[0.7rem] hover:bg-slate-50 reception-status-dropdown-trigger {{ $statusDropdownColor }}">
                                                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $statusName)) }}</span>
                                                    <x-lucide-chevron-down class="w-[14px] h-[14px] text-slate-400" />
                                                </button>
                                                <div class="hidden absolute right-0 top-full mt-1 w-[140px] rounded-lg border border-slate-200 bg-white shadow-lg z-50 reception-status-dropdown-menu">
                                                    <div class="py-1">
                                                        @if (strtolower($statusName) !== 'serving')
                                                        <button type="button" class="w-full text-left px-3 py-1.5 text-[0.72rem] text-slate-700 hover:bg-slate-50 reception-queue-status flex items-center gap-2" data-queue-id="{{ $queueId }}" data-status="serving">
                                                            <x-lucide-play class="w-[14px] h-[14px] text-emerald-500" />
                                                            Serve
                                                        </button>
                                                        @endif
                                                        @if (strtolower($statusName) !== 'skipped')
                                                        <button type="button" class="w-full text-left px-3 py-1.5 text-[0.72rem] text-slate-700 hover:bg-slate-50 reception-queue-status flex items-center gap-2" data-queue-id="{{ $queueId }}" data-status="skipped">
                                                            <x-lucide-skip-forward class="w-[14px] h-[14px] text-orange-500" />
                                                            Skipped
                                                        </button>
                                                        @endif
                                                        @if (strtolower($statusName) !== 'on_hold')
                                                        <button type="button" class="w-full text-left px-3 py-1.5 text-[0.72rem] text-slate-700 hover:bg-slate-50 reception-queue-status flex items-center gap-2" data-queue-id="{{ $queueId }}" data-status="on_hold">
                                                            <x-lucide-pause class="w-[14px] h-[14px] text-purple-500" />
                                                            On hold
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2 py-1.5 text-[0.7rem] text-slate-600 hover:bg-slate-50 reception-queue-config" data-queue-id="{{ $queueId }}" data-priority-level="{{ $priority }}">
                                                <x-lucide-settings class="w-[14px] h-[14px]" />
                                                Config
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-[0.7rem] text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-center text-[0.78rem] text-slate-400">
                                No queue entries for today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="receptionQueuePagination" class="px-4 py-2 border-t border-slate-50 bg-white flex items-center justify-center gap-1"></div>
    </div>
</div>

<div id="queueDisplayOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/95 flex flex-col">
    <div class="flex items-center justify-between px-8 py-4 border-b border-slate-700">
        <div>
            <div class="text-[0.8rem] text-slate-400 uppercase tracking-widest">Opol Clinic</div>
            <div class="text-lg font-semibold text-white">Queue display</div>
        </div>
        <div class="flex items-center gap-2">
            <button id="queueDisplayFullscreenButton" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-800 text-slate-100 text-[0.78rem] font-semibold hover:bg-slate-700">
                <x-lucide-fullscreen class="w-[18px] h-[18px]" />
                Full screen
            </button>
            <button id="queueDisplayCloseButton" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-700 text-slate-100 text-[0.78rem] font-semibold hover:bg-slate-600">
                <x-lucide-x class="w-[18px] h-[18px]" />
                Close
            </button>
        </div>
    </div>

    <div class="flex-1 flex flex-col lg:flex-row">
        <div class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-xl" id="queueDisplayNowServing">
                <div class="text-[0.85rem] text-green-300 uppercase tracking-[0.3em] mb-3">Now serving</div>
                @if ($servingItems->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($servingItems as $serving)
                            @php
                                $servingPatient = optional(optional($serving->appointment)->patient)->personalInformation->full_name ?? 'Patient';
                                $servingDoctor = optional(optional($serving->appointment)->doctor)->personalInformation->full_name ?? null;
                                $servingLabel = $serving->queue_code ?? $serving->queue_number;
                            @endphp
                            <div class="rounded-3xl bg-slate-800/80 border border-slate-600/80 px-6 py-6 shadow-[0_0_40px_rgba(8,47,73,0.9)]">
                                <div class="text-[0.9rem] text-slate-300 mb-2">Queue</div>
                                <div class="text-5xl md:text-6xl font-serif font-bold text-white tracking-[0.18em]">
                                    {{ $servingLabel }}
                                </div>
                                <div class="mt-4 text-[0.95rem] text-slate-100 font-semibold">
                                    {{ $servingPatient }}
                                </div>
                                <div class="mt-1 text-[0.8rem] text-slate-400">
                                    @if ($servingDoctor)
                                        {{ $servingDoctor }}
                                    @else
                                        Waiting for doctor assignment
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3xl bg-slate-800/80 border border-slate-600/80 px-6 py-8 text-center text-slate-300">
                        No queue is currently being served.
                    </div>
                @endif
            </div>
        </div>

        <div class="w-full lg:w-[420px] border-t lg:border-t-0 lg:border-l border-slate-700 bg-slate-950/70 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[0.8rem] text-slate-400 uppercase tracking-[0.25em]">Next in line</div>
                <div class="text-[0.75rem] text-slate-500" id="queueDisplayNextCount">{{ $nextItems->count() }} shown</div>
            </div>
            <div class="space-y-3 max-h-full overflow-y-auto scrollbar-hidden" id="queueDisplayNextList">
                @forelse ($nextItems as $queue)
                    @php
                        $patientName = optional(optional($queue->appointment)->patient)->personalInformation->full_name ?? 'Patient';
                        $doctorName = optional(optional($queue->appointment)->doctor)->personalInformation->full_name ?? null;
                        $statusName = (string) ($queue->status ?? '');
                    @endphp
                    <div class="rounded-2xl bg-slate-800/60 border border-slate-600/70 px-4 py-3 flex items-center justify-between">
                        <div>
                                    <div class="text-[0.75rem] text-slate-400 mb-1">Queue #{{ $queue->queue_code ?? $queue->queue_number }}</div>
                            <div class="text-[0.9rem] text-slate-100 font-semibold">{{ $patientName }}</div>
                            <div class="text-[0.75rem] text-slate-400">
                                @if ($doctorName)
                                    Doctor: {{ $doctorName }}
                                @else
                                    Doctor not assigned
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            @if ($statusName)
                                <div class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold bg-green-500/10 text-green-300 border border-green-500/40">
                                    {{ strtoupper($statusName) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-[0.8rem] text-slate-400">
                        No additional queue entries.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="receptionQueueConfigOverlay" class="hidden fixed" style="z-index:9999">
    <div class="w-[280px] rounded-xl border border-slate-200 bg-white shadow-[0_8px_30px_rgba(15,23,42,0.18)]" style="max-height:70vh;overflow-y:auto;">
        <!-- Config form content -->
        <div id="receptionConfigFormContent">
            <div class="px-3 py-2.5 border-b border-slate-100 flex items-center justify-between gap-2">
                <div class="text-[0.72rem] font-semibold text-slate-800">Queue Config</div>
                <div id="receptionQueueConfigMeta" class="text-[0.65rem] text-slate-500 truncate"></div>
            </div>
            <div class="px-3 py-2.5">
                <div id="receptionQueueConfigError" class="hidden mb-2 rounded-lg border border-red-200 bg-red-50 px-2 py-1.5 text-[0.7rem] text-red-700"></div>

                <div>
                    <label for="receptionQueueConfigPriority" class="block text-[0.65rem] text-slate-600 mb-1">Priority level</label>
                    <select id="receptionQueueConfigPriority" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="1">1 : Emergency</option>
                        <option value="2">2 : Priority</option>
                        <option value="5">5 : Regular</option>
                    </select>
                </div>

                <div class="mt-2 flex items-center gap-2">
                    <div class="flex-1">
                        <label for="receptionQueueConfigMoveSteps" class="block text-[0.65rem] text-slate-600 mb-1">Move steps</label>
                        <input id="receptionQueueConfigMoveSteps" type="number" min="1" max="4" value="1" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <div class="flex gap-1 pt-5">
                        <button id="receptionQueueConfigMoveUp" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-500 text-white hover:bg-orange-600">
                            <x-lucide-arrow-up class="w-[16px] h-[16px]" />
                        </button>
                        <button id="receptionQueueConfigMoveDown" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500 text-white hover:bg-red-600">
                            <x-lucide-arrow-down class="w-[16px] h-[16px]" />
                        </button>
                    </div>
                </div>

                <div class="mt-2.5 flex items-center justify-end gap-2">
                    <button id="receptionQueueConfigClose" type="button" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-[0.7rem] font-semibold hover:bg-slate-200 border border-slate-200">
                        Close
                    </button>
                    <button id="receptionQueueConfigSave" type="button" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-green-600 text-white text-[0.7rem] font-semibold hover:bg-green-700">
                        <x-lucide-save class="w-[14px] h-[14px]" />
                        Save
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation content (hidden by default) -->
        <div id="receptionConfigConfirmContent" class="hidden">
            <div class="px-3 py-2.5 border-b border-slate-100">
                <div id="receptionConfigConfirmTitle" class="text-[0.72rem] font-semibold text-slate-800">Change priority</div>
                <div id="receptionConfigConfirmMessage" class="mt-1 text-[0.7rem] text-slate-600">Are you sure you want to update the priority level?</div>
            </div>
            <div class="px-3 py-2.5 flex items-center justify-end gap-2">
                <button id="receptionConfigConfirmCancel" type="button" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-[0.7rem] font-semibold hover:bg-slate-200 border border-slate-200">
                    Cancel
                </button>
                <button id="receptionConfigConfirmOk" type="button" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-green-600 text-white text-[0.7rem] font-semibold hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="receptionConfigConfirmOkSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span id="receptionConfigConfirmOkLabel">Confirm</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="receptionServiceOverlayBackdrop" class="hidden fixed inset-0 z-[65] bg-slate-900/20">
    <div id="receptionServiceOverlayPanel" class="absolute hidden w-[320px] max-w-[calc(100vw-1.5rem)] rounded-xl border border-slate-200 bg-white shadow-[0_16px_40px_rgba(15,23,42,0.18)]">
        <div class="px-3 py-2.5 border-b border-slate-100">
            <div id="receptionServiceOverlayTitle" class="text-[0.75rem] font-semibold text-slate-800">Services</div>
            <div id="receptionServiceOverlayMeta" class="text-[0.68rem] text-slate-500 mt-0.5"></div>
        </div>
        <div id="receptionServiceOverlayBody" class="px-3 py-2.5 max-h-56 overflow-y-auto text-[0.75rem] text-slate-700"></div>
    </div>
</div>

<div id="receptionConfirmOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200 shadow-[0_20px_80px_rgba(15,23,42,0.35)]">
        <div class="px-5 py-4 border-b border-slate-100">
            <div id="receptionConfirmTitle" class="text-sm font-semibold text-slate-900">Confirm</div>
            <div id="receptionConfirmMessage" class="mt-1 text-[0.78rem] text-slate-600"></div>
        </div>
        <div class="px-5 py-4 flex items-center justify-end gap-2">
            <button id="receptionConfirmCancel" type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-slate-100 text-slate-800 text-[0.78rem] font-semibold hover:bg-slate-200 border border-slate-200">
                Cancel
            </button>
            <button id="receptionConfirmOk" type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">
                Confirm
            </button>
        </div>
    </div>
</div>

<div id="recQueueDoctorPickerOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-slate-900">Change Doctor</div>
                <div id="recQueueDoctorPickerSubtitle" class="text-[0.72rem] text-slate-500">Select an available doctor for this patient.</div>
            </div>
            <button id="recQueueDoctorPickerClose" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                <x-lucide-x class="w-[16px] h-[16px]" />
            </button>
        </div>
        <div id="recQueueDoctorPickerBody" class="px-4 py-3 max-h-72 overflow-y-auto scrollbar-hidden space-y-2">
            <div class="text-[0.78rem] text-slate-400">No doctors available.</div>
        </div>
    </div>
</div>

@php
    $queueAvailableDoctors = $doctorPanelItems
        ->filter(function ($ds) {
            $spec = strtolower(trim($ds->doctor_specialization ?? ''));
            return in_array($spec, ['general medicine', 'pediatrics']);
        })
        ->map(function ($ds) {
            return [
                'id' => $ds->doctor_id,
                'name' => $ds->doctor_name,
                'specialization' => $ds->doctor_specialization ?? '',
            ];
        })->values()->toArray();
@endphp

<script>
    var __queueAvailableDoctors = @json($queueAvailableDoctors);

    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('reception_queue_search')
        var sortSelect = document.getElementById('reception_queue_sort')
        var rows = Array.prototype.slice.call(document.querySelectorAll('.reception-queue-row'))
        var addQueueForm = document.getElementById('receptionAddQueueForm')
        var queueErrorBox = document.getElementById('receptionQueueError')
        var queueSuccessBox = document.getElementById('receptionQueueSuccess')
        var appointmentSearch = document.getElementById('reception_queue_appointment_search')
        var appointmentIdInput = document.getElementById('reception_add_queue_appointment_id')
        var appointmentResults = document.getElementById('receptionQueueAppointmentResults')
        var appointmentPreview = document.getElementById('receptionQueueAppointmentPreview')
        var selectedAppointmentLabel = ''
        var appointmentSearchTimer = null

        var confirmOverlay = document.getElementById('receptionConfirmOverlay')
        var confirmTitle = document.getElementById('receptionConfirmTitle')
        var confirmMessage = document.getElementById('receptionConfirmMessage')
        var confirmCancel = document.getElementById('receptionConfirmCancel')
        var confirmOk = document.getElementById('receptionConfirmOk')
        var confirmResolver = null

        var configOverlay = document.getElementById('receptionQueueConfigOverlay')
        var configClose = document.getElementById('receptionQueueConfigClose')
        var configMeta = document.getElementById('receptionQueueConfigMeta')
        var configError = document.getElementById('receptionQueueConfigError')
        var configPriority = document.getElementById('receptionQueueConfigPriority')
        var configMoveSteps = document.getElementById('receptionQueueConfigMoveSteps')
        var configMoveUp = document.getElementById('receptionQueueConfigMoveUp')
        var configMoveDown = document.getElementById('receptionQueueConfigMoveDown')
        var configSave = document.getElementById('receptionQueueConfigSave')
        var configQueueId = null
        var callNextDoctorSelect = document.getElementById('receptionCallNextDoctorId')

        // ── Doctor Picker ──
        var doctorPickerOverlay = document.getElementById('recQueueDoctorPickerOverlay')
        var doctorPickerBody = document.getElementById('recQueueDoctorPickerBody')
        var doctorPickerSubtitle = document.getElementById('recQueueDoctorPickerSubtitle')
        var doctorPickerClose = document.getElementById('recQueueDoctorPickerClose')
        var pendingQueueAppointmentId = null
        var pendingQueueRow = null

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.rec-queue-change-doctor')
            if (!btn) return
            var row = btn.closest('.reception-queue-row')
            if (!row) return
            var rowStatus = row.getAttribute('data-status') || ''
            if (rowStatus !== 'waiting' && rowStatus !== 'serving' && rowStatus !== 'skipped') {
                return
            }
            var appointmentId = row.getAttribute('data-appointment-id')
            if (!appointmentId) {
                if (typeof showToast === 'function') showToast('No appointment linked to this queue entry.', 'error')
                return
            }
            pendingQueueAppointmentId = appointmentId
            pendingQueueRow = row

            var patientName = row.getAttribute('data-patient') || 'this patient'
            patientName = patientName.replace(/\b\w/g, function (c) { return c.toUpperCase() })
            if (doctorPickerSubtitle) doctorPickerSubtitle.textContent = 'Select an available doctor for ' + patientName + '.'

            var doctors = Array.isArray(window.__queueAvailableDoctors) ? window.__queueAvailableDoctors : []
            var currentDoctorId = row.getAttribute('data-doctor-id') || ''

            if (!doctors.length) {
                if (doctorPickerBody) doctorPickerBody.innerHTML = '<div class="text-[0.78rem] text-slate-400">No doctors available.</div>'
            } else {
                if (doctorPickerBody) {
                    doctorPickerBody.innerHTML = doctors.map(function (d) {
                        var isCurrent = String(d.id) === String(currentDoctorId)
                        var spec = d.specialization ? ('<span class="text-[0.68rem] text-slate-400">' + escapeHtml(d.specialization) + '</span>') : ''
                        return '<button type="button" class="rec-queue-doctor-option w-full text-left px-3 py-2.5 rounded-xl border ' +
                            (isCurrent
                                ? 'border-green-300 bg-green-50 text-green-800 cursor-not-allowed opacity-60'
                                : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-300') +
                            '" data-doctor-id="' + escapeHtml(String(d.id)) + '"' +
                            (isCurrent ? ' disabled' : '') +
                            '>' +
                            '<div class="font-semibold text-[0.78rem]">' + escapeHtml(d.name) + '</div>' +
                            (d.specialization ? '<div class="text-[0.68rem] text-slate-400">' + escapeHtml(d.specialization) + '</div>' : '') +
                            (isCurrent ? '<div class="text-[0.65rem] text-green-600 mt-0.5">Currently assigned</div>' : '') +
                        '</button>'
                    }).join('')
                }
            }

            if (doctorPickerOverlay && doctorPickerOverlay.classList.contains('hidden')) {
                doctorPickerOverlay.classList.remove('hidden')
                doctorPickerOverlay.classList.add('flex')
            }
        })

        if (doctorPickerClose) {
            doctorPickerClose.addEventListener('click', function () {
                if (doctorPickerOverlay) { doctorPickerOverlay.classList.add('hidden'); doctorPickerOverlay.classList.remove('flex') }
            })
        }
        if (doctorPickerOverlay) {
            doctorPickerOverlay.addEventListener('click', function (e) {
                if (e.target === doctorPickerOverlay) { doctorPickerOverlay.classList.add('hidden'); doctorPickerOverlay.classList.remove('flex') }
            })
        }

        // Doctor option click via delegation
        document.addEventListener('click', function (e) {
            var option = e.target.closest('.rec-queue-doctor-option')
            if (!option || option.disabled) return
            var newDoctorId = option.getAttribute('data-doctor-id')
            var newDoctorName = option.querySelector('.font-semibold') ? option.querySelector('.font-semibold').textContent : 'this doctor'

            if (!pendingQueueAppointmentId || !newDoctorId) return
            if (doctorPickerOverlay) { doctorPickerOverlay.classList.add('hidden'); doctorPickerOverlay.classList.remove('flex') }

            // Confirm with cooldown
            if (confirmOverlay && confirmMessage && confirmTitle) {
                confirmTitle.textContent = 'Change Doctor'
                confirmMessage.textContent = 'Are you sure you want to change the doctor for this patient to ' + newDoctorName.trim() + '?'
                confirmOverlay.classList.remove('hidden')
                confirmOverlay.classList.add('flex')

                var countdown = 3
                if (confirmOk) {
                    confirmOk.disabled = true
                    confirmOk.classList.add('opacity-60', 'cursor-not-allowed')
                    confirmOk.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>Confirm (' + countdown + ')</span>'
                    var timer = setInterval(function () {
                        countdown--
                        if (countdown < 1) {
                            clearInterval(timer)
                            confirmOk.disabled = false
                            confirmOk.classList.remove('opacity-60', 'cursor-not-allowed')
                            confirmOk.textContent = 'Confirm'
                            return
                        }
                        confirmOk.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>Confirm (' + countdown + ')</span>'
                    }, 1000)
                }

                var confirmHandler = function () {
                    if (confirmOk && confirmOk.disabled) return
                    if (confirmOverlay) { confirmOverlay.classList.add('hidden'); confirmOverlay.classList.remove('flex') }
                    if (confirmOk) { confirmOk.disabled = false; confirmOk.classList.remove('opacity-60', 'cursor-not-allowed'); confirmOk.textContent = 'Confirm' }
                    if (confirmCancel) confirmCancel.removeEventListener('click', cancelHandler)
                    confirmOk.removeEventListener('click', confirmHandler)
                    clearInterval(timer)

                    // API call
                    if (typeof apiFetch !== 'function') {
                        if (typeof showToast === 'function') showToast('API client unavailable.', 'error')
                        return
                    }
                    apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(pendingQueueAppointmentId), {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ doctor_id: parseInt(newDoctorId, 10) })
                    })
                    .then(function (response) {
                        return response.json().then(function (d) { return { ok: response.ok, status: response.status, data: d } }).catch(function () { return { ok: response.ok, status: response.status, data: null } })
                    })
                    .then(function (result) {
                        if (!result.ok) {
                            var msg = (result.data && result.data.message) ? result.data.message : 'Failed to change doctor.'
                            if (typeof showToast === 'function') showToast(msg, 'error')
                            return
                        }
                        if (typeof showToast === 'function') showToast('Doctor changed successfully.', 'success')
                        if (typeof refreshFullPage === 'function') refreshFullPage()
                        else window.location.reload()
                    })
                    .catch(function () {
                        if (typeof showToast === 'function') showToast('Network error while changing doctor.', 'error')
                    })
                }
                var cancelHandler = function () {
                    if (confirmOverlay) { confirmOverlay.classList.add('hidden'); confirmOverlay.classList.remove('flex') }
                    if (confirmOk) { confirmOk.disabled = false; confirmOk.classList.remove('opacity-60', 'cursor-not-allowed'); confirmOk.textContent = 'Confirm' }
                    clearInterval(timer)
                    confirmOk.removeEventListener('click', confirmHandler)
                    confirmCancel.removeEventListener('click', cancelHandler)
                }
                confirmOk.addEventListener('click', confirmHandler)
                confirmCancel.addEventListener('click', cancelHandler)
            }
        })

        var serviceOverlayBackdrop = document.getElementById('receptionServiceOverlayBackdrop')
        var serviceOverlayPanel = document.getElementById('receptionServiceOverlayPanel')
        var serviceOverlayTitle = document.getElementById('receptionServiceOverlayTitle')
        var serviceOverlayMeta = document.getElementById('receptionServiceOverlayMeta')
        var serviceOverlayBody = document.getElementById('receptionServiceOverlayBody')

        function confirmAction(title, message) {
            return new Promise(function (resolve) {
                confirmResolver = resolve
                if (confirmTitle) confirmTitle.textContent = title || 'Confirm'
                if (confirmMessage) confirmMessage.textContent = message || ''
                if (confirmOverlay) confirmOverlay.classList.remove('hidden')
            })
        }

        function closeConfirm(result) {
            if (confirmOverlay) confirmOverlay.classList.add('hidden')
            if (typeof confirmResolver === 'function') {
                var fn = confirmResolver
                confirmResolver = null
                fn(!!result)
            }
        }

        if (confirmCancel) {
            confirmCancel.addEventListener('click', function () { closeConfirm(false) })
        }
        if (confirmOk) {
            confirmOk.addEventListener('click', function () { closeConfirm(true) })
        }
        if (confirmOverlay) {
            confirmOverlay.addEventListener('click', function (e) {
                if (e.target === confirmOverlay) closeConfirm(false)
            })
        }

        function showConfigError(message) {
            if (!configError) return
            configError.textContent = message || ''
            configError.classList.toggle('hidden', !message)
        }

        function closeQueueConfig() {
            var overlay = document.getElementById('receptionQueueConfigOverlay')
            if (!overlay) return
            overlay.classList.add('hidden')
            overlay.style.left = ''
            overlay.style.top = ''
            configQueueId = null
            showConfigError('')
        }

        function openQueueConfig(queueId, priorityLevel, metaText, triggerButton) {
            configQueueId = queueId ? String(queueId) : null
            if (!configQueueId) return

            // Get fresh reference (in case DOM was refreshed via refreshFullPage)
            var overlay = document.getElementById('receptionQueueConfigOverlay')
            if (!overlay) return
            configOverlay = overlay

            // Append to body to avoid any parent CSS breaking position:fixed
            if (overlay.parentNode !== document.body) {
                document.body.appendChild(overlay)
            }

            if (configMeta) configMeta.textContent = metaText || ('Queue #' + String(queueId))
            if (configPriority) {
                var p = parseInt(priorityLevel, 10)
                    if (p !== 1 && p !== 2 && p !== 5) p = 5
                configPriority.value = String(p)
            }
            if (configMoveSteps) configMoveSteps.value = '1'
            showConfigError('')

            // Position under the trigger button, always below
            if (triggerButton) {
                var rect = triggerButton.getBoundingClientRect()
                var overlayWidth = 280
                var left = Math.max(8, rect.right - overlayWidth)
                if (left + overlayWidth + 8 > (window.innerWidth || 0)) {
                    left = Math.max(8, (window.innerWidth || 0) - overlayWidth - 8)
                }
                var top = rect.bottom + 4
                overlay.style.left = String(left) + 'px'
                overlay.style.top = String(top) + 'px'
                overlay.classList.remove('hidden')
            }
        }

        function updateQueuePriority(nextLevel) {
            if (!configQueueId || typeof apiFetch !== 'function') return Promise.resolve(false)
            var level = parseInt(nextLevel, 10)
            if (level !== 1 && level !== 2 && level !== 5) level = 5

            if (configSave) configSave.disabled = true
            showConfigError('')
            return apiFetch("{{ url('/api/queues') }}/" + encodeURIComponent(configQueueId), {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ priority_level: level })
            })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) {
                        showConfigError((res.data && res.data.message) ? res.data.message : 'Failed to update priority.')
                        return false
                    }
                    return true
                })
                .catch(function () {
                    showConfigError('Network error while updating priority.')
                    return false
                })
                .finally(function () {
                    if (configSave) configSave.disabled = false
                })
        }

        if (configClose) configClose.addEventListener('click', closeQueueConfig)

        // Close config overlay on outside click (like a dropdown)
        document.addEventListener('click', function (e) {
            var overlay = document.getElementById('receptionQueueConfigOverlay')
            if (!overlay || overlay.classList.contains('hidden')) return
            if (overlay.contains(e.target)) return
            if (e.target && e.target.closest && e.target.closest('.reception-queue-config')) return
            closeQueueConfig()
        })

        // Config button click — event delegation (handles DOM refreshes)
        document.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest ? e.target.closest('.reception-queue-config') : null
            if (!btn) return
            var queueId = btn.getAttribute('data-queue-id')
            if (!queueId) return
            var priority = btn.getAttribute('data-priority-level') || '5'
            var row = btn.closest ? btn.closest('tr.reception-queue-row') : null
            var code = row ? (row.getAttribute('data-queue-code') || row.getAttribute('data-queue-number') || queueId) : queueId

            // Toggle: if already open for this queueId, close it
            var overlay = document.getElementById('receptionQueueConfigOverlay')
            if (configQueueId === queueId && overlay && !overlay.classList.contains('hidden')) {
                closeQueueConfig()
                return
            }
            openQueueConfig(queueId, priority, 'Queue #' + String(code), btn)
        })

        // Close config overlay on scroll (handles scrollable containers + window scroll)
        document.addEventListener('scroll', function () {
            var overlay = document.getElementById('receptionQueueConfigOverlay')
            if (!overlay || overlay.classList.contains('hidden')) return
            closeQueueConfig()
        }, { capture: true })

        function escapeHtml(input) {
            return String(input == null ? '' : input)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function normalizeText(value) {
            return String(value == null ? '' : value).toLowerCase().replace(/\s+/g, ' ').trim()
        }

        function closeServiceOverlay() {
            if (serviceOverlayBackdrop) serviceOverlayBackdrop.classList.add('hidden')
            if (serviceOverlayPanel) serviceOverlayPanel.classList.add('hidden')
        }

        function openServiceOverlay(trigger, title, meta, services) {
            if (!serviceOverlayBackdrop || !serviceOverlayPanel || !serviceOverlayBody) return
            var list = Array.isArray(services) ? services : []
            serviceOverlayTitle.textContent = title || 'Services'
            serviceOverlayMeta.textContent = meta || ''
            serviceOverlayBody.innerHTML = list.length
                ? ('<ul class="space-y-1.5">' + list.map(function (item) {
                    var name = item && item.name ? String(item.name) : ''
                    var desc = item && item.description ? String(item.description) : ''
                    var label = name + (desc ? ' - ' + desc : '')
                    return '<li class="rounded-lg bg-slate-50 border border-slate-100 px-2.5 py-1.5">' + escapeHtml(label) + '</li>'
                }).join('') + '</ul>')
                : '<div class="text-slate-500">No services listed.</div>'

            serviceOverlayBackdrop.classList.remove('hidden')
            serviceOverlayPanel.classList.remove('hidden')

            var rect = trigger && trigger.getBoundingClientRect ? trigger.getBoundingClientRect() : null
            var panelWidth = 320
            var left = 16
            var top = 16
            if (rect) {
                left = Math.max(12, Math.min((window.innerWidth || 0) - panelWidth - 12, rect.left))
                top = rect.bottom + 8
            }
            serviceOverlayPanel.style.left = String(Math.max(12, left)) + 'px'
            serviceOverlayPanel.style.top = String(Math.max(12, top)) + 'px'
        }

        function localDateIso() {
            var now = new Date()
            var y = now.getFullYear()
            var m = String(now.getMonth() + 1).padStart(2, '0')
            var d = String(now.getDate()).padStart(2, '0')
            return y + '-' + m + '-' + d
        }

        function wordPrefixMatch(value, query) {
            var v = normalizeText(value || '')
            var q = normalizeText(query || '')
            if (!q) return true
            if (!v) return false
            if (v.indexOf(q) === 0) return true
            return v.split(/\s+/).some(function (part) { return part.indexOf(q) === 0 })
        }

        function appointmentLabel(appt) {
            if (!appt) return ''
            var id = appt.appointment_id != null ? appt.appointment_id : ''
            var patient = appt.patient || null
            var doctor = appt.doctor || null
            var pName = patient ? [patient.firstname, patient.middlename, patient.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim() : ''
            var dName = doctor ? [doctor.firstname, doctor.middlename, doctor.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim() : ''
            var when = appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : 'Queue request'
            return '#' + id + ' - ' + (pName || 'Patient') + ' · ' + (dName || 'Doctor') + ' · ' + when
        }

        function setAppointmentSelection(appt) {
            if (!appointmentIdInput) return
            var id = appt && appt.appointment_id != null ? parseInt(appt.appointment_id, 10) : 0
            if (!id) {
                appointmentIdInput.value = ''
                selectedAppointmentLabel = ''
                if (appointmentPreview) {
                    appointmentPreview.textContent = ''
                    appointmentPreview.classList.add('hidden')
                }
                return
            }

            appointmentIdInput.value = String(id)
            selectedAppointmentLabel = appointmentLabel(appt)
            if (appointmentSearch) appointmentSearch.value = selectedAppointmentLabel

            if (appointmentPreview) {
                appointmentPreview.textContent = selectedAppointmentLabel
                appointmentPreview.classList.remove('hidden')
            }

            if (appointmentResults) {
                appointmentResults.innerHTML = ''
                appointmentResults.classList.add('hidden')
            }
        }

        function renderAppointmentOptions(list) {
            if (!appointmentResults) return
            var items = (list || []).filter(function (a) {
                if (!a) return false
                var t = String(a.appointment_type || '').toLowerCase().trim()
                if (!t) return true
                return t === 'walk_in'
            }).slice(0, 20)
            if (!items.length) {
                appointmentResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No walk-in appointments found.</div>'
                appointmentResults.classList.remove('hidden')
                return
            }

            appointmentResults.innerHTML = items.map(function (a) {
                return '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 border-b border-slate-100 last:border-0">' +
                    '<div class="text-[0.78rem] text-slate-800 font-semibold">' + escapeHtml(appointmentLabel(a)) + '</div>' +
                '</button>'
            }).join('')
            appointmentResults.classList.remove('hidden')

            var buttons = appointmentResults.querySelectorAll('button')
            Array.prototype.forEach.call(buttons, function (btn, idx) {
                btn.addEventListener('click', function () {
                    setAppointmentSelection(items[idx])
                })
            })
        }

        function loadAppointmentOptions(search) {
            if (typeof apiFetch !== 'function') return
            var today = localDateIso()
            var url = "{{ url('/api/appointments') }}" + '?per_page=15&start_date=' + encodeURIComponent(today) + '&end_date=' + encodeURIComponent(today) + '&today_only=1&order=latest&appointment_type=walk_in'
            if (search) {
                url += '&search=' + encodeURIComponent(search)
            }
            apiFetch(url, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) return
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    
                    // Filter only those that are walk_in AND not already in the queue
                    var walkIns = raw.filter(function (a) {
                        if (!a) return false
                        var t = String(a.appointment_type || '').toLowerCase().trim()
                        if (t !== 'walk_in') return false
                        
                        // If there's a queue object, and its status is waiting or serving, it's already in the queue
                        if (a.queue) {
                            var qStatus = String(a.queue.status || '').toLowerCase().trim()
                            if (qStatus === 'waiting' || qStatus === 'serving') return false
                        }
                        
                        return true
                    })
                    
                    renderAppointmentOptions(walkIns)
                })
                .catch(function () {})
        }

        if (appointmentSearch) {
            appointmentSearch.addEventListener('input', function () {
                if (appointmentSearchTimer) clearTimeout(appointmentSearchTimer)
                appointmentSearchTimer = setTimeout(function () {
                    var q = (appointmentSearch.value || '').trim()
                    if (appointmentIdInput && appointmentIdInput.value && selectedAppointmentLabel) {
                        if (normalizeText(q) !== normalizeText(selectedAppointmentLabel)) {
                            setAppointmentSelection(null)
                        }
                    }
                    loadAppointmentOptions(q)
                }, 250)
            })
            appointmentSearch.addEventListener('focus', function () {
                var q = String(appointmentSearch.value || '').trim()
                loadAppointmentOptions(q)
            })
            appointmentSearch.addEventListener('click', function () {
                var q = String(appointmentSearch.value || '').trim()
                loadAppointmentOptions(q)
            })
        }

        document.addEventListener('click', function (e) {
            var target = e.target
            if (appointmentResults && !appointmentResults.classList.contains('hidden')) {
                if (!(appointmentResults.contains(target) || (appointmentSearch && appointmentSearch.contains(target)))) {
                    appointmentResults.classList.add('hidden')
                }
            }
        })

        if (serviceOverlayBackdrop) {
            serviceOverlayBackdrop.addEventListener('click', function (e) {
                if (e.target === serviceOverlayBackdrop) closeServiceOverlay()
            })
        }
        document.querySelectorAll('.reception-service-overlay-trigger').forEach(function (button) {
            button.addEventListener('click', function () {
                var raw = button.getAttribute('data-services') || '[]'
                var services = []
                try {
                    services = JSON.parse(raw)
                } catch (_) {
                    services = []
                }
                var patient = button.getAttribute('data-patient') || 'Patient'
                var queueLabel = button.getAttribute('data-queue-label') || ''
                openServiceOverlay(
                    button,
                    'Service inquiries',
                    queueLabel ? (patient + ' - Queue #' + queueLabel) : patient,
                    services
                )
            })
        })

        function applyReceptionQueueFilters() {
            var query = searchInput ? normalizeText(searchInput.value) : ''

            rows.forEach(function (row) {
                var number = ((row.getAttribute('data-queue-code') || '') + ' ' + (row.getAttribute('data-queue-number') || '')).trim()
                var patient = normalizeText(row.getAttribute('data-patient') || '')
                var doctor = normalizeText(row.getAttribute('data-doctor') || '')
                var date = normalizeText(row.getAttribute('data-date') || '')

                var matches = true
                if (query) {
                    matches =
                        ('#' + number).indexOf(query) !== -1 ||
                        wordPrefixMatch(patient, query) ||
                        wordPrefixMatch(doctor, query) ||
                        date.indexOf(query) === 0
                }

                row.style.display = matches ? '' : 'none'
            })

            applyReceptionQueueSort()
        }

        function applyReceptionQueueSort() {
            if (!sortSelect) {
                return
            }
            var value = sortSelect.value
            var tbody = rows.length ? rows[0].parentNode : null
            if (!tbody) {
                return
            }

            var visibleRows = rows.filter(function (row) {
                return row.style.display !== 'none'
            })

            visibleRows.sort(function (a, b) {
                var na = parseInt(a.getAttribute('data-queue-number') || '0', 10)
                var nb = parseInt(b.getAttribute('data-queue-number') || '0', 10)
                var da = a.getAttribute('data-date') || ''
                var db = b.getAttribute('data-date') || ''
                var pa = parseInt(a.getAttribute('data-priority') || '5', 10)
                var pb = parseInt(b.getAttribute('data-priority') || '5', 10)
                var sa = String(a.getAttribute('data-status') || '').toLowerCase()
                var sb = String(b.getAttribute('data-status') || '').toLowerCase()

                function statusRank(s) {
                    // Skipped stays in queue_number order with waiting
                    if (s === 'serving') return 0
                    if (s === 'waiting' || s === 'skipped') return 1
                    if (s === 'on_hold') return 2
                    if (s === 'consulted') return 3
                    if (s === 'done') return 4
                    if (s === 'cancelled') return 5
                    if (s === 'no_show') return 6
                    return 7
                }
                var ra = statusRank(sa)
                var rb = statusRank(sb)
                if (ra < rb) return -1
                if (ra > rb) return 1

                if (value === 'priority') {
                    if (pa < pb) return -1
                    if (pa > pb) return 1
                    if (na < nb) return -1
                    if (na > nb) return 1
                    return 0
                }

                if (value === 'newest') {
                    if (da < db) return 1
                    if (da > db) return -1
                    if (na < nb) return 1
                    if (na > nb) return -1
                    return 0
                }

                if (da < db) return -1
                if (da > db) return 1
                return 0
            })

            visibleRows.forEach(function (row) {
                tbody.appendChild(row)
            })
        }

        function showQueueError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showQueueSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyReceptionQueueFilters)
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', applyReceptionQueueSort)
        }

        applyReceptionQueueFilters()

        if (addQueueForm) {
            addQueueForm.addEventListener('submit', function (e) {
                e.preventDefault()

                showQueueError('')
                showQueueSuccess('')

                var appointmentInput = document.getElementById('reception_add_queue_appointment_id')

                var appointmentId = appointmentInput ? parseInt(appointmentInput.value, 10) : 0

                if (!appointmentId) {
                    showQueueError('Appointment ID is required to add to queue.')
                    return
                }

                if (typeof apiFetch !== 'function') {
                    showQueueError('API client is not available.')
                    return
                }

                confirmAction('Add to queue', 'Are you sure you want to add this appointment to the queue?')
                    .then(function (confirmed) {
                        if (!confirmed) return

                        apiFetch("{{ url('/api/queues') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ appointment_id: appointmentId })
                        })
                            .then(function (response) {
                                return response.json().then(function (data) {
                                    return { ok: response.ok, status: response.status, data: data }
                                }).catch(function () {
                                    return { ok: response.ok, status: response.status, data: null }
                                })
                            })
                            .then(function (result) {
                                if (!result.ok) {
                                    var message = 'Failed to add appointment to queue.'
                                    if (result.data && result.data.message) {
                                        message = result.data.message
                                    }
                                    showQueueError(message)
                                    return
                                }

                                showQueueSuccess('Appointment added to queue.')
                                refreshFullPage()
                            })
                            .catch(function () {
                                showQueueError('Network error while adding to queue.')
                            })
                    })
            })
        }

        // Config button clicks handled via event delegation (see document click handler below)

        var configFormContent = document.getElementById('receptionConfigFormContent')
        var configConfirmContent = document.getElementById('receptionConfigConfirmContent')
        var configConfirmTitle = document.getElementById('receptionConfigConfirmTitle')
        var configConfirmMessage = document.getElementById('receptionConfigConfirmMessage')
        var configConfirmCancel = document.getElementById('receptionConfigConfirmCancel')
        var configConfirmOk = document.getElementById('receptionConfigConfirmOk')

        function showConfigConfirm() {
            if (configFormContent) configFormContent.classList.add('hidden')
            if (configConfirmContent) configConfirmContent.classList.remove('hidden')
        }
        function hideConfigConfirm() {
            if (configConfirmContent) configConfirmContent.classList.add('hidden')
            if (configFormContent) configFormContent.classList.remove('hidden')
        }

        var configOriginalPriority = null
        if (configSave) {
            configSave.addEventListener('click', function () {
                if (!configQueueId || !configPriority) return
                var newPriority = configPriority.value
                if (configOriginalPriority !== null && String(configOriginalPriority) !== String(newPriority)) {
                    // Show inline confirmation
                    showConfigConfirm()
                } else {
                    saveAndReload(newPriority)
                }
            })
        }

        if (configConfirmCancel) {
            configConfirmCancel.addEventListener('click', function () {
                // Reset to original priority and go back
                if (configPriority && configOriginalPriority !== null) {
                    configPriority.value = String(configOriginalPriority)
                }
                hideConfigConfirm()
            })
        }
        var configConfirmOkBtn = document.getElementById('receptionConfigConfirmOk')
        var configConfirmOkSpinner = document.getElementById('receptionConfigConfirmOkSpinner')
        var configConfirmOkLabel = document.getElementById('receptionConfigConfirmOkLabel')
        var configSaving = false

        if (configConfirmOk) {
            configConfirmOk.addEventListener('click', function () {
                if (!configPriority || configSaving) return
                configSaving = true
                configConfirmOk.disabled = true
                if (configConfirmOkSpinner) configConfirmOkSpinner.classList.remove('hidden')
                if (configConfirmOkLabel) configConfirmOkLabel.textContent = 'Saving...'

                var level = configPriority.value
                updateQueuePriority(level).then(function (ok) {
                    if (ok) {
                        // Close config overlay first
                        closeQueueConfig()
                        hideConfigConfirm()
                        // Show toast
                        if (typeof showToast === 'function') showToast('Priority changed successfully', 'success')
                        // Refresh page content after a brief delay for toast visibility
                        setTimeout(function () { refreshFullPage() }, 600)
                    } else {
                        // Error already shown via showConfigError - re-enable button
                        configSaving = false
                        configConfirmOk.disabled = false
                        if (configConfirmOkSpinner) configConfirmOkSpinner.classList.add('hidden')
                        if (configConfirmOkLabel) configConfirmOkLabel.textContent = 'Confirm'
                    }
                }).catch(function () {
                    configSaving = false
                    configConfirmOk.disabled = false
                    if (configConfirmOkSpinner) configConfirmOkSpinner.classList.add('hidden')
                    if (configConfirmOkLabel) configConfirmOkLabel.textContent = 'Confirm'
                })
            })
        }

        function saveAndReload(level) {
            updateQueuePriority(level).then(function (ok) {
                if (ok) refreshFullPage()
            })
        }

        // Track original priority when config opens
        var origOpenQueueConfig = openQueueConfig
        openQueueConfig = function (queueId, priorityLevel, metaText, triggerButton) {
            configOriginalPriority = priorityLevel
            hideConfigConfirm()
            origOpenQueueConfig(queueId, priorityLevel, metaText, triggerButton)
        }

        function moveQueuePosition(queueId, direction) {
            if (!queueId || typeof apiFetch !== 'function') return Promise.resolve(false)
            showConfigError('')
            return apiFetch("{{ url('/api/queues') }}/" + encodeURIComponent(queueId) + "/move", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ direction: direction })
            })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) {
                        showConfigError((res.data && res.data.message) ? res.data.message : 'Failed to move queue entry.')
                        return false
                    }
                    return true
                })
                .catch(function () {
                    showConfigError('Network error while moving queue entry.')
                    return false
                })
        }

        if (configMoveUp) {
            configMoveUp.addEventListener('click', function () {
                if (!configQueueId) return
                moveQueuePosition(configQueueId, 'up').then(function (ok) {
                    if (ok) refreshFullPage()
                })
            })
        }

        if (configMoveDown) {
            configMoveDown.addEventListener('click', function () {
                if (!configQueueId) return
                moveQueuePosition(configQueueId, 'down').then(function (ok) {
                    if (ok) refreshFullPage()
                })
            })
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeQueueConfig()
                closeServiceOverlay()
            }
        })

        // Status dropdown toggle + status item clicks (event delegation)
        document.addEventListener('click', function (e) {
            // Handle status dropdown trigger toggle
            var trigger = e.target && e.target.closest ? e.target.closest('.reception-status-dropdown-trigger') : null
            if (trigger) {
                var container = trigger.closest('.reception-status-dropdown-container')
                if (container) {
                    var menu = container.querySelector('.reception-status-dropdown-menu')
                    if (menu) {
                        document.querySelectorAll('.reception-status-dropdown-menu').forEach(function (m) {
                            if (m !== menu) m.classList.add('hidden')
                        })
                        menu.classList.toggle('hidden')
                    }
                }
                return
            }
            // Handle status option click inside dropdown
            var statusBtn = e.target && e.target.closest ? e.target.closest('.reception-queue-status') : null
            if (statusBtn) {
                e.stopPropagation()
                var queueId = statusBtn.getAttribute('data-queue-id')
                var status = statusBtn.getAttribute('data-status')
                if (!queueId || !status) return
                var container = statusBtn.closest('.reception-status-dropdown-container')
                if (container) {
                    var menu = container.querySelector('.reception-status-dropdown-menu')
                    if (menu) menu.classList.add('hidden')
                }
                updateQueueStatus(queueId, status, 'Queue status updated to ' + String(status).replace(/_/g, ' ') + '.')
                return
            }
            // Close dropdowns on outside click
            document.querySelectorAll('.reception-status-dropdown-menu:not(.hidden)').forEach(function (menu) {
                if (!menu.contains(e.target) && !(e.target && e.target.closest && e.target.closest('.reception-status-dropdown-trigger'))) {
                    menu.classList.add('hidden')
                }
            })
        })

        function updateQueueStatus(queueId, status, successMessage) {
            if (!queueId) {
                return
            }

            showQueueError('')
            showQueueSuccess('')

            if (typeof apiFetch !== 'function') {
                showQueueError('API client is not available.')
                return
            }

            var url = "{{ url('/api/queues') }}/" + encodeURIComponent(queueId)

            apiFetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, status: response.status, data: data }
                    }).catch(function () {
                        return { ok: response.ok, status: response.status, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        var message = 'Failed to update queue.'
                        if (result.data && result.data.message) {
                            message = result.data.message
                        }
                        showQueueError(message)
                        return
                    }

                    showQueueSuccess(successMessage || 'Queue updated.')
                    refreshFullPage()
                })
                .catch(function () {
                    showQueueError('Network error while updating queue.')
                })
        }

        // Status item clicks handled via event delegation above

        var callNextButton = document.getElementById('receptionCallNextButton')
        var callNextSpinner = document.getElementById('receptionCallNextSpinner')
        var callNextContent = document.getElementById('receptionCallNextContent')
        function setCallNextSubmitting(state) {
            var disabled = !!state
            if (callNextButton) callNextButton.disabled = disabled
            if (callNextSpinner) callNextSpinner.classList.toggle('hidden', !disabled)
            if (callNextContent) callNextContent.classList.toggle('opacity-0', disabled)
        }
        if (callNextButton) {
            callNextButton.addEventListener('click', function () {
                if (callNextButton.disabled) return
                showQueueError('')
                showQueueSuccess('')

                if (typeof apiFetch !== 'function') {
                    showQueueError('API client is not available.')
                    return
                }

                var selectedDoctorId = callNextDoctorSelect ? String(callNextDoctorSelect.value || '').trim() : ''
                var payload = {}
                if (selectedDoctorId) {
                    payload.doctor_id = parseInt(selectedDoctorId, 10)
                }

                setCallNextSubmitting(true)
                apiFetch("{{ url('/api/queues/call-next') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, status: response.status, data: data }
                        }).catch(function () {
                            return { ok: response.ok, status: response.status, data: null }
                        })
                    })
                    .then(function (result) {
                        if (!result.ok) {
                            var message = 'Failed to call next.'
                            if (result.data && result.data.message) {
                                message = result.data.message
                            }
                            showQueueError(message)
                            return
                        }

                        showQueueSuccess(selectedDoctorId ? 'Next patient for selected doctor is now serving.' : 'Next patient is now serving.')
                        refreshFullPage()
                    })
                    .catch(function () {
                        showQueueError('Network error while calling next.')
                    })
                    .finally(function () {
                        setCallNextSubmitting(false)
                    })
            })
        }

        var publicLinkButton = document.getElementById('receptionPublicQueueLinkButton')
        if (publicLinkButton) {
            publicLinkButton.addEventListener('click', function () {
                var today = localDateIso()
                var link = "{{ route('queue.display') }}" + '?date=' + encodeURIComponent(today)

                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(link)
                    }
                } catch (_) {
                }

                try {
                    window.open(link, '_blank', 'noopener')
                } catch (_) {
                    window.location.href = link
                }
            })
        }

        function displayQueueLabel(item) {
            if (item && item.queue_code) return String(item.queue_code)
            if (item && item.queue_number != null) {
                var n = String(item.queue_number)
                while (n.length < 3) n = '0' + n
                return n
            }
            return '---'
        }

        function roomLabel(roomNumber) {
            if (roomNumber == null) return ''
            var n = parseInt(roomNumber, 10)
            if (isNaN(n) || n < 1) return ''
            return '[ROOM ' + n + ']'
        }

        function waitLabel(minutes) {
            if (minutes == null) return ''
            var n = parseInt(minutes, 10)
            if (isNaN(n) || n < 1) return ''
            return 'Est. ' + n + 'min - ' + (n + 5) + 'min'
        }

        function buildQueueDisplay(payload) {
            var servingContainer = document.getElementById('queueDisplayNowServing')
            var nextList = document.getElementById('queueDisplayNextList')
            var nextCount = document.getElementById('queueDisplayNextCount')

            var serving = payload && Array.isArray(payload.now_serving) ? payload.now_serving : []
            var next = payload && Array.isArray(payload.next) ? payload.next : []
            var nextItems = next.slice(0, 5)

            if (servingContainer) {
                if (!serving.length) {
                    servingContainer.innerHTML =
                        '<div class="text-[0.85rem] text-green-300 uppercase tracking-[0.3em] mb-3">Now serving</div>' +
                        '<div class="rounded-3xl bg-slate-800/80 border border-slate-600/80 px-6 py-8 text-center text-slate-300">' +
                        'No queue is currently being served.' +
                        '</div>'
                } else {
                    var cards = serving.map(function (item) {
                        var qn = displayQueueLabel(item)
                        var patient = item && item.patient && item.patient.name ? item.patient.name : 'Patient'
                        var doctor = item && item.doctor && item.doctor.name ? item.doctor.name : '-'
                        var room = roomLabel(item && item.room_number != null ? item.room_number : null)

                        return '' +
                            '<div class="rounded-3xl bg-slate-800/80 border border-slate-600/80 px-6 py-6 shadow-[0_0_40px_rgba(8,47,73,0.9)]">' +
                                '<div class="text-[0.9rem] text-slate-300 mb-2">Queue</div>' +
                                '<div class="text-5xl md:text-6xl font-serif font-bold text-white tracking-[0.18em]">' + escapeHtml(qn) + '</div>' +
                                '<div class="mt-4 text-[0.95rem] text-slate-100 font-semibold">' + escapeHtml(patient) + '</div>' +
                                '<div class="mt-1 text-[0.8rem] text-slate-400">' + (room ? (escapeHtml(room) + ' ') : '') + escapeHtml(doctor) + '</div>' +
                            '</div>'
                    }).join('')

                    servingContainer.innerHTML =
                        '<div class="text-[0.85rem] text-green-300 uppercase tracking-[0.3em] mb-3">Now serving</div>' +
                        '<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">' + cards + '</div>'
                }
            }

            if (nextList) {
                if (!nextItems.length) {
                    nextList.innerHTML = '<div class="text-[0.8rem] text-slate-400">No additional queue entries.</div>'
                } else {
                    nextList.innerHTML = nextItems.map(function (q) {
                        var qn = displayQueueLabel(q)
                        var patient = q && q.patient && q.patient.name ? q.patient.name : 'Patient'
                        var doctor = q && q.doctor && q.doctor.name ? q.doctor.name : 'Doctor'
                        var statusName = q && q.status ? String(q.status) : ''
                        var wait = waitLabel(q && q.estimated_wait_minutes != null ? q.estimated_wait_minutes : null)

                        return '' +
                            '<div class="rounded-2xl bg-slate-800/60 border border-slate-600/70 px-4 py-3 flex items-center justify-between gap-4">' +
                                '<div>' +
                                    '<div class="text-[0.75rem] text-slate-400 mb-1">Queue #' + escapeHtml(qn) + '</div>' +
                                    '<div class="text-[0.9rem] text-slate-100 font-semibold">' + escapeHtml(patient) + '</div>' +
                                    '<div class="text-[0.75rem] text-slate-400">' + escapeHtml(doctor) + '</div>' +
                                '</div>' +
                                '<div class="text-right">' +
                                    (wait ? ('<div class="text-[0.72rem] text-slate-400 mb-1">' + escapeHtml(wait) + '</div>') : '') +
                                    (statusName
                                        ? '<div class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold bg-green-500/10 text-green-300 border border-green-500/40">' +
                                            escapeHtml(statusName.toUpperCase()) +
                                          '</div>'
                                        : '') +
                                '</div>' +
                            '</div>'
                    }).join('')
                }
            }

            if (nextCount) {
                nextCount.textContent = nextItems.length + ' shown'
            }
        }

        function fetchQueueSnapshot() {
            if (typeof apiFetch !== 'function') {
                return
            }

            var today = localDateIso()
            var url = "{{ route('queue.display.data') }}" + '?date=' + encodeURIComponent(today)

            apiFetch(url, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, status: response.status, data: data }
                    }).catch(function () {
                        return { ok: response.ok, status: response.status, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        return
                    }
                    buildQueueDisplay(result.data)
                })
                .catch(function () {
                })
        }

        var displayButton = document.getElementById('receptionDisplayQueueButton')
        var overlay = document.getElementById('queueDisplayOverlay')
        var closeButton = document.getElementById('queueDisplayCloseButton')
        var fullscreenButton = document.getElementById('queueDisplayFullscreenButton')

        if (displayButton && overlay) {
            displayButton.addEventListener('click', function () {
                overlay.classList.remove('hidden')
            })
        }

        function closeOverlay() {
            if (!overlay) {
                return
            }
            overlay.classList.add('hidden')
            if (document.fullscreenElement && document.exitFullscreen) {
                document.exitFullscreen()
            }
        }

        if (closeButton && overlay) {
            closeButton.addEventListener('click', function () {
                closeOverlay()
            })
        }

        if (fullscreenButton && overlay) {
            fullscreenButton.addEventListener('click', function () {
                if (!document.fullscreenElement) {
                    if (overlay.requestFullscreen) {
                        overlay.requestFullscreen()
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen()
                    }
                }
            })
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay && !overlay.classList.contains('hidden')) {
                closeOverlay()
            }
        })

        // ── Queue table pagination ──
        initQueuePagination()
        function initQueuePagination() {
            var perPage = 10
            var currentPage = 1
            var container = document.getElementById('receptionQueuePagination')
            var rows = Array.prototype.slice.call(document.querySelectorAll('#receptionQueueTableBody tr.reception-queue-row'))

            function renderQueuePagination() {
                if (!container) return
                var total = rows.length
                var totalPages = Math.max(1, Math.ceil(total / perPage))
                if (currentPage > totalPages) currentPage = totalPages
                if (currentPage < 1) currentPage = 1

                // Show/hide rows
                rows.forEach(function (row, idx) {
                    var page = Math.floor(idx / perPage) + 1
                    row.style.display = (page === currentPage) ? '' : 'none'
                })

                // Build pagination HTML — same pattern as recRenderPagination
                var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
                var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
                var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
                var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
                var visibleCount = 5
                var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
                html += '<button type="button" class="' + (currentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (currentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
                var ws = currentPage
                var we = Math.min(ws + visibleCount - 1, totalPages)
                for (var i = ws; i <= we; i++) {
                    html += '<button type="button" class="' + (i === currentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
                }
                if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>' }
                html += '<button type="button" class="' + (currentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (currentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
                container.innerHTML = html
                container.querySelectorAll('button[data-page]').forEach(function (b) {
                    b.addEventListener('click', function () {
                        var p = b.getAttribute('data-page')
                        if (p === 'prev' && currentPage > 1) { currentPage--; renderQueuePagination() }
                        else if (p === 'next' && currentPage < totalPages) { currentPage++; renderQueuePagination() }
                        else if (p === 'next-window') { var ns = Math.min(we + 1, totalPages); currentPage = ns; renderQueuePagination() }
                        else if (p !== 'prev' && p !== 'next') { currentPage = parseInt(p, 10); renderQueuePagination() }
                    })
                })
            }

            renderQueuePagination()
        }

        // ── Helper: refresh only the queue table via targeted fetch ──
        var __refreshPending = false
        function refreshFullPage() {
            if (__refreshPending) return
            __refreshPending = true

            var tbody = document.getElementById('receptionQueueTableBody')
            if (!tbody) { __refreshPending = false; return }

            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.text() })
                .then(function (html) {
                    var parser = new DOMParser()
                    var doc = parser.parseFromString(html, 'text/html')
                    var newTbody = doc.getElementById('receptionQueueTableBody')
                    if (newTbody) {
                        tbody.innerHTML = newTbody.innerHTML
                    }
                    // Update the rows variable for search/sort/pagination
                    rows = Array.prototype.slice.call(document.querySelectorAll('.reception-queue-row'))
                    applyReceptionQueueFilters()
                    initQueuePagination()
                })
                .catch(function () {
                    window.location.reload()
                })
                .finally(function () {
                    __refreshPending = false
                })
        }

        // Refresh button: replace table with "Loading queue…" while fetching
        var queueRefreshBtn = document.getElementById('recQueueRefreshBtn')
        if (queueRefreshBtn) {
            queueRefreshBtn.addEventListener('click', function () {
                var tbody = document.getElementById('receptionQueueTableBody')
                if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-[0.78rem] text-slate-400">Loading queue…</td></tr>'
                }
                refreshFullPage()
            })
        }

        // Echo listener: refresh table on queue updates (debounced by __refreshPending)
        if (!window.__queueInited) {
            window.__queueInited = true
            fetchQueueSnapshot()

            if (typeof window.Echo !== 'undefined' && window.Echo) {
                try {
                    window.Echo.private('queue.all')
                        .listen('.queue.updated', function () {
                            refreshFullPage()
                        })
                    console.log('[ReceptionQueue] Echo listener attached to private queue.all')
                } catch (e) {
                    console.error('[ReceptionQueue] Echo subscribe failed:', e)
                }
            }
        }

        // Static estimated wait - calculated on page load from API data
    })
</script>
