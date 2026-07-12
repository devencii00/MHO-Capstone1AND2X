<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prescription - Opol Primary Healthcare</title>
    @vite('resources/css/app.css')
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm;
        }
 
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .rx-shell {
                border: 0 !important;
                box-shadow: none !important;
            }
            .print-page-footer {
                display: block !important;
            }
        }
 
        * {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
 
        .print-page-footer {
            display: none;
            position: fixed;
            bottom: 6mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #777;
            z-index: 1000;
        }
 
        .letterhead-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }
 
        /* Classic pad texture: a faint rule every line under the Rx writing area */
        .rx-ruled-lines {
            background-image: repeating-linear-gradient(
                to bottom,
                transparent,
                transparent 34px,
                rgb(226 232 240) 34px,
                rgb(226 232 240) 35px
            );
        }
 
        .rx-mark {
            font-family: Georgia, 'Times New Roman', serif;
            font-style: italic;
            line-height: 1;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
 
    <div class="max-w-[1150px] mx-auto p-4 md:p-6">
        <div id="rxError" class="hidden mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[0.85rem] text-red-700"></div>
 
        <div class="rx-shell bg-white border border-slate-200 rounded-3xl overflow-hidden">
 
            {{-- ===== LETTERHEAD (full width) ===== --}}
            <div class="border-b-4 border-double border-slate-900 px-6 md:px-8 pt-6 pb-4 flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <img src="{{ asset('images/MHOLogoV2.png') }}" alt="Opol MHO logo"
                         class="letterhead-logo flex-shrink-0 mt-0.5">
                    <div class="min-w-0">
                        <div class="text-[0.78rem] font-semibold uppercase tracking-[0.35em] text-slate-500">
                            Opol Primary Healthcare
                        </div>
                        <h1 class="mt-1 text-2xl font-bold tracking-wide text-slate-900">PRESCRIPTION</h1>
                    </div>
                </div>
                <div class="text-right shrink-0 pt-1">
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400">Reference</div>
                    <div id="rxMeta" class="text-[0.85rem] font-medium text-slate-600 mt-1">Loading…</div>
                </div>
            </div>
 
            {{-- ===== PATIENT & DOCTOR STRIP ===== --}}
            <div class="grid grid-cols-2 md:grid-cols-3 divide-x divide-slate-100 border-b border-slate-100 px-6 md:px-8">
                <div class="py-3 pr-4">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient</div>
                    <div id="rxPatientName" class="text-sm font-semibold text-slate-900 mt-1">-</div>
                </div>
                <div class="py-3 px-4">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Doctor</div>
                    <div id="rxDoctorName" class="text-sm font-semibold text-slate-900 mt-1">-</div>
                </div>
                <div class="py-3 pl-4">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Doctor Info</div>
                    <div id="rxDoctorInfo" class="text-[0.78rem] text-slate-600 mt-1">-</div>
                </div>
            </div>
 
            {{-- ===== RX WRITING AREA ===== --}}
            <div class="flex px-6 md:px-8 py-6 gap-6">
 
                {{-- Rx mark, anchoring the left margin like a real pad --}}
                <div class="shrink-0 pt-1">
                    <div class="rx-mark text-5xl text-slate-900">℞</div>
                </div>
 
                {{-- Medicines --}}
                <div class="flex-1 min-w-0 rx-ruled-lines">
                    <table class="w-full text-left text-[0.82rem] text-slate-700 border-collapse">
                        <thead>
                            <tr class="text-[0.68rem] uppercase tracking-widest text-slate-400">
                                <th class="pb-2 pr-4 font-semibold">Medicine</th>
                                <th class="pb-2 pr-4 font-semibold">Dosage</th>
                                <th class="pb-2 pr-4 font-semibold">Frequency</th>
                                <th class="pb-2 pr-4 font-semibold">Duration</th>
                                <th class="pb-2 pr-0 font-semibold">Instructions</th>
                            </tr>
                        </thead>
                        <tbody id="rxItemsBody"></tbody>
                    </table>
                </div>
            </div>
 
            {{-- ===== FOOTER: SIGNATURE ===== --}}
            <div class="border-t border-slate-100 px-6 md:px-8 py-5 flex items-end justify-between gap-6">
                <div class="text-[0.72rem] text-slate-400">
                    This prescription is valid only when signed by the attending Doctor.
                </div>
 
                <div class="text-right shrink-0">
                    <div id="rxSignatureBox" class="h-16 w-56 flex items-center justify-center text-[0.78rem] text-slate-400 border-b border-slate-300">
                        No signature
                    </div>
                    <div id="rxSignatureName" class="mt-2 text-[0.85rem] font-semibold text-slate-900">-</div>
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400 mt-0.5">Doctor Signature</div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="print-page-footer">Opol Primary Healthcare</div>
    
    <script>
        (function () {
            var prescriptionId = {{ (int) $prescriptionId }};
            var errorBox = document.getElementById('rxError');

            var rxMeta = document.getElementById('rxMeta');
            var patientName = document.getElementById('rxPatientName');
            var doctorName = document.getElementById('rxDoctorName');
            var doctorInfo = document.getElementById('rxDoctorInfo');
            var itemsBody = document.getElementById('rxItemsBody');
            var sigBox = document.getElementById('rxSignatureBox');
            var sigName = document.getElementById('rxSignatureName');

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

            function apiFetch(path, options) {
                if (window.apiFetch) return window.apiFetch(path, options || {});
                var token = null;
                try { token = window.localStorage ? window.localStorage.getItem('api_token') : null; } catch (_) { token = null; }
                var headers = (options && options.headers) ? Object.assign({}, options.headers) : {};
                if (token) headers['Authorization'] = 'Bearer ' + token;
                if (!headers['Accept']) headers['Accept'] = 'application/json';
                return fetch(path, Object.assign({}, options, { headers: headers }));
            }

            function nameForUser(u, fallback) {
                if (!u) return fallback || '-';
                var parts = [u.firstname, u.middlename, u.lastname].filter(function (v) { return String(v || '').trim() !== ''; });
                var name = parts.join(' ').trim();
                return name || fallback || ('User #' + (u.user_id || ''));
            }

            function medicineName(item) {
                var med = item && item.medicine ? item.medicine : null;
                if (med) {
                    var generic = med.generic_name || '';
                    var brand = med.brand_name || '';
                    if (generic && brand) return generic + ' (' + brand + ')';
                    return generic || brand || ('Medicine #' + (med.medicine_id || ''));
                }
                return 'Medicine #' + (item && item.medicine_id ? item.medicine_id : '');
            }

            function renderSignature(doctorUser) {
                if (!sigBox || !sigName) return;
                var docName = nameForUser(doctorUser, 'Doctor');
                sigName.textContent = docName;

                var signatureUrl = doctorUser && doctorUser.signature_url ? String(doctorUser.signature_url) : '';
                if (!signatureUrl) {
                    sigBox.textContent = 'No signature';
                    return;
                }

                sigBox.innerHTML = '<img alt="Signature" src="' + escapeHtml(signatureUrl) + '" class="max-h-16 max-w-full object-contain">';
            }

            function load() {
                showError('');
                apiFetch("{{ url('/api/prescriptions') }}/" + encodeURIComponent(String(prescriptionId)), { method: 'GET' })
                    .then(function (res) {
                        return res.text().then(function (txt) {
                            var data = null;
                            try { data = txt ? JSON.parse(txt) : null; } catch (_) { data = null; }
                            return { ok: res.ok, status: res.status, data: data };
                        });
                    })
                    .then(function (result) {
                        if (!result.ok || !result.data) {
                            showError('Unable to load prescription. Please ensure you are logged in.');
                            return;
                        }

                        var rx = result.data;
                        var tx = rx.transaction || null;
                        var appt = tx && tx.appointment ? tx.appointment : null;
                        var patient = appt && appt.patient ? appt.patient : null;
                        var doctor = rx.doctor || null;
                        var items = rx.items || [];

                        var dt = rx.prescribed_datetime ? String(rx.prescribed_datetime).replace('T', ' ').slice(0, 16) : '';
                        if (rxMeta) rxMeta.textContent = dt ? ('Prescribed: ' + dt) : '-';

                        if (patientName) patientName.textContent = nameForUser(patient, 'Patient');

                        if (doctorName) doctorName.textContent = nameForUser(doctor, 'Doctor');
                        if (doctorInfo) {
                            var dmeta = [];
                            if (doctor && doctor.specialization) dmeta.push(doctor.specialization);
                            if (doctor && doctor.prc_license) dmeta.push('Lic: ' + doctor.prc_license);
                            doctorInfo.textContent = dmeta.length ? dmeta.join(' • ') : '-';
                        }

                        if (itemsBody) {
                            if (!items.length) {
                                itemsBody.innerHTML = '<tr><td colspan="5" class="py-3 text-slate-500 text-[0.85rem]">No medicines listed.</td></tr>';
                            } else {
                                itemsBody.innerHTML = items.map(function (it) {
                                    return '' +
                                        '<tr class="border-b border-slate-100 last:border-0">' +
                                            '<td class="py-2 pr-4 font-semibold text-slate-900">' + escapeHtml(medicineName(it)) + '</td>' +
                                            '<td class="py-2 pr-4">' + escapeHtml(it.dosage || '-') + '</td>' +
                                            '<td class="py-2 pr-4">' + escapeHtml(it.frequency || '-') + '</td>' +
                                            '<td class="py-2 pr-4">' + escapeHtml(it.duration || '-') + '</td>' +
                                            '<td class="py-2 pr-0">' + escapeHtml(it.instructions || '-') + '</td>' +
                                        '</tr>';
                                }).join('');
                            }
                        }

                        renderSignature(doctor);
                    })
                    .catch(function () {
                        showError('Network error while loading prescription.');
                    });
            }

            load();
        })();
    </script>
</body>
</html>
