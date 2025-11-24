@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10">

    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Riwayat Absensi</h1>
        <p class="text-gray-500 text-sm mt-2">Rekap kehadiran {{ Auth::user()->name }}</p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Total Absen</p>
            <h3 class="text-3xl font-bold text-blue-700 mt-1">{{ $riwayat->total() }}</h3>
        </div>

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Hadir</p>
            <h3 class="text-3xl font-bold text-green-700 mt-1">
                {{ $riwayat->where('status','hadir')->count() }}
            </h3>
        </div>

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Terlambat</p>
            <h3 class="text-3xl font-bold text-yellow-600 mt-1">
                {{ $riwayat->where('status','terlambat')->count() }}
            </h3>
        </div>

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Sudah Checkout</p>
            <h3 class="text-3xl font-bold text-purple-600 mt-1">
                {{ $riwayat->whereNotNull('check_out')->count() }}
            </h3>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Absensi</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Hari</th>
                        <th class="px-6 py-3 text-left">Masuk</th>
                        <th class="px-6 py-3 text-left">Pulang</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($riwayat as $absen)
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($absen->date)->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l') }}
                        </td>

                        <td class="px-6 py-4 font-semibold">
                            @if($absen->check_in)
                                {{ \Carbon\Carbon::parse($absen->check_in)->format('H:i') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-semibold">
                            @if($absen->check_out)
                                {{ \Carbon\Carbon::parse($absen->check_out)->format('H:i') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($absen->status == 'hadir')
                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700 font-semibold">Hadir</span>
                            @elseif($absen->status == 'terlambat')
                                <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700 font-semibold">Terlambat</span>
                            @elseif($absen->status == 'izin')
                                <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700 font-semibold">Izin</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700 font-semibold">{{ $absen->status }}</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $absen->note ?? '-' }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <div class="text-4xl mb-3">📝</div>
                            Tidak ada data absensi ditemukan
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($riwayat->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div class="text-sm text-gray-600">
                    Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} data
                </div>

                <div class="flex space-x-1">

                    {{-- Previous --}}
                    @if($riwayat->onFirstPage())
                        <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">←</span>
                    @else
                        <a href="{{ $riwayat->previousPageUrl() }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition">←</a>
                    @endif

                    {{-- Pages --}}
                    @foreach($riwayat->getUrlRange(1, $riwayat->lastPage()) as $page => $url)
                        @if($page == $riwayat->currentPage())
                            <span class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($riwayat->hasMorePages())
                        <a href="{{ $riwayat->nextPageUrl() }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition">→</a>
                    @else
                        <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">→</span>
                    @endif

                </div>

            </div>

        </div>
        @endif

    </div>

    <!-- Quick Action -->
    <div class="mt-10 text-center">
        <a href="{{ route('attendance.index') }}"
           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 shadow-md">
            <span class="mr-2">📍</span>
            Absensi Sekarang
        </a>
    </div>

</div>
@endsection
