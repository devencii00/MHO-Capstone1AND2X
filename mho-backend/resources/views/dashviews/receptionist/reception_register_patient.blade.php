<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 mb-1">Patients Registration & Records</h1>
        <p class="text-sm text-slate-500">Register new patients and browse patient records.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)] overflow-hidden">
    <div class="grid grid-cols-2 border-b border-slate-200">
        <button id="receptionPatientTabRegister" type="button" class="px-4 py-3 text-xs font-semibold text-white bg-green-500 border-b-2 border-green-600">
            Register patient
        </button>
        <button id="receptionPatientTabRecords" type="button" class="px-4 py-3 text-xs font-semibold text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200">
            Patient Records
        </button>
    </div>

    <div id="receptionRegisterPatientPanel" class="p-5">

        <div id="receptionRegisterPatientError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
        <div id="receptionRegisterPatientSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>
        <pre id="receptionRegisterPatientCredentials" class="hidden mb-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.7rem] text-slate-700 overflow-x-auto"></pre>

        <form id="receptionRegisterPatientForm" class="grid gap-3 grid-cols-1 md:grid-cols-3 items-end mb-4">
            <div class="md:col-span-3">
                <label class="inline-flex items-center gap-2 text-[0.75rem] text-slate-700 font-semibold">
                    <input id="reception_patient_is_dependent" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-green-600 focus:ring-green-500">
                    Dependent account
                </label>
                <div class="text-[0.7rem] text-slate-400 mt-1">
                    Enable to link this patient as a dependent under an existing parent patient.
                </div>
            </div>

            <div id="receptionDependentParentSection" class="hidden md:col-span-3">
                <label class="block text-[0.7rem] text-slate-600 mb-1">Parent</label>
                <div class="relative">
                    <input id="reception_parent_search" type="text" readonly class="w-full cursor-pointer rounded-lg border border-slate-200 bg-white px-3 py-2 pr-24 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Select parent patient">
                    <input id="reception_parent_user_id" type="hidden">
                    <button id="receptionPrParentPickerBtn" type="button" class="absolute inset-y-1 right-1 inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-[0.7rem] font-semibold text-slate-700 hover:bg-slate-100">
                        Browse
                    </button>
                </div>
                <div id="receptionParentPreview" class="hidden mt-2 rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-600 shrink-0">
                            <x-lucide-user class="w-[16px] h-[16px]" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div id="receptionParentPreviewName" class="text-[0.8rem] font-semibold text-slate-900 truncate"></div>
                            <div id="receptionParentPreviewAddress" class="text-[0.72rem] text-slate-500 truncate mt-0.5"></div>
                            <div id="receptionParentPreviewContact" class="text-[0.72rem] text-slate-500 truncate"></div>
                        </div>
                        <button type="button" id="receptionPrParentRemoveBtn" class="shrink-0 text-slate-400 hover:text-red-500">
                            <x-lucide-x class="w-[16px] h-[16px]" />
                        </button>
                    </div>
                </div>
                <div id="receptionDependentRelationshipSection" class="hidden mt-3">
                    <label for="reception_dependent_relationship" class="block text-[0.7rem] text-slate-600 mb-1">Relationship</label>
                    <select id="reception_dependent_relationship" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="">Select</option>
                        <option value="mother">Mother</option>
                        <option value="father">Father</option>
                        <option value="guardian">Guardian</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="reception_patient_firstname" class="block text-[0.7rem] text-slate-600 mb-1">Firstname</label>
                <input id="reception_patient_firstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Firstname">
            </div>
            <div>
                <label for="reception_patient_middlename" class="block text-[0.7rem] text-slate-600 mb-1">Middlename (optional)</label>
                <input id="reception_patient_middlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Middlename">
            </div>
            <div>
                <label for="reception_patient_lastname" class="block text-[0.7rem] text-slate-600 mb-1">Lastname</label>
                <input id="reception_patient_lastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Lastname">
            </div>
            <div>
                <label for="reception_patient_birthdate" class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                <input id="reception_patient_birthdate" type="date" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
            </div>
            <div>
                <label for="reception_patient_sex" class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                <select id="reception_patient_sex" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="">Select</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div>
                <label id="reception_patient_contact_label" for="reception_patient_contact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                <input id="reception_patient_contact" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
            </div>
            <div class="md:col-span-3">
                <label for="reception_patient_address" class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                <input id="reception_patient_address" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Complete address">
            </div>
            <div class="md:col-span-2">
                <label id="reception_patient_email_label" for="reception_patient_email" class="block text-[0.7rem] text-slate-600 mb-1">Email</label>
                <input id="reception_patient_email" type="email" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Email address">
            </div>
            <div>
                <button id="receptionRegisterPatientSubmit" type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors">
                    Register
                </button>
            </div>
        </form>

        <p id="receptionRegisterPatientHint" class="text-[0.7rem] text-slate-400">
            Email is required for patient accounts. Dependent accounts may be registered without an email.
        </p>
    </div>

    <div id="receptionPatientRecordsPanel" class="hidden">
        <div class="p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-slate-900"></h2>
                <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Clinical</span>
            </div>
            <p class="text-xs text-slate-500 mb-4"></p>

            <div id="receptionPrPatientsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

            <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
                <div class="flex-1">
                    <label for="reception_pr_patients_search" class="block text-[0.7rem] text-slate-600 mb-1">Search patient name</label>
                    <input id="reception_pr_patients_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by name (starts with)">
                </div>
                <div class="w-full md:w-44">
                    <label for="reception_pr_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
                    <select id="reception_pr_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="name_asc">Name A-Z</option>
                        <option value="created_desc">Newest first</option>
                        <option value="created_asc">Oldest first</option>
                    </select>
                </div>
                <div class="w-full md:w-28 pt-1">
                    <button type="button" id="receptionPrRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                        <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                        Refresh
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <div class="text-[0.7rem] text-slate-600 mb-1">Age filter</div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-green-600 text-white text-[0.72rem] font-semibold" data-age-filter="all">
                        All
                        <span id="receptionPrAgeCountAll" class="ml-1 inline-flex items-center rounded-full bg-white/15 px-2 py-0.5 text-[0.68rem] font-semibold">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="1_5">
                        1–5
                        <span id="receptionPrAgeCount1_5" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="6_12">
                        6–12
                        <span id="receptionPrAgeCount6_12" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="13_18">
                        13–18
                        <span id="receptionPrAgeCount13_18" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="19_30">
                        19–30
                        <span id="receptionPrAgeCount19_30" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="31_up">
                        31+
                        <span id="receptionPrAgeCount31Up" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto overflow-y-auto scrollbar-hidden h-[610px]">
                <table class="min-w-full text-left text-xs text-slate-600">
                    <thead>
                        <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                            <th class="py-2 pr-4 font-semibold">Profile</th>
                            <th class="py-2 pr-4 font-semibold">Patient</th>
                            <th class="py-2 pr-4 font-semibold">Address</th>
                            <th class="py-2 pr-4 font-semibold">Age</th>
                            <th class="py-2 pr-4 font-semibold">Sex</th>
                            <th class="py-2 pr-4 font-semibold">Type</th>
                            <th class="py-2 pr-4 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="reception_pr_patients_table_body">
                        <tr>
                            <td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">
                                Loading patients…
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="receptionPrPagination" class="flex items-center justify-center gap-3 pt-3 pb-1 flex-wrap"></div>
        </div>
    </div>
</div>

<div id="receptionPrSlideoverOverlay" class="fixed inset-0 z-40 bg-black/30 opacity-0 pointer-events-none transition-opacity duration-300 ease-out"></div>

<div id="receptionPrSlideoverPanel" class="fixed top-0 right-0 z-50 h-full w-full max-w-[560px] bg-white border-l border-slate-200 shadow-2xl translate-x-full transition-transform duration-300 ease-out">
    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 shrink-0">
            <div class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Patient Details</div>
            <button type="button" id="receptionPrPanelClose" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800 flex-shrink-0">
                <x-lucide-x class="w-[16px] h-[16px]" />
            </button>
        </div>

        <div class="flex-1 overflow-y-auto scrollbar-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex gap-4">
                    <div class="flex w-20 flex-shrink-0 self-start flex-col items-center gap-2">
                        <div id="receptionPrPanelProfilePic" class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <x-lucide-user class="w-9 h-9" />
                            </div>
                        </div>
                        <button type="button" id="receptionPrPanelEditInfoBtn" class="inline-flex items-center justify-center rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 transition-colors">
                            Edit Info
                        </button>
                    </div>
                    <div class="flex-1 flex gap-x-5 gap-y-[3px] text-[0.78rem]">
                        <div class="flex-1 space-y-[3px]">
                            <div><span class="text-slate-500">First name:</span> <span id="receptionPrDetailFirstname" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Middle Name:</span> <span id="receptionPrDetailMiddlename" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Last Name:</span> <span id="receptionPrDetailLastname" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Date Of Birth:</span> <span id="receptionPrDetailBirthdate" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Address:</span> <span id="receptionPrDetailAddress" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Sex:</span> <span id="receptionPrDetailSex" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Civil status:</span> <span id="receptionPrDetailCivilStatus" class="text-slate-800 ml-1">-</span></div>
                        </div>
                        <div class="flex-1 space-y-[3px]">
                            <div><span class="text-slate-500">Nationality:</span> <span id="receptionPrDetailNationality" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Contact Number:</span> <span id="receptionPrDetailContact" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">PHIC #:</span> <span id="receptionPrDetailPhic" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Occupation:</span> <span id="receptionPrDetailOccupation" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Emergency contact:</span> <span id="receptionPrDetailEmergContact" class="text-slate-800 ml-1">-</span></div>
                            <div><span class="text-slate-500">Emergency Contact Number:</span> <span id="receptionPrDetailEmergNumber" class="text-slate-800 ml-1">-</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 pt-4 pb-3 border-b border-slate-100">
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Patient records</div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="receptionPrPanelTabBackground" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Medical background</button>
                    <button type="button" id="receptionPrPanelTabVisits" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Visit history</button>
                    <button type="button" id="receptionPrPanelTabVitals" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Vitals history</button>
                    <button type="button" id="receptionPrPanelTabDependents" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Dependents</button>
                </div>
                <p class="mt-2 text-[0.74rem] text-slate-500">Select a tab to open the matching records panel beside this profile.</p>
            </div>

            <div class="px-5 py-4 space-y-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification status</div>
                    <div id="receptionPrPanelVerificationStatus" class="text-[0.8rem] font-semibold text-slate-700 mt-1">-</div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient type</div>
                    <div id="receptionPrPanelPatientType" class="text-[0.8rem] font-semibold text-slate-700 mt-1">-</div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification ID</div>
                    <div id="receptionPrPanelVerificationId" class="text-[0.8rem] font-semibold text-slate-700 mt-1">-</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="receptionPrEditOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-slate-900">Edit Patient Info</div>
                <div id="receptionPrEditSubtitle" class="text-[0.72rem] text-slate-500">Update patient profile information.</div>
            </div>
            <button type="button" id="receptionPrEditClose" class="text-slate-400 hover:text-slate-600">
                <x-lucide-x class="w-[20px] h-[20px]" />
            </button>
        </div>
        <div class="p-5">
            <div id="receptionPrEditError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
            <form id="receptionPrEditForm" class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <div class="md:col-span-3 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="receptionPrEditLastname" class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                            <input id="receptionPrEditLastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="receptionPrEditFirstname" class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                            <input id="receptionPrEditFirstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="receptionPrEditMiddlename" class="block text-[0.7rem] text-slate-600 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                            <input id="receptionPrEditMiddlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="receptionPrEditSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="receptionPrEditSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="receptionPrEditBirthdate" class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                            <input id="receptionPrEditBirthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="receptionPrEditCivilStatus" class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label>
                            <select id="receptionPrEditCivilStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
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
                            <label for="receptionPrEditNationalitySelect" class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label>
                            <div id="receptionPrEditNationalityField" class="flex gap-2">
                                <select id="receptionPrEditNationalitySelect" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                    <option value="">None</option>
                                    <option value="Filipino">Filipino</option>
                                    <option value="__others__">Other/s specify</option>
                                </select>
                                <input id="receptionPrEditNationality" type="text" class="w-0 hidden rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Please specify">
                            </div>
                        </div>
                        <div>
                            <label for="receptionPrEditOccupation" class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label>
                            <input id="receptionPrEditOccupation" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                    </div>
                    <div>
                        <label for="receptionPrEditAddress" class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                        <textarea id="receptionPrEditAddress" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none resize-y" placeholder="Street, barangay, municipality"></textarea>
                    </div>
                    <hr class="border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="receptionPrEditPhilhealth" class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                            <input id="receptionPrEditPhilhealth" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                        </div>
                        <div>
                            <label for="receptionPrEditEmergencyContact" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label>
                            <input id="receptionPrEditEmergencyContact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                    </div>
                    <div>
                        <label for="receptionPrEditEmergencyContactNumber" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                        <input id="receptionPrEditEmergencyContactNumber" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                        <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                        <div id="receptionPrEditProfilePreview" class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="mt-3">
                            <label for="receptionPrEditProfileUpload" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-green-200 bg-green-50 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Upload photo
                            </label>
                            <input id="receptionPrEditProfileUpload" type="file" accept="image/*" class="hidden">
                        </div>
                        <div class="mt-4 text-left">
                            <label for="receptionPrEditContact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                            <input id="receptionPrEditContact" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                        </div>
                    </div>
                </div>
                <div class="md:col-span-5 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" id="receptionPrEditCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" id="receptionPrEditSave" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                        <span id="receptionPrEditSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                        <span id="receptionPrEditSaveLabel">Save changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="receptionPrEditConfirmOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                <x-lucide-info class="w-[18px] h-[18px]" />
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-slate-900">Confirm</div>
                <div id="receptionPrEditConfirmMessage" class="text-[0.78rem] text-slate-600 mt-0.5">Are you sure?</div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-end gap-2">
            <button type="button" id="receptionPrEditConfirmCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="button" id="receptionPrEditConfirmOk" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Confirm</button>
        </div>
    </div>
