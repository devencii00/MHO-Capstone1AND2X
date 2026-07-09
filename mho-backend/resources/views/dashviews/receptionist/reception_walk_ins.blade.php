<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 mb-1">Walk-ins Management</h1>
        <p class="text-sm text-slate-500">Register walk-in patients and view walk-in history.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="grid grid-cols-2 border-b border-slate-200">
      <button id="receptionWalkInTabAccount" type="button" class="px-4 py-3 text-xs font-semibold text-white bg-green-500 border-b-2 border-green-600 rounded-tl-[18px]">
    Walk-in
</button>
<button id="receptionWalkInTabGuest" type="button" class="px-4 py-3 text-xs font-semibold text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200 rounded-tr-[18px]">
    Walk-ins History
</button>
    </div>

    <div id="receptionWalkInPanelGuest" class="hidden p-5 pt-4">
    <div class="flex items-center gap-2 mb-3">
        <div class="relative flex-1">
            <input id="receptionWalkInHistorySearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search patient or doctor">
        </div>
        <div class="relative">
            <input id="receptionWalkInHistoryServiceSearch" type="text" class="w-36 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="All services" autocomplete="off">
            <input id="receptionWalkInHistoryServiceId" type="hidden">
            <div id="receptionWalkInHistoryServiceResults" class="hidden absolute left-0 right-0 top-full mt-1 w-full rounded-lg border border-slate-200 bg-white shadow-sm max-h-64 overflow-y-auto overscroll-contain z-50"></div>
        </div>
        <select id="receptionWalkInHistorySort" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
            <option value="latest">Latest first</option>
            <option value="oldest">Oldest first</option>
        </select>
        <select id="receptionWalkInHistoryStatus" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
            <option value="">All statuses</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="no_show">No-show</option>
        </select>
        <button type="button" id="recWalkinsRefreshBtn" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
            <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
            Refresh
        </button>
    </div>
    <div id="receptionWalkInHistoryError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <div class="overflow-x-auto overflow-y-auto scrollbar-hidden" style="height:470px;">
            <table class="text-xs" style="min-width:720px;width:100%;table-layout:auto;">
                <thead class="bg-slate-50 text-slate-600 sticky top-0">
                    <tr>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Time</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Patient</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Service</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Doctor</th>
                        <th class="text-left px-3 py-2 font-semibold whitespace-nowrap">Status</th>
                        <th class="text-right px-3 py-2 font-semibold whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="receptionWalkInHistoryTableBody" class="divide-y divide-slate-100 bg-white"></tbody>
            </table>
        </div>
        <div id="receptionWalkInHistoryMeta" class="px-4 py-2 text-[0.72rem] text-slate-500 border-t border-slate-100 bg-slate-50">Loading today's walk-ins…</div>
        <div id="receptionWalkInPagination" class="px-4 py-2 border-t border-slate-50 bg-white flex items-center justify-center gap-1"></div>
    </div>
    </div>

<!-- Patient History Modal -->
<div id="receptionWalkInHistoryOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl h-[90vh] max-h-none rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex overflow-hidden">
        <!-- History list (left) -->
        <div class="w-1/2 border-r border-slate-200 flex flex-col min-h-0">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Patient History</div>
                    <div id="receptionWalkInHistorySubtitle" class="text-[0.72rem] text-slate-500">Loading…</div>
                </div>
                <button type="button" id="receptionWalkInHistoryClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div class="px-4 py-2 border-b border-slate-100 shrink-0 grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-[0.6rem] text-slate-500 mb-0.5">Date</label>
                    <input id="receptionWalkInHistDate" type="date" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>
                <div>
                    <label class="block text-[0.6rem] text-slate-500 mb-0.5">Status</label>
                    <select id="receptionWalkInHistStatus" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
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
                    <select id="receptionWalkInHistType" class="w-full rounded-md border border-slate-200 bg-white px-2 py-1 text-[0.7rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="">All</option>
                        <option value="walk_in">Walk In</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>
            </div>
            <div id="receptionWalkInHistBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>
            </div>
        </div>
        <!-- Detail panel (right) -->
        <div id="receptionWalkInHistDetailPanel" class="w-1/2 flex flex-col min-h-0 bg-slate-50/50">
            <div class="px-4 py-3 border-b border-slate-200 shrink-0 flex items-center justify-between bg-white">
                <div class="text-sm font-semibold text-slate-900">Appointment Details</div>
            </div>
            <div id="receptionWalkInHistDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>
            </div>
        </div>
    </div>
</div>

    <div id="receptionWalkInPanelAccount" class="p-5 pt-4">
        <div class="flex items-center gap-3 px-1 mb-4">
            <label class="relative inline-flex items-center cursor-pointer">
                <input id="receptionWalkInGuestToggle" type="checkbox" class="sr-only peer">
                <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:bg-green-500 peer-focus:ring-2 peer-focus:ring-green-200 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
            </label>
            <span class="text-xs font-medium text-slate-700">Guest Walk-In</span>
        </div>
    <div id="receptionWalkInAccountFormWrapper" class="rounded-2xl   p-4">
        <div id="receptionWalkInAccountError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
        <div id="receptionWalkInAccountSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

        <form id="receptionWalkInAccountForm" class="grid gap-3 grid-cols-1 md:grid-cols-3 items-start">
            <div class="min-w-0">
                <label for="reception_walkin_patient_id" class="block text-[0.7rem] text-slate-600 mb-1">Patient</label>
                <div class="relative">
                    <input id="reception_walkin_patient_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select patient">
                    <input id="reception_walkin_patient_id" type="hidden" required>
                    <button id="reception_walkin_patient_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100">
                        Browse
                    </button>
                </div>
                <div id="receptionWalkInPatientPreview" class="hidden mt-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.78rem] text-slate-700 break-words"></div>
            </div>
            <div class="min-w-0">
                <label for="reception_walkin_service_ids" class="block text-[0.7rem] text-slate-600 mb-1">Services</label>
                <div class="relative">
                    <input id="reception_walkin_service_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select service">
                    <input id="reception_walkin_service_ids" type="hidden">
                    <button id="reception_walkin_service_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100">
                        Browse
                    </button>
                </div>
                <div id="receptionWalkInSelectedServices" class="mt-2 rounded-lg border border-slate-200 bg-white px-3 py-2 min-h-[3rem] max-h-24 overflow-y-auto overscroll-contain">
                    <span id="receptionWalkInSelectedServicesEmpty" class="text-[0.78rem] text-slate-400">No selected services.</span>
                </div>
            </div>
            <div class="min-w-0">
                <label for="reception_walkin_doctor_id" class="block text-[0.7rem] text-slate-600 mb-1">Doctor</label>
                <div class="relative">
                    <input id="reception_walkin_doctor_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none disabled:cursor-not-allowed disabled:bg-slate-100" placeholder="Select doctor" disabled>
                    <input id="reception_walkin_doctor_id" type="hidden" required>
                    <button id="reception_walkin_doctor_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-60" disabled>
                        Browse
                    </button>
                </div>
                <div id="receptionWalkInDoctorPreview" class="hidden mt-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.78rem] text-slate-700 break-words"></div>
            </div>
            <div class="min-w-0">
                <div class="block text-[0.7rem] text-slate-600 mb-1">Last Walk-in visit</div>
                <div id="receptionWalkInPatientSummaryCard" class="rounded-xl border border-slate-200 bg-white px-3 py-3 min-h-[96px]">
                    <div id="receptionWalkInPatientSummaryEmpty" class="text-[0.75rem] text-slate-500">No patient selected.</div>
                    <div id="receptionWalkInPatientSummaryDetails" class="hidden space-y-1.5 text-[0.75rem] text-slate-700">
                        <div><span class="font-semibold text-slate-800">Last visit:</span> <span id="receptionWalkInPatientSummaryVisit">-</span></div>
                        <div><span class="font-semibold text-slate-800">Service inquired:</span> <span id="receptionWalkInPatientSummaryService">-</span></div>
                        <div><span class="font-semibold text-slate-800">Doctor:</span> <span id="receptionWalkInPatientSummaryDoctor">-</span></div>
                    </div>
                </div>
            </div>
            <div>
                <label for="reception_walkin_reason" class="block text-[0.7rem] text-slate-600 mb-1">Reason (optional)</label>
                <input id="reception_walkin_reason" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Reason for visit">
            </div>
            <div>
                <label for="reception_walkin_priority" class="block text-[0.7rem] text-slate-600 mb-1">Priority level (optional)</label>
                <select id="reception_walkin_priority" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">Select priority</option>
                    <option value="1">1 : Emergency</option>
                    <option value="2">2 : Priority</option>
                    <option value="5">5 : Regular</option>
                </select>
                <div id="receptionWalkInPriorityHelp" class="mt-1 text-[0.68rem] text-slate-500">
                    Reception manually assigns Emergency, Priority, or Regular based on the situation.
                </div>
            </div>

            <input id="reception_walkin_type" type="hidden" value="walk_in">

            <div class="md:col-span-3 flex justify-end">
                <button id="receptionWalkInAccountSubmit" type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                    <span id="receptionWalkInAccountSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span id="receptionWalkInAccountSubmitLabel">Create walk-in</span>
                </button>
            </div>
        </form>
    </div>

    <div id="receptionWalkInGuestForm" class="hidden mt-3 rounded-2xl  p-4">
        <div id="receptionGuestWalkInError" class="hidden mb-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
        <div id="receptionGuestWalkInSuccess" class="hidden mb-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>
        <div id="receptionGuestWalkInCreds" class="hidden mb-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700"></div>

       <form id="receptionGuestWalkInForm" class="grid gap-3 grid-cols-1 md:grid-cols-4 items-start">
            <div>
                <label for="reception_guest_firstname" class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                <input id="reception_guest_firstname" type="text" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="First name">
            </div>
            <div>
                <label for="reception_guest_middlename" class="block text-[0.7rem] text-slate-600 mb-1">Middle name (optional)</label>
                <input id="reception_guest_middlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Middle name">
            </div>
            <div>
                <label for="reception_guest_lastname" class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                <input id="reception_guest_lastname" type="text" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Last name">
            </div>
            <div>
                <label for="reception_guest_contact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number (optional)</label>
                <input id="reception_guest_contact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Mobile number">
            </div>
            <div class="min-w-0 md:col-span-2">
                <label for="reception_guest_service_ids" class="block text-[0.7rem] text-slate-600 mb-1">Services</label>
                <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div>
                <div class="relative">
                    <input id="reception_guest_service_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select service">
                    <input id="reception_guest_service_ids" type="hidden">
                    <button id="reception_guest_service_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100">
                        Browse
                    </button>
                </div>
                <div id="receptionGuestSelectedServices" class="mt-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.78rem] text-slate-700 max-h-24 overflow-y-auto overscroll-contain"></div>
            </div>
            <div class="min-w-0 md:col-span-2">
                <label for="reception_guest_doctor_id" class="block text-[0.7rem] text-slate-600 mb-1">Doctor</label>
                <div class="mb-1 text-[0.7rem] text-slate-500">&nbsp;</div>
                <div class="relative">
                    <input id="reception_guest_doctor_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none disabled:cursor-not-allowed disabled:bg-slate-100" placeholder="Select doctor" disabled>
                    <input id="reception_guest_doctor_id" type="hidden" required>
                    <button id="reception_guest_doctor_picker_btn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-60" disabled>
                        Browse
                    </button>
                </div>
                
                <div id="receptionGuestDoctorPreview" class="hidden mt-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.78rem] text-slate-700 break-words"></div>
            </div>
            <div class="md:col-span-2">
                <label for="reception_guest_reason" class="block text-[0.7rem] text-slate-600 mb-1">Reason (optional)</label>
                <input id="reception_guest_reason" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Reason for visit">
            </div>
            <div>
                <label for="reception_guest_priority_level" class="block text-[0.7rem] text-slate-600 mb-1">Priority level (optional)</label>
                <select id="reception_guest_priority_level" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">Select priority</option>
                    <option value="1">1 : Emergency</option>
                    <option value="2">2 : Priority</option>
                    <option value="5">5 : Regular</option>
                </select>
            </div>
<div class="flex items-end self-end">
    <button id="receptionGuestWalkInSubmit" type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
        <span id="receptionGuestWalkInSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
        <span id="receptionGuestWalkInSubmitLabel">Create guest walk-in</span>
    </button>
</div>
        </form>
    </div>

    </div>
</div>

<div id="receptionGuestLinkModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 px-4 py-6 overflow-y-auto">
    <div class="max-w-2xl mx-auto bg-white rounded-[18px] border border-slate-200 shadow-[0_10px_40px_rgba(15,23,42,0.20)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between gap-3">
            <div class="min-w-0">
                <div class="text-sm font-semibold text-slate-900">Guest walk-in public link</div>
                <div class="text-xs text-slate-500">Use the QR to let patients register themselves as guest walk-ins.</div>
            </div>
            <button id="receptionGuestLinkClose" type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">
                <x-lucide-x class="w-[20px] h-[20px]" />
            </button>
        </div>

        <div class="p-5 grid gap-4 grid-cols-1 md:grid-cols-2 items-start">
            <div>
                <div id="receptionGuestLinkModalError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                <div class="text-[0.7rem] text-slate-400 uppercase tracking-widest mb-1">Current active link</div>
                <div class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 break-words">
                    <a id="receptionGuestActiveLink" href="#" target="_blank" class="text-green-700 font-semibold hover:underline"></a>
                </div>

                <div class="mt-3 text-[0.7rem] text-slate-400 uppercase tracking-widest mb-1">Static link</div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 break-words">
                    <a id="receptionGuestStaticLink" href="#" target="_blank" class="text-green-700 font-semibold hover:underline"></a>
                </div>

                <div class="mt-3 text-[0.7rem] text-slate-400 uppercase tracking-widest mb-1">QR display page</div>
                <div class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 break-words">
                    <a id="receptionGuestQrPageLink" href="#" target="_blank" class="text-green-700 font-semibold hover:underline"></a>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button id="receptionGuestLinkGenerate" type="button" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">
                        <x-lucide-refresh-cw class="w-[18px] h-[18px]" />
                        Generate new link
                    </button>
                    <button id="receptionGuestLinkDeprecate" type="button" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-[0.78rem] font-semibold hover:bg-slate-50">
                        <x-lucide-ban class="w-[18px] h-[18px]" />
                        Deprecate current
                    </button>
                </div>
            </div>

            <div class="flex flex-col items-center">
                <div class="text-[0.7rem] text-slate-400 uppercase tracking-widest mb-2">QR Code</div>
                <div class="w-[280px] h-[280px] rounded-2xl border border-slate-200 bg-white overflow-hidden flex items-center justify-center">
                    <img id="receptionGuestQrImg" src="" alt="Guest walk-in QR" class="w-[280px] h-[280px] object-contain">
                </div>
                <div class="mt-2 text-[0.72rem] text-slate-500 text-center">
                    Patients can scan this to open the guest registration page.
                </div>
            </div>
        </div>
    </div>
</div>
<div id="receptionWalkInConfirmOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 transition-all duration-200">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-100 overflow-hidden">
        <!-- Header area with refined spacing and visual hierarchy -->
        <div class="px-5 pt-5 pb-3 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 flex-shrink-0">
                    <x-lucide-info class="w-5 h-5" />
                </div>
                <div class="flex-1">
                    <h3 id="receptionWalkInConfirmMessage" class="text-base font-semibold text-slate-800 tracking-tight">Confirm action</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Please review before confirming</p>
                </div>
            </div>
        </div>
        
        <!-- Body with clear, scannable details section -->
        <div class="px-5 py-4 bg-white">
            <div class="bg-slate-50/80 rounded-xl border border-slate-100 p-4 text-sm text-slate-700 leading-relaxed space-y-2">
                <div class="flex items-start gap-2 text-slate-600">
                    <x-lucide-alert-circle class="w-3.5 h-3.5 mt-0.5 text-slate-400 flex-shrink-0" />
                    <span class="text-slate-700">Are you sure you want to perform this action?</span>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-200 text-xs text-amber-600 bg-amber-50/50 -mx-2 px-2 py-1.5 rounded-md flex items-center gap-2">
                    <x-lucide-info class="w-3.5 h-3.5" />
                    <span>This action cannot be undone</span>
                </div>
            </div>
        </div>
        
        <!-- Footer with improved button hierarchy and spacing -->
        <div class="px-5 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="receptionWalkInConfirmCancel" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1">
                Cancel
            </button>
            <button type="button" id="receptionWalkInConfirmOk" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-sm transition-all duration-150">
                Confirm
            </button>
        </div>
    </div>
</div>

<div id="receptionWalkInReviewOverlay" class="hidden fixed inset-0 z-[55] bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 transition-all duration-200">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-100 overflow-hidden">
        <!-- Header section with icon and title - refined spacing -->
        <div class="px-5 pt-5 pb-3 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-600 shadow-sm flex-shrink-0">
                    <x-lucide-info class="w-5 h-5" />
                </div>
                <div class="flex-1 min-w-0">
                    <h3 id="receptionWalkInReviewTitle" class="text-base font-semibold text-slate-800 tracking-tight">Review Walk-in Details</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Please verify all information before confirming</p>
                </div>
            </div>
        </div>

        <!-- Content area - improved typography and visual hierarchy -->
        <div class="px-5 py-4 bg-white">
            <div id="receptionWalkInReviewContent" class="bg-slate-50/80 rounded-xl border border-slate-100 p-4 text-sm text-slate-700 leading-relaxed space-y-3">
                <!-- Dynamic content will be injected here -->
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
                <div class="mt-3 pt-2 border-t border-slate-200 text-xs text-amber-600 bg-amber-50/50 -mx-2 px-2 py-1.5 rounded-md flex items-center gap-2">
                    <x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />
                    <span>Please ensure all details are correct before confirming the walk-in appointment.</span>
                </div>
            </div>
        </div>

        <!-- Footer buttons - improved hierarchy -->
        <div class="px-5 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="receptionWalkInReviewCancel" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1">
                Cancel
            </button>
            <button type="button" id="receptionWalkInReviewOk" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-sm transition-all duration-150">
                Confirm Walk-in
            </button>
        </div>
    </div>
</div>

    <div id="receptionWalkInSelectorOverlay" class="hidden fixed inset-0 z-[80] bg-slate-900/50 items-center justify-center p-4">
        <div class="w-full max-w-5xl h-[88vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] overflow-hidden grid grid-cols-1 md:grid-cols-2">
            <div class="border-b md:border-b-0 md:border-r border-slate-200 flex flex-col min-h-0">
                <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-start justify-between gap-3">
                    <div>
                        <div id="receptionWalkInSelectorTitle" class="text-sm font-semibold text-slate-900">Select record</div>
                        <div id="receptionWalkInSelectorSubtitle" class="text-[0.72rem] text-slate-500">Recent records appear here.</div>
                    </div>
                    <button type="button" id="receptionWalkInSelectorClose" class="text-slate-400 hover:text-slate-600">
                        <x-lucide-x class="w-[20px] h-[20px]" />
                    </button>
                </div>
                <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                    <label for="receptionWalkInSelectorSearch" class="block text-[0.65rem] uppercase tracking-widest text-slate-400 mb-1">Search</label>
                    <input id="receptionWalkInSelectorSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search records">
                    <div id="receptionWalkInSelectorListLabel" class="mt-2 text-[0.7rem] text-slate-500">Latest records</div>
                </div>
                <div id="receptionWalkInSelectorListBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                    <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading records…</div>
                </div>
            </div>
            <div class="flex flex-col min-h-0 bg-slate-50/60">
                <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                    <div class="text-sm font-semibold text-slate-900">Details</div>
                    <div class="text-[0.72rem] text-slate-500">Review the selected record before confirming.</div>
                </div>
                <div id="receptionWalkInSelectorDetailBody" class="flex-1 overflow-y-auto p-4">
                    <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a record to view details.</div>
                </div>
                <div class="px-4 py-3 border-t border-slate-100 bg-white shrink-0 flex items-center justify-end gap-2">
                    <button type="button" id="receptionWalkInSelectorCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="button" id="receptionWalkInSelectorConfirm" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-60" disabled>Select</button>
                </div>
            </div>
        </div>
    </div>

