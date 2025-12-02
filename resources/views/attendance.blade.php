@extends('layouts.app')

@section('content')
<div class="p-6 bg-blue-50 min-h-screen">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-indigo-700">Sistem Absensi</h1>
            <p class="text-gray-600">Kelola kehadiran pribadi Anda</p>
        </div>
        <div class="bg-white shadow-md px-4 py-3 rounded-xl text-right border">
            <h2 class="font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
            <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role ?? 'Karyawan' }}</p>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-indigo-500">
            <p class="text-gray-600">Status Hari Ini</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                {{ $attendanceToday ? 'Sudah Absen' : 'Belum Absen' }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
            <p class="text-gray-600">Kehadiran Bulan Ini</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">{{ $monthlyCount }}</h2>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
            <p class="text-gray-600">Tepat Waktu</p>
            <h2 class="text-xl font-bold text-green-600 mt-1">{{ $onTimeCount }}</h2>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-yellow-500">
            <p class="text-gray-600">Rata-rata Jam Kerja</p>
            <h2 class="text-xl font-bold text-orange-600 mt-1">
                {{ $averageHours ? number_format($averageHours, 1).' jam' : '-' }}
            </h2>
        </div>

    </div>

    {{-- TABS --}}
    <div class="flex justify-center mb-6 space-x-3">
        <button onclick="showTab('today')" id="tab-today"
            class="px-6 py-2 rounded-full font-semibold bg-indigo-600 text-white shadow">
            Hari Ini
        </button>
        <button onclick="showTab('absen')" id="tab-absen"
            class="px-6 py-2 rounded-full font-semibold bg-gray-200 text-gray-600 hover:bg-gray-300 transition">
            Absen
        </button>
    </div>

    {{-- TAB: TODAY --}}
    <div id="today" class="tab-content bg-white shadow-xl rounded-xl p-6">

        <h3 class="text-xl font-bold mb-5 text-gray-800">Status Kehadiran Hari Ini</h3>

        @if ($attendanceToday)
            <ul class="text-gray-700 leading-relaxed space-y-2">
                <li>Check In:
                    <b>{{ $attendanceToday->check_in ? $attendanceToday->check_in->format('H:i') : '-' }}</b>
                </li>

                <li>Check Out:
                    <b>{{ $attendanceToday->check_out ? $attendanceToday->check_out->format('H:i') : '-' }}</b>
                </li>

                <li>Status:
                    <b class="capitalize">{{ $attendanceToday->status ?? '-' }}</b>
                </li>

                @if ($attendanceToday->photo_path)
                <li class="mt-4">
                    <img src="{{ asset('storage/'.$attendanceToday->photo_path) }}"
                         class="w-48 rounded-xl border shadow">
                </li>
                @endif
            </ul>

        @else

            <div class="text-center p-8 text-gray-500">
                <div class="text-5xl mb-3"></div>
                <p class="font-semibold">Anda belum melakukan absensi hari ini</p>
                <p>Klik tab <b class="text-indigo-600">Absen</b> untuk mulai check in</p>
            </div>

        @endif

    </div>

    {{-- TAB: ABSEN --}}
    <div id="absen" class="tab-content hidden bg-white shadow-xl rounded-xl p-6">

        <h3 class="text-xl font-bold mb-4 text-gray-800">
            {{ $attendanceToday && !$attendanceToday->check_out ? 'Check Out' : 'Form Absensi' }}
        </h3>

        {{-- WAKTU --}}
        <div class="mb-6 text-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-5 rounded-xl shadow-lg">
            <p class="text-sm opacity-90">{{ \Carbon\Carbon::parse($serverTime)->translatedFormat('l, d F Y') }}</p>
            <h2 id="realtimeClock" class="text-4xl font-bold mt-1">00:00:00</h2>
        </div>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- CHECK IN --}}
        @if (!$attendanceToday)
        <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="font-semibold text-gray-700">Foto Kehadiran *</label>
                <input type="file" name="photo" required
                       class="mt-2 border rounded-lg w-full p-2 focus:ring focus:ring-indigo-200">
            </div>

            <div class="mb-4">
                <label class="font-semibold text-gray-700">Catatan (Opsional)</label>
                <textarea name="note" class="mt-2 border rounded-lg w-full p-2"></textarea>
            </div>

            <button class="bg-green-600 hover:bg-green-700 text-white py-3 w-full rounded-lg font-semibold shadow">
                Check In Sekarang
            </button>
        </form>

        {{-- CHECK OUT --}}
        @elseif ($attendanceToday && !$attendanceToday->check_out)
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf

            <p class="text-gray-700 text-center mb-4">
                Anda sudah check-in pukul <b>{{ $attendanceToday->check_in->format('H:i') }}</b>
            </p>

            <button class="bg-red-600 hover:bg-red-700 text-white py-3 w-full rounded-lg font-semibold shadow">
                Check Out Sekarang
            </button>
        </form>

        {{-- ABSEN SELESAI --}}
        @else
        <div class="text-center py-8 text-green-600 font-semibold">
             Anda telah menyelesaikan absensi hari ini.
        </div>
        @endif

        {{-- PETUNJUK --}}
        <div class="mt-8 bg-indigo-50 p-4 rounded-xl border border-indigo-200">
            <h4 class="font-semibold text-indigo-700 mb-2">Petunjuk Absensi</h4>
            <ul class="text-sm text-gray-700 list-disc ml-6 space-y-1">
                <li>Upload foto kehadiran yang jelas</li>
                <li>Jam kerja: 08:00 - 17:00</li>
                <li>Terlambat jika check in setelah 08:00</li>
                <li>Wajib check out saat pulang</li>
            </ul>
        </div>

    </div>
</div>


{{-- SCRIPT TAB + JAM --}}
<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(tab).classList.remove('hidden');

    document.querySelectorAll('[id^="tab-"]').forEach(el => {
        el.classList.remove('bg-indigo-600','text-white');
        el.classList.add('bg-gray-200','text-gray-600');
    });

    const active = document.getElementById('tab-' + tab);
    active.classList.remove('bg-gray-200','text-gray-600');
    active.classList.add('bg-indigo-600','text-white');
}

// JAM REALTIME
let currentTime = new Date("{{ $serverTime }}");

function updateClock(){
    currentTime.setSeconds(currentTime.getSeconds() + 1);
    document.getElementById('realtimeClock').textContent =
        currentTime.toLocaleTimeString('id-ID',{hour12:false});
}

setInterval(updateClock, 1000);
updateClock();
</script>

@endsection
