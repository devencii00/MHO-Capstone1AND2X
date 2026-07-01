<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    @vite('resources/css/app.css')
     <link rel="icon" type="image/x-icon" href="/images/logoMHOV2.ico">
     
       <link rel="stylesheet" href="{{ asset('assets/fonts/css/stylefont.css') }}">
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100 p-4 font-sans">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h1 class="font-playfair text-2xl font-bold text-slate-900 mb-1">Forgot password</h1>
        <p class="text-xs text-slate-500 mb-4">We will send a one-time code to your email to reset your password.</p>

        <div id="forgotError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700"></div>
        <div id="forgotSuccess" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700"></div>

        <form id="forgotForm" class="space-y-3">
            <div id="forgotStepEmail">
                <label for="forgot_email" class="block text-xs text-slate-600 mb-1">Email</label>
                <input type="email" id="forgot_email" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" required>
                <button type="submit" class="w-full mt-3 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-green-700 text-white text-sm font-semibold hover:from-green-600 hover:to-green-800 transition-colors">
                    Send code
                </button>
            </div>

            <div id="forgotStepCode" class="hidden space-y-3">
                <p class="text-xs text-slate-500">Enter the 5-digit code we sent to your email.</p>
                <div class="flex items-center justify-between gap-2">
                    <input type="text" maxlength="1" class="forgot-code-input w-10 h-10 text-center rounded-lg border border-slate-200 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <input type="text" maxlength="1" class="forgot-code-input w-10 h-10 text-center rounded-lg border border-slate-200 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <input type="text" maxlength="1" class="forgot-code-input w-10 h-10 text-center rounded-lg border border-slate-200 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <input type="text" maxlength="1" class="forgot-code-input w-10 h-10 text-center rounded-lg border border-slate-200 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <input type="text" maxlength="1" class="forgot-code-input w-10 h-10 text-center rounded-lg border border-slate-200 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>
            </div>

            <div id="forgotStepPassword" class="hidden space-y-3">
                <div>
                    <label for="forgot_new_password" class="block text-xs text-slate-600 mb-1">New password</label>
                    <input type="password" id="forgot_new_password" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>
                <div>
                    <label for="forgot_new_password_confirmation" class="block text-xs text-slate-600 mb-1">Confirm new password</label>
                    <input type="password" id="forgot_new_password_confirmation" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                </div>
                <button type="submit" class="w-full mt-1 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-green-700 text-white text-sm font-semibold hover:from-green-600 hover:to-green-800 transition-colors">
                    Reset password
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-xs text-slate-500">
            Remembered your password?
            <a href="{{ route('webadmin.login') }}" class="text-green-500 hover:text-green-600 font-semibold">Back to login</a>
        </p>
    </div>

    <script>
        function forgotApiFetch(path, options) {
            var headers = (options && options.headers) ? Object.assign({}, options.headers) : {}
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

        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('forgotForm')
            var errorBox = document.getElementById('forgotError')
            var successBox = document.getElementById('forgotSuccess')
            var emailInput = document.getElementById('forgot_email')
            var stepEmail = document.getElementById('forgotStepEmail')
            var stepCode = document.getElementById('forgotStepCode')
            var stepPassword = document.getElementById('forgotStepPassword')
            var codeInputs = Array.prototype.slice.call(document.querySelectorAll('.forgot-code-input'))
            var newPasswordInput = document.getElementById('forgot_new_password')
            var newPasswordConfirmInput = document.getElementById('forgot_new_password_confirmation')

            var currentStep = 'email'

            codeInputs.forEach(function (input, index) {
                input.addEventListener('input', function () {
                    var value = this.value.replace(/[^0-9]/g, '')
                    this.value = value
                    if (value && index < codeInputs.length - 1) {
                        codeInputs[index + 1].focus()
                    }
                })
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        codeInputs[index - 1].focus()
                    }
                })
            })

            function showError(message) {
                if (message && typeof showToast === 'function') showToast(message, 'error')
            }

            function showSuccess(message) {
                if (message && typeof showToast === 'function') showToast(message, 'success')
            }

            if (!form) {
                return
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault()
                showError('')
                showSuccess('')

                if (currentStep === 'email') {
                    var email = emailInput ? emailInput.value.trim() : ''
                    if (!email) {
                        showError('Please enter your email.')
                        return
                    }

                    forgotApiFetch("{{ url('/api/password/forgot') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: email })
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                return { ok: response.ok, data: data }
                            })
                        })
                        .then(function (result) {
                            if (!result.ok) {
                                var message = result.data && result.data.message ? result.data.message : 'Unable to start reset process.'
                                showError(message)
                                return
                            }

                            showSuccess('If the email exists, a one-time code has been generated.')
                            currentStep = 'code'
                            if (stepEmail) stepEmail.classList.add('hidden')
                            if (stepCode) stepCode.classList.remove('hidden')
                            if (codeInputs.length) codeInputs[0].focus()
                        })
                        .catch(function () {
                            showError('Network error. Please try again.')
                        })
                } else if (currentStep === 'code') {
                    var code = codeInputs.map(function (input) { return input.value || '' }).join('')
                    if (!code || code.length !== 5) {
                        showError('Please enter the 5-digit code.')
                        return
                    }

                    currentStep = 'password'
                    if (stepCode) stepCode.classList.add('hidden')
                    if (stepPassword) stepPassword.classList.remove('hidden')
                    if (newPasswordInput) newPasswordInput.focus()
                    form.setAttribute('data-reset-token', code)
                } else if (currentStep === 'password') {
                    var password = newPasswordInput ? newPasswordInput.value : ''
                    var confirm = newPasswordConfirmInput ? newPasswordConfirmInput.value : ''
                    if (!password || !confirm) {
                        showError('Please enter and confirm your new password.')
                        return
                    }
                    if (password !== confirm) {
                        showError('Passwords do not match.')
                        return
                    }

                    var token = form.getAttribute('data-reset-token') || ''
                    if (!token) {
                        showError('Reset token is missing. Please restart the process.')
                        return
                    }

                    forgotApiFetch("{{ url('/api/password/reset') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            token: token,
                            password: password,
                            password_confirmation: confirm
                        })
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                return { ok: response.ok, data: data }
                            })
                        })
                        .then(function (result) {
                            if (!result.ok) {
                                var message = result.data && result.data.message ? result.data.message : 'Unable to reset password.'
                                showError(message)
                                return
                            }

                            showSuccess('Password has been reset. You can now sign in with your new password.')
                            setTimeout(function () {
                                window.location.href = "{{ route('webadmin.login') }}"
                            }, 1200)
                        })
                        .catch(function () {
                            showError('Network error. Please try again.')
                        })
                }
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
