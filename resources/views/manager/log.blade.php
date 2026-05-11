@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Log Absensi Tim</h1>
            <p class="text-sm text-stone-500 mt-1">Monitoring kehadiran dan aktivitas harian pegawai</p>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-8 border border-stone-200 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wide text-stone-400 mb-4">Filter Log Absensi</h3>
            <form action="{{ route('manager.log') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-stone-600 mb-1">Tanggal</label>
                        <select name="date_filter" id="date_filter" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                            <option value="today" {{ request('date_filter', 'today') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Pilih Tanggal</option>
                            <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>Semua Tanggal</option>
                        </select>
                        <input type="date"
                               name="date"
                               id="custom_date"
                               value="{{ request('date', date('Y-m-d')) }}"
                               class="w-full mt-2 border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f] {{ request('date_filter') == 'custom' ? '' : 'hidden' }}"
                               {{ request('date_filter') == 'custom' ? '' : 'disabled' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-600 mb-1">Role</label>
                        <select name="role" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                            <option value="">Semua Role</option>
                            <option value="user" {{ request('role')=='user' ? 'selected':'' }}>Pekerja</option>
                            <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                            <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                            <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-600 mb-1">Status</label>
                        <select name="status" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                            <option value="">Semua Status</option>
                            <option value="tepat waktu" {{ request('status')=='tepat waktu'?'selected':'' }}>Tepat Waktu</option>
                            <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-600 mb-1">Pencarian</label>
                        <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="bg-[#2d6a4f] text-white px-5 py-2 rounded-xl font-semibold text-sm hover:bg-[#235c44] transition">Terapkan Filter</button>
                    <a href="{{ route('manager.log') }}" class="bg-transparent text-stone-500 px-4 py-2 rounded-xl font-medium text-sm border border-stone-200 hover:border-stone-300 hover:text-stone-700 transition">Reset</a>
                </div>
            </form>
        </div>

        {{-- Statistik Kehadiran --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-8 border border-stone-200 shadow-sm">
            <div class="text-center mb-6">
                <h3 class="text-base font-bold text-stone-800">Statistik Kehadiran</h3>
                <p class="text-xs text-stone-400 mt-1">
                    @if($dateFilter == 'all')
                        Seluruh Riwayat Absensi
                    @else
                        {{ \Carbon\Carbon::parse($displayDate)->translatedFormat('d F Y') }}
                    @endif
                </p>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12">
                <div class="relative w-[200px] h-[200px]">
                    <canvas id="attendanceChart" style="width:100%; height:100%;"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <div class="text-2xl md:text-3xl font-extrabold text-stone-800">{{ $totalHadir + $totalTerlambat + $totalAlpha }}</div>
                        <div class="text-[0.65rem] font-medium text-stone-400">Total Absensi</div>
                    </div>
                </div>
                <div class="space-y-3 min-w-[160px]">
                    <div class="flex items-center gap-3">
                        <div class="w-3.5 h-3.5 rounded-full bg-[#2d6a4f]"></div>
                        <span class="text-sm font-medium text-stone-600">Tepat Waktu</span>
                        <span class="font-bold text-stone-800 ml-auto">{{ $totalHadir }}</span>
                        <span class="text-xs text-stone-400">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalHadir / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3.5 h-3.5 rounded-full bg-[#eab308]"></div>
                        <span class="text-sm font-medium text-stone-600">Terlambat</span>
                        <span class="font-bold text-stone-800 ml-auto">{{ $totalTerlambat }}</span>
                        <span class="text-xs text-stone-400">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalTerlambat / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3.5 h-3.5 rounded-full bg-[#dc2626]"></div>
                        <span class="text-sm font-medium text-stone-600">Alpha</span>
                        <span class="font-bold text-stone-800 ml-auto">{{ $totalAlpha }}</span>
                        <span class="text-xs text-stone-400">({{ $totalHadir + $totalTerlambat + $totalAlpha > 0 ? round(($totalAlpha / ($totalHadir + $totalTerlambat + $totalAlpha)) * 100) : 0 }}%)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 p-5 pb-3 border-b border-stone-100">
                <h3 class="text-sm font-bold text-stone-800">
                    @if($dateFilter == 'all')
                        Log Absensi – Semua Tanggal
                    @else
                        Log Absensi – {{ \Carbon\Carbon::parse($displayDate)->translatedFormat('d F Y') }}
                    @endif
                </h3>
                <span class="bg-[#f8f6f2] px-3 py-1 rounded-full text-xs font-medium text-stone-500">Update: {{ now('Asia/Jakarta')->format('H:i') }} WIB</span>
            </div>

            @if($attendances->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#fafaf8] border-b border-stone-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Nama Karyawan</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Role</th>
                            @if($dateFilter == 'all')
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Tanggal</th>
                            @endif
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Absen Masuk</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Absen Keluar</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Status</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr class="border-b border-stone-100 hover:bg-[#fefcf7] transition">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-stone-800 text-sm">{{ $attendance->user->name }}</div>
                                <div class="text-xs text-stone-400">{{ $attendance->user->email ?? $attendance->user->no_hp }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @php $role = $attendance->user->role; @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    @if($role=='user') bg-emerald-50 text-[#2d6a4f]
                                    @elseif($role=='security') bg-blue-50 text-blue-800
                                    @elseif($role=='cleaning') bg-amber-50 text-amber-800
                                    @else bg-purple-50 text-purple-800 @endif">
                                    {{ ucfirst($role) }}
                                </span>
                            </td>
                            @if($dateFilter == 'all')
                            <td class="px-4 py-3 text-sm text-stone-600">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}</td>
                            @endif
                            <td class="px-4 py-3">
                                @if($attendance->check_in)
                                    <span class="font-medium text-stone-800 text-sm">{{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }} <span class="text-xs text-stone-400">WIB</span></span>
                                @else
                                    <span class="text-stone-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($attendance->check_out)
                                    <span class="font-medium text-stone-800 text-sm">{{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }} <span class="text-xs text-stone-400">WIB</span></span>
                                @elseif($attendance->check_in)
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">Belum Checkout</span>
                                @else
                                    <span class="text-stone-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($attendance->status)
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $attendance->status=='tepat waktu' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                @else
                                    <span class="text-stone-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-stone-500 max-w-[180px] truncate">{{ $attendance->note ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-stone-100">
                {{ $attendances->links() }}
            </div>
            @else
            <div class="py-12 text-center text-stone-400">
                <p class="font-semibold text-sm text-stone-500">📋 Tidak ada data absensi</p>
                <span class="text-xs">Tidak ditemukan data untuk tanggal atau filter yang dipilih.</span>
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

    const totalHadir = {{ $totalHadir }};
    const totalTerlambat = {{ $totalTerlambat }};
    const totalAlpha = {{ $totalAlpha }};

    new Chart(document.getElementById('attendanceChart'), {
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