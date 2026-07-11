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
                <option value="visit_asc">Last Visit ASC</option>
                <option value="visit_desc">Last Visit DESC</option>
            </select>
        </div>
        <div class="w-full md:w-28 pt-1">
            <button type="button" id="adminPrRefreshBtn" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
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
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="0_5">
                Infants/Toddlers&nbsp;(0–5)
                <span id="adminPrAgeCount0_5" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="6_12">
                School Age&nbsp;(6–12)
                <span id="adminPrAgeCount6_12" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="13_19">
                Adolescents&nbsp;(13–19)
                <span id="adminPrAgeCount13_19" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="20_64">
                Adults&nbsp;(20–64)
                <span id="adminPrAgeCount20_64" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
            </button>
            <button type="button" class="admin-pr-age-filter px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-[0.72rem] font-semibold" data-age-filter="65_up">
                Senior Citizens&nbsp;(65+)
                <span id="adminPrAgeCount65Up" class="ml-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[0.68rem] font-semibold text-slate-700">0</span>
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

<div id="adminPrViewOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-4xl max-h-[90vh] rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div>
                <div class="text-sm font-semibold text-slate-900">Patient Details</div>
                <div id="adminPrViewSubtitle" class="text-[0.72rem] text-slate-500">View patient profile information.</div>
            </div>
            <button type="button" id="adminPrViewClose" class="text-slate-400 hover:text-slate-600">
                <x-lucide-x class="w-[20px] h-[20px]" />
            </button>
        </div>

        <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-1.5 overflow-x-auto scrollbar-hidden shrink-0">
            <button type="button" class="admin-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-green-600 bg-green-600 text-white" data-view-tab="profile">Profile Info</button>
            <button type="button" class="admin-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="verification">Type &amp; Verification</button>
            <button type="button" class="admin-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="background">Medical Background</button>
            <button type="button" class="admin-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="visits">Visit History</button>
            <button type="button" class="admin-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="vitals">Vitals History</button>
            <button type="button" id="adminPrViewTabDependentsBtn" class="admin-pr-view-tab px-3 py-1.5 rounded-xl text-[0.75rem] font-semibold border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" data-view-tab="dependents">Dependents</button>
        </div>

        <div id="adminPrViewBody" class="p-5 overflow-y-auto flex-1">
            {{-- Profile Info Tab --}}
            <div id="adminPrViewTabProfile" class="admin-pr-view-tab-content min-h-[420px]">
                {{-- Edit mode toggle --}}
                <div class="flex gap-2 mb-4">
                    <button type="button" id="adminPrViewEditBtn" class="inline-flex items-center gap-1 text-[0.78rem] font-semibold text-green-700 hover:text-green-800 transition-colors">
                        Edit Info
                    </button>
                </div>

                {{-- ===== DISPLAY MODE ===== --}}
                <div id="adminPrViewProfileDisplay">
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
                                    <div id="adminPrViewProfilePic">
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
                <div id="adminPrViewProfileEdit" class="hidden">
                    <div id="adminPrViewEditError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                    <form id="adminPrViewEditForm" class="grid grid-cols-1 md:grid-cols-5 gap-5">
                        <div class="md:col-span-3 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label for="adminPrViewEditLastname" class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                                    <input id="adminPrViewEditLastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="adminPrViewEditFirstname" class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                                    <input id="adminPrViewEditFirstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="adminPrViewEditMiddlename" class="block text-[0.7rem] text-slate-600 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                                    <input id="adminPrViewEditMiddlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                                    <div class="flex items-center gap-4 pt-1">
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="radio" name="adminPrViewEditSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                        </label>
                                        <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                            <input type="radio" name="adminPrViewEditSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label for="adminPrViewEditBirthdate" class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                                    <input id="adminPrViewEditBirthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="adminPrViewEditCivilStatus" class="block text-[0.7rem] text-slate-600 mb-1">Civil status</label>
                                    <select id="adminPrViewEditCivilStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
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
                                    <label for="adminPrViewEditNationalitySelect" class="block text-[0.7rem] text-slate-600 mb-1">Nationality</label>
                                    <div id="adminPrViewEditNationalityField" class="flex gap-2">
                                        <select id="adminPrViewEditNationalitySelect" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                            <option value="">None</option>
                                            <option value="Filipino">Filipino</option>
                                            <option value="__others__">Other/s specify</option>
                                        </select>
                                        <input id="adminPrViewEditNationality" type="text" class="w-0 hidden rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Please specify">
                                    </div>
                                </div>
                                <div>
                                    <label for="adminPrViewEditOccupation" class="block text-[0.7rem] text-slate-600 mb-1">Occupation</label>
                                    <input id="adminPrViewEditOccupation" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                            </div>
                            <div>
                                <label for="adminPrViewEditAddress" class="block text-[0.7rem] text-slate-600 mb-1">Address</label>
                                <textarea id="adminPrViewEditAddress" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none resize-y" placeholder="Street, barangay, municipality"></textarea>
                            </div>
                            <hr class="border-slate-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="adminPrViewEditPhilhealth" class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                                    <input id="adminPrViewEditPhilhealth" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                                </div>
                                <div>
                                    <label for="adminPrViewEditEmergencyContact" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact</label>
                                    <input id="adminPrViewEditEmergencyContact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                            </div>
                            <div>
                                <label for="adminPrViewEditEmergencyContactNumber" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                                <input id="adminPrViewEditEmergencyContactNumber" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                                <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                                <div id="adminPrViewEditProfilePreview" class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <div class="mt-3">
                                    <label for="adminPrViewEditProfileUpload" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-green-200 bg-green-50 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Upload photo
                                    </label>
                                    <input id="adminPrViewEditProfileUpload" type="file" accept="image/*" class="hidden">
                                </div>
                                <div class="mt-4 text-left">
                                    <label for="adminPrViewEditContact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                                    <input id="adminPrViewEditContact" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" id="adminPrViewEditCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                            <button type="submit" id="adminPrViewEditSave" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                                <span id="adminPrViewEditSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                                <span id="adminPrViewEditSaveLabel">Save changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Type & Verification Tab --}}
            <div id="adminPrViewTabVerification" class="hidden admin-pr-view-tab-content min-h-[420px]">
                <div class="space-y-3">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Verification status</div>
                        <div id="adminPrViewVerificationStatus" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Patient type</div>
                        <div id="adminPrViewPatientType" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                        <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Verification ID</div>
                        <div id="adminPrViewVerificationId" class="text-[0.8rem] font-semibold text-slate-800 mt-1">-</div>
                    </div>
                </div>
            </div>

            {{-- Medical Background Tab --}}
            <div id="adminPrViewTabBackground" class="hidden admin-pr-view-tab-content min-h-[420px]"></div>
            {{-- Visit History Tab --}}
            <div id="adminPrViewTabVisits" class="hidden admin-pr-view-tab-content min-h-[420px]"></div>
            {{-- Vitals History Tab --}}
            <div id="adminPrViewTabVitals" class="hidden admin-pr-view-tab-content min-h-[420px]"></div>
            {{-- Dependents Tab --}}
            <div id="adminPrViewTabDependents" class="hidden admin-pr-view-tab-content min-h-[420px]"></div>
        </div>
    </div>
</div>

{{-- Visit Details Modal --}}
<div id="adminVisitDetailOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <div class="text-sm font-semibold text-slate-900">Visit Details</div>
                <div id="adminVisitDetailSubtitle" class="text-[0.72rem] text-slate-500">Appointment and clinical information.</div>
            </div>
            <button type="button" id="adminVisitDetailClose" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-5 space-y-4" id="adminVisitDetailBody">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Appointment date</div>
                    <div id="adminVisitDetailDate" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Doctor</div>
                    <div id="adminVisitDetailDoctor" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Services inquired</div>
                <div id="adminVisitDetailServices" class="text-[0.8rem] text-slate-700 mt-1">-</div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Fees</div>
                    <div id="adminVisitDetailFees" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Payment status</div>
                    <div id="adminVisitDetailPayment" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Status</div>
                    <div id="adminVisitDetailStatus" class="mt-1">-</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                    <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Appointment type</div>
                    <div id="adminVisitDetailApptType" class="text-[0.82rem] font-semibold text-slate-800 mt-1">-</div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Diagnosis</div>
                <div id="adminVisitDetailDiagnosis" class="text-[0.8rem] text-slate-700 mt-1">-</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Treatment notes</div>
                <div id="adminVisitDetailTreatment" class="text-[0.8rem] text-slate-700 mt-1">-</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">
                <div class="text-[0.65rem] uppercase tracking-widest text-slate-400">Prescriptions</div>
                <div id="adminVisitDetailPrescriptions" class="text-[0.8rem] text-slate-700 mt-1">-</div>
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

