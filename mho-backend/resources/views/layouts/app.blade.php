<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Opol Primary Healthcare</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
      <link rel="stylesheet" href="{{ asset('assets/fonts/css/stylefont.css') }}">
    <link rel="icon" type="image/x-icon" href="/images/logoMHO.ico">
    <style>
        .scrollbar-hidden {
            scrollbar-width: none;
        }

        .scrollbar-hidden::-webkit-scrollbar {
            width: 0;
            height: 0;
        }
    </style>
</head>
<body class="font-sans min-h-screen bg-slate-100 text-slate-800 [background-image:radial-gradient(ellipse_at_80%_0%,rgba(6,182,212,0.06)_0%,transparent_55%)]">

    @yield('body')

    <script>
        window.apiFetch = function (path, options) {
            var token = null
            try {
                if (window.localStorage) {
                    token = window.localStorage.getItem('api_token')
                }
            } catch (e) {
                token = null
            }

            var method = (options && options.method) ? options.method : 'GET'
            var reqHeaders = (options && options.headers) ? Object.assign({}, options.headers) : {}
            reqHeaders['Accept'] = 'application/json'

            if (token) {
                reqHeaders['Authorization'] = 'Bearer ' + token
            }

            // Use axios if available, fall back to fetch
            if (typeof window.axios === 'function') {
                var axiosConfig = {
                    method: method,
                    url: path,
                    headers: reqHeaders,
                    timeout: 30000,
                }
                if (options && options.body && method !== 'GET') {
                    axiosConfig.data = options.body
                }
                if (options && options.signal) {
                    axiosConfig.signal = options.signal
                }
                return window.axios(axiosConfig).then(function (response) {
                    return {
                        ok: response.status >= 200 && response.status < 300,
                        status: response.status,
                        json: function () { return Promise.resolve(response.data) },
                        text: function () { return Promise.resolve(JSON.stringify(response.data)) },
                    }
                }).catch(function (err) {
                    if (err && err.response) {
                        return {
                            ok: false,
                            status: err.response.status,
                            json: function () { return Promise.resolve(err.response.data) },
                            text: function () { return Promise.resolve(JSON.stringify(err.response.data)) },
                        }
                    }
                    return {
                        ok: false,
                        status: 0,
                        json: function () { return Promise.resolve(null) },
                        text: function () { return Promise.resolve('') },
                    }
                })
            }

            // Fallback to native fetch
            var fetchOptions = { method: method, headers: reqHeaders, credentials: 'same-origin' }
            if (options && options.body && method !== 'GET') {
                fetchOptions.body = options.body
            }
            if (options && options.signal) {
                fetchOptions.signal = options.signal
            }
            return fetch(path, fetchOptions).then(function (response) {
                return {
                    ok: response.ok,
                    status: response.status,
                    json: function () { return response.json() },
                    text: function () { return response.text() },
                }
            }).catch(function (err) {
                return {
                    ok: false,
                    status: 0,
                    json: function () { return Promise.resolve(null) },
                    text: function () { return Promise.resolve('') },
                }
            })
        }
    </script>

</body>
</html>
