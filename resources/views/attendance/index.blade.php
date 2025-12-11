@extends('layouts.app')

@section('content')
<div class="min-h-screen px-4 py-6 bg-gray-50">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-10">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ Auth::user()->role == 'user' ? 'Sistem Absensi Pekerja Sawit' : 'Sistem Absensi Pegawai' }}
            </h1>
            <p class="text-gray-500 mt-1">Kelola kehadiran dan aktivitas kerja Anda</p>
        </div>

        <div class="bg-white shadow rounded-xl px-5 py-4 border">
            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            <p class="text-sm text-gray-500 capitalize">{{ Auth::user()->role }}</p>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-10">

        {{-- Status hari ini --}}
        <div class="bg-white shadow rounded-xl p-5 border">
            <p class="text-gray-500">Status Hari Ini</p>
            <h2 class="text-2xl font-bold text-indigo-600 mt-1">
                {{ $attendanceToday ? ($attendanceToday->check_out ? 'Selesai' : 'Sedang Bekerja') : 'Belum Masuk' }}
            </h2>
        </div>

        {{-- Kehadiran bulan ini --}}
        <div class="bg-white shadow rounded-xl p-5 border">
            <p class="text-gray-500">Total Kehadiran Bulan Ini</p>
            <h2 class="text-2xl font-bold text-green-600 mt-1">{{ $monthlyCount }}</h2>
        </div>

        {{-- Statistik pekerja sawit --}}
        @if(Auth::user()->role == 'user')

            {{-- Total panen bulan ini --}}
            <div class="bg-white shadow rounded-xl p-5 border">
                <p class="text-gray-500">Total Sawit Bulan Ini</p>
                <h2 class="text-2xl font-bold text-yellow-600 mt-1">
                    {{ number_format($monthlyPalmWeight, 1) }} kg
                </h2>
            </div>

            {{-- Rata-rata panen --}}
            <div class="bg-white shadow rounded-xl p-5 border">
                <p class="text-gray-500">Rata-rata per Hari</p>
                <h2 class="text-2xl font-bold text-orange-600 mt-1">
                    {{ $monthlyCount > 0 ? number_format($monthlyPalmWeight / $monthlyCount, 1) : 0 }} kg
                </h2>
            </div>

        @else

            {{-- Role non-sawit --}}
            <div class="bg-white shadow rounded-xl p-5 border">
                <p class="text-gray-500">Jam Kerja Hari Ini</p>
                <h2 class="text-2xl font-bold text-purple-600 mt-1">
                    @if($attendanceToday && $attendanceToday->check_out)
                        {{ $attendanceToday->check_in->diffInHours($attendanceToday->check_out) }} jam
                    @else
                        -
                    @endif
                </h2>
            </div>
        @endif

    </div>

    {{-- TAB MENU --}}
    <div class="flex justify-center gap-3 mb-8">
        <button id="tab-today"
            onclick="showTab('today')"
            class="px-6 py-3 rounded-lg font-semibold bg-indigo-600 text-white shadow">
            Hari Ini
        </button>

        <button id="tab-absen"
            onclick="showTab('absen')"
            class="px-6 py-3 rounded-lg font-semibold bg-white border text-gray-600 hover:bg-gray-100 shadow">
            Absensi
        </button>
    </div>

    {{-- TAB: HARI INI --}}
    <div id="today" class="tab-content bg-white shadow rounded-xl p-8 border">

        <h3 class="text-2xl font-bold text-gray-800 mb-6 pb-3 border-b">
            Status Kehadiran Hari Ini
        </h3>

        @if($attendanceToday)

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- CHECK IN --}}
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <h4 class="text-lg font-bold text-green-700 mb-3">Check In</h4>
                    <p class="text-gray-700 mb-2">
                        <b>Waktu:</b> {{ $attendanceToday->check_in->format('H:i:s') }}
                    </p>
                    <p class="text-gray-700">
                        <b>Status:</b>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $attendanceToday->status == 'tepat waktu' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($attendanceToday->status) }}
                        </span>
                    </p>
                </div>

                {{-- CHECK OUT --}}
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <h4 class="text-lg font-bold text-blue-700 mb-3">Check Out</h4>

                    @if($attendanceToday->check_out)
                        <p class="text-gray-700 mb-2">
                            <b>Waktu:</b> {{ $attendanceToday->check_out->format('H:i:s') }}
                        </p>

                        {{-- PEKERJA SAWIT --}}
                        @if(Auth::user()->role == 'user')
                            <p class="text-gray-700 mt-2">
                                <b>Berat Sawit:</b>
                                <span class="text-2xl font-bold text-green-600">
                                    {{ number_format($todayPalmWeight,1) }} kg
                                </span>
                            </p>

                            @if($attendanceToday->checkout_photo_path)
                                <img src="{{ asset('storage/' . $attendanceToday->checkout_photo_path) }}"
                                     class="w-40 rounded-lg border mt-4 shadow">
                            @endif

                        @endif

                    @else
                        <p class="text-gray-600">Belum melakukan check out</p>
                    @endif
                </div>
            </div>

        @else

            <div class="text-center py-16">
                <div class="text-6xl mb-4">📌</div>
                <p class="text-xl font-semibold text-gray-700">
                    Belum ada absensi hari ini
                </p>
            </div>

        @endif

    </div>

    {{-- TAB: ABSEN --}}
    <div id="absen" class="tab-content hidden bg-white shadow rounded-xl p-8 border">

        <h3 class="text-2xl font-bold text-gray-800 mb-6 pb-3 border-b">
            {{ !$attendanceToday ? 'Check In' : (!$attendanceToday->check_out ? 'Check Out' : 'Selesai') }}
        </h3>

        {{-- JAM --}}
        <div class="text-center bg-indigo-600 text-white py-6 rounded-xl mb-8 shadow">
            <p class="text-sm opacity-80">
                {{ \Carbon\Carbon::parse($serverTime)->translatedFormat('l, d F Y') }}
            </p>
            <h2 id="realtimeClock" class="text-4xl font-bold mt-1">00:00:00</h2>
        </div>

        {{-- FORM --}}
        @if(!$attendanceToday)

            {{-- CHECK IN --}}
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl text-lg font-semibold shadow">
                    Masuk Hari Ini
                </button>
            </form>

        @elseif(!$attendanceToday->check_out)

            {{-- CHECK OUT --}}
            <form action="{{ route('attendance.checkout') }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- PEKERJA SAWIT --}}
                @if(Auth::user()->role == 'user')

                    <div>
                        <label class="font-semibold text-gray-700">Foto Hasil Panen *</label>
                        <input type="file" name="checkout_photo" required class="w-full border rounded-lg p-3 mt-2">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Berat Sawit (kg) *</label>
                        <input type="number" step="0.1" min="0" name="palm_weight"
                               class="w-full border rounded-lg p-3 mt-2" required>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Catatan (Opsional)</label>
                        <textarea name="note" rows="3" class="w-full border rounded-lg p-3 mt-2"></textarea>
                    </div>

                @else

                    {{-- NON PEKERJA SAWIT --}}
                    <div>
                        <label class="font-semibold text-gray-700">Foto Bukti Pekerjaan *</label>
                        <input type="file" name="checkout_photo" required class="w-full border rounded-lg p-3 mt-2">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Deskripsi *</label>
                        <textarea name="note" rows="4" required class="w-full border rounded-lg p-3 mt-2"></textarea>
                    </div>

                @endif

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl text-lg font-semibold shadow">
                    Kirim & Check Out
                </button>

            </form>

        @else

            <div class="text-center py-16">
                <div class="text-6xl mb-4">🎉</div>
                <p class="text-xl font-bold text-indigo-700">Absensi Hari Ini Selesai</p>
            </div>

        @endif

    </div>

</div>

{{-- SCRIPT JAM --}}
<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    document.getElementById(tab).classList.remove('hidden');

    document.getElementById('tab-today').classList.remove('bg-indigo-600','text-white');
    document.getElementById('tab-absen').classList.remove('bg-indigo-600','text-white');

    document.getElementById('tab-' + tab).classList.add('bg-indigo-600','text-white');
}

let currentTime = new Date("{{ $serverTime }}");

function updateClock() {
    currentTime.setSeconds(currentTime.getSeconds() + 1);
    const h = String(currentTime.getHours()).padStart(2,'0');
    const m = String(currentTime.getMinutes()).padStart(2,'0');
    const s = String(currentTime.getSeconds()).padStart(2,'0');
    document.getElementById('realtimeClock').textContent = `${h}:${m}:${s}`;
}

setInterval(updateClock, 1000);
updateClock();

showTab('today');
</script>
@endsection
