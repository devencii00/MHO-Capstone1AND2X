@props(['role' => 'admin'])

@php
    $roleKey = strtolower($role ?? 'admin');

    $roleNames = [
        'admin' => 'Admin',
        'doctor' => 'Doctor',
        'receptionist' => 'Receptionist',
        'patient' => 'Patient',
    ];

    $roleLabel = $roleNames[$roleKey] ?? ucfirst($roleKey);

    $currentSection = request()->query('section');
    $currentSection = $currentSection ?: 'overview';
    if ($currentSection === 'medical-background-viewer') {
        $currentSection = 'patient-records';
    }

    // Preserve user_uuid across all sidebar navigation links
    $uuidParam = request()->query('user_uuid');
    $uuidParam = $uuidParam ?: request()->query('user_id');
    function dashUrl($role, $extra = []) {
        $uuid = request()->query('user_uuid') ?: request()->query('user_id');
        $params = array_merge(['role' => $role], $extra);
        if ($uuid) {
            $params['user_uuid'] = $uuid;
        }
        return route('dashboard', $params);
    }

    $navBase = 'flex items-center gap-2.5 p-2 rounded-xl text-[0.87rem] font-medium mb-1 relative';
    $navInactive = 'text-slate-600 hover:bg-slate-50 hover:text-slate-900';
    $navActive = 'bg-gradient-to-br from-green-50/20 to-green-100/10 text-green-700 relative';
@endphp

    <aside id="sidebar-aside" class="flex-shrink-0 bg-white flex flex-col border-r border-slate-200 sticky top-0 self-start h-screen">
<style>
    #sidebar-aside {
        width: 68px;
        transition: width 0.1s ease-in-out;
        overflow: hidden;
        white-space: nowrap;
    }
    #sidebar-aside:hover {
        width: 248px;
    }

    /* ── Independent scrollable nav area ── */
    #sidebar-aside .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        scrollbar-width: none;
    }
    #sidebar-aside .sidebar-nav::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    /* ── Bottom section stays pinned at bottom of sidebar ── */
    #sidebar-aside .sidebar-bottom {
        flex-shrink: 0;
    }

    /* ── Nav links: icon centered in collapsed sidebar, text fully hidden ── */
    #sidebar-aside:not(:hover) nav a {
        justify-content: center;
        gap: 0;
        padding: 0.5rem 0;
        font-size: 0;
    }
    #sidebar-aside:not(:hover) nav a svg {
        font-size: initial;
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    /* ── Logo header: center icon when collapsed ── */
    #sidebar-aside:not(:hover) .sidebar-logo-wrap {
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
    }

    /* ── Bottom section: center items when collapsed ── */
    #sidebar-aside:not(:hover) .sidebar-bottom {
        padding-left: 0;
        padding-right: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    #sidebar-aside:not(:hover) .sidebar-bottom > div:first-child {
        justify-content: center;
        padding: 0.5rem;
        width: auto;
        border-radius: 0.75rem;
    }
    #sidebar-aside:not(:hover) .sidebar-bottom > button {
        justify-content: center;
        padding: 0.5rem;
        width: 40px;
        height: 40px;
        margin: 0 auto;
        border-radius: 0.75rem;
    }

    /* ── Hide text/labels when collapsed ── */
    #sidebar-aside:not(:hover) .sidebar-text,
    #sidebar-aside:not(:hover) .sidebar-group-header,
    #sidebar-aside:not(:hover) .sidebar-group-toggle,
    #sidebar-aside:not(:hover) .sidebar-user-details,
    #sidebar-aside:not(:hover) .sidebar-signout-label {
        display: none !important;
    }

    /* ── Active state indicator: smaller bar when collapsed ── */
    #sidebar-aside:not(:hover) nav a .sidebar-active-badge {
        width: 3px;
        top: 35%;
        bottom: 35%;
        left: 3px;
    }
</style>
<div class="sidebar-logo-wrap flex items-center gap-3 p-6 border-b border-slate-100"> 
    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-white border border-slate-200 overflow-hidden"> 
        <img src="{{ asset('images/MHOLogoV2.png') }}" alt="OPOL - MHO" class="w-full h-full object-cover"> 
    </div> 
    <div class="sidebar-text pt-0.78 truncate"> 
        <div class="font-serif font-bold text-slate-900 text-sm leading-[1.2]">OPOL - MHO</div> 
        <div class="text-slate-400 font-medium text-[0.68rem] uppercase tracking-widest">{{ $roleLabel }}</div> 
    </div> 
</div>

    <nav class="sidebar-nav flex-1 px-3 py-2 overflow-y-auto scrollbar-hidden">
        @php
            $isDashboardActive = $currentSection === 'overview';
        @endphp

        @if ($roleKey !== 'admin')
            @php
                $groupHeaderBase = 'sidebar-group-header flex items-center justify-between gap-2 pt-4 pb-1 text-slate-400 text-[0.67rem] font-semibold uppercase tracking-widest';
                $groupToggleBtn = 'inline-flex items-center justify-center w-7 h-7 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-700';
                $mainGroupKey = $roleKey . '-main';
            @endphp

            <div class="{{ $groupHeaderBase }}">
                <div>Main Menu</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="{{ $mainGroupKey }}">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="{{ $mainGroupKey }}">
                <a href="{{ route('dashboard', ['role' => $roleKey]) }}" class="{{ $navBase }} {{ $isDashboardActive ? $navActive : $navInactive }}">
                    <x-lucide-layout-dashboard class="w-[18px] h-[18px] flex-shrink-0 {{ $isDashboardActive ? 'text-green-600' : '' }}" />
                    Dashboard
                    @if ($isDashboardActive)
                        
                    @endif
                </a>
            </div>
        @endif

        @if ($roleKey === 'admin')
            @php
                $isUserManagement = $currentSection === 'user-management';
                $isDoctorManagement = $currentSection === 'doctor-management';
                $isServicesManagement = $currentSection === 'services-management';
                $isMedicinesManagement = $currentSection === 'medicines-management';
                $isPatientRecords = $currentSection === 'patient-records';
                $isAppointments = $currentSection === 'appointments';
                $isVerificationOversight = $currentSection === 'verification-oversight';
                $isReports = $currentSection === 'reports';
                $isChatbotManagement = $currentSection === 'chatbot-management';
                $isLogs = $currentSection === 'logs';
                $isSettings = $currentSection === 'settings';

                $groupHeaderBase = 'sidebar-group-header flex items-center justify-between gap-2 pt-4 pb-1 text-slate-400 text-[0.67rem] font-semibold uppercase tracking-widest';
                $groupToggleBtn = 'inline-flex items-center justify-center w-7 h-7 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-700';
            @endphp

            <div class="{{ $groupHeaderBase }}">
                <div>Main Menu</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="admin-main">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="admin-main">
                <a href="{{ route('dashboard', ['role' => $roleKey]) }}" class="{{ $navBase }} {{ $isDashboardActive ? $navActive : $navInactive }}">
                    <x-lucide-layout-dashboard class="w-[18px] h-[18px] flex-shrink-0 {{ $isDashboardActive ? 'text-green-600' : '' }}" />
                    Dashboard
                    @if ($isDashboardActive)
                        
                    @endif
                </a>
            </div>

            <div class="{{ $groupHeaderBase }}">
                <div>Clinical Management</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="admin-clinical">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="admin-clinical">
                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'doctor-management']) }}" class="{{ $navBase }} {{ $isDoctorManagement ? $navActive : $navInactive }}">
                    <x-lucide-stethoscope class="w-[18px] h-[18px] {{ $isDoctorManagement ? 'text-green-600' : '' }}" />
                    Staff
                    @if ($isDoctorManagement)
                        
                    @endif
                </a>

                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'appointments']) }}" class="{{ $navBase }} {{ $isAppointments ? $navActive : $navInactive }}">
                    <x-lucide-calendar class="w-[18px] h-[18px] {{ $isAppointments ? 'text-green-600' : '' }}" />
                    Appointments
                    @if ($isAppointments)
                        
                    @endif
                </a>

                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'patient-records']) }}" class="{{ $navBase }} {{ $isPatientRecords ? $navActive : $navInactive }}">
                    <x-lucide-folder-open class="w-[18px] h-[18px] {{ $isPatientRecords ? 'text-green-600' : '' }}" />
                    Patient Records
                    @if ($isPatientRecords)
                        
                    @endif
                </a>
            </div>

            <div class="{{ $groupHeaderBase }}">
                <div>Inventory & Services</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="admin-inventory">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="admin-inventory">
                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'services-management']) }}" class="{{ $navBase }} {{ $isServicesManagement ? $navActive : $navInactive }}">
                    <x-lucide-briefcase-medical class="w-[18px] h-[18px] {{ $isServicesManagement ? 'text-green-600' : '' }}" />
                    Services
                    @if ($isServicesManagement)
                        
                    @endif
                </a>

                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'medicines-management']) }}" class="{{ $navBase }} {{ $isMedicinesManagement ? $navActive : $navInactive }}">
                    <x-lucide-syringe class="w-[18px] h-[18px] {{ $isMedicinesManagement ? 'text-green-600' : '' }}" />
                    Medicines
                    @if ($isMedicinesManagement)
                        
                    @endif
                </a>
            </div>

            <div class="{{ $groupHeaderBase }}">
                <div>Administrative Tools</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="admin-tools">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="admin-tools">
                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'user-management']) }}" class="{{ $navBase }} {{ $isUserManagement ? $navActive : $navInactive }}">
                    <x-lucide-users class="w-[18px] h-[18px] {{ $isUserManagement ? 'text-green-600' : '' }}" />
                    Users
                    @if ($isUserManagement)
                        
                    @endif
                </a>

                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'verification-oversight']) }}" class="{{ $navBase }} {{ $isVerificationOversight ? $navActive : $navInactive }}">
                    <x-lucide-user-check class="w-[18px] h-[18px] {{ $isVerificationOversight ? 'text-green-600' : '' }}" />
                    Verification Oversight
                    @if ($isVerificationOversight)
                        
                    @endif
                </a>
            </div>

            <div class="{{ $groupHeaderBase }}">
                <div>System & Analytics</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="admin-system">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="admin-system">
                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'reports']) }}" class="{{ $navBase }} {{ $isReports ? $navActive : $navInactive }}">
                    <x-lucide-chart-no-axes-combined class="w-[18px] h-[18px] {{ $isReports ? 'text-green-600' : '' }}" />
                    Reports
                    @if ($isReports)
                        
                    @endif
                </a>

                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'logs']) }}" class="{{ $navBase }} {{ $isLogs ? $navActive : $navInactive }}">
                    <x-lucide-folder class="w-[18px] h-[18px] {{ $isLogs ? 'text-green-600' : '' }}" />
                    Logs
                    @if ($isLogs)
                        
                    @endif
                </a>

                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'chatbot-management']) }}" class="{{ $navBase }} {{ $isChatbotManagement ? $navActive : $navInactive }}">
                    <x-lucide-bot class="w-[18px] h-[18px] {{ $isChatbotManagement ? 'text-green-600' : '' }}" />
                    Chatbot
                    @if ($isChatbotManagement)
                        
                    @endif
                </a>

                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'settings']) }}" class="{{ $navBase }} {{ $isSettings ? $navActive : $navInactive }}">
                    <x-lucide-settings class="w-[18px] h-[18px] {{ $isSettings ? 'text-green-600' : '' }}" />
                    Settings
                    @if ($isSettings)
                        
                    @endif
                </a>
            </div>
        @elseif ($roleKey === 'receptionist')
            @php
                $isReceptionRegister = $currentSection === 'register-patient';
                $isReceptionAppointments = $currentSection === 'book-appointment';
                $isReceptionWalkIns = $currentSection === 'walk-ins';
                $isReceptionQueue = $currentSection === 'queue-management';
                $isReceptionRecordPayments = $currentSection === 'record-payment';
                $isReceptionVerificationOversight = $currentSection === 'verification-oversight';
                $isReceptionSettings = $currentSection === 'settings-reception';

                $groupHeaderBase = 'sidebar-group-header flex items-center justify-between gap-2 pt-4 pb-1 text-slate-400 text-[0.67rem] font-semibold uppercase tracking-widest';
                $groupToggleBtn = 'inline-flex items-center justify-center w-7 h-7 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-700';
            @endphp

            <div class="{{ $groupHeaderBase }}">
                <div>Patients & Appointments</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="reception-patients">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="reception-patients">

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'register-patient']) }}" class="{{ $navBase }} {{ $isReceptionRegister ? $navActive : $navInactive }}">
                <x-lucide-user-plus class="w-[18px] h-[18px] {{ $isReceptionRegister ? 'text-green-600' : '' }}" />
                Register patient
                @if ($isReceptionRegister)
                    
                @endif
            </a>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'book-appointment']) }}" class="{{ $navBase }} {{ $isReceptionAppointments ? $navActive : $navInactive }}">
                <x-lucide-calendar class="w-[18px] h-[18px] {{ $isReceptionAppointments ? 'text-green-600' : '' }}" />
                Appointments
                @if ($isReceptionAppointments)
                    
                @endif
            </a>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'walk-ins']) }}" class="{{ $navBase }} {{ $isReceptionWalkIns ? $navActive : $navInactive }}">
                <x-lucide-user-check class="w-[18px] h-[18px] {{ $isReceptionWalkIns ? 'text-green-600' : '' }}" />
                Walk-ins
                @if ($isReceptionWalkIns)
                    
                @endif
            </a>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'queue-management']) }}" class="{{ $navBase }} {{ $isReceptionQueue ? $navActive : $navInactive }} mb-3">
                <x-lucide-list class="w-[18px] h-[18px] {{ $isReceptionQueue ? 'text-green-600' : '' }}" />
                Queue management
                @if ($isReceptionQueue)
                    
                @endif
            </a>
            </div>


            <div class="{{ $groupHeaderBase }}">
                <div>Billing</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="reception-billing">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="reception-billing">

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'record-payment']) }}" class="{{ $navBase }} {{ $isReceptionRecordPayments ? $navActive : $navInactive }}">
                <x-lucide-credit-card class="w-[18px] h-[18px] {{ $isReceptionRecordPayments ? 'text-green-600' : '' }}" />
                Record payments
                @if ($isReceptionRecordPayments)
                    
                @endif
            </a>
            </div>

            <div class="{{ $groupHeaderBase }}">
                <div>Verification</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="reception-verification">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="reception-verification">

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'verification-oversight']) }}" class="{{ $navBase }} {{ $isReceptionVerificationOversight ? $navActive : $navInactive }} mb-3">
                <x-lucide-user-check class="w-[18px] h-[18px] {{ $isReceptionVerificationOversight ? 'text-green-600' : '' }}" />
                Verification requests
                @if ($isReceptionVerificationOversight)
                    
                @endif
            </a>
            </div>




            <div class="{{ $groupHeaderBase }}">
                <div>System</div>
                <button type="button" class="{{ $groupToggleBtn }} sidebar-group-toggle" data-group="reception-system">
                    <x-lucide-chevron-down class="sidebar-group-icon-expanded w-[18px] h-[18px]" />
                    <x-lucide-chevron-right class="sidebar-group-icon-collapsed hidden w-[18px] h-[18px]" />
                </button>
            </div>
            <div data-group-body="reception-system">
                <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'settings-reception']) }}" class="{{ $navBase }} {{ $isReceptionSettings ? $navActive : $navInactive }}">
                    <x-lucide-settings class="w-[18px] h-[18px] {{ $isReceptionSettings ? 'text-green-600' : '' }}" />
                    Settings
                    @if ($isReceptionSettings)
                        
                    @endif
                </a>
            </div>
        @elseif ($roleKey === 'doctor')
            @php
                $isDoctorSchedule = $currentSection === 'my-schedule';
                $isDoctorConsultation = $currentSection === 'consultation';
                $isDoctorPrescription = $currentSection === 'prescriptions';
                $isDoctorHistory = $currentSection === 'history';
                $isDoctorPatientRecords = $currentSection === 'patient-records';
                $isDoctorSettings = $currentSection === 'settings-doctor';
            @endphp

            <div class="sidebar-group-header text-slate-400 text-[0.67rem] font-semibold uppercase tracking-widest mt-4 mb-1">Work</div>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'my-schedule']) }}" class="{{ $navBase }} {{ $isDoctorSchedule ? $navActive : $navInactive }}">
                <x-lucide-calendar class="w-[18px] h-[18px] {{ $isDoctorSchedule ? 'text-green-600' : '' }}" />
                My Appointments
                @if ($isDoctorSchedule)
                    
                @endif
            </a>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'consultation']) }}" class="{{ $navBase }} {{ $isDoctorConsultation ? $navActive : $navInactive }}">
                <x-lucide-clipboard-list class="w-[18px] h-[18px] {{ $isDoctorConsultation ? 'text-green-600' : '' }}" />
                Consultation
                @if ($isDoctorConsultation)
                    
                @endif
            </a>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'prescriptions']) }}" class="{{ $navBase }} {{ $isDoctorPrescription ? $navActive : $navInactive }}">
                <x-lucide-pill class="w-[18px] h-[18px] {{ $isDoctorPrescription ? 'text-green-600' : '' }}" />
                Prescription
                @if ($isDoctorPrescription)
                    
                @endif
            </a>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'history']) }}" class="{{ $navBase }} {{ $isDoctorHistory ? $navActive : $navInactive }} mb-3">
                <x-lucide-history class="w-[18px] h-[18px] {{ $isDoctorHistory ? 'text-green-600' : '' }}" />
                History
                @if ($isDoctorHistory)
                    
                @endif
            </a>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'patient-records']) }}" class="{{ $navBase }} {{ $isDoctorPatientRecords ? $navActive : $navInactive }} mb-3">
                <x-lucide-folder-open class="w-[18px] h-[18px] {{ $isDoctorPatientRecords ? 'text-green-600' : '' }}" />
                Patient Records
                @if ($isDoctorPatientRecords)
                    
                @endif
            </a>

            <div class="sidebar-group-header text-slate-400 text-[0.67rem] font-semibold uppercase tracking-widest mt-2 mb-1">Settings</div>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'settings-doctor']) }}" class="{{ $navBase }} {{ $isDoctorSettings ? $navActive : $navInactive }}">
                <x-lucide-settings class="w-[18px] h-[18px] {{ $isDoctorSettings ? 'text-green-600' : '' }}" />
                Settings
                @if ($isDoctorSettings)
                    
                @endif
            </a>
        @elseif ($roleKey === 'patient')
            @php
                $isPatientSettings = $currentSection === 'settings-patient';
            @endphp

            <div class="sidebar-group-header text-slate-400 text-[0.67rem] font-semibold uppercase tracking-widest mt-4 mb-1">System</div>

            <a href="{{ route('dashboard', ['role' => $roleKey, 'section' => 'settings-patient']) }}" class="{{ $navBase }} {{ $isPatientSettings ? $navActive : $navInactive }}">
                <x-lucide-settings class="w-[18px] h-[18px] {{ $isPatientSettings ? 'text-green-600' : '' }}" />
                Settings
                @if ($isPatientSettings)
                    
                @endif
            </a>
        @endif
    </nav>

    <div class="sidebar-bottom px-3 py-4 border-t border-slate-100">
        <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 mb-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-green-400 to-green-700 text-white overflow-hidden">
                <img id="sidebarUserImage" src="" class="hidden w-full h-full object-cover" alt="Profile">
                <x-lucide-user id="sidebarUserIcon" class="w-[18px] h-[18px]" />
            </div>
            <div class="sidebar-user-details">
                <div id="sidebarUserName" class="text-slate-800 font-semibold text-[0.83rem] leading-tight">{{ $roleLabel }}</div>
                <div id="sidebarUserEmail" class="text-slate-400 text-[0.7rem]"></div>
            </div>
        </div>
        <button id="sidebarLogoutButton" type="button" class="w-full flex items-center justify-center gap-2.5 p-2 rounded-xl border border-red-400/25 bg-red-50 text-red-600 text-[0.83rem] font-semibold hover:bg-red-100 hover:border-red-400/40">
            <x-lucide-log-out class="w-[16px] h-[16px]" />
            <span class="sidebar-signout-label">Sign Out</span>
        </button>
    </div>
