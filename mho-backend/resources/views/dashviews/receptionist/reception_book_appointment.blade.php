<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 mb-1">Appointments Management</h1>
        <p class="text-sm text-slate-500">Book and manage patient appointments efficiently.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)] overflow-hidden">
    <div class="grid grid-cols-2 border-b border-slate-200">
      <button id="receptionAppointmentTabBook" type="button" class="px-4 py-3 text-xs font-semibold text-white bg-green-500 border-b-2 border-green-600">
    Book appointment
</button>
<button id="receptionAppointmentTabManage" type="button" class="px-4 py-3 text-xs font-semibold text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200">
    Manage appointment
</button>
    </div>

    <div id="receptionAppointmentPanelBook" class="p-5">
        <div id="receptionBookAppointmentError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

        <form id="receptionBookAppointmentForm" class="grid gap-3 grid-cols-1 md:grid-cols-3 items-start mb-4">
        <div class="min-w-0">
            <label for="reception_appointment_patient_id" class="block text-[0.7rem] text-slate-600 mb-1">Patient</label>
            <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div>
                <div class="relative">
                <input id="reception_patient_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select patient">
                <input id="reception_appointment_patient_id" type="hidden" required>
                <button id="reception_patient_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100">
                    Browse
                </button>
            </div>
            <div id="receptionPatientPreview" class="hidden mt-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.78rem] text-slate-700 break-words"></div>
        </div>
        <div class="min-w-0">
            <label for="reception_appointment_service_id" class="block text-[0.7rem] text-slate-600 mb-1">Service</label>
            <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div> 
            <div class="relative">
                <input id="reception_service_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select service">
                <input id="reception_appointment_service_id" type="hidden">
                <button id="reception_service_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100">
                    Browse
                </button>
            </div>
            <div id="receptionSelectedServices" class="mt-2 rounded-lg border border-slate-200 bg-white px-3 py-2 min-h-[3rem] max-h-24 overflow-y-auto overscroll-contain">
                <span id="receptionSelectedServicesEmpty" class="text-[0.78rem] text-slate-400">No selected services.</span>
            </div>
        </div>
        <div class="min-w-0">
            <label for="reception_appointment_doctor_id" class="block text-[0.7rem] text-slate-600 mb-1">Doctor</label>
            <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div>
            <div class="relative">
                <input id="reception_doctor_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none disabled:cursor-not-allowed disabled:bg-slate-100" placeholder="Select doctor" disabled>
                <input id="reception_appointment_doctor_id" type="hidden" required>
                <button id="reception_doctor_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-60" disabled>
                    Browse
                </button>
            </div>
            <div id="receptionDoctorPreview" class="hidden mt-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.78rem] text-slate-700 break-words"></div>
        </div>
        <div class="min-w-0">
            <div class="block text-[0.7rem] text-slate-600 mb-1">Last Scheduled visit</div>
            <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div>
            <div id="receptionAppointmentPatientSummaryCard" class="rounded-xl border border-slate-200 bg-white px-3 py-3 min-h-[96px]">
                <div id="receptionAppointmentPatientSummaryEmpty" class="text-[0.75rem] text-slate-500">No patient selected.</div>
                <div id="receptionAppointmentPatientSummaryDetails" class="hidden space-y-1.5 text-[0.75rem] text-slate-700">
                    <div><span class="font-semibold text-slate-800">Last visit:</span> <span id="receptionAppointmentPatientSummaryVisit">-</span></div>
                    <div><span class="font-semibold text-slate-800">Service inquired:</span> <span id="receptionAppointmentPatientSummaryService">-</span></div>
                    <div><span class="font-semibold text-slate-800">Doctor:</span> <span id="receptionAppointmentPatientSummaryDoctor">-</span></div>
                </div>
            </div>
        </div>
        <div id="receptionAppointmentScheduleWrap" class="self-start relative min-w-0 mb-8">
            <label class="block text-[0.7rem] text-slate-600 mb-1">Schedule</label>
             <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div>
            <input id="reception_appointment_date" type="hidden" required>
            <input id="reception_appointment_time" type="hidden" required>
            <button id="receptionScheduleTrigger" type="button" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 text-left focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none disabled:opacity-60" disabled>
                Select a doctor first
            </button>
        </div>
        
        <div class="self-start min-w-0">
            <label class="block text-[0.7rem] text-slate-600 mb-1">Set Date & Time</label>
            <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div>
            <div id="receptionScheduleCard" class="hidden p-3 rounded-xl border border-green-200 bg-green-50">
                <div class="text-[0.72rem] text-green-700 font-semibold">Selected Schedule</div>
                <div id="receptionScheduleCardText" class="text-[0.78rem] text-green-800 mt-1"></div>
                <button id="receptionScheduleClearBtn" type="button" class="mt-1 text-[0.7rem] text-green-600 hover:text-green-800 underline">Clear</button>
            </div>
        </div>
        <input id="reception_appointment_type" type="hidden" value="scheduled">
        <div class="md:col-span-3">
            <label for="reception_appointment_reason" class="block text-[0.7rem] text-slate-600 mb-1">Reason (optional)</label>
            <input id="reception_appointment_reason" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Reason for visit">
        </div>
        <div class="md:col-span-3 flex justify-end">
            <button id="receptionBookAppointmentSubmit" type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                <span id="receptionBookAppointmentSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                <span id="receptionBookAppointmentSubmitLabel">Book appointment</span>
            </button>
        </div>
    </form>

    <p class="text-[0.7rem] text-slate-400">
        Appointments booked by receptionists are confirmed by default.
    </p>
    </div>

    <div id="receptionAppointmentPanelManage" class="hidden p-5">
        <div id="receptionManageDateHeader" class="hidden text-center text-sm font-semibold text-slate-700 mb-3"></div>
        <div class="flex items-center justify-between mb-3 gap-3">
            <div class="flex items-center gap-2">
                <button id="receptionManageCalendarToggle" type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.7rem] font-semibold text-slate-700 transition-colors">
                    <x-lucide-calendar class="w-[14px] h-[14px]" />
                    <span id="receptionManageCalendarToggleText">Calendar view</span>
                </button>
                <button id="receptionManageClearFilterBtn" type="button" style="display:none" class="shrink-0 inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-[0.7rem] font-semibold text-red-700 hover:bg-red-100 transition-colors">
                    <x-lucide-x class="w-[14px] h-[14px]" />
                    Clear Filter
                </button>
            </div>
            <div class="flex items-center gap-2">
            <button id="receptionManageTodayOnlyBtn" type="button" class="shrink-0 inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.75rem] font-semibold text-slate-700">
                Show today only
            </button>
            <button type="button" id="recBookRefreshBtn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                Refresh
            </button>
        </div>
        </div>

    <div id="receptionManageAppointmentError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
    <div id="receptionManageAppointmentSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

    <div class="grid gap-3 grid-cols-1 md:grid-cols-5 items-start mb-4">
        <div class="md:col-span-2 min-w-0">
            <label for="receptionManageApptSearch" class="block text-[0.7rem] text-slate-600 mb-1">Search</label>
            <input id="receptionManageApptSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by patient or doctor">
        </div>
        <div class="min-w-0">
            <label for="receptionManageServiceSearch" class="block text-[0.7rem] text-slate-600 mb-1">Service</label>
            <div class="relative">
                <input id="receptionManageServiceSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="All services" autocomplete="off">
                <input id="receptionManageServiceId" type="hidden">
                <div id="receptionManageServiceResults" class="hidden absolute left-0 right-0 top-full mt-1 w-full rounded-lg border border-slate-200 bg-white shadow-sm max-h-64 overflow-y-auto overscroll-contain z-50"></div>
            </div>
        </div>
        <div class="min-w-0">
            <label for="receptionManageSort" class="block text-[0.7rem] text-slate-600 mb-1">Sort by date</label>
            <select id="receptionManageSort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="latest">Latest first</option>
                <option value="oldest">Oldest first</option>
            </select>
        </div>
        <div class="min-w-0">
            <label for="receptionManageStatus" class="block text-[0.7rem] text-slate-600 mb-1">Status</label>
            <select id="receptionManageStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="no_show">No-show</option>
            </select>
        </div>
    </div>

    <div id="receptionManageTableArea">
        <div class="w-full" style="display:grid;">
        <div class="rounded-2xl border border-slate-200 overflow-hidden">
         <div class="overflow-x-auto overflow-y-auto scrollbar-hidden mb-4 h-[470px]">
                <table class="text-xs" style="min-width:700px;width:100%;table-layout:auto;">
                    <thead class="bg-slate-50 text-slate-600 sticky top-0">
                        <tr>
                            <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Date</th>
                            <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Time</th>
                            <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Patient</th>
                            <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Service</th>
                            <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Doctor</th>
                            <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Status</th>
                            <th class="text-right px-3 py-2 font-semibold whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="receptionManageAppointmentTableBody" class="divide-y divide-slate-100 bg-white"></tbody>
                </table>
            </div>
            <div id="receptionManageAppointmentTableFooter" class="px-3 py-2 text-[0.72rem] text-slate-500 bg-white border-t border-slate-100 flex items-center justify-between">
                <div id="receptionManageAppointmentMeta">Showing latest 10 booked appointments.</div>
            </div>
            <div id="receptionManagePagination" class="px-3 py-2 bg-white border-t border-slate-50 flex items-center justify-center gap-1"></div>
        </div>
        </div>
        <pre id="receptionManageAppointmentResult" class="hidden mt-3 text-[0.68rem] text-slate-600 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 overflow-x-auto"></pre>
    </div>

    <div id="receptionManageCalendarArea" class="hidden">
        <div class="rounded-2xl border border-slate-200 overflow-hidden bg-white">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <button id="receptionManageCalDatePrev" type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">‹</button>
                    <div id="receptionManageCalMonthLabel" class="text-base font-bold text-slate-800"></div>
                    <button id="receptionManageCalDateNext" type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">›</button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-7 gap-1.5 text-[0.75rem] text-slate-400 mb-3 text-center font-medium">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>
                <div id="receptionManageCalDateGrid" class="grid grid-cols-7 grid-rows-[repeat(6,1fr)] gap-1.5 h-[520px]"></div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Patient History Modal -->
<div id="managePatientHistoryOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl h-[90vh] max-h-none rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex overflow-hidden">
        <!-- History list (left) -->
        <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
            <!-- History list content -->
            <div id="manageHistListSection">
                <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Patient History</div>
                        <div id="managePatientHistSubtitle" class="text-[0.72rem] text-slate-500">Loading…</div>
                    </div>
                    <button type="button" id="managePatientHistClose" class="text-slate-400 hover:text-slate-600">
                        <x-lucide-x class="w-[20px] h-[20px]" />
                    </button>
                </div>
                <div class="px-4 py-2 border-b border-slate-100 shrink-0 grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Date</label>
                        <input id="managePatientHistDate" type="date" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Status</label>
                        <select id="managePatientHistStatus" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="no_show">No-show</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[0.6rem] text-slate-500 mb-0.5">Type</label>
                        <select id="managePatientHistType" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="">All</option>
                            <option value="walk_in">Walk In</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>
                </div>
                <div id="managePatientHistBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                    <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>
                </div>
            </div>
            <!-- Change Appointment panel -->
            <div id="manageHistChangePanel" class="hidden flex-1 flex flex-col min-h-0">
                <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center gap-3">
                    <button id="manageHistChangeBack" type="button" class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                        <x-lucide-arrow-left class="w-[14px] h-[14px]" />
                    </button>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Change Appointment</div>
                        <div id="manageHistChangeSubtitle" class="text-[0.72rem] text-slate-500">Select a new doctor, date and time.</div>
                    </div>
                </div>
                <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                    <label class="block text-[0.65rem] font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Doctor</label>
                    <select id="manageHistChangeDoctor" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none"></select>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <label class="text-[0.65rem] font-semibold text-slate-600 uppercase tracking-wider block mb-2">Select Date</label>
                        <div class="flex items-center justify-between mb-2">
                            <button id="manageHistChangeCalPrev" type="button" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">‹</button>
                            <div id="manageHistChangeCalMonth" class="text-xs font-semibold text-slate-800"></div>
                            <button id="manageHistChangeCalNext" type="button" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">›</button>
                        </div>
                        <div class="grid grid-cols-7 gap-0.5 text-[0.6rem] text-slate-400 mb-1 text-center font-medium">
                            <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
                        </div>
                        <div id="manageHistChangeCalGrid" class="grid grid-cols-7 gap-0.5"></div>
                    </div>
                    <div class="px-4 py-3">
                        <label class="text-[0.65rem] font-semibold text-slate-600 uppercase tracking-wider block mb-2">Select Time</label>
                        <div id="manageHistChangeTimeBody">
                            <div class="text-center text-[0.72rem] text-slate-400 py-4">Select a date to view time slots.</div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 border-t border-slate-100 shrink-0 bg-white">
                    <button id="manageHistChangeConfirmBtn" type="button" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-[0.78rem] font-semibold text-white hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed" disabled>Confirm Change</button>
                </div>
            </div>
        </div>
        <!-- Detail panel (right) with status actions -->
        <div id="managePatientHistDetailPanel" class="w-1/2 flex flex-col min-h-0 bg-slate-50/50">
            <div class="px-4 py-3 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div class="text-sm font-semibold text-slate-900">Appointment Details</div>
            </div>
            <div id="managePatientHistDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>
            </div>
            <div class="px-4 py-3 border-t border-slate-200 shrink-0 bg-white flex items-center gap-3">
                <div class="flex-1">
                    <label class="block text-[0.6rem] text-slate-500 mb-0.5">Change Status</label>
                    <select id="managePatientHistStatusSelect" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" disabled>
                        <option value="">Select status</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="no_show">No-show</option>
                    </select>
                </div>
                <button id="managePatientHistUpdateBtn" type="button" class="shrink-0 self-end px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed" disabled>Update Status</button>
            </div>
        </div>
    </div>
</div>

<div id="receptionSelectorOverlay" class="hidden fixed inset-0 z-[80] bg-slate-900/50 items-center justify-center p-4">
    <div class="w-full max-w-5xl h-[88vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] overflow-hidden grid grid-cols-1 md:grid-cols-2">
        <div class="border-b md:border-b-0 md:border-r border-slate-200 flex flex-col min-h-0">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-start justify-between gap-3">
                <div>
                    <div id="receptionSelectorTitle" class="text-sm font-semibold text-slate-900">Select record</div>
                    <div id="receptionSelectorSubtitle" class="text-[0.72rem] text-slate-500">Recent records appear here.</div>
                </div>
                <button type="button" id="receptionSelectorClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                <label for="receptionSelectorSearch" class="block text-[0.65rem] uppercase tracking-widest text-slate-400 mb-1">Search</label>
                <input id="receptionSelectorSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search records">
                <div id="receptionSelectorListLabel" class="mt-2 text-[0.7rem] text-slate-500">Latest records</div>
            </div>
            <div id="receptionSelectorListBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading records…</div>
            </div>
        </div>
        <div class="flex flex-col min-h-0 bg-slate-50/60">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                <div class="text-sm font-semibold text-slate-900">Details</div>
                <div class="text-[0.72rem] text-slate-500">Review the selected record before confirming.</div>
            </div>
            <div id="receptionSelectorDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a record to view details.</div>
            </div>
            <div class="px-4 py-3 border-t border-slate-100 bg-white shrink-0 flex items-center justify-end gap-2">
                <button type="button" id="receptionSelectorCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" id="receptionSelectorConfirm" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-60" disabled>Select</button>
            </div>
        </div>
    </div>
</div>

<div id="receptionBookConfirmOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                <x-lucide-info class="w-[18px] h-[18px]" />
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-slate-900">Confirm</div>
                <div id="receptionBookConfirmMessage" class="text-[0.78rem] text-slate-600 mt-0.5">Are you sure?</div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-end gap-2">
            <button type="button" id="receptionBookConfirmCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="button" id="receptionBookConfirmOk" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Confirm</button>
        </div>
    </div>
</div>
<div id="receptionScheduleOverlay" class="hidden fixed inset-0 z-[80] bg-slate-900/50 items-center justify-center p-4">
    <div class="w-full max-w-5xl h-[88vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] overflow-hidden grid grid-cols-1 md:grid-cols-2">
        <!-- Left: Calendar -->
        <div class="border-b md:border-b-0 md:border-r border-slate-200 flex flex-col min-h-0">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-start justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Select Date</div>
                    <div class="text-[0.72rem] text-slate-500">Choose a date for the appointment.</div>
                </div>
                <button type="button" id="receptionScheduleClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                <div class="flex items-center justify-between">
                    <button id="receptionScheduleDatePrev" type="button" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">‹</button>
                    <div id="receptionScheduleMonthLabel" class="text-[0.78rem] font-semibold text-slate-800"></div>
                    <button id="receptionScheduleDateNext" type="button" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold">›</button>
                </div>
            </div>
            <div class="p-3 flex-1 overflow-y-auto">
                <div class="grid grid-cols-7 gap-1 text-[0.68rem] text-slate-400 mb-2">
                    <div class="text-center">Sun</div><div class="text-center">Mon</div><div class="text-center">Tue</div><div class="text-center">Wed</div><div class="text-center">Thu</div><div class="text-center">Fri</div><div class="text-center">Sat</div>
                </div>
                <div id="receptionScheduleDateGrid" class="grid grid-cols-7 gap-1"></div>
            </div>
        </div>
        <!-- Right: Time slots -->
        <div class="flex flex-col min-h-0 bg-slate-50/60">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                <div class="text-sm font-semibold text-slate-900">Select Time</div>
                <div id="receptionScheduleTimeHint" class="text-[0.72rem] text-slate-500">Select a date to view time slots.</div>
            </div>
            <div id="receptionScheduleTimeBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a date to load time slots.</div>
            </div>
            <div class="px-4 py-3 border-t border-slate-100 bg-white shrink-0 flex items-center justify-end gap-2">
                <button type="button" id="receptionScheduleCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" id="receptionScheduleSetBtn" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-60" disabled>Set Schedule</button>
            </div>
        </div>
    </div>
</div>
<div id="receptionBookReviewOverlay" class="hidden fixed inset-0 z-[75] bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 transition-all duration-200">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-100 overflow-hidden">
        <!-- Header section with icon and title - refined spacing -->
        <div class="px-5 pt-5 pb-3 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 border border-green-200 flex items-center justify-center text-green-600 shadow-sm flex-shrink-0">
                    <x-lucide-calendar-check class="w-5 h-5" />
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-base font-semibold text-slate-800 tracking-tight">Review Appointment Details</div>
                    <p class="text-xs text-slate-500 mt-0.5">Please verify all information before confirming</p>
                </div>
            </div>
        </div>

        <!-- Content area - improved typography and visual hierarchy -->
        <div class="px-5 py-4 bg-white">
            <div id="receptionBookReviewContent" class="text-sm text-slate-700 leading-relaxed space-y-3">
                <!-- Dynamic content will be injected here with better structure -->
                <div class="bg-slate-50/80 rounded-xl border border-slate-100 p-4 space-y-3">
                    <div class="flex items-start gap-2.5">
                        <x-lucide-user class="w-4 h-4 mt-0.5 text-slate-400 flex-shrink-0" />
                        <div class="flex flex-wrap items-baseline gap-1">
                            <span class="font-medium text-slate-800">Patient:</span>
                            <span class="text-slate-600">-</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <x-lucide-calendar-days class="w-4 h-4 mt-0.5 text-slate-400 flex-shrink-0" />
                        <div class="flex flex-wrap items-baseline gap-1">
                            <span class="font-medium text-slate-800">Date:</span>
                            <span class="text-slate-600">-</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <x-lucide-clock class="w-4 h-4 mt-0.5 text-slate-400 flex-shrink-0" />
                        <div class="flex flex-wrap items-baseline gap-1">
                            <span class="font-medium text-slate-800">Time:</span>
                            <span class="text-slate-600">-</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <x-lucide-stethoscope class="w-4 h-4 mt-0.5 text-slate-400 flex-shrink-0" />
                        <div class="flex flex-wrap items-baseline gap-1">
                            <span class="font-medium text-slate-800">Doctor / Department:</span>
                            <span class="text-slate-600">-</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <x-lucide-file-text class="w-4 h-4 mt-0.5 text-slate-400 flex-shrink-0" />
                        <div class="flex flex-wrap items-baseline gap-1">
                            <span class="font-medium text-slate-800">Reason / Notes:</span>
                            <span class="text-slate-600">-</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-start gap-2 text-xs text-amber-700 bg-amber-50/60 rounded-lg px-3 py-2">
                    <x-lucide-alert-circle class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" />
                    <span>Please ensure all details are correct before confirming the appointment.</span>
                </div>
            </div>
        </div>

        <!-- Footer buttons - improved hierarchy -->
        <div class="px-5 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="receptionBookReviewCancel" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1">
                Cancel
            </button>
            <button type="button" id="receptionBookReviewOk" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-sm transition-all duration-150">
                Confirm Appointment
            </button>
        </div>
    </div>
</div>
<div id="receptionBookDocPickerOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-slate-900">Change Appointment</div>
                <div id="recBookPickerSubtitle" class="text-[0.72rem] text-slate-500">Select a new date and time.</div>
            </div>
            <button id="receptionBookDocPickerClose" type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                <x-lucide-x class="w-[16px] h-[16px]" />
            </button>
        </div>
        {{-- Doctor toggle section --}}
        <div class="px-4 py-2 border-b border-slate-50">
            <button type="button" id="recBookToggleDoctor" class="inline-flex items-center gap-1.5 text-[0.72rem] font-medium text-slate-600 hover:text-green-600 transition-colors">
                <x-lucide-user-plus class="w-[14px] h-[14px]" />
                <span>Change Doctor</span>
                <span id="recBookToggleDoctorCaret" class="text-[0.6rem] ml-1">&#x25BC;</span>
            </button>
            <div id="recBookCurrentDoctorLabel" class="text-[0.68rem] text-slate-400 mt-0.5"></div>
        </div>
        {{-- Doctor picker list (hidden by default) --}}
        <div id="recBookDoctorListWrap" class="hidden px-4 py-3 max-h-56 overflow-y-auto scrollbar-hidden space-y-2 border-b border-slate-100">
            <div id="receptionBookDocPickerBody" class="space-y-2">
                <div class="text-[0.78rem] text-slate-400">Loading doctors...</div>
            </div>
        </div>
        {{-- Date/Time slot selector (always shown) --}}
        <div id="recBookSlotPicker" class="border-t border-slate-100">
            <div class="px-4 py-3 space-y-3">
                <div>
                    <label class="text-[0.65rem] font-semibold text-slate-600 uppercase tracking-wider block mb-1.5">Select Date</label>
                    <div id="recBookDateOptions" class="flex gap-2 overflow-x-auto pb-1 scrollbar-thin scrollbar-thumb-slate-200"></div>
                </div>
                <div>
                    <label class="text-[0.65rem] font-semibold text-slate-600 uppercase tracking-wider block mb-1.5">Select Time</label>
                    <div id="recBookTimeOptions" class="flex flex-wrap gap-1.5"></div>
                </div>
                <button type="button" id="recBookConfirmSlotBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-[0.78rem] font-semibold text-white hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Confirm Change
                </button>
            </div>
        </div>
    </div>
</div>

<template id="receptionBookAppointmentIconX">
    <x-lucide-x class="w-[18px] h-[18px]" />
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var iconX = (function () {
            var tpl = document.getElementById('receptionBookAppointmentIconX')
            return tpl ? String(tpl.innerHTML || '').trim() : ''
        })()
        var tabBookBtn = document.getElementById('receptionAppointmentTabBook')
        var tabManageBtn = document.getElementById('receptionAppointmentTabManage')
        var panelBook = document.getElementById('receptionAppointmentPanelBook')
        var panelManage = document.getElementById('receptionAppointmentPanelManage')
