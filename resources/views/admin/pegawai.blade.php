@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 pb-5 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Admin</p>
                <h1 class="text-2xl md:text-3xl font-bold text-[#2c5e4e]">Data Pegawai</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar lengkap pegawai perusahaan</p>
            </div>
            <span class="inline-block px-4 py-1.5 bg-[#eaf4f1] text-[#2c5e4e] rounded-full text-sm font-medium self-start sm:self-center">
                PT. Sipirok Indah
            </span>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-5 mb-8">
        <div class="bg-[#2c5e4e] rounded-2xl p-5 transition-all hover:bg-[#1f4a3d] hover:shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-white/70 mb-2">Total Pegawai</p>
            <p class="text-3xl font-bold text-white">{{ $pegawai->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Kebun & Panen</p>
            <p class="text-3xl font-bold text-[#2c5e4e]">{{ $pegawai->where('role','user')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Security</p>
            <p class="text-3xl font-bold text-gray-800">{{ $pegawai->where('role','security')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Cleaning</p>
            <p class="text-3xl font-bold text-gray-800">{{ $pegawai->where('role','cleaning')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-200 transition-all hover:border-[#eaf4f1] hover:shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Kantoran</p>
            <p class="text-3xl font-bold text-gray-800">{{ $pegawai->where('role','kantoran')->count() }}</p>
        </div>
    </div>

    {{-- Filter Box --}}
    <div class="bg-white rounded-2xl p-5 md:p-6 mb-6 border border-gray-200 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Cari Pegawai</label>
                <input type="text" id="searchInput" placeholder="Cari nama atau nomor HP pegawai..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Filter Role</label>
                <select id="roleFilter"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-[#2c5e4e] focus:ring-1 focus:ring-[#2c5e4e]">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="user">Kebun & Panen</option>
                    <option value="security">Security</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="kantoran">Kantoran</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
            <div class="w-8 h-8 bg-[#eaf4f1] rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-[#2c5e4e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-700">Daftar Pegawai</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">No</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Nama</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">No HP</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Role</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Bergabung</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    @foreach($pegawai as $i => $emp)
                    <tr class="employee-row border-b border-gray-100 hover:bg-gray-50 transition"
                        data-role="{{ $emp->role }}"
                        data-name="{{ strtolower($emp->name) }}"
                        data-phone="{{ strtolower($emp->phone ?? $emp->no_hp ?? '') }}">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-600">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-[#eaf4f1] rounded-xl flex items-center justify-center font-bold text-[#2c5e4e] text-sm flex-shrink-0">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">{{ $emp->name }}</div>
                                    <div class="text-xs text-gray-400">ID: {{ $emp->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            @if(!empty($emp->phone) || !empty($emp->no_hp))
                                {{ $emp->phone ?? $emp->no_hp }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf4f1] text-[#2c5e4e]">
                                @if($emp->role == 'user') Kebun & Panen
                                @elseif($emp->role == 'security') Security
                                @elseif($emp->role == 'cleaning') Cleaning
                                @elseif($emp->role == 'kantoran') Staff Kantor
                                @else {{ ucfirst($emp->role) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $emp->created_at ? $emp->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pegawai->isEmpty())
        <div class="py-14 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="font-semibold text-sm text-gray-500">Tidak ada data pegawai</p>
        </div>
        @endif
    </div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const rows = document.querySelectorAll('.employee-row');

    function filter() {
        const search = searchInput.value.toLowerCase();
        const role = roleFilter.value;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const phone = row.dataset.phone || '';
            const r = row.dataset.role;

            const matchSearch = name.includes(search) || phone.includes(search);
            const matchRole = !role || r === role;

            row.style.display = (matchSearch && matchRole) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filter);
    if (roleFilter) roleFilter.addEventListener('change', filter);
});
</script>
@endsection