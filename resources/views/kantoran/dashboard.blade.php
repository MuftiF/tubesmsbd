@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">

    {{-- HEADER --}}
    <div class="text-center mb-12">
        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
            <span class="text-5xl">🏢</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Dashboard Kantoran</h1>
        <p class="text-gray-500">Administrasi, Dokumentasi, dan Operasional Kantor</p>
    </div>

    {{-- STATS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-indigo-500 flex flex-col items-center">
        <div class="text-4xl mb-2"></div>
        <p class="text-3xl font-bold text-indigo-600 leading-none">8</p>
        <p class="text-gray-600 text-sm mt-1">Total Staf</p>
    </div>

    <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-green-500 flex flex-col items-center">
        <div class="text-4xl mb-2"></div>
        <p class="text-3xl font-bold text-green-600 leading-none">7</p>
        <p class="text-gray-600 text-sm mt-1">Hadir Bulan Ini</p>
    </div>

    <div class="bg-white shadow-md rounded-xl p-6 border-l-4 border-blue-500 flex flex-col items-center">
        <div class="text-4xl mb-2"></div>
        <p class="text-3xl font-bold text-blue-600 leading-none">15</p>
        <p class="text-gray-600 text-sm mt-1">Dokumen Masuk</p>
    </div>

</div>
</div>
    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- ABSENSI --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-5">Absensi Hari Ini</h3>

            <div class="flex items-center justify-between bg-green-50 px-4 py-3 rounded-lg border border-green-200 mb-5">
                <div class="flex items-center">
                    <span class="text-2xl mr-3"></span>
                    <p class="font-semibold text-gray-700">Status Kehadiran</p>
                </div>

                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    Aktif
                </span>
            </div>

            <a href="{{ route('attendance.index') }}"
                class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 rounded-lg shadow-md transform hover:scale-[1.02] transition">
                📍 Absen Sekarang
            </a>
        </div>

        {{-- TOOLS --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-5">Tools Kantor</h3>

            <div class="grid grid-cols-2 gap-4">

                {{-- RIWAYAT ABSEN (baru) --}}
                <a href="{{ route('attendance.history') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-lg text-center shadow-md font-semibold transform hover:scale-[1.02] transition">
                    <div class="text-2xl mb-2"></div>
                    <p class="text-sm">Riwayat Absen</p>
                </a>

                <a href="{{ route('attendance.index') }}"
                    class="bg-green-600 hover:bg-green-700 text-white py-4 rounded-lg text-center shadow-md font-semibold transform hover:scale-[1.02] transition">
                    <div class="text-2xl mb-2"></div>
                    <p class="text-sm">Absen</p>
                </a>

            </div>
        </div>

    </div>

</div>
@endsection
