@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#eaf4f1] rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
</svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-0.5">Dashboard</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[#2c5e4e]">Cleaning Service</h1>
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

        {{-- ROW 1: STAT CARDS (tanpa hover border) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Shift Masuk</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">06:00</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Area Selesai</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
    {{ $jumlahAreaHariIni }}
</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Waktu Masuk</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                        {{ !empty($absenHariIni) && $absenHariIni->check_in ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-500' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(!empty($absenHariIni) && $absenHariIni->check_in)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                        @if(!empty($absenHariIni) && $absenHariIni->check_in) Tepat Waktu @else Belum Masuk @endif
                    </span>
                </div>
            </div>

            <div class="bg-[#2c5e4e] rounded-2xl p-5 transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-white/70 uppercase tracking-wide">Waktu Pulang</p>
                        <p class="text-3xl font-bold text-white mt-1">
                            @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                            @else
                                --
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-white/20">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                        {{ !empty($absenHariIni) && $absenHariIni->check_out ? 'bg-white/20 text-white' : 'bg-white/10 text-white/60' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        {{-- ROW 2: MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kehadiran Detail - 2/3 (tanpa hover border) --}}
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

            {{-- Aksi Cepat - 1/3 --}}
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

                            @if(isset($absenHariIni) && $absenHariIni->check_out)
                            <div class="bg-[#eaf4f1] rounded-xl p-6 text-center border border-[#2c5e4e]/20">
                                <svg class="w-12 h-12 mx-auto mb-3 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <h3 class="text-base font-semibold text-[#1f4a3d] mb-2">Absensi Selesai</h3>
                                <p class="text-sm text-gray-600">Terima kasih sudah menjaga kebersihan hari ini!</p>
                            </div>
                            @endif

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

    </div>
</div>
@endsection