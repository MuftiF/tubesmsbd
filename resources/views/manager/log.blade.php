@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Manager</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Log Absensi Tim</h1>
                    <p class="text-sm text-gray-500 mt-1">Monitoring kehadiran dan aktivitas harian pegawai</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-6 border border-gray-200 shadow-sm">
            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-4">Filter Log Absensi</h3>
            <form action="{{ route('manager.log') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Tanggal</label>
                        <select name="date_filter" id="date_filter" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                            <option value="today" {{ request('date_filter', 'today') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Pilih Tanggal</option>
                            <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>Semua Tanggal</option>
                        </select>
                        <input type="date"
                               name="date"
                               id="custom_date"
                               value="{{ request('date', date('Y-m-d')) }}"
                               class="w-full mt-2 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e] {{ request('date_filter') == 'custom' ? '' : 'hidden' }}"
                               {{ request('date_filter') == 'custom' ? '' : 'disabled' }}>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Role</label>
                        <select name="role" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                            <option value="">Semua Role</option>
                            <option value="user" {{ request('role')=='user' ? 'selected':'' }}>Pekerja</option>
                            <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                            <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                            <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                            <option value="">Semua Status</option>
                            <option value="tepat waktu" {{ request('status')=='tepat waktu'?'selected':'' }}>Tepat Waktu</option>
                            <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Pencarian</label>
                        <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 shadow-md">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('manager.log') }}"
                        class="bg-white text-gray-500 px-5 py-2.5 rounded-xl font-medium text-sm border border-gray-200 hover:border-gray-300 hover:text-gray-700 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Main Grid: Statistik + Tabel --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- Statistik Kehadiran - 1/4 --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-700">Statistik Kehadiran</h3>
                    <p class="text-xs text-gray-400 mt-1 mb-5">
                        @if($dateFilter == 'all')
                            Seluruh Riwayat Absensi
                        @else
                            {{ \Carbon\Carbon::parse($displayDate)->translatedFormat('d F Y') }}
                        @endif
                    </p>

                    <div class="relative w-[160px] h-[160px] mx-auto mb-5">
                        <canvas id="attendanceChart" style="width:100%; height:100%;"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <div class="text-2xl font-bold text-gray-800">{{ $totalHadir + $totalTerlambat + $totalAlpha }}</div>
                            <div class="text-xs text-gray-400">Total</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-[#2c5e4e] flex-shrink-0"></div>
                            <span class="text-sm text-gray-600 flex-1">Tepat Waktu</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ $totalHadir }}</span>
                            <span class="text-xs text-gray-400">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalHadir / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-[#d4a373] flex-shrink-0"></div>
                            <span class="text-sm text-gray-600 flex-1">Terlambat</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ $totalTerlambat }}</span>
                            <span class="text-xs text-gray-400">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalTerlambat / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></div>
                            <span class="text-sm text-gray-600 flex-1">Alpha</span>
                            <span class="font-semibold text-gray-800 text-sm">{{ $totalAlpha }}</span>
                            <span class="text-xs text-gray-400">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalAlpha / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel - 3/4 --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-5 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-800">
                            @if($dateFilter == 'all')
                                Log Absensi – Semua Tanggal
                            @else
                                Log Absensi – {{ \Carbon\Carbon::parse($displayDate)->translatedFormat('d F Y') }}
                            @endif
                        </h3>
                        <span class="bg-[#eaf4f1] text-[#2c5e4e] px-3 py-1 rounded-full text-xs font-medium">
                            Update: {{ now('Asia/Jakarta')->format('H:i') }} WIB
                        </span>
                    </div>

                    @if($attendances->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Nama Karyawan</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Role</th>
                                    @if($dateFilter == 'all')
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal</th>
                                    @endif
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Absen Masuk</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Absen Keluar</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-800 text-sm">{{ $attendance->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $attendance->user->email ?? $attendance->user->no_hp }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php $role = $attendance->user->role; @endphp
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf4f1] text-[#2c5e4e]">
                                            {{ ucfirst($role) }}
                                        </span>
                                    </td>
                                    @if($dateFilter == 'all')
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}</td>
                                    @endif
                                    <td class="px-4 py-3">
                                        @if($attendance->check_in)
                                            <span class="font-medium text-gray-800 text-sm">{{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }} <span class="text-xs text-gray-400">WIB</span></span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($attendance->check_out)
                                            <span class="font-medium text-gray-800 text-sm">{{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }} <span class="text-xs text-gray-400">WIB</span></span>
                                        @elseif($attendance->check_in)
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Belum Checkout</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($attendance->status)
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $attendance->status=='tepat waktu' ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-red-100 text-red-700' }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 max-w-[180px] truncate">{{ $attendance->note ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $attendances->links() }}
                    </div>
                    @else
                    <div class="py-14 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="font-semibold text-sm text-gray-500">Tidak ada data absensi</p>
                        <span class="text-xs">Tidak ditemukan data untuk tanggal atau filter yang dipilih.</span>
                    </div>
                    @endif
                </div>
            </div>

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

    const totalHadir = {{ $totalHadir }};
    const totalTerlambat = {{ $totalTerlambat }};
    const totalAlpha = {{ $totalAlpha }};

    new Chart(document.getElementById('attendanceChart'), {
        type: 'doughnut',
        data: {
            labels: ['Tepat Waktu', 'Terlambat', 'Alpha'],
            datasets: [{
                data: [totalHadir, totalTerlambat, totalAlpha],
                backgroundColor: ['#2c5e4e', '#d4a373', '#ef4444'],
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
                    backgroundColor: '#1a2e25',
                    padding: 10,
                    titleColor: '#fff',
                    bodyColor: '#a7c4bb',
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