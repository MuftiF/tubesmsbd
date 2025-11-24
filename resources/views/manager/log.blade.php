@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">

    {{-- HEADER --}}
    <div class="text-center mb-12">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Log Absensi Tim
        </h1>
        <p class="text-gray-500 mt-2">
            Monitoring kehadiran dan aktivitas harian pegawai
        </p>
    </div>

    {{-- FILTER --}}
    <div class="bg-white shadow-md rounded-xl p-6 mb-8 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Filter Log Absensi</h3>

        <form action="{{ route('manager.log') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                {{-- Date --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="date"
                           value="{{ request('date', date('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:outline-none">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Role</label>
                    <select name="role"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role')=='user' ? 'selected':'' }}>Pekerja</option>
                        <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                        <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                        <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="tepat waktu" {{ request('status')=='tepat waktu'?'selected':'' }}>Tepat Waktu</option>
                        <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                    </select>
                </div>

                {{-- Search --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Pencarian</label>
                    <input type="text"
                           name="search"
                           placeholder="Cari nama..."
                           value="{{ request('search') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:outline-none">
                </div>

            </div>

            <div class="flex gap-3 mt-6">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                    Terapkan Filter
                </button>
                <a href="{{ route('manager.log') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-blue-600">{{ $totalPegawai }}</p>
            <p class="text-gray-600 text-sm mt-1">Total Pegawai</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-600">{{ $totalHadir }}</p>
            <p class="text-gray-600 text-sm mt-1">Hadir Hari Ini</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-yellow-500">
            <p class="text-2xl font-bold text-yellow-600">{{ $totalTerlambat }}</p>
            <p class="text-gray-600 text-sm mt-1">Terlambat</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-red-500">
            <p class="text-2xl font-bold text-red-600">{{ $totalAlpha }}</p>
            <p class="text-gray-600 text-sm mt-1">Alpha</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100">

        {{-- Table header --}}
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">
                Log Absensi – {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') }}
            </h3>

            <p class="text-sm text-gray-500">
                Update terakhir: {{ now('Asia/Jakarta')->format('H:i') }} WIB
            </p>
        </div>

        @if($attendances->count())

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Masuk</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Pulang</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Catatan</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Foto</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- Name --}}
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">{{ $attendance->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $attendance->user->email }}</p>
                            </td>

                            {{-- Role --}}
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs
                                    @if($attendance->user->role=='user') bg-green-100 text-green-800
                                    @elseif($attendance->user->role=='security') bg-blue-100 text-blue-800
                                    @elseif($attendance->user->role=='cleaning') bg-yellow-100 text-yellow-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ ucfirst($attendance->user->role) }}
                                </span>
                            </td>

                            {{-- Check in --}}
                            <td class="px-4 py-3">
                                @if($attendance->check_in)
                                    <span class="font-semibold text-sm
                                        {{ $attendance->status=='terlambat' ? 'text-yellow-600' : 'text-gray-900' }}">
                                        {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>

                            {{-- Check out --}}
                            <td class="px-4 py-3">
                                @if($attendance->check_out)
                                    <span class="font-semibold text-gray-900 text-sm">
                                        {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @if($attendance->status)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $attendance->status=='tepat waktu' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>

                            {{-- Note --}}
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $attendance->note ?? '-' }}
                            </td>

                            {{-- Photo --}}
                            <td class="px-4 py-3">
                                @if($attendance->photo_path)
                                    <a href="{{ asset('storage/'.$attendance->photo_path) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm underline">
                                        Lihat Foto
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="mt-6">
                {{ $attendances->links() }}
            </div>

        @else

            <div class="text-center py-16">
                <div class="text-5xl mb-3 text-gray-400">📊</div>
                <h3 class="text-lg font-semibold text-gray-600 mb-1">Tidak ada data absensi</h3>
                <p class="text-gray-500 text-sm">
                    Tidak ditemukan data untuk tanggal atau filter yang dipilih.
                </p>
            </div>

        @endif
    </div>

</div>
@endsection
