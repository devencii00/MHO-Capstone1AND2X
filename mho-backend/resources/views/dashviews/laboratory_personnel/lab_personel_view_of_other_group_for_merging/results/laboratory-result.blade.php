@extends('layouts.app')

@section('content')

@php
$serviceCategory = 'laboratory';
$serviceKey = strtolower(trim($record->service_name ?? ''));
@endphp

<div class="lab-wrap" id="printRoot">

    <div class="action-bar no-print">
        <a href="{{ url()->previous() }}" class="btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back
        </a>
        <div class="action-right">
            <button class="btn-save" id="saveBtn" onclick="saveResult()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Result
            </button>
            <button class="btn-print" onclick="printResult()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 12H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print
            </button>
        </div>
    </div>

    {{-- ── RESULT FORM CARD ── --}}
    <div class="result-card" id="printArea">

        {{-- CLINIC HEADER --}}
        <div class="clinic-header">
            <div class="clinic-logo">
                <img src="{{ asset('images/MHO12.jpg') }}" alt="MHO Logo" class="logo-img">
            </div>
            <div class="clinic-info">
                <div class="clinic-gov">Republic of the Philippines</div>
                <div class="clinic-province">Province of Misamis Oriental</div>
                <div class="clinic-name">OPOL MUNICIPAL HEALTH CENTER &amp; LYING-IN CLINIC</div>
                <div class="clinic-address">Taboc, Opol, Misamis Oriental</div>
                <div class="clinic-dept">LABORATORY SECTION</div>
            </div>
        </div>

        <div class="header-divider"></div>

        {{-- PATIENT INFO ROW --}}
        <div class="patient-info-grid">
            <div class="info-field">
                <span class="info-label">Patient:</span>
                <span class="info-value">{{ ($record->patient->last_name ?? '') . ', ' . ($record->patient->first_name ?? '') . ' ' . ($record->patient->middle_name ?? '') }}</span>
            </div>
            <div class="info-field">
                <span class="info-label">Age / Sex:</span>
                <span class="info-value">{{ $record->patient->age ?? '—' }} / {{ ucfirst($record->patient->gender ?? '—') }}</span>
            </div>
            <div class="info-field">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ $record->created_at ? $record->created_at->format('F d, Y') : now()->format('F d, Y') }}</span>
            </div>
            <div class="info-field info-field-full">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $record->patient->address ?? '—' }}</span>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════
             FECALYSIS
        ════════════════════════════════════════════════════ --}}
        @if(in_array($serviceKey, ['fecalysis', 'fecal occult blood', 'stool', 'stool exam', 'stool examination']))
        <div class="section-title">FECALYSIS RESULT</div>

        <div class="two-col-sections connected">
            <div class="result-section">
                <div class="section-header">MACROSCOPIC EXAMINATION</div>
                <table class="result-table">
                    <tr><td class="field-label">COLOR</td><td class="field-input"><input type="text" name="color" class="result-input" value="{{ $findings['color'] ?? '' }}" placeholder="e.g. Brown"></td></tr>
                    <tr><td class="field-label">CONSISTENCY</td><td class="field-input"><input type="text" name="consistency" class="result-input" value="{{ $findings['consistency'] ?? '' }}" placeholder="e.g. Formed"></td></tr>
                </table>
            </div>
            <div class="result-section">
                <div class="section-header">MICROSCOPIC EXAMINATION</div>
                <table class="result-table">
                    <tr><td class="field-label">PUS CELLS</td><td class="field-unit">/HPF</td><td class="field-input"><input type="text" name="pus_cells" class="result-input" value="{{ $findings['pus_cells'] ?? '' }}" placeholder="0-2"></td></tr>
                    <tr><td class="field-label">RED BLOOD CELLS</td><td class="field-unit">/HPF</td><td class="field-input"><input type="text" name="rbc" class="result-input" value="{{ $findings['rbc'] ?? '' }}" placeholder="0-1"></td></tr>
                    <tr><td class="field-label">BACTERIA</td><td class="field-unit"></td><td class="field-input"><input type="text" name="bacteria" class="result-input" value="{{ $findings['bacteria'] ?? '' }}" placeholder="Few / Moderate"></td></tr>
                    <tr><td class="field-label">FAT GLOBULES</td><td class="field-unit"></td><td class="field-input"><input type="text" name="fat_globules" class="result-input" value="{{ $findings['fat_globules'] ?? '' }}" placeholder="None"></td></tr>
                    <tr><td class="field-label">AMOEBA</td><td class="field-unit"></td><td class="field-input"><input type="text" name="amoeba" class="result-input" value="{{ $findings['amoeba'] ?? '' }}" placeholder="None seen"></td></tr>
                    <tr><td class="field-label">OVA / EGG</td><td class="field-unit"></td><td class="field-input"><input type="text" name="ova_egg" class="result-input" value="{{ $findings['ova_egg'] ?? '' }}" placeholder="None seen"></td></tr>
                    <tr><td class="field-label">RESULT</td><td class="field-unit"></td><td class="field-input"><input type="text" name="result" class="result-input" value="{{ $findings['result'] ?? '' }}" placeholder="Negative"></td></tr>
                </table>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════
             URINALYSIS
        ════════════════════════════════════════════════════ --}}
        @elseif($serviceKey === 'urinalysis')
        <div class="section-title">URINALYSIS RESULT</div>
        <div class="two-col-sections connected">
            <div class="result-section">
                <div class="section-header">PHYSICAL EXAMINATION</div>
                <table class="result-table">
                    <tr><td class="field-label">COLOR</td><td class="field-input"><input type="text" name="color" class="result-input" value="{{ $findings['color'] ?? '' }}" placeholder="Yellow"></td></tr>
                    <tr><td class="field-label">TRANSPARENCY</td><td class="field-input"><input type="text" name="transparency" class="result-input" value="{{ $findings['transparency'] ?? '' }}" placeholder="Clear"></td></tr>
                    <tr><td class="field-label">REACTION (pH)</td><td class="field-input"><input type="text" name="ph" class="result-input" value="{{ $findings['ph'] ?? '' }}" placeholder="6.0"></td></tr>
                    <tr><td class="field-label">SPECIFIC GRAVITY</td><td class="field-input"><input type="text" name="specific_gravity" class="result-input" value="{{ $findings['specific_gravity'] ?? '' }}" placeholder="1.015"></td></tr>
                </table>
            </div>
            <div class="result-section">
                <div class="section-header">CHEMICAL EXAMINATION</div>
                <table class="result-table">
                    <tr><td class="field-label">ALBUMIN</td><td class="field-input"><input type="text" name="albumin" class="result-input" value="{{ $findings['albumin'] ?? '' }}" placeholder="Negative"></td></tr>
                    <tr><td class="field-label">SUGAR</td><td class="field-input"><input type="text" name="sugar" class="result-input" value="{{ $findings['sugar'] ?? '' }}" placeholder="Negative"></td></tr>
                    <tr><td class="field-label">BLOOD</td><td class="field-input"><input type="text" name="blood" class="result-input" value="{{ $findings['blood'] ?? '' }}" placeholder="Negative"></td></tr>
                </table>
            </div>
        </div>
        <div class="result-section" style="margin-top:0; border-top:none; border-radius:0 0 8px 8px;">
            <div class="section-header">MICROSCOPIC EXAMINATION</div>
            <table class="result-table">
                <tr><td class="field-label">PUS CELLS</td><td class="field-unit">/HPF</td><td class="field-input"><input type="text" name="pus_cells" class="result-input" value="{{ $findings['pus_cells'] ?? '' }}" placeholder="0-2"></td></tr>
                <tr><td class="field-label">RED BLOOD CELLS</td><td class="field-unit">/HPF</td><td class="field-input"><input type="text" name="rbc" class="result-input" value="{{ $findings['rbc'] ?? '' }}" placeholder="0-1"></td></tr>
                <tr><td class="field-label">EPITHELIAL CELLS</td><td class="field-unit">/HPF</td><td class="field-input"><input type="text" name="epithelial" class="result-input" value="{{ $findings['epithelial'] ?? '' }}" placeholder="Few"></td></tr>
                <tr><td class="field-label">MUCUS THREADS</td><td class="field-unit"></td><td class="field-input"><input type="text" name="mucus" class="result-input" value="{{ $findings['mucus'] ?? '' }}" placeholder="None"></td></tr>
                <tr><td class="field-label">BACTERIA</td><td class="field-unit"></td><td class="field-input"><input type="text" name="bacteria" class="result-input" value="{{ $findings['bacteria'] ?? '' }}" placeholder="Few"></td></tr>
                <tr><td class="field-label">CASTS</td><td class="field-unit"></td><td class="field-input"><input type="text" name="casts" class="result-input" value="{{ $findings['casts'] ?? '' }}" placeholder="None seen"></td></tr>
                <tr><td class="field-label">CRYSTALS</td><td class="field-unit"></td><td class="field-input"><input type="text" name="crystals" class="result-input" value="{{ $findings['crystals'] ?? '' }}" placeholder="None seen"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             CBC
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['complete blood count', 'cbc']))
        <div class="section-title">CBC RESULT</div>
        <div class="result-section">
            <table class="result-table wide">
                <tr>
                    <td class="field-label"></td>
                    <td class="field-input" style="font-weight:700; padding-left:12px;">RESULT</td>
                    <td class="field-ref" style="font-weight:700;">NORMAL</td>
                </tr>
                <tr>
                    <td class="field-label">Hemoglobin</td>
                    <td class="field-input"><input type="text" name="hemoglobin" class="result-input" value="{{ $findings['hemoglobin'] ?? '' }}"></td>
                    <td class="field-ref">F: 11.7 – 14.5 g/dl<br>M: 13.7 – 16.7 g/dl</td>
                </tr>
                <tr>
                    <td class="field-label">Hematocrit</td>
                    <td class="field-input"><input type="text" name="hematocrit" class="result-input" value="{{ $findings['hematocrit'] ?? '' }}"></td>
                    <td class="field-ref">F: 34.1 – 44.3 vols%<br>M: 40.5 – 42.7 vols%</td>
                </tr>
                <tr>
                    <td class="field-label">WBC Count</td>
                    <td class="field-input"><input type="text" name="wbc_count" class="result-input" value="{{ $findings['wbc_count'] ?? '' }}"></td>
                    <td class="field-ref">5,000 – 10,000 cells/mm³</td>
                </tr>
            </table>
        </div>

        <div class="section-title">DIFFERENTIAL COUNT</div>
        <div class="result-section">
            <table class="result-table wide">
                <tr>
                    <td class="field-label"></td>
                    <td class="field-input" style="font-weight:700; padding-left:12px;">RESULT</td>
                    <td class="field-ref" style="font-weight:700;">NORMAL</td>
                </tr>
                @foreach([
                    ['Segmenters','segmenters','45 – 70 %'],
                    ['Lymphocytes','lymphocytes','18 – 45 %'],
                    ['Monocytes','monocytes','4 – 8 %'],
                    ['Eosinophils','eosinophils','2 – 3 %'],
                    ['Basophils','basophils','0 – 0.5 %'],
                    ['Band','band','1 – 2 %'],
                    ['Platelet Count','platelet','F: 174,000 – 390,000<br>M: 144,000 – 372,000'],
                ] as [$label, $name, $ref])
                <tr>
                    <td class="field-label">{{ $label }}</td>
                    <td class="field-input"><input type="text" name="{{ $name }}" class="result-input" value="{{ $findings[$name] ?? '' }}"></td>
                    <td class="field-ref">{!! $ref !!}</td>
                </tr>
                @endforeach
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             BLOOD TYPING
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['blood typing', 'blood type']))
        <div class="section-title">BLOOD TYPING RESULT</div>
        <div class="result-section">
            <div class="section-header">BLOOD GROUP DETERMINATION</div>
            <table class="result-table wide">
                <tr><td class="field-label">BLOOD TYPE</td><td class="field-input"><input type="text" name="blood_type" class="result-input" value="{{ $findings['blood_type'] ?? '' }}" placeholder="e.g. O, A, B, AB"></td></tr>
                <tr><td class="field-label">RH FACTOR</td><td class="field-input"><input type="text" name="rh_factor" class="result-input" value="{{ $findings['rh_factor'] ?? '' }}" placeholder="Positive / Negative"></td></tr>
                <tr><td class="field-label">ANTI-A SERUM</td><td class="field-input"><input type="text" name="anti_a" class="result-input" value="{{ $findings['anti_a'] ?? '' }}" placeholder="Agglutination / No Agglutination"></td></tr>
                <tr><td class="field-label">ANTI-B SERUM</td><td class="field-input"><input type="text" name="anti_b" class="result-input" value="{{ $findings['anti_b'] ?? '' }}" placeholder="Agglutination / No Agglutination"></td></tr>
                <tr><td class="field-label">ANTI-D SERUM</td><td class="field-input"><input type="text" name="anti_d" class="result-input" value="{{ $findings['anti_d'] ?? '' }}" placeholder="Agglutination / No Agglutination"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             PREGNANCY TEST
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['pregnancy test', 'urine pregnancy test', 'upt']))
        <div class="section-title">URINE PREGNANCY TEST RESULT</div>
        <div class="result-section">
            <div class="section-header">TEST RESULT</div>
            <table class="result-table wide">
                <tr><td class="field-label">METHOD</td><td class="field-input"><input type="text" name="method" class="result-input" value="{{ $findings['method'] ?? '' }}" placeholder="Immunochromatographic Assay"></td></tr>
                <tr><td class="field-label">SPECIMEN</td><td class="field-input"><input type="text" name="specimen" class="result-input" value="{{ $findings['specimen'] ?? '' }}" placeholder="Urine"></td></tr>
                <tr><td class="field-label">RESULT</td><td class="field-input"><input type="text" name="result" class="result-input" value="{{ $findings['result'] ?? '' }}" placeholder="Positive / Negative"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             SPUTUM / TB / AFB
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['sputum exam', 'sputum examination', 'afb', 'afb smear', 'tb test', 'sputum microscopy']))
        <div class="section-title">SPUTUM AFB MICROSCOPY RESULT</div>
        <div class="result-section">
            <div class="section-header">MICROSCOPIC EXAMINATION</div>
            <table class="result-table wide">
                <tr><td class="field-label">SPECIMEN QUALITY</td><td class="field-input"><input type="text" name="specimen_quality" class="result-input" value="{{ $findings['specimen_quality'] ?? '' }}" placeholder="Mucopurulent / Salivary"></td></tr>
                <tr><td class="field-label">AFB RESULT</td><td class="field-input"><input type="text" name="afb_result" class="result-input" value="{{ $findings['afb_result'] ?? '' }}" placeholder="Negative / 1+ / 2+ / 3+"></td></tr>
                <tr><td class="field-label">NUMBER OF SPECIMEN</td><td class="field-input"><input type="text" name="specimen_count" class="result-input" value="{{ $findings['specimen_count'] ?? '' }}" placeholder="e.g. 2 specimens"></td></tr>
                <tr><td class="field-label">REMARKS</td><td class="field-input"><input type="text" name="remarks" class="result-input" value="{{ $findings['remarks'] ?? '' }}" placeholder="No AFB seen"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             HEPATITIS B (HBsAg)
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['hepatitis b', 'hbsag', 'hepa b', 'hepatitis b screening']))
        <div class="section-title">HEPATITIS B SCREENING (HBsAg) RESULT</div>
        <div class="result-section">
            <div class="section-header">TEST RESULT</div>
            <table class="result-table wide">
                <tr><td class="field-label">METHOD</td><td class="field-input"><input type="text" name="method" class="result-input" value="{{ $findings['method'] ?? '' }}" placeholder="Rapid Test / ELISA"></td></tr>
                <tr><td class="field-label">SPECIMEN</td><td class="field-input"><input type="text" name="specimen" class="result-input" value="{{ $findings['specimen'] ?? '' }}" placeholder="Whole Blood / Serum"></td></tr>
                <tr><td class="field-label">HBsAg RESULT</td><td class="field-input"><input type="text" name="result" class="result-input" value="{{ $findings['result'] ?? '' }}" placeholder="Negative / Positive (Non-Reactive / Reactive)"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             DENGUE (NS1 / IgG / IgM)
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['dengue test', 'dengue ns1', 'dengue duo', 'dengue']))
        <div class="section-title">DENGUE NS1/IgG/IgM RAPID TEST RESULT</div>
        <div class="result-section">
            <div class="section-header">TEST RESULT</div>
            <table class="result-table wide">
                <tr><td class="field-label">NS1 ANTIGEN</td><td class="field-input"><input type="text" name="ns1" class="result-input" value="{{ $findings['ns1'] ?? '' }}" placeholder="Positive / Negative"></td></tr>
                <tr><td class="field-label">IgG ANTIBODY</td><td class="field-input"><input type="text" name="igg" class="result-input" value="{{ $findings['igg'] ?? '' }}" placeholder="Positive / Negative"></td></tr>
                <tr><td class="field-label">IgM ANTIBODY</td><td class="field-input"><input type="text" name="igm" class="result-input" value="{{ $findings['igm'] ?? '' }}" placeholder="Positive / Negative"></td></tr>
                <tr><td class="field-label">INTERPRETATION</td><td class="field-input"><input type="text" name="interpretation" class="result-input" value="{{ $findings['interpretation'] ?? '' }}" placeholder="e.g. Suggestive of acute infection"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             FBS / BLOOD SUGAR / GLUCOSE
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['fbs', 'fasting blood sugar', 'blood sugar', 'glucose', 'random blood sugar', 'rbs']))
        <div class="section-title">BLOOD GLUCOSE TEST RESULT</div>
        <div class="result-section">
            <div class="section-header">CHEMISTRY</div>
            <table class="result-table wide">
                <tr><td class="field-label">TEST TYPE</td><td class="field-input"><input type="text" name="test_type" class="result-input" value="{{ $findings['test_type'] ?? '' }}" placeholder="Fasting / Random"></td></tr>
                <tr><td class="field-label">GLUCOSE LEVEL</td><td class="field-unit">mg/dL</td><td class="field-input"><input type="text" name="glucose_level" class="result-input" value="{{ $findings['glucose_level'] ?? '' }}" placeholder="e.g. 95"></td></tr>
                <tr><td class="field-label">REFERENCE RANGE</td><td class="field-input"><input type="text" name="reference_range" class="result-input" value="{{ $findings['reference_range'] ?? '70-110 mg/dL (Fasting)' }}"></td></tr>
                <tr><td class="field-label">INTERPRETATION</td><td class="field-input"><input type="text" name="interpretation" class="result-input" value="{{ $findings['interpretation'] ?? '' }}" placeholder="Normal / High / Low"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             LIPID PROFILE
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['lipid profile', 'lipid panel', 'cholesterol']))
        <div class="section-title">LIPID PROFILE RESULT</div>
        <div class="result-section">
            <div class="section-header">CHEMISTRY</div>
            <table class="result-table wide">
                @foreach([['Total Cholesterol','total_cholesterol','mg/dL','<200'],['Triglycerides','triglycerides','mg/dL','<150'],['HDL Cholesterol','hdl','mg/dL','>40'],['LDL Cholesterol','ldl','mg/dL','<100'],['VLDL Cholesterol','vldl','mg/dL','5-40']] as [$label, $name, $unit, $ref])
                <tr><td class="field-label">{{ $label }}</td><td class="field-unit">{{ $unit }}</td><td class="field-input"><input type="text" name="{{ $name }}" class="result-input" value="{{ $findings[$name] ?? '' }}"></td><td class="field-ref">{{ $ref }}</td></tr>
                @endforeach
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             BLOOD CHEMISTRY (BUN, Creatinine, Uric Acid)
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['blood chemistry', 'bun', 'creatinine', 'uric acid', 'kidney function test', 'renal function test']))
        <div class="section-title">BLOOD CHEMISTRY RESULT</div>
        <div class="result-section">
            <div class="section-header">CHEMISTRY</div>
            <table class="result-table wide">
                @foreach([['BUN','bun','mg/dL','7-20'],['Creatinine','creatinine','mg/dL','0.6-1.3'],['Uric Acid','uric_acid','mg/dL','M: 3.4-7.0 / F: 2.4-6.0'],['SGPT/ALT','sgpt','U/L','0-41'],['SGOT/AST','sgot','U/L','0-40']] as [$label, $name, $unit, $ref])
                <tr><td class="field-label">{{ $label }}</td><td class="field-unit">{{ $unit }}</td><td class="field-input"><input type="text" name="{{ $name }}" class="result-input" value="{{ $findings[$name] ?? '' }}"></td><td class="field-ref">{{ $ref }}</td></tr>
                @endforeach
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             VAGINAL SMEAR / PAP SMEAR
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['pap smear', 'vaginal smear', 'pap test']))
        <div class="section-title">PAP SMEAR RESULT</div>
        <div class="result-section">
            <div class="section-header">CYTOLOGY FINDINGS</div>
            <table class="result-table wide">
                <tr><td class="field-label">SPECIMEN ADEQUACY</td><td class="field-input"><input type="text" name="adequacy" class="result-input" value="{{ $findings['adequacy'] ?? '' }}" placeholder="Satisfactory for Evaluation"></td></tr>
                <tr><td class="field-label">EPITHELIAL CELLS</td><td class="field-input"><input type="text" name="epithelial_cells" class="result-input" value="{{ $findings['epithelial_cells'] ?? '' }}" placeholder="Within normal limits"></td></tr>
                <tr><td class="field-label">ORGANISMS</td><td class="field-input"><input type="text" name="organisms" class="result-input" value="{{ $findings['organisms'] ?? '' }}" placeholder="None seen"></td></tr>
                <tr><td class="field-label">INTERPRETATION</td><td class="field-input"><input type="text" name="interpretation" class="result-input" value="{{ $findings['interpretation'] ?? '' }}" placeholder="Negative for Intraepithelial Lesion"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             HIV TEST
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['hiv test', 'hiv screening', 'hiv']))
        <div class="section-title">HIV SCREENING TEST RESULT</div>
        <div class="result-section">
            <div class="section-header">TEST RESULT</div>
            <table class="result-table wide">
                <tr><td class="field-label">METHOD</td><td class="field-input"><input type="text" name="method" class="result-input" value="{{ $findings['method'] ?? '' }}" placeholder="Rapid Test / ELISA"></td></tr>
                <tr><td class="field-label">SPECIMEN</td><td class="field-input"><input type="text" name="specimen" class="result-input" value="{{ $findings['specimen'] ?? '' }}" placeholder="Whole Blood / Serum"></td></tr>
                <tr><td class="field-label">RESULT</td><td class="field-input"><input type="text" name="result" class="result-input" value="{{ $findings['result'] ?? '' }}" placeholder="Non-Reactive / Reactive"></td></tr>
            </table>
        </div>

        {{-- ════════════════════════════════════════════════════
             SEMEN ANALYSIS
        ════════════════════════════════════════════════════ --}}
        @elseif(in_array($serviceKey, ['semen analysis', 'sperm analysis']))
        <div class="section-title">SEMEN ANALYSIS RESULT</div>
        <div class="two-col-sections connected">
            <div class="result-section">
                <div class="section-header">PHYSICAL EXAMINATION</div>
                <table class="result-table">
                    <tr><td class="field-label">VOLUME</td><td class="field-unit">mL</td><td class="field-input"><input type="text" name="volume" class="result-input" value="{{ $findings['volume'] ?? '' }}" placeholder="2-5"></td></tr>
                    <tr><td class="field-label">COLOR</td><td class="field-unit"></td><td class="field-input"><input type="text" name="color" class="result-input" value="{{ $findings['color'] ?? '' }}" placeholder="Grayish White"></td></tr>
                    <tr><td class="field-label">VISCOSITY</td><td class="field-unit"></td><td class="field-input"><input type="text" name="viscosity" class="result-input" value="{{ $findings['viscosity'] ?? '' }}" placeholder="Normal"></td></tr>
                    <tr><td class="field-label">LIQUEFACTION TIME</td><td class="field-unit">min</td><td class="field-input"><input type="text" name="liquefaction" class="result-input" value="{{ $findings['liquefaction'] ?? '' }}" placeholder="20-30"></td></tr>
                </table>
            </div>
            <div class="result-section">
                <div class="section-header">MICROSCOPIC EXAMINATION</div>
                <table class="result-table">
                    <tr><td class="field-label">SPERM COUNT</td><td class="field-unit">M/mL</td><td class="field-input"><input type="text" name="sperm_count" class="result-input" value="{{ $findings['sperm_count'] ?? '' }}" placeholder=">15"></td></tr>
                    <tr><td class="field-label">MOTILITY</td><td class="field-unit">%</td><td class="field-input"><input type="text" name="motility" class="result-input" value="{{ $findings['motility'] ?? '' }}" placeholder=">40"></td></tr>
                    <tr><td class="field-label">MORPHOLOGY</td><td class="field-unit">%</td><td class="field-input"><input type="text" name="morphology" class="result-input" value="{{ $findings['morphology'] ?? '' }}" placeholder=">4"></td></tr>
                </table>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════
             DEFAULT / FALLBACK — STILL STRUCTURED, NOT A TEXTAREA
        ════════════════════════════════════════════════════ --}}
        @else
        <div class="section-title">{{ strtoupper($record->service_name ?? 'LABORATORY') }} RESULT</div>
        <div class="result-section">
            <div class="section-header">TEST RESULT</div>
            <table class="result-table wide">
                <tr><td class="field-label">SPECIMEN</td><td class="field-input"><input type="text" name="specimen" class="result-input" value="{{ $findings['specimen'] ?? '' }}" placeholder="e.g. Blood / Urine / Stool"></td></tr>
                <tr><td class="field-label">METHOD</td><td class="field-input"><input type="text" name="method" class="result-input" value="{{ $findings['method'] ?? '' }}" placeholder="e.g. Rapid Test"></td></tr>
                <tr><td class="field-label">RESULT</td><td class="field-input"><input type="text" name="result" class="result-input" value="{{ $findings['result'] ?? '' }}" placeholder="Enter result"></td></tr>
                <tr><td class="field-label">REFERENCE RANGE</td><td class="field-input"><input type="text" name="reference_range" class="result-input" value="{{ $findings['reference_range'] ?? '' }}" placeholder="Normal range"></td></tr>
                <tr><td class="field-label">REMARKS</td><td class="field-input"><input type="text" name="remarks" class="result-input" value="{{ $findings['remarks'] ?? '' }}" placeholder="Additional remarks"></td></tr>
            </table>
        </div>
        @endif

        {{-- SIGNATORIES --}}
        <div class="signatories">
            <div class="signatory">
                {{-- <div class="sig-line"></div> --}}
                <div class="sig-name">GLEZEAL J. MACALISANG, RMT / GLENN D. ARACENA, RMT / AIRA COLEEN NUSING, RMT</div>
                <div class="sig-title">LIC NO. 0108927 / LIC. NO. 0045183 / LIC NO. 069678</div>
                <div class="sig-role">Medical Technologist</div>
            </div>
            <div class="signatory">
                {{-- <div class="sig-line"></div> --}}
                <div class="sig-name">RAMON M. NERY, M.D.</div>
                <div class="sig-title">LIC. NO. 52586</div>
                <div class="sig-role">Pathologist</div>
            </div>
        </div>

    </div>{{-- end result-card --}}

