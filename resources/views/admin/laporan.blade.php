@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Laporan Hasil Panen Sawit</h1>
        <p class="text-gray-500 text-sm mt-2">Dashboard analisis produktivitas dan kehadiran pekerja sawit</p>
    </div>

    <!-- FILTER BOX -->
    <form method="GET" action="{{ route('admin.laporan') }}" 
        class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 mb-10">

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-600">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}"
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}"
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Role</label>
                <select name="role"
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Role</option>
                    <option value="user" {{ request('role')=='user' ? 'selected':'' }}>User</option>
                    <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                    <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                    <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Terapkan Filter
            </button>

            <a href="{{ route('admin.laporan') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Reset
            </a>
        </div>
    </form>

    @php
        $selectedRole = request('role');
        $hasPalmAccess = !$selectedRole || $selectedRole == 'user';
    @endphp

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Total Pekerja</p>
            <p class="text-3xl font-bold text-blue-700 mt-1">{{ $totalPegawai }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Total Berat Sawit</p>
            <p class="text-3xl font-bold text-green-700 mt-1">
                {{ $hasPalmAccess ? number_format($totalPalmWeight,1).' kg' : '-' }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Rata-rata Panen</p>
            <p class="text-3xl font-bold text-yellow-600 mt-1">
                {{ $hasPalmAccess ? number_format($averagePalmWeight,1).' kg' : '-' }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Total Kehadiran</p>
            <p class="text-3xl font-bold text-orange-600 mt-1">{{ $totalHadir }}</p>
        </div>

    </div>

    <!-- CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

        @if($hasPalmAccess)
        <!-- PANEN HARIAN -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Hasil Panen 7 Hari Terakhir</h3>

            @if($dailyPalmWeight->count())
            <div class="space-y-3">
                @php $maxWeight = $dailyPalmWeight->max('total_weight'); @endphp

                @foreach($dailyPalmWeight as $daily)
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</span>
                        <span>{{ number_format($daily->total_weight,1) }} kg</span>
                    </div>
                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-green-600 h-full rounded-full"
                            style="width: {{ $maxWeight > 0 ? ($daily->total_weight/$maxWeight*100):0 }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="h-40 flex items-center justify-center text-gray-400">Tidak ada data panen</div>
            @endif
        </div>
        @endif

        <!-- KEHADIRAN HARIAN -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Kehadiran 7 Hari Terakhir</h3>

            @if($dailyAttendance->count())
            <div class="space-y-3">

                @foreach($dailyAttendance as $daily)
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</span>
                        <span>{{ $daily->total }} pekerja</span>
                    </div>
                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full"
                            style="width: {{ $totalPegawai>0 ? ($daily->total/$totalPegawai*100):0 }}%">
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            @else
            <div class="h-40 flex items-center justify-center text-gray-400">Tidak ada data kehadiran</div>
            @endif
        </div>

    </div>

    <!-- TOP PERFORMERS -->
    <div class="grid grid-cols-1 gap-8 mb-10">

        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pekerja Terbaik</h3>

            @if($topPerformers->count())
            <div class="space-y-4">

                @foreach($topPerformers as $index => $p)

                <div class="flex justify-between items-center p-4 rounded-lg border border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold
                            {{ $index==0?'bg-green-100 text-green-700':
                               ($index==1?'bg-blue-100 text-blue-700':
                               'bg-yellow-100 text-yellow-700') }}">
                            {{ $index+1 }}
                        </div>

                        <div>
                            <p class="font-semibold text-gray-800">{{ $p->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ number_format($p->total_weight,1) }} kg • {{ $p->total_hadir }} hari</p>
                        </div>

                    </div>

                    <span class="text-xs font-bold px-3 py-1 rounded-full
                        {{ $index==0?'bg-green-200 text-green-800':
                           ($index==1?'bg-blue-200 text-blue-800':
                           'bg-yellow-200 text-yellow-800') }}">
                        {{ $index==0?'Top':($index==1?'Excellent':'Good') }}
                    </span>
                </div>

                @endforeach

            </div>

            @else
            <div class="h-40 flex items-center justify-center text-gray-400">Tidak ada data</div>
            @endif
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 mb-10">

        <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Kehadiran & Panen</h3>

        @if($detailedAttendances->count())
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm border-b">
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Check In</th>
                        <th class="px-4 py-3">Check Out</th>
                        <th class="px-4 py-3">Berat</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Foto</th>
                    </tr>
                </thead>

                <tbody class="text-sm">

                    @foreach($detailedAttendances as $a)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $a->user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-600 text-xs capitalize">
                                {{ $a->user->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $a->check_in ? \Carbon\Carbon::parse($a->check_in)->format('H:i'):'-' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $a->check_out ? \Carbon\Carbon::parse($a->check_out)->format('H:i'):'-' }}</td>
                        <td class="px-4 py-3 font-semibold text-green-700">
                            {{ $hasPalmAccess && $a->palm_weight ? number_format($a->palm_weight,1).' kg':'-' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($a->status == 'tepat waktu')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Tepat Waktu</span>
                            @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Terlambat</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($a->checkout_photo_path)
                            <a href="{{ asset('storage/'.$a->checkout_photo_path) }}" 
                                target="_blank" class="text-blue-600 hover:underline">Lihat</a>
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
        <div class="h-40 flex items-center justify-center text-gray-400">
            Tidak ada data hasil panen
        </div>
        @endif

    </div>

</div>
@endsection
