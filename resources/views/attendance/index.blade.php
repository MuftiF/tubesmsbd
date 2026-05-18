@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAF9] p-4 md:p-8">
    <div class="container mx-auto max-w-6xl px-2 sm:px-6">

        {{-- ============================================================ --}}
        {{-- HEADER --}}
        {{-- ============================================================ --}}
        <div class="mb-6 md:mb-8 pb-4 md:pb-5 border-b border-[#E2E8F0]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-[#eaf4f1] rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-8 md:h-8 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-0.5">Dashboard</p>
                        <h1 class="text-xl md:text-3xl font-bold text-[#2c5e4e]">Sistem Absensi</h1>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs md:text-sm text-gray-500">{{ now()->translatedFormat('l, j F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ALERT MESSAGES --}}
        {{-- ============================================================ --}}
        @if(session('success'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#e8f5f0] border border-[#2e7d5e]/20 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#2e7d5e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="text-sm md:text-base text-[#1f4a3d]">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 md:mb-5 p-3 md:p-4 rounded-xl bg-[#FDECEA] border border-[#C0392B]/20 flex items-center gap-3">
            <svg class="w-5 h-5 text-[#C0392B] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm md:text-base text-[#7B1C14]">{{ session('error') }}</p>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- STAT CARDS (Balanced) --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-6 md:mb-8">
            {{-- Card 1: Status Hari Ini --}}
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-5 border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status Hari Ini</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1 truncate">
                            @if(!$attendanceToday)
                                Belum Masuk
                            @elseif(!$attendanceToday->check_out)
                                Sedang Bekerja
                            @else
                                Selesai ✓
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 rounded-full text-xs font-medium
                        {{ !$attendanceToday ? 'bg-gray-200 text-gray-600' : ($attendanceToday->check_out ? 'bg-[#eaf4f1] text-[#2c5e4e]' : 'bg-[#fef5ed] text-[#d4a373]') }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(!$attendanceToday)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @elseif($attendanceToday->check_out)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            @endif
                        </svg>
                        @if(!$attendanceToday)
                            Segera Absen
                        @elseif(!$attendanceToday->check_out)
                            Sedang Shift
                        @else
                            Selesai
                        @endif
                    </span>
                </div>
            </div>

            {{-- Card 2: Kehadiran Bulan Ini --}}
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-5 border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kehadiran Bulan Ini</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $monthlyCount }} <span class="text-sm md:text-base font-normal text-gray-500">Hari</span></p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 3: Total Sawit (khusus user) --}}
            @if(Auth::user()->role == 'user')
            <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-5 border border-[#E2E8F0] shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Sawit</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ number_format($monthlyPalmWeight, 1) }} <span class="text-sm md:text-base font-normal text-gray-500">KG</span></p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-[#fef5ed] flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endif

            {{-- Card 4: Jam Sekarang (menyesuaikan jika ada card sawit) --}}
            <div class="{{ Auth::user()->role == 'user' ? '' : 'sm:col-span-2 lg:col-span-1' }} bg-[#2c5e4e] rounded-xl md:rounded-2xl p-4 md:p-5 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-white/70 uppercase tracking-wide">Jam Sekarang</p>
                        <p id="realtimeClock" class="text-xl md:text-3xl font-bold text-white mt-1 font-mono tracking-wider">00:00:00</p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0 ml-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB NAVIGATION --}}
        {{-- ============================================================ --}}
        <div class="flex gap-2 bg-white border border-[#E2E8F0] rounded-full p-1 w-fit mb-6 shadow-sm">
            <button onclick="showTab('today')" id="tab-today-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-[#2c5e4e] text-white shadow-md whitespace-nowrap">
                Status Hari Ini
            </button>
            <button onclick="showTab('absen')" id="tab-absen-btn" class="tab-btn px-4 md:px-6 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-600 hover:bg-[#eaf4f1] hover:text-[#2c5e4e] whitespace-nowrap">
                Form Absensi
            </button>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: STATUS HARI INI --}}
        {{-- ============================================================ --}}
        <div id="tab-today" class="tab-content">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex items-center gap-3">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h2 class="text-base md:text-lg font-semibold text-gray-700">Rincian Kehadiran Hari Ini</h2>
                </div>
                <div class="p-4 md:p-7">
                    @if($attendanceToday)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        {{-- CHECK IN --}}
                        <div class="bg-[#e8f5f0] rounded-xl p-4 md:p-6 border border-[#2e7d5e]/20">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-[#2e7d5e]/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2e7d5e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-800 text-sm md:text-base">Check In</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-[#2e7d5e]/10 gap-1">
                                    <span class="text-xs md:text-sm text-gray-600">Jam Masuk</span>
                                    <span class="font-semibold text-gray-800 text-sm md:text-base">{{ $attendanceToday->check_in ? $attendanceToday->check_in->format('H:i:s') : '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-[#2e7d5e]/10 gap-1">
                                    <span class="text-xs md:text-sm text-gray-600">Status</span>
                                    <span class="px-2 md:px-3 py-1 rounded-full text-xs font-semibold w-fit
                                        {{ $attendanceToday->status == 'hadir' ? 'bg-[#e8f5f0] text-[#2e7d5e]' : ($attendanceToday->status == 'telat' ? 'bg-[#FDECEA] text-[#C0392B]' : 'bg-[#fef5ed] text-[#d4a373]') }}">
                                        {{ ucfirst($attendanceToday->status) }}
                                    </span>
                                </div>
                                <div class="py-2">
                                    <span class="text-xs md:text-sm text-gray-600 block mb-1">Lokasi</span>
                                    <p class="text-xs md:text-sm text-gray-700 bg-white/60 rounded-lg p-2 break-words">{{ $attendanceToday->checkin_address ?? '—' }}</p>
                                </div>
                                @if($attendanceToday->photo_path)
                                <div>
                                    <span class="text-xs md:text-sm text-gray-600 block mb-1">Foto</span>
                                    <img src="{{ asset('storage/'.$attendanceToday->photo_path) }}" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border-2 border-white shadow-sm" alt="Foto check in">
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- CHECK OUT --}}
                        <div class="bg-[#eaf4f1] rounded-xl p-4 md:p-6 border border-[#2c5e4e]/20">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-[#2c5e4e]/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-800 text-sm md:text-base">Check Out</h3>
                            </div>
                            @if($attendanceToday->check_out)
                            <div class="space-y-3">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-[#2c5e4e]/10 gap-1">
                                    <span class="text-xs md:text-sm text-gray-600">Jam Pulang</span>
                                    <span class="font-semibold text-gray-800 text-sm md:text-base">{{ $attendanceToday->check_out->format('H:i:s') }}</span>
                                </div>
                                @if(Auth::user()->role == 'user')
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-[#2c5e4e]/10 gap-1">
                                    <span class="text-xs md:text-sm text-gray-600">Berat Sawit</span>
                                    <span class="font-semibold text-gray-800 text-sm md:text-base">{{ number_format($attendanceToday->palm_weight, 1) }} KG</span>
                                </div>
                                @endif
                                <div class="py-2">
                                    <span class="text-xs md:text-sm text-gray-600 block mb-1">Lokasi</span>
                                    <p class="text-xs md:text-sm text-gray-700 bg-white/60 rounded-lg p-2 break-words">{{ $attendanceToday->checkout_address ?? '—' }}</p>
                                </div>
                                @if($attendanceToday->checkout_photo_path)
                                <div>
                                    <span class="text-xs md:text-sm text-gray-600 block mb-1">Foto</span>
                                    <img src="{{ asset('storage/'.$attendanceToday->checkout_photo_path) }}" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border-2 border-white shadow-sm" alt="Foto check out">
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="text-center py-6 md:py-8">
                                <svg class="w-10 h-10 md:w-12 md:h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm md:text-base">Belum Check Out</p>
                                <p class="text-xs text-gray-400 mt-1">Anda masih dalam sesi kerja</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 md:py-12">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-[#eaf4f1] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-base md:text-lg font-semibold text-gray-800">Belum Ada Absensi Hari Ini</h3>
                        <p class="text-sm text-gray-500 mt-1">Silakan buka tab Form Absensi untuk mulai</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB: FORM ABSENSI --}}
        {{-- ============================================================ --}}
        <div id="tab-absen" class="tab-content hidden">
            <div class="bg-white rounded-xl md:rounded-2xl shadow-sm border border-[#E2E8F0] overflow-hidden">
                <div class="px-4 md:px-7 py-4 md:py-5 border-b border-[#eaf4f1] flex items-center gap-3">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#2c5e4e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h2 class="text-base md:text-lg font-semibold text-gray-700">
                        @if(!$attendanceToday)
                            Form Check In
                        @elseif(!$attendanceToday->check_out)
                            Form Check Out
                        @else
                            Absensi Selesai
                        @endif
                    </h2>
                </div>
                <div class="p-4 md:p-7">

                    {{-- CHECK IN FORM --}}
                    @if(!$attendanceToday)
                    <form action="{{ route('attendance.store') }}" method="POST" id="checkinForm">
                        @csrf

                        {{-- Camera --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Foto Check In
                                </span>
                            </label>
                          <div id="cameraContainer" class="relative rounded-xl overflow-hidden bg-gray-900 w-full md:max-w-2xl mx-auto">
    <video id="camera" autoplay playsinline class="w-full h-auto md:h-[480px] object-cover"></video>
    <canvas id="canvas" class="hidden"></canvas>
    <button type="button" onclick="captureCheckinPhoto()" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/95 hover:bg-white text-[#2c5e4e] font-semibold px-5 md:px-7 py-2 md:py-2.5 rounded-full shadow-lg transition-all text-sm md:text-base whitespace-nowrap flex items-center gap-2">
        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        Ambil Foto
    </button>
</div>
                            <div id="checkinPreviewContainer" class="hidden mt-3">
                                <div class="bg-[#eaf4f1] rounded-xl p-3 md:p-4 flex flex-col sm:flex-row items-center gap-3 md:gap-4">
                                    <img id="checkinPreview" src="" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border-2 border-white shadow" alt="Preview">
                                    <div class="flex-1 text-center sm:text-left">
                                        <p class="font-semibold text-gray-800 text-sm md:text-base">Foto berhasil diambil</p>
                                        <p class="text-xs text-gray-500">Pastikan wajah terlihat jelas</p>
                                        <button type="button" onclick="retakeCheckinPhoto()" class="mt-2 text-xs md:text-sm text-[#d4a373] hover:text-[#b88352] font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Ambil Ulang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="photo" id="photoInput">
                        </div>

                        {{-- Location --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Lokasi
                                </span>
                            </label>
                            <div class="bg-gray-50 rounded-xl p-3 flex items-start gap-2 border border-gray-200">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span id="locationText" class="text-xs md:text-sm text-gray-600 flex-1 break-words">Mengambil lokasi GPS…</span>
                            </div>
                            <input type="hidden" name="checkin_latitude" id="latitude">
                            <input type="hidden" name="checkin_longitude" id="longitude">
                            <input type="hidden" name="checkin_address" id="checkin_address">
                        </div>

                        {{-- Note --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Catatan
                                </span>
                            </label>
                            <textarea name="note" id="checkin_note" class="w-full px-3 md:px-4 py-2 md:py-3 rounded-xl border border-gray-200 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm md:text-base" rows="3" placeholder="Catatan tambahan (opsional)…"></textarea>
                        </div>

                        <button type="button" onclick="submitCheckin()" id="submitCheckinBtn" disabled class="w-full bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white font-semibold py-2.5 md:py-3 rounded-xl transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan & Check In
                        </button>
                    </form>

                    {{-- CHECK OUT FORM --}}
                    @elseif(!$attendanceToday->check_out)
                    <div class="mb-5 p-3 bg-[#eaf4f1] rounded-xl text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-[#2c5e4e] text-sm md:text-base">Anda check in pukul <strong>{{ $attendanceToday->check_in->format('H:i:s') }}</strong></p>
                    </div>

                    <form action="{{ route('attendance.checkout') }}" method="POST" id="checkoutForm">
                        @csrf

                        {{-- Camera --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Foto Check Out
                                </span>
                            </label>
                           <div id="cameraContainerCheckout" class="relative rounded-xl overflow-hidden bg-gray-900 w-full md:max-w-2xl mx-auto">
    <video id="cameraCheckout" autoplay playsinline class="w-full h-auto md:h-[480px] object-cover"></video>
    <canvas id="canvasCheckout" class="hidden"></canvas>
    <button type="button" onclick="captureCheckoutPhoto()" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/95 hover:bg-white text-[#2c5e4e] font-semibold px-5 md:px-7 py-2 md:py-2.5 rounded-full shadow-lg transition-all text-sm md:text-base whitespace-nowrap flex items-center gap-2">
        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        Ambil Foto
    </button>
</div>
                            <div id="checkoutPreviewContainer" class="hidden mt-3">
                                <div class="bg-[#eaf4f1] rounded-xl p-3 md:p-4 flex flex-col sm:flex-row items-center gap-3 md:gap-4">
                                    <img id="checkoutPreview" src="" class="w-16 h-16 md:w-20 md:h-20 rounded-xl object-cover border-2 border-white shadow" alt="Preview">
                                    <div class="flex-1 text-center sm:text-left">
                                        <p class="font-semibold text-gray-800 text-sm md:text-base">Foto berhasil diambil</p>
                                        <p class="text-xs text-gray-500">Pastikan wajah terlihat jelas</p>
                                        <button type="button" onclick="retakeCheckoutPhoto()" class="mt-2 text-xs md:text-sm text-[#d4a373] hover:text-[#b88352] font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Ambil Ulang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="checkout_photo" id="checkoutPhotoInput">
                        </div>

                        {{-- Location --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Lokasi
                                </span>
                            </label>
                            <div class="bg-gray-50 rounded-xl p-3 flex items-start gap-2 border border-gray-200">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span id="locationTextCheckout" class="text-xs md:text-sm text-gray-600 flex-1 break-words">Mengambil lokasi GPS…</span>
                            </div>
                            <input type="hidden" name="checkout_latitude" id="checkout_latitude">
                            <input type="hidden" name="checkout_longitude" id="checkout_longitude">
                            <input type="hidden" name="checkout_address" id="checkout_address">
                        </div>

                        {{-- Palm Weight (khusus user) --}}
                        @if(Auth::user()->role == 'user')
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    Berat Sawit (KG)
                                </span>
                            </label>
                            <input type="number" step="0.1" min="0" name="palm_weight" id="palm_weight" required class="w-full px-3 md:px-4 py-2 md:py-3 rounded-xl border border-gray-200 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm md:text-base" placeholder="0.0">
                        </div>
                        @endif

                        {{-- Note --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Catatan Kerja
                                </span>
                            </label>
                            <textarea name="note" id="checkout_note" class="w-full px-3 md:px-4 py-2 md:py-3 rounded-xl border border-gray-200 focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#2c5e4e]/20 outline-none transition text-sm md:text-base" rows="3" required placeholder="Ringkasan pekerjaan hari ini…"></textarea>
                        </div>

                        <button type="button" onclick="submitCheckout()" id="submitCheckoutBtn" disabled class="w-full bg-[#d4a373] hover:bg-[#b88352] text-white font-semibold py-2.5 md:py-3 rounded-xl transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Simpan & Check Out
                        </button>
                    </form>

                    {{-- DONE STATE --}}
                    @else
                    <div class="text-center py-8 md:py-12">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-[#e8f5f0] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-[#2e7d5e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-[#2c5e4e] mb-2">Absensi Selesai</h3>
                        <p class="text-sm text-gray-500">Terima kasih atas kerja keras Anda hari ini</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<script>
// ── CLOCK ───────────────────────────────────────────
let currentTime = new Date("{{ $serverTime ?? now() }}");

function updateClock() {
    currentTime.setSeconds(currentTime.getSeconds() + 1);
    const el = document.getElementById('realtimeClock');
    if (el) el.textContent = currentTime.toLocaleTimeString('id-ID', { hour12: false });
}
setInterval(updateClock, 1000);
updateClock();

// ── TABS ─────────────────────────────────────────────
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(`tab-${tab}`).classList.remove('hidden');

    const todayBtn = document.getElementById('tab-today-btn');
    const absenBtn = document.getElementById('tab-absen-btn');

    if (tab === 'today') {
        todayBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        todayBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        absenBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        absenBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
    } else {
        absenBtn.classList.add('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        absenBtn.classList.remove('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
        todayBtn.classList.remove('bg-[#2c5e4e]', 'text-white', 'shadow-md');
        todayBtn.classList.add('text-gray-600', 'bg-transparent', 'hover:bg-[#eaf4f1]');
    }
}

// ── CAMERA ──────────────────────────────────────────
async function initCamera(id) {
    const video = document.getElementById(id);
    if (!video) return;
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
        video.srcObject = stream;
    } catch (e) {
        alert('Kamera tidak dapat diakses. Periksa izin browser.');
    }
}

function stopCamera(video) {
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(t => t.stop());
        video.srcObject = null;
    }
}

function capture(videoId, canvasId) {
    const video = document.getElementById(videoId);
    const canvas = document.getElementById(canvasId);
    if (video.videoWidth === 0) { alert('Kamera belum siap, tunggu sebentar.'); return null; }
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const img = canvas.toDataURL('image/jpeg', 0.85);
    stopCamera(video);
    return img;
}

// ── CHECK IN ─────────────────────────────────────────
function captureCheckinPhoto() {
    const img = capture('camera', 'canvas');
    if (!img) return;
    document.getElementById('photoInput').value = img;
    document.getElementById('checkinPreview').src = img;
    document.getElementById('checkinPreviewContainer').classList.remove('hidden');
    document.getElementById('cameraContainer').classList.add('hidden');
    document.getElementById('submitCheckinBtn').disabled = false;
}

function retakeCheckinPhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('checkinPreviewContainer').classList.add('hidden');
    document.getElementById('cameraContainer').classList.remove('hidden');
    document.getElementById('submitCheckinBtn').disabled = true;
    initCamera('camera');
}

function submitCheckin() {
    if (!document.getElementById('photoInput').value) { alert('Silakan ambil foto terlebih dahulu.'); return; }
    const btn = document.getElementById('submitCheckinBtn');
    btn.innerHTML = '<svg class="w-4 h-4 md:w-5 md:h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
    btn.disabled = true;
    document.getElementById('checkinForm').submit();
}

// ── CHECK OUT ─────────────────────────────────────────
function captureCheckoutPhoto() {
    const img = capture('cameraCheckout', 'canvasCheckout');
    if (!img) return;
    document.getElementById('checkoutPhotoInput').value = img;
    document.getElementById('checkoutPreview').src = img;
    document.getElementById('checkoutPreviewContainer').classList.remove('hidden');
    document.getElementById('cameraContainerCheckout').classList.add('hidden');
    document.getElementById('submitCheckoutBtn').disabled = false;
}

function retakeCheckoutPhoto() {
    document.getElementById('checkoutPhotoInput').value = '';
    document.getElementById('checkoutPreviewContainer').classList.add('hidden');
    document.getElementById('cameraContainerCheckout').classList.remove('hidden');
    document.getElementById('submitCheckoutBtn').disabled = true;
    initCamera('cameraCheckout');
}

function submitCheckout() {
    if (!document.getElementById('checkoutPhotoInput').value) { alert('Silakan ambil foto terlebih dahulu.'); return; }
    const btn = document.getElementById('submitCheckoutBtn');
    btn.innerHTML = '<svg class="w-4 h-4 md:w-5 md:h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
    btn.disabled = true;
    document.getElementById('checkoutForm').submit();
}

// ── LOCATION ─────────────────────────────────────────
async function getLocation() {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition(
        async (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            const latInputs = ['latitude', 'checkout_latitude'];
            const lngInputs = ['longitude', 'checkout_longitude'];

            latInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = lat;
            });
            lngInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = lng;
            });

            let address = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
                const data = await res.json();
                if (data.display_name) address = data.display_name;
            } catch (e) {}

            const addressInputs = ['checkin_address', 'checkout_address'];
            addressInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = address;
            });

            const locationTexts = ['locationText', 'locationTextCheckout'];
            locationTexts.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = address;
                    el.classList.remove('location-loading');
                }
            });
        },
        () => {
            const locationTexts = ['locationText', 'locationTextCheckout'];
            locationTexts.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = 'Gagal mendapatkan lokasi';
            });
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
    );
}

// ── INIT ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    @if(!$attendanceToday || !$attendanceToday->check_out)
        showTab('absen');
        setTimeout(() => {
            initCamera('camera');
            initCamera('cameraCheckout');
        }, 350);
    @else
        showTab('today');
    @endif
    getLocation();
});
</script>
@endsection