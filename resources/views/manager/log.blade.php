@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">LOG ABSENSI TIM</h1>
        <p class="text-gray-600">Monitoring kehadiran pegawai</p>
    </div>


    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Log Absensi</h3>
        <form action="{{ route('manager.log') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Pekerja</option>
                        <option value="security" {{ request('role') == 'security' ? 'selected' : '' }}>Security</option>
                        <option value="cleaning" {{ request('role') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                        <option value="kantoran" {{ request('role') == 'kantoran' ? 'selected' : '' }}>Kantoran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="tepat waktu" {{ request('status') == 'tepat waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <input type="text" name="search" placeholder="Cari nama..."
                           value="{{ request('search') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    Terapkan Filter
                </button>
                <a href="{{ route('manager.log') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $totalPegawai }}</div>
            <div class="text-sm text-gray-600">Total Pegawai</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $totalHadir }}</div>
            <div class="text-sm text-gray-600">Hadir Hari Ini</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $totalTerlambat }}</div>
            <div class="text-sm text-gray-600">Terlambat</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $totalAlpha }}</div>
            <div class="text-sm text-gray-600">Alpha</div>
        </div>
    </div>

    <!-- Attendance Log Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">
                Log Absensi - {{ \Carbon\Carbon::parse(request('date', \Carbon\Carbon::today('Asia/Jakarta')))->translatedFormat('d F Y') }}
            </h3>
            <div class="text-sm text-gray-600">
                Terakhir update: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
            </div>
        </div>

        @if($attendances->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Pegawai</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Masuk</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Pulang</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Catatan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Foto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($attendances as $attendance)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="text-sm font-semibold">{{ $attendance->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $attendance->user->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($attendance->user->role == 'user') bg-green-100 text-green-800
                                @elseif($attendance->user->role == 'security') bg-blue-100 text-blue-800
                                @elseif($attendance->user->role == 'cleaning') bg-yellow-100 text-yellow-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ ucfirst($attendance->user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($attendance->check_in)
                                <div class="text-sm font-semibold
                                    {{ $attendance->status == 'terlambat' ? 'text-yellow-600' : 'text-gray-900' }}">
                                    {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}
                                </div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($attendance->check_out)
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                                </div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($attendance->status)
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $attendance->status == 'tepat waktu' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $attendance->note ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($attendance->photo_path)
                                <a href="{{ asset('storage/' . $attendance->photo_path) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    Lihat Foto
                                </a>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <div class="text-gray-400 text-6xl mb-4">📊</div>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada data absensi</h3>
            <p class="text-gray-500">Tidak ada data absensi untuk tanggal yang dipilih.</p>
        </div>
        @endif
    </div>
</div>
@endsection
