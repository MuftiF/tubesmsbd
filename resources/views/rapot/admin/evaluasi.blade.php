@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Buat Evaluasi Kinerja</h1>
            <p class="text-sm text-stone-500 mt-1">
                Periode: <span class="font-semibold text-[#2d6a4f]">{{ $periode }}</span> |
                Pegawai: <span class="font-semibold">{{ $user->name }}</span> ({{ ucfirst($user->role) }})
            </p>
        </div>

        {{-- Statistik Kehadiran --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-stone-400 mb-2">Hari Kerja</div>
                <div class="text-2xl md:text-3xl font-bold text-[#2d6a4f]">{{ $hariKerja }} <span class="text-sm font-normal text-stone-400">hari</span></div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-stone-400 mb-2">Total Jam Kerja</div>
                <div class="text-2xl md:text-3xl font-bold text-[#2563eb]">{{ number_format($totalJam, 2) }} <span class="text-sm font-normal text-stone-400">jam</span></div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-stone-400 mb-2">Rata-rata per Hari</div>
                <div class="text-2xl md:text-3xl font-bold text-[#2d6a4f]">{{ $hariKerja > 0 ? number_format($totalJam / $hariKerja, 2) : 0 }} <span class="text-sm font-normal text-stone-400">jam</span></div>
            </div>
        </div>

        {{-- Form Evaluasi --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <form action="{{ route('admin.rapot.evaluasi.store', $user->id) }}" method="POST" id="evaluasiForm">
                @csrf
                
                {{-- Hidden fields --}}
                <input type="hidden" name="periode_start" value="{{ $periodeStart }}">
                <input type="hidden" name="periode_end" value="{{ $periodeEnd }}">
                <input type="hidden" name="detail_absen" id="detailAbsenInput" value="{{ json_encode($detailAbsen) }}">

                {{-- Informasi Pegawai --}}
                <div class="p-6 md:p-8 border-b border-stone-100 bg-gradient-to-r from-emerald-50 to-white">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center">
                            <span class="text-2xl font-bold text-[#2d6a4f]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-[#1e1e1e]">{{ $user->name }}</h3>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="px-2.5 py-1 bg-emerald-50 text-[#2d6a4f] text-xs font-semibold rounded-full">{{ ucfirst($user->role) }}</span>
                                <span class="px-2.5 py-1 bg-stone-100 text-stone-600 text-xs font-semibold rounded-full">ID: {{ $user->id }}</span>
                                <span class="px-2.5 py-1 bg-emerald-50 text-[#2d6a4f] text-xs font-semibold rounded-full">Periode: {{ $periode }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="p-6 md:p-8 space-y-6">
                    {{-- Evaluasi Kerja --}}
                    <div>
                        <label for="evaluasi_kerja" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-2">
                            Evaluasi Kinerja <span class="text-red-500">*</span>
                        </label>
                        <textarea name="evaluasi_kerja" id="evaluasi_kerja" rows="6"
                            class="w-full border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f] resize-none"
                            placeholder="Deskripsikan evaluasi kinerja selama periode ini..."
                            required>{{ old('evaluasi_kerja') }}</textarea>
                        @error('evaluasi_kerja')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Saran Perbaikan --}}
                    <div>
                        <label for="saran_perbaikan" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-2">
                            Saran Perbaikan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="saran_perbaikan" id="saran_perbaikan" rows="4"
                            class="w-full border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f] resize-none"
                            placeholder="Berikan saran untuk perbaikan kinerja..."
                            required>{{ old('saran_perbaikan') }}</textarea>
                        @error('saran_perbaikan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan Opsional --}}
                    <div>
                        <label for="catatan" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-2">
                            Catatan Tambahan (Opsional)
                        </label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full border border-stone-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f] resize-none"
                            placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                    </div>

                    {{-- Status Evaluasi --}}
                    <div>
                        <label for="status" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-2">
                            Status Evaluasi <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                            class="w-full border border-stone-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f] bg-white">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="dikirim" {{ old('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Detail Kehadiran --}}
                <div class="border-t border-stone-100 bg-stone-50/30 p-6 md:p-8">
                    <div class="flex justify-between items-center mb-5">
                        <div>
                            <h3 class="text-sm font-bold text-stone-700">Detail Kehadiran</h3>
                            <p class="text-xs text-stone-400 mt-0.5">Total: {{ count($detailAbsen) }} hari</p>
                        </div>
                    </div>
                    
                    @if(count($detailAbsen) > 0)
                    <div class="overflow-x-auto rounded-xl border border-stone-200 bg-white mb-5">
                        <table class="w-full">
                            <thead class="bg-stone-50 border-b border-stone-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Check In</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Check Out</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Jam Kerja</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @foreach($detailAbsen as $absen)
                                <tr class="hover:bg-[#fefcf7] transition">
                                    <td class="px-4 py-3 text-sm text-stone-800 font-medium">{{ $absen['tanggal'] }}</td>
                                    <td class="px-4 py-3 text-sm text-stone-600">{{ $absen['check_in'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-stone-600">{{ $absen['check_out'] ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full 
                                            {{ ($absen['jam_kerja'] ?? 0) > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ number_format($absen['jam_kerja'] ?? 0, 2) }} jam
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColor = match($absen['status'] ?? 'hadir') {
                                                'hadir', 'tepat waktu' => 'bg-emerald-100 text-emerald-800',
                                                'izin' => 'bg-yellow-100 text-yellow-800',
                                                'sakit' => 'bg-blue-100 text-blue-800',
                                                'terlambat' => 'bg-amber-100 text-amber-800',
                                                'alfa' => 'bg-red-100 text-red-800',
                                                default => 'bg-stone-100 text-stone-600'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                            {{ ucfirst($absen['status'] ?? 'hadir') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Summary Box --}}
                    <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                        <h4 class="font-bold text-emerald-800 text-sm mb-3">Ringkasan Statistik</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="bg-white rounded-xl p-3">
                                <p class="text-xs text-stone-500">Hari Hadir</p>
                                <p class="text-xl font-bold text-stone-800">{{ $hariHadir }}</p>
                            </div>
                            <div class="bg-white rounded-xl p-3">
                                <p class="text-xs text-stone-500">Total Jam</p>
                                <p class="text-xl font-bold text-[#2d6a4f]">{{ number_format($totalJam, 2) }} <span class="text-xs">jam</span></p>
                            </div>
                            <div class="bg-white rounded-xl p-3">
                                <p class="text-xs text-stone-500">Rata-rata/Hari</p>
                                <p class="text-xl font-bold text-[#2563eb]">{{ $hariKerja > 0 ? number_format($totalJam / $hariKerja, 2) : 0 }} <span class="text-xs">jam</span></p>
                            </div>
                            <div class="bg-white rounded-xl p-3">
                                <p class="text-xs text-stone-500">Terlambat</p>
                                <p class="text-xl font-bold text-amber-600">{{ $totalTerlambat }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12 border-2 border-dashed border-stone-200 rounded-xl">
                        <i class="fas fa-calendar-times text-3xl text-stone-300 mb-3 block"></i>
                        <p class="text-stone-500 font-semibold text-sm">Tidak ada data kehadiran</p>
                        <p class="text-stone-400 text-xs mt-1">Tidak ditemukan data kehadiran untuk periode ini.</p>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 p-6 md:p-8 border-t border-stone-100 bg-stone-50/30">
                    <a href="{{ route('admin.rapot.index') }}" 
                       class="px-5 py-2.5 border border-stone-200 rounded-xl text-stone-600 text-sm font-semibold hover:bg-white hover:border-stone-300 transition">
                        ← Kembali
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-[#2d6a4f] text-white text-sm font-semibold rounded-xl hover:bg-[#235f48] transition shadow-sm flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

@push('scripts')
<script>
    document.getElementById('evaluasiForm').addEventListener('submit', function(e) {
        const evaluasi = document.getElementById('evaluasi_kerja').value.trim();
        const saran = document.getElementById('saran_perbaikan').value.trim();
        
        if (!evaluasi || !saran) {
            e.preventDefault();
            alert('Mohon isi evaluasi kerja dan saran perbaikan!');
            return false;
        }
        
        const konfirmasi = confirm('Simpan evaluasi kinerja ini?');
        if (!konfirmasi) {
            e.preventDefault();
            return false;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        submitBtn.disabled = true;
    });
    
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@endpush
@endsection