<template id="receptionWalkInsIconX">
    <x-lucide-x class="w-[18px] h-[18px]" />
</template>

<script>
    window.receptionWalkInsIconX = '';
    document.addEventListener('DOMContentLoaded', function () {
        window.receptionWalkInsIconX = (function () {
            var tpl = document.getElementById('receptionWalkInsIconX')
            return tpl ? String(tpl.innerHTML || '').trim() : ''
        })()
        var tabAccountBtn = document.getElementById('receptionWalkInTabAccount')
        var tabGuestBtn = document.getElementById('receptionWalkInTabGuest')
        var panelAccount = document.getElementById('receptionWalkInPanelAccount')
        var panelGuest = document.getElementById('receptionWalkInPanelGuest')
        var headerTitle = document.getElementById('receptionWalkInHeaderTitle')
        var headerDesc = document.getElementById('receptionWalkInHeaderDesc')
function setWalkInTab(tab) {
    var isAccount = tab === 'account'
    if (panelAccount) panelAccount.classList.toggle('hidden', !isAccount)
    if (panelGuest) panelGuest.classList.toggle('hidden', isAccount)

    if (headerTitle) {
            headerTitle.textContent = isAccount ? 'Create walk-in' : 'Walk-in history'
        }
        if (headerDesc) {
            headerDesc.textContent = isAccount
                ? 'Register a walk-in for an existing patient or as a guest.'
                : 'View walk-in appointment records and statuses.'
        }

    if (tabAccountBtn) {
        // Active tab (Account)
        tabAccountBtn.classList.toggle('bg-green-500', isAccount)      // green background
        tabAccountBtn.classList.toggle('text-white', isAccount)       // White text
        tabAccountBtn.classList.toggle('border-b-2', isAccount)       // Bottom border indicator
        tabAccountBtn.classList.toggle('border-green-600', isAccount)  // Darker green border
        // Inactive tab
        tabAccountBtn.classList.toggle('bg-white', !isAccount)        // White background
        tabAccountBtn.classList.toggle('text-slate-900', !isAccount)  // Dark text
        tabAccountBtn.classList.toggle('hover:bg-slate-50', !isAccount) // Hover effect
        tabAccountBtn.classList.toggle('border-b-0', !isAccount)      // No border when inactive
        tabAccountBtn.classList.toggle('border-l', !isAccount)        // Left border separator
        tabAccountBtn.classList.toggle('border-slate-200', !isAccount) // Border color
    }
    if (tabGuestBtn) {
        // Active tab (Guest)
        tabGuestBtn.classList.toggle('bg-green-500', !isAccount)       // green background
        tabGuestBtn.classList.toggle('text-white', !isAccount)        // White text
        tabGuestBtn.classList.toggle('border-b-2', !isAccount)        // Bottom border indicator
        tabGuestBtn.classList.toggle('border-green-600', !isAccount)   // Darker green border
        // Inactive tab
        tabGuestBtn.classList.toggle('bg-white', isAccount)           // White background
        tabGuestBtn.classList.toggle('text-slate-900', isAccount)     // Dark text
        tabGuestBtn.classList.toggle('hover:bg-slate-50', isAccount)  // Hover effect
        tabGuestBtn.classList.toggle('border-b-0', isAccount)         // No border when inactive
        tabGuestBtn.classList.toggle('border-l', isAccount)           // Left border separator
        tabGuestBtn.classList.toggle('border-slate-200', isAccount)   // Border color
    }
}
        if (tabAccountBtn) tabAccountBtn.addEventListener('click', function () { setWalkInTab('account') })
        if (tabGuestBtn) tabGuestBtn.addEventListener('click', function () { setWalkInTab('guest') })
        setWalkInTab('account')
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var errorBox = document.getElementById('receptionWalkInHistoryError')
        var searchInput = document.getElementById('receptionWalkInHistorySearch')
        var serviceSearch = document.getElementById('receptionWalkInHistoryServiceSearch')
        var serviceIdInput = document.getElementById('receptionWalkInHistoryServiceId')
        var serviceResults = document.getElementById('receptionWalkInHistoryServiceResults')
        var sortSelect = document.getElementById('receptionWalkInHistorySort')
        var statusSelect = document.getElementById('receptionWalkInHistoryStatus')
        var tableBody = document.getElementById('receptionWalkInHistoryTableBody')
        var refreshBtn = document.getElementById('recWalkinsRefreshBtn')
        var searchTimer = null
        var services = []
        var servicesLoaded = false
        var servicesLoading = false
        var walkinCurrentPage = 1
        var walkinPerPage = 10
        var walkinVisibleCount = 5
        var walkinLastPage = 1
        var walkinTotal = 0

        function normalizeText(value) {
            return String(value || '').trim().toLowerCase()
        }

        function escapeHtml(input) {
            return String(input == null ? '' : input)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function formatLocalDateIso(date) {
            var value = date instanceof Date ? date : new Date()
            var y = value.getFullYear()
            var m = String(value.getMonth() + 1).padStart(2, '0')
            var d = String(value.getDate()).padStart(2, '0')
            return y + '-' + m + '-' + d
        }

        function safeIsoParts(raw) {
            var value = String(raw || '')
            if (!value) return { date: '', time: '' }
            if (value.indexOf('T') !== -1) value = value.replace('T', ' ')
            return {
                date: value.slice(0, 10),
                time: value.slice(11, 16)
            }
        }

        function formatTime12h(hhmm) {
            var value = String(hhmm || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(value)) return value || '-'
            var parts = value.split(':')
            var hour = parseInt(parts[0], 10)
            var minute = parts[1]
            var suffix = hour >= 12 ? 'PM' : 'AM'
            var hour12 = hour % 12
            if (!hour12) hour12 = 12
            return hour12 + ':' + minute + ' ' + suffix
        }

        function personName(person, fallback) {
            var parts = person ? [person.firstname, person.middlename, person.lastname] : []
            var name = parts.filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            return name || fallback || '-'
        }

        function ageFromBirthdate(value) {
            var raw = String(value || '').slice(0, 10)
            if (!/^\d{4}-\d{2}-\d{2}$/.test(raw)) return ''
            var birth = new Date(raw + 'T00:00:00')
            if (isNaN(birth.getTime())) return ''
            var now = new Date()
            var age = now.getFullYear() - birth.getFullYear()
            var monthDiff = now.getMonth() - birth.getMonth()
            if (monthDiff < 0 || (monthDiff === 0 && now.getDate() < birth.getDate())) age -= 1
            return age >= 0 ? String(age) : ''
        }

        function serviceSummary(appt) {
            var list = appt && Array.isArray(appt.services) ? appt.services : []
            var names = list.map(function (item) {
                return String(item && item.service_name ? item.service_name : '').trim()
            }).filter(Boolean)
            return names.length ? names.join(', ') : '-'
        }

        function statusText(appt) {
            var status = appt && appt.status ? String(appt.status) : ''
            if (!status) return '-'
            if (status === 'confirmed' && appt && appt.check_in_time) return 'checked-in'
            return status.replace(/_/g, ' ')
        }

        function statusBadgeClass(appt) {
            var status = normalizeText(appt && appt.status ? appt.status : '')
            if (status === 'completed') return 'border-emerald-200 bg-emerald-50 text-emerald-700'
            if (status === 'cancelled') return 'border-rose-200 bg-rose-50 text-rose-700'
            if (status === 'no_show') return 'border-slate-200 bg-slate-100 text-slate-600'
            if (status === 'pending') return 'border-amber-200 bg-amber-50 text-amber-700'
            return 'border-green-200 bg-green-50 text-green-700'
        }

        function showError(message) {
            if (!errorBox) return
            errorBox.textContent = message || ''
            errorBox.classList.toggle('hidden', !message)
        }

        function renderRows(rows) {
            if (!tableBody) return
            var list = Array.isArray(rows) ? rows : []
            if (!list.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-3 py-6 text-center text-[0.78rem] text-slate-500">No walk-in appointments found.</td></tr>'
                var pag = document.getElementById('receptionWalkInPagination')
                if (pag) pag.innerHTML = ''
                return
            }

            tableBody.innerHTML = list.map(function (appt) {
                var when = safeIsoParts(appt && appt.appointment_datetime ? appt.appointment_datetime : '')
                var patient = appt && appt.patient ? appt.patient : null
                var doctor = appt && appt.doctor ? appt.doctor : null
                var patientName = personName(patient, 'Patient #' + String(patient && patient.user_id != null ? patient.user_id : ''))
                var doctorName = personName(doctor, 'Doctor #' + String(doctor && doctor.user_id != null ? doctor.user_id : ''))
                var status = statusText(appt)

                return '' +
                    '<tr>' +
                        '<td class="px-3 py-2 text-slate-700 whitespace-nowrap">' + escapeHtml(formatTime12h(when.time)) + '</td>' +
                        '<td class="px-3 py-2 text-slate-700 min-w-[12rem] whitespace-nowrap">' + escapeHtml(patientName) + '</td>' +
                        '<td class="px-3 py-2 text-slate-700 min-w-[14rem] whitespace-nowrap">' + escapeHtml(serviceSummary(appt)) + '</td>' +
                        '<td class="px-3 py-2 text-slate-700 min-w-[12rem] whitespace-nowrap">' + escapeHtml(doctorName) + '</td>' +
                        '<td class="px-3 py-2 whitespace-nowrap"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + statusBadgeClass(appt) + '">' + escapeHtml(status) + '</span></td>' +
                        '<td class="text-right px-3 py-2 whitespace-nowrap">' +
                            '<button type="button" class="walkin-see-history-btn inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300" data-patient-id="' + escapeHtml(patient && patient.user_id != null ? patient.user_id : '') + '" data-patient-name="' + escapeHtml(patientName) + '">See Walk-ins History</button>' +
                        '</td>' +
                    '</tr>'
            }).join('')
            renderWalkinPagination()
        }

        function renderWalkinPagination() {
            var pag = document.getElementById('receptionWalkInPagination')
            if (!pag) return
            if (walkinTotal === 0) { pag.innerHTML = ''; return }
            var totalPages = walkinLastPage
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + walkinTotal + ' entries</span>'
            html += '<button type="button" class="' + (walkinCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-walkin-page="prev"' + (walkinCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var ws = Math.max(1, walkinCurrentPage - Math.floor(walkinVisibleCount / 2))
            var we = Math.min(ws + walkinVisibleCount - 1, totalPages)
            if (we - ws + 1 < walkinVisibleCount) ws = Math.max(1, we - walkinVisibleCount + 1)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === walkinCurrentPage ? btnActive : btnInactive) + '" data-walkin-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-walkin-page="next-window" title="Next set">…</button>' }
            html += '<button type="button" class="' + (walkinCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-walkin-page="next"' + (walkinCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            pag.innerHTML = html
            pag.querySelectorAll('button[data-walkin-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-walkin-page')
                    if (p === 'prev' && walkinCurrentPage > 1) { walkinCurrentPage-- }
                    else if (p === 'next' && walkinCurrentPage < totalPages) { walkinCurrentPage++ }
                    else if (p === 'next-window') { walkinCurrentPage = Math.min(we + 1, totalPages) }
                    else if (p !== 'prev' && p !== 'next') { walkinCurrentPage = parseInt(p, 10) }
                    else return
                    if (typeof loadHistory === 'function') loadHistory()
                })
            })
        }

        function wordPrefixMatch(value, query) {
            var source = normalizeText(value)
            var term = normalizeText(query)
            if (!term) return true
            if (!source) return false
            if (source.indexOf(term) === 0) return true
            return source.split(/\s+/).some(function (piece) { return piece.indexOf(term) === 0 })
        }

        function renderServiceResults() {
            if (!serviceResults || !serviceSearch) return
            var query = String(serviceSearch.value || '').trim()
            var filtered = (Array.isArray(services) ? services : []).filter(function (item) {
                return wordPrefixMatch(item && item.service_name ? item.service_name : '', query)
            }).slice(0, 25)

            if (!filtered.length) {
                serviceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No services found.</div>'
            } else {
                serviceResults.innerHTML = filtered.map(function (item) {
                    var id = item && item.service_id != null ? item.service_id : ''
                    var name = item && item.service_name ? item.service_name : ('Service #' + id)
                    return '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 text-[0.78rem] text-slate-700" data-service-id="' + escapeHtml(id) + '">' + escapeHtml(name) + '</button>'
                }).join('')
            }

            serviceResults.classList.remove('hidden')
        }

        function setServiceSelection(service) {
            if (serviceIdInput) {
                serviceIdInput.value = service && service.service_id != null ? String(service.service_id) : ''
            }
            if (serviceSearch) {
                serviceSearch.value = service && service.service_name ? String(service.service_name) : ''
                if (!service) serviceSearch.placeholder = 'All services'
            }
            if (serviceResults) serviceResults.classList.add('hidden')
        }

        function loadServices() {
            if (servicesLoaded || servicesLoading || typeof apiFetch !== 'function') return
            servicesLoading = true
            apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok) return
                    services = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    servicesLoaded = true
                })
                .catch(function () {})
                .finally(function () {
                    servicesLoading = false
                })
        }

        function loadHistory(page) {
            if (typeof apiFetch !== 'function') return
            page = page || walkinCurrentPage
            showError('')
            tableBody.innerHTML = '<tr><td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">Loading walk-in history…</td></tr>'
            var metaBox = document.getElementById('receptionWalkInHistoryMeta')
            if (metaBox) metaBox.textContent = 'Loading walk-in history…'

            var url = "{{ url('/api/appointments') }}" + '?per_page=10&page=' + page + '&appointment_type=walk_in'
            var order = sortSelect && sortSelect.value === 'oldest' ? 'oldest' : 'latest'
            url += '&order=' + encodeURIComponent(order)

            var todayIso = formatLocalDateIso(new Date())
            url += '&start_date=' + encodeURIComponent(todayIso)
            url += '&end_date=' + encodeURIComponent(todayIso)

            var search = searchInput ? normalizeText(searchInput.value) : ''
            if (search) url += '&search=' + encodeURIComponent(search)

            var serviceId = serviceIdInput && serviceIdInput.value ? parseInt(serviceIdInput.value, 10) : 0
            if (serviceId) url += '&service_id=' + encodeURIComponent(serviceId)

            var status = statusSelect && statusSelect.value ? String(statusSelect.value) : ''
            if (status) url += '&status=' + encodeURIComponent(status)

            apiFetch(url, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        showError((result.data && result.data.message) ? String(result.data.message) : 'Failed to load walk-in history.')
                        renderRows([])
                        var metaBox = document.getElementById('receptionWalkInHistoryMeta')
                        if (metaBox) metaBox.textContent = 'No walk-in history loaded.'
                        return
                    }

                    var rows = result.data && Array.isArray(result.data.data) ? result.data.data.slice() : (Array.isArray(result.data) ? result.data.slice() : [])

                    walkinCurrentPage = result.data.current_page || page
                    walkinLastPage = result.data.last_page || 1
                    walkinTotal = result.data.total || rows.length

                    renderRows(rows)
                    var metaBox = document.getElementById('receptionWalkInHistoryMeta')
                    metaBox.textContent = 'Showing page ' + walkinCurrentPage + ' of ' + walkinLastPage + ' (' + walkinTotal + ' walk-in(s) for today).'
                })
                .catch(function () {
                    showError('Network error while loading walk-in history.')
                    renderRows([])
                    var metaBox = document.getElementById('receptionWalkInHistoryMeta')
                    if (metaBox) metaBox.textContent = 'No walk-in history loaded.'
                })
        }

        // ── Patient history modal functions ──

        var historyPatientId = null
        var historyAppointments = []

        function openWalkinHistoryModal(patientId, patientName) {
            historyPatientId = patientId
            var subtitle = document.getElementById('receptionWalkInHistorySubtitle')
            if (subtitle) subtitle.textContent = patientName || 'Patient #' + patientId
            var body = document.getElementById('receptionWalkInHistBody')
            if (body) body.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading history…</div>'
            var detailBody = document.getElementById('receptionWalkInHistDetailBody')
            if (detailBody) detailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select an appointment to view details.</div>'
            var dateFilter = document.getElementById('receptionWalkInHistDate')
            if (dateFilter) dateFilter.value = ''
            var statusFilter = document.getElementById('receptionWalkInHistStatus')
            if (statusFilter) statusFilter.value = ''
            var typeFilter = document.getElementById('receptionWalkInHistType')
            if (typeFilter) typeFilter.value = ''
            var overlay = document.getElementById('receptionWalkInHistoryOverlay')
            if (overlay) {
                overlay.classList.remove('hidden')
                overlay.classList.add('flex')
            }
            loadWalkinPatientHistory(patientId)
        }

        function closeWalkinHistoryModal() {
            var overlay = document.getElementById('receptionWalkInHistoryOverlay')
            if (overlay) {
                overlay.classList.add('hidden')
                overlay.classList.remove('flex')
            }
            historyPatientId = null
            historyAppointments = []
        }

        function loadWalkinPatientHistory(patientId) {
            if (!patientId) return
            apiFetch("{{ url('/api/appointments') }}?per_page=15&patient_id=" + patientId, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () { return { ok: false, data: null } })
                })
                .then(function (result) {
                    if (!result.ok) {
                        var body = document.getElementById('receptionWalkInHistBody')
                        if (body) body.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Failed to load history.</div>'
                        return
                    }
                    historyAppointments = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var subtitle = document.getElementById('receptionWalkInHistorySubtitle')
                    if (subtitle) {
                        var first = historyAppointments[0]
                        var label = first && first.patient ? personName(first.patient) : ('Patient #' + patientId)
                        subtitle.textContent = label + ' - ' + historyAppointments.length + ' appointment(s)'
                    }
                    renderWalkinPatientHistory()
                })
                .catch(function () {
                    var body = document.getElementById('receptionWalkInHistBody')
                    if (body) body.innerHTML = '<div class="text-center text-[0.78rem] text-red-500 py-8">Network error loading history.</div>'
                })
        }

        function renderWalkinPatientHistory() {
            var body = document.getElementById('receptionWalkInHistBody')
            if (!body) return
            var filtered = historyAppointments.slice()

            var selDate = document.getElementById('receptionWalkInHistDate')
            var selStatus = document.getElementById('receptionWalkInHistStatus')
            var selType = document.getElementById('receptionWalkInHistType')
            var dateVal = selDate ? selDate.value : ''
            var statusVal = selStatus ? selStatus.value : ''
            var typeVal = selType ? selType.value : ''

            if (dateVal) filtered = filtered.filter(function (a) { return (a.appointment_datetime || '').slice(0, 10) === dateVal })
            if (statusVal) filtered = filtered.filter(function (a) { return String(a.status || '') === statusVal })
            if (typeVal) filtered = filtered.filter(function (a) { return String(a.appointment_type || '') === typeVal })

            filtered.sort(function (a, b) {
                return ((b.appointment_datetime || '') > (a.appointment_datetime || '')) ? 1 : -1
            })

            if (!filtered.length) {
                body.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No matching appointments found.</div>'
                return
            }

            var html = ''
            filtered.forEach(function (a) {
                var dt = a.appointment_datetime ? String(a.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
                var doctor = a.doctor ? personName(a.doctor, '-') : '-'
                var typeLabel = a.appointment_type ? String(a.appointment_type).replace(/_/g, ' ') : '-'
                html += '<div class="rounded-xl border border-slate-200 bg-white p-3 hover:border-green-200 transition-colors cursor-pointer walkin-history-row" data-appointment-id="' + a.appointment_id + '">' +
                    '<div class="flex items-center justify-between mb-1">' +
                        '<span class="text-[0.78rem] font-semibold text-slate-800">' + escapeHtml(dt) + '</span>' +
                        '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + statusBadgeClass(a) + '">' + escapeHtml(statusText(a)) + '</span>' +
                    '</div>' +
                    '<div class="text-[0.72rem] text-slate-500 mb-2">' + escapeHtml(doctor) + ' · ' + escapeHtml(typeLabel) + '</div>' +
                    '<button type="button" class="text-[0.7rem] font-semibold text-green-700 hover:text-green-800 walkin-history-details" data-appointment-id="' + a.appointment_id + '">View Details →</button>' +
                '</div>'
            })
            body.innerHTML = html

            body.querySelectorAll('.walkin-history-details').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation()
                    var apptId = this.getAttribute('data-appointment-id')
                    var found = historyAppointments.find(function (a) { return String(a.appointment_id) === apptId })
                    if (found) renderWalkinAppointmentDetail(found)
                })
            })
            body.querySelectorAll('.walkin-history-row').forEach(function (row) {
                row.addEventListener('click', function () {
                    var apptId = this.getAttribute('data-appointment-id')
                    var found = historyAppointments.find(function (a) { return String(a.appointment_id) === apptId })
                    if (found) renderWalkinAppointmentDetail(found)
                })
            })
        }

        function renderWalkinAppointmentDetail(appt) {
            var detailBody = document.getElementById('receptionWalkInHistDetailBody')
            if (!detailBody) return
            var dt = appt.appointment_datetime ? String(appt.appointment_datetime).replace('T', ' ').slice(0, 16) : '-'
            var tx = appt.transaction || null
            var services = Array.isArray(appt.services) ? appt.services : []
            var serviceNames = services.length ? services.map(function (s) { return s.service_name || s.name || '' }).filter(Boolean).join(', ') : '-'
            var amount = tx ? (tx.amount || 0) : 0
            var discountAmount = tx ? (tx.discount_amount || 0) : 0
            var discountType = tx ? (tx.discount_type || 'none') : 'none'
            var net = parseFloat(amount) - parseFloat(discountAmount)
            var diagnosis = tx ? (tx.diagnosis || '-') : '-'
            var treatment = tx ? (tx.treatment_notes || '-') : '-'
            var doctorName = appt.doctor ? personName(appt.doctor, '-') : '-'
            var typeLabel = appt.appointment_type ? String(appt.appointment_type).replace(/_/g, ' ') : '-'
            var reason = appt.reason_for_visit ? escapeHtml(appt.reason_for_visit) : '<span class="text-slate-400">-</span>'

            var html = '<div class="space-y-3">' +
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Appointment</div>' +
                    '<div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-[0.78rem]">' +
                        '<div class="text-slate-500">Date & Time</div>' +
                        '<div class="text-slate-800 font-medium">' + escapeHtml(dt) + '</div>' +
                        '<div class="text-slate-500">Doctor</div>' +
                        '<div class="text-slate-800 font-medium">' + escapeHtml(doctorName) + '</div>' +
                        '<div class="text-slate-500">Type</div>' +
                        '<div class="text-slate-800 font-medium">' + escapeHtml(typeLabel) + '</div>' +
                        '<div class="text-slate-500">Status</div>' +
                        '<div><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] border ' + statusBadgeClass(appt) + '">' + escapeHtml(statusText(appt)) + '</span></div>' +
                        '<div class="text-slate-500">Reason</div>' +
                        '<div class="text-slate-800">' + reason + '</div>' +
                    '</div>' +
                '</div>' +
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
                '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Diagnosis & Treatment</div>' +
                    '<div class="text-[0.78rem] space-y-2">' +
                        '<div><span class="text-slate-500">Diagnosis:</span><br><span class="text-slate-800">' + escapeHtml(diagnosis) + '</span></div>' +
                        '<div><span class="text-slate-500">Treatment Notes:</span><br><span class="text-slate-800">' + escapeHtml(treatment) + '</span></div>' +
                    '</div>' +
                '</div>' +
            '</div>'
            detailBody.innerHTML = html
        }

        loadServices()
        loadHistory()

        if (refreshBtn) {
            refreshBtn.addEventListener('click', function (e) {
                e.preventDefault()
                walkinCurrentPage = 1
                loadHistory()
            })
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                if (searchTimer) clearTimeout(searchTimer)
                searchTimer = setTimeout(function () {
                    walkinCurrentPage = 1
                    loadHistory()
                }, 250)
            })
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                walkinCurrentPage = 1
                loadHistory()
            })
        }

        if (statusSelect) {
            statusSelect.addEventListener('change', function () {
                walkinCurrentPage = 1
                loadHistory()
            })
        }

        if (serviceSearch) {
            serviceSearch.addEventListener('focus', function () {
                loadServices()
                renderServiceResults()
            })
            serviceSearch.addEventListener('input', function () {
                if (serviceIdInput && serviceIdInput.value) {
                    var chosen = services.find(function (item) {
                        return String(item && item.service_id) === String(serviceIdInput.value)
                    }) || null
                    var chosenName = chosen && chosen.service_name ? String(chosen.service_name) : ''
                    if (normalizeText(serviceSearch.value) !== normalizeText(chosenName)) {
                        setServiceSelection(null)
                        loadHistory()
                    }
                }
                loadServices()
                renderServiceResults()
            })
        }

        if (serviceResults) {
            serviceResults.addEventListener('click', function (e) {
                var button = e.target && e.target.closest ? e.target.closest('button[data-service-id]') : null
                if (!button) return
                var id = button.getAttribute('data-service-id') || ''
                var picked = services.find(function (item) {
                    return String(item && item.service_id) === String(id)
                }) || null
                setServiceSelection(picked)
                walkinCurrentPage = 1
                loadHistory()
            })
        }

        document.addEventListener('click', function (e) {
            if (!serviceResults || !serviceSearch) return
            if (serviceSearch.contains(e.target) || serviceResults.contains(e.target)) return
            serviceResults.classList.add('hidden')
        })

        // ── See History button delegation ──
        tableBody.addEventListener('click', function (e) {
            var btn = e.target.closest('.walkin-see-history-btn')
            if (btn) {
                var pid = btn.getAttribute('data-patient-id')
                var pname = btn.getAttribute('data-patient-name')
                if (pid) openWalkinHistoryModal(pid, pname)
            }
        })

        // ── Modal close ──
        var histOverlay = document.getElementById('receptionWalkInHistoryOverlay')
        var histClose = document.getElementById('receptionWalkInHistoryClose')
        if (histOverlay) {
            histOverlay.addEventListener('click', function (e) {
                if (e.target === histOverlay) closeWalkinHistoryModal()
            })
        }
        if (histClose) {
            histClose.addEventListener('click', closeWalkinHistoryModal)
        }

        // ── Modal filter listeners ──
        var histDate = document.getElementById('receptionWalkInHistDate')
        var histStatus = document.getElementById('receptionWalkInHistStatus')
        var histType = document.getElementById('receptionWalkInHistType')
        if (histDate) histDate.addEventListener('change', renderWalkinPatientHistory)
        if (histStatus) histStatus.addEventListener('change', renderWalkinPatientHistory)
        if (histType) histType.addEventListener('change', renderWalkinPatientHistory)
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var overlay = document.getElementById('receptionWalkInConfirmOverlay')
        var messageEl = document.getElementById('receptionWalkInConfirmMessage')
        var okBtn = document.getElementById('receptionWalkInConfirmOk')
        var cancelBtn = document.getElementById('receptionWalkInConfirmCancel')
        var reviewOverlay = document.getElementById('receptionWalkInReviewOverlay')
        var reviewTitleEl = document.getElementById('receptionWalkInReviewTitle')
        var reviewContentEl = document.getElementById('receptionWalkInReviewContent')
        var reviewOkBtn = document.getElementById('receptionWalkInReviewOk')
        var reviewCancelBtn = document.getElementById('receptionWalkInReviewCancel')
        var resolver = null
        var reviewResolver = null
        var timer = null
        var okDefaultHtml = okBtn ? okBtn.innerHTML : ''

        function close(result) {
            if (overlay) {
                overlay.classList.add('hidden')
                overlay.classList.remove('flex')
            }
            if (timer) {
                clearTimeout(timer)
                timer = null
            }
            if (okBtn) {
                okBtn.disabled = false
                okBtn.innerHTML = okDefaultHtml || 'Yes'
            }
            var r = resolver
            resolver = null
            if (typeof r === 'function') r(!!result)
        }

        function open(message, delayMs) {
            return new Promise(function (resolve) {
                if (!overlay || !messageEl || !okBtn || !cancelBtn) {
                    resolve(window.confirm(message || 'Are you sure?'))
                    return
                }
                if (timer) {
                    clearTimeout(timer)
                    timer = null
                }
                resolver = resolve
                messageEl.textContent = message || 'Are you sure?'
                var ms = delayMs != null ? parseInt(delayMs, 10) : 0
                if (isNaN(ms) || ms < 0) ms = 0
                okBtn.disabled = ms > 0
                if (ms > 0) {
                    okBtn.innerHTML = '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>Yes</span></span>'
                    timer = setTimeout(function () {
                        okBtn.disabled = false
                        okBtn.textContent = 'Yes'
                        timer = null
                    }, ms)
                } else {
                    okBtn.textContent = 'Yes'
                }
                overlay.classList.remove('hidden')
                overlay.classList.add('flex')
            })
        }

        function closeReview(result) {
            if (reviewOverlay) {
                reviewOverlay.classList.add('hidden')
                reviewOverlay.classList.remove('flex')
            }
            var r = reviewResolver
            reviewResolver = null
            if (typeof r === 'function') r(!!result)
        }

        function openReview(title, details) {
            return new Promise(function (resolve) {
                if (!reviewOverlay || !reviewContentEl || !reviewOkBtn || !reviewCancelBtn) {
                    resolve(window.confirm('Please review details before continuing.'))
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
                reviewResolver = resolve
                if (reviewTitleEl) reviewTitleEl.textContent = title || 'Review Details'
                var rows = []
                var source = details && typeof details === 'object' ? details : {}
                Object.keys(source).forEach(function (key) {
                    rows.push('<li><strong class="font-semibold text-slate-800">' + esc(key) + ':</strong> ' + esc(source[key]) + '</li>')
                })
                reviewContentEl.innerHTML = '<ul class="space-y-1">' + rows.join('') + '</ul>'
                reviewOverlay.classList.remove('hidden')
                reviewOverlay.classList.add('flex')
            })
        }

        if (okBtn) okBtn.addEventListener('click', function () { close(true) })
        if (cancelBtn) cancelBtn.addEventListener('click', function () { close(false) })
        if (overlay) overlay.addEventListener('click', function (e) { if (e.target === overlay) close(false) })
        if (reviewOkBtn) reviewOkBtn.addEventListener('click', function () { closeReview(true) })
        if (reviewCancelBtn) reviewCancelBtn.addEventListener('click', function () { closeReview(false) })
        if (reviewOverlay) reviewOverlay.addEventListener('click', function (e) { if (e.target === reviewOverlay) closeReview(false) })

        window.receptionWalkInConfirm = open
        window.receptionWalkInReview = openReview
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var guestForm = document.getElementById('receptionGuestWalkInForm')
        var guestErrorBox = document.getElementById('receptionGuestWalkInError')
        var guestSuccessBox = document.getElementById('receptionGuestWalkInSuccess')
        var guestCredsBox = document.getElementById('receptionGuestWalkInCreds')
        var guestServiceSearch = document.getElementById('reception_guest_service_search')
        var guestServiceIdsInput = document.getElementById('reception_guest_service_ids')
        var guestServiceResults = document.getElementById('receptionGuestServiceResults')
        var guestSelectedServicesEl = document.getElementById('receptionGuestSelectedServices')
        var guestDoctorSearch = document.getElementById('reception_guest_doctor_search')
        var guestDoctorIdInput = document.getElementById('reception_guest_doctor_id')
        var guestDoctorResults = document.getElementById('receptionGuestDoctorResults')
        var guestDoctorPreview = document.getElementById('receptionGuestDoctorPreview')
        var guestSubmitBtn = document.getElementById('receptionGuestWalkInSubmit')
        var guestSubmitSpinner = document.getElementById('receptionGuestWalkInSpinner')
        var guestSubmitLabel = document.getElementById('receptionGuestWalkInSubmitLabel')

        var guestServices = []
        var guestPopularServices = []
        var guestDoctors = []
        var guestSelectedServices = []
        var guestSelectedDoctor = null
        var guestLoadingServices = false
        var guestLoadingDoctors = false
        var guestPreviousDoctorId = 0
        var guestPreviousServiceIds = []
        var guestPreviousServiceIdSet = {}
        var guestNameTimer = null
        var guestFirstNameInput = document.getElementById('reception_guest_firstname')
        var guestMiddleNameInput = document.getElementById('reception_guest_middlename')
        var guestLastNameInput = document.getElementById('reception_guest_lastname')
        var guestServicePickerBtn = document.getElementById('reception_guest_service_picker_btn')
        var guestDoctorPickerBtn = document.getElementById('reception_guest_doctor_picker_btn')

        function updateGuestData() {
            window._guestData = {
                services: guestServices,
                doctors: guestDoctors,
                selectedServices: guestSelectedServices,
                selectedDoctor: guestSelectedDoctor,
                previousServiceIds: guestPreviousServiceIds,
                previousServiceIdSet: guestPreviousServiceIdSet,
                previousDoctorId: guestPreviousDoctorId,
                applyServices: function (services) {
                    guestSelectedServices = Array.isArray(services) ? services : []
                    syncGuestServiceHiddenInput()
                    renderGuestSelectedServices()
                    syncGuestDoctorEnabled()
                    setGuestDoctorSelection(null)
                    if (guestDoctorSearch) guestDoctorSearch.value = ''
                },
                applyDoctor: function (doctor) {
                    setGuestDoctorSelection(doctor)
                    if (guestDoctorSearch) guestDoctorSearch.value = doctorDisplayName(doctor)
                }
            }
        }
        updateGuestData()

        function showGuestError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showGuestSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function showGuestCreds(message) {
            if (!guestCredsBox) return
            guestCredsBox.textContent = message || ''
            if (message) {
                guestCredsBox.classList.remove('hidden')
            } else {
                guestCredsBox.classList.add('hidden')
            }
        }

        function setGuestSubmitting(isSubmitting) {
            if (guestSubmitBtn) guestSubmitBtn.disabled = !!isSubmitting
            if (guestSubmitSpinner) guestSubmitSpinner.classList.toggle('hidden', !isSubmitting)
            if (guestSubmitLabel) guestSubmitLabel.textContent = isSubmitting ? 'Creating…' : 'Create guest walk-in'
        }

        function escapeHtml(text) {
            return String(text || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function normalizeText(text) {
            return String(text || '')
                .toLowerCase()
                .replace(/\s+/g, ' ')
                .trim()
        }

        function isValidPersonName(value) {
            var v = String(value || '').trim()
            if (v === '') {
                return true
            }
            try {
                return /^[\p{L}\p{M}][\p{L}\p{M}\s.'\-\u00B7]*$/u.test(v)
            } catch (_) {
                return /^[A-Za-z][A-Za-z\s.'-]*$/.test(v)
            }
        }

        function normalizePersonName(value) {
            var s = String(value || '').trim()
            if (!s) return ''
            s = s.replace(/\s+/g, ' ')
            s = s.replace(/\s*([.'\-\u00B7])\s*/g, '$1')
            return s
        }

        function wordPrefixMatch(text, query) {
            var t = normalizeText(text)
            var q = normalizeText(query)
            if (!q) return true
            var words = t.split(' ')
            return words.some(function (w) { return w.indexOf(q) === 0 })
        }

        function serviceGroup(service) {
            if (!service) return ''
            var name = String(service.service_name || '').trim()
            if (!name) return ''
            var parts = name.split(':')
            var group = String(parts[0] || name).trim().toLowerCase()
            return group
        }

        function isWalkInExcludedService(service) {
            var g = serviceGroup(service)
            return g === 'obsterician - gynecologist' || g === 'obstetrician - gynecologist' || g === 'general surgeon'
        }

        function extractServiceCategory(serviceName) {
            var raw = String(serviceName || '').trim()
            if (!raw) return ''
            var parts = raw.split(':')
            var category = String(parts[0] || raw).trim().toLowerCase()
            return category
        }

        function specializationMatches(serviceCategory, doctorSpecialization) {
            var a = normalizeText(serviceCategory)
            var b = normalizeText(doctorSpecialization)
            if (!a || !b) return false
            return b.indexOf(a) !== -1 || a.indexOf(b) !== -1
        }

        function dayKeyFromDate(dateStr) {
            if (!dateStr) return ''
            var d = new Date(dateStr + 'T00:00:00')
            if (isNaN(d.getTime())) return ''
            var keys = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']
            return keys[d.getDay()] || ''
        }

        function localDateIso() {
            var now = new Date()
            var y = now.getFullYear()
            var m = String(now.getMonth() + 1).padStart(2, '0')
            var d = String(now.getDate()).padStart(2, '0')
            return y + '-' + m + '-' + d
        }

        function minutesFromHHMM(timeStr) {
            var t = String(timeStr || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return NaN
            var parts = t.split(':')
            return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10)
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
                if (String(s.day_of_week || '').toLowerCase() !== String(dayKey || '').toLowerCase()) return false
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

        function selectedGuestServiceIds() {
            return (guestSelectedServices || [])
                .map(function (s) { return parseInt(s && s.service_id != null ? s.service_id : 0, 10) })
                .filter(function (id) { return !!id && !isNaN(id) })
        }

        function syncGuestServiceHiddenInput() {
            if (!guestServiceIdsInput) return
            guestServiceIdsInput.value = selectedGuestServiceIds().join(',')
        }

        function syncGuestDoctorEnabled() {
            if (!guestDoctorSearch) return
            guestDoctorSearch.disabled = !(guestSelectedServices && guestSelectedServices.length)
            if (guestDoctorSearch.disabled) {
                guestDoctorSearch.value = ''
                setGuestDoctorSelection(null)
            }
        }

        function applyGuestHistory(payload) {
            guestPreviousDoctorId = payload && payload.previous_doctor_id ? parseInt(payload.previous_doctor_id, 10) : 0
            if (isNaN(guestPreviousDoctorId)) guestPreviousDoctorId = 0
            guestPreviousServiceIds = Array.isArray(payload && payload.previous_service_ids) ? payload.previous_service_ids.map(function (v) { return parseInt(v, 10) }).filter(function (v) { return !!v && !isNaN(v) }) : []
            guestPreviousServiceIds = Array.from(new Set(guestPreviousServiceIds.map(function (v) { return String(v) }))).map(function (v) { return parseInt(v, 10) })
            guestPreviousServiceIdSet = {}
            guestPreviousServiceIds.forEach(function (id) { guestPreviousServiceIdSet[String(id)] = true })
            updateGuestData()
        }

        function loadGuestHistoryForNames() {
            if (typeof apiFetch !== 'function') return Promise.resolve(null)
            var fn = guestFirstNameInput ? normalizePersonName(guestFirstNameInput.value) : ''
            var mn = guestMiddleNameInput ? normalizePersonName(guestMiddleNameInput.value) : ''
            var ln = guestLastNameInput ? normalizePersonName(guestLastNameInput.value) : ''
            if (guestFirstNameInput) guestFirstNameInput.value = fn
            if (guestMiddleNameInput) guestMiddleNameInput.value = mn
            if (guestLastNameInput) guestLastNameInput.value = ln
            if (!fn || !mn || !ln) {
                applyGuestHistory(null)
                return Promise.resolve(null)
            }
            var url = "{{ url('/api/walk-ins/guest/check-duplicates') }}" +
                '?firstname=' + encodeURIComponent(fn) +
                '&middlename=' + encodeURIComponent(mn) +
                '&lastname=' + encodeURIComponent(ln)
            return apiFetch(url, { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res || !res.ok) return null
                    applyGuestHistory(res.data)
                    return res.data
                })
                .catch(function () { return null })
        }

        function scheduleGuestHistoryLoad() {
            if (guestNameTimer) clearTimeout(guestNameTimer)
            guestNameTimer = setTimeout(function () {
                loadGuestHistoryForNames().then(function () {
                    if (guestServiceSearch && guestServiceResults && !guestServiceResults.classList.contains('hidden')) {
                        searchGuestServices(String(guestServiceSearch.value || ''))
                    }
                    if (guestDoctorSearch && guestDoctorResults && !guestDoctorResults.classList.contains('hidden')) {
                        searchGuestDoctors(String(guestDoctorSearch.value || ''))
                    }
                })
            }, 250)
        }

        function renderGuestSelectedServices() {
            if (!guestSelectedServicesEl) return
            var list = Array.isArray(guestSelectedServices) ? guestSelectedServices : []
            if (!list.length) {
                guestSelectedServicesEl.innerHTML = '<div class="text-[0.75rem] text-slate-500">No services selected.</div>'
                return
            }

            guestSelectedServicesEl.innerHTML = list.map(function (s) {
                var id = parseInt(s && s.service_id != null ? s.service_id : 0, 10)
                var name = String(s && s.service_name ? s.service_name : '').trim() || 'Service'
                var meta = []
                if (s && s.duration_minutes != null) meta.push(String(s.duration_minutes) + ' min')
                if (s && s.price != null) meta.push('₱' + String(s.price))
                return '' +
                    '<div class="flex items-center justify-between gap-2 py-1.5 border-b border-slate-200 last:border-0">' +
                        '<div class="min-w-0">' +
                            '<div class="text-[0.78rem] text-slate-800 font-semibold truncate">' + escapeHtml(name) + '</div>' +
                            '<div class="text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || '-') + '</div>' +
                        '</div>' +
                        '<button type="button" class="reception-guest-remove-service inline-flex items-center justify-center w-7 h-7 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50" data-service-id="' + escapeHtml(id) + '">' +
                            window.receptionWalkInsIconX +
                        '</button>' +
                    '</div>'
            }).join('')

            var buttons = guestSelectedServicesEl.querySelectorAll('.reception-guest-remove-service')
            Array.prototype.forEach.call(buttons, function (btn) {
                btn.addEventListener('click', function () {
                    var id = parseInt(btn.getAttribute('data-service-id') || '0', 10)
                    if (!id) return
                    guestSelectedServices = (guestSelectedServices || []).filter(function (s) {
                        return parseInt(s && s.service_id != null ? s.service_id : 0, 10) !== id
                    })
                    syncGuestServiceHiddenInput()
                    renderGuestSelectedServices()
                    syncGuestDoctorEnabled()
                    setGuestDoctorSelection(null)
                    if (guestDoctorSearch) guestDoctorSearch.value = ''
                    renderGuestServiceResults(guestServices.slice(0, 10))
                })
            })
        }

        function addGuestService(service) {
            if (!service || service.service_id == null) return
            var id = String(service.service_id)
            var exists = (guestSelectedServices || []).some(function (s) { return String(s && s.service_id) === id })
            if (exists) return
            guestSelectedServices = (guestSelectedServices || []).concat([service])
            syncGuestServiceHiddenInput()
            renderGuestSelectedServices()
            syncGuestDoctorEnabled()
            setGuestDoctorSelection(null)
            if (guestDoctorSearch) guestDoctorSearch.value = ''
            if (guestServiceSearch) guestServiceSearch.value = ''
            if (guestServiceResults) guestServiceResults.classList.add('hidden')
        }

        function renderGuestServiceResults(items) {
            if (!guestServiceResults) return
            var list = Array.isArray(items) ? items : []

            list = list.filter(function (s) { return !isWalkInExcludedService(s) })

            if (guestSelectedServices && guestSelectedServices.length) {
                var base = serviceGroup(guestSelectedServices[0])
                if (base) {
                    list = list.filter(function (s) { return serviceGroup(s) === base })
                }
            }

            if (guestPreviousServiceIds && guestPreviousServiceIds.length) {
                var order = {}
                guestPreviousServiceIds.forEach(function (id, idx) {
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

            if (!list.length) {
                guestServiceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No services found.</div>'
                guestServiceResults.classList.remove('hidden')
                return
            }

            guestServiceResults.innerHTML = list.slice(0, 12).map(function (s) {
                var name = String(s.service_name || '').trim() || 'Service'
                var meta = []
                if (s.duration_minutes != null) meta.push(String(s.duration_minutes) + ' min')
                if (s.price != null) meta.push('₱' + String(s.price))
                var desc = s.description != null ? String(s.description).trim() : ''
                var isLast = !!(guestPreviousServiceIdSet && guestPreviousServiceIdSet[String(s.service_id)])
                var tag = isLast
                    ? '<span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold bg-amber-50 text-amber-800 border border-amber-200">Last inquired</span>'
                    : ''
                return '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 border-b border-slate-100 last:border-0" data-service-id="' + escapeHtml(s.service_id) + '">' +
                    '<div class="text-[0.78rem] text-slate-800 font-semibold flex items-center justify-between gap-2">' +
                        '<span class="min-w-0 truncate">' + escapeHtml(name) + '</span>' +
                        tag +
                    '</div>' +
                    '<div class="text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || '-') + '</div>' +
                    (desc ? '<div class="mt-0.5 text-[0.72rem] text-slate-500">' + escapeHtml(desc) + '</div>' : '') +
                '</button>'
            }).join('')
            guestServiceResults.classList.remove('hidden')

            var buttons = guestServiceResults.querySelectorAll('button[data-service-id]')
            Array.prototype.forEach.call(buttons, function (btn) {
                btn.addEventListener('click', function () {
                    var id = btn.getAttribute('data-service-id') || ''
                    var chosen = (guestServices || []).find(function (s) { return String(s && s.service_id) === String(id) }) || null
                    if (!chosen) return
                    addGuestService(chosen)
                })
            })
        }

        function searchGuestServices(query) {
            var q = normalizeText(query)
            if (!q) {
                if (guestSelectedServices && guestSelectedServices.length) {
                    renderGuestServiceResults(guestServices || [])
                } else {
                    renderGuestServiceResults((guestPopularServices && guestPopularServices.length) ? guestPopularServices.slice() : (guestServices || []).slice(0, 12))
                }
                return
            }
            var filtered = (guestServices || []).filter(function (s) {
                return wordPrefixMatch(s && s.service_name ? s.service_name : '', q)
            })
            renderGuestServiceResults(filtered)
        }

        function doctorDisplayName(doctor) {
            if (!doctor) return ''
            var parts = [doctor.firstname, doctor.middlename, doctor.lastname].filter(function (v) { return String(v || '').trim() !== '' })
            var name = parts.join(' ').trim()
            if (!name) name = 'Doctor #' + (doctor.user_id != null ? doctor.user_id : '')
            return name
        }

        function setGuestDoctorSelection(doctor) {
            guestSelectedDoctor = doctor || null
            if (guestDoctorIdInput) guestDoctorIdInput.value = doctor && doctor.user_id != null ? String(doctor.user_id) : ''
            if (guestDoctorPreview) {
                if (!doctor) {
                    guestDoctorPreview.textContent = ''
                    guestDoctorPreview.classList.add('hidden')
                } else {
                    var label = 'Doctor: ' + doctorDisplayName(doctor)
                    if (doctor.specialization) label += ' • ' + String(doctor.specialization)
                    if (guestPreviousDoctorId && parseInt(doctor.user_id, 10) === guestPreviousDoctorId) label += ' • Last provider'
                    guestDoctorPreview.textContent = label
                    guestDoctorPreview.classList.remove('hidden')
                }
            }
            if (guestDoctorResults) guestDoctorResults.classList.add('hidden')
        }

        function renderGuestDoctorResults(items) {
            if (!guestDoctorResults) return
            var list = Array.isArray(items) ? items : []
            if (!guestSelectedServices || !guestSelectedServices.length) {
                guestDoctorResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">Select a service first.</div>'
                guestDoctorResults.classList.remove('hidden')
                return
            }
            if (!list.length) {
                guestDoctorResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No doctors found.</div>'
                guestDoctorResults.classList.remove('hidden')
                return
            }

            var dateStr = localDateIso()
            var dayKey = dayKeyFromDate(dateStr)
            var checkTime = new Date().toTimeString().slice(0, 5)

            var enriched = list.slice(0, 20).map(function (d) {
                var name = doctorDisplayName(d)
                var spec = d && d.specialization ? String(d.specialization) : ''
                var isDoctorAvailable = d && d.is_available !== false
                var hasSchedule = !!dayKey && hasScheduleAtTime(d, dayKey, dateStr, checkTime)
                var isSelectable = isDoctorAvailable && hasSchedule
                var tag = ''
                if (!isDoctorAvailable) tag = 'Unavailable'
                else if (!hasSchedule) tag = 'No schedule on this time'
                else if (guestPreviousDoctorId && parseInt(d.user_id, 10) === guestPreviousDoctorId) tag = 'Last provider'
                return { d: d, name: name, spec: spec, isSelectable: isSelectable, tag: tag }
            })

            enriched.sort(function (a, b) {
                if (a.isSelectable !== b.isSelectable) return a.isSelectable ? -1 : 1
                if ((a.tag === 'Last provider') !== (b.tag === 'Last provider')) return a.tag === 'Last provider' ? -1 : 1
                return normalizeText(a.name).localeCompare(normalizeText(b.name))
            })

            guestDoctorResults.innerHTML = enriched.slice(0, 12).map(function (x) {
                return '<button type="button" class="w-full text-left px-3 py-2 border-b border-slate-100 last:border-0 flex items-start justify-between gap-3 ' + (x.isSelectable ? 'hover:bg-slate-50' : 'bg-slate-50/60 cursor-not-allowed') + '" ' + (x.isSelectable ? '' : 'disabled') + ' data-doctor-id="' + escapeHtml(x.d.user_id) + '">' +
                    '<div class="min-w-0">' +
                        '<div class="text-[0.78rem] text-slate-800 font-semibold">' + escapeHtml('Dr. ' + x.name) + '</div>' +
                        '<div class="text-[0.72rem] text-slate-500">' + escapeHtml(x.spec || '-') + '</div>' +
                    '</div>' +
                    (x.tag ? '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold ' + (x.tag === 'Last provider' ? 'bg-green-500/10 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-500 border border-slate-200') + '">' + escapeHtml(x.tag) + '</span>' : '') +
                '</button>'
            }).join('')
            guestDoctorResults.classList.remove('hidden')

            var buttons = guestDoctorResults.querySelectorAll('button[data-doctor-id]')
            Array.prototype.forEach.call(buttons, function (btn) {
                btn.addEventListener('click', function () {
                    var id = btn.getAttribute('data-doctor-id') || ''
                    var chosen = (guestDoctors || []).find(function (d) { return String(d && d.user_id) === String(id) }) || null
                    if (!chosen) return
                    setGuestDoctorSelection(chosen)
                    if (guestDoctorSearch) guestDoctorSearch.value = doctorDisplayName(chosen)
                })
            })
        }

        function searchGuestDoctors(query) {
            var q = normalizeText(query)
            var baseService = guestSelectedServices && guestSelectedServices.length ? guestSelectedServices[0] : null
            var category = extractServiceCategory(baseService && baseService.service_name ? baseService.service_name : '')

            var list = (guestDoctors || []).slice()
            if (category) {
                list = list.filter(function (d) {
                    return specializationMatches(category, d && d.specialization ? d.specialization : '')
                })
            }
            if (q) {
                list = list.filter(function (d) {
                    return wordPrefixMatch(doctorDisplayName(d) + ' ' + (d && d.specialization ? d.specialization : ''), q)
                })
            }
            renderGuestDoctorResults(list.slice(0, 20))
        }

        function loadGuestServices() {
            if (guestLoadingServices || typeof apiFetch !== 'function') return
            guestLoadingServices = true
            apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) return
                    var raw = res.data && Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : [])
                    guestServices = raw || []
                    updateGuestData()
                })
                .catch(function () {})
                .finally(function () { guestLoadingServices = false })

            apiFetch("{{ url('/api/services-popular') }}?limit=10", { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) return
                    var raw = res.data && Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : [])
                    guestPopularServices = raw || []
                })
                .catch(function () {})
        }

        function loadGuestDoctors() {
            if (guestLoadingDoctors || typeof apiFetch !== 'function') return
            guestLoadingDoctors = true
            apiFetch("{{ url('/api/doctors') }}?per_page=15", { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) return
                    var raw = res.data && Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : [])
                    guestDoctors = raw || []
                    updateGuestData()
                })
                .catch(function () {})
                .finally(function () { guestLoadingDoctors = false })
        }

        loadGuestServices()
        loadGuestDoctors()
        renderGuestSelectedServices()
        syncGuestDoctorEnabled()
        scheduleGuestHistoryLoad()

        if (guestFirstNameInput) guestFirstNameInput.addEventListener('input', scheduleGuestHistoryLoad)
        if (guestMiddleNameInput) guestMiddleNameInput.addEventListener('input', scheduleGuestHistoryLoad)
        if (guestLastNameInput) guestLastNameInput.addEventListener('input', scheduleGuestHistoryLoad)

        if (guestServiceSearch) {
            guestServiceSearch.addEventListener('focus', function () {
                loadGuestServices()
                searchGuestServices(String(guestServiceSearch.value || ''))
            })
            guestServiceSearch.addEventListener('input', function () {
                loadGuestServices()
                searchGuestServices(String(guestServiceSearch.value || ''))
            })
        }

        if (guestDoctorSearch) {
            guestDoctorSearch.addEventListener('focus', function () {
                loadGuestDoctors()
                searchGuestDoctors(String(guestDoctorSearch.value || ''))
            })
            guestDoctorSearch.addEventListener('input', function () {
                loadGuestDoctors()
                searchGuestDoctors(String(guestDoctorSearch.value || ''))
            })
        }

        if (guestServicePickerBtn) {
            guestServicePickerBtn.addEventListener('click', function () {
                updateGuestData()
                if (window._openSelectorModal) window._openSelectorModal('service', 'guest')
            })
        }
        if (guestDoctorPickerBtn) {
            guestDoctorPickerBtn.addEventListener('click', function () {
                updateGuestData()
                if (window._openSelectorModal) window._openSelectorModal('doctor', 'guest')
            })
        }

        document.addEventListener('click', function (e) {
            var target = e && e.target ? e.target : null
            if (guestServiceResults && !guestServiceResults.classList.contains('hidden')) {
                if (!(guestServiceResults.contains(target) || (guestServiceSearch && guestServiceSearch.contains(target)))) {
                    guestServiceResults.classList.add('hidden')
                }
            }
            if (guestDoctorResults && !guestDoctorResults.classList.contains('hidden')) {
                if (!(guestDoctorResults.contains(target) || (guestDoctorSearch && guestDoctorSearch.contains(target)))) {
                    guestDoctorResults.classList.add('hidden')
                }
            }
        })

        if (guestForm) {
            guestForm.addEventListener('submit', function (e) {
                e.preventDefault()

                showGuestError('')
                showGuestSuccess('')
                showGuestCreds('')

                var firstNameInput = document.getElementById('reception_guest_firstname')
                var middleNameInput = document.getElementById('reception_guest_middlename')
                var lastNameInput = document.getElementById('reception_guest_lastname')
                var contactInput = document.getElementById('reception_guest_contact')
                var doctorInput = document.getElementById('reception_guest_doctor_id')
                var serviceIdsInput = document.getElementById('reception_guest_service_ids')
                var reasonInput = document.getElementById('reception_guest_reason')
                var priorityInput = document.getElementById('reception_guest_priority_level')

                var doctorId = doctorInput ? parseInt(doctorInput.value, 10) : 0
                var serviceIds = serviceIdsInput && serviceIdsInput.value ? String(serviceIdsInput.value).split(',').map(function (v) { return parseInt(v, 10) }).filter(function (v) { return !!v && !isNaN(v) }) : []
                if (!serviceIds.length) {
                    showGuestError('Services are required.')
                    return
                }
                if (!doctorId) {
                    showGuestError('Doctor is required.')
                    return
                }

                if (typeof apiFetch !== 'function') {
                    showGuestError('API client is not available.')
                    return
                }

                var body = {
                    doctor_id: doctorId,
                    service_ids: serviceIds
                }

                var firstName = firstNameInput ? normalizePersonName(firstNameInput.value) : ''
                var middleName = middleNameInput ? normalizePersonName(middleNameInput.value) : ''
                var lastName = lastNameInput ? normalizePersonName(lastNameInput.value) : ''
                var contact = contactInput ? String(contactInput.value || '').trim() : ''
                var reason = reasonInput ? String(reasonInput.value || '').trim() : ''
                var priorityLevel = priorityInput && priorityInput.value ? parseInt(priorityInput.value, 10) : null

                if (!firstName || !lastName) {
                    showGuestError('First name and last name are required.')
                    return
                }
                if (!isValidPersonName(firstName) || (middleName !== '' && !isValidPersonName(middleName)) || !isValidPersonName(lastName)) {
                    showGuestError('Name fields must contain letters only (accents allowed), plus hyphens, apostrophes, periods, and middle dots.')
                    return
                }
                if (firstNameInput) firstNameInput.value = firstName
                if (middleNameInput) middleNameInput.value = middleName
                if (lastNameInput) lastNameInput.value = lastName

                body.firstname = firstName
                body.middlename = middleName
                body.lastname = lastName
                if (contact) body.contact_number = contact
                if (reason) body.reason_for_visit = reason
                if (priorityLevel !== null && !isNaN(priorityLevel)) body.priority_level = priorityLevel

                var reviewDetails = {
                    'First Name': firstName,
                    'Middle Name': middleName,
                    'Last Name': lastName,
                    'Contact Number': contact || 'N/A',
                    'Doctor': guestSelectedDoctor ? doctorDisplayName(guestSelectedDoctor) : ('#' + String(doctorId)),
                    'Services': (guestSelectedServices || []).map(function (s) { return s && s.service_name ? s.service_name : '' }).filter(Boolean).join(', '),
                    'Reason': reason || 'N/A',
                    'Priority Level': (priorityLevel !== null && !isNaN(priorityLevel)) ? String(priorityLevel) : 'N/A'
                }

                function submitGuestWalkIn() {
                    setGuestSubmitting(true)
                    apiFetch("{{ url('/api/walk-ins/guest') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(body)
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
                                var message = 'Failed to create guest walk-in.'
                                if (result.data && result.data.message) {
                                    message = result.data.message
                                }
                                showGuestError(message)
                                return
                            }

                            showGuestSuccess('Walk-in successfuly created and currently on the queue.')
                            showGuestCreds('')

                            if (firstNameInput) firstNameInput.value = ''
                            if (middleNameInput) middleNameInput.value = ''
                            if (lastNameInput) lastNameInput.value = ''
                            if (contactInput) contactInput.value = ''
                            guestSelectedServices = []
                            syncGuestServiceHiddenInput()
                            renderGuestSelectedServices()
                            syncGuestDoctorEnabled()

                            if (guestServiceSearch) guestServiceSearch.value = ''
                            if (guestDoctorSearch) guestDoctorSearch.value = ''
                            setGuestDoctorSelection(null)
                            if (doctorInput) doctorInput.value = ''
                            if (reasonInput) reasonInput.value = ''
                            if (priorityInput) priorityInput.value = ''
                        })
                        .catch(function () {
                            showGuestError('Network error while creating guest walk-in.')
                        })
                        .finally(function () {
                            setGuestSubmitting(false)
                        })
                }
                var askReview = window.receptionWalkInReview
                var reviewPromise = (typeof askReview === 'function')
                    ? askReview('Review Guest Walk-in Details', reviewDetails)
                    : Promise.resolve(window.confirm('Please review details before submitting this guest walk-in.'))

                reviewPromise
                    .then(function (reviewOk) {
                        if (!reviewOk) return
                        return loadGuestHistoryForNames()
                            .then(function (dup) {
                                if (dup && dup.similar_in_queue) {
                                    var ask = window.receptionWalkInConfirm
                                    if (typeof ask === 'function') {
                                        return ask('A patient with similar details is already in current queue, would you still like to register this queue entry?', 3000)
                                            .then(function (confirmed) {
                                                if (!confirmed) return
                                                submitGuestWalkIn()
                                            })
                                    }
                                    if (!window.confirm('A patient with similar details is already in current queue, would you still like to register this queue entry?')) return
                                }
                                submitGuestWalkIn()
                            })
                            .catch(function () {
                                submitGuestWalkIn()
                            })
                    })
            })
        }
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var openBtn = document.getElementById('receptionGuestLinkBtn')
        var modal = document.getElementById('receptionGuestLinkModal')
        var closeBtn = document.getElementById('receptionGuestLinkClose')
        var errBox = document.getElementById('receptionGuestLinkModalError')
        var activeLinkEl = document.getElementById('receptionGuestActiveLink')
        var staticLinkEl = document.getElementById('receptionGuestStaticLink')
        var qrPageLinkEl = document.getElementById('receptionGuestQrPageLink')
        var qrImg = document.getElementById('receptionGuestQrImg')
        var generateBtn = document.getElementById('receptionGuestLinkGenerate')
        var deprecateBtn = document.getElementById('receptionGuestLinkDeprecate')

        var currentLinkId = null
        var currentActiveUrl = null
        var currentStaticUrl = null
        var currentQrUrl = null

        function showError(message) {
            if (!errBox) return
            errBox.textContent = message || ''
            errBox.classList.toggle('hidden', !message)
        }

        function setQrUrl(url) {
            if (!qrImg) return
            var u = String(url || '').trim()
            if (!u) {
                qrImg.src = ''
                return
            }
            qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=' + encodeURIComponent(u)
        }

        function setLink(el, url) {
            if (!el) return
            var u = String(url || '').trim()
            el.textContent = u || '-'
            el.href = u || '#'
            el.classList.toggle('pointer-events-none', !u)
            el.classList.toggle('text-slate-400', !u)
            el.classList.toggle('font-semibold', !!u)
        }

        function applyPayload(payload) {
            var link = payload && payload.link ? payload.link : null
            currentLinkId = link && link.link_id != null ? String(link.link_id) : null
            currentActiveUrl = payload && payload.active_url ? String(payload.active_url) : null
            currentStaticUrl = payload && payload.static_url ? String(payload.static_url) : null
            currentQrUrl = payload && payload.qr_url ? String(payload.qr_url) : null

            setLink(activeLinkEl, currentActiveUrl)
            setLink(staticLinkEl, currentStaticUrl)
            setLink(qrPageLinkEl, currentQrUrl)

            var qrTarget = currentActiveUrl || currentStaticUrl
            setQrUrl(qrTarget)

            if (deprecateBtn) deprecateBtn.disabled = !currentLinkId
        }

        function loadCurrent() {
            if (typeof apiFetch !== 'function') return
            showError('')
            apiFetch("{{ url('/api/guest-walk-in-links/current') }}", { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) {
                        showError((res.data && res.data.message) ? res.data.message : 'Failed to load current link.')
                        return
                    }
                    applyPayload(res.data)
                })
                .catch(function () {
                    showError('Network error while loading current link.')
                })
        }

        function generateNew() {
            if (typeof apiFetch !== 'function') return
            showError('')
            if (generateBtn) generateBtn.disabled = true
            apiFetch("{{ url('/api/guest-walk-in-links') }}", { method: 'POST' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) {
                        showError((res.data && res.data.message) ? res.data.message : 'Failed to generate new link.')
                        return
                    }
                    applyPayload(res.data)
                })
                .catch(function () {
                    showError('Network error while generating new link.')
                })
                .finally(function () {
                    if (generateBtn) generateBtn.disabled = false
                })
        }

        function deprecateCurrent() {
            if (!currentLinkId || typeof apiFetch !== 'function') return
            showError('')
            if (deprecateBtn) deprecateBtn.disabled = true
            apiFetch("{{ url('/api/guest-walk-in-links') }}/" + encodeURIComponent(currentLinkId) + "/deprecate", { method: 'POST' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                .then(function (res) {
                    if (!res.ok) {
                        showError((res.data && res.data.message) ? res.data.message : 'Failed to deprecate current link.')
                        return
                    }
                    applyPayload(res.data)
                })
                .catch(function () {
                    showError('Network error while deprecating link.')
                })
                .finally(function () {
                    if (deprecateBtn) deprecateBtn.disabled = !currentLinkId
                })
        }

        function openModal() {
            if (!modal) return
            modal.classList.remove('hidden')
            loadCurrent()
        }

        function closeModal() {
            if (!modal) return
            modal.classList.add('hidden')
        }

        if (openBtn) openBtn.addEventListener('click', openModal)
        if (closeBtn) closeBtn.addEventListener('click', closeModal)
        if (generateBtn) generateBtn.addEventListener('click', generateNew)
        if (deprecateBtn) deprecateBtn.addEventListener('click', deprecateCurrent)

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e && e.target === modal) closeModal()
            })
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal()
        })
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var accountPanel = document.getElementById('receptionWalkInPanelAccount')
        function accountQuery(selector) {
            return accountPanel ? accountPanel.querySelector(selector) : null
        }

        var form = accountQuery('#receptionWalkInAccountForm')
        var errorBox = accountQuery('#receptionWalkInAccountError')
        var successBox = accountQuery('#receptionWalkInAccountSuccess')
        var submitBtn = accountQuery('#receptionWalkInAccountSubmit')
        var submitSpinner = accountQuery('#receptionWalkInAccountSpinner')
        var submitLabel = accountQuery('#receptionWalkInAccountSubmitLabel')
        var patientSearch = accountQuery('#reception_walkin_patient_search')
        var patientPickerBtn = accountQuery('#reception_walkin_patient_picker_btn')
        var patientSelect = accountQuery('#reception_walkin_patient_id')
        var patientResults = accountQuery('#receptionWalkInPatientResults')
        var patientPreview = accountQuery('#receptionWalkInPatientPreview')
        var serviceSearch = accountQuery('#reception_walkin_service_search')
        var servicePickerBtn = accountQuery('#reception_walkin_service_picker_btn')
        var serviceIdsInput = accountQuery('#reception_walkin_service_ids')
        var serviceResults = accountQuery('#receptionWalkInServiceResults')
        var selectedServicesEl = accountQuery('#receptionWalkInSelectedServices')
        var doctorSearch = accountQuery('#reception_walkin_doctor_search')
        var doctorPickerBtn = accountQuery('#reception_walkin_doctor_picker_btn')
        var doctorSelect = accountQuery('#reception_walkin_doctor_id')
        var doctorResults = accountQuery('#receptionWalkInDoctorResults')
        var doctorPreview = accountQuery('#receptionWalkInDoctorPreview')
        var patientSummaryEmpty = accountQuery('#receptionWalkInPatientSummaryEmpty')
        var patientSummaryDetails = accountQuery('#receptionWalkInPatientSummaryDetails')
        var patientSummaryVisit = accountQuery('#receptionWalkInPatientSummaryVisit')
        var patientSummaryService = accountQuery('#receptionWalkInPatientSummaryService')
        var patientSummaryDoctor = accountQuery('#receptionWalkInPatientSummaryDoctor')
        var dateSelect = accountQuery('#reception_walkin_date_select')
        var dateInput = accountQuery('#reception_walkin_date')
        var dateLoadMore = accountQuery('#reception_walkin_date_load_more')
        var dateRangeHint = accountQuery('#reception_walkin_date_range_hint')
        var dateWrap = accountQuery('#receptionWalkInDateWrap')
        var dateTrigger = accountQuery('#receptionWalkInDateTrigger')
        var dateOverlay = accountQuery('#receptionWalkInDateOverlay')
        var dateGrid = accountQuery('#receptionWalkInDateGrid')
        var dateList = dateGrid
        var datePrevBtn = accountQuery('#receptionWalkInDatePrev')
        var dateNextBtn = accountQuery('#receptionWalkInDateNext')
        var dateMonthLabel = accountQuery('#receptionWalkInDateMonthLabel')
        var timeInput = accountQuery('#reception_walkin_time')
        var timeWrap = accountQuery('#receptionWalkInTimeWrap')
        var timeTrigger = accountQuery('#receptionWalkInTimeTrigger')
        var timeOverlay = accountQuery('#receptionWalkInTimeOverlay')
        var availableDaysEl = accountQuery('#reception_walkin_available_days')
        var timeSlotsEl = accountQuery('#reception_walkin_time_slots')
        var priorityInput = accountQuery('#reception_walkin_priority')
        var priorityHelp = accountQuery('#receptionWalkInPriorityHelp')
        var previousDoctorId = 0
        var previousServiceIds = []
        var previousServiceIdSet = {}
        var approvedVerificationType = ''
        var services = []
        var doctors = []
        var servicesLoaded = false
        var servicesLoading = false
        var servicesLoadError = ''
        var popularServices = []
        var popularServicesLoaded = false
        var popularServicesLoading = false
        var popularServicesLoadError = ''
        var doctorsLoaded = false
        var doctorsLoading = false
        var doctorsLoadError = ''
        var doctorSchedules = []
        var doctorAvailableDaySet = {}
        var doctorAppointments = []
        var selectedSlotStart = null
        var slotMinutes = 60
        var patientSearchTimer = null
        var selectedPatient = null
        var walkInServiceSearchPage = 1
        var walkInServiceSearchResults = []
        var walkInServiceSearchHasMore = false
        var walkInServiceSearchQuery = ''
        var walkInServiceSearchLoading = false
        var walkInPatientSearchPage = 1
        var walkInPatientSearchResults = []
        var walkInPatientSearchHasMore = false
        var walkInPatientSearchQuery = ''
        var walkInPatientSearchLoading = false
        var walkInDoctorSearchPage = 1
        var walkInDoctorSearchResults = []
        var walkInDoctorSearchHasMore = false
        var walkInDoctorSearchQuery = ''
        var walkInDoctorSearchLoading = false
        var selectedServices = []
        var selectedDoctor = null
        var dateCursorFirstIso = null
        var dateCursorLastIso = null
        var dateCursorIndex = 0
        var typeInput = accountQuery('#reception_walkin_type')
        var selectorOverlay = document.getElementById('receptionWalkInSelectorOverlay')
        var selectorCloseBtn = document.getElementById('receptionWalkInSelectorClose')
        var selectorTitle = document.getElementById('receptionWalkInSelectorTitle')
        var selectorSubtitle = document.getElementById('receptionWalkInSelectorSubtitle')
        var selectorSearch = document.getElementById('receptionWalkInSelectorSearch')
        var selectorListLabel = document.getElementById('receptionWalkInSelectorListLabel')
        var selectorListBody = document.getElementById('receptionWalkInSelectorListBody')
        var selectorDetailBody = document.getElementById('receptionWalkInSelectorDetailBody')
        var selectorCancelBtn = document.getElementById('receptionWalkInSelectorCancel')
        var selectorConfirmBtn = document.getElementById('receptionWalkInSelectorConfirm')
        var selectorState = {
            type: '',
            items: [],
            activeItem: null,
            stagedServices: [],
            searchTimer: null,
            searchSeq: 0,
            mode: 'account'
        }

        function getAppointmentType() {
            return typeInput && typeInput.value ? String(typeInput.value) : 'walk_in'
        }

        function setSubmitting(isSubmitting) {
            if (submitBtn) submitBtn.disabled = !!isSubmitting
            if (submitSpinner) submitSpinner.classList.toggle('hidden', !isSubmitting)
            if (submitLabel) submitLabel.textContent = isSubmitting ? 'Creating…' : 'Create walk-in'
        }

        function showError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function normalizeText(value) {
            return String(value || '').trim().toLowerCase()
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

        function patientDisplayName(patient) {
            if (!patient) return ''
            var name = [patient.firstname, patient.middlename, patient.lastname]
                .filter(function (v) { return String(v || '').trim() !== '' })
                .join(' ')
                .trim()
            if (!name) name = 'User #' + (patient.user_id != null ? patient.user_id : '')
            return name
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
                if (doctor.user_id != null) return 'Doctor #' + doctor.user_id
            }
            if (appointment.doctor_id != null) return 'Doctor #' + appointment.doctor_id
            return '-'
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
            if (!selectorListBody) return
            selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">' + escapeHtml(message || 'Loading records…') + '</div>'
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
                                '<div class="mt-1 text-[0.78rem] text-slate-500">#' + escapeHtml(patient.user_id) + '</div>' +
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

        function fetchWalkInPatients(query) {
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

        function fetchWalkInPatientsPage(query, page) {
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

        function fetchWalkInDoctorsPage(query, page) {
            if (typeof apiFetch !== 'function') return Promise.resolve({ data: [], hasMore: false })
            var params = 'per_page=10&page=' + page
            if (query) params += '&search=' + encodeURIComponent(query)
            return apiFetch("{{ url('/api/doctors') }}?" + params, { method: 'GET' })
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

        function renderSelectorPatientList() {
            if (!selectorListBody) return
            var rawQuery = String(selectorSearch ? selectorSearch.value : '').trim()
            var query = normalizeText(rawQuery)
            var searchActive = !!query

            if (searchActive) {
                walkInPatientSearchQuery = rawQuery
                walkInPatientSearchPage = 1
                setSelectorLoading('Searching patients…')
                fetchWalkInPatientsPage(rawQuery, 1).then(function (result) {
                    if (selectorState.type !== 'patient') return
                    if (String(selectorSearch ? selectorSearch.value : '').trim() !== rawQuery) return
                    walkInPatientSearchResults = result.data
                    walkInPatientSearchHasMore = result.hasMore
                    buildWalkInPatientList(result.data, rawQuery, result.hasMore)
                })
            } else {
                walkInPatientSearchQuery = ''
                walkInPatientSearchPage = 1
                setSelectorLoading('Loading patients…')
                fetchWalkInPatientsPage('', 1).then(function (result) {
                    if (selectorState.type !== 'patient') return
                    walkInPatientSearchResults = result.data
                    walkInPatientSearchHasMore = result.hasMore
                    buildWalkInPatientList(result.data, '', result.hasMore)
                })
            }
        }

        function buildWalkInPatientList(items, query, hasMore) {
            if (!selectorListBody) return
            selectorState.items = Array.isArray(items) ? items : []
            if (selectorListLabel) selectorListLabel.textContent = query ? 'Patient search results' : 'Latest patients'
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
                                '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || ('#' + String(patient.user_id))) + '</div>' +
                            '</div>' +
                            '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold border ' + (active ? 'border-green-200 bg-white text-green-700' : 'border-slate-200 bg-slate-50 text-slate-500') + '">' + (active ? 'Selected' : 'Recent') + '</span>' +
                        '</div>' +
                    '</button>'
            })
            if (hasMore) {
                html += '<button type="button" id="walkInPatientLoadMoreBtn" class="w-full text-center py-2.5 mt-1 text-[0.75rem] font-semibold text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg border border-dashed border-slate-200 transition-colors">Load more patients</button>'
            }
            selectorListBody.innerHTML = html
            selectorState.items = items
            Array.prototype.forEach.call(selectorListBody.querySelectorAll('.reception-selector-patient'), function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(btn.getAttribute('data-index') || '-1', 10)
                    selectorState.activeItem = selectorState.items[idx] || null
                    buildWalkInPatientList(selectorState.items, query, hasMore)
                    renderSelectorDetail()
                })
            })
            var loadMoreBtn = document.getElementById('walkInPatientLoadMoreBtn')
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    if (walkInPatientSearchLoading) return
                    walkInPatientSearchLoading = true
                    walkInPatientSearchPage++
                    var page = walkInPatientSearchPage
                    var originalText = loadMoreBtn.textContent
                    loadMoreBtn.textContent = 'Loading…'
                    loadMoreBtn.disabled = true
                    fetchWalkInPatientsPage(walkInPatientSearchQuery, page).then(function (result) {
                        if (result.data.length) {
                            walkInPatientSearchResults = walkInPatientSearchResults.concat(result.data)
                        }
                        walkInPatientSearchHasMore = result.hasMore
                        walkInPatientSearchLoading = false
                        buildWalkInPatientList(walkInPatientSearchResults, walkInPatientSearchQuery, walkInPatientSearchHasMore)
                    })
                })
            }
            renderSelectorDetail()
        }

        function walkInServiceSourceList() {
            var query = normalizeText(selectorSearch ? selectorSearch.value : '')
            var srcServices, srcPrevIds, srcPrevIdSet
            if (selectorState.mode === 'guest') {
                var gd = window._guestData || {}
                srcServices = Array.isArray(gd.services) ? gd.services : []
                srcPrevIds = Array.isArray(gd.previousServiceIds) ? gd.previousServiceIds : []
                srcPrevIdSet = gd.previousServiceIdSet || {}
            } else {
                srcServices = (services || []).slice()
                srcPrevIds = previousServiceIds || []
                srcPrevIdSet = previousServiceIdSet || {}
            }
            var list = srcServices.slice()
            var isWalkIn = selectorState.mode === 'guest' || getAppointmentType() === 'walk_in'
            if (isWalkIn) {
                list = list.filter(function (service) { return !isWalkInExcludedService(service) })
            }
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
            if (srcPrevIds && srcPrevIds.length) {
                var order = {}
                srcPrevIds.forEach(function (id, idx) { order[String(id)] = idx })
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

        function fetchWalkInServicesPage(query, page) {
            if (typeof apiFetch !== 'function') return Promise.resolve({ data: [], hasMore: false })
            var params = 'per_page=10&page=' + page
            if (query) params += '&search=' + encodeURIComponent(query)
            return apiFetch("{{ url('/api/services') }}?" + params, { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (res) {
                    if (!res.ok) return { data: [], hasMore: false }
                    var items = Array.isArray(res.data.data) ? res.data.data : []
                    var allowed = ['general medicine', 'pediatrics']
                    items = items.filter(function (s) {
                        return allowed.indexOf(normalizeText(s && s.service_name ? s.service_name : '')) !== -1
                    })
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
                walkInServiceSearchQuery = rawQuery
                walkInServiceSearchPage = 1
                setSelectorLoading('Searching services…')
                fetchWalkInServicesPage(rawQuery, 1).then(function (result) {
                    if (selectorState.type !== 'service') return
                    if (String(selectorSearch ? selectorSearch.value : '').trim() !== rawQuery) return
                    walkInServiceSearchResults = result.data
                    walkInServiceSearchHasMore = result.hasMore
                    buildSelectorServiceList(result.data, rawQuery, result.hasMore)
                })
            } else {
                walkInServiceSearchQuery = ''
                walkInServiceSearchPage = 1
                var list = walkInServiceSourceList()
                walkInServiceSearchResults = list
                walkInServiceSearchHasMore = false
                buildSelectorServiceList(list, '', false)
            }
        }

        function buildSelectorServiceList(items, query, hasMore) {
            if (!selectorListBody) return
            if (selectorListLabel) selectorListLabel.textContent = query ? 'Service search results' : 'Latest services'
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
                var prevIdSet = selectorState.mode === 'guest' ? (window._guestData ? window._guestData.previousServiceIdSet : {}) : (previousServiceIdSet || {})
                var isLast = !!(prevIdSet[String(service.service_id)])
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
                html += '<button type="button" id="walkInServiceLoadMoreBtn" class="w-full text-center py-2.5 mt-1 text-[0.75rem] font-semibold text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg border border-dashed border-slate-200 transition-colors">Load more services</button>'
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
            var loadMoreBtn = document.getElementById('walkInServiceLoadMoreBtn')
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    if (walkInServiceSearchLoading) return
                    walkInServiceSearchLoading = true
                    walkInServiceSearchPage++
                    var page = walkInServiceSearchPage
                    var originalText = loadMoreBtn.textContent
                    loadMoreBtn.textContent = 'Loading…'
                    loadMoreBtn.disabled = true
                    fetchWalkInServicesPage(walkInServiceSearchQuery, page).then(function (result) {
                        if (result.data.length) {
                            walkInServiceSearchResults = walkInServiceSearchResults.concat(result.data)
                        }
                        walkInServiceSearchHasMore = result.hasMore
                        walkInServiceSearchLoading = false
                        buildSelectorServiceList(walkInServiceSearchResults, walkInServiceSearchQuery, walkInServiceSearchHasMore)
                    })
                })
            }
            renderSelectorDetail()
        }

        function walkInDoctorSourceList() {
            var srcSelectedServices, srcDoctorList, srcPreviousDoctorId
            if (selectorState.mode === 'guest') {
                var gd = window._guestData || {}
                srcSelectedServices = Array.isArray(gd.selectedServices) ? gd.selectedServices : []
                srcDoctorList = Array.isArray(gd.doctors) ? gd.doctors : []
                srcPreviousDoctorId = gd.previousDoctorId || 0
            } else {
                srcSelectedServices = selectedServices || []
                srcDoctorList = doctors || []
                srcPreviousDoctorId = previousDoctorId || 0
            }
            if (!srcSelectedServices || !srcSelectedServices.length) {
                return { needsService: true, list: [] }
            }
            var query = normalizeText(selectorSearch ? selectorSearch.value : '')
            var isGuest = selectorState.mode === 'guest'
            var type = isGuest ? 'walk_in' : getAppointmentType()
            var dateStr = type === 'walk_in'
                ? localDateIso()
                : ((dateSelect && dateSelect.value) ? String(dateSelect.value) : localDateIso())
            var dayKey = dayKeyFromDate(dateStr)
            var checkTime = type === 'walk_in'
                ? new Date().toTimeString().slice(0, 5)
                : (selectedSlotStart ? String(selectedSlotStart).slice(0, 5) : '')
            var categories = (srcSelectedServices || [])
                .map(function (service) { return extractServiceCategory(service && service.service_name ? service.service_name : '') })
                .filter(function (category) { return !!category })
            var list = (srcDoctorList || []).filter(function (doctor) {
                var spec = doctor && doctor.specialization ? doctor.specialization : ''
                return categories.every(function (category) { return specializationMatches(category, spec) })
            })
            if (query) {
                list = list.filter(function (doctor) {
                    var spec = doctor && doctor.specialization ? doctor.specialization : ''
                    return wordPrefixMatch(doctorDisplayName(doctor) + ' ' + spec, query)
                })
            }
            var enriched = list.map(function (doctor) {
                var isDoctorAvailable = doctor && doctor.is_available !== false
                var hasSchedule = !!dayKey && hasScheduleAtTime(doctor, dayKey, dateStr, checkTime)
                var isSelectable = isDoctorAvailable && hasSchedule
                var tag = ''
                if (!isDoctorAvailable) tag = 'Unavailable'
                else if (!hasSchedule) tag = 'No schedule on this time'
                else if (srcPreviousDoctorId && parseInt(doctor.user_id, 10) === srcPreviousDoctorId) tag = 'Last provider'
                return { doctor: doctor, isSelectable: isSelectable, tag: tag }
            })
            enriched.sort(function (a, b) {
                if (a.isSelectable !== b.isSelectable) return a.isSelectable ? -1 : 1
                if ((a.tag === 'Last provider') !== (b.tag === 'Last provider')) return a.tag === 'Last provider' ? -1 : 1
                return normalizeText(doctorDisplayName(a.doctor)).localeCompare(normalizeText(doctorDisplayName(b.doctor)))
            })
            return { needsService: false, list: enriched.slice(0, 10) }
        }

        function walkInDoctorEnrichItems(rawDoctors) {
            var srcSelectedServices, srcPreviousDoctorId
            if (selectorState.mode === 'guest') {
                var gd = window._guestData || {}
                srcSelectedServices = Array.isArray(gd.selectedServices) ? gd.selectedServices : []
                srcPreviousDoctorId = gd.previousDoctorId || 0
            } else {
                srcSelectedServices = selectedServices || []
                srcPreviousDoctorId = previousDoctorId || 0
            }
            var isGuest = selectorState.mode === 'guest'
            var type = isGuest ? 'walk_in' : getAppointmentType()
            var dateStr = type === 'walk_in'
                ? localDateIso()
                : ((dateSelect && dateSelect.value) ? String(dateSelect.value) : localDateIso())
            var dayKey = dayKeyFromDate(dateStr)
            var checkTime = type === 'walk_in'
                ? new Date().toTimeString().slice(0, 5)
                : (selectedSlotStart ? String(selectedSlotStart).slice(0, 5) : '')
            var categories = (srcSelectedServices || [])
                .map(function (service) { return extractServiceCategory(service && service.service_name ? service.service_name : '') })
                .filter(function (category) { return !!category })
            var list = (rawDoctors || []).filter(function (doctor) {
                var spec = doctor && doctor.specialization ? doctor.specialization : ''
                return categories.every(function (category) { return specializationMatches(category, spec) })
            })
            var enriched = list.map(function (doctor) {
                var isDoctorAvailable = doctor && doctor.is_available !== false
                var hasSchedule = !!dayKey && hasScheduleAtTime(doctor, dayKey, dateStr, checkTime)
                var isSelectable = isDoctorAvailable && hasSchedule
                var tag = ''
                if (!isDoctorAvailable) tag = 'Unavailable'
                else if (!hasSchedule) tag = 'No schedule on this time'
                else if (srcPreviousDoctorId && parseInt(doctor.user_id, 10) === srcPreviousDoctorId) tag = 'Last provider'
                return { doctor: doctor, isSelectable: isSelectable, tag: tag }
            })
            enriched.sort(function (a, b) {
                if (a.isSelectable !== b.isSelectable) return a.isSelectable ? -1 : 1
                if ((a.tag === 'Last provider') !== (b.tag === 'Last provider')) return a.tag === 'Last provider' ? -1 : 1
                return normalizeText(doctorDisplayName(a.doctor)).localeCompare(normalizeText(doctorDisplayName(b.doctor)))
            })
            return enriched
        }

        function renderSelectorDoctorList() {
            if (!selectorListBody) return
            var rawQuery = String(selectorSearch ? selectorSearch.value : '').trim()
            var query = normalizeText(rawQuery)
            var searchActive = !!query

            var srcSelectedServices
            if (selectorState.mode === 'guest') {
                srcSelectedServices = Array.isArray(window._guestData && window._guestData.selectedServices) ? window._guestData.selectedServices : []
            } else {
                srcSelectedServices = selectedServices || []
            }
            var needsService = !srcSelectedServices || !srcSelectedServices.length

            if (needsService) {
                selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a service first to load matching doctors.</div>'
                renderSelectorDetail()
                return
            }

            if (searchActive) {
                walkInDoctorSearchQuery = rawQuery
                walkInDoctorSearchPage = 1
                setSelectorLoading('Searching doctors…')
                fetchWalkInDoctorsPage(rawQuery, 1).then(function (result) {
                    if (selectorState.type !== 'doctor') return
                    if (String(selectorSearch ? selectorSearch.value : '').trim() !== rawQuery) return
                    var enriched = walkInDoctorEnrichItems(result.data)
                    walkInDoctorSearchResults = enriched
                    walkInDoctorSearchHasMore = result.hasMore
                    buildWalkInDoctorList(enriched, rawQuery, result.hasMore)
                })
            } else {
                walkInDoctorSearchQuery = ''
                walkInDoctorSearchPage = 1
                setSelectorLoading('Loading doctors…')
                fetchWalkInDoctorsPage('', 1).then(function (result) {
                    if (selectorState.type !== 'doctor') return
                    var enriched = walkInDoctorEnrichItems(result.data)
                    walkInDoctorSearchResults = enriched
                    walkInDoctorSearchHasMore = result.hasMore
                    buildWalkInDoctorList(enriched, '', result.hasMore)
                })
            }
        }

        function buildWalkInDoctorList(items, query, hasMore) {
            if (!selectorListBody) return
            selectorState.items = Array.isArray(items) ? items : []
            if (selectorListLabel) selectorListLabel.textContent = query ? 'Doctor search results' : 'Latest doctors'
            if (!selectorState.items.length) {
                selectorListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No doctors found.</div>'
                renderSelectorDetail()
                return
            }
            var html = ''
            selectorState.items.forEach(function (entry, idx) {
                var doctor = entry.doctor
                var isActive = selectorState.activeItem && String(selectorState.activeItem.user_id) === String(doctor.user_id)
                html += '' +
                    '<button type="button" class="reception-selector-doctor w-full rounded-xl border px-3 py-3 text-left transition-colors ' + (entry.isSelectable ? (isActive ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white hover:border-green-200 hover:bg-slate-50') : 'border-slate-200 bg-slate-100/80') + '" data-index="' + idx + '" ' + (entry.isSelectable ? '' : 'disabled') + '>' +
                        '<div class="flex items-start justify-between gap-3">' +
                            '<div class="min-w-0">' +
                                '<div class="text-[0.8rem] font-semibold text-slate-900 truncate">' + escapeHtml('Dr. ' + doctorDisplayName(doctor)) + '</div>' +
                                '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(doctor.specialization || '-') + '</div>' +
                            '</div>' +
                            (entry.tag ? '<span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[0.65rem] font-semibold ' + (entry.tag === 'Last provider' ? 'border-green-200 bg-white text-green-700' : 'border-slate-200 bg-white text-slate-500') + '">' + escapeHtml(entry.tag) + '</span>' : '') +
                        '</div>' +
                    '</button>'
            })
            if (hasMore) {
                html += '<button type="button" id="walkInDoctorLoadMoreBtn" class="w-full text-center py-2.5 mt-1 text-[0.75rem] font-semibold text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg border border-dashed border-slate-200 transition-colors">Load more doctors</button>'
            }
            selectorListBody.innerHTML = html
            selectorState.items = items
            Array.prototype.forEach.call(selectorListBody.querySelectorAll('.reception-selector-doctor'), function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(btn.getAttribute('data-index') || '-1', 10)
                    selectorState.activeItem = selectorState.items[idx] ? selectorState.items[idx].doctor : null
                    buildWalkInDoctorList(selectorState.items, query, hasMore)
                    renderSelectorDetail()
                })
            })
            var loadMoreBtn = document.getElementById('walkInDoctorLoadMoreBtn')
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    if (walkInDoctorSearchLoading) return
                    walkInDoctorSearchLoading = true
                    walkInDoctorSearchPage++
                    var page = walkInDoctorSearchPage
                    var originalText = loadMoreBtn.textContent
                    loadMoreBtn.textContent = 'Loading…'
                    loadMoreBtn.disabled = true
                    fetchWalkInDoctorsPage(walkInDoctorSearchQuery, page).then(function (result) {
                        if (result.data.length) {
                            var enriched = walkInDoctorEnrichItems(result.data)
                            walkInDoctorSearchResults = walkInDoctorSearchResults.concat(enriched)
                        }
                        walkInDoctorSearchHasMore = result.hasMore
                        walkInDoctorSearchLoading = false
                        buildWalkInDoctorList(walkInDoctorSearchResults, walkInDoctorSearchQuery, walkInDoctorSearchHasMore)
                    })
                })
            }
            renderSelectorDetail()
        }

        function ensureWalkInServicesLoaded() {
            if (servicesLoaded && services.length) return Promise.resolve(services)
            if (servicesLoading) return new Promise(function (resolve) {
                var check = setInterval(function () {
                    if (servicesLoaded) { clearInterval(check); resolve(services) }
                }, 16)
            })
            if (typeof apiFetch !== 'function') return Promise.resolve([])
            return apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) return services || []
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    var allowed = ['general medicine', 'pediatrics']
                    services = raw.filter(function (s) {
                        return allowed.indexOf(normalizeText(s && s.service_name ? s.service_name : '')) !== -1
                    })
                    servicesLoaded = true
                    return services
                })
                .catch(function () { return services || [] })
        }

        function ensureWalkInDoctorsLoaded() {
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

        function openSelectorModal(type, mode) {
            selectorState.type = type
            selectorState.mode = mode || 'account'
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
                renderSelectorPatientList()
            } else if (type === 'service') {
                selectorState.stagedServices = Array.isArray(selectedServices) ? selectedServices.slice() : []
                if (selectorTitle) selectorTitle.textContent = 'Select Service/s'
                if (selectorSubtitle) selectorSubtitle.textContent = 'Choose one or more services from the latest records or search for another service.'
                if (selectorSearch) selectorSearch.placeholder = 'Search service name'
                if (selectorConfirmBtn) selectorConfirmBtn.textContent = 'Select Service/s'
                setSelectorOpen(true)
                setSelectorLoading('Loading services…')
                ensureWalkInServicesLoaded().then(function () {
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
                Promise.all([ensureWalkInServicesLoaded(), ensureWalkInDoctorsLoaded()]).then(function () {
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

        function formatAppointmentVisitLabel(value) {
            if (!value) return 'No walk-in visit yet.'
            var date = new Date(value)
            if (isNaN(date.getTime())) {
                return String(value || '').replace('T', ' ').slice(0, 16) || 'No walk-in visit yet.'
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

        function verificationTypeLabel(type) {
            var key = normalizeText(type || '')
            if (key === 'pwd') return 'PWD'
            if (key === 'pregnant') return 'Pregnant'
            if (key === 'senior') return 'Senior'
            if (key === 'none') return 'None'
            return ''
        }

        function priorityTypeLabel(level) {
            var value = parseInt(level, 10)
            if (value === 1) return 'Emergency'
            if (value === 2) return 'Priority'
            if (value === 5) return 'Regular'
            return ''
        }

        function verificationPriorityLevel(type) {
            return null
        }

        function syncPriorityInputState() {
            if (!priorityInput) return
            var verificationType = normalizeText(approvedVerificationType || '')
            priorityInput.disabled = false
            if (getAppointmentType() !== 'walk_in') {
                if (priorityHelp) priorityHelp.textContent = 'Priority can be adjusted manually for this appointment.'
                return
            }

            if (verificationType && verificationType !== 'none') {
                if (priorityHelp) {
                    priorityHelp.textContent = 'Verified patient type: ' + verificationTypeLabel(verificationType) + '. Priority is still assigned manually.'
                }
                return
            }

            if (priorityHelp) priorityHelp.textContent = 'Reception manually assigns Emergency, Priority, or Regular based on the situation.'
        }

        function loadApprovedPatientVerification(patientId) {
            approvedVerificationType = ''
            syncPriorityInputState()

            if (!patientId || typeof apiFetch !== 'function') return
            apiFetch("{{ url('/api/patient-verifications') }}?patient_id=" + encodeURIComponent(patientId) + "&status=approved&per_page=15", { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (res) {
                    if (!res.ok) return
                    var list = res.data && Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : [])
                    var latest = list && list.length ? list[0] : null
                    approvedVerificationType = latest && latest.type ? String(latest.type) : ''
                    syncPriorityInputState()
                })
                .catch(function () {})
        }

        function setPatientSelection(patient) {
            selectedPatient = patient || null
            if (patientSelect) patientSelect.value = patient && patient.user_id ? String(patient.user_id) : ''
            syncSelectionTriggers()
            previousDoctorId = 0
            previousServiceIds = []
            previousServiceIdSet = {}
            approvedVerificationType = ''
            if (priorityInput) priorityInput.value = ''
            syncPriorityInputState()
            setPatientSummaryCard(null)

            if (patientPreview) {
                if (!patient) {
                    patientPreview.textContent = ''
                    patientPreview.classList.add('hidden')
                } else {
                    var parts = []
                    var name = [patient.firstname, patient.middlename, patient.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                    if (!name) name = 'User #' + patient.user_id
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
                loadApprovedPatientVerification(String(patient.user_id))
            }
        }

        function loadPreviousProvider(patientId) {
            if (!patientId || typeof apiFetch !== 'function') return
            apiFetch("{{ url('/api/appointments') }}?patient_id=" + encodeURIComponent(patientId) + "&appointment_type=walk_in&per_page=1&order=latest", { method: 'GET' })
                .then(function (r) { return readResponse(r) })
                .then(function (res) {
                    if (!selectedPatient || String(selectedPatient.user_id || '') !== String(patientId)) return
                    if (!res.ok) {
                        setPatientSummaryCard({
                            lastVisit: 'No walk-in visit yet.',
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

                    if (doctorSearch && doctorResults && !doctorResults.classList.contains('hidden')) {
                        searchDoctors(String(doctorSearch.value || '').trim())
                    }
                    if (serviceSearch && serviceResults && !serviceResults.classList.contains('hidden')) {
                        searchServices(String(serviceSearch.value || '').trim())
                    }
                })
                .catch(function () {
                    if (!selectedPatient || String(selectedPatient.user_id || '') !== String(patientId)) return
                    setPatientSummaryCard({
                        lastVisit: 'No walk-in visit yet.',
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
                if (!name) name = 'User #' + p.user_id
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
                    if (patientSearch) patientSearch.value = patientDisplayName(chosen)
                })
            })
        }

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

        function serviceDisplayName(service) {
            if (!service) return ''
            return String(service.service_name || service.name || '').trim()
        }

        function serviceKey(service) {
            if (!service || service.service_id == null) return ''
            return String(service.service_id)
        }

        function serviceGroup(service) {
            if (!service) return ''
            var name = String(service.service_name || '').trim()
            if (!name) return ''
            var parts = name.split(':')
            var group = String(parts[0] || name).trim().toLowerCase()
            return group
        }

        function isWalkInExcludedService(service) {
            var g = serviceGroup(service)
            return g === 'obsterician - gynecologist' || g === 'obstetrician - gynecologist' || g === 'general surgeon'
        }

        function selectedServiceIds() {
            return (selectedServices || [])
                .map(function (s) { return parseInt(s && s.service_id != null ? s.service_id : 0, 10) })
                .filter(function (id) { return !!id && !isNaN(id) })
        }

        function syncServiceHiddenInput() {
            if (!serviceIdsInput) return
            var ids = selectedServiceIds()
            serviceIdsInput.value = ids.join(',')
        }

        function renderSelectedServices() {
            if (!selectedServicesEl) return
            var list = Array.isArray(selectedServices) ? selectedServices : []
            syncSelectionTriggers()
            if (!list.length) {
                var emptyMsg = document.getElementById('receptionWalkInSelectedServicesEmpty')
                if (emptyMsg) {
                    selectedServicesEl.innerHTML = ''
                    emptyMsg.style.display = ''
                    selectedServicesEl.appendChild(emptyMsg)
                } else {
                    selectedServicesEl.innerHTML = '<span id="receptionWalkInSelectedServicesEmpty" class="text-[0.78rem] text-slate-400">No selected services.</span>'
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
                            window.receptionWalkInsIconX +
                        '</button>' +
                    '</div>'
            }).join('')

            var buttons = selectedServicesEl.querySelectorAll('.reception-remove-service')
            Array.prototype.forEach.call(buttons, function (btn) {
                btn.addEventListener('click', function () {
                    var id = parseInt(btn.getAttribute('data-service-id') || '0', 10)
                    if (!id) return
                    selectedServices = (selectedServices || []).filter(function (s) {
                        return parseInt(s && s.service_id != null ? s.service_id : 0, 10) !== id
                    })
                    syncServiceHiddenInput()
                    renderSelectedServices()
                    filterDoctorsByService()
                    searchServices(String(serviceSearch && serviceSearch.value ? serviceSearch.value : '').trim())
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
        }

        function renderServiceResults(items) {
            if (!serviceResults) return
            var list = Array.isArray(items) ? items : []

            var type = getAppointmentType()
            if (type === 'walk_in') {
                list = list.filter(function (s) { return !isWalkInExcludedService(s) })
            }

            if (selectedServices && selectedServices.length) {
                var base = serviceGroup(selectedServices[0])
                if (base) {
                    list = list.filter(function (s) {
                        return serviceGroup(s) === base
                    })
                }
            }

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

            if (!list.length) {
                serviceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No services found.</div>'
                serviceResults.classList.remove('hidden')
                return
            }
            var html = ''
            list.slice(0, 12).forEach(function (s) {
                var name = String(s.service_name || '').trim() || 'Service'
                var isLast = !!(previousServiceIdSet && previousServiceIdSet[String(s.service_id)])
                var tag = isLast
                    ? '<span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold bg-amber-50 text-amber-800 border border-amber-200">Last inquired</span>'
                    : ''
                var meta = []
                if (s.duration_minutes != null) meta.push(String(s.duration_minutes) + ' min')
                if (s.price != null) meta.push('₱' + String(s.price))
                var desc = s.description != null ? String(s.description).trim() : ''
                html += '<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 border-b border-slate-100 last:border-0">' +
                    '<div class="text-[0.78rem] text-slate-800 font-semibold flex items-center justify-between gap-2">' +
                        '<span class="min-w-0 truncate">' + escapeHtml(name) + '</span>' +
                        tag +
                    '</div>' +
                    '<div class="text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || '-') + '</div>' +
                    (desc ? '<div class="mt-0.5 text-[0.72rem] text-slate-500">' + escapeHtml(desc) + '</div>' : '') +
                '</button>'
            })
            serviceResults.innerHTML = html
            serviceResults.classList.remove('hidden')

            var buttons = serviceResults.querySelectorAll('button')
            Array.prototype.forEach.call(buttons, function (btn, idx) {
                btn.addEventListener('click', function () {
                    var chosen = list[idx]
                    addService(chosen)
                    filterDoctorsByService()
                })
            })
        }

        function searchServices(query) {
            var q = normalizeText(query)
            var list = Array.isArray(services) ? services : []

            if (!q) {
                if (selectedServices && selectedServices.length) {
                    renderServiceResults(list)
                    return
                }

                var popularList = Array.isArray(popularServices) ? popularServices : []
                if (popularServicesLoaded && popularList.length) {
                    renderServiceResults(popularList.slice(0, 10))
                    return
                }

                if (servicesLoaded && list.length) {
                    renderServiceResults(list.slice(0, 10))
                    return
                }

                if (serviceResults) {
                    var isLoading = servicesLoading || popularServicesLoading
                    var hasLoadError = !isLoading && !servicesLoaded && !!servicesLoadError
                    var message = hasLoadError ? servicesLoadError : (isLoading ? 'Loading services…' : 'No services available.')
                    serviceResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">' + escapeHtml(message) + '</div>'
                    serviceResults.classList.remove('hidden')
                }
                return
            }
            var filtered = list.filter(function (s) {
                var name = normalizeText(s && s.service_name ? s.service_name : '')
                return wordPrefixMatch(name, q)
            })
            renderServiceResults(filtered)
        }

        function doctorDisplayName(doctor) {
            if (!doctor) return ''
            var parts = [doctor.firstname, doctor.middlename, doctor.lastname].filter(function (v) { return String(v || '').trim() !== '' })
            var name = parts.join(' ').trim()
            if (!name) name = 'Doctor #' + (doctor.user_id != null ? doctor.user_id : '')
            return name
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
                    var parts = []
                    var name = [doctor.firstname, doctor.middlename, doctor.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                    if (!name) name = 'Doctor #' + doctor.user_id
                    var type = getAppointmentType()
                    var dateStr = type === 'walk_in'
                        ? localDateIso()
                        : ((dateSelect && dateSelect.value) ? String(dateSelect.value) : localDateIso())
                    var dayKey = dayKeyFromDate(dateStr)
                    var checkTime = type === 'walk_in'
                        ? new Date().toTimeString().slice(0, 5)
                        : (selectedSlotStart ? String(selectedSlotStart).slice(0, 5) : '')
                    var hasSchedule = !!dayKey && hasScheduleAtTime(doctor, dayKey, dateStr, checkTime)
                    var categories = (selectedServices || [])
                        .map(function (s) { return extractServiceCategory(s && s.service_name ? s.service_name : '') })
                        .filter(function (c) { return !!c })
                    var spec = doctor && doctor.specialization ? doctor.specialization : ''
                    var matchesService = !categories.length || categories.every(function (c) { return specializationMatches(c, spec) })

                    parts.push('Name: ' + name)
                    if (doctor.specialization) parts.push('Specialization: ' + doctor.specialization)
                    if (previousDoctorId && parseInt(doctor.user_id, 10) === previousDoctorId) parts.push('Last provider')
                    parts.push('Availability: ' + ((doctor.is_available !== false && hasSchedule) ? 'Available' : 'Unavailable'))
                    if (categories.length) parts.push('Service match: ' + (matchesService ? 'Yes' : 'No'))
                    doctorPreview.textContent = parts.join(' • ')
                    doctorPreview.classList.remove('hidden')
                }
            }

            if (doctorResults) {
                doctorResults.innerHTML = ''
                doctorResults.classList.add('hidden')
            }

            var type = getAppointmentType()
            if (type !== 'walk_in') {
                clearAvailability()
                if (doctor && doctor.user_id) {
                    loadDoctorSchedulesAndAvailability(String(doctor.user_id), null)
                } else {
                    renderTimeSlots()
                }
            } else {
                renderTimeSlots()
            }
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
                if (String(s.day_of_week || '').toLowerCase() !== String(dayKey || '').toLowerCase()) return false
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

        function renderDoctorResults(items) {
            if (!doctorResults) return
            var list = Array.isArray(items) ? items : []
            if (!list.length) {
                doctorResults.innerHTML = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No doctors found.</div>'
                doctorResults.classList.remove('hidden')
                return
            }
            var type = getAppointmentType()
            var dateStr = ''
            if (type === 'walk_in') {
                dateStr = localDateIso()
            } else {
                dateStr = (dateSelect && dateSelect.value) ? String(dateSelect.value) : localDateIso()
            }
            var dayKey = dayKeyFromDate(dateStr)
            var checkTime = ''
            if (type === 'walk_in') {
                checkTime = new Date().toTimeString().slice(0, 5)
            } else if (selectedSlotStart) {
                checkTime = String(selectedSlotStart).slice(0, 5)
            }

            var enriched = list.map(function (d) {
                var name = [d.firstname, d.middlename, d.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                if (!name) name = 'Doctor #' + d.user_id
                var spec = d && d.specialization ? String(d.specialization) : ''
                var isDoctorAvailable = d && d.is_available !== false
                var hasSchedule = !!dayKey && hasScheduleAtTime(d, dayKey, dateStr, checkTime)
                var isSelectable = isDoctorAvailable && hasSchedule
                var tag = ''
                if (!isDoctorAvailable) tag = 'Unavailable'
                else if (!hasSchedule) tag = 'No schedule on this time'
                else if (previousDoctorId && parseInt(d.user_id, 10) === previousDoctorId) tag = 'Last provider'
                return {
                    d: d,
                    name: name,
                    spec: spec,
                    isSelectable: isSelectable,
                    tag: tag,
                }
            })

            enriched.sort(function (a, b) {
                if (a.isSelectable !== b.isSelectable) return a.isSelectable ? -1 : 1
                if ((a.tag === 'Last provider') !== (b.tag === 'Last provider')) return a.tag === 'Last provider' ? -1 : 1
                return normalizeText(a.name).localeCompare(normalizeText(b.name))
            })

            var html = ''
            enriched.forEach(function (x) {
                var d = x.d
                var meta = [x.spec].filter(Boolean).join(' • ')
                html += '<button type="button" class="w-full text-left px-3 py-2 border-b border-slate-100 last:border-0 flex items-start justify-between gap-3 ' + (x.isSelectable ? 'hover:bg-slate-50' : 'bg-slate-50/60 cursor-not-allowed') + '" ' + (x.isSelectable ? '' : 'disabled') + '>' +
                    '<div class="min-w-0">' +
                        '<div class="text-[0.78rem] text-slate-800 font-semibold">' + escapeHtml('Dr. ' + x.name) + '</div>' +
                        '<div class="text-[0.72rem] text-slate-500">#' + escapeHtml(d.user_id) + (meta ? ' • ' + escapeHtml(meta) : '') + '</div>' +
                    '</div>' +
                    (x.tag
                        ? '<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.65rem] font-semibold ' + (x.tag === 'Last provider' ? 'bg-green-500/10 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-500 border border-slate-200') + '">' + escapeHtml(x.tag) + '</span>'
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
                    if (doctorSearch) doctorSearch.value = doctorDisplayName(chosen)
                })
            })
        }

        function filterDoctorsByService() {
            var list = Array.isArray(doctors) ? doctors : []
            if (!selectedServices || !selectedServices.length) {
                if (doctorSearch) doctorSearch.disabled = true
                setDoctorSelection(null)
                if (doctorSearch) doctorSearch.value = ''
                syncSelectionTriggers()
                if (doctorResults) doctorResults.classList.add('hidden')
                return
            }
            var categories = (selectedServices || [])
                .map(function (s) { return extractServiceCategory(s && s.service_name ? s.service_name : '') })
                .filter(function (c) { return !!c })

            var filtered = list.filter(function (d) {
                var spec = d && d.specialization ? d.specialization : ''
                return categories.every(function (c) { return specializationMatches(c, spec) })
            })
            if (doctorSearch) doctorSearch.disabled = false
            if (filtered.length === 1) {
                var candidate = filtered[0]
                var type = getAppointmentType()
                var dateStr = type === 'walk_in'
                    ? localDateIso()
                    : ((dateSelect && dateSelect.value) ? String(dateSelect.value) : localDateIso())
                var dayKey = dayKeyFromDate(dateStr)
                var checkTime = type === 'walk_in' ? new Date().toTimeString().slice(0, 5) : (selectedSlotStart ? String(selectedSlotStart).slice(0, 5) : '')
                var isSelectable = candidate && candidate.is_available !== false && !!dayKey && hasScheduleAtTime(candidate, dayKey, dateStr, checkTime)
                if (isSelectable) {
                    setDoctorSelection(candidate)
                    if (doctorSearch) doctorSearch.value = doctorDisplayName(candidate)
                } else {
                    setDoctorSelection(null)
                    if (doctorSearch) doctorSearch.value = ''
                }
            } else {
                if (selectedDoctor) {
                    var stillOk = filtered.some(function (d) { return String(d.user_id) === String(selectedDoctor.user_id) })
                    if (!stillOk) {
                        setDoctorSelection(null)
                        if (doctorSearch) doctorSearch.value = ''
                    }
                }
            }
            syncSelectionTriggers()
        }

        if (selectorSearch) {
            selectorSearch.addEventListener('input', function () {
                if (selectorState.searchTimer) clearTimeout(selectorState.searchTimer)
                if (selectorState.type === 'patient') {
                    selectorState.searchTimer = window.setTimeout(function () {
                        renderSelectorPatientList()
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
                    if (selectorState.mode === 'guest') return
                    if (!selectorState.activeItem) return
                    setPatientSelection(selectorState.activeItem)
                    closeSelectorModal()
                    return
                }
                if (selectorState.type === 'service') {
                    if (selectorState.mode === 'guest') {
                        var gd = window._guestData || {}
                        if (gd && typeof gd.applyServices === 'function') {
                            gd.applyServices(Array.isArray(selectorState.stagedServices) ? selectorState.stagedServices.slice() : [])
                        }
                        closeSelectorModal()
                        return
                    }
                    var nextServices = Array.isArray(selectorState.stagedServices) ? selectorState.stagedServices.slice() : []
                    var currentIds = selectedServiceIds()
                    var nextIds = nextServices.map(function (service) { return service && service.service_id != null ? parseInt(service.service_id, 10) : 0 }).filter(function (id) { return !!id && !isNaN(id) })
                    var changed = !sameIdList(currentIds, nextIds)
                    selectedServices = nextServices
                    syncServiceHiddenInput()
                    renderSelectedServices()
                    filterDoctorsByService()
                    if (changed && !selectedServices.length && doctorSearch) {
                        doctorSearch.value = ''
                    }
                    closeSelectorModal()
                    return
                }
                if (selectorState.type === 'doctor') {
                    if (selectorState.mode === 'guest') {
                        if (!selectorState.activeItem) return
                        var gd = window._guestData || {}
                        if (gd && typeof gd.applyDoctor === 'function') {
                            gd.applyDoctor(selectorState.activeItem)
                        }
                        closeSelectorModal()
                        return
                    }
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

        function searchDoctors(query) {
            var q = normalizeText(query)
            var list = Array.isArray(doctors) ? doctors : []
            if (selectedServices && selectedServices.length) {
                var categories = (selectedServices || [])
                    .map(function (s) { return extractServiceCategory(s && s.service_name ? s.service_name : '') })
                    .filter(function (c) { return !!c })
                list = list.filter(function (d) {
                    var spec = d && d.specialization ? d.specialization : ''
                    return categories.every(function (c) { return specializationMatches(c, spec) })
                })
            }
            if (!q) {
                renderDoctorResults(list.slice(0, 30))
                return
            }
            var filtered = list.filter(function (d) {
                var name = normalizeText([d.firstname, d.middlename, d.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' '))
                var spec = normalizeText(d && d.specialization ? d.specialization : '')
                return wordPrefixMatch(name, q) || wordPrefixMatch(spec, q)
            })
            renderDoctorResults(filtered.slice(0, 30))
        }

        function dayKeyFromDate(dateStr) {
            if (!dateStr) return ''
            var d = new Date(dateStr + 'T00:00:00')
            if (isNaN(d.getTime())) return ''
            var keys = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat']
            return keys[d.getDay()] || ''
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

        function isoDate(d) {
            var yr = d.getFullYear()
            var mo = String(d.getMonth() + 1).padStart(2, '0')
            var da = String(d.getDate()).padStart(2, '0')
            return yr + '-' + mo + '-' + da
        }

        function resetDateCursor() {
            var now = new Date()
            dateCursorFirstIso = isoDate(now)
            var end = new Date(now.getTime() + (1000 * 60 * 60 * 24 * 365))
            dateCursorLastIso = isoDate(end)
            dateCursorIndex = 0
        }

        function appendAllowedDates(daysToAdd) {
            if (!dateSelect) return
            var allowedKeys = doctorAvailableDaySet && Object.keys(doctorAvailableDaySet).length ? doctorAvailableDaySet : null
            if (!allowedKeys) return
            var start = new Date(dateCursorFirstIso + 'T00:00:00')
            if (isNaN(start.getTime())) return
            var added = 0
            var limit = parseInt(daysToAdd || 0, 10)
            if (isNaN(limit) || limit <= 0) limit = 60

            for (var i = 0; i < limit * 3 && added < limit; i++) {
                var d = new Date(start.getTime() + (1000 * 60 * 60 * 24 * (dateCursorIndex + i)))
                if (d.getTime() > new Date(dateCursorLastIso + 'T00:00:00').getTime()) break
                var key = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'][d.getDay()] || ''
                if (!key || !allowedKeys[key]) continue
                var value = isoDate(d)
                if (dateSelect.querySelector('option[value="' + value + '"]')) continue
                var opt = document.createElement('option')
                opt.value = value
                opt.textContent = value
                dateSelect.appendChild(opt)
                added += 1
            }

            dateCursorIndex += limit
            if (dateLoadMore) dateLoadMore.classList.toggle('hidden', dateSelect.options.length <= 1)
            if (dateRangeHint) {
                dateRangeHint.textContent = dateCursorFirstIso + ' → ' + dateCursorLastIso
                dateRangeHint.classList.remove('hidden')
            }

            syncDateRollerFromSelect()
        }

        function friendlyDateLabelFromIso(iso) {
            var datePart = String(iso || '').slice(0, 10)
            if (!/^\d{4}-\d{2}-\d{2}$/.test(datePart)) return datePart || 'Select a date'
            var d = new Date(datePart + 'T00:00:00')
            if (isNaN(d.getTime())) return datePart
            var parts = d.toLocaleDateString(undefined, { weekday: 'short', month: 'short', day: '2-digit' })
            return parts + ' · ' + datePart
        }

        function closeDateOverlay() {
            if (dateOverlay) dateOverlay.classList.add('hidden')
        }

        function closeTimeOverlay() {
            if (timeOverlay) timeOverlay.classList.add('hidden')
        }

        function syncDateRollerFromSelect() {
            if (!dateSelect || !dateTrigger) return
            dateTrigger.disabled = !!dateSelect.disabled

            var selected = dateSelect.value ? String(dateSelect.value) : ''
            if (dateSelect.disabled) {
                dateTrigger.textContent = 'Select a doctor first'
            } else if (selected) {
                dateTrigger.textContent = friendlyDateLabelFromIso(selected)
            } else {
                dateTrigger.textContent = 'Select a date'
            }

            if (!dateList) return
            var html = ''
            var opts = Array.prototype.slice.call(dateSelect.options || [])
            var usable = opts.filter(function (o) { return o && o.value })
            if (!usable.length) {
                html = '<div class="px-3 py-2 text-[0.75rem] text-slate-500">No dates available.</div>'
            } else {
                html = usable.map(function (o) {
                    var isActive = selected && String(o.value) === selected
                    return (
                        '<button type="button" class="w-full text-left px-3 py-2 text-[0.78rem] snap-start ' +
                        (isActive ? 'bg-green-50 text-green-800 font-semibold' : 'text-slate-700 hover:bg-slate-50') +
                        '" data-date="' + escapeHtml(o.value) + '">' + escapeHtml(o.textContent || o.value) + '</button>'
                    )
                }).join('')
            }
            dateList.innerHTML = html
        }

        function populateAllowedDates() {
            if (!dateSelect) return
            dateSelect.innerHTML = ''
            var placeholder = document.createElement('option')
            placeholder.value = ''
            placeholder.textContent = 'Select a date'
            dateSelect.appendChild(placeholder)

            resetDateCursor()
            var allowedKeys = doctorAvailableDaySet && Object.keys(doctorAvailableDaySet).length ? doctorAvailableDaySet : null
            if (!allowedKeys) {
                var opt = document.createElement('option')
                opt.value = ''
                opt.textContent = 'No available schedule days'
                dateSelect.appendChild(opt)
                dateSelect.disabled = false
                if (dateLoadMore) dateLoadMore.classList.add('hidden')
                if (dateRangeHint) {
                    dateRangeHint.textContent = ''
                    dateRangeHint.classList.add('hidden')
                }
                syncDateRollerFromSelect()
                return
            }
            appendAllowedDates(60)
            dateSelect.disabled = false
            if (dateSelect.options && dateSelect.options.length <= 1) {
                var none = document.createElement('option')
                none.value = ''
                none.textContent = 'No available dates in range'
                dateSelect.appendChild(none)
            }
            syncDateRollerFromSelect()
        }

        function renderAvailableDays() {
            if (!availableDaysEl) return
            if (!doctorSchedules || !doctorSchedules.length) {
                availableDaysEl.textContent = ''
                return
            }
            var days = {}
            doctorSchedules.forEach(function (s) {
                if (!s) return
                var k = String(s.day_of_week || '').toLowerCase()
                if (!k) return
                days[k] = true
            })
            doctorAvailableDaySet = days
            var order = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']
            var labels = { mon: 'Mon', tue: 'Tue', wed: 'Wed', thu: 'Thu', fri: 'Fri', sat: 'Sat', sun: 'Sun' }
            var list = order.filter(function (k) { return !!days[k] }).map(function (k) { return labels[k] || k })
            availableDaysEl.textContent = list.length ? ('Available: ' + list.join(', ')) : ''
        }

        function clearAvailability() {
            doctorSchedules = []
            doctorAvailableDaySet = {}
            doctorAppointments = []
            selectedSlotStart = null
            if (timeInput) timeInput.value = ''
            if (dateSelect) {
                if (selectedDoctor && selectedDoctor.user_id) {
                    dateSelect.innerHTML = '<option value="">Loading available dates…</option>'
                    dateSelect.disabled = false
                } else {
                    dateSelect.innerHTML = '<option value="">Select a doctor first</option>'
                    dateSelect.disabled = true
                }
            }
            syncDateRollerFromSelect()
            if (dateLoadMore) dateLoadMore.classList.add('hidden')
            if (dateRangeHint) {
                dateRangeHint.textContent = ''
                dateRangeHint.classList.add('hidden')
            }

            if (timeTrigger) {
                timeTrigger.disabled = true
                timeTrigger.textContent = 'Select a date first'
            }
            closeTimeOverlay()
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
            if (!timeSlotsEl) return
            timeSlotsEl.innerHTML = ''

            var apptType = getAppointmentType()
            if (apptType === 'walk_in') {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'Walk-in does not require a time slot'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">Walk-in visits do not require a time slot.</div>'
                return
            }

            if (!doctorSelect || !doctorSelect.value) {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'Select a doctor first'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">Select a doctor to load time slots.</div>'
                return
            }
            if (!dateInput || !dateInput.value) {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'Select a date first'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">Select a date to load time slots.</div>'
                return
            }
            if (!doctorSchedules.length) {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'No schedules found'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">No schedules found for this doctor.</div>'
                return
            }

            var dayKey = dayKeyFromDate(dateInput.value)
            if (!dayKey) {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'Invalid date'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">Invalid date selected.</div>'
                return
            }
            if (doctorAvailableDaySet && Object.keys(doctorAvailableDaySet).length && !doctorAvailableDaySet[dayKey]) {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'Doctor not available'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">Doctor is not available on this day.</div>'
                return
            }

            var todayIso = localDateIso()
            var isToday = String(dateInput.value) === todayIso
            var daySchedules = doctorSchedules.filter(function (s) {
                if (!s) return false
                if (String(s.day_of_week || '').toLowerCase() !== dayKey) return false
                if (isToday && s.is_available === false) return false
                return true
            })

            if (!daySchedules.length) {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'No available slots'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">Doctor has no available slots on this day.</div>'
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
            var bookedSet = {}
            appts.forEach(function (a) {
                if (!a || !a.appointment_datetime) return
                if (String(a.status || '').toLowerCase() === 'cancelled') return
                if (a.appointment_type && String(a.appointment_type) !== 'scheduled') return
                var dt = String(a.appointment_datetime).replace('T', ' ').slice(0, 16)
                if (!/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/.test(dt)) return
                var datePart = dt.slice(0, 10)
                var timePart = dt.slice(11, 16)
                if (datePart !== dateInput.value) return
                bookedSet[timePart] = true
            })

            var slots = []
            merged.forEach(function (block) {
                for (var m = block.start; m + slotMinutes <= block.end; m += slotMinutes) {
                    slots.push({ start: m, end: m + slotMinutes })
                }
            })

            if (!slots.length) {
                if (timeTrigger) {
                    timeTrigger.disabled = true
                    timeTrigger.textContent = 'No time slots available'
                }
                closeTimeOverlay()
                timeSlotsEl.innerHTML = '<div class="text-[0.7rem] text-slate-400">No time slots available for this day.</div>'
                return
            }

            if (timeTrigger) {
                timeTrigger.disabled = false
                timeTrigger.textContent = selectedSlotStart ? ('Selected: ' + formatTime12h(selectedSlotStart)) : 'Select a time slot'
            }

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
                    closeTimeOverlay()
                    renderTimeSlots()
                })
                timeSlotsEl.appendChild(btn)
            })
        }

        function loadDoctorSchedulesAndAvailability(doctorId, dateStr) {
            if (!doctorId || typeof apiFetch !== 'function') return
            clearAvailability()
            apiFetch("{{ url('/api/doctor-schedules') }}?doctor_id=" + encodeURIComponent(doctorId) + "&per_page=15", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) {
                        var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to load doctor schedules.'
                        if (result.status === 401) msg = 'Session expired. Please log in again.'
                        if (result.status === 403) msg = 'Forbidden (403). Your account does not have permission to view this doctor’s schedules.'
                        showError(msg)
                        if ((!doctorSchedules || !doctorSchedules.length) && dateSelect) {
                            dateSelect.innerHTML = '<option value="">Failed to load schedules</option>'
                            dateSelect.disabled = false
                        }
                        renderAvailableDays()
                        populateAllowedDates()
                        renderTimeSlots()
                        return
                    }

                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    doctorSchedules = raw || []
                    renderAvailableDays()
                    populateAllowedDates()
                    if (dateSelect) dateSelect.disabled = false
                    if (dateStr) {
                        loadDoctorAppointments(doctorId, dateStr)
                    } else {
                        renderTimeSlots()
                    }
                })
                .catch(function () {
                    showError('Network error while loading doctor schedules.')
                    if ((!doctorSchedules || !doctorSchedules.length) && dateSelect) {
                        dateSelect.innerHTML = '<option value="">Network error loading schedules</option>'
                        dateSelect.disabled = false
                    }
                    renderAvailableDays()
                    populateAllowedDates()
                    renderTimeSlots()
                })
        }

        function loadDoctorAppointments(doctorId, dateStr) {
            if (!doctorId || !dateStr || typeof apiFetch !== 'function') return
            apiFetch("{{ url('/api/appointments') }}?doctor_id=" + encodeURIComponent(doctorId) + "&start_date=" + encodeURIComponent(dateStr) + "&end_date=" + encodeURIComponent(dateStr) + "&per_page=15", { method: 'GET' })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    var raw = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    doctorAppointments = raw || []
                    renderTimeSlots()
                })
                .catch(function () { renderTimeSlots() })
        }

        function onDateChanged() {
            showError('')
            showSuccess('')
            selectedSlotStart = null
            if (timeInput) timeInput.value = ''
            syncDateRollerFromSelect()
            closeDateOverlay()
            closeTimeOverlay()
            if (!doctorSelect || !doctorSelect.value) {
                renderTimeSlots()
                return
            }
            var dateStr = dateSelect && dateSelect.value ? dateSelect.value : ''
            if (!dateStr) {
                if (dateInput) dateInput.value = ''
                renderTimeSlots()
                return
            }
            if (dateInput) dateInput.value = dateStr
            loadDoctorAppointments(doctorSelect.value, dateStr)
        }

        function loadServicesAndDoctors() {
            if (typeof apiFetch !== 'function') return
            if (!servicesLoaded && !servicesLoading) {
                servicesLoading = true
                servicesLoadError = ''
                apiFetch("{{ url('/api/services') }}?per_page=15", { method: 'GET' })
                    .then(function (r) { return readResponse(r) })
                    .then(function (res) {
                        if (res.ok) {
                            services = res.data && Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : [])
                            var allowed = ['general medicine', 'pediatrics']
                            services = services.filter(function (s) {
                                return allowed.indexOf(normalizeText(s && s.service_name ? s.service_name : '')) !== -1
                            })
                            servicesLoaded = true
                            if (serviceSearch && document.activeElement === serviceSearch) {
                                searchServices(String(serviceSearch.value || '').trim())
                            }
                        } else {
                            servicesLoadError = (res.data && res.data.message) ? String(res.data.message) : 'Failed to load services.'
                        }
                    })
                    .catch(function () { servicesLoadError = 'Failed to load services.' })
                    .finally(function () { servicesLoading = false })
            }
            if (!popularServicesLoaded && !popularServicesLoading) {
                popularServicesLoading = true
                popularServicesLoadError = ''
                apiFetch("{{ url('/api/services-popular') }}?limit=10", { method: 'GET' })
                    .then(function (r) { return readResponse(r) })
                    .then(function (res) {
                        if (res.ok) {
                            popularServices = Array.isArray(res.data) ? res.data : (res.data && Array.isArray(res.data.data) ? res.data.data : [])
                            var allowed = ['general medicine', 'pediatrics']
                            popularServices = popularServices.filter(function (s) {
                                return allowed.indexOf(normalizeText(s && s.service_name ? s.service_name : '')) !== -1
                            })
                            popularServicesLoaded = true
                        } else {
                            popularServicesLoadError = (res.data && res.data.message) ? String(res.data.message) : 'Failed to load popular services.'
                        }
                    })
                    .catch(function () { popularServicesLoadError = 'Failed to load popular services.' })
                    .finally(function () { popularServicesLoading = false })
            }
            if (!doctorsLoaded && !doctorsLoading) {
                doctorsLoading = true
                doctorsLoadError = ''
                apiFetch("{{ url('/api/doctors') }}?per_page=15", { method: 'GET' })
                    .then(function (r) { return readResponse(r) })
                    .then(function (res) {
                        if (res.ok) {
                            doctors = res.data && Array.isArray(res.data.data) ? res.data.data : (Array.isArray(res.data) ? res.data : [])
                            doctorsLoaded = true
                            filterDoctorsByService()
                            if (doctorSearch && document.activeElement === doctorSearch) {
                                searchDoctors(String(doctorSearch.value || '').trim())
                            }
                        } else {
                            doctorsLoadError = (res.data && res.data.message) ? String(res.data.message) : 'Failed to load doctors.'
                        }
                    })
                    .catch(function () { doctorsLoadError = 'Failed to load doctors.' })
                    .finally(function () { doctorsLoading = false })
            }
        }

        var typeScheduledBtn = accountQuery('#receptionWalkInTypeScheduledBtn')
        var typeWalkInBtn = accountQuery('#receptionWalkInTypeWalkInBtn')

        function setTypeButtonState(btn, isActive) {
            if (!btn) return
            btn.classList.toggle('bg-white', isActive)
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
            var type = typeInput && typeInput.value ? typeInput.value : 'walk_in'
            setTypeButtonState(typeScheduledBtn, type === 'scheduled')
            setTypeButtonState(typeWalkInBtn, type === 'walk_in')
        }

        function applyAppointmentTypeUI() {
            var type = typeInput && typeInput.value ? String(typeInput.value) : 'walk_in'
            var isWalkIn = type === 'walk_in'
            if (dateWrap) dateWrap.classList.toggle('hidden', isWalkIn)
            if (timeWrap) timeWrap.classList.toggle('hidden', isWalkIn)
            if (dateSelect) {
                dateSelect.required = !isWalkIn
                dateSelect.disabled = isWalkIn || !doctorSelect || !doctorSelect.value
            }
            if (dateLoadMore) {
                var canShowMore = !isWalkIn && !!(doctorSelect && doctorSelect.value) && !!(doctorAvailableDaySet && Object.keys(doctorAvailableDaySet).length)
                dateLoadMore.classList.toggle('hidden', !canShowMore)
            }
            if (dateRangeHint) {
                var canShowHint = !isWalkIn && !!(doctorSelect && doctorSelect.value) && !!(dateCursorFirstIso && dateCursorLastIso)
                dateRangeHint.classList.toggle('hidden', !canShowHint)
            }
            if (dateInput) dateInput.required = false
            if (timeInput) timeInput.required = !isWalkIn
            if (isWalkIn) {
                if (dateSelect) dateSelect.value = ''
                if (dateInput) dateInput.value = ''
                if (timeInput) timeInput.value = ''
                selectedSlotStart = null
                var filteredServices = (selectedServices || []).filter(function (s) { return !isWalkInExcludedService(s) })
                if (filteredServices.length !== (selectedServices || []).length) {
                    selectedServices = filteredServices
                    syncServiceHiddenInput()
                    renderSelectedServices()
                    filterDoctorsByService()
                }
            } else {
                if (doctorSelect && doctorSelect.value && (!doctorSchedules || !doctorSchedules.length)) {
                    clearAvailability()
                    loadDoctorSchedulesAndAvailability(String(doctorSelect.value), null)
                }
            }
            syncPriorityInputState()
            renderTimeSlots()
        }

        function setAppointmentType(nextType) {
            if (typeInput) typeInput.value = nextType === 'scheduled' ? 'scheduled' : 'walk_in'
            applyAppointmentTypeUI()
            syncTypeToggleUI()
        }

        if (typeScheduledBtn) typeScheduledBtn.addEventListener('click', function () { setAppointmentType('scheduled') })
        if (typeWalkInBtn) typeWalkInBtn.addEventListener('click', function () { setAppointmentType('walk_in') })

        function bindSelectorTrigger(inputEl, buttonEl, type) {
            function openCurrentSelector() {
                showError('')
                showSuccess('')
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

        if (dateSelect) {
            dateSelect.addEventListener('change', onDateChanged)
            if (dateLoadMore) {
                dateLoadMore.addEventListener('click', function () {
                    if (dateLoadMore.disabled) return
                    appendAllowedDates(60)
                })
            }
            syncDateRollerFromSelect()
        }

        if (dateTrigger) {
            dateTrigger.addEventListener('click', function () {
                if (!dateSelect || dateSelect.disabled) return
                if (!dateOverlay) return
                syncDateRollerFromSelect()
                dateOverlay.classList.toggle('hidden')
            })
        }

        if (dateList) {
            dateList.addEventListener('click', function (e) {
                var btn = e.target && e.target.closest ? e.target.closest('button[data-date]') : null
                if (!btn || !dateSelect) return
                var iso = btn.getAttribute('data-date') || ''
                if (!iso) return
                dateSelect.value = iso
                dateSelect.dispatchEvent(new Event('change', { bubbles: true }))
            })
        }

        if (timeTrigger) {
            timeTrigger.addEventListener('click', function () {
                if (timeTrigger.disabled) return
                if (!timeOverlay) return
                renderTimeSlots()
                timeOverlay.classList.toggle('hidden')
            })
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSelectorModal()
                closeDateOverlay()
                closeTimeOverlay()
            }
        })

        loadServicesAndDoctors()
        syncServiceHiddenInput()
        renderSelectedServices()
        if (typeInput && !typeInput.value) typeInput.value = 'walk_in'
        syncTypeToggleUI()
        applyAppointmentTypeUI()
        syncSelectionTriggers()

        var guestToggle = document.getElementById('receptionWalkInGuestToggle')
        var guestForm = document.getElementById('receptionWalkInGuestForm')
        var accountFormWrapper = document.getElementById('receptionWalkInAccountFormWrapper')
        if (guestToggle && guestForm && accountFormWrapper) {
            guestToggle.addEventListener('change', function () {
                if (guestToggle.checked) {
                    accountFormWrapper.classList.add('hidden')
                    guestForm.classList.remove('hidden')
                } else {
                    accountFormWrapper.classList.remove('hidden')
                    guestForm.classList.add('hidden')
                }
            })
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault()

                showError('')
                showSuccess('')
                setSubmitting(true)

                var patientInput = accountQuery('#reception_walkin_patient_id')
                var doctorInput = accountQuery('#reception_walkin_doctor_id')
                var dateSelect = accountQuery('#reception_walkin_date_select')
                var dateInput = accountQuery('#reception_walkin_date')
                var timeInput = accountQuery('#reception_walkin_time')
                var currentTypeInput = accountQuery('#reception_walkin_type')
                var priorityInput = accountQuery('#reception_walkin_priority')
                var reasonInput = accountQuery('#reception_walkin_reason')

                var patientId = patientInput ? parseInt(patientInput.value, 10) : 0
                var doctorId = doctorInput ? parseInt(doctorInput.value, 10) : 0
                var serviceIds = selectedServiceIds()
                var date = dateSelect && dateSelect.value ? dateSelect.value : (dateInput ? dateInput.value : '')
                var time = timeInput ? timeInput.value : ''
                var type = currentTypeInput && currentTypeInput.value ? String(currentTypeInput.value) : 'walk_in'
                var priority = priorityInput && priorityInput.value ? parseInt(priorityInput.value, 10) : null
                var reason = reasonInput ? reasonInput.value : ''
                var patientType = verificationTypeLabel(approvedVerificationType) || ((priority !== null && !isNaN(priority)) ? priorityTypeLabel(priority) : '')
                var autoQueue = true

                if (!patientId || !serviceIds.length || !doctorId) {
                    showError('Patient, services, and doctor are required.')
                    setSubmitting(false)
                    return
                }

                if (type !== 'walk_in') {
                    if (!date || !time) {
                        showError('Date and time are required for scheduled visits.')
                        setSubmitting(false)
                        return
                    }
                }

                if (typeof apiFetch !== 'function') {
                    showError('API client is not available.')
                    setSubmitting(false)
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
                if (reason) body.reason_for_visit = reason
                if (priority !== null && !isNaN(priority)) body.priority_level = priority

                var patientName = selectedPatient ? patientDisplayName(selectedPatient) : ('#' + String(patientId))
                var doctorName = selectedDoctor ? doctorDisplayName(selectedDoctor) : ('#' + String(doctorId))
                var serviceText = (selectedServices || []).map(function (s) { return s && s.service_name ? s.service_name : '' }).filter(Boolean).join(', ')
                var reviewDetails = {
                    'Appointment Type': type === 'walk_in' ? 'Walk-in' : 'Scheduled',
                    'Patient': patientName,
                    'Doctor': doctorName,
                    'Services': serviceText || 'N/A',
                    'Date': type === 'walk_in' ? localDateIso() : date,
                    'Time': type === 'walk_in' ? 'Now' : time,
                    'Reason': reason || 'N/A',
                    'Patient Type': patientType || 'Manual / General',
                    'Priority Level': (priority !== null && !isNaN(priority)) ? String(priority) : 'N/A'
                }

                function readJson(response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, status: response.status, data: data }
                    }).catch(function () {
                        return { ok: response.ok, status: response.status, data: null }
                    })
                }

                function resetFormState() {
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
                    if (priorityInput) priorityInput.value = ''
                    if (reasonInput) reasonInput.value = ''
                    if (currentTypeInput) currentTypeInput.value = 'walk_in'
                    syncTypeToggleUI()
                    applyAppointmentTypeUI()
                }

                function createAndQueue(options) {
                    var opts = options && typeof options === 'object' ? options : {}
                    var createUrl = type === 'walk_in' ? "{{ url('/api/walk-ins') }}" : "{{ url('/api/appointments') }}"
                    var requestBody = Object.assign({}, body)
                    if (type === 'walk_in' && opts.forceDuplicateQueue) {
                        requestBody.force_duplicate_queue = true
                    }

                    return apiFetch(createUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(requestBody)
                    })
                        .then(readJson)
                        .then(function (result) {
                            if (!result.ok) {
                                var message = 'Failed to create appointment.'
                                if (result.data && result.data.message) message = result.data.message
                                showError(message)
                                return
                            }

                            var created = result.data || {}
                            function afterQueue() {
                                showSuccess(type === 'walk_in'
                                    ? 'Walk-in successfuly created and currently on the queue.'
                                    : 'Appointment has been created successfully. Queue entry created.')
                                resetFormState()
                            }

                            if (autoQueue && created && created.appointment_id) {
                                var queueBody = { appointment_id: created.appointment_id }
                                if (type === 'walk_in' && opts.forceDuplicatePatient) {
                                    queueBody.force_duplicate_patient = true
                                }
                                return apiFetch("{{ url('/api/queues') }}", {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify(queueBody)
                                })
                                    .then(function () { afterQueue() })
                                    .catch(function () { afterQueue() })
                            }
                            afterQueue()
                        })
                        .catch(function () {
                            showError('Network error while creating appointment.')
                        })
                        .finally(function () {
                            setSubmitting(false)
                        })
                }

                function continueAfterReview() {
                    if (type === 'walk_in') {
                        return apiFetch("{{ url('/api/queues/active-exists') }}?patient_id=" + encodeURIComponent(String(patientId)), { method: 'GET' })
                            .then(readJson)
                            .then(function (res) {
                                var exists = !!(res && res.ok && res.data && res.data.exists)
                                if (!exists) return createAndQueue()
                                var ask = window.receptionWalkInConfirm
                                if (typeof ask === 'function') {
                                    return ask('This patient is already in the queue, would you still like to register this queue entry?', 3000)
                                        .then(function (confirmed) {
                                            if (!confirmed) {
                                                setSubmitting(false)
                                                return
                                            }
                                            return createAndQueue({ forceDuplicateQueue: true, forceDuplicatePatient: true })
                                        })
                                }
                                if (!window.confirm('This patient is already in the queue, would you still like to register this queue entry?')) {
                                    setSubmitting(false)
                                    return
                                }
                                return createAndQueue({ forceDuplicateQueue: true, forceDuplicatePatient: true })
                            })
                            .catch(function () {
                                return createAndQueue()
                            })
                    }

                    return apiFetch("{{ url('/api/appointments/active-exists') }}?patient_id=" + encodeURIComponent(String(patientId)), { method: 'GET' })
                        .then(readJson)
                        .then(function (res) {
                            var exists = !!(res && res.ok && res.data && res.data.exists)
                            if (!exists) return createAndQueue()
                            var ask = window.receptionWalkInConfirm
                            if (typeof ask === 'function') {
                                return ask('This patient has an active appointment already, would you still like to book this appointment?', 3000)
                                    .then(function (confirmed) {
                                        if (!confirmed) {
                                            setSubmitting(false)
                                            return
                                        }
                                        return createAndQueue()
                                    })
                            }
                            if (!window.confirm('This patient has an active appointment already, would you still like to book this appointment?')) {
                                setSubmitting(false)
                                return
                            }
                            return createAndQueue()
                        })
                        .catch(function () {
                            return createAndQueue()
                        })
                }

                var askReview = window.receptionWalkInReview
                var reviewPromise = (typeof askReview === 'function')
                    ? askReview('Review Walk-in Details', reviewDetails)
                    : Promise.resolve(window.confirm('Please review details before submitting this walk-in.'))

                reviewPromise.then(function (reviewOk) {
                    if (!reviewOk) {
                        setSubmitting(false)
                        return
                    }
                    return continueAfterReview()
                })
            })
        }

        window._openSelectorModal = openSelectorModal
        window._closeSelectorModal = closeSelectorModal
    })
</script>
