@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">

    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center justify-center">
            <span class="text-4xl mr-3"></span> Evaluasi Kinerja Saya
        </h1>
        <p class="text-gray-500 mt-1">
            Rekap evaluasi kinerja berdasarkan periode penilaian
        </p>
    </div>

    @if($rapots->isEmpty())
        <div class="bg-white p-10 rounded-2xl shadow text-center border border-gray-200">
            <div class="text-5xl mb-3 text-gray-400"></div>
            <p class="text-gray-600 text-lg">
                Belum ada evaluasi kinerja untuk ditampilkan.
            </p>
            <p class="text-gray-500 mt-2">
                Admin akan mengirimkan evaluasi setelah periode penilaian selesai.
            </p>
        </div>
    @else

        <div class="grid grid-cols-1 gap-6">

            @foreach($rapots as $rapot)

            @php
                // Decode detail absen dari JSON atau gunakan array kosong
                $detailAbsen = [];
                if ($rapot->detail_absen) {
                    if (is_string($rapot->detail_absen)) {
                        $detailAbsen = json_decode($rapot->detail_absen, true) ?? [];
                    } elseif (is_array($rapot->detail_absen)) {
                        $detailAbsen = $rapot->detail_absen;
                    }
                }
                
                // Parse data evaluasi
                $dataEvaluasi = [];
                if ($rapot->detail_evaluasi && is_string($rapot->detail_evaluasi)) {
                    $dataEvaluasi = json_decode($rapot->detail_evaluasi, true) ?? [];
                } elseif (is_array($rapot->detail_evaluasi)) {
                    $dataEvaluasi = $rapot->detail_evaluasi;
                }
                
                // Hitung statistik dari detail absen
                $totalJam = 0;
                $hariHadir = 0;
                $totalTerlambat = 0;
                $hasKeterangan = false;
                
                if (!empty($detailAbsen)) {
                    foreach ($detailAbsen as $absen) {
                        if (isset($absen['jam_kerja']) && $absen['jam_kerja'] > 0) {
                            $totalJam += abs($absen['jam_kerja'] ?? 0);
                            $hariHadir++;
                        }
                        
                        // Hitung terlambat
                        if (isset($absen['status']) && ($absen['status'] == 'terlambat' || $absen['status'] == 'Terlambat')) {
                            $totalTerlambat++;
                        }
                        
                        // Cek apakah ada keterangan yang tidak kosong
                        $keterangan = $absen['keterangan'] ?? $absen['description'] ?? '-';
                        if ($keterangan && $keterangan !== '-' && trim($keterangan) !== '') {
                            $hasKeterangan = true;
                        }
                    }
                }
                
                // Gunakan data dari $dataEvaluasi jika ada
                $hariHadir = $dataEvaluasi['hari_hadir'] ?? $hariHadir;
                $totalJam = $dataEvaluasi['total_jam'] ?? $totalJam;
                $totalTerlambat = $dataEvaluasi['total_terlambat'] ?? $totalTerlambat;
                $rataJam = $dataEvaluasi['rata_jam_perhari'] ?? ($hariHadir > 0 ? $totalJam / $hariHadir : 0);
            @endphp

            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-xl transition-all duration-200">

                <!-- Header Kartu -->
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            Evaluasi Periode {{ $rapot->periode }}
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">
                            Dibuat: {{ $rapot->created_at->format('d M Y') }}
                            @if($rapot->generated_at)
                                | Dikirim: {{ $rapot->generated_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-col items-end">
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm mb-2
                            @if($rapot->status == 'draft') bg-gray-100 text-gray-800
                            @elseif($rapot->status == 'dikirim') bg-blue-100 text-blue-800
                            @elseif($rapot->status == 'selesai') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($rapot->status) }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $rapot->tipe == 'evaluasi' ? 'Evaluasi' : 'Standar' }}
                        </span>
                    </div>
                </div>

                <!-- Informasi Pegawai -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-xl font-bold text-blue-600">
                                {{ strtoupper(substr($rapot->user->name ?? 'N', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $rapot->user->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600">{{ $rapot->user->role ?? 'user' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik Ringkas - HANYA 2 KOLOM -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Hari Hadir</p>
                        <p class="text-xl font-bold text-gray-800">{{ $hariHadir }} hari</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Rata-rata Harian</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ number_format($rataJam, 2) }} jam/hari
                        </p>
                    </div>
                </div>

                <!-- Evaluasi Kerja -->
                @if($rapot->evaluasi_kerja)
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <h3 class="font-semibold text-gray-700">Evaluasi Kerja</h3>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-gray-600 whitespace-pre-line">
                            {{ $rapot->evaluasi_kerja }}
                        </p>
                    </div>
                </div>
                @endif

                <!-- Saran Perbaikan -->
                @if($rapot->saran_perbaikan)
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <h3 class="font-semibold text-gray-700">Saran Perbaikan</h3>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-gray-600 whitespace-pre-line">
                            {{ $rapot->saran_perbaikan }}
                        </p>
                    </div>
                </div>
                @endif

                <!-- Catatan -->
                @if($rapot->catatan)
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <h3 class="font-semibold text-gray-700">Catatan</h3>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <p class="text-gray-600 whitespace-pre-line">
                            {{ $rapot->catatan }}
                        </p>
                    </div>
                </div>
                @endif

                <!-- Detail Absen -->
                @if(!empty($detailAbsen))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <details class="group">
                        <summary class="cursor-pointer font-medium text-gray-700 flex items-center justify-between py-2">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-gray-600 text-sm"></span>
                                </div>
                                Detail Absensi ({{ count($detailAbsen) }} hari)
                            </div>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div class="mt-3 overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-gray-600 font-medium">Tanggal</th>
                                        <th class="px-3 py-2 text-left text-gray-600 font-medium">Check In</th>
                                        <th class="px-3 py-2 text-left text-gray-600 font-medium">Status</th>
                                        <th class="px-3 py-2 text-left text-gray-600 font-medium">Jam Kerja</th>
                                        @if($hasKeterangan)
                                        <th class="px-3 py-2 text-left text-gray-600 font-medium">Keterangan</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailAbsen as $absen)
                                    <tr class="border-b hover:bg-gray-50">
                                        <!-- Tanggal -->
                                        <td class="px-3 py-2">
                                            @if(isset($absen['tanggal']) && $absen['tanggal'])
                                                @php
                                                    try {
                                                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $absen['tanggal'])) {
                                                            echo $absen['tanggal'];
                                                        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $absen['tanggal'])) {
                                                            echo \Carbon\Carbon::parse($absen['tanggal'])->format('d/m/Y');
                                                        } else {
                                                            echo $absen['tanggal'];
                                                        }
                                                    } catch (\Exception $e) {
                                                        echo $absen['tanggal'];
                                                    }
                                                @endphp
                                            @else
                                                -
                                            @endif
                                        </td>
                                        
                                        <!-- Check In -->
                                        <td class="px-3 py-2 font-medium">
                                            @if(isset($absen['check_in']) && $absen['check_in'] && $absen['check_in'] !== '-')
                                                @php
                                                    try {
                                                        if (preg_match('/^\d{1,2}:\d{2}$/', $absen['check_in'])) {
                                                            echo $absen['check_in'];
                                                        } else {
                                                            echo \Carbon\Carbon::parse($absen['check_in'])->format('H:i');
                                                        }
                                                    } catch (\Exception $e) {
                                                        echo $absen['check_in'];
                                                    }
                                                @endphp
                                            @else
                                                -
                                            @endif
                                        </td>
                                        
                                        <!-- Status -->
                                        <td class="px-3 py-2">
                                            @php
                                                $status = $absen['status'] ?? 'hadir';
                                                $statusLower = strtolower($status);
                                                $statusColor = match($statusLower) {
                                                    'hadir', 'tepat waktu' => 'bg-green-100 text-green-800',
                                                    'izin' => 'bg-yellow-100 text-yellow-800',
                                                    'sakit' => 'bg-blue-100 text-blue-800',
                                                    'terlambat' => 'bg-orange-100 text-orange-800',
                                                    'alfa' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full {{ $statusColor }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        
                                        <!-- Jam Kerja -->
                                        <td class="px-3 py-2 font-medium">
                                            @if(isset($absen['jam_kerja']) && $absen['jam_kerja'] > 0)
                                                {{ number_format($absen['jam_kerja'], 2) }} jam
                                            @else
                                                -
                                            @endif
                                        </td>
                                        
                                        <!-- Keterangan (hanya ditampilkan jika ada data) -->
                                        @if($hasKeterangan)
                                        <td class="px-3 py-2 text-gray-600">
                                            {{ $absen['keterangan'] ?? $absen['description'] ?? '-' }}
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                </div>
                @endif

                <!-- Footer Kartu -->
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        ID: {{ $rapot->id }} | Evaluator: {{ $rapot->evaluator->name ?? 'Admin' }}
                    </div>
                    @if(Route::has('rapot.show'))
                    <a href="{{ route('rapot.show', $rapot->id) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                        Lihat Detail →
                    </a>
                    @elseif(Route::has('admin.rapot.show') && auth()->user()->role == 'admin')
                    <a href="{{ route('admin.rapot.show', $rapot->id) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                        Lihat Detail →
                    </a>
                    @endif
                </div>

            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($rapots->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $rapots->links() }}
        </div>
        @endif

    @endif
</div>

@push('styles')
<style>
    /* Custom style untuk details/summary */
    details summary::-webkit-details-marker {
        display: none;
    }
    
    details summary {
        list-style: none;
    }
    
    details[open] summary svg {
        transform: rotate(180deg);
    }
    
    /* Style untuk statistik box */
    .bg-gray-50 {
        transition: all 0.2s ease;
    }
    
    .bg-gray-50:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple toast notification
    @if(session('success'))
    setTimeout(() => {
        alert('{{ session('success') }}');
    }, 100);
    @endif

    @if(session('error'))
    setTimeout(() => {
        alert('Error: {{ session('error') }}');
    }, 100);
    @endif
    
    // Auto expand details jika ada hash
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#details') {
            const details = document.querySelector('details');
            if (details) {
                details.open = true;
            }
        }
    });
</script>
@endpush
@endsection