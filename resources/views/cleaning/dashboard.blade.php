@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-3xl">🧹</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">CLEANING SERVICE</h1>
        <p class="text-gray-600">Area Kebersihan & Perawatan</p>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-green-500">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Status Tugas Hari Ini</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-xl">⏰</span>
                </div>
                <p class="text-sm text-gray-600">Masuk</p>
                <p class="font-bold text-gray-800">06:00</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-xl">✅</span>
                </div>
                <p class="text-sm text-gray-600">Area Selesai</p>
                <p class="font-bold text-gray-800">3/5</p>
            </div>
        </div>
    </div>

<!-- Action Buttons -->
    <div class="space-y-4">
        @if(!isset($absenHariIni) || !$absenHariIni || !$absenHariIni->check_in)
        <a href="{{ route('attendance.index') }}"
           class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 flex items-center justify-center block text-center">
            <span class="text-xl mr-3">📍</span>
            ABSEN MASUK
        </a>
        @endif

        @if(isset($absenHariIni) && $absenHariIni && $absenHariIni->check_in && !$absenHariIni->check_out)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Absen Pulang</h3>

            <a href="{{ route('attendance.index') }}"
               class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg mb-3 transition duration-200 flex items-center justify-center block text-center">
                <span class="text-xl mr-2">📸</span>
                AMBIL FOTO & ABSEN PULANG
            </a>
        </div>
        @endif

        @if(isset($absenHariIni) && $absenHariIni && $absenHariIni->check_out)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
            <div class="text-4xl mb-2">✅</div>
            <h3 class="text-xl font-bold text-green-800 mb-2">Absensi Selesai</h3>
            <p class="text-green-600">Terima kasih sudah bekerja keras hari ini!</p>
            <a href="{{ route('attendance.history') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                Lihat Riwayat →
            </a>
        </div>
        @endif
    </div>
    &nbsp;

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('attendance.index') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition duration-200 block">
                <div class="text-xl mb-1">📍</div>
                <div class="text-sm">Absen</div>
            </a>
            <a href="{{ route('attendance.history') }}"
               class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition duration-200 block">
                <div class="text-xl mb-1">📋</div>
                <div class="text-sm">Riwayat</div>
            </a>
        </div>
    </div>
</div>
@endsection