</div>

<div id="receptionPrTabDrawer" class="fixed top-0 right-0 md:right-[560px] z-[49] h-full w-full max-w-[480px] bg-white border-l border-slate-200 shadow-xl hidden">
    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 shrink-0">
            <div>
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient records</div>
                <div id="receptionPrTabDrawerTitle" class="text-[0.82rem] font-semibold text-slate-900 mt-1">Medical background</div>
            </div>
            <button type="button" id="receptionPrTabDrawerClose" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                <x-lucide-x class="w-[16px] h-[16px]" />
            </button>
        </div>
        <div id="receptionPrTabDrawerBody" class="flex-1 overflow-y-auto p-4 scrollbar-hidden">
            <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a tab to view records.</div>
        </div>
    </div>
</div>
<div id="receptionRegisterPatientConfirmOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4 transition-all duration-200">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-100 overflow-hidden">
        <!-- Header area with refined spacing and visual hierarchy -->
        <div class="px-5 pt-5 pb-3 border-b border-slate-100 bg-gradient-to-r from-white to-slate-50/50">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 flex-shrink-0">
                    <!-- Lucide info icon from composer/blade -->
                    <x-lucide-info class="w-5 h-5" />
                </div>
                <div class="flex-1">
                    <h3 id="receptionRegisterPatientConfirmMessage" class="text-base font-semibold text-slate-800 tracking-tight">Confirm patient registration</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Please review the information below before confirming</p>
                </div>
            </div>
        </div>
        
        <!-- Body with clear, scannable details section - improved for reading -->
        <div class="px-5 py-4 bg-white">
            <!-- dynamic details with card-like presentation for better readability -->
            <div id="receptionRegisterPatientConfirmDetails" class="bg-slate-50/80 rounded-xl border border-slate-100 p-4 text-sm text-slate-700 leading-relaxed space-y-2">
                <!-- Example structured content - will be dynamically replaced -->
                <div class="flex items-start gap-2 text-slate-600">
                    <x-lucide-user class="w-3.5 h-3.5 mt-0.5 text-slate-400 flex-shrink-0" />
                    <span class="font-medium">Name:</span>
                    <span class="text-slate-700">Sarah Johnson</span>
                </div>
                <div class="flex items-start gap-2 text-slate-600">
                    <x-lucide-calendar class="w-3.5 h-3.5 mt-0.5 text-slate-400 flex-shrink-0" />
                    <span class="font-medium">Date of birth:</span>
                    <span class="text-slate-700">May 15, 1984</span>
                </div>
                <div class="flex items-start gap-2 text-slate-600">
                    <x-lucide-phone class="w-3.5 h-3.5 mt-0.5 text-slate-400 flex-shrink-0" />
                    <span class="font-medium">Contact:</span>
                    <span class="text-slate-700">+1 (555) 123-4567</span>
                </div>
                <div class="mt-3 pt-2 border-t border-slate-200 text-xs text-amber-600 bg-amber-50/50 -mx-2 px-2 py-1.5 rounded-md flex items-center gap-2">
                    <x-lucide-alert-circle class="w-3.5 h-3.5" />
                    <span>This action will register the patient in the system</span>
                </div>
            </div>
        </div>
        
        <!-- Footer with improved button hierarchy and spacing -->
        <div class="px-5 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-2.5">
            <button type="button" id="receptionRegisterPatientConfirmCancel" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-1">
                Cancel
            </button>
            <button type="button" id="receptionRegisterPatientConfirmOk" class="px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-sm transition-all duration-150">
                Confirm registration
            </button>
        </div>
    </div>
</div>

