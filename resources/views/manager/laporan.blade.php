@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4">

    {{-- HEADER --}}
    <div class="text-center mb-12">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            🌴 Laporan Hasil Panen Sawit
        </h1>
        <p class="text-gray-500 mt-2">
            Monitoring dan analisis produktivitas pekerja sawit
        </p>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('manager.laporan') }}"
          class="bg-white shadow-md border border-gray-100 rounded-xl p-6 mb-10">

        <h3 class="text-lg font-bold text-gray-800 mb-5">Filter Laporan</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Role</label>
                <select name="role"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Semua Role</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="security" {{ request('role') == 'security' ? 'selected' : '' }}>Security</option>
                    <option value="cleaning" {{ request('role') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                    <option value="kantoran" {{ request('role') == 'kantoran' ? 'selected' : '' }}>Kantoran</option>
                </select>
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                Terapkan Filter
            </button>

            <a href="{{ route('manager.laporan') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold">
                Reset
            </a>
        </div>

    </form>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-blue-500">
            <p class="text-3xl font-bold text-blue-600">{{ $totalPegawai }}</p>
            <p class="text-gray-600 text-sm mt-1">Total Pekerja Sawit</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-green-500">
            <p class="text-3xl font-bold text-green-600">{{ number_format($totalPalmWeight, 1) }} kg</p>
            <p class="text-gray-600 text-sm mt-1">Total Berat Sawit</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-yellow-500">
            <p class="text-3xl font-bold text-yellow-600">{{ number_format($averagePalmWeight, 1) }} kg</p>
            <p class="text-gray-600 text-sm mt-1">Rata-rata / Pekerja</p>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 text-center border-l-4 border-orange-500">
            <p class="text-3xl font-bold text-orange-600">{{ $totalHadir }}</p>
            <p class="text-gray-600 text-sm mt-1">Total Kehadiran</p>
        </div>

    </div>

    {{-- MAIN: 2 CHART SECTIONS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- CHART 1 --}}
        <div class="bg-white shadow-md rounded-xl p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🌴 Hasil Panen Harian</h3>

            @if($dailyPalmWeight->count())
                <div class="space-y-3">

                    @php $maxWeight = $dailyPalmWeight->max('total_weight'); @endphp

                    @foreach($dailyPalmWeight as $daily)
                        @php
                            $percent = $maxWeight > 0
                                        ? ($daily->total_weight / $maxWeight) * 100
                                        : 0;
                        @endphp

                        <div>
                            <div class="flex justify-between mb-1 text-sm">
                                <span class="text-gray-600">
                                    {{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}
                                </span>

                                <span class="font-bold text-green-600">
                                    {{ number_format($daily->total_weight, 1) }} kg
                                </span>
                            </div>

                            <div class="w-full bg-gray-200 h-4 rounded-full overflow-hidden">
                                <div class="bg-green-500 h-full"
                                     style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                    @endforeach

                </div>
            @else
                <div class="h-56 flex items-center justify-center text-gray-500 border-2 border-dashed rounded-xl">
                    Tidak ada data hasil panen.
                </div>
            @endif
        </div>

        {{-- CHART 2 --}}
        <div class="bg-white shadow-md rounded-xl p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">👥 Kehadiran Harian</h3>

            @if($dailyAttendance->count())
                <div class="space-y-3">
                    @foreach($dailyAttendance as $daily)
                        @php
                            $percent = $totalPegawai > 0
                                        ? ($daily->total / $totalPegawai) * 100
                                        : 0;
                        @endphp

                        <div>
                            <div class="flex justify-between mb-1 text-sm">
                                <span class="text-gray-600">
                                    {{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}
                                </span>

                                <span class="font-bold text-blue-600">
                                    {{ $daily->total }} pekerja
                                </span>
                            </div>

                            <div class="w-full bg-gray-200 h-4 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full"
                                     style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                    @endforeach
                </div>
            @else
                <div class="h-56 flex items-center justify-center text-gray-500 border-2 border-dashed rounded-xl">
                    Tidak ada data kehadiran.
                </div>
            @endif
        </div>

    </div>

    {{-- TOP PERFORMERS + ROLE SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- TOP PERFORMERS --}}
        <div class="bg-white shadow-md rounded-xl p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🏆 Pekerja Terbaik</h3>

            @if($topPerformers->count())
                <div class="space-y-4">
                    @foreach($topPerformers as $i => $p)

                        @php
                            $rankColor = [
                                'bg-green-50', 'bg-blue-50', 'bg-yellow-50'
                            ][$i] ?? 'bg-gray-50';

                            $badge = [
                                '🥇 Top', '🥈 Excellent', '🥉 Good'
                            ][$i] ?? '⭐';
                        @endphp

                        <div class="flex items-center justify-between p-4 rounded-xl border {{ $rankColor }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold">
                                    {{ $i + 1 }}
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-800">{{ $p->user->name }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ number_format($p->total_weight, 1) }} kg
                                        <span class="text-gray-400 mx-1">•</span>
                                        {{ $p->total_hadir }} hari
                                    </p>
                                </div>
                            </div>

                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white border">
                                {{ $badge }}
                            </span>
                        </div>

                    @endforeach
                </div>

            @else
                <div class="h-40 flex items-center justify-center border-2 border-dashed rounded-xl text-gray-500">
                    Tidak ada data.
                </div>
            @endif

        </div>

        {{-- PER ROLE --}}
        <div class="bg-white shadow-md rounded-xl p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Hasil Panen per Role</h3>

            @if($palmWeightByRole->count())
                <div class="space-y-4">
                    @foreach($palmWeightByRole as $role => $data)

                        <div class="p-4 bg-gray-50 rounded-xl border flex justify-between items-center">
                            <div class="font-semibold capitalize text-gray-800">
                                {{ $role }}
                            </div>

                            <div class="text-right">
                                <p class="font-bold text-green-600 text-lg">
                                    {{ number_format($data['total_weight'], 1) }} kg
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $data['total_workers'] }} pekerja
                                    <span class="mx-1 text-gray-300">•</span>
                                    Rata-rata {{ number_format($data['avg_weight'], 1) }} kg
                                </p>
                            </div>
                        </div>

                    @endforeach
                </div>
            @else
                <div class="h-40 flex items-center justify-center border-2 border-dashed rounded-xl text-gray-500">
                    Tidak ada data.
                </div>
            @endif

        </div>

    </div>

    {{-- DETAIL TABLE --}}
    <div class="bg-white shadow-md rounded-xl p-6">

        <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Detail Laporan Panen</h3>

        @if($detailedAttendances->count())

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                    <tr class="text-gray-700">
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Check In</th>
                        <th class="px-4 py-3 text-left">Check Out</th>
                        <th class="px-4 py-3 text-left">Berat</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Foto</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">
                    @foreach($detailedAttendances as $a)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3 font-semibold">{{ $a->user->name }}</td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 capitalize">
                                    {{ $a->user->role }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                {{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i') : '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i') : '-' }}
                            </td>

                            <td class="px-4 py-3 font-bold text-green-600">
                                {{ $a->palm_weight ? number_format($a->palm_weight, 1).' kg' : '-' }}
                            </td>

                            <td class="px-4 py-3">
                                @if($a->status == 'tepat waktu')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        Tepat Waktu
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                        Terlambat
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if($a->checkout_photo_path)
                                    <a href="{{ asset('storage/'.$a->checkout_photo_path) }}"
                                       class="text-blue-600 hover:text-blue-800 font-semibold"
                                       target="_blank">📸 Lihat</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $detailedAttendances->links() }}
            </div>

        @else

            <div class="h-48 flex items-center justify-center border-2 border-dashed rounded-xl text-gray-500">
                Tidak ada data laporan.
            </div>

        @endif

    </div>

</div>
@endsection
