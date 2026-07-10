@props(['role' => 'admin'])

@php
    $roleKey = strtolower($role ?? 'admin');
    $roleNames = [
        'admin' => 'Admin',
        'doctor' => 'Doctor',
        'receptionist' => 'Receptionist',
        'patient' => 'Patient',
    ];
    $roleLabel = $roleNames[$roleKey] ?? ucfirst($roleKey);
@endphp

<header class="sticky top-0 z-30 bg-white/85 backdrop-blur-md border-b border-slate-200 px-8 h-15 flex items-center justify-between">
    <div class="flex items-center gap-1 text-slate-400 text-[0.82rem]">
        <span>Opol Clinic</span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span class="text-slate-500">{{ $roleLabel }}</span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span class="text-slate-700 font-semibold">Dashboard</span>
    </div>
    <div class="relative flex items-center gap-3">
        @if ($roleKey === 'receptionist')
        <!-- Messages Button -->
        <button id="headerMessagesButton" class="px-3.5 h-8.5 rounded-lg border border-slate-200 bg-white flex items-center gap-2 text-slate-500 hover:border-green-400 hover:text-green-600 relative">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            <span class="text-[0.78rem] font-semibold text-slate-600">Messages</span>
            <span id="headerMessagesBadge" class="hidden absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 border border-white text-white text-[0.62rem] font-semibold leading-[16px] text-center"></span>
        </button>
        @endif

        <!-- Notifications Button -->
        <button id="headerNotificationButton" class="px-3.5 h-8.5 rounded-lg border border-slate-200 bg-white flex items-center gap-2 text-slate-500 hover:border-green-400 hover:text-green-600 relative">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            <span class="text-[0.78rem] font-semibold text-slate-600">Notifications</span>
            <span id="headerNotificationBadge" class="hidden absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 border border-white text-white text-[0.62rem] font-semibold leading-[16px] text-center"></span>
        </button>

        <div id="headerNotificationPanel" class="hidden absolute right-0 top-10 w-80 max-h-[28rem] bg-white border border-slate-200 rounded-2xl shadow-[0_10px_30px_rgba(15,23,42,0.18)] overflow-hidden">
            <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between">
                <p class="text-[0.75rem] font-semibold text-slate-800">Notifications</p>
                <button id="headerMarkAllReadBtn" type="button" class="text-[0.65rem] font-semibold text-green-600 hover:text-green-700 hidden">Mark all read</button>
            </div>
            <div id="headerNotificationBody" class="max-h-72 overflow-y-auto scrollbar-hidden">
                <div class="px-3 py-3 text-[0.75rem] text-slate-400">
                    Loading notifications...
                </div>
            </div>
        </div>

</header>

@if ($roleKey === 'receptionist')
<!-- Messages Modal (must be outside <header> — backdrop-filter on header breaks fixed positioning) -->
<div id="headerMessagesModal" class="hidden fixed inset-0 z-[70] flex items-center justify-center bg-black/70">
    <div class="w-full max-w-4xl h-[85vh] mx-4 rounded-2xl bg-white border border-slate-200 shadow-[0_20px_80px_rgba(15,23,42,0.35)] flex flex-col overflow-hidden">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 shrink-0">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Patient Messages</h2>
                <p class="text-xs text-slate-500">Chat with patients for doctor reassignment and queue updates.</p>
            </div>
            <button type="button" id="headerMessagesModalClose" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <!-- Modal Body - inner panels handle scrolling -->
        <div class="flex-1 min-h-0 p-5">
            @include('dashviews.receptionist.recept_message')
        </div>
    </div>
</div>
@endif

<template id="headerIconNotificationAppointment">
    <x-lucide-calendar class="w-4 h-4 text-green-600" />
</template>
<template id="headerIconNotificationPayment">
    <x-lucide-credit-card class="w-4 h-4 text-green-600" />
</template>
<template id="headerIconNotificationSystem">
    <x-lucide-info class="w-4 h-4 text-green-600" />
</template>

