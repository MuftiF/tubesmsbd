@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Buat Evaluasi Kinerja</h1>
        <p class="text-gray-600 mt-2">
            Periode: <span class="font-semibold">{{ $periode }}</span> |
            Pegawai: <span class="font-semibold">{{ $user->name }}</span> ({{ $user->role }})
        </p>
    </div>

    <!-- Statistik Kehadiran -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div>
                    <p class="text-gray-500 text-sm">Hari Kerja</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $hariKerja }} hari</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Jam Kerja</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalJam, 2) }} jam</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex items-center">
                <div>
                    <p class="text-gray-500 text-sm">Rata-rata per Hari</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $hariKerja > 0 ? number_format($totalJam / $hariKerja, 2) : 0 }} jam
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Evaluasi -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <form action="{{ route('admin.rapot.evaluasi.store', $user->id) }}" method="POST" id="evaluasiForm">
            @csrf
            
            <!-- Hidden fields untuk data absen -->
            <input type="hidden" name="periode_start" value="{{ $periodeStart }}">
            <input type="hidden" name="periode_end" value="{{ $periodeEnd }}">
            <input type="hidden" name="detail_absen" id="detailAbsenInput" value="{{ json_encode($detailAbsen) }}">

            <!-- Informasi Pegawai -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-2xl font-bold text-blue-600">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                                {{ $user->role }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                                ID: {{ $user->id }}
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                                Periode: {{ $periode }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluasi Kerja -->
            <div class="mb-6">
                <label for="evaluasi_kerja" class="block text-sm font-medium text-gray-700 mb-2">
                    Evaluasi Kinerja <span class="text-red-500">*</span>
                </label>
                <textarea name="evaluasi_kerja" id="evaluasi_kerja" rows="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Deskripsikan evaluasi kinerja selama periode ini..."
                    required>{{ old('evaluasi_kerja') }}</textarea>
                @error('evaluasi_kerja')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Saran Perbaikan -->
            <div class="mb-6">
                <label for="saran_perbaikan" class="block text-sm font-medium text-gray-700 mb-2">
                    Saran Perbaikan <span class="text-red-500">*</span>
                </label>
                <textarea name="saran_perbaikan" id="saran_perbaikan" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Berikan saran untuk perbaikan kinerja..."
                    required>{{ old('saran_perbaikan') }}</textarea>
                @error('saran_perbaikan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan (Opsional) -->
            <div class="mb-6">
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea name="catatan" id="catatan" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status Evaluasi <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="dikirim" {{ old('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Detail Kehadiran -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Kehadiran</h3>
                    <span class="text-sm text-gray-500">Total: {{ count($detailAbsen) }} hari</span>
                </div>
                
                @if(count($detailAbsen) > 0)
                <div class="overflow-x-auto border border-gray-200 rounded-lg mb-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Kerja</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($detailAbsen as $absen)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $absen['tanggal'] }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                    {{ $absen['check_in'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                    {{ $absen['check_out'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ ($absen['jam_kerja'] ?? 0) > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ number_format($absen['jam_kerja'] ?? 0, 2) }} jam
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColor = match($absen['status'] ?? 'hadir') {
                                            'hadir' => 'bg-green-100 text-green-800',
                                            'izin' => 'bg-yellow-100 text-yellow-800',
                                            'sakit' => 'bg-blue-100 text-blue-800',
                                            'terlambat' => 'bg-orange-100 text-orange-800',
                                            'tepat waktu' => 'bg-green-100 text-green-800',
                                            'alfa' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                        {{ ucfirst($absen['status'] ?? 'hadir') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary Box -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Ringkasan Statistik</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-sm text-gray-500">Hari Hadir</p>
                            <p class="text-xl font-bold text-gray-800">{{ $hariHadir }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-sm text-gray-500">Total Jam</p>
                            <p class="text-xl font-bold text-green-600">{{ number_format($totalJam, 2) }} jam</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-sm text-gray-500">Rata-rata/Hari</p>
                            <p class="text-xl font-bold text-blue-600">
                                {{ $hariKerja > 0 ? number_format($totalJam / $hariKerja, 2) : 0 }} jam
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-sm text-gray-500">Terlambat</p>
                            <p class="text-xl font-bold text-orange-600">{{ $totalTerlambat }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <div class="text-gray-400 text-4xl mb-4">📭</div>
                    <p class="text-gray-500 text-lg font-medium">Tidak ada data kehadiran</p>
                    <p class="text-gray-400 text-sm mt-2">Tidak ditemukan data kehadiran untuk periode ini.</p>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.rapot.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-200">
                    ← Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Evaluasi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Validasi form sebelum submit
    document.getElementById('evaluasiForm').addEventListener('submit', function(e) {
        const evaluasi = document.getElementById('evaluasi_kerja').value.trim();
        const saran = document.getElementById('saran_perbaikan').value.trim();
        
        if (!evaluasi || !saran) {
            e.preventDefault();
            alert('Mohon isi evaluasi kerja dan saran perbaikan!');
            return false;
        }
        
        // Konfirmasi sebelum submit
        const konfirmasi = confirm('Simpan evaluasi kinerja ini?');
        if (!konfirmasi) {
            e.preventDefault();
            return false;
        }
        
        // Tampilkan loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span> Menyimpan...';
        submitBtn.disabled = true;
    });
    
    // Auto-resize textarea
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@endpush
@endsection