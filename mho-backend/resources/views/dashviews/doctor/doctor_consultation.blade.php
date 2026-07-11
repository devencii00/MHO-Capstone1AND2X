@php
    $appointments = $doctorTodayAppointments ?? $doctorRecentAppointments ?? [];
    $initialAppointmentId = request()->query('appointment_id');

    // Auto-detect the currently serving patient from the queue
    $servingAppointmentId = null;
    if (!$initialAppointmentId) {
        foreach ($appointments as $appt) {
            if ($appt->queue && strtolower(trim($appt->queue->status ?? '')) === 'serving') {
                $servingAppointmentId = $appt->appointment_id;
                break;
            }
        }
    }
    $preselectedAppointmentId = $initialAppointmentId ?: $servingAppointmentId;

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
        return $name !== '' ? $name : ('User #' . ($user->user_id ?? ''));
    };
@endphp

<div class="space-y-4">
    <div>
        <h2 class="text-sm font-semibold text-slate-900">Consultation Workspace</h2>
        <p class="text-xs text-slate-500">Select today&rsquo;s appointment, review the patient snapshot, and record visit notes + prescriptions.</p>
    </div>

    <div class="grid gap-4 lg:grid-cols-12">
        {{-- ══════ Left Column — Appointment Card ══════ --}}
        <div class="lg:col-span-3 bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)] overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-green-50/60 to-white flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center text-green-600">
                        <x-lucide-calendar-clock class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Appointment</h2>
                        <p class="text-[0.7rem] text-slate-500 mt-0.5">Select a patient to consult</p>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3 flex-1">
                {{-- Hidden select for JS compatibility --}}
                <select id="consult_appointment" class="hidden">
                    <option value="">Select today&rsquo;s appointment</option>
                    @foreach ($appointments as $appointment)
                        @php
                            $patientName = $formatUserName($appointment->patient);
                            $labelDate = optional($appointment->appointment_datetime)->format('Y-m-d') ?? '-';
                            $labelTime = optional($appointment->appointment_datetime)->format('H:i') ?? '-';
                            $statusName = strtolower((string) ($appointment->status ?? ''));
                        @endphp
                        <option
                            value="{{ $appointment->appointment_id }}"
                            data-status="{{ $statusName }}"
                            data-label="{{ $patientName }} - {{ $labelDate }} {{ $labelTime }}"
                            data-appointment-type="{{ $appointment->appointment_type ?? '' }}"
                            data-queue-status="{{ optional($appointment->queue)->status ?? '' }}"
                            {{ (string) $appointment->appointment_id === (string) $preselectedAppointmentId ? 'selected' : '' }}
                        >
                            {{ $statusName === 'consulted' ? '[ Consulted ] ' : '' }}{{ $patientName }} - {{ $labelDate }} {{ $labelTime }}
                        </option>
                    @endforeach
                </select>

                {{-- Clickable appointment selection button --}}
                <button type="button" id="consultAppointmentBtn" class="w-full text-left rounded-xl border border-slate-200 bg-white px-3 py-2.5 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <div id="consultAppointmentDisplay" class="text-[0.75rem] text-slate-500">Select appointment</div>
                            <div id="consultAppointmentMeta" class="text-[0.68rem] text-slate-400 mt-0.5"></div>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 text-slate-400 flex-shrink-0" />
                    </div>
                </button>

                <div id="consultSnapshotLoading" class="hidden rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.75rem] text-slate-600">Loading patient snapshot&hellip;</div>

                {{-- Patient Details Card --}}
                <div class="border border-slate-200 rounded-xl bg-white p-3 space-y-2">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                        <div class="w-6 h-6 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                            <x-lucide-user class="w-3.5 h-3.5" />
                        </div>
                        <span class="text-[0.68rem] font-semibold text-slate-700 uppercase tracking-wider">Patient Details</span>
                    </div>
                    <div>
                        <div id="consultPatientName" class="text-[0.95rem] font-semibold text-slate-900">-</div>
                        <div id="consultPatientMeta" class="text-[0.72rem] text-slate-500">-</div>
                    </div>
                    <dl class="text-[0.72rem] text-slate-600 space-y-0.5">
                        <div><dt class="inline text-slate-400">Phone: </dt><dd id="consultPatientPhone" class="inline font-medium text-slate-800">-</dd></div>
                        <div><dt class="inline text-slate-400">Email: </dt><dd id="consultPatientEmail" class="inline font-medium text-slate-800">-</dd></div>
                        <div><dt class="inline text-slate-400">Address: </dt><dd id="consultPatientAddress" class="inline font-medium text-slate-800">-</dd></div>
                    </dl>
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div class="rounded-lg border border-slate-100 bg-slate-50 px-2.5 py-1.5">
                            <div class="text-[0.65rem] text-slate-400">Last visit</div>
                            <div id="consultLastVisit" class="text-[0.72rem] font-semibold text-slate-800">-</div>
                        </div>
                        <div class="rounded-lg border border-slate-100 bg-slate-50 px-2.5 py-1.5">
                            <div class="text-[0.65rem] text-slate-400">Total visits</div>
                            <div id="consultTotalVisits" class="text-[0.72rem] font-semibold text-slate-800">-</div>
                        </div>
                    </div>
                </div>

                {{-- Appointment Details Card --}}
                <div class="border border-slate-200 rounded-xl bg-white p-3 space-y-2">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                        <div class="w-6 h-6 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center text-green-600">
                            <x-lucide-calendar-check class="w-3.5 h-3.5" />
                        </div>
                        <span class="text-[0.68rem] font-semibold text-slate-700 uppercase tracking-wider">Appointment Details</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="text-[0.7rem] text-slate-400">Date &amp; Time</div>
                            <div id="consultApptDateTime" class="text-[0.75rem] font-semibold text-slate-700">-</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[0.7rem] text-slate-400">Type</div>
                            <div id="consultApptType" class="text-[0.72rem] font-semibold text-slate-700">-</div>
                        </div>
                    </div>
                    <div>
                        <div class="text-[0.68rem] text-slate-400 mb-0.5">Services</div>
                        <div id="consultApptServices" class="text-[0.72rem] font-medium text-slate-800">-</div>
                    </div>
                    <div>
                        <div class="text-[0.68rem] text-slate-400 mb-0.5">Reason for Visit</div>
                        <div id="consultApptReason" class="text-[0.72rem] font-medium text-slate-700">-</div>
                    </div>
                </div>

                <button type="button" id="consultViewProfileBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <x-lucide-external-link class="w-3.5 h-3.5" />
                    View Profile &amp; History
                </button>
            </div>
        </div>

        {{-- ══════ Middle Column — Consultation & Prescription Card ══════ --}}
        <div class="lg:col-span-6 bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)] overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-blue-50/60 to-white flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                            <x-lucide-clipboard-list class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Consultation &amp; Prescription</h2>
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">Record diagnosis and prescribe medicine</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="consultVitalsBtn" class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 text-[0.78rem] font-semibold text-amber-700 hover:bg-amber-100">
                           Take vitals
                        </button>
                        <button type="button" id="consultSave" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-3 py-1.5 text-[0.78rem] font-semibold text-white hover:bg-green-700 disabled:opacity-70 disabled:hover:bg-green-600">
                            <span id="consultSaveSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            <span id="consultSaveLabel">Submit</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-4 space-y-3 flex-1">
                <div id="consultSafetyBox" class="hidden rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-[0.75rem] text-amber-800 whitespace-pre-line"></div>
                <div id="consultVitalsSummary" class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-[0.75rem] text-amber-800">
                    No vitals recorded yet. This step is optional.
                </div>
                <div id="consultVitalsFeedback" class="hidden rounded-lg border px-3 py-2 text-[0.75rem]"></div>

                <div class="grid gap-3 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="consult_diagnosis" class="block text-[0.7rem] text-slate-600 mb-1">Diagnosis</label>
                        <textarea id="consult_diagnosis" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none min-h-[90px]" placeholder="Enter clinical diagnosis"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="consult_treatment" class="block text-[0.7rem] text-slate-600 mb-1">Treatment notes</label>
                        <textarea id="consult_treatment" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none min-h-[120px]" placeholder="Enter treatment plan, follow-up instructions, and other notes"></textarea>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <div>
                            <h4 class="text-xs font-semibold text-slate-900">Prescription items</h4>
                            <p class="text-[0.72rem] text-slate-500">Add medicines, then save to issue the prescription.</p>
                        </div>
                        <button type="button" id="consultAddMedicine" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                            + Add medicine
                        </button>
                    </div>

                    <div id="consultPrescriptionScroller" class="w-full max-w-full overflow-x-auto overflow-y-hidden border border-slate-100 rounded-xl pb-2" style="scrollbar-gutter: stable both-edges;">
                        <table class="w-full min-w-[72rem] table-fixed text-left text-xs text-slate-600">
                            <colgroup>
                                <col style="width: 22rem;">
                                <col style="width: 9rem;">
                                <col style="width: 9rem;">
                                <col style="width: 9rem;">
                                <col style="width: 13rem;">
                                <col style="width: 7rem;">
                            </colgroup>
                            <thead class="bg-slate-50">
                                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                                    <th class="py-2 px-3 font-semibold whitespace-nowrap">Medicine</th>
                                    <th class="py-2 px-3 font-semibold whitespace-nowrap">Dosage</th>
                                    <th class="py-2 px-3 font-semibold whitespace-nowrap">Frequency</th>
                                    <th class="py-2 px-3 font-semibold whitespace-nowrap">Duration</th>
                                    <th class="py-2 px-3 font-semibold whitespace-nowrap">Instructions</th>
                                    <th class="py-2 px-3 font-semibold whitespace-nowrap">Remove</th>
                                </tr>
                            </thead>
                            <tbody id="consultPrescriptionBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════ Right Column — Patient History Card ══════ --}}
        <div class="lg:col-span-3 bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)] overflow-hidden flex flex-col">
            <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-purple-50/60 to-white flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                        <x-lucide-clock class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Patient History</h2>
                        <p class="text-[0.7rem] text-slate-500 mt-0.5">Recent visits for quick context</p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex-1">
                <div id="consultHistoryLoading" class="hidden rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.75rem] text-slate-600">Loading history&hellip;</div>
                <div id="consultHistoryTimeline" class="space-y-2 max-h-[38rem] overflow-y-auto pr-1 scrollbar-hidden"></div>
            </div>
        </div>
    </div>
</div>