</div>{{-- end lab-wrap --}}

{{-- Hidden fields for JS --}}
<input type="hidden" id="recordId" value="{{ $record->id }}">
<input type="hidden" id="serviceType" value="{{ $serviceCategory }}">

<style>
/* ── LAYOUT ── */
.lab-wrap {
    font-family: -apple-system, 'Segoe UI', sans-serif;
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 0 48px;
}

/* ── ACTION BAR ── */
.action-bar { display: flex; align-items: center; justify-content: space-between; padding: 16px 0 20px; }
.action-right { display: flex; gap: 10px; }
.btn-back {
    display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 6px;
    border: 1px solid #D9D9D9; background: #fff; color: #374151; font-size: 14px; font-weight: 500;
    text-decoration: none; cursor: pointer; transition: all .2s;
}
.btn-back:hover { border-color: #6b7280; background: #f9fafb; color: #111; }
.btn-save {
    display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; border-radius: 6px;
    border: none; background: #16a34a; color: #fff; font-size: 14px; font-weight: 600; cursor: pointer; transition: background .2s;
}
.btn-save:hover { background: #15803d; }
.btn-print {
    display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 6px;
    border: 1px solid #D9D9D9; background: #fff; color: #374151; font-size: 14px; font-weight: 500; cursor: pointer; transition: all .2s;
}
.btn-print:hover { border-color: #6b7280; background: #f9fafb; }

/* ── RESULT CARD ── */
.result-card {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 40px 48px; box-shadow: 0 1px 3px rgba(0,0,0,.06);
}

/* ── CLINIC HEADER ── */
.clinic-header { display: flex; align-items: center; gap: 4px; margin-bottom: 0; }
.clinic-logo { flex-shrink: 0; margin-right: 2px; }
.logo-img { width: 68px; height: 68px; border-radius: 8px; object-fit: contain; display: block; }
.clinic-info { text-align: center; flex: 1; }
.clinic-gov { font-size: 12px; color: #6b7280; }
.clinic-province { font-size: 12px; color: #6b7280; }
.clinic-name { font-size: 17px; font-weight: 800; color: #111827; margin: 3px 0; }
.clinic-address { font-size: 12px; color: #6b7280; }
.clinic-dept { font-size: 14px; font-weight: 700; color: #1a6b3c; margin-top: 4px; letter-spacing: .5px; }
.header-divider { border: none; border-top: 2px solid #1a6b3c; margin: 14px 0; }

/* ── PATIENT INFO ── */
.patient-info-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px 32px; margin-bottom: 0; }
.info-field { display: flex; gap: 10px; align-items: baseline; }
.info-field-full { grid-column: 1 / -1; }
.info-label { font-size: 13px; font-weight: 600; color: #6b7280; white-space: nowrap; min-width: 65px; }
.info-value { font-size: 14px; font-weight: 500; color: #111827; flex: 1; padding-bottom: 2px; }

/* ── SECTION TITLE ── */
.section-title { font-size: 15px; font-weight: 800; color: #1a6b3c; text-align: center; letter-spacing: 1px; margin-bottom: 16px; margin-top: 18px; text-transform: uppercase; }

/* ── TWO COLUMN ── */
.two-col-sections { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
.two-col-sections.connected .result-section { border-radius: 0; margin: 0; }
.two-col-sections.connected .result-section:first-child { border-right: none; border-radius: 8px 0 0 8px; }
.two-col-sections.connected .result-section:last-child { border-radius: 0 8px 8px 0; }

/* ── RESULT SECTION ── */
.result-section { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
.section-header { background: #f0fdf4; border-bottom: 1px solid #d1fae5; padding: 10px 16px; font-size: 12px; font-weight: 700; color: #1a6b3c; letter-spacing: .8px; text-transform: uppercase; }

/* ── RESULT TABLE ── */
.result-table { width: 100%; border-collapse: collapse; }
.result-table tr { border-bottom: 1px solid #f3f4f6; }
.result-table tr:last-child { border-bottom: none; }
.field-label { padding: 10px 16px; font-size: 13px; font-weight: 600; color: #374151; white-space: nowrap; width: 42%; }
.field-unit { font-size: 12px; color: #9ca3af; white-space: nowrap; padding: 0 6px; width: 14%; }
.field-ref { font-size: 12px; color: #9ca3af; padding: 0 16px 0 6px; white-space: nowrap; }
.field-input { padding: 7px 12px 7px 0; }
.result-input { width: 100%; border: 1px solid #e5e7eb; border-radius: 5px; padding: 8px 12px; font-size: 14px; color: #111827; background: #fafafa; transition: border-color .15s, background .15s; font-family: inherit; }
.result-input:focus { outline: none; border-color: #16a34a; background: #fff; box-shadow: 0 0 0 2px rgba(22,163,74,.12); }
.result-table.wide .field-label { width: 34%; }

/* ── SIGNATORIES ── */
.signatories { display: flex; justify-content: space-between; gap: 28px; margin-top: 40px; padding-top: 16px; }
.signatory { flex: 1; text-align: center; }
.sig-line { border-top: 1.5px solid #374151; margin-bottom: 8px; }
.sig-name { font-size: 12px; font-weight: 700; color: #111827; }
.sig-title { font-size: 11px; color: #6b7280; }
.sig-role { font-size: 12px; font-weight: 600; color: #374151; margin-top: 4px; }

/* ── PRINT ── */
@media print {
    @page { size: A4; margin: 12mm; }

    html, body, .main-content, .content-wrapper, .page-content {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    body * {
        visibility: hidden !important;
    }
    
    #printArea, #printArea * {
        visibility: visible !important;
    }
    
    #printArea {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #fff !important;
    }
    
    .no-print, .action-bar, .btn-back, .btn-save, .btn-print, 
    nav, header, footer, .sidebar, .navbar, .topbar, .main-header,
    .main-sidebar, .main-footer, .content-header {
        display: none !important;
    }

    #printArea .clinic-header { display: flex !important; }
    #printArea .patient-info-grid { display: grid !important; grid-template-columns: 1fr 1fr 1fr !important; }
    #printArea .info-field { display: flex !important; }
    #printArea .info-field-full { grid-column: 1 / -1 !important; }
    #printArea .signatories { display: flex !important; flex-direction: row !important; }
    #printArea .two-col-sections { display: grid !important; grid-template-columns: 1fr 1fr !important; }
    #printArea table { display: table !important; width: 100% !important; }
    #printArea thead { display: table-header-group !important; }
    #printArea tbody { display: table-row-group !important; }
    #printArea tr { display: table-row !important; }
    #printArea td, #printArea th { display: table-cell !important; }
    #printArea input { display: block !important; }

    .clinic-header { gap: 4px !important; margin-bottom: 8px !important; }
    .logo-img { width: 50px !important; height: 50px !important; }
    .clinic-name { font-size: 14px !important; color: #000 !important; }
    .clinic-dept { font-size: 12px !important; color: #000 !important; }
    .clinic-gov, .clinic-province, .clinic-address { font-size: 10px !important; color: #444 !important; }
    .header-divider { border-top: 2px solid #000 !important; margin: 8px 0 !important; }
    .patient-info-grid { gap: 6px 16px !important; margin-bottom: 8px !important; }
    .info-label { font-size: 11px !important; color: #000 !important; }
    .info-value { font-size: 12px !important; color: #000 !important; }
    .section-title { font-size: 13px !important; color: #000 !important; margin: 10px 0 8px !important; }
    .result-section { border: 1px solid #000 !important; border-radius: 0 !important; }
    .section-header { background: #e8e8e8 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color: #000 !important; border-bottom: 1px solid #000 !important; }
    .two-col-sections.connected .result-section:first-child { border-right: none !important; }
    .two-col-sections.connected .result-section { border-radius: 0 !important; }
    .result-input { border: none !important; border-bottom: 1px solid #000 !important; border-radius: 0 !important; background: transparent !important; font-size: 12px !important; color: #000 !important; box-shadow: none !important; padding: 2px 4px !important; }
    .field-label { font-size: 11px !important; color: #000 !important; padding: 6px 10px !important; }
    .field-unit, .field-ref { font-size: 10px !important; color: #444 !important; }
    .signatories { gap: 28px !important; margin-top: 32px !important; }
    .sig-line { border-top: 1.5px solid #000 !important; }
    .sig-name { font-size: 10px !important; color: #000 !important; }
    .sig-title { font-size: 9px !important; color: #444 !important; }
    .sig-role { font-size: 10px !important; color: #000 !important; }
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .patient-info-grid { grid-template-columns: 1fr 1fr; }
    .info-field-full { grid-column: 1 / -1; }
}
@media (max-width: 640px) {
    .patient-info-grid { grid-template-columns: 1fr; }
    .info-field-full { grid-column: 1; }
    .two-col-sections { grid-template-columns: 1fr; }
    .two-col-sections.connected .result-section:first-child { border-right: 1px solid #e5e7eb; border-radius: 8px 8px 0 0; }
    .two-col-sections.connected .result-section:last-child { border-radius: 0 0 8px 8px; }
    .result-card { padding: 24px 18px; }
    .signatories { flex-direction: column; }
}
</style>

<script>
function printResult() {
    window.print();
}

function saveResult() {
    const recordId = document.getElementById('recordId').value;
    const serviceType = document.getElementById('serviceType').value;
    const findings = {};
    document.querySelectorAll('.result-input').forEach(el => { if (el.name) findings[el.name] = el.value; });
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Saving...';
    fetch("{{ route('staff1.api.result.save', $record->id) }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ service_type: serviceType, findings: JSON.stringify(findings), doctor_remarks: '' })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) { 
            btn.innerHTML = '✓ Saved!'; 
            btn.style.background = '#15803d'; 
            setTimeout(() => { 
                window.location.href = "{{ route('staff1.laboratory') }}"; 
            }, 1000);
        } else { 
            alert('Failed: ' + (d.message || 'Unknown error')); 
            btn.disabled = false; 
            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Result'; 
            btn.style.background = '#16a34a';
        }
    })
    .catch(e => { 
        alert('Error: ' + e.message); 
        btn.disabled = false; 
        btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Result'; 
        btn.style.background = '#16a34a';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    @if($record->findings)
    try {
        const saved = @json(is_string($record->findings) ? json_decode($record->findings, true) : $record->findings);
        if (saved && typeof saved === 'object') {
            Object.entries(saved).forEach(([key, val]) => {
                const el = document.querySelector(`[name="${key}"]`);
                if (el) el.value = val;
            });
        }
    } catch(e) {}
    @endif
});
</script>

@endsection