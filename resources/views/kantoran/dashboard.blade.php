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

            <div class="grid grid-cols-2 gap-4">
            <!-- MASUK -->
            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow">
                    <span class="text-xl">⏰</span>
                </div>
                <p class="text-sm text-gray-600">Masuk</p>

                <p class="font-bold text-gray-800">
                    @if(!empty($absenHariIni) && $absenHariIni->check_in)
                        {{ \Carbon\Carbon::parse($absenHariIni->check_in)->format('H:i') }}
                    @else
                        -
                    @endif
                </p>

                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full
                    @if(!empty($absenHariIni) && $absenHariIni->check_in) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                    @if(!empty($absenHariIni) && $absenHariIni->check_in) TEPAT WAKTU @else BELUM @endif
                </span>
            </div>

            <!-- PULANG -->
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2 shadow">
                    <span class="text-xl">🚪</span>
                </div>
                <p class="text-sm text-gray-600">Pulang</p>

                <p class="font-bold text-gray-800">
                    @if(!empty($absenHariIni) && $absenHariIni->check_out)
                        {{ \Carbon\Carbon::parse($absenHariIni->check_out)->format('H:i') }}
                    @else
                        -
                    @endif
                </p>

                <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full
                    @if(!empty($absenHariIni) && $absenHariIni->check_out) bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                    @if(!empty($absenHariIni) && $absenHariIni->check_out) SELESAI @else BELUM @endif
                </span>
            </div>
        </div>
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

                <div class="space-y-5">

        {{-- ABSEN MASUK --}}
        @if(!isset($absenHariIni) || !$absenHariIni || !$absenHariIni->check_in)
        <a href="{{ route('attendance.index') }}"
           class="block text-center w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl shadow-md transform hover:scale-[1.02] transition">
            <span class="text-xl mr-2"></span> Absen Masuk
        </a>
        @endif

        {{-- ABSEN PULANG --}}
        @if(isset($absenHariIni) && $absenHariIni->check_in && !$absenHariIni->check_out)
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Absen Pulang</h3>

            <a href="{{ route('attendance.index') }}"
                class="block text-center w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-5 rounded-lg shadow-md transform hover:scale-[1.02] transition">
                <span class="text-xl mr-2"></span> Ambil Foto & Absen Pulang
            </a>
        </div>
        @endif

        {{-- SELESAI --}}
        @if(isset($absenHariIni) && $absenHariIni->check_out)
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center shadow-sm">
            <div class="text-5xl mb-3"></div>
            <h3 class="text-xl font-bold text-green-800 mb-1">Absensi Selesai</h3>
        </div>
        @endif

    </div>

            </div>
        </div>

    </div>

</div>
@endsection
