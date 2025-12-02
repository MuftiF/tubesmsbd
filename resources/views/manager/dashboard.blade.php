@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    {{-- HEADER --}}
    <div class="text-center mb-12">
        <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
            <span class="text-5xl">👨‍💼</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Manager Dashboard</h1>
        <p class="text-gray-500">Monitoring tim dan produktivitas harian kebun sawit</p>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-blue-500">
            <p class="text-3xl font-bold text-blue-600">{{ $totalTim ?? 0 }}</p>
            <p class="text-gray-600 text-sm mt-1">Total Tim</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-green-500">
            <p class="text-3xl font-bold text-green-600">{{ $hadirHariIni ?? 0 }}</p>
            <p class="text-gray-600 text-sm mt-1">Hadir Hari Ini</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-yellow-500">
            <p class="text-3xl font-bold text-yellow-600">{{ number_format($produksiHariIni ?? 0, 1) }} kg</p>
            <p class="text-gray-600 text-sm mt-1">Produksi Hari Ini</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-red-500">
            <p class="text-3xl font-bold text-red-600">{{ $totalAlpha ?? 0 }}</p>
            <p class="text-gray-600 text-sm mt-1">Alpha</p>
        </div>

    </div>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        {{-- AKSI CEPAT --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-5">Aksi Cepat</h3>

            <div class="grid grid-cols-3 gap-4">

                <a href="{{ route('manager.laporan') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-4 text-center shadow-sm font-semibold transform hover:scale-[1.03] transition">
                    <div class="text-2xl mb-1"></div>
                    Laporan
                </a>

                <a href="{{ route('manager.log') }}"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-lg py-4 text-center shadow-sm font-semibold transform hover:scale-[1.03] transition">
                    <div class="text-2xl mb-1"></div>
                    Log Absensi
                </a>

                <a href="{{ route('manager.pegawai') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg py-4 text-center shadow-sm font-semibold transform hover:scale-[1.03] transition">
                    <div class="text-2xl mb-1"></div>
                    Pegawai
                </a>

            </div>
        </div>


        {{-- RINGKASAN --}}
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-5">Ringkasan Hari Ini</h3>

            <div class="space-y-4">

                <div class="flex items-center justify-between bg-green-50 px-4 py-3 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <span class="text-green-600 text-2xl mr-3"></span>
                        <p class="font-semibold text-gray-800">Kehadiran</p>
                    </div>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $totalTim > 0 ? round(($hadirHariIni / $totalTim) * 100) : 0 }}%
                    </span>
                </div>

                <div class="flex items-center justify-between bg-blue-50 px-4 py-3 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <span class="text-blue-600 text-2xl mr-3">🌴</span>
                        <p class="font-semibold text-gray-800">Produktivitas</p>
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

                <div class="flex items-center justify-between bg-yellow-50 px-4 py-3 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <span class="text-yellow-600 text-2xl mr-3"></span>
                        <p class="font-semibold text-gray-800">Keterlambatan</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold">{{ $totalTerlambat ?? 0 }}</span>
                </div>

            </div>
        </div>

    </div>


    {{-- TEAM ACTIVITY --}}
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold text-gray-800">Aktivitas Tim Terbaru</h3>
            <a href="{{ route('manager.log') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                Lihat Semua →
            </a>
        </div>

        <div class="space-y-3">
            @foreach($recentActivities as $activity)
            <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-green-600 text-xl">✓</span>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-800">{{ $activity->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ ucfirst($activity->user->role) }} |
                            Check In: {{ \Carbon\Carbon::parse($activity->check_in)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

</div>
@endsection