</aside>

<div id="sidebarLogoutModal" class="hidden fixed inset-0 z-[80] bg-slate-900/60 backdrop-blur-sm p-4 items-center justify-center">
    <div class="w-full max-w-md rounded-2xl bg-white border border-slate-200 shadow-[0_20px_80px_rgba(15,23,42,0.35)]">
        <div class="px-5 py-4 border-b border-slate-100">
            <div class="text-sm font-semibold text-slate-900">Confirm Sign Out</div>
            <div class="mt-1 text-[0.8rem] text-slate-600">Are you sure you want to log out?</div>
        </div>
        <div class="px-5 py-4 flex items-center justify-end gap-2">
            <button id="sidebarLogoutCancel" type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-slate-100 text-slate-800 text-[0.78rem] font-semibold hover:bg-slate-200 border border-slate-200">
                Cancel
            </button>
            <button id="sidebarLogoutConfirm" type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-rose-600 text-white text-[0.78rem] font-semibold hover:bg-rose-700">
                Sign out
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        function sidebarApiFetch(path, options) {
            var method = (options && options.method) ? options.method : 'GET'
            var reqHeaders = (options && options.headers) ? Object.assign({}, options.headers) : {}
            reqHeaders['Accept'] = 'application/json'

            var token = null
            try { token = window.localStorage ? window.localStorage.getItem('api_token') : null } catch (_) { token = null }
            if (token) {
                reqHeaders['Authorization'] = 'Bearer ' + token
            }

            if (typeof window.axios === 'function') {
                var config = { method: method, url: path, headers: reqHeaders }
                if (options && options.body && method !== 'GET') {
                    config.data = options.body
                }
                return window.axios(config).then(function (response) {
                    return { ok: true, status: response.status, json: function () { return Promise.resolve(response.data) }, data: response.data }
                }).catch(function (err) {
                    var resp = (err && err.response) ? err.response : { status: 0, data: null }
                    return { ok: false, status: resp.status, json: function () { return Promise.resolve(resp.data) }, data: resp.data }
                })
            }

            // Fallback to native fetch
            var fetchOptions = { method: method, headers: reqHeaders }
            if (options && options.body && method !== 'GET') {
                fetchOptions.body = options.body
            }
            return fetch(path, fetchOptions).then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, status: response.status, json: function () { return Promise.resolve(data) }, data: data }
                })
            }).catch(function () {
                return { ok: false, status: 0, json: function () { return Promise.resolve(null) }, data: null }
            })
        }

        function formatUserName(user) {
            if (!user) {
                return ''
            }
            var parts = []
            if (user.firstname) parts.push(String(user.firstname))
            if (user.middlename) parts.push(String(user.middlename))
            if (user.lastname) parts.push(String(user.lastname))
            var name = parts.join(' ').trim()
            if (name) {
                return name
            }
            return 'User'
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (window.__sidebarInited) return;
            window.__sidebarInited = true;
            var logoutButton = document.getElementById('sidebarLogoutButton')
            var logoutModal = document.getElementById('sidebarLogoutModal')
            var logoutCancel = document.getElementById('sidebarLogoutCancel')
            var logoutConfirm = document.getElementById('sidebarLogoutConfirm')
            var logoutButtonDefaultHtml = logoutButton ? logoutButton.innerHTML : ''
            var logoutConfirmDefaultHtml = logoutConfirm ? logoutConfirm.innerHTML : ''
            var logoutSubmitting = false

            function setLogoutSubmittingState(submitting) {
                logoutSubmitting = !!submitting
                if (logoutButton) {
                    logoutButton.disabled = logoutSubmitting
                    logoutButton.classList.toggle('opacity-70', logoutSubmitting)
                    logoutButton.classList.toggle('cursor-not-allowed', logoutSubmitting)
                    logoutButton.innerHTML = logoutSubmitting
                        ? '<span class="inline-flex items-center gap-2"><span class="w-4 h-4 border-2 border-red-300 border-t-red-600 rounded-full animate-spin"></span><span>Signing Out...</span></span>'
                        : logoutButtonDefaultHtml
                }
                if (logoutCancel) {
                    logoutCancel.disabled = logoutSubmitting
                    logoutCancel.classList.toggle('opacity-70', logoutSubmitting)
                    logoutCancel.classList.toggle('cursor-not-allowed', logoutSubmitting)
                }
                if (logoutConfirm) {
                    logoutConfirm.disabled = logoutSubmitting
                    logoutConfirm.classList.toggle('opacity-70', logoutSubmitting)
                    logoutConfirm.classList.toggle('cursor-not-allowed', logoutSubmitting)
                    logoutConfirm.innerHTML = logoutSubmitting
                        ? '<span class="inline-flex items-center gap-2"><span class="w-3.5 h-3.5 border-2 border-white/40 border-t-white rounded-full animate-spin"></span><span>Signing out...</span></span>'
                        : logoutConfirmDefaultHtml
                }
            }

            function closeLogoutModal() {
                if (!logoutModal || logoutSubmitting) return
                logoutModal.classList.add('hidden')
                logoutModal.classList.remove('flex')
            }

            function openLogoutModal() {
                if (!logoutModal || logoutSubmitting) return
                logoutModal.classList.remove('hidden')
                logoutModal.classList.add('flex')
            }

            function doLogout() {
                if (logoutSubmitting) return
                setLogoutSubmittingState(true)
                try {
                    if (window.localStorage) {
                        window.localStorage.removeItem('api_token')
                        window.localStorage.removeItem('current_user_id')
                        window.localStorage.removeItem('current_user_uuid')
                    }
                } catch (_) {}
                window.location.href = "{{ route('webadmin.login') }}"
            }

            if (logoutButton) {
                logoutButton.addEventListener('click', openLogoutModal)
            }
            if (logoutCancel) {
                logoutCancel.addEventListener('click', closeLogoutModal)
            }
            if (logoutConfirm) {
                logoutConfirm.addEventListener('click', doLogout)
            }
            if (logoutModal) {
                logoutModal.addEventListener('click', function (e) {
                    if (e.target === logoutModal) closeLogoutModal()
                })
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeLogoutModal()
            })

            var toggles = document.querySelectorAll('.sidebar-group-toggle')
            toggles.forEach(function (btn) {
                var group = btn.getAttribute('data-group')
                if (!group) {
                    return
                }
                var body = document.querySelector('[data-group-body="' + group + '"]')
                var iconExpanded = btn.querySelector('.sidebar-group-icon-expanded')
                var iconCollapsed = btn.querySelector('.sidebar-group-icon-collapsed')
                var storageKey = 'sidebar_group_' + group
                var collapsed = false
                try {
                    collapsed = window.localStorage ? window.localStorage.getItem(storageKey) === '1' : false
                } catch (_) {
                    collapsed = false
                }
                if (body) {
                    body.classList.toggle('hidden', collapsed)
                }
                if (iconExpanded) iconExpanded.classList.toggle('hidden', collapsed)
                if (iconCollapsed) iconCollapsed.classList.toggle('hidden', !collapsed)
                btn.addEventListener('click', function () {
                    collapsed = !collapsed
                    if (body) {
                        body.classList.toggle('hidden', collapsed)
                    }
                    if (iconExpanded) iconExpanded.classList.toggle('hidden', collapsed)
                    if (iconCollapsed) iconCollapsed.classList.toggle('hidden', !collapsed)
                    try {
                        if (window.localStorage) {
                            window.localStorage.setItem(storageKey, collapsed ? '1' : '0')
                        }
                    } catch (_) {}
                })
            })

            var nameEl = document.getElementById('sidebarUserName')
            var emailEl = document.getElementById('sidebarUserEmail')
            if (!nameEl || !emailEl) {
                return
            }

            var userRef = null
            try {
                userRef = window.localStorage ? window.localStorage.getItem('current_user_uuid') : null
            } catch (_) {
                userRef = null
            }

            if (!userRef) {
                try {
                    userRef = window.localStorage ? window.localStorage.getItem('current_user_id') : null
                } catch (_) {
                    userRef = null
                }
            }

            if (!userRef) return

            try {
                var dashboardLinks = document.querySelectorAll('a[href*="/dashboard/"]')
                dashboardLinks.forEach(function (anchor) {
                    if (!anchor || !anchor.href) return
                    var u = new URL(anchor.href, window.location.origin)
                    var path = String(u.pathname || '').toLowerCase()
                    u.searchParams.delete('user_id')
                    if (!u.searchParams.get('user_uuid')) {
                        u.searchParams.set('user_uuid', String(userRef))
                    }
                    anchor.href = u.toString()
                })
            } catch (_) {}

            sidebarApiFetch("{{ url('/api/users') }}/" + encodeURIComponent(userRef), { method: 'GET' })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data }
                    }).catch(function () {
                        return { ok: response.ok, data: null }
                    })
                })
                .then(function (result) {
                    if (!result.ok || !result.data) {
                        return
                    }
                    var user = result.data
                    nameEl.textContent = formatUserName(user)
                    emailEl.textContent = user && user.email ? String(user.email) : ''

                    var imgEl = document.getElementById('sidebarUserImage')
                    var iconEl = document.getElementById('sidebarUserIcon')
                    if (imgEl && iconEl) {
                        if (user.prof_path_url) {
                            imgEl.src = user.prof_path_url
                            imgEl.classList.remove('hidden')
                            iconEl.classList.add('hidden')
                        } else {
                            imgEl.src = ''
                            imgEl.classList.add('hidden')
                            iconEl.classList.remove('hidden')
                        }
                    }
                })
                .catch(function () {})
        })
    })();

    // ── Sidebar scroll: save on every scroll (throttled) ──
    // Restore is handled in the layout (app.blade.php)
    (function () {
        var nav = document.querySelector('.sidebar-nav');
        if (!nav) return;
        var timer = null;
        nav.addEventListener('scroll', function () {
            if (timer) clearTimeout(timer);
            timer = setTimeout(function () {
                try { window.localStorage.setItem('sidebar_scroll_top', String(nav.scrollTop)); } catch (_) {}
            }, 150);
        });
    })();

    // ── On hover, scroll to active tab ──
    (function () {
        var aside = document.getElementById('sidebar-aside');
        if (!aside) return;
        aside.addEventListener('mouseenter', function () {
            var nav = aside.querySelector('.sidebar-nav');
            if (!nav) return;
            var active = nav.querySelector('a.text-green-700');
            if (!active) return;
            var linkRect = active.getBoundingClientRect();
            var navRect = nav.getBoundingClientRect();
            if (linkRect.top >= navRect.top + 10 && linkRect.bottom <= navRect.bottom - 10) return;
            var targetScroll = (linkRect.top - navRect.top) + nav.scrollTop - navRect.height / 2 + linkRect.height / 2;
            nav.scrollTop = Math.max(0, Math.min(targetScroll, nav.scrollHeight - nav.clientHeight));
        });
    })();
</script>
