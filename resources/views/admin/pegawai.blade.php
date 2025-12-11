@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10">

    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight">Data Pegawai</h1>
        <p class="text-gray-500 text-sm mt-2">Daftar lengkap pegawai perusahaan</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-10">

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Total Pegawai</p>
            <h3 class="text-3xl font-bold text-blue-700 mt-1">{{ $pegawai->count() }}</h3>
        </div>

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Kebun & Panen</p>
            <h3 class="text-3xl font-bold text-green-700 mt-1">{{ $pegawai->where('role','user')->count() }}</h3>
        </div>

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Security</p>
            <h3 class="text-3xl font-bold text-blue-700 mt-1">{{ $pegawai->where('role','security')->count() }}</h3>
        </div>

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Cleaning</p>
            <h3 class="text-3xl font-bold text-yellow-600 mt-1">{{ $pegawai->where('role','cleaning')->count() }}</h3>
        </div>

        <div class="bg-white shadow-md rounded-xl p-5 border border-gray-100 text-center">
            <p class="text-sm text-gray-500">Kantoran</p>
            <h3 class="text-3xl font-bold text-red-600 mt-1">{{ $pegawai->where('role','kantoran')->count() }}</h3>
        </div>

    </div>

    <!-- Search & Filter -->
    <div class="bg-white border border-gray-100 shadow-lg rounded-xl p-6 mb-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Cari nama atau nomor HP pegawai..." 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <div>
                <select 
                    id="roleFilter" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                >
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

    <!-- Table -->
    <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Pegawai</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr class="text-gray-600 uppercase text-xs">
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">No HP</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Bergabung</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody id="employeeTable" class="divide-y divide-gray-200">

                    @foreach($pegawai as $i => $emp)
                    <tr class="hover:bg-gray-50 employee-row" data-role="{{ $emp->role }}">
                        
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $i + 1 }}</td>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                </div>
                                <span class="ml-4 font-medium text-gray-900">{{ $emp->name }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            @if(!empty($emp->phone) || !empty($emp->no_hp))
                                {{ $emp->phone ?? $emp->no_hp }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($emp->role=='admin') bg-red-100 text-red-700
                                @elseif($emp->role=='manager') bg-purple-100 text-purple-700
                                @elseif($emp->role=='user') bg-green-100 text-green-700
                                @elseif($emp->role=='security') bg-blue-100 text-blue-700
                                @elseif($emp->role=='cleaning') bg-yellow-100 text-yellow-700
                                @elseif($emp->role=='kantoran') bg-orange-100 text-orange-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                @if($emp->role == 'user') Kebun & Panen
                                @elseif($emp->role == 'security') Security
                                @elseif($emp->role == 'cleaning') Cleaning
                                @elseif($emp->role == 'kantoran') Administrasi
                                @else {{ ucfirst($emp->role) }}
                                @endif
                            </span>
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $emp->created_at ? $emp->created_at->format('d M Y') : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                Aktif
                            </span>
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

        @if($pegawai->isEmpty())
        <div class="px-6 py-12 text-center text-gray-500">
            <div class="text-4xl">👥</div>
            <div class="mt-2">Tidak ada data pegawai</div>
        </div>
        @endif

    </div>

</div>

<!-- FILTER SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('searchInput');
    const roleFilter  = document.getElementById('roleFilter');
    const rows        = document.querySelectorAll('.employee-row');

    function filter() {
        const search = searchInput.value.toLowerCase();
        const role   = roleFilter.value;

        rows.forEach(row => {
            const name  = row.querySelector('.font-medium.text-gray-900')?.textContent.toLowerCase() || '';
            const phone = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const r     = row.dataset.role;

            const matchSearch = name.includes(search) || phone.includes(search);
            const matchRole   = !role || r === role;

            row.style.display = (matchSearch && matchRole) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filter);
    roleFilter.addEventListener('change', filter);

});
</script>

@endsection
