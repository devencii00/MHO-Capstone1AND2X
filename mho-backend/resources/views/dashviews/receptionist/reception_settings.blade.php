<div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)]">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-slate-900"></h2>
        <span class="text-[0.7rem] text-slate-400 uppercase tracking-widest">Receptionist</span>
    </div>
    <p class="text-xs text-slate-500 mb-4">
   
    </p>

    <div id="receptionAccountNotice" class="hidden mb-3 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[0.75rem] text-slate-700"></div>
    <div id="receptionAccountError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700"></div>

    <div class="flex flex-col md:flex-row gap-5">
        {{-- Side Nav --}}
        <div class="flex md:flex-col gap-2 shrink-0">
            <button type="button" id="receptionSettingsTabProfile" class="reception-settings-tab-btn px-4 py-2.5 rounded-xl text-[0.78rem] font-semibold text-left transition-colors border whitespace-nowrap border-green-500/40 bg-green-50 text-green-700 hover:bg-green-100">
                <span class="inline-flex items-center gap-2"><x-lucide-user class="w-[16px] h-[16px]" /> Edit Profile</span>
            </button>
            <button type="button" id="receptionSettingsTabPassword" class="reception-settings-tab-btn px-4 py-2.5 rounded-xl text-[0.78rem] font-semibold text-left transition-colors border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                <span class="inline-flex items-center gap-2"><x-lucide-lock class="w-[16px] h-[16px]" /> Change Password</span>
            </button>
        </div>

        {{-- Content Area --}}
        <div class="flex-1 min-w-0">
            {{-- ===== EDIT PROFILE PANEL ===== --}}
            <div id="receptionSettingsProfilePanel" class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-900">Edit Profile</h3>
                        <p class="text-[0.7rem] text-slate-500">Update your name, profile picture, and personal details.</p>
                    </div>
                    <x-lucide-user class="w-[18px] h-[18px] text-slate-700" />
                </div>

                <form id="receptionSettingsProfileForm" class="space-y-4">
                    {{-- Profile picture --}}
                    <div class="flex items-center gap-4">
                        <div class="relative w-16 h-16 rounded-full border-2 border-slate-200 overflow-hidden bg-slate-100 flex-shrink-0">
                            <img id="receptionSettingsProfilePreview" src="" alt="Profile" class="w-full h-full object-cover hidden">
                            <div id="receptionSettingsProfilePlaceholder" class="w-full h-full flex items-center justify-center text-slate-400">
                                <x-lucide-user class="w-8 h-8" />
                            </div>
                        </div>
                        <div>
                            <button type="button" id="receptionSettingsProfileUploadBtn" class="inline-flex items-center gap-2 rounded-xl border border-green-500/40 bg-green-50 px-3 py-1.5 text-[0.72rem] font-semibold text-green-700 hover:bg-green-100">
                                <x-lucide-camera class="w-[14px] h-[14px]" />
                                Upload photo
                            </button>
                            <input id="receptionSettingsProfileUploadInput" type="file" accept="image/*" class="hidden">
                            <p class="text-[0.65rem] text-slate-400 mt-1">JPG, PNG. Max 5MB.</p>
                        </div>
                    </div>

                    {{-- Name fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="reception_settings_lastname" class="block text-[0.7rem] text-slate-500 mb-1">Last name</label>
                            <input id="reception_settings_lastname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="reception_settings_firstname" class="block text-[0.7rem] text-slate-500 mb-1">First name</label>
                            <input id="reception_settings_firstname" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="reception_settings_middlename" class="block text-[0.7rem] text-slate-500 mb-1">Middle name <span class="text-slate-400">(optional)</span></label>
                            <input id="reception_settings_middlename" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="N/A">
                        </div>
                    </div>

                    {{-- Sex + Birthdate --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[0.7rem] text-slate-500 mb-1">Sex</label>
                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="receptionSettingsSex" value="Male" class="rounded-full text-green-600 focus:ring-green-500"> Male
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="receptionSettingsSex" value="Female" class="rounded-full text-green-600 focus:ring-green-500"> Female
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="reception_settings_birthdate" class="block text-[0.7rem] text-slate-500 mb-1">Birthdate</label>
                            <input id="reception_settings_birthdate" type="date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="reception_settings_contact" class="block text-[0.7rem] text-slate-500 mb-1">Contact number</label>
                            <input id="reception_settings_contact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="09xx xxx xxxx">
                        </div>
                    </div>

                    <hr class="border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="reception_settings_prc" class="block text-[0.7rem] text-slate-500 mb-1">PRC License Number</label>
                            <input id="reception_settings_prc" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="7-digit number" maxlength="7">
                        </div>
                        <div>
                            <label for="reception_settings_phic" class="block text-[0.7rem] text-slate-500 mb-1">PHIC Number</label>
                            <input id="reception_settings_phic" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="01-234567890-1" maxlength="14">
                        </div>
                    </div>
                    <div>
                        <label for="reception_settings_ptr" class="block text-[0.7rem] text-slate-500 mb-1">PTR Number</label>
                        <input id="reception_settings_ptr" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <hr class="border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="reception_settings_emergency_contact" class="block text-[0.7rem] text-slate-500 mb-1">Emergency contact (name)</label>
                            <input id="reception_settings_emergency_contact" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="reception_settings_emergency_contact_number" class="block text-[0.7rem] text-slate-500 mb-1">Emergency contact number</label>
                            <input id="reception_settings_emergency_contact_number" type="tel" inputmode="tel" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="+63 917 555 0123" maxlength="18">
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="reception_settings_address" class="block text-[0.7rem] text-slate-500 mb-1">Address</label>
                        <input id="reception_settings_address" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>

                    <div class="flex items-center justify-end pt-1">
                        <button type="submit" id="receptionSettingsProfileSave" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-3 py-2 text-[0.78rem] font-semibold text-white hover:bg-green-700 disabled:opacity-60 disabled:hover:bg-green-600">
                            <span id="receptionSettingsProfileSaveSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            Save profile
                        </button>
                    </div>
                </form>
            </div>

            {{-- ===== CHANGE PASSWORD PANEL ===== --}}
            <div id="receptionSettingsPasswordPanel" class="hidden rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-900">Account password</h3>
                        <p class="text-[0.7rem] text-slate-500">Verify your current password before setting a new one.</p>
                    </div>
                    <x-lucide-lock class="w-[18px] h-[18px] text-slate-700" />
                </div>

                <div id="receptionSettingsAccountIdle" class="rounded-2xl border border-slate-200 bg-white p-4">
                    <button type="button" id="receptionSettings_account_start" class="inline-flex items-center gap-2 rounded-xl border border-green-500/40 bg-green-50 px-3 py-2 text-[0.78rem] font-semibold text-green-700 hover:bg-green-100">
                        <x-lucide-key class="w-[18px] h-[18px]" />
                        Change password
                    </button>
                </div>

                <div id="receptionSettingsAccountVerifyStep" class="hidden rounded-2xl border border-slate-200 bg-white p-4 mt-3">
                    <div class="text-[0.72rem] font-semibold text-slate-900 mb-3">Verify current password</div>
                    <div>
                        <label for="receptionSettings_current_password" class="block text-[0.7rem] text-slate-500 mb-1">Current password</label>
                        <input id="receptionSettings_current_password" type="password" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    </div>
                    <div class="mt-3 flex items-center justify-end gap-2">
                        <button type="button" id="receptionSettings_account_cancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button type="button" id="receptionSettings_account_verify" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-700 text-white text-[0.78rem] font-semibold hover:bg-green-600">
                            <span id="receptionSettingsAccountVerifySpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            <span id="receptionSettingsAccountVerifyLabel">Verify</span>
                        </button>
                    </div>
                </div>

                <div id="receptionSettingsAccountChangeStep" class="hidden rounded-2xl border border-slate-200 bg-white p-4 mt-3">
                    <div class="text-[0.72rem] font-semibold text-slate-900 mb-3">Set new password</div>
                    <div class="space-y-3">
                        <div>
                            <label for="receptionSettings_new_password" class="block text-[0.7rem] text-slate-500 mb-1">New password</label>
                            <input id="receptionSettings_new_password" type="password" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                        <div>
                            <label for="receptionSettings_confirm_password" class="block text-[0.7rem] text-slate-500 mb-1">Confirm new password</label>
                            <input id="receptionSettings_confirm_password" type="password" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[0.78rem] text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-end gap-2">
                        <button type="button" id="receptionSettings_account_back" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Back</button>
                        <button type="button" id="receptionSettings_account_save" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700">
                            <span id="receptionSettingsAccountSaveSpinner" class="hidden w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                            Save new password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="receptionSettingsConfirmOverlay" class="hidden fixed inset-0 z-[70] bg-slate-900/40 items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-200 shadow-[0_12px_30px_rgba(15,23,42,0.24)] p-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                <x-lucide-info class="w-[18px] h-[18px]" />
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold text-slate-900">Confirm</div>
                <div id="receptionSettingsConfirmMessage" class="text-[0.78rem] text-slate-600 mt-0.5">Are you sure?</div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-end gap-2">
            <button type="button" id="receptionSettingsConfirmCancel" class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-[0.78rem] font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="button" id="receptionSettingsConfirmOk" class="px-3 py-2 rounded-xl bg-slate-900 text-white text-[0.78rem] font-semibold hover:bg-slate-800">Confirm</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentUserId = null
        var pendingProfileFile = null

        // ── Tab switching ──
        var tabProfile = document.getElementById('receptionSettingsTabProfile')
        var tabPassword = document.getElementById('receptionSettingsTabPassword')
        var panelProfile = document.getElementById('receptionSettingsProfilePanel')
        var panelPassword = document.getElementById('receptionSettingsPasswordPanel')

        function setActiveSettingsTab(tab) {
            var isProfile = tab === 'profile'
            ;[tabProfile, tabPassword].forEach(function (btn) {
                if (!btn) return
                btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-500/40', 'bg-white', 'text-slate-700', 'border-slate-200')
                btn.classList.add('border', 'border-slate-200', 'bg-white', 'text-slate-700', 'hover:bg-slate-50')
            })
            var active = isProfile ? tabProfile : tabPassword
            if (active) {
                active.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:bg-slate-50')
                active.classList.add('bg-green-50', 'text-green-700', 'border-green-500/40')
            }
            if (panelProfile) panelProfile.classList.toggle('hidden', !isProfile)
            if (panelPassword) panelPassword.classList.toggle('hidden', isProfile)
        }

        if (tabProfile) tabProfile.addEventListener('click', function () { setActiveSettingsTab('profile') })
        if (tabPassword) tabPassword.addEventListener('click', function () { setActiveSettingsTab('password') })
        setActiveSettingsTab('profile')

        // ── Common helpers ──
        var accountNotice = document.getElementById('receptionAccountNotice')
        var accountError = document.getElementById('receptionAccountError')

        function showAccountError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showAccountNotice(message) {
            if (!accountNotice) return
            accountNotice.textContent = message || ''
            accountNotice.classList.toggle('hidden', !message)
        }

        // ── Confirm overlay ──
        var confirmOverlay = document.getElementById('receptionSettingsConfirmOverlay')
        var confirmMessage = document.getElementById('receptionSettingsConfirmMessage')
        var confirmOk = document.getElementById('receptionSettingsConfirmOk')
        var confirmCancel = document.getElementById('receptionSettingsConfirmCancel')
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
                    var fn = document.getElementById('reception_settings_firstname')
                    var mn = document.getElementById('reception_settings_middlename')
                    var ln = document.getElementById('reception_settings_lastname')
                    var bd = document.getElementById('reception_settings_birthdate')
                    var sexRadios = document.querySelectorAll('input[name="receptionSettingsSex"]')
                    var addr = document.getElementById('reception_settings_address')
                    var contact = document.getElementById('reception_settings_contact')
                    var prc = document.getElementById('reception_settings_prc')
                    var phic = document.getElementById('reception_settings_phic')
                    var ptr = document.getElementById('reception_settings_ptr')
                    var ec = document.getElementById('reception_settings_emergency_contact')
                    var ecn = document.getElementById('reception_settings_emergency_contact_number')
                    if (fn) fn.value = user.firstname || ''
                    if (mn) mn.value = user.middlename || ''
                    if (ln) ln.value = user.lastname || ''
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
                        var preview = document.getElementById('receptionSettingsProfilePreview')
                        var placeholder = document.getElementById('receptionSettingsProfilePlaceholder')
                        if (preview) { preview.src = user.prof_path_url; preview.classList.remove('hidden') }
                        if (placeholder) placeholder.classList.add('hidden')
                    }
                })
                .catch(function () {})
        }

        // ── Profile picture upload ──
        var uploadBtn = document.getElementById('receptionSettingsProfileUploadBtn')
        var uploadInput = document.getElementById('receptionSettingsProfileUploadInput')
        var profilePreview = document.getElementById('receptionSettingsProfilePreview')
        var profilePlaceholder = document.getElementById('receptionSettingsProfilePlaceholder')

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

        // ── Save profile ──
        var profileForm = document.getElementById('receptionSettingsProfileForm')
        var profileSave = document.getElementById('receptionSettingsProfileSave')
        var profileSaveSpinner = document.getElementById('receptionSettingsProfileSaveSpinner')

        function setProfileSubmitting(isSubmitting) {
            if (profileSave) profileSave.disabled = !!isSubmitting
            if (profileSaveSpinner) profileSaveSpinner.classList.toggle('hidden', !isSubmitting)
        }

        function buildProfilePayload() {
            var fn = document.getElementById('reception_settings_firstname')
            var mn = document.getElementById('reception_settings_middlename')
            var ln = document.getElementById('reception_settings_lastname')
            var bd = document.getElementById('reception_settings_birthdate')
            var sexRadios = document.querySelectorAll('input[name="receptionSettingsSex"]')
            var addr = document.getElementById('reception_settings_address')
            var contact = document.getElementById('reception_settings_contact')
            var prc = document.getElementById('reception_settings_prc')
            var phic = document.getElementById('reception_settings_phic')
            var ptr = document.getElementById('reception_settings_ptr')
            var ec = document.getElementById('reception_settings_emergency_contact')
            var ecn = document.getElementById('reception_settings_emergency_contact_number')
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
        var currentPassword = document.getElementById('receptionSettings_current_password')
        var newPassword = document.getElementById('receptionSettings_new_password')
        var confirmPassword = document.getElementById('receptionSettings_confirm_password')
        var accountSave = document.getElementById('receptionSettings_account_save')
        var accountStart = document.getElementById('receptionSettings_account_start')
        var accountCancel = document.getElementById('receptionSettings_account_cancel')
        var accountVerify = document.getElementById('receptionSettings_account_verify')
        var accountBack = document.getElementById('receptionSettings_account_back')
        var verifySpinner = document.getElementById('receptionSettingsAccountVerifySpinner')
        var verifyLabel = document.getElementById('receptionSettingsAccountVerifyLabel')
        var saveSpinner = document.getElementById('receptionSettingsAccountSaveSpinner')
        var accountIdle = document.getElementById('receptionSettingsAccountIdle')
        var accountVerifyStep = document.getElementById('receptionSettingsAccountVerifyStep')
        var accountChangeStep = document.getElementById('receptionSettingsAccountChangeStep')
        var passwordVerifyToken = null
        var cooldownTimer = null

        var passwordTokenKey = 'opol_reception_pw_verify_token'
        var passwordTokenExpKey = 'opol_reception_pw_verify_expires_at'
        var passwordCooldownUntilKey = 'opol_reception_pw_verify_cooldown_until'

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
