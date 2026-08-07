<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ url('/dashboard/laboratory?role=laboratory&section=results') }}"
               data-spa-nav="1"
               class="w-8 h-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-arrow-left class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 mb-1">X-Ray Result</h1>
                <p class="text-sm text-slate-500">Upload x-ray images and encode the radiology report.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="labXrResPrintBtn" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 text-[0.72rem] font-semibold hover:border-green-400 hover:text-green-600 transition-colors">
                <x-lucide-printer class="w-3.5 h-3.5" />
                Print
            </button>
            <button type="button" id="labXrResSaveBtn" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-green-600 text-white text-[0.72rem] font-semibold hover:bg-green-700 transition-colors shadow-sm">
                <x-lucide-save class="w-3.5 h-3.5" />
                Save Result
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- ── Left: patient info + image panel ── --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-100/60 to-white flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600">
                        <x-lucide-user class="w-4 h-4" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Patient Information</h2>
                        <p class="text-[0.7rem] text-slate-500 mt-0.5">Details for this x-ray exam</p>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Patient ID</p><p class="text-sm font-bold text-slate-800" id="labXrResPatientId">—</p></div>
                    <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Date of Birth</p><p class="text-sm font-bold text-slate-800" id="labXrResDob">—</p></div>
                    <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Gender</p><p class="text-sm font-bold text-slate-800 capitalize" id="labXrResGender">—</p></div>
                    <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Exam Date</p><p class="text-sm font-bold text-slate-800" id="labXrResExamDate">—</p></div>
                    <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Address</p><p class="text-sm font-bold text-slate-800" id="labXrResAddress">—</p></div>
                    <div><p class="text-[0.62rem] font-semibold uppercase tracking-widest text-slate-400 mb-1">Physician</p><p class="text-sm font-bold text-slate-800" id="labXrResPhysician">—</p></div>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600">
                            <x-lucide-scan-line class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-800 tracking-tight">X-Ray Images</h2>
                            <p class="text-[0.7rem] text-slate-500 mt-0.5">Click an image to preview</p>
                        </div>
                    </div>
                    <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-green-600 text-white text-[0.72rem] font-semibold hover:bg-green-700 transition-colors cursor-pointer">
                        <x-lucide-upload class="w-3.5 h-3.5" />
                        Upload Image
                        <input type="file" id="labXrResFileInput" accept="image/*" multiple class="hidden">
                    </label>
                </div>
                <div class="p-5">
                    <div id="labXrResImageGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        <div class="col-span-full py-10 text-center border-2 border-dashed border-slate-200 rounded-2xl">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                    <x-lucide-scan-line class="w-7 h-7" />
                                </div>
                                <p class="text-slate-400 text-[0.8rem] font-medium">No x-ray images uploaded</p>
                                <p class="text-slate-400 text-[0.7rem]">Uploaded images will appear here once the backend is connected.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Right: radiology report ── --}}
        <div class="bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden flex flex-col h-full">
            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50/60 to-white flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                    <x-lucide-clipboard-list class="w-4 h-4" />
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-slate-800 tracking-tight">Radiology Report</h2>
                    <p class="text-[0.7rem] text-slate-500 mt-0.5">X-ray findings</p>
                </div>
            </div>
            <div class="p-5 space-y-4 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-slate-50">
                <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Chest — Lung Fields</span>
                    <textarea rows="2" class="labXrResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Findings..."></textarea></label>
                <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Heart / Mediastinum</span>
                    <textarea rows="2" class="labXrResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Findings..."></textarea></label>
                <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Bones &amp; Soft Tissues</span>
                    <textarea rows="2" class="labXrResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Findings..."></textarea></label>
                <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Impression</span>
                    <textarea rows="3" class="labXrResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Overall impression..."></textarea></label>
                <label class="block"><span class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide">Remarks</span>
                    <textarea rows="2" class="labXrResInput mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-[0.78rem] focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Optional remarks..."></textarea></label>
                <div>
                    <p class="text-[0.68rem] font-semibold text-slate-500 uppercase tracking-wide mb-1">Radiologist</p>
                    <p class="text-[0.8rem] text-slate-700 font-medium">_________________________</p>
                    <p class="text-[0.62rem] text-slate-400 mt-0.5">Signature over printed name</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Lightbox ── --}}
