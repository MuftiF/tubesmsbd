@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Evaluasi Kinerja</h1>
            <p class="text-sm text-stone-500 mt-1">Kelola evaluasi kinerja pegawai</p>
        </div>

        {{-- Single Column Layout --}}
        <div class="grid grid-cols-1 gap-5">
            {{-- Card: Daftar Pegawai --}}
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-sm font-bold text-stone-700">Daftar Pegawai</h2>
                            <p class="text-xs text-stone-400 mt-0.5">Pilih pegawai untuk melakukan evaluasi kinerja</p>
                        </div>
                        <span class="text-xs font-semibold text-stone-500 bg-stone-100 px-2.5 py-1 rounded-full">{{ $users->count() }} pegawai</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-stone-50 border-b border-stone-100">
                            <tr class="text-left">
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-stone-500">Nama</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-stone-500">Posisi</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-stone-500">Status</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-stone-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($users as $user)
                            <tr class="hover:bg-[#fefcf7] transition">
                                {{-- NAMA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center text-[#2d6a4f] font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-stone-800 text-sm">{{ $user->name }}</div>
                                            <div class="text-xs text-stone-400">{{ $user->no_hp ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ROLE --}}
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full
                                        @if($user->role == 'user') bg-emerald-100 text-emerald-800
                                        @elseif($user->role == 'security') bg-red-100 text-red-800
                                        @elseif($user->role == 'cleaning') bg-yellow-100 text-yellow-800
                                        @elseif($user->role == 'kantoran') bg-blue-100 text-blue-800
                                        @else bg-stone-100 text-stone-600 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                {{-- STATUS / TERAKHIR DINILAI --}}
                                <td class="px-6 py-4 text-sm">
                                    @if($user->last_evaluated_at)
                                        <div class="text-stone-700 text-sm font-medium">
                                            {{ \Carbon\Carbon::parse($user->last_evaluated_at)->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-stone-400">
                                            {{ \Carbon\Carbon::parse($user->last_evaluated_at)->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-stone-400 italic text-sm">Belum dinilai</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.rapot.evaluasi.create', $user->id) }}"
                                       class="inline-block px-3 py-1.5 bg-[#2d6a4f] hover:bg-[#235f48] text-white text-xs font-semibold rounded-lg transition">
                                        Evaluasi
                                    </a>
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

                {{-- Pagination --}}
                @if($users->hasPages())
                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/30">
                    {{ $users->links() }}
                </div>
                @endif
            </div>

            {{-- Card: Riwayat Evaluasi Terbaru --}}
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-sm font-bold text-stone-700">Riwayat Evaluasi Terbaru</h2>
                            <p class="text-xs text-stone-400 mt-0.5">{{ $rapots->count() }} hasil terbaru</p>
                        </div>
                        <i class="fas fa-history text-stone-300 text-sm"></i>
                    </div>
                </div>

                <div class="divide-y divide-stone-100">
                    @forelse($rapots as $rapot)
                    <div class="px-6 py-4 hover:bg-[#fefcf7] transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-semibold text-stone-800 text-sm">{{ $rapot->user->name ?? '-' }}</div>
                                <div class="text-xs text-stone-400 mt-0.5">{{ $rapot->periode }}</div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-stone-100 text-stone-600">
                                        Status: {{ $rapot->status ?? '-' }}
                                    </span>
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full
                                        @if($rapot->tipe == 'evaluasi') bg-emerald-100 text-emerald-800
                                        @else bg-stone-100 text-stone-600 @endif">
                                        {{ $rapot->tipe == 'evaluasi' ? 'Evaluasi' : 'Standar' }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('admin.rapot.show', $rapot->id) }}"
                               class="text-stone-400 hover:text-[#2d6a4f] transition text-sm">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="mt-2 text-xs text-stone-400">
                            <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($rapot->generated_at)->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center text-stone-400">
                        <i class="fas fa-inbox text-3xl mb-3 block text-stone-300"></i>
                        <p class="font-semibold text-sm">Belum ada riwayat evaluasi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

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
</script>
@endpush
@endsection