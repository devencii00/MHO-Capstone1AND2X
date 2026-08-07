@php
    $sectionKey = $section ?? 'overview';
    $viewId = request()->query('view');
    $resultType = request()->query('type');
@endphp

@if ($sectionKey === 'records' && $viewId)
    @include('dashviews.laboratory.lab_record_view')
@elseif ($sectionKey === 'records')
    @include('dashviews.laboratory.lab_patient_records')
@elseif ($sectionKey === 'results' && $viewId && $resultType === 'laboratory')
    @include('dashviews.laboratory.lab_laboratory_result')
@elseif ($sectionKey === 'results' && $viewId && $resultType === 'ultrasound')
    @include('dashviews.laboratory.lab_ultrasound_result')
@elseif ($sectionKey === 'results' && $viewId && $resultType === 'xray')
    @include('dashviews.laboratory.lab_xray_result')
@elseif ($sectionKey === 'results')
    @include('dashviews.laboratory.lab_results')
@elseif ($sectionKey === 'laboratory-records')
    @include('dashviews.laboratory.lab_laboratory_records')
@elseif ($sectionKey === 'ultrasound-records')
    @include('dashviews.laboratory.lab_ultrasound_records')
@elseif ($sectionKey === 'xray-records')
    @include('dashviews.laboratory.lab_xray_records')
@elseif ($sectionKey === 'lab-result')
    @include('dashviews.laboratory.lab_laboratory_result')
@elseif ($sectionKey === 'ultrasound-result')
    @include('dashviews.laboratory.lab_ultrasound_result')
@elseif ($sectionKey === 'xray-result')
    @include('dashviews.laboratory.lab_xray_result')
@elseif ($sectionKey === 'xray-pdf')
    @include('dashviews.laboratory.lab_xray_pdf')
@elseif ($sectionKey === 'settings-lab')
    @include('dashviews.laboratory.lab_settings')
@else
    @include('dashviews.laboratory.lab_dashboard')
@endif
