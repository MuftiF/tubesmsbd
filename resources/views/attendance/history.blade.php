@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">RIWAYAT ABSENSI</h1>
        <p class="text-gray-600">History kehadiran {{ Auth::user()->name }}</p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-xl font-bold text-blue-600">{{ $riwayat->total() }}</div>
            <div class="text-sm text-gray-600">Total Absen</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-xl font-bold text-green-600">
                {{ $riwayat->where('status', 'hadir')->count() }}
            </div>
            <div class="text-sm text-gray-600">Hadir</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-xl font-bold text-yellow-600">
                {{ $riwayat->where('status', 'terlambat')->count() }}
            </div>
            <div class="text-sm text-gray-600">Terlambat</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-xl font-bold text-purple-600">
                {{ $riwayat->whereNotNull('check_out')->count() }}
            </div>
            <div class="text-sm text-gray-600">Selesai</div>
        </div>
    </div>

    <!-- Attendance History Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Daftar Absensi</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Hari</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Masuk</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Pulang</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($riwayat as $absen)
                    <tr>
                        <td class="px-4 py-3 text-sm">
                            {{ \Carbon\Carbon::parse($absen->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold">
                            @if($absen->check_in)
                                {{ \Carbon\Carbon::parse($absen->check_in)->format('H:i') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold">
                            @if($absen->check_out)
                                {{ \Carbon\Carbon::parse($absen->check_out)->format('H:i') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($absen->status == 'hadir')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Hadir</span>
                            @elseif($absen->status == 'terlambat')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Terlambat</span>
                            @elseif($absen->status == 'izin')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Izin</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ $absen->status }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $absen->note ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="text-lg mb-2">📝</div>
                            Belum ada data absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($riwayat->hasPages())
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $riwayat->firstItem() }} - {{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} data
                </div>
                <div class="flex space-x-1">
                    {{-- Previous Page Link --}}
                    @if($riwayat->onFirstPage())
                        <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">← Previous</span>
                    @else
                        <a href="{{ $riwayat->previousPageUrl() }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition duration-200">
                            ← Previous
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach($riwayat->getUrlRange(1, $riwayat->lastPage()) as $page => $url)
                        @if($page == $riwayat->currentPage())
                            <span class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition duration-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($riwayat->hasMorePages())
                        <a href="{{ $riwayat->nextPageUrl() }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition duration-200">
                            Next →
                        </a>
                    @else
                        <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">Next →</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Action -->
    <div class="mt-6 text-center">
        <a href="{{ route('attendance.index') }}"
           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
            <span class="mr-2">📍</span>
            Absensi
        </a>
    </div>
</div>
@endsection
