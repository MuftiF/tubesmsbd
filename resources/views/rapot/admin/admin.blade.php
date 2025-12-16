@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-8">
    <!-- HEADER -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Evaluasi Kinerja & Rapot</h1>
                <p class="text-gray-600 mt-2">Kelola evaluasi kinerja dan hasil rapot pegawai</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.rapot.export.all') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                    Export Semua
                </a>
            </div>
        </div>
    </div>

    <!-- DUA KOLOM LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- KOLOM KIRI: DAFTAR PEGAWAI UNTUK EVALUASI -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-xl rounded-xl border border-gray-200 overflow-hidden">
                <!-- HEADER CARD -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">👥 Daftar Pegawai</h2>
                        <span class="text-sm text-gray-600">{{ $users->count() }} pegawai</span>
                    </div>
                    <p class="text-gray-600 text-sm mt-1">Pilih pegawai untuk melakukan evaluasi kinerja</p>
                </div>

                <!-- TABLE PEGAWAI -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Dinilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- NAMA -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->no_hp ?? 'No HP belum diisi' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- ROLE -->
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($user->role == 'user') bg-green-100 text-green-700
                                        @elseif($user->role == 'security') bg-red-100 text-red-700
                                        @elseif($user->role == 'cleaning') bg-yellow-100 text-yellow-700
                                        @elseif($user->role == 'kantoran') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700 @endif
                                    ">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <!-- TERAKHIR DINILAI -->
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($user->last_evaluated_at)
                                        {{ \Carbon\Carbon::parse($user->last_evaluated_at)->format('d M Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($user->last_evaluated_at)->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Belum pernah</span>
                                    @endif
                                </td>

                                <!-- AKSI -->
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.rapot.evaluasi.create', $user->id) }}"
                                           class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                                            Evaluasi
                                        </a>
                                        <a href="{{ route('admin.rapot.generate', $user->id) }}"
                                           class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                                            Rapot
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.137a4 4 0 00-5.71-5.71M14 7a4 4 0 10-8 0 4 4 0 008 0z" />
                                        </svg>
                                        <p>Tidak ada data pegawai</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: RIWAYAT RAPOT TERBARU -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-xl rounded-xl border border-gray-200 overflow-hidden">
                <!-- HEADER -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                    <h2 class="text-xl font-semibold text-gray-800">Riwayat Rapot Terbaru</h2>
                    <p class="text-gray-600 text-sm mt-1">{{ $rapots->count() }} rapot terbaru</p>
                </div>

                <!-- LIST RAPOT -->
                <div class="divide-y divide-gray-200 max-h-[500px] overflow-y-auto">
                    @forelse($rapots as $rapot)
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $rapot->user->name ?? 'Unknown' }}</h3>
                                <p class="text-sm text-gray-500">{{ $rapot->periode }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        @if($rapot->nilai >= 90) bg-green-100 text-green-800
                                        @elseif($rapot->nilai >= 80) bg-blue-100 text-blue-800
                                        @elseif($rapot->nilai >= 70) bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif
                                    ">
                                        Nilai: {{ $rapot->nilai }}
                                    </span>
                                    <span class="ml-2 text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded-full">
                                        {{ $rapot->status ?? 'Standard' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <a href="{{ route('admin.rapot.show', $rapot->id) }}"
                                   class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded">
                                    👁️
                                </a>
                                <a href="{{ route('admin.rapot.export.pdf', $rapot->id) }}"
                                   class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded">
                                    📄
                                </a>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($rapot->generated_at)->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>Belum ada rapot</p>
                    </div>
                    @endforelse
                </div>

                <!-- FOOTER -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <a href="{{ route('admin.rapot.index') }}?filter=all"
                       class="block text-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat semua rapot →
                    </a>
                </div>
            </div>

            <!-- INFO CARD -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-5">
                <h3 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                    ℹ️ Panduan Evaluasi
                </h3>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start gap-2">
                        <span><strong>Evaluasi:</strong> Penilaian kinerja mendetail dengan feedback</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span><strong>Rapot:</strong> Laporan jam kerja otomatis</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span>Hasil evaluasi dapat dikirim ke email pegawai</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom scrollbar untuk riwayat rapot */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush