@extends('layouts.app')

@section('content')
<div class="p-6 bg-gradient-to-br from-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-50 to-blue-50 min-h-screen">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            @if(Auth::user()->role == 'user')
                <h1 class="text-3xl font-bold text-green-700">🌴 Sistem Absensi Pekerja Sawit</h1>
                <p class="text-gray-600">Kelola kehadiran dan laporan hasil panen Anda</p>
            @else
                <h1 class="text-3xl font-bold text-blue-700">📋 Sistem Absensi</h1>
                <p class="text-gray-600">Kelola kehadiran pribadi Anda</p>
            @endif
        </div>
        <div class="text-right bg-white rounded-lg shadow-md p-4">
            <h2 class="font-semibold text-lg">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
        </div>
    </div>

    {{-- Statistik --}}
    @if(Auth::user()->role == 'user')
        {{-- Statistik untuk Pekerja Sawit --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border-l-4 border-blue-500">
                <p class="text-gray-600 mb-2">Status Hari Ini</p>
                <h2 class="text-2xl font-bold text-blue-600">
                    {{ $attendanceToday ? ($attendanceToday->check_out ? 'Selesai' : 'Sedang Bekerja') : 'Belum Masuk' }}
                </h2>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border-l-4 border-green-500">
                <p class="text-gray-600 mb-2">Total Kehadiran Bulan Ini</p>
                <h2 class="text-2xl font-bold text-green-600">{{ $monthlyCount }} hari</h2>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border-l-4 border-yellow-500">
                <p class="text-gray-600 mb-2">Total Sawit Bulan Ini</p>
                <h2 class="text-2xl font-bold text-yellow-600">{{ number_format($monthlyPalmWeight, 1) }} kg</h2>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border-l-4 border-orange-500">
                <p class="text-gray-600 mb-2">Rata-rata per Hari</p>
                <h2 class="text-2xl font-bold text-orange-600">
                    {{ $monthlyCount > 0 ? number_format($monthlyPalmWeight / $monthlyCount, 1) : '0' }} kg
                </h2>
            </div>
        </div>
    @else
        {{-- Statistik untuk Role Lain (Security, Cleaning, Kantoran) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border-l-4 border-blue-500">
                <p class="text-gray-600 mb-2">Status Hari Ini</p>
                <h2 class="text-2xl font-bold text-blue-600">
                    {{ $attendanceToday ? ($attendanceToday->check_out ? 'Selesai' : 'Sedang Bekerja') : 'Belum Masuk' }}
                </h2>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border-l-4 border-green-500">
                <p class="text-gray-600 mb-2">Total Kehadiran Bulan Ini</p>
                <h2 class="text-2xl font-bold text-green-600">{{ $monthlyCount }} hari</h2>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border-l-4 border-purple-500">
                <p class="text-gray-600 mb-2">Jam Kerja Hari Ini</p>
                <h2 class="text-2xl font-bold text-purple-600">
                    @if($attendanceToday && $attendanceToday->check_in && $attendanceToday->check_out)
                        {{ $attendanceToday->check_in->diffInHours($attendanceToday->check_out) }} jam
                    @else
                        -
                    @endif
                </h2>
            </div>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="flex justify-center mb-6 space-x-2">
        <button onclick="showTab('today')" id="tab-today"
            class="px-6 py-3 rounded-full bg-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-600 text-white font-semibold shadow-lg">
            Hari Ini
        </button>
        <button onclick="showTab('absen')" id="tab-absen"
            class="px-6 py-3 rounded-full bg-gray-200 text-gray-600 font-semibold hover:bg-gray-300">
            Absensi
        </button>
    </div>

    {{-- Tab: Hari Ini --}}
    <div id="today" class="tab-content bg-white shadow-xl rounded-xl p-8">
        <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">📋 Status Kehadiran Hari Ini</h3>

        @if ($attendanceToday)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Check In Info --}}
                <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-500">
                    <h4 class="font-bold text-green-700 mb-3 text-lg">✅ Check In</h4>
                    <ul class="space-y-2 text-gray-700">
                        <li><span class="font-semibold">Waktu:</span> {{ $attendanceToday->check_in ? $attendanceToday->check_in->format('H:i:s') : '-' }}</li>
                        <li><span class="font-semibold">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $attendanceToday->status == 'tepat waktu' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($attendanceToday->status ?? '-') }}
                            </span>
                        </li>
                    </ul>
                </div>

                {{-- Check Out Info --}}
                <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500">
                    <h4 class="font-bold text-blue-700 mb-3 text-lg">🏁 Check Out</h4>
                    @if($attendanceToday->check_out)
                        <ul class="space-y-2 text-gray-700">
                            <li><span class="font-semibold">Waktu:</span> {{ $attendanceToday->check_out->format('H:i:s') }}</li>

                            @if(Auth::user()->role == 'user' && $attendanceToday->palm_weight)
                                {{-- Tampilan untuk Pekerja Sawit --}}
                                <li><span class="font-semibold">Berat Sawit:</span>
                                    <span class="text-xl font-bold text-green-600">{{ number_format($attendanceToday->palm_weight, 1) }} kg</span>
                                </li>
                                @if ($attendanceToday->checkout_photo_path)
                                    <li class="mt-3">
                                        <span class="font-semibold">Foto Hasil Panen:</span>
                                        <img src="{{ asset('storage/' . $attendanceToday->checkout_photo_path) }}"
                                             class="w-full max-w-xs rounded-lg border-2 border-blue-300 mt-2 shadow-md">
                                    </li>
                                @endif
                                @if($attendanceToday->note)
                                    <li><span class="font-semibold">Catatan:</span> {{ $attendanceToday->note }}</li>
                                @endif
                            @elseif(in_array(Auth::user()->role, ['security', 'cleaning', 'kantoran']) && $attendanceToday->checkout_photo_path)
                                {{-- Tampilan untuk Security, Cleaning, Kantoran --}}
                                @if ($attendanceToday->checkout_photo_path)
                                    <li class="mt-3">
                                        <span class="font-semibold">Foto Bukti Pekerjaan:</span>
                                        <img src="{{ asset('storage/' . $attendanceToday->checkout_photo_path) }}"
                                             class="w-full max-w-xs rounded-lg border-2 border-blue-300 mt-2 shadow-md">
                                    </li>
                                @endif
                                @if($attendanceToday->note)
                                    <li class="mt-3">
                                        <span class="font-semibold">Deskripsi Pekerjaan:</span>
                                        <p class="mt-1 text-gray-600 bg-white p-3 rounded border">{{ $attendanceToday->note }}</p>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    @else
                        <p class="text-gray-600">Belum melakukan check out</p>
                        @if(Auth::user()->role == 'user')
                            <p class="text-sm text-blue-600 mt-2">Silakan check out saat selesai bekerja dan laporkan hasil panen Anda</p>
                        @elseif(in_array(Auth::user()->role, ['security', 'cleaning', 'kantoran']))
                            <p class="text-sm text-blue-600 mt-2">Silakan check out saat selesai bekerja dan laporkan pekerjaan Anda</p>
                        @else
                            <p class="text-sm text-blue-600 mt-2">Silakan check out saat selesai bekerja</p>
                        @endif
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <div class="text-6xl mb-4">{{ Auth::user()->role == 'user' ? '🌴' : '📋' }}</div>
                <p class="text-xl font-semibold mb-2">Anda belum melakukan absensi hari ini</p>
                <p>Klik tab <b class="text-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-600">Absensi</b> untuk check in</p>
            </div>
        @endif
    </div>

    {{-- Tab: Absen --}}
    <div id="absen" class="tab-content hidden bg-white shadow-xl rounded-xl p-8">
        <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">
            @if($attendanceToday && !$attendanceToday->check_out)
                @if(Auth::user()->role == 'user')
                    🏁 Check Out & Lapor Hasil Panen
                @else
                    🏁 Check Out
                @endif
            @else
                ✅ Check In
            @endif
        </h3>

        <div class="mb-6 text-center bg-gradient-to-r from-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-500 to-blue-500 text-white p-6 rounded-xl shadow-lg">
            <p class="text-sm mb-2">{{ \Carbon\Carbon::parse($serverTime)->translatedFormat('l, d F Y') }}</p>
            <h2 id="realtimeClock" class="text-4xl font-bold">00:00:00</h2>
        </div>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if (!$attendanceToday)
            {{-- === Check In Form === --}}
            <div class="max-w-2xl mx-auto">
                <div class="bg-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-50 border-l-4 border-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-500 p-4 mb-6 rounded">
                    <p class="text-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-800 font-semibold">📌 Petunjuk Check In:</p>
                    <ul class="list-disc ml-6 mt-2 text-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-700 text-sm">
                        <li>Cukup tekan tombol "Masuk Hari Ini" untuk check in</li>
                        <li>Pastikan Anda berada di lokasi kerja</li>
                        <li>Jam kerja: 07:00 - 17:00</li>
                        <li>Terlambat jika check in setelah jam 07:00</li>
                    </ul>
                </div>

                <form action="{{ route('attendance.store') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-gradient-to-r from-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-600 to-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-700 hover:from-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-700 hover:to-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-800 text-white font-bold py-4 px-8 rounded-xl w-full text-xl shadow-lg transform hover:scale-105 transition duration-200">
                        ✅ Masuk Hari Ini
                    </button>
                </form>
            </div>
        @elseif ($attendanceToday && !$attendanceToday->check_out)
            {{-- === Check Out Form === --}}
            <div class="max-w-2xl mx-auto">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                    <p class="text-blue-800 font-semibold mb-2">✅ Anda sudah Check In pada pukul <b>{{ $attendanceToday->check_in->format('H:i') }}</b></p>
                    @if(Auth::user()->role == 'user')
                        <p class="text-blue-700 text-sm">Silakan lakukan Check Out dan laporkan hasil panen sawit Anda hari ini</p>
                    @else
                        <p class="text-blue-700 text-sm">Silakan lakukan Check Out saat selesai bekerja</p>
                    @endif
                </div>

                <form action="{{ route('attendance.checkout') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    @if(Auth::user()->role == 'user')
                        {{-- Form untuk Pekerja Sawit: Foto + Berat Sawit --}}
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <label class="block text-gray-700 font-semibold mb-3">
                                📸 Upload Foto Hasil Panen Sawit *
                            </label>
                            <input type="file" name="checkout_photo" accept="image/*" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                            <p class="text-sm text-gray-500 mt-2">Upload foto sawit yang telah dipanen hari ini</p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <label class="block text-gray-700 font-semibold mb-3">
                                ⚖️ Berat Sawit (kg) *
                            </label>
                            <input type="number" name="palm_weight" step="0.1" min="0" required
                                placeholder="Contoh: 150.5"
                                class="w-full border-2 border-gray-300 rounded-lg p-3 text-lg focus:border-blue-500 focus:outline-none">
                            <p class="text-sm text-gray-500 mt-2">Masukkan total berat sawit dalam kilogram (kg)</p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <label class="block text-gray-700 font-semibold mb-3">
                                📝 Catatan (Opsional)
                            </label>
                            <textarea name="note" rows="3"
                                placeholder="Tambahkan catatan jika ada..."
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"></textarea>
                        </div>

                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-xl w-full text-xl shadow-lg transform hover:scale-105 transition duration-200">
                            🏁 Check Out & Kirim Laporan
                        </button>

                    @elseif(in_array(Auth::user()->role, ['security', 'cleaning', 'kantoran']))
                        {{-- Form untuk Security, Cleaning, Kantoran: Foto + Deskripsi Pekerjaan --}}
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <label class="block text-gray-700 font-semibold mb-3">
                                📸 Upload Foto Bukti Pekerjaan *
                            </label>
                            <input type="file" name="checkout_photo" accept="image/*" required
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none">
                            <p class="text-sm text-gray-500 mt-2">Upload foto sebagai bukti pekerjaan hari ini</p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <label class="block text-gray-700 font-semibold mb-3">
                                📝 Deskripsi Pekerjaan Hari Ini *
                            </label>
                            <textarea name="note" rows="4" required
                                placeholder="Jelaskan pekerjaan yang telah Anda lakukan hari ini..."
                                class="w-full border-2 border-gray-300 rounded-lg p-3 focus:border-blue-500 focus:outline-none"></textarea>
                            <p class="text-sm text-gray-500 mt-2">Deskripsikan tugas dan aktivitas yang telah diselesaikan</p>
                        </div>

                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-xl w-full text-xl shadow-lg transform hover:scale-105 transition duration-200">
                            🏁 Check Out & Kirim Laporan
                        </button>

                    @else
                        {{-- Form untuk Admin & Manager: Hanya tombol checkout --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                            <p class="text-blue-800 text-sm">
                                <span class="font-semibold">ℹ️ Info:</span> Sebagai {{ ucfirst(Auth::user()->role) }}, Anda cukup menekan tombol di bawah untuk check out.
                            </p>
                        </div>

                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-xl w-full text-xl shadow-lg transform hover:scale-105 transition duration-200">
                            🏁 Check Out Sekarang
                        </button>
                    @endif
                </form>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🎉</div>
                <p class="text-2xl font-bold text-{{ Auth::user()->role == 'user' ? 'green' : 'blue' }}-600 mb-2">Absensi Hari Ini Selesai!</p>
                <p class="text-gray-600">Terima kasih atas kerja keras Anda hari ini</p>
                @if(Auth::user()->role == 'user' && $attendanceToday->palm_weight)
                    <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4 max-w-md mx-auto">
                        <p class="text-green-800 font-semibold">Hasil Panen Hari Ini:</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($attendanceToday->palm_weight, 1) }} kg</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
    // === Handle Tabs ===
    function showTab(tab) {
        document.querySelectorAll('.tab-content').forEach(div => div.classList.add('hidden'));
        document.querySelectorAll('[id^="tab-"]').forEach(btn => {
            btn.classList.remove('bg-green-600', 'bg-blue-600', 'text-white', 'shadow-lg');
            btn.classList.add('bg-gray-200', 'text-gray-600');
        });
        document.getElementById(tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tab);
        const userRole = '{{ Auth::user()->role }}';
        const activeColor = userRole === 'user' ? 'bg-green-600' : 'bg-blue-600';
        activeBtn.classList.add(activeColor, 'text-white', 'shadow-lg');
        activeBtn.classList.remove('bg-gray-200', 'text-gray-600');
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

    // Tampilkan tab "Hari Ini" default saat halaman load
    showTab('today');
</script>
@endsection
