<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Opol Primary Healthcare</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
      <link rel="stylesheet" href="{{ asset('assets/fonts/css/stylefont.css') }}">
    <link rel="icon" type="image/x-icon" href="/images/logoMHOV2.ico">
    <style>
        .scrollbar-hidden {
            scrollbar-width: none;
        }

        .scrollbar-hidden::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        /* Toast notifications */
        #toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        #toast-container .toast {
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 22px;
            border-radius: 14px;
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.4;
            box-shadow: 0 10px 40px rgba(15,23,42,0.18);
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.22,1,0.36,1), opacity 0.35s ease;
            max-width: 460px;
            min-width: 280px;
            word-break: break-word;
            letter-spacing: 0.01em;
        }
        #toast-container .toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        #toast-container .toast.hide {
            transform: translateX(120%);
            opacity: 0;
        }
        #toast-container .toast.toast-success {
            background: #ecfdf5;
            border: 2px solid #34d399;
            color: #064e3b;
        }
        #toast-container .toast.toast-error {
            background: #fef2f2;
            border: 2px solid #f87171;
            color: #7f1d1d;
        }
        #toast-container .toast.toast-info {
            background: #eff6ff;
            border: 2px solid #60a5fa;
            color: #1e3a5f;
        }
        #toast-container .toast svg {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
        }
        #toast-container .toast .toast-close {
            margin-left: auto;
            cursor: pointer;
            opacity: 0.4;
            flex-shrink: 0;
            transition: opacity 0.15s;
        }
        #toast-container .toast .toast-close:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="font-sans min-h-screen bg-slate-100 text-slate-800 [background-image:radial-gradient(ellipse_at_80%_0%,rgba(6,182,212,0.06)_0%,transparent_55%)]">

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

    @yield('body')

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

    <div id="toast-container"></div>

    <script>
    function showToast(message, type) {
        if (!message) return
        type = type || 'success'
        var container = document.getElementById('toast-container')
        if (!container) return

        var icons = {
            success: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            error: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            info: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        }

        var toast = document.createElement('div')
        toast.className = 'toast toast-' + type
        toast.innerHTML =
            (icons[type] || icons.info) +
            '<span>' + String(message).replace(/</g, '&lt;') + '</span>' +
            '<span class="toast-close" onclick="this.parentElement.classList.add(\'hide\');setTimeout(function(){this.parentElement.remove()}.bind(this),300)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>'

        container.appendChild(toast)

        // Trigger slide-in
        requestAnimationFrame(function () {
            toast.classList.add('show')
        })

        // Auto-dismiss after 4s
        var dismissTimer = setTimeout(function () {
            dismissToast(toast)
        }, 4000)

        // Remove on click
        toast.addEventListener('click', function (e) {
            if (e.target.closest('.toast-close')) return
            clearTimeout(dismissTimer)
            dismissToast(toast)
        })
    }

    function dismissToast(el) {
        if (!el || el.classList.contains('hide')) return
        el.classList.remove('show')
        el.classList.add('hide')
        setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el) }, 350)
    }
    </script>
</body>
</html>
