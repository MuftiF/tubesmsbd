@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Laporan Manager - Hasil Panen Sawit</h1>
        <p class="text-gray-500 text-sm mt-2">Dashboard analisis produktivitas dan kehadiran pekerja sawit (Manager View)</p>
    </div>

    <!-- FILTER BOX -->
    <!-- PERUBAHAN: Ganti route('admin.laporan') menjadi route('manager.laporan') -->
    <form method="GET" action="{{ route('manager.laporan') }}" 
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
                    <option value="user" {{ request('role')=='user' ? 'selected':'' }}>Kebun & Panen</option>
                    <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                    <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                    <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                </select>
            </div>
        </div>

        <!-- FILTER DATA TYPE -->
        <div class="mt-4">
            <label class="text-sm font-medium text-gray-600">Tampilkan Data</label>
            <select name="data_type" class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="today" {{ request('data_type', 'today') == 'today' ? 'selected' : '' }}>Hari Ini Saja</option>
                <option value="all" {{ request('data_type') == 'all' ? 'selected' : '' }}>Semua Data (Berdasarkan Filter Tanggal)</option>
            </select>
        </div>

        <div class="flex gap-3 mt-6">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Terapkan Filter
            </button>

            <!-- PERUBAHAN: Ganti route('admin.laporan') menjadi route('manager.laporan') -->
            <a href="{{ route('manager.laporan') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Reset
            </a>
        </div>
    </form>

    @php
        $selectedRole = request('role');
        $hasPalmAccess = !$selectedRole || $selectedRole == 'user';
        $dataType = request('data_type', 'today');
        $todayDate = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
    @endphp

    <!-- RINGKASAN HARI INI -->
    @if($dataType == 'today' && $hasPalmAccess)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 border border-blue-100 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-600 font-medium">Ringkasan Hari Ini</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $todayDate }}</h3>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-xs text-gray-500">Hadir</p>
                    <p class="text-xl font-bold text-green-600">{{ $todayAttendanceCount ?? 0 }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500">Panen</p>
                    <p class="text-xl font-bold text-yellow-600">{{ number_format($todayPalmWeight ?? 0, 1) }} kg</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500">Rata-rata</p>
                    <p class="text-xl font-bold text-blue-600">
                        @php
                            $avgToday = ($todayAttendanceCount > 0 && $todayPalmWeight > 0) 
                                ? number_format($todayPalmWeight / $todayAttendanceCount, 1) 
                                : 0;
                        @endphp
                        {{ $avgToday }} kg/orang
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Total Tim</p>
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

    <!-- TABLE - DETAIL KEHADIRAN & PANEN -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 mb-10">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">
                @if($dataType == 'today')
                Detail Kehadiran & Panen Hari Ini
                @else
                Detail Kehadiran & Panen
                @endif
            </h3>
            
            @if($dataType == 'today')
            <div class="text-sm text-gray-500 bg-blue-50 px-3 py-1 rounded-lg">
                {{ $todayDate }}
            </div>
            @else
            <div class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-lg">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </div>
            @endif
        </div>

        @if($detailedAttendances->count())
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm border-b">
                        @if($dataType == 'all')
                        <th class="px-4 py-3">Tanggal</th>
                        @endif
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Check In</th>
                        <th class="px-4 py-3">Check Out</th>
                        <th class="px-4 py-3">Berat Panen</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Foto Panen</th>
                    </tr>
                </thead>

                <tbody class="text-sm">
                    @foreach($detailedAttendances as $a)
                        @php
                            // Ambil data panen berdasarkan attendance
                            $panen = \App\Models\CatatanPanen::where('id_pegawai', $a->user_id)
                                ->whereDate('tanggal', $a->date)
                                ->first();
                        @endphp
                        
                        <tr class="border-b hover:bg-gray-50">
                            @if($dataType == 'all')
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                            @endif
                            
                            <!-- NAMA -->
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $a->user?->name ?? 'Nama Tidak Diketahui' }}</td>
                            
                            <!-- ROLE -->
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-600 text-xs capitalize">
                                    {{ $a->user?->role ?? 'unknown' }}
                                </span>
                            </td>
                            
                            <!-- CHECK IN -->
                            <td class="px-4 py-3 text-gray-700">
                                @if($a->check_in)
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($a->check_in)->format('H:i') }}</span>
                                        @if($a->check_in_location)
                                        <span class="text-xs text-gray-400 truncate max-w-[120px]" title="{{ $a->check_in_location }}">
                                            {{ Str::limit($a->check_in_location, 20) }}
                                        </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <!-- CHECK OUT -->
                            <td class="px-4 py-3 text-gray-700">
                                @if($a->check_out)
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($a->check_out)->format('H:i') }}</span>
                                        @if($a->check_out_location)
                                        <span class="text-xs text-gray-400 truncate max-w-[120px]" title="{{ $a->check_out_location }}">
                                            {{ Str::limit($a->check_out_location, 20) }}
                                        </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-yellow-500 font-medium text-sm">Belum Checkout</span>
                                @endif
                            </td>
                            
                            <!-- BERAT PANEN -->
                            <td class="px-4 py-3">
                                @if($hasPalmAccess && $panen && $panen->berat_kg)
                                    <div class="font-semibold text-green-700">{{ number_format($panen->berat_kg, 1) }} kg</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <!-- KETERANGAN PANEN -->
                            <td class="px-4 py-3 text-gray-700">
                                @if($panen && $panen->keterangan)
                                    <span class="text-xs">{{ $panen->keterangan }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            
                            <!-- STATUS ABSENSI -->
                            <td class="px-4 py-3">
                                @if($a->status == 'tepat waktu')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Tepat Waktu</span>
                                @elseif($a->status == 'terlambat')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Terlambat</span>
                                @elseif($a->status == 'cuti')
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">Cuti</span>
                                @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">Belum Absen</span>
                                @endif
                            </td>
                            
                            <!-- FOTO PANEN -->
                            <td class="px-4 py-3">
                                @if($panen && $panen->foto_panen)
                                <a href="{{ asset('storage/'.$panen->foto_panen) }}" 
                                    target="_blank" 
                                    class="inline-flex items-center gap-1 text-blue-600 hover:underline text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Lihat
                                </a>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- SUMMARY TABLE -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">
                        @if($dataType == 'today')
                        Total Kehadiran Hari Ini: 
                        <span class="font-bold text-blue-700">{{ $detailedAttendances->total() }} orang</span>
                        @else
                        Total Data: 
                        <span class="font-bold text-blue-700">{{ $detailedAttendances->total() }} data</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Data diupdate terakhir: {{ now()->format('H:i') }}
                    </p>
                </div>
                
                @if($dataType == 'today' && $detailedAttendances->total() > 0)
                <div class="text-right">
                    <p class="text-sm text-gray-600">
                        Belum Checkout: 
                        <span class="font-bold text-yellow-600">
                            {{ $detailedAttendances->whereNull('check_out')->count() }} orang
                        </span>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-4">
            {{ $detailedAttendances->links() }}
        </div>

        @else
        <!-- TIDAK ADA DATA -->
        <div class="h-60 flex flex-col items-center justify-center text-gray-400">
            @if($dataType == 'today')
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg font-medium mb-1">Belum ada absensi hari ini</p>
            <p class="text-sm">Data kehadiran akan muncul setelah pekerja melakukan check-in</p>
            @else
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg font-medium mb-1">Tidak ada data untuk periode ini</p>
            <p class="text-sm">Coba ubah filter tanggal atau role untuk melihat data</p>
            @endif
        </div>
        @endif

    </div>

</div>
@endsection