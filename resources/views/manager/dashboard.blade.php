@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Selamat Datang</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Manager Dashboard</h1>
                    <p class="text-sm text-gray-500 mt-1">Monitoring produktivitas dan aktivitas tim kebun sawit</p>
                </div>
                <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                    PT. Sipirok Indah
                </span>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Absensi Hari Ini</p>
                        <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($hadirHariIni ?? 0) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total Kehadiran</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Total Tim</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($totalTim ?? 0) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Tim Aktif</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Produksi Hari Ini</p>
                        <p class="text-3xl font-bold text-[#2c5e4e]">{{ number_format($produksiHariIni ?? 0, 1) }} <span class="text-sm font-medium text-gray-400">kg</span></p>
                        <p class="text-xs text-gray-400 mt-1">Total Panen</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#eaf4f1] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-[#2c5e4e] rounded-2xl p-5 transition-all hover:bg-[#1f4a3d] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-white/70 mb-2">Alpha</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($totalAlpha ?? 0) }}</p>
                        <p class="text-xs text-white/60 mt-1">Tidak Hadir</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
            {{-- Line Chart --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
                <div class="flex flex-wrap justify-between items-center gap-3 mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Produktivitas Harian</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Dalam satuan kilogram (kg)</p>
                    </div>
                    <select id="filterType" class="border border-gray-200 rounded-full px-4 py-1.5 text-xs bg-white text-gray-600 focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                        <option value="7">7 Hari</option>
                        <option value="30">1 Bulan</option>
                        <option value="365">1 Tahun</option>
                    </select>
                </div>
                <div class="h-[280px] relative">
                    <canvas id="productivityChart"></canvas>
                </div>
            </div>

            {{-- Donut Chart --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Status Absensi Hari Ini</h3>
                <p class="text-xs text-gray-400 mb-4">Distribusi kehadiran tim</p>
                <div class="h-[180px] relative">
                    <canvas id="attendanceChart"></canvas>
                </div>
                <div class="mt-5 space-y-2.5">
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#2c5e4e]"></span>
                            <span class="text-gray-600">Hadir</span>
                        </div>
                        <span class="font-semibold text-gray-800">{{ $hadirHariIni ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#d4a373]"></span>
                            <span class="text-gray-600">Terlambat</span>
                        </div>
                        <span class="font-semibold text-gray-800">{{ $totalTerlambat ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                            <span class="text-gray-600">Alpha</span>
                        </div>
                        <span class="font-semibold text-gray-800">{{ $totalAlpha ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('manager.laporan') }}" class="bg-white rounded-2xl p-5 border border-gray-200 flex items-center justify-between hover:border-[#eaf4f1] hover:shadow-sm transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-[#eaf4f1] rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">Laporan</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Lihat laporan harian</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            <a href="{{ route('manager.log') }}" class="bg-white rounded-2xl p-5 border border-gray-200 flex items-center justify-between hover:border-[#eaf4f1] hover:shadow-sm transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-[#eaf4f1] rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">Log Absensi</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Aktivitas pegawai</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            <a href="{{ route('manager.pegawai') }}" class="bg-white rounded-2xl p-5 border border-gray-200 flex items-center justify-between hover:border-[#eaf4f1] hover:shadow-sm transition-all hover:-translate-y-0.5">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 bg-[#eaf4f1] rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">Pegawai</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Kelola data tim</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        {{-- Top Performer & Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
            {{-- Top Performer --}}
            <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-700">Top Performer Hari Ini</h3>
                <p class="text-xs text-gray-400 mt-1 mb-4">Berdasarkan hasil produksi</p>
                <div class="space-y-3">
                    @forelse($topPerformers as $index => $performer)
                    <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 hover:border-[#eaf4f1] transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-[#eaf4f1] rounded-xl flex items-center justify-center font-bold text-[#2c5e4e] text-sm">{{ $index + 1 }}</div>
                            <div>
                                <h5 class="font-semibold text-gray-800 text-sm">{{ $performer->name }}</h5>
                                <p class="text-xs text-gray-400">{{ ucfirst($performer->role) }}</p>
                            </div>
                        </div>
                        <div class="font-bold text-[#2c5e4e] text-sm">{{ number_format($performer->total_produksi ?? 0, 1) }} kg</div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        <p class="font-semibold text-sm">Belum ada data produksi hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Team Activity --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
                <div class="flex flex-wrap justify-between items-center gap-2 mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Aktivitas Tim Terbaru</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Monitoring check in dan produksi tim</p>
                    </div>
                    <a href="{{ route('manager.log') }}" class="text-xs font-semibold text-[#2c5e4e] hover:underline flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentActivities as $activity)
                    @php
                        $prod = $activity->produksi_harian ?? 0;
                        $efficiency = $prod > 20 ? 'Tinggi' : ($prod > 10 ? 'Sedang' : 'Rendah');
                        $effClass = $prod > 20 ? 'bg-[#eaf4f1] text-[#2c5e4e]' : ($prod > 10 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700');
                    @endphp
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 hover:border-[#eaf4f1] transition">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-[#eaf4f1] rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-800 text-sm">{{ $activity->user->name }}</h5>
                                <span class="inline-block bg-[#eaf4f1] text-[#2c5e4e] text-xs font-medium px-2 py-0.5 rounded-full mt-0.5">{{ ucfirst($activity->user->role) }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ \Carbon\Carbon::parse($activity->check_in)->format('H:i') }} WIB
                            </span>
                            @if($activity->produksi_harian)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                {{ number_format($activity->produksi_harian, 1) }} kg
                            </span>
                            @endif
                        </div>
                        @if($activity->produksi_harian)
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $effClass }}">{{ $efficiency }}</span>
                        @endif
                    </div>
                    @empty
                    <div class="py-8 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="font-semibold text-sm">Belum Ada Aktivitas</p>
                        <span class="text-xs">Aktivitas terbaru tim akan muncul di sini</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Export Data --}}
        <div class="bg-white rounded-2xl p-5 md:p-6 border border-gray-200 shadow-sm">
            <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Export Data Aktivitas</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Download laporan absensi dan aktivitas pegawai</p>
                </div>
                <div class="w-10 h-10 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </div>
            </div>
            <form action="{{ route('export.all') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Dari Tanggal</label>
                        <input type="date" name="from" value="{{ date('Y-m-d', strtotime('-1 week')) }}"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="to" value="{{ date('Y-m-d') }}"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-4 py-2.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('export.all.everything') }}"
                            class="w-full bg-gray-800 hover:bg-gray-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                            Export Semua Data
                        </a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const allData = @json($produktivitasData ?? []);

    function formatDate(dateString, type = '7') {
        const date = new Date(dateString);
        if (type == '365') return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    }

    function filterData(days) {
        const now = new Date();
        return allData.filter(item => {
            const itemDate = new Date(item.tanggal);
            const diffDays = (now - itemDate) / (1000 * 60 * 60 * 24);
            return diffDays <= days;
        });
    }

    const ctx = document.getElementById('productivityChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(44,94,78,0.25)');
    gradient.addColorStop(1, 'rgba(44,94,78,0.02)');

    let productivityChart;

    function renderChart(days = 7) {
        const filtered = filterData(days);
        const labels = filtered.map(item => formatDate(item.tanggal, days));
        const totals = filtered.map(item => item.total_produksi);

        if (productivityChart) productivityChart.destroy();

        productivityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Produktivitas',
                    data: totals,
                    borderColor: '#2c5e4e',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.45,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2c5e4e',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a2e25',
                        padding: 10,
                        titleColor: '#fff',
                        bodyColor: '#a7c4bb',
                        callbacks: { label: (ctx) => ctx.parsed.y + ' kg' }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { color: '#9ca3af', callback: (val) => val + ' kg', font: { size: 11 } } },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } }
                }
            }
        });
    }

    renderChart(7);
    document.getElementById('filterType').addEventListener('change', function() { renderChart(parseInt(this.value)); });

    new Chart(document.getElementById('attendanceChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Alpha'],
            datasets: [{
                data: [{{ $hadirHariIni ?? 0 }}, {{ $totalTerlambat ?? 0 }}, {{ $totalAlpha ?? 0 }}],
                backgroundColor: ['#2c5e4e', '#d4a373', '#ef4444'],
                borderWidth: 0,
                cutout: '72%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.parsed}` } }
            }
        }
    });
});
</script>
@endsection