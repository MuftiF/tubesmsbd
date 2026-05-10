@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');

    .laporan-wrap * {
        font-family: 'Inter', sans-serif;
    }

    .laporan-wrap {
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

    /* TODAY BANNER */
    .today-banner {
        background: linear-gradient(135deg, #1b4332, #2d6a4f);
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.75rem;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .today-banner .label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #a7f3d0;
        margin-bottom: 0.2rem;
    }
    .today-banner .date {
        font-size: 1.1rem;
        font-weight: 700;
    }
    .today-stats {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }
    .today-stat {
        text-align: center;
    }
    .today-stat .ts-label {
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        color: rgba(255,255,255,0.6);
        margin-bottom: 0.2rem;
    }
    .today-stat .ts-val {
        font-size: 1.4rem;
        font-weight: 700;
        color: white;
        line-height: 1.2;
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

    /* CHARTS */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 1024px) {
        .charts-grid { grid-template-columns: repeat(2, 1fr); }
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
    .bar-row {
        margin-bottom: 1rem;
    }
    .bar-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #78716c;
        margin-bottom: 0.3rem;
    }
    .bar-meta .bar-val {
        font-weight: 600;
        color: #1c1c1c;
    }
    .bar-track {
        width: 100%;
        background: #f1f5f0;
        border-radius: 20px;
        height: 6px;
        overflow: hidden;
    }
    .bar-fill-green {
        background: #2d6a4f;
        height: 100%;
        border-radius: 20px;
    }
    .bar-fill-blue {
        background: #3b82f6;
        height: 100%;
        border-radius: 20px;
    }
    .chart-empty {
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #c9c6c2;
        font-size: 0.85rem;
    }

    /* TABLE - UPDATED STYLE LIKE IMAGE */
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

    /* BADGES WITH ROUNDED PILLS LIKE IMAGE */
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
    .badge-role {
        background: #eef5f0;
        color: #2d6a4f;
    }
    .badge-ok {
        background: #e3f5e9;
        color: #0f6e3f;
    }
    .badge-late {
        background: #fee9e6;
        color: #bc3f2c;
    }
    .badge-leave {
        background: #efebfa;
        color: #5b3ca0;
    }
    .badge-absent {
        background: #f2f2f0;
        color: #6b6b66;
    }
    .badge-pending {
        background: #fff2cf;
        color: #b56a0c;
    }
    .time-val {
        font-weight: 500;
        color: #1c1c1c;
    }
    .weight-val {
        font-weight: 600;
        color: #2d6a4f;
    }
    .dash {
        color: #cbcbc4;
    }
    .photo-link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: #eef2ff;
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #2c5f8a;
        text-decoration: none;
        transition: all 0.2s;
    }
    .photo-link:hover {
        background: #e0e7f7;
        color: #1f4a6e;
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
</style>

<div class="laporan-wrap">
<div style="max-width:1280px;margin:0 auto;">

    <!-- HEADER -->
    <div class="lap-header">
        <h1>Laporan Hasil Panen Sawit</h1>
        <p>Dashboard analisis produktivitas dan kehadiran pekerja sawit</p>
    </div>

    <!-- FILTER BOX -->
    <form method="GET" action="{{ route('manager.laporan') }}" class="filter-box">
        <h3>Filter Laporan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}">
            </div>
            <div>
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}">
            </div>
            <div>
                <label>Role</label>
                <select name="role">
                    <option value="">Semua Role</option>
                    <option value="user" {{ request('role')=='user' ? 'selected':'' }}>Kebun & Panen</option>
                    <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                    <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                    <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                </select>
            </div>
        </div>

        <div style="margin-top:1rem;">
            <label>Tampilkan Data</label>
            <select name="data_type">
                <option value="today" {{ request('data_type', 'today') == 'today' ? 'selected' : '' }}>Hari Ini Saja</option>
                <option value="all" {{ request('data_type') == 'all' ? 'selected' : '' }}>Semua Data (Berdasarkan Filter Tanggal)</option>
            </select>
        </div>

        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
            <button type="submit" class="btn-primary">Terapkan Filter</button>
            <a href="{{ route('manager.laporan') }}" class="btn-secondary">Reset</a>
        </div>
    </form>

    @php
        $selectedRole = request('role');
        $hasPalmAccess = !$selectedRole || $selectedRole == 'user';
        $dataType = request('data_type', 'today');
        $todayDate = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    @endphp

    <!-- TODAY BANNER -->
    @if($dataType == 'today' && $hasPalmAccess)
    <div class="today-banner">
        <div>
            <div class="label">Ringkasan Hari Ini</div>
            <div class="date">{{ $todayDate }}</div>
        </div>
        <div class="today-stats">
            <div class="today-stat">
                <div class="ts-label">Hadir</div>
                <div class="ts-val">{{ $todayAttendanceCount ?? 0 }}</div>
            </div>
            <div class="today-stat">
                <div class="ts-label">Panen</div>
                <div class="ts-val">{{ number_format($todayPalmWeight ?? 0, 1) }} kg</div>
            </div>
            <div class="today-stat">
                <div class="ts-label">Rata-rata</div>
                @php
                    $avgToday = ($todayAttendanceCount > 0 && $todayPalmWeight > 0) 
                        ? number_format($todayPalmWeight / $todayAttendanceCount, 1) 
                        : 0;
                @endphp
                <div class="ts-val">{{ $avgToday }} kg/org</div>
            </div>
        </div>
    </div>
    @endif

    <!-- SUMMARY CARDS -->
    <div class="summary-cards">
        <div class="scard">
            <div class="sc-label">Total Pekerja</div>
            <div class="sc-val">{{ number_format($totalPegawai) }}</div>
        </div>
        <div class="scard">
            <div class="sc-label">Total Berat Sawit</div>
            <div class="sc-val">{{ $hasPalmAccess ? number_format($totalPalmWeight, 1) : '-' }}</div>
            @if($hasPalmAccess)<div class="sc-unit">kilogram</div>@endif
        </div>
        <div class="scard">
            <div class="sc-label">Rata-rata Panen</div>
            <div class="sc-val">{{ $hasPalmAccess ? number_format($averagePalmWeight, 1) : '-' }}</div>
            @if($hasPalmAccess)<div class="sc-unit">kg / pekerja</div>@endif
        </div>

        @if($dataType == 'today')
        <div class="scard">
            <div class="sc-label">Total Kehadiran</div>
            <div class="sc-val">{{ number_format($totalHadir ?? 0) }}</div>
            <div class="sc-unit">hari ini</div>
        </div>
        @else
        <div class="scard" style="opacity:0; pointer-events:none;"></div>
        @endif
    </div>

    <!-- CHARTS -->
    <div class="charts-grid">
        @if($hasPalmAccess)
        <div class="chart-box">
            <h3>Hasil Panen 7 Hari Terakhir</h3>
            @if($dailyPalmWeight->count())
                @php $maxWeight = $dailyPalmWeight->max('total_weight') ?: 1; @endphp
                @foreach($dailyPalmWeight as $daily)
                <div class="bar-row">
                    <div class="bar-meta">
                        <span>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</span>
                        <span class="bar-val">{{ number_format($daily->total_weight, 1) }} kg</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill-green" style="width: {{ ($daily->total_weight / $maxWeight) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="chart-empty">Tidak ada data panen</div>
            @endif
        </div>
        @endif

        <div class="chart-box">
            <h3>Kehadiran 7 Hari Terakhir</h3>
            @if($dailyAttendance->count())
                @foreach($dailyAttendance as $daily)
                <div class="bar-row">
                    <div class="bar-meta">
                        <span>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</span>
                        <span class="bar-val">{{ number_format($daily->total) }} pekerja</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill-blue" style="width: {{ $totalPegawai > 0 ? ($daily->total / $totalPegawai) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="chart-empty">Tidak ada data kehadiran</div>
            @endif
        </div>
    </div>

    <!-- TABLE with UPDATED MODERN UI (inspired by image) -->
    <div class="table-box">
        <div class="table-header">
            <h3>
                @if($dataType == 'today')
                    Detail Kehadiran & Panen Hari Ini
                @else
                    Detail Kehadiran & Panen
                @endif
            </h3>
            @if($dataType == 'today')
                <span class="period-badge">{{ $todayDate }}</span>
            @else
                <span class="period-badge">
                    Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </span>
            @endif
        </div>

        @if($detailedAttendances->count())
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        @if($dataType == 'all')
                        <th>Tanggal</th>
                        @endif
                        <th>Nama Karyawan</th>
                        <th>Role</th>
                        <th>Absen Masuk</th>
                        <th>Absen Keluar</th>
                        <th>Berat Panen</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Bukti Absensi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailedAttendances as $a)
                        @php
                            $panen = \App\Models\CatatanPanen::where('id_pegawai', $a->user_id)
                                ->whereDate('tanggal', $a->date)
                                ->first();
                        @endphp
                        <tr>
                            @if($dataType == 'all')
                            <td>{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                            @endif
                            <td class="td-name">{{ $a->user?->name ?? 'Nama Tidak Diketahui' }}</td>
                            <td><span class="badge badge-role">{{ $a->user?->role ?? 'unknown' }}</span></td>
                            <td>
                                @if($a->check_in)
                                    <span class="time-val">{{ \Carbon\Carbon::parse($a->check_in)->format('H:i') }} <span style="font-size:0.65rem; color:#a1a09a;">WIB</span></span>
                                @else
                                    <span class="dash">-</span>
                                @endif
                            </td>
                            <td>
                                @if($a->check_out)
                                    <span class="time-val">{{ \Carbon\Carbon::parse($a->check_out)->format('H:i') }} <span style="font-size:0.65rem; color:#a1a09a;">WIB</span></span>
                                @else
                                    @if($a->check_in)
                                        <span class="badge badge-pending">Belum Checkout</span>
                                    @else
                                        <span class="dash">-</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($hasPalmAccess && $panen && $panen->berat_kg)
                                    <span class="weight-val">{{ number_format($panen->berat_kg, 1) }} kg</span>
                                @else
                                    <span class="dash">-</span>
                                @endif
                            </td>
                            <td>
                                @if($a->note)
                                    <span style="font-size:0.75rem;">{{ $a->note }}</span>
                                @else
                                    <span class="dash">-</span>
                                @endif
                            </td>
                            <td>
                                @if($a->status == 'tepat waktu')
                                    <span class="badge badge-ok">Hadir</span>
                                @elseif($a->status == 'terlambat')
                                    <span class="badge badge-late">Terlambat</span>
                                @elseif($a->status == 'cuti')
                                    <span class="badge badge-leave">Cuti</span>
                                @else
                                    <span class="badge badge-absent">Alpha</span>
                                @endif
                            </td>
                            <td>
                                @if($a->checkout_photo_path)
                                    <a href="{{ asset('storage/'.$a->checkout_photo_path) }}" target="_blank" class="photo-link">
                                        Lihat
                                    </a>
                                @elseif($panen && $panen->foto_panen)
                                    <a href="{{ asset('storage/'.$panen->foto_panen) }}" target="_blank" class="photo-link">
                                        Lihat
                                    </a>
                                @else
                                    <span class="dash">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">
            {{ $detailedAttendances->links() }}
        </div>
        @else
        <div class="empty-state">
            @if($dataType == 'today')
                <p>Belum ada absensi hari ini</p>
                <span>Data kehadiran akan muncul setelah pekerja melakukan check-in</span>
            @else
                <p>Tidak ada data untuk periode ini</p>
                <span>Coba ubah filter tanggal atau role untuk melihat data</span>
            @endif
        </div>
        @endif
    </div>

</div>
</div>
@endsection