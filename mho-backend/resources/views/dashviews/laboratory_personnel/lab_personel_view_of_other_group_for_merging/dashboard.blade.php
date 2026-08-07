@extends('layouts.app')

@section('title', 'Staff1 Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
  * { box-sizing: border-box; }

  .db {
    background: #f4f6f9;
    padding: 24px;
    min-height: 100vh;
    font-family: 'Nunito', sans-serif;
  }

  /* ===== HEADER ===== */
  .db-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 22px;
  }
  .db-title {
    font-size: 22px;
    font-weight: 800;
    color: #1a2332;
  }
  .db-sub {
    font-size: 13px;
    color: #8a98af;
    margin-top: 2px;
    font-weight: 600;
  }
  .db-date {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: #5a6a7e;
    background: #ffffff;
    border: 1px solid #e5e9f2;
    border-radius: 9px;
    padding: 8px 14px;
    font-weight: 700;
  }
  .db-date i { font-size: 16px; color: #1db954; }

  /* ===== STAT CARDS ===== */
  .stat-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 18px;
  }
  .stat-c {
    background: #ffffff;
    border-radius: 16px;
    padding: 18px 18px 14px;
    border: 1px solid #e8ecf5;
    transition: box-shadow 0.2s;
  }
  .stat-c:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.07); }
  .stat-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 8px;
  }
  .stat-icon-wrap {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
  }
  .stat-icon-wrap.gr { background: #eaf7f0; color: #1db954; }
  .stat-icon-wrap.bl { background: #e8f3fd; color: #3a7bd5; }
  .stat-icon-wrap.or { background: #fef5e7; color: #f5a623; }
  .stat-icon-wrap.sk { background: #e8f7fd; color: #1c92d2; }
  .stat-lbl {
    font-size: 11px;
    font-weight: 800;
    color: #8a98af;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }
  .stat-num {
    font-size: 36px;
    font-weight: 800;
    color: #1a2332;
    line-height: 1;
    margin: 6px 0 10px;
  }
  .stat-foot-row {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    font-size: 11px;
    font-weight: 800;
    border-radius: 5px;
    padding: 2px 7px;
  }
  .stat-badge.up { background: #eaf7f0; color: #1db954; }
  .stat-badge.dn { background: #fef0f0; color: #e74c3c; }
  .stat-badge.nt { background: #f0f2f5; color: #8a98af; }
  .stat-foot { font-size: 11px; color: #b0bac8; font-weight: 600; }
  .spark { height: 38px; width: 100%; margin-top: 10px; }

  /* ===== ANALYTICS PANEL ===== */
  .analytics-panel {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e8ecf5;
    margin-bottom: 18px;
  }
  .panel-hd {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }
  .panel-title {
    font-size: 16px;
    font-weight: 800;
    color: #1a2332;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .panel-title i { color: #1db954; font-size: 20px; }
  .panel-controls { display: flex; align-items: center; gap: 10px; }
  .view-link {
    font-size: 12px;
    font-weight: 700;
    color: #1db954;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .view-link:hover { text-decoration: underline; }
  .period-sel {
    font-size: 12px;
    font-weight: 700;
    padding: 6px 11px;
    border: 1px solid #e5e9f2;
    border-radius: 8px;
    color: #5a6a7e;
    background: #ffffff;
    cursor: pointer;
    font-family: 'Nunito', sans-serif;
    outline: none;
    transition: border-color 0.2s;
  }
  .period-sel:focus { border-color: #1db954; }
  .line-chart-area { position: relative; height: 230px; }
  .chart-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    border-top: 1px solid #f0f3f8;
    margin-top: 14px;
    padding-top: 14px;
  }
  .cs-item {
    text-align: center;
    padding: 4px 8px;
    border-right: 1px solid #f0f3f8;
  }
  .cs-item:last-child { border-right: none; }
  .cs-num { font-size: 20px; font-weight: 800; color: #1a2332; }
  .cs-lbl { font-size: 11px; color: #8a98af; font-weight: 700; margin: 2px 0; }
  .cs-pct { font-size: 11px; font-weight: 800; }
  .cs-pct.up { color: #1db954; }
  .cs-pct.dn { color: #e74c3c; }

  /* ===== DISTRIBUTION ROW ===== */
  .dist-section-title {
    font-size: 15px;
    font-weight: 800;
    color: #1a2332;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
  }
  .dist-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
  }
  .dist-panel {
    background: #ffffff;
    border-radius: 16px;
    padding: 16px;
    border: 1px solid #e8ecf5;
    transition: box-shadow 0.2s;
  }
  .dist-panel:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.06); }
  .dist-title {
    font-size: 12px;
    font-weight: 800;
    color: #1a2332;
    margin-bottom: 14px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .donut-wrap { display: flex; align-items: center; gap: 12px; }
  .donut-area { position: relative; height: 90px; width: 90px; flex-shrink: 0; }
  .legend-sm { flex: 1; min-width: 0; }
  .leg-item {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
    font-size: 11px;
    color: #5a6a7e;
    font-weight: 700;
  }
  .leg-item:last-child { margin-bottom: 0; }
  .leg-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
  .leg-val { margin-left: auto; font-weight: 800; color: #1a2332; font-size: 11px; }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1200px) {
    .stat-row { grid-template-columns: repeat(2, 1fr); }
    .dist-row { grid-template-columns: repeat(2, 1fr); }
    .chart-stats { grid-template-columns: repeat(2, 1fr); }
    .cs-item:nth-child(2) { border-right: none; }
  }
  @media (max-width: 700px) {
    .db { padding: 14px; }
    .stat-row { grid-template-columns: 1fr; }
    .dist-row { grid-template-columns: 1fr; }
    .chart-stats { grid-template-columns: repeat(2, 1fr); }
    .db-header { flex-direction: column; gap: 10px; }
  }
</style>
@endpush

@section('content')
<div class="db">

  {{-- ===== HEADER ===== --}}
  <div class="db-header">
    <div>
      <div class="db-title">Dashboard</div>
      <div class="db-sub">Welcome back! Here's what's happening today.</div>
    </div>
    {{-- <div class="db-date">
      <i class="ti ti-calendar"></i>
      {{ now()->format('F j, Y') }}
    </div> --}}
  </div>

  {{-- ===== STAT CARDS ===== --}}
  <div class="stat-row">

    {{-- Total Patients --}}
    <div class="stat-c">
      <div class="stat-top">
        <div class="stat-lbl">Total Patients</div>
        <div class="stat-icon-wrap gr"><i class="ti ti-users"></i></div>
      </div>
      <div class="stat-num">{{ number_format($stats['total_patients']) }}</div>
      <div class="stat-foot-row">
        <span class="stat-badge {{ $patientGrowth > 0 ? 'up' : ($patientGrowth < 0 ? 'dn' : 'nt') }}">
          @if($patientGrowth > 0) ↑ {{ $patientGrowth }}%
          @elseif($patientGrowth < 0) ↓ {{ abs($patientGrowth) }}%
          @else — 0%
          @endif
        </span>
        <span class="stat-foot">from last month</span>
      </div>
      
    </div>

    {{-- Total Appointments --}}
    <div class="stat-c">
      <div class="stat-top">
        <div class="stat-lbl">Total Appointments</div>
        <div class="stat-icon-wrap gr"><i class="ti ti-calendar-event"></i></div>
      </div>
      <div class="stat-num">{{ number_format($stats['total_appointments']) }}</div>
      <div class="stat-foot-row">
        <span class="stat-badge {{ $apptGrowth > 0 ? 'up' : ($apptGrowth < 0 ? 'dn' : 'nt') }}">
          @if($apptGrowth > 0) ↑ {{ $apptGrowth }}%
          @elseif($apptGrowth < 0) ↓ {{ abs($apptGrowth) }}%
          @else — 0%
          @endif
        </span>
        <span class="stat-foot">from last month</span>
      </div>
    </div>

    {{-- Pending Approvals --}}
    <div class="stat-c">
      <div class="stat-top">
        <div class="stat-lbl">Pending Approvals</div>
        <div class="stat-icon-wrap or"><i class="ti ti-clock"></i></div>
      </div>
      <div class="stat-num">{{ number_format($stats['pending_appointments']) }}</div>
      <div class="stat-foot-row">
        <span class="stat-badge {{ $pendingGrowth > 0 ? 'up' : ($pendingGrowth < 0 ? 'dn' : 'nt') }}">
          @if($pendingGrowth > 0) ↑ {{ $pendingGrowth }}%
          @elseif($pendingGrowth < 0) ↓ {{ abs($pendingGrowth) }}%
          @else — 0%
          @endif
        </span>
        <span class="stat-foot">from last week</span>
      </div>
    </div>

    {{-- In Queue --}}
    <div class="stat-c">
      <div class="stat-top">
        <div class="stat-lbl">In Queue</div>
        <div class="stat-icon-wrap sk"><i class="ti ti-hourglass"></i></div>
      </div>
      <div class="stat-num">{{ number_format($stats['waiting_queue']) }}</div>
      <div class="stat-foot-row">
        <span class="stat-badge {{ $queueGrowth > 0 ? 'up' : ($queueGrowth < 0 ? 'dn' : 'nt') }}">
          @if($queueGrowth > 0) ↑ {{ $queueGrowth }}%
          @elseif($queueGrowth < 0) ↓ {{ abs($queueGrowth) }}%
          @else — 0%
          @endif
        </span>
        <span class="stat-foot">from last hour</span>
      </div>
      {{-- <svg class="spark" viewBox="0 0 120 38" fill="none" preserveAspectRatio="none">
        <path d="M0,10 15,13 30,17 45,21 60,19 75,24 90,22 105,28 120,30 L120,38 L0,38 Z" fill="#1c92d2" opacity="0.10"/>
        <polyline points="{{ $sparklines['queue'] }}" stroke="#1c92d2" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
      </svg> --}}
    </div>

  </div>

  {{-- ===== ANALYTICS PANEL ===== --}}
  <div class="analytics-panel">
    <div class="panel-hd">
      <div class="panel-title">
        <i class="ti ti-chart-line"></i>
        Analytics
      </div>
      <div class="panel-controls">
        {{-- <a href="#" class="view-link"><i class="ti ti-external-link"></i> View Report</a> --}}
        <select class="period-sel" id="periodSelect">
          <option value="this_month" {{ $selectedPeriod == 'this_month' ? 'selected' : '' }}>This Month</option>
          <option value="last_month" {{ $selectedPeriod == 'last_month' ? 'selected' : '' }}>Last Month</option>
          <option value="this_year"  {{ $selectedPeriod == 'this_year'  ? 'selected' : '' }}>This Year</option>
        </select>
      </div>
    </div>

    <div class="line-chart-area">
      <canvas id="lineChart"></canvas>
    </div>

    <div class="chart-stats">
      <div class="cs-item">
        <div class="cs-num">{{ number_format($stats['total_patients']) }}</div>
        <div class="cs-lbl">Total Patients</div>
        {{-- <div class="cs-pct {{ $patientGrowth >= 0 ? 'up' : 'dn' }}">
          {{ $patientGrowth >= 0 ? '+' : '' }}{{ $patientGrowth }}%
        </div> --}}
      </div>
      <div class="cs-item">
        <div class="cs-num">{{ number_format($stats['total_appointments']) }}</div>
        <div class="cs-lbl">Appointments</div>
        {{-- <div class="cs-pct {{ $apptGrowth >= 0 ? 'up' : 'dn' }}">
          {{ $apptGrowth >= 0 ? '+' : '' }}{{ $apptGrowth }}%
        </div> --}}
      </div>
      <div class="cs-item">
        <div class="cs-num">{{ number_format($stats['completed_appointments'] ?? 0) }}</div>
        <div class="cs-lbl">Completed</div>
        {{-- <div class="cs-pct {{ ($completedGrowth ?? 0) >= 0 ? 'up' : 'dn' }}">
          {{ ($completedGrowth ?? 0) >= 0 ? '+' : '' }}{{ $completedGrowth ?? 0 }}%
        </div> --}}
      </div>
      <div class="cs-item">
        <div class="cs-num">{{ number_format($stats['cancelled_appointments'] ?? 0) }}</div>
        <div class="cs-lbl">Cancelled</div>
        {{-- <div class="cs-pct {{ ($cancelledGrowth ?? 0) >= 0 ? 'up' : 'dn' }}">
          {{ ($cancelledGrowth ?? 0) >= 0 ? '+' : '' }}{{ $cancelledGrowth ?? 0 }}%
        </div> --}}
      </div>
    </div>
  </div>

  {{-- ===== DISTRIBUTION OVERVIEW ===== --}}
  <div class="dist-section-title">
    <span>Distribution Overview</span>
    <select class="period-sel" id="distPeriodSelect">
      <option value="this_month" {{ $selectedPeriod == 'this_month' ? 'selected' : '' }}>This Month</option>
      <option value="last_month" {{ $selectedPeriod == 'last_month' ? 'selected' : '' }}>Last Month</option>
      <option value="this_year"  {{ $selectedPeriod == 'this_year'  ? 'selected' : '' }}>This Year</option>
    </select>
  </div>

  <div class="dist-row">

    {{-- Patients by Age Group --}}
    <div class="dist-panel">
      <div class="dist-title">Patients by Age Group</div>
      <div class="donut-wrap">
        <div class="donut-area">
          <canvas id="ageChart"></canvas>
        </div>
        <div class="legend-sm">
          @foreach($ageGroups as $age)
          <div class="leg-item">
            <span class="leg-dot" style="background:{{ $age['color'] }}"></span>
            {{ $age['label'] }}
            <span class="leg-val">{{ number_format($age['value']) }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Appointments by Service --}}
    <div class="dist-panel">
      <div class="dist-title">Appointments by Service</div>
      <div class="donut-wrap">
        <div class="donut-area">
          <canvas id="svcChart"></canvas>
        </div>
        <div class="legend-sm">
          @foreach($serviceGroups as $sg)
          <div class="leg-item">
            <span class="leg-dot" style="background:{{ $sg['color'] }}"></span>
            {{ $sg['label'] }}
            <span class="leg-val">{{ number_format($sg['value']) }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Gender Distribution --}}
    <div class="dist-panel">
      <div class="dist-title">Gender Distribution</div>
      <div class="donut-wrap">
        <div class="donut-area">
          <canvas id="genderChart"></canvas>
        </div>
        <div class="legend-sm">
          @foreach($genderGroups as $gg)
          <div class="leg-item">
            <span class="leg-dot" style="background:{{ $gg['color'] }}"></span>
            {{ $gg['label'] }}
            <span class="leg-val">{{ number_format($gg['value']) }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Status Distribution --}}
    <div class="dist-panel">
      <div class="dist-title">Status Distribution</div>
      <div class="donut-wrap">
        <div class="donut-area">
          <canvas id="statusChart"></canvas>
        </div>
        <div class="legend-sm">
          @foreach($statusGroups as $stg)
          <div class="leg-item">
            <span class="leg-dot" style="background:{{ $stg['color'] }}"></span>
            {{ $stg['label'] }}
            <span class="leg-val">{{ number_format($stg['value']) }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ===== DATA FROM PHP =====
  const chartData  = @json($chartData);
  const ageData    = @json($ageGroups);
  const svcData    = @json($serviceGroups);
  const genderData = @json($genderGroups);
  const statusData = @json($statusGroups);

  // ===== LINE CHART =====
  const lineCanvas = document.getElementById('lineChart');
  if (lineCanvas) {
    const lineCtx = lineCanvas.getContext('2d');
    const gradient = lineCtx.createLinearGradient(0, 0, 0, 230);
    gradient.addColorStop(0, 'rgba(29,185,84,0.15)');
    gradient.addColorStop(1, 'rgba(29,185,84,0.00)');

    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: chartData.labels,
        datasets: [{
          label: 'Appointments',
          data: chartData.values,
          borderColor: '#1db954',
          backgroundColor: gradient,
          borderWidth: 2.5,
          fill: true,
          tension: 0.45,
          pointRadius: 4,
          pointBackgroundColor: '#1db954',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1a2332',
            titleColor: '#ffffff',
            bodyColor: '#b0bac8',
            borderColor: '#2d3a4a',
            borderWidth: 1,
            padding: 10,
            callbacks: {
              label: ctx => ' ' + ctx.parsed.y.toLocaleString() + ' appointments'
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: '#b0bac8',
              font: { size: 11, family: 'Nunito' },
              stepSize: 200,
              callback: v => v.toLocaleString()
            },
            grid: { color: 'rgba(0,0,0,0.04)' },
            border: { display: false }
          },
          x: {
            ticks: {
              color: '#b0bac8',
              font: { size: 11, family: 'Nunito' },
              maxRotation: 0
            },
            grid: { display: false },
            border: { display: false }
          }
        }
      }
    });
  }

  // ===== MINI DONUT HELPER =====
  function miniDonut(id, dataArr) {
    const canvas = document.getElementById(id);
    if (!canvas) return;
    new Chart(canvas.getContext('2d'), {
      type: 'doughnut',
      data: {
        labels: dataArr.map(d => d.label),
        datasets: [{
          data: dataArr.map(d => d.value),
          backgroundColor: dataArr.map(d => d.color),
          borderWidth: 2.5,
          borderColor: '#ffffff',
          hoverOffset: 5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1a2332',
            titleColor: '#ffffff',
            bodyColor: '#b0bac8',
            padding: 8,
            callbacks: {
              label: ctx => ' ' + ctx.parsed.toLocaleString()
            }
          }
        }
      }
    });
  }

  miniDonut('ageChart',    ageData);
  miniDonut('svcChart',    svcData);
  miniDonut('genderChart', genderData);
  miniDonut('statusChart', statusData);

  // ===== PERIOD SELECTORS =====
  function bindPeriod(id) {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('change', function () {
        window.location.href = '{{ route("staff.dashboard") }}?period=' + this.value;
      });
    }
  }
  bindPeriod('periodSelect');
  bindPeriod('distPeriodSelect');

});
</script>
@endpush