@php
    $sectionKey = $section ?? 'overview';
    $viewId = request()->query('view');
    $resultType = request()->query('type');
@endphp

@if ($sectionKey === 'records' && $viewId)
    @include('dashviews.laboratory_personnel.lab_record_view')
@elseif ($sectionKey === 'records')
    @include('dashviews.laboratory_personnel.lab_patient_records')
@elseif ($sectionKey === 'results' && $viewId && $resultType === 'laboratory')
    @include('dashviews.laboratory_personnel.lab_laboratory_result')
@elseif ($sectionKey === 'results' && $viewId && $resultType === 'ultrasound')
    @include('dashviews.laboratory_personnel.lab_ultrasound_result')
@elseif ($sectionKey === 'results' && $viewId && $resultType === 'xray')
    @include('dashviews.laboratory_personnel.lab_xray_result')
@elseif ($sectionKey === 'results')
    @include('dashviews.laboratory_personnel.lab_results')
@elseif ($sectionKey === 'laboratory-records')
    @include('dashviews.laboratory_personnel.lab_laboratory_records')
@elseif ($sectionKey === 'ultrasound-records')
    @include('dashviews.laboratory_personnel.lab_ultrasound_records')
@elseif ($sectionKey === 'xray-records')
    @include('dashviews.laboratory_personnel.lab_xray_records')
@elseif ($sectionKey === 'lab-result')
    @include('dashviews.laboratory_personnel.lab_laboratory_result')
@elseif ($sectionKey === 'ultrasound-result')
    @include('dashviews.laboratory_personnel.lab_ultrasound_result')
@elseif ($sectionKey === 'xray-result')
    @include('dashviews.laboratory_personnel.lab_xray_result')
@elseif ($sectionKey === 'xray-pdf')
    @include('dashviews.laboratory_personnel.lab_xray_pdf')
@elseif ($sectionKey === 'settings-lab')
    @include('dashviews.laboratory_personnel.lab_settings')
@else
    @include('dashviews.laboratory_personnel.lab_dashboard')
@endif
