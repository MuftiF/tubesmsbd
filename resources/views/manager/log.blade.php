@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');

    .log-wrap * {
        font-family: 'Inter', sans-serif;
    }

    .log-wrap {
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

    /* FILTER BOX */
    .filter-box {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e7e5e4;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .filter-box h3 {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #a8a29e;
        margin-bottom: 1rem;
    }
    .filter-box label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #57534e;
        display: block;
        margin-bottom: 0.3rem;
    }
    .filter-box input,
    .filter-box select {
        width: 100%;
        border: 1px solid #e7e5e4;
        border-radius: 12px;
        padding: 0.6rem 0.875rem;
        font-size: 0.85rem;
        color: #1c1c1c;
        background: #ffffff;
        transition: all 0.2s;
        outline: none;
    }
    .filter-box input:focus,
    .filter-box select:focus {
        border-color: #2d6a4f;
        box-shadow: 0 0 0 3px rgba(45,106,79,0.1);
    }
    .btn-primary {
        background: #2d6a4f;
        color: white;
        padding: 0.55rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-primary:hover { background: #235c44; }
    .btn-secondary {
        background: transparent;
        color: #78716c;
        padding: 0.55rem 1.25rem;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.85rem;
        border: 1px solid #e7e5e4;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-secondary:hover { border-color: #d6d3d1; color: #44403c; }
    .btn-secondary.bg-gray {
        background: #f4f4f2;
        color: #5b5b56;
    }

    /* STATISTICS CARD */
    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e7e5e4;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .stats-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .stats-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1c1c1c;
    }
    .stats-header p {
        font-size: 0.75rem;
        color: #a8a29e;
        margin-top: 0.25rem;
    }
    .chart-circle-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .chart-circle-wrap {
            flex-direction: row;
            gap: 2rem;
        }
    }
    .chart-container {
        position: relative;
        width: 220px;
        height: 220px;
    }
    .chart-center-text {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .chart-center-text .total {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1c1c1c;
        line-height: 1.2;
    }
    .chart-center-text .label {
        font-size: 0.7rem;
        font-weight: 500;
        color: #a8a29e;
        margin-top: 0.2rem;
    }
    .legend-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .legend-color {
        width: 14px;
        height: 14px;
        border-radius: 999px;
    }
    .legend-text {
        font-size: 0.8rem;
        font-weight: 500;
        color: #57534e;
    }
    .legend-value {
        font-weight: 700;
        color: #1c1c1c;
        margin-left: auto;
    }
    .legend-percent {
        font-size: 0.7rem;
        color: #a8a29e;
        margin-left: 0.5rem;
    }

    /* TABLE BOX - SAME STYLE AS LAPORAN */
    .table-box {
        background: white;
        border-radius: 20px;
        padding: 0;
        border: 1px solid #e7e5e4;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem 0.75rem 1.5rem;
        border-bottom: 1px solid #f0f0ee;
    }
    .table-header h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1c1c1c;
        margin: 0;
    }
    .period-badge {
        background: #f8f6f2;
        padding: 0.3rem 0.9rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        color: #78716c;
    }
    .overflow-x-auto {
        overflow-x: auto;
        padding: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead tr {
        border-bottom: 1px solid #e9ecee;
        background: #fafaf8;
    }
    thead th {
        text-align: left;
        padding: 0.9rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #8d8a84;
        background: #fafaf8;
    }
    tbody tr {
        border-bottom: 1px solid #f0f0ee;
        transition: background 0.15s ease;
    }
    tbody tr:hover {
        background: #fefcf7;
    }
    tbody td {
        padding: 0.9rem 1rem;
        font-size: 0.8rem;
        color: #3c3a36;
        vertical-align: middle;
    }
    .td-name {
        font-weight: 600;
        color: #1c1c1c;
    }
    .td-email {
        font-size: 0.7rem;
        color: #a8a29e;
        margin-top: 0.15rem;
    }

    /* BADGES */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.25;
        white-space: nowrap;
    }
    .badge-role-user {
        background: #eef5f0;
        color: #2d6a4f;
    }
    .badge-role-security {
        background: #eef2ff;
        color: #1e40af;
    }
    .badge-role-cleaning {
        background: #fef3c7;
        color: #b45309;
    }
    .badge-role-kantoran {
        background: #f3e8ff;
        color: #6b21a5;
    }
    .badge-status-tepat {
        background: #e3f5e9;
        color: #0f6e3f;
    }
    .badge-status-terlambat {
        background: #fee9e6;
        color: #bc3f2c;
    }
    .time-val {
        font-weight: 500;
        color: #1c1c1c;
    }
    .dash {
        color: #cbcbc4;
    }

    /* PAGINATION */
    .pagination-wrap {
        margin-top: 1rem;
        padding: 1rem 1.5rem 1.25rem 1.5rem;
        border-top: 1px solid #f0f0ee;
    }
    .pagination-wrap nav {
        display: flex;
        justify-content: center;
    }
    .pagination-wrap .pagination {
        display: flex;
        gap: 0.25rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .pagination-wrap .pagination li a,
    .pagination-wrap .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #5e5b56;
        background: transparent;
        text-decoration: none;
        transition: all 0.2s;
    }
    .pagination-wrap .pagination li.active span {
        background: #2d6a4f;
        color: white;
    }
    .pagination-wrap .pagination li a:hover {
        background: #ece9e4;
    }

    /* EMPTY STATE */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #a8a29e;
    }
    .empty-state p {
        font-weight: 600;
        margin-top: 0.5rem;
    }
    .empty-state span {
        font-size: 0.75rem;
    }

    /* GRID UTILITIES */
    .grid-cols-1 { display: grid; grid-template-columns: 1fr; gap: 1rem; }
    @media (min-width: 768px) {
        .md-grid-cols-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
        .md-grid-cols-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
    }
    .flex-between { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; }
    .gap-3 { gap: 0.75rem; }
    .mt-6 { margin-top: 1.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .text-center { text-align: center; }
</style>

<div class="log-wrap">
<div style="max-width:1280px;margin:0 auto;">

    {{-- HEADER --}}
    <div class="lap-header">
        <h1>Log Absensi Tim</h1>
        <p>Monitoring kehadiran dan aktivitas harian pegawai</p>
    </div>

    {{-- FILTER BOX --}}
    <div class="filter-box">
        <h3>Filter Log Absensi</h3>
        <form action="{{ route('manager.log') }}" method="GET">
            <div class="grid-cols-1 md-grid-cols-4">
                <div>
                    <label>Tanggal</label>
                    <select name="date_filter" id="date_filter" class="mb-2">
                        <option value="today" {{ request('date_filter', 'today') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Pilih Tanggal</option>
                        <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>Semua Tanggal</option>
                    </select>
                    <input type="date"
                           name="date"
                           id="custom_date"
                           value="{{ request('date', date('Y-m-d')) }}"
                           class="{{ request('date_filter') == 'custom' ? '' : 'hidden' }}"
                           {{ request('date_filter') == 'custom' ? '' : 'disabled' }}>
                </div>
                <div>
                    <label>Role</label>
                    <select name="role">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role')=='user' ? 'selected':'' }}>Pekerja</option>
                        <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                        <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                        <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="tepat waktu" {{ request('status')=='tepat waktu'?'selected':'' }}>Tepat Waktu</option>
                        <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                    </select>
                </div>
                <div>
                    <label>Pencarian</label>
                    <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="flex-between mt-6" style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn-primary">Terapkan Filter</button>
                <a href="{{ route('manager.log') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- STATISTIK KEHADIRAN --}}
    <div class="stats-card">
        <div class="stats-header">
            <h3>Statistik Kehadiran</h3>
            <p>
                @if($dateFilter == 'all')
                    Seluruh Riwayat Absensi
                @else
                    {{ \Carbon\Carbon::parse($displayDate)->translatedFormat('d F Y') }}
                @endif
            </p>
        </div>
        <div class="chart-circle-wrap">
            <div class="chart-container">
                <canvas id="attendanceChart" style="width:100%; height:100%;"></canvas>
                <div class="chart-center-text">
                    <div class="total">{{ $totalHadir + $totalTerlambat + $totalAlpha }}</div>
                    <div class="label">Total Absensi</div>
                </div>
            </div>
            <div class="legend-list">
                <div class="legend-item">
                    <div class="legend-color" style="background: #2d6a4f;"></div>
                    <span class="legend-text">Tepat Waktu</span>
                    <span class="legend-value">{{ $totalHadir }}</span>
                    <span class="legend-percent">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalHadir / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #eab308;"></div>
                    <span class="legend-text">Terlambat</span>
                    <span class="legend-value">{{ $totalTerlambat }}</span>
                    <span class="legend-percent">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalTerlambat / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #dc2626;"></div>
                    <span class="legend-text">Alpha</span>
                    <span class="legend-value">{{ $totalAlpha }}</span>
                    <span class="legend-percent">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalAlpha / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-box">
        <div class="table-header">
            <h3>
                @if($dateFilter == 'all')
                    Log Absensi – Semua Tanggal
                @else
                    Log Absensi – {{ \Carbon\Carbon::parse($displayDate)->translatedFormat('d F Y') }}
                @endif
            </h3>
            <span class="period-badge">Update: {{ now('Asia/Jakarta')->format('H:i') }} WIB</span>
        </div>

        @if($attendances->count())
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Role</th>
                        @if($dateFilter == 'all')<th>Tanggal</th>@endif
                        <th>Absen Masuk</th>
                        <th>Absen Keluar</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td class="td-name">
                            {{ $attendance->user->name }}
                            <div class="td-email">{{ $attendance->user->email ?? $attendance->user->no_hp }}</div>
                        </td>
                        <td>
                            @php $role = $attendance->user->role; @endphp
                            <span class="badge 
                                @if($role=='user') badge-role-user
                                @elseif($role=='security') badge-role-security
                                @elseif($role=='cleaning') badge-role-cleaning
                                @else badge-role-kantoran @endif">
                                {{ ucfirst($role) }}
                            </span>
                        </td>
                        @if($dateFilter == 'all')
                        <td>
                            <span style="font-size:0.8rem; color:#57534e;">
                                {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        @endif
                        <td>
                            @if($attendance->check_in)
                                <span class="time-val">{{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }} <span style="font-size:0.65rem; color:#a1a09a;">WIB</span></span>
                            @else
                                <span class="dash">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance->check_out)
                                <span class="time-val">{{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }} <span style="font-size:0.65rem; color:#a1a09a;">WIB</span></span>
                            @elseif($attendance->check_in)
                                <span class="badge" style="background:#fff2cf; color:#b56a0c;">Belum Checkout</span>
                            @else
                                <span class="dash">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance->status)
                                <span class="badge {{ $attendance->status=='tepat waktu' ? 'badge-status-tepat' : 'badge-status-terlambat' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            @else
                                <span class="dash">-</span>
                            @endif
                        </td>
                        <td style="max-width:180px;">
                            <span style="font-size:0.75rem; color:#78716c;">{{ $attendance->note ?? '-' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">
            {{ $attendances->links() }}
        </div>
        @else
        <div class="empty-state">
            <p>📋 Tidak ada data absensi</p>
            <span>Tidak ditemukan data untuk tanggal atau filter yang dipilih.</span>
        </div>
        @endif
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateFilter = document.getElementById('date_filter');
    const customDate = document.getElementById('custom_date');

    function toggleDateInput() {
        if (dateFilter.value === 'custom') {
            customDate.classList.remove('hidden');
            customDate.disabled = false;
        } else {
            customDate.classList.add('hidden');
            customDate.disabled = true;
        }
    }
    toggleDateInput();
    dateFilter.addEventListener('change', toggleDateInput);

    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const totalHadir = {{ $totalHadir }};
    const totalTerlambat = {{ $totalTerlambat }};
    const totalAlpha = {{ $totalAlpha }};

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Tepat Waktu', 'Terlambat', 'Alpha'],
            datasets: [{
                data: [totalHadir, totalTerlambat, totalAlpha],
                backgroundColor: ['#2d6a4f', '#eab308', '#dc2626'],
                borderWidth: 0,
                hoverOffset: 10,
                cutout: '65%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = totalHadir + totalTerlambat + totalAlpha;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection