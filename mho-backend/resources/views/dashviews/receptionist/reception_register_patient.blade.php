<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 mb-1">Patients Registration & Records</h1>
        <p class="text-sm text-slate-500">Register new patients and browse patient records.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-[18px] shadow-[0_2px_10px_rgba(15,23,42,0.04)] overflow-hidden">
    <div class="grid grid-cols-2 border-b border-slate-200">
        <button id="receptionPatientTabRecords" type="button" class="px-4 py-3 text-xs font-semibold text-white bg-green-500 border-b-2 border-green-600">
            Patient Records
        </button>
        <button id="receptionPatientTabRegister" type="button" class="px-4 py-3 text-xs font-semibold text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200">
            Register patient
        </button>
    </div>

    <div id="receptionRegisterPatientPanel" class="hidden p-5">

        <div id="receptionRegisterPatientError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
        <div id="receptionRegisterPatientSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>
        <pre id="receptionRegisterPatientCredentials" class="hidden mb-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[0.7rem] text-slate-700 overflow-x-auto"></pre>

        <form id="receptionRegisterPatientForm" class="grid gap-3 grid-cols-1 md:grid-cols-3 items-end mb-4">
            <div class="md:col-span-3">
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input id="reception_patient_is_dependent" type="checkbox" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:bg-green-500 peer-focus:ring-2 peer-focus:ring-green-200 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                    </label>
                    <span class="text-xs font-medium text-slate-700">Dependent account</span>
                </div>
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

    <div id="receptionPatientRecordsPanel">
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
                        <option value="visit_asc">Last Visit ASC</option>
                        <option value="visit_desc">Last Visit DESC</option>
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
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="0_5">
                        Infants/Toddlers&nbsp;(0–5)
                        <span id="receptionPrAgeCount0_5" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="6_12">
                        School Age&nbsp;(6–12)
                        <span id="receptionPrAgeCount6_12" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="13_19">
                        Adolescents&nbsp;(13–19)
                        <span id="receptionPrAgeCount13_19" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="20_64">
                        Adults&nbsp;(20–64)
                        <span id="receptionPrAgeCount20_64" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
                    </button>
                    <button type="button" class="reception-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="65_up">
                        Senior Citizens&nbsp;(65+)
                        <span id="receptionPrAgeCount65Up" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
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

<div id="receptionPrViewOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div>
                <div class="text-sm font-semibold text-slate-900">Patient Details</div>
                <div id="receptionPrViewSubtitle" class="text-[0.72rem] text-slate-500">View patient profile information.</div>
            </div>
            <button type="button" id="receptionPrViewClose" class="text-slate-400 hover:text-slate-600">
                <x-lucide-x class="w-[20px] h-[20px]" />
            </button>
        </div>

        <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-1.5 overflow-x-auto scrollbar-hidden shrink-0">
            <button type="button" class="reception-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-green-600 bg-green-600 text-white" data-view-tab="profile">Profile Info</button>
            <button type="button" class="reception-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="verification">Type &amp; Verification</button>
            <button type="button" class="reception-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="background">Medical Background</button>
            <button type="button" class="reception-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="visits">Visit History</button>
            <button type="button" class="reception-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="vitals">Vitals History</button>
            <button type="button" id="receptionPrViewTabDependentsBtn" class="reception-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="dependents">Dependents</button>
        </div>

        <div id="receptionPrViewBody" class="p-5 overflow-y-auto flex-1">
            {{-- Profile Info Tab --}}
            <div id="receptionPrViewTabProfile" class="reception-pr-view-tab-content min-h-[420px]">
                {{-- Edit mode toggle --}}
                <div class="flex gap-2 mb-4">
                    <button type="button" id="receptionPrViewEditBtn" class="inline-flex items-center gap-1 text-[0.78rem] font-semibold text-green-700 hover:text-green-800 transition-colors">
                        Edit Info
                    </button>
                </div>

                {{-- ===== DISPLAY MODE ===== --}}
                <div id="receptionPrViewProfileDisplay">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                        <div class="md:col-span-3 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                                    <div id="receptionPrDetailLastname" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                                    <div id="receptionPrDetailFirstname" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Middle name</label>
                                    <div id="receptionPrDetailMiddlename" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                                    <div id="receptionPrDetailSex" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                                    <div id="receptionPrDetailBirthdate" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label>
                                    <div id="receptionPrDetailCivilStatus" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label>
                                    <div id="receptionPrDetailNationality" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label>
                                    <div id="receptionPrDetailOccupation" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                                <div id="receptionPrDetailAddress" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg min-h-[2.5rem]">-</div>
                            </div>
                            <hr class="border-slate-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                                    <div id="receptionPrDetailPhic" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label>
                                    <div id="receptionPrDetailEmergContact" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                                <div id="receptionPrDetailEmergNumber" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                                <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                                <div class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                                    <div id="receptionPrViewProfilePic">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                </div>
                                <div class="mt-4 text-left">
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                                    <div id="receptionPrDetailContact" class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== EDIT MODE ===== --}}
                <div id="receptionPrViewProfileEdit" class="hidden">
                    <div id="receptionPrViewEditError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                    <form id="receptionPrViewEditForm" class="grid grid-cols-1 md:grid-cols-5 gap-5">
                        <div class="md:col-span-3 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label for="receptionPrViewEditLastname" class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                                    <input id="receptionPrViewEditLastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="receptionPrViewEditFirstname" class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                                    <input id="receptionPrViewEditFirstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="receptionPrViewEditMiddlename" class="block text-[0.7rem] text-slate-600 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                                    <input id="receptionPrViewEditMiddlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                                    <div class="flex items-center gap-4 pt-1">
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="radio" name="receptionPrViewEditSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                        </label>
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="radio" name="receptionPrViewEditSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label for="receptionPrViewEditBirthdate" class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                                    <input id="receptionPrViewEditBirthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="receptionPrViewEditCivilStatus" class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label>
                                    <select id="receptionPrViewEditCivilStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
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
                                    <label for="receptionPrViewEditNationalitySelect" class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label>
                                    <div id="receptionPrViewEditNationalityField" class="flex gap-2">
                                        <select id="receptionPrViewEditNationalitySelect" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                            <option value="">None</option>
                                            <option value="Filipino">Filipino</option>
                                            <option value="__others__">Other/s specify</option>
                                        </select>
                                        <input id="receptionPrViewEditNationality" type="text" class="w-0 hidden rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Please specify">
                                    </div>
                                </div>
                                <div>
                                    <label for="receptionPrViewEditOccupation" class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label>
                                    <input id="receptionPrViewEditOccupation" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                            </div>
                            <div>
                                <label for="receptionPrViewEditAddress" class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                                <textarea id="receptionPrViewEditAddress" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none resize-y" placeholder="Street, barangay, municipality"></textarea>
                            </div>
                            <hr class="border-slate-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="receptionPrViewEditPhilhealth" class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                                    <input id="receptionPrViewEditPhilhealth" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                                </div>
                                <div>
                                    <label for="receptionPrViewEditEmergencyContact" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label>
                                    <input id="receptionPrViewEditEmergencyContact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                            </div>
                            <div>
                                <label for="receptionPrViewEditEmergencyContactNumber" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                                <input id="receptionPrViewEditEmergencyContactNumber" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                                <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                                <div id="receptionPrViewEditProfilePreview" class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div class="mt-3">
                                    <label for="receptionPrViewEditProfileUpload" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-green-200 bg-green-50 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Upload photo
                                    </label>
                                    <input id="receptionPrViewEditProfileUpload" type="file" accept="image/*" class="hidden">
                                </div>
                                <div class="mt-4 text-left">
                                    <label for="receptionPrViewEditContact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                                    <input id="receptionPrViewEditContact" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" id="receptionPrViewEditCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                            <button type="submit" id="receptionPrViewEditSave" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                                <span id="receptionPrViewEditSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                                <span id="receptionPrViewEditSaveLabel">Save changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Type & Verification Tab --}}
            <div id="receptionPrViewTabVerification" class="hidden reception-pr-view-tab-content min-h-[420px]">
                <div class="space-y-3">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Verification status</div>
                        <div id="receptionPrViewVerificationStatus" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Patient type</div>
                        <div id="receptionPrViewPatientType" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Verification ID</div>
                        <div id="receptionPrViewVerificationId" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                </div>
            </div>

            {{-- Medical Background Tab --}}
            <div id="receptionPrViewTabBackground" class="hidden reception-pr-view-tab-content min-h-[420px]"></div>
            {{-- Visit History Tab --}}
            <div id="receptionPrViewTabVisits" class="hidden reception-pr-view-tab-content min-h-[420px]"></div>
            {{-- Vitals History Tab --}}
            <div id="receptionPrViewTabVitals" class="hidden reception-pr-view-tab-content min-h-[420px]"></div>
            {{-- Dependents Tab --}}
            <div id="receptionPrViewTabDependents" class="hidden reception-pr-view-tab-content min-h-[420px]"></div>
        </div>
    </div>
</div>