<div id="consultSafetyModal" class="hidden fixed inset-0 z-50 bg-slate-900/70">
    <div class="absolute inset-0 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-lg rounded-3xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(15,23,42,0.35)] overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3">
                <div>
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Safety Warning</div>
                    <div class="text-sm font-semibold text-slate-900">Possible allergy conflict detected</div>
                    <div class="text-xs text-slate-500 mt-1">Review before continuing. Override is required to save if conflicts remain.</div>
                </div>
                <button type="button" id="consultSafetyModalClose" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Close
                </button>
            </div>
            <div class="px-5 py-4">
                <div id="consultSafetyModalBody" class="text-[0.8rem] text-slate-700 whitespace-pre-line"></div>
                <div class="mt-4 flex items-center justify-between gap-2 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                    <div class="text-[0.78rem] text-amber-900">
                        Check <span class="font-semibold">Override safety warnings</span> to proceed with saving.
                    </div>
                    <button type="button" id="consultSafetyModalAcknowledge" class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-3 py-1.5 text-[0.78rem] font-semibold text-white hover:bg-amber-700">
                        Override
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- Appointment Selection Modal --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="consultAppointmentModal" class="hidden fixed inset-0 z-50 bg-slate-900/70">
    <div class="absolute inset-0 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-4xl max-h-[80vh] h-[32rem] rounded-3xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(15,23,42,0.35)] overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3 flex-shrink-0">
                <div>
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Select Appointment</div>
                    <div class="text-sm font-semibold text-slate-900">Choose a patient from today&rsquo;s queue</div>
                    <div class="text-xs text-slate-500 mt-1">Click on a patient to load their consultation workspace.</div>
                </div>
                <button type="button" id="consultAppointmentModalClose" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Close
                </button>
            </div>
            <div class="px-5 py-4 flex-1 overflow-y-auto">
                <div id="consultAppointmentModalGrid" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @forelse ($appointments as $appointment)
                        @php
                            $patientName = $formatUserName($appointment->patient);
                            $apptTime = optional($appointment->appointment_datetime)->format('H:i') ?? '-';
                            $apptDate = optional($appointment->appointment_datetime)->format('Y-m-d') ?? '-';
                            $statusKey = strtolower((string) ($appointment->status ?? ''));
                            $statusLabel = $appointment->status ? ucfirst(str_replace('_', ' ', $appointment->status)) : '-';
                            $statusColors = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'consulted' => 'bg-green-50 text-green-700 border-green-100',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                'no_show' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'waiting' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'serving' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'skipped' => 'bg-orange-50 text-orange-700 border-orange-100',
                                'on_hold' => 'bg-purple-50 text-purple-700 border-purple-100',
                            ];
                            $statusColor = $statusColors[$statusKey] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            $queueCode = optional($appointment->queue)->queue_code ?? '#A' . $appointment->appointment_id;
                        @endphp
                        <button type="button"
                            class="consult-appt-card text-left rounded-xl border border-slate-200 bg-white px-4 py-3.5 hover:border-green-300 hover:bg-green-50/30 transition-all duration-150 shadow-[0_1px_4px_rgba(15,23,42,0.04)]"
                            data-appointment-id="{{ $appointment->appointment_id }}"
                            data-patient-name="{{ $patientName }}"
                            data-datetime="{{ $apptDate }} {{ $apptTime }}"
                            data-status="{{ $statusKey }}"
                            data-status-label="{{ $statusLabel }}">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="inline-flex items-center gap-1.5 text-[0.82rem] font-semibold text-slate-800">
                                    <x-lucide-hash class="w-3.5 h-3.5 text-slate-400" />
                                    {{ $queueCode }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-medium border whitespace-nowrap {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-1.5">
                                <x-lucide-user class="w-3 h-3 text-slate-400" />
                                <span class="text-[0.75rem] text-slate-600 truncate">{{ $patientName }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-1.5 text-[0.7rem] text-slate-500">
                                <x-lucide-clock class="w-3 h-3 text-slate-400" />
                                <span>{{ $apptTime }}</span>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-3 flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-2">
                                <x-lucide-calendar-x class="w-5 h-5 text-slate-300" />
                            </div>
                            <p class="text-[0.78rem] font-medium text-slate-500">No appointments available</p>
                            <p class="text-[0.68rem] text-slate-400 mt-0.5">There are no patients in today&rsquo;s queue.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- History Detail Modal --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="consultHistoryDetailModal" class="hidden fixed inset-0 z-50 bg-slate-900/70">
    <div class="absolute inset-0 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-2xl rounded-3xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(15,23,42,0.35)] overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3">
                <div>
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Visit Details</div>
                    <div id="consultHistoryDetailTitle" class="text-sm font-semibold text-slate-900">Visit Details</div>
                </div>
                <button type="button" id="consultHistoryDetailClose" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Close
                </button>
            </div>
            <div class="px-5 py-4 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-4 text-[0.75rem]">
                    <div>
                        <div class="text-[0.68rem] text-slate-400 mb-0.5">Date</div>
                        <div id="consultHistoryDetailDate" class="font-semibold text-slate-800">-</div>
                    </div>
                    <div>
                        <div class="text-[0.68rem] text-slate-400 mb-0.5">Doctor</div>
                        <div id="consultHistoryDetailDoctor" class="font-semibold text-slate-800">-</div>
                    </div>
                    <div>
                        <div class="text-[0.68rem] text-slate-400 mb-0.5">Type</div>
                        <div id="consultHistoryDetailType" class="font-semibold text-slate-800">-</div>
                    </div>
                    <div>
                        <div class="text-[0.68rem] text-slate-400 mb-0.5">Services</div>
                        <div id="consultHistoryDetailServices" class="font-semibold text-slate-800">-</div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="text-[0.68rem] text-slate-400 mb-1">Reason for Visit</div>
                    <div id="consultHistoryDetailReason" class="text-[0.78rem] text-slate-700">-</div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="text-[0.68rem] text-slate-400 mb-1">Diagnosis</div>
                    <div id="consultHistoryDetailDiagnosis" class="text-[0.78rem] text-slate-700 whitespace-pre-line">-</div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="text-[0.68rem] text-slate-400 mb-1">Treatment Notes</div>
                    <div id="consultHistoryDetailTreatment" class="text-[0.78rem] text-slate-700 whitespace-pre-line">-</div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="text-[0.68rem] text-slate-400 mb-1">Prescriptions</div>
                    <div id="consultHistoryDetailPrescriptions" class="text-[0.78rem] text-slate-700 space-y-1">-</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="consultVitalsModal" class="hidden fixed inset-0 z-50 bg-slate-900/70">
    <div class="absolute inset-0 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-xl rounded-3xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(15,23,42,0.35)] overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3">
                <div>
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Optional Step</div>
                    <div class="text-sm font-semibold text-slate-900">Record patient vitals</div>
                    <div class="text-xs text-slate-500 mt-1">You can save any of these fields now or close this modal to skip.</div>
                </div>
                <button type="button" id="consultVitalsClose" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Close
                </button>
            </div>
            <div class="px-5 py-4 space-y-4">
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label for="consult_height_cm" class="block text-[0.7rem] text-slate-600 mb-1">Height (cm)</label>
                        <input id="consult_height_cm" type="number" step="0.01" min="0" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional">
                    </div>
                    <div>
                        <label for="consult_weight_kg" class="block text-[0.7rem] text-slate-600 mb-1">Weight (kg)</label>
                        <input id="consult_weight_kg" type="number" step="0.01" min="0" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional">
                    </div>
                    <div>
                        <label for="consult_blood_pressure" class="block text-[0.7rem] text-slate-600 mb-1">Blood pressure</label>
                        <input id="consult_blood_pressure" type="text" maxlength="20" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional">
                    </div>
                    <div>
                        <label for="consult_temperature" class="block text-[0.7rem] text-slate-600 mb-1">Temperature</label>
                        <input id="consult_temperature" type="number" step="0.1" min="0" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional">
                    </div>
                    <div class="md:col-span-2">
                        <label for="consult_pulse_rate" class="block text-[0.7rem] text-slate-600 mb-1">Pulse rate</label>
                        <input id="consult_pulse_rate" type="number" step="1" min="0" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional">
                    </div>
                </div>
            </div>
            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" id="consultVitalsSkip" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Skip
                </button>
                <button type="button" id="consultVitalsSave" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-3 py-2 text-[0.78rem] font-semibold text-white hover:bg-green-700">
                    <span id="consultVitalsSaveSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span id="consultVitalsSaveLabel">Save vitals</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="consultConfirmModal" class="hidden fixed inset-0 z-50 bg-slate-900/70">
    <div class="absolute inset-0 flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-2xl rounded-3xl bg-white border border-slate-200 shadow-[0_20px_60px_rgba(15,23,42,0.35)] overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3">
                <div>
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Confirm Submission</div>
                    <div class="text-sm font-semibold text-slate-900">Review consultation before saving</div>
                    <div class="text-xs text-slate-500 mt-1">This will save the consultation notes and any prescription items listed below.</div>
                </div>
                <button type="button" id="consultConfirmClose" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Close
                </button>
            </div>
            <div class="px-5 py-4 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400 mb-2">Consultation Notes</div>
                    <div class="space-y-3">
                        <div>
                            <div class="text-[0.72rem] font-semibold text-slate-700">Diagnosis</div>
                            <div id="consultConfirmDiagnosis" class="mt-1 text-[0.8rem] text-slate-700 whitespace-pre-line">-</div>
                        </div>
                        <div>
                            <div class="text-[0.72rem] font-semibold text-slate-700">Treatment Notes</div>
                            <div id="consultConfirmTreatment" class="mt-1 text-[0.8rem] text-slate-700 whitespace-pre-line">-</div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400 mb-2">Prescription Items</div>
                    <div id="consultConfirmPrescription" class="space-y-2 text-[0.8rem] text-slate-700"></div>
                </div>
            </div>
            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" id="consultConfirmCancel" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="button" id="consultConfirmSubmit" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-3 py-2 text-[0.78rem] font-semibold text-white hover:bg-green-700">
                    Confirm Submit
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- View Details and History Modal --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="doctorPrViewOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div>
                <div class="text-sm font-semibold text-slate-900">Patient Details</div>
                <div id="doctorPrViewSubtitle" class="text-[0.72rem] text-slate-500">View patient profile information.</div>
            </div>
            <button type="button" id="doctorPrViewClose" class="text-slate-400 hover:text-slate-600">
                <x-lucide-x class="w-[20px] h-[20px]" />
            </button>
        </div>

        <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-1.5 overflow-x-auto scrollbar-hidden shrink-0">
            <button type="button" class="doctor-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-green-600 bg-green-600 text-white" data-view-tab="profile">Profile Info</button>
            <button type="button" class="doctor-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="verification">Type &amp; Verification</button>
            <button type="button" class="doctor-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="background">Medical Background</button>
            <button type="button" class="doctor-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="visits">Visit History</button>
            <button type="button" class="doctor-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="vitals">Vitals History</button>
            <button type="button" id="doctorPrViewTabDependentsBtn" class="doctor-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="dependents">Dependents</button>
        </div>

        <div id="doctorPrViewBody" class="p-5 overflow-y-auto flex-1">
            {{-- Profile Info Tab --}}
            <div id="doctorPrViewTabProfile" class="doctor-pr-view-tab-content h-[420px] overflow-y-auto">
                {{-- Edit mode toggle --}}
                <div class="flex gap-2 mb-4">
                    <button type="button" id="doctorPrViewEditBtn" class="inline-flex items-center gap-1 text-[0.78rem] font-semibold text-green-700 hover:text-green-800 transition-colors">
                        Edit Info
                    </button>
                </div>

                {{-- ===== DISPLAY MODE ===== --}}
                <div id="doctorPrViewProfileDisplay">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                        <div class="md:col-span-3 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                                    <div id="prDetailLastname" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                                    <div id="prDetailFirstname" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Middle name</label>
                                    <div id="prDetailMiddlename" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                                    <div id="prDetailSex" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                                    <div id="prDetailBirthdate" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label>
                                    <div id="prDetailCivilStatus" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label>
                                    <div id="prDetailNationality" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label>
                                    <div id="prDetailOccupation" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                                <div id="prDetailAddress" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg min-h-[2.5rem]">-</div>
                            </div>
                            <hr class="border-slate-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                                    <div id="prDetailPhic" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label>
                                    <div id="prDetailEmergContact" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                                <div id="prDetailEmergNumber" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                                <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                                <div class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                                    <div id="doctorPrViewProfilePic">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                </div>
                                <div class="mt-4 text-left">
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                                    <div id="prDetailContact" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== EDIT MODE ===== --}}
                <div id="doctorPrViewProfileEdit" class="hidden">
                    <div id="doctorPrViewEditError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                    <form id="doctorPrViewEditForm" class="grid grid-cols-1 md:grid-cols-5 gap-5">
                        <div class="md:col-span-3 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label for="doctorPrViewEditLastname" class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                                    <input id="doctorPrViewEditLastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="doctorPrViewEditFirstname" class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                                    <input id="doctorPrViewEditFirstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="doctorPrViewEditMiddlename" class="block text-[0.7rem] text-slate-600 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                                    <input id="doctorPrViewEditMiddlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                                    <div class="flex items-center gap-4 pt-1">
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="radio" name="doctorPrViewEditSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                        </label>
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="radio" name="doctorPrViewEditSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label for="doctorPrViewEditBirthdate" class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                                    <input id="doctorPrViewEditBirthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="doctorPrViewEditCivilStatus" class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label>
                                    <select id="doctorPrViewEditCivilStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                        <option value="">Select</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Annulled">Annulled</option>
                                        <option value="Legally Separated">Legally Separated</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="doctorPrViewEditNationalitySelect" class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label>
                                    <div id="doctorPrViewEditNationalityField" class="flex gap-2">
                                        <select id="doctorPrViewEditNationalitySelect" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                            <option value="">None</option>
                                            <option value="Filipino">Filipino</option>
                                            <option value="__others__">Other/s specify</option>
                                        </select>
                                        <input id="doctorPrViewEditNationality" type="text" class="w-0 hidden rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Please specify">
                                    </div>
                                </div>
                                <div>
                                    <label for="doctorPrViewEditOccupation" class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label>
                                    <input id="doctorPrViewEditOccupation" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                            </div>
                            <div>
                                <label for="doctorPrViewEditAddress" class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                                <textarea id="doctorPrViewEditAddress" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none resize-y" placeholder="Street, barangay, municipality"></textarea>
                            </div>
                            <hr class="border-slate-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="doctorPrViewEditPhilhealth" class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                                    <input id="doctorPrViewEditPhilhealth" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                                </div>
                                <div>
                                    <label for="doctorPrViewEditEmergencyContact" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label>
                                    <input id="doctorPrViewEditEmergencyContact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                            </div>
                            <div>
                                <label for="doctorPrViewEditEmergencyContactNumber" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                                <input id="doctorPrViewEditEmergencyContactNumber" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                                <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                                <div id="doctorPrViewEditProfilePreview" class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div class="mt-3">
                                    <label for="doctorPrViewEditProfileUpload" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-green-200 bg-green-50 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Upload photo
                                    </label>
                                    <input id="doctorPrViewEditProfileUpload" type="file" accept="image/*" class="hidden">
                                </div>
                                <div class="mt-4 text-left">
                                    <label for="doctorPrViewEditContact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                                    <input id="doctorPrViewEditContact" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" id="doctorPrViewEditCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                            <button type="submit" id="doctorPrViewEditSave" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                                <span id="doctorPrViewEditSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                                <span id="doctorPrViewEditSaveLabel">Save changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Type & Verification Tab --}}
            <div id="doctorPrViewTabVerification" class="hidden doctor-pr-view-tab-content min-h-[420px]">
                <div class="space-y-3">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Verification status</div>
                        <div id="doctorPrViewVerificationStatus" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Patient type</div>
                        <div id="doctorPrViewPatientType" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Verification ID</div>
                        <div id="doctorPrViewVerificationId" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                </div>
            </div>

            {{-- Medical Background Tab --}}
            <div id="doctorPrViewTabBackground" class="hidden doctor-pr-view-tab-content min-h-[420px]"></div>
            {{-- Visit History Tab --}}
            <div id="doctorPrViewTabVisits" class="hidden doctor-pr-view-tab-content min-h-[420px]"></div>
            {{-- Vitals History Tab --}}
            <div id="doctorPrViewTabVitals" class="hidden doctor-pr-view-tab-content min-h-[420px]"></div>
            {{-- Dependents Tab --}}
            <div id="doctorPrViewTabDependents" class="hidden doctor-pr-view-tab-content min-h-[420px]"></div>
        </div>
    </div>
</div>

<style>
    #consultPrescriptionScroller {
        scrollbar-width: auto;
        scrollbar-color: #94a3b8 #e2e8f0;
    }

    #consultPrescriptionScroller::-webkit-scrollbar {
        height: 12px;
    }

    #consultPrescriptionScroller::-webkit-scrollbar-track {
        background: #e2e8f0;
        border-radius: 9999px;
    }

    #consultPrescriptionScroller::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 9999px;
        border: 2px solid #e2e8f0;
    }

    #consultPrescriptionScroller::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var appointmentSelect = document.getElementById('consult_appointment')
        var snapshotLoading = document.getElementById('consultSnapshotLoading')
        var safetyBox = document.getElementById('consultSafetyBox')
        var vitalsSummary = document.getElementById('consultVitalsSummary')
        var vitalsFeedback = document.getElementById('consultVitalsFeedback')
        var diagnosisEl = document.getElementById('consult_diagnosis')
        var treatmentEl = document.getElementById('consult_treatment')
        var vitalsBtn = document.getElementById('consultVitalsBtn')
        var clearBtn = document.getElementById('consultClear')
        var saveBtn = document.getElementById('consultSave')
        var saveSpinner = document.getElementById('consultSaveSpinner')
        var saveLabel = document.getElementById('consultSaveLabel')
        var addMedBtn = document.getElementById('consultAddMedicine')
        var prescriptionBody = document.getElementById('consultPrescriptionBody')
        var printBtn = document.getElementById('consultPrintReceipt')
        var acknowledgeEl = document.getElementById('consultAcknowledgeConflicts')
        var safetyModal = document.getElementById('consultSafetyModal')
        var safetyModalBody = document.getElementById('consultSafetyModalBody')
        var safetyModalClose = document.getElementById('consultSafetyModalClose')
        var safetyModalAck = document.getElementById('consultSafetyModalAcknowledge')
        var vitalsModal = document.getElementById('consultVitalsModal')
        var vitalsClose = document.getElementById('consultVitalsClose')
        var vitalsSkip = document.getElementById('consultVitalsSkip')
        var vitalsSave = document.getElementById('consultVitalsSave')
        var vitalsSaveSpinner = document.getElementById('consultVitalsSaveSpinner')
        var vitalsSaveLabel = document.getElementById('consultVitalsSaveLabel')
        var heightEl = document.getElementById('consult_height_cm')
        var weightEl = document.getElementById('consult_weight_kg')
        var bloodPressureEl = document.getElementById('consult_blood_pressure')
        var temperatureEl = document.getElementById('consult_temperature')
        var pulseRateEl = document.getElementById('consult_pulse_rate')
        var confirmModal = document.getElementById('consultConfirmModal')
        var confirmClose = document.getElementById('consultConfirmClose')
        var confirmCancel = document.getElementById('consultConfirmCancel')
        var confirmSubmit = document.getElementById('consultConfirmSubmit')
        var confirmDiagnosis = document.getElementById('consultConfirmDiagnosis')
        var confirmTreatment = document.getElementById('consultConfirmTreatment')
        var confirmPrescription = document.getElementById('consultConfirmPrescription')
        var historyFilter = document.getElementById('consultHistoryFilter')
        var historyLoading = document.getElementById('consultHistoryLoading')
        var historyTimeline = document.getElementById('consultHistoryTimeline')

        var elPatientName = document.getElementById('consultPatientName')
        var elPatientMeta = document.getElementById('consultPatientMeta')
        var elApptDateTime = document.getElementById('consultApptDateTime')
        var elApptType = document.getElementById('consultApptType')
        var elPhone = document.getElementById('consultPatientPhone')
        var elEmail = document.getElementById('consultPatientEmail')
        var elAddress = document.getElementById('consultPatientAddress')
        var elDepStatus = document.getElementById('consultDependentStatus')
        var elParentName = document.getElementById('consultParentName')
        var elParentMeta = document.getElementById('consultParentMeta')
        var elAllergyDrug = document.getElementById('consultAllergyDrug')
        var elAllergyFood = document.getElementById('consultAllergyFood')
        var elConditions = document.getElementById('consultConditions')
        var elLastVisit = document.getElementById('consultLastVisit')
        var elTotalVisits = document.getElementById('consultTotalVisits')

        var state = {
            doctorUserId: null,
            appointmentId: null,
            patientId: null,
            parentUserId: null,
            transactionId: null,
            prescriptionId: null,
            existingItemIds: [],
            medicalBackground: [],
            medicines: [],
            medicinesById: {},
            history: [],
            historyVitalsByAppointment: {},
            lastSavedTransactionId: null,
            vitals: null,
        }
        var successHideTimer = null

        function setVisible(el, visible) {
            if (!el) return
            if (visible) el.classList.remove('hidden')
            else el.classList.add('hidden')
        }

        function setText(el, text) {
            if (!el) return
            el.textContent = text || '-'
        }

        function setHtml(el, html) {
            if (!el) return
            el.innerHTML = html || ''
        }

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function clearSuccessTimer() {
            if (!successHideTimer) return
            clearTimeout(successHideTimer)
            successHideTimer = null
        }

        function setPrintVisible(visible) {
            if (!printBtn) return
            printBtn.classList.toggle('hidden', !visible)
        }

        function showSaveSuccess(message, allowPrint) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function setVitalsFeedback(message, type) {
            if (message && typeof showToast === 'function') showToast(message, type === 'error' ? 'error' : 'success')
        }

        function setVitalsModalError(message) {
            if (message && typeof showToast === 'function') {
                showToast(message, 'error')
            }
        }

        function setVitalsLoading(isLoading) {
            if (vitalsSave) vitalsSave.disabled = !!isLoading
            if (vitalsSaveSpinner) vitalsSaveSpinner.classList.toggle('hidden', !isLoading)
            if (vitalsSaveLabel) vitalsSaveLabel.textContent = isLoading ? 'Saving...' : 'Save vitals'
        }

        function modalValue(el) {
            return el ? String(el.value || '').trim() : ''
        }

        function normalizeVitalPayload() {
            var payload = {
                height_cm: modalValue(heightEl),
                weight_kg: modalValue(weightEl),
                blood_pressure: modalValue(bloodPressureEl),
                temperature: modalValue(temperatureEl),
                pulse_rate: modalValue(pulseRateEl),
            }

            Object.keys(payload).forEach(function (key) {
                if (payload[key] === '') payload[key] = null
            })

            return payload
        }

        function applyVitalsToForm(vitals) {
            if (heightEl) heightEl.value = vitals && vitals.height_cm != null ? vitals.height_cm : ''
            if (weightEl) weightEl.value = vitals && vitals.weight_kg != null ? vitals.weight_kg : ''
            if (bloodPressureEl) bloodPressureEl.value = vitals && vitals.blood_pressure != null ? vitals.blood_pressure : ''
            if (temperatureEl) temperatureEl.value = vitals && vitals.temperature != null ? vitals.temperature : ''
            if (pulseRateEl) pulseRateEl.value = vitals && vitals.pulse_rate != null ? vitals.pulse_rate : ''
        }

        function formatVitalNumber(value) {
            if (value == null || value === '') return ''
            var num = Number(value)
            return isNaN(num) ? String(value) : String(num)
        }

        function renderVitalsSummary() {
            if (!vitalsSummary) return
            var vitals = state.vitals
            if (!vitals) {
                vitalsSummary.textContent = 'No vitals recorded yet.'
                return
            }

            var parts = []
            if (vitals.height_cm != null && vitals.height_cm !== '') parts.push('Height: ' + formatVitalNumber(vitals.height_cm) + ' cm')
            if (vitals.weight_kg != null && vitals.weight_kg !== '') parts.push('Weight: ' + formatVitalNumber(vitals.weight_kg) + ' kg')
            if (vitals.blood_pressure) parts.push('BP: ' + vitals.blood_pressure)
            if (vitals.temperature != null && vitals.temperature !== '') parts.push('Temp: ' + formatVitalNumber(vitals.temperature))
            if (vitals.pulse_rate != null && vitals.pulse_rate !== '') parts.push('Pulse: ' + formatVitalNumber(vitals.pulse_rate) + ' bpm')

            vitalsSummary.textContent = parts.length ? parts.join(' • ') : 'No vitals recorded yet. This step is optional.'
        }

        function setSubmitLoading(isLoading) {
            if (saveBtn) saveBtn.disabled = !!isLoading
            if (saveSpinner) saveSpinner.classList.toggle('hidden', !isLoading)
            if (saveLabel) saveLabel.textContent = isLoading ? 'Processing...' : 'Submit'
            if (confirmSubmit) confirmSubmit.disabled = !!isLoading
            if (saveBtn) saveBtn.classList.toggle('cursor-wait', !!isLoading)
        }

        function syncAppointmentOptionLabel(option) {
            if (!option) return
            var baseLabel = option.getAttribute('data-label') || ''
            var status = normalizeString(option.getAttribute('data-status'))
            if (!baseLabel) {
                baseLabel = String(option.textContent || '')
                    .replace(/^\s*\[\s*Consulted\s*\]\s*/i, '')
                    .trim()
                option.setAttribute('data-label', baseLabel)
            }
            option.textContent = (status === 'consulted' ? '[ Consulted ] ' : '') + baseLabel
        }

        function markSelectedAppointmentConsulted() {
            if (!appointmentSelect) return
            var option = appointmentSelect.options[appointmentSelect.selectedIndex]
            if (!option) return
            option.setAttribute('data-status', 'consulted')
            syncAppointmentOptionLabel(option)
        }

        function badge(label, variant) {
            var cls = 'inline-flex items-center rounded-full border px-2 py-0.5 text-[0.68rem] font-medium '
            if (variant === 'danger') cls += 'bg-red-50 border-red-200 text-red-700'
            else if (variant === 'warn') cls += 'bg-amber-50 border-amber-200 text-amber-800'
            else cls += 'bg-white border-slate-200 text-slate-700'
            return '<span class="' + cls + '">' + (label || '-') + '</span>'
        }

        function showSafetyModal(text) {
            if (safetyModalBody) safetyModalBody.textContent = text || ''
            setVisible(safetyModal, true)
        }

        function hideSafetyModal() {
            setVisible(safetyModal, false)
        }

        function openConfirmModal() {
            setVisible(confirmModal, true)
        }

        function closeConfirmModal() {
            setVisible(confirmModal, false)
        }

        function openVitalsModal() {
            if (!state.appointmentId) {
                if (typeof showToast === 'function') showToast('Select an appointment first.', 'error')
                return
            }
            setVitalsModalError('')
            applyVitalsToForm(state.vitals)
            setVisible(vitalsModal, true)
        }

        function closeVitalsModal() {
            setVisible(vitalsModal, false)
            setVitalsModalError('')
        }

        function api(url, options) {
            if (!window.apiFetch) {
                return Promise.reject(new Error('API client is not available.'))
            }
            return window.apiFetch(url, options || {})
                .then(function (res) {
                    if (!res.ok) {
                        return res.text().then(function (txt) {
                            var err = new Error('Request failed')
                            err.status = res.status
                            err.body = txt
                            throw err
                        })
                    }
                    return res.json()
                })
        }

        function formatName(user) {
            if (!user) return '-'
            var parts = []
            if (user.firstname) parts.push(user.firstname)
            if (user.middlename) parts.push(user.middlename)
            if (user.lastname) parts.push(user.lastname)
            var name = parts.join(' ').trim()
            return name || ('User #' + (user.user_id || ''))
        }

        function medicineDisplayName(med) {
            if (!med) return '-'
            var generic = med.generic_name || ''
            var brand = med.brand_name || ''
            if (generic && brand) return generic + ' (' + brand + ')'
            return generic || brand || ('Medicine #' + (med.medicine_id || ''))
        }

        function computeAgeFromBirthdate(birthdate) {
            if (!birthdate) return ''
            var d = new Date(birthdate)
            if (isNaN(d.getTime())) return ''
            var now = new Date()
            var years = now.getFullYear() - d.getFullYear()
            var m = now.getMonth() - d.getMonth()
            if (m < 0 || (m === 0 && now.getDate() < d.getDate())) years--
            return years >= 0 ? String(years) : ''
        }

        function normalizeString(v) {
            return (v || '').toString().toLowerCase()
        }

        function getPaginatedData(resp) {
            if (!resp) return []
            if (Array.isArray(resp)) return resp
            if (Array.isArray(resp.data)) return resp.data
            return []
        }

        function resetWorkspace() {
            state.patientId = null
            state.parentUserId = null
            state.transactionId = null
            state.prescriptionId = null
            state.existingItemIds = []
            state.lastSavedTransactionId = null
            state.medicalBackground = []
            state.history = []
            state.vitals = null
            setText(elPatientName, '-')
            setText(elPatientMeta, '-')
            setText(elApptDateTime, '-')
            setText(elApptType, '-')
            setText(elPhone, '-')
            setText(elEmail, '-')
            setText(elAddress, '-')
            setText(elDepStatus, '-')
            setText(elParentName, '')
            setText(elParentMeta, '')
            setHtml(elAllergyDrug, '')
            setHtml(elAllergyFood, '')
            setHtml(elConditions, '')
            setText(elLastVisit, '-')
            setText(elTotalVisits, '-')
            if (diagnosisEl) diagnosisEl.value = ''
            if (treatmentEl) treatmentEl.value = ''
            if (prescriptionBody) prescriptionBody.innerHTML = ''
            if (historyTimeline) historyTimeline.innerHTML = ''
            setVisible(safetyBox, false)
            setVitalsFeedback('')
            renderVitalsSummary()
            applyVitalsToForm(null)
            if (acknowledgeEl) acknowledgeEl.checked = false
            setPrintVisible(false)
            clearSuccessTimer()
        }

        function ensureRow(item) {
            if (!prescriptionBody) return
            var tr = document.createElement('tr')
            tr.className = 'border-b border-slate-50 last:border-0 align-top'
            tr.innerHTML = '' +
                '<td class="py-2 px-3 align-top">' +
                    '<select class="consult-med h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"></select>' +
                    '<div class="mt-1 space-y-1">' +
                        '<div class="text-[0.68rem] text-slate-400">Indications: <span class="consult-ind text-slate-600"></span></div>' +
                        '<div class="text-[0.68rem] text-slate-400">Contra: <span class="consult-contra text-slate-600"></span></div>' +
                    '</div>' +
                '</td>' +
                '<td class="py-2 px-3 align-top"><input class="consult-dose h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. 500mg"></td>' +
                '<td class="py-2 px-3 align-top"><input class="consult-freq h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. BID"></td>' +
                '<td class="py-2 px-3 align-top"><input class="consult-dur h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. 7 days"></td>' +
                '<td class="py-2 px-3 align-top"><input class="consult-inst h-9 w-full min-w-0 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. after meals"></td>' +
                '<td class="py-2 px-3 align-top"><button type="button" class="consult-remove mt-0.5 inline-flex h-9 w-full items-center justify-center rounded-lg border border-slate-200 bg-white px-2 py-1 text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50">Remove</button></td>'

            var sel = tr.querySelector('.consult-med')
            var ind = tr.querySelector('.consult-ind')
            var contra = tr.querySelector('.consult-contra')
            var removeBtn = tr.querySelector('.consult-remove')

            var opts = ['<option value="">Select</option>']
            state.medicines.forEach(function (m) {
                opts.push('<option value="' + m.medicine_id + '">' + medicineDisplayName(m) + '</option>')
            })
            sel.innerHTML = opts.join('')

            function updateMeta() {
                var id = sel.value
                var med = state.medicinesById[id]
                ind.textContent = med && med.indications ? med.indications : '-'
                contra.textContent = med && med.contraindications ? med.contraindications : '-'
                renderSafety()

                var conflicts = computeConflicts()
                var hasConflict = conflicts.some(function (c) {
                    return normalizeString(c.medicine) === normalizeString(medicineDisplayName(med))
                })

                if (hasConflict) {
                    sel.classList.add('border-red-300')
                    sel.classList.add('bg-red-50')
                    sel.classList.add('focus:border-red-400')
                    sel.classList.add('focus:ring-red-200')

                    var lines = conflicts
                        .filter(function (c) { return normalizeString(c.medicine) === normalizeString(medicineDisplayName(med)) })
                        .slice(0, 8)
                        .map(function (c) { return '• ' + c.medicine + ' vs allergy "' + c.allergy + '"' })
                        .join('\n')
                    showSafetyModal('Possible allergy conflicts:\n' + lines)
                } else {
                    sel.classList.remove('border-red-300')
                    sel.classList.remove('bg-red-50')
                    sel.classList.remove('focus:border-red-400')
                    sel.classList.remove('focus:ring-red-200')
                }
            }

            sel.addEventListener('change', updateMeta)
            if (removeBtn) {
                removeBtn.addEventListener('click', function () {
                    tr.remove()
                    renderSafety()
                })
            }

            if (item) {
                if (item.medicine_id) sel.value = String(item.medicine_id)
                var dose = tr.querySelector('.consult-dose')
                var freq = tr.querySelector('.consult-freq')
                var dur = tr.querySelector('.consult-dur')
                var inst = tr.querySelector('.consult-inst')
                if (dose && item.dosage) dose.value = item.dosage
                if (freq && item.frequency) freq.value = item.frequency
                if (dur && item.duration) dur.value = item.duration
                if (inst && item.instructions) inst.value = item.instructions
            }

            updateMeta()
            prescriptionBody.appendChild(tr)
        }

        function getPrescriptionRows() {
            if (!prescriptionBody) return []
            var trs = Array.prototype.slice.call(prescriptionBody.querySelectorAll('tr'))
            return trs.map(function (tr) {
                return {
                    tr: tr,
                    medicine_id: tr.querySelector('.consult-med') ? tr.querySelector('.consult-med').value : '',
                    dosage: tr.querySelector('.consult-dose') ? tr.querySelector('.consult-dose').value : '',
                    frequency: tr.querySelector('.consult-freq') ? tr.querySelector('.consult-freq').value : '',
                    duration: tr.querySelector('.consult-dur') ? tr.querySelector('.consult-dur').value : '',
                    instructions: tr.querySelector('.consult-inst') ? tr.querySelector('.consult-inst').value : '',
                }
            }).filter(function (r) {
                return r.medicine_id
            })
        }

        function renderConfirmSummary() {
            var rows = getPrescriptionRows()
            if (confirmDiagnosis) {
                confirmDiagnosis.textContent = diagnosisEl && String(diagnosisEl.value || '').trim()
                    ? String(diagnosisEl.value || '').trim()
                    : 'No diagnosis entered.'
            }
            if (confirmTreatment) {
                confirmTreatment.textContent = treatmentEl && String(treatmentEl.value || '').trim()
                    ? String(treatmentEl.value || '').trim()
                    : 'No treatment notes entered.'
            }
            if (confirmPrescription) {
                if (!rows.length) {
                    confirmPrescription.innerHTML = '<div class="text-slate-500">No prescription items added.</div>'
                } else {
                    confirmPrescription.innerHTML = rows.map(function (row, index) {
                        var med = state.medicinesById[String(row.medicine_id)]
                        var parts = [row.dosage, row.frequency, row.duration, row.instructions]
                            .filter(function (part) { return String(part || '').trim() !== '' })
                            .map(function (part) { return escapeHtml(String(part)) })
                        return '' +
                            '<div class="rounded-xl border border-slate-100 bg-white px-3 py-2">' +
                                '<div class="font-semibold text-slate-900">' + escapeHtml((index + 1) + '. ' + medicineDisplayName(med)) + '</div>' +
                                '<div class="mt-1 text-[0.75rem] text-slate-600">' + (parts.length ? parts.join(' • ') : 'No dosage details provided.') + '</div>' +
                            '</div>'
                    }).join('')
                }
            }
        }

        function renderBackground(backgrounds) {
            var drug = []
            var food = []
            var cond = []

            backgrounds.forEach(function (b) {
                var cat = normalizeString(b.category)
                if (cat === 'allergy_drug') drug.push(b)
                else if (cat === 'allergy_food') food.push(b)
                else if (cat === 'condition') cond.push(b)
            })

            setHtml(elAllergyDrug, drug.length ? drug.map(function (b) { return badge(b.name || '-', 'danger') }).join(' ') : '<span class="text-[0.72rem] text-slate-400">None recorded</span>')
            setHtml(elAllergyFood, food.length ? food.map(function (b) { return badge(b.name || '-', 'warn') }).join(' ') : '<span class="text-[0.72rem] text-slate-400">None recorded</span>')
            setHtml(elConditions, cond.length ? cond.map(function (b) { return badge(b.name || '-', 'default') }).join(' ') : '<span class="text-[0.72rem] text-slate-400">None recorded</span>')
        }

        function renderHistory() {
            if (!historyTimeline) return
            var filter = historyFilter ? historyFilter.value : 'all'
            var items = state.history.slice()
            if (filter === 'with_rx') {
                items = items.filter(function (tx) {
                    return tx.prescriptions && tx.prescriptions.length
                })
            }

            if (!items.length) {
                historyTimeline.innerHTML = '<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 text-[0.78rem] text-slate-500">No visit history found.</div>'
                return
            }

            historyTimeline.innerHTML = items.map(function (tx) {
                var dt = tx.visit_datetime || tx.transaction_datetime || ''
                var dateStr = dt ? dt.toString().slice(0, 10) : '-'
                var timeStr = dt ? dt.toString().slice(11, 16) : ''
                var dx = tx.diagnosis ? tx.diagnosis : 'No diagnosis'
                var notes = tx.treatment_notes ? tx.treatment_notes : ''
                var appointmentId = tx && tx.appointment_id != null
                    ? String(tx.appointment_id)
                    : (tx && tx.appointment && tx.appointment.appointment_id != null ? String(tx.appointment.appointment_id) : '')
                var visitVitals = appointmentId ? state.historyVitalsByAppointment[appointmentId] : null
                var rx = tx.prescriptions || []
                var rxLines = []
                rx.forEach(function (p) {
                    var items = p.items || []
                    items.forEach(function (it) {
                        var medName = it.medicine ? medicineDisplayName(it.medicine) : ('Medicine #' + it.medicine_id)
                        var line = medName
                        if (it.dosage) line += ' • ' + it.dosage
                        if (it.frequency) line += ' • ' + it.frequency
                        if (it.duration) line += ' • ' + it.duration
                        rxLines.push(line)
                    })
                })
                var rxHtml = rxLines.length
                    ? '<ul class="mt-2 space-y-1 text-[0.72rem] text-slate-600">' + rxLines.slice(0, 6).map(function (l) { return '<li class="flex gap-2"><span class="text-slate-400">•</span><span>' + l + '</span></li>' }).join('') + '</ul>'
                    : '<div class="mt-2 text-[0.72rem] text-slate-400">No prescriptions</div>'

                var notesHtml = notes ? '<div class="mt-2 text-[0.72rem] text-slate-600">' + notes + '</div>' : ''
                var vitalsRows = []
                if (visitVitals && visitVitals.height_cm != null && visitVitals.height_cm !== '') vitalsRows.push('<div><span class="font-semibold text-slate-700">Height:</span> ' + escapeHtml(formatVitalNumber(visitVitals.height_cm)) + ' cm</div>')
                if (visitVitals && visitVitals.weight_kg != null && visitVitals.weight_kg !== '') vitalsRows.push('<div><span class="font-semibold text-slate-700">Weight:</span> ' + escapeHtml(formatVitalNumber(visitVitals.weight_kg)) + ' kg</div>')
                if (visitVitals && visitVitals.blood_pressure) vitalsRows.push('<div><span class="font-semibold text-slate-700">Blood pressure:</span> ' + escapeHtml(visitVitals.blood_pressure) + '</div>')
                if (visitVitals && visitVitals.temperature != null && visitVitals.temperature !== '') vitalsRows.push('<div><span class="font-semibold text-slate-700">Temperature:</span> ' + escapeHtml(formatVitalNumber(visitVitals.temperature)) + '</div>')
                if (visitVitals && visitVitals.pulse_rate != null && visitVitals.pulse_rate !== '') vitalsRows.push('<div><span class="font-semibold text-slate-700">Pulse rate:</span> ' + escapeHtml(formatVitalNumber(visitVitals.pulse_rate)) + ' bpm</div>')
                var vitalsButtonLabel = vitalsRows.length ? 'Show vitals' : 'No vitals recorded'
                var vitalsButtonDisabled = vitalsRows.length ? '' : ' disabled'
                var vitalsButtonClass = vitalsRows.length
                    ? 'mt-2 inline-flex items-center justify-center rounded-lg border border-green-200 bg-green-50 px-2.5 py-1 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100'
                    : 'mt-2 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[0.72rem] font-semibold text-slate-400 cursor-not-allowed'
                var vitalsHtml = vitalsRows.length
                    ? '<div class="mt-2">' +
                        '<button type="button" class="' + vitalsButtonClass + '" data-toggle-history-vitals="' + escapeHtml(String(tx.transaction_id || '')) + '" data-expand-label="Show vitals" data-collapse-label="Retract vitals">' + vitalsButtonLabel + '</button>' +
                        '<div class="hidden mt-2 rounded-lg border border-green-100 bg-white px-3 py-2 text-[0.72rem] text-slate-600 space-y-1" data-history-vitals-body="' + escapeHtml(String(tx.transaction_id || '')) + '">' + vitalsRows.join('') + '</div>' +
                    '</div>'
                    : '<div class="mt-2"><button type="button" class="' + vitalsButtonClass + '"' + vitalsButtonDisabled + '>' + vitalsButtonLabel + '</button></div>'

                return '' +
                    '<div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3">' +
                        '<div class="flex items-start justify-between gap-2">' +
                            '<div>' +
                                '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Visit</div>' +
                                '<div class="text-[0.85rem] font-semibold text-slate-900">' + dateStr + (timeStr ? (' ' + timeStr) : '') + '</div>' +
                            '</div>' +
                            '<div class="text-right text-[0.72rem] text-slate-400">#' + (tx.transaction_id || '') + '</div>' +
                        '</div>' +
                        '<div class="mt-2 text-[0.78rem] text-slate-700"><span class="font-semibold">Dx:</span> ' + dx + '</div>' +
                        notesHtml +
                        vitalsHtml +
                        rxHtml +
                    '</div>'
            }).join('')
        }

        function computeConflicts() {
            var drugAllergies = state.medicalBackground
                .filter(function (b) { return normalizeString(b.category) === 'allergy_drug' })
                .map(function (b) { return normalizeString(b.name) })
                .filter(Boolean)

            if (!drugAllergies.length) return []

            var rows = getPrescriptionRows()
            var conflicts = []
            rows.forEach(function (r) {
                var med = state.medicinesById[r.medicine_id]
                if (!med) return
                var contra = normalizeString(med.contraindications || '')
                var medName = normalizeString(medicineDisplayName(med))
                drugAllergies.forEach(function (a) {
                    if (a && (contra.indexOf(a) !== -1 || medName.indexOf(a) !== -1)) {
                        conflicts.push({ allergy: a, medicine: medicineDisplayName(med) })
                    }
                })
            })
            return conflicts
        }

        function renderSafety() {
            var conflicts = computeConflicts()
            if (!conflicts.length) {
                setVisible(safetyBox, false)
                return
            }
            var lines = conflicts.slice(0, 8).map(function (c) {
                return '• Possible conflict: ' + c.medicine + ' vs allergy "' + c.allergy + '"'
            })
            safetyBox.textContent = 'Safety warnings:\n' + lines.join('\n')
            setVisible(safetyBox, true)
        }

        function loadMedicines() {
            return api('{{ url('/api/medicines') }}?per_page=15').then(function (resp) {
                state.medicines = getPaginatedData(resp)
                state.medicinesById = {}
                state.medicines.forEach(function (m) {
                    state.medicinesById[String(m.medicine_id)] = m
                })
            })
        }

        function loadDoctorUser() {
            return api('{{ url('/api/user') }}').then(function (u) {
                state.doctorUserId = u && u.user_id ? u.user_id : null
            })
        }

        function loadAppointment(appointmentId) {
            setVisible(snapshotLoading, true)
            return api('{{ url('/api/appointments') }}/' + appointmentId).then(function (appt) {
                state.appointmentId = appt.appointment_id
                state.patientId = appt.patient_id
                state.parentUserId = appt.patient && appt.patient.parent_user_id ? appt.patient.parent_user_id : null
                state.transactionId = appt.transaction ? appt.transaction.transaction_id : null
                state.prescriptionId = null
                state.existingItemIds = []

                setText(elPatientName, formatName(appt.patient))
                var sex = appt.patient && appt.patient.sex ? appt.patient.sex : ''
                var age = appt.patient && appt.patient.age ? String(appt.patient.age) : computeAgeFromBirthdate(appt.patient ? appt.patient.birthdate : '')
                var metaParts = []
                if (sex) metaParts.push(sex)
                if (age) metaParts.push(age + ' yrs')
                metaParts.push(appt.patient && appt.patient.is_dependent ? 'Dependent' : 'Regular')
                setText(elPatientMeta, metaParts.filter(Boolean).join(' | ') || '-')

                var dt = appt.appointment_datetime || ''
                var dateStr = dt ? dt.toString().slice(0, 10) : '-'
                var timeStr = dt ? dt.toString().slice(11, 16) : '-'
                setText(elApptDateTime, dateStr + ' ' + timeStr)
                setText(elApptType, appt.appointment_type ? appt.appointment_type.toString().replace('_', '-') : '-')
                setText(elPhone, appt.patient && appt.patient.contact_number ? appt.patient.contact_number : '-')
                setText(elEmail, appt.patient && appt.patient.email ? appt.patient.email : '-')
                setText(elAddress, appt.patient && appt.patient.address ? appt.patient.address : '-')

                if (appt.patient && appt.patient.is_dependent && state.parentUserId) {
                    setText(elDepStatus, 'Dependent of:')
                    return api('{{ url('/api/users') }}/' + state.parentUserId).then(function (parent) {
                        setText(elParentName, formatName(parent))
                        var pPhone = parent && parent.contact_number ? parent.contact_number : ''
                        var pEmail = parent && parent.email ? parent.email : ''
                        setText(elParentMeta, [pPhone, pEmail].filter(Boolean).join(' • '))
                    }).catch(function () {
                        setText(elParentName, 'Parent record unavailable')
                        setText(elParentMeta, '')
                    })
                }

                setText(elDepStatus, appt.patient && appt.patient.is_dependent ? 'Dependent' : 'Not a dependent')
                setText(elParentName, '')
                setText(elParentMeta, '')
            }).catch(function (err) {
                if (typeof showToast === 'function') showToast(err && err.body ? err.body : 'Unable to load appointment details.', 'error')
                throw err
            }).finally(function () {
                setVisible(snapshotLoading, false)
            })
        }

        function loadMedicalBackground(patientId) {
            return api('{{ url('/api/medical-backgrounds') }}?patient_id=' + patientId + '&per_page=15').then(function (resp) {
                state.medicalBackground = getPaginatedData(resp)
                renderBackground(state.medicalBackground)
                renderSafety()
            })
        }

        function loadVitalsData(patientId, appointmentId) {
            state.vitals = null
            state.historyVitalsByAppointment = {}
            renderVitalsSummary()
            applyVitalsToForm(null)
            if (!patientId) {
                return Promise.resolve()
            }

            return api('{{ url('/api/vitals') }}?patient_id=' + encodeURIComponent(String(patientId)) + '&per_page=15')
                .then(function (resp) {
                    var rows = getPaginatedData(resp)

                    // Build vitals-by-appointment map for history tab
                    var byAppointment = {}
                    rows.forEach(function (row) {
                        if (!row || row.appointment_id == null) return
                        var key = String(row.appointment_id)
                        if (!byAppointment[key]) byAppointment[key] = row
                    })
                    state.historyVitalsByAppointment = byAppointment

                    // Set current appointment's vitals (if appointmentId provided)
                    if (appointmentId) {
                        var current = rows.find(function (r) {
                            return r && String(r.appointment_id) === String(appointmentId)
                        })
                        state.vitals = current || null
                    }
                    applyVitalsToForm(state.vitals)
                    renderVitalsSummary()
                }).catch(function () {
                    state.vitals = null
                    state.historyVitalsByAppointment = {}
                    renderVitalsSummary()
                })
        }

        function loadHistory(patientId) {
            setVisible(historyLoading, true)
            return api('{{ url('/api/visits') }}?patient_id=' + patientId + '&per_page=15').then(function (resp) {
                state.history = getPaginatedData(resp)
                setText(elTotalVisits, String(state.history.length))
                var last = state.history.length ? state.history[0] : null
                var dt = last ? (last.visit_datetime || last.transaction_datetime || '') : ''
                setText(elLastVisit, dt ? dt.toString().slice(0, 10) : '-')
                renderHistory()
            }).catch(function (err) {
                if (typeof showToast === 'function') showToast(err && err.body ? err.body : 'Unable to load patient history.', 'error')
            }).finally(function () {
                setVisible(historyLoading, false)
            })
        }

        function loadExistingDraft() {
            if (!state.transactionId) {
                return Promise.resolve()
            }
            return api('{{ url('/api/transactions') }}/' + state.transactionId).then(function (tx) {
                if (diagnosisEl) diagnosisEl.value = tx.diagnosis || ''
                if (treatmentEl) treatmentEl.value = tx.treatment_notes || ''
                var rx = tx.prescriptions && tx.prescriptions.length ? tx.prescriptions[0] : null
                if (rx) {
                    state.prescriptionId = rx.prescription_id
                    state.existingItemIds = (rx.items || []).map(function (it) { return it.item_id })
                    if (prescriptionBody) prescriptionBody.innerHTML = ''
                    if (rx.items && rx.items.length) {
                        rx.items.forEach(function (it) {
                            ensureRow({
                                medicine_id: it.medicine_id,
                                dosage: it.dosage,
                                frequency: it.frequency,
                                duration: it.duration,
                                instructions: it.instructions,
                            })
                        })
                    }
                }
            })
        }

        function saveVitals() {
            setVitalsModalError('')
            setVitalsFeedback('')

            if (!state.appointmentId) {
                setVitalsModalError('Select an appointment first.')
                return Promise.resolve(false)
            }

            var payload = normalizeVitalPayload()
            var hasAnyValue = Object.keys(payload).some(function (key) {
                return payload[key] != null && String(payload[key]).trim() !== ''
            })

            if (!hasAnyValue) {
                setVitalsModalError('Provide at least one vital sign or close the modal to skip.')
                return Promise.resolve(false)
            }

            payload.appointment_id = state.appointmentId
            setVitalsLoading(true)

            return api('{{ url('/api/vitals') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            }).then(function (vital) {
                state.vitals = vital || null
                applyVitalsToForm(state.vitals)
                renderVitalsSummary()
                closeVitalsModal()
                setVitalsFeedback('Vitals saved successfully.', 'success')
                return true
            }).catch(function (err) {
                var message = err && err.body ? err.body : 'Unable to save vitals.'
                try {
                    var parsed = JSON.parse(message)
                    if (parsed && parsed.message) message = parsed.message
                } catch (e) {}
                setVitalsModalError(message)
                setVitalsFeedback(message, 'error')
                return false
            }).finally(function () {
                setVitalsLoading(false)
            })
        }

        function saveAll() {
            clearSuccessTimer()

            if (!state.appointmentId) {
                if (typeof showToast === 'function') showToast('Select an appointment first.', 'error')
                return Promise.resolve(false)
            }

            var conflicts = computeConflicts()
            if (conflicts.length && (!acknowledgeEl || !acknowledgeEl.checked)) {
                if (typeof showToast === 'function') showToast('Safety warnings detected. Check "Override safety warnings" to proceed.', 'error')
                return Promise.resolve(false)
            }

            var prescriptionRows = getPrescriptionRows()
            var shouldSavePrescription = prescriptionRows.length > 0 || !!state.prescriptionId

            var payload = {
                appointment_id: state.appointmentId,
                diagnosis: diagnosisEl ? diagnosisEl.value : '',
                treatment_notes: treatmentEl ? treatmentEl.value : '',
                visit_datetime: new Date().toISOString().slice(0, 19).replace('T', ' '),
            }

            var txPromise = state.transactionId
                ? api('{{ url('/api/transactions') }}/' + state.transactionId, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                })
                : api('{{ url('/api/transactions') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                })

            return txPromise.then(function (tx) {
                state.transactionId = tx.transaction_id
                state.lastSavedTransactionId = tx.transaction_id
                var rxPayload = {
                    transaction_id: state.transactionId,
                    doctor_id: state.doctorUserId,
                    prescribed_datetime: new Date().toISOString().slice(0, 19).replace('T', ' '),
                    notes: null,
                }

                if (!shouldSavePrescription) {
                    state.prescriptionId = null
                    return null
                }

                var rxPromise = state.prescriptionId
                    ? api('{{ url('/api/prescriptions') }}/' + state.prescriptionId, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(rxPayload),
                    })
                    : api('{{ url('/api/prescriptions') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(rxPayload),
                    })

                return rxPromise.then(function (rx) {
                    state.prescriptionId = rx.prescription_id

                    var deletes = state.existingItemIds.reduce(function (p, id) {
                        return p.then(function () {
                            return api('{{ url('/api/prescription-items') }}/' + id, { method: 'DELETE' }).catch(function () {})
                        })
                    }, Promise.resolve())

                    return deletes.then(function () {
                        return prescriptionRows.reduce(function (p, row) {
                            return p.then(function () {
                                return api('{{ url('/api/prescription-items') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({
                                        prescription_id: state.prescriptionId,
                                        medicine_id: Number(row.medicine_id),
                                        dosage: row.dosage || null,
                                        frequency: row.frequency || null,
                                        duration: row.duration || null,
                                        instructions: row.instructions || null,
                                    }),
                                })
                            })
                        }, Promise.resolve())
                    })
                })
            }).then(function () {
                return loadExistingDraft()
            }).then(function () {
                var successMessage = shouldSavePrescription
                    ? 'Saved consultation and prescription successfully. Appointment is now consulted.'
                    : 'Saved consultation notes successfully. Appointment is now consulted and remains active until payment is recorded.'
                markSelectedAppointmentConsulted()
                showSaveSuccess(successMessage, !!state.lastSavedTransactionId)
                return loadHistory(state.patientId)
            }).then(function () {
                return true
            }).catch(function (err) {
                if (typeof showToast === 'function') showToast(err && err.body ? err.body : 'Unable to save consultation.', 'error')
                return false
            })
        }

        function handleAppointmentChange() {
            var id = appointmentSelect ? appointmentSelect.value : ''
            resetWorkspace()
            if (!id) return
            state.appointmentId = id
            loadAppointment(id).then(function () {
                return Promise.all([
                    loadMedicalBackground(state.patientId),
                    loadHistory(state.patientId),
                    loadVitalsData(state.patientId, state.appointmentId),
                    loadExistingDraft(),
                ])
            }).then(function () {
                if (prescriptionBody && !prescriptionBody.querySelector('tr')) {
                    ensureRow()
                }
                renderSafety()
            }).catch(function () {})
        }

        if (historyFilter) {
            historyFilter.addEventListener('change', renderHistory)
        }
        if (historyTimeline) {
            historyTimeline.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('[data-toggle-history-vitals]') : null
                if (!btn || btn.disabled) return
                var id = btn.getAttribute('data-toggle-history-vitals')
                var body = historyTimeline.querySelector('[data-history-vitals-body="' + id + '"]')
                if (!body) return
                var isHidden = body.classList.contains('hidden')
                body.classList.toggle('hidden', !isHidden)
                btn.textContent = isHidden
                    ? (btn.getAttribute('data-collapse-label') || 'Retract vitals')
                    : (btn.getAttribute('data-expand-label') || 'Show vitals')
            })
        }

        if (appointmentSelect) {
            appointmentSelect.addEventListener('change', handleAppointmentChange)
        }

        if (vitalsBtn) {
            vitalsBtn.addEventListener('click', openVitalsModal)
        }
        if (vitalsClose) {
            vitalsClose.addEventListener('click', closeVitalsModal)
        }
        if (vitalsSkip) {
            vitalsSkip.addEventListener('click', closeVitalsModal)
        }
        if (vitalsModal) {
            vitalsModal.addEventListener('click', function (e) {
                if (e.target === vitalsModal) closeVitalsModal()
            })
        }
        if (vitalsSave) {
            vitalsSave.addEventListener('click', function () {
                if (vitalsSave.disabled) return
                saveVitals()
            })
        }

        if (safetyModalClose) {
            safetyModalClose.addEventListener('click', hideSafetyModal)
        }
        if (safetyModal) {
            safetyModal.addEventListener('click', function (e) {
                if (e.target === safetyModal) hideSafetyModal()
            })
        }
        if (safetyModalAck) {
            safetyModalAck.addEventListener('click', function () {
                if (acknowledgeEl) acknowledgeEl.checked = true
                hideSafetyModal()
            })
        }
        if (confirmClose) {
            confirmClose.addEventListener('click', closeConfirmModal)
        }
        if (confirmCancel) {
            confirmCancel.addEventListener('click', closeConfirmModal)
        }
        if (confirmModal) {
            confirmModal.addEventListener('click', function (e) {
                if (e.target === confirmModal) closeConfirmModal()
            })
        }
        if (confirmSubmit) {
            confirmSubmit.addEventListener('click', function () {
                if (confirmSubmit.disabled) return
                closeConfirmModal()
                setSubmitLoading(true)
                saveAll().then(function (ok) {
                }).finally(function () {
                    setSubmitLoading(false)
                })
            })
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (diagnosisEl) diagnosisEl.value = ''
                if (treatmentEl) treatmentEl.value = ''
                if (prescriptionBody) prescriptionBody.innerHTML = ''
                ensureRow()
                setVitalsFeedback('')
                renderSafety()
                setPrintVisible(false)
                clearSuccessTimer()
            })
        }

        if (addMedBtn) {
            addMedBtn.addEventListener('click', function () {
                ensureRow()
            })
        }

        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                if (saveBtn.disabled) return
                clearSuccessTimer()

                if (!state.appointmentId) {
                    if (typeof showToast === 'function') showToast('Select an appointment first.', 'error')
                    return
                }

                var conflicts = computeConflicts()
                if (conflicts.length && (!acknowledgeEl || !acknowledgeEl.checked)) {
                    if (typeof showToast === 'function') showToast('Safety warnings detected. Check "Override safety warnings" to proceed.', 'error')
                    return
                }

                renderConfirmSummary()
                openConfirmModal()
            })
        }

        if (printBtn) {
            printBtn.addEventListener('click', function () {
                if (!state.lastSavedTransactionId) return
                var url = "{{ url('/print/consultations') }}/" + encodeURIComponent(String(state.lastSavedTransactionId))
                window.open(url, '_blank', 'noopener')
            })
        }

        Promise.all([loadDoctorUser(), loadMedicines()]).then(function () {
            if (appointmentSelect) {
                Array.prototype.slice.call(appointmentSelect.options).forEach(syncAppointmentOptionLabel)
            }
            if (appointmentSelect && appointmentSelect.value) {
                handleAppointmentChange()
                // Sync the button display with the pre-selected value
                updateAppointmentButtonDisplay()
            } else {
                ensureRow()
            }
        }).catch(function () {
            if (typeof showToast === 'function') showToast('Unable to load medicines or user profile.', 'error')
        })

        // ── New: Appointment Selection Modal ──────────────────────────────
        var consultAppointmentBtn = document.getElementById('consultAppointmentBtn')
        var consultAppointmentModal = document.getElementById('consultAppointmentModal')
        var consultAppointmentModalClose = document.getElementById('consultAppointmentModalClose')
        var consultAppointmentDisplay = document.getElementById('consultAppointmentDisplay')
        var consultAppointmentMeta = document.getElementById('consultAppointmentMeta')

        function openAppointmentModal() {
            if (consultAppointmentModal) consultAppointmentModal.classList.remove('hidden')
        }

        function closeAppointmentModal() {
            if (consultAppointmentModal) consultAppointmentModal.classList.add('hidden')
        }

        if (consultAppointmentBtn) {
            consultAppointmentBtn.addEventListener('click', openAppointmentModal)
        }
        if (consultAppointmentModalClose) {
            consultAppointmentModalClose.addEventListener('click', closeAppointmentModal)
        }
        if (consultAppointmentModal) {
            consultAppointmentModal.addEventListener('click', function (e) {
                if (e.target === consultAppointmentModal) closeAppointmentModal()
            })
        }

        // Delegate click on appointment cards in modal
        if (consultAppointmentModal) {
            consultAppointmentModal.addEventListener('click', function (e) {
                var card = e.target.closest('.consult-appt-card')
                if (!card) return
                var apptId = card.getAttribute('data-appointment-id')
                if (!apptId || !appointmentSelect) return
                appointmentSelect.value = apptId
                var changeEvent = new Event('change', { bubbles: true })
                appointmentSelect.dispatchEvent(changeEvent)
                closeAppointmentModal()
            })
        }

        // Update appointment button display
        function updateAppointmentButtonDisplay() {
            if (!consultAppointmentDisplay || !appointmentSelect) return
            var option = appointmentSelect.options[appointmentSelect.selectedIndex]
            if (!option || !option.value) {
                consultAppointmentDisplay.textContent = 'Select appointment'
                if (consultAppointmentMeta) consultAppointmentMeta.textContent = ''
                return
            }
            var label = option.getAttribute('data-label') || option.textContent || ''
            var apptType = option.getAttribute('data-appointment-type') || ''
            var queueStatus = option.getAttribute('data-queue-status') || ''
            var apptStatus = option.getAttribute('data-status') || ''
            // Walk-in: show queue status; Scheduled: show appointment status
            var displayStatus = (apptType === 'walk_in') ? queueStatus : apptStatus
            consultAppointmentDisplay.textContent = label
            if (consultAppointmentMeta) {
                consultAppointmentMeta.textContent = displayStatus ? 'Status: ' + displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1) : ''
            }
        }
        if (appointmentSelect) {
            appointmentSelect.addEventListener('change', updateAppointmentButtonDisplay)
        }

        // ── New: History Detail Modal ─────────────────────────────────────
        var consultHistoryDetailModal = document.getElementById('consultHistoryDetailModal')
        var consultHistoryDetailClose = document.getElementById('consultHistoryDetailClose')
        var consultHistoryDetailDate = document.getElementById('consultHistoryDetailDate')
        var consultHistoryDetailDoctor = document.getElementById('consultHistoryDetailDoctor')
        var consultHistoryDetailType = document.getElementById('consultHistoryDetailType')
        var consultHistoryDetailServices = document.getElementById('consultHistoryDetailServices')
        var consultHistoryDetailReason = document.getElementById('consultHistoryDetailReason')
        var consultHistoryDetailDiagnosis = document.getElementById('consultHistoryDetailDiagnosis')
        var consultHistoryDetailTreatment = document.getElementById('consultHistoryDetailTreatment')
        var consultHistoryDetailPrescriptions = document.getElementById('consultHistoryDetailPrescriptions')
        var consultHistoryDetailTitle = document.getElementById('consultHistoryDetailTitle')

        function openHistoryDetailModal() {
            if (consultHistoryDetailModal) consultHistoryDetailModal.classList.remove('hidden')
        }

        function closeHistoryDetailModal() {
            if (consultHistoryDetailModal) consultHistoryDetailModal.classList.add('hidden')
        }

        if (consultHistoryDetailClose) {
            consultHistoryDetailClose.addEventListener('click', closeHistoryDetailModal)
        }
        if (consultHistoryDetailModal) {
            consultHistoryDetailModal.addEventListener('click', function (e) {
                if (e.target === consultHistoryDetailModal) closeHistoryDetailModal()
            })
        }

        function populateHistoryDetail(tx) {
            if (!tx) return
            var dt = tx.visit_datetime || tx.transaction_datetime || ''
            var dateStr = dt ? dt.toString().slice(0, 10) + ' ' + dt.toString().slice(11, 16) : '-'
            if (consultHistoryDetailDate) consultHistoryDetailDate.textContent = dateStr
            if (consultHistoryDetailDoctor) {
                var doc = tx.doctor || (tx.appointment ? tx.appointment.doctor : null)
                if (doc) {
                    var parts = [doc.firstname, doc.middlename, doc.lastname].filter(function (v) { return v && String(v).trim() !== '' })
                    consultHistoryDetailDoctor.textContent = parts.length ? parts.join(' ') : ('User #' + (doc.user_id || ''))
                } else {
                    consultHistoryDetailDoctor.textContent = '-'
                }
            }
            if (consultHistoryDetailTitle) {
                consultHistoryDetailTitle.textContent = 'Visit - ' + dateStr
            }

            var apptType = (tx.appointment && tx.appointment.appointment_type) || ''
            if (consultHistoryDetailType) consultHistoryDetailType.textContent = apptType ? apptType.replace(/_/g, '-') : '-'

            if (consultHistoryDetailServices) {
                var services = tx.appointment && tx.appointment.services
                if (services && services.length) {
                    consultHistoryDetailServices.textContent = services.map(function (s) { return s.name || s.service_name || s.service_id }).join(', ')
                } else {
                    consultHistoryDetailServices.textContent = '-'
                }
            }

            if (consultHistoryDetailReason) {
                consultHistoryDetailReason.textContent = (tx.appointment && tx.appointment.reason_for_visit) || tx.reason_for_visit || '-'
            }
            if (consultHistoryDetailDiagnosis) {
                consultHistoryDetailDiagnosis.textContent = tx.diagnosis || 'No diagnosis recorded'
            }
            if (consultHistoryDetailTreatment) {
                consultHistoryDetailTreatment.textContent = tx.treatment_notes || 'No treatment notes recorded'
            }
            if (consultHistoryDetailPrescriptions) {
                var rx = tx.prescriptions || []
                if (!rx.length) {
                    consultHistoryDetailPrescriptions.innerHTML = '<div class="text-slate-500">No prescriptions</div>'
                } else {
                    var items = []
                    rx.forEach(function (p) {
                        var pItems = p.items || []
                        pItems.forEach(function (it) {
                            var medName = it.medicine ? medicineDisplayName(it.medicine) : ('Medicine #' + it.medicine_id)
                            var line = medName
                            if (it.dosage) line += ' &bull; ' + it.dosage
                            if (it.frequency) line += ' &bull; ' + it.frequency
                            if (it.duration) line += ' &bull; ' + it.duration
                            if (it.instructions) line += ' &bull; ' + it.instructions
                            items.push(line)
                        })
                    })
                    if (items.length) {
                        consultHistoryDetailPrescriptions.innerHTML = items.map(function (l) {
                            return '<div class="flex gap-2"><span class="text-slate-400">&bull;</span><span>' + l + '</span></div>'
                        }).join('')
                    } else {
                        consultHistoryDetailPrescriptions.innerHTML = '<div class="text-slate-500">No prescription items</div>'
                    }
                }
            }
        }

        // Delegate click on history cards in timeline
        if (historyTimeline) {
            historyTimeline.addEventListener('click', function (e) {
                var card = e.target.closest('[data-history-id]')
                if (!card) return
                var txId = card.getAttribute('data-history-id')
                if (!txId) return
                var tx = state.history.find(function (h) {
                    return String(h.transaction_id || h.appointment_id) === txId
                })
                if (tx) {
                    populateHistoryDetail(tx)
                    openHistoryDetailModal()
                }
            })
        }

        // ── View Details & History Modal ──────────────────────────
        var consultViewProfileBtn = document.getElementById('consultViewProfileBtn')

        // --- Cached data for modal ---
        var cachedPatientData = null
        var cachedMedBgRows = null
        var cachedVisitRows = null
        var cachedVitalRows = null
        var cachedDependentRows = null
        var cachedParentData = null

        var viewOverlay = document.getElementById('doctorPrViewOverlay')
        var viewClose = document.getElementById('doctorPrViewClose')
        var viewProfilePic = document.getElementById('doctorPrViewProfilePic')
        var viewEditBtn = document.getElementById('doctorPrViewEditBtn')
        var viewTabButtons = Array.prototype.slice.call(document.querySelectorAll('.doctor-pr-view-tab'))
        var viewTabContents = {}
        document.querySelectorAll('.doctor-pr-view-tab-content').forEach(function (el) {
            var id = el.getAttribute('id') || ''
            var key = id.replace('doctorPrViewTab', '').toLowerCase()
            viewTabContents[key] = el
        })
        var prDetailFirstname = document.getElementById('prDetailFirstname')
        var prDetailMiddlename = document.getElementById('prDetailMiddlename')
        var prDetailLastname = document.getElementById('prDetailLastname')
        var prDetailBirthdate = document.getElementById('prDetailBirthdate')
        var prDetailAddress = document.getElementById('prDetailAddress')
        var prDetailSex = document.getElementById('prDetailSex')
        var prDetailCivilStatus = document.getElementById('prDetailCivilStatus')
        var prDetailNationality = document.getElementById('prDetailNationality')
        var prDetailContact = document.getElementById('prDetailContact')
        var prDetailPhic = document.getElementById('prDetailPhic')
        var prDetailOccupation = document.getElementById('prDetailOccupation')
        var prDetailEmergContact = document.getElementById('prDetailEmergContact')
        var prDetailEmergNumber = document.getElementById('prDetailEmergNumber')
        var viewVerificationStatus = document.getElementById('doctorPrViewVerificationStatus')
        var viewPatientType = document.getElementById('doctorPrViewPatientType')
        var viewVerificationId = document.getElementById('doctorPrViewVerificationId')

        var currentViewTab = 'profile'

        var activeDependentRecord = null
        var activeDependentTab = 'background'
        var activeDependentMedBgRows = null
        var activeDependentVisitRows = null
        var activeDependentVitalRows = null
        var activeDependentVerification = null

        window._doctorShowingDependentProfile = false
        window._doctorDependentProfileId = null

        var defaultProfilePicHtml = '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'

        // --- Helper functions ---
        function displayValue(value) {
            return (value != null && value !== '') ? String(value) : '-'
        }

        function sexLabel(value) {
            var text = displayValue(value)
            if (text === '-') return text
            return text.charAt(0).toUpperCase() + text.slice(1)
        }

        function fullName(p, fallback) {
            if (!p) return fallback || '-'
            var parts = []
            if (p.firstname) parts.push(String(p.firstname))
            if (p.middlename) parts.push(String(p.middlename))
            if (p.lastname) parts.push(String(p.lastname))
            var name = parts.join(' ').trim()
            if (name) return name
            if (p.email) return String(p.email)
            return fallback || ('#' + (p.user_id || ''))
        }

        function categoryLabel(key) {
            var k = String(key || '')
            if (k === 'allergy_food') return 'Food'
            if (k === 'allergy_drug') return 'Drug'
            if (k === 'condition') return 'Condition'
            return k || '-'
        }

        function buildTableHtml(headers, rowsHtml, emptyMessage, loadingMessage) {
            var headerHtml = headers.map(function (header) {
                return '<th class="py-2 pr-4 font-semibold">' + escapeHtml(header) + '</th>'
            }).join('')
            var bodyHtml = rowsHtml
            if (!bodyHtml) {
                var message = loadingMessage || emptyMessage
                bodyHtml = '<tr><td colspan="' + headers.length + '" class="py-4 text-center text-[0.78rem] text-slate-400">' + escapeHtml(message) + '</td></tr>'
            }
            return '<div class="overflow-x-auto"><table class="min-w-full text-left text-xs text-slate-600"><thead><tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">' + headerHtml + '</tr></thead><tbody>' + bodyHtml + '</tbody></table></div>'
        }

        function formatRecordedAt(value) {
            var raw = value ? String(value) : ''
            if (!raw) return '-'
            return raw.replace('T', ' ').slice(0, 16)
        }

        function formatNumeric(value, decimals) {
            if (value == null || value === '') return '-'
            var num = typeof value === 'number' ? value : parseFloat(value)
            if (isNaN(num)) return '-'
            return num.toFixed(decimals == null ? 1 : decimals)
        }

        function formatCurrency(value) {
            if (value == null || value === '') return '-'
            var num = typeof value === 'number' ? value : parseFloat(value)
            if (isNaN(num)) return '-'
            return 'PHP ' + num.toFixed(2)
        }

        function formatBirthdate(val) {
            if (!val) return '-'
            return String(val).slice(0, 10)
        }

        function ageFromBirthdate(birthdate) {
            if (!birthdate) return null
            var d = new Date(String(birthdate))
            if (isNaN(d.getTime())) return null
            var today = new Date()
            var age = today.getFullYear() - d.getFullYear()
            var m = today.getMonth() - d.getMonth()
            if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--
            if (age < 0) return null
            return age
        }

        // --- Modal logic ---
        function setViewTabActive(tabKey) {
            viewTabButtons.forEach(function (btn) {
                var key = btn.getAttribute('data-view-tab') || ''
                btn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                if (key === tabKey) {
                    btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
                } else {
                    btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                }
            })
        }

        // --- Medical Background add-entry & per-row edit ---
        var medBgEditingId = null

        function buildCategoryOptions(selected) {
            var cats = [
                { value: 'allergy_food', label: 'Food' },
                { value: 'allergy_drug', label: 'Drug' },
                { value: 'condition', label: 'Condition' },
                { value: 'history_present_illness', label: 'Present Illness' },
                { value: 'family_social_history', label: 'Family/Social' },
                { value: 'surgical_history', label: 'Surgical' },
            ]
            return cats.map(function (c) {
                var sel = c.value === selected ? ' selected' : ''
                return '<option value="' + c.value + '"' + sel + '>' + escapeHtml(c.label) + '</option>'
            }).join('')
        }

        function renderViewTabContent(tabKey) {
            var container = viewTabContents[tabKey]
            if (!container) return
            if (tabKey === 'background') {
                var headers = ['Category', 'Name', 'Diagnosis Date', 'Procedure Date', 'Notes', '']
                if (cachedMedBgRows == null) {
                    container.innerHTML = buildTableHtml(headers, '', 'No medical background entries found.', 'Loading medical background entries...')
                    return
                }
                var rowsHtml = ''
                cachedMedBgRows.forEach(function (row) {
                    var rowId = row && row.medical_background_id ? String(row.medical_background_id) : ''
                    var rawDate = row && row.diagnosis_date ? String(row.diagnosis_date) : ''
                    var diagnosisDate = rawDate ? rawDate.slice(0, 10) : ''
                    var procedureDate = row && row.procedure_date ? String(row.procedure_date).slice(0, 10) : ''
                    // Per-row edit mode
                    if (medBgEditingId === rowId) {
                        var prefix = 'medbg-edit-' + rowId
                        var catOpts = buildCategoryOptions(row.category)
                        var dtPicker = '<input type="date" value="' + escapeHtml(diagnosisDate) + '" class="' + prefix + '-date w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">'
                        var procPicker = '<input type="date" value="' + escapeHtml(procedureDate) + '" class="' + prefix + '-proc w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">'
                        var notesInput = '<input type="text" value="' + escapeHtml(row && row.notes ? String(row.notes) : '') + '" class="' + prefix + '-notes w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400" placeholder="Notes">'
                        rowsHtml += '<tr class="border-b border-amber-200 bg-amber-50/40">' +
                            '<td class="py-2 pr-4"><select class="' + prefix + '-cat w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">' + catOpts + '</select></td>' +
                            '<td class="py-2 pr-4"><input type="text" value="' + escapeHtml(row && row.name ? String(row.name) : '') + '" class="' + prefix + '-name w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400"></td>' +
                            '<td class="py-2 pr-4">' + dtPicker + '</td>' +
                            '<td class="py-2 pr-4">' + procPicker + '</td>' +
                            '<td class="py-2 pr-4">' + notesInput + '</td>' +
                            '<td class="py-2 pr-4 text-right whitespace-nowrap">' +
                                '<button type="button" class="medbg-edit-save px-2 py-1 rounded-lg border border-green-300 bg-green-600 text-[0.7rem] font-semibold text-white hover:bg-green-700 disabled:opacity-50" data-medbg-id="' + escapeHtml(rowId) + '">Save</button>' +
                                '<button type="button" class="medbg-edit-cancel ml-1 px-2 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-500 hover:bg-slate-50" data-medbg-id="' + escapeHtml(rowId) + '">Cancel</button>' +
                            '</td>' +
                        '</tr>'
                    } else {
                        rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(categoryLabel(row.category)) + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(row && row.name ? String(row.name) : '-') + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosisDate ? escapeHtml(diagnosisDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (procedureDate ? escapeHtml(procedureDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (row && row.notes ? escapeHtml(String(row.notes)) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="py-2 pr-4 text-right"><button type="button" class="medbg-edit-btn text-[0.65rem] font-semibold text-green-600 hover:text-green-700 underline" data-medbg-id="' + escapeHtml(rowId) + '">Edit</button></td>' +
                        '</tr>'
                    }
                })
                var headerHtml = '<div class="flex items-center justify-between mb-3">' +
                    '<div class="text-[0.72rem] font-semibold text-slate-700">Medical Background</div>' +
                    '<button type="button" class="medbg-add-btn text-[0.7rem] font-semibold text-green-700 hover:text-green-800 underline">+ Add entry</button>' +
                '</div>'
                container.innerHTML = headerHtml + buildTableHtml(headers, rowsHtml, 'No medical background entries found.')
            } else if (tabKey === 'visits') {
                var headers = ['Doctor', 'Visit date', 'Fees', 'Status', 'Action']
                if (cachedVisitRows == null) {
                    container.innerHTML = buildTableHtml(headers, '', 'No visits found.', 'Loading visit history...')
                    return
                }
                var rowsHtml = ''
                cachedVisitRows.forEach(function (visit) {
                    var appointment = visit && visit.appointment ? visit.appointment : null
                    var doctor = appointment && appointment.doctor ? appointment.doctor : null
                    var dateRaw = visit && (visit.visit_datetime || visit.transaction_datetime) ? String(visit.visit_datetime || visit.transaction_datetime) : ''
                    var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '-'
                    var apptStatus = (appointment && appointment.status) ? String(appointment.status) : ''
                    var statusColors = {
                        pending: 'bg-amber-50 text-amber-700 border-amber-200',
                        confirmed: 'bg-blue-50 text-blue-700 border-blue-200',
                        completed: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        cancelled: 'bg-red-50 text-red-700 border-red-200',
                        no_show: 'bg-slate-100 text-slate-600 border-slate-200',
                        consulted: 'bg-green-50 text-green-700 border-green-100',
                        waiting: 'bg-amber-50 text-amber-700 border-amber-100',
                        serving: 'bg-blue-50 text-blue-700 border-blue-100',
                        done: 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        skipped: 'bg-orange-50 text-orange-700 border-orange-100',
                        on_hold: 'bg-purple-50 text-purple-700 border-purple-100',
                    }
                    var statusClass = statusColors[apptStatus] || 'bg-slate-50 text-slate-600 border-slate-100'
                    var statusLabel = apptStatus ? apptStatus.charAt(0).toUpperCase() + apptStatus.slice(1).replace(/_/g, ' ') : '-'
                    rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(fullName(doctor, 'Doctor')) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(dateText) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(formatCurrency(visit && visit.amount != null ? visit.amount : '')) + '</td>' +
                        '<td class="py-2 pr-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-medium border ' + statusClass + '">' + escapeHtml(statusLabel) + '</span></td>' +
                        '<td class="py-2 pr-4"><button type="button" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 doctor-visit-detail-btn" data-visit=\'' + escapeHtml(JSON.stringify(visit).replace(/'/g, '&#39;')) + '\'>Details</button></td>' +
                    '</tr>'
                })
                container.innerHTML = buildTableHtml(headers, rowsHtml, 'No visits found.')
            } else if (tabKey === 'vitals') {
                var headers = ['Recorded', 'Height (cm)', 'Weight (kg)', 'BP', 'Temp', 'Pulse']
                if (cachedVitalRows == null) {
                    container.innerHTML = buildTableHtml(headers, '', 'No vitals found.', 'Loading vitals history...')
                    return
                }
                var rowsHtml = ''
                cachedVitalRows.forEach(function (vital) {
                    var recorded = formatRecordedAt(vital && vital.recorded_at ? vital.recorded_at : (vital && vital.appointment_datetime ? vital.appointment_datetime : ''))
                    var height = vital && vital.height_cm != null ? formatNumeric(vital.height_cm, 1) : '-'
                    var weight = vital && vital.weight_kg != null ? formatNumeric(vital.weight_kg, 1) : '-'
                    var bp = vital && vital.blood_pressure ? String(vital.blood_pressure) : '-'
                    var temp = vital && vital.temperature != null ? formatNumeric(vital.temperature, 1) : '-'
                    var pulse = vital && vital.pulse_rate != null ? String(vital.pulse_rate) : '-'
                    rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(recorded) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(height) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(weight) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(bp) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(temp) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(pulse) + '</td>' +
                    '</tr>'
                })
                container.innerHTML = buildTableHtml(headers, rowsHtml, 'No vitals found.')
            } else if (tabKey === 'dependents') {
                var patient = currentPatientId ? findPatientById(currentPatientId) : null
                var isDependent = patient && patient.is_dependent
                var depBtn = document.getElementById('doctorPrViewTabDependentsBtn')
                if (depBtn) depBtn.textContent = isDependent ? 'Parent/Guardian' : 'Dependents'

                if (window._doctorShowingDependentProfile) {
                    renderDependentProfileInline(container)
                    return
                }

                if (isDependent) {
                    var parentId = patient.parent_user_id
                    if (!parentId) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">No parent/guardian linked to this account.</div></div>'
                        return
                    }
                    if (!cachedParentData) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading parent information...</div></div>'
                        fetchConsultParentData(parentId)
                        return
                    }
                    renderParentOrDependentCards(container, [cachedParentData], 'parent')
                } else {
                    if (cachedDependentRows == null) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading dependents...</div></div>'
                        return
                    }
                    if (!cachedDependentRows.length) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">No dependents found for this patient.</div></div>'
                        return
                    }
                    renderParentOrDependentCards(container, cachedDependentRows, 'dependent')
                }
            }
        }

        function setViewTab(tabKey) {
            if (!tabKey || !currentPatientId) return
            currentViewTab = tabKey
            setViewTabActive(tabKey)
            Object.keys(viewTabContents).forEach(function (key) {
                if (viewTabContents[key]) {
                    viewTabContents[key].classList.toggle('hidden', key !== tabKey)
                }
            })
            renderViewTabContent(tabKey)
        }

        // Medical Background click handlers: add entry, edit, save, cancel
        var bgContainer = viewTabContents['background']
        if (bgContainer) {
            bgContainer.addEventListener('click', function (e) {
                // + Add entry button
                var addBtn = e.target.closest('.medbg-add-btn')
                if (addBtn) {
                    e.preventDefault()
                    var tbody = bgContainer.querySelector('table tbody')
                    if (!tbody) return
                    var uid = 'new-' + Date.now()
                    var dtPicker = '<input type="date" class="medbg-' + uid + '-date w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">'
                    var tr = document.createElement('tr')
                    tr.className = 'border-b border-green-200 bg-green-50/40'
                    tr.setAttribute('data-new-row', uid)
                    tr.innerHTML =
                        '<td class="py-2 pr-4">' +
                            '<select class="medbg-' + uid + '-cat w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">' +
                                buildCategoryOptions('') +
                            '</select>' +
                        '</td>' +
                        '<td class="py-2 pr-4">' +
                            '<input type="text" class="medbg-' + uid + '-name w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400" placeholder="e.g. Penicillin">' +
                        '</td>' +
                        '<td class="py-2 pr-4">' + dtPicker + '</td>' +
                        '<td class="py-2 pr-4">' +
                            '<input type="date" class="medbg-' + uid + '-proc w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">' +
                        '</td>' +
                        '<td class="py-2 pr-4">' +
                            '<input type="text" class="medbg-' + uid + '-notes w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400" placeholder="Notes">' +
                        '</td>' +
                        '<td class="py-2 pr-4 text-right whitespace-nowrap">' +
                            '<button type="button" class="medbg-new-save px-2 py-1 rounded-lg border border-green-300 bg-green-600 text-[0.7rem] font-semibold text-white hover:bg-green-700 disabled:opacity-50" data-new-uid="' + uid + '">Save</button>' +
                            '<button type="button" class="medbg-new-cancel ml-1 px-2 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-500 hover:bg-slate-50" data-new-uid="' + uid + '">Cancel</button>' +
                        '</td>'
                    tbody.insertBefore(tr, tbody.firstChild)
                    return
                }

                // Edit existing entry button
                var editBtn = e.target.closest('.medbg-edit-btn')
                if (editBtn) {
                    medBgEditingId = editBtn.getAttribute('data-medbg-id') || null
                    renderViewTabContent('background')
                    return
                }

                // Cancel editing existing entry
                var editCancel = e.target.closest('.medbg-edit-cancel')
                if (editCancel) {
                    medBgEditingId = null
                    renderViewTabContent('background')
                    return
                }

                // Save edited entry
                var editSave = e.target.closest('.medbg-edit-save')
                if (editSave && !editSave.disabled) {
                    var rowId = editSave.getAttribute('data-medbg-id')
                    if (!rowId) return
                    var prefix = 'medbg-edit-' + rowId
                    var catEl = bgContainer.querySelector('.' + prefix + '-cat')
                    var nameEl = bgContainer.querySelector('.' + prefix + '-name')
                    var dateEl = bgContainer.querySelector('.' + prefix + '-date')
                    var procEl = bgContainer.querySelector('.' + prefix + '-proc')
                    var notesEl = bgContainer.querySelector('.' + prefix + '-notes')
                    if (!catEl || !nameEl) return
                    var category = catEl.value
                    var name = nameEl.value.trim()
                    if (!category || !name) {
                        if (typeof showToast === 'function') showToast('Category and Name are required.', 'error')
                        return
                    }
                    var diagnosisDate = dateEl ? dateEl.value || null : null
                    var procedureDate = procEl ? procEl.value || null : null
                    var notes = notesEl ? notesEl.value.trim() || null : null
                    editSave.disabled = true
                    editSave.textContent = 'Saving...'
                    var payload = {
                        category: category,
                        name: name,
                        diagnosis_date: diagnosisDate,
                        procedure_date: procedureDate,
                        notes: notes,
                    }
                    api('{{ url('/api/medical-backgrounds') }}/' + encodeURIComponent(rowId), {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                    }).then(function (result) {
                        medBgEditingId = null
                        // Refresh the whole medical background list
                        loadPatientPanelData(currentPatientId)
                        if (typeof showToast === 'function') showToast('Entry updated.', 'success')
                    }).catch(function (err) {
                        editSave.disabled = false
                        editSave.textContent = 'Save'
                        if (typeof showToast === 'function') showToast('Failed to update entry.', 'error')
                    })
                    return
                }

                // Save new entry
                var saveBtn = e.target.closest('.medbg-new-save')
                if (!saveBtn || saveBtn.disabled) return
                var uid = saveBtn.getAttribute('data-new-uid')
                if (!uid) return
                var prefix = 'medbg-' + uid
                var catEl = bgContainer.querySelector('.' + prefix + '-cat')
                var nameEl = bgContainer.querySelector('.' + prefix + '-name')
                var dateEl = bgContainer.querySelector('.' + prefix + '-date')
                var procEl = bgContainer.querySelector('.' + prefix + '-proc')
                var notesEl = bgContainer.querySelector('.' + prefix + '-notes')
                if (!catEl || !nameEl) return
                var category = catEl.value
                var name = nameEl.value.trim()
                if (!category || !name) {
                    if (typeof showToast === 'function') showToast('Category and Name are required.', 'error')
                    return
                }
                var diagnosisDate = dateEl ? dateEl.value || null : null
                var procedureDate = procEl ? procEl.value || null : null
                var notes = notesEl ? notesEl.value.trim() || null : null
                saveBtn.disabled = true
                saveBtn.textContent = 'Saving...'
                var payload = {
                    patient_id: currentPatientId,
                    category: category,
                    name: name,
                    diagnosis_date: diagnosisDate,
                    procedure_date: procedureDate,
                    notes: notes,
                }
                api('{{ url('/api/medical-backgrounds') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                }).then(function (result) {
                    // Refresh the whole medical background list
                    loadPatientPanelData(currentPatientId)
                    if (typeof showToast === 'function') showToast('Entry added.', 'success')
                }).catch(function (err) {
                    saveBtn.disabled = false
                    saveBtn.textContent = 'Save'
                    if (typeof showToast === 'function') showToast('Failed to save entry.', 'error')
                })
            })

            // Cancel new entry (delegated)
            bgContainer.addEventListener('click', function (e) {
                var cancelBtn = e.target.closest('.medbg-new-cancel')
                if (!cancelBtn) return
                var uid = cancelBtn.getAttribute('data-new-uid')
                if (!uid) return
                var row = bgContainer.querySelector('tr[data-new-row="' + uid + '"]')
                if (row) row.remove()
            })
        }

        function openViewModal() {
            if (viewOverlay) {
                viewOverlay.classList.remove('hidden')
                viewOverlay.classList.add('flex')
            }
            var patient = currentPatientId ? findPatientById(currentPatientId) : null
            var depBtn = document.getElementById('doctorPrViewTabDependentsBtn')
            if (depBtn) depBtn.textContent = (patient && patient.is_dependent) ? 'Parent/Guardian' : 'Dependents'
            setViewTab('profile')
        }

        function closeViewModal() {
            currentPatientId = null
            medBgEditingId = null
            cachedMedBgRows = null
            cachedVisitRows = null
            cachedVitalRows = null
            cachedDependentRows = null
            cachedParentData = null
            window._doctorShowingDependentProfile = false
            window._doctorDependentProfileId = null
            var depBtn = document.getElementById('doctorPrViewTabDependentsBtn')
            if (depBtn) depBtn.textContent = 'Dependents'
            viewEditModeToggle(false)
            if (viewOverlay) {
                viewOverlay.classList.add('hidden')
                viewOverlay.classList.remove('flex')
            }
        }

        function findPatientById(patientId) {
            var value = String(patientId || '')
            if (cachedPatientData && String(cachedPatientData.user_id) === value) return cachedPatientData
            return null
        }

        function mergePatientRecord(updatedPatient) {
            if (!updatedPatient || updatedPatient.user_id == null) return
            var updatedId = String(updatedPatient.user_id)
            if (cachedPatientData && String(cachedPatientData.user_id) === updatedId) {
                cachedPatientData = Object.assign({}, cachedPatientData, updatedPatient)
            } else {
                cachedPatientData = updatedPatient
            }
            if (currentPatientId && currentPatientId === updatedId) {
                populatePatientDetails(cachedPatientData)
            }
        }

        function resetPanelMetaFields() {
            if (viewVerificationStatus) viewVerificationStatus.textContent = '-'
            if (viewPatientType) viewPatientType.textContent = '-'
            if (viewVerificationId) viewVerificationId.textContent = '-'
        }

        function loadPatientPanelData(patientId) {
            currentPatientId = String(patientId || '')
            cachedMedBgRows = null
            cachedVisitRows = null
            cachedVitalRows = null
            cachedDependentRows = null
            cachedParentData = null
            activeDependentRecord = null
            activeDependentTab = 'background'
            activeDependentMedBgRows = null
            activeDependentVisitRows = null
            activeDependentVitalRows = null
            activeDependentVerification = null
            resetPanelMetaFields()

            var medBgReq = api('{{ url('/api/medical-backgrounds') }}?per_page=15&patient_id=' + encodeURIComponent(currentPatientId))
                .then(function (data) {
                    return { ok: true, data: data }
                }).catch(function () {
                    return { ok: false, data: null }
                })

            var visitsReq = api('{{ url('/api/visits') }}?per_page=15&patient_id=' + encodeURIComponent(currentPatientId))
                .then(function (data) {
                    return { ok: true, data: data }
                }).catch(function () {
                    return { ok: false, data: null }
                })

            var vitalsReq = api('{{ url('/api/vitals') }}?per_page=15&patient_id=' + encodeURIComponent(currentPatientId))
                .then(function (data) {
                    return { ok: true, data: data }
                }).catch(function () {
                    return { ok: false, data: null }
                })

            var verificationReq = api('{{ url('/api/patient-verifications') }}?per_page=1&patient_id=' + encodeURIComponent(currentPatientId))
                .then(function (data) {
                    return { ok: true, data: data }
                }).catch(function () {
                    return { ok: false, data: null }
                })

            var dependentsReq = api('{{ url('/api/users') }}/' + encodeURIComponent(currentPatientId) + '/dependents')
                .then(function (data) {
                    return { ok: true, data: data }
                }).catch(function () {
                    return { ok: false, data: null }
                })

            Promise.all([medBgReq, visitsReq, vitalsReq, verificationReq, dependentsReq])
                .then(function (results) {
                    if (String(patientId || '') !== currentPatientId) return

                    var medBgRes = results[0]
                    cachedMedBgRows = (!medBgRes || !medBgRes.ok || !medBgRes.data)
                        ? []
                        : (Array.isArray(medBgRes.data.data) ? medBgRes.data.data : (Array.isArray(medBgRes.data) ? medBgRes.data : []))

                    var visitsRes = results[1]
                    cachedVisitRows = (!visitsRes || !visitsRes.ok || !visitsRes.data)
                        ? []
                        : (Array.isArray(visitsRes.data.data) ? visitsRes.data.data : (Array.isArray(visitsRes.data) ? visitsRes.data : []))

                    var vitalsRes = results[2]
                    cachedVitalRows = (!vitalsRes || !vitalsRes.ok || !vitalsRes.data)
                        ? []
                        : (Array.isArray(vitalsRes.data.data) ? vitalsRes.data.data : (Array.isArray(vitalsRes.data) ? vitalsRes.data : []))

                    var verRes = results[3]
                    if (!verRes || !verRes.ok || !verRes.data) {
                        if (viewVerificationStatus) viewVerificationStatus.textContent = '-'
                        if (viewPatientType) viewPatientType.textContent = '-'
                        if (viewVerificationId) viewVerificationId.textContent = '-'
                    } else {
                        var verRows = Array.isArray(verRes.data.data) ? verRes.data.data : (Array.isArray(verRes.data) ? verRes.data : [])
                        var latest = verRows && verRows.length ? verRows[0] : null
                        var verStatus = latest && latest.status ? String(latest.status) : 'Not submitted'
                        var verType = latest && latest.type ? String(latest.type) : '-'
                        if (viewVerificationStatus) viewVerificationStatus.textContent = verStatus
                        if (viewPatientType) viewPatientType.textContent = verType
                        var isVerified = verStatus.toLowerCase() === 'verified' || verStatus.toLowerCase() === 'approved'
                        if (viewVerificationId) {
                            if (isVerified && latest && latest.document_url) {
                                var docUrl = String(latest.document_url)
                                viewVerificationId.innerHTML = '<a href="' + docUrl.replace(/"/g, '&quot;') + '" target="_blank" class="text-green-700 underline hover:text-green-800">View ID</a>'
                            } else {
                                viewVerificationId.textContent = isVerified ? '-' : '—'
                            }
                        }
                    }

                    var dependentsRes = results[4]
                    cachedDependentRows = (!dependentsRes || !dependentsRes.ok || !dependentsRes.data)
                        ? []
                        : (Array.isArray(dependentsRes.data) ? dependentsRes.data : (Array.isArray(dependentsRes.data.data) ? dependentsRes.data.data : []))

                    if (currentViewTab && currentViewTab !== 'profile' && currentViewTab !== 'verification') {
                        setViewTab(currentViewTab)
                    }
                })
                .catch(function () {
                    if (String(patientId || '') !== currentPatientId) return
                    cachedMedBgRows = []
                    cachedVisitRows = []
                    cachedVitalRows = []
                    cachedDependentRows = []
                })
        }

        function populatePatientDetails(patient) {
            var address = patient && patient.address ? String(patient.address) : ''
            var age = ageFromBirthdate(patient && patient.birthdate ? String(patient.birthdate) : null)
            var contact = patient && patient.contact_number ? String(patient.contact_number) : ''
            var profileImg = patient && patient.prof_path_url ? String(patient.prof_path_url) : ''
            var value = function (input) { return (input != null && input !== '') ? String(input) : '-' }

            if (viewProfilePic) {
                viewProfilePic.innerHTML = profileImg
                    ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                    : defaultProfilePicHtml
            }

            if (prDetailFirstname) prDetailFirstname.textContent = value(patient && patient.firstname)
            if (prDetailMiddlename) prDetailMiddlename.textContent = value(patient && patient.middlename)
            if (prDetailLastname) prDetailLastname.textContent = value(patient && patient.lastname)
            if (prDetailBirthdate) {
                var birthdate = patient && patient.birthdate ? String(patient.birthdate) : ''
                prDetailBirthdate.textContent = birthdate ? birthdate.substring(0, 10) + (age != null ? ' (Age: ' + age + ')' : '') : '-'
            }
            if (prDetailAddress) prDetailAddress.textContent = value(address)
            if (prDetailSex) prDetailSex.textContent = value(patient && patient.sex)
            if (prDetailCivilStatus) prDetailCivilStatus.textContent = value(patient && patient.civil_status)
            if (prDetailNationality) prDetailNationality.textContent = value(patient && patient.nationality)
            if (prDetailContact) prDetailContact.textContent = value(contact)
            if (prDetailPhic) prDetailPhic.textContent = value(patient && patient.philhealth_number)
            if (prDetailOccupation) prDetailOccupation.textContent = value(patient && patient.occupation)
            if (prDetailEmergContact) prDetailEmergContact.textContent = value(patient && patient.emergency_contact)
            if (prDetailEmergNumber) prDetailEmergNumber.textContent = value(patient && patient.emergency_contact_number)
        }

        // --- Edit mode ---
        function populateViewEditForm(patient) {
            if (!patient) return
            var ev = function (input) { return (input != null && input !== '') ? String(input) : '' }
            var editLastname = document.getElementById('doctorPrViewEditLastname')
            var editFirstname = document.getElementById('doctorPrViewEditFirstname')
            var editMiddlename = document.getElementById('doctorPrViewEditMiddlename')
            var editBirthdate = document.getElementById('doctorPrViewEditBirthdate')
            var editCivilStatus = document.getElementById('doctorPrViewEditCivilStatus')
            var editNationalitySelect = document.getElementById('doctorPrViewEditNationalitySelect')
            var editNationality = document.getElementById('doctorPrViewEditNationality')
            var editOccupation = document.getElementById('doctorPrViewEditOccupation')
            var editAddress = document.getElementById('doctorPrViewEditAddress')
            var editPhilhealth = document.getElementById('doctorPrViewEditPhilhealth')
            var editEmergencyContact = document.getElementById('doctorPrViewEditEmergencyContact')
            var editEmergencyContactNumber = document.getElementById('doctorPrViewEditEmergencyContactNumber')
            var editContact = document.getElementById('doctorPrViewEditContact')
            var editProfilePreview = document.getElementById('doctorPrViewEditProfilePreview')
            var editProfileUpload = document.getElementById('doctorPrViewEditProfileUpload')

            if (editLastname) editLastname.value = ev(patient && patient.lastname)
            if (editFirstname) editFirstname.value = ev(patient && patient.firstname)
            if (editMiddlename) editMiddlename.value = ev(patient && patient.middlename)
            if (editBirthdate) editBirthdate.value = patient && patient.birthdate ? String(patient.birthdate).substring(0, 10) : ''
            if (editCivilStatus) editCivilStatus.value = patient && patient.civil_status ? String(patient.civil_status) : ''
            var sexRadios = document.querySelectorAll('input[name="doctorPrViewEditSex"]')
            var patientSex = patient && patient.sex ? String(patient.sex) : ''
            sexRadios.forEach(function (r) { r.checked = r.value === patientSex })

            var natl = patient && patient.nationality ? String(patient.nationality) : ''
            if (editNationalitySelect && editNationality) {
                var isOther = natl && natl !== 'Filipino'
                editNationalitySelect.value = isOther ? '__others__' : (natl || '')
                if (isOther) {
                    editNationalitySelect.className = 'w-[30%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    editNationality.className = 'w-[70%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    editNationality.classList.remove('hidden')
                    editNationality.value = natl
                } else {
                    editNationalitySelect.className = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    editNationality.classList.add('hidden')
                    editNationality.value = ''
                }
            }
            if (editOccupation) editOccupation.value = ev(patient && patient.occupation)
            if (editAddress) editAddress.value = patient && patient.address ? String(patient.address) : ''
            if (editPhilhealth) editPhilhealth.value = patient && patient.philhealth_number ? String(patient.philhealth_number) : ''
            if (editEmergencyContact) editEmergencyContact.value = ev(patient && patient.emergency_contact)
            if (editEmergencyContactNumber) editEmergencyContactNumber.value = ev(patient && patient.emergency_contact_number)
            if (editContact) editContact.value = patient && patient.contact_number ? String(patient.contact_number) : ''

            var profileImg = patient && patient.prof_path_url ? String(patient.prof_path_url) : ''
            if (editProfilePreview) {
                editProfilePreview.innerHTML = profileImg
                    ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
            }

            if (editProfileUpload) {
                editProfileUpload.value = ''
                editProfileUpload.onchange = function () {
                    var file = this.files && this.files[0]
                    if (!file) return
                    var reader = new FileReader()
                    reader.onload = function (e) {
                        if (editProfilePreview) {
                            editProfilePreview.innerHTML = '<img src="' + e.target.result + '" alt="" class="w-full h-full object-cover">'
                        }
                    }
                    reader.readAsDataURL(file)
                }
            }
        }

        function viewEditModeToggle(showEdit) {
            var display = document.getElementById('doctorPrViewProfileDisplay')
            var edit = document.getElementById('doctorPrViewProfileEdit')
            var editBtn = document.getElementById('doctorPrViewEditBtn')
            if (display) display.classList.toggle('hidden', showEdit)
            if (edit) edit.classList.toggle('hidden', !showEdit)
            if (editBtn) {
                if (showEdit) {
                    editBtn.innerHTML = 'Cancel'
                    editBtn.classList.add('text-slate-500', 'hover:text-slate-700')
                    editBtn.classList.remove('text-green-700', 'hover:text-green-800')
                } else {
                    editBtn.innerHTML = 'Edit Info'
                    editBtn.classList.remove('text-slate-500', 'hover:text-slate-700')
                    editBtn.classList.add('text-green-700', 'hover:text-green-800')
                }
            }
        }

        // --- Parent/dependents data ---
        function fetchConsultParentData(parentId) {
            api('{{ url('/api/users') }}/' + encodeURIComponent(parentId))
                .then(function (data) {
                    if (data && !data.error) cachedParentData = data
                    else cachedParentData = null
                    renderViewTabContent('dependents')
                })
                .catch(function () { cachedParentData = null; renderViewTabContent('dependents') })
        }

        function renderParentOrDependentCards(container, rows, type) {
            var html = '<div class="space-y-3">'
            rows.forEach(function (person) {
                var pid = person && person.user_id != null ? String(person.user_id) : ''
                var age = ageFromBirthdate(person && person.birthdate ? String(person.birthdate) : null)
                var profileImg = person && person.prof_path_url ? String(person.prof_path_url) : ''
                html += '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 cursor-pointer hover:border-slate-300 transition-colors doctor-' + type + '-card" data-' + type + '-id="' + escapeHtml(pid) + '">' +
                    '<div class="flex items-center gap-4">' +
                        '<div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">' +
                            (profileImg
                                ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                                : '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>') +
                        '</div>' +
                        '<div class="flex-1 min-w-0 space-y-1">' +
                            '<div class="text-[0.82rem] font-semibold text-slate-900 truncate">' + escapeHtml(fullName(person, type === 'parent' ? 'Parent' : 'Dependent')) + '</div>' +
                            '<div class="text-[0.76rem] text-slate-500">Age: <span class="text-slate-700">' + escapeHtml(age == null ? '-' : String(age)) + '</span> &middot; Sex: <span class="text-slate-700">' + escapeHtml(sexLabel(person && person.sex)) + '</span></div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            })
            html += '</div>'
            container.innerHTML = html

            container._depClickHandler && container.removeEventListener('click', container._depClickHandler)
            container._depClickHandler = function (e) {
                var card = e.target.closest('.doctor-parent-card, .doctor-dependent-card')
                if (card) {
                    var id = card.getAttribute('data-parent-id') || card.getAttribute('data-dependent-id')
                    if (id) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading profile...</div></div>'
                        showDependentProfileInline(id)
                    }
                }
            }
            container.addEventListener('click', container._depClickHandler)
        }

        function showDependentProfileInline(personId) {
            var container = viewTabContents['dependents']
            if (!container) return
            window._doctorShowingDependentProfile = true
            window._doctorDependentProfileId = personId

            api('{{ url('/api/users') }}/' + encodeURIComponent(personId))
                .then(function (user) {
                    if (!user || user.error) {
                        container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load profile.</div>'
                        window._doctorShowingDependentProfile = false
                        return
                    }
                    renderDependentProfileInline(container, user)
                })
                .catch(function () {
                    container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load profile.</div>'
                    window._doctorShowingDependentProfile = false
                })
        }

        function renderDependentProfileInline(container, userData) {
            var user = userData || null
            if (!user && window._doctorDependentProfileId) {
                var allRows = cachedDependentRows || []
                user = allRows.find(function (r) { return String(r.user_id) === String(window._doctorDependentProfileId) }) || null
            }
            if (!user) {
                container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Profile data not available.</div>'
                window._doctorShowingDependentProfile = false
                return
            }

            var profileImg = user.prof_path_url || ''
            container.innerHTML =
                '<div class="space-y-4">' +
                    '<button type="button" class="inline-flex items-center gap-1.5 text-[0.78rem] font-semibold text-slate-500 hover:text-slate-700 doctor-profile-back-btn">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>' +
                        'Back to list' +
                    '</button>' +
                    '<div class="grid grid-cols-1 md:grid-cols-5 gap-5">' +
                        '<div class="md:col-span-3 space-y-3">' +
                            '<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Last name</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.lastname || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">First name</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.firstname || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Middle name</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.middlename || '-') + '</div></div>' +
                            '</div>' +
                            '<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(sexLabel(user.sex)) + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(formatBirthdate(user.birthdate)) + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.civil_status || '-') + '</div></div>' +
                            '</div>' +
                            '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.nationality || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.occupation || '-') + '</div></div>' +
                            '</div>' +
                            '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Address</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg min-h-[2.5rem]">' + escapeHtml(user.address || '-') + '</div></div>' +
                            '<hr class="border-slate-100">' +
                            '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.philhealth_number || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.emergency_contact || '-') + '</div></div>' +
                            '</div>' +
                            '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.emergency_contact_number || '-') + '</div></div>' +
                        '</div>' +
                        '<div class="md:col-span-2">' +
                            '<div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">' +
                                '<div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>' +
                                '<div class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">' +
                                    (profileImg
                                        ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                                        : '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>') +
                                '</div>' +
                                '<div class="mt-4 text-left">' +
                                    '<label class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>' +
                                    '<div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.contact_number || '-') + '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'

            var backBtn = container.querySelector('.doctor-profile-back-btn')
            if (backBtn) {
                backBtn.addEventListener('click', function () {
                    window._doctorShowingDependentProfile = false
                    window._doctorDependentProfileId = null
                    renderViewTabContent('dependents')
                })
            }
        }

        // --- Phone formatting for edit fields ---
        function setupPhoneFormat(input) {
            if (!input) return
            input.addEventListener('input', function () {
                var cursor = this.selectionStart
                var oldLen = this.value.length
                var raw = this.value.replace(/[^\d]/g, '')
                if (raw.startsWith('63')) raw = raw.slice(2)
                if (raw.startsWith('0')) raw = raw.slice(1)
                if (raw.length > 10) raw = raw.slice(0, 10)
                var formatted = raw ? '+63 ' : ''
                if (raw.length > 0) formatted += raw.slice(0, 3)
                if (raw.length > 3) formatted += ' ' + raw.slice(3, 6)
                if (raw.length > 6) formatted += ' ' + raw.slice(6)
                this.value = formatted
                var newLen = this.value.length
                this.setSelectionRange(cursor + (newLen - oldLen), cursor + (newLen - oldLen))
            })
        }

        // --- Event handlers ---
        if (viewClose) viewClose.addEventListener('click', closeViewModal)
        if (viewOverlay) {
            viewOverlay.addEventListener('click', function (e) {
                if (e.target === viewOverlay) closeViewModal()
            })
        }

        // View tab switching
        viewTabButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var tabKey = this.getAttribute('data-view-tab') || 'profile'
                setViewTab(tabKey)
            })
        })

        // Edit button
        if (viewEditBtn) {
            viewEditBtn.addEventListener('click', function () {
                if (!currentPatientId) return
                var isEditing = document.getElementById('doctorPrViewProfileEdit') && !document.getElementById('doctorPrViewProfileEdit').classList.contains('hidden')
                if (isEditing) {
                    viewEditModeToggle(false)
                    return
                }
                var patient = findPatientById(currentPatientId)
                if (!patient) return
                populateViewEditForm(patient)
                viewEditModeToggle(true)
            })
        }

        // Edit form submit
        var doctorPrViewEditForm = document.getElementById('doctorPrViewEditForm')
        var doctorPrViewEditSave = document.getElementById('doctorPrViewEditSave')
        var doctorPrViewEditSpinner = document.getElementById('doctorPrViewEditSpinner')
        var doctorPrViewEditSaveLabel = document.getElementById('doctorPrViewEditSaveLabel')
        var doctorPrViewEditError = document.getElementById('doctorPrViewEditError')
        var doctorPrViewEditCancel = document.getElementById('doctorPrViewEditCancel')

        if (doctorPrViewEditCancel) {
            doctorPrViewEditCancel.addEventListener('click', function () {
                viewEditModeToggle(false)
                if (doctorPrViewEditError) {
                    doctorPrViewEditError.classList.add('hidden')
                    doctorPrViewEditError.textContent = ''
                }
            })
        }

        if (doctorPrViewEditForm) {
            doctorPrViewEditForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (!currentPatientId) return
                if (doctorPrViewEditError) {
                    doctorPrViewEditError.classList.add('hidden')
                    doctorPrViewEditError.textContent = ''
                }
                if (doctorPrViewEditSave) doctorPrViewEditSave.disabled = true
                if (doctorPrViewEditSpinner) doctorPrViewEditSpinner.classList.remove('hidden')
                if (doctorPrViewEditSaveLabel) doctorPrViewEditSaveLabel.textContent = 'Saving...'

                var firstname = document.getElementById('doctorPrViewEditFirstname')
                var lastname = document.getElementById('doctorPrViewEditLastname')
                var middlename = document.getElementById('doctorPrViewEditMiddlename')
                var birthdate = document.getElementById('doctorPrViewEditBirthdate')
                var civilStatus = document.getElementById('doctorPrViewEditCivilStatus')
                var editNationalitySelect = document.getElementById('doctorPrViewEditNationalitySelect')
                var editNationality = document.getElementById('doctorPrViewEditNationality')
                var occupation = document.getElementById('doctorPrViewEditOccupation')
                var address = document.getElementById('doctorPrViewEditAddress')
                var philhealth = document.getElementById('doctorPrViewEditPhilhealth')
                var emergencyContact = document.getElementById('doctorPrViewEditEmergencyContact')
                var emergencyContactNumber = document.getElementById('doctorPrViewEditEmergencyContactNumber')
                var contact = document.getElementById('doctorPrViewEditContact')

                var sexVal = ''
                document.querySelectorAll('input[name="doctorPrViewEditSex"]').forEach(function (r) {
                    if (r.checked) sexVal = r.value
                })

                var natlVal = ''
                if (editNationalitySelect) {
                    natlVal = editNationalitySelect.value === '__others__'
                        ? (editNationality ? editNationality.value : '')
                        : editNationalitySelect.value
                }

                var payload = {
                    firstname: firstname ? firstname.value.trim() : '',
                    lastname: lastname ? lastname.value.trim() : '',
                    middlename: middlename ? middlename.value.trim() : '',
                    birthdate: birthdate ? birthdate.value : null,
                    sex: sexVal,
                    civil_status: civilStatus ? civilStatus.value : '',
                    nationality: natlVal,
                    occupation: occupation ? occupation.value.trim() : '',
                    address: address ? address.value.trim() : '',
                    philhealth_number: philhealth ? philhealth.value.trim() : '',
                    emergency_contact: emergencyContact ? emergencyContact.value.trim() : '',
                    emergency_contact_number: emergencyContactNumber ? emergencyContactNumber.value.trim() : '',
                    contact_number: contact ? contact.value.trim() : '',
                }

                var uploadInput = document.getElementById('doctorPrViewEditProfileUpload')
                var fd = new FormData()
                fd.append('_method', 'PUT')
                for (var key in payload) {
                    if (payload.hasOwnProperty(key)) {
                        fd.append(key, payload[key] === null ? '' : payload[key])
                    }
                }
                if (uploadInput && uploadInput.files && uploadInput.files[0]) {
                    fd.append('profile_photo', uploadInput.files[0])
                }

                api('{{ url('/api/patients') }}/' + encodeURIComponent(currentPatientId), {
                    method: 'POST',
                    body: fd,
                })
                    .then(function (result) {
                        if (doctorPrViewEditSave) doctorPrViewEditSave.disabled = false
                        if (doctorPrViewEditSpinner) doctorPrViewEditSpinner.classList.add('hidden')
                        if (doctorPrViewEditSaveLabel) doctorPrViewEditSaveLabel.textContent = 'Save changes'

                        if (!result) {
                            var msg = 'Failed to save patient info.'
                            if (doctorPrViewEditError) {
                                doctorPrViewEditError.textContent = msg
                                doctorPrViewEditError.classList.remove('hidden')
                            }
                            if (typeof showToast === 'function') showToast(msg, 'error')
                            return
                        }

                        var merged = result.data || result
                        mergePatientRecord(merged)
                        viewEditModeToggle(false)
                        if (typeof showToast === 'function') showToast('Patient updated successfully.', 'success')
                    })
                    .catch(function (err) {
                        if (doctorPrViewEditSave) doctorPrViewEditSave.disabled = false
                        if (doctorPrViewEditSpinner) doctorPrViewEditSpinner.classList.add('hidden')
                        if (doctorPrViewEditSaveLabel) doctorPrViewEditSaveLabel.textContent = 'Save changes'
                        if (doctorPrViewEditError) {
                            doctorPrViewEditError.textContent = 'An unexpected error occurred.'
                            doctorPrViewEditError.classList.remove('hidden')
                        }
                        if (typeof showToast === 'function') showToast('Network error.', 'error')
                    })
            })
        }

        // Setup phone formatting on edit fields
        setupPhoneFormat(document.getElementById('doctorPrViewEditContact'))
        setupPhoneFormat(document.getElementById('doctorPrViewEditEmergencyContactNumber'))

        // View modal trigger
        if (consultViewProfileBtn) {
            consultViewProfileBtn.addEventListener('click', function () {
                if (!state.patientId) {
                    if (typeof showToast === 'function') showToast('No patient selected.', 'error')
                    return
                }
                viewEditModeToggle(false)
                currentPatientId = String(state.patientId)

                // Open modal immediately, load data in background
                openViewModal()
                loadPatientPanelData(currentPatientId)

                // Fetch patient basic info in parallel
                api('{{ url('/api/users') }}/' + currentPatientId).then(function (patientData) {
                    if (patientData) {
                        cachedPatientData = patientData
                        populatePatientDetails(patientData)
                    }
                }).catch(function () {
                    // Silently fail — display fields just show "-"
                })
            })
        }
    })
</script>
