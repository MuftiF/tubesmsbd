@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER SECTION -->
        <div class="mb-8 pb-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">{{ getGreeting() }}</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-[#2c5e4e]">Riwayat Absensi</h1>
                    <p class="text-sm text-gray-500 mt-1">Rekap kehadiran {{ Auth::user()->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium mt-1">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- SUMMARY STATS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            
            <!-- Total Absen -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Absen</p>
                        <p class="text-4xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->total() }}
                            <span class="text-base font-medium text-gray-400">kali</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#2c5e4e]"></span>
                    <span class="text-sm text-gray-500">Total data absensi</span>
                </div>
            </div>

            <!-- Hadir -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Hadir</p>
                        <p class="text-4xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->where('status','hadir')->count() }}
                            <span class="text-base font-medium text-gray-400">hari</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#eaf4f1] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#2c5e4e]"></span>
                    <span class="text-sm text-gray-500">Kehadiran tepat waktu</span>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Terlambat</p>
                        <p class="text-4xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->where('status','terlambat')->count() }}
                            <span class="text-base font-medium text-gray-400">hari</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#d4a373]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#d4a373]"></span>
                    <span class="text-sm text-gray-500">Perlu perhatian</span>
                </div>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Selesai</p>
                        <p class="text-4xl font-bold text-gray-800 mt-1">
                            {{ $riwayat->whereNotNull('check_out')->count() }}
                            <span class="text-base font-medium text-gray-400">kali</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <span class="text-sm text-gray-500">Checkout selesai</span>
                </div>
            </div>
        </div>

        <!-- FILTER SECTION -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 mb-6">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Filter Status</label>
                    <select id="filterStatus" onchange="filterTable()" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition">
                        <option value="all">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="izin">Izin</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Filter Bulan</label>
                    <input type="month" id="filterMonth" onchange="filterTable()" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Cari Tanggal</label>
                    <input type="date" id="filterDate" onchange="filterTable()" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-2 focus:ring-[#eaf4f1] transition">
                </div>
                <div>
                    <button onclick="exportToCSV()" class="inline-flex items-center gap-2 px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 rounded-xl text-sm font-medium transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-7 py-5 bg-white border-b-2 border-[#eaf4f1] flex items-center gap-3">
                <svg class="w-7 h-7 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-700">Daftar Absensi</h2>
            </div>
            
            <div class="p-7">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="attendanceTable">
                        <thead class="bg-gray-50 border-b-2 border-[#eaf4f1]">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Tanggal</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Hari</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Masuk</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Pulang</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Durasi</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Status</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase text-xs tracking-wide">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($riwayat as $absen)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition" data-status="{{ $absen->status }}" data-date="{{ \Carbon\Carbon::parse($absen->date)->format('Y-m-d') }}" data-month="{{ \Carbon\Carbon::parse($absen->date)->format('Y-m') }}">
                                <td class="px-5 py-4 font-semibold text-gray-900" data-label="Tanggal">
                                    {{ \Carbon\Carbon::parse($absen->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-4 text-gray-700" data-label="Hari">
                                    {{ \Carbon\Carbon::parse($absen->date)->translatedFormat('l') }}
                                </td>
                                <td class="px-5 py-4 font-semibold" data-label="Masuk">
                                    @if($absen->check_in)
                                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($absen->check_in)->format('H:i') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-semibold" data-label="Pulang">
                                    @if($absen->check_out)
                                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($absen->check_out)->format('H:i') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4" data-label="Durasi">
                                    @if($absen->check_in && $absen->check_out)
                                        @php
                                            $checkIn = \Carbon\Carbon::parse($absen->check_in);
                                            $checkOut = \Carbon\Carbon::parse($absen->check_out);
                                            $duration = $checkIn->diff($checkOut);
                                        @endphp
                                        <span class="text-sm text-gray-600">{{ $duration->h }} jam {{ $duration->i }} menit</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4" data-label="Status">
                                    @if($absen->status == 'hadir')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf4f1] text-[#2c5e4e]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Hadir
                                        </span>
                                    @elseif($absen->status == 'terlambat')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-[#d4a373]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Terlambat
                                        </span>
                                    @elseif($absen->status == 'izin')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            Izin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">{{ $absen->status }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-500" data-label="Keterangan">
                                    {{ $absen->note ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center">
                                    <div class="text-center py-8">
                                        <div class="text-6xl mb-3">📝</div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Data Absensi</h3>
                                        <p class="text-gray-500 mb-4">Silakan lakukan absensi terlebih dahulu untuk melihat riwayat</p>
                                        <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                            </svg>
                                            Absen Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                @if($riwayat->hasPages())
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} data
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            {{-- Previous --}}
                            @if($riwayat->onFirstPage())
                                <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">← Sebelumnya</span>
                            @else
                                <a href="{{ $riwayat->previousPageUrl() }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition">← Sebelumnya</a>
                            @endif

                            {{-- Pages --}}
                            @foreach($riwayat->getUrlRange(1, $riwayat->lastPage()) as $page => $url)
                                @if($page == $riwayat->currentPage())
                                    <span class="px-3 py-2 bg-[#2c5e4e] text-white rounded-lg text-sm font-semibold">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if($riwayat->hasMorePages())
                                <a href="{{ $riwayat->nextPageUrl() }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition">Selanjutnya →</a>
                            @else
                                <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm">Selanjutnya →</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- QUICK ACTION -->
        <div class="mt-10 text-center">
            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 bg-[#2c5e4e] hover:bg-[#1f4a3d] text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Absensi Sekarang
            </a>
        </div>

    </div>
</div>

<style>
    /* Responsive table for mobile */
    @media (max-width: 768px) {
        #attendanceTable thead {
            display: none;
        }
        
        #attendanceTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
        }
        
        #attendanceTable tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border: none;
        }
        
        #attendanceTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            margin-right: 1rem;
        }
    }
</style>

<script>
// Filter Function
function filterTable() {
    const status = document.getElementById('filterStatus').value;
    const month = document.getElementById('filterMonth').value;
    const date = document.getElementById('filterDate').value;
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        if (row.querySelector('td') && row.querySelector('td').getAttribute('colspan') !== '7') {
            const rowStatus = row.getAttribute('data-status');
            const rowDate = row.getAttribute('data-date');
            const rowMonth = row.getAttribute('data-month');
            
            let show = true;
            
            if (status !== 'all' && rowStatus !== status) {
                show = false;
            }
            
            if (month && rowMonth !== month) {
                show = false;
            }
            
            if (date && rowDate !== date) {
                show = false;
            }
            
            row.style.display = show ? '' : 'none';
        }
    });
    
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none' && row.querySelector('td') && row.querySelector('td').getAttribute('colspan') !== '7');
    let emptyMessage = document.getElementById('emptyFilterMessage');
    
    if (visibleRows.length === 0 && rows.length > 0) {
        if (!emptyMessage) {
            const tbody = document.getElementById('tableBody');
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'emptyFilterMessage';
            emptyRow.innerHTML = '<td colspan="7"><div class="text-center py-12"><div class="text-6xl mb-3">🔍</div><h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data</h3><p class="text-gray-500">Tidak ditemukan data absensi dengan filter yang dipilih</p></div></td>';
            tbody.appendChild(emptyRow);
        }
    } else if (emptyMessage) {
        emptyMessage.remove();
    }
}

// Export to CSV
function exportToCSV() {
    const rows = document.querySelectorAll('#tableBody tr');
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none' && row.querySelector('td') && row.querySelector('td').getAttribute('colspan') !== '7');
    
    if (visibleRows.length === 0) {
        alert('Tidak ada data untuk diexport');
        return;
    }
    
    let csvContent = "Tanggal,Hari,Masuk,Pulang,Durasi,Status,Keterangan\n";
    
    visibleRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const rowData = [];
            cells.forEach(cell => {
                let text = cell.innerText.trim();
                if (text.includes(',')) {
                    text = `"${text}"`;
                }
                rowData.push(text);
            });
            csvContent += rowData.join(',') + "\n";
        }
    });
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'riwayat_absensi.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection

@php
function getGreeting() {
    $hour = now()->hour;
    if ($hour < 12) return 'Selamat Pagi';
    if ($hour < 18) return 'Selamat Siang';
    return 'Selamat Malam';
}
@endphp