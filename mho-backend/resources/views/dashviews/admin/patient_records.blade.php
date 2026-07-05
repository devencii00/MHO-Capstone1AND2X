<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900"></h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Clinical</span>
    </div>
    <p class="text-xs text-slate-500 mb-4">
       
    </p>

    <div id="adminPrPatientsError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

    <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
        <div class="flex-1">
            <label for="admin_pr_patients_search" class="block text-[0.7rem] text-slate-600 mb-1">Search patient name</label>
            <input id="admin_pr_patients_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by name (starts with)">
        </div>
        <div class="w-full md:w-44">
            <label for="admin_pr_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
            <select id="admin_pr_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="name_asc">Name A-Z</option>
                <option value="created_desc">Newest first</option>
                <option value="created_asc">Oldest first</option>
            </select>
        </div>
        <div class="w-full md:w-28 pt-1">
            <button type="button" id="adminPrRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                Refresh
            </button>
        </div>
    </div>

    <div class="mb-4">
        <div class="text-[0.7rem] text-slate-600 mb-1">Age filter</div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-green-600 text-white text-[0.72rem] font-semibold" data-age-filter="all">
                All
                <span id="adminPrAgeCountAll" class="ml-1 inline-flex items-center rounded-full bg-white/15 px-2 py-0.5 text-[0.68rem] font-semibold">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="1_5">
                1–5
                <span id="adminPrAgeCount1_5" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="6_12">
                6–12
                <span id="adminPrAgeCount6_12" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="13_18">
                13–18
                <span id="adminPrAgeCount13_18" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="19_30">
                19–30
                <span id="adminPrAgeCount19_30" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="31_up">
                31+
                <span id="adminPrAgeCount31Up" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto overflow-y-auto scrollbar-hidden h-[610px]">
        <table class="min-w-full text-left text-xs text-slate-600">
            <thead>
                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                    <th class="py-2 pr-4 font-semibold"></th>
                    <th class="py-2 pr-4 font-semibold">Patient</th>
                    <th class="py-2 pr-4 font-semibold">Address</th>
                    <th class="py-2 pr-4 font-semibold">Age</th>
                    <th class="py-2 pr-4 font-semibold">Sex</th>
                    <th class="py-2 pr-4 font-semibold">Type</th>
                    <th class="py-2 pr-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody id="admin_pr_patients_table_body">
                <tr>
                    <td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">
                        Loading patients…
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="adminPrPagination" class="flex items-center justify-center gap-3 pt-3 pb-1 flex-wrap"></div>
</div>

<div id="adminPrSlideoverOverlay" class="fixed inset-0 z-40 bg-black/30 opacity-0 pointer-events-none transition-opacity duration-300 ease-out"></div>

<div id="adminPrSlideoverPanel" class="fixed top-0 right-0 z-50 h-full w-full max-w-[560px] bg-white border-l border-slate-200 shadow-2xl translate-x-full transition-transform duration-300 ease-out">
    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 shrink-0">
            <div class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Patient Details</div>
            <button type="button" id="adminPrPanelClose" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800 flex-shrink-0">
                <x-lucide-x class="w-[16px] h-[16px]" />
            </button>
        </div>

        <div class="flex-1 overflow-y-auto scrollbar-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex gap-4">
                    <div class="flex w-20 flex-shrink-0 self-start flex-col items-center gap-2">
                        <div id="adminPrPanelProfilePic" class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <x-lucide-user class="w-9 h-9" />
                            </div>
                        </div>
                        <button type="button" id="adminPrPanelEditInfoBtn" class="inline-flex items-center justify-center rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 transition-colors">
                            Edit Info
                        </button>
                    </div>
                    <div class="flex-1 flex gap-x-5 gap-y-[3px] text-[0.78rem]">
                        <div class="flex-1 space-y-[3px]">
                            <div><span class="text-slate-500">First name:</span> <span id="prDetailFirstname" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Middle Name:</span> <span id="prDetailMiddlename" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Last Name:</span> <span id="prDetailLastname" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Date Of Birth:</span> <span id="prDetailBirthdate" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Address:</span> <span id="prDetailAddress" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Sex:</span> <span id="prDetailSex" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Civil status:</span> <span id="prDetailCivilStatus" class="text-slate-800 ml-1">—</span></div>
                        </div>
                        <div class="flex-1 space-y-[3px]">
                            <div><span class="text-slate-500">Nationality:</span> <span id="prDetailNationality" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Contact Number:</span> <span id="prDetailContact" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">PHIC #:</span> <span id="prDetailPhic" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Occupation:</span> <span id="prDetailOccupation" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Emergency contact:</span> <span id="prDetailEmergContact" class="text-slate-800 ml-1">—</span></div>
                            <div><span class="text-slate-500">Emergency Contact Number:</span> <span id="prDetailEmergNumber" class="text-slate-800 ml-1">—</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 pt-4 pb-3 border-b border-slate-100">
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Patient records</div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="adminPrPanelTabBackground" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Medical background</button>
                    <button type="button" id="adminPrPanelTabVisits" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Visit history</button>
                    <button type="button" id="adminPrPanelTabVitals" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Vitals history</button>
                    <button type="button" id="adminPrPanelTabDependents" class="px-3 py-2 rounded-xl text-[0.78rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">Dependents</button>
                </div>
                <p class="mt-2 text-[0.74rem] text-slate-500">Select a tab to open the matching records panel beside this profile.</p>
            </div>

            <div class="px-5 py-4 space-y-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification status</div>
                    <div id="adminPrPanelVerificationStatus" class="text-[0.8rem] font-semibold text-slate-700 mt-1">—</div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient type</div>
                    <div id="adminPrPanelPatientType" class="text-[0.8rem] font-semibold text-slate-700 mt-1">—</div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification ID</div>
                    <div id="adminPrPanelVerificationId" class="text-[0.8rem] font-semibold text-slate-700 mt-1">—</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="adminPrEditOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-slate-900">Edit Patient Info</div>
                <div id="adminPrEditSubtitle" class="text-[0.72rem] text-slate-500">Update patient profile information.</div>
            </div>
            <button type="button" id="adminPrEditClose" class="text-slate-400 hover:text-slate-600">
                <x-lucide-x class="w-[20px] h-[20px]" />
            </button>
        </div>
        <div class="p-5">
            <div id="adminPrEditError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
            <form id="adminPrEditForm" class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <div class="md:col-span-3 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="adminPrEditLastname" class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                            <input id="adminPrEditLastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="adminPrEditFirstname" class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                            <input id="adminPrEditFirstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="adminPrEditMiddlename" class="block text-[0.7rem] text-slate-600 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                            <input id="adminPrEditMiddlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="adminPrEditSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="adminPrEditSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="adminPrEditBirthdate" class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                            <input id="adminPrEditBirthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="adminPrEditCivilStatus" class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label>
                            <select id="adminPrEditCivilStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
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
                            <label for="adminPrEditNationalitySelect" class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label>
                            <div id="adminPrEditNationalityField" class="flex gap-2">
                                <select id="adminPrEditNationalitySelect" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                    <option value="">None</option>
                                    <option value="Filipino">Filipino</option>
                                    <option value="__others__">Other/s specify</option>
                                </select>
                                <input id="adminPrEditNationality" type="text" class="w-0 hidden rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Please specify">
                            </div>
                        </div>
                        <div>
                            <label for="adminPrEditOccupation" class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label>
                            <input id="adminPrEditOccupation" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                    </div>
                    <div>
                        <label for="adminPrEditAddress" class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                        <textarea id="adminPrEditAddress" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none resize-y" placeholder="Street, barangay, municipality"></textarea>
                    </div>
                    <hr class="border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="adminPrEditPhilhealth" class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                            <input id="adminPrEditPhilhealth" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                        </div>
                        <div>
                            <label for="adminPrEditEmergencyContact" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label>
                            <input id="adminPrEditEmergencyContact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                    </div>
                    <div>
                        <label for="adminPrEditEmergencyContactNumber" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                        <input id="adminPrEditEmergencyContactNumber" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                        <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                        <div id="adminPrEditProfilePreview" class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="mt-3">
                            <label for="adminPrEditProfileUpload" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-green-200 bg-green-50 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Upload photo
                            </label>
                            <input id="adminPrEditProfileUpload" type="file" accept="image/*" class="hidden">
                        </div>
                        <div class="mt-4 text-left">
                            <label for="adminPrEditContact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                            <input id="adminPrEditContact" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                        </div>
                    </div>
                </div>
                <div class="md:col-span-5 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" id="adminPrEditCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" id="adminPrEditSave" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                        <span id="adminPrEditSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                        <span id="adminPrEditSaveLabel">Save changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="adminPrEditConfirmOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                <x-lucide-info class="w-[18px] h-[18px]" />
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-slate-900">Confirm</div>
                <div id="adminPrEditConfirmMessage" class="text-[0.78rem] text-slate-600 mt-0.5">Are you sure?</div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-end gap-2">
            <button type="button" id="adminPrEditConfirmCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="button" id="adminPrEditConfirmOk" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Confirm</button>
        </div>
    </div>
</div>

<div id="adminPrTabDrawer" class="fixed top-0 right-0 md:right-[560px] z-[49] h-full w-full max-w-[480px] bg-white border-l border-slate-200 shadow-xl hidden">
    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 shrink-0">
            <div>
                <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient records</div>
                <div id="adminPrTabDrawerTitle" class="text-[0.82rem] font-semibold text-slate-900 mt-1">Medical background</div>
            </div>
            <button type="button" id="adminPrTabDrawerClose" class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800">
                <x-lucide-x class="w-[16px] h-[16px]" />
            </button>
        </div>
        <div id="adminPrTabDrawerBody" class="flex-1 overflow-y-auto p-4 scrollbar-hidden">
            <div class="text-center text-[0.78rem] text-slate-400 py-8">Select a tab to view records.</div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var apiBaseUrl = "{{ request()->getBasePath() }}/api"
        var defaultProfilePicHtml = '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
        var patientsError = document.getElementById('adminPrPatientsError')
        var patientsSearch = document.getElementById('admin_pr_patients_search')
        var sortSelect = document.getElementById('admin_pr_sort')
        var patientsTableBody = document.getElementById('admin_pr_patients_table_body')
        var pagination = document.getElementById('adminPrPagination')
        var patientsRows = []
        var perPage = 10
        var currentPage = 1
        var visibleCount = 6

        var activeAgeFilter = 'all'
        var ageFilterButtons = Array.prototype.slice.call(document.querySelectorAll('.admin-pr-age-filter'))
        var ageCountAll = document.getElementById('adminPrAgeCountAll')
        var ageCount1_5 = document.getElementById('adminPrAgeCount1_5')
        var ageCount6_12 = document.getElementById('adminPrAgeCount6_12')
        var ageCount13_18 = document.getElementById('adminPrAgeCount13_18')
        var ageCount19_30 = document.getElementById('adminPrAgeCount19_30')
        var ageCount31Up = document.getElementById('adminPrAgeCount31Up')

        var overlay = document.getElementById('adminPrSlideoverOverlay')
        var panel = document.getElementById('adminPrSlideoverPanel')
        var panelClose = document.getElementById('adminPrPanelClose')
        var panelProfilePic = document.getElementById('adminPrPanelProfilePic')
        var panelEditInfoBtn = document.getElementById('adminPrPanelEditInfoBtn')
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
        var panelVerificationStatus = document.getElementById('adminPrPanelVerificationStatus')
        var panelPatientType = document.getElementById('adminPrPanelPatientType')
        var panelVerificationId = document.getElementById('adminPrPanelVerificationId')

        var panelTabBackground = document.getElementById('adminPrPanelTabBackground')
        var panelTabVisits = document.getElementById('adminPrPanelTabVisits')
        var panelTabVitals = document.getElementById('adminPrPanelTabVitals')
        var panelTabDependents = document.getElementById('adminPrPanelTabDependents')

        var patientEditOverlay = document.getElementById('adminPrEditOverlay')
        var patientEditClose = document.getElementById('adminPrEditClose')
        var patientEditCancel = document.getElementById('adminPrEditCancel')
        var patientEditForm = document.getElementById('adminPrEditForm')
        var patientEditError = document.getElementById('adminPrEditError')
        var patientEditSubtitle = document.getElementById('adminPrEditSubtitle')
        var patientEditFirstname = document.getElementById('adminPrEditFirstname')
        var patientEditMiddlename = document.getElementById('adminPrEditMiddlename')
        var patientEditLastname = document.getElementById('adminPrEditLastname')
        var patientEditSexMale = document.querySelector('input[name="adminPrEditSex"][value="Male"]')
        var patientEditSexFemale = document.querySelector('input[name="adminPrEditSex"][value="Female"]')
        var patientEditBirthdate = document.getElementById('adminPrEditBirthdate')
        var patientEditCivilStatus = document.getElementById('adminPrEditCivilStatus')
        var patientEditNationalitySelect = document.getElementById('adminPrEditNationalitySelect')
        var patientEditNationality = document.getElementById('adminPrEditNationality')
        var patientEditNationalityField = document.getElementById('adminPrEditNationalityField')
        var patientEditAddress = document.getElementById('adminPrEditAddress')
        var patientEditContact = document.getElementById('adminPrEditContact')
        var patientEditPhilhealth = document.getElementById('adminPrEditPhilhealth')
        var patientEditOccupation = document.getElementById('adminPrEditOccupation')
        var patientEditEmergencyContact = document.getElementById('adminPrEditEmergencyContact')
        var patientEditEmergencyContactNumber = document.getElementById('adminPrEditEmergencyContactNumber')
        var patientEditProfileUpload = document.getElementById('adminPrEditProfileUpload')
        var patientEditProfilePreview = document.getElementById('adminPrEditProfilePreview')
        var patientEditSave = document.getElementById('adminPrEditSave')
        var patientEditSpinner = document.getElementById('adminPrEditSpinner')
        var patientEditSaveLabel = document.getElementById('adminPrEditSaveLabel')

        var patientEditConfirmOverlay = document.getElementById('adminPrEditConfirmOverlay')
        var patientEditConfirmMessage = document.getElementById('adminPrEditConfirmMessage')
        var patientEditConfirmOk = document.getElementById('adminPrEditConfirmOk')
        var patientEditConfirmCancel = document.getElementById('adminPrEditConfirmCancel')
        var patientEditConfirmResolver = null
        var editingPatientId = null

        var tabDrawer = document.getElementById('adminPrTabDrawer')
        var tabDrawerTitle = document.getElementById('adminPrTabDrawerTitle')
        var tabDrawerBody = document.getElementById('adminPrTabDrawerBody')
        var tabDrawerClose = document.getElementById('adminPrTabDrawerClose')

        var cachedMedBgRows = null
        var cachedVisitRows = null
        var cachedVitalRows = null
        var cachedDependentRows = null
        var currentPatientId = null
        var currentPanelTab = null
        var activeDependentRecord = null
        var activeDependentTab = 'background'
        var activeDependentMedBgRows = null
        var activeDependentVisitRows = null
        var activeDependentVitalRows = null
        var activeDependentVerification = null

        function getPatientTableRows() {
            return document.querySelectorAll('#admin_pr_patients_table_body .admin-pr-patient-row')
        }

        function escapeHtml(text) {
            return String(text || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
        }

        function showInlineBox(el, message) {
            if (!el) return
            el.textContent = message || ''
            el.classList.toggle('hidden', !message)
        }

        function showPatientEditError(message) {
            showInlineBox(patientEditError, message)
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showPatientEditSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function categoryLabel(key) {
            var k = String(key || '')
            if (k === 'allergy_food') return 'Food'
            if (k === 'allergy_drug') return 'Drug'
            if (k === 'condition') return 'Condition'
            return k || '—'
        }

        function fullName(p, fallback) {
            if (!p) return fallback || '—'
            var parts = []
            if (p.firstname) parts.push(String(p.firstname))
            if (p.middlename) parts.push(String(p.middlename))
            if (p.lastname) parts.push(String(p.lastname))
            var name = parts.join(' ').trim()
            if (name) return name
            if (p.email) return String(p.email)
            return fallback || ('#' + (p.user_id || ''))
        }

        function nameOnly(p) {
            if (!p) return ''
            var parts = []
            if (p.firstname) parts.push(String(p.firstname))
            if (p.middlename) parts.push(String(p.middlename))
            if (p.lastname) parts.push(String(p.lastname))
            return parts.join(' ').trim()
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

        function matchesAgeFilter(age, filterKey) {
            if (filterKey === 'all') return true
            if (age == null) return false
            if (filterKey === '1_5') return age >= 1 && age <= 5
            if (filterKey === '6_12') return age >= 6 && age <= 12
            if (filterKey === '13_18') return age >= 13 && age <= 18
            if (filterKey === '19_30') return age >= 19 && age <= 30
            if (filterKey === '31_up') return age >= 31
            return true
        }

        function displayValue(value) {
            return (value != null && value !== '') ? String(value) : '—'
        }

        function sexLabel(value) {
            var text = displayValue(value)
            if (text === '—') return text
            return text.charAt(0).toUpperCase() + text.slice(1)
        }

        function setAgeFilterActiveStyles() {
            ageFilterButtons.forEach(function (btn) {
                var key = btn.getAttribute('data-age-filter') || ''
                var isActive = key === activeAgeFilter
                btn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                if (isActive) {
                    btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
                } else {
                    btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                }
            })
        }

        function setText(el, text) {
            if (!el) return
            el.textContent = text == null ? '' : String(text)
        }

        function setTabButtonActive(btn, isActive) {
            if (!btn) return
            btn.classList.remove('bg-green-600', 'text-white', 'border-green-600', 'bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
            if (isActive) {
                btn.classList.add('bg-green-600', 'text-white', 'border-green-600')
            } else {
                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
            }
        }

        function syncTabButtonState() {
            setTabButtonActive(panelTabBackground, currentPanelTab === 'background')
            setTabButtonActive(panelTabVisits, currentPanelTab === 'visits')
            setTabButtonActive(panelTabVitals, currentPanelTab === 'vitals')
            setTabButtonActive(panelTabDependents, currentPanelTab === 'dependents')
        }

        function formatRecordedAt(value) {
            var raw = value ? String(value) : ''
            if (!raw) return '—'
            return raw.replace('T', ' ').slice(0, 16)
        }

        function formatNumeric(value, decimals) {
            if (value == null || value === '') return '—'
            var num = typeof value === 'number' ? value : parseFloat(value)
            if (isNaN(num)) return '—'
            return num.toFixed(decimals == null ? 1 : decimals)
        }

        function formatCurrency(value) {
            if (value == null || value === '') return '—'
            var num = typeof value === 'number' ? value : parseFloat(value)
            if (isNaN(num)) return '—'
            return 'PHP ' + num.toFixed(2)
        }

        function openPanel() {
            if (overlay) {
                overlay.classList.remove('opacity-0', 'pointer-events-none')
                overlay.classList.add('opacity-100', 'pointer-events-auto')
            }
            if (panel) {
                panel.classList.remove('translate-x-full')
                panel.classList.add('translate-x-0')
            }
        }

        function closeTabDrawer() {
            if (tabDrawer) {
                tabDrawer.classList.add('hidden')
            }
            currentPanelTab = null
            activeDependentRecord = null
            activeDependentTab = 'background'
            activeDependentMedBgRows = null
            activeDependentVisitRows = null
            activeDependentVitalRows = null
            activeDependentVerification = null
            syncTabButtonState()
        }

        function openTabDrawer() {
            if (tabDrawer) {
                tabDrawer.classList.remove('hidden')
            }
        }

        function closePanel() {
            currentPatientId = null
            cachedMedBgRows = null
            cachedVisitRows = null
            cachedVitalRows = null
            cachedDependentRows = null
            closeTabDrawer()
            if (overlay) {
                overlay.classList.add('opacity-0', 'pointer-events-none')
                overlay.classList.remove('opacity-100', 'pointer-events-auto')
            }
            if (panel) {
                panel.classList.add('translate-x-full')
                panel.classList.remove('translate-x-0')
            }
        }

        function setPatientEditSubmitting(isSubmitting) {
            if (patientEditSave) patientEditSave.disabled = !!isSubmitting
            if (patientEditSpinner) patientEditSpinner.classList.toggle('hidden', !isSubmitting)
            if (patientEditSaveLabel) patientEditSaveLabel.textContent = isSubmitting ? 'Saving...' : 'Save changes'
        }

        function updatePatientProfilePreview(path) {
            if (!patientEditProfilePreview) return
            if (path) {
                patientEditProfilePreview.innerHTML = '<img src="' + String(path).replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
            } else {
                patientEditProfilePreview.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
            }
        }

        function formatPhone(val) {
            var s = String(val || '').replace(/[^\d]/g, '')
            if (s.startsWith('63')) s = s.slice(2)
            if (s.startsWith('0')) s = s.slice(1)
            if (s.length === 10) return '+63 ' + s.slice(0, 3) + ' ' + s.slice(3, 6) + ' ' + s.slice(6)
            return val || ''
        }

        function parsePhoneRaw(val) {
            var s = String(val || '').replace(/[^\d]/g, '')
            if (s.startsWith('63')) return '+' + s
            if (s.startsWith('0')) return '+63' + s.slice(1)
            return s ? '+63' + s : ''
        }

        function formatPhilhealth(val) {
            var s = String(val || '').replace(/[^\d]/g, '')
            if (s.length >= 2 && s.length <= 4) return s.slice(0, 2) + '-' + s.slice(2)
            if (s.length > 4 && s.length <= 11) return s.slice(0, 2) + '-' + s.slice(2, 11) + '-' + s.slice(11)
            if (s.length > 11) return s.slice(0, 2) + '-' + s.slice(2, 11) + '-' + s.slice(11, 12)
            return s
        }

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

        function normalizePhilippinesNumber(value) {
            var raw = String(value || '').trim()
            if (!raw) return ''
            raw = raw.replace(/\s+/g, '').replace(/-/g, '')
            if (raw.startsWith('+63')) return raw
            if (raw.startsWith('63')) return '+' + raw
            if (raw.startsWith('0') && raw.length >= 2) return '+63' + raw.slice(1)
            if (/^\d+$/.test(raw)) return '+63' + raw
            return raw
        }

        function isValidPhilippinesNumber(value) {
            var normalized = normalizePhilippinesNumber(value)
            return /^\+63\d{10}$/.test(normalized)
        }

        function isValidName(value) {
            var v = String(value || '').trim()
            if (v === '') return true
            return /^[A-Za-z][A-Za-z\s.'-]*$/.test(v)
        }

        function openPatientEditModal(patient) {
            if (!patientEditOverlay || !patient) return
            editingPatientId = patient && patient.user_id ? String(patient.user_id) : null
            showPatientEditError('')
            setPatientEditSubmitting(false)

            var fullName = fullNameForEdit(patient)
            if (patientEditSubtitle) patientEditSubtitle.textContent = 'Editing — ' + fullName
            if (patientEditFirstname) patientEditFirstname.value = patient.firstname || ''
            if (patientEditMiddlename) patientEditMiddlename.value = patient.middlename || ''
            if (patientEditLastname) patientEditLastname.value = patient.lastname || ''
            if (patientEditSexMale) patientEditSexMale.checked = patient.sex === 'Male'
            if (patientEditSexFemale) patientEditSexFemale.checked = patient.sex === 'Female'
            if (patientEditBirthdate) {
                var birthdate = patient.birthdate || ''
                patientEditBirthdate.value = birthdate ? String(birthdate).slice(0, 10) : ''
            }
            if (patientEditCivilStatus) patientEditCivilStatus.value = patient.civil_status || ''
            if (patientEditNationalitySelect && patientEditNationality && patientEditNationalityField) {
                var nat = (patient.nationality || '').trim()
                if (!nat || nat === 'None' || nat === 'Filipino') {
                    patientEditNationalitySelect.value = nat === 'Filipino' ? 'Filipino' : ''
                    patientEditNationalitySelect.className = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    patientEditNationality.classList.add('hidden')
                    patientEditNationality.value = ''
                } else {
                    patientEditNationalitySelect.value = '__others__'
                    patientEditNationalitySelect.className = 'w-[30%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    patientEditNationality.classList.remove('hidden')
                    patientEditNationality.className = 'w-[70%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    patientEditNationality.value = nat
                }
            }
            if (patientEditAddress) patientEditAddress.value = patient.address || ''
            if (patientEditContact) patientEditContact.value = patient.contact_number ? formatPhone(patient.contact_number) : ''
            if (patientEditPhilhealth) {
                var phicRaw = patient.philhealth_number || ''
                patientEditPhilhealth.value = phicRaw ? formatPhilhealth(phicRaw) : ''
            }
            if (patientEditOccupation) patientEditOccupation.value = patient.occupation || ''
            if (patientEditEmergencyContact) patientEditEmergencyContact.value = patient.emergency_contact || ''
            if (patientEditEmergencyContactNumber) {
                var emergencyRaw = patient.emergency_contact_number || ''
                patientEditEmergencyContactNumber.value = emergencyRaw ? formatPhone(emergencyRaw) : ''
            }

            updatePatientProfilePreview(patient.prof_path_url || patient.prof_path || null)

            if (patientEditProfileUpload) patientEditProfileUpload.value = ''
            patientEditOverlay.classList.remove('hidden')
            patientEditOverlay.classList.add('flex')
        }

        function fullNameForEdit(patient) {
            if (!patient) return 'Patient'
            var full = ((patient.firstname || '') + ' ' + (patient.lastname || '')).trim()
            if (full) return full
            return patient.email || ('Patient #' + (patient.user_id || ''))
        }

        function closePatientEditModal() {
            if (!patientEditOverlay) return
            patientEditOverlay.classList.add('hidden')
            patientEditOverlay.classList.remove('flex')
            editingPatientId = null
            showInlineBox(patientEditError, '')
        }

        function confirmPatientEditAction(message) {
            return new Promise(function (resolve) {
                if (!patientEditConfirmOverlay || !patientEditConfirmMessage || !patientEditConfirmOk || !patientEditConfirmCancel) {
                    resolve(window.confirm(message || 'Are you sure?'))
                    return
                }
                patientEditConfirmMessage.textContent = message || 'Are you sure?'
                patientEditConfirmResolver = resolve
                patientEditConfirmOverlay.classList.remove('hidden')
                patientEditConfirmOverlay.classList.add('flex')
            })
        }

        function closePatientEditConfirm(result) {
            if (patientEditConfirmOverlay) {
                patientEditConfirmOverlay.classList.add('hidden')
                patientEditConfirmOverlay.classList.remove('flex')
            }
            var resolver = patientEditConfirmResolver
            patientEditConfirmResolver = null
            if (typeof resolver === 'function') resolver(!!result)
        }

        function mergePatientRecord(updatedPatient) {
            if (!updatedPatient || updatedPatient.user_id == null) return
            var updatedId = String(updatedPatient.user_id)
            var merged = updatedPatient
            var found = false
            for (var i = 0; i < patientsRows.length; i++) {
                if (patientsRows[i] && String(patientsRows[i].user_id) === updatedId) {
                    merged = Object.assign({}, patientsRows[i], updatedPatient)
                    patientsRows[i] = merged
                    found = true
                    break
                }
            }
            if (!found) {
                patientsRows.push(updatedPatient)
                merged = updatedPatient
            }

            if (currentPatientId && currentPatientId === updatedId) {
                populatePatientDetails(merged)
            }
            renderPatients()
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

        function renderDrawerTable(headers, rowsHtml, emptyMessage, loadingMessage) {
            tabDrawerBody.innerHTML = buildTableHtml(headers, rowsHtml, emptyMessage, loadingMessage)
        }

        function renderDrawerMedicalBackground(entries) {
            var headers = ['Category', 'Name', 'Diagnosis Date', 'Procedure Date', 'Notes']
            if (entries == null) {
                renderDrawerTable(headers, '', 'No medical background entries found.', 'Loading medical background entries...')
                return
            }
            var rowsHtml = ''
            entries.forEach(function (row) {
                var diagnosisDate = row && row.diagnosis_date ? String(row.diagnosis_date) : ''
                var diagnosisTime = row && row.diagnosis_time ? String(row.diagnosis_time) : ''
                if (diagnosisDate || diagnosisTime) diagnosisDate = (diagnosisDate || '') + (diagnosisTime ? ' ' + diagnosisTime.slice(0, 5) : '')
                var procedureDate = row && row.procedure_date ? String(row.procedure_date) : ''
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(categoryLabel(row.category)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(row && row.name ? String(row.name) : '—') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosisDate ? escapeHtml(diagnosisDate) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (procedureDate ? escapeHtml(procedureDate) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (row && row.notes ? escapeHtml(String(row.notes)) : '<span class="text-slate-400">—</span>') + '</td>' +
                '</tr>'
            })
            renderDrawerTable(headers, rowsHtml, 'No medical background entries found.')
        }

        function renderDrawerVisits(rows) {
            var headers = ['Doctor', 'Visit date', 'Fees', 'Action']
            if (rows == null) {
                renderDrawerTable(headers, '', 'No visits found.', 'Loading visit history...')
                return
            }
            var rowsHtml = ''
            rows.forEach(function (visit) {
                var appointment = visit && visit.appointment ? visit.appointment : null
                var doctor = appointment && appointment.doctor ? appointment.doctor : null
                var dateRaw = visit && (visit.visit_datetime || visit.transaction_datetime) ? String(visit.visit_datetime || visit.transaction_datetime) : ''
                var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '—'
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(fullName(doctor, 'Doctor')) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(dateText) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(formatCurrency(visit && visit.amount != null ? visit.amount : '')) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' +
                        '<button type="button" class="admin-pr-open-vitals-tab inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.72rem] font-semibold hover:bg-slate-50">Open Vitals</button>' +
                    '</td>' +
                '</tr>'
            })
            renderDrawerTable(headers, rowsHtml, 'No visits found.')

            tabDrawerBody.querySelectorAll('.admin-pr-open-vitals-tab').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    setPanelTab('vitals')
                })
            })
        }

        function renderDrawerVitals(rows) {
            var headers = ['Recorded', 'Height (cm)', 'Weight (kg)', 'BP', 'Temp', 'Pulse']
            if (rows == null) {
                renderDrawerTable(headers, '', 'No vitals found.', 'Loading vitals history...')
                return
            }
            var rowsHtml = ''
            rows.forEach(function (vital) {
                var recorded = formatRecordedAt(vital && vital.recorded_at ? vital.recorded_at : (vital && vital.appointment_datetime ? vital.appointment_datetime : ''))
                var height = vital && vital.height_cm != null ? formatNumeric(vital.height_cm, 1) : '—'
                var weight = vital && vital.weight_kg != null ? formatNumeric(vital.weight_kg, 1) : '—'
                var bp = vital && vital.blood_pressure ? String(vital.blood_pressure) : '—'
                var temp = vital && vital.temperature != null ? formatNumeric(vital.temperature, 1) : '—'
                var pulse = vital && vital.pulse_rate != null ? String(vital.pulse_rate) : '—'
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(recorded) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(height) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(weight) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(bp) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(temp) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(pulse) + '</td>' +
                '</tr>'
            })
            renderDrawerTable(headers, rowsHtml, 'No vitals found.')
        }

        function renderDependentsList(rows) {
            if (rows == null) {
                tabDrawerBody.innerHTML = '<div class="space-y-3">' +
                    '<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-[0.78rem] text-slate-600">Loading dependents...</div>' +
                '</div>'
                return
            }

            if (!rows.length) {
                tabDrawerBody.innerHTML = '<div class="space-y-3">' +
                    '<div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-[0.78rem] text-slate-600">No dependents found for this patient.</div>' +
                '</div>'
                return
            }

            var html = '<div class="space-y-3">'
            rows.forEach(function (dependent) {
                var dependentId = dependent && dependent.user_id != null ? String(dependent.user_id) : ''
                var age = ageFromBirthdate(dependent && dependent.birthdate ? String(dependent.birthdate) : null)
                var profileImg = dependent && dependent.prof_path_url ? String(dependent.prof_path_url) : ''
                html += '<button type="button" class="admin-pr-dependent-card w-full text-left rounded-2xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition-colors" data-dependent-id="' + escapeHtml(dependentId) + '">' +
                    '<div class="flex items-center gap-4">' +
                        '<div class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">' +
                            (profileImg
                                ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                                : '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>') +
                        '</div>' +
                        '<div class="flex-1 min-w-0 space-y-1">' +
                            '<div class="text-[0.82rem] font-semibold text-slate-900 truncate">' + escapeHtml(fullName(dependent, 'Dependent')) + '</div>' +
                            '<div class="text-[0.76rem] text-slate-500">Age: <span class="text-slate-700">' + escapeHtml(age == null ? '—' : String(age)) + '</span></div>' +
                            '<div class="text-[0.76rem] text-slate-500">Sex: <span class="text-slate-700">' + escapeHtml(sexLabel(dependent && dependent.sex)) + '</span></div>' +
                        '</div>' +
                    '</div>' +
                '</button>'
            })
            html += '</div>'
            tabDrawerBody.innerHTML = html

            tabDrawerBody.querySelectorAll('.admin-pr-dependent-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    var dependentId = this.getAttribute('data-dependent-id')
                    if (!dependentId) return
                    openDependentRecord(dependentId)
                })
            })
        }

        function renderDependentRecordView() {
            if (!tabDrawerBody || !activeDependentRecord) return

            var record = activeDependentRecord
            if (tabDrawerTitle) tabDrawerTitle.textContent = fullName(record, 'Dependent')
            var profileImg = record && record.prof_path_url ? String(record.prof_path_url) : ''
            var birthdate = record && record.birthdate ? String(record.birthdate) : ''
            var age = ageFromBirthdate(birthdate)
            var verificationStatus = activeDependentVerification && activeDependentVerification.status ? String(activeDependentVerification.status) : 'Not submitted'
            var patientType = activeDependentVerification && activeDependentVerification.type ? String(activeDependentVerification.type) : '—'
            var verificationHtml = '—'
            if (activeDependentVerification && activeDependentVerification.document_url) {
                verificationHtml = '<a href="' + String(activeDependentVerification.document_url).replace(/"/g, '&quot;') + '" target="_blank" class="text-green-700 underline hover:text-green-800">View ID</a>'
            }

            var contentHtml = ''
            if (activeDependentTab === 'background') {
                contentHtml = buildTableHtml(
                    ['Category', 'Name', 'Diagnosis Date', 'Procedure Date', 'Notes'],
                    buildDependentMedicalRows(activeDependentMedBgRows),
                    'No medical background entries found.',
                    'Loading medical background entries...'
                )
            } else if (activeDependentTab === 'visits') {
                contentHtml = buildTableHtml(
                    ['Doctor', 'Visit date', 'Fees', 'Action'],
                    buildDependentVisitRows(activeDependentVisitRows),
                    'No visits found.',
                    'Loading visit history...'
                )
            } else {
                contentHtml = buildTableHtml(
                    ['Recorded', 'Height (cm)', 'Weight (kg)', 'BP', 'Temp', 'Pulse'],
                    buildDependentVitalRows(activeDependentVitalRows),
                    'No vitals found.',
                    'Loading vitals history...'
                )
            }

            var html = ''
            html += '<div class="space-y-4">'
            html += '<div class="flex items-center justify-between gap-3">' +
                '<button type="button" class="admin-pr-dependent-back inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.76rem] font-semibold hover:bg-slate-50">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>' +
                    'Back' +
                '</button>' +
                '<div class="text-right min-w-0">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Dependent record</div>' +
                    '<div class="text-[0.82rem] font-semibold text-slate-900 truncate">' + escapeHtml(fullName(record, 'Dependent')) + '</div>' +
                '</div>' +
            '</div>'

            html += '<div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">' +
                '<div class="px-4 py-4 border-b border-slate-100">' +
                    '<div class="flex gap-4">' +
                        '<div class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 self-start">' +
                            (profileImg
                                ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                                : '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>') +
                        '</div>' +
                        '<div class="flex-1 flex gap-x-5 gap-y-[3px] text-[0.78rem]">' +
                            '<div class="flex-1 space-y-[3px]">' +
                                '<div><span class="text-slate-500">First name:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.firstname)) + '</span></div>' +
                                '<div><span class="text-slate-500">Middle Name:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.middlename)) + '</span></div>' +
                                '<div><span class="text-slate-500">Last Name:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.lastname)) + '</span></div>' +
                                '<div><span class="text-slate-500">Date Of Birth:</span> <span class="text-slate-800 ml-1">' + escapeHtml(birthdate ? birthdate.substring(0, 10) + (age != null ? ' (Age: ' + age + ')' : '') : '—') + '</span></div>' +
                                '<div><span class="text-slate-500">Address:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.address)) + '</span></div>' +
                                '<div><span class="text-slate-500">Sex:</span> <span class="text-slate-800 ml-1">' + escapeHtml(sexLabel(record && record.sex)) + '</span></div>' +
                                '<div><span class="text-slate-500">Civil status:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.civil_status)) + '</span></div>' +
                            '</div>' +
                            '<div class="flex-1 space-y-[3px]">' +
                                '<div><span class="text-slate-500">Nationality:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.nationality)) + '</span></div>' +
                                '<div><span class="text-slate-500">Contact Number:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.contact_number)) + '</span></div>' +
                                '<div><span class="text-slate-500">PHIC #:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.philhealth_number)) + '</span></div>' +
                                '<div><span class="text-slate-500">Occupation:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.occupation)) + '</span></div>' +
                                '<div><span class="text-slate-500">Emergency contact:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.emergency_contact)) + '</span></div>' +
                                '<div><span class="text-slate-500">Emergency Contact Number:</span> <span class="text-slate-800 ml-1">' + escapeHtml(displayValue(record && record.emergency_contact_number)) + '</span></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="px-4 pt-4 pb-3 border-b border-slate-100">' +
                    '<div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mb-2">Patient records</div>' +
                    '<div class="flex flex-wrap items-center gap-2">' +
                        '<button type="button" class="admin-pr-dependent-tab px-3 py-2 rounded-xl text-[0.78rem] font-semibold border ' + (activeDependentTab === 'background' ? 'border-green-600 bg-green-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50') + '" data-dependent-tab="background">Medical background</button>' +
                        '<button type="button" class="admin-pr-dependent-tab px-3 py-2 rounded-xl text-[0.78rem] font-semibold border ' + (activeDependentTab === 'visits' ? 'border-green-600 bg-green-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50') + '" data-dependent-tab="visits">Visit history</button>' +
                        '<button type="button" class="admin-pr-dependent-tab px-3 py-2 rounded-xl text-[0.78rem] font-semibold border ' + (activeDependentTab === 'vitals' ? 'border-green-600 bg-green-600 text-white' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50') + '" data-dependent-tab="vitals">Vitals history</button>' +
                    '</div>' +
                '</div>' +
                '<div class="px-4 py-4 space-y-3 border-b border-slate-100">' +
                    '<div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2"><div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification status</div><div class="text-[0.8rem] font-semibold text-slate-700 mt-1">' + escapeHtml(verificationStatus) + '</div></div>' +
                    '<div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2"><div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient type</div><div class="text-[0.8rem] font-semibold text-slate-700 mt-1">' + escapeHtml(patientType) + '</div></div>' +
                    '<div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2"><div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Verification ID</div><div class="text-[0.8rem] font-semibold text-slate-700 mt-1">' + verificationHtml + '</div></div>' +
                '</div>' +
                '<div class="px-4 py-4">' + contentHtml + '</div>' +
            '</div>'
            html += '</div>'

            tabDrawerBody.innerHTML = html

            var backBtn = tabDrawerBody.querySelector('.admin-pr-dependent-back')
            if (backBtn) {
                backBtn.addEventListener('click', function () {
                    activeDependentRecord = null
                    activeDependentTab = 'background'
                    activeDependentMedBgRows = null
                    activeDependentVisitRows = null
                    activeDependentVitalRows = null
                    activeDependentVerification = null
                    renderTabDrawerContent('dependents')
                })
            }

            tabDrawerBody.querySelectorAll('.admin-pr-dependent-tab').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    activeDependentTab = this.getAttribute('data-dependent-tab') || 'background'
                    renderDependentRecordView()
                })
            })

            tabDrawerBody.querySelectorAll('.admin-pr-dependent-open-vitals-tab').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    activeDependentTab = 'vitals'
                    renderDependentRecordView()
                })
            })
        }

        function buildDependentMedicalRows(entries) {
            if (entries == null || !entries.length) return ''
            var rowsHtml = ''
            entries.forEach(function (row) {
                var diagnosisDate = row && row.diagnosis_date ? String(row.diagnosis_date) : ''
                var diagnosisTime = row && row.diagnosis_time ? String(row.diagnosis_time) : ''
                if (diagnosisDate || diagnosisTime) diagnosisDate = (diagnosisDate || '') + (diagnosisTime ? ' ' + diagnosisTime.slice(0, 5) : '')
                var procedureDate = row && row.procedure_date ? String(row.procedure_date) : ''
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(categoryLabel(row.category)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(displayValue(row && row.name)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosisDate ? escapeHtml(diagnosisDate) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (procedureDate ? escapeHtml(procedureDate) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (row && row.notes ? escapeHtml(String(row.notes)) : '<span class="text-slate-400">—</span>') + '</td>' +
                '</tr>'
            })
            return rowsHtml
        }

        function buildDependentVisitRows(rows) {
            if (rows == null || !rows.length) return ''
            var rowsHtml = ''
            rows.forEach(function (visit) {
                var appointment = visit && visit.appointment ? visit.appointment : null
                var doctor = appointment && appointment.doctor ? appointment.doctor : null
                var dateRaw = visit && (visit.visit_datetime || visit.transaction_datetime) ? String(visit.visit_datetime || visit.transaction_datetime) : ''
                var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '—'
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(fullName(doctor, 'Doctor')) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(dateText) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(formatCurrency(visit && visit.amount != null ? visit.amount : '')) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]"><button type="button" class="admin-pr-dependent-open-vitals-tab inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.72rem] font-semibold hover:bg-slate-50">Open Vitals</button></td>' +
                '</tr>'
            })
            return rowsHtml
        }

        function buildDependentVitalRows(rows) {
            if (rows == null || !rows.length) return ''
            var rowsHtml = ''
            rows.forEach(function (vital) {
                var recorded = formatRecordedAt(vital && vital.recorded_at ? vital.recorded_at : (vital && vital.appointment_datetime ? vital.appointment_datetime : ''))
                var height = vital && vital.height_cm != null ? formatNumeric(vital.height_cm, 1) : '—'
                var weight = vital && vital.weight_kg != null ? formatNumeric(vital.weight_kg, 1) : '—'
                var bp = vital && vital.blood_pressure ? String(vital.blood_pressure) : '—'
                var temp = vital && vital.temperature != null ? formatNumeric(vital.temperature, 1) : '—'
                var pulse = vital && vital.pulse_rate != null ? String(vital.pulse_rate) : '—'
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(recorded) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(height) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(weight) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(bp) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(temp) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(pulse) + '</td>' +
                '</tr>'
            })
            return rowsHtml
        }

        function openDependentRecord(dependentId) {
            if (!dependentId || !Array.isArray(cachedDependentRows)) return
            var found = null
            for (var i = 0; i < cachedDependentRows.length; i++) {
                if (cachedDependentRows[i] && String(cachedDependentRows[i].user_id) === String(dependentId)) {
                    found = cachedDependentRows[i]
                    break
                }
            }
            if (!found) return

            activeDependentRecord = found
            activeDependentTab = 'background'
            activeDependentMedBgRows = null
            activeDependentVisitRows = null
            activeDependentVitalRows = null
            activeDependentVerification = null
            renderDependentRecordView()

            var depId = String(dependentId)
            var medBgReq = apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=100&patient_id=" + encodeURIComponent(depId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var visitsReq = apiFetch(apiBaseUrl + "/visits?per_page=100&patient_id=" + encodeURIComponent(depId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var vitalsReq = apiFetch(apiBaseUrl + "/vitals?per_page=100&patient_id=" + encodeURIComponent(depId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var verificationReq = apiFetch(apiBaseUrl + "/patient-verifications?per_page=1&patient_id=" + encodeURIComponent(depId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            Promise.all([medBgReq, visitsReq, vitalsReq, verificationReq])
                .then(function (results) {
                    if (!activeDependentRecord || String(activeDependentRecord.user_id) !== depId) return
                    activeDependentMedBgRows = (!results[0] || !results[0].ok || !results[0].data) ? [] : (Array.isArray(results[0].data.data) ? results[0].data.data : (Array.isArray(results[0].data) ? results[0].data : []))
                    activeDependentVisitRows = (!results[1] || !results[1].ok || !results[1].data) ? [] : (Array.isArray(results[1].data.data) ? results[1].data.data : (Array.isArray(results[1].data) ? results[1].data : []))
                    activeDependentVitalRows = (!results[2] || !results[2].ok || !results[2].data) ? [] : (Array.isArray(results[2].data.data) ? results[2].data.data : (Array.isArray(results[2].data) ? results[2].data : []))

                    var verificationRows = (!results[3] || !results[3].ok || !results[3].data) ? [] : (Array.isArray(results[3].data.data) ? results[3].data.data : (Array.isArray(results[3].data) ? results[3].data : []))
                    activeDependentVerification = verificationRows.length ? verificationRows[0] : null
                    renderDependentRecordView()
                })
                .catch(function () {
                    if (!activeDependentRecord || String(activeDependentRecord.user_id) !== depId) return
                    activeDependentMedBgRows = []
                    activeDependentVisitRows = []
                    activeDependentVitalRows = []
                    activeDependentVerification = null
                    renderDependentRecordView()
                })
        }

        function renderTabDrawerContent(key) {
            if (!tabDrawerTitle || !tabDrawerBody) return
            if (key === 'background') {
                tabDrawerTitle.textContent = 'Medical background'
                renderDrawerMedicalBackground(cachedMedBgRows)
            } else if (key === 'visits') {
                tabDrawerTitle.textContent = 'Visit history'
                renderDrawerVisits(cachedVisitRows)
            } else if (key === 'vitals') {
                tabDrawerTitle.textContent = 'Vitals history'
                renderDrawerVitals(cachedVitalRows)
            } else if (key === 'dependents') {
                if (activeDependentRecord) {
                    tabDrawerTitle.textContent = fullName(activeDependentRecord, 'Dependent')
                    renderDependentRecordView()
                } else {
                    tabDrawerTitle.textContent = 'Dependents'
                    renderDependentsList(cachedDependentRows)
                }
            }
        }

        function setPanelTab(key) {
            if (!currentPatientId) return
            if (key === 'dependents') {
                activeDependentRecord = null
                activeDependentTab = 'background'
                activeDependentMedBgRows = null
                activeDependentVisitRows = null
                activeDependentVitalRows = null
                activeDependentVerification = null
            }
            currentPanelTab = key
            syncTabButtonState()
            openTabDrawer()
            renderTabDrawerContent(key)
        }

        function showPage(page) {
            var rows = getPatientTableRows()
            var total = rows.length
            if (!total || !pagination) return
            var totalPages = Math.ceil(total / perPage)
            if (page < 1 || page > totalPages) return
            currentPage = page
            var start = (page - 1) * perPage
            var end = Math.min(start + perPage, total)
            rows.forEach(function (row, index) {
                row.style.display = (index >= start && index < end) ? '' : 'none'
            })
            renderPagination()
        }

        function renderPagination() {
            if (!pagination) return
            var rows = getPatientTableRows()
            var total = rows.length
            if (total === 0) {
                pagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                return
            }
            var totalPages = Math.ceil(total / perPage)
            var btnBase = 'px-2 py-1 text-[0.72rem] font-semibold rounded-md border '
            var btnInactive = btnBase + 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer'
            var btnDisabled = btnBase + 'border-slate-200 text-slate-300 cursor-default'
            var btnActive = btnBase + 'bg-green-600 text-white border-green-600'
            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'
            html += '<button type="button" class="' + (currentPage === 1 ? btnDisabled : btnInactive) + '" data-page="prev"' + (currentPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            var windowStart = currentPage
            var windowEnd = Math.min(windowStart + visibleCount - 1, totalPages)
            for (var i = windowStart; i <= windowEnd; i++) {
                html += '<button type="button" class="' + (i === currentPage ? btnActive : btnInactive) + '" data-page="' + i + '">' + i + '</button>'
            }
            if (windowEnd < totalPages) {
                html += '<button type="button" class="' + btnInactive + '" data-page="next-window" title="Next set">…</button>'
            }
            html += '<button type="button" class="' + (currentPage === totalPages ? btnDisabled : btnInactive) + '" data-page="next"' + (currentPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            pagination.innerHTML = html

            pagination.querySelectorAll('button[data-page]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var page = btn.getAttribute('data-page')
                    if (page === 'prev' && currentPage > 1) showPage(currentPage - 1)
                    else if (page === 'next' && currentPage < totalPages) showPage(currentPage + 1)
                    else if (page === 'next-window') showPage(Math.min(windowEnd + 1, totalPages))
                    else if (page !== 'prev' && page !== 'next') showPage(parseInt(page, 10))
                })
            })
        }

        function loadPatients() {
            if (!patientsTableBody) return
            patientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading patients…</td></tr>'
            showInlineBox(patientsError, '')

            apiFetch(apiBaseUrl + "/patients?per_page=50", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: false, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        patientsRows = []
                        showInlineBox(patientsError, 'Failed to load patients.')
                        renderPatients()
                        return
                    }
                    patientsRows = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    renderPatients()
                })
                .catch(function () {
                    patientsRows = []
                    showInlineBox(patientsError, 'Failed to load patients.')
                    renderPatients()
                })
        }

        function renderPatients() {
            if (!patientsTableBody) return
            var query = patientsSearch ? String(patientsSearch.value || '').toLowerCase().trim() : ''
            var sortValue = sortSelect ? String(sortSelect.value || 'name_asc') : 'name_asc'
            var base = (patientsRows || []).slice()

            if (query) {
                base = base.filter(function (patient) {
                    var name = nameOnly(patient).toLowerCase()
                    return name !== '' && name.indexOf(query) === 0
                })
            }

            var counts = { all: 0, '1_5': 0, '6_12': 0, '13_18': 0, '19_30': 0, '31_up': 0 }
            base.forEach(function (patient) {
                var age = ageFromBirthdate(patient && patient.birthdate ? String(patient.birthdate) : null)
                counts.all++
                if (matchesAgeFilter(age, '1_5')) counts['1_5']++
                if (matchesAgeFilter(age, '6_12')) counts['6_12']++
                if (matchesAgeFilter(age, '13_18')) counts['13_18']++
                if (matchesAgeFilter(age, '19_30')) counts['19_30']++
                if (matchesAgeFilter(age, '31_up')) counts['31_up']++
            })

            setText(ageCountAll, counts.all)
            setText(ageCount1_5, counts['1_5'])
            setText(ageCount6_12, counts['6_12'])
            setText(ageCount13_18, counts['13_18'])
            setText(ageCount19_30, counts['19_30'])
            setText(ageCount31Up, counts['31_up'])

            var filtered = base.filter(function (patient) {
                var age = ageFromBirthdate(patient && patient.birthdate ? String(patient.birthdate) : null)
                return matchesAgeFilter(age, activeAgeFilter)
            })

            filtered.sort(function (a, b) {
                if (sortValue === 'created_asc' || sortValue === 'created_desc') {
                    var ta = a && a.created_at ? Date.parse(String(a.created_at)) : 0
                    var tb = b && b.created_at ? Date.parse(String(b.created_at)) : 0
                    if (isNaN(ta)) ta = 0
                    if (isNaN(tb)) tb = 0
                    if (ta < tb) return sortValue === 'created_asc' ? -1 : 1
                    if (ta > tb) return sortValue === 'created_asc' ? 1 : -1
                    return 0
                }

                var na = nameOnly(a).toLowerCase()
                var nb = nameOnly(b).toLowerCase()
                if (na < nb) return -1
                if (na > nb) return 1
                var ia = a && a.user_id != null ? parseInt(a.user_id, 10) : 0
                var ib = b && b.user_id != null ? parseInt(b.user_id, 10) : 0
                if (ia < ib) return -1
                if (ia > ib) return 1
                return 0
            })

            if (!filtered.length) {
                patientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">No patients found.</td></tr>'
                renderPagination()
                return
            }

            var html = ''
            filtered.forEach(function (patient) {
                var patientId = patient && patient.user_id != null ? String(patient.user_id) : ''
                var name = fullName(patient, 'Patient')
                var address = patient && patient.address ? String(patient.address) : ''
                var age = ageFromBirthdate(patient && patient.birthdate ? String(patient.birthdate) : null)
                var sex = patient && patient.sex ? String(patient.sex) : ''
                var verificationType = patient && patient.verification_type ? String(patient.verification_type) : ''
                var profileImg = patient && patient.prof_path_url ? String(patient.prof_path_url) : ''
                html += '<tr class="admin-pr-patient-row border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4">' +
                        (profileImg
                            ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-10 h-10 rounded-lg object-cover border border-slate-200">'
                            : '<div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
                        ) +
                    '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(name) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (address ? escapeHtml(address) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (age != null ? escapeHtml(age) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (sex ? escapeHtml(sex.charAt(0).toUpperCase() + sex.slice(1)) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (verificationType ? escapeHtml(verificationType.charAt(0).toUpperCase() + verificationType.slice(1)) : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4">' +
                        '<button type="button" class="admin-pr-open-panel inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.78rem] font-semibold hover:bg-slate-50" data-patient-id="' + escapeHtml(patientId) + '">View Details and History</button>' +
                    '</td>' +
                '</tr>'
            })
            patientsTableBody.innerHTML = html

            var totalPages = Math.ceil(filtered.length / perPage)
            if (currentPage > totalPages) currentPage = totalPages
            showPage(currentPage || 1)
        }

        function findPatientById(patientId) {
            var value = String(patientId || '')
            for (var i = 0; i < (patientsRows || []).length; i++) {
                if (patientsRows[i] && String(patientsRows[i].user_id) === value) return patientsRows[i]
            }
            return null
        }

        function resetPanelMetaFields() {
            if (panelVerificationStatus) panelVerificationStatus.textContent = '—'
            if (panelPatientType) panelPatientType.textContent = '—'
            if (panelVerificationId) panelVerificationId.textContent = '—'
        }

        function loadPatientPanelData(patientId) {
            currentPatientId = String(patientId || '')
            cachedMedBgRows = null
            cachedVisitRows = null
            cachedVitalRows = null
            cachedDependentRows = null
            activeDependentRecord = null
            activeDependentTab = 'background'
            activeDependentMedBgRows = null
            activeDependentVisitRows = null
            activeDependentVitalRows = null
            activeDependentVerification = null
            resetPanelMetaFields()
            if (currentPanelTab) renderTabDrawerContent(currentPanelTab)

            var medBgReq = apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=100&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var visitsReq = apiFetch(apiBaseUrl + "/visits?per_page=100&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var vitalsReq = apiFetch(apiBaseUrl + "/vitals?per_page=100&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var verificationReq = apiFetch(apiBaseUrl + "/patient-verifications?per_page=1&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var dependentsReq = apiFetch(apiBaseUrl + "/users/" + encodeURIComponent(currentPatientId) + "/dependents", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
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
                        if (panelVerificationStatus) panelVerificationStatus.textContent = '—'
                        if (panelPatientType) panelPatientType.textContent = '—'
                        if (panelVerificationId) panelVerificationId.textContent = '—'
                    } else {
                        var verRows = Array.isArray(verRes.data.data) ? verRes.data.data : (Array.isArray(verRes.data) ? verRes.data : [])
                        var latest = verRows && verRows.length ? verRows[0] : null
                        if (panelVerificationStatus) panelVerificationStatus.textContent = latest && latest.status ? String(latest.status) : 'Not submitted'
                        if (panelPatientType) panelPatientType.textContent = latest && latest.type ? String(latest.type) : '—'
                        if (panelVerificationId) {
                            var docUrl = latest && latest.document_url ? String(latest.document_url) : ''
                            if (docUrl) {
                                panelVerificationId.innerHTML = '<a href="' + docUrl.replace(/"/g, '&quot;') + '" target="_blank" class="text-green-700 underline hover:text-green-800">View ID</a>'
                            } else {
                                panelVerificationId.textContent = '—'
                            }
                        }
                    }

                    var dependentsRes = results[4]
                    cachedDependentRows = (!dependentsRes || !dependentsRes.ok || !dependentsRes.data)
                        ? []
                        : (Array.isArray(dependentsRes.data) ? dependentsRes.data : (Array.isArray(dependentsRes.data.data) ? dependentsRes.data.data : []))

                    if (currentPanelTab) renderTabDrawerContent(currentPanelTab)
                })
                .catch(function () {
                    if (String(patientId || '') !== currentPatientId) return
                    cachedMedBgRows = []
                    cachedVisitRows = []
                    cachedVitalRows = []
                    cachedDependentRows = []
                    if (currentPanelTab) renderTabDrawerContent(currentPanelTab)
                })
        }

        function populatePatientDetails(patient) {
            var address = patient && patient.address ? String(patient.address) : ''
            var age = ageFromBirthdate(patient && patient.birthdate ? String(patient.birthdate) : null)
            var contact = patient && patient.contact_number ? String(patient.contact_number) : ''
            var profileImg = patient && patient.prof_path_url ? String(patient.prof_path_url) : ''
            var value = function (input) { return (input != null && input !== '') ? String(input) : '—' }

            if (panelProfilePic) {
                panelProfilePic.innerHTML = profileImg
                    ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                    : defaultProfilePicHtml
            }

            if (prDetailFirstname) prDetailFirstname.textContent = value(patient && patient.firstname)
            if (prDetailMiddlename) prDetailMiddlename.textContent = value(patient && patient.middlename)
            if (prDetailLastname) prDetailLastname.textContent = value(patient && patient.lastname)
            if (prDetailBirthdate) {
                var birthdate = patient && patient.birthdate ? String(patient.birthdate) : ''
                prDetailBirthdate.textContent = birthdate ? birthdate.substring(0, 10) + (age != null ? ' (Age: ' + age + ')' : '') : '—'
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

        function searchAndRender() {
            currentPage = 1
            renderPatients()
        }

        if (patientsSearch) patientsSearch.addEventListener('input', searchAndRender)
        if (sortSelect) sortSelect.addEventListener('change', searchAndRender)

        setupPhoneFormat(patientEditContact)
        setupPhoneFormat(patientEditEmergencyContactNumber)

        if (patientEditNationalitySelect && patientEditNationality) {
            patientEditNationalitySelect.addEventListener('change', function () {
                if (this.value === '__others__') {
                    this.className = 'w-[30%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    patientEditNationality.className = 'w-[70%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    patientEditNationality.classList.remove('hidden')
                    patientEditNationality.focus()
                } else {
                    this.className = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    patientEditNationality.classList.add('hidden')
                    patientEditNationality.value = ''
                }
            })
        }

        if (patientEditPhilhealth) {
            patientEditPhilhealth.addEventListener('input', function () {
                var raw = this.value.replace(/[^\d]/g, '')
                if (raw.length > 12) raw = raw.slice(0, 12)
                this.value = formatPhilhealth(raw)
            })
        }

        if (patientEditProfileUpload) {
            patientEditProfileUpload.addEventListener('change', function () {
                var file = this.files && this.files[0]
                if (!file) return
                var reader = new FileReader()
                reader.onload = function (e) {
                    updatePatientProfilePreview(e.target.result)
                }
                reader.readAsDataURL(file)
            })
        }

        if (patientEditConfirmOk) {
            patientEditConfirmOk.addEventListener('click', function () { closePatientEditConfirm(true) })
        }
        if (patientEditConfirmCancel) {
            patientEditConfirmCancel.addEventListener('click', function () { closePatientEditConfirm(false) })
        }
        if (patientEditConfirmOverlay) {
            patientEditConfirmOverlay.addEventListener('click', function (e) {
                if (e.target === patientEditConfirmOverlay) closePatientEditConfirm(false)
            })
        }

        if (patientEditClose) patientEditClose.addEventListener('click', closePatientEditModal)
        if (patientEditCancel) patientEditCancel.addEventListener('click', closePatientEditModal)
        if (patientEditOverlay) {
            patientEditOverlay.addEventListener('click', function (e) {
                if (e.target === patientEditOverlay) closePatientEditModal()
            })
        }

        if (panelEditInfoBtn) {
            panelEditInfoBtn.addEventListener('click', function () {
                if (!currentPatientId) return
                var patient = findPatientById(currentPatientId)
                if (!patient) return
                openPatientEditModal(patient)
            })
        }

        if (patientEditForm) {
            patientEditForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (!editingPatientId) return
                if (patientEditSave && patientEditSave.disabled) return

                showPatientEditError('')

                var firstname = patientEditFirstname ? String(patientEditFirstname.value || '').trim() : ''
                var middlename = patientEditMiddlename ? String(patientEditMiddlename.value || '').trim() : ''
                var lastname = patientEditLastname ? String(patientEditLastname.value || '').trim() : ''
                var contact = patientEditContact ? String(patientEditContact.value || '').trim() : ''

                if (!isValidName(firstname) || !isValidName(middlename) || !isValidName(lastname)) {
                    showPatientEditError('Name fields must contain letters only.')
                    return
                }
                if (contact && contact !== '+63' && !isValidPhilippinesNumber(contact)) {
                    showPatientEditError('Contact number must be a valid PH number starting with +63 and 10 digits.')
                    return
                }

                confirmPatientEditAction('Are you sure you want to save these changes?')
                    .then(function (confirmed) {
                        if (!confirmed) return

                        setPatientEditSubmitting(true)

                        var formData = new FormData()
                        formData.append('_method', 'PUT')

                        function val(el) { return el ? String(el.value || '').trim() : '' }
                        function appendIf(key, value) {
                            if (value !== null && value !== undefined && value !== '') formData.append(key, value)
                        }

                        appendIf('firstname', val(patientEditFirstname))
                        formData.append('middlename', val(patientEditMiddlename) || '')
                        appendIf('lastname', val(patientEditLastname))
                        var sexVal = patientEditSexMale && patientEditSexMale.checked ? 'Male' : (patientEditSexFemale && patientEditSexFemale.checked ? 'Female' : null)
                        appendIf('sex', sexVal)
                        appendIf('birthdate', val(patientEditBirthdate))
                        appendIf('civil_status', val(patientEditCivilStatus))
                        var nationalityVal = ''
                        if (patientEditNationalitySelect) {
                            if (patientEditNationalitySelect.value === '__others__') {
                                nationalityVal = patientEditNationality ? String(patientEditNationality.value || '').trim() : ''
                            } else {
                                nationalityVal = patientEditNationalitySelect.value
                            }
                        }
                        appendIf('nationality', nationalityVal)
                        appendIf('address', val(patientEditAddress))
                        appendIf('occupation', val(patientEditOccupation))
                        appendIf('emergency_contact', val(patientEditEmergencyContact))
                        var emergencyContactNumber = val(patientEditEmergencyContactNumber)
                        appendIf('emergency_contact_number', emergencyContactNumber ? parsePhoneRaw(emergencyContactNumber) : null)
                        var philhealthRaw = val(patientEditPhilhealth).replace(/[^\d]/g, '')
                        appendIf('philhealth_number', philhealthRaw)
                        var contactRaw = val(patientEditContact)
                        appendIf('contact_number', contactRaw ? parsePhoneRaw(contactRaw) : null)

                        if (patientEditProfileUpload && patientEditProfileUpload.files && patientEditProfileUpload.files[0]) {
                            formData.append('prof_path', patientEditProfileUpload.files[0])
                        }

                        apiFetch(apiBaseUrl + '/patients/' + encodeURIComponent(editingPatientId), {
                            method: 'POST',
                            body: formData
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
                                    if (result.status === 422 && result.data && result.data.errors) {
                                        var firstKey = Object.keys(result.data.errors)[0]
                                        var message = firstKey && result.data.errors[firstKey] && result.data.errors[firstKey][0]
                                            ? result.data.errors[firstKey][0]
                                            : 'Validation error.'
                                        showPatientEditError(String(message))
                                    } else {
                                        var fallback = result.data && result.data.message ? result.data.message : 'Failed to update patient.'
                                        showPatientEditError(String(fallback))
                                    }
                                    return
                                }

                                mergePatientRecord(result.data || {})
                                closePatientEditModal()
                                showPatientEditSuccess('Changes saved.')
                            })
                            .catch(function () {
                                showPatientEditError('Network error while updating patient.')
                            })
                            .finally(function () {
                                setPatientEditSubmitting(false)
                            })
                    })
                    .catch(function () {})
            })
        }

        if (ageFilterButtons.length) {
            ageFilterButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    activeAgeFilter = this.getAttribute('data-age-filter') || 'all'
                    setAgeFilterActiveStyles()
                    currentPage = 1
                    renderPatients()
                })
            })
        }

        if (patientsTableBody) {
            patientsTableBody.addEventListener('click', function (event) {
                var target = event && event.target ? event.target : null
                var btn = target && target.closest ? target.closest('.admin-pr-open-panel') : null
                if (!btn) return

                var patientId = btn.getAttribute('data-patient-id')
                if (!patientId) return

                var patient = findPatientById(patientId)
                closeTabDrawer()
                populatePatientDetails(patient)
                openPanel()
                loadPatientPanelData(patientId)
            })
        }

        if (panelClose) panelClose.addEventListener('click', closePanel)
        if (tabDrawerClose) tabDrawerClose.addEventListener('click', closeTabDrawer)
        if (overlay) overlay.addEventListener('click', closePanel)

        if (panelTabBackground) panelTabBackground.addEventListener('click', function () { setPanelTab('background') })
        if (panelTabVisits) panelTabVisits.addEventListener('click', function () { setPanelTab('visits') })
        if (panelTabVitals) panelTabVitals.addEventListener('click', function () { setPanelTab('vitals') })
        if (panelTabDependents) panelTabDependents.addEventListener('click', function () { setPanelTab('dependents') })

        setAgeFilterActiveStyles()
        syncTabButtonState()
        closePanel()
        loadPatients()

        var prRefreshBtn = document.getElementById('adminPrRefreshBtn')
        if (prRefreshBtn) {
            prRefreshBtn.addEventListener('click', function () { loadPatients() })
        }
    })
</script>
