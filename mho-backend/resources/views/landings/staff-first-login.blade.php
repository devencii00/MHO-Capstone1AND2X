<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>First Login – Update Password</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
     <link rel="icon" type="image/x-icon" href="/images/logoMHO.ico">
       <link rel="stylesheet" href="{{ asset('assets/fonts/css/stylefont.css') }}">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100 p-4 font-sans">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h1 class="font-playfair text-2xl font-bold text-slate-900 mb-1">Set a new password</h1>
        <p class="text-xs text-slate-500 mb-4">
            For security, please change the temporary password you received. Your email is already set.
        </p>

        <div id="staffFirstLoginError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700"></div>
        <div id="staffFirstLoginSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700"></div>

        <form id="staffFirstLoginForm" class="space-y-3">
            <div>
                <label for="staff_new_password" class="block text-xs text-slate-600 mb-1">New password</label>
                <div class="relative">
                    <input type="password" id="staff_new_password" class="w-full pr-10 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <button type="button" id="staffTogglePassword" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 text-xl">
                        <x-lucide-eye id="staffTogglePasswordEye" class="w-[20px] h-[20px]" />
                        <x-lucide-eye-off id="staffTogglePasswordEyeOff" class="hidden w-[20px] h-[20px]" />
                    </button>
                </div>
            </div>
            <div>
                <label for="staff_new_password_confirmation" class="block text-xs text-slate-600 mb-1">Confirm new password</label>
                <input type="password" id="staff_new_password_confirmation" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
            </div>
            <button id="staffFirstLoginSubmit" type="submit" class="w-full mt-2 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-green-700 text-white text-sm font-semibold hover:from-green-600 hover:to-green-800 transition-colors disabled:opacity-70 disabled:hover:from-green-500 disabled:hover:to-green-700 relative flex items-center justify-center">
                <span id="staffFirstLoginSpinner" class="hidden absolute w-5 h-5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                <span id="staffFirstLoginSubmitLabel">Save and continue</span>
            </button>
        </form>
    </div>

    <script>
        function staffApiFetch(path, options) {
            var token = null;
            try { token = window.localStorage ? window.localStorage.getItem('api_token') : null } catch (_) { token = null }
            var headers = (options && options.headers) ? Object.assign({}, options.headers) : {}
            if (token) {
                headers['Authorization'] = 'Bearer ' + token
            }
            headers['Accept'] = 'application/json'

            var method = (options && options.method) ? options.method : 'GET'

            if (typeof window.axios === 'function') {
                var config = { method: method, url: path, headers: headers }
                if (options && options.body && method !== 'GET') {
                    config.data = options.body
                }
                return window.axios(config).then(function (response) {
                    return { ok: true, status: response.status, json: function () { return Promise.resolve(response.data) }, data: response.data }
                }).catch(function (err) {
                    var resp = (err && err.response) ? err.response : { status: 0, data: null }
                    return { ok: false, status: resp.status, json: function () { return Promise.resolve(resp.data) }, data: resp.data }
                })
            }

            // Fallback to native fetch
            var fetchOptions = { method: method, headers: headers }
            if (options && options.body && method !== 'GET') {
                fetchOptions.body = options.body
            }
            return fetch(path, fetchOptions).then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, status: response.status, json: function () { return Promise.resolve(data) }, data: data }
                })
            }).catch(function () {
                return { ok: false, status: 0, json: function () { return Promise.resolve(null) }, data: null }
            })
        }

        function togglePasswordVisibility(inputId, eyeId, eyeOffId) {
            var input = document.getElementById(inputId)
            var eye = document.getElementById(eyeId)
            var eyeOff = document.getElementById(eyeOffId)
            if (!input || !eye || !eyeOff) {
                return
            }
            var isPassword = input.type === 'password'
            input.type = isPassword ? 'text' : 'password'
            eye.classList.toggle('hidden', isPassword)
            eyeOff.classList.toggle('hidden', !isPassword)
        }

        function isStrongPassword(value) {
            if (!value) {
                return false
            }
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(String(value))
        }

        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('staffFirstLoginForm')
            var errorBox = document.getElementById('staffFirstLoginError')
            var successBox = document.getElementById('staffFirstLoginSuccess')
            var newPasswordInput = document.getElementById('staff_new_password')
            var confirmInput = document.getElementById('staff_new_password_confirmation')
            var toggleBtn = document.getElementById('staffTogglePassword')
            var submitBtn = document.getElementById('staffFirstLoginSubmit')
            var submitSpinner = document.getElementById('staffFirstLoginSpinner')
            var submitLabel = document.getElementById('staffFirstLoginSubmitLabel')
            var isSubmitting = false

            function setSubmitting(state) {
                isSubmitting = !!state
                if (submitBtn) submitBtn.disabled = isSubmitting
                if (submitSpinner) submitSpinner.classList.toggle('hidden', !isSubmitting)
                if (submitLabel) submitLabel.classList.toggle('opacity-0', isSubmitting)
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    togglePasswordVisibility('staff_new_password', 'staffTogglePasswordEye', 'staffTogglePasswordEyeOff')
                })
            }

            if (!form) {
                return
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault()
                if (isSubmitting) return

                if (errorBox) {
                    errorBox.classList.add('hidden')
                    errorBox.textContent = ''
                }
                if (successBox) {
                    successBox.classList.add('hidden')
                    successBox.textContent = ''
                }

                var password = newPasswordInput ? newPasswordInput.value : ''
                var confirm = confirmInput ? confirmInput.value : ''

                if (!password || !confirm) {
                    if (errorBox) {
                        errorBox.textContent = 'Please enter and confirm your new password.'
                        errorBox.classList.remove('hidden')
                    }
                    return
                }

                if (password !== confirm) {
                    if (errorBox) {
                        errorBox.textContent = 'Passwords do not match.'
                        errorBox.classList.remove('hidden')
                    }
                    return
                }

                if (!isStrongPassword(password)) {
                    if (errorBox) {
                        errorBox.textContent = 'Password must be at least 8 characters and include uppercase, lowercase, a number, and a symbol.'
                        errorBox.classList.remove('hidden')
                    }
                    return
                }

                var userRef = null
                try {
                    userRef = window.localStorage ? window.localStorage.getItem('current_user_uuid') : null
                } catch (_) {
                    userRef = null
                }

                if (!userRef) {
                    try {
                        userRef = window.localStorage ? window.localStorage.getItem('current_user_id') : null
                    } catch (_) {
                        userRef = null
                    }
                }

                if (!userRef) {
                    if (errorBox) {
                        errorBox.textContent = 'User information is missing. Please sign in again.'
                        errorBox.classList.remove('hidden')
                    }
                    return
                }

                setSubmitting(true)
                staffApiFetch("{{ url('/api/users') }}/" + userRef, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        password: password,
                        must_change_credentials: false
                    })
                })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data }
                        })
                    })
                    .then(function (result) {
                        if (!result.ok) {
                            if (errorBox) {
                                var message = result.data && result.data.message ? result.data.message : 'Failed to update password.'
                                errorBox.textContent = message
                                errorBox.classList.remove('hidden')
                            }
                            setSubmitting(false)
                            return
                        }

                        if (typeof showToast === 'function') showToast('Password updated. Redirecting to dashboard...', 'success')
                        if (successBox) { successBox.classList.add('hidden'); successBox.textContent = '' }

                        var role = 'admin'

                        if (result.data && result.data.current_role && result.data.current_role.role_name) {
                            role = String(result.data.current_role.role_name).toLowerCase()
                        }

                        setTimeout(function () {
                            var target = "{{ url('/dashboard') }}/" + role
                            if (result.data && result.data.uuid) {
                                target += '?user_uuid=' + encodeURIComponent(String(result.data.uuid))
                            }
                            window.location.href = target
                        }, 1000)
                    })
                    .catch(function () {
                        if (errorBox) {
                            errorBox.textContent = 'Network error while updating password.'
                            errorBox.classList.remove('hidden')
                        }
                        setSubmitting(false)
                    })
            })
        })
    </script>

    <div id="toast-container"></div>

    <script>
    function showToast(message, type) {
        if (!message) return
        type = type || 'success'
        var container = document.getElementById('toast-container')
        if (!container) return
        var icons = {success: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>', error: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>', info: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'}
        var toast = document.createElement('div')
        toast.className = 'toast toast-' + type
        toast.innerHTML = (icons[type] || icons.info) + '<span>' + String(message).replace(/</g, '&lt;') + '</span><span class="toast-close" onclick="this.parentElement.classList.add(\'hide\');setTimeout(function(){this.parentElement.remove()}.bind(this),300)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>'
        container.appendChild(toast)
        requestAnimationFrame(function(){toast.classList.add('show')})
        var t = setTimeout(function(){dismissToast(toast)},4000)
        toast.addEventListener('click',function(e){if(e.target.closest('.toast-close'))return;clearTimeout(t);dismissToast(toast)})
    }
    function dismissToast(el){if(!el||el.classList.contains('hide'))return;el.classList.remove('show');el.classList.add('hide');setTimeout(function(){if(el.parentNode)el.parentNode.removeChild(el)},350)}
    </script>

    <style>
    #toast-container{position:fixed;top:24px;right:24px;z-index:99999;display:flex;flex-direction:column;gap:12px;pointer-events:none}
    #toast-container .toast{pointer-events:auto;display:flex;align-items:center;gap:12px;padding:16px 22px;border-radius:14px;font-size:0.9rem;font-weight:600;line-height:1.4;box-shadow:0 10px 40px rgba(15,23,42,0.18);transform:translateX(120%);opacity:0;transition:transform 0.4s cubic-bezier(0.22,1,0.36,1),opacity 0.35s ease;max-width:460px;min-width:280px;word-break:break-word;letter-spacing:0.01em}
    #toast-container .toast.show{transform:translateX(0);opacity:1}
    #toast-container .toast.hide{transform:translateX(120%);opacity:0}
    #toast-container .toast.toast-success{background:#ecfdf5;border:2px solid #34d399;color:#064e3b}
    #toast-container .toast.toast-error{background:#fef2f2;border:2px solid #f87171;color:#7f1d1d}
    #toast-container .toast svg{width:22px;height:22px;flex-shrink:0}
    #toast-container .toast .toast-close{margin-left:auto;cursor:pointer;opacity:0.4;flex-shrink:0;transition:opacity 0.15s}
    #toast-container .toast .toast-close:hover{opacity:0.8}
    </style>
</body>
</html>
