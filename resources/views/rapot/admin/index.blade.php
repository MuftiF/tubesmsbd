@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Admin</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Evaluasi Kinerja</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola evaluasi kinerja pegawai</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- Session Alerts --}}
        @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf4f1] text-[#1f4a3d] p-4 px-5 rounded-2xl mb-6 border border-[#2c5e4e]/20 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 text-red-800 p-4 px-5 rounded-2xl mb-6 border border-red-200 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Daftar Pegawai - 2/3 --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex justify-between items-center px-6 py-5 border-b border-gray-100">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700">Daftar Pegawai</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Pilih pegawai untuk melakukan evaluasi kinerja</p>
                        </div>
                        <span class="text-xs font-semibold text-[#2c5e4e] bg-[#eaf4f1] px-3 py-1 rounded-full">
                            {{ $users->count() }} pegawai
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr class="text-left">
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Nama</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Posisi</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-[#eaf4f1] rounded-xl flex items-center justify-center text-[#2c5e4e] font-bold text-sm flex-shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800 text-sm">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $user->no_hp ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-[#eaf4f1] text-[#2c5e4e]">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($user->last_evaluated_at)
                                            <div class="text-gray-700 text-sm font-medium">
                                                {{ \Carbon\Carbon::parse($user->last_evaluated_at)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($user->last_evaluated_at)->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-sm">Belum dinilai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.rapot.evaluasi.create', $user->id) }}"
                                           class="inline-block px-3 py-1.5 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white text-xs font-semibold rounded-lg transition">
                                            Evaluasi
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-14 text-center text-gray-400">
                                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="font-semibold text-sm text-gray-500">Tidak ada data pegawai</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Riwayat Evaluasi - 1/3 --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex justify-between items-center px-6 py-5 border-b border-gray-100">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700">Riwayat Evaluasi</h2>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $rapots->count() }} hasil terbaru</p>
                        </div>
                        <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($rapots as $rapot)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-gray-800 text-sm truncate">{{ $rapot->user->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $rapot->periode }}</div>
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                            {{ $rapot->status ?? '-' }}
                                        </span>
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full
                                            {{ $rapot->tipe == 'evaluasi' ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $rapot->tipe == 'evaluasi' ? 'Evaluasi' : 'Standar' }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.rapot.show', $rapot->id) }}"
                                   class="text-gray-300 hover:text-[#2c5e4e] transition flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="mt-2 flex items-center gap-1 text-xs text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($rapot->generated_at)->diffForHumans() }}
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-14 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="font-semibold text-sm text-gray-500">Belum ada riwayat evaluasi</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    @if(session('success'))
    setTimeout(() => { alert('{{ session('success') }}'); }, 100);
    @endif
    @if(session('error'))
    setTimeout(() => { alert('Error: {{ session('error') }}'); }, 100);
    @endif
</script>
@endpush
@endsection