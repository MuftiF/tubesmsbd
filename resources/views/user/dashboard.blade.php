@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ============================================================ --}}
        {{-- HEADER --}}
        {{-- ============================================================ --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-0.5">Dashboard</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Pekerja Kebun</h1>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('l, j F Y') }}</p>
                    <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1">
                        PT. Sipirok Indah
                    </span>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ROW 1: STAT CARDS --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">

            {{-- Card 1: Total Panen Bulan Ini --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Panen Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ number_format($monthlyPalmWeight, 1) }}
                            <span class="text-base font-normal text-gray-400">kg</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 2: Rata-rata per Hari --}}
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rata-rata per Hari</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ number_format($averageDailyPalmWeight, 1) }}
                            <span class="text-base font-normal text-gray-400">kg/hari</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 3: Panen Hari Ini --}}
            <div class="bg-[#2c5e4e] rounded-2xl p-5 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-white/70 uppercase tracking-wide">Panen Hari Ini</p>
                        <p class="text-3xl font-bold text-white mt-1">
                            {{ number_format($todayPalmWeight, 1) }}
                            <span class="text-base font-normal text-white/55">kg</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-white/20">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Data real-time
                    </span>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- ROW 2: MAIN GRID --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kehadiran Detail - 2/3 width --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="px-7 py-5 border-b-2 border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-700">Status Kehadiran Hari Ini</h2>
                    </div>
                    <div class="p-7">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Check In --}}
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 transition-all hover:shadow-md">
                                <div class="w-16 h-16 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Waktu Masuk</p>
                                <p class="text-3xl font-bold text-gray-800 mb-3">
                                    @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                        {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                                    @else --
                                    @endif
                                </p>
                                <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                    {{ !empty($absenHariIni) && $absenHariIni->check_in ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                    @if(!empty($absenHariIni) && $absenHariIni->check_in) Tepat Waktu @else Belum @endif
                                </span>
                            </div>

                            {{-- Check Out --}}
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 transition-all hover:shadow-md">
                                <div class="w-16 h-16 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Waktu Pulang</p>
                                <p class="text-3xl font-bold text-gray-800 mb-3">
                                    @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                        {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                                    @else --
                                    @endif
                                </p>
                                <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-medium
                                    {{ !empty($absenHariIni) && $absenHariIni->check_out ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                    @if(!empty($absenHariIni) && $absenHariIni->check_out) Selesai @else Belum Pulang @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aksi Cepat - 1/3 width --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="px-7 py-5 border-b-2 border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700">Aksi Cepat</h3>
                    </div>
                    <div class="p-7">
                        <div class="flex flex-col gap-4">

                            {{-- Belum Absen Masuk --}}
                            @if(empty($absenHariIni) || !$absenHariIni->check_in)
                            <a href="{{ route('attendance.index') }}"
                               class="inline-flex items-center justify-center gap-3 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Absen Masuk</span>
                            </a>
                            @endif

                            {{-- Sudah Absen Masuk, Belum Checkout --}}
                            @if(!empty($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
                            <a href="{{ route('attendance.index') }}"
                                class="inline-flex items-center justify-center gap-3 bg-[#d4a373] hover:bg-[#b88352] text-white px-5 py-3.5 rounded-xl font-semibold transition-all hover:translate-y-[-2px] shadow-md w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Ambil Foto & Absen Pulang</span>
                            </a>
                            @endif

                            {{-- Selesai Absen --}}
                            @if(!empty($absenHariIni) && $absenHariIni->check_out)
                            <div class="bg-[#eaf4f1] rounded-xl p-6 text-center border border-[#2c5e4e]/20">
                                <svg class="w-12 h-12 mx-auto mb-3 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <h3 class="text-base font-semibold text-[#1f4a3d] mb-2">Absensi Selesai</h3>
                                <p class="text-sm text-gray-600">Terima kasih sudah bekerja keras hari ini!</p>
                            </div>
                            @endif

                            {{-- Tombol Riwayat --}}
                            <a href="{{ route('attendance.history') }}"
                               class="inline-flex items-center justify-center gap-3 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3.5 rounded-xl font-medium transition-all hover:translate-y-[-2px] border border-gray-200 w-full">
                                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Lihat Riwayat</span>
                            </a>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{-- ROW 3: INFO BOTTOM --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-6">
            <div class="bg-white rounded-xl p-5 flex gap-4 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Tips Perawatan Sawit</h3>
                    <p class="text-sm text-gray-600">Panen tepat waktu meningkatkan kualitas minyak sawit. Pastikan buah matang optimal sebelum dipanen.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 flex gap-4 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Pengumuman</h3>
                    <p class="text-sm text-gray-600 mb-2">Jadwal panen minggu depan akan diinformasikan lebih lanjut oleh mandor kebun.</p>
                    <a href="{{ route('pengumuman.user') }}" class="text-[#2c5e4e] text-sm font-medium hover:underline">Lihat semua →</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection