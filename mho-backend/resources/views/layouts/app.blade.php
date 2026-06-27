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

    <script>
    // ── Update sidebar's active nav indicator ──
    function updateSidebarActive(url) {
        var sidebarNav = document.querySelector('#sidebar-aside nav');
        if (!sidebarNav) return;

        // Clear all active states
        var links = sidebarNav.querySelectorAll('a');
        Array.prototype.forEach.call(links, function (link) {
            link.classList.remove('bg-gradient-to-br', 'from-green-50/20', 'to-green-100/10', 'text-green-700');
            link.classList.add('text-slate-600', 'hover:bg-slate-50', 'hover:text-slate-900');
            // Find and remove injected badge indicator
            var badge = link.querySelector('.sidebar-active-badge');
            if (badge) badge.remove();
            var icon = link.querySelector('svg');
            if (icon) icon.classList.remove('text-green-600');
        });

        // Find the matching link and activate it
        var target = null;
        Array.prototype.forEach.call(links, function (link) {
            var href = link.getAttribute('href');
            if (!href) return;
            // Compare by route name + query params (handles relative & absolute URLs)
            try {
                var u = new URL(href, window.location.origin);
                var cur = new URL(url, window.location.origin);
                if (u.pathname === cur.pathname && u.search === cur.search) {
                    target = link;
                }
            } catch (_) {
                if (href === url) target = link;
            }
        });

        // Fallback: match by section= query param in the clicked URL
        if (!target) {
            var section = null;
            try { section = new URL(url, window.location.origin).searchParams.get('section'); } catch (_) {}
            if (section) {
                Array.prototype.forEach.call(links, function (link) {
                    var lh = link.getAttribute('href');
                    if (!lh) return;
                    try {
                        var ls = new URL(lh, window.location.origin).searchParams.get('section');
                        if (ls === section) target = link;
                    } catch (_) {}
                });
            }
        }

        if (target) {
            target.classList.remove('text-slate-600', 'hover:bg-slate-50', 'hover:text-slate-900');
            target.classList.add('bg-gradient-to-br', 'from-green-50/20', 'to-green-100/10', 'text-green-700');
            // Inject badge indicator (remove existing first to avoid duplicates)
            var badge = target.querySelector('.sidebar-active-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'sidebar-active-badge absolute left-0 top-[25%] bottom-[25%] w-1.5 rounded-r bg-green-500';
                target.appendChild(badge);
            }
            // Color the icon green
            var icon = target.querySelector('svg');
            if (icon) icon.classList.add('text-green-600');
        }
    }

    // ── Custom page loader for SPA-like navigation ──
    (function () {
        var pageCache = {};
        var mainContent = document.getElementById('main-content');
        if (!mainContent) return; 

        function afterContentSwap(url) {
            // Re-run all inline scripts in main-content
            Array.prototype.forEach.call(mainContent.querySelectorAll('script'), function (oldScript) {
                var newScript = document.createElement('script');
                Array.prototype.forEach.call(oldScript.attributes, function (attr) {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });

            // Fire DOMContentLoaded so script listeners registered via
            // document.addEventListener('DOMContentLoaded', ...) will execute
            document.dispatchEvent(new Event('DOMContentLoaded'));

            // Update sidebar active nav indicator
            updateSidebarActive(url);

            // Restore sidebar scroll in case content reflow affected it
            var sidebarEl = document.getElementById('sidebar-aside');
            if (sidebarEl) {
                var saved = null;
                try { saved = window.localStorage.getItem('sidebar_scroll_top'); } catch (_) {}
                if (saved) sidebarEl.scrollTop = parseInt(saved, 10) || 0;
            }
        }

        document.addEventListener('click', function (e) {
            var a = e.target.closest('a');
            if (!a) return;
            // Only intercept sidebar nav links
            if (!a.closest('#sidebar-aside nav')) return;
            // Skip external links, download links, etc.
            if (a.target === '_blank' || a.hasAttribute('download') || a.getAttribute('rel') === 'external') return;
            // Skip if modifier held
            if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

            var href = a.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript:') || href.startsWith('http') && !href.startsWith(window.location.origin)) return;

            e.preventDefault();

            var url = href;

            // Cached?
            if (pageCache[url]) {
                mainContent.innerHTML = pageCache[url];
                window.scrollTo(0, 0);
                history.pushState(null, '', url);
                afterContentSwap(url);
                return;
            }

            // Show subtle loading indicator
            mainContent.style.opacity = '0.4';
            mainContent.style.transition = 'opacity 0.15s';

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var newContent = doc.getElementById('main-content');
                    if (!newContent) {
                        window.location.href = url;
                        return;
                    }

                    var newHtml = newContent.innerHTML;
                    pageCache[url] = newHtml;
                    mainContent.innerHTML = newHtml;
                    mainContent.style.opacity = '1';
                    window.scrollTo(0, 0);
                    history.pushState(null, '', url);

                    afterContentSwap(url);
                })
                .catch(function () {
                    window.location.href = url;
                });
        });

        // Handle back/forward browser navigation
        window.addEventListener('popstate', function () {
            window.location.reload();
        });
    })();
    </script>

</body>
</html>
