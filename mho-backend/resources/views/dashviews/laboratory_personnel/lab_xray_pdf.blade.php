<div class="space-y-6">
    <div class="flex items-center justify-between gap-3 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ url('/dashboard/laboratory_personnel?role=laboratory_personnel&section=results') }}"
               data-spa-nav="1"
               class="w-8 h-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 mb-1">X-Ray Report</h1>
                <p class="text-sm text-slate-500">Print-ready x-ray result.</p>
            </div>
        </div>
        <button type="button" id="labXrPdfPrintBtn" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.72rem] font-semibold hover:bg-green-700 transition-colors shadow-sm">
            <x-lucide-printer class="w-3.5 h-3.5" />
            Print / Save PDF
        </button>
    </div>

    {{-- ── Print sheet ── --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden print:shadow-none print:border-0 print:rounded-none">
        <div class="px-8 py-6 text-center border-b border-slate-200">
            <p class="text-[0.7rem] text-slate-600">Republic of the Philippines</p>
            <p class="text-[0.7rem] text-slate-600">Province of Misamis Oriental</p>
            <p class="text-[0.7rem] text-slate-600">City/Municipality of Opol</p>
            <h2 class="text-lg font-bold text-slate-900 tracking-wide mt-1">OPOL MUNICIPAL HEALTH CENTER</h2>
            <p class="text-[0.72rem] text-slate-500 mt-0.5">Opol, Misamis Oriental</p>
            <div class="inline-flex items-center gap-2 mt-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[0.72rem] font-semibold">
                <x-lucide-scan-line class="w-3.5 h-3.5" />
                X-RAY REPORT
            </div>
        </div>

        <div class="px-8 py-5 grid grid-cols-2 sm:grid-cols-4 gap-4 border-b border-slate-200">
            <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Patient ID</p><p class="text-sm font-bold text-slate-800" id="labXrPdfPatientId">—</p></div>
            <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Patient</p><p class="text-sm font-bold text-slate-800" id="labXrPdfPatient">—</p></div>
            <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Age / Sex</p><p class="text-sm font-bold text-slate-800" id="labXrPdfAgeSex">—</p></div>
            <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Exam Date</p><p class="text-sm font-bold text-slate-800" id="labXrPdfExamDate">—</p></div>
        </div>

        <div class="px-8 py-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-500 uppercase tracking-widest mb-3">X-Ray Image</p>
                <div class="rounded-xl border border-slate-200 bg-slate-50 aspect-[4/3] flex items-center justify-center overflow-hidden">
                    <img id="labXrPdfImage" src="" alt="X-Ray" class="w-full h-full object-contain hidden">
                    <div class="flex flex-col items-center gap-2 text-slate-300">
                        <x-lucide-scan-line class="w-10 h-10" />
                        <span class="text-[0.7rem] text-slate-400">No image attached</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-500 uppercase tracking-widest mb-3">Radiology Findings</p>
                <div class="space-y-3 text-[0.8rem] text-slate-700">
                    <div><span class="font-semibold text-slate-500">Chest / Lung Fields:</span> <span id="labXrPdfLungs">—</span></div>
                    <div><span class="font-semibold text-slate-500">Heart / Mediastinum:</span> <span id="labXrPdfHeart">—</span></div>
                    <div><span class="font-semibold text-slate-500">Bones &amp; Soft Tissues:</span> <span id="labXrPdfBones">—</span></div>
                    <div class="pt-3 border-t border-slate-100"><span class="font-semibold text-slate-500">Impression:</span> <span id="labXrPdfImpression">—</span></div>
                    <div><span class="font-semibold text-slate-500">Remarks:</span> <span id="labXrPdfRemarks">—</span></div>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 border-t border-slate-200 flex flex-col items-end">
            <p class="text-[0.8rem] text-slate-700 font-medium">_________________________</p>
            <p class="text-[0.68rem] text-slate-500 mt-0.5">Radiologist — Signature over printed name</p>
        </div>
    </div>
</div>

<script>
(function () {
    var printBtn = document.getElementById('labXrPdfPrintBtn')
    if (printBtn) printBtn.addEventListener('click', function () { window.print() })

    var qs = window.location.search
    var getParam = function (k) {
        var m = qs.match(new RegExp('[?&]' + k + '=([^&]+)'))
        return m ? decodeURIComponent(m[1]) : null
    }
    var set = function (id, v) { var el = document.getElementById(id); if (el && v) el.textContent = v }
    set('labXrPdfPatientId', getParam('patient_id'))
    set('labXrPdfPatient', getParam('patient'))
    set('labXrPdfAgeSex', getParam('agesex'))
    set('labXrPdfExamDate', getParam('date'))
    set('labXrPdfLungs', getParam('lungs'))
    set('labXrPdfHeart', getParam('heart'))
    set('labXrPdfBones', getParam('bones'))
    set('labXrPdfImpression', getParam('impression'))
    set('labXrPdfRemarks', getParam('remarks'))
    var imgEl = document.getElementById('labXrPdfImage')
    var imgSrc = getParam('image')
    if (imgEl && imgSrc) {
        imgEl.src = imgSrc
        imgEl.classList.remove('hidden')
    }
})();
</script>
