@extends('layouts.app')

@section('content')
<div class="container-fluid ultrasound-result-container">

    <!-- Top Bar -->
    <div class="mb-3 ultrasound-topbar no-print">
        <a href="{{ url()->previous() }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="ultrasound-topbar-icon">
            <i class="fas fa-stethoscope"></i>
        </div>
        <span class="ultrasound-topbar-title">ULTRASOUND RESULT</span>

        <div class="ultrasound-topbar-actions">
            <button type="button" class="btn-topbar-save" id="saveTopbarBtn" onclick="saveAndSubmit(event)">
                <i class="fas fa-save"></i> Save
            </button>
        </div>
    </div>

    <div class="ultrasound-result-card" id="printArea">

        <!-- Patient Info -->
        <div class="patient-info-card">
            <div class="patient-info-top">
                <div class="patient-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="patient-name-block">
                    <h4 class="patient-name">{{ $record->patient->first_name ?? 'N/A' }} {{ $record->patient->last_name ?? '' }}</h4>

                    <!-- Row 1 -->
                    <div class="patient-info-grid">
                        <div class="info-item">
                            <span class="info-label">Patient ID</span>
                            <span class="info-value">
                                {{ isset($record->patient->patient_id)
                                    ? $record->patient->patient_id
                                    : (isset($record->patient->id)
                                        ? date('Y') . '-' . str_pad($record->patient->id, 3, '0', STR_PAD_LEFT)
                                        : 'N/A') }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date of Birth</span>
                            <span class="info-value">
                                {{ $record->patient->date_of_birth ? \Carbon\Carbon::parse($record->patient->date_of_birth)->format('M d, Y') : 'N/A' }}
                                @if($record->patient->date_of_birth)
                                    ({{ \Carbon\Carbon::parse($record->patient->date_of_birth)->age }} y/o)
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value">{{ $record->patient->gender ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Exam Date</span>
                            <span class="info-value">
                                {{ $record->exam_date ? \Carbon\Carbon::parse($record->exam_date)->format('M d, Y') : 'N/A' }}
                                @if($record->exam_time)
                                    | {{ \Carbon\Carbon::parse($record->exam_time)->format('h:i A') }}
                                @endif
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Horizontal Divider - Full Width Edge-to-Edge -->
            <div class="patient-info-hr"></div>

            <!-- Row 2 -->
            <div class="patient-info-bottom">
                <div class="patient-info-grid">
                    <div class="info-item">
                        <span class="info-label">Address</span>
                        <span class="info-value">{{ $record->patient->address ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Requesting Physician</span>
                        <span class="info-value">{{ $record->physician_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department</span>
                        <span class="info-value">{{ $record->department ?? 'Ultrasound' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Accession No.</span>
                        <span class="info-value">{{ $record->accession_no ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image + Diagnosis Drawer -->
        <div class="ultrasound-main-flex">

            <!-- Ultrasound Image (shrinks when the diagnosis drawer opens) -->
            <div class="ultrasound-panel-wrapper">
                <div class="ultrasound-panel">
                    <div class="panel-header">
                        <span class="panel-title">ULTRASOUND IMAGE</span>
                        <div class="panel-header-actions no-print">
                            <label for="ultrasoundImageInput" class="btn-upload">
                                <i class="fas fa-cloud-upload-alt"></i> Upload Image
                            </label>
                            <button type="button" class="btn-diagnosis" id="diagnosisToggleBtn" onclick="toggleDiagnosisDrawer()">
                                <i class="fas fa-stethoscope"></i> Diagnosis
                            </button>
                        </div>
                    </div>

                    <!-- Hidden file input -->
                    <input type="file" id="ultrasoundImageInput" accept="image/*" style="display: none;" onchange="previewUltrasoundImage(event)">

                    <div class="ultrasound-image-wrapper" id="ultrasoundImageWrapper" onclick="openUltrasoundLightbox()">
                        @if($record->xray_image ?? false)
                            <img src="{{ asset('storage/' . $record->xray_image) }}" alt="Ultrasound Image" id="ultrasoundImagePreview" class="ultrasound-image">
                        @else
                            <div class="ultrasound-image-placeholder" id="ultrasoundImagePreviewPlaceholder">
                                <i class="fas fa-image"></i>
                                <span>No image uploaded</span>
                            </div>
                            <img src="" alt="Ultrasound Image" id="ultrasoundImagePreview" class="ultrasound-image d-none" style="display:none;">
                        @endif
                    </div>

                    <div class="ultrasound-image-footer no-print">
                        <div class="upload-status" id="uploadStatus">
                            @if($record->xray_image ?? false)
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Image uploaded</span>
                                <small>{{ $record->updated_at ? $record->updated_at->format('M d, Y | h:i A') : '' }}</small>
                            @endif
                        </div>
                        {{-- Remove button ONLY shown when image exists --}}
                        @if($record->xray_image ?? false)
                        <button type="button" class="btn-remove" id="removeImageBtn" onclick="showRemoveModal()">
                            <i class="fas fa-trash-alt"></i> Remove Image
                        </button>
                        @else
                        <button type="button" class="btn-remove" id="removeImageBtn" style="display:none;" onclick="showRemoveModal()">
                            <i class="fas fa-trash-alt"></i> Remove Image
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Diagnosis Drawer: slides in from the right -->
            <div class="diagnosis-drawer no-print" id="diagnosisDrawer">
                <div class="diagnosis-drawer-inner">

                    <div class="diagnosis-drawer-header">
                        <span class="diagnosis-drawer-title"><i class="fas fa-notes-medical"></i> Medical Report</span>
                        <button type="button" class="btn-drawer-close" onclick="toggleDiagnosisDrawer()" title="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- FINDINGS CARD -->
                    <div class="diag-card">
                        <div class="diag-card-header">
                            <div class="diag-card-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="diag-card-heading">
                                <span class="diag-card-title">Findings</span>
                                <span class="diag-card-badge" id="findingsCount">0</span>
                            </div>
                        </div>
                        <div class="diag-card-body">
                            <textarea
                                class="findings-textarea no-print"
                                id="findingsInput"
                                name="findings"
                                placeholder="Type findings here, one per line...">{{ $record->findings ?? '' }}</textarea>
                            <ul class="findings-list-print">
                                @forelse(explode("\n", $record->findings ?? '') as $finding)
                                    @if(trim($finding) !== '')
                                        <li>{{ trim($finding) }}</li>
                                    @endif
                                @empty
                                    <li>No findings recorded.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- IMPRESSION CARD -->
                    <div class="diag-card">
                        <div class="diag-card-header">
                            <div class="diag-card-icon">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="diag-card-heading">
                                <span class="diag-card-title">Impression</span>
                            </div>
                        </div>
                        <div class="diag-card-body">
                            <textarea
                                class="impression-textarea no-print"
                                id="impressionInput"
                                name="impression"
                                placeholder="Type impression here...">{{ $record->impression ?? '' }}</textarea>
                            <p class="impression-text-print">{{ $record->impression ?? 'No impression recorded.' }}</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="confidential-footer">
            <i class="fas fa-shield-alt"></i>
            This information is confidential and intended for authorized personnel only.
        </div>

    </div>
</div>

<!-- Validation/Success Modal -->
<div class="modal-overlay" id="messageModal">
    <div class="modal-confirm-box">
        <div class="modal-confirm-icon" id="messageIcon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <h3 id="messageTitle">Alert</h3>
        <p id="messageContent">Message here</p>
        <div class="modal-confirm-actions">
            <button class="btn-confirm-cancel" onclick="closeMessageModal()">OK</button>
        </div>
    </div>
</div>

<!-- Remove Image Confirmation Modal -->
<div class="modal-overlay" id="removeModal">
    <div class="modal-confirm-box">
        <div class="modal-confirm-icon warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 4 19 4 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>
        <h3>Remove Ultrasound Image?</h3>
        <p>This action cannot be undone. The image will be permanently deleted.</p>
        <div class="modal-confirm-actions">
            <button class="btn-confirm-cancel" onclick="closeRemoveModal()">Cancel</button>
            <button class="btn-confirm-danger" onclick="confirmRemoveImage()">Yes, Remove</button>
        </div>
    </div>
</div>

<!-- Fullscreen Lightbox -->
<div class="ultrasound-lightbox" id="ultrasoundLightbox" onclick="closeUltrasoundLightbox(event)">
    <button class="ultrasound-lightbox-close" onclick="closeUltrasoundLightbox(event)" title="Close">
        <i class="fas fa-times"></i>
    </button>
    <img id="ultrasoundLightboxImage" class="ultrasound-lightbox-image" alt="Ultrasound Full View" onclick="event.stopPropagation()">
</div>

<style>
    :root {
        --ultrasound-bg: #F0F4F8;
        --ultrasound-card-bg: #FFFFFF;
        --ultrasound-border: #E2E6EC;
        --ultrasound-blue: #0066CC;
        --ultrasound-blue-dark: #004A99;
        --ultrasound-blue-soft: #E6F0FB;
        --ultrasound-text-dark: #1F2937;
        --ultrasound-text-muted: #6B7280;
        --ultrasound-red: #E2433B;
        --ultrasound-green: #22C55E;
        --ultrasound-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
    }

    .ultrasound-result-container {
        padding: 24px 0;
        background-color: var(--ultrasound-bg);
    }

    /* ============ TOP BAR ============ */
    .ultrasound-topbar {
        display: flex;
        align-items: center;
        gap: 14px;
        background-color: var(--ultrasound-card-bg);
        border: 1px solid var(--ultrasound-border);
        border-radius: 10px;
        padding: 16px 20px;
        box-shadow: var(--ultrasound-shadow);
    }

    .back-link {
        color: var(--ultrasound-blue);
        font-size: 16px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .ultrasound-topbar-icon {
        width: 32px;
        height: 32px;
        background-color: var(--ultrasound-blue);
        color: #fff;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .ultrasound-topbar-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--ultrasound-text-dark);
        letter-spacing: 0.3px;
    }

    .ultrasound-topbar-actions {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-topbar-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 38px;
        padding: 0 18px;
        border-radius: 8px;
        background-color: var(--ultrasound-green);
        border: 1px solid var(--ultrasound-green);
        color: #fff;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-topbar-save:hover {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    .btn-topbar-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* ============ MAIN CARD ============ */
    .ultrasound-result-card {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ============ PATIENT INFO ============ */
    .patient-info-card {
        background-color: var(--ultrasound-card-bg);
        border: 1px solid var(--ultrasound-border);
        border-radius: 10px;
        padding: 24px;
        box-shadow: var(--ultrasound-shadow);
    }

    .patient-info-top {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .patient-avatar {
        width: 72px;
        height: 72px;
        min-width: 72px;
        border-radius: 50%;
        background-color: #DCE3F7;
        color: #7B8CC9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        flex-shrink: 0;
        align-self: flex-start;
    }

    .patient-name-block {
        flex: 1;
        min-width: 0;
    }

    .patient-name {
        margin: 0 0 16px 0;
        font-size: 20px;
        font-weight: 700;
        color: var(--ultrasound-text-dark);
    }

    .patient-info-hr {
        width: calc(100% + 48px);
        height: 1px;
        background-color: #D1D5DB;
        margin: 16px -24px;
    }

    .patient-info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px 20px;
        margin-bottom: 10px;
    }

    .patient-info-grid:last-child {
        margin-bottom: 0;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--ultrasound-text-muted);
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--ultrasound-text-dark);
    }

    /* ============ IMAGE + DRAWER FLEX LAYOUT ============ */
    .ultrasound-main-flex {
        display: flex;
        align-items: stretch;
        gap: 20px;
    }

    .ultrasound-panel-wrapper {
        flex: 1;
        min-width: 0;
        transition: flex 0.3s ease;
    }

    .ultrasound-panel {
        background-color: var(--ultrasound-card-bg);
        border: 1px solid var(--ultrasound-border);
        border-radius: 10px;
        padding: 18px;
        box-shadow: var(--ultrasound-shadow);
        display: flex;
        flex-direction: column;
    }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
        color: var(--ultrasound-blue);
    }

    .panel-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: auto;
    }

    .panel-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ultrasound-blue);
        letter-spacing: 0.3px;
    }

    .btn-upload {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--ultrasound-blue);
        background-color: #fff;
        border: 1px solid var(--ultrasound-blue);
        border-radius: 6px;
        padding: 7px 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-upload:hover {
        background-color: var(--ultrasound-blue-soft);
    }

    .btn-diagnosis {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 700;
        color: #fff;
        background-color: var(--ultrasound-blue);
        border: 1px solid var(--ultrasound-blue);
        border-radius: 6px;
        padding: 7px 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-diagnosis:hover {
        background-color: var(--ultrasound-blue-dark);
    }

    .btn-diagnosis.active {
        background-color: var(--ultrasound-blue-dark);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
    }

    .ultrasound-image-wrapper {
        width: 100%;
        min-height: 300px;
        background-color: #000;
        border-radius: 6px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: zoom-in;
    }

    .ultrasound-image {
        width: 100%;
        height: auto;
        max-height: 750px;
        display: block;
    }

    .ultrasound-image-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: #9CA3AF;
        font-size: 13px;
        text-align: center;
        padding: 20px;
    }

    .ultrasound-image-placeholder i {
        font-size: 40px;
        color: #6B7280;
    }

    .ultrasound-image-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 14px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .upload-status {
        display: flex;
        flex-direction: column;
        gap: 2px;
        font-size: 12.5px;
        color: var(--ultrasound-green);
    }

    .upload-status small {
        color: var(--ultrasound-text-muted);
    }

    .btn-remove {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--ultrasound-red);
        background-color: #fff;
        border: 1px solid var(--ultrasound-red);
        border-radius: 6px;
        padding: 7px 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-remove:hover {
        background-color: #FDECEC;
    }

    /* ============ DIAGNOSIS DRAWER ============ */
    .diagnosis-drawer {
        width: 0;
        flex-shrink: 0;
        overflow: hidden;
        transition: width 0.3s ease;
        align-self: stretch;
    }

    .diagnosis-drawer.active {
        width: 490px;
    }

    .diagnosis-drawer-inner {
        width: 490px;
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background-color: var(--ultrasound-card-bg);
        border: 1px solid var(--ultrasound-border);
        border-radius: 10px;
        padding: 18px;
        box-shadow: var(--ultrasound-shadow);
        box-sizing: border-box;
    }

    .diagnosis-drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--ultrasound-border);
    }

    .diagnosis-drawer-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 700;
        color: var(--ultrasound-text-dark);
    }

    .btn-drawer-close {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid var(--ultrasound-border);
        background-color: #fff;
        color: var(--ultrasound-text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-drawer-close:hover {
        background-color: #F5F6F8;
        color: var(--ultrasound-text-dark);
    }

    /* Diagnosis cards: split the drawer 50 / 50 */
    .diag-card {
        background-color: #fff;
        border: 1px solid var(--ultrasound-border);
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(31, 41, 55, 0.04);
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
    }

    .diag-card .diag-card-body {
        flex: 1;
        display: flex;
        min-height: 0;
    }

    .diag-card .findings-textarea,
    .diag-card .impression-textarea {
        flex: 1;
        height: 100%;
        min-height: 0;
        resize: none;
    }

    .diag-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .diag-card-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 9px;
        background-color: var(--ultrasound-blue-soft);
        color: var(--ultrasound-blue);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .diag-card-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
    }

    .diag-card-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--ultrasound-text-dark);
    }

    .diag-card-badge {
        margin-left: auto;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 10px;
        background-color: var(--ultrasound-blue);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .diag-card-body {
        background-color: #F8F9FB;
        border: 1px solid #EEF0F3;
        border-radius: 8px;
        padding: 12px;
    }

    .findings-textarea, .impression-textarea {
        width: 100%;
        border: none;
        background-color: transparent;
        font-size: 13.5px;
        font-family: inherit;
        color: var(--ultrasound-text-dark);
        line-height: 1.7;
        padding: 0;
    }

    .findings-textarea:focus, .impression-textarea:focus { outline: none; }

    .findings-list-print {
        display: none; margin: 0; padding-left: 18px;
        flex-direction: column; gap: 10px; font-size: 13.5px; color: var(--ultrasound-text-dark);
    }

    .impression-text-print {
        display: none; margin: 0; font-size: 13.5px; color: var(--ultrasound-text-dark); line-height: 1.6;
    }

    /* ============ CONFIRMATION MODAL ============ */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-confirm-box {
        background: #fff;
        border-radius: 16px;
        padding: 32px 28px 28px;
        max-width: 420px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: modalIn 0.2s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.9) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-confirm-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-confirm-icon.warning {
        background: #fffbeb;
        border: 3px solid #fde68a;
    }

    .modal-confirm-icon.error {
        background: #fee2e2;
        border: 3px solid #fca5a5;
    }

    .modal-confirm-icon.error svg {
        stroke: #dc2626;
    }

    .modal-confirm-icon.success {
        background: #dcfce7;
        border: 3px solid #86efac;
    }

    .modal-confirm-icon.success svg {
        stroke: #22c55e;
    }

    .modal-confirm-icon svg {
        width: 32px;
        height: 32px;
        stroke: #f59e0b;
    }

    .modal-confirm-box h3 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .modal-confirm-box p {
        font-size: 15px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .modal-confirm-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-confirm-cancel {
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
        transition: all 0.15s;
    }

    .btn-confirm-cancel:hover { background: #e2e8f0; }

    .btn-confirm-danger {
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        background: #dc2626;
        color: #fff;
        border: none;
        transition: all 0.15s;
    }

    .btn-confirm-danger:hover { background: #b91c1c; }

    /* ============ FULLSCREEN LIGHTBOX ============ */
    .ultrasound-lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.92);
        z-index: 2147483647;
        align-items: center;
        justify-content: center;
        padding: 40px;
        box-sizing: border-box;
        cursor: zoom-out;
    }

    .ultrasound-lightbox.active { display: flex; }

    .ultrasound-lightbox-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 4px;
        cursor: default;
    }

    .ultrasound-lightbox-close {
        position: absolute;
        top: 24px;
        right: 32px;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ultrasound-lightbox-close:hover { background-color: rgba(255, 255, 255, 0.2); }

    /* ============ FOOTER ============ */
    .confidential-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--ultrasound-text-muted);
        padding: 4px 4px 0 4px;
    }

    /* ============ PRINT ============ */
    @media print {
        body * { visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .notes-print-only { display: block !important; font-size: 13px; color: #000; white-space: pre-wrap; }
        .findings-list-print { display: flex !important; }
        .impression-text-print { display: block !important; }
        .diagnosis-drawer { width: auto !important; }
        .diagnosis-drawer-inner { width: 100% !important; box-shadow: none; border: none; }
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 992px) {
        .patient-info-grid { grid-template-columns: repeat(2, 1fr); }
        .ultrasound-main-flex { flex-direction: column; }
        .diagnosis-drawer, .diagnosis-drawer.active { width: 100%; }
        .diagnosis-drawer-inner { width: 100%; }
        .patient-info-hr { width: calc(100% + 32px); margin: 16px -16px; }
        .ultrasound-image-wrapper { min-height: 260px; }
        .ultrasound-topbar { flex-wrap: wrap; row-gap: 10px; }
    }

    @media (max-width: 576px) {
        .patient-info-card { padding: 16px; }
        .patient-info-top { flex-direction: column; align-items: flex-start; }
        .patient-info-grid { grid-template-columns: 1fr; gap: 12px; }
        .patient-info-hr { width: calc(100% + 32px); margin: 14px -16px; }
        .ultrasound-image-wrapper { min-height: 220px; }
        .panel-header { flex-wrap: wrap; row-gap: 10px; }
        .panel-header-actions { width: 100%; }
    }
</style>

<script>
    function previewUltrasoundImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('ultrasoundImagePreview');
        const placeholder = document.getElementById('ultrasoundImagePreviewPlaceholder');
        const status = document.getElementById('uploadStatus');
        const removeBtn = document.getElementById('removeImageBtn');

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            preview.style.display = 'block';
            if (placeholder) {
                placeholder.classList.add('d-none');
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);

        status.innerHTML = `<i class="fas fa-spinner fa-spin"></i> <span>Uploading...</span>`;

        const formData = new FormData();
        formData.append('xray_image', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch("{{ route('staff1.results.ultrasound.upload', $record->id ?? '') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => { if (!res.ok) throw new Error('Upload failed'); return res.json(); })
        .then(data => {
            if (data.url) preview.src = data.url;
            const now = new Date();
            status.innerHTML = `
                <i class="fas fa-check-circle text-success"></i>
                <span>Image uploaded</span>
                <small>${now.toLocaleDateString()} | ${now.toLocaleTimeString()}</small>
            `;
            if (removeBtn) removeBtn.style.display = 'inline-flex';
        })
        .catch(err => {
            console.error(err);
            status.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i> <span>Upload failed, please try again.</span>`;
        });
    }

    function showRemoveModal() {
        document.getElementById('removeModal').classList.add('active');
    }

    function closeRemoveModal() {
        document.getElementById('removeModal').classList.remove('active');
    }

    function confirmRemoveImage() {
        closeRemoveModal();

        fetch("{{ route('staff1.results.ultrasound.remove', $record->id ?? '') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ _token: '{{ csrf_token() }}' })
        })
        .then(res => res.json())
        .then(() => {
            const preview = document.getElementById('ultrasoundImagePreview');
            const placeholder = document.getElementById('ultrasoundImagePreviewPlaceholder');
            const removeBtn = document.getElementById('removeImageBtn');
            preview.src = '';
            preview.classList.add('d-none');
            preview.style.display = 'none';
            if (placeholder) {
                placeholder.classList.remove('d-none');
                placeholder.style.display = 'flex';
            }
            document.getElementById('uploadStatus').innerHTML = '';
            document.getElementById('ultrasoundImageInput').value = '';
            if (removeBtn) removeBtn.style.display = 'none';
        })
        .catch(err => {
            console.error(err);
            alert('Failed to remove image. Please try again.');
        });
    }

    function showMessageModal(title, message, type = 'error') {
        const modal = document.getElementById('messageModal');
        const icon = document.getElementById('messageIcon');
        const titleEl = document.getElementById('messageTitle');
        const contentEl = document.getElementById('messageContent');

        titleEl.textContent = title;
        contentEl.innerHTML = message.replace(/\n/g, '<br>');

        if (type === 'success') {
            icon.className = 'modal-confirm-icon success';
            icon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        } else if (type === 'error') {
            icon.className = 'modal-confirm-icon error';
            icon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
        } else {
            icon.className = 'modal-confirm-icon warning';
            icon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        }

        modal.classList.add('active');
    }

    function closeMessageModal() {
        document.getElementById('messageModal').classList.remove('active');
    }

    document.getElementById('messageModal').addEventListener('click', function(e) {
        if (e.target === this) closeMessageModal();
    });

    /* ============ Diagnosis Drawer ============ */
    function toggleDiagnosisDrawer() {
        const drawer = document.getElementById('diagnosisDrawer');
        const btn = document.getElementById('diagnosisToggleBtn');
        drawer.classList.toggle('active');
        btn.classList.toggle('active');
    }

    function updateFindingsCount() {
        const findingsInput = document.getElementById('findingsInput');
        const countEl = document.getElementById('findingsCount');
        if (!findingsInput || !countEl) return;
        const lines = findingsInput.value.split('\n').map(l => l.trim()).filter(l => l !== '');
        countEl.textContent = lines.length;
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateFindingsCount();
        const findingsInput = document.getElementById('findingsInput');
        if (findingsInput) findingsInput.addEventListener('input', updateFindingsCount);
    });

    function saveAndSubmit(event) {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;

        const ultrasoundImage = document.getElementById('ultrasoundImagePreview');
        const findings = document.getElementById('findingsInput').value.trim();
        const impression = document.getElementById('impressionInput').value.trim();

        let errors = [];

        if (!ultrasoundImage.src || ultrasoundImage.classList.contains('d-none') || ultrasoundImage.style.display === 'none') {
            errors.push('❌ Ultrasound image is required');
        }

        if (!findings) {
            errors.push('❌ Findings cannot be empty');
        }

        if (!impression) {
            errors.push('❌ Impression cannot be empty');
        }

        if (errors.length > 0) {
            showMessageModal('Validation Error', errors.join('\n'), 'error');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        const data = {
            service_type: 'ultrasound',
            findings: JSON.stringify({
                findings_text: findings,
                impression: impression
            }),
        };

        fetch("{{ route('staff1.api.result.save', $record->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(res => {
            if (!res.ok) throw new Error('Server error: ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Saved!';
                btn.style.backgroundColor = '#10b981';
                showMessageModal('Success!', '✅ Result saved & submitted successfully!', 'success');
                setTimeout(() => {
                    window.location.href = "{{ route('staff1.ultrasound') }}";
                }, 1500);
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(err => {
            console.error('Save error:', err);
            btn.disabled = false;
            btn.innerHTML = originalText;
            showMessageModal('Error', '❌ Failed to save: ' + err.message, 'error');
        });
    }

    function openUltrasoundLightbox() {
        const preview = document.getElementById('ultrasoundImagePreview');
        if (!preview.src || preview.classList.contains('d-none')) return;

        const lightbox = document.getElementById('ultrasoundLightbox');
        if (lightbox.parentElement !== document.body) {
            document.body.appendChild(lightbox);
        }

        const lightboxImg = document.getElementById('ultrasoundLightboxImage');
        lightboxImg.src = preview.src;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeUltrasoundLightbox(event) {
        const lightbox = document.getElementById('ultrasoundLightbox');
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeUltrasoundLightbox();
        }
    });
</script>
@endsection