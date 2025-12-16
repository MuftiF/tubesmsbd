@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Evaluasi Kinerja</h1>
                <p class="text-gray-600 mt-2">
                    <span class="font-semibold">{{ $rapot->user->name ?? 'N/A' }}</span> |
                    Periode: <span class="font-semibold">{{ $rapot->periode }}</span> |
                    Status: 
                    <span class="font-semibold 
                        {{ $rapot->status == 'Sangat Baik' ? 'text-green-600' : 
                           ($rapot->status == 'Baik' ? 'text-blue-600' : 
                           ($rapot->status == 'Perlu Perbaikan' ? 'text-orange-600' : 'text-gray-600')) }}">
                        {{ $rapot->status }}
                    </span>
                </p>
            </div>
            <div>
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.rapot.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    ← Kembali ke Daftar
                </a>
                @else
                <a href="{{ route('rapot.saya') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    ← Kembali
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Utama -->
           <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">👤 Informasi Pegawai</h3>
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <span class="text-xl font-bold text-blue-600">
                        {{ strtoupper(substr($rapot->user->name ?? 'N', 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">{{ $rapot->user->name ?? 'N/A' }}</h4>
                    <p class="text-gray-600">{{ $rapot->user->role ?? 'user' }}</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">ID:</span>
                    <span class="font-medium">{{ $rapot->user->id ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Evaluator:</span>
                    <span class="font-medium">{{ $rapot->evaluator->name ?? 'Admin' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Dibuat:</span>
                    <span class="font-medium">{{ $rapot->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Evaluasi</h3>
            <div class="space-y-3">
                @php
                    $statusColor = match($dataEvaluasi['status_evaluasi'] ?? 'draft') {
                        'draft' => 'bg-gray-100 text-gray-800',
                        'dikirim' => 'bg-blue-100 text-blue-800',
                        'selesai' => 'bg-green-100 text-green-800',
                        'Perlu Perbaikan' => 'bg-orange-100 text-orange-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                @endphp
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Status Evaluasi:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                        {{ ucfirst($dataEvaluasi['status_evaluasi'] ?? 'draft') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Nilai Skala 10:</span>
                    <span class="font-medium">
                        {{ number_format(abs($dataEvaluasi['nilai_skala_10'] ?? 0), 1) }}/10
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tipe:</span>
                    <span class="font-medium">{{ $rapot->tipe == 'evaluasi_kinerja' ? 'Evaluasi Kinerja' : ucfirst($rapot->tipe) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Persentase:</span>
                    <span class="font-medium {{ ($dataEvaluasi['persentase_kehadiran'] ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format(abs($dataEvaluasi['persentase_kehadiran'] ?? 0), 1) }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluasi Detail -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Evaluasi Kinerja</h2>
        
        <!-- Evaluasi Kerja -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <span class="mr-2"></span> Evaluasi Kerja
            </h3>
            <div class="bg-gray-50 rounded-lg p-6">
                <p class="text-gray-700 whitespace-pre-line">
                    {{ $dataEvaluasi['evaluasi_kerja'] ?? ($rapot->evaluasi_kerja ?? 'Tidak ada evaluasi') }}
                </p>
            </div>
        </div>

        <!-- Saran Perbaikan -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <span class="mr-2"></span> Saran Perbaikan
            </h3>
            <div class="bg-blue-50 rounded-lg p-6">
                <p class="text-gray-700 whitespace-pre-line">
                    {{ $dataEvaluasi['saran_perbaikan'] ?? ($rapot->saran_perbaikan ?? 'Tidak ada saran') }}
                </p>
            </div>
        </div>

        <!-- Catatan Tambahan -->
        @if($rapot->catatan)
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <span class="mr-2"></span> Catatan Tambahan
            </h3>
            <div class="bg-yellow-50 rounded-lg p-6">
                <p class="text-gray-700 whitespace-pre-line">{{ $rapot->catatan }}</p>
            </div>
        </div>
        @endif

        <!-- Statistik Detail -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <span class="mr-2"></span> Statistik Detail
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Persentase Kehadiran</p>
                    <p class="text-2xl font-bold 
                        {{ ($dataEvaluasi['persentase_kehadiran'] ?? 0) < 0 ? 'text-red-600' : 'text-gray-800' }}">
                        {{ number_format(abs($dataEvaluasi['persentase_kehadiran'] ?? 0), 1) }}%
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Hari Hadir</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $dataEvaluasi['hari_hadir'] ?? 0 }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Total Terlambat</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $dataEvaluasi['total_terlambat'] ?? 0 }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Rata Jam/Hari</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ number_format(abs($dataEvaluasi['rata_jam_perhari'] ?? 0), 2) }} jam
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Kehadiran -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Kehadiran</h2>
        
        @if(!empty($detailAbsen) && count($detailAbsen) > 0)
        <div class="overflow-x-auto">
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
                        <!-- TANGGAL - FIXED -->
                        <td class="px-4 py-3 text-sm text-gray-900">
                            @if(isset($absen['tanggal']) && $absen['tanggal'])
                                @php
                                    try {
                                        // Jika sudah format d/m/Y, tampilkan langsung
                                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $absen['tanggal'])) {
                                            echo $absen['tanggal'];
                                        } 
                                        // Jika format Y-m-d, konversi ke d/m/Y
                                        elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $absen['tanggal'])) {
                                            echo \Carbon\Carbon::parse($absen['tanggal'])->format('d/m/Y');
                                        }
                                        // Jika sudah string waktu lainnya, tampilkan langsung
                                        else {
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
                        
                        <!-- CHECK IN - FIXED -->
                        <td class="px-4 py-3 text-sm text-gray-900">
                            @if(isset($absen['check_in']) && $absen['check_in'] && $absen['check_in'] !== '-')
                                @php
                                    try {
                                        // Jika sudah format H:i, tampilkan langsung
                                        if (preg_match('/^\d{1,2}:\d{2}$/', $absen['check_in'])) {
                                            echo $absen['check_in'];
                                        } 
                                        // Jika datetime, ambil hanya waktu
                                        else {
                                            $time = \Carbon\Carbon::parse($absen['check_in'])->format('H:i');
                                            echo $time;
                                        }
                                    } catch (\Exception $e) {
                                        echo $absen['check_in'];
                                    }
                                @endphp
                            @else
                                -
                            @endif
                        </td>
                        
                        <!-- CHECK OUT - FIXED -->
                        <td class="px-4 py-3 text-sm text-gray-900">
                            @if(isset($absen['check_out']) && $absen['check_out'] && $absen['check_out'] !== '-')
                                @php
                                    try {
                                        if (preg_match('/^\d{1,2}:\d{2}$/', $absen['check_out'])) {
                                            echo $absen['check_out'];
                                        } else {
                                            $time = \Carbon\Carbon::parse($absen['check_out'])->format('H:i');
                                            echo $time;
                                        }
                                    } catch (\Exception $e) {
                                        echo $absen['check_out'];
                                    }
                                @endphp
                            @else
                                -
                            @endif
                        </td>
                        
                        <!-- JAM KERJA -->
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ isset($absen['jam_kerja']) ? number_format(abs($absen['jam_kerja']), 2) . ' jam' : '-' }}
                        </td>
                        
                        <!-- STATUS -->
                        <td class="px-4 py-3">
                            @php
                                $statusColor = match($absen['status'] ?? 'hadir') {
                                    'hadir' => 'bg-green-100 text-green-800',
                                    'izin' => 'bg-yellow-100 text-yellow-800',
                                    'sakit' => 'bg-blue-100 text-blue-800',
                                    'terlambat' => 'bg-orange-100 text-orange-800',
                                    'alfa' => 'bg-red-100 text-red-800',
                                    'tepat waktu' => 'bg-green-100 text-green-800',
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
        <p class="mt-4 text-sm text-gray-500">
            Total: {{ count($detailAbsen) }} hari kehadiran
        </p>
        @else
        <div class="text-center py-12">
            <div class="text-gray-400 text-5xl mb-4"></div>
            <p class="text-gray-500 text-lg">Tidak ada data kehadiran untuk periode ini.</p>
            @if($rapot->detail_absen)
                <p class="text-sm text-gray-400 mt-2">
                    Data tersimpan: {{ substr($rapot->detail_absen, 0, 50) }}...
                </p>
            @endif
        </div>
        @endif
    </div>

    <!-- Footer Actions -->
    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-500">
            Terakhir diupdate: {{ $rapot->updated_at->format('d M Y H:i') }}
        </div>
        @if(auth()->check() && auth()->user()->role == 'admin')
        <div class="flex gap-3">      
            @if(Route::has('admin.rapot.delete'))
            <form action="{{ route('admin.rapot.delete', $rapot->id) }}" method="POST" 
                  onsubmit="return confirm('Hapus evaluasi ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition">
                    Hapus Evaluasi
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Evaluasi detail loaded for ID: {{ $rapot->id }}');
    });
</script>
@endpush
@endsection