@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Laporan Manager - Hasil Panen Sawit</h1>
        <p class="text-gray-500 text-sm mt-2">Dashboard analisis produktivitas dan kehadiran tim Anda</p>
    </div>

    <!-- FILTER BOX -->
    <form method="GET" action="{{ route('manager.laporan') }}" 
        class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 mb-10">

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan Manager</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-600">Dari Tanggal</label>
                <input type="date" name="start_date" 
                       value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Sampai Tanggal</label>
                <input type="date" name="end_date" 
                       value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Role</label>
                <select name="role"
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Role</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Kebun & Panen</option>
                    <option value="security" {{ request('role') == 'security' ? 'selected' : '' }}>Security</option>
                    <option value="cleaning" {{ request('role') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                    <option value="kantoran" {{ request('role') == 'kantoran' ? 'selected' : '' }}>Administrasi</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow transition duration-200">
                Terapkan Filter
            </button>

            <a href="{{ route('manager.laporan') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold shadow transition duration-200">
                Reset Filter
            </a>
        </div>
    </form>

    @php
        $selectedRole = request('role');
        $hasPalmAccess = !$selectedRole || $selectedRole == 'user';
        $showPalmStats = $hasPalmAccess;
        $showPalmTable = $hasPalmAccess; // Menentukan apakah menampilkan kolom produksi di tabel
    @endphp

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 font-medium">Total Pegawai</p>
            <p class="text-3xl font-bold text-blue-700 mt-2">{{ $totalPegawai }}</p>
            <p class="text-xs text-gray-400 mt-1">Semua tim Anda</p>
        </div>

        @if($showPalmStats)
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 font-medium">Total Berat Sawit</p>
            <p class="text-3xl font-bold text-green-700 mt-2">
                {{ number_format($totalPalmWeight, 1) . ' kg' }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Produksi total</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 font-medium">Rata-rata Panen</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">
                {{ number_format($averagePalmWeight, 1) . ' kg' }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Per pekerja</p>
        </div>
        @else
        <!-- Kartu placeholder untuk role non-user -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 font-medium">Jumlah Hadir</p>
            <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalHadir }}</p>
            <p class="text-xs text-gray-400 mt-1">Pegawai aktif</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 font-medium">Rate Kehadiran</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $totalPegawai > 0 ? round(($totalHadir / $totalPegawai) * 100) : 0 }}%
            </p>
            <p class="text-xs text-gray-400 mt-1">Persentase hadir</p>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
            <p class="text-sm text-gray-500 font-medium">Kehadiran</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $totalHadir }}</p>
            <p class="text-xs text-gray-400 mt-1">Pegawai hadir</p>
        </div>

    </div>

    <!-- CHARTS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

        @if($showPalmStats && $dailyPalmWeight->count())
        <!-- PANEN HARIAN -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Hasil Panen 7 Hari Terakhir</h3>
                <span class="text-sm text-gray-500">{{ $startDate->format('d M') }} - {{ $endDate->format('d M') }}</span>
            </div>

            <div class="space-y-3">
                @php $maxWeight = $dailyPalmWeight->max('total_weight'); @endphp

                @foreach($dailyPalmWeight as $daily)
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span class="font-medium">{{ \Carbon\Carbon::parse($daily->date)->format('d M') }}</span>
                        <span class="font-bold text-green-700">{{ number_format($daily->total_weight, 1) }} kg</span>
                    </div>
                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-full rounded-full transition-all duration-500"
                            style="width: {{ $maxWeight > 0 ? ($daily->total_weight/$maxWeight*100) : 0 }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @elseif($showPalmStats)
        <!-- PANEN HARIAN - EMPTY STATE -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Hasil Panen 7 Hari Terakhir</h3>
                <span class="text-sm text-gray-500">{{ $startDate->format('d M') }} - {{ $endDate->format('d M') }}</span>
            </div>
            <div class="h-48 flex flex-col items-center justify-center text-gray-400">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Tidak ada data panen</p>
                <p class="text-sm mt-1">Pada periode yang dipilih</p>
            </div>
        </div>
        @else

        @endif

        <!-- KEHADIRAN HARIAN -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Kehadiran 7 Hari Terakhir</h3>
                <span class="text-sm text-gray-500">{{ $startDate->format('d M') }} - {{ $endDate->format('d M') }}</span>
            </div>

            @if($dailyAttendance->count())
            <div class="space-y-3">
                @foreach($dailyAttendance as $daily)
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span class="font-medium">{{ \Carbon\Carbon::parse($daily->date)->format('d M') }}</span>
                        <span class="font-bold text-blue-700">{{ $daily->total }} pekerja</span>
                    </div>
                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-500"
                            style="width: {{ $totalPegawai > 0 ? ($daily->total/$totalPegawai*100) : 0 }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="h-48 flex flex-col items-center justify-center text-gray-400">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 1.75a.75.75 0 00-1.5 0v2.5a.75.75 0 001.5 0v-2.5z"></path>
                </svg>
                <p>Tidak ada data kehadiran</p>
                <p class="text-sm mt-1">Pada periode yang dipilih</p>
            </div>
            @endif
        </div>

    </div>

    <!-- PER ROLE SECTION - Hanya tampil jika filter Semua Role atau User -->
    @if($showPalmStats && $palmWeightByRole && $palmWeightByRole->count())
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 mb-10">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Produksi Sawit</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($palmWeightByRole as $role => $data)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-gray-800 capitalize">
                        @switch($role)
                            @case('user') Kebun @break
                            @case('security') Security @break
                            @case('cleaning') Cleaning @break
                            @case('kantoran') Administrasi @break
                            @default {{ $role }}
                        @endswitch
                    </h4>
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                        {{ $data['total_workers'] ?? 0 }} orang
                    </span>
                </div>
                
                <p class="text-2xl font-bold text-green-700 mb-1">
                    {{ number_format($data['total_weight'] ?? 0, 1) }} kg
                </p>
                
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Rata-rata:</span>
                    <span class="font-medium">{{ number_format($data['avg_weight'] ?? 0, 1) }} kg</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- DETAIL TABLE -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 mb-10">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Detail Laporan</h3>
            <div class="text-sm text-gray-500">
                Menampilkan {{ $detailedAttendances->count() }} dari {{ $detailedAttendances->total() }} data
            </div>
        </div>

        @if($detailedAttendances->count())
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr class="text-gray-700 text-sm">
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Nama Pegawai</th>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium">Jam Masuk</th>
                        <th class="px-4 py-3 font-medium">Jam Keluar</th>
                        @if($showPalmTable)
                        <th class="px-4 py-3 font-medium">Produksi (kg)</th>
                        @endif
                        <!-- Kolom Status dihapus dari sini -->
                        <th class="px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm divide-y divide-gray-200">
                    @foreach($detailedAttendances as $attendance)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900">{{ $attendance->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $attendance->user->no_hp ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $roleColor = match($attendance->user->role ?? 'user') {
                                    'user' => 'bg-green-100 text-green-800',
                                    'security' => 'bg-blue-100 text-blue-800',
                                    'cleaning' => 'bg-purple-100 text-purple-800',
                                    'kantoran' => 'bg-indigo-100 text-indigo-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                
                                $roleName = match($attendance->user->role ?? 'user') {
                                    'user' => 'Kebun & Panen',
                                    'security' => 'Security',
                                    'cleaning' => 'Cleaning',
                                    'kantoran' => 'Administrasi',
                                    default => $attendance->user->role
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleColor }} capitalize">
                                {{ $roleName }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($attendance->check_in)
                                <span class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($attendance->check_out)
                                <span class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-gray-400">Belum checkout</span>
                            @endif
                        </td>
                        
                        @if($showPalmTable)
                        <td class="px-4 py-3">
                            @php
                                $produksi = \App\Models\CatatanPanen::where('id_pegawai', $attendance->user_id)
                                    ->whereDate('tanggal', $attendance->date)
                                    ->sum('berat_kg');
                            @endphp
                            <span class="font-bold {{ $produksi > 0 ? 'text-green-700' : 'text-gray-500' }}">
                                {{ $produksi > 0 ? number_format($produksi, 1) . ' kg' : '-' }}
                            </span>
                        </td>
                        @endif
                        
                        <!-- Kolom Status dihapus dari sini juga -->
                        <td class="px-4 py-3">
                            @if($attendance->checkout_photo_path)
                            <a href="{{ asset('storage/'.$attendance->checkout_photo_path) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Foto
                            </a>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Halaman {{ $detailedAttendances->currentPage() }} dari {{ $detailedAttendances->lastPage() }}
            </div>
            <div>
                {{ $detailedAttendances->links() }}
            </div>
        </div>

        @else
        <div class="h-64 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-xl">
            <svg class="w-20 h-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-lg text-gray-500 font-medium mb-2">Tidak ada data laporan</p>
            <p class="text-sm text-gray-400">Coba gunakan filter yang berbeda atau periode waktu lain</p>
        </div>
        @endif

    </div>

</div>

<!-- JavaScript untuk interaktivitas -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filter jika tanggal berubah
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    
    [startDateInput, endDateInput].forEach(input => {
        if (input) {
            input.addEventListener('change', function() {
                if (startDateInput.value && endDateInput.value) {
                    // Validasi: start date tidak boleh lebih besar dari end date
                    if (new Date(startDateInput.value) > new Date(endDateInput.value)) {
                        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                        startDateInput.value = '';
                        endDateInput.value = '';
                        return;
                    }
                    // Auto submit setelah 1 detik
                    setTimeout(() => {
                        document.querySelector('form').submit();
                    }, 1000);
                }
            });
        }
    });
    
    // Highlight row yang di-hover
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f9fafb';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<style>
/* Custom scrollbar untuk tabel */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Smooth transitions */
.bg-gradient-to-r {
    transition: width 0.5s ease-in-out;
}

/* Responsive table */
@media (max-width: 768px) {
    table {
        font-size: 0.875rem;
    }
    
    .px-4 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .py-3 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
}
</style>
@endsection