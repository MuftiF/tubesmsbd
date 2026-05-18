@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">

        {{-- ============================================================ --}}
        {{-- HEADER SECTION --}}
        {{-- ============================================================ --}}
        <div class="mb-8 pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Kantoran</h1>
                        <p class="text-sm text-gray-500 mt-1">Administrasi, Dokumentasi, dan Operasional Kantor</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1">
                            PT. Sipirok Indah
                        </span>
                    </div>
                
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- STAT CARDS (tanpa hover border) --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">

            {{-- Total Staf --}}
            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Staf</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">8</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Hadir Bulan Ini --}}
            <div class="bg-white rounded-2xl p-5 border border-[#E2E8F0] transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Hadir Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">7</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Dokumen Masuk --}}
            <div class="bg-[#2c5e4e] rounded-2xl p-5 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-white/75 uppercase tracking-wide">Dokumen Masuk</p>
                        <p class="text-3xl font-bold text-white mt-1">15</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- MAIN GRID --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ABSENSI - 2/3 --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden h-full">
                    <div class="px-7 py-5 border-b border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700">Status Kehadiran Hari Ini</h3>
                    </div>

                    <div class="p-7">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- MASUK --}}
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-[#E2E8F0] transition-all hover:shadow-md">
                                <div class="w-14 h-14 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Waktu Masuk</p>
                                <p class="text-3xl font-bold text-gray-800 mb-3">
                                    @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                        {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                                    @else
                                        --
                                    @endif
                                </p>
                                <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                    {{ !empty($absenHariIni) && $absenHariIni->check_in ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                    @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                        Tepat Waktu
                                    @else
                                        Belum
                                    @endif
                                </span>
                            </div>

                            {{-- PULANG --}}
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-[#E2E8F0] transition-all hover:shadow-md">
                                <div class="w-14 h-14 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Waktu Pulang</p>
                                <p class="text-3xl font-bold text-gray-800 mb-3">
                                    @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                        {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                                    @else
                                        --
                                    @endif
                                </p>
                                <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                    {{ !empty($absenHariIni) && $absenHariIni->check_out ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                    @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                        Selesai
                                    @else
                                        Belum Pulang
                                    @endif
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- TOOLS / AKSI CEPAT - 1/3 --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden h-full">
                    <div class="px-7 py-5 border-b border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700">Aksi Cepat</h3>
                    </div>

                    <div class="p-7">
                        <div class="flex flex-col gap-4">
                            @if(isset($absenHariIni) && $absenHariIni->check_out)
                                {{-- Tampilkan pesan selesai dengan tinggi yang sama --}}
                                <div class="bg-[#eaf4f1] rounded-xl p-6 text-center border border-[#2c5e4e]/20 flex-1 flex flex-col items-center justify-center min-h-[200px]">
                                    <svg class="w-14 h-14 mx-auto mb-3 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-[#1f4a3d] mb-2">Absensi Selesai</h3>
                                    <p class="text-sm text-gray-600">Terima kasih sudah bekerja keras hari ini!</p>
                                </div>
                            @else
                                {{-- Tampilkan tombol aksi --}}
                                <a href="{{ route('attendance.history') }}"
                                    class="inline-flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3.5 rounded-xl font-medium transition-all border border-[#E2E8F0] w-full">
                                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Riwayat Absen
                                </a>

                                @if(!isset($absenHariIni) || !$absenHariIni || !$absenHariIni->check_in)
                                <a href="{{ route('attendance.index') }}"
                                   class="inline-flex items-center justify-center gap-3 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Absen Masuk</span>
                                </a>
                                @endif

                                @if(isset($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
                                <a href="{{ route('attendance.index') }}"
                                    class="inline-flex items-center justify-center gap-3 bg-[#d4a373] hover:bg-[#b88352] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Ambil Foto & Absen Pulang</span>
                                </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection