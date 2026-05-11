@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Evaluasi Kinerja & Rapot</h1>
            <p class="text-sm text-stone-500 mt-1">Kelola evaluasi kinerja dan hasil rapot pegawai</p>
        </div>

        {{-- DUA KOLOM LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- KOLOM KIRI: DAFTAR PEGAWAI UNTUK EVALUASI --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 overflow-hidden">
                {{-- HEADER CARD --}}
                <div class="px-6 py-4 border-b border-stone-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-sm font-bold text-stone-700">👥 Daftar Pegawai</h2>
                            <p class="text-xs text-stone-400 mt-0.5">Pilih pegawai untuk melakukan evaluasi kinerja</p>
                        </div>
                        <span class="text-xs font-medium text-stone-500 bg-stone-100 px-2.5 py-1 rounded-full">{{ $users->count() }} pegawai</span>
                    </div>
                </div>

                {{-- TABLE PEGAWAI --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-stone-50 border-b border-stone-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Nama Pegawai</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Terakhir Dinilai</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-[#fefcf7] transition">
                                {{-- NAMA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center text-[#2d6a4f] font-bold">{{ substr($user->name, 0, 1) }}</div>
                                        <div>
                                            <div class="text-sm font-semibold text-stone-800">{{ $user->name }}</div>
                                            <div class="text-xs text-stone-400">{{ $user->no_hp ?? 'No HP belum diisi' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ROLE --}}
                                <td class="px-6 py-4">
                                    <span class="inline-block bg-emerald-50 text-[#2d6a4f] text-xs font-semibold px-2.5 py-1 rounded-full">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                {{-- TERAKHIR DINILAI --}}
                                <td class="px-6 py-4 text-sm text-stone-500">
                                    @if($user->last_evaluated_at)
                                        {{ \Carbon\Carbon::parse($user->last_evaluated_at)->format('d M Y') }}
                                        <div class="text-xs text-stone-400">{{ \Carbon\Carbon::parse($user->last_evaluated_at)->diffForHumans() }}</div>
                                    @else
                                        <span class="text-stone-400 italic">Belum pernah</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.rapot.evaluasi.create', $user->id) }}"
                                           class="px-3 py-1.5 bg-[#2d6a4f] text-white text-xs font-semibold rounded-lg hover:bg-[#235f48] transition flex items-center gap-1">
                                            Evaluasi
                                        </a>
                                        <a href="{{ route('admin.rapot.generate', $user->id) }}"
                                           class="px-3 py-1.5 bg-stone-800 text-white text-xs font-semibold rounded-lg hover:bg-stone-700 transition flex items-center gap-1">
                                            Rapot
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-stone-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-users text-3xl mb-3 text-stone-300"></i>
                                        <p class="font-semibold text-sm">Tidak ada data pegawai</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($users->hasPages())
                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50">
                    {{ $users->links() }}
                </div>
                @endif
            </div>

            {{-- KOLOM KANAN: RIWAYAT RAPOT TERBARU --}}
            <div class="lg:col-span-1 space-y-5">
                <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
                    {{-- HEADER --}}
                    <div class="px-5 py-4 border-b border-stone-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-bold text-stone-700">Riwayat Rapot Terbaru</h2>
                                <p class="text-xs text-stone-400 mt-0.5">{{ $rapots->count() }} rapot terbaru</p>
                            </div>
                            <i class="fas fa-scroll text-stone-300 text-lg"></i>
                        </div>
                    </div>

                    {{-- LIST RAPOT --}}
                    <div class="divide-y divide-stone-100 max-h-[480px] overflow-y-auto">
                        @forelse($rapots as $rapot)
                        <div class="px-5 py-4 hover:bg-[#fefcf7] transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-stone-800 text-sm">{{ $rapot->user->name ?? 'Unknown' }}</h3>
                                    <p class="text-xs text-stone-400 mt-0.5">{{ $rapot->periode }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full
                                            @if($rapot->nilai >= 90) bg-emerald-100 text-emerald-800
                                            @elseif($rapot->nilai >= 80) bg-blue-100 text-blue-800
                                            @elseif($rapot->nilai >= 70) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif
                                        ">
                                            Nilai: {{ $rapot->nilai }}
                                        </span>
                                        <span class="text-xs font-semibold bg-stone-100 text-stone-600 px-2 py-0.5 rounded-full">
                                            {{ $rapot->status ?? 'Standard' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.rapot.show', $rapot->id) }}" class="p-1.5 text-stone-400 hover:text-[#2d6a4f] hover:bg-emerald-50 rounded-lg transition">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.rapot.export.pdf', $rapot->id) }}" class="p-1.5 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fas fa-download text-xs"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-stone-400">
                                <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($rapot->generated_at)->diffForHumans() }}
                            </div>
                        </div>
                        @empty
                        <div class="px-5 py-12 text-center text-stone-400">
                            <i class="fas fa-inbox text-3xl mb-3 block text-stone-300"></i>
                            <p class="font-semibold text-sm">Belum ada rapot</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- FOOTER --}}
                    <div class="px-5 py-3 border-t border-stone-100 bg-stone-50">
                        <a href="{{ route('admin.rapot.index') }}?filter=all" class="text-xs font-semibold text-[#2d6a4f] hover:underline flex items-center justify-center gap-1">
                            Lihat semua rapot <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- INFO CARD --}}
                <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100">
                    <h3 class="font-bold text-emerald-800 text-sm mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Panduan Evaluasi
                    </h3>
                    <ul class="text-xs text-emerald-700 space-y-2">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5">•</span>
                            <span><strong>Evaluasi:</strong> Penilaian kinerja mendetail dengan feedback</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5">•</span>
                            <span><strong>Rapot:</strong> Laporan jam kerja otomatis</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5">•</span>
                            <span>Hasil evaluasi dapat dikirim ke email pegawai</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

@push('styles')
<style>
    /* Custom scrollbar untuk riwayat rapot */
    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #e2e2e2;
        border-radius: 10px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
@endpush
@endsection