function setAppointmentTab(tab) {
    var isBook = tab === 'book'
    if (panelBook) panelBook.classList.toggle('hidden', !isBook)
    if (panelManage) panelManage.classList.toggle('hidden', isBook)

    if (tabBookBtn) {
        // Active tab (Book)
        tabBookBtn.classList.toggle('bg-green-500', isBook)      // green background
        tabBookBtn.classList.toggle('text-white', isBook)       // White text
        tabBookBtn.classList.toggle('border-b-2', isBook)       // Bottom border indicator
        tabBookBtn.classList.toggle('border-green-600', isBook)  // Darker green border
        // Inactive tab
        tabBookBtn.classList.toggle('bg-white', !isBook)        // White background
        tabBookBtn.classList.toggle('text-slate-900', !isBook)  // Black/dark text
        tabBookBtn.classList.toggle('hover:bg-slate-50', !isBook) // Hover effect
        tabBookBtn.classList.toggle('border-b-0', !isBook)      // No border when inactive
        tabBookBtn.classList.toggle('border-l', !isBook)        // Left border separator
        tabBookBtn.classList.toggle('border-slate-200', !isBook) // Border color
    }
    if (tabManageBtn) {
        // Active tab (Manage)
        tabManageBtn.classList.toggle('bg-green-500', !isBook)    // green background
        tabManageBtn.classList.toggle('text-white', !isBook)     // White text
        tabManageBtn.classList.toggle('border-b-2', !isBook)     // Bottom border indicator
        tabManageBtn.classList.toggle('border-green-600', !isBook)// Darker green border
        // Inactive tab
        tabManageBtn.classList.toggle('bg-white', isBook)        // White background
        tabManageBtn.classList.toggle('text-slate-900', isBook)  // Black/dark text
        tabManageBtn.classList.toggle('hover:bg-slate-50', isBook) // Hover effect
        tabManageBtn.classList.toggle('border-b-0', isBook)      // No border when inactive
        tabManageBtn.classList.toggle('border-l', isBook)        // Left border separator
        tabManageBtn.classList.toggle('border-slate-200', isBook) // Border color
    }
}

        if (tabBookBtn) {
            tabBookBtn.addEventListener('click', function () { setAppointmentTab('book') })
        }
        if (tabManageBtn) {
            tabManageBtn.addEventListener('click', function () { setAppointmentTab('manage') })
        }
        setAppointmentTab('book')

        var form = document.getElementById('receptionBookAppointmentForm')
        var errorBox = document.getElementById('receptionBookAppointmentError')
        var submitBtn = document.getElementById('receptionBookAppointmentSubmit')
        var submitSpinner = document.getElementById('receptionBookAppointmentSpinner')
        var submitLabel = document.getElementById('receptionBookAppointmentSubmitLabel')
        var patientSearch = document.getElementById('reception_patient_search')
        var patientSelect = document.getElementById('reception_appointment_patient_id')
        var patientResults = document.getElementById('receptionPatientResults')
        var patientPreview = document.getElementById('receptionPatientPreview')
        var patientSummaryEmpty = document.getElementById('receptionAppointmentPatientSummaryEmpty')
        var patientSummaryDetails = document.getElementById('receptionAppointmentPatientSummaryDetails')
        var patientSummaryVisit = document.getElementById('receptionAppointmentPatientSummaryVisit')
        var patientSummaryService = document.getElementById('receptionAppointmentPatientSummaryService')
        var patientSummaryDoctor = document.getElementById('receptionAppointmentPatientSummaryDoctor')
        var patientPickerBtn = document.getElementById('reception_patient_picker_btn')
        var serviceSearch = document.getElementById('reception_service_search')
        var serviceSelect = document.getElementById('reception_appointment_service_id')
        var serviceResults = document.getElementById('receptionServiceResults')
        var selectedServicesEl = document.getElementById('receptionSelectedServices')
        var servicePickerBtn = document.getElementById('reception_service_picker_btn')
        var doctorSearch = document.getElementById('reception_doctor_search')
        var doctorSelect = document.getElementById('reception_appointment_doctor_id')
        var doctorResults = document.getElementById('receptionDoctorResults')
        var doctorPreview = document.getElementById('receptionDoctorPreview')
        var doctorPickerBtn = document.getElementById('reception_doctor_picker_btn')
        var dateInput = document.getElementById('reception_appointment_date')
        var timeInput = document.getElementById('reception_appointment_time')
        var scheduleWrap = document.getElementById('receptionAppointmentScheduleWrap')
        var scheduleTrigger = document.getElementById('receptionScheduleTrigger')
        var scheduleCard = document.getElementById('receptionScheduleCard')
        var scheduleCardText = document.getElementById('receptionScheduleCardText')
        var scheduleClearBtn = document.getElementById('receptionScheduleClearBtn')
        var scheduleOverlay = document.getElementById('receptionScheduleOverlay')
        var scheduleCloseBtn = document.getElementById('receptionScheduleClose')
        var scheduleDateGrid = document.getElementById('receptionScheduleDateGrid')
        var scheduleMonthLabel = document.getElementById('receptionScheduleMonthLabel')
        var scheduleDatePrev = document.getElementById('receptionScheduleDatePrev')
        var scheduleDateNext = document.getElementById('receptionScheduleDateNext')
        var scheduleTimeBody = document.getElementById('receptionScheduleTimeBody')
        var scheduleTimeHint = document.getElementById('receptionScheduleTimeHint')
        var scheduleCancelBtn = document.getElementById('receptionScheduleCancel')
        var scheduleSetBtn = document.getElementById('receptionScheduleSetBtn')
        var services = []
        var doctors = []
        var servicesLoaded = false
        var servicesLoading = false
        var doctorsLoaded = false
        var doctorsLoading = false
        var doctorSchedules = []
        var doctorAvailableDaySet = {}
        var doctorAppointments = []
        var doctorMonthAppointments = {}
        var selectedSlotStart = null
        var slotMinutes = 30
        var patientSearchTimer = null
        var selectedPatient = null
        var bookServiceSearchPage = 1
        var bookServiceSearchResults = []
        var bookServiceSearchHasMore = false
        var bookServiceSearchQuery = ''
        var bookServiceSearchLoading = false
        var bookPatientSearchPage = 1
        var bookPatientSearchResults = []
        var bookPatientSearchHasMore = false
        var bookPatientSearchQuery = ''
        var bookPatientSearchLoading = false
        var bookDoctorSearchPage = 1
        var bookDoctorSearchResults = []
        var bookDoctorSearchHasMore = false
        var bookDoctorSearchQuery = ''
        var bookDoctorSearchLoading = false
        var selectedServices = []
        var selectedDoctor = null
        var previousDoctorId = 0
        var previousServiceIds = []
        var previousServiceIdSet = {}
        var selectorOverlay = document.getElementById('receptionSelectorOverlay')
        var selectorCloseBtn = document.getElementById('receptionSelectorClose')
        var selectorTitle = document.getElementById('receptionSelectorTitle')
        var selectorSubtitle = document.getElementById('receptionSelectorSubtitle')
        var selectorSearch = document.getElementById('receptionSelectorSearch')
        var selectorListLabel = document.getElementById('receptionSelectorListLabel')
        var selectorListBody = document.getElementById('receptionSelectorListBody')
        var selectorDetailBody = document.getElementById('receptionSelectorDetailBody')
        var selectorCancelBtn = document.getElementById('receptionSelectorCancel')
        var selectorConfirmBtn = document.getElementById('receptionSelectorConfirm')
        var selectorState = {
            type: '',
            items: [],
            activeItem: null,
            stagedServices: [],
            searchTimer: null,
            searchSeq: 0
        }

        function setBookSubmitting(isSubmitting) {
            if (submitBtn) submitBtn.disabled = !!isSubmitting
            if (submitSpinner) submitSpinner.classList.toggle('hidden', !isSubmitting)
            if (submitLabel) submitLabel.textContent = isSubmitting ? 'Booking…' : 'Book appointment'
        }

        function showBookAppointmentError(message) {
            if (!errorBox) return
            errorBox.textContent = message || ''
            if (message) {
                errorBox.classList.remove('hidden')
            } else {
                errorBox.classList.add('hidden')
            }
        }

        function showBookAppointmentSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function normalizeText(value) {
            return String(value || '').trim().toLowerCase()
        }

        function extractServiceCategory(serviceName) {
            var s = String(serviceName || '').trim()
            if (!s) return ''
            var parts = s.split(':')
            return normalizeText(parts[0] || s)
        }

        function specializationMatches(serviceCategory, doctorSpecialization) {
            var a = normalizeText(serviceCategory)
            var b = normalizeText(doctorSpecialization)
            if (!a || !b) return false
            return b.indexOf(a) !== -1 || a.indexOf(b) !== -1
        }

        function escapeHtml(input) {
            var s = String(input == null ? '' : input)
            return s
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function escapeAttr(input) {
            return escapeHtml(input).replace(/`/g, '&#096;')
        }

        function patientLabel(p) {
            var parts = [p && p.firstname, p && p.middlename, p && p.lastname].filter(function (v) { return String(v || '').trim() !== '' })
            var name = parts.join(' ').trim()
            if (name) return name
            if (p && p.email) return String(p.email)
            return 'Patient'
        }

        function patientDisplayName(patient) {
            if (!patient) return ''
            var name = [patient.firstname, patient.middlename, patient.lastname]
                .filter(function (v) { return String(v || '').trim() !== '' })
                .join(' ')
                .trim()
            if (!name) name = patient.email ? String(patient.email) : ''
            return name
        }

        function serviceDisplayName(service) {
            if (!service) return ''
            return String(service.service_name || service.name || '').trim()
        }

        function doctorDisplayName(doctor) {
            if (!doctor) return ''
            return [doctor.firstname, doctor.middlename, doctor.lastname]
                .filter(function (v) { return String(v || '').trim() !== '' })
                .join(' ')
                .trim() || (doctor.email || '')
        }

        function selectedServicesLabel() {
            var list = Array.isArray(selectedServices) ? selectedServices : []
            if (!list.length) return ''
            if (list.length === 1) return serviceDisplayName(list[0]) || '1 service selected'
            return String(list.length) + ' services selected'
        }

        function syncSelectionTriggers() {
            if (patientSearch) patientSearch.value = selectedPatient ? patientDisplayName(selectedPatient) : ''
            if (serviceSearch) serviceSearch.value = selectedServicesLabel()
            if (doctorSearch) doctorSearch.value = selectedDoctor ? doctorDisplayName(selectedDoctor) : ''
            if (doctorPickerBtn) doctorPickerBtn.disabled = !(doctorSearch && !doctorSearch.disabled)
        }

        var selectorUserIcon = '' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
                '<path d="M18 20a6 6 0 0 0-12 0"/>' +
                '<circle cx="12" cy="10" r="4"/>' +
                '<circle cx="12" cy="12" r="10"/>' +
            '</svg>'

        function profilePhotoUrl(record) {
            if (!record) return ''
            return String(record.prof_path_url || record.profile_photo_url || '').trim()
        }

        function renderProfileAvatar(record, labelText) {
            var photo = profilePhotoUrl(record)
            if (photo) {
                return '<img src="' + escapeAttr(photo) + '" alt="' + escapeAttr(labelText || '') + '" class="w-20 h-20 rounded-2xl object-cover border border-slate-200 bg-white">'
            }
            return '' +
                '<div class="w-20 h-20 rounded-2xl border border-slate-200 bg-white text-slate-400 flex items-center justify-center">' +
                    selectorUserIcon +
                '</div>'
        }

        function ageFromBirthdate(birthdate) {
            if (!birthdate) return ''
            var date = new Date(String(birthdate))
            if (isNaN(date.getTime())) return ''
            var today = new Date()
            var age = today.getFullYear() - date.getFullYear()
            var monthDiff = today.getMonth() - date.getMonth()
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < date.getDate())) {
                age -= 1
            }
            return age >= 0 ? String(age) : ''
        }

        function titleCase(value) {
            var raw = String(value || '').trim()
            if (!raw) return ''
            return raw.replace(/[_\-]+/g, ' ').replace(/\b\w/g, function (chr) { return chr.toUpperCase() })
        }

        function patientTypeLabel(patient) {
            return titleCase(patient && (patient.account_type || patient.patient_type || 'patient')) || 'Patient'
        }

        function dayChipLabel(key) {
            var map = {
                mon: 'Mon',
                tue: 'Tue',
                wed: 'Wed',
                thu: 'Thu',
                fri: 'Fri',
                sat: 'Sat',
                sun: 'Sun'
            }
            return map[String(key || '').toLowerCase()] || ''
        }

        function doctorScheduleSummary(doctor) {
            var schedules = doctor && Array.isArray(doctor.doctor_schedules) ? doctor.doctor_schedules : []
            if (!schedules.length) return 'No schedule posted.'
            var order = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']
            var unique = []
            schedules.forEach(function (entry) {
                var key = normalizeDayKey(entry && entry.day_of_week != null ? entry.day_of_week : '')
                if (key && unique.indexOf(key) === -1) unique.push(key)
            })
            unique.sort(function (a, b) { return order.indexOf(a) - order.indexOf(b) })
            if (!unique.length) return 'No schedule posted.'
            var ranges = []
            var start = unique[0]
            var previous = unique[0]
            for (var i = 1; i <= unique.length; i++) {
                var current = unique[i]
                var prevIndex = order.indexOf(previous)
                var currentIndex = order.indexOf(current)
                if (current && currentIndex === prevIndex + 1) {
                    previous = current
                    continue
                }
                ranges.push(start === previous ? dayChipLabel(start) : (dayChipLabel(start) + ' - ' + dayChipLabel(previous)))
                start = current
                previous = current
            }
            return ranges.join(', ')
        }

        function sameIdList(a, b) {
            var left = Array.isArray(a) ? a.slice().map(String).sort() : []
            var right = Array.isArray(b) ? b.slice().map(String).sort() : []
            if (left.length !== right.length) return false
            for (var i = 0; i < left.length; i++) {
                if (left[i] !== right[i]) return false
            }
            return true
        }

        function setSelectorOpen(isOpen) {
            if (!selectorOverlay) return
            selectorOverlay.classList.toggle('hidden', !isOpen)
            selectorOverlay.classList.toggle('flex', !!isOpen)
        }

        function closeSelectorModal() {
            if (selectorState.searchTimer) clearTimeout(selectorState.searchTimer)
            selectorState.searchTimer = null
            selectorState.searchSeq += 1
            selectorState.type = ''
            selectorState.items = []
            selectorState.activeItem = null
            selectorState.stagedServices = []
            if (selectorSearch) selectorSearch.value = ''
            setSelectorOpen(false)
        }

        function setSelectorLoading(message) {
            if (selectorListBody) {
                selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">' + escapeHtml(message || 'Loading records…') + '</div>'
            }
        }

        function updateSelectorConfirmState() {
            if (!selectorConfirmBtn) return
            var hasSelection = false
            if (selectorState.type === 'service') {
                hasSelection = !!(selectorState.stagedServices && selectorState.stagedServices.length)
            } else {
                hasSelection = !!selectorState.activeItem
            }
            selectorConfirmBtn.disabled = !hasSelection
        }

        function renderSelectorPatientDetail(patient) {
            if (!selectorDetailBody) return
            if (!patient) {
                selectorDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a patient to view details.</div>'
                updateSelectorConfirmState()
                return
            }
            var name = patientDisplayName(patient)
            var age = ageFromBirthdate(patient.birthdate)
            selectorDetailBody.innerHTML = '' +
                '<div class="space-y-3">' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-4">' +
                        '<div class="flex items-start gap-3">' +
                            renderProfileAvatar(patient, name) +
                            '<div class="min-w-0 flex-1">' +
                                '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-1">Patient</div>' +
                                '<div class="text-base font-semibold text-slate-900 break-words">' + escapeHtml(name) + '</div>' +
                                '<div class="mt-1 text-[0.78rem] text-slate-500">' + escapeHtml(patient && patient.email ? patient.email : '') + '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-4">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Patient Summary</div>' +
                        '<div class="grid grid-cols-2 gap-x-3 gap-y-2 text-[0.78rem]">' +
                            '<div class="text-slate-500">Age</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(age ? (age + ' years old') : '-') + '</div>' +
                            '<div class="text-slate-500">Date of Birth</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(patient.birthdate ? String(patient.birthdate).slice(0, 10) : '-') + '</div>' +
                            '<div class="text-slate-500">Address</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(patient.address || '-') + '</div>' +
                            '<div class="text-slate-500">Patient Type</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(patientTypeLabel(patient)) + '</div>' +
                            '<div class="text-slate-500">Sex</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(titleCase(patient.sex || '-') || '-') + '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            updateSelectorConfirmState()
        }

        function renderSelectorServiceDetail() {
            if (!selectorDetailBody) return
            var list = Array.isArray(selectorState.stagedServices) ? selectorState.stagedServices : []
            if (!list.length) {
                selectorDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select one or more services to review them here.</div>'
                updateSelectorConfirmState()
                return
            }
            var html = '' +
                '<div class="space-y-3">' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-4">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Selected Services</div>' +
                        '<div class="text-[0.78rem] text-slate-500">' + escapeHtml(String(list.length)) + ' service(s) selected</div>' +
                    '</div>' +
                    '<div class="space-y-2">'
            list.forEach(function (service) {
                var meta = []
                if (service && service.duration_minutes != null) meta.push(String(service.duration_minutes) + ' min')
                if (service && service.price != null) meta.push('₱' + String(service.price))
                html += '' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-4">' +
                        '<div class="text-[0.82rem] font-semibold text-slate-900">' + escapeHtml(serviceDisplayName(service) || 'Service') + '</div>' +
                        '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || '-') + '</div>' +
                        '<div class="mt-2 text-[0.75rem] text-slate-600">' + escapeHtml(service && service.description ? service.description : 'No description provided.') + '</div>' +
                    '</div>'
            })
            html += '</div></div>'
            selectorDetailBody.innerHTML = html
            updateSelectorConfirmState()
        }

        function renderSelectorDoctorDetail(doctor) {
            if (!selectorDetailBody) return
            if (!doctor) {
                selectorDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a doctor to view details.</div>'
                updateSelectorConfirmState()
                return
            }
            var name = 'Dr. ' + doctorDisplayName(doctor)
            selectorDetailBody.innerHTML = '' +
                '<div class="space-y-3">' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-4">' +
                        '<div class="flex items-start gap-3">' +
                            renderProfileAvatar(doctor, name) +
                            '<div class="min-w-0 flex-1">' +
                                '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-1">Doctor</div>' +
                                '<div class="text-base font-semibold text-slate-900 break-words">' + escapeHtml(name) + '</div>' +
                                '<div class="mt-1 text-[0.78rem] text-slate-500">#' + escapeHtml(doctor.user_id) + '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-4">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Doctor Summary</div>' +
                        '<div class="grid grid-cols-2 gap-x-3 gap-y-2 text-[0.78rem]">' +
                            '<div class="text-slate-500">Full Name</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(name) + '</div>' +
                            '<div class="text-slate-500">Specialization</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(doctor.specialization || '-') + '</div>' +
                            '<div class="text-slate-500">Schedule</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(doctorScheduleSummary(doctor)) + '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            updateSelectorConfirmState()
        }

        function renderSelectorDetail() {
            if (selectorState.type === 'patient') {
                renderSelectorPatientDetail(selectorState.activeItem)
                return
            }
            if (selectorState.type === 'service') {
                renderSelectorServiceDetail()
                return
            }
            if (selectorState.type === 'doctor') {
                renderSelectorDoctorDetail(selectorState.activeItem)
                return
            }
            if (selectorDetailBody) {
                selectorDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a record to view details.</div>'
            }
            updateSelectorConfirmState()
        }

        function fetchBookPatients(query) {
            if (typeof apiFetch !== 'function') return Promise.resolve([])
            var url = "{{ url('/api/patients') }}?per_page=15&sort=desc"
            var trimmed = String(query || '').trim()
            if (trimmed) url += '&search=' + encodeURIComponent(trimmed)
            return apiFetch(url, { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) return []
                    return result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                })
                .catch(function () { return [] })
        }

        function fetchBookPatientsPage(query, page) {
            if (typeof apiFetch !== 'function') return Promise.resolve({ data: [], hasMore: false })
            var params = 'per_page=10&page=' + page + '&sort=desc'
            if (query) params += '&search=' + encodeURIComponent(query)
            return apiFetch("{{ url('/api/patients') }}?" + params, { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (res) {
                    if (!res.ok) return { data: [], hasMore: false }
                    var items = Array.isArray(res.data.data) ? res.data.data : []
                    return {
                        data: items,
                        hasMore: (res.data.current_page || 1) < (res.data.last_page || 1)
                    }
                })
                .catch(function () { return { data: [], hasMore: false } })
        }

        function fetchBookDoctorsPage(query, page) {
            if (typeof apiFetch !== 'function') return Promise.resolve({ data: [], hasMore: false })
            var params = 'per_page=10&page=' + page
            if (query) params += '&search=' + encodeURIComponent(query)
            return apiFetch("{{ url('/api/doctors') }}?" + params, { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (res) {
                    if (!res.ok) return { data: [], hasMore: false }
                    var items = Array.isArray(res.data.data) ? res.data.data : []
                    if (query) {
                        var q = normalizeText(query)
                        items = items.filter(function (doc) {
                            return normalizeText(doc.specialization || '') === q
                        })
                    }
                    return {
                        data: items,
                        hasMore: (res.data.current_page || 1) < (res.data.last_page || 1)
                    }
                })
                .catch(function () { return { data: [], hasMore: false } })
        }

        function buildBookPatientList(items, query, hasMore) {
            if (!selectorListBody) return
            selectorState.items = Array.isArray(items) ? items : []
            if (selectorListLabel) {
                selectorListLabel.textContent = query ? 'Patient search results' : 'Latest patients'
            }
            if (!selectorState.items.length) {
                selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No patients found.</div>'
                renderSelectorDetail()
                return
            }
            var html = ''
            selectorState.items.forEach(function (patient, idx) {
                var active = selectorState.activeItem && String(selectorState.activeItem.user_id) === String(patient.user_id)
                var name = patientDisplayName(patient)
                var meta = []
                if (patient.email) meta.push(patient.email)
                if (patient.contact_number) meta.push(patient.contact_number)
                html += '' +
                    '<button type="button" class="reception-selector-patient w-full rounded-xl border px-3 py-3 text-left transition-colors ' + (active ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white hover:border-green-200 hover:bg-slate-50') + '" data-index="' + idx + '">' +
                        '<div class="flex items-start justify-between gap-3">' +
                            '<div class="min-w-0">' +
                                '<div class="text-[0.8rem] font-semibold text-slate-900 truncate">' + escapeHtml(name) + '</div>' +
                                '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || '#'+ String(patient.user_id)) + '</div>' +
                            '</div>' +
                            '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold border ' + (active ? 'border-green-200 bg-white text-green-700' : 'border-slate-200 bg-slate-50 text-slate-500') + '">' + (active ? 'Selected' : 'Recent') + '</span>' +
                        '</div>' +
                    '</button>'
            })
            if (hasMore) {
                html += '<button type="button" id="bookPatientLoadMoreBtn" class="w-full text-center py-2.5 mt-1 text-[0.75rem] font-semibold text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg border border-dashed border-slate-200 transition-colors">Load more patients</button>'
            }
            selectorListBody.innerHTML = html
            Array.prototype.forEach.call(selectorListBody.querySelectorAll('.reception-selector-patient'), function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(btn.getAttribute('data-index') || '-1', 10)
                    selectorState.activeItem = selectorState.items[idx] || null
                    buildBookPatientList(selectorState.items, query, hasMore)
                    renderSelectorDetail()
                })
            })
            var loadMoreBtn = document.getElementById('bookPatientLoadMoreBtn')
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    if (bookPatientSearchLoading) return
                    bookPatientSearchLoading = true
                    bookPatientSearchPage++
                    var page = bookPatientSearchPage
                    loadMoreBtn.textContent = 'Loading…'
                    loadMoreBtn.disabled = true
                    fetchBookPatientsPage(bookPatientSearchQuery, page).then(function (result) {
                        if (result.data.length) {
                            bookPatientSearchResults = bookPatientSearchResults.concat(result.data)
                        }
                        bookPatientSearchHasMore = result.hasMore
                        bookPatientSearchLoading = false
                        buildBookPatientList(bookPatientSearchResults, bookPatientSearchQuery, bookPatientSearchHasMore)
                    })
                })
            }
            renderSelectorDetail()
        }

        function bookServiceSourceList() {
            var query = normalizeText(selectorSearch ? selectorSearch.value : '')
            var list = (services || []).slice()
            var baseSelection = selectorState.stagedServices && selectorState.stagedServices.length ? selectorState.stagedServices[0] : null
            if (baseSelection) {
                var baseGroup = serviceGroup(baseSelection)
                if (baseGroup) {
                    list = list.filter(function (service) { return serviceGroup(service) === baseGroup })
                }
            }
            if (query) {
                list = list.filter(function (service) {
                    if (!service) return false
                    var name = service.service_name || ''
                    var desc = service.description || ''
                    return wordPrefixMatch(name, query) || wordPrefixMatch(desc, query)
                })
            }
            list.sort(function (a, b) {
                var ai = a && a.service_id != null ? parseInt(a.service_id, 10) : 0
                var bi = b && b.service_id != null ? parseInt(b.service_id, 10) : 0
                return (isNaN(bi) ? 0 : bi) - (isNaN(ai) ? 0 : ai)
            })
            if (previousServiceIds && previousServiceIds.length) {
                var order = {}
                previousServiceIds.forEach(function (id, idx) { order[String(id)] = idx })
                var pinned = []
                var rest = []
                list.forEach(function (service) {
                    var sid = service && service.service_id != null ? String(service.service_id) : ''
                    if (sid && order[sid] != null) pinned.push(service)
                    else rest.push(service)
                })
                pinned.sort(function (a, b) {
                    return (order[String(a.service_id)] || 0) - (order[String(b.service_id)] || 0)
                })
                list = pinned.concat(rest)
            }
            return list.slice(0, 12)
        }

        function fetchBookServicesPage(query, page) {
            if (typeof apiFetch !== 'function') return Promise.resolve({ data: [], hasMore: false })
            var params = 'per_page=10&page=' + page
            if (query) params += '&search=' + encodeURIComponent(query)
            return apiFetch("{{ url('/api/services') }}?" + params, { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (res) {
                    if (!res.ok) return { data: [], hasMore: false }
                    var items = Array.isArray(res.data.data) ? res.data.data : []
                    return {
                        data: items,
                        hasMore: (res.data.current_page || 1) < (res.data.last_page || 1)
                    }
                })
                .catch(function () { return { data: [], hasMore: false } })
        }

        function renderSelectorServiceList() {
            if (!selectorListBody) return
            var rawQuery = String(selectorSearch ? selectorSearch.value : '').trim()
            var query = normalizeText(rawQuery)
            var searchActive = !!query

            if (searchActive) {
                bookServiceSearchQuery = rawQuery
                bookServiceSearchPage = 1
                setSelectorLoading('Searching services…')
                fetchBookServicesPage(rawQuery, 1).then(function (result) {
                    if (selectorState.type !== 'service') return
                    if (String(selectorSearch ? selectorSearch.value : '').trim() !== rawQuery) return
                    bookServiceSearchResults = result.data
                    bookServiceSearchHasMore = result.hasMore
                    buildBookServiceList(result.data, rawQuery, result.hasMore)
                })
            } else {
                bookServiceSearchQuery = ''
                bookServiceSearchPage = 1
                var list = bookServiceSourceList()
                bookServiceSearchResults = list
                bookServiceSearchHasMore = false
                buildBookServiceList(list, '', false)
            }
        }

        function buildBookServiceList(items, query, hasMore) {
            if (!selectorListBody) return
            if (selectorListLabel) { selectorListLabel.textContent = query ? 'Service search results' : 'Latest services' }
            if (!items.length) {
                selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No services found.</div>'
                renderSelectorDetail()
                return
            }
            var html = ''
            items.forEach(function (service, idx) {
                var isSelected = (selectorState.stagedServices || []).some(function (entry) {
                    return String(entry && entry.service_id) === String(service && service.service_id)
                })
                var meta = []
                if (service && service.duration_minutes != null) meta.push(String(service.duration_minutes) + ' min')
                if (service && service.price != null) meta.push('₱' + String(service.price))
                var isLast = !!(previousServiceIdSet && previousServiceIdSet[String(service.service_id)])
                html += '' +
                    '<button type="button" class="reception-selector-service w-full rounded-xl border px-3 py-3 text-left transition-colors ' + (isSelected ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white hover:border-green-200 hover:bg-slate-50') + '" data-index="' + idx + '">' +
                        '<div class="flex items-start justify-between gap-3">' +
                            '<div class="min-w-0">' +
                                '<div class="text-[0.8rem] font-semibold text-slate-900 truncate">' + escapeHtml(serviceDisplayName(service) || 'Service') + '</div>' +
                                '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || '-') + '</div>' +
                                (service && service.description ? '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(service.description) + '</div>' : '') +
                            '</div>' +
                            '<div class="shrink-0 flex flex-col items-end gap-1">' +
                                (isLast ? '<span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[0.65rem] font-semibold text-amber-700">Last inquired</span>' : '') +
                                '<span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[0.65rem] font-semibold ' + (isSelected ? 'border-green-200 bg-white text-green-700' : 'border-slate-200 bg-slate-50 text-slate-500') + '">' + (isSelected ? 'Selected' : 'Add') + '</span>' +
                            '</div>' +
                        '</div>' +
                    '</button>'
            })
            if (hasMore) {
                html += '<button type="button" id="bookServiceLoadMoreBtn" class="w-full text-center py-2.5 mt-1 text-[0.75rem] font-semibold text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg border border-dashed border-slate-200 transition-colors">Load more services</button>'
            }
            selectorListBody.innerHTML = html
            selectorState.items = items
            Array.prototype.forEach.call(selectorListBody.querySelectorAll('.reception-selector-service'), function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(btn.getAttribute('data-index') || '-1', 10)
                    var chosen = selectorState.items[idx] || null
                    if (!chosen || chosen.service_id == null) return
                    var key = String(chosen.service_id)
                    var exists = (selectorState.stagedServices || []).some(function (entry) {
                        return String(entry && entry.service_id) === key
                    })
                    if (exists) {
                        selectorState.stagedServices = (selectorState.stagedServices || []).filter(function (entry) {
                            return String(entry && entry.service_id) !== key
                        })
                    } else {
                        selectorState.stagedServices = (selectorState.stagedServices || []).concat([chosen])
                    }
                    renderSelectorServiceList()
                    renderSelectorDetail()
                })
            })
            var loadMoreBtn = document.getElementById('bookServiceLoadMoreBtn')
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    if (bookServiceSearchLoading) return
                    bookServiceSearchLoading = true
                    bookServiceSearchPage++
                    var page = bookServiceSearchPage
                    loadMoreBtn.textContent = 'Loading…'
                    loadMoreBtn.disabled = true
                    fetchBookServicesPage(bookServiceSearchQuery, page).then(function (result) {
                        if (result.data.length) {
                            bookServiceSearchResults = bookServiceSearchResults.concat(result.data)
                        }
                        bookServiceSearchHasMore = result.hasMore
                        bookServiceSearchLoading = false
                        buildBookServiceList(bookServiceSearchResults, bookServiceSearchQuery, bookServiceSearchHasMore)
                    })
                })
            }
            renderSelectorDetail()
        }

        function bookDoctorSourceList() {
            var primary = selectedServices && selectedServices.length ? selectedServices[0] : null
            var category = extractServiceCategory(primary ? primary.service_name : '')
            if (!category) {
                return { needsService: true, list: [] }
            }
            var query = normalizeText(selectorSearch ? selectorSearch.value : '')
            var dateStr = dateInput && dateInput.value ? String(dateInput.value).slice(0, 10) : localDateIso()
            var dayKey = dayKeyFromDate(dateStr)
            var checkTime = selectedSlotStart ? String(selectedSlotStart).slice(0, 5) : ''
            var list = (doctors || []).filter(function (doctor) {
                return specializationMatches(category, doctor.specialization)
            })
            if (query) {
                list = list.filter(function (doctor) {
                    return wordPrefixMatch(doctorDisplayName(doctor) + ' ' + (doctor.specialization || ''), query)
                })
            }
            list.sort(function (a, b) {
                var ai = a && a.user_id != null ? parseInt(a.user_id, 10) : 0
                var bi = b && b.user_id != null ? parseInt(b.user_id, 10) : 0
                return (isNaN(bi) ? 0 : bi) - (isNaN(ai) ? 0 : ai)
            })
            var enriched = list.map(function (doctor) {
                var isDoctorAvailable = doctor && doctor.is_available !== false
                var hasSchedule = !!dayKey && hasScheduleAtTime(doctor, dayKey, dateStr, checkTime)
                var isSelectable = isDoctorAvailable && hasSchedule
                var tag = ''
                if (!isDoctorAvailable) tag = 'Unavailable'
                else if (!hasSchedule) tag = 'No schedule on this time'
                else if (previousDoctorId && parseInt(doctor.user_id, 10) === previousDoctorId) tag = 'Last provider'
                return {
                    doctor: doctor,
                    isSelectable: isSelectable,
                    tag: tag
                }
            })
            enriched.sort(function (a, b) {
                if (a.isSelectable !== b.isSelectable) return a.isSelectable ? -1 : 1
                if ((a.tag === 'Last provider') !== (b.tag === 'Last provider')) return a.tag === 'Last provider' ? -1 : 1
                return normalizeText(doctorDisplayName(a.doctor)).localeCompare(normalizeText(doctorDisplayName(b.doctor)))
            })
            return { needsService: false, list: enriched.slice(0, 10) }
        }

        function renderSelectorDoctorList() {
            if (!selectorListBody) return
            var rawQuery = String(selectorSearch ? selectorSearch.value : '').trim()
            var query = normalizeText(rawQuery)
            var searchActive = !!query

            if (searchActive) {
                bookDoctorSearchQuery = rawQuery
                bookDoctorSearchPage = 1
                setSelectorLoading('Searching doctors…')
                fetchBookDoctorsPage(rawQuery, 1).then(function (result) {
                    if (selectorState.type !== 'doctor') return
                    if (String(selectorSearch ? selectorSearch.value : '').trim() !== rawQuery) return
                    bookDoctorSearchResults = result.data
                    bookDoctorSearchHasMore = result.hasMore
                    buildBookDoctorList(result.data, rawQuery, result.hasMore)
                })
                return
            }

            var primary = selectedServices && selectedServices.length ? selectedServices[0] : null
            var category = extractServiceCategory(primary ? primary.service_name : '')
            if (!category) {
                selectorState.items = []
                if (selectorListLabel) selectorListLabel.textContent = 'Latest doctors'
                selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a service first to load matching doctors.</div>'
                renderSelectorDetail()
                return
            }

            bookDoctorSearchQuery = category
            bookDoctorSearchPage = 1
            setSelectorLoading('Loading doctors…')
            fetchBookDoctorsPage(category, 1).then(function (result) {
                if (selectorState.type !== 'doctor') return
                bookDoctorSearchResults = result.data
                bookDoctorSearchHasMore = result.hasMore
                if (selectorListLabel) selectorListLabel.textContent = 'Latest doctors'
                buildBookDoctorList(result.data, category, result.hasMore)
            })
        }

        function buildBookDoctorList(items, query, hasMore) {
            if (!selectorListBody) return
            selectorState.items = Array.isArray(items) ? items : []
            if (!selectorState.items.length) {
                selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No doctors found.</div>'
                renderSelectorDetail()
                return
            }
            var html = ''
            selectorState.items.forEach(function (doctor, idx) {
                var isActive = selectorState.activeItem && String(selectorState.activeItem.user_id) === String(doctor.user_id)
                html += '' +
                    '<button type="button" class="reception-selector-doctor w-full rounded-xl border px-3 py-3 text-left transition-colors ' + (isActive ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white hover:border-green-200 hover:bg-slate-50') + '" data-index="' + idx + '">' +
                        '<div class="flex items-start justify-between gap-3">' +
                            '<div class="min-w-0">' +
                                '<div class="text-[0.8rem] font-semibold text-slate-900 truncate">' + escapeHtml('Dr. ' + doctorDisplayName(doctor)) + '</div>' +
                                '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(doctor.specialization || '-') + '</div>' +
                            '</div>' +
                        '</div>' +
                    '</button>'
            })
            if (hasMore) {
                html += '<button type="button" id="bookDoctorLoadMoreBtn" class="w-full text-center py-2.5 mt-1 text-[0.75rem] font-semibold text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg border border-dashed border-slate-200 transition-colors">Load more doctors</button>'
            }
            selectorListBody.innerHTML = html
            Array.prototype.forEach.call(selectorListBody.querySelectorAll('.reception-selector-doctor'), function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(btn.getAttribute('data-index') || '-1', 10)
                    var chosen = selectorState.items[idx] || null
                    selectorState.activeItem = chosen
                    buildBookDoctorList(selectorState.items, query, hasMore)
                    renderSelectorDetail()
                })
            })
            var loadMoreBtn = document.getElementById('bookDoctorLoadMoreBtn')
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    if (bookDoctorSearchLoading) return
                    bookDoctorSearchLoading = true
                    bookDoctorSearchPage++
                    var page = bookDoctorSearchPage
                    loadMoreBtn.textContent = 'Loading…'
                    loadMoreBtn.disabled = true
                    fetchBookDoctorsPage(bookDoctorSearchQuery, page).then(function (result) {
                        if (result.data.length) {
                            bookDoctorSearchResults = bookDoctorSearchResults.concat(result.data)
                        }
                        bookDoctorSearchHasMore = result.hasMore
                        bookDoctorSearchLoading = false
                        buildBookDoctorList(bookDoctorSearchResults, bookDoctorSearchQuery, bookDoctorSearchHasMore)
                    })
                })
            }
            renderSelectorDetail()
        }

        function ensureBookServicesLoaded() {
            if (servicesLoaded && services.length) return Promise.resolve(services)
            if (servicesLoading) return new Promise(function (resolve) {
                var check = setInterval(function () {
                    if (servicesLoaded) { clearInterval(check); resolve(services) }
                }, 16)
            })
            if (typeof apiFetch !== 'function') return Promise.resolve([])
            servicesLoading = true
            return apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) return services || []
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var allowedServiceNames = [
                        'general surgeon',
                        'obstetrician-gynecologist',
                        'obstetrician - gynecologist',
                        'internal medicine'
                    ]
                    services = (raw || []).filter(function (s) {
                        var name = normalizeText(s && s.service_name ? s.service_name : '')
                        return allowedServiceNames.indexOf(name) !== -1
                    })
                    servicesLoaded = true
                    return services
                })
                .catch(function () { return services || [] })
                .finally(function () { servicesLoading = false })
        }

        function ensureBookDoctorsLoaded() {
            if (doctorsLoaded && doctors.length) return Promise.resolve(doctors)
            if (typeof apiFetch !== 'function') return Promise.resolve([])
            return apiFetch("{{ url('/api/doctors') }}?per_page=15", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) return doctors || []
                    doctors = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    doctorsLoaded = true
                    return doctors
                })
                .catch(function () { return doctors || [] })
        }

        function openSelectorModal(type) {
            selectorState.type = type
            selectorState.items = []
            selectorState.activeItem = null
            selectorState.stagedServices = []
            if (selectorSearch) selectorSearch.value = ''
            if (type === 'patient') {
                selectorState.activeItem = selectedPatient
                if (selectorTitle) selectorTitle.textContent = 'Select Patient'
                if (selectorSubtitle) selectorSubtitle.textContent = 'Choose from the most recently created patient records or search for another patient.'
                if (selectorSearch) selectorSearch.placeholder = 'Search patient by name, email, contact, or address'
                if (selectorConfirmBtn) selectorConfirmBtn.textContent = 'Select Patient'
                setSelectorOpen(true)
                setSelectorLoading('Loading patients…')
                bookPatientSearchQuery = ''
                bookPatientSearchPage = 1
                fetchBookPatientsPage('', 1).then(function (result) {
                    if (selectorState.type !== 'patient') return
                    bookPatientSearchResults = result.data
                    bookPatientSearchHasMore = result.hasMore
                    buildBookPatientList(result.data, '', result.hasMore)
                })
            } else if (type === 'service') {
                selectorState.stagedServices = Array.isArray(selectedServices) ? selectedServices.slice() : []
                if (selectorTitle) selectorTitle.textContent = 'Select Service/s'
                if (selectorSubtitle) selectorSubtitle.textContent = 'Choose one or more services from the latest records or search for another service.'
                if (selectorSearch) selectorSearch.placeholder = 'Search service name'
                if (selectorConfirmBtn) selectorConfirmBtn.textContent = 'Select Service/s'
                setSelectorOpen(true)
                setSelectorLoading('Loading services…')
                ensureBookServicesLoaded().then(function () {
                    if (selectorState.type !== 'service') return
                    renderSelectorServiceList()
                })
            } else if (type === 'doctor') {
                selectorState.activeItem = selectedDoctor
                if (selectorTitle) selectorTitle.textContent = 'Select Doctor'
                if (selectorSubtitle) selectorSubtitle.textContent = 'Choose from the most recently created matching doctors or search for another doctor.'
                if (selectorSearch) selectorSearch.placeholder = 'Search doctor name or specialization'
                if (selectorConfirmBtn) selectorConfirmBtn.textContent = 'Select Doctor'
                setSelectorOpen(true)
                setSelectorLoading('Loading doctors…')
                Promise.all([ensureBookServicesLoaded(), ensureBookDoctorsLoaded()]).then(function () {
                    if (selectorState.type !== 'doctor') return
                    renderSelectorDoctorList()
                })
            } else {
                return
            }
            renderSelectorDetail()
            updateSelectorConfirmState()
            window.setTimeout(function () {
                if (selectorSearch && selectorState.type === type) selectorSearch.focus()
            }, 80)
        }

        function appointmentDoctorDisplayName(appointment) {
            if (!appointment) return '-'
            var doctor = appointment.doctor || null
            if (doctor) {
                var name = [doctor.firstname, doctor.middlename, doctor.lastname]
                    .filter(function (v) { return String(v || '').trim() !== '' })
                    .join(' ')
                    .trim()
                if (name) return name
                if (doctor.email) return doctor.email
            }
            return '-'
        }

        function formatAppointmentVisitLabel(value) {
            if (!value) return 'No scheduled visit yet.'
            var date = new Date(value)
            if (isNaN(date.getTime())) {
                return String(value || '').replace('T', ' ').slice(0, 16) || 'No scheduled visit yet.'
            }
            return date.toLocaleString(undefined, {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit'
            })
        }

        function setPatientSummaryCard(summary) {
            if (!patientSummaryEmpty || !patientSummaryDetails || !patientSummaryVisit || !patientSummaryService || !patientSummaryDoctor) return
            if (!summary) {
                patientSummaryEmpty.textContent = 'No patient selected.'
                patientSummaryEmpty.classList.remove('hidden')
                patientSummaryDetails.classList.add('hidden')
                patientSummaryVisit.textContent = '-'
                patientSummaryService.textContent = '-'
                patientSummaryDoctor.textContent = '-'
                return
            }
            patientSummaryEmpty.classList.add('hidden')
            patientSummaryDetails.classList.remove('hidden')
            patientSummaryVisit.textContent = summary.lastVisit || '-'
            patientSummaryService.textContent = summary.serviceInquired || '-'
            patientSummaryDoctor.textContent = summary.doctor || '-'
        }

        function setPatientSelection(patient) {
            selectedPatient = patient || null
            if (patientSelect) patientSelect.value = patient && patient.user_id ? String(patient.user_id) : ''
            syncSelectionTriggers()
            previousDoctorId = 0
            previousServiceIds = []
            previousServiceIdSet = {}
            setPatientSummaryCard(null)

            if (patientPreview) {
                if (!patient) {
                    patientPreview.textContent = ''
                    patientPreview.classList.add('hidden')
                } else {
                    var parts = []
                    var name = [patient.firstname, patient.middlename, patient.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                    if (!name) name = patient.email ? String(patient.email) : ''
                    parts.push('Name: ' + name)
                    if (patient.birthdate) parts.push('Birthdate: ' + String(patient.birthdate).slice(0, 10))
                    if (patient.contact_number) parts.push('Contact: ' + patient.contact_number)
                    if (patient.address) parts.push('Address: ' + patient.address)
                    patientPreview.textContent = parts.join(' • ')
                    patientPreview.classList.remove('hidden')
                }
            }

            if (patientResults) {
                patientResults.innerHTML = ''
                patientResults.classList.add('hidden')
            }

            if (patient && patient.user_id) {
                setPatientSummaryCard({
                    lastVisit: 'Loading...',
                    serviceInquired: 'Loading...',
                    doctor: 'Loading...'
                })
                loadPreviousProvider(String(patient.user_id))
            }
        }

        function loadPreviousProvider(patientId) {
            if (!patientId || typeof apiFetch !== 'function') return
            apiFetch("{{ url('/api/appointments') }}?patient_id=" + encodeURIComponent(patientId) + "&appointment_type=scheduled&per_page=1&order=latest", { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (res) {
                    if (!selectedPatient || String(selectedPatient.user_id || '') !== String(patientId)) return
                    if (!res.ok) {
                        setPatientSummaryCard({
                            lastVisit: 'No scheduled visit yet.',
                            serviceInquired: '-',
                            doctor: '-'
                        })
                        return
                    }
                    var list = res.data && Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : [])
                    var last = list && list.length ? list[0] : null
                    var docId = last && last.doctor_id != null ? parseInt(last.doctor_id, 10) : 0
                    previousDoctorId = (!docId || isNaN(docId)) ? 0 : docId

                    previousServiceIds = []
                    previousServiceIdSet = {}
                    var lastServices = last && Array.isArray(last.services) ? last.services : []
                    lastServices.forEach(function (s) {
                        var sid = s && s.service_id != null ? parseInt(s.service_id, 10) : 0
                        if (!sid || isNaN(sid)) return
                        if (previousServiceIdSet[String(sid)]) return
                        previousServiceIdSet[String(sid)] = true
                        previousServiceIds.push(sid)
                    })

                    var serviceText = lastServices
                        .map(function (service) { return serviceDisplayName(service) })
                        .filter(function (name) { return !!String(name || '').trim() })
                        .join(', ')

                    setPatientSummaryCard({
                        lastVisit: formatAppointmentVisitLabel(last && last.appointment_datetime ? last.appointment_datetime : ''),
                        serviceInquired: serviceText || '-',
                        doctor: appointmentDoctorDisplayName(last)
                    })

                    renderDoctorResults()
                    if (serviceSearch && (document.activeElement === serviceSearch || (serviceResults && !serviceResults.classList.contains('hidden')))) {
                        renderServiceResults()
                    }
                })
                .catch(function () {
                    if (!selectedPatient || String(selectedPatient.user_id || '') !== String(patientId)) return
                    setPatientSummaryCard({
                        lastVisit: 'No scheduled visit yet.',
                        serviceInquired: '-',
                        doctor: '-'
                    })
                })
        }

        function renderPatientResults(items) {
            if (!patientResults) return
            var list = Array.isArray(items) ? items : []
            if (!list.length) {
                patientResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No patients found.</div>'
                patientResults.classList.remove('hidden')
                return
            }

            var html = ''
            list.forEach(function (p) {
                var name = [p.firstname, p.middlename, p.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                if (!name) name = p.email ? String(p.email) : ''
                var meta = [p.email, p.contact_number].filter(Boolean).join(' • ')
                html += '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 border-b border-slate-100 last:border-0">' +
                    '<div class="text-[0.78rem] text-slate-800 font-semibold">' + escapeHtml(name) + '</div>' +
                    '<div class="text-[0.72rem] text-slate-500">' + (meta ? escapeHtml(meta) : '-') + '</div>' +
                '</button>'
            })
            patientResults.innerHTML = html
            patientResults.classList.remove('hidden')

            var buttons = patientResults.querySelectorAll('button')
            Array.prototype.forEach.call(buttons, function (btn, idx) {
                btn.addEventListener('click', function () {
                    var chosen = list[idx]
                    setPatientSelection(chosen)
                    if (patientSearch) {
                        patientSearch.value = patientDisplayName(chosen)
                    }
                })
            })
        }

        var patientInitialList = []
        var patientInitialLoaded = false
        var patientInitialLoading = false

        function searchPatients(query) {
            if (typeof apiFetch !== 'function') return
            apiFetch("{{ url('/api/patients') }}?per_page=15&sort=desc&search=" + encodeURIComponent(query), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        renderPatientResults([])
                        return
                    }
                    var list = []
                    if (result.data && Array.isArray(result.data.data)) {
                        list = result.data.data
                    } else if (Array.isArray(result.data)) {
                        list = result.data
                    }
                    renderPatientResults(list)
                })
                .catch(function () {
                    renderPatientResults([])
                })
        }

        function loadInitialPatients() {
            if (patientInitialLoaded || patientInitialLoading || typeof apiFetch !== 'function') return
            patientInitialLoading = true
            apiFetch("{{ url('/api/patients') }}?per_page=15&sort=desc", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok) return
                    var list = []
                    if (result.data && Array.isArray(result.data.data)) {
                        list = result.data.data
                    } else if (Array.isArray(result.data)) {
                        list = result.data
                    }
                    patientInitialList = Array.isArray(list) ? list : []
                    patientInitialLoaded = true
                })
                .catch(function () {})
                .finally(function () {
                    patientInitialLoading = false
                })
        }

        function dayKeyFromDate(dateStr) {
            if (!dateStr) return ''
            var d = new Date(dateStr + 'T00:00:00')
            if (isNaN(d.getTime())) return ''
            var keys = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']
            return keys[d.getDay()] || ''
        }

        function normalizeDayKey(raw) {
            var v = String(raw == null ? '' : raw).trim().toLowerCase()
            if (!v) return ''
            if (/^\d+$/.test(v)) {
                var n = parseInt(v, 10)
                var keys = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']
                return keys[n] || ''
            }
            var map = {
                sun: 'sun', sunday: 'sun',
                mon: 'mon', monday: 'mon',
                tue: 'tue', tues: 'tue', tuesday: 'tue',
                wed: 'wed', wednesday: 'wed',
                thu: 'thu', thur: 'thu', thurs: 'thu', thursday: 'thu',
                fri: 'fri', friday: 'fri',
                sat: 'sat', saturday: 'sat'
            }
            if (map[v]) return map[v]
            var s3 = v.slice(0, 3)
            return map[s3] || ''
        }

        function dayLabelFromKey(key) {
            var map = { sun: 'Sun', mon: 'Mon', tue: 'Tue', wed: 'Wed', thu: 'Thu', fri: 'Fri', sat: 'Sat' }
            return map[String(key || '').toLowerCase()] || String(key || '')
        }

        function minutesFromHHMM(timeStr) {
            var t = String(timeStr || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return NaN
            var parts = t.split(':')
            return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10)
        }

        function formatTime12h(hhmmss) {
            var t = String(hhmmss || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return t
            var parts = t.split(':')
            var h24 = parseInt(parts[0], 10)
            var m = parts[1]
            var ap = h24 >= 12 ? 'PM' : 'AM'
            var h12 = h24 % 12
            if (h12 === 0) h12 = 12
            return h12 + ':' + m + ' ' + ap
        }

        function readResponse(response) {
            return response.text().then(function (text) {
                var data = null
                try {
                    data = text ? JSON.parse(text) : null
                } catch (e) {
                    data = null
                }
                return { ok: response.ok, status: response.status, data: data, raw: text }
            })
        }

        function formatLocalDateIso(dateObj) {
            var d = dateObj instanceof Date ? dateObj : new Date()
            var y = d.getFullYear()
            var m = String(d.getMonth() + 1).padStart(2, '0')
            var day = String(d.getDate()).padStart(2, '0')
            return String(y) + '-' + m + '-' + day
        }

        function localDateIso() {
            return formatLocalDateIso(new Date())
        }

        function clearAvailability() {
            doctorSchedules = []
            doctorAvailableDaySet = {}
            doctorAppointments = []
            doctorMonthAppointments = {}
            selectedSlotStart = null
            if (timeInput) timeInput.value = ''
            if (dateInput) dateInput.value = ''
            datePickerMonth = (function () {
                var now = new Date()
                return new Date(now.getFullYear(), now.getMonth(), 1)
            })()
            updateScheduleCard()
            syncScheduleUI()
            if (scheduleOverlay && !scheduleOverlay.classList.contains('hidden')) {
                scheduleOverlay.classList.add('hidden')
            }
        }

        function updateScheduleCard() {
            if (!scheduleCard || !scheduleCardText) return
            var date = dateInput && dateInput.value ? String(dateInput.value).slice(0, 10) : ''
            var time = timeInput ? timeInput.value : ''
            if (date && time) {
                scheduleCard.classList.remove('hidden')
                scheduleCardText.textContent = friendlyDateLabelFromIso(date) + ' · ' + formatTime12h(time)
                scheduleTrigger.textContent = 'Change Schedule'
            } else {
                scheduleCard.classList.add('hidden')
                scheduleCardText.textContent = ''
                scheduleTrigger.textContent = 'Select Schedule'
            }
        }

        function openScheduleModal() {
            if (!scheduleOverlay) return
            scheduleOverlay.classList.remove('hidden')
            scheduleOverlay.classList.add('flex')
            renderScheduleDatePicker()
            renderScheduleTimeSlotsInModal()
            if (doctorSelect && doctorSelect.value) loadMonthAppointments(doctorSelect.value)
        }

        function closeScheduleModal() {
            if (scheduleOverlay) {
                scheduleOverlay.classList.add('hidden')
                scheduleOverlay.classList.remove('flex')
            }
        }

        function serviceKey(service) {
            var id = service && service.service_id != null ? parseInt(service.service_id, 10) : 0
            return String(id || '')
        }

        function serviceGroup(service) {
            if (!service) return ''
            var name = String(service.service_name || '').trim()
            if (!name) return ''
            var parts = name.split(':')
            var group = String(parts[0] || name).trim().toLowerCase()
            return group
        }

        function selectedServiceIds() {
            return (selectedServices || [])
                .map(function (s) { return s && s.service_id != null ? parseInt(s.service_id, 10) : 0 })
                .filter(function (v) { return !!v && !isNaN(v) })
        }

        function syncServiceHiddenInput() {
            if (!serviceSelect) return
            var ids = selectedServiceIds()
            serviceSelect.value = ids.length ? String(ids[0]) : ''
        }

        function renderSelectedServices() {
            if (!selectedServicesEl) return
            var list = Array.isArray(selectedServices) ? selectedServices : []
            syncSelectionTriggers()
            if (!list.length) {
                var emptyMsg = document.getElementById('receptionSelectedServicesEmpty')
                if (emptyMsg) {
                    selectedServicesEl.innerHTML = ''
                    emptyMsg.style.display = ''
                    selectedServicesEl.appendChild(emptyMsg)
                } else {
                    selectedServicesEl.innerHTML = '<span id="receptionSelectedServicesEmpty" class="text-[0.78rem] text-slate-400">No selected services.</span>'
                }
                return
            }

            selectedServicesEl.innerHTML = list.map(function (s) {
                var id = parseInt(s && s.service_id != null ? s.service_id : 0, 10)
                var name = String(s && s.service_name ? s.service_name : '').trim() || 'Service'
                var fee = s && s.price != null ? '\u20B1' + String(s.price) : ''
                var time = s && s.duration_minutes != null ? String(s.duration_minutes) + ' min' : ''
                var desc = s && s.description != null ? String(s.description).trim() : ''
                var parts = []
                if (fee) parts.push('<span class="text-emerald-600">' + escapeHtml(fee) + '</span>')
                if (time) parts.push('<span>' + escapeHtml(time) + '</span>')
                if (desc) parts.push('<span class="text-slate-500">' + escapeHtml(desc) + '</span>')
                return '' +
                    '<div class="flex items-center gap-2 py-1.5 border-b border-slate-100 last:border-0">' +
                        '<div class="flex-1 min-w-0">' +
                            '<div class="text-[0.78rem] text-slate-800 font-semibold truncate">' + escapeHtml(name) + '</div>' +
                            (parts.length ? '<div class="text-[0.68rem] text-slate-400">' + parts.join(' <span class="text-slate-300">|</span> ') + '</div>' : '') +
                        '</div>' +
                        '<button type="button" class="reception-remove-service shrink-0 inline-flex items-center justify-center w-6 h-6 rounded-md border border-slate-200 bg-white text-slate-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors" data-service-id="' + escapeHtml(id) + '">' +
                            iconX +
                        '</button>' +
                    '</div>'
            }).join('')

            var buttons = selectedServicesEl.querySelectorAll('.reception-remove-service')
            Array.prototype.forEach.call(buttons, function (btn) {
                btn.addEventListener('click', function () {
                    var id = parseInt(btn.getAttribute('data-service-id') || '0', 10)
                    if (!id) return
                    selectedServices = (selectedServices || []).filter(function (s) {
                        return parseInt(s && s.service_id ? s.service_id : 0, 10) !== id
                    })
                    syncServiceHiddenInput()
                    renderSelectedServices()

                    if (!selectedServices.length) {
                        if (doctorSearch) doctorSearch.disabled = true
                        if (doctorSearch) doctorSearch.value = ''
                        setDoctorSelection(null)
                        renderServiceResults()
                    } else {
                        if (doctorSearch) doctorSearch.disabled = false
                        setDoctorSelection(null)
                        renderDoctorResults()
                        renderServiceResults()
                    }
                })
            })
        }

        function addService(service) {
            if (!service || service.service_id == null) return
            var key = serviceKey(service)
            if (!key) return
            var exists = (selectedServices || []).some(function (s) { return serviceKey(s) === key })
            if (exists) return

            selectedServices = (selectedServices || []).concat([service])
            syncServiceHiddenInput()
            renderSelectedServices()

            if (serviceResults) {
                serviceResults.innerHTML = ''
                serviceResults.classList.add('hidden')
            }
            if (serviceSearch) serviceSearch.value = ''

            if (doctorSearch) doctorSearch.disabled = selectedServices.length === 0
            setDoctorSelection(null)
        }

        function renderServiceResults() {
            if (!serviceResults) return
            var q = serviceSearch ? normalizeText(serviceSearch.value) : ''
            var list = (services || []).slice()

            if (selectedServices && selectedServices.length) {
                var base = serviceGroup(selectedServices[0])
                if (base) {
                    list = list.filter(function (s) {
                        return serviceGroup(s) === base
                    })
                }
            }

            if (q) {
                list = list.filter(function (s) {
                    var name = normalizeText(s && s.service_name ? s.service_name : '')
                    return wordPrefixMatch(name, q)
                })
            }
            list.sort(function (a, b) {
                var ai = a && a.service_id != null ? parseInt(a.service_id, 10) : 0
                var bi = b && b.service_id != null ? parseInt(b.service_id, 10) : 0
                return (isNaN(bi) ? 0 : bi) - (isNaN(ai) ? 0 : ai)
            })

            if (previousServiceIds && previousServiceIds.length) {
                var order = {}
                previousServiceIds.forEach(function (id, idx) {
                    order[String(id)] = idx
                })
                var pinned = []
                var rest = []
                list.forEach(function (s) {
                    var sid = s && s.service_id != null ? String(s.service_id) : ''
                    if (sid !== '' && order[sid] != null) pinned.push(s)
                    else rest.push(s)
                })
                pinned.sort(function (a, b) {
                    return (order[String(a.service_id)] || 0) - (order[String(b.service_id)] || 0)
                })
                list = pinned.concat(rest)
            }

            list = list.slice(0, 10)
            if (!list.length) {
                serviceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No services found.</div>'
                serviceResults.classList.remove('hidden')
                return
            }
            var html = ''
            list.forEach(function (s) {
                var title = s.service_name || ('Service #' + s.service_id)
                var meta = []
                if (s && s.duration_minutes != null) meta.push(String(s.duration_minutes) + ' min')
                if (s && s.price != null) meta.push('₱' + String(s.price))
                var sub = s.description ? String(s.description).trim() : ''
                var isLast = !!(previousServiceIdSet && previousServiceIdSet[String(s.service_id)])
                var tag = isLast
                    ? '<span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold bg-amber-50 text-amber-800 border border-amber-200">Last inquired</span>'
                    : ''
                html += '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 border-b border-slate-100 last:border-0">' +
                    '<div class="text-[0.78rem] text-slate-800 font-semibold flex items-center justify-between gap-2">' +
                        '<span class="min-w-0 truncate">' + escapeHtml(title) + '</span>' +
                        tag +
                    '</div>' +
                    '<div class="text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || '-') + '</div>' +
                    (sub ? '<div class="mt-0.5 text-[0.72rem] text-slate-500">' + escapeHtml(sub) + '</div>' : '') +
                '</button>'
            })
            serviceResults.innerHTML = html
            serviceResults.classList.remove('hidden')

            var buttons = serviceResults.querySelectorAll('button')
            Array.prototype.forEach.call(buttons, function (btn, idx) {
                btn.addEventListener('click', function () {
                    var chosen = list[idx]
                    addService(chosen)
                })
            })
        }

        function doctorLabel(d) {
            if (!d) return ''
            var name = [d.firstname, d.middlename, d.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            if (!name) name = d.email || ''
            return name + (d.specialization ? ' - ' + d.specialization : '')
        }

        function doctorSchedulesForDay(doctor, dayKey, dateStr) {
            var list = doctor && doctor.doctor_schedules && Array.isArray(doctor.doctor_schedules) ? doctor.doctor_schedules : []
            var isToday = false
            if (dateStr) {
                var today = localDateIso()
                isToday = String(dateStr) === today
            }
            return list.filter(function (s) {
                if (!s) return false
                if (normalizeDayKey(s.day_of_week) !== normalizeDayKey(dayKey)) return false
                if (isToday && s.is_available === false) return false
                return true
            })
        }

        function hasScheduleAtTime(doctor, dayKey, dateStr, hhmm) {
            var slots = doctorSchedulesForDay(doctor, dayKey, dateStr)
            if (!hhmm) return slots.length > 0
            var t = minutesFromHHMM(String(hhmm || '').slice(0, 5))
            if (isNaN(t)) return slots.length > 0
            return slots.some(function (s) {
                var st = minutesFromHHMM(String(s.start_time || '').slice(0, 5))
                var en = minutesFromHHMM(String(s.end_time || '').slice(0, 5))
                if (isNaN(st) || isNaN(en)) return false
                return t >= st && t < en
            })
        }

        function setDoctorSelection(doctor) {
            selectedDoctor = doctor || null
            if (doctorSelect) doctorSelect.value = doctor && doctor.user_id ? String(doctor.user_id) : ''
            syncSelectionTriggers()

            if (doctorPreview) {
                if (!doctor) {
                    doctorPreview.textContent = ''
                    doctorPreview.classList.add('hidden')
                } else {
                    var name = doctorDisplayName(doctor) || (doctor.email || '')
                    var spec = doctor.specialization ? String(doctor.specialization).trim() : 'N/A'
                    var sched = doctorScheduleSummary(doctor)
                    var lines = []
                    lines.push('<div class="text-[0.78rem] text-slate-700 font-semibold">' + escapeHtml(name) + '</div>')
                    lines.push('<div class="text-[0.7rem] text-slate-500">Specialization: ' + escapeHtml(spec) + '</div>')
                    lines.push('<div class="text-[0.7rem] text-slate-500">Schedule: ' + escapeHtml(sched) + '</div>')
                    doctorPreview.innerHTML = lines.join('')
                    doctorPreview.classList.remove('hidden')
                }
            }

            if (doctorResults) {
                doctorResults.innerHTML = ''
                doctorResults.classList.add('hidden')
            }

            clearAvailability()

            if (doctor && doctor.user_id) {
                var embedded = doctor.doctor_schedules
                if (Array.isArray(embedded) && embedded.length) {
                    doctorSchedules = embedded.slice()
                    buildAvailableDaySet()
                    syncScheduleUI()
                    renderScheduleDatePicker()
                }
                loadDoctorSchedulesAndAvailability(doctor.user_id, dateInput ? dateInput.value : '')
                applyAppointmentTypeUI()
            }
        }

        function renderDoctorResults() {
            if (!doctorResults) return
            var q = doctorSearch ? normalizeText(doctorSearch.value) : ''

            var primary = selectedServices && selectedServices.length ? selectedServices[0] : null
            var category = extractServiceCategory(primary ? primary.service_name : '')
            var list = []
            if (category) {
                list = (doctors || []).filter(function (d) {
                    return specializationMatches(category, d.specialization)
                })
            }

            if (q) {
                list = list.filter(function (d) {
                    return wordPrefixMatch(doctorLabel(d), q)
                })
            }

            list.sort(function (a, b) {
                var ai = a && a.user_id != null ? parseInt(a.user_id, 10) : 0
                var bi = b && b.user_id != null ? parseInt(b.user_id, 10) : 0
                return (isNaN(bi) ? 0 : bi) - (isNaN(ai) ? 0 : ai)
            })
            list = list.slice(0, 8)

            if (!category) {
                doctorResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">Select a service first.</div>'
                doctorResults.classList.remove('hidden')
                return
            }

            if (!list.length) {
                doctorResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No doctors found.</div>'
                doctorResults.classList.remove('hidden')
                return
            }

            var dateStr = dateInput && dateInput.value ? String(dateInput.value).slice(0, 10) : localDateIso()
            var dayKey = dayKeyFromDate(dateStr)
            var checkTime = selectedSlotStart ? String(selectedSlotStart).slice(0, 5) : ''

            var enriched = list.map(function (d) {
                var name = [d.firstname, d.middlename, d.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                if (!name) name = d.email || ''
                var isDoctorAvailable = d && d.is_available !== false
                var hasSchedule = !!dayKey && hasScheduleAtTime(d, dayKey, dateStr, checkTime)
                var isSelectable = isDoctorAvailable && hasSchedule
                var tag = ''
                if (!isDoctorAvailable) tag = 'Unavailable'
                else if (!hasSchedule) tag = 'No schedule on this time'
                else if (previousDoctorId && parseInt(d.user_id, 10) === previousDoctorId) tag = 'Last provider'
                return { d: d, name: name, isSelectable: isSelectable, tag: tag }
            })

            enriched.sort(function (a, b) {
                if (a.isSelectable !== b.isSelectable) return a.isSelectable ? -1 : 1
                if ((a.tag === 'Last provider') !== (b.tag === 'Last provider')) return a.tag === 'Last provider' ? -1 : 1
                var ai = a.d && a.d.user_id != null ? parseInt(a.d.user_id, 10) : 0
                var bi = b.d && b.d.user_id != null ? parseInt(b.d.user_id, 10) : 0
                return (isNaN(bi) ? 0 : bi) - (isNaN(ai) ? 0 : ai)
            })

            enriched = enriched.slice(0, 8)

            var html = ''
            enriched.forEach(function (x) {
                var d = x.d
                var name = x.name
                html += '<button type="button" class="w-full text-left px-3 py-2 border-b border-slate-100 last:border-0 flex items-start justify-between gap-3 ' + (x.isSelectable ? 'hover:bg-slate-50' : 'bg-slate-50/60 cursor-not-allowed') + '" ' + (x.isSelectable ? '' : 'disabled') + '>' +
                    '<div class="min-w-0">' +
                        '<div class="text-[0.78rem] text-slate-800 font-semibold">' + escapeHtml('Dr. ' + name) + '</div>' +
                        '<div class="text-[0.72rem] text-slate-500">' + escapeHtml(d.specialization || '-') + '</div>' +
                    '</div>' +
                    (x.tag
                        ? '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold ' + ((x.tag === 'Last provider') ? 'bg-green-500/10 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-500 border border-slate-200') + '">' + escapeHtml(x.tag) + '</span>'
                        : '') +
                '</button>'
            })
            doctorResults.innerHTML = html
            doctorResults.classList.remove('hidden')

            var buttons = doctorResults.querySelectorAll('button')
            Array.prototype.forEach.call(buttons, function (btn, idx) {
                btn.addEventListener('click', function () {
                    var chosen = enriched[idx] ? enriched[idx].d : null
                    if (!chosen) return
                    setDoctorSelection(chosen)
                    if (doctorSearch) doctorSearch.value = [chosen.firstname, chosen.middlename, chosen.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim() || (chosen.email || '')
                })
            })
        }

        if (selectorSearch) {
            selectorSearch.addEventListener('input', function () {
                if (selectorState.searchTimer) clearTimeout(selectorState.searchTimer)
                if (selectorState.type === 'patient') {
                    var query = String(selectorSearch.value || '').trim()
                    selectorState.searchTimer = window.setTimeout(function () {
                        var requestId = ++selectorState.searchSeq
                        setSelectorLoading(query ? 'Searching patients…' : 'Loading patients…')
                        bookPatientSearchQuery = query
                        bookPatientSearchPage = 1
                        fetchBookPatientsPage(query, 1).then(function (result) {
                            if (selectorState.type !== 'patient' || selectorState.searchSeq !== requestId) return
                            bookPatientSearchResults = result.data
                            bookPatientSearchHasMore = result.hasMore
                            buildBookPatientList(result.data, query, result.hasMore)
                        })
                    }, 250)
                    return
                }
                if (selectorState.type === 'service') {
                    selectorState.searchTimer = window.setTimeout(function () {
                        renderSelectorServiceList()
                    }, 250)
                    return
                }
                if (selectorState.type === 'doctor') {
                    renderSelectorDoctorList()
                }
            })
        }

        if (selectorConfirmBtn) {
            selectorConfirmBtn.addEventListener('click', function () {
                if (selectorState.type === 'patient') {
                    if (!selectorState.activeItem) return
                    setPatientSelection(selectorState.activeItem)
                    closeSelectorModal()
                    return
                }
                if (selectorState.type === 'service') {
                    var nextServices = Array.isArray(selectorState.stagedServices) ? selectorState.stagedServices.slice() : []
                    var currentIds = selectedServiceIds()
                    var nextIds = nextServices.map(function (service) { return service && service.service_id != null ? parseInt(service.service_id, 10) : 0 }).filter(function (id) { return !!id && !isNaN(id) })
                    var changed = !sameIdList(currentIds, nextIds)
                    selectedServices = nextServices
                    syncServiceHiddenInput()
                    renderSelectedServices()
                    if (doctorSearch) doctorSearch.disabled = selectedServices.length === 0
                    if (doctorPickerBtn) doctorPickerBtn.disabled = doctorSearch ? doctorSearch.disabled : true
                    if (changed) {
                        if (!selectedServices.length && doctorSearch) doctorSearch.value = ''
                        setDoctorSelection(null)
                    }
                    closeSelectorModal()
                    return
                }
                if (selectorState.type === 'doctor') {
                    if (!selectorState.activeItem) return
                    setDoctorSelection(selectorState.activeItem)
                    closeSelectorModal()
                }
            })
        }

        if (selectorCloseBtn) selectorCloseBtn.addEventListener('click', closeSelectorModal)
        if (selectorCancelBtn) selectorCancelBtn.addEventListener('click', closeSelectorModal)
        if (selectorOverlay) {
            selectorOverlay.addEventListener('click', function (e) {
                if (e.target === selectorOverlay) closeSelectorModal()
            })
        }

        function buildAvailableDaySet() {
            doctorAvailableDaySet = {}
            doctorSchedules.forEach(function (s) {
                var dayKey = normalizeDayKey(s && s.day_of_week != null ? s.day_of_week : '')
                if (dayKey) doctorAvailableDaySet[dayKey] = true
            })
        }

        function formatDateIso(d) {
            var yyyy = String(d.getFullYear())
            var mm = String(d.getMonth() + 1).padStart(2, '0')
            var dd = String(d.getDate()).padStart(2, '0')
            return yyyy + '-' + mm + '-' + dd
        }

        function formatDateLabel(d) {
            var keys = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
            return formatDateIso(d) + ' (' + (keys[d.getDay()] || '') + ')'
        }

        var datePickerMonth = (function () {
            var now = new Date()
            return new Date(now.getFullYear(), now.getMonth(), 1)
        })()

        function isoFromDate(d) {
            var yr = d.getFullYear()
            var mo = String(d.getMonth() + 1).padStart(2, '0')
            var da = String(d.getDate()).padStart(2, '0')
            return yr + '-' + mo + '-' + da
        }

        function friendlyDateLabelFromIso(iso) {
            var datePart = String(iso || '').slice(0, 10)
            if (!/^\d{4}-\d{2}-\d{2}$/.test(datePart)) return datePart || 'Select a date'
            var d = new Date(datePart + 'T00:00:00')
            if (isNaN(d.getTime())) return datePart
            return d.toLocaleDateString(undefined, { weekday: 'short', month: 'short', day: '2-digit', year: 'numeric' })
        }

        function syncScheduleUI() {
            if (!scheduleTrigger) return
            var allowedKeys = doctorAvailableDaySet && Object.keys(doctorAvailableDaySet).length ? doctorAvailableDaySet : null
            var doctorId = doctorSelect && doctorSelect.value ? String(doctorSelect.value) : ''
            var enabled = !!doctorId && !!allowedKeys
            scheduleTrigger.disabled = !enabled
            scheduleTrigger.textContent = enabled ? 'Select Schedule' : 'Select a doctor first'
        }

        function renderScheduleDatePicker() {
            if (!scheduleDateGrid || !scheduleMonthLabel) return

            var allowedKeys = doctorAvailableDaySet && Object.keys(doctorAvailableDaySet).length ? doctorAvailableDaySet : null
            var doctorId = doctorSelect && doctorSelect.value ? String(doctorSelect.value) : ''
            if (!doctorId) {
                scheduleMonthLabel.textContent = ''
                scheduleDateGrid.innerHTML = '<div class="col-span-7 text-[0.75rem] text-slate-500 py-2">Select a doctor first.</div>'
                return
            }
            if (!allowedKeys) {
                scheduleMonthLabel.textContent = ''
                scheduleDateGrid.innerHTML = '<div class="col-span-7 text-[0.75rem] text-slate-500 py-2">No available schedule days.</div>'
                return
            }

            var year = datePickerMonth.getFullYear()
            var month = datePickerMonth.getMonth()
            var first = new Date(year, month, 1)
            var firstDow = first.getDay()
            var daysIn = new Date(year, month + 1, 0).getDate()

            scheduleMonthLabel.textContent = first.toLocaleDateString(undefined, { month: 'long', year: 'numeric' })

            var today = new Date()
            today.setHours(0, 0, 0, 0)

            var selectedIso = dateInput && dateInput.value ? String(dateInput.value).slice(0, 10) : ''
            var keys = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']

            var cells = []
            for (var i = 0; i < firstDow; i++) cells.push('')
            for (var day = 1; day <= daysIn; day++) {
                var d = new Date(year, month, day)
                var iso = isoFromDate(d)
                var dayKey = keys[d.getDay()] || ''
                var allowed = !!allowedKeys[dayKey]
                var notPast = d.getTime() > today.getTime()
                var enabled = allowed && notPast
                var selected = selectedIso && selectedIso === iso
                var base =
                    'relative w-full aspect-square rounded-lg text-[0.75rem] font-semibold border transition-colors flex items-center justify-center'
                var cls = base + ' ' + (enabled
                    ? (selected ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50')
                    : 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed')
                var count = doctorMonthAppointments[iso] || 0
                var showBadge = count > 0 && d.getTime() >= today.getTime()
                var badge = showBadge ? '<span class="absolute -top-1 -right-1 min-w-[14px] h-[14px] bg-red-500 text-white text-[0.5rem] leading-[14px] font-bold rounded-full px-0.5 text-center">' + count + '</span>' : ''
                cells.push('<button type="button" class="' + cls + '" data-date="' + iso + '"' + (enabled ? '' : ' disabled') + '>' + day + badge + '</button>')
            }

            var total = Math.ceil(cells.length / 7) * 7
            while (cells.length < total) cells.push('')

            scheduleDateGrid.innerHTML = cells.map(function (html) {
                return html ? html : '<div></div>'
            }).join('')
        }

        function hhmmFromMinutes(mins) {
            var m = parseInt(mins, 10)
            if (isNaN(m)) return ''
            var hh = Math.floor(m / 60)
            var mm = m % 60
            var hhStr = String(hh).padStart(2, '0')
            var mmStr = String(mm).padStart(2, '0')
            return hhStr + ':' + mmStr
        }

        function renderTimeSlots() {
            // Keep as-is for backward compatibility; delegates to renderScheduleTimeSlotsInModal
        }

        function renderScheduleTimeSlotsInModal() {
            if (!scheduleTimeBody) return

            if (!doctorSelect || !doctorSelect.value) {
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'Select a doctor first.'
                scheduleTimeBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a doctor to load time slots.</div>'
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                return
            }
            if (!dateInput || !dateInput.value) {
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'Select a date to view time slots.'
                scheduleTimeBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a date to load time slots.</div>'
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                return
            }
            if (!doctorSchedules.length) {
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'No schedules found.'
                scheduleTimeBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No schedules found for this doctor.</div>'
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                return
            }

            var dayKey = dayKeyFromDate(dateInput.value)
            if (!dayKey) {
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'Invalid date.'
                scheduleTimeBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Invalid date selected.</div>'
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                return
            }
            if (doctorAvailableDaySet && Object.keys(doctorAvailableDaySet).length && !doctorAvailableDaySet[dayKey]) {
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'Doctor not available on this day.'
                scheduleTimeBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Doctor is not available on this day.</div>'
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                return
            }

            var todayIso = localDateIso()
            var isToday = String(dateInput.value) === todayIso
            var daySchedules = doctorSchedules.filter(function (s) {
                if (!s) return false
                if (normalizeDayKey(s.day_of_week) !== normalizeDayKey(dayKey)) return false
                if (isToday && s.is_available === false) return false
                return true
            })

            if (!daySchedules.length) {
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'No available slots.'
                scheduleTimeBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Doctor has no available slots on this day.</div>'
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                return
            }

            daySchedules.sort(function (a, b) {
                var sa = minutesFromHHMM(String(a.start_time || ''))
                var sb = minutesFromHHMM(String(b.start_time || ''))
                if (isNaN(sa) || isNaN(sb)) return 0
                return sa - sb
            })

            var intervals = []
            daySchedules.forEach(function (s) {
                var st = minutesFromHHMM(String(s.start_time || ''))
                var en = minutesFromHHMM(String(s.end_time || ''))
                if (isNaN(st) || isNaN(en) || en <= st) return
                intervals.push({ start: st, end: en })
            })
            intervals.sort(function (a, b) { return a.start - b.start })
            var merged = []
            intervals.forEach(function (i) {
                var last = merged.length ? merged[merged.length - 1] : null
                if (!last) {
                    merged.push({ start: i.start, end: i.end })
                    return
                }
                if (i.start <= last.end) {
                    last.end = Math.max(last.end, i.end)
                    return
                }
                merged.push({ start: i.start, end: i.end })
            })

            var appts = Array.isArray(doctorAppointments) ? doctorAppointments : []
            if (window.console) console.log('[renderScheduleTimeSlotsInModal] doctorAppointments count:', appts.length, 'dateInput.value:', dateInput ? dateInput.value : 'null')
            var bookedSet = {}
            appts.forEach(function (a) {
                if (!a || !a.appointment_datetime) return
                if (String(a.status || '').toLowerCase() === 'cancelled') return
                if (a.appointment_type && String(a.appointment_type) !== 'scheduled') return
                var d = new Date(a.appointment_datetime)
                if (isNaN(d.getTime())) return
                var y = d.getFullYear()
                var m = String(d.getMonth() + 1).padStart(2, '0')
                var dd = String(d.getDate()).padStart(2, '0')
                var datePart = y + '-' + m + '-' + dd
                var timePart = String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0')
                if (datePart !== dateInput.value) return
                bookedSet[timePart] = true
            })
            if (window.console) console.log('[renderScheduleTimeSlotsInModal] bookedSet keys:', Object.keys(bookedSet))

            var slots = []
            merged.forEach(function (block) {
                for (var m = block.start; m + slotMinutes <= block.end; m += slotMinutes) {
                    slots.push({ start: m, end: m + slotMinutes })
                }
            })

            if (!slots.length) {
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'No time slots available.'
                scheduleTimeBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No time slots available for this day.</div>'
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                return
            }

            var availableCount = slots.filter(function (slot) {
                var startHHMM = hhmmFromMinutes(slot.start)
                return !bookedSet[startHHMM]
            }).length
            if (scheduleTimeHint) scheduleTimeHint.textContent = String(availableCount) + ' available slot' + (availableCount !== 1 ? 's' : '') + ' · Click a slot to select.'

            var container = document.createElement('div')
            container.className = 'grid grid-cols-2 gap-2'
            // Column-major layout: fills top-to-bottom then left-to-right (1 6 / 2 7 / 3 8 / ...)
            container.style.gridAutoFlow = 'column'
            var gridRows = Math.ceil(slots.length / 2)
            if (gridRows > 1) container.style.gridTemplateRows = 'repeat(' + gridRows + ', auto)'

            slots.forEach(function (slot) {
                var startHHMM = hhmmFromMinutes(slot.start)
                var endHHMM = hhmmFromMinutes(slot.end)
                var isBooked = !!bookedSet[startHHMM]
                var isSelected = String(selectedSlotStart || '') === startHHMM
                var label = formatTime12h(startHHMM) + '–' + formatTime12h(endHHMM)

                var btn = document.createElement('button')
                btn.type = 'button'
                btn.className =
                    'w-full px-3 py-2 rounded-xl text-[0.75rem] font-semibold border transition-colors flex items-center justify-between ' +
                    (isBooked
                        ? 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed'
                        : (isSelected
                            ? 'border-green-600 bg-green-600 text-white'
                            : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'))
                btn.disabled = !!isBooked
                btn.textContent = label + (isBooked ? ' · Booked' : '')
                btn.addEventListener('click', function () {
                    selectedSlotStart = startHHMM
                    if (timeInput) timeInput.value = startHHMM
                    if (scheduleTimeHint) scheduleTimeHint.textContent = 'Selected: ' + formatTime12h(startHHMM)
                    if (scheduleSetBtn) scheduleSetBtn.disabled = false
                    renderScheduleTimeSlotsInModal()
                })
                container.appendChild(btn)
            })

            scheduleTimeBody.innerHTML = ''
            scheduleTimeBody.appendChild(container)
        }

        document.addEventListener('click', function (e) {
            if (scheduleOverlay && !scheduleOverlay.classList.contains('hidden')) {
                if (e.target === scheduleOverlay) {
                    closeScheduleModal()
                }
            }
        })

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSelectorModal()
                closeScheduleModal()
            }
        })

        function loadDoctorSchedulesAndAvailability(doctorId, dateStr) {
            if (!doctorId || typeof apiFetch !== 'function') return
            clearAvailability()
            apiFetch("{{ url('/api/doctor-schedules') }}?doctor_id=" + encodeURIComponent(doctorId) + "&per_page=100", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) {
                        var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to load doctor schedules.'
                        if (result.status === 401) msg = 'Session expired. Please log in again.'
                        if (result.status === 403) msg = 'Forbidden (403). Your account does not have permission to view this doctor’s schedules.'
                        showBookAppointmentError(msg)
                        buildAvailableDaySet()
                        syncScheduleUI()
                        renderScheduleDatePicker()
                        renderScheduleTimeSlotsInModal()
                        return
                    }

                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    doctorSchedules = raw || []
                    buildAvailableDaySet()
                    syncScheduleUI()
                    renderScheduleDatePicker()
                    if (dateStr) {
                        loadDoctorAppointments(doctorId, dateStr)
                    } else {
                        renderScheduleTimeSlotsInModal()
                    }
                    loadMonthAppointments(doctorId)
                })
                .catch(function () {
                    showBookAppointmentError('Network error while loading doctor schedules.')
                    buildAvailableDaySet()
                    syncScheduleUI()
                    renderScheduleDatePicker()
                    renderScheduleTimeSlotsInModal()
                    if (doctorId) loadMonthAppointments(doctorId)
                })
        }

        function loadDoctorAppointments(doctorId, dateStr) {
            if (!doctorId || !dateStr || typeof apiFetch !== 'function') return
            apiFetch("{{ url('/api/appointments') }}?doctor_id=" + encodeURIComponent(doctorId) + "&start_date=" + encodeURIComponent(dateStr) + "&end_date=" + encodeURIComponent(dateStr) + "&per_page=30", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    if (window.console) {
                        console.log('[loadDoctorAppointments] raw count:', raw ? raw.length : 0)
                        if (raw && raw.length) {
                            raw.forEach(function (a, i) {
                                console.log('[loadDoctorAppointments] appointment ' + i + ':', JSON.stringify({ id: a.appointment_id, dt: a.appointment_datetime, type: a.appointment_type, status: a.status }))
                            })
                        }
                    }
                    doctorAppointments = raw || []
                    renderScheduleTimeSlotsInModal()
                })
                .catch(function () {
                    if (window.console) console.log('[loadDoctorAppointments] fetch failed, resetting')
                    doctorAppointments = []
                    renderScheduleTimeSlotsInModal()
                })
        }

        function loadMonthAppointments(doctorId) {
            if (!doctorId || typeof apiFetch !== 'function') return
            var y = datePickerMonth.getFullYear()
            var m = datePickerMonth.getMonth()
            var startDate = y + '-' + String(m + 1).padStart(2, '0') + '-01'
            var lastDay = new Date(y, m + 1, 0).getDate()
            var endDate = y + '-' + String(m + 1).padStart(2, '0') + '-' + String(lastDay).padStart(2, '0')
            apiFetch("{{ url('/api/appointments') }}?doctor_id=" + encodeURIComponent(doctorId) + "&appointment_type=scheduled&start_date=" + encodeURIComponent(startDate) + "&end_date=" + encodeURIComponent(endDate) + "&per_page=200", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var map = {}
                    raw.forEach(function (a) {
                        if (!a || !a.appointment_datetime) return
                        if (String(a.status || '').toLowerCase() === 'cancelled') return
                        if (String(a.appointment_type || '').toLowerCase() !== 'scheduled') return
                        var d = new Date(a.appointment_datetime)
                        if (!isNaN(d.getTime())) {
                            var datePart = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                            map[datePart] = (map[datePart] || 0) + 1
                        }
                    })
                    doctorMonthAppointments = map
                    renderScheduleDatePicker()
                })
                .catch(function () {
                    doctorMonthAppointments = {}
                    renderScheduleDatePicker()
                })
        }

        function loadServicesAndDoctors() {
            if (typeof apiFetch !== 'function') return
            if (!servicesLoaded && !servicesLoading) {
                servicesLoading = true
                apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                    .then(function (response) { return readResponse(response) })
                    .then(function (result) {
                        if (!result.ok) return
                        var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                        var allowedServiceNames = [
                            'general surgeon',
                            'obstetrician-gynecologist',
                            'obstetrician - gynecologist',
                            'internal medicine'
                        ]
                        services = (raw || []).filter(function (s) {
                            var name = normalizeText(s && s.service_name ? s.service_name : '')
                            return allowedServiceNames.indexOf(name) !== -1
                        })
                        servicesLoaded = true
                        if (serviceSearch && serviceSearch.value) {
                            renderServiceResults()
                        }
                    })
                    .catch(function () {})
                    .finally(function () {
                        servicesLoading = false
                    })
            }

            doctorsLoading = true
            apiFetch("{{ url('/api/doctors') }}?per_page=15", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    doctors = raw || []
                    doctorsLoaded = true
                    if (doctorSearch && doctorSearch.value) {
                        renderDoctorResults()
                    }
                })
                .catch(function () {})
                .finally(function () {
                    doctorsLoading = false
                })
        }

        function bindSelectorTrigger(inputEl, buttonEl, type) {
            function openCurrentSelector() {
                showBookAppointmentError('')
                showBookAppointmentSuccess('')
                if (type === 'doctor' && inputEl && inputEl.disabled) return
                openSelectorModal(type)
            }
            if (inputEl) {
                inputEl.addEventListener('click', openCurrentSelector)
                inputEl.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault()
                        openCurrentSelector()
                    }
                })
            }
            if (buttonEl) {
                buttonEl.addEventListener('click', openCurrentSelector)
            }
        }

        bindSelectorTrigger(patientSearch, patientPickerBtn, 'patient')
        bindSelectorTrigger(serviceSearch, servicePickerBtn, 'service')
        bindSelectorTrigger(doctorSearch, doctorPickerBtn, 'doctor')

        // Schedule trigger → open modal
        if (scheduleTrigger) {
            scheduleTrigger.addEventListener('click', function () {
                if (scheduleTrigger.disabled) return
                openScheduleModal()
            })
        }

        // Schedule close/cancel
        if (scheduleCloseBtn) scheduleCloseBtn.addEventListener('click', closeScheduleModal)
        if (scheduleCancelBtn) scheduleCancelBtn.addEventListener('click', closeScheduleModal)

        // Schedule Set button
        if (scheduleSetBtn) {
            scheduleSetBtn.addEventListener('click', function () {
                if (scheduleSetBtn.disabled) return
                var date = dateInput && dateInput.value ? String(dateInput.value).slice(0, 10) : ''
                var time = selectedSlotStart
                if (date && time) {
                    dateInput.value = date
                    timeInput.value = time
                    updateScheduleCard()
                    closeScheduleModal()
                    updateSelectorConfirmState()
                }
            })
        }

        // Schedule clear
        if (scheduleClearBtn) {
            scheduleClearBtn.addEventListener('click', function () {
                dateInput.value = ''
                timeInput.value = ''
                selectedSlotStart = null
                updateScheduleCard()
                updateSelectorConfirmState()
            })
        }

        // Date prev/next in schedule modal
        if (scheduleDatePrev) {
            scheduleDatePrev.addEventListener('click', function () {
                datePickerMonth = new Date(datePickerMonth.getFullYear(), datePickerMonth.getMonth() - 1, 1)
                renderScheduleDatePicker()
                if (doctorSelect && doctorSelect.value) loadMonthAppointments(doctorSelect.value)
            })
        }
        if (scheduleDateNext) {
            scheduleDateNext.addEventListener('click', function () {
                datePickerMonth = new Date(datePickerMonth.getFullYear(), datePickerMonth.getMonth() + 1, 1)
                renderScheduleDatePicker()
                if (doctorSelect && doctorSelect.value) loadMonthAppointments(doctorSelect.value)
            })
        }

        // Calendar date click (via event delegation on scheduleDateGrid)
        if (scheduleDateGrid) {
            scheduleDateGrid.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-date]') : null
                if (!btn || btn.disabled) return
                var dateIso = btn.getAttribute('data-date') || ''
                if (!dateIso) return
                dateInput.value = dateIso
                selectedSlotStart = null
                timeInput.value = ''
                if (scheduleSetBtn) scheduleSetBtn.disabled = true
                if (scheduleTimeHint) scheduleTimeHint.textContent = 'Loading appointments…'
                renderScheduleDatePicker()
                loadDoctorAppointments(doctorSelect ? doctorSelect.value : '', dateIso)
            })
        }

        var typeInput = document.getElementById('reception_appointment_type')
        var typeScheduledBtn = document.getElementById('receptionApptTypeScheduledBtn')
        var typeWalkInBtn = document.getElementById('receptionApptTypeWalkInBtn')

        function setTypeButtonState(btn, isActive) {
            if (!btn) return
            btn.classList.toggle('bg-green-500', isActive)
            btn.classList.toggle('text-slate-900', isActive)
            btn.classList.toggle('shadow-sm', isActive)
            btn.classList.toggle('border', isActive)
            btn.classList.toggle('border-slate-200', isActive)
            btn.classList.toggle('bg-transparent', !isActive)
            btn.classList.toggle('text-slate-600', !isActive)
        }

        function syncTypeToggleUI() {
            if (typeScheduledBtn) typeScheduledBtn.textContent = 'Scheduled'
            if (typeWalkInBtn) typeWalkInBtn.textContent = 'Walk-in'
            var type = typeInput && typeInput.value ? typeInput.value : 'scheduled'
            setTypeButtonState(typeScheduledBtn, type === 'scheduled')
            setTypeButtonState(typeWalkInBtn, type === 'walk_in')
        }

        function setAppointmentType(nextType) {
            var type = nextType === 'walk_in' ? 'walk_in' : 'scheduled'
            if (typeInput) typeInput.value = type
            showBookAppointmentError('')
            showBookAppointmentSuccess('')
            applyAppointmentTypeUI()
            syncTypeToggleUI()
        }

        function applyAppointmentTypeUI() {
            if (typeInput) typeInput.value = 'scheduled'
            var isWalkIn = false
            if (scheduleWrap) scheduleWrap.classList.toggle('hidden', isWalkIn)
            if (dateInput) dateInput.required = false
            if (timeInput) timeInput.required = !isWalkIn
            if (isWalkIn) {
                if (dateInput) dateInput.value = ''
                if (timeInput) timeInput.value = ''
                selectedSlotStart = null
                updateScheduleCard()
            }
        }
        if (typeScheduledBtn) {
            typeScheduledBtn.addEventListener('click', function () { setAppointmentType('scheduled') })
        }
        if (typeWalkInBtn) {
            typeWalkInBtn.addEventListener('click', function () { setAppointmentType('walk_in') })
        }

        document.addEventListener('click', function (e) {
            var target = e && e.target ? e.target : null

            if (patientResults && !patientResults.classList.contains('hidden')) {
                if (!(patientResults.contains(target) || (patientSearch && patientSearch.contains(target)))) {
                    patientResults.classList.add('hidden')
                }
            }
            if (serviceResults && !serviceResults.classList.contains('hidden')) {
                if (!(serviceResults.contains(target) || (serviceSearch && serviceSearch.contains(target)))) {
                    serviceResults.classList.add('hidden')
                }
            }
            if (doctorResults && !doctorResults.classList.contains('hidden')) {
                if (!(doctorResults.contains(target) || (doctorSearch && doctorSearch.contains(target)))) {
                    doctorResults.classList.add('hidden')
                }
            }
        })

        loadServicesAndDoctors()
        if (dateInput) {
            var today = new Date()
            var yyyy = String(today.getFullYear())
            var mm = String(today.getMonth() + 1).padStart(2, '0')
            var dd = String(today.getDate()).padStart(2, '0')
            dateInput.min = yyyy + '-' + mm + '-' + dd
        }
        if (typeInput && !typeInput.value) typeInput.value = 'scheduled'
        applyAppointmentTypeUI()
        syncTypeToggleUI()
        syncScheduleUI()
        updateScheduleCard()
        syncSelectionTriggers()

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault()

                showBookAppointmentError('')
                showBookAppointmentSuccess('')
                setBookSubmitting(true)

                var patientInput = document.getElementById('reception_appointment_patient_id')
                var doctorInput = document.getElementById('reception_appointment_doctor_id')
                var serviceInput = document.getElementById('reception_appointment_service_id')
                var dateInput = document.getElementById('reception_appointment_date')
                var timeInput = document.getElementById('reception_appointment_time')
                var typeInput = document.getElementById('reception_appointment_type')
                var reasonInput = document.getElementById('reception_appointment_reason')

                var patientId = patientInput ? parseInt(patientInput.value, 10) : 0
                var doctorId = doctorInput ? parseInt(doctorInput.value, 10) : 0
                var serviceIds = selectedServiceIds()
                var date = (dateInput && dateInput.value ? dateInput.value : '')
                var time = timeInput ? timeInput.value : ''
                var type = 'scheduled'
                var reason = reasonInput ? reasonInput.value : ''

                if (!patientId || !doctorId || !serviceIds.length) {
                    showBookAppointmentError('Patient, service, and doctor are required.')
                    setBookSubmitting(false)
                    return
                }

                if (type !== 'walk_in') {
                    if (!date || !time) {
                        showBookAppointmentError('Date and time are required for scheduled appointments.')
                        setBookSubmitting(false)
                        return
                    }
                }

                if (typeof apiFetch !== 'function') {
                    showBookAppointmentError('API client is not available.')
                    setBookSubmitting(false)
                    return
                }

                var body = {
                    patient_id: patientId,
                    doctor_id: doctorId,
                    service_ids: serviceIds,
                    appointment_type: type,
                    status: 'confirmed'
                }

                if (type !== 'walk_in') {
                    body.appointment_datetime = date + ' ' + time
                }
                if (reason) {
                    body.reason_for_visit = reason
                }

                var reviewDetails = {
                    'Patient': selectedPatient ? patientDisplayName(selectedPatient) : ('#' + String(patientId)),
                    'Doctor': selectedDoctor ? doctorLabel(selectedDoctor) : ('#' + String(doctorId)),
                    'Services': (selectedServices || []).map(function (s) { return s && s.service_name ? s.service_name : '' }).filter(Boolean).join(', ') || 'N/A',
                    'Date': date || 'N/A',
                    'Time': time ? formatTime12h(time) : 'N/A',
                    'Reason': reason || 'N/A'
                }

                function submitAppointment() {
                    apiFetch("{{ url('/api/appointments') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(body)
                    })
                        .then(function (response) { return readResponse(response) })
                        .then(function (result) {
                            if (!result.ok) {
                                var message = 'Failed to book appointment.'
                                if (result.data && result.data.message) {
                                    message = result.data.message
                                }
                                showBookAppointmentError(message)
                                return
                            }

                            var createdId = result.data && result.data.appointment_id ? parseInt(result.data.appointment_id, 10) : 0
                            if (!createdId || isNaN(createdId)) {
                                showBookAppointmentError('Appointment was not saved. Please refresh and try again.')
                                return
                            }

                            showBookAppointmentSuccess('Appointment has been created successfully.')
                            if (patientSearch) patientSearch.value = ''
                            if (serviceSearch) serviceSearch.value = ''
                            if (doctorSearch) doctorSearch.value = ''
                            setPatientSelection(null)
                            selectedServices = []
                            syncServiceHiddenInput()
                            renderSelectedServices()
                            setDoctorSelection(null)
                            if (dateInput) dateInput.value = ''
                            if (timeInput) timeInput.value = ''
                            if (typeInput) typeInput.value = 'scheduled'
                            if (reasonInput) reasonInput.value = ''
                            updateScheduleCard()
                            syncScheduleUI()
                            applyAppointmentTypeUI()
                            syncTypeToggleUI()
                            loadManageAppointments()
                        })
                        .catch(function () {
                            showBookAppointmentError('Network error while booking appointment.')
                        })
                        .finally(function () {
                            setBookSubmitting(false)
                        })
                }

                openReview(reviewDetails)
                    .then(function (reviewOk) {
                        if (!reviewOk) {
                            setBookSubmitting(false)
                            return
                        }
                        return apiFetch("{{ url('/api/appointments/active-exists') }}?patient_id=" + encodeURIComponent(String(patientId)), { method: 'GET' })
                            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                            .then(function (res) {
                                var exists = !!(res && res.ok && res.data && res.data.exists)
                                if (!exists) {
                                    submitAppointment()
                                    return
                                }
                                confirmAction('This patient has an active appointment already, would you still like to book this appointment?', { okText: 'Yes', delayMs: 3000 })
                                    .then(function (confirmed) {
                                        if (!confirmed) {
                                            setBookSubmitting(false)
                                            return
                                        }
                                        submitAppointment()
                                    })
                            })
                            .catch(function () {
                                submitAppointment()
                            })
                    })
            })
        }

        var manageError = document.getElementById('receptionManageAppointmentError')
        var manageSuccess = document.getElementById('receptionManageAppointmentSuccess')
        var manageResult = document.getElementById('receptionManageAppointmentResult')
        var manageSearchInput = document.getElementById('receptionManageApptSearch')
        var manageServiceSearch = document.getElementById('receptionManageServiceSearch')
        var manageServiceId = document.getElementById('receptionManageServiceId')
        var manageServiceResults = document.getElementById('receptionManageServiceResults')
        var manageSortSelect = document.getElementById('receptionManageSort')
        var manageStatusSelect = document.getElementById('receptionManageStatus')
        var manageTableBody = document.getElementById('receptionManageAppointmentTableBody')
        var manageMeta = document.getElementById('receptionManageAppointmentMeta')
        var manageRefreshBtn = document.getElementById('recBookRefreshBtn')
        var manageTodayOnlyBtn = document.getElementById('receptionManageTodayOnlyBtn')
        var manageShowTodayOnly = false
        var manageSearchTimer = null
        var manageServices = []
        var manageServicesLoaded = false
        var manageServicesLoading = false
        var manageCurrentPage = 1
        var managePerPage = 10
        var manageVisibleCount = 5
        var manageLastPage = 1
        var manageTotal = 0
        var manageFilterDate = ''
        var manageDateHeader = document.getElementById('receptionManageDateHeader')
        var manageClearFilterBtn = document.getElementById('receptionManageClearFilterBtn')

        var manageHistOverlay = document.getElementById('managePatientHistoryOverlay')
        var manageHistClose = document.getElementById('managePatientHistClose')
        var manageHistSubtitle = document.getElementById('managePatientHistSubtitle')
        var manageHistBody = document.getElementById('managePatientHistBody')
        var manageHistDetailBody = document.getElementById('managePatientHistDetailBody')
        var manageHistDate = document.getElementById('managePatientHistDate')
        var manageHistStatus = document.getElementById('managePatientHistStatus')
        var manageHistType = document.getElementById('managePatientHistType')
        var manageHistStatusSelect = document.getElementById('managePatientHistStatusSelect')
        var manageHistUpdateBtn = document.getElementById('managePatientHistUpdateBtn')
        var manageHistoryPatientId = ''
        var manageHistoryAppointments = []

        var manageCalMonth = new Date()
        manageCalMonth.setDate(1)
        var manageMonthAppointments = {}
        var manageCalMonthLabel = document.getElementById('receptionManageCalMonthLabel')
        var manageCalDateGrid = document.getElementById('receptionManageCalDateGrid')
        var manageCalDatePrev = document.getElementById('receptionManageCalDatePrev')
        var manageCalDateNext = document.getElementById('receptionManageCalDateNext')

        function renderManageCalendar() {
            if (!manageCalDateGrid || !manageCalMonthLabel) return

            var year = manageCalMonth.getFullYear()
            var month = manageCalMonth.getMonth()
            var first = new Date(year, month, 1)
            var firstDow = first.getDay()
            var daysIn = new Date(year, month + 1, 0).getDate()

            manageCalMonthLabel.textContent = first.toLocaleDateString(undefined, { month: 'long', year: 'numeric' })

            var today = new Date()
            today.setHours(0, 0, 0, 0)

            var selectedIso = ''
            var cells = []
            for (var i = 0; i < firstDow; i++) cells.push('')
            for (var day = 1; day <= daysIn; day++) {
                var d = new Date(year, month, day)
                var iso = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                var notPast = d.getTime() >= today.getTime()
                var selected = selectedIso && selectedIso === iso
                var base = 'relative w-full rounded-lg text-[0.75rem] font-semibold border transition-colors flex items-center justify-center'
                var cls = base + ' ' + (notPast
                    ? (selected ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50')
                    : 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed')
                var count = manageMonthAppointments[iso] || 0
                var showBadge = count > 0 && notPast
                var badge = showBadge ? '<span class="absolute -top-1 -right-1 min-w-[14px] h-[14px] bg-red-500 text-white text-[0.5rem] leading-[14px] font-bold rounded-full px-0.5 text-center">' + count + '</span>' : ''
                cells.push('<button type="button" class="' + cls + '" data-date="' + iso + '"' + (notPast ? '' : ' disabled') + '>' + day + badge + '</button>')
            }
            var total = Math.ceil(cells.length / 7) * 7
            while (cells.length < total) cells.push('')
            manageCalDateGrid.innerHTML = cells.map(function (html) {
                return html ? html : '<div></div>'
            }).join('')
        }

        function loadManageMonthAppointments() {
            if (typeof apiFetch !== 'function') return
            var y = manageCalMonth.getFullYear()
            var m = manageCalMonth.getMonth()
            var startDate = y + '-' + String(m + 1).padStart(2, '0') + '-01'
            var lastDay = new Date(y, m + 1, 0).getDate()
            var endDate = y + '-' + String(m + 1).padStart(2, '0') + '-' + String(lastDay).padStart(2, '0')
            apiFetch("{{ url('/api/appointments') }}?appointment_type=scheduled&start_date=" + encodeURIComponent(startDate) + "&end_date=" + encodeURIComponent(endDate) + "&per_page=200", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var map = {}
                    raw.forEach(function (a) {
                        if (!a || !a.appointment_datetime) return
                        if (String(a.status || '').toLowerCase() === 'cancelled') return
                        if (String(a.appointment_type || '').toLowerCase() !== 'scheduled') return
                        var d = new Date(a.appointment_datetime)
                        if (!isNaN(d.getTime())) {
                            var datePart = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                            map[datePart] = (map[datePart] || 0) + 1
                        }
                    })
                    manageMonthAppointments = map
                    renderManageCalendar()
                })
                .catch(function () {
                    manageMonthAppointments = {}
                    renderManageCalendar()
                })
        }

        var confirmOverlay = document.getElementById('receptionBookConfirmOverlay')
        var confirmMessage = document.getElementById('receptionBookConfirmMessage')
        var confirmOk = document.getElementById('receptionBookConfirmOk')
        var confirmCancel = document.getElementById('receptionBookConfirmCancel')
        var reviewOverlay = document.getElementById('receptionBookReviewOverlay')
        var reviewContent = document.getElementById('receptionBookReviewContent')
        var reviewOk = document.getElementById('receptionBookReviewOk')
        var reviewCancel = document.getElementById('receptionBookReviewCancel')
        var confirmResolver = null
        var reviewResolver = null
        var confirmDelayTimer = null
        var confirmOkDefaultHtml = confirmOk ? confirmOk.innerHTML : ''

        function setManageSubmitting(isSubmitting) {
            var disabled = !!isSubmitting
            if (manageSortSelect) manageSortSelect.disabled = disabled
            if (manageStatusSelect) manageStatusSelect.disabled = disabled
            if (manageRefreshBtn) manageRefreshBtn.disabled = disabled
            if (manageTodayOnlyBtn) manageTodayOnlyBtn.disabled = disabled
        }
function updateManageTodayButton() {
    if (!manageTodayOnlyBtn) return
    if (manageShowTodayOnly) {
        manageTodayOnlyBtn.textContent = 'Showing today only'
        manageTodayOnlyBtn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
        manageTodayOnlyBtn.classList.add('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-green-700', 'hover:border-green-700')
    } else {
        manageTodayOnlyBtn.textContent = 'Show today only'
        manageTodayOnlyBtn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
        manageTodayOnlyBtn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-green-700', 'hover:border-green-700')
    }
}

        function confirmAction(message, options) {
            return new Promise(function (resolve) {
                if (!confirmOverlay || !confirmMessage || !confirmOk || !confirmCancel) {
                    resolve(window.confirm(message || 'Are you sure?'))
                    return
                }
                if (confirmDelayTimer) {
                    clearTimeout(confirmDelayTimer)
                    confirmDelayTimer = null
                }
                var opts = (options && typeof options === 'object') ? options : {}
                var okText = String(opts.okText || 'Confirm')
                var delayMs = opts.delayMs != null ? parseInt(opts.delayMs, 10) : 0
                if (isNaN(delayMs) || delayMs < 0) delayMs = 0

                confirmMessage.textContent = message || 'Are you sure?'
                confirmOk.disabled = delayMs > 0
                if (delayMs > 0) {
                    confirmOk.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>' + okText + '</span></span>'
                    confirmDelayTimer = setTimeout(function () {
                        confirmOk.disabled = false
                        confirmOk.textContent = okText
                        confirmDelayTimer = null
                    }, delayMs)
                } else {
                    confirmOk.textContent = okText
                }
                confirmResolver = resolve
                confirmOverlay.classList.remove('hidden')
                confirmOverlay.classList.add('flex')
            })
        }

        function closeConfirm(result) {
            if (confirmOverlay) {
                confirmOverlay.classList.add('hidden')
                confirmOverlay.classList.remove('flex')
            }
            if (confirmDelayTimer) {
                clearTimeout(confirmDelayTimer)
                confirmDelayTimer = null
            }
            if (confirmOk) {
                confirmOk.disabled = false
                confirmOk.innerHTML = confirmOkDefaultHtml || 'Confirm'
            }
            var resolver = confirmResolver
            confirmResolver = null
            if (typeof resolver === 'function') {
                resolver(!!result)
            }
        }

        if (confirmOk) confirmOk.addEventListener('click', function () { closeConfirm(true) })
        if (confirmCancel) confirmCancel.addEventListener('click', function () { closeConfirm(false) })
        if (confirmOverlay) {
            confirmOverlay.addEventListener('click', function (e) {
                if (e.target === confirmOverlay) closeConfirm(false)
            })
        }

        function closeReview(result) {
            if (reviewOverlay) {
                reviewOverlay.classList.add('hidden')
                reviewOverlay.classList.remove('flex')
            }
            var resolver = reviewResolver
            reviewResolver = null
            if (typeof resolver === 'function') resolver(!!result)
        }

        function openReview(details) {
            return new Promise(function (resolve) {
                if (!reviewOverlay || !reviewContent || !reviewOk || !reviewCancel) {
                    resolve(window.confirm('Please review appointment details before submitting.'))
                    return
                }
                function esc(input) {
                    return String(input == null ? '' : input)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;')
                }
                var source = details && typeof details === 'object' ? details : {}
                var rows = Object.keys(source).map(function (key) {
                    return '<li><strong class="font-semibold text-slate-800">' + esc(key) + ':</strong> ' + esc(source[key]) + '</li>'
                })
                reviewContent.innerHTML = '<ul class="space-y-1">' + rows.join('') + '</ul>'
                reviewResolver = resolve
                reviewOverlay.classList.remove('hidden')
                reviewOverlay.classList.add('flex')
            })
        }

        if (reviewOk) reviewOk.addEventListener('click', function () { closeReview(true) })
        if (reviewCancel) reviewCancel.addEventListener('click', function () { closeReview(false) })
        if (reviewOverlay) {
            reviewOverlay.addEventListener('click', function (e) {
                if (e.target === reviewOverlay) closeReview(false)
            })
        }

        function showManageError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showManageSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function showManageResult(data) {
            if (!manageResult) return
            if (!data) {
                manageResult.classList.add('hidden')
                manageResult.textContent = ''
                return
            }
            try {
                manageResult.textContent = JSON.stringify(data, null, 2)
            } catch (e) {
                manageResult.textContent = String(data)
            }
            manageResult.classList.remove('hidden')
        }

        function wordPrefixMatch(value, query) {
            var v = normalizeText(value || '')
            var q = normalizeText(query || '')
            if (!q) return true
            if (!v) return false
            if (v.indexOf(q) === 0) return true
            return v.split(/\s+/).some(function (part) { return part.indexOf(q) === 0 })
        }

        function patientFullName(patient) {
            if (!patient) return ''
            return [patient.firstname, patient.middlename, patient.lastname]
                .filter(function (v) { return String(v || '').trim() !== '' })
                .join(' ')
                .trim()
        }

        function ageFromBirthdate(dateStr) {
            var raw = String(dateStr || '').slice(0, 10)
            if (!/^\d{4}-\d{2}-\d{2}$/.test(raw)) return ''
            var now = new Date()
            var y = parseInt(raw.slice(0, 4), 10)
            var m = parseInt(raw.slice(5, 7), 10) - 1
            var d = parseInt(raw.slice(8, 10), 10)
            var dob = new Date(y, m, d)
            if (isNaN(dob.getTime())) return ''
            var age = now.getFullYear() - dob.getFullYear()
            var monthDiff = now.getMonth() - dob.getMonth()
            if (monthDiff < 0 || (monthDiff === 0 && now.getDate() < dob.getDate())) {
                age -= 1
            }
            return age < 0 ? '' : String(age)
        }

        function safeIsoParts(iso) {
            var raw = String(iso || '').replace('T', ' ')
            if (raw.length >= 16) raw = raw.slice(0, 16)
            var datePart = raw.slice(0, 10)
            var timePart = raw.slice(11, 16)
            return { date: datePart, time: timePart }
        }

        function serviceSummary(appt) {
            var services = appt && Array.isArray(appt.services) ? appt.services : []
            var names = services
                .map(function (s) { return String((s && s.service_name) ? s.service_name : '').trim() })
                .filter(function (v) { return v !== '' })
            if (!names.length) return '-'
            return names.join(', ')
        }

        function personName(person, fallback) {
            var parts = person ? [person.firstname, person.middlename, person.lastname] : []
            var name = parts.filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            return name || fallback || '-'
        }

        function manageStatusLabel(appt) {
            var status = appt && appt.status ? String(appt.status) : ''
            if (!status) return ''
            if (status === 'confirmed') {
                if (appt && appt.check_in_time) return 'checked-in'
            }
            return status.replace(/_/g, ' ')
        }

        function manageRowHtml(appt) {
            if (!appt) return ''
            var id = appt.id || appt.appointment_id || ''
            var patient = appt.patient || {}
            var patientFallback = patient && patient.email ? String(patient.email) : ''
            var patientName = personName(patient, patientFallback)
            var doctor = appt.doctor || {}
            var doctorName = personName(doctor, doctor && doctor.email ? doctor.email : '-')
            var when = safeIsoParts(appt && appt.appointment_datetime ? String(appt.appointment_datetime) : '')
            if (!when) when = { date: '-', time: '-' }
            var serviceText = serviceSummary(appt)
            var statusLabel = manageStatusLabel(appt)

            var statusKey = String(appt && appt.status ? appt.status : '').toLowerCase()
            var isCheckedIn = statusKey === 'confirmed' && (appt && appt.check_in_time)
            var statusClass = ''
            if (isCheckedIn || statusKey === 'completed') {
                statusClass = 'border-emerald-200 bg-emerald-50 text-emerald-700'
            } else if (statusKey === 'cancelled') {
                statusClass = 'border-rose-200 bg-rose-50 text-rose-700'
            } else if (statusKey === 'no_show') {
                statusClass = 'border-slate-200 bg-slate-100 text-slate-600'
            } else if (statusKey === 'pending') {
                statusClass = 'border-amber-200 bg-amber-50 text-amber-700'
            } else {
                statusClass = 'border-green-200 bg-green-50 text-green-700'
            }
            var statusDisplay = statusLabel
                ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + statusClass + '">' + escapeHtml(statusLabel) + '</span>'
                : '-'

            var doctorCell = '<span class="text-slate-700">' + escapeHtml(doctorName) + '</span>'

            return (
                '<tr data-appointment-id="' + escapeHtml(id) + '">' +
                    '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(when.date || '-') + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(when.time ? formatTime12h(when.time) : '-') + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 min-w-[12rem] whitespace-nowrap">' + escapeHtml(patientName) + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 min-w-[14rem] whitespace-nowrap">' + escapeHtml(serviceText) + '</td>' +
                    '<td class="px-3 py-2 text-slate-700 min-w-[12rem] whitespace-nowrap">' + doctorCell + '</td>' +
                    '<td class="px-3 py-2 whitespace-nowrap">' + statusDisplay + '</td>' +
                    '<td class="px-3 py-2 text-right whitespace-nowrap">' +
                        '<button type="button" class="manage-see-history-btn inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300" data-patient-id="' + escapeHtml(patient && patient.user_id != null ? patient.user_id : '') + '" data-patient-name="' + escapeHtml(patientName) + '">See Details</button>' +
                    '</td>' +
                '</tr>'
            )
        }

        function openManageHistoryModal(patientId, patientName) {
            manageHistoryPatientId = patientId
            if (manageHistSubtitle) manageHistSubtitle.textContent = patientName || 'Loading…'
            if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>'
            if (manageHistDetailBody) manageHistDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>'
            if (manageHistDate) manageHistDate.value = ''
            if (manageHistStatus) manageHistStatus.value = ''
            if (manageHistType) manageHistType.value = 'scheduled'
            if (manageHistStatusSelect) { manageHistStatusSelect.value = ''; manageHistStatusSelect.disabled = true }
            if (manageHistUpdateBtn) { manageHistUpdateBtn.disabled = true }
            if (manageHistOverlay) {
                manageHistOverlay.classList.remove('hidden')
                manageHistOverlay.classList.add('flex')
            }
            loadManagePatientHistory(patientId)
        }

        function closeManageHistoryModal() {
            if (manageHistOverlay) {
                manageHistOverlay.classList.add('hidden')
                manageHistOverlay.classList.remove('flex')
            }
            manageHistoryPatientId = ''
            manageHistoryAppointments = []
        }

        function loadManagePatientHistory(patientId) {
            if (!patientId || typeof apiFetch !== 'function') return
            if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>'
            apiFetch("{{ url('/api/appointments') }}?per_page=15&patient_id=" + encodeURIComponent(patientId), { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result || !result.ok || !result.data) {
                        if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load history.</div>'
                        return
                    }
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    manageHistoryAppointments = Array.isArray(raw) ? raw.slice() : []
                    if (manageHistSubtitle) {
                        manageHistSubtitle.textContent = (manageHistSubtitle.textContent || '') + ' (' + String(manageHistoryAppointments.length) + ' records)'
                    }
                    renderManagePatientHistory()
                })
                .catch(function () {
                    if (manageHistBody) manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Network error.</div>'
                })
        }

        function renderManagePatientHistory() {
            if (!manageHistBody) return
            var list = Array.isArray(manageHistoryAppointments) ? manageHistoryAppointments.slice() : []

            // Apply modal filters
            var filterDate = manageHistDate ? String(manageHistDate.value).trim() : ''
            var filterStatus = manageHistStatus ? manageHistStatus.value : ''
            var filterType = manageHistType ? manageHistType.value : ''

            if (filterDate) {
                list = list.filter(function (a) {
                    var d = a && a.appointment_datetime ? String(a.appointment_datetime).slice(0, 10) : ''
                    return d === filterDate
                })
            }
            if (filterStatus) {
                list = list.filter(function (a) {
                    return String(a && a.status ? a.status : '').toLowerCase() === filterStatus
                })
            }
            if (filterType) {
                list = list.filter(function (a) {
                    return String(a && a.appointment_type ? a.appointment_type : '').toLowerCase() === filterType
                })
            }

            // Sort by date descending
            list.sort(function (a, b) {
                var da = a && a.appointment_datetime ? String(a.appointment_datetime) : ''
                var db = b && b.appointment_datetime ? String(b.appointment_datetime) : ''
                if (da < db) return 1; if (da > db) return -1; return 0
            })

            if (list.length === 0) {
                manageHistBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No records found.</div>'
                return
            }

            manageHistBody.innerHTML = list.map(function (a) {
                var aid = a.id || a.appointment_id || ''
                var w = safeIsoParts(a && a.appointment_datetime ? String(a.appointment_datetime) : '')
                var d = w ? (w.date || '') : ''
                var t = w ? (w.time ? formatTime12h(w.time) : '') : ''
                var doctor = a.doctor ? personName(a.doctor, '-') : '-'
                var stLabel = manageStatusLabel(a)
                var stKey = String(a && a.status ? a.status : '').toLowerCase()
                var isCI = stKey === 'confirmed' && (a && a.check_in_time)
                var sc = ''
                if (isCI || stKey === 'completed') sc = 'border-emerald-200 bg-emerald-50 text-emerald-700'
                else if (stKey === 'cancelled') sc = 'border-rose-200 bg-rose-50 text-rose-700'
                else if (stKey === 'no_show') sc = 'border-slate-200 bg-slate-100 text-slate-600'
                else if (stKey === 'pending') sc = 'border-amber-200 bg-amber-50 text-amber-700'
                else sc = 'border-green-200 bg-green-50 text-green-700'
                var typeRaw = String(a && a.appointment_type ? a.appointment_type : '').toLowerCase()
                var typeLabel = (typeRaw === 'walk_in' || typeRaw === 'walk-in' || typeRaw === 'walk in') ? 'Walk In' : (typeRaw === 'scheduled' ? 'Scheduled' : typeRaw)

                // Service info with fee (match walk-in design)
                var services = Array.isArray(a.services) ? a.services : []
                var serviceParts = services.map(function (s) {
                    var name = String(s && s.service_name ? s.service_name : '').trim()
                    var desc = String(s && s.description ? s.description : '').trim()
                    var fee = s && s.price != null ? '\u20B1' + String(s.price) : ''
                    var p = [name]
                    if (desc) p.push(desc)
                    if (fee) p.push(fee)
                    return p.join(' - ')
                }).filter(Boolean)
                var serviceInfo = ''
                if (serviceParts.length === 1) {
                    serviceInfo = serviceParts[0]
                } else if (serviceParts.length > 1) {
                    serviceInfo = serviceParts[0] + ' <span class="text-green-600 font-medium">...' + (serviceParts.length - 1) + ' more ›</span>'
                } else {
                    serviceInfo = 'No services'
                }

                return (
                    '<button type="button" class="manage-hist-item-btn w-full text-left rounded-xl border border-slate-200 bg-white p-3 hover:border-green-300 hover:shadow-sm transition-all cursor-pointer active-appt-item" data-appointment-id="' + escapeHtml(aid) + '">' +
                        '<div class="flex items-center justify-between mb-1">' +
                            '<span class="text-[0.78rem] font-semibold text-slate-800 truncate">' + escapeHtml(d) + ' ' + escapeHtml(t) + '</span>' +
                            '<span class="inline-flex items-center gap-1.5 shrink-0">' +
                                '<span class="inline-flex items-center px-2 py-0.5 rounded text-[0.6rem] font-medium border ' + (String(typeLabel).toLowerCase().trim() === 'walk in' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200') + '">' + escapeHtml(typeLabel) + '</span>' +
                                '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.62rem] border ' + sc + '">' + escapeHtml(stLabel || '-') + '</span>' +
                            '</span>' +
                        '</div>' +
                        '<div class="text-[0.72rem] text-slate-500 mb-1">' + escapeHtml(doctor) + '</div>' +
                        '<div class="text-[0.68rem] text-slate-500 mb-1 truncate">' + serviceInfo + '</div>' +
                        '<span class="text-[0.7rem] font-semibold text-green-700">View Details →</span>' +
                    '</button>'
                )
            }).join('')
        }

        function renderManageApptDetail(appt) {
            if (!appt || !manageHistDetailBody) {
                if (manageHistDetailBody) manageHistDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No appointment selected.</div>'
                if (manageHistStatusSelect) { manageHistStatusSelect.value = ''; manageHistStatusSelect.disabled = true }
                if (manageHistUpdateBtn) manageHistUpdateBtn.disabled = true
                return
            }

            var patient = appt.patient || {}
            var doctor = appt.doctor || {}
            var patientName = personName(patient, 'N/A')
            var doctorName = personName(doctor, 'N/A')
            var stLabel = manageStatusLabel(appt)
            var apptId = appt.id || appt.appointment_id || ''
            var docId = doctor.user_id || doctor.doctor_id || ''

            var apptTypeRaw = String(appt && appt.appointment_type ? appt.appointment_type : '').toLowerCase()
            var apptTypeLabel = (apptTypeRaw === 'walk_in' || apptTypeRaw === 'walk-in' || apptTypeRaw === 'walk in') ? 'Walk In' : (apptTypeRaw === 'scheduled' ? 'Scheduled' : apptTypeRaw)
            var currentStatus = String(appt && appt.status ? appt.status : '').toLowerCase()

            var stKey = currentStatus
            var isCI = stKey === 'confirmed' && (appt && appt.check_in_time)
            var sc = ''
            if (isCI || stKey === 'completed') sc = 'border-emerald-200 bg-emerald-50 text-emerald-700'
            else if (stKey === 'cancelled') sc = 'border-rose-200 bg-rose-50 text-rose-700'
            else if (stKey === 'no_show') sc = 'border-slate-200 bg-slate-100 text-slate-600'
            else if (stKey === 'pending') sc = 'border-amber-200 bg-amber-50 text-amber-700'
            else sc = 'border-green-200 bg-green-50 text-green-700'

            var dt = appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
            var tx = appt.transaction || null
            var services = Array.isArray(appt.services) ? appt.services : []
            var serviceNames = services.length ? services.map(function (s) { return s.service_name || s.name || '' }).filter(Boolean).join(', ') : '-'
            var amount = tx ? (tx.amount || 0) : 0
            var discountAmount = tx ? (tx.discount_amount || 0) : 0
            var discountType = tx ? (tx.discount_type || 'none') : 'none'
            var net = parseFloat(amount) - parseFloat(discountAmount)
            var reason = appt.reason_for_visit ? escapeHtml(appt.reason_for_visit) : '<span class="text-slate-400">-</span>'
            var diagnosis = tx ? (tx.diagnosis || '-') : '-'
            var treatment = tx ? (tx.treatment_notes || '-') : '-'
            var txId = tx ? (tx.transaction_id || tx.id || '') : ''

            manageHistDetailBody.innerHTML =
                '<div class="space-y-3">' +
                    // Appointment card
                    '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Appointment</div>' +
                        '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                            '<div class="text-slate-500">Patient</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(patientName) + '</div>' +
                            '<div class="text-slate-500">Date & Time</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(dt) + '</div>' +
                            '<div class="text-slate-500">Doctor</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(doctorName) + '</div>' +
                            '<div class="text-slate-500">Type</div>' +
                            '<div><span class="inline-flex items-center px-2 py-0.5 rounded text-[0.6rem] font-medium border ' + (String(apptTypeLabel).toLowerCase().trim() === 'walk in' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200') + '">' + escapeHtml(apptTypeLabel) + '</span></div>' +
                            '<div class="text-slate-500">Status</div>' +
                            '<div><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + sc + '">' + escapeHtml(stLabel || '-') + '</span></div>' +
                            '<div class="text-slate-500">Reason</div>' +
                            '<div class="text-slate-800">' + reason + '</div>' +
                            // Change Appointment as clickable text inside the card
                            (currentStatus === 'confirmed'
                                ? '<div class="col-span-2 mt-1 pt-2 border-t border-slate-100"><button type="button" class="rec-book-change-appt text-[0.7rem] font-semibold text-emerald-600 hover:text-emerald-700 hover:underline" data-appointment-id="' + escapeHtml(apptId) + '" data-doctor-id="' + escapeHtml(docId) + '">Change Appointment &#8594;</button></div>'
                                : '') +
                        '</div>' +
                    '</div>' +
                    // Services & Payment card
                    '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Services & Payment</div>' +
                        '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                            '<div class="text-slate-500">Services</div>' +
                            '<div class="text-slate-800">' + escapeHtml(serviceNames) + (services.length && services[0] && services[0].description ? ' — <span class="text-slate-500">' + escapeHtml(services[0].description) + '</span>' : '') + '</div>' +
                            '<div class="text-slate-500">Gross Amount</div>' +
                            '<div class="text-slate-800 font-medium">₱' + escapeHtml(Number(amount).toFixed(2)) + '</div>' +
                            '<div class="text-slate-500">Discount (' + escapeHtml(discountType !== 'none' ? discountType.toUpperCase() : 'None') + ')</div>' +
                            '<div class="text-slate-800">−₱' + escapeHtml(Number(discountAmount).toFixed(2)) + '</div>' +
                            '<div class="text-slate-500 font-semibold">Net</div>' +
                            '<div class="text-slate-800 font-bold text-green-700">₱' + escapeHtml(net.toFixed(2)) + '</div>' +
                            '<div class="text-slate-500">Payment Mode</div>' +
                            '<div class="text-slate-800">' + (tx ? escapeHtml(tx.payment_mode || '-') : '-') + '</div>' +
                        '</div>' +
                    '</div>' +
                    // Diagnosis & Treatment card
                    '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Diagnosis & Treatment</div>' +
                        '<div class="text-[0.78rem] space-y-2">' +
                            '<div><span class="text-slate-500">Diagnosis:</span><br><span class="text-slate-800">' + escapeHtml(diagnosis) + '</span></div>' +
                            '<div><span class="text-slate-500">Treatment Notes:</span><br><span class="text-slate-800">' + escapeHtml(treatment) + '</span></div>' +
                        '</div>' +
                    '</div>' +
                    // Prescriptions card (loaded async below)
                    '<div id="manageHistPrescriptionsWrap" class="rounded-xl border border-slate-200 bg-white p-3">' +
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                        '<div class="text-[0.78rem] text-slate-400">Loading prescriptions…</div>' +
                    '</div>' +
                '</div>'

            // Enable status dropdown
            if (manageHistStatusSelect) {
                manageHistStatusSelect.disabled = false
                manageHistStatusSelect.value = currentStatus
            }
            if (manageHistUpdateBtn) {
                manageHistUpdateBtn.disabled = false
                manageHistUpdateBtn.setAttribute('data-appointment-id', apptId)
            }

            // Load prescriptions asynchronously
            if (txId && typeof apiFetch === 'function') {
                apiFetch("{{ url('/api/prescriptions') }}?per_page=20&transaction_id=" + encodeURIComponent(txId), { method: 'GET' })
                    .then(function (response) { return readResponse(response) })
                    .then(function (result) {
                        var wrap = document.getElementById('manageHistPrescriptionsWrap')
                        if (!wrap) return
                        var items = (result && result.ok && result.data) ? (Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])) : []
                        if (!items.length) {
                            wrap.innerHTML =
                                '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                                '<div class="text-[0.78rem] text-slate-500">No prescriptions found.</div>'
                            return
                        }
                        var rxHtml = ''
                        items.forEach(function (rx) {
                            var rxDr = rx.doctor ? personName(rx.doctor, '-') : '-'
                            var rxDate = rx.prescribed_datetime ? String(rx.prescribed_datetime).replace('T', ' ').slice(0, 10) : '-'
                            var rxNotes = rx.notes ? escapeHtml(rx.notes) : ''
                            rxHtml += '<div class="border-b border-slate-100 pb-2 mb-2 last:border-0 last:pb-0 last:mb-0">' +
                                '<div class="flex items-center justify-between text-[0.72rem] text-slate-500 mb-1">' +
                                    '<span class="font-medium text-slate-700">' + escapeHtml(rxDate) + '</span>' +
                                    '<span>' + escapeHtml(rxDr) + '</span>' +
                                '</div>' +
                                (rxNotes ? '<div class="text-[0.72rem] text-slate-600 mb-1">' + rxNotes + '</div>' : '') +
                                '<div class="overflow-x-auto">' +
                                    '<table class="w-full text-[0.72rem] text-slate-700">' +
                                        '<thead>' +
                                            '<tr class="text-[0.6rem] uppercase tracking-wider text-slate-400 border-b border-slate-100">' +
                                                '<th class="py-1 pr-2 text-left">Medicine</th>' +
                                                '<th class="py-1 pr-2 text-left">Dosage</th>' +
                                                '<th class="py-1 pr-2 text-left">Frequency</th>' +
                                                '<th class="py-1 pr-0 text-left">Duration</th>' +
                                            '</tr>' +
                                        '</thead>' +
                                        '<tbody>'
                            var rxItems = Array.isArray(rx.items) ? rx.items : []
                            if (rxItems.length) {
                                rxItems.forEach(function (it) {
                                    rxHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                                        '<td class="py-1 pr-2 font-medium">' + escapeHtml(it.medicine_name || '-') + '</td>' +
                                        '<td class="py-1 pr-2">' + escapeHtml(it.dosage || '-') + '</td>' +
                                        '<td class="py-1 pr-2">' + escapeHtml(it.frequency || '-') + '</td>' +
                                        '<td class="py-1 pr-0">' + escapeHtml(it.duration || '-') + '</td>' +
                                    '</tr>'
                                })
                            } else {
                                rxHtml += '<tr><td colspan="4" class="py-1 text-slate-400">No items</td></tr>'
                            }
                            rxHtml += '</tbody></table></div></div>'
                        })
                        wrap.innerHTML =
                            '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                            rxHtml
                    })
                    .catch(function () {
                        var wrap = document.getElementById('manageHistPrescriptionsWrap')
                        if (wrap) {
                            wrap.innerHTML =
                                '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                                '<div class="text-[0.78rem] text-slate-500">Failed to load prescriptions.</div>'
                        }
                    })
            } else {
                var wrap = document.getElementById('manageHistPrescriptionsWrap')
                if (wrap) {
                    wrap.innerHTML =
                        '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Prescriptions & Medicines</div>' +
                        '<div class="text-[0.78rem] text-slate-500">No info.</div>'
                }
            }
        }

        function updateManageApptStatus(appointmentId, newStatus) {
            if (!appointmentId || !newStatus || typeof apiFetch !== 'function') return
            if (manageHistUpdateBtn) {
                manageHistUpdateBtn.disabled = true
                manageHistUpdateBtn.textContent = 'Updating…'
            }
            apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(appointmentId), {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: newStatus })
            })
            .then(function (response) {
                return readResponse(response).then(function (result) {
                    if (manageHistUpdateBtn) { manageHistUpdateBtn.disabled = false; manageHistUpdateBtn.textContent = 'Update Status' }
                    if (!result.ok) {
                        var msg = (result.data && result.data.message) ? result.data.message : 'Failed to update status.'
                        if (typeof showToast === 'function') showToast(msg, 'error')
                        return
                    }
                    if (typeof showToast === 'function') showToast('Status updated successfully.', 'success')
                    // Refresh both the modal list and the underlying table
                    if (manageHistoryPatientId) loadManagePatientHistory(manageHistoryPatientId)
                    loadManageAppointments()
                })
            })
            .catch(function () {
                if (manageHistUpdateBtn) { manageHistUpdateBtn.disabled = false; manageHistUpdateBtn.textContent = 'Update Status' }
                if (typeof showToast === 'function') showToast('Network error while updating status.', 'error')
            })
        }

        function applyManageRowUpdate(appt) {
            if (!manageTableBody) return
            if (!appt || appt.appointment_id == null) return
            var id = String(appt.appointment_id)
            var row = manageTableBody.querySelector('tr[data-appointment-id="' + id + '"]')
            if (!row) return
            row.outerHTML = manageRowHtml(appt)
        }

        function renderManageServiceResults() {
            if (!manageServiceResults || !manageServiceSearch) return
            var q = String(manageServiceSearch.value || '').trim()
            var list = Array.isArray(manageServices) ? manageServices : []
            var filtered = list.filter(function (s) {
                var name = s && s.service_name ? String(s.service_name) : ''
                return wordPrefixMatch(name, q)
            })
            filtered = filtered.slice(0, 25)
            if (!filtered.length) {
                manageServiceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No services found.</div>'
            } else {
                manageServiceResults.innerHTML = filtered.map(function (s) {
                    var id = s.service_id != null ? s.service_id : ''
                    var name = s.service_name != null ? s.service_name : ('Service #' + id)
                    return '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 text-[0.78rem] text-slate-700" data-service-id="' + escapeHtml(id) + '">' + escapeHtml(name) + '</button>'
                }).join('')
            }
            manageServiceResults.classList.remove('hidden')
        }

        function setManageServiceSelection(service) {
            if (manageServiceId) manageServiceId.value = service && service.service_id != null ? String(service.service_id) : ''
            if (manageServiceSearch) {
                manageServiceSearch.value = service && service.service_name ? String(service.service_name) : ''
                if (!service) manageServiceSearch.placeholder = 'All services'
            }
            if (manageServiceResults) manageServiceResults.classList.add('hidden')
        }

        function loadManageServices() {
            if (manageServicesLoaded || manageServicesLoading || typeof apiFetch !== 'function') return
            manageServicesLoading = true
            apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) return
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    manageServices = raw || []
                    manageServicesLoaded = true
                })
                .catch(function () {})
                .finally(function () {
                    manageServicesLoading = false
                })
        }

        function renderManageAppointments(list) {
            if (!manageTableBody) return
            var rows = Array.isArray(list) ? list : []
            if (!rows.length) {
                manageTableBody.innerHTML = '<tr><td colspan="7" class="px-3 py-6 text-center text-[0.78rem] text-slate-500">No appointments found.</td></tr>'
                var pag = document.getElementById('receptionManagePagination')
                if (pag) pag.innerHTML = ''
                return
            }
            manageTableBody.innerHTML = rows.map(manageRowHtml).join('')
            renderManagePagination()
        }

        function renderManagePagination() {
            var pag = document.getElementById('receptionManagePagination')
            if (!pag) return
            if (manageTotal === 0) { pag.innerHTML = ''; return }
            var totalPages = manageLastPage
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + manageTotal + ' entries</span>'
            html += '<button type="button" class="' + (manageCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-manage-page="prev"' + (manageCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var ws = Math.max(1, manageCurrentPage - Math.floor(manageVisibleCount / 2))
            var we = Math.min(ws + manageVisibleCount - 1, totalPages)
            if (we - ws + 1 < manageVisibleCount) ws = Math.max(1, we - manageVisibleCount + 1)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === manageCurrentPage ? btnActive : btnInactive) + '" data-manage-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-manage-page="next-window" title="Next set">…</button>' }
            html += '<button type="button" class="' + (manageCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-manage-page="next"' + (manageCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            pag.innerHTML = html
            pag.querySelectorAll('button[data-manage-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-manage-page')
                    if (p === 'prev' && manageCurrentPage > 1) { manageCurrentPage-- }
                    else if (p === 'next' && manageCurrentPage < totalPages) { manageCurrentPage++ }
                    else if (p === 'next-window') { manageCurrentPage = Math.min(we + 1, totalPages) }
                    else if (p !== 'prev' && p !== 'next') { manageCurrentPage = parseInt(p, 10) }
                    else return
                    // Re-run the full load to preserve filters
                    var fn = typeof loadManageAppointments === 'function' ? loadManageAppointments : null
                    if (fn) fn()
                })
            })
        }

        function loadManageAppointments(page) {
            if (typeof apiFetch !== 'function') return
            page = page || manageCurrentPage
            showManageError('')
            showManageSuccess('')
            showManageResult(null)
            setManageSubmitting(true)
            manageTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading appointments…</td></tr>'

            var url = "{{ url('/api/appointments') }}" + '?per_page=10&page=' + page + '&appointment_type=scheduled'
            var order = manageSortSelect && manageSortSelect.value ? String(manageSortSelect.value) : 'latest'
            url += '&order=' + encodeURIComponent(order === 'oldest' ? 'oldest' : 'latest')

            var now = new Date()
            var startIso = ''
            var endIso = ''
            if (manageFilterDate) {
                startIso = manageFilterDate
                endIso = manageFilterDate
            } else if (manageShowTodayOnly) {
                var todayIso = formatLocalDateIso(now)
                startIso = todayIso
                endIso = todayIso
            } else {
                var start = new Date(now.getFullYear(), now.getMonth(), 1)
                var end = new Date(now.getFullYear(), now.getMonth() + 1, 0)
                startIso = formatLocalDateIso(start)
                endIso = formatLocalDateIso(end)
            }
            url += '&start_date=' + encodeURIComponent(startIso)
            url += '&end_date=' + encodeURIComponent(endIso)

            var search = manageSearchInput ? normalizeText(manageSearchInput.value) : ''
            if (search) url += '&search=' + encodeURIComponent(search)

            var serviceId = manageServiceId && manageServiceId.value ? parseInt(manageServiceId.value, 10) : 0
            if (serviceId) url += '&service_id=' + encodeURIComponent(serviceId)

            var statusFilter = manageStatusSelect && manageStatusSelect.value ? String(manageStatusSelect.value) : ''
            if (statusFilter) url += '&status=' + encodeURIComponent(statusFilter)

            apiFetch(url, { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) {
                        var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to load appointments.'
                        showManageError(msg)
                        renderManageAppointments([])
                        return
                    }
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var rows = Array.isArray(raw) ? raw.slice() : []

                    manageCurrentPage = result.data.current_page || page
                    manageLastPage = result.data.last_page || 1
                    manageTotal = result.data.total || rows.length

                    renderManageAppointments(rows)

                    if (manageMeta) {
                        if (manageShowTodayOnly) {
                            manageMeta.textContent = 'Showing page ' + manageCurrentPage + ' of ' + manageLastPage + ' (' + manageTotal + ' appointments for ' + startIso + ').'
                        } else {
                            var monthLabel = startIso.slice(0, 7)
                            manageMeta.textContent = 'Showing page ' + manageCurrentPage + ' of ' + manageLastPage + ' (' + manageTotal + ' appointments for ' + monthLabel + ').'
                        }
                    }
                })
                .catch(function () {
                    showManageError('Network error while loading appointments.')
                    renderManageAppointments([])
                })
                .finally(function () {
                    setManageSubmitting(false)
                })
        }

        if (manageServiceSearch) {
            manageServiceSearch.addEventListener('focus', function () {
                loadManageServices()
                renderManageServiceResults()
            })
            manageServiceSearch.addEventListener('input', function () {
                if (manageServiceId && manageServiceId.value) {
                    var picked = manageServices.find(function (s) { return String(s.service_id) === String(manageServiceId.value) }) || null
                    var pickedName = picked && picked.service_name ? String(picked.service_name) : ''
                    if (normalizeText(manageServiceSearch.value) !== normalizeText(pickedName)) {
                        setManageServiceSelection(null)
                        manageCurrentPage = 1
                        loadManageAppointments()
                    }
                }
                loadManageServices()
                renderManageServiceResults()
            })
        }

        if (manageServiceResults) {
            manageServiceResults.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-service-id]') : null
                if (!btn) return
                var id = btn.getAttribute('data-service-id')
                var picked = manageServices.find(function (s) { return String(s.service_id) === String(id) }) || null
                setManageServiceSelection(picked)
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        if (manageSearchInput) {
            manageSearchInput.addEventListener('input', function () {
                if (manageSearchTimer) clearTimeout(manageSearchTimer)
                manageSearchTimer = setTimeout(function () {
                    manageCurrentPage = 1
                    loadManageAppointments()
                }, 250)
            })
        }

        if (manageSortSelect) {
            manageSortSelect.addEventListener('change', function () {
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }
        if (manageStatusSelect) {
            manageStatusSelect.addEventListener('change', function () {
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        if (manageRefreshBtn) {
            manageRefreshBtn.addEventListener('click', function () {
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }
        if (manageTodayOnlyBtn) {
            updateManageTodayButton()
            manageTodayOnlyBtn.addEventListener('click', function () {
                manageShowTodayOnly = !manageShowTodayOnly
                updateManageTodayButton()
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        var manageCalendarToggle = document.getElementById('receptionManageCalendarToggle')
        var manageCalendarToggleText = document.getElementById('receptionManageCalendarToggleText')
        var manageTableArea = document.getElementById('receptionManageTableArea')
        var manageCalendarArea = document.getElementById('receptionManageCalendarArea')
        var manageShowCalendar = false
        if (manageCalendarToggle && manageTableArea && manageCalendarArea) {
            manageCalendarToggle.addEventListener('click', function () {
                manageShowCalendar = !manageShowCalendar
                if (manageShowCalendar) {
                    manageTableArea.classList.add('hidden')
                    manageCalendarArea.classList.remove('hidden')
                    manageCalendarToggle.classList.add('bg-green-600', 'text-white', 'border-green-600')
                    manageCalendarToggle.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300')
                    if (manageCalendarToggleText) manageCalendarToggleText.textContent = 'Table view'
                    if (manageDateHeader) manageDateHeader.style.display = 'none'
                    if (manageClearFilterBtn) manageClearFilterBtn.style.display = 'none'
                    manageCalMonth = new Date()
                    manageCalMonth.setDate(1)
                    loadManageMonthAppointments()
                } else {
                    manageCalendarArea.classList.add('hidden')
                    manageTableArea.classList.remove('hidden')
                    manageCalendarToggle.classList.remove('bg-green-600', 'text-white', 'border-green-600')
                    manageCalendarToggle.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50', 'hover:border-slate-300')
                    if (manageCalendarToggleText) manageCalendarToggleText.textContent = 'Calendar view'
                    if (manageFilterDate && manageDateHeader && manageClearFilterBtn) {
                        manageDateHeader.style.display = ''
                        manageClearFilterBtn.style.display = ''
                    }
                }
            })
        }

        if (manageCalDatePrev) {
            manageCalDatePrev.addEventListener('click', function () {
                manageCalMonth.setMonth(manageCalMonth.getMonth() - 1)
                loadManageMonthAppointments()
            })
        }
        if (manageCalDateNext) {
            manageCalDateNext.addEventListener('click', function () {
                manageCalMonth.setMonth(manageCalMonth.getMonth() + 1)
                loadManageMonthAppointments()
            })
        }

        if (manageCalDateGrid) {
            manageCalDateGrid.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-date]') : null
                if (!btn || btn.disabled) return
                var dateIso = btn.getAttribute('data-date') || ''
                if (!dateIso) return
                manageFilterDate = dateIso
                manageCurrentPage = 1
                if (manageDateHeader) {
                    var d = new Date(dateIso + 'T00:00:00')
                    var formatted = d.toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' })
                    manageDateHeader.textContent = 'Showing ' + formatted + ' Appointments'
                    manageDateHeader.style.display = ''
                }
                if (manageClearFilterBtn) manageClearFilterBtn.style.display = ''
                if (manageCalendarToggle && manageTableArea && manageCalendarArea) {
                    manageShowCalendar = false
                    manageCalendarArea.classList.add('hidden')
                    manageTableArea.classList.remove('hidden')
                    manageCalendarToggle.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'hover:bg-slate-50', 'hover:border-slate-300')
                    manageCalendarToggle.classList.add('bg-white', 'text-slate-700', 'border-slate-200')
                    if (manageCalendarToggleText) manageCalendarToggleText.textContent = 'Calendar view'
                }
                loadManageAppointments()
            })
        }

        if (manageClearFilterBtn) {
            manageClearFilterBtn.addEventListener('click', function () {
                manageFilterDate = ''
                if (manageDateHeader) {
                    manageDateHeader.textContent = ''
                    manageDateHeader.style.display = 'none'
                }
                manageClearFilterBtn.style.display = 'none'
                manageCurrentPage = 1
                loadManageAppointments()
            })
        }

        if (manageTableBody) {
            manageTableBody.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-action][data-id]') : null
                if (!btn) return
                var action = btn.getAttribute('data-action') || ''
                var id = parseInt(btn.getAttribute('data-id') || '0', 10)
                if (!id) return

                showManageError('')
                showManageSuccess('')
                setManageSubmitting(true)

                var url = "{{ url('/api/appointments') }}/" + encodeURIComponent(id)

                if (action === 'view') {
                    apiFetch(url, { method: 'GET' })
                        .then(function (response) { return readResponse(response) })
                        .then(function (result) {
                            if (!result.ok) {
                                var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to fetch appointment.'
                                showManageError(msg)
                                return
                            }
                            showManageSuccess('Appointment details loaded.')
                            showManageResult(result.data)
                        })
                        .catch(function () {
                            showManageError('Network error while fetching appointment.')
                        })
                        .finally(function () {
                            setManageSubmitting(false)
                        })
                    return
                }

                var body = {}
                var confirmText = 'Apply this action?'
                if (action === 'check_in') {
                    var nowCheckIn = new Date()
                    body.check_in_time = formatLocalDateIso(nowCheckIn) + ' ' + nowCheckIn.toTimeString().slice(0, 8)
                    confirmText = 'Mark this appointment as checked-in now?'
                } else if (action === 'cancel') {
                    body.status = 'cancelled'
                    confirmText = 'Cancel this appointment?'
                } else {
                    setManageSubmitting(false)
                    return
                }

                confirmAction(confirmText)
                    .then(function (confirmed) {
                        if (!confirmed) return
                        return apiFetch(url, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(body)
                        })
                            .then(function (response) { return readResponse(response) })
                            .then(function (result) {
                                if (!result.ok) {
                                    var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to update appointment.'
                                    showManageError(msg)
                                    return
                                }
                                showManageSuccess('Appointment has been updated.')
                                showManageResult(result.data)
                                applyManageRowUpdate(result.data)
                                loadManageAppointments()
                            })
                    })
                    .catch(function () {
                        showManageError('Network error while updating appointment.')
                    })
                    .finally(function () {
                        setManageSubmitting(false)
                    })
            })
        }

        // See Details
        if (manageTableBody) {
            manageTableBody.addEventListener('click', function (e) {
                var btn = e.target.closest('.manage-see-history-btn')
                if (btn) {
                    var pid = btn.getAttribute('data-patient-id')
                    var pname = btn.getAttribute('data-patient-name')
                    if (pid) openManageHistoryModal(pid, pname)
                }
            })
        }

        if (manageHistOverlay) {
            manageHistOverlay.addEventListener('click', function (e) {
                if (e.target === manageHistOverlay) closeManageHistoryModal()
            })
        }
        if (manageHistClose) {
            manageHistClose.addEventListener('click', closeManageHistoryModal)
        }

        if (manageHistDate) manageHistDate.addEventListener('change', renderManagePatientHistory)
        if (manageHistStatus) manageHistStatus.addEventListener('change', renderManagePatientHistory)
        if (manageHistType) manageHistType.addEventListener('change', renderManagePatientHistory)

        if (manageHistBody) {
            manageHistBody.addEventListener('click', function (e) {
                var item = e.target.closest('.manage-hist-item-btn')
                if (item) {
                    var aid = item.getAttribute('data-appointment-id')
                    var appt = manageHistoryAppointments.find(function (a) {
                        return String(a.id || a.appointment_id || '') === aid
                    })
                    if (appt) {
                        // Highlight selected
                        var allItems = manageHistBody.querySelectorAll('.manage-hist-item-btn')
                        allItems.forEach(function (el) { el.classList.remove('border-green-400', 'bg-green-50') })
                        item.classList.add('border-green-400', 'bg-green-50')
                        renderManageApptDetail(appt)
                    }
                }
            })
        }

        if (manageHistUpdateBtn) {
            manageHistUpdateBtn.addEventListener('click', function () {
                var aid = manageHistUpdateBtn.getAttribute('data-appointment-id')
                var newStatus = manageHistStatusSelect ? manageHistStatusSelect.value : ''
                if (aid && newStatus) updateManageApptStatus(aid, newStatus)
            })
        }

        document.addEventListener('click', function (e) {
            if (!manageServiceResults || !manageServiceSearch) return
            if (manageServiceSearch.contains(e.target) || manageServiceResults.contains(e.target)) return
            manageServiceResults.classList.add('hidden')
        })

        // ── Change Appointment overlay state ──
        var recBookDocPickerOverlay = document.getElementById('receptionBookDocPickerOverlay')
        var recBookDocPickerBody = document.getElementById('receptionBookDocPickerBody')
        var recBookDocPickerClose = document.getElementById('receptionBookDocPickerClose')
        var recBookPendingDoctorId = null
        var recBookPendingAppointmentId = null
        var recBookCurrentDoctor = null
        var recBookPendingDoctorChanged = false

        var recBookSelectedDate = null
        var recBookSelectedTime = null
        var recBookDocPickerSubtitle = document.getElementById('recBookPickerSubtitle')
        var recBookSlotPicker = document.getElementById('recBookSlotPicker')
        var recBookDateOptions = document.getElementById('recBookDateOptions')
        var recBookTimeOptions = document.getElementById('recBookTimeOptions')
        var recBookConfirmSlotBtn = document.getElementById('recBookConfirmSlotBtn')
        var recBookToggleDoctor = document.getElementById('recBookToggleDoctor')
        var recBookToggleDoctorCaret = document.getElementById('recBookToggleDoctorCaret')
        var recBookCurrentDoctorLabel = document.getElementById('recBookCurrentDoctorLabel')
        var recBookDoctorListWrap = document.getElementById('recBookDoctorListWrap')

        // ── Change Appointment Panel (inside Details modal) ──
        var manageHistChangePanel = document.getElementById('manageHistChangePanel')
        var manageHistListSection = document.getElementById('manageHistListSection')
        var manageHistChangeBack = document.getElementById('manageHistChangeBack')
        var manageHistChangeDoctor = document.getElementById('manageHistChangeDoctor')
        var manageHistChangeCalPrev = document.getElementById('manageHistChangeCalPrev')
        var manageHistChangeCalNext = document.getElementById('manageHistChangeCalNext')
        var manageHistChangeCalMonth = document.getElementById('manageHistChangeCalMonth')
        var manageHistChangeCalGrid = document.getElementById('manageHistChangeCalGrid')
        var manageHistChangeTimeBody = document.getElementById('manageHistChangeTimeBody')
        var manageHistChangeConfirmBtn = document.getElementById('manageHistChangeConfirmBtn')
        var manageHistChangeCalDate = new Date()
        manageHistChangeCalDate.setDate(1)
        var manageHistChangeMonthAppts = {}
        var manageHistChangeSelectedDate = null
        var manageHistChangeSelectedTime = null

        function manageHistRenderDoctorDropdown(docList, selectedDocId) {
            if (!manageHistChangeDoctor || !docList || !docList.length) return
            var allowedSpecializations = [
                'general surgeon',
                'obstetrician-gynecologist',
                'obstetrician - gynecologist',
                'internal medicine'
            ]
            var filtered = docList.filter(function (doc) {
                if (!doc || !doc.specialization) return false
                var spec = normalizeText(doc.specialization)
                for (var s = 0; s < allowedSpecializations.length; s++) {
                    if (specializationMatches(allowedSpecializations[s], spec)) return true
                }
                return false
            })
            manageHistChangeDoctor.innerHTML = ''
            for (var i = 0; i < filtered.length; i++) {
                var doc = filtered[i]
                var opt = document.createElement('option')
                opt.value = doc.user_id
                opt.textContent = doctorDisplayName(doc)
                if (String(doc.user_id) === String(selectedDocId)) opt.selected = true
                manageHistChangeDoctor.appendChild(opt)
            }
        }

        function manageHistRenderChangeCalendar() {
            if (!manageHistChangeCalGrid || !manageHistChangeCalMonth) return
            var year = manageHistChangeCalDate.getFullYear()
            var month = manageHistChangeCalDate.getMonth()
            var first = new Date(year, month, 1)
            var firstDow = first.getDay()
            var daysIn = new Date(year, month + 1, 0).getDate()

            manageHistChangeCalMonth.textContent = first.toLocaleDateString(undefined, { month: 'short', year: 'numeric' })

            var today = new Date()
            today.setHours(0, 0, 0, 0)
            var selectedIso = manageHistChangeSelectedDate || ''

            var cells = []
            for (var i = 0; i < firstDow; i++) cells.push('')
            for (var day = 1; day <= daysIn; day++) {
                var d = new Date(year, month, day)
                var iso = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                var notPast = d.getTime() >= today.getTime()
                var selected = selectedIso === iso
                var base = 'w-full rounded-md text-[0.7rem] font-semibold border transition-colors flex items-center justify-center aspect-square'
                var cls = base + ' ' + (notPast
                    ? (selected ? 'bg-green-600 text-white border-green-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50')
                    : 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed')
                var count = manageHistChangeMonthAppts[iso] || 0
                var showBadge = count > 0 && notPast
                var badge = showBadge ? '<span class="absolute -top-1 -right-1 min-w-[12px] h-[12px] bg-red-500 text-white text-[0.45rem] leading-[12px] font-bold rounded-full px-0.5 text-center">' + count + '</span>' : ''
                cells.push('<button type="button" class="relative ' + cls + '" data-date="' + iso + '"' + (notPast ? '' : ' disabled') + '>' + day + badge + '</button>')
            }
            var total = Math.ceil(cells.length / 7) * 7
            while (cells.length < total) cells.push('')
            manageHistChangeCalGrid.innerHTML = cells.map(function (html) {
                return html ? html : '<div></div>'
            }).join('')
        }

        function manageHistLoadMonthAppts() {
            if (typeof apiFetch !== 'function') return
            var doctorId = manageHistChangeDoctor ? manageHistChangeDoctor.value : ''
            if (!doctorId) return
            var y = manageHistChangeCalDate.getFullYear()
            var m = manageHistChangeCalDate.getMonth()
            var startDate = y + '-' + String(m + 1).padStart(2, '0') + '-01'
            var lastDay = new Date(y, m + 1, 0).getDate()
            var endDate = y + '-' + String(m + 1).padStart(2, '0') + '-' + String(lastDay).padStart(2, '0')
            apiFetch("{{ url('/api/appointments') }}?doctor_id=" + encodeURIComponent(doctorId) + "&appointment_type=scheduled&start_date=" + encodeURIComponent(startDate) + "&end_date=" + encodeURIComponent(endDate) + "&per_page=200", { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var map = {}
                    raw.forEach(function (a) {
                        if (!a || !a.appointment_datetime) return
                        if (String(a.status || '').toLowerCase() === 'cancelled') return
                        if (String(a.appointment_type || '').toLowerCase() !== 'scheduled') return
                        var d = new Date(a.appointment_datetime)
                        if (!isNaN(d.getTime())) {
                            var datePart = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                            map[datePart] = (map[datePart] || 0) + 1
                        }
                    })
                    manageHistChangeMonthAppts = map
                    manageHistRenderChangeCalendar()
                })
                .catch(function () {
                    manageHistChangeMonthAppts = {}
                    manageHistRenderChangeCalendar()
                })
        }

        function manageHistLoadTimeSlots(dateIso) {
            if (!manageHistChangeTimeBody || !dateIso) return
            manageHistChangeTimeBody.innerHTML = '<div class="text-center text-[0.72rem] text-slate-400 py-4">Loading time slots…</div>'
            var doctorId = manageHistChangeDoctor ? manageHistChangeDoctor.value : ''
            if (!doctorId) return

            apiFetch("{{ url('/api/appointments') }}?doctor_id=" + encodeURIComponent(doctorId) + "&appointment_type=scheduled&start_date=" + encodeURIComponent(dateIso) + "&end_date=" + encodeURIComponent(dateIso) + "&per_page=50", { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var bookedSet = {}
                    raw.forEach(function (a) {
                        if (!a || !a.appointment_datetime) return
                        if (String(a.status || '').toLowerCase() === 'cancelled') return
                        if (String(a.appointment_type || '').toLowerCase() !== 'scheduled') return
                        var d = new Date(a.appointment_datetime)
                        if (!isNaN(d.getTime())) {
                            var datePart = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
                            if (datePart === dateIso) {
                                var timeKey = String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0')
                                bookedSet[timeKey] = true
                            }
                        }
                    })
                    manageHistRenderTimeOptions(bookedSet)
                })
                .catch(function () {
                    manageHistChangeTimeBody.innerHTML = '<div class="text-center text-[0.72rem] text-slate-400 py-4">Error loading time slots.</div>'
                })
        }

        function manageHistRenderTimeOptions(bookedSet) {
            if (!manageHistChangeTimeBody) return
            var slotMinutes = 30
            var startHour = 7
            var endHour = 17
            var html = '<div class="grid grid-cols-3 gap-1.5">'
            for (var h = startHour; h < endHour; h++) {
                for (var m = 0; m < 60; m += slotMinutes) {
                    var timeStr = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0')
                    var display = (h % 12 || 12) + ':' + String(m).padStart(2, '0') + ' ' + (h < 12 ? 'AM' : 'PM')
                    var booked = bookedSet && bookedSet[timeStr]
                    var selected = manageHistChangeSelectedTime === timeStr
                    var cls = 'w-full rounded-lg border px-2 py-1.5 text-[0.68rem] font-semibold text-center transition-colors'
                    if (booked) {
                        cls += ' border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed'
                        html += '<button type="button" class="' + cls + '" disabled>' + display + ' · Booked</button>'
                    } else if (selected) {
                        cls += ' border-green-600 bg-green-600 text-white'
                        html += '<button type="button" class="' + cls + '" data-time="' + timeStr + '">' + display + '</button>'
                    } else {
                        cls += ' border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                        html += '<button type="button" class="' + cls + '" data-time="' + timeStr + '">' + display + '</button>'
                    }
                }
            }
            html += '</div>'
            manageHistChangeTimeBody.innerHTML = html
        }

        // Open change appointment panel (inside details modal)
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.rec-book-change-appt')
            if (!btn) return
            var appointmentId = btn.getAttribute('data-appointment-id')
            var currentDoctorId = btn.getAttribute('data-doctor-id') || ''
            if (!appointmentId) return
            recBookPendingAppointmentId = appointmentId

            // Find current doctor object from doctors list
            recBookCurrentDoctor = null
            recBookPendingDoctorChanged = false
            recBookPendingDoctorId = currentDoctorId
            if (typeof doctors !== 'undefined' && Array.isArray(doctors)) {
                for (var _ci = 0; _ci < doctors.length; _ci++) {
                    if (String(doctors[_ci].user_id) === String(currentDoctorId)) {
                        recBookCurrentDoctor = doctors[_ci]
                        break
                    }
                }
            }

            // Hide history list, show change panel
            if (manageHistListSection) manageHistListSection.classList.add('hidden')
            if (manageHistChangePanel) manageHistChangePanel.classList.remove('hidden')

            // Populate doctor dropdown
            if (typeof doctors !== 'undefined' && Array.isArray(doctors)) {
                manageHistRenderDoctorDropdown(doctors, currentDoctorId)
            }

            // Reset state
            manageHistChangeSelectedDate = null
            manageHistChangeSelectedTime = null
            if (manageHistChangeConfirmBtn) manageHistChangeConfirmBtn.disabled = true

            // Init calendar
            manageHistChangeCalDate = new Date()
            manageHistChangeCalDate.setDate(1)
            manageHistLoadMonthAppts()
            if (manageHistChangeTimeBody) manageHistChangeTimeBody.innerHTML = '<div class="text-center text-[0.72rem] text-slate-400 py-4">Select a date to view time slots.</div>'
        })

        // Back button
        if (manageHistChangeBack) {
            manageHistChangeBack.addEventListener('click', function () {
                if (manageHistChangePanel) manageHistChangePanel.classList.add('hidden')
                if (manageHistListSection) manageHistListSection.classList.remove('hidden')
            })
        }

        // Doctor change → reload calendar + time slots
        if (manageHistChangeDoctor) {
            manageHistChangeDoctor.addEventListener('change', function () {
                manageHistChangeSelectedDate = null
                manageHistChangeSelectedTime = null
                if (manageHistChangeConfirmBtn) manageHistChangeConfirmBtn.disabled = true
                if (manageHistChangeTimeBody) manageHistChangeTimeBody.innerHTML = '<div class="text-center text-[0.72rem] text-slate-400 py-4">Select a date to view time slots.</div>'
                manageHistLoadMonthAppts()
            })
        }

        // Calendar navigation
        if (manageHistChangeCalPrev) {
            manageHistChangeCalPrev.addEventListener('click', function () {
                manageHistChangeCalDate.setMonth(manageHistChangeCalDate.getMonth() - 1)
                manageHistLoadMonthAppts()
            })
        }
        if (manageHistChangeCalNext) {
            manageHistChangeCalNext.addEventListener('click', function () {
                manageHistChangeCalDate.setMonth(manageHistChangeCalDate.getMonth() + 1)
                manageHistLoadMonthAppts()
            })
        }

        // Calendar date click
        if (manageHistChangeCalGrid) {
            manageHistChangeCalGrid.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-date]') : null
                if (!btn || btn.disabled) return
                manageHistChangeSelectedDate = btn.getAttribute('data-date') || ''
                manageHistChangeSelectedTime = null
                if (manageHistChangeConfirmBtn) manageHistChangeConfirmBtn.disabled = true
                manageHistRenderChangeCalendar()
                manageHistLoadTimeSlots(manageHistChangeSelectedDate)
            })
        }

        // Time slot click (delegated) — just update classes, no re-fetch
        if (manageHistChangeTimeBody) {
            manageHistChangeTimeBody.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-time]') : null
                if (!btn || btn.disabled) return
                var timeStr = btn.getAttribute('data-time') || ''
                if (!timeStr) return
                // Deselect previous
                var prev = manageHistChangeTimeBody.querySelector('button.bg-green-600')
                if (prev) {
                    prev.className = 'w-full rounded-lg border px-2 py-1.5 text-[0.68rem] font-semibold text-center transition-colors border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                }
                // Select clicked
                btn.className = 'w-full rounded-lg border px-2 py-1.5 text-[0.68rem] font-semibold text-center transition-colors border-green-600 bg-green-600 text-white'
                manageHistChangeSelectedTime = timeStr
                if (manageHistChangeConfirmBtn) manageHistChangeConfirmBtn.disabled = false
            })
        }

        // Confirm change
        if (manageHistChangeConfirmBtn) {
            manageHistChangeConfirmBtn.addEventListener('click', function () {
                var appointmentId = recBookPendingAppointmentId
                var doctorId = manageHistChangeDoctor ? manageHistChangeDoctor.value : ''
                var dateIso = manageHistChangeSelectedDate
                var timeStr = manageHistChangeSelectedTime
                if (!appointmentId || !doctorId || !dateIso || !timeStr) return
                manageHistChangeConfirmBtn.disabled = true
                manageHistChangeConfirmBtn.textContent = 'Updating…'

                apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(appointmentId), {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        doctor_id: doctorId,
                        appointment_datetime: dateIso + ' ' + timeStr + ':00'
                    })
                })
                .then(function (r) { return readResponse(r) })
                .then(function (result) {
                    if (result.ok) {
                        manageHistChangeConfirmBtn.textContent = 'Updated ✓'
                        if (manageHistChangeBack) manageHistChangeBack.click()
                        loadManageAppointments()
                        setTimeout(function () {
                            manageHistChangeConfirmBtn.textContent = 'Confirm Change'
                            manageHistChangeConfirmBtn.disabled = false
                        }, 1500)
                    } else {
                        manageHistChangeConfirmBtn.textContent = 'Failed — ' + (result.data && result.data.message ? result.data.message : 'Error')
                        setTimeout(function () {
                            manageHistChangeConfirmBtn.textContent = 'Confirm Change'
                            manageHistChangeConfirmBtn.disabled = false
                        }, 3000)
                    }
                })
                .catch(function () {
                    manageHistChangeConfirmBtn.textContent = 'Error — try again'
                    setTimeout(function () {
                        manageHistChangeConfirmBtn.textContent = 'Confirm Change'
                        manageHistChangeConfirmBtn.disabled = false
                    }, 3000)
                })
            })
        }

        // Toggle doctor picker
        if (recBookToggleDoctor) {
            recBookToggleDoctor.addEventListener('click', function () {
                if (!recBookDoctorListWrap) return
                var isHidden = recBookDoctorListWrap.classList.contains('hidden')
                if (isHidden) {
                    recBookDoctorListWrap.classList.remove('hidden')
                    if (recBookToggleDoctorCaret) recBookToggleDoctorCaret.textContent = '\u25B2'
                    recBookRenderDoctorList()
                } else {
                    recBookDoctorListWrap.classList.add('hidden')
                    if (recBookToggleDoctorCaret) recBookToggleDoctorCaret.textContent = '\u25BC'
                    // Revert to current doctor
                    recBookPendingDoctorChanged = false
                    recBookPendingDoctorId = recBookCurrentDoctor ? String(recBookCurrentDoctor.user_id) : recBookPendingDoctorId
                    recBookSelectedDate = null
                    recBookSelectedTime = null
                    recBookRenderDateOptions()
                    if (recBookTimeOptions) recBookTimeOptions.innerHTML = ''
                    if (recBookConfirmSlotBtn) recBookConfirmSlotBtn.disabled = true
                }
            })
        }

        // Render doctor list (filtered by allowedSpecs and having schedules for any day)
        function recBookRenderDoctorList() {
            if (!recBookDocPickerBody || !recBookCurrentDoctor) return
            var currentDoctorId = String(recBookCurrentDoctor.user_id)
            var allDoctors = (typeof doctors !== 'undefined' && Array.isArray(doctors))
                ? doctors.filter(function (d) {
                    var spec = String(d.specialization || '').trim().toLowerCase()
                    var allowedSpecs = ['general surgeon', 'obstetrician-gynecologist', 'obstetrician - gynecologist', 'internal medicine']
                    if (allowedSpecs.indexOf(spec) === -1) return false
                    var schedules = Array.isArray(d.doctor_schedules) ? d.doctor_schedules : []
                    return schedules.length > 0
                })
                : []
            if (!allDoctors.length) {
                recBookDocPickerBody.innerHTML = '<div class="text-[0.78rem] text-slate-400">No doctors available.</div>'
            } else {
                recBookDocPickerBody.innerHTML = allDoctors.map(function (d) {
                    var isCurrent = String(d.user_id) === currentDoctorId
                    return '<button type="button" data-doc-id="' + escapeHtml(String(d.user_id)) + '"' +
                        (isCurrent ? ' disabled' : '') +
                        ' class="rec-book-pick-doctor w-full text-left px-3 py-2.5 rounded-xl border ' +
                        (isCurrent ? 'border-green-300 bg-green-50 text-green-800 cursor-not-allowed opacity-60' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-300') +
                        '">' +
                        '<div class="font-semibold text-[0.78rem]">' + escapeHtml(doctorDisplayName(d)) + '</div>' +
                        '<div class="text-[0.68rem] text-slate-400">' + escapeHtml(doctorScheduleSummary(d)) + '</div>' +
                        (isCurrent ? '<div class="text-[0.65rem] text-green-600 mt-0.5">Currently assigned</div>' : '') +
                    '</button>'
                }).join('')
            }
        }

        // Doctor selected from picker → update date/time slots based on new doctor
        document.addEventListener('click', function (e) {
            var opt = e.target.closest('.rec-book-pick-doctor')
            if (!opt || opt.disabled) return
            var newDocId = opt.getAttribute('data-doc-id')
            if (!recBookPendingAppointmentId || !newDocId) return
            recBookPendingDoctorId = newDocId
            recBookSelectedDate = null
            recBookSelectedTime = null
            recBookPendingDoctorChanged = true

            // Find the selected doctor object
            var newDoctor = null
            if (typeof doctors !== 'undefined' && Array.isArray(doctors)) {
                for (var _di = 0; _di < doctors.length; _di++) {
                    if (String(doctors[_di].user_id) === String(newDocId)) {
                        newDoctor = doctors[_di]
                        break
                    }
                }
            }

            if (recBookCurrentDoctorLabel && newDoctor) {
                recBookCurrentDoctorLabel.textContent = 'Selected: ' + doctorDisplayName(newDoctor)
            }

            // Regenerate date options based on new doctor
            recBookRenderDateOptions(newDoctor)
            if (recBookTimeOptions) recBookTimeOptions.innerHTML = ''
            if (recBookConfirmSlotBtn) recBookConfirmSlotBtn.disabled = true
        })

        // Render date options (uses provided doctor or current doctor)
        function recBookRenderDateOptions(doctorObj) {
            if (!recBookDateOptions) return
            var activeDoctor = doctorObj || recBookCurrentDoctor
            if (!activeDoctor) {
                recBookDateOptions.innerHTML = '<span class="text-[0.72rem] text-slate-400">No schedule available.</span>'
                return
            }
            var schedules = Array.isArray(activeDoctor.doctor_schedules) ? activeDoctor.doctor_schedules : []
            if (!schedules.length) {
                recBookDateOptions.innerHTML = '<span class="text-[0.72rem] text-slate-400">No schedule available.</span>'
                return
            }
            var dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']
            var availableDays = {}
            schedules.forEach(function (s) {
                var dayNorm = normalizeDayKey(s.day_of_week)
                availableDays[dayNorm] = { start_time: s.start_time || '08:00', end_time: s.end_time || '17:00', schedule: s }
            })
            var html = ''
            var today = new Date()
            for (var d = 0; d < 14; d++) {
                var dateObj = new Date(today)
                dateObj.setDate(today.getDate() + d)
                var dateStr = dateObj.toISOString().split('T')[0]
                var dayIdx = dateObj.getDay()
                var dayName = dayNames[dayIdx]
                if (!availableDays[dayName]) continue
                var label = d === 0 ? 'Today' : (d === 1 ? 'Tomorrow' : dateStr)
                html += '<button type="button" class="rec-book-date-opt flex-shrink-0 px-3 py-1.5 rounded-lg border text-[0.72rem] font-medium border-slate-200 bg-white text-slate-700 hover:border-green-300 hover:bg-green-50" data-date="' + dateStr + '" data-day="' + dayName + '">' + label + '</button>'
            }
            recBookDateOptions.innerHTML = html || '<span class="text-[0.72rem] text-slate-400">No dates available.</span>'
        }

        // Date option clicked
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.rec-book-date-opt')
            if (!btn) return
            document.querySelectorAll('.rec-book-date-opt').forEach(function (b) {
                b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700')
                b.classList.add('border-slate-200', 'bg-white', 'text-slate-700')
            })
            btn.classList.remove('border-slate-200', 'bg-white', 'text-slate-700')
            btn.classList.add('border-green-500', 'bg-green-50', 'text-green-700')
            recBookSelectedDate = btn.getAttribute('data-date')
            recBookSelectedTime = null
            recBookRenderTimeOptions(btn.getAttribute('data-day'))
        })

        // Render time options based on doctor schedule
        function recBookRenderTimeOptions(dayName) {
            if (!recBookTimeOptions) return
            var activeDoctor = recBookPendingDoctorChanged ? null : recBookCurrentDoctor
            if (recBookPendingDoctorChanged) {
                if (typeof doctors !== 'undefined' && Array.isArray(doctors)) {
                    for (var _di2 = 0; _di2 < doctors.length; _di2++) {
                        if (String(doctors[_di2].user_id) === String(recBookPendingDoctorId)) {
                            activeDoctor = doctors[_di2]
                            break
                        }
                    }
                }
            }
            if (!activeDoctor) {
                recBookTimeOptions.innerHTML = '<span class="text-[0.72rem] text-slate-400">No doctor selected.</span>'
                return
            }
            var schedules = Array.isArray(activeDoctor.doctor_schedules) ? activeDoctor.doctor_schedules : []
            var schedule = null
            for (var _s = 0; _s < schedules.length; _s++) {
                if (normalizeDayKey(schedules[_s].day_of_week) === dayName) { schedule = schedules[_s]; break }
            }
            if (!schedule) {
                recBookTimeOptions.innerHTML = '<span class="text-[0.72rem] text-slate-400">No time slots available.</span>'
                return
            }
            var start = schedule.start_time || '08:00', end = schedule.end_time || '17:00'
            var startMin = parseInt(start.split(':')[0], 10) * 60 + parseInt(start.split(':')[1], 10)
            var endMin = parseInt(end.split(':')[0], 10) * 60 + parseInt(end.split(':')[1], 10)
            var html = ''
            for (var m = startMin; m < endMin; m += 30) {
                var hh = String(Math.floor(m / 60)).padStart(2, '0'), mm = String(m % 60).padStart(2, '0')
                var timeVal = hh + ':' + mm
                var h = parseInt(hh, 10)
                var display = (h === 0 ? '12' : (h > 12 ? h - 12 : h)) + ':' + mm + ' ' + (h < 12 ? 'AM' : h === 12 && parseInt(mm, 10) === 0 ? 'NN' : 'PM')
                html += '<button type="button" class="rec-book-time-opt px-3 py-1.5 rounded-lg border text-[0.7rem] font-medium border-slate-200 bg-white text-slate-700 hover:border-green-300 hover:bg-green-50" data-time="' + timeVal + '">' + display + '</button>'
            }
            recBookTimeOptions.innerHTML = html || '<span class="text-[0.72rem] text-slate-400">No time slots available.</span>'
        }

        // Time option clicked
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.rec-book-time-opt')
            if (!btn) return
            document.querySelectorAll('.rec-book-time-opt').forEach(function (b) {
                b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700')
                b.classList.add('border-slate-200', 'bg-white', 'text-slate-700')
            })
            btn.classList.remove('border-slate-200', 'bg-white', 'text-slate-700')
            btn.classList.add('border-green-500', 'bg-green-50', 'text-green-700')
            recBookSelectedTime = btn.getAttribute('data-time')
            if (recBookConfirmSlotBtn) recBookConfirmSlotBtn.disabled = false
        })

        // Confirm slot button → confirmAction with 3s delay
        if (recBookConfirmSlotBtn) {
            recBookConfirmSlotBtn.addEventListener('click', function () {
                if (!recBookSelectedDate || !recBookSelectedTime || !recBookPendingDoctorId) return
                if (recBookDocPickerOverlay) { recBookDocPickerOverlay.classList.add('hidden'); recBookDocPickerOverlay.classList.remove('flex') }
                var dateTimeStr = recBookSelectedDate + 'T' + recBookSelectedTime + ':00'
                var activeDoctor = recBookCurrentDoctor
                if (recBookPendingDoctorChanged && typeof doctors !== 'undefined' && Array.isArray(doctors)) {
                    for (var _di3 = 0; _di3 < doctors.length; _di3++) {
                        if (String(doctors[_di3].user_id) === String(recBookPendingDoctorId)) {
                            activeDoctor = doctors[_di3]
                            break
                        }
                    }
                }
                var docName = activeDoctor ? doctorDisplayName(activeDoctor) : 'selected'
                var msg = 'Change appointment to <strong>' + escapeHtml(docName) + '</strong> on ' + recBookSelectedDate + ' at ' + recBookSelectedTime + '?'
                confirmAction(msg, { okText: 'Confirm', delayMs: 3000 })
                    .then(function (confirmed) {
                        if (!confirmed) {
                            recBookPendingDoctorId = null
                            recBookSelectedDate = null
                            recBookSelectedTime = null
                            return
                        }
                        recBookDoChangeAppointment(dateTimeStr)
                    })
            })
        }

        // Reset picker state when closed
        function recBookResetPicker() {
            recBookPendingDoctorId = null
            recBookPendingAppointmentId = null
            recBookCurrentDoctor = null
            recBookPendingDoctorChanged = false
            recBookSelectedDate = null
            recBookSelectedTime = null
            if (recBookDoctorListWrap) recBookDoctorListWrap.classList.add('hidden')
            if (recBookToggleDoctorCaret) recBookToggleDoctorCaret.textContent = '\u25BC'
            if (recBookDocPickerSubtitle) recBookDocPickerSubtitle.textContent = 'Select a new date and time.'
            if (recBookCurrentDoctorLabel) recBookCurrentDoctorLabel.textContent = ''
            if (recBookDateOptions) recBookDateOptions.innerHTML = ''
            if (recBookTimeOptions) recBookTimeOptions.innerHTML = ''
            if (recBookConfirmSlotBtn) recBookConfirmSlotBtn.disabled = true
        }

        // Patch close/reset handlers
        if (recBookDocPickerClose) {
            recBookDocPickerClose.addEventListener('click', function () { recBookResetPicker(); if (recBookDocPickerOverlay) { recBookDocPickerOverlay.classList.add('hidden'); recBookDocPickerOverlay.classList.remove('flex') } })
        }
        if (recBookDocPickerOverlay) {
            recBookDocPickerOverlay.addEventListener('click', function (e) {
                if (e.target === recBookDocPickerOverlay) { recBookResetPicker(); recBookDocPickerOverlay.classList.add('hidden'); recBookDocPickerOverlay.classList.remove('flex') }
            })
        }

        // Confirm change → API call
        function recBookDoChangeAppointment(dateTimeStr) {
            if (!recBookPendingAppointmentId || !recBookPendingDoctorId) return
            if (typeof apiFetch !== 'function') {
                if (typeof showToast === 'function') showToast('API client unavailable.', 'error')
                return
            }
            var payload = {}
            if (recBookPendingDoctorChanged) {
                payload.doctor_id = parseInt(recBookPendingDoctorId, 10)
            }
            if (dateTimeStr) payload.appointment_datetime = dateTimeStr
            apiFetch("{{ url('/api/appointments') }}/" + encodeURIComponent(recBookPendingAppointmentId), {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(function (response) {
                return readResponse(response)
            })
            .then(function (result) {
                if (!result.ok) {
                    var msg = (result.data && result.data.message) ? result.data.message : 'Failed to update appointment.'
                    if (typeof showToast === 'function') showToast(msg, 'error')
                    return
                }
                if (typeof showToast === 'function') showToast('Appointment updated successfully.', 'success')
                recBookPendingAppointmentId = null
                recBookPendingDoctorId = null
                recBookCurrentDoctor = null
                recBookPendingDoctorChanged = false
                recBookSelectedDate = null
                recBookSelectedTime = null
                if (typeof loadManageAppointments === 'function') loadManageAppointments()
                if (manageHistoryPatientId && typeof loadManagePatientHistory === 'function') loadManagePatientHistory(manageHistoryPatientId)
            })
            .catch(function () {
                if (typeof showToast === 'function') showToast('Network error while updating appointment.', 'error')
            })
        }



        loadManageAppointments()

        // ── Reverb listener for appointment slot changes ──
        if (typeof window.Echo !== 'undefined' && window.Echo) {
            // Listen for all doctor appointments to refresh the manage list
            window.Echo.private('appointments.all')
                .listen('.appointment.updated', function (e) {
                    // Refresh manage appointments table
                    if (typeof loadManageAppointments === 'function') loadManageAppointments()
                    // Refresh manage calendar
                    if (typeof loadManageMonthAppointments === 'function') loadManageMonthAppointments()

                    // Refresh booking form calendar/slots if a doctor is currently selected
                    if (recBookCurrentDoctor && recBookCurrentDoctor.user_id) {
                        var docId = recBookCurrentDoctor.user_id
                        if (typeof loadDoctorSchedulesAndAvailability === 'function') {
                            loadDoctorSchedulesAndAvailability(docId, recBookSelectedDate || null)
                        } else if (typeof loadDoctorAppointments === 'function' && recBookSelectedDate) {
                            loadDoctorAppointments(docId, recBookSelectedDate)
                        }
                    }

                    // Refresh Change Appointment panel (inside details modal) if open
                    if (manageHistChangePanel && !manageHistChangePanel.classList.contains('hidden')) {
                        if (typeof manageHistLoadMonthAppts === 'function') manageHistLoadMonthAppts()
                        if (manageHistChangeSelectedDate && typeof manageHistLoadTimeSlots === 'function') {
                            manageHistLoadTimeSlots(manageHistChangeSelectedDate)
                        }
                    }
                });
        }
    })
</script>
</div>