<!-- Parent Picker Modal (2-panel, matching walk-ins selector) -->
<div id="receptionPrParentPickerOverlay" class="hidden fixed inset-0 z-[80] bg-slate-900/50 items-center justify-center p-4">
    <div class="w-full max-w-5xl h-[88vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] overflow-hidden grid grid-cols-1 md:grid-cols-2">
        <div class="border-b md:border-b-0 md:border-r border-slate-200 flex flex-col min-h-0">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0 flex items-start justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Select Parent</div>
                    <div class="text-[0.72rem] text-slate-500">Select a parent patient for the dependent account.</div>
                </div>
                <button type="button" id="receptionPrParentPickerClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                <label for="receptionPrParentPickerSearch" class="block text-[0.65rem] uppercase tracking-widest text-slate-400 mb-1">Search</label>
                <input id="receptionPrParentPickerSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by name, email, contact, or address">
                <div id="receptionPrParentPickerListLabel" class="mt-2 text-[0.7rem] text-slate-500">Latest patients</div>
            </div>
            <div id="receptionPrParentPickerListBody" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Loading patients…</div>
            </div>
        </div>
        <div class="flex flex-col min-h-0 bg-slate-50/60">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                <div class="text-sm font-semibold text-slate-900">Details</div>
                <div class="text-[0.72rem] text-slate-500">Review the selected patient before confirming.</div>
            </div>
            <div id="receptionPrParentPickerDetailBody" class="flex-1 overflow-y-auto p-4">
                <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a patient to view details.</div>
            </div>
            <div class="px-4 py-3 border-t border-slate-100 bg-white shrink-0 flex items-center justify-end gap-2">
                <button type="button" id="receptionPrParentPickerCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" id="receptionPrParentPickerConfirmBtn" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-60" disabled>Select Parent</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('receptionRegisterPatientForm')
        var errorBox = document.getElementById('receptionRegisterPatientError')
        var successBox = document.getElementById('receptionRegisterPatientSuccess')
        var credentialsBox = document.getElementById('receptionRegisterPatientCredentials')
        var dependentToggle = document.getElementById('reception_patient_is_dependent')
        var parentSection = document.getElementById('receptionDependentParentSection')
        var parentSearchInput = document.getElementById('reception_parent_search')
        var parentUserIdInput = document.getElementById('reception_parent_user_id')
        var parentPreview = document.getElementById('receptionParentPreview')
        var relationshipSection = document.getElementById('receptionDependentRelationshipSection')
        var relationshipSelect = document.getElementById('reception_dependent_relationship')
        var submitButton = document.getElementById('receptionRegisterPatientSubmit')
        var emailLabel = document.getElementById('reception_patient_email_label')
        var contactLabel = document.getElementById('reception_patient_contact_label')
        var hint = document.getElementById('receptionRegisterPatientHint')
        var selectedParent = null
        var successTimer = null

        var confirmOverlay = document.getElementById('receptionRegisterPatientConfirmOverlay')
        var confirmMessage = document.getElementById('receptionRegisterPatientConfirmMessage')
        var confirmDetails = document.getElementById('receptionRegisterPatientConfirmDetails')
        var confirmOk = document.getElementById('receptionRegisterPatientConfirmOk')
        var confirmCancel = document.getElementById('receptionRegisterPatientConfirmCancel')
        var confirmResolver = null

        function escapeHtml(input) {
            var s = String(input == null ? '' : input)
            return s
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
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

        function showRegisterPatientError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showRegisterPatientSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function showCredentials(payload) {
            if (!credentialsBox) return
            if (!payload) {
                credentialsBox.textContent = ''
                credentialsBox.classList.add('hidden')
                return
            }
            try {
                credentialsBox.textContent = JSON.stringify(payload, null, 2)
            } catch (_) {
                credentialsBox.textContent = String(payload)
            }
            credentialsBox.classList.remove('hidden')
        }

        function confirmAction(message, detailsHtml) {
            return new Promise(function (resolve) {
                if (!confirmOverlay || !confirmMessage || !confirmOk || !confirmCancel) {
                    resolve(window.confirm(message || 'Are you sure?'))
                    return
                }
                confirmMessage.textContent = message || 'Are you sure?'
                if (confirmDetails) {
                    confirmDetails.innerHTML = detailsHtml || ''
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

        function normalizePHContact(value) {
            var raw = String(value || '').trim()
            if (!raw) return ''
            var compact = raw.replace(/[^\d+]/g, '')
            if (compact === '+63') return ''

            var digits = compact.replace(/[^\d]/g, '')
            if (!digits) return ''

            if (digits.length === 11 && digits.indexOf('09') === 0) {
                return '+63' + digits.slice(1)
            }
            if (digits.length === 10 && digits.indexOf('9') === 0) {
                return '+63' + digits
            }
            if (digits.length === 12 && digits.indexOf('639') === 0) {
                return '+' + digits
            }
            if (compact.indexOf('+') === 0 && digits.length === 12 && digits.indexOf('639') === 0) {
                return '+' + digits
            }

            return ''
        }

        function isValidPHContact(value) {
            return /^\+639\d{9}$/.test(String(value || ''))
        }

        function setSubmitting(isSubmitting) {
            if (!submitButton) return
            submitButton.disabled = !!isSubmitting
            submitButton.textContent = isSubmitting ? 'Registering…' : (dependentToggle && dependentToggle.checked ? 'Register dependent' : 'Register patient')
        }

        function setParentSelection(parent) {
            selectedParent = parent || null
            if (parentUserIdInput) parentUserIdInput.value = parent && parent.user_id ? String(parent.user_id) : ''

            if (parentPreview) {
                if (!parent) {
                    parentPreview.classList.add('hidden')
                } else {
                    var name = [parent.firstname, parent.middlename, parent.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                    if (!name) name = 'User #' + parent.user_id
                    var nameEl = document.getElementById('receptionParentPreviewName')
                    var addrEl = document.getElementById('receptionParentPreviewAddress')
                    var contactEl = document.getElementById('receptionParentPreviewContact')
                    if (nameEl) nameEl.textContent = name
                    if (addrEl) addrEl.textContent = parent.address ? 'Address: ' + String(parent.address) : ''
                    if (contactEl) contactEl.textContent = parent.contact_number ? 'Contact: ' + String(parent.contact_number) : ''
                    if (parentSearchInput) parentSearchInput.value = name
                    parentPreview.classList.remove('hidden')
                }
            }

            if (relationshipSection) {
                relationshipSection.classList.toggle('hidden', !(dependentToggle && dependentToggle.checked && !!parent))
            }
            if (relationshipSelect) {
                relationshipSelect.required = !!(dependentToggle && dependentToggle.checked && !!parent)
                if (!parent) relationshipSelect.value = ''
            }

            var addressInput = document.getElementById('reception_patient_address')
            if (addressInput && dependentToggle && dependentToggle.checked && parent && (!String(addressInput.value || '').trim())) {
                if (parent.address) {
                    addressInput.value = String(parent.address)
                }
            }
        }

        function setDependentMode(on) {
            var enabled = !!on
            if (parentSection) parentSection.classList.toggle('hidden', !enabled)
            if (emailLabel) emailLabel.textContent = enabled ? 'Email (optional)' : 'Email'
            if (contactLabel) contactLabel.textContent = enabled ? 'Contact number (optional)' : 'Contact number'
            var emailInput = document.getElementById('reception_patient_email')
            if (emailInput) emailInput.required = !enabled
            var addressLabel = document.querySelector('label[for="reception_patient_address"]')
            if (addressLabel) addressLabel.textContent = enabled ? 'Address (optional)' : 'Address'
            if (hint) {
                hint.textContent = enabled
                    ? 'Email is optional for dependent accounts. If omitted, activation may require adding an email later.'
                    : 'Email is required for patient accounts.'
            }
            if (submitButton) submitButton.textContent = enabled ? 'Register dependent' : 'Register patient'
            if (!enabled) {
                if (parentSearchInput) parentSearchInput.value = ''
                setParentSelection(null)
                if (relationshipSection) relationshipSection.classList.add('hidden')
                if (relationshipSelect) {
                    relationshipSelect.required = false
                    relationshipSelect.value = ''
                }
            }
        }

        if (dependentToggle) {
            dependentToggle.addEventListener('change', function () {
                setDependentMode(!!dependentToggle.checked)
            })
            setDependentMode(!!dependentToggle.checked)
        }

        // Parent picker modal state
        var parentPickerState = { items: [], activeItem: null }

        function openParentPicker() {
            if (!parentPickerOverlay) return
            parentPickerState.items = []
            parentPickerState.activeItem = null
            parentPickerOverlay.classList.remove('hidden')
            parentPickerOverlay.classList.add('flex')
            fetchParentPickerPatients('')
        }

        function closeParentPicker() {
            if (!parentPickerOverlay) return
            parentPickerOverlay.classList.add('hidden')
            parentPickerOverlay.classList.remove('flex')
        }

        function fetchParentPickerPatients(query) {
            if (typeof apiFetch !== 'function') return
            var url = "{{ url('/api/patients') }}?per_page=15&parents_only=1"
            var trimmed = String(query || '').trim()
            if (trimmed) url += '&search=' + encodeURIComponent(trimmed)
            if (parentPickerListLabel) parentPickerListLabel.textContent = trimmed ? 'Search results' : 'Latest patients'
            if (parentPickerListBody) parentPickerListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading patients…</div>'
            apiFetch(url, { method: 'GET' })
                .then(function (r) {
                    return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } })
                })
                .then(function (result) {
                    if (!result.ok) {
                        if (parentPickerListBody) parentPickerListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load patients.</div>'
                        return
                    }
                    var list = result.data && Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    parentPickerState.items = list
                    renderParentPickerList(list)
                })
                .catch(function () {
                    if (parentPickerListBody) parentPickerListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Network error.</div>'
                })
        }

        function renderParentPickerList(items) {
            if (!parentPickerListBody) return
            if (!items.length) {
                parentPickerListBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No patients found.</div>'
                renderParentPickerDetail(null)
                return
            }
            var activeId = parentPickerState.activeItem ? String(parentPickerState.activeItem.user_id) : null
            var html = ''
            items.forEach(function (p, idx) {
                var name = [p.firstname, p.middlename, p.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                if (!name) name = 'User #' + p.user_id
                var meta = []
                if (p.email) meta.push(p.email)
                if (p.contact_number) meta.push(p.contact_number)
                var isActive = activeId && String(p.user_id) === activeId
                html += '<button type="button" class="reception-pr-parent-pick w-full rounded-xl border px-3 py-3 text-left transition-colors ' + (isActive ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white hover:border-green-200 hover:bg-slate-50') + '" data-idx="' + idx + '">' +
                    '<div class="text-[0.8rem] font-semibold text-slate-900 truncate">' + escapeHtml(name) + '</div>' +
                    '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || ('#' + p.user_id)) + '</div>' +
                '</button>'
            })
            parentPickerListBody.innerHTML = html
            Array.prototype.forEach.call(parentPickerListBody.querySelectorAll('button.reception-pr-parent-pick'), function (btn) {
                btn.addEventListener('click', function () {
                    var idx = parseInt(btn.getAttribute('data-idx') || '-1', 10)
                    var chosen = parentPickerState.items[idx]
                    if (chosen) {
                        parentPickerState.activeItem = chosen
                        renderParentPickerList(parentPickerState.items)
                        renderParentPickerDetail(chosen)
                    }
                })
            })
            renderParentPickerDetail(parentPickerState.activeItem)
        }

        function renderParentPickerDetail(patient) {
            if (!parentPickerDetailBody) return
            if (!patient) {
                parentPickerDetailBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Select a patient to view details.</div>'
                updateParentPickerConfirmState()
                return
            }
            var name = [patient.firstname, patient.middlename, patient.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            if (!name) name = 'User #' + patient.user_id
            var age = patient.birthdate ? (function () {
                var bd = new Date(patient.birthdate)
                if (isNaN(bd.getTime())) return ''
                var diff = new Date() - bd
                return Math.floor(diff / 31557600000)
            })() : ''
            parentPickerDetailBody.innerHTML = '' +
                '<div class="space-y-3">' +
                    '<div class="rounded-xl border border-slate-200 bg-white p-4">' +
                        '<div class="flex items-start gap-3">' +
                            '<div class="w-10 h-10 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-600 shrink-0">' +
                                '<svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M3 21v-2a7 7 0 0 1 14 0v2"/></svg>' +
                            '</div>' +
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
                            '<div class="text-slate-500">Sex</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(patient.sex ? String(patient.sex) : '-') + '</div>' +
                            '<div class="text-slate-500">Contact</div>' +
                            '<div class="text-slate-800 font-medium">' + escapeHtml(patient.contact_number || '-') + '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            updateParentPickerConfirmState()
        }

        function updateParentPickerConfirmState() {
            if (!parentPickerConfirmBtn) return
            parentPickerConfirmBtn.disabled = !parentPickerState.activeItem
        }

        // Wire up parent picker browse button
        if (parentSearchInput) {
            parentSearchInput.addEventListener('click', openParentPicker)
        }
        var parentPickerBtn = document.getElementById('receptionPrParentPickerBtn')
        if (parentPickerBtn) {
            parentPickerBtn.addEventListener('click', openParentPicker)
        }

        // Wire up parent picker modal elements
        var parentPickerOverlay = document.getElementById('receptionPrParentPickerOverlay')
        var parentPickerClose = document.getElementById('receptionPrParentPickerClose')
        var parentPickerCancel = document.getElementById('receptionPrParentPickerCancel')
        var parentPickerConfirmBtn = document.getElementById('receptionPrParentPickerConfirmBtn')
        var parentPickerSearch = document.getElementById('receptionPrParentPickerSearch')
        var parentPickerListLabel = document.getElementById('receptionPrParentPickerListLabel')
        var parentPickerListBody = document.getElementById('receptionPrParentPickerListBody')
        var parentPickerDetailBody = document.getElementById('receptionPrParentPickerDetailBody')

        if (parentPickerClose) parentPickerClose.addEventListener('click', closeParentPicker)
        if (parentPickerCancel) parentPickerCancel.addEventListener('click', closeParentPicker)
        if (parentPickerConfirmBtn) {
            parentPickerConfirmBtn.addEventListener('click', function () {
                var chosen = parentPickerState.activeItem
                if (chosen) {
                    setParentSelection(chosen)
                    closeParentPicker()
                }
            })
        }

        // Search within parent picker modal
        var parentPickerSearchTimer = null
        if (parentPickerSearch) {
            parentPickerSearch.addEventListener('input', function () {
                var q = String(parentPickerSearch.value || '').trim()
                if (parentPickerSearchTimer) clearTimeout(parentPickerSearchTimer)
                parentPickerSearchTimer = setTimeout(function () {
                    fetchParentPickerPatients(q)
                }, 300)
            })
        }

        // Remove parent button
        var parentRemoveBtn = document.getElementById('receptionPrParentRemoveBtn')
        if (parentRemoveBtn) {
            parentRemoveBtn.addEventListener('click', function () {
                setParentSelection(null)
                if (parentSearchInput) parentSearchInput.value = ''
            })
        }

        var contactInput = document.getElementById('reception_patient_contact')
        // Auto-format phone input (same as staff_management.blade.php)
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
                if (raw.length > 0) formatted += raw.slice(0,3)
                if (raw.length > 3) formatted += ' ' + raw.slice(3,6)
                if (raw.length > 6) formatted += ' ' + raw.slice(6)
                this.value = formatted
                var newLen = this.value.length
                this.setSelectionRange(cursor + (newLen - oldLen), cursor + (newLen - oldLen))
            })
        }
        setupPhoneFormat(contactInput)

        function fetchPossibleDuplicates(payload) {
            if (typeof apiFetch !== 'function') return Promise.resolve([])
            var parts = [payload.firstname, payload.middlename, payload.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
            if (!parts) return Promise.resolve([])

            return apiFetch("{{ url('/api/patients') }}?per_page=10&search=" + encodeURIComponent(parts), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) return []
                    var list = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])

                    function normName(v) {
                        return String(v || '').trim().toLowerCase()
                    }

                    var first = normName(payload.firstname)
                    var middle = normName(payload.middlename)
                    var last = normName(payload.lastname)
                    var birth = String(payload.birthdate || '').trim()
                    var email = normName(payload.email || '')
                    var contact = String(payload.contact_number || '').trim()

                    return list.filter(function (p) {
                        if (!p) return false
                        var pf = normName(p.firstname)
                        var pm = normName(p.middlename)
                        var pl = normName(p.lastname)
                        if (!pf || !pl) return false

                        var sameName = pf === first && pl === last && (middle ? pm === middle : true)
                        if (!sameName) return false

                        var matches = 0
                        if (birth && String(p.birthdate || '').trim() === birth) matches += 1
                        if (email && normName(p.email || '') === email) matches += 1
                        if (contact && String(p.contact_number || '').trim() === contact) matches += 1

                        return matches > 0
                    }).slice(0, 5)
                })
                .catch(function () {
                    return []
                })
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault()

                showRegisterPatientError('')
                showRegisterPatientSuccess('')
                showCredentials(null)

                var firstnameInput = document.getElementById('reception_patient_firstname')
                var middlenameInput = document.getElementById('reception_patient_middlename')
                var lastnameInput = document.getElementById('reception_patient_lastname')
                var birthdateInput = document.getElementById('reception_patient_birthdate')
                var sexInput = document.getElementById('reception_patient_sex')
                var contactInput2 = document.getElementById('reception_patient_contact')
                var addressInput = document.getElementById('reception_patient_address')
                var emailInput = document.getElementById('reception_patient_email')
                var isDependent = dependentToggle ? !!dependentToggle.checked : false
                var parentId = parentUserIdInput ? parseInt(parentUserIdInput.value || '0', 10) : 0
                var relationship = relationshipSelect ? String(relationshipSelect.value || '') : ''

                var fName = firstnameInput ? normalizePersonName(firstnameInput.value) : ''
                var mName = middlenameInput ? normalizePersonName(middlenameInput.value) : ''
                var lName = lastnameInput ? normalizePersonName(lastnameInput.value) : ''

                if (!isValidPersonName(fName) || (mName !== '' && !isValidPersonName(mName)) || !isValidPersonName(lName)) {
                    showRegisterPatientError('Name fields must contain letters only (accents allowed), plus hyphens, apostrophes, periods, and middle dots.')
                    return
                }

                if (!fName || !lName) {
                    showRegisterPatientError('Firstname and lastname are required.')
                    return
                }

                if (firstnameInput) firstnameInput.value = fName
                if (middlenameInput) middlenameInput.value = mName
                if (lastnameInput) lastnameInput.value = lName

                if (typeof apiFetch !== 'function') {
                    showRegisterPatientError('API client is not available.')
                    return
                }

                var body = {
                    firstname: fName,
                    middlename: mName,
                    lastname: lName,
                    birthdate: birthdateInput ? birthdateInput.value : '',
                    sex: sexInput ? sexInput.value : '',
                    contact_number: '',
                    address: addressInput ? addressInput.value.trim() : ''
                }

                if (!body.birthdate) {
                    showRegisterPatientError('Birthdate is required.')
                    return
                }

                var email = emailInput ? emailInput.value.trim() : ''
                if (!isDependent) {
                    if (!email) {
                        showRegisterPatientError('Email is required for patient accounts.')
                        return
                    }
                    body.email = email
                } else if (email) {
                    body.email = email
                }

                var rawContact = contactInput2 ? contactInput2.value : ''
                var normalizedContact = normalizePHContact(rawContact)
                if (isDependent) {
                    if (normalizedContact) {
                        if (!isValidPHContact(normalizedContact)) {
                            showRegisterPatientError('Please enter a valid PH contact number (e.g. +639750443410).')
                            return
                        }
                        body.contact_number = normalizedContact
                        if (contactInput2) contactInput2.value = normalizedContact
                    } else {
                        delete body.contact_number
                        if (contactInput2) contactInput2.value = '+63'
                    }
                } else {
                    if (!normalizedContact || !isValidPHContact(normalizedContact)) {
                        showRegisterPatientError('Please enter a valid PH contact number (e.g. +639750443410).')
                        return
                    }
                    body.contact_number = normalizedContact
                    if (contactInput2) contactInput2.value = normalizedContact
                }

                var url = isDependent ? "{{ url('/api/dependents') }}" : "{{ url('/api/patients') }}"
                if (isDependent) {
                    if (!parentId) {
                        showRegisterPatientError('Please select the parent patient first.')
                        return
                    }
                    body.parent_user_id = parentId
                    if (!relationship) {
                        showRegisterPatientError('Please select the relationship.')
                        return
                    }
                    body.relationship = relationship
                    if ((!body.address || !String(body.address).trim()) && selectedParent && selectedParent.address) {
                        body.address = String(selectedParent.address)
                    }
                }

                function maskHalf(value) {
                    var s = String(value == null ? '' : value)
                    if (!s) return '-'
                    var visible = Math.ceil(s.length / 2)
                    return s.slice(0, visible) + new Array(Math.max(0, s.length - visible) + 1).join('*')
                }

                function buildConfirmDetails(dupes) {
                    var name = [body.firstname, body.middlename, body.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                    var details = '<div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">' +
                        '<div class="text-[0.7rem] text-slate-500">Name</div>' +
                        '<div class="text-[0.8rem] font-semibold text-slate-800">' + escapeHtml(name || '-') + '</div>' +
                        '<div class="mt-2 grid grid-cols-2 gap-2">' +
                            '<div>' +
                                '<div class="text-[0.7rem] text-slate-500">Birthdate</div>' +
                                '<div class="text-[0.78rem] text-slate-700">' + escapeHtml(body.birthdate || '-') + '</div>' +
                            '</div>' +
                            '<div>' +
                                '<div class="text-[0.7rem] text-slate-500">Sex</div>' +
                                '<div class="text-[0.78rem] text-slate-700">' + escapeHtml(body.sex || '-') + '</div>' +
                            '</div>' +
                            '<div>' +
                                '<div class="text-[0.7rem] text-slate-500">Contact</div>' +
                                '<div class="text-[0.78rem] text-slate-700">' + escapeHtml(body.contact_number || '-') + '</div>' +
                            '</div>' +
                            '<div>' +
                                '<div class="text-[0.7rem] text-slate-500">Email</div>' +
                                '<div class="text-[0.78rem] text-slate-700">' + escapeHtml(body.email || '-') + '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="mt-2">' +
                            '<div class="text-[0.7rem] text-slate-500">Address</div>' +
                            '<div class="text-[0.78rem] text-slate-700">' + escapeHtml(body.address || '-') + '</div>' +
                        '</div>' +
                        (isDependent ? (
                            '<div class="mt-2 grid grid-cols-2 gap-2">' +
                                '<div>' +
                                    '<div class="text-[0.7rem] text-slate-500">Parent</div>' +
                                    '<div class="text-[0.78rem] text-slate-700">' + escapeHtml((selectedParent && selectedParent.firstname ? [selectedParent.firstname, selectedParent.middlename, selectedParent.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim() : '') || ('#' + String(parentId || ''))) + '</div>' +
                                '</div>' +
                                '<div>' +
                                    '<div class="text-[0.7rem] text-slate-500">Relationship</div>' +
                                    '<div class="text-[0.78rem] text-slate-700">' + escapeHtml(relationship || '-') + '</div>' +
                                '</div>' +
                            '</div>'
                        ) : '') +
                    '</div>'

                    if (!dupes || !dupes.length) return details

                    var list = dupes.map(function (p) {
                        var nm = [p.firstname, p.middlename, p.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                        var meta = []
                        if (p.birthdate) meta.push('Birthdate: ' + escapeHtml(p.birthdate))
                        if (p.contact_number) meta.push('Contact: ' + escapeHtml(p.contact_number))
                        if (p.email) meta.push('Email: ' + escapeHtml(p.email))
                        return '<div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2">' +
                            '<div class="text-[0.78rem] font-semibold text-slate-900">' + escapeHtml(nm || ('Patient #' + p.user_id)) + '</div>' +
                            '<div class="text-[0.72rem] text-slate-600 mt-0.5">#' + escapeHtml(p.user_id) + (meta.length ? ' • ' + meta.join(' • ') : '') + '</div>' +
                        '</div>'
                    }).join('')

                    return details + '<div class="mt-3">' +
                        '<div class="text-[0.72rem] font-semibold text-slate-700 mb-1">Similar patients found</div>' +
                        '<div class="space-y-2">' + list + '</div>' +
                    '</div>'
                }

                function buildStrongMatchDetails(matches) {
                    var p = matches && matches.length ? matches[0] : null
                    if (!p) return ''
                    var nm = [p.firstname, p.middlename, p.lastname].filter(function (v) { return String(v || '').trim() !== '' }).join(' ').trim()
                    return '<div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2">' +
                        '<div class="text-[0.7rem] text-amber-700 font-semibold mb-1">Existing patient (masked)</div>' +
                        '<div class="text-[0.78rem] text-slate-900 font-semibold">' + escapeHtml(maskHalf(nm || ('Patient #' + p.user_id))) + '</div>' +
                        '<div class="mt-2 grid grid-cols-2 gap-2 text-[0.75rem] text-slate-700">' +
                            '<div><span class="text-slate-500">Birthdate:</span> ' + escapeHtml(maskHalf(p.birthdate || '-')) + '</div>' +
                            '<div><span class="text-slate-500">Sex:</span> ' + escapeHtml(maskHalf(p.sex || '-')) + '</div>' +
                            '<div><span class="text-slate-500">Contact:</span> ' + escapeHtml(maskHalf(p.contact_number || '-')) + '</div>' +
                            '<div><span class="text-slate-500">Address:</span> ' + escapeHtml(maskHalf(p.address || '-')) + '</div>' +
                        '</div>' +
                    '</div>'
                }

                setSubmitting(true)

                fetchPossibleDuplicates(body)
                    .then(function (dupes) {
                        function normalizeText(v) {
                            return String(v || '').trim().toLowerCase().replace(/\s+/g, ' ')
                        }
                        var strongMatches = (dupes || []).filter(function (p) {
                            if (!p) return false
                            return normalizeText(p.firstname) === normalizeText(body.firstname) &&
                                normalizeText(p.middlename) === normalizeText(body.middlename) &&
                                normalizeText(p.lastname) === normalizeText(body.lastname) &&
                                String(p.birthdate || '').trim() === String(body.birthdate || '').trim() &&
                                normalizeText(p.sex) === normalizeText(body.sex) &&
                                String(p.contact_number || '').trim() === String(body.contact_number || '').trim() &&
                                normalizeText(p.address) === normalizeText(body.address)
                        })

                        if (strongMatches.length) {
                            return confirmAction(
                                'There’s a patient with similar info. Do you still want to register this patient?',
                                buildStrongMatchDetails(strongMatches)
                            )
                        }

                        return confirmAction('Register this patient?', buildConfirmDetails(dupes))
                    })
                    .then(function (confirmed) {
                        if (!confirmed) {
                            setSubmitting(false)
                            return
                        }

                        return apiFetch(url, {
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
                                    var message2 = 'Failed to register patient.'
                                    if (result.data && result.data.message) {
                                        message2 = result.data.message
                                    }
                                    showRegisterPatientError(message2)
                                    return
                                }

                                var payload = result.data || null
                                var credentials = payload && payload.credentials ? payload.credentials : null
                                var activation = payload && payload.activation ? payload.activation : null

                                if (isDependent) {
                                    if (activation && activation.requires_email) {
                                        showRegisterPatientSuccess('Dependent registered. ' + (activation.prompt || 'Add email to activate account.'))
                                    } else {
                                        showRegisterPatientSuccess('Dependent has been registered successfully.')
                                    }
                                    showCredentials(null)
                                } else {
                                    showRegisterPatientSuccess('Patient has been registered successfully. Credentials were sent to the email address.')
                                    showCredentials(null)
                                }

                                if (firstnameInput) firstnameInput.value = ''
                                if (middlenameInput) middlenameInput.value = ''
                                if (lastnameInput) lastnameInput.value = ''
                                if (birthdateInput) birthdateInput.value = ''
                                if (sexInput) sexInput.value = ''
                                if (contactInput2) contactInput2.value = '+63'
                                if (addressInput) addressInput.value = ''
                                if (emailInput) emailInput.value = ''
                                if (dependentToggle) dependentToggle.checked = false
                                setDependentMode(false)
                            })
                            .catch(function () {
                                showRegisterPatientError('Network error while registering patient.')
                            })
                            .finally(function () {
                                setSubmitting(false)
                            })
                    })
                    .catch(function () {
                        setSubmitting(false)
                        showRegisterPatientError('Unable to validate registration right now.')
                    })
            })
        }

        // ── Tab switching ──
        var tabRegister = document.getElementById('receptionPatientTabRegister')
        var tabRecords = document.getElementById('receptionPatientTabRecords')
        var panelRegister = document.getElementById('receptionRegisterPatientPanel')
        var panelRecords = document.getElementById('receptionPatientRecordsPanel')

        function switchPatientTab(tab) {
            var isRegister = tab === 'register'
            tabRegister.className = 'px-4 py-3 text-xs font-semibold ' + (isRegister ? 'text-white bg-green-500 border-b-2 border-green-600' : 'text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200')
            tabRecords.className = 'px-4 py-3 text-xs font-semibold ' + (!isRegister ? 'text-white bg-green-500 border-b-2 border-green-600' : 'text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200')
            panelRegister.classList.toggle('hidden', !isRegister)
            panelRecords.classList.toggle('hidden', isRegister)
        }

        if (tabRegister) tabRegister.addEventListener('click', function () { switchPatientTab('register') })
        if (tabRecords) tabRecords.addEventListener('click', function () { switchPatientTab('records') })

        // ── Patient Records (duplicated from admin patient_records) ──
        var recDefaultProfilePicHtml = '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
        var recPatientsError = document.getElementById('receptionPrPatientsError')
        var recPatientsSearch = document.getElementById('reception_pr_patients_search')
        var recSortSelect = document.getElementById('reception_pr_sort')
        var recPatientsTableBody = document.getElementById('reception_pr_patients_table_body')
        var recPagination = document.getElementById('receptionPrPagination')
        var recPatientsRows = []
        var recPerPage = 10
        var recCurrentPage = 1
        var recVisibleCount = 6

        var recActiveAgeFilter = 'all'
        var recAgeFilterButtons = Array.prototype.slice.call(document.querySelectorAll('.reception-pr-age-filter'))
        var recAgeCountAll = document.getElementById('receptionPrAgeCountAll')
        var recAgeCount1_5 = document.getElementById('receptionPrAgeCount1_5')
        var recAgeCount6_12 = document.getElementById('receptionPrAgeCount6_12')
        var recAgeCount13_18 = document.getElementById('receptionPrAgeCount13_18')
        var recAgeCount19_30 = document.getElementById('receptionPrAgeCount19_30')
        var recAgeCount31Up = document.getElementById('receptionPrAgeCount31Up')

        var recOverlay = document.getElementById('receptionPrSlideoverOverlay')
        var recPanel = document.getElementById('receptionPrSlideoverPanel')
        var recPanelClose = document.getElementById('receptionPrPanelClose')
        var recPanelProfilePic = document.getElementById('receptionPrPanelProfilePic')
        var recPanelEditInfoBtn = document.getElementById('receptionPrPanelEditInfoBtn')
        var recPrDetailFirstname = document.getElementById('receptionPrDetailFirstname')
        var recPrDetailMiddlename = document.getElementById('receptionPrDetailMiddlename')
        var recPrDetailLastname = document.getElementById('receptionPrDetailLastname')
        var recPrDetailBirthdate = document.getElementById('receptionPrDetailBirthdate')
        var recPrDetailAddress = document.getElementById('receptionPrDetailAddress')
        var recPrDetailSex = document.getElementById('receptionPrDetailSex')
        var recPrDetailCivilStatus = document.getElementById('receptionPrDetailCivilStatus')
        var recPrDetailNationality = document.getElementById('receptionPrDetailNationality')
        var recPrDetailContact = document.getElementById('receptionPrDetailContact')
        var recPrDetailPhic = document.getElementById('receptionPrDetailPhic')
        var recPrDetailOccupation = document.getElementById('receptionPrDetailOccupation')
        var recPrDetailEmergContact = document.getElementById('receptionPrDetailEmergContact')
        var recPrDetailEmergNumber = document.getElementById('receptionPrDetailEmergNumber')
        var recPanelVerificationStatus = document.getElementById('receptionPrPanelVerificationStatus')
        var recPanelPatientType = document.getElementById('receptionPrPanelPatientType')
        var recPanelVerificationId = document.getElementById('receptionPrPanelVerificationId')

        var recPanelTabBackground = document.getElementById('receptionPrPanelTabBackground')
        var recPanelTabVisits = document.getElementById('receptionPrPanelTabVisits')
        var recPanelTabVitals = document.getElementById('receptionPrPanelTabVitals')
        var recPanelTabDependents = document.getElementById('receptionPrPanelTabDependents')

        var recPatientEditOverlay = document.getElementById('receptionPrEditOverlay')
        var recPatientEditClose = document.getElementById('receptionPrEditClose')
        var recPatientEditCancel = document.getElementById('receptionPrEditCancel')
        var recPatientEditForm = document.getElementById('receptionPrEditForm')
        var recPatientEditError = document.getElementById('receptionPrEditError')
        var recPatientEditSubtitle = document.getElementById('receptionPrEditSubtitle')
        var recPatientEditFirstname = document.getElementById('receptionPrEditFirstname')
        var recPatientEditMiddlename = document.getElementById('receptionPrEditMiddlename')
        var recPatientEditLastname = document.getElementById('receptionPrEditLastname')
        var recPatientEditSexMale = document.querySelector('input[name="receptionPrEditSex"][value="Male"]')
        var recPatientEditSexFemale = document.querySelector('input[name="receptionPrEditSex"][value="Female"]')
        var recPatientEditBirthdate = document.getElementById('receptionPrEditBirthdate')
        var recPatientEditCivilStatus = document.getElementById('receptionPrEditCivilStatus')
        var recPatientEditNationalitySelect = document.getElementById('receptionPrEditNationalitySelect')
        var recPatientEditNationality = document.getElementById('receptionPrEditNationality')
        var recPatientEditNationalityField = document.getElementById('receptionPrEditNationalityField')
        var recPatientEditAddress = document.getElementById('receptionPrEditAddress')
        var recPatientEditContact = document.getElementById('receptionPrEditContact')
        var recPatientEditPhilhealth = document.getElementById('receptionPrEditPhilhealth')
        var recPatientEditOccupation = document.getElementById('receptionPrEditOccupation')
        var recPatientEditEmergencyContact = document.getElementById('receptionPrEditEmergencyContact')
        var recPatientEditEmergencyContactNumber = document.getElementById('receptionPrEditEmergencyContactNumber')
        var recPatientEditProfileUpload = document.getElementById('receptionPrEditProfileUpload')
        var recPatientEditProfilePreview = document.getElementById('receptionPrEditProfilePreview')
        var recPatientEditSave = document.getElementById('receptionPrEditSave')
        var recPatientEditSpinner = document.getElementById('receptionPrEditSpinner')
        var recPatientEditSaveLabel = document.getElementById('receptionPrEditSaveLabel')

        var recPatientEditConfirmOverlay = document.getElementById('receptionPrEditConfirmOverlay')
        var recPatientEditConfirmMessage = document.getElementById('receptionPrEditConfirmMessage')
        var recPatientEditConfirmOk = document.getElementById('receptionPrEditConfirmOk')
        var recPatientEditConfirmCancel = document.getElementById('receptionPrEditConfirmCancel')
        var recPatientEditConfirmResolver = null
        var recEditingPatientId = null

        var recTabDrawer = document.getElementById('receptionPrTabDrawer')
        var recTabDrawerTitle = document.getElementById('receptionPrTabDrawerTitle')
        var recTabDrawerBody = document.getElementById('receptionPrTabDrawerBody')
        var recTabDrawerClose = document.getElementById('receptionPrTabDrawerClose')

        var recCachedMedBgRows = null
        var recCachedVisitRows = null
        var recCachedVitalRows = null
        var recCachedDependentRows = null
        var recCurrentPatientId = null
        var recCurrentPanelTab = null
        var recActiveDependentRecord = null
        var recActiveDependentTab = 'background'
        var recActiveDependentMedBgRows = null
        var recActiveDependentVisitRows = null
        var recActiveDependentVitalRows = null
        var recActiveDependentVerification = null

        function recGetPatientTableRows() {
            return document.querySelectorAll('#reception_pr_patients_table_body .reception-pr-patient-row')
        }

        function recEscHtml(text) {
            return String(text || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;')
        }

        function recShowInlineBox(el, message) { if (!el) return; el.textContent = message || ''; el.classList.toggle('hidden', !message) }
        function recShowPatientEditError(message) { recShowInlineBox(recPatientEditError, message); if (message && typeof showToast === 'function') showToast(message, 'error') }
        function recShowPatientEditSuccess(message) { if (message && typeof showToast === 'function') showToast(message, 'success') }

        function recCategoryLabel(key) { var k = String(key || ''); if (k === 'allergy_food') return 'Food'; if (k === 'allergy_drug') return 'Drug'; if (k === 'condition') return 'Condition'; return k || '-' }

        function recFullName(p, fallback) {
            if (!p) return fallback || '-'
            var parts = []; if (p.firstname) parts.push(String(p.firstname)); if (p.middlename) parts.push(String(p.middlename)); if (p.lastname) parts.push(String(p.lastname))
            var name = parts.join(' ').trim(); if (name) return name; if (p.email) return String(p.email); return fallback || ('#' + (p.user_id || ''))
        }

        function recNameOnly(p) {
            if (!p) return ''; var parts = []; if (p.firstname) parts.push(String(p.firstname)); if (p.middlename) parts.push(String(p.middlename)); if (p.lastname) parts.push(String(p.lastname))
            return parts.join(' ').trim()
        }

        function recAgeFromBirthdate(birthdate) {
            if (!birthdate) return null; var d = new Date(String(birthdate)); if (isNaN(d.getTime())) return null
            var today = new Date(); var age = today.getFullYear() - d.getFullYear(); var m = today.getMonth() - d.getMonth()
            if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--; if (age < 0) return null; return age
        }

        function recMatchesAgeFilter(age, filterKey) {
            if (filterKey === 'all') return true; if (age == null) return false
            if (filterKey === '1_5') return age >= 1 && age <= 5; if (filterKey === '6_12') return age >= 6 && age <= 12
            if (filterKey === '13_18') return age >= 13 && age <= 18; if (filterKey === '19_30') return age >= 19 && age <= 30
            if (filterKey === '31_up') return age >= 31; return true
        }

        function recDisplayValue(value) { return (value != null && value !== '') ? String(value) : '-' }
        function recSexLabel(value) { var text = recDisplayValue(value); if (text === '-') return text; return text.charAt(0).toUpperCase() + text.slice(1) }

        function recSetAgeFilterActiveStyles() {
            recAgeFilterButtons.forEach(function (btn) {
                var key = btn.getAttribute('data-age-filter') || ''; var isActive = key === recActiveAgeFilter
                btn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                if (isActive) btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
                else btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
            })
        }

        function recSetText(el, text) { if (!el) return; el.textContent = text == null ? '' : String(text) }

        function recSetTabButtonActive(btn, isActive) {
            if (!btn) return; btn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
            if (isActive) btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
            else btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
        }

        function recSyncTabButtonState() {
            recSetTabButtonActive(recPanelTabBackground, recCurrentPanelTab === 'background')
            recSetTabButtonActive(recPanelTabVisits, recCurrentPanelTab === 'visits')
            recSetTabButtonActive(recPanelTabVitals, recCurrentPanelTab === 'vitals')
            recSetTabButtonActive(recPanelTabDependents, recCurrentPanelTab === 'dependents')
        }

        function recFormatRecordedAt(value) { var raw = value ? String(value) : ''; if (!raw) return '-'; return raw.replace('T', ' ').slice(0, 16) }
        function recFormatNumeric(value, decimals) { if (value == null || value === '') return '-'; var num = typeof value === 'number' ? value : parseFloat(value); if (isNaN(num)) return '-'; return num.toFixed(decimals == null ? 1 : decimals) }
        function recFormatCurrency(value) { if (value == null || value === '') return '-'; var num = typeof value === 'number' ? value : parseFloat(value); if (isNaN(num)) return '-'; return 'PHP ' + num.toFixed(2) }

        function recOpenPanel() {
            if (recOverlay) { recOverlay.classList.remove('opacity-0', 'pointer-events-none'); recOverlay.classList.add('opacity-100', 'pointer-events-auto') }
            if (recPanel) { recPanel.classList.remove('translate-x-full'); recPanel.classList.add('translate-x-0') }
        }

        function recCloseTabDrawer() {
            if (recTabDrawer) recTabDrawer.classList.add('hidden')
            recCurrentPanelTab = null; recActiveDependentRecord = null; recActiveDependentTab = 'background'
            recActiveDependentMedBgRows = null; recActiveDependentVisitRows = null; recActiveDependentVitalRows = null; recActiveDependentVerification = null
            recSyncTabButtonState()
        }

        function recOpenTabDrawer() { if (recTabDrawer) recTabDrawer.classList.remove('hidden') }

        function recClosePanel() {
            recCurrentPatientId = null; recCachedMedBgRows = null; recCachedVisitRows = null; recCachedVitalRows = null; recCachedDependentRows = null; recCloseTabDrawer()
            if (recOverlay) { recOverlay.classList.add('opacity-0', 'pointer-events-none'); recOverlay.classList.remove('opacity-100', 'pointer-events-auto') }
            if (recPanel) { recPanel.classList.add('translate-x-full'); recPanel.classList.remove('translate-x-0') }
        }

        function recSetPatientEditSubmitting(isSubmitting) {
            if (recPatientEditSave) recPatientEditSave.disabled = !!isSubmitting
            if (recPatientEditSpinner) recPatientEditSpinner.classList.toggle('hidden', !isSubmitting)
            if (recPatientEditSaveLabel) recPatientEditSaveLabel.textContent = isSubmitting ? 'Saving...' : 'Save changes'
        }

        function recUpdatePatientProfilePreview(path) {
            if (!recPatientEditProfilePreview) return
            if (path) recPatientEditProfilePreview.innerHTML = '<img src="' + String(path).replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
            else recPatientEditProfilePreview.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
        }

        function recFormatPhone(val) { var s = String(val || '').replace(/[^\d]/g, ''); if (s.startsWith('63')) s = s.slice(2); if (s.startsWith('0')) s = s.slice(1); if (s.length === 10) return '+63 ' + s.slice(0, 3) + ' ' + s.slice(3, 6) + ' ' + s.slice(6); return val || '' }
        function recParsePhoneRaw(val) { var s = String(val || '').replace(/[^\d]/g, ''); if (s.startsWith('63')) return '+' + s; if (s.startsWith('0')) return '+63' + s.slice(1); return s ? '+63' + s : '' }
        function recFormatPhilhealth(val) { var s = String(val || '').replace(/[^\d]/g, ''); if (s.length >= 2 && s.length <= 4) return s.slice(0, 2) + '-' + s.slice(2); if (s.length > 4 && s.length <= 11) return s.slice(0, 2) + '-' + s.slice(2, 11) + '-' + s.slice(11); if (s.length > 11) return s.slice(0, 2) + '-' + s.slice(2, 11) + '-' + s.slice(11, 12); return s }

        function recNormalizePhilippinesNumber(value) {
            var raw = String(value || '').trim(); if (!raw) return ''
            raw = raw.replace(/\s+/g, '').replace(/-/g, '')
            if (raw.startsWith('+63')) return raw; if (raw.startsWith('63')) return '+' + raw
            if (raw.startsWith('0') && raw.length >= 2) return '+63' + raw.slice(1)
            if (/^\d+$/.test(raw)) return '+63' + raw; return raw
        }

        function recIsValidPhilippinesNumber(value) { return /^\+63\d{10}$/.test(recNormalizePhilippinesNumber(value)) }
        function recIsValidName(value) { var v = String(value || '').trim(); if (v === '') return true; return /^[A-Za-z][A-Za-z\s.'-]*$/.test(v) }

   
        function recLoadPatients() {
            if (!recPatientsTableBody) return
            recPatientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading patients…</td></tr>'
            recShowInlineBox(recPatientsError, '')
            var url = "{{ url('/api/patients') }}" + '?per_page=15'
            apiFetch(url, { method: 'GET' })
                .then(function (res) { return res.json().then(function (d) { return { ok: res.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    if (!r.ok || !r.data) { recPatientsRows = []; recCurrentPage = 1; recRenderPatientTable(); return }
                    var raw = Array.isArray(r.data.data) ? r.data.data.slice() : (Array.isArray(r.data) ? r.data.slice() : [])
                    recPatientsRows = raw.map(function (p) {
                        return { user_id: p.user_id, firstname: p.firstname || '', middlename: p.middlename || '', lastname: p.lastname || '', birthdate: p.birthdate || '', sex: p.sex || '', address: p.address || '', contact_number: p.contact_number || '', email: p.email || '', account_type: p.account_type || '', verification_status: p.verification_status || '', verification_id: p.verification_id || '', philhealth_number: p.philhealth_number || '', nationality: p.nationality || '', civil_status: p.civil_status || '', occupation: p.occupation || '', emergency_contact: p.emergency_contact || '', emergency_contact_number: p.emergency_contact_number || '', profile_photo_url: p.profile_photo_url || '' }
                    })
                    recCurrentPage = 1
                    recUpdateAgeCounts()
                    recRenderPatientTable()
                })
                .catch(function () { recPatientsRows = []; recCurrentPage = 1; recRenderPatientTable() })
        }

        function recUpdateAgeCounts() {
            var counts = { all: 0, '1_5': 0, '6_12': 0, '13_18': 0, '19_30': 0, '31_up': 0 }
            recPatientsRows.forEach(function (p) {
                var age = recAgeFromBirthdate(p.birthdate)
                counts.all++
                if (age == null) return
                ['1_5', '6_12', '13_18', '19_30', '31_up'].forEach(function (k) { if (recMatchesAgeFilter(age, k)) counts[k]++ })
            })
            recSetText(recAgeCountAll, counts.all); recSetText(recAgeCount1_5, counts['1_5']); recSetText(recAgeCount6_12, counts['6_12'])
            recSetText(recAgeCount13_18, counts['13_18']); recSetText(recAgeCount19_30, counts['19_30']); recSetText(recAgeCount31Up, counts['31_up'])
        }

        function recFilterPatients() {
            if (!recPatientsTableBody) return
            recPatientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading patients…</td></tr>'
            recShowInlineBox(recPatientsError, '')
            var q = (recPatientsSearch && recPatientsSearch.value ? String(recPatientsSearch.value).trim() : '')
            var sort = (recSortSelect && recSortSelect.value ? String(recSortSelect.value) : '')
            var url = "{{ url('/api/patients') }}" + '?per_page=15'
            if (q) url += '&search=' + encodeURIComponent(q)
            if (sort) url += '&sort=' + encodeURIComponent(sort)
            apiFetch(url, { method: 'GET' })
                .then(function (res) { return res.json().then(function (d) { return { ok: res.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    if (!r.ok || !r.data) { recPatientsRows = []; recCurrentPage = 1; recRenderPatientTable(); return }
                    var raw = Array.isArray(r.data.data) ? r.data.data.slice() : (Array.isArray(r.data) ? r.data.slice() : [])
                    recPatientsRows = raw.map(function (p) {
                        return { user_id: p.user_id, firstname: p.firstname || '', middlename: p.middlename || '', lastname: p.lastname || '', birthdate: p.birthdate || '', sex: p.sex || '', address: p.address || '', contact_number: p.contact_number || '', email: p.email || '', account_type: p.account_type || '', verification_status: p.verification_status || '', verification_id: p.verification_id || '', philhealth_number: p.philhealth_number || '', nationality: p.nationality || '', civil_status: p.civil_status || '', occupation: p.occupation || '', emergency_contact: p.emergency_contact || '', emergency_contact_number: p.emergency_contact_number || '', profile_photo_url: p.profile_photo_url || '' }
                    })
                    recCurrentPage = 1
                    recUpdateAgeCounts()
                    recRenderPatientTable()
                })
                .catch(function () { recPatientsRows = []; recCurrentPage = 1; recRenderPatientTable() })
        }

        function recRenderPatientTable() {
            if (!recPatientsTableBody) return
            var filtered = recPatientsRows.filter(function (p) {
                var age = recAgeFromBirthdate(p.birthdate)
                return recMatchesAgeFilter(age, recActiveAgeFilter)
            })
            if (!filtered.length) {
                recPatientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">No patients found.</td></tr>'
                if (recPagination) recPagination.innerHTML = ''
                return
            }
            var totalPages = Math.ceil(filtered.length / recPerPage)
            if (recCurrentPage > totalPages) recCurrentPage = totalPages
            var start = (recCurrentPage - 1) * recPerPage
            var end = Math.min(start + recPerPage, filtered.length)
            var pageSlice = filtered.slice(start, end)
            var html = ''
            pageSlice.forEach(function (p) {
                var patientId = p && p.user_id != null ? String(p.user_id) : ''
                var name = recNameOnly(p) || ('Patient #' + p.user_id)
                var address = p && p.address ? String(p.address) : ''
                var age = recAgeFromBirthdate(p && p.birthdate ? String(p.birthdate) : null)
                var sex = p && p.sex ? String(p.sex) : ''
                var type = String(p.account_type || 'patient').toLowerCase()
                if (type === 'admin') type = 'patient'
                var typeLabel = type.charAt(0).toUpperCase() + type.slice(1)
                var profileImg = p && p.profile_photo_url ? String(p.profile_photo_url) : ''
                html += '<tr class="reception-pr-patient-row border-b border-slate-50 last:border-0" data-patient-id="' + recEscHtml(patientId) + '">' +
                    '<td class="py-2 pr-4">' +
                        (profileImg
                            ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-10 h-10 rounded-lg object-cover border border-slate-200">'
                            : '<div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
                        ) +
                    '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + recEscHtml(name) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (address ? recEscHtml(address) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (age != null ? age : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (sex ? recEscHtml(sex.charAt(0).toUpperCase() + sex.slice(1)) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(typeLabel) + '</td>' +
                    '<td class="py-2 pr-4">' +
                        '<button type="button" class="reception-pr-open-panel inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.78rem] font-semibold hover:bg-slate-50" data-patient-id="' + recEscHtml(patientId) + '">View Details and History</button>' +
                    '</td>' +
                '</tr>'
            })
            recPatientsTableBody.innerHTML = html
            recRenderPagination(filtered.length, totalPages)
            recBindPatientRowClicks()
        }

        function recRenderPagination(total, totalPages) {
            if (!recPagination) return
            if (total === 0) { recPagination.innerHTML = ''; return }
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
            html += '<button type="button" class="' + (recCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (recCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var ws = recCurrentPage
            var we = Math.min(ws + recVisibleCount - 1, totalPages)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === recCurrentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>' }
            html += '<button type="button" class="' + (recCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (recCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            recPagination.innerHTML = html
            recPagination.querySelectorAll('button[data-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-page')
                    if (p === 'prev' && recCurrentPage > 1) { recCurrentPage--; recRenderPatientTable() }
                    else if (p === 'next' && recCurrentPage < totalPages) { recCurrentPage++; recRenderPatientTable() }
                    else if (p === 'next-window') { var ns = Math.min(we + 1, totalPages); recCurrentPage = ns; recRenderPatientTable() }
                    else if (p !== 'prev' && p !== 'next') { recCurrentPage = parseInt(p, 10); recRenderPatientTable() }
                })
            })
        }

        function recBindPatientRowClicks() {
            var rows = recGetPatientTableRows()
            rows.forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('.reception-pr-open-panel')) { var pid = row.getAttribute('data-patient-id'); if (pid) recOpenPatientPanel(pid) }
                })
                var viewBtn = row.querySelector('.reception-pr-open-panel')
                if (viewBtn) { viewBtn.addEventListener('click', function (e) { e.stopPropagation(); var pid = row.getAttribute('data-patient-id'); if (pid) recOpenPatientPanel(pid) }) }
            })
        }


        function recOpenPatientPanel(patientId) {
            recCurrentPatientId = patientId
            recCachedMedBgRows = null; recCachedVisitRows = null; recCachedVitalRows = null; recCachedDependentRows = null
            recCloseTabDrawer()
            var row = recPatientsTableBody ? recPatientsTableBody.querySelector('.reception-pr-patient-row[data-patient-id="' + patientId + '"]') : null
            var patientData = recPatientsRows.find(function (p) { return String(p.user_id) === String(patientId) })
            if (!patientData && row) {
                var nameEl = row.querySelector('td:nth-child(2)')
                patientData = { user_id: patientId, firstname: '', lastname: '', middlename: '', birthdate: '', sex: '', address: '', contact_number: '', email: '' }
                if (nameEl) { var parts = (nameEl.textContent || '').trim().split(' '); patientData.firstname = parts[0] || ''; patientData.lastname = parts.slice(1).join(' ') || '' }
            }
            if (!patientData) return
            var fullName = recFullName(patientData, 'Patient #' + patientId)
            recSetText(recPrDetailFirstname, patientData.firstname || '-')
            recSetText(recPrDetailMiddlename, patientData.middlename || '-')
            recSetText(recPrDetailLastname, patientData.lastname || '-')
            recSetText(recPrDetailBirthdate, patientData.birthdate || '-')
            recSetText(recPrDetailAddress, patientData.address || '-')
            recSetText(recPrDetailSex, recSexLabel(patientData.sex))
            recSetText(recPrDetailContact, recFormatPhone(patientData.contact_number))
            if (recPanelProfilePic) recPanelProfilePic.innerHTML = patientData.profile_photo_url ? '<img src="' + String(patientData.profile_photo_url).replace(/"/g, '&quot;') + '" class="w-full h-full object-cover" alt="">' : recDefaultProfilePicHtml
            recSetText(recPrDetailCivilStatus, patientData.civil_status || '-')
            recSetText(recPrDetailNationality, patientData.nationality || '-')
            recSetText(recPrDetailPhic, recFormatPhilhealth(patientData.philhealth_number))
            recSetText(recPrDetailOccupation, patientData.occupation || '-')
            recSetText(recPrDetailEmergContact, patientData.emergency_contact || '-')
            recSetText(recPrDetailEmergNumber, recFormatPhone(patientData.emergency_contact_number))
            recOpenPanel()
            recShowPanelTabsForPatient(patientId)
        }

        function recShowPanelTabsForPatient(patientId) {
            recCloseTabDrawer()
            recCurrentPatientId = patientId
            recCachedMedBgRows = null; recCachedVisitRows = null; recCachedVitalRows = null; recCachedDependentRows = null
            recSetText(recPanelVerificationStatus, 'Loading…')
            recSetText(recPanelPatientType, 'Loading…')
            recSetText(recPanelVerificationId, 'Loading…')
            var url = "{{ url('/api/patients') }}/" + encodeURIComponent(patientId) + '/verification'
            apiFetch(url, { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    var patient = r.ok && r.data && r.data.patient ? r.data.patient : null
                    var d = r.ok && r.data && r.data.dependents_data ? r.data.dependents_data : null
                    if (patient) {
                        recSetText(recPanelVerificationStatus, String(patient.verification_status || 'unverified').charAt(0).toUpperCase() + String(patient.verification_status || 'unverified').slice(1))
                        recSetText(recPanelPatientType, String(patient.account_type || 'patient').charAt(0).toUpperCase() + String(patient.account_type || 'patient').slice(1))
                        recSetText(recPanelVerificationId, patient.verification_id || '-')
                    } else { recSetText(recPanelVerificationStatus, '-'); recSetText(recPanelPatientType, '-'); recSetText(recPanelVerificationId, '-') }
                })
                .catch(function () { recSetText(recPanelVerificationStatus, '-'); recSetText(recPanelPatientType, '-'); recSetText(recPanelVerificationId, '-') })
            recCurrentPanelTab = null
            recSyncTabButtonState()
        }

        function recLoadAndShowTab(tabKey, patientId) {
            if (!recTabDrawerBody) return
            recCurrentPanelTab = tabKey
            recSyncTabButtonState()
            recOpenTabDrawer()
            if (tabKey === 'background') { recSetText(recTabDrawerTitle, 'Medical background'); recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading…</div>'; recLoadMedicalBackground(patientId) }
            else if (tabKey === 'visits') { recSetText(recTabDrawerTitle, 'Visit history'); recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading…</div>'; recLoadVisitHistory(patientId) }
            else if (tabKey === 'vitals') { recSetText(recTabDrawerTitle, 'Vitals history'); recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading…</div>'; recLoadVitalsHistory(patientId) }
            else if (tabKey === 'dependents') { recSetText(recTabDrawerTitle, 'Dependents'); recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading…</div>'; recLoadDependents(patientId) }
        }

        function recLoadMedicalBackground(patientId) {
            var url = "{{ url('/api/patients') }}/" + encodeURIComponent(patientId) + '/medical-background'
            apiFetch(url, { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    var rows = r.ok && r.data && Array.isArray(r.data.records) ? r.data.records : []
                    recCachedMedBgRows = rows
                    if (recCurrentPanelTab === 'background') recRenderMedBg(rows)
                })
                .catch(function () { recCachedMedBgRows = []; if (recCurrentPanelTab === 'background') recRenderMedBg([]) })
        }

        function recRenderMedBg(rows) {
            if (!recTabDrawerBody) return
            if (!rows || !rows.length) { recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No medical background records.</div>'; return }
            var html = '<div class="space-y-2">'
            rows.forEach(function (r) {
                var type = String(r.type || '').toLowerCase()
                var badge = type === 'allergy_food' ? 'bg-amber-100 text-amber-800' : (type === 'allergy_drug' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')
                html += '<div class="rounded-xl border border-slate-200 p-3">' +
                    '<div class="flex items-start justify-between"><div><span class="inline-flex px-2 py-0.5 rounded-full text-[0.68rem] font-semibold ' + badge + '">' + recEscHtml(recCategoryLabel(r.type)) + '</span>' +
                    '<span class="ml-2 text-[0.78rem] font-semibold text-slate-900">' + recEscHtml(r.name || '-') + '</span></div>' +
                    '<span class="text-[0.7rem] text-slate-400">' + recEscHtml(recFormatRecordedAt(r.created_at)) + '</span></div>' +
                    '<div class="mt-1 text-[0.74rem] text-slate-600">' + recEscHtml(r.description || '-') + '</div>' +
                '</div>'
            })
            html += '</div>'
            recTabDrawerBody.innerHTML = html
        }

        function recLoadVisitHistory(patientId) {
            var url = "{{ url('/api/patients') }}/" + encodeURIComponent(patientId) + '/visits'
            apiFetch(url, { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    var rows = r.ok && r.data && Array.isArray(r.data.data) ? r.data.data : (r.ok && Array.isArray(r.data) ? r.data : [])
                    recCachedVisitRows = rows
                    if (recCurrentPanelTab === 'visits') recRenderVisits(rows)
                })
                .catch(function () { recCachedVisitRows = []; if (recCurrentPanelTab === 'visits') recRenderVisits([]) })
        }

        function recRenderVisits(rows) {
            if (!recTabDrawerBody) return
            if (!rows || !rows.length) { recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No visit history.</div>'; return }
            var html = '<div class="overflow-x-auto"><table class="min-w-full text-left text-xs text-slate-600"><thead><tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400"><th class="py-2 pr-3 font-semibold">Date</th><th class="py-2 pr-3 font-semibold">Doctor</th><th class="py-2 pr-3 font-semibold">Diagnosis</th><th class="py-2 pr-3 font-semibold">Status</th></tr></thead><tbody>'
            rows.forEach(function (v) {
                var doctorName = v.doctor ? recFullName(v.doctor, '-') : '-'
                var diagnosis = v.diagnosis || '-'
                var status = String(v.status || 'pending').charAt(0).toUpperCase() + String(v.status || 'pending').slice(1)
                html += '<tr class="border-b border-slate-50"><td class="py-2 pr-3">' + recEscHtml(recFormatRecordedAt(v.appointment_date || v.created_at)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(doctorName) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(diagnosis) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(status) + '</td></tr>'
            })
            html += '</tbody></table></div>'
            recTabDrawerBody.innerHTML = html
        }

        function recLoadVitalsHistory(patientId) {
            var url = "{{ url('/api/patients') }}/" + encodeURIComponent(patientId) + '/vitals'
            apiFetch(url, { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    var rows = r.ok && r.data && Array.isArray(r.data.data) ? r.data.data : (r.ok && Array.isArray(r.data) ? r.data : [])
                    recCachedVitalRows = rows
                    if (recCurrentPanelTab === 'vitals') recRenderVitals(rows)
                })
                .catch(function () { recCachedVitalRows = []; if (recCurrentPanelTab === 'vitals') recRenderVitals([]) })
        }

        function recRenderVitals(rows) {
            if (!recTabDrawerBody) return
            if (!rows || !rows.length) { recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No vitals history.</div>'; return }
            var html = '<div class="overflow-x-auto"><table class="min-w-full text-left text-xs text-slate-600"><thead><tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400"><th class="py-2 pr-3 font-semibold">Date</th><th class="py-2 pr-3 font-semibold">BP</th><th class="py-2 pr-3 font-semibold">HR</th><th class="py-2 pr-3 font-semibold">RR</th><th class="py-2 pr-3 font-semibold">Temp</th><th class="py-2 pr-3 font-semibold">O2 Sat</th></tr></thead><tbody>'
            rows.forEach(function (v) {
                html += '<tr class="border-b border-slate-50"><td class="py-2 pr-3">' + recEscHtml(recFormatRecordedAt(v.recorded_at || v.created_at)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recDisplayValue(v.blood_pressure)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recFormatNumeric(v.heart_rate, 0)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recFormatNumeric(v.respiratory_rate, 0)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recFormatNumeric(v.temperature, 1)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recFormatNumeric(v.oxygen_saturation, 0)) + '</td></tr>'
            })
            html += '</tbody></table></div>'
            recTabDrawerBody.innerHTML = html
        }

        function recLoadDependents(patientId) {
            var url = "{{ url('/api/patients') }}/" + encodeURIComponent(patientId) + '/dependents'
            apiFetch(url, { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    var rows = r.ok && r.data && Array.isArray(r.data.data) ? r.data.data : (r.ok && Array.isArray(r.data) ? r.data : [])
                    recCachedDependentRows = rows
                    if (recCurrentPanelTab === 'dependents') recRenderDependents(rows)
                })
                .catch(function () { recCachedDependentRows = []; if (recCurrentPanelTab === 'dependents') recRenderDependents([]) })
        }

        function recRenderDependents(rows) {
            if (!recTabDrawerBody) return
            if (!rows || !rows.length) { recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">No dependents found.</div>'; return }
            var html = '<div class="overflow-x-auto"><table class="min-w-full text-left text-xs text-slate-600"><thead><tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400"><th class="py-2 pr-3 font-semibold">Name</th><th class="py-2 pr-3 font-semibold">Birthdate</th><th class="py-2 pr-3 font-semibold">Sex</th><th class="py-2 pr-3 font-semibold">Relationship</th><th class="py-2 pr-3 font-semibold">Action</th></tr></thead><tbody>'
            rows.forEach(function (dep) {
                var name = recFullName(dep, '-')
                html += '<tr class="border-b border-slate-50"><td class="py-2 pr-3 text-[0.78rem] text-slate-900 font-medium">' + recEscHtml(name) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recDisplayValue(dep.birthdate)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recSexLabel(dep.sex)) + '</td>' +
                    '<td class="py-2 pr-3">' + recEscHtml(recDisplayValue(dep.relationship || dep.dependent_relationship)) + '</td>' +
                    '<td class="py-2 pr-3"><button type="button" class="reception-pr-view-dep-btn text-[0.72rem] font-semibold text-green-600 hover:text-green-800" data-dep-id="' + dep.user_id + '">View</button></td></tr>'
            })
            html += '</tbody></table></div>'
            recTabDrawerBody.innerHTML = html
            recBindDepViewButtons()
        }

        function recBindDepViewButtons() {
            if (!recTabDrawerBody) return
            var btns = recTabDrawerBody.querySelectorAll('.reception-pr-view-dep-btn')
            btns.forEach(function (b) {
                b.addEventListener('click', function (e) { e.stopPropagation(); var did = b.getAttribute('data-dep-id'); if (did) recShowDependentRecords(did) })
            })
        }

        function recShowDependentRecords(depId) {
            recActiveDependentRecord = recCachedDependentRows ? recCachedDependentRows.find(function (d) { return String(d.user_id) === String(depId) }) : null
            if (!recActiveDependentRecord) return
            recActiveDependentTab = 'background'
            recActiveDependentMedBgRows = null; recActiveDependentVisitRows = null; recActiveDependentVitalRows = null; recActiveDependentVerification = null
            recOpenTabDrawer()
            recSetText(recTabDrawerTitle, recFullName(recActiveDependentRecord, 'Dependent') + ' — Background')
            recTabDrawerBody.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Loading…</div>'
            var url = "{{ url('/api/patients') }}/" + encodeURIComponent(depId) + '/medical-background'
            apiFetch(url, { method: 'GET' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) { recActiveDependentMedBgRows = r.ok && r.data && Array.isArray(r.data.records) ? r.data.records : []; if (recActiveDependentTab === 'background') recRenderMedBg(recActiveDependentMedBgRows) })
                .catch(function () { recActiveDependentMedBgRows = []; if (recActiveDependentTab === 'background') recRenderMedBg([]) })
            recCurrentPanelTab = null
            recSyncTabButtonState()
        }

        // ── Edit Patient Modal ──
        function recOpenEditModal(patientId) {
            if (!recPatientEditOverlay) return
            var patient = recPatientsRows.find(function (p) { return String(p.user_id) === String(patientId) })
            if (!patient) return
            recEditingPatientId = patientId
            recSetText(recPatientEditError, '')
            recPatientEditError.classList.add('hidden')
            recSetText(recPatientEditSubtitle, 'Updating: ' + recFullName(patient, ''))
            if (recPatientEditFirstname) recPatientEditFirstname.value = patient.firstname || ''
            if (recPatientEditMiddlename) recPatientEditMiddlename.value = patient.middlename || ''
            if (recPatientEditLastname) recPatientEditLastname.value = patient.lastname || ''
            if (recPatientEditSexMale) recPatientEditSexMale.checked = String(patient.sex || '').toLowerCase() === 'male'
            if (recPatientEditSexFemale) recPatientEditSexFemale.checked = String(patient.sex || '').toLowerCase() === 'female'
            if (recPatientEditBirthdate) recPatientEditBirthdate.value = patient.birthdate || ''
            if (recPatientEditCivilStatus) recPatientEditCivilStatus.value = patient.civil_status || ''
            if (recPatientEditNationalitySelect) {
                var nat = String(patient.nationality || '')
                if (['Filipino', '', 'None'].indexOf(nat) === -1) { recPatientEditNationalitySelect.value = '__others__'; if (recPatientEditNationality) { recPatientEditNationality.value = nat; recPatientEditNationality.classList.remove('hidden', 'w-0'); recPatientEditNationality.classList.add('w-full'); recPatientEditNationalitySelect.classList.add('hidden', 'w-0') } }
                else { recPatientEditNationalitySelect.value = nat || ''; if (recPatientEditNationality) { recPatientEditNationality.classList.add('hidden', 'w-0'); recPatientEditNationalitySelect.classList.remove('hidden', 'w-0') } }
            }
            if (recPatientEditAddress) recPatientEditAddress.value = patient.address || ''
            if (recPatientEditContact) recPatientEditContact.value = recFormatPhone(patient.contact_number)
            if (recPatientEditPhilhealth) recPatientEditPhilhealth.value = patient.philhealth_number || ''
            if (recPatientEditOccupation) recPatientEditOccupation.value = patient.occupation || ''
            if (recPatientEditEmergencyContact) recPatientEditEmergencyContact.value = patient.emergency_contact || ''
            if (recPatientEditEmergencyContactNumber) recPatientEditEmergencyContactNumber.value = recFormatPhone(patient.emergency_contact_number)
            recUpdatePatientProfilePreview(patient.profile_photo_url || '')
            recPatientEditOverlay.classList.add('flex'); recPatientEditOverlay.classList.remove('hidden')
        }

        function recCloseEditModal() {
            if (recPatientEditOverlay) { recPatientEditOverlay.classList.add('hidden'); recPatientEditOverlay.classList.remove('flex') }
            recEditingPatientId = null
        }

        function recShowEditConfirm(message) {
            return new Promise(function (resolve) {
                recPatientEditConfirmResolver = resolve
                if (recPatientEditConfirmMessage) recPatientEditConfirmMessage.textContent = message || 'Are you sure?'
                if (recPatientEditConfirmOverlay) { recPatientEditConfirmOverlay.classList.add('flex'); recPatientEditConfirmOverlay.classList.remove('hidden') }
            })
        }

        function recCloseEditConfirm() {
            if (recPatientEditConfirmOverlay) { recPatientEditConfirmOverlay.classList.add('hidden'); recPatientEditConfirmOverlay.classList.remove('flex') }
            if (recPatientEditConfirmResolver) { recPatientEditConfirmResolver(false); recPatientEditConfirmResolver = null }
        }

        function recResolveEditConfirm(value) {
            if (recPatientEditConfirmOverlay) { recPatientEditConfirmOverlay.classList.add('hidden'); recPatientEditConfirmOverlay.classList.remove('flex') }
            if (recPatientEditConfirmResolver) { recPatientEditConfirmResolver(value); recPatientEditConfirmResolver = null }
        }

        // ── Event Listeners ──
        if (recPatientsSearch) { var recSearchTimer = null; recPatientsSearch.addEventListener('input', function () { if (recSearchTimer) clearTimeout(recSearchTimer); recSearchTimer = setTimeout(function () { recFilterPatients() }, 300) }) }
        if (recSortSelect) recSortSelect.addEventListener('change', function () { recFilterPatients() })

        recAgeFilterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                recActiveAgeFilter = btn.getAttribute('data-age-filter') || 'all'
                recSetAgeFilterActiveStyles()
                recCurrentPage = 1
                recRenderPatientTable()
            })
        })

        if (document.getElementById('receptionPrRefreshBtn')) document.getElementById('receptionPrRefreshBtn').addEventListener('click', function () { recLoadPatients() })

        if (recPanelClose) recPanelClose.addEventListener('click', function () { recClosePanel() })
        if (recOverlay) recOverlay.addEventListener('click', function () { recClosePanel() })

        if (recPanelEditInfoBtn) recPanelEditInfoBtn.addEventListener('click', function () { if (recCurrentPatientId) recOpenEditModal(recCurrentPatientId) })

        if (recPanelTabBackground) recPanelTabBackground.addEventListener('click', function () { if (recCurrentPatientId) recLoadAndShowTab('background', recCurrentPatientId) })
        if (recPanelTabVisits) recPanelTabVisits.addEventListener('click', function () { if (recCurrentPatientId) recLoadAndShowTab('visits', recCurrentPatientId) })
        if (recPanelTabVitals) recPanelTabVitals.addEventListener('click', function () { if (recCurrentPatientId) recLoadAndShowTab('vitals', recCurrentPatientId) })
        if (recPanelTabDependents) recPanelTabDependents.addEventListener('click', function () { if (recCurrentPatientId) recLoadAndShowTab('dependents', recCurrentPatientId) })
        if (recTabDrawerClose) recTabDrawerClose.addEventListener('click', function () { recCloseTabDrawer() })

        if (recPatientEditClose) recPatientEditClose.addEventListener('click', function () { recCloseEditModal() })
        if (recPatientEditCancel) recPatientEditCancel.addEventListener('click', function () { recCloseEditModal() })

        if (recPatientEditConfirmCancel) recPatientEditConfirmCancel.addEventListener('click', function () { recCloseEditConfirm() })
        if (recPatientEditConfirmOk) recPatientEditConfirmOk.addEventListener('click', function () { recResolveEditConfirm(true) })

        if (recPatientEditNationalitySelect) {
            recPatientEditNationalitySelect.addEventListener('change', function () {
                if (this.value === '__others__') {
                    if (recPatientEditNationality) { recPatientEditNationality.classList.remove('hidden', 'w-0'); recPatientEditNationality.classList.add('w-full') }
                    this.classList.add('hidden', 'w-0')
                }
            })
        }

        if (recPatientEditProfileUpload) {
            recPatientEditProfileUpload.addEventListener('change', function () {
                var file = this.files && this.files[0]
                if (!file) { recUpdatePatientProfilePreview(''); return }
                recUpdatePatientProfilePreview(URL.createObjectURL(file))
            })
        }

        if (recPatientEditForm) {
            recPatientEditForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (!recEditingPatientId) return
                recShowPatientEditError('')
                var body = { firstname: recPatientEditFirstname ? String(recPatientEditFirstname.value).trim() : '', middlename: recPatientEditMiddlename ? String(recPatientEditMiddlename.value).trim() : '', lastname: recPatientEditLastname ? String(recPatientEditLastname.value).trim() : '', sex: recPatientEditSexMale && recPatientEditSexMale.checked ? 'Male' : (recPatientEditSexFemale && recPatientEditSexFemale.checked ? 'Female' : ''), birthdate: recPatientEditBirthdate ? String(recPatientEditBirthdate.value).trim() : '', civil_status: recPatientEditCivilStatus ? String(recPatientEditCivilStatus.value).trim() : '', nationality: recPatientEditNationalitySelect && recPatientEditNationalitySelect.value === '__others__' ? (recPatientEditNationality ? String(recPatientEditNationality.value).trim() : '') : (recPatientEditNationalitySelect ? String(recPatientEditNationalitySelect.value).trim() : ''), address: recPatientEditAddress ? String(recPatientEditAddress.value).trim() : '', contact_number: recPatientEditContact ? recParsePhoneRaw(recPatientEditContact.value) : '', philhealth_number: recPatientEditPhilhealth ? String(recPatientEditPhilhealth.value).trim() : '', occupation: recPatientEditOccupation ? String(recPatientEditOccupation.value).trim() : '', emergency_contact: recPatientEditEmergencyContact ? String(recPatientEditEmergencyContact.value).trim() : '', emergency_contact_number: recPatientEditEmergencyContactNumber ? recParsePhoneRaw(recPatientEditEmergencyContactNumber.value) : '' }
                recShowEditConfirm('Save changes for this patient?')
                    .then(function (confirmed) {
                        if (!confirmed) return
                        recSetPatientEditSubmitting(true)
                        var url = "{{ url('/api/patients') }}/" + encodeURIComponent(recEditingPatientId)
                        apiFetch(url, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) })
                            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                            .then(function (r) {
                                if (!r.ok) { recShowPatientEditError(r.data && r.data.message ? r.data.message : 'Failed to update patient.'); return }
                                recShowPatientEditSuccess('Patient updated successfully.')
                                recCloseEditModal()
                                if (recCurrentPatientId === recEditingPatientId) recOpenPatientPanel(recEditingPatientId)
                                recFilterPatients()
                            })
                            .catch(function () { recShowPatientEditError('Network error.') })
                            .finally(function () { recSetPatientEditSubmitting(false) })
                    })
                    .catch(function () { recSetPatientEditSubmitting(false) })
            })
        }

        recSetAgeFilterActiveStyles()
        recUpdateAgeCounts()
        recLoadPatients()
    })
</script>