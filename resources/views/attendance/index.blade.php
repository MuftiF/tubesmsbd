@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-4 md:py-8">
    <div class="container mx-auto px-3 md:px-4">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e] flex items-center gap-2">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Sistem Absensi
                </h1>
                <p class="text-sm md:text-base text-gray-600 mt-1">Kelola kehadiran dan aktivitas kerja Anda</p>
            </div>

            <div class="bg-white px-4 md:px-6 py-2 md:py-3 rounded-xl shadow-sm border w-full md:w-auto">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-[#2c5e4e] rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-sm md:text-base">{{ Auth::user()->name }}</h2>
                        <p class="text-xs md:text-sm text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="mb-4 md:mb-5 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 md:mb-5 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- CARD STATUS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 mb-6 md:mb-8">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Status Hari Ini</p>
                        <h2 class="text-lg md:text-2xl font-bold mt-1 text-[#2c5e4e]">
                            @if(!$attendanceToday)
                                Belum Masuk
                            @elseif($attendanceToday && !$attendanceToday->check_out)
                                Sedang Bekerja
                            @else
                                Selesai
                            @endif
                        </h2>
                    </div>
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Kehadiran Bulan Ini</p>
                        <h2 class="text-lg md:text-2xl font-bold mt-1 text-blue-600">{{ $monthlyCount }} Hari</h2>
                    </div>
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>

            @if(Auth::user()->role == 'user')
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Total Sawit</p>
                        <h2 class="text-lg md:text-2xl font-bold mt-1 text-yellow-600">{{ number_format($monthlyPalmWeight,1) }} KG</h2>
                    </div>
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-yellow-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs md:text-sm">Tanggal</p>
                        <h2 class="text-sm md:text-lg font-bold mt-1 text-purple-600">{{ now()->translatedFormat('d F Y') }}</h2>
                    </div>
                    <svg class="w-8 h-8 md:w-10 md:h-10 text-purple-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- TAB --}}
        <div class="flex justify-center gap-2 md:gap-3 mb-4 md:mb-6">
            <button
                onclick="showTab('today')"
                id="tab-today"
                class="px-4 md:px-6 py-2 md:py-2.5 rounded-full bg-[#2c5e4e] text-white font-semibold shadow transition-all active:scale-95 touch-manipulation flex items-center gap-2"
            >
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Hari Ini
            </button>
            <button
                onclick="showTab('absen')"
                id="tab-absen"
                class="px-4 md:px-6 py-2 md:py-2.5 rounded-full bg-gray-200 text-gray-700 font-semibold transition-all active:scale-95 touch-manipulation flex items-center gap-2"
            >
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Absensi
            </button>
        </div>

        {{-- TAB HARI INI --}}
        <div id="today" class="tab-content bg-white rounded-xl shadow-sm p-4 md:p-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Status Kehadiran Hari Ini
            </h2>

            @if($attendanceToday)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                    {{-- CHECK IN CARD --}}
                    <div class="border rounded-xl p-4 md:p-5 bg-gradient-to-br from-green-50 to-white">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-green-700">Check In</h3>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600 text-sm md:text-base">Jam Masuk</span>
                                <span class="font-bold text-base md:text-lg">{{ $attendanceToday->check_in ? $attendanceToday->check_in->format('H:i:s') : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600 text-sm md:text-base">Status</span>
                                <span class="font-bold capitalize px-2 py-1 rounded-full text-xs 
                                    @if($attendanceToday->status == 'tepat') bg-green-100 text-green-700
                                    @elseif($attendanceToday->status == 'terlambat') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ $attendanceToday->status }}
                                </span>
                            </div>
                            @if($attendanceToday->checkin_address)
                            <div class="py-2">
                                <p class="text-gray-600 text-sm md:text-base mb-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Lokasi Check In
                                </p>
                                <div class="bg-gray-50 rounded-lg p-2 md:p-3 text-xs md:text-sm text-gray-700 break-words">
                                    {{ Str::limit($attendanceToday->checkin_address, 100) }}
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($attendanceToday->photo_path)
                        <div class="mt-4 md:mt-5">
                            <p class="font-semibold mb-2 text-sm md:text-base flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Foto Check In
                            </p>
                            <img src="{{ asset('storage/' . $attendanceToday->photo_path) }}" class="w-24 h-24 md:w-32 md:h-32 rounded-lg border shadow object-cover" alt="Foto Check In">
                        </div>
                        @endif
                    </div>

                    {{-- CHECK OUT CARD --}}
                    <div class="border rounded-xl p-4 md:p-5 bg-gradient-to-br from-blue-50 to-white">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg md:text-xl font-bold text-blue-700">Check Out</h3>
                        </div>

                        @if($attendanceToday->check_out)
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="text-gray-600 text-sm md:text-base">Jam Pulang</span>
                                    <span class="font-bold text-base md:text-lg">{{ $attendanceToday->check_out->format('H:i:s') }}</span>
                                </div>
                                @if(Auth::user()->role == 'user')
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="text-gray-600 text-sm md:text-base">Berat Sawit</span>
                                    <span class="font-bold text-green-700 text-base md:text-lg">{{ number_format($attendanceToday->palm_weight,1) }} KG</span>
                                </div>
                                @endif
                                @if($attendanceToday->checkout_address)
                                <div class="py-2">
                                    <p class="text-gray-600 text-sm md:text-base mb-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Lokasi Check Out
                                    </p>
                                    <div class="bg-gray-50 rounded-lg p-2 md:p-3 text-xs md:text-sm text-gray-700 break-words">
                                        {{ Str::limit($attendanceToday->checkout_address, 100) }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if($attendanceToday->checkout_photo_path)
                            <div class="mt-4 md:mt-5">
                                <p class="font-semibold mb-2 text-sm md:text-base flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Foto Check Out
                                </p>
                                <img src="{{ asset('storage/' . $attendanceToday->checkout_photo_path) }}" class="w-24 h-24 md:w-32 md:h-32 rounded-lg border shadow object-cover" alt="Foto Check Out">
                            </div>
                            @endif
                        @else
                            <div class="text-center py-8 md:py-10 text-gray-500">
                                <svg class="w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm md:text-base">Belum Check Out</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12 md:py-16">
                    <svg class="w-16 h-16 md:w-20 md:h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-700">Belum Ada Absensi Hari Ini</h2>
                    <p class="text-gray-500 mt-2 text-sm md:text-base">Silakan buka tab absensi untuk mulai check in</p>
                </div>
            @endif
        </div>

        {{-- TAB ABSENSI dengan PREVIEW FOTO --}}
        <div id="absen" class="tab-content hidden bg-white rounded-xl shadow-sm p-4 md:p-6 mt-4 md:mt-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4 md:mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                @if(!$attendanceToday)
                    Form Check In
                @elseif(!$attendanceToday->check_out)
                    Form Check Out
                @else
                    Absensi Selesai
                @endif
            </h2>

            {{-- JAM --}}
            <div class="bg-gradient-to-r from-[#2c5e4e] to-[#3c7a66] rounded-xl text-white text-center p-4 md:p-6 mb-4 md:mb-6">
                <p class="text-sm md:text-base">{{ now()->translatedFormat('l, d F Y') }}</p>
                <h1 id="realtimeClock" class="text-3xl md:text-5xl font-bold mt-2 font-mono tracking-wider">00:00:00</h1>
            </div>

            @if(!$attendanceToday)
            {{-- FORM CHECK IN --}}
            <form action="{{ route('attendance.store') }}" method="POST" id="checkinForm">
                @csrf

                <div class="mb-4 md:mb-5">
                    <label class="font-semibold text-gray-700 block mb-2 text-sm md:text-base flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Foto Check In
                    </label>
                    
                    {{-- AREA KAMERA --}}
                    <div id="cameraContainer" class="relative bg-black rounded-xl overflow-hidden" style="min-height: 300px;">
                        <video id="camera" autoplay playsinline class="w-full h-full object-cover" style="min-height: 300px;"></video>
                        <canvas id="canvas" class="hidden"></canvas>
                        
                        {{-- Tombol Ambil Foto --}}
                        <button 
                            type="button" 
                            onclick="captureCheckinPhoto()" 
                            id="captureCheckinBtn"
                            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-white text-gray-800 px-6 md:px-8 py-2 md:py-3 rounded-full text-sm md:text-base font-semibold shadow-lg hover:bg-gray-100 transition-colors flex items-center gap-2 z-10"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ambil Foto
                        </button>
                    </div>

                    {{-- PREVIEW FOTO CHECKIN --}}
                    <div id="checkinPreviewContainer" class="hidden mt-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-start gap-4">
                                <img id="checkinPreview" class="w-32 h-32 rounded-lg border shadow object-cover" alt="Preview foto">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-700 mb-2">Foto telah diambil</p>
                                    <p class="text-sm text-gray-500 mb-3">Pastikan foto Anda jelas dan wajah terlihat</p>
                                    <button 
                                        type="button" 
                                        onclick="retakeCheckinPhoto()"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Ambil Ulang Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="photo" id="photoInput">
                </div>

                <input type="hidden" name="checkin_latitude" id="latitude">
                <input type="hidden" name="checkin_longitude" id="longitude">
                <input type="hidden" name="checkin_address" id="checkin_address">

                <div class="mb-4 md:mb-5">
                    <label class="font-semibold text-gray-700 block mb-2 text-sm md:text-base flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Catatan
                    </label>
                    <textarea name="note" rows="3" class="w-full border rounded-xl p-3 text-sm md:text-base focus:ring-2 focus:ring-[#2c5e4e] focus:border-transparent" placeholder="Catatan tambahan..."></textarea>
                </div>

                <button type="button" onclick="submitCheckin()" id="submitCheckinBtn" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 md:py-3.5 rounded-xl font-bold transition-colors active:scale-98 text-base md:text-lg flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan & Check In
                </button>
            </form>

            @elseif(!$attendanceToday->check_out)
            {{-- FORM CHECK OUT --}}
            <form action="{{ route('attendance.checkout') }}" method="POST" id="checkoutForm">
                @csrf

                <div class="bg-[#eaf4f1] rounded-xl p-3 md:p-4 mb-4 md:mb-5 text-center text-sm md:text-base flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Anda check in pukul <b>{{ $attendanceToday->check_in->format('H:i:s') }}</b>
                </div>

                <div class="mb-4 md:mb-5">
                    <label class="font-semibold text-gray-700 block mb-2 text-sm md:text-base flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Foto Check Out
                    </label>
                    
                    {{-- AREA KAMERA CHECKOUT --}}
                    <div id="cameraContainerCheckout" class="relative bg-black rounded-xl overflow-hidden" style="min-height: 300px;">
                        <video id="cameraCheckout" autoplay playsinline class="w-full h-full object-cover" style="min-height: 300px;"></video>
                        <canvas id="canvasCheckout" class="hidden"></canvas>
                        
                        <button 
                            type="button" 
                            onclick="captureCheckoutPhoto()" 
                            id="captureCheckoutBtn"
                            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-white text-gray-800 px-6 md:px-8 py-2 md:py-3 rounded-full text-sm md:text-base font-semibold shadow-lg hover:bg-gray-100 transition-colors flex items-center gap-2 z-10"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ambil Foto
                        </button>
                    </div>

                    {{-- PREVIEW FOTO CHECKOUT --}}
                    <div id="checkoutPreviewContainer" class="hidden mt-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-start gap-4">
                                <img id="checkoutPreview" class="w-32 h-32 rounded-lg border shadow object-cover" alt="Preview foto">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-700 mb-2">Foto telah diambil</p>
                                    <p class="text-sm text-gray-500 mb-3">Pastikan foto Anda jelas dan wajah terlihat</p>
                                    <button 
                                        type="button" 
                                        onclick="retakeCheckoutPhoto()"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Ambil Ulang Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="checkout_photo" id="checkoutPhotoInput">
                </div>

                <input type="hidden" name="checkout_latitude" id="checkout_latitude">
                <input type="hidden" name="checkout_longitude" id="checkout_longitude">
                <input type="hidden" name="checkout_address" id="checkout_address">

                @if(Auth::user()->role == 'user')
                <div class="mb-4 md:mb-5">
                    <label class="font-semibold text-gray-700 block mb-2 text-sm md:text-base flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                        </svg>
                        Berat Sawit (KG)
                    </label>
                    <input type="number" step="0.1" min="0" name="palm_weight" required class="w-full border rounded-xl p-3 text-sm md:text-base focus:ring-2 focus:ring-[#2c5e4e] focus:border-transparent" placeholder="Masukkan berat sawit">
                </div>
                @endif

                <div class="mb-4 md:mb-5">
                    <label class="font-semibold text-gray-700 block mb-2 text-sm md:text-base flex items-center gap-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Catatan
                    </label>
                    <textarea name="note" rows="3" required class="w-full border rounded-xl p-3 text-sm md:text-base focus:ring-2 focus:ring-[#2c5e4e] focus:border-transparent" placeholder="Catatan akhir hari..."></textarea>
                </div>

                <button type="button" onclick="submitCheckout()" id="submitCheckoutBtn" class="w-full bg-[#d4a373] hover:bg-[#b88352] text-white py-3 md:py-3.5 rounded-xl font-bold transition-colors active:scale-98 text-base md:text-lg flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan & Check Out
                </button>
            </form>

            @else
            <div class="text-center py-12 md:py-16">
                <svg class="w-16 h-16 md:w-20 md:h-20 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-xl md:text-2xl font-bold text-green-600">Absensi Selesai</h2>
                <p class="text-gray-500 mt-2 text-sm md:text-base">Terima kasih atas kerja keras Anda hari ini</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
let capturedCheckinPhoto = false;
let capturedCheckoutPhoto = false;
let checkinImageData = null;
let checkoutImageData = null;

// TAB functionality
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
    });
    document.getElementById(tab).classList.remove('hidden');
    
    ['today','absen'].forEach(t => {
        const btn = document.getElementById('tab-' + t);
        if(t === tab){
            btn.classList.remove('bg-gray-200','text-gray-700');
            btn.classList.add('bg-[#2c5e4e]','text-white');
        } else {
            btn.classList.remove('bg-[#2c5e4e]','text-white');
            btn.classList.add('bg-gray-200','text-gray-700');
        }
    });
}

