@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Kelola Pengumuman</h1>
            <p class="text-sm text-stone-500 mt-1">Tambahkan atau kelola pengumuman untuk seluruh pegawai</p>
        </div>

        {{-- Form Tambah --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-8 border border-stone-200 shadow-sm">
            <h2 class="text-sm font-bold text-stone-800 pb-2 border-b-2 border-emerald-100 inline-flex items-center gap-2 mb-5">
                <i class="fas fa-plus-circle text-[#2d6a4f]"></i> Tambah Pengumuman
            </h2>

            <form method="POST" action="{{ route('admin.pengumuman.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Judul</label>
                    <input type="text" name="judul"
                           class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]"
                           placeholder="Masukkan judul pengumuman" required>
                </div>

                <div class="mb-5">
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Isi Pengumuman</label>
                    <textarea name="isi" rows="4"
                              class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]"
                              placeholder="Tulis isi pengumuman"
                              required></textarea>
                </div>

                <button class="bg-[#2d6a4f] hover:bg-[#235c44] text-white px-5 py-2 rounded-xl font-semibold text-sm transition inline-flex items-center gap-2">
                    <i class="fas fa-save text-xs"></i> Tambah Pengumuman
                </button>
            </form>
        </div>

        {{-- List Pengumuman --}}
        <div class="space-y-4">
            @forelse($announcements as $a)
                <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                        <h3 class="text-base md:text-lg font-bold text-stone-800">
                            {{ $a->judul }}
                        </h3>

                        <form action="{{ route('admin.pengumuman.delete', $a->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus pengumuman ini?')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                <i class="fas fa-trash-alt text-xs"></i> Hapus
                            </button>
                        </form>
                    </div>

                    <p class="text-stone-700 leading-relaxed text-sm mb-3">
                        {{ $a->isi }}
                    </p>

                    <div class="text-xs text-stone-400 flex items-center gap-2">
                        <i class="far fa-calendar-alt"></i>
                        Dibuat pada: {{ $a->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-10 text-center border border-stone-200">
                    <i class="far fa-newspaper text-4xl text-stone-300 mb-3 block"></i>
                    <p class="font-semibold text-stone-500">Belum ada pengumuman</p>
                    <span class="text-xs text-stone-400">Silakan tambah pengumuman melalui form di atas</span>
                </div>
            @endforelse
        </div>

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@endsection