{{-- ===== GENERATE PATIENT RECORDS REPORT MODAL ===== --}}
<div id="adminPrReportModal" class="hidden fixed inset-0 z-[90] bg-slate-950/45 backdrop-blur-sm p-4 sm:p-6">
    <div class="min-h-full flex items-center justify-center">
        <div id="adminPrReportModalCard" class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.22)] transition-all duration-200">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                <div>
                    <h3 id="adminPrReportModalTitle" class="text-sm font-semibold text-slate-900">Generate patient medical record</h3>
                    <p id="adminPrReportModalSubtitle" class="mt-1 text-[0.78rem] text-slate-500">Choose a single date or a custom date range, then generate a report preview inside this window.</p>
                </div>
                <button type="button" id="adminPrReportModalCloseBtn" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700">
                    <x-lucide-x class="w-4 h-4" />
                </button>
            </div>

            <div id="adminPrReportModalForm" class="px-5 py-4 space-y-4">
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient</div>
                    <div id="adminPrReportPatientName" class="text-[0.82rem] font-semibold text-slate-800 mt-0.5">-</div>
                </div>

                <div id="adminPrReportFormFields">
                    <label for="adminPrReportType" class="block text-[0.72rem] text-slate-600 mb-1">Report type</label>
                    <select id="adminPrReportType" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        <option value="date">Single date</option>
                        <option value="range">Date range</option>
                    </select>
                </div>

                <div id="adminPrReportSingleDateWrap">
                    <label for="adminPrReportDate" class="block text-[0.72rem] text-slate-600 mb-1">Date</label>
                    <input id="adminPrReportDate" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>

                <div id="adminPrReportRangeWrap" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="adminPrReportStartDate" class="block text-[0.72rem] text-slate-600 mb-1">Starting date</label>
                        <input id="adminPrReportStartDate" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <div>
                        <label for="adminPrReportEndDate" class="block text-[0.72rem] text-slate-600 mb-1">End date</label>
                        <input id="adminPrReportEndDate" type="date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                </div>

                <div id="adminPrReportFeedback" class="hidden rounded-2xl border px-3 py-2 text-[0.78rem]"></div>
            </div>

            <div id="adminPrReportPreviewWrap" class="hidden px-5 py-4">
                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50">
                    <iframe id="adminPrReportPreviewFrame" title="Patient medical record preview" class="block w-full h-[68vh] bg-white"></iframe>
                </div>
            </div>

            <div id="adminPrReportInitialActions" class="flex items-center justify-end gap-2 border-t border-slate-100 px-5 py-4">
                <button type="button" id="adminPrReportCancelBtn" class="px-3 py-2 rounded-xl border border-slate-200 text-[0.78rem] font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="button" id="adminPrReportSubmitBtn" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Generate Report</button>
            </div>

            <div id="adminPrReportPreviewActions" class="hidden items-center justify-between gap-2 border-t border-slate-100 px-5 py-4">
                <button type="button" id="adminPrReportResetBtn" class="px-3 py-2 rounded-xl border border-slate-200 text-[0.78rem] font-semibold text-slate-600 hover:bg-slate-50">Generate Another Report</button>
                <div class="flex items-center gap-2">
                    <button type="button" id="adminPrReportPreviewCloseBtn" class="px-3 py-2 rounded-xl border border-slate-200 text-[0.78rem] font-semibold text-slate-600 hover:bg-slate-50">Close</button>
                    <button type="button" id="adminPrReportPrintBtn" class="px-3 py-2 rounded-xl bg-green-700 text-white text-[0.78rem] font-semibold hover:bg-green-800">Download / Print</button>
                </div>
            </div>
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
        var patientRows = []
        var patientMeta = { current_page: 1, last_page: 1, total: 0, per_page: 15 }
        var patientAgeCounts = null
        var currentPage = 1
        var visibleCount = 6

        var activeAgeFilter = 'all'
        var ageFilterButtons = Array.prototype.slice.call(document.querySelectorAll('.admin-pr-age-filter'))
        var ageCountAll = document.getElementById('adminPrAgeCountAll')
        var ageCount0_5 = document.getElementById('adminPrAgeCount0_5')
        var ageCount6_12 = document.getElementById('adminPrAgeCount6_12')
        var ageCount13_19 = document.getElementById('adminPrAgeCount13_19')
        var ageCount20_64 = document.getElementById('adminPrAgeCount20_64')
        var ageCount65Up = document.getElementById('adminPrAgeCount65Up')

        var viewOverlay = document.getElementById('adminPrViewOverlay')
        var viewClose = document.getElementById('adminPrViewClose')
        var viewProfilePic = document.getElementById('adminPrViewProfilePic')
        var viewEditBtn = document.getElementById('adminPrViewEditBtn')
        var viewTabButtons = Array.prototype.slice.call(document.querySelectorAll('.admin-pr-view-tab'))
        var viewTabContents = {}
        document.querySelectorAll('.admin-pr-view-tab-content').forEach(function (el) {
            var id = el.getAttribute('id') || ''
            var key = id.replace('adminPrViewTab', '').toLowerCase()
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
        var viewVerificationStatus = document.getElementById('adminPrViewVerificationStatus')
        var viewPatientType = document.getElementById('adminPrViewPatientType')
        var viewVerificationId = document.getElementById('adminPrViewVerificationId')

        var medBgEditingId = null
        var currentViewTab = 'profile'

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
        var cachedParentData = null
        var currentPatientId = null
        var activeDependentRecord = null
        var activeDependentTab = 'background'
        var activeDependentMedBgRows = null
        var activeDependentVisitRows = null
        var activeDependentVitalRows = null
        var activeDependentVerification = null

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
            return k || '-'
        }

        function buildCategoryOptions(selected) {
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
                html += '<option value="' + escapeHtml(o.value) + '"' + selectedAttr + '>' + escapeHtml(o.label) + '</option>'
            })
            return html
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
            if (filterKey === '0_5') return age >= 0 && age <= 5
            if (filterKey === '6_12') return age >= 6 && age <= 12
            if (filterKey === '13_19') return age >= 13 && age <= 19
            if (filterKey === '20_64') return age >= 20 && age <= 64
            if (filterKey === '65_up') return age >= 65
            return true
        }

        function displayValue(value) {
            return (value != null && value !== '') ? String(value) : '-'
        }

        function sexLabel(value) {
            var text = displayValue(value)
            if (text === '-') return text
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
                    var statusColors = { pending:'bg-amber-50 text-amber-700 border-amber-200', confirmed:'border-orange-200 bg-orange-50 text-orange-700', completed:'border-green-200 bg-green-50 text-green-700', cancelled:'bg-red-50 text-red-700 border-red-200', no_show:'bg-slate-100 text-slate-600 border-slate-200', consulted:'border-purple-200 bg-purple-50 text-purple-700', waiting:'bg-amber-50 text-amber-700 border-amber-100', serving:'bg-blue-50 text-blue-700 border-blue-100', done:'bg-emerald-50 text-emerald-700 border-emerald-100', skipped:'bg-orange-50 text-orange-700 border-orange-100', on_hold:'bg-purple-50 text-purple-700 border-purple-100' }
                    var statusClass = statusColors[apptStatus] || 'bg-slate-50 text-slate-600 border-slate-100'
                    var statusLabel = apptStatus ? apptStatus.charAt(0).toUpperCase() + apptStatus.slice(1).replace(/_/g, ' ') : '-'
                    rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(fullName(doctor, 'Doctor')) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(dateText) + '</td>' +
                        '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(formatCurrency(visit && visit.amount != null ? visit.amount : '')) + '</td>' +
                        '<td class="py-2 pr-4"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-medium border ' + statusClass + '">' + escapeHtml(statusLabel) + '</span></td>' +
                        '<td class="py-2 pr-4"><button type="button" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[0.7rem] font-semibold text-slate-600 hover:bg-slate-50 hover:border-slate-300 admin-visit-detail-btn" data-visit=\'' + escapeHtml(JSON.stringify(visit).replace(/'/g, '&#39;')) + '\'>Details</button></td>' +
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
                var depBtn = document.getElementById('adminPrViewTabDependentsBtn')
                if (depBtn) depBtn.textContent = isDependent ? 'Parent/Guardian' : 'Dependents'

                if (window._adminShowingDependentProfile) {
                    renderAdminDependentProfileInline(container)
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
                        fetchAdminParentData(parentId)
                        return
                    }
                    renderAdminParentOrDependentCards(container, [cachedParentData], 'parent')
                } else {
                    if (cachedDependentRows == null) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading dependents...</div></div>'
                        return
                    }
                    if (!cachedDependentRows.length) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">No dependents found for this patient.</div></div>'
                        return
                    }
                    renderAdminParentOrDependentCards(container, cachedDependentRows, 'dependent')
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

        function openViewModal() {
            medBgEditingId = null
            if (viewOverlay) {
                viewOverlay.classList.remove('hidden')
                viewOverlay.classList.add('flex')
            }
            var patient = currentPatientId ? findPatientById(currentPatientId) : null
            var depBtn = document.getElementById('adminPrViewTabDependentsBtn')
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
            window._adminShowingDependentProfile = false
            window._adminDependentProfileId = null
            var depBtn = document.getElementById('adminPrViewTabDependentsBtn')
            if (depBtn) depBtn.textContent = 'Dependents'
            // Reset to display mode
            viewEditModeToggle(false)
            if (viewOverlay) {
                viewOverlay.classList.add('hidden')
                viewOverlay.classList.remove('flex')
            }
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
            if (patientEditSubtitle) patientEditSubtitle.textContent = 'Editing - ' + fullName
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
            for (var i = 0; i < patientRows.length; i++) {
                if (patientRows[i] && String(patientRows[i].user_id) === updatedId) {
                    merged = Object.assign({}, patientRows[i], updatedPatient)
                    patientRows[i] = merged
                    found = true
                    break
                }
            }
            if (!found) {
                patientRows.push(updatedPatient)
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
                var diagnosisDate = row && row.diagnosis_date ? String(row.diagnosis_date).replace('T', ' ').slice(0, 16) : ''
                var procedureDate = row && row.procedure_date ? String(row.procedure_date) : ''
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(categoryLabel(row.category)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(row && row.name ? String(row.name) : '-') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosisDate ? escapeHtml(diagnosisDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (procedureDate ? escapeHtml(procedureDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (row && row.notes ? escapeHtml(String(row.notes)) : '<span class="text-slate-400">-</span>') + '</td>' +
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
                var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '-'
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
                            '<div class="text-[0.76rem] text-slate-500">Age: <span class="text-slate-700">' + escapeHtml(age == null ? '-' : String(age)) + '</span></div>' +
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
            var patientType = activeDependentVerification && activeDependentVerification.type ? String(activeDependentVerification.type) : '-'
            var verificationHtml = '-'
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
                                '<div><span class="text-slate-500">Date Of Birth:</span> <span class="text-slate-800 ml-1">' + escapeHtml(birthdate ? birthdate.substring(0, 10) + (age != null ? ' (Age: ' + age + ')' : '') : '-') + '</span></div>' +
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
                var diagnosisDate = row && row.diagnosis_date ? String(row.diagnosis_date).replace('T', ' ').slice(0, 16) : ''
                var procedureDate = row && row.procedure_date ? String(row.procedure_date) : ''
                rowsHtml += '<tr class="border-b border-slate-50 last:border-0">' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + escapeHtml(categoryLabel(row.category)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' + escapeHtml(displayValue(row && row.name)) + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (diagnosisDate ? escapeHtml(diagnosisDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (procedureDate ? escapeHtml(procedureDate) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (row && row.notes ? escapeHtml(String(row.notes)) : '<span class="text-slate-400">-</span>') + '</td>' +
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
                var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '-'
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
            var medBgReq = apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=15&patient_id=" + encodeURIComponent(depId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var visitsReq = apiFetch(apiBaseUrl + "/visits?per_page=15&patient_id=" + encodeURIComponent(depId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var vitalsReq = apiFetch(apiBaseUrl + "/vitals?per_page=15&patient_id=" + encodeURIComponent(depId), { method: 'GET' })
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

        function renderPagination() {
            if (!pagination) return
            var total = patientMeta.total
            var totalPages = patientMeta.last_page
            if (total === 0) {
                pagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                return
            }
            currentPage = patientMeta.current_page
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
                    if (page === 'prev' && currentPage > 1) loadPatients(currentPage - 1)
                    else if (page === 'next' && currentPage < totalPages) loadPatients(currentPage + 1)
                    else if (page === 'next-window') {
                        var nextStart = Math.min(windowEnd + 1, totalPages)
                        loadPatients(nextStart)
                    }
                    else if (page !== 'prev' && page !== 'next') loadPatients(parseInt(page, 10))
                })
            })
        }

        function loadPatients(page) {
            if (!patientsTableBody) return
            patientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">Loading patients…</td></tr>'
            showInlineBox(patientsError, '')
            page = page || 1

            var query = patientsSearch ? String(patientsSearch.value || '').trim() : ''
            var sortValue = sortSelect ? String(sortSelect.value || 'visit_asc') : 'visit_asc'
            var params = 'per_page=10&include_counts=1&page=' + page
            if (query) params += '&search=' + encodeURIComponent(query)
            if (activeAgeFilter !== 'all') params += '&age_filter=' + encodeURIComponent(activeAgeFilter)
            params += '&order_by=' + encodeURIComponent(sortValue)
            apiFetch(apiBaseUrl + "/patients?" + params, { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: false, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        patientRows = []
                        patientMeta = { current_page: 1, last_page: 1, total: 0, per_page: 10 }
                        patientAgeCounts = null
                        showInlineBox(patientsError, 'Failed to load patients.')
                        renderPatients()
                        return
                    }
                    patientRows = Array.isArray(result.data.data) ? result.data.data : (Array.isArray(result.data) ? result.data : [])
                    patientMeta = {
                        current_page: result.data.current_page || 1,
                        last_page: result.data.last_page || 1,
                        total: result.data.total || 0,
                        per_page: result.data.per_page || 10
                    }
                    patientAgeCounts = result.data.age_counts || null
                    renderPatients()
                })
                .catch(function () {
                    patientRows = []
                    patientMeta = { current_page: 1, last_page: 1, total: 0, per_page: 10 }
                    patientAgeCounts = null
                    showInlineBox(patientsError, 'Failed to load patients.')
                    renderPatients()
                })
        }

        function renderPatients() {
            if (!patientsTableBody) return
            var base = (patientRows || []).slice()

            // Update age counters from API response (covers all patients, not just current page)
            var counts = patientAgeCounts || { all: 0, '0_5': 0, '6_12': 0, '13_19': 0, '20_64': 0, '65_up': 0 }
            // Fallback to computing from current page if API counts not available
            if (!patientAgeCounts) {
                base.forEach(function (p) {
                    var a = ageFromBirthdate(p && p.birthdate ? String(p.birthdate) : null)
                    counts.all++
                    if (a == null) return
                    if (a >= 0 && a <= 5) counts['0_5']++
                    else if (a >= 6 && a <= 12) counts['6_12']++
                    else if (a >= 13 && a <= 19) counts['13_19']++
                    else if (a >= 20 && a <= 64) counts['20_64']++
                    else if (a >= 65) counts['65_up']++
                })
            }
            var countAllEl = document.getElementById('adminPrAgeCountAll')
            var count0_5El = document.getElementById('adminPrAgeCount0_5')
            var count6_12El = document.getElementById('adminPrAgeCount6_12')
            var count13_19El = document.getElementById('adminPrAgeCount13_19')
            var count20_64El = document.getElementById('adminPrAgeCount20_64')
            var count65UpEl = document.getElementById('adminPrAgeCount65Up')
            if (countAllEl) countAllEl.textContent = String(counts.all)
            if (count0_5El) count0_5El.textContent = String(counts['0_5'])
            if (count6_12El) count6_12El.textContent = String(counts['6_12'])
            if (count13_19El) count13_19El.textContent = String(counts['13_19'])
            if (count20_64El) count20_64El.textContent = String(counts['20_64'])
            if (count65UpEl) count65UpEl.textContent = String(counts['65_up'])

            if (!base.length) {
                patientsTableBody.innerHTML = '<tr><td colspan="7" class="py-4 text-center text-[0.78rem] text-slate-400">No patients found.</td></tr>'
                renderPagination()
                return
            }

            var html = ''
            base.forEach(function (patient) {
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
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (address ? escapeHtml(address) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (age != null ? escapeHtml(age) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (sex ? escapeHtml(sex.charAt(0).toUpperCase() + sex.slice(1)) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (verificationType ? escapeHtml(verificationType.charAt(0).toUpperCase() + verificationType.slice(1)) : '<span class="text-slate-400">-</span>') + '</td>' +
                    '<td class="py-2 pr-4">' +
                        '<button type="button" class="admin-pr-open-panel inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-[0.78rem] font-semibold hover:bg-slate-50" data-patient-id="' + escapeHtml(patientId) + '">View Details and History</button>' +
                        '<button type="button" class="admin-pr-generate-records ml-2 inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-green-200 bg-green-50 text-green-700 text-[0.72rem] font-semibold hover:bg-green-100" data-patient-id="' + escapeHtml(patientId) + '">Generate Records</button>' +
                    '</td>' +
                '</tr>'
            })
            patientsTableBody.innerHTML = html
            renderPagination()
        }

        function findPatientById(patientId) {
            var value = String(patientId || '')
            for (var i = 0; i < (patientRows || []).length; i++) {
                if (patientRows[i] && String(patientRows[i].user_id) === value) return patientRows[i]
            }
            return null
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

            var medBgReq = apiFetch(apiBaseUrl + "/medical-backgrounds?per_page=15&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var visitsReq = apiFetch(apiBaseUrl + "/visits?per_page=15&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })

            var vitalsReq = apiFetch(apiBaseUrl + "/vitals?per_page=15&patient_id=" + encodeURIComponent(currentPatientId), { method: 'GET' })
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
                        // Only show verification details when verified/approved
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

                    // Re-render current view tab if it's a data-driven tab
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

        function searchAndRender() {
            currentPage = 1
            loadPatients(1)
        }

        if (patientsSearch) patientsSearch.addEventListener('input', searchAndRender)
        if (sortSelect) sortSelect.addEventListener('change', searchAndRender)

        setupPhoneFormat(patientEditContact)
        setupPhoneFormat(patientEditEmergencyContactNumber)

        // View edit form - phone formatting
        var viewEditContact = document.getElementById('adminPrViewEditContact')
        var viewEditEmergNumber = document.getElementById('adminPrViewEditEmergencyContactNumber')
        setupPhoneFormat(viewEditContact)
        setupPhoneFormat(viewEditEmergNumber)

        // View edit form - nationality select
        var viewEditNationalitySelect = document.getElementById('adminPrViewEditNationalitySelect')
        var viewEditNationality = document.getElementById('adminPrViewEditNationality')
        if (viewEditNationalitySelect && viewEditNationality) {
            viewEditNationalitySelect.addEventListener('change', function () {
                if (this.value === '__others__') {
                    this.className = 'w-[30%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    viewEditNationality.className = 'w-[70%] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    viewEditNationality.classList.remove('hidden')
                    viewEditNationality.focus()
                } else {
                    this.className = 'w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none'
                    viewEditNationality.classList.add('hidden')
                    viewEditNationality.value = ''
                }
            })
        }

        // View edit form - philhealth formatting
        var viewEditPhilhealth = document.getElementById('adminPrViewEditPhilhealth')
        if (viewEditPhilhealth) {
            viewEditPhilhealth.addEventListener('input', function () {
                var raw = this.value.replace(/[^\d]/g, '')
                if (raw.length > 12) raw = raw.slice(0, 12)
                this.value = formatPhilhealth(raw)
            })
        }

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

        function populateViewEditForm(patient) {
            if (!patient) return
            var ev = function (input) { return (input != null && input !== '') ? String(input) : '' }
            var editLastname = document.getElementById('adminPrViewEditLastname')
            var editFirstname = document.getElementById('adminPrViewEditFirstname')
            var editMiddlename = document.getElementById('adminPrViewEditMiddlename')
            var editBirthdate = document.getElementById('adminPrViewEditBirthdate')
            var editCivilStatus = document.getElementById('adminPrViewEditCivilStatus')
            var editNationalitySelect = document.getElementById('adminPrViewEditNationalitySelect')
            var editNationality = document.getElementById('adminPrViewEditNationality')
            var editOccupation = document.getElementById('adminPrViewEditOccupation')
            var editAddress = document.getElementById('adminPrViewEditAddress')
            var editPhilhealth = document.getElementById('adminPrViewEditPhilhealth')
            var editEmergencyContact = document.getElementById('adminPrViewEditEmergencyContact')
            var editEmergencyContactNumber = document.getElementById('adminPrViewEditEmergencyContactNumber')
            var editContact = document.getElementById('adminPrViewEditContact')
            var editProfilePreview = document.getElementById('adminPrViewEditProfilePreview')
            var editProfileUpload = document.getElementById('adminPrViewEditProfileUpload')

            if (editLastname) editLastname.value = ev(patient && patient.lastname)
            if (editFirstname) editFirstname.value = ev(patient && patient.firstname)
            if (editMiddlename) editMiddlename.value = ev(patient && patient.middlename)
            if (editBirthdate) editBirthdate.value = patient && patient.birthdate ? String(patient.birthdate).substring(0, 10) : ''
            if (editCivilStatus) editCivilStatus.value = patient && patient.civil_status ? String(patient.civil_status) : ''
            // Sex radio
            var sexRadios = document.querySelectorAll('input[name="adminPrViewEditSex"]')
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

            // Profile photo preview
            var profileImg = patient && patient.prof_path_url ? String(patient.prof_path_url) : ''
            if (editProfilePreview) {
                editProfilePreview.innerHTML = profileImg
                    ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
            }

            // Re-attach file upload listener
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
            var display = document.getElementById('adminPrViewProfileDisplay')
            var edit = document.getElementById('adminPrViewProfileEdit')
            var editBtn = document.getElementById('adminPrViewEditBtn')
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

        if (viewEditBtn) {
            viewEditBtn.addEventListener('click', function () {
                if (!currentPatientId) return
                var isEditing = document.getElementById('adminPrViewProfileEdit') && !document.getElementById('adminPrViewProfileEdit').classList.contains('hidden')
                if (isEditing) {
                    // Cancel - switch back to display
                    viewEditModeToggle(false)
                    return
                }
                // Switch to edit mode - populate form
                var patient = findPatientById(currentPatientId)
                if (!patient) return
                populateViewEditForm(patient)
                viewEditModeToggle(true)
            })
        }

        // View edit form submit
        var adminPrViewEditForm = document.getElementById('adminPrViewEditForm')
        var adminPrViewEditSave = document.getElementById('adminPrViewEditSave')
        var adminPrViewEditSpinner = document.getElementById('adminPrViewEditSpinner')
        var adminPrViewEditSaveLabel = document.getElementById('adminPrViewEditSaveLabel')
        var adminPrViewEditError = document.getElementById('adminPrViewEditError')
        var adminPrViewEditCancel = document.getElementById('adminPrViewEditCancel')

        if (adminPrViewEditCancel) {
            adminPrViewEditCancel.addEventListener('click', function () {
                viewEditModeToggle(false)
                if (adminPrViewEditError) {
                    adminPrViewEditError.classList.add('hidden')
                    adminPrViewEditError.textContent = ''
                }
            })
        }

        if (adminPrViewEditForm) {
            adminPrViewEditForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (!currentPatientId) return
                if (adminPrViewEditError) {
                    adminPrViewEditError.classList.add('hidden')
                    adminPrViewEditError.textContent = ''
                }
                if (adminPrViewEditSave) adminPrViewEditSave.disabled = true
                if (adminPrViewEditSpinner) adminPrViewEditSpinner.classList.remove('hidden')
                if (adminPrViewEditSaveLabel) adminPrViewEditSaveLabel.textContent = 'Saving...'

                var firstname = document.getElementById('adminPrViewEditFirstname')
                var lastname = document.getElementById('adminPrViewEditLastname')
                var middlename = document.getElementById('adminPrViewEditMiddlename')
                var birthdate = document.getElementById('adminPrViewEditBirthdate')
                var civilStatus = document.getElementById('adminPrViewEditCivilStatus')
                var editNationalitySelect = document.getElementById('adminPrViewEditNationalitySelect')
                var editNationality = document.getElementById('adminPrViewEditNationality')
                var occupation = document.getElementById('adminPrViewEditOccupation')
                var address = document.getElementById('adminPrViewEditAddress')
                var philhealth = document.getElementById('adminPrViewEditPhilhealth')
                var emergencyContact = document.getElementById('adminPrViewEditEmergencyContact')
                var emergencyContactNumber = document.getElementById('adminPrViewEditEmergencyContactNumber')
                var contact = document.getElementById('adminPrViewEditContact')

                var sexVal = ''
                document.querySelectorAll('input[name="adminPrViewEditSex"]').forEach(function (r) {
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

                var uploadInput = document.getElementById('adminPrViewEditProfileUpload')
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

                apiFetch(apiBaseUrl + '/patients/' + encodeURIComponent(currentPatientId), {
                    method: 'POST',
                    body: fd,
                }, false)
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data }
                        }).catch(function () {
                            return { ok: false, data: null }
                        })
                    })
                    .then(function (result) {
                        if (adminPrViewEditSave) adminPrViewEditSave.disabled = false
                        if (adminPrViewEditSpinner) adminPrViewEditSpinner.classList.add('hidden')
                        if (adminPrViewEditSaveLabel) adminPrViewEditSaveLabel.textContent = 'Save changes'

                        if (!result || !result.ok) {
                            var msg = (result && result.data && result.data.message) ? String(result.data.message) : 'Failed to save patient info.'
                            if (adminPrViewEditError) {
                                adminPrViewEditError.textContent = msg
                                adminPrViewEditError.classList.remove('hidden')
                            }
                            if (typeof showToast === 'function') showToast(msg, 'error')
                            return
                        }

                        // Update display and switch back
                        var merged = result.data || {}
                        mergePatientRecord(merged)
                        viewEditModeToggle(false)
                        if (typeof showToast === 'function') showToast('Patient updated successfully.', 'success')
                    })
                    .catch(function (err) {
                        if (adminPrViewEditSave) adminPrViewEditSave.disabled = false
                        if (adminPrViewEditSpinner) adminPrViewEditSpinner.classList.add('hidden')
                        if (adminPrViewEditSaveLabel) adminPrViewEditSaveLabel.textContent = 'Save changes'
                        if (adminPrViewEditError) {
                            adminPrViewEditError.textContent = 'An unexpected error occurred.'
                            adminPrViewEditError.classList.remove('hidden')
                        }
                        if (typeof showToast === 'function') showToast('Network error.', 'error')
                    })
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
                    loadPatients(1)
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

                // Reset to display mode first
                viewEditModeToggle(false)
                // Set currentPatientId so setViewTab works
                currentPatientId = String(patientId)
                var patient = findPatientById(patientId)
                populatePatientDetails(patient)
                openViewModal()
                loadPatientPanelData(patientId)
            })
        }

        if (viewClose) viewClose.addEventListener('click', closeViewModal)
        if (tabDrawerClose) tabDrawerClose.addEventListener('click', closeTabDrawer)
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
                    window.apiFetch(apiBaseUrl + '/medical-backgrounds/' + encodeURIComponent(rowId), {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                    }).then(function (res) {
                        if (!res.ok) throw new Error('Update failed')
                        medBgEditingId = null
                        loadPatientPanelData(currentPatientId)
                        if (typeof showToast === 'function') showToast('Entry updated.', 'success')
                    }).catch(function () {
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
                window.apiFetch(apiBaseUrl + '/medical-backgrounds', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                }).then(function (res) {
                    if (!res.ok) throw new Error('Save failed')
                    loadPatientPanelData(currentPatientId)
                    if (typeof showToast === 'function') showToast('Entry added.', 'success')
                }).catch(function () {
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

        setAgeFilterActiveStyles()
        closeViewModal()
        loadPatients(1)

        var prRefreshBtn = document.getElementById('adminPrRefreshBtn')
        if (prRefreshBtn) {
            prRefreshBtn.addEventListener('click', function () { loadPatients(1) })
        }

        // ── Generate Patient Records Report ──

        function todayIsoDate() {
            var d = new Date()
            return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0')
        }

        function fetchWithAuth(url, options) {
            options = options || {}
            options.headers = options.headers || {}
            var token = null
            try { token = window.localStorage ? window.localStorage.getItem('api_token') : null } catch (e) { token = null }
            if (token) {
                options.headers['Authorization'] = 'Bearer ' + token
            }
            options.headers['X-Requested-With'] = 'XMLHttpRequest'
            options.credentials = 'include'
            return fetch(url, options)
        }

        var prReportModal = document.getElementById('adminPrReportModal')
        var prReportModalCard = document.getElementById('adminPrReportModalCard')
        var prReportModalCloseBtn = document.getElementById('adminPrReportModalCloseBtn')
        var prReportModalTitle = document.getElementById('adminPrReportModalTitle')
        var prReportModalSubtitle = document.getElementById('adminPrReportModalSubtitle')
        var prReportModalForm = document.getElementById('adminPrReportModalForm')
        var prReportPreviewWrap = document.getElementById('adminPrReportPreviewWrap')
        var prReportPreviewFrame = document.getElementById('adminPrReportPreviewFrame')
        var prReportInitialActions = document.getElementById('adminPrReportInitialActions')
        var prReportPreviewActions = document.getElementById('adminPrReportPreviewActions')
        var prReportCancelBtn = document.getElementById('adminPrReportCancelBtn')
        var prReportSubmitBtn = document.getElementById('adminPrReportSubmitBtn')
        var prReportResetBtn = document.getElementById('adminPrReportResetBtn')
        var prReportPreviewCloseBtn = document.getElementById('adminPrReportPreviewCloseBtn')
        var prReportPrintBtn = document.getElementById('adminPrReportPrintBtn')
        var prReportType = document.getElementById('adminPrReportType')
        var prReportDate = document.getElementById('adminPrReportDate')
        var prReportStartDate = document.getElementById('adminPrReportStartDate')
        var prReportEndDate = document.getElementById('adminPrReportEndDate')
        var prReportSingleDateWrap = document.getElementById('adminPrReportSingleDateWrap')
        var prReportRangeWrap = document.getElementById('adminPrReportRangeWrap')
        var prReportFeedback = document.getElementById('adminPrReportFeedback')
        var prReportPatientName = document.getElementById('adminPrReportPatientName')

        var prReportSelectedPatientId = null
        var prReportPreviewLoaded = false

        function prReportSetFeedback(message, tone) {
            if (!prReportFeedback) return
            if (!message) {
                prReportFeedback.className = 'hidden rounded-2xl border px-3 py-2 text-[0.78rem]'
                prReportFeedback.textContent = ''
                return
            }
            var cls = 'rounded-2xl border px-3 py-2 text-[0.78rem] '
            if (tone === 'error') cls += 'border-rose-200 bg-rose-50 text-rose-700'
            else if (tone === 'success') cls += 'border-emerald-200 bg-emerald-50 text-emerald-700'
            else cls += 'border-slate-200 bg-slate-50 text-slate-600'
            prReportFeedback.className = cls
            prReportFeedback.textContent = message
        }

        function prReportSyncInputs() {
            var isRange = prReportType && prReportType.value === 'range'
            if (prReportSingleDateWrap) prReportSingleDateWrap.classList.toggle('hidden', isRange)
            if (prReportRangeWrap) prReportRangeWrap.classList.toggle('hidden', !isRange)
            prReportSetFeedback('', '')
        }

        function prReportSetModalMode(mode) {
            var previewMode = mode === 'preview'
            if (prReportModalCard) {
                prReportModalCard.classList.toggle('max-w-lg', !previewMode)
                prReportModalCard.classList.toggle('max-w-7xl', previewMode)
            }
            if (prReportModalForm) prReportModalForm.classList.toggle('hidden', previewMode)
            if (prReportPreviewWrap) prReportPreviewWrap.classList.toggle('hidden', !previewMode)
            if (prReportInitialActions) {
                prReportInitialActions.classList.toggle('hidden', previewMode)
                prReportInitialActions.classList.toggle('flex', !previewMode)
            }
            if (prReportPreviewActions) {
                prReportPreviewActions.classList.toggle('hidden', !previewMode)
                prReportPreviewActions.classList.toggle('flex', previewMode)
            }
            if (prReportModalTitle) prReportModalTitle.textContent = previewMode ? 'Patient medical record preview' : 'Generate patient medical record'
            if (prReportModalSubtitle) {
                prReportModalSubtitle.textContent = previewMode
                    ? 'Review the generated report here, then print it or save it as PDF when ready.'
                    : 'Choose a single date or a custom date range, then generate a report preview inside this window.'
            }
        }

        function prReportResetModal(clearDates) {
            prReportPreviewLoaded = false
            if (prReportPreviewFrame) prReportPreviewFrame.srcdoc = ''
            prReportSetModalMode('form')
            prReportSetFeedback('', '')
            if (clearDates) {
                var defaultDate = todayIsoDate()
                if (prReportType) prReportType.value = 'date'
                if (prReportDate) prReportDate.value = defaultDate
                if (prReportStartDate) prReportStartDate.value = defaultDate
                if (prReportEndDate) prReportEndDate.value = defaultDate
            }
            prReportSyncInputs()
        }

        function prReportOpenModal(patientId) {
            if (!prReportModal) return
            prReportSelectedPatientId = patientId
            var patient = findPatientById(patientId)
            if (prReportPatientName) {
                prReportPatientName.textContent = patient ? fullName(patient, 'Patient #' + patientId) : 'Patient #' + patientId
            }
            prReportModal.classList.remove('hidden')
            prReportResetModal(false)
        }

        function prReportCloseModal() {
            if (!prReportModal) return
            prReportModal.classList.add('hidden')
            prReportResetModal(false)
            prReportSelectedPatientId = null
        }

        function prReportBuildQuery() {
            if (!prReportSelectedPatientId) throw new Error('No patient selected.')
            var mode = prReportType ? prReportType.value : 'date'
            var base = '?patient_id=' + encodeURIComponent(prReportSelectedPatientId)
            if (mode === 'range') {
                var start = String(prReportStartDate ? prReportStartDate.value || '' : '').trim()
                var end = String(prReportEndDate ? prReportEndDate.value || '' : '').trim()
                if (!start || !end) throw new Error('Starting date and end date are required.')
                return base + '&start_date=' + encodeURIComponent(start) + '&end_date=' + encodeURIComponent(end)
            }
            var singleDate = String(prReportDate ? prReportDate.value || '' : '').trim()
            if (!singleDate) throw new Error('Date is required.')
            return base + '&start_date=' + encodeURIComponent(singleDate) + '&end_date=' + encodeURIComponent(singleDate)
        }

        function prReportOpenPrintableReport() {
            try {
                var query = prReportBuildQuery()
                if (!prReportSubmitBtn) return

                prReportSubmitBtn.disabled = true
                prReportSubmitBtn.textContent = 'Preparing report...'
                prReportSetFeedback('Generating report preview...', 'info')

                fetchWithAuth('/api/patients/report/print' + query + '&embed=1', {
                    headers: { 'Accept': 'text/html' }
                })
                .then(function (response) {
                    return response.text().then(function (html) {
                        return { ok: response.ok, status: response.status, html: html }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        if (result.status === 403) throw new Error('You are not allowed to generate this report.')
                        if (result.status === 401) throw new Error('Authentication required. Please log in again.')
                        if (result.status === 422) {
                            try {
                                var errData = JSON.parse(result.html)
                                var msg = errData && errData.message ? errData.message : 'Validation error.'
                                throw new Error(msg)
                            } catch (e) {
                                throw new Error('Validation error.')
                            }
                        }
                        var snippet = (result.html || '').substring(0, 120)
                        throw new Error('Server error (HTTP ' + result.status + ': ' + snippet + ')')
                    }
                    if (!prReportPreviewFrame) throw new Error('Report preview is unavailable.')
                    prReportPreviewLoaded = false
                    prReportPreviewFrame.srcdoc = result.html
                    prReportSetModalMode('preview')
                    prReportSetFeedback('', '')
                })
                .catch(function (error) {
                    prReportSetFeedback(error && error.message ? error.message : 'Failed to generate patient medical record.', 'error')
                })
                .finally(function () {
                    if (prReportSubmitBtn) {
                        prReportSubmitBtn.disabled = false
                        prReportSubmitBtn.textContent = 'Generate Report'
                    }
                })
            } catch (error) {
                prReportSetFeedback(error && error.message ? error.message : 'Please review the report dates.', 'error')
                if (prReportSubmitBtn) {
                    prReportSubmitBtn.disabled = false
                    prReportSubmitBtn.textContent = 'Generate Report'
                }
            }
        }

        function prReportPrintPreview() {
            if (!prReportPreviewFrame || !prReportPreviewFrame.contentWindow || !prReportPreviewLoaded) return

            var origTitle = document.title
            document.title = 'OPOL MHO - Patient Medical Record'

            prReportPreviewFrame.contentWindow.focus()
            prReportPreviewFrame.contentWindow.print()

            setTimeout(function () {
                document.title = origTitle
            }, 100)
        }

        var prReportDefaultDate = todayIsoDate()
        if (prReportDate) prReportDate.value = prReportDefaultDate
        if (prReportStartDate) prReportStartDate.value = prReportDefaultDate
        if (prReportEndDate) prReportEndDate.value = prReportDefaultDate
        if (prReportPreviewFrame) {
            prReportPreviewFrame.addEventListener('load', function () {
                prReportPreviewLoaded = true
            })
        }

        if (prReportType) prReportType.addEventListener('change', prReportSyncInputs)

        // Delegate click on Generate Records buttons
        if (patientsTableBody) {
            patientsTableBody.addEventListener('click', function (event) {
                var target = event && event.target ? event.target : null
                var btn = target && target.closest ? target.closest('.admin-pr-generate-records') : null
                if (!btn) return
                var patientId = btn.getAttribute('data-patient-id')
                if (!patientId) return
                prReportOpenModal(patientId)
            })
        }

        if (prReportModalCloseBtn) prReportModalCloseBtn.addEventListener('click', prReportCloseModal)
        if (prReportCancelBtn) prReportCancelBtn.addEventListener('click', prReportCloseModal)
        if (prReportSubmitBtn) prReportSubmitBtn.addEventListener('click', prReportOpenPrintableReport)
        if (prReportResetBtn) prReportResetBtn.addEventListener('click', function () { prReportResetModal(true) })
        if (prReportPreviewCloseBtn) prReportPreviewCloseBtn.addEventListener('click', prReportCloseModal)
        if (prReportPrintBtn) prReportPrintBtn.addEventListener('click', prReportPrintPreview)
        if (prReportModal) {
            prReportModal.addEventListener('click', function (event) {
                if (event.target === prReportModal) prReportCloseModal()
            })
        }
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && prReportModal && !prReportModal.classList.contains('hidden')) {
                prReportCloseModal()
            }
        })

        // ── Visit Details Modal ──
        var adminVisitDetailOverlay = document.getElementById('adminVisitDetailOverlay')
        var adminVisitDetailClose = document.getElementById('adminVisitDetailClose')
        if (adminVisitDetailClose) {
            adminVisitDetailClose.addEventListener('click', function () {
                if (adminVisitDetailOverlay) { adminVisitDetailOverlay.classList.add('hidden'); adminVisitDetailOverlay.classList.remove('flex') }
            })
        }
        if (adminVisitDetailOverlay) {
            adminVisitDetailOverlay.addEventListener('click', function (e) {
                if (e.target === adminVisitDetailOverlay) { adminVisitDetailOverlay.classList.add('hidden'); adminVisitDetailOverlay.classList.remove('flex') }
            })
        }
        function openAdminVisitDetail(visit) {
            if (!adminVisitDetailOverlay || !visit) return
            var appt = visit.appointment || {}
            var doctor = appt.doctor || {}
            var services = appt.services || []
            var prescriptions = visit.prescriptions || []
            var dateRaw = visit.visit_datetime || visit.transaction_datetime || ''
            var dateText = dateRaw ? dateRaw.replace('T', ' ').slice(0, 16) : '-'
            var dateEl = document.getElementById('adminVisitDetailDate'); if (dateEl) dateEl.textContent = dateText
            var doctorEl = document.getElementById('adminVisitDetailDoctor'); if (doctorEl) doctorEl.textContent = fullName(doctor, 'Doctor')
            var svcHtml = services.length
                ? services.map(function (s) {
                    var name = s.service_name || 'Unknown service'
                    var desc = s.description ? s.description : ''
                    var price = s.price != null ? formatCurrency(s.price) : ''
                    var parts = [name]
                    if (desc) parts.push('<span class="text-slate-400">' + escapeHtml(desc) + '</span>')
                    if (price) parts.push('<span class="font-medium text-slate-600">' + price + '</span>')
                    return '<div class="flex flex-wrap items-baseline gap-x-2">' + parts.join(' ') + '</div>'
                }).join('')
                : '-'
            var svcEl = document.getElementById('adminVisitDetailServices'); if (svcEl) svcEl.innerHTML = svcHtml
            var feesEl = document.getElementById('adminVisitDetailFees'); if (feesEl) feesEl.textContent = formatCurrency(visit.amount != null ? visit.amount : '')
            var payStatus = visit.payment_status ? String(visit.payment_status) : '-'
            var payEl = document.getElementById('adminVisitDetailPayment'); if (payEl) payEl.textContent = payStatus.charAt(0).toUpperCase() + payStatus.slice(1)
            var apptStatus = appt.status ? String(appt.status) : ''
            var sc = ({ pending:'bg-amber-50 text-amber-700 border-amber-200', confirmed:'border-orange-200 bg-orange-50 text-orange-700', completed:'border-green-200 bg-green-50 text-green-700', cancelled:'bg-red-50 text-red-700 border-red-200', no_show:'bg-slate-100 text-slate-600 border-slate-200', consulted:'border-purple-200 bg-purple-50 text-purple-700', waiting:'bg-amber-50 text-amber-700 border-amber-100', serving:'bg-blue-50 text-blue-700 border-blue-100', done:'bg-emerald-50 text-emerald-700 border-emerald-100', skipped:'bg-orange-50 text-orange-700 border-orange-100', on_hold:'bg-purple-50 text-purple-700 border-purple-100' })[apptStatus] || 'bg-slate-50 text-slate-600 border-slate-100'
            var sl = apptStatus ? apptStatus.charAt(0).toUpperCase() + apptStatus.slice(1).replace(/_/g, ' ') : '-'
            var statusEl = document.getElementById('adminVisitDetailStatus')
            if (statusEl) statusEl.innerHTML = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-medium border ' + sc + '">' + escapeHtml(sl) + '</span>'
            var apptTypeEl = document.getElementById('adminVisitDetailApptType'); if (apptTypeEl) apptTypeEl.textContent = appt.appointment_type ? String(appt.appointment_type).replace(/_/g, '-') : '-'
            var diagEl = document.getElementById('adminVisitDetailDiagnosis'); if (diagEl) diagEl.textContent = visit.diagnosis || '-'
            var txEl = document.getElementById('adminVisitDetailTreatment'); if (txEl) txEl.textContent = visit.treatment_notes || '-'
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
            var rxEl = document.getElementById('adminVisitDetailPrescriptions'); if (rxEl) rxEl.textContent = rxHtml || '-'
            adminVisitDetailOverlay.classList.remove('hidden')
            adminVisitDetailOverlay.classList.add('flex')
        }

        // ── Event delegation for Details buttons ──
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.admin-visit-detail-btn')
            if (!btn) return
            try {
                var visitData = JSON.parse(btn.getAttribute('data-visit') || '{}')
                openAdminVisitDetail(visitData)
            } catch (err) { /* ignore */ }
        })

        // ── Dependents helper functions ──
        window._adminShowingDependentProfile = false
        window._adminDependentProfileId = null

        function fetchAdminParentData(parentId) {
            apiFetch(apiBaseUrl + '/users/' + encodeURIComponent(parentId), { method: 'GET' })
                .then(function (r) { return r.json() })
                .then(function (data) {
                    if (data && !data.error) cachedParentData = data
                    else cachedParentData = null
                    renderViewTabContent('dependents')
                })
                .catch(function () { cachedParentData = null; renderViewTabContent('dependents') })
        }

        function renderAdminParentOrDependentCards(container, rows, type) {
            var html = '<div class="space-y-3">'
            rows.forEach(function (person) {
                var pid = person && person.user_id != null ? String(person.user_id) : ''
                var age = ageFromBirthdate(person && person.birthdate ? String(person.birthdate) : null)
                var profileImg = person && person.prof_path_url ? String(person.prof_path_url) : ''
                html += '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 cursor-pointer hover:border-slate-300 transition-colors admin-' + type + '-card" data-' + type + '-id="' + escapeHtml(pid) + '">' +
                    '<div class="flex items-center gap-4">' +
                        '<div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">' +
                            (profileImg ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">' : '<div class="w-full h-full flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>') +
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
                var card = e.target.closest('.admin-parent-card, .admin-dependent-card')
                if (card) {
                    var id = card.getAttribute('data-parent-id') || card.getAttribute('data-dependent-id')
                    if (id) {
                        container.innerHTML = '<div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-4 text-[0.78rem] text-slate-500">Loading profile...</div></div>'
                        showAdminDependentProfileInline(id)
                    }
                }
            }
            container.addEventListener('click', container._depClickHandler)
        }

        function showAdminDependentProfileInline(personId) {
            var container = viewTabContents['dependents']
            if (!container) return
            window._adminShowingDependentProfile = true
            window._adminDependentProfileId = personId
            apiFetch(apiBaseUrl + '/users/' + encodeURIComponent(personId), { method: 'GET' })
                .then(function (r) { return r.json() })
                .then(function (user) {
                    if (!user || user.error) {
                        container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load profile.</div>'
                        window._adminShowingDependentProfile = false
                        return
                    }
                    renderAdminDependentProfileInline(container, user)
                })
                .catch(function () {
                    container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Failed to load profile.</div>'
                    window._adminShowingDependentProfile = false
                })
        }

        function renderAdminDependentProfileInline(container, userData) {
            var user = userData || null
            if (!user && window._adminDependentProfileId) {
                var allRows = cachedDependentRows || []
                user = allRows.find(function (r) { return String(r.user_id) === String(window._adminDependentProfileId) }) || null
            }
            if (!user) {
                container.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-8">Profile data not available.</div>'
                window._adminShowingDependentProfile = false
                return
            }
            var profileImg = user.prof_path_url || ''
            container.innerHTML =
                '<div class="space-y-4">' +
                    '<button type="button" class="inline-flex items-center gap-1.5 text-[0.78rem] font-semibold text-slate-500 hover:text-slate-700 admin-profile-back-btn">' +
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
                                '<div><label class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label><div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(String(user.birthdate || '').slice(0, 10) || '-') + '</div></div>' +
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
                                    (profileImg ? '<img src="' + profileImg.replace(/"/g, '&quot;') + '" alt="" class="w-full h-full object-cover">' : '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>') +
                                '</div>' +
                                '<div class="mt-4 text-left">' +
                                    '<label class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>' +
                                    '<div class="text-xs text-slate-800 px-3 py-2 bg-slate-50/60 border border-slate-100 rounded-lg">' + escapeHtml(user.contact_number || '-') + '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            var backBtn = container.querySelector('.admin-profile-back-btn')
            if (backBtn) {
                backBtn.addEventListener('click', function () {
                    window._adminShowingDependentProfile = false
                    window._adminDependentProfileId = null
                    renderViewTabContent('dependents')
                })
            }
        }
    })
</script>
