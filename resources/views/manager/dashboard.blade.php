@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Admin Dashboard</h1>
            <p class="text-sm text-stone-500 mt-1">Monitoring produktivitas dan aktivitas tim kebun sawit</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-stone-400 mb-2">Absensi Hari Ini</div>
                <div class="text-2xl md:text-3xl font-bold text-[#2d6a4f]">{{ number_format($hadirHariIni ?? 0) }}</div>
                <div class="text-xs font-medium text-stone-400 mt-1">Total Kehadiran</div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-stone-400 mb-2">Total Tim</div>
                <div class="text-2xl md:text-3xl font-bold text-[#2563eb]">{{ number_format($totalTim ?? 0) }}</div>
                <div class="text-xs font-medium text-stone-400 mt-1">Tim Aktif</div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-stone-400 mb-2">Produksi Hari Ini</div>
                <div class="text-2xl md:text-3xl font-bold text-[#2d6a4f]">{{ number_format($produksiHariIni ?? 0, 1) }} <span class="text-sm">kg</span></div>
                <div class="text-xs font-medium text-stone-400 mt-1">Total Panen</div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-stone-400 mb-2">Alpha</div>
                <div class="text-2xl md:text-3xl font-bold text-[#dc2626]">{{ number_format($totalAlpha ?? 0) }}</div>
                <div class="text-xs font-medium text-stone-400 mt-1">Tidak Hadir</div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
            {{-- Line Chart --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 md:p-6 border border-stone-200">
                <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-stone-700">Produktivitas Harian</h3>
                        <p class="text-xs text-stone-400">Dalam satuan kilogram (kg)</p>
                    </div>
                    <select id="filterType" class="border border-stone-200 rounded-full px-4 py-1.5 text-xs bg-white text-stone-600 focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        <option value="7">7 Hari</option>
                        <option value="30">1 Bulan</option>
                        <option value="365">1 Tahun</option>
                    </select>
                </div>
                <div class="h-[280px] relative">
                    <canvas id="productivityChart"></canvas>
                </div>
            </div>

            {{-- Donut Chart --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200">
                <h3 class="text-sm font-bold text-stone-700 mb-4">Status Absensi Hari Ini</h3>
                <div class="h-[200px] relative">
                    <canvas id="attendanceChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#2d6a4f]"></span><span class="text-stone-600">Hadir</span></div>
                        <span class="font-semibold text-stone-800">{{ $hadirHariIni ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#eab308]"></span><span class="text-stone-600">Terlambat</span></div>
                        <span class="font-semibold text-stone-800">{{ $totalTerlambat ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#dc2626]"></span><span class="text-stone-600">Alpha</span></div>
                        <span class="font-semibold text-stone-800">{{ $totalAlpha ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Action --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('manager.laporan') }}" class="bg-white rounded-2xl p-4 border border-stone-200 flex items-center justify-between group hover:bg-[#fefcf7] hover:border-stone-300 transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-[#2d6a4f] text-xl"><i class="fas fa-chart-line"></i></div>
                    <div><h4 class="font-bold text-stone-800 text-sm">Laporan</h4><p class="text-xs text-stone-400 mt-0.5">Lihat laporan harian</p></div>
                </div>
                <div class="text-stone-300 text-sm"><i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('manager.log') }}" class="bg-white rounded-2xl p-4 border border-stone-200 flex items-center justify-between group hover:bg-[#fefcf7] hover:border-stone-300 transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-[#2d6a4f] text-xl"><i class="fas fa-clipboard-list"></i></div>
                    <div><h4 class="font-bold text-stone-800 text-sm">Log Absensi</h4><p class="text-xs text-stone-400 mt-0.5">Aktivitas pegawai</p></div>
                </div>
                <div class="text-stone-300 text-sm"><i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('manager.pegawai') }}" class="bg-white rounded-2xl p-4 border border-stone-200 flex items-center justify-between group hover:bg-[#fefcf7] hover:border-stone-300 transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-800 text-xl"><i class="fas fa-user-friends"></i></div>
                    <div><h4 class="font-bold text-stone-800 text-sm">Pegawai</h4><p class="text-xs text-stone-400 mt-0.5">Kelola data tim</p></div>
                </div>
                <div class="text-stone-300 text-sm"><i class="fas fa-arrow-right"></i></div>
            </a>
        </div>

        {{-- Top Performer & Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
            {{-- Top Performer --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200">
                <h3 class="text-sm font-bold text-stone-700">Top Performer Hari Ini</h3>
                <p class="text-xs text-stone-400 mt-1">Berdasarkan hasil produksi</p>
                <div class="mt-4 space-y-3">
                    @forelse($topPerformers as $index => $performer)
                    <div class="flex items-center justify-between p-3 rounded-xl border border-stone-100 hover:bg-[#fefcf7] transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center font-bold text-[#2d6a4f]">{{ $index + 1 }}</div>
                            <div><h5 class="font-bold text-stone-800 text-sm">{{ $performer->name }}</h5><p class="text-xs text-stone-400">{{ ucfirst($performer->role) }}</p></div>
                        </div>
                        <div class="font-bold text-[#2d6a4f] text-sm">{{ number_format($performer->total_produksi ?? 0, 1) }} kg</div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-stone-400">
                        <i class="fas fa-trophy text-3xl mb-2 block"></i>
                        <p class="font-semibold text-sm">Belum ada data produksi hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Team Activity --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 md:p-6 border border-stone-200">
                <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
                    <div><h3 class="text-sm font-bold text-stone-700">Aktivitas Tim Terbaru</h3><p class="text-xs text-stone-400">Monitoring check in dan produksi tim</p></div>
                    <a href="{{ route('manager.log') }}" class="text-xs font-semibold text-[#2d6a4f] hover:underline">Lihat Semua →</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentActivities as $activity)
                    @php
                        $prod = $activity->produksi_harian ?? 0;
                        $efficiency = $prod > 20 ? 'Tinggi' : ($prod > 10 ? 'Sedang' : 'Rendah');
                        $effClass = $prod > 20 ? 'bg-emerald-100 text-emerald-800' : ($prod > 10 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
                    @endphp
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4 rounded-xl border border-stone-100 hover:bg-[#fefcf7] transition">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-[#2d6a4f] text-xl"><i class="fas fa-user-check"></i></div>
                            <div><h5 class="font-bold text-stone-800 text-sm">{{ $activity->user->name }}</h5><span class="inline-block bg-emerald-50 text-[#2d6a4f] text-xs font-semibold px-2 py-0.5 rounded-full mt-1">{{ ucfirst($activity->user->role) }}</span></div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs text-stone-500">
                            <span><i class="fas fa-clock text-stone-400 mr-1"></i> {{ \Carbon\Carbon::parse($activity->check_in)->format('H:i') }} WIB</span>
                            @if($activity->produksi_harian)<span><i class="fas fa-seedling text-stone-400 mr-1"></i> {{ number_format($activity->produksi_harian, 1) }} kg</span>@endif
                        </div>
                        @if($activity->produksi_harian)
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $effClass }}">{{ $efficiency }}</span>
                        @endif
                    </div>
                    @empty
                    <div class="py-8 text-center text-stone-400">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i>
                        <p class="font-semibold text-sm">Belum Ada Aktivitas</p>
                        <span class="text-xs">Aktivitas terbaru tim akan muncul di sini</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Export Data --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200">
            <div class="flex flex-wrap justify-between items-center gap-3 mb-5">
                <div><h3 class="text-sm font-bold text-stone-700">Export Data Aktivitas</h3><p class="text-xs text-stone-400">Download laporan absensi dan aktivitas pegawai</p></div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-[#2d6a4f] text-xl"><i class="fas fa-file-export"></i></div>
            </div>
            <form action="{{ route('export.all') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div><label class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">Dari Tanggal</label><input type="date" name="from" value="{{ date('Y-m-d', strtotime('-1 week')) }}" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]"></div>
                    <div><label class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">Sampai Tanggal</label><input type="date" name="to" value="{{ date('Y-m-d') }}" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]"></div>
                    <div><button type="submit" class="w-full bg-[#2d6a4f] text-white px-4 py-2 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 hover:bg-[#235f48] transition"><i class="fas fa-download"></i> Export CSV</button></div>
                    <div><a href="{{ route('export.all.everything') }}" class="w-full bg-stone-800 text-white px-4 py-2 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 hover:bg-stone-700 transition"><i class="fas fa-database"></i> Export Semua Data</a></div>
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
        if (type == '365') return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }

    function filterData(days) {
        const now = new Date();
        return allData.filter(item => {
            const itemDate = new Date(item.tanggal);
            const diffDays = (now - itemDate) / (1000 * 60 * 60 * 24);
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

        if (productivityChart) productivityChart.destroy();

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
                    tooltip: { backgroundColor: '#1e1e1e', padding: 10, titleColor: '#fff', bodyColor: '#fff', callbacks: { label: (ctx) => ctx.parsed.y + ' kg' } }
                },
                scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: (val) => val + ' kg' } }, x: { grid: { display: false } } }
            }
        });
    }

    renderChart(7);
    document.getElementById('filterType').addEventListener('change', function() { renderChart(parseInt(this.value)); });

    new Chart(document.getElementById('attendanceChart').getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['Hadir', 'Terlambat', 'Alpha'], datasets: [{ data: [{{ $hadirHariIni ?? 0 }}, {{ $totalTerlambat ?? 0 }}, {{ $totalAlpha ?? 0 }}], backgroundColor: ['#2d6a4f', '#eab308', '#dc2626'], borderWidth: 0, cutout: '70%' }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.parsed}` } } } }
    });
});
</script>
@endsection