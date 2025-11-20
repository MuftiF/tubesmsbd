@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-4xl">🏢</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">KANTORAN DASHBOARD</h1>
        <p class="text-gray-600">Administrasi & Operasional Kantor</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-indigo-500">
            <div class="text-3xl font-bold text-indigo-600">8</div>
            <div class="text-sm text-gray-600">Total Staf</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-green-500">
            <div class="text-3xl font-bold text-green-600">7</div>
            <div class="text-sm text-gray-600">Hadir</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-blue-500">
            <div class="text-3xl font-bold text-blue-600">15</div>
            <div class="text-sm text-gray-600">Dokumen</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-yellow-500">
            <div class="text-3xl font-bold text-yellow-600">3</div>
            <div class="text-sm text-gray-600">Meeting</div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Absensi Section -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Absensi</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">📍</span>
                        <div>
                            <div class="font-semibold">Status Hari Ini</div>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">AKTIF</span>
                </div>
                <a href="{{ route('attendance.index') }}"
                   class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 block text-center">
                    📍 ABSEN SEKARANG
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Tools Kantor</h3>
            <div class="grid grid-cols- gap-4">
                <a href="{{ route('manager.laporan') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white py-4 px-4 rounded-lg text-center font-semibold transition duration-200 transform hover:scale-105 block">
                    <div class="text-2xl mb-2">📊</div>
                    <div class="text-sm">Laporan</div>
                </a>
                <a href="{{ route('attendance.index') }}"
                   class="bg-green-500 hover:bg-green-600 text-white py-4 px-4 rounded-lg text-center font-semibold transition duration-200 transform hover:scale-105 block">
                    <div class="text-2xl mb-2">📍</div>
                    <div class="text-sm">Absen</div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
