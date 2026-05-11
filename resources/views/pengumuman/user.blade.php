@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Pengumuman</h1>
            <p class="text-sm text-stone-500 mt-1">Informasi terbaru untuk seluruh pegawai</p>
        </div>

        {{-- List Pengumuman --}}
        @if($announcements->count())
        <div class="space-y-4">
            @foreach($announcements as $a)
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <h3 class="text-base md:text-lg font-bold text-stone-800 mb-2">{{ $a->judul }}</h3>
                <div class="h-px bg-emerald-50 my-3 rounded-full"></div>
                <p class="text-sm text-stone-600 leading-relaxed mb-4">
                    {{ $a->isi }}
                </p>
                <div class="flex justify-end">
                    <span class="inline-flex items-center gap-2 bg-[#f8f6f2] px-3 py-1.5 rounded-full text-xs font-medium text-stone-500">
                        <i class="fas fa-clock text-xs"></i> {{ $a->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl py-12 px-4 text-center border border-stone-200">
            <div class="text-4xl mb-3">📭</div>
            <p class="font-semibold text-stone-500 text-sm">Belum ada pengumuman</p>
            <span class="text-xs text-stone-400">Belum ada pengumuman yang tersedia saat ini</span>
        </div>
        @endif

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@endsection