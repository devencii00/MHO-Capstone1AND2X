<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Opol Primary Healthcare - Login</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
     <link rel="icon" type="image/x-icon" href="/images/logoMHO.ico">
       <link rel="stylesheet" href="{{ asset('assets/fonts/css/stylefont.css') }}">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        @keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
        @keyframes pulseRing { 0%,100% { box-shadow:0 0 0 0 rgba(255,255,255,0.2);} 50% { box-shadow:0 0 0 15px rgba(255,255,255,0.1);} }
        .animate-pulseRing { animation: pulseRing 3s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100 p-4 font-sans">

    <div class="flex flex-col md:flex-row w-full max-w-4xl rounded-3xl overflow-hidden shadow-xl bg-white animate-fadeIn">

        <!-- Left Panel -->
        <div class="md:flex-[0_0_42%] flex flex-col items-center justify-center relative p-8 bg-gradient-to-br from-green-500 to-green-700 overflow-hidden">
            
            <!-- Decorative circles -->
            <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10 pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-44 h-44 rounded-full bg-white/10 pointer-events-none"></div>

            <!-- Logo ring -->
            <div class="relative w-44 h-44 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center shadow-lg animate-pulseRing">
                <img src="{{ asset('images/MHOLogoV2.png') }}" alt="OPOL - MHO" class="w-40 h-40 object-contain drop-shadow-lg">
            </div>

            <p class="mt-7 text-white/90 text-xs uppercase tracking-wide text-center">Trusted Healthcare Since</p>
            <p class="font-playfair text-white text-lg font-bold text-center mt-1.5 leading-snug">Opol Primary<br>Healthcare Facility</p>

            <div class="flex flex-wrap gap-2 justify-center mt-4">
                <span class="text-white/80 text-[0.55rem] font-medium bg-white/20 border border-white/25 rounded-full px-3 py-1">General Medicine</span>
                <span class="text-white/80 text-[0.55rem] font-medium bg-white/20 border border-white/25 rounded-full px-3 py-1">Patient Care</span>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="flex-1 flex flex-col justify-center p-10 md:p-12 bg-white">

            <div class="mb-6">
                <h1 class="font-playfair text-2xl font-bold text-slate-900 mb-1">Welcome back</h1>
                <p class="text-sm text-slate-400">Sign in to your clinic account to continue</p>
            </div>

            <div id="errorBox" class="hidden mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            <form onsubmit="handleSubmit(event)" class="space-y-5">

                <!-- Email -->
                <div class="relative">
                    <input type="email" id="email" name="email" placeholder=" " required
                           class="peer w-full px-4 pt-5 pb-2 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                    <label for="email" class="absolute left-4 top-2.5 text-slate-400 text-xs transition-all peer-placeholder-shown:top-5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:top-1 peer-focus:text-xs peer-focus:text-green-600">Email address</label>
                </div>

                <!-- Password -->
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder=" " required
                           class="peer w-full pr-10 px-4 pt-5 pb-2 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition">
                    <label for="password" class="absolute left-4 top-2.5 text-slate-400 text-xs transition-all peer-placeholder-shown:top-5 peer-placeholder-shown:text-sm peer-placeholder-shown:text-slate-400 peer-focus:top-1 peer-focus:text-xs peer-focus:text-green-600">Password</label>
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 text-xl">
                        <x-lucide-eye id="togglePasswordEye" class="w-[20px] h-[20px]" />
                        <x-lucide-eye-off id="togglePasswordEyeOff" class="hidden w-[20px] h-[20px]" />
                    </button>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border border-slate-300 accent-green-500">
                        <span class="text-slate-600">Remember me</span>
                    </label>
                    <a href="{{ route('password.forgot') }}" class="text-green-500 hover:text-green-600 font-semibold transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Sign in button -->
                <button type="submit" id="signInBtn"
                        class="w-full h-11 rounded-xl bg-gradient-to-br from-green-500 to-green-700 text-white font-playfair font-semibold shadow-lg hover:from-green-600 hover:to-green-800 transition-colors flex items-center justify-center gap-2">
                    <span id="btnSpinner" class="hidden w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                    <span id="btnLabel">Sign In</span>
                </button>
            </form>

            <!-- Divider -->
            <!-- <div class="flex items-center gap-2 my-4">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs text-slate-400 font-medium">or</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div> -->

        </div>

    </div>

    <script>
        // ── Auto-redirect if already logged in ──
        (function () {
            var token = null;
            try { token = window.localStorage ? window.localStorage.getItem('api_token') : null } catch (_) {}
            if (!token) return;

            if (typeof window.axios === 'function') {
                window.axios.get("{{ url('/api/user') }}", {
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                }).then(function (response) {
                    var resp = response.data;
                    if (!resp || !resp.uuid) return;
                    var role = 'admin';
                    if (resp.current_role && resp.current_role.role_name) {
                        role = String(resp.current_role.role_name).toLowerCase();
                    }
                    var target = "{{ url('/dashboard') }}/" + role + '?user_uuid=' + encodeURIComponent(resp.uuid);
                    window.location.replace(target);
                }).catch(function () {});
            } else {
                // Fallback via fetch
                fetch("{{ url('/api/user') }}", {
                    headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
                }).then(function (r) { return r.json(); }).then(function (resp) {
                    if (!resp || !resp.uuid) return;
                    var role = 'admin';
                    if (resp.current_role && resp.current_role.role_name) {
                        role = String(resp.current_role.role_name).toLowerCase();
                    }
                    var target = "{{ url('/dashboard') }}/" + role + '?user_uuid=' + encodeURIComponent(resp.uuid);
                    window.location.replace(target);
                }).catch(function () {});
            }
        })();

        async function handleSubmit(e) {
            e.preventDefault();

            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const btn = document.getElementById('signInBtn');
            const label = document.getElementById('btnLabel');
            const spinner = document.getElementById('btnSpinner');
            const errorBox = document.getElementById('errorBox');

            errorBox.classList.add('hidden');
            errorBox.textContent = '';

            btn.disabled = true;
            spinner.classList.remove('hidden');

            try {
                // Fallback if axios is not loaded (e.g., Vite module failed)
                if (typeof window.axios !== 'function') {
                    var fetchResp = await fetch("{{ url('/api/login') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ email: emailInput.value, password: passwordInput.value }),
                    });
                    var fetchData = await fetchResp.json();
                    if (!fetchResp.ok) {
                        var msg = fetchData.message || 'Unable to sign in.';
                        if (fetchResp.status === 422 && fetchData.errors) {
                            var all = [];
                            for (var k in fetchData.errors) {
                                if (!Object.prototype.hasOwnProperty.call(fetchData.errors, k)) continue;
                                var v = fetchData.errors[k];
                                if (Array.isArray(v)) { for (var i = 0; i < v.length; i++) all.push(String(v[i])); }
                                else if (v != null) all.push(String(v));
                            }
                            if (all.length) msg = all.join(' ');
                        }
                        errorBox.textContent = msg;
                        errorBox.classList.remove('hidden');
                        btn.disabled = false;
                        spinner.classList.add('hidden');
                        return;
                    }
                    data = fetchData;
                } else {
                    const response = await window.axios.post("{{ url('/api/login') }}", {
                        email: emailInput.value,
                        password: passwordInput.value,
                    }, {
                        headers: { 'Accept': 'application/json' }
                    });
                    var data = response.data;
                }

                if (data.token) {
                    try {
                        window.localStorage.setItem('api_token', data.token);
                    } catch (_) {
                    }
                }

                const user = data.user || {};

                if (user.uuid) {
                    try {
                        window.localStorage.setItem('current_user_uuid', user.uuid);
                    } catch (_) {
                    }
                }
                if (user.user_id) {
                    try {
                        window.localStorage.setItem('current_user_id', user.user_id);
                    } catch (_) {
                    }
                }

                if (user.must_change_credentials) {
                    var roleName = 'admin';
                    if (user.current_role && user.current_role.role_name) {
                        roleName = String(user.current_role.role_name).toLowerCase();
                    }

                    if (roleName === 'admin') {
                        window.location.href = "{{ route('first.login') }}";
                    } else {
                        window.location.href = "{{ route('staff.first.login') }}";
                    }
                    return;
                }

                let role = 'admin';

                if (user.current_role && user.current_role.role_name) {
                    role = String(user.current_role.role_name).toLowerCase();
                }

                let target = "{{ url('/dashboard') }}/" + role;
                if (user.uuid) {
                    target += '?user_uuid=' + encodeURIComponent(user.uuid);
                }

                window.location.href = target;
            } catch (err) {
                var message = 'Network error. Please try again.';
                if (err && err.response) {
                    var respData = err.response.data;
                    if (respData && respData.message) {
                        message = respData.message;
                    }
                    if (err.response.status === 422 && respData && respData.errors) {
                        var allErrors = [];
                        for (var key in respData.errors) {
                            if (!Object.prototype.hasOwnProperty.call(respData.errors, key)) continue;
                            var val = respData.errors[key];
                            if (Array.isArray(val)) {
                                for (var i = 0; i < val.length; i++) allErrors.push(String(val[i]));
                            } else if (val != null) {
                                allErrors.push(String(val));
                            }
                        }
                        if (allErrors.length > 0) message = allErrors.join(' ');
                    }
                }
                errorBox.textContent = message;
                errorBox.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
            }
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

        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            if (togglePassword) {
                togglePassword.addEventListener('click', function () {
                    togglePasswordVisibility('password', 'togglePasswordEye', 'togglePasswordEyeOff');
                });
            }
        });
    </script>

</body>
</html>