<div id="labXrResLightbox" class="hidden fixed inset-0 z-[90] bg-black/85 items-center justify-center p-6">
    <button type="button" id="labXrResLightboxClose" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20">
        <x-lucide-x class="w-5 h-5" />
    </button>
    <img id="labXrResLightboxImg" src="" alt="X-Ray preview" class="max-w-full max-h-full rounded-xl object-contain shadow-2xl">
</div>

<script>
(function () {
    var fileInput = document.getElementById('labXrResFileInput')
    var grid = document.getElementById('labXrResImageGrid')
    var lightbox = document.getElementById('labXrResLightbox')
    var lightboxImg = document.getElementById('labXrResLightboxImg')
    var lightboxClose = document.getElementById('labXrResLightboxClose')
    var printBtn = document.getElementById('labXrResPrintBtn')
    var saveBtn = document.getElementById('labXrResSaveBtn')

    var files = []

    function renderGrid() {
        if (!grid) return
        if (!files.length) {
            grid.innerHTML = '<div class="col-span-full py-10 text-center border-2 border-dashed border-slate-200 rounded-2xl">' +
                '<div class="flex flex-col items-center gap-2">' +
                '<div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-300"><svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg></div>' +
                '<p class="text-slate-400 text-[0.8rem] font-medium">No x-ray images uploaded</p>' +
                '<p class="text-slate-400 text-[0.7rem]">Uploaded images will appear here once the backend is connected.</p></div></div>'
            return
        }
        var html = ''
        files.forEach(function (f, i) {
            html += '<div class="group relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50 aspect-square cursor-pointer" data-img-index="' + i + '">' +
                '<img src="' + f.url + '" alt="X-Ray ' + (i + 1) + '" class="w-full h-full object-cover">' +
                '<button type="button" class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" data-remove-index="' + i + '">' +
                '<svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button></div>'
        })
        grid.innerHTML = html

        grid.querySelectorAll('[data-img-index]').forEach(function (el) {
            el.addEventListener('click', function () {
                var img = files[Number(this.getAttribute('data-img-index'))]
                if (img && lightbox && lightboxImg) {
                    lightboxImg.src = img.url
                    lightbox.classList.remove('hidden')
                    lightbox.classList.add('flex')
                }
            })
        })
        grid.querySelectorAll('[data-remove-index]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation()
                files.splice(Number(this.getAttribute('data-remove-index')), 1)
                renderGrid()
            })
        })
    }

    if (fileInput) fileInput.addEventListener('change', function () {
        var selected = Array.prototype.slice.call(this.files || [])
        selected.forEach(function (file) {
            if (!file.type.match(/^image\//)) return
            var reader = new FileReader()
            reader.onload = function (e) {
                files.push({ url: e.target.result })
                renderGrid()
            }
            reader.readAsDataURL(file)
        })
        this.value = ''
    })

    if (lightboxClose) lightboxClose.addEventListener('click', function () {
        lightbox.classList.add('hidden')
        lightbox.classList.remove('flex')
    })
    if (lightbox) lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox) {
            lightbox.classList.add('hidden')
            lightbox.classList.remove('flex')
        }
    })
    if (printBtn) printBtn.addEventListener('click', function () { window.print() })
    if (saveBtn) saveBtn.addEventListener('click', function () {
        if (typeof window.showToast === 'function') {
            window.showToast('Front-end preview — result saving is enabled once the x-ray backend is connected.', 'info')
        }
    })
})();
</script>
