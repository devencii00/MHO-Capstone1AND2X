<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900"></h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Doctor</span>
    </div>
    <p class="text-xs text-slate-500 mb-4">
   
    </p>

    <div class="flex flex-col md:flex-row gap-5">
        {{-- Side Nav --}}
        <div class="flex md:flex-col gap-2 shrink-0">
            <button type="button" id="doctorSettingsTabProfile" class="doctor-settings-tab-btn px-4 py-2.5 rounded-xl text-[0.78rem] font-semibold text-left transition-colors border whitespace-nowrap border-green-500/40 bg-green-50 text-green-700 hover:bg-green-100">
                <span class="inline-flex items-center gap-2"><x-lucide-user class="w-[16px] h-[16px]" /> Edit Profile</span>
            </button>
            <button type="button" id="doctorSettingsTabSignature" class="doctor-settings-tab-btn px-4 py-2.5 rounded-xl text-[0.78rem] font-semibold text-left transition-colors border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                <span class="inline-flex items-center gap-2"><x-lucide-pen-line class="w-[16px] h-[16px]" /> Signature</span>
            </button>
            <button type="button" id="doctorSettingsTabSchedule" class="doctor-settings-tab-btn px-4 py-2.5 rounded-xl text-[0.78rem] font-semibold text-left transition-colors border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                <span class="inline-flex items-center gap-2"><x-lucide-calendar class="w-[16px] h-[16px]" /> My Schedule</span>
            </button>
            <button type="button" id="doctorSettingsTabPassword" class="doctor-settings-tab-btn px-4 py-2.5 rounded-xl text-[0.78rem] font-semibold text-left transition-colors border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                <span class="inline-flex items-center gap-2"><x-lucide-lock class="w-[16px] h-[16px]" /> Change Password</span>
            </button>
        </div>

        {{-- Content Area --}}
        <div class="flex-1 min-w-0">
            {{-- ===== EDIT PROFILE PANEL ===== --}}
            <div id="doctorSettingsProfilePanel" class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-900">Edit Profile</h3>
                        <p class="text-[0.7rem] text-slate-500">Update your name, profile picture, and personal details.</p>
                    </div>
                    <x-lucide-user class="w-[18px] h-[18px] text-slate-700" />
                </div>

                <form id="doctorSettingsProfileForm" class="space-y-4">
                    {{-- Profile picture --}}
                    <div class="flex items-center gap-4">
                        <div class="relative w-16 h-16 rounded-full border-2 border-slate-200 overflow-hidden bg-slate-100 flex-shrink-0">
                            <img id="doctorSettingsProfilePreview" src="" alt="Profile" class="w-full h-full object-cover hidden">
                            <div id="doctorSettingsProfilePlaceholder" class="w-full h-full flex items-center justify-center text-slate-400">
                                <x-lucide-user class="w-8 h-8" />
                            </div>
                        </div>
                        <div>
                            <button type="button" id="doctorSettingsProfileUploadBtn" class="inline-flex items-center gap-2 rounded-xl border border-green-500/40 bg-green-50 px-3 py-1.5 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100">
                                <x-lucide-camera class="w-[14px] h-[14px]" />
                                Upload photo
                            </button>
                            <input id="doctorSettingsProfileUploadInput" type="file" accept="image/*" class="hidden">
                            <p class="text-[0.65rem] text-slate-400 mt-1">JPG, PNG. Max 5MB.</p>
                        </div>
                    </div>

                    {{-- Name fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="doctor_settings_lastname" class="block text-[0.7rem] text-slate-500 mb-1">Last name</label>
                            <input id="doctor_settings_lastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="doctor_settings_firstname" class="block text-[0.7rem] text-slate-500 mb-1">First name</label>
                            <input id="doctor_settings_firstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="doctor_settings_middlename" class="block text-[0.7rem] text-slate-500 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                            <input id="doctor_settings_middlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                        </div>
                    </div>

                    {{-- Specialization (display only) --}}
                    <div>
                        <label class="block text-[0.7rem] text-slate-500 mb-1">Specialization</label>
                        <div class="w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-xs text-slate-600 cursor-default select-none">
                            <span id="doctor_settings_specialization_display">—</span>
                        </div>
                    </div>

                    {{-- Sex + Birthdate --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[0.7rem] text-slate-500 mb-1">Sex</label>
                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="doctorSettingsSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="doctorSettingsSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="doctor_settings_birthdate" class="block text-[0.7rem] text-slate-500 mb-1">Birthdate</label>
                            <input id="doctor_settings_birthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="doctor_settings_contact" class="block text-[0.7rem] text-slate-500 mb-1">Contact number</label>
                            <input id="doctor_settings_contact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="09xx xxx xxxx">
                        </div>
                    </div>

                    <hr class="border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="doctor_settings_prc" class="block text-[0.7rem] text-slate-500 mb-1">PRC License Number</label>
                            <input id="doctor_settings_prc" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="7-digit number" maxlength="7">
                        </div>
                        <div>
                            <label for="doctor_settings_phic" class="block text-[0.7rem] text-slate-500 mb-1">PHIC Number</label>
                            <input id="doctor_settings_phic" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                        </div>
                    </div>
                    <div>
                        <label for="doctor_settings_ptr" class="block text-[0.7rem] text-slate-500 mb-1">PTR Number</label>
                        <input id="doctor_settings_ptr" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <hr class="border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="doctor_settings_emergency_contact" class="block text-[0.7rem] text-slate-500 mb-1">Emergency contact (name)</label>
                            <input id="doctor_settings_emergency_contact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="doctor_settings_emergency_contact_number" class="block text-[0.7rem] text-slate-500 mb-1">Emergency contact number</label>
                            <input id="doctor_settings_emergency_contact_number" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="doctor_settings_address" class="block text-[0.7rem] text-slate-500 mb-1">Address</label>
                        <input id="doctor_settings_address" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>

                    <div class="flex items-center justify-end pt-1">
                        <button type="submit" id="doctorSettingsProfileSave" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-3 py-2 text-[0.78rem] font-semibold text-white hover:bg-green-700 disabled:opacity-60 disabled:hover:bg-green-600">
                            <span id="doctorSettingsProfileSaveSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            Save profile
                        </button>
                    </div>
                </form>
            </div>

            {{-- ===== SIGNATURE PANEL ===== --}}
            <div id="doctorSettingsSignaturePanel" class="hidden rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-900">Signature</h3>
                        <p class="text-[0.7rem] text-slate-500">Upload your signature image for use on receipts and documents.</p>
                    </div>
                    <x-lucide-pen-line class="w-[18px] h-[18px] text-slate-700" />
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="flex flex-col items-center">
                        <div id="doctorSettingsSignaturePreviewWrap" class="w-56 h-28 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden mb-3">
                            <img id="doctorSettingsSignaturePreview" src="" alt="Signature" class="h-full w-full object-contain hidden">
                            <div id="doctorSettingsSignaturePlaceholder" class="text-[0.72rem] text-slate-400">No signature uploaded</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" id="doctorSettingsSignatureUploadBtn" class="inline-flex items-center gap-2 rounded-xl border border-green-500/40 bg-green-50 px-3 py-1.5 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100">
                                <x-lucide-upload class="w-[14px] h-[14px]" />
                                Upload signature
                            </button>
                            <button type="button" id="doctorSettingsSignatureSave" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-3 py-1.5 text-[0.72rem] font-semibold text-white hover:bg-green-700 disabled:opacity-60 disabled:hover:bg-green-600">
                                <span id="doctorSettingsSignatureSaveSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                                Save
                            </button>
                        </div>
                        <input id="doctorSettingsSignatureUploadInput" type="file" accept="image/*" class="hidden">
                        <p class="text-[0.65rem] text-slate-400 mt-2">Upload a clear image of your signature. JPG, PNG. Max 2MB.</p>
                    </div>
                </div>
            </div>

            {{-- ===== MY SCHEDULE PANEL ===== --}}
            <div id="doctorSettingsSchedulePanel" class="hidden rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-900">My Schedule</h3>
                        <p class="text-[0.7rem] text-slate-500">Manage your weekly schedule — edit room/patient limits or mark slots as unavailable (resets daily).</p>
                    </div>
                    <x-lucide-calendar class="w-[18px] h-[18px] text-slate-700" />
                </div>

                <div id="doctorScheduleError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>
                <div id="doctorScheduleSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-[0.75rem] text-emerald-700"></div>

                {{-- Set Schedule Card --}}
                <div class="rounded-xl border border-slate-200 bg-white p-4 mb-4">
                    <h4 class="text-[0.72rem] font-semibold text-slate-900 mb-3 flex items-center gap-2">
                        <x-lucide-pen-line class="w-[14px] h-[14px]" />
                        Toggle Availability
                    </h4>
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="w-full sm:flex-1 min-w-0">
                            <label class="block text-[0.7rem] text-slate-600 mb-1">Day</label>
                            <select id="doctor_toggle_day" class="hidden"><option value="">Select</option><option value="mon">Mon</option><option value="tue">Tue</option><option value="wed">Wed</option><option value="thu">Thu</option><option value="fri">Fri</option><option value="sat">Sat</option><option value="sun">Sun</option></select>
                            <div id="doctor_toggle_day_roller" class="day-roller flex flex-wrap gap-1.5">
                                <button type="button" class="day-roller-btn flex-shrink-0 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-300 whitespace-nowrap transition-colors cursor-pointer" data-value="mon">Mon</button>
                                <button type="button" class="day-roller-btn flex-shrink-0 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-300 whitespace-nowrap transition-colors cursor-pointer" data-value="tue">Tue</button>
                                <button type="button" class="day-roller-btn flex-shrink-0 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-300 whitespace-nowrap transition-colors cursor-pointer" data-value="wed">Wed</button>
                                <button type="button" class="day-roller-btn flex-shrink-0 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-300 whitespace-nowrap transition-colors cursor-pointer" data-value="thu">Thu</button>
                                <button type="button" class="day-roller-btn flex-shrink-0 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-300 whitespace-nowrap transition-colors cursor-pointer" data-value="fri">Fri</button>
                                <button type="button" class="day-roller-btn flex-shrink-0 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-300 whitespace-nowrap transition-colors cursor-pointer" data-value="sat">Sat</button>
                                <button type="button" class="day-roller-btn flex-shrink-0 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 font-medium hover:bg-slate-50 hover:border-slate-300 whitespace-nowrap transition-colors cursor-pointer" data-value="sun">Sun</button>
                            </div>
                        </div>
                        <div class="w-full sm:w-[140px]">
                            <label for="doctor_toggle_start" class="block text-[0.7rem] text-slate-600 mb-1">Start</label>
                            <input type="text" id="doctor_toggle_start" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. 8am">
                        </div>
                        <div class="w-full sm:w-[140px]">
                            <label for="doctor_toggle_end" class="block text-[0.7rem] text-slate-600 mb-1">End</label>
                            <input type="text" id="doctor_toggle_end" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="e.g. 5pm">
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex gap-2">
                                <button type="button" id="doctorToggleUnavailable" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-colors disabled:opacity-60">
                                    <x-lucide-x-circle class="w-[14px] h-[14px]" />
                                    Unavailable
                                </button>
                                <button type="button" id="doctorToggleAvailable" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors disabled:opacity-60">
                                    <x-lucide-check-circle class="w-[14px] h-[14px]" />
                                    Available
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Weekly Timetable --}}
                <div class="border-t border-slate-100 pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-semibold text-slate-900">Weekly Schedule</h4>
                        <div class="flex items-center gap-2">
                            <span class="inline-block rounded-full bg-slate-100 px-2.5 py-0.5 text-[0.6rem] text-slate-500 font-medium">Click cells to select, click again to deselect</span>
                            <button type="button" id="doctorScheduleTtRefreshBtn" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-orange-200 bg-orange-50 text-[0.72rem] font-semibold text-orange-700 hover:bg-orange-100 transition-colors">
                                <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                                Refresh
                            </button>
                        </div>
                    </div>
                    <div id="doctorTimeTableView" class="mt-2"></div>
                </div>
            </div>

            {{-- ===== CHANGE PASSWORD PANEL ===== --}}
            <div id="doctorSettingsPasswordPanel" class="hidden rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-900">Account password</h3>
                        <p class="text-[0.7rem] text-slate-500">Verify your current password before setting a new one.</p>
                    </div>
                    <x-lucide-lock class="w-[18px] h-[18px] text-slate-700" />
                </div>

                <div id="doctorSettingsAccountIdle" class="rounded-2xl border border-slate-200 bg-white p-4">
                    <button type="button" id="doctorSettings_account_start" class="inline-flex items-center gap-2 rounded-xl border border-green-500/40 bg-green-50 px-3 py-2 text-[0.78rem] font-semibold text-green-700 hover:bg-green-100">
                        <x-lucide-key class="w-[18px] h-[18px]" />
                        Change password
                    </button>
                </div>

                <div id="doctorSettingsAccountVerifyStep" class="hidden rounded-2xl border border-slate-200 bg-white p-4 mt-3">
                    <div class="text-[0.72rem] font-semibold text-slate-900 mb-3">Verify current password</div>
                    <div>
                        <label for="doctorSettings_current_password" class="block text-[0.7rem] text-slate-500 mb-1">Current password</label>
                        <input id="doctorSettings_current_password" type="password" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <div class="mt-3 flex items-center justify-end gap-2">
                        <button type="button" id="doctorSettings_account_cancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button type="button" id="doctorSettings_account_verify" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-700 text-white text-[0.78rem] font-semibold hover:bg-green-600">
                            <span id="doctorSettingsAccountVerifySpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            <span id="doctorSettingsAccountVerifyLabel">Verify</span>
                        </button>
                    </div>
                </div>

                <div id="doctorSettingsAccountChangeStep" class="hidden rounded-2xl border border-slate-200 bg-white p-4 mt-3">
                    <div class="text-[0.72rem] font-semibold text-slate-900 mb-3">Set new password</div>
                    <div class="space-y-3">
                        <div>
                            <label for="doctorSettings_new_password" class="block text-[0.7rem] text-slate-500 mb-1">New password</label>
                            <input id="doctorSettings_new_password" type="password" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="doctorSettings_confirm_password" class="block text-[0.7rem] text-slate-500 mb-1">Confirm new password</label>
                            <input id="doctorSettings_confirm_password" type="password" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-end gap-2">
                        <button type="button" id="doctorSettings_account_back" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Back</button>
                        <button type="button" id="doctorSettings_account_save" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">
                            <span id="doctorSettingsAccountSaveSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            Save new password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="doctorSettingsConfirmOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                <x-lucide-info class="w-[18px] h-[18px]" />
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-slate-900">Confirm</div>
                <div id="doctorSettingsConfirmMessage" class="text-[0.78rem] text-slate-600 mt-0.5">Are you sure?</div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-end gap-2">
            <button type="button" id="doctorSettingsConfirmCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="button" id="doctorSettingsConfirmOk" class="px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">Confirm</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentUserId = null
        var pendingProfileFile = null
        var pendingSignatureFile = null

        // ── Tab switching ──
        var tabProfile = document.getElementById('doctorSettingsTabProfile')
        var tabSignature = document.getElementById('doctorSettingsTabSignature')
        var tabSchedule = document.getElementById('doctorSettingsTabSchedule')
        var tabPassword = document.getElementById('doctorSettingsTabPassword')
        var panelProfile = document.getElementById('doctorSettingsProfilePanel')
        var panelSignature = document.getElementById('doctorSettingsSignaturePanel')
        var panelSchedule = document.getElementById('doctorSettingsSchedulePanel')
        var panelPassword = document.getElementById('doctorSettingsPasswordPanel')

        function setActiveSettingsTab(tab) {
            var btns = [tabProfile, tabSignature, tabSchedule, tabPassword]
            btns.forEach(function (btn) {
                if (!btn) return
                btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-500/40', 'bg-white', 'text-slate-700', 'border-slate-200')
                btn.classList.add('border', 'border-slate-200', 'bg-white', 'text-slate-700', 'hover:bg-slate-50')
            })
            var active = null
            var isProfile = false, isSignature = false, isSchedule = false, isPassword = false
            if (tab === 'profile') { active = tabProfile; isProfile = true }
            else if (tab === 'signature') { active = tabSignature; isSignature = true }
            else if (tab === 'schedule') { active = tabSchedule; isSchedule = true }
            else { active = tabPassword; isPassword = true }
            if (active) {
                active.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                active.classList.add('bg-green-50', 'text-green-700', 'border-green-500/40')
            }
            if (panelProfile) panelProfile.classList.toggle('hidden', !isProfile)
            if (panelSignature) panelSignature.classList.toggle('hidden', !isSignature)
            if (panelSchedule) panelSchedule.classList.toggle('hidden', !isSchedule)
            if (panelPassword) panelPassword.classList.toggle('hidden', !isPassword)
            // Load schedule data when tab is activated
            if (isSchedule) loadSchedulesForDoctor()
        }

        if (tabProfile) tabProfile.addEventListener('click', function () { setActiveSettingsTab('profile') })
        if (tabSignature) tabSignature.addEventListener('click', function () { setActiveSettingsTab('signature') })
        if (tabSchedule) tabSchedule.addEventListener('click', function () { setActiveSettingsTab('schedule') })
        if (tabPassword) tabPassword.addEventListener('click', function () { setActiveSettingsTab('password') })
        setActiveSettingsTab('profile')

        // ── Common helpers ──
        function esc(s) { if (s == null) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;') }

        function showAccountError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showAccountNotice(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        // ── Schedule Management ────────────────────────────────────
        var scheduleEls = {
            error: document.getElementById('doctorScheduleError'),
            success: document.getElementById('doctorScheduleSuccess'),
            timetableEl: document.getElementById('doctorTimeTableView'),
            ttRefreshBtn: document.getElementById('doctorScheduleTtRefreshBtn'),
            toggleDay: document.getElementById('doctor_toggle_day'),
            toggleStart: document.getElementById('doctor_toggle_start'),
            toggleEnd: document.getElementById('doctor_toggle_end'),
            toggleUnavail: document.getElementById('doctorToggleUnavailable'),
            toggleAvail: document.getElementById('doctorToggleAvailable')
        };
        var loadedSchedules = [];

        function showScheduleError(msg) { if (scheduleEls.error) { scheduleEls.error.textContent = msg; scheduleEls.error.classList.toggle('hidden', !msg); } }
        function showScheduleSuccess(msg) { if (scheduleEls.success) { scheduleEls.success.textContent = msg; scheduleEls.success.classList.toggle('hidden', !msg); } }

        // ── Helpers ──
        function pad2(n) { return n < 10 ? '0' + n : '' + n; }

        function normalizeDayKey(raw) {
            if (!raw || typeof raw !== 'string') return '';
            var map = { mon:'mon',mon:'mon',tue:'tue',tue:'tue',wed:'wed',wed:'wed',thu:'thu',thu:'thu',fri:'fri',fri:'fri',sat:'sat',sat:'sat',sun:'sun',sun:'sun',
                monday:'mon',tuesday:'tue',wednesday:'wed',thursday:'thu',friday:'fri',saturday:'sat',sunday:'sun',
                '0':'sun','1':'mon','2':'tue','3':'wed','4':'thu','5':'fri','6':'sat' };
            var lower = raw.toLowerCase().trim();
            return map[lower] || lower;
        }

        function timeToMinutes(t) {
            if (!t || typeof t !== 'string') return null;
            var parts = t.split(':');
            if (parts.length < 2) return null;
            var h = parseInt(parts[0], 10), m = parseInt(parts[1], 10);
            if (isNaN(h) || isNaN(m)) return null;
            return h * 60 + m;
        }

        function minutesToTime(m) { return pad2(Math.floor(m / 60)) + ':' + pad2(m % 60); }

        function formatTimeCompact(hhmm) {
            if (!hhmm || typeof hhmm !== 'string') return '';
            var parts = hhmm.split(':');
            var h = parseInt(parts[0], 10), m = parseInt(parts[1], 10);
            if (isNaN(h)) return hhmm;
            var ap = h >= 12 ? 'PM' : 'AM';
            var h12 = h === 0 ? 12 : (h > 12 ? h - 12 : h);
            return h12 + (m > 0 ? ':' + pad2(m) : '') + ap;
        }

        // ── Day Roller ──
        function syncDayRoller(selectId, rollerId) {
            var select = document.getElementById(selectId), roller = document.getElementById(rollerId);
            if (!select || !roller) return;
            var value = select.value || '';
            roller.querySelectorAll('.day-roller-btn').forEach(function(btn) {
                var isSelected = btn.getAttribute('data-value') === value;
                btn.classList.toggle('active', isSelected);
                if (isSelected) {
                    btn.style.cssText = 'border-color:#16a34a;background:#f0fdf4;color:#166534;';
                } else {
                    btn.style.cssText = '';
                }
            });
        }
        function setupDayRoller(selectId, rollerId) {
            var roller = document.getElementById(rollerId);
            if (!roller) return;
            roller.addEventListener('click', function(e) {
                var btn = e.target.closest('.day-roller-btn');
                if (!btn) return;
                var select = document.getElementById(selectId);
                if (!select) return;
                var val = btn.getAttribute('data-value') || '';
                select.value = val;
                syncDayRoller(selectId, rollerId);
            });
            syncDayRoller(selectId, rollerId);
        }

        // ── Store valid schedule hours for time input validation ──
        var validScheduleHours = {};

        function storeScheduleHours(schedules) {
            validScheduleHours = {};
            for (var i = 0; i < schedules.length; i++) {
                var st = timeToMinutes(schedules[i].start_time);
                var en = timeToMinutes(schedules[i].end_time);
                if (st !== null) { validScheduleHours[st] = true; }
                if (en !== null) { validScheduleHours[en] = true; }
            }
        }

        // ── Parse time text input like "8am", "2PM", "12pm" → minutes or null ──
        function parseTimeInput(text) {
            if (!text || typeof text !== 'string') return null;
            var trimmed = text.trim().toLowerCase();
            var match = trimmed.match(/^(\d{1,2})\s*(am|pm)$/);
            if (!match) return null;
            var h = parseInt(match[1], 10);
            var mer = match[2];
            if (h < 1 || h > 12) return null;
            if (mer === 'pm' && h !== 12) h += 12;
            if (mer === 'am' && h === 12) h = 0;
            return h * 60;
        }

        // ── Format minutes back to display text like "8am", "2pm" ──
        function formatTimeToAmPm(mins) {
            if (mins == null) return '';
            var hh = Math.floor(mins / 60);
            var mm = mins % 60;
            var ap = hh >= 12 ? 'pm' : 'am';
            var h12 = hh === 0 ? 12 : (hh > 12 ? hh - 12 : hh);
            return h12 + (mm ? ':' + (mm < 10 ? '0' : '') + mm : '') + ap;
        }

        // ── Filter day roller to only scheduled days ──
        function filterDayRollerBySchedule(schedules) {
            var scheduledDays = {};
            for (var i = 0; i < schedules.length; i++) {
                var dk = normalizeDayKey(schedules[i].day_of_week);
                if (dk) scheduledDays[dk] = true;
            }
            var roller = document.getElementById('doctor_toggle_day_roller');
            if (!roller) return;
            var btns = roller.querySelectorAll('.day-roller-btn');
            for (var j = 0; j < btns.length; j++) {
                var btn = btns[j];
                var val = btn.getAttribute('data-value') || '';
                if (scheduledDays[val]) {
                    btn.style.display = '';
                } else {
                    btn.style.display = 'none';
                }
            }
        }

        // ── Validate toggle inputs ──
        function getToggleInputs() {
            var day = scheduleEls.toggleDay ? scheduleEls.toggleDay.value : '';
            var startText = scheduleEls.toggleStart ? scheduleEls.toggleStart.value : '';
            var endText = scheduleEls.toggleEnd ? scheduleEls.toggleEnd.value : '';
            var startMin = parseTimeInput(startText);
            var endMin = parseTimeInput(endText);
            return { day: normalizeDayKey(day), start: startMin, end: endMin, startText: startText, endText: endText };
        }

        // ── Validate time against schedule ──
        function isValidScheduleTime(mins) {
            if (mins == null) return false;
            return validScheduleHours[mins] === true;
        }

        // ── Load schedules with pagination ──
        function loadSchedulesForDoctor(callback) {
            loadedSchedules = [];
            if (scheduleEls.timetableEl) scheduleEls.timetableEl.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-6">Loading schedules...</div>';
            var page = 1, allItems = [];

            function fetchPage() {
                apiFetch("{{ url('/api/doctor-schedules') }}?page=" + page + "&per_page=100", { method: 'GET' })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        var items = [];
                        if (data.data && Array.isArray(data.data)) {
                            items = data.data;
                        } else if (Array.isArray(data)) {
                            items = data;
                        }
                        allItems = allItems.concat(items);
                        if (data.next_page_url) {
                            page++;
                            fetchPage();
                        } else {
                            loadedSchedules = allItems;
                            filterDayRollerBySchedule(loadedSchedules);
                            storeScheduleHours(loadedSchedules);
                            renderTimeTableView();
                            if (typeof callback === 'function') callback();
                        }
                    })
                    .catch(function() {
                        if (scheduleEls.timetableEl) scheduleEls.timetableEl.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-6">Failed to load schedules.</div>';
                    });
            }
            fetchPage();
        }

        // ── Clickable timetable selection ──
        var selectedSlots = [];

        function getSlotKey(day, startMin, endMin) { return day + '|' + startMin + '|' + endMin; }

        function toggleSlotSelection(day, startMin, endMin) {
            var key = getSlotKey(day, startMin, endMin);
            var idx = -1;
            for (var i = 0; i < selectedSlots.length; i++) {
                if (getSlotKey(selectedSlots[i].day, selectedSlots[i].startMin, selectedSlots[i].endMin) === key) {
                    idx = i;
                    break;
                }
            }
            if (idx >= 0) {
                selectedSlots.splice(idx, 1);
            } else {
                selectedSlots.push({ day: day, startMin: startMin, endMin: endMin });
            }
            updateToggleFromSelection();
            // Re-render to update visual state
            renderTimeTableView();
        }

        function clearSlotSelection() {
            selectedSlots = [];
            updateToggleFromSelection();
            renderTimeTableView();
        }

        function updateToggleFromSelection() {
            if (!selectedSlots.length) {
                // Clear day roller
                if (scheduleEls.toggleDay) scheduleEls.toggleDay.value = '';
                syncDayRoller('doctor_toggle_day', 'doctor_toggle_day_roller');
                if (scheduleEls.toggleStart) scheduleEls.toggleStart.value = '';
                if (scheduleEls.toggleEnd) scheduleEls.toggleEnd.value = '';
                return;
            }

            // Check if all selected slots share same day
            var firstDay = selectedSlots[0].day;
            var allSameDay = selectedSlots.every(function(s) { return s.day === firstDay; });

            if (allSameDay) {
                if (scheduleEls.toggleDay) {
                    scheduleEls.toggleDay.value = firstDay;
                    syncDayRoller('doctor_toggle_day', 'doctor_toggle_day_roller');
                }
            } else {
                // Different days — clear day selector
                if (scheduleEls.toggleDay) scheduleEls.toggleDay.value = '';
                syncDayRoller('doctor_toggle_day', 'doctor_toggle_day_roller');
            }

            // Compute min start and max end
            var minStart = selectedSlots[0].startMin;
            var maxEnd = selectedSlots[0].endMin;
            for (var i = 1; i < selectedSlots.length; i++) {
                if (selectedSlots[i].startMin < minStart) minStart = selectedSlots[i].startMin;
                if (selectedSlots[i].endMin > maxEnd) maxEnd = selectedSlots[i].endMin;
            }

            if (scheduleEls.toggleStart) {
                scheduleEls.toggleStart.value = formatTimeToAmPm(minStart);
            }
            if (scheduleEls.toggleEnd) {
                scheduleEls.toggleEnd.value = formatTimeToAmPm(maxEnd);
            }
        }

        // ── Render 1-hour block timetable ──
        function renderTimeTableView() {
            var ttEl = scheduleEls.timetableEl;
            if (!ttEl) return;
            if (!loadedSchedules || !loadedSchedules.length) {
                ttEl.innerHTML = '<div class="text-center text-[0.78rem] text-slate-400 py-6">No schedules set. Contact admin to set your base schedule.</div>';
                return;
            }

            var dayOrder = ['mon','tue','wed','thu','fri','sat','sun'];
            var dayLabels = { mon:'Monday', tue:'Tuesday', wed:'Wednesday', thu:'Thursday', fri:'Friday', sat:'Saturday', sun:'Sunday' };
            var dayColors = {
                mon: { bg: '#eff6ff', head: '#dbeafe', text: '#1e40af', border: '#bfdbfe' },
                tue: { bg: '#fefce8', head: '#fef9c3', text: '#854d0e', border: '#fde68a' },
                wed: { bg: '#f0fdf4', head: '#dcfce7', text: '#166534', border: '#bbf7d0' },
                thu: { bg: '#fdf4ff', head: '#fae8ff', text: '#86198f', border: '#f5d0fe' },
                fri: { bg: '#fff7ed', head: '#ffedd5', text: '#9a3412', border: '#fed7aa' },
                sat: { bg: '#f1f5f9', head: '#e2e8f0', text: '#334155', border: '#cbd5e1' },
                sun: { bg: '#fef2f2', head: '#fecaca', text: '#991b1b', border: '#fecaca' }
            };

            // Group schedules by day
            var daySchedules = {};
            var globalEarliest = 480, globalLatest = 1020;

            for (var i = 0; i < loadedSchedules.length; i++) {
                var s = loadedSchedules[i];
                var dk = normalizeDayKey(s.day_of_week);
                if (!daySchedules[dk]) daySchedules[dk] = [];
                daySchedules[dk].push(s);
                var stMin = timeToMinutes(s.start_time);
                var enMin = timeToMinutes(s.end_time);
                if (stMin !== null && stMin < globalEarliest) globalEarliest = stMin;
                if (enMin !== null && enMin > globalLatest) globalLatest = enMin;
            }

            // Round to hour boundaries
            globalEarliest = Math.floor(globalEarliest / 60) * 60;
            globalLatest = Math.ceil(globalLatest / 60) * 60;

            // Build 1-hour time rows
            var timeRows = [];
            for (var tm = globalEarliest; tm < globalLatest; tm += 60) {
                var rowStart = tm;
                var rowEnd = Math.min(tm + 60, globalLatest);
                var startStr = minutesToTime(rowStart);
                var endStr = minutesToTime(rowEnd);
                var label = formatTimeCompact(startStr) + '\u2013' + formatTimeCompact(endStr);
                timeRows.push({ start: startStr, end: endStr, startMin: rowStart, endMin: rowEnd, label: label });
            }

            var html = '<div class="overflow-x-auto"><table id="doctorTtTable" class="w-full text-[0.72rem] border-collapse">';
            html += '<tr><td class="p-1.5 font-semibold text-slate-600 text-center border bg-slate-50/80" style="min-width:70px">Time</td>';
            dayOrder.forEach(function(dk) {
                var c = dayColors[dk] || dayColors.mon;
                html += '<td class="p-1.5 font-semibold text-center border" style="background:' + c.head + ';color:' + c.text + ';border-color:' + c.border + ';min-width:110px">' + dayLabels[dk] + '</td>';
            });
            html += '</tr>';

            timeRows.forEach(function(row) {
                html += '<tr>';
                html += '<td class="p-1.5 text-center font-medium text-slate-600 border bg-slate-50/80">' + row.label + '</td>';
                dayOrder.forEach(function(dk) {
                    var c = dayColors[dk] || dayColors.mon;
                    var slots = daySchedules[dk] || [];
                    // Find all 30-min slots within this 1-hour block
                    var matching = [];
                    for (var j = 0; j < slots.length; j++) {
                        var sch = slots[j];
                        var sMin = timeToMinutes(sch.start_time);
                        var eMin = timeToMinutes(sch.end_time);
                        if (sMin !== null && eMin !== null && sMin >= row.startMin && eMin <= row.endMin) {
                            matching.push(sch);
                        }
                    }

                    if (matching.length) {
                        var allAvail = matching.every(function(sch) { return sch.is_available !== false; });
                        var statusLabel = allAvail ? 'Available' : 'Unavailable';
                        var statusColor = allAvail ? 'bg-emerald-500' : 'bg-rose-500';
                        var txtColor = allAvail ? c.text : '#991b1b';

                        var first = matching[0];
                        var txt = first.room_number != null ? 'Room ' + first.room_number : '';
                        if (first.max_patients != null) txt += (txt ? ' | ' : '') + 'Max ' + first.max_patients;

                        // Check if this cell is selected
                        var isSelected = false;
                        var selKey = getSlotKey(dk, row.startMin, row.endMin);
                        for (var si = 0; si < selectedSlots.length; si++) {
                            if (getSlotKey(selectedSlots[si].day, selectedSlots[si].startMin, selectedSlots[si].endMin) === selKey) {
                                isSelected = true;
                                break;
                            }
                        }

                        var selStyle = isSelected ? 'outline:3px solid #16a34a;outline-offset:-2px;' : '';
                        html += '<td class="p-1.5 text-center border cursor-pointer select-none transition-all hover:brightness-95" style="background:' + c.bg + ';border-color:' + c.border + ';' + selStyle + '" data-day="' + dk + '" data-start="' + row.startMin + '" data-end="' + row.endMin + '">' +
                            '<div class="font-semibold" style="color:' + txtColor + '">' +
                                '<span class="status-dot inline-flex items-center justify-center w-2 h-2 rounded-full ' + statusColor + ' mr-1"></span>' +
                                '<span class="status-label">' + statusLabel + '</span>' +
                            '</div>' +
                            (txt ? '<div class="text-[0.65rem] text-slate-500 mt-0.5">' + txt + '</div>' : '') +
                        '</td>';
                    } else {
                        html += '<td class="p-1.5 text-center border" style="background:' + c.bg + ';border-color:' + c.border + '"><span class="text-slate-300">\u2014</span></td>';
                    }
                });
                html += '</tr>';
            });

            html += '</table></div>';
            ttEl.innerHTML = html;

            // Attach click handler to the table
            var tbl = document.getElementById('doctorTtTable');
            if (tbl) {
                tbl.addEventListener('click', function(e) {
                    var td = e.target.closest('td[data-day]');
                    if (!td) return;
                    var day = td.getAttribute('data-day');
                    var start = parseInt(td.getAttribute('data-start'), 10);
                    var end = parseInt(td.getAttribute('data-end'), 10);
                    if (!isNaN(start) && !isNaN(end) && day) {
                        toggleSlotSelection(day, start, end);
                    }
                });
            }
        }

        // ── Update timetable cells in-place after toggle ──
        function updateTtCellsInPlace(isAvailable) {
            var tab = document.getElementById('doctorTtTable');
            if (!tab) return;
            for (var si = 0; si < selectedSlots.length; si++) {
                var sel = selectedSlots[si];
                var cell = tab.querySelector('td[data-day="' + sel.day + '"][data-start="' + sel.startMin + '"][data-end="' + sel.endMin + '"]');
                if (!cell) continue;
                var newDot = isAvailable ? 'bg-emerald-500' : 'bg-rose-500';
                var newLabel = isAvailable ? 'Available' : 'Unavailable';
                var newColor = isAvailable ? '' : '#991b1b';
                // Update dot
                var dot = cell.querySelector('.status-dot');
                if (dot) {
                    dot.className = 'status-dot inline-flex items-center justify-center w-2 h-2 rounded-full ' + newDot + ' mr-1';
                }
                // Update label text
                var labelEl = cell.querySelector('.status-label');
                if (labelEl) {
                    labelEl.textContent = newLabel;
                    if (newColor) labelEl.style.color = newColor;
                    else labelEl.style.color = '';
                }
                // Clear green selection outline
                cell.style.outline = '';
                cell.style.outlineOffset = '';
                // Update loadedSchedules in-memory
                for (var li = 0; li < loadedSchedules.length; li++) {
                    var ls = loadedSchedules[li];
                    var dk = normalizeDayKey(ls.day_of_week);
                    var sMin = timeToMinutes(ls.start_time);
                    var eMin = timeToMinutes(ls.end_time);
                    if (dk === sel.day && sMin >= sel.startMin && eMin <= sel.endMin) {
                        loadedSchedules[li].is_available = isAvailable;
                    }
                }
            }
            // Update validScheduleHours if marking as available (hours were previously invalid)
            storeScheduleHours(loadedSchedules);
            // Clear selection
            selectedSlots = [];
            updateToggleFromSelection();
        }

        // ── Toggle availability ──
        function toggleAvailability(isAvailable) {
            var inputs = getToggleInputs();
            if (!inputs.day) { showScheduleError('Select a day.'); return; }
            if (inputs.start == null || inputs.startText === '') { showScheduleError('Enter a valid start time (e.g. 8am).'); return; }
            if (inputs.end == null || inputs.endText === '') { showScheduleError('Enter a valid end time (e.g. 5pm).'); return; }
            if (!isValidScheduleTime(inputs.start)) { showScheduleError('Start time "' + inputs.startText + '" is not in your schedule. Valid times: ' + Object.keys(validScheduleHours).map(function(m) { return formatTimeToAmPm(parseInt(m,10)); }).join(', ') + '.'); return; }
            if (!isValidScheduleTime(inputs.end)) { showScheduleError('End time "' + inputs.endText + '" is not in your schedule.'); return; }
            if (inputs.start >= inputs.end) { showScheduleError('End time must be after start time.'); return; }

            showScheduleError('');
            var btn = isAvailable ? scheduleEls.toggleAvail : scheduleEls.toggleUnavail;
            if (btn) { btn.disabled = true; btn.classList.add('opacity-60'); }

            var startHhmm = pad2(Math.floor(inputs.start / 60)) + ':' + pad2(inputs.start % 60);
            var endHhmm = pad2(Math.floor(inputs.end / 60)) + ':' + pad2(inputs.end % 60);

            apiFetch("{{ url('/api/doctor-schedules/toggle-slot') }}", {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    day_of_week: inputs.day,
                    start_time: startHhmm,
                    end_time: endHhmm,
                    is_available: isAvailable
                })
            })
            .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, status: r.status, data: d }; }); })
            .then(function(result) {
                if (btn) { btn.disabled = false; btn.classList.remove('opacity-60'); }
                if (!result.ok) {
                    var msg = result.data && result.data.message ? result.data.message : 'Failed to update availability.';
                    showScheduleError(msg);
                    return;
                }
                showScheduleSuccess(result.data.message || (isAvailable ? 'Slots marked as available.' : 'Slots marked as unavailable.'));
                setTimeout(function() { showScheduleSuccess(''); }, 3000);
                // Update just the affected cells in-place, no full re-render
                updateTtCellsInPlace(isAvailable);
            })
            .catch(function() {
                if (btn) { btn.disabled = false; btn.classList.remove('opacity-60'); }
                showScheduleError('Network error.');
            });
        }

        // ── Wire up toggle buttons ──
        if (scheduleEls.toggleUnavail) {
            scheduleEls.toggleUnavail.addEventListener('click', function(e) { e.preventDefault(); toggleAvailability(false); });
        }
        if (scheduleEls.toggleAvail) {
            scheduleEls.toggleAvail.addEventListener('click', function(e) { e.preventDefault(); toggleAvailability(true); });
        }

        // ── Refresh button ──
        if (scheduleEls.ttRefreshBtn) {
            scheduleEls.ttRefreshBtn.addEventListener('click', function() { loadSchedulesForDoctor(); });
        }

        // ── Init day roller ──
        setupDayRoller('doctor_toggle_day', 'doctor_toggle_day_roller');

        // ── Confirm overlay ──
        var confirmOverlay = document.getElementById('doctorSettingsConfirmOverlay')
        var confirmMessage = document.getElementById('doctorSettingsConfirmMessage')
        var confirmOk = document.getElementById('doctorSettingsConfirmOk')
        var confirmCancel = document.getElementById('doctorSettingsConfirmCancel')
        var confirmResolver = null
        var confirmCountdownTimer = null
        var confirmOkOriginalText = null

        function stopConfirmCountdown() {
            if (confirmCountdownTimer) {
                clearInterval(confirmCountdownTimer)
                confirmCountdownTimer = null
            }
            if (confirmOk) {
                confirmOk.disabled = false
                confirmOk.classList.remove('opacity-60', 'cursor-not-allowed')
                if (confirmOkOriginalText != null) {
                    confirmOk.textContent = confirmOkOriginalText
                }
            }
            confirmOkOriginalText = null
        }

        function closeConfirm(result) {
            if (confirmOverlay) {
                confirmOverlay.classList.add('hidden')
                confirmOverlay.classList.remove('flex')
            }
            stopConfirmCountdown()
            var resolver = confirmResolver
            confirmResolver = null
            if (typeof resolver === 'function') resolver(!!result)
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
                confirmOk.textContent = confirmText
                confirmOkOriginalText = confirmText
                confirmResolver = resolve
                confirmOverlay.classList.remove('hidden')
                confirmOverlay.classList.add('flex')
                var countdownSeconds = options && options.countdownSeconds ? parseInt(String(options.countdownSeconds), 10) : 0
                if (!countdownSeconds || isNaN(countdownSeconds) || countdownSeconds < 1) return
                confirmOk.disabled = true
                confirmOk.classList.add('opacity-60', 'cursor-not-allowed')
                var remaining = countdownSeconds
                confirmOk.textContent = confirmText + ' (' + remaining + ')'
                confirmCountdownTimer = setInterval(function () {
                    remaining -= 1
                    if (remaining <= 0) { stopConfirmCountdown(); return }
                    if (confirmOk) confirmOk.textContent = confirmText + ' (' + remaining + ')'
                }, 1000)
            })
        }

        if (confirmOk) confirmOk.addEventListener('click', function () { closeConfirm(true) })
        if (confirmCancel) confirmCancel.addEventListener('click', function () { closeConfirm(false) })
        if (confirmOverlay) {
            confirmOverlay.addEventListener('click', function (e) {
                if (e.target === confirmOverlay) closeConfirm(false)
            })
        }

        // ── Load current user ──
        function loadCurrentUser() {
            if (typeof apiFetch !== 'function') return
            apiFetch("{{ url('/api/user') }}", { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, status: response.status, data: data }
                    }).catch(function () {
                        return { ok: response.ok, status: response.status, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) return
                    var user = result.data
                    currentUserId = user.user_id ? String(user.user_id) : null
                    var fn = document.getElementById('doctor_settings_firstname')
                    var mn = document.getElementById('doctor_settings_middlename')
                    var ln = document.getElementById('doctor_settings_lastname')
                    var bd = document.getElementById('doctor_settings_birthdate')
                    var sexRadios = document.querySelectorAll('input[name="doctorSettingsSex"]')
                    var addr = document.getElementById('doctor_settings_address')
                    var contact = document.getElementById('doctor_settings_contact')
                    var prc = document.getElementById('doctor_settings_prc')
                    var phic = document.getElementById('doctor_settings_phic')
                    var ptr = document.getElementById('doctor_settings_ptr')
                    var ec = document.getElementById('doctor_settings_emergency_contact')
                    var ecn = document.getElementById('doctor_settings_emergency_contact_number')
                    if (fn) fn.value = user.firstname || ''
                    if (mn) mn.value = user.middlename || ''
                    if (ln) ln.value = user.lastname || ''
                    var specDisplay = document.getElementById('doctor_settings_specialization_display')
                    if (specDisplay) specDisplay.textContent = user.specialization || '—'
                    if (bd) bd.value = user.birthdate || ''
                    if (addr) addr.value = user.address || ''
                    if (contact) contact.value = user.contact_number || ''
                    if (prc) prc.value = user.prc_license || ''
                    if (phic) phic.value = user.philhealth_number || ''
                    if (ptr) ptr.value = user.ptr_number || ''
                    if (ec) ec.value = user.emergency_contact || ''
                    if (ecn) ecn.value = user.emergency_contact_number || ''
                    if (sexRadios && user.sex) {
                        sexRadios.forEach(function (r) {
                            r.checked = r.value === user.sex
                        })
                    }
                    if (user.prof_path_url) {
                        var preview = document.getElementById('doctorSettingsProfilePreview')
                        var placeholder = document.getElementById('doctorSettingsProfilePlaceholder')
                        if (preview) { preview.src = user.prof_path_url; preview.classList.remove('hidden') }
                        if (placeholder) placeholder.classList.add('hidden')
                    }
                    if (user.signature_url) {
                        var sigPreview = document.getElementById('doctorSettingsSignaturePreview')
                        var sigPlaceholder = document.getElementById('doctorSettingsSignaturePlaceholder')
                        if (sigPreview) { sigPreview.src = user.signature_url; sigPreview.classList.remove('hidden') }
                        if (sigPlaceholder) sigPlaceholder.classList.add('hidden')
                    }
                })
                .catch(function () {})
        }

        // ── Profile picture upload ──
        var uploadBtn = document.getElementById('doctorSettingsProfileUploadBtn')
        var uploadInput = document.getElementById('doctorSettingsProfileUploadInput')
        var profilePreview = document.getElementById('doctorSettingsProfilePreview')
        var profilePlaceholder = document.getElementById('doctorSettingsProfilePlaceholder')

        if (uploadBtn && uploadInput) {
            uploadBtn.addEventListener('click', function () { uploadInput.click() })
            uploadInput.addEventListener('change', function () {
                var file = uploadInput.files && uploadInput.files[0]
                if (!file) return
                pendingProfileFile = file
                var reader = new FileReader()
                reader.onload = function (e) {
                    if (profilePreview) { profilePreview.src = e.target.result; profilePreview.classList.remove('hidden') }
                    if (profilePlaceholder) profilePlaceholder.classList.add('hidden')
                }
                reader.readAsDataURL(file)
            })
        }

        // ── Signature upload ──
        var sigUploadBtn = document.getElementById('doctorSettingsSignatureUploadBtn')
        var sigUploadInput = document.getElementById('doctorSettingsSignatureUploadInput')
        var sigPreview = document.getElementById('doctorSettingsSignaturePreview')
        var sigPlaceholder = document.getElementById('doctorSettingsSignaturePlaceholder')
        var sigSave = document.getElementById('doctorSettingsSignatureSave')
        var sigSaveSpinner = document.getElementById('doctorSettingsSignatureSaveSpinner')

        if (sigUploadBtn && sigUploadInput) {
            sigUploadBtn.addEventListener('click', function () { sigUploadInput.click() })
            sigUploadInput.addEventListener('change', function () {
                var file = sigUploadInput.files && sigUploadInput.files[0]
                if (!file) return
                pendingSignatureFile = file
                var reader = new FileReader()
                reader.onload = function (e) {
                    if (sigPreview) { sigPreview.src = e.target.result; sigPreview.classList.remove('hidden') }
                    if (sigPlaceholder) sigPlaceholder.classList.add('hidden')
                }
                reader.readAsDataURL(file)
            })
        }

        if (sigSave) {
            sigSave.addEventListener('click', function () {
                if (sigSave.disabled || !pendingSignatureFile) {
                    if (!pendingSignatureFile) showAccountError('Please select a signature image first.')
                    return
                }
                showAccountError('')
                showAccountNotice('')
                sigSave.disabled = true
                if (sigSaveSpinner) sigSaveSpinner.classList.remove('hidden')

                var fd = new FormData()
                fd.append('signature', pendingSignatureFile)

                apiFetch("{{ url('/api/users/me/signature') }}", {
                    method: 'POST',
                    body: fd
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
                        showAccountError((result.data && result.data.message) ? result.data.message : 'Unable to upload signature.')
                        return
                    }
                    pendingSignatureFile = null
                    showAccountNotice('Signature uploaded.')
                    loadCurrentUser()
                })
                .catch(function () {
                    showAccountError('Network error while uploading signature.')
                })
                .finally(function () {
                    sigSave.disabled = false
                    if (sigSaveSpinner) sigSaveSpinner.classList.add('hidden')
                })
            })
        }

        // ── Save profile ──
        var profileForm = document.getElementById('doctorSettingsProfileForm')
        var profileSave = document.getElementById('doctorSettingsProfileSave')
        var profileSaveSpinner = document.getElementById('doctorSettingsProfileSaveSpinner')

        function setProfileSubmitting(isSubmitting) {
            if (profileSave) profileSave.disabled = !!isSubmitting
            if (profileSaveSpinner) profileSaveSpinner.classList.toggle('hidden', !isSubmitting)
        }

        function buildProfilePayload() {
            var fn = document.getElementById('doctor_settings_firstname')
            var mn = document.getElementById('doctor_settings_middlename')
            var ln = document.getElementById('doctor_settings_lastname')
            var bd = document.getElementById('doctor_settings_birthdate')
            var sexRadios = document.querySelectorAll('input[name="doctorSettingsSex"]')
            var addr = document.getElementById('doctor_settings_address')
            var contact = document.getElementById('doctor_settings_contact')
            var prc = document.getElementById('doctor_settings_prc')
            var phic = document.getElementById('doctor_settings_phic')
            var ptr = document.getElementById('doctor_settings_ptr')
            var ec = document.getElementById('doctor_settings_emergency_contact')
            var ecn = document.getElementById('doctor_settings_emergency_contact_number')
            var payload = {
                firstname: fn ? String(fn.value || '').trim() : '',
                lastname: ln ? String(ln.value || '').trim() : '',
                middlename: mn ? String(mn.value || '').trim() : '',
                address: addr ? String(addr.value || '').trim() : '',
                contact_number: contact ? String(contact.value || '').trim() : '',
                prc_license: prc ? String(prc.value || '').trim() : '',
                philhealth_number: phic ? String(phic.value || '').trim() : '',
                ptr_number: ptr ? String(ptr.value || '').trim() : '',
                emergency_contact: ec ? String(ec.value || '').trim() : '',
                emergency_contact_number: ecn ? String(ecn.value || '').trim() : ''
            }
            var birthdate = bd ? String(bd.value || '').trim() : ''
            if (birthdate) payload.birthdate = birthdate
            if (sexRadios) {
                sexRadios.forEach(function (r) {
                    if (r.checked) payload.sex = r.value
                })
            }
            return payload
        }

        if (profileForm) {
            profileForm.addEventListener('submit', function (e) {
                e.preventDefault()
                if (profileSave && profileSave.disabled) return

                var namePayload = buildProfilePayload()

                confirmAction('Are you sure you want to update your profile?', { confirmText: 'Update' })
                    .then(function (confirmed) {
                        if (!confirmed) return

                        showAccountError('')
                        showAccountNotice('')
                        setProfileSubmitting(true)

                        function doSaveName() {
                            apiFetch("{{ url('/api/users') }}/" + encodeURIComponent(currentUserId), {
                                method: 'PUT',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify(namePayload)
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
                                    var msg = (result.data && result.data.message) ? result.data.message : 'Unable to update profile.'
                                    showAccountError(msg)
                                    return
                                }
                                showAccountNotice('Profile updated.')
                                loadCurrentUser()
                            })
                            .catch(function () {
                                showAccountError('Network error while updating profile.')
                            })
                            .finally(function () {
                                setProfileSubmitting(false)
                            })
                        }

                        if (pendingProfileFile) {
                            var fd = new FormData()
                            fd.append('prof_path', pendingProfileFile)
                            apiFetch("{{ url('/api/users/me/profile-picture') }}", {
                                method: 'POST',
                                body: fd
                            })
                            .then(function () {
                                pendingProfileFile = null
                                doSaveName()
                            })
                            .catch(function () {
                                showAccountError('Network error while uploading picture.')
                                setProfileSubmitting(false)
                            })
                        } else {
                            doSaveName()
                        }
                    })
            })
        }

        // ── Password change ──
        var currentPassword = document.getElementById('doctorSettings_current_password')
        var newPassword = document.getElementById('doctorSettings_new_password')
        var confirmPassword = document.getElementById('doctorSettings_confirm_password')
        var accountSave = document.getElementById('doctorSettings_account_save')
        var accountStart = document.getElementById('doctorSettings_account_start')
        var accountCancel = document.getElementById('doctorSettings_account_cancel')
        var accountVerify = document.getElementById('doctorSettings_account_verify')
        var accountBack = document.getElementById('doctorSettings_account_back')
        var verifySpinner = document.getElementById('doctorSettingsAccountVerifySpinner')
        var verifyLabel = document.getElementById('doctorSettingsAccountVerifyLabel')
        var saveSpinner = document.getElementById('doctorSettingsAccountSaveSpinner')
        var accountIdle = document.getElementById('doctorSettingsAccountIdle')
        var accountVerifyStep = document.getElementById('doctorSettingsAccountVerifyStep')
        var accountChangeStep = document.getElementById('doctorSettingsAccountChangeStep')
        var passwordVerifyToken = null
        var cooldownTimer = null

        var passwordTokenKey = 'opol_doctor_pw_verify_token'
        var passwordTokenExpKey = 'opol_doctor_pw_verify_expires_at'
        var passwordCooldownUntilKey = 'opol_doctor_pw_verify_cooldown_until'

        function safeLocalGet(key) {
            try { return window.localStorage ? window.localStorage.getItem(key) : null }
            catch (_) { return null }
        }
        function safeLocalSet(key, value) {
            try { if (window.localStorage) window.localStorage.setItem(key, value) } catch (_) {}
        }
        function safeLocalRemove(key) {
            try { if (window.localStorage) window.localStorage.removeItem(key) } catch (_) {}
        }

        function persistPasswordToken(token, expiresInSeconds) {
            if (!token) return
            var ms = parseInt(String(expiresInSeconds || 0), 10)
            ms = isNaN(ms) || ms < 1 ? 600 : ms
            safeLocalSet(passwordTokenKey, String(token))
            safeLocalSet(passwordTokenExpKey, String(Date.now() + ms * 1000))
        }

        function clearPasswordToken() {
            safeLocalRemove(passwordTokenKey)
            safeLocalRemove(passwordTokenExpKey)
            passwordVerifyToken = null
        }

        function persistCooldown(seconds) {
            var s = parseInt(String(seconds || 0), 10)
            if (isNaN(s) || s < 1) return
            safeLocalSet(passwordCooldownUntilKey, String(Date.now() + s * 1000))
        }

        function clearCooldown() {
            safeLocalRemove(passwordCooldownUntilKey)
        }

        function setAccountStep(step) {
            if (accountIdle) accountIdle.classList.toggle('hidden', step !== 'idle')
            if (accountVerifyStep) accountVerifyStep.classList.toggle('hidden', step !== 'verify')
            if (accountChangeStep) accountChangeStep.classList.toggle('hidden', step !== 'change')
        }

        function setVerifySubmitting(isSubmitting) {
            if (accountVerify) {
                if (isSubmitting) accountVerify.disabled = true
                else if (!cooldownTimer) accountVerify.disabled = false
            }
            if (verifySpinner) verifySpinner.classList.toggle('hidden', !isSubmitting)
        }

        function setSaveSubmitting(isSubmitting) {
            if (accountSave) accountSave.disabled = !!isSubmitting
            if (saveSpinner) saveSpinner.classList.toggle('hidden', !isSubmitting)
        }

        function stopCooldown() {
            if (cooldownTimer) { clearInterval(cooldownTimer); cooldownTimer = null }
        }

        function startCooldown(seconds) {
            stopCooldown()
            var remaining = parseInt(String(seconds || 0), 10)
            if (!remaining || remaining < 1) return
            persistCooldown(remaining)
            if (accountVerify) {
                accountVerify.disabled = true
                accountVerify.classList.add('opacity-60', 'cursor-not-allowed')
                if (verifyLabel) verifyLabel.textContent = 'Try again (' + remaining + ')'
            }
            cooldownTimer = setInterval(function () {
                remaining -= 1
                if (remaining <= 0) {
                    stopCooldown(); clearCooldown()
                    if (accountVerify) {
                        accountVerify.disabled = false
                        accountVerify.classList.remove('opacity-60', 'cursor-not-allowed')
                        if (verifyLabel) verifyLabel.textContent = 'Verify'
                    }
                    return
                }
                if (accountVerify && verifyLabel) verifyLabel.textContent = 'Try again (' + remaining + ')'
            }, 1000)
        }

        function restoreCooldownIfAny() {
            var raw = safeLocalGet(passwordCooldownUntilKey)
            if (!raw) return
            var until = parseInt(String(raw || ''), 10)
            if (isNaN(until) || until <= 0) { clearCooldown(); return }
            var remaining = Math.ceil((until - Date.now()) / 1000)
            if (remaining > 0) startCooldown(remaining)
            else clearCooldown()
        }

        function restorePasswordTokenIfAny() {
            var token = safeLocalGet(passwordTokenKey)
            var expRaw = safeLocalGet(passwordTokenExpKey)
            if (!token || !expRaw) { clearPasswordToken(); return }
            var exp = parseInt(String(expRaw || ''), 10)
            if (isNaN(exp) || exp <= Date.now()) { clearPasswordToken(); return }
            passwordVerifyToken = String(token)
            setAccountStep('change')
        }

        function saveAccount() {
            if (!passwordVerifyToken) {
                showAccountError('Please verify your current password first.')
                setAccountStep('verify')
                return
            }
            var next = newPassword ? String(newPassword.value || '') : ''
            var confirm = confirmPassword ? String(confirmPassword.value || '') : ''
            if (!next || !confirm) { showAccountError('Please enter and confirm your new password.'); return }
            if (next !== confirm) { showAccountError('New password and confirmation do not match.'); return }
            if (typeof apiFetch !== 'function') { showAccountError('API client is not available.'); return }
            showAccountError('')
            showAccountNotice('')
            confirmAction('Are you sure you want to change your password?', { confirmText: 'Change', countdownSeconds: 3 })
                .then(function (confirmed) {
                    if (!confirmed) return
                    setSaveSubmitting(true)
                    apiFetch("{{ url('/api/users/me/password/change') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ token: passwordVerifyToken, password: next, password_confirmation: confirm })
                    })
                    .then(function (response) {
                        return response.json().then(function (data) { return { ok: response.ok, status: response.status, data: data } }).catch(function () { return { ok: response.ok, status: response.status, data: null } })
                    })
                    .then(function (result) {
                        if (!result.ok) {
                            var msg = (result.data && result.data.message) ? result.data.message : 'Unable to update password.'
                            showAccountError(msg)
                            if (result.data && result.data.code === 'PASSWORD_VERIFY_REQUIRED') { clearPasswordToken(); setAccountStep('verify') }
                            return
                        }
                        clearPasswordToken()
                        if (currentPassword) currentPassword.value = ''
                        if (newPassword) newPassword.value = ''
                        if (confirmPassword) confirmPassword.value = ''
                        showAccountError('')
                        showAccountNotice('Password updated.')
                        setAccountStep('idle')
                    })
                    .catch(function () { showAccountError('Network error while updating password.') })
                    .finally(function () { setSaveSubmitting(false) })
                })
        }

        function verifyCurrentPassword() {
            if (typeof apiFetch !== 'function') { showAccountError('API client is not available.'); return }
            var current = currentPassword ? String(currentPassword.value || '') : ''
            if (!current) { showAccountError('Please enter your current password.'); return }
            showAccountError('')
            showAccountNotice('')
            setVerifySubmitting(true)
            apiFetch("{{ url('/api/users/me/password/verify') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ current_password: current })
            })
            .then(function (response) {
                return response.json().then(function (data) { return { ok: response.ok, status: response.status, data: data } }).catch(function () { return { ok: response.ok, status: response.status, data: null } })
            })
            .then(function (result) {
                if (!result.ok) {
                    if (result.status === 429) {
                        var retry = result.data && result.data.retry_after ? parseInt(result.data.retry_after, 10) : 300
                        showAccountError('Too many attempts. Please try again later.')
                        startCooldown(retry)
                        return
                    }
                    var msg = (result.data && result.data.message) ? result.data.message : 'Verification failed.'
                    if (result.data && typeof result.data.tries_remaining === 'number') msg += ' Tries remaining: ' + result.data.tries_remaining
                    showAccountError(msg)
                    return
                }
                passwordVerifyToken = result.data && result.data.token ? String(result.data.token) : null
                if (!passwordVerifyToken) { showAccountError('Verification token missing. Please try again.'); return }
                var expiresIn = result.data && result.data.expires_in ? parseInt(result.data.expires_in, 10) : 600
                persistPasswordToken(passwordVerifyToken, expiresIn)
                if (currentPassword) currentPassword.value = ''
                stopCooldown()
                clearCooldown()
                if (accountVerify) {
                    accountVerify.disabled = false
                    accountVerify.classList.remove('opacity-60', 'cursor-not-allowed')
                    if (verifyLabel) verifyLabel.textContent = 'Verify'
                }
                setAccountStep('change')
            })
            .catch(function () { showAccountError('Network error while verifying password.') })
            .finally(function () { setVerifySubmitting(false) })
        }

        if (accountSave) accountSave.addEventListener('click', saveAccount)
        if (accountStart) accountStart.addEventListener('click', function () {
            showAccountError(''); showAccountNotice(''); clearPasswordToken(); setAccountStep('verify')
        })
        if (accountCancel) accountCancel.addEventListener('click', function () {
            showAccountError(''); showAccountNotice(''); clearPasswordToken(); stopCooldown(); clearCooldown()
            if (currentPassword) currentPassword.value = ''
            setAccountStep('idle')
        })
        if (accountBack) accountBack.addEventListener('click', function () {
            showAccountError(''); showAccountNotice(''); clearPasswordToken()
            if (newPassword) newPassword.value = ''
            if (confirmPassword) confirmPassword.value = ''
            setAccountStep('verify')
        })
        if (accountVerify) accountVerify.addEventListener('click', verifyCurrentPassword)

        setAccountStep('idle')
        restoreCooldownIfAny()
        restorePasswordTokenIfAny()
        loadCurrentUser()
    })
</script>
