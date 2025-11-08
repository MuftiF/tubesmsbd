@extends('layouts.app')

@section('content')
<div class="p-6 bg-blue-50 min-h-screen">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-indigo-700">Sistem Absensi</h1>
            <p class="text-gray-600">Kelola kehadiran pribadi Anda</p>
        </div>
        <div class="text-right">
            <h2 class="font-semibold">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500">{{ Auth::user()->role ?? 'Karyawan' }}</p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-xl p-4 text-center">
            <p>Status Hari Ini</p>
            <h2 class="text-lg font-semibold">
                {{ $attendanceToday ? 'Sudah Absen' : 'Belum Absen' }}
            </h2>
        </div>
        <div class="bg-white shadow rounded-xl p-4 text-center">
            <p>Total Kehadiran Bulan Ini</p>
            <h2 class="text-lg font-semibold">{{ $monthlyCount }}</h2>
        </div>
        <div class="bg-white shadow rounded-xl p-4 text-center">
            <p>Tepat Waktu</p>
            <h2 class="text-lg font-semibold text-green-600">{{ $onTimeCount }}</h2>
        </div>
        <div class="bg-white shadow rounded-xl p-4 text-center">
            <p>Rata-rata Jam Kerja</p>
            <h2 class="text-lg font-semibold text-orange-500">
                {{ $averageHours ? number_format($averageHours, 1) . ' jam' : '-' }}
            </h2>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex justify-center mb-6 space-x-2">
        <button onclick="showTab('today')" id="tab-today"
            class="px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 font-medium">Hari Ini</button>
        <button onclick="showTab('absen')" id="tab-absen"
            class="px-4 py-2 rounded-full bg-gray-200 text-gray-600 font-medium">Absen</button>
        <button onclick="showTab('history')" id="tab-history"
            class="px-4 py-2 rounded-full bg-gray-200 text-gray-600 font-medium">Riwayat</button>
    </div>

    {{-- Tab: Hari Ini --}}
    <div id="today" class="tab-content bg-white shadow rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Status Kehadiran Hari Ini</h3>

        @if ($attendanceToday)
            <p>Anda telah melakukan absensi hari ini.</p>
            <ul class="mt-3 text-gray-700">
                <li>Check In: <b>{{ $attendanceToday->check_in ?? '-' }}</b></li>
                <li>Check Out: <b>{{ $attendanceToday->check_out ?? '-' }}</b></li>
                <li>Status: <b>{{ ucfirst($attendanceToday->status ?? '-') }}</b></li>
                @if ($attendanceToday->photo_path)
                    <li class="mt-3">
                        <img src="{{ asset('storage/' . $attendanceToday->photo_path) }}" class="w-40 rounded-xl border">
                    </li>
                @endif
            </ul>
        @else
            <div class="text-center text-gray-500">
                <p>Anda belum melakukan absensi hari ini</p>
                <p>Klik tab <b>Absen</b> untuk check in</p>
            </div>
        @endif
    </div>

    {{-- Tab: Absen --}}
    <div id="absen" class="tab-content hidden bg-white shadow rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">
            {{ $attendanceToday && !$attendanceToday->check_out ? 'Check Out' : 'Form Absensi' }}
        </h3>

        <div class="mb-4 text-center bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-4 rounded-xl">
            <p class="text-sm">{{ \Carbon\Carbon::parse($serverTime)->translatedFormat('l, d F Y') }}</p>
            <h2 id="realtimeClock" class="text-3xl font-bold">00:00:00</h2>
        </div>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if (!$attendanceToday)
            {{-- === Check In Form === --}}
            <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-600">Upload Foto Bukti Kehadiran *</label>
                    <input type="file" name="photo" required class="mt-2 border rounded-lg w-full p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600">Catatan (Opsional)</label>
                    <textarea name="note" class="mt-2 border rounded-lg w-full p-2"></textarea>
                </div>
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg w-full">
                    Check In Sekarang
                </button>
            </form>
        @elseif ($attendanceToday && !$attendanceToday->check_out)
            {{-- === Check Out Button === --}}
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <div class="text-center mb-4">
                    <p class="text-gray-700 mb-2">Anda sudah melakukan Check In pada pukul
                        <b>{{ $attendanceToday->check_in->format('H:i') }}</b>.
                    </p>
                    <p>Silakan lakukan <b>Check Out</b> saat Anda selesai bekerja.</p>
                </div>
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg w-full">
                    Check Out Sekarang
                </button>
            </form>
        @else
            <div class="text-center text-green-600 font-semibold">
                Anda telah menyelesaikan absensi hari ini. 🎉
            </div>
        @endif

        <div class="mt-6 text-sm text-blue-600">
            <p><b>Petunjuk Absensi:</b></p>
            <ul class="list-disc ml-6">
                <li>Upload foto sebagai bukti kehadiran di lokasi kerja</li>
                <li>Pastikan foto jelas dan menunjukkan lokasi Anda</li>
                <li>Jam kerja: 08:00 - 17:00</li>
                <li>Terlambat jika check in setelah jam 08:00</li>
                <li>Jangan lupa check out saat pulang</li>
            </ul>
        </div>
    </div>

    {{-- Tab: Riwayat --}}
    <div id="history" class="tab-content hidden bg-white shadow rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4">Riwayat Kehadiran</h3>
        <input type="date" id="searchDate" class="border rounded-lg p-2 mb-4">
        <div id="historyTable" class="overflow-x-auto"></div>
    </div>
