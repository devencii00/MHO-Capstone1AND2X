<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900">Staff Management</h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Staff</span>
    </div>
    <p class="text-xs text-slate-500 mb-4">
      

    <div id="adminDoctorError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
    <div id="adminDoctorSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

    <div class="mb-3 flex flex-col gap-2 md:flex-row md:items-end">
        <div class="flex-1">
            <label for="admin_doctor_search" class="block text-[0.7rem] text-slate-600 mb-1">Search staff</label>
            <input id="admin_doctor_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by name, email, or role">
        </div>
        <div class="w-full md:w-32">
            <label for="admin_staff_role_filter" class="block text-[0.7rem] text-slate-600 mb-1">Role</label>
            <select id="admin_staff_role_filter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="">All staff</option>
                <option value="doctor">Doctors</option>
                <option value="receptionist">Receptionists</option>
            </select>
        </div>
        <div class="w-full md:w-40">
            <label for="admin_doctor_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
            <select id="admin_doctor_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                <option value="created_desc">Newest first</option>
                <option value="created_asc">Oldest first</option>
                <option value="name_asc">Name A–Z</option>
                <option value="name_desc">Name Z–A</option>
            </select>
        </div>
    </div>

<div class="overflow-x-auto scrollbar-hidden">
        <table class="min-w-full text-left text-xs text-slate-600">
            <thead>
                <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                    <th class="py-2 pr-4 font-semibold">Profile</th>
                    <th class="py-2 pr-4 font-semibold">Name</th>
                    <th class="py-2 pr-4 font-semibold">Role</th>
                    <th class="py-2 pr-4 font-semibold">Specialization</th>
                    <th class="py-2 pr-4 font-semibold">Employee #</th>
                    <th class="py-2 pr-4 font-semibold">PRC #</th>
                    <th class="py-2 pr-4 font-semibold">PTR #</th>
                    <th class="py-2 pr-4 font-semibold">PHIC #</th>
                    <th class="py-2 pr-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody id="admin_doctor_table_body">
                <tr>
                    <td colspan="9" class="py-4 text-center text-[0.78rem] text-slate-400">
                        Loading staff…
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="adminStaffPagination" class="flex items-center justify-center gap-3 pt-3 pb-1"></div>

    
    <!-- Schedule Modal -->
    <div id="adminDoctorScheduleModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 items-center justify-center p-4">
        <div class="w-full max-w-5xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
            <div class="sticky top-0 bg-white px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3 z-10">
                <div>
                    <div class="text-sm font-semibold text-slate-900" id="adminDoctorScheduleTitle">Manage Schedule</div>
                    <div class="text-[0.72rem] text-slate-500">Add time slots and view existing schedules.</div>
                </div>
                <button type="button" id="adminDoctorScheduleClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>

            <div class="p-5">
                <div id="adminDoctorScheduleError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                <div id="adminDoctorScheduleSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

                <div class="flex items-center justify-between mb-3">
                    <button type="button" id="adminDoctorScheduleAddToggle" class="text-[0.78rem] font-semibold text-green-600 hover:text-green-700 underline underline-offset-2 cursor-pointer">
                        + Add Schedule
                    </button>
                </div>

                <div id="adminDoctorScheduleFormWrap" class="hidden">
                <form id="adminDoctorScheduleForm" class="mb-5 grid gap-3 grid-cols-1 md:grid-cols-6 items-start">
                    <div>
                        <label for="admin_schedule_from_day" class="block text-[0.7rem] text-slate-600 mb-1">From day</label>
                        <select id="admin_schedule_from_day" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="">Select</option>
                            <option value="mon">Mon</option>
                            <option value="tue">Tue</option>
                            <option value="wed">Wed</option>
                            <option value="thu">Thu</option>
                            <option value="fri">Fri</option>
                            <option value="sat">Sat</option>
                            <option value="sun">Sun</option>
                        </select>
                    </div>
                    <div>
                        <label for="admin_schedule_to_day" class="block text-[0.7rem] text-slate-600 mb-1">To day</label>
                        <select id="admin_schedule_to_day" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            <option value="">Select</option>
                            <option value="mon">Mon</option>
                            <option value="tue">Tue</option>
                            <option value="wed">Wed</option>
                            <option value="thu">Thu</option>
                            <option value="fri">Fri</option>
                            <option value="sat">Sat</option>
                            <option value="sun">Sun</option>
                        </select>
                    </div>
                    <div>
                        <label for="admin_schedule_start_time" class="block text-[0.7rem] text-slate-600 mb-1">Start time</label>
                        <input id="admin_schedule_start_time" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. 8:00 AM or 08:00">
                        <div class="mt-1 text-[0.68rem] leading-tight text-slate-400">Accepts `8:00 AM`, `8am`, or `08:00`.</div>
                    </div>
                    <div>
                        <label for="admin_schedule_end_time" class="block text-[0.7rem] text-slate-600 mb-1">End time</label>
                        <input id="admin_schedule_end_time" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. 5:00 PM or 17:00">
                        <div class="mt-1 text-[0.68rem] leading-tight text-slate-400">Accepts `5:00 PM`, `5pm`, or `17:00`.</div>
                    </div>
                    <div>
                        <label for="admin_schedule_max" class="block text-[0.7rem] text-slate-600 mb-1">Max patients</label>
                        <input id="admin_schedule_max" type="number" min="1" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional">
                    </div>
                    <div>
                        <label for="admin_schedule_room" class="block text-[0.7rem] text-slate-600 mb-1">Room # (optional)</label>
                        <input id="admin_schedule_room" type="number" min="1" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. 101">
                    </div>
                    <input type="hidden" id="admin_schedule_slot_minutes" value="60">
                    <div class="md:col-span-6 flex justify-end">
                        <button type="submit" id="adminDoctorScheduleSubmit" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60">
                            <span id="adminDoctorScheduleSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            <span id="adminDoctorScheduleSubmitLabel">Generate schedule</span>
                        </button>
                    </div>
                </form>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h4 class="text-xs font-semibold text-slate-900 mb-3">Existing Schedules</h4>
                    
                    <!-- Day Filter for Deletion -->
                    <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div class="flex items-center gap-2">
                            <button type="button" id="adminScheduleSelectAll" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50">Select all</button>
                            <button type="button" id="adminScheduleClearAll" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50">Clear</button>
                            <button type="button" id="adminScheduleDeleteSelected" class="px-3 py-2 rounded-xl bg-rose-600 text-white text-[0.72rem] font-semibold hover:bg-rose-700">Delete selected</button>
                        </div>
                        <div class="w-full sm:w-48">
                            <label for="adminScheduleDayFilter" class="block text-[0.7rem] text-slate-600 mb-1">Filter by day</label>
                            <select id="adminScheduleDayFilter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                <option value="">All days</option>
                                <option value="mon">Monday</option>
                                <option value="tue">Tuesday</option>
                                <option value="wed">Wednesday</option>
                                <option value="thu">Thursday</option>
                                <option value="fri">Friday</option>
                                <option value="sat">Saturday</option>
                                <option value="sun">Sunday</option>
                            </select>
                        </div>
                        <button type="button" id="adminScheduleTimeTableViewBtn" class="px-3 py-2 rounded-xl border border-green-200 bg-green-50 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100">Time Table view</button>
                    </div>

                    <!-- Grouped Schedule View (by day, then time slots) -->
                    <div id="adminDoctorScheduleList" class="space-y-3 max-h-[300px] overflow-y-auto">
                    </div>

                    <!-- Time Table View (hidden by default) -->
                    <div id="adminDoctorTimeTableView" class="hidden mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="adminConfirmOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/40 items-center justify-center p-4">
        <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                    <x-lucide-info class="w-[18px] h-[18px]" />
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-slate-900">Confirm</div>
                    <div id="adminConfirmMessage" class="text-[0.78rem] text-slate-600 mt-0.5">Are you sure?</div>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-end gap-2">
                <button type="button" id="adminConfirmCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" id="adminConfirmOk" class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">
                    <span id="adminConfirmOkSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span id="adminConfirmOkLabel">Confirm</span>
                </button>
            </div>
        </div>
    </div>
    <div id="adminDoctorEditOverlay" class="hidden fixed inset-0 z-50 bg-slate-900/40 items-center justify-center p-4">
        <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)]">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Edit Staff Info</div>
                    <div id="adminDoctorEditSubtitle" class="text-[0.72rem] text-slate-500">Update profile information.</div>
                </div>
                <button type="button" id="adminDoctorEditClose" class="text-slate-400 hover:text-slate-600">
                    <x-lucide-x class="w-[20px] h-[20px]" />
                </button>
            </div>
            <div class="p-5">
                <div id="adminDoctorEditError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                <form id="adminDoctorEditForm" class="grid grid-cols-1 md:grid-cols-5 gap-5">
                    <!-- LEFT: Form fields (3 cols) -->
                    <div class="md:col-span-3 space-y-3">
                        <div>
                            <label for="adminDoctorEditRole" class="block text-[0.7rem] text-slate-600 mb-1">Position</label>
                            <select id="adminDoctorEditRole" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                <option value="doctor">Doctor</option>
                                <option value="receptionist">Receptionist</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label for="adminDoctorEditLastname" class="block text-[0.7rem] text-slate-600 mb-1">Last name</label>
                                <input id="adminDoctorEditLastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            </div>
                            <div>
                                <label for="adminDoctorEditFirstname" class="block text-[0.7rem] text-slate-600 mb-1">First name</label>
                                <input id="adminDoctorEditFirstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            </div>
                            <div>
                                <label for="adminDoctorEditMiddlename" class="block text-[0.7rem] text-slate-600 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                                <input id="adminDoctorEditMiddlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[0.7rem] text-slate-600 mb-1">Sex</label>
                                <div class="flex items-center gap-4 pt-1">
                                    <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                        <input type="radio" name="adminDoctorEditSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                    </label>
                                    <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                        <input type="radio" name="adminDoctorEditSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label for="adminDoctorEditBirthdate" class="block text-[0.7rem] text-slate-600 mb-1">Birthdate</label>
                                <input id="adminDoctorEditBirthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            </div>
                            <div>
                                <label for="adminDoctorEditEmploymentStatus" class="block text-[0.7rem] text-slate-600 mb-1">Employment status</label>
                                <select id="adminDoctorEditEmploymentStatus" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                    <option value="">—</option>
                                    <option value="contractual">Contractual</option>
                                    <option value="permanent">Permanent</option>
                                </select>
                            </div>
                        </div>
                        <hr class="border-slate-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="adminDoctorEditPrcLicense" class="block text-[0.7rem] text-slate-600 mb-1">PRC License Number</label>
                                <input id="adminDoctorEditPrcLicense" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="7-digit number" maxlength="7">
                            </div>
                            <div>
                                <label for="adminDoctorEditPhilhealth" class="block text-[0.7rem] text-slate-600 mb-1">PHIC Number</label>
                                <input id="adminDoctorEditPhilhealth" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="adminDoctorEditSpecialization" class="block text-[0.7rem] text-slate-600 mb-1">Specialization <span id="adminDoctorEditSpecRequired" class="text-red-500 hidden">*</span></label>
                                <input id="adminDoctorEditSpecialization" type="text" list="adminDoctorSpecializationList" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. General Medicine">
                                <span id="adminDoctorEditSpecHint" class="mt-1 text-[0.68rem] leading-tight text-slate-400 hidden"></span>
                                <datalist id="adminDoctorSpecializationList">
                                    <option value="Pediatrics"></option>
                                    <option value="General Medicine"></option>
                                    <option value="General Surgeon"></option>
                                    <option value="Obstetrician - Gynecologist"></option>
                                    <option value="Internal Medicine"></option>
                                </datalist>
                            </div>
                            <div>
                                <label for="adminDoctorEditPtrNumber" class="block text-[0.7rem] text-slate-600 mb-1">PTR Number</label>
                                <input id="adminDoctorEditPtrNumber" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            </div>
                        </div>
                        <hr class="border-slate-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="adminDoctorEditEmergencyContact" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact (name)</label>
                                <input id="adminDoctorEditEmergencyContact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                            </div>
                            <div>
                                <label for="adminDoctorEditEmergencyContactNumber" class="block text-[0.7rem] text-slate-600 mb-1">Emergency contact number</label>
                                <input id="adminDoctorEditEmergencyContactNumber" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[0.7rem] text-slate-600 mb-1">Active in service?</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="adminDoctorEditActiveInService" value="1" class="rounded-full text-green-600 focus:ring-green-500"> Yes
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="adminDoctorEditActiveInService" value="0" class="rounded-full text-green-600 focus:ring-green-500"> No
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT: Profile panel (2 cols) -->
                    <div class="md:col-span-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-5 text-center">
                            <div class="text-[0.72rem] font-semibold text-slate-700 mb-3">Profile Photo</div>
                            <div id="adminDoctorEditProfilePreview" class="w-32 h-32 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 overflow-hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div class="mt-3">
                                <label for="adminDoctorEditProfileUpload" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-green-200 bg-green-50 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    Upload photo
                                </label>
                                <input id="adminDoctorEditProfileUpload" type="file" accept="image/*" class="hidden">
                            </div>
                            <div class="mt-4 text-left space-y-3">
                                <div>
                                    <label for="adminDoctorEditEmail" class="block text-[0.7rem] text-slate-600 mb-1">Email</label>
                                    <input id="adminDoctorEditEmail" type="email" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                                </div>
                                <div>
                                    <label for="adminDoctorEditContact" class="block text-[0.7rem] text-slate-600 mb-1">Contact number</label>
                                    <input id="adminDoctorEditContact" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bottom: Save / Cancel -->
                    <div class="md:col-span-5 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" id="adminDoctorEditCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button type="submit" id="adminDoctorEditSave" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600">
                            <span id="adminDoctorEditSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            <span id="adminDoctorEditSaveLabel">Save changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="adminDoctorEditConfirmOverlay" class="hidden fixed inset-0 z-[60] bg-slate-900/40 items-center justify-center p-4">
        <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                    <x-lucide-info class="w-[18px] h-[18px]" />
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-slate-900">Confirm</div>
                    <div id="adminDoctorEditConfirmMessage" class="text-[0.78rem] text-slate-600 mt-0.5">Are you sure?</div>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-end gap-2">
                <button type="button" id="adminDoctorEditConfirmCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="button" id="adminDoctorEditConfirmOk" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    ;(function () {
        var errorBox = document.getElementById('adminDoctorError')
        var successBox = document.getElementById('adminDoctorSuccess')
        var searchInput = document.getElementById('admin_doctor_search')
        var roleFilter = document.getElementById('admin_staff_role_filter')
        var sortSelect = document.getElementById('admin_doctor_sort')
        var tableBody = document.getElementById('admin_doctor_table_body')
        var staffRows = []
        var staffPage = 1
        var staffPerPage = 10

        var scheduleModal = document.getElementById('adminDoctorScheduleModal')
        var scheduleTitle = document.getElementById('adminDoctorScheduleTitle')
        var scheduleClose = document.getElementById('adminDoctorScheduleClose')
        var scheduleErrorBox = document.getElementById('adminDoctorScheduleError')
        var scheduleSuccessBox = document.getElementById('adminDoctorScheduleSuccess')
        var scheduleForm = document.getElementById('adminDoctorScheduleForm')
        var scheduleFromDay = document.getElementById('admin_schedule_from_day')
        var scheduleToDay = document.getElementById('admin_schedule_to_day')
        var scheduleMax = document.getElementById('admin_schedule_max')
        var scheduleRoom = document.getElementById('admin_schedule_room')
        var scheduleSlotMinutes = document.getElementById('admin_schedule_slot_minutes')
        var scheduleList = document.getElementById('adminDoctorScheduleList')
        var scheduleSubmit = document.getElementById('adminDoctorScheduleSubmit')
        var scheduleSpinner = document.getElementById('adminDoctorScheduleSpinner')
        var scheduleSubmitLabel = document.getElementById('adminDoctorScheduleSubmitLabel')
        var scheduleAddToggle = document.getElementById('adminDoctorScheduleAddToggle')
        var scheduleFormWrap = document.getElementById('adminDoctorScheduleFormWrap')
        var scheduleDayFilter = document.getElementById('adminScheduleDayFilter')
        var scheduleSelectAll = document.getElementById('adminScheduleSelectAll')
        var scheduleClearAll = document.getElementById('adminScheduleClearAll')
        var scheduleDeleteSelected = document.getElementById('adminScheduleDeleteSelected')
        var scheduleBulkDay = document.getElementById('adminScheduleBulkDay')
        var scheduleDeleteDay = document.getElementById('adminScheduleDeleteDay')
        var scheduleDeleteAll = document.getElementById('adminScheduleDeleteAll')
        var confirmOverlay = document.getElementById('adminConfirmOverlay')
        var confirmMessage = document.getElementById('adminConfirmMessage')
        var confirmOk = document.getElementById('adminConfirmOk')
        var confirmOkSpinner = document.getElementById('adminConfirmOkSpinner')
        var confirmOkLabel = document.getElementById('adminConfirmOkLabel')
        var confirmCancel = document.getElementById('adminConfirmCancel')
        var confirmResolver = null
        var confirmCountdownTimer = null
        var confirmOkOriginalText = null

        var currentDoctorIdForSchedule = null
        var currentScheduleId = null
        var loadedSchedules = []
        var doctors = []
        var scheduleListWired = false

        var doctorEditOverlay = document.getElementById('adminDoctorEditOverlay')
        var doctorEditClose = document.getElementById('adminDoctorEditClose')
        var doctorEditCancel = document.getElementById('adminDoctorEditCancel')
        var doctorEditForm = document.getElementById('adminDoctorEditForm')
        var doctorEditError = document.getElementById('adminDoctorEditError')
        var doctorEditSubtitle = document.getElementById('adminDoctorEditSubtitle')
        var doctorEditRole = document.getElementById('adminDoctorEditRole')
        var doctorEditFirstname = document.getElementById('adminDoctorEditFirstname')
        var doctorEditMiddlename = document.getElementById('adminDoctorEditMiddlename')
        var doctorEditLastname = document.getElementById('adminDoctorEditLastname')
        var doctorEditSpecialization = document.getElementById('adminDoctorEditSpecialization')
        var doctorEditSexMale = document.querySelector('input[name="adminDoctorEditSex"][value="Male"]')
        var doctorEditSexFemale = document.querySelector('input[name="adminDoctorEditSex"][value="Female"]')
        var doctorEditBirthdate = document.getElementById('adminDoctorEditBirthdate')
        var doctorEditEmploymentStatus = document.getElementById('adminDoctorEditEmploymentStatus')
        var doctorEditPrcLicense = document.getElementById('adminDoctorEditPrcLicense')
        var doctorEditPhilhealth = document.getElementById('adminDoctorEditPhilhealth')
        var doctorEditPtrNumber = document.getElementById('adminDoctorEditPtrNumber')
        var doctorEditEmergencyContact = document.getElementById('adminDoctorEditEmergencyContact')
        var doctorEditEmergencyContactNumber = document.getElementById('adminDoctorEditEmergencyContactNumber')
        var doctorEditActiveYes = document.querySelector('input[name="adminDoctorEditActiveInService"][value="1"]')
        var doctorEditActiveNo = document.querySelector('input[name="adminDoctorEditActiveInService"][value="0"]')
        var doctorEditContact = document.getElementById('adminDoctorEditContact')
        var doctorEditEmail = document.getElementById('adminDoctorEditEmail')
        var doctorEditProfileUpload = document.getElementById('adminDoctorEditProfileUpload')
        var doctorEditProfilePreview = document.getElementById('adminDoctorEditProfilePreview')
        var doctorEditSave = document.getElementById('adminDoctorEditSave')
        var doctorEditSpinner = document.getElementById('adminDoctorEditSpinner')

        var editingDoctorId = null

        var doctorEditConfirmOverlay = document.getElementById('adminDoctorEditConfirmOverlay')
        var doctorEditConfirmMessage = document.getElementById('adminDoctorEditConfirmMessage')
        var doctorEditConfirmOk = document.getElementById('adminDoctorEditConfirmOk')
        var doctorEditConfirmCancel = document.getElementById('adminDoctorEditConfirmCancel')
        var doctorEditConfirmResolver = null

        var apiBasePath = "{{ request()->getBasePath() }}"
        function apiUrl(path) {
            return String(apiBasePath || '') + String(path || '')
        }

        function fetchAllDoctorSchedules(doctorId, onSuccess, onFailure) {
            var perPage = 100
            var page = 1
            var all = []

            function fail(message) {
                if (typeof onFailure === 'function') onFailure(message || 'Failed to load schedules.')
            }

            function fetchPage() {
                var url = apiUrl('/api/doctor-schedules') +
                    '?doctor_id=' + encodeURIComponent(doctorId) +
                    '&per_page=' + encodeURIComponent(perPage) +
                    '&page=' + encodeURIComponent(page)

                apiFetch(url, { method: 'GET' })
                    .then(function (response) { return readResponse(response) })
                    .then(function (result) {
                        if (!result.ok || !result.data) {
                            if (result.status === 401) {
                                fail('Session expired. Please log in again.')
                                return
                            }
                            if (result.status === 403) {
                                fail('Forbidden (403). Your account does not have permission to view this doctor’s schedules. Please sign out and sign in as an admin.')
                                return
                            }
                            var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to load schedules.'
                            if (!result.data && result.raw) {
                                msg += ' HTTP ' + String(result.status || '')
                            }
                            fail(msg)
                            return
                        }

                        var payload = result.data
                        var items = Array.isArray(payload && payload.data) ? payload.data : []
                        all = all.concat(items)

                        var lastPage = parseInt(payload && payload.last_page ? payload.last_page : 1, 10)
                        if (isNaN(lastPage) || lastPage < 1) lastPage = 1

                        if (page < lastPage) {
                            page += 1
                            fetchPage()
                            return
                        }

                        if (typeof onSuccess === 'function') {
                            try {
                                onSuccess(all)
                            } catch (e) {
                                var renderMsg = 'Failed to render schedules.'
                                if (e && e.message) renderMsg += ' ' + String(e.message)
                                fail(renderMsg)
                            }
                        }
                    })
                    .catch(function (err) {
                        var msg = 'Network error while loading schedules.'
                        if (err && err.message) msg += ' ' + String(err.message)
                        fail(msg)
                    })
            }

            fetchPage()
        }

        function showDoctorError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showDoctorSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function showDoctorEditError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function setDoctorEditSubmitting(isSubmitting) {
            if (doctorEditSave) doctorEditSave.disabled = !!isSubmitting
            if (doctorEditSpinner) doctorEditSpinner.classList.toggle('hidden', !isSubmitting)
        }

        function openDoctorEditModal(doctor) {
            if (!doctorEditOverlay) return
            editingDoctorId = doctor && doctor.user_id ? String(doctor.user_id) : null
            showDoctorEditError('')
            setDoctorEditSubmitting(false)

            var fullName = ((doctor.firstname || '') + ' ' + (doctor.lastname || '')).trim()
            if (!fullName) fullName = doctor.email || ('Staff #' + (doctor.user_id || ''))
            if (doctorEditSubtitle) doctorEditSubtitle.textContent = 'Editing — ' + fullName

            if (doctorEditRole) doctorEditRole.value = doctor.role || 'doctor'
            if (doctorEditFirstname) doctorEditFirstname.value = doctor.firstname || ''
            if (doctorEditMiddlename) doctorEditMiddlename.value = doctor.middlename || ''
            if (doctorEditLastname) doctorEditLastname.value = doctor.lastname || ''
            if (doctorEditSpecialization) {
                var specRequired = document.getElementById('adminDoctorEditSpecRequired')
                var specHint = document.getElementById('adminDoctorEditSpecHint')
                if (doctor.role === 'receptionist') {
                    doctorEditSpecialization.value = doctor.specialization || 'N/A'
                    doctorEditSpecialization.removeAttribute('required')
                    if (specRequired) specRequired.classList.add('hidden')
                    if (specHint) {
                        specHint.textContent = 'Defaults to N/A for receptionists'
                        specHint.classList.remove('hidden')
                    }
                } else {
                    doctorEditSpecialization.value = doctor.specialization || ''
                    doctorEditSpecialization.setAttribute('required', 'required')
                    if (specRequired) specRequired.classList.remove('hidden')
                    if (specHint) specHint.classList.add('hidden')
                }
            }

            if (doctorEditSexMale) doctorEditSexMale.checked = doctor.sex === 'Male'
            if (doctorEditSexFemale) doctorEditSexFemale.checked = doctor.sex === 'Female'
            if (doctorEditBirthdate) {
                var bd = doctor.birthdate || ''
                doctorEditBirthdate.value = bd ? bd.slice(0, 10) : ''
            }
            if (doctorEditEmploymentStatus) doctorEditEmploymentStatus.value = doctor.employment_status || ''

            if (doctorEditPrcLicense) doctorEditPrcLicense.value = doctor.prc_license || ''
            if (doctorEditPhilhealth) {
                var raw = doctor.philhealth_number || ''
                doctorEditPhilhealth.value = raw ? formatPhilhealth(raw) : ''
            }
            if (doctorEditPtrNumber) doctorEditPtrNumber.value = doctor.ptr_number || ''

            if (doctorEditEmergencyContact) doctorEditEmergencyContact.value = doctor.emergency_contact || ''
            if (doctorEditEmergencyContactNumber) {
                var ecn = doctor.emergency_contact_number || ''
                doctorEditEmergencyContactNumber.value = ecn ? formatPhone(ecn) : ''
            }

            if (doctorEditActiveYes) doctorEditActiveYes.checked = doctor.active_in_service === true || doctor.active_in_service === 1
            if (doctorEditActiveNo) doctorEditActiveNo.checked = doctor.active_in_service === false || doctor.active_in_service === 0 || doctor.active_in_service === null

            if (doctorEditContact) {
                var c = doctor.contact_number || ''
                doctorEditContact.value = c ? formatPhone(c) : ''
            }
            if (doctorEditEmail) doctorEditEmail.value = doctor.email || ''

            // Profile photo preview
            updateProfilePreview(doctor.prof_path || null)

            doctorEditOverlay.classList.remove('hidden')
            doctorEditOverlay.classList.add('flex')
        }

        function updateProfilePreview(path) {
            if (!doctorEditProfilePreview) return
            if (path) {
                doctorEditProfilePreview.innerHTML = '<img src="' + path.replace(/"/g,'&quot;') + '" alt="" class="w-full h-full object-cover">'
            } else {
                doctorEditProfilePreview.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
            }
        }

        function formatPhone(val) {
            var s = String(val || '').replace(/[^\d]/g, '')
            if (s.startsWith('63')) s = s.slice(2)
            if (s.startsWith('0')) s = s.slice(1)
            if (s.length === 10) return '+63 ' + s.slice(0,3) + ' ' + s.slice(3,6) + ' ' + s.slice(6)
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
            if (s.length >= 2 && s.length <= 4) return s.slice(0,2) + '-' + s.slice(2)
            if (s.length > 4 && s.length <= 11) return s.slice(0,2) + '-' + s.slice(2,9) + '-' + s.slice(9)
            if (s.length > 11) return s.slice(0,2) + '-' + s.slice(2,11) + '-' + s.slice(11,12)
            return s
        }

        // Auto-format phone inputs
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
        setupPhoneFormat(doctorEditContact)
        setupPhoneFormat(doctorEditEmergencyContactNumber)

        // Auto-format PHIC
        if (doctorEditPhilhealth) {
            doctorEditPhilhealth.addEventListener('input', function () {
                var cursor = this.selectionStart
                var raw = this.value.replace(/[^\d]/g, '')
                if (raw.length > 12) raw = raw.slice(0, 12)
                this.value = formatPhilhealth(raw)
            })
        }

        // Profile upload preview
        if (doctorEditProfileUpload) {
            doctorEditProfileUpload.addEventListener('change', function () {
                var file = this.files && this.files[0]
                if (!file) return
                var reader = new FileReader()
                reader.onload = function (e) {
                    updateProfilePreview(e.target.result)
                }
                reader.readAsDataURL(file)
            })
        }

        function closeDoctorEditModal() {
            if (!doctorEditOverlay) {
                return
            }
            doctorEditOverlay.classList.add('hidden')
            doctorEditOverlay.classList.remove('flex')
            editingDoctorId = null
        }

        function confirmDoctorEditAction(message) {
            return new Promise(function (resolve) {
                if (!doctorEditConfirmOverlay || !doctorEditConfirmMessage || !doctorEditConfirmOk || !doctorEditConfirmCancel) {
                    resolve(window.confirm(message || 'Are you sure?'))
                    return
                }
                doctorEditConfirmMessage.textContent = message || 'Are you sure?'
                doctorEditConfirmResolver = resolve
                doctorEditConfirmOverlay.classList.remove('hidden')
                doctorEditConfirmOverlay.classList.add('flex')
            })
        }

        function normalizePhilippinesNumber(value) {
            var raw = String(value || '').trim()
            if (!raw) {
                return ''
            }
            raw = raw.replace(/\s+/g, '').replace(/-/g, '')
            if (raw.startsWith('+63')) {
                return raw
            }
            if (raw.startsWith('63')) {
                return '+' + raw
            }
            if (raw.startsWith('0') && raw.length >= 2) {
                return '+63' + raw.slice(1)
            }
            if (/^\d+$/.test(raw)) {
                return '+63' + raw
            }
            return raw
        }

        function isValidPhilippinesNumber(value) {
            var normalized = normalizePhilippinesNumber(value)
            return /^\+63\d{10}$/.test(normalized)
        }

        function isValidName(value) {
            var v = String(value || '').trim()
            if (v === '') {
                return true
            }
            return /^[A-Za-z][A-Za-z\s.'-]*$/.test(v)
        }

        function closeDoctorEditConfirm(result) {
            if (doctorEditConfirmOverlay) {
                doctorEditConfirmOverlay.classList.add('hidden')
                doctorEditConfirmOverlay.classList.remove('flex')
            }
            var resolver = doctorEditConfirmResolver
            doctorEditConfirmResolver = null
            if (typeof resolver === 'function') {
                resolver(!!result)
            }
        }

        if (doctorEditConfirmOk) {
            doctorEditConfirmOk.addEventListener('click', function () { closeDoctorEditConfirm(true) })
        }
        if (doctorEditConfirmCancel) {
            doctorEditConfirmCancel.addEventListener('click', function () { closeDoctorEditConfirm(false) })
        }
        if (doctorEditConfirmOverlay) {
            doctorEditConfirmOverlay.addEventListener('click', function (e) {
                if (e.target === doctorEditConfirmOverlay) closeDoctorEditConfirm(false)
            })
        }

        if (doctorEditClose) {
            doctorEditClose.addEventListener('click', closeDoctorEditModal)
        }
        if (doctorEditCancel) {
            doctorEditCancel.addEventListener('click', closeDoctorEditModal)
        }
        if (doctorEditOverlay) {
            doctorEditOverlay.addEventListener('click', function (e) {
                if (e.target === doctorEditOverlay) closeDoctorEditModal()
            })
        }

        // Role change handler — toggle specialization required / N/A default
        if (doctorEditRole) {
            doctorEditRole.addEventListener('change', function () {
                if (!doctorEditSpecialization) return
                var specRequired = document.getElementById('adminDoctorEditSpecRequired')
                var specHint = document.getElementById('adminDoctorEditSpecHint')
                if (this.value === 'receptionist') {
                    if (!doctorEditSpecialization.value || doctorEditSpecialization.value === 'N/A') {
                        doctorEditSpecialization.value = 'N/A'
                    }
                    doctorEditSpecialization.removeAttribute('required')
                    if (specRequired) specRequired.classList.add('hidden')
                    if (specHint) {
                        specHint.textContent = 'Defaults to N/A for receptionists'
                        specHint.classList.remove('hidden')
                    }
                } else {
                    if (doctorEditSpecialization.value === 'N/A') {
                        doctorEditSpecialization.value = ''
                    }
                    doctorEditSpecialization.setAttribute('required', 'required')
                    if (specRequired) specRequired.classList.remove('hidden')
                    if (specHint) specHint.classList.add('hidden')
                }
            })
        }

        if (doctorEditForm) {
            doctorEditForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (!editingDoctorId) {
                    return
                }
                if (doctorEditSave && doctorEditSave.disabled) {
                    return
                }

                showDoctorEditError('')

                var f = doctorEditFirstname ? String(doctorEditFirstname.value || '').trim() : ''
                var m = doctorEditMiddlename ? String(doctorEditMiddlename.value || '').trim() : ''
                var l = doctorEditLastname ? String(doctorEditLastname.value || '').trim() : ''
                var c = doctorEditContact ? String(doctorEditContact.value || '').trim() : ''

                if (!isValidName(f) || !isValidName(m) || !isValidName(l)) {
                    showDoctorEditError('Name fields must contain letters only.')
                    return
                }
                if (c && c !== '+63') {
                    if (!isValidPhilippinesNumber(c)) {
                        showDoctorEditError('Contact number must be a valid PH number starting with +63 and 10 digits.')
                        return
                    }
                }

                confirmDoctorEditAction('Are you sure you want to save these changes?')
                    .then(function (confirmed) {
                        if (!confirmed) return

                        setDoctorEditSubmitting(true)

                        var formData = new FormData()
                        formData.append('_method', 'PUT')

                        function val(el) { return el ? String(el.value || '').trim() : '' }
                        function appendIf(key, value) { if (value) formData.append(key, value) }

                        formData.append('role', val(doctorEditRole) || 'doctor')
                        appendIf('firstname', val(doctorEditFirstname))
                        formData.append('middlename', val(doctorEditMiddlename) || 'N/A')
                        appendIf('lastname', val(doctorEditLastname))
                        var specVal = val(doctorEditSpecialization)
                        if (doctorEditRole && doctorEditRole.value === 'receptionist' && !specVal) {
                            specVal = 'N/A'
                        }
                        appendIf('specialization', specVal)
                        var sexVal = doctorEditSexMale && doctorEditSexMale.checked ? 'Male' : (doctorEditSexFemale && doctorEditSexFemale.checked ? 'Female' : null)
                        appendIf('sex', sexVal)
                        appendIf('birthdate', val(doctorEditBirthdate))
                        appendIf('employment_status', val(doctorEditEmploymentStatus))
                        appendIf('prc_license', val(doctorEditPrcLicense))
                        appendIf('ptr_number', val(doctorEditPtrNumber))

                        var phRaw = val(doctorEditPhilhealth).replace(/[^\d]/g, '')
                        appendIf('philhealth_number', phRaw)

                        appendIf('emergency_contact', val(doctorEditEmergencyContact))

                        var ecnRaw = val(doctorEditEmergencyContactNumber)
                        appendIf('emergency_contact_number', ecnRaw ? parsePhoneRaw(ecnRaw) : null)

                        var active = null
                        if (doctorEditActiveYes && doctorEditActiveYes.checked) active = 1
                        else if (doctorEditActiveNo && doctorEditActiveNo.checked) active = 0
                        if (active !== null) formData.append('active_in_service', active)

                        var cRaw = val(doctorEditContact)
                        appendIf('contact_number', cRaw ? parsePhoneRaw(cRaw) : null)
                        appendIf('email', val(doctorEditEmail))

                        // Attach profile file if selected
                        if (doctorEditProfileUpload && doctorEditProfileUpload.files && doctorEditProfileUpload.files[0]) {
                            formData.append('prof_path', doctorEditProfileUpload.files[0])
                        }

                        apiFetch(apiUrl('/api/doctors/' + editingDoctorId), {
                            method: 'POST',
                            body: formData
                        })
                            .then(readResponse)
                            .then(function (result) {
                                if (!result.ok) {
                                    if (result.status === 422 && result.data && result.data.errors) {
                                        var firstKey = Object.keys(result.data.errors)[0]
                                        var msg = firstKey && result.data.errors[firstKey] && result.data.errors[firstKey][0] ? result.data.errors[firstKey][0] : 'Validation error.'
                                        showDoctorEditError(String(msg))
                                    } else {
                                        var msg2 = (result.data && result.data.message) ? result.data.message : 'Failed to update staff.'
                                        showDoctorEditError(String(msg2))
                                    }
                                    return
                                }

                                showDoctorSuccess('Changes saved.')
                                setTimeout(function () { showDoctorSuccess('') }, 2500)
                                closeDoctorEditModal()
                                loadDoctors()
                            })
                            .catch(function () {
                                showDoctorEditError('Network error while updating doctor.')
                            })
                            .finally(function () {
                                setDoctorEditSubmitting(false)
                            })
                    })
                    .catch(function () {})
            })
        }

        function setScheduleSubmitting(isSubmitting) {
            if (scheduleSubmit) scheduleSubmit.disabled = !!isSubmitting
            if (scheduleSpinner) scheduleSpinner.classList.toggle('hidden', !isSubmitting)
            if (scheduleSubmitLabel) scheduleSubmitLabel.textContent = currentScheduleId ? (isSubmitting ? 'Saving...' : 'Save changes') : (isSubmitting ? 'Saving...' : 'Generate schedule')
        }

        function stopConfirmCountdown() {
            if (confirmCountdownTimer) {
                clearInterval(confirmCountdownTimer)
                confirmCountdownTimer = null
            }
            if (confirmOk) {
                confirmOk.disabled = false
                confirmOk.classList.remove('opacity-60', 'cursor-not-allowed')
            }
            if (confirmOkSpinner) {
                confirmOkSpinner.classList.add('hidden')
            }
            if (confirmOkLabel && confirmOkOriginalText != null) {
                confirmOkLabel.textContent = confirmOkOriginalText
            }
            confirmOkOriginalText = null
        }

        function confirmAction(message, options) {
            return new Promise(function (resolve) {
                if (!confirmOverlay || !confirmMessage || !confirmOk || !confirmCancel) {
                    resolve(window.confirm(message || 'Are you sure?'))
                    return
                }
                stopConfirmCountdown()
                confirmMessage.textContent = message || 'Are you sure?'
                var confirmText = options && options.confirmText ? String(options.confirmText) : 'Confirm'
                if (confirmOkLabel) confirmOkLabel.textContent = confirmText
                confirmOkOriginalText = confirmText
                confirmResolver = resolve
                confirmOverlay.classList.remove('hidden')
                confirmOverlay.classList.add('flex')

                var countdownSeconds = options && options.countdownSeconds ? parseInt(String(options.countdownSeconds), 10) : 0
                if (!countdownSeconds || isNaN(countdownSeconds) || countdownSeconds < 1) {
                    return
                }

                confirmOk.disabled = true
                confirmOk.classList.add('opacity-60', 'cursor-not-allowed')
                if (confirmOkSpinner) confirmOkSpinner.classList.remove('hidden')

                var remaining = countdownSeconds
                if (confirmOkLabel) confirmOkLabel.textContent = confirmText + ' (' + remaining + ')'

                confirmCountdownTimer = setInterval(function () {
                    remaining -= 1
                    if (remaining <= 0) {
                        stopConfirmCountdown()
                        return
                    }
                    if (confirmOkLabel) {
                        confirmOkLabel.textContent = confirmText + ' (' + remaining + ')'
                    }
                }, 1000)
            })
        }

        function closeConfirm(result) {
            if (confirmOverlay) {
                confirmOverlay.classList.add('hidden')
                confirmOverlay.classList.remove('flex')
            }
            stopConfirmCountdown()
            var resolver = confirmResolver
            confirmResolver = null
            if (typeof resolver === 'function') {
                resolver(!!result)
            }
        }

        if (confirmOk) {
            confirmOk.addEventListener('click', function () { closeConfirm(true) })
        }
        if (confirmCancel) {
            confirmCancel.addEventListener('click', function () { closeConfirm(false) })
        }
        if (confirmOverlay) {
            confirmOverlay.addEventListener('click', function (e) {
                if (e.target === confirmOverlay) closeConfirm(false)
            })
        }

        function pad2(n) {
            return String(n).padStart(2, '0')
        }
        function normalizeDayKey(raw) {
            var v = String(raw == null ? '' : raw).trim().toLowerCase()
            if (!v) return ''
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
            return map[v.slice(0, 3)] || ''
        }
        function normalizeTimeToHHMM(raw) {
            var value = String(raw == null ? '' : raw).trim()
            if (!value) return ''

            var hhmm24 = value.match(/^(\d{1,2}):(\d{2})(?::\d{2})?$/)
            if (hhmm24) {
                var h24 = parseInt(hhmm24[1], 10)
                var m24 = hhmm24[2]
                if (!isNaN(h24) && h24 >= 0 && h24 <= 23) {
                    return pad2(h24) + ':' + m24
                }
            }

            var compact = value.match(/^(\d{1,2})(?::?(\d{2}))?\s*([AaPp][Mm])$/)
            if (compact) {
                return to24Hour(compact[1], compact[2] || '00', compact[3])
            }

            return ''
        }
        function to24Hour(hour12, minute, ampm) {
            var h = parseInt(hour12, 10)
            if (isNaN(h) || h < 1 || h > 12) return ''
            var m = String(minute || '')
            if (!/^\d{2}$/.test(m)) return ''
            var ap = String(ampm || '').toLowerCase().trim()
            if (ap !== 'am' && ap !== 'pm') return ''
            var base = h % 12
            if (ap === 'pm') base += 12
            return pad2(base) + ':' + m
        }
        function minutesFromHHMM(hhmm) {
            var t = String(hhmm || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return NaN
            var parts = t.split(':')
            return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10)
        }
        function syncScheduleTimeHidden(prefix) {
            var inputEl = document.getElementById('admin_schedule_' + prefix + '_time')
            if (!inputEl) return
            var normalized = normalizeTimeToHHMM(inputEl.value)
            inputEl.dataset.normalizedTime = normalized
        }
        function read12HourTime(prefix) {
            var inputEl = document.getElementById('admin_schedule_' + prefix + '_time')
            if (!inputEl) return ''
            return normalizeTimeToHHMM(inputEl.value)
        }
        function set12HourSelects(prefix, rawValue) {
            var t = normalizeTimeToHHMM(rawValue)
            var inputEl = document.getElementById('admin_schedule_' + prefix + '_time')
            if (!inputEl) return
            inputEl.value = t ? formatTimeLabel(t) : ''
            inputEl.dataset.normalizedTime = t
        }
       

        function clear12HourSelects(prefix) {
            var inputEl = document.getElementById('admin_schedule_' + prefix + '_time')
            if (!inputEl) return
            inputEl.value = ''
            inputEl.dataset.normalizedTime = ''
        }

        function wire12HourPicker(prefix) {
            var inputEl = document.getElementById('admin_schedule_' + prefix + '_time')
            if (!inputEl) return
            inputEl.addEventListener('blur', function () {
                var normalized = normalizeTimeToHHMM(inputEl.value)
                if (!normalized) {
                    inputEl.dataset.normalizedTime = ''
                    return
                }
                inputEl.value = formatTimeLabel(normalized)
                inputEl.dataset.normalizedTime = normalized
            })
        }

        function formatTimeLabel(hhmm) {
            var t = String(hhmm || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return ''
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

        function formatTimeCompact(hhmm) {
            var t = String(hhmm || '').slice(0, 5)
            if (!/^\d{2}:\d{2}$/.test(t)) return ''
            var parts = t.split(':')
            var h24 = parseInt(parts[0], 10)
            var m = parts[1]
            var ap = h24 >= 12 ? 'PM' : 'AM'
            var h12 = h24 % 12
            if (h12 === 0) h12 = 12
            if (m === '00') return String(h12) + ap
            return String(h12) + ':' + m + ap
        }

        wire12HourPicker('start')
        wire12HourPicker('end')
        clear12HourSelects('start')
        clear12HourSelects('end')

        function loadDoctors() {
            if (!tableBody) return
            tableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-center text-[0.78rem] text-slate-400">Loading staff…</td></tr>'

            apiFetch(apiUrl('/api/staff'), {
                method: 'GET'
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        tableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-center text-[0.78rem] text-red-500">Failed to load staff.</td></tr>'
                        return
                    }
                    doctors = Array.isArray(result.data) ? result.data : []
                    renderDoctors()
                })
                .catch(function () {
                    tableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-center text-[0.78rem] text-red-500">Network error while loading staff.</td></tr>'
                })
        }

        function renderDoctors() {
            if (!tableBody) return

            var query = searchInput ? searchInput.value.toLowerCase().trim() : ''
            var sort = sortSelect ? sortSelect.value : 'created_desc'
            var roleFilterVal = roleFilter ? roleFilter.value : ''

            var filtered = doctors.slice().filter(function (staff) {
                if (roleFilterVal && staff.role !== roleFilterVal) return false
                var name = ((staff.firstname || '') + ' ' + (staff.lastname || '')).toLowerCase().trim()
                var email = (staff.email || '').toLowerCase()
                var roleText = (staff.role || '').toLowerCase()
                if (!query) return true
                return name.indexOf(query) !== -1 || email.indexOf(query) !== -1 || roleText.indexOf(query) !== -1
            })

            filtered.sort(function (a, b) {
                if (sort === 'name_asc' || sort === 'name_desc') {
                    var na = ((a.firstname || '') + ' ' + (a.lastname || '')).toLowerCase().trim()
                    var nb = ((b.firstname || '') + ' ' + (b.lastname || '')).toLowerCase().trim()
                    if (na < nb) return sort === 'name_asc' ? -1 : 1
                    if (na > nb) return sort === 'name_asc' ? 1 : -1
                    return 0
                }
                var da = a.created_at || ''
                var db = b.created_at || ''
                if (da < db) return sort === 'created_desc' ? 1 : -1
                if (da > db) return sort === 'created_desc' ? -1 : 1
                return 0
            })

            if (!filtered.length) {
                tableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-center text-[0.78rem] text-slate-400">' +
                    (query ? 'No staff members match your search.' : 'No staff members found.') +
                    '</td></tr>'
                staffRows = []
                showStaffPage(1)
                return
            }

            tableBody.innerHTML = ''
            filtered.forEach(function (staff) {
                var tr = document.createElement('tr')
                tr.className = 'border-b border-slate-50 last:border-0'

                var fullName = ((staff.firstname || '') + ' ' + (staff.lastname || '')).trim()
                if (!fullName) {
                    fullName = staff.email || ('Staff #' + staff.user_id)
                }
                var specialization = (staff.specialization || '').trim()
                var empNum = (staff.employee_number || '').trim()
                var prc = (staff.prc_license || '').trim()
                var ptr = (staff.ptr_number || '').trim()
                var phic = (staff.philhealth_number || '').trim()
                var roleLabel = staff.role === 'doctor' ? 'Doctor' : (staff.role === 'receptionist' ? 'Receptionist' : (staff.role || '—'))
                var profileImg = staff.prof_path ? staff.prof_path : null

                tr.innerHTML =
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700">' +
                        (profileImg
                            ? '<img src="' + profileImg.replace(/"/g,'&quot;') + '" alt="" class="w-10 h-10 rounded-lg object-cover border border-slate-200">'
                            : '<div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>'
                        ) +
                    '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-700 font-medium">' + fullName + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' +
                        '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.68rem] font-semibold border ' +
                            (staff.role === 'doctor' ? 'text-green-700 bg-green-50 border-green-100' : 'text-blue-700 bg-blue-50 border-blue-100') +
                        '">' + roleLabel + '</span>' +
                    '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' +
                        (specialization ? specialization : (staff.role === 'receptionist' ? 'N/A' : '<span class="text-slate-400">—</span>')) +
                    '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (empNum ? empNum : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (prc ? prc : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (ptr ? ptr : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem] text-slate-500">' + (phic ? phic : '<span class="text-slate-400">—</span>') + '</td>' +
                    '<td class="py-2 pr-4 text-[0.78rem]">' +
                        '<div class="flex items-center gap-2 flex-wrap">' +
                            '<button type="button" class="inline-flex items-center justify-center rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100 transition-colors admin-doctor-edit" data-doctor-id="' + staff.user_id + '">Edit Info</button>' +
                            (staff.role === 'doctor'
                                ? '<button type="button" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50 transition-colors admin-doctor-schedule" data-doctor-id="' + staff.user_id + '" data-doctor-name="' + fullName.replace(/"/g, '&quot;') + '">Manage schedule</button>'
                                : ''
                            ) +
                        '</div>' +
                    '</td>'

                tableBody.appendChild(tr)
            })

            var editButtons = tableBody.querySelectorAll('.admin-doctor-edit')
            editButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var id = this.getAttribute('data-doctor-id')
                    var doctor = doctors.find(function (d) { return String(d.user_id) === String(id) })
                    if (!doctor) return
                    openDoctorEditModal(doctor)
                })
            })
            var scheduleButtons = tableBody.querySelectorAll('.admin-doctor-schedule')
            scheduleButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var id = this.getAttribute('data-doctor-id')
                    var name = this.getAttribute('data-doctor-name') || ''
                    currentDoctorIdForSchedule = id
                    currentScheduleId = null
                    
                    clear12HourSelects('start')
                    clear12HourSelects('end')
                    if (scheduleMax) scheduleMax.value = ''
                    if (scheduleFromDay) scheduleFromDay.value = ''
                    if (scheduleToDay) scheduleToDay.value = ''
                    if (scheduleSubmitLabel) scheduleSubmitLabel.textContent = 'Generate schedule'
                    
                    showDoctorError('')
                    showDoctorSuccess('')
                    setScheduleSubmitting(false)
                    
                    if (scheduleTitle) {
                        scheduleTitle.textContent = 'Manage Schedule | ' + name
                    }
                    
                    if (scheduleModal) {
                        scheduleModal.classList.remove('hidden')
                        scheduleModal.classList.add('flex')
                    }
                    
                    loadSchedulesForDoctor(id)
                })
            })

            // Track staff rows and show first page
            staffRows = Array.prototype.slice.call(tableBody.querySelectorAll('tr'))
            showStaffPage(1)
        }

        function loadSchedulesForDoctor(doctorId) {
            if (!scheduleList) return
            if (!doctorId) return
            loadedSchedules = []
            scheduleList.innerHTML = '<div class="text-[0.78rem] text-slate-500">Loading schedules…</div>'
            showDoctorError('')
            showDoctorSuccess('')

            fetchAllDoctorSchedules(doctorId, function (all) {
                loadedSchedules = Array.isArray(all) ? all : []
                renderGroupedSchedules()
                renderTimeTableView(loadedSchedules)
                wireScheduleBulkActions(doctorId)

                if (!scheduleListWired) {
                    scheduleListWired = true
                    scheduleList.addEventListener('click', function (e) {
                        var toggleBtn = e.target && e.target.closest ? e.target.closest('button.admin-schedule-toggle[data-day]') : null
                        if (toggleBtn) {
                            var day = toggleBtn.getAttribute('data-day') || ''
                            var container = document.getElementById('adminScheduleDay_' + day)
                            if (!container) return
                            var extras = container.querySelectorAll('.admin-schedule-slot-extra')
                            var allHidden = true
                            extras.forEach(function (el) { if (!el.classList.contains('hidden')) allHidden = false })
                            if (allHidden) {
                                extras.forEach(function (el) { el.classList.remove('hidden') })
                                toggleBtn.textContent = 'Retract'
                            } else {
                                extras.forEach(function (el) { el.classList.add('hidden') })
                                toggleBtn.textContent = 'Show all'
                            }
                            return
                        }

                        var editBtn = e.target && e.target.closest ? e.target.closest('button.admin-schedule-edit[data-schedule-id]') : null
                        if (editBtn) {
                            var sid = editBtn.getAttribute('data-schedule-id') || ''
                            var slot = loadedSchedules.find(function (s) { return String(s && s.schedule_id) === String(sid) })
                            if (!slot) return

                            currentScheduleId = String(slot.schedule_id)
                            if (scheduleFromDay) scheduleFromDay.value = normalizeDayKey(slot.day_of_week || '')
                            if (scheduleToDay) scheduleToDay.value = ''

                            set12HourSelects('start', slot.start_time || '')
                            set12HourSelects('end', slot.end_time || '')
                            if (scheduleMax) scheduleMax.value = slot.max_patients != null ? String(slot.max_patients) : ''
                            if (scheduleRoom) scheduleRoom.value = slot.room_number != null ? String(slot.room_number) : ''
                            if (scheduleSubmitLabel) scheduleSubmitLabel.textContent = 'Save changes'
                            return
                        }

                        var deleteBtn = e.target && e.target.closest ? e.target.closest('button.admin-schedule-delete[data-schedule-id]') : null
                        if (deleteBtn) {
                            var sid2 = deleteBtn.getAttribute('data-schedule-id') || ''
                            var slot2 = loadedSchedules.find(function (s) { return String(s && s.schedule_id) === String(sid2) })
                            if (!slot2 || !currentDoctorIdForSchedule) return

                            showDoctorError('')
                            showDoctorSuccess('')
                            confirmAction('Delete this schedule slot?', { countdownSeconds: 3, confirmText: 'Delete' })
                                .then(function (confirmed) {
                                    if (!confirmed) return
                                    bulkDeleteSchedules({
                                        doctor_id: parseInt(String(currentDoctorIdForSchedule), 10),
                                        schedule_ids: [parseInt(String(slot2.schedule_id), 10)]
                                    }, 'Schedule deleted.')
                                })
                            return
                        }
                    })

                    scheduleList.addEventListener('change', function (e) {
                        var sel = e.target && e.target.closest ? e.target.closest('select.admin-schedule-availability') : null
                        if (!sel || !currentDoctorIdForSchedule) return
                        var sid = sel.getAttribute('data-schedule-id') || ''
                        var newVal = sel.value === '1'
                        var label = newVal ? 'Available' : 'Unavailable'
                        confirmAction('Are you sure you want to set this slot to ' + label + '?', { confirmText: 'Yes, ' + label })
                            .then(function (confirmed) {
                                if (!confirmed) {
                                    sel.value = newVal ? '0' : '1'
                                    return
                                }
                                var ids = [parseInt(sid, 10)]
                                if (isNaN(ids[0])) return
                                apiFetch(apiUrl('/api/doctor-schedules/bulk-availability'), {
                                    method: 'PATCH',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ schedule_ids: ids, is_available: newVal })
                                })
                                    .then(function (r) { return readResponse(r) })
                                    .then(function (result) {
                                        if (!result.ok) {
                                            showDoctorError('Failed to update availability.')
                                            sel.value = newVal ? '0' : '1'
                                            return
                                        }
                                        showDoctorSuccess('Slot set to ' + label + '.')
                                        loadDoctors()
                                        loadSchedulesForDoctor(currentDoctorIdForSchedule)
                                    })
                                    .catch(function () {
                                        showDoctorError('Network error.')
                                        sel.value = newVal ? '0' : '1'
                                    })
                            })
                            .catch(function () {})
                    })
                }
            }, function (message) {
                loadedSchedules = []
                scheduleList.innerHTML = '<div class="text-[0.78rem] text-slate-500">' + String(message || 'Failed to load schedules.') + '</div>'
                renderTimeTableView([])
            })
        }

        function renderGroupedSchedules() {
            if (!scheduleList) return
            var filter = scheduleDayFilter ? String(scheduleDayFilter.value || '').toLowerCase() : ''
            var slots = Array.isArray(loadedSchedules) ? loadedSchedules.slice() : []
            if (!slots.length) {
                scheduleList.innerHTML = '<div class="text-[0.78rem] text-slate-500">No schedules found.</div>'
                return
            }

            var dayOrder = [
                { key: 'mon', label: 'Monday' },
                { key: 'tue', label: 'Tuesday' },
                { key: 'wed', label: 'Wednesday' },
                { key: 'thu', label: 'Thursday' },
                { key: 'fri', label: 'Friday' },
                { key: 'sat', label: 'Saturday' },
                { key: 'sun', label: 'Sunday' }
            ]

            var grouped = {}
            dayOrder.forEach(function (d) { grouped[d.key] = [] })

            slots.forEach(function (s) {
                var key = s && s.day_of_week ? String(s.day_of_week).toLowerCase() : ''
                if (!key || !grouped[key]) return
                if (filter && filter !== key) return
                grouped[key].push(s)
            })

            dayOrder.forEach(function (d) {
                grouped[d.key].sort(function (a, b) {
                    var sa = String(a && a.start_time ? a.start_time : '').slice(0, 5)
                    var sb = String(b && b.start_time ? b.start_time : '').slice(0, 5)
                    if (sa < sb) return -1
                    if (sa > sb) return 1
                    var ia = parseInt(String(a && a.schedule_id ? a.schedule_id : 0), 10)
                    var ib = parseInt(String(b && b.schedule_id ? b.schedule_id : 0), 10)
                    if (isNaN(ia)) ia = 0
                    if (isNaN(ib)) ib = 0
                    return ia - ib
                })
            })

            var html = ''
            dayOrder.forEach(function (d) {
                var rows = grouped[d.key] || []
                if (!rows.length) return
                var dayId = 'adminScheduleDay_' + d.key
                html += '<div class="rounded-xl border border-slate-200 bg-white p-3">' +
                    '<div class="flex items-center justify-between mb-2">' +
                        '<div class="text-[0.72rem] font-semibold text-slate-900">' + d.label + '</div>' +
                        '<div class="flex items-center gap-2">' +
                            '<div class="text-[0.7rem] text-slate-400">' + rows.length + ' slot(s)</div>' +
                            (rows.length > 1 ? '<button type="button" class="admin-schedule-toggle text-[0.68rem] font-semibold text-green-600 hover:text-green-700 underline" data-day="' + d.key + '">Show all</button>' : '') +
                        '</div>' +
                    '</div>' +
                    '<div id="' + dayId + '">'

                rows.forEach(function (s, idx) {
                    var start = String(s && s.start_time ? s.start_time : '').slice(0, 5)
                    var end = String(s && s.end_time ? s.end_time : '').slice(0, 5)
                    var label = (formatTimeLabel(start) || start) + '–' + (formatTimeLabel(end) || end)
                    var id = s && s.schedule_id != null ? String(s.schedule_id) : ''
                    var isUnavailable = s && s.is_available === false
                    var hiddenClass = idx > 0 ? ' hidden admin-schedule-slot-extra' : ''

                    html += '<div class="flex items-center justify-between gap-3 rounded-lg border border-slate-100 bg-slate-50/60 px-3 py-2 mb-1' + hiddenClass + '">' +
                        '<label class="flex items-center gap-2">' +
                            '<input type="checkbox" class="admin-schedule-check rounded border-slate-300 text-green-600 focus:ring-green-500" data-schedule-id="' + id + '">' +
                            '<span class="text-[0.78rem] text-slate-700 font-semibold">' + label + '</span>' +
                        '</label>' +
                        '<div class="flex items-center gap-2 shrink-0">' +
                            '<select class="admin-schedule-availability text-[0.72rem] font-semibold rounded-lg border px-2 py-1 outline-none cursor-pointer ' + (isUnavailable ? 'text-rose-700 bg-rose-50 border-rose-200' : 'text-emerald-700 bg-emerald-50 border-emerald-200') + '" data-schedule-id="' + id + '">' +
                                '<option value="1"' + (isUnavailable ? '' : ' selected') + '>Available</option>' +
                                '<option value="0"' + (isUnavailable ? ' selected' : '') + '>Unavailable</option>' +
                            '</select>' +
                            '<button type="button" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-1 text-[0.72rem] font-semibold text-slate-700 hover:bg-slate-50 transition-colors admin-schedule-edit" data-schedule-id="' + id + '">Edit</button>' +
                            '<button type="button" class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-1 text-[0.72rem] font-semibold text-rose-700 hover:bg-rose-100 transition-colors admin-schedule-delete" data-schedule-id="' + id + '">Delete</button>' +
                        '</div>' +
                    '</div>'
                })

                html += '</div></div>'
            })

            if (!html) {
                html = '<div class="text-[0.78rem] text-slate-500">No schedules found for the selected filter.</div>'
            }

            scheduleList.innerHTML = html
        }


        function setBulkDeleting(isDeleting) {
            var buttons = [scheduleSelectAll, scheduleClearAll, scheduleDeleteSelected, scheduleDeleteDay, scheduleDeleteAll]
            buttons.forEach(function (btn) {
                if (!btn) return
                btn.disabled = !!isDeleting
                btn.classList.toggle('opacity-60', !!isDeleting)
                btn.classList.toggle('cursor-not-allowed', !!isDeleting)
            })
            if (scheduleBulkDay) {
                scheduleBulkDay.disabled = !!isDeleting
                scheduleBulkDay.classList.toggle('opacity-60', !!isDeleting)
                scheduleBulkDay.classList.toggle('cursor-not-allowed', !!isDeleting)
            }
        }

        function getCheckedScheduleIds() {
            if (!scheduleList) return []
            var checks = scheduleList.querySelectorAll('.admin-schedule-check')
            var ids = []
            checks.forEach(function (c) {
                if (!c || !c.checked) return
                var id = c.getAttribute('data-schedule-id')
                if (id) ids.push(parseInt(id, 10))
            })
            return ids.filter(function (v) { return !isNaN(v) })
        }

        function bulkDeleteSchedules(payload, successMessage) {
            if (!payload || !payload.doctor_id) return
            setBulkDeleting(true)
            apiFetch(apiUrl('/api/doctor-schedules/bulk-delete'), {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
                .then(function (response) { return readResponse(response) })
                .then(function (result) {
                    if (!result.ok) {
                        var msg = (result.data && result.data.message) ? String(result.data.message) : 'Failed to delete schedules.'
                        if (result.status === 401) msg = 'Session expired. Please log in again.'
                        if (result.status === 403) msg = 'You do not have permission to delete schedules.'
                        if (result.status === 422 && result.data && result.data.errors) {
                            var all = []
                            Object.keys(result.data.errors).forEach(function (key) {
                                var v = result.data.errors[key]
                                if (Array.isArray(v)) v.forEach(function (x) { all.push(String(x)) })
                            })
                            if (all.length) msg = all.join(' ')
                        }
                        showDoctorError(msg)
                        return
                    }
                    var deleted = result.data && result.data.deleted != null ? parseInt(result.data.deleted, 10) : null
                    var finalMsg = successMessage || 'Schedules deleted successfully.'
                    if (deleted != null && !isNaN(deleted)) {
                        finalMsg = finalMsg + ' Deleted ' + deleted + '.'
                    }
                    showDoctorSuccess(finalMsg)
                    loadSchedulesForDoctor(String(payload.doctor_id))
                    loadDoctors()
                })
                .catch(function () {
                    showDoctorError('Network error while deleting schedules.')
                })
                .finally(function () {
                    setBulkDeleting(false)
                })
        }

        function wireScheduleBulkActions(doctorId) {
            if (!scheduleList) return

            if (scheduleDayFilter) {
                scheduleDayFilter.onchange = function () {
                    renderGroupedSchedules()
                }
            }

            if (scheduleSelectAll) {
                scheduleSelectAll.onclick = function () {
                    var checks = scheduleList.querySelectorAll('.admin-schedule-check')
                    checks.forEach(function (c) { c.checked = true })
                }
            }
            if (scheduleClearAll) {
                scheduleClearAll.onclick = function () {
                    var checks = scheduleList.querySelectorAll('.admin-schedule-check')
                    checks.forEach(function (c) { c.checked = false })
                }
            }
            if (scheduleDeleteSelected) {
                scheduleDeleteSelected.onclick = function () {
                    showDoctorError('')
                    showDoctorSuccess('')
                    var ids = getCheckedScheduleIds()
                    if (!ids.length) {
                        showDoctorError('Select at least one schedule.')
                        return
                    }
                    confirmAction('Delete ' + ids.length + ' selected schedule(s)?', { countdownSeconds: 3, confirmText: 'Delete' })
                        .then(function (confirmed) {
                            if (!confirmed) return
                            bulkDeleteSchedules({ doctor_id: parseInt(doctorId, 10), schedule_ids: ids }, 'Selected schedules deleted.')
                        })
                }
            }
            if (scheduleDeleteDay) {
                scheduleDeleteDay.onclick = function () {
                    showDoctorError('')
                    showDoctorSuccess('')
                    var day = scheduleBulkDay ? String(scheduleBulkDay.value || '') : ''
                    if (!day) {
                        showDoctorError('Select a day first.')
                        return
                    }
                    var countForDay = loadedSchedules.filter(function (s) { return String(s.day_of_week || '') === day }).length
                    confirmAction('Delete all schedules for ' + day.toUpperCase() + '? (' + countForDay + ' slot(s))', { countdownSeconds: 3, confirmText: 'Delete' })
                        .then(function (confirmed) {
                            if (!confirmed) return
                            bulkDeleteSchedules({ doctor_id: parseInt(doctorId, 10), day_of_week: day }, 'Day schedules deleted.')
                        })
                }
            }
            if (scheduleDeleteAll) {
                scheduleDeleteAll.onclick = function () {
                    showDoctorError('')
                    showDoctorSuccess('')
                    var countAll = Array.isArray(loadedSchedules) ? loadedSchedules.length : 0
                    confirmAction('Delete ALL schedules for this doctor? (' + countAll + ' slot(s))', { countdownSeconds: 3, confirmText: 'Delete all' })
                        .then(function (confirmed) {
                            if (!confirmed) return
                            bulkDeleteSchedules({ doctor_id: parseInt(doctorId, 10) }, 'All schedules deleted.')
                        })
                }
            }
        }

              if (scheduleClose) {
            scheduleClose.addEventListener('click', function () {
                if (scheduleModal) {
                    scheduleModal.classList.add('hidden')
                    scheduleModal.classList.remove('flex')
                }
                currentDoctorIdForSchedule = null
                currentScheduleId = null
                clear12HourSelects('start')
                clear12HourSelects('end')
                if (scheduleMax) scheduleMax.value = ''
                if (scheduleRoom) scheduleRoom.value = ''
                if (scheduleFromDay) scheduleFromDay.value = ''
                if (scheduleToDay) scheduleToDay.value = ''
                if (scheduleSubmitLabel) scheduleSubmitLabel.textContent = 'Generate schedule'
                showDoctorError('')
                showDoctorSuccess('')
                if (scheduleFormWrap) scheduleFormWrap.classList.add('hidden')
                if (scheduleAddToggle) scheduleAddToggle.textContent = '+ Add Schedule'
            })
        }
        if (scheduleModal) {
            scheduleModal.addEventListener('click', function (e) {
                if (e.target !== scheduleModal) return
                if (scheduleClose) scheduleClose.click()
            })
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                renderDoctorsWithReset()
            })
        }
        if (roleFilter) {
            roleFilter.addEventListener('change', function () {
                renderDoctorsWithReset()
            })
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                renderDoctorsWithReset()
            })
        }

        if (scheduleAddToggle && scheduleFormWrap) {
            scheduleAddToggle.addEventListener('click', function () {
                var isHidden = scheduleFormWrap.classList.contains('hidden')
                if (isHidden) {
                    scheduleFormWrap.classList.remove('hidden')
                    scheduleAddToggle.textContent = '- Cancel'
                } else {
                    scheduleFormWrap.classList.add('hidden')
                    scheduleAddToggle.textContent = '+ Add Schedule'
                }
            })
        }
        if (scheduleForm) {
            scheduleForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (!currentDoctorIdForSchedule) {
                    showDoctorError('Select a doctor to manage schedules.')
                    return
                }
                showDoctorError('')
                showDoctorSuccess('')

                syncScheduleTimeHidden('start')
                syncScheduleTimeHidden('end')
        
                var start = read12HourTime('start')
                var end = read12HourTime('end')
                var maxPatients = scheduleMax ? scheduleMax.value : ''
                var roomNumberRaw = scheduleRoom ? String(scheduleRoom.value || '').trim() : ''
                var fromDay = scheduleFromDay ? normalizeDayKey(scheduleFromDay.value || '') : ''
                var toDay = scheduleToDay ? normalizeDayKey(scheduleToDay.value || '') : ''
                var slotMinutes = scheduleSlotMinutes && scheduleSlotMinutes.value ? parseInt(String(scheduleSlotMinutes.value), 10) : 60
                if (!slotMinutes || isNaN(slotMinutes)) {
                    slotMinutes = 60
                }

              // Validate required fields
if (!start || !end) {
    showDoctorError('Start time and end time are required.')
    return
}

if (!fromDay) {
    showDoctorError('Day is required.')
    return
}

// Only require toDay when creating new schedules (not editing)
if (!currentScheduleId && !toDay) {
    showDoctorError('To day is required for generating multiple day schedules.')
    return
}

                if (end <= start) {
                    showDoctorError('End time must be after start time.')
                    return
                }

                // Calculate minutes for validation
                var startMinutes = minutesFromHHMM(start)
                var endMinutes = minutesFromHHMM(end)
                if (isNaN(startMinutes) || isNaN(endMinutes) || endMinutes <= startMinutes) {
                    showDoctorError('End time must be after start time.')
                    return
                }

                if (!currentScheduleId) {
                    var diff = endMinutes - startMinutes
                    if (diff % slotMinutes !== 0) {
                        showDoctorError('Time range must be divisible by ' + slotMinutes + ' minutes.')
                        return
                    }
                }

                var body = {}
                if (maxPatients) {
                    body.max_patients = parseInt(maxPatients, 10)
                }
                if (roomNumberRaw !== '') {
                    var roomNumber = parseInt(roomNumberRaw, 10)
                    if (isNaN(roomNumber) || roomNumber < 1) {
                        showDoctorError('Room number must be a valid number (1 or higher).')
                        return
                    }
                    body.room_number = roomNumber
                } else {
                    body.room_number = null
                }

                var url = apiUrl('/api/doctor-schedules')
                var method = 'POST'
                if (currentScheduleId) {
                    url = url + '/' + currentScheduleId
                    method = 'PUT'
                    body.day_of_week = fromDay
                    body.start_time = start
                    body.end_time = end
                } else {
                    body.doctor_id = currentDoctorIdForSchedule
                    body.from_day = fromDay
                    body.to_day = toDay
                    body.start_time = start
                    body.end_time = end
                    body.slot_minutes = slotMinutes
                }

                setScheduleSubmitting(true)
                apiFetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(body)
                })
                    .then(function (response) {
                        return readResponse(response)
                    })
                    .then(function (result) {
                        if (!result.ok) {
                            var message = 'Failed to save schedule.'
                            if (result.data && result.data.message) {
                                message = result.data.message
                            } else if (result.data && result.data.errors) {
                                var all = []
                                Object.keys(result.data.errors).forEach(function (key) {
                                    var v = result.data.errors[key]
                                    if (Array.isArray(v)) {
                                        v.forEach(function (x) { all.push(String(x)) })
                                    } else if (v != null) {
                                        all.push(String(v))
                                    }
                                })
                                if (all.length) {
                                    message = all.join(' ')
                                }
                            } else if (result.status === 401) {
                                message = 'Session expired. Please log in again.'
                            } else if (result.status === 403) {
                                message = 'You do not have permission to manage schedules.'
                            }
                            showDoctorError(message)
                            return
                        }
                        var successMsg = currentScheduleId ? 'Slot updated.' : 'Slots generated.'
                        if (!currentScheduleId && result.data && (result.data.created != null || result.data.updated != null)) {
                            var created = parseInt(result.data.created || 0, 10)
                            var updated = parseInt(result.data.updated || 0, 10)
                            if (isNaN(created)) created = 0
                            if (isNaN(updated)) updated = 0
                            successMsg = 'Slots generated. Created ' + created + ', updated ' + updated + '.'
                        }
                        showDoctorSuccess(successMsg)
                        
                        // Reset form
                        clear12HourSelects('start')
                        clear12HourSelects('end')
                        if (scheduleMax) scheduleMax.value = ''
                        if (scheduleRoom) scheduleRoom.value = ''
                        if (scheduleFromDay) scheduleFromDay.value = ''
                        if (scheduleToDay) scheduleToDay.value = ''
                        if (scheduleSubmitLabel) scheduleSubmitLabel.textContent = 'Generate schedule'
                        currentScheduleId = null
                        loadSchedulesForDoctor(currentDoctorIdForSchedule)
                        loadDoctors()
                    })
                    .catch(function () {
                        showDoctorError('Network error while saving schedule.')
                    })
                    .finally(function () {
                        setScheduleSubmitting(false)
                    })
            })
        }

        var timetableVisible = false
        var timetableBtn = document.getElementById('adminScheduleTimeTableViewBtn')
        if (timetableBtn) {
            timetableBtn.addEventListener('click', function () {
                timetableVisible = !timetableVisible
                var listEl = document.getElementById('adminDoctorScheduleList')
                var ttEl = document.getElementById('adminDoctorTimeTableView')
                if (!listEl || !ttEl) return
                if (timetableVisible) {
                    listEl.classList.add('hidden')
                    ttEl.classList.remove('hidden')
                    timetableBtn.textContent = 'Day view'
                } else {
                    listEl.classList.remove('hidden')
                    ttEl.classList.add('hidden')
                    timetableBtn.textContent = 'Time Table view'
                }
            })
        }

        function renderTimeTableView(schedules) {
            var ttEl = document.getElementById('adminDoctorTimeTableView')
            if (!ttEl) return

            if (!schedules || !schedules.length) {
                ttEl.innerHTML = '<div class="text-[0.78rem] text-slate-500 p-4 text-center">No schedules to display.</div>'
                return
            }

            var dayOrder = ['mon','tue','wed','thu','fri','sat','sun']
            var dayLabels = { mon:'Monday', tue:'Tuesday', wed:'Wednesday', thu:'Thursday', fri:'Friday', sat:'Saturday', sun:'Sunday' }
            var dayColors = {
                mon: { bg: '#eff6ff', head: '#dbeafe', text: '#1e40af', border: '#bfdbfe' },
                tue: { bg: '#fefce8', head: '#fef9c3', text: '#854d0e', border: '#fde68a' },
                wed: { bg: '#f0fdf4', head: '#dcfce7', text: '#166534', border: '#bbf7d0' },
                thu: { bg: '#fdf4ff', head: '#fae8ff', text: '#86198f', border: '#f5d0fe' },
                fri: { bg: '#fff7ed', head: '#ffedd5', text: '#9a3412', border: '#fed7aa' },
                sat: { bg: '#f1f5f9', head: '#e2e8f0', text: '#334155', border: '#cbd5e1' },
                sun: { bg: '#fef2f2', head: '#fecaca', text: '#991b1b', border: '#fecaca' }
            }

            // Collect all unique time slots across the week
            var allSlots = []
            var seenTimes = {}
            for (var i = 0; i < schedules.length; i++) {
                var s = schedules[i]
                var start = (s.start_time || '').slice(0, 5)
                var end = (s.end_time || '').slice(0, 5)
                var tkey = start + '-' + end
                if (!seenTimes[tkey]) {
                    seenTimes[tkey] = true
                    allSlots.push({ start: start, end: end, label: formatTimeCompact(start) + '–' + formatTimeCompact(end) })
                }
            }
            allSlots.sort(function (a, b) { if (a.start < b.start) return -1; if (a.start > b.start) return 1; return 0 })

            // Build lookup: day_key + time_key -> schedule
            var slotMap = {}
            for (var j = 0; j < schedules.length; j++) {
                var s2 = schedules[j]
                var dk = (s2.day_of_week || '').toLowerCase()
                var st = (s2.start_time || '').slice(0, 5)
                var et = (s2.end_time || '').slice(0, 5)
                var tk = st + '-' + et
                slotMap[dk + '|' + tk] = s2
            }

            var html = '<div class="overflow-x-auto"><table class="w-full text-[0.72rem] border-collapse">'
            // Header row
            html += '<tr><td class="p-1.5 font-semibold text-slate-600 text-center border" style="min-width:70px">Time</td>'
            dayOrder.forEach(function (dk) {
                var c = dayColors[dk] || dayColors.mon
                html += '<td class="p-1.5 font-semibold text-center border" style="background:' + c.head + ';color:' + c.text + ';border-color:' + c.border + ';min-width:110px">' + dayLabels[dk] + '</td>'
            })
            html += '</tr>'

            // Body rows
            allSlots.forEach(function (slot) {
                html += '<tr>'
                html += '<td class="p-1.5 text-center font-medium text-slate-600 border bg-slate-50/80">' + slot.label + '</td>'
                dayOrder.forEach(function (dk) {
                    var c = dayColors[dk] || dayColors.mon
                    var sch = slotMap[dk + '|' + slot.start + '-' + slot.end]
                    if (sch) {
                        var isUnavail = sch.is_available === false
                        var txt = sch.room_number != null ? 'Room ' + sch.room_number : ''
                        if (sch.max_patients != null) txt += (txt ? ' | ' : '') + 'Max ' + sch.max_patients
                        var statusLabel = isUnavail ? 'Unavailable' : 'Available'
                        html += '<td class="p-1.5 text-center border" style="background:' + c.bg + ';border-color:' + c.border + '">' +
                            '<div class="font-semibold" style="color:' + c.text + '">' +
                                '<span class="inline-flex items-center justify-center w-2 h-2 rounded-full ' + (isUnavail ? 'bg-rose-500' : 'bg-emerald-500') + ' mr-1"></span>' +
                                statusLabel +
                            '</div>' +
                            (txt ? '<div class="text-[0.65rem] text-slate-500 mt-0.5">' + txt + '</div>' : '') +
                        '</td>'
                    } else {
                        html += '<td class="p-1.5 text-center border" style="background:' + c.bg + ';border-color:' + c.border + '"><span class="text-slate-300">—</span></td>'
                    }
                })
                html += '</tr>'
            })

            html += '</table></div>'
            ttEl.innerHTML = html
        }

        function showStaffPage(page) {
            var total = staffRows.length
            var totalPages = Math.ceil(total / staffPerPage) || 1
            if (page < 1 || page > totalPages) return
            staffPage = page
            var start = (page - 1) * staffPerPage
            var end = Math.min(start + staffPerPage, total)

            staffRows.forEach(function (row, i) {
                row.style.display = (i >= start && i < end) ? '' : 'none'
            })

            renderStaffPagination()
        }

        function renderStaffPagination() {
            var pagination = document.getElementById('adminStaffPagination')
            if (!pagination) return
            var total = staffRows.length
            var totalPages = Math.ceil(total / staffPerPage) || 1

            if (total === 0) {
                pagination.innerHTML = '<span class="text-[0.7rem] text-slate-300">No entries</span>'
                return
            }

            var html = '<span class="text-[0.7rem] text-slate-400 mr-2">' + total + ' entries</span>'

            if (totalPages <= 1) {
                pagination.innerHTML = html
                return
            }

            // Prev
            html += '<button type="button" class="px-2 py-1 text-[0.72rem] font-semibold rounded-md border border-slate-200 ' +
                (staffPage === 1 ? 'text-slate-300 cursor-default' : 'text-slate-600 hover:bg-slate-50 cursor-pointer') +
                '" data-page="prev"' + (staffPage === 1 ? ' disabled' : '') + '>‹ Prev</button>'
            // Page numbers
            for (var i = 1; i <= totalPages; i++) {
                html += '<button type="button" class="px-2 py-1 text-[0.72rem] font-semibold rounded-md border ' +
                    (i === staffPage ? 'bg-green-600 text-white border-green-600' : 'border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer') +
                    '" data-page="' + i + '">' + i + '</button>'
            }
            // Next
            html += '<button type="button" class="px-2 py-1 text-[0.72rem] font-semibold rounded-md border border-slate-200 ' +
                (staffPage === totalPages ? 'text-slate-300 cursor-default' : 'text-slate-600 hover:bg-slate-50 cursor-pointer') +
                '" data-page="next"' + (staffPage === totalPages ? ' disabled' : '') + '>Next ›</button>'
            pagination.innerHTML = html

            pagination.querySelectorAll('button[data-page]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var p = btn.getAttribute('data-page')
                    if (p === 'prev' && staffPage > 1) { staffPage--; showStaffPage(staffPage) }
                    else if (p === 'next' && staffPage < totalPages) { staffPage++; showStaffPage(staffPage) }
                    else if (p !== 'prev' && p !== 'next') showStaffPage(parseInt(p, 10))
                })
            })
        }

        // Wrapper for filter/sort — resets to page 1
        var renderDoctorsWithReset = function () {
            staffPage = 1
            renderDoctors()
        }

        // Call loadDoctors once
        setTimeout(function () { loadDoctors() }, 0)
    })()
</script>