<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Queue Display - Opol Primary Healthcare</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
       <link rel="stylesheet" href="{{ asset('assets/fonts/css/stylefont.css') }}">
         <link rel="icon" type="image/x-icon" href="/images/logoMHOV2.ico">
    <style>
        .scrollbar-hidden { scrollbar-width: none; }
        .scrollbar-hidden::-webkit-scrollbar { width: 0; height: 0; }
        .font-queue { font-family: Helvetica, Arial, sans-serif; }
        :fullscreen #queueDisplayHeader { display: none; }
        :-webkit-full-screen #queueDisplayHeader { display: none; }
        :-moz-full-screen #queueDisplayHeader { display: none; }
        :fullscreen body { overflow: hidden; }
        :-webkit-full-screen body { overflow: hidden; }
        :-moz-full-screen body { overflow: hidden; }
        :fullscreen #queueDisplaySidebar { max-height: 100vh !important; }
        :-webkit-full-screen #queueDisplaySidebar { max-height: 100vh !important; }
        :-moz-full-screen #queueDisplaySidebar { max-height: 100vh !important; }
        :fullscreen #queueDisplayMain { height: 100vh; overflow: hidden; }
        :-webkit-full-screen #queueDisplayMain { height: 100vh; overflow: hidden; }
        :-moz-full-screen #queueDisplayMain { height: 100vh; overflow: hidden; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-white">
<div class="min-h-screen flex flex-col">
    <div id="queueDisplayHeader" class="flex items-center justify-between px-6 md:px-10 py-4 border-b border-slate-800">
        <div>
            <div class="text-[0.75rem] text-slate-400 uppercase tracking-widest">Opol Clinic</div>
            <div class="text-lg md:text-xl font-semibold text-white">Queue Display</div>
            <div class="text-[0.75rem] text-slate-400 mt-0.5">
                <span id="queueDisplayDateLabel"></span>
                @if ($doctorId)
                    · Doctor filter: #{{ $doctorId }}
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button id="queueDisplayFullscreen" type="button" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-800 text-slate-100 text-[0.78rem] font-semibold hover:bg-slate-700">
                <x-lucide-fullscreen class="w-[18px] h-[18px]" />
                Full screen
            </button>
        </div>
    </div>

    <div id="queueDisplayMain" class="flex-1 grid grid-cols-1 lg:grid-cols-3">
        <div class="lg:col-span-2 p-6 md:p-10 flex items-center justify-center">
            <div class="w-full max-w-2xl">
                <div class="text-lg md:text-xl text-green-300 uppercase tracking-[0.25em] mb-3">Now serving</div>
                <div id="queueNowServingGrid" class="grid grid-cols-1 gap-4"></div>
                <div id="queueNowServingEmpty" class="hidden rounded-3xl bg-slate-900/60 border border-slate-700 px-6 md:px-8 py-8 text-center text-slate-300">
                    No queue is currently being served.
                </div>
                <div id="queueDisplayError" class="hidden mt-4 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-[0.85rem] text-red-200"></div>
            </div>
        </div>

        <div id="queueDisplaySidebar" class="border-t lg:border-t-0 lg:border-l border-slate-800 bg-slate-950/60 p-6 md:p-8 flex flex-col h-full overflow-hidden scrollbar-hidden">
            <div class="flex-1 flex flex-col min-h-0">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-lg md:text-xl text-slate-300 uppercase tracking-[0.25em]">Next patients</div>
                    <div id="queueNextMeta" class="text-[0.75rem] text-slate-500"></div>
                </div>
                <div id="queueNextList" class="flex-1 overflow-y-auto space-y-3 scrollbar-hidden"></div>
            </div>
            <div class="flex-1 flex flex-col min-h-0 border-t border-slate-700/30 pt-3">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-lg md:text-xl text-violet-300 uppercase tracking-[0.25em]">On hold</div>
                    <div id="queueOnHoldMeta" class="text-[0.75rem] text-slate-500"></div>
                </div>
                <div id="queueOnHoldList" class="flex-1 overflow-y-auto space-y-3 scrollbar-hidden"></div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        function localDateIso() {
            var now = new Date();
            var y = now.getFullYear();
            var m = String(now.getMonth() + 1).padStart(2, '0');
            var d = String(now.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + d;
        }
        var date = @json($date);
        if (!date) {
            date = localDateIso();
        }
        
        var doctorId = @json($doctorId);

        var dateLabel = document.getElementById('queueDisplayDateLabel');
        var btnFullscreen = document.getElementById('queueDisplayFullscreen');

        var errorBox = document.getElementById('queueDisplayError');
        var nowEmpty = document.getElementById('queueNowServingEmpty');
        var nowGrid = document.getElementById('queueNowServingGrid');
        var nextList = document.getElementById('queueNextList');
        var nextMeta = document.getElementById('queueNextMeta');
        var onHoldList = document.getElementById('queueOnHoldList');
        var onHoldMeta = document.getElementById('queueOnHoldMeta');

        function showError(message) {
            if (!errorBox) return;
            errorBox.textContent = message || '';
            errorBox.classList.toggle('hidden', !message);
        }

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function pad3(n) {
            var s = String(n == null ? '' : n);
            if (!s) return '---';
            while (s.length < 3) s = '0' + s;
            return s;
        }

        function displayQueueLabel(item, sideSpaces) {
            var code = '';
            if (item && item.queue_code) {
                code = String(item.queue_code);
            } else if (item && item.queue_number != null) {
                return pad3(item.queue_number);
            } else {
                return '---';
            }
            // Add spacing around the dash
            var s = sideSpaces || 1;
            var sp = '';
            for (var i = 0; i < s; i++) sp += ' ';
            return code.replace('-', sp + '-' + sp);
        }

        function roomLabel(roomNumber) {
            if (roomNumber == null) return '';
            var n = parseInt(roomNumber, 10);
            if (isNaN(n) || n < 1) return '';
            return '[ROOM ' + n + ']';
        }

        function waitLabel(minutes) {
            if (minutes == null) return '';
            var n = parseInt(minutes, 10);
            if (isNaN(n) || n < 1) return '';
            return 'Est. ' + n + 'min - ' + (n + 5) + 'min';
        }

        function formatDisplayName(rawName, fallback) {
            if (!rawName) return fallback || '---';
            var name = String(rawName).trim();
            if (!name) return fallback || '---';

            // Email fallback: show first 5 chars + ..... + @domain
            var atIdx = name.indexOf('@');
            if (atIdx > -1) {
                var prefix = name.substring(0, atIdx);
                var domain = name.substring(atIdx);
                if (prefix.length > 5) {
                    return prefix.substring(0, 5) + '.....' + domain;
                }
                return name;
            }

            // Skip any fallback that contains # or ID patterns — return empty instead
            if (name.indexOf('#') > -1 || /^[0-9]+$/.test(name.replace(/\s/g, ''))) {
                return fallback || '---';
            }

            // Parse as "First Middle Last" or "First Last"
            var parts = name.split(/\s+/).filter(function (p) { return p.length > 0; });
            if (parts.length === 0) return fallback || '---';

            var last = parts[parts.length - 1];
            var firstInit = parts[0].charAt(0).toUpperCase() + '.';
            var middleInit = '';
            if (parts.length >= 3 && parts[1].length > 0) {
                middleInit = ' ' + parts[1].charAt(0).toUpperCase() + '.';
            }
            return firstInit + middleInit + ' ' + last;
        }

        function drDisplayName(rawName, fallback) {
            var n = formatDisplayName(rawName, fallback);
            if (!n || n === fallback || n.indexOf('@') > -1) return n;
            return 'Dr. ' + n;
        }

        function patientDisplayName(rawName, fallback) {
            return formatDisplayName(rawName, fallback);
        }

        function render(payload) {
            if (dateLabel) dateLabel.textContent = 'Date: ' + (payload && payload.date ? payload.date : date);

            // ── Now Serving ──
            var serving = payload && Array.isArray(payload.now_serving) ? payload.now_serving : [];
            if (nowGrid) {
                if (!serving.length) {
                    nowGrid.innerHTML = '';
                } else {
                    nowGrid.innerHTML = serving.map(function (item) {
                        var qn = displayQueueLabel(item);
                        var docName = drDisplayName(item.doctor && item.doctor.name ? item.doctor.name : '', '');
                        var patName = patientDisplayName(item.patient && item.patient.name ? item.patient.name : '', '');
                        var room = item && item.room_number != null ? String(item.room_number) : '-';

                      return '' +
    '<div class="rounded-3xl bg-slate-900/60 border border-slate-700 shadow-[0_0_50px_rgba(8,47,73,0.7)]">' +
        '<div class="px-6 md:px-10 pt-6 md:pt-8 pb-2">' +
            '<div class="flex items-center justify-between">' +
                '<div class="text-5xl md:text-7xl font-bold font-queue text-white tracking-wider whitespace-pre">' + escapeHtml(qn) + '</div>' +
                '<div class="text-right">' +
                    '<div class="text-[0.7rem] text-slate-500 uppercase tracking-wider">Room</div>' +
                    '<div class="text-2xl md:text-4xl font-bold font-queue text-green-300 whitespace-pre">' + escapeHtml(room) + '</div>' +
                '</div>' +
            '</div>' +
        '</div>' +
        '<div class="px-6 md:px-10 pb-6 md:pb-8 pt-3 border-t border-slate-700/50">' +
            '<div class="flex items-center justify-between">' +
                '<div>' +
                    '<div class="text-xl md:text-3xl font-semibold text-white">' + (patName || 'Patient') + '</div>' +
                '</div>' +
                '<div class="text-right">' +
                    '<div class="text-lg md:text-2xl font-semibold text-sky-200">' + (docName || 'Doctor') + '</div>' +
                '</div>' +
            '</div>' +
        '</div>' +
    '</div>';
                    }).join('');
                }
            }

            if (nowEmpty) {
                nowEmpty.classList.toggle('hidden', serving.length > 0);
            }

            // ── Next Patients ──
            var next = payload && Array.isArray(payload.next) ? payload.next : [];
            var counts = payload && payload.counts ? payload.counts : null;
            if (nextMeta) {
                var waitingCount = counts && counts.waiting != null ? String(counts.waiting) : '';
                nextMeta.textContent = waitingCount ? (waitingCount + ' waiting') : '';
            }
            if (nextList) {
                if (!next.length) {
                    nextList.innerHTML = '<div class="rounded-2xl border border-slate-800 bg-slate-900/40 px-4 py-4 text-[0.85rem] text-slate-300">No patients waiting.</div>';
                } else {
                    nextList.innerHTML = next.map(function (q) {
                        var qn = displayQueueLabel(q, 2);
                        var docName = drDisplayName(q.doctor && q.doctor.name ? q.doctor.name : '', '');
                        var patName = patientDisplayName(q.patient && q.patient.name ? q.patient.name : '', '');
                        var wait = waitLabel(q && q.estimated_wait_minutes != null ? q.estimated_wait_minutes : null);
                        return '' +
                            '<div class="rounded-2xl border border-slate-800 bg-slate-900/40 px-4 py-3">' +
                                '<div class="flex items-center justify-between gap-3">' +
                                    '<div>' +
                                        '<div class="text-lg md:text-xl font-bold font-queue text-white whitespace-pre">' + escapeHtml(qn) + '</div>' +
                                        '<div class="text-sm text-slate-300 mt-0.5">' + (patName || 'Patient') + '</div>' +
                                        (wait ? '<div class="text-xs text-slate-400 mt-0.5">' + escapeHtml(wait) + '</div>' : '') +
                                    '</div>' +
                                    '<div class="text-right shrink-0">' +
                                        '<div class="text-sm font-semibold text-sky-200">' + (docName || 'Doctor') + '</div>' +
                                        (q && q.priority_level != null ? '<div class="text-[0.68rem] text-slate-500 mt-0.5">Priority ' + escapeHtml(q.priority_level) + '</div>' : '') +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                    }).join('');
                }
            }

            // ── On Hold ──
            var onHold = payload && Array.isArray(payload.on_hold) ? payload.on_hold : [];
            if (onHoldMeta) {
                onHoldMeta.textContent = onHold.length ? (onHold.length + ' on hold') : '';
            }
            if (onHoldList) {
                if (!onHold.length) {
                    onHoldList.innerHTML = '<div class="rounded-2xl border border-slate-800 bg-slate-900/40 px-4 py-4 text-[0.85rem] text-slate-300">No patients on hold.</div>';
                } else {
                    onHoldList.innerHTML = onHold.map(function (q) {
                        var qn = displayQueueLabel(q, 2);
                        var docName = drDisplayName(q.doctor && q.doctor.name ? q.doctor.name : '', '');
                        var patName = patientDisplayName(q.patient && q.patient.name ? q.patient.name : '', '');
                        var wait = waitLabel(q && q.estimated_wait_minutes != null ? q.estimated_wait_minutes : null);
                        return '' +
                            '<div class="rounded-2xl border border-violet-800/40 bg-violet-900/20 px-4 py-3">' +
                                '<div class="flex items-center justify-between gap-3">' +
                                    '<div>' +
                                        '<div class="text-lg md:text-xl font-bold font-queue text-violet-100 whitespace-pre">' + escapeHtml(qn) + '</div>' +
                                        '<div class="text-sm text-violet-200/80 mt-0.5">' + (patName || 'Patient') + '</div>' +
                                        (wait ? '<div class="text-xs text-violet-300/60 mt-0.5">' + escapeHtml(wait) + '</div>' : '') +
                                    '</div>' +
                                    '<div class="text-right shrink-0">' +
                                        '<div class="text-sm font-semibold text-violet-200">' + (docName || 'Doctor') + '</div>' +
                                        (q && q.priority_level != null ? '<div class="text-[0.68rem] text-violet-400/50 mt-0.5">Priority ' + escapeHtml(q.priority_level) + '</div>' : '') +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                    }).join('');
                }
            }
        }

        function load() {
            showError('');
            var url = "{{ route('queue.display.data') }}" + '?date=' + encodeURIComponent(date || '');
            if (doctorId) {
                url += '&doctor_id=' + encodeURIComponent(doctorId);
            }
            // Use native fetch - this page is anonymous so window.axios may not be available
            var headers = { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
            fetch(url, { headers: headers })
                .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                .then(function (data) {
                    render(data);
                })
                .catch(function () {
                    // silent fail - polling will retry
                });
        }

        if (btnFullscreen) {
            btnFullscreen.addEventListener('click', function () {
                try {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        document.exitFullscreen();
                    }
                } catch (_) {
                }
            });
        }

        load();

        // ── Realtime via Reverb (public channel) ──
        (function () {
            function attachEchoListener() {
                if (typeof window.Echo !== 'undefined' && window.Echo) {
                    try {
                        window.Echo.channel('queue.display')
                            .listen('.queue.updated', function (data) {
                                var timeReceived = Date.now();
                                if (data && data.fired_at) {
                                    var absoluteDelay = timeReceived - data.fired_at;
                                    console.log('[QueueDisplay] Reverb fired: ' + absoluteDelay + 'ms');
                                }
                                document.dispatchEvent(new CustomEvent('queue:updated', { detail: data }));
                                load();
                            });
                        console.log('[QueueDisplay] Echo listener attached to queue.display (public channel)');
                        return true;
                    } catch (e) {
                        console.error('[QueueDisplay] Echo subscribe failed:', e);
                    }
                }
                return false;
            }

            // Try immediately — Vite module may not be loaded yet on standalone public page
            if (!attachEchoListener()) {
                console.log('[QueueDisplay] Echo not ready yet, retrying in 1.5s...');
                setTimeout(function () {
                    if (!attachEchoListener()) {
                        console.warn('[QueueDisplay] Echo not available - using 30s polling fallback');
                        setInterval(load, 30000);
                    }
                }, 1500);
            }
        })();

        // Static estimated wait - calculated on page load from API data
    })();
</script>
</body>
</html>
