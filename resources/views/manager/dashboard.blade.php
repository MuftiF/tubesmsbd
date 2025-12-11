@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    {{-- HEADER --}}
    <div class="text-center mb-12">
        <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
            <span class="text-5xl">👨‍💼</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Manager Dashboard</h1>
        <p class="text-gray-500">Monitoring tim dan produktivitas harian kebun sawit</p>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-blue-500">
            <p class="text-3xl font-bold text-blue-600">{{ $totalTim ?? 0 }}</p>
            <p class="text-gray-600 text-sm mt-1">Total Tim</p>
        </div>
        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-green-500">
            <p class="text-3xl font-bold text-green-600">{{ $hadirHariIni ?? 0 }}</p>
            <p class="text-gray-600 text-sm mt-1">Hadir Hari Ini</p>
        </div>
        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-yellow-500">
            <p class="text-3xl font-bold text-yellow-600">{{ number_format($produksiHariIni ?? 0, 1) }} kg</p>
            <p class="text-gray-600 text-sm mt-1">Produksi Hari Ini</p>
        </div>
        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-red-500">
            <p class="text-3xl font-bold text-red-600">{{ $totalAlpha ?? 0 }}</p>
            <p class="text-gray-600 text-sm mt-1">Alpha</p>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

        {{-- AKSI CEPAT --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-5">Aksi Cepat</h3>
            <div class="space-y-4">
                <a href="{{ route('manager.laporan') }}"
                   class="block bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-4 px-4 text-center font-semibold shadow-sm transform hover:scale-[1.03] transition">
                    <div class="text-2xl mb-1">📊</div>Laporan
                </a>
                <a href="{{ route('manager.log') }}"
                   class="block bg-green-600 hover:bg-green-700 text-white rounded-lg py-4 px-4 text-center font-semibold shadow-sm transform hover:scale-[1.03] transition">
                    <div class="text-2xl mb-1">📋</div>Log Absensi
                </a>
                <a href="{{ route('manager.pegawai') }}"
                   class="block bg-purple-600 hover:bg-purple-700 text-white rounded-lg py-4 px-4 text-center font-semibold shadow-sm transform hover:scale-[1.03] transition">
                    <div class="text-2xl mb-1">👥</div>Pegawai
                </a>
            </div>
        </div>

        {{-- GRAFIK PRODUKTIVITAS --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Produktivitas Harian (kg)</h3>
                <select id="chartRange"
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="14">14 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                </select>
            </div>

            <div class="h-64">
                <canvas id="productivityChart"></canvas>
            </div>

            <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                    <p class="text-sm text-gray-600">Rata-rata Harian</p>
                    <p class="text-xl font-bold text-green-700" id="avgDaily">
                        {{ number_format($avgProduksi ?? 0, 1) }} kg
                    </p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                    <p class="text-sm text-gray-600">Total Bulan Ini</p>
                    <p class="text-xl font-bold text-blue-700" id="monthlyTotal">
                        {{ number_format($totalProduksiBulanIni ?? 0, 1) }} kg
                    </p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                    <p class="text-sm text-gray-600">Puncak Produksi</p>
                    <p class="text-xl font-bold text-yellow-700" id="peakProduction">
                        {{ number_format($peakProduksi ?? 0, 1) }} kg
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- RINGKASAN & RANKING --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

        {{-- RINGKASAN --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-5">Ringkasan Hari Ini</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-green-50 px-4 py-3 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <span class="text-green-600 text-2xl mr-3">👥</span>
                        <p class="font-semibold text-gray-800">Kehadiran</p>
                    </div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $totalTim > 0 ? round(($hadirHariIni / $totalTim) * 100) : 0 }}%
                    </span>
                </div>

                <div class="flex justify-between items-center bg-blue-50 px-4 py-3 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <span class="text-blue-600 text-2xl mr-3">🌴</span>
                        <p class="font-semibold text-gray-800">Produktivitas</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold">
                        @if($produksiHariIni > 100)
                            Tinggi
                        @elseif($produksiHariIni > 50)
                            Sedang
                        @else
                            Rendah
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center bg-yellow-50 px-4 py-3 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <span class="text-yellow-600 text-2xl mr-3">⏰</span>
                        <p class="font-semibold text-gray-800">Keterlambatan</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $totalTerlambat ?? 0 }}
                    </span>
                </div>

                <div class="flex justify-between items-center bg-purple-50 px-4 py-3 rounded-lg border border-purple-200">
                    <div class="flex items-center">
                        <span class="text-purple-600 text-2xl mr-3">📈</span>
                        <p class="font-semibold text-gray-800">Trend Produksi</p>
                    </div>
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $trend ?? 'Stabil' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- TOP PERFORMER --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold text-gray-800">Top Performer Hari Ini</h3>
                <span class="text-sm text-gray-500">Berdasarkan Produksi</span>
            </div>

            <div class="space-y-3">
                @forelse($topPerformers as $index => $performer)
                <div class="flex items-center justify-between p-3 rounded-lg border
                    {{ $index < 3 ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full
                            {{ $index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-600' }}
                            flex items-center justify-center font-bold mr-3">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $performer->name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($performer->role) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-700">
                            {{ number_format($performer->total_produksi ?? 0, 1) }} kg
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $performer->check_in_time ?? 'Belum absen' }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-gray-500">Belum ada data produksi hari ini</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- EXPORT DATA --}}
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-10">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Data Semua Aktivitas</h3>
        <form action="{{ route('export.all') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="from" required
                       class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="to" required
                       class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-3 shadow-sm transform hover:scale-[1.03] transition">
                📥 Export CSV
            </button>
            <a href="{{ route('export.all.everything') }}"
                class="w-full bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg px-4 py-3 shadow-sm transform hover:scale-[1.02] transition text-center">
                    Export Semua Data (All Time)
                </a>

        </form>
    </div>

    {{-- TEAM ACTIVITY --}}
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold text-gray-800">Aktivitas Tim Terbaru</h3>
            <a href="{{ route('manager.log') }}"
               class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                Lihat Semua →
            </a>
        </div>

        <div class="space-y-3">
            @foreach($recentActivities as $activity)
            <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-green-600 text-xl">✓</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $activity->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ ucfirst($activity->user->role) }} |
                            Check In: {{ \Carbon\Carbon::parse($activity->check_in)->format('H:i') }}
                            @if($activity->produksi_harian)
                                | Produksi: {{ number_format($activity->produksi_harian, 1) }} kg
                            @endif
                        </p>
                    </div>
                </div>
                @if($activity->produksi_harian)
                <div class="text-right">
                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ number_format($activity->produksi_harian, 1) }} kg
                    </span>
                    <p class="text-xs text-gray-500 mt-1">
                        Efisiensi:
                        @if($activity->produksi_harian > 20)
                            <span class="text-green-600 font-bold">Tinggi</span>
                        @elseif($activity->produksi_harian > 10)
                            <span class="text-yellow-600 font-bold">Sedang</span>
                        @else
                            <span class="text-red-600 font-bold">Rendah</span>
                        @endif
                    </p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>



{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari controller (pastikan Anda mengirim data ini dari controller)
    const productivityData = @json($produktivitasData ?? []);
    
    // Format tanggal untuk label
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'short' 
        });
    };
    
    // 1. GRAFIK PRODUKTIVITAS HARIAN
    const productivityCtx = document.getElementById('productivityChart').getContext('2d');
    
    // Siapkan data awal (7 hari terakhir)
    let chartDays = 7;
    let filteredData = productivityData.slice(-chartDays);
    
    const productivityChart = new Chart(productivityCtx, {
        type: 'line',
        data: {
            labels: filteredData.map(item => formatDate(item.tanggal)),
            datasets: [{
                label: 'Produksi Sawit (kg)',
                data: filteredData.map(item => item.total_produksi),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Produksi: ${context.raw} kg`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Kilogram (kg)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' kg';
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            }
        }
    });
    
    // Event listener untuk filter range
    document.getElementById('chartRange').addEventListener('change', function(e) {
        chartDays = parseInt(e.target.value);
        filteredData = productivityData.slice(-chartDays);
        
        // Update chart
        productivityChart.data.labels = filteredData.map(item => formatDate(item.tanggal));
        productivityChart.data.datasets[0].data = filteredData.map(item => item.total_produksi);
        productivityChart.update();
        
        // Update statistik
        updateStatistics(filteredData);
    });
    
    // Fungsi untuk update statistik
    function updateStatistics(data) {
        if (data.length === 0) return;
        
        // Hitung rata-rata
        const total = data.reduce((sum, item) => sum + parseFloat(item.total_produksi), 0);
        const avg = total / data.length;
        
        // Temukan puncak produksi
        const peak = Math.max(...data.map(item => item.total_produksi));
        
        // Hitung trend
        let trend = 'Stabil';
        if (data.length >= 2) {
            const latest = data[data.length - 1].total_produksi;
            const previous = data[data.length - 2].total_produksi;
            const change = ((latest - previous) / previous) * 100;
            
            if (change > 10) trend = 'Naik';
            else if (change < -10) trend = 'Turun';
        }
        
        // Update UI
        document.getElementById('avgDaily').textContent = avg.toFixed(1) + ' kg';
        document.getElementById('peakProduction').textContent = peak.toFixed(1) + ' kg';
        document.getElementById('trendIndicator').textContent = trend;
    }
    
    // Inisialisasi statistik
    updateStatistics(filteredData);
});
</script>
@endsection
