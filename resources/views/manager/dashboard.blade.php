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
    .scard-red .sc-val { color: #dc2626; }

    /* CHARTS GRID */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 1024px) {
        .charts-grid { grid-template-columns: repeat(3, 1fr); }
        .charts-grid .col-span-2 { grid-column: span 2; }
    }
    .chart-box {
        background: white;
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e7e5e4;
    }
    .chart-box h3 {
        font-size: 0.85rem;
        font-weight: 700;
        color: #44403c;
        margin-bottom: 1.25rem;
    }
    .chart-box .sub {
        font-size: 0.7rem;
        color: #a8a29e;
        margin-top: 0.25rem;
    }
    .chart-container {
        height: 280px;
        position: relative;
    }

    /* QUICK ACTION */
    .quick-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .quick-grid { grid-template-columns: repeat(3, 1fr); }
    }
    .action-card {
        background: white;
        border-radius: 20px;
        padding: 1rem 1.25rem;
        border: 1px solid #e7e5e4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        transition: all 0.2s;
    }
    .action-card:hover {
        background: #fefcf7;
        border-color: #d6d3d1;
        transform: translateY(-2px);
    }
    .action-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .action-icon {
        width: 48px;
        height: 48px;
        background: #eef5f0;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2d6a4f;
        font-size: 1.25rem;
    }
    .action-icon.purple { background: #f3e8ff; color: #6b21a5; }
    .action-text h4 { font-weight: 700; color: #1c1c1c; font-size: 0.9rem; margin: 0; }
    .action-text p { font-size: 0.7rem; color: #a8a29e; margin: 0.2rem 0 0; }
    .action-arrow { color: #cbcbc4; font-size: 0.9rem; }

    /* TOP PERFORMER */
    .performer-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .performer-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        border-radius: 14px;
        border: 1px solid #f0f0ee;
        transition: background 0.15s;
    }
    .performer-item:hover { background: #fefcf7; }
    .performer-rank {
        width: 36px;
        height: 36px;
        background: #eef5f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #2d6a4f;
    }
    .performer-info h5 { font-weight: 700; color: #1c1c1c; font-size: 0.85rem; margin: 0; }
    .performer-info p { font-size: 0.7rem; color: #a8a29e; margin: 0.2rem 0 0; }
    .performer-weight { font-weight: 700; color: #2d6a4f; font-size: 0.9rem; }

    /* ACTIVITY LIST */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .activity-item {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem;
        border-radius: 16px;
        border: 1px solid #f0f0ee;
        transition: background 0.15s;
    }
    .activity-item:hover { background: #fefcf7; }
    .activity-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .activity-avatar {
        width: 48px;
        height: 48px;
        background: #eef5f0;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2d6a4f;
        font-size: 1.25rem;
    }
    .activity-name h5 { font-weight: 700; color: #1c1c1c; font-size: 0.85rem; margin: 0; }
    .activity-name .role-badge {
        display: inline-block;
        background: #eef5f0;
        color: #2d6a4f;
        font-size: 0.6rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        margin-top: 0.25rem;
    }
    .activity-detail {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.7rem;
        color: #78716c;
    }
    .activity-detail i { margin-right: 0.25rem; color: #a8a29e; }
    .efficiency-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
    }
    .efficiency-high { background: #e3f5e9; color: #0f6e3f; }
    .efficiency-medium { background: #fef3c7; color: #b45309; }
    .efficiency-low { background: #fee9e6; color: #bc3f2c; }

    /* EXPORT BOX */
    .export-box {
        background: white;
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e7e5e4;
        margin-top: 1.5rem;
    }
    .export-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .export-header h3 { font-size: 0.9rem; font-weight: 700; color: #1c1c1c; margin: 0; }
    .export-header p { font-size: 0.7rem; color: #a8a29e; margin: 0.2rem 0 0; }
    .export-icon {
        width: 48px;
        height: 48px;
        background: #eef5f0;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2d6a4f;
        font-size: 1.25rem;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .form-grid { grid-template-columns: repeat(4, 1fr); }
    }
    .form-group label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #57534e;
        display: block;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .form-group input, .form-group select {
        width: 100%;
        border: 1px solid #e7e5e4;
        border-radius: 14px;
        padding: 0.6rem 0.875rem;
        font-size: 0.8rem;
        background: #ffffff;
        outline: none;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #2d6a4f;
        box-shadow: 0 0 0 3px rgba(45,106,79,0.1);
    }
    .btn-export {
        background: #2d6a4f;
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.8rem;
        border: none;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-export-dark {
        background: #1e1e1e;
    }
    .btn-export-dark:hover { background: #2c2c2c; }

    /* EMPTY STATE */
    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        color: #a8a29e;
    }
    .empty-state i { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
    .empty-state p { font-weight: 600; margin-top: 0.5rem; font-size: 0.85rem; }

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
</style>

<div class="admin-wrap">
<div style="max-width:1280px;margin:0 auto;">

    <!-- HEADER -->
    <div class="lap-header">
        <h1>Admin Dashboard</h1>
        <p>Monitoring produktivitas dan aktivitas tim kebun sawit</p>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary-cards">
        <div class="scard scard-emerald">
            <div class="sc-label">Absensi Hari Ini</div>
            <div class="sc-val">{{ number_format($hadirHariIni ?? 0) }}</div>
            <div class="sc-unit">Total Kehadiran</div>
        </div>
        <div class="scard scard-blue">
            <div class="sc-label">Total Tim</div>
            <div class="sc-val">{{ number_format($totalTim ?? 0) }}</div>
            <div class="sc-unit">Tim Aktif</div>
        </div>
        <div class="scard scard-emerald">
            <div class="sc-label">Produksi Hari Ini</div>
            <div class="sc-val">{{ number_format($produksiHariIni ?? 0, 1) }} <span style="font-size:0.9rem;">kg</span></div>
            <div class="sc-unit">Total Panen</div>
        </div>
        <div class="scard scard-red">
            <div class="sc-label">Alpha</div>
            <div class="sc-val">{{ number_format($totalAlpha ?? 0) }}</div>
            <div class="sc-unit">Tidak Hadir</div>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <div class="charts-grid">
        <!-- LINE CHART - Produktivitas Harian -->
        <div class="chart-box col-span-2">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem;">
                <div>
                    <h3>Produktivitas Harian</h3>
                    <div class="sub">Dalam satuan kilogram (kg)</div>
                </div>
                <select id="filterType" style="border:1px solid #e7e5e4; border-radius:30px; padding:0.4rem 1rem; font-size:0.7rem; background:white;">
                    <option value="7">7 Hari</option>
                    <option value="30">1 Bulan</option>
                    <option value="365">1 Tahun</option>
                </select>
            </div>
            <div class="chart-container">
                <canvas id="productivityChart"></canvas>
            </div>
        </div>

        <!-- DONUT CHART - Status Absensi -->
        <div class="chart-box">
            <h3>Status Absensi Hari Ini</h3>
            <div class="chart-container" style="height: 200px;">
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
        </div>
    </div>

    <!-- QUICK ACTION -->
    <div class="quick-grid">
        <a href="{{ route('manager.laporan') }}" class="action-card">
            <div class="action-left">
                <div class="action-icon"><i class="fas fa-chart-line"></i></div>
                <div class="action-text">
                    <h4>Laporan</h4>
                    <p>Lihat laporan harian</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
        <a href="{{ route('manager.log') }}" class="action-card">
            <div class="action-left">
                <div class="action-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="action-text">
                    <h4>Log Absensi</h4>
                    <p>Aktivitas pegawai</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
        <a href="{{ route('manager.pegawai') }}" class="action-card">
            <div class="action-left">
                <div class="action-icon purple"><i class="fas fa-user-friends"></i></div>
                <div class="action-text">
                    <h4>Pegawai</h4>
                    <p>Kelola data tim</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
    </div>

    <!-- TOP PERFORMER & ACTIVITY -->
    <div class="charts-grid" style="margin-bottom:0;">
        <!-- TOP PERFORMER -->
        <div class="chart-box">
            <h3>Top Performer Hari Ini</h3>
            <div class="sub">Berdasarkan hasil produksi</div>
            <div class="performer-list" style="margin-top:1rem;">
                @forelse($topPerformers as $index => $performer)
                <div class="performer-item">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="performer-rank">{{ $index + 1 }}</div>
                        <div class="performer-info">
                            <h5>{{ $performer->name }}</h5>
                            <p>{{ ucfirst($performer->role) }}</p>
                        </div>
                    </div>
                    <div class="performer-weight">{{ number_format($performer->total_produksi ?? 0, 1) }} kg</div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-trophy"></i>
                    <p>Belum ada data produksi hari ini</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- TEAM ACTIVITY -->
        <div class="chart-box col-span-2">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                <div>
                    <h3>Aktivitas Tim Terbaru</h3>
                    <div class="sub">Monitoring check in dan produksi tim</div>
                </div>
                <a href="{{ route('manager.log') }}" style="font-size:0.7rem; color:#2d6a4f; font-weight:600; text-decoration:none;">Lihat Semua →</a>
            </div>
            <div class="activity-list">
                @forelse($recentActivities as $activity)
                <div class="activity-item">
                    <div class="activity-user">
                        <div class="activity-avatar"><i class="fas fa-user-check"></i></div>
                        <div class="activity-name">
                            <h5>{{ $activity->user->name }}</h5>
                            <span class="role-badge">{{ ucfirst($activity->user->role) }}</span>
                        </div>
                    </div>
                    <div class="activity-detail">
                        <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($activity->check_in)->format('H:i') }} WIB</span>
                        @if($activity->produksi_harian)
                        <span><i class="fas fa-seedling"></i> {{ number_format($activity->produksi_harian, 1) }} kg</span>
                        @endif
                    </div>
                    @if($activity->produksi_harian)
                    <div>
                        @php
                            $prod = $activity->produksi_harian;
                            $efficiency = $prod > 20 ? 'Tinggi' : ($prod > 10 ? 'Sedang' : 'Rendah');
                            $effClass = $prod > 20 ? 'efficiency-high' : ($prod > 10 ? 'efficiency-medium' : 'efficiency-low');
                        @endphp
                        <span class="efficiency-badge {{ $effClass }}">{{ $efficiency }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum Ada Aktivitas</p>
                    <span style="font-size:0.7rem;">Aktivitas terbaru tim akan muncul di sini</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- EXPORT DATA -->
    <div class="export-box">
        <div class="export-header">
            <div>
                <h3>Export Data Aktivitas</h3>
                <p>Download laporan absensi dan aktivitas pegawai</p>
            </div>
            <div class="export-icon"><i class="fas fa-file-export"></i></div>
        </div>
        <form action="{{ route('export.all') }}" method="GET">
            <div class="form-grid">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="from" value="{{ date('Y-m-d', strtotime('-1 week')) }}" required>
                </div>
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-export"><i class="fas fa-download"></i> Export CSV</button>
                </div>
                <div class="form-group">
                    <a href="{{ route('export.all.everything') }}" class="btn-export btn-export-dark" style="display:flex; align-items:center; justify-content:center; text-decoration:none;"><i class="fas fa-database"></i> Export Semua Data</a>
                </div>
            </div>
        </form>
    </div>

</div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const allData = @json($produktivitasData ?? []);

    function formatDate(dateString, type = '7') {
        const date = new Date(dateString);
        if (type == '365') {
            return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        }
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }

    function filterData(days) {
        const now = new Date();
        return allData.filter(item => {
            const itemDate = new Date(item.tanggal);
            const diffTime = now - itemDate;
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            return diffDays <= days;
        });
    }

    const ctx = document.getElementById('productivityChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(45,106,79,0.35)');
    gradient.addColorStop(1, 'rgba(45,106,79,0.02)');

    let productivityChart;

    function renderChart(days = 7) {
        const filtered = filterData(days);
        const labels = filtered.map(item => formatDate(item.tanggal, days));
        const totals = filtered.map(item => item.total_produksi);

        if (productivityChart) {
            productivityChart.destroy();
        }

        productivityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Produktivitas',
                    data: totals,
                    borderColor: '#2d6a4f',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.45,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2d6a4f',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e1e1e',
                        padding: 10,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) { return context.parsed.y + ' kg'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { callback: (val) => val + ' kg' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    renderChart(7);
    document.getElementById('filterType').addEventListener('change', function() {
        renderChart(parseInt(this.value));
    });

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
                tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.parsed}` } }
            }
        }
    });
});
</script>
@endsection