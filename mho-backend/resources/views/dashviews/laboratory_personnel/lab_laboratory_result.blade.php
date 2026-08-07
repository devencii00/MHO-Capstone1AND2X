<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ url('/dashboard/laboratory_personnel?role=laboratory_personnel&section=results') }}"
               data-spa-nav="1"
               class="w-8 h-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 mb-1">Laboratory Result</h1>
                <p class="text-sm text-slate-500">Encode the laboratory examination result for this patient.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="labLabResPrintBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-printer class="w-3.5 h-3.5" />
                Print
            </button>
            <button type="button" id="labLabResSaveBtn" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.72rem] font-semibold hover:bg-green-700 transition-colors shadow-sm">
                <x-lucide-save class="w-3.5 h-3.5" />
                Save Result
            </button>
        </div>
    </div>

    {{-- ── Exam type selector (no-print) ── --}}
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-[0.75rem] font-semibold text-slate-600">Examination:</span>
        <div class="flex flex-wrap gap-1.5">
            <button type="button" class="labLabResTypeBtn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-green-600 text-white text-[0.72rem] font-semibold transition-colors" data-type="fecalysis">
                Fecalysis
            </button>
            <button type="button" class="labLabResTypeBtn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-300 transition-colors" data-type="urinalysis">
                Urinalysis
            </button>
            <button type="button" class="labLabResTypeBtn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-300 transition-colors" data-type="cbc">
                CBC
            </button>
            <button type="button" class="labLabResTypeBtn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-300 transition-colors" data-type="other">
                Other
            </button>
        </div>
    </div>

    {{-- ── Result form card ── --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
        {{-- Clinic header --}}
        <div class="px-6 py-5 border-b border-slate-100 text-center bg-gradient-to-r from-emerald-50/50 via-white to-emerald-50/50">
            <p class="text-[0.68rem] text-slate-500">Republic of the Philippines</p>
            <p class="text-[0.68rem] text-slate-500">Province of Misamis Oriental</p>
            <p class="text-[0.68rem] text-slate-500">City/Municipality of Opol</p>
            <h2 class="text-base font-bold text-slate-900 tracking-wide mt-1">OPOL MUNICIPAL HEALTH CENTER</h2>
            <p class="text-[0.7rem] text-slate-500 mt-0.5">Opol, Misamis Oriental</p>
            <div class="inline-flex items-center gap-2 mt-2 px-3 py-1 rounded-full bg-white border border-emerald-100 text-emerald-700 text-[0.7rem] font-semibold">
                <x-lucide-flask-conical class="w-3.5 h-3.5" />
                LABORATORY RESULT FORM
            </div>
        </div>

        {{-- Patient info --}}
        <div class="px-6 py-4 border-b border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Patient</p>
                <p class="text-sm font-bold text-slate-800" id="labLabResPatient">—</p>
            </div>
            <div>
                <p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Age / Sex</p>
                <p class="text-sm font-bold text-slate-800" id="labLabResAgeSex">—</p>
            </div>
            <div>
                <p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Date</p>
                <p class="text-sm font-bold text-slate-800" id="labLabResDate">—</p>
            </div>
            <div>
                <p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Address</p>
                <p class="text-sm font-bold text-slate-800" id="labLabResAddress">—</p>
            </div>
        </div>

        <div class="p-6 space-y-6">
            {{-- Fecalysis --}}
            <section data-exam-section="fecalysis">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600"><x-lucide-flask-conical class="w-3.5 h-3.5" /></div>
                    <h3 class="text-sm font-semibold text-slate-800">FECALYSIS</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Color</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Consistency</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Occult Blood</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">RBC</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">WBC</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Ova or Parasite</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block sm:col-span-3"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Others</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                </div>
            </section>

            {{-- Urinalysis --}}
            <section data-exam-section="urinalysis" class="hidden">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600"><x-lucide-flask-conical class="w-3.5 h-3.5" /></div>
                    <h3 class="text-sm font-semibold text-slate-800">URINALYSIS</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Color</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Transparency</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Specific Gravity</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">pH</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Albumin</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Sugar</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                </div>
                <p class="text-[0.7rem] font-semibold text-slate-500 uppercase tracking-wide mt-5 mb-2">Microscopic</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Pus Cells</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">RBC</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Epithelial Cells</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Amorphous</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Bacteria</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Mucous Threads</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                </div>
            </section>

            {{-- CBC --}}
            <section data-exam-section="cbc" class="hidden">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600"><x-lucide-flask-conical class="w-3.5 h-3.5" /></div>
                    <h3 class="text-sm font-semibold text-slate-800">COMPLETE BLOOD COUNT (CBC)</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Hemoglobin</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Hematocrit</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">RBC Count</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">WBC Count</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Platelet Count</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                </div>
                <p class="text-[0.7rem] font-semibold text-slate-500 uppercase tracking-wide mt-5 mb-2">Differential Count</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Neutrophils</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Lymphocytes</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Monocytes</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Eosinophils</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                    <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Basophils</span>
                        <input type="text" class="labLabResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="—"></label>
                </div>
            </section>

            {{-- Other --}}
            <section data-exam-section="other" class="hidden">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600"><x-lucide-flask-conical class="w-3.5 h-3.5" /></div>
                    <h3 class="text-sm font-semibold text-slate-800">LABORATORY RESULT</h3>
                </div>
                <textarea rows="6" class="labLabResInput w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Enter the laboratory findings here..."></textarea>
            </section>

            {{-- Remarks & signature --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                <div>
                    <label class="block"><span class="text-[0.7rem] font-semibold text-slate-500 uppercase tracking-wide">Remarks / Impression</span>
                        <textarea rows="3" id="labLabResRemarks" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional remarks..."></textarea></label>
                </div>
                <div class="flex flex-col justify-end items-start">
                    <p class="text-[0.7rem] font-semibold text-slate-500 uppercase tracking-wide mb-1">Medical Technologist</p>
                    <p class="text-[0.8rem] text-slate-700 font-medium">_________________________</p>
                    <p class="text-[0.65rem] text-slate-400 mt-0.5">Signature over printed name</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var typeBtns = document.querySelectorAll('.labLabResTypeBtn')
    var sections = document.querySelectorAll('[data-exam-section]')
    var printBtn = document.getElementById('labLabResPrintBtn')
    var saveBtn = document.getElementById('labLabResSaveBtn')

    function showType(type) {
        sections.forEach(function (s) {
            s.classList.toggle('hidden', s.getAttribute('data-exam-section') !== type)
        })
        typeBtns.forEach(function (b) {
            var active = b.getAttribute('data-type') === type
            if (active) {
                b.className = 'labLabResTypeBtn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-green-600 text-white text-[0.72rem] font-semibold transition-colors'
            } else {
                b.className = 'labLabResTypeBtn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-300 transition-colors'
            }
        })
    }

    typeBtns.forEach(function (b) {
        b.addEventListener('click', function () { showType(this.getAttribute('data-type')) })
    })

    if (printBtn) printBtn.addEventListener('click', function () { window.print() })
    if (saveBtn) saveBtn.addEventListener('click', function () {
        if (typeof window.showToast === 'function') {
            window.showToast('Front-end preview — result saving is enabled once the laboratory backend is connected.', 'info')
        }
    })

    // Populate patient placeholders from query params if present
    var qs = window.location.search
    var getParam = function (k) {
        var m = qs.match(new RegExp('[?&]' + k + '=([^&]+)'))
        return m ? decodeURIComponent(m[1]) : null
    }
    var set = function (id, v) { var el = document.getElementById(id); if (el && v) el.textContent = v }
    set('labLabResPatient', getParam('patient'))
    set('labLabResAgeSex', getParam('agesex'))
    set('labLabResDate', getParam('date'))
    set('labLabResAddress', getParam('address'))
})();
</script>
