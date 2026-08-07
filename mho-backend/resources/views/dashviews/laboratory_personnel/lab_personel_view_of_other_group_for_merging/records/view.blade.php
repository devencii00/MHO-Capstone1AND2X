@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gray-50/60">

    {{-- ============ HEADER ============ --}}
    <div class="px-6 pt-6 mx-auto max-w-7xl">
        <div class="flex items-center justify-between px-6 py-4 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center gap-4">
                <a href="{{ route('staff1.records') }}" class="flex items-center justify-center w-8 h-8 text-gray-500 transition rounded-lg hover:bg-gray-100 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Patient Records</h1>
                    <p class="text-xs text-gray-400">Viewing all services for this patient</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="flex items-center justify-center w-8 h-8 border border-gray-200 rounded-full">
                    <span class="text-xs font-bold text-gray-600">MHO</span>
                </div>
                <span>Primary Care Facility</span>
            </div>
        </div>
    </div>

    <div class="px-6 pb-6 pt-5 mx-auto max-w-7xl">

        @if($record && $record->patient)

            {{-- ============ PATIENT INFO CARD ============ --}}
            <div class="p-5 mb-5 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <div class="grid flex-1 grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                        <div>
                            <p class="mb-0.5 text-xs font-semibold tracking-wider text-gray-400 uppercase">Patient ID</p>
                            <p class="text-sm font-bold text-gray-800">
                                @php
                                    $yr = date('Y', strtotime($record->created_at));
                                    echo $record->patient_id ? $yr.'-'.str_pad($record->patient_id, 3, '0', STR_PAD_LEFT) : '—';
                                @endphp
                            </p>
                        </div>
                        <div>
                            <p class="mb-0.5 text-xs font-semibold tracking-wider text-gray-400 uppercase">Full Name</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ trim(($record->last_name ?? '').' '.($record->first_name ?? '').' '.($record->middle_name ?? '')) ?: '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-0.5 text-xs font-semibold tracking-wider text-gray-400 uppercase">Age</p>
                            <p class="text-sm font-bold text-gray-800">{{ $record->age ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="mb-0.5 text-xs font-semibold tracking-wider text-gray-400 uppercase">Gender</p>
                            <p class="text-sm font-bold text-gray-800 capitalize">{{ $record->gender ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="mb-0.5 text-xs font-semibold tracking-wider text-gray-400 uppercase">Date Registered</p>
                            <p class="text-sm font-bold text-gray-800">
                                {{ $record->created_at ? $record->created_at->format('M d, Y') : '—' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $record->created_at ? $record->created_at->format('h:i A') : '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ SERVICE CARDS ============ --}}
            <div id="serviceCardsSection" class="mb-5 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-800">Patient Services & Results</h2>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $allRecords->count() }} service{{ $allRecords->count() === 1 ? '' : 's' }} on record
                        </p>
                    </div>
                    <select id="categoryFilter" onchange="filterCards(this.value)"
                        class="px-3 py-2 pr-8 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg appearance-none cursor-pointer focus:outline-none focus:border-green-400">
                        <option value="">All Services</option>
                        <option value="xray">X-Ray</option>
                        <option value="ultrasound">Ultrasound</option>
                        <option value="laboratory">Laboratory</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="serviceCardsGrid">
                    @forelse($allRecords as $svcRecord)
                        @php
                            $svcName   = $svcRecord->services ?? 'Unknown Service';
                            $svcLower  = strtolower($svcName);
                            $medResult = $svcRecord->medicalResult;
                            $status    = $medResult ? $medResult->status : ($svcRecord->status ?? 'completed');
                            $encodedBy = $svcRecord->creator->name ?? 'MHO Primary Care Facility';

                            if (str_contains($svcLower,'ultrasound')||str_contains($svcLower,'utz')||str_contains($svcLower,'abdomen')||str_contains($svcLower,'transvaginal')||str_contains($svcLower,'doppler')||str_contains($svcLower,'thyroid')||str_contains($svcLower,'breast')||str_contains($svcLower,'renal')||str_contains($svcLower,'pelvic')) {
                                $category = 'ultrasound'; $iconColor = 'text-blue-500'; $bgColor = 'bg-blue-50'; $badgeClass = 'bg-blue-100 text-blue-800'; $borderHover = 'hover:border-blue-300';
                            } elseif (str_contains($svcLower,'lab')||str_contains($svcLower,'urinalysis')||str_contains($svcLower,'fecalysis')||str_contains($svcLower,'cbc')||str_contains($svcLower,'complete blood')||str_contains($svcLower,'blood')||str_contains($svcLower,'urine')||str_contains($svcLower,'stool')||str_contains($svcLower,'glucose')||str_contains($svcLower,'lipid')||str_contains($svcLower,'creatinine')||str_contains($svcLower,'hba1c')||str_contains($svcLower,'chemistry')||str_contains($svcLower,'hematology')||str_contains($svcLower,'sugar')||str_contains($svcLower,'cholesterol')||str_contains($svcLower,'triglycerides')||str_contains($svcLower,'uric')||str_contains($svcLower,'bun')||str_contains($svcLower,'sgot')||str_contains($svcLower,'sgpt')||str_contains($svcLower,'sodium')||str_contains($svcLower,'potassium')||str_contains($svcLower,'calcium')||str_contains($svcLower,'albumin')) {
                                $category = 'laboratory'; $iconColor = 'text-purple-500'; $bgColor = 'bg-purple-50'; $badgeClass = 'bg-purple-100 text-purple-800'; $borderHover = 'hover:border-purple-300';
                            } else {
                                $category = 'xray'; $iconColor = 'text-green-600'; $bgColor = 'bg-green-50'; $badgeClass = 'bg-green-100 text-green-800'; $borderHover = 'hover:border-green-300';
                            }
                        @endphp

                        <div class="service-card border border-gray-200 rounded-xl p-4 transition-all duration-200 {{ $borderHover }} hover:shadow-md group cursor-pointer relative"
                             data-category="{{ $category }}"
                             data-record-id="{{ $svcRecord->id }}"
                             onclick="openDetail({{ $svcRecord->id }})">

                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 inline-block"></span>
                                    {{ strtoupper(str_replace('_',' ', $status)) }}
                                </span>
                            </div>

                            <div class="flex items-center justify-center w-11 h-11 mb-3 {{ $bgColor }} rounded-lg transition group-hover:scale-105">
                                @if($category === 'ultrasound')
                                    <svg class="w-5 h-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/></svg>
                                @elseif($category === 'laboratory')
                                    <svg class="w-5 h-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2v2h1v14a4 4 0 0 0 8 0V4h1V2H7zm6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm0-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm1-6h-4V4h4v2z"/></svg>
                                @else
                                    <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                @endif
                            </div>

                            <h3 class="pr-16 mb-2 text-sm font-bold leading-tight text-gray-800 uppercase line-clamp-2">{{ $svcName }}</h3>

                            <div class="pt-3 mb-4 border-t border-gray-100">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-1 text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="font-semibold text-gray-700">{{ $svcRecord->created_at ? $svcRecord->created_at->format('M d, Y h:i A') : '—' }}</span>
                                    </div>
                                    <span class="text-gray-400">|</span>
                                    <div class="flex items-center gap-1 text-gray-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="font-semibold text-gray-700">{{ $encodedBy }}</span>
                                    </div>
                                </div>
                            </div>

                            <button type="button"
                                class="w-full py-2 border border-gray-200 rounded-lg text-gray-600 font-semibold text-xs hover:bg-green-500 hover:text-white hover:border-green-500 transition-all duration-150 flex items-center justify-center gap-1.5">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                View Details
                            </button>
                        </div>
                    @empty
                        <div class="col-span-4 py-12 text-center text-gray-400">
                            <p class="text-sm font-semibold">No services recorded</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ============ DETAIL MODAL (centered popup) ============ --}}
            <div id="detailPanel"
                 class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                 onclick="if(event.target===this) closeDetail()">
                <div id="detailModalBox"
                     class="relative w-full max-w-5xl max-h-[90vh] overflow-y-auto bg-white shadow-2xl rounded-2xl scrollbar-hide"
                     onclick="event.stopPropagation()">
                    <div id="detailContent"></div>
                </div>
            </div>

            {{-- ============ LIGHTBOX ============ --}}
            <div id="lightbox" class="fixed inset-0 z-[60] items-center justify-center hidden bg-black/90" onclick="closeLightbox()">
                <img id="lightboxImg" src="" alt="Full view" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl">
                <button class="absolute text-white transition top-5 right-5 hover:text-gray-300" onclick="closeLightbox()">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

        @else
            <div class="p-12 text-center bg-white border border-gray-100 shadow-sm rounded-xl">
                <h3 class="mb-2 text-lg font-bold text-gray-800">Record Not Found</h3>
                <a href="{{ route('staff1.records') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-white transition bg-green-600 rounded-lg hover:bg-green-700">Back to Records</a>
            </div>
        @endif
    </div>
</div>

<script>
const recordData = {
    @foreach($allRecords as $r)
    @php
        $mr   = $r->medicalResult;
        $sLow = strtolower($r->services ?? '');
        $svcNameLower = strtolower($r->services ?? '');

        $isUltrasound = str_contains($sLow,'ultrasound')||str_contains($sLow,'utz')||str_contains($sLow,'abdomen')||str_contains($sLow,'transvaginal')||str_contains($sLow,'doppler')||str_contains($sLow,'thyroid')||str_contains($sLow,'breast')||str_contains($sLow,'renal')||str_contains($sLow,'pelvic');
        $isLab        = !$isUltrasound && (str_contains($sLow,'lab')||str_contains($sLow,'urinalysis')||str_contains($sLow,'fecalysis')||str_contains($sLow,'cbc')||str_contains($sLow,'complete blood')||str_contains($sLow,'blood')||str_contains($sLow,'urine')||str_contains($sLow,'stool')||str_contains($sLow,'glucose')||str_contains($sLow,'lipid')||str_contains($sLow,'creatinine')||str_contains($sLow,'hba1c')||str_contains($sLow,'chemistry')||str_contains($sLow,'hematology')||str_contains($sLow,'sugar')||str_contains($sLow,'cholesterol')||str_contains($sLow,'triglycerides')||str_contains($sLow,'uric')||str_contains($sLow,'bun')||str_contains($sLow,'sgot')||str_contains($sLow,'sgpt')||str_contains($sLow,'sodium')||str_contains($sLow,'potassium')||str_contains($sLow,'calcium')||str_contains($sLow,'albumin'));
        $cat          = $isUltrasound ? 'ultrasound' : ($isLab ? 'laboratory' : 'xray');

        $cleanText = function(string $text): string {
            $text = str_replace(["\r\n", "\r"], "\n", $text);
            $text = preg_replace('/^[ \t]+/m', '', $text);
            $text = preg_replace('/\n{3,}/', "\n\n", $text);
            $text = ltrim($text, "\n");
            return trim($text);
        };

        $splitFindingsImpression = function(string $text) use ($cleanText): array {
            $text = $cleanText($text);
            $pattern = '/(?:^|\n)[ \t]*impression\s*:[ \t]*(?:\n|$)/i';
            $parts = preg_split($pattern, $text, 2);
            if (count($parts) === 2) {
                return [trim($parts[0]) !== '' ? trim($parts[0]) : null, trim($parts[1]) !== '' ? trim($parts[1]) : null];
            }
            return [$text !== '' ? $text : null, null];
        };

        // ===== FINDINGS EXTRACTION =====
        $f   = null;
        $imp = null;

        if ($mr && $mr->findings) {
            $raw     = $mr->findings;
            $decoded = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : null);

            if (is_array($decoded)) {
                if ($isLab) {
                    // Check if findings_text contains raw lines (urinalysis/fecalysis/cbc case)
                    if (isset($decoded['findings_text']) && !empty($decoded['findings_text'])) {
                        $lines = explode("\n", $decoded['findings_text']);
                        $lines = array_map('trim', $lines);
                        $lines = array_values(array_filter($lines, fn($l) => $l !== ''));

                        if (str_contains($svcNameLower, 'urinalysis') || str_contains($svcNameLower, 'urine')) {
                            $urineKeys = ['color','transparency','ph','specific_gravity','albumin','sugar','blood','pus_cells','rbc','epithelial','mucus','bacteria','casts','crystals'];
                            $f = [];
                            foreach ($lines as $i => $line) {
                                if (isset($urineKeys[$i])) $f[$urineKeys[$i]] = $line;
                            }
                        } elseif (str_contains($svcNameLower, 'fecalysis') || str_contains($svcNameLower, 'fecal') || str_contains($svcNameLower, 'stool')) {
                            $fecalKeys = ['color','consistency','pus_cells','rbc','bacteria','fat_globules','amoeba','ova_egg','result'];
                            $f = [];
                            foreach ($lines as $i => $line) {
                                if (isset($fecalKeys[$i])) $f[$fecalKeys[$i]] = $line;
                            }
                        } elseif (str_contains($svcNameLower, 'cbc') || str_contains($svcNameLower, 'complete blood count') || str_contains($svcNameLower, 'hematology') || (str_contains($svcNameLower, 'blood') && !str_contains($svcNameLower, 'fecal'))) {
                            // FIX: CBC raw findings_text lines mapped positionally to the
                            // same field order as the CBC + Differential Count form
                            // (Hemoglobin, Hematocrit, WBC Count, Segmenters, Lymphocytes,
                            // Monocytes, Eosinophils, Basophils, Band, Platelet Count).
                            // Without this, CBC results only had a generic 'else' branch
                            // to fall back to, so the structured table never populated.
                            $cbcKeys = ['hemoglobin','hematocrit','wbc_count','segmenters','lymphocytes','monocytes','eosinophils','basophils','band','platelet'];
                            $f = [];
                            foreach ($lines as $i => $line) {
                                if (isset($cbcKeys[$i])) $f[$cbcKeys[$i]] = $line;
                            }
                        } else {
                            $f = array_filter($decoded, fn($k) => $k !== 'impression', ARRAY_FILTER_USE_KEY);
                        }
                    } else {
                        $f = array_filter($decoded, fn($k) => $k !== 'impression', ARRAY_FILTER_USE_KEY);
                    }
                    $imp = isset($decoded['impression']) && trim((string)$decoded['impression']) !== '' ? trim((string)$decoded['impression']) : null;
                } else {
                    $imp = isset($decoded['impression']) && trim((string)$decoded['impression']) !== '' ? trim((string)$decoded['impression']) : null;
                    if (isset($decoded['findings_text']) && trim($decoded['findings_text']) !== '') {
                        [$f, $impFromText] = $splitFindingsImpression($decoded['findings_text']);
                        if (!$imp && $impFromText) $imp = $impFromText;
                    } else {
                        $lines = [];
                        foreach ($decoded as $key => $val) {
                            if ($key === 'impression') continue;
                            if (!empty($val) && $val !== 'No findings recorded.') $lines[] = ucfirst(str_replace('_',' ',$key)).': '.$val;
                        }
                        $f = !empty($lines) ? implode("\n", $lines) : null;
                    }
                }
            } else {
                $text = $cleanText((string)$raw);
                $text = preg_replace('/^findings\s*text\s*:\s*/i', '', $text);
                $text = trim($text);
                [$f, $imp] = $splitFindingsImpression($text);
            }
        }

        if (!$f && !$isLab) {
            $resultText = $r->result ?? null;
            if ($resultText) {
                $resultText = $cleanText((string)$resultText);
                $resultText = preg_replace('/^findings\s*text\s*:\s*/i', '', $resultText);
                $resultText = trim($resultText);
                [$f, $impFromResult] = $splitFindingsImpression($resultText);
                if (!$imp && $impFromResult) $imp = $impFromResult;
            }
        }

        if (!$imp && $mr) {
            foreach ([$mr->impression ?? null, $mr->doctor_remarks ?? null] as $c) {
                if ($c && trim($c) !== '' && strcasecmp(trim($c), 'No impression recorded.') !== 0) { $imp = $cleanText((string)$c); break; }
            }
        }
        if (!$imp) { $d = $r->description ?? null; if ($d && trim($d) !== '') $imp = $cleanText((string)$d); }

        if (is_string($f) && trim($f) === '') $f = null;
        if (is_string($imp) && trim($imp) === '') $imp = null;
        if ($isLab && !is_array($f)) $f = [];

        $remarks = $mr ? ($mr->doctor_remarks ?? null) : null;
        if ($remarks && $imp && trim($remarks) === trim($imp)) $remarks = null;

        $img = null;
        if ($mr) { if ($mr->xray_image) $img = asset('storage/'.$mr->xray_image); elseif ($mr->file_path) $img = asset('storage/'.$mr->file_path); }
        $pdf = ($mr && $mr->pdf_path) ? asset('storage/'.$mr->pdf_path) : null;

        $statusRaw = $mr ? ($mr->status ?? 'completed') : 'completed';
        $statusLabel = strtoupper(str_replace('_',' ',$statusRaw));
        $dateTime = $r->created_at ? $r->created_at->format('M d, Y | h:i A') : '—';
        $encodedBy = $r->creator->name ?? 'MHO Primary Care Facility';
    @endphp
    {{ $r->id }}: {
        id: {{ $r->id }},
        service: @json($r->services ?? 'Unknown Service'),
        category: @json($cat),
        status: @json($statusLabel),
        dateTime: @json($dateTime),
        encodedBy: @json($encodedBy),
        findings: @json($f),
        impression: @json($imp),
        remarks: @json($remarks),
        image: @json($img),
        pdf: @json($pdf),
    },
    @endforeach
};

const icons={xray:`<svg class="text-green-600 w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>`,ultrasound:`<svg class="text-blue-500 w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/></svg>`,laboratory:`<svg class="text-purple-500 w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2v2h1v14a4 4 0 0 0 8 0V4h1V2H7zm6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm0-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm1-6h-4V4h4v2z"/></svg>`};
const iconBg={xray:'bg-green-50',ultrasound:'bg-blue-50',laboratory:'bg-purple-50'};
const badgeCls={xray:'bg-green-100 text-green-700',ultrasound:'bg-blue-100 text-blue-700',laboratory:'bg-purple-100 text-purple-700'};
const dotCls={xray:'bg-green-500',ultrasound:'bg-blue-500',laboratory:'bg-purple-500'};

function openDetail(id){
    const d=recordData[id];
    if(!d)return;
    const panel=document.getElementById('detailPanel');
    panel.classList.remove('hidden');
    panel.classList.add('flex');
    document.body.style.overflow='hidden';
    document.getElementById('detailModalBox').scrollTop=0;
    d.category==='laboratory'?loadLabForm(id,d,document.getElementById('detailContent')):loadImageViewer(id,d,document.getElementById('detailContent'));
}
function closeDetail(){
    const panel=document.getElementById('detailPanel');
    panel.classList.add('hidden');
    panel.classList.remove('flex');
    document.body.style.overflow='';
}

document.addEventListener('keydown',function(e){
    if(e.key==='Escape'){
        const lb=document.getElementById('lightbox');
        if(lb && !lb.classList.contains('hidden')){ closeLightbox(); return; }
        const panel=document.getElementById('detailPanel');
        if(panel && !panel.classList.contains('hidden')){ closeDetail(); }
    }
});

function buildDetailHeader(d){return`<div class="px-6 py-5 border-b border-gray-100"><button onclick="closeDetail()" class="absolute z-10 flex items-center justify-center w-8 h-8 text-gray-400 transition bg-white border border-gray-200 rounded-lg top-4 right-4 hover:text-gray-600 hover:bg-gray-50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button><div class="flex items-center gap-4 mb-5 pr-12"><div class="flex items-center justify-center flex-shrink-0 w-14 h-14 ${iconBg[d.category]} rounded-xl">${icons[d.category]||icons.xray}</div><div class="flex-1 min-w-0"><div class="flex flex-wrap items-center gap-3"><h2 class="text-xl font-extrabold tracking-tight text-gray-800 uppercase">${escHtml(d.service)}</h2><span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold ${badgeCls[d.category]} rounded-full"><span class="w-1.5 h-1.5 rounded-full ${dotCls[d.category]} inline-block"></span>${escHtml(d.status)}</span></div></div></div><div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-5"><div><p class="mb-1 text-xs font-semibold text-gray-400 uppercase">Patient Name</p><p class="text-sm font-bold text-gray-800">{{ ($record->last_name ?? '').', '.($record->first_name ?? '') }}</p></div><div><p class="mb-1 text-xs font-semibold text-gray-400 uppercase">Age / Gender</p><p class="text-sm font-bold text-gray-800 capitalize">{{ ($record->age ?? '—') }} / {{ ($record->gender ?? '—') }}</p></div><div><p class="mb-1 text-xs font-semibold text-gray-400 uppercase">Date & Time</p><p class="text-sm font-bold text-gray-800">${escHtml(d.dateTime)}</p></div><div><p class="mb-1 text-xs font-semibold text-gray-400 uppercase">Encoded By</p><p class="text-sm font-bold text-gray-800">${escHtml(d.encodedBy)}</p></div><div><p class="mb-1 text-xs font-semibold text-gray-400 uppercase">Service Type</p><p class="text-sm font-bold text-gray-800 capitalize">${escHtml(d.category)}</p></div></div></div>`;}

function bulletizeText(input){if(!input)return null;let lines=Array.isArray(input)?input:String(input).split('\n');lines=lines.map(l=>l.replace(/^[ \t]+/,'').trimEnd());while(lines.length>0&&lines[0].trim()==='')lines.shift();while(lines.length>0&&lines[lines.length-1].trim()==='')lines.pop();if(lines.length===0)return null;return lines.map(l=>l.trim()===''?'':(l.startsWith('•')?l:`• ${l}`)).join('\n');}
function resolveFindingsText(findings){if(!findings)return null;if(typeof findings==='string'){const text=findings.replace(/^[ \t]+/gm,'').trim();return text!==''?bulletizeText(text):null;}if(typeof findings==='object'&&!Array.isArray(findings)){const lines=Object.entries(findings).filter(([k,v])=>k!=='impression'&&v!==null&&v!==undefined&&String(v).trim()!=='').map(([k,v])=>`${k.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase())}: ${v}`);return bulletizeText(lines);}return null;}

function loadImageViewer(id,d,container){const findingsText=resolveFindingsText(d.findings);const rawImpression=(d.impression&&d.impression.trim()!==''&&d.impression!=='No impression recorded.')?d.impression.trim():null;const impressionText=rawImpression?bulletizeText(rawImpression):null;const remarksText=(d.remarks&&d.remarks.trim()!=='')?d.remarks.trim():null;container.innerHTML=`<div class="relative">${buildDetailHeader(d)}<div class="flex flex-col lg:flex-row" style="min-height:560px;"><div class="flex-1 p-6 space-y-5 overflow-hidden border-b border-gray-100 lg:border-b-0 lg:border-r"><div><h4 class="mb-3 text-xs font-semibold tracking-wider text-green-600 uppercase">Findings</h4><div class="p-4 overflow-y-auto text-sm leading-relaxed text-left text-gray-700 whitespace-pre-wrap border border-gray-100 bg-gray-50 rounded-xl scrollbar-hide" style="max-height:200px;min-height:80px;">${findingsText?escHtml(findingsText):'<span class="italic text-gray-400">No findings recorded.</span>'}</div></div><div><h4 class="mb-3 text-xs font-semibold tracking-wider text-green-600 uppercase">Impression</h4><div class="p-4 overflow-y-auto text-sm leading-relaxed text-left text-gray-700 whitespace-pre-wrap border border-gray-100 bg-gray-50 rounded-xl scrollbar-hide" style="max-height:150px;min-height:60px;">${impressionText?escHtml(impressionText):'<span class="italic text-gray-400">No impression recorded.</span>'}</div></div>${remarksText?`<div><h4 class="mb-3 text-xs font-semibold tracking-wider text-green-600 uppercase">Remarks</h4><div class="p-4 overflow-y-auto text-sm leading-relaxed text-left text-gray-700 whitespace-pre-wrap border border-gray-100 bg-gray-50 rounded-xl scrollbar-hide" style="max-height:120px;min-height:50px;">${escHtml(remarksText)}</div></div>`:''}</div><div class="w-full lg:w-[420px] xl:w-[480px] flex-shrink-0 p-5 flex flex-col gap-3"><div class="flex items-center justify-between"><h4 class="text-xs font-semibold tracking-wider text-green-600 uppercase">Images</h4><button onclick="openLightbox(document.getElementById('detailMainImage').src)" class="${d.image?'':'hidden'} p-1.5 text-gray-400 transition rounded-lg hover:text-gray-600 hover:bg-gray-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg></button></div><div class="relative flex-1 overflow-hidden bg-gray-100 rounded-xl" style="min-height:340px;"><div class="${d.image?'hidden':''} absolute inset-0 flex flex-col items-center justify-center text-gray-500"><svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-sm text-gray-400">No image available</p></div><img id="detailMainImage" src="${d.image||''}" alt="Result image" class="${d.image?'':'hidden'} absolute inset-0 w-full h-full object-cover cursor-zoom-in rounded-xl" onclick="openLightbox(this.src)"></div><div class="grid grid-cols-4 gap-2">${d.image?Array(4).fill(0).map((_,i)=>`<div class="h-16 w-full rounded-lg overflow-hidden border-2 transition ${i===0?'border-green-400':'border-transparent hover:border-gray-400'} cursor-pointer"><img src="${d.image}" alt="thumb" class="object-cover w-full h-full" onclick="document.getElementById('detailMainImage').src=this.src;this.closest('.grid').querySelectorAll('img').forEach(t=>{t.parentElement.classList.remove('border-green-400');t.parentElement.classList.add('border-transparent')});this.parentElement.classList.remove('border-transparent');this.parentElement.classList.add('border-green-400')"></div>`).join(''):Array(4).fill('<div class="h-16 bg-gray-200 rounded-lg"></div>').join('')}</div>${d.pdf?`<a href="${d.pdf}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 text-sm font-semibold text-red-600 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 transition"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 7V3.5L18.5 9H13z"/></svg>Download PDF</a>`:''}</div></div></div>`;}

function loadLabForm(id,d,container){let findings={};if(d.findings&&typeof d.findings==='object'&&!Array.isArray(d.findings)){findings=d.findings;}else if(d.findings&&typeof d.findings==='string'){try{findings=JSON.parse(d.findings);}catch(e){findings={findings_text:d.findings};}}const rawImpLab=(d.impression&&d.impression.trim()!==''&&d.impression!=='No impression recorded.')?d.impression.trim():null;const impressionText=rawImpLab?bulletizeText(rawImpLab):null;container.innerHTML=`<div class="relative">${buildDetailHeader(d)}<div style="background:#fff;padding:40px 48px;"><div style="display:flex;align-items:center;gap:4px;margin-bottom:0;"><div style="flex-shrink:0;margin-right:2px;"><img src="{{ asset('images/MHO12.jpg') }}" alt="MHO Logo" style="width:68px;height:68px;border-radius:8px;object-fit:contain;display:block;"></div><div style="text-align:center;flex:1;"><div style="font-size:12px;color:#6b7280;">Republic of the Philippines</div><div style="font-size:12px;color:#6b7280;">Province of Misamis Oriental</div><div style="font-size:17px;font-weight:800;color:#111827;margin:3px 0;">OPOL MUNICIPAL HEALTH CENTER &amp; LYING-IN CLINIC</div><div style="font-size:12px;color:#6b7280;">Taboc, Opol, Misamis Oriental</div><div style="font-size:14px;font-weight:700;color:#1a6b3c;margin-top:4px;letter-spacing:.5px;">LABORATORY SECTION</div></div></div><div style="border-top:2px solid #1a6b3c;margin:14px 0;"></div><div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px 32px;margin-bottom:18px;"><div style="display:flex;gap:10px;align-items:baseline;"><span style="font-size:13px;font-weight:600;color:#6b7280;white-space:nowrap;min-width:65px;">Patient:</span><span style="font-size:14px;font-weight:500;color:#111827;">{{ ($record->last_name ?? '').', '.($record->first_name ?? '').' '.($record->middle_name ?? '') }}</span></div><div style="display:flex;gap:10px;align-items:baseline;"><span style="font-size:13px;font-weight:600;color:#6b7280;white-space:nowrap;min-width:65px;">Age / Sex:</span><span style="font-size:14px;font-weight:500;color:#111827;">{{ ($record->age ?? '—') }} / {{ ucfirst($record->gender ?? '—') }}</span></div><div style="display:flex;gap:10px;align-items:baseline;"><span style="font-size:13px;font-weight:600;color:#6b7280;white-space:nowrap;min-width:65px;">Date:</span><span style="font-size:14px;font-weight:500;color:#111827;">{{ $record->created_at ? $record->created_at->format('F d, Y') : now()->format('F d, Y') }}</span></div></div>${buildLabFormContent(d.service.toLowerCase(),findings)}${impressionText?`<div style="margin-top:20px;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;"><div style="background:#f0fdf4;border-bottom:1px solid #d1fae5;padding:10px 16px;font-size:12px;font-weight:700;color:#1a6b3c;letter-spacing:.8px;text-transform:uppercase;">Impression / Remarks</div><div style="padding:14px 16px;font-size:14px;color:#111827;line-height:1.7;white-space:pre-wrap;text-align:left;background:#fafafa;">${escHtml(impressionText)}</div></div>`:''}${d.pdf?`<div style="margin-top:20px;"><a href="${d.pdf}" target="_blank" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;font-size:14px;font-weight:600;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;text-decoration:none;"><svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 7V3.5L18.5 9H13z"/></svg>Download PDF</a></div>`:''}</div></div>`;}

function buildLabFormContent(serviceName,findings){
    const titleStyle='font-size:15px;font-weight:800;color:#1a6b3c;text-align:center;letter-spacing:1px;margin-bottom:16px;margin-top:18px;text-transform:uppercase;';
    const sectionHdrStyle='background:#f0fdf4;border-bottom:1px solid #d1fae5;padding:10px 16px;font-size:12px;font-weight:700;color:#1a6b3c;letter-spacing:.8px;text-transform:uppercase;';
    const labelTd='padding:10px 16px;font-size:13px;font-weight:600;color:#374151;width:42%;';
    const unitTd='font-size:12px;color:#9ca3af;padding:0 6px;width:12%;white-space:nowrap;';
    const normalTd='font-size:12px;color:#9ca3af;padding:0 16px 0 6px;white-space:nowrap;';
    const rowStyle='border-bottom:1px solid #f3f4f6;';

    const getF=(keys)=>{
        if(!Array.isArray(keys))keys=[keys];
        for(const key of keys){
            if(findings[key]!==undefined&&findings[key]!==null&&String(findings[key]).trim()!==''){
                return String(findings[key]).trim();
            }
        }
        return undefined;
    };
    const val=(keys,placeholder)=>{
        const v=getF(keys);
        const display=v!==undefined?escHtml(v):`<span style="color:#d1d5db;">${placeholder||'—'}</span>`;
        return`<div style="padding:8px 12px 8px 0;font-size:14px;color:#111827;min-height:36px;display:flex;align-items:center;">${display}</div>`;
    };

    if(serviceName.includes('fecalysis')||serviceName.includes('fecal')||serviceName.includes('stool')){
        return`<div style="${titleStyle}">FECALYSIS RESULT</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:0;"><div style="border:1px solid #e5e7eb;border-radius:8px 0 0 8px;overflow:hidden;border-right:none;"><div style="${sectionHdrStyle}">MACROSCOPIC EXAMINATION</div><table style="width:100%;border-collapse:collapse;"><tr style="${rowStyle}"><td style="${labelTd}">COLOR</td><td style="padding:4px 12px 4px 0;">${val(['color'],'Not recorded')}</td></tr><tr><td style="${labelTd}">CONSISTENCY</td><td style="padding:4px 12px 4px 0;">${val(['consistency'],'Not recorded')}</td></tr></table></div><div style="border:1px solid #e5e7eb;border-radius:0 8px 8px 0;overflow:hidden;"><div style="${sectionHdrStyle}">MICROSCOPIC EXAMINATION</div><table style="width:100%;border-collapse:collapse;"><tr style="${rowStyle}"><td style="${labelTd}">PUS CELLS</td><td style="${unitTd}">/HPF</td><td style="padding:4px 12px 4px 0;">${val(['pus_cells','pus cells','pus'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">RED BLOOD CELLS</td><td style="${unitTd}">/HPF</td><td style="padding:4px 12px 4px 0;">${val(['rbc','red_blood_cells','red blood cells'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">BACTERIA</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['bacteria'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">FAT GLOBULES</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['fat_globules','fat globules'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">AMOEBA</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['amoeba'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">OVA / EGG</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['ova_egg','ova/egg','ova'])}</td></tr><tr><td style="${labelTd}">RESULT</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['result'])}</td></tr></table></div></div>`;
    }

    if(serviceName.includes('urinalysis')||serviceName.includes('urine')){
        return`<div style="${titleStyle}">URINALYSIS RESULT</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:0;"><div style="border:1px solid #e5e7eb;border-radius:8px 0 0 0;overflow:hidden;border-right:none;border-bottom:none;"><div style="${sectionHdrStyle}">PHYSICAL EXAMINATION</div><table style="width:100%;border-collapse:collapse;"><tr style="${rowStyle}"><td style="${labelTd}">COLOR</td><td style="padding:4px 12px 4px 0;">${val(['color','colour'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">TRANSPARENCY</td><td style="padding:4px 12px 4px 0;">${val(['transparency','clarity'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">REACTION (pH)</td><td style="padding:4px 12px 4px 0;">${val(['ph','reaction','reaction_ph'])}</td></tr><tr><td style="${labelTd}">SPECIFIC GRAVITY</td><td style="padding:4px 12px 4px 0;">${val(['specific_gravity','sp_gravity','sp. gravity'])}</td></tr></table></div><div style="border:1px solid #e5e7eb;border-radius:0 8px 0 0;overflow:hidden;border-bottom:none;"><div style="${sectionHdrStyle}">CHEMICAL EXAMINATION</div><table style="width:100%;border-collapse:collapse;"><tr style="${rowStyle}"><td style="${labelTd}">ALBUMIN</td><td style="padding:4px 12px 4px 0;">${val(['albumin'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">SUGAR</td><td style="padding:4px 12px 4px 0;">${val(['sugar'])}</td></tr><tr><td style="${labelTd}">BLOOD</td><td style="padding:4px 12px 4px 0;">${val(['blood'])}</td></tr></table></div></div><div style="border:1px solid #e5e7eb;border-radius:0 0 8px 8px;overflow:hidden;border-top:none;"><div style="${sectionHdrStyle}">MICROSCOPIC EXAMINATION</div><table style="width:100%;border-collapse:collapse;"><tr style="${rowStyle}"><td style="${labelTd}">PUS CELLS</td><td style="${unitTd}">/HPF</td><td style="padding:4px 12px 4px 0;">${val(['pus_cells','pus cells','pus','wbc'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">RED BLOOD CELLS</td><td style="${unitTd}">/HPF</td><td style="padding:4px 12px 4px 0;">${val(['rbc','red_blood_cells','red blood cells'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">EPITHELIAL CELLS</td><td style="${unitTd}">/HPF</td><td style="padding:4px 12px 4px 0;">${val(['epithelial','epithelial_cells','epithelial cells'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">MUCUS THREADS</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['mucus','mucus_threads','mucus threads'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">BACTERIA</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['bacteria'])}</td></tr><tr style="${rowStyle}"><td style="${labelTd}">CASTS</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['casts'])}</td></tr><tr><td style="${labelTd}">CRYSTALS</td><td style="${unitTd}"></td><td style="padding:4px 12px 4px 0;">${val(['crystals'])}</td></tr></table></div>`;
    }

    // ════════════════════════════════════════════════════
    // COMPLETE BLOOD COUNT (CBC) — structured table kung naa
    // structured fields (Hemoglobin, Segmenters, etc.), pero
    // mo-fallback sa plain-text display kung ang naa lang kay
    // free-text "findings_text" (gikan sa generic encode form).
    // ════════════════════════════════════════════════════
    if(serviceName.includes('complete blood count')||serviceName.includes('cbc')||serviceName.includes('hematology')||(serviceName.includes('blood')&&!serviceName.includes('fecal'))){

        const structuredKeys = [
            'hemoglobin','hgb','hb',
            'hematocrit','hct',
            'wbc_count','wbc count',
            'segmenters','lymphocytes','monocytes','eosinophils','basophils','band',
            'platelet','platelets','plt'
        ];
        const hasStructuredValue = structuredKeys.some(k => getF([k]) !== undefined);

        if (hasStructuredValue) {
            return `<div style="${titleStyle}">CBC RESULT</div>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;">
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Hemoglobin</td>
                        <td style="padding:4px 12px 4px 0;">${val(['hemoglobin','hgb','hb'])}</td>
                        <td style="${normalTd}">F: 11.7 – 14.5 g/dl<br>M: 13.7 – 16.7 g/dl</td>
                    </tr>
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Hematocrit</td>
                        <td style="padding:4px 12px 4px 0;">${val(['hematocrit','hct'])}</td>
                        <td style="${normalTd}">F: 34.1 – 44.3 vols%<br>M: 40.5 – 42.7 vols%</td>
                    </tr>
                    <tr>
                        <td style="${labelTd}">WBC Count</td>
                        <td style="padding:4px 12px 4px 0;">${val(['wbc_count','wbc count'])}</td>
                        <td style="${normalTd}">5,000 – 10,000 cells/mm³</td>
                    </tr>
                </table>
            </div>

            <div style="${titleStyle}">DIFFERENTIAL COUNT</div>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;">
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Segmenters</td>
                        <td style="padding:4px 12px 4px 0;">${val(['segmenters'])}</td>
                        <td style="${normalTd}">45 – 70 %</td>
                    </tr>
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Lymphocytes</td>
                        <td style="padding:4px 12px 4px 0;">${val(['lymphocytes'])}</td>
                        <td style="${normalTd}">18 – 45 %</td>
                    </tr>
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Monocytes</td>
                        <td style="padding:4px 12px 4px 0;">${val(['monocytes'])}</td>
                        <td style="${normalTd}">4 – 8 %</td>
                    </tr>
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Eosinophils</td>
                        <td style="padding:4px 12px 4px 0;">${val(['eosinophils'])}</td>
                        <td style="${normalTd}">2 – 3 %</td>
                    </tr>
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Basophils</td>
                        <td style="padding:4px 12px 4px 0;">${val(['basophils'])}</td>
                        <td style="${normalTd}">0 – 0.5 %</td>
                    </tr>
                    <tr style="${rowStyle}">
                        <td style="${labelTd}">Band</td>
                        <td style="padding:4px 12px 4px 0;">${val(['band'])}</td>
                        <td style="${normalTd}">1 – 2 %</td>
                    </tr>
                    <tr>
                        <td style="${labelTd}">Platelet Count</td>
                        <td style="padding:4px 12px 4px 0;">${val(['platelet','platelets','plt'])}</td>
                        <td style="${normalTd}">F: 174,000 – 390,000<br>M: 144,000 – 372,000</td>
                    </tr>
                </table>
            </div>`;
        }

        // FALLBACK: walay structured fields — pero basin naa'y
        // free-text findings_text gikan sa generic encode form.
        const freeText = getF(['findings_text']);
        if (freeText) {
            return `<div style="${titleStyle}">CBC RESULT</div>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                <div style="${sectionHdrStyle}">FINDINGS</div>
                <div style="padding:14px 16px;font-size:14px;color:#111827;line-height:1.7;white-space:pre-wrap;text-align:left;background:#fafafa;">${escHtml(freeText)}</div>
            </div>`;
        }

        return `<div style="${titleStyle}">CBC RESULT</div>
        <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
            <div style="padding:24px;text-align:center;font-size:13px;color:#9ca3af;font-style:italic;">
                No CBC result encoded yet.
            </div>
        </div>`;
    }

    if(serviceName.includes('glucose')||serviceName.includes('blood sugar')||serviceName.includes('lipid')||serviceName.includes('cholesterol')||serviceName.includes('triglyceride')||serviceName.includes('creatinine')||serviceName.includes('uric')||serviceName.includes('bun')||serviceName.includes('sgot')||serviceName.includes('sgpt')||serviceName.includes('hba1c')||serviceName.includes('chemistry')){
        return`<div style="${titleStyle}">BLOOD CHEMISTRY RESULT</div><div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;"><div style="${sectionHdrStyle}">RESULTS</div><table style="width:100%;border-collapse:collapse;"><tr style="${rowStyle}"><td style="${labelTd}">Fasting Blood Sugar</td><td style="${unitTd}">mg/dL</td><td style="padding:4px 12px 4px 0;">${val(['fbs','fasting_blood_sugar','fasting blood sugar'])}</td><td style="${normalTd}">70-100</td></tr><tr style="${rowStyle}"><td style="${labelTd}">Total Cholesterol</td><td style="${unitTd}">mg/dL</td><td style="padding:4px 12px 4px 0;">${val(['cholesterol','total_cholesterol'])}</td><td style="${normalTd}">&lt;200</td></tr><tr style="${rowStyle}"><td style="${labelTd}">Triglycerides</td><td style="${unitTd}">mg/dL</td><td style="padding:4px 12px 4px 0;">${val(['triglycerides','triglyceride'])}</td><td style="${normalTd}">&lt;150</td></tr><tr style="${rowStyle}"><td style="${labelTd}">Creatinine</td><td style="${unitTd}">mg/dL</td><td style="padding:4px 12px 4px 0;">${val(['creatinine'])}</td><td style="${normalTd}">M: 0.7-1.3 / F: 0.5-1.1</td></tr><tr style="${rowStyle}"><td style="${labelTd}">BUN</td><td style="${unitTd}">mg/dL</td><td style="padding:4px 12px 4px 0;">${val(['bun'])}</td><td style="${normalTd}">7-20</td></tr><tr style="${rowStyle}"><td style="${labelTd}">Uric Acid</td><td style="${unitTd}">mg/dL</td><td style="padding:4px 12px 4px 0;">${val(['uric_acid','uric acid','uric'])}</td><td style="${normalTd}">M: 3.4-7.0 / F: 2.4-6.0</td></tr><tr style="${rowStyle}"><td style="${labelTd}">SGOT (AST)</td><td style="${unitTd}">U/L</td><td style="padding:4px 12px 4px 0;">${val(['sgot','ast'])}</td><td style="${normalTd}">10-40</td></tr><tr style="${rowStyle}"><td style="${labelTd}">SGPT (ALT)</td><td style="${unitTd}">U/L</td><td style="padding:4px 12px 4px 0;">${val(['sgpt','alt'])}</td><td style="${normalTd}">7-56</td></tr><tr><td style="${labelTd}">HbA1c</td><td style="${unitTd}">%</td><td style="padding:4px 12px 4px 0;">${val(['hba1c'])}</td><td style="${normalTd}">&lt;5.7</td></tr></table></div>`;
    }

    const knownKeys=Object.keys(findings).filter(k=>k!=='impression'&&findings[k]!==null&&findings[k]!=='');
    if(knownKeys.length>0){
        const rows=knownKeys.map(k=>`<tr style="${rowStyle}"><td style="${labelTd}">${escHtml(k.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase()))}</td><td style="padding:4px 12px 4px 0;">${val([k])}</td></tr>`).join('');
        return`<div style="${titleStyle}">LABORATORY RESULT</div><div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;"><div style="${sectionHdrStyle}">FINDINGS</div><table style="width:100%;border-collapse:collapse;">${rows}</table></div>`;
    }

    return`<div style="${titleStyle}">LABORATORY RESULT</div><div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;"><div style="${sectionHdrStyle}">FINDINGS</div><div style="padding:16px;font-size:14px;color:#6b7280;font-style:italic;">No findings recorded.</div></div>`;
}

function filterCards(value){document.querySelectorAll('.service-card').forEach(card=>{card.style.display=(!value||card.dataset.category===value)?'':'none';});}
function openLightbox(src){if(!src)return;document.getElementById('lightboxImg').src=src;const lb=document.getElementById('lightbox');lb.classList.remove('hidden');lb.classList.add('flex');}
function closeLightbox(){const lb=document.getElementById('lightbox');lb.classList.add('hidden');lb.classList.remove('flex');}
function escHtml(str){if(str===null||str===undefined)return'';return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}
</script>

<style>
.service-card{transition:all 0.2s ease;}
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
@media(max-width:640px){.result-card{padding:24px 18px;}}

/* Hide scrollbar for modal */
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Chrome, Safari and Opera */
}
</style>

@endsection