@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-4xl">👨‍💼</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">MANAGER DASHBOARD</h1>
        <p class="text-gray-600">Monitor tim dan produktivitas kebun sawit</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-blue-500 transform hover:scale-105 transition duration-200">
            <div class="text-3xl font-bold text-blue-600">{{ $totalTim ?? 0 }}</div>
            <div class="text-sm text-gray-600">Total Tim</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-green-500 transform hover:scale-105 transition duration-200">
            <div class="text-3xl font-bold text-green-600">{{ $hadirHariIni ?? 0 }}</div>
            <div class="text-sm text-gray-600">Hadir Hari Ini</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-yellow-500 transform hover:scale-105 transition duration-200">
            <div class="text-3xl font-bold text-yellow-600">{{ number_format($produksiHariIni ?? 0, 1) }} kg</div>
            <div class="text-sm text-gray-600">Produksi Hari Ini</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-red-500 transform hover:scale-105 transition duration-200">
            <div class="text-3xl font-bold text-red-600">{{ $totalAlpha ?? 0 }}</div>
            <div class="text-sm text-gray-600">Alpha</div>
        </div>
    </div>

    <!-- Main Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('manager.laporan') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-4 px-4 rounded-lg text-center font-semibold transition duration-200 transform hover:scale-105 block">
                    <div class="text-2xl mb-2">📊</div>
                    <div class="text-sm">Laporan</div>
                </a>
                <a href="{{ route('manager.log') }}" class="bg-green-500 hover:bg-green-600 text-white py-4 px-4 rounded-lg text-center font-semibold transition duration-200 transform hover:scale-105 block">
                    <div class="text-2xl mb-2">📋</div>
                    <div class="text-sm">Log Absensi</div>
                </a>
                <a href="{{ route('manager.pegawai') }}" class="bg-purple-500 hover:bg-purple-600 text-white py-4 px-4 rounded-lg text-center font-semibold transition duration-200 transform hover:scale-105 block">
                    <div class="text-2xl mb-2">👥</div>
                    <div class="text-sm">Kelola Pegawai</div>
                </a>
            </div>
        </div>

        <!-- Today's Overview -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Hari Ini</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-green-600 text-xl mr-3">✅</span>
                        <span class="font-semibold">Kehadiran</span>
                    </div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $totalTim > 0 ? round(($hadirHariIni / $totalTim) * 100) : 0 }}%
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-blue-600 text-xl mr-3">🌴</span>
                        <span class="font-semibold">Produktivitas</span>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold">
                        @if($produksiHariIni > 100)
                            Tinggi
                        @elseif($produksiHariIni > 50)
                            Sedang
                        @else
                            Rendah
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="text-yellow-600 text-xl mr-3">⏰</span>
                        <span class="font-semibold">Keterlambatan</span>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold">{{ $totalTerlambat ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Activity -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Aktivitas Tim Terbaru</h3>
            <a href="{{ route('manager.log') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                Lihat Semua →
            </a>
        </div>
        <div class="space-y-3">
            @foreach($recentActivities as $activity)
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-green-600">✓</span>
                    </div>
                    <div>
                        <div class="font-semibold">{{ $activity->user->name }}</div>
                        <div class="text-sm text-gray-600">{{ ucfirst($activity->user->role) }} | Check In: {{ $activity->check_in }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-green-600">{{ \Carbon\Carbon::parse($activity->check_in)->format('H:i') }}</div>
                    <div class="text-sm text-gray-500">Masuk</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