// Real-time clock
let currentTime = new Date("{{ $serverTime }}");
function updateClock() {
    currentTime.setSeconds(currentTime.getSeconds() + 1);
    const clock = document.getElementById('realtimeClock');
    if(clock){
        clock.textContent = currentTime.toLocaleTimeString('id-ID', { hour12: false });
    }
}
setInterval(updateClock, 1000);
updateClock();

// Camera initialization
function initCamera(videoElementId) {
    const video = document.getElementById(videoElementId);
    if(video && !video.srcObject) {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Camera error:', err);
                alert('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
            });
    }
}

// Capture photo for checkin with preview
function captureCheckinPhoto() {
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    
    if(video && video.videoWidth > 0) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0);
        const image = canvas.toDataURL('image/jpeg');
        
        // Store image data
        checkinImageData = image;
        document.getElementById('photoInput').value = image;
        capturedCheckinPhoto = true;
        
        // Show preview
        const previewImg = document.getElementById('checkinPreview');
        previewImg.src = image;
        document.getElementById('checkinPreviewContainer').classList.remove('hidden');
        
        // Hide camera container
        document.getElementById('cameraContainer').classList.add('hidden');
        
        // Enable submit button
        document.getElementById('submitCheckinBtn').disabled = false;
        
        // Stop camera stream to save battery
        if(video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
    } else {
        alert('Mohon tunggu kamera siap');
    }
}

