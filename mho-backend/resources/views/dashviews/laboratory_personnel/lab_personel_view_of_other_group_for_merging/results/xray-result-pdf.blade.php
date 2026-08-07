<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>X-Ray Result</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #1F2937;
            margin: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2F4CDD;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 18px;
            color: #2F4CDD;
            margin: 0;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.info-table td {
            padding: 4px 6px;
            font-size: 11px;
            vertical-align: top;
        }
        .label {
            color: #6B7280;
            display: block;
            font-size: 10px;
        }
        .value {
            font-weight: bold;
            color: #1F2937;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #2F4CDD;
            border-bottom: 1px solid #E2E6EC;
            padding-bottom: 4px;
            margin-top: 16px;
            margin-bottom: 8px;
        }
        .xray-image {
            width: 100%;
            max-height: 350px;
            margin-bottom: 16px;
        }
        ul.findings-list {
            margin: 0;
            padding-left: 16px;
        }
        ul.findings-list li {
            margin-bottom: 6px;
        }
        .impression-box, .notes-box {
            border: 1px solid #E2E6EC;
            background-color: #F8F9FB;
            padding: 10px;
            border-radius: 4px;
            min-height: 40px;
        }
        .footer {
            margin-top: 24px;
            font-size: 9px;
            color: #6B7280;
            text-align: center;
            border-top: 1px solid #E2E6EC;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>X-RAY RESULT</h1>
    </div>

    <table class="info-table">
        <tr>
            <td width="25%">
                <span class="label">Patient Name</span>
                <span class="value">{{ $record->patient->first_name ?? '' }} {{ $record->patient->last_name ?? '' }}</span>
            </td>
            <td width="25%">
                <span class="label">Patient ID</span>
                <span class="value">
                    {{ isset($record->patient->patient_id)
                        ? $record->patient->patient_id
                        : (isset($record->patient->id)
                            ? date('Y') . '-' . str_pad($record->patient->id, 3, '0', STR_PAD_LEFT)
                            : 'N/A') }}
                </span>
            </td>
            <td width="25%">
                <span class="label">Date of Birth</span>
                <span class="value">
                    {{ $record->patient->date_of_birth ? \Carbon\Carbon::parse($record->patient->date_of_birth)->format('M d, Y') : 'N/A' }}
                </span>
            </td>
            <td width="25%">
                <span class="label">Gender</span>
                <span class="value">{{ $record->patient->gender ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Exam Date</span>
                <span class="value">
                    {{ $record->exam_date ? \Carbon\Carbon::parse($record->exam_date)->format('M d, Y') : 'N/A' }}
                </span>
            </td>
            <td>
                <span class="label">Requesting Physician</span>
                <span class="value">{{ $record->physician_name ?? 'N/A' }}</span>
            </td>
            <td>
                <span class="label">Department</span>
                <span class="value">{{ $record->department ?? 'Radiology' }}</span>
            </td>
            <td>
                <span class="label">Accession No.</span>
                <span class="value">{{ $record->accession_no ?? 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span class="label">Address</span>
                <span class="value">{{ $record->patient->address ?? 'N/A' }}</span>
            </td>
        </tr>
    </table>

    @if($record->xray_image ?? false)
        <div class="section-title">X-RAY IMAGE</div>
        <img src="{{ public_path('storage/' . $record->xray_image) }}" class="xray-image">
    @endif

    <div class="section-title">FINDINGS</div>
    <ul class="findings-list">
        @forelse(explode("\n", $record->findings ?? '') as $finding)
            @if(trim($finding) !== '')
                <li>{{ trim($finding) }}</li>
            @endif
        @empty
            <li>No findings recorded.</li>
        @endforelse
    </ul>

    <div class="section-title">IMPRESSION</div>
    <div class="impression-box">
        {{ $record->impression ?? 'No impression recorded.' }}
    </div>

    <div class="section-title">NOTES</div>
    <div class="notes-box">
        {{ $record->notes ?? 'No notes.' }}
    </div>

    <div class="footer">
        This information is confidential and intended for authorized personnel only.<br>
        Generated on {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}
    </div>

</body>
</html>