</div>

<script>
    // === Handle Tabs ===
    function showTab(tab) {
        document.querySelectorAll('.tab-content').forEach(div => div.classList.add('hidden'));
        document.querySelectorAll('[id^="tab-"]').forEach(btn => {
            btn.classList.remove('bg-indigo-100', 'text-indigo-700');
            btn.classList.add('bg-gray-200', 'text-gray-600');
        });
        document.getElementById(tab).classList.remove('hidden');
        document.getElementById('tab-' + tab).classList.add('bg-indigo-100', 'text-indigo-700');
        document.getElementById('tab-' + tab).classList.remove('bg-gray-200', 'text-gray-600');
        if (tab === 'history') loadHistory();
    }

    // === Realtime Clock ===
    const initialServerTime = "{{ $serverTime }}";
    let currentTime = new Date(initialServerTime);

    function formatTime(date) {
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        return `${hours}:${minutes}:${seconds}`;
    }

    function updateClock() {
        currentTime.setSeconds(currentTime.getSeconds() + 1);
        document.getElementById('realtimeClock').textContent = formatTime(currentTime);
    }

    setInterval(updateClock, 1000);
    updateClock();

    // === Load History ===
    function loadHistory(date = '') {
        fetch(`{{ route('attendance.history') }}?date=${date}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('historyTable');
                if (data.length === 0) {
                    container.innerHTML = `<p class="text-gray-500 text-center py-4">Tidak ada data ditemukan</p>`;
                    return;
                }
                let html = `
                <table class="min-w-full text-sm">
                    <thead><tr class="border-b bg-gray-100">
                        <th class="px-3 py-2 text-left">Tanggal</th>
                        <th class="px-3 py-2 text-left">Check In</th>
                        <th class="px-3 py-2 text-left">Check Out</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Foto</th>
                    </tr></thead><tbody>`;
                data.forEach(a => {
                    html += `<tr class="border-b">
                        <td class="px-3 py-2">${a.date}</td>
                        <td class="px-3 py-2">${a.check_in ?? '-'}</td>
                        <td class="px-3 py-2">${a.check_out ?? '-'}</td>
                        <td class="px-3 py-2 capitalize">${a.status ?? '-'}</td>
                        <td class="px-3 py-2">
                            ${a.photo_path ? `<img src="/storage/${a.photo_path}" class="w-16 rounded-lg">` : '-'}
                        </td>
                    </tr>`;
                });
                html += '</tbody></table>';
                container.innerHTML = html;
            });
    }

    document.getElementById('searchDate').addEventListener('change', e => loadHistory(e.target.value));

    // Tampilkan tab "Hari Ini" default saat halaman load
    showTab('today');
</script>
@endsection
