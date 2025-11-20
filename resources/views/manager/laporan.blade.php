@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-green-700">🌴 LAPORAN HASIL PANEN SAWIT</h1>
        <p class="text-gray-600">Monitoring dan analisis hasil panen pekerja sawit</p>
    </div>

    <!-- Filter Section -->
    <form method="GET" action="{{ route('manager.laporan') }}" class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Laporan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Role</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="security" {{ request('role') == 'security' ? 'selected' : '' }}>Security</option>
                    <option value="cleaning" {{ request('role') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                    <option value="kantoran" {{ request('role') == 'kantoran' ? 'selected' : '' }}>Kantoran</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Terapkan Filter
            </button>
            <a href="{{ route('manager.laporan') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                Reset
            </a>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-blue-500">
            <div class="text-2xl font-bold text-blue-600">{{ $totalPegawai }}</div>
            <div class="text-sm text-gray-600">Total Pekerja Sawit</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-green-500">
            <div class="text-2xl font-bold text-green-600">{{ number_format($totalPalmWeight, 1) }} kg</div>
            <div class="text-sm text-gray-600">Total Berat Sawit</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-yellow-500">
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($averagePalmWeight, 1) }} kg</div>
            <div class="text-sm text-gray-600">Rata-rata per Pekerja</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center border-l-4 border-orange-500">
            <div class="text-2xl font-bold text-orange-600">{{ $totalHadir }}</div>
            <div class="text-sm text-gray-600">Total Kehadiran</div>
        </div>
    </div>

    <!-- Main Report Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Daily Palm Weight Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🌴 Hasil Panen Harian (7 Hari Terakhir)</h3>
            @if($dailyPalmWeight->count() > 0)
                <div class="space-y-2">
                    @foreach($dailyPalmWeight as $daily)
                        <div class="flex items-center">
                            <div class="w-32 text-sm text-gray-600">{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    @php
                                        $maxWeight = $dailyPalmWeight->max('total_weight');
                                        $percentage = $maxWeight > 0 ? ($daily->total_weight / $maxWeight * 100) : 0;
                                    @endphp
                                    <div class="bg-green-500 h-full flex items-center justify-end pr-2 text-white text-xs font-semibold"
                                         style="width: {{ $percentage }}%">
                                        {{ number_format($daily->total_weight, 1) }} kg
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                    <div class="text-center text-gray-500">
                        <div class="text-4xl mb-2">📊</div>
                        <div>Tidak ada data hasil panen</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Daily Attendance Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">👥 Kehadiran Harian (7 Hari Terakhir)</h3>
            @if($dailyAttendance->count() > 0)
                <div class="space-y-2">
                    @foreach($dailyAttendance as $daily)
                        <div class="flex items-center">
                            <div class="w-32 text-sm text-gray-600">{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-blue-500 h-full flex items-center justify-end pr-2 text-white text-xs font-semibold"
                                         style="width: {{ $totalPegawai > 0 ? ($daily->total / $totalPegawai * 100) : 0 }}%">
                                        {{ $daily->total }} pekerja
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                    <div class="text-center text-gray-500">
                        <div class="text-4xl mb-2">📊</div>
                        <div>Tidak ada data kehadiran</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Performers by Palm Weight -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🏆 Pekerja Terbaik (Hasil Panen Terbanyak)</h3>
            @if($topPerformers->count() > 0)
                <div class="space-y-3">
                    @foreach($topPerformers as $index => $performer)
                        @php
                            $bgColor = $index == 0 ? 'bg-green-50' : ($index == 1 ? 'bg-blue-50' : 'bg-yellow-50');
                            $badgeColor = $index == 0 ? 'bg-green-100 text-green-800' : ($index == 1 ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800');
                            $numberColor = $index == 0 ? 'text-green-600 bg-green-100' : ($index == 1 ? 'text-blue-600 bg-blue-100' : 'text-yellow-600 bg-yellow-100');
                            $badge = $index == 0 ? '🥇 Top' : ($index == 1 ? '🥈 Excellent' : '🥉 Good');
                        @endphp
                        <div class="flex items-center justify-between p-3 {{ $bgColor }} rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 {{ $numberColor }} rounded-full flex items-center justify-center mr-3">
                                    <span class="font-bold text-lg">{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $performer->user->name }}</div>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-bold text-green-600">{{ number_format($performer->total_weight, 1) }} kg</span>
                                        <span class="text-gray-400">•</span>
                                        {{ $performer->total_hadir }} hari kerja
                                    </div>
                                </div>
                            </div>
                            <span class="{{ $badgeColor }} px-3 py-1 rounded-full text-xs font-bold">{{ $badge }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-48 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                    <div class="text-center text-gray-500">
                        <div class="text-4xl mb-2">🏆</div>
                        <div>Tidak ada data pekerja terbaik</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Palm Weight by Role -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Hasil Panen per Role</h3>
            @if($palmWeightByRole->count() > 0)
                <div class="space-y-3">
                    @foreach($palmWeightByRole as $role => $data)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                                <span class="font-semibold capitalize text-gray-800">{{ $role }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-green-600 text-lg">{{ number_format($data['total_weight'], 1) }} kg</div>
                                <div class="text-sm text-gray-500">
                                    {{ $data['total_workers'] }} pekerja
                                    <span class="text-gray-400">•</span>
                                    Avg: {{ number_format($data['avg_weight'], 1) }} kg
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-48 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                    <div class="text-center text-gray-500">
                        <div class="text-4xl mb-2">📊</div>
                        <div>Tidak ada data hasil panen per role</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Detail Laporan Hasil Panen</h3>
        @if($detailedAttendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-green-50 to-blue-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Pekerja</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Check In</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Check Out</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Berat Sawit</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($detailedAttendances as $attendance)
                            <tr class="hover:bg-green-50 transition duration-150">
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $attendance->user->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold capitalize">
                                        {{ $attendance->user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($attendance->palm_weight)
                                        <span class="font-bold text-green-600 text-base">{{ number_format($attendance->palm_weight, 1) }} kg</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($attendance->status == 'tepat waktu')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                            ✓ Tepat Waktu
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                            ⚠ Terlambat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($attendance->checkout_photo_path)
                                        <a href="{{ asset('storage/' . $attendance->checkout_photo_path) }}" target="_blank"
                                           class="text-blue-600 hover:text-blue-800 font-semibold">
                                            📸 Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $detailedAttendances->links() }}
            </div>
        @else
            <div class="h-48 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <div class="text-center text-gray-500">
                    <div class="text-4xl mb-2">📋</div>
                    <div>Tidak ada data hasil panen</div>
                    <div class="text-sm mt-2">Silakan pilih periode atau filter lain</div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
