@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Admin</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Kelola Pengumuman</h1>
                    <p class="text-sm text-gray-500 mt-1">Tambahkan atau kelola pengumuman untuk seluruh pegawai</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- Form Tambah --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-[#eaf4f1]">
                <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-gray-700">Tambah Pengumuman</h2>
            </div>

            <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Judul</label>
                    <input type="text" name="judul"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]"
                        placeholder="Masukkan judul pengumuman" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Isi Pengumuman</label>
                    <textarea name="isi" rows="4"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]"
                        placeholder="Tulis isi pengumuman" required></textarea>
                </div>
                <button type="submit"
                    class="bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 shadow-md inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Pengumuman
                </button>
            </form>
        </div>

        {{-- List Pengumuman --}}
        <div class="space-y-4">
            @forelse($announcements as $a)
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm hover:border-[#eaf4f1] hover:shadow-md transition-all duration-200">
                <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                    <h3 class="text-base font-semibold text-gray-800">{{ $a->judul }}</h3>
                    <form action="{{ route('admin.pengumuman.delete', $a->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Yakin ingin menghapus pengumuman ini?')"
                            class="border border-red-200 text-red-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-red-50 hover:border-red-400 transition">
                            Hapus
                        </button>
                    </form>
                </div>
                <p class="text-gray-600 leading-relaxed text-sm mb-3">{{ $a->isi }}</p>
                <div class="flex items-center gap-2 text-xs text-gray-400 pt-3 border-t border-gray-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Dibuat pada: {{ $a->created_at->format('d M Y, H:i') }}
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-200">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <p class="font-semibold text-gray-500">Belum ada pengumuman</p>
                <span class="text-xs text-gray-400">Silakan tambah pengumuman melalui form di atas</span>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection