@extends('layouts.app')

@section('content')
<div class="bg-[#f8f6f2] min-h-screen font-['Inter',sans-serif] p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="relative pl-4 mb-8">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2d6a4f] rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#1e1e1e] tracking-tight">Laporan Hasil Panen Sawit</h1>
            <p class="text-sm text-stone-500 mt-1">Dashboard analisis produktivitas dan kehadiran pekerja sawit</p>
        </div>

        {{-- Filter Box --}}
        <form method="GET" action="{{ route('manager.laporan') }}" class="bg-white rounded-2xl p-5 md:p-6 mb-8 border border-stone-200 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wide text-stone-400 mb-4">Filter Laporan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-stone-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-stone-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-stone-600 mb-1">Role</label>
                    <select name="role" class="w-full border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role')=='user' ? 'selected':'' }}>Kebun & Panen</option>
                        <option value="security" {{ request('role')=='security' ? 'selected':'' }}>Security</option>
                        <option value="cleaning" {{ request('role')=='cleaning' ? 'selected':'' }}>Cleaning</option>
                        <option value="kantoran" {{ request('role')=='kantoran' ? 'selected':'' }}>Kantoran</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-stone-600 mb-1">Tampilkan Data</label>
                <select name="data_type" class="w-full md:w-64 border border-stone-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                    <option value="today" {{ request('data_type', 'today') == 'today' ? 'selected' : '' }}>Hari Ini Saja</option>
                    <option value="all" {{ request('data_type') == 'all' ? 'selected' : '' }}>Semua Data (Berdasarkan Filter Tanggal)</option>
                </select>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-[#2d6a4f] text-white px-5 py-2 rounded-xl font-semibold text-sm hover:bg-[#235c44] transition">Terapkan Filter</button>
                <a href="{{ route('manager.laporan') }}" class="bg-transparent text-stone-500 px-4 py-2 rounded-xl font-medium text-sm border border-stone-200 hover:border-stone-300 hover:text-stone-700 transition">Reset</a>
            </div>
        </form>

        @php
            $selectedRole = request('role');
            $hasPalmAccess = !$selectedRole || $selectedRole == 'user';
            $dataType = request('data_type', 'today');
            $todayDate = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
        @endphp

        {{-- Today Banner --}}
        @if($dataType == 'today' && $hasPalmAccess)
        <div class="bg-gradient-to-r from-[#1b4332] to-[#2d6a4f] rounded-2xl p-5 mb-6 text-white flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="text-[0.7rem] font-semibold uppercase tracking-wide text-emerald-200 mb-1">Ringkasan Hari Ini</div>
                <div class="text-base md:text-lg font-bold">{{ $todayDate }}</div>
            </div>
            <div class="flex flex-wrap gap-6">
                <div class="text-center">
                    <div class="text-[0.65rem] font-semibold uppercase text-white/60 mb-1">Hadir</div>
                    <div class="text-xl md:text-2xl font-bold text-white">{{ $todayAttendanceCount ?? 0 }}</div>
                </div>
                <div class="text-center">
                    <div class="text-[0.65rem] font-semibold uppercase text-white/60 mb-1">Panen</div>
                    <div class="text-xl md:text-2xl font-bold text-white">{{ number_format($todayPalmWeight ?? 0, 1) }} kg</div>
                </div>
                <div class="text-center">
                    <div class="text-[0.65rem] font-semibold uppercase text-white/60 mb-1">Rata-rata</div>
                    @php
                        $avgToday = ($todayAttendanceCount > 0 && $todayPalmWeight > 0) 
                            ? number_format($todayPalmWeight / $todayAttendanceCount, 1) 
                            : 0;
                    @endphp
                    <div class="text-xl md:text-2xl font-bold text-white">{{ $avgToday }} kg/org</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl md:rounded-2xl p-4 border border-stone-200">
                <div class="text-[0.7rem] font-semibold uppercase tracking-wide text-stone-400 mb-2">Total Pekerja</div>
                <div class="text-xl md:text-3xl font-bold text-stone-800">{{ number_format($totalPegawai) }}</div>
            </div>
            <div class="bg-white rounded-xl md:rounded-2xl p-4 border border-stone-200">
                <div class="text-[0.7rem] font-semibold uppercase tracking-wide text-stone-400 mb-2">Total Berat Sawit</div>
                <div class="text-xl md:text-3xl font-bold text-stone-800">{{ $hasPalmAccess ? number_format($totalPalmWeight, 1) : '-' }}</div>
                @if($hasPalmAccess)<div class="text-xs font-medium text-stone-400 mt-1">kilogram</div>@endif
            </div>
            <div class="bg-white rounded-xl md:rounded-2xl p-4 border border-stone-200">
                <div class="text-[0.7rem] font-semibold uppercase tracking-wide text-stone-400 mb-2">Rata-rata Panen</div>
                <div class="text-xl md:text-3xl font-bold text-stone-800">{{ $hasPalmAccess ? number_format($averagePalmWeight, 1) : '-' }}</div>
                @if($hasPalmAccess)<div class="text-xs font-medium text-stone-400 mt-1">kg / pekerja</div>@endif
            </div>
            @if($dataType == 'today')
            <div class="bg-white rounded-xl md:rounded-2xl p-4 border border-stone-200">
                <div class="text-[0.7rem] font-semibold uppercase tracking-wide text-stone-400 mb-2">Total Kehadiran</div>
                <div class="text-xl md:text-3xl font-bold text-stone-800">{{ number_format($totalHadir ?? 0) }}</div>
                <div class="text-xs font-medium text-stone-400 mt-1">hari ini</div>
            </div>
            @endif
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
            @if($hasPalmAccess)
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200">
                <h3 class="text-sm font-bold text-stone-700 mb-5">Hasil Panen 7 Hari Terakhir</h3>
                @if($dailyPalmWeight->count())
                    @php $maxWeight = $dailyPalmWeight->max('total_weight') ?: 1; @endphp
                    <div class="space-y-4">
                        @foreach($dailyPalmWeight as $daily)
                        <div>
                            <div class="flex justify-between text-xs text-stone-500 mb-1">
                                <span>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</span>
                                <span class="font-semibold text-stone-700">{{ number_format($daily->total_weight, 1) }} kg</span>
                            </div>
                            <div class="w-full bg-stone-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-[#2d6a4f] h-full rounded-full" style="width: {{ ($daily->total_weight / $maxWeight) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-36 flex items-center justify-center text-stone-400 text-sm">Tidak ada data panen</div>
                @endif
            </div>
            @endif

            <div class="bg-white rounded-2xl p-5 md:p-6 border border-stone-200">
                <h3 class="text-sm font-bold text-stone-700 mb-5">Kehadiran 7 Hari Terakhir</h3>
                @if($dailyAttendance->count())
                    <div class="space-y-4">
                        @foreach($dailyAttendance as $daily)
                        <div>
                            <div class="flex justify-between text-xs text-stone-500 mb-1">
                                <span>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</span>
                                <span class="font-semibold text-stone-700">{{ number_format($daily->total) }} pekerja</span>
                            </div>
                            <div class="w-full bg-stone-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full" style="width: {{ $totalPegawai > 0 ? ($daily->total / $totalPegawai) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-36 flex items-center justify-center text-stone-400 text-sm">Tidak ada data kehadiran</div>
                @endif
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 p-5 pb-3 border-b border-stone-100">
                <h3 class="text-sm font-bold text-stone-800">
                    @if($dataType == 'today')
                        Detail Kehadiran & Panen Hari Ini
                    @else
                        Detail Kehadiran & Panen
                    @endif
                </h3>
                @if($dataType == 'today')
                    <span class="bg-[#f8f6f2] px-3 py-1 rounded-full text-xs font-medium text-stone-500">{{ $todayDate }}</span>
                @else
                    <span class="bg-[#f8f6f2] px-3 py-1 rounded-full text-xs font-medium text-stone-500">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </span>
                @endif
            </div>

            @if($detailedAttendances->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#fafaf8] border-b border-stone-200">
                        <tr>
                            @if($dataType == 'all')
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Tanggal</th>
                            @endif
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Nama Karyawan</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Role</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Absen Masuk</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Absen Keluar</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Berat Panen</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Catatan</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Status</th>
                            <th class="text-left px-4 py-3 text-[0.7rem] font-bold uppercase tracking-wide text-stone-500">Bukti Absensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailedAttendances as $a)
                            @php
                                $panen = \App\Models\CatatanPanen::where('id_pegawai', $a->user_id)
                                    ->whereDate('tanggal', $a->date)
                                    ->first();
                            @endphp
                            <tr class="border-b border-stone-100 hover:bg-[#fefcf7] transition">
                                @if($dataType == 'all')
                                <td class="px-4 py-3 text-sm text-stone-600">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                                @endif
                                <td class="px-4 py-3 text-sm font-semibold text-stone-800">{{ $a->user?->name ?? 'Nama Tidak Diketahui' }}</td>
                                <td class="px-4 py-3"><span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-[#2d6a4f]">{{ $a->user?->role ?? 'unknown' }}</span></td>
                                <td class="px-4 py-3 text-sm font-medium text-stone-800">
                                    @if($a->check_in)
                                        {{ \Carbon\Carbon::parse($a->check_in)->format('H:i') }} <span class="text-xs text-stone-400">WIB</span>
                                    @else
                                        <span class="text-stone-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($a->check_out)
                                        <span class="text-sm font-medium text-stone-800">{{ \Carbon\Carbon::parse($a->check_out)->format('H:i') }} <span class="text-xs text-stone-400">WIB</span></span>
                                    @else
                                        @if($a->check_in)
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">Belum Checkout</span>
                                        @else
                                            <span class="text-stone-400">-</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($hasPalmAccess && $panen && $panen->berat_kg)
                                        <span class="font-semibold text-[#2d6a4f] text-sm">{{ number_format($panen->berat_kg, 1) }} kg</span>
                                    @else
                                        <span class="text-stone-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-stone-500">
                                    @if($a->note)
                                        {{ $a->note }}
                                    @else
                                        <span class="text-stone-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($a->status == 'tepat waktu')
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Hadir</span>
                                    @elseif($a->status == 'terlambat')
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Terlambat</span>
                                    @elseif($a->status == 'cuti')
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">Cuti</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-600">Alpha</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($a->checkout_photo_path)
                                        <a href="{{ asset('storage/'.$a->checkout_photo_path) }}" target="_blank" class="inline-flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-full text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">Lihat</a>
                                    @elseif($panen && $panen->foto_panen)
                                        <a href="{{ asset('storage/'.$panen->foto_panen) }}" target="_blank" class="inline-flex items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-full text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">Lihat</a>
                                    @else
                                        <span class="text-stone-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-stone-100">
                {{ $detailedAttendances->links() }}
            </div>
            @else
            <div class="py-12 text-center text-stone-400">
                @if($dataType == 'today')
                    <p class="font-semibold text-sm text-stone-500">Belum ada absensi hari ini</p>
                    <span class="text-xs">Data kehadiran akan muncul setelah pekerja melakukan check-in</span>
                @else
                    <p class="font-semibold text-sm text-stone-500">Tidak ada data untuk periode ini</p>
                    <span class="text-xs">Coba ubah filter tanggal atau role untuk melihat data</span>
                @endif
            </div>
            @endif
        </div>

    </div>
</div>
@endsection