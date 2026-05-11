@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER SECTION -->
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">{{ getGreeting() }}</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-[#2c5e4e]">{{ Auth::user()->name }}</h1>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- USER STATISTICS - Only for user role -->
        @if(Auth::user()->role === 'user')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <!-- Monthly Total -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Panen Bulan Ini</p>
                        <p class="text-4xl font-bold text-gray-800 mt-1">
                            {{ number_format($monthlyPalmWeight, 1) }} 
                            <span class="text-base font-medium text-gray-400">kg</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#2c5e4e]"></span>
                    <span class="text-sm text-gray-500">Data real-time</span>
                </div>
            </div>

            <!-- Daily Average -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Rata-rata per Hari</p>
                        <p class="text-4xl font-bold text-gray-800 mt-1">
                            {{ number_format($averageDailyPalmWeight, 1) }}
                            <span class="text-base font-medium text-gray-400">kg/hari</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#2c5e4e]"></span>
                    <span class="text-sm text-gray-500">Berdasarkan data aktif</span>
                </div>
            </div>

            <!-- Today's Harvest -->
            <div class="bg-[#2c5e4e] rounded-2xl p-6 border-none transition-all hover:bg-[#1f4a3d] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/75 uppercase tracking-wide">Panen Hari Ini</p>
                        <p class="text-4xl font-bold text-white mt-1">
                            {{ number_format($todayPalmWeight, 1) }}
                            <span class="text-base font-medium text-white/55">kg</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- MAIN CONTENT GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- ATTENDANCE STATUS - 2/3 width on desktop -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-7 py-5 bg-white border-b-2 border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-700">Status Kehadiran Hari Ini</h2>
                    </div>
                    
                    <div class="p-7">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Check In -->
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 transition-all hover:bg-white hover:border-[#eaf4f1]">
                                <div class="w-16 h-16 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <span class="inline-block px-4 py-1.5 rounded-full text-sm font-medium {{ !empty($absenHariIni) && $absenHariIni->check_in ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                    @if(!empty($absenHariIni) && $absenHariIni->check_in) 
                                        ✓ Sudah Absen 
                                    @else 
                                        ⏳ Belum Absen 
                                    @endif
                                </span>
                            </div>

                            <!-- Check Out -->
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200 transition-all hover:bg-white hover:border-[#eaf4f1]">
                                <div class="w-16 h-16 rounded-xl bg-[#eaf4f1] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                
                                @if(Auth::user()->role === 'user' && !empty($absenHariIni) && $absenHariIni->check_out)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-sm text-gray-500 mb-1">Berat Sawit</p>
                                        <p class="text-2xl font-bold text-[#2c5e4e]">
                                            {{ number_format($todayPalmWeight ?? 0, 1) }} kg
                                        </p>
                                    </div>
                                @endif

                                <span class="inline-block px-4 py-1.5 rounded-full text-sm font-medium mt-3 {{ !empty($absenHariIni) && $absenHariIni->check_out ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-gray-200 text-gray-600' }}">
                                    @if(!empty($absenHariIni) && $absenHariIni->check_out) 
                                        ✓ Selesai 
                                    @else 
                                        ⏳ Belum Pulang 
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS - 1/3 width on desktop -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-7 py-5 bg-white border-b-2 border-[#eaf4f1] flex items-center gap-3">
                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-700">Aksi Cepat</h2>
                    </div>
                    
                    <div class="p-7">
                        <div class="flex flex-col gap-4">
                            @if(empty($absenHariIni) || !$absenHariIni->check_in)
                                <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-3 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-3 rounded-xl font-semibold transition-all hover:-translate-y-0.5 shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Absen Masuk
                                </a>
                            @endif

                            @if(!empty($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
                                <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-3 bg-[#d4a373] hover:bg-[#b88352] text-white px-6 py-3 rounded-xl font-semibold transition-all hover:-translate-y-0.5 shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ambil Foto & Absen Pulang
                                </a>
                            @endif

                            @if(!empty($absenHariIni) && $absenHariIni->check_out)
                                <div class="bg-[#eaf4f1] rounded-xl p-6 text-center border border-transparent">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <h3 class="text-xl font-semibold text-[#1f4a3d] mb-2">Absensi Selesai</h3>
                                    <p class="text-sm text-gray-600">Terima kasih sudah bekerja keras!</p>
                                    @if(Auth::user()->role === 'user')
                                        <p class="mt-3 text-lg font-bold text-[#2c5e4e]">
                                            {{ number_format($todayPalmWeight ?? 0, 1) }} kg
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-3 mt-2">
                                <a href="{{ route('attendance.history') }}" class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-medium transition-all border border-gray-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Riwayat
                                </a>
                                <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-4 py-3 rounded-xl font-semibold transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                                    </svg>
                                    Absen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM INFO SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl p-5 flex gap-4 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:bg-gray-50">
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

            <div class="bg-white rounded-xl p-5 flex gap-4 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:bg-gray-50">
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

@php
function getGreeting() {
    $hour = now()->hour;
    if ($hour < 12) return 'Selamat Pagi';
    if ($hour < 18) return 'Selamat Siang';
    return 'Selamat Malam';
}
@endphp 