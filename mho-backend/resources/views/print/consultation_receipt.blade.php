<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consultation Receipt - Opol Primary Healthcare</title>
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
        }

        * {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .letterhead-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .rx-ruled-lines {
            background-image: repeating-linear-gradient(
                to bottom,
                transparent,
                transparent 34px,
                rgb(226 232 240) 34px,
                rgb(226 232 240) 35px
            );
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

    <div class="max-w-[1150px] mx-auto p-4 md:p-6">
        <div id="consultationPrintError" class="hidden mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[0.85rem] text-red-700"></div>

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
                        <h1 class="mt-1 text-2xl font-bold tracking-wide text-slate-900">CONSULTATION SUMMARY</h1>
                        <div id="consultationMeta" class="text-[0.78rem] text-slate-500 mt-1">Loading…</div>
                    </div>
                </div>
                
            </div>

            {{-- ===== PATIENT & DOCTOR STRIP ===== --}}
            <div class="grid grid-cols-2 md:grid-cols-2 divide-x divide-slate-100 border-b border-slate-100 px-6 md:px-8">
                <div class="py-3 pr-4">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Patient</div>
                    <div id="consultationPatientName" class="text-sm font-semibold text-slate-900 mt-1">-</div>
                </div>
                <div class="py-3 pl-4">
                    <div class="text-[0.68rem] uppercase tracking-widest text-slate-400">Doctor</div>
                    <div id="consultationDoctorName" class="text-sm font-semibold text-slate-900 mt-1">-</div>
                </div>
            </div>

            {{-- ===== DIAGNOSIS & TREATMENT (side by side, landscape width) ===== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100 border-b border-slate-100">
                <div class="px-6 md:px-8 py-5">
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400 mb-2">Diagnosis</div>
                    <div id="consultationDiagnosis" class="text-[0.85rem] text-slate-700 whitespace-pre-line leading-relaxed">-</div>
                </div>
                <div class="px-6 md:px-8 py-5">
                    <div class="text-[0.7rem] uppercase tracking-widest text-slate-400 mb-2">Treatment Notes</div>
                    <div id="consultationTreatment" class="text-[0.85rem] text-slate-700 whitespace-pre-line leading-relaxed">-</div>
                </div>
            </div>

            {{-- ===== PRESCRIPTION ITEMS ===== --}}
            <div class="px-6 md:px-8 py-6">
                <div class="text-[0.7rem] uppercase tracking-widest text-slate-400 mb-3">Prescription Items</div>
                <div class="rx-ruled-lines">
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
                        <tbody id="consultationItemsBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

    <script>
        (function () {
            var transactionId = {{ (int) $transactionId }};
            var errorBox = document.getElementById('consultationPrintError');
            var printBtn = document.getElementById('consultationPrintBtn');
            var metaEl = document.getElementById('consultationMeta');
            var patientNameEl = document.getElementById('consultationPatientName');
            var patientInfoEl = document.getElementById('consultationPatientInfo');
            var doctorNameEl = document.getElementById('consultationDoctorName');
            var doctorInfoEl = document.getElementById('consultationDoctorInfo');
            var diagnosisEl = document.getElementById('consultationDiagnosis');
            var treatmentEl = document.getElementById('consultationTreatment');
            var itemsBody = document.getElementById('consultationItemsBody');

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
                return item && item.medicine_name ? item.medicine_name : ('Medicine #' + (item && item.medicine_id ? item.medicine_id : ''));
            }

            function render() {
                showError('');
                apiFetch("{{ url('/api/transactions') }}/" + encodeURIComponent(String(transactionId)), { method: 'GET' })
                    .then(function (res) {
                        return res.text().then(function (txt) {
                            var data = null;
                            try { data = txt ? JSON.parse(txt) : null; } catch (_) { data = null; }
                            return { ok: res.ok, data: data };
                        });
                    })
                    .then(function (result) {
                        if (!result.ok || !result.data) {
                            showError('Unable to load consultation receipt. Please ensure you are logged in.');
                            return;
                        }

                        var tx = result.data;
                        var appt = tx.appointment || null;
                        var patient = appt && appt.patient ? appt.patient : null;
                        var doctor = appt && appt.doctor ? appt.doctor : null;
                        var prescriptions = tx.prescriptions || [];
                        var allItems = [];

                        prescriptions.forEach(function (rx) {
                            (rx.items || []).forEach(function (item) {
                                allItems.push(item);
                            });
                            if (!doctor && rx.doctor) doctor = rx.doctor;
                        });

                        var dt = tx.visit_datetime || tx.transaction_datetime || '';
                        if (metaEl) metaEl.textContent = dt ? ('Visit: ' + String(dt).replace('T', ' ').slice(0, 16)) : '-';

                        if (patientNameEl) patientNameEl.textContent = nameForUser(patient, 'Patient');
                        if (patientInfoEl) {
                            var patientMeta = [];
                            if (patient && patient.sex) patientMeta.push(patient.sex);
                            if (patient && patient.birthdate) patientMeta.push(String(patient.birthdate).slice(0, 10));
                            if (appt && appt.appointment_id) patientMeta.push('Appointment #' + appt.appointment_id);
                            patientInfoEl.textContent = patientMeta.length ? patientMeta.join(' • ') : '-';
                        }

                        if (doctorNameEl) doctorNameEl.textContent = nameForUser(doctor, 'Doctor');
                        if (doctorInfoEl) {
                            var doctorMeta = [];
                            if (doctor && doctor.specialization) doctorMeta.push(doctor.specialization);
                            if (doctor && doctor.prc_license) doctorMeta.push('Lic: ' + doctor.prc_license);
                            doctorInfoEl.textContent = doctorMeta.length ? doctorMeta.join(' • ') : '-';
                        }

                        if (diagnosisEl) diagnosisEl.textContent = tx.diagnosis ? String(tx.diagnosis) : 'No diagnosis recorded.';
                        if (treatmentEl) treatmentEl.textContent = tx.treatment_notes ? String(tx.treatment_notes) : 'No treatment notes recorded.';

                        if (itemsBody) {
                            if (!allItems.length) {
                                itemsBody.innerHTML = '<tr><td colspan="5" class="py-3 text-slate-500 text-[0.85rem]">No prescription items recorded.</td></tr>';
                            } else {
                                itemsBody.innerHTML = allItems.map(function (item) {
                                    return '' +
                                        '<tr class="border-b border-slate-100 last:border-0">' +
                                            '<td class="py-2 pr-4 font-semibold text-slate-900">' + escapeHtml(medicineName(item)) + '</td>' +
                                            '<td class="py-2 pr-4">' + escapeHtml(item.dosage || '-') + '</td>' +
                                            '<td class="py-2 pr-4">' + escapeHtml(item.frequency || '-') + '</td>' +
                                            '<td class="py-2 pr-4">' + escapeHtml(item.duration || '-') + '</td>' +
                                            '<td class="py-2 pr-0">' + escapeHtml(item.instructions || '-') + '</td>' +
                                        '</tr>';
                                }).join('');
                            }
                        }
                    });
            }

            if (printBtn) {
                printBtn.addEventListener('click', function () {
                    window.print();
                });
            }

            render();
        })();
    </script>
</body>
</html>
