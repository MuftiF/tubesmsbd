@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Kelola Pengumuman (Manager)</h1>
            <p class="text-sm text-stone-500 mt-1">Tambah, lihat, dan kelola pengumuman perusahaan.</p>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-100 text-emerald-800 p-3 px-5 rounded-xl mb-5 border-l-4 border-[#2d6a4f] text-sm">
            <i class="fas fa-check-circle text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-100 text-red-800 p-3 px-5 rounded-xl mb-5 border-l-4 border-red-600 text-sm">
            <i class="fas fa-exclamation-triangle text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Form Tambah --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 mb-8 border border-stone-200 shadow-sm">
            <h2 class="text-sm font-bold text-stone-800 pb-2 border-b-2 border-emerald-100 inline-flex items-center gap-2 mb-5">
                <i class="fas fa-plus-circle text-[#2d6a4f]"></i> Tambah Pengumuman Baru
            </h2>

            <form method="POST" action="{{ route('manager.pengumuman.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Judul</label>
                    <input type="text" name="judul"
                           class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]"
                           placeholder="Masukkan judul pengumuman">
                </div>

                <div>
                    <label class="block text-[0.7rem] font-semibold uppercase tracking-wide text-stone-600 mb-1">Isi Pengumuman</label>
                    <textarea name="isi" rows="4"
                              class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]"
                              placeholder="Tulis isi pengumuman..."></textarea>
                </div>

                <button class="bg-[#2d6a4f] hover:bg-[#235c44] text-white px-5 py-2 rounded-xl font-semibold text-sm transition inline-flex items-center gap-2">
                    <i class="fas fa-save text-xs"></i> Tambah Pengumuman
                </button>
            </form>
        </div>

        {{-- List Pengumuman --}}
        <div class="space-y-4">
            @foreach($announcements as $a)
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex flex-wrap justify-between items-start gap-3">
                    <div class="flex-1">
                        <h3 class="text-base md:text-lg font-bold text-stone-800 mb-2">{{ $a->judul }}</h3>
                        <p class="text-sm text-stone-600 leading-relaxed">{{ $a->isi }}</p>
                    </div>
                </div>

                <form action="{{ route('manager.pengumuman.delete', $a->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                        <i class="fas fa-trash-alt text-xs"></i> Hapus
                    </button>
                </form>
            </div>
            @endforeach
        </div>

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@endsection