{{-- Visit Details Modal --}}
<div id="recVisitDetailOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <div class="text-sm font-semibold text-slate-900">Visit Details</div>
                <div id="recVisitDetailSubtitle" class="text-[0.72rem] text-slate-500">Appointment and clinical information.</div>
            </div>
            <button type="button" id="recVisitDetailClose" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-5 space-y-4" id="recVisitDetailBody">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Appointment date</div>
                    <div id="recVisitDetailDate" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Doctor</div>
                    <div id="recVisitDetailDoctor" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Services inquired</div>
                <div id="recVisitDetailServices" class="text-[0.8rem] text-slate-700 mt-1">-</div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Fees</div>
                    <div id="recVisitDetailFees" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Payment status</div>
                    <div id="recVisitDetailPayment" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Status</div>
                    <div id="recVisitDetailStatus" class="mt-1">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Appointment type</div>
                    <div id="recVisitDetailApptType" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Diagnosis</div>
                <div id="recVisitDetailDiagnosis" class="text-[0.8rem] text-slate-700 mt-1">-</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Treatment notes</div>
                <div id="recVisitDetailTreatment" class="text-[0.8rem] text-slate-700 mt-1">-</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Prescriptions</div>
                <div id="recVisitDetailPrescriptions" class="text-[0.8rem] text-slate-700 mt-1">-</div>
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
        var apiBaseUrl = "{{ url('/api') }}"
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
                if (!name) name = p.email ? String(p.email) : 'User'
                var meta = []
                if (p.email) meta.push(p.email)
                if (p.contact_number) meta.push(p.contact_number)
                var isActive = activeId && String(p.user_id) === activeId
                html += '<button type="button" class="reception-pr-parent-pick w-full rounded-xl border px-3 py-3 text-left transition-colors ' + (isActive ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white hover:border-green-200 hover:bg-slate-50') + '" data-idx="' + idx + '">' +
                    '<div class="text-[0.8rem] font-semibold text-slate-900 truncate">' + escapeHtml(name) + '</div>' +
                    '<div class="mt-1 text-[0.72rem] text-slate-500">' + escapeHtml(meta.join(' • ') || (p.email ? p.email : '')) + '</div>' +
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
                    '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">' +
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
                    '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">' +
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
                    var details = '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">' +
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
                            '<div class="text-[0.78rem] font-semibold text-slate-900">' + escapeHtml(nm || (p.email ? p.email : 'Patient')) + '</div>' +
                            '<div class="text-[0.72rem] text-slate-600 mt-0.5">' + (meta.length ? meta.join(' • ') : '') + '</div>' +
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
                        '<div class="text-[0.78rem] text-slate-900 font-semibold">' + escapeHtml(maskHalf(nm || (p.email ? p.email : 'Patient'))) + '</div>' +
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

        window.switchPatientTab = function (tab) {
            var isRegister = tab === 'register'
            tabRegister.className = 'px-4 py-3 text-xs font-semibold ' + (isRegister ? 'text-white bg-green-500 border-b-2 border-green-600' : 'text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200')
            tabRecords.className = 'px-4 py-3 text-xs font-semibold ' + (!isRegister ? 'text-white bg-green-500 border-b-2 border-green-600' : 'text-slate-900 bg-white hover:bg-slate-50 border-l border-slate-200')
            panelRegister.classList.toggle('hidden', !isRegister)
            panelRecords.classList.toggle('hidden', isRegister)
        }

        if (tabRegister) tabRegister.addEventListener('click', function () { window.switchPatientTab('register') })
        if (tabRecords) tabRecords.addEventListener('click', function () { window.switchPatientTab('records') })

        // ── Patient Records (duplicated from admin patient_records) ──
        var recDefaultProfilePicHtml = '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
        var recPatientsError = document.getElementById('receptionPrPatientsError')
        var recPatientsSearch = document.getElementById('reception_pr_patients_search')
        var recSortSelect = document.getElementById('reception_pr_sort')
        var recPatientsTableBody = document.getElementById('reception_pr_patients_table_body')
        var recPagination = document.getElementById('receptionPrPagination')
        var recPatientsRows = []
        var recPatientsAgeCounts = null
        var recPerPage = 10
        var recCurrentPage = 1
        var recVisibleCount = 6
        var recLastPage = 1
        var recTotal = 0

        var recActiveAgeFilter = 'all'
        var recAgeFilterButtons = Array.prototype.slice.call(document.querySelectorAll('.reception-pr-age-filter'))
        var recAgeCountAll = document.getElementById('receptionPrAgeCountAll')
        var recAgeCount0_5 = document.getElementById('receptionPrAgeCount0_5')
        var recAgeCount6_12 = document.getElementById('receptionPrAgeCount6_12')
        var recAgeCount13_19 = document.getElementById('receptionPrAgeCount13_19')
        var recAgeCount20_64 = document.getElementById('receptionPrAgeCount20_64')
        var recAgeCount65Up = document.getElementById('receptionPrAgeCount65Up')

        var recViewOverlay = document.getElementById('receptionPrViewOverlay')
        var recViewClose = document.getElementById('receptionPrViewClose')
        var recViewProfilePic = document.getElementById('receptionPrViewProfilePic')
        var recViewEditBtn = document.getElementById('receptionPrViewEditBtn')
        var recViewTabButtons = Array.prototype.slice.call(document.querySelectorAll('.reception-pr-view-tab'))
        var recViewTabContents = {}
        document.querySelectorAll('.reception-pr-view-tab-content').forEach(function (el) {
            var id = el.getAttribute('id') || ''
            var key = id.replace('receptionPrViewTab', '').toLowerCase()
            recViewTabContents[key] = el
        })
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
        var recViewVerificationStatus = document.getElementById('receptionPrViewVerificationStatus')
        var recViewPatientType = document.getElementById('receptionPrViewPatientType')
        var recViewVerificationId = document.getElementById('receptionPrViewVerificationId')

        var recMedBgEditingId = null

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
        var recCachedParentData = null
        var recCurrentPatientId = null
        var recCurrentViewTab = 'profile'
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

        function recBuildCategoryOptions(selected) {
            var sel = String(selected || '')
            var opts = [
                { value: '', label: '- Select -' },
                { value: 'allergy_food', label: 'Food Allergy' },
                { value: 'allergy_drug', label: 'Drug Allergy' },
                { value: 'condition', label: 'Condition' },
            ]
            var html = ''
            opts.forEach(function (o) {
                var selectedAttr = o.value === sel ? ' selected' : ''
                html += '<option value="' + recEscHtml(o.value) + '"' + selectedAttr + '>' + recEscHtml(o.label) + '</option>'
            })
            return html
        }

        function recFullName(p, fallback) {
            if (!p) return fallback || '-'
            var parts = []; if (p.firstname) parts.push(String(p.firstname)); if (p.middlename) parts.push(String(p.middlename)); if (p.lastname) parts.push(String(p.lastname))
            var name = parts.join(' ').trim(); if (name) return name; if (p.email) return String(p.email); return fallback || ''
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
            if (filterKey === '0_5') return age >= 0 && age <= 5; if (filterKey === '6_12') return age >= 6 && age <= 12
            if (filterKey === '13_19') return age >= 13 && age <= 19; if (filterKey === '20_64') return age >= 20 && age <= 64
            if (filterKey === '65_up') return age >= 65; return true
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

        function recSetViewTabActive(tabKey) {
            recViewTabButtons.forEach(function (btn) {
                var key = btn.getAttribute('data-view-tab') || ''
                btn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                if (key === tabKey) {
                    btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
                } else {
                    btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                }
            })
        }

        function recFormatRecordedAt(value) { var raw = value ? String(value) : ''; if (!raw) return '-'; return raw.replace('T', ' ').slice(0, 16) }
        function recFormatNumeric(value, decimals) { if (value == null || value === '') return '-'; var num = typeof value === 'number' ? value : parseFloat(value); if (isNaN(num)) return '-'; return num.toFixed(decimals == null ? 1 : decimals) }
        function recFormatCurrency(value) { if (value == null || value === '') return '-'; var num = typeof value === 'number' ? value : parseFloat(value); if (isNaN(num)) return '-'; return 'PHP ' + num.toFixed(2) }

        function recCloseTabDrawer() {
            if (recTabDrawer) recTabDrawer.classList.add('hidden')
            recCurrentPanelTab = null; recActiveDependentRecord = null; recActiveDependentTab = 'background'
            recActiveDependentMedBgRows = null; recActiveDependentVisitRows = null; recActiveDependentVitalRows = null; recActiveDependentVerification = null
        }

        function recOpenTabDrawer() { if (recTabDrawer) recTabDrawer.classList.remove('hidden') }

        function recResetPanelMetaFields() {
            if (recViewVerificationStatus) recViewVerificationStatus.textContent = '-'
            if (recViewPatientType) recViewPatientType.textContent = '-'
            if (recViewVerificationId) recViewVerificationId.textContent = '-'
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

   
        function recLoadPatients(page) {
            if (!recPatientsTableBody) return
            page = page || recCurrentPage
            recPatientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading patients…</td></tr>'
            recShowInlineBox(recPatientsError, '')
            var url = "{{ url('/api/patients') }}" + '?per_page=10&page=' + page + '&order_by=visit_asc&include_counts=1'
            apiFetch(url, { method: 'GET' })
                .then(function (res) { return res.json().then(function (d) { return { ok: res.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    if (!r.ok || !r.data) { recPatientsRows = []; recPatientsAgeCounts = null; recCurrentPage = 1; recLastPage = 1; recTotal = 0; recUpdateAgeCounts(); recRenderPatientTable(); return }
                    var raw = Array.isArray(r.data.data) ? r.data.data.slice() : (Array.isArray(r.data) ? r.data.slice() : [])
                    recPatientsRows = raw.map(function (p) {
                        return { user_id: p.user_id, firstname: p.firstname || '', middlename: p.middlename || '', lastname: p.lastname || '', birthdate: p.birthdate || '', sex: p.sex || '', address: p.address || '', contact_number: p.contact_number || '', email: p.email || '', account_type: p.account_type || '', verification_status: p.verification_status || '', verification_type: p.verification_type || '', verification_id: p.verification_id || '', philhealth_number: p.philhealth_number || '', nationality: p.nationality || '', civil_status: p.civil_status || '', occupation: p.occupation || '', emergency_contact: p.emergency_contact || '', emergency_contact_number: p.emergency_contact_number || '', profile_photo_url: p.profile_photo_url || '', is_dependent: p.is_dependent || false, parent_user_id: p.parent_user_id || null }
                    })
                    recCurrentPage = r.data.current_page || page
                    recLastPage = r.data.last_page || 1
                    recTotal = r.data.total || raw.length
                    recPatientsAgeCounts = r.data.age_counts || null
                    recUpdateAgeCounts()
                    recRenderPatientTable()
                })
                .catch(function () { recPatientsRows = []; recPatientsAgeCounts = null; recCurrentPage = 1; recLastPage = 1; recTotal = 0; recRenderPatientTable() })
        }

        function recUpdateAgeCounts() {
            var counts = recPatientsAgeCounts || { all: 0, '0_5': 0, '6_12': 0, '13_19': 0, '20_64': 0, '65_up': 0 }
            // Fallback to computing from current page if API counts not available
            if (!recPatientsAgeCounts) {
                recPatientsRows.forEach(function (p) {
                    var age = recAgeFromBirthdate(p.birthdate)
                    counts.all++
                    if (age == null) return
                    if (recMatchesAgeFilter(age, '0_5')) counts['0_5']++
                    else if (recMatchesAgeFilter(age, '6_12')) counts['6_12']++
                    else if (recMatchesAgeFilter(age, '13_19')) counts['13_19']++
                    else if (recMatchesAgeFilter(age, '20_64')) counts['20_64']++
                    else if (recMatchesAgeFilter(age, '65_up')) counts['65_up']++
                })
            }
            recSetText(recAgeCountAll, counts.all); recSetText(recAgeCount0_5, counts['0_5']); recSetText(recAgeCount6_12, counts['6_12'])
            recSetText(recAgeCount13_19, counts['13_19']); recSetText(recAgeCount20_64, counts['20_64']); recSetText(recAgeCount65Up, counts['65_up'])
        }

        function recFilterPatients(page) {
            if (!recPatientsTableBody) return
            page = page || 1
            recPatientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading patients…</td></tr>'
            recShowInlineBox(recPatientsError, '')
            var q = (recPatientsSearch && recPatientsSearch.value ? String(recPatientsSearch.value).trim() : '')
            var sort = (recSortSelect && recSortSelect.value ? String(recSortSelect.value) : 'visit_asc')
            var url = "{{ url('/api/patients') }}" + '?per_page=10&page=' + page + '&include_counts=1'
            if (q) url += '&search=' + encodeURIComponent(q)
            if (sort) url += '&order_by=' + encodeURIComponent(sort)
            apiFetch(url, { method: 'GET' })
                .then(function (res) { return res.json().then(function (d) { return { ok: res.ok, data: d } }).catch(function () { return { ok: false, data: null } }) })
                .then(function (r) {
                    if (!r.ok || !r.data) { recPatientsRows = []; recPatientsAgeCounts = null; recCurrentPage = 1; recLastPage = 1; recTotal = 0; recUpdateAgeCounts(); recRenderPatientTable(); return }
                    var raw = Array.isArray(r.data.data) ? r.data.data.slice() : (Array.isArray(r.data) ? r.data.slice() : [])
                    recPatientsRows = raw.map(function (p) {
                        return { user_id: p.user_id, firstname: p.firstname || '', middlename: p.middlename || '', lastname: p.lastname || '', birthdate: p.birthdate || '', sex: p.sex || '', address: p.address || '', contact_number: p.contact_number || '', email: p.email || '', account_type: p.account_type || '', verification_status: p.verification_status || '', verification_type: p.verification_type || '', verification_id: p.verification_id || '', philhealth_number: p.philhealth_number || '', nationality: p.nationality || '', civil_status: p.civil_status || '', occupation: p.occupation || '', emergency_contact: p.emergency_contact || '', emergency_contact_number: p.emergency_contact_number || '', profile_photo_url: p.profile_photo_url || '', is_dependent: p.is_dependent || false, parent_user_id: p.parent_user_id || null }
                    })
                    recCurrentPage = r.data.current_page || page
                    recLastPage = r.data.last_page || 1
                    recTotal = r.data.total || raw.length
                    recPatientsAgeCounts = r.data.age_counts || null
                    recUpdateAgeCounts()
                    recRenderPatientTable()
                })
                .catch(function () { recPatientsRows = []; recPatientsAgeCounts = null; recCurrentPage = 1; recLastPage = 1; recTotal = 0; recRenderPatientTable() })
        }

        function recRenderPatientTable() {
            if (!recPatientsTableBody) return
            var filtered = recPatientsRows.filter(function (p) {
                var age = recAgeFromBirthdate(p.birthdate)
                return recMatchesAgeFilter(age, recActiveAgeFilter)
            })
            if (!filtered.length) {
                recPatientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">No patients found.<br><button type="button" onclick="window.switchPatientTab(\'register\')" class="mt-2 inline-flex items-center gap-1 text-green-700 hover:text-green-800 font-semibold underline underline-offset-2">Register patient?</button></td></tr>'
                if (recPagination) recPagination.innerHTML = ''
                return
            }
            var html = ''
            filtered.forEach(function (p) {
                var patientId = p && p.user_id != null ? String(p.user_id) : ''
                var name = recNameOnly(p) || (p && p.email ? String(p.email) : '')
                var address = p && p.address ? String(p.address) : ''
                var age = recAgeFromBirthdate(p && p.birthdate ? String(p.birthdate) : null)
                var sex = p && p.sex ? String(p.sex) : ''
                var verificationType = p && p.verification_type ? String(p.verification_type) : ''
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
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (verificationType ? recEscHtml(verificationType.charAt(0).toUpperCase() + verificationType.slice(1)) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4">' +
                        '<button type="button" class="reception-pr-open-panel inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.78rem] font-semibold hover:bg-slate-50" data-patient-id="' + recEscHtml(patientId) + '">View Details and History</button>' +
                    '</td>' +
                '</tr>'
            })
            recPatientsTableBody.innerHTML = html
            recRenderPagination()
            recBindPatientRowClicks()
        }

        function recRenderPagination() {
            if (!recPagination) return
            if (recTotal === 0) { recPagination.innerHTML = ''; return }
            var totalPages = recLastPage
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + recTotal + ' entries</span>'
            html += '<button type="button" class="' + (recCurrentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (recCurrentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var ws = Math.max(1, recCurrentPage - Math.floor(recVisibleCount / 2))
            var we = Math.min(ws + recVisibleCount - 1, totalPages)
            if (we - ws + 1 < recVisibleCount) ws = Math.max(1, we - recVisibleCount + 1)
            for (var i = ws; i <= we; i++) {
                html += '<button type="button" class="' + (i === recCurrentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
            }
            if (we < totalPages) { html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>' }
            html += '<button type="button" class="' + (recCurrentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (recCurrentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            recPagination.innerHTML = html
            recPagination.querySelectorAll('button[data-page]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var p = b.getAttribute('data-page')
                    if (p === 'prev' && recCurrentPage > 1) { recCurrentPage-- }
                    else if (p === 'next' && recCurrentPage < totalPages) { recCurrentPage++ }
                    else if (p === 'next-window') { recCurrentPage = Math.min(we + 1, totalPages) }
                    else if (p !== 'prev' && p !== 'next') { recCurrentPage = parseInt(p, 10) }
                    else return
                    var q = (recPatientsSearch && recPatientsSearch.value ? String(recPatientsSearch.value).trim() : '')
                    if (q) { recFilterPatients(recCurrentPage) } else { recLoadPatients(recCurrentPage) }
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
            recViewEditModeToggle(false)
            recCachedMedBgRows = null; recCachedVisitRows = null; recCachedVitalRows = null; recCachedDependentRows = null; recCachedParentData = null
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
            // Update dependents/parent tab label based on patient type
            var depBtn = document.getElementById('receptionPrViewTabDependentsBtn')
            var isDependent = patientData && patientData.is_dependent
            if (depBtn) depBtn.textContent = isDependent ? 'Parent/Guardian' : 'Dependents'
            recSetText(recPrDetailFirstname, patientData.firstname || '-')
            recSetText(recPrDetailMiddlename, patientData.middlename || '-')
            recSetText(recPrDetailLastname, patientData.lastname || '-')
            var birthdateStr = patientData && patientData.birthdate ? String(patientData.birthdate) : ''
            if (birthdateStr) {
                var age = recAgeFromBirthdate(birthdateStr)
                recSetText(recPrDetailBirthdate, birthdateStr.substring(0, 10) + (age != null ? ' (Age: ' + age + ')' : ''))
            } else {
                recSetText(recPrDetailBirthdate, '-')
            }
            recSetText(recPrDetailAddress, patientData.address || '-')
            recSetText(recPrDetailSex, recSexLabel(patientData.sex))
            recSetText(recPrDetailContact, recFormatPhone(patientData.contact_number))
            if (recViewProfilePic) recViewProfilePic.innerHTML = patientData.profile_photo_url ? '<img src="' + String(patientData.profile_photo_url).replace(/"/g, '&quot;') + '" class="w-full h-full object-cover" alt="">' : recDefaultProfilePicHtml
            recSetText(recPrDetailCivilStatus, patientData.civil_status || '-')
            recSetText(recPrDetailNationality, patientData.nationality || '-')
            recSetText(recPrDetailPhic, recFormatPhilhealth(patientData.philhealth_number))
            recSetText(recPrDetailOccupation, patientData.occupation || '-')
            recSetText(recPrDetailEmergContact, patientData.emergency_contact || '-')
            recSetText(recPrDetailEmergNumber, recFormatPhone(patientData.emergency_contact_number))
            recOpenViewModal()
            recLoadPatientPanelData(patientId)
        }

        function recLoadPatientPanelData(patientId) {
            recCurrentPatientId = patientId
            recCachedMedBgRows = null; recCachedVisitRows = null; recCachedVitalRows = null; recCachedDependentRows = null; recCachedParentData = null
            recResetPanelMetaFields()

            var medBgReq = apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=15&patient_id=" + encodeURIComponent(patientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var visitsReq = apiFetch(apiBaseUrl + "/visits?per_page=15&patient_id=" + encodeURIComponent(patientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var vitalsReq = apiFetch(apiBaseUrl + "/vitals?per_page=15&patient_id=" + encodeURIComponent(patientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var verificationReq = apiFetch(apiBaseUrl + "/patient-verifications?per_page=1&patient_id=" + encodeURIComponent(patientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var dependentsReq = apiFetch(apiBaseUrl + "/users/" + encodeURIComponent(patientId) + "/dependents", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            Promise.all([medBgReq, visitsReq, vitalsReq, verificationReq, dependentsReq])
                .then(function (results) {
                    if (String(patientId || '') !== String(recCurrentPatientId || '')) return

                    var medBgRes = results[0]
                    recCachedMedBgRows = (!medBgRes || !medBgRes.ok || !medBgRes.data)
                        ? []
                        : (Array.isArray(medBgRes.data.data) ? medBgRes.data.data : (Array.isArray(medBgRes.data) ? medBgRes.data : []))

                    var visitsRes = results[1]
                    recCachedVisitRows = (!visitsRes || !visitsRes.ok || !visitsRes.data)
                        ? []
                        : (Array.isArray(visitsRes.data.data) ? visitsRes.data.data : (Array.isArray(visitsRes.data) ? visitsRes.data : []))

                    var vitalsRes = results[2]
                    recCachedVitalRows = (!vitalsRes || !vitalsRes.ok || !vitalsRes.data)
                        ? []
                        : (Array.isArray(vitalsRes.data.data) ? vitalsRes.data.data : (Array.isArray(vitalsRes.data) ? vitalsRes.data : []))

                    var verRes = results[3]
                    if (!verRes || !verRes.ok || !verRes.data) {
                        if (recViewVerificationStatus) recViewVerificationStatus.textContent = '-'
                        if (recViewPatientType) recViewPatientType.textContent = '-'
                        if (recViewVerificationId) recViewVerificationId.textContent = '-'
                    } else {
                        var verRows = Array.isArray(verRes.data.data) ? verRes.data.data : (Array.isArray(verRes.data) ? verRes.data : [])
                        var latest = verRows && verRows.length ? verRows[0] : null
                        var verStatus = latest && latest.status ? String(latest.status) : 'Not submitted'
                        if (recViewVerificationStatus) recViewVerificationStatus.textContent = verStatus
                        if (recViewPatientType) recViewPatientType.textContent = latest && latest.type ? String(latest.type) : '-'
                        var isVerified = verStatus.toLowerCase() === 'verified' || verStatus.toLowerCase() === 'approved'
                        if (recViewVerificationId) {
                            if (isVerified && latest && latest.document_url) {
                                var docUrl = String(latest.document_url)
                                recViewVerificationId.innerHTML = '<a href="' + docUrl.replace(/"/g, '&quot;') + '" target="_blank" class="text-green-700 underline hover:text-green-800">View ID</a>'
                            } else {
                                recViewVerificationId.textContent = isVerified ? '-' : '—'
                            }
                        }
                    }

                    var dependentsRes = results[4]
                    recCachedDependentRows = (!dependentsRes || !dependentsRes.ok || !dependentsRes.data)
                        ? []
                        : (Array.isArray(dependentsRes.data) ? dependentsRes.data : (Array.isArray(dependentsRes.data.data) ? dependentsRes.data.data : []))

                    // Re-render current view tab if it's a data-driven tab
                    if (recCurrentViewTab && recCurrentViewTab !== 'profile' && recCurrentViewTab !== 'verification') {
                        recSetViewTab(recCurrentViewTab)
                    }
                })
                .catch(function () {
                    if (String(patientId || '') !== String(recCurrentPatientId || '')) return
                    recCachedMedBgRows = []
                    recCachedVisitRows = []
                    recCachedVitalRows = []
                    recCachedDependentRows = []
                })
        }

        function recLoadAndShowTab(tabKey, patientId) {
            if (!recTabDrawerBody) return
            recCurrentPanelTab = tabKey
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
            var url = "{{ url('/api/users') }}/" + encodeURIComponent(patientId) + '/dependents'
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
            if (!rows || !rows.length) { recTabDrawerBody.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">No dependents found for this patient.</div></div>'; return }
            var html = '<div class="space-y-3">'
            rows.forEach(function (dep) {
                var name = recFullName(dep, '-')
                html += '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">' +
                    '<div class="flex items-center justify-between gap-3">' +
                        '<div class="min-w-0 flex-1">' +
                            '<div class="text-[0.82rem] font-semibold text-slate-900">' + recEscHtml(name) + '</div>' +
                            '<div class="mt-1 flex items-center gap-3 text-[0.72rem] text-slate-500">' +
                                '<span>' + recEscHtml(recDisplayValue(dep.birthdate)) + '</span>' +
                                '<span class="text-slate-300">|</span>' +
                                '<span>' + recEscHtml(recSexLabel(dep.sex)) + '</span>' +
                                '<span class="text-slate-300">|</span>' +
                                '<span>' + recEscHtml(recDisplayValue(dep.relationship || dep.dependent_relationship)) + '</span>' +
                            '</div>' +
                        '</div>' +
                        '<button type="button" class="reception-pr-view-dep-btn px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-[0.72rem] font-semibold text-green-600 hover:bg-green-50 hover:border-green-200 whitespace-nowrap" data-dep-id="' + dep.user_id + '">View</button>' +
                    '</div>' +
                '</div>'
            })
            html += '</div>'
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

        // ── View modal tab rendering functions ──
        function recBuildTableHtml(headers, rowsHtml, emptyMessage, loadingMessage) {
            var headerHtml = headers.map(function (header) {
                return '<th class="py-2 pr-4 font-semibold">' + recEscHtml(header) + '</th>'
            }).join('')
            var bodyHtml = rowsHtml
            if (!bodyHtml) {
                var message = loadingMessage || emptyMessage
                bodyHtml = '<tr><td colspan="' + headers.length + '" class="py-4 text-center text-[0.78rem] text-slate-400">' + recEscHtml(message) + '</td></tr>'
            }
            return '<div class="overflow-x-auto"><table class="min-w-full text-left text-xs text-slate-600"><thead><tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">' + headerHtml + '</tr></thead><tbody>' + bodyHtml + '</tbody></table></div>'
        }

        function recRenderViewTabContent(tabKey) {
            var container = recViewTabContents[tabKey]
            if (!container) return
            if (tabKey === 'background') {
                var headers = ['Category', 'Name', 'Diagnosis Date', 'Procedure Date', 'Notes', '']
                if (recCachedMedBgRows == null) {
                    container.innerHTML = recBuildTableHtml(headers, '', 'No medical background entries found.', 'Loading medical background entries...')
                    return
                }
                var rowsHtml = ''
                recCachedMedBgRows.forEach(function (row) {
                    var rowId = row && row.medical_background_id ? String(row.medical_background_id) : ''
                    var rawDate = row && row.diagnosis_date ? String(row.diagnosis_date) : ''
                    var diagnosisDate = rawDate ? rawDate.slice(0, 10) : ''
                    var procedureDate = row && row.procedure_date ? String(row.procedure_date).slice(0, 10) : ''
                    // Per-row edit mode
                    if (recMedBgEditingId === rowId) {
                        var prefix = 'recmedbg-edit-' + rowId
                        var catOpts = recBuildCategoryOptions(row.category)
                        var dtPicker = '<input type="date" value="' + recEscHtml(diagnosisDate) + '" class="' + prefix + '-date w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">'
                        var procPicker = '<input type="date" value="' + recEscHtml(procedureDate) + '" class="' + prefix + '-proc w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">'
                        var notesInput = '<input type="text" value="' + recEscHtml(row && row.notes ? String(row.notes) : '') + '" class="' + prefix + '-notes w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400" placeholder="Notes">'
                        rowsHtml += '<tr class="border-b border-amber-200 bg-amber-50/40">' +
                            '<td class="py-2 pr-4"><select class="' + prefix + '-cat w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">' + catOpts + '</select></td>' +
                            '<td class="py-2 pr-4"><input type="text" value="' + recEscHtml(row && row.name ? String(row.name) : '') + '" class="' + prefix + '-name w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400"></td>' +
                            '<td class="py-2 pr-4">' + dtPicker + '</td>' +
                            '<td class="py-2 pr-4">' + procPicker + '</td>' +
                            '<td class="py-2 pr-4">' + notesInput + '</td>' +
                            '<td class="py-2 pr-4 text-right whitespace-nowrap">' +
                                '<button type="button" class="recmedbg-edit-save px-2 py-1 rounded-lg border border-green-300 bg-green-600 text-[0.7rem] font-semibold text-white hover:bg-green-700 disabled:opacity-50" data-medbg-id="' + recEscHtml(rowId) + '">Save</button>' +
                                '<button type="button" class="recmedbg-edit-cancel ml-1 px-2 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-500 hover:bg-slate-50" data-medbg-id="' + recEscHtml(rowId) + '">Cancel</button>' +
                            '</td>' +
                        '</tr>'
                    } else {
                        rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(recCategoryLabel(row.category)) + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + recEscHtml(row && row.name ? String(row.name) : '-') + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosisDate ? recEscHtml(diagnosisDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (procedureDate ? recEscHtml(procedureDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (row && row.notes ? recEscHtml(String(row.notes)) : '<span class="text-slate-400">-</span>') + '</td>' +
                            '<td class="py-2 pr-4 text-right"><button type="button" class="recmedbg-edit-btn text-[0.65rem] font-semibold text-green-600 hover:text-green-700 underline" data-medbg-id="' + recEscHtml(rowId) + '">Edit</button></td>' +
                        '</tr>'
                    }
                })
                var headerHtml = '<div class="flex items-center justify-between mb-3">' +
                    '<div class="text-[0.72rem] font-semibold text-slate-700">Medical Background</div>' +
                    '<button type="button" class="recmedbg-add-btn text-[0.7rem] font-semibold text-green-700 hover:text-green-800 underline">+ Add entry</button>' +
                '</div>'
                container.innerHTML = headerHtml + recBuildTableHtml(headers, rowsHtml, 'No medical background entries found.')
            } else if (tabKey === 'visits') {
                var headers = ['Doctor', 'Visit date', 'Fees', 'Status', 'Action']
                if (recCachedVisitRows == null) {
                    container.innerHTML = recBuildTableHtml(headers, '', 'No visits found.', 'Loading visit history...')
                    return
                }
                var rowsHtml = ''
                recCachedVisitRows.forEach(function (visit) {
                    var appointment = visit && visit.appointment ? visit.appointment : null
                    var doctor = appointment && appointment.doctor ? appointment.doctor : null
                    var dateRaw = visit && (visit.visit_datetime || visit.transaction_datetime) ? String(visit.visit_datetime || visit.transaction_datetime) : ''
                    var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '-'
                    var apptStatus = (appointment && appointment.status) ? String(appointment.status) : ''
                    var statusColors = { pending:'bg-amber-50 text-amber-700 border-amber-200', confirmed:'border-orange-200 bg-orange-50 text-orange-700', completed:'border-green-200 bg-green-50 text-green-700', cancelled:'bg-red-50 text-red-700 border-red-200', no_show:'bg-slate-100 text-slate-600 border-slate-200', consulted:'border-purple-200 bg-purple-50 text-purple-700', waiting:'bg-amber-50 text-amber-700 border-amber-100', serving:'bg-blue-50 text-blue-700 border-blue-100', done:'bg-emerald-50 text-emerald-700 border-emerald-100', skipped:'bg-orange-50 text-orange-700 border-orange-100', on_hold:'bg-purple-50 text-purple-700 border-purple-100' }
                    var statusClass = statusColors[apptStatus] || 'bg-slate-50 text-slate-600 border-slate-100'
                    var statusLabel = apptStatus ? apptStatus.charAt(0).toUpperCase() + apptStatus.slice(1).replace(/_/g, ' ') : '-'
                    rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + recEscHtml(recFullName(doctor, 'Doctor')) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(dateText) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + recEscHtml(recFormatCurrency(visit && visit.amount != null ? visit.amount : '')) + '</td>' +
                        '<td class="py-2 pr-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-medium border ' + statusClass + '">' + recEscHtml(statusLabel) + '</span></td>' +
                        '<td class="py-2 pr-4"><button type="button" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 rec-visit-detail-btn" data-visit=\'' + recEscHtml(JSON.stringify(visit).replace(/'/g, '&#39;')) + '\'>Details</button></td>' +
                    '</tr>'
                })
                container.innerHTML = recBuildTableHtml(headers, rowsHtml, 'No visits found.')
            } else if (tabKey === 'vitals') {
                var headers = ['Recorded', 'Height (cm)', 'Weight (kg)', 'BP', 'Temp', 'Pulse']
                if (recCachedVitalRows == null) {
                    container.innerHTML = recBuildTableHtml(headers, '', 'No vitals found.', 'Loading vitals history...')
                    return
                }
                var rowsHtml = ''
                recCachedVitalRows.forEach(function (vital) {
                    var recorded = recFormatRecordedAt(vital && vital.recorded_at ? vital.recorded_at : (vital && vital.appointment_datetime ? vital.appointment_datetime : ''))
                    var height = vital && vital.height_cm != null ? recFormatNumeric(vital.height_cm, 1) : '-'
                    var weight = vital && vital.weight_kg != null ? recFormatNumeric(vital.weight_kg, 1) : '-'
                    var bp = vital && vital.blood_pressure ? String(vital.blood_pressure) : '-'
                    var temp = vital && vital.temperature != null ? recFormatNumeric(vital.temperature, 1) : '-'
                    var pulse = vital && vital.pulse_rate != null ? String(vital.pulse_rate) : '-'
                    rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + recEscHtml(recorded) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(height) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(weight) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(bp) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(temp) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + recEscHtml(pulse) + '</td>' +
                    '</tr>'
                })
                container.innerHTML = recBuildTableHtml(headers, rowsHtml, 'No vitals found.')
            } else if (tabKey === 'dependents') {
                var patient = recCurrentPatientId ? recFindPatientById(recCurrentPatientId) : null
                var isDependent = patient && patient.is_dependent
                var depBtn = document.getElementById('receptionPrViewTabDependentsBtn')
                if (depBtn) depBtn.textContent = isDependent ? 'Parent/Guardian' : 'Dependents'

                if (window._recShowingDependentProfile) {
                    renderRecDependentProfileInline(container)
                    return
                }

                if (isDependent) {
                    var parentId = patient.parent_user_id
                    if (!parentId) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">No parent/guardian linked to this account.</div></div>'
                        return
                    }
                    if (!recCachedParentData) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading parent information...</div></div>'
                        fetchRecParentData(parentId)
                        return
                    }
                    renderRecParentOrDependentCards(container, [recCachedParentData], 'parent')
                } else {
                    if (recCachedDependentRows == null) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading dependents...</div></div>'
                        return
                    }
                    if (!recCachedDependentRows.length) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">No dependents found for this patient.</div></div>'
                        return
                    }
                    renderRecParentOrDependentCards(container, recCachedDependentRows, 'dependent')
                }
            }
        }

        function recSetViewTab(tabKey) {
            if (!tabKey || !recCurrentPatientId) return
            recCurrentViewTab = tabKey
            recSetViewTabActive(tabKey)
            Object.keys(recViewTabContents).forEach(function (key) {
                if (recViewTabContents[key]) {
                    recViewTabContents[key].classList.toggle('hidden', key !== tabKey)
                }
            })
            recRenderViewTabContent(tabKey)
        }

        function recOpenViewModal() {
            recMedBgEditingId = null
            if (recViewOverlay) {
                recViewOverlay.classList.remove('hidden')
                recViewOverlay.classList.add('flex')
            }
            recSetViewTab('profile')
        }

        function recCloseViewModal() {
            recCurrentPatientId = null
            recMedBgEditingId = null
            recCachedMedBgRows = null
            recCachedVisitRows = null
            recCachedVitalRows = null
            recCachedDependentRows = null
            recCachedParentData = null
            window._recShowingDependentProfile = false
            window._recDependentProfileId = null
            var depBtn = document.getElementById('receptionPrViewTabDependentsBtn')
            if (depBtn) depBtn.textContent = 'Dependents'
            recViewEditModeToggle(false)
            if (recViewOverlay) {
                recViewOverlay.classList.add('hidden')
                recViewOverlay.classList.remove('flex')
            }
        }

        // ── View edit mode functions ──
        function recPopulateViewEditForm(patient) {
            if (!patient) return
            var ev = function (input) { return (input != null && input !== '') ? String(input) : '' }
            var editLastname = document.getElementById('receptionPrViewEditLastname')
            var editFirstname = document.getElementById('receptionPrViewEditFirstname')
            var editMiddlename = document.getElementById('receptionPrViewEditMiddlename')
            var editBirthdate = document.getElementById('receptionPrViewEditBirthdate')
            var editCivilStatus = document.getElementById('receptionPrViewEditCivilStatus')
            var editNationalitySelect = document.getElementById('receptionPrViewEditNationalitySelect')
            var editNationality = document.getElementById('receptionPrViewEditNationality')
            var editOccupation = document.getElementById('receptionPrViewEditOccupation')
            var editAddress = document.getElementById('receptionPrViewEditAddress')
            var editPhilhealth = document.getElementById('receptionPrViewEditPhilhealth')
            var editEmergencyContact = document.getElementById('receptionPrViewEditEmergencyContact')
            var editEmergencyContactNumber = document.getElementById('receptionPrViewEditEmergencyContactNumber')
            var editContact = document.getElementById('receptionPrViewEditContact')
            var editProfilePreview = document.getElementById('receptionPrViewEditProfilePreview')
            var editProfileUpload = document.getElementById('receptionPrViewEditProfileUpload')

            if (editLastname) editLastname.value = ev(patient && patient.lastname)
            if (editFirstname) editFirstname.value = ev(patient && patient.firstname)
            if (editMiddlename) editMiddlename.value = ev(patient && patient.middlename)
            if (editBirthdate) editBirthdate.value = patient && patient.birthdate ? String(patient.birthdate).substring(0, 10) : ''
            if (editCivilStatus) editCivilStatus.value = patient && patient.civil_status ? String(patient.civil_status) : ''
            // Sex radio
            var sexRadios = document.querySelectorAll('input[name="receptionPrViewEditSex"]')
            var patientSex = patient && patient.sex ? String(patient.sex) : ''
            sexRadios.forEach(function (r) { r.checked = r.value === patientSex })

            // Nationality
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

        function recViewEditModeToggle(showEdit) {
            var display = document.getElementById('receptionPrViewProfileDisplay')
            var edit = document.getElementById('receptionPrViewProfileEdit')
            var editBtn = document.getElementById('receptionPrViewEditBtn')
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

        if (recViewClose) recViewClose.addEventListener('click', recCloseViewModal)
        if (recViewOverlay) recViewOverlay.addEventListener('click', function (e) { if (e.target === recViewOverlay) recCloseViewModal() })

        if (recViewEditBtn) recViewEditBtn.addEventListener('click', function () {
            if (!recCurrentPatientId) return
            var isEditing = !document.getElementById('receptionPrViewProfileEdit').classList.contains('hidden')
            if (isEditing) {
                recViewEditModeToggle(false)
            } else {
                var patient = recFindPatientById(recCurrentPatientId)
                if (patient) { recPopulateViewEditForm(patient); recViewEditModeToggle(true) }
            }
        })

        recViewTabButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var tabKey = this.getAttribute('data-view-tab') || 'profile'
                recSetViewTab(tabKey)
            })
        })

        // Medical Background click handlers: add entry, edit, save, cancel
        var recBgContainer = recViewTabContents['background']
        if (recBgContainer) {
            recBgContainer.addEventListener('click', function (e) {
                // + Add entry button
                var addBtn = e.target.closest('.recmedbg-add-btn')
                if (addBtn) {
                    e.preventDefault()
                    var tbody = recBgContainer.querySelector('table tbody')
                    if (!tbody) return
                    var uid = 'new-' + Date.now()
                    var dtPicker = '<input type="date" class="recmedbg-' + uid + '-date w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">'
                    var tr = document.createElement('tr')
                    tr.className = 'border-b border-green-200 bg-green-50/40'
                    tr.setAttribute('data-new-row', uid)
                    tr.innerHTML =
                        '<td class="py-2 pr-4">' +
                            '<select class="recmedbg-' + uid + '-cat w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">' +
                                recBuildCategoryOptions('') +
                            '</select>' +
                        '</td>' +
                        '<td class="py-2 pr-4">' +
                            '<input type="text" class="recmedbg-' + uid + '-name w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400" placeholder="e.g. Penicillin">' +
                        '</td>' +
                        '<td class="py-2 pr-4">' + dtPicker + '</td>' +
                        '<td class="py-2 pr-4">' +
                            '<input type="date" class="recmedbg-' + uid + '-proc w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400">' +
                        '</td>' +
                        '<td class="py-2 pr-4">' +
                            '<input type="text" class="recmedbg-' + uid + '-notes w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-green-400" placeholder="Notes">' +
                        '</td>' +
                        '<td class="py-2 pr-4 text-right whitespace-nowrap">' +
                            '<button type="button" class="recmedbg-new-save px-2 py-1 rounded-lg border border-green-300 bg-green-600 text-[0.7rem] font-semibold text-white hover:bg-green-700 disabled:opacity-50" data-new-uid="' + uid + '">Save</button>' +
                            '<button type="button" class="recmedbg-new-cancel ml-1 px-2 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-500 hover:bg-slate-50" data-new-uid="' + uid + '">Cancel</button>' +
                        '</td>'
                    tbody.insertBefore(tr, tbody.firstChild)
                    return
                }

                // Edit existing entry button
                var editBtn = e.target.closest('.recmedbg-edit-btn')
                if (editBtn) {
                    recMedBgEditingId = editBtn.getAttribute('data-medbg-id') || null
                    recRenderViewTabContent('background')
                    return
                }

                // Cancel editing existing entry
                var editCancel = e.target.closest('.recmedbg-edit-cancel')
                if (editCancel) {
                    recMedBgEditingId = null
                    recRenderViewTabContent('background')
                    return
                }

                // Save edited entry
                var editSave = e.target.closest('.recmedbg-edit-save')
                if (editSave && !editSave.disabled) {
                    var rowId = editSave.getAttribute('data-medbg-id')
                    if (!rowId) return
                    var prefix = 'recmedbg-edit-' + rowId
                    var catEl = recBgContainer.querySelector('.' + prefix + '-cat')
                    var nameEl = recBgContainer.querySelector('.' + prefix + '-name')
                    var dateEl = recBgContainer.querySelector('.' + prefix + '-date')
                    var procEl = recBgContainer.querySelector('.' + prefix + '-proc')
                    var notesEl = recBgContainer.querySelector('.' + prefix + '-notes')
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
                    window.apiFetch(apiBaseUrl + '/medical-backgrounds/' + encodeURIComponent(rowId), {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                    }).then(function (res) {
                        if (!res.ok) throw new Error('Update failed')
                        recMedBgEditingId = null
                        if (typeof showToast === 'function') showToast('Entry updated.', 'success')
                        // Reload only medical backgrounds for immediate UI update
                        apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=15&patient_id=" + encodeURIComponent(recCurrentPatientId), { method: 'GET' })
                            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                            .then(function (result) {
                                recCachedMedBgRows = (!result || !result.ok || !result.data) ? [] : (Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : []))
                                recRenderViewTabContent('background')
                            })
                    }).catch(function () {
                        editSave.disabled = false
                        editSave.textContent = 'Save'
                        if (typeof showToast === 'function') showToast('Failed to update entry.', 'error')
                    })
                    return
                }

                // Save new entry
                var saveBtn = e.target.closest('.recmedbg-new-save')
                if (!saveBtn || saveBtn.disabled) return
                var uid = saveBtn.getAttribute('data-new-uid')
                if (!uid) return
                var prefix = 'recmedbg-' + uid
                var catEl = recBgContainer.querySelector('.' + prefix + '-cat')
                var nameEl = recBgContainer.querySelector('.' + prefix + '-name')
                var dateEl = recBgContainer.querySelector('.' + prefix + '-date')
                var procEl = recBgContainer.querySelector('.' + prefix + '-proc')
                var notesEl = recBgContainer.querySelector('.' + prefix + '-notes')
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
                    patient_id: recCurrentPatientId,
                    category: category,
                    name: name,
                    diagnosis_date: diagnosisDate,
                    procedure_date: procedureDate,
                    notes: notes,
                }
                window.apiFetch(apiBaseUrl + '/medical-backgrounds', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                }).then(function (res) {
                    if (!res.ok) throw new Error('Save failed')
                    if (typeof showToast === 'function') showToast('Entry added.', 'success')
                    // Reload only medical backgrounds for immediate UI update
                    apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=15&patient_id=" + encodeURIComponent(recCurrentPatientId), { method: 'GET' })
                        .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d } }).catch(function () { return { ok: r.ok, data: null } }) })
                        .then(function (result) {
                            recCachedMedBgRows = (!result || !result.ok || !result.data) ? [] : (Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : []))
                            recRenderViewTabContent('background')
                        })
                }).catch(function () {
                    saveBtn.disabled = false
                    saveBtn.textContent = 'Save'
                    if (typeof showToast === 'function') showToast('Failed to save entry.', 'error')
                })
            })

            // Cancel new entry (delegated)
            recBgContainer.addEventListener('click', function (e) {
                var cancelBtn = e.target.closest('.recmedbg-new-cancel')
                if (!cancelBtn) return
                var uid = cancelBtn.getAttribute('data-new-uid')
                if (!uid) return
                var row = recBgContainer.querySelector('tr[data-new-row="' + uid + '"]')
                if (row) row.remove()
            })
        }

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

        // ── View Edit Form Submit ──
        var recViewEditForm = document.getElementById('receptionPrViewEditForm')
        var recViewEditSave = document.getElementById('receptionPrViewEditSave')
        var recViewEditSpinner = document.getElementById('receptionPrViewEditSpinner')
        var recViewEditSaveLabel = document.getElementById('receptionPrViewEditSaveLabel')
        var recViewEditError = document.getElementById('receptionPrViewEditError')
        if (recViewEditForm) {
            recViewEditForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (!recCurrentPatientId) return
                if (recViewEditError) { recViewEditError.classList.add('hidden'); recViewEditError.textContent = '' }
                if (recViewEditSave) recViewEditSave.disabled = true
                if (recViewEditSpinner) recViewEditSpinner.classList.remove('hidden')
                if (recViewEditSaveLabel) recViewEditSaveLabel.textContent = 'Saving...'

                var firstname = document.getElementById('receptionPrViewEditFirstname')
                var lastname = document.getElementById('receptionPrViewEditLastname')
                var middlename = document.getElementById('receptionPrViewEditMiddlename')
                var birthdate = document.getElementById('receptionPrViewEditBirthdate')
                var civilStatus = document.getElementById('receptionPrViewEditCivilStatus')
                var editNationalitySelect = document.getElementById('receptionPrViewEditNationalitySelect')
                var editNationality = document.getElementById('receptionPrViewEditNationality')
                var occupation = document.getElementById('receptionPrViewEditOccupation')
                var address = document.getElementById('receptionPrViewEditAddress')
                var philhealth = document.getElementById('receptionPrViewEditPhilhealth')
                var emergencyContact = document.getElementById('receptionPrViewEditEmergencyContact')
                var emergencyContactNumber = document.getElementById('receptionPrViewEditEmergencyContactNumber')
                var contact = document.getElementById('receptionPrViewEditContact')

                var sexVal = ''
                document.querySelectorAll('input[name="receptionPrViewEditSex"]').forEach(function (r) {
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

                var uploadInput = document.getElementById('receptionPrViewEditProfileUpload')
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

                var url = "{{ url('/api/patients') }}/" + encodeURIComponent(recCurrentPatientId)
                apiFetch(url, { method: 'POST', body: fd }, false)
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data }
                        }).catch(function () {
                            return { ok: false, data: null }
                        })
                    })
                    .then(function (r) {
                        if (recViewEditSave) recViewEditSave.disabled = false
                        if (recViewEditSpinner) recViewEditSpinner.classList.add('hidden')
                        if (recViewEditSaveLabel) recViewEditSaveLabel.textContent = 'Save changes'

                        if (!r || !r.ok) {
                            var msg = (r && r.data && r.data.message) ? String(r.data.message) : 'Failed to save patient info.'
                            if (recViewEditError) {
                                recViewEditError.textContent = msg
                                recViewEditError.classList.remove('hidden')
                            }
                            if (typeof showToast === 'function') showToast(msg, 'error')
                            return
                        }

                        if (typeof showToast === 'function') showToast('Patient updated successfully.', 'success')
                        // Update display and switch back
                        var merged = r.data && r.data.patient ? r.data.patient : (r.data || {})
                        if (merged && merged.user_id != null) {
                            var found = recPatientsRows.find(function (p) { return String(p.user_id) === String(recCurrentPatientId) })
                            if (found) {
                                Object.assign(found, merged)
                            }
                        }
                        recViewEditModeToggle(false)
                        recRenderPatientTable()
                        if (recCurrentPatientId) recOpenPatientPanel(recCurrentPatientId)
                    })
                    .catch(function () {
                        if (recViewEditSave) recViewEditSave.disabled = false
                        if (recViewEditSpinner) recViewEditSpinner.classList.add('hidden')
                        if (recViewEditSaveLabel) recViewEditSaveLabel.textContent = 'Save changes'
                        if (recViewEditError) {
                            recViewEditError.textContent = 'An unexpected error occurred.'
                            recViewEditError.classList.remove('hidden')
                        }
                        if (typeof showToast === 'function') showToast('Network error.', 'error')
                    })
            })
        }

        // ── View edit cancel button ──
        var recViewEditCancel = document.getElementById('receptionPrViewEditCancel')
        if (recViewEditCancel) {
            recViewEditCancel.addEventListener('click', function () {
                recViewEditModeToggle(false)
                if (recViewEditError) {
                    recViewEditError.classList.add('hidden')
                    recViewEditError.textContent = ''
                }
            })
        }

        // ── View edit philhealth formatting ──
        var recViewEditPhilhealth = document.getElementById('receptionPrViewEditPhilhealth')
        if (recViewEditPhilhealth) {
            recViewEditPhilhealth.addEventListener('input', function () {
                var raw = this.value.replace(/[^\d]/g, '')
                if (raw.length > 12) raw = raw.slice(0, 12)
                this.value = recFormatPhilhealth(raw)
            })
        }

        // ── View edit nationality select toggle ──
        var recViewEditNationalitySelect = document.getElementById('receptionPrViewEditNationalitySelect')
        var recViewEditNationality = document.getElementById('receptionPrViewEditNationality')
        if (recViewEditNationalitySelect) {
            recViewEditNationalitySelect.addEventListener('change', function () {
                if (this.value === '__others__') {
                    if (recViewEditNationality) {
                        recViewEditNationality.classList.remove('hidden')
                        this.className = 'w-[30%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                        recViewEditNationality.className = 'w-[70%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    }
                } else {
                    if (recViewEditNationality) {
                        recViewEditNationality.classList.add('hidden')
                        this.className = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                        recViewEditNationality.value = ''
                    }
                }
            })
        }

        // ── View edit phone formatting ──
        var recViewEditContact = document.getElementById('receptionPrViewEditContact')
        if (recViewEditContact) setupPhoneFormat(recViewEditContact)
        var recViewEditEmergNumber = document.getElementById('receptionPrViewEditEmergencyContactNumber')
        if (recViewEditEmergNumber) setupPhoneFormat(recViewEditEmergNumber)

        // ── Helper: find patient by ID ──
        function recFindPatientById(id) {
            if (!recPatientsRows) return null
            return recPatientsRows.find(function (p) { return String(p.user_id) === String(id) }) || null
        }

        // ── Visit Details Modal ──
        var recVisitDetailOverlay = document.getElementById('recVisitDetailOverlay')
        var recVisitDetailClose = document.getElementById('recVisitDetailClose')
        if (recVisitDetailClose) {
            recVisitDetailClose.addEventListener('click', function () {
                if (recVisitDetailOverlay) { recVisitDetailOverlay.classList.add('hidden'); recVisitDetailOverlay.classList.remove('flex') }
            })
        }
        if (recVisitDetailOverlay) {
            recVisitDetailOverlay.addEventListener('click', function (e) {
                if (e.target === recVisitDetailOverlay) { recVisitDetailOverlay.classList.add('hidden'); recVisitDetailOverlay.classList.remove('flex') }
            })
        }
        function openRecVisitDetail(visit) {
            if (!recVisitDetailOverlay || !visit) return
            var appt = visit.appointment || {}
            var doctor = appt.doctor || {}
            var services = appt.services || []
            var prescriptions = visit.prescriptions || []
            var dateRaw = visit.visit_datetime || visit.transaction_datetime || ''
            var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '-'
            var dateEl = document.getElementById('recVisitDetailDate'); if (dateEl) dateEl.textContent = dateText
            var doctorEl = document.getElementById('recVisitDetailDoctor'); if (doctorEl) doctorEl.textContent = recFullName(doctor, 'Doctor')
            var svcHtml = services.length
                ? services.map(function (s) {
                    var name = s.service_name || 'Unknown service'
                    var desc = s.description ? s.description : ''
                    var price = s.price != null ? recFormatCurrency(s.price) : ''
                    var parts = [name]
                    if (desc) parts.push('<span class="text-slate-400">' + recEscHtml(desc) + '</span>')
                    if (price) parts.push('<span class="font-medium text-slate-600">' + price + '</span>')
                    return '<div class="flex flex-wrap items-baseline gap-x-2">' + parts.join(' ') + '</div>'
                }).join('')
                : '-'
            var svcEl = document.getElementById('recVisitDetailServices'); if (svcEl) svcEl.innerHTML = svcHtml
            var feesEl = document.getElementById('recVisitDetailFees'); if (feesEl) feesEl.textContent = recFormatCurrency(visit.amount != null ? visit.amount : '')
            var payStatus = visit.payment_status ? String(visit.payment_status) : '-'
            var payEl = document.getElementById('recVisitDetailPayment'); if (payEl) payEl.textContent = payStatus.charAt(0).toUpperCase() + payStatus.slice(1)
            var apptStatus = appt.status ? String(appt.status) : ''
            var sc = ({ pending:'bg-amber-50 text-amber-700 border-amber-200', confirmed:'border-orange-200 bg-orange-50 text-orange-700', completed:'border-green-200 bg-green-50 text-green-700', cancelled:'bg-red-50 text-red-700 border-red-200', no_show:'bg-slate-100 text-slate-600 border-slate-200', consulted:'border-purple-200 bg-purple-50 text-purple-700', waiting:'bg-amber-50 text-amber-700 border-amber-100', serving:'bg-blue-50 text-blue-700 border-blue-100', done:'bg-emerald-50 text-emerald-700 border-emerald-100', skipped:'bg-orange-50 text-orange-700 border-orange-100', on_hold:'bg-purple-50 text-purple-700 border-purple-100' })[apptStatus] || 'bg-slate-50 text-slate-600 border-slate-100'
            var sl = apptStatus ? apptStatus.charAt(0).toUpperCase() + apptStatus.slice(1).replace(/_/g, ' ') : '-'
            var statusEl = document.getElementById('recVisitDetailStatus')
            if (statusEl) statusEl.innerHTML = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-medium border ' + sc + '">' + recEscHtml(sl) + '</span>'
            var apptTypeEl = document.getElementById('recVisitDetailApptType'); if (apptTypeEl) apptTypeEl.textContent = appt.appointment_type ? String(appt.appointment_type).replace(/_/g, '-') : '-'
            var diagEl = document.getElementById('recVisitDetailDiagnosis'); if (diagEl) diagEl.textContent = visit.diagnosis || '-'
            var txEl = document.getElementById('recVisitDetailTreatment'); if (txEl) txEl.textContent = visit.treatment_notes || '-'
            var rxHtml = ''
            if (prescriptions.length) {
                rxHtml = prescriptions.map(function (rx) {
                    var items = (rx.items || []).map(function (item) {
                        var med = item.medicine || {}
                        return (med.medicine_name || '-') + (item.dosage ? ' (' + item.dosage + ')' : '')
                    }).join(', ')
                    return items || (rx.notes || 'Prescription')
                }).join('; ')
            }
            var rxEl = document.getElementById('recVisitDetailPrescriptions'); if (rxEl) rxEl.textContent = rxHtml || '-'
            recVisitDetailOverlay.classList.remove('hidden')
            recVisitDetailOverlay.classList.add('flex')
        }

        // ── Event delegation for visit detail buttons ──
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.rec-visit-detail-btn')
            if (!btn) return
            try {
                var visitData = JSON.parse(btn.getAttribute('data-visit') || '{}')
                openRecVisitDetail(visitData)
            } catch (err) { /* ignore */ }
        })

        window._recShowingDependentProfile = false
        window._recDependentProfileId = null

        function fetchRecParentData(parentId) {
            apiFetch("{{ url('/api/users') }}/" + encodeURIComponent(parentId), { method: 'GET' })
                .then(function (r) { return r.json() })
                .then(function (data) {
                    if (data && !data.error) recCachedParentData = data
                    else recCachedParentData = null
                    recRenderViewTabContent('dependents')
                })
                .catch(function () { recCachedParentData = null; recRenderViewTabContent('dependents') })
        }

        function renderRecParentOrDependentCards(container, rows, type) {
            var html = '<div class="space-y-3">'
            rows.forEach(function (person) {
                var pid = person && person.user_id != null ? String(person.user_id) : ''
                var age = recAgeFromBirthdate(person && person.birthdate ? String(person.birthdate) : null)
                var profileImg = person && person.prof_path_url ? String(person.prof_path_url) : ''
                html += '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 cursor-pointer hover:border-slate-300 transition-colors rec-' + type + '-card" data-' + type + '-id="' + recEscHtml(pid) + '">' +
                    '<div class="flex items-center gap-4">' +
                        '<div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">' +
                            (profileImg ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">' : '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>') +
                        '</div>' +
                        '<div class="flex-1 min-w-0 space-y-1">' +
                            '<div class="text-[0.82rem] font-semibold text-slate-900 truncate">' + recEscHtml(recFullName(person, type === 'parent' ? 'Parent' : 'Dependent')) + '</div>' +
                            '<div class="text-[0.76rem] text-slate-500">Age: <span class="text-slate-700">' + recEscHtml(age == null ? '-' : String(age)) + '</span> &middot; Sex: <span class="text-slate-700">' + recEscHtml(recSexLabel(person && person.sex)) + '</span></div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            })
            html += '</div>'
            container.innerHTML = html
            container._depClickHandler && container.removeEventListener('click', container._depClickHandler)
            container._depClickHandler = function (e) {
                var card = e.target.closest('.rec-parent-card, .rec-dependent-card')
                if (card) {
                    var id = card.getAttribute('data-parent-id') || card.getAttribute('data-dependent-id')
                    if (id) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading profile...</div></div>'
                        showRecDependentProfileInline(id)
                    }
                }
            }
            container.addEventListener('click', container._depClickHandler)
        }

        function showRecDependentProfileInline(personId) {
            var container = recViewTabContents['dependents']
            if (!container) return
            window._recShowingDependentProfile = true
            window._recDependentProfileId = personId
            apiFetch("{{ url('/api/users') }}/" + encodeURIComponent(personId), { method: 'GET' })
                .then(function (r) { return r.json() })
                .then(function (user) {
                    if (!user || user.error) {
                        container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load profile.</div>'
                        window._recShowingDependentProfile = false
                        return
                    }
                    renderRecDependentProfileInline(container, user)
                })
                .catch(function () {
                    container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load profile.</div>'
                    window._recShowingDependentProfile = false
                })
        }

        function renderRecDependentProfileInline(container, userData) {
            var user = userData || null
            if (!user && window._recDependentProfileId) {
                var allRows = recCachedDependentRows || []
                user = allRows.find(function (r) { return String(r.user_id) === String(window._recDependentProfileId) }) || null
            }
            if (!user) {
                container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Profile data not available.</div>'
                window._recShowingDependentProfile = false
                return
            }
            var profileImg = user.prof_path_url || ''
            container.innerHTML =
                '<div class="space-y-4">' +
                    '<button type="button" class="inline-flex items-center gap-1.5 text-[0.78rem] font-semibold text-slate-500 hover:text-slate-700 rec-profile-back-btn">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>' +
                        'Back to list' +
                    '</button>' +
                    '<div class="grid grid-cols-1 md:grid-cols-5 gap-5">' +
                        '<div class="md:col-span-3 space-y-3">' +
                            '<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Last name</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.lastname || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">First name</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.firstname || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Middle name</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.middlename || '-') + '</div></div>' +
                            '</div>' +
                            '<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(recSexLabel(user.sex)) + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(String(user.birthdate || '').slice(0, 10) || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.civil_status || '-') + '</div></div>' +
                            '</div>' +
                            '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.nationality || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.occupation || '-') + '</div></div>' +
                            '</div>' +
                            '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Address</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg min-h-[2.5rem]">' + recEscHtml(user.address || '-') + '</div></div>' +
                            '<hr class="border-slate-100">' +
                            '<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.philhealth_number || '-') + '</div></div>' +
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.emergency_contact || '-') + '</div></div>' +
                            '</div>' +
                            '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.emergency_contact_number || '-') + '</div></div>' +
                        '</div>' +
                        '<div class="md:col-span-2">' +
                            '<div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">' +
                                '<div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>' +
                                '<div class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">' +
                                    (profileImg ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">' : '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>') +
                                '</div>' +
                                '<div class="mt-4 text-left">' +
                                    '<label class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>' +
                                    '<div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + recEscHtml(user.contact_number || '-') + '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            var backBtn = container.querySelector('.rec-profile-back-btn')
            if (backBtn) {
                backBtn.addEventListener('click', function () {
                    window._recShowingDependentProfile = false
                    window._recDependentProfileId = null
                    recRenderViewTabContent('dependents')
                })
            }
        }

        recSetViewTabActive('profile')
        recSetAgeFilterActiveStyles()
        recUpdateAgeCounts()
        recLoadPatients()
    })
</script>