// Retake photo for checkin
function retakeCheckinPhoto() {
    // Reset state
    capturedCheckinPhoto = false;
    checkinImageData = null;
    document.getElementById('photoInput').value = '';
    document.getElementById('submitCheckinBtn').disabled = true;
    
    // Hide preview, show camera
    document.getElementById('checkinPreviewContainer').classList.add('hidden');
    document.getElementById('cameraContainer').classList.remove('hidden');
    
    // Restart camera
    const video = document.getElementById('camera');
    if(video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
    initCamera('camera');
}

// Capture photo for checkout with preview
function captureCheckoutPhoto() {
    const video = document.getElementById('cameraCheckout');
    const canvas = document.getElementById('canvasCheckout');
    
    if(video && video.videoWidth > 0) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0);
        const image = canvas.toDataURL('image/jpeg');
        
        // Store image data
        checkoutImageData = image;
        document.getElementById('checkoutPhotoInput').value = image;
        capturedCheckoutPhoto = true;
        
        // Show preview
        const previewImg = document.getElementById('checkoutPreview');
        previewImg.src = image;
        document.getElementById('checkoutPreviewContainer').classList.remove('hidden');
        
        // Hide camera container
        document.getElementById('cameraContainerCheckout').classList.add('hidden');
        
        // Enable submit button
        document.getElementById('submitCheckoutBtn').disabled = false;
        
        // Stop camera stream to save battery
        if(video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
    } else {
        alert('Mohon tunggu kamera siap');
    }
}

