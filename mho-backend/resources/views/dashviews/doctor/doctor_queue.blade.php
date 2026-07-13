@php
    $doctorQueueItems = collect($doctorTodayQueue ?? [])
        ->sortBy(function ($queue) {
            $status = strtolower((string) ($queue->status ?? ''));
            $rank = match ($status) {
                'waiting', 'skipped' => 0,
                'serving' => 1,
                'on_hold' => 2,
                default => 3,
            };
            $priority = (int) ($queue->priority_level ?? 5);
            $number = (int) ($queue->queue_number ?? 999999);
            return str_pad((string) $rank, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $priority, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
        })
        ->values();
    $doctorUserId = (int) ($currentUser->user_id ?? request()->query('user_id') ?? 0);
@endphp

@php
    $formatUserName = function ($user) {
        if (! $user) {
            return '';
        }
        $parts = array_filter([
            $user->firstname ?? null,
            $user->middlename ?? null,
            $user->lastname ?? null,
        ], function ($v) {
            return (string) $v !== '';
        });
        $name = trim(implode(' ', $parts));
        if ($name !== '') {
            return $name;
        }
        $email = trim((string) ($user->email ?? ''));
        return $email !== '' ? $email : 'Patient';
    };

    $servingEntry = collect($doctorTodayQueue ?? [])->first(fn($q) => strtolower((string) ($q->status ?? '')) === 'serving');
    $onHoldQueueForCards = collect($doctorTodayQueue ?? [])->filter(fn($q) => strtolower((string) ($q->status ?? '')) === 'on_hold')->values();
@endphp

<div class="flex flex-col flex-1 min-h-0 gap-4">
    {{-- ══════ Serving + On Hold display cards ══════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-shrink-0">
        {{-- Currently Serving --}}
        <div id="doctorQueueServingCard" class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden h-[11rem] flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50/60 to-white flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                            <x-lucide-user-check class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Currently Serving</h2>
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">Patient being attended</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50">
                @if ($servingEntry)
                    @php
                        $patientName = $formatUserName(optional(optional($servingEntry->appointment)->patient));
                        $timeKey = optional($servingEntry->queue_datetime)->format('H:i') ?? '-';
                    @endphp
                    <div class="flex flex-col items-center justify-center h-full px-5 py-6">
                        <span class="inline-flex items-center gap-1.5 text-2xl font-bold text-slate-800">
                            <x-lucide-hash class="w-5 h-5 text-slate-400" />
                            {{ $servingEntry->queue_code }}
                        </span>
                        <span class="mt-1 text-[0.9rem] text-slate-600 font-medium">{{ $patientName }}</span>
                        <span class="mt-1 text-xs text-slate-400">{{ $timeKey }}</span>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full px-4 text-center">
                        <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mb-2">
                            <x-lucide-user-check class="w-5 h-5 text-blue-300" />
                        </div>
                        <p class="text-[0.78rem] font-medium text-slate-500">No one being served</p>
                        <p class="text-[0.68rem] text-slate-400 mt-0.5">Call the next patient</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- On Hold (display only, no action buttons) --}}
        <div id="doctorQueueOnHoldCard" class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden h-[11rem] flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-purple-50/60 to-white flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
                            <x-lucide-pause class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800 tracking-tight">On Hold</h2>
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">Patients currently on hold</p>
                        </div>
                    </div>
                    @if (count($onHoldQueueForCards))
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-purple-50 border border-purple-100 text-purple-700 text-[0.7rem] font-semibold">
                            {{ count($onHoldQueueForCards) }} {{ Str::plural('patient', count($onHoldQueueForCards)) }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50">
                @if (count($onHoldQueueForCards))
                    <div class="divide-y divide-slate-100">
                        @foreach ($onHoldQueueForCards as $queue)
                            @php
                                $patientName = $formatUserName(optional(optional($queue->appointment)->patient));
                                $timeKey = optional($queue->queue_datetime)->format('H:i') ?? '-';
                            @endphp
                            <div class="px-5 py-3.5 hover:bg-slate-50/50 transition-all duration-150">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="inline-flex items-center gap-1.5 text-[0.75rem] font-semibold text-slate-700">
                                            <x-lucide-hash class="w-3 h-3 text-slate-400" />
                                            {{ $queue->queue_code }}
                                        </span>
                                        <span class="text-[0.72rem] text-slate-600 truncate">{{ $patientName }}</span>
                                    </div>
                                    <span class="text-[0.65rem] text-slate-400 flex-shrink-0">{{ $timeKey }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full px-4 text-center">
                        <div class="w-10 h-10 rounded-full bg-purple-50 border border-purple-100 flex items-center justify-center mb-2">
                            <x-lucide-pause class="w-5 h-5 text-purple-300" />
                        </div>
                        <p class="text-[0.78rem] font-medium text-slate-500">No patients on hold</p>
                        <p class="text-[0.68rem] text-slate-400 mt-0.5">On hold queue is empty</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-[18px] p-5 shadow-[0_2px_10px_rgba(15,23,42,0.04)] flex flex-col flex-1 min-h-0">
        <div class="mb-3 flex-shrink-0 flex flex-col gap-2 md:flex-row md:items-end">
            <div class="flex-1">
                <label for="doctor_queue_search" class="block text-[0.7rem] text-slate-600 mb-1">Search queue</label>
                <input id="doctor_queue_search" type="text" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Queue number or patient">
            </div>
            <div class="w-full md:w-40">
                <label for="doctor_queue_sort" class="block text-[0.7rem] text-slate-600 mb-1">Sort</label>
                <select id="doctor_queue_sort" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none">
                    <option value="queue">Queue order</option>
                    <option value="newest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                </select>
            </div>
            <div class="w-full md:w-auto">
                <label class="block text-[0.7rem] text-slate-600 mb-1">&nbsp;</label>
                <div class="flex gap-2">
                    <button id="doctorQueueCallNextButton" type="button" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-green-600 text-white text-[0.78rem] font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 disabled:hover:bg-green-600 min-w-[110px] relative">
                        <span id="doctorQueueCallNextSpinner" class="hidden absolute w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span>
                        <span id="doctorQueueCallNextContent" class="inline-flex items-center gap-1">
                            <x-lucide-megaphone class="w-[16px] h-[16px]" />
                            Call next
                        </span>
                    </button>
                    <button type="button" id="docQueueRefreshBtn" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-200 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700 hover:bg-orange-100">
                        <x-lucide-refresh-cw class="w-[14px] h-[14px]" />
                        Refresh
                    </button>
                </div>
            </div>
        </div>

    <div class="overflow-x-auto overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50 flex-1 min-h-0">
            <table class="min-w-full text-left text-xs text-slate-600">
                <thead>
                    <tr class="border-b border-slate-100 text-[0.68rem] uppercase tracking-widest text-slate-400">
                        <th class="py-2 pr-4 font-semibold">Queue #</th>
                        <th class="py-2 pr-4 font-semibold">Patient</th>
                        <th class="py-2 pr-4 font-semibold">Date</th>
                        <th class="py-2 pr-4 font-semibold">Time</th>
                        <th class="py-2 pr-4 font-semibold">Status</th>
                        <th class="py-2 pr-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="doctorQueueTbody">
                    @forelse ($doctorQueueItems as $queue)
                        @php
                            $patientName = optional(optional($queue->appointment)->patient)->personalInformation->full_name ?? '';
                            $statusName = strtolower((string) ($queue->status ?? ''));
                            $queueId = $queue->queue_id ?? null;
                            $dateKey = optional($queue->queue_datetime)->format('Y-m-d') ?? '';
                            $timeKey = optional($queue->queue_datetime)->format('H:i') ?? '';
                            $dateTimeKey = optional($queue->queue_datetime)->format('Y-m-d H:i:s') ?? '';
                            $statusDropdownColor = match($statusName) {
                                'waiting' => 'text-orange-700 border-orange-300 bg-orange-50',
                                'serving' => 'text-blue-700 border-blue-300 bg-blue-50',
                                'consulted' => 'text-blue-700 border-blue-300 bg-blue-50',
                                'skipped' => 'text-orange-700 border-orange-300 bg-orange-50',
                                'on_hold' => 'text-purple-700 border-purple-300 bg-purple-50',
                                default => 'text-slate-700 border-slate-200 bg-white',
                            };
                            $statusBadgeColor = match($statusName) {
                                'waiting' => 'border-orange-200 bg-orange-50 text-orange-700',
                                'serving' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'consulted' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'done' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-100',
                                'no_show' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'skipped' => 'bg-orange-50 text-orange-700 border-orange-100',
                                'on_hold' => 'bg-purple-50 text-purple-700 border-purple-100',
                                default => 'bg-slate-50 text-slate-700 border-slate-100',
                            };
                        @endphp
                        <tr class="border-b border-slate-50 last:border-0 doctor-queue-row"
                            data-queue-id="{{ $queueId }}"
                            data-queue-number="{{ $queue->queue_number }}"
                            data-queue-code="{{ $queue->queue_code }}"
                            data-patient="{{ strtolower($patientName) }}"
                            data-status="{{ $statusName }}"
                            data-datetime="{{ $dateTimeKey }}">
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">{{ $queue->queue_code ?? $queue->queue_number }}</td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-700">
                                @if ($patientName)
                                    {{ $patientName }}
                                @else
                                    <span class="text-slate-400">Patient</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">{{ $dateKey ?: '-' }}</td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">{{ $timeKey ?: '-' }}</td>
                            <td class="py-2 pr-4 text-[0.78rem] text-slate-500">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.68rem] font-medium border {{ $statusBadgeColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $statusName ?: 'unknown')) }}
                                </span>
                            </td>
                            <td class="py-2 pr-4 text-right">
                                @if ($queueId && ! in_array($statusName, ['done', 'cancelled', 'no_show'], true))
                                    <div class="inline-flex items-center gap-1.5">
                                        <div class="relative reception-status-dropdown-container">
                                            <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1.5 text-[0.7rem] hover:bg-slate-50 reception-status-dropdown-trigger {{ $statusDropdownColor }}">
                                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $statusName)) }}</span>
                                                <x-lucide-chevron-down class="w-[14px] h-[14px] text-slate-400" />
                                            </button>
                                            <div class="hidden absolute right-0 top-full mt-1 w-[140px] rounded-lg border border-slate-200 bg-white shadow-lg z-50 reception-status-dropdown-menu">
                                                <div class="py-1">
                                                    @if ($statusName !== 'serving')
                                                    <button type="button" class="w-full text-left px-3 py-1.5 text-[0.72rem] text-slate-700 hover:bg-slate-50 reception-queue-status flex items-center gap-2" data-queue-id="{{ $queueId }}" data-status="serving">
                                                        <x-lucide-play class="w-[14px] h-[14px] text-emerald-500" />
                                                        Serve
                                                    </button>
                                                    @endif
                                                    @if ($statusName !== 'skipped')
                                                    <button type="button" class="w-full text-left px-3 py-1.5 text-[0.72rem] text-slate-700 hover:bg-slate-50 reception-queue-status flex items-center gap-2" data-queue-id="{{ $queueId }}" data-status="skipped">
                                                        <x-lucide-skip-forward class="w-[14px] h-[14px] text-orange-500" />
                                                        Skipped
                                                    </button>
                                                    @endif
                                                    @if ($statusName !== 'on_hold')
                                                    <button type="button" class="w-full text-left px-3 py-1.5 text-[0.72rem] text-slate-700 hover:bg-slate-50 reception-queue-status flex items-center gap-2" data-queue-id="{{ $queueId }}" data-status="on_hold">
                                                        <x-lucide-pause class="w-[14px] h-[14px] text-purple-500" />
                                                        On hold
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if ($statusName === 'consulted')
                                            <span class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[0.68rem] font-medium text-slate-600">
                                                Waiting for payment
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[0.7rem] text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-[0.78rem] text-slate-400">
                                No queue entries assigned to you today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('doctor_queue_search')
        var sortSelect = document.getElementById('doctor_queue_sort')
        var rows = Array.prototype.slice.call(document.querySelectorAll('.doctor-queue-row'))
        var callNextButton = document.getElementById('doctorQueueCallNextButton')
        var callNextSpinner = document.getElementById('doctorQueueCallNextSpinner')
        var callNextContent = document.getElementById('doctorQueueCallNextContent')
        var doctorUserId = {{ $doctorUserId > 0 ? $doctorUserId : 'null' }}

        function showError(message) {
            if (message && typeof showToast === 'function') showToast(message, 'error')
        }

        function showSuccess(message) {
            if (message && typeof showToast === 'function') showToast(message, 'success')
        }

        function setCallNextSubmitting(isSubmitting) {
            if (callNextButton) callNextButton.disabled = !!isSubmitting
            if (callNextSpinner) callNextSpinner.classList.toggle('hidden', !isSubmitting)
            if (callNextContent) callNextContent.classList.toggle('opacity-0', !!isSubmitting)
        }

        function applyDoctorQueueFilters() {
            var query = searchInput ? String(searchInput.value || '').toLowerCase().trim() : ''

            rows.forEach(function (row) {
                var number = ((row.getAttribute('data-queue-code') || '') + ' ' + (row.getAttribute('data-queue-number') || '')).trim()
                var patient = row.getAttribute('data-patient') || ''
                var matches = true

                if (query) {
                    matches = ('#' + number).indexOf(query) !== -1 || patient.indexOf(query) !== -1
                }

                row.style.display = matches ? '' : 'none'
            })

            applyDoctorQueueSort()
        }

        function applyDoctorQueueSort() {
            if (!sortSelect || !rows.length) return
            var tbody = rows[0].parentNode
            var value = sortSelect.value
            var visibleRows = rows.filter(function (row) {
                return row.style.display !== 'none'
            })

            visibleRows.sort(function (a, b) {
                var qa = parseInt(a.getAttribute('data-queue-number') || '999999', 10)
                var qb = parseInt(b.getAttribute('data-queue-number') || '999999', 10)
                var da = a.getAttribute('data-datetime') || ''
                var db = b.getAttribute('data-datetime') || ''
                var sa = String(a.getAttribute('data-status') || '')
                var sb = String(b.getAttribute('data-status') || '')

                function statusRank(status) {
                    if (status === 'serving') return 0
                    if (status === 'waiting' || status === 'skipped') return 1
                    if (status === 'on_hold') return 2
                    if (status === 'consulted') return 3
                    if (status === 'done') return 4
                    if (status === 'cancelled') return 5
                    if (status === 'no_show') return 6
                    return 7
                }

                var ra = statusRank(sa)
                var rb = statusRank(sb)
                if (ra !== rb) return ra - rb

                if (value === 'newest') {
                    if (da < db) return 1
                    if (da > db) return -1
                    return qb - qa
                }

                if (value === 'oldest') {
                    if (da < db) return -1
                    if (da > db) return 1
                    return qa - qb
                }

                return qa - qb
            })

            visibleRows.forEach(function (row) {
                tbody.appendChild(row)
            })
        }

        // Guard that survives DOM re-execution (var gets reset on script re-run, but window persists)
        if (typeof window.__doctorUpdatingQueueStatus === 'undefined') {
            window.__doctorUpdatingQueueStatus = false
        }
        function updateQueueStatus(queueId, status, successMessage) {
            if (window.__doctorUpdatingQueueStatus) return
            if (!queueId || typeof apiFetch !== 'function') return

            window.__doctorUpdatingQueueStatus = true
            showError('')
            showSuccess('')

            apiFetch("{{ url('/api/queues') }}/" + encodeURIComponent(queueId), {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ status: status })
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok) {
                        showError(result.data && result.data.message ? result.data.message : 'Failed to update queue.')
                        return
                    }

                    showSuccess(successMessage || 'Queue updated.')
                })
                .catch(function () {
                    showError('Network error while updating queue.')
                })
                .finally(function () {
                    setTimeout(function () {
                        window.__doctorUpdatingQueueStatus = false
                    }, 2000)
                })
        }

        // Event delegation: status dropdown toggle + status option clicks
        if (window.__doctorQueueDocClick) {
            document.removeEventListener('click', window.__doctorQueueDocClick)
        }
        window.__doctorQueueDocClick = function (e) {
            var trigger = e.target && e.target.closest ? e.target.closest('.reception-status-dropdown-trigger') : null
            if (trigger) {
                var container = trigger.closest('.reception-status-dropdown-container')
                if (container) {
                    var menu = container.querySelector('.reception-status-dropdown-menu')
                    if (menu) {
                        document.querySelectorAll('.reception-status-dropdown-menu').forEach(function (m) {
                            if (m !== menu) m.classList.add('hidden')
                        })
                        menu.classList.toggle('hidden')
                    }
                }
                return
            }
            var statusBtn = e.target && e.target.closest ? e.target.closest('.reception-queue-status') : null
            if (statusBtn) {
                e.stopPropagation()
                var queueId = statusBtn.getAttribute('data-queue-id')
                var status = statusBtn.getAttribute('data-status')
                if (!queueId || !status) return
                var container = statusBtn.closest('.reception-status-dropdown-container')
                if (container) {
                    var menu = container.querySelector('.reception-status-dropdown-menu')
                    if (menu) menu.classList.add('hidden')
                }
                updateQueueStatus(queueId, status, 'Queue status updated to ' + String(status).replace(/_/g, ' ') + '.')
                return
            }
            document.querySelectorAll('.reception-status-dropdown-menu:not(.hidden)').forEach(function (menu) {
                if (!menu.contains(e.target) && !(e.target && e.target.closest && e.target.closest('.reception-status-dropdown-trigger'))) {
                    menu.classList.add('hidden')
                }
            })
        }
        document.addEventListener('click', window.__doctorQueueDocClick)

        if (searchInput) {
            searchInput.addEventListener('input', applyDoctorQueueFilters)
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', applyDoctorQueueSort)
        }
        function refreshTableFromServer(tableBodyEl) {
            if (!tableBodyEl) return
            tableBodyEl.innerHTML = '<tr><td colspan="999" class="py-4 text-center text-[0.78rem] text-slate-400">Loading…</td></tr>'
            var url = window.location.href
            fetch(url)
                .then(function (r) { return r.text() })
                .then(function (html) {
                    var parser = new DOMParser()
                    var doc = parser.parseFromString(html, 'text/html')
                    var freshBody = doc.getElementById(tableBodyEl.id)
                    if (freshBody) {
                        tableBodyEl.innerHTML = freshBody.innerHTML
                    }
                    // Also refresh serving and on-hold display cards
                    var servingCard = document.getElementById('doctorQueueServingCard')
                    var onHoldCard = document.getElementById('doctorQueueOnHoldCard')
                    if (servingCard) {
                        var freshServing = doc.getElementById('doctorQueueServingCard')
                        if (freshServing) servingCard.outerHTML = freshServing.outerHTML
                    }
                    if (onHoldCard) {
                        var freshOnHold = doc.getElementById('doctorQueueOnHoldCard')
                        if (freshOnHold) onHoldCard.outerHTML = freshOnHold.outerHTML
                    }
                    rows = Array.prototype.slice.call(document.querySelectorAll('.doctor-queue-row'))
                    applyDoctorQueueFilters()
                })
                .catch(function () {
                    tableBodyEl.innerHTML = '<tr><td colspan="999" class="py-4 text-center text-[0.78rem] text-slate-400 text-red-500">Refresh failed.</td></tr>'
                })
        }
        // Silent refresh for Reverb (no loading state)
        function silentRefreshTableFromServer(tableBodyEl) {
            if (!tableBodyEl) return
            var url = window.location.href
            fetch(url)
                .then(function (r) { return r.text() })
                .then(function (html) {
                    var parser = new DOMParser()
                    var doc = parser.parseFromString(html, 'text/html')
                    var freshBody = doc.getElementById(tableBodyEl.id)
                    if (freshBody) {
                        tableBodyEl.innerHTML = freshBody.innerHTML
                    }
                    var servingCard = document.getElementById('doctorQueueServingCard')
                    var onHoldCard = document.getElementById('doctorQueueOnHoldCard')
                    if (servingCard) {
                        var freshServing = doc.getElementById('doctorQueueServingCard')
                        if (freshServing) servingCard.outerHTML = freshServing.outerHTML
                    }
                    if (onHoldCard) {
                        var freshOnHold = doc.getElementById('doctorQueueOnHoldCard')
                        if (freshOnHold) onHoldCard.outerHTML = freshOnHold.outerHTML
                    }
                    rows = Array.prototype.slice.call(document.querySelectorAll('.doctor-queue-row'))
                    applyDoctorQueueFilters()
                })
        }
        if (document.getElementById('docQueueRefreshBtn')) document.getElementById('docQueueRefreshBtn').addEventListener('click', function () { refreshTableFromServer(document.getElementById('doctorQueueTbody')) })
        if (callNextButton) {
            callNextButton.addEventListener('click', function () {
                if (callNextButton.disabled || typeof apiFetch !== 'function') return

                showError('')
                showSuccess('')
                setCallNextSubmitting(true)

                var payload = {}
                if (doctorUserId) {
                    payload.doctor_id = doctorUserId
                }

                apiFetch("{{ url('/api/queues/call-next') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            return { ok: response.ok, data: data }
                        }).catch(function () {
                            return { ok: response.ok, data: null }
                        })
                    })
                    .then(function (result) {
                        if (!result.ok) {
                            showError(result.data && result.data.message ? result.data.message : 'Failed to call next patient.')
                            setCallNextSubmitting(false)
                            return
                        }

                        showSuccess('The next patient in your queue is now marked as serving.')
                        setCallNextSubmitting(false)
                    })
                    .catch(function () {
                        showError('Network error while calling next patient.')
                        setCallNextSubmitting(false)
                    })
            })
        }

        applyDoctorQueueFilters()

        if (typeof window.Echo !== 'undefined' && window.Echo && doctorUserId) {
            if (!window.__doctorQueueEchoListener) {
                try {
                    window.__doctorQueueEchoListener = window.Echo.private('queue.' + doctorUserId)
                        .listen('.queue.updated', function (data) {
                            var timeReceived = Date.now();
                            if (data && data.fired_at) {
                                var absoluteDelay = timeReceived - data.fired_at;
                                console.log('[DoctorQueue] Reverb fired: ' + absoluteDelay + 'ms');
                            }
                            silentRefreshTableFromServer(document.getElementById('doctorQueueTbody'))
                        })
                    console.log('[DoctorQueue] Echo listener attached to queue.' + doctorUserId)

                    // Also listen for appointment status changes (e.g. consulted) so the queue table updates
                    window.Echo.private('appointments.' + doctorUserId)
                        .listen('.appointment.updated', function () {
                            silentRefreshTableFromServer(document.getElementById('doctorQueueTbody'))
                        })
                    console.log('[DoctorQueue] Echo listener attached to appointments.' + doctorUserId)
                } catch (e) {
                    console.error('[DoctorQueue] Echo subscribe failed:', e)
                }
            } else {
                console.log('[DoctorQueue] Echo listener already attached, skipping.')
            }
        }
    })
</script>
