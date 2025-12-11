@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
            <span class="text-4xl">🌴</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 tracking-wide">PEKERJA SAWIT</h1>
        <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}</p>
    </div>

    {{-- ========================= --}}
    {{--   INFO PANEN UNTUK USER   --}}
    {{-- ========================= --}}
    @if(Auth::user()->role === 'user')

    <div class="grid grid-cols-2 gap-4 mb-6">

        <!-- TOTAL BULAN INI -->
        <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-600">Total Sawit Bulan Ini</p>
            <p class="text-3xl font-bold text-yellow-600">
                {{ number_format($monthlyPalmWeight, 1) }} kg
            </p>
        </div>

        <!-- RATA-RATA PER HARI -->
        <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-orange-500">
            <p class="text-sm text-gray-600">Rata-rata per Hari</p>
            <p class="text-3xl font-bold text-orange-600">
                {{ number_format($averageDailyPalmWeight, 1) }} kg
            </p>
        </div>

    </div>

    <!-- PANEN HARI INI -->
    <div class="bg-blue-50 rounded-xl shadow p-5 mb-6 border border-blue-200">
        <h3 class="font-bold text-blue-800 text-lg mb-2">Panen Hari Ini</h3>
        <p class="text-blue-700 text-xl font-semibold">
            {{ number_format($todayPalmWeight, 1) }} kg
        </p>
    </div>

    @endif
    {{-- ========================= --}}
    {{--        END INFO PANEN     --}}
    {{-- ========================= --}}

    <!-- STATUS CARD -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-l-4 border-green-600">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Status Kehadiran Hari Ini</h2>

        <div class="grid grid-cols-2 gap-4">
            <!-- MASUK -->
            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow">
                    <span class="text-xl">⏰</span>
                </div>
                <p class="text-sm text-gray-600">Masuk</p>

                <p class="font-bold text-gray-800">
                    @if(!empty($absenHariIni) && $absenHariIni->check_in)
                        {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                    @else
                        -
                    @endif
                </p>

                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full
                    @if(!empty($absenHariIni) && $absenHariIni->check_in) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                    @if(!empty($absenHariIni) && $absenHariIni->check_in) TEPAT WAKTU @else BELUM @endif
                </span>
            </div>

            <!-- PULANG -->
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow">
                    <span class="text-xl">🚪</span>
                </div>
                <p class="text-sm text-gray-600">Pulang</p>

                <p class="font-bold text-gray-800">
                    @if(!empty($absenHariIni) && $absenHariIni->check_out)
                        {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                    @else
                        -
                    @endif
                </p>

                {{-- TAMBAHAN: TAMPILKAN BERAT SAWIT --}}
                @if(Auth::user()->role === 'user' && !empty($absenHariIni) && $absenHariIni->check_out)
                    <p class="mt-1 text-sm">
                        Berat Sawit:
                        <span class="font-bold text-green-600">
                            {{ number_format($todayPalmWeight ?? 0, 1) }} kg
                        </span>
                    </p>
                @endif

                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full
                    @if(!empty($absenHariIni) && $absenHariIni->check_out) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                    @if(!empty($absenHariIni) && $absenHariIni->check_out) SELESAI @else BELUM @endif
                </span>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="space-y-4">

        @if(empty($absenHariIni) || !$absenHariIni->check_in)
        <!-- BELUM CHECK IN -->
        <a href="{{ route('attendance.index') }}"
           class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center justify-center">
            <span class="text-xl mr-3">📍</span>
            ABSEN MASUK
        </a>
        @endif

        @if(!empty($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
        <!-- CHECK IN SUDAH, BELUM CHECK OUT -->
        <div class="bg-white rounded-xl shadow-xl p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Absen Pulang</h3>

            <a href="{{ route('attendance.index') }}"
               class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-xl transition transform hover:scale-105 flex items-center justify-center">
                AMBIL FOTO & ABSEN PULANG
            </a>
        </div>
        @endif

        @if(!empty($absenHariIni) && $absenHariIni->check_out)
        <!-- SUDAH SELESAI -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center shadow">
            <h3 class="text-xl font-bold text-green-800">Absensi Selesai</h3>
            <p class="text-green-600">Terima kasih sudah bekerja keras hari ini!</p>

            {{-- TAMBAHAN: TAMPILKAN BERAT SAWIT --}}
            @if(Auth::user()->role === 'user')
                <p class="mt-2 text-gray-800">
                    Berat Sawit Hari Ini:
                    <b class="text-green-700">
                        {{ number_format($todayPalmWeight ?? 0, 1) }} kg
                    </b>
                </p>
            @endif

            <a href="{{ route('attendance.history') }}"
               class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                Lihat Riwayat →
            </a>
        </div>
        @endif
    </div>

    <!-- QUICK ACTIONS -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mt-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>

        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('attendance.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg text-center font-semibold transition transform hover:scale-105">
                <p class="text-sm">Absen</p>
            </a>

            <a href="{{ route('attendance.history') }}"
               class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-center font-semibold transition transform hover:scale-105">
                <p class="text-sm">Riwayat</p>
            </a>
        </div>
    </div>

</div>
@endsection
