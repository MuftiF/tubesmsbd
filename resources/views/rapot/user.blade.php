@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight flex items-center gap-2">
                Evaluasi Kinerja Saya
            </h1>
            <p class="text-sm text-stone-500 mt-1">
                Rekap evaluasi kinerja berdasarkan periode penilaian
            </p>
        </div>

        @if($rapots->isEmpty())
        <div class="bg-white rounded-2xl p-10 text-center border border-stone-200 shadow-sm">
            <div class="flex flex-col items-center">
                <i class="fas fa-file-alt text-4xl text-stone-300 mb-4"></i>
                <p class="text-stone-600 font-semibold text-lg">Belum ada evaluasi kinerja</p>
                <p class="text-stone-400 text-sm mt-1">Admin akan mengirimkan evaluasi setelah periode penilaian selesai.</p>
            </div>
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
                        
                        if (isset($absen['status']) && strtolower($absen['status']) == 'terlambat') {
                            $totalTerlambat++;
                        }
                        
                        $keterangan = $absen['keterangan'] ?? $absen['description'] ?? '-';
                        if ($keterangan && $keterangan !== '-' && trim($keterangan) !== '') {
                            $hasKeterangan = true;
                        }
                    }
                }
                
                $hariHadir = $dataEvaluasi['hari_hadir'] ?? $hariHadir;
                $totalJam = $dataEvaluasi['total_jam'] ?? $totalJam;
                $totalTerlambat = $dataEvaluasi['total_terlambat'] ?? $totalTerlambat;
                $rataJam = $dataEvaluasi['rata_jam_perhari'] ?? ($hariHadir > 0 ? $totalJam / $hariHadir : 0);
            @endphp

            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                {{-- Header Kartu --}}
                <div class="px-6 py-4 border-b border-stone-100 bg-gradient-to-r from-emerald-50 to-white">
                    <div class="flex flex-wrap justify-between items-start gap-3">
                        <div>
                            <h2 class="text-base font-bold text-stone-800">
                                Evaluasi Periode <span class="text-[#2d6a4f]">{{ $rapot->periode }}</span>
                            </h2>
                            <p class="text-xs text-stone-400 mt-1">
                                Dibuat: {{ $rapot->created_at->format('d M Y') }}
                                @if($rapot->generated_at)
                                    | Dikirim: {{ $rapot->generated_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                @if($rapot->status == 'draft') bg-stone-100 text-stone-600
                                @elseif($rapot->status == 'dikirim') bg-blue-100 text-blue-700
                                @elseif($rapot->status == 'selesai') bg-emerald-100 text-emerald-700
                                @else bg-stone-100 text-stone-600 @endif">
                                {{ ucfirst($rapot->status) }}
                            </span>
                            <span class="text-xs text-stone-400">
                                {{ $rapot->tipe == 'evaluasi' ? 'Evaluasi' : 'Standar' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Informasi Pegawai --}}
                    <div class="flex items-center gap-4 p-4 bg-emerald-50/40 rounded-xl border border-emerald-100">
                        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <span class="text-xl font-bold text-[#2d6a4f]">
                                {{ strtoupper(substr($rapot->user->name ?? 'N', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-bold text-stone-800">{{ $rapot->user->name ?? 'N/A' }}</h3>
                            <span class="inline-block px-2 py-0.5 bg-emerald-50 text-[#2d6a4f] text-xs font-semibold rounded-full mt-1">
                                {{ ucfirst($rapot->user->role ?? 'user') }}
                            </span>
                        </div>
                    </div>

                    {{-- Statistik Ringkas --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-stone-50 rounded-xl p-4 text-center">
                            <p class="text-xs text-stone-500 mb-1">Hari Hadir</p>
                            <p class="text-2xl font-bold text-stone-800">{{ $hariHadir }} <span class="text-xs font-normal text-stone-400">hari</span></p>
                        </div>
                        <div class="bg-stone-50 rounded-xl p-4 text-center">
                            <p class="text-xs text-stone-500 mb-1">Rata-rata Harian</p>
                            <p class="text-2xl font-bold text-[#2d6a4f]">{{ number_format($rataJam, 2) }} <span class="text-xs font-normal text-stone-400">jam</span></p>
                        </div>
                    </div>

                    {{-- Evaluasi Kerja --}}
                    @if($rapot->evaluasi_kerja)
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-stone-500 mb-2 flex items-center gap-2">
                            <i class="fas fa-star text-amber-400 text-xs"></i> Evaluasi Kerja
                        </h3>
                        <div class="bg-emerald-50/30 rounded-xl p-4 border border-emerald-100">
                            <p class="text-stone-700 text-sm whitespace-pre-line leading-relaxed">
                                {{ Str::limit($rapot->evaluasi_kerja, 200) }}
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Saran Perbaikan --}}
                    @if($rapot->saran_perbaikan)
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-stone-500 mb-2 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-amber-400 text-xs"></i> Saran Perbaikan
                        </h3>
                        <div class="bg-blue-50/30 rounded-xl p-4 border border-blue-100">
                            <p class="text-stone-700 text-sm whitespace-pre-line leading-relaxed">
                                {{ Str::limit($rapot->saran_perbaikan, 200) }}
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Catatan --}}
                    @if($rapot->catatan)
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-stone-500 mb-2 flex items-center gap-2">
                            <i class="fas fa-pencil-alt text-stone-400 text-xs"></i> Catatan
                        </h3>
                        <div class="bg-amber-50/30 rounded-xl p-4 border border-amber-100">
                            <p class="text-stone-700 text-sm whitespace-pre-line leading-relaxed">
                                {{ Str::limit($rapot->catatan, 200) }}
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Detail Absen Accordion --}}
                    @if(!empty($detailAbsen))
                    <div class="pt-2">
                        <details class="group">
                            <summary class="cursor-pointer py-2 flex items-center justify-between text-sm font-semibold text-stone-600 hover:text-[#2d6a4f] transition">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-stone-400"></i>
                                    Detail Absensi ({{ count($detailAbsen) }} hari)
                                </div>
                                <i class="fas fa-chevron-down text-xs text-stone-400 group-open:rotate-180 transition-transform"></i>
                            </summary>

                            <div class="mt-4 overflow-x-auto rounded-xl border border-stone-200">
                                <table class="w-full text-sm">
                                    <thead class="bg-stone-50 border-b border-stone-100">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500">Tanggal</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500">Check In</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500">Status</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500">Jam Kerja</th>
                                            @if($hasKeterangan)
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500">Keterangan</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-stone-100">
                                        @foreach($detailAbsen as $absen)
                                        <tr class="hover:bg-[#fefcf7] transition">
                                            <td class="px-3 py-2 text-stone-700">
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
                                            <td class="px-3 py-2 font-medium text-stone-700">
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
                                            <td class="px-3 py-2">
                                                @php
                                                    $status = $absen['status'] ?? 'hadir';
                                                    $statusLower = strtolower($status);
                                                    $statusColor = match($statusLower) {
                                                        'hadir', 'tepat waktu' => 'bg-emerald-100 text-emerald-800',
                                                        'izin' => 'bg-yellow-100 text-yellow-800',
                                                        'sakit' => 'bg-blue-100 text-blue-800',
                                                        'terlambat' => 'bg-amber-100 text-amber-800',
                                                        'alfa' => 'bg-red-100 text-red-800',
                                                        default => 'bg-stone-100 text-stone-600'
                                                    };
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 font-medium text-stone-700">
                                                @if(isset($absen['jam_kerja']) && $absen['jam_kerja'] > 0)
                                                    {{ number_format($absen['jam_kerja'], 2) }} jam
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @if($hasKeterangan)
                                            <td class="px-3 py-2 text-stone-500 text-xs">
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
                </div>

                {{-- Footer Kartu --}}
                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/30 flex flex-wrap justify-between items-center gap-3">
                    <div class="text-xs text-stone-400">
                        <i class="far fa-id-card mr-1"></i> ID: {{ $rapot->id }} |
                        <i class="fas fa-user-check ml-2 mr-1"></i> Evaluator: {{ $rapot->evaluator->name ?? 'Admin' }}
                    </div>
                    @if(Route::has('rapot.show'))
                    <a href="{{ route('rapot.show', $rapot->id) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#2d6a4f] text-white text-xs font-semibold rounded-xl hover:bg-[#235f48] transition">
                        Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    @elseif(Route::has('admin.rapot.show') && auth()->user()->role == 'admin')
                    <a href="{{ route('admin.rapot.show', $rapot->id) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#2d6a4f] text-white text-xs font-semibold rounded-xl hover:bg-[#235f48] transition">
                        Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($rapots->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $rapots->links() }}
        </div>
        @endif

    @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

@push('styles')
<style>
    details summary::-webkit-details-marker {
        display: none;
    }
    
    details summary {
        list-style: none;
    }
    
    details[open] summary .fa-chevron-down {
        transform: rotate(180deg);
    }
</style>
@endpush

@push('scripts')
<script>
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
    
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#details') {
            const details = document.querySelector('details');
            if (details) details.open = true;
        }
    });
</script>
@endpush
@endsection