<script>
    (function () {
        function headerApiFetch(path, options) {
            var method = (options && options.method) ? options.method : 'GET'
            var reqHeaders = (options && options.headers) ? Object.assign({}, options.headers) : {}
            reqHeaders['Accept'] = 'application/json'

            var token = null
            try { token = window.localStorage ? window.localStorage.getItem('api_token') : null } catch (_) { token = null }
            if (token) {
                reqHeaders['Authorization'] = 'Bearer ' + token
            }

            if (typeof window.axios === 'function') {
                var config = { method: method, url: path, headers: reqHeaders }
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
            var fetchOptions = { method: method, headers: reqHeaders }
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

        function renderNotifications(container, items) {
            if (!container) {
                return
            }

            var iconAppointment = (function () {
                var tpl = document.getElementById('headerIconNotificationAppointment')
                return tpl ? String(tpl.innerHTML || '').trim() : ''
            })()
            var iconPayment = (function () {
                var tpl = document.getElementById('headerIconNotificationPayment')
                return tpl ? String(tpl.innerHTML || '').trim() : ''
            })()
            var iconSystem = (function () {
                var tpl = document.getElementById('headerIconNotificationSystem')
                return tpl ? String(tpl.innerHTML || '').trim() : ''
            })()

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;')
            }

            function timeAgo(dateStr) {
                if (!dateStr) return ''
                var dt = new Date(dateStr)
                if (isNaN(dt.getTime())) return ''
                var now = new Date()
                var diffMs = now - dt
                var diffMin = Math.floor(diffMs / 60000)
                if (diffMin < 1) return 'Just now'
                if (diffMin < 60) return diffMin + 'm ago'
                var diffHrs = Math.floor(diffMin / 60)
                if (diffHrs < 24) return diffHrs + 'h ago'
                var diffDays = Math.floor(diffHrs / 24)
                if (diffDays < 7) return diffDays + 'd ago'
                return dt.toLocaleDateString()
            }

            if (!items || !items.length) {
                container.innerHTML = '<div class="px-3 py-8 text-[0.75rem] text-slate-400 text-center">No notifications at the moment.</div>'
                return
            }

            var html = ''
            items.forEach(function (item) {
                var type = item && item.type ? String(item.type) : 'system'
                var title = item && item.title ? String(item.title) : 'Notification'
                var body = item && item.message ? String(item.message) : ''
                var createdAt = item && item.created_at ? String(item.created_at) : ''
                var timeLabel = timeAgo(createdAt)
                var isUnread = item && (item.read_at === null || item.read_at === undefined || item.is_read === false)
                var nav = item && item.navigation ? item.navigation : null
                var navRoute = nav && nav.route ? nav.route : null
                var notificationId = item && item.notification_id != null ? item.notification_id : ''

                var iconHtml = type === 'appointment' ? iconAppointment : (type === 'payment' ? iconPayment : iconSystem)
                var unreadDot = isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-green-500 shrink-0 mt-1.5"></span>' : ''

                html += '<div class="px-3 py-2.5 border-b border-slate-50 last:border-0 flex gap-2.5 hover:bg-slate-50 transition-colors cursor-pointer notification-item" data-notification-id="' + escapeHtml(notificationId) + '" data-navigate="' + escapeHtml(navRoute || '') + '">' +
                    '<div class="mt-0.5 shrink-0">' +
                    iconHtml +
                    '</div>' +
                    '<div class="flex-1 min-w-0">' +
                    '<div class="flex items-start justify-between gap-1">' +
                    '<div class="text-[0.75rem] font-semibold text-slate-800 truncate ' + (isUnread ? '' : 'text-slate-600') + '">' + escapeHtml(title) + '</div>' +
                    unreadDot +
                    '</div>' +
                    '<div class="text-[0.7rem] text-slate-500 line-clamp-2 mt-0.5">' + escapeHtml(body) + '</div>' +
                    '<div class="flex items-center justify-between mt-1">' +
                    '<span class="text-[0.62rem] text-slate-400">' + escapeHtml(timeLabel) + '</span>' +
                    (navRoute ? '<span class="text-[0.62rem] text-green-600 font-semibold">' + escapeHtml(nav.label || 'View') + ' →</span>' : '') +
                    '</div>' +
                    '</div>' +
                    '</div>'
            })

            container.innerHTML = html

            // Attach click handlers for notification navigation
            container.querySelectorAll('.notification-item').forEach(function (el) {
                el.addEventListener('click', function () {
                    var notifId = this.getAttribute('data-notification-id')
                    var navigate = this.getAttribute('data-navigate')

                    // Mark as read on click
                    if (notifId) {
                        markSingleNotificationRead(notifId)
                    }

                    // Navigate if route is defined
                    if (navigate) {
                        handleNotificationNavigation(navigate, itemFromElement(el))
                    }

                    // Close panel
                    var panel = document.getElementById('headerNotificationPanel')
                    if (panel) panel.classList.add('hidden')
                })
            })
        }

        function itemFromElement(el) {
            var idx = Array.from(el.parentNode.children).indexOf(el)
            var body = document.getElementById('headerNotificationBody')
            if (!body) return null
            var items = body._notificationItems || []
            return items[idx] || null
        }

        function markSingleNotificationRead(notifId) {
            headerApiFetch("{{ url('/api/notifications') }}/" + encodeURIComponent(notifId), {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ is_read: true })
            }).catch(function () {})
        }

        function handleNotificationNavigation(route, item) {
            if (!item || !item.navigation) return

            var nav = item.navigation
            var action = nav.action || ''
            var navigateUrl = nav.navigate_url || ''

            // Special action: open messages modal
            if (action === 'open-messages-modal') {
                var msgBtn = document.getElementById('headerMessagesButton')
                if (msgBtn) {
                    msgBtn.click()
                }
                return
            }

            // Standard navigation: find sidebar link and click it (uses page roller)
            if (navigateUrl) {
                var sidebar = document.getElementById('sidebar-aside')
                if (sidebar) {
                    // Normalize both URLs for comparison (handles relative vs absolute mismatch)
                    var normalizedNavUrl = new URL(navigateUrl, window.location.origin).href.toLowerCase()
                    var links = sidebar.querySelectorAll('nav a[href]')
                    var found = false
                    Array.prototype.some.call(links, function (link) {
                        try {
                            var linkUrl = new URL(link.getAttribute('href'), window.location.origin).href.toLowerCase()
                            if (linkUrl === normalizedNavUrl) {
                                link.click()
                                found = true
                                return true
                            }
                        } catch (e) {}
                        return false
                    })
                    if (found) return
                }
                // Fallback to full page reload if sidebar link not found
                window.location.href = navigateUrl
                return
            }

            // Fallback: dispatch custom event for legacy support
            var event = new CustomEvent('notification:navigate', {
                detail: {
                    route: route,
                    notification: item,
                    params: nav.params || {}
                }
            })
            document.dispatchEvent(event)
        }

        function setupEchoListener() {
            if (!window.Echo) return

            headerApiFetch("{{ url('/api/me') }}", { method: 'GET' })
                .then(function (response) {
                    return response.json()
                })
                .then(function (userData) {
                    if (!userData || !userData.user_id) return
                    var userId = String(userData.user_id)
                    try {
                        // ── Notification channel ──
                        window.Echo.private('notifications.' + userId)
                            .listen('.notification.new', function () {
                                document.dispatchEvent(new Event('header:refresh-badges'))
                                var panel = document.getElementById('headerNotificationPanel')
                                if (panel && !panel.classList.contains('hidden')) {
                                    loadNotificationPanel(false)
                                }
                            })

                        // ── Messages channel ──
                        window.Echo.private('messages.' + userId)
                            .listen('.message.new', function () {
                                // Refresh badges (message count)
                                document.dispatchEvent(new Event('header:refresh-badges'))
                                // Trigger conversation list refresh if messages modal is open
                                var msgModal = document.getElementById('headerMessagesModal')
                                if (msgModal && !msgModal.classList.contains('hidden')) {
                                    var refreshBtn = document.getElementById('receptionMessagesRefresh')
                                    if (refreshBtn) refreshBtn.click()
                                }
                            })
                    } catch (e) {
                        // Echo setup failed silently
                    }
                })
                .catch(function () {})
        }

        function extractNotificationItems(payload) {
            if (Array.isArray(payload)) {
                return payload
            }
            if (payload && Array.isArray(payload.data)) {
                return payload.data
            }
            if (payload && Array.isArray(payload.notifications)) {
                return payload.notifications
            }
            return []
        }

        function readJsonResult(response) {
            return response.json().then(function (data) {
                return { ok: response.ok, data: data }
            }).catch(function () {
                return { ok: response.ok, data: null }
            })
        }

        function isMessageNotification(item) {
            if (!item) {
                return false
            }

            var type = String(item.type || '').toLowerCase()
            if (type === 'message') {
                return true
            }

            var message = String(item.message || '').trim().toLowerCase()
            return message === 'a patient has sent a message.'
        }

        function formatBadgeCount(count) {
            var numeric = parseInt(count || 0, 10)
            if (!numeric || numeric < 1) {
                return ''
            }

            return numeric > 99 ? '99+' : String(numeric)
        }

        function setBadgeCount(element, count) {
            if (!element) {
                return
            }

            var label = formatBadgeCount(count)
            element.textContent = label
            element.classList.toggle('hidden', label === '')
        }

        function countUnreadBuckets(items) {
            var messageCount = 0
            var notificationCount = 0

            ;(Array.isArray(items) ? items : []).forEach(function (item) {
                if (!item) return
                var isUnread = item.read_at === null || item.read_at === undefined || item.is_read === false
                if (!isUnread) return

                if (isMessageNotification(item)) {
                    messageCount += 1
                    return
                }

                notificationCount += 1
            })

            return {
                messageCount: messageCount,
                notificationCount: notificationCount,
            }
        }

        function fetchNotifications(query) {
            return headerApiFetch("{{ url('/api/notifications') }}" + (query || ''), { method: 'GET' })
                .then(readJsonResult)
        }

        function fetchUnreadCounts() {
            return headerApiFetch("{{ url('/api/notifications/unread-count') }}", { method: 'GET' })
                .then(readJsonResult)
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        return { total: 0, messages: 0, notifications: 0 }
                    }
                    return result.data
                })
                .catch(function () {
                    return { total: 0, messages: 0, notifications: 0 }
                })
        }

        function markNotificationsRead(items) {
            var unreadIds = (Array.isArray(items) ? items : [])
                .filter(function (item) {
                    return item && (item.read_at === null || item.read_at === undefined) && item.notification_id != null
                })
                .map(function (item) {
                    return String(item.notification_id)
                })

            if (!unreadIds.length) {
                return Promise.resolve()
            }

            return Promise.all(unreadIds.map(function (id) {
                return headerApiFetch("{{ url('/api/notifications') }}/" + encodeURIComponent(id), {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ is_read: true })
                }).catch(function () {
                    return null
                })
            })).then(function () {
                return null
            })
        }

        document.addEventListener('DOMContentLoaded', function () {
            var button = document.getElementById('headerNotificationButton')
            var panel = document.getElementById('headerNotificationPanel')
            var body = document.getElementById('headerNotificationBody')
            var notificationBadge = document.getElementById('headerNotificationBadge')
            var messagesBadge = document.getElementById('headerMessagesBadge')
            var msgOpenBtn = document.getElementById('headerMessagesButton')
            var msgModal = document.getElementById('headerMessagesModal')
            var msgCloseBtn = document.getElementById('headerMessagesModalClose')
            var markAllBtn = document.getElementById('headerMarkAllReadBtn')

            function refreshHeaderBadges() {
                return fetchUnreadCounts()
                    .then(function (counts) {
                        setBadgeCount(messagesBadge, counts.messages)
                        setBadgeCount(notificationBadge, counts.notifications)
                        return counts
                    })
                    .catch(function () {
                        setBadgeCount(messagesBadge, 0)
                        setBadgeCount(notificationBadge, 0)
                        return { total: 0, messages: 0, notifications: 0 }
                    })
            }

            function markMessageNotificationsRead() {
                return fetchNotifications('?unread_only=1&per_page=100&exclude_message=0')
                    .then(function (result) {
                        if (!result.ok || !result.data) {
                            return null
                        }

                        var unreadItems = extractNotificationItems(result.data).filter(function (item) {
                            return item && (item.read_at === null || item.read_at === undefined) && isMessageNotification(item)
                        })

                        if (!unreadItems.length) {
                            return null
                        }

                        return markNotificationsRead(unreadItems)
                    })
                    .then(function () {
                        return refreshHeaderBadges()
                    })
                    .catch(function () {
                        return refreshHeaderBadges()
                    })
            }

            function markNonMessageNotificationsRead() {
                return fetchNotifications('?unread_only=1&per_page=100')
                    .then(function (result) {
                        if (!result.ok || !result.data) {
                            return null
                        }

                        var unreadItems = extractNotificationItems(result.data).filter(function (item) {
                            return item && (item.read_at === null || item.read_at === undefined) && !isMessageNotification(item)
                        })

                        if (!unreadItems.length) {
                            return null
                        }

                        return markNotificationsRead(unreadItems)
                    })
                    .then(function () {
                        return refreshHeaderBadges()
                    })
                    .catch(function () {
                        return refreshHeaderBadges()
                    })
            }

            function loadNotificationPanel(markAsRead) {
                if (body) {
                    body.innerHTML = '<div class="px-3 py-3 text-[0.75rem] text-slate-400">Loading notifications...</div>'
                }
                if (markAllBtn) markAllBtn.classList.add('hidden')

                return fetchNotifications('?per_page=15')
                    .then(function (result) {
                        if (!result.ok || !result.data) {
                            if (body) {
                                body.innerHTML = '<div class="px-3 py-3 text-[0.75rem] text-slate-400">Unable to load notifications.</div>'
                            }
                            return
                        }

                        var items = extractNotificationItems(result.data)
                        // Store items for later reference
                        body._notificationItems = items
                        renderNotifications(body, items)

                        // Show mark all read button if there are unread items
                        var hasUnread = items.some(function (item) {
                            return item && (item.read_at === null || item.read_at === undefined)
                        })
                        if (markAllBtn) markAllBtn.classList.toggle('hidden', !hasUnread)

                        if (markAsRead === false) {
                            return refreshHeaderBadges()
                        }

                        return markNonMessageNotificationsRead()
                    })
                    .catch(function () {
                        if (body) {
                            body.innerHTML = '<div class="px-3 py-3 text-[0.75rem] text-slate-400">Unable to load notifications.</div>'
                        }
                    })
            }

            if (!button || !panel) {
                return
            }

            if (button.dataset.headerInitialized === '1') {
                document.dispatchEvent(new Event('header:refresh-badges'))
                return
            }

            button.dataset.headerInitialized = '1'

            refreshHeaderBadges()

            document.addEventListener('header:refresh-badges', function () {
                refreshHeaderBadges()
                if (panel && !panel.classList.contains('hidden')) {
                    loadNotificationPanel(false)
                }
            })

            button.addEventListener('click', function (e) {
                e.stopPropagation()
                var isHidden = panel.classList.contains('hidden')
                if (isHidden) {
                    loadNotificationPanel(true)
                }
                panel.classList.toggle('hidden')
            })

            document.addEventListener('click', function () {
                if (panel && !panel.classList.contains('hidden')) {
                    panel.classList.add('hidden')
                }
            })

            // ── Mark All As Read ──
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function (e) {
                    e.stopPropagation()
                    headerApiFetch("{{ url('/api/notifications/read-all') }}", {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' }
                    }).then(function () {
                        markAllBtn.classList.add('hidden')
                        return loadNotificationPanel(false)
                    }).catch(function () {})
                })
            }

            // ── Real-time Echo listener for new notifications ──
            setupEchoListener()

            // ── Messages Modal toggle ──
            if (msgOpenBtn && msgModal) {
                msgOpenBtn.addEventListener('click', function (e) {
                    e.stopPropagation()
                    msgModal.classList.remove('hidden')
                    msgModal.classList.add('flex')

                    // Refresh conversation data when modal opens
                    var refreshBtn = document.getElementById('receptionMessagesRefresh')
                    if (refreshBtn) refreshBtn.click()

                    markMessageNotificationsRead()
                })

                if (msgCloseBtn) {
                    msgCloseBtn.addEventListener('click', function () {
                        msgModal.classList.add('hidden')
                        msgModal.classList.remove('flex')
                    })
                }

                // Close on backdrop click
                msgModal.addEventListener('click', function (e) {
                    if (e.target === msgModal) {
                        msgModal.classList.add('hidden')
                        msgModal.classList.remove('flex')
                    }
                })
            }
        })

        // ── Escape key closes messages modal ──
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                var msgModal = document.getElementById('headerMessagesModal');
                if (msgModal && !msgModal.classList.contains('hidden')) {
                    msgModal.classList.add('hidden');
                    msgModal.classList.remove('flex');
                }
            }
        });

        // ── Reverb listener for real-time notifications ──
        (function () {
            var userId = null;
            try { var data = window.localStorage ? window.localStorage.getItem('user_data') : null; if (data) { var parsed = JSON.parse(data); userId = parsed && parsed.user_id ? parsed.user_id : null; } } catch (_) {}
            if (typeof window.Echo !== 'undefined' && window.Echo && userId) {
                window.Echo.private('notifications.' + userId)
                    .listen('.notification.new', function (e) {
                        document.dispatchEvent(new Event('header:refresh-badges'));
                    });
            }
        })();
    })()
</script>
