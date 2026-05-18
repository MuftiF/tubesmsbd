@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Informasi</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Pengumuman</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi terbaru untuk seluruh pegawai</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- List Pengumuman --}}
        @if($announcements->count())
        <div class="space-y-4">
            @foreach($announcements as $a)
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 transition-all duration-200 hover:border-[#eaf4f1] hover:shadow-md hover:-translate-y-0.5">
                <h3 class="text-base font-semibold text-gray-800 mb-2">{{ $a->judul }}</h3>
                <div class="h-px bg-[#eaf4f1] my-3 rounded-full"></div>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ $a->isi }}</p>
                <div class="flex justify-end">
                    <span class="inline-flex items-center gap-1.5 bg-gray-100 px-3 py-1.5 rounded-full text-xs font-medium text-gray-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $a->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl py-12 px-4 text-center border border-gray-200">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
            <p class="font-semibold text-gray-500 text-sm">Belum ada pengumuman</p>
            <span class="text-xs text-gray-400">Belum ada pengumuman yang tersedia saat ini</span>
        </div>
        @endif

    </div>
</div>
@endsection