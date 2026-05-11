@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');

    .admin-wrap * {
        font-family: 'Inter', sans-serif;
    }

    .admin-wrap {
        background: #f8f6f2;
        min-height: 100vh;
        padding: 2rem 1.5rem;
    }

    /* HEADER */
    .lap-header {
        margin-bottom: 2rem;
        position: relative;
        padding-left: 1rem;
    }
    .lap-header::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #2d6a4f;
        border-radius: 2px;
    }
    .lap-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e1e1e;
        letter-spacing: -0.3px;
        margin: 0;
    }
    .lap-header p {
        font-size: 0.85rem;
        color: #78716c;
        margin-top: 0.25rem;
    }

    /* SUMMARY CARDS */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .summary-cards { grid-template-columns: repeat(4, 1fr); }
    }
    .scard {
        background: white;
        border-radius: 18px;
        padding: 1rem 1.25rem;
        border: 1px solid #e7e5e4;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        transition: all 0.2s;
    }
    .scard .sc-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #a8a29e;
        margin-bottom: 0.5rem;
    }
    .scard .sc-val {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1c1c1c;
        line-height: 1.2;
    }
    .scard .sc-unit {
        font-size: 0.7rem;
        font-weight: 500;
        color: #a8a29e;
        margin-top: 0.25rem;
    }
    .scard-emerald .sc-val { color: #2d6a4f; }
    .scard-blue .sc-val { color: #2563eb; }
    .scard-purple .sc-val { color: #7c3aed; }
    .scard-red .sc-val { color: #dc2626; }

    /* TWO COLUMN GRID */
    .two-col-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .two-col-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* THREE COLUMN GRID FOR CHARTS */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 1024px) {
        .charts-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* CARD */
    .card {
        background: white;
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e7e5e4;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .card h3 {
        font-size: 0.85rem;
        font-weight: 700;
        color: #44403c;
        margin-bottom: 1rem;
    }
    .card .sub {
        font-size: 0.7rem;
        color: #a8a29e;
        margin-top: -0.5rem;
        margin-bottom: 1rem;
    }

    /* CHART CONTAINER */
    .chart-container {
        height: 200px;
        position: relative;
    }

    /* LEGEND */
    .legend-stats {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .legend-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        margin-right: 0.5rem;
    }
    .legend-label { color: #57534e; }
    .legend-value { font-weight: 600; color: #1c1c1c; }

    /* SUMMARY ITEMS */
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: 14px;
        border: 1px solid #f0f0ee;
        margin-bottom: 0.75rem;
    }
    .summary-item:last-child { margin-bottom: 0; }
    .summary-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #57534e;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .summary-value {
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.7rem;
    }
    .value-green { background: #e3f5e9; color: #0f6e3f; }
    .value-blue { background: #eef2ff; color: #1e40af; }
    .value-red { background: #fee9e6; color: #dc2626; }

    /* ACTIVITY LIST */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .activity-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 14px;
        border: 1px solid #f0f0ee;
        transition: background 0.15s;
    }
    .activity-item:hover { background: #fefcf7; }
    .activity-avatar {
        width: 40px;
        height: 40px;
        background: #eef5f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2d6a4f;
        font-size: 1rem;
    }
    .activity-info h5 {
        font-weight: 700;
        color: #1c1c1c;
        font-size: 0.8rem;
        margin: 0;
    }
    .activity-info p {
        font-size: 0.65rem;
        color: #a8a29e;
        margin: 0.2rem 0 0;
    }
    .activity-time {
        margin-left: auto;
        font-size: 0.65rem;
        color: #a8a29e;
    }

    /* PROGRESS BAR */
    .progress-item {
        margin-bottom: 1rem;
    }
    .progress-header {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        margin-bottom: 0.3rem;
    }
    .progress-header span:first-child { font-weight: 600; color: #57534e; }
    .progress-header span:last-child { font-weight: 700; color: #2d6a4f; }
    .progress-track {
        width: 100%;
        background: #f1f5f0;
        border-radius: 20px;
        height: 6px;
        overflow: hidden;
    }
    .progress-fill {
        background: #2d6a4f;
        height: 100%;
        border-radius: 20px;
    }

    /* EMPTY STATE */
    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        color: #a8a29e;
    }
    .empty-state i { font-size: 1.5rem; margin-bottom: 0.5rem; display: block; }
    .empty-state p { font-weight: 600; margin-top: 0.5rem; font-size: 0.8rem; }
</style>

<div class="admin-wrap">
<div style="max-width:1280px;margin:0 auto;">

    <!-- HEADER -->
    <div class="lap-header">
        <h1>Admin Dashboard</h1>
        <p>Dashboard Sistem Manajemen Absensi Perusahaan Sawit</p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary-cards">
        <div class="scard scard-blue">
            <div class="sc-label">Total Pegawai</div>
            <div class="sc-val">{{ number_format($totalPegawai ?? 0) }}</div>
            <div class="sc-unit">Seluruh Tim</div>
        </div>
        <div class="scard scard-emerald">
            <div class="sc-label">Hadir Hari Ini</div>
            <div class="sc-val">{{ number_format($hadirHariIni ?? 0) }}</div>
            <div class="sc-unit">Total Kehadiran</div>
        </div>
        <div class="scard scard-purple">
            <div class="sc-label">Produksi Hari Ini</div>
            <div class="sc-val">{{ number_format($produksiHariIni ?? 0, 1) }} <span style="font-size:0.9rem;">kg</span></div>
            <div class="sc-unit">Total Panen</div>
        </div>
        <div class="scard scard-red">
            <div class="sc-label">Jumlah Alpha</div>
            <div class="sc-val">{{ number_format($totalAlpha ?? 0) }}</div>
            <div class="sc-unit">Tidak Hadir / Belum Absen</div>
        </div>
    </div>

    <!-- CHARTS SECTION (2 COLUMN) -->
    <div class="charts-grid">
        <!-- DONUT CHART - Status Absensi -->
        <div class="card">
            <h3>Status Absensi Hari Ini</h3>
            <div class="chart-container">
                <canvas id="attendanceChart"></canvas>
            </div>
            <div class="legend-stats">
                <div class="legend-item">
                    <div><span class="legend-dot" style="background:#2d6a4f;"></span><span class="legend-label">Hadir</span></div>
                    <span class="legend-value">{{ $hadirHariIni ?? 0 }}</span>
                </div>
                <div class="legend-item">
                    <div><span class="legend-dot" style="background:#eab308;"></span><span class="legend-label">Terlambat</span></div>
                    <span class="legend-value">{{ $totalTerlambat ?? 0 }}</span>
                </div>
                <div class="legend-item">
                    <div><span class="legend-dot" style="background:#dc2626;"></span><span class="legend-label">Alpha</span></div>
                    <span class="legend-value">{{ $totalAlpha ?? 0 }}</span>
                </div>
            </div>
            <div class="sub" style="margin-top: 0.75rem; text-align: center;">
                *Alpha = Pegawai yang belum melakukan absensi hari ini
            </div>
        </div>

        <!-- RINGKASAN HARI INI -->
        <div class="card">
            <h3>Ringkasan Hari Ini</h3>
            <div class="summary-item">
                <div class="summary-label">
                    <span></span> Kehadiran
                </div>
                <div class="summary-value value-green">
                    @php
                        $totalHadir = ($hadirHariIni ?? 0) + ($totalTerlambat ?? 0);
                        $totalPegawaiFix = $totalPegawai ?? 1;
                        $rateKehadiran = $totalPegawaiFix > 0 ? round(($totalHadir / $totalPegawaiFix) * 100) : 0;
                    @endphp
                    {{ $rateKehadiran }}%
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">
                    <span></span> Produktivitas
                </div>
                <div class="summary-value value-blue">
                    @if(($produksiHariIni ?? 0) > 100) Tinggi
                    @elseif(($produksiHariIni ?? 0) > 50) Sedang
                    @else Rendah
                    @endif
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">
                    <span></span> Alpha (Belum Absen)
                </div>
                <div class="summary-value value-red">{{ $totalAlpha ?? 0 }} Orang</div>
            </div>
        </div>
    </div>

    <!-- BOTTOM PANELS (2 COLUMN) -->
    <div class="two-col-grid">
        <!-- AKTIVITAS TERBARU -->
        <div class="card">
            <h3>Aktivitas Terbaru</h3>
            <div class="activity-list">
                @forelse($recentActivities as $activity)
                <div class="activity-item">
                    <div class="activity-avatar"><i class="fas fa-user-check"></i></div>
                    <div class="activity-info">
                        <h5>{{ $activity->user->name }}</h5>
                        <p>{{ ucfirst($activity->user->role) }} — Check In: {{ $activity->check_in ? \Carbon\Carbon::parse($activity->check_in)->format('H:i') : '-' }} WIB</p>
                    </div>
                    <div class="activity-time">
                        @if($activity->check_in)
                            {{ \Carbon\Carbon::parse($activity->check_in)->diffForHumans() }}
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada aktivitas hari ini</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- OVERVIEW DEPARTEMEN -->
        <div class="card">
            <h3>Overview Departemen</h3>
            @forelse($departments as $role => $dept)
            <div class="progress-item">
                <div class="progress-header">
                    <span>{{ $dept['name'] }}</span>
                    <span>{{ $dept['hadir'] }}/{{ $dept['total'] }}</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $dept['percentage'] }}%"></div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-chart-simple"></i>
                <p>Belum ada data departemen</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Donut Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Alpha'],
            datasets: [{
                data: [{{ $hadirHariIni ?? 0 }}, {{ $totalTerlambat ?? 0 }}, {{ $totalAlpha ?? 0 }}],
                backgroundColor: ['#2d6a4f', '#eab308', '#dc2626'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    callbacks: { 
                        label: (ctx) => {
                            let label = ctx.label;
                            if (ctx.label === 'Alpha') {
                                return `${label}: ${ctx.parsed} (Belum Absen)`;
                            }
                            return `${label}: ${ctx.parsed}`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection