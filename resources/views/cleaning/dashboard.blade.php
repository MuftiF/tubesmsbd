@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-10 px-4">

    {{-- HEADER --}}
    <div class="text-center mb-10">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
            <span class="text-4xl">🧹</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Cleaning Service</h1>
        <p class="text-gray-500 text-sm">Area Kebersihan dan Perawatan Fasilitas</p>
    </div>

    {{-- STATUS CARD --}}
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 mb-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Status Tugas Hari Ini</h2>

        <div class="grid grid-cols-2 gap-6">

            <div class="text-center">
                <div class="w-14 h-14 bg-blue-50 border border-blue-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <span class="text-2xl">⏰</span>
                </div>
                <p class="text-sm text-gray-500 mt-2">Masuk</p>
                <p class="font-bold text-gray-800 text-lg">06:00</p>
            </div>

            <div class="text-center">
                <div class="w-14 h-14 bg-orange-50 border border-orange-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <span class="text-2xl">✅</span>
                </div>
                <p class="text-sm text-gray-500 mt-2">Area Selesai</p>
                <p class="font-bold text-gray-800 text-lg">3/5</p>
            </div>

        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="space-y-5">

        {{-- ABSEN MASUK --}}
        @if(!isset($absenHariIni) || !$absenHariIni || !$absenHariIni->check_in)
        <a href="{{ route('attendance.index') }}"
           class="block text-center w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl shadow-md transform hover:scale-[1.02] transition">
            <span class="text-xl mr-2">📍</span> Absen Masuk
        </a>
        @endif

        {{-- ABSEN PULANG --}}
        @if(isset($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Absen Pulang</h3>

            <a href="{{ route('attendance.index') }}"
                class="block text-center w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md transform hover:scale-[1.02] transition">
                <span class="text-xl mr-2">📸</span> Ambil Foto & Absen Pulang
            </a>
        </div>
        @endif

        {{-- SELESAI --}}
        @if(isset($absenHariIni) && $absenHariIni->check_out)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center shadow-sm">
            <div class="text-5xl mb-3">🎉</div>
            <h3 class="text-xl font-bold text-green-800 mb-1">Absensi Selesai</h3>
            <p class="text-green-700 text-sm">Terima kasih sudah menjaga kebersihan hari ini!</p>

            <a href="{{ route('attendance.history') }}"
               class="inline-block mt-3 text-blue-600 hover:text-blue-800 font-semibold">
                Lihat Riwayat →
            </a>
        </div>
        @endif

    </div>

    {{-- QUICK ACTIONS --}}
    <div class="bg-white rounded-xl shadow-md p-6 mt-10 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>

        <div class="grid grid-cols-2 gap-4">

            <a href="{{ route('attendance.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-center shadow-sm font-semibold transition">
                <div class="text-2xl mb-1">📍</div>
                <p class="text-sm">Absen</p>
            </a>

            <a href="{{ route('attendance.history') }}"
                class="bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg text-center shadow-sm font-semibold transition">
                <div class="text-2xl mb-1">📋</div>
                <p class="text-sm">Riwayat</p>
            </a>

        </div>
    </div>

</div>
@endsection