// Retake photo for checkout
function retakeCheckoutPhoto() {
    // Reset state
    capturedCheckoutPhoto = false;
    checkoutImageData = null;
    document.getElementById('checkoutPhotoInput').value = '';
    document.getElementById('submitCheckoutBtn').disabled = true;
    
    // Hide preview, show camera
    document.getElementById('checkoutPreviewContainer').classList.add('hidden');
    document.getElementById('cameraContainerCheckout').classList.remove('hidden');
    
    // Restart camera
    const video = document.getElementById('cameraCheckout');
    if(video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
    initCamera('cameraCheckout');
}

// Submit checkin
function submitCheckin() {
    if(!capturedCheckinPhoto) {
        alert('Silakan ambil foto terlebih dahulu');
        return;
    }
    
    const form = document.getElementById('checkinForm');
    const submitBtn = document.getElementById('submitCheckinBtn');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Memproses...';
    submitBtn.disabled = true;
    
    form.submit();
}

// Submit checkout
function submitCheckout() {
    if(!capturedCheckoutPhoto) {
        alert('Silakan ambil foto terlebih dahulu');
        return;
    }
    
    const form = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitCheckoutBtn');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Memproses...';
    submitBtn.disabled = true;
    
    form.submit();
}

// Location & address
navigator.geolocation.getCurrentPosition(async position => {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;
    
    if(document.getElementById('latitude')){
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }
    if(document.getElementById('checkout_latitude')){
        document.getElementById('checkout_latitude').value = lat;
        document.getElementById('checkout_longitude').value = lng;
    }
    
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
        const data = await response.json();
        const address = data.display_name ?? 'Lokasi tidak ditemukan';
        
        if(document.getElementById('checkin_address')){
            document.getElementById('checkin_address').value = address;
        }
        if(document.getElementById('checkout_address')){
            document.getElementById('checkout_address').value = address;
        }
    } catch (error) {
        console.log('Gagal mengambil alamat');
    }
}, error => {
    console.log('Gagal获取 lokasi:', error);
    if(document.getElementById('checkin_address')){
        document.getElementById('checkin_address').value = 'Lokasi tidak dapat ditentukan';
    }
});

// Initialize cameras when needed
document.addEventListener('DOMContentLoaded', () => {
    @if(!$attendanceToday || !$attendanceToday->check_out)
        showTab('absen');
        setTimeout(() => {
            initCamera('camera');
            initCamera('cameraCheckout');
        }, 100);
    @else
        showTab('today');
    @endif
});
</script>

<style>
/* Touch optimization */
.touch-manipulation {
    touch-action: manipulation;
}

.active\:scale-98:active {
    transform: scale(0.98);
}

/* Smooth transitions */
button, .transition-all, .transition-colors, .transition-shadow {
    transition: all 0.2s ease;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    input, textarea, button {
        font-size: 16px !important;
    }
}

/* Loading state */
button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Animation for loading spinner */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Camera container full view */
#cameraContainer, #cameraContainerCheckout {
    position: relative;
    width: 100%;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
}

#camera, #cameraCheckout {
    width: 100%;
    height: auto;
    min-height: 300px;
    max-height: 70vh;
    object-fit: cover;
}
</style>
@endsection