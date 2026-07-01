<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Admin Credentials</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="{{ asset('assets/fonts/css/stylefont.css') }}">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100 p-4 font-sans">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h1 class="font-playfair text-2xl font-bold text-slate-900 mb-1">Update admin credentials</h1>
        <p class="text-xs text-slate-500 mb-4">For security, please update the default administrator email and/or password.</p>

        <div id="errorBox" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700"></div>
        <div id="successBox" class="hidden mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700"></div>

        <form id="firstLoginForm" class="space-y-3" novalidate>
            <div>
                <label for="new_email" class="block text-xs text-slate-600 mb-1">New email (optional)</label>
                <div id="newEmailError" class="hidden mb-1 text-[0.7rem] text-red-600"></div>
                <input type="text" id="new_email" inputmode="email" autocomplete="email" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
            </div>
            <div>
                <label for="new_password" class="block text-xs text-slate-600 mb-1">New password</label>
                <div id="newPasswordError" class="hidden mb-1 text-[0.7rem] text-red-600"></div>
                <div class="relative">
                    <input type="password" id="new_password" class="w-full pr-10 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <button type="button" id="toggleNewPassword" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 text-xl">
                        <x-lucide-eye id="toggleNewPasswordEye" class="w-[20px] h-[20px]" />
                        <x-lucide-eye-off id="toggleNewPasswordEyeOff" class="hidden w-[20px] h-[20px]" />
                    </button>
                </div>
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-xs text-slate-600 mb-1">Confirm new password</label>
                <input type="password" id="new_password_confirmation" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
            </div>
            <button id="firstLoginSubmit" type="submit" class="w-full mt-2 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-green-700 text-white text-sm font-semibold hover:from-green-600 hover:to-green-800 transition-colors disabled:opacity-70 disabled:hover:from-green-500 disabled:hover:to-green-700 relative flex items-center justify-center">
                <span id="firstLoginSpinner" class="hidden absolute w-5 h-5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                <span id="firstLoginSubmitLabel">Save and continue</span>
            </button>
        </form>
    </div>

    <script>
        function apiFetch(path, options = {}) {
            var token = null;
            try { token = window.localStorage ? window.localStorage.getItem('api_token') : null } catch (_) { token = null; }
            var headers = options.headers ? Object.assign({}, options.headers) : {};
            if (token) {
                headers['Authorization'] = 'Bearer ' + token;
            }
            headers['Accept'] = 'application/json';

            var method = options.method || 'GET';

            if (typeof window.axios === 'function') {
                var config = { method: method, url: path, headers: headers };
                if (options.body && method !== 'GET') {
                    config.data = options.body;
                }
                if (options.signal) {
                    config.signal = options.signal;
                }
                return window.axios(config).then(function (response) {
                    return {
                        ok: true,
                        status: response.status,
                        json: function () { return Promise.resolve(response.data) },
                    };
                }).catch(function (err) {
                    if (err && err.response) {
                        return {
                            ok: false,
                            status: err.response.status,
                            json: function () { return Promise.resolve(err.response.data) },
                        };
                    }
                    return {
                        ok: false,
                        status: 0,
                        json: function () { return Promise.resolve(null) },
                    };
                });
            }

            // Fallback to native fetch
            var fetchOptions = { method: method, headers: headers };
            if (options.body && method !== 'GET') {
                fetchOptions.body = options.body;
            }
            if (options.signal) {
                fetchOptions.signal = options.signal;
            }
            return fetch(path, fetchOptions).then(function (response) {
                return {
                    ok: response.ok,
                    status: response.status,
                    json: function () { return response.json() },
                };
            }).catch(function () {
                return {
                    ok: false,
                    status: 0,
                    json: function () { return Promise.resolve(null) },
                };
            });
        }

        function togglePasswordVisibility(inputId, eyeId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);
            if (!input || !eye || !eyeOff) {
                return;
            }
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            eye.classList.toggle('hidden', isPassword);
            eyeOff.classList.toggle('hidden', !isPassword);
        }

        function isValidEmail(value) {
            if (!value) {
                return false;
            }
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value).trim());
        }

        function isExampleDotComEmail(value) {
            if (!isValidEmail(value)) {
                return false;
            }
            return String(value).trim().toLowerCase().endsWith('@example.com');
        }

        function isStrongPassword(value) {
            if (!value) {
                return false;
            }
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(String(value));
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('firstLoginForm');
            const errorBox = document.getElementById('errorBox');
            const successBox = document.getElementById('successBox');
            const newEmailInput = document.getElementById('new_email');
            const newPasswordInput = document.getElementById('new_password');
            const newPasswordConfirmInput = document.getElementById('new_password_confirmation');
            const toggleNewPassword = document.getElementById('toggleNewPassword');
            const newEmailError = document.getElementById('newEmailError');
            const newPasswordError = document.getElementById('newPasswordError');
            const submitBtn = document.getElementById('firstLoginSubmit');
            const spinner = document.getElementById('firstLoginSpinner');
            const submitLabel = document.getElementById('firstLoginSubmitLabel');

            if (toggleNewPassword) {
                toggleNewPassword.addEventListener('click', function () {
                    togglePasswordVisibility('new_password', 'toggleNewPasswordEye', 'toggleNewPasswordEyeOff');
                });
            }

            if (!form) {
                return;
            }

            function clearInlineErrors() {
                if (newEmailError) {
                    newEmailError.classList.add('hidden');
                    newEmailError.textContent = '';
                }
                if (newPasswordError) {
                    newPasswordError.classList.add('hidden');
                    newPasswordError.textContent = '';
                }
            }

            function setInlineError(el, message) {
                if (!el) {
                    return;
                }
                el.textContent = message || '';
                el.classList.toggle('hidden', !message);
            }

            function setLoading(isLoading) {
                if (submitBtn) {
                    submitBtn.disabled = !!isLoading;
                }
                if (spinner) {
                    spinner.classList.toggle('hidden', !isLoading);
                }
                if (submitLabel) {
                    submitLabel.classList.toggle('opacity-0', !!isLoading);
                }
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (submitBtn && submitBtn.disabled) {
                    return;
                }

                if (errorBox) {
                    errorBox.classList.add('hidden');
                    errorBox.textContent = '';
                }
                if (successBox) {
                    successBox.classList.add('hidden');
                    successBox.textContent = '';
                }
                clearInlineErrors();

                const body = {};

                if (newEmailInput && newEmailInput.value) {
                    if (!isExampleDotComEmail(newEmailInput.value)) {
                        setInlineError(newEmailError, 'Please enter a valid email ending with @example.com.');
                        newEmailInput.focus();
                        return;
                    }
                    body.email = newEmailInput.value;
                }

                if (!newPasswordInput || !newPasswordInput.value) {
                    setInlineError(newPasswordError, 'Password change is required.');
                    if (newPasswordInput) {
                        newPasswordInput.focus();
                    }
                    return;
                }

                if (!newPasswordConfirmInput || newPasswordInput.value !== newPasswordConfirmInput.value) {
                    setInlineError(newPasswordError, 'Passwords do not match.');
                    newPasswordInput.focus();
                    return;
                }
                if (!isStrongPassword(newPasswordInput.value)) {
                    setInlineError(newPasswordError, 'Password must be at least 8 characters and include uppercase, lowercase, a number, and a symbol.');
                    newPasswordInput.focus();
                    return;
                }
                body.password = newPasswordInput.value;

                body.must_change_credentials = false;

                let userRef = null;
                try {
                    userRef = window.localStorage ? window.localStorage.getItem('current_user_uuid') : null;
                } catch (_) {
                    userRef = null;
                }

                if (!userRef) {
                    try {
                        userRef = window.localStorage ? window.localStorage.getItem('current_user_id') : null;
                    } catch (_) {
                        userRef = null;
                    }
                }

                if (!userRef) {
                    if (errorBox) {
                        errorBox.textContent = 'User information is missing. Please sign in again.';
                        errorBox.classList.remove('hidden');
                    }
                    return;
                }

                setLoading(true);
                let keepLoading = false;

                try {
                    const abortController = typeof AbortController !== 'undefined' ? new AbortController() : null;
                    const timeoutId = setTimeout(function () {
                        if (abortController) {
                            abortController.abort();
                        }
                    }, 15000);

                    const response = await apiFetch("{{ url('/api/users') }}/" + userRef, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        signal: abortController ? abortController.signal : undefined,
                        body: JSON.stringify(body),
                    });
                    clearTimeout(timeoutId);

                    let data = {};

                    try {
                        data = await response.json();
                    } catch (_) {
                        data = {};
                    }

                    if (!response.ok) {
                        if (response.status === 422 && data && data.errors) {
                            if (data.errors.email && data.errors.email.length) {
                                setInlineError(newEmailError, 'Please enter a valid email ending with @example.com.');
                            }
                            if (data.errors.password && data.errors.password.length) {
                                setInlineError(newPasswordError, 'Password change is required.');
                            }
                        } else {
                            const message = data.message || 'Failed to update credentials.';
                            if (errorBox) {
                                errorBox.textContent = message;
                                errorBox.classList.remove('hidden');
                            }
                        }
                        return;
                    }

                    if (typeof showToast === 'function') showToast('Credentials updated. Redirecting to dashboard.', 'success');
                    if (successBox) {
                        successBox.classList.add('hidden');
                        successBox.textContent = '';
                    }
                    keepLoading = true;
                    setLoading(true);

                    let role = 'admin';

                    if (data.current_role && data.current_role.role_name) {
                        role = String(data.current_role.role_name).toLowerCase();
                    }

                    setTimeout(function () {
                        let target = "{{ url('/dashboard') }}/" + role;
                        if (data && data.uuid) {
                            target += '?user_uuid=' + encodeURIComponent(String(data.uuid));
                        }
                        window.location.href = target;
                    }, 1000);
                } catch (_) {
                    if (errorBox) {
                        errorBox.textContent = 'Network error. Please try again.';
                        errorBox.classList.remove('hidden');
                    }
                } finally {
                    if (!keepLoading) {
                        setLoading(false);
                    }
                }
            });
        });
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
