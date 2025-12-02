@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10">

    <!-- Header -->
    <div class="mb-10 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
            <span class="text-4xl">👑</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Admin Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Dashboard Sistem Manajemen Absensi Perusahaan Sawit</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-10">
        @php 
            $stats = [
                ['label' => 'Total Pegawai', 'value' => $totalPegawai ?? 0, 'color' => 'blue'],
                ['label' => 'Hadir Hari Ini', 'value' => $hadirHariIni ?? 0, 'color' => 'green'],
                ['label' => 'Produksi Hari Ini', 'value' => number_format($produksiHariIni ?? 0, 1) . " kg", 'color' => 'purple'],
                ['label' => 'Rate Kehadiran', 'value' => ($rateKehadiran ?? 0) . "%", 'color' => 'yellow'],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6">
            <div class="text-xl font-bold text-{{ $s['color'] }}-600">
                {{ $s['value'] }}
            </div>
            <div class="text-gray-600 text-sm mt-1">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Main Panels -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        
        <!-- Quick Action -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-5">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.laporan') }}" 
                   class="p-5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-center font-medium shadow transition">
                    <div class="text-2xl mb-1"></div>
                    Laporan
                </a>

                <a href="{{ route('admin.pegawai') }}"
                   class="p-5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-center font-medium shadow transition">
                    <div class="text-2xl mb-1"></div>
                    Data Pegawai
                </a>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Hari Ini</h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg border border-green-100">
                    <span class="text-gray-800 font-semibold flex items-center">
                        <span class="text-green-600 text-xl mr-2"></span> Kehadiran
                    </span>
                    <span class="text-green-700 font-semibold px-3 py-1 bg-green-100 rounded-full text-sm">
                        {{ $rateKehadiran ?? 0 }}%
                    </span>
                </div>

                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <span class="text-gray-800 font-semibold flex items-center">
                        <span class="text-blue-600 text-xl mr-2"></span>Produktivitas
                    </span>
                    <span class="text-blue-700 font-semibold px-3 py-1 bg-blue-100 rounded-full text-sm">
                        @if(($produksiHariIni ?? 0) > 100) Tinggi
                        @elseif(($produksiHariIni ?? 0) > 50) Sedang
                        @else Rendah
                        @endif
                    </span>
                </div>

                <div class="flex justify-between items-center p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                    <span class="text-gray-800 font-semibold flex items-center">
                        <span class="text-yellow-600 text-xl mr-2"></span> Total Pegawai
                    </span>
                    <span class="text-yellow-700 font-semibold px-3 py-1 bg-yellow-100 rounded-full text-sm">
                        {{ $totalPegawai ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Activities -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>

            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mr-3 text-green-600 text-xl">
                            ✓
                        </div>

                        <div>
                            <div class="font-semibold text-gray-900">{{ $activity->user->name }}</div>
                            <div class="text-sm text-gray-600">
                                {{ ucfirst($activity->user->role) }} — 
                                Check In: {{ $activity->check_in ? \Carbon\Carbon::parse($activity->check_in)->format('H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                @empty
                <div class="text-gray-500 text-sm">Belum ada aktivitas hari ini.</div>
                @endforelse
            </div>
        </div>

        <!-- Department Overview -->
        <div class="bg-white shadow-md rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Overview Departemen</h3>

            <div class="space-y-4">
                @foreach($departments as $role => $dept)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="font-medium text-gray-700">{{ $dept['name'] }}</span>
                        <span class="font-semibold text-green-700">{{ $dept['hadir'] }}/{{ $dept['total'] }}</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-green-500" style="width: {{ $dept['percentage'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
