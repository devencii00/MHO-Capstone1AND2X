        <div id="receptionMessageRoot" class="h-full flex flex-col">
            <div id="receptionMessagesError" class="hidden mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[0.75rem] text-red-700 shrink-0"></div>

            <div class="flex items-center gap-2 mb-4 shrink-0">
                <div class="flex-1">
                    <input id="receptionMessagesSearch" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Search by patient name or email...">
                </div>
                <button type="button" id="receptionMessagesOpenChatBtn" class="px-4 py-2 rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors whitespace-nowrap">Open chat</button>
                <button type="button" id="receptionMessagesRefresh" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-2 text-xs font-semibold text-orange-700 hover:bg-orange-100 whitespace-nowrap">
                    <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                    Refresh
                </button>
            </div>

        <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Left panel: Conversation list --}}
            <div class="lg:col-span-1 min-h-0 flex flex-col border border-slate-100 rounded-2xl overflow-hidden bg-white">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 shrink-0">
                    <div class="text-xs font-semibold text-slate-700">Conversations</div>
                </div>
                <div id="receptionConversationList" class="flex-1 min-h-0 overflow-y-auto scrollbar-hidden"></div>
                <div id="receptionLoadMoreConvos" class="hidden shrink-0 border-t border-slate-100">
                    <button type="button" id="receptionLoadMoreBtn" class="w-full py-2.5 text-[0.75rem] font-semibold text-green-600 hover:bg-green-50 transition-colors">Load more conversations</button>
                </div>
            </div>

            {{-- Right panel: Chat area --}}
            <div class="lg:col-span-2 min-h-0 flex flex-col border border-slate-100 rounded-2xl overflow-hidden bg-white">
                {{-- Sticky header with avatar --}}
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 shrink-0 flex items-center gap-3">
                    <div id="receptionChatAvatar" class="hidden w-8 h-8 rounded-full flex-shrink-0 overflow-hidden"></div>
                    <div class="min-w-0 flex-1">
                        <div id="receptionConversationTitle" class="text-xs font-semibold text-slate-700">Select a conversation</div>
                        <div id="receptionConversationMeta" class="text-[0.7rem] text-slate-500 truncate"></div>
                    </div>
                </div>

                {{-- Scrollable messages --}}
                <div id="receptionMessageList" class="flex-1 min-h-0 bg-white p-4 space-y-2 overflow-y-auto scrollbar-hidden"></div>

                {{-- Sticky send form --}}
                <form id="receptionSendMessageForm" class="border-t border-slate-100 bg-white p-3 flex gap-2 items-stretch shrink-0">
                    <div class="w-14 shrink-0 relative">
                        <button id="receptionQuickMessageToggle" type="button" class="w-full min-h-[56px] rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 disabled:opacity-60 disabled:hover:bg-white flex items-center justify-center" disabled aria-label="Quick message">
                            <x-lucide-zap class="w-5 h-5" />
                        </button>
                        <div id="receptionQuickMessageMenu" class="hidden absolute bottom-full left-0 mb-2 w-72 rounded-xl border border-slate-200 bg-white shadow-[0_10px_30px_rgba(15,23,42,0.12)] overflow-hidden z-20">
                            <button type="button" class="receptionQuickMessageOption w-full text-left px-3 py-2.5 text-xs text-slate-700 hover:bg-slate-50" data-message="Add Your doctor is unavailable, would you like to change doctor or reschedule your visit?">Add Your doctor is unavailable, would you like to change doctor or reschedule your visit?</button>
                            <button type="button" class="receptionQuickMessageOption w-full text-left px-3 py-2.5 text-xs text-slate-700 hover:bg-slate-50 border-t border-slate-100" data-message="Your appointmnt was cancelled.">Your appointmnt was cancelled.</button>
                            <button type="button" class="receptionQuickMessageOption w-full text-left px-3 py-2.5 text-xs text-slate-700 hover:bg-slate-50 border-t border-slate-100" data-message="Your appointment was Cancelled due no No show.">Your appointment was Cancelled due no No show.</button>
                        </div>
                    </div>
                    <div class="flex-1 flex">
                        <label for="receptionMessageText" class="sr-only">Message</label>
                        <textarea id="receptionMessageText" rows="2" class="w-full min-h-[56px] rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none resize-none" placeholder="Type a message…" disabled></textarea>
                    </div>
                    <button id="receptionSendMessageBtn" type="submit" class="px-4 min-h-[56px] self-stretch rounded-xl bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 disabled:opacity-60 disabled:hover:bg-green-600 flex items-center justify-center" disabled>Send</button>
                </form>
            </div>
        </div>
        </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var root = document.getElementById('receptionMessageRoot')
                    var errorBox = document.getElementById('receptionMessagesError')
                    var refreshBtn = document.getElementById('receptionMessagesRefresh')
                    var conversationList = document.getElementById('receptionConversationList')
                    var messageList = document.getElementById('receptionMessageList')
                    var titleEl = document.getElementById('receptionConversationTitle')
                    var metaEl = document.getElementById('receptionConversationMeta')
                    var chatAvatarWrap = document.getElementById('receptionChatAvatar')
                    var searchInput = document.getElementById('receptionMessagesSearch')
                    var openChatBtn = document.getElementById('receptionMessagesOpenChatBtn')
                    var sendForm = document.getElementById('receptionSendMessageForm')
                    var quickMessageToggle = document.getElementById('receptionQuickMessageToggle')
                    var quickMessageMenu = document.getElementById('receptionQuickMessageMenu')
                    var messageText = document.getElementById('receptionMessageText')
                    var sendBtn = document.getElementById('receptionSendMessageBtn')
                    var loadMoreBtn = document.getElementById('receptionLoadMoreBtn')
                    var loadMoreWrap = document.getElementById('receptionLoadMoreConvos')

                    var conversations = []
                    var selectedConversation = null
                    var searchQuery = ''
                    var displayLimit = 6
                    var searchTimer = null

                    if (root && root.dataset.initialized === '1') {
                        return
                    }

                    if (root) {
                        root.dataset.initialized = '1'
                    }

                    // ── Utility ──

                    function showError(message) {
                        if (message && typeof showToast === 'function') showToast(message, 'error')
                    }

                    function escapeHtml(input) {
                        var s = String(input == null ? '' : input)
                        return s
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;')
                    }

                    function escapeAttr(input) {
                        return String(input == null ? '' : input)
                            .replace(/&/g, '&amp;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#39;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                    }

                    function nameForUser(user) {
                        if (!user) return ''
                        var parts = [user.firstname, user.middlename, user.lastname].filter(function (v) { return String(v || '').trim() !== '' })
                        var name = parts.join(' ').trim()
                        if (!name) name = String(user.email || '').trim()
                        if (!name) name = 'Patient'
                        return name
                    }

                    function truncateText(text, maxLen) {
                        if (!text) return ''
                        var s = String(text).trim()
                        if (s.length <= maxLen) return s
                        return s.substring(0, maxLen).replace(/\s+\S*$/, '') + '…'
                    }

                    function closeQuickMessageMenu() {
                        if (quickMessageMenu) quickMessageMenu.classList.add('hidden')
                    }

                    function syncConversationAfterOutgoingMessage(message) {
                        if (!selectedConversation || !message) return

                        var conversationId = String(selectedConversation.conversation_id)
                        var convo = conversations.find(function (item) {
                            return String(item.conversation_id) === conversationId
                        })

                        if (!convo) return

                        convo.latest_message = message
                        convo.updated_at = message.created_at || new Date().toISOString()
                        convo.messages_count = parseInt(convo.messages_count || 0, 10) + 1
                        convo.unread_count = 0

                        selectedConversation = convo

                        // Keep the active conversation at the top after a fresh outgoing message.
                        conversations = [convo].concat(conversations.filter(function (item) {
                            return String(item.conversation_id) !== conversationId
                        }))

                        renderConversations()
                    }

                    function lastMessagePreview(convo) {
                        if (!convo.latest_message) {
                            return '<span class="text-slate-400">No messages yet</span>'
                        }
                        var msg = convo.latest_message
                        var senderPrefix = msg.sender === 'user'
                            ? escapeHtml(nameForUser(convo.user))
                            : 'System/Recept'
                        var text = truncateText(msg.message_text || '', 60)
                        return '<span class="text-slate-500">' + escapeHtml(senderPrefix) + ':</span> ' + escapeHtml(text)
                    }

                    function profileAvatarHtml(user) {
                        var url = user && user.prof_path_url ? String(user.prof_path_url).trim() : ''
                        if (url) {
                            return '<img src="' + escapeAttr(url) + '" alt="" class="w-8 h-8 rounded-full object-cover border border-slate-200 bg-white flex-shrink-0">'
                        }
                        return '<div class="w-8 h-8 rounded-full border border-slate-200 bg-slate-50 text-slate-400 flex items-center justify-center flex-shrink-0">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                                '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>' +
                                '<circle cx="12" cy="7" r="4"/>' +
                            '</svg>' +
                        '</div>'
                    }

                    function getFilteredConversations() {
                        if (!searchQuery) return conversations
                        var q = searchQuery.toLowerCase().trim()
                        return conversations.filter(function (c) {
                            var user = c.user
                            if (!user) return false
                            var name = nameForUser(user).toLowerCase()
                            var email = String(user.email || '').toLowerCase()
                            return name.indexOf(q) !== -1 || email.indexOf(q) !== -1
                        })
                    }

                    function getDisplayedConversations() {
                        var filtered = getFilteredConversations()
                        return {
                            all: filtered,
                            shown: filtered.slice(0, displayLimit),
                            hasMore: filtered.length > displayLimit
                        }
                    }

                    // ── Selection ──

                    function setSelectedConversation(convo) {
                        selectedConversation = convo || null
                        if (!selectedConversation) {
                            if (titleEl) titleEl.textContent = 'Select a conversation'
                            if (metaEl) metaEl.textContent = ''
                            if (chatAvatarWrap) { chatAvatarWrap.classList.add('hidden') }
                            if (quickMessageToggle) quickMessageToggle.disabled = true
                            closeQuickMessageMenu()
                            if (messageText) messageText.disabled = true
                            if (sendBtn) sendBtn.disabled = true
                            if (messageList) messageList.innerHTML = ''
                            return
                        }

                        var patientName = nameForUser(selectedConversation.user)

                        if (titleEl) titleEl.textContent = patientName
                        // Show email or last active meta instead of "Conversation #X"
                        var metaParts = []
                        if (selectedConversation.user && selectedConversation.user.email) {
                            metaParts.push(escapeHtml(selectedConversation.user.email))
                        }
                        if (selectedConversation.updated_at) {
                            var d = new Date(selectedConversation.updated_at)
                            if (!isNaN(d.getTime())) {
                                metaParts.push('Last activity: ' + d.toLocaleDateString())
                            }
                        }
                        if (metaEl) metaEl.textContent = metaParts.join(' · ')

                        // Set avatar in message card header
                        if (chatAvatarWrap) {
                            var user = selectedConversation.user
                            var picUrl = user && user.prof_path_url ? String(user.prof_path_url).trim() : ''
                            if (picUrl) {
                                chatAvatarWrap.innerHTML = '<img src="' + escapeAttr(picUrl) + '" alt="" class="w-8 h-8 rounded-full object-cover border border-slate-200 bg-white">'
                            } else {
                                chatAvatarWrap.innerHTML = '<div class="w-8 h-8 rounded-full border border-slate-200 bg-slate-50 text-slate-400 flex items-center justify-center">' +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                                        '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>' +
                                        '<circle cx="12" cy="7" r="4"/>' +
                                    '</svg>' +
                                '</div>'
                            }
                            chatAvatarWrap.classList.remove('hidden')
                        }

                        if (messageText) messageText.disabled = false
                        if (quickMessageToggle) quickMessageToggle.disabled = false
                        if (sendBtn) sendBtn.disabled = false
                        loadMessages(selectedConversation.conversation_id)
                    }

                    // ── Render conversation list ──

                    function renderConversations() {
                        if (!conversationList) return
                        var result = getDisplayedConversations()
                        var items = result.shown

                        if (!items.length) {
                            conversationList.innerHTML = '<div class="p-4 text-[0.78rem] text-slate-400">' +
                                (searchQuery ? 'No conversations match your search.' : 'No conversations yet.') +
                                '</div>'
                            if (loadMoreWrap) loadMoreWrap.classList.add('hidden')
                            return
                        }

                        var html = ''
                        items.forEach(function (c) {
                            var patientName = escapeHtml(nameForUser(c.user))
                            var previewHtml = lastMessagePreview(c)
                            var isActive = selectedConversation && String(selectedConversation.conversation_id) === String(c.conversation_id)
                            var unreadCount = c.unread_count != null ? parseInt(c.unread_count, 10) : 0

                            html += '<button type="button" class="w-full text-left px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition-colors ' + (isActive ? 'bg-slate-50' : '') + '" data-conversation-id="' + c.conversation_id + '">' +
                                '<div class="flex items-start gap-3">' +
                                    profileAvatarHtml(c.user) +
                                    '<div class="flex-1 min-w-0">' +
                                        '<div class="flex items-center justify-between gap-2">' +
                                            '<div class="text-[0.8rem] font-semibold text-slate-800 truncate">' + patientName + '</div>' +
                                            (unreadCount > 0 ? '<div class="flex items-center gap-1"><span class="inline-flex items-center justify-center min-w-[18px] h-[18px] rounded-full bg-red-500 text-white text-[0.6rem] font-bold px-1">' + unreadCount + '</span></div>' : '') +
                                        '</div>' +
                                        '<div class="text-[0.7rem] mt-0.5 truncate">' + previewHtml + '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</button>'
                        })
                        conversationList.innerHTML = html

                        // Re-bind click events
                        var buttons = conversationList.querySelectorAll('button[data-conversation-id]')
                        buttons.forEach(function (btn) {
                            btn.addEventListener('click', function () {
                                var id = this.getAttribute('data-conversation-id')
                                var convo = conversations.find(function (x) { return String(x.conversation_id) === String(id) })
                                setSelectedConversation(convo || null)
                                renderConversations()
                            })
                        })

                        // Show/hide load more
                        if (loadMoreWrap) {
                            if (result.hasMore) {
                                loadMoreWrap.classList.remove('hidden')
                            } else {
                                loadMoreWrap.classList.add('hidden')
                            }
                        }
                    }

                    // ── Load conversations ──

                    var loadingConversations = false
                    function loadConversations(selectConversationId) {
                        if (loadingConversations) return
                        loadingConversations = true
                        showError('')
                        if (conversationList) conversationList.innerHTML = '<div class="p-4 text-[0.78rem] text-slate-400">Loading…</div>'

                        apiFetch("{{ url('/api/conversations') }}?per_page=200", { method: 'GET' })
                            .then(function (response) {
                                return response.text().then(function (text) {
                                    var data = null
                                    try { data = text ? JSON.parse(text) : null } catch (e) {}
                                    return { ok: response.ok, status: response.status, data: data }
                                })
                            })
                            .then(function (result) {
                                if (!result.ok) {
                                    showError('Failed to load conversations.')
                                    if (conversationList) conversationList.innerHTML = ''
                                    loadingConversations = false
                                    return
                                }
                                var payload = result.data
                                conversations = Array.isArray(payload.data) ? payload.data : (Array.isArray(payload) ? payload : [])
                                displayLimit = 6
                                if (selectConversationId) {
                                    var convo = conversations.find(function (x) { return String(x.conversation_id) === String(selectConversationId) })
                                    if (convo) selectedConversation = convo
                                }
                                renderConversations()
                                if (selectedConversation) {
                                    setSelectedConversation(selectedConversation)
                                } else {
                                    setSelectedConversation(null)
                                }
                                loadingConversations = false
                            })
                            .catch(function () {
                                showError('Network error while loading conversations.')
                                if (conversationList) conversationList.innerHTML = ''
                                loadingConversations = false
                            })
                    }

                    // ── Scroll to bottom of messages reliably ──

                    function scrollMessagesToBottom() {
                        if (!messageList) return
                        requestAnimationFrame(function () {
                            messageList.scrollTop = messageList.scrollHeight
                        })
                    }

                    // ── Load messages ──

                    function loadMessages(conversationId) {
                        if (!messageList || !conversationId) return
                        messageList.innerHTML = '<div class="text-[0.78rem] text-slate-400">Loading messages…</div>'

                        apiFetch("{{ url('/api/conversations') }}/" + encodeURIComponent(conversationId) + "/messages?per_page=200&mark_read=1", { method: 'GET' })
                            .then(function (response) {
                                return response.text().then(function (text) {
                                    var data = null
                                    try { data = text ? JSON.parse(text) : null } catch (e) {}
                                    return { ok: response.ok, status: response.status, data: data }
                                })
                            })
                            .then(function (result) {
                                if (!result.ok) {
                                    messageList.innerHTML = '<div class="text-[0.78rem] text-red-500">Failed to load messages.</div>'
                                    return
                                }
                                var payload = result.data
                                var items = Array.isArray(payload.data) ? payload.data : (Array.isArray(payload) ? payload : [])
                                items = items.slice().reverse()
                                if (!items.length) {
                                    messageList.innerHTML = '<div class="text-[0.78rem] text-slate-400">No messages yet.</div>'
                                    return
                                }

                                var html = ''
                                items.forEach(function (m) {
                                    var isPatient = m.sender === 'user'
                                    var bubbleClass = isPatient ? 'bg-slate-100 text-slate-800' : 'bg-green-600 text-white'
                                    var alignClass = isPatient ? 'justify-start' : 'justify-end'
                                    var senderName = isPatient ? 'Patient' : 'Receptionist/System'
                                    html += '<div class="flex ' + alignClass + '">' +
                                        '<div class="max-w-[85%] rounded-2xl px-3 py-2 ' + bubbleClass + '">' +
                                            '<div class="text-[0.68rem] opacity-80 mb-1">' + escapeHtml(senderName) + '</div>' +
                                            '<div class="text-[0.8rem] whitespace-pre-wrap break-words">' + escapeHtml(m.message_text || '') + '</div>' +
                                        '</div>' +
                                    '</div>'
                                })
                                messageList.innerHTML = html
                                scrollMessagesToBottom()

                                // Mark conversation as read locally and re-render
                                var convo = conversations.find(function (x) { return String(x.conversation_id) === String(conversationId) })
                                if (convo) {
                                    convo.unread_count = 0
                                    renderConversations()
                                }
                            })
                            .catch(function () {
                                messageList.innerHTML = '<div class="text-[0.78rem] text-red-500">Network error while loading messages.</div>'
                            })
                    }

                    // ── Search with debounce ──

                    if (searchInput) {
                        searchInput.addEventListener('input', function () {
                            if (searchTimer) clearTimeout(searchTimer)
                            searchTimer = setTimeout(function () {
                                searchQuery = searchInput.value
                                displayLimit = 6
                                renderConversations()
                            }, 300)
                        })
                    }

                    // ── Open Chat button ──

                    if (openChatBtn) {
                        openChatBtn.addEventListener('click', function () {
                            showError('')
                            var pid = prompt('Enter Patient ID to open a conversation:')
                            if (!pid) return
                            pid = String(pid).trim()
                            if (!pid) return

                            var body = { patient_id: parseInt(pid, 10) }

                            apiFetch("{{ url('/api/conversations') }}", {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify(body)
                            })
                                .then(function (response) {
                                    return response.json().then(function (data) { return { ok: response.ok, data: data } })
                                })
                                .then(function (result) {
                                    if (!result.ok) {
                                        showError('Failed to open conversation.')
                                        return
                                    }
                                    var convo = result.data
                                    loadConversations(convo && convo.conversation_id ? convo.conversation_id : null)
                                })
                                .catch(function () {
                                    showError('Network error while opening conversation.')
                                })
                        })
                    }

                    // ── Load More button ──

                    if (loadMoreBtn) {
                        loadMoreBtn.addEventListener('click', function () {
                            displayLimit += 10
                            renderConversations()
                        })
                    }

                    // ── Refresh button — refreshes both panels simultaneously ──

                    if (refreshBtn) {
                        refreshBtn.addEventListener('click', function () {
                            var convoId = selectedConversation ? selectedConversation.conversation_id : null
                            loadConversations(convoId)
                            if (convoId) loadMessages(convoId)
                        })
                    }

                    if (quickMessageToggle && quickMessageMenu) {
                        quickMessageToggle.addEventListener('click', function (e) {
                            e.stopPropagation()
                            if (quickMessageToggle.disabled) return
                            quickMessageMenu.classList.toggle('hidden')
                        })

                        quickMessageMenu.querySelectorAll('.receptionQuickMessageOption').forEach(function (btn) {
                            btn.addEventListener('click', function () {
                                if (!messageText || messageText.disabled) return
                                var value = String(this.getAttribute('data-message') || '').trim()
                                if (!value) return
                                var current = String(messageText.value || '').trim()
                                messageText.value = current ? (current + '\n' + value) : value
                                messageText.focus()
                                closeQuickMessageMenu()
                            })
                        })

                        document.addEventListener('click', function (e) {
                            if (!quickMessageMenu.contains(e.target) && !quickMessageToggle.contains(e.target)) {
                                closeQuickMessageMenu()
                            }
                        })
                    }

                    // ── Send form ──

                    if (sendForm) {
                        sendForm.addEventListener('submit', function (e) {
                            e.preventDefault()
                            showError('')
                            if (!selectedConversation) return
                            var text = messageText ? String(messageText.value || '').trim() : ''
                            if (!text) return

                            if (sendBtn) sendBtn.disabled = true

                            apiFetch("{{ url('/api/conversations') }}/" + encodeURIComponent(selectedConversation.conversation_id) + "/messages", {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ message_text: text })
                            })
                                .then(function (response) {
                                    return response.json().then(function (data) { return { ok: response.ok, data: data } })
                                })
                                .then(function (result) {
                                    if (!result.ok) {
                                        showError('Failed to send message.')
                                        return
                                    }
                                    if (messageText) messageText.value = ''
                                    syncConversationAfterOutgoingMessage(result.data)
                                    loadMessages(selectedConversation.conversation_id)
                                })
                                .catch(function () {
                                    showError('Network error while sending message.')
                                })
                                .finally(function () {
                                    if (sendBtn) sendBtn.disabled = false
                                })
                        })
                    }

                    // ── Initial load (only if authenticated) ──

                    var _userDataCached = null;
                    try { var _rawCached = window.localStorage ? window.localStorage.getItem('user_data') : null; if (_rawCached) { _userDataCached = JSON.parse(_rawCached); } } catch (_) {}
                    if (_userDataCached && _userDataCached.user_id) {
                        loadConversations()
                    }

                    // ── Reverb listener for real-time messages ──

                    if (typeof window.Echo !== 'undefined' && window.Echo) {
                        var userId = null;
                        try { var data = window.localStorage ? window.localStorage.getItem('user_data') : null; if (data) { var parsed = JSON.parse(data); userId = parsed && parsed.user_id ? parsed.user_id : null; } } catch (_) {}
                        if (userId) {
                            window.Echo.private('notifications.' + userId)
                                .listen('.notification.new', function (e) {
                                    loadConversations(selectedConversation ? selectedConversation.conversation_id : null)
                                });
                        }
                    }
                